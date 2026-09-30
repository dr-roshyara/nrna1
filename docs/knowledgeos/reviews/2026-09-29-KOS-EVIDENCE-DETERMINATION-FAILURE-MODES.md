# Evidence Determination — Failure-Mode Discrimination Experiment

**Date:** 2026-09-29. Corpus is evidence, not authority. `M1`–`M4` and the previously-committed
reference documents are hypotheses under test, not ground truth. This document does not resolve
`OQ-11`, does not evaluate `K5=(S,A,R)`, does not train ML, does not redesign `Fuse`, and does not
implement architecture or a kernel.

## 0 · Terminology correction (accepted, not defended)

The prior document's title, "gold-standard," was premature. Reviewer A and Reviewer B for every case
in this whole research line are both instances of the same underlying model as this session, run
with only session-context independence (no shared conversation, no exposure to prior conclusions) —
not human-expert independence or model-family independence. **From this point on, the committed
5-case set is called the "Blind Double-Review Reference Set," not a gold standard**, until multiple
independent evidence sources and a stabilized label ontology justify the stronger term. The prior
document and its `.jsonl` file are not renamed (that would rewrite history silently); this is the
disclosed correction.

## 1 · Research question

Not "can Claude classify more cases," but: **which evidence relation is missing from the current
deterministic models when they disagree with independent review, and are the resulting failure
modes systematic or isolated?**

## 2 · Current evidence model

`M1` (work-item citation) → `M2` (+ exact grant-ID citation, informational) → `M3` (+ scope/
protected-artifact check against all grants) → `M4` (+ temporal containment), as built and tested in
`evidence-fusion-model.md` and re-derived in `ablation2.py`. Treated throughout as a hypothesis, not
an answer.

## 3 · Failure-mode taxonomy

| Failure mode | Case(s) | Model that failed | Missing/misused evidence | Error type | Status |
|---|---|---|---|---|---|
| **Citation-gate blind spot** | GOLD-001 (`9f83a369c`) | `M1`–`M4` (all — commit outside the 379-population entirely) | Grant `G-KOS-CONTRACT-ARTIFACT-UPDATE-AMD3`'s standing prohibition; no ledger entry to cite in the first place | Discovery error | `OBSERVED`, gold-confirmed |
| **Temporal false positive** | GOLD-002 (`6b983bc12`) | `M4` (`TEMPORAL_MISMATCH`, gold = `AUTHORIZED`) | The date-window check is too shallow — it doesn't recognize that the *content* of a governance-verification document can itself corroborate authorization regardless of date fit | Temporal reasoning error | `OBSERVED`, n=1 (unreplicated this round — REF-007/`386942459` was also stratum A but its ledger date happened to fit cleanly once traced, so it doesn't independently replicate the *mismatch*) |
| **Grant-chain false abstention** | GOLD-004 (`2bdd68b2b`), REF-008/009 (`c9915445d`, `12c9c297e`) | `M4` (`UNDERDETERMINED`, gold = `AUTHORIZED` in all 3) | A complete grant chain (`G-KOS-TOPO-ARCH`) exists but is never searched for by content — the model only checks citation/temporal fields, not prose grant-chain references | Discovery + representation error | `OBSERVED`, **replicated 3/3 in this round — the strongest systematic finding of this experiment** |
| **Self-declaration ambiguity** | GOLD-003 (`0727c9342`) | Human/blind-review disagreement itself, not a deterministic model | Self-declared "human-authorized" claims are evidential (INV-ATTR-1/2), not dispositive | Epistemic-status error | `OBSERVED`, n=1 |
| **Exact-grant-citation not necessary** | GOLD-005 (`1bc550019`), REF-012/013 (`67a8e75e8`, `461babb13`) | `M2` (treats `CITED_NO_GRANT` as weaker evidence) | Work-item-level or track-level citation is routinely sufficient; requiring exact grant-ID citation over-penalizes normal governance bookkeeping | Scope/authority-reasoning error (model too strict, not too lenient) | `OBSERVED`, replicated 3/3, plus population-level support (161 `CITED_NO_GRANT` commits, 77.6% still `M4`-`CONFIRMED`) |
| **Representation error — grant ID present, wrong search location** | REF-012 (`67a8e75e8`) | `M2` | Grant ID is cited inside the *added file content*, not the commit *message* — `M2`'s extraction only searches the message | Representation error | `OBSERVED`, n=1, mechanistically explained (not a mystery — a fixable extraction-scope bug) |
| **Work-item misattribution at the extraction layer** | REF-008/009 vs. GOLD-004 | `ablation2.py`'s `find_work_item` (substring match) | The extractor picked `KOS-ACTIVATION-REPORTING-001` when the real work item was `KOS-EXEC-TOPOLOGY-001` — likely because the commit body mentions multiple work-item-shaped strings and substring matching is greedy/ambiguous | Representation error, **at the stratification layer itself, before any classification happens** | `OBSERVED`, replicated 2/2 within the same stratum-B pull |
| **Anachronistic evaluation** | REF-010 (`2a0696d6a`) | `M1`–`M4` (labels it `UNCITED`, as if evidence is missing) | The commit predates the JSON grant-ledger mechanism's existence by ~11 days (ledger first appears ~2026-08-15; this commit is 2026-08-04) — there is no grant to find because that *kind* of record did not yet exist, not because discovery failed | **New failure mode, not previously named**: applying a later-invented evidence schema retroactively and treating its absence as a gap rather than a category error | `OBSERVED`, n=1, logically clear |
| **Authorization-mechanism plurality** | REF-006 (`480207d66`), REF-011 (`32554cbd7`), REF-010 | none of `M1`–`M4` reach these tracks at all | At least three distinct, non-overlapping authorization-tracking mechanisms coexist in this corpus: (a) the JSON grant/work-item ledger `M1`–`M4` was built against; (b) a plan-file + HPA-approval-with-amendments + EP-02-review chain (`docs/plans/`); (c) informal, session-log-recorded direct human commissioning with no grant record of any kind | **New failure mode**: `M1`–`M4` only ever searches mechanism (a); mechanisms (b) and (c) are real, governed, and produce `AUTHORIZED`-worthy evidence the model never looks for | `OBSERVED`, 3 independent instances |
| **Record vs. substantive ambiguity** | GOLD-001 | Adjudication itself | Whether "no ledger entry lifting a standing prohibition" settles the *substantive* legitimacy question, not just the *procedural* one | Ontological/label-scheme gap | `OBSERVED`, n=1, motivates §5 |
| **Not-applicable vs. authorized-by-alternate-mechanism** | REF-006 | Reviewer disagreement itself | Whether "formal ledger doesn't reach this case" should be labeled `NOT_APPLICABLE` (question doesn't apply) or `AUTHORIZED` (a lighter real mechanism answers it) | Ontological/label-scheme gap | `OBSERVED`, n=1, adjudicated `AUTHORIZED_BY_ALTERNATE_MECHANISM` (see `BLIND-REFERENCE-SET-ROUND2.jsonl` REF-006) |

## 4 · Stratified sample (this round)

8 new cases, 2 per stratum (`TEMPORAL_MISMATCH`, `UNDERDETERMINED`, `UNCITED`, `CITED_NO_GRANT`),
selected from the real `M1`–`M4` disagreement distribution over the 379-commit population, not
randomly. Full records: `2026-09-29-KOS-BLIND-REFERENCE-SET-ROUND2.jsonl` (REF-006..013). Combined
with the round-1 reference set, this brings the cumulative reviewed population to **13 cases**, 26
individual blind reviews.

## 5 · Blind review results (this round)

7/8 cases unanimous `AUTHORIZED`. 1/8 disagreement (REF-006: `NOT_APPLICABLE` vs. `AUTHORIZED`,
adjudicated `AUTHORIZED_BY_ALTERNATE_MECHANISM`). Full per-case reviewer text preserved in the
`.jsonl` file, not reproduced here.

## 6 · Model-vs-reference disagreements (cumulative, 13 cases)

| # unanimous `AUTHORIZED` | # disagreement | # model-vs-gold mismatch (of the 13) |
|---|---|---|
| 10/13 (77%) | 3/13 (23%) | 4/13 — GOLD-001 (unreachable), GOLD-002 (false flag), GOLD-004+REF-008+REF-009 (3× false abstention on the same underlying pattern) |

**Do not read 77%/23% as an accuracy or base-rate claim about the repository** — this is a
*stratified* sample deliberately over-weighted toward disagreement-prone strata, not a random sample.
What it legitimately supports: **the deterministic model's disagreements with independent review are
concentrated, not scattered** — 3 of the 4 mismatches are the *same* failure mode (grant-chain false
abstention), now replicated 3/3 within its own targeted stratum. That is a much stronger signal than
"the model is imperfect."

## 7 · Logical implications tested (H1–H8)

| # | Implication | Verdict | Evidence |
|---|---|---|---|
| H1 | `CitesWorkItem(x,w) → Authorized(x)` | **INVALID** | 220/379 (58.0%) commits cite a work item at all; many of those are `UNCITED`-adjacent or `TEMPORAL_MISMATCH` cases now shown to often still be `AUTHORIZED` on review, but citation alone was never claimed sufficient by `M1` itself — this is a sanity-check confirmation, not a new finding |
| H2 | `CitesGrant(x,g) → Authorized(x)` | Weakly `SUPPORTED`, not proven | Every case in this round citing a specific grant ID (REF-007, REF-012) was independently confirmed `AUTHORIZED` — n too small (2) to generalize further |
| H3 | `Authorized(g,x) → CitesGrant(x,g)` | **Already falsified — not reopened** | Per `EVIDENCE-OBSERVABILITY-AND-DETERMINATION.md` §6.1, 75/144 (52.1%) exact-match / 62/144 (43.1%) loose-match grants are never cited by any commit |
| H4 | `¬CitesGrant(x,g) → Unauthorized(x)` | **INVALID, now strongly supported by replication** | GOLD-005, REF-012, REF-013 all `CITED_NO_GRANT` yet `AUTHORIZED`; population-level, 125/161 (77.6%) `CITED_NO_GRANT` commits are `M4`-`CONFIRMED` by the model's own logic |
| H5 | `¬DiscoverableByModel(E) → ¬Exists(E)` | **INVALID, now the best-replicated finding in this whole experiment** | GOLD-004 + REF-008 + REF-009 (3/3 same pattern: grant chain exists in prose, model's extraction path never follows it) |
| H6 | `SelfDeclaredAuthorization(x) → Authorized(x)` | **INVALID as a sufficient condition** | GOLD-003: self-declaration alone was adjudicated `UNDERDETERMINED`, not `AUTHORIZED`, per `INV-ATTR-1/2` |
| H7 | `NoCorroboratingLedgerEvent(x) → Unauthorized(x)` | **INVALID** | REF-010: no ledger event exists because the ledger mechanism itself postdates the commit; REF-006/REF-011: no ledger event exists because a *different*, real mechanism governs. Correct reading is closer to `UNDERDETERMINED_BY_RECORD` or `NOT_APPLICABLE_TO_THIS_MECHANISM`, never a default to `Unauthorized` |
| H8 | `StandingProhibition(g,x) ∧ ¬RecordedRelease(g,x) ∧ xOccurred → UNAUTHORIZED_BY_RECORD` | **SUPPORTED, n=1** | GOLD-001 remains the only case with an actual standing, on-point prohibition contradicted — the implication held there, but n=1 is not a generalizable test; every other "no grant found" case in this round turned out to have *no* standing prohibition at all (a structurally different, more common situation) |

## 8 · Counterexamples (collected, not new beyond §7's table)

The decisive counterexample set is: GOLD-004/REF-008/REF-009 for H5 (discoverability ≠ existence,
now the most solid empirical result here); REF-010 for H7 (ledger silence can mean "mechanism didn't
exist yet," a temporal/historical category, not an evidentiary gap); GOLD-005/REF-012/REF-013 for H4
(exact-grant citation is demonstrably not required by the corpus's own practice).

## 9 · Discovery vs. representation vs. reasoning errors

- **Discovery errors** (evidence existed, model never looked): GOLD-004/REF-008/REF-009 (grant chain
  never searched by content), GOLD-001 (citation-gated, never reaches the case at all).
- **Representation errors** (evidence existed, in a form the model's chosen extraction path
  couldn't reach): REF-012 (grant ID in file content, not commit message), and the
  work-item-misattribution bug found while stratifying (REF-008/009 mislabeled by `ablation2.py`'s
  substring matcher before any reviewer even looked at them).
- **Temporal reasoning errors**: GOLD-002 (date-window check too shallow to recognize a
  content-corroborated case).
- **Scope/authority reasoning errors**: H4's over-strict exact-grant-citation requirement.
- **Epistemic-status errors**: GOLD-003 (self-declaration treated too strongly by one reviewer, not
  by the deterministic model itself — this one is a reviewer-level, not model-level, finding).
- **Category errors** (a genuinely new class, not in the original 6-error list the prior redirect
  proposed): REF-010's anachronistic evaluation and REF-006/REF-011's mechanism-plurality — the
  model isn't reasoning wrong within its evidence scheme, it's applying the wrong evidence scheme
  entirely for a subset of the corpus.

## 10 · Evidence-ontology findings

Two structural findings, both `HYPOTHESIS`, neither adopted into architecture or kernel:

1. **Authorization determination may be at minimum two-dimensional** (record/procedural vs.
   substantive), as the prior redirect proposed and GOLD-001 continues to support — still
   `HYPOTHESIS`, n=1, not generalized further this round since no new case required the compound
   label.
2. **A third, orthogonal dimension is now independently evidenced**: *which authorization mechanism
   is even in scope* — at least three real, non-overlapping mechanisms coexist (JSON ledger;
   plan+HPA-approval chain; informal session-log commissioning), plus a temporal validity condition
   (did the mechanism being checked against even exist yet at the commit's date). This is **not**
   proposed as a kernel change — it is reported as a structural finding about this corpus's own
   governance history, worth testing further before any architectural consequence is drawn.

## 11 · Authorization-level findings

`M1`–`M4` is not wrong on its own terms so much as **narrow**: built and validated only against
mechanism (a). Its false abstentions and false flags are concentrated exactly where a case belongs
to mechanism (b), (c), or predates all three mechanisms' existence. This reframes "the model's
accuracy" as a category-coverage question, not a tuning question — extending `M3`'s prose-search
depth (per the smallest-next-experiment recommendation) would fix the grant-chain false-abstention
mode, but would not by itself fix the mechanism-plurality or anachronism modes, which need the model
to recognize which authorization system applies before attempting to apply it.

## 12 · What remains UNKNOWN

Whether the three identified mechanisms are exhaustive (only tested against 13 cases); whether the
grant-chain false-abstention mode generalizes beyond `KOS-EXEC-TOPOLOGY-001`-shaped cases (all 3
replications came from the same underlying work item); whether GOLD-002's temporal false positive is
systematic or a one-off (unreplicated this round); the true boundary between mechanisms (b) and (c)
(REF-006 and REF-011 might be the same mechanism under different degrees of formality, not two
distinct ones — not tested).

## 13 · What was FALSIFIED

H3 (already falsified, not reopened), H4, H5, H6, H7 all fail as stated. The "gold standard"
terminology (§0). The assumption that "no ledger evidence" defaults to suspicion (REF-010, REF-006,
REF-011 all show real, benign explanations for ledger silence, roughly 3× more often in this sample
than the one genuine violation case).

## 14 · What is currently SUPPORTED

The citation-gate blind spot (GOLD-001, now further contextualized by REF-010's anachronism finding
as a *related but distinct* category of "ledger silence"). The grant-chain false-abstention mode,
now the best-replicated finding (3/3) of the whole experiment — a concrete, scoped, fixable
`M3`-extension target. The over-strictness of exact-grant-ID citation as a requirement (H4, 5/5
replication counting population-level support). The mechanism-plurality finding (3 independent real
cases). Uncertainty/indeterminacy remains the strongest kernel candidate on prior evidence; nothing
here changes that, and nothing here is treated as new kernel evidence per the explicit instruction
not to over-interpret repeated vocabulary as kernel support.

## 15 · Next smallest decisive experiment

Given the grant-chain false-abstention mode is now 3/3 replicated and entirely concentrated in one
work item's lineage (`KOS-EXEC-TOPOLOGY-001`), the next decisive test is narrow and cheap: pull 2–3
more `UNDERDETERMINED`-stratum cases from *different* work items (not `KOS-EXEC-TOPOLOGY-001`-derived)
to check whether the false-abstention pattern is a property of grant-chain discovery in general, or
specific to how that one work item's grants happen to be worded/structured. This is more decisive
than growing the reference set further in other strata, since strata A/C/D are now either weakly
supported (A, n=1 unreplicated) or already strongly supported (C, D) — B is the one stratum with a
strong-but-possibly-confounded result.

---

## 16 · Addendum (2026-09-29, same day) — the planned next experiment was impossible, and that's decisive

Attempted §15's recommendation directly (pull `UNDERDETERMINED`-stratum cases from work items other
than `KOS-EXEC-TOPOLOGY-001`) before dispatching any new blind reviewers. Direct inspection
(`git show -s --format=%B` on all 6 `UNDERDETERMINED` commits, plus a check of `find_work_item()`'s
sort order in `ablation2.py`) found:

**All 6 `UNDERDETERMINED` commits in the entire 379-commit population are attributed to the same
work-item string, `KOS-ACTIVATION-REPORTING-001`, by one specific, now fully mechanistically-explained
extraction bug**: `find_work_item()` sorts the 22 candidate work-item names by string length,
descending, and returns the *first* substring match. `KOS-ACTIVATION-REPORTING-001` (29 characters)
is the longest of all 22 names, so whenever a commit body legitimately cross-references multiple
work items (a common pattern in this corpus's status-tracking commit messages), this one string wins
regardless of which work item the commit is actually about. Direct grep confirms each of the 6
commits' *real* subject is a different work item: `KOS-EXEC-TOPOLOGY-001` (×4, matching the 3 already
blind-reviewed and confirmed `AUTHORIZED`), `KOS-GOV-ATTRIBUTION-001` (×1), `KOS-SESSION-DISCOVERY-001`
(×2), `KOS-AI-ORCH-001-INC1` (×1) — no commit is actually about `KOS-ACTIVATION-REPORTING-001` at all.

**Consequence for §15's planned experiment**: it cannot be run as designed, because there is no
second, independent source of `UNDERDETERMINED` cases in this population — the entire stratum is one
bug's output, not 6 separate samples. This is itself the decisive result: **the grant-chain
false-abstention finding (§3, §7 H5) cannot be shown to generalize within this 379-commit population,
full stop — not because the effect is absent elsewhere, but because this population's own
`UNDERDETERMINED` stratum was never built from independent cases to test it against.** Generalizing
the finding further would require either fixing the extractor (tie-break by relevance/position, not
length, or search each candidate independently rather than picking one winner) and re-running the
full ablation, or selecting new candidate cases by a different, unbiased method entirely (e.g.
`M3`'s scope-check logic rather than `M1`'s work-item attribution). Neither was attempted here —
correctly stopping at the boundary of what this experiment's own data can support, rather than
patching the extractor and quietly re-running to get a bigger number.

**New failure mode for the taxonomy (§3), fifth of its kind**: **stratification-tool bias** — the
tool used to select which cases to test can itself introduce a systematic, undisclosed sampling
bias (here: silently favoring the longest-named work item whenever multiple are mentioned), which
then masquerades as a property of the *evidence model* being tested rather than a property of the
*test harness*. This is distinct from a representation error (§9) in an important way: a
representation error means the model misses real evidence that exists; a stratification-tool bias
means the *experiment itself* was silently sampling from a narrower population than intended.

**Status**: `OBSERVED`, fully explained, not merely suspected. No further action taken this round —
per the STOP discipline, this addendum reports the finding and stops rather than automatically
patching the extractor or re-running a bigger ablation.

---

## 17 · Addendum 2 (2026-09-29, same day) — extractor fixed and full ablation re-run, per explicit instruction

§16 named two options; you chose (a): fix `find_work_item()` and re-run. Implemented as a joint
earliest-string-position match across both direct work-item-name strings *and* grant-ID
cross-references (compared together, not direct-name-first) — the bug was partly the
longest-string-wins tie-break, and partly that the original one-line fallback-to-grant-ID logic only
ran when zero direct-name matches existed, so a correct-but-late grant-ID reference could still lose
to an incorrect-but-present direct-name match found anywhere else in the body. Verified against all 6
known-bad commits (5/6 fixed by earliest-position alone; the 6th, `12c9c297e`, needed the joint
grant-ID comparison — its only work-item evidence is the grant reference `G-KOS-TOPO-ARCH` at an
earlier position than the incidental `KOS-ACTIVATION-REPORTING-001` mention later in the same body).

**Result: all 6/6 now resolve correctly.** Re-ran the full 379-commit `M1`–`M4` ablation with the
fixed extractor (`ablation3.py`, `ablation3_results.json`, both in the session scratchpad — not
committed; only these aggregate results are). Headline changes:

| | Old (buggy extractor) | New (fixed) |
|---|---|---|
| `UNCITED` (S1) | 159/379 (41.9%) | 145/379 (38.3%) |
| `TEMPORAL_MISMATCH` (S4) | 35/379 (9.2%) | 27/379 (7.1%) |
| `CONFIRMED` (S4) | 179/379 (47.2%) | 207/379 (54.6%) |
| `UNDERDETERMINED` (S4) | 6/379 (1.6%) | **0/379 (0%)** |
| Total commits reattributed | — | 40/379 (10.6%) |
| `H`(classifier output, S1) | 0.9812 bits (98.1% of 1 bit max) | 0.9598 bits (96.0% of 1 bit max) |
| `H`(classifier output, S4) | 1.4489 bits (72.4% of 2-bit max, 4 categories) | 1.2784 bits (80.7% of a **1.585-bit max, 3 categories** — `UNDERDETERMINED` no longer occurs at all) |

**The `UNDERDETERMINED` category is entirely eliminated population-wide, not just for the 6
originally-flagged cases** — confirming §16's diagnosis precisely: it was never a real evidentiary
state produced by `M4`'s logic, only an artifact of one work item's name being systematically
mis-selected. Note the S4 entropy comparison changes category count (4→3), so the raw percentages
are not directly comparable without accounting for that — flagged explicitly per this whole research
line's own entropy discipline (§8 of `EVIDENCE-OBSERVABILITY-AND-DETERMINATION.md`).

**Cross-checked against all 13 already-blind-reviewed cases**: all 6 originally-misattributed cases
(`GOLD-004`, `REF-008`, `REF-009`, plus 3 more of the original 6 `UNDERDETERMINED` commits not
previously blind-reviewed) now correctly attribute to their real work items, matching what blind
review had already independently found. `GOLD-002` (`6b983bc12`) still classifies `TEMPORAL_MISMATCH`
under the corrected attribution — **the temporal false-positive finding is confirmed independent of
the extraction bug, not an artifact of it.** `1bc550019` (`GOLD-005`) is also reattributed (old:
`KOS-OPERATING-MODEL-001-AMENDMENT-001`, new: `KOS-CONTRACT-NEUTRALITY-001`) — matching what its
blind reviewers had already found directly from primary sources, meaning the *old* extractor was
wrong there too even though the manually-selected stratum label happened not to matter for that
case's outcome.

**What this does and doesn't settle**: the grant-chain false-abstention finding (§3, §7 H5,
originally 3/3 from one work item's `UNDERDETERMINED` cases) can no longer be tested via the
`UNDERDETERMINED` stratum at all, because that stratum no longer exists under the corrected
extractor. This doesn't refute the finding — the 3 replicated cases were real, correctly
blind-reviewed, and remain valid evidence that `M4` can produce a false abstention when a grant chain
exists in prose it doesn't search. It does mean **no further replication of that specific pattern is
available from this population as currently classified** — the corrected `M4` simply doesn't produce
`UNDERDETERMINED` output anymore to sample from. Whether `M4` still has *other*, undiscovered
false-abstention modes under the corrected data is untested and not claimed either way.

**Status of `ablation2.py`/`ablation2_results.json` (used throughout the round-1/round-2 reference
sets and the `EVIDENCE-OBSERVABILITY-AND-DETERMINATION.md`/`GOVERNANCE-CONSTRAINT-OBSERVABILITY.md`
population statistics)**: superseded by `ablation3.py` for any future stratification or population
statistic, per this addendum — not retroactively edited into those documents, consistent with this
whole research line's stated discipline of disclosing corrections forward rather than rewriting
history. Any future document computing population-level `M1`–`M4` statistics should use the fixed
extractor and cite this addendum.

---

## 18 · Addendum 3 (2026-09-29, same day) — §7's H2 row is internally inconsistent with §3/§4 of this same document

Found while an independent adversarial review (`kos-theory-reviewer`) verified a citation in a
downstream document (`2026-09-29-KOS-EVIDENCE-PROPOSITION-MATRIX.md`) against this one, across 5
review rounds. §7's original H2 row read:

> H2 | `CitesGrant(x,g) → Authorized(x)` | Weakly `SUPPORTED`, not proven | Every case in this round
> citing a specific grant ID (`REF-007`, `REF-012`) was independently confirmed `AUTHORIZED` — n too
> small (2) to generalize further

**This is wrong on both named cases, checked directly against this document's own §3/§4 and against
the repository:**

- **`REF-007` cites zero grant IDs anywhere** — not in the commit message (`git log -1 --format=%B
  386942459 | grep -ic "G-KOS"` → 0, checked repeatedly across multiple independent review rounds)
  and not in any file it changed. Its `AUTHORIZED` verdict came entirely from reviewers
  independently tracing the grant chain (`G-KOS-ATTRARCH-DESIGN` +`AMD1`/`AMD2`) by content — this
  is the grant-chain-discovery pattern this document's own §3 names as a *distinct* failure mode
  (`H5`), not an instance of `CitesGrant` being true.
- **`REF-012`'s grant ID is cited only in the file content the commit added, never the commit
  message** — this document's own §3 states this explicitly ("the grant ID is cited, but inside the
  added file content, not the commit message"). H2 cited it as if it satisfied `CitesGrant(x,g)` at
  the message level without carrying that qualifier forward.
- **`REF-009`, the one case in the same round (§4's stratified sample) that genuinely cites its own
  covering grant in the commit message** ("linkage `G-KOS-TOPO-ARCH`," "within `G-KOS-TOPO-ARCH`
  only" — verified in full context, not just a string match), **is omitted from H2 entirely.**

**Corrected H2:**

> H2 | `CitesGrant(x,g) → Authorized(x)`, strictly (grant cited **as the commit's own covering
> authorization**, in the message itself) | Weakly `SUPPORTED`, **n=1** (`REF-009` only — the sole
> case in this round meeting the strict condition; `GOLD-002` and `GOLD-005` from the earlier round
> were also checked in full context and excluded, since their `G-KOS-*` mentions are analytical
> discussion of *other* grants concluding those grants do *not* cover the act in question, never a
> citation of their own authorization) | `REF-007` and `REF-012` do not test this implication at all
> — `REF-007` cites no grant ID anywhere (its `AUTHORIZED` verdict is evidence for `H5`, not `H2`);
> `REF-012`'s citation is file-content-only, a distinct, weaker evidentiary condition this row never
> claimed to cover

This does not change any other row in §7, any kernel-candidate status, or any conclusion in §9–§17 —
the correction is local to H2's own case list and evidentiary count. Recorded as a further, disclosed
correction to an already-committed document, per this whole research line's standing discipline —
not silently edited.

---

**Traceability:** `2026-09-29-KOS-BLIND-REFERENCE-SET-ROUND2.jsonl` (this round's 8 cases, full
provenance) · `2026-09-29-KOS-GOLD-STANDARD.jsonl` / `-EVIDENCE-DATASET.md` (round 1, terminology
corrected in §0, content not altered) · `2026-09-29-KOS-EVIDENCE-OBSERVABILITY-AND-DETERMINATION.md`
(H3's source, §4.3's provenance-vs-content distinction reused in §9's category-error analysis) ·
`2026-09-29-KOS-evidence-fusion-model.md` (source of `M1`–`M4`) ·
`2026-09-29-KOS-EVIDENCE-PROPOSITION-MATRIX.md` (source of §18's correction, itself independently
reviewed across 5 rounds before this defect was found) · INV-ATTR-1/INV-ATTR-2 (load-bearing
in H6/GOLD-003).
