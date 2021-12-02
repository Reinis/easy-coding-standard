<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace ECSPrefix20211202\Symfony\Component\Console\Completion\Output;

use ECSPrefix20211202\Symfony\Component\Console\Completion\CompletionSuggestions;
use ECSPrefix20211202\Symfony\Component\Console\Output\OutputInterface;
/**
 * @author Wouter de Jong <wouter@wouterj.nl>
 */
class BashCompletionOutput implements \ECSPrefix20211202\Symfony\Component\Console\Completion\Output\CompletionOutputInterface
{
    /**
     * @param \Symfony\Component\Console\Completion\CompletionSuggestions $suggestions
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     */
    public function write($suggestions, $output) : void
    {
        $values = $suggestions->getValueSuggestions();
        foreach ($suggestions->getOptionSuggestions() as $option) {
            $values[] = '--' . $option->getName();
        }
        $output->writeln(\implode("\n", $values));
    }
}