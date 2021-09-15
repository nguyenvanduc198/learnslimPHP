<?php

declare(strict_types=1);

namespace App\Application\Actions\Auth;

use App\Application\Actions\Action;
use Psr\Http\Message\ResponseInterface as Response;

class loginAction extends Action
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $body = $this->request->getParsedBody();

        $username = $body['username'];
        $password = $body['password'];
        // $statement =$this->db->db->prepare
        $message = "Your username and password is not correct";

        if ($username === "testuser" and $password === "test") {
            $_SESSION["admin"] = 1;
            $message = "You have been login successfully as testuser";
        }

        return $this->respondWithData($message, 200);
    }
}
