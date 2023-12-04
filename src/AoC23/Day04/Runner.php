<?php

declare(strict_types=1);

namespace HaeckseSarah\AoC23\Day04;

use HaeckseSarah\AoC23\lib\Collection\Collection;

class Runner extends \HaeckseSarah\AoC23\lib\Runner\Runner
{
    public function challengeA(): string
    {
        return (string) $this->input
        ->reduce(function ($carry, $card) {
            return $carry + ($card['matches'] > 0 ? pow(2, $card['matches'] - 1) : 0);
        });
    }

    public function challengeB(): string
    {
        $pile = new Collection();
        $this->input->each(function ($card) use (&$pile) {
            $id = $card['id'];
            $pile[$id] = ($pile[$id] ?? 0) + 1;
            for ($i = 1;$i <= $card['matches'];$i++) {
                $pile[$id + $i] = ($pile[$id + $i] ?? 0) + $pile[$id];
            }
        });

        return (string) $pile->sum();
    }

    protected function parseInput($input): Collection
    {
        return $input->map(function ($line) {
            [$card,$game] = explode(':', $line);
            [$_,$cardId] = explode(' ', $card, 2);
            [$winString,$nrString] = explode('|', $game);

            $winning = (new Collection(explode(' ', trim($winString))))
            ->filter()
            ->map(fn ($nr) => (int)$nr);

            $numbers = (new Collection(explode(' ', trim($nrString))))
            ->filter()
            ->map(fn ($nr) => (int)$nr);

            $matches = $numbers->filter(function ($number) use ($winning) {
                return in_array($number, $winning->toArray());
            })->count();

            return new Collection([
                'id' => (int) $cardId,
                'winning' => $winning,
                'numbers' => $numbers,
                'matches' => $matches,
            ]);
        });
    }
}
