<?php

declare(strict_types=1);

namespace PlanB\DS\Traits;

/**
 * @template TKey
 * @template T
 */
trait InternalTrait
{
    /** @var array<TKey, T> */
    protected array $items = [];

    /**
     * @param T ...$items
     */
    public function __construct(mixed ...$items)
    {
        /** @var array<TKey, T> $items */
        $this->items = $items;
    }

    public static function collect(iterable $input = [], ?callable $normalizer = null): static
    {
        /** @var array<TKey, T> $items */
        $items = is_array($input) ? $input : iterator_to_array($input);

        if ($normalizer !== null) {
            $keys = array_keys($items);

            /** @var callable(mixed, mixed): mixed $normalizer */
            $items = array_map($normalizer, $items, $keys);
        }

        /**  @phpstan-ignore-next-line */
        return new static(...$items);
    }

    public function toArray(): array
    {
        return $this->items;
    }
}
