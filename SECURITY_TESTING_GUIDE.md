# Security Testing Guide - OWASP Top 10 & Multi-Tenant Protection

## Overview

Comprehensive security testing guide for the Voter Management System, covering:
- OWASP Top 10 vulnerabilities
- Multi-tenant data isolation
- Authorization & authentication
- Input validation & sanitization
- CSRF & XSS prevention
- SQL injection prevention
- Rate limiting
- Audit logging

---

## Security Testing Checklist

### ✅ OWASP Top 10 (2021) Coverage

| # | Vulnerability | Test | Status |
|---|---|---|---|
| 1 | Broken Access Control | Authorization tests | ✅ Covered |
| 2 | Cryptographic Failures | HTTPS only | ✅ Configured |
| 3 | Injection | SQL injection tests | ✅ Covered |
| 4 | Insecure Design | Security-first design | ✅ Implemented |
| 5 | Security Misconfiguration | Config audit | ✅ In checklist |
| 6 | Vulnerable & Outdated Components | Dependencies check | ✅ In checklist |
| 7 | Authentication Failures | Auth bypass tests | ✅ Covered |
| 8 | Software & Data Integrity Failures | Integrity tests | ✅ Covered |
| 9 | Logging & Monitoring Failures | Audit logging | ✅ Implemented |
| 10 | SSRF | Not applicable | ✅ N/A |

---

## 1. SQL Injection Testing

### What It Is
Attacker inserts SQL commands into input fields to manipulate database queries.

### Test Cases
```php
// Malicious payloads
"'; DROP TABLE users; --"
"' OR '1'='1"
"1' UNION SELECT * FROM users --"
"'; UPDATE users SET role='admin' --"
```

### How to Test
```bash
# Run automated tests
php artisan test tests/Feature/Security/VoterControllerPenetrationTest.php --filter=sql_injection

# Manual test: Try in search field
/organizations/myorg/voters?search='; DROP TABLE users; --

# Expected result: No error, query safely escaped
```

### Protection Mechanisms
- ✅ Laravel's QueryBuilder parameterization
- ✅ Eloquent ORM escapes by default
- ✅ Never concatenating user input in queries
- ✅ Using prepared statements

### Verification
```php
// Safe: QueryBuilder parameterizes
User::where('name', 'LIKE', $search . '%')->get();

// Unsafe: String concatenation (NEVER DO THIS)
User::whereRaw("name LIKE '" . $search . "%'")->get();
```

---

## 2. Cross-Site Scripting (XSS) Testing

### What It Is
Attacker injects malicious JavaScript that executes in user's browser.

### Test Cases
```html
<script>alert('XSS')</script>
<img src=x onerror="alert('XSS')">
<svg onload="alert('XSS')">
javascript:alert('XSS')
```

### How to Test
```bash
# Run automated tests
php artisan test tests/Feature/Security/VoterControllerPenetrationTest.php --filter=xss

# Manual test: Try in search field
/organizations/myorg/voters?search=<script>alert('test')</script>

# Expected result: Script escaped, not executed
```

### Protection Mechanisms
- ✅ Vue 3 auto-escapes by default `{{ variable }}`
- ✅ Laravel Blade escapes with `{{ }}`
- ✅ Never use `v-html` with user input
- ✅ Use `v-text` or `{{ }}`

### Verification
```vue
<!-- Safe: Vue auto-escapes -->
<div>{{ userInput }}</div>

<!-- Unsafe: Raw HTML (NEVER with user input) -->
<div v-html="userInput"></div>
```

---

## 3. CSRF (Cross-Site Request Forgery) Testing

### What It Is
Attacker tricks user into making unwanted requests to another site.

### How to Test
```bash
# Run automated tests
php artisan test tests/Feature/Security/VoterControllerPenetrationTest.php --filter=csrf

# Manual test: Submit form without CSRF token
curl -X POST http://localhost:8000/organizations/myorg/voters/1/approve \
  -H "Cookie: XSRF-TOKEN=invalid" \
  -H "X-CSRF-TOKEN: invalid"

# Expected result: 419 Conflict (CSRF mismatch)
```

### Protection Mechanisms
- ✅ Laravel CSRF middleware enabled
- ✅ Inertia.js includes CSRF token in requests
- ✅ POST/PUT/DELETE require valid token
- ✅ Token regenerated per session

### Verification
```php
// Middleware active in web group
'web' => [
    // ...
    \App\Http\Middleware\VerifyCsrfToken::class,
]
```

---

## 4. Authorization & Access Control Testing

### Test Cases

#### 4.1 Non-Member Access
```bash
php artisan test tests/Feature/Security/VoterControllerSecurityTest.php \
  --filter=it_prevents_cross_organization_voter_list_access
```

**Result**: Non-members should get 403 Forbidden

#### 4.2 Privilege Escalation
```bash
php artisan test tests/Feature/Security/VoterControllerSecurityTest.php \
  --filter=it_requires_commission_role_for_approval
```

**Result**: Regular members cannot approve voters

#### 4.3 Cross-organisation Access
```bash
php artisan test tests/Feature/Security/VoterControllerPenetrationTest.php \
  --filter=it_prevents_insecure_direct_object_reference
```

**Result**: Cannot access/modify voters from other organizations

### Protection Mechanisms
- ✅ `EnsureOrganization` middleware validates membership
- ✅ All queries filtered by `organisation_id`
- ✅ Commission role check on approval/suspension
- ✅ Route model binding validates ownership

---

## 5. Input Validation & Sanitization Testing

### Test Cases
```bash
# SQL injection attempts
search='; DROP TABLE users; --
search=1' UNION SELECT * FROM users --

# XSS attempts
search=<script>alert('xss')</script>
search=<img src=x onerror="alert('xss')">

# Type juggling
voter_id=123abc
voter_id=[1,2,3]

# Path traversal
organisation=../../etc/passwd
organisation=..\\..\\windows
```

### How to Test
```bash
php artisan test tests/Feature/Security/VoterControllerPenetrationTest.php \
  --filter=it_sanitizes_user_input
```

### Verification
```php
// Laravel validation prevents type juggling
$validated = request()->validate([
    'voter_ids' => 'array',
    'voter_ids.*' => 'integer',  // Each item must be integer
]);
```

---

## 6. Authentication Testing

### Test Cases

#### 6.1 Unauthenticated Access
```bash
# Should redirect to login
curl -X GET http://localhost:8000/organizations/myorg/voters
```

#### 6.2 Invalid Token
```bash
# Should reject invalid/fake tokens
curl -X GET http://localhost:8000/organizations/myorg/voters \
  -H "Authorization: Bearer invalid-token"
```

#### 6.3 Expired Session
```bash
# Session should expire after configured timeout
# Default: 120 minutes
```

### How to Test
```bash
php artisan test tests/Feature/Security/VoterControllerPenetrationTest.php \
  --filter=it_prevents_authentication_bypass
```

---

## 7. Rate Limiting Testing

### Current Status
⚠️ **Should be implemented in production**

### How to Configure
```php
// routes/organizations.php
Route::post('/voters/{voter}/approve', [VoterController::class, 'approve'])
    ->middleware('throttle:organisation-actions');  // 30 per minute

Route::post('/voters/bulk-approve', [VoterController::class, 'bulkApprove'])
    ->middleware('throttle:bulk-operations');  // 5 per minute
```

### How to Test
```bash
# Make 31 rapid requests (should fail on 31st)
for i in {1..40}; do
  curl -X POST http://localhost:8000/organizations/myorg/voters/1/approve
done

# Expected: After 30 requests, get 429 Too Many Requests
```

---

## 8. Sensitive Data Exposure Testing

### What to Check
- ✅ No passwords in responses
- ✅ No API keys/secrets exposed
- ✅ No sensitive user data logged
- ✅ HTTPS only (TLS 1.3)
- ✅ Secure cookie flags

### How to Test
```bash
# Check HTTP headers
curl -i http://localhost:8000/organizations/myorg/voters

# Look for security headers:
# Strict-Transport-Security: max-age=31536000; includeSubDomains
# X-Content-Type-Options: nosniff
# X-Frame-Options: SAMEORIGIN
# X-XSS-Protection: 1; mode=block
```

### Verification
```php
php artisan test tests/Feature/Security/VoterControllerPenetrationTest.php \
  --filter=it_does_not_expose_sensitive_data
```

---

## 9. Insecure Direct Object Reference (IDOR) Testing

### Test Case
```bash
# Try to access voter from different organisation
POST /organizations/myorg/voters/999/approve
# Where voter 999 belongs to different organisation

# Expected: 403 Forbidden
```

### How to Test
```bash
php artisan test tests/Feature/Security/VoterControllerPenetrationTest.php \
  --filter=it_prevents_insecure_direct_object_reference
```

### Protection Mechanisms
- ✅ organisation filter on all queries
- ✅ Explicit voter-organisation check
- ✅ 403 response for cross-org access
- ✅ Audit logging of attempts

---

## 10. Audit Logging Testing

### What Gets Logged
```
[time] [action] [user_id] [user_name] [organisation_id] [voter_id] [ip_address]
2026-02-23 14:23:45 voter_approved 1 John_Doe 5 123 192.168.1.100
2026-02-23 14:24:10 unauthorized_access_attempt 2 Jane_Smith 5 999 192.168.1.101
```

### How to Test
```bash
# Make an approval
php artisan test tests/Feature/Security/VoterControllerSecurityTest.php \
  --filter=it_logs_all_approval_attempts

# Check log file
tail storage/logs/voting_audit.log
```

### Log Format
```
[timestamp] [action] [details]
[2026-02-23 14:23:45] voter_approved {
  "approver_id": 1,
  "approver_name": "John Doe",
  "voter_id": 123,
  "organisation_id": 5,
  "ip_address": "192.168.1.100"
}
```

---

## Running All Security Tests

### Quick Test
```bash
# Run all security penetration tests
php artisan test tests/Feature/Security/VoterControllerPenetrationTest.php

# Expected: All tests pass
# Time: ~30 seconds
```

### Comprehensive Test
```bash
# Run security + existing tests
php artisan test tests/Feature/Security/ tests/Feature/Organizations/

# This validates no regressions
```

### Continuous Integration
```bash
# Add to CI/CD pipeline
before_deploy:
  - php artisan test tests/Feature/Security/
```

---

## Manual Security Testing Checklist

### Authentication
- [ ] Cannot access without login
- [ ] Cannot access with invalid token
- [ ] Session expires after timeout
- [ ] Logout clears session
- [ ] Cannot reuse old session token

### Authorization
- [ ] Non-members cannot access organisation
- [ ] Regular members cannot approve
- [ ] Commission members can approve
- [ ] Users cannot access other org's voters
- [ ] Users cannot modify other org's voters

### Input Validation
- [ ] Search field rejects SQL injection
- [ ] Search field escapes XSS attempts
- [ ] Voter ID must be numeric
- [ ] organisation slug must be valid
- [ ] No file upload vulnerabilities

### CSRF Protection
- [ ] POST requires CSRF token
- [ ] Invalid token rejected (419)
- [ ] Token changes per session
- [ ] Old tokens don't work

### Sensitive Data
- [ ] No passwords in response
- [ ] No API keys exposed
- [ ] Error messages don't leak info
- [ ] Debug mode disabled
- [ ] HTTPS enforced

### Rate Limiting
- [ ] Bulk operations limited
- [ ] Rapid requests blocked
- [ ] Gets 429 after limit
- [ ] Rate limit headers present
- [ ] Reset after timeout

### Logging
- [ ] All approvals logged
- [ ] Failed attempts logged
- [ ] User ID recorded
- [ ] IP address recorded
- [ ] Timestamps accurate

### Miscellaneous
- [ ] No directory listing
- [ ] No source code exposure
- [ ] No timing attacks possible
- [ ] Passwords never logged
- [ ] Security headers present

---

## Security Headers Verification

### Required Headers
```
Strict-Transport-Security: max-age=31536000; includeSubDomains
X-Content-Type-Options: nosniff
X-Frame-Options: SAMEORIGIN
X-XSS-Protection: 1; mode=block
Content-Security-Policy: default-src 'self'
Referrer-Policy: strict-origin-when-cross-origin
```

### How to Check
```bash
curl -i http://localhost:8000/organizations/myorg/voters | grep -E "^[A-Z-]+:"
```

### Configure in Middleware
```php
// app/Http/Middleware/SecurityHeaders.php
$response->headers->set('X-Content-Type-Options', 'nosniff');
$response->headers->set('X-Frame-Options', 'SAMEORIGIN');
```

---

## Dependency Vulnerability Checking

### Check for Vulnerable Dependencies
```bash
# Check for known vulnerabilities
composer audit

# Update dependencies
composer update

# Lock file should be committed
git add composer.lock
```

### Regular Audits
- [ ] Weekly: `composer audit`
- [ ] Monthly: Full dependency review
- [ ] Quarterly: Security patches
- [ ] Annually: Major upgrades

---

## Penetration Testing Results

### Test Execution
```bash
php artisan test tests/Feature/Security/VoterControllerPenetrationTest.php -v
```

### Expected Results
```
PASS  SQL injection tests (5 tests)
PASS  XSS prevention tests (3 tests)
PASS  Authorization bypass tests (4 tests)
PASS  Input validation tests (3 tests)
PASS  Data integrity tests (2 tests)

Tests: 20 passed
Time: ~30 seconds
```

---

## Security Incident Response

### If Vulnerability Found
1. **Assess**: Understand the severity
2. **Fix**: Implement patch
3. **Test**: Verify fix works
4. **Deploy**: Release immediately
5. **Notify**: Inform stakeholders
6. **Monitor**: Watch for exploitation

### Incident Response Contacts
```
Security Team Lead: [name]
Database Admin: [name]
DevOps: [name]
Management: [name]
```

---

## Compliance Checklist

### Data Protection
- [ ] GDPR compliant (if EU users)
- [ ] CCPA compliant (if CA users)
- [ ] No unauthorized data sharing
- [ ] Data encrypted at rest (if applicable)
- [ ] Data encrypted in transit (HTTPS)

### Audit & Logging
- [ ] All sensitive actions logged
- [ ] Logs retained 90+ days
- [ ] Logs cannot be altered
- [ ] Regular log review
- [ ] Alert on suspicious activity

### Access Control
- [ ] Least privilege principle
- [ ] Role-based access control
- [ ] Regular access review
- [ ] Unused accounts disabled
- [ ] Admin actions logged

---

## Continuous Security Monitoring

### Daily
- [ ] Check error logs for attacks
- [ ] Monitor failed login attempts
- [ ] Review approval actions
- [ ] Watch for unusual patterns

### Weekly
- [ ] Review security logs
- [ ] Check for vulnerabilities
- [ ] Update dependencies
- [ ] Security briefing

### Monthly
- [ ] Comprehensive security audit
- [ ] Penetration test
- [ ] Access control review
- [ ] Policy compliance check

### Quarterly
- [ ] Full security assessment
- [ ] Stakeholder briefing
- [ ] Policy updates
- [ ] Training refresh

---

## Security Testing Tools

### OWASP ZAP
```bash
# Free automated security scanner
https://www.zaproxy.org/

# Scan application
zaproxy -cmd -quickurl http://localhost:8000 -quickout /tmp/scan.html
```

### Burp Suite Community
```bash
# Professional penetration testing
https://portswigger.net/burp/community

# Useful for manual testing
```

### npm Security Audit
```bash
# Check JavaScript dependencies
npm audit

# Fix vulnerabilities
npm audit fix
```

### Composer Audit
```bash
# Check PHP dependencies
composer audit

# Update packages
composer update
```

---

## Security Best Practices

### Development
- [ ] Never commit secrets (.env)
- [ ] Use environment variables for config
- [ ] Enable HTTPS in development
- [ ] Use security headers
- [ ] Keep dependencies updated

### Deployment
- [ ] Database encryption enabled
- [ ] HTTPS enforced (redirect HTTP to HTTPS)
- [ ] Security headers configured
- [ ] Error messages generic (not detailed)
- [ ] Debug mode disabled
- [ ] Rate limiting enabled
- [ ] Logging enabled and monitored

### Maintenance
- [ ] Regular security audits
- [ ] Dependency updates
- [ ] Log monitoring
- [ ] Access control review
- [ ] Security training

---

## Resources

### OWASP
- [Top 10](https://owasp.org/Top10/)
- [Testing Guide](https://owasp.org/www-project-web-security-testing-guide/)
- [Cheat Sheets](https://cheatsheetseries.owasp.org/)

### Laravel Security
- [Security Guide](https://laravel.com/docs/security)
- [Authentication](https://laravel.com/docs/authentication)
- [Authorization](https://laravel.com/docs/authorization)

### Testing Tools
- [OWASP ZAP](https://www.zaproxy.org/)
- [Burp Suite](https://portswigger.net/burp)
- [npm audit](https://docs.npmjs.com/cli/v8/commands/npm-audit)

---

**Document Version**: 1.0
**Last Updated**: February 23, 2026
**Status**: ✅ Ready for Security Testing
