<?php

namespace marty\tests\unit;

use marty\ParameterResolver;
use marty\PluginLoader;
use PHPUnit\Framework\TestCase;

/**
 * Test cases for the plugin loader.
 *
 * @author Bert Peters <bert.ljpeters@gmail.com>
 */
class PluginLoaderTest extends TestCase
{

    /**
     * Test whether the test plugin actually can be loaded using the loader.
     */
    public function testPluginLoad()
    {
        $container = $this->getMockBuilder('mako\syringe\Container')->disableOriginalConstructor()->getMock();
        $smarty    = $this->getMockBuilder('Smarty')->disableOriginalConstructor()->getMock();
        $smarty->expects($this->exactly(4))
            ->method('registerPlugin')
            ->with(
                $this->anything(),
                $this->anything(),
                $this->callback('is_callable')
        );

        $instance = new PluginLoader(new ParameterResolver($container));
        $dirs     = [dirname(__DIR__).'/resources/plugins'];
        $instance->loadPlugins($dirs, $smarty);
    }
}
