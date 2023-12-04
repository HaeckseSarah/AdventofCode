<?php
declare(strict_types=1);

namespace HaeckseSarah\AoC\Tests;

use HaeckseSarah\AoC\AoC###YEAR###\Day###DAY###\Runner;
use Codeception\Test\Unit;
use HaeckseSarah\AoC\lib\Stream\StreamFactory;

class AoC###YEAR###Day###DAY###Test extends Unit
{
    const INPUT = <<<EOF
    XXX
    EOF;

    protected $challengeAResult = '';
    protected $challengeBResult = '';

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
