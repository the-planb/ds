<?php

declare(strict_types=1);

namespace PlanB\Tests\Unit\Resources\DS\Helper;

use PHPUnit\Framework\TestCase;
use PlanB\DS\Vector\VectorMutable;

use function PlanB\Resources\DS\Helper\vectorMutable;

/**
 * @internal
 *
 * @coversNothing
 */
class VectorMutableHelperTest extends TestCase
{
    public function testCreatesVectorMutableFromArray(): void
    {
        $result = vectorMutable(['a', 'b', 'c']);

        $this->assertInstanceOf(VectorMutable::class, $result);
        $this->assertSame(3, $result->count());
        $this->assertSame(['a', 'b', 'c'], $result->toArray());
    }

    public function testCreatesVectorMutableFromEmptyArray(): void
    {
        $result = vectorMutable([]);

        $this->assertInstanceOf(VectorMutable::class, $result);
        $this->assertSame(0, $result->count());
    }

    public function testCreatesVectorMutableWithNormalizer(): void
    {
        $result = vectorMutable(['a', 'b', 'c'], fn (string $value) => strtoupper($value));

        $this->assertSame(['A', 'B', 'C'], $result->toArray());
    }

    public function testCreatesVectorMutableFromIterator(): void
    {
        $iterator = new \ArrayIterator(['a', 'b', 'c']);
        $result = vectorMutable($iterator);

        $this->assertSame(['a', 'b', 'c'], $result->toArray());
    }

    public function testCreatesVectorMutableFromGenerator(): void
    {
        $generator = function () {
            yield 'a';

            yield 'b';

            yield 'c';
        };
        $result = vectorMutable($generator());

        $this->assertSame(['a', 'b', 'c'], $result->toArray());
    }
}
