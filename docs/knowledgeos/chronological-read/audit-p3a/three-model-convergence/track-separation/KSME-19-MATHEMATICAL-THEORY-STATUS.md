---
source_track: TRACK-A-PHASE-MEASURE
input_artifacts: [KSME-19-MATHEMATICAL-THEORY-DERIVATION-LEDGER, KSME-19-THEORY-CLOSURE-MATRIX, KSME-19-THEORY-DEPENDENCY-DAG]
derived_from: [4 KSME-19 forks; KSME-18]
cross_track_dependency: none
---

# KSME-19 — Mathematical Theory Status

## A. Which results are genuinely SOURCE-DERIVED?

Very few, at the individual-proposition level (not whole-document level, per the commission's own rule):
- D25's **circularity problem statement** (`𝒜`/`ABK-1` defines its own passing criteria) — the source states
  this as a finding about its own material, not a claim requiring reconstruction.
- `KR-CONTR-FDE-2026-09`'s **experimental result table itself** (14 scenarios, FDE collapses 6/14) — an
  executed, complete experiment, not a derivation-in-progress.
- The `174914` document's **own §34 self-disclosure** ("IN PROGRESS"/"OPEN"/"NOT READY") — trivially
  source-derived as a fact about the document's own status.

Everything else in the ledger required reconstruction, partial cross-referencing, or remains undone.

## B. Which are CORPUS-DERIVABLE (a derivation exists in pieces across ≥2 corpus files)?

D13, D14, D18 — each has a **PARTIAL** corpus-derivable status: real content exists in `theory-part-04`
answering part of the question, but not the whole proposition (D13 missing 3 of 4 predicate families; D14
checked for one operation only; D18 missing Identity/Associativity).

## C. Which are DERIVED-BY-RECONSTRUCTION (built from corpus premises, not source-stated as one artifact)?

D1 (core theorem, not the universality claim), D2 (the preservation predicate, not the full `δ`/`O_core`
semantics), D4 (the KSME-08 `R_eq`/`R_ord` prerequisite, not applied to real `EVal` components).

## D. Which have exact computational/formal proof (machine-checked or fully worked)?

None of D1–D27 reach this bar within this pass's audited material. The closest: `KR-CONTR-FDE-2026-09`'s
14-scenario table is a complete, checkable computation, but it answers D3's *broader* claim
empirically — it is not a formal proof of D3's exact `m*=4` bound. This investigation's own BSE (KSME-15/17)
does have machine-checked results, but only for constructed/synthetic regimes — never for a real corpus
`K` candidate, and never for D20 as a corpus theorem.

## E. Which are only hypotheses?

D6, D7 (tier-limited), D8, D9, D11 (tier-limited), D12, D15 (4 of 5 terms), D16, D17, D19, D21 (a
predecessor exists but is explicitly unproven), D23, D24, D26 (corpus side), D27.

## F. Which have been falsified?

- The specific `CLOSURE-5` implementation attempt cited under D22 — **FAILED**, a genuine negative result.
- A prior invalid inference under D23 — **REJECTED** (corrected, not merely disputed).
- FDE's "four values suffice" claim, tested by `KR-CONTR-FDE-2026-09` and directly relevant to D3 —
  **FALSIFIED** (42.9% collapse rate, 6/14 scenarios).

No D-item's own headline claim (D1–D27 as stated) has itself been falsified — only specific implementation
attempts and a competing hypothesis (FDE sufficiency) that some D-items cite as background.

## G. Which remain genuinely UNDERIVED?

D6, D8, D9, D10, D12, D15 (4/5 terms), D16, D17, D19, D20 (as a corpus theorem), D23, D24, D27 — the
majority of the programme. This is the honest headline number: **of 27 items, at most 6 (D1–D4, D13, D14,
D18 partially) have any corpus-grounded content; roughly 12–14 remain entirely untouched by any source
found in this pass.**

## H. Which D1–D27 items are already solved elsewhere in the corpus (outside the `174914` lineage)?

- **D3** — effectively answered (broader claim) by `KR-CONTR-FDE-2026-09`, an entirely separate lineage
  D3 itself cites by identifier but never engages with.
- **D14** — partially answered by `theory-part-04`, in a directory (`mathematical_ideas_that_can_be_
  implemented/`) KSME-16's own exhaustive `phase_measure_theory/`-scoped search never reached.
- **D21** — a genuine predecessor (`Adequate(K_t,EC_t)`, `20260902-004631`) exists under different
  notation, itself unproven.
- **D25** — its circularity problem is the same circularity KSME-18 independently found in `ABK-1`, from a
  document KSME-18 never read — two independent discovery routes to the same problem.

## I. Which are duplicated by earlier KSME work specifically (not just "the corpus")?

- **D16** poses exactly the same open question as KSME-12/16/17's own `Contr_Γ` work — no new derivation
  found; this is the same gap, re-posed, not a new one.
- **D20** (congruence) has no corpus proof, but this investigation's own BSE (KSME-15/17) already built
  machinery for exactly this class of question — for synthetic regimes, never claimed to answer D20 as a
  corpus theorem. Not a duplication of a *result*, but of *scope* — worth flagging so no future pass treats
  KSME-15/17's BSE as if it had already solved D20.

## J. Which introduce genuinely new mathematical content (not found anywhere else in the corpus before)?

Within the audited material: **none conclusively**. D1–D3's own dedicated files (`175313`/`180019`/`180021`)
are themselves new *executions*, but Fork 1 found D1 reconciles two pre-existing corpus fragments rather
than introducing new content, and D2/D3 lean on pre-existing definitions. D4–D27 as stated in `174914` are
**proposals**, explicitly self-disclosed as not yet executed — proposing a definition is not introducing
derived mathematical content. The one item closest to "new": D25's explicit circularity diagnosis, which no
prior source (including KSME-18's own `ABK-1` finding) had stated in exactly this form, though the
underlying problem (ABK-1 not one object) was already found independently.

## K. What is the smallest remaining mathematical gap?

Unchanged from KSME-11/12/13A and KSME-18's own independent corroboration: **enumerate `𝒪` against the 8
ratified primitives** (Rule 258's own `Relevant(p)` criterion), still never closed against a finite
operation/observation universe. This pass adds one candidate second-smallest gap, newly surfaced: **D14's
`History⊆History'` guardrail, verified only for `RETRACT`** — checking the remaining `O_core` operations
against `theory-part-04`'s own per-operation conditions is now a small, well-scoped, named next step (not
executed this pass, per the commission's explicit instruction not to auto-continue into construction).

## L. Does the corpus already contain a mathematically closed theory even without a complete executable Kernel?

**No — and this is worth stating precisely, since the user flagged this question as extremely important
and explicitly distinct from Kernel-completeness.** "Mathematically closed theory" would require every
D-item (or an equivalent complete axiom/theorem/proof set) to be derived and internally consistent, with no
open items. What was actually found: a handful of PARTIAL results (D1–D4, D13, D14, D18), one genuine
predecessor theory document (`20260902-004631`) that **explicitly self-discloses its own "OPEN-6 — Minimal
primitive kernel"** and proves a real theorem (Theorem 9) within a still-incomplete axiom system, and the
`174914` master document's own §34 admission that the whole programme is "IN PROGRESS"/"NOT READY." No
source anywhere in this pass's audited material claims, let alone substantiates, a mathematically closed
theory independent of Kernel-completeness. The two questions (closed theory vs. complete Kernel) are indeed
different claims, as the user noted — but the answer to both, on the evidence gathered, is currently the
same: **neither exists in the audited material.**

## Stop condition assessment (STOP-A through STOP-E)

- **STOP-A** ("a complete mathematical theory is found in the corpus"): **does not trigger** — see Q(L)
  above; no complete theory found.
- **STOP-B** ("a complete Kernel derivation is found"): **does not trigger** — consistent with KSME-18's
  own (corrected) finding; nothing in this pass overturns it.
- **STOP-C** ("a previously assumed 'open' result is actually already proved in the corpus"): **triggers,
  partially** — D3's broader claim ("polarity alone insufficient") is answered by `KR-CONTR-FDE-2026-09`,
  a real, complete, already-executed experiment that D3 itself treats as open. This is reported, not acted
  on further per the commission's explicit instruction not to auto-continue; the narrower `m*=4` claim
  remains genuinely open, so this is a **partial**, not full, STOP-C trigger.
- **STOP-D** ("a KSME result is shown to duplicate older corpus derivation"): **does not cleanly trigger** —
  D14's finding is a new *source* (`theory-part-04`) for an old *question* (KSME-16's `δ` search), not a
  case of a KSME conclusion duplicating something already concluded elsewhere. Named as STOP-D-adjacent,
  not treated as a full trigger.
- **STOP-E** ("a claimed theorem is falsified by an existing counterexample"): **triggers for background
  claims only, not for any D-item's own headline claim** — `CLOSURE-5`'s failure (D22-adjacent) and FDE's
  "four values suffice" (D3-adjacent) are both real falsifications, but of implementation attempts / cited
  background hypotheses, not of any D1–D27 statement as such.

None of the five conditions calls for halting further audit work outright; STOP-C's partial trigger is the
strongest signal and is exactly why D3 is now reclassified `EMPIRICALLY-SUPPORTED` in the Ledger rather than
left at the source's own `OPEN`.

## Explicit instruction honored: no automatic continuation

Per the commission: **this pass does not proceed to D1–D8 construction.** Named, not executed, candidate
next steps, in the order their supporting evidence in this pass makes them most tractable:

1. Check D14's `History⊆History'` guardrail against the remaining `O_core` operations beyond `RETRACT`
   (smallest, most concretely scoped gap found this pass).
2. Reconcile `theory-part-04`'s non-explosion/paraconsistency material (D16-adjacent) against
   `KR-CONTR-FDE-2026-09`'s actual content — flagged, never cross-checked.
3. Read `theory-part-04` §4.43 ("Identity and duplicate ASSERT") — directly relevant to D18's Identity
   property, left unread this pass.
4. Independently verify the `theory-part-03`/`04` provenance concern (mtime clustering) before treating
   their content as full `SOURCE-DERIVED` in any future pass.
5. Resolve the `20260902-182025`/`182026` file-pair provenance flag (identical size, ~1.5h mtime gap, one
   commit) — not read, not resolved this pass.
6. Open `MD-103`'s actual decision-log content directly (only cited secondhand this pass) if D5/D7/D11/D12
   are to move past `CANDIDATE` tier.
7. Search `phase_measure_theory/knowledgeos_kernel/` specifically for D10/D12/D15/D17-adjacent material —
   part of the still-disclosed ~624-file coverage gap, never separately targeted for these particular items.
8. Independently re-run/re-derive `KR-CONTR-FDE-2026-09`'s own experiment if a formal (not merely empirical)
   bound on `m*` is required for D3.

**The next step after KSME-19 must be determined by the user from this evidence** — no step above is
selected, prioritized as mandatory, or begun.
