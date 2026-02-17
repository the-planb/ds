<?php

declare(strict_types=1);

namespace PlanB\DS\Map;

/**
 * @template T
 *
 * @extends MapInterface<T>
 */
interface MapMutableInterface extends MapInterface
{
    /**
     * Puts a value with the specified key.
     *
     * @param T $value
     */
    public function put(string $key, mixed $value): static;

    /**
     * Puts all key-value pairs from an iterable.
     *
     * @param iterable<array-key, T> $input
     */
    public function putAll(iterable $input): static;

    /**
     * Removes the value at the specified key.
     */
    public function remove(string $key): static;

    /**
     * Removes the first occurrence of a value.
     *
     * @param T $value
     */
    public function removeValue(mixed $value): static;
}
