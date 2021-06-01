<?php

declare (strict_types=1);
namespace Symplify\CodingStandard\TokenRunner\Analyzer\FixerAnalyzer;

use PhpCsFixer\Doctrine\Annotation\Token;
use PhpCsFixer\Doctrine\Annotation\Tokens;
use PhpCsFixer\Tokenizer\Tokens as PhpTokens;
use Symplify\CodingStandard\Exception\EdgeFindingException;
use Symplify\CodingStandard\TokenRunner\ValueObject\BlockInfo;
use Symplify\CodingStandard\TokenRunner\ValueObject\DocBlockEdgeDefinition;
final class DoctrineBlockFinder
{
    /**
     * @var string[]
     */
    const START_EDGES = ['(', '{'];
    /**
     * @var DocBlockEdgeDefinition[]
     */
    private $docBlockEdgeDefinitions = [];
    /**
     * @var BlockFinder
     */
    private $blockFinder;
    public function __construct(\Symplify\CodingStandard\TokenRunner\Analyzer\FixerAnalyzer\BlockFinder $blockFinder)
    {
        $this->docBlockEdgeDefinitions = [new \Symplify\CodingStandard\TokenRunner\ValueObject\DocBlockEdgeDefinition(\PhpCsFixer\Tokenizer\Tokens::BLOCK_TYPE_CURLY_BRACE, '{', '}'), new \Symplify\CodingStandard\TokenRunner\ValueObject\DocBlockEdgeDefinition(\PhpCsFixer\Tokenizer\Tokens::BLOCK_TYPE_PARENTHESIS_BRACE, '(', ')')];
        $this->blockFinder = $blockFinder;
    }
    /**
     * Accepts position to both start and end token, e.g. (, ), {, }
     *
     * @param Tokens<Token> $tokens
     */
    public function findInTokensByEdge(\PhpCsFixer\Doctrine\Annotation\Tokens $tokens, int $position) : \Symplify\CodingStandard\TokenRunner\ValueObject\BlockInfo
    {
        /** @var Token $token */
        $token = $tokens[$position];
        $blockType = $this->getBlockTypeByToken($token);
        if (\in_array($token->getContent(), self::START_EDGES, \true)) {
            $blockStart = $position;
            $blockEnd = $this->findOppositeBlockEdge($tokens, $blockType, $blockStart);
        } else {
            $blockEnd = $position;
            $blockStart = $this->findOppositeBlockEdge($tokens, $blockType, $blockEnd, \false);
        }
        return new \Symplify\CodingStandard\TokenRunner\ValueObject\BlockInfo($blockStart, $blockEnd);
    }
    private function getBlockTypeByToken(\PhpCsFixer\Doctrine\Annotation\Token $token) : int
    {
        return $this->blockFinder->getBlockTypeByContent($token->getContent());
    }
    /**
     * @param Tokens<Token> $tokens
     *
     * @copied from
     * @see \PhpCsFixer\Tokenizer\Tokens::findBlockEnd()
     */
    private function findOppositeBlockEdge(\PhpCsFixer\Doctrine\Annotation\Tokens $tokens, int $type, int $searchIndex, bool $findEnd = \true) : int
    {
        foreach ($this->docBlockEdgeDefinitions as $docBlockEdgeDefinition) {
            if ($docBlockEdgeDefinition->getKind() !== $type) {
                continue;
            }
            return $this->resolveDocBlockEdgeByType($docBlockEdgeDefinition, $searchIndex, $tokens, $findEnd);
        }
        $message = \sprintf('Invalid param type: "%s".', $type);
        throw new \Symplify\CodingStandard\Exception\EdgeFindingException($message);
    }
    /**
     * @param Tokens<Token> $tokens
     */
    private function resolveIndexForBlockLevel(int $startIndex, int $endIndex, \PhpCsFixer\Doctrine\Annotation\Tokens $tokens, string $startEdge, string $endEdge, int $indexOffset) : int
    {
        $blockLevel = 0;
        for ($index = $startIndex; $index !== $endIndex; $index += $indexOffset) {
            /** @var Token $token */
            $token = $tokens[$index];
            if ($token->getContent() === $startEdge) {
                ++$blockLevel;
                continue;
            }
            if ($token->getContent() === $endEdge) {
                --$blockLevel;
                if ($blockLevel === 0) {
                    break;
                }
                continue;
            }
        }
        return $index;
    }
    /**
     * @param Tokens<Token> $tokens
     * @return void
     */
    private function ensureStartTokenIsNotStartEdge(\PhpCsFixer\Doctrine\Annotation\Tokens $tokens, int $startIndex, string $startEdge, bool $findEnd)
    {
        /** @var Token $startToken */
        $startToken = $tokens[$startIndex];
        if ($startToken->getContent() !== $startEdge) {
            throw new \Symplify\CodingStandard\Exception\EdgeFindingException(\sprintf('Invalid param $startIndex - not a proper block "%s".', $findEnd ? 'start' : 'end'));
        }
    }
    /**
     * @param Tokens<Token> $tokens
     */
    private function resolveDocBlockEdgeByType(\Symplify\CodingStandard\TokenRunner\ValueObject\DocBlockEdgeDefinition $docBlockEdgeDefinition, int $searchIndex, \PhpCsFixer\Doctrine\Annotation\Tokens $tokens, bool $findEnd) : int
    {
        $startChart = $docBlockEdgeDefinition->getStartChar();
        $endChar = $docBlockEdgeDefinition->getEndChar();
        $startIndex = $searchIndex;
        $endIndex = $tokens->count() - 1;
        $indexOffset = 1;
        if (!$findEnd) {
            list($startChart, $endChar) = [$endChar, $startChart];
            $indexOffset = -1;
            $endIndex = 0;
        }
        $this->ensureStartTokenIsNotStartEdge($tokens, $startIndex, $startChart, $findEnd);
        $index = $this->resolveIndexForBlockLevel($startIndex, $endIndex, $tokens, $startChart, $endChar, $indexOffset);
        /** @var Token $currentToken */
        $currentToken = $tokens[$index];
        if ($currentToken->getContent() !== $endChar) {
            $message = \sprintf('Missing block "%s".', $findEnd ? 'end' : 'start');
            throw new \Symplify\CodingStandard\Exception\EdgeFindingException($message);
        }
        return $index;
    }
}
