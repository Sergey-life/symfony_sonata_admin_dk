<?php

namespace App\Command;

use App\Entity\Product;
use App\Repository\CategoryProductRepository;
use App\Repository\ProductRepository;
use App\Service\ProductProvider;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:update:product',
    description: 'Create and update a new category.',
    hidden: false,
    aliases: ['app:update:product']
)]
class UpdateProductCommand extends Command
{
    private $productProvider;

    private $productRepository;

    private $categoryProductRepository;

    protected static $defaultName = 'app:update:product';

    public function __construct(
        ProductProvider $productProvider,
        ProductRepository $productRepository,
        CategoryProductRepository $categoryProductRepository
    )
    {
        $this->categoryProductRepository = $categoryProductRepository;
        $this->productRepository = $productRepository;
        $this->productProvider = $productProvider;
        parent::__construct();
    }
    protected function configure(): void
    {
        $this
            // the command help shown when running the command with the "--help" option
            ->setHelp('This command allows you to create a user...')
        ;
    }
    /*
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        foreach ($this->productProvider->getProducts() as $item) {
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

            $this->productProvider->save($product);
        }

        return Command::SUCCESS;
    }
}