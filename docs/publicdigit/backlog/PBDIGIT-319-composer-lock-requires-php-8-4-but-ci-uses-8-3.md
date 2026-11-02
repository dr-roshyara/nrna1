# PBDIGIT-319: `composer.lock` requires PHP ≥ 8.4.1, but `composer.json` and CI declare PHP 8.3

**Type:** Technical debt (build / CI) · **Epic:** none · **Created:** 2026-11-02 · **Raised by:** found while verifying PBDIGIT-318 S1b under PHP 8.3 (an acceptance condition set by the PO)

| | |
|---|---|
| **Status** | `OPEN` · not authorised · no change made |
| **Classification** | **observed in files** (static) plus one local run. Whether CI currently fails is **not verified** |
| **Scope** | `composer.json`, `composer.lock`, the PHP version in the GitHub workflows |

## Finding (verified 2026-11-02)

- `composer.json:11` declares `"php": "^8.3"`.
- The workflows declare PHP 8.3, including the blocking `greenfield-merge-gate.yml:49`. Two others declare 8.2 (`membership-architecture.yml:22`, `regression-detector.yml:21`).
- **`composer.lock` pins packages that require PHP ≥ 8.4.1:** `symfony/clock` v8.1.9, `symfony/css-selector` v8.1.6, `symfony/event-dispatcher` v8.1.5, `symfony/string` v8.1.9, `symfony/translation` v8.1.5, `symfony/var-exporter` v8.1.9.
- **Local evidence:** `php vendor/bin/phpunit` under the official `php:8.3-cli` image stops in `vendor/composer/platform_check.php`: "Your Composer dependencies require a PHP version ">= 8.4.1". You are running 8.3.36."

## Consequence (inferred, not verified)

`composer install` from this lock should fail on the PHP 8.3 and 8.2 runners. Either those CI jobs are failing, or they install differently from what the files suggest.

## Questions for the owner

- What is the intended minimum PHP version? Either raise the declared version and the workflows, or constrain the lock to packages that support 8.3.
- What do the CI runs show today? (A read of the workflow logs, not done here.)

## Not in scope here

Any change to `composer.json`, `composer.lock`, `vendor/` or the workflows. That needs its own authorisation.

## Related

PBDIGIT-318 S1b: the boundary test passed under PHP 8.3 with the platform check disabled in a scratch copy, so S1b itself is 8.3-compatible.
