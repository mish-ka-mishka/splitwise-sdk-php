<?php

declare(strict_types=1);

namespace MKaverin\SplitwiseSDK\Requests\Groups;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Remove a user from a group.
 *
 * Remove a user from a group. Does not succeed if the user has a non-zero balance.
 *
 * **Note:** 200 OK
 * does not indicate a successful response. You must check the success value of the response.
 */
class RemoveUserFromGroup extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected int $groupId,
        protected int $userId,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/remove_user_from_group';
    }

    public function defaultBody(): array
    {
        return ['group_id' => $this->groupId, 'user_id' => $this->userId];
    }
}
