---
source_track: TRACK-A-PHASE-MEASURE
input_artifacts: [KSME-07-D4-D14-DERIVATION-LEDGER, KSME-06A-REPORT, TRACK-A-INDEPENDENT-BASELINE]
derived_from: [mathematical_ideas_that_can_be_implemented corpus, D1, D2, D3, KR-CONTR-FDE-2026-09]
cross_track_dependency: none
---

# KSME-07 — Controlled Semantic Derivation and Kernel Construction: Status Report

**Track-B firewall**: confirmed held throughout. No `gap-discovery/` path, no
`so_model.py`/`kos_kernel.py`, no Track-B state shapes, no Track-B numeric
results (`41,820` / `16` / `17,129` / `27,398`) were read, imported, or used
as inspiration anywhere in this pass.

**Scope actually executed, stated plainly**: this pass did **not** attempt
D4–D14. It audited D1–D3 (read in full, not from memory) and closed the one
concrete, bounded, already-identified gap each of D1 and D3 name as their own
prerequisite for closure. This is a real, disciplined, bounded installment —
not the full commissioning — per the commissioning's own instruction not to
force a blind sequential execution of D4→D14.

## 1. What D1–D3 actually establish (see `KSME-07-D4-D14-DERIVATION-LEDGER.md` for full detail)

- **D1 (Distinction)**: the equivalence-relation formalization is correct
  and complete for the case it covers, but its implicit claim that *every*
  required distinction reduces to an equivalence relation is **falsified**
  by real corpus evidence — the source itself leaves open whether comparing/
  challenging assertions requires a partial order, lattice, or bilattice
  (`20260826-173048...md`, explicitly unresolved in the source). `D1` is
  reclassified `GENERALIZATION REQUIRED`, not `CLOSED`.
- **D2 (Preservation)**: independently re-read in full and confirmed
  consistent with its own author's verdict, `MATHEMATICALLY CLOSED`, with
  two disclosed open qualifications (operation-specific preservation
  contracts; complete `δ` semantics). This gives the **type** of `δ`
  (`S×O×Γ⇀S`) and the **invariants** any candidate `δ` must satisfy
  (the reflection condition, historical preservation) — this is real,
  already-derived content, not new to this pass, restated for the record.
- **D3 (Minimal polarity)**: was explicitly `OPEN`, pending its own stated
  falsification requirement. **Closed this pass** using a real, dated,
  already-executed prior experiment (`KR-CONTR-FDE-2026-09`, 2026-09-02,
  Status: COMPLETE) that D3 itself does not appear to cite directly. The
  experiment's real 14-scenario, 4-model separation matrix supplies exactly
  the witness table D3 asked for, and independently confirms both D3's
  lower bound (`m*=4`, now `VERIFIED` not `DERIVED-CANDIDATE`) and D3's own
  flagged-but-unconfirmed claim that polarity alone is insufficient
  (boundary metadata is separately required — also now `VERIFIED`).

## 2. Determinism / relational-nature of `δ` (Part 5 of the commissioning)

D2's own formalization already answers this, correctly, without needing new
work this pass: `δ` is defined as a **partial function**
(`δ:S×O×Γ⇀S`, `Dom(δ_o^Γ)⊆S`), explicitly not assumed total, not assumed
deterministic beyond what "function" already implies (a function is
single-valued by definition; D2 never claims or needs nondeterminism). This
is consistent with, and does not contradict, `KSME-06A`'s finding that no
concrete effect body exists for any real operation — D2 establishes the
*signature and invariants* `δ` must have; `KSME-06A` establishes that no
source anywhere fills in the *body*.

## 3. Operation materiality (Part 12) — not tested this pass

Because no concrete `δ` body exists for any operation (`KSME-06A`), the
materiality question ("does an undefined operation actually change the
behavioral quotient") cannot yet be tested — there is no quotient to test it
against. This is named as blocked, not attempted with a manufactured
substitute.

## 4. Final decision-tree classification (Part 17 of the commissioning)

Per the commissioning's required A/B/C/D taxonomy:

$$
\boxed{\text{Case D — DERIVATION UNDERSPECIFIED}}
$$

**Not Case C (inconsistent)**: no contradiction was found between D1, D2,
D3, or between them and `KSME-06A`'s findings — D1's gap is a scope
limitation, not a contradiction; D3's closure this pass strengthens rather
than conflicts with prior work.

**Not Case A or B**: no executable or partial transition semantics exist yet
(`D14` remains `UNWITNESSED`, confirmed again independently by D2's own
scope statement — D2 explicitly does not claim to have derived `δ`'s body).

**Case D, precisely**: the corpus does not yet provide enough constraints to
derive a unique semantic system for `δ`, but no contradiction has been
established that would rule one out. D1 needs a real (not yet attempted)
generalization to cover order-type distinctions. D4–D13 remain unstarted.
This is recorded as the honest state, not inflated and not treated as
failure — it directly continues, rather than repeats, `KSME-06A`'s Case C
verdict at the search level: the search is closed (no effect exists to
find); the derivation is open (an effect could in principle be built, but
isn't yet, and the prerequisites for building it soundly are only partially
in place).

## 5. Answers to the commissioning's Part 20 questions

1. **What did D4–D14 actually establish?** Nothing — not executed this pass.
2. **What remains source-derived versus newly derived?** D1/D2/D3's own
   content is `mathematical_ideas_that_can_be_implemented`'s own derived
   work (already existed before this session touched it); this pass's only
   new contribution is the D1 falsification finding and the D3-KR-CONTR-FDE
   cross-reference — both are *connections found*, not new mathematics
   invented.
3. **Is a semantic carrier now well-defined?** No — D1's generalization gap
   means even the *carrier* question (what counts as a state, what counts as
   a distinguishing criterion) is not yet uniformly settled.
4. **Is `δ` a function, partial function, relation, or still undefined?**
   Its *type* is settled by D2 (`partial function`, `S×O×Γ⇀S`); its *body*
   is `UNWITNESSED` (confirmed by both `KSME-06A` and D2's own scope limits).
5. **Which operations are material?** Untested — no quotient exists yet to
   test against.
6. **Which state components are behaviorally necessary?** Untested, for the
   same reason.
7. **Smallest verified quotient currently computable (Track A)?** None —
   this remains `KSME-03`'s status (the 27-class oracle-minimal *observational*
   quotient on S0881, and the disclosed `HYPOTHESIS`-tier 37-class *behavioral*
   quotient on a 109-state carrier — neither changed this pass).
8. **What has been falsified?** D1's implicit "all distinctions are
   equivalence relations" claim (§`KSME-07-D4-D14-DERIVATION-LEDGER.md` §2).
9. **What remains unresolved?** D1's generalization; D4–D13; whether `{0,1}²`
   specifically (vs. cardinality 4 generally) is required; the full Boundary
   taxonomy's completeness.
10. **Can Track A now independently produce `K_R^A`?** No.
11. **If yes, compute it.** N/A.
12. **If no, identify the exact mathematical obstruction.** Two, precisely:
    (a) `D1` needs re-derivation to cover order-type required distinctions
    before a general-purpose distinction/preservation theory can be trusted;
    (b) even granting (a), `D4`–`D13` are entirely unstarted, and `D14`'s
    body requires concrete per-operation effects that `KSME-06A` already
    proved absent from the admissible corpus at the `SOURCE-ESTABLISHED`
    tier — meaning even a fully-derived D1–D13 would still terminate at the
    same wall `KSME-06A` already found, unless the corpus is found to
    contain effect-level material `KSME-06A`'s bounded search missed (its own
    disclosed scope limitation, §"What would change this verdict").

## 6. Explicit non-actions (no-canonicalization, unchanged)

No Kernel tuple assumed or proposed. No Track-B comparison attempted. No
D1 re-derivation for the order case attempted this pass (named as the
concrete next step, not executed — it is real mathematical work, not a
search, and deserves its own scoped commission). No operation materiality
claims manufactured in the absence of a computable quotient.
