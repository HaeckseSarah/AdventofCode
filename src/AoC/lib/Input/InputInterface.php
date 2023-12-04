<?php

declare(strict_types=1);

namespace HaeckseSarah\AoC\lib\Input;

use HaeckseSarah\AoC\lib\Stream\StreamInterface;

interface InputInterface
{
    public function __construct(StreamInterface $input);

    public function rewind(): void;

    public function iterate($callable): void;

    public function map($callable): array;
}
