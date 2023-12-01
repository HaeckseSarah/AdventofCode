<?php

declare(strict_types=1);

namespace HaeckseSarah\AoC23\Day01;

use HaeckseSarah\AoC23\lib\Collection\Collection;

/**
 * Undocumented class
 */
class Runner extends \HaeckseSarah\AoC23\lib\Runner\Runner
{
    public function challengeA(): string
    {
        return (string) $this->input
            ->map(
                function ($element) {
                    $a = $b = false;
                    $length = strlen($element);
                    for ($i = 0;$i <= $length;$i++) {
                        $char = substr($element, $i, 1);
                        if (is_numeric($char)) {
                            $a = $char;
                            break;
                        }
                    }

                    for ($i = 1;$i <= $length;$i++) {
                        $char = substr($element, -$i, 1);
                        if (is_numeric($char)) {
                            $b = $char;
                            break;
                        }
                    }

                    return (int) "$a$b";
                }
            )
            ->reduce(
                fn ($element, $sum) => $sum + $element,
                0
            );
    }

    public function challengeB(): string
    {
        return (string) $this->input
        ->map(
            function ($element) {
                $a = $b = false;

                $length = strlen($element);
                for ($i = 0;$i <= $length;$i++) {
                    if ($a = $this->toNumber(substr($element, $i, 5))) {
                        break;
                    }
                }

                for ($i = 1;$i <= $length;$i++) {
                    if ($b = $this->toNumber(substr($element, -$i, 5))) {
                        break;
                    }
                }
                return "$a$b";
            }
        )
        ->reduce(
            fn ($element, $sum) => $sum + $element,
            0
        );
    }

    public function toNumber($string)
    {
        $char = substr($string, 0, 1);
        if (is_numeric($char)) {
            return $char;
        }

        switch ($char) {
            case 'e': //eight
                return $string === 'eight' ? 8 : false;
                break;
            case 'f': //four, five
                return substr($string, 0, 4) === 'four'
                        ? 4
                        : (
                            substr($string, 0, 4) === 'five'
                            ? 5
                            : false
                        );
                break;
            case 'n': //nine
                return substr($string, 0, 4) === 'nine' ? 9 : false;
                break;
            case 'o': // one
                return substr($string, 0, 3) === 'one' ? 1 : false;
                # code...
                break;
            case 't': //two, three
                return substr($string, 0, 3) === 'two'
                            ? 2
                            : (
                                $string === 'three'
                                ? 3
                                : false
                            );
                # code...
                break;
            case 's': //six seven
                return substr($string, 0, 3) === 'six'
                        ? 6
                        : (
                            $string === 'seven'
                            ? 7
                            : false
                        );
                break;
            default:
                return false;
                break;
        }
    }
}
