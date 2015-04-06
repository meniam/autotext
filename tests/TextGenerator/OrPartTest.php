<?php

namespace TextGeneratorTest;

require_once __DIR__ . '/TestCase.php';

use TextGenerator\OrPart;
use TextGenerator\TextGenerator;

class OrPartTest extends TestCase
{
    public function testGetRandomTemplate()
    {
        $str = "1|2|3|4|5|6";
        $part = new OrPart($str);
        $this->assertNotEquals($part->generate(true), $part->generate(true));

        $part = new OrPart($str);
        $this->assertEquals('1 2 3 4 5 6', $part->generate());
        $this->assertEquals('1 2 3 4 6 5', $part->generate());
    }

    public function testGetCount()
    {
        $str = "1|2";
        $part = new OrPart($str);
        $this->assertEquals(2, $part->getCount());

        $str = "1|2|3";
        $part = new OrPart($str);
        $this->assertEquals(6, $part->getCount());

        $str = "1|2|3|4";
        $part = new OrPart($str);
        $this->assertEquals(24, $part->getCount());

        $str = "+ and +1|2|3|4|5";
        $part = new OrPart($str);
        $this->assertEquals(120, $part->getCount());
        $part->next();
        $result = array();
        $i = 0;
        while ($item = $part->getCurrentTemplate()) {
            $result[$item] = true;
            $part->next();
            if ($i++ >= 240) {
                break;
            }
        }

        $this->assertEquals(120, count($result));

        $str = "+ and +1|2|3|4|{5|6}";
        $part = new OrPart($str);

        $part->getCount(true);
        $this->assertEquals(240, $part->getCount());
        $part->next();
        $result = array();
        $i = 0;
        while ($item = $part->getCurrentTemplate()) {
            $result[$item] = true;
            $part->next();
            if ($i++ >= 720) {
                break;
            }
        }
        // wrong :(
        $this->assertEquals(120, count($result));


    }
}