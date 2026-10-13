<?php

use PHPUnit\Framework\TestSuite;

/**
 * AllTestsTest class
 *
 * Collects all test cases of the Seo plugin.
 */
class AllTestsTest extends CakeTestSuite
{
    /**
     * Assemble Test Suite
     *
     * @return \PHPUnit\Framework\TestSuite the instance of \PHPUnit\Framework\TestSuite
     */
    public static function suite(): TestSuite
    {
        $suite = new CakeTestSuite('All Seo Plugin Tests');
        foreach (glob(__DIR__ . DS . '*Test.php') as $file) {
            if ($file !== __FILE__) {
                $suite->addTestFile($file);
            }
        }
        foreach (glob(__DIR__ . DS . '*', GLOB_ONLYDIR) as $directory) {
            $suite->addTestDirectoryRecursive($directory);
        }

        return $suite;
    }
}
