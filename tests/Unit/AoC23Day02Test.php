<?php

declare(strict_types=1);

namespace HaeckseSarah\AoC23\Tests;

use HaeckseSarah\AoC\AoC23\Day02\Runner;
use Codeception\Test\Unit;
use HaeckseSarah\AoC\lib\Stream\StreamFactory;

class AoC23Day02Test extends Unit
{
    public const INPUT = <<<EOF
    Game 1: 3 blue, 4 red; 1 red, 2 green, 6 blue; 2 green
    Game 2: 1 blue, 2 green; 3 green, 4 blue, 1 red; 1 green, 1 blue
    Game 3: 8 green, 6 blue, 20 red; 5 blue, 4 red, 13 green; 5 green, 1 red
    Game 4: 1 green, 3 red, 6 blue; 3 green, 6 red; 3 green, 15 blue, 14 red
    Game 5: 6 red, 1 blue, 3 green; 2 blue, 1 red, 2 green
    EOF;

    protected $challengeAResult = '8';
    protected $challengeBResult = '2286';

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
