<?php

declare(strict_types=1);

namespace MKaverin\SplitwiseSDK\Requests\Groups;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * List the current user's groups.
 *
 * **Note**: Expenses that are not associated with a group are listed in a group with ID 0.
 */
class ListTheCurrentUserGroups extends Request
{
    protected Method $method = Method::GET;

    public function __construct() {}

    public function resolveEndpoint(): string
    {
        return '/get_groups';
    }
}
