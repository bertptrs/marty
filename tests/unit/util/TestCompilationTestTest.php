<?php
/**
 * Created by PhpStorm.
 * User: bert
 * Date: 27/12/18
 * Time: 14:36
 */

namespace marty\tests\unit\util;

use marty\util\TemplateCompilationTest;

class TestCompilationTestTest extends TemplateCompilationTest
{

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
            'pluginDirs' => [$base . '/resources/views'],
        ];
    }
}
