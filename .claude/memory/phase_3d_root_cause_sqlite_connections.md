---
name: Phase 3D root cause - SQLite in-memory multi-connection issue
description: Definitive diagnosis of why 3 approval tests fail - database connection isolation with in-memory SQLite
type: project
originSessionId: 0b8d9422-360e-4388-83e6-9972ffe33c53
---
## CONFIRMED ROOT CAUSE: SQLite In-Memory Multiple Connections

### The Problem
SQLite in-memory (`:memory:`) creates a **separate independent database instance for each connection**. When Laravel's test framework makes HTTP requests, new database connections are opened, each with their own in-memory database.

### Evidence (from diagnostic logging 2026-05-03 15:02+)

**Repository's raw SQL query sees:**
```
db_status: "approved"  ← Application was correctly saved
```

**Test assertion's raw SQL query sees:**
```
status: "submitted"  ← Same ID, different value!
```

Both queries ran within the same test, on the same data, but saw different values because they used different database connections accessing different in-memory databases.

### Why This Affects Only the 3 Approval Tests

1. **Submission tests (✅ working)** - Create record directly in test, query directly. Same connection = same database.
2. **Approval tests (❌ failing)** - Test creates record → HTTP request → controller saves via different connection → test asserts via original connection. Three different database instances.

### Why `RefreshDatabase` and `DatabaseTransactions` Didn't Fix It

Both traits wrap tests in transactions at the Laravel level, but SQLite's in-memory still creates separate database instances per connection. Transaction wrapping doesn't merge the databases.

### Solution Options

| Option | Pros | Cons |
|--------|------|------|
| **File-based SQLite** | Fast, local, all connections access same file | Requires absolute path, needs cleanup between tests |
| **PostgreSQL for tests** | Real database server, no connection issues | Need running Postgres, slower, can't use in-memory |
| **SQLite shared cache URI** | Single DB instance, all connections access it | Not widely supported in Laravel/PDO, URI syntax issues |
| **Mock HTTP requests differently** | Avoid separate connections | Complex, requires significant refactoring |

### Current Status
- 11/14 tests passing ✅
- 3 tests failing due to SQLite in-memory ❌
- Root cause **definitively identified and confirmed**

### Next Step
Awaiting user decision on which solution to implement.
