<?php

namespace App\Service;

use App\Entity\Category;
use App\Entity\CategoryProduct;
use App\Entity\Product;
use App\Repository\CategoryProductRepository;
use App\Repository\ProductRepository;

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
     * @param ProductProviderInterface $productProvider
     * @param ProductRepository $productRepository
     * @param CategoryProductRepository $categoryProductRepository
     */
    public function __construct(
        ProductProviderInterface $productProvider,
        ProductRepository $productRepository,
        CategoryProductRepository $categoryProductRepository
    )
    {
        $this->productProvider = $productProvider;
        $this->productRepository = $productRepository;
        $this->categoryProductRepository = $categoryProductRepository;
    }

    /**
     * Update categories and products
     */
    public function updateProdsAndCats()
    {
        foreach ($this->productProvider->getProducts() as $item) {
//            $category = $this->categoryProductRepository->findOneBy(['name' => $item['category']]);
            $product = $this->productRepository->findOneBy(['code' => $item['code']]);
            $category = $this->processCategory($item['category']);
            if (!$product) {
                $product = new Product();
            }
            $product
                ->setName($item['name'])
                ->setDescription($item['description'])
                ->setPrice($item['price'])
                ->setCode($item['code'])
                ->setCategory($category);

            $this->productRepository->save($product, true);
        }
    }

    /**
     * @return CategoryProduct
     */
    public function processCategory(array $category): CategoryProduct
    {
        $categoryOfProduct = $this->categoryProductRepository->find($category['id']);
        if (!$categoryOfProduct) {
            $categoryOfProduct = new CategoryProduct();
        }
        $categoryOfProduct->setName($category['name']);
        $this->categoryProductRepository->save($categoryOfProduct, true);

        return $categoryOfProduct;
    }
}