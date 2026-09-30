# Source passages (verbatim). Code ONLY from these.

## S1 — rulings register row R-41

| R-41 | 2026-07-30 | **Artifact Lifecycle Consistency adopted as a permanent documentation standard (Principal Architect instruction).** An artifact has one authoritative lifecycle state at any moment; when work crosses a lifecycle boundary (authorization → execution → acceptance → closure), every authoritative artifact transitions with it. Work plans state the PRESENT state; session logs preserve the SEQUENCE of past states (append-only, ES-004.2); history is never rewritten to manufacture consistency. Minimum synchronization checklist at slice closure: Work Plan · CONTEXT.md · Session Log · Developer Guide · Acceptance Record · ADR references. Provenance: the WP-1 closure inconsistency (a CLOSED plan whose header still read "AUTHORIZED — execution begins…"), corrected 2026-07-30; first checklist execution the same day caught a second instance (ADR-T22 row's issuance-time "Implementation NOT yet authorized" clause, annotated). Parsimony honored: rule text hosted ONCE as **ES-004.3** (documentation standard, not a new standard document); runtime pointers only. | ES-004.3 in force; checklist binds every slice closure |

## S2 — session log 2026-07-30, lines 1-41

# Session Log — 2026-07-30

## Summary
PA instruction institutionalized: **Artifact Lifecycle Consistency** — adopted as **ES-004.3** (canonical, hosted once) + register ruling **R-41**; runtime pointer in `.claude/CLAUDE.md`; MEMORY hint extended. First checklist execution against WP-1's closure caught a second real instance.

## Completed
- **ES-004.3 hosted** in `engineering/governance/ES-004-Documentation.md`: one authoritative lifecycle state per artifact · Draft→Authorized→Executing→Accepted→Closed · Historical Record Rule · plan-states-present vs log-keeps-history separation · slice-closure synchronization checklist (Work Plan · CONTEXT · Log · Guide · Acceptance · ADR refs) · consistency-review questions · Historical Integrity (never rewrite history to fake consistency) · expected AI behavior at closure.
- **R-41 appended** to the platform rulings register (chronological, after the parallel session's R-40). Parsimony honored: rule text lives once in ES-004.3; the register records the decision event.
- **Checklist executed against WP-1 (first application):** Work Plan ✔ (status line fixed `307b90e8e`) · CONTEXT ✔ · Session Log ✔ (history intact) · Dev Guide ✔ · Acceptance Record ✔ · **ADR references ✘→✔**: ADR-T22's row still carried the issuance-time "Implementation NOT yet authorized" — annotated *(status at issuance — since REALIZED by WP-1, ARB-accepted 2026-07-27; ES-004.3 annotation, decision text unchanged)*. ADR-T21's identical clause verified still ACCURATE (WP-3 not yet authorized) — untouched.
- Pointers: `.claude/CLAUDE.md` (one line, points not restates) · MEMORY ES-004 hint extended.

## Decisions
- ES-004.3 adopted by explicit PA instruction (recorded R-41); the rule generalizes the WP-1 status-line correction into permanent governance.

## Next Steps
**WP-2 (APM core), fresh session** — unchanged single next action: work plan from roadmap §WP-2 → RED keystones → GREEN → gates → slice acceptance. ES-004.3's checklist now binds that closure and every closure after it.

---

## ES-004.3 REFINED ROLE-BASED (ARB review adopted; structure/clarity only, substance unchanged)

- **Artifact Roles added** (the architectural rationale the file-list lacked): **Runtime** (CONTEXT · active plan — describes today) · **Historical** (session logs · git — never rewritten) · **Reference** (dev guides · governance — tracks knowledge) · **Decision** (ADR logs · rulings · acceptance — text immutable, status annotations evolve).
- **Governing distinction added:** *synchronization updates only the MUTABLE portion of an artifact; immutable historical or decision content is never rewritten* — with the ADR-T22 first-execution case recorded as the demonstration and the forbidden misreading named.
- **Checklist rewritten role-based** (Role · Check · Purpose), replacing the bare file list. Pointers updated in `.claude/CLAUDE.md` + MEMORY hint (point, never restate). **No new register row** — R-41 remains the decision record; this is refinement of the same rule, noted in the provenance line.
- **Self-review passed:** roles non-overlapping · distinction explicit · checklist role-based · no immutable content touched (R-41 text and session history untouched; ES-004.3 itself is a Reference artifact legitimately updated on a knowledge change).

## Next Steps (single action — unchanged)
**WP-2 (APM core), fresh session, RED first.** ES-004.3 (role-based) binds its closure.

---

## ES-004.3 VALIDATION EXECUTED — VALIDATED UNCHANGED, DECLARED ARCHITECTURALLY STABLE

- **Report:** `engineering/verification/reports/2026-07-30-es-004-3-artifact-lifecycle-validation.md` — 9 real artifacts classified across all four roles; every one fits exactly one primary role at any moment; all five completeness concepts COMPLETE; boundary clean (no creep into workflow/ADR-methodology/promotion/structure).
- **Negative validation:** no simultaneous multi-role artifacts · no divergent sync behavior · no mutable/immutable violations at rule level · no missing lifecycle state. Register: **O-1** (plans change role at closure — already covered by the Historical Record Rule) · **O-2 watch item** (composite artifact: acceptance record embedded in the closed plan — single instance, below the evidence bar) · **F-2 repository finding, referred out** (CONTEXT.md violates its own Runtime role: 2 superseded blocks + 53 completed-history entries / 160 lines — the rule DETECTING this is evidence it works; prune = separate authorized housekeeping) · **E-1** (chair's noted lifecycle duplication no longer exists — consolidated in the role-based rewrite; verified one occurrence).
- **Recommendation issued (exactly one): ES-004.3 validated unchanged → architecturally stable.** Refinement stops until future implementation evidence (O-2 second occurrence or repeated ambiguity).

## Next Steps (single action — unchanged)
**WP-2 (APM core), fresh session, RED first.** Separately awaiting chair disposition: the F-2 CONTEXT.md prune commission (optional housekeeping, not blocking).

---

## S3 — session log 2026-08-15, lines 483-495

### Ninth — PO/ARB ACCEPTED the finalization; vocabulary ruling registered and applied

* **Acceptance registered verbatim** (`…-qualification-adoption-closure.md` §8): *"I would accept this Governance result."* · *"This is not a reason to reopen this work item."*
* **⚠️ BINDING VOCABULARY RULING — applies to ALL future documentation, not just this artifact:** closure must always be stated with its plane named —
  > **Governance/documentary closure: CLOSED.**
  > **Authoritative workflow-machine state: OPEN, because AST-015 has no closure transition.**

  *"Do **not** allow future documentation to casually say that the machine record itself says CLOSED. It doesn't."*
* **Applied immediately** to the closure artifact §5 (headline restated in the ruled two-line form), `CONTEXT.md`, and this log's §Eighth heading. My earlier single-line "is CLOSED" headline was exactly the formulation the ruling forbids — corrected, not defended.
* **PO/ARB architectural observation registered — three distinct state planes**, which are *not* one state machine: AST-015 execution state (`OPEN`/`STOPPED`, machine-authoritative) · governance lifecycle state (`QUALIFIED`/`ADOPTED`/`CLOSED`, documentary) · architecture findings (`V-3` open, documentary). *"The danger would be pretending they are one state machine when they currently are not."*
* **Disposition:** registered as the PO's **framing of `O-CLOSURE-VOCAB`**, to belong to that item **if and when it is commissioned**. **NOT promoted to methodology** — single occurrence, and ES-006.1 forbids promoting from one. Whether the planes should be unified, formally separated, or left alone is **architecture work nobody is yet authorized to perform**.
* **Nothing reopened. Nothing commissioned. Sessions 1 and 3 not re-engaged.**


## S4 — promotion-review plan, lines 23-50 (Deliverable 1 — Promotion Matrix)

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


## S5 — rulings register row R-36

| R-36 | 2026-07-09 | **AI Architecture Promotion Review ADOPTED (evidence-based; PB-004 · PB-005 · PB-006).** First use of the AIP-13 amendment path after the R-27 freeze precondition (PB-004 retrospective) was met. **Promoted into AST-013 (`OPERATING_INSTRUCTIONS.md` §Promoted Engineering Behaviours), 6 one-line behaviours:** ownership-determines-architectural-reuse (never precedent) · reuse-before-create · deferred ≠ skipped · epistemic labels (Observed·Measured·Derived·Interpreted·Recommended — broadened to ALL architectural recommendations/reviews) · stop-at-architectural-uncertainty (surface, classify, request ARB — never silently invent architecture) · implementation-evidence-outweighs-unverified-theory (ARB wording refinement at adoption: "unverified theory", not "theoretical elegance" — elegance is not the enemy; untested assumptions are). **Expressly NOT promoted:** Registration ≠ Delivery stays Messaging-scoped in ADR-MP-06 (1 slice); Domain-Event ≠ Integration-Event + the Discovery→IDD→RED→GREEN→Qualification→Completion chain are Category-B follow-ups (principles doc / v1.1 ratification — separate slices); Strangler reconstitution, `ChallengeResolvedIntegration` carrier, PGP-03 hoist, Engineering Standards (R-28/CMP-009) remain Candidates. **AST-008: deprecate → remove next release** (one migration cycle). **Promotion Report:** promoted 6 (2 added by ARB at review) · Category-B follow-ups 2 · platform-doc (C) 4 · candidates (D) 5 · already-permanent (duplication refused) 4 · removal scheduled 1 · new documents 0. Full matrix: `claude/plans/swirling-jingling-blossom.md`; session log 2026-07-09. | AST-013 amended (+6 lines); AI Architecture smaller-per-value; no governance/workflow/DDD/Messaging change |

## S6 — session log 2026-07-30, lines 193-204

## DDD-Driven Architectural Implementation Protocol received (17 phases) — ADOPTED AS THE OPERATING STANDARD for implementation work; NOT self-promoted into a standards document

**Received from the PA as the going-forward protocol.** In force from the next work package. Its ordering is binding: *Business Model → Strategic Architecture → Architectural Decisions → Approved Design → Implementation → Repository State → Operational Evidence* — never reversed.

**Confirmed already-demonstrated in WP-1/WP-2** (so this formalizes practice rather than introducing untested process): Commission Reset (Ph 1) · Authority Register (Ph 2) · Business Understanding (Ph 3) · Strategic DDD (Ph 4) · Business Model Fidelity (Ph 5) · Traceability (Ph 6) · Simplification (Ph 7) · Business Assumption Review (Ph 8) · RED-first with business-behaviour tests (Ph 10) · minimal GREEN (Ph 11) · Architectural Verification (Ph 13) · Static analysis as design feedback (Ph 14 — the `whereNotIn` type-erasure fix was exactly this) · **Failure Analysis (Ph 15)** — applied when the state guards rejected my own test: the five-way diagnosis returned "incorrect test", so the test changed and the code did not · Governance Discipline (Ph 16) · Completion Review (Ph 17).

**Governing principle carried at the top of the protocol** (originating in this slice's mapper self-correction, now generalised by the PA): *a good question does not become a decision by being well argued* → **evidence + reasoning is not authority; a decision is an explicit act by an authority.** Applied twice in WP-2 — the mapper (roadmap won over my simplification argument) and F-T1 (PM-1's wording won over my inference).

**GOVERNANCE FLAG — one decision belongs to the DA, not to me.** This protocol is, in substance, the **"Implementation Execution Contract"** candidate that the PA parked on 2026-07-26 with an explicit promotion criterion: *extract only after WP-1 plus a few more slices demonstrate the shape is stable; revisit ~WP-3/WP-4* (recorded with the slice-ledger candidate). Evidence today = **two** slices (WP-1, WP-2). Writing it into a governed standards document now would be exactly what the protocol's own **Phase 16** forbids — *promoting observations into standards* without authorization — so I have **not** created one. Options for the DA: **(a)** adopt now as an explicit early promotion (R-39 is the precedent for DA-authorized early promotion, recorded as an exception so the normal bar stays intact); **(b)** hold to the parked criterion and let WP-3 supply the third instance, following the protocol meanwhile without a standards doc. **Recommendation: (b)** — the protocol is followed either way, and one more slice makes the promotion evidence-backed rather than assertion-backed.

*(Practical note: whichever is chosen, the protocol must reach the next session through the runtime path — the WP-3 work plan will carry it, as the WP-1 plan carried the execution contract.)*

