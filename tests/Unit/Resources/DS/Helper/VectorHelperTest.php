<?php

declare(strict_types=1);

namespace PlanB\Tests\Unit\Resources\DS\Helper;

use PHPUnit\Framework\TestCase;
use PlanB\DS\Vector\Vector;

use function PlanB\Resources\DS\Helper\vector;

/**
 * @internal
 *
 * @coversNothing
 */
class VectorHelperTest extends TestCase
{
    public function testCreatesVectorFromArray(): void
    {
        $result = vector(['a', 'b', 'c']);

        $this->assertInstanceOf(Vector::class, $result);
        $this->assertSame(3, $result->count());
        $this->assertSame(['a', 'b', 'c'], $result->toArray());
    }

    public function testCreatesVectorFromEmptyArray(): void
    {
        $result = vector([]);

        $this->assertInstanceOf(Vector::class, $result);
        $this->assertSame(0, $result->count());
    }

    public function testCreatesVectorWithNormalizer(): void
    {
        $result = vector(['a', 'b', 'c'], fn (string $value) => strtoupper($value));

        $this->assertSame(['A', 'B', 'C'], $result->toArray());
    }

    public function testCreatesVectorFromIterator(): void
    {
        $iterator = new \ArrayIterator(['a', 'b', 'c']);
        $result = vector($iterator);

        $this->assertSame(['a', 'b', 'c'], $result->toArray());
    }

    public function testCreatesVectorFromGenerator(): void
    {
        $generator = function () {
            yield 'a';

            yield 'b';

            yield 'c';
        };
        $result = vector($generator());

        $this->assertSame(['a', 'b', 'c'], $result->toArray());
    }
}
