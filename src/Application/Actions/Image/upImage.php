<?php

declare(strict_types=1);

namespace App\Application\Actions\Image;

use App\Application\Actions\Action;
use Psr\Http\Message\ResponseInterface as Response;

class upImage extends Action
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        // $body = $this->request->getParsedBody();
        // $uploadedImage= $this->request->getUploadedFiles()['image'];
        // $directory=$this->setting->get('uploadDir');
        // if()

        $body = $this->request->getParsedBody();

        $uploadedImage = $this->request->getUploadedFiles()['image'];

        $directory = $this->settings->get('uploadDir');

        if ($uploadedImage->getError() === UPLOAD_ERR_OK) {
            $filename = $this->moveUploadedFile($directory, $uploadedImage);
            $result = 'uploaded ' . $filename;
        }



        return $this->respondWithData($result);
    }


    protected function moveUploadedFile($dir, $uploadedFile)
    {
        $originName = $uploadedFile->getClientFilename();
        $extension = pathinfo($originName, PATHINFO_EXTENSION);
        $basename = bin2hex(random_bytes(8)); // see http://php.net/manual/en/function.random-bytes.php
        $filename = $basename . "-" . $originName;

        $uploadedFile->moveTo($dir . DIRECTORY_SEPARATOR . $filename);

        return $filename;
    }
}
