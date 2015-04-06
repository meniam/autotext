<?php

namespace TextGenerator;

class XorPart extends Part
{
    /**
     * Массив шаблонов для генерации
     * @var array
     */
    protected $template;

    /**
     * Текущий ключ массива шаблонов
     * @var int
     */
    private $currentTemplateKey = 0;

    public function __construct($template, array $options = array())
    {
        $this->setOptions($options);
        $template = $this->parseTemplate($template);

        $this->template         = explode('|', $template['template']);
        $this->replacementArray = $template['replacement_array'];
    }

    /**
     * Смещает текущий ключ массива
     */
    public function next()
    {
        $this->currentTemplateKey++;
        if (!isset($this->template[$this->currentTemplateKey])) {
            $this->currentTemplateKey = 0;
        }
    }

    /**
     * Returns current template value
     *
     * @return string
     */
    public function getCurrentTemplate()
    {
        if ($hash = $this->getOption(self::OPTION_GENERATE_HASH)) {
            if (!is_int($hash)) {
                $hash = abs(crc32($hash));
            }

            $templateCount = count($this->template);
            $templateKey = ($hash > $templateCount) ? $hash % $templateCount : $templateCount % $hash;
        } elseif ($this->getOption(self::OPTION_GENERATE_RANDOM)) {
            $templateKey = mt_rand(0, count($this->template) - 1);
        } else {
            $templateKey = $this->currentTemplateKey;
        }

        return $this->template[$templateKey];
    }

    /**
     * @return int
     */
    public function getCount()
    {
        return count($this->template) + $this->getReplacementCount() - 1;
    }
}