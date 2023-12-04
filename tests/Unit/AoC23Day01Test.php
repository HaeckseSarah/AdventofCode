<?php

declare(strict_types=1);

namespace HaeckseSarah\AoC23\Tests;

use HaeckseSarah\AoC\AoC23\Day01\Runner;
use HaeckseSarah\AoC\lib\Collection\Collection;
use Codeception\Test\Unit;
use HaeckseSarah\AoC\lib\Stream\StreamFactory;

class AoC23Day01Test extends Unit
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
        $runner = new Runner((new StreamFactory())->createStream(self::INPUT));
        $this->assertEquals($this->challengeAResult, $runner->challengeA());
    }

    public function testChallengeB()
    {
        $runner = new Runner((new StreamFactory())->createStream(self::INPUTB));

        $this->assertEquals($this->challengeBResult, $runner->challengeB());
    }
}
