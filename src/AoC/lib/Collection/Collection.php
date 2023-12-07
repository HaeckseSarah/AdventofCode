<?php

declare(strict_types=1);

namespace HaeckseSarah\AoC\lib\Collection;

use HaeckseSarah\AoC\lib\Stream\StreamInterface;

/**
 * Undocumented class
 */
class Collection implements \ArrayAccess, \IteratorAggregate
{
    protected $items = [];

    /**
     * Undocumented function
     *
     * @param  StreamInterface $stream
     * @param  callable|null   $callback
     * @return Collection
     */
    public static function createFromStream(StreamInterface $stream, callable $callback = null): Collection
    {
        $collection = new self();
        $stream->rewind();

        while ($line = $stream->readLine(null)) {
            $collection->append((null !== $callback) ? $callback($line) : rtrim($line, "\n"));
        }

        return $collection;
    }

    public function __construct($items = [])
    {
        $this->items = $items;
    }

    public function __toString()
    {
        return print_r($this->items, true);
    }

    public function toArray()
    {
        return array_map(function ($value) {
            return is_object($value) && method_exists($value, 'toArray') ? $value->toArray() : $value;
        }, $this->items);
    }

    public function offsetExists($key): bool
    {
        return array_key_exists($key, $this->items);
    }

    public function offsetGet($key)
    {
        return $this->items[$key];
    }

    public function offsetSet($key, $value): void
    {
        if (is_null($key)) {
            $this->items[] = $value;
        } else {
            $this->items[$key] = $value;
        }
    }

    public function offsetUnset($key): void
    {
        unset($this->items[$key]);
    }

    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->items);
    }

    public function keys()
    {
        return new static(array_keys($this->items));
    }

    public function count()
    {
        return count($this->items);
    }

    public function rsort()
    {
        rsort($this->items);

        return $this;
    }

    public function slice($offset, $length = null)
    {
        return new static(array_slice($this->items, $offset, $length, true));
    }

    public function chunk($size)
    {
        $chunks = [];

        foreach (array_chunk($this->items, $size, true) as $chunk) {
            $chunks[] = new static($chunk);
        }

        return new static($chunks);
    }

    public function intersectByKeys(...$items)
    {
        return new static(array_intersect_key(
            $this->items,
            ...$items
        ));
    }

    public function append($element)
    {
        $this->items[] = $element;
    }

    public function getItem($index)
    {
        return $this->items[$index];
    }

    public function each(callable $callback)
    {
        foreach ($this->items as $key => $item) {
            if (false === $callback($item, $key)) {
                break;
            }
        }

        return $this;
    }

    public function filter(callable $callback = null)
    {
        if (is_null($callback)) {
            return new static(array_filter($this->items));
        }

        return new static(array_filter($this->items, $callback, ARRAY_FILTER_USE_BOTH));
    }

    public function map(callable $callback)
    {
        $keys = array_keys($this->items);

        $items = array_map($callback, $this->items, $keys);

        return new static(array_combine($keys, $items));
    }

    public function reduce(callable $callback, $initial = null)
    {
        return array_reduce($this->items, $callback, $initial);
    }

    public function sum(?callable $callback = null)
    {
        if (is_null($callback)) {
            return array_sum($this->items);
        }

        return $this->reduce(function ($result, $item) use ($callback) {
            return $result + $callback($item);
        }, 0);
    }

    public function sort(?callable $callback = null)
    {
        $items = $this->items;
        usort($items, $callback);
        return new static($items);
    }
}
