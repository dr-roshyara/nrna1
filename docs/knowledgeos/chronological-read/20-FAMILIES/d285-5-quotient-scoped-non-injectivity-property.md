# d285-5-quotient-scoped-non-injectivity-property

**Scope(s):** OBJECT · **Row count:** 4 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** "D285-5", "δ(K0,o1) ≡ δ(K0,o2) under a projection discarding Π" · **Aliases:** "D285-5 property"
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- OBJECT-INDEX, batch B0052, scope OBJECT: "A named corpus property (D285-5) asserting non-injectivity of the transition function delta modulo a quotient discarding governance/provenance (Pi): there exist distinct operations o1≠o2 and a state K0 with delta(K0,o1) ≡ delta(K0,o2) under that projection. Step 287 revalidates it against all four Step-246 equality relations (vacuous under structural/provenance-sensitive equality, substantive under semantic equality conditional on Step 254 Decision 3, undefined under observational equality) and narrows its strength from a claim about delta itself to a claim about delta composed with a quotient."

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S2132 §"`CORPUS` — **Step 254 Decision 3, verbatim OPEN:** *"Is governance/authority part of semantic equality? Should `K₁ = K₂` require the same Authority/Policy/Governance, or only the same epistemic content?"*"]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S2139. Candidate lifecycle: DORMANT. Evidence: retracted_by and superseded_by are both empty and no row is self-typed as a contradiction; this is a heuristic based on how recently (by source_id order) this label was last used (S2139), not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up

| Dimension | Status | source_ids |
|---|---|---|
| purpose_rationale | PRESENT | S2132, S2132, S2132 |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | NOT-EVIDENCED-IN-CAPTURE | — |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | PRESENT | S2139 |
| dependencies | PRESENT | S2132, S2132, S2139 |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S2132, S2139 |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | PRESENT | S2132 |

## Rationale
Step 254 Decision 3 (verbatim open): whether governance/authority (Pi) is part of semantic equality, i.e. whether K1=K2 must require identical Authority/Policy/Governance or only identical epistemic content. Both branches (Pi inside vs outside equiv) are shown formally consistent, with consequences for whether D285-5's property is vacuous or substantive, whether ≅_λ collapses into ≡, whether a named projection (D285-6) is legitimate, and whether provenance-blindness is possible by construction; no recommendation is offered, since it is characterized as a decision about what KnowledgeOS wants equality to mean. [S2132] D285-5 is revalidated against all four corpus equality relations: under structural equality the antecedent cannot hold (Pi is part of identity) so the property is VACUOUS; under semantic equality it is SUBSTANTIVE, conditional on Decision 3; under observational equality it is UNDEFINED as stated (the antecedent can hold but the property is relative); under provenance-sensitive equality it is again VACUOUS (delta is injective there); 'history' is noted as not a corpus relation at all. The precise content of D285-5 is narrowed to: there exist distinct operations o1≠o2 and a state K0 such that delta(K0,o1) ≡ delta(K0,o2) under a projection discarding Pi — i.e. non-injectivity modulo a quotient, a property of the composite delta-then-quotient, not of delta itself; the file states the previously stronger wording is SUPERSEDED. [S2132] Five identity notions are enumerated, of which only two are defined: state identity (id = H(P,e,c,t,Pi), while K_t itself has none) and provenance identity (Pi, safe at t=0); operation identity, authority-act identity (a Grant has a grantId but the underlying act does not), and event identity (no schema) remain undefined. Explicitly states D285-5 does not imply a general identity rule, since its property concerns an equivalence on states while a separately-noted grantId collision concerns identity on authority acts — different layers, and an earlier citation linking them is withdrawn. [S2132]

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S2132] types=[OPEN-QUESTION, ANALYSIS] scope=OBJECT — "Step 254 Decision 3 (verbatim open): whether governance/authority (Pi) is part of semantic equality, i.e. whether K1=K2 must require identical Authority/Policy/Governance or only identical epistemic content. Both branches (Pi inside vs outside equiv) are shown formally consistent, with consequences for whether D285-5's property is vacuous or substantive, whether ≅_λ collapses into ≡, whether a named projection (D285-6) is legitimate, and whether provenance-blindness is possible by construction; no recommendation is offered, since it is characterized as a decision about what KnowledgeOS wants equality to mean." (anchor: "`CORPUS` — **Step 254 Decision 3, verbatim OPEN:** *"Is governance/authority part of semantic equality? Should `K₁ = K₂` require the same Authority/Policy/Governance, or only the same epistemic content?"*")
- [S2132] types=[ANALYSIS, CORRECTION] scope=OBJECT — "D285-5 is revalidated against all four corpus equality relations: under structural equality the antecedent cannot hold (Pi is part of identity) so the property is VACUOUS; under semantic equality it is SUBSTANTIVE, conditional on Decision 3; under observational equality it is UNDEFINED as stated (the antecedent can hold but the property is relative); under provenance-sensitive equality it is again VACUOUS (delta is injective there); 'history' is noted as not a corpus relation at all. The precise content of D285-5 is narrowed to: there exist distinct operations o1≠o2 and a state K0 such that delta(K0,o1) ≡ delta(K0,o2) under a projection discarding Pi — i.e. non-injectivity modulo a quotient, a property of the composite delta-then-quotient, not of delta itself; the file states the previously stronger wording is SUPERSEDED." (anchor: "**What `D285-5` establishes, exactly:** ∃ `o₁ ≠ o₂`, ∃ `K₀` with `δ(K₀,o₁) ≡ δ(K₀,o₂)` under a projection discarding `Π`. **Non-injectivity MODULO A QUOTIENT** — a property of `δ ∘ q`, **not of `δ`.**")
- [S2132] types=[ANALYSIS, DISTINCTION] scope=THEORY-LEVEL — "Five identity notions are enumerated, of which only two are defined: state identity (id = H(P,e,c,t,Pi), while K_t itself has none) and provenance identity (Pi, safe at t=0); operation identity, authority-act identity (a Grant has a grantId but the underlying act does not), and event identity (no schema) remain undefined. Explicitly states D285-5 does not imply a general identity rule, since its property concerns an equivalence on states while a separately-noted grantId collision concerns identity on authority acts — different layers, and an earlier citation linking them is withdrawn." (anchor: "`state identity` ✅ (`id = H(P,e,c,t,Π)`; **`K_t` itself has none**) · `operation identity` 🔴 · `authority-act identity` 🔴 (`Grant` has `grantId`; the **act** has none) · `event identity` 🔴 (no schema) · `provenance identity` ✅ (`Π`, `t=0`-safe).")
- [S2139] types=[CORRECTION, PRINCIPLE] scope=THEORY-LEVEL — "Establishes that no implication hierarchy among the equality relations is proven or licensed: 25J.5 itself leaves the exact-implies-structural-implies-semantic chain with an open question mark on the second arrow; 261.9 boxes 'no universal equality hierarchy has yet been proven'; and 258.19 explicitly states the relations should NOT be interpreted as a strict mathematical hierarchy but as separate semantic constructs connected by explicit rules. Consequence: D285-5's previously-used chain 'history ⊊ structural ⊊ semantic' is not merely unmeasured but positively forbidden by the corpus's own stated position, with the correction already propagated to D285-5's source and REFINED-STEP-287 §2." (anchor: "`25J.5` writes `E_exact ⇒ E_struct ⇒`**`?`**`E_sem` — the question mark is the corpus's. `261.9`: $\boxed{\text{No universal equality hierarchy has yet been proven}}$ ... `D285-5`'s `history ⊊ structural ⊊ semantic` chain is therefore not merely unmeasured — it is **forbidden**.")

## Notes for P3
- This label is ungrouped in P2a — no mechanical signal (token overlap, co-occurrence, or explicit cross-reference) connected it to any other label in this batch's normalization pass.
- Rows for this label were captured under more than one scope tag (['OBJECT', 'THEORY-LEVEL']) — this may reflect genuine cross-scope relevance (e.g. an OBJECT used at THEORY-LEVEL) rather than a labeling error, but P3 may want to confirm.
