# Multi-database + organisation hostnames — investigation report

| | |
|---|---|
| **Status** | **BRAINSTORMING / architecture investigation.** Not an ADR, not an approved plan. Nothing here is authorised for implementation (EP-01 still applies). |
| **Date** | 2026-10-06 |
| **Question** | Can `nrna1` become *one organisation = one tenant = one hostname (`<org>.publicdigit.com`) = one PostgreSQL database*, with the smallest safe change? |
| **Answer (short)** | **Yes, with conditions.** See `01-executive-summary.md` and `16-final-recommendation.md`. |
| **Implementation performed** | **None.** No source code, migration, config, `.env`, database, DNS or infrastructure was changed. Only the files in this folder listed below were created. |

## What was investigated

| Repository | Path | Role | Access |
|---|---|---|---|
| `nrna1` | `/home/nab-raj.roshyara@dg-nexolution.de/roshyara/personal/nrna1` (same checkout as `/home/d0f38614-…/nrna1`), branch `knowelegeos-modelling` | The system to evolve | read-only |
| `election` | `/home/nab-raj.roshyara@dg-nexolution.de/roshyara/personal/election` | Reference implementation of multi-DB tenancy — **reverse-engineered, not trusted** | read-only |

Areas covered:
- organisation resolution, routing, middleware;
- the full table inventory (95 tables);
- foreign keys;
- raw SQL and transactions;
- authentication, sessions, cookies, Sanctum, CORS and proxies;
- URL generation in mails and jobs; OAuth;
- queues, schedule, outbox, cache, files and logging;
- infrastructure files;
- slug and DNS constraints;
- `election`'s hostname resolution, provisioning, sessions, queues, tests and infrastructure.

## Relationship to existing files in this folder (read-only, not modified)

| File | Relationship |
|---|---|
| `firrst_prompt.md` | Earlier commission (persistence-focused) |
| `MULTI_DATABASE_ARCHITECTURE_ASSESSMENT.md` | Earlier assessment (same day) answering `firrst_prompt.md`. **This report builds on it and reuses its measured counts.** It adds: hostname-based resolution, a complete 95-table classification, cookie / session / OAuth / signed-URL / mail-link analysis, infrastructure, file storage, the option scoring including schema-per-tenant and per-deployment, and answers to Q1–Q20. Where this report differs, the difference is stated explicitly. |
| `# Architecture Investigation: Organisati…` | The prompt for this report |

## Evidence convention

| Label | Meaning |
|---|---|
| **FACT** | Read in the code or config, with `path:line` (relative to the repository root). |
| **INFERENCE** `[I]` | Derived from facts; not executed or observed at runtime. |
| **RECOMMENDATION** | The author's architectural proposal. |
| **DECISION REQUIRED** | Cannot be derived from the repositories; needs PO / ARB / Ops. |

Counts marked "≈" come from grep in the earlier assessment and were not re-measured here.

## Files

| # | File | Content |
|---|---|---|
| 01 | `01-executive-summary.md` | Answer, conditions, risks, first step |
| 02 | `02-current-nrna1-architecture.md` | Stack, domain map, current organisation boundary, sequence diagram |
| 03 | `03-election-reference-assessment.md` | What `election` really does; good and bad patterns |
| 04 | `04-hostname-tenant-resolution.md` | `<org>.publicdigit.com` design: resolution, URLs, cookies, OAuth, infra |
| 05 | `05-central-vs-tenant-data-classification.md` | All 95 tables classified |
| 06 | `06-database-and-transaction-analysis.md` | Default connection, FKs, raw SQL, transactions |
| 07 | `07-authentication-and-identity.md` | Central identity, login per host, sessions |
| 08 | `08-queues-events-and-background-processing.md` | Jobs, schedule, outbox, cache, files, logs |
| 09 | `09-security-and-isolation.md` | Threats, existing holes, fail-closed invariants |
| 10 | `10-migration-and-cutover-strategy.md` | Organisation-by-organisation migration |
| 11 | `11-target-architecture.md` | Diagrams A–G |
| 12 | `12-minimal-change-plan.md` | KEEP / CHANGE / ADD / REMOVE + phases |
| 13 | `13-testing-strategy.md` | Tests and fitness functions |
| 14 | `14-election-to-nrna1-architectural-delta.md` | Capability-by-capability delta |
| 15 | `15-open-questions-and-decisions.md` | Decisions requiring humans |
| 16 | `16-final-recommendation.md` | Option scoring, Q1–Q20, final conclusion, PoC |

## What remains undecided

All items in `15-open-questions-and-decisions.md`. The most important:
- cookie scope (per-hostname vs shared `.publicdigit.com`);
- the role of the apex `publicdigit.com`;
- the ownership of the tenancy capability;
- the fate of NULL-organisation rows and of tables without an organisation key;
- whether the database credential must be per organisation.
