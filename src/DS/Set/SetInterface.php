<?php

declare(strict_types=1);

namespace PlanB\DS\Set;

use PlanB\DS\CollectionInterface;

/**
 * @template T
 *
 * @extends CollectionInterface<int, T>
 */
interface SetInterface extends CollectionInterface
{
    /**
     * @param Set<T> $other
     *
     * @return static<T>
     */
    public function union(Set $other): static;

    /**
     * @param Set<T> $other
     *
     * @return static<T>
     */
    public function intersect(Set $other): static;
}
