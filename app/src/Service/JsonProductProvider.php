<?php

namespace App\Service;


use App\Entity\CategoryProduct;
use App\Entity\Product;
use App\Repository\CategoryProductRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;


class JsonProductProvider implements ProductProviderInterface
{
    private $productDirectory;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    private $productRepository;

    private $categoryProductRepository;

    /**
     * JsonProductProvider constructor.
     * @param EntityManagerInterface $entityManager
     * @param $productDirectory
     */
    public function __construct(
        $productDirectory,
        EntityManagerInterface $entityManager,
        ProductRepository $productRepository,
        CategoryProductRepository $categoryProductRepository
    )
    {
        $this->entityManager = $entityManager;
        $this->productDirectory = $productDirectory;
        $this->productRepository = $productRepository;
        $this->categoryProductRepository = $categoryProductRepository;
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
        foreach ($this->getProducts() as $item) {
            if (!$this->categoryProductRepository->findOneBy(['name' => $item['category']])) {
                $category = new CategoryProduct();
                $category->setName($item['category']);
                $this->categoryProductRepository->save($category, true);
            }
            if (!$this->productRepository->findOneBy(['code' => $item['code']])) {
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
                ->setCategory($this->categoryProductRepository->findOneBy(['name' => $item['category']]));

            $this->save($product);
        }
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