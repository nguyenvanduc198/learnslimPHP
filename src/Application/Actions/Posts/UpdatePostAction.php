<?php

declare(strict_types=1);

namespace App\Application\Actions\Posts;

use App\Application\Actions\Action;
use Psr\Http\Message\ResponseInterface as Response;

class UpdatePostAction extends Action
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $body = $this->request->getParsedBody();
        $title = $body['title'];
        // $content = $body['content'];
        // $category_id = $body['category_id'];
        $catid = $this->args['catid'];
        try {
            $db = $this->db;
            $statement = $this->db->prepare("UPDATE posts SET title=?  WHERE id=?");
            $statement->execute([$title,  $catid]);
        } catch (\Throwable $th) {
            return $this->respondWithData($th->getMessage());
        }

        // $post = $statement->fetchAll();



        return $this->respondWithData('you have been updated successfully', 200);
    }
}
