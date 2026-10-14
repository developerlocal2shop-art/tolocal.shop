# Product Requirements Document

## Title
Incremental Laravel 8 to Laravel 9 Upgrade

## Purpose
Upgrade the existing production-style multi-vendor e-commerce application from Laravel 8 to Laravel 9 on PHP 8.2.12 with the smallest practical dependency and code changes. Preserve the existing application, business behavior, data, and integrations. This is an in-place framework upgrade, not a project rebuild.

## Background
The application includes customer and vendor authentication, admin/vendor panels, product and inventory management, cart and checkout, orders, payment integrations, affiliate features, APIs, imports/exports, mail, queued jobs, notifications, image processing, and scheduled tasks. The production database is MariaDB 10.4.32. The runtime environment is Windows/XAMPP with Composer 2.9.3.

The Laravel 8 manifest supplied for this upgrade differs from the workspace root `composer.json`, which currently declares Laravel `^9.0`. A root `composer.lock` was not discoverable in the workspace. The supplied Laravel 8 manifest is the planning source of truth; before execution, identify the actual Laravel 8 checkout and its matching `composer.lock`. No exact locked dependency resolution has yet been demonstrated. The nested `lara-currency` project is outside this PRD's scope.

## Goals
- Upgrade only from Laravel 8 to Laravel 9; do not move directly to Laravel 10 or later.
- Keep the existing database schema and production data unchanged as part of the framework upgrade.
- Keep the Composer lockfile unchanged until dependency compatibility and the update dry-run have been reviewed.
- Keep all existing customer, vendor, admin, commerce, payment, affiliate, API, mail, queue, storage, and frontend/backend functionality.
- Use a staging copy and focused verification before production deployment.
- Make no dependency replacement without confirmed incompatibility and an evaluated migration path.

## Non-Goals
- Creating a new Laravel application or replacing application architecture.
- Rewriting authentication, vendor roles, payment flows, affiliate logic, APIs, or frontend functionality.
- Changing production database structure or performing unrelated data cleanup.
- Upgrading to Laravel 10, 11, or 12.
- Replacing a package based only on speculation.

## Scope
- Laravel framework and required Laravel first-party package compatibility.
- Composer dependency constraints and the resolved lockfile.
- Laravel 9 migration behavior that can affect application code or runtime configuration.
- Staging tests for existing customer, vendor, admin, payment, API, queue, mail, storage, and affiliate workflows.
- Deployment and rollback procedure using a matched code release and Composer lockfile.

## Proposed Composer Changes
The following are the proposed manifest changes based on package requirements and release metadata. The exact final dependency set is conditional on the actual Laravel 8 lockfile, `composer why-not`, and a successful reviewed dry-run. Do not run an unrestricted `composer update`.

| Package | Proposed constraint/action | Reason |
| --- | --- | --- |
| `php` | `^8.0.2` | Laravel 9 minimum PHP version; supports the stated PHP 8.2.12 runtime. |
| `laravel/framework` | `^9.0` | Requested target framework. |
| `laravel/sanctum` | `^3.0` | Sanctum 2.x is not the Laravel 9 line. Confirm API/token and stateful-auth behavior after update. |
| `laravel/ui` | `^3.4` | Laravel 9-compatible release line while retaining current scaffolding. |
| `laravel/tinker` | `^2.7` | Laravel 9-compatible release line. |
| `nunomaduro/collision` | `^6.1` | Laravel 9-compatible major and Symfony 6-compatible dependency line. |
| `facade/ignition` | Remove | Replaced for Laravel 9 by Spatie's Ignition package. |
| `spatie/laravel-ignition` | Add `^1.0` | Laravel 9-compatible Ignition replacement. |
| `phpunit/phpunit` | Keep on `^9.5` | Laravel 9 supports PHPUnit 9; the existing `^9.3.3` range may be retained if the lock resolves to a compatible release. |
| `renatomarinho/laravel-page-speed` | `^2.1` | Version 2.0.0 does not declare Laravel 9 support; 2.1 adds it. |
| `spatie/laravel-google-calendar` | `^3.5.3` | Version 3.5.0 does not declare Laravel 9 support; 3.5.3 does. |
| `srmklive/paypal` | `^3.0.15` | First verified Laravel 9-compatible 3.x release. Keep the existing API integration and test it in sandbox. |

### Constraints to Keep Unless the Resolver Proves Otherwise
- Keep `barryvdh/laravel-dompdf ^1.0`; its v1 release metadata permits Illuminate 9.
- Keep `fruitcake/laravel-cors ^2.0` for the minimum-code-change upgrade. Verified 2.x releases permit Illuminate 9 and the application currently registers Fruitcake middleware. Test CORS behavior. Migrating later to Laravel's built-in `Illuminate\\Http\\Middleware\\HandleCors` is optional, not a confirmed blocker.
- Keep `doctrine/dbal ^3.3`, `guzzlehttp/guzzle ^7.0.1`, `intervention/image ^2.7`, `laravel/socialite ^5.5`, `maatwebsite/excel ^3.1`, both Mailchimp packages, QRCode, calendar-links, Location, Google Translate, and Stripe on their existing major constraints unless the locked resolver or focused tests identify a concrete incompatibility.
- Keep Laravel Sail and Mockery constraints initially; allow targeted dependency resolution to select compatible versions only if required.

### Compatibility Gates
- `rinvex/laravel-subscriptions ^5.0`: verify the exact locked/tagged v5 package and its transitive constraints. Current upstream branch metadata does not prove the selected v5 release works with Laravel 9. If unsupported, report the blocker and investigate vendor-supported alternatives; do not guess or remove subscription behavior.
- `dotunj/laratwilio ^1.0`: package metadata declares no Laravel framework constraint and requires Twilio SDK `^5.42`. Composer success does not establish runtime compatibility; test the SMS workflows.
- `maatwebsite/excel ^3.1`: retain the major constraint but confirm the resolved release accepts Illuminate 9 and PHP 8.2; test imports, exports, and queued exports if used.
- `fruitcake/laravel-cors`: although compatible 2.x releases exist, confirm the exact locked version accepts the selected Symfony dependencies.
- All remaining third-party packages must be checked using the resolved Composer metadata and actual package version, not only the broad `composer.json` constraint.

## Requirements

### Upgrade Safety
- Establish and preserve a known-good baseline before dependency changes.
- Confirm the actual Laravel 8 `composer.json` and matching `composer.lock` belong to the upgrade target.
- Back up or commit both Composer files together; never update the lockfile before compatibility review.
- Use a staging copy and a staging database for dependency resolution and behavior testing.
- Run `composer why-not laravel/framework ^9.0 -t` against the actual Laravel 8 project and document each blocker with its dependency path.
- Resolve dependency changes incrementally. Use targeted Composer commands and review every planned install, removal, and upgrade.
- Use `-W` / `--with-all-dependencies` only with an explicit reviewed package set when locked transitive dependencies must move with Laravel, Symfony, Flysystem, or Illuminate components.
- Preserve application features if a package is incompatible; pause and present verified options rather than guessing a replacement.

### Framework and Application Compatibility
- **Flysystem 3 / storage:** Preserve disk names, roots, URLs, symbolic links, and S3 behavior. Audit `Storage` writes for false-return handling, missing-file reads for null handling, overwrite behavior, and custom filesystem adapters. If the application depends on write exceptions, configure `throw` deliberately and test it. Install the Flysystem 3 S3 adapter only if S3 is used and the resolver requires it.
- **Symfony 6:** Resolve Symfony components through Laravel and compatible packages. Do not independently pin Symfony versions without a solver result. Check custom code overriding framework/Symfony interfaces for PHP return-type compatibility.
- **SwiftMailer to Symfony Mailer:** Search app code, event listeners, queued jobs, and integrations for Swift-specific classes or methods. Verify configured transports and all queued email flows. Do not change transport configuration that is not in use.
- **Ignition and Collision:** Replace the Laravel 8 development error-handler dependencies as listed above; confirm local exception rendering and PHPUnit CLI output.
- **Sanctum and authentication:** Test API tokens, SPA/stateful authentication if enabled, guards, login/register, password reset, email verification, roles, and permissions. Preserve existing admin/vendor guard behavior.
- **Middleware, proxy, CORS, and routes:** Preserve middleware ordering and custom route middleware. Verify trusted proxy headers, browser/API CORS, route bindings, route naming, route caching, and authorization behavior.
- **Queues, events, notifications, and scheduler:** Test serialization, retry/backoff, failed jobs, queued mail, listeners, notifications, queue-worker restart, scheduled commands, and cron execution. Search direct `opis/closure` or removed Laravel Serializable Closure class usage.
- **Database, query builder, and Eloquent:** Preserve schema and transaction behavior. Audit custom casts handling null, `belongsToMany` `firstOrCreate`/`updateOrCreate` behavior, custom `touch` overrides, DBAL use, MariaDB query behavior, and migration status. Do not run migrations as a routine framework-upgrade action.
- **Validation and pagination:** Review password/current-password rules, custom `validated()` overrides, array validation relying on unvalidated keys, and pagination links/API output.
- **HTTP client:** Laravel 9 uses a 30-second default timeout. Give longer-running external calls explicit timeouts where required and test payment, shipping, translation, geolocation, and other integrations.
- **Mail configuration:** Check active mail config and environment variables; remove obsolete SwiftMailer-specific settings only when confirmed unused. Do not put credentials in source control.
- **Other areas:** Check Blade directives, LazyCollection loop usage, helper-name collisions, custom facades, collection method removals/renames, `Request::getSession()` assumptions, and any manual implementations of changed Laravel contracts.

## Delivery Milestones and Gates

1. **Baseline and backup**
   - Confirm PHP `8.2.12`, Composer `2.9.3`, Laravel 8 version, database connection, extensions, and actual project root.
   - Run `composer validate`, baseline tests, `php artisan route:list`, and `php artisan migrate:status`.
   - Back up/commit the matching `composer.json` and `composer.lock`; back up the staging database.
   - Expected result: a reproducible Laravel 8 baseline and a known rollback point. Do not proceed if the manifest and lockfile are mismatched or missing.

2. **Dependency blocker analysis**
   - Run `composer show laravel/framework --locked` and `composer why-not laravel/framework ^9.0 -t`.
   - Inspect relevant locked versions and each blocker path. Verify third-party package requirements for those exact versions.
   - Expected result: a written list of actual blockers and the smallest compatible version changes. No dependency update occurs in this milestone.

3. **Manifest proposal and dry-run**
   - Apply only the proposed manifest constraint changes while retaining the existing lockfile unchanged.
   - Run `composer update --dry-run -W` for the explicit upgrade set, initially: `laravel/framework laravel/sanctum laravel/ui nunomaduro/collision spatie/laravel-ignition laravel/tinker renatomarinho/laravel-page-speed spatie/laravel-google-calendar srmklive/paypal`.
   - Add another package only when `why-not` or the dry-run identifies it as a blocker. Review all transitive Symfony/Flysystem changes and package removals.
   - Expected result: a solvable and reviewable proposed lockfile diff, with no unexplained package removal. If a critical feature's package cannot resolve or is unsupported, stop for an options decision.

4. **Targeted dependency update**
   - After the dry-run is reviewed, run the equivalent targeted `composer update -W` command and inspect `composer.lock` diff, `composer show`, and Composer audit output.
   - Expected result: only reviewed direct/transitive packages change, and Laravel reports version 9. Do not run a blanket update.

5. **Code/config compatibility fixes**
   - Change only code/config proven affected by the audit or failing focused tests. Preserve production schema and business logic.
   - Expected result: the application boots, routes register, storage/mail/queue integrations work in staging, and no unrelated behavior has been changed.

6. **Staging verification and release**
   - Complete the verification checklist, get business-owner sign-off for payment and financial workflows, then deploy using a release that includes the matched lockfile.
   - Expected result: Laravel 9 is running with all required multi-vendor flows verified and a tested rollback package available.

## Cache and Autoload Commands
Run after dependency resolution in staging, then rebuild only the caches used in the deployment:

```text
composer dump-autoload -o
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

Use `php artisan cache:clear` only after assessing the effect on shared application cache. If route caching fails because routes use closures or unsupported behavior, keep route caching disabled and document that result.

## Acceptance Criteria
- Laravel 9 resolves on PHP 8.2.12 using the actual project lockfile and Composer 2.9.3.
- `composer why-not laravel/framework ^9.0 -t` blockers are resolved or explicitly documented; no critical third-party integration is replaced without evidence and approval.
- No migration or production database schema change is required for the framework upgrade.
- Existing customer login/register, admin, vendor, product, cart, checkout, order, payment, Stripe, PayPal, email, storage/image, API, queue, cron/scheduler, and affiliate workflows pass the approved staging tests.
- Composer lock changes are limited to the reviewed upgrade set and necessary compatible transitive dependencies.
- A rollback using the prior release and matching prior Composer files has been documented and tested.

## Testing Checklist
- Login, registration, password reset, verification, logout, admin/vendor guards, roles and permissions.
- Admin panel and vendor panel access, CRUD, and authorization boundaries.
- Product creation/editing, inventory, import/export, product images and media.
- Cart/session persistence, checkout validation, order creation, order status, cancellation/refund paths as applicable.
- Every configured payment gateway in test/sandbox mode; Stripe intents/webhooks and PayPal return/webhook paths, retries, and idempotency.
- Synchronous and queued email, notifications, SMTP transport, failed delivery handling, and retry behavior.
- Upload, replace, read, download, and delete for local/public/images/S3 disks as used; image transformations and symbolic links.
- Public, admin, vendor, and API routes; CORS preflight and authentication/token flows.
- Queue dispatch, worker processing, retries, failures, `failed_jobs`, worker restart, and queued mail.
- `php artisan schedule:list` and a controlled scheduled-task execution through the configured cron mechanism.
- Affiliate attribution, commission calculation, wallet/ledger entries, payout job, and reconciliation.
- MariaDB reads/writes, transaction rollback, aggregates, pagination, search/filter, `migrate:status`, and no unintended schema changes.
- Full automated suite plus focused smoke tests for all integrations above. Do not use real payment charges or customer email addresses.

## Rollback
- Stop deployment and pause/stop workers running the new release.
- Restore the prior application release and its matching `composer.json` and `composer.lock` as a pair.
- Run `composer install` from that lockfile; do not run `composer update` during rollback.
- Clear/rebuild configuration, route, and view caches as appropriate; restart workers and verify critical login, checkout, and queue flows.
- Restore a database backup only if a separately approved destructive schema/data migration occurred. The Laravel framework upgrade itself must not require such a migration.

## Constraints and Open Risks
- PHP must remain at 8.2.12; Laravel 9 is the target and Laravel 10+ is out of scope.
- The supplied Laravel 8 manifest and the workspace root Laravel 9 manifest differ. The actual upgrade source and its lockfile must be identified before execution.
- Exact `composer why-not` output and final mutual package resolution cannot be confirmed without the actual Laravel 8 lockfile.
- `rinvex/laravel-subscriptions` and `dotunj/laratwilio` require exact-version/runtime verification; do not claim compatibility from broad constraints alone.
- Product/legal owners must verify payment and privacy-sensitive production practices; this PRD does not authorize production charges or email tests.

## Notes
This document defines requirements and rollout gates only. It does not implement the Laravel upgrade or authorize Composer updates, application-code changes, database migrations, or production deployment.
