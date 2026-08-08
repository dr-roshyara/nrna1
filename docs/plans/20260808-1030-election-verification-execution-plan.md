# Election Verification — Execution Plan

**Date:** 2026-08-08 · **Status:** **SLICE 1 AUTHORISED** by the Product Owner (investigation only; PO review gate before Slice 2). Slices 2–7 remain PROPOSED. · **Checkpoint:** `9a38c510`

> **Governing principle, adopted:** *"We are not trying to make Election tests green. We are trying to establish that the Election system is correct, that the tests actually verify that correctness, and that the authoritative Election Manifesto/Lifecycle is the source of every business decision that is supposed to depend on lifecycle."*
>
> **Slice 1 scope, as authorised:** inventory every Election test and map each to — lifecycle state · capability · business invariant · entry point/route · authoritative decision source · fixture · expected behaviour · actual behaviour · failure status · existing evidence · coverage gap. **Incorporate the already-proven classifications** from `PBDIGIT-48` and the four closed clusters rather than re-deriving them. **No code, no fixtures, no production changes, no legacy migration. Stop for PO review.**
**Implements:** `docs/publicdigit/backlog/full_discovery.md` (Product Owner's Full Discovery Plan, §1–19)
**Does not reopen:** `PBDIGIT-48` Option B · `PBDIGIT-59` · the four closed clusters

---

## Objective

Turn the Full Discovery Plan into executable slices **without re-deriving what is already evidenced**. The plan's own Definition of Done governs; this document is only *how* and *in what order*.

## What is already done — do not redo

| Plan section | Already satisfied by | Evidence |
|---|---|---|
| §2 Freeze the authoritative model | `ADR_20260807_1500_Election_Lifecycle_Single_Source_Of_Truth` | committed |
| §9 Legacy-field migration inventory | `PBDIGIT-48` §Completion Report — 46 sites, four buckets | committed |
| §3 Classification (partial) | 4 clusters closed on proven mechanism | `9b95469a`…`edd6b551` |
| §11 Already-voted exclusion | `PBDIGIT-62` — fail-open guard fixed + regression test | committed |

**Anchor Track B to `PBDIGIT-48`'s existing four-bucket classification.** Re-running that inventory from scratch would discard evidence and re-open a closed decision.

## Sequenced slices

### Slice 1 — Master Matrix *(AUTHORISED; investigation only)*

**Ordering is mandatory and must not be inverted:**

```
business invariant → domain meaning → authoritative authority → application capability
→ application authorization → interface/HTTP entry → persistence → test
```

**Determine test *intent* before interpreting its *failure*.** Never start from a PHPUnit message and reason backwards into a business explanation.

**Per-test mapping** — the 30 columns in the directive, plus one added by the Product Owner:

> **`Business Decision Ownership`** — `Domain` · `Application` · `Policy/Authorization` · `Infrastructure` · `Interface/Projection` · `Unknown`.
>
> **Why it matters:** several mechanisms can answer *"can this happen?"* while owning entirely different responsibilities — lifecycle answers *what state*, capability answers *what is legitimate in that state*, policy answers *is this actor permitted*, the use case *executes*, persistence *stores*, the UI *projects*. **This column is the foundation for Slice 5**: it turns the legacy question from *"does this reference `status`?"* into *"does this code make a decision that belongs to the Election domain authority, or merely transport, persist or display it?"*

**Non-equivalent legacy categories — never collapse:** field exists · fixture writes it · production reads it · **production decides from it** · persisted/displayed only · explicitly retained by decision.

**Evidence discipline:** separate OBSERVED FACT · INTERPRETATION · HYPOTHESIS · CONCLUSION. Where a mechanism was not measured, write **"Mechanism not yet established."** **"Undetermined" is a preferred outcome over a forced classification.**

**Carry forward, do not re-derive:** `PBDIGIT-48`, `ElectionCreationTest`, clusters 8c, 8d, 8a import-preview. Reference the evidence; reproducing the investigation destroys traceability.

**Coverage is a business question**, not a test count: for each capability — allowed path · forbidden path · correct state · correct actor · **server-side enforcement** · negative case · persistence where it matters.

**Cluster 6 is intentional migration evidence, not defects.**

#### ⛔ Slice 1 is investigation and classification only — NOT remediation

**Slice 1 is investigation and classification only, not remediation.** Findings such as defective fixtures, production defects, legacy consumers, missing coverage, or architectural smells **must be recorded** in the matrix and the appropriate finding registers, but **must not be repaired during Slice 1**. No code, test, fixture, production, or legacy-migration changes are authorised by Slice 1. **Remediation begins only in a later authorised slice, after the Product Owner review gate.**

**The prohibition applies even — especially — when the repair looks obvious.** "This fixture clearly uses the old `status` field" is a *finding*, not a licence.

**Operating rule when something actionable is discovered:**

```
DISCOVER → UNDERSTAND BUSINESS INTENT → IDENTIFY DECISION OWNER
        → MEASURE ACTUAL MECHANISM → CLASSIFY → RECORD FINDING → STOP

                       ⛔ NO REPAIR IN SLICE 1
```

**Worked example of the required output shape:**

| Field | Value |
|---|---|
| Observation | fixture writes legacy `status` |
| Business intent | test intends `SetupAdministration` |
| Authoritative authority | `ElectionLifecycle` |
| Actual derived state | `draft` |
| Classification | fixture does not establish the intended state |
| Impact | test does not exercise the intended business scenario |
| Candidate remediation | recorded for a later authorised slice |
| **Action** | **NO CHANGE** |

**The Slice 1 contract, stated once:**

> **Slice 1 answers:** what does the Election test estate claim to verify, what business decision does each test protect, who owns that decision, what mechanism actually executes it, and where are the verification gaps?
>
> **Slice 1 does not answer by changing code:** how should we fix those gaps? — that belongs to subsequent authorised slices.

*(This is a programme-level separation of concerns, mirroring the separation of responsibilities the matrix is mapping inside the system.)*

#### ⚠️ The columns are not the point — the lens is

**30 columns must not become a bureaucratic exercise.** A row is valuable only if it answers:

> **What business rule is this test supposed to protect, who owns that decision, and does the implementation actually exercise that authority?**

| The naive question | The architectural question |
|---|---|
| Is the election in Counting? | What does **`ElectionLifecycle` derive**? |
| Can the operation occur? | What **capability/invariant** governs it? |
| May this officer perform it? | What **authorization/policy** governs the actor? |
| Can the HTTP request reach it? | What **application/interface gates** exist? |
| Does the operation happen? | Which **use case/service** executes it? |
| Is the result stored? | Which **persistence responsibility** owns storage? |
| Does the UI show it? | Is the UI **projecting** authoritative information? |
| Is `status` involved? | Is it **making a business decision**, or merely transported/stored/displayed? |

**A column filled in without answering its architectural question is noise.** Prefer fewer rows reasoned through to thirty columns completed mechanically.

**Gate:** produce matrix, coverage summary, failure classification, legacy-observation register, architectural findings, gaps, open questions · run the 18-item self-audit · then state **"SLICE 1 COMPLETE — AWAITING PRODUCT OWNER REVIEW"** and stop.

### Slice 2 — Finish failure classification (~95 unclassified + GracePeriod)
Evidence-first protocol, unchanged: intent → actual lifecycle state → trace → rejecting boundary → classify. **Repair only after classification.**
**First item:** `ElectionGracePeriodUITest` — measure the request-time Gate subject before hypothesising.

### Slice 3 — Fixture rehabilitation (Track A)
Repair fixtures that assert a lifecycle state they never created. **Each repaired fixture asserts its resulting `ElectionLifecycle` state before asserting behaviour** — the guard that closed 8d and 8a.

### Slice 4 — Production defects, individually authorised
Each one: proven mechanism → smallest repair → RED→GREEN evidence → separate commit. **No batching.**

### Slice 5 — Track B: migrate remaining legacy business-decision consumers
From `PBDIGIT-48` Bucket 1 only. **Bucket 2 (demo) stays until `PBDIGIT-59`; Bucket 3 (display) stays until field retirement.**

### Slice 6 — New coverage (§6, §7, §8, §10, §12–15)
**Largest and least-defined scope.** Lifecycle state matrix · capability matrix · server-side enforcement · journeys A–G · entry resolution · voting integrity · audit determinism.
**Must be authorised as its own work package** — it is building, not repairing.

### Slice 7 — Sweep, mutation/negative testing (§17–18), closure review (§19)

## Risks I would flag before starting

1. **Slice 6 is a scope expansion, not a cleanup.** The plan's 8–10 day estimate looks optimistic against measured throughput: four clusters (~41 failures) consumed one long session, and ~95 remain unclassified. **Recommend estimating Slices 2–4 from Slice 1's matrix rather than in advance.**
2. **Green is not the target.** The plan says this; the failure modes proved it — `withoutMiddleware()` hid a production 500, and cluster 6's red tests *are* `PBDIGIT-48`'s completion signal.
3. **Recognition is not classification.** Four plausible hypotheses dissolved under measurement this cycle. Every classification needs a measured mechanism.
4. **`full_discovery.md` is untracked.** 28 KB of governing methodology outside version control. **Committing it is a PO governance decision**; placement derives from `php scripts/doc-placement.php`.

## Definition of Done

The plan's own (§"The final Definition of Done"), which this plan does not restate or weaken. **Green tests are one criterion among several.**

## Outstanding obligation carried in

Developer guides for `developer_guide/http/` and `developer_guide/models/` — production code changed in both areas and the guides were deferred, not written.

## Traceability

`docs/publicdigit/backlog/full_discovery.md` · `PBDIGIT-48` · `PBDIGIT-58` · `PBDIGIT-62` · `ADR_20260807_1500` · handover in `.claude/sessions/2026-08-07.md` §HANDOVER

---

## SLICE 1 — STEP 1 (scope determination) — STARTED 2026-08-08

**The plan requires scope be determined before any matrix is built** (*"Do not produce a partial matrix and call it complete"*). That step ran first, and its result materially changes the programme's shape.

### 🔴 The estate is ~4.5× larger than the working set measured all session

| Measure | Value |
|---|---|
| Test files referencing Election | **491** |
| Test methods in those files | **~3,688** |
| Files in the 5 suites measured this session | **180** |
| Tests in those suites | **826** (668 passed / 146 → 105 failed / 12 incomplete) |

**The `146 → 105` trajectory describes a subset, not the Election estate.** Every failure figure quoted in this programme so far — including the handover — is scoped to those five suites.

**Distribution beyond the measured set:** `tests/Feature` (74) · `tests/Unit/Domain/Election/Security` (35) · `tests/Feature/Demo` (18) · `tests/Unit/Models` (17) · `tests/Unit/Application/Election/Security` (17) · `tests/Unit/Services` (8) · `tests/Feature/Membership` (8) — none of which were in the measured suites.

### What this means for Slice 1

1. **The 30-column matrix over ~3,688 tests is not a single-session artifact.** Scoping it as such would guarantee the partial-but-authoritative outcome the plan forbids.
2. **A scope decision is required from the Product Owner before matrix construction**, e.g.:
   - **(a)** the 826-test constitutional core already measured — coherent, bounded, already partly classified; or
   - **(b)** all 491 files — complete, but a multi-session programme needing its own slicing; or
   - **(c)** a capability-first cut: matrix the tests covering the constitutional invariants and journeys, and inventory the rest by file without per-test mapping.
3. **"491 files reference Election" is an upper bound, not a consumer count** — the same distinction that reduced 46 legacy sites to a much smaller true set. Many will reference Election incidentally.

### Status

**SLICE 1 STEP 1 COMPLETE — SCOPE DECISION REQUIRED BEFORE MATRIX CONSTRUCTION.**
**Slice 1 is NOT complete.** No matrix rows have been produced. No code, test or fixture changed.
