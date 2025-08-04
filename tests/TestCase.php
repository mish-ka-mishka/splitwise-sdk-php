<?php

declare(strict_types=1);

namespace MKaverin\SplitwiseSDK\SDKBuilder\Tests;

use LaravelZero\Framework\Testing\TestCase as BaseTestCase;
use MKaverin\SplitwiseSDK\SplitwiseSDK;
use Override;
use Saloon\Config;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected SplitwiseSDK $connector;

    /**
     * @param array<string, string|int|float|bool> $headers
     */
    public function getMockClient(
        string $request,
        array $body = [],
        int $status = 200,
        array $headers = [],
    ): MockClient {
        return new MockClient([
            $request => MockResponse::make($body, $status, $headers),
        ]);
    }

    #[Override]
    protected function setUp(): void
    {
        parent::setUp();

        MockClient::destroyGlobal();
        Config::preventStrayRequests();

        $this->connector = new SplitwiseSDK('test-access-token');
    }
}
