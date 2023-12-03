<?php

declare(strict_types=1);

namespace HaeckseSarah\AoC23\Tests;

use HaeckseSarah\AoC23\Day03\Runner;
use HaeckseSarah\AoC23\lib\Collection\Collection;
use Codeception\Test\Unit;
use HaeckseSarah\AoC23\lib\Stream\StreamFactory;

class Day03Test extends Unit
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
        $input = Collection::createFromStream((new StreamFactory())->createStream(self::INPUT));
        $runner = new Runner($input);
        $this->assertEquals($this->challengeAResult, $runner->challengeA());
    }
    public function testChallengeB()
    {
        $input = Collection::createFromStream((new StreamFactory())->createStream(self::INPUT));
        $runner = new Runner($input);
        $this->assertEquals($this->challengeBResult, $runner->challengeB());
    }
}
