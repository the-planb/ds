<?php

declare(strict_types=1);

namespace PlanB\DS\Queue;

/**
 * @template T
 *
 * @extends QueueInterface<T>
 */
interface QueueMutableInterface extends QueueInterface
{
    /**
     * Adds an element to the back of the queue.
     * Alias of enqueue.
     */
    public function push(mixed $value): static;

    /**
     * Removes and returns the element at the front of the queue.
     * Alias of dequeue.
     */
    public function shift(): mixed;
}
