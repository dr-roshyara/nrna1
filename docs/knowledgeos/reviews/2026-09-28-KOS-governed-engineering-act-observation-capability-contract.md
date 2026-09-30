# EKS Capability Contract — Governed Engineering Act Observation

**Date:** 2026-09-28. **Status:** DRAFT CONTRACT — not implemented, not authorized. Read-only
design artifact, matching the same discipline as the earlier Conformance Oracle contract
(`2026-09-28-KOS-conformance-oracle-capability-contract.md`).

> ⛔ This document specifies a capability contract only. No code is written here.

---

## 1 · Why this capability, precisely

This is the single most-corroborated real problem found across this whole investigation —
`EKS-07` alone documents **six independent, first-hand incidents**: engineering work committed
without traceable process attribution, a real commit misattribution (one session's uncommitted
edit silently swept into another session's commit, `196aa607e`/`c821abece`), and repeated
identifier/numbering collisions traced to the same root cause. Nothing in the corpus disputes
this; it is the best-evidenced gap this session has found.

**Ground-truth check performed before writing this contract** (not assumed): work items in
`workflow-state.php` carry a `--scope=<s>` value that is a **free-text label, not a structured
file/path list** — confirmed by direct inspection. There is no machine-readable "expected scope"
to check a commit's actual diff against today. **This means the capability cannot start by
verifying scope-to-diff correctness — only citation-to-governance-record correctness.** That is a
real, disclosed limit on what a first version can claim, not a design flaw.

**A second ground-truth check, this one a genuine worked example, found by accident while writing
this contract:** commit `0727c9342` (this session's own `D-1` implementation) states in its own
body, verbatim: *"No product story applies (KnowledgeOS engineering, not PublicDigit product
work)"* — a **deliberate, justified non-citation**, matching this repo's own `CLAUDE.md` commit
convention ("chore/MAINT... say explicitly that no story applies"). The same commit *does*
informally reference `KOS-CONTRACT-NEUTRALITY-001` via a document-path citation in its body, not a
formal ID. **This single real commit already falsifies a plain `YES`/`NO`/`AMBIGUOUS` output
vocabulary** — it is neither confidently associated with a formal work-item ID, nor an
unexplained gap, nor genuinely ambiguous. The vocabulary in §4 is shaped by this finding, not
designed in the abstract.

## 2 · Capability statement

> **Governed Engineering Act Observation: given a real commit, determine whether there is
> sufficient independent evidence to associate it with a specific, governed work item — and
> distinguish a deliberate, justified non-citation from a genuine, unexplained gap.**

## 3 · Input

- A **commit** (hash, timestamp, author, full message — subject and body, diff stat).
- The **candidate work-item reference**, if any, extracted from the commit message by pattern
  (a formal ID like `PBDIGIT-nn`, or an informal reference like a cited document path or work-item
  name — both are real, evidenced forms, per §1's worked example).
- **Independent governance records** to check the candidate against — reused, not
  reimplemented: `IdentifierIntegrity`'s existing `GovernedRegisterMap.php` for formal-ID
  registers, plus the relevant governance ledger for the cited track (`workflow-state.php`'s
  grants for `PublicDigit`/product work; the `GN-nn` ruling ledger or equivalent for
  `KnowledgeOS`-track work) — **this capability does not invent a new register lookup; it
  consumes `CAP-001`'s existing one wherever the citation's register is one `CAP-001` already
  knows about, per `ES-005.4`'s reuse-before-build discipline.**

## 4 · Output

Exactly one of five states per commit — deliberately not three, because a real commit already
falsified the simpler vocabulary (§1):

| State | Meaning | Evidence required |
|---|---|---|
| `CONFIRMED` | Cites a work-item reference that resolves to a real, currently-authorized governance record, **and** at least one independent corroborating signal exists (timestamp inside the record's active window, matching branch/session where recorded) | Positive, checked |
| `CONTRADICTED` | Cites a reference that resolves to a real record, **but** evidence conflicts (timestamp outside the authorized window; the record was already closed; the citation points to a different work item than the one active at commit time) | Positive, conflicting |
| `UNDERDETERMINED` | Cites a reference that resolves to something real, but **no independent evidence exists beyond the citation itself** — the honest, expected-to-be-common default given today's weak-evidence reality (§1) | Present, insufficient |
| `DECLARED_NO_STORY` | The commit **explicitly states** no work item applies, with a stated reason — matches this repo's own commit convention exactly, and is a clean, non-risky outcome, never conflated with `UNCITED` | Explicit, honest |
| `UNCITED` | No work-item reference of any recognizable form, **and** no explicit "no story applies" declaration either | Absence, unexplained — the state that most needs human attention |

**No sixth, silent state.** A commit this capability cannot parse at all is `UNCITED` with a
`FIXTURE_ERROR`-class note (reusing the Conformance Oracle's own failure-classification
discipline), never silently dropped from the count.

## 5 · Invariants

- **Never trust the citation alone as proof.** `CONFIRMED` requires independent corroboration,
  not just a parseable reference — the same "never trust the thing being checked as its own
  proof" principle as the Conformance Oracle (§5 of that contract), applied here to provenance
  instead of semantic facts.
- **Absence of evidence is never `CONFIRMED`.** Directly reusing PKS's own verified `AP-8`
  fail-closed discipline: an unresolvable citation defaults to `UNDERDETERMINED` or `UNCITED`,
  never silently upgraded.
- **The capability observes and classifies; it never mints, corrects, or amends** a commit
  message, a governance record, or a work item. Same kernel-classifier principle confirmed
  independently across Cohesion, the governance engine, PKS, and the Observation Runtime
  (`AssessmentService`) this session.
- **`DECLARED_NO_STORY` and `UNCITED` are never merged.** This is the one invariant this contract
  exists specifically to enforce, given §1's worked example — collapsing them would erase exactly
  the distinction that makes the measurement meaningful.

## 6 · Supported / unsupported / indeterminate cases

- **Supported:** any commit whose message contains a parseable reference in a known form
  (`PBDIGIT-nn`, a cited document path resolving to a real file, a cited `GN-nn`/`F-LOG-nnnn`/
  `KOS-*` identifier resolving to a real governance record).
- **Unsupported, explicitly:** verifying that the commit's actual file diff matches the work
  item's intended scope — this requires structured scope data that does not exist today (§1). Not
  built speculatively; characterized here as a future capability's precondition, not invented.
- **Indeterminate:** a commit citing a reference in an unrecognized or ambiguous form (e.g. a
  bare number with no prefix) — reported as `UNDERDETERMINED`, never guessed.

## 7 · Provenance

Each classification records: commit hash, extracted reference (verbatim), which governance
register was checked and its resolution, the specific corroborating or conflicting fact found (if
any), and the final state. Matches `INV-ATTR-1`/`INV-ATTR-2`: this capability *records* what it
found; it does not *attest* that the commit is correct, complete, or trustworthy beyond its own
narrow check.

## 8 · Adapter boundary

A read-only script consuming `git log`/`git show` output and the existing governance registers
listed in §3. It writes nothing back to git, to `workflow-state.php`, or to any governance ledger
— pure observation, matching the Observation Runtime's own established "runtime executes, never
governs" boundary (`developer_guide/engineering_observations/11_live_feedback_trigger.md`,
verified this session).

## 9 · Test strategy

- **RED first, against real, already-known commits from this session's own `D-1` work** (not
  synthetic fixtures — these are real, dated, already-understood commits):
  - `0727c9342` → expected `DECLARED_NO_STORY` (the worked example in §1).
  - A commit citing a real, currently-open `PBDIGIT-nn` story (if one exists in `git log`) →
    expected `CONFIRMED` or `UNDERDETERMINED`, depending on whether independent corroboration
    beyond the citation is actually available.
- **GREEN:** implement the five-state classifier; confirm both cases above resolve correctly.
- **Negative test (the discrimination proof, same discipline as the Conformance Oracle's own
  negative test):** construct a commit whose message cites a work-item reference known to have
  been closed *before* the commit's timestamp — confirm `CONTRADICTED` is correctly produced, not
  silently passed as `CONFIRMED`. This directly guards against "a discriminator that's never
  returned anything but the expected value hasn't been shown to discriminate"
  (`KNOWLEDGEOS-RESEARCH-STATE.md`'s own caution, already applied once this session).
- **Real-corpus validation, the actual experiment the redirect proposed:** run the classifier
  against the most recent 100–200 real commits in this repository's own `git log` and report the
  real distribution across all five states — not a synthetic estimate. This is the smallest,
  cheapest version of the "examine 1,000 real engineering changes" measurement, immediately
  runnable against real, existing history.

## 10 · What this contract does not decide

- Whether `CONFIRMED`'s bar (one independent corroborating signal) is strict enough — an
  implementation-tuning question, deferred until real-corpus results are in hand.
- Whether this capability should eventually gain scope-to-diff checking once structured work-item
  scope data exists — explicitly out of scope until that precondition is met (§6).
- Where this capability's code lives, or whether it becomes `CAP-00N` in PKS's catalog — a
  governance question, following the same evidence-threshold discipline `CAP-001`'s own README
  already states (*"No CAP-002... until this record holds roughly 20–30 rows"*).

---

**Traceability:** `EKS-07-multi-process-coordination.md` (six real incidents, the evidentiary
basis for this whole capability) · `IdentifierIntegrity/README.md` (`GovernedRegisterMap.php`,
reused not rebuilt; the `PMR-10`/`AP-8` fail-closed discipline) · commit `0727c9342` (the worked
example that shaped §4's vocabulary) · this repo's own `CLAUDE.md` commit-ID convention · the
Conformance Oracle contract (shared invariant language, §5/§9) · `INV-ATTR-1`/`INV-ATTR-2` ·
`KNOWLEDGEOS-RESEARCH-STATE.md` (the discrimination-proof caution).
