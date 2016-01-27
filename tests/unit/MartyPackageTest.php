<?php

namespace marty\tests\unit;

use marty\MartyPackage;
use PHPUnit_Framework_TestCase;
use ReflectionClass;

/**
 * Test cases for the MartyPackage class.
 *
 * @author Bert Peters <bert.ljpeters@gmail.com>
 */
class MartyPackageTest extends PHPUnit_Framework_TestCase
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
        $viewFactory->expects($this->once())->method('registerRenderer')
            ->with(
                $this->equalTo('.tpl'),
                $this->callback(function($subject) {
                    return is_callable($subject);
                })
            );

        // Create a container to return our viewFactory.
        $container = $this->getMockBuilder('mako\syringe\Container')
            ->disableOriginalConstructor()->getMock();
        $container->method("get")->will($this->returnCallback(function($item) use ($viewFactory)
        {
            switch ($item) {
            case 'config':
                return null;

            case 'view':
                return $viewFactory;

            default:
                $this->fail("Unneccsary item requested: $item");
            }
        }));

        $package = new MartyPackage($container);
        // Use reflection to call protected method.
        $class  = new ReflectionClass('marty\MartyPackage');
        $method = $class->getMethod("bootstrap");
        $method->setAccessible(true);
        $method->invokeArgs($package, []);
    }
}
