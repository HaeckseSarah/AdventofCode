<?php

declare(strict_types=1);

namespace HaeckseSarah\AoC\Tests;

use HaeckseSarah\AoC\AoC23\Day07\Runner;
use Codeception\Test\Unit;
use HaeckseSarah\AoC\lib\Stream\StreamFactory;

class AoC23Day07Test extends Unit
{
    public const INPUT = <<<EOF
    32T3K 765
    T55J5 684
    KK677 28
    KTJJT 220
    QQQJA 483
    EOF;

    protected $challengeAResult = '6440';
    protected $challengeBResult = '5905';

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
