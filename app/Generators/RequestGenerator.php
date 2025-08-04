<?php

declare(strict_types=1);

namespace MKaverin\SplitwiseSDK\SDKBuilder\Generators;

use Crescat\SaloonSdkGenerator\Data\Generator\ApiSpecification;
use Crescat\SaloonSdkGenerator\Data\Generator\Endpoint;
use Crescat\SaloonSdkGenerator\Data\Generator\Parameter;
use Crescat\SaloonSdkGenerator\Generator;
use Crescat\SaloonSdkGenerator\Helpers\NameHelper;
use Crescat\SaloonSdkGenerator\Helpers\Utils;
use DateTime;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use MKaverin\SplitwiseSDK\SDKBuilder\Helpers\MethodGeneratorHelper;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\Literal;
use Nette\PhpGenerator\PhpFile;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method as SaloonHttpMethod;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

use function collect;
use function in_array;
use function sprintf;

class RequestGenerator extends Generator
{
    public function generate(ApiSpecification $specification): PhpFile|array
    {
        $classes = [];

        foreach ($specification->endpoints as $endpoint) {
            $classes[] = $this->generateRequestClass($endpoint);
        }

        return $classes;
    }

    protected function generateRequestClass(Endpoint $endpoint): PhpFile
    {
        // @phpstan-ignore-next-line
        $resourceName = NameHelper::resourceClassName($endpoint->collection !== null && $endpoint->collection !== '' && $endpoint->collection !== '0' ? $endpoint->collection : $this->config->fallbackResourceName);
        $className = NameHelper::requestClassName($endpoint->name);

        $classType = new ClassType($className);

        $classFile = new PhpFile();
        $namespace = $classFile
            ->addNamespace("{$this->config->namespace}\\{$this->config->requestNamespaceSuffix}\\{$resourceName}");

        $classType->setExtends(Request::class)
            ->setComment($endpoint->name)
            ->addComment('')
            ->addComment(Utils::wrapLongLines($endpoint->description ?? ''));

        // TODO: We assume JSON body if post/patch, make these assumptions configurable in the future.
        if ($endpoint->method->isPost() || $endpoint->method->isPatch()) {
            $classType
                ->addImplement(HasBody::class)
                ->addTrait(HasJsonBody::class);

            $namespace
                ->addUse(HasBody::class)
                ->addUse(HasJsonBody::class);
        }

        $classType->addProperty('method')
            ->setProtected()
            ->setType(SaloonHttpMethod::class)
            ->setValue(
                new Literal(
                    sprintf('Method::%s', $endpoint->method->value)
                )
            );

        $classType->addMethod('resolveEndpoint')
            ->setPublic()
            ->setReturnType('string')
            ->addBody(
                (string) collect($endpoint->pathSegments)
                    ->map(fn (string $segment): string|Literal => Str::startsWith($segment, ':')
                        ? new Literal(sprintf('{$this->%s}', NameHelper::safeVariableName($segment)))
                        : $segment)
                    ->pipe(fn (Collection $segments): Literal => new Literal(sprintf('return "/%s";', $segments->implode('/'))))
            );

        $classConstructor = $classType->addMethod('__construct');

        // Priority 1. - Path Parameters
        foreach ($endpoint->pathParameters as $pathParam) {
            MethodGeneratorHelper::addParameterAsPromotedProperty($classConstructor, $pathParam);
        }

        // Priority 2. - Body Parameters
        if ($endpoint->bodyParameters !== []) {
            $bodyParams = collect($endpoint->bodyParameters)
                ->reject(fn (Parameter $parameter): bool => in_array($parameter->name, $this->config->ignoredBodyParams, true))
                ->values()
                ->toArray();

            foreach ($bodyParams as $bodyParam) {
                MethodGeneratorHelper::addParameterAsPromotedProperty($classConstructor, $bodyParam);
            }

            MethodGeneratorHelper::generateArrayReturnMethod($classType, 'defaultBody', $bodyParams, withArrayFilterWrapper: true);
        }

        // Priority 3. - Query Parameters
        if ($endpoint->queryParameters !== []) {
            $queryParams = collect($endpoint->queryParameters)
                ->reject(fn (Parameter $parameter): bool => in_array($parameter->name, $this->config->ignoredQueryParams, true))
                ->values()
                ->toArray();

            foreach ($queryParams as $queryParam) {
                MethodGeneratorHelper::addParameterAsPromotedProperty($classConstructor, $queryParam);
            }

            MethodGeneratorHelper::generateArrayReturnMethod($classType, 'defaultQuery', $queryParams, withArrayFilterWrapper: true);
        }

        $namespace
            ->addUse(SaloonHttpMethod::class)
            ->addUse(DateTime::class)
            ->addUse(Request::class)
            ->add($classType);

        return $classFile;
    }
}
