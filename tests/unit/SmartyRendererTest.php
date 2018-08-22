<?php

namespace marty\tests\unit;

use mako\syringe\Container;
use marty\PluginLoader;
use marty\SmartyRenderer;
use PHPUnit\Framework\TestCase;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use Smarty;

class SmartyRendererTest extends TestCase
{
    /**
     * @var Smarty
     */
    private $smarty;

    public function setUp()
    {
        parent::setUp();
        $smarty = new Smarty();
        $smarty->setTemplateDir(dirname(__DIR__).'/resources/views/');
        $smarty->setCompileDir(sys_get_temp_dir().uniqid('/martycompiletest_'));

        $container = $this->createMock(Container::class);

        $pluginLoader = new PluginLoader($container);
        $pluginLoader->loadPlugins([dirname(__DIR__).'/resources/plugins'], $smarty);

        $this->smarty = $smarty;
    }

    public function renderProvider()
    {
        $files   = [];
        $basedir = dirname(__DIR__).'/resources/views/';
        $it      = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($basedir));
        foreach ($it as $template) {
            if (substr($template->getRealPath(), -4) != '.tpl') {
                // Not a template.
                continue;
            }

            $files[] = [substr(substr(
                $template->getRealPath(),
                        mb_strlen($basedir)
            ), 0, -4)];
        }

        return $files;
    }

    public function tearDown()
    {
        parent::tearDown();

        // Delete smarty compilation dir
        $dir = escapeshellarg($this->smarty->getCompileDir());

        `rm -r $dir`;
    }

    /**
     * Test the rendering capabilities of the engine.
     *
     * @param string $templateFile
     * @dataProvider renderProvider
     */
    public function testRender($templateFile)
    {
        $instance  = new SmartyRenderer($this->smarty);
        $variables = $this->getTemplateVariables($templateFile);

        $result   = $instance->render($templateFile . '.tpl', $variables);
        $expected = $this->getCorrectRender($templateFile);

        $this->assertEquals($expected, $result);
    }

    private function getCorrectRender($templateFile)
    {
        return file_get_contents(dirname(__DIR__) . "/resources/renders/$templateFile.html");
    }

    private function getTemplateVariables($templateFile)
    {
        $filename = dirname(__DIR__) . "/resources/variables/$templateFile.php";

        if (is_file($filename)) {
            return include $filename;
        } else {
            return [];
        }
    }
}
