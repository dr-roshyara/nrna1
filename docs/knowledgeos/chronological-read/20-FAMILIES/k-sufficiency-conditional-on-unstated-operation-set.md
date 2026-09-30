# k-sufficiency-conditional-on-unstated-operation-set

**Scope(s):** OBJECT · **Row count:** 3 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** K=(A,R) sufficient iff O excludes history-sensitive predicates, ever_contested · **Aliases:** KG-4, sharpest form of EV-E1
**Candidate group membership (NOT an identity claim):**
- **G0413** [`k-sufficiency-conditional-on-unstated-operation-set` · `theory-evolution-map-independent-reconstruction`] — explicit agent-stated uncertainty: 'k-sufficiency-conditional-on-unstated-operation-set' POSSIBLY relates to 'theory-evolution-map-independent-reconstruction' (batch B0041). Note: Executed demonstration (exp_congruence.py EXP-3) that two histories with different contestation trajectories (H_calm vs H_stormy) reach a byte-identical K=(A,R), so K is sufficient only if the mandatory operation set excludes history-sensitive predicates like ever_contested (an ordinary governance predicate the corpus's own Steps 155/179/181 discuss); concludes the widely-repeated claim 'K=(A,R) is the minimal sufficient knowledge state' is not a corpus theorem but a consequence of an unstated choice of O.

## Sources (how this label entered the ledger)
- **PROPOSAL**, batch B0041, scope OBJECT (relation_to_existing: POSSIBLY:theory-evolution-map-independent-reconstruction): Executed demonstration (exp_congruence.py EXP-3) that two histories with different contestation trajectories (H_calm vs H_stormy) reach a byte-identical K=(A,R), so K is sufficient only if the mandatory operation set excludes history-sensitive predicates like ever_contested (an ordinary governance predicate the corpus's own Steps 155/179/181 discuss); concludes the widely-repeated claim 'K=(A,R) is the minimal sufficient knowledge state' is not a corpus theorem but a consequence of an unstated choice of O.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1693 §"K=(A,R) is sufficient IFF the mandatory operation set excludes every history-sensitive predicate. ever_contested is not an exotic operation ... 'K=(A,R) is the minimal sufficient knowledge state' is not a theorem of the corpus. It is a consequence of an unstated choice of O, and a different, equally corpus-consistent O refutes it. This is the sharpest form of EV-E1."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S1714. Candidate lifecycle: DORMANT.
Evidence: no retraction/supersession/contradiction evidence recorded — the DORMANT classification is a heuristic based on how recently (by source_id) this label was last used, not a confirmed ongoing status or a confirmed retirement.

## Completeness roll-up
| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | PRESENT | S1714 |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | NOT-EVIDENCED-IN-CAPTURE | — |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | PRESENT | S1693, S1706, S1714 |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | NOT-EVIDENCED-IN-CAPTURE | — |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | PRESENT | S1693 |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
- [S1714] (ANALYSIS/CORRECTION) Finding FR-3 (one of 'the three refutations that matter most'): reframes the K=(A,R)-minimality refutation as the claim being underdetermined rather than false -- it is true relative to an unstated premise (a fixed operation set O excluding history-sensitive predicates like ever_contested), which is characterized as a different and more repairable kind of failure than an outright false claim.

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows
- [S1693] types=['EXPERIMENTAL-RESULT', 'CORRECTION'] scope=THEORY-LEVEL — "Finding KG-4 (EXP-3): constructs two histories (H_calm: a single assert; H_stormy: assert, contradicting assert, relate as contradicts, withdraw) that reach a byte-identical K=(A,R) yet differ on ever_contested(H) (False vs True); since ever_contested is an ordinary governance predicate discussed in the corpus's own Steps 155/179/181, this refutes 'K=(A,R) is minimal and sufficient' as a corpus theorem, showing it holds only conditionally on excluding history-sensitive predicates from the mandatory operation set -- described as the sharpest form of finding EV-E1 (from the prior batch's independent theory-evolution reconstruction)." (anchor: "K=(A,R) is sufficient IFF the mandatory operation set excludes every history-sensitive predicate. ever_contested is not an exotic operation ... 'K=(A,R) is the minimal sufficient knowledge state' is not a theorem of the corpus. It is a consequence of an unstated choice of O, and a different, equally corpus-consistent O refutes it. This is the sharpest form of EV-E1.")
- [S1706] types=['LIMITATION', 'CORRECTION'] scope=THEORY-LEVEL — "Finding TG-5: the executable kernel's good properties (TG-4) hold only relative to the specific four-operation set chosen for this session; every corpus property quantifying over the operation set (sufficiency Step 259, congruence Step 260.9, minimality Steps 260/266.19, state equality) is underdetermined until the set is fixed, and exp_congruence.py EXP-3 shows the sufficiency answer actually flips once an ordinary governance-plausible history-sensitive operation (ever_contested) is added, even though it does not appear in the chosen minimal or full operation sets tested." (anchor: "The transformation is well-behaved RELATIVE TO a four-operation set this session chose. Every corpus property that quantifies over T ... is UNDERDETERMINED until O is enumerated ... Add one history-sensitive operation ... and K=(A,R) stops being sufficient.")
- [S1714] types=['ANALYSIS', 'CORRECTION'] scope=THEORY-LEVEL — "Finding FR-3 (one of 'the three refutations that matter most'): reframes the K=(A,R)-minimality refutation as the claim being underdetermined rather than false -- it is true relative to an unstated premise (a fixed operation set O excluding history-sensitive predicates like ever_contested), which is characterized as a different and more repairable kind of failure than an outright false claim." (anchor: "F-1 -- The minimality claim is conditional, not false ... Whether K=(A,R) suffices depends entirely on whether O contains such a predicate, and O has never been written down. The corpus's terminal claim is TRUE RELATIVE TO A PREMISE IT NEVER STATES. That is a different and more repairable failure than being wrong.")

## Notes for P3
None beyond what is recorded above.
