<?php

namespace App\Service;

use App\Entity\StoreProduct;
use App\Repository\ProductRepository;
use App\Repository\StoreProductRepository;
use App\Repository\StoreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

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
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param JsonPriceProvider $jsonPriceProvider
     * @param ProductRepository $productRepository
     * @param StoreRepository $storeRepository
     * @param StoreProductRepository $storeProductRepository
     * @param EntityManagerInterface $entityManager
     * @param LoggerInterface $logger
     */
    public function __construct(
        JsonPriceProvider $jsonPriceProvider,
        ProductRepository $productRepository,
        StoreRepository $storeRepository,
        StoreProductRepository $storeProductRepository,
        EntityManagerInterface $entityManager,
        LoggerInterface $logger
    )
    {
        $this->jsonPriceProvider = $jsonPriceProvider;
        $this->productRepository = $productRepository;
        $this->storeRepository = $storeRepository;
        $this->storeProductRepository = $storeProductRepository;
        $this->entityManager = $entityManager;
        $this->logger = $logger;
    }

    public function updatePrice()
    {
        foreach ($this->jsonPriceProvider->getPrices() as $price) {
            $store = $this->storeRepository->findOneBy(['id' => $price['store_id']]);
            $product = $this->productRepository->findOneBy(['id' => $price['product_id']]);

            if (!$store || !$product) {
                $this->logger->error(
                    'Такого продукту з id: ' .
                    $price['product_id'] . ' або магазину з id: ' .
                    $price['store_id'] . ' неіснує!'
                );
                continue;
            }
            $storeProduct = $this->storeProductRepository->findOneBy([
                'product' => $price['product_id'],
                'store' => $price['store_id']
            ]);
            if (!$storeProduct) {
                $storeProduct = new StoreProduct();
                $storeProduct
                    ->setStore($store)
                    ->setProduct($product)
                    ->setPrice($price['price']);
            }
            else
            {
                $storeProduct->setPrice($price['price']);
            }

            $this->storeProductRepository->save($storeProduct, true);
        }
    }

    /**
     * @param StoreProduct
     */
    public function save(StoreProduct $storeProduct): void
    {
        // Persist in database
        $this->entityManager->persist($storeProduct);
        $this->entityManager->flush();

    }
}