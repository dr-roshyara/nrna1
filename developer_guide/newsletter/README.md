# Organisation Newsletter System — Developer Guide

## Overview

The newsletter system allows organisation admins and owners to compose, preview, and send bulk emails to all active members. It is built with a production-grade architecture: idempotent delivery, a campaign state machine, a kill switch for runaway failures, unsubscribe tokens, bounce suppression, and an immutable audit log.

---

## Table of Contents

### For Users (Admins & Owners)

- [User Guide](user_guide.md) — complete reference for organisation admins
- [Tutorial: How to Send a Newsletter](tutorial_how_to_send_a_newsletter.md) — step-by-step walkthrough from draft to delivery

### For Developers

1. [Architecture](01-architecture.md)
2. [Database Schema](02-database-schema.md)
3. [Campaign State Machine](03-state-machine.md)
4. [Service Layer](04-service-layer.md)
5. [Queue Jobs](05-queue-jobs.md)
6. [Kill Switch](06-kill-switch.md)
7. [Unsubscribe & Bounce](07-unsubscribe-bounce.md)
8. [Controllers & Routes](08-controllers-routes.md)
9. [Testing Guide](09-testing.md)
10. [Troubleshooting](10-troubleshooting.md)

---

## Quick Start

```bash
# 1. Run migrations (already run — for new environments)
php artisan migrate

# 2. Run tests
php artisan test tests/Feature/Organisation/Newsletter --no-coverage

# 3. Start queue worker for email delivery
php artisan queue:work --queue=emails-normal

# 4. Visit newsletter management
# http://localhost:8000/organisations/{slug}/membership/newsletters
```

---

## Key Facts

| Item | Value |
|------|-------|
| Route prefix | `organisations.membership.newsletters.*` |
| Queue | `emails-normal` |
| Auth | Admin or Owner role only |
| Unsubscribe | Public URL, no auth required |
| Kill switch threshold | >20% failure rate after ≥50 sends |
| HTML sanitisation | `strip_tags()` with allowed-tag allowlist |
| Idempotency | SHA-256 lock per recipient via Redis |
