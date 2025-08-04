<?php

declare(strict_types=1);

use MKaverin\SplitwiseSDK\Requests\Comments\GetExpenseComments;
use MKaverin\SplitwiseSDK\Resource\Comments;
use MKaverin\SplitwiseSDK\SplitwiseSDK;
use Saloon\Http\Auth\AccessTokenAuthenticator;

/** @var MKaverin\SplitwiseSDK\SDKBuilder\Tests\TestCase $this */
it('sets correct base URL', function (): void {
    expect($this->connector)->toBeInstanceOf(SplitwiseSDK::class);
    expect($this->connector->resolveBaseUrl())->toBe('https://secure.splitwise.com/api/v3.0');
});

it('handles auth', function (): void {
    $this->connector->withMockClient($this->getMockClient(GetExpenseComments::class));

    $resource = new Comments($this->connector);
    $resource->getExpenseComments(1);

    $authenticator = $this->connector->getMockClient()->getLastPendingRequest()->getAuthenticator();

    expect($authenticator)->toBeInstanceOf(AccessTokenAuthenticator::class);
    expect($authenticator->accessToken)->toBe('test-access-token');
    expect($authenticator->refreshToken)->toBeNull();
    expect($authenticator->expiresAt)->toBeNull();
});
