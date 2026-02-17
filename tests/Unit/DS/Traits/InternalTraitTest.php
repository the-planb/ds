<?php

declare(strict_types=1);

namespace PlanB\Tests\Unit\DS\Traits;

use PHPUnit\Framework\TestCase;
use PlanB\DS\Traits\InternalTrait;

/**
 * @internal
 *
 * @coversNothing
 */
class InternalTraitTest extends TestCase
{
    public function testCollectCreatesInstanceFromArray(): void
    {
        $instance = $this->createTestable(['a', 'b', 'c']);

        $this->assertInstanceOf(TestableInternal::class, $instance);
        $this->assertSame(['a', 'b', 'c'], $instance->internal());
    }

    public function testCollectCreatesInstanceFromEmptyArray(): void
    {
        $instance = $this->createTestable([]);

        $this->assertSame([], $instance->internal());
    }

    public function testCollectCreatesInstanceFromIterator(): void
    {
        $iterator = new \ArrayIterator(['x', 'y', 'z']);
        $instance = $this->createTestable($iterator);

        $this->assertSame(['x', 'y', 'z'], $instance->internal());
    }

    public function testCollectAppliesNormalizerToValues(): void
    {
        $instance = $this->createTestable(['a', 'b', 'c'], fn ($value) => strtoupper($value));

        $this->assertSame(['A', 'B', 'C'], $instance->internal());
    }

    public function testCollectAppliesNormalizerWithKeys(): void
    {
        $instance = $this->createTestable(['first' => 'a', 'second' => 'b'], fn ($value, $key) => "{$key}_{$value}");

        $this->assertSame(['first_a', 'second_b'], $instance->internal());
    }

    public function testCollectWithGenerator(): void
    {
        $generator = function () {
            yield 'x';

            yield 'y';
        };
        $instance = $this->createTestable($generator());

        $this->assertSame(['x', 'y'], $instance->internal());
    }

    public function testInternalReturnsItemsArray(): void
    {
        $instance = $this->createTestable(['foo' => 'bar']);

        $this->assertSame(['foo' => 'bar'], $instance->internal());
    }

    private function createTestable(iterable $input, ?callable $normalizer = null): TestableInternal
    {
        return TestableInternal::collect($input, $normalizer);
    }
}

class TestableInternal
{
    use InternalTrait;

    public function internal(): array
    {
        return $this->items;
    }
}
