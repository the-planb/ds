<?php

declare(strict_types=1);

namespace PlanB\DS\Queue;

/**
 * @template T
 *
 * @extends PriorityQueueInterface<T>
 */
interface PriorityQueueMutableInterface extends PriorityQueueInterface
{
    /**
     * Adds an element with priority to the queue.
     * Alias of enqueue.
     * Higher priority values are dequeued first.
     */
    public function push(mixed $value, int $priority = 0): static;

    /**
     * Removes and returns the element with highest priority.
     * Alias of dequeue.
     */
    public function shift(): mixed;
}
