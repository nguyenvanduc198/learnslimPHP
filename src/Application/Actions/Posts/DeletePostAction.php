<?php

declare(strict_types=1);

namespace App\Application\Actions\Posts;

use App\Application\Actions\Action;
use Psr\Http\Message\ResponseInterface as Response;

class DeletePostAction extends Action
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $body = $this->request->getParsedBody();
        $catid = $this->args['catid'];
        $statement = $this->db->prepare("SELECT * FROM posts WHERE id=?");
        $statement->execute([$catid]);
        $post = $statement->fetchAll();



        return $this->respondWithData($post, 401);
    }
}
