<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../src/config/db.php';

$app = AppFactory::create();

$app->get('/api/users', function (Request $request, Response $response, $args) {
    $sql = "SELECT * FROM users";

    try {
        $db = new db();
        $pdo = $db->connect();
        $stmt = $pdo->query($sql);
        $users = $stmt->fetchAll(PDO::FETCH_OBJ);

        echo json_encode($users);
    } catch (\PDOException $e) {
        echo '{"msg": {"resp": '.$e->getMessage().'}}';
    }
});

$app->run();