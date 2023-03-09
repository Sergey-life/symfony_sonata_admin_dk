<?php

namespace App\Service;

interface PriceProviderInterface
{
    /**
     * @return array
     */
    public function getPrices();
}