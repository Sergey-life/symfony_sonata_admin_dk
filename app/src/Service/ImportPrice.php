<?php

namespace App\Service;

use App\Entity\Product;
use App\Entity\StoreProduct;
use App\Repository\ProductRepository;
use App\Repository\StoreProductRepository;
use App\Repository\StoreRepository;

class ImportPrice
{
    /**
     * @var JsonPriceProvider
     */
    private $jsonPriceProvider;

    /**
     * @var ProductRepository
     */
    private $productRepository;

    /**
     * @var StoreRepository
     */
    private $storeRepository;

    /**
     * @var StoreProductRepository
     */
    private $storeProductRepository;

    /**
     * @param JsonPriceProvider $jsonPriceProvider
     * @param ProductRepository $productRepository
     * @param StoreRepository $storeRepository
     * @param StoreProductRepository $storeProductRepository
     */
    public function __construct(
        JsonPriceProvider $jsonPriceProvider,
        ProductRepository $productRepository,
        StoreRepository $storeRepository,
        StoreProductRepository $storeProductRepository
    )
    {
        $this->jsonPriceProvider = $jsonPriceProvider;
        $this->productRepository = $productRepository;
        $this->storeRepository = $storeRepository;
        $this->storeProductRepository = $storeProductRepository;
    }

    public function updatePrice()
    {
        foreach ($this->jsonPriceProvider->getPrices() as $price) {
            if (!$this->storeProductRepository->findOneBy([
                'product' => $price['product_id'],
                'store' => $price['store_id']
            ])) {
                $storeProduct = new StoreProduct();
            }
            $storeProduct
                ->setStore($this->storeRepository->findOneBy(['id' => $price['store_id']]))
                ->setProduct($this->productRepository->findOneBy(['id' => $price['product_id']]))
                ->setPrice($price['price']);

            $this->storeProductRepository->save($storeProduct, true);
        }
    }
}