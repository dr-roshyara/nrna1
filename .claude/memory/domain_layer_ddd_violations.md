---
name: Domain Layer DDD Violations - Critical
description: 10 critical architectural violations preventing DDD compliance; requires 6-week refactoring
type: project
originSessionId: be14e6db-faf3-4787-84c8-a39f1f385592
---
# Domain Layer Architecture Issues

## Critical Status: 🔴 FAILS 10/10 DDD Rules

The `app/Domain` layer violates CLAUDE.md rules preventing proper multi-tenant enforcement:

### Key Violations:
1. **Controllers in Domain folder** - Should be in `app/Http/Controllers`
2. **Framework dependencies in Domain** - Uses `Illuminate\*` facades (violates Rule 4)
3. **No TenantId in Domain models** - Cannot enforce tenant boundaries (violates Rule 1)
4. **No Repository pattern** - Controllers access persistence directly (violates Rule 2)
5. **No Value Objects** - Anemic domain with business logic in controllers (anti-pattern)
6. **No Domain Events** - Missing event-driven architecture (violates Rule 8)
7. **Hardcoded configuration** - Email addresses, countries hardcoded in code
8. **No validation in Domain** - Only controller-level validation exists
9. **No tenant-aware services** - Services lack TenantId enforcement
10. **Transaction Script pattern** - Logic scattered in controllers, not DDD services

## Immediate Actions Required:
- Move `app/Domain/Finance/Controllers/*` → `app/Http/Controllers/Finance/*`
- Remove all `Illuminate\*` imports from Domain services
- Add `TenantId` value object to all Domain models
- Create Repository interfaces (ForTenant naming pattern)
- Implement 6-week refactoring plan (see audit report)

## Files Affected:
- `app/Domain/Finance/Controllers/IncomeController.php` - Move to HTTP layer
- `app/Domain/Finance/Controllers/OutcomeController.php` - Move to HTTP layer
- `app/Domain/Finance/Services/FinanceNotificationService.php` - Remove framework deps
- `app/Domain/Finance/Models/Income.php` - Add business logic
- `app/Domain/Finance/Models/Outcome.php` - Add business logic

## Compliance: Currently 0% DDD-compliant with your CLAUDE.md rules
