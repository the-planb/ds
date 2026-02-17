<?php

declare(strict_types=1);

namespace PlanB\DS\Traits;

/**
 * @template TKey
 * @template T
 */
trait SliceTrait
{
    /**  @use InternalTrait<TKey, T> */
    use InternalTrait;

    public function take(int $limit): static
    {
        return static::collect(array_slice($this->items, 0, $limit, true));
    }

    public function drop(int $limit): static
    {
        return static::collect(array_slice($this->items, $limit, null, true));
    }

    public function takeWhile(callable $condition): static
    {
        $items = [];
        foreach ($this->items as $key => $value) {
            if (!$condition($value, $key)) {
                break;
            }
            $items[$key] = $value;
        }

        return static::collect($items);
    }

    public function dropWhile(callable $condition): static
    {
        $items = [];
        $dropping = true;
        foreach ($this->items as $key => $value) {
            if ($dropping && $condition($value, $key)) {
                continue;
            }
            $dropping = false;
            $items[$key] = $value;
        }

        return static::collect($items);
    }
}
