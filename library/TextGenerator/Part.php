<?php

namespace TextGenerator;

class Part
{
    const OPTION_STRIP_WHITE_SPACE   = 'strip_white_space';
    const OPTION_FILTER_EMPTY_VALUES = 'filter_empty_values';
    const OPTION_REMOVE_DUPLICATES   = 'remove_duplicates';

    const OPTION_GENERATE_RANDOM = 'generate_random';
    const OPTION_GENERATE_HASH = 'generate_hash';

    /**
     * Шаблон для генерации
     * @see TextGenerator_Part::parseTemplate()
     * @var string
     */
    protected $template;

    /**
     * Массив замен из управляющих конструкций (перестановок и переборов)
     * @var array|Part[]
     */
    protected $replacementArray;

    private $options = [
        self::OPTION_STRIP_WHITE_SPACE => true,
        self::OPTION_FILTER_EMPTY_VALUES => true,
        self::OPTION_REMOVE_DUPLICATES => true,
        self::OPTION_GENERATE_HASH => null,
        self::OPTION_GENERATE_RANDOM => false
    ];

    /**
     * @param string $template - шаблон, по которому будет генерироваться текст
     * @param array  $options
     */
    public function __construct($template, array $options = array())
    {
        $this->setOptions($options);
        $template               = $this->parseTemplate($template);
        $this->template         = $template['template'];
        $this->replacementArray = $template['replacement_array'];
    }

    /**
     * Парсит шаблон, заменяет все управляющие конструкции (переборы, перестановки и т.д) и получает массив типа:
     * array(
     *   'template' => 'Генератор может генерировать %%0%%',
     *   'replacement_array' => array(
     *       '%%0%%' => TextGenerator_OrPart
     *    )
     * )
     *
     * @param string $template - шаблон
     *
     * @return array
     */
    protected function parseTemplate($template)
    {
        $replacementArray = array();

        $template = preg_replace_callback('#(?:\[|\{)((?:(?:[^\[\{\]\}]+)|(?R))*)(?:\]|\})#', function ($match) use (&$replacementArray) {
            $key                    = '%0000' . count($replacementArray) . '%';
            $replacementArray[$key] = TextGenerator::factory($match[0], $this->getOptions());
            return $key;
        }, $template);

        return array(
            'template'          => $template,
            'replacement_array' => $replacementArray
        );
    }

    /**
     * Сгенерировать текст по текущему шаблону
     * @return string
     */
    public function generate()
    {
        $template         = $this->getCurrentTemplate();
        $replacementArray = $this->getReplacementArray();

        $replacementArrayTmp = array();
        $searchArray         = array();
        foreach ($replacementArray as $key => $value) {
            $searchArray[]         = $key;
            $replacementArrayTmp[] = $value->generate();
        }
        $replacementArray = $replacementArrayTmp;

        $this->next();

        if ($searchArray) {
            return str_replace($searchArray, $replacementArray, $template);
        }
        return $template;
    }

    /**
     * @return int|void
     */
    public function getReplacementCount()
    {
        $repeats = 1;
        if (!empty($this->replacementArray)) {
            foreach ($this->replacementArray as &$v) {
                $repeats *= $v->getCount();
            }
            return $repeats;
        } else {
            return 1;
        }
    }

    /**
     * @return int
     */
    public function getCount()
    {
        return intval(count($this->template) * $this->getReplacementCount());
    }

    /**
     *
     */
    protected function next()
    {
    }

    /**
     * Получить текущий шаблон, по которому будет сгенерен текст
     * @return string
     */
    protected function getCurrentTemplate()
    {
        return $this->template;
    }

    /**
     * Получить массив замен для шаблона
     * @return array|Part[]
     */
    protected function getReplacementArray()
    {
        return $this->replacementArray;
    }

    /**
     * Set options
     *
     * @param array $options
     * @return $this
     */
    public function setOptions(array $options)
    {
        foreach ($options as $k => $v) {
            $this->setOption($k, $v);
        }
        return $this;
    }

    /**
     * Set option value
     *
     * @param string $name
     * @param mixed $value
     * @return $this
     */
    public function setOption($name, $value)
    {
        $this->options[(string)$name] = $value;
        return $this;
    }

    /**
     * Get option value be key
     *
     * @param string $key
     * @param mixed $default Default value if key don't exists
     * @return array|null
     */
    public function getOption($key = null, $default = null)
    {
        if (is_null($key)) {
            return $this->options;
        } elseif (array_key_exists($key, $this->options)) {
            return $this->options[$key];
        }

        return $default;
    }

    /**
     * Get all options
     *
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Factorial
     *
     * @param $x
     * @return int
     */
    protected function factorial($x)
    {
        if ($x === 0) {
            return 1;
        } else {
            return $x*$this->factorial($x-1);
        }
    }
}