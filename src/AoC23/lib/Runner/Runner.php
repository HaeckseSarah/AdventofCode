<?php

declare(strict_types=1);

namespace HaeckseSarah\AoC23\lib\Runner;

use HaeckseSarah\AoC23\lib\Collection\Collection;

abstract class Runner implements RunnerInterface
{
    protected $input = null;

    public function __construct(Collection $input)
    {
        $this->input = $input;
    }
}
