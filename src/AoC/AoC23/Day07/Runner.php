<?php

declare(strict_types=1);

namespace HaeckseSarah\AoC\AoC23\Day07;

use HaeckseSarah\AoC\lib\Stream\StreamInterface;

class Runner extends \HaeckseSarah\AoC\lib\Runner\Runner
{
    public function challengeA(): string
    {
        return (string) $this->input
        ->sort(
            function ($a, $b) {
                return $a['hand']->compare($b['hand']);
            }
        )
        ->map(
            function ($game, $index) {
                return ($index + 1) * $game['bid'];
            }
        )
        ->sum();
    }

    public function challengeB(): string
    {
        return (string) $this->input
        ->map(
            function ($hand) {
                $hand['hand']->useJoker();
                return $hand;
            }
        )
        ->sort(
            function ($a, $b) {
                return $a['hand']->compare($b['hand']);
            }
        )
        ->map(
            function ($game, $index) {
                return ($index + 1) * $game['bid'];
            }
        )
        ->sum();
    }

    protected function parseInput(StreamInterface $input)
    {
        $input = parent::parseInput($input);
        return $input->map(function ($line) {
            [$hand,$bid] = explode(' ', $line);
            return [
                'hand' => new Hand($hand),
                'bid' => (int) $bid
            ];
        });
    }
}
