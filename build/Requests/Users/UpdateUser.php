<?php

declare(strict_types=1);

namespace MKaverin\SplitwiseSDK\Requests\Users;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

use function is_null;

/**
 * Update a user.
 */
class UpdateUser extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected int $id,
        protected ?string $firstName = null,
        protected ?string $lastName = null,
        protected ?string $email = null,
        protected ?string $password = null,
        protected ?string $locale = null,
        protected ?string $defaultCurrency = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/update_user/{$this->id}";
    }

    public function defaultBody(): array
    {
        return array_filter([
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'email' => $this->email,
            'password' => $this->password,
            'locale' => $this->locale,
            'default_currency' => $this->defaultCurrency,
        ], fn (mixed $value): bool => ! is_null($value));
    }
}
