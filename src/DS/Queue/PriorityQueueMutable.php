<?php

declare(strict_types=1);

namespace PlanB\DS\Queue;

/**
 * @template T
 *
 * @extends PriorityQueue<T>
 *
 * @implements PriorityQueueMutableInterface<T>
 */
class PriorityQueueMutable extends PriorityQueue implements PriorityQueueMutableInterface
{
    public function enqueue(mixed $value, int $priority = 0): static
    {
        $this->items[] = $value;
        $this->priorities[] = $priority;

        return $this;
    }

    public function push(mixed $value, int $priority = 0): static
    {
        $this->items[] = $value;
        $this->priorities[] = $priority;

        return $this;
    }

    public function shift(): mixed
    {
        if (empty($this->items)) {
            return null;
        }

        $sorted = $this->sortedItems();
        $first = array_shift($sorted);

        /** @phpstan-ignore offsetAccess.notFound */
        $index = array_search($first['value'], $this->items, true);
        if ($index !== false) {
            unset($this->items[$index], $this->priorities[$index]);

            $this->items = array_values($this->items);
            $this->priorities = array_values($this->priorities);
        }

        /** @phpstan-ignore offsetAccess.notFound */
        return $first['value'];
    }
}
