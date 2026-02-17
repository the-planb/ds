<?php

declare(strict_types=1);

namespace PlanB\Tests\DS\Map;

use PHPUnit\Framework\TestCase;
use PlanB\DS\Exception\ElementNotFoundException;
use PlanB\DS\Map\Map;
use PlanB\DS\Vector\Vector;

/**
 * @internal
 *
 * @coversNothing
 */
class MapTest extends TestCase
{
    public function testCollectCreatesInstanceFromArray(): void
    {
        $map = $this->createMap(['a' => 1, 'b' => 2]);

        $this->assertInstanceOf(Map::class, $map);
        $this->assertSame(2, $map->count());
    }

    public function testCollectWithNumericKeysConvertsToStrings(): void
    {
        $map = $this->createMap([0 => 'a', 1 => 'b']);

        $this->assertTrue($map->hasKey('0'));
        $this->assertTrue($map->hasKey('1'));
    }

    public function testGetReturnsElementAtKey(): void
    {
        $map = $this->createMap(['a' => 1, 'b' => 2]);

        $this->assertSame(1, $map->get('a'));
        $this->assertSame(2, $map->get('b'));
    }

    public function testGetReturnsDefaultWhenKeyNotFound(): void
    {
        $map = $this->createMap(['a' => 1]);

        $this->assertSame('default', $map->get('nonexistent', 'default'));
        $this->assertNull($map->get('nonexistent', null));
    }

    public function testGetThrowsExceptionWhenKeyNotFoundWithoutDefault(): void
    {
        $map = $this->createMap(['a' => 1]);

        $this->expectException(ElementNotFoundException::class);
        $this->expectExceptionMessage("The key 'nonexistent' doesn't exists");

        $map->get('nonexistent');
    }

    public function testHasKeyReturnsTrueWhenKeyExists(): void
    {
        $map = $this->createMap(['a' => 1, 'b' => 2]);

        $this->assertTrue($map->hasKey('a'));
        $this->assertTrue($map->hasKey('b'));
    }

    public function testHasKeyReturnsFalseWhenKeyNotExists(): void
    {
        $map = $this->createMap(['a' => 1]);

        $this->assertFalse($map->hasKey('nonexistent'));
    }

    public function testKeysReturnsVectorOfKeys(): void
    {
        $map = $this->createMap(['a' => 1, 'b' => 2, 'c' => 3]);

        $keys = $map->keys();

        $this->assertInstanceOf(Vector::class, $keys);
        $this->assertSame(['a', 'b', 'c'], $keys->toArray());
    }

    public function testValuesReturnsVectorOfValues(): void
    {
        $map = $this->createMap(['a' => 1, 'b' => 2, 'c' => 3]);

        $values = $map->values();

        $this->assertInstanceOf(Vector::class, $values);
        $this->assertSame([1, 2, 3], $values->toArray());
    }

    public function testMapKeysTransformsKeys(): void
    {
        $map = $this->createMap(['a' => 1, 'b' => 2]);

        $mapped = $map->mapKeys(fn ($value, $key) => strtoupper($key));

        $this->assertInstanceOf(Map::class, $mapped);
        $this->assertTrue($mapped->hasKey('A'));
        $this->assertTrue($mapped->hasKey('B'));
        $this->assertFalse($map->hasKey('A'));
    }

    public function testMapKeysPreservesValues(): void
    {
        $map = $this->createMap(['a' => 1, 'b' => 2]);

        $mapped = $map->mapKeys(fn ($value, $key) => "key_{$key}");

        $this->assertSame(1, $mapped->get('key_a'));
        $this->assertSame(2, $mapped->get('key_b'));
    }

    public function testMergeCombinesMaps(): void
    {
        $map1 = $this->createMap(['a' => 1, 'b' => 2]);
        $map2 = $this->createMap(['c' => 3, 'd' => 4]);

        $merged = $map1->merge($map2);

        $this->assertInstanceOf(Map::class, $merged);
        $this->assertSame(4, $merged->count());
        $this->assertSame(1, $merged->get('a'));
        $this->assertSame(2, $merged->get('b'));
        $this->assertSame(3, $merged->get('c'));
        $this->assertSame(4, $merged->get('d'));
    }

    public function testMergeOverwritesExistingKeys(): void
    {
        $map1 = $this->createMap(['a' => 1, 'b' => 2]);
        $map2 = $this->createMap(['a' => 10, 'c' => 3]);

        $merged = $map1->merge($map2);

        $this->assertSame(10, $merged->get('a'));
        $this->assertSame(2, $merged->get('b'));
        $this->assertSame(3, $merged->get('c'));
    }

    public function testMergeWithArray(): void
    {
        $map = $this->createMap(['a' => 1]);

        $merged = $map->merge(['b' => 2, 'c' => 3]);

        $this->assertSame(3, $merged->count());
    }

    public function testMergeWithIterator(): void
    {
        $map = $this->createMap(['a' => 1]);
        $iterator = new \ArrayIterator(['b' => 2, 'c' => 3]);

        $merged = $map->merge($iterator);

        $this->assertSame(3, $merged->count());
    }

    public function testCollectWithNormalizerTransformsValues(): void
    {
        $map = Map::collect(['a' => 1, 'b' => 2], fn ($value) => $value * 10);

        $this->assertSame(['a' => 10, 'b' => 20], $map->toArray());
    }

    public function testCollectWithNormalizerProvidesKeyToCallback(): void
    {
        $map = Map::collect(['a' => 1, 'b' => 2], fn ($value, $key) => $key . ':' . $value);

        $this->assertSame(['a' => 'a:1', 'b' => 'b:2'], $map->toArray());
    }

    public function testCollectWithIterator(): void
    {
        $iterator = new \ArrayIterator(['a' => 1, 'b' => 2]);
        $map = Map::collect($iterator);

        $this->assertSame(['a' => 1, 'b' => 2], $map->toArray());
    }

    public function testCollectWithGenerator(): void
    {
        $generator = function () {
            yield 'a' => 1;

            yield 'b' => 2;
        };
        $map = Map::collect($generator());

        $this->assertSame(['a' => 1, 'b' => 2], $map->toArray());
    }

    public function testKeySortSortsByKeys(): void
    {
        $map = $this->createMap(['b' => 2, 'a' => 1, 'c' => 3]);

        $result = $map->keySort();

        $this->assertSame(['a' => 1, 'b' => 2, 'c' => 3], $result->toArray());
    }

    public function testKeySortWithComparisonCallback(): void
    {
        $map = $this->createMap(['b' => 2, 'a' => 1, 'c' => 3]);

        $result = $map->keySort(fn ($a, $b) => strcmp($b, $a));

        $this->assertSame(['c' => 3, 'b' => 2, 'a' => 1], $result->toArray());
    }

    public function testKeySortReturnsNewInstance(): void
    {
        $map = $this->createMap(['b' => 2, 'a' => 1]);

        $result = $map->keySort();

        $this->assertNotSame($map, $result);
    }

    public function testDiffKeysRemovesKeysPresentInInput(): void
    {
        $map = $this->createMap(['a' => 1, 'b' => 2, 'c' => 3]);

        $result = $map->diffKeys(['b' => 10, 'd' => 20]);

        $this->assertSame(['a' => 1, 'c' => 3], $result->toArray());
    }

    public function testDiffKeysWithComparisonCallback(): void
    {
        $map = $this->createMap(['a1' => 1, 'b2' => 2, 'c3' => 3]);

        $result = $map->diffKeys(['a1' => 10, 'b2' => 20], fn ($a, $b) => $a <=> $b);

        $this->assertSame(['c3' => 3], $result->toArray());
    }

    public function testDiffKeysEmptyInput(): void
    {
        $map = $this->createMap(['a' => 1, 'b' => 2]);

        $result = $map->diffKeys([]);

        $this->assertSame(['a' => 1, 'b' => 2], $result->toArray());
    }

    public function testIntersectKeepsValuesPresentInInput(): void
    {
        $map = $this->createMap(['a' => 1, 'b' => 2, 'c' => 3]);

        $result = $map->intersect([1, 3, 5]);

        $this->assertSame(['a' => 1, 'c' => 3], $result->toArray());
    }

    public function testIntersectWithComparisonCallback(): void
    {
        $map = $this->createMap(['a' => 11, 'b' => 22, 'c' => 33]);

        $result = $map->intersect([1, 2], fn ($a, $b) => ($a % 10) - ($b % 10));

        $this->assertSame(['a' => 11, 'b' => 22], $result->toArray());
    }

    public function testIntersectEmptyInput(): void
    {
        $map = $this->createMap(['a' => 1, 'b' => 2]);

        $result = $map->intersect([]);

        $this->assertSame([], $result->toArray());
    }

    public function testIntersectKeysKeepsKeysPresentInInput(): void
    {
        $map = $this->createMap(['a' => 1, 'b' => 2, 'c' => 3]);

        $result = $map->intersectKeys(['a' => 10, 'c' => 30, 'd' => 40]);

        $this->assertSame(['a' => 1, 'c' => 3], $result->toArray());
    }

    public function testIntersectKeysWithComparisonCallback(): void
    {
        $map = $this->createMap(['ab' => 1, 'cd' => 2, 'ef' => 3]);

        $result = $map->intersectKeys(['ab' => 1, 'xyz' => 2], fn ($a, $b) => strlen($a) <=> strlen($b));

        $this->assertSame(['ab' => 1], $result->toArray());
    }

    public function testIntersectKeysEmptyInput(): void
    {
        $map = $this->createMap(['a' => 1, 'b' => 2]);

        $result = $map->intersectKeys([]);

        $this->assertSame([], $result->toArray());
    }

    public function testDiffKeysReturnsNewInstance(): void
    {
        $map = $this->createMap(['a' => 1, 'b' => 2]);

        $result = $map->diffKeys(['a']);

        $this->assertNotSame($map, $result);
    }

    public function testIntersectReturnsNewInstance(): void
    {
        $map = $this->createMap(['a' => 1, 'b' => 2]);

        $result = $map->intersect([1]);

        $this->assertNotSame($map, $result);
    }

    public function testIntersectKeysReturnsNewInstance(): void
    {
        $map = $this->createMap(['a' => 1, 'b' => 2]);

        $result = $map->intersectKeys(['a']);

        $this->assertNotSame($map, $result);
    }

    public function testMapTransformsValues(): void
    {
        $collection = $this->createMap([1, 2, 3]);

        $result = $collection->map(fn ($v) => $v * 2);

        $this->assertSame([2, 4, 6], $result->toArray());
    }

    public function testMapPreservesKeys(): void
    {
        $collection = $this->createMap(['a' => 1, 'b' => 2]);

        $result = $collection->map(fn ($v) => $v * 10);

        $this->assertSame(['a' => 10, 'b' => 20], $result->toArray());
    }

    public function testMapProvidesKeyToCallback(): void
    {
        $collection = $this->createMap(['a', 'b', 'c']);

        $result = $collection->map(fn ($v, $k) => "{$k}:{$v}");

        $this->assertSame(['0:a', '1:b', '2:c'], $result->toArray());
    }

    public function testMapReturnsNewInstance(): void
    {
        $collection = $this->createMap([1, 2, 3]);
        $original = $collection->toArray();

        $collection->map(fn ($v) => $v * 2);

        $this->assertSame($original, $collection->toArray());
    }

    public function testChainingMultipleTransforms(): void
    {
        $collection = $this->createMap([1, 2, 3, 4, 5]);

        $result = $collection
            ->filter(fn ($v) => $v % 2 === 0)
            ->map(fn ($v) => $v * 10)
        ;

        $this->assertSame([0 => 20, 1 => 40], $result->toArray());
    }

    public function testMapWithEmptyCollection(): void
    {
        $collection = $this->createMap([]);

        $result = $collection->map(fn ($v) => $v * 2);

        $this->assertSame([], $result->toArray());
    }

    private function createMap(array $items): Map
    {
        return Map::collect($items);
    }
}
