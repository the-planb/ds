<?php

declare(strict_types=1);

namespace PlanB\DS\Queue;

use PlanB\DS\CollectionInterface;

/**
 * @template T
 *
 * @extends CollectionInterface<int, T>
 */
interface PriorityQueueInterface extends CollectionInterface
{
    /**
     * Adds an element with priority to the queue.
     * Higher priority values are dequeued first.
     */
    public function enqueue(mixed $value, int $priority = 0): static;

    /**
     * Removes and returns the element with highest priority.
     */
    public function dequeue(): mixed;

    /**
     * Returns the element with highest priority without removing it.
     */
    public function peek(mixed $default = null): mixed;
}
