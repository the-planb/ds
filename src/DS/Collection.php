<?php

declare(strict_types=1);

namespace PlanB\DS;

use PlanB\DS\Traits\CollectionTrait;
use PlanB\DS\Traits\SearchTrait;
use PlanB\DS\Traits\SliceTrait;
use PlanB\DS\Traits\TransformTrait;

/**
 * @template TKey of array-key
 * @template T
 *
 * @implements  CollectionInterface<TKey, T>
 */
abstract class Collection implements CollectionInterface
{
    /**  @use CollectionTrait<TKey, T> */
    use CollectionTrait;

    /**  @use SearchTrait<TKey, T> */
    use SearchTrait;

    /**  @use TransformTrait<TKey, T> */
    use TransformTrait;

    /**  @use SliceTrait<TKey, T> */
    use SliceTrait;
}
