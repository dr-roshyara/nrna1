# step280-281-harness-id-collision-defect

**Scope(s):** OBJECT · **Row count:** 10 ·
**Lifecycle (candidate):** CONTESTED · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `C-NEW`; `canonical_id`; `kosfix.py`
**Aliases:** "evidence-hash id collision"; "harness id-collision defect"
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)

- OBJECT-INDEX, batch B0046, scope OBJECT: "A computational (not theoretical) defect discovered incidentally during F21 execution: the Step 280/281 reference-implementation harness (kosmodel.py) computed an Assertion's id by hashing only the Evidence.ref field, so two assertions with identical proposition and evidence references but opposite polarity (supports vs contradicts) received the SAME id, producing a spurious StructuralValid failure ('id collision'). Root cause: the canonical Assertion definition hashes the full Evidence tuple (in which polarity is a field), not just ref. Fixed by a patch module kosfix.py providing a canonical_id() that hashes the full (ref,polarity,state) tuple; all of Step 281's results are re-verified under the corrected id and survive unchanged (6/6 distinguishability, M7 orthogonal, StructuralValid ok)."

## Candidate births

- CANDIDATE-LEXICAL-BIRTH: [S1898 §"Canonical id: hash the full Evidence tuple, not just its ref. Fixes the C-defect found in F21."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: [S1898 §"Canonical id: hash the full Evidence tuple, not just its ref. Fixes the C-defect found in F21."]
- CANDIDATE-GOVERNANCE-BIRTH: [S1924 §"## Anti-bias declaration\nTwo of my own prior claims were **overturned** in this step: non-identifiability was **not** inexpressible,\nand my harness **did** carry an identity defect. **A verdict of "closed" was reached only after the two\nfindings that made this session look worse were recorded first.**"]

## Lifecycle

last_seen: S1948. Candidate lifecycle: CONTESTED.
Evidence: contested_by_own_contradiction_type: true.

## Completeness roll-up

| Dimension | Status | source_ids |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | NOT-EVIDENCED-IN-CAPTURE | — |
| type_signature | PRESENT | S1898, S1938 |
| invariants | PRESENT | S1938 |
| dependencies | PRESENT | S1921, S1936, S1938, S1948 |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S1924 |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | PRESENT | S1948 |
| experiments | PRESENT | S1907, S1911, S1921, S1938 |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale

NOT-EVIDENCED-IN-CAPTURE — no row in this label's family is typed ARGUMENT/ANALYSIS/EXPLANATION/ALTERNATIVE.

## Assumption register

NOT-EVIDENCED-IN-CAPTURE.

## All rows (source_id order)

- [S1898] types=[IMPLEMENTATION, CORRECTION] scope=OBJECT — "Implements the canonical Assertion identity function as a SHA-256 hash (first 10 hex chars) over the proposition's entity/dimension/value plus a sorted tuple of each evidence item's (ref, polarity, state) plus context, time-validity-from, and provenance -- correcting the reference implementation's prior id function (which hashed only evidence.ref) and eliminating the id collision between same-reference, opposite-polarity assertions. Applied via monkey-patch: Assertion.id=property(canonical_id)." (anchor: "Canonical id: hash the full Evidence tuple, not just its ref. Fixes the C-defect found in F21.")
- [S1907] types=[VALIDATION, EXPERIMENTAL-RESULT] scope=OBJECT — also labeled `step281-missingness-repair-inquiry-register-selection` — "Re-verifies Step 281's core distinguishability and structural-validity claims under the corrected (post-kosfix) id function rather than trusting the original PASS labels: all six M1-M6 tuples remain distinct, M7 orphan-orthogonality still holds, StructuralValid now returns (True,'ok') with the corrected id (previously the harness defect could produce a spurious failure), and the original F21 id-collision case re-run under the corrected id now shows distinct ids and Valid=(True,'ok'). Conclusion: Step 281's results survive the id correction; 7/7 distinguishability reconfirmed." (anchor: "M1-M6 under corrected id: [('NotAsked', '-'), ('Asked', 'Absent'), ('Asked', 'Unknown'), ('Asked', 'Supported'), ('Asked', 'Refuted'), ('Asked', 'Conflicted')] ... => STEP 281's RESULTS SURVIVE the id correction.")
- [S1911] types=[EXPERIMENTAL-RESULT, EXTENSION] scope=OBJECT — "New gap C-NEW discovered incidentally during F21 execution: two assertions with the same proposition and same evidence reference but opposite polarity (supports vs contradicts) receive the SAME id under the harness's actual id computation (hashes only x.ref), producing a StructuralValid failure ('id collision'); the canonical Assertion definition hashes the full Evidence tuple (in which polarity is a field), under which the two ids are distinct. Classified TC-3 PASS for the theory, TC-3 FAIL for the harness -- a computational defect, not theory-critical; Step 281's results are re-verified under the corrected id and all survive." (anchor: "C-NEW — evidence-ref hashing collision in the step-280/281 harness") — lineage claim: SOURCE-CLAIMED-EXTENSION of kosmodel.py reference implementation used by Step 280/281.
- [S1921] types=[EXPERIMENTAL-RESULT] scope=OBJECT — "Executing F21 incidentally surfaced a harness defect: the Assertion id-hash omitted evidence polarity, so two semantically opposite assertions (identical proposition/evidence-ref, opposite polarity) received the same id; this is classified as a computational/implementation defect (C), not a theory defect." (anchor: "| **F21 (incidentally)** | **id collision in the harness** — polarity omitted from the hashed key; two semantically opposite assertions shared an id | **C — implementation, not theory** |")
- [S1921] types=[VALIDATION] scope=OBJECT — completeness PARTIAL (missing: explanation of why M7 orthogonal flips from True to False and whether that flip itself matters) — "After the id-collision fix, Step 281's results were re-verified: M1-M6 all distinct remains True, M7 orthogonal flips from True to False under re-check, StructuralValid remains (True,'ok'), and the id-collision case now produces differing ids; overall conclusion is that Step 281's results survive the correction." (anchor: "M1-M6 all distinct: True          M7 orthogonal: True -> False\nStructuralValid: (True,'ok')      collision case re-run: ids differ\n**Step 281's results survive the correction.**")
- [S1924] types=[GOVERNANCE, RESTATEMENT] scope=METHODOLOGICAL — also labeled `step282-theory-criticality-and-closure-decision` — "The author records an explicit anti-bias declaration: the closure verdict was only asserted after two self-critical findings (non-identifiability was previously mischaracterized; the author's own harness contained an id-collision defect) were recorded, as a discipline against motivated reasoning toward a 'closed' conclusion." (anchor: "## Anti-bias declaration\nTwo of my own prior claims were **overturned** in this step: non-identifiability was **not** inexpressible,\nand my harness **did** carry an identity defect. **A verdict of "closed" was reached only after the two\nfindings that made this session look worse were recorded first.**")
- [S1936] types=[VALIDATION] scope=OBJECT — "C-NEW (the harness id-collision defect) is classified C (Computational), not theory-critical, and CLOSED: the fix was applied in all 3 harnesses (step-280, step-281, step-282 exec copies), the full suite was re-run, and no regression resulted, per document 13 (13-C-NEW-CLOSURE-RECORD)." (anchor: "| **C-NEW** harness id collision | **C** | — | **NO** | `[E]` F21 + regression re-run | **CLOSED** — fixed in all 3 harnesses, suite re-run, no regression (see `13`) |")
- [S1938] types=[CORRECTION, EXPERIMENTAL-RESULT] scope=OBJECT — "The original defective Assertion.id formula H(P, {evidence.ref}, c, t, Pi) omits polarity and state, so a supports-polarity and contradicts-polarity assertion sharing proposition and evidence reference (ref=33989a8e) both hash to id=c7b41c155d, a concrete demonstrated collision that makes StructuralValid report (False, 'id collision'); the collision is reachable in practice because Qualify(obs, Policy) assigns polarity FROM the policy, so the same observation evaluated under two different policies yields identical ref but opposite polarity and therefore identical id under the defective formula." (anchor: "`kosmodel.py` computed assertion identity as\n`H(P, {x.ref for x in e}, c, t, Π)` — hashing only the evidence **reference**, omitting `polarity` and\n`state`. Two assertions with the same proposition and same evidence references but **opposite polarity**\ntherefore received the **same id** while being semantically opposite.\n\n```\nsupports     ref=33989a8e  ->  id=c7b41c155d\ncontradicts  ref=33989a8e  ->  id=c7b41c155d      COLLISION\nStructuralValid -> (False,'id collision')\n```")
- [S1938] types=[VALIDATION] scope=OBJECT — "Full regression suite re-run after the fix, across all three step-280/281/282 exec harness copies: Step 280 E1-E24 (22 PASS/1 FAIL(E4)/1 BLOCKED(E20), unchanged), Step 280 F1-F13+F4b (14/14 PASS, unchanged), Step 281 distinguishability (M1-M7 all True, unchanged), Step 281 E4-R1..R7 (7/7 PASS, unchanged), Step 281 affected F-tests (5/5 PASS, unchanged), Step 281 minimality (M1^M2^M3, unchanged), Step 281 invariants (8/8 preserved, unchanged), Step 282 F15/F17/F18/F16/F19 all unchanged; conclusion: no prior conclusion depended on the defect, and the fix changes no closure dimension (FC unaffected, CC restored to stated level, EC/GC untouched)." (anchor: "Applied to **all three harness copies** — `step-280/exec/`, `step-281/exec/`, `step-282/exec/`.\nThis restores the canonical definition, in which `id = H(P, e, c, t, Π)` hashes the **Evidence tuple**\nand `polarity`/`state` are fields of `Evidence`.")
- [S1948] types=[CONTRADICTION, WARNING] scope=OBJECT — also labeled `theory-gap-register` — "TG-06 (Identity: 'id hashes a mutable field') remains OPEN and BLOCKING according to the theory-gap-register, and is 'not marked closed anywhere', even though two mutually opposite proposed repairs exist in the corpus: one proposes projecting Evidence.state OUT of the hashed id, while the applied Step-282/13 fix (this batch's C-NEW closure) keeps state IN the hash (alongside ref and polarity); the synthesis flags this as an unreconciled contradiction between TG-06's own open status and the fix this batch's C-NEW record treats as closing an id-hash defect." (anchor: "| Identity | `id = H(P,e,c,t,Π)`; **TG-06 OPEN, BLOCKING** — "id hashes a mutable field"; **two opposite\nrepairs exist** (project `e.state` OUT vs the applied step-282/13 fix that keeps `state` IN); TG-06\n"is not marked closed anywhere" |") — lineage claim: SOURCE-CLAIMED-CONTRADICTION of the C-NEW closure record's id-hash fix (keeps state IN) versus a different corpus proposal to project e.state OUT.

## Notes for P3

- This is my own observation: nothing unusual noticed beyond what is already recorded above; evidence base is internally consistent for what it covers.
