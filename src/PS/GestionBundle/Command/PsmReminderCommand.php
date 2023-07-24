<?php

namespace PS\GestionBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class PsmReminderCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('psm:reminder')
            ->setDescription('Envoi un SMS de rappel aux patients')
            //->addOption('type', null, InputOption::VALUE_OPTIONAL)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $rappel = $this->getContainer()->get('app.reminder');

        $result = $rappel->sendMessage();


        if ($result[1] > 0) {
            $output->writeln(utf8_decode($result[1].'/'.$result[0].' envoyé(s) à '. date("d/m/y H:m:s")));
        } else {
            $output->writeln(utf8_decode('Aucun message envoyé à '. date("d/m/y H:m:s")));
        }
    }

}
