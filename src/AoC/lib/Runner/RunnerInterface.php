<?php

declare(strict_types=1);

namespace HaeckseSarah\AoC\lib\Runner;

use HaeckseSarah\AoC\lib\Stream\StreamInterface;

interface RunnerInterface
{
    public function __construct(StreamInterface $input);

    public function challengeA();

    public function challengeB();
}
