<?php

declare(strict_types=1);

namespace PlanB\Tests\Unit\DS\Vector;

use PHPUnit\Framework\TestCase;
use PlanB\DS\Exception\ElementNotFoundException;
use PlanB\DS\Vector\Vector;

/**
 * @internal
 *
 * @coversNothing
 */
class VectorTest extends TestCase
{
    public function testCollectCreatesInstanceFromArray(): void
    {
        $vector = $this->createVector(['a', 'b', 'c']);

        $this->assertInstanceOf(Vector::class, $vector);
        $this->assertSame(3, $vector->count());
    }

    public function testGetReturnsElementAtIndex(): void
    {
        $vector = $this->createVector(['a', 'b', 'c']);

        $this->assertSame('a', $vector->get(0));
        $this->assertSame('b', $vector->get(1));
        $this->assertSame('c', $vector->get(2));
    }

    public function testGetReturnsDefaultWhenIndexNotFound(): void
    {
        $vector = $this->createVector(['a', 'b']);

        $this->assertSame('default', $vector->get(5, 'default'));
        $this->assertNull($vector->get(5, null));
    }

    public function testGetThrowsExceptionWhenIndexNotFoundWithoutDefault(): void
    {
        $vector = $this->createVector(['a', 'b']);

        $this->expectException(ElementNotFoundException::class);
        $this->expectExceptionMessage("The key '5' doesn't exists");

        $vector->get(5);
    }

    public function testHasKeyReturnsTrueWhenIndexExists(): void
    {
        $vector = $this->createVector(['a', 'b', 'c']);

        $this->assertTrue($vector->hasKey(0));
        $this->assertTrue($vector->hasKey(1));
        $this->assertTrue($vector->hasKey(2));
    }

    public function testHasKeyReturnsFalseWhenIndexNotExists(): void
    {
        $vector = $this->createVector(['a', 'b']);

        $this->assertFalse($vector->hasKey(5));
        $this->assertFalse($vector->hasKey(-1));
    }

    public function testHasIndexIsAliasOfHasKey(): void
    {
        $vector = $this->createVector(['a', 'b']);

        $this->assertTrue($vector->hasIndex(0));
        $this->assertFalse($vector->hasIndex(5));
    }

    public function testCollectWithNormalizerTransformsValues(): void
    {
        $vector = Vector::collect(['a', 'b', 'c'], fn ($value) => strtoupper($value));

        $this->assertSame(['A', 'B', 'C'], $vector->toArray());
    }

    public function testCollectWithNormalizerReindexesKeys(): void
    {
        $vector = Vector::collect(['a' => 1, 'b' => 2], fn ($value) => $value * 10);

        $this->assertSame([10, 20], $vector->toArray());
    }

    public function testCollectWithNormalizerProvidesKeyToCallback(): void
    {
        $vector = Vector::collect(['a', 'b', 'c'], fn ($value, $key) => "{$key}:{$value}");

        $this->assertSame(['0:a', '1:b', '2:c'], $vector->toArray());
    }

    public function testCollectWithIterator(): void
    {
        $iterator = new \ArrayIterator(['a', 'b', 'c']);
        $vector = Vector::collect($iterator);

        $this->assertSame(['a', 'b', 'c'], $vector->toArray());
    }

    public function testCollectWithGenerator(): void
    {
        $generator = function () {
            yield 'a';

            yield 'b';

            yield 'c';
        };
        $vector = Vector::collect($generator());

        $this->assertSame(['a', 'b', 'c'], $vector->toArray());
    }

    public function testMapTransformsValues(): void
    {
        $collection = Vector::collect([1, 2, 3]);

        $result = $collection->map(fn ($v) => $v * 2);

        $this->assertSame([2, 4, 6], $result->toArray());
    }

    public function testMapPreservesKeys(): void
    {
        $collection = Vector::collect([0 => 1, 1 => 2]);

        $result = $collection->map(fn ($v) => $v * 10);

        $this->assertSame([0 => 10, 1 => 20], $result->toArray());
    }

    public function testMapProvidesKeyToCallback(): void
    {
        $collection = Vector::collect(['a', 'b', 'c']);

        $result = $collection->map(fn ($v, $k) => "{$k}:{$v}");

        $this->assertSame(['0:a', '1:b', '2:c'], $result->toArray());
    }

    public function testMapReturnsNewInstance(): void
    {
        $collection = Vector::collect([1, 2, 3]);
        $original = $collection->toArray();

        $collection->map(fn ($v) => $v * 2);

        $this->assertSame($original, $collection->toArray());
    }

    public function testChainingMultipleTransforms(): void
    {
        $collection = Vector::collect([1, 2, 3, 4, 5]);

        $result = $collection
            ->filter(fn ($v) => $v % 2 === 0)
            ->map(fn ($v) => $v * 10)
        ;

        $this->assertSame([0 => 20, 1 => 40], $result->toArray());
    }

    public function testMapWithEmptyCollection(): void
    {
        $collection = Vector::collect([]);

        $result = $collection->map(fn ($v) => $v * 2);

        $this->assertSame([], $result->toArray());
    }

    private function createVector(array $items): Vector
    {
        return Vector::collect($items);
    }
}
