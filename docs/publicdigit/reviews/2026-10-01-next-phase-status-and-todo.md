# PublicDigit: next-phase status review — what is done, what remains

| | |
|---|---|
| **Date** | 2026-10-01 |
| **Kind** | ⚠ authority: generated. A **verification of the next-phase narrative** against the repository, a status report and a TODO map. **It authorises nothing and decides nothing.** |
| **Input reviewed** | the user-supplied next-phase narrative (goal, four layers, five buckets, the Audit System question, the 22-item TODO list) |
| **Prepared by** | `claude-code-session:42e0287e-e0e2-4638-8165-cd5d5862c578` (self-declared) |
| **Frozen baseline** | *"Delivery baseline frozen at PBDIGIT-208 (PO, 2026-10-01). New IDs only for a delivery obligation shown not to be covered by PBDIGIT-00…208."* This review found **one** such obligation, now `PBDIGIT-209` (§5). Everything else maps to an existing story or to a decision. |
| **Placement** | `php scripts/doc-placement.php --scope=product-specific --domain=publicdigit` → `docs/publicdigit` (exit 0); `reviews/` |

> **⚠️ Superseded in part (2026-10-01, `2026-10-01-backlog-completion-review.md`):** on the PO's instruction, the **merge/release story drafted here as `PBDIGIT-209` was WITHDRAWN before any commit**. Merge/release is a later delivery concern (class E), and §3.1 item 1, §5 and §7 below stand only as facts. `PBDIGIT-209` now denotes *"A membership fee payment cannot be recorded"*.

## 1. Verdict on the narrative

**The narrative's direction is right, and three of its facts are wrong.** The right direction is: close authorised work, merge, then investigate the next capability with evidence before authority before architecture. The three wrong facts:

| Narrative claim | Repository reality | Verdict |
|---|---|---|
| "WP-4C-1 … what remains is the acceptance decision" | **Accepted on 2026-08-04 (R-93), scope D1 + D2.** D3 and D4 were not offered and are HELD (R-97) → `PBDIGIT-175` | ❌ stale |
| "PB-006 scope-boundary disposition" is a held item | **Disposed on 2026-08-04 (R-94): ANNOTATE adopted** | ❌ stale |
| "The `DeterminationIssued` production path has been verified end to end" | The path is verified **from the routed challenge onwards**, with a test-seeded head and a stubbed authority (`CorrectionLoopIntegrationTest`). **No production caller can deliver an authority decision** (WP-4D unbuilt → `PBDIGIT-177`). The issuance write and its outbox row are in **two transactions** on one path (R-91 → `PBDIGIT-178`). **Determinations never become final** (`PBDIGIT-197`). | ⚠️ overstated |
| "Confirmed bounded contexts: Evidence, Voting, Appointment/Mandate, Contestation, Adjudication" | That is the **Boundary Decision Register v1.1** set. The **EPIC-002 Canonical Context Map** (ARB, 2026-07-25) ratified a **different five**, and no record reconciles them (Track 2, SK-215) | ⚠️ one of two unreconciled sets |
| "We do not have sufficient evidence that the Audit System isn't built" | Correct. The first-pass inventory (§4) shows runtime audit **exists, fragmented across four stores, with no unified review capability and no integrity protection** | ✅ now evidenced |
| `AdjudicationExpired` and `AdjudicationFailureDeclared` have no consumer | Confirmed: `0` Contestation references → `PBDIGIT-199`; failure-declared publication is HELD → `PBDIGIT-175`/`176` | ✅ |
| "Merge the validated feature branch into main" | **There is no separate feature branch.** All work sits on `knowelegeos-modelling`: **1,599 commits ahead of `main`**, mixing 244 PublicDigit code files with **16,242 KnowledgeOS documentation files**, and the merge gate is red (§5) | ⚠️ not ready as stated → `PBDIGIT-209` |

## 2. What is already done

| Layer | Done (with evidence) |
|---|---|
| **Governance and method** | Governance roles separated (Programme, Architecture, Execution, Delivery). The engineering-vs-authority boundary is recorded (R-34, R-90, R-100: "engineering may not answer Q1–Q4"). The new-commission rule (R-100). Engineering Verification Framework v1.x closed (R-99). |
| **Strategic DDD** | EPIC-002 discovery completed and the context map ratified (2026-07-25). Relationship patterns selected. The Tactical Entry Assessment completed (EPIC-003). **Track 2 (today): 215 strategic outcomes, each with one disposition**, in `2026-10-01-strategic-knowledge-traceability-audit.md`. |
| **Correction loop: implemented and accepted, on `main`** | EPIC-001 (PB-001…007). WP-1 (EvidenceSet, v3). WP-2 (APM core). WP-3A (ChallengeRouted published language). WP-4A (inbox wiring). WP-4B (crash-safe conclude → issue seam). WP-4C-1 D1/D2 (`AdjudicationFailureDeclared`). WP-5 (raise path). WP-6 (temporal config, horizon, expiry announcement). WP-7 (EPW-aware retention). These have code; Track 2 marks 43 outcomes IMPLEMENTED with paths. |
| **Election product: on the branch only** | `PBDIGIT-38` (votes persist), 62, 65, 69 (eligibility and cache repairs), EM-IMPL-001 and EM-IMPL-002 GREEN-1…4, H-2, EM-DOM-001 Act B, and others |
| **Inventory and traceability** | **The delivery inventory is closed and frozen at `PBDIGIT-208`.** The knowledge-to-delivery traceability converged (Track 2, K1–K10). |

## 3. What remains: one map

Every remaining item is already owned by a story or a decision. **No item is left only as prose.**

### 3.1 Immediate (delivery)

| # | Item | Owner |
|---|---|---|
| 1 | **Merge readiness and merge to `main`** (with the separation decision) | **`PBDIGIT-209` (NEW)** |
| 2 | Truthful programme status (the health table claims a green gate) | `PBDIGIT-159` |
| 3 | Gate vs RED-first decision; the legal-timestamp defect; PHPStan | `PBDIGIT-160` (ARB) · `158` · `161` |
| 4 | Live, runtime-confirmed product defects | `PBDIGIT-201` (valid voter refused at `start()`) · `202` (Full Membership import throws) · `203` (import success with 0 memberships) · `195` (false-success committee endpoint) |

### 3.2 Governance closure (decisions, in the order proposed)

| # | Decision | Owner | Story / record |
|---|---|---|---|
| 5 | **D-1:** do engineering and documentation items get PBDIGIT stories? | PO | integrity review §3.1 |
| 6 | **Q1–Q4** answered → engineering records and verifies → ARB decides on a WP-4C-2 commission | Contestation Domain Owner, then ARB | `PBDIGIT-176` |
| 7 | WP-4C-1 **D3/D4** release (Board E1/E2) | Board | `PBDIGIT-175` |
| 8 | R-91 transaction-boundary repair → then WP-4D intake | Decision Authority | `PBDIGIT-178` → `177` |
| 9 | `enforceHorizon()` isolation (plan authorised) and the production horizon trigger | Execution Governance / ARB | `PBDIGIT-180` · `198` |
| 10 | Risky-signal classification (plan authorised, R-95) | Execution Governance | `PBDIGIT-162` |
| 11 | COL-5a home | governance | Track 2 SK-135 (no story, by rule) |
| 12 | WP-8, deferred (R-79) | — | `PBDIGIT-182` |
| 13 | **Evidence-side bounded contexts:** build, supersede or defer? | ARB / Decision Authority | Track 2 candidate SK-078 |
| 14 | **Q-2 evidence-preservation anchor** (interim resolver still bound) | Q-2 owner / ARB | Track 2 candidate SK-177 |
| 15 | **Which bounded-context set governs** (BDR v1.1 vs the EPIC-002 map) | ARB | Track 2 SK-215 → affects `PBDIGIT-189` |
| 16 | Additive factual correction to `PBDIGIT-177` (R-73 already implemented) | PO | Track 2 §7 |
| 17 | The other decision-gate stories | as recorded | `PBDIGIT-179`, `181`, `183`, `184`, `189`, `197` |

### 3.3 Next capability investigation: runtime audit

| # | Item | Owner |
|---|---|---|
| 18 | First-pass runtime audit inventory | **Done in this review (§4)** |
| 19 | **The business purpose of a unified runtime audit capability:** auditable facts, invariants (immutability, provenance, ordering, retention, access, custody, integrity), ownership | **PO, then ARB.** Not a story yet (§4.3) |
| 20 | Only if a delivery obligation results: strategic placement → ARB authorisation → Tactical DDD → stories | later; it may then receive `PBDIGIT-210`+ |

## 4. Runtime audit capability: first-pass inventory (facts only)

### 4.1 What exists

| Store | Producer (verified) | Retrieval / reader | Retention | Integrity | Class |
|---|---|---|---|---|---|
| **Per-voter / per-election audit files** (`storage/logs/audit/…`) | `App\Services\ElectionAuditService` (`log`, `logVoterAction`), called from `CodeController`, `VoteController`, `ElectionVotingController`, `VoterVerificationController`, `ElectionSettingsController` | platform-admin download: `routes/platform.php:27` → `AdminElectionController::downloadAuditFile` | `audit:cleanup`, EPW-aware (WP-7; `AuditCleanup.php`) | plain files. Only the file-name component is hashed (`ElectionAuditService.php:116`) | **Existing, partial** |
| **`election_audit_logs` table** | dual-write at `app/Models/Election.php:2054` (lifecycle actions) and `ElectionManagementController.php:1034` (`ElectionAuditLog::record`) | **none found: write-only** (no query, page or export reads it) | none found | none | **Existing, write-only** |
| `elections.state_audit_log` (JSON column) | same site; **keeps only the last 200 entries** (`Election.php:2048`) | the election model (state history) | truncated by design (the table holds the full record) | none | **Legacy cache** |
| **`election_security_events` table** | `App\Application\Election\Security\SecurityEventRecorder` (outside the vote transaction since the `PBDIGIT-38` fix, `2ef38297a`) | **runtime only:** `IpVelocityOverlay.php:38` queries recent events. *(The admin "user audit" page, `VotingSecurityController::auditUser`, computes a live security check and does **not** read this table.)* | none found | none | **Existing, partial** |
| `newsletter_audit_logs` table | newsletter services (6 files) | newsletter pages | none found | none | **Existing (communication)** |
| Outbox events (correction loop) | the Adjudication/Contestation/Election outbox adapters | inbox consumers; replay | not deleted | append-only by design (ADR-T5, blueprint §12); **no hash chain** | **Existing: the legal record of the loop** |
| `AppliedDeterminationLedger` | Election (correction idempotency) | Election | — | — | **Unrelated to audit review** (an idempotency ledger) |
| `EvaluationAuditTrail` (simplified security) | security pipeline | — | — | — | **Partial / legacy** (A2 track replaces it: `PBDIGIT-112`) |

### 4.2 What does not exist

- **No unified review capability.** No single place where an officer, auditor or dispute body reads the audit history of an election. The evidence is spread over four stores. **`election_audit_logs` is read by nothing at all**, and `election_security_events` is read only by a runtime security overlay. The only human retrieval path is the platform-admin download of audit files.
- **No integrity protection** of any audit store: no hash chain, signature or tamper evidence. The ratified *Custodial* and *Self-Verifying Integrity* contexts are unbuilt (Track 2 candidate SK-078).
- **No documented retention policy** for the three tables. Only the files fall under the EPW.

### 4.3 What this means, and does not mean

- **It does not mean "an Audit System must be built."** Per the narrative's own discipline, a gap is a delivery obligation only once the PO states the business purpose (step A). So **no story is created here**: items 19–20 stay decisions.
- **Existing stories already own parts of the audit concern:**
  - `PBDIGIT-23` (verify the audit), `22` (export), `42` (audit schema semantics);
  - `143` (suspend/resume audit completeness), `203` (assignment audit on import);
  - `112` (CAST_VOTE decision records), `114` (replay);
  - plus the Voter Activity Trail governance items B-1…B-6 (WP-6 closure §3, "separate strategic matter").
- **The decisive next question for the PO:** *what must an auditor be able to prove about an election, to whom, and from which store?* Its answer decides whether item 20 opens.

## 5. Merge readiness: facts behind `PBDIGIT-209`

| Fact | Evidence |
|---|---|
| Branch `knowelegeos-modelling`: **1,599 ahead / 0 behind `main`**; merge base `e698cfdbb` (2026-08-05) | `git rev-list --left-right --count main...HEAD` |
| `main` already has WP-4B, WP-4C-1, WP-6 and WP-7. The branch adds EM-IMPL-002, PBDIGIT-65 and others | `git log` on each |
| PublicDigit delta: 244 code/test files (+22,597 / −363), 80 commits | `git diff --stat main...HEAD -- app tests routes database config` |
| **KnowledgeOS delta: 16,242 files (~7.0 M lines)** | `git diff --stat main...HEAD -- docs/knowledgeos engineering docs/knowledge` |
| Merge gate red on the branch | `PBDIGIT-159`…`161`, `158` |
| 97 commits unpushed | `git rev-list --count @{u}..HEAD` |

**Two decisions come before a merge:** what reaches `main` (the whole branch, or PublicDigit only; the repository-separation ADR is PROPOSED), and whether a red gate blocks it. **Neither is engineering's to make.**

## 6. Recommended order (proposal, not authorisation)

1. PO: **D-1**, and **`PBDIGIT-209`'s two decisions**.
2. `PBDIGIT-159` (status truth), so the merge-readiness review starts from true numbers.
3. **`PBDIGIT-209`:** merge-readiness review → merge by the decided method. The push is authorised separately.
4. ARB: the bounded-context set (SK-215) and the Evidence-side contexts (SK-078). Both bear on every future tactical commission.
5. Domain Owner: Q1–Q4 (`PBDIGIT-176`).
6. First implementation stories, one at a time: `PBDIGIT-201`, `PBDIGIT-202` (single-site, runtime-confirmed). Acceptance must verify the business state, not the HTTP status.
7. PO: the audit-purpose question (§4.3). Only then can a runtime-audit capability be commissioned.

## 7. New backlog story

| ID | Story | Why it is new under the frozen baseline |
|---|---|---|
| **`PBDIGIT-209`** | The validated PublicDigit work has not reached `main` | Merging or releasing to `main` is owned by no story in 00…208 (git grep, §5). It is a delivery obligation: customers run `main`. |

**Considered and NOT given an ID:**
- the WP-4C-1 acceptance (done, R-93);
- PB-006 (done, R-94);
- the runtime-audit capability (a decision first, §4.3);
- the Evidence-side contexts, Q-2 and the bounded-context reconciliation (Track 2 candidates and H items, which are decisions);
- every other narrative item (already owned, §3).

---

**Traceability:**
- **Rulings:** R-93, R-94, R-97, R-99, R-100 (`engineering/architecture/adr/ADR-AIP-LOG-Platform-Rulings.md`).
- **Audits:** `2026-10-01-pbdigit-story-integrity-final.md` (frozen baseline); `2026-10-01-strategic-knowledge-traceability-audit.md` (SK-078, SK-135, SK-177, SK-215).
- **Code paths:** cited in §4.
- **Git measurements:** cited in §5.
