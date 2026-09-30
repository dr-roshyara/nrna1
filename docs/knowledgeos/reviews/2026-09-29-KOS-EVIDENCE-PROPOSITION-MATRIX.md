# Evidence-Proposition Matrix — What Can Each Evidence Source Actually Establish?

**Date:** 2026-09-29. Corpus is evidence, not authority. Does not freeze the Kernel, does not
implement EKS/PKS, does not train ML, does not activate the hook. **Reviewed by an independent
adversarial reviewer (`kos-theory-reviewer` agent) before first publication — verdict `FAIL`, 9
findings, all accepted and fixed below. Never published in the FAIL-state version**: the draft this
document replaces existed only as a local, uncommitted file, so this is a pre-publication revision,
not a forward erratum on committed content.

## Research question

> What logical proposition can each independently observable evidence source legitimately
> establish, and how can multiple propositions combine without overclaiming authorization,
> attribution, provenance, production, or causality?

## Evidence-Proposition Matrix (validated against real corpus findings, corrected per review)

| Evidence source | Observable fact | Supported proposition | Unsupported proposition | Dependencies | Independence risk | Status |
|---|---|---|---|---|---|---|
| Commit (git object) | hash, date, author string, message, diff | `CommitExists`, `CommitContent` | `Authorized`, `ActorAttested` (author string self-declared, `INV-ATTR-1/2`); **`CommitDate` is also client-set and forgeable — corrected: does not escape the same caveat** | git object model | hash integrity ≠ evidentiary independence (reviewer finding 5, accepted) — the hash proves content wasn't altered *after* commit, nothing about the metadata's truth *at* commit | `VALIDATED`, caveat widened |
| Grant ledger entry | record with `status`/`scope`/`humanActRef` fields | `GrantRecorded`, `GrantStatus` | `WorkExecuted`, substantive authorization (`GOLD-001`: record can exist without covering a specific later act) | `.claude/runtime/workflow/*.json` | record/substance gap, confirmed real | `VALIDATED`, record/substance caveat explicit |
| Session log (date file) | work-item string(s), narrative prose | `WorkContextRecorded` | `ActorExecuted`, `ProducedBy(commit,session)` — **corrected reason**: not "no commit cites a session" (false — 14/379 do, per `EMPIRICAL-VALIDATION.md` §6), but "none of those 14 has independent ground truth to test against" | `.claude/sessions/*.md` | the log's own authorship is *also* self-declared — same `INV-ATTR-1/2` caveat one level up | `VALIDATED`, reasoning corrected |
| Session+WorkItem co-occurrence (S1/S2) | mention count ≥1/≥2 | `CorroboratedWorkItemContext` | `Authorized` — `b96374c8f` corroborates yet is self-declared `NOT_APPLICABLE`; **downgraded from `REFUTED` to `NOT_SUPPORTED`, see below** | both above | multiple work items same day not fully ruled out as a confound | `VALIDATED`, weakened per this case, severity corrected |
| Session+Artifact (S3) | shared file-path string | weak activity-linkage signal | `Ownership`, `Authorized` | both + git diff | still string-based, not causally verified | `HYPOTHESIS`, thin evidence (n small) |
| Human governance record (quoted `humanActRef`) | quoted text in a grant/registration doc | `GovernanceDecisionRecorded` | `TechnicalExecution`, substantive authorization | governance documents | self-declared by the same recording process | `VALIDATED`, same caveat |
| Git author identity string | `git log --format=%an/%ae` | `DeclaredGitActor` | `RealPerson`, `ActorAttested` | local git config, unverified | high — trivially spoofable, never independently checked anywhere in this research line | `VALIDATED` as weak/unverifiable |

## Formal implication classification (corrected)

| Implication | Status |
|---|---|
| `SessionLogMentions(w,t) → WorkContextRecorded(w,t)` | `PROVEN_FROM_DEFINITIONS` |
| `SessionLogMentions(w,t) → ProducedBy(commit,session)` | `UNTESTABLE` — corrected reason: 14/379 real commits *do* contain a session-shaped string (self-contradiction in the pre-review draft, now fixed), but none has independent ground truth to verify against |
| `SessionLogMentions(w,t) → Authorized(commit)` | **`NOT_SUPPORTED`** (corrected from `REFUTED`) — a refutation needs `NOT_APPLICABLE(b96374c8f) ⇒ ¬Authorized(b96374c8f)` proven, but this same document rates `NOT_APPLICABLE` as `HYPOTHESIS`, and `b96374c8f`'s own "no authorization needed" claim is itself only a self-declared commit sentence, never blind-reviewed (`INV-ATTR` caveat applies), and `REF-006` shows real reviewers split on exactly this `NOT_APPLICABLE`-vs-`AUTHORIZED` question |
| `GrantRecorded(g,w,t) → Authorized(commit)`, unqualified | `REFUTED` — `GOLD-001` |
| `GrantRecorded(g,w,t) ∧ CommitCites(c,g) → Authorized(c)`, qualified (grant cited **as the commit's own covering authorization**, not merely mentioned) | **Corrected a third time — this row required two prior review rounds to get right, and the second correction was itself still wrong.** The condition this row needs is not "a `G-KOS-*` string appears in the message" but "the message cites a grant *as its own covering authorization*" — a distinction a raw grep count cannot make, confirmed by reading full context for every hit. Checked all 13 cases directly: `GOLD-002` (5 `G-KOS-*` hits, not 2) discusses several *other* grants analytically — mostly to conclude they do **not** authorize the act in question — and never mentions its own actual governing grant (`G-KOS-CONTRACT-PASS1-RECONCILE`, per its gold record); it does **not** qualify. `GOLD-005` (3 hits) similarly cites `G-KOS-CONTRACT-V3-ARCH`/`-AMD1` only to determine they do **not** cover the work in question (the commit's own purpose); it does **not** qualify either, despite the `.jsonl` recording it as one of the grants "governing" the case at a narrative level. **Only `REF-009` (`G-KOS-TOPO-ARCH`, "linkage `G-KOS-TOPO-ARCH`," "within `G-KOS-TOPO-ARCH` only") genuinely cites its own covering grant** — the cited grant is the lane authorization the registered START transition itself activates, read in full context; the registration act's own authority rests on the quoted PO/ARB START act (seq 3), so "qualifies" is the accurate claim, not "unambiguous." `REF-007` (0 hits) is corrected again: it does not belong to the grant-chain-false-abstention pattern (`FAILURE-MODES.md` §3 lists only `GOLD-004`/`REF-008`/`REF-009` there) — it is simply a `TEMPORAL_MISMATCH`-stratum case with zero message-level grant citation, `AUTHORIZED` only via reviewers independently tracing `G-KOS-ATTRARCH-DESIGN` by content. **This directly disagrees with `2026-09-29-KOS-EVIDENCE-DETERMINATION-FAILURE-MODES.md` §7's H2 row** ("`CitesGrant(x,g) → Authorized(x)` | Weakly `SUPPORTED` ... every case in this round citing a specific grant ID (`REF-007`, `REF-012`)") — scoped there to "this round" (`REF-006`–`REF-013`) only, versus all 13 cases here. That H2 row is itself internally inconsistent with its own document: `REF-007`'s message contains zero grant IDs (confirmed above); `FAILURE-MODES.md` §3 itself says `REF-012`'s grant ID appears only in the added file content, never the message; and H2 omits `REF-009`, the one case in that same round that actually does cite a grant ID in its message. H2 is not applying a message-level citation test at all, despite reading as if it were — the disagreement is stated here rather than silently reconciled, since resolving it would mean revising `FAILURE-MODES.md` §7, out of scope for this document. Corrected status: `EMPIRICALLY_SUPPORTED`, **n=1** (`REF-009` only) — a single case, weaker than every prior stated count (13, then 2), and the true floor given how strict the row's own condition actually is |
| `CommitExists(c,t) ∧ SessionLogMentions(w,t) → ExecutedBy(session,c)` | `UNTESTABLE` |
| `EvidenceA ∧ EvidenceB → Determined(...)`, as one global law | `NOT_SUPPORTED` as stated |

`GOLD-001`'s refutation of the unqualified grant-implication holds **only at the record level**
(`UNAUTHORIZED_BY_RECORD / SUBSTANCE_UNDERDETERMINED`, itself an adjudication by the primary
session over two split reviewers, not a clean independent finding) — stated explicitly rather than
left implicit.

## Determination-model granularity — is a new state justified?

Tested against the required 4 criteria, using `b96374c8f` as the primary test case:

1. **Observed need**: yes, and **broader than previously stated** — `REF-006` (`480207d66`)
   independently produced a `NOT_APPLICABLE` label from a *different* reviewer on a *different*
   case, and the `KOS-SESSION-DISCOVERY-001`/2026-08-14 cluster (5 commits) was never independently
   checked for the same pattern, only assumed similar to the checked 08-15 cluster by mechanism
   identity. **Coverage of this question is a gap, not a settled count of "1 instance."**
2. **Semantic definition**: `NOT_APPLICABLE` = evidence indicates the act falls outside what
   authorization is required for at all.
3. **Discriminating evidence**: not yet available at scale — no deterministic detection rule exists.
4. **Testable decision rule**: not built.

**Verdict unchanged: `HYPOTHESIS`.** Criteria 1–2 are met (1 more strongly than previously stated);
3–4 are not.

## Experiment B — independent replication availability (corrected twice: scope, then re-checked against the completed scoping)

**The prior version of this document overstated unavailability.** The 379/383-commit population is
`git log --grep="KOS-" -i --all` — a citation-filtered slice of **4,554 total commits** (checked
`2026-09-29`, this figure drifts as the population is live — 4,561 on a later re-check the same
day). Three other real, sizeable ticket families exist: **414 commits cite `PBDIGIT-`, 224 cite
`WP-`, 175 cite `EPIC-`** (checked directly; overlapping, not deduplicated).

**This has since actually been checked**, not left as a future task —
`2026-09-29-KOS-REPLICATION-FEASIBILITY-SCOPING.md` (same day, reviewed independently,
`PASS_WITH_LIMITATIONS`) confirms directly: **zero `.claude/runtime/workflow/*.json` ledger files
exist for any of `PBDIGIT`/`WP`/`EPIC`.** `PBDIGIT`/`EPIC` are classified `PARTIALLY_REPLICABLE`
(a real but thin backlog-status mechanism, a session-log corroboration channel that only covers a
2-week window of a much longer commit history); `WP` is a **separate, unverified mechanism**
(`docs/implementation/PROGRAM_STATUS.md`'s prose-embedded `R-nn` rulings, ARB certification
records) classified `INSUFFICIENT_EVIDENCE` — real artifacts exist but were not checked deeply
enough to classify further in that cheap scoping pass.

**Current status, superseding both the original `REPLICATION_UNAVAILABLE` and the intermediate
`REPLICATION_UNATTEMPTED_OUTSIDE_KOS_FAMILY`**: replication within the KOS-citation-filtered
`TEMPORAL_MISMATCH` definition remains unavailable (all 27 baseline cases accounted for by the 6
clusters, 2+1+4+4+10+5=26 changed +1 exception=27). Outside that family, the scoping is now done —
`PBDIGIT`/`EPIC` are `PARTIALLY_REPLICABLE` but not yet attempted; `WP` needs a deeper, dedicated
look before any classification beyond `INSUFFICIENT_EVIDENCE` is possible.

## DDD bounded-context candidates — corrected

**The prior version's asymmetry was wrong.** `Authorization` is not "conflated" relative to
`Evidence`/`Determination` — it is exercised by exactly the same artifacts (the `M`-ladder's own
output vocabulary and all 13 blind-reviewed cases produce authorization-vocabulary labels — not all
literally `AUTHORIZED`: `GOLD-001` is `UNAUTHORIZED_BY_RECORD`, `GOLD-003` is `UNDERDETERMINED`). The real,
defensible distinction is narrower: **most of this session's `Authorization` reasoning was performed
by a single researcher's pipeline (this session), not independently** — true blind, multi-reviewer
independence exists only for the 13 blind-reviewed cases, not for the deterministic `M`-ladder's own
classifications. "Exercised at scale" is in any case a weak criterion for bounded-context boundaries,
which are properly decided by language and ownership, not test frequency. Corrected conclusion:
**no bounded-context decision is supported by current evidence, full stop** — not an asymmetric
"Evidence/Determination ahead, Authorization behind" claim.

## Traceability (corrected — was previously unauditable)

Every claim above is sourced to a specific document and, where applicable, a specific `.jsonl`
record ID, inline. Removed: the prior version's bare "see prior turn" reference (conversation, not
a repository artifact) for the git-collision context — that operational note belongs in the session
log, not in this research document's substantive content, and has been removed here.

---

**Traceability:** `2026-09-29-KOS-GOLD-STANDARD.jsonl` / `-BLIND-REFERENCE-SET-ROUND2.jsonl` (source
of the corrected n≈2 grant-citation count) · `2026-09-29-KOS-SESSION-LOG-EVIDENCE-ABLATION.md` §7
(6-cluster count) · `2026-09-29-KOS-COMMIT-GOVERNANCE-EMPIRICAL-VALIDATION.md` §6 (14/379 UUID
count, corrects this document's own earlier self-contradiction) · `2026-09-29-KOS-EVIDENCE-DETERMINATION-FAILURE-MODES.md`
(`REF-006`'s independent `NOT_APPLICABLE` finding) · `2026-09-29-KOS-REPLICATION-FEASIBILITY-SCOPING.md`
(§Experiment B's replication status) · `INV-ATTR-1`/`INV-ATTR-2` (standing invariant from this
research line's foundational corpus, load-bearing throughout — no single defining document path
identified in this session; cited as an established constraint, not a fresh finding).
