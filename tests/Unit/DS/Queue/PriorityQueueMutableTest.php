<?php

declare(strict_types=1);

namespace PlanB\Tests\Unit\DS\Queue;

use PHPUnit\Framework\TestCase;
use PlanB\DS\Queue\PriorityQueueMutable;

/**
 * @internal
 *
 * @coversNothing
 */
class PriorityQueueMutableTest extends TestCase
{
    public function testCollectCreatesInstanceFromArray(): void
    {
        $queue = $this->createQueue(['a', 'b', 'c']);

        $this->assertInstanceOf(PriorityQueueMutable::class, $queue);
        $this->assertSame(3, $queue->count());
    }

    public function testPushAddsElementWithPriority(): void
    {
        $queue = $this->createQueue([]);
        $result = $queue->push('low', 1)->push('high', 10)->push('medium', 5);

        $this->assertSame(3, $queue->count());
        $this->assertSame('high', $queue->peek());
        $this->assertSame($queue, $result);
    }

    public function testShiftRemovesHighestPriority(): void
    {
        $queue = $this->createQueue([]);
        $queue->push('low', 1)->push('high', 10)->push('medium', 5);

        $this->assertSame('high', $queue->shift());
        $this->assertSame('medium', $queue->shift());
        $this->assertSame('low', $queue->shift());
    }

    public function testShiftReturnsNullWhenEmpty(): void
    {
        $queue = $this->createQueue([]);

        $result = $queue->shift();

        $this->assertNull($result);
    }

    public function testMutationWorks(): void
    {
        $queue = $this->createQueue([]);
        $queue->push(1, 3)->push(2, 1)->push(3, 2);

        $this->assertSame(1, $queue->shift());
        $this->assertSame(3, $queue->shift());
        $this->assertSame(2, $queue->shift());
    }

    public function testEnqueueAlsoWorks(): void
    {
        $queue = $this->createQueue([]);
        $queue->enqueue('a', 5)->enqueue('b', 10);

        $this->assertSame(2, $queue->count());
        $this->assertSame('b', $queue->peek());
    }

    private function createQueue(array $items): PriorityQueueMutable
    {
        return PriorityQueueMutable::collect($items);
    }
}
