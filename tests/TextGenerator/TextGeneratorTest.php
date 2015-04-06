<?php

namespace TextGeneratorTest;

require_once __DIR__ . '/TestCase.php';

use TextGenerator\Part;
use TextGenerator\TextGenerator;

class TextGeneratorTest extends TestCase
{
    public function testFactory()
    {
        $str = " {hi} ";
        $this->assertInstanceOf('TextGenerator\\Part', TextGenerator::factory($str));

        $str = " [hi] ";
        $this->assertInstanceOf('TextGenerator\\Part', TextGenerator::factory($str));

        $str = " hi ";
        $this->assertInstanceOf('TextGenerator\\Part', TextGenerator::factory($str));
    }

    public function testRandomGenerate()
    {
        $str = "Hi {men|girl|kid|guy|dude} you are so [+ and +biutifull|amazin|good|{awesome|nerdy :)}|practice]";

        $this->assertNotEquals(TextGenerator::factory($str, [Part::OPTION_GENERATE_RANDOM => true])->generate(), TextGenerator::factory($str, [Part::OPTION_GENERATE_RANDOM => true])->generate(true));
    }

    public function testHashedGenerator()
    {
        $str = "Hi {men|girl|kid|guy|dude} you are so [+ and +biutifull|amazin|good|{awesome|nerdy :)}|practice]";

        $this->assertNotEquals(TextGenerator::factory($str, [Part::OPTION_GENERATE_HASH => 2])->generate(),
                               TextGenerator::factory($str, [Part::OPTION_GENERATE_HASH => "2"])->generate(true));
    }

    public function testSomeCase()
    {
        $str = "{[+, +США|Англии|Китая]}";
        $this->assertEquals('Китая, Англии, США', TextGenerator::factory($str, [Part::OPTION_GENERATE_HASH => 11])->generate());
    }

    public function testReplace()
    {
        $str = "Hi {%gender%} you are so [+ and +%type%]";

        TextGenerator::addReplaceList(['%gender' => 'female', 'type%' => 'cute']);
        $this->assertEquals("Hi female you are so cute", TextGenerator::factory($str)->generate());
    }
}