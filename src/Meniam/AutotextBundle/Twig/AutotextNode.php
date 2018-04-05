<?php

namespace Meniam\AutotextBundle\Twig;

use Twig_Node_Expression;
use Twig_Node_Expression_Array;
use Twig_Node;

class AutotextNode extends Twig_Node
{
    public function __construct(Twig_Node $body, Twig_Node_Expression $id = null, Twig_Node_Expression_Array $vars = null, $line = 0, $tag = null)
    {
        $nodes = array('body' => $body);

        if (null !== $id) {
            $nodes['id'] = $id;
        }

        if (null !== $vars) {
            $nodes['vars'] = $vars;
        }

        parent::__construct($nodes, [], $line, $tag);
    }

    public function compile(\Twig_Compiler $compiler)
    {
        $compiler
            ->addDebugInfo($this)
            ->write('ob_start();'.PHP_EOL)
            ->subcompile($this->getNode('body'))
            ->write('$body = ob_get_clean();'.PHP_EOL);

        if ($this->hasNode('id') && $this->getNode('id')) {
            $compiler->raw('$id = ')->subcompile($this->getNode('id'))->raw(';'.PHP_EOL);
        } else {
            $compiler->raw('$id = null;'.PHP_EOL);
        }

        if ($this->hasNode('vars') && ($vars = $this->getNode('vars')) instanceof \Twig_Node_Expression_Array) {
            $compiler
                ->raw('$vars = ')
                ->subcompile($this->getNode('vars'))
                ->raw(';'.PHP_EOL);
        } else {
            $compiler->raw('$vars = [];'.PHP_EOL);
        }

        $compiler->write('echo $this->env->getExtension(\'Meniam\\AutotextBundle\\Twig\\AutotextExtension\')->autotext($body, $id, $vars);'.PHP_EOL);
    }
}