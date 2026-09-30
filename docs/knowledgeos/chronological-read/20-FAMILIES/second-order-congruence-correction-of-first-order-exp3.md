# second-order-congruence-correction-of-first-order-exp3

**Scope(s):** OBJECT · **Row count:** 6 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `SO-EXP-01`, `SO-EXP-03` · **Aliases:** none recorded
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- OBJECT-INDEX, batch B0042, scope OBJECT: "A second-order investigation executes the corpus's own Step 259 congruence-matrix test (s259.9, 17 rows the corpus itself left UNRESOLVED) over a bounded 208-state, 6-abstraction, 8-operation, 2-dependency-variant domain and finds the terminal model F4=K=(A,R) IS congruent (no counterexample found) for every implemented class-1 (state-transforming) operation in BOTH dependency variants. This corrects the first-order gap-discovery pass's EXP-3 (OUT-congruence.txt), which had concluded K=(A,R) sufficiency 'depends on the operation set' using a history-reading predicate (ever_contested): per the corpus's own s259.7-8 five-class operation taxonomy, ever_contested is a class-4 audit/history operation excluded by construction from the class-1 congruence test. SO-EXP-03 states verbatim: 'The first-order pass EXP-3 tested a class-4 predicate against the class-1 criterion. Its arithmetic was correct; its inference was not. CORRECTED HERE.'"

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1753 §"Implements the corpus's OWN specified test (Step 259 s259.15):

    F(H1) = F(H2)   =>   F(T_hat(H1,c)) = F(T_hat(H2,c))     for all T in T_mandatory"]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S1753 §"Implements the corpus's OWN specified test (Step 259 s259.15):

    F(H1) = F(H2)   =>   F(T_hat(H1,c)) = F(T_hat(H2,c))     for all T in T_mandatory"]
- CANDIDATE-OPERATIONAL-BIRTH: [S1754 §"for fname, F in ABSTRACTIONS.items():
        row = f"  {fname:<26}"
        for opname in OPERATIONS:"]
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S1774. Candidate lifecycle: DORMANT. Evidence: retracted_by and superseded_by are both empty and no own-row contradiction trigger fired; the DORMANT classification is a heuristic based on how recently (by source_id order) this label was last used, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | source_ids |
|---|---|---|
| purpose_rationale | PRESENT | S1753, S1754, S1756 |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S1753 |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | NOT-EVIDENCED-IN-CAPTURE | — |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | PRESENT | S1754, S1756 |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
The second-order module implements the corpus's own Step 259 §259.15 congruence-preservation criterion (F(H1)=F(H2) implies F(T(H1,c))=F(T(H2,c)) for every mandatory operation T) as executable code, making computable three separate corpus-specified but never-computed dependency matrices (s256.28 operation×information 9x6, s257.22 four-operation dependency 4x8, s259.9 congruence matrix 17 rows) that the corpus itself left entirely marked UNRESOLVED/'?'. [S1753] SO-EXP-01's stated limitation, honoring the corpus's own epistemic discipline (s259.16): a PASS over the bounded 208-state test domain means only 'no counterexample found in this domain', never 'congruence proven globally'; a FAIL, by contrast, is a genuine refutation since a single counterexample suffices. [S1754] SO-EXP-03's verdict: across the corpus's full 17-operation, 5-class vocabulary, K=(A,R) is congruent for every implemented class-1 operation over both dependency variants, determines every class-2 (Assess) reading using only state-visible fields, and is not challenged at all by class-3/4/5 operations, which the corpus's own s259.8 excludes by construction — so the open remainder of s256.32's unfinalized nine operations is NOT load-bearing for K: a choice among them cannot change the answer, and is therefore not a normative decision that needs making. What remains genuinely live is SO-EXP-02's separate finding: congruence is necessary but not sufficient, and which invariants are mandatory (and whether K can express them) is the real open question. [S1756]

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S1753] types=[FORMALIZATION, EXPLANATION] scope=THEORY-LEVEL — "The second-order module implements the corpus's own Step 259 §259.15 congruence-preservation criterion (F(H1)=F(H2) implies F(T(H1,c))=F(T(H2,c)) for every mandatory operation T) as executable code, making computable three separate corpus-specified but never-computed dependency matrices (s256.28 operation×information 9x6, s257.22 four-operation dependency 4x8, s259.9 congruence matrix 17 rows) that the corpus itself left entirely marked UNRESOLVED/'?'." (anchor: "Implements the corpus's OWN specified test (Step 259 s259.15):

    F(H1) = F(H2)   =>   F(T_hat(H1,c)) = F(T_hat(H2,c))     for all T in T_mandatory")
- [S1754] types=[LIMITATION, EXPLANATION] scope=METHODOLOGICAL — "SO-EXP-01's stated limitation, honoring the corpus's own epistemic discipline (s259.16): a PASS over the bounded 208-state test domain means only 'no counterexample found in this domain', never 'congruence proven globally'; a FAIL, by contrast, is a genuine refutation since a single counterexample suffices." (anchor: "LIMITATION Bounded domain. Per s259.16 a finite test establishes
           Congruent_tested, NEVER Congruent_global.")
- [S1754] types=[EXPERIMENT, EXPERIMENTAL-RESULT] scope=THEORY-LEVEL — "SO-EXP-01 executes the full 6-abstraction x 8-operation x 2-variant congruence matrix over a 208-state bounded domain (2 ids x 2 contents x 2 origins x 2 statuses, plus supersession/merge-record variants) and reports, per the accompanying so_exp03 verdict, that F4=K=(A,R) passes (is congruent) for every implemented class-1 operation in both the 'blind' and 'sensitive' (provenance-reading) dependency variants — the first computed answer to a corpus matrix that had stood as 17 rows of 'UNRESOLVED'." (anchor: "for fname, F in ABSTRACTIONS.items():
        row = f"  {fname:<26}"
        for opname in OPERATIONS:")
- [S1756] types=[CORRECTION] scope=THEORY-LEVEL — "Explicit correction of the first-order gap-discovery pass's EXP-3: EverContested and RevisionCount are CLASS-4 (audit/history) operations that read History, not state-transforming class-1 operations, and the corpus's own s259.8 excludes non-state-transforming operations from the primary congruence test, and its s257.32 distinguishes 'history dependence of implementation' from 'history dependence of state semantics' (only the second disproves state sufficiency); a history-reading QUERY does not refute K=(A,R) — it shows only that the system must retain H somewhere, which Steps 247 and 265 already grant. The first-order EXP-3's arithmetic was correct; its inference (that K=(A,R) sufficiency depends on 𝒪 including ever_contested) was not." (anchor: "=> The first-order EXP-3 tested a class-4 predicate against the class-1
       criterion.  Its arithmetic was correct; its inference was not.
       CORRECTED HERE.")
- [S1756] types=[EXPERIMENTAL-RESULT, ARGUMENT] scope=THEORY-LEVEL — "SO-EXP-03's verdict: across the corpus's full 17-operation, 5-class vocabulary, K=(A,R) is congruent for every implemented class-1 operation over both dependency variants, determines every class-2 (Assess) reading using only state-visible fields, and is not challenged at all by class-3/4/5 operations, which the corpus's own s259.8 excludes by construction — so the open remainder of s256.32's unfinalized nine operations is NOT load-bearing for K: a choice among them cannot change the answer, and is therefore not a normative decision that needs making. What remains genuinely live is SO-EXP-02's separate finding: congruence is necessary but not sufficient, and which invariants are mandatory (and whether K can express them) is the real open question." (anchor: "=> Across the full corpus operation vocabulary (17 named operations, five
  classes), the terminal model K=(A,R):
    * is congruent for every implemented class-1 operation")
- [S1774] types=[CORRECTION] scope=OBJECT — "Explicit correction of the first-order pass's EXP-3: EverContested/RevisionCount are class-4 (history-reading) predicates excluded by s259.8's class-1-only congruence test, and s257.32 states history-dependence of an implementation does not disprove state-semantics sufficiency -- so a history-reading query does not refute K=(A,R); it only shows the system must retain H somewhere, which s247/s265 already grant." (anchor: "The first-order pass EXP-3 tested a class-4 predicate against the class-1 criterion. Its arithmetic was correct; its inference was not. CORRECTED HERE.")

## Notes for P3
- family.files_touching lists source_id(s) ['S1724', 'S1758', 'S1760'] that do not appear among this label's own family.rows — a data-completeness oddity for P3 to check against 03-CONTRIBUTIONS.jsonl.
