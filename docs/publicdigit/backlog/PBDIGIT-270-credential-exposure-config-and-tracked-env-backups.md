# PBDIGIT-270: Credential exposure — committed database password and tracked environment backups

**Type:** Security defect story (standalone) · **Epic:** none (security) · **Created:** 2026-10-10 · **Raised by:** `claude-code-session:42e0287e…` (self-declared, not attestable), on the Human PO / ARB decision P2-5 (2026-10-10; plan `docs/plans/20261010-1830-membership-pg-verification-and-far-date-plan.md`, "Part 2 gate register"). **ID note:** the next free ID (`git grep "PBDIGIT-270"` and the untracked files: no occurrence). **Index row:** added 2026-10-10.

| | |
|---|---|
| **Status** | `OPEN` · **not authorised**. Creating this story authorises no change, no credential rotation, no file reading and no history rewrite |
| **Owner** | **to be named:** an authorised security / infrastructure owner |
| **Priority** | to be set by the owner. Committed secrets are treated as **potentially exposed** |
| **Source** | PostgreSQL Part 2 readiness investigation, 2026-10-10 (security findings S-1, S-2), found while checking which database connections the Membership tests can open |
| **Not part of** | `PBDIGIT-264` (Membership persistence follow-ups) |

## Findings

**S-1: a plaintext database password in tracked configuration.**
- `config/database.php`, connection `testing` (lines ~140–150), hard-codes host, database `nrna_test`, role `nrna` **and a password**.
- Introduced by `e53d8a321` (2026-02-25, "test: Configure test database infrastructure with RefreshDatabase").
- The value is deliberately **not reproduced** here or in any evidence.

**S-2: environment backup files tracked in git.**
- `.env.back_20260419_0138` and `.env.backup-laravel12` are tracked (`git ls-files`).
- Their contents were **not read**. Environment files usually contain credentials, so treat them as potentially exposed until an authorised person has triaged them.

## Recommended actions (for the owner; each needs its own authorisation)

1. Assess and **rotate** the `nrna` database credential, and any credential S-2 turns out to contain.
2. Move the `testing` connection's credentials to environment or secret management; no secret in tracked configuration.
3. An authorised person triages the two backup files **without copying values** into tickets, reports, logs or chat.
4. Stop tracking the backup files through a reviewed change (`git rm --cached`, plus `.gitignore` coverage).
5. Decide **separately** whether to clean git history. Rewriting shared history disrupts other sessions and clones, so it needs its own decision.

## Acceptance criteria

- No credential in any tracked file (verified by an agreed secret scan).
- The exposed credentials are rotated, confirmed by the owner without disclosing values.
- The backup files are no longer tracked, and `.gitignore` prevents re-adding them.
- A recorded decision on history cleanup.

## Rules

- No credential value in this story, its commits, evidence or any report.
- Claude does not read the backup files, print credentials, rotate passwords or rewrite history without explicit, separate authorisation.

## Relation to Membership Part 2

The `testing` connection cannot be redirected by `DB_*`. The Part 2 harness refuses it at the guard's pre-connect stage (plan, "Pre-connect guard"), so Part 2 does not depend on this story. Fixing S-1 removes the hazard at its source.
