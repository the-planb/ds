<?php

declare(strict_types=1);

namespace PlanB\DS\Vector;

use PlanB\DS\Collection;
use PlanB\DS\Exception\ElementNotFoundException;

/**
 * @template T
 *
 * @extends  Collection<int, T>
 *
 * @implements VectorInterface<T>
 *
 * @phpstan-consistent-constructor
 */
class Vector extends Collection implements VectorInterface
{
    public static function collect(iterable $input = [], ?callable $normalizer = null): static
    {
        $items = is_array($input) ? $input : iterator_to_array($input);

        if ($normalizer !== null) {
            $keys = array_keys($items);
            $items = array_map($normalizer, $items, $keys);
        }

        /** @var static<T> */
        return new static(...array_values($items));
    }

    public function map(callable $callback): Vector
    {
        $items = [];
        foreach ($this->items as $key => $value) {
            $items[$key] = $callback($value, $key);
        }

        return new Vector(...$items);
    }

    public function get(int $index, mixed $default = null): mixed
    {
        if (func_num_args() === 1) {
            return $this->items[$index] ?? throw ElementNotFoundException::missingKey($index);
        }

        return $this->items[$index] ?? $default;
    }

    public function hasKey(int $index): bool
    {
        return array_key_exists($index, $this->items);
    }

    public function hasIndex(int $index): bool
    {
        return $this->hasKey($index);
    }
}
