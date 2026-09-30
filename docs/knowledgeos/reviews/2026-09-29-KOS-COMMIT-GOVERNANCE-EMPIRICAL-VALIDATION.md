# Commit Governance — Empirical Validation Before Activation

**Date:** 2026-09-29. Corpus is evidence, not authority. The commit-message rule implemented in
`scripts/knowledgeos/governance/commit_rule/` is an experiment under test, not established theory.
This document does not activate the git hook, does not finalize the commit-message format, does not
build EKS/PKS, does not train ML, and does not freeze the Kernel.

## Erratum (2026-09-29, same day)

§8's table below calls `CitesSession(c,s) → improved M4 determination` `REFUTED`. That overclaims
what an n=14 null result actually shows. Corrected status: `SUPPORTED` only as "no marginal
information gain was observed in that specific sample" — `NOT SUPPORTED` as a general claim, and
explicitly `NOT FALSIFIED` for the possibility that *verified* (not merely cited) session evidence
could help. See `2026-09-29-KOS-COMMIT-GOVERNANCE-CONTROLLED-EXPERIMENT.md` for the corrected
framing and the follow-on experiment this correction motivated.

## 1 · Research question

> Does adding an explicitly recorded session identifier to a commit provide additional determinable
> evidence about the origin or attribution of that commit — measured, not assumed?

Broken into distinct, individually-testable propositions (per the required logic discipline):

```
CitesSession(c,s)    -- the commit message/body contains a session-id-shaped string
ProducedBy(c,s)      -- the commit was actually produced during session s
ExecutedBy(s,a)      -- session s was executed by actor a
AttributableTo(c,a)  -- the commit can be attributed to actor a
Authorized(c)        -- the commit's action was authorized
```

None assumed to imply the next. Each tested against real data where the corpus supports it, marked
`UNTESTABLE` where it doesn't.

## 2 · Existing evidence (recap, not re-derived)

`INV-ATTR-1`/`INV-ATTR-2`: self-declared identity is evidential, never attestable. Session/role
field population across 274 real transitions: 73.0%/27.4% (`EVIDENCE-OBSERVABILITY-AND-DETERMINATION.md`).
Three real, non-overlapping authorization mechanisms (`EVIDENCE-DETERMINATION-FAILURE-MODES.md`).

## 3 · Hypotheses

H-A: `CitesSession(c,s) → improved determination` (the implicit premise behind proposing the commit
rule at all). H-B: `CitesSession(c,s) → Authorized(c)` (would be a scope error to assume — tested
anyway for completeness). H-Mechanism: the three known governance mechanisms share a genuine
invariant justifying a common abstraction (tested in §9).

## 4 · Corpus / sample

The corrected 379-commit population (`ablation3_results.json`, the fixed extractor from the prior
addendum). No new corpus gathered — this experiment reuses existing data specifically to avoid the
cost of another large blind-review round before knowing whether one is even justified.

## 5 · Experimental method

**5a. Historical-format coverage** (§7 of the redirect): does any existing commit already match the
proposed `TYPE(SESSION):[TICKET] DESCRIPTION` syntax? Direct regex match across all 379 commit
subjects.

**5b. Informal session-id recoverability** (§8): does any existing commit already contain a
session-id-*shaped* string (a UUID) anywhere in its body, via the informal, pre-existing convention
this corpus already uses in places (e.g. `claude-code-session:<uuid>` in governance prose)? Direct
regex search, not restricted to the subject line.

**5c. Marginal information gain** (§9): for every commit that does contain such a string, check
whether its `M4` classification (`CONFIRMED`/`UNCITED`/`TEMPORAL_MISMATCH`) would have differed
without it — i.e., was the session-shaped string doing any classification work, or was the commit
already fully resolved by citation + temporal evidence alone?

## 6 · Results

**5a**: **0/379** commits match the new syntax. Expected — the rule was created today; this measures
absence of prior adoption, not a defect (`NOT_APPLICABLE`, per the redirect's own instruction not to
call pre-existence non-conformance a "failure").

**5b**: **14/379 (3.7%)** commits contain a UUID-shaped string somewhere in the body (an informal
session reference, not the new rule's format).

**5c — the decisive result**: **all 14** of those commits already had `temporal_within=True` and a
grant/work-item citation *independent of* the UUID string — meaning `M4` classifies all 14
`CONFIRMED` with or without the session reference. **Checked directly, not assumed**: for every one
of the 14, the citation and temporal evidence alone were already sufficient (table in the scratch
analysis, reproduced): e.g. `22234b234` cites `KOS-CONTRACT-NEUTRALITY-001` with an exact grant and
falls within its temporal window; `70d6361af` cites the work item (no exact grant, matching the
already-established `H4` finding that this doesn't matter) and is temporally valid. **In this sample,
adding the session-id signal changed zero classifications.** The raw correlation (100% `CONFIRMED`
among UUID-containing commits vs. 54.6% baseline) is real but fully explained by a confound: commits
careful enough to embed a session reference are also the ones already careful enough to cite a grant
and fall inside the temporal window — the session signal is redundant with evidence these commits
already had, not additive to it.

## 7 · Counterexamples

None found to H-A as originally hoped (i.e., no case where session evidence *rescued* an otherwise-
unresolved commit) — this null result is itself the counterexample to the implicit premise that
adding session evidence would help. No counterexample was needed against H-B (`CitesSession →
Authorized`) because no independently-verified ground truth exists for any of these 14 commits
(none overlap with the 13 already-blind-reviewed reference cases) — `H-B` is therefore `UNTESTABLE`
from this corpus as it stands, not confirmed or falsified.

## 8 · Information added by session ID

**Zero measured marginal information gain**, n=14, in this specific test. This does not prove
session evidence can *never* help — it proves that, in this corpus's current shape, the cases where
a session id happens to already be present are exactly the cases that didn't need it. A more
demanding test (checking whether session evidence resolves currently-`UNCITED` or
`TEMPORAL_MISMATCH` commits) is impossible to run today because zero such commits contain a session
reference at all (all 14 UUID-containing commits are in the `CONFIRMED` bucket already) — there is no
current counterfactual case to test against. This absence-of-a-hard-case is itself recorded as a
limitation of this experiment, not glossed over.

**Formal implication status**, tested against what's available:

| Implication | Status | Basis |
|---|---|---|
| `CitesSession(c,s) → ProducedBy(c,s)` | `UNTESTABLE` | no independent verification of true production session exists in this corpus |
| `ProducedBy(c,s) → ExecutedBy(s,a)` | `UNTESTABLE` | no actor-identity verification exists |
| `ExecutedBy(s,a) → AttributableTo(c,a)` | `UNTESTABLE` | same |
| `CitesSession(c,s) → Authorized(c)` | `UNTESTABLE` (not `SUPPORTED`) | the 14 cases' `Authorized` status is only known via the same deterministic model under test — circular, not independent evidence |
| `CitesSession(c,s) → improved M4 determination` | **`REFUTED` for this sample** | 0/14 cases show any classification change attributable to the session signal |

## 9 · Governance mechanism findings

Mechanism comparison table, built from the 13 already-blind-reviewed cases (no new review round
run — reusing existing evidence per the redirect's own cost discipline):

| Mechanism | Authority source | Applicability | Temporal semantics | Scope | Evidence | Determination |
|---|---|---|---|---|---|---|
| (a) JSON grant ledger | PO/ARB act, quoted verbatim in `humanActRef` | only work items with a `.claude/runtime/workflow/*.json` file | grant/transition dates checked against commit date | `scope`/`does NOT authorize` prose per grant | structured fields (grantId, status, transitions) | `M1`–`M4`, tested at scale |
| (b) Plan + HPA-approval chain | HPA "approved with amendments," in `docs/plans/*.md` | work items tracked as EP-01/EP-02 plans, not JSON-ledger items (e.g. `KOS-SNF-P5`) | approval date vs. execution date, checked manually by blind reviewers, not by `M1`–`M4` | amendments (Q-1..Q-4-style) in the plan doc | plan-file status headers + session-log corroboration | not covered by `M1`–`M4` at all; only tested via blind review (`REF-011`) |
| (c) Informal session-log commissioning | direct, verbatim, dated human instruction in `.claude/sessions/*.md` | commits predating or outside the ledger/plan mechanisms (e.g. `2a0696d6a`, 2026-08-04, 11 days before the ledger existed) | the mechanism's own historical existence is itself the temporal constraint (`REF-010`) | no formal scope field — the instruction's own text is the only boundary | free-text session-log entry | not covered by `M1`–`M4`; only tested via blind review (`GOLD-005` shape, `REF-006`, `REF-010`) |

**Do the mechanisms share a genuine invariant, or are they only superficially similar?** All three
share *exactly one* structural commonality across all 13 reviewed cases: **a dated, human-attributed
statement precedes and justifies the recorded act**, in every single case regardless of mechanism.
Everything else differs in kind, not merely configuration: (a) has structured, machine-parseable
fields; (b) and (c) do not. (a) and (b) have an explicit scope/amendment boundary; (c) does not. Only
(a) is machine-checked by any deterministic model built so far. **This one shared invariant
("dated human-attributed statement precedes the act") is a real candidate for a minimal shared
interface — but a three-field one (`date`, `attributed_human_statement`, `precedes_act`), far
narrower than the seven-field `Governance Mechanism` abstraction hypothesized in the prior document.
That earlier 7-field hypothesis is not supported by this comparison and should be narrowed, not
adopted as-is.**

**Search for a 4th mechanism** (§13, light deterministic census, not exhaustive): grepped the review
corpus for authority-source phrases. Found `PO/ARB` (2166×), `HPA` (832×), `Human Principal
Architect` (48×), `principal-architect` (6×) — **these appear to be the same underlying authority
referred to by different names across different eras of the corpus, not evidence of a structurally
distinct fourth mechanism.** No new mechanism surfaced by this census; a deeper search was not run,
consistent with "use deterministic search efficiently, not exhaustive manual reading."

## 10 · Kernel implications

**No concept promoted.** The narrowed 3-field shared invariant found in §9 is `HYPOTHESIS`, one data
point (this comparison), not independently replicated. `Governance Mechanism` remains a domain
concept at most, unchanged from the prior document's classification. Session id / `CitesSession` is
now more precisely characterized: `OBSERVED` as a real, present-when-cited signal, but `REFUTED` (for
this sample) as an *additive* evidence source — this refines rather than promotes its kernel status.

## 11 · EKS/PKS implications

None. `OQ-11` untouched. The mechanism table (§9) is evidence *for future* EKS/PKS boundary work
(mechanism (a) is squarely EKS-shaped: code-tested, structured; (b)/(c) are less clearly
EKS-vs-PKS-specific), but no boundary decision is made here.

## 12 · UNKNOWN

Whether session evidence would show a different result on a *harder* sample (one containing
currently-`UNCITED`/`TEMPORAL_MISMATCH` commits with a session reference) — untestable today because
no such commit exists in this corpus yet. Whether the narrowed 3-field mechanism invariant (§9)
survives contact with a mechanism this session hasn't found. Whether `ProducedBy`/`ExecutedBy`/
`AttributableTo` can ever be independently tested in this corpus at all, absent some verification
channel this research hasn't identified (e.g., IDE/CLI telemetry correlated with commit timestamps —
not investigated, would itself be a privacy/scope question before a technical one).

## 13 · Next smallest decisive experiment

Wait for (or deliberately construct, e.g. by using the now-implemented rule on a handful of this
session's own upcoming commits, advisory-only, not enforced) a small number of real commits that
embed a session id *and* would otherwise have been `UNCITED` or `TEMPORAL_MISMATCH` — that is the
one case type this experiment could not test today for lack of examples, and it is the case type
that would actually distinguish "session evidence helps" from "session evidence is redundant." This
is more decisive than expanding the blind-review set further, and cheaper: it requires zero new
agent dispatches, only accumulating a handful of real commits over ordinary future work and then
re-running this same comparison.

---

**Traceability:** `2026-09-29-KOS-EKS-PKS-GOVERNANCE-ARCHITECTURE.md` (source of the implemented
commit rule and the withdrawn "two blocking decisions" framing this document corrects) ·
`2026-09-29-KOS-EVIDENCE-DETERMINATION-FAILURE-MODES.md` (source of the 3-mechanism finding, §9's
starting point) · `ablation3_results.json` (corrected 379-commit population, reused without
modification) · INV-ATTR-1/INV-ATTR-2 (load-bearing throughout §6–§8).
