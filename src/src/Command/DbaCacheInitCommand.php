<?php

namespace App\Command;

use Faker\Factory as Faker;
use InMemory\Dba\InMemoryDatabase;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'dba:cache:init',
    description: 'Add a short description for your command',
)]
class DbaCacheInitCommand extends Command
{
    protected function configure(): void
    {
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $faker = Faker::create();
        $db = new InMemoryDatabase();
        $cache = $db->initialize();

        foreach (range(1, 5) as $value) {
            $uuid  = $faker->uuid();
            # Insert values into cache
            $cache->put(
                $uuid, 
                [
                    'idx' => $value,
                    'uuid' => $uuid,
                    'fullname' =>  $faker->name(),
                    'email' => $faker->safeEmail(),
                    'phone' => $faker->phoneNumber(),
                    'job' => $faker->jobTitle(),
                    'credit_card' => $faker->creditCardType(),
                    'credit_card_number' => $faker->creditCardNumber(),
                    'iban' => $faker->iban(),
                ]
            );
            $output->writeln([
                'Account UUID ' . $uuid . '✅'
            ]);
        }

        # Update values into cache
        $content = $cache->get($uuid);
        $newArr = [
            'country_code' => $faker->countryCode(),
            'currency_code' => $faker->currencyCode(),
        ];
        $cache->put($uuid, array_merge($content, $newArr));

        dump(
            $uuid,
            $content,
            $cache->has($uuid),
            $cache->get($uuid),
            $cache->delete($uuid),
            $cache->get($uuid)
        );

        # Remove cache file
        $db->truncate();

        return Command::SUCCESS;
    }
}
