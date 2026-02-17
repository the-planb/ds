<?php

declare(strict_types=1);

namespace PlanB\Tests\Unit\DS\Traits;

use PHPUnit\Framework\TestCase;
use PlanB\DS\Traits\CollectionTrait;

/**
 * @internal
 *
 * @coversNothing
 */
class CollectionTraitTest extends TestCase
{
    public function testCountReturnsZeroForEmptyCollection(): void
    {
        $class = $this->createCollection([]);

        $this->assertSame(0, $class->count());
    }

    public function testCountReturnsCorrectNumberOfItems(): void
    {
        $class = $this->createCollection(['a', 'b', 'c']);

        $this->assertSame(3, $class->count());
    }

    public function testCountReturnsCorrectNumberWithAssociativeArray(): void
    {
        $class = $this->createCollection(['foo' => 'a', 'bar' => 'b']);

        $this->assertSame(2, $class->count());
    }

    public function testIsEmptyReturnsTrueForEmptyCollection(): void
    {
        $class = $this->createCollection([]);

        $this->assertTrue($class->isEmpty());
    }

    public function testIsEmptyReturnsFalseForNonEmptyCollection(): void
    {
        $class = $this->createCollection(['a']);

        $this->assertFalse($class->isEmpty());
    }

    public function testIsNotEmptyReturnsTrueForNonEmptyCollection(): void
    {
        $class = $this->createCollection(['a']);

        $this->assertTrue($class->isNotEmpty());
    }

    public function testIsNotEmptyReturnsFalseForEmptyCollection(): void
    {
        $class = $this->createCollection([]);

        $this->assertFalse($class->isNotEmpty());
    }

    public function testHasCountReturnsTrueWhenCountMatches(): void
    {
        $class = $this->createCollection(['a', 'b', 'c']);

        $this->assertTrue($class->hasCount(3));
    }

    public function testHasCountReturnsFalseWhenCountDoesNotMatch(): void
    {
        $class = $this->createCollection(['a', 'b', 'c']);

        $this->assertFalse($class->hasCount(5));
    }

    public function testHasCountReturnsTrueForZero(): void
    {
        $class = $this->createCollection([]);

        $this->assertTrue($class->hasCount(0));
    }

    public function testEachCallsCallbackForEachItem(): void
    {
        $class = $this->createCollection(['a', 'b', 'c']);
        $called = [];

        $class->each(function ($value, $key) use (&$called): void {
            $called[] = [$key, $value];
        });

        $this->assertSame([[0, 'a'], [1, 'b'], [2, 'c']], $called);
    }

    public function testEachProvidesKeyAndValueToCallback(): void
    {
        $class = $this->createCollection(['first' => 'a', 'second' => 'b']);
        $called = [];

        $class->each(function ($value, $key) use (&$called): void {
            $called[] = [$key, $value];
        });

        $this->assertSame([['first', 'a'], ['second', 'b']], $called);
    }

    public function testEachReturnsThisForChaining(): void
    {
        $class = $this->createCollection(['a']);

        $result = $class->each(function (): void {});

        $this->assertSame($class, $result);
    }

    public function testEachDoesNotModifyItems(): void
    {
        $class = $this->createCollection(['a', 'b', 'c']);

        $class->each(function ($value): void {
            // Modify value in some way, but original should remain
        });

        $this->assertSame(['a', 'b', 'c'], $class->toArray());
    }

    public function testToArrayReturnsEmptyArrayForEmptyCollection(): void
    {
        $class = $this->createCollection([]);

        $this->assertSame([], $class->toArray());
    }

    public function testToArrayReturnsItemsArray(): void
    {
        $class = $this->createCollection(['a', 'b', 'c']);

        $this->assertSame(['a', 'b', 'c'], $class->toArray());
    }

    public function testToArrayReturnsAssociativeKeys(): void
    {
        $class = $this->createCollection(['foo' => 'a', 'bar' => 'b']);

        $this->assertSame(['foo' => 'a', 'bar' => 'b'], $class->toArray());
    }

    public function testToArrayReturnsCopyNotReference(): void
    {
        $class = $this->createCollection(['a', 'b', 'c']);
        $array = $class->toArray();

        $array[] = 'd';

        $this->assertSame(['a', 'b', 'c'], $class->toArray());
    }

    public function testJsonSerializeReturnsArray(): void
    {
        $class = $this->createCollection(['a', 'b', 'c']);

        $result = $class->jsonSerialize();

        $this->assertIsArray($result);
        $this->assertSame(['a', 'b', 'c'], $result);
    }

    public function testJsonSerializeReturnsToArrayResult(): void
    {
        $class = $this->createCollection(['foo' => 'bar']);

        $this->assertSame($class->toArray(), $class->jsonSerialize());
    }

    public function testDebugInfoReturnsItemsAndTotal(): void
    {
        $class = $this->createCollection(['a', 'b', 'c']);

        $info = $class->__debugInfo();

        $this->assertArrayHasKey('items', $info);
        $this->assertArrayHasKey('total', $info);
        $this->assertSame(['a', 'b', 'c'], $info['items']);
        $this->assertSame(3, $info['total']);
    }

    public function testDebugInfoForEmptyCollection(): void
    {
        $class = $this->createCollection([]);

        $info = $class->__debugInfo();

        $this->assertSame([], $info['items']);
        $this->assertSame(0, $info['total']);
    }

    public function testGetIteratorReturnsArrayIterator(): void
    {
        $class = $this->createCollection(['a', 'b', 'c']);

        $iterator = $class->getIterator();

        $this->assertInstanceOf(\ArrayIterator::class, $iterator);
    }

    public function testGetIteratorIteratesAllItems(): void
    {
        $class = $this->createCollection(['a', 'b', 'c']);

        $iterator = $class->getIterator();
        $items = [];

        foreach ($iterator as $key => $value) {
            $items[$key] = $value;
        }

        $this->assertSame(['a', 'b', 'c'], $items);
    }

    public function testGetIteratorPreservesKeys(): void
    {
        $class = $this->createCollection(['foo' => 'a', 'bar' => 'b']);

        $iterator = $class->getIterator();
        $items = [];

        foreach ($iterator as $key => $value) {
            $items[$key] = $value;
        }

        $this->assertSame(['foo' => 'a', 'bar' => 'b'], $items);
    }

    private function createCollection(iterable $input): TestableCollection
    {
        return TestableCollection::collect($input);
    }
}

class TestableCollection
{
    use CollectionTrait;
}
