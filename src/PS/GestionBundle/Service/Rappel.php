<?php

namespace PS\GestionBundle\Service;

use Doctrine\ORM\EntityManagerInterface;
use PS\GestionBundle\Entity\RendezVous;
use PS\UtilisateurBundle\Entity\EnvoiRappel;
use PS\GestionBundle\Manager\SmsManager;
use PS\GestionBundle\Entity\Patient;
use Swift_Mailer;

class Rappel
{
    /**
     * @var mixed
     */
    private $em;

    /**
     * @var mixed
     */
    private $smsManager;


    private $mailer;

    /**
     * @param EntityManager $em
     * @param SmsManager $smsManager
     */
    public function __construct(EntityManagerInterface $em, SmsManager $smsManager, Swift_Mailer $mailer)
    {
        $this->em         = $em;
        $this->smsManager = $smsManager;
        $this->mailer = $mailer;
    }

    /**
     * @return mixed
     */
    private function getPatients()
    {
        return $this->em->getRepository(RendezVous::class)->getFromToday();
    }

    /**
     * @param $patient
     * @return mixed
     */
    private function getMessage($patient)
    {
        if ($patient->type == 1) {
            $message = sprintf('Vous avez un rappel pour votre vaccin %s', $patient->title);
        } else {
            $message = sprintf('Vous avez un RDV avec le Medecin %s à %s', $patient->title, $patient->date_rappel);
        }

        $message .= "\n\nPASS SANTE";
        return $message;
    }

    /**
     * @return mixed
     */
    public function sendMessage()
    {
        $envoiRappel = new EnvoiRappel();
        $_patient = $this->em->getRepository(Patient::class);
        $patients     = $this->getPatients();
        
        $totalsentMessages         = 0;
        $totalNumbers = 0;


        $transport = (new \Swift_SmtpTransport('mail.pass-sante.net', 465, 'ssl'))
        ->setUsername('info@pass-sante.net')
        ->setPassword(urldecode('{k7S^8Fu=.XN'));

        $mailer = new \Swift_Mailer($transport);


       
        
        foreach ($patients as $patient) {
            if ($patient->identifiant && $patient->pin) {
                if ($patient->email) {
                    if ($patient->type == 1) {
                        $title = 'RDV PASS SANTE';
                    } else {
                        $title = 'Rappel PASS SANTE';
                    }
                    $message = (new \Swift_Message($transport))
                    ->setSubject($title)
                    ->setFrom('info@pass-sante.net')
                    ->setTo($patient->email)
                    ->setBody($this->getMessage($patient), 'text/plain');
                    $mailer->send($message);
    
                }
                
    
    
                if ($patient->contact) {
                    $contact = preg_split('/[ \/,-]/', $patient->contact);
                    if (count($contact) && $contact[0]) {
                       
                        $response = $this->smsManager->send($this->getMessage($patient), $contact[0]);
                        if ($response && $response['success']) {
                           $totalsentMessages += $response['success'];
                          
                           $envoiRappel->setPatient($_patient->find($patient->patient_id));
                           $envoiRappel->setTypeRappel($patient->type);
                           $this->em->persist($envoiRappel);
                           $this->em->flush();
                        }
                        
                        $totalNumbers += 1;
                    }
    
                }
            }
          
        }
       
        return [$totalNumbers, $totalsentMessages];
    }




     /**
     * @return mixed
     */
    public function sendNotification()
    {
        $patients     = $this->getPatients();
        $totalsentMessages         = 0;
        $totalNumbers = 0;
        foreach ($patients as $patient) {
            
        }
        return [$totalNumbers, $totalsentMessages];
    }
}
