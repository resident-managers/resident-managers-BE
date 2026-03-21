# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

Laravel 11 + Lighthouse GraphQL API cho hệ thống quản lý dân cư (resident management). API-only backend, không có frontend — client là mobile app (Flutter). Authentication dùng Laravel Passport (Bearer token).

## Common Commands

```bash
# Run all tests
php artisan test

# Run a single test file
php artisan test tests/Feature/AuthTest.php

# Run a single test method
php artisan test --filter test_user_can_login_via_graphql

# Validate GraphQL schema
php artisan lighthouse:validate-schema

# Print compiled schema
php artisan lighthouse:print-schema

# Clear Lighthouse schema cache (required after schema changes in production)
php artisan lighthouse:clear-schema-cache

# Code formatting (Laravel Pint)
./vendor/bin/pint

# Run migrations
php artisan migrate

# Tinker
php artisan tinker
```

## Architecture

### GraphQL Layer (`graphql/` + `app/GraphQL/`)

- **Entry schema:** `graphql/schema.graphql` — defines root types, auth mutations, imports model schemas via `#import models/*.graphql` và `#import admin/*.graphql`
- **Model schemas:** `graphql/models/*.graphql` — one file per domain entity (resident, household, health_insurance, social_insurance, temporary_residence, temporary_absence)
- **Admin schemas:** `graphql/admin/*.graphql` — mutations chỉ dành cho admin (e.g. `users.graphql`)
- **Mutations:** `app/GraphQL/Mutations/` — grouped by domain subfolder: `Auth/`, `Users/`, `Residents/`, `Households/`, `HealthInsurance/`, `SocialInsurance/`, `TemporaryResidence/`, `TemporaryAbsence/`. Mỗi class: `final readonly class`, single `__invoke(null $_, array $args): array`
- **Queries:** `app/GraphQL/Queries/` — same pattern
- **Validators:** `app/GraphQL/Validators/` — Lighthouse validator classes for input validation, one per create/update operation. Input dùng `@validator(class: "FooInputValidator")` + `@spread` trên mutation arg
- **Enum registration:** `app/GraphQL/GraphQLServiceProvider.php` — PHP-backed enums (BenSampo) are registered as `LaravelEnumType` into Lighthouse's TypeRegistry

### Authentication Flow

- `POST /graphql` với `login` mutation → Passport `access_token` (guard `api`, model `User`)
- `POST /graphql` với `adminLogin` mutation → Passport `access_token` (guard `admin`, model `Admin`)
- All guarded operations require `Authorization: Bearer <token>` header
- `extend type Mutation @guard` / `extend type Query @guard` — bảo vệ toàn bộ block. Dùng `@guard(with: ["admin"])` để restrict về guard cụ thể
- Password reset: `forgotPassword` + `resetPassword` mutations (không dùng Laravel's built-in reset routes) — token gửi qua email (Mailpit local, port 1025), hiển thị token trong email để user nhập vào app

### Models

All models use UUIDs (`HasUuids`). Key relationships:
- `Household` hasMany `Resident` (via `household_id`)
- `Resident` hasOne each of `HealthInsurance`, `SocialInsurance`, `TemporaryResidence`, `TemporaryAbsence`
- `User` — guard `api`, table `users`, dùng `HasRoles` (Spatie). Password cast as `hashed` — dùng `forceFill(['password' => Hash::make($pw)])` khi reset để tránh double-hash
- `Admin` — guard `admin`, table `admins`, dùng `HasRoles` (Spatie). Model riêng biệt hoàn toàn với `User`

### Authorization (Spatie Permission)

Hai guard riêng biệt, hai role set riêng biệt:

| Guard   | Model   | Role    | Permission guard |
|---------|---------|---------|-----------------|
| `api`   | `User`  | `user`  | `api`           |
| `admin` | `Admin` | `admin` | `admin`         |

- **`@hasPermission(name: "...")`** — custom directive tại `app/GraphQL/Directives/HasPermissionDirective.php`. Dùng cho mọi query/mutation cần kiểm tra quyền
- **`@hasRole(name: "...")`** — `app/GraphQL/Directives/HasRoleDirective.php`. Ít dùng hơn, chủ yếu kết hợp `@guard` + `@hasPermission`
- Khi thêm mutation admin-only: dùng `@guard(with: ["admin"])` + `@hasPermission(name: "...")`
- Permission naming: `"$action $resource"` — e.g. `"view residents"`, `"create health insurance"`, `"delete temporary absence"`
- Seed: `PermissionSeeder` → `AdminSeeder` → `UserSeeder` (thứ tự bắt buộc)
- Seed accounts: `admin@quan-ly-dan-cu.local` (Admin model, guard admin), `test@example.com` (User model, guard api) — cả hai password `password`

**Thêm permission mới:**
1. Thêm vào `PermissionSeeder` đúng guard (`api` hoặc `admin`)
2. Đặt `@hasPermission(name: "...")` lên field trong `.graphql`

**Gán role (phải chỉ rõ guard):**
```php
$user->assignRole(Role::findByName('user', 'api'));
$admin->assignRole(Role::findByName('admin', 'admin'));
```

### Enums (BenSampo)

PHP-backed enums in `app/Enums/`: `HouseholdRelationship`, `ResidenceType`, `InsuranceType`, `SocialInsuranceStatus`. Phải đăng ký vào Lighthouse TypeRegistry trong `GraphQLServiceProvider`.

### Testing Pattern

Feature tests dùng `MakesGraphQLRequests` (Lighthouse) + `RefreshDatabase`. `RefreshDatabase` wipes toàn bộ DB sau mỗi test, kể cả permissions/roles — phải seed lại trong `setUp()`.

**Dùng trait `Tests\CreatesAuthenticatedUser`** cho các test cần authenticated user (guard `api`):
```php
use Tests\CreatesAuthenticatedUser;

class FooTest extends TestCase
{
    use MakesGraphQLRequests, RefreshDatabase, CreatesAuthenticatedUser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpAuthenticatedUser(); // seeds permissions + creates user with 'user' role
    }
    // $this->token và $this->auth() có sẵn
}
```

Trait này tự động: tạo Passport client, chạy `PermissionSeeder`, tạo `User` factory, gán role `user` (guard `api`), set `$this->token`.

GraphQL request:
```php
$this->graphQL('mutation { ... }');
$this->auth()->graphQL('query { ... }'); // authenticated
$this->withHeaders(['Authorization' => 'Bearer '.$token])->graphQL('...');
```

**Lưu ý:** Khi gán role trong test phải chỉ rõ guard:
```php
$user->assignRole(Role::findByName('user', 'api'));
```

### Database

MariaDB (production). SQLite in-memory for tests (configured in `phpunit.xml`). Bảng `password_reset_tokens` có sẵn từ Laravel default migrations.

### Mail (Local Dev)

Mailpit — SMTP port 1025, web UI http://localhost:8025. Template: `resources/views/emails/reset-password.blade.php`. JavaScript trong email bị Mailpit sandbox block — dùng "Open in browser" để test copy button.
