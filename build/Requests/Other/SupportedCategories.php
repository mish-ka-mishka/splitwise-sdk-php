<?php

declare(strict_types=1);

namespace MKaverin\SplitwiseSDK\Requests\Other;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Supported categories.
 *
 * Returns a list of all categories Splitwise allows for expenses. There are parent categories that
 * represent groups of categories with subcategories for more specific categorization.
 * When creating
 * expenses, you must use a subcategory, not a parent category.
 * If you intend for an expense to be
 * represented by the parent category and nothing more specific, please use the "Other" subcategory.
 */
class SupportedCategories extends Request
{
    protected Method $method = Method::GET;

    public function __construct() {}

    public function resolveEndpoint(): string
    {
        return '/get_categories';
    }
}
