# Splitwise API (3.0.0) PHP SDK

This package provides a PHP SDK for the Splitwise API (3.0.0), allowing developers to easily integrate Splitwise's services into their
applications. It is built
using [Splitwise's official OpenAPI specifications](https://dev.splitwise.com/75dda077-162b-407d-b642-cd83493234c6) and is built
with the help of [crescat-io/saloon-sdk-generator](https://github.com/crescat-io/saloon-sdk-generator) tool.

## Installation

```bash
composer require mkaverin/splitwise-sdk-php
```

## Usage

```php
use Splitwise\SplitwiseSDK;

$connector = new SplitwiseSDK(
    $accessToken,
    $refreshToken,
    $expiresAt,
);

$response = $connector->clients()->fetchAllTenantsFormerlyClients(); // TODO
```

## Contributing

`/app` folder contains the Laravel Zero console application that is used to generate the SDK. 
You can use `build` command to download a fresh version of the OpenAPI specification and generate the SDK files in the `/build` folder:

```bash
php application build
```

## Links

- [Splitwise API Documentation](https://dev.splitwise.com/)
- [Saloon library](https://docs.saloon.dev)
