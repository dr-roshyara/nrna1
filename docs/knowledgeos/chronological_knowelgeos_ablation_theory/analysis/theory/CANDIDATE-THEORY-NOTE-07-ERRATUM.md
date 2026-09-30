# Note 07 — erratum: epistemic downgrades to notes 04–06 (reviewer, 2026-09-27; accepted)

⚠ generated · no model selected. This note supersedes the wording listed below; the earlier notes are unchanged as historical records.

| # | Earlier wording | Corrected wording | Label |
|---|---|---|---|
| E-1 | "no lasting authorization grant exists" | **In the released promotion-chain description, no separate persistent authorization-grant state is specified between Promotion Ready and the Authority Promotion Decision.** | SOURCE (scope-limited) |
| E-2 | "the evidence condition holds at the moment of promotion" | **The promotion decision consumes Promotion Ready. Whether Promotion Ready ≡ the evidence floor (EF) or ≡ the bar (EB) is NOT established.** Promotion Ready → derived from verification → **???** → evidence condition satisfied | SOURCE + open |
| E-3 | "MT1-P0, MT2-P0 inconsistent with ES-001.2" | **Under the declared mapping (governance status ↦ MT `ad`, MEDIUM), MT1-P0 and MT2-P0 are ruled out by the ES-001.2 interpretation.** | DERIVED under ASSUME |
| E-4 | "MT1-P1 inconsistent with the promotion chain" | **MT1-P1 is incompatible with the promotion-chain interpretation if MT's authorization state is intended to correspond to the derived Promotion Ready state (and readiness to EF).** | DERIVED under ASSUME |
| E-5 | T1 A1 "ΔStanding ⇒ explicit decision" | **A1′:** a change of *governance status* requires an explicit authority decision (ES-001.2). Its generalization to all standing is a HYPOTHESIS | SOURCE / HYP |
| E-6 | T1 A5 "a frozen item changes only by supersession" | **A5′:** a frozen artifact cannot change through an ordinary lifecycle transition; any change requires the prescribed new-ADR mechanism. **A5-dict [SOURCE, Phase-02.6 L157]:** for the ubiquitous-language dictionary, the ADR "produces a superseding version". Whether the ADR mechanism always yields a new identity or version is a HYP | SOURCE / HYP |
| E-7 | T1 A6 "every act carries its evidence burden" | **A6′:** each transition that has an evidence burden needs an explicit evidence basis appropriate to that transition. The attested burdens are on *proposals* (R-37) and *promotion* (ES-006.1). Nothing is claimed for other acts | SOURCE / scope-limited |
| E-8 | "MT2-P1 is the only survivor" | **Under the current evidence and mappings, MT2-P1 is the only MT model surviving all current readings. The result is sensitive to two MEDIUM semantic readings, and MT represents neither authority nor scope.** This is a research result, **not** a theory verdict | TEST |
| E-9 | "T1, candidate theory" | **T1: candidate transition ontology**, not a candidate KnowledgeOS theory | — |

## Semantic categories now kept separate (a reviewer synthesis; accepted as working vocabulary)
- **Categories:** Evidence (verification results) · **Derived predicates** (Promotion Ready) · **Decision acts** (the Authority Promotion Decision; HDEs) · **Resulting states** (Promoted Baseline / Authoritative / Frozen).
- **Dimensions:** authority · status · maturity · adoption. These are distinct concepts; along the platform-artifact chain, authority and status are coordinates of one chain position (note 06 §5, a tension with the Metamodel's ⊥).
- **The research question** is now: *which are the primitive state variables, which are derived predicates, which are events, and which transitions are caused by human decisions rather than automatic derivation?* MT-like models are projections of that ontology.
