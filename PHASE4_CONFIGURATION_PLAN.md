# Laravel 11 Upgrade - Phase 4: Configuration & Environment

**Status**: READY FOR IMPLEMENTATION

**Estimated Duration**: 4-6 hours

**Risk Level**: LOW (Non-breaking changes, tests verify everything)

---

## Executive Summary

Phase 4 focuses on **cleaning up application configuration** and **environment variables** after Phase 2's bootstrap migration. With Phase 3's comprehensive test suite, all changes can be verified immediately.

**Key Goals**:
- ✅ Verify Laravel 11 fluent bootstrap API is properly configured
- ✅ Clean up deprecated environment variables
- ✅ Publish missing config files
- ✅ Verify multi-tenancy, voting, and security configs
- ✅ Set up CI/CD for automated testing
- ✅ Prepare for Phase 5 (Vite Frontend Migration)

---

## Current State Analysis

### Existing Configs (26 files)
```
✅ Custom election configs: election.php, election_steps.php, voting.php, voting_security.php
✅ Multi-tenancy ready: auth.php, permission.php, sanctum.php
✅ Phase 2 updated: fortify.php, app.php
✅ Legacy: image.php, broadcasting.php
⚠️ Needs review: cors.php, session.php, services.php
```

### .env Status
```
✅ Voting configuration: TWO_CODES_SYSTEM, SELECT_ALL_REQUIRED
✅ Election settings: ELECTION_IS_ACTIVE, ELECTION_TIMEOUT
✅ Mail configured: SMTP (Hostinger)
⚠️ Outdated: MIX_PUSHER_* (should be VITE_*)
⚠️ Missing: VITE_API_BASE_URL, other Vite variables
⚠️ Duplicate: ELECTION_RESULTS_PUBLISHED appears twice
⚠️ Typo: CONTROL_IP_ADDRESS (should be MAX_USE_IP_ADDRESS)
```

---

## Phase 4.1: Config Verification & Publishing

### Task 4.1.1: Verify Bootstrap Configuration

**File**: `bootstrap/app.php`

**Checklist**:
```php
✅ Routes registered (web, api, console, channels)
✅ Global middleware appended (TrustProxies, TrackPerformance)
✅ Web middleware correct order:
  1. SetLocale
  2. HandleInertiaRequests
  3. TenantContext
✅ API stateful configured
✅ 12 middleware aliases registered
✅ 7 voting middleware aliases
✅ 2 multi-tenancy middleware aliases
```

**Action**: Review and verify Phase 2 changes still in place.

### Task 4.1.2: Publish Laravel 11 Config Files

Some configs need to be published or verified:

```bash
# Publish Fortify config (already done, verify)
php artisan vendor:publish --tag=fortify --force

# Verify Sanctum config
ls config/sanctum.php && echo "✅ Present" || echo "❌ Missing"

# Verify Jetstream config
ls config/jetstream.php && echo "✅ Present" || echo "❌ Missing"
```

**Configs to Verify**:

| Config | Purpose | Status |
|--------|---------|--------|
| `config/app.php` | App name, locale, timezone | ✅ Updated |
| `config/auth.php` | Guards, providers | ✅ OK |
| `config/fortify.php` | Fortify auth | ⚠️ Verify |
| `config/sanctum.php` | API tokens | ✅ OK |
| `config/session.php` | Session driver | ⚠️ Verify |
| `config/cors.php` | CORS settings | ⚠️ Review |

### Task 4.1.3: Review Critical Configs

#### A. Mail Configuration (`config/mail.php`)

**Current**: Hostinger SMTP
```php
'from' => [
    'address' => env('MAIL_FROM_ADDRESS', 'hello@example.com'),
    'name' => env('MAIL_FROM_NAME', 'Example'),
],
'mailers' => [
    'smtp' => [
        'transport' => 'smtp',
        'host' => env('MAIL_HOST'),
        'port' => env('MAIL_PORT'),
        'username' => env('MAIL_USERNAME'),
        'password' => env('MAIL_PASSWORD'),
        'encryption' => env('MAIL_ENCRYPTION'),
    ],
],
```

**Verify**:
- ✅ Hostinger SMTP credentials correct?
- ✅ MAIL_FROM_NAME matches brand?
- ✅ TLS/SSL encryption correct?

#### B. Session Configuration (`config/session.php`)

**Current**: Database sessions

**Verify**:
```php
// Sessions must be database-driven for multi-tenancy
'driver' => env('SESSION_DRIVER', 'database'), // ✅ Must be 'database'

// Secure cookies for HTTPS
'secure' => env('SESSION_SECURE_COOKIE', false), // Review for production
```

**Action**:
```php
// config/session.php - verify these settings:
return [
    'driver' => env('SESSION_DRIVER', 'database'), // ✅ CRITICAL
    'lifetime' => env('SESSION_LIFETIME', 120),
    'expire_on_close' => false,
    'secure' => env('SESSION_SECURE_COOKIE', false), // true in production
    'http_only' => true, // ✅ CRITICAL for security
    'same_site' => 'lax', // ✅ CSRF protection
];
```

#### C. CORS Configuration (`config/cors.php`)

**Current**: Default Laravel 11 setup

**Verify**:
```php
'paths' => ['api/*', 'sanctum/csrf-cookie'],
'allowed_methods' => ['*'],
'allowed_origins' => [env('APP_URL')],
'allowed_origins_patterns' => [],
'allowed_headers' => ['*'],
'exposed_headers' => [],
'max_age' => 0,
'supports_credentials' => true, // ✅ Required for Sanctum
```

**Action**: Add mobile API origins if separate:
```php
'allowed_origins' => [
    env('APP_URL'),
    env('MOBILE_API_URL', 'http://localhost:4200'), // Angular dev
],
```

#### D. Cache Configuration (`config/cache.php`)

**Current**: File-based cache

**Verify**:
```php
'default' => env('CACHE_DRIVER', 'file'),
'stores' => [
    'file' => [
        'driver' => 'file',
        'path' => storage_path('framework/cache/data'),
    ],
    'redis' => [
        'driver' => 'redis',
        'connection' => 'cache', // ✅ Separate connection
    ],
],
```

**Action**: For production, use Redis:
```bash
# .env production
CACHE_DRIVER=redis
REDIS_HOST=localhost
REDIS_PASSWORD=null
REDIS_PORT=6379
```

---

## Phase 4.2: Environment Variable Cleanup & Normalization

### Task 4.2.1: Fix Duplicate & Typo Variables

**Issues Found**:
```bash
# Issue 1: Duplicate ELECTION_RESULTS_PUBLISHED (lines 90, 93)
# Fix: Keep only ONE definition

# Issue 2: Typo - CONTROL_IP_ADDRESS (should be MAX_USE_IP_ADDRESS)
# Lines 108 shows both used inconsistently

# Issue 3: Missing proper Vite variables
# OLD: MIX_PUSHER_APP_KEY (for Laravel Mix)
# NEW: VITE_* format (for Vite)
```

### Task 4.2.2: Create Clean .env File

**Actions**:
1. Backup current: `cp .env .env.backup-phase4`
2. Fix duplicate & typo issues
3. Add missing Vite variables
4. Remove unused/commented variables
5. Verify all required variables present

**New .env Template** (Phase 4 compatible):

```env
# ============================================================================
# APPLICATION CONFIGURATION
# ============================================================================
APP_NAME="Public Digit"
APP_ENV=local
APP_KEY=base64:YOUR_KEY_HERE
APP_DEBUG=true
APP_URL=https://publicdigit.local
APP_LOCALE=de

# ============================================================================
# DATABASE CONFIGURATION
# ============================================================================
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nrna_de
DB_USERNAME=nrna
DB_PASSWORD=secure_password_here

# ============================================================================
# CACHE & SESSION CONFIGURATION
# ============================================================================
CACHE_DRIVER=file
SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_SECURE_COOKIE=false
QUEUE_CONNECTION=sync

# Redis (optional, for caching)
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

# ============================================================================
# MAIL CONFIGURATION
# ============================================================================
MAIL_MAILER=smtp
MAIL_HOST=smtp.hostinger.com
MAIL_PORT=465
MAIL_USERNAME=info@publicdigit.com
MAIL_PASSWORD=secure_password_here
MAIL_ENCRYPTION=ssl
MAIL_FROM_ADDRESS=info@publicdigit.com
MAIL_FROM_NAME="Public Digit"

# ============================================================================
# VOTING SYSTEM CONFIGURATION
# ============================================================================
# Voting code system: 0 = Simple (Code1 used twice), 1 = Strict (Code1+Code2)
TWO_CODES_SYSTEM=0

# Candidate selection: yes = must select exact number, no = up to N
SELECT_ALL_REQUIRED=yes

# IP address validation: limit voting attempts per IP
MAX_USE_IP_ADDRESS=5

# Voting timeout in minutes
ELECTION_VOTING_TIMEOUT=20

# ============================================================================
# ELECTION CONFIGURATION
# ============================================================================
ELECTION_IS_ACTIVE=true
ELECTION_RESULTS_PUBLISHED=false
ELECTION_START_DATE="2026-08-10 00:00:00"
ELECTION_END_DATE="2026-08-31 23:59:59"
ELECTION_ALLOW_VOTE_VERIFICATION=true
ELECTION_MAX_CANDIDATES_PER_POST=1
ELECTION_REQUIRE_EMAIL_VERIFICATION=true
ALLOW_CANDIDATE_NOMINATION=true

# ============================================================================
# FRONTEND CONFIGURATION (VITE)
# ============================================================================
VITE_APP_NAME="Public Digit"
VITE_APP_URL="${APP_URL}"
VITE_API_BASE_URL="${APP_URL}/api"
VITE_SELECT_ALL_REQUIRED="${SELECT_ALL_REQUIRED}"
VITE_VOTING_TIMEOUT="${ELECTION_VOTING_TIMEOUT}"

# ============================================================================
# LOGGING
# ============================================================================
LOG_CHANNEL=stack
LOG_LEVEL=debug

# ============================================================================
# FILESYSTEM
# ============================================================================
FILESYSTEM_DRIVER=local

# ============================================================================
# SYSTEM FEATURES
# ============================================================================
USE_SLUG_PATH=true
BROADCAST_DRIVER=log
```

### Task 4.2.3: Create .env.testing File

For testing, create isolated environment:

```bash
# Create testing environment
cp .env .env.testing

# Edit .env.testing - use SQLite for speed
```

**Key changes for testing**:
```env
APP_ENV=testing
APP_DEBUG=false

# Use SQLite for fast tests
DB_CONNECTION=sqlite
DB_DATABASE=:memory:

# No email in tests
MAIL_MAILER=array

# Disable session persistence
SESSION_DRIVER=array

# Synchronous queue for testing
QUEUE_CONNECTION=sync

# Fast cache
CACHE_DRIVER=array

# Disable debug in tests
LOG_LEVEL=emergency
```

### Task 4.2.4: Create .env.production File

For production deployment:

```bash
cp .env .env.production
```

**Key changes for production**:
```env
APP_ENV=production
APP_DEBUG=false

# Secure session cookies
SESSION_SECURE_COOKIE=true

# Use Redis cache
CACHE_DRIVER=redis

# Use database queue for reliability
QUEUE_CONNECTION=database

# Higher logging threshold
LOG_LEVEL=notice
```

---

## Phase 4.3: Run Full Test Suite with Updated Config

### Task 4.3.1: Verify Tests Pass

```bash
# Run all Phase 3 tests
php artisan test --no-coverage --parallel

# Run with coverage
php artisan test --coverage --parallel

# Run specific test suites
php artisan test tests/Feature/Bootstrap
php artisan test tests/Feature/Middleware
php artisan test tests/Feature/Voting
php artisan test tests/Integration
```

**Expected Result**: All tests pass ✅

### Task 4.3.2: Coverage Report

```bash
# Generate HTML coverage report
php artisan test --coverage-html coverage/

# Generate Clover report (for CI/CD)
php artisan test --coverage-clover coverage.xml

# View coverage
open coverage/index.html
```

**Target**: ≥ 85% coverage

---

## Phase 4.4: Security Configuration

### Task 4.4.1: CSRF Protection

**Verify** `config/session.php`:
```php
'same_site' => 'lax', // Prevents CSRF attacks
```

**Verify** middleware in `bootstrap/app.php`:
```php
✅ VerifyCsrfToken middleware registered
✅ Web middleware group configured
```

### Task 4.4.2: Password Hashing

**Verify** `config/hashing.php`:
```php
'driver' => env('HASH_DRIVER', 'bcrypt'),
'bcrypt' => [
    'rounds' => env('BCRYPT_ROUNDS', 12), // Secure: 12+
],
```

**Action**: For production, verify `BCRYPT_ROUNDS=12` or higher.

### Task 4.4.3: Sanctum Token Security

**Verify** `config/sanctum.php`:
```php
'stateful' => explode(',', env('SANCTUM_STATEFUL_DOMAINS', 'localhost,127.0.0.1')),
'middleware' => [
    'encrypt_cookies' => \App\Http\Middleware\EncryptCookies::class,
    'trim_strings' => \App\Http\Middleware\TrimStrings::class,
    'validate_csrf_token' => \App\Http\Middleware\VerifyCsrfToken::class,
],
```

**Action**: Add your domain:
```env
# .env
SANCTUM_STATEFUL_DOMAINS=publicdigit.com,*.publicdigit.com
```

---

## Phase 4.5: Multi-Tenancy Configuration

### Task 4.5.1: Verify Permission System

**File**: `config/permission.php`

```php
// Cache permissions for performance
'cache' => [
    'enable' => true,
    'expiration_time' => 60 * 24, // 24 hours
],

// Use database tables (not cache) for role/permission lookup
'table_names' => [
    'roles' => 'roles',
    'permissions' => 'permissions',
    'model_has_permissions' => 'model_has_permissions',
    'model_has_roles' => 'model_has_roles',
    'role_has_permissions' => 'role_has_permissions',
],
```

**Verify**: Tenant-scoped permissions are working (Phase 3 tests verify this).

### Task 4.5.2: Database Connection Verification

**File**: `config/database.php`

**Verify**:
```php
'default' => env('DB_CONNECTION', 'mysql'),

'mysql' => [
    'driver' => 'mysql',
    'host' => env('DB_HOST', '127.0.0.1'),
    'port' => env('DB_PORT', 3306),
    'database' => env('DB_DATABASE'),
    'username' => env('DB_USERNAME'),
    'password' => env('DB_PASSWORD'),
],

// Foreign key constraints MUST be enabled for multi-tenancy
'mysql' => [
    'strict' => true, // ✅ CRITICAL
    'engine' => 'InnoDB', // ✅ Required for foreign keys
],
```

**Action**: Verify strict mode is enabled:
```php
'mysql' => [
    ...
    'strict' => env('DB_STRICT_MODE', true), // ✅
],
```

---

## Phase 4.6: Set Up CI/CD Pipeline

### Task 4.6.1: Create GitHub Actions Workflow

**File**: `.github/workflows/tests.yml`

```yaml
name: Tests & Coverage

on:
  push:
    branches: [ main, develop ]
  pull_request:
    branches: [ main, develop ]

jobs:
  test:
    runs-on: ubuntu-latest

    services:
      mysql:
        image: mysql:8.0
        env:
          MYSQL_ALLOW_EMPTY_PASSWORD: true
          MYSQL_DATABASE: testing
        ports:
          - 3306:3306
        options: --health-cmd="mysqladmin ping" --health-interval=10s --health-timeout=5s --health-retries=3

    steps:
      - uses: actions/checkout@v3

      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: '8.3'
          extensions: mbstring, pdo, pdo_mysql, fileinfo
          coverage: xdebug

      - name: Install Dependencies
        run: composer install --prefer-dist --no-progress --no-interaction

      - name: Copy Environment
        run: cp .env.example .env.testing

      - name: Generate Key
        run: php artisan key:generate --env=testing

      - name: Run Migrations
        run: php artisan migrate --env=testing

      - name: Run Tests
        run: php artisan test --env=testing --parallel --coverage-clover coverage.xml

      - name: Upload Coverage to Codecov
        uses: codecov/codecov-action@v3
        with:
          file: ./coverage.xml
          fail_ci_if_error: false

      - name: Archive Coverage Report
        if: always()
        uses: actions/upload-artifact@v3
        with:
          name: coverage-report
          path: coverage/
```

### Task 4.6.2: Create Pre-Commit Hook

**File**: `.husky/pre-commit`

Prevent commits with failing tests:

```bash
#!/usr/bin/env sh
. "$(dirname -- "$0")/_/husky.sh"

# Run tests before commit
php artisan test --stop-on-failure

# Lint PHP
./vendor/bin/pint --test
```

### Task 4.6.3: Add GitHub Branch Protection

In GitHub repo settings:
1. Go to **Settings → Branches → main**
2. Add rule: **Require status checks to pass**
3. Select: Tests workflow must pass
4. Require code reviews: 1 approval

---

## Phase 4.7: Configuration Validation Tests

Create a test to verify config integrity:

**File**: `tests/Feature/Configuration/ConfigurationValidationTest.php`

```php
<?php

namespace Tests\Feature\Configuration;

use Tests\TestCase;

class ConfigurationValidationTest extends TestCase
{
    /**
     * Test required environment variables are set.
     */
    public function test_required_environment_variables_present()
    {
        $required = [
            'APP_NAME',
            'APP_KEY',
            'DB_CONNECTION',
            'DB_HOST',
            'DB_DATABASE',
            'MAIL_MAILER',
            'ELECTION_IS_ACTIVE',
        ];

        foreach ($required as $var) {
            $this->assertNotNull(env($var), "Missing required environment variable: {$var}");
        }
    }

    /**
     * Test no duplicate variables.
     */
    public function test_no_duplicate_variables()
    {
        // ELECTION_RESULTS_PUBLISHED should appear only once
        $envContent = file_get_contents(base_path('.env'));
        $count = substr_count($envContent, 'ELECTION_RESULTS_PUBLISHED=');
        $this->assertEquals(1, $count, 'ELECTION_RESULTS_PUBLISHED should appear only once');
    }

    /**
     * Test database configuration is correct.
     */
    public function test_database_configuration()
    {
        $this->assertEquals('mysql', config('database.default'));
        $this->assertTrue(config('database.connections.mysql.strict'));
    }

    /**
     * Test session configuration for multi-tenancy.
     */
    public function test_session_configuration()
    {
        $this->assertEquals('database', config('session.driver'));
        $this->assertTrue(config('session.http_only'));
    }

    /**
     * Test mail configuration.
     */
    public function test_mail_configuration()
    {
        $this->assertNotNull(config('mail.mailers.smtp'));
        $this->assertNotNull(config('mail.from.address'));
    }

    /**
     * Test Sanctum configuration for API tokens.
     */
    public function test_sanctum_configuration()
    {
        $this->assertNotNull(config('sanctum.stateful'));
    }

    /**
     * Test voting system configuration.
     */
    public function test_voting_system_configuration()
    {
        $this->assertNotNull(env('TWO_CODES_SYSTEM'));
        $this->assertNotNull(env('SELECT_ALL_REQUIRED'));
        $this->assertNotNull(env('MAX_USE_IP_ADDRESS'));
    }
}
```

---

## Phase 4.8: Documentation & Handoff

### Create Configuration Documentation

**File**: `docs/CONFIGURATION.md`

```markdown
# Configuration Guide - Public Digit

## Environment Variables

### Application
- `APP_NAME` - Application name
- `APP_ENV` - Environment (local, testing, production)
- `APP_DEBUG` - Debug mode (false in production)
- `APP_URL` - Base application URL

### Database
- `DB_CONNECTION` - mysql (required)
- `DB_HOST` - Database host
- `DB_DATABASE` - Database name
- `DB_USERNAME` - Database user
- `DB_PASSWORD` - Database password

### Voting System
- `TWO_CODES_SYSTEM` - 0=Simple, 1=Strict mode
- `SELECT_ALL_REQUIRED` - yes=exact selection, no=up to N
- `MAX_USE_IP_ADDRESS` - IP address voting limit
- `ELECTION_VOTING_TIMEOUT` - Voting window in minutes

### Production Checklist
- [ ] `APP_DEBUG=false`
- [ ] `SESSION_SECURE_COOKIE=true`
- [ ] `CACHE_DRIVER=redis` (recommended)
- [ ] `QUEUE_CONNECTION=database`
- [ ] All passwords in secrets management
- [ ] Database strict mode enabled
- [ ] Sanctum domains configured
```

---

## Phase 4 Completion Checklist

### Config Files (26 total)
- [ ] Bootstrap config verified
- [ ] Mail configuration correct
- [ ] Session configuration for multi-tenancy
- [ ] CORS configuration updated
- [ ] Cache configuration correct
- [ ] Database strict mode enabled
- [ ] Sanctum stateful domains set

### Environment Variables
- [ ] Remove duplicate `ELECTION_RESULTS_PUBLISHED`
- [ ] Fix typo: `CONTROL_IP_ADDRESS` → `MAX_USE_IP_ADDRESS`
- [ ] Update MIX_* to VITE_* variables
- [ ] Add `VITE_API_BASE_URL`
- [ ] Create `.env.testing` file
- [ ] Create `.env.production` file
- [ ] Verify all required variables present
- [ ] No sensitive data in repo

### Testing
- [ ] Run Phase 3 tests: `php artisan test --parallel`
- [ ] Coverage ≥ 85%
- [ ] Generate HTML coverage report
- [ ] All critical tests passing

### Security
- [ ] CSRF protection enabled
- [ ] Secure session cookies configured
- [ ] Password hashing configured (BCRYPT_ROUNDS=12+)
- [ ] Sanctum token security verified
- [ ] Database foreign key constraints enabled

### CI/CD
- [ ] GitHub Actions workflow created
- [ ] Pre-commit hooks configured
- [ ] Branch protection rules set
- [ ] Coverage reporting configured

### Documentation
- [ ] Configuration guide created
- [ ] Environment variables documented
- [ ] Production checklist ready

---

## Phase 4 Success Criteria

✅ **All tests pass** (Phase 3 suite)
✅ **Coverage ≥ 85%**
✅ **No deprecated variables**
✅ **Security configurations verified**
✅ **CI/CD pipeline ready**
✅ **Documentation complete**
✅ **Ready for Phase 5 (Vite)**

---

## Estimated Timeline

| Task | Duration | Risk |
|------|----------|------|
| **4.1: Config Verification** | 1 hour | LOW |
| **4.2: .env Cleanup** | 1 hour | LOW |
| **4.3: Test Suite** | 1 hour | LOW |
| **4.4: Security Config** | 1 hour | LOW |
| **4.5: Multi-Tenancy Config** | 30 min | LOW |
| **4.6: CI/CD Setup** | 1 hour | MEDIUM |
| **4.7: Config Validation Tests** | 30 min | LOW |
| **4.8: Documentation** | 30 min | LOW |
| **TOTAL** | **~6 hours** | **LOW** |

---

## Next Steps: Phase 5 (Frontend - Vite)

After Phase 4 completion, proceed to:

```bash
# Phase 5: Vite Migration
# - Remove Laravel Mix
# - Migrate to Vite
# - Update build process
# - Optimize frontend assets
```

---

**Phase 4 Status**: ✅ READY TO BEGIN

**Estimated Completion**: 2026-02-24 + 6 hours
