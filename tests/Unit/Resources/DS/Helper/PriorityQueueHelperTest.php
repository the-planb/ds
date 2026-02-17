<?php

declare(strict_types=1);

namespace PlanB\Tests\Unit\Resources\DS\Helper;

use PHPUnit\Framework\TestCase;
use PlanB\DS\Queue\PriorityQueue;

use function PlanB\Resources\DS\Helper\priorityQueue;

/**
 * @internal
 *
 * @coversNothing
 */
class PriorityQueueHelperTest extends TestCase
{
    public function testCreatesPriorityQueueFromArray(): void
    {
        $result = priorityQueue(['a', 'b', 'c']);

        $this->assertInstanceOf(PriorityQueue::class, $result);
        $this->assertSame(3, $result->count());
        $this->assertSame(['a', 'b', 'c'], $result->toArray());
    }

    public function testCreatesPriorityQueueFromEmptyArray(): void
    {
        $result = priorityQueue([]);

        $this->assertInstanceOf(PriorityQueue::class, $result);
        $this->assertSame(0, $result->count());
    }

    public function testCreatesPriorityQueueWithNormalizer(): void
    {
        $result = priorityQueue(['a', 'b', 'c'], fn (string $value) => strtoupper($value));

        $this->assertSame(['A', 'B', 'C'], $result->toArray());
    }

    public function testCreatesPriorityQueueFromIterator(): void
    {
        $iterator = new \ArrayIterator(['a', 'b', 'c']);
        $result = priorityQueue($iterator);

        $this->assertSame(['a', 'b', 'c'], $result->toArray());
    }

    public function testCreatesPriorityQueueFromGenerator(): void
    {
        $generator = function () {
            yield 'a';

            yield 'b';

            yield 'c';
        };
        $result = priorityQueue($generator());

        $this->assertSame(['a', 'b', 'c'], $result->toArray());
    }
}
