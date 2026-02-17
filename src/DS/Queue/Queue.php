<?php

declare(strict_types=1);

namespace PlanB\DS\Queue;

use PlanB\DS\Collection;

/**
 * @template T
 *
 * @extends Collection<int, T>
 *
 * @implements QueueInterface<T>
 *
 * @phpstan-consistent-constructor
 */
class Queue extends Collection implements QueueInterface
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

    public function enqueue(mixed $value): static
    {
        $clone = clone $this;
        $clone->items[] = $value;

        return $clone;
    }

    public function dequeue(): mixed
    {
        return array_shift($this->items);
    }

    public function peek(mixed $default = null): mixed
    {
        if (func_num_args() === 1) {
            return $this->items[0] ?? $default;
        }

        return $this->items[0] ?? null;
    }
}
