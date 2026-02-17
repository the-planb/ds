<?php

declare(strict_types=1);

namespace PlanB\Tests\Unit\DS\Set;

use PHPUnit\Framework\TestCase;
use PlanB\DS\Set\Set;

/**
 * @internal
 *
 * @coversNothing
 */
class SetTest extends TestCase
{
    public function testCollectCreatesInstanceFromArray(): void
    {
        $set = $this->createSet(['a', 'b', 'c']);

        $this->assertInstanceOf(Set::class, $set);
        $this->assertSame(3, $set->count());
    }

    public function testCollectRemovesDuplicates(): void
    {
        $set = $this->createSet(['a', 'b', 'a', 'c', 'b']);

        $this->assertSame(3, $set->count());
    }

    public function testCollectWithIterator(): void
    {
        $set = Set::collect(new \ArrayIterator(['a', 'b', 'c']));

        $this->assertSame(3, $set->count());
    }

    public function testCollectWithNormalizerTransformsValues(): void
    {
        $set = Set::collect(['a', 'b', 'c'], fn ($value) => strtoupper($value));

        $this->assertSame(['A', 'B', 'C'], $set->toArray());
    }

    public function testCollectWithNormalizerProvidesKeyToCallback(): void
    {
        $set = Set::collect(['a', 'b', 'c'], fn ($value, $key) => "{$key}:{$value}");

        $this->assertSame(['0:a', '1:b', '2:c'], $set->toArray());
    }

    public function testCollectWithNormalizerRemovesDuplicatesAfterTransformation(): void
    {
        $set = Set::collect(['a', 'A', 'b'], fn ($value) => strtolower($value));

        $this->assertSame(2, $set->count());
    }

    public function testUnionReturnsCombinedSet(): void
    {
        $set1 = $this->createSet(['a', 'b']);
        $set2 = $this->createSet(['b', 'c']);

        $result = $set1->union($set2);

        $this->assertNotSame($set1, $result);
        $this->assertSame(3, $result->count());
        $this->assertTrue($result->hasValue('a'));
        $this->assertTrue($result->hasValue('b'));
        $this->assertTrue($result->hasValue('c'));
    }

    public function testUnionIsImmutable(): void
    {
        $set1 = $this->createSet(['a', 'b']);
        $set2 = $this->createSet(['c']);

        $set1->union($set2);

        $this->assertSame(2, $set1->count());
    }

    public function testIntersectReturnsCommonElements(): void
    {
        $set1 = $this->createSet(['a', 'b', 'c']);
        $set2 = $this->createSet(['b', 'c', 'd']);

        $result = $set1->intersect($set2);

        $this->assertNotSame($set1, $result);
        $this->assertSame(2, $result->count());
        $this->assertTrue($result->hasValue('b'));
        $this->assertTrue($result->hasValue('c'));
        $this->assertFalse($result->hasValue('a'));
    }

    public function testIntersectIsImmutable(): void
    {
        $set1 = $this->createSet(['a', 'b', 'c']);
        $set2 = $this->createSet(['b']);

        $set1->intersect($set2);

        $this->assertSame(3, $set1->count());
    }

    public function testHasValueReturnsTrueForExistingValue(): void
    {
        $set = $this->createSet(['a', 'b', 'c']);

        $this->assertTrue($set->hasValue('a'));
        $this->assertTrue($set->hasValue('b'));
        $this->assertTrue($set->hasValue('c'));
    }

    public function testHasValueReturnsFalseForNonExistingValue(): void
    {
        $set = $this->createSet(['a', 'b']);

        $this->assertFalse($set->hasValue('c'));
    }

    public function testIsEmptyReturnsTrueForEmptySet(): void
    {
        $set = $this->createSet([]);

        $this->assertTrue($set->isEmpty());
    }

    public function testIsEmptyReturnsFalseForNonEmptySet(): void
    {
        $set = $this->createSet(['a']);

        $this->assertFalse($set->isEmpty());
    }

    public function testIsNotEmptyReturnsFalseForEmptySet(): void
    {
        $set = $this->createSet([]);

        $this->assertFalse($set->isNotEmpty());
    }

    public function testIsNotEmptyReturnsTrueForNonEmptySet(): void
    {
        $set = $this->createSet(['a']);

        $this->assertTrue($set->isNotEmpty());
    }

    public function testFilterReturnsNewSet(): void
    {
        $set = $this->createSet(['a', 'b', 'c']);

        $result = $set->filter(fn ($value) => $value !== 'b');

        $this->assertNotSame($set, $result);
        $this->assertSame(2, $result->count());
    }

    public function testUniqueReturnsNewSet(): void
    {
        $set = $this->createSet(['a', 'b', 'a', 'c']);

        $result = $set->unique();

        $this->assertNotSame($set, $result);
        $this->assertSame(3, $result->count());
    }

    public function testReversedReturnsNewSet(): void
    {
        $set = $this->createSet(['a', 'b', 'c']);

        $result = $set->reversed();

        $this->assertNotSame($set, $result);
        $this->assertSame(3, $result->count());
    }

    public function testToArrayReturnsArray(): void
    {
        $set = $this->createSet(['a', 'b', 'c']);

        $result = $set->toArray();

        $this->assertIsArray($result);
        $this->assertSame(['a', 'b', 'c'], $result);
    }

    public function testJsonSerializeReturnsArray(): void
    {
        $set = $this->createSet(['a', 'b']);

        $result = $set->jsonSerialize();

        $this->assertIsArray($result);
    }

    private function createSet(array $items): Set
    {
        return Set::collect($items);
    }
}
