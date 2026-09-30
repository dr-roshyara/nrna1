# basic-action-theory-structure

**Scope(s):** OBJECT · **Row count:** 7 · **Lifecycle (candidate):** ACTIVE · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `D = Sigma ∪ D_ss ∪ D_ap ∪ D_una ∪ D_S0` · **Aliases:** `Reiter Basic Action Theory`
**Candidate group membership (NOT an identity claim):**
- **G1828** [`basic-action-theory-structure` · `step291-closure-vs-membership-taxonomy`] — labels co-occur in the same contribution's labels[] 2 separate times across the corpus

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** (batch B0061, scope OBJECT): Full formal decomposition of a situation-calculus domain theory into foundational axioms Sigma, successor-state axioms D_ss, action-precondition axioms D_ap, unique-names axioms D_una, and an initial database D_S0 (sentences uniform in S0, i.e. not mentioning Poss/sqsubset or the future); Relative Satisfiability Theorem: D is satisfiable iff D_una ∪ D_S0 is.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S2536] §"D = Sigma \cup D_{ss} \cup D_{ap} \cup D_{una} \cup D_{S_0} ... A formula is uniform in sigma iff it does not mention the predicates Poss or sqsubset, it does not quantify over variables of sort situation ... A basic action theory D is satisfiable iff D_{una} \cup D_{S_0} is."
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S2536] §"D = Sigma \cup D_{ss} \cup D_{ap} \cup D_{una} \cup D_{S_0} ... A formula is uniform in sigma iff it does not mention the predicates Poss or sqsubset, it does not quantify over variables of sort situation ... A basic action theory D is satisfiable iff D_{una} \cup D_{S_0} is."
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S2563. Candidate lifecycle: ACTIVE.
Evidence: none recorded (no retraction/supersession/contradiction signal) — this lifecycle label is a heuristic based on how recently (by source_id) this label was last used, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source ids |
|---|---|---|
| Purpose / rationale | PRESENT | S2562 |
| Informal meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| Formal definition | PRESENT | S2536 |
| Type signature | PRESENT | S2562, S2563 |
| Invariants | PRESENT | S2562 |
| Dependencies | PRESENT | S2562, S2563 |
| Assumptions | PRESENT | S2542 |
| Semantics | PRESENT | S2542, S2563 |
| Examples | NOT-EVIDENCED-IN-CAPTURE | — |
| Warnings | PRESENT | S2542, S2562 |
| Experiments | PRESENT | S2562, S2563 |
| Open questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
[EXT] A Reiter basic action theory is exactly a registry structure: per-action precondition axiom (Poss) and effect axioms (gamma+/gamma-), plus unique-names axioms and S0. Axiom A4 confirms Poss functions as a gate -- a non-executable history is rejected at its first violating prefix, not repaired after the fact [S2562].

## Assumption register
| Statement | Stated | Source id | Anchor |
|---|---|---|---|
| causal completeness: effect axioms enumerate all ways a fluent can change | EXPLICIT | S2542 | This is an assumption about the axiomatiser's knowledge, not a theorem. |

## All rows (source_id order)
- [S2536] types=[FORMALIZATION, VALIDATION] scope=OBJECT — "Formalizes the complete Basic Action Theory D = Sigma ∪ D_ss ∪ D_ap ∪ D_una ∪ D_S0 (foundational axioms, successor-state axioms, action-precondition axioms, unique-names axioms, initial database), defines uniform-in-sigma formulas as 'current state' (non-future-referencing) formulas, and states the Relative Satisfiability Theorem (D satisfiable iff D_una ∪ D_S0 is) -- i.e. adding foundational/precondition/successor-state axioms cannot itself introduce unsatisfiability." (anchor: "D = Sigma \cup D_{ss} \cup D_{ap} \cup D_{una} \cup D_{S_0} ... A formula is uniform in sigma iff it does not mention the predicates Poss or sqsubset, it does not quantify over variables of sort situation ... A basic action theory D is satisfiable iff D_{una} \cup D_{S_0} is.")
- [S2542] types=[RESTATEMENT, WARNING] scope=OBJECT — "Restates Reiter's formal ontology and axioms (already captured in S2536) under an explicit [EXT]/'extracted not endorsed' framing, emphasizing two carried-forward limitations for any KnowledgeOS transfer: the induction axiom is second-order (not fully first-order decidable), and the successor-state axiom rests on an unproven causal-completeness ASSUMPTION about the axiomatiser's own knowledge, not a theorem." (anchor: "[EXT] throughout. Extracted, not endorsed. ... The induction axiom is second-order. The extraction states the consequence plainly: the system is not fully first-order decidable. Any transfer inherits this. ... It rests on the causal completeness assumption ... This is an assumption about the axiomatiser's knowledge, not a theorem.")
- [S2562] types=[ARGUMENT, VALIDATION] scope=CROSS-OBJECT — "[EXT] A Reiter basic action theory is exactly a registry structure: per-action precondition axiom (Poss) and effect axioms (gamma+/gamma-), plus unique-names axioms and S0. Axiom A4 confirms Poss functions as a gate -- a non-executable history is rejected at its first violating prefix, not repaired after the fact." (anchor: "[EXT] A basic action theory is exactly a registry: for each action, a precondition axiom (Poss) and effect axioms (gamma+, gamma-), plus unique names and S0. A4 verifies that Poss functions as a gate: a non-executable history is rejected at its first violating prefix, not repaired.")
- [S2562] types=[EXPERIMENTAL-RESULT] scope=CROSS-OBJECT — "[EXP] The registry-entry shape (name, precondition, positive effects, negative effects) is judged the most directly transferable structure from Reiter's source into the KnowledgeOS operation-registry candidate architecture." (anchor: "[EXP] The shape (name, precondition, positive effects, negative effects) is formally supported. This is the most directly transferable structure in the source.")
- [S2562] types=[WARNING, LIMITATION] scope=CROSS-OBJECT — "[NEG] Explicit guard: Reiter supplies only the SHAPE of a registry entry, not its MEMBERSHIP, signatures, or bodies (Step 291's open gaps); a shape without members is not a registry, and Reiter's formal support for the shape must not be cited as closing Step 291's membership/signature/body gaps." (anchor: "Reiter supplies the SHAPE of a registry entry. Step 291 established that KnowledgeOS does not yet know its MEMBERSHIP, its signatures, or its bodies. A shape without members is not a registry. [NEG] Reiter does not close step 291's gaps, and must not be cited as closing them.")
- [S2563] types=[EXPERIMENTAL-RESULT, LIMITATION] scope=CROSS-OBJECT — "IC-2: regression (R[phi] unwinding the successor-state axioms to S0) is proposed as a verification mechanism, motivated by the corpus finding (KR-HISTORY) that history is written and never read -- regression is what would make it readable for audit. Terminates on regressable queries, deterministic, and A3 confirms regression agrees with progression 12/12. But KnowledgeOS has no basic action theory, so the regression theorem's formal guarantee is unavailable -- only the mechanism's shape transfers. Status: SUPPORTED as the most transferable item, yet still not adoptable." (anchor: "IC-2 Regression as a verification mechanism | Corpus basis: KR-HISTORY: history is written and never read -- regression is what would make it readable for audit ... Current evidence: A3: agrees with progression 12/12 ... Missing evidence: KnowledgeOS has no basic action theory, so the theorem's guarantee is unavailable -- only the mechanism's shape ... Maturity/Status: SUPPORTED -- the most transferable item, and still not adoptable")
- [S2563] types=[RESTATEMENT, LIMITATION] scope=CROSS-OBJECT — "IC-3: the operation registry entry shape (name, Poss, gamma+, gamma-) is RESEARCH ONLY status -- Reiter supplies the shape but does not close Step 291's finding that membership, signatures (8/22), bodies (0/22), identity (0/22) and ratification (0/22) are all OPEN; a shape without membership is not a registry." (anchor: "IC-3 Operation registry entry shape | Corpus basis: step 291: membership, signatures (8/22), bodies (0/22), identity (0/22), ratification (0/22) -- ALL OPEN ... Missing evidence: the members. A shape without membership is not a registry ... Maturity/Status: RESEARCH ONLY -- Reiter does not close step 291")

## Notes for P3
None — this label's evidence is internally consistent within the rows captured for this batch.
