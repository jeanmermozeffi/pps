<?php
namespace PS\GestionBundle\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{
    private $targetDirectory;

    public function __construct($targetDirectory)
    {
        $this->setTargetDirectory($targetDirectory);
    }

    public function upload(UploadedFile $file, $prefix = null, &$path = null, $newFileName = false)
    {

        if ($prefix == 'private') {
            $path = dirname($this->targetDirectory).'/data';
        } else {
            $path = dirname($this->targetDirectory).'/web/uploads';
        }


        $this->setTargetDirectory($path);

        $extension = $file->guessExtension();

        if (!$extension) {
            $extension = $file->getClientOriginalExtension();
        }

        $fileName = $newFileName === false ? md5(uniqid()) : $newFileName;
        $fileName .= '.'.$extension;

    
        $file->move($this->getTargetDirectory(), $fileName);


        $path .= "/{$fileName}";

        return $fileName;
    }

    public function getTargetDirectory()
    {
        return $this->targetDirectory;
    }


    public function setTargetDirectory($targetDirectory)
    {
        $this->targetDirectory = $targetDirectory;
    }
}