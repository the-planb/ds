<?php

declare(strict_types=1);

namespace PlanB\Tests\Unit\Resources\DS\Helper;

use PHPUnit\Framework\TestCase;
use PlanB\DS\Queue\PriorityQueueMutable;

use function PlanB\Resources\DS\Helper\priorityQueueMutable;

/**
 * @internal
 *
 * @coversNothing
 */
class PriorityQueueMutableHelperTest extends TestCase
{
    public function testCreatesPriorityQueueMutableFromArray(): void
    {
        $result = priorityQueueMutable(['a', 'b', 'c']);

        $this->assertInstanceOf(PriorityQueueMutable::class, $result);
        $this->assertSame(3, $result->count());
        $this->assertSame(['a', 'b', 'c'], $result->toArray());
    }

    public function testCreatesPriorityQueueMutableFromEmptyArray(): void
    {
        $result = priorityQueueMutable([]);

        $this->assertInstanceOf(PriorityQueueMutable::class, $result);
        $this->assertSame(0, $result->count());
    }

    public function testCreatesPriorityQueueMutableWithNormalizer(): void
    {
        $result = priorityQueueMutable(['a', 'b', 'c'], fn (string $value) => strtoupper($value));

        $this->assertSame(['A', 'B', 'C'], $result->toArray());
    }

    public function testCreatesPriorityQueueMutableFromIterator(): void
    {
        $iterator = new \ArrayIterator(['a', 'b', 'c']);
        $result = priorityQueueMutable($iterator);

        $this->assertSame(['a', 'b', 'c'], $result->toArray());
    }

    public function testCreatesPriorityQueueMutableFromGenerator(): void
    {
        $generator = function () {
            yield 'a';

            yield 'b';

            yield 'c';
        };
        $result = priorityQueueMutable($generator());

        $this->assertSame(['a', 'b', 'c'], $result->toArray());
    }
}
