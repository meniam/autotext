<?php

namespace AutotextBundle;

use Psr\Cache\CacheItemPoolInterface;
use TextGenerator\Part;
use TextGenerator\TextGenerator;

class Autotext
{
    protected CacheItemPoolInterface $cache;

    public function __construct(CacheItemPoolInterface $appCache)
    {
        $this->cache = $appCache;
    }

    public function autotext($text, $seed = null, $vars = []): string
    {
        $cacheItem = $this->cache->getItem('autotext_' . md5($text) . '_' . md5($seed ?: ''));
        if ($cacheItem->get()) {
            $text = $cacheItem->get();
        } else {
            $textGeneratorOptions = [ Part::OPTION_GENERATE_RANDOM => $seed ];
            $textGenerator = TextGenerator::factory($text, $textGeneratorOptions);
            $text = $seed ? $textGenerator->generateRandom($seed) : $textGenerator->generate();
            $this->cache->save($cacheItem->set($text));
        }
        return self::replaceVars($text, $vars);
    }

    private static function replaceVars($text, $vars = null): string
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