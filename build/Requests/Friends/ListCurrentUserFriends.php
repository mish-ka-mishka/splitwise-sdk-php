<?php

declare(strict_types=1);

namespace MKaverin\SplitwiseSDK\Requests\Friends;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * List current user's friends.
 *
 * **Note**: `group` objects only include group balances with that friend.
 */
class ListCurrentUserFriends extends Request
{
    protected Method $method = Method::GET;

    public function __construct() {}

    public function resolveEndpoint(): string
    {
        return '/get_friends';
    }
}
