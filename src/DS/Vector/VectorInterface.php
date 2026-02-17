<?php

declare(strict_types=1);

namespace PlanB\DS\Vector;

use PlanB\DS\CollectionInterface;

/**
 * @template T
 *
 * @extends CollectionInterface<int, T>
 */
interface VectorInterface extends CollectionInterface
{
    /**
     * Transforms each element using the provided callback.
     *
     * @template TValue
     *
     * @param callable(T, int): TValue $callback
     *
     * @return Vector<TValue>
     */
    public function map(callable $callback): Vector;

    /**
     * Returns the element at the specified index.
     */
    public function get(int $index, mixed $default = null): mixed;

    /**
     * Checks whether the collection has a value at the specified index.
     */
    public function hasKey(int $index): bool;

    /**
     * Checks whether the collection has a value at the specified index.
     * Alias of hasKey.
     */
    public function hasIndex(int $index): bool;
}
