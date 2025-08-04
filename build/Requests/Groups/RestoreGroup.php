<?php

declare(strict_types=1);

namespace MKaverin\SplitwiseSDK\Requests\Groups;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Restore a group.
 *
 * Restores a deleted group.
 *
 * **Note**: 200 OK does not indicate a successful response. You must check
 * the `success` value of the response.
 */
class RestoreGroup extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected int $id,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/undelete_group/{$this->id}";
    }
}
