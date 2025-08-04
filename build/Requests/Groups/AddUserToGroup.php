<?php

declare(strict_types=1);

namespace MKaverin\SplitwiseSDK\Requests\Groups;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Add a user to a group.
 *
 * **Note**: 200 OK does not indicate a successful response. You must check the `success` value of the
 * response.
 */
class AddUserToGroup extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct() {}

    public function resolveEndpoint(): string
    {
        return '/add_user_to_group';
    }
}
