<?php

namespace App\Command;

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

    protected static $defaultName = 'app:create:category';

    public function __construct(ProductProvider $productProvider)
    {
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
        $this->productProvider->getCategories();

        return Command::SUCCESS;
    }
}