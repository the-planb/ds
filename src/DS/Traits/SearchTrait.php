<?php

declare(strict_types=1);

namespace PlanB\DS\Traits;

/**
 * @template TKey
 * @template T
 */
trait SearchTrait
{
    /**  @use InternalTrait<TKey, T> */
    use InternalTrait;

    public function hasValue(mixed $value): bool
    {
        return in_array($value, $this->items, true);
    }

    public function contains(mixed ...$values): bool
    {
        foreach ($values as $value) {
            if (!$this->hasValue($value)) {
                return false;
            }
        }

        return true;
    }

    public function first(): mixed
    {
        $key = array_key_first($this->items);

        return !is_null($key)
            ? $this->items[$key]
            : null;
    }

    public function some(callable $condition): bool
    {
        /** @var callable(mixed, mixed): bool $condition */
        return array_any($this->items, $condition);
    }

    public function every(callable $condition): bool
    {
        /** @var callable(mixed, mixed): bool $condition */
        return array_all($this->items, $condition);
    }

    public function firstThat(callable $condition): mixed
    {
        /** @var callable(mixed, mixed): bool $condition */
        return array_find($this->items, $condition);
    }

    public function last(): mixed
    {
        $key = array_key_last($this->items);

        return $key !== null
            ? $this->items[$key]
            : null;
    }

    public function lastThat(callable $condition): mixed
    {
        foreach (array_reverse($this->items, true) as $key => $value) {
            if ($condition($value, $key)) {
                return $value;
            }
        }

        return null;
    }

    public function find(mixed $value): false|int|string
    {
        return array_search($value, $this->items, true);
    }
}
