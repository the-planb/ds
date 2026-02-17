# Task: queue-data-structure

**Type**: feat | **Scope**: queue | **Risk**: MINOR

## 1. Objective
Create a FIFO (First-In-First-Out) Queue data structure following the existing collection patterns in the codebase.

## 2. Architectural Roadmap

```php
// Immutable Queue
interface QueueInterface extends CollectionInterface
{
    public function enqueue(mixed $value): static;
    public function dequeue(): mixed;
    public function peek(): mixed;
}

// Mutable Queue (extends Immutable)
interface QueueMutableInterface extends QueueInterface
{
    public function push(mixed $value): static;
    public function shift(): mixed;
}

class Queue implements QueueInterface { }
class QueueMutable implements QueueMutableInterface { }
```

## 3. Implementation Roadmap & Atomic Commits

- [x] **Commit: feat(queue): define QueueInterface**
    - [x] Task: Create interface/contract + DocBlocks.

- [x] **Commit: feat(queue): define QueueMutableInterface**
    - [x] Task: Create mutable interface/contract + DocBlocks.

- [x] **Commit: feat(queue): implement Queue**
    - [x] **Task**: Unit Tests for Queue (Red).
    - [x] **Task**: Logic implementation + DocBlocks (Green).

- [x] **Commit: feat(queue): implement QueueMutable**
    - [x] **Task**: Unit Tests for QueueMutable (Red).
    - [x] **Task**: Logic implementation + DocBlocks (Green).

## 4. Definition of Done
- [x] **Atomicity**: Commits are focused; no mixed functionalities.
- [x] **Documentation**: All new/modified methods/classes have DocBlocks.
- [x] **Test Coverage**: All new logic is covered by tests in the same commit.
- [x] **Green State**: All tests passing.
- [x] **Quality Gates**: Static analysis and linting passed.
