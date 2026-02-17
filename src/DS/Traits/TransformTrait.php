<?php

declare(strict_types=1);

namespace PlanB\DS\Traits;

use PlanB\DS\Vector\Vector;

/**
 * @template TKey
 * @template T
 */
trait TransformTrait
{
    /**  @use InternalTrait<TKey, T> */
    use InternalTrait;

    public function filter(?callable $condition = null): static
    {
        if ($condition === null) {
            $condition = fn ($value, $key) => (bool) $value;
        }
        $items = [];
        foreach ($this->items as $key => $value) {
            if ($condition($value, $key)) {
                $items[$key] = $value;
            }
        }

        return static::collect($items);
    }

    public function sort(?callable $comparator = null): static
    {
        $items = $this->items;
        if ($comparator === null) {
            asort($items);
        } else {
            /** @var callable(mixed, mixed): int $comparator) */
            uasort($items, $comparator);
        }

        return static::collect($items);
    }

    public function reversed(): static
    {
        return static::collect(array_reverse($this->items, true));
    }

    public function shuffle(): static
    {
        $items = $this->items;
        shuffle($items);

        return static::collect($items);
    }

    public function unique(): static
    {
        return static::collect(array_unique($this->items, SORT_REGULAR));
    }

    public function flatten(int $depth = 1): Vector
    {
        $result = [];
        foreach ($this->items as $value) {
            if (is_array($value) && $depth > 0) {
                $nested = Vector::collect($value)->flatten($depth - 1)->toArray();
                $result = array_merge($result, $nested);
            } else {
                $result[] = $value;
            }
        }

        return Vector::collect($result);
    }

    public function flatMap(callable $callback): Vector
    {
        $result = [];
        foreach ($this->items as $key => $value) {
            /** @var mixed $mapped */
            $mapped = $callback($value, $key);
            if (is_iterable($mapped)) {
                foreach ($mapped as $item) {
                    $result[] = $item;
                }
            } else {
                $result[] = $mapped;
            }
        }

        return Vector::collect($result);
    }

    public function reduce(callable $callback, mixed $initial = null): mixed
    {
        $carry = $initial;
        foreach ($this->items as $key => $value) {
            $carry = $callback($carry, $value, $key);
        }

        return $carry;
    }

    public function diff(iterable $input, ?callable $comparison = null): static
    {
        $other = is_array($input) ? $input : iterator_to_array($input);

        if ($comparison === null) {
            $diff = array_diff($this->items, $other);
        } else {
            $diff = array_udiff($this->items, $other, $comparison);
        }

        return static::collect($diff);
    }
}
