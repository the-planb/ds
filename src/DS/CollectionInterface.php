<?php

declare(strict_types=1);

namespace PlanB\DS;

use IteratorAggregate;
use JsonSerializable;
use PlanB\DS\Vector\Vector;

/**
 * @template TKey of array-key
 * @template T
 *
 * @extends \IteratorAggregate<TKey, T>
 */
interface CollectionInterface extends \Countable, IteratorAggregate, JsonSerializable
{
    /**
     * Returns debug information about the collection.
     */
    public function __debugInfo(): array;

    // ===== InternalTrait =====
    /**
     * Creates a new collection from an iterable input.
     *
     * @template TInput
     *
     * @param iterable<array-key, TInput>    $input
     * @param null|callable(TInput, TKey): T $normalizer
     *
     * @return static<TKey,T>
     */
    public static function collect(iterable $input = [], ?callable $normalizer = null): static;

    // ===== CollectionTrait =====

    /**
     * Returns the number of elements in the collection.
     */
    public function count(): int;

    /**
     * Checks whether the collection is empty.
     */
    public function isEmpty(): bool;

    /**
     * Checks whether the collection is not empty.
     */
    public function isNotEmpty(): bool;

    /**
     * Checks whether the collection has exactly the specified number of elements.
     */
    public function hasCount(int $total): bool;

    /**
     * Executes a callback for each element in the collection.
     *
     * @param callable(T, TKey): T $callback
     *
     * @return static<TKey,T>
     */
    public function each(callable $callback): static;

    /**
     * Returns the collection data for JSON serialization.
     *
     * @return array<TKey, T>
     */
    public function jsonSerialize(): array;

    /**
     * Converts the collection to an array.
     *
     * @return array<TKey, T>
     */
    public function toArray(): array;

    // ===== SearchTrait =====

    /**
     * Checks whether the collection contains a specific value.
     *
     * @param T $value
     */
    public function hasValue(mixed $value): bool;

    /**
     * Checks whether the collection contains all specified values.
     *
     * @param T ...$values
     */
    public function contains(mixed ...$values): bool;

    /**
     * Returns the first element of the collection.
     *
     * @return null|T
     */
    public function first(): mixed;

    /**
     * @param callable(T, TKey): bool $condition
     */
    public function some(callable $condition): bool;

    /**
     * @param callable(T, TKey): bool $condition
     */
    public function every(callable $condition): bool;

    /**
     * Returns the first element that matches the condition.
     *
     * @param callable(T, TKey): bool $condition
     *
     * @return null|T
     */
    public function firstThat(callable $condition): mixed;

    /**
     * Returns the last element of the collection.
     *
     * @return null|T
     */
    public function last(): mixed;

    /**
     * Returns the last element that matches the condition.
     *
     * @param callable(T, TKey): bool $condition
     *
     * @return null|T
     */
    public function lastThat(callable $condition): mixed;

    /**
     * Finds the key of a value in the collection.
     *
     * @param T $value
     */
    public function find(mixed $value): false|int|string;

    // ===== TransformTrait =====
    /**
     * Filters elements based on a condition.
     *
     * @param null|(callable(T, TKey): bool) $condition
     *
     * @return static<TKey,T>
     */
    public function filter(?callable $condition = null): static;

    /**
     * Sorts the collection elements.
     *
     * @param null|callable(T, TKey): int $comparator
     *
     * @return static<TKey,T>
     */
    public function sort(?callable $comparator = null): static;

    /**
     * Returns a new collection with elements in reverse order.
     *
     * @return static<TKey,T>
     */
    public function reversed(): static;

    /**
     * Returns a new collection with elements in random order.
     *
     * @return static<TKey,T>
     */
    public function shuffle(): static;

    /**
     * Returns a new collection with duplicate values removed.
     *
     * @return static<TKey,T>
     */
    public function unique(): static;

    /**
     * Flattens nested collections to the specified depth.
     *
     * @return Vector<mixed>
     */
    public function flatten(int $depth = 1): Vector;

    /**
     * Transforms and flattens the collection in one operation.
     *
     * @param callable(T, TKey): iterable<array-key, T> $callback
     *
     * @return Vector<mixed>
     */
    public function flatMap(callable $callback): Vector;

    /**
     * Reduces the collection to a single value.
     *
     * @template TValue
     *
     * @param callable(TValue, T, TKey): TValue $callback
     *
     * @return TValue
     */
    public function reduce(callable $callback, mixed $initial = null): mixed;

    /**
     * Returns a new collection with elements not present in the input.
     *
     * @param iterable<mixed, T>       $input
     * @param null|callable(T, T): int $comparison
     *
     * @return static<TKey,T>
     */
    public function diff(iterable $input, ?callable $comparison = null): static;

    // ===== SliceTrait =====

    /**
     * Returns the first N elements of the collection.
     *
     * @return static<TKey,T>
     */
    public function take(int $limit): static;

    /**
     * Returns the collection without the first N elements.
     *
     * @return static<TKey,T>
     */
    public function drop(int $limit): static;

    /**
     * Returns elements from the start while the condition is true.
     *
     * @param callable(T, TKey): bool $condition
     *
     * @return static<TKey,T>
     */
    public function takeWhile(callable $condition): static;

    /**
     * Returns elements after dropping from the start while the condition is true.
     *
     * @param callable(T, TKey): bool $condition
     *
     * @return static<TKey,T>
     */
    public function dropWhile(callable $condition): static;
}
