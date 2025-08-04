<?php

declare(strict_types=1);

namespace MKaverin\SplitwiseSDK\Requests\Comments;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

use function is_null;

/**
 * Create a comment.
 */
class CreateComment extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected ?int $expenseId = null,
        protected ?string $content = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/create_comment';
    }

    public function defaultBody(): array
    {
        return array_filter(['expense_id' => $this->expenseId, 'content' => $this->content], fn (mixed $value): bool => ! is_null($value));
    }
}
