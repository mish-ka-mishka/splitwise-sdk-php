<?php

declare(strict_types=1);

namespace MKaverin\SplitwiseSDK\Requests\Expenses;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

use function is_null;

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

    /**
     * @param string|null $cost            A string representation of a decimal value, limited to 2 decimal places
     * @param string|null $description     A short description of the expense
     * @param string|null $details         Also known as "notes."
     * @param string|null $date            The date and time the expense took place. May differ from `created_at`
     * @param string|null $currencyCode    A currency code. Must be in the list from `get_currencies`
     * @param int|null    $categoryId      A category id from `get_categories`
     * @param int|null    $groupId         the group to put this expense in, or `0` to create an expense outside of a group
     * @param string|null $users0PaidShare Decimal amount as a string with 2 decimal places. The amount this user paid for the expense
     * @param string|null $users0OwedShare Decimal amount as a string with 2 decimal places. The amount this user owes for the expense
     * @param string|null $users1PaidShare Decimal amount as a string with 2 decimal places. The amount this user paid for the expense
     * @param string|null $users1OwedShare Decimal amount as a string with 2 decimal places. The amount this user owes for the expense
     */
    public function __construct(
        protected ?string $cost = null,
        protected ?string $description = null,
        protected ?string $details = null,
        protected ?string $date = null,
        protected ?string $repeatInterval = null,
        protected ?string $currencyCode = null,
        protected ?int $categoryId = null,
        protected ?int $groupId = null,
        protected ?bool $splitEqually = null,
        protected ?int $users0UserId = null,
        protected ?string $users0PaidShare = null,
        protected ?string $users0OwedShare = null,
        protected ?string $users1FirstName = null,
        protected ?string $users1LastName = null,
        protected ?string $users1Email = null,
        protected ?string $users1PaidShare = null,
        protected ?string $users1OwedShare = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/create_expense';
    }

    public function defaultBody(): array
    {
        return array_filter([
            'cost' => $this->cost,
            'description' => $this->description,
            'details' => $this->details,
            'date' => $this->date,
            'repeat_interval' => $this->repeatInterval,
            'currency_code' => $this->currencyCode,
            'category_id' => $this->categoryId,
            'group_id' => $this->groupId,
            'split_equally' => $this->splitEqually,
            'users__0__user_id' => $this->users0UserId,
            'users__0__paid_share' => $this->users0PaidShare,
            'users__0__owed_share' => $this->users0OwedShare,
            'users__1__first_name' => $this->users1FirstName,
            'users__1__last_name' => $this->users1LastName,
            'users__1__email' => $this->users1Email,
            'users__1__paid_share' => $this->users1PaidShare,
            'users__1__owed_share' => $this->users1OwedShare,
        ], fn (mixed $value): bool => ! is_null($value));
    }
}
