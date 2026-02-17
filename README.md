# PlanB DS

PHP Data Structures and Collection Utils

## Installation

```bash
composer require planb/ds
```

## Usage

### Collections

```php
use PlanB\DS\Vector\Vector;
use PlanB\DS\Map\Map;
use PlanB\DS\Set\Set;
use PlanB\DS\Queue\Queue;
use PlanB\DS\Queue\PriorityQueue;

// Vector (immutable list with numeric keys)
$vector = Vector::collect([1, 2, 3]);

// Map (associative array with string keys)
$map = Map::collect(['a' => 1, 'b' => 2]);

// Set (unique values)
$set = Set::collect([1, 2, 2, 3]); // [1, 2, 3]

// Queue (FIFO)
$queue = Queue::collect([1, 2, 3]);

// PriorityQueue
$priorityQueue = PriorityQueue::collect([1, 2, 3]);
```

### Mutable Collections

```php
use PlanB\DS\Vector\VectorMutable;
use PlanB\DS\Map\MapMutable;
use PlanB\DS\Set\SetMutable;

$vector = new VectorMutable();
$vector->add(1)->add(2)->add(3);
```

### Helper Functions

```php
use function PlanB\Resources\DS\Helper\vector;
use function PlanB\Resources\DS\Helper\map;
use function PlanB\Resources\DS\Helper\set;
use function PlanB\Resources\DS\Helper\queue;

$vector = vector([1, 2, 3]);
$map = map(['a' => 1, 'b' => 2]);
$set = set(['a', 'b', 'a']); // ['a', 'b']
$queue = queue([1, 2, 3]);
```

With normalizer:

```php
$vector = vector(['a', 'b', 'c'], fn($value) => strtoupper($value));
// ['A', 'B', 'C']
```

## Requirements

- PHP 8.5+

## License

MIT
