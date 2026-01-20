<?php

declare(strict_types=1);

namespace MKaverin\SplitwiseSDK\SDKBuilder\Parsers;

use cebe\openapi\Reader;
use cebe\openapi\ReferenceContext;
use cebe\openapi\spec\MediaType;
use cebe\openapi\spec\Operation;
use cebe\openapi\spec\RequestBody;
use cebe\openapi\spec\Schema;
use Crescat\SaloonSdkGenerator\Data\Generator\Endpoint;
use Crescat\SaloonSdkGenerator\Data\Generator\Parameter;
use Crescat\SaloonSdkGenerator\Parsers\OpenApiParser as Parser;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Override;

use function collect;
use function in_array;
use function is_null;

class OpenApiParser extends Parser
{
    #[Override] // @phpstan-ignore-line
    public static function build($content): self // @phpstan-ignore-line
    {
        return new self(
            Reader::readFromYamlFile(fileName: realpath($content), resolveReferences: ReferenceContext::RESOLVE_MODE_ALL) // @phpstan-ignore-line
        );
    }

    #[Override] // @phpstan-ignore-line
    protected function parseEndpoint(Operation $operation, $pathParams, string $path, string $method): ?Endpoint // @phpstan-ignore-line
    {
        /** @var Endpoint $endpoint */
        $endpoint = parent::parseEndpoint($operation, $pathParams, $path, $method);

        $endpoint->bodyParameters = $this->parseBodyParameters($operation);

        // Filter out query parameters that are already defined in the path parameters (case insensitive)
        $endpoint->queryParameters = collect($endpoint->queryParameters)
            ->filter(fn (Parameter $parameter): bool => ! collect($endpoint->pathParameters)->contains(
                fn (Parameter $pathParameter): bool => Str::lower($pathParameter->name) === Str::lower($parameter->name)
            ))
            ->values()
            ->all();

        $endpoint->bodyParameters = collect($endpoint->bodyParameters)
            ->filter(fn (Parameter $parameter): bool => ! collect($endpoint->pathParameters)->contains(
                fn (Parameter $pathParameter): bool => Str::lower($pathParameter->name) === Str::lower($parameter->name)
            ))
            ->filter(fn (Parameter $parameter): bool => ! collect($endpoint->queryParameters)->contains(
                fn (Parameter $queryParameter): bool => Str::lower($queryParameter->name) === Str::lower($parameter->name)
            ))
            ->values()
            ->all();

        return $endpoint;
    }

    protected function parseBodyParameters(Operation $operation): array
    {
        if (is_null($operation->requestBody)) {
            return [];
        }

        /** @var RequestBody $requestBody */
        $requestBody = $operation->requestBody;

        /** @var MediaType $content */
        $content = $requestBody->content['application/json'] ?? Arr::first($requestBody->content);

        if (is_null($content->schema)) {
            return [];
        }

        /** @var Schema $schema */
        $schema = $content->schema;
        $properties = $this->resolveAllOfProperties($schema);
        $required = $schema->required ?? [];

        $parameters = collect();
        foreach ($properties as $name => $property) {
            // Skip duplicate properties
            if ($parameters->contains(fn (Parameter $parameter): bool => Str::lower($parameter->name) === Str::lower($name))) {
                continue;
            }

            $parameters->push(new Parameter(
                type: $this->mapSchemaTypeToPhpType($property->type ?? null), // @phpstan-ignore-line
                nullable: ! in_array($name, $required, true),
                name: $name,
                description: $property->description ?? null, // @phpstan-ignore-line
            ));
        }

        // required parameters first
        return $parameters
            ->sort(fn (Parameter $a, Parameter $b): int => (int) $a->nullable <=> (int) $b->nullable)
            ->values()
            ->all();
    }

    protected function resolveAllOfProperties(Schema $schema): array
    {
        $properties = [];
        if ($schema->allOf !== null) {
            foreach ($schema->allOf as $subSchema) {
                $props = $this->resolveAllOfProperties($this->resolveRef($subSchema)); // @phpstan-ignore-line
                $properties = array_merge($properties, $props);
            }
        }
        if ($schema->oneOf !== null) {
            foreach ($schema->oneOf as $subSchema) {
                $props = $this->resolveAllOfProperties($this->resolveRef($subSchema)); // @phpstan-ignore-line
                $properties = array_merge($properties, $props);
            }
        }
        if ($schema->properties !== null) {
            return array_merge($properties, $schema->properties);
        }

        return $properties;
    }

    protected function resolveRef(Schema $schema): Schema
    {
        if (! isset($schema->{'$ref'})) { // @phpstan-ignore-line
            return $schema;
        }
        if (method_exists($schema, 'resolve')) {
            return $schema->resolve();
        }

        return $schema;
    }
}
