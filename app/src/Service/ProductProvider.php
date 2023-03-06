<?php

namespace App\Service;


use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;


class ProductProvider implements ProductProviderInterface
{
    private $productDirectory;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * ProductProvider constructor.
     * @param EntityManagerInterface $entityManager
     * @param $productDirectory
     */
    public function __construct($productDirectory, EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->productDirectory = $productDirectory;
    }

    public function getCategories()
    {
        $categories = json_decode($this->getFileJson('categories.json'), true);

        return $categories;
    }

    public function getProducts()
    {
        $products = json_decode($this->getFileJson('products.json'), true);

        return $products;
    }

    private function getFileJson(string $file)
    {
        return file_get_contents($this->productDirectory.$file);
    }

    public function updateProdsAndCats()
    {
        /**
         * @todo перенести функціонал з консольної команди в цей сервіс
         */
    }
    /**
     * @param Product
     */
    public function save(Product $product): void
    {
        // Persist in database
        $this->entityManager->persist($product);
        $this->entityManager->flush();

    }
}