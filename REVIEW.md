# Code Review Guidelines

## Always check

### GraphQL / Lighthouse
- New mutations must have a corresponding `@validator(class: "...")` on the input type
- Every query and mutation field must have `@hasPermission(name: "...")` directive
- New mutations use `@field(resolver: "App\\GraphQL\\Mutations\\...")` — not inline resolvers
- Mutation resolver classes must be `final readonly class` with a single `__invoke(null $_, array $args)` method
- New BenSampo enums must be registered in `GraphQLServiceProvider` via `$typeRegistry->register(new LaravelEnumType(...))`
- `@rename(attribute: "snake_case")` must be applied when GraphQL camelCase field differs from DB column
- Run `php artisan lighthouse:validate-schema` after schema changes

### Authentication & Authorization
- New admin-only operations use `@guard(with: ["admin"])` + `@hasPermission(name: "...")`
- New permissions must be added to `PermissionSeeder` under the correct guard (`api` or `admin`)
- Role assignment must always specify guard: `Role::findByName('role', 'guard')`
- Password reset must use `forceFill(['password' => Hash::make(...)])` — never assign directly to avoid double-hash

### Database
- Migrations must be backward-compatible (no dropping columns or renaming without a rollback plan)
- All new models must use `HasUuids`
- Foreign key relationships must match existing conventions (`household_id` → `Household`)

### Testing
- New feature (query/mutation) must have a corresponding Feature test
- Tests that need an authenticated user must use `CreatesAuthenticatedUser` trait and call `setUpAuthenticatedUser()` in `setUp()`
- Seed order in tests: `PermissionSeeder` must run before role assignment
- `RefreshDatabase` is used — never rely on data persisting between test methods

## Style
- PHP files must start with `<?php declare(strict_types=1);`
- Use Laravel Pint formatting: `./vendor/bin/pint`
- GraphQL files: group sections in order — Query, Mutation, Input, Enum, Type — separated by `#####` comment blocks

## Skip
- `vendor/` directory
- `storage/` directory
- `bootstrap/cache/`
- Lock files (`composer.lock`, `package-lock.json`)
