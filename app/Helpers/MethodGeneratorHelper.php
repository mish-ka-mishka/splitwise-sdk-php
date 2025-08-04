<?php

declare(strict_types=1);

namespace MKaverin\SplitwiseSDK\SDKBuilder\Helpers;

use Crescat\SaloonSdkGenerator\Data\Generator\Parameter;
use Crescat\SaloonSdkGenerator\Helpers\NameHelper;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\Dumper;
use Nette\PhpGenerator\Literal;
use Nette\PhpGenerator\Method;

use function collect;
use function sprintf;

class MethodGeneratorHelper
{
    /**
     * Adds a promoted property to a method based on a given parameter.
     *
     * @param Method    $method    the method to which the promoted property is added
     * @param Parameter $parameter the parameter based on which the promoted property is added
     *
     * @return Method the updated method with the promoted property
     */
    public static function addParameterAsPromotedProperty(Method $method, Parameter $parameter): Method
    {
        $name = NameHelper::safeVariableName($parameter->name);

        $property = $method
            ->addComment(
                trim(sprintf(
                    '@param %s $%s %s',
                    $parameter->nullable ? "null|{$parameter->type}" : $parameter->type,
                    $name,
                    $parameter->description
                ))
            )
            ->addPromotedParameter($name);

        $property
            ->setType($parameter->type)
            ->setNullable($parameter->nullable)
            ->setProtected();

        if ($parameter->nullable) {
            $property->setDefaultValue(null);
        }

        return $method;
    }

    /**
     * Generates a method that returns parameters as an array.
     */
    public static function generateArrayReturnMethod(ClassType $classType, string $name, array $parameters, bool $withArrayFilterWrapper = false): Method
    {
        $paramArray = self::buildParameterArray($parameters);

        $body = $withArrayFilterWrapper && collect($parameters)->filter(fn (Parameter $parameter): bool => $parameter->nullable)->isNotEmpty()
            ? sprintf('return array_filter(%s, fn (mixed $value): bool => ! is_null($value));', (new Dumper())->dump($paramArray))
            : sprintf('return %s;', (new Dumper())->dump($paramArray));

        return $classType
            ->addMethod($name)
            ->setReturnType('array')
            ->addBody($body);
    }

    /**
     * Builds an array of parameters with their corresponding values.
     */
    protected static function buildParameterArray(array $parameters): array
    {
        return collect($parameters)
            ->mapWithKeys(fn (Parameter $parameter) => [
                $parameter->name => new Literal(
                    sprintf('$this->%s', NameHelper::safeVariableName($parameter->name))
                ),
            ])
            ->toArray();
    }
}
