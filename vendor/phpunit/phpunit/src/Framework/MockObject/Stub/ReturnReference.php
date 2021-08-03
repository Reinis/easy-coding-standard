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
namespace ECSPrefix20210803\PHPUnit\Framework\MockObject\Stub;

use function sprintf;
use ECSPrefix20210803\PHPUnit\Framework\MockObject\Invocation;
use ECSPrefix20210803\SebastianBergmann\Exporter\Exporter;
/**
 * @internal This class is not covered by the backward compatibility promise for PHPUnit
 */
final class ReturnReference implements \ECSPrefix20210803\PHPUnit\Framework\MockObject\Stub\Stub
{
    /**
     * @var mixed
     */
    private $reference;
    public function __construct(&$reference)
    {
        $this->reference =& $reference;
    }
    public function invoke(\ECSPrefix20210803\PHPUnit\Framework\MockObject\Invocation $invocation)
    {
        return $this->reference;
    }
    public function toString() : string
    {
        $exporter = new \ECSPrefix20210803\SebastianBergmann\Exporter\Exporter();
        return \sprintf('return user-specified reference %s', $exporter->export($this->reference));
    }
}
