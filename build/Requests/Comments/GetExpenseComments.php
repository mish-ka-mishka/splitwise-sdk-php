<?php

declare(strict_types=1);

namespace MKaverin\SplitwiseSDK\Requests\Comments;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Get expense comments.
 */
class GetExpenseComments extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected int $expenseId,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/get_comments';
    }

    protected function defaultQuery(): array
    {
        return ['expense_id' => $this->expenseId];
    }
}
