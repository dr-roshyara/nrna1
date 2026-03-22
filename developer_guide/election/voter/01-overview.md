# 01 — Overview

## The Problem This Solves

Before this system, a user's "voter status" was stored as a single boolean (`is_voter`) on the `users` table. That design has a fundamental flaw: **it assumes a user can only ever vote in one election**. The moment an organisation runs a second election, or a platform user belongs to two different organisations, the model collapses.

The old model also had no way to answer questions like:

- Who is eligible to vote in *this specific* election?
- When was this person added as a voter, and by whom?
- Is this voter's eligibility still current, or has it expired?
- What happens to voter access if a user leaves the organisation?

The `election_memberships` table answers all of these questions while enforcing integrity at the **database level**, not just in PHP.

---

## Core Concept

An `ElectionMembership` is the join between a **User**, an **Organisation**, and an **Election**. It represents the fact that:

> "This user is allowed to participate in this election, in this specific capacity."

```
users ──────────────────────────────────────────────────────────────────┐
                                                                         │
user_organisation_roles ─────────────────────────────────────────────── │ (user is a member of org)
                                                                         │
elections ──────────────────────────────────────────────────────────────┤
                                                                         ▼
                                                          election_memberships
                                                          ┌───────────────────────┐
                                                          │ user_id               │
                                                          │ organisation_id       │
                                                          │ election_id           │
                                                          │ role (voter/candidate)│
                                                          │ status (active/etc.)  │
                                                          │ expires_at            │
                                                          │ assigned_by           │
                                                          │ metadata (JSON)       │
                                                          └───────────────────────┘
```

---

## Key Design Decisions

### 1. Composite Foreign Keys — Not Just Application Validation

The database enforces two rules that no PHP code can bypass:

**Rule A — User must belong to the organisation:**
```sql
FOREIGN KEY (user_id, organisation_id)
    REFERENCES user_organisation_roles(user_id, organisation_id)
    ON DELETE CASCADE
```
If you try to add a voter from the wrong organisation, MySQL rejects it with error 1452. The application never sees a bad record.

**Rule B — Election must belong to the same organisation:**
```sql
FOREIGN KEY (election_id, organisation_id)
    REFERENCES elections(id, organisation_id)
    ON DELETE CASCADE
```
This is why `elections` has a `UNIQUE(id, organisation_id)` key — MySQL requires the referenced columns to form a key.

**Cascade behaviour:** If a user is removed from `user_organisation_roles` (leaves the organisation), all their `election_memberships` rows are automatically deleted. Tenant isolation is self-maintaining.

### 2. `organisation_id` Is Denormalised

`organisation_id` appears in `election_memberships` even though it could be derived from `election_id → elections.organisation_id`. This is intentional. The composite FKs require it to be physically present so MySQL can enforce both constraints in a single row check. It also makes tenant-scoped queries faster — no join needed.

### 3. One Role Per User Per Election

```sql
UNIQUE (user_id, election_id)
```

A user cannot be both a voter and a candidate in the same election. If requirements change, the unique constraint can be relaxed, but the default is strict.

### 4. Cache Strategy — Option B (No Redis Tags)

The platform uses `CACHE_DRIVER=file`. The `Cache::tags()` API requires a driver that supports it (Redis, Memcached). File driver will throw `BadMethodCallException` on tag calls.

The system therefore uses **explicit key invalidation**:

```php
Cache::forget("election.{$id}.voter_count");
Cache::forget("election.{$id}.voter_stats");
```

This is slightly more verbose than tags but works in every environment. See [06-caching.md](./06-caching.md) for the full pattern.

---

## Roles and Statuses

### Roles

| Value | Meaning |
|-------|---------|
| `voter` | Can cast a vote |
| `candidate` | Is standing for a position |
| `observer` | Read-only access to the election |
| `admin` | Election administrator |

The system currently uses `voter` exclusively. Other roles are reserved for future expansion.

### Statuses

| Value | Meaning |
|-------|---------|
| `invited` | Invited but not yet accepted |
| `active` | Eligible to participate |
| `inactive` | Membership deactivated (e.g. after voting) |
| `removed` | Explicitly removed, reason stored in `metadata` |

---

## Relationship to Other Systems

| System | Relationship |
|--------|-------------|
| `VoterRegistration` | Older table that tracks per-election voter approval flow. `ElectionMembership` is the eligibility record; `VoterRegistration` is the approval workflow. They coexist. |
| `VoterSlug` | Created when a voter starts the voting flow. Requires the voter to exist in `election_memberships` first. |
| `Code` / `DemoCode` | Voting codes are issued after eligibility is confirmed via `election_memberships`. |
| `BelongsToTenant` | The `Election` model uses this global scope. All relationships from `ElectionMembership` to `Election` must call `.withoutGlobalScopes()` to bypass session-based tenant filtering. |
