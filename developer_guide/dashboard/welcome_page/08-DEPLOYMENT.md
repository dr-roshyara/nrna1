# Deployment Documentation

## Pre-Deployment Checklist

### Code Quality (REQUIRED)

- [ ] All tests passing: `php artisan test`
- [ ] Test coverage ≥ 80%
- [ ] No linting errors: `php artisan pint`
- [ ] Static analysis clean: `phpstan analyse`
- [ ] All migrations created and reviewed
- [ ] Database rollback tested
- [ ] Git commits clean and descriptive

### Security Review (REQUIRED)

- [ ] GDPR Article 32 compliance verified
- [ ] DSGVO §26 political opinion protection confirmed
- [ ] No PII in logs
- [ ] Encryption enabled for sensitive data
- [ ] HTTPS enforced in production
- [ ] CSRF tokens in place
- [ ] SQL injection protection verified
- [ ] XSS protections enabled
- [ ] Authentication middleware verified

### Performance (REQUIRED - CRITICAL)

**Dashboard Welcome Page Specific Requirements:**

- [ ] **Exactly 6 database queries** (not more, not less)
  - Query 1: Load User
  - Query 2: Load organizationRoles
  - Query 3: Load organizations
  - Query 4: Load commissions
  - Query 5: Load voterRegistrations
  - Query 6: Load roles
- [ ] **Page response time < 200ms** (not 30+ seconds)
- [ ] **No N+1 queries** (test with tinker)
- [ ] **All relationships safely loaded** (relationLoaded() checks in place)
- [ ] **No circular serialization errors** (relationships hidden in User model)
- [ ] **Frontend array safety validated** (Array.isArray() checks in Vue)
- [ ] **Bundle size acceptable**
- [ ] **Cache strategies implemented**
- [ ] **CDN configuration tested**

**Performance Verification Before Deployment:**
```bash
# Test query count
php artisan tinker
> \DB::enableQueryLog()
> $builder = app(\App\Services\Dashboard\UserStateBuilder::class)
> $user = \App\Models\User::first()
> $state = $builder->build($user)
> count(\DB::getQueryLog())  # Must be exactly 6

# Test response time
> $start = microtime(true)
> $state = $builder->build($user)
> round((microtime(true) - $start) * 1000)  # Must be < 200ms
```

### Documentation (REQUIRED)

- [ ] README updated with deployment steps
- [ ] Environment variables documented
- [ ] Database schema changes documented
- [ ] API changes documented
- [ ] DPIA (Data Protection Impact Assessment) completed
- [ ] Risk assessment documented

---

## Deployment Environments

### Development (Local)

```
URL: http://localhost:8000
Database: PostgreSQL localhost
Encryption: Disabled (for testing)
Mail: Mailtrap or log driver
```

**Setup:**
```bash
# Install dependencies
composer install
npm install

# Setup environment
cp .env.example .env
php artisan key:generate

# Setup database
php artisan migrate

# Run tests
php artisan test
```

### Staging (Testing)

```
URL: https://staging.publicdigit.de
Database: PostgreSQL (staging)
Encryption: Enabled
Mail: Transactional service
Backups: Daily
```

**Requirements:**
- SSL certificate
- Environment-specific configuration
- Staging-only users for testing
- Isolated database (no production data)
- Logging enabled

**Deployment:**
```bash
git checkout staging
git pull origin staging

composer install --no-dev
npm run build

php artisan migrate --force
php artisan cache:clear

# Run tests on staging
php artisan test

# Monitor logs
tail -f storage/logs/laravel.log
```

### Production (Live)

```
URL: https://publicdigit.de
Database: PostgreSQL (Germany)
Encryption: Enabled (TLS 1.3)
Mail: Verified service
Backups: Hourly + encrypted
```

**Requirements:**
- EV SSL certificate
- High availability setup
- Load balancer configuration
- Database replication
- Automated backups
- Disaster recovery plan

---

## Step-by-Step Deployment

### Step 1: Pre-Deployment Verification

```bash
# 1. Verify all tests pass
php artisan test

# 2. Generate static analysis report
phpstan analyse app/

# 3. Check for migrations
php artisan migrate:status

# 4. Verify environment configuration
php artisan tinker
> config('app.debug')  # Should be false in production
> config('app.env')    # Should be 'production'
```

### Step 2: Database Migrations

**On Staging First:**

```bash
# 1. Create backup
pg_dump publicdigit_staging > backup-$(date +%s).sql

# 2. Run migrations
php artisan migrate --force

# 3. Verify schema
php artisan migrate:status

# 4. Test rollback (if needed)
php artisan migrate:rollback
php artisan migrate

# 5. Seed test data (if applicable)
php artisan db:seed --class=StagingSeeder
```

**Then on Production:**

```bash
# 1. Create automated backup
# (handled by infrastructure)

# 2. Run migrations
php artisan migrate --force

# 3. Verify migration completed
php artisan migrate:status
```

### Step 3: Application Deployment

```bash
# 1. Pull latest code
git checkout main
git pull origin main

# 2. Install dependencies
composer install --no-dev --optimize-autoloader

# 3. Build frontend assets
npm ci
npm run build

# 4. Clear caches
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 5. Migrate database (if needed)
php artisan migrate --force

# 6. Set permissions (if applicable)
chmod -R 755 storage bootstrap/cache
```

### Step 4: Performance Verification (CRITICAL - MUST NOT SKIP)

**Dashboard Welcome Page Performance Test:**

```bash
# Test N+1 query problem (most common issue that causes 30+ second timeouts)
php artisan tinker

# Enable query logging
> \DB::enableQueryLog()

# Time the operation
> $startTime = microtime(true)

# Get UserStateBuilder and test with a user
> $builder = app(\App\Services\Dashboard\UserStateBuilder::class)
> $user = \App\Models\User::first()
> $state = $builder->build($user)

# Check query count
> $queries = \DB::getQueryLog()
> count($queries)
# MUST be exactly 6, not 50+!

# Check response time
> $elapsed = (microtime(true) - $startTime) * 1000
> echo round($elapsed) . "ms\n"
# MUST be < 200ms, not 30,000ms!

# If queries > 6 or time > 200ms, STOP DEPLOYMENT
# The N+1 query problem has not been fixed!
```

**What to Check in Query Log:**

```bash
> foreach ($queries as $q) echo $q->sql . "\n"

# Expected queries (in order):
# 1. SELECT * FROM users WHERE id = ?
# 2. SELECT * FROM organization_roles WHERE user_id = ?
# 3. SELECT * FROM organizations WHERE ...
# 4. SELECT * FROM organization_members WHERE ...
# 5. SELECT * FROM commissions WHERE ...
# 6. SELECT * FROM roles WHERE ...

# If you see more queries (especially repeated queries), N+1 is still present
```

**Deployment Must Not Proceed If:**
- [ ] Query count is > 6
- [ ] Response time is > 200ms
- [ ] Circular serialization errors appear in logs
- [ ] Frontend shows "TypeError: contentBlocks.some is not a function"

---

### Step 5: Service Verification

```bash
# 1. Verify application health
curl https://publicdigit.de/health

# 2. Check welcome page loads
curl https://publicdigit.de/dashboard/welcome -H "Authorization: Bearer TOKEN"

# 3. Verify GDPR redirect
curl https://publicdigit.de/dashboard/welcome -H "Cookie: GDPR=unconsented"

# 4. Check API endpoints
curl https://publicdigit.de/api/v1/user

# 5. Monitor error logs
tail -f storage/logs/laravel.log
```

### Step 5: Rollback Plan (If Needed)

```bash
# 1. Revert to previous version
git revert HEAD

# 2. Rebuild application
php artisan cache:clear
npm run build

# 3. Rollback database (if needed)
php artisan migrate:rollback

# 4. Verify services restored
curl https://publicdigit.de/health
```

---

## Environment Configuration

### Production .env Variables

```env
# Application
APP_NAME=PublicDigit
APP_ENV=production
APP_DEBUG=false
APP_URL=https://publicdigit.de
APP_KEY=base64:...

# Database
DB_CONNECTION=pgsql
DB_HOST=db.publicdigit.de
DB_PORT=5432
DB_DATABASE=publicdigit
DB_USERNAME=...
DB_PASSWORD=...

# Cache
CACHE_DRIVER=redis
REDIS_HOST=redis.publicdigit.de
REDIS_PASSWORD=...

# Mail
MAIL_DRIVER=smtp
MAIL_HOST=mail.publicdigit.de
MAIL_PORT=587
MAIL_USERNAME=...
MAIL_PASSWORD=...
MAIL_FROM_ADDRESS=noreply@publicdigit.de
MAIL_FROM_NAME="Public Digit"

# Security
SANCTUM_GUARD=sanctum
SANCTUM_COOKIE_SECURE=true
SANCTUM_COOKIE_HTTP_ONLY=true
SANCTUM_COOKIE_SAME_SITE=lax

# HTTPS/SSL
FORCE_HTTPS=true
SECURE_SCHEME=https

# GDPR
GDPR_CONSENT_REQUIRED=true
GDPR_CONSENT_CHECKBOX=true
```

### Secrets Management

**Never commit secrets to git:**

```bash
# Store in secure vault (AWS Secrets Manager, HashiCorp Vault)
aws secretsmanager get-secret-value --secret-id publicdigit/production

# Use environment secrets in CI/CD
export DB_PASSWORD=$(aws secretsmanager get-secret-value ...)
```

---

## Performance Optimization

### Database Optimization

```bash
# 1. Analyze query performance
EXPLAIN ANALYZE SELECT * FROM users WHERE organisation_id = 1;

# 2. Create indexes
ALTER TABLE users ADD INDEX idx_organization_id (organisation_id);
ALTER TABLE votes ADD INDEX idx_election_id (election_id);

# 3. Vacuum database (PostgreSQL)
VACUUM ANALYZE;
```

### Caching Strategy

```php
// Cache frequently accessed data
$roles = Cache::remember('user:' . $user->id . ':roles', 3600, function () use ($user) {
    return $this->roleDetectionService->getDashboardRoles($user);
});

// Invalidate cache on changes
Cache::forget('user:' . $user->id . ':roles');
```

### Frontend Optimization

```bash
# 1. Minify assets
npm run build

# 2. Enable gzip compression (in web server config)
gzip on;
gzip_types text/css text/javascript application/json;

# 3. Set cache headers
Cache-Control: public, max-age=3600

# 4. Use CDN for static assets
src="https://cdn.publicdigit.de/app.js"
```

### Database Connection Pooling

```php
// In config/database.php
'pgsql' => [
    // ...
    'pool' => [
        'min_idle' => 5,
        'max_size' => 20,
    ],
],
```

---

## Monitoring & Logging

### Log Configuration

**File:** `config/logging.php`

```php
'channels' => [
    // Safe production logs
    'production' => [
        'driver' => 'stack',
        'channels' => ['daily', 'syslog'],
        'ignore_exceptions' => false,
    ],

    // GDPR-safe audit logs
    'gdpr_audit' => [
        'driver' => 'daily',
        'path' => storage_path('logs/gdpr_audit.log'),
        'level' => 'info',
    ],
],
```

### Monitoring Alerts

```php
// Monitor critical errors
if (Log::hasError()) {
    NotificationService::alert([
        'type' => 'error',
        'message' => 'Production error detected',
        'channels' => ['email', 'slack']
    ]);
}

// Monitor GDPR violations
Log::channel('gdpr_audit')->warning('Consent check failed', [
    'user_id' => $user->id,
    'timestamp' => now(),
]);
```

### Health Checks

Create endpoint: `GET /health`

```php
Route::get('/health', function () {
    $checks = [
        'database' => DB::connection()->getPdo() !== null,
        'cache' => Cache::ping() !== false,
        'queue' => Queue::connection()->ping() !== false,
    ];

    return response()->json([
        'status' => all($checks) ? 'ok' : 'error',
        'checks' => $checks,
        'timestamp' => now(),
    ]);
});
```

---

## Automated Deployment

### GitHub Actions CI/CD Pipeline

**File:** `.github/workflows/deploy.yml`

```yaml
name: Deploy to Production

on:
  push:
    branches: [main]

jobs:
  test:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v3
      - uses: php-actions/setup-php@v1
        with:
          php-version: '8.2'
      - run: composer install
      - run: php artisan test --coverage

  deploy:
    needs: test
    runs-on: ubuntu-latest
    if: github.ref == 'refs/heads/main'
    steps:
      - uses: actions/checkout@v3

      - name: Deploy to production
        uses: appleboy/ssh-action@master
        with:
          host: ${{ secrets.HOST }}
          username: ${{ secrets.USERNAME }}
          key: ${{ secrets.SSH_KEY }}
          script: |
            cd /var/www/publicdigit
            git pull origin main
            composer install --no-dev
            npm ci && npm run build
            php artisan migrate --force
            php artisan cache:clear
            sudo systemctl restart php-fpm

      - name: Notify deployment
        run: |
          curl -X POST ${{ secrets.SLACK_WEBHOOK }} \
            -d '{"text":"✅ Deployment complete"}'
```

---

## GDPR Deployment Checklist

### Pre-Deployment

- [ ] DPIA updated and approved
- [ ] Data Processing Agreement reviewed
- [ ] Encryption keys generated and stored securely
- [ ] Consent management system verified
- [ ] Audit logging enabled
- [ ] Backup encryption verified
- [ ] Privacy policy updated
- [ ] Cookie consent banner working
- [ ] Data subject rights endpoints tested
- [ ] Incident response plan ready

### Post-Deployment

- [ ] Monitor for GDPR violations
- [ ] Verify audit logs being written
- [ ] Check consent storage working
- [ ] Monitor for data breaches
- [ ] Verify pseudonymization working
- [ ] Monitor access logs
- [ ] Test data export functionality
- [ ] Test data deletion functionality
- [ ] Verify no PII in logs
- [ ] Document deployment in audit trail

---

## Rollback Scenarios

### Scenario 1: Database Migration Failed

```bash
# 1. Rollback migration
php artisan migrate:rollback

# 2. Restore from backup
psql publicdigit < backup.sql

# 3. Restart application
php artisan cache:clear
```

### Scenario 2: Frontend Breaking Change

```bash
# 1. Revert code
git revert HEAD

# 2. Rebuild frontend
npm run build

# 3. Clear caches
php artisan cache:clear
```

### Scenario 3: Service Outage

```bash
# 1. Switch to backup server
# (handled by infrastructure)

# 2. Verify services online
curl https://backup.publicdigit.de/health

# 3. Update DNS/load balancer
# (handled by infrastructure)

# 4. Restore from backup
# (if needed)
```

---

## Post-Deployment Tasks

### 1. Verify Services

```bash
# Test all critical endpoints
./scripts/smoke-tests.sh

# Check logs for errors
grep ERROR storage/logs/laravel.log | tail -20

# Verify GDPR compliance
curl /api/v1/user/export  # Should require consent
```

### 2. Notify Stakeholders

- Send deployment notification to team
- Update status page
- Document changes in changelog
- Create deployment ticket in issue tracker

### 3. Monitor Systems

```bash
# Watch error logs
tail -f storage/logs/laravel.log

# Monitor performance
php artisan tinker
> \DB::connection()->getReadPdo()

# Check queue status
php artisan queue:failed
```

### 4. Schedule Follow-up

- Review error logs in 1 hour
- Full regression testing in 24 hours
- Performance review in 1 week
- GDPR audit in 1 month

---

## Maintenance & Updates

### Security Updates

```bash
# Check for vulnerabilities
composer audit

# Update vulnerable packages
composer update --with-all-dependencies

# Review changelog for breaking changes
# Commit changes and deploy
```

### Scheduled Maintenance

**Weekly:**
- Database VACUUM ANALYZE
- Log rotation
- Cache cleanup

**Monthly:**
- Security updates
- Performance review
- Backup integrity checks

**Quarterly:**
- Full disaster recovery test
- GDPR compliance audit
- Security assessment

---

## Summary

Deployment requires:

1. **Pre-Deployment** - All tests passing, code reviewed, security verified
2. **Migrations** - Database changes applied carefully with backup
3. **Application** - Code deployed, assets built, caches cleared
4. **Verification** - Health checks pass, services online, logs clean
5. **Monitoring** - Error logs watched, performance monitored, GDPR verified
6. **Rollback** - Plan ready for quick reversion if needed

Never deploy without:
- ✅ All tests passing
- ✅ Staging verification
- ✅ GDPR compliance confirmed
- ✅ Rollback plan ready
- ✅ Team notification sent
