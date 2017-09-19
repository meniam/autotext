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

        $id = null;
        if ($stream->test('id')) {
            $stream->expect(Twig_Token::NAME_TYPE);
            $stream->expect(Twig_Token::OPERATOR_TYPE, '=');
            $id = $parser->getExpressionParser()->parseExpression();
        }

        $vars = new \Twig_Node_Expression_Array(array(), $lineno);
        if ($stream->test('vars')) {
            $stream->expect(Twig_Token::NAME_TYPE);
            $stream->expect(Twig_Token::OPERATOR_TYPE, '=');
            $vars = $parser->getExpressionParser()->parseExpression();
        }

        $stream->expect(Twig_Token::BLOCK_END_TYPE);

        $body = $parser->subparse(array($this, 'decideMarkdownEnd'), true);
        $stream->expect(Twig_Token::BLOCK_END_TYPE);

        return new AutotextNode($body, $id, $vars, $lineno, $this->getTag());
    }

    public function decideMarkdownEnd(Twig_Token $token)
    {
        return ($token->test('autotextend') || $token->test('endautotext'));
    }

    public function getTag()
    {
        return 'autotext';
    }
}