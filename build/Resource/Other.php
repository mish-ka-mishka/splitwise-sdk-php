<?php

declare(strict_types=1);

namespace MKaverin\SplitwiseSDK\Resource;

use MKaverin\SplitwiseSDK\Requests\Other\SupportedCategories;
use MKaverin\SplitwiseSDK\Requests\Other\SupportedCurrencies;
use MKaverin\SplitwiseSDK\Resource;
use Saloon\Http\Response;

class Other extends Resource
{
    public function supportedCurrencies(): Response
    {
        return $this->connector->send(new SupportedCurrencies());
    }

    public function supportedCategories(): Response
    {
        return $this->connector->send(new SupportedCategories());
    }
}
