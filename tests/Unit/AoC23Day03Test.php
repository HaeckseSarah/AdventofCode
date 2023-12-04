<?php

declare(strict_types=1);

namespace HaeckseSarah\AoC23\Tests;

use HaeckseSarah\AoC\AoC23\Day03\Runner;
use Codeception\Test\Unit;
use HaeckseSarah\AoC\lib\Stream\StreamFactory;

class AoC23Day03Test extends Unit
{
    public const INPUT = <<<EOF
    467..114..
    ...*......
    ..35..633.
    ......#...
    617*......
    .....+.58.
    ..592.....
    ......755.
    ...$.*....
    .664.598..
    EOF;

    protected $challengeAResult = "4361";
    protected $challengeBResult = "467835";

    public function testChallengeA()
    {
        $runner = new Runner((new StreamFactory())->createStream(self::INPUT));
        $this->assertEquals($this->challengeAResult, $runner->challengeA());
    }

    public function testChallengeB()
    {
        $runner = new Runner((new StreamFactory())->createStream(self::INPUT));

        $this->assertEquals($this->challengeBResult, $runner->challengeB());
    }
}
