<?php

declare(strict_types=1);

namespace PlanB\DS\Set;

use PlanB\DS\Collection;

/**
 * @template T
 *
 * @extends Collection<int, T>
 *
 * @implements SetInterface<T>
 *
 * @phpstan-consistent-constructor
 */
class Set extends Collection implements SetInterface
{
    public static function collect(iterable $input = [], ?callable $normalizer = null): static
    {
        $items = is_array($input) ? $input : iterator_to_array($input);

        if ($normalizer !== null) {
            $keys = array_keys($items);
            $items = array_map($normalizer, $items, $keys);
        }

        $items = array_values(array_unique($items, SORT_REGULAR));

        /** @var static<T> */
        return new static(...$items);
    }

    /**
     * @param Set<T> $other
     */
    public function union(Set $other): static
    {
        return static::collect([...$this->items, ...$other->toArray()]);
    }

    /**
     * @param SetInterface<T> $other
     */
    public function intersect(SetInterface $other): static
    {
        /** @var static<T> */
        return $this->filter(fn (mixed $value) => $other->hasValue($value));
    }
}
