<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/vendor/autoload.php';

// Veritabanı bağlantısı için gerekli bilgiler
$servername = "localhost";
$username = "username";
$password = "password";
$dbname = "database";

// Veritabanı bağlantısını oluştur
$pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);

// Slim uygulamasını oluştur
$app = AppFactory::create();

// Posts endpoint'i
$app->get('/posts', function (Request $request, Response $response, $args) use ($pdo) {
    $stmt = $pdo->query("SELECT * FROM posts");
    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $response->getBody()->write(json_encode($posts));
    return $response->withHeader('Content-Type', 'application/json');
});

// Comments endpoint'i
$app->get('/comments', function (Request $request, Response $response, $args) use ($pdo) {
    $stmt = $pdo->query("SELECT * FROM comments");
    $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $response->getBody()->write(json_encode($comments));
    return $response->withHeader('Content-Type', 'application/json');
});

// Post'a ait comment'leri getiren endpoint
$app->get('/posts/{post_id}/comments', function (Request $request, Response $response, $args) use ($pdo) {
    $postId = $args['post_id'];
    $stmt = $pdo->prepare("SELECT * FROM comments WHERE post_id = :post_id");
    $stmt->execute(['post_id' => $postId]);
    $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $response->getBody()->write(json_encode($comments));
    return $response->withHeader('Content-Type', 'application/json');
});


$app->run();
?>
