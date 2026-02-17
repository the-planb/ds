<?php

declare(strict_types=1);

namespace PlanB\DS\Queue;

/**
 * @template T
 *
 * @extends Queue<T>
 *
 * @implements QueueMutableInterface<T>
 */
class QueueMutable extends Queue implements QueueMutableInterface
{
    public function enqueue(mixed $value): static
    {
        $this->items[] = $value;

        return $this;
    }

    public function push(mixed $value): static
    {
        $this->items[] = $value;

        return $this;
    }

    public function shift(): mixed
    {
        return array_shift($this->items);
    }
}
