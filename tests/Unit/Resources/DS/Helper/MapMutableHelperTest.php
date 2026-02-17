<?php

declare(strict_types=1);

namespace PlanB\Tests\Unit\Resources\DS\Helper;

use PHPUnit\Framework\TestCase;
use PlanB\DS\Map\MapMutable;

use function PlanB\Resources\DS\Helper\mapMutable;

/**
 * @internal
 *
 * @coversNothing
 */
class MapMutableHelperTest extends TestCase
{
    public function testCreatesMapMutableFromArray(): void
    {
        $result = mapMutable(['a' => 1, 'b' => 2, 'c' => 3]);

        $this->assertInstanceOf(MapMutable::class, $result);
        $this->assertSame(3, $result->count());
        $this->assertSame(['a' => 1, 'b' => 2, 'c' => 3], $result->toArray());
    }

    public function testCreatesMapMutableFromEmptyArray(): void
    {
        $result = mapMutable([]);

        $this->assertInstanceOf(MapMutable::class, $result);
        $this->assertSame(0, $result->count());
    }

    public function testCreatesMapMutableWithNormalizer(): void
    {
        $result = mapMutable(['a' => 1, 'b' => 2], fn (int $value, string $key) => $value * 10);

        $this->assertSame(['a' => 10, 'b' => 20], $result->toArray());
    }

    public function testCreatesMapMutableFromIterator(): void
    {
        $iterator = new \ArrayIterator(['a' => 1, 'b' => 2]);
        $result = mapMutable($iterator);

        $this->assertSame(['a' => 1, 'b' => 2], $result->toArray());
    }

    public function testCreatesMapMutableFromGenerator(): void
    {
        $generator = function () {
            yield 'a' => 1;

            yield 'b' => 2;
        };
        $result = mapMutable($generator());

        $this->assertSame(['a' => 1, 'b' => 2], $result->toArray());
    }
}
