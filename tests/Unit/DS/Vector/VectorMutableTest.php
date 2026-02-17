<?php

declare(strict_types=1);

namespace PlanB\Tests\Unit\DS\Vector;

use PHPUnit\Framework\TestCase;
use PlanB\DS\Vector\VectorMutable;

/**
 * @internal
 *
 * @coversNothing
 */
class VectorMutableTest extends TestCase
{
    public function testCollectCreatesInstanceFromArray(): void
    {
        $vector = $this->createVectorMutable(['a', 'b', 'c']);

        $this->assertInstanceOf(VectorMutable::class, $vector);
        $this->assertSame(3, $vector->count());
    }

    public function testAddAppendsValue(): void
    {
        $vector = $this->createVectorMutable(['a', 'b']);

        $result = $vector->add('c');

        $this->assertSame(['a', 'b', 'c'], $vector->toArray());
        $this->assertSame($vector, $result);
    }

    public function testAddReturnsThisForChaining(): void
    {
        $vector = $this->createVectorMutable();

        $result = $vector->add('a')->add('b')->add('c');

        $this->assertSame(['a', 'b', 'c'], $vector->toArray());
        $this->assertSame($vector, $result);
    }

    public function testAddAllAppendsAllValues(): void
    {
        $vector = $this->createVectorMutable(['a', 'b']);

        $result = $vector->addAll(['c', 'd']);

        $this->assertSame(['a', 'b', 'c', 'd'], $vector->toArray());
    }

    public function testAddAllWithIterator(): void
    {
        $vector = $this->createVectorMutable(['a']);
        $iterator = new \ArrayIterator(['b', 'c']);

        $vector->addAll($iterator);

        $this->assertSame(['a', 'b', 'c'], $vector->toArray());
    }

    public function testInsertAddsValuesAtIndex(): void
    {
        $vector = $this->createVectorMutable(['a', 'd']);

        $result = $vector->insert(1, 'b', 'c');

        $this->assertSame(['a', 'b', 'c', 'd'], $vector->toArray());
    }

    public function testInsertAtBeginning(): void
    {
        $vector = $this->createVectorMutable(['b', 'c']);

        $vector->insert(0, 'a');

        $this->assertSame(['a', 'b', 'c'], $vector->toArray());
    }

    public function testInsertAtEnd(): void
    {
        $vector = $this->createVectorMutable(['a', 'b']);

        $vector->insert(2, 'c');

        $this->assertSame(['a', 'b', 'c'], $vector->toArray());
    }

    public function testSetUpdatesValueAtIndex(): void
    {
        $vector = $this->createVectorMutable(['a', 'b', 'c']);

        $result = $vector->set(1, 'x');

        $this->assertSame(['a', 'x', 'c'], $vector->toArray());
        $this->assertSame($vector, $result);
    }

    public function testRemoveDeletesValueAtIndex(): void
    {
        $vector = $this->createVectorMutable(['a', 'b', 'c']);

        $result = $vector->remove(1);

        $this->assertSame(['a', 'c'], $vector->toArray());
    }

    public function testRemoveReindexesArray(): void
    {
        $vector = $this->createVectorMutable(['a', 'b', 'c']);

        $vector->remove(0);

        $this->assertSame([0 => 'b', 1 => 'c'], $vector->toArray());
    }

    public function testRemoveValueRemovesFirstOccurrence(): void
    {
        $vector = $this->createVectorMutable(['a', 'b', 'a', 'c']);

        $result = $vector->removeValue('a');

        $this->assertSame(['b', 'a', 'c'], array_values($vector->toArray()));
    }

    public function testRemoveValueUsesStrictComparison(): void
    {
        $vector = $this->createVectorMutable(['1', 1, '2']);

        $vector->removeValue('1');

        $this->assertSame([1, '2'], array_values($vector->toArray()));
    }

    public function testChainingMutableOperations(): void
    {
        $vector = $this->createVectorMutable();

        $vector->add('a')->add('b')->insert(1, 'x')->set(0, 'z');

        $this->assertSame(['z', 'x', 'b'], $vector->toArray());
    }

    public function testImmutableOperationsReturnNewInstance(): void
    {
        $vector = $this->createVectorMutable(['a', 'b', 'c']);

        $mapped = $vector->map(fn ($v) => strtoupper($v));

        $this->assertSame(['A', 'B', 'C'], $mapped->toArray());
        $this->assertSame(['a', 'b', 'c'], $vector->toArray());
    }

    private function createVectorMutable(array $items = []): VectorMutable
    {
        return VectorMutable::collect($items);
    }
}
