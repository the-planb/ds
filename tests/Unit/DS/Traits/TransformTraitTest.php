<?php

declare(strict_types=1);

namespace PlanB\Tests\Unit\DS\Traits;

use PHPUnit\Framework\TestCase;
use PlanB\DS\Traits\TransformTrait;
use PlanB\DS\Vector\Vector;

/**
 * @internal
 *
 * @coversNothing
 */
class TransformTraitTest extends TestCase
{
    public function testFilterKeepsMatchingValues(): void
    {
        $collection = $this->createCollection([1, 2, 3, 4]);

        $result = $collection->filter(fn ($v) => $v > 2);

        $this->assertSame([0 => 3, 1 => 4], $result->toArray());
    }

    public function testFilterPreservesKeys(): void
    {
        $collection = $this->createCollection(['a' => 1, 'b' => 2, 'c' => 3]);

        $result = $collection->filter(fn ($v) => $v > 1);

        $this->assertSame(['b' => 2, 'c' => 3], $result->toArray());
    }

    public function testFilterWithNullCallbackUsesTruthiness(): void
    {
        $collection = $this->createCollection([0, 1, 2, '', 'a', null, false]);

        $result = $collection->filter();

        $this->assertSame([0 => 1, 1 => 2, 2 => 'a'], $result->toArray());
    }

    public function testFilterReturnsNewInstance(): void
    {
        $collection = $this->createCollection([1, 2, 3]);

        $collection->filter(fn ($v) => $v > 10);

        $this->assertSame([1, 2, 3], $collection->toArray());
    }

    public function testSortSortsValues(): void
    {
        $collection = $this->createCollection([3, 1, 2]);

        $result = $collection->sort();

        $this->assertSame([0 => 1, 1 => 2, 2 => 3], $result->toArray());
    }

    public function testSortPreservesKeys(): void
    {
        $collection = $this->createCollection(['a' => 3, 'b' => 1, 'c' => 2]);

        $result = $collection->sort();

        $this->assertSame(['b' => 1, 'c' => 2, 'a' => 3], $result->toArray());
    }

    public function testSortWithCustomComparator(): void
    {
        $collection = $this->createCollection([1, 2, 3, 4]);

        $result = $collection->sort(fn ($a, $b) => $b <=> $a);

        $this->assertSame([0 => 4, 1 => 3, 2 => 2, 3 => 1], $result->toArray());
    }

    public function testReversedReversesOrder(): void
    {
        $collection = $this->createCollection([1, 2, 3]);

        $result = $collection->reversed();

        $this->assertSame([0 => 3, 1 => 2, 2 => 1], $result->toArray());
    }

    public function testReversedPreservesKeys(): void
    {
        $collection = $this->createCollection(['a' => 1, 'b' => 2, 'c' => 3]);

        $result = $collection->reversed();

        $this->assertSame(['c' => 3, 'b' => 2, 'a' => 1], $result->toArray());
    }

    public function testShuffleRandomizesOrder(): void
    {
        $collection = $this->createCollection([1, 2, 3, 4, 5]);

        $result = $collection->shuffle();

        //        $this->assertCount(5, $result);
        $this->assertContains(1, $result->toArray());
        $this->assertContains(2, $result->toArray());
        $this->assertContains(3, $result->toArray());
        $this->assertContains(4, $result->toArray());
        $this->assertContains(5, $result->toArray());
    }

    public function testUniqueRemovesDuplicates(): void
    {
        $collection = $this->createCollection([1, 2, 2, 3, 1, 4]);

        $result = $collection->unique();

        $this->assertSame([0 => 1, 1 => 2, 2 => 3, 3 => 4], $result->toArray());
    }

    public function testUniquePreservesFirstOccurrenceKey(): void
    {
        $collection = $this->createCollection(['a' => 1, 'b' => 2, 'c' => 1]);

        $result = $collection->unique();

        $this->assertSame(['a' => 1, 'b' => 2], $result->toArray());
    }

    public function testFlattenFlattensOneLevel(): void
    {
        $collection = $this->createCollection([[1, 2], [3, 4], [5]]);

        $result = $collection->flatten();

        $this->assertInstanceOf(Vector::class, $result);
        $this->assertSame([1, 2, 3, 4, 5], $result->toArray());
    }

    public function testFlattenPreservesNonArrays(): void
    {
        $collection = $this->createCollection([1, [2, 3], 4]);

        $result = $collection->flatten();

        $this->assertSame([1, 2, 3, 4], $result->toArray());
    }

    public function testFlattenWithDepthZero(): void
    {
        $collection = $this->createCollection([[1, 2], [3, 4]]);

        $result = $collection->flatten(0);

        $this->assertInstanceOf(Vector::class, $result);
        $this->assertSame([[1, 2], [3, 4]], $result->toArray());
    }

    public function testFlattenWithDepthTwo(): void
    {
        $collection = $this->createCollection([[[1, 2], [3]], [[4]]]);

        $result = $collection->flatten(2);

        $this->assertSame([1, 2, 3, 4], $result->toArray());
    }

    public function testFlatMapMapsAndFlattens(): void
    {
        $collection = $this->createCollection([1, 2, 3]);

        $result = $collection->flatMap(fn ($v) => [$v, $v * 2]);

        $this->assertInstanceOf(Vector::class, $result);
        $this->assertSame([1, 2, 2, 4, 3, 6], $result->toArray());
    }

    public function testFlatMapWithGenerator(): void
    {
        $collection = $this->createCollection(['a', 'b']);

        $result = $collection->flatMap(fn ($v) => str_split($v));

        $this->assertSame(['a', 'b'], $result->toArray());
    }

    public function testFlatMapHandlesNonIterableResults(): void
    {
        $collection = $this->createCollection([1, 2, 3]);

        $result = $collection->flatMap(fn ($v) => $v * 2);

        $this->assertSame([2, 4, 6], $result->toArray());
    }

    public function testReduceAccumulatesValues(): void
    {
        $collection = $this->createCollection([1, 2, 3, 4]);

        $result = $collection->reduce(fn ($acc, $v) => $acc + $v, 0);

        $this->assertSame(10, $result);
    }

    public function testReduceWithoutInitial(): void
    {
        $collection = $this->createCollection([1, 2, 3, 4]);

        $result = $collection->reduce(fn ($acc, $v) => $acc + $v);

        $this->assertSame(10, $result);
    }

    public function testReduceProvidesKeyToCallback(): void
    {
        $collection = $this->createCollection([1, 2, 3]);

        $result = $collection->reduce(fn ($acc, $v, $k) => $acc + $k, 0);

        $this->assertSame(3, $result);
    }

    public function testReduceWithInitialValue(): void
    {
        $collection = $this->createCollection([1, 2, 3]);

        $result = $collection->reduce(fn ($acc, $v) => $acc + $v, 10);

        $this->assertSame(16, $result);
    }

    public function testReduceOnEmptyCollectionWithInitial(): void
    {
        $collection = $this->createCollection([]);

        $result = $collection->reduce(fn ($acc, $v) => $acc + $v, 5);

        $this->assertSame(5, $result);
    }

    public function testReduceOnEmptyCollectionWithoutInitial(): void
    {
        $collection = $this->createCollection([]);

        $result = $collection->reduce(fn ($acc, $v) => $acc + $v);

        $this->assertNull($result);
    }

    public function testFilterRemovesAll(): void
    {
        $collection = $this->createCollection([1, 2, 3]);

        $result = $collection->filter(fn ($v) => $v > 10);

        $this->assertSame([], $result->toArray());
    }

    public function testSortEmptyCollection(): void
    {
        $collection = $this->createCollection([]);

        $result = $collection->sort();

        $this->assertSame([], $result->toArray());
    }

    public function testReversedEmptyCollection(): void
    {
        $collection = $this->createCollection([]);

        $result = $collection->reversed();

        $this->assertSame([], $result->toArray());
    }

    public function testShuffleEmptyCollection(): void
    {
        $collection = $this->createCollection([]);

        $result = $collection->shuffle();

        $this->assertSame([], $result->toArray());
    }

    public function testUniqueEmptyCollection(): void
    {
        $collection = $this->createCollection([]);

        $result = $collection->unique();

        $this->assertSame([], $result->toArray());
    }

    public function testDiffRemovesValuesPresentInInput(): void
    {
        $collection = $this->createCollection([1, 2, 3, 4, 5]);

        $result = $collection->diff([2, 4]);

        $this->assertSame([1, 3, 5], array_values($result->toArray()));
    }

    public function testDiffWithAssociativeKeys(): void
    {
        $collection = $this->createCollection(['a' => 1, 'b' => 2, 'c' => 3]);

        $result = $collection->diff([2]);

        $this->assertSame(['a' => 1, 'c' => 3], $result->toArray());
    }

    public function testDiffWithComparisonCallback(): void
    {
        $collection = $this->createCollection(['a' => 'apple', 'b' => 'banana', 'c' => 'cherry']);

        $result = $collection->diff(['apple', 'date'], fn ($a, $b) => strcmp($a, $b));

        $this->assertSame(['b' => 'banana', 'c' => 'cherry'], $result->toArray());
    }

    public function testDiffEmptyCollection(): void
    {
        $collection = $this->createCollection([]);

        $result = $collection->diff([1, 2, 3]);

        $this->assertSame([], $result->toArray());
    }

    public function testDiffWithEmptyInput(): void
    {
        $collection = $this->createCollection([1, 2, 3]);

        $result = $collection->diff([]);

        $this->assertSame([1, 2, 3], $result->toArray());
    }

    public function testDiffReturnsNewInstance(): void
    {
        $collection = $this->createCollection([1, 2, 3]);

        $result = $collection->diff([2]);

        $this->assertNotSame($collection, $result);
    }

    private function createCollection(iterable $input): TestableTransform
    {
        return TestableTransform::collect($input);
    }
}

class TestableTransform
{
    use TransformTrait;
}
