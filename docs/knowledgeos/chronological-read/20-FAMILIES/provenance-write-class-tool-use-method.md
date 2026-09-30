# provenance-write-class-tool-use-method

**Scope(s):** METHODOLOGICAL · **Row count:** 5 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** write-class tool_use provenance · **Aliases:** none recorded
**Candidate group membership (NOT an identity claim):** Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** (batch B0003, scope METHODOLOGICAL): A methodology introduced in this batch: identify an artifact's true producing process by searching session transcripts for write-class tool_use operations (not tool_result echoes) targeting the artifact's path, correlated to commit timestamps -- stronger evidence than self-declaration.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S0092 §"50d55d26 is not a session identifier at all. It is a git commit hash. ... Authorship of a commit is established by provenance, not by a self-declaration the artifact was never asked to carry."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S0164. Candidate lifecycle: DORMANT.
Evidence: none recorded (retracted_by/superseded_by empty, contested flag false). The DORMANT classification is a heuristic based on how recently (by source_id) this label was last used in the ledger, not a confirmed retirement and not a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | PRESENT | S0092 |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | NOT-EVIDENCED-IN-CAPTURE | — |
| type_signature | PRESENT | S0092 |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | NOT-EVIDENCED-IN-CAPTURE | — |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | PRESENT | S0164 |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
The evidence base attributes the following rationale to this object (as recorded, cited to its source):

- **[CORRECTION/ANALYSIS]** [S0092]: The prior registration's premise that a Track-1 report's author had 'no identifier to exclude' was itself falsified: the cited string was a git commit hash, not a session identifier, and its true producer is discoverable via write-class provenance -- a process already named in the exclusion list.

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S0092] types=[CORRECTION, ANALYSIS] scope=METHODOLOGICAL — "The prior registration's premise that a Track-1 report's author had 'no identifier to exclude' was itself falsified: the cited string was a git commit hash, not a session identifier, and its true producer is discoverable via write-class provenance -- a process already named in the exclusion list." (anchor: "50d55d26 is not a session identifier at all. It is a git commit hash. ... Authorship of a commit is established by provenance, not by a self-declaration the artifact was never asked to carry.")
- [S0092] types=[EXTENSION, VALIDATION] scope=METHODOLOGICAL — "A methodology is introduced and validated for establishing artifact authorship independently of self-declaration: search session transcripts for write-class tool_use operations targeting the artifact's path, correlated against commit timestamps, distinguishing an issuing tool_use block from a self-contaminating tool_result echo of the same string." (anchor: "Search every session transcript in this project for a write-class operation (cat >, tee, sed -i, Write, Edit) whose target is ...track1-independent-verification.md, then confirm the hit is a tool_use input block and not a tool_result echo")
- [S0099] types=[EXTENSION, VALIDATION] scope=METHODOLOGICAL — "Extension to the provenance method: distinguishing a file-creating write (cat >) from a file-appending write (cat >>) resolves which of two candidate sessions authored an artifact versus merely appended a correction to it, corroborated by a clock reconciliation between UTC session transcripts and local-time git commits." (anchor: "Which transcript CREATED the refusal record (cat >, not cat >>)? 2da45a86: cat > x1 real, cat >> x0. 4858c37c: cat > x0, cat >> x1.")
- [S0105] types=[LIMITATION, EXTENSION] scope=METHODOLOGICAL — "Explicit statement that git commit metadata (author, co-author trailer) cannot distinguish which of several AI sessions authored a given change, since all commits share the same human author and co-author trailer; only write-class transcript provenance (the actual tool_use calls) is discriminating evidence." (anchor: "Git metadata is not probative of process authorship. Every commit in scope carries the same human identity and the same trailer. Write-class transcript provenance is the discriminating evidence.")
- [S0164] types=[VALIDATION, EXPERIMENTAL-RESULT] scope=METHODOLOGICAL — "A worked application of the write-class tool_use provenance method: an artifact whose producing process a prior registration deemed 'unnameable' is positively identified by finding the single transcript with a write-class tool_use targeting that exact path, correcting a category error (a commit hash had been mistaken for a session identifier)." (anchor: "write-class tool_use provenance — exactly one transcript issued cat > ...track1-independent... 2da45a86. ... 50d55d26 is a git commit hash, not a session identifier.")

## Notes for P3
(Own observation) Nothing unusual noticed while drafting this file beyond what is already recorded above.
