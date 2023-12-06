<?php
/*
Todo: Clean this mess!
*/
declare(strict_types=1);

namespace HaeckseSarah\AoC\AoC23\Day05;

use Exception;
use HaeckseSarah\AoC\lib\Collection\Collection;
use HaeckseSarah\AoC\lib\Stream\StreamInterface;
use PHPUnit\TestRunner\TestResult\Collector;

class Runner extends \HaeckseSarah\AoC\lib\Runner\Runner
{
    public function challengeA(): string
    {
        $seeds = (new Collection(explode(' ', $this->input['seeds'])))->map(fn ($x) => (int) $x);
        $result = PHP_INT_MAX;
        foreach ($seeds as $seed) {
            $location = $this->findValueInMap($this->input['index'], $seed);
            $result = min($result, $location);
        }
        return (string) $result;
    }

    public function challengeB(): string
    {
        $seeds = [];
        preg_match_all('/[0-9]+\s[0-9]+/', $this->input['seeds'], $matches);
        foreach ($matches[0] as $m) {
            [$start,$length] = explode(' ', $m);
            $seeds[] = [
                'start' => $start,
                'end' => $start + $length - 1,
            ];
        }

        usort(
            $seeds,
            function ($a, $b) {
                return $a['start'] <=> $b['start'];
            }
        );

        $possibleValues = [];
        $map = $this->input['map'];

        foreach ($seeds as $seed) {
            foreach ($map as $e) {
                if ($e['srcEnd'] < $seed['start']) {
                    continue;
                }

                if ($e['srcStart'] > $seed['end']) {
                    continue;
                }

                $possibleValues[] = $e['srcStart'];
            }
        }

        $result = PHP_INT_MAX;
        foreach ($possibleValues as $seed) {
            $location = $this->findValueInMap($this->input['index'], $seed);
            $result = min($result, $location);
        }
        return (string) $result;
    }

    protected function parseInput(StreamInterface $input)
    {
        $input->seek(6);
        $line = trim($input->readLine(null));

        //parse seeds
        $seeds = $line;

        // parse maps
        $maps = [];
        $index = [];

        $mapName = 'undefined';
        $counter = 0;
        while ($line = $input->readLine(null)) {
            $line = rtrim($line, "\n");
            if ($line == '') {
                continue;
            }
            // map name
            if (!is_numeric(substr($line, 0, 1))) {
                $counter = 0;
                [$mapName,$_] = explode(' ', $line, 2);
                $maps[$mapName] = [];
                $index[$mapName] = ['src' => [],'dest' => []];

                continue;
            }

            [$destination,$source,$length] = explode(' ', $line, 3);
            $maps[$mapName][$counter] = new Collection([
                'srcStart' => (int) $source,
                'srcEnd' => ((int) $source) + ((int) $length) - 1,
                'destStart' => (int) $destination,
                'destEnd' => ((int) $destination) + ((int) $length) - 1,
            ]);

            $index[$mapName]['src'][] = &$maps[$mapName][$counter];
            $index[$mapName]['dest'][] = &$maps[$mapName][$counter];

            $counter++;
        }

        //sort indices
        foreach ($index as &$map) {
            foreach ($map as $name => &$idx) {
                usort(
                    $idx,
                    function ($a, $b) use ($name) {
                        return $a[$name.'Start'] <=> $b[$name.'Start'];
                    }
                );
            }
        }

        // build pathmap
        $pathMap = [];
        foreach ($maps as $mapName => $_) {
            [$left,$_,$right] = explode('-', $mapName);
            if (!array_key_exists($left, $pathMap)) {
                $pathMap[$left] = ['left' => null,'right' => null];
            }
            if (!array_key_exists($right, $pathMap)) {
                $pathMap[$right] = ['left' => null,'right' => null];
            }
            $pathMap[$left]['right'] = [
                'key' => $right,
                'map' => $mapName,
            ];
            $pathMap[$right]['left'] = [
                'key' => $left,
                'map' => $mapName,
            ];
        }

        $slMap = $this->buildSeedToLocationMap($pathMap, $index);
        $slidx = ['src' => [],'dest' => []];

        foreach ($slMap as $k => &$v) {
            $slidx['src'][] = &$v;
            $slidx['dest'][] = &$v;
        }

        //sort indices
        foreach ($slidx as $name => &$idx) {
            usort(
                $idx,
                function ($a, $b) use ($name) {
                    return $a[$name.'Start'] <=> $b[$name.'Start'];
                }
            );
        }

        return [
            'seeds' => $seeds,
            'map' => $slMap,
            'index' => $slidx,
        ];
    }


    protected function buildSeedToLocationMap($pathMap, $index)
    {
        $seeds = [0];
        foreach ($pathMap as $src => $data) {
            if ($data['right'] === null) {
                continue;
            }

            $map = $index[$data['right']['map']];
            foreach ($map as $m) {
                foreach ($m as $e) {
                    // add values by destination
                    array_push($seeds, ...$this->findMultipleValuesInMap(
                        $pathMap,
                        $index,
                        $data['right']['key'],
                        'seed',
                        [
                            0,
                            $e['destStart'] - 1,
                            $e['destStart'],
                            $e['destEnd'],
                            $e['destEnd'] + 1,
                        ],
                        false
                    ));


                    if ($src == 'seed') {
                        $seeds[] = $e['srcStart'];
                        $seeds[] = $e['srcEnd'];
                        continue;
                    }
                    array_push($seeds, ...$this->findMultipleValuesInMap(
                        $pathMap,
                        $index,
                        $data['right']['key'],
                        'seed',
                        [
                            0,
                            $e['srcStart'] - 1,
                            $e['srcStart'],
                            $e['srcEnd'],
                            $e['srcEnd'] + 1,
                        ],
                        false
                    ));
                    //////////////////////
                }
            }
        }

        $seeds = array_unique($seeds);
        asort($seeds);

        $m = [];
        foreach ($seeds as $s) {
            $l = $this->findMappedValue($pathMap, $index, 'seed', 'location', $s);
            $m[$s] = $l;
        }

        $r = [];
        $prev = 0;
        foreach ($m as $s => $l) {
            $r[$s] = new Collection([
                'srcStart' => (int) $s,
                'diff' => ((int) $l) - ((int)$s),
            ]);
            $r[$prev]['srcEnd'] = $s - 1;
            $prev = $s;
        }
        $nm = [];
        foreach ($r as $x) {
            if (($x['srcEnd'] ?? null) === null) {
                continue;
            }
            if ($x['diff'] === 0) {
                continue;
            }

            $nm[] = [
                'srcStart' => $x['srcStart'],
                'srcEnd' => $x['srcEnd'],
                'destStart' => $x['srcStart'] + $x['diff'],
                'destEnd' => $x['srcEnd'] + $x['diff'],
            ];
        }

        return $nm;
    }

    protected function findMappedValue($pathMap, $index, string $source, string $destination, int $value, bool $goRight = true): int
    {
        $key = (($goRight) ? 'right' : 'left');

        $mapData = $pathMap[$source][$key];
        if ($mapData === null) {
            throw new Exception("Path not found \n".var_export([
                'source' => $source,
                'destination' => $destination,
                'goRight' => $goRight
            ]));
        }

        $mappedValue = $this->findValueInMap($index[$mapData['map']], $value, $goRight);
        if ($mapData['key'] === $destination) {
            return $mappedValue;
        }

        return $this->findMappedValue($pathMap, $index, $mapData['key'], $destination, $mappedValue, $goRight);
    }

    protected function findValueInMap(array $mapIndex, int $value, bool $bySource = true)
    {
        $key = ($bySource) ? 'src' : 'dest';

        foreach ($mapIndex[$key] as $entry) {
            // end before search value
            if ($entry[$key.'End'] < $value) {
                continue;
            }

            // start after search value => no previous match => result=value
            if ($entry[$key.'Start'] > $value) {
                return $value;
            }

            //value in range of entry
            $multiplicator = ($bySource) ? -1 : 1;
            $offset = ($entry['srcStart'] - $entry['destStart']) * $multiplicator;
            return $value + $offset;
        }

        // value out of mapped values => result=value
        return $value;
    }

    protected function findMultipleValuesInMap($pathMap, $index, $source, $destination, $values, $goRight)
    {
        $result = [];
        foreach ($values as $value) {
            $result[] = $this->findMappedValue(
                $pathMap,
                $index,
                $source,
                $destination,
                $value,
                $goRight
            );
        }
        return $result;
    }
}
