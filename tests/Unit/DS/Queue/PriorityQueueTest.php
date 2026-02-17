<?php

declare(strict_types=1);

namespace PlanB\Tests\Unit\DS\Queue;

use PHPUnit\Framework\TestCase;
use PlanB\DS\Queue\PriorityQueue;

/**
 * @internal
 *
 * @coversNothing
 */
class PriorityQueueTest extends TestCase
{
    public function testCollectCreatesInstanceFromArray(): void
    {
        $queue = $this->createQueue(['a', 'b', 'c']);

        $this->assertInstanceOf(PriorityQueue::class, $queue);
        $this->assertSame(3, $queue->count());
    }

    public function testEnqueueWithDefaultPriority(): void
    {
        $queue = $this->createQueue([]);
        $result = $queue->enqueue('a');

        $this->assertSame(1, $result->count());
        $this->assertSame('a', $result->peek());
    }

    public function testEnqueueWithPriority(): void
    {
        $queue = $this->createQueue([]);
        $queue = $queue->enqueue('low', 1)->enqueue('high', 10)->enqueue('medium', 5);

        $this->assertSame('high', $queue->peek());
    }

    public function testDequeueRemovesHighestPriority(): void
    {
        $queue = $this->createQueue([]);
        $queue = $queue->enqueue('low', 1)->enqueue('high', 10)->enqueue('medium', 5);

        $this->assertSame('high', $queue->dequeue());
        $this->assertSame('medium', $queue->dequeue());
        $this->assertSame('low', $queue->dequeue());
    }

    public function testDequeueReturnsNullWhenEmpty(): void
    {
        $queue = $this->createQueue([]);

        $result = $queue->dequeue();

        $this->assertNull($result);
    }

    public function testDequeueReturnsNullAfterBecomingEmpty(): void
    {
        $queue = $this->createQueue(['a']);

        $this->assertSame('a', $queue->dequeue());
        $this->assertNull($queue->dequeue());
        $this->assertSame(0, $queue->count());
    }

    public function testPeekReturnsHighestPriority(): void
    {
        $queue = $this->createQueue([]);
        $queue = $queue->enqueue('low', 1)->enqueue('high', 10)->enqueue('medium', 5);

        $this->assertSame('high', $queue->peek());
        $this->assertSame(3, $queue->count());
    }

    public function testPeekReturnsDefaultWhenEmpty(): void
    {
        $queue = $this->createQueue([]);

        $this->assertSame('default', $queue->peek('default'));
    }

    public function testEnqueueReturnsNewInstance(): void
    {
        $queue = $this->createQueue(['a']);
        $newQueue = $queue->enqueue('b', 5);

        $this->assertNotSame($queue, $newQueue);
        $this->assertSame(1, $queue->count());
        $this->assertSame(2, $newQueue->count());
    }

    public function testSamePriorityFifoOrder(): void
    {
        $queue = $this->createQueue([]);
        $queue = $queue->enqueue('first', 5)->enqueue('second', 5)->enqueue('third', 5);

        $this->assertSame('first', $queue->dequeue());
        $this->assertSame('second', $queue->dequeue());
        $this->assertSame('third', $queue->dequeue());
    }

    public function testCollectWithNormalizer(): void
    {
        $queue = PriorityQueue::collect(
            ['a', 'b', 'c'],
            fn (string $value) => strtoupper($value),
        );

        $this->assertInstanceOf(PriorityQueue::class, $queue);
        $this->assertSame(['A', 'B', 'C'], $queue->toArray());
    }

    private function createQueue(array $items): PriorityQueue
    {
        return PriorityQueue::collect($items);
    }
}
