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
namespace ECSPrefix20210803\PHPUnit\Framework\MockObject\Rule;

use ECSPrefix20210803\PHPUnit\Framework\ExpectationFailedException;
use ECSPrefix20210803\PHPUnit\Framework\MockObject\Invocation as BaseInvocation;
/**
 * @internal This class is not covered by the backward compatibility promise for PHPUnit
 */
final class InvokedAtMostCount extends \ECSPrefix20210803\PHPUnit\Framework\MockObject\Rule\InvocationOrder
{
    /**
     * @var int
     */
    private $allowedInvocations;
    /**
     * @param int $allowedInvocations
     */
    public function __construct($allowedInvocations)
    {
        $this->allowedInvocations = $allowedInvocations;
    }
    public function toString() : string
    {
        return 'invoked at most ' . $this->allowedInvocations . ' times';
    }
    /**
     * Verifies that the current expectation is valid. If everything is OK the
     * code should just return, if not it must throw an exception.
     *
     * @throws ExpectationFailedException
     */
    public function verify() : void
    {
        $count = $this->getInvocationCount();
        if ($count > $this->allowedInvocations) {
            throw new \ECSPrefix20210803\PHPUnit\Framework\ExpectationFailedException('Expected invocation at most ' . $this->allowedInvocations . ' times but it occurred ' . $count . ' time(s).');
        }
    }
    public function matches(\ECSPrefix20210803\PHPUnit\Framework\MockObject\Invocation $invocation) : bool
    {
        return \true;
    }
    protected function invokedDo(\ECSPrefix20210803\PHPUnit\Framework\MockObject\Invocation $invocation) : void
    {
    }
}
