# Spec: Set Data Structure

## Context
A Set is an immutable collection of unique values. It enforces uniqueness and provides set operations like union, intersection, and difference.

**SemVer Impact**: MINOR (new feature, backward compatible)

---

## [Interface] SetInterface
*Extends: `CollectionInterface`*
- [x] `union(SetInterface $other): static`
- [x] `intersect(SetInterface $other): static`


## [Interface] SetMutableInterface
*Extends: `SetInterface`*
- [x] `add(mixed $value): static`
- [x] `addAll(iterable $input): static`
- [x] `remove(mixed $value): static`
- [x] `clear(): static`

---

## [class] Set (Immutable)
*Extends: `Collection`*
*Implements: `SetInterface`*
- [x] `union(SetInterface $other): static` - Union of two sets
- [x] `intersect(SetInterface $other): static` - Intersection of two sets

## [class] SetMutable (Mutable)
*Extends: `Set`*
*Implements: `SetMutableInterface`*
- [x] `add(mixed $value): static` - Adds value to the set (mutates)
- [x] `addAll(iterable $input): static` - Adds all values (mutates)
- [x] `remove(mixed $value): static` - Removes value from set (mutates)
- [x] `clear(): static` - Removes all values (mutates)

---

## Notes
- Set uses value equality for uniqueness (similar to PHP's array_unique)
- Factory method: `Set::collect(iterable $input)` - creates Set from iterable, ensuring uniqueness
- SetMutable factory: `SetMutable::collect(iterable $input)` - creates Set from iterable, ensuring uniqueness
