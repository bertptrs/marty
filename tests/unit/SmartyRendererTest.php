<?php

namespace marty\tests\unit;

use mako\syringe\Container;
use marty\ParameterResolver;
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

        $pluginLoader = new PluginLoader(new ParameterResolver($container));
        $pluginLoader->loadPlugins([dirname(__DIR__).'/resources/plugins'], $smarty);

        $this->smarty = $smarty;
    }

    public function renderProvider()
    {
        $files   = [];
        $basedir = dirname(__DIR__).'/resources/views/';
        $it      = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($basedir));
        foreach ($it as $template) {
            if (mb_substr($template->getRealPath(), -4) != '.tpl') {
                // Not a template.
                continue;
            }

            $file = mb_substr($template->getRealPath(), mb_strlen($basedir), -4);

            $files[$file] = [$file];
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
