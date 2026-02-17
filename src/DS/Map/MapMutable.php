<?php

declare(strict_types=1);

namespace PlanB\DS\Map;

/**
 * @template T
 *
 * @extends Map<T>
 *
 * @implements MapMutableInterface<T>
 */
class MapMutable extends Map implements MapMutableInterface
{
    public function put(string $key, mixed $value): static
    {
        $this->items[$key] = $value;

        return $this;
    }

    public function putAll(iterable $input): static
    {
        foreach ($input as $key => $value) {
            /** @var string $key */
            $this->items[$key] = $value;
        }

        return $this;
    }

    public function remove(string $key): static
    {
        unset($this->items[$key]);

        return $this;
    }

    public function removeValue(mixed $value): static
    {
        foreach ($this->items as $key => $item) {
            if ($item === $value) {
                unset($this->items[$key]);

                break;
            }
        }

        return $this;
    }
}
