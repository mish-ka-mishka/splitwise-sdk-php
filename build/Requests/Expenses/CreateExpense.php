<?php

declare(strict_types=1);

namespace MKaverin\SplitwiseSDK\Requests\Expenses;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Create an expense.
 *
 * Creates an expense. You may either split an expense equally (only with `group_id` provided),
 * or
 * supply a list of shares.
 *
 * When splitting equally, the authenticated user is assumed to be the
 * payer.
 *
 * When providing a list of shares, each share must include `paid_share` and `owed_share`, and
 * must be identified by one of the following:
 * - `email`, `first_name`, and `last_name`
 * -
 * `user_id`
 *
 * **Note**: 200 OK does not indicate a successful response. The operation was successful
 * only if `errors` is empty.
 */
class CreateExpense extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct() {}

    public function resolveEndpoint(): string
    {
        return '/create_expense';
    }
}
