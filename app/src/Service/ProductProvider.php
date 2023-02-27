<?php

namespace App\Service;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;

class ProductProvider
{
    private $productDirectory;
    private $categoryRepository;

    public function __construct($productDirectory ,CategoryRepository $categoryRepository)
    {
        $this->productDirectory = $productDirectory;
        $this->categoryRepository = $categoryRepository;
    }

    public function getCategories()
    {
        $categories = json_decode($this->getFileJson('categories.json'), true);
        foreach ($categories as $item) {
            $category = new Category();
            $category->setName($item['name']);
            $this->categoryRepository->save($category, true);
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