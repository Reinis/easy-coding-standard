<?php

declare (strict_types=1);
/*
 * This file is part of PHP CS Fixer.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *     Dariusz Rumiński <dariusz.ruminski@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
namespace PhpCsFixer\FixerDefinition;

/**
 * @author Dariusz Rumiński <dariusz.ruminski@gmail.com>
 */
final class FixerDefinition implements \PhpCsFixer\FixerDefinition\FixerDefinitionInterface
{
    /**
     * @var null|string
     */
    private $riskyDescription;
    /**
     * @var CodeSampleInterface[]
     */
    private $codeSamples;
    /**
     * @var string
     */
    private $summary;
    /**
     * @var null|string
     */
    private $description;
    /**
     * @param CodeSampleInterface[] $codeSamples      array of samples, where single sample is [code, configuration]
     * @param null|string           $riskyDescription null for non-risky fixer
     * @param string|null $description
     */
    public function __construct(string $summary, array $codeSamples, $description = null, $riskyDescription = null)
    {
        $this->summary = $summary;
        $this->codeSamples = $codeSamples;
        $this->description = $description;
        $this->riskyDescription = $riskyDescription;
    }
    public function getSummary() : string
    {
        return $this->summary;
    }
    /**
     * @return string|null
     */
    public function getDescription()
    {
        return $this->description;
    }
    /**
     * @return string|null
     */
    public function getRiskyDescription()
    {
        return $this->riskyDescription;
    }
    public function getCodeSamples() : array
    {
        return $this->codeSamples;
    }
}
