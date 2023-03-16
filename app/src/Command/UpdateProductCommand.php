<?php

namespace App\Command;

use App\Entity\Product;
use App\Repository\CategoryProductRepository;
use App\Repository\ProductRepository;
use App\Service\ProductImporter;
use App\Service\JsonProductProvider;
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
    /**
     * @var ProductImporter
     */
    private $importProduct;

    /**
     * @var string
     */
    protected static $defaultName = 'app:update:product';

    /**
     * @param ProductImporter $importProduct
     */
    public function __construct(ProductImporter $importProduct)
    {
        $this->importProduct = $importProduct;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setHelp('This command allows you to create a user...');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->importProduct->updateProdsAndCats();

        return Command::SUCCESS;
    }
}