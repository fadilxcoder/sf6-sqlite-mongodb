<?php

namespace App\Command;

use App\Document\Product;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:mongo:find',
    description: 'Find all or one data from MongoDB',
)]
class MongoFindCommand extends Command
{
    public function __construct(
        private DocumentManager $documentManager
    ) {
        parent::__construct();
    }
    protected function configure(): void
    {
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Truncate DB before save !')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $arg1 = $input->getArgument('arg1');

        if ($arg1) {
            $product = $this->documentManager->getRepository(Product::class)->find($arg1);
            $productArr = $this->productObjToArr($product);
            dump($productArr);
            $io->success('findOne()');

            return Command::SUCCESS;
        }

        $products = $this->documentManager->getRepository(Product::class)->findAll();

        foreach ($products as $product) {
            dump($this->productObjToArr($product));
        }

        $io->success('findAll()');

        return Command::SUCCESS;
    }

    private function productObjToArr(Product $product)
    {
        return [
            'id' => $product->getId(),
            'idx' => $product->getIdx(),
            'name' => $product->getName(),
            'price' => $product->getPrice(),
        ];
    }
}
