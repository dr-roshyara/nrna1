# Deployment Checklist: Enhanced Login System

> **Date:** {{ date('Y-m-d H:i:s') }}
> **Environment:** Production
> **Feature:** 3-Level Fallback Login Routing with Voting Priority

---

## Phase 1: Pre-Deployment Verification (Local Development)

### Code Quality
- [ ] All new files created and reviewed:
  - [ ] `config/login-routing.php` - Configuration file
  - [ ] `app/Enums/VotingStep.php` - Voting step enum
  - [ ] `app/Observers/UserOrganisationObserver.php` - Cache invalidation
  - [ ] `app/Http/Responses/LoginResponse.php` - Enhanced with 3-level fallback
  - [ ] `app/Services/DashboardResolver.php` - Enhanced with caching and voting priority
  - [ ] `app/Http/Controllers/EmergencyDashboardController.php` - Level 2 fallback
  - [ ] `database/migrations/[timestamp]_add_onboarding_fields_to_users_table.php` - Schema
  - [ ] `resources/views/auth/login-success-fallback.blade.php` - Level 3 fallback HTML

### Laravel Standards
- [ ] Code follows PSR-12 style guide
- [ ] All methods have proper docblocks
- [ ] No console errors in logs
- [ ] No deprecated API usage
- [ ] Proper exception handling throughout

### Testing
- [ ] All classes load without syntax errors:
  ```bash
  php artisan tinker --execute="
  require_once 'app/Enums/VotingStep.php';
  require_once 'app/Observers/UserOrganisationObserver.php';
  require_once 'app/Http/Responses/LoginResponse.php';
  echo 'All files loaded successfully';
  "
  ```
- [ ] Configuration file is valid:
  ```bash
  php artisan config:cache
  ```

---

## Phase 2: Database Preparation

### Migration Verification
- [ ] Migration file created with proper naming convention
- [ ] Up/down methods properly implemented
- [ ] Schema checks for column existence (defensive)
- [ ] Foreign keys properly constrained

### Pre-Migration Check
```bash
# Verify migration hasn't been run yet
php artisan migrate:status | grep "add_onboarding_fields_to_users"

# Backup database
mysqldump -u [user] -p [database] > backup_$(date +%Y%m%d_%H%M%S).sql

# Or using Laravel
php artisan db:backup
```

- [ ] Database backup created
- [ ] Database user has proper permissions
- [ ] Migration can be rolled back if needed

---

## Phase 3: Pre-Production Environment

### Environment Configuration
- [ ] `.env` variables configured:
  ```env
  # Login routing cache
  LOGIN_CACHE_TTL=300                    # 5 minutes
  LOGIN_ORG_CACHE_TTL=300                # 5 minutes
  LOGIN_VOTING_CACHE_TTL=30              # 30 seconds

  # Timeouts
  LOGIN_TIMEOUT_SECONDS=5                # Max 5 second resolution
  LOGIN_QUERY_TIMEOUT_SECONDS=2          # Max 2 seconds per query

  # Fallback thresholds
  LOGIN_FALLBACK_MAX_FAILURES=3          # Emergency after 3 failures
  LOGIN_ALERT_THRESHOLD=100              # Alert at 100 failures/hour

  # Session freshness
  LOGIN_SESSION_FRESHNESS=60             # 1 minute

  # Features
  LOGIN_ANALYTICS_ENABLED=true
  LOGIN_DEBUG_DECISIONS=false            # MUST be false in production
  LOGIN_DEBUG_QUERIES=false              # MUST be false in production
  LOGIN_DEBUG_CACHE=false                # MUST be false in production
  ```

- [ ] Cache driver configured (Redis recommended):
  ```env
  CACHE_DRIVER=redis
  REDIS_HOST=127.0.0.1
  REDIS_PASSWORD=null
  REDIS_PORT=6379
  ```

- [ ] Log channel configured for login analytics:
  ```env
  LOG_CHANNEL=stack  # Will route to 'login' channel
  ```

### Application Health Check
- [ ] Database connection works
- [ ] Redis connection works (if using Redis cache)
- [ ] File permissions correct on `storage/` and `bootstrap/`
- [ ] `.env` file has correct permissions (644)

---

## Phase 4: Pre-Deployment Announcement (Ops Team)

### Communication
- [ ] Maintenance window scheduled
- [ ] Users notified of potential brief downtime
- [ ] On-call engineer briefed
- [ ] Rollback plan reviewed
- [ ] Support team aware of emergency dashboard URL

### Incident Response Plan
- [ ] PagerDuty/Slack alerts configured
- [ ] Critical alerts channel monitored
- [ ] Ops dashboard has login failure metric
- [ ] Database query logs accessible for debugging

---

## Phase 5: Deployment (Maintenance Mode)

### Before Deployment Begins
```bash
# 1. Enable maintenance mode
php artisan down --secret="[generate-random-uuid]"

# 2. Document the secret
echo "Bypass URL: https://yourapp.com/?bypass=[secret]"

# 3. Verify site is down for regular users
curl https://yourapp.com/  # Should show maintenance page
```

- [ ] Maintenance mode activated
- [ ] Bypass URL accessible to admin team
- [ ] Error page displays to regular users

### Deploy Code Changes
```bash
# 1. Pull latest code
git pull origin main

# 2. Install/update dependencies if needed
composer install --no-dev --optimize-autoloader

# 3. Dump autoloader
composer dump-autoload --optimize
```

- [ ] Code pulled successfully
- [ ] No merge conflicts
- [ ] Dependencies installed
- [ ] Autoloader updated

### Configuration and Cache
```bash
# 1. Clear old cache
php artisan cache:clear

# 2. Clear old config
php artisan config:clear

# 3. Clear view cache
php artisan view:clear

# 4. Recache config
php artisan config:cache

# 5. Recache routes
php artisan route:cache
```

- [ ] Cache cleared
- [ ] Configuration cached
- [ ] Routes cached
- [ ] Views cached

### Run Database Migrations
```bash
# 1. Verify migration is ready
php artisan migrate:status

# 2. Run migrations in production
php artisan migrate --force

# 3. Verify migration completed
php artisan migrate:status | grep "add_onboarding_fields"
```

- [ ] Migration executed successfully
- [ ] New columns added to users table
- [ ] Foreign keys created
- [ ] Rollback verified to work

### Verify New Database Columns
```bash
# Check columns exist
php artisan tinker --execute="
\$columns = DB::getSchemaBuilder()->getColumnListing('users');
echo 'Users table has ' . count(\$columns) . ' columns' . PHP_EOL;
echo 'onboarded_at: ' . (in_array('onboarded_at', \$columns) ? 'YES' : 'NO') . PHP_EOL;
echo 'last_used_organisation_id: ' . (in_array('last_used_organisation_id', \$columns) ? 'YES' : 'NO') . PHP_EOL;
echo 'dashboard_preferences: ' . (in_array('dashboard_preferences', \$columns) ? 'YES' : 'NO') . PHP_EOL;
echo 'last_activity_at: ' . (in_array('last_activity_at', \$columns) ? 'YES' : 'NO') . PHP_EOL;
"
```

- [ ] `onboarded_at` column exists
- [ ] `last_used_organisation_id` column exists
- [ ] `dashboard_preferences` column exists
- [ ] `last_activity_at` column exists
- [ ] Foreign key constraint valid

### Bring Application Online
```bash
# 1. Disable maintenance mode
php artisan up

# 2. Verify site is accessible
curl https://yourapp.com/
curl https://yourapp.com/login
```

- [ ] Maintenance mode disabled
- [ ] Site is accessible
- [ ] No error pages visible
- [ ] Login page loads normally

---

## Phase 6: Post-Deployment Testing

### Functional Testing (Manual)

#### Test User Types
- [ ] New user (first login) → Welcome dashboard
  ```
  Steps:
  1. Create test user
  2. Login with new credentials
  3. Verify redirected to dashboard.welcome
  4. Check logs for "first_time_user" message
  ```

- [ ] Voter with active voting session → Voting dashboard
  ```
  Steps:
  1. Create voter_slug record in active status
  2. Login as that user
  3. Verify redirected to correct voting step
  4. Check logs for "active_voting_session" message
  ```

- [ ] User with single org → Organisation dashboard
  ```
  Steps:
  1. Create user with one organisation role
  2. Login
  3. Verify redirected to organisations.show
  4. Check logs for "organisation_admin_redirect"
  ```

- [ ] User with multiple roles → Role selection
  ```
  Steps:
  1. Create user with multiple roles
  2. Login
  3. Verify redirected to role.selection
  4. Check logs for "multiple_roles" message
  ```

- [ ] Legacy user → Legacy fallback
  ```
  Steps:
  1. Create user with legacy is_voter flag
  2. Login
  3. Verify correct legacy redirect
  4. Check logs for "legacy" message
  ```

#### Test Cache Behavior
- [ ] Cache hit on second login
  ```
  Steps:
  1. Login user (cache miss)
  2. Check logs for "cache miss" message
  3. Logout and login again (cache hit)
  4. Verify faster resolution time
  5. Check logs for "cache_hit" message
  ```

- [ ] Cache invalidation on role change
  ```
  Steps:
  1. Login user (cache created)
  2. Add new organisation role via database
  3. Verify UserOrganisationObserver fires
  4. Check cache is cleared
  5. Login again - should see new resolution
  ```

- [ ] Session freshness validation
  ```
  Steps:
  1. Set user.last_activity_at to 2 minutes ago
  2. Try to login
  3. Verify cache is not used (too old)
  4. Check logs for "session not fresh" message
  ```

#### Test Fallback Chain
- [ ] Normal resolution works (happy path)
  ```bash
  # Monitor logs during login
  tail -f storage/logs/laravel.log | grep "LoginResponse"
  # Should show: "Login successful - user routed" with "resolution_level: normal"
  ```

- [ ] Emergency dashboard accessible at `/dashboard/emergency`
  ```bash
  curl -H "Authorization: Bearer $TOKEN" https://yourapp.com/dashboard/emergency
  # Should return 200 OK with emergency dashboard
  ```

- [ ] Static fallback HTML renders properly
  ```bash
  # Manually trigger fallback by forcing exception in DashboardResolver
  # Should see login-success-fallback.blade.php rendered
  ```

### Performance Testing
- [ ] Login response time < 200ms (avg)
  ```bash
  # Monitor logs
  grep "duration_ms" storage/logs/login.log
  # Check average is < 200ms
  ```

- [ ] Cache hit responses < 50ms
  ```bash
  # Second login should be faster
  # Compare duration_ms between first and second login
  ```

- [ ] No N+1 queries in DashboardResolver
  ```bash
  # Enable query logging
  # Count queries during login resolution
  # Should be < 10 queries total
  ```

### Error Scenarios
- [ ] Simulate database connection failure
  ```bash
  # Temporarily stop MySQL
  # Try to login - should see emergency dashboard
  # Check logs for fallback attempts
  ```

- [ ] Simulate Redis cache failure
  ```bash
  # Temporarily stop Redis
  # Try to login - should work without cache
  # Check logs for cache failure handling
  ```

- [ ] Test maintenance mode allow-list
  ```bash
  # Set LOGIN_ALLOW_USER_IDS=[1,2,3]
  # Enable maintenance mode
  # User 1 should login normally
  # User 4 should see maintenance page
  ```

### Log Verification
```bash
# Check login analytics
tail -100 storage/logs/login.log | grep -E "Login|resolution|cache"

# Check for errors
tail -100 storage/logs/laravel.log | grep -E "ERROR|CRITICAL"

# Check performance
tail -100 storage/logs/login.log | grep "duration_ms" | awk -F'duration_ms": ' '{print $2}' | sort -n
```

- [ ] Login events logged correctly
- [ ] No errors in error log
- [ ] Performance metrics normal
- [ ] Cache invalidation events recorded

---

## Phase 7: Rollback Plan (If Issues Detected)

### Immediate Rollback
```bash
# 1. Enable maintenance mode
php artisan down --secret="[secret]"

# 2. Revert code changes
git revert HEAD

# 3. Clear cache
php artisan cache:clear
php artisan config:clear

# 4. Rollback migration (CAREFUL - may lose data)
php artisan migrate:rollback

# 5. Clear cache again
php artisan cache:clear

# 6. Bring site back up
php artisan up
```

### If Rollback Required
- [ ] Code reverted to previous version
- [ ] Migration rolled back
- [ ] Cache cleared
- [ ] Site restored to working state
- [ ] Users notified

---

## Phase 8: Post-Deployment Monitoring (24-48 hours)

### Metrics to Monitor
- [ ] Login success rate: >= 99.5%
- [ ] Login resolution time: < 200ms average
- [ ] Emergency dashboard usage: < 0.1%
- [ ] Static fallback usage: 0% (should never trigger)
- [ ] Cache hit ratio: >= 80%
- [ ] Database errors: 0

### Log Analysis Commands
```bash
# Monitor login success rate
grep "Login successful" storage/logs/login.log | wc -l
grep -E "ERROR|CRITICAL" storage/logs/login.log | wc -l

# Average resolution time
grep "duration_ms" storage/logs/login.log | \
  awk -F'duration_ms": ' '{print $2}' | \
  awk -F',' '{print $1}' | \
  awk '{sum+=$1; count++} END {print "Average: " sum/count "ms"}'

# Fallback usage
grep "emergency_dashboard_accessed\|static_html_fallback" storage/logs/login.log | wc -l

# Cache hit ratio
grep "cache_hit" storage/logs/login.log | wc -l
grep "cache_miss" storage/logs/login.log | wc -l
```

### Alerts to Configure
```yaml
# Example Prometheus/Grafana alerts
- name: LoginResolutionSlow
  condition: p99(login_resolution_duration_ms) > 1000
  severity: warning

- name: LoginFailureRate
  condition: login_failure_count/hour > 100
  severity: critical

- name: EmergencyDashboardUsage
  condition: emergency_dashboard_visits/hour > 10
  severity: warning

- name: FallbackActivated
  condition: static_fallback_usage/hour > 0
  severity: critical
```

- [ ] Monitoring dashboard configured
- [ ] Alerts configured and tested
- [ ] On-call engineer alerted to new metrics
- [ ] Baseline metrics established

---

## Phase 9: Documentation and Handoff

### Update Documentation
- [ ] README updated with new configuration options
- [ ] Architecture docs updated with fallback chain diagram
- [ ] Runbook created for ops team
- [ ] Observer pattern documented
- [ ] VotingStep enum usage documented

### Team Knowledge Transfer
- [ ] Ops team trained on emergency dashboard
- [ ] Developers understand fallback chain
- [ ] Support team knows about fallback routes
- [ ] Monitoring team has metric explanations
- [ ] Incident response plan reviewed

### Version Control
```bash
# Tag the deployment
git tag -a deployment/login-routing-v1.0.0 -m "Login routing 3-level fallback deployment"
git push origin deployment/login-routing-v1.0.0
```

- [ ] Deployment tagged in Git
- [ ] Changelog updated
- [ ] Release notes published

---

## Phase 10: Sign-Off and Completion

### Final Checklist
- [ ] All tests passed
- [ ] No errors in logs after 24 hours
- [ ] Performance metrics normal
- [ ] Monitoring alerts working
- [ ] Team trained and ready
- [ ] Documentation complete
- [ ] Rollback plan verified
- [ ] Customer notification sent (if applicable)

### Sign-Off
- [ ] Development Lead: _________________ Date: _______
- [ ] Ops Lead: _________________ Date: _______
- [ ] Product Owner: _________________ Date: _______

---

## Emergency Contacts

| Role | Name | Phone | Email |
|------|------|-------|-------|
| On-Call Engineer | | | |
| Ops Lead | | | |
| DBA | | | |
| Security | | | |

---

## Useful Commands During Deployment

```bash
# Monitor logs in real-time
tail -f storage/logs/login.log

# Check database integrity
php artisan tinker --execute="
\$count = DB::table('users')->whereNotNull('onboarded_at')->count();
echo 'Users with onboarded_at: ' . \$count;
"

# Test cache
php artisan tinker --execute="
Cache::put('test_key', 'test_value', 60);
echo 'Cache: ' . Cache::get('test_key');
Cache::forget('test_key');
"

# Test observer
php artisan tinker --execute="
Log::info('Observer test');
"

# Verify routes registered
php artisan route:list | grep dashboard

# Check config loaded
php artisan config:show login-routing

# Test email fallback
php artisan tinker --execute="
Mail::raw('Test', function(\$m) { \$m->to('test@example.com'); });
"
```

---

## Success Criteria

✅ **Deployment is successful when:**

1. All 3 fallback levels function correctly
2. Login response time < 200ms average
3. Cache hit ratio > 80%
4. Zero production errors after 24 hours
5. All user types route to correct dashboard
6. Emergency dashboard never needs to be used
7. Static HTML fallback never triggers
8. Team trained and confident
9. Monitoring/alerts active
10. Rollback plan verified

---

**Document Version:** 1.0
**Last Updated:** {{ date('Y-m-d H:i:s') }}
**Next Review Date:** [30 days from deployment]
