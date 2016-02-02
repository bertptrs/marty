<?php

namespace marty\tests\unit;

use marty\SmartyRenderer;
use PHPUnit_Framework_TestCase;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class SmartyRendererTest extends PHPUnit_Framework_TestCase
{
    private static $smartyDir;

    /**
     * Create a smarty compile directory in the temp dir.
     */
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();
        static::$smartyDir = sys_get_temp_dir() . "/marty_test_compile/";
        mkdir(static::$smartyDir, 0777, true);
    }

    /**
     * Remove the smarty temp dir.
     */
    public static function tearDownAfterClass()
    {
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator(static::$smartyDir,
            RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::CHILD_FIRST
        );

        foreach ($files as $file) {
            if ($file->isDir()) {
                rmdir($file->getRealPath);
            } else {
                unlink($file->getRealPath());
            }
        }
        rmdir(static::$smartyDir);
        parent::tearDownAfterClass();
    }

    /**
     * Get a mock object for the config.
     *
     * This mock implements the neccessary parameters for Smarty to start.
     *
     * @return mako\config\Config; Description
     */
    private function getConfigInstance()
    {
        $mockConfig = $this->getMockBuilder('mako\config\Config')->disableOriginalConstructor()->getMock();
        $configMap  = [
            "marty::smarty.templateDir" => dirname(__DIR__) . "/resources/views",
            "marty::smarty.compileDir" => static::$smartyDir,
            "marty::smarty.pluginDirs" => [dirname(__DIR__) . "/resources/plugins"],
        ];
        $mockConfig->expects($this->any())->method("get")->will(
            $this->returnCallback(function ($arg) use ($configMap) {
                if (array_key_exists($arg, $configMap)) {
                    return $configMap[$arg];
                } else {
                    throw new \InvalidArgumentException("No such key: $arg");
                }
            })
        );

        return $mockConfig;
    }

    /**
     * Test whether we can actually produce a valid instance.
     */
    public function testGetInstance()
    {
        $instance = new SmartyRenderer($this->getConfigInstance());

        $this->assertInstanceOf('marty\SmartyRenderer', $instance);

        return $instance;
    }

    public function renderProvider()
    {
        $files   = [];
        $basedir = dirname(__DIR__) . "/resources/views/";
        $it      = new RecursiveIteratorIterator(new \RecursiveDirectoryIterator($basedir));
        foreach ($it as $template) {
            if (substr($template->getRealPath(), -4) != ".tpl") {
                // Not a template.
                continue;
            }

            $files[] = [substr(substr($template->getRealPath(),
                        mb_strlen($basedir)), 0, -4)];
        }

        return $files;
    }

    /**
     * Test the rendering capabilities of the engine.
     *
     * @param string $templateFile
     * @dataProvider renderProvider
     */
    public function testRender($templateFile)
    {
        $instance  = new SmartyRenderer($this->getConfigInstance());
        $variables = $this->getTemplateVariables($templateFile);

        $result   = $instance->render($templateFile . ".tpl", $variables);
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
