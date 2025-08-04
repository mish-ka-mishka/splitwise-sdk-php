<?php

declare(strict_types=1);

namespace MKaverin\SplitwiseSDK\Requests\Expenses;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Delete an expense.
 *
 * **Note**: 200 OK does not indicate a successful response. The operation was successful only if
 * `success` is true.
 */
class DeleteExpense extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    /**
     * @param int $id ID of the expense to delete
     */
    public function __construct(
        protected int $id,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/delete_expense/{$this->id}";
    }
}
