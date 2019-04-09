<?php

namespace marty\tests\unit;

use mako\syringe\Container;
use marty\ParameterResolver;
use marty\UnresolvableParameterException;
use PHPUnit\Framework\TestCase;
use ReflectionException;
use ReflectionFunction;
use stdClass;

function dummy_resolvable(stdClass $ignored)
{
}

function dummy_unresolvable(NonExistentClass $ignored)
{
}

function dummy_unresolvable2($ignored)
{
}

class ParameterResolverTest extends TestCase
{
    /**
     * Test whether we can resolve simple class can be resolved.
     *
     * @throws ReflectionException
     */
    public function testResolutionWorks()
    {
        $container = new Container();

        $instance = new ParameterResolver($container);
        $function = new ReflectionFunction('marty\tests\unit\dummy_resolvable');

        $parameters = $function->getParameters();

        $this->assertInstanceOf(stdClass::class, $instance->resolveParameter($parameters[0], []));
    }

    /**
     * Test that resolution failure throws the right exception.
     *
     * @throws ReflectionException
     */
    public function testResolutionCanFail()
    {
        $function = new ReflectionFunction('marty\tests\unit\dummy_unresolvable');
        $this->checkParameterResolutionFailure($function);
    }

    /**
     * Test that resolution failure also works for parameters without class.
     *
     * @throws ReflectionException
     */
    public function testResolutionCanFailWithoutClass()
    {
        $function = new ReflectionFunction('marty\tests\unit\dummy_unresolvable2');
        $this->checkParameterResolutionFailure($function);
    }

    private function checkParameterResolutionFailure(ReflectionFunction $function)
    {
        $container = new Container();
        $instance = new ParameterResolver($container);

        $parameters = $function->getParameters();
        $this->expectException(UnresolvableParameterException::class);
        $instance->resolveParameter($parameters[0], []);
    }
}
