<?php

namespace Aleste\TrackerBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\ORM\EntityManager;

class AvisosCommand extends Command
{
    protected $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;

        parent::__construct();
    }

    protected function configure()
    {

        $this
            ->setName('gestion:sendAvisos')
            ->setDescription('Envía mensajes de aviso vía Emails')
            ->addArgument(
                'limit',
                InputArgument::OPTIONAL,
                'Cantidad limite de mensajes por ejecución'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $documentos = $this->em->getRepository('TrackerBundle:Documento')->findAll();
        $limit = ($input->getArgument('limit') == null ? 10 : $input->getArgument('limit'));

        $cant = 0;
        foreach ($documentos as $documento) {
            if ($cant < $limit) {
                $output->writeln($documento->__toString());
                $cant++;
            }

        }
    }
}