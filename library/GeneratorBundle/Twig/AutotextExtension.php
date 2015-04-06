<?php

namespace GeneratorBundle\Twig;

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
            'autotext' => new Twig_SimpleFilter('autotext', array(&$this, 'generateText'), array('is_safe' => array('html'))),
        );
    }

    public function generateText($text, $id)
    {
        $textGeneratorOptions = array(Part::OPTION_GENERATE_HASH => $id);
        return TextGenerator::factory($text, $textGeneratorOptions)->generate();
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