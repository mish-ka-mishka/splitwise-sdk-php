<?php

declare(strict_types=1);

namespace MKaverin\SplitwiseSDK\Requests\Groups;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

use function is_null;

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

    public function __construct(
        protected int $groupId,
        protected ?int $userId = null,
        protected ?string $firstName = null,
        protected ?string $lastName = null,
        protected ?string $email = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/add_user_to_group';
    }

    public function defaultBody(): array
    {
        return array_filter([
            'group_id' => $this->groupId,
            'user_id' => $this->userId,
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'email' => $this->email,
        ], fn (mixed $value): bool => ! is_null($value));
    }
}
