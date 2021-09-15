<?php

declare(strict_types=1);

namespace App\Application\Actions\Category;

use App\Application\Actions\Action;
use Psr\Http\Message\ResponseInterface as Response;

class DetailCategory extends Action
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $catId = $this->args['catid'];
        $statement = $this->db->prepare("SELECT * FROM categories WHERE id=?");
        $statement->execute([$catId]);

        $category = $statement->fetchAll();

        if (array_key_exists("admin", $_SESSION)) {
            $result = 1 * $_SESSION["admin"];
        } else {
            $result = 0;
        }

        return $this->respondWithData($result, 200);
    }
}
