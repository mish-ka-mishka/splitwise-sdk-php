<?php

declare(strict_types=1);

namespace MKaverin\SplitwiseSDK\SDKBuilder\Generators;

use Crescat\SaloonSdkGenerator\Data\Generator\ApiSpecification;
use Crescat\SaloonSdkGenerator\Data\Generator\Endpoint;
use Crescat\SaloonSdkGenerator\Data\Generator\Parameter;
use Crescat\SaloonSdkGenerator\Generator;
use Crescat\SaloonSdkGenerator\Helpers\NameHelper;
use Nette\InvalidStateException;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\Literal;
use Nette\PhpGenerator\Method;
use Nette\PhpGenerator\PhpFile;
use Saloon\Http\Response;

use function in_array;
use function sprintf;

class ResourceGenerator extends Generator
{
    public function generate(ApiSpecification $specification): PhpFile|array
    {
        return $this->generateResourceClasses($specification);
    }

    /**
     * @param array|Endpoint[] $endpoints
     */
    public function generateResourceClass(string $resourceName, array $endpoints): PhpFile
    {
        $classType = new ClassType($resourceName);

        $classType->setExtends("{$this->config->namespace}\\Resource");

        $classFile = new PhpFile();
        $namespace = $classFile
            ->addNamespace("{$this->config->namespace}\\{$this->config->resourceNamespaceSuffix}")
            ->addUse("{$this->config->namespace}\\Resource");

        $duplicateCounter = 1;

        foreach ($endpoints as $endpoint) {
            $requestClassName = NameHelper::resourceClassName($endpoint->name);
            $methodName = NameHelper::safeVariableName($requestClassName);
            $requestClassNameAlias = $requestClassName === $resourceName ? "{$requestClassName}Request" : null;
            $requestClassFQN = "{$this->config->namespace}\\{$this->config->requestNamespaceSuffix}\\{$resourceName}\\{$requestClassName}";

            $namespace
                ->addUse(Response::class)
                ->addUse(
                    name: $requestClassFQN,
                    alias: $requestClassNameAlias,
                );

            try {
                $method = $classType->addMethod($methodName);
            } catch (InvalidStateException) {
                // TODO: handle more gracefully in the future
                $deduplicatedMethodName = NameHelper::safeVariableName(
                    sprintf('%s%s', $methodName, 'Duplicate' . $duplicateCounter)
                );
                $duplicateCounter++;
                dump("DUPLICATE: {$requestClassName} -> {$deduplicatedMethodName}");

                $method = $classType
                    ->addMethod($deduplicatedMethodName)
                    ->addComment('@todo Fix duplicated method name');
            }

            $method->setReturnType(Response::class);

            $args = [];

            foreach ($endpoint->pathParameters as $parameter) {
                $this->addPropertyToMethod($method, $parameter);
                $args[] = new Literal(sprintf('$%s', NameHelper::safeVariableName($parameter->name)));
            }

            foreach ($endpoint->bodyParameters as $parameter) {
                if (in_array($parameter->name, $this->config->ignoredBodyParams, true)) {
                    continue;
                }

                $this->addPropertyToMethod($method, $parameter);
                $args[] = new Literal(sprintf('$%s', NameHelper::safeVariableName($parameter->name)));
            }

            foreach ($endpoint->queryParameters as $parameter) {
                if (in_array($parameter->name, $this->config->ignoredQueryParams, true)) {
                    continue;
                }
                $this->addPropertyToMethod($method, $parameter);
                $args[] = new Literal(sprintf('$%s', NameHelper::safeVariableName($parameter->name)));
            }

            $method->setBody(sprintf('return $this->connector->send(new %s(%s));', $requestClassNameAlias ?? $requestClassName, implode(', ', $args)));
        }

        $namespace->add($classType);

        return $classFile;
    }

    /**
     * @return array|PhpFile[]
     */
    protected function generateResourceClasses(ApiSpecification $specification): array
    {
        $classes = [];

        $groupedByCollection = collect($specification->endpoints)->groupBy(fn (Endpoint $endpoint): string => NameHelper::resourceClassName(
            $endpoint->collection !== null && $endpoint->collection !== '' && $endpoint->collection !== '0' ? $endpoint->collection : $this->config->fallbackResourceName // @phpstan-ignore-line
        ));

        foreach ($groupedByCollection as $collection => $items) {
            $classes[] = $this->generateResourceClass($collection, $items->toArray());
        }

        return $classes;
    }

    protected function addPropertyToMethod(Method $method, Parameter $parameter): Method
    {
        $name = NameHelper::safeVariableName($parameter->name);

        $property
            = $method
                ->addComment(
                    trim(sprintf('@param %s $%s %s', $parameter->type, $name, $parameter->description))
                )
                ->addParameter($name);

        $property->setType($parameter->type);

        if ($parameter->nullable) {
            $property->setNullable()
                ->setDefaultValue(null);
        }

        return $method;
    }
}
