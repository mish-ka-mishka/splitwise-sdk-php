<?php

declare(strict_types=1);

namespace MKaverin\SplitwiseSDK\Requests\Notifications;

use Saloon\Enums\Method;
use Saloon\Http\Request;

use function is_null;

/**
 * Get notifications.
 *
 * Return a list of recent activity on the users account with the most recent items first.
 * `content`
 * will be suitable for display in HTML and uses only the `<strong>`, `<strike>`, `<small>`,
 * `<br>` and
 * `<font color="#FFEE44">` tags.
 *
 * The `type` value indicates what the notification is about.
 * Notification types may be added in the future
 * without warning. Below is an incomplete list of
 * notification types.
 *
 * | Type | Meaning |
 * | ---- | ------- |
 * | 0    | Expense added |
 * | 1    | Expense
 * updated |
 * | 2	   | Expense deleted |
 * | 3	   | Comment added |
 * | 4	   | Added to group |
 * | 5	   |
 * Removed from group |
 * | 6	   | Group deleted |
 * | 7	   | Group settings changed |
 * | 8	   | Added as
 * friend |
 * | 9	   | Removed as friend |
 * | 10	 | News (a URL should be included) |
 * | 11	 | Debt
 * simplification |
 * | 12	 | Group undeleted |
 * | 13	 | Expense undeleted |
 * | 14	 | Group currency
 * conversion |
 * | 15	 | Friend currency conversion |
 *
 * **Note**: While all parameters are optional, the
 * server sets arbitrary (but large) limits
 * on the number of notifications returned if you set a very
 * old `updated_after` value or `limit` of `0` for a
 * user with many notifications.
 */
class GetNotifications extends Request
{
    protected Method $method = Method::GET;

    /**
     * @param string|null $updatedAfter if provided, returns only notifications after this time
     * @param int|null    $limit        omit (or provide `0`) to get the maximum number of notifications
     */
    public function __construct(
        protected ?string $updatedAfter = null,
        protected ?int $limit = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/get_notifications';
    }

    protected function defaultQuery(): array
    {
        return array_filter(['updated_after' => $this->updatedAfter, 'limit' => $this->limit], fn (mixed $value): bool => ! is_null($value));
    }
}
