<?php

declare(strict_types=1);

namespace App\Application\Actions\Posts;

use App\Application\Actions\Action;
use Psr\Http\Message\ResponseInterface as Response;

class AddPostAction extends Action
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $body = $this->request->getParsedBody();
        $title = $body['title'];
        $content = $body['content'];
        $category_id = $body['category_id'];
        try {
            $db = $this->db;
            $statement = $db->prepare("INSERT INTO posts (title,content,category_id) VALUES (?,?,?)");
            $statement->execute([$title, $content, $category_id]);
        } catch (\Throwable $th) {
            return $this->respondWithData($th->getMessage());
        }




        return $this->respondWithData('You have added a new post', 200);
    }
}
