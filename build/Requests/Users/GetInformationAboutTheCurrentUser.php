<?php

declare(strict_types=1);

namespace MKaverin\SplitwiseSDK\Requests\Users;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Get information about the current user.
 */
class GetInformationAboutTheCurrentUser extends Request
{
    protected Method $method = Method::GET;

    public function __construct() {}

    public function resolveEndpoint(): string
    {
        return '/get_current_user';
    }
}
