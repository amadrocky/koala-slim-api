<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../src/config/db.php';

$app = AppFactory::create();

// Get all users
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

// Get favorites users
$app->get('/api/users/favorites', function (Request $request, Response $response, $args) {
    $sql = "SELECT * FROM users WHERE favorite = 1";

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

// Get one user
$app->get('/api/users/{id}', function (Request $request, Response $response, $args) {
    $id = $request->getAttribute('id');

    $sql = "SELECT * FROM users WHERE id = $id";

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

// Add user
$app->post('/api/users/add', function (Request $request, Response $response, $args) {
    $name = $request->getParam('name');
    $description = $request->getParam('description');

    $sql = "INSERT INTO users (name, description, favorite) VALUES(?,?,?)";

    try {
        $db = new db();
        $pdo = $db->connect();
        $stmt = $pdo->prepare($sql)->execute([$name, $description, 0]);

        echo '{"msg": {"resp": "User added"}}';
    } catch (\PDOException $e) {
        echo '{"msg": {"resp": '.$e->getMessage().'}}';
    }
});

// Add user to favorites
$app->put('/api/users/{id}/add-favorite', function (Request $request, Response $response, $args) {
    $id = $request->getAttribute('id');

    $sql = "UPDATE users SET favorite = 1 WHERE id=?";

    try {
        $db = new db();
        $pdo = $db->connect();
        $stmt = $pdo->prepare($sql)->execute([$id]);

        echo '{"msg": {"resp": "User added to favorites"}}';
    } catch (\PDOException $e) {
        echo '{"msg": {"resp": '.$e->getMessage().'}}';
    }
});

// Remove user to favorites
$app->put('/api/users/{id}/remove-favorite', function (Request $request, Response $response, $args) {
    $id = $request->getAttribute('id');

    $sql = "UPDATE users SET favorite = 0 WHERE id=?";

    try {
        $db = new db();
        $pdo = $db->connect();
        $stmt = $pdo->prepare($sql)->execute([$id]);

        echo '{"msg": {"resp": "User added to favorites"}}';
    } catch (\PDOException $e) {
        echo '{"msg": {"resp": '.$e->getMessage().'}}';
    }
});

// Add or update user description
$app->put('/api/users/{id}/update-description', function (Request $request, Response $response, $args) {
    $id = $request->getAttribute('id');
    $description = $request->getParam('description');

    $sql = "UPDATE users SET description =? WHERE id=?";

    try {
        $db = new db();
        $pdo = $db->connect();
        $stmt = $pdo->prepare($sql)->execute([$description, $id]);

        echo '{"msg": {"resp": "User description updated"}}';
    } catch (\PDOException $e) {
        echo '{"msg": {"resp": '.$e->getMessage().'}}';
    }
});

// Remove user
$app->delete('/api/users/{id}/delete', function (Request $request, Response $response, $args) {
    $id = $request->getAttribute('id');

    $sql = "DELETE FROM users WHERE id=?";

    try {
        $db = new db();
        $pdo = $db->connect();
        $stmt = $pdo->prepare($sql)->execute([$id]);

        echo '{"msg": {"resp": "User deleted"}}';
    } catch (\PDOException $e) {
        echo '{"msg": {"resp": '.$e->getMessage().'}}';
    }
});

$app->run();