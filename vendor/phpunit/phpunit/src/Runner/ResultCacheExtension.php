<?php

declare (strict_types=1);
/*
 * This file is part of PHPUnit.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace ECSPrefix20210803\PHPUnit\Runner;

use function preg_match;
use function round;
/**
 * @internal This class is not covered by the backward compatibility promise for PHPUnit
 */
final class ResultCacheExtension implements \ECSPrefix20210803\PHPUnit\Runner\AfterIncompleteTestHook, \ECSPrefix20210803\PHPUnit\Runner\AfterLastTestHook, \ECSPrefix20210803\PHPUnit\Runner\AfterRiskyTestHook, \ECSPrefix20210803\PHPUnit\Runner\AfterSkippedTestHook, \ECSPrefix20210803\PHPUnit\Runner\AfterSuccessfulTestHook, \ECSPrefix20210803\PHPUnit\Runner\AfterTestErrorHook, \ECSPrefix20210803\PHPUnit\Runner\AfterTestFailureHook, \ECSPrefix20210803\PHPUnit\Runner\AfterTestWarningHook
{
    /**
     * @var TestResultCache
     */
    private $cache;
    public function __construct(\ECSPrefix20210803\PHPUnit\Runner\TestResultCache $cache)
    {
        $this->cache = $cache;
    }
    public function flush() : void
    {
        $this->cache->persist();
    }
    public function executeAfterSuccessfulTest(string $test, float $time) : void
    {
        $testName = $this->getTestName($test);
        $this->cache->setTime($testName, \round($time, 3));
    }
    public function executeAfterIncompleteTest(string $test, string $message, float $time) : void
    {
        $testName = $this->getTestName($test);
        $this->cache->setTime($testName, \round($time, 3));
        $this->cache->setState($testName, \ECSPrefix20210803\PHPUnit\Runner\BaseTestRunner::STATUS_INCOMPLETE);
    }
    public function executeAfterRiskyTest(string $test, string $message, float $time) : void
    {
        $testName = $this->getTestName($test);
        $this->cache->setTime($testName, \round($time, 3));
        $this->cache->setState($testName, \ECSPrefix20210803\PHPUnit\Runner\BaseTestRunner::STATUS_RISKY);
    }
    public function executeAfterSkippedTest(string $test, string $message, float $time) : void
    {
        $testName = $this->getTestName($test);
        $this->cache->setTime($testName, \round($time, 3));
        $this->cache->setState($testName, \ECSPrefix20210803\PHPUnit\Runner\BaseTestRunner::STATUS_SKIPPED);
    }
    public function executeAfterTestError(string $test, string $message, float $time) : void
    {
        $testName = $this->getTestName($test);
        $this->cache->setTime($testName, \round($time, 3));
        $this->cache->setState($testName, \ECSPrefix20210803\PHPUnit\Runner\BaseTestRunner::STATUS_ERROR);
    }
    public function executeAfterTestFailure(string $test, string $message, float $time) : void
    {
        $testName = $this->getTestName($test);
        $this->cache->setTime($testName, \round($time, 3));
        $this->cache->setState($testName, \ECSPrefix20210803\PHPUnit\Runner\BaseTestRunner::STATUS_FAILURE);
    }
    public function executeAfterTestWarning(string $test, string $message, float $time) : void
    {
        $testName = $this->getTestName($test);
        $this->cache->setTime($testName, \round($time, 3));
        $this->cache->setState($testName, \ECSPrefix20210803\PHPUnit\Runner\BaseTestRunner::STATUS_WARNING);
    }
    public function executeAfterLastTest() : void
    {
        $this->flush();
    }
    /**
     * @param string $test A long description format of the current test
     *
     * @return string The test name without TestSuiteClassName:: and @dataprovider details
     */
    private function getTestName(string $test) : string
    {
        $matches = [];
        if (\preg_match('/^(?<name>\\S+::\\S+)(?:(?<dataname> with data set (?:#\\d+|"[^"]+"))\\s\\()?/', $test, $matches)) {
            $test = $matches['name'] . ($matches['dataname'] ?? '');
        }
        return $test;
    }
}
