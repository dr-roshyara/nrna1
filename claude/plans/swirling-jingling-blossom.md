# AI Architecture Promotion Review — Evidence-Based (PB-004 · PB-005 · PB-006)

**Type:** Architecture Promotion Review (NOT a redesign) · **Date:** 2026-07-09
**Reviewer role:** ARB instruction "AI Architecture Evolution Review (v2.1)"
**Approval semantics:** Plan approval = ARB adoption of the Promotion Matrix + Delta below (Observation Class D — explicit human adoption). Until approved, everything here is a *proposal*; no `.claude` file is modified.

---

## Context

The ARB asked for an evidence-based promotion review of the AI Architecture using implementation evidence from PB-004 (Election Reaction, CLOSED), PB-005 (Contestation Reaction, CLOSED), and PB-006 (Discovery + 6A RED, in flight). The governing chain is **Practice → Evidence → Repeated Success → Retrospective → Promotion → Standard**; nothing is promoted from a single demonstration.

**Scope note:** no document is literally named "AI Architecture v2.0". The AI Architecture in this repo = the AI-facing guidance corpus:
- `.claude/platform/OPERATING_INSTRUCTIONS.md` (AST-013 — operating doctrine)
- `.claude/CLAUDE.md` (AST-012 — standing rules, pointer discipline)
- `.claude/platform/registry.yaml` (AST-009 — registry-first workflow)
- governed by `docs/adr/ADR-AIP-01` / `ADR-AIP-02` + `docs/adr/ADR-AIP-LOG-Platform-Rulings.md` (R-30..R-35)

**Freeze status:** R-27 (governance freeze) gates new platform principles on the PB-004 usage retrospective. That retrospective exists (`docs/implementation/PB-004_Retrospective.md`), and PB-005/PB-006 added two more independent evidence points — so the AIP-13 **amendment** path (amend existing docs, no new documents) is now legitimately open. This review uses only that path.

---

## Deliverable 1 — Promotion Matrix

Categories: **A** = promote to AI Architecture · **B** = promote to Architecture Principles docs · **C** = stays platform documentation (ADR-MP / dev guides) · **D** = remains Candidate Pattern (retrospective backlog) · **already** = permanent guidance today; re-adding = duplication.

| # | Candidate | Category | Evidence (independent slices) | Recommendation |
|---|---|---|---|---|
| 1 | Evidence Before Governance | **already** | ER-02 (frozen) · AIP-01 · Governance Economy (OPERATING_INSTRUCTIONS) · demonstrated PB-004 retro, PB-005 promotion deferral, PB-006 R-29 escape | No delta — already permanent |
| 2 | **Reuse Before Create** | **A** | 3: PB-004 ("no new messaging abstraction was required or created"; `ClockInterface` reuse) · PB-005 (carrier ≠ pattern, Q1; idempotency-ledger consciously NOT copied) · PB-006 (new capability built only after a feature demonstrated platform insufficiency, R-29) | **PROMOTE** — one line in OPERATING_INSTRUCTIONS |
| 3 | **Ownership Drives Reuse** | **A** | 3: PB-004 (ownership held stable through implementation, retro §2) · PB-005 (Strangler/ACL NOT transferred because Contestation *owns* the Challenge — Discovery §5) · PB-006 (delivery owned by Messaging, "Inbox is one consumer") | **PROMOTE** — one line; the strongest new principle ("reuse is justified per-pattern by ownership, never by precedent") |
| 4 | Test Behaviour, Not Transport | **already** (ER-07, v1.1 draft) | 3: PB-004 retro §4 · PB-005 5A RED restructure (business vs translation) · PB-006 behavioural RED matrix | No `.claude` delta — the pending action is **ratifying `Implementation_Process_v1.1_Draft.md`**, its single designated home |
| 5 | Registration ≠ Delivery | **C** (+ generalization → D) | 1: PB-006 / ADR-MP-06 (recorded there as "permanent design principle") | Stays in ADR-MP-06 (Messaging-scoped). The generalized form ("configuration is not capability") has ONE demonstration → Candidate Pattern |
| 6 | Domain Event ≠ Integration Event | **B** | 2: PB-004 (established; schema-v2 enrichment) · PB-005 F-2 (`resolution` integration-only, binding ARB ruling) | **PROMOTE to Architecture Principles** (e.g. alongside PGP-01..05 in `docs/architecture/principles/`), not into `.claude`. ARB may prefer waiting for a 3rd slice — 2 slices is the floor |
| 7 | Stop at architectural uncertainty | **already** | 3: PB-004 4A.2 (IDD-first stop) · PB-005 (7 open questions → STOP for ARB) · PB-006 (F-PB006-1 STOP before IDD) | No delta — covered by ER-01 + EP-01 STOP-and-re-plan + the ARR gate. Evidence confirms the existing rule works; do not duplicate it |
| 8 | Freeze architecture before implementation | **already** | ER-01 · AIP-09 · IDD-FROZEN practice in all three tickets | No delta |
| 9 | **Surface gaps / Deferred ≠ skipped** | **A** | 3: PB-004 ("Deferred — finding, not silent" fitness extension) · PB-005 (F-5 catalog inconsistencies recorded, Deptrac "deferred, NOT skipped") · PB-006 (F-PB006-1 surfaced instead of hand-stitching around it) | **PROMOTE** — one line: gaps and deferrals are recorded with their resumption trigger, never silently absorbed |
| 10 | **Epistemic labeling** (Observed · Measured · Derived · Interpreted · Recommended) | **A** | 2–3: Handover §9 (ARB-adopted Completion-Review discipline) · PB-006 Discovery ("Classification (Interpreted): …") · PB-004/005 EP-02 reviews | **PROMOTE (relocation, not new governance)** — already ARB-adopted in Handover §9; add one pointer line so it applies to all AI findings, not only Completion Reviews |
| 11 | Discovery → IDD → RED → GREEN → Qualification(×3) → Completion Review chain | **B** (process) | 3: executed verbatim in PB-004, PB-005, PB-006 | Fold the named chain into `Implementation_Process_v1.1_Draft.md` at ratification (its single home). `.claude` keeps the existing pointer — no restatement |
| 12 | Trustworthiness Qualification (3rd category) | **C**/already governance | 2: PB-005 (retroactive PASS) · PB-006 6C (prescribed) — adopted in Handover §9a | Stays in Handover §9a + process doc. No `.claude` delta |
| 13 | Never silently introduce a new pattern | **already** | DDD Qualification check "no new architectural pattern introduced" (Handover §9) · PB-005 Q1 | No delta — already an executable qualification check |
| 14 | Strangler reconstitution (Legacy → ACL → Aggregate Reconstruction → …) | **D** | 1 context only (Election). PB-005 deliberately did NOT reuse it — evidence it does not generalize by default | Remains Candidate; documented in PB-004 retro §9 + Handover §8 |
| 15 | Inbox-inherited atomicity | **C** | 2: PB-004 (rollback proven) · PB-005 (reused, justified) | Messaging documentation (dev guide; optional future ADR-MP note). Not AI guidance |
| 16 | Deterministic ordered routing · audit continuity · ConsumerResolver · consumer isolation · D-1/D-2 · parking · Dismissed short-circuit | **C** | PB-006 / PB-005 | Already correctly homed in ADR-MP-06 + IDDs + dev guides. No move |
| 17 | `ChallengeResolvedIntegration` carrier | **D** | 1 — explicitly ruled "temporary carrier, not a pattern" (PB-005 Q1) | Remains candidate; replaced only on repeated evidence (ER-02) |
| 18 | PGP-03 "owner-hosts-the-guard" hoist to global rule | **D** | 2-ish: ADR-MP-03 note + AD-M1 fitness relocation | Keep as recorded candidate in ADR-MP-03; revisit when a non-Messaging context hosts a guard |
| 19 | Engineering Standards document (R-28/R-32, CMP-009) | **D** | PB-004 retrospective did NOT trigger R-28 (created no standards) | Keep deferred — building it now would violate Documentation Economy |

---

## Deliverable 2 — AI Architecture Delta (additions / removals / relocations ONLY)

### Additions — ONE short section appended to `.claude/platform/OPERATING_INSTRUCTIONS.md` (AST-013)

```markdown
## Promoted Engineering Behaviours (ARB Promotion Review, 2026-07-09 — R-36)

Promoted from three independent implementation slices (PB-004 · PB-005 · PB-006); each changes how work is reasoned about, not how any subsystem is built:

1. **Ownership drives reuse.** Reuse is justified per-pattern by ownership and business semantics — never by precedent. "It worked in the last slice" is not a reason.
2. **Reuse before create.** Introduce a new abstraction or capability only when implementation evidence shows the existing architecture insufficient.
3. **Deferred ≠ skipped.** Gaps and deferrals are surfaced and recorded with their resumption trigger — never silently absorbed into the slice.
4. **Label epistemic status.** In findings, discoveries, and reviews classify claims: Observed · Measured · Derived · Interpreted · Recommended (per Handover §9).
```

That is the entire textual delta: **4 bullets, 0 new documents, 0 new frameworks.**

### Removals

- `registry.yaml` **AST-008** (`~/.claude/hooks/timestamp-plan.sh`) — already `deprecated`, machine-local, violates AIP-03, superseded. Remove the entry (or mark `removed`) → satisfies the R-27 retrospective **deletion goal**. Platform ends smaller.

### Relocations

- None inside `.claude`. (Items 6 and 11 above are promotions into *other* corpora — Architecture Principles and the Implementation Process draft — executed only if ARB adopts them, as separate follow-ups outside this delta.)

### Bookkeeping required by existing rules (not new governance)

- `registry.yaml` AST-013 entry: version bump + five-question trace note referencing R-36 (registry-first workflow is BINDING for every `.claude` artifact change).
- `docs/adr/ADR-AIP-LOG-Platform-Rulings.md`: append **R-36** recording this adoption (one table row; append-only log — its designed purpose).

---

## Deliverable 3 — Relocation Plan for rejected/non-A items

| Item | Correct home (already there unless noted) |
|---|---|
| Registration ≠ Delivery · ordered routing · audit continuity · ConsumerResolver · consumer isolation · D-1/D-2 | `docs/adr/ADR-MP-Messaging-Platform.md` (ADR-MP-06) ✔ |
| Parking-as-business-workflow · Dismissed short-circuit · F-1 translation boundary · F-2 resolution enrichment | PB-005 IDD + `developer_guide/contestation/` ✔ |
| Inbox-inherited atomicity | `developer_guide/` (messaging/election); optional ADR-MP note later |
| Strangler reconstitution · idempotency ledger · `ChallengeResolvedIntegration` carrier · PGP-03 hoist · Engineering Standards | Candidate Pattern registry = PB-004 retro §9 + Handover §8 + ADR-MP-03 note ✔ (retrospective backlog) |
| Domain Event ≠ Integration Event | IF adopted: `docs/architecture/principles/` (Category B follow-up); today: Handover §12 ✔ |
| Workflow chain + Trustworthiness Qualification | `Implementation_Process_v1.1_Draft.md` at ratification; today: Handover §3/§9a ✔ |

## Deliverable 4 — Behavioural improvements

**Newly permanent (this review):** the 4 promoted bullets above.
**Already permanent — re-adoption would be duplication (verified against the corpus):** ask-before-redesigning (EP-01/ER-01/R-29) · stop-at-uncertainty (ARR gate) · Discovery→IDD→RED→GREEN→Qualification→Completion sequencing (practiced; normative home = v1.1 draft) · never-silently-introduce-a-pattern (DDD Qualification check) · evidence-before-governance (ER-02/AIP-01) · reviews-record-implementations-repair (ER-08) · observation classes A/B/C/D (OPERATING_INSTRUCTIONS).

## Deliverable 5 — Updated AI Architecture (delta only)

Exactly the block quoted in Deliverable 2. No other line of AST-012/AST-013 changes; pointer discipline (rules-live-once) preserved.

## Deliverable 6 — Architecture Impact Assessment

| Dimension | Impact |
|---|---|
| Governance | **Unchanged** — uses the existing AIP-13 amendment path + append-only rulings log; creates no new mechanism |
| Engineering workflow | **Unchanged** — no step added/removed; the chain stays where it is practiced |
| Coding conventions | **Unchanged** |
| DDD | **Unchanged** — ownership/boundaries untouched |
| Messaging | **Unchanged** — everything Messaging-specific stays in ADR-MP |
| Net effect | **Documents proven behaviour.** +4 guidance lines · −1 deprecated registry asset · 0 new documents. AI Architecture remains small, stable, technology-independent |

---

## Execution steps (ONLY after this plan is approved = ARB adoption)

1. Append the 4-bullet section to `.claude/platform/OPERATING_INSTRUCTIONS.md` (exact text from Deliverable 2).
2. Remove/mark-removed the deprecated `AST-008` entry and update the `AST-013` trace in `.claude/platform/registry.yaml`.
3. Append ruling **R-36** to `docs/adr/ADR-AIP-LOG-Platform-Rulings.md` (adoption of Promotion Matrix items 2, 3, 9, 10; AST-008 removal; B/D items expressly NOT promoted).
4. Update `.claude/CONTEXT.md` (parallel-tracks note) + append to `.claude/sessions/2026-07-09.md` per session rules.
5. **Explicitly out of scope** (separate ARB follow-ups if desired): Category-B promotions (Domain Event ≠ Integration Event → principles doc; workflow chain → v1.1 ratification). Not executed in this slice.

## Verification (doc-only change)

- Re-read the three edited files; confirm no rule text is duplicated from ER/EP/ADR sources (pointer discipline).
- Confirm `registry.yaml` stays valid YAML and hooks still fire (start a command; SessionStart/Stop hooks unaffected — no script touched).
- Confirm OPERATING_INSTRUCTIONS still reads as doctrine ≤ 1 page growth; nothing Messaging-specific entered `.claude`.
- No code gates required (no PHP/test change); do NOT run migrations or tests for this slice.
