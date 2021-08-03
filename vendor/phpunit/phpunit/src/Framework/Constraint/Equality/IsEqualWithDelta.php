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
namespace ECSPrefix20210803\PHPUnit\Framework\Constraint;

use function sprintf;
use function trim;
use ECSPrefix20210803\PHPUnit\Framework\ExpectationFailedException;
use ECSPrefix20210803\SebastianBergmann\Comparator\ComparisonFailure;
use ECSPrefix20210803\SebastianBergmann\Comparator\Factory as ComparatorFactory;
/**
 * @no-named-arguments Parameter names are not covered by the backward compatibility promise for PHPUnit
 */
final class IsEqualWithDelta extends \ECSPrefix20210803\PHPUnit\Framework\Constraint\Constraint
{
    /**
     * @var mixed
     */
    private $value;
    /**
     * @var float
     */
    private $delta;
    public function __construct($value, float $delta)
    {
        $this->value = $value;
        $this->delta = $delta;
    }
    /**
     * Evaluates the constraint for parameter $other.
     *
     * If $returnResult is set to false (the default), an exception is thrown
     * in case of a failure. null is returned otherwise.
     *
     * If $returnResult is true, the result of the evaluation is returned as
     * a boolean value instead: true in case of success, false in case of a
     * failure.
     *
     * @throws ExpectationFailedException
     */
    public function evaluate($other, string $description = '', bool $returnResult = \false) : ?bool
    {
        // If $this->value and $other are identical, they are also equal.
        // This is the most common path and will allow us to skip
        // initialization of all the comparators.
        if ($this->value === $other) {
            return \true;
        }
        $comparatorFactory = \ECSPrefix20210803\SebastianBergmann\Comparator\Factory::getInstance();
        try {
            $comparator = $comparatorFactory->getComparatorFor($this->value, $other);
            $comparator->assertEquals($this->value, $other, $this->delta);
        } catch (\ECSPrefix20210803\SebastianBergmann\Comparator\ComparisonFailure $f) {
            if ($returnResult) {
                return \false;
            }
            throw new \ECSPrefix20210803\PHPUnit\Framework\ExpectationFailedException(\trim($description . "\n" . $f->getMessage()), $f);
        }
        return \true;
    }
    /**
     * Returns a string representation of the constraint.
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function toString() : string
    {
        return \sprintf('is equal to %s with delta <%F>>', $this->exporter()->export($this->value), $this->delta);
    }
}
