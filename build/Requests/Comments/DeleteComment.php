<?php

declare(strict_types=1);

namespace MKaverin\SplitwiseSDK\Requests\Comments;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Delete a comment.
 *
 * Deletes a comment. Returns the deleted comment.
 */
class DeleteComment extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected int $id,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/delete_comment/{$this->id}";
    }
}
