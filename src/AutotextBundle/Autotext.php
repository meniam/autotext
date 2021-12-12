<?php

declare(strict_types=1);

namespace AutotextBundle;

use Psr\Cache\CacheItemPoolInterface;
use TextGenerator\TextGenerator;

class Autotext
{
    protected CacheItemPoolInterface $cache;

    public function __construct(CacheItemPoolInterface $appCache)
    {
        $this->cache = $appCache;
    }

    public function autotext($text, $seed = null, array $vars = []): string
    {
        $cacheItem = $this->cache->getItem('autotext_' . md5($text));
        if ($cacheItem->get()) {
            $textGenerator = $cacheItem->get();
        } else {
            $textGenerator = TextGenerator::factory($text, []);
            $this->cache->save($cacheItem->set($textGenerator));
        }
        $text = $seed ? $textGenerator->generateRandom($seed) : $textGenerator->generate();
        return self::replaceVars($text, $vars);
    }

    private static function replaceVars($text, array $vars = []): string
    {
        if (empty($text) || empty($vars) || (strpos($text, '%') === false)) {
            return trim($text);
        }
        $replaces = [];
        foreach ($vars as $k => $v) {
            $replaces['%'.trim($k, ' %').'%'] = $v;
        }
        $text = strtr($text, $replaces);
        $text = preg_replace('#%\s*\w+\s*%#si', '', $text);
        return trim($text);
    }
}