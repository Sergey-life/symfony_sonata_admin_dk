<?php

namespace App\Service;


use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;


class JsonProductProvider implements ProductProviderInterface
{
    /**
     * @var string
     */
    private $productDirectory;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * JsonProductProvider constructor.
     * @param EntityManagerInterface $entityManager
     * @param $productDirectory
     */
    public function __construct(
        $productDirectory,
        EntityManagerInterface $entityManager
    )
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

    public function getFileJson(string $file)
    {
        return file_get_contents($this->productDirectory.$file);
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