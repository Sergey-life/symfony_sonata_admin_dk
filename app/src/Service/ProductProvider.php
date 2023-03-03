<?php

namespace App\Service;


class ProductProvider
{
    private $productDirectory;

    public function __construct($productDirectory)
    {
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
}