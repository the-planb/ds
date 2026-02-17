<?php

declare(strict_types=1);

namespace PlanB\Tests\Unit\DS\Map;

use PHPUnit\Framework\TestCase;
use PlanB\DS\Map\MapMutable;

/**
 * @internal
 *
 * @coversNothing
 */
class MapMutableTest extends TestCase
{
    public function testCollectCreatesInstanceFromArray(): void
    {
        $map = $this->createMapMutable(['a' => 1, 'b' => 2]);

        $this->assertInstanceOf(MapMutable::class, $map);
        $this->assertSame(2, $map->count());
    }

    public function testPutAddsKeyValuePair(): void
    {
        $map = $this->createMapMutable(['a' => 1]);

        $result = $map->put('b', 2);

        $this->assertSame(['a' => 1, 'b' => 2], $map->toArray());
        $this->assertSame($map, $result);
    }

    public function testPutOverwritesExistingKey(): void
    {
        $map = $this->createMapMutable(['a' => 1]);

        $map->put('a', 10);

        $this->assertSame(['a' => 10], $map->toArray());
    }

    public function testPutReturnsThisForChaining(): void
    {
        $map = $this->createMapMutable();

        $result = $map->put('a', 1)->put('b', 2)->put('c', 3);

        $this->assertSame(['a' => 1, 'b' => 2, 'c' => 3], $map->toArray());
        $this->assertSame($map, $result);
    }

    public function testPutAllAddsMultipleKeyValuePairs(): void
    {
        $map = $this->createMapMutable(['a' => 1]);

        $result = $map->putAll(['b' => 2, 'c' => 3]);

        $this->assertSame(['a' => 1, 'b' => 2, 'c' => 3], $map->toArray());
    }

    public function testPutAllOverwritesExistingKeys(): void
    {
        $map = $this->createMapMutable(['a' => 1, 'b' => 2]);

        $map->putAll(['a' => 10, 'c' => 3]);

        $this->assertSame(['a' => 10, 'b' => 2, 'c' => 3], $map->toArray());
    }

    public function testPutAllWithIterator(): void
    {
        $map = $this->createMapMutable(['a' => 1]);
        $iterator = new \ArrayIterator(['b' => 2, 'c' => 3]);

        $map->putAll($iterator);

        $this->assertSame(['a' => 1, 'b' => 2, 'c' => 3], $map->toArray());
    }

    public function testRemoveDeletesKey(): void
    {
        $map = $this->createMapMutable(['a' => 1, 'b' => 2]);

        $result = $map->remove('a');

        $this->assertSame(['b' => 2], $map->toArray());
        $this->assertSame($map, $result);
    }

    public function testRemoveNonexistentKeyDoesNothing(): void
    {
        $map = $this->createMapMutable(['a' => 1]);

        $map->remove('nonexistent');

        $this->assertSame(['a' => 1], $map->toArray());
    }

    public function testRemoveValueRemovesFirstOccurrence(): void
    {
        $map = $this->createMapMutable(['a' => 1, 'b' => 2, 'c' => 1]);

        $result = $map->removeValue(1);

        $this->assertSame(['b' => 2, 'c' => 1], $map->toArray());
        $this->assertSame($map, $result);
    }

    public function testRemoveValueUsesStrictComparison(): void
    {
        $map = $this->createMapMutable(['a' => '1', 'b' => 1, 'c' => '2']);

        $map->removeValue('1');

        $this->assertSame(['b' => 1, 'c' => '2'], $map->toArray());
    }

    public function testRemoveValueNonexistentValueDoesNothing(): void
    {
        $map = $this->createMapMutable(['a' => 1, 'b' => 2]);

        $map->removeValue(99);

        $this->assertSame(['a' => 1, 'b' => 2], $map->toArray());
    }

    public function testChainingMutableOperations(): void
    {
        $map = $this->createMapMutable();

        $map->put('a', 1)->put('b', 2)->putAll(['c' => 3])->remove('a');

        $this->assertSame(['b' => 2, 'c' => 3], $map->toArray());
    }

    public function testImmutableOperationsReturnNewInstance(): void
    {
        $map = $this->createMapMutable(['a' => 1, 'b' => 2]);

        $mapped = $map->map(fn ($v) => $v * 10);

        $this->assertSame(['a' => 10, 'b' => 20], $mapped->toArray());
        $this->assertSame(['a' => 1, 'b' => 2], $map->toArray());
    }

    public function testMergeReturnsNewInstance(): void
    {
        $map1 = $this->createMapMutable(['a' => 1]);
        $map2 = $this->createMapMutable(['b' => 2]);

        $merged = $map1->merge($map2);

        $this->assertSame(['a' => 1], $map1->toArray());
        $this->assertSame(['b' => 2], $map2->toArray());
        $this->assertSame(['a' => 1, 'b' => 2], $merged->toArray());
    }

    private function createMapMutable(array $items = []): MapMutable
    {
        return MapMutable::collect($items);
    }
}
