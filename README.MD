<p align="center">
<a href="https://travis-ci.com/viloveul/router">
<img src="https://travis-ci.com/viloveul/router.svg" alt="Build Status">
</a>
<a href="https://packagist.org/packages/viloveul/router">
<img src="https://poser.pugx.org/viloveul/router/d/total.svg" alt="Total Downloads">
</a>
<a href="https://packagist.org/packages/viloveul/router">
<img src="https://poser.pugx.org/viloveul/router/v/stable.svg" alt="Latest Stable Version">
</a>
</p>

# Installation

make sure your php version > 7.0

```bash
composer require viloveul/router
```

## HOW

```php
require __DIR__ . '/vendor/autoload.php';

// init collection object
$collection = new Viloveul\Router\Collection();

// declare class controller for handler
class MyController
{
	public function look($name)
	{
		return $name;
	}
}

// declare hello handler from my controller
$helloRoute = new Viloveul\Router\Route('GET /hello/:name', [MyController::class, 'look']);
// or 
// $helloRoute = new Viloveul\Router\Route('GET /hello/:name', [MyController::class, 'look']);
// add foo to collection
$collection->add($helloRoute);

// declare foo handler
$fooRoute = new Viloveul\Router\Route('GET /foo/{:bar}', function($bar) {
	return $bar;
});
// add foo to collection
$collection->add($fooRoute);

// declare test handler
$testRoute = new Viloveul\Router\Route('/test/:name', [
	'method' => 'GET|POST|PUT|PATCH',
	'handler' => function ($name) {
		return $name;
	}
]);
// add test to collection
$collection->add($testRoute);

// init object dispatcher with collection
$router = new Viloveul\Router\Dispatcher($collection);

// in action

$router->dispatch('GET', Zend\Diactoros\UriFactory::createUri('/hello/zafex'));
$route1 = $router->routed();

$router->dispatch('GET', Zend\Diactoros\UriFactory::createUri('/foo/hello-world'));
$route2 = $router->routed();

$router->dispatch('GET', Zend\Diactoros\UriFactory::createUri('/test/fajrul-akbar-zuhdi'));
$route3 = $router->routed();

var_dump($route1, $route2, $route3);

```
