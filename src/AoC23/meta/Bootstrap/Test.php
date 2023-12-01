<?php
declare(strict_types=1);

namespace HaeckseSarah\AoC23\Tests;

use HaeckseSarah\AoC23\Day###DAY###\Runner;
use HaeckseSarah\AoC23\lib\Collection\Collection;
use Codeception\Test\Unit;
use HaeckseSarah\AoC23\lib\Stream\StreamFactory;

class Day###DAY###Test extends Unit
{
    const INPUT = <<<EOF
    XXX
    EOF;

    protected $challengeAResult = "";
    protected $challengeBResult = "";

    public function testChallengeA()
    {
        $input = Collection::createFromStream((new StreamFactory())->createStream(self::INPUT));
        $runner = new Runner($input);
        $this->assertEquals($this->challengeAResult,$runner->challengeA());
    }
    public function testChallengeB()
    {
        $input = Collection::createFromStream((new StreamFactory())->createStream(self::INPUT));
        $runner = new Runner($input);
        $this->assertEquals($this->challengeBResult,$runner->challengeB());        
    }
}
