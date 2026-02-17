<?php

declare(strict_types=1);

namespace PlanB\Tests\Unit\DS\Traits;

use PHPUnit\Framework\TestCase;
use PlanB\DS\Traits\SearchTrait;

/**
 * @internal
 *
 * @coversNothing
 */
class SearchTraitTest extends TestCase
{
    public function testHasValueReturnsTrueWhenValueExists(): void
    {
        $collection = $this->createCollection(['a', 'b', 'c']);

        $this->assertTrue($collection->hasValue('b'));
    }

    public function testHasValueReturnsFalseWhenValueNotExists(): void
    {
        $collection = $this->createCollection(['a', 'b', 'c']);

        $this->assertFalse($collection->hasValue('z'));
    }

    public function testHasValueReturnsFalseForEmptyCollection(): void
    {
        $collection = $this->createCollection([]);

        $this->assertFalse($collection->hasValue('a'));
    }

    public function testHasValueUsesStrictComparison(): void
    {
        $collection = $this->createCollection([1, 2, 3]);

        $this->assertFalse($collection->hasValue('1'));
        $this->assertTrue($collection->hasValue(1));
    }

    public function testContainsReturnsTrueWhenAllValuesExist(): void
    {
        $collection = $this->createCollection(['a', 'b', 'c']);

        $this->assertTrue($collection->contains('a', 'b'));
    }

    public function testContainsReturnsFalseWhenAnyValueMissing(): void
    {
        $collection = $this->createCollection(['a', 'b', 'c']);

        $this->assertFalse($collection->contains('a', 'z'));
    }

    public function testContainsReturnsTrueForEmptyArguments(): void
    {
        $collection = $this->createCollection(['a', 'b', 'c']);

        $this->assertTrue($collection->contains());
    }

    public function testFirstReturnsFirstElement(): void
    {
        $collection = $this->createCollection(['a', 'b', 'c']);

        $this->assertSame('a', $collection->first());
    }

    public function testFirstReturnsNullForEmptyCollection(): void
    {
        $collection = $this->createCollection([]);

        $this->assertNull($collection->first());
    }

    public function testFirstWithAssociativeKeys(): void
    {
        $collection = $this->createCollection(['first' => 'a', 'second' => 'b']);

        $this->assertSame('a', $collection->first());
    }

    public function testSomeReturnsTrueWhenConditionMatches(): void
    {
        $collection = $this->createCollection([1, 2, 3, 4]);

        $this->assertTrue($collection->some(fn ($v) => $v > 2));
    }

    public function testSomeReturnsFalseWhenNoMatch(): void
    {
        $collection = $this->createCollection([1, 2, 3]);

        $this->assertFalse($collection->some(fn ($v) => $v > 10));
    }

    public function testSomeEmptyCollectionReturnsFalse(): void
    {
        $collection = $this->createCollection([]);

        $this->assertFalse($collection->some(fn ($v) => $v > 0));
    }

    public function testSomeProvidesKeyToCallback(): void
    {
        $collection = $this->createCollection(['a', 'b', 'c']);

        $found = $collection->some(fn ($value, $key) => $key === 1);

        $this->assertTrue($found);
    }

    public function testEveryReturnsTrueWhenAllMatch(): void
    {
        $collection = $this->createCollection([1, 2, 3]);

        $this->assertTrue($collection->every(fn ($v) => $v > 0));
    }

    public function testEveryReturnsFalseWhenAnyFails(): void
    {
        $collection = $this->createCollection([1, 2, 3]);

        $this->assertFalse($collection->every(fn ($v) => $v < 3));
    }

    public function testEveryEmptyCollectionReturnsTrue(): void
    {
        $collection = $this->createCollection([]);

        $this->assertTrue($collection->every(fn ($v) => $v > 0));
    }

    public function testEveryProvidesKeyToCallback(): void
    {
        $collection = $this->createCollection(['a', 'b', 'c']);

        $result = $collection->every(fn ($value, $key) => is_int($key));

        $this->assertTrue($result);
    }

    public function testFirstThatReturnsFirstMatchingElement(): void
    {
        $collection = $this->createCollection([1, 2, 3, 4]);

        $this->assertSame(3, $collection->firstThat(fn ($v) => $v > 2));
    }

    public function testFirstThatReturnsNullWhenNoMatch(): void
    {
        $collection = $this->createCollection([1, 2, 3]);

        $this->assertNull($collection->firstThat(fn ($v) => $v > 10));
    }

    public function testFirstThatEmptyCollectionReturnsNull(): void
    {
        $collection = $this->createCollection([]);

        $this->assertNull($collection->firstThat(fn ($v) => $v > 0));
    }

    public function testFirstThatProvidesKeyToCallback(): void
    {
        $collection = $this->createCollection(['a', 'b', 'c']);

        $result = $collection->firstThat(fn ($value, $key) => $key === 2);

        $this->assertSame('c', $result);
    }

    public function testLastReturnsLastElement(): void
    {
        $collection = $this->createCollection(['a', 'b', 'c']);

        $this->assertSame('c', $collection->last());
    }

    public function testLastReturnsNullForEmptyCollection(): void
    {
        $collection = $this->createCollection([]);

        $this->assertNull($collection->last());
    }

    public function testLastWithAssociativeKeys(): void
    {
        $collection = $this->createCollection(['first' => 'a', 'second' => 'b']);

        $this->assertSame('b', $collection->last());
    }

    public function testLastThatReturnsLastMatchingElement(): void
    {
        $collection = $this->createCollection([1, 2, 3, 4]);

        $this->assertSame(2, $collection->lastThat(fn ($v) => $v < 3));
    }

    public function testLastThatReturnsNullWhenNoMatch(): void
    {
        $collection = $this->createCollection([1, 2, 3]);

        $this->assertNull($collection->lastThat(fn ($v) => $v > 10));
    }

    public function testLastThatEmptyCollectionReturnsNull(): void
    {
        $collection = $this->createCollection([]);

        $this->assertNull($collection->lastThat(fn ($v) => $v > 0));
    }

    public function testLastThatProvidesKeyToCallback(): void
    {
        $collection = $this->createCollection(['a' => 1, 'b' => 2, 'c' => 3]);

        $result = $collection->lastThat(fn ($value, $key) => $value < 3);

        $this->assertSame(2, $result);
    }

    public function testFindReturnsKeyWhenValueExists(): void
    {
        $collection = $this->createCollection(['a', 'b', 'c']);

        $this->assertSame(1, $collection->find('b'));
    }

    public function testFindReturnsFalseWhenValueNotExists(): void
    {
        $collection = $this->createCollection(['a', 'b', 'c']);

        $this->assertFalse($collection->find('z'));
    }

    public function testFindReturnsFalseForEmptyCollection(): void
    {
        $collection = $this->createCollection([]);

        $this->assertFalse($collection->find('a'));
    }

    public function testFindUsesStrictComparison(): void
    {
        $collection = $this->createCollection([1, 2, 3]);

        $this->assertFalse($collection->find('1'));
        $this->assertSame(0, $collection->find(1));
    }

    public function testFindWithAssociativeKeys(): void
    {
        $collection = $this->createCollection(['foo' => 'a', 'bar' => 'b']);

        $this->assertSame('bar', $collection->find('b'));
    }

    public function testFindReturnsFirstKeyWhenDuplicates(): void
    {
        $collection = $this->createCollection(['a', 'b', 'a']);

        $this->assertSame(0, $collection->find('a'));
    }

    public function testHasValueWithNullValue(): void
    {
        $collection = $this->createCollection([null, 'a', null]);

        $this->assertTrue($collection->hasValue(null));
    }

    public function testHasValueWithFalseValue(): void
    {
        $collection = $this->createCollection([false, 'a', true]);

        $this->assertTrue($collection->hasValue(false));
    }

    public function testHasValueWithZeroValue(): void
    {
        $collection = $this->createCollection([0, 1, 2]);

        $this->assertTrue($collection->hasValue(0));
    }

    public function testHasValueWithEmptyString(): void
    {
        $collection = $this->createCollection(['', 'a', 'b']);

        $this->assertTrue($collection->hasValue(''));
    }

    public function testLastReturnsLastElementWithZeroValue(): void
    {
        $collection = $this->createCollection([1, 2, 0]);

        $this->assertSame(0, $collection->last());
    }

    public function testFirstReturnsFalseForFalseValue(): void
    {
        $collection = $this->createCollection([false, true, true]);

        $this->assertSame(false, $collection->first());
    }

    private function createCollection(iterable $input): TestableSearch
    {
        return TestableSearch::collect($input);
    }
}

class TestableSearch
{
    use SearchTrait;
}
