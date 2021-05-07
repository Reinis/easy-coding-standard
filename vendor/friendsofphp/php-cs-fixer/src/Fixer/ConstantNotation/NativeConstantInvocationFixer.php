<?php

/*
 * This file is part of PHP CS Fixer.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *     Dariusz Rumiński <dariusz.ruminski@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
namespace PhpCsFixer\Fixer\ConstantNotation;

use PhpCsFixer\AbstractFixer;
use PhpCsFixer\Fixer\ConfigurableFixerInterface;
use PhpCsFixer\FixerConfiguration\FixerConfigurationResolver;
use PhpCsFixer\FixerConfiguration\FixerConfigurationResolverInterface;
use PhpCsFixer\FixerConfiguration\FixerOptionBuilder;
use PhpCsFixer\FixerDefinition\CodeSample;
use PhpCsFixer\FixerDefinition\FixerDefinition;
use PhpCsFixer\FixerDefinition\FixerDefinitionInterface;
use PhpCsFixer\Tokenizer\Analyzer\Analysis\NamespaceAnalysis;
use PhpCsFixer\Tokenizer\Analyzer\NamespacesAnalyzer;
use PhpCsFixer\Tokenizer\Analyzer\NamespaceUsesAnalyzer;
use PhpCsFixer\Tokenizer\Token;
use PhpCsFixer\Tokenizer\Tokens;
use PhpCsFixer\Tokenizer\TokensAnalyzer;
use ECSPrefix20210507\Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
/**
 * @author Filippo Tessarotto <zoeslam@gmail.com>
 */
final class NativeConstantInvocationFixer extends \PhpCsFixer\AbstractFixer implements \PhpCsFixer\Fixer\ConfigurableFixerInterface
{
    /**
     * @var array<string, true>
     */
    private $constantsToEscape = [];
    /**
     * @var array<string, true>
     */
    private $caseInsensitiveConstantsToEscape = [];
    /**
     * {@inheritdoc}
     * @return \PhpCsFixer\FixerDefinition\FixerDefinitionInterface
     */
    public function getDefinition()
    {
        return new \PhpCsFixer\FixerDefinition\FixerDefinition('Add leading `\\` before constant invocation of internal constant to speed up resolving. Constant name match is case-sensitive, except for `null`, `false` and `true`.', [new \PhpCsFixer\FixerDefinition\CodeSample("<?php var_dump(PHP_VERSION, M_PI, MY_CUSTOM_PI);\n"), new \PhpCsFixer\FixerDefinition\CodeSample('<?php
namespace space1 {
    echo PHP_VERSION;
}
namespace {
    echo M_PI;
}
', ['scope' => 'namespaced']), new \PhpCsFixer\FixerDefinition\CodeSample("<?php var_dump(PHP_VERSION, M_PI, MY_CUSTOM_PI);\n", ['include' => ['MY_CUSTOM_PI']]), new \PhpCsFixer\FixerDefinition\CodeSample("<?php var_dump(PHP_VERSION, M_PI, MY_CUSTOM_PI);\n", ['fix_built_in' => \false, 'include' => ['MY_CUSTOM_PI']]), new \PhpCsFixer\FixerDefinition\CodeSample("<?php var_dump(PHP_VERSION, M_PI, MY_CUSTOM_PI);\n", ['exclude' => ['M_PI']])], null, 'Risky when any of the constants are namespaced or overridden.');
    }
    /**
     * {@inheritdoc}
     *
     * Must run before GlobalNamespaceImportFixer.
     * @return int
     */
    public function getPriority()
    {
        return 10;
    }
    /**
     * {@inheritdoc}
     * @param \PhpCsFixer\Tokenizer\Tokens $tokens
     * @return bool
     */
    public function isCandidate($tokens)
    {
        return $tokens->isTokenKindFound(\T_STRING);
    }
    /**
     * {@inheritdoc}
     * @return bool
     */
    public function isRisky()
    {
        return \true;
    }
    /**
     * {@inheritdoc}
     * @return void
     */
    public function configure(array $configuration)
    {
        parent::configure($configuration);
        $uniqueConfiguredExclude = \array_unique($this->configuration['exclude']);
        // Case sensitive constants handling
        $constantsToEscape = \array_values($this->configuration['include']);
        if (\true === $this->configuration['fix_built_in']) {
            $getDefinedConstants = \get_defined_constants(\true);
            unset($getDefinedConstants['user']);
            foreach ($getDefinedConstants as $constants) {
                $constantsToEscape = \array_merge($constantsToEscape, \array_keys($constants));
            }
        }
        $constantsToEscape = \array_diff(\array_unique($constantsToEscape), $uniqueConfiguredExclude);
        // Case insensitive constants handling
        static $caseInsensitiveConstants = ['null', 'false', 'true'];
        $caseInsensitiveConstantsToEscape = [];
        foreach ($constantsToEscape as $constantIndex => $constant) {
            $loweredConstant = \strtolower($constant);
            if (\in_array($loweredConstant, $caseInsensitiveConstants, \true)) {
                $caseInsensitiveConstantsToEscape[] = $loweredConstant;
                unset($constantsToEscape[$constantIndex]);
            }
        }
        $caseInsensitiveConstantsToEscape = \array_diff(\array_unique($caseInsensitiveConstantsToEscape), \array_map(static function (string $function) {
            return \strtolower($function);
        }, $uniqueConfiguredExclude));
        // Store the cache
        $this->constantsToEscape = \array_fill_keys($constantsToEscape, \true);
        \ksort($this->constantsToEscape);
        $this->caseInsensitiveConstantsToEscape = \array_fill_keys($caseInsensitiveConstantsToEscape, \true);
        \ksort($this->caseInsensitiveConstantsToEscape);
    }
    /**
     * {@inheritdoc}
     * @return void
     * @param \SplFileInfo $file
     * @param \PhpCsFixer\Tokenizer\Tokens $tokens
     */
    protected function applyFix($file, $tokens)
    {
        if ('all' === $this->configuration['scope']) {
            $this->fixConstantInvocations($tokens, 0, \count($tokens) - 1);
            return;
        }
        $namespaces = (new \PhpCsFixer\Tokenizer\Analyzer\NamespacesAnalyzer())->getDeclarations($tokens);
        // 'scope' is 'namespaced' here
        /** @var NamespaceAnalysis $namespace */
        foreach (\array_reverse($namespaces) as $namespace) {
            if ('' === $namespace->getFullName()) {
                continue;
            }
            $this->fixConstantInvocations($tokens, $namespace->getScopeStartIndex(), $namespace->getScopeEndIndex());
        }
    }
    /**
     * {@inheritdoc}
     * @return \PhpCsFixer\FixerConfiguration\FixerConfigurationResolverInterface
     */
    protected function createConfigurationDefinition()
    {
        $constantChecker = static function (array $value) {
            foreach ($value as $constantName) {
                if (!\is_string($constantName) || '' === \trim($constantName) || \trim($constantName) !== $constantName) {
                    throw new \ECSPrefix20210507\Symfony\Component\OptionsResolver\Exception\InvalidOptionsException(\sprintf('Each element must be a non-empty, trimmed string, got "%s" instead.', \is_object($constantName) ? \get_class($constantName) : \gettype($constantName)));
                }
            }
            return \true;
        };
        return new \PhpCsFixer\FixerConfiguration\FixerConfigurationResolver([(new \PhpCsFixer\FixerConfiguration\FixerOptionBuilder('fix_built_in', 'Whether to fix constants returned by `get_defined_constants`. User constants are not accounted in this list and must be specified in the include one.'))->setAllowedTypes(['bool'])->setDefault(\true)->getOption(), (new \PhpCsFixer\FixerConfiguration\FixerOptionBuilder('include', 'List of additional constants to fix.'))->setAllowedTypes(['array'])->setAllowedValues([$constantChecker])->setDefault([])->getOption(), (new \PhpCsFixer\FixerConfiguration\FixerOptionBuilder('exclude', 'List of constants to ignore.'))->setAllowedTypes(['array'])->setAllowedValues([$constantChecker])->setDefault(['null', 'false', 'true'])->getOption(), (new \PhpCsFixer\FixerConfiguration\FixerOptionBuilder('scope', 'Only fix constant invocations that are made within a namespace or fix all.'))->setAllowedValues(['all', 'namespaced'])->setDefault('all')->getOption(), (new \PhpCsFixer\FixerConfiguration\FixerOptionBuilder('strict', 'Whether leading `\\` of constant invocation not meant to have it should be removed.'))->setAllowedTypes(['bool'])->setDefault(\true)->getOption()]);
    }
    /**
     * @return void
     * @param \PhpCsFixer\Tokenizer\Tokens $tokens
     * @param int $startIndex
     * @param int $endIndex
     */
    private function fixConstantInvocations($tokens, $startIndex, $endIndex)
    {
        $useDeclarations = (new \PhpCsFixer\Tokenizer\Analyzer\NamespaceUsesAnalyzer())->getDeclarationsFromTokens($tokens);
        $useConstantDeclarations = [];
        foreach ($useDeclarations as $use) {
            if ($use->isConstant()) {
                $useConstantDeclarations[$use->getShortName()] = \true;
            }
        }
        $tokenAnalyzer = new \PhpCsFixer\Tokenizer\TokensAnalyzer($tokens);
        for ($index = $endIndex; $index > $startIndex; --$index) {
            $token = $tokens[$index];
            // test if we are at a constant call
            if (!$token->isGivenKind(\T_STRING)) {
                continue;
            }
            if (!$tokenAnalyzer->isConstantInvocation($index)) {
                continue;
            }
            $tokenContent = $token->getContent();
            $prevIndex = $tokens->getPrevMeaningfulToken($index);
            if (!isset($this->constantsToEscape[$tokenContent]) && !isset($this->caseInsensitiveConstantsToEscape[\strtolower($tokenContent)])) {
                if (!$this->configuration['strict']) {
                    continue;
                }
                if (!$tokens[$prevIndex]->isGivenKind(\T_NS_SEPARATOR)) {
                    continue;
                }
                $prevPrevIndex = $tokens->getPrevMeaningfulToken($prevIndex);
                if ($tokens[$prevPrevIndex]->isGivenKind(\T_STRING)) {
                    continue;
                }
                $tokens->clearTokenAndMergeSurroundingWhitespace($prevIndex);
                continue;
            }
            if (isset($useConstantDeclarations[$tokenContent])) {
                continue;
            }
            if ($tokens[$prevIndex]->isGivenKind(\T_NS_SEPARATOR)) {
                continue;
            }
            $tokens->insertAt($index, new \PhpCsFixer\Tokenizer\Token([\T_NS_SEPARATOR, '\\']));
        }
    }
}