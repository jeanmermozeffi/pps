<?php

namespace PS\ParametreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation\Exclude;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Groups;

/**
 * @ExclusionPolicy("all")
 * PieceJointeMission
 *
 * @ORM\Table(name="fichier")
 * @ORM\Entity(repositoryClass="PS\ParametreBundle\Repository\FichierRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Fichier
{
    /**
     * @Expose
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Groups({"fichier"})
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=255)
     */
    private $url;



    /**
     * @var string
     *
     * @ORM\Column(name="path", type="string", length=255)
     */
    private $path;


    
    /**
     * @var string
     *
     * @ORM\Column(name="folder", type="string", length=255)
     */
    private $folder;

    /**
     * @var string
     *
     * @ORM\Column(name="alt", type="string", length=255)
     * 
     */
    private $alt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateFichier;

    /**
     * @var mixed
     * @Assert\NotNull(message="Veuillez sélectionner un fichier", groups={"FileRequired"})
     */
    private $file;


    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var mixed
     */
    private $webPath;

    // On ajoute cet attribut pour y stocker le nom du fichier temporairement
    /**
     * @var mixed
     */
    private $tempFilename;

    /**
     * @var string
     */
    private $uploadDir;

    public function __construct()
    {
       
        $this->dateFichier = new \DateTime();

    }

    // On modifie le setter de File, pour prendre en compte l'upload d'un fichier lorsqu'il en existe déjà un autre
    /**
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null)
    {
       
        
        
        $this->file = $file;

        // On vérifie si on avait déjà un fichier pour cette entité
        if (null !== $this->alt) {
            // On sauvegarde l'extension du fichier pour le supprimer plus tard
            $this->tempFilename = $this->alt;

            // On réinitialise les valeurs des attributs url et alt
            $this->url = null;
            $this->alt = null;
        }
    }

    /**
     * @return mixed
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set url
     *
     * @param string $url
     *
     * @return PieceJointeMission
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set alt
     *
     * @param string $alt
     *
     * @return PieceJointeMission
     */
    public function setAlt($alt)
    {
        $this->alt = $alt;

        return $this;
    }

    /**
     * Get alt
     *
     * @return string
     */
    public function getAlt()
    {
        return $this->alt;
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
        
       
        // Si jamais il n'y a pas de fichier (champ facultatif)
        if (null === $this->file) {
            //dump('foo00');exit;
            return false;
        }

       

        
        //dump('foo');exit;

        // Le nom du fichier est son id, on doit juste stocker également son extension
        // Pour faire propre, on devrait renommer cet attribut en « extension », plutôt que « url »
        //$this->url = $this->file->guessExtension();
        $clientOriginalName = $this->file->getClientOriginalName();

        $fileExt  = pathinfo($clientOriginalName, PATHINFO_EXTENSION);
        $baseName = pathinfo($clientOriginalName, PATHINFO_FILENAME);

        $this->url = $fileExt;



        // Et on génère l'attribut alt de la balise <img>, à la valeur du nom du fichier sur le PC de l'internaute
        $this->alt = substr(str_slug($baseName, '_'), 0, 255 - 1 - strlen($fileExt)) . '.' . $fileExt;
        if (!$this->title) {
            $this->title = $this->alt;
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        $uploadDir = $this->getUploadRootDir();
        // Si jamais il n'y a pas de fichier (champ facultatif)t
        if (null === $this->file) {
            return;
        }

        // Si on avait un ancien fichier, on le supprime
        if (null !== $this->tempFilename) {
            $file = $this->getFullFileName();

            if (file_exists($file)) {
                unlink($file);
            }
        }


        



        // On déplace le fichier envoyé dans le répertoire de notre choix
        $this->file->move(
           $this->getFullPath(), // Le répertoire de destination
           $this->id.'_'.$this->alt
        );
    }

    /**
     * @ORM\PreRemove()
     */
    public function preRemoveUpload()
    {
        // On sauvegarde temporairement le nom du fichier, car il dépend de l'id
        $this->tempFilename = $this->getFullFileName();
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        // En PostRemove, on n'a pas accès à l'id, on utilise notre nom sauvegardé
        if (file_exists($this->tempFilename)) {
            // On supprime le fichier
            unlink($this->tempFilename);
        }
    }

    /**
     * @param $uploadDir
     * @return mixed
     */
    private function setUploadDir($uploadDir)
    {
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        $this->uploadDir = $uploadDir;
        
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUploadDir()
    {
        // On retourne le chemin relatif vers l'image pour un navigateur
        return basename($this->folder);
    }

    protected function getUploadRootDir()
    {
        // On retourne le chemin relatif vers l'image pour notre code PHP
        return __DIR__ . '/../../../'.$this->getFolder();
    }


    protected function getUploadBaseDir()
    {
        // On retourne le chemin relatif vers l'image pour notre code PHP
        return __DIR__ . '/../../../'.$this->getFolder();
    }

    public function getWebPath()
    {
        return @$this->getFullFileName();
    }


    public function getFullPath()
    {
        return $this->getUploadBaseDir().$this->getPath();
    }

    public function getFullFileName()
    {
        return file_exists($this->getFullPath() . '/' . $this->alt) ?
        $this->getFullPath() . '/' . $this->alt :
        $this->getFullPath() . '/' . $this->id . '.' . $this->url;
    }


    public function getFileName()
    {
        return basename($this->getFullFileName());
    }

    /**
     * Set dateFichier
     *
     * @param \DateTime $dateFichier
     *
     * @return PieceJointeMission
     */
    public function setDateFichier($dateFichier)
    {
        $this->dateFichier = $dateFichier;

        return $this;
    }

    /**
     * Get dateFichier
     *
     * @return \DateTime
     */
    public function getDateFichier()
    {
        return $this->dateFichier;
    }

    /**
     * Set path
     *
     * @param string $path
     *
     * @return Fichier
     */
    public function setPath(string $path)
    {
        //$parts = explode('/../web/uploads/', $path);
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }


       /**
     * Set title
     *
     * @param string $title
     *
     * @return Fichier
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set folder
     *
     * @param string $folder
     *
     * @return Fichier
     */
    public function setFolder($folder)
    {
        $this->folder = $folder;

        return $this;
    }

    /**
     * Get folder
     *
     * @return string
     */
    public function getFolder()
    {
        return $this->folder ?: '../web/uploads';
    }
}
