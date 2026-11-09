# How to start a session on the multi-database work

This page explains how to resume the multi-database investigation in a new Claude Code session: what has been done, what to read, what is still open, and a prompt you can paste to start.

`README.md` in this folder is the index of the second investigation (files `01`–`16`). This file does not replace it.

| | |
|---|---|
| **Topic** | Moving `nrna1` from one shared PostgreSQL database to one database per organisation (later also one hostname per organisation, `<org>.publicdigit.com`) |
| **Status** | **BRAINSTORMING. Nothing is approved and nothing is implemented.** EP-01 applies: plan → explicit human approval → implement only the approved plan |
| **Last checked** | 2026-11-01 |

---

## 1. What exists so far

Two investigations, both read-only, both answering **"YES, WITH CONDITIONS"**.

| # | Date | Commissioned by | Output | Focus |
|---|---|---|---|---|
| 1 | 2026-10-06 | `firrst_prompt.md` | `MULTI_DATABASE_ARCHITECTURE_ASSESSMENT.md` (one long report) | Database per organisation: which connection is the default, central vs tenant data, queues, data migration, backlog MD-01…MD-18 |
| 2 | 2026-10-06, committed 2026-10-09 | `# Architecture Investigation: Organisati…` (prompt file) | `README.md` + `01`–`16` | Builds on #1 and adds hostname-based resolution, a full 95-table classification, cookies / OAuth / mail links, infrastructure, option scoring, and decisions D1… |

**Where #2 differs from #1, #2 says so explicitly. Treat #2 as the more recent view, and #1 as the source of its measured counts.**

### Recommendation shared by both

- **The organisation's database is the default connection.** Every request and job must name its organisation; if it doesn't, any query on organisation data fails instead of falling back to a shared database.
- **Platform data stays in one central database:** users, organisations, roles, access grants, and a registry of which organisation lives in which database.
- **Each organisation's database holds a read-only copy** of its members' basic user records, so existing links to users keep working.
- **Organisations move one at a time**, marked `shared` or `isolated` in the registry. There is no global on/off switch.
- **Laravel 11's built-in features are enough.** No new package, and `election`'s code should not be copied.

---

## 2. Reading order for a new session

| Step | Read | Why |
|---|---|---|
| 1 | This file | Orientation |
| 2 | `01-executive-summary.md` | The current answer and conditions |
| 3 | `15-open-questions-and-decisions.md` | What is waiting for a human |
| 4 | `16-final-recommendation.md` | Option scoring and the proposed proof of concept |
| 5 | `12-minimal-change-plan.md` | What changes, what stays (KEEP / CHANGE / ADD / REMOVE) |
| 6 | Only the topic files you need (`04`–`11`, `13`, `14`) | Detail |
| 7 | `MULTI_DATABASE_ARCHITECTURE_ASSESSMENT.md` | Only when you need the original counts, the MD-xx backlog, or §17 (first proof of concept) |

Project-level steps from `.claude/CLAUDE.md` still apply at session start: read `.claude/MEMORY.md`, the routed CONTEXT file, and today's session log. Run `php .claude/scripts/session-bootstrap.php` before acting.

---

## 3. State of the code (verified 2026-11-01)

| Item | State |
|---|---|
| `central` / `tenant` connections in `config/database.php` | **Not present.** No multi-database code has been written |
| Admin election routes without a platform-admin check (`routes/election/electionRoutes.php:678-688`) | **Still open.** Tracked as item 1 of `docs/publicdigit/backlog/PBDIGIT-266-live-routes-without-a-found-authorisation-guard.md` (investigation, priority high, needs a confirming feature test first) |
| `X-Tenant-Id` header trusted without a membership check (`app/Http/Middleware/TenantContext.php:49-56`) | **Still open. No backlog item yet** |
| Fallback organisation looked up by slug `platform` while the seed uses `publicdigit` (`app/Traits/BelongsToTenant.php:54,77`) | **Still open. No backlog item yet** |

**Re-check these before relying on them.** Line numbers drift.

---

## 4. Decisions waiting for a human

The full list is in `15-open-questions-and-decisions.md` and in §20 of the assessment. The ones that block progress:

1. **Is there production data in the shared database, and for how many organisations?** This sets the cost of the data move.
2. **Who owns the tenancy capability?** An infrastructure capability (`app/Infrastructure/Tenancy`) or a new Platform bounded context. This is an ARB decision.
3. **Cookie scope:** host-only cookies per organisation hostname, or a shared `.publicdigit.com` cookie.
4. **Role of `publicdigit.com` itself:** platform hub or marketing only.
5. **Rows with no organisation, and tables with no organisation column:** where do they belong?
6. **Database credentials:** per organisation, or one application role.

---

## 5. Rules for working in this folder

- **Existing files here are read-only.** Add new files; never edit, rename or overwrite the investigation documents. If a document needs a new version, create a `_V2` file.
- **No implementation from here.** Code changes start only after a plan is approved under EP-01 and turned into `PBDIGIT-nn` stories (one story, one commit).
- **Discovered work becomes a backlog item** (`docs/publicdigit/backlog/PBDIGIT-nn-*.md`), not a sentence in a reply.
- **Never run destructive database commands** (`migrate:fresh`, `migrate:refresh`) against the development database.
- **Evidence labels:** keep FACT, INFERENCE, RECOMMENDATION and DECISION REQUIRED separate, with `path:line` for every fact.
- **Repository paths:** `nrna1` is reachable as `/home/nab-raj.roshyara@dg-nexolution.de/roshyara/personal/nrna1` and as `/home/d0f38614-c3a6-41d3-9952-7f59ad699b2d/roshyara/personal/nrna1` (same checkout). The reference repository is `/home/nab-raj.roshyara@dg-nexolution.de/roshyara/personal/election`; read it only, never copy from it.

---

## 6. Prompts to start a session

Pick the one that matches what you want to do next. Paste it as the first message.

### A. Continue the analysis (no code)

```text
Read docs/publicdigit/brainstorming/multi-database-system/SESSION_START.md and follow its
reading order. Work as principal software architect. Treat every existing file in that folder
as read-only; write any new output as a new file there.

First re-verify section 3 ("State of the code") against the current branch and tell me what
changed. Then help me with: <your question, e.g. "decide D1 cookie scope" or
"classify the NULL-organisation rows">.

Do not modify production code, config, migrations or any database.
```

### B. Turn the security findings into backlog items

```text
Read docs/publicdigit/brainstorming/multi-database-system/SESSION_START.md, section 3.
For the two findings that have no backlog item yet (X-Tenant-Id header trust; the
'platform' vs 'publicdigit' fallback slug), re-verify them in the code and create one
PBDIGIT backlog item each, following the format of the existing items and updating
docs/publicdigit/backlog/README.md. Link PBDIGIT-266 for the admin-route finding.
No code changes.
```

### C. Plan the first proof of concept (EP-01, plan only)

```text
Read docs/publicdigit/brainstorming/multi-database-system/SESSION_START.md, then
16-final-recommendation.md and §17 of MULTI_DATABASE_ARCHITECTURE_ASSESSMENT.md.
Run the Engineering Readiness Review (EP-03) and produce an EP-01 plan for the proof of
concept "fail-closed tenant connection in shared mode", stored under docs/plans/ with the
ES-004.2 naming rule. Stop and wait for my approval before writing any code.
```

---

## 7. Traceability

- Investigation #1: `firrst_prompt.md` → `MULTI_DATABASE_ARCHITECTURE_ASSESSMENT.md`; session log `.claude/sessions/2026-10-06.md` ("Multi-database assessment").
- Investigation #2: `# Architecture Investigation: Organisati…` → `README.md`, `01`–`16`; commit `f6fc3c5ab`.
- Security follow-up: `PBDIGIT-266` (admin routes, item 1).
