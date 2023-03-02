<?php

namespace App\Service;

use App\Entity\CategoryProduct;
use App\Repository\CategoryProductRepository;
use App\Repository\CategoryRepository;


class ProductProvider
{
    private $productDirectory;
    private $categoryProductRepository;

    public function __construct($productDirectory, CategoryProductRepository $categoryProductRepository)
    {
        $this->productDirectory = $productDirectory;
        $this->categoryProductRepository = $categoryProductRepository;
    }

    public function getCategories()
    {
        $categoriesFromFile = json_decode($this->getFileJson('categories.json'), true);
        foreach ($categoriesFromFile as $item) {
            $category = new CategoryProduct();
            if (!$this->categoryProductRepository->findOneBy(['name' => $item['name']])) {
                $category->setName($item['name']);
                $this->categoryProductRepository->save($category, true);
            }
        }
    }

    public function GetProducts()
    {

    }

    private function getFileJson(string $file)
    {
        return file_get_contents($this->productDirectory.$file);
    }
}