<?php

declare(strict_types=1);

namespace PlanB\Tests\Unit\DS\Traits;

use PHPUnit\Framework\TestCase;
use PlanB\DS\Traits\SliceTrait;

/**
 * @internal
 *
 * @coversNothing
 */
class SliceTraitTest extends TestCase
{
    public function testTakeReturnsFirstNElements(): void
    {
        $collection = $this->createCollection([1, 2, 3, 4, 5]);

        $result = $collection->take(3);

        $this->assertSame([1, 2, 3], $result->toArray());
    }

    public function testTakeReturnsAllWhenLimitExceedsCount(): void
    {
        $collection = $this->createCollection([1, 2, 3]);

        $result = $collection->take(10);

        $this->assertSame([1, 2, 3], $result->toArray());
    }

    public function testTakeReturnsEmptyWhenLimitIsZero(): void
    {
        $collection = $this->createCollection([1, 2, 3]);

        $result = $collection->take(0);

        $this->assertSame([], $result->toArray());
    }

    public function testTakePreservesKeys(): void
    {
        $collection = $this->createCollection(['a' => 1, 'b' => 2, 'c' => 3]);

        $result = $collection->take(2);

        $this->assertSame(['a' => 1, 'b' => 2], $result->toArray());
    }

    public function testDropRemovesFirstNElements(): void
    {
        $collection = $this->createCollection([1, 2, 3, 4, 5]);

        $result = $collection->drop(2);

        $this->assertSame([3, 4, 5], $result->toArray());
    }

    public function testDropReturnsEmptyWhenLimitExceedsCount(): void
    {
        $collection = $this->createCollection([1, 2, 3]);

        $result = $collection->drop(10);

        $this->assertSame([], $result->toArray());
    }

    public function testDropReturnsAllWhenLimitIsZero(): void
    {
        $collection = $this->createCollection([1, 2, 3]);

        $result = $collection->drop(0);

        $this->assertSame([1, 2, 3], $result->toArray());
    }

    public function testDropPreservesKeys(): void
    {
        $collection = $this->createCollection(['a' => 1, 'b' => 2, 'c' => 3]);

        $result = $collection->drop(1);

        $this->assertSame(['b' => 2, 'c' => 3], $result->toArray());
    }

    public function testTakeWhileReturnsElementsWhileConditionIsTrue(): void
    {
        $collection = $this->createCollection([1, 2, 3, 4, 5]);

        $result = $collection->takeWhile(fn ($v) => $v < 3);

        $this->assertSame([1, 2], $result->toArray());
    }

    public function testTakeWhileReturnsEmptyWhenFirstElementFails(): void
    {
        $collection = $this->createCollection([1, 2, 3]);

        $result = $collection->takeWhile(fn ($v) => $v < 0);

        $this->assertSame([], $result->toArray());
    }

    public function testTakeWhileReturnsAllWhenAllPass(): void
    {
        $collection = $this->createCollection([1, 2, 3]);

        $result = $collection->takeWhile(fn ($v) => $v > 0);

        $this->assertSame([1, 2, 3], $result->toArray());
    }

    public function testTakeWhileProvidesKeyToCallback(): void
    {
        $collection = $this->createCollection(['a' => 1, 'b' => 2, 'c' => 3]);

        $result = $collection->takeWhile(fn ($v, $k) => $k !== 'b');

        $this->assertSame(['a' => 1], $result->toArray());
    }

    public function testDropWhileSkipsElementsWhileConditionIsTrue(): void
    {
        $collection = $this->createCollection([1, 2, 3, 4, 5]);

        $result = $collection->dropWhile(fn ($v) => $v < 3);

        $this->assertSame([3, 4, 5], $result->toArray());
    }

    public function testDropWhileReturnsAllWhenAllMatch(): void
    {
        $collection = $this->createCollection([1, 2, 3]);

        $result = $collection->dropWhile(fn ($v) => $v > 0);

        $this->assertSame([], $result->toArray());
    }

    public function testDropWhileReturnsAllWhenNoneMatch(): void
    {
        $collection = $this->createCollection([1, 2, 3]);

        $result = $collection->dropWhile(fn ($v) => $v < 0);

        $this->assertSame([1, 2, 3], $result->toArray());
    }

    public function testDropWhileProvidesKeyToCallback(): void
    {
        $collection = $this->createCollection(['a' => 1, 'b' => 2, 'c' => 3]);

        $result = $collection->dropWhile(fn ($v, $k) => $k !== 'b');

        $this->assertSame(['b' => 2, 'c' => 3], $result->toArray());
    }

    public function testTakeOnEmptyCollection(): void
    {
        $collection = $this->createCollection([]);

        $result = $collection->take(3);

        $this->assertSame([], $result->toArray());
    }

    public function testDropOnEmptyCollection(): void
    {
        $collection = $this->createCollection([]);

        $result = $collection->drop(3);

        $this->assertSame([], $result->toArray());
    }

    public function testTakeWhileOnEmptyCollection(): void
    {
        $collection = $this->createCollection([]);

        $result = $collection->takeWhile(fn ($v) => true);

        $this->assertSame([], $result->toArray());
    }

    public function testDropWhileOnEmptyCollection(): void
    {
        $collection = $this->createCollection([]);

        $result = $collection->dropWhile(fn ($v) => false);

        $this->assertSame([], $result->toArray());
    }

    public function testTakeReturnsNewInstance(): void
    {
        $collection = $this->createCollection([1, 2, 3]);
        $original = $collection->toArray();

        $collection->take(2);

        $this->assertSame($original, $collection->toArray());
    }

    public function testDropReturnsNewInstance(): void
    {
        $collection = $this->createCollection([1, 2, 3]);
        $original = $collection->toArray();

        $collection->drop(2);

        $this->assertSame($original, $collection->toArray());
    }

    public function testTakeWhileReturnsNewInstance(): void
    {
        $collection = $this->createCollection([1, 2, 3]);
        $original = $collection->toArray();

        $collection->takeWhile(fn ($v) => true);

        $this->assertSame($original, $collection->toArray());
    }

    public function testDropWhileReturnsNewInstance(): void
    {
        $collection = $this->createCollection([1, 2, 3]);
        $original = $collection->toArray();

        $collection->dropWhile(fn ($v) => false);

        $this->assertSame($original, $collection->toArray());
    }

    private function createCollection(iterable $input): TestableSlice
    {
        return TestableSlice::collect($input);
    }
}

class TestableSlice
{
    use SliceTrait;
}
