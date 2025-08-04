<?php

declare(strict_types=1);

namespace MKaverin\SplitwiseSDK\Resource;

use MKaverin\SplitwiseSDK\Requests\Expenses\CreateExpense;
use MKaverin\SplitwiseSDK\Requests\Expenses\DeleteExpense;
use MKaverin\SplitwiseSDK\Requests\Expenses\GetExpenseInformation;
use MKaverin\SplitwiseSDK\Requests\Expenses\ListTheCurrentUserExpenses;
use MKaverin\SplitwiseSDK\Requests\Expenses\RestoreExpense;
use MKaverin\SplitwiseSDK\Requests\Expenses\UpdateExpense;
use MKaverin\SplitwiseSDK\Resource;
use Saloon\Http\Response;

class Expenses extends Resource
{
    public function getExpenseInformation(int $id): Response
    {
        return $this->connector->send(new GetExpenseInformation($id));
    }

    /**
     * @param int $groupId  if provided, only expenses in that group will be returned, and `friend_id` will be ignored
     * @param int $friendId ID of another user. If provided, only expenses between the current and provided user will be returned.
     */
    public function listTheCurrentUserExpenses(
        ?int $groupId = null,
        ?int $friendId = null,
        ?string $datedAfter = null,
        ?string $datedBefore = null,
        ?string $updatedAfter = null,
        ?string $updatedBefore = null,
        ?int $limit = null,
        ?int $offset = null,
    ): Response {
        return $this->connector->send(new ListTheCurrentUserExpenses($groupId, $friendId, $datedAfter, $datedBefore, $updatedAfter, $updatedBefore, $limit, $offset));
    }

    public function createExpense(): Response
    {
        return $this->connector->send(new CreateExpense());
    }

    /**
     * @param int    $id              ID of the expense to update
     * @param string $cost            A string representation of a decimal value, limited to 2 decimal places
     * @param string $description     A short description of the expense
     * @param string $details         Also known as "notes."
     * @param string $date            The date and time the expense took place. May differ from `created_at`
     * @param string $currencyCode    A currency code. Must be in the list from `get_currencies`
     * @param int    $categoryId      A category id from `get_categories`
     * @param int    $groupId         the group to put this expense in, or `0` to create an expense outside of a group
     * @param string $users0PaidShare Decimal amount as a string with 2 decimal places. The amount this user paid for the expense
     * @param string $users0OwedShare Decimal amount as a string with 2 decimal places. The amount this user owes for the expense
     * @param string $users1PaidShare Decimal amount as a string with 2 decimal places. The amount this user paid for the expense
     * @param string $users1OwedShare Decimal amount as a string with 2 decimal places. The amount this user owes for the expense
     */
    public function updateExpense(
        int $id,
        ?string $cost = null,
        ?string $description = null,
        ?string $details = null,
        ?string $date = null,
        ?string $repeatInterval = null,
        ?string $currencyCode = null,
        ?int $categoryId = null,
        ?int $groupId = null,
        ?int $users0UserId = null,
        ?string $users0PaidShare = null,
        ?string $users0OwedShare = null,
        ?string $users1FirstName = null,
        ?string $users1LastName = null,
        ?string $users1Email = null,
        ?string $users1PaidShare = null,
        ?string $users1OwedShare = null,
    ): Response {
        return $this->connector->send(new UpdateExpense($id, $cost, $description, $details, $date, $repeatInterval, $currencyCode, $categoryId, $groupId, $users0UserId, $users0PaidShare, $users0OwedShare, $users1FirstName, $users1LastName, $users1Email, $users1PaidShare, $users1OwedShare));
    }

    /**
     * @param int $id ID of the expense to delete
     */
    public function deleteExpense(int $id): Response
    {
        return $this->connector->send(new DeleteExpense($id));
    }

    /**
     * @param int $id ID of the expense to restore
     */
    public function restoreExpense(int $id): Response
    {
        return $this->connector->send(new RestoreExpense($id));
    }
}
