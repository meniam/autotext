<?php

namespace TextGenerator;

class TextGenerator
{
    /** @var  array */
    protected static $replaceList;

    /**
     * Factory method
     *
     * @param       $template
     * @param array $options
     *
     * @return OrPart|Part|XorPart
     */
    public static function factory($template, array $options = array())
    {
        $template = (string)$template;

        if ($replaceList = self::getReplaceList()) {
            $template = str_replace(array_keys($replaceList), array_values($replaceList), $template);
        }

        if (preg_match_all('#(?:\[|\{)((?:(?:[^\[\{\]\}]+)|(?R))*)(?:\]|\})#', $template, $m) > 1) {
            return new Part($template, $options);
        }

        if (mb_strpos($template, '{', null, 'UTF-8') === 0) {
            $template = mb_substr($template, 1, -1, 'UTF-8');
            return new XorPart($template, $options);
        }

        if (mb_strpos($template, '[', null, 'UTF-8') === 0) {
            $template = mb_substr($template, 1, -1, 'UTF-8');
            return new OrPart($template, $options);
        }

        return new Part($template, $options);
    }

    /**
     * @return array
     */
    public static function getReplaceList()
    {
        return (array)self::$replaceList;
    }

    /**
     * Add list of replaces
     *
     * @param $array
     */
    public static function addReplaceList($array)
    {
        if (is_array($array)) {
            foreach ($array as $k => &$v) {
                self::addReplace($k, $v);
            }
        }
    }

    /**
     * Add replace
     *
     * @param $name
     * @param $value
     */
    public static function addReplace($name, $value)
    {
        self::$replaceList['%' . trim($name, '%') . '%'] = (string)$value;
    }
}