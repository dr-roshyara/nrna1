# `KOS-CONTRACT-NEUTRALITY-001` — **commission REGISTERED** (contract language-neutrality investigation)

**Work item:** `KOS-CONTRACT-NEUTRALITY-001` · **Date:** 2026-08-24
**Recorded by:** Governance — `claude-code-session:5928b9f9-b4d5-46e9-8c71-c295dace18f8` *(governance-recording; holds no lane on this work item)*
**Placement derived:** `php scripts/doc-placement.php --scope=product-specific --domain=publicdigit` → `docs/publicdigit` (exit 0) — the existing `KOS-CONTRACT-NEUTRALITY-001` document family lives here.

> ### ⛔ **REGISTERED · NOT COMMISSIONED · NOT AUTHORIZED · NO LANE · NO GRANT.**
> This record captures the human's instructions so they are governed and not lost. It **starts nothing**, appoints nobody, creates no authority, and changes no state. **Nothing in §4 was acted on.**

---

## 1 · What was registered

**The order:** *"can you register this task now"* — PO/ARB, 2026-08-24, accompanied by the full instruction set *"KOS Contract Neutrality — Next Session Instructions"*, preserved in the session transcript.

**The question the work exists to answer, verbatim:**

> *"Is the existing capability contract sufficiently precise and language-neutral that independent PHP and Python implementations can conform to the same externally observable behaviour?"*

**Explicit exclusions, verbatim:** *"This is **not** a Python migration. This is **not** a PHP-to-Python rewrite. This is **not** a target-architecture exercise."*

**The object under test, verbatim:** *"The contract is the subject under test; PHP and Python are implementations under test."* · *"Do not let the reference implementation silently define the contract."*

**Do-not-modify list (the human's own lane isolation):** `KOS-ARCH-BASELINE-001` · the current EKS architecture baseline · the proposed KnowledgeOS architecture · bounded-context definitions · target technology decisions · platform architecture · governance architecture. And: *"Do not use the neutrality experiment to redefine the EKS architecture baseline."*

**Required sequence (the human's, not added to):** existing contract → evidence reconstruction → ambiguity identification → semantic clarification → contract correction → golden fixture review → independent conformance test → Python implementation. *"Do not reverse this sequence."*

**Deliverables A–F:** contract evidence reconstruction · intra-class-call semantic analysis · contract correction proposal (only if ambiguity confirmed) · golden fixture assessment · Python conformance plan (only after the contract is precise) · neutrality verdict.

**Verdict vocabulary, fixed by the human:** `PROVEN NEUTRAL` · `CONDITIONALLY NEUTRAL` · `NOT NEUTRAL` · `NOT YET DETERMINABLE`. *"Do not force a positive verdict."*

**Failure classification vocabulary:** `CONTRACT_ERROR` · `PHP_REFERENCE_ERROR` · `PYTHON_IMPLEMENTATION_ERROR` · `FIXTURE_ERROR` · `TEST_HARNESS_ERROR` · `LANGUAGE_SEMANTIC_GAP` · `UNKNOWN`. *"Never label a failure 'Python incompatibility' without proving that the contract is actually language-neutral."*

**Evidence vocabulary:** `OBSERVED` · `DECLARED` · `INFERRED` · `PROPOSED` · `UNKNOWN`.

**Stop conditions** are the human's §16 and are carried unchanged.

## 2 · ⚠️ The registration's load-bearing observation: the brief's stated evidence appears superseded by the record

**This is reported, not resolved.** The instructions' §2 "Current Known Evidence" describes a state that the authoritative record does not currently match. Governance did **not** reinterpret the findings — it read the record, as the brief itself requires for authoritative state.

| Brief states (`DECLARED`) | Record shows (`OBSERVED`) |
|---|---|
| *"a separate contract-correction work item was created"* | **`KOS-LCOM4-CONTRACT-001` exists and its three lanes are `COMPLETED`** — architecture (draft), implementation (apply), verification (corrected re-verify); grants `DRAFT`, `APPLY`, `REVERIFY` all `AUTHORIZED` |
| *"independent verification identified an ambiguity concerning the meaning of 'intra-class call'"* … *"This is currently the central blocker"* | That correction item is **about exactly this term** — `intra-class call` appears **5 times** in `2026-08-16-KOS-LCOM4-CONTRACT-001-draft-correction.md`, including in golden-fixture definitions |
| *"Stage 2 Python implementation is therefore blocked"* | Grants `G-KOS-CONTRACT-STAGE2`, `G-KOS-CONTRACT-STAGE2-VERIFY`, `G-KOS-CONTRACT-STAGE2-BREADTH` are all recorded `AUTHORIZED` |
| *(no later work mentioned)* | **40 transitions · 27 grants** (one `REVOKED`), including `SEMANTIC-CLARIFY` (+2 amendments), `PARSING-ARCH`, `IMPL-ARCH` (+4 amendments), `IMPL-TRACK1` (+1), `TRACK1-VERIFY` (+1), `V3-ARCH` (+1), `ARTIFACT-UPDATE` (+3) — and documents dated **2026-08-19** including a V-3 architecture determination and a decisions registration |
| *(no active work implied)* | **An architecture lane is currently `ACTIVE`** and holds the work item (`S4-architecture-v3-determination`) |

**What Governance does NOT claim.** Whether the *"intra-class call"* ambiguity is actually **resolved** is **`UNKNOWN`** to this registration. Establishing that would require reading the LCOM4 re-verification and the V-3 determination — **that is investigation, and investigation was not what was ordered.** The completion of a correction item's lanes is evidence that work occurred; it is **not** proof that the semantic question was settled.

**Also `UNKNOWN`:** whether this commission is intended to **continue** the existing investigation, **re-baseline** it from an earlier point, or **run alongside / supersede** the active architecture lane.

## 3 · Why this matters before anyone starts

Three risks, stated plainly and not as objections:

1. **Duplicate ownership.** If the *"intra-class call"* correction is already delivered, Deliverable B and C would re-do owned work — the brief's own §11-equivalent discipline (*search for an existing owner before creating a new item*) applies to the deliverables too.
2. **An active lane.** A second actor investigating the same work item while an architecture lane holds it is the coordination hazard already recorded as `EKS-07`.
3. **A stale premise producing a wrong verdict.** If §2's evidence is superseded, a verdict derived from it would be unsound regardless of how carefully it were reached.

## 4 · What was NOT done

No lane · no appointment · no grant (not even `PROPOSED`) · no transition · no evidence table · no contract reconstruction · no semantic analysis · no correction proposal · no fixture assessment · no Python plan · no verdict · no file read for investigative purposes beyond establishing §2's discrepancy · nothing in the do-not-modify list touched · `KOS-LCOM4-CONTRACT-001` not reopened · the active architecture lane not disturbed · `AST-019` and its closed lifecycle untouched.

## 5 · Decision required before this work can start

**Smallest decision:** on the state discrepancy in §2 — does this commission **continue** the existing investigation from where the record actually stands, or **re-run** the reconstruction from the brief's stated baseline?

Then, separately and only if the work is to proceed: **authorization** (a grant), and **who performs it** — including whether the currently active architecture lane is the performer or is a different concern entirely.

**Governance recommends nothing here and asks for no additional review.** Independent verification and Architecture review are **`OPTIONAL`** for this investigation on present evidence — no binding rule requires either for an evidence-reconstruction task, and the brief itself makes them conditional.

**Traceability:** PO/ARB order 2026-08-24 · authoritative fold `KOS-CONTRACT-NEUTRALITY-001` (40 transitions, 27 grants, architecture lane ACTIVE) · authoritative fold `KOS-LCOM4-CONTRACT-001` (3 lanes COMPLETED) · `2026-08-16-KOS-LCOM4-CONTRACT-001-commission.md` · `2026-08-16-KOS-LCOM4-CONTRACT-001-draft-correction.md` · `2026-08-19-KOS-CONTRACT-NEUTRALITY-001-V3-architecture-determination.md` · `2026-08-19-KOS-CONTRACT-NEUTRALITY-001-v3-decisions-registration.md` · `EKS-07` (multi-process coordination) · placement `scripts/doc-placement.php` (exit 0)
