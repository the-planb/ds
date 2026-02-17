<?php

declare(strict_types=1);

namespace PlanB\DS\Set;

/**
 * @template T
 *
 * @extends Set<T>
 *
 * @implements SetMutableInterface<T>
 *
 * @phpstan-consistent-constructor
 */
class SetMutable extends Set implements SetMutableInterface
{
    public function add(mixed $value): static
    {
        if (!$this->hasValue($value)) {
            $this->items[] = $value;
        }

        return $this;
    }

    public function addAll(iterable $input): static
    {
        foreach ($input as $value) {
            $this->add($value);
        }

        return $this;
    }

    public function remove(mixed $value): static
    {
        $this->items = array_values(array_filter(
            $this->items,
            fn (mixed $item) => $item !== $value,
        ));

        return $this;
    }

    public function clear(): static
    {
        $this->items = [];

        return $this;
    }
}
