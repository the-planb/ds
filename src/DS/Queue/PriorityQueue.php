<?php

declare(strict_types=1);

namespace PlanB\DS\Queue;

use PlanB\DS\Collection;

/**
 * @template T
 *
 * @extends Collection<int, T>
 *
 * @implements PriorityQueueInterface<T>
 *
 * @phpstan-consistent-constructor
 */
class PriorityQueue extends Collection implements PriorityQueueInterface
{
    /** @var array<int, int> */
    protected array $priorities = [];

    public static function collect(iterable $input = [], ?callable $normalizer = null): static
    {
        $items = is_array($input) ? $input : iterator_to_array($input);

        if ($normalizer !== null) {
            $keys = array_keys($items);
            $items = array_map($normalizer, $items, $keys);
        }

        $queue = new static();

        /** @var T $value */
        foreach ($items as $value) {
            $queue->items[] = $value;
            $queue->priorities[] = 0;
        }

        /** @var static<T> */
        return $queue;
    }

    public function toArray(): array
    {
        $sorted = $this->sortedItems();

        return array_column($sorted, 'value');
    }

    public function enqueue(mixed $value, int $priority = 0): static
    {
        $clone = clone $this;
        $clone->items[] = $value;
        $clone->priorities[] = $priority;

        return $clone;
    }

    public function dequeue(): mixed
    {
        if (empty($this->items)) {
            return null;
        }

        $sorted = $this->sortedItems();
        $first = array_shift($sorted);

        /** @phpstan-ignore offsetAccess.notFound */
        $index = array_search($first['value'], $this->items, true);
        if ($index !== false) {
            unset($this->items[$index], $this->priorities[$index]);

            $this->items = array_values($this->items);
            $this->priorities = array_values($this->priorities);
        }

        /** @phpstan-ignore offsetAccess.notFound */
        return $first['value'];
    }

    public function peek(mixed $default = null): mixed
    {
        if (empty($this->items)) {
            return func_num_args() === 1 ? $default : null;
        }

        $sorted = $this->sortedItems();

        return $sorted[0]['value'];
    }

    /**
     * @return array<int, array{value: mixed, priority: int}>
     */
    protected function sortedItems(): array
    {
        $combined = [];
        foreach ($this->items as $index => $value) {
            $combined[] = [
                'value' => $value,
                'priority' => $this->priorities[$index] ?? 0,
            ];
        }

        usort($combined, fn ($a, $b) => $b['priority'] - $a['priority']);

        return $combined;
    }
}
