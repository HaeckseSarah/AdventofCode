<?php

declare(strict_types=1);

namespace HaeckseSarah\AoC\lib\Runner;

use HaeckseSarah\AoC\lib\Collection\Collection;
use HaeckseSarah\AoC\lib\Stream\StreamInterface;

abstract class Runner implements RunnerInterface
{
    protected $input = null;

    public function __construct(StreamInterface $input)
    {
        $this->input = $this->parseInput($input);
    }

    protected function parseInput(StreamInterface $input)
    {
        return Collection::createFromStream($input);
    }
}
