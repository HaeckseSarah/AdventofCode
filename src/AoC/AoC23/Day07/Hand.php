<?php

declare(strict_types=1);

namespace HaeckseSarah\AoC\AoC23\Day07;

use Exception;
use HaeckseSarah\AoC\lib\Collection\Collection;

class Hand extends Collection
{
    public const TYPE_FIVE = 6;
    public const TYPE_FOUR = 5;
    public const TYPE_FULL_HOUSE = 4;
    public const TYPE_THREE = 3;
    public const TYPE_TWO_PAIR = 2;
    public const TYPE_PAIR = 1;
    public const TYPE_HIGH = 0;

    private $type = null;
    private $jokerRule = false;

    public function __construct(string $hand)
    {
        $length = strlen($hand);
        for ($i = 0;$i < $length;$i++) {
            $card = substr($hand, $i, 1);
            $this->items[] = $card;
        }
    }

    public function compare(Hand $hand)
    {
        $r = $this->getType() <=> $hand->getType();
        if ($r != 0) {
            return $r;
        }
        $a = $this->getIterator();
        $b = $hand->getIterator();
        $a->rewind();
        $b->rewind();
        do {
            $r = $this->cardToValue($a->current()) <=> $this->cardToValue($b->current());

            if ($r != 0) {
                return $r;
            }

            $a->next();
            $b->next();
        } while ($a->valid() && $b->valid());

        return 0;
    }

    public function getType()
    {
        if ($this->type === null) {
            $this->type = $this->calculateType();
        }
        return $this->type;
    }

    public function useJoker()
    {
        $this->jokerRule = true;
        $this->type = null;
    }

    protected function calculateType()
    {
        $stack = $this->items;
        $tmp = [];
        foreach ($stack as $c) {
            $tmp[$c] = ($tmp[$c] ?? 0) + 1;
        }
        $joker = 0;
        if ($this->jokerRule) {
            $joker = $tmp['J'] ?? 0;
            unset($tmp['J']);
        }

        rsort($tmp);

        $tmp[0] = ($tmp[0] ?? 0) + $joker;

        switch($tmp[0]) {
            case 5:
                return self::TYPE_FIVE;
                break;
            case 4:
                return self::TYPE_FOUR;
                break;
            case 3:
                return count($tmp) == 2 ? self::TYPE_FULL_HOUSE : self::TYPE_THREE;
                break;
            case 2:
                return count($tmp) == 3 ? self::TYPE_TWO_PAIR : self::TYPE_PAIR;
                break;
            default:
                return self::TYPE_HIGH;
        }
    }

    protected function cardToValue(string $card)
    {
        if (is_numeric($card)) {
            return (int) $card;
        }

        switch($card) {
            case 'A':
                return 14;
            case 'K':
                return 13;
            case 'Q':
                return 12;
            case 'J':
                return ($this->jokerRule) ? 0 : 11;
            case 'T':
                return 10;
            default:
                throw new Exception('unidentified Card: "'.$card.'"');
        }
    }
}
