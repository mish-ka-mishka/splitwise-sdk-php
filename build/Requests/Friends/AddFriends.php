<?php

declare(strict_types=1);

namespace MKaverin\SplitwiseSDK\Requests\Friends;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Add friends.
 *
 * Add multiple friends at once.
 *
 * For each user, if the other user does not exist, you must supply
 * `friends__{index}__first_name`.
 *
 * **Note**: user parameters must be flattened into the format
 * `friends__{index}__{property}`, where
 * `property` is `first_name`, `last_name`, or `email`.
 */
class AddFriends extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct() {}

    public function resolveEndpoint(): string
    {
        return '/create_friends';
    }
}
