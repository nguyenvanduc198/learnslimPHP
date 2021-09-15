<?php

declare(strict_types=1);

namespace App\Application\Actions\Category;

use App\Application\Actions\Action;
use Psr\Http\Message\ResponseInterface as Response;

class DeleteCategory extends Action
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $catId = $this->args['catid'];
        $statement = $this->db->prepare("DELETE FROM categories WHERE id=?");
        $statement->execute([$catId]);

        // $category = $statement->fetchAll();

        return $this->respondWithData("you have been deleted category successfully", 200);
    }
}
