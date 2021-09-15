<?php

declare(strict_types=1);

namespace App\Application\Actions\Image;

use App\Application\Actions\Action;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Psr7\Stream;

class downImage extends Action
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {

        $directory = $this->settings->get('uploadDir');
        $path = $directory . '/4379aa93437436d9-2021-08-13 slim L2.png';

        $fh = fopen($path, 'rb');
        $file_stream = new Stream($fh);

        return $this->response->withBody($file_stream)
            ->withHeader('Content-Disposition', 'attachment; filename=file.csv;')
            ->withHeader('Content-Type', mime_content_type($path))
            ->withHeader('Content-Length', filesize($path));
    }
}
