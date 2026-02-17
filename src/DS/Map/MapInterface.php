<?php

declare(strict_types=1);

namespace PlanB\DS\Map;

use PlanB\DS\CollectionInterface;
use PlanB\DS\Vector\Vector;

/**
 * @template T
 *
 * @extends CollectionInterface<string, T>
 */
interface MapInterface extends CollectionInterface
{
    /**
     * Transforms each element using the provided callback.
     *
     * @template TValue
     *
     * @param callable(T, string): TValue $callback
     *
     * @return Map<TValue>
     */
    public function map(callable $callback): Map;

    /**
     * Returns the element at the specified key.
     *
     * @param T $default
     *
     * @return T
     */
    public function get(string $key, mixed $default = null): mixed;

    /**
     * Checks whether the collection has a value at the specified key.
     */
    public function hasKey(string $key): bool;

    /**
     * Returns all keys in the collection.
     *
     * @return Vector<string>
     */
    public function keys(): Vector;

    /**
     * Returns all values in the collection.
     *
     * @return Vector<T>
     */
    public function values(): Vector;

    /**
     * Returns a new collection with keys transformed by the callback.
     *
     * @param callable(mixed, string): array-key $callback
     */
    public function mapKeys(callable $callback): static;

    /**
     * Returns a new collection merged with the input.
     *
     * @param iterable<string, T> $input
     */
    public function merge(iterable $input): static;

    /**
     * Sorts the collection by keys.
     *
     * @param null|callable(string, string): int $comparison
     */
    public function keySort(?callable $comparison = null): static;

    /**
     * Returns a new collection with keys not present in the input.
     *
     * @param iterable<array-key, mixed>                                             $input
     * @param null|callable(int|string, int|string): int|callable(mixed, mixed): int $comparison
     */
    public function diffKeys(iterable $input, $comparison = null): static;

    /**
     * Returns a new collection with values present in both collections.
     *
     * @param iterable<array-key, T>   $input
     * @param null|callable(T, T): int $comparison
     */
    public function intersect(iterable $input, ?callable $comparison = null): static;

    /**
     * Returns a new collection with keys present in both collections.
     *
     * @param iterable<array-key, mixed>                                             $input
     * @param null|callable(int|string, int|string): int|callable(mixed, mixed): int $comparison
     */
    public function intersectKeys(iterable $input, $comparison = null): static;
}
