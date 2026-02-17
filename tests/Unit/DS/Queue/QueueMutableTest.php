<?php

declare(strict_types=1);

namespace PlanB\Tests\Unit\DS\Queue;

use PHPUnit\Framework\TestCase;
use PlanB\DS\Queue\QueueMutable;

/**
 * @internal
 *
 * @coversNothing
 */
class QueueMutableTest extends TestCase
{
    public function testCollectCreatesInstanceFromArray(): void
    {
        $queue = $this->createQueue(['a', 'b', 'c']);

        $this->assertInstanceOf(QueueMutable::class, $queue);
        $this->assertSame(3, $queue->count());
    }

    public function testPushAddsElementToBack(): void
    {
        $queue = $this->createQueue(['a', 'b']);
        $result = $queue->push('c');

        $this->assertSame(3, $queue->count());
        $this->assertSame('a', $queue->peek());
        $this->assertSame($queue, $result);
    }

    public function testShiftRemovesElementFromFront(): void
    {
        $queue = $this->createQueue(['a', 'b', 'c']);

        $result = $queue->shift();

        $this->assertSame('a', $result);
        $this->assertSame(2, $queue->count());
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
        $queue->push(1)->push(2)->push(3);

        $this->assertSame(1, $queue->shift());
        $this->assertSame(2, $queue->shift());
        $this->assertSame(3, $queue->shift());
    }

    public function testEnqueueAlsoWorks(): void
    {
        $queue = $this->createQueue([]);
        $queue->enqueue('a')->enqueue('b');

        $this->assertSame(2, $queue->count());
        $this->assertSame('a', $queue->peek());
    }

    private function createQueue(array $items): QueueMutable
    {
        return QueueMutable::collect($items);
    }
}
