<?php

namespace PS\GestionBundle\Form\EventListener;

use Doctrine\ORM\EntityManagerInterface;
use PS\GestionBundle\Entity\Patient;
use PS\ParametreBundle\Entity\Assurance;
use PS\ParametreBundle\Entity\Nationalite;
use PS\ParametreBundle\Entity\Pays;
use PS\ParametreBundle\Entity\Region;
use PS\ParametreBundle\Entity\Ville;
use PS\UtilisateurBundle\Entity\CompteAssocie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use PS\ParametreBundle\Entity\ListeAntecedent;

class PatientEditListener implements EventSubscriberInterface
{
    /**
     * @var mixed
     */
    private $em;

    /**
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public static function getSubscribedEvents()
    {
        return [
            FormEvents::PRE_SUBMIT   => 'onPreSubmit',
            FormEvents::SUBMIT       => 'onSubmit',
            FormEvents::POST_SUBMIT  => 'onPostSubmit',
            FormEvents::PRE_SET_DATA => 'onPreSetData',
        ];
    }

    /**
     * @param FormEvent $event
     */
    public function onSubmit(FormEvent $event)
    {

    }

    /**
     * @param FormEvent $event
     */
    public function onPostSubmit(FormEvent $event)
    {
        $form = $event->getForm();
        $data = $event->getData();

        /*if (!$info = $data->getPersonne()->getInfo()) {
        $info = new InfoPersonne();
        } else {
        $data->getPersonne()->
        }

        if ($data->getPays()) {
        $info->setPays($data->getPays());
        }*/

        //$data->getPersonne()->setInfo($info);
    }

    /**
     * @param FormEvent $event
     */
    public function onPreSetData(FormEvent $event)
    {
        $form = $event->getForm();
        $data = $event->getData();

        $this->addElements($form, $data->getPays() ?? null);
    }

    /**
     * @param FormInterface $form
     * @param Specialite $specialite
     */
    protected function addElements(FormInterface $form, Pays $pays = null)
    {

        $form->add('ville', EntityType::class, [
            'required'     => true,
            'placeholder'  => '----------',
            'label' => 'ville',
            'class'        => Ville::class,
            'choices'      => $pays ? $this->em->getRepository(Ville::class)->findByPays($pays) : [],
            'choice_label' => 'nom',
        ]);
    }

    //public function

    /**
     * @param FormEvent $event
     * @return null
     */
    public function onPreSubmit(FormEvent $event)
    {
        $form = $event->getForm();
        $data = $event->getData();

        $assurances = isset($data['ligneAssurances']) ? $data['ligneAssurances'] : [];
        $associes = $data['associes'] ?? [];
        //$associes = $data['personne']['associes'] ?? [];
        $repVille     = $this->em->getRepository(Ville::class);
        $repRegion    = $this->em->getRepository(Region::class);
        $repAssurance = $this->em->getRepository(Assurance::class);

        $repPatient     = $this->em->getRepository(Patient::class);
        $repPays        = $this->em->getRepository(Pays::class);
        $repNationalite = $this->em->getRepository(Nationalite::class);
        $repAssocie     = $this->em->getRepository(CompteAssocie::class);
        foreach ($assurances as &$assurance) {

            if (!($_assurance = $repAssurance->exists($assurance['assurance']))) {
                $_assurance = new Assurance();
                $_assurance->setNom($assurance['assurance']);
                $_assurance->setIdentifiant($repAssurance->getMaxIdentifiant());
                $this->em->persist($_assurance);
                $this->em->flush();
                $idAssurance = $_assurance->getId();
            } else {
                $idAssurance = $_assurance['id'];
            }

            $assurance['assurance'] = $idAssurance;

        }

        if (count($assurances)) {
            $data['ligneAssurances'] = $assurances;
        }

        $doublons = [];

        foreach ($associes as $key => &$associe) {
            $identifiant = $associe['identifiant'];
            $pin         = $associe['pin'];

            if (!in_array($identifiant, $doublons)) {
                $patient = $repPatient->findOneBy(compact('identifiant', 'pin'));

                if ($patient) {
                    $associe['associe'] = $patient->getId();
                } else {
                    unset($associes[$key]);
                }
            } else {
                $doublons[] = $identifiant;
                unset($associes[$key]);
            }

        }

        //dump($associes);exit;

        if (count($associes)) {
            $data['associes'] = $associes;
        }

        if (!empty($data['ville']) && !empty($data['pays'])) {

            if (!($_ville = $repVille->exists($data['ville'], ['a.pays' => $data['pays']]))) {

                $_ville = new Ville();
                $_ville->setNom($data['ville']);
                //$_ville->setRegion($repRegion->find(34));
                $_ville->setPays($repPays->find($data['pays']));
                $this->em->persist($_ville);
                $this->em->flush();
                $idVille = $_ville->getId();
            } else {
                $idVille = $_ville['id'];
            }

            $data['ville'] = $idVille;
        }

        if (!empty($data['nationalite'])) {

            if (!($_nationalite = $repNationalite->exists($data['nationalite']))) {

                $_nationalite = new Nationalite();
                $_nationalite->setLibNationalite($data['nationalite']);

                $this->em->persist($_nationalite);
                $this->em->flush();
                $idNationalite = $_nationalite->getId();
            } else {
                $idNationalite = $_nationalite['id'];
            }

            $data['nationalite'] = $idNationalite;
        }




        $this->addElements($form, $repPays->find($data['pays']));

        $event->setData($data);

    }
}
