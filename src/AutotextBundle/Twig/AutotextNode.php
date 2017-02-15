<?php

namespace AutotextBundle\Twig;

use Twig_Node_Expression;
use Twig_Node;

class AutotextNode extends Twig_Node
{
    public function __construct(Twig_Node $body, Twig_Node_Expression $id, $line, $tag = null)
    {
        parent::__construct(array('id' => $id, 'body' => $body), array(), $line, $tag);
    }

    public function compile(\Twig_Compiler $compiler)
    {
        $compiler
            ->addDebugInfo($this)
            ->write('ob_start();'.PHP_EOL)
            ->subcompile($this->getNode('body'))
            ->write('$body = ob_get_clean();'.PHP_EOL)
            ->write('$id = ')
            ->subcompile($this->getNode('id'))
            ->write(';'.PHP_EOL)
            ->write('echo $this->env->getExtension(\'autotext\')->generateText($body, $id);'.PHP_EOL);
    }
}