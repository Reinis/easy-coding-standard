<?php

namespace Symplify\EasyCodingStandard\SniffRunner\PHP_CodeSniffer;

use PHP_CodeSniffer\Fixer;
final class FixerFactory
{
    /**
     * @return \PHP_CodeSniffer\Fixer
     */
    public function create()
    {
        $fixer = new \PHP_CodeSniffer\Fixer();
        $fixer->enabled = \true;
        return $fixer;
    }
}