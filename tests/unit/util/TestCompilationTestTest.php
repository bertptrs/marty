<?php

namespace marty\tests\unit\util;

use marty\util\TemplateCompilationTest;
use PHPUnit\Framework\TestCase;

class TestCompilationTestTest extends TestCase
{
    public function testTestWorks()
    {
        TemplateCompilationTest::setUpBeforeClass();

        $instance = $this->getMockForAbstractClass(TemplateCompilationTest::class);
        $instance->expects($this->any())
            ->method('getMartyConfig')
            ->will($this->returnValue($this->getMartyConfig()));

        foreach ($instance->viewProvider() as $view) {
            $instance->setUp();
            $this->assertIsArray($view);
            call_user_func_array([$instance, 'testTemplateCompiles'], $view);
            $instance->tearDown();
        }

        TemplateCompilationTest::tearDownAfterClass();
    }

    /**
     * Get the smarty config.
     *
     * Required fields are templateDir and pluginDirs fields. See the documentation for details.
     *
     * @return array
     */
    protected function getMartyConfig(): array
    {
        $base = dirname(__DIR__, 2);
        return [
            'templateDir' => $base . '/resources/views',
            'pluginDirs'  => [$base . '/resources/views'],
        ];
    }
}
