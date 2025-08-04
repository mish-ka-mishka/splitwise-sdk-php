<?php

declare(strict_types=1);

namespace MKaverin\SplitwiseSDK\Requests\Friends;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Delete friendship.
 *
 * Given a friend ID, break off the friendship between the current user and the specified
 * user.
 *
 * **Note**: 200 OK does not indicate a successful response. You must check the `success` value
 * of the response.
 */
class DeleteFriendship extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    /**
     * @param int $id User ID of the friend
     */
    public function __construct(
        protected int $id,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/delete_friend/{$this->id}";
    }
}
