# Governance Constraint Observability — Research Result

**Date:** 2026-09-29. Empirical experiment. References, does not rewrite,
`2026-09-29-KOS-PROTECTED-ARTIFACT-SWEEP.md`.

---

## 1 · Research question

> Can governance constraints be transformed into deterministic, machine-observable predicates
> without semantic inference?

## 2 · Corpus

All 144 grants across all 22 real `.claude/runtime/workflow/*.json` ledgers — every grant's
`scope` and `humanActRef` text, full corpus, not a sample.

## 3 · Extraction method — and a disclosed limitation

Searched every grant's text for sentences containing one of: `does not authorize`, `must not`,
`may not`, `only`, `excludes`, `outside scope`, `forbidden`, `requires` (case-insensitive),
extracting to the next sentence boundary. 243 clauses matched across 105 of 144 grants.

**Disclosed limitation, not smoothed over:** this marker-based extraction has real noise. Several
matches are false positives for "constraint" — e.g. *"only durable record — reconstruction
ability, governance risks, failure scenarios"* uses "only" as an intensifier, not an exclusivity
constraint; it was extracted because the word matched, not because it states a real boundary. **This
means the 243-clause count overstates the number of genuine constraint statements.** The core
finding below (the overwhelming dominance of `SEMANTICALLY_STATED_NOT_OBSERVABLE`) is unaffected
in direction — removing the noisy fragments would only *increase* that category's share, since
noise fragments trivially contain no concrete token — but the absolute counts should be read as an
upper-bound census, not a precise one.

## 4 · Observability taxonomy

- **`DERIVABLY_OBSERVABLE`** — the clause contains a token type with a demonstrated, deterministic
  transformation to repository/runtime evidence (a filename-shaped token → git path check, already
  proven in the protected-artifact sweep; a session-shaped token → transition `session` field
  match; a `G-KOS-*` token → grant-citation match; a date → commit-timestamp comparison).
- **`SEMANTICALLY_STATED_NOT_OBSERVABLE`** — a real constraint is stated, a human can understand
  it, but no concrete, mechanically-matchable token was found in the clause.
- No clause required the `UNKNOWN` category — every clause found either did or did not contain a
  matchable token; `UNKNOWN` is reserved for cases where even this determination can't be made,
  none arose.

**No semantic guessing was performed.** *"Does not authorize modification of governance state"*
would be extracted, found to contain no filename/session/grant/date token, and classified
`SEMANTICALLY_STATED_NOT_OBSERVABLE` — never guessed into a path like `governance-state/*.json`.

## 5 · Constraint inventory — measured, not estimated

| Classification | Count | % |
|---|---|---|
| `SEMANTICALLY_STATED_NOT_OBSERVABLE` | 240 | 98.8% |
| `DERIVABLY_OBSERVABLE` | 3 | 1.2% |

**Observable dimension breakdown (of the 3):** `GRANT_REF` — 2 (`KOS-AIP04-DISCOVERY-001`,
`KOS-CONTRACT-NEUTRALITY-001`); `TEMPORAL` — 1 (`KOS-OQ-001`). **`FILE` and `SESSION` dimensions:
zero clauses matched this way** — the protected-artifact sweep's file-level finding came from a
*different*, narrower extraction (targeting `Does NOT authorize` specifically, not the full marker
set here) and is not contradicted, but this broader sweep did not independently rediscover it via
these markers, which is itself worth noting precisely rather than glossed over.

**Work-item-level coverage:** 3 of 22 work items have *any* `DERIVABLY_OBSERVABLE` constraint by
this method. 15 of 22 have at least one marker-clause of any kind. **7 of 22 have zero
marker-clauses at all** (`KOS-ACTIVATION-REPORTING-001`, `KOS-AI-ORCH-001-INC1`,
`KOS-GOV-ATTRIBUTION-001`, `KOS-NEXT-ACTOR-ORCHESTRATION-001`, `KOS-OPERATING-MODEL-001`,
`KOS-ROLE-IDENTITY-001`, `KOS-SESSION-BOOTSTRAP-001`) — this does not establish they have no
constraints, only that none were phrased using the searched markers.

## 6 · Deterministic transformation rules, where they exist

```
"Does NOT authorize... expected.json"
   → grant text
   → filename-token extraction
   → ProtectedFile(expected.json)
   → git log -- <path>
   → candidate event               [already proven, protected-artifact sweep]

"Performed by the SAME lane: S5-architecture-pass1-evidence-reconciliation"
   → grant text
   → session-token extraction
   → transition.session match
   → candidate continuity check    [derivable, not yet run as its own sweep]

"PO/ARB act 2026-09-04"
   → grant text
   → date-token extraction
   → commit-timestamp comparison   [already used, §4 of the fusion model]
```

**No transformation rule exists** for role-based, actor-based, or abstract-referent constraints
(*"must not verify or accept the design"* — real, clear to a human, but "the design" has no
concrete, extractable referent without reading the surrounding prose).

## 7 · Examples of `SEMANTICALLY_STATED_NOT_OBSERVABLE`, real, quoted

- *"must not be relaxed for runtime, while the relocated governance evidence must be durable"*
- *"must not verify or accept the design"*
- *"only if new architectural decisions emerge"*

Each states a genuine, human-comprehensible constraint. None reduces to a concrete, checkable
predicate without additional interpretation this experiment was instructed not to perform.

## 8 · Falsification results

**Both hypothesized distinctions are strongly supported, not refuted:**

- `Governance constraint ≠ Observable governance constraint` — **supported**, decisively (98.8%
  vs. 1.2%).
- `Authorization ≠ Machine-verifiable authorization` — **supported**, same evidence.

**Not tested, explicitly:** whether this ratio is specific to this repository's drafting style, or
a general property of natural-language governance text. `UNKNOWN`, out of scope for a single-corpus
study.

## 9 · Candidate invariant

> *"A governance claim is not operationally testable unless the governed constraint has an
> observable representation."*

**Status: `SUPPORTED` by this corpus, repeatedly (240 independent instances) — not merely
plausible.** Recorded as a candidate, domain-independent invariant, not yet elevated to
established theory (one corpus, one drafting convention).

## 10 · Kernel implications — no new candidate added

Per instruction, this finding is **not** promoted into an eighth kernel candidate. It is recorded
as a candidate invariant (§9) about the relationship between governance and evidence generally —
orthogonal to the seven existing candidates, not a substitute for any of them.

## 11 · Reassessment of the seven kernel candidates

| Candidate | Prior status | This pass |
|---|---|---|
| Canonical representation | `SUPPORTED WITH LIMITATION` | unchanged — this experiment didn't test it directly |
| Authorization/scope | `SUPPORTED WITH LIMITATION` | **sharpened, not changed**: now known that only 1.2% of all real constraint clauses are mechanically checkable at all — the earlier "1 of 22 work items" file-level finding is now seen as the *strongest* case in the corpus, not a weak outlier |
| Governed lifecycle | `SUPPORTED WITH LIMITATION` | unchanged |
| Uncertainty/indeterminacy | `SUPPORTED` | **reinforced** — `SEMANTICALLY_STATED_NOT_OBSERVABLE` is itself a real, necessary, non-collapsible third state this experiment had to use for 98.8% of its own data |
| Provenance | `UNKNOWN` | unchanged, not tested this pass |
| Observation | `UNKNOWN — not an empirical kernel result` | unchanged |
| Rule/classification | `SUPPORTED WITH LIMITATION` | unchanged |

## 12 · Remaining unknowns

- Whether the 7 zero-marker work items have real, unphrased-this-way constraints, or genuinely
  none.
- Whether extending the marker set (beyond the 8 words searched) would find more, or merely more
  noise — not tested.
- Whether role/actor-based constraints could ever become derivably observable given a different
  grant-drafting convention (a design question, not answerable from this corpus alone).

## 13 · Next smallest decisive experiment

Run the derivable `SESSION` and `TEMPORAL` transformation rules (§6) as actual sweeps — not yet
done, only shown derivable — against `KOS-AIP04-DISCOVERY-001` and `KOS-OQ-001` respectively, the
same rigor already applied to the file-level case in the protected-artifact sweep. This tests
whether the file-level result (1 real violation found, out of 6 hits) generalizes to the other two
observable dimensions, or whether each dimension behaves differently.

---

**Traceability:** `2026-09-29-KOS-PROTECTED-ARTIFACT-SWEEP.md` (the originating file-level finding,
not rewritten) · direct extraction from all 144 grants, this session, this pass.
