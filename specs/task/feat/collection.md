# Spec: Collection Core & Traits (Refactored)

# Spec: Set Data Structure

## Context
A Collection is a wrapper for php array used like first order collection
class Collection is used like base for others structures, like vector, map, queue, priorityQueue or Set

```php
abstract class Collection implements CollectionInterface {
    use InternalTrait;
    use CollectionTrait;
    use SearchTrait;
    use TransformTrait;
    use SliceTrait;
}
```

**SemVer Impact**: MINOR (new feature, backward compatible)

---

## [Interface] CollectionInterface
*Extends: `Countable`, `IteratorAggregate`, `JsonSerializable`*
- [x] `isEmpty(): bool`
- [x] `isNotEmpty(): bool`
- [x] `hasCount(int $total): bool`
- [x] `each(callable $cb): static` (Callback: `fn($val, $key)`)
- [x] `first(): mixed`
- [x] `firstThat(callable $condition): mixed`
- [x] `last(): mixed`
- [x] `lastThat(callable $condition): mixed`
- [x] `take(int $numOfElements): static`
- [x] `drop(int $numOfElements): static`
- [x] `takeWhile(callable $condition): static`
- [x] `dropWhile(callable $condition): static`
- [x] `hasValue(mixed $value): bool`
- [x] `contains(mixed ...$values): bool`
- [x] `find(mixed $value): string|int|null`
- [x] `some(callable $condition): bool`
- [x] `every(callable $condition): bool`
- [x] `filter(?callable $condition = null): static`
- [x] `sort(?callable $comparison = null): static`
- [x] `diff(iterable $input, ?callable $comparison = null): static`
- [x] `unique(): static`
- [x] `reversed(): static`
- [x] `reduce(callable $callback, mixed $initial = null): mixed`
- [x] `flatten(int $depth = PHP_INT_MAX): Vector`
- [x] `flatMap(callable $callback): Vector`
- [x] `shuffle(): static`

---

## [Trait] InternalTrait
- [x] `private array $items`
- [x] `public static function collect(iterable $input, callback $normalizer): static`
- [x] `toArray(): array` 
- [x] `protected function internal(): array`


## [Trait] CollectionTrait
*use: `InternalTrait`*
- [x] `count(): int`
- [x] `isEmpty(): bool`
- [x] `isNotEmpty(): bool`
- [x] `hasCount(int $total): bool`
- [x] `each(callable $callback): static` (Callback: `fn($value, $key)`)
- [x] `jsonSerialize(): array`
- [x] `getIterator(): Traversable`
- [x] `toArray(): array`
- [x] `__debugInfo(): array`

## [Trait] SearchTrait
*use: `InternalTrait`*
- [x] `hasValue(mixed $value): bool`
- [x] `contains(mixed ...$values): bool`
- [x] `first(): mixed`
- [x] `some(callable $condition): bool`
- [x] `every(callable $condition): bool`
- [x] `firstThat(callable $condition): mixed`
- [x] `last(): mixed`
- [x] `lastThat(callable $condition): mixed`
- [x] `find(mixed $value): string|int|null`

## [Trait] TransformTrait
*use: `InternalTrait`*
- [x] `map(callable $callback): static`
- [x] `filter(?callable $condition): static`
- [x] `sort(?callable $comparator): static`
- [x] `reversed(): static`
- [x] `shuffle(): static`
- [x] `unique(): static`
- [x] `flatten(int $depth): Vector`
- [x] `flatMap(callable $callback): Vector`
- [x] `reduce(callable $callback, mixed $initial): mixed`
- [x] `diff(iterable $input, ?callable $comparison = null): static`

## [Trait] SliceTrait
*use: `InternalTrait`*
- [X] `take(int $limit): static`
- [X] `drop(int $limit): static`
- [X] `takeWhile(callable $condition): static`
- [X] `dropWhile(callable $condition): static`

---

## [class] Vector (Immutable)
*use: `CollectionTrait`, `SearchTrait`, `TransformTrait`, `SliceTrait`*
- [X] `get(int $index, mixed $default = null): mixed`
- [X] `hasKey(int $index): bool`
- [X] `hasIndex(int $index): bool` (Alias)

## [class] VectorMutable
*Extends: `Vector`*
- [X] `add(mixed $value): static`
- [X] `addAll(iterable $input): static`
- [X] `insert(int $index, mixed ...$values): static`
- [X] `set(int $index, mixed $value): static`
- [X] `remove(int $index): static`
- [X] `removeValue(mixed $value): static`

---

## [class] Map (Immutable)
*use: `CollectionTrait`, `SearchTrait`, `TransformTrait`, `SliceTrait`*
- [X] `get(string|int $key, mixed $default = null): mixed`
- [X] `hasKey(string|int $key): bool`
- [X] `keys(): Vector`
- [X] `values(): Vector`
- [X] `mapKeys(callable $callback): static`
- [X] `merge(iterable $input): static`
- [X] `keySort(?callable $comparison = null): static`
- [X] `diffKeys(iterable $input, ?callable $comparison = null): static`
- [X] `intersect(iterable $input, ?callable $comparison = null): static`
- [X] `intersectKeys(iterable $input, ?callable $comparison = null): static`

## [class] MapMutable
*Extends: `Map`*
- [X] `put(string $key, mixed $value): static`
- [X] `putAll(iterable $input): static`
- [X] `remove(string $key): static`
- [X] `removeValue(mixed $value): static`