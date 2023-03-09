<?php

namespace App\Service;

class JsonPriceProvider implements PriceProviderInterface
{
    /**
     * @var JsonProductProvider
     */
    private $jsonProductProvider;

    /**
     * @param JsonProductProvider $productProvider
     */
    public function __construct(JsonProductProvider $productProvider)
    {
        $this->jsonProductProvider = $productProvider;
    }

    public function getPrices()
    {
        $prices = json_decode($this->jsonProductProvider->getFileJson('price.json'), true);

        return $prices;
    }
}