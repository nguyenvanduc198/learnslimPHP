<?php

declare(strict_types=1);

namespace App\Application\Actions\Posts;

use App\Application\Actions\Action;
use Psr\Http\Message\ResponseInterface as Response;

class ListPostAction extends Action
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $body = $this->request->getParsedBody();
        $statement = $this->db->prepare("SELECT * FROM posts");
        $statement->execute();
        $posts = $statement->fetchAll();


        return $this->respondWithData($posts, 200);
    }
}
