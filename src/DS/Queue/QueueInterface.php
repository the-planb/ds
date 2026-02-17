<?php

declare(strict_types=1);

namespace PlanB\DS\Queue;

use PlanB\DS\CollectionInterface;

/**
 * @template T
 *
 * @extends CollectionInterface<int, T>
 */
interface QueueInterface extends CollectionInterface
{
    /**
     * Adds an element to the back of the queue.
     */
    public function enqueue(mixed $value): static;

    /**
     * Removes and returns the element at the front of the queue.
     */
    public function dequeue(): mixed;

    /**
     * Returns the element at the front of the queue without removing it.
     */
    public function peek(mixed $default = null): mixed;
}
