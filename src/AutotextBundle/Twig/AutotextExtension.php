<?php

namespace AutotextBundle\Twig;

use AutotextBundle\Autotext;
use TextGenerator\Part;
use TextGenerator\TextGenerator;
use Twig_Extension;
use Twig_SimpleFilter;

class AutotextExtension extends Twig_Extension
{
    /**
     * {@inheritdoc}
     */
    public function getFilters()
    {
        return array(
            'autotext' => new Twig_SimpleFilter('autotext', array(&$this, 'autotext'), array('is_safe' => array('html'))),
        );
    }

    /**
     * @param       $text
     * @param null  $id
     * @param array $vars
     * @return string
     */
    public function autotext($text, $id = null, $vars = [])
    {
        return Autotext::autotext($text, $id, $vars);
    }

    /**
     * {@inheritdoc}
     */
    public function getTokenParsers()
    {
        return array(new AutotextTokenParser());
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'autotext';
    }
}