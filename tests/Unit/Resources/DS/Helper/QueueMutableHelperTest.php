<?php

declare(strict_types=1);

namespace PlanB\Tests\Unit\Resources\DS\Helper;

use PHPUnit\Framework\TestCase;
use PlanB\DS\Queue\QueueMutable;

use function PlanB\Resources\DS\Helper\queueMutable;

/**
 * @internal
 *
 * @coversNothing
 */
class QueueMutableHelperTest extends TestCase
{
    public function testCreatesQueueMutableFromArray(): void
    {
        $result = queueMutable(['a', 'b', 'c']);

        $this->assertInstanceOf(QueueMutable::class, $result);
        $this->assertSame(3, $result->count());
        $this->assertSame(['a', 'b', 'c'], $result->toArray());
    }

    public function testCreatesQueueMutableFromEmptyArray(): void
    {
        $result = queueMutable([]);

        $this->assertInstanceOf(QueueMutable::class, $result);
        $this->assertSame(0, $result->count());
    }

    public function testCreatesQueueMutableWithNormalizer(): void
    {
        $result = queueMutable(['a', 'b', 'c'], fn (string $value) => strtoupper($value));

        $this->assertSame(['A', 'B', 'C'], $result->toArray());
    }

    public function testCreatesQueueMutableFromIterator(): void
    {
        $iterator = new \ArrayIterator(['a', 'b', 'c']);
        $result = queueMutable($iterator);

        $this->assertSame(['a', 'b', 'c'], $result->toArray());
    }

    public function testCreatesQueueMutableFromGenerator(): void
    {
        $generator = function () {
            yield 'a';

            yield 'b';

            yield 'c';
        };
        $result = queueMutable($generator());

        $this->assertSame(['a', 'b', 'c'], $result->toArray());
    }
}
