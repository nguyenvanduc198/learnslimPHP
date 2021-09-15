<?php

declare(strict_types=1);

use App\Application\Actions\Auth\loginAction;
use App\Application\Actions\Auth\logoutAction;
use App\Application\Actions\Category\AddCategory;
use App\Application\Actions\Category\DeleteCategory;
use App\Application\Actions\Category\DetailCategory;
use App\Application\Actions\Category\ListCategory;
use App\Application\Actions\Category\UpdateCategory;
use App\Application\Actions\Image\downImage;
use App\Application\Actions\Image\upImage;
use App\Application\Actions\Posts\AddPostAction;
use App\Application\Actions\Posts\DeletePostAction;
use App\Application\Actions\Posts\DetailPostAction;
use App\Application\Actions\Posts\ListPostAction;
use App\Application\Actions\Posts\UpdatePostAction;
use App\Application\Actions\User\ListUsersAction;
use App\Application\Actions\User\ViewUserAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;
use Slim\Psr7\Stream;

return function (App $app) {
    $app->options('/{routes:.*}', function (Request $request, Response $response) {
        // CORS Pre-Flight OPTIONS Request Handler
        return $response;
    });

    $app->get('/', function (Request $request, Response $response) {
        $response->getBody()->write('Hello world!');
        return $response;
    });
    // $app->get('/xinchao[/{lang:[a-z]{2}}]', function (Request $request, Response $response, array $args) {

    //     if (array_key_exists('lang', $args) && $args['lang'] === 'vi') {
    //         $welcomeMessage = "Xin chao cac ban";
    //     } else {
    //         $welcomeMessage = "Hello everyone!";
    //     }
    //     $response->getBody()->write($welcomeMessage);
    //     return $response;
    // });
    $app->get('/product/{productId:[1-9]{1}[0-9]*}', function (Request $request, Response $response, array $args) {

        $productId = intval($args['productId']);

        if (array_key_exists('productId', $args)) {
            $output = "Đang xem sản phẩm $productId";
        }

        $tinhTrang = "con hang";
        if ($productId < 10) {
            $tinhTrang = "het hang";
        }

        $response->getBody()->write("$output : $tinhTrang");
        return $response;
    });

    $app->get('/xinchao/{lang}', function (Request $request, Response $response, array $args) {
        if ($args['lang'] === 'vi') {
            $welcomeMessage = "Ban dang noi tieng viet";
        } else {
            $welcomeMessage = "Ban dang noi tieng anh";
        }
        $response->getBody()->write($welcomeMessage);
        return $response;
    });

    $app->post('/product/{productId:[1-9]{1}[0-9]*}', function (Request $request, Response $response, array $args) {
        $productId = intval($args['productId']);
        if (array_key_exists('productId', $args)) {
            $output = "Đang xem sản phẩm $productId";
        }
        $tinhTrang = "con hang";
        if ($productId < 10) {
            $tinhTrang = "het hang";
        }
        $response->getBody()->write("$output : $tinhTrang");
        return $response;
    });

    $app->group('/users', function (Group $group) {
        $group->get('', ListUsersAction::class);
        $group->get('/{id}', ViewUserAction::class);
    });


    $app->group(
        '/test1',
        function (Group $group) {

            $group->get('', function (Request $request, Response $response) {
                $response->getBody()->write('Xin chao');
                return $response;
            });

            $group->get('/family', function (Request $request, Response $response) {
                $response->getBody()->write('Xin chao 2 me con');
                return $response;
            });
        }
    );
    // $app->post("/testaction", CalcAction::class);

    $app->get("/db", function (Request $request, Response $response, array $args) {
        try {
            $db = $this->get(PDO::class);
            // $db->exec("INSERT INTO posts ( title, content, category_id) VALUES ( 'abc', 'abc', 1)");

            $sth = $db->prepare("SELECT * FROM posts ");
            $sth->execute();
            $data = $sth->fetchAll(PDO::FETCH_ASSOC);
            $payload = json_encode($data);
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    });
    $app->get("/posts", function (Request $request, Response $response, array $args) {

        try {
            $db = $this->get(PDO::class);

            $sth = $db->prepare("SELECT * FROM posts");
            $sth->execute();

            $data = $sth->fetchAll(PDO::FETCH_CLASS);

            $payload = json_encode($data);
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    });
    $app->get("/posts/{postId}", function (Request $request, Response $response, array $args) {

        $postId = $args['postId'];

        try {
            $db = $this->get(PDO::class);

            $sth = $db->prepare("SELECT * FROM posts WHERE id = ?");
            $sth->execute([$postId]);

            $data = $sth->fetchAll(PDO::FETCH_CLASS);

            $payload = json_encode($data);
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    });

    // $app->post("/posts", function (Request $request, Response $response, array $args) {

    //     try {
    //         $db = $this->get(PDO::class);

    //         // $db->exec("INSERT INTO posts ( title, content, category_id) VALUES ( 'abc', 'abc', 1)");

    //         $sth = $db->prepare("SELECT * FROM posts WHERE id < 10");
    //         $sth->execute();

    //         $data = $sth->fetchAll(PDO::FETCH_CLASS);

    //         $payload = json_encode($data);
    //         $response->getBody()->write($payload);
    //         return $response->withHeader('Content-Type', 'application/json');
    //     } catch (\Throwable $th) {
    //         return $th->getMessage();
    //     }
    // });
    $app->get("/static/{filetype}/{filename}", function (Request $request, Response $response, array $args) {
        $filename = $args['filename'];
        $filetype = $args['filetype'];
        $directory = __DIR__ . "/../public/static";
        $path = $directory . "/$filetype/$filename";
        if (file_exists($path)) {
            $fh = fopen($path, 'rb');
            $file_stream = new Stream($fh);
            return $response->withBody($file_stream);
        }
        return $response->withStatus(404, 'File not found');
    });










    $app->get("/categories", ListCategory::class);
    $app->get("/category/{catid}", DetailCategory::class);

    $app->post("/category", AddCategory::class);
    $app->post("/category/{catId:[0-9]+}/update", UpdateCategory::class);
    $app->get("/category/{catid}/delete", DeleteCategory::class);
    $app->post("/login", loginAction::class);
    $app->get("/logout", logoutAction::class);
    $app->post("/uploadImage", upImage::class);
    $app->get("/downloadImage", downImage::class);
    $app->get("/listposts", ListPostAction::class);
    $app->get("/post/{catid}", DetailPostAction::class);
    $app->post("/post", AddPostAction::class);
    $app->post("/post/{catid:[0-9]+}/update", UpdatePostAction::class);
    $app->get("post/{catid}/delete", DeletePostAction::class);
};
