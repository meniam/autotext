<?php

namespace AutotextBundle\Twig;

use Twig\Node\Expression\ArrayExpression;
use Twig\Token;
use Twig\TokenParser\AbstractTokenParser;

class AutotextTokenParser extends AbstractTokenParser
{
    public function parse(Token $token): AutotextNode
    {
        $lineno = $token->getLine();
        $parser = $this->parser;
        $stream = $this->parser->getStream();

        $id = null;
        if ($stream->test('id')) {
            $stream->expect(Token::NAME_TYPE);
            $stream->expect(Token::OPERATOR_TYPE, '=');
            $id = $parser->getExpressionParser()->parseExpression();
        }

        $vars = new ArrayExpression([ ], $lineno);
        if ($stream->test('vars')) {
            $stream->expect(Token::NAME_TYPE);
            $stream->expect(Token::OPERATOR_TYPE, '=');
            $vars = $parser->getExpressionParser()->parseExpression();
        }

        $stream->expect(Token::BLOCK_END_TYPE);

        $body = $parser->subparse([ $this, 'decideMarkdownEnd' ], true);
        $stream->expect(Token::BLOCK_END_TYPE);

        return new AutotextNode($body, $id, $vars, $lineno, $this->getTag());
    }

    public function decideMarkdownEnd(Token $token): bool
    {
        return ($token->test('autotextend') || $token->test('endautotext'));
    }

    public function getTag(): string
    {
        return 'autotext';
    }
}