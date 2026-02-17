<?php

declare(strict_types=1);

namespace PlanB\DS\Set;

/**
 * @template T
 *
 * @extends SetInterface<T>
 */
interface SetMutableInterface extends SetInterface
{
    public function add(mixed $value): static;

    /**
     * @param iterable<mixed, T> $input
     */
    public function addAll(iterable $input): static;

    public function remove(mixed $value): static;

    public function clear(): static;
}
