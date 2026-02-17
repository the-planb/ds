<?php

declare(strict_types=1);

namespace PlanB\Tests\Unit\DS\Set;

use PHPUnit\Framework\TestCase;
use PlanB\DS\Set\SetMutable;

/**
 * @internal
 *
 * @coversNothing
 */
class SetMutableTest extends TestCase
{
    public function testCollectCreatesInstanceFromArray(): void
    {
        $set = $this->createSet(['a', 'b', 'c']);

        $this->assertInstanceOf(SetMutable::class, $set);
        $this->assertSame(3, $set->count());
    }

    public function testCollectRemovesDuplicates(): void
    {
        $set = $this->createSet(['a', 'b', 'a', 'c', 'b']);

        $this->assertSame(3, $set->count());
    }

    public function testAddAppendsValue(): void
    {
        $set = $this->createSet(['a', 'b']);

        $result = $set->add('c');

        $this->assertSame($set, $result);
        $this->assertSame(3, $set->count());
        $this->assertTrue($set->hasValue('c'));
    }

    public function testAddDoesNotDuplicate(): void
    {
        $set = $this->createSet(['a', 'b']);

        $set->add('a');

        $this->assertSame(2, $set->count());
    }

    public function testAddReturnsThisForChaining(): void
    {
        $set = $this->createSet(['a']);

        $result = $set->add('b')->add('c');

        $this->assertSame($set, $result);
        $this->assertSame(3, $set->count());
    }

    public function testAddAllAppendsAllValues(): void
    {
        $set = $this->createSet(['a']);

        $result = $set->addAll(['b', 'c']);

        $this->assertSame($set, $result);
        $this->assertSame(3, $set->count());
    }

    public function testAddAllWithIterator(): void
    {
        $set = $this->createSet(['a']);

        $set->addAll(new \ArrayIterator(['b', 'c']));

        $this->assertSame(3, $set->count());
    }

    public function testAddAllDoesNotDuplicate(): void
    {
        $set = $this->createSet(['a', 'b']);

        $set->addAll(['a', 'c']);

        $this->assertSame(3, $set->count());
    }

    public function testRemoveRemovesValue(): void
    {
        $set = $this->createSet(['a', 'b', 'c']);

        $result = $set->remove('b');

        $this->assertSame($set, $result);
        $this->assertSame(2, $set->count());
        $this->assertFalse($set->hasValue('b'));
    }

    public function testRemoveReturnsThisForChaining(): void
    {
        $set = $this->createSet(['a', 'b', 'c']);

        $result = $set->remove('a')->remove('b');

        $this->assertSame($set, $result);
        $this->assertSame(1, $set->count());
    }

    public function testRemoveNonExistingValue(): void
    {
        $set = $this->createSet(['a', 'b']);

        $set->remove('c');

        $this->assertSame(2, $set->count());
    }

    public function testClearRemovesAllValues(): void
    {
        $set = $this->createSet(['a', 'b', 'c']);

        $result = $set->clear();

        $this->assertSame($set, $result);
        $this->assertTrue($set->isEmpty());
    }

    public function testChainingMutableOperations(): void
    {
        $set = $this->createSet(['a']);

        $set->add('b')
            ->add('c')
            ->remove('b')
            ->add('d')
        ;

        $this->assertSame(3, $set->count());
        $this->assertTrue($set->hasValue('a'));
        $this->assertFalse($set->hasValue('b'));
        $this->assertTrue($set->hasValue('c'));
        $this->assertTrue($set->hasValue('d'));
    }

    public function testUnionIsImmutable(): void
    {
        $set1 = $this->createSet(['a', 'b']);
        $set2 = SetMutable::collect(['b', 'c']);

        $result = $set1->union($set2);

        $this->assertNotSame($set1, $result);
        $this->assertSame(2, $set1->count());
        $this->assertSame(3, $result->count());
    }

    public function testIntersectIsImmutable(): void
    {
        $set1 = $this->createSet(['a', 'b', 'c']);
        $set2 = SetMutable::collect(['b']);

        $result = $set1->intersect($set2);

        $this->assertNotSame($set1, $result);
        $this->assertSame(3, $set1->count());
    }

    private function createSet(array $items): SetMutable
    {
        return SetMutable::collect($items);
    }
}
