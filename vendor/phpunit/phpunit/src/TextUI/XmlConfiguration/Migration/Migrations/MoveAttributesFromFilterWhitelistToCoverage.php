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
namespace ECSPrefix20210803\PHPUnit\TextUI\XmlConfiguration;

use DOMDocument;
use DOMElement;
/**
 * @internal This class is not covered by the backward compatibility promise for PHPUnit
 */
final class MoveAttributesFromFilterWhitelistToCoverage implements \ECSPrefix20210803\PHPUnit\TextUI\XmlConfiguration\Migration
{
    /**
     * @throws MigrationException
     */
    public function migrate(\DOMDocument $document) : void
    {
        $whitelist = $document->getElementsByTagName('whitelist')->item(0);
        if (!$whitelist) {
            return;
        }
        $coverage = $document->getElementsByTagName('coverage')->item(0);
        if (!$coverage instanceof \DOMElement) {
            throw new \ECSPrefix20210803\PHPUnit\TextUI\XmlConfiguration\MigrationException('Unexpected state - No coverage element');
        }
        $map = ['addUncoveredFilesFromWhitelist' => 'includeUncoveredFiles', 'processUncoveredFilesFromWhitelist' => 'processUncoveredFiles'];
        foreach ($map as $old => $new) {
            if (!$whitelist->hasAttribute($old)) {
                continue;
            }
            $coverage->setAttribute($new, $whitelist->getAttribute($old));
            $whitelist->removeAttribute($old);
        }
    }
}
