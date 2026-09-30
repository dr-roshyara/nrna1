# book-structure-crosswalk-six-part-proposal

**Scope(s):** OBJECT · **Row count:** 3 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** BA-ED3, GN-76, Part I..VI · **Aliases:** PROPOSED BOOK STRUCTURE + CHAPTER-BY-CHAPTER CROSSWALK
**Candidate group membership (NOT an identity claim):**
- G0430: [`book-readiness-audit-part-iv-entry-contract` · `book-structure-crosswalk-six-part-proposal`] — explicit agent-stated uncertainty: 'book-structure-crosswalk-six-part-proposal' POSSIBLY relates to 'book-readiness-audit-part-iv-entry-contract' (batch B0047). Note: A concrete six-Part book restructuring proposal (Discovery/Reconstruction/Architecture/The Formal Programme[new]/Implementation Specification[new]/Implications, GN-76) with a full chapter-by-chapter crosswalk of all 25 existing chapters plus new Part IV (5 ch) and Part V (9 ch) chapter lists; its decisive finding is that an implementation specification chain (objects->state->invariants->operations->transformations->evidence->governance->software->tests) is ratified through invariants/evidence(partial)/governance/software/tests but BLOCKED at operations (v0.2 names zero operations, grep-verified) and transformations (no pre/post-condition spec exists anywhere, delta-commit executes as identity K1 is K0); explicitly a proposal only -- no book file edited, BA-1..BA-6 unmodified, requires a future BOOK ARCHITECTURE v2 ratification act.
- G0431: [`book-structure-crosswalk-six-part-proposal` · `implementation-specification-source-map`] — explicit agent-stated uncertainty: 'implementation-specification-source-map' POSSIBLY relates to 'book-structure-crosswalk-six-part-proposal' (batch B0047). Note: A book-lane-only analysis (GN-77) re-verifying and strengthening GN-76's finding that the ratified architecture defines zero operations and no pre/post-condition specification, over the WHOLE ratified surface (v0.1, v0.2, FA-1..FA-9), individually inspecting all six textual hits for operation-like words and finding all six false positives; produces a 14-row per-construct 'implementable today from canonical material only' table and a 9-link implementation-chain table, concluding the chain is severed between INVARIANTS and OPERATIONS and again between OPERATIONS and TRANSFORMATIONS; ends with an explicit minimum-safe-build statement.

## Sources (how this label entered the ledger)
- **PROPOSAL** (batch B0047, scope OBJECT): A concrete six-Part book restructuring proposal (Discovery/Reconstruction/Architecture/The Formal Programme[new]/Implementation Specification[new]/Implications, GN-76) with a full chapter-by-chapter crosswalk of all 25 existing chapters plus new Part IV (5 ch) and Part V (9 ch) chapter lists; its decisive finding is that an implementation specification chain (objects->state->invariants->operations->transformations->evidence->governance->software->tests) is ratified through invariants/evidence(partial)/governance/software/tests but BLOCKED at operations (v0.2 names zero operations, grep-verified) and transformations (no pre/post-condition spec exists anywhere, delta-commit executes as identity K1 is K0); explicitly a proposal only -- no book file edited, BA-1..BA-6 unmodified, requires a future BOOK ARCHITECTURE v2 ratification act. _[relation_to_existing: POSSIBLY:book-readiness-audit-part-iv-entry-contract]_

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1957] §"```\nPart I    · DISCOVERY                6 ch   [H]     unchanged   (Ed1 done · Ed2 0/6)\nPart II   · RECONSTRUCTION           4 ch   [M]     ACCEPTED — frozen, untouched\nPart III  · ARCHITECTURE            10 ch   [FA]    PRODUCED + GATED — protected, untouched\n          ├─ the canon layer: what the governed architecture defines\nPart IV   · THE FORMAL PROGRAMME   ~5 ch   [H/M]   NEW — the verification era (steps 183–283)\n...\nPart V    · IMPLEMENTATION SPECIFICATION ~9 ch [E]  NEW — what an engineer builds\n...\nPart VI   · IMPLICATIONS & OPEN QUESTIONS 5 ch [P]  the present Part IV, renumbered\n```"
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: [S1957] §"```\nPart I    · DISCOVERY                6 ch   [H]     unchanged   (Ed1 done · Ed2 0/6)\nPart II   · RECONSTRUCTION           4 ch   [M]     ACCEPTED — frozen, untouched\nPart III  · ARCHITECTURE            10 ch   [FA]    PRODUCED + GATED — protected, untouched\n          ├─ the canon layer: what the governed architecture defines\nPart IV   · THE FORMAL PROGRAMME   ~5 ch   [H/M]   NEW — the verification era (steps 183–283)\n...\nPart V    · IMPLEMENTATION SPECIFICATION ~9 ch [E]  NEW — what an engineer builds\n...\nPart VI   · IMPLICATIONS & OPEN QUESTIONS 5 ch [P]  the present Part IV, renumbered\n```"

## Lifecycle
last_seen: S1957. Candidate lifecycle: DORMANT.
Evidence: no retraction/supersession/contradiction evidence recorded; this status is a heuristic based on how recently (by source_id) this label was last used, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | NOT-EVIDENCED-IN-CAPTURE | — |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | PRESENT | S1957 |
| dependencies | PRESENT | S1957 |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | NOT-EVIDENCED-IN-CAPTURE | — |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | PRESENT | S1957 |
| open_questions | PRESENT | S1957 |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)

- [S1957] types=['EXTENSION', 'GOVERNANCE'] scope=METHODOLOGICAL — "Proposes a six-Part book structure that adds rather than repurposes: Part I Discovery and Part II Reconstruction (frozen, ACCEPTED under GN-70) and Part III Architecture (produced, gate-passed) remain untouched; two new Parts are inserted -- Part IV 'The Formal Programme' (~5 chapters, covering the verification era Steps 183-283, every construct there explicitly marked NON-CANONICAL) and Part V 'Implementation Specification' (~9 chapters, the engineer-facing chain objects->state->invariants->operations->transformations->evidence->governance->software->tests); the present Part IV 'Implications' is renumbered to Part VI. Rationale for a separate Part IV rather than folding verification results into Part V: those constructs (Sigma, O, Q_t, closure senses, L0-L6 evidence model) have no governance act behind them and belong in the book only as 'what the programme did', not 'what the system is'." (anchor: "```\nPart I    · DISCOVERY                6 ch   [H]     unchanged   (Ed1 done · Ed2 0/6)\nPart II   · RECONSTRUCTION           4 ch   [M]     ACCEPTED — frozen, untouched\nPart III  · ARCHITECTURE            10 ch   [FA]    PRODUCED + GATED — protected, untouched\n          ├─ the canon layer: what the governed architecture defines\nPart IV   · THE FORMAL PROGRAMME   ~5 ch   [H/M]   NEW — the verification era (steps 183–283)\n...\nPart V    · IMPLEMENTATION SPECIFICATION ~9 ch [E]  NEW — what an engineer builds\n...\nPart VI   · IMPLICATIONS & OPEN QUESTIONS 5 ch [P]  the present Part IV, renumbered\n```")
- [S1957] types=['EXPERIMENTAL-RESULT', 'LIMITATION'] scope=THEORY-LEVEL — "A mechanical, grep-verified check finds the ratified v0.2/FA architecture names ZERO operations, so a proposed Part V.5 chapter ('Operations and their contracts') is BLOCKED -- only formal/open verification-lane material (O_core, three non-agreeing membership lists, none ratified) could fill it; similarly V.6 ('Legal transformations and rejection') is BLOCKED because no pre/post-condition specification exists anywhere in the ratified record, and the reference implementation's delta/commit operation was found to execute as identity (K1 is K0), i.e. it currently does nothing observable." (anchor: "| V.5 Operations and their contracts | operations | **NO — the ratified model names zero operations** (grep-verified) | **BLOCKED** — FORMAL/OPEN only; `𝒪_core` not ratified, three non-agreeing membership lists |\n| V.6 Legal transformations and rejection | transformations | **NO** — no pre/post-condition specification exists anywhere in the record | **BLOCKED** — and `δ`-commit executes as identity (`K₁ is K₀`) |")
- [S1957] types=['GOVERNANCE', 'OPEN-QUESTION'] scope=METHODOLOGICAL — "Lists five decisions required before any new book prose may be written: (1) adopt the six-Part reconciliation or rule differently on the Part I/II structural collision (Part II cannot be repurposed without reopening GN-70); (2) authorize a BA-ED3-style additive Book Architecture v2 ratification vehicle; (3) decide whether Part V ships with V.5/V.6 marked 'NOT ESTABLISHED -- awaiting canonical operation set' or rule on O_core first; (4) decide whether/how the open GC-1/I-11 policy-loop collision is made visible in Part V's governance chapter; (5) decide whether the verification era is admitted into the book at all, even as history (Part IV). Confirms nothing was actually changed: no book file edited, all 10 Part III chapter md5s unchanged, Part II untouched, no construct promoted, no OQ moved, no evidence class collapsed, O_core not frozen, Sigma and Q_t not canonicalized, GC-1 not resolved." (anchor: "## 5 · Decisions required before any prose\n\n1. **Structure** ... 2. **BA v2 ratification** ... 3. **V.5/V.6 blockage** — either accept that Part V ships with two chapters marked\n   `NOT ESTABLISHED — awaiting canonical operation set`, or rule on `𝒪_core` first.\n4. **GC-1 / I-11** ... 5. **Part IV (Formal Programme) admission**")

## Notes for P3
- No internal tension, evidentiary anomaly, or lifecycle-flag discrepancy observed in this label's own rows beyond what the completeness roll-up above already shows.
