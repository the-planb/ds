<?php

declare(strict_types=1);

namespace PlanB\DS\Vector;

/**
 * @template T
 *
 * @extends Vector<T>
 *
 * @implements VectorMutableInterface<T>
 */
class VectorMutable extends Vector implements VectorMutableInterface
{
    public function add(mixed $value): static
    {
        $this->items[] = $value;

        return $this;
    }

    public function addAll(iterable $input): static
    {
        foreach ($input as $value) {
            $this->items[] = $value;
        }

        return $this;
    }

    public function insert(int $index, mixed ...$values): static
    {
        array_splice($this->items, $index, 0, $values);

        return $this;
    }

    public function set(int $index, mixed $value): static
    {
        $this->items[$index] = $value;

        return $this;
    }

    public function remove(int $index): static
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);

        return $this;
    }

    public function removeValue(mixed $value): static
    {
        foreach ($this->items as $index => $item) {
            if ($item === $value) {
                unset($this->items[$index]);
                $this->items = array_values($this->items);

                break;
            }
        }

        return $this;
    }
}
