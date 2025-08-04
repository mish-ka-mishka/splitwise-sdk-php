<?php

declare(strict_types=1);

namespace MKaverin\SplitwiseSDK\Requests\Groups;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Get information about a group.
 */
class GetInformationAboutGroup extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected int $id,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/get_group/{$this->id}";
    }
}
