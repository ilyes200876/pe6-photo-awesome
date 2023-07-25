<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use App\Repository\UserRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:set-admin',
    description: 'Add a short description for your command',
)]
class SetAdminCommand extends Command
{

    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserRepository $userRepository
    )
    {
        parent::__construct();
    }
    protected function configure(): void
    {
        $this
            ->addArgument('userEmail', InputArgument::REQUIRED, 'L\'email du user')
            // ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        $userEmail = $input->getArgument('userEmail');
        $output->writeln($userEmail);

        $userEntity = $this->userRepository->findOneBy(['email' => $userEmail]);

        if($userEntity !== null){
            $userEntity->setRoles(["ROLE_ADMIN"]);
            $this->entityManager->persist($userEntity);
            $this->entityManager->flush();
            $output->writeln("l'utilisateur " . $userEmail . " est devenu ADMIN");
            return Command::SUCCESS;
        }else{
            $output->writeln("l'utilisateur " . $userEmail . " n'est pas dans la bdd");  
            return Command::FAILURE;   
        }

        
    }
}
