<?php

namespace App\Command;

use App\Document\Product;
use Faker\Factory as Faker;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:mongo:init',
    description: 'Populate MongoDB with faker',
)]
class MongoInitCommand extends Command
{
    private const CLEAR = 'clear';

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
        $faker = Faker::create();
        $arg1 = $input->getArgument('arg1');

        if ($arg1 && self::CLEAR === $arg1) {
            $productRepository = $this->documentManager->getRepository(Product::class);
            $queryBuilder = $productRepository->createQueryBuilder();
            $queryBuilder->remove()->getQuery()->execute();
            $io->success('Clear data in MongoDB');
        }

        foreach (range(1, 10) as $value) {
            $product = new Product();
            $product
                ->setIdx($value)
                ->setName($faker->bs())
                ->setPrice($faker->randomFloat(1, 10, 100))
            ;
            $this->documentManager->persist($product);
            $io->note(sprintf('Persisting prodcut id: %s', $product->getId()));
        }
        $this->documentManager->flush();

        $io->success('New data successfully saved in MongoDB');

        return Command::SUCCESS;
    }
}
