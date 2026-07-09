# PublicDigit — Architecture Handover, Release 2.0

**Status:** authoritative starting document for any new session / AI collaborator. **2026-07-09.**
Supersedes the Release 1.x handover (which predated PB-004 completion and is now temporally inaccurate).

> **Framing:** this is a **constitutional governance platform** (trustworthy, anonymous, auditable collective decisions), not merely "online voting." Read every model through that lens.

---

## 1. What it is (one paragraph)
Laravel 11 modular monolith · strict **DDD** (strategic + tactical) · **Hexagonal** · **event-driven** (transactional **outbox/inbox**, at-least-once + idempotent) · **multi-tenant** (organisation-scoped) · **ARB-governed** with an evidence-first engineering process. Bounded contexts: **Contestation · Adjudication · Election · Voting · Evidence · Membership · Geography · Governance** (+ a Shared Messaging Platform).

## 2. Current state (the part the old handover got wrong)
| Item | State |
|---|---|
| Architecture | Release 1.x **stable · FROZEN · implementation-driven** — no redesign expected |
| Messaging Platform | **Qualified AND proven by reuse** across bounded contexts (Adjudication produces; Election + Contestation react) with **zero platform modification** |
| **PB-004 (Election Reaction)** | **CLOSED** — 4A.1 (clock/appliedAt) · 4A.2 (existence-ACL design) · 4A.3 (persistence) · 4B (messaging) · 4C (architecture qualification). Qualified + formally accepted. |
| **PB-005 (Contestation Reaction)** | **IN PROGRESS** — 5A (Domain+Application reaction) **accepted**; **5B (persistence) landing**; 5C (messaging) + 5D (qualification) remain |
| Strategic DDD | Stable |
| Focus | Shifted from **architectural invention** → **disciplined implementation + repeatable reuse** |

## 3. The engineering workflow (every non-trivial slice)
`Discovery (DDD Ownership) → IDD → ARB review → RED → GREEN → PHPStan → Regression → Qualification → Completion Review → STOP.`
One slice at a time; stop at the boundary; RED before GREEN, always, with evidence.

## 4. Governing rules (defined once in `docs/implementation/Implementation_Process_v1.1_Draft.md`; referenced, never restated)
ER-01 Architecture-before-implementation · ER-02 **Evidence before governance** · ER-03 **Reuse before create** · ER-04 Repository awareness · ER-05 Architecture convergence · ER-06 Ubiquitous-before-published language · ER-07 **Test behaviour, not transport** · ER-08 **Reviews record, implementations repair**. EP-01 Plan First · EP-02 Completion Review · EP-03 Engineering Readiness Review.

## 5. Non-negotiable invariants
- **ADR-T1** — one transaction = one aggregate root + its outbox row(s). The correction loop is **5 causally-linked transactions, never a saga**.
- **ADR-T8** — forward-only correction (`ContainedOnly`); no compensation (anonymity forbids un-casting).
- **ADR-T11** — anonymity: **no voter↔vote linkage** in any aggregate, event, payload, log, or dead-letter row.
- **ADR-T16** — cross-context identity crosses as **strings**; each context reconstructs its **own local VO**; the **domain is tenant-free** (tenant lives at the infrastructure boundary).

## 6. The correction loop (the system's spine)
`Contestation` raises a **Challenge** → `Adjudication` issues a **Determination** (`DeterminationIssued`) → `Election` applies a correction (`ElectionCorrectionApplied`) → `Contestation` **adjudicates** then **resolves** the Challenge (`ChallengeAdjudicated`, `ChallengeResolved`). Five transactions; eventual consistency via events; **out-of-order arrival → park + re-drive** (never fail).

## 7. Validated engineering evidence (NOT "future work")
- **Aggregate Reconstruction from multiple persistence sources** — **VALIDATED** (PB-004 Election: existence via a legacy **ACL** + greenfield reaction-state ledger; `Election ≠ legacy row`).
- **Inbox-inherited atomicity** — a reacting context needs **no** own TransactionManager; the inbox's `DB::transaction` wraps apply→enqueue→save (rollback proven).
- **Business-condition → inbox-marker translation boundary** — the Domain/Application raise **business** conditions; a single translator maps them to messaging outcomes (PB-005 5A). The Domain never names a messaging outcome.

## 8. Candidate patterns — **validated, NOT promoted to standards**
**Strangler reconstitution:** `Legacy Source → ACL → Aggregate Reconstruction → Domain Behaviour → Integration Event → Shared Messaging`. Validated across **Adjudication** (produce) + **Election** (react).
- **Deliberately NOT reused in Contestation:** Contestation *owns* the Challenge, so it uses a **single-source repository — no ACL, no multi-source reconstruction**. This ownership-driven non-reuse is the strongest sign the architecture is applied by reasoning, not by cargo-cult.
- PB-005 provides **additional independent evidence**; the ARB decides promotion **only after** review (ER-02). Precedent alone never makes a standard.

## 9. Review discipline (trial to a future retrospective)
Every Completion Review: **A Product · B Platform · C Learning**, plus an **Architectural Confidence Delta** table, closing with two questions — *Did this improve PublicDigit delivery?* and *Did this reduce or increase architectural entropy?* Every statement classified **Observed · Measured · Derived · Interpreted · Recommended**. Principle: *the implementation agent records evidence; the architect decides significance.*

## 10. AI Engineering Platform — frozen
**Baseline Execution Mode v1.0:** no changes to the `.claude` structure, hooks, automation, or governance artifacts unless a feature demonstrates the platform is insufficient. The platform **serves the product** (ADR-AIP-02 Product Primacy). Reviews record findings; changes require evidence.

## 11. Known gotchas (save a new collaborator hours)
- **Test harness:** PostgreSQL `nrna_test`; `Tests\TestCase::beginDatabaseTransaction` is a **NO-OP for pgsql** → **no per-test rollback** (isolation is `migrate:fresh` once per process). Feature tests must **self-isolate**: unique organisation **and** unique aggregate ids per test; **tenant-scoped assertions**; never global `assertDatabaseCount`.
- **Legacy vs greenfield:** `elections` table + `App\Models\Election` are legacy-owned; greenfield reactions read existence via an ACL only, never import legacy into the domain.
- **PHPUnit "risky (removed error handlers)"** appears only in mixed cross-suite runs; **clean in isolation** — a pre-existing pgsql+PHPUnit artifact, not a defect.

## 12. Open architectural discussions (revisit with the current architecture)
- **ContestableDecisionIdentifier / Contestation published language** — do **not** carry any pre-PB-004 recommendation forward unchanged; the event modelling has evolved (see ADR-UL-01 / ADR-PL-01). Requires fresh ARB discussion.
- **`ChallengeResolved.resolution`** (PB-005 F-2) — ARB ruling: keep the **domain event minimal**; enrich only the **Integration Event** (Application supplies `resolution` explicitly at publish time). Domain-Event ≠ Integration-Event.
- **Frozen Canonical Event Catalog inconsistencies** — `DeterminationIssued` consumer row omits Contestation; `ChallengeAdjudicated` payload undefined. **Record** and correct via versioned re-issue + ADR **after** the PB-005 IDD, never during.

## 13. Read these at session start (repo is the single source of truth)
`.claude/MEMORY.md` · `.claude/CONTEXT.md` (current working state + THE next action) · the active IDD `docs/implementation/backlog/PB-005_Contestation_Reaction_Implementation_Design.md` · today's `.claude/sessions/YYYY-MM-DD.md` · rules `docs/implementation/Implementation_Process_v1.1_Draft.md` · `docs/adr/` (ADR-T/S/UL/PL/PC/AIP logs) · `docs/implementation/Canonical_Event_Catalog_v1.0.md` · Round50 design docs · `docs/implementation/PB-004_Retrospective.md`.

## 14. Bootstrap prompt (paste into a fresh session / new collaborator)
> You are joining the PublicDigit constitutional-governance platform as an engineer under ARB governance. **The architecture has now been implemented across multiple bounded contexts; prioritize reuse over redesign.** Follow the workflow: Discovery → IDD → ARB review → RED → GREEN → Regression → Qualification → Completion Review → STOP. Work **one slice at a time and stop at the boundary**. Reuse a pattern only where **ownership and business semantics justify it** — state *why it fits*, never "it worked before." Introduce a new abstraction **only** if implementation evidence shows the existing architecture is insufficient. Honour the invariants (ADR-T1/T8/T11/T16). The repository is the single source of truth; update `CONTEXT.md`, the session log, and the active IDD as you work. Do not modify the frozen AI Engineering Platform (`.claude/…`) without evidence of insufficiency.
