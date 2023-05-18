<?php

namespace App\Command;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpClient\HttpOptions;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:graphql:query',
    description: 'Make a GraphQL query - live',
)]
class GraphqlQueryCommand extends Command
{
    protected function configure(): void
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $query = <<<GRAPHQL
        query {
            post(id: 1) {
                id
                title
                body
            }
        }
        GRAPHQL;

        $options = (new HttpOptions())
            ->setJson(['query' => $query, 'variables' => []])
            ->setHeaders([
                'Content-Type' => 'application/json',
                'User-Agent' => 'SF6 GraphQL client'
            ])
        ;

        $response = HttpClient::create()
            ->request(
                'POST', 
                'https://graphqlzero.almansi.me/api',
                $options->toArray()
            )
            ->toArray()
        ;
        
        $io->note([
            'id' => $response['data']['post']['id'],
            'title' => $response['data']['post']['title'],
            'body' => $response['data']['post']['body'],
        ])
        ;

        $io->success('GraphQL query success !');

        return Command::SUCCESS;
    }
}
