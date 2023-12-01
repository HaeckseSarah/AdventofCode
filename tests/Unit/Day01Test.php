<?php

declare(strict_types=1);

namespace HaeckseSarah\AoC23\Tests;

use HaeckseSarah\AoC23\Day01\Runner;
use HaeckseSarah\AoC23\lib\Collection\Collection;
use Codeception\Test\Unit;
use HaeckseSarah\AoC23\lib\Stream\StreamFactory;

class Day01Test extends Unit
{
    public const INPUT = <<<EOF
    1abc2
    pqr3stu8vwx
    a1b2c3d4e5f
    treb7uchet
    EOF;

    public const INPUTB = <<<EOF
    two1nine
    eightwothree
    abcone2threexyz
    xtwone3four
    4nineeightseven2
    zoneight234
    7pqrstsixteen
    EOF;

    protected $challengeAResult = "142";
    protected $challengeBResult = "281";

    public function testChallengeA()
    {
        $input = Collection::createFromStream((new StreamFactory())->createStream(self::INPUT));
        $runner = new Runner($input);
        $this->assertEquals($this->challengeAResult, $runner->challengeA());
    }
    public function testChallengeB()
    {
        $input = Collection::createFromStream((new StreamFactory())->createStream(self::INPUTB));
        $runner = new Runner($input);
        $this->assertEquals($this->challengeBResult, $runner->challengeB());
    }
}
