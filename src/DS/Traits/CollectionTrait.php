<?php

declare(strict_types=1);

namespace PlanB\DS\Traits;

/**
 * @template TKey
 * @template T
 *
 * @use InternalTrait<TKey, T>
 */
trait CollectionTrait
{
    /**  @use InternalTrait<TKey, T> */
    use InternalTrait;

    public function __debugInfo(): array
    {
        return [
            'items' => $this->toArray(),
            'total' => $this->count(),
        ];
    }

    public function count(): int
    {
        return count($this->items);
    }

    public function hasCount(int $total): bool
    {
        return $this->count() === $total;
    }

    public function isEmpty(): bool
    {
        return $this->count() === 0;
    }

    public function isNotEmpty(): bool
    {
        return !$this->isEmpty();
    }

    public function each(callable $callback): static
    {
        foreach ($this->items as $key => $value) {
            $callback($value, $key);
        }

        /** @var static<TKey, T> */
        return $this;
    }

    public function jsonSerialize(): array
    {
        return $this->items;
    }

    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->items);
    }
}
