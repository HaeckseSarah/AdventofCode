<?php

declare(strict_types=1);

namespace HaeckseSarah\AoC23\lib\Input;

use HaeckseSarah\AoC23\lib\Stream\StreamInterface;

interface InputInterface
{
    public function __construct(StreamInterface $input);

    public function rewind(): void;

    public function iterate($callable): void;

    public function map($callable): array;
}
