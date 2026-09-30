# `KOS-CONTRACT-NEUTRALITY-001` — D-1 governance decision preparation

**Date:** 2026-09-28 · **Purpose:** give the human/PO-ARB a clean factual basis for the D-1
formal-incorporation decision. **This document decides nothing and recommends neither
option.**

> ⛔ Nothing modified. No code, test, `expected.json`, governance, or architecture document
> changed by this pass. `git status --short scripts/ tests/ .claude/runtime` clean, confirmed
> before writing this.

---

## 1 · What is already implemented

Commits `0727c9342` (code+tests) and `52783467a` (docs), both on branch
`knowelegeos-modelling`, not pushed, not incorporated into `expected.json`.

- A new `L3` fact kind, `IndeterminateBehaviourReference` (zero fields — determinability is
  fixed by construction, no field is needed to store it).
- One new, unconditional `EdgeRules` branch: any instance of this type is excluded with
  reason `NotDeterminable`, checked before every other rule.
- Both adapters emit it: PHP for `$this->$method()` (a variable method name where the
  extractor previously fell silent), Python for `getattr(self, name)()`.
- **Evidence, executed, not asserted:** 188/188 tests green (182 baseline + 6 new). RED
  confirmed by reverting the implementation and re-running before restoring it. Cross-language
  output is byte-identical, including the exclusion record. Real-corpus validated: one genuine
  site in this project's own code (`DebugVoterSlug.php`), three genuine sites in the real
  Python standard library (`pdb.py`, `pydoc.py`×2), all spot-checked by reading the actual
  source lines, not merely counted.

## 2 · What is currently inconsistent

**Narrower than "implementation vs. specification disagree."** Checked directly, this pass:
`expected.json._variant_decisions_pinned.intra_class_calls` (unchanged since 2026-08-16,
confirmed by `git log`) already states, in prose, that `$this->$name()` and
`call_user_func([$this,'m'])` are *"EXCLUDED AS NOT DETERMINABLE... a real dependency may
exist, but the analysis cannot determine the target reliably."* **The implemented behavior
does not contradict this text — it fulfils it for the first time.** Before this slice, the
code silently violated this pinned sentence (the construct was invisible, not
excluded-with-reason); now it doesn't.

What the pinned text does **not** yet say, and what "incorporation" would add: **how** the
exclusion is represented — the `IndeterminateBehaviourReference` vocabulary, its no-field
schema, and the specific `EdgeRules` rule. That representation detail exists today only in
code and in this session's research reports, not in the governed contract text. Separately,
and independently of any text change: `G-KOS-CONTRACT-ARTIFACT-UPDATE` (confirmed still
`AUTHORIZED`/`UNEXERCISED`) currently **blocks** adding any golden-fixture expected-evidence
entry for this construct, and that block is explicitly conditioned on incorporation
happening first (Amendment 1, §A1.2: *"until [incorporated] into the authoritative
specification"*).

## 3 · What formal incorporation would change

Exactly two artifacts, both requiring a further, separate Governance act (this document does
not touch either):

- `scripts/observations/examples/lcom4/expected.json` — an addition to
  `_variant_decisions_pinned.intra_class_calls` (or an adjacent pinned key) naming the
  `IndeterminateBehaviourReference` representation and its schema rule, per the incorporation
  brief's own worded proposal (`2026-09-27-...-V3-incorporation-decision-brief.md` §1).
- The `G-KOS-CONTRACT-ARTIFACT-UPDATE` gate's status for this specific construct — would move
  from *"evidence production blocked"* to *"evidence production permitted, conformance
  assertion still separately gated"* (the same two-tier structure Amendment 1 already
  established for every other category).

**Nothing else.** No third artifact was found to require change.

## 4 · What would remain unchanged

- **`L4` behaviour** — the `EdgeRules` rule already exists and already runs; incorporation
  is a text act, not a code act.
- **`L5`/`LCOM4`** — metric-neutral by construction and by execution (the dedicated
  metric-neutrality test, and the real-corpus check on `DebugVoterSlug.php`); nothing here
  changes that.
- **PHP/Python parity** — already byte-identical; a contract-text change does not touch code.
- **`D-4`** — explicitly closed, no genuine occurrence found in either corpus
  (`2026-09-28-KOS-D4-characterization-closure.md`); untouched by this decision.
- **`D-5`** — depends entirely on `D-4`; untouched.
- **Unrelated architecture** — Architecture D, the parsing-architecture selection, the
  `L1`–`L5` stratification: none of this is implicated by naming one new `L3` fact kind.
- **Unrelated application code** — nothing in `app/` is touched by either option.

## 5 · Risks of incorporating now

- **Semantic risk:** low. The semantic rule being formalized (`$this->$m()` excluded as
  not-determinable) was already decided 2026-08-16 and is not being changed — only its
  representation is being named.
- **Regression risk:** low, and bounded — confirmed by 188/188 green and by the fact that no
  existing golden fixture contains this construct (so no existing conformance assertion is
  touched). The one residual gap: Python real-corpus validation for `D-1` used the CPython
  standard library, not a large, independent Python corpus outside what this investigation
  has already used throughout — not a new risk, the same standing caveat every prior report
  in this chain has carried.
- **Governance/specification risk:** the `self::`/`static::` scope question this session's
  own schema-sufficiency pass flagged (does `D-1` cover only `$this->$m()`, or also
  `self::$m()`/`static::$m()`?) remains genuinely open. Incorporating text that describes only
  the `$this->` case, while the underlying architecture-of-record language ("dynamic own-
  behaviour references") reads more broadly, risks a contract-text/architecture-intent
  mismatch if that scope question is answered "yes" later. This is real and unresolved — not
  hypothetical.
- **Repository/process risk:** none evidenced beyond the standing, already-known session-log
  and developer-guide Definition-of-Done items, which this document does not treat as
  incorporation-blocking (per the human's own direction).

## 6 · Risks of NOT incorporating now

- **Evidence risk:** the artifact-update gate stays closed for this construct indefinitely;
  no golden-fixture expected-evidence can be authored for it, so `D-1`'s correctness can
  never be asserted as part of formal conformance — only demonstrated in ad hoc unit tests,
  as it is today.
- **Drift risk:** the longer implemented code and governed text diverge (even non-
  contradictorily, as established in §2), the more a future reader of `expected.json` alone
  — without this session's reports — would not learn that `D-1` has already been solved,
  and could re-open work that is, in fact, done.
- **No metric or regression risk** is created by waiting — nothing about *not* incorporating
  changes any currently-passing test or any currently-computed value.

## 7 · What the human/PO-ARB would actually be approving, in plain language

Not a new rule. The rule — *"when the system can't tell which method a call reaches, say so
explicitly instead of staying silent"* — was already decided over a month ago. **What's being
approved is whether to write down, in the project's official rulebook, the specific way the
system now keeps that promise** — so that a future reader of the rulebook alone, not just of
the code, can see the promise is being kept. Declining to approve it right now doesn't undo
the fix or make it wrong; it just means the rulebook stays one paragraph behind what the
software actually, correctly, does.

## 8 · Decision structure — presented, not chosen

### Option A — formally incorporate D-1 now

- **What changes:** `expected.json`'s pinned text gains a paragraph naming the
  `IndeterminateBehaviourReference` representation; the artifact-update gate opens for this
  construct specifically.
- **What remains:** everything in §4 — no code, no metric, no parity, `D-4`/`D-5` untouched.
- **Consequence:** golden-fixture expected-evidence for `D-1` becomes authorable; `D-1`
  becomes assertable as formal conformance, not only as unit-test behavior.
- **Evidence supporting this being safe to do now:** 188/188 tests, executed RED→GREEN,
  cross-language parity executed and confirmed, two independent real-corpus validations.

### Option B — leave the implementation present, do not incorporate yet

- **What changes:** nothing — the code stays exactly as committed.
- **What remains:** `D-1` continues to work correctly in every test and in real code; the
  artifact-update gate stays closed for this construct; `expected.json` stays one paragraph
  behind what the code does (non-contradictorily, per §2).
- **Consequence:** no golden-fixture conformance evidence can be authored for `D-1` until a
  later incorporation act.
- **Evidence supporting this being reasonable to choose:** the open `self::`/`static::` scope
  question (§5) is genuinely unresolved, and some readers may prefer resolving it before
  writing incorporation text, to avoid a second amendment later.

Neither option is ranked. Both are consistent with everything established this session.

---

**STOP after this document.** No `D-4`, `D-5`, `L3` parity, `LCOM4`, or general Python
research reopened. No implementation performed. No governance document modified.

**Traceability:** `2026-09-28-KOS-CONTRACT-NEUTRALITY-001-V3-governance-decision-brief.md` ·
`2026-09-28-KOS-D1-implementation-results.md` · `2026-09-28-KOS-D1-implementation-contract.md`
· `2026-09-28-KOS-CONTRACT-NEUTRALITY-001-D1-schema-sufficiency-pass.md` (the `self::`/
`static::` open question, §5 here) · `2026-09-27-...-V3-incorporation-decision-brief.md` §1 ·
`2026-08-19-...-v3-decisions-registration.md` + Amendment 1 (`G-KOS-CONTRACT-ARTIFACT-UPDATE`
gate language) · `scripts/observations/examples/lcom4/expected.json` (read, not modified,
`git log` confirming no change since 2026-08-16) · commits `0727c9342`, `52783467a`.
