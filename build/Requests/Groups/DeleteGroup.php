<?php

declare(strict_types=1);

namespace MKaverin\SplitwiseSDK\Requests\Groups;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Delete a group.
 *
 * Delete an existing group. Destroys all associated records (expenses, etc.)
 */
class DeleteGroup extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected int $id,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/delete_group/{$this->id}";
    }
}
