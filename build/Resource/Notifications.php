<?php

declare(strict_types=1);

namespace MKaverin\SplitwiseSDK\Resource;

use MKaverin\SplitwiseSDK\Requests\Notifications\GetNotifications;
use MKaverin\SplitwiseSDK\Resource;
use Saloon\Http\Response;

class Notifications extends Resource
{
    /**
     * @param string $updatedAfter if provided, returns only notifications after this time
     * @param int    $limit        omit (or provide `0`) to get the maximum number of notifications
     */
    public function getNotifications(?string $updatedAfter = null, ?int $limit = null): Response
    {
        return $this->connector->send(new GetNotifications($updatedAfter, $limit));
    }
}
