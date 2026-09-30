# proof-checking-vs-generation-and-ai-reasoning

**Scope(s):** THEORY-LEVEL · **Row count:** 1 ·
**Lifecycle (candidate):** ACTIVE · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `AI(E,Gamma) -> CandidateDerivation`; `Check(pi,Gamma)`
**Aliases:** none recorded
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)

- OBJECT-INDEX, batch B0067, scope THEORY-LEVEL: "Separation of proof generation (including AI-generated candidate derivations) from proof checking/verification, with Generated!=Verified!=True."

## Candidate births

- CANDIDATE-LEXICAL-BIRTH: [S2785 §"Check(pi,Gamma) -> {Valid,Invalid,Undetermined}. ... Proof-Checking Soundness ... AI(E,Gamma) -> CandidateDerivation [followed by] Verify(CandidateDerivation,Gamma) -> Valid/Invalid/Undetermined. ... Generated != Verified. And Verified != True."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S2785 §"Check(pi,Gamma) -> {Valid,Invalid,Undetermined}. ... Proof-Checking Soundness ... AI(E,Gamma) -> CandidateDerivation [followed by] Verify(CandidateDerivation,Gamma) -> Valid/Invalid/Undetermined. ... Generated != Verified. And Verified != True."]
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle

last_seen: S2785. Candidate lifecycle: ACTIVE.
Evidence: `lifecycle_evidence` is empty (retracted_by: [], superseded_by: [], contested_by_own_contradiction_type: false). ACTIVE is a recency heuristic based on last use (S2785), not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up

| Dimension | Status | source_ids |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S2785 |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S2785 |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale

NOT-EVIDENCED-IN-CAPTURE — no row in this label's family is typed ARGUMENT/ANALYSIS/EXPLANATION/ALTERNATIVE.

## Assumption register

NOT-EVIDENCED-IN-CAPTURE.

## All rows (source_id order)

- [S2785] types=[FORMALIZATION, DISTINCTION, CONSTRAINT] scope=THEORY-LEVEL — "21.28-21.30: formalizes proof checking Check(pi,Gamma)->{Valid,Invalid,Undetermined} against 10 listed checks, with Proof-Checking Soundness stating Check(pi,Gamma)=Valid implies Gamma⊢q only under a sound/trusted checker; separates proof generation from verification, especially for AI: AI(E,Gamma)->CandidateDerivation followed by Verify(CandidateDerivation,Gamma)->Valid/Invalid/Undetermined, so the AI is a 'generator of candidate reasoning artifacts', not a self-authenticating source; AI-generated reasoning may contain correct derivations, incorrect premises, invalid rule applications, hidden assumptions, circular reasoning, semantic category errors, fabricated sources, incorrect arithmetic, or unsupported causal claims, giving Generated≠Verified and Verified≠True (unless the verification system establishes the semantic bridge); AI-generated reasoning should be classified CandidateDerivation until verification succeeds." (anchor: "Check(pi,Gamma) -> {Valid,Invalid,Undetermined}. ... Proof-Checking Soundness ... AI(E,Gamma) -> CandidateDerivation [followed by] Verify(CandidateDerivation,Gamma) -> Valid/Invalid/Undetermined. ... Generated != Verified. And Verified != True.")

## Notes for P3

- This is my own observation: singleton label (1 row) — evidence base is thin by construction; no internal corroboration is possible from this label alone.
