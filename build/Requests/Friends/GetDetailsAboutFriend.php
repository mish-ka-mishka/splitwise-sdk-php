<?php

declare(strict_types=1);

namespace MKaverin\SplitwiseSDK\Requests\Friends;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Get details about a friend.
 */
class GetDetailsAboutFriend extends Request
{
    protected Method $method = Method::GET;

    /**
     * @param int $id User ID of the friend
     */
    public function __construct(
        protected int $id,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/get_friend/{$this->id}";
    }
}
