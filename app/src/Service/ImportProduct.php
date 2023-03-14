<?php

namespace App\Service;

use App\Entity\CategoryProduct;
use App\Entity\Product;
use App\Repository\CategoryProductRepository;
use App\Repository\ProductRepository;

class ImportProduct
{
    /**
     * @var JsonProductProvider
     */
    private $jsonProductProvider;

    /**
     * @var ProductRepository
     */
    private $productRepository;

    /**
     * @var CategoryProductRepository
     */
    private $categoryProductRepository;

    /**
     * @param JsonProductProvider $jsonProductProvider
     * @param ProductRepository $productRepository
     * @param CategoryProductRepository $categoryProductRepository
     */
    public function __construct(
        JsonProductProvider $jsonProductProvider,
        ProductRepository $productRepository,
        CategoryProductRepository $categoryProductRepository
    )
    {
        $this->jsonProductProvider = $jsonProductProvider;
        $this->productRepository = $productRepository;
        $this->categoryProductRepository = $categoryProductRepository;
    }

    /**
     * Update categories and products
     */
    public function updateProdsAndCats()
    {
        foreach ($this->jsonProductProvider->getProducts() as $item) {
            $category = $this->categoryProductRepository->findOneBy(['name' => $item['category']]);
            $product = $this->productRepository->findOneBy(['code' => $item['code']]);

            if (!$category) {
                $category = new CategoryProduct();
                $category->setName($item['category']);
                $this->categoryProductRepository->save($category, true);
            }
            if (!$product) {
                $product = new Product();
            }
            else
            {
                $product = $this->productRepository->findOneBy(['code' => $item['code']]);
            }
            $product
                ->setName($item['name'])
                ->setDescription($item['description'])
                ->setPrice($item['price'])
                ->setCode($item['code'])
                ->setCategory($category);

            $this->jsonProductProvider->save($product);
        }
    }
}