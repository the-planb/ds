# Task: priority-queue

**Type**: feat | **Scope**: queue | **Risk**: MINOR

## 1. Objective
Create a Priority Queue data structure where elements are dequeued based on priority (highest priority first).

## 2. Architectural Roadmap

```php
interface PriorityQueueInterface extends CollectionInterface
{
    public function enqueue(mixed $value, int $priority = 0): static;
    public function dequeue(): mixed;
    public function peek(): mixed;
}

interface PriorityQueueMutableInterface extends PriorityQueueInterface
{
    public function push(mixed $value, int $priority = 0): static;
    public function shift(): mixed;
}

class PriorityQueue implements PriorityQueueInterface { }
class PriorityQueueMutable implements PriorityQueueMutableInterface { }
```

## 3. Implementation Roadmap & Atomic Commits

- [x] **Commit: feat(queue): define PriorityQueueInterface**
    - [x] Task: Create interface/contract + DocBlocks.

- [x] **Commit: feat(queue): define PriorityQueueMutableInterface**
    - [x] Task: Create mutable interface/contract + DocBlocks.

- [x] **Commit: feat(queue): implement PriorityQueue**
    - [x] **Task**: Unit Tests for PriorityQueue (Red).
    - [x] **Task**: Logic implementation + DocBlocks (Green).

- [x] **Commit: feat(queue): implement PriorityQueueMutable**
    - [x] **Task**: Unit Tests for PriorityQueueMutable (Red).
    - [x] **Task**: Logic implementation + DocBlocks (Green).

## 4. Definition of Done
- [x] **Atomicity**: Commits are focused; no mixed functionalities.
- [x] **Documentation**: All new/modified methods/classes have DocBlocks.
- [x] **Test Coverage**: All new logic is covered by tests in the same commit.
- [x] **Green State**: All tests passing.
- [x] **Quality Gates**: Static analysis and linting passed.
