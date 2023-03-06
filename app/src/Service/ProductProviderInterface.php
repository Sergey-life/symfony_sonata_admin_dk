<?php

namespace App\Service;

interface ProductProviderInterface
{
    /**
     * Get product list
     *
     * @return array
     */
    public function getProducts();
}