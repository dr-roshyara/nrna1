# KOS — Governed Agent Observation: Theory Research

**Date:** 2026-09-28. **Phase:** research only — no implementation. Supersedes the prior framing of
`2026-09-28-KOS-governed-engineering-act-observation-capability-contract.md` as "the" next
capability; that contract is retained here as evidence of **one observation slice**, not the whole
answer.

> ⛔ No code written. No capability implemented. The theory-construction programme's own stopped
> state is untouched. Everything below is either a direct citation of already-real mechanisms, or
> explicitly marked HYPOTHESIS.

---

## 1 · What the current corpus actually proves

Real, mechanically-enforced governed-agent machinery already exists — this is not aspirational:

- **`session-bootstrap.php` (`AST-017`)** already performs the exact six-way separation the
  research question asks about: *identity ≠ role ≠ eligibility ≠ authorization ≠ ownership ≠
  continuation* — tested, 18/18 contract assertions, 210 assertions total.
- **`workflow-state.php`** mechanically enforces `Inv A`/`Inv C` (one mutation owner), `G-1`/`G-2`
  (sole writer), `G-3` (human `START` required) — real code, not prose.
- **`INV-ATTR-1`/`INV-ATTR-2`** (adopted): self-declared identity is evidential, never attested;
  no gate reads it as authority. This is the single most important existing constraint for
  everything below.
- **The Observation Runtime** (`developer_guide/engineering_observations/`) is a real, wired,
  TDD-built `Activity → Evidence → Recommendation → Decision → Outcome → Assessment` pipeline —
  the closest existing analogue to the hypothesized lifecycle, though built for code metrics, not
  agent governance.
- **PKS's own rule**, verified directly this session: *"No decision is final without human
  approval. AI surfaces governance questions; it does not resolve them."*

## 2 · What the prior contract correctly discovered

The `DECLARED_NO_STORY`/`UNCITED` distinction, the fail-closed default, the reuse-not-rebuild
discipline (`GovernedRegisterMap.php`), and the refusal to claim scope-to-diff verification without
structured scope data. These stand as evidence, unchanged.

## 3 · What the prior contract does not cover — answered directly, not defended

Answering §8's eleven questions precisely, using a **new, first-hand empirical check performed
this pass** (a real 30-commit sample, `git log`, this repository, 2026-09-28):

1. **What it solves:** whether a single commit's citation resolves to a real, currently-valid
   governance record.
2. **What it does not solve:** whether the *correct agent* performed the work; role/eligibility/
   authorization of that agent; whether the agent stayed within its commissioned responsibility;
   independent verification of the artifact itself.
3. **Empirically justified:** citations resolving to real registers — **confirmed, 100% in this
   pass's 30-commit sample** (every commit citing `F-LOG-nnnn`/`G-LOG-nnnn` resolved to a real
   file, e.g. `F-GOVERNANCE-LOG.md`); free-text (not structured) work-item scope — confirmed
   directly, unchanged from the prior pass.
4. **Remaining hypotheses:** that timestamp/branch/session corroboration is actually available and
   independent — **weakened by this pass's own new finding** (§4 below).
5. **Is the five-state classifier sufficient?** No — it conflates *"the citation resolves"* with
   *"the correct agent did the work,"* which are different predicates (§7).
6. **Is `CONFIRMED` strong enough?** No, for a specific, newly-found reason: session-level
   corroboration is not a reliably available signal at all (§4) — `CONFIRMED` would rarely be
   honestly reachable; most real cases will land in `UNDERDETERMINED`, which is itself the correct,
   disclosed finding, not a defect.
7. **Is timestamp/branch/session corroboration actually independent?** Timestamps: yes, reliable.
   Branch naming: real but not universally used. Session: **no** — see §4.
8. **Does commit attribution prove agent attribution?** **No — confirmed directly, this pass**
   (§4), not merely asserted from `INV-ATTR-1`/`2`.
9. **Can a commit be correctly attributed to a work item but performed by the wrong actor?** Yes,
   and undetectable by the prior contract as written — a genuine, disclosed gap.
10. **Can an authorized agent produce an unattributed commit?** Yes — this is exactly `UNCITED`;
    the contract detects the fact but cannot resolve *why* (forgot vs. deliberate).
11. **What additional signal would resolve these?** A reliably *attested* (not self-declared)
    process identity bound to each commit at write time — **this does not exist today, and
    `INV-ATTR-1`/`INV-ATTR-2` structurally bar building it** without first revisiting that
    invariant, which is a governance decision, not a research one.

## 4 · New empirical finding this pass (real, not corpus-cited)

A real 30-commit sample (`git log -30`, this repository) was pulled and cross-checked directly:

- **Every commit's git-author field is identical** (the one human account) regardless of which AI
  process actually produced the change — directly re-confirms `EKS-07`'s finding, first-hand.
- **`Co-Authored-By` carries a *model variant label*, not a session/process identifier** — two
  adjacent commits in the sample carry `Claude Opus 5.5` and `Claude Opus 5.5 (1M context)`
  respectively. There is no way, from git alone, to tell whether these are the same session with a
  different context setting or genuinely different sessions.
- **Work-item citation discipline was 100% in this sample** — every commit named an `F-LOG`/`G-LOG`
  reference, and a spot-check confirmed these resolve to real files. This is *stronger* citation
  discipline than the earlier, smaller sample suggested — worth disclosing as a correction, not
  silently dropped.

**Consequence:** the observable signal set can currently answer *"which human account, which model
variant, which work item was cited, when"* — and cannot currently answer *"which specific AI
session or process."* That gap is not a missing feature; it is the direct, intended consequence of
`INV-ATTR-1`/`INV-ATTR-2`.

## 5 · Observation signal inventory

| Signal | Exists? | Source | Reliable? | Machine-readable? | Limit |
|---|---|---|---|---|---|
| Human account | Yes | git author | Coarse but solid | Yes | Same account regardless of producing AI process |
| Model variant | Partial | `Co-Authored-By` trailer | Weak | Yes | Names model, not session; drifts (`(1M context)` suffix) |
| Session/process ID | Partial | free-text `executionContext`, `session-bootstrap.php` | Unreliable | Partial | Self-declared, never attested (`INV-ATTR-1/2`); a real diagnosed case returned `AMBIGUOUS` |
| Work-item/finding-log citation | Yes | commit body | Reliable when present; format varies by track | Yes, per-track | No unified format across `PublicDigit`/`KnowledgeOS` tracks |
| Governance ledger entry | Yes | `governance-notes.md` and siblings | Reliable | Partial (prose) | Not schema'd, not queryable as data |
| Grant record | Yes | `workflow-state.php`, `.claude/runtime/*-state.json` | Reliable, mechanically enforced | Yes | `--scope` is free text; state is gitignored (a separate durability concern, `EKS-05`) |
| REGISTER/HANDOFF/START | Yes | `workflow-state.php` transitions | Reliable when recorded | Yes | No transaction boundary across the 3-step sequence (`EKS-09`); a forced race produced a permanent orphan |
| Identity/role/eligibility/authorization/ownership/continuation | Yes | `session-bootstrap.php` (`AST-017`) | Reliable, tested | Yes | Read-only, `ON_DEMAND` only, not wired to run automatically |
| Predecessor-handoff fact | Partial | `AST-015`'s internal `fold()` | Unreliable | No read command exposes it | Diagnosed gap, `KOS-SESSION-BOOTSTRAP-001` |
| File/diff content | Yes | git | Reliable | Yes | Says what changed, nothing about authorization |
| Test execution | Partial | CI logs | Not durably linked to commits | Yes at run time | No queryable post-hoc link |
| Independent verification | Conceptually yes | PKS's authorial-vs-reviewer separation | Declared, not measured | No | Independence is asserted, not attested — same shape as `INV-ATTR-1` |

## 6 · The observable unit — derived, not assumed

Per §3's answers to questions 9–10, **a commit alone is provably insufficient**: it can be
correctly cited to a work item while produced by an undetermined actor, and an authorized agent can
produce a correctly-authorized but uncited commit. **The smallest unit that preserves governance
meaning is the governed transition (`REGISTER`/`HANDOFF`/`START`/`CLOSE`), with commits and other
artifacts attached to it as evidence — not the commit as a standalone unit.** This is derived from
§3's own failure cases, not chosen by preference.

## 7 · Predicates — which implications actually hold

```
Authorized(a, w, t)   — a is authorized for work item w at time t
Observed(e, t)        — event e was observed at time t
Cited(e, w)           — e's message cites w
Produced(a, e)        — a actually produced e
Verified(e)           — e passed independent review
Accepted(w)           — w's outcome was accepted by governance
```

**Confirmed NOT to hold, with direct evidence:**

- `Observed(e,t) ⇒ Authorized(a,w,t)` — **false.** Observing an event says nothing about who
  authorized it; git's author field is constant regardless of authorization state (§4).
- `Cited(e,w) ⇒ Produced(a,e)` — **false**, and not merely by argument: `EKS-07`'s own AMD6
  provenance-split incident (`196aa607e`/`c821abece`) is a real, first-hand case where content was
  correctly associated with the right substance but the *commit record* misattributed which
  session produced it.
- `Verified(e) ⇒ Accepted(w)` — **false**, matching PKS's own explicit rule verbatim.
- `Accepted(w) ⇒ Correct(e)` — **false**; nothing in the corpus claims otherwise.

**This is the precise, formal reason a five-state citation classifier cannot be "agent
observation":** it computes `Cited(e,w)`, and `Cited` implies none of `Authorized`, `Produced`, or
`Verified`.

## 8 · Candidate invariants, classified

| Invariant | Class | Evidence |
|---|---|---|
| Observation must not create authorization | **ESTABLISHED** | `INV-ATTR-1/2`; confirmed independently across Cohesion, the governance engine, PKS, and the Observation Runtime this session |
| Observation must not modify the observed record | **ESTABLISHED** | Observation Runtime's own "executes, never governs" boundary; this contract's own §8 invariant |
| Verification must not automatically imply adoption | **ESTABLISHED** | PKS's explicit rule; `AssessmentService`'s `INCONCLUSIVE` state |
| Every authorized action is attributable to *an* actor | **SUPPORTED**, weak reading only | true for the coarse human account (100% in this pass's sample); **CONTRADICTED** for the strong reading ("the true producing process") — §4 |
| Independent verification is distinguishable from implementation | **SUPPORTED** | the `S4`/reviewer-lane separation cited in this session's own plan-file reading; PKS's authorial-vs.-reviewer rule |
| An action outside an agent's responsibility is detectable | **UNKNOWN** | no evidence found either way this pass — genuinely open |

## 9 · Where deterministic logic suffices vs. where ML might help

Deterministic rules already suffice for: citation parsing, register resolution, timestamp-window
checks, lifecycle-transition state reads (`AST-015`/`AST-017`) — none of this needs ML.

**ML is not yet justified.** Per the corpus's own evidence, the actual gap (§4) is a **missing
signal**, not an ambiguous one — no amount of clustering or anomaly detection recovers a session
identity that was never attested in the first place. ML would have a legitimate, narrow role only
*after* the deterministic signals in §5 are exhausted and real residual ambiguity remains — e.g.
ranking candidate work-item associations for `UNCITED` commits with no exact-match citation. Not
proposed for implementation here; recorded as a conditional, not a plan.

## 10 · Minimal architecture, evidence-derived

```
Governed Transition (REGISTER/HANDOFF/START/CLOSE)   — already real, workflow-state.php
        │
        ▼
Attached Evidence (commits, artifacts in the transition's window)
        │  ← the prior contract's citation-check applies HERE, as a sub-check,
        │    not as the primary observable unit
        ▼
Independent Verification (conceptually real — PKS's reviewer separation;
                            not yet mechanically linked to transitions)
        │
        ▼
Decision (governance ledger — real, `GN-nn`-style rulings)
```

**No new "attested agent identity" component is proposed.** The gap is real (§4) but
`INV-ATTR-1`/`INV-ATTR-2` structurally bar solving it without first revisiting that invariant —
which is a governance act, not a research finding this document can make on its own. Recorded as
an explicit open question (§12), not designed around silently.

## 11 · A / B / C, kept separate

- **A — current implementation:** `workflow-state.php`, `session-bootstrap.php`, the Observation
  Runtime, the four `Capabilities/` — all real, all cited above.
- **B — evidence-derived target:** the prior contract's citation-checker, re-scoped as a sub-check
  on governed transitions rather than commits alone (§6, §10).
- **C — research hypotheses, not adopted:** ML-assisted ranking for `UNCITED` residuals (§9);
  whether `INV-ATTR-1`/`INV-ATTR-2` should ever be revisited to permit attested process identity
  (a governance question, flagged not decided).

## 12 · Open questions that cannot yet be answered

- Whether `INV-ATTR-1`/`INV-ATTR-2` should be revisited — out of this research's authority.
- Whether "action outside responsibility" is detectable at all with current signals (§8, `UNKNOWN`).
- Whether the governed-transition unit (§6) actually covers all real cases, or whether some
  legitimate engineering activity happens entirely outside any `REGISTER`/`HANDOFF`/`START` —
  not tested this pass.

## 13 · Smallest next experiment

Re-scope the existing citation-checker contract (already written) to attach to governed
transitions rather than raw commits, and re-run the same real-corpus classification against the
transitions recorded in `.claude/runtime/*-state.json` (not yet inspected for this purpose) —
cheap, and directly tests whether §6's derived unit actually reduces the ambiguity the commit-level
version would otherwise show.

---

## Status (short form)

- **Goal progress:** the observation *model* is now evidence-derived (§6, §7, §10), not assumed.
- **Established:** the six-way identity/role/eligibility/authorization/ownership/continuation
  separation is real, not hypothetical; three of four candidate invariants are `ESTABLISHED`.
- **Remains uncertain:** whether "responsibility violation" is detectable at all (§8); whether the
  governed-transition unit covers all real activity (§12).
- **Highest-value next experiment:** §13.
- **Stop condition:** implementation of the *narrow* citation-checker (already contracted) remains
  justified on its own terms; the *broader* "observe which agent did what" question stops here,
  pending a governance decision on `INV-ATTR-1`/`INV-ATTR-2`, not further research.

---

## 14 · Addendum (2026-09-29) — grant/transition correspondence, measured across all 22 ledgers

**Method:** every `*.json` file in `.claude/runtime/workflow/` parsed directly (not via forked
prose-reading — this is structured data). Matching rule, fixed before applying it: a grant is
matched to the transition record if its exact `grantId` string appears verbatim anywhere in the
JSON-serialized transitions array of the same file; a transition carries authorization evidence if
it either cites a known `grantId` or contains inline human-act language (`PO/ARB`, `humanAct`,
`authoriz`, `APPOINTED`, `appointment`).

**A self-caught measurement error, disclosed rather than hidden:** the first pass checked only for
exact `grantId` citations inside transitions and reported *78% of all 274 transitions carry no
authorization evidence* — alarmingly high. Spot-checking the worst-looking case
(`KOS-OPERATING-MODEL-001`: 0 grants, 18 transitions) found real, explicit authorization
("APPOINTED BY PO/ARB 2026-08-22... 'I confirm... and authorize delivery...'") embedded **inline**
in the transition itself, never intended to use the separate `grants[]` array at all. The first
pass's rule couldn't see this and misclassified it as unauthorized. **Corrected result: 212/274
transitions (77.4%) carry some authorization evidence; only 62/274 (22.6%) carry none.** The
first-pass number was a measurement artifact, not a real finding — recorded here, not silently
replaced.

**What remains real after the correction, concentrated, not uniform:**

| File | Transitions with no authorization evidence |
|---|---|
| `KOS-SESSION-DISCOVERY-001` | 12/20 (60%) |
| `KOS-OQ-001` | 10/15 (67%) |
| `KOS-CONTRACT-NEUTRALITY-001` | 9/45 (20%) |
| `KOS-AIP04-DISCOVERY-001` | 5/33 (15%) |

**The original `KOS-CONTRACT-NEUTRALITY-001` finding, re-examined precisely:** the reverse
direction (grant → transition) still shows real drift — **17 of 40 grants (42.5%) have no matching
or partial-matching transition anywhere in the file** — this is a different check than the
authorization-evidence one above, and it is not resolved by the correction. Checked directly
whether the later, unmatched grants (the Python `R1`–`R6` series, `D-1`'s own grant lineage) cite
any session/lane name that could tie them to the last real transition (a `START` dated 2026-09-04,
lane `S5-architecture-pass1-evidence-reconciliation`): **they do not.** Their `authority`/
`humanActRef` fields say only *"PO/ARB (human), in-session act 2026-09-27"* — a bare date, no
session or lane identifier at all. **This is genuinely `INDETERMINATE`, not confirmed drift and not
confirmed continuity** — the schema for this batch of grants simply omits the field that would let
either reading be checked. Recorded honestly as unresolved, correcting this document's own earlier,
more alarmed framing.

**H1/H2/H3, assessed:**
- **H1** (grants and transitions are intentionally independent concepts needing a reliable
  relation) — **SUPPORTED.** Transitions overwhelmingly carry coarse, blanket appointment
  authorization inline; the `grants[]` array appears used for a narrower, different purpose
  (bounding what an already-appointed session may additionally do) — two purposes, not one
  lifecycle split by accident.
- **H2** (one governed lifecycle, needs a stronger connecting invariant) — **WEAKENED** by the H1
  finding, not excluded.
- **H3** (a third concept is missing — e.g. a scope-amendment-to-an-already-active-session concept
  that doesn't need its own transition) — **PLAUSIBLE, INDETERMINATE.** Directly consistent with
  the missing session-citation finding above, but not provable from current data.

**Task 7 (commit-level vs. transition-level ambiguity reduction) was not reached this pass** — the
correspondence measurement took priority and surfaced a real self-correction worth stopping on.
Recorded as the next open task, not skipped silently.

**Smallest next step:** check whether the missing-session-citation pattern is itself systemic (does
*every* grant after a certain date stop citing a session/lane, or is this specific to the Python-
track grants?) — this would distinguish a genuine architectural gap from a narrow, recent
drafting-style change, and is a cheap, structured check on data already in hand.

---

## 15 · Addendum (2026-09-29, second pass) — the semantic purpose of a grant, measured directly

**Method:** all 144 grants extracted programmatically; a fixed, disclosed set of keyword
heuristics assigned semantic labels (multi-label allowed, `UNKNOWN` when nothing matched); a
structural key-inventory check ran across every grant in the corpus, not a sample.

**1 · What a grant actually means, empirically:** **`IMPLEMENTATION_AUTHORIZATION`** (65.3%) and
**`SCOPE_BOUNDARY`** (59.0%) dominate, frequently together (94/144 grants carry more than one
label). **`APPOINTMENT`** is rare (12.5%); **`CONTINUATION`** is rare (10.4%). **A grant, in this
corpus, is overwhelmingly "permission to do a narrowly bounded piece of work," not "the act of
establishing a governed session."**

**2 · Grants and transitions are semantically distinct — confirmed structurally, not just
argued:** every one of the 144 grants, across all 22 ledgers, with zero exceptions, uses exactly
the same six fields (`grantId`, `status`, `authority`, `humanActRef`, `scope`, `registeredBy`).
**No grant, anywhere in the corpus, has ever carried a session/lane/agent field.** This is not a
drafting-style lapse in a subset of records — it is the schema's universal, consistent shape.

**3 · Is a transition expected per category?** Re-classifying the 17 grants from
`KOS-CONTRACT-NEUTRALITY-001` that §14 found unmatched: **16 of 17 are `SCOPE_BOUNDARY`/
`IMPLEMENTATION_AUTHORIZATION`** — grants that narrow what an already-active session may do, not
grants that establish one. Only one weakly triggers `APPOINTMENT` (`R1-CONSTRUCTORS`, matched on
the phrase "new branch," which names a work topic, not a new governed session — a heuristic
false-positive, disclosed as such). **Under a semantic-purpose model, a transition is expected
primarily for `APPOINTMENT`-type grants. Given that, 16 of the 17 "unmatched" grants were never
expected to produce one.** The §14 finding is not overturned, but it is now understood correctly:
most of that unmatched set is not evidence of drift — it is exactly what the corpus's own,
uniformly-applied grant semantics predicts.

**4 · H1 — re-tested, more strongly supported than in §14:** grants bound scope for existing
activity; transitions establish and move sessions through their lifecycle. These are two
different *purposes*, evidenced structurally (the universal six-field schema) and semantically
(the category distribution), not merely "currently compatible with independence."

**5 · H3 — weakened further, not strengthened:** a real, if inconsistently-applied, "this grant
extends grant X" citation convention *does* exist — two of the Python-experiment grants
(`PYTHON-PROPERTY-EXPERIMENT`→`ADAPTER-SLICE1`, `PYTHON-CALLABLE-REFERENCE-EXPERIMENT`→
`PYTHON-PROPERTY-EXPERIMENT`) explicitly cite their predecessor. It lapsed for every later grant
in the same sequence (`STATIC-DISPATCH` onward cite nothing). This is a minor, disclosed
documentation-consistency gap, not evidence of a missing architectural concept — the existing
`SCOPE_BOUNDARY` category already explains the pattern without inventing anything new.

**6 · Session/lane identity is not semantically required in a grant.** Confirmed by the universal
schema finding in §2 — no invariant in the corpus ties a grant to a session, and none is needed for
a grant to do its actual job (bounding scope). **The missing-session-citation observation in §14 is
retracted as a "gap" and reclassified as expected behavior**, not an architectural defect.

**7 · Newly discovered invariant, tentative:** *"A grant authorizes scope; a transition authorizes
presence. Neither is required to reference the other by structured field — their relationship is
established by which session is active when the grant's authority is exercised, not by a stored
link."* Marked `HYPOTHESIS`, not `ESTABLISHED` — inferred from the pattern, not stated anywhere in
the corpus directly.

**8 · Smallest next experiment:** Task 7 from §14 (commit-level vs. transition-level ambiguity
reduction) remains the next open, unattempted task — this pass answered the grant-semantics
question instead, which was the correct priority call, but the original observable-unit
falsification test still has not been run.

---

## 16 · The observable-unit falsification test (§14's Task 7, finally run)

**Test subject:** commit `0727c9342` (this session's own `D-1` implementation, real, already known
in detail), work item `KOS-CONTRACT-NEUTRALITY-001`.

| Dimension | Commit-level observation | Transition-level observation |
|---|---|---|
| Actor | Human account: `Dr. Nab Raj Roshyara` (git author); model variant: `Claude Sonnet 5` (`Co-Authored-By`) | The only transition covering this work item's later era is a `START` dated **2026-09-04**, lane `S5-architecture-pass1-evidence-reconciliation`, `recordedBy: human` — 24 days before this commit, never `COMPLETE`d or `CANCEL`ed |
| Role/responsibility | Not observable at all | That stale transition's role is `architecture` — the actual work (a Python semantic-adapter implementation) is `implementation`, per every other transition's own role field in this ledger. **A naive "still-open lane absorbs later work" reading would attribute this commit to the wrong role.** |
| Authorization | Informal only: cites a document path (`...-V3-governance-decision-brief.md`), not a `grantId` | **The `grants` array, not `transitions`, is the actually-relevant record** — `G-KOS-PYTHON-RULE-VALIDATION-R1-CONSTRUCTORS` and siblings (dated 2026-09-27, `SCOPE_BOUNDARY`/`IMPLEMENTATION_AUTHORIZATION`) are topically correct for this commit, but structurally uncitable (no session field, §15) and not cited by the commit itself either |
| Work item | Inferable from the cited document's filename prefix (informal) | Same work item, correctly identified — but only because the ledger's filename matches, not through any structured field |
| Lifecycle position | Unknown | **No transition exists for this era at all** — the lifecycle layer is silent, not merely ambiguous |
| Temporal ordering | Precise, reliable (`2026-09-28T16:05:11+02:00`) | The nearest transition is 24 days stale — temporal proximity, taken alone, would wrongly suggest continuity |
| Provenance | Human account + model variant, no session ID (§4) | No improvement — the transition record doesn't reach this far, so it adds nothing where the commit's own signal already runs out |

**Verdict: the hypothesis is WEAKENED, not supported, for this real case.** Shifting to the
transition as the observable unit does not reduce ambiguity here — it returns silence, and a naive
implementation that filled that silence by assuming "the last-opened, never-closed lane must still
apply" would produce an actively **wrong** attribution (wrong role, 24-day-stale context). That is
a worse failure mode than commit-level `UNDERDETERMINED`, not a better one.

**What actually helped, and it wasn't the transition:** the **`grants`** array holds real,
topically-correct authorization for this exact era of work — just not linked by any structured
field, exactly as §15 established. This refines rather than discards the original hypothesis:

> **The correct observable unit is not "transition" alone. It is: prefer a temporally-matching
> transition when one exists; fall back to a temporally-matching grant's scope when no transition
> covers the era; and when neither matches, report the gap honestly — never default to the nearest
> available record just because it exists.**

This is a genuine refinement, evidenced by a real test, not a defense of the original hypothesis
against contrary evidence — the original "transition is the unit" claim from §6 is now corrected,
disclosed as corrected, not silently replaced.

---

**Traceability:** `2026-09-28-KOS-governed-engineering-act-observation-capability-contract.md`
(the prior slice, retained as evidence) · `EKS-07-multi-process-coordination.md` (six real
incidents) · `KOS-SESSION-BOOTSTRAP-001` diagnostic + `AST-017` contract suite · `workflow-state.php`
invariants · `developer_guide/engineering_observations/` (Observation Runtime) · PKS's
`20260728_1710_what_is_pks_v1.md` (verified directly, this session) · a fresh 30-commit `git log`
sample, this pass, 2026-09-28.
