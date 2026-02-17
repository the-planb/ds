<?php

declare(strict_types=1);

namespace PlanB\Tests\Unit\Resources\DS\Helper;

use PHPUnit\Framework\TestCase;
use PlanB\DS\Set\SetMutable;

use function PlanB\Resources\DS\Helper\setMutable;

/**
 * @internal
 *
 * @coversNothing
 */
class SetMutableHelperTest extends TestCase
{
    public function testCreatesSetMutableFromArray(): void
    {
        $result = setMutable(['a', 'b', 'c']);

        $this->assertInstanceOf(SetMutable::class, $result);
        $this->assertSame(3, $result->count());
        $this->assertSame(['a', 'b', 'c'], $result->toArray());
    }

    public function testCreatesSetMutableFromEmptyArray(): void
    {
        $result = setMutable([]);

        $this->assertInstanceOf(SetMutable::class, $result);
        $this->assertSame(0, $result->count());
    }

    public function testCreatesSetMutableWithNormalizer(): void
    {
        $result = setMutable(['a', 'b', 'c'], fn (string $value) => strtoupper($value));

        $this->assertSame(['A', 'B', 'C'], $result->toArray());
    }

    public function testCreatesSetMutableRemovesDuplicates(): void
    {
        $result = setMutable(['a', 'b', 'a', 'c', 'b']);

        $this->assertSame(['a', 'b', 'c'], $result->toArray());
    }

    public function testCreatesSetMutableFromIterator(): void
    {
        $iterator = new \ArrayIterator(['a', 'b', 'c']);
        $result = setMutable($iterator);

        $this->assertSame(['a', 'b', 'c'], $result->toArray());
    }

    public function testCreatesSetMutableFromGenerator(): void
    {
        $generator = function () {
            yield 'a';

            yield 'b';

            yield 'c';
        };
        $result = setMutable($generator());

        $this->assertSame(['a', 'b', 'c'], $result->toArray());
    }
}
