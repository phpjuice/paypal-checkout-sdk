# AGENTS.md

This file helps agentic coding tools work effectively in this repository.

## Scope and intent
- This is a PHP SDK for PayPal Checkout.
- Primary code lives in `src/`; tests live in `tests/`.
- CI runs on PHP 8.1, 8.2, 8.3.

## Build, lint, test

### Install dependencies
- `composer install`

### Build
- No dedicated build step is defined for this library.

### Lint/format
- `composer pint` (Laravel Pint; formatting)
- `./vendor/bin/pint` (same as above)

### Static analysis
- `composer analyse` (PHPStan, level max)
- `phpstan analyse --ansi --debug` (direct)

### Test
- `composer test` (Pest)
- `vendor/bin/pest`

### Run a single test
- `vendor/bin/pest tests/Orders/OrderTest.php`
- `vendor/bin/pest --filter "can initialize an order"`
- `vendor/bin/pest tests/Requests/OrderCreateRequestTest.php --filter "has correct request uri"`

### Test configuration notes
- PHPUnit config: `phpunit.xml`
- Strict settings are enabled (warnings/risky tests fail the run).

## Repository rules

### Cursor/Copilot rules
- No `.cursor/rules/`, `.cursorrules`, or `.github/copilot-instructions.md` found.

### Contributing requirements (from `CONTRIBUTING.md`)
- Follow PSR-2 coding standard.
- Add tests for changes.
- Update docs for behavior changes.
- Keep commits meaningful and squash before PR if needed.

## Code style and conventions

### Formatting
- Indent with 4 spaces (see `.editorconfig`).
- Use LF line endings and final newline.
- Keep trailing whitespace trimmed (except Markdown).
- Follow PSR-2 layout rules.

### Namespaces and imports
- Use `PayPal\Checkout\...` namespaces for production code.
- Place `use` statements after `namespace` with a blank line between.
- Prefer alphabetical ordering of imports when adding new ones.

### Naming
- Classes: `PascalCase` (e.g., `OrderCreateRequest`).
- Methods/properties/variables: `camelCase`.
- Constants: `UPPER_SNAKE_CASE` (e.g., `CAPTURE`, `AUTHORIZE`).
- Tests: Pest `it('does something', function () { ... })` with readable names.

### Types and docblocks
- Use typed properties and return types where possible.
- Use docblocks for array element types and readonly notes.
- Interfaces: `Arrayable`, `Jsonable` for objects that serialize.

### Serialization
- Objects implement `toArray()` and `toJson()` via `CastsToJson`.
- Throw `JsonEncodingException` on JSON errors.
- Payload keys are snake_case to match PayPal API.

### Error handling
- Validate inputs early and throw domain exceptions in `src/Exceptions/`.
- Prefer specific exceptions (e.g., `InvalidOrderIntentException`).
- Messages should be explicit and user-facing when appropriate.

### Requests
- Request classes extend `PayPal\Http\PaypalRequest`.
- Use explicit headers and JSON body streams.
- Avoid side effects in request constructors beyond setup.

### Collections and helpers
- `HasCollection` and similar concerns encapsulate list logic.
- Use array_map and small helpers for array transformations.

### Tests
- Use Pest expectations (`expect()->toBe(...)`, etc.).
- Use helper functions in `tests/Pest.php` for common fixtures.
- Keep tests deterministic and JSON comparisons explicit.

## Suggested workflow for changes
- Add or update tests in `tests/` when behavior changes.
- Run `composer pint` before committing formatting changes.
- Run `composer analyse` for static analysis checks.
- Run `composer test` or targeted `vendor/bin/pest` for fast feedback.

## Key files
- `composer.json`: scripts, dependencies, PHP version support.
- `phpstan.neon`: static analysis config.
- `phpunit.xml`: test configuration and strictness settings.
- `tests/Pest.php`: shared test helpers and setup.
- `CONTRIBUTING.md`: PSR-2 and contribution expectations.
