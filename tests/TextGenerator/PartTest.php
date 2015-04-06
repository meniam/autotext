<?php

namespace TextGeneratorTest;

require_once __DIR__ . '/TestCase.php';

use TextGenerator\Part;
use TextGenerator\TextGenerator;

class PartTest extends TestCase
{
    public function testSetOptions()
    {
        $options = array(
            Part::OPTION_FILTER_EMPTY_VALUES => false,
            Part::OPTION_REMOVE_DUPLICATES   => false,
            Part::OPTION_STRIP_WHITE_SPACE   => false,
            Part::OPTION_GENERATE_HASH       => null,
            Part::OPTION_GENERATE_RANDOM     => false
        );

        $str = " hi ";
        $part = TextGenerator::factory($str, $options);
        $this->assertInstanceOf('TextGenerator\\Part', $part);

        $this->assertEquals($options, $part->getOptions());

        $options = array(
            Part::OPTION_FILTER_EMPTY_VALUES => true,
            Part::OPTION_REMOVE_DUPLICATES   => true,
            Part::OPTION_STRIP_WHITE_SPACE   => true,
            Part::OPTION_GENERATE_HASH       => null,
            Part::OPTION_GENERATE_RANDOM     => false
        );
        foreach ($options as $name => $value) {
            $part->setOption($name, $value);
            $this->assertEquals($value, $part->getOption($name));
        }

        $this->assertEquals($options, $part->getOption());

        $this->assertEquals('unknown', $part->getOption('unknown', 'unknown'));
    }

    public function testGetCount()
    {
        $part = TextGenerator::factory("[a|b|c]");
        $this->assertEquals(6, $part->getCount());

        $part = TextGenerator::factory("[a|b|c|d]");
        $this->assertEquals(24, $part->getCount());

        $part = TextGenerator::factory("[a|b|c|d|e]");
        $this->assertEquals(120, $part->getCount());

    }
}