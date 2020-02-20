<?php

namespace App\Command;

use App\Entity\State;
use App\Entity\Trip;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
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
    private $log;


    public function __construct(EntityManagerInterface $em, LoggerInterface $logger, string $name = null)
    {
        $this->log = $logger;
        $this->em = $em;
        parent::__construct($name);
    }

    protected function configure()
    {
        $this
            ->setDescription('Update state Passed and Closed');
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
        $stateInProgress = $stateRepository->find('4');

        //Récupération de la date d'aujourd'hui
        $now = new \DateTime();

        foreach ($alltrips as $t) {
            //Si la sortie est en cours, alors nous controlons les dates de début de sortie et les dates de clotures pour mettre à jour dans la BDD toutes les sorties en cours.
            if ($stateInProgress->getId() == 4) {

                //Nous récupérons les dates de début de sorties ainsi que leurs durées.
                $dateTrip = $t->getDateTimeStart();
                $duration = $t->getDuration();

                //Création d'un interval avec la durée de la sortie
                try {
                    $interval = new \DateInterval('PT'.$duration.'M');


                    //Clonage de la date de début de sortie
                    $dateTripClone = clone $dateTrip;
                    //Addition de la date de la sortie avec sa durée
                    $dateTripClone->add($interval);

                    //Si la date de début de sortie + sa durée est supérieure à la date d'aujourd'hui
                    if ($dateTripClone < $now) {
                        $t->setState($statePassed);

                        //MaJ BDD
                        $this->em->persist($t);

                        //Ecriture dans un fichier log
                        $this->log->info('L\'état de la sortie '.$t->getName().' est devenu: Passée');
                    } elseif ($now >= $t->getRegistDeadline()) {
                        $t->setState($stateClosed);

                        //MaJ BDD
                        $this->em->persist($t);

                        $this->log->info('L\'état de la sortie '.$t->getName().' est devenu: Cloturée');
                    } //Si la date de la sortie est égale à la date d'aujourd'hui, le status devient "En cours"
                    if ($dateTrip < $now and $now < $dateTripClone) {
                        $t->setState($stateInProgress);

                        //MaJ BDD
                        $this->em->persist($t);

                        $this->log->info('L\'état de la sortie '.$t->getName().' est devenu: En cours');
                    }
                } catch (\Exception $e) {
                }
                //Si la date de cloture de la sortie est passée, par rapport à la date d'aujourd'hui, le status devient "Cloturée"

            }
            $this->em->flush();
        }

        return 0;
    }
}
