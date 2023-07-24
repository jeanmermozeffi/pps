<?php

namespace PS\MobileBundle\Service;
use Symfony\Component\HttpFoundation\File\File;


class ApiUploadedFile extends File
{
    

    public function __construct(string $base64Content)
    {
        
        $tempDir = sys_get_temp_dir();
        $filePath = tempnam($tempDir, 'UploadedFile');
        $file = file_put_contents($filePath, base64_decode($base64Content));
        //$image = getimagesize($filePath);
        parent::__construct($filePath, true);
    }


}