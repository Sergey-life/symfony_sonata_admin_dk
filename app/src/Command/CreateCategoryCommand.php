<?php

namespace App\Command;

use App\Entity\CategoryProduct;
use App\Repository\CategoryProductRepository;
use App\Service\ProductProvider;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


#[AsCommand(
    name: 'app:create-category',
    description: 'Creates a new category.',
    hidden: false,
    aliases: ['app:create:category']
)]
class CreateCategoryCommand extends Command
{
    private $productProvider;

    private $categoryProductRepository;

    protected static $defaultName = 'app:create:category';

    public function __construct(ProductProvider $productProvider, CategoryProductRepository $categoryProductRepository)
    {
        $this->categoryProductRepository = $categoryProductRepository;
        $this->productProvider = $productProvider;
        parent::__construct();
    }
    protected function configure(): void
    {
        $this->setHelp('This command allows you to create a category...');
    }
    /*
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        foreach ($this->productProvider->getCategories() as $item) {
            $category = new CategoryProduct();
            if (!$this->categoryProductRepository->findOneBy(['name' => $item['name']])) {
                $category->setName($item['name']);
                $this->categoryProductRepository->save($category, true);
            }
        }

        return Command::SUCCESS;
    }
}