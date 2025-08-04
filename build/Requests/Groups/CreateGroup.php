<?php

declare(strict_types=1);

namespace MKaverin\SplitwiseSDK\Requests\Groups;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

use function is_null;

/**
 * Create a group.
 *
 * Creates a new group. Adds the current user to the group by default.
 *
 * **Note**: group user parameters
 * must be flattened into the format `users__{index}__{property}`, where
 * `property` is `user_id`,
 * `first_name`, `last_name`, or `email`.
 * The user's email or ID must be provided.
 */
class CreateGroup extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    /**
     * @param string|null $groupType what is the group used for?
     *
     * **Note**: It is recommended to use `home` in place of `house` or `apartment`
     * @param bool|null $simplifyByDefault Turn on simplify debts?
     */
    public function __construct(
        protected string $name,
        protected ?string $groupType = null,
        protected ?bool $simplifyByDefault = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/create_group';
    }

    public function defaultBody(): array
    {
        return array_filter(['name' => $this->name, 'group_type' => $this->groupType, 'simplify_by_default' => $this->simplifyByDefault], fn (mixed $value): bool => ! is_null($value));
    }
}
