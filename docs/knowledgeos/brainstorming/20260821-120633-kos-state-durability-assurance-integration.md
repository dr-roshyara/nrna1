---
source:
  original_name: "20260821_1206_kos-state-durability-assurance-integration.md"
  original_path: "docs/knowledgeos/brainstorming/20260821_1206_kos-state-durability-assurance-integration.md"
  detected_timestamp: "2026-08-21 12:06:33"
  timestamp_source: filesystem-mtime
  timestamp_confidence: high
  timestamp_notes: "filename stamp 12:06 agrees with mtime 12:06:33"
classification:
  theme: "03-evidence-assurance-governance"
  type: architecture-proposal
status:
  authoritative: false
  proposed: false
---

# KOS-STATE-DURABILITY × Deterministic Assurance — the integration model

**Kind:** brainstorming / integration analysis — **INPUT only**. ⛔ **This document decides nothing, commissions nothing, mints no identifier, adopts no model, and authorizes no implementation.**
**Authority:** `generated` — AI-produced analysis, per the Knowledge Constitution **not authoritative without human review**.
**Date:** 2026-08-21 · **Place:** `docs/knowledgeos/brainstorming/` — the non-governed home that already carries its input, `how_to_optimize_cost.md`.
**Inputs consumed, not reinterpreted:** `docs/knowledgeos/brainstorming/how_to_optimize_cost.md` · `docs/knowledgeos/reviews/2026-08-21-cost-optimization-governance-assurance-review.md` *(the split, the `X-1`…`X-8` corrections, the Phase 0–4 sequence)* · `docs/plans/20260821-1138-track2-deterministic-assurance-phase0-plan.md` *(the current proposed Phase 0)* · `KOS-AIP-GOV-STATE-DURABILITY-ADR.md` · `KOS-AIP-GOV-STATE-DURABILITY-DECISION.md` *(`B′`, `R-CONFLICT`)* · `KOS-AIP-GOV-STATE-DURABILITY-MIGRATION-PLAN-AMD6-SUMMARY.md`.
**Epistemic classes, kept apart:** `OBSERVED` · `DERIVED` · `RECOMMENDATION` · `OPEN QUESTION`. Never mixed.

---

## 0 · The one-line conclusion

> ## **`KOS-AIP-GOV-STATE-DURABILITY` should be the FIRST real consumer of the deterministic assurance capability — not a second project that waits until durability is finished.**

The two tracks already share the same evidence base: the migration corpus is the checker's back-test corpus. The integration is therefore **a sequencing and authority problem, not a new architecture problem**. Nothing needs to be merged; neither track replaces the other.

---

## 1 · The correct combined model

```
                 KOS-AIP-GOV-STATE-DURABILITY
                              │
                    produces governed evidence
                              │
                              ▼
                  Deterministic Assurance
                              │
          ┌───────────────────┼───────────────────┐
          │                   │                   │
          ▼                   ▼                   ▼
     structure            provenance          corpus integrity
     /references          /identifiers        /sequence
          │                   │                   │
          └───────────────────┼───────────────────┘
                              ▼
                    Human Architecture Review
                              │
                              ▼
                         Governance
                              │
                              ▼
                           PO/ARB
```

**Division of labour, stated as the invariant both documents already carry:**

```
Automation
    ↓ produces evidence
Human authority
    ↓ decides what the evidence means
```

⭐ `RECOMMENDATION` — the durability work **creates the evidence boundary**; the assurance capability **checks mechanical properties of that boundary**. Neither owns the other's half.

---

## 2 · What can start now vs what must wait

The proposal is two halves with different authorities — the review already ruled on both:

| Half | Ruling | Consequence |
|---|---|---|
| ⭐ **Deterministic assurance** *(read-only checker producing a report)* | `KnowledgeOS_Deferred_Architecture_Register`, correction **ACCEPTED 2026-08-04**: *"automation of deterministic work is not speculative architecture"* | ✅ **May start NOW.** Changes no rule, role, gate or artifact; produces evidence only. The freeze does not reach it |
| ⛔ **Assurance classes · risk routing · gates · role-model change** | `.claude/CLAUDE.md`: **methodology frozen 2026-08-01**; the exception (*PublicDigit implementation exposes a genuine deficiency*) does **not** fire — this is KnowledgeOS governance execution | ⛔ **Must NOT start.** Backlog item + a PO/ARB act |

So, explicitly:

```
NOW
✅ deterministic read-only assurance
❌ routing automation
❌ assurance-class routing
❌ new governance gate
❌ independence automation
```

⭐ `DERIVED` — the single most valuable test is **already defined** and is a **falsification experiment, not a theoretical exercise**:

```
AMD4 state (0a2fa71d) · AMD5 state (7d3abc59) · AMD6 state (8307beca)
      ↓
deterministic checker
      ↓
must rediscover the 17 known mechanical findings (DI-1…DI-7, RC-*, RD-*)
      ↓
and must be QUIET on the repaired artifact (the AMD6 quiet row)
```

⛔ If the checker does not rediscover its rows, Phase 0 FAILS and Phase 1 must not start. `RECOMMENDATION`, adopted from the plan's §6 exit criterion.

---

## 3 · The seven integration surfaces

| # | Surface | Mechanism | Authority |
|---|---|---|---|
| **1** | **Phase 0 back-test** against AMD4–AMD6 | the checker's exit criterion reads the migration plan as it stood at each commit and must rediscover the mechanical findings the independent reviews raised | **DA plan approval** (EP-01; `R-46`); none beyond it |
| **2** | **Four-layer trace verification** *(declared trace resolves)* | AMD6 `§0.6.5`'s table — operator IDs `P5·1…P7·3`, criteria 16–20 — are the "objects now exist" that un-gate the deferred automation (Phase 0 §3) | same |
| **3** | **`R-CONFLICT` sequence / integrity checks** | read-only: seq dense + monotonic + superset-after-reconciliation; never a resolution | same — detect, never resolve |
| **4** | **Registration / amendment lineage verification** *(OPEN-M6)* | corpus-level reader: cited grant/aggregate IDs exist; amendment lineage resolves | same |
| **5** | **Post-`B′` scope extension** — the checker must read the relocated governed evidence boundary | `--root`/profile extension; the checker's root resolution **follows** the boundary, it does not define it (§5) | DA approval; the target path is the migration's act |
| **6** | **Author-side pre-handoff report** during migration execution | Phase 1: the migration executor runs the checker before handoff and attaches the report — an automation of the **already-imposed** AMD6 14-point pre-delivery obligation | the existing obligation; ⛔ warn-only |
| **7** | **Report becomes required handoff evidence** | Phase 2: the assurance report is a required attachment to migration execution handoffs | **PO/ARB act**; entry = AMD6 accepted + `OPEN-M6` registered |

⭐ `RECOMMENDATION` — surfaces 1–6 are what make the durability track the first real consumer. Surface 7 is the boundary to Phase 2 and stays behind AMD6 acceptance.

---

## 4 · The load-bearing guardrails

| # | Guardrail | Why it is load-bearing |
|---|---|---|
| **G-1** | ⛔ **The checker must remain WARN-ONLY initially** — exit 0, no gate, no hook, no CI wiring | a blocking checker **is** a governance object and therefore requires authority. Phase 1 adoption changes who performs an existing check, not whether a check blocks |
| **G-2** | ⛔ **The checker cannot discover an undeclared architectural act** | it can verify `ACT → declared normative object → criterion → operator ID`; it **cannot** say *"you forgot to declare an act."* `RD-1`, `RD-7`, `RD-3·b` — the missing-act class — are only caught by human Architecture review (`X-5`; the DA's sentence: *mechanical assurance proves DECLARED STRUCTURE; architecture review discovers UNDECLARED ARCHITECTURAL CONTENT*) |
| **G-3** | ⛔ **Every report states what it did NOT check, positively** | a GREEN report that reads as *"the design is sound"* is false assurance — the top risk of the whole programme |
| **G-4** | ⛔ **No authority manufacture** — the checker emits `CONFLICT DETECTED`, never `INDEPENDENCE = TRUE`; never a decision | composes with `Recording ≠ Asserting · Evidence ≠ Proof · Reference ≠ Ownership · Author ≠ Independent Reviewer · Self-check ≠ Independent Assurance · Execution ≠ Governance`, and is the machine-facing case of `G-2`/`R5b` |
| **G-5** | ⛔ **No frontmatter/card requirement may ride along** | *requiring* a knowledge card on governed architecture artifacts is a documentation-architecture change, and that is frozen |
| **G-6** | ⛔ **No identifier minted** | `CAP`/`EKS` are ungoverned series (`identifier-check` → `INCONCLUSIVE`); `PMR-10` demands a human act before minting |

⭐ `G-2` deserves restating because it is the limitation that preserves the human review: the checker reduces *dangling-reference* defects to zero and reduces *missing-act* defects by nothing. Only a human notices an act the author never declared.

---

## 5 · ⭐ The one coupling to design for now

The future `B′` migration moves governance evidence out of the current runtime location into a governed, tracked boundary. The assurance tool must be able to read **that** boundary.

> ## **Assurance root resolution must FOLLOW the governed evidence boundary; it must not DEFINE or OWN that boundary.**

**Consequence:**
- Ownership of the boundary stays with `KOS-AIP-GOV-STATE-DURABILITY` (`B′` decides the *class*; the *path* is a separate, reserved act).
- The checker is an **adapter over a resolved root**, never the resolver of where evidence authoritatively lives.
- Measured today: `knowledge-lint` is hard-scoped to `docs/knowledge/` and never sees `docs/knowledgeos/` — ⭐ **that single scope gap is why the entire Track 2 corpus had zero mechanical coverage**, and it is the gap `--root`/profile fixes.
- After relocation, the same `--root` mechanism must accept the governed evidence boundary as a root — no new architecture, just the boundary it is pointed at.

`DERIVED` — this is the **main integration dependency** between the two tracks, and it is cheap to satisfy precisely because Phase 0 is designed as adapters over rules (§ `D-1` of the Phase 0 plan), not as a path-bound engine.

---

## 6 · Recommended execution order

```
1 · NOW
     Approve / execute Deterministic Assurance Phase 0
     → historical back-test only (AMD4 · AMD5 · AMD6; quiet on AMD6-current)
     → ⛔ cannot disturb the unreviewed AMD6 chain — read-only by construction

2 · Meanwhile
     AMD6 independent Architecture review
     → Governance bounded review
     → PO/ARB
     → AMD3–AMD6 registration / acceptance  (OPEN-M6)

3 · Before migration execution
     Integrate the checker as the author's existing pre-delivery /
     pre-handoff assurance obligation
     → still warn-only; report attached, never a gate

4 · After migration starts
     Checker reads the relocated governed evidence boundary
     (the §5 property, applied)

5 · Only later
     EKS-06 / assurance classes / routing evolution
     → separate governance decision (PO/ARB + second adopter, ES-006.1)
```

⭐ `RECOMMENDATION` — the sequence is already the conclusion of the source documents: **Phase 0 can run now · the AMD6 chain remains undisturbed · the assurance capability becomes part of migration handoff evidence without changing the authority model.**

**The final stance, stated once:**

> ## **Integrate them NOW — but integrate the DETERMINISTIC ASSURANCE capability into the durability track, not the future role/routing model.**

---

## 7 · Non-decisions and traceability

⛔ **This document does NOT:** adopt any model · create `EKS-06` · mint any identifier · define an assurance class · create or modify a gate · change any review or routing rule · change any script · decide `OPEN-M1`…`M7` · reopen `B′`, `R-CONFLICT` or `INV-ORDER` · touch the AMD6 chain · lift, narrow or reinterpret the **2026-08-01 methodology freeze**. It is an **input** to the existing authorisation paths — the DA's Phase-0 plan approval, and the migration's PO/ARB acceptance — not a substitute for either.

**Traceability:** `docs/knowledgeos/brainstorming/how_to_optimize_cost.md` *(the reviewed input; the "first test case" claim)* · `docs/knowledgeos/reviews/2026-08-21-cost-optimization-governance-assurance-review.md` *(the two-authority split · `X-1`…`X-8` · Phase 0–4 · the DA's `X-5` formulation)* · `docs/plans/20260821-1138-track2-deterministic-assurance-phase0-plan.md` *(§3 objects-exist · §6 exit criterion · § `D-1` adapters)* · `KOS-AIP-GOV-STATE-DURABILITY-ADR.md` *(`O-3`, `B′`, `R-CONFLICT`)* · `KOS-AIP-GOV-STATE-DURABILITY-DECISION.md` *(D1 `B′` · D2 `R-CONFLICT` ADOPTED)* · `KOS-AIP-GOV-STATE-DURABILITY-MIGRATION-PLAN-AMD6-SUMMARY.md` *(§5 four-layer trace · §7 `OPEN-M6`/`OPEN-M7` · §8 14-point pre-delivery verification)* · `KOS-AIP-GOV-STATE-DURABILITY-IMPLEMENTATION-DESIGN.md:119–121` *(the `R-CONFLICT` compliance test)* · `ES-005.4` · `ES-006.1` · `PMR-10` · `G-2`/`R5b` · `R-34`/`P-2` · `R-46`.

**ANALYSIS DELIVERED · STOPPING.** ⛔ **NOTHING IS ADOPTED, COMMISSIONED OR IMPLEMENTED BY THIS ACT.**
