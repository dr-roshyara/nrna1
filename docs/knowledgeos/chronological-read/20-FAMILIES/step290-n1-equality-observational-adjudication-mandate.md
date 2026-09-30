# step290-n1-equality-observational-adjudication-mandate

**Scope(s):** METHODOLOGICAL · **Row count:** 9 ·
**Lifecycle (candidate):** ACTIVE · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `N-1A..N-1E`, `Q1-Q8` · **Aliases:** `REFINED STEP 290 mandate`, `Step 290 N-1 mandate`
**Candidate group membership (NOT an identity claim):**
- **G0811**: [`kr-zoom-out-01-design-rival-meanings` · `step290-n1-equality-observational-adjudication-mandate`] — labels share the notation 'Q1-Q8'

## Sources (how this label entered the ledger)
- OBJECT-INDEX, batch B0052, scope METHODOLOGICAL: "Reviewer B's deliberately narrow Step-290 research mandate, scoped to exactly one question (decision N-1: is the corpus's equiv/semantic-equality distinct from approx/observational-equivalence, and does the corpus itself decide this or must Governance?). Specifies eight audit questions (Q1-Q8), a required actual-witness distinguishability test (forbidding invented hypotheticals), nine required negative checks, and a mandatory five-way exhaustive classification (N-1A Distinct / N-1B Same / N-1C Derivable Collapse / N-1D Governance-Choice / N-1E Corpus-Contradiction, with 'OPEN' forbidden as a final answer), plus a hard stop immediately after N-1 is classified."

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S2163 §"**Step 289 looks ready as a research result, but its N-1 conclusion must not silently become a governance recommendation.** The next step should therefore be a tightly bounded **N-1 adjudication audit**, not another general equality investigation. ... **Are `≡` and `≈` actually two distinct corpus r…"]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: [S2163 §"**Step 289 looks ready as a research result, but its N-1 conclusion must not silently become a governance recommendation.** The next step should therefore be a tightly bounded **N-1 adjudication audit**, not another general equality investigation. ... **Are `≡` and `≈` actually two distinct corpus r…"]

## Lifecycle
last_seen: S2202. Candidate lifecycle: ACTIVE. Evidence: retracted_by and superseded_by are both empty and no own-row contradiction trigger fired; the ACTIVE classification is a heuristic based on how recently (by source_id order) this label was last used, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | source_ids |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S2163 |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | PRESENT | S2163 |
| dependencies | PRESENT | S2163 |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | NOT-EVIDENCED-IN-CAPTURE | — |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | PRESENT | S2174, S2202 |
| open_questions | PRESENT | S2163 |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S2163] types=[GOVERNANCE, CONSTRAINT] scope=METHODOLOGICAL — "Deliberately narrows the scope of the next research step (290) to exactly one question -- N-1 adjudication -- rather than a general re-investigation of equality, to avoid reopening the whole Steps 287-289 arc: the central question is whether the corpus itself already distinguishes semantic equality (equiv) and observational equivalence (approx), or whether that distinction requires Governance to declare it, framed explicitly as a corpus-integrity question first and only secondarily a governance question. Explicit non-goals: do not solve equality, do not select an observational relation, do not repair identity, do not derive O or T." (anchor: "**Step 289 looks ready as a research result, but its N-1 conclusion must not silently become a governance recommendation.** The next step should therefore be a tightly bounded **N-1 adjudication audit…")
- [S2163] types=[CONSTRAINT, OPEN-QUESTION] scope=METHODOLOGICAL — "Specifies eight precise audit questions (Q1-Q8) for the N-1 adjudication: whether the corpus explicitly states equiv and approx are distinct (Q1); whether it states approx is a special case/projection/quotient/refinement/implementation-of/synonym-for equiv (Q2); whether the two relations differ in domain, codomain, parameters, observables, decision procedures, congruence requirements, invariants, intended uses, or consequences (Q3); whether any such difference is semantic, operational, observational, provenance-related, or merely terminological (Q4); whether any artifact uses equiv_K and approx interchangeably (Q5); whether two registers define apparently identical relations under different symbols (Q6); whether the corpus contains enough evidence to establish a distinction without new normative assumptions (Q7); and, if not, precisely what Governance would have to declare (Q8)." (anchor: "### Q1 Does the corpus explicitly state that `≡` and `≈` are distinct? ### Q2 ... ### Q7 Does the corpus contain enough evidence to establish a distinction without introducing new normative assumption…")
- [S2163] types=[CONSTRAINT] scope=METHODOLOGICAL — "Requires an actual distinguishability test (not a symbol-counting inference): construct, using only corpus-defined states/observations/operations/projections or already-formally-derived consequences, a witness pair K1,K2 such that K1≡K2 but not K1≈K2 (or vice versa); explicitly forbids inventing hypothetical examples merely to force the relations to differ, requiring instead the honest report 'UNDECIDABLE FROM CURRENT CORPUS' if the required corpus semantics are unavailable to construct such a witness." (anchor: "Do not conclude "distinct" merely because different symbols occur. Construct the strongest possible equivalence test available from the corpus. Ask: Can there exist `K₁,K₂` such that `K₁ ≡ K₂` but not…")
- [S2163] types=[GOVERNANCE, DEFINITION] scope=METHODOLOGICAL — "Defines a mandatory five-way exhaustive classification for the N-1 adjudication's final answer, explicitly forbidding 'OPEN' as a terminal verdict: N-1A DISTINCT (the corpus establishes equiv and approx are different relations); N-1B SAME (the corpus establishes they are the same relation); N-1C DERIVABLE COLLAPSE (they formally collapse under an already-established corpus rule); N-1D GOVERNANCE-CHOICE (the corpus establishes no unique answer, so distinction/collapse requires normative authority); N-1E CORPUS-CONTRADICTION (the corpus contains irreconcilable definitions that cannot be reduced to a governance choice without first repairing the corpus). States the purpose of N-1 is specifically to classify WHY the question remains unresolved, if it does, rather than leaving it as an undifferentiated open item." (anchor: "### `N-1A — DISTINCT` ... ### `N-1B — SAME` ... ### `N-1C — DERIVABLE COLLAPSE` ... ### `N-1D — GOVERNANCE-CHOICE` ... ### `N-1E — CORPUS-CONTRADICTION` ... Do not use `OPEN` as the final answer. The …")
- [S2163] types=[CONSTRAINT] scope=METHODOLOGICAL — "Specifies nine required negative checks that must be explicitly tested and recorded rather than assumed: neither 'equiv=approx' (from shared 'equivalence' terminology) nor 'equiv!=approx' (from different symbols) may be assumed; approx_X must not be assumed to be either the canonical corpus approx or behavioural equivalence; cong_lambda must not be assumed equivalent to either equiv or approx; Sigma's existence must not be treated as proof of observational equivalence; delta-congruence dependencies (the very cycles found in Step 289) must not be treated as proof of semantic equality; and philosophical sources and governance preference must not be used as evidence." (anchor: "Explicitly test and record: `≡ = ≈` is NOT assumed because both are called "equivalence". `≡ ≠ ≈` is NOT assumed because different symbols are used. `≈_X` is NOT assumed to be the canonical `≈`. ... t…")
- [S2163] types=[CONSTRAINT, GOVERNANCE] scope=METHODOLOGICAL — "Imposes a hard stop condition: research must halt immediately once N-1 is classified, explicitly forbidding continuation into operation-registry derivation, O_core, transformation semantics, delta, identity repair, an equality decision procedure, invariant enumeration, kernel selection, or a governance recommendation -- if N-1 resolves to GOVERNANCE-CHOICE, the legitimate next act is an actual governance decision, not a further research loop attempting to manufacture a mathematical answer where none exists. Closing discipline: research may establish what the corpus entails; it may not supply the missing normative choice -- no preference, no repair by intuition, no semantic invention, no architecture promotion." (anchor: "STOP immediately after N-1 is classified. Do not continue into: operation registry derivation; `𝒪_core`; transformation semantics; `δ`; identity repair; equality decision procedure; invariant enumerat…")
- [S2174] types=[EXPERIMENTAL-RESULT] scope=OBJECT — "The executed audit script found a 20-entry relation register of which only 5 carry an actual definition (15 merely name a relation), confirmed both proposed equiv_K definitional loci are labelled candidate/proposal (not assertions), and reported the distinguishability test as failing loudly (by design) due to two absent required definitions, alongside 9 passed negative checks." (anchor: "20-entry register; 5 defined / 15 named; the same formula under 2 symbols; both `≡_K` loci CANDIDATE/PROPOSAL; distinguishability FAILS LOUDLY on two absent definitions; 9 negative checks")
- [S2174] types=[CONSTRAINT] scope=METHODOLOGICAL — "Explicit negative evidentiary constraint: the N-1 verdict is derived using zero philosophical, external-literature, governance-preference, or hypothetical-construction inputs — every negative result and every established finding traces only to the primary technical corpus and executed code." (anchor: "No philosophical source — no Gītā material, no Cavell, no Chalmers, no process algebra imported as architecture. ... No governance preference. ... No hypothetical state pair")
- [S2202] types=[EXPERIMENTAL-RESULT] scope=OBJECT — "Reports the executed distinguishability test's designed fail-loud behavior: constructing a witness pair demonstrating equiv and approx differ (or agree) requires either an independent decision procedure for equiv or a closed observation set O_K, and both are absent, so the test correctly reports UNDECIDABLE FROM CURRENT CORPUS rather than fabricating a hypothetical witness." (anchor: "FAIL LOUDLY: required definition ABSENT -> a decision procedure for == (semantic) ... FAIL LOUDLY: required definition ABSENT -> a closed observation set O_K")

## Notes for P3
- family.files_touching lists source_id(s) ['S2164'] that do not appear among this label's own family.rows — a data-completeness oddity for P3 to check against 03-CONTRIBUTIONS.jsonl.
