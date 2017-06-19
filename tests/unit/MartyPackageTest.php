<?php

namespace marty\tests\unit;

use marty\MartyPackage;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

/**
 * Test cases for the MartyPackage class.
 *
 * @author Bert Peters <bert.ljpeters@gmail.com>
 */
class MartyPackageTest extends TestCase
{

    /**
     * Test whether the bootstrap method of the package works according to
     * spec.
     *
     * This method verifies the bootstrap method works and whether it registers
     * the view renderer.
     */
    public function testBootstrap()
    {
        // Create a viewfactory that verifies the registerRenderer method is called.
        $viewFactory = $this->getMockBuilder('mako\view\ViewFactory')
            ->disableOriginalConstructor()->getMock();
        $viewFactory->expects($this->once())->method('extend')
            ->with(
                $this->equalTo('.tpl'),
                $this->callback('is_callable')
            );

        // Create a container to return our viewFactory.
        $container = $this->getMockBuilder('mako\syringe\Container')
            ->disableOriginalConstructor()->getMock();
        $container->expects($this->once())->method('get')->will($this->returnCallback(function ($item) use ($viewFactory) {
            switch ($item) {
            case 'config':
                return null;

            case 'view':
                return $viewFactory;

            default:
                $this->fail("Unneccsary item requested: $item");
            }
        }));
        // Ensure that it the Smarty class is registered.
        $container->expects($this->once())->method('register')
            ->with($this->equalTo('Smarty'), $this->anything());// Cannot verify that is is a callable.

        $package = new MartyPackage($container);
        // Use reflection to call protected method.
        $class  = new ReflectionClass('marty\MartyPackage');
        $method = $class->getMethod('bootstrap');
        $method->setAccessible(true);
        $method->invokeArgs($package, []);
    }
}
