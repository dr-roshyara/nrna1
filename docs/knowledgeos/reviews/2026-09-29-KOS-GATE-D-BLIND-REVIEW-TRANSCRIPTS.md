# Gate D — Blind Review Transcripts (Verbatim)

**Date:** 2026-09-29. Persisted so these reports are checkable against a file, not only quotable from
conversation history — a gap an independent review of a downstream document correctly flagged (the
downstream document cited these reports as evidence before they existed anywhere on disk).
Six reviewers, three real WP rulings/ruling-sequences, each read blind: told upfront that the WP
ruling log and prior KOS research share one author, given no theory or vocabulary in advance, asked
to answer nine fixed questions in their own words from the primary source
(`engineering/architecture/adr/ADR-AIP-LOG-Platform-Rulings.md`) directly.

## Case 1 — R-43/R-49/R-50/R-53

### Reviewer A (forward reading order)

[Full text as delivered by the subagent, unedited]

Read directly from `engineering/architecture/adr/ADR-AIP-LOG-Platform-Rulings.md`, rows R-43, R-49, R-50, R-53 (plus R-52/R-61/R-62/R-63 for surrounding vocabulary).

1. Authority act. The legitimating act is the ARB's formal ruling itself — R-43: "WP-6 ACCEPTED (EPIC-004 temporal machinery) — Delivery Governance · Approval · ARB," grounded in "authority reviews complete." Legitimacy comes from the ARB having ruled, not from any test run per se.

2. "The record" vs. the act. R-43's prose ("10 tests / 22 assertions · all gates pass... PHPStan max clean... Deptrac 0... Architecture 146 green") is a written claim about what happened. R-53 makes the record/act gap explicit: its note "does not... determine whether the gate was ACTUALLY EXECUTED at the time of acceptance" — the record asserted execution; whether execution actually occurred is a separate question.

3. Execution evidence. The only genuine post-hoc execution evidence is R-50's reproduction: running composer merge-gate at "the recorded WP-6 closure commit" 22d604844. Its result, quoted in R-53: "the GreenfieldCore suite terminated with a fatal error and the merge gate would not have completed successfully." That reproduced result contradicts R-43's claimed evidence.

4. Substantive content. R-43: WP-6 declared accepted/closed, unlocking WP-7. R-49: chooses remediation strategy "Option B" — reproduce the historical gate before opening any repair, explicitly "keeps evidence collection separate from repair." R-50: authorizes exactly that reproduction, scoped to "evidence collection only." R-53: attaches a note to R-43 recording the contradiction, "THIS IS AN ANNOTATION, NOT AN AMENDMENT."

5. Citation. Extensive internal cross-referencing: R-53 cites R-50 ("reproduced evidence from the historical reproduction (R-50) establishes..."); R-49 cites finding "F-7A-1"; R-43's row is annotated in place by both R-53 and later R-62 ("RECORDING CORRECTION A1"). Rulings cite each other by ID throughout.

6. What's authorized ≠ work done. R-50 authorizes only the reproduction run, with explicit negative scope: "no production changes · no test repairs · no governance edits · evidence collection only." R-49 states directly: "No remediation work package is opened by this ruling." Authorization is a permission/decision act; the text repeatedly and deliberately marks it as distinct from execution.

7. Derived vs. stated. The central derived (not directly observed) conclusion is a disjunction R-53 states but refuses to collapse: "Whether the acceptance evidence was FALSE (the gate was run and misreported) or UNSUPPORTED (the gate was not run) remains UNDETERMINED from repository evidence." The reproduced fatal error is stated fact; the inference to "the merge gate would not have completed successfully" is a derived reading of that fact; but the further inference to which failure-mode explains R-43's original claim is explicitly declared not derivable.

8. Current state. Partially resolved. The acceptance itself is settled and reaffirmed: R-53 says "the WP-6 acceptance STANDS." But the evidentiary basis for that acceptance is now flagged as doubtful and explicitly left open — "not resolved by this note." So: decision closed, evidentiary question open, held apart on purpose.

9. What remains genuinely unresolved. Precisely the FALSE-vs-UNSUPPORTED disjunction about R-43's original gate evidence — stated twice, verbatim, as undetermined "from repository evidence." Also unresolved (visible in nearby rows): whether R-43's acceptance should be revisited given the contradiction is never decided here — R-53 pointedly declines to do that work, only to record the doubt.

In my own words: the record deliberately separates the decision's validity (which stands) from the evidence's truth-value (which is left as an irreducible two-way disjunction), and further separates authorization to investigate from the investigation's result from repair of what the investigation found. Each of these pairs is kept apart by explicit textual guardrails ("annotation, not amendment"; "evidence collection only"; "not for repair") rather than collapsed into a single verdict.

### Reviewer B (reverse reading order: R-53 → R-50 → R-49 → R-43)

[Full text as delivered by the subagent, unedited]

All four rulings read directly from engineering/architecture/adr/ADR-AIP-LOG-Platform-Rulings.md (lines 29, 35, 36, 39), read R-53 → R-50 → R-49 → R-43 as instructed.

1. Authority act. The act of legitimacy is the ARB's ruling itself, not any underlying test run. R-43: "WP-6 ACCEPTED ... Delivery Governance · Approval · ARB" — acceptance is an ARB act. R-50: ARB "HISTORICAL REPRODUCTION AUTHORIZED." R-53: ARB adopts a "GOVERNANCE NOTE ... Note as adopted:" — the authorizing act is adoption/ratification, distinct from the fact being noted.

2. Merely "the record." R-43's evidence line ("10 tests / 22 assertions · all gates pass") is a written claim, not proof of execution. R-53 makes this explicit: the note "does not amend R-43, nor does it determine whether the gate was ACTUALLY EXECUTED at the time of acceptance."

3. Execution evidence. Only R-50's reproduction constitutes actual execution evidence: running composer merge-gate "at the recorded WP-6 closure commit" 22d604844 produced a concrete result — "the GreenfieldCore suite terminated with a fatal error and the merge gate would not have completed successfully." No comparable execution evidence exists for the original R-43 claim itself.

4. Substantive content. R-43 accepted WP-6/EPIC-004 and opened WP-7 entry conditions. R-49 chose a strategy (Option B) without opening remediation. R-50 authorized a scoped, read-only reproduction. R-53 attached a note to R-43, explicitly not amending R-43.

5. Citation. Heavy internal cross-referencing: R-53 cites R-50 by ID; R-43's annotation cites R-53 and R-50; R-49 cites finding "F-7A-1"; later R-62 cites R-43/R-48/R-50 to correct type labels. External citation: commit hash 22d604844 and rule ES-004.3.

6. What's authorized vs. done. R-50 authorizes precisely one bounded activity. Authorization ≠ completion: R-50's own outcome column says only "a read-only investigation track is authorized" — the actual running and reporting is a separate later act. Mirrors R-52's language elsewhere: "the package is OPENED, not delivered."

7. Derived vs. stated. The fatal-error result is stated directly. The derived-but-blocked item is the disjunction R-53 sets up and refuses to resolve: "FALSE... or UNSUPPORTED..." — explicitly declared "UNDETERMINED from repository evidence."

8. Current state. Settled as a governance act, unsettled as fact. R-53 states twice: "R-43's decision text is unchanged and the WP-6 acceptance STANDS." The evidentiary question is left permanently open by the same note.

9. Genuinely unresolved. The FALSE-vs-UNSUPPORTED disjunction, stated three times almost verbatim across R-43's annotation and R-53, each time flagged unresolved.

In my own words: a governance body's power to ratify a decision is independent of its power to verify the fact the decision was based on. The record and the act are kept on two separate planes that citation can point between but never merge: annotating a decision is not the same speech-act as verifying the fact it once asserted, and the log is honest enough to say so rather than quietly closing the gap.

## Case 2 — R-46/R-47/R-48

### Reviewer A (forward reading order)

[Full text as delivered by the subagent, unedited]

Read directly from engineering/architecture/adr/ADR-AIP-LOG-Platform-Rulings.md, rows R-46/R-47/R-48 (2026-08-01), plus R-51/R-56/R-58/R-62 for surrounding vocabulary.

1. Authority act: The act of legitimacy is the vote/ruling itself by the named body. R-46 is explicitly "the Decision Authority's act." R-47 and R-48 are acts of the ARB. The authority act is the deciding body casting its ruling — not the document it rules on.

2. "The record": The ruling-log row itself is the record of the act. Distinct from that, the plan file .claude/plans/WP-7-retention-alignment.md is called "the binding statement of intended work" — a record of intention that R-46's act approves, not the approving act itself.

3. Execution evidence: Only R-48 supplies it — "slice 7A GREEN report — 11/11 keystones · Deptrac 0 with deptrac.yaml unmodified · PHPStan max clean · Architecture suite 149 green." R-46 and R-47 contain no such artifacts; they are pure decisions.

4. Substantive content and relation: R-46 approves the PLAN. R-47 authorizes EXECUTION of one slice only ("Scope is slice-granular: 7A ONLY — 7B and 7C are NOT authorized"), gated on R-43 ∧ R-46 ∧ R-44 being satisfied. R-48 accepts the COMPLETED work for that same slice, explicitly narrowing: "Acceptance is of 7A's authorized SCOPE, not of a green gate." Plan → scoped authorization → scoped acceptance, each narrower than the last.

5. Citation: R-47 cites R-43, R-46, R-44 by ID as guard-satisfying rulings. R-48 cites R-44's earlier prediction ("Deptrac passes unmodified" was "an analytical prediction at R-44 and is now an executed result") and cites open item F-7A-1. R-47 also cites R-34.

6. What's authorized vs done: R-46 authorizes only the plan. R-47 authorizes only execution activity, and only "RED at 7A" as the first authorized step — "7A is inert — no observable behaviour changes until 7C." R-48 authorizes nothing new; it closes the loop with evidence.

7. Derived vs stated: R-48's Deptrac note is explicitly a derivation-turned-verification: a claim "analytical" (derived/predicted) at R-44 becomes an "executed result" at R-48. R-47's "zero architectural gates remain" is derived from a stated logical conjunction of guards.

8. Current state: Per R-48, "Slice 7A ACCEPTED and CLOSED." A later annotation, R-62, corrects R-48's TYPE label — "annotation; decision unchanged" — so the substance is settled, but the record needed a bookkeeping correction after the fact.

9. Unresolved: (a) F-7A-1 — the full composer merge-gate was never demonstrated for 7A. (b) The "Layer Verification Rule" methodology proposal "remains PROPOSED and non-binding." (c) R-48's own transition type was wrong until corrected by R-62.

In my own words: the record repeatedly separates "permission to act" from "proof that the act occurred," and refuses to let either one silently stand in for the other.

### Reviewer B (reverse reading order: R-48 → R-47 → R-46)

[Full text as delivered by the subagent, unedited]

Read directly from engineering/architecture/adr/ADR-AIP-LOG-Platform-Rulings.md, lines 32-34 (R-46/R-47/R-48), read in order R-48→R-47→R-46, plus surrounding rows R-40..R-45 and R-34/R-51/R-62 for vocabulary.

1. Authority act. Each ruling has a different authority act. R-46: "Planning Governance · Approval · DECISION AUTHORITY (not the ARB...)." R-47: "Execution Governance · Authorization · ARB." R-48: "Delivery Governance · Approval · ARB." Three distinct bodies/act-types, not one.

2. The record vs the act. The table row itself is the record. Distinguished from the underlying artifacts it points to: the plan file, and from later annotation R-62 which corrects the record's type-label without touching "decision text."

3. Execution evidence. Only R-48 supplies it, and also records a negative evidence fact: "7A never demonstrated a complete composer merge-gate... the gate remains blocked by F-7A-1." Execution evidence is present but explicitly partial.

4. Substantive content / relation. R-46 approves intent. R-47 authorizes execution but scoped: "7A ONLY — 7B and 7C are NOT authorized," and "7A is inert." R-48 accepts and closes delivered work, "of 7A's authorized SCOPE, not of a green gate." Plan→Authorize→Accept, each gated on the prior.

5. Citation. R-47: "Guard WP-6 ACCEPTED ∧ WP-7 PLAN APPROVED satisfied by R-43 ∧ R-46"; cites R-34. R-48 cites R-44. Later R-62 cites back into R-48.

6. What's authorized, and is authorization = work done? No. R-46 authorizes only the plan's status. R-47 authorizes only slice-7A RED-phase work. R-48 is not an authorization at all — it's closure.

7. Derived vs stated. R-47's guard satisfaction is a derived logical conjunction stated explicitly. R-48 derives a status change: what "was an analytical prediction at R-44 is now an executed result."

8. Current state. Settled for 7A's authorized scope. But even this settled state was later re-labeled: R-62 corrects R-48's transition type from "Approval" to "Acceptance."

9. Genuinely unresolved. (a) F-7A-1. (b) The "Layer Verification Rule" module "remains PROPOSED and non-binding." (c) The type-label scheme itself was inconsistent at the time of R-48 and only fixed later by R-62.

Most interesting distinction: the record repeatedly separates authorization (permission to act) from the act itself being performed and from evidence that it was performed correctly — three different things that could each independently be true or false.

## Case 3 — R-44

### Reviewer A

[Full text as delivered by the subagent, unedited]

R-44 (2026-08-01, "A-1 RATIFIED — Architecture Governance · Ratification · ARB") full text quoted and analyzed.

1. Authority act: The ruling itself — "A-1 RATIFIED," typed "Ratification · ARB." The act of legitimacy is the ARB's approval/ratification of "option (d)" as the realization of the invariant.

2. The record vs. the act: The ruling text is the record. The record is distinguished from execution: "Deptrac passes unmodified" was flagged as "an analytical prediction at ruling time, not an executed result."

3. Execution evidence: None in R-44 itself. Appears only later: R-48 states "'Deptrac passes unmodified' was an analytical prediction at R-44 and is now an executed result," backed by "Deptrac 0."

4. Substantive content: R-44 determines how the "MAD has exactly one home" invariant is technically realized: Election gets its own consumer-side port rather than directly consuming Adjudication's existing port.

5. Citation: R-44 cites finding "G-1" as provenance; later rulings (R-47, R-56, R-57) cite R-44 back.

6. What is authorized, and is that the same as work being done: R-44 authorizes/ratifies a design choice — not execution of code. The ruling's own "Recorded limit" flags that "Deptrac passes unmodified" was a prediction, not something executed yet.

7. Derived vs. stated: The reclassification of G-1 as an "Architecture–Enforcement Alignment Gap" is a derived conclusion, with its premise stated in parentheses: "(architecture correct · enforcement correct · the mapping between them incomplete)."

8. Current state: Settled as a decision but explicitly flagged as evidentially incomplete at the time of ruling. Becomes fully settled later via R-48 and R-57.

9. Underdetermined/unresolved, and the invariant/mechanism distinction: R-44 distinguishes an invariant (the fixed property that must always hold — "MAD has exactly one home") from a mechanism (one particular technical way of satisfying that property, "substitutable"). What remains open at the moment of R-44: whether Deptrac would actually pass was unverified — closed two rulings later. R-57 shows that ratifying a decision doesn't automatically propagate it everywhere it's referenced.

In my own words: R-44 separates what must remain true (the invariant) from how you currently make it true (the mechanism), and further separates deciding something from verifying it happened. Decision, mechanism, and verification are kept as three separate things that must each be independently discharged.

### Reviewer B — NOT YET RECEIVED AT TIME OF WRITING THIS TRANSCRIPT FILE

This case's second reviewer report had not been delivered when this transcript file was created;
appended below if/when it lands, or explicitly marked absent if the research moves on without it.

---

**Traceability:** dispatched by this session as part of Gate D (blind double-review of real WP
rulings, reviewers told upfront about shared authorship, per explicit instruction). Referenced by
`2026-09-29-KOS-CROSS-ARTIFACT-MINIMAL-THEORY.md`, which cited these before this file existed — a
gap an independent review caught and this file corrects.
