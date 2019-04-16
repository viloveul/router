<?php

class CollectionTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    /**
     * @return mixed
     */
    public function testAddRoute()
    {
        $foo = new Viloveul\Router\Route('/foo/{:bar}', function ($bar) {
            return $bar;
        });
        $collection = new Viloveul\Router\Collection();
        $collection->add($foo);
        $this->tester->assertTrue($collection->count() > 0);
    }

    // tests
    public function testInstance()
    {
        $collection = new Viloveul\Router\Collection();
        $this->tester->assertInstanceOf(Viloveul\Router\Contracts\Collection::class, $collection);
    }

    /**
     * @return mixed
     */
    public function testMerger()
    {
        $foo = new Viloveul\Router\Route('/foo/{:bar}', function ($bar) {
            return $bar;
        });
        $collection = new Viloveul\Router\Collection();
        $collection->add($foo);
        $a = $collection->count();

        $another = new Viloveul\Router\Collection();
        $another->add($foo);
        $b = $collection->count();

        $collection->merge($another);
        $this->tester->assertTrue($collection->count() === ($a + $b));
    }

    protected function _after()
    {
    }

    protected function _before()
    {
    }
}
