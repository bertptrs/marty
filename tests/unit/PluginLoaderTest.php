<?php

namespace marty\tests\unit;

use marty\PluginLoader;
use PHPUnit_Framework_TestCase;

/**
 * Test cases for the plugin loader.
 *
 * @author Bert Peters <bert.ljpeters@gmail.com>
 */
class PluginLoaderTest extends PHPUnit_Framework_TestCase
{

    /**
     * Test whether the test plugin actually can be loaded using the loader.
     */
    public function testPluginLoad()
    {
        $container = $this->getMockBuilder('mako\syringe\Container')->disableOriginalConstructor()->getMock();
        $smarty    = $this->getMockBuilder('Smarty')->disableOriginalConstructor()->getMock();
        $smarty->expects($this->once())
            ->method('registerPlugin')
            ->with($this->equalTo('function'), $this->equalTo('plugintest'),
                $this->callback('is_callable')
        );

        $instance = new PluginLoader($container);
        $dirs     = [dirname(__DIR__).'/resources/plugins'];
        $instance->loadPlugins($dirs, $smarty);
    }
}
