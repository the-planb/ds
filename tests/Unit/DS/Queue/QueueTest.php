<?php

declare(strict_types=1);

namespace PlanB\Tests\Unit\DS\Queue;

use PHPUnit\Framework\TestCase;
use PlanB\DS\Queue\Queue;

/**
 * @internal
 *
 * @coversNothing
 */
class QueueTest extends TestCase
{
    public function testCollectCreatesInstanceFromArray(): void
    {
        $queue = $this->createQueue(['a', 'b', 'c']);

        $this->assertInstanceOf(Queue::class, $queue);
        $this->assertSame(3, $queue->count());
    }

    public function testCollectWithNormalizer(): void
    {
        $queue = Queue::collect(
            ['a', 'b', 'c'],
            fn (string $value) => strtoupper($value),
        );

        $this->assertInstanceOf(Queue::class, $queue);
        $this->assertSame(['A', 'B', 'C'], $queue->toArray());
    }

    public function testEnqueueAddsElementToBack(): void
    {
        $queue = $this->createQueue(['a', 'b']);
        $result = $queue->enqueue('c');

        $this->assertSame(2, $queue->count());
        $this->assertSame('a', $queue->peek());
        $this->assertSame(3, $result->count());
        $this->assertInstanceOf(Queue::class, $result);
    }

    public function testDequeueRemovesElementFromFront(): void
    {
        $queue = $this->createQueue(['a', 'b', 'c']);

        $result = $queue->dequeue();

        $this->assertSame('a', $result);
        $this->assertSame(2, $queue->count());
    }

    public function testDequeueReturnsNullWhenEmpty(): void
    {
        $queue = $this->createQueue([]);

        $result = $queue->dequeue();

        $this->assertNull($result);
    }

    public function testPeekReturnsFrontElement(): void
    {
        $queue = $this->createQueue(['a', 'b', 'c']);

        $result = $queue->peek();

        $this->assertSame('a', $result);
        $this->assertSame(3, $queue->count());
    }

    public function testPeekReturnsDefaultWhenEmpty(): void
    {
        $queue = $this->createQueue([]);

        $this->assertSame('default', $queue->peek('default'));
        $this->assertNull($queue->peek(null));
    }

    public function testEnqueueReturnsNewInstance(): void
    {
        $queue = $this->createQueue(['a']);
        $newQueue = $queue->enqueue('b');

        $this->assertNotSame($queue, $newQueue);
        $this->assertSame(1, $queue->count());
        $this->assertSame(2, $newQueue->count());
    }

    public function testFifoOrder(): void
    {
        $queue = $this->createQueue([]);
        $queue = $queue->enqueue(1)->enqueue(2)->enqueue(3);

        $this->assertSame(1, $queue->dequeue());
        $this->assertSame(2, $queue->dequeue());
        $this->assertSame(3, $queue->dequeue());
    }

    private function createQueue(array $items): Queue
    {
        return Queue::collect($items);
    }
}
