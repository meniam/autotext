<?php

namespace PermutationTest;

use PHPUnit\Framework\TestCase;
use Permutation\Permutation;

class PermutationTest extends TestCase
{
    public function testCount()
    {
        $permutation = new Permutation(10);
        $this->assertEquals(10, $permutation->count());
    }

    /**
     * @expectedException \Permutation\Exception
     */
    public function testExceptionZero()
    {
        new Permutation(0);
    }

    public function testGetByPos()
    {
        $permutation = new Permutation(2);
        $this->assertEquals([0, 1], $permutation->getByPos(0));
        $this->assertEquals([1, 0], $permutation->getByPos(1));
        $this->assertEquals([0, 1], $permutation->getByPos(2));
        $this->assertEquals([1, 0], $permutation->getByPos(3));
        $this->assertEquals([0, 1], $permutation->getByPos(4));
        $this->assertEquals([1, 0], $permutation->getByPos(5));

        $permutation = new Permutation(3);
        $this->assertEquals([0, 1, 2], $permutation->getByPos(0));
        $this->assertEquals([0, 2, 1], $permutation->getByPos(1));
        $this->assertEquals([1, 0, 2], $permutation->getByPos(2));
        $this->assertEquals([1, 2, 0], $permutation->getByPos(3));
        $this->assertEquals([2, 0, 1], $permutation->getByPos(4));
        $this->assertEquals([2, 1, 0], $permutation->getByPos(5));
        $this->assertEquals([0, 1, 2], $permutation->getByPos(6));
    }

    public function testPermuteArray()
    {
        $permutation = new Permutation(2);
        $this->assertEquals([0 => [0, 1], 1 => [1, 0]], $permutation->permuteArray());
    }

    /**
     * @expectedException \Permutation\Exception
     */
    public function testExceptionMany()
    {
        new Permutation(PHP_INT_MAX);
    }

    public function testCurrent()
    {
        $permutation = new Permutation(3);
        $this->assertEquals(array(0, 1, 2), $permutation->current());
    }

    public function testNext()
    {
        $permutation = new Permutation(3);
        $this->assertEquals(array(0, 2, 1), $permutation->next());
    }

    public function testOverflow()
    {
        $permutation = new Permutation(2);
        $this->assertEquals(array(1, 0), $permutation->next());
        $this->assertEquals(array(0, 1), $permutation->next());
        $this->assertEquals(array(1, 0), $permutation->next());
        $this->assertEquals(array(0, 1), $permutation->next());
    }
}