<?php

namespace marty\util;

use PHPUnit\Framework\TestCase;
use Smarty;

/**
 * Class TemplateCompilationTest
 *
 * This utility class can be used to test the sanity of all your templates,
 * without needing integration tests for checking each individual render.
 *
 * To use it, simply extend this class in your test cases and implement the
 * abstract methods. PHPUnit 6 or higher is required to use this class.
 */
abstract class TemplateCompilationTest extends TestCase
{
    /**
     * @var string Temporary storage directory to put compiled templates into.
     */
    private static $compileDir;

    /**
     * @var Smarty smarty instance.
     */
    private $smarty;

    /**
     * Set up the compilation directory for use in the tests.
     *
     * @see TestCase::setUpBeforeClass()
     */
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        // Create a compile dir
        $compileDir = tempnam(sys_get_temp_dir(), 'smarty_compile_');
        unlink($compileDir);
        mkdir($compileDir);

        self::$compileDir = $compileDir;
    }

    /**
     * Clean the compilation directory after the tests.
     *
     * @see TestCase::tearDownAfterClass()
     */
    public static function tearDownAfterClass(): void
    {
        parent::tearDownAfterClass();

        $dir = escapeshellarg(self::$compileDir);

        // Could use PHP for this, but this is way shorter.
        `rm -r $dir`;
    }

    /**
     * Set up the Smarty instance for the test.
     *
     * @see TestCase::setUp()
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = $this->getMartyConfig();
        $smarty = new Smarty();
        $smarty->setTemplateDir($config['templateDir']);
        $smarty->setCompileDir(self::$compileDir);
        foreach ($config['pluginDirs'] as $pluginDir) {
            $smarty->addPluginsDir($pluginDir);
        }

        $this->smarty = $smarty;
    }

    /**
     * Get the smarty config.
     *
     * Required fields are templateDir and pluginDirs fields. See the documentation for details.
     *
     * @return array
     */
    abstract protected function getMartyConfig(): array;

    /**
     * Generate a list of all smarty templates.
     *
     * @return array
     */
    public function viewProvider()
    {
        $config = $this->getMartyConfig();
        $templateDir = $config['templateDir'];
        $templates = [];
        $it = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($templateDir));
        /** @var \SplFileInfo $file */
        foreach ($it as $file) {
            if ($file->isFile() && $file->getExtension() == 'tpl') {
                $templateName = substr($file->getPathname(), strlen($templateDir) + 1);
                $templateId = str_replace(['/', '.tpl'], ['.', ''], $templateName);
                $templates[$templateId] = [$templateName];
            }
        }

        return $templates;
    }

    /**
     * Test whether the specified template actually compiles.
     *
     * @param $templateName string The full path to the template file.
     * @dataProvider viewProvider
     */
    public function testTemplateCompiles($templateName)
    {
        $smarty = $this->smarty;
        /* @var \Smarty_Internal_Template $templateName */
        $template = new $smarty->template_class($templateName, $smarty);
        $template->source = \Smarty_Template_Source::load($template);

        try {
            $template->compileTemplateSource();
        } catch (\Exception $ex) {
            $this->fail('Compilation error in template: ' . $ex->getMessage());
        }

        // Bit of a dummy assertion, but it makes PHPUnit happy.
        // We are only interested in whether it compiles.
        $this->assertFalse($template->mustCompile());
    }
}
