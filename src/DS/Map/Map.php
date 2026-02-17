<?php

declare(strict_types=1);

namespace PlanB\DS\Map;

use PlanB\DS\Collection;
use PlanB\DS\Exception\ElementNotFoundException;
use PlanB\DS\Vector\Vector;

/**
 * @template T
 *
 * @extends  Collection<string, T>
 *
 * @implements MapInterface<T>
 *
 * @phpstan-consistent-constructor
 */
class Map extends Collection implements MapInterface
{
    /**
     * @param T ...$items
     */
    public function __construct(...$items)
    {
        $input = $this->normalizeKeys($items);

        parent::__construct(...$input);
    }

    public function map(callable $callback): Map
    {
        $items = [];
        foreach ($this->items as $key => $value) {
            $items[$key] = $callback($value, $key);
        }

        return new Map(...$items);
    }

    public static function collect(iterable $input = [], ?callable $normalizer = null): static
    {
        /** @var array<string, T> $items */
        $items = is_array($input) ? $input : iterator_to_array($input);

        $items = $items
                |> array_keys(...)
                |> (fn($x) => array_map(strval(...), $x))
                |> (fn($x) => array_combine($x, $items,));

        if ($normalizer !== null) {
            $keys = array_keys($items);
            $values = array_map($normalizer, $items, $keys);

            /** @var array<string, T> $items */
            $items = array_combine($keys, $values);
        }

        return new static(...$items);
    }

    public function get(string $key, mixed $default = null): mixed
    {
        if (func_num_args() === 1) {
            return $this->items[$key] ?? throw ElementNotFoundException::missingKey($key);
        }

        return $this->items[$key] ?? $default;
    }

    public function hasKey(int|string $key): bool
    {
        return array_key_exists($key, $this->items);
    }

    public function keys(): Vector
    {
        /** @var Vector<string> */
        return Vector::collect(array_keys($this->items));
    }

    public function values(): Vector
    {
        /** @var Vector<T> */
        return Vector::collect(array_values($this->items));
    }

    public function mapKeys(callable $callback): static
    {
        $items = [];

        foreach ($this->items as $key => $value) {
            /** @var array-key $newKey */
            $newKey = $callback($value, $key);
            $items[$newKey] = $value;
        }

        /** @var static */
        return new static(...$items);
    }

    public function merge(iterable $input): static
    {
        $items = $this->items;

        foreach ($input as $key => $value) {
            $items[$key] = $value;
        }

        /** @var static */
        return new static(...$items);
    }

    public function keySort(?callable $comparison = null): static
    {
        $items = $this->items;

        if ($comparison === null) {
            ksort($items);
        } else {
            /** @var callable(string, string): int $comparison */
            uksort($items, $comparison);
        }

        /** @var static */
        return new static(...$items);
    }

    public function diffKeys(iterable $input, $comparison = null): static
    {
        $other = is_array($input) ? $input : iterator_to_array($input);

        if ($comparison === null) {
            $diff = array_diff_key($this->items, $other);
        } else {
            /** @var callable(int|string, int|string): int $comparison */
            $diff = array_diff_ukey($this->items, $other, $comparison);
        }

        /** @var static */
        return new static(...$diff);
    }

    public function intersect(iterable $input, ?callable $comparison = null): static
    {
        $other = is_array($input) ? $input : iterator_to_array($input);

        if ($comparison === null) {
            $intersect = array_intersect($this->items, $other);
        } else {
            $intersect = array_uintersect($this->items, $other, $comparison);
        }

        /** @var static */
        return new static(...$intersect);
    }

    public function intersectKeys(iterable $input, $comparison = null): static
    {
        $other = is_array($input) ? $input : iterator_to_array($input);

        if ($comparison === null) {
            $intersect = array_intersect_key($this->items, $other);
        } else {
            /** @var callable(int|string, int|string): int $comparison */
            $intersect = array_intersect_ukey($this->items, $other, $comparison);
        }

        /** @var static */
        return new static(...$intersect);
    }

    /**
     * @param T $item
     */
    protected function keyFromValue(mixed $item, string $key): string
    {
        return $key;
    }

    /**
     * @param array<array-key, T> $items
     *
     * @return array<string, T> $items
     */
    private function normalizeKeys(array $items): array
    {
        $input = [];
        foreach ($items as $key => $item) {
            $newKey = $this->keyFromValue($item, strval($key));
            $input[$newKey] = $item;
        }

        return $input;
    }
}
