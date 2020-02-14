<?php

namespace App\Command;

use App\Entity\State;
use App\Entity\Trip;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class UpdateStateCommand extends Command
{
    protected static $defaultName = 'app:update-state';
    private $em;


    public function __construct(EntityManagerInterface $em, string $name = null)
    {
        $this->em = $em;
        parent::__construct($name);
    }

    protected function configure()
    {
        $this
            ->setDescription('Update state Passed and Closed')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        //On récupère toutes les sorties actuelles
        $tripRepository = $this->em->getRepository(Trip::class);
        $alltrips = $tripRepository->findAll();

        //On récupère le status "Passée"
        $stateRepository = $this->em->getRepository(State::class);
        $statePassed = $stateRepository->find('5');
        $stateClosed = $stateRepository->find('3');






        foreach ($alltrips as $t){
            //Si la date de la sortie est passée, par rapport à la date d'aujourd'hui, le status devient "Passée"
            if($t->getDateTimeStart() === date d'aujourd'hui){
                $t->setState($statePassed);
                $io->success('L\'état de la sortie '|$t->getName()|' est devenu: Passée');
            }
            //Si la date de cloture de la sortie est passée, par rapport à la date d'aujourd'hui, le status devient "Cloturée"
            if('date aujourd'hui === $t->getRegistDeadline()){
                $t->setState($stateClosed);
                $io->success('L\'état de la sortie '|$t->getName()|' est devenu: Cloturée');
            }
        }





        return 0;
    }
}
