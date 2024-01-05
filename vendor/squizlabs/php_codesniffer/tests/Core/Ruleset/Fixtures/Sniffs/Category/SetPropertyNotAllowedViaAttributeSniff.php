<?php

/**
 * Test fixture.
 *
 * @see \PHP_CodeSniffer\Tests\Core\Ruleset\SetSniffPropertyTest
 */
namespace ECSPrefix202401\Fixtures\Sniffs\Category;

use ECSPrefix202401\AllowDynamicProperties;
use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
#[\AllowDynamicProperties]
class SetPropertyNotAllowedViaAttributeSniff implements Sniff
{
    public function register()
    {
        return [\T_WHITESPACE];
    }
    public function process(File $phpcsFile, $stackPtr)
    {
        // Do something.
    }
}
