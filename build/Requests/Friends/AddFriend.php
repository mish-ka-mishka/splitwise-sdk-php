<?php

declare(strict_types=1);

namespace MKaverin\SplitwiseSDK\Requests\Friends;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

use function is_null;

/**
 * Add a friend.
 *
 * Adds a friend. If the other user does not exist, you must supply `user_first_name`.
 * If the other
 * user exists, `user_first_name` and `user_last_name` will be ignored.
 */
class AddFriend extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected ?string $userEmail = null,
        protected ?string $userFirstName = null,
        protected ?string $userLastName = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/create_friend';
    }

    public function defaultBody(): array
    {
        return array_filter(['user_email' => $this->userEmail, 'user_first_name' => $this->userFirstName, 'user_last_name' => $this->userLastName], fn (mixed $value): bool => ! is_null($value));
    }
}
