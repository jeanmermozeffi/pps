<?php
// src/AppBundle/EventListener/BrochureUploadListener.php
namespace PS\GestionBundle\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use PS\GestionBundle\Entity\Corporate;
use PS\GestionBundle\Service\FileUploader;
use PS\UtilisateurBundle\Entity\Personne;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class LogoUploadListener
{
    /**
     * @var mixed
     */
    private $uploader;

    /**
     * @param FileUploader $uploader
     */
    public function __construct(FileUploader $uploader)
    {
        $this->uploader = $uploader;
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        $this->uploadFile($entity);
    }

    /**
     * @param PreUpdateEventArgs $args
     */
    public function preUpdate(PreUpdateEventArgs $args)
    {
        $entity = $args->getEntity();

        $this->uploadFile($entity);
    }

    /**
     * @param $entity
     * @return null
     */
    private function uploadFile($entity)
    {
        $valid   = false;
     
        if (($entity instanceof Personne) || ($entity instanceof Corporate)) {
            $valid = true;
        }
        

        if (!$valid) {
            return;
        }


        if ($entity instanceof Personne) {
            $prefix = 'private';
            $file   = $entity->getSignature();
        } else {
            $prefix = '';
            $file   = $entity->getLogo();
        }

        // only upload new files
        if ($file instanceof UploadedFile) {

            $fileName = $this->uploader->upload($file, $prefix);
            if ($entity instanceof Corporate) {
                $entity->setLogo($fileName);
            } else {
                $entity->setSignature($fileName);
            }

        }
    }
}
