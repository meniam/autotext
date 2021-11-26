<?php

namespace AutotextBundle\Twig;

use AutotextBundle\Autotext;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class AutotextExtension extends AbstractExtension
{
    private Autotext $autotext;

    public function __construct(Autotext $autotext)
    {
        $this->autotext = $autotext;
    }

    public function getFilters(): array
    {
        return [
            'autotext' => new TwigFilter('autotext', [ &$this, 'autotext' ], [ 'is_safe' => [ 'html' ] ]),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('autotext', [ $this, 'autotext' ], [ 'is_safe' => [ 'all' ] ]),
        ];
    }

    public function autotext($text, $id = null, array $vars = []): string
    {
        return $this->autotext->autotext($text, $id, $vars);
    }

    public function getTokenParsers(): array
    {
        return [new AutotextTokenParser()];
    }
}