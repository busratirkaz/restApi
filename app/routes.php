<?php
use Slim\Routing\RouteCollectorProxy;

require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();

$app->group('/api', function (RouteCollectorProxy $group) {
    $group->get('/posts', \App\Endpoints\PostsEndpoint::class);
    $group->get('/comments', \App\Endpoints\CommentsEndpoint::class);
    $group->get('/posts/{post_id}/comments', \App\Endpoints\PostCommentsEndpoint::class);
});

$app->run();
