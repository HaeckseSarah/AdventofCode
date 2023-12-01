<?php

declare(strict_types=1);

namespace HaeckseSarah\AoC23\lib\Runner;

use HaeckseSarah\AoC23\lib\Collection\Collection;

interface RunnerInterface
{
    public function __construct(Collection $input);

    public function challengeA(): string;

    public function challengeB(): string;
}
