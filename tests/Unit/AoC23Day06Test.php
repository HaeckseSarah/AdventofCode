<?php

declare(strict_types=1);

namespace HaeckseSarah\AoC\Tests;

use HaeckseSarah\AoC\AoC23\Day06\Runner;
use Codeception\Test\Unit;
use HaeckseSarah\AoC\lib\Stream\StreamFactory;

class AoC23Day06Test extends Unit
{
    public const INPUT = <<<EOF
    Time:      7  15   30
    Distance:  9  40  200
    EOF;

    protected $challengeAResult = '288';
    protected $challengeBResult = '71503';

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
