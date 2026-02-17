<?php

declare(strict_types=1);

namespace PlanB\Tests\Unit\Resources\DS\Helper;

use PHPUnit\Framework\TestCase;
use PlanB\DS\Set\Set;

use function PlanB\Resources\DS\Helper\set;

/**
 * @internal
 *
 * @coversNothing
 */
class SetHelperTest extends TestCase
{
    public function testCreatesSetFromArray(): void
    {
        $result = set(['a', 'b', 'c']);

        $this->assertInstanceOf(Set::class, $result);
        $this->assertSame(3, $result->count());
        $this->assertSame(['a', 'b', 'c'], $result->toArray());
    }

    public function testCreatesSetFromEmptyArray(): void
    {
        $result = set([]);

        $this->assertInstanceOf(Set::class, $result);
        $this->assertSame(0, $result->count());
    }

    public function testCreatesSetWithNormalizer(): void
    {
        $result = set(['a', 'b', 'c'], fn (string $value) => strtoupper($value));

        $this->assertSame(['A', 'B', 'C'], $result->toArray());
    }

    public function testCreatesSetRemovesDuplicates(): void
    {
        $result = set(['a', 'b', 'a', 'c', 'b']);

        $this->assertSame(['a', 'b', 'c'], $result->toArray());
    }

    public function testCreatesSetFromIterator(): void
    {
        $iterator = new \ArrayIterator(['a', 'b', 'c']);
        $result = set($iterator);

        $this->assertSame(['a', 'b', 'c'], $result->toArray());
    }

    public function testCreatesSetFromGenerator(): void
    {
        $generator = function () {
            yield 'a';

            yield 'b';

            yield 'c';
        };
        $result = set($generator());

        $this->assertSame(['a', 'b', 'c'], $result->toArray());
    }
}
