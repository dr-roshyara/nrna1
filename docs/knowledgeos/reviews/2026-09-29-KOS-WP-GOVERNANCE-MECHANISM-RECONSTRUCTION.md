# WP Governance Mechanism Reconstruction

**Date:** 2026-09-29. Reconstruction/reconnaissance only — no ablation built, no classifier implemented,
no KOS terminology forced onto WP's own vocabulary. Does not freeze architecture, does not promote a
kernel concept, does not begin EKS/PKS. **Independently reviewed (`kos-theory-reviewer`) — verdict
`FAIL` on the document's central claim** (that WP is an "independently-designed" mechanism from "a
different governance culture," offering convergent cross-mechanism evidence). **That claim is
withdrawn below, as a disclosed correction, not a silent edit** — see §"Independence — corrected"
before reading the comparison and gate sections, which are revised in light of it. The underlying
mechanism reconstruction (§A–J) and the evidence map survive the review largely intact and are not
rewritten.

## Research question

> What is the actual WP governance mechanism, what evidence does it generate, and can that evidence
> be mapped to logical propositions independently of the KOS mechanism?

## Method

Direct reading of the three primary artifacts in full (`docs/implementation/PROGRAM_STATUS.md`, 130
lines; `engineering/architecture/adr/ADR-AIP-LOG-Platform-Rulings.md`, 86 lines, 71 ruling rows
`R-30`–`R-100`, read `R-30` through `R-56`),
plus direct repository checks (commit citation rates, session-log coverage, date ranges).

## A–J · Mechanism reconstruction (WP's own vocabulary, not KOS's)

- **A. Work item**: `WP-nn` (`WP-4`, `WP-4B`, `WP-4C-1`, `WP-4C-2`, `WP-6`, `WP-7`, `WP-8`...) and
  `EPIC-nnn`. Real, named, referenced by ID throughout.
- **B. Governance record**: two-layer, not one. `PROGRAM_STATUS.md`'s "Governance state" table
  (Item / State / Ruling / Next owner) is a **derived summary**; the actual decision text lives in
  `ADR-AIP-LOG-Platform-Rulings.md`'s append-only table (`# | Date | Ruling | Effect`). This is
  structurally closer to a real ledger than PBDIGIT/EPIC's thin backlog-status fields — a table with
  an ID, a date, a decision, and a recorded consequence, for every entry.
- **C. Authority**: named roles, not individuals — `ARB` (Architecture Review Board), `Decision
  Authority`, `Domain Owner`, `Execution Governance`, `Board`. Each ruling's "Effect"/category field
  (e.g. "Delivery Governance · Approval · ARB", "Execution Governance · Authorization · ARB")
  explicitly names which authority acted and in what capacity — a real, structured field WP has that
  KOS's grant JSON does not carry as explicitly.
- **D. Scope**: expressed in the ruling text itself, sometimes with an explicit guard condition
  (`R-47`: "Guard `WP-6 ACCEPTED ∧ WP-7 PLAN APPROVED` satisfied by `R-43 ∧ R-46`... Scope is
  slice-granular: 7A ONLY — 7B and 7C are NOT authorized").
- **E. Temporal validity**: each ruling carries a `Date` column; `PROGRAM_STATUS.md`'s milestone
  burn-up carries dated checkpoints (`M1 ✔ (2026-07-07)`...). More structured than PBDIGIT's loose
  `Created`/`Baseline` fields, less structured than KOS's per-transition timestamp array.
- **F. Execution**: explicitly separated from authorization in the ruling text itself — `R-46`
  approves a *plan*; `R-47` separately authorizes *execution* of one slice of it, gated on the plan's
  approval; `R-48` separately *accepts* the delivered slice. Three distinct, dated, ID'd acts for one
  piece of work — a real, observable authorization→execution→acceptance chain.
- **G. Verification**: CI/mutation-testing evidence (`PHPStan`, `Deptrac`, `Architecture suite N
  green`, mutation score) cited directly inside ruling text as the evidentiary basis for acceptance —
  a structurally different verification channel than KOS's blind human/AI review.
- **H. Adoption/certification**: explicit `ACCEPTED`/`CLOSED`/`RATIFIED` states recorded per item in
  `PROGRAM_STATUS.md`'s governance table, each citing its authorizing ruling ID.
- **I. Provenance (decision→work→implementation→verification chain)**: explicitly diagrammed in
  `PROGRAM_STATUS.md` itself ("What happens after Q1–Q4" — Domain Owner → ARB review → ARB
  authorization → new commission → model → acceptance → ARB authorizes implementation → commission
  opens). Whether this sequential-gate structure is more explicit than anything found in KOS's own
  documents was not tested — disclosed as an untested comparison, not asserted, consistent with how
  Gate E's "better-populated than KOS" claim was withdrawn elsewhere in this document.
- **J. Actor**: role-based (`ARB`, `Decision Authority`...), never an individual. **Same limitation
  as KOS**: no independent attestation mechanism found — these are self-declared/recorded roles,
  same `INV-ATTR-1/2` caveat applies without modification.

## WP evidence map

| WP evidence source | Observable fact | Supported proposition | Unsupported proposition | Temporal info | Independence | Status |
|---|---|---|---|---|---|---|
| Ruling-log entry (`ADR-AIP-LOG-Platform-Rulings.md`) | `#`, `Date`, `Ruling` text, `Effect` | `RulingRecorded(r,date,category)` | `WorkExecuted`, substantive correctness (see `R-43`/`R-53` below) | `Date` column, real | self-declared by the recording process (same `INV-ATTR` caveat as KOS's `humanActRef`) | `VALIDATED` |
| `PROGRAM_STATUS.md` governance table | `State`, `Ruling` ID, `Next owner` | `StateRecorded(item, state)` | `StateCurrentlyTrue` (this is an explicitly-labeled *derived summary*, one hop removed from the ruling itself) | none per-row (dashboard, not a log) | **derived from the ruling log, not independent of it** — explicitly disclosed by the document's own header ("detail: `backlog/BACKLOG.md`... rulings: `ADR-AIP-LOG...`") | `VALIDATED`, explicitly non-independent of the ruling log |
| Commit message citing `R-nn` | citation string | `CitesRuling(c,r)` | `Authorized(c)` (untested — same string-match-≠-semantics caution as KOS's `H2` correction) | commit date, real | independent of the ruling log itself (a separate artifact) | `VALIDATED`, citation rate real: 107 citing commits, denominator disputed on independent recount — 107/224 (47.8%) originally, 107/218 (49.1%) on the reviewer's own recount; the exact commit-selection rule was not stated precisely enough to reconcile, both counts disclosed rather than picking one |
| Session log | narrative prose, `R-nn`/`WP-nn` mentions | `WorkContextRecorded` | `ActorExecuted` | dated file — **corrected**: 10 of 11 `WP-`-mentioning session logs fall in a dense 2026-07-26–2026-08-05 window (11 days), matching 217 of 218 real WP commits in the same window; the 11th file (2026-09-28) and the 1 remaining commit are a *later KOS-research session studying WP*, not WP activity itself — the original "through 2026-09-28" framing conflated this contamination with genuine coverage | same `INV-ATTR` caveat, one level up, as KOS | `VALIDATED` for the dense 11-day window; **not** materially stronger than PBDIGIT once the KOS-era outlier is excluded — both are dense-but-narrow |
| CI/mutation evidence cited in ruling text | test counts, gate pass/fail, mutation score | `AutomatedVerificationRecorded` | `Authorized` (verification ≠ authorization — a real, distinct evidentiary layer WP has that KOS's blind-review verification doesn't) | tied to a specific commit/gate run, when cited | **corrected**: the underlying machine-run numbers may be generated independently, but their *citation* in a ruling reaches the record only through the same author's prose — not independent of the authorship finding, and `R-43`/`R-53` is itself a case where cited gate evidence could not be substantiated | `HYPOTHESIS` (found, not yet tested at scale) |

## Record vs. substance test — real, self-disclosed, but same-author (corrected framing)

**`R-43`** (2026-08-01) accepted `WP-6` based on a claimed evidentiary line: "all gates pass." **`R-50`**
later authorized a read-only historical reproduction of that same gate at the recorded closure
commit. **`R-53`** — read in full this round, corrected from the original version's partial
attribution — is its own ruling row, *"GOVERNANCE NOTE ATTACHED TO R-43 ... THIS IS AN ANNOTATION,
NOT AN AMENDMENT; R-43's decision text is unchanged and the WP-6 acceptance STANDS,"* and states,
verbatim: *"Whether the acceptance evidence was **FALSE** (the gate was run and misreported) or
**UNSUPPORTED** (the gate was not run) remains **UNDETERMINED from repository evidence** and is not
resolved by this note."* **The acceptance itself stands, unrevoked, while its own evidentiary basis
is formally undetermined.** (The original version of this document quoted a near-identical sentence
embedded as a forward-pointer summary inside `R-43`'s own row, without citing `R-53`'s own full,
correctly-worded entry directly — fixed here.)

**Corrected significance**: this is real, self-disclosed evidence of a record/substance gap — but it
is **not independent corroboration of `GOLD-001`**, since both come from artifacts built by the same
person (see below). Its value is narrower: it shows the *same author's* record/substance and
disclose-don't-silently-fix discipline manifesting a second time, in a structurally different
artifact format (a shared prose ruling log vs. per-work-item JSON), which is a real but weaker claim
than "independently converging." **A second, related finding from the same read**: `R-52`
self-discloses an unresolved internal classification inconsistency ("SAME CLASSIFICATION QUESTION AS
R-60, FLAGGED NOT CORRECTED... the inconsistency is recorded rather than silently normalised") —
further, real evidence of the same disclose-don't-silently-fix habit, not a new kind of finding.
**Status: `OBSERVED`, same-author, not independent.**

## Authorization test

Checked directly against `R-43`→`R-50`→`R-53`: `RulingRecorded(r) → Authorized` does not hold as a
single implication — WP itself distinguishes `PLAN APPROVED` (`R-46`), `EXECUTION AUTHORIZED` (`R-47`,
separately gated), and `ACCEPTED` (`R-48`) as three distinct, sequentially-gated propositions, none
implying the others automatically. **Corrected**: this is the same shape of finding KOS produced, but
**not independently arrived at** (§Independence) — it is at least partly a *design choice* WP's
mechanism was built with (the `PLAN`/`EXECUTION`/`ACCEPTANCE` staging is explicit vocabulary in the
ruling text itself, not solely a discovered empirical pattern the way KOS's `M`-ladder disagreements
were). The reviewer also notes the staging was applied *inconsistently* in the rulings read (`R-62`
re-types `R-43` from `Approval` to `Acceptance`, and `R-50` from `Delivery` to `Execution`; `R-55`
notes `R-48` and `R-55` carry different type labels for comparable acts) — real counter-evidence
against treating the staging as a clean, settled proposition, disclosed here rather than omitted.

## Session-log test (corrected)

**Corrected count**: 41, not 44, session-log files match an `R-nn`-shaped pattern (independently
re-counted by review; some may be other numbering schemes, not re-verified). 11 mention `WP-`
directly. **Corrected coverage claim**: 10 of those 11 fall inside a dense 2026-07-26–2026-08-05
window; the 11th (2026-09-28) is a later KOS-research session log *about* WP, not a WP-era log.
Real WP commits show the identical shape: 217 of 218 fall in the same 11-day window, with 1 outlier
(the same 2026-09-28 KOS-research commit). **Session-log corroboration for WP is dense within an
11-day window — not "near-complete coverage of the full commit range" as originally claimed, and not
demonstrated to be materially stronger than PBDIGIT/EPIC's comparably narrow window.** Not yet tested
for circularity (does the session-log entry independently narrate the ruling, or copy the ruling text
verbatim?) — still not performed, still flagged as the natural next check.

## Independence — corrected, the most serious finding of this review round

**The original version of this document never tested the premise its whole comparison rested on: is
WP genuinely an independently-designed mechanism from "a different governance culture"? It is not.**
Checked directly: `git log --format=%an -- engineering/architecture/adr/ADR-AIP-LOG-Platform-Rulings.md`
returns a single author, the same person behind this entire KOS research line. Worse: a **prior KOS
research commit already studied `WP-4B` directly** (`958b6f6df`, *"execution-before-authorization
result on WP-4B (F-LOG-0128)"*, 2026-09-28) — meaning this "reconstruction" was not a blind test of
an unfamiliar mechanism; the researcher's own prior work had already touched this exact topic. **WP
is, at best, one author's earlier governance work in the same repository, separated from KOS
chronologically, not by an independent design process.** The shared "annotate, never silently
rewrite" discipline both mechanisms exhibit (`ES-004.3`, adopted repository-wide at `R-41`,
2026-07-30, and bound in `CLAUDE.md` for all future work) is **inheritance from one common,
explicit, repo-wide standard — not independent convergence on the same idea.** This single finding
invalidates the document's central interpretive claim, even though the underlying mechanism facts
(§A–J, the evidence map, the `R-43`/`R-53` case) remain real and accurately described once corrected
above.

`PROGRAM_STATUS.md` is separately confirmed **not independent** of the ruling log — it self-declares
as a derived dashboard, and this specific sub-finding was correct in the original version. The
ruling log and session logs remain structurally separate *artifacts* (different files, different
update triggers) even though not independent *sources* in the authorship sense. CI/mutation evidence,
when cited inside a ruling, remains the one evidence channel genuinely absent from KOS — a real,
machine-generated (not human-narrated) source at the point of measurement — but its *citation*
inside a ruling passes through the same author's prose, so it is **not unaffected by the
authorship finding**; corrected to match the evidence-map row above.

## Temporal model

`RulingDate` (the `Date` column) is real and consistently present, unlike KOS where grant dates are
often only embedded in prose. `EffectiveDate`/`ImplementationDate`/`VerificationDate` are not
uniformly distinguished — `R-43`'s own later correction (`R-53`) shows the *acceptance date* and the
*verification-truth date* can diverge in ways the ledger itself only discovers after the fact, and
records honestly rather than silently reconciles.

## Determination reconstruction — candidate states, tested against real WP evidence, not assumed

`ACCEPTED`, `CLOSED · ARCHIVED`, `DEFERRED`, `AWAITING BUSINESS DECISION`, `PLAN authorized ·
implement NOT`, `UNAUTHORIZED`, `HELD`, `RE-ANCHORED` — **these are WP's own real, already-used
vocabulary** (`PROGRAM_STATUS.md`'s governance table), not KOS labels forced onto WP. Directly
comparable in *function*, not name, to KOS's `AUTHORIZED`/`NOT_APPLICABLE`/`UNAUTHORIZED_BY_RECORD`:
`PLAN authorized · implement NOT` functions like KOS's `NOT_APPLICABLE`-for-implementation-while-
authorized-for-planning; `UNAUTHORIZED — wire no production caller ahead of it` (the `D-1..D-4`
transaction-boundary item, held under `R-91`) functions exactly like `GOLD-001`'s standing
prohibition.

## Cross-mechanism comparison

| Logical function | KOS | WP |
|---|---|---|
| Work identification | `KOS-<name>-nnn`, JSON file per work item | `WP-nn`/`EPIC-nnn`, referenced in a shared ledger |
| Governance decision | grant object (`status`, `scope`, `humanActRef`) | ruling row (`#`, `Date`, `Ruling`, `Effect`) |
| Scope | prose `scope` field per grant | prose within the ruling, sometimes an explicit guard formula |
| Temporal validity | transition timestamps (per work item file) | `Date` column (per ruling, shared log) |
| Execution evidence | commit diff + citation | commit diff + citation (107 citing commits; 47.8%/49.1% depending on denominator, both disclosed above) |
| Verification | blind human/AI review (this research line) | CI/mutation gates, cited inside ruling text |
| Attribution | self-declared session/actor, `INV-ATTR` caveat | self-declared role, same caveat |
| Certification | `AUTHORIZED` grant status | `ACCEPTED`/`CLOSED`/`RATIFIED` ruling effect |
| Corroboration | session log (73%/27.4% session/role field population) | session log (dense within an 11-day window, 2026-07-26–2026-08-05; not near-complete coverage — see corrected session-log test) |

**Corrected**: a common abstract proposition is still visible — *a dated, role-attributed decision
record precedes and scopes a governed act, is logically distinct from that act's execution and from
its later verification, and the mechanism itself can — and in real, found cases does — disclose that
its own evidentiary basis for an acceptance later became undetermined without revoking the
acceptance* — but per the independence finding above, **this is not evidence from two
independently-designed governance cultures.** It is the same author's habits appearing in two
structurally different artifact formats within one repository. That is a real, useful, but
*materially weaker* form of evidence than cross-cultural convergence: it rules out "this pattern is
an artifact of one specific file format (JSON grants)," since the same author reproduces it in prose
tables too — but it does **not** rule out "this pattern is an artifact of one specific author's
governance instincts," which remains untested. **Reframed status: `HYPOTHESIS`, weakly strengthened
in scope (format-independent within one author), not strengthened in generality (author-independent
remains untested).**

## Replication gate (corrected — an "independence" precondition is now added ahead of the six gates)

**Precondition, newly failed**: is WP an independent mechanism at all, in the sense needed for
replication to mean anything beyond "the same author's second artifact"? **NO** — same author, prior
KOS contamination of the very topic studied (§Independence above). Any future blind review (Gate D)
must disclose this to reviewers rather than presenting WP as a fresh, unfamiliar mechanism.

- **Gate A (comparable evidence)**: **PARTIAL, corrected from YES** — "structurally close to KOS
  grants" was asserted, not tested; the ruling log bundles decision, evidence, effect, and later
  annotations into a single free-text cell per row, which is weaker for machine-readable propositions
  than KOS's typed JSON fields.
- **Gate B (comparable proposition)**: **PARTIAL, corrected from YES** — the record/substance and
  multi-stage-authorization findings do replicate, but as one author's design/habit, not as
  independent empirical convergence (§Independence, §Cross-mechanism comparison).
- **Gate C (sufficient population)**: **PARTIAL, corrected from YES** — 27 of 71 total ruling rows
  (`R-30`–`R-56`) were read, not "~50"; 44 rows (`R-57`–`R-100`) remain unread. Population
  sufficiency is unknown, not established.
- **Gate D (independent evidence)**: **NOT MET** — no blind review has been run on any WP case, and
  the independence precondition above makes this gate more important than originally framed, not
  less.
- **Gate E (temporal information)**: YES — real `Date` fields are present; "better-populated than
  KOS's" was not actually demonstrated and is withdrawn as an unsupported comparison.
- **Gate F (attribution)**: PARTIAL — unchanged, same limitation as KOS.

**Corrected verdict: `INSUFFICIENT_EVIDENCE`**, downgraded from `REPLICATION_POSSIBLE_WITH_NEW_RULE`.
The original verdict didn't follow from its own gate scores even before the independence finding
(Gate C was miscounted; Gate B rested on n=1 evidence read as more than it was). Real, valuable
reconnaissance exists — the mechanism appears richer than PBDIGIT/EPIC's, though that comparison
itself was not rigorously tested — but "possible" is not
yet earned.

## Architectural implication

**Corrected**: none drawn, and the basis for drawing one is weaker than originally claimed. The
record/substance and multi-stage-authorization findings are `HYPOTHESIS`, same-author-replicated
across two artifact formats — not `SUPPORTED` by independent mechanisms. No kernel concept promoted;
no EKS/PKS boundary decision follows from this document, now more clearly than before.

## Next smallest decisive experiment (unchanged in kind, now more clearly motivated)

**Gate D remains the right next step, and is now more clearly necessary, not less**: select 3–5 real
WP rulings (stratified across categories) and run the same blind double-review protocol already
validated for KOS, adapted to WP's own vocabulary. **Critically, per the independence finding**: any
reviewer dispatched for this must be told plainly that WP and KOS share an author and a repo-wide
lifecycle standard (`ES-004.3`) — presenting WP as an unfamiliar, independently-designed mechanism
to a reviewer would repeat this exact document's own error one level down. This would be the first
genuine blind verification of *any* WP case in this research line — not a "cross-mechanism"
verification in the strong sense originally claimed, but a real test of whether the record/substance
pattern holds up under independent scrutiny even within one author's own governance habits.

---

**Traceability:** `docs/implementation/PROGRAM_STATUS.md` · `engineering/architecture/adr/ADR-AIP-LOG-Platform-Rulings.md`
(read `R-30`–`R-56`, 27 of 71 total ruling rows; `R-57`–`R-100` not read) · `2026-09-29-KOS-REPLICATION-FEASIBILITY-SCOPING.md`
(prior, shallower scoping this document supersedes for WP specifically — that document's
`PARTIALLY_REPLICABLE` verdict for PBDIGIT/EPIC is unaffected) · `2026-09-29-KOS-EVIDENCE-PROPOSITION-MATRIX.md`
(source of the `H2` string-match-≠-semantics discipline applied throughout) · INV-ATTR-1/INV-ATTR-2.
