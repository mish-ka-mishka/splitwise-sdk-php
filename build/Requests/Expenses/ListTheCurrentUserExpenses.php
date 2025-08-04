<?php

declare(strict_types=1);

namespace MKaverin\SplitwiseSDK\Requests\Expenses;

use Saloon\Enums\Method;
use Saloon\Http\Request;

use function is_null;

/**
 * List the current user's expenses.
 */
class ListTheCurrentUserExpenses extends Request
{
    protected Method $method = Method::GET;

    /**
     * @param int|null $groupId  if provided, only expenses in that group will be returned, and `friend_id` will be ignored
     * @param int|null $friendId ID of another user. If provided, only expenses between the current and provided user will be returned.
     */
    public function __construct(
        protected ?int $groupId = null,
        protected ?int $friendId = null,
        protected ?string $datedAfter = null,
        protected ?string $datedBefore = null,
        protected ?string $updatedAfter = null,
        protected ?string $updatedBefore = null,
        protected ?int $limit = null,
        protected ?int $offset = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/get_expenses';
    }

    protected function defaultQuery(): array
    {
        return array_filter([
            'group_id' => $this->groupId,
            'friend_id' => $this->friendId,
            'dated_after' => $this->datedAfter,
            'dated_before' => $this->datedBefore,
            'updated_after' => $this->updatedAfter,
            'updated_before' => $this->updatedBefore,
            'limit' => $this->limit,
            'offset' => $this->offset,
        ], fn (mixed $value): bool => ! is_null($value));
    }
}
