---
source_track: TRACK-A-PHASE-MEASURE
input_artifacts: [KSME-18-KERNEL-DERIVATION-INVENTORY, KSME-18-KERNEL-DERIVATION-LEDGER]
derived_from: [4 KSME-19 forks; mathematical_ideas_that_can_be_implemented/20260911-174914 and family; theory-part-03/04; 20260902-004631; KR-CONTR-FDE-2026-09]
cross_track_dependency: none
---

# KSME-19 — Mathematical Theory Derivation Ledger

Covers all 27 items of the `20260911-174914` "Mathematical Theory Completion & Derivation Programme."
**The document itself is not a derivation record** — its own §34 self-assessment states "Semantic
derivations: IN PROGRESS," "Minimal kernel: OPEN," "Theory v1.3: NOT READY." Only D1–D3 were ever executed
as dedicated files by the corpus's own D-series; D4–D27 exist only as the master document's own proposed
definitions.

| ID | Statement (source-exact) | Source | Derivation found? | Status |
|---|---|---|---|---|
| D1 | Distinction criterion `∼_d`, separation, preservation `sim_ρ⊆sim_d` | `175313` | Yes, reconciles 2 corpus fragments | `DERIVED-BY-RECONSTRUCTION` (core theorem); `UNDERIVED` (universality claim, falsification never run) |
| D2 | `δ:S×O×Γ⇀S`, reflection/injectivity, `Semantic change≠collapse` | `180019` | Yes | `DERIVED-BY-RECONSTRUCTION` (predicate only) — **corrects the document's own "MATHEMATICALLY CLOSED" overclaim**; `O_core`/full `δ` semantics remain open per its own §26 |
| D3 | Minimal valuation `m*≥4` conditional lower bound | `180021` | Yes, plus a corpus-wide match found | **`EMPIRICALLY-SUPPORTED`** for "polarity alone insufficient, boundary metadata necessary" (via `KR-CONTR-FDE-2026-09`, 14-scenario experiment, FDE collapses 6/14); `CANDIDATE` for exact `m*=4` |
| D4 | EVal Information Sufficiency | `174914` | Conceptually unlocked (KSME-08's `R_eq`/`R_ord` split) | `DERIVED-BY-RECONSTRUCTION` (prerequisite only); `UNDERIVED` (never applied to real `EVal` components) |
| D5 | Provenance vs. Warrant | `174914` | Partial — `theory-part-04`-adjacent, MD-103's "Evidence redefined as a relation" (secondhand, unverified) | `CANDIDATE` |
| D6 | EVal Aggregation | `174914` | Not searched this pass | `UNDERIVED` (not covered) |
| D7 | Determination Semantics | `174914` | MD-102/103/104 secondhand claims exist (governance-log, not primary) | `CANDIDATE` (tier-limited by secondhand source) |
| D8 | EVal→Determination mapping | `174914` | Not found | `UNDERIVED` |
| D9 | Factivity | `174914` | Not found | `UNDERIVED` |
| D10 | Gap `Δ(Q,Γ,K)`, `N⇏Gap` | `174914` §718–749 | Not found beyond source's own admission | `CANDIDATE`/`UNDERIVED` |
| D11 | Determination Ontology | `174914` | MD-102/103/104 secondhand only | `CANDIDATE` (tier-limited) |
| D12 | Decision boundary chain | `174914` §800–827 | Not found; `MD-103` lead not chased | `UNDERIVED` |
| D13 | Operation identity (`Pre_o`,`Post_o`,etc.) | `174914` §830–894 | Partial — `theory-part-04` §4.30–4.34 gives per-op conditions for `O_core`, but `Provenance_o`/`Failure_o`/`Partiality_o` absent everywhere | `CORPUS-DERIVABLE, PARTIAL` |
| D14 | `δ:K×O×Γ⇀K`, `History⊆History'` vs. `Knowledge⪯Knowledge'` | `174914` §898–932 | **Same question as KSME-16's exhaustive `phase_measure_theory/`-scoped search — answered partially from `theory-part-04`, a source KSME-16 never reached** (different directory) | `CORPUS-DERIVABLE, PARTIAL` — notational variant confirmed via Symbol Identity, not literal match; the specific `History`/`Knowledge` guardrail only checked for one operation (`RETRACT`), not proven generally |
| D15 | Lifecycle states (`Retracted≠Superseded≠...`) | `174914` §935–958 | Only `Retracted` found (`theory-part-04` §4.33) | `UNDERIVED` (4 of 5 terms) |
| D16 | `Contr_Γ(x,y)` relational properties | `174914` §962–1016 | **Same question as KSME-12/16/17** — no new derivation of `Contr`'s own properties; one adjacent non-explosion/closure lead found, not cross-checked | `UNDERIVED` (unchanged from prior KSME work) |
| D17 | `Scope(Contr,Γ)`, `ISOLATE(K,p,Γ)` (ternary) | `174914` §1020–1048 | **Symbol collision found, not a match**: `theory-part-04`'s `ISOLATE(I_1,I_2)` is binary, different role | `UNDERIVED` |
| D18 | Operation composition (`∘`) | `174914` §1052–1097 | Partial — `theory-part-04` §4.40–4.42 confirms non-commutativity with a concrete worked example, plus idempotence discussion | `CORPUS-DERIVABLE, PARTIAL` — Identity/Associativity/precondition-propagation not found; §4.43 ("Identity and duplicate ASSERT") flagged unread |
| D19 | Contextual observational equivalence | `174914` | No dedicated derivation | `UNDERIVED` |
| D20 | Congruence `F∘T̂=T∘F` | `174914` | Source itself flags this as newly proposed, not prior scope | `UNDERIVED` (corpus theorem); `PROVED-IN-TESTED-SCOPE` (this investigation's own BSE, for synthetic/constructed regimes only — methodology, not a corpus theorem about a real candidate) |
| D21 | `Adequate(K,Q,Γ)` | `174914` | Related predecessor found: `20260902-004631`'s `Adequate(K_t,EC_t)`, different notation, explicitly not proven | `CANDIDATE` |
| D22 | Executable adequacy | `174914` | Named prior test **`CLOSURE-5` FAILED** | `FALSIFIED` (the specific prior test); `UNDERIVED` (D22 itself) |
| D23 | Complexity | `174914` | Only a correction of a prior invalid inference found | `REJECTED` (the invalid inference); `UNDERIVED` (a real proof) |
| D24 | Determinism (Implementation vs. Constitutional) | `174914` | Proposed, not derived | `UNDERIVED` |
| D25 | Candidate architecture space `𝒜` | `174914` | Circularity (`ABK-1` defines its own passing criteria) named by the source itself — **independently corroborates KSME-18's own `ABK-1`-not-one-object finding**, from a document KSME-18 never read | `UNDERIVED` (D25 itself); `SOURCE-DERIVED` (the circularity problem statement) |
| D26 | Minimality (7 distinct notions) | `174914` | Source's own "current four-candidate experiment" explicitly insufficient | `UNDERIVED` (corpus); `CONSTRUCTION` (this investigation's own 3-notion framework, KSME-15/17, confirmed unrelated to and not sourced from this document) |
| D27 | Kernel reduction `K*=argmin` | `174914` | Explicitly "should be a result, not an assumption" | `UNDERIVED` |

## Provenance concerns, disclosed not resolved

`theory-part-03`/`04` (2026-09-06, mtimes ~90 seconds apart) show a pattern "more consistent with rapid/
batch generation than incremental multi-file authorship" — less extreme than the confirmed-fabricated
`182xxx` cluster (26 seconds, 27 files) but the same genre of concern. Everything sourced from these two
files in this ledger is tagged `SOURCE-CLAIMED-WITH-PROVENANCE-CONCERN`, not full `SOURCE-DERIVED`, pending
independent check. MD-102/103/104 are governance-log entries from a prior session (not primary corpus) —
tagged `SOURCE-CLAIMED-VIA-CITATION` throughout, one primary file independently verified
(`20260825-235804_kernel-problem-minimum-substrate-for-conditional-determination.md`).

## Naming collision, disclosed

`174632` (a `20260911` precursor file) proposes its own, topically different D1–D5 numbering ("Polarity
minimality," "EVal sufficiency," etc.) — a genuine collision with `174914`'s D1–D27. Never conflate
"`174632`'s D1" with "`174914`'s D1."
