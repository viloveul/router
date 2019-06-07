<?php

error_reporting(-1);
ini_set('display_errors', 'On');

require __DIR__ . '/vendor/autoload.php';

$collection = new Viloveul\Router\Collection();
$router = new Viloveul\Router\Dispatcher($collection);

$jos = new Viloveul\Router\Route('GET /abc/def/ghi', function () {
    echo 'Hello';
});
$collection->add($jos);

$router->setBase('/api');

try {
    $router->dispatch('GET', new Zend\Diactoros\Uri('http://localhost:99/api/abc/def/ghi'));
    $routed = $router->routed();
    print_r($routed);
} catch (Viloveul\Router\NotFoundException $e) {
    exit('404');
}
