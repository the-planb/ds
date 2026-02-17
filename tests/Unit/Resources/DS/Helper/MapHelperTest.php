<?php

declare(strict_types=1);

namespace PlanB\Tests\Unit\Resources\DS\Helper;

use PHPUnit\Framework\TestCase;
use PlanB\DS\Map\Map;

use function PlanB\Resources\DS\Helper\map;

/**
 * @internal
 *
 * @coversNothing
 */
class MapHelperTest extends TestCase
{
    public function testCreatesMapFromArray(): void
    {
        $result = map(['a' => 1, 'b' => 2, 'c' => 3]);

        $this->assertInstanceOf(Map::class, $result);
        $this->assertSame(3, $result->count());
        $this->assertSame(['a' => 1, 'b' => 2, 'c' => 3], $result->toArray());
    }

    public function testCreatesMapFromEmptyArray(): void
    {
        $result = map([]);

        $this->assertInstanceOf(Map::class, $result);
        $this->assertSame(0, $result->count());
    }

    public function testCreatesMapWithNormalizer(): void
    {
        $result = map(['a' => 1, 'b' => 2], fn (int $value, string $key) => $value * 10);

        $this->assertSame(['a' => 10, 'b' => 20], $result->toArray());
    }

    public function testCreatesMapFromIterator(): void
    {
        $iterator = new \ArrayIterator(['a' => 1, 'b' => 2]);
        $result = map($iterator);

        $this->assertSame(['a' => 1, 'b' => 2], $result->toArray());
    }

    public function testCreatesMapFromGenerator(): void
    {
        $generator = function () {
            yield 'a' => 1;

            yield 'b' => 2;
        };
        $result = map($generator());

        $this->assertSame(['a' => 1, 'b' => 2], $result->toArray());
    }
}
