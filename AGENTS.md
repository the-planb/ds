# PlanB/DS Agent Guide

## Overview
**PHP 8.5+** lib for Data Structures. Namespace: `PlanB\DS\`, Tests: `PlanB\Tests\Unit\DS\`.

## Skills
**PHP coding standards**: use skill `planb-php-style`
**Testing**: use skill `planb-php-tests`
**Workflow**: use skill `planb-workflow`
**Action Plans**: use skill `planb-plan-task`

## Commands
- **Tests Unitarios**: `make tests/unit` (supports args, e.g. `--filter <Name>`)
- **Coverage**: `make tests/coverage`
- **Analysis**: `make analysis`

## Architecture
See `specs/task/collection.md`
- **Collections**: Immutable (`Vector`, `Map`) & Mutable (`VectorMutable`, `MapMutable`).
- **Behavior**: Immutable returns **new instance**. Mutable returns `$this`.
- **Traits**:
    - `InternalTrait`: `$items`, factory `collect()`.
    - `CollectionTrait`: `Countable`, `IteratorAggregate`, `JsonSerializable`.
    - `SearchTrait`, `TransformTrait`, `SliceTrait`: Functional methods.
- **Signatures**: Chainable/Factory return `static`.
