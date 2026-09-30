# KOS — Minimal Evidence-Fusion Model for Governed-Act Observation

**Date:** 2026-09-29. **Phase:** formal model derivation, not implementation.

> ⛔ No code written. This document derives the smallest formal model the evidence in
> `2026-09-28-KOS-governed-agent-observation-theory-research.md` (§1–§16) actually forces — it
> does not propose an architecture beyond what that evidence justifies.

**Research question this answers:** what is the smallest formal evidence model capable of
correlating Activity + Grant + Transition + Work Item + Actor without confusing correlation with
proof?

---

## 1 · Why fusion, not a single unit — restated precisely

§16's test falsified *"transition is the observable unit"* with a concrete, disclosed failure: a
stale, never-closed transition (role `architecture`) would have been wrongly bound to a real
`implementation`-role commit, 24 days later. §15 showed grants and transitions are structurally
and semantically distinct — no shared field joins them. **No single record type is sufficient.**
The only defensible model treats `Activity`, `Grant`, `Transition`, and `WorkItem` as genuinely
separate domain concepts (per the pasted redirect's own DDD insight, which this document adopts
because the evidence already supports it, not because it is elegant) and defines correlation as an
explicit, auditable fusion over independent evidence — never a lookup, never a default.

## 2 · Entities (kept separate, per §11's own A/B/C discipline — these are what exists, not what's proposed)

| Entity | Real fields (confirmed, this session) | What it answers |
|---|---|---|
| `Activity` | commit hash, human-account author, `Co-Authored-By` model variant, timestamp, message text, diff | *What actually happened?* |
| `Grant` | `grantId`, `authority`, `humanActRef`, `status`, `scope`, `registeredBy` — **no session field, ever** (§15) | *What work is permitted?* |
| `Transition` | `type`, `session`, `role`, `predecessor`, `executionContext`, `recordedBy`, `seq` — dates only in prose | *What happened to the governed session?* |
| `WorkItem` | ledger name, `workflow` type, `roles` list | *Which governance record owns this?* |

No fifth entity is introduced. `Actor` is deliberately **not** modeled as a first-class entity with
its own identity — `INV-ATTR-1`/`INV-ATTR-2` already establish that self-declared identity is
evidential only, and this document does not attempt to reopen that (per the redirect's own Task 8
instruction, still binding).

## 3 · Evidence predicates — the vector, each independently computable and separately falsifiable

| Predicate | Computed from | Value domain |
|---|---|---|
| `Cites(a, ref)` | text search: does `a`'s message contain a `grantId`, ledger name, or document path? | `TRUE` / `FALSE` |
| `TemporalMatch(a, t)` | is `a`'s timestamp within a defensible window of `t`'s inferred date, **and** is `t` not yet `COMPLETE`/`CANCEL`ed at that time? | `TRUE` / `FALSE` / `INDETERMINATE` (no date on `t`) |
| `TemporalMatch(a, g)` | same, against `g`'s `humanActRef` date | `TRUE` / `FALSE` / `INDETERMINATE` |
| `RoleConsistent(a, t)` | does `a`'s apparent work-type match `t.role`? | `TRUE` / `FALSE` / `INDETERMINATE` (no independent read on `a`'s role) |
| `ScopeConsistent(a, g)` | does `a`'s diff avoid `g`'s explicit *"Does NOT authorize"* text? | `TRUE` / `FALSE` / `INDETERMINATE` |
| `WorkItemMatch(a, w)` | does `a`'s citation resolve to `w`'s real ledger? | `TRUE` / `FALSE` |

Each predicate is **three-valued**, not boolean — reusing the corpus's own established discipline
(never collapse "not proven" into "false") rather than inventing a new truth system. `INDETERMINATE`
is the honest default when the underlying record simply lacks the field to check (e.g. `§15`'s
finding that grants never carry a session — `TemporalMatch(a,g)` is frequently `INDETERMINATE` by
schema, not by failure).

## 4 · The fusion procedure — deterministic, auditable, no black box

```
Fuse(a, w):
  if WorkItemMatch(a, w) = FALSE:
      return UNCITED                                   -- no evidence path attempted at all

  contradictions = { p in {RoleConsistent, TemporalMatch, ScopeConsistent}
                      : p(a, ·) = FALSE }
  if contradictions ≠ ∅:
      return CONTRADICTED, evidence = contradictions    -- §16's exact failure mode, now a named state

  supporting = { p in {TemporalMatch, RoleConsistent, ScopeConsistent}
                 : p(a, ·) = TRUE }
  if |supporting| ≥ 1:
      return CONFIRMED, evidence = {Cites} ∪ supporting  -- citation ALONE is never sufficient (§8)

  return UNDERDETERMINED, evidence = {Cites}              -- citation present, nothing else resolves
```

**Worked validation against the real test case (§16, commit `0727c9342`):**
`WorkItemMatch` = `TRUE` (informal document-path citation resolves). `TemporalMatch(a, t)` = `FALSE`
(the only candidate transition is 24 days stale and never closed — this is exactly why §16 called
it a contradiction risk, not silence). Under this procedure, `RoleConsistent(a,t)` also evaluates
`FALSE` (transition role `architecture` ≠ activity's `implementation` shape) →
**`contradictions ≠ ∅` → the fusion procedure correctly returns `CONTRADICTED`, not `CONFIRMED` and
not silence.** This is the single most important property this model has that §16's naive
"nearest transition" rule lacked: **it converts the exact failure §16 found into a correctly
classified, named outcome, rather than a silent wrong answer.**

## 5 · Formal predicates and the invalid implications (extends §7, does not replace it)

```
Cites(a,ref)                      -- a's own text names ref
TemporalMatch(a,x)                -- a is proximate to x, x not yet closed
RoleConsistent(a,t)                -- a's role shape matches t.role
ScopeConsistent(a,g)               -- a's content respects g's negative scope
Confirmed(a,w)                     -- Fuse(a,w) = CONFIRMED
```

**Confirmed NOT to hold, now with §16's own evidence, not just argument:**

- `Cites(a,ref) ⇒ Confirmed(a,w)` — **false**, this is exactly the five-state contract's own
  `UNDERDETERMINED` case, and §4's procedure enforces it structurally (citation alone routes to
  `UNDERDETERMINED`, never `CONFIRMED`).
- `TemporalMatch(a,t) ⇒ Confirmed(a,w)` — **false, demonstrated, not merely asserted**: §16's own
  test showed temporal proximity to a stale, wrong-role transition would produce a **wrong**
  answer, not just an unconfirmed one. This is the specific implication the redirect asked this
  model to guard against, and it now has a real counterexample on record, not just a caution.
- `Grants(g,w,scope) ⇒ ∃t. TemporalMatch(t,g)` — **false** (§15: 16 of 17 real unmatched grants
  were legitimately not expected to have one).

## 6 · Where ML fits — bounded precisely, per the redirect's own architecture

ML has **no role** in §4's procedure itself — every predicate there is deterministic and
inspectable. The **only** legitimate entry point is *after* `Fuse` returns `UNDERDETERMINED` **and**
there is more than one plausible candidate `w` to check `a` against (a case not yet encountered in
this session's small worked examples, but real once this is run at the "examine 1,000 commits"
scale the original redirect proposed):

```
UNDERDETERMINED, multiple candidate w's
        ↓
ML candidate ranking (e.g. text-similarity between a's diff/message and each g.scope)
        ↓
ranked hypothesis list — confidence scores attached
        ↓
STILL UNDERDETERMINED — routed to human/governance review, never auto-promoted
```

**ML never outputs `CONFIRMED`.** Its output is a ranked hypothesis over an already-`UNDERDETERMINED`
case — evidence about which candidate is more plausible, never authorization or proof. This is the
same boundary PKS's own rule states directly (*"AI surfaces governance questions; it does not
resolve them"*), applied here without modification.

## 7 · What this model does not decide

- Whether `|supporting| ≥ 1` is the right threshold (vs. requiring 2) — a tuning question, deferred
  until real-corpus volume is available to test it against.
- Whether `Actor` should ever become a first-class entity — explicitly out of scope, per
  `INV-ATTR-1`/`INV-ATTR-2` (§8 of the theory document, unchanged).
- Any implementation location, language, or storage — this is a logical model, not a build.

---

## 8 · `Fuse` applied to real commits, 2026-09-29 — including a self-referential case

Rather than a generic sample, the highest-value available test case surfaced directly:
**`6b983bc12`, an independent PO/ARB-commissioned verification pass (different process,
`Co-Authored-By: Claude Opus 5 (1M context)`), examined this same `KOS-CONTRACT-NEUTRALITY-001`
ledger today and found a real anomaly in this session's own prior work.** Reported here plainly,
not defensively — this is exactly the kind of case the model exists to catch.

**Finding (independently produced, not by this model):** commit `9f83a369c`
("formally incorporate `D-1` into the `expected.json` contract," this session, 2026-09-28) added a
new pinned-decision key to `expected.json`. **No grant authorized this, and the one grant naming
`D-1`** (`G-KOS-CONTRACT-V3-TARGETED-CONTINUATION`, scope: *"TARGETED CONTINUATION of V-3...
D-1 (representation of the dynamic-member-access fact)..."*) **explicitly states, verbatim: "does
not authorize:... contract modification (`expected.json` or the pinned decisions)."** A sweep of
all 40 grants in both work items found none permitted it. Classified by the independent verifier
as *"GOVERNANCE — RECORDING GAP, NOT A BREACH OF SUBSTANCE"* — the underlying human decision was
real (Option A, made by the human), the authorization paperwork was never registered before the
act, and nothing is claimed to be wrong about the decision itself or in need of reverting.

**Applying `Fuse(9f83a369c, KOS-CONTRACT-NEUTRALITY-001)`:**
`WorkItemMatch` = `TRUE`. `ScopeConsistent(a, g)` for `g = G-KOS-CONTRACT-V3-TARGETED-CONTINUATION`
= **`FALSE`** (the grant's own text explicitly forbids the exact act performed) →
**`contradictions ≠ ∅` → `Fuse` returns `CONTRADICTED`.** This matches the independently-verified
ground truth exactly — the model would have caught this immediately, without waiting for a
separate commissioned audit.

**Applying `Fuse(0727c9342, KOS-CONTRACT-NEUTRALITY-001)`** — the adjacent `D-1` *implementation*
commit, same day, same author, same topic: the independent verifier's own text states the
implementation activity ran under a properly-scoped grant and was **not** flagged (*"Everything
beyond evidence reconciliation... ran under SEPARATE grants, each carrying a verbatim PO/ARB
humanActRef"*). `ScopeConsistent(a,g)` = `TRUE` for the same grant, which does authorize *"D-1
(representation of the dynamic-member-access fact)"* as an in-scope item. → `Fuse` returns
**`CONFIRMED`** (or at minimum, not `CONTRADICTED`) — a materially different, correct outcome from
its near-identical sibling commit, checked against the correct record (the grant, not the stale
transition §16 warned against).

**What this real case adds that the earlier worked example didn't have:** a genuine refinement
need. **`CONTRADICTED` currently conflates two different things** — a recording gap (real
decision, unregistered paperwork, `9f83a369c`'s actual disposition) and a genuine substance breach
(the decision itself was wrong). The model as specified in §4 cannot yet tell these apart; both
route to the same state. **Proposed refinement, not yet adopted:** split `CONTRADICTED` into
`CONTRADICTED_RECORDING` (a human decision plausibly exists but isn't registered as an amendment)
and `CONTRADICTED_SUBSTANCE` (no such decision is evidenced anywhere) — both still route to
mandatory human/governance disposition, never auto-resolved, but the label itself should not
imply wrongdoing where the independent record shows none. This is marked `HYPOTHESIS`, prompted
directly by a real case, not invented in the abstract.

---

## 9 · Prior-art disclosure (2026-09-29, third pass) — this model's core distinction was already ratified

**A required correction, not a footnote.** `KOS-ARCH-BASELINE-001`'s own accepted Phase A baseline
(`docs/publicdigit/reviews/2026-08-15-KOS-ARCH-BASELINE-001-phase-a-current-architecture-baseline.md`,
PO/ARB-accepted 2026-08-17) already states, as a ratified architectural rule (§7, citing rulebook
`G`/`§10a`): **"Lifecycle state and authority state are two records that are never merged... `transitions[]`
and `grants[]` share no fields and have disjoint writer rules."** It gives this exact separation its
own formal triad, cleaner than anything derived in this document:

```
ACTIVE  ≠  AUTHORIZED        OWNERSHIP  ≠  AUTHORITY        EXISTENCE  ≠  PERMISSION
```

**This is precisely §15's `H1` finding — independently re-derived through empirical measurement in
this document, rather than found by reading the existing baseline first.** That is a real miss,
not a triumph — exactly the *"derivation without consulting existing theory"* pattern this
corpus's own `EKS-16`/`EKS-30` backlog items catalogue repeatedly, now confirmed operating on this
document's own research. Disclosed here rather than left for someone else to notice.

**What the baseline additionally confirms, directly bearing on §4's fusion procedure:**
- **`recordedBy` is unvalidated on `REGISTER`/`HANDOFF`/`START`** — an unvalidated free string,
  confirmed by direct source reading (`AST-015`), not merely inferred. This means `RoleConsistent`
  and `TemporalMatch` checks against a transition are checking a field the mechanism itself never
  validates — the fusion procedure's caution about not trusting a transition alone is *architecturally
  motivated*, not just empirically prudent.
- **Two sessions can be simultaneously `ACTIVE`; a second `START` silently transfers ownership with
  no precondition on the current owner.** This means §16's stale-transition failure mode was not a
  one-off data artifact — it is a **designed property of the mechanism**: nothing prevents a stale
  `ACTIVE` session from coexisting with, or being silently superseded by, unrelated later activity.
- **The workflow/capability model has its own unregistered-capability finding, independently
  matching this session's own PKS/Cohesion finding**: *"the newest and most consequential
  executable capability on the platform — the workflow state record and its resolver — has no
  declared `CAP-` identifier."* The same pattern (Cohesion missing from PKS's Capability Catalog,
  `U-03`, found earlier this session) recurs here, one level down, in the mechanism Cohesion's own
  governance depends on.
- **Nine open "Architecture Unknowns" are recorded** (§10 of that baseline), none resolved by
  inference — the same discipline this whole research arc has tried to hold itself to.

**What this changes in this document's own model:** nothing in §2–§8 is retracted — the fusion
procedure and its worked validation stand. What changes is the *credit and the confidence*: `H1`
is not merely "supported by this session's measurements," it is **an existing, ratified
architectural rule**, and this document's own predicates (`Cites`, `TemporalMatch`, `RoleConsistent`,
`ScopeConsistent`) should be read as an operational instrument for checking compliance with that
already-ratified rule, not as the discovery of a new one.

---

## 10 · `Fuse` applied to the complete real population (2026-09-29, fourth pass)

Not a sample — **every commit that has ever touched the Cohesion capability's own files**
(`git log -- scripts/lib/.../Cohesion/ tests/Unit/Cohesion/ scripts/observations/examples/lcom4/`),
6 total, spanning 2026-08-04 to 2026-09-28.

| Commit | Date | `WorkItemMatch` | Supporting signals | Result |
|---|---|---|---|---|
| `d61bf5e84` | 2026-08-04 | `FALSE` (no citation) | — | **`UNCITED`** |
| `286cad1e1` | 2026-08-16 | `TRUE` (`KOS-LCOM4-CONTRACT-001`) | `TemporalMatch`✓ (real `REGISTER→HANDOFF→START`, seq 5–7) · `RoleConsistent`✓ · `ScopeConsistent`✓ (`G-KOS-LCOM4-CONTRACT-APPLY`) | **`CONFIRMED`** (3/3) |
| `4c6c1dac5` | 2026-08-18 | `TRUE` (cites `G-KOS-CONTRACT-IMPL-TRACK1-AMD1` exactly) | `TemporalMatch`✓ (real `REGISTER→HANDOFF→START`, seq 32–34) · `RoleConsistent`✓ · `ScopeConsistent`✓ | **`CONFIRMED`** (3/3) |
| `880acd8bb` | 2026-09-28 | `FALSE` (no citation anywhere in the message) | — | **`UNCITED`** |
| `0727c9342` | 2026-09-28 | `TRUE` (informal doc-path citation) | `ScopeConsistent`✓ only | **`CONFIRMED`** (1/3) |
| `9f83a369c` | 2026-09-28 | `TRUE` | `ScopeConsistent`✗ (explicit prohibition) | **`CONTRADICTED`** |

**Distribution: 3 `CONFIRMED` (50%), 2 `UNCITED` (33%), 1 `CONTRADICTED` (17%), 0 `UNDERDETERMINED`.**

**A real false negative, disclosed:** `880acd8bb` is, by this session's own first-hand knowledge,
genuinely and properly authorized work (the direct predecessor of `0727c9342`, same day, covered
by real grants `G-KOS-CONTRACT-ADAPTER-SLICE1`/`G-KOS-CONTRACT-PYTHON-PROPERTY-EXPERIMENT`) — but
its own commit message cites no work item at all, so `Fuse` correctly reports `UNCITED`, not
`CONFIRMED`. **This is not a model defect** — it is the model correctly refusing to guess where
the evidence genuinely doesn't support a claim, exactly per §4's own discipline. It is real evidence
that citation discipline lapses even in properly-governed work, and that `UNCITED` must never be
read as "probably wrong."

**On the untuned threshold (§7's open item):** in this complete population, `CONFIRMED` never fired
on a case that looks wrong on inspection — the weakest `CONFIRMED` (`0727c9342`, 1/3 signals) is
independently known to be legitimate. No evidence in this population that `≥1` is too loose; the
population is small (6), so this is a disclosed observation, not a statistical claim.

---

## 11 · Correction, blind-spot discovery, and full evidence-ablation ladder (2026-09-29, fifth pass)

### 11.1 · A disclosed factual error

§8/§10 stated that `9f83a369c` resolves `WorkItemMatch = TRUE` via "an informal document-path
citation." **Re-reading the commit's actual body directly, this is wrong** — that citation belongs
to the *adjacent* commit `0727c9342`, not this one. `9f83a369c`'s own message never mentions
`KOS-CONTRACT-NEUTRALITY-001` or any document referencing it. This is a real conflation error,
corrected here rather than silently fixed.

### 11.2 · What the correction reveals — more important than the error itself

Under `Fuse`'s own §4 procedure (`if WorkItemMatch = FALSE: return UNCITED`), correcting this means
`9f83a369c` should have returned **`UNCITED`, not `CONTRADICTED`.** But the real, independent
verification (`6b983bc12`) found a genuine, serious violation regardless — because it searched by
**file** (which commits touched `expected.json`), not by **citation** (which commits mention the
work item). **`Fuse`, as specified, would have missed this real violation entirely.** Confirmed by
direct sweep: exactly 3 commits in the repository's whole history ever touched
`scripts/observations/examples/lcom4/expected.json` — `286cad1e1` (properly authorized, real
`REGISTER→HANDOFF→START`, §10), `d61bf5e84` (predates any grant regime), and `9f83a369c` (the real
violation, self-uncited). **A citation-gated fusion procedure is structurally blind to exactly the
cases where the violation and the missing citation occur together** — which is precisely the
highest-risk case, not an edge case.

**Required refinement, not yet adopted, evidenced by a real example:** `Fuse` needs a second,
citation-independent entry point — a **protected-artifact sweep**: for each work item, derive a
set of "do-not-modify" file patterns directly from its grants' own negative-scope text (already
demonstrated extractable, §4), and check *every* commit touching those files, regardless of
whether it cites the work item at all. `UNCITED` must never be read as "no further check needed."

### 11.3 · Full evidence-ablation ladder, real data, n=379

Population: every commit whose message contains "KOS-" (`git log --grep="KOS-" -i --all`) — a
real, bounded, much larger sample than §10's 6-commit census, not cherry-picked.

| Stage | What's added | Result |
|---|---|---|
| **S1** | work-item citation only | `CITED`: 220 (58.0%) · `UNCITED`: 159 (42.0%) |
| **S2** | + exact grant-ID citation | of the 220 cited: 59 (15.6% of total) cite an exact grant; 161 (42.5%) cite the work item only |
| **S3** | + scope check against the *cited* grant only | 0 violations found — **later shown to be the blind spot in §11.2, not a clean result** |
| **S4** | + temporal containment (commit date within the work item's real transition date range) | `CONFIRMED`: 179 (47.2%) · `UNCITED`: 159 (42.0%) · `TEMPORAL_MISMATCH`: 35 (9.2%) · `UNDERDETERMINED`: 6 (1.6%) |

> **Correction (2026-09-29, sixth pass):** the earlier statement here ("201 of 379") is arithmetically
> wrong — `159 + 35 + 6 = 200`, not 201. Fixed; the percentage (≈53%) was approximately right, the
> count was not. Full continuation of this research, including a corrected, normalized-entropy
> treatment of "does each dimension reduce uncertainty," is in
> `2026-09-29-KOS-PROTECTED-ARTIFACT-SWEEP.md` §9 — the claim below is retained for history but
> superseded by that document's more careful version.

**Does each dimension reduce uncertainty? Yes, measurably.** S1 alone only stratifies the
population into two flat buckets (58/42). By S4, the same population resolves into four
meaningfully different states, including 35 real, named temporal contradictions (9.2% of the
whole population) — a genuine, non-trivial discovery, not noise. Adding grant-citation (S2) shows
most cited work is *not* tied to a specific grant (161 vs. 59) — consistent with §15's finding
that most grants are scope-bounding refinements, not appointments, and citation of the *work item*
is more common than citation of a *specific grant*.

### 11.4 · Kernel-candidate test, against this larger evidence base

| Candidate | Required? | Reusable? | Domain-independent? | Materially reduces uncertainty? | Status |
|---|---|---|---|---|---|
| Canonical representation (work-item identity) | Yes — `S1` alone separates 58%/42% | Yes | Yes | Yes, foundational | **SUPPORTED** |
| Authorization/scope (grant) | Partial | Yes | Yes | Weak alone (`S3` found nothing until the blind spot was fixed) — **strength depends on the file-sweep refinement, not yet built** | **HYPOTHESIS, refinement pending** |
| Governed lifecycle (transition/temporal) | Yes — `S4` produces the sharpest real split (35 named contradictions) | Yes | Yes | Yes, strongest single addition measured | **SUPPORTED** |
| Uncertainty/indeterminacy as distinct state | Yes — `UNDERDETERMINED`/`UNCITED` are real, non-collapsible outcomes (201 of 379, 53%, are not `CONFIRMED`) | Yes | Yes | Yes | **SUPPORTED** |
| Provenance | Not independently tested this pass | — | — | — | **UNKNOWN this pass** |
| Observation | Precondition for everything above | Yes | Yes | Trivially necessary | **SUPPORTED** |
| Rule/classification (never reasoning) | The classifier itself (`Fuse`) is exactly this | Yes | Yes | Structural, not measured numerically | **SUPPORTED by construction** |

### 11.5 · ML assessment

**`ML NOT JUSTIFIED YET`** — a valid result, not a deferral. Reasons, concretely: (a) the
deterministic signal set is **not exhausted** — the 161-commit `CITED_NO_GRANT` bucket and the
protected-artifact sweep (§11.2) have not yet been run to their own conclusion; (b) **no ground
truth at meaningful scale exists** — only one case (`9f83a369c`) has genuine independent
verification behind it; training or evaluating any model against 1 labeled example would be
scientifically indefensible. Revisit only after the protected-artifact sweep and the
`CITED_NO_GRANT` bucket have been deterministically resolved as far as possible.

---

## Status (short form)

- **Established this pass:** a deterministic, three-valued, auditable fusion procedure exists that
  correctly reclassifies §16's own failure case from "silently wrong" to "correctly `CONTRADICTED`."
- **Formally shown, not merely argued:** citation alone and temporal proximity alone are each
  individually insufficient for `CONFIRMED` — both have real counterexamples on record now, not
  hypothetical ones.
- **ML's role:** bounded to ranking within an already-`UNDERDETERMINED` set; never authorizing.
- **Remains open:** the `≥1`-supporting-predicate threshold is untuned; running `Fuse` against a
  real, larger commit sample (the original 100–200-commit experiment) is the natural next step,
  now that the procedure itself — not just the observable unit — is defined.
- **Not done:** no implementation. This is a logic/DDD model, ready for the same stop-and-ask
  discipline the whole research arc has followed.

---

**Traceability:** `2026-09-28-KOS-governed-agent-observation-theory-research.md` §§4, 6, 7, 14, 15,
16 (every predicate and worked example here is drawn directly from that document's own findings) ·
`2026-09-28-KOS-governed-engineering-act-observation-capability-contract.md` (the five-state
vocabulary this model generalizes) · `INV-ATTR-1`/`INV-ATTR-2` · PKS's `20260728_1710_what_is_pks_v1.md`
("AI surfaces governance questions; it does not resolve them," verified directly this session).
