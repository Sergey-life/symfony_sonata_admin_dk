<?php

namespace App\Service;

use App\Entity\CategoryProduct;
use App\Entity\Product;
use App\Repository\CategoryProductRepository;
use App\Repository\ProductRepository;
use Psr\Log\LoggerInterface;

class ProductImporter
{
    /**
     * @var ProductProviderInterface
     */
    private $productProvider;

    /**
     * @var ProductRepository
     */
    private $productRepository;

    /**
     * @var CategoryProductRepository
     */
    private $categoryProductRepository;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param ProductProviderInterface $productProvider
     * @param ProductRepository $productRepository
     * @param CategoryProductRepository $categoryProductRepository
     * @param LoggerInterface $logger
     */
    public function __construct(
        ProductProviderInterface $productProvider,
        ProductRepository $productRepository,
        CategoryProductRepository $categoryProductRepository,
        LoggerInterface $logger
    )
    {
        $this->productProvider = $productProvider;
        $this->productRepository = $productRepository;
        $this->categoryProductRepository = $categoryProductRepository;
        $this->logger = $logger;
    }

    /**
     * Update categories and products
     */
    public function importProductsAndCategories()
    {
        foreach ($this->productProvider->getProducts() as $item) {
            $product = $this->productRepository->findOneBy(['code' => $item['code']]);
            if ($product) {
                $category = $this->processCategory($item['category'], $product->getCategory());
            }
            else
            {
                $category = $this->processCategory($item['category']);
            }
            if ($product) {
                $product
                    ->setName($item['name'])
                    ->setDescription($item['description'])
                    ->setPrice($item['price'])
                    ->setCode($item['code'])
                    ->setCategory($category);
            }
            if (!$product) {
                $product = new Product(
                    $item['name'],
                    $item['description'],
                    $item['price'],
                    $item['code'],
                    $category
                );
            }

            $this->productRepository->save($product, true);
        }
    }

    /**
     * @param array $data
     * @param CategoryProduct|null $category
     * @return CategoryProduct
     */
    private function processCategory(array $data, CategoryProduct $category = null): CategoryProduct
    {
        if ($category && $category->getId() == $data['id']) {
            $categoryOfProduct = $category;
            $categoryOfProduct->setName($data['name']);
            $this->categoryProductRepository->save($categoryOfProduct, true);

            return $categoryOfProduct;
        }
        if ($category && $category->getId() != $data['id']) {
            $categoryOfProduct = $this->categoryProductRepository->findOneBy(['id' => $data['id']]);
            if (!$categoryOfProduct) {
                $categoryOfProduct = $this->createNewCategory($data['name']);
            }
        }
        else
        {
            $categoryOfProduct = $this->createNewCategory($data['name']);
        }

        return $categoryOfProduct;
    }

    /**
     * Return new category or write error in log
     * @param string
     * @return CategoryProduct
     */
    private function createNewCategory(string $categoryName): CategoryProduct
    {
        if ($this->categoryProductRepository->findOneBy(['name' => $categoryName])) {
            $this->logger->error("Категорія з {name} вже існує!", [
                'name' => $categoryName
            ]);
            exit();
        }
        $categoryOfProduct = new CategoryProduct($categoryName);
        $this->categoryProductRepository->save($categoryOfProduct, true);

        return $categoryOfProduct;
    }
}