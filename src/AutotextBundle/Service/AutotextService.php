<?php

namespace AutotextBundle\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;
use TextGenerator\Part;
use TextGenerator\TextGenerator;

class AutotextService
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param       $text
     * @param null  $id
     * @param array $vars
     *
     * @return string
     */
    public function autotext($text, $id = null, $vars = [])
    {
        $textGeneratorOptions = array(Part::OPTION_GENERATE_HASH => $id);
        return $this->replaceVars(TextGenerator::factory($text, $textGeneratorOptions)->generate(), $vars);
    }

    /**
     * @param $text
     * @param $vars
     *
     * @return mixed|string
     */
    public function replaceVars($text, $vars = null)
    {
        if (empty($text) || empty($vars) || (strpos($text, '%') === false)) {
            return trim($text);
        }

        $replaces = [];
        foreach ($vars as $k => &$v) {
            $replaces['%'.trim($k, ' %').'%'] = $v;
        }

        $text = strtr($text, $replaces);
        $text = preg_replace('#%\s*\w+\s*%#si', '', $text);

        return trim($text);
    }
}