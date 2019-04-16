<?php 

class DispatchTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;
    
    protected function _before()
    {
    }

    protected function _after()
    {
    }

    // tests
    public function testInstanceWithCollection()
    {
        $e = null;
        try {
            $collection = new Viloveul\Router\Collection();
            $router = new Viloveul\Router\Dispatcher($collection);
        } catch (Exception $e) {
            // do nothing
        }
        $this->tester->assertNotInstanceOf(Exception::class, $e);
    }

    public function testCanDispatchRoute()
    {
        $foo = new Viloveul\Router\Route('GET /foo/{:bar}', function($bar) {
            return $bar;
        });
        $foo->setName('foo');
        $collection = new Viloveul\Router\Collection();
        $collection->add($foo);
        $router = new Viloveul\Router\Dispatcher($collection);
        $router->dispatch('GET', Zend\Diactoros\UriFactory::createUri('/foo/dor'));
        $route = $router->routed();
        $this->tester->assertEquals('foo', $route->getName());
    }

    public function testNotFound()
    {
        $this->tester->expectThrowable(Viloveul\Router\NotFoundException::class, function() {
            $foo = new Viloveul\Router\Route('GET /foo/{:bar}', function($bar) {
                return $bar;
            });
            $collection = new Viloveul\Router\Collection();
            $collection->add($foo);
            $router = new Viloveul\Router\Dispatcher($collection);
            $router->dispatch('GET', Zend\Diactoros\UriFactory::createUri('/foot/dor'));
        });
    }
}