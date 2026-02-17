<?php

declare(strict_types=1);

namespace PlanB\DS\Vector;

/**
 * @template T
 *
 * @extends VectorInterface<T>
 */
interface VectorMutableInterface extends VectorInterface
{
    /**
     * Adds a value to the end of the vector.
     *
     * @param T $value
     */
    public function add(mixed $value): static;

    /**
     * Adds all values from an iterable to the end of the vector.
     *
     * @param iterable<array-key, T> $input
     */
    public function addAll(iterable $input): static;

    /**
     * Inserts values at the specified index.
     *
     * @param T ...$values
     */
    public function insert(int $index, mixed ...$values): static;

    /**
     * Sets the value at the specified index.
     *
     * @param T $value
     */
    public function set(int $index, mixed $value): static;

    /**
     * Removes the value at the specified index.
     */
    public function remove(int $index): static;

    /**
     * Removes the first occurrence of a value.
     *
     * @param T $value
     */
    public function removeValue(mixed $value): static;
}
