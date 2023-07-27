<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[AsCommand(
    name: 'app:set_test_mail',
    description: 'Add a short description for your command',
)]
class SetTestMailCommand extends Command
{

    public function __construct(
        private HttpClientInterface $httpClient
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $response = $this->httpClient->request('POST', "https://api.brevo.com/v3/smtp/email", [
            "headers" => [
                "accept" =>"application/json",
                "api-key" => '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',
                "content-type" => "application/json"
            ],
            'json' => [
                "sender" => [
                    "name" => "Ilyes Attia",
                    'email' =>'ilyes200876@live.fr'
                ],
                "to" => [
                    [
                        "email" =>'ilyesattia69@gmail.com',
                    'name' => 'IlyesAttia'
                    ],
                ],
                "subject" => "Bonjour !!!",
                "htmlContent" => "<p>Salut, je me dis bonjour :p !!!</p>"
            ]
        ]);

        return Command::SUCCESS;
    }
}
