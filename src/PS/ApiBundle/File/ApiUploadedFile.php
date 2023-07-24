<?php

namespace ApiBundle\File;
use Symfony\Component\HttpFoundation\File\File;


class ApiUploadedFile extends File
{
    private $fileSize;

    private $mimeType;

    private $filePath;

    public function __construct($base64Content)
    {
        
        $tempDir = sys_get_temp_dir();
        $filePath = tempnam($tempDir, 'UploadedFile');
        $file = file_put_contents($filePath, base64_decode($base64Content));
        parent::__construct($filePath, true);
    }


    public function setData($fileSize, $fileName, $mimeType)
    {
        $this->fileSize = $fileSize;
        $this->fileName = $fileName;
        $this->mimeType = $mimeType;
    }


    public function getData()
    {

    }

}