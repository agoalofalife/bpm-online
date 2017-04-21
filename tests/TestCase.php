<?php
namespace agoalofalife\Tests;
use Mockery;
use Faker\Factory;


abstract class TestCase extends \PHPUnit\Framework\TestCase
{
    protected $container;

    protected function tearDown()
    {
        parent::tearDown();
        Mockery::close();
    }
    protected function faker()
    {
        return Factory::create();
    }
    /**
     * @param string $class
     *
     * @return \Mockery\Mock|mixed
     */
    protected function mock($class)
    {
        return Mockery::mock($class);
    }
}