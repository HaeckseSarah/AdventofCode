<?php

declare(strict_types=1);

namespace HaeckseSarah\AoC23\Day02;

use HaeckseSarah\AoC23\lib\Collection\Collection;

class Runner extends \HaeckseSarah\AoC23\lib\Runner\Runner
{
    public const MAX_CUBES = [
        'red' => 12,
        'green' => 13,
        'blue' => 14
    ];

    public function challengeA(): string
    {
        return (string) $this->parseInput()
                    ->filter(function ($round) {
                        return
                            $round['max']['red'] <= $this::MAX_CUBES['red']
                            && $round['max']['green'] <= $this::MAX_CUBES['green']
                            && $round['max']['blue'] <= $this::MAX_CUBES['blue'];
                    })
                    ->reduce(fn ($sum, $el) => $sum += $el['id']);
    }

    public function challengeB(): string
    {
        return (string) $this->parseInput()
        ->map(
            fn ($round) => $round['max']->reduce(fn ($product, $cubes) => $product * $cubes, 1)
        )
        ->reduce(
            fn ($sum, $el) => $sum += $el
        );
    }

    private function parseInput(): Collection
    {
        return $this->input->map(
            function ($line) {
                //Game 2: 1 blue, 2 green; 3 green, 4 blue, 1 red; 1 green, 1 blue
                [$game, $gameInfo] = explode(':', $line, 2);
                $rounds =  (new Collection(explode(';', $gameInfo)))
                ->map([$this, 'parseRound']);

                return new Collection([
                    'id' => (int) substr($game, 5),
                    'rounds' => $rounds,
                    'max' => $rounds->reduce(
                        function ($result, $draws) {
                            $draws->each(
                                function ($item) use ($result) {
                                    $result[$item['color']] = max($result[$item['color']], $item['count']);
                                }
                            );
                            return $result;
                        },
                        new Collection([
                            'red' => 0,
                            'green' => 0,
                            'blue' => 0
                        ])
                    )
                ]);
            }
        );
    }

    public function parseRound(string $round)
    {
        return (new Collection(explode(',', $round)))
            ->map(
                function ($draw) {
                    [$count,$color] = explode(' ', trim($draw));
                    return [
                        'color' => $color,
                        'count' => (int) $count,
                    ];
                }
            );
    }
}
