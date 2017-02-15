<?php

namespace AutotextBundle\Twig;

use Twig_Token;

class AutotextTokenParser extends \Twig_TokenParser
{
    public function parse(Twig_Token $token)
    {
        $lineno = $token->getLine();
        $parser = $this->parser;
        $stream = $this->parser->getStream();

        $stream->expect(Twig_Token::NAME_TYPE)->getValue();
        $stream->expect(Twig_Token::OPERATOR_TYPE, '=');
        $id = $parser->getExpressionParser()->parseExpression();
        $stream->expect(Twig_Token::BLOCK_END_TYPE);
        $body = $parser->subparse(array($this, 'decideMarkdownEnd'), true);
        $stream->expect(Twig_Token::BLOCK_END_TYPE);

        return new AutotextNode($body, $id, $lineno, $this->getTag());
    }

    public function decideMarkdownEnd(Twig_Token $token)
    {
        return $token->test('autotextend');
    }

    public function getTag()
    {
        return 'autotext';
    }
}