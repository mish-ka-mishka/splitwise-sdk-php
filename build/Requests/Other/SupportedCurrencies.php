<?php

declare(strict_types=1);

namespace MKaverin\SplitwiseSDK\Requests\Other;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Supported currencies.
 *
 * Returns a list of all currencies allowed by the system. These are mostly ISO 4217 codes, but we
 * do
 * sometimes use pending codes or unofficial, colloquial codes (like BTC instead of XBT for
 * Bitcoin).
 */
class SupportedCurrencies extends Request
{
    protected Method $method = Method::GET;

    public function __construct() {}

    public function resolveEndpoint(): string
    {
        return '/get_currencies';
    }
}
