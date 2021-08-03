<?php

declare (strict_types=1);
/*
 * This file is part of phpunit/php-code-coverage.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace ECSPrefix20210803\SebastianBergmann\CodeCoverage\StaticAnalysis;

use ECSPrefix20210803\SebastianBergmann\LinesOfCode\LinesOfCode;
/**
 * @internal This class is not covered by the backward compatibility promise for phpunit/php-code-coverage
 */
final class CachingCoveredFileAnalyser extends \ECSPrefix20210803\SebastianBergmann\CodeCoverage\StaticAnalysis\Cache implements \ECSPrefix20210803\SebastianBergmann\CodeCoverage\StaticAnalysis\CoveredFileAnalyser
{
    /**
     * @var CoveredFileAnalyser
     */
    private $coveredFileAnalyser;
    /**
     * @var array
     */
    private $cache = [];
    public function __construct(string $directory, \ECSPrefix20210803\SebastianBergmann\CodeCoverage\StaticAnalysis\CoveredFileAnalyser $coveredFileAnalyser)
    {
        parent::__construct($directory);
        $this->coveredFileAnalyser = $coveredFileAnalyser;
    }
    public function classesIn(string $filename) : array
    {
        if (!isset($this->cache[$filename])) {
            $this->process($filename);
        }
        return $this->cache[$filename]['classesIn'];
    }
    public function traitsIn(string $filename) : array
    {
        if (!isset($this->cache[$filename])) {
            $this->process($filename);
        }
        return $this->cache[$filename]['traitsIn'];
    }
    public function functionsIn(string $filename) : array
    {
        if (!isset($this->cache[$filename])) {
            $this->process($filename);
        }
        return $this->cache[$filename]['functionsIn'];
    }
    public function linesOfCodeFor(string $filename) : \ECSPrefix20210803\SebastianBergmann\LinesOfCode\LinesOfCode
    {
        if (!isset($this->cache[$filename])) {
            $this->process($filename);
        }
        return $this->cache[$filename]['linesOfCodeFor'];
    }
    public function ignoredLinesFor(string $filename) : array
    {
        if (!isset($this->cache[$filename])) {
            $this->process($filename);
        }
        return $this->cache[$filename]['ignoredLinesFor'];
    }
    public function process(string $filename) : void
    {
        if ($this->has($filename, __CLASS__)) {
            $this->cache[$filename] = $this->read($filename, __CLASS__, [\ECSPrefix20210803\SebastianBergmann\LinesOfCode\LinesOfCode::class]);
            return;
        }
        $this->cache[$filename] = ['classesIn' => $this->coveredFileAnalyser->classesIn($filename), 'traitsIn' => $this->coveredFileAnalyser->traitsIn($filename), 'functionsIn' => $this->coveredFileAnalyser->functionsIn($filename), 'linesOfCodeFor' => $this->coveredFileAnalyser->linesOfCodeFor($filename), 'ignoredLinesFor' => $this->coveredFileAnalyser->ignoredLinesFor($filename)];
        $this->write($filename, __CLASS__, $this->cache[$filename]);
    }
}
