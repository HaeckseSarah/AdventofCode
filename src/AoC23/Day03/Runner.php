<?php

declare(strict_types=1);

namespace HaeckseSarah\AoC23\Day03;

use HaeckseSarah\AoC23\lib\Collection\Collection;

class Runner extends \HaeckseSarah\AoC23\lib\Runner\Runner
{
    /**
     * Challenge A
     * find all numbers with adjacent symbol
     *
     * @return string
     */
    public function challengeA(): string
    {
        $input = $this->parseInput();
        $numbers = $this->findNumbers($input);


        return (string) $numbers
            ->filter(function ($number) use ($input) {
                for ($i = ($number['start'] - 1);$i < ($number['end'] + 1);$i++) {
                    $rows = $this->getAdjectingRows($input, $number['row']);
                    foreach ($rows as $row) {
                        if ($this->isSymbol($row[$i] ?? '.')) {
                            return true;
                        }
                    }
                }
                return false;
            })
        ->reduce(fn ($sum, $number) => $sum += $number['value']);
    }

    /**
     * Challenge B
     * find all asterisk (*) with 2 adjecting numbers
     *
     * @return string
     */
    public function challengeB(): string
    {
        $input = $this->parseInput();
        $numbersByRow = $this->groupNumbersByRow($this->findNumbers($input));

        return (string) $this->findGears($input, $numbersByRow)->sum();
    }

    /**
     * creates simple two dimensional collection from input
     *
     * @return Collection
     */
    public function parseInput(): Collection
    {
        return $this->input->map(function ($line) {
            return new Collection(str_split($line, 1));
        });
    }

    /**
     * get a collection of all numbers (with coordinates) in input
     *
     * @param  Collection $input
     * @return Collection
     */
    public function findNumbers(Collection $input): Collection
    {
        $result = new Collection();

        $input->each(function ($row, $rowIdx) use (&$result) {
            $start = $number = false;

            $row->each(function ($cell, $colIdx) use (&$result, $rowIdx, &$start, &$number) {
                if (is_numeric($cell)) {
                    if ($number === false) {
                        $number = $cell;
                        $start = $colIdx;
                    } else {
                        $number .= $cell;
                    }
                    return;
                }

                // cell is not numeric
                if ($number !== false) {
                    $result[] = $this->createNumberObj($number, $rowIdx, $start, $colIdx);
                    $number = false;
                }
            });

            if ($number !== false) {
                $result[] = $this->createNumberObj($number, $rowIdx, $start, $row->count() - 1);
            }
        });

        return $result;
    }

    /**
     * create new number object
     *
     * @param             $number
     * @param             $row
     * @param             $start
     * @param             $end
     * @return Collection
     */
    private function createNumberObj($number, $row, $start, $end): Collection
    {
        return new Collection([
            'value' => $number,
            'row' => (int) $row,
            'start' => (int) $start,
            'end' => (int) $end,
        ]);
    }

    /**
     * get row and the row before and after
     *
     * @param  Collection $input
     * @param  int        $index
     * @return Collection
     */
    private function getAdjectingRows(Collection $input, int $index): Collection
    {
        return new Collection([
            $input[$index - 1] ?? new Collection(),
            $input[$index ] ?? new Collection(),
            $input[$index + 1] ?? new Collection(),
        ]);
    }

    /**
     * check if character is a symbol
     * must not be numeric or a dot (.)
     *
     * @param       $char
     * @return bool
     */
    private function isSymbol($char): bool
    {
        return !is_numeric($char) && $char != '.';
    }

    /**
     * create new daaset with all numbers by row
     *
     * @param  Collection $numbers
     * @return Collection
     */
    private function groupNumbersByRow(Collection $numbers): Collection
    {
        $result = new Collection();
        $numbers->each(function ($number) use (&$result) {
            if (!($result[$number['row']] ?? false)) {
                $result[$number['row']] = new Collection();
            }
            $result[$number['row']][] = $number;
        });

        return $result;
    }

    /**
     * find all gears and calculate ratio
     *
     * @param  Collection $input
     * @param             $numbersByRow
     * @return Collection
     */
    private function findGears(Collection $input, $numbersByRow): Collection
    {
        $result = new Collection();

        $input->each(function ($row, $rowIdx) use (&$result, $numbersByRow) {
            $row->each(function ($cell, $colIdx) use (&$result, $numbersByRow, $rowIdx) {
                if ($cell !== '*') {
                    return;
                }
                $ratio = new Collection();
                $rows = $this->getAdjectingRows($numbersByRow, $rowIdx);
                $rows->each(function ($numbers) use (&$ratio, $colIdx) {
                    $numbers->each(function ($number) use (&$ratio, $colIdx) {
                        if (
                            $number['start'] <= ($colIdx + 1) && $number['end'] > ($colIdx - 1)
                        ) {
                            $ratio[] = $number;
                        }
                    });
                });

                if ($ratio->count() == 2) {
                    $result[] = $ratio->reduce(
                        fn ($carry, $number) => $carry *= $number['value'],
                        1
                    );
                }
            });
        });

        return $result;
    }
}
