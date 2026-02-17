<?php

declare(strict_types=1);

namespace PlanB\Resources\DS\Helper;

use PlanB\DS\Map\Map;
use PlanB\DS\Map\MapMutable;
use PlanB\DS\Queue\PriorityQueue;
use PlanB\DS\Queue\PriorityQueueMutable;
use PlanB\DS\Queue\Queue;
use PlanB\DS\Queue\QueueMutable;
use PlanB\DS\Set\Set;
use PlanB\DS\Set\SetMutable;
use PlanB\DS\Vector\Vector;
use PlanB\DS\Vector\VectorMutable;

/**
 * @template TInput
 * @template TValue
 *
 * @param iterable<array-key, TInput>                $input
 * @param null|(callable(TInput, array-key): TValue) $normalizer
 *
 * @return Vector<TValue>
 */
function vector(iterable $input, ?callable $normalizer = null): Vector
{
    /** @var Vector<TValue> */
    return Vector::collect($input, $normalizer);
}

/**
 * @template TInput
 * @template TValue
 *
 * @param iterable<array-key,TInput>                 $input
 * @param null|(callable(TInput, array-key): TValue) $normalizer
 *
 * @return VectorMutable<TValue>
 */
function vectorMutable(iterable $input, ?callable $normalizer = null): VectorMutable
{
    /** @var VectorMutable<TValue> */
    return VectorMutable::collect($input, $normalizer);
}

/**
 * @template TInput
 * @template TValue
 *
 * @param iterable<array-key, TInput>                $input
 * @param null|(callable(TInput, array-key): TValue) $normalizer
 *
 * @return Map<TValue>
 */
function map(iterable $input, ?callable $normalizer = null): Map
{
    /** @var Map<TValue> */
    return Map::collect($input, $normalizer);
}

/**
 * @template TInput
 * @template TValue
 *
 * @param iterable<array-key, TInput>                $input
 * @param null|(callable(TInput, array-key): TValue) $normalizer
 *
 * @return MapMutable<TValue>
 */
function mapMutable(iterable $input, ?callable $normalizer = null): MapMutable
{
    /** @var MapMutable<TValue> */
    return MapMutable::collect($input, $normalizer);
}

/**
 * @template TInput
 * @template TValue
 *
 * @param iterable<array-key,TInput>                 $input
 * @param null|(callable(TInput, array-key): TValue) $normalizer
 *
 * @return Set<TValue>
 */
function set(iterable $input, ?callable $normalizer = null): Set
{
    /** @var Set<TValue> */
    return Set::collect($input, $normalizer);
}

/**
 * @template TInput
 * @template TValue
 *
 * @param iterable<array-key,TInput>                 $input
 * @param null|(callable(TInput, array-key): TValue) $normalizer
 *
 * @return SetMutable<TValue>
 */
function setMutable(iterable $input, ?callable $normalizer = null): SetMutable
{
    /** @var SetMutable<TValue> */
    return SetMutable::collect($input, $normalizer);
}

/**
 * @template TInput
 * @template TValue
 *
 * @param iterable<array-key,TInput>                 $input
 * @param null|(callable(TInput, array-key): TValue) $normalizer
 *
 * @return Queue<TValue>
 */
function queue(iterable $input, ?callable $normalizer = null): Queue
{
    /** @var Queue<TValue> */
    return Queue::collect($input, $normalizer);
}

/**
 * @template TInput
 * @template TValue
 *
 * @param iterable<array-key,TInput>                 $input
 * @param null|(callable(TInput, array-key): TValue) $normalizer
 *
 * @return QueueMutable<TValue>
 */
function queueMutable(iterable $input, ?callable $normalizer = null): QueueMutable
{
    /** @var QueueMutable<TValue> */
    return QueueMutable::collect($input, $normalizer);
}

/**
 * @template TInput
 * @template TValue
 *
 * @param iterable<array-key,TInput>                 $input
 * @param null|(callable(TInput, array-key): TValue) $normalizer
 *
 * @return PriorityQueue<TValue>
 */
function priorityQueue(iterable $input, ?callable $normalizer = null): PriorityQueue
{
    /** @var PriorityQueue<TValue> */
    return PriorityQueue::collect($input, $normalizer);
}

/**
 * @template TInput
 * @template TValue
 *
 * @param iterable<array-key,TInput>                 $input
 * @param null|(callable(TInput, array-key): TValue) $normalizer
 *
 * @return PriorityQueueMutable<TValue>
 */
function priorityQueueMutable(iterable $input, ?callable $normalizer = null): PriorityQueueMutable
{
    /** @var PriorityQueueMutable<TValue> */
    return PriorityQueueMutable::collect($input, $normalizer);
}
