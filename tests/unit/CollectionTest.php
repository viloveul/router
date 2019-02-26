<?php

class CollectionTest extends \Codeception\Test\Unit
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
    public function testInstance()
    {
        $collection = new Viloveul\Router\Collection();
        $this->tester->assertInstanceOf(Viloveul\Router\Contracts\Collection::class, $collection);
    }

    public function testAddRoute()
    {
        $foo = new Viloveul\Router\Route('/foo/{:bar}', function($bar) {
            return $bar;
        });
        $collection = new Viloveul\Router\Collection();
        $collection->add('foo', $foo);
        $this->tester->assertTrue($collection->exists('foo'));
    }

    public function testGetRoute()
    {
        $foo = new Viloveul\Router\Route('/foo/{:bar}', function($bar) {
            return $bar;
        });
        $collection = new Viloveul\Router\Collection();
        $collection->add('foo', $foo);
        $this->tester->assertEquals('foo', $collection->get('foo')->getName());
    }

    public function testMerger()
    {
        $foo = new Viloveul\Router\Route('/foo/{:bar}', function($bar) {
            return $bar;
        });
        $collection = new Viloveul\Router\Collection();
        $collection->add('foo', $foo);

        $another = new Viloveul\Router\Collection();
        $another->add('bar', $foo);

        $collection->merge($another);
        $this->tester->assertTrue($collection->exists('bar'));
    }
}
