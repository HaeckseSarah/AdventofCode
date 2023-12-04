<?php

declare(strict_types=1);

namespace HaeckseSarah\AoC\lib\Input;

use HaeckseSarah\AoC\lib\Stream\StreamInterface;

class Input implements InputInterface
{
    protected $inputStream = null;

    public function __construct(StreamInterface $input)
    {
        $this->inputStream = $input;
    }

    public function rewind(): void
    {
        $this->inputStream->rewind();
    }

    public function iterate($callable): void
    {
        while ($line = $this->inputStream->readLine(null)) {
            $line = rtrim($line, "\n");
            if (false === $callable($line)) {
                break;
            }
        }
    }

    public function map($callable): array
    {
        $map = [];
        $this->iterate(function ($line) use ($callable) {
            $map[] = $callable($line);
        });

        return $map;
    }
}
