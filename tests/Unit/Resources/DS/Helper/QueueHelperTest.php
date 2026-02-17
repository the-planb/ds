<?php

declare(strict_types=1);

namespace PlanB\Tests\Unit\Resources\DS\Helper;

use PHPUnit\Framework\TestCase;
use PlanB\DS\Queue\Queue;

use function PlanB\Resources\DS\Helper\queue;

/**
 * @internal
 *
 * @coversNothing
 */
class QueueHelperTest extends TestCase
{
    public function testCreatesQueueFromArray(): void
    {
        $result = queue(['a', 'b', 'c']);

        $this->assertInstanceOf(Queue::class, $result);
        $this->assertSame(3, $result->count());
        $this->assertSame(['a', 'b', 'c'], $result->toArray());
    }

    public function testCreatesQueueFromEmptyArray(): void
    {
        $result = queue([]);

        $this->assertInstanceOf(Queue::class, $result);
        $this->assertSame(0, $result->count());
    }

    public function testCreatesQueueWithNormalizer(): void
    {
        $result = queue(['a', 'b', 'c'], fn (string $value) => strtoupper($value));

        $this->assertSame(['A', 'B', 'C'], $result->toArray());
    }

    public function testCreatesQueueFromIterator(): void
    {
        $iterator = new \ArrayIterator(['a', 'b', 'c']);
        $result = queue($iterator);

        $this->assertSame(['a', 'b', 'c'], $result->toArray());
    }

    public function testCreatesQueueFromGenerator(): void
    {
        $generator = function () {
            yield 'a';

            yield 'b';

            yield 'c';
        };
        $result = queue($generator());

        $this->assertSame(['a', 'b', 'c'], $result->toArray());
    }
}
