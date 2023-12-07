<?php

declare(strict_types=1);

namespace HaeckseSarah\AoC\AoC23\Day06;

use HaeckseSarah\AoC\lib\Collection\Collection;
use HaeckseSarah\AoC\lib\Stream\StreamInterface;

class Runner extends \HaeckseSarah\AoC\lib\Runner\Runner
{
    public function challengeA(): string
    {
        return (string) $this->input
        ->map(fn ($game) => $this->findPossibleSolutions($game))
        ->reduce(function ($a, $b) {return $a * $b;}, 1);
    }

    public function challengeB(): string
    {
        $game = $this->input
        ->reduce(function ($c, $e) {
            $c['time'] = $c['time'].$e['time'];
            $c['distance'] = $c['distance'].$e['distance'];
            return $c;
        }, ['time' => '','distance' => '']);

        return (string) $this->findPossibleSolutions($game);
    }

    protected function parseInput(StreamInterface $input)
    {
        $input->seek(9, SEEK_SET);
        $time = (new Collection(explode(' ', trim($input->readLine(null)))))->filter()->getIterator();

        $input->seek(9, SEEK_CUR);
        $distance = (new Collection(explode(' ', trim($input->readLine(null)))))->filter()->getIterator();

        $time->rewind();
        $distance->rewind();

        $result = [];
        do {
            $result[] = [
                'time' => (int) $time->current(),
                'distance' => (int) $distance->current(),
            ];

            $time->next();
            $distance->next();
        } while ($time->valid() && $distance->valid());

        return new Collection($result);
    }

    protected function findPossibleSolutions($game)
    {
        /*
        Math solution

        driveTime = GameTime - button
        distance = driveTime * button

        distance = (GameTime - button) * button
        distance = GameTime*button - button^2
        button^2 - GameTime*button + distance = 0

        min = (GameTime + SQRT(GameTime*GameTime - 4 * distance))/2
        B2 = (GameTime - SQRT(GameTime*GameTime - 4 * distance))/2
        */

        $d = $game['time'] * $game['time'] - 4 * $game['distance'];
        $sqrtd = sqrt($d);
        $a = floor(($game['time'] + $sqrtd) / 2);
        $b =  ceil(($game['time'] - $sqrtd) / 2);

        return  floor($a) - ceil($b) + ((floor($sqrtd) == $sqrtd) ? -1 : 1);

        /*
        first bruteforce solution

        $max = floor($game['time'] / 2);
        $count = 0;
        for ($i = $max;$i >= 0;$i--) {
            $m = $game['time'] - $i;
            $d = $i * $m;
            //echo "\n{$game['time']} => $i * $m = $d <=>{$game['distance']}";
            if ($d <= $game['distance']) {
                return $count;
            }
            $count++;
            if ($i <> $m) {
                $count++;
            }
        }
        return $count;
        */
    }
}
