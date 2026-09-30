# proposition-expressiveness-gap

**Scope(s):** OBJECT · **Row count:** 4 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `P=(Entity,Dimension,Value)`
**Aliases:** "EXP-19"
**Candidate group membership (NOT an identity claim):**
- G1705: co-occurs with `determination-as-mathematical-object` — labels co-occur in the same contribution's labels[] 2 separate times across the corpus

## Sources (how this label entered the ledger)

- OBJECT-INDEX, batch B0041, scope OBJECT: "An executed classification of ten corpus-required example propositions against the terminal type P=(Entity,Dimension,Value): finds negation, conditional determination, and quantification INEXPRESSIBLE; validity-interval and units LOSSY; assertion-level contradiction NOT-A-PROPOSITION (expressed as an R-edge instead, with no stated relationship between the two content mechanisms); and epistemic-Unknown AMBIGUOUS between a value-space entry and assertion absence."

## Candidate births

- CANDIDATE-LEXICAL-BIRTH: [S1700 §"Of 10 propositions the corpus itself requires, {n_ok} are cleanly expressible. THREE are inexpressible (negation, conditional, quantification), TWO are lossy (validity interval, unit), ONE is not a proposition at all in this type system, and ONE is ambiguous between a value and an epistemic marker."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle

last_seen: S1701. Candidate lifecycle: DORMANT.
Evidence: `lifecycle_evidence` is empty (retracted_by: [], superseded_by: [], contested_by_own_contradiction_type: false). DORMANT is a recency heuristic based on last use (S1701), not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up

| Dimension | Status | source_ids |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | NOT-EVIDENCED-IN-CAPTURE | — |
| type_signature | PRESENT | S1700 |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | PRESENT | S1700 (×2), S1701 (×2) |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | NOT-EVIDENCED-IN-CAPTURE | — |
| examples | PRESENT | S1701 |
| warnings | PRESENT | S1701 |
| experiments | PRESENT | S1700 (×2), S1701 |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale

NOT-EVIDENCED-IN-CAPTURE — no row in this label's family is typed ARGUMENT/ANALYSIS/EXPLANATION/ALTERNATIVE.

## Assumption register

NOT-EVIDENCED-IN-CAPTURE.

## All rows (source_id order)

- [S1700] types=[EXPERIMENTAL-RESULT, LIMITATION] scope=OBJECT — "Classifies ten corpus-sourced natural-language propositions against P=(Entity,Dimension,Value): 'Nexus.version=3.69' EXPRESSIBLE; 'Nexus.version=3.69 in production context' EXPRESSIBLE-VIA-A but shown to make context-varying propositions distinct assertions rather than distinct propositions; 'Alice is Board chair from date to date' LOSSY (drops which board and the validity interval, since A's single t is assertion-time not valid-time, per Step 185's T_valid != T_known distinction); 'a1 contradicts a2' NOT-A-PROPOSITION (expressed via an R-edge, leaving two content mechanisms P and R with no stated relationship); 'NOT Nexus.version=3.69' INEXPRESSIBLE (no negation, would require negative values in V_D which Step 264 does not provide); 'IF v>=3.60 THEN patch-current' INEXPRESSIBLE (no conditional -- the exact conditional-determination problem the corpus opened with on 2026-08-25); 'EVERY committee has >=3 members' INEXPRESSIBLE (no quantification/arithmetic over a population); 'Nexus is ready to be migrated' EXPRESSIBLE-BUT-EMPTY (holds the conclusion but not the deriving rule, which lives in the class-C, no-decision-procedure Policy); 'response time = 250ms' LOSSY (drops the unit, Step 264.14's own 'another layer' that is not placed in V); 'Nexus.version is UNKNOWN' AMBIGUOUS (either an epistemic marker inside the value space -- a category error Step 264 warns against -- or indistinguishable assertion-absence)." (anchor: "Of 10 propositions the corpus itself requires, {n_ok} are cleanly expressible. THREE are inexpressible (negation, conditional, quantification), TWO are lossy (validity interval, unit), ONE is not a proposition at all in this type system, and ONE is ambiguous between a value and an epistemic marker.")
- [S1700] types=[EXPERIMENTAL-RESULT, CORRECTION, LIMITATION] scope=THEORY-LEVEL — also labeled `determination-as-mathematical-object` — "EXP-20 checks the terminal type P=(Entity,Dimension,Value)/K=(A,R) (closed 2026-08-30) against the corpus's own founding research question, opened 2026-08-25/26 as 'what substrate must be preserved so multiple determination regimes can independently reconstruct the same phenomenon' and 'Determination is the missing mathematical object'; finds the conditional determination example remains INEXPRESSIBLE (per EXP-19), its deriving rule lives in Policy (class-C, no decision procedure per Step 266 s266.22/s266.23), and a grep for 'Determination' across Steps 258-270's K or A definitions returns nothing -- Determination as a named object is entirely absent from the terminal model six days after being identified as the founding gap; concludes the theory closed around the part of the problem it could formalize." (anchor: "The object the corpus named as MISSING on day 1 -- Determination -- is still missing on day 6, and the terminal proposition type cannot express the conditional structure that Determination was introduced to carry. The theory closed around the part of the problem it could formalize.") — lineage claim: SOURCE-CLAIMED-CONTINUATION of the corpus's 2026-08-25/26 opening files (235804, 235855, 000209, 000339). — lineage claim: SOURCE-CLAIMED-CONTRADICTION of the terminal theory's implicit claim of adequacy.
- [S1701] types=[EXPERIMENTAL-RESULT, CORRECTION, WARNING] scope=THEORY-LEVEL — also labeled `determination-as-mathematical-object` — "Finding CS-4 (called the single most consequential finding in the document): the corpus opened (2026-08-25 23:57-00:03) on the conditional determination problem ('What substrate must be preserved so multiple determination regimes can independently reconstruct the same phenomenon?', and 'Determination is the missing mathematical object'), but closed on P=(E,D,V) and K=(A,R); a grep across the six terminal steps (262-267) finds zero occurrences of 'Determination'; the conditional example IF version>=3.60 THEN patch-current remains inexpressible, and its deriving rule lives in class-C (no decision procedure) Policy; explicitly warns that no terminal step records that the founding question was silently dropped, so a reader of only the terminal artifacts cannot detect the abandonment." (anchor: "The object the corpus named as the missing mathematical object on day 1 is absent from every terminal artefact on day 6, and the terminal proposition type cannot express the conditional structure it was introduced to carry. This is the single most consequential finding in this document. ... the theory closed around the part of the problem it could formalize, and the reader of the terminal steps cannot tell, because no terminal step records that the founding question was dropped.") — lineage claim: SOURCE-CLAIMED-CONTINUATION of 2026-08-25 opening files.
- [S1701] types=[COUNTEREXAMPLE] scope=OBJECT — label_confidence UNCERTAIN — "A third worked counterexample: a real, currently-enforced EKP rule ('assertions from authority:generated may not be authoritative without human review', per the Knowledge-Constitution's AI-collaboration principles) is a proposition about assertions carrying a quantifier and a modal, and is inexpressible in the terminal proposition type -- alongside two other counterexamples: identity underdetermination (A-in-K disagreeing on 2 of 3 probes across four equalities) and an unbacked contradicts-relation that cannot itself carry evidence/provenance/status." (anchor: ""Assertions from authority: generated may not be authoritative without human review" is a real, running EKP rule ... It is a proposition about assertions with a quantifier and a modal. Inexpressible (CS-4).")

## Notes for P3

- This is my own observation: this label's own rows carry a lineage claim of kind SOURCE-CLAIMED-CONTRADICTION, yet the mechanical CONTESTED flag did not fire — worth a manual look per the known false-negative limitation of that heuristic.
