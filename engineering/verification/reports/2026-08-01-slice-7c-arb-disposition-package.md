# Slice 7C — ARB Disposition Package

**Date:** 2026-08-01 · **Prepared by:** Recording Architect
**Purpose:** allow the ARB to dispose of the verification findings before authorization.
**Source:** `2026-08-01-slice-7c-preauthorization-verification.md` (Revision 2, architecturally complete).
**Repository Integrity Gate:** ✅ PASSED. **No implementation · no tests · no rulings · no identifiers assigned · no architecture changed.**

> **Note on what was removed.** The source report carried recommendations — a preferred identifier allocation and a preferred authorization wording. **Those are governance, not architecture, and they are withheld here.** Section C presents options and their effects without preference. **Where an option is foreclosed, it is foreclosed by an existing approval, not by my judgement, and the report says which.**

---

## Section A — Verified Facts

*Established by verification. No conclusions, no recommendations.*

| # | Fact | How established |
|---|---|---|
| A-1 | `composer merge-gate` passes — Architecture fitness, Deptrac, greenfield PHPStan, widened regression | executed 2026-08-01 |
| A-2 | 266 tests, 665 assertions, 0 failures; 101 pre-existing *risky* notices | same run |
| A-3 | Working tree clean; branch `feature/pb003` | `git status` |
| A-4 | Slice 7B accepted and closed under R-59; its output is in the accepted implementation baseline | rulings record |
| A-5 | The realizing surface consists of `EvidencePreservationWindow::forElection/closesAt/isOpenAt` and `ResolvesEvidencePreservationWindow::isOpenFor(Election, DateTimeImmutable): bool` | source |
| A-6 | `ResolvesEvidencePreservationWindow::isOpenFor` accepts `App\Models\Election` | source |
| A-7 | `app/Console/Commands/AuditCleanup.php` is 60 lines and contains no election concept: it lists directories under `storage/logs/audit`, compares `lastModified` to a `--days` cutoff, and deletes | source |
| A-8 | The WP-7 plan allocates *"the guard inside `AuditCleanup`; folder→election resolution"* to Infrastructure, and records that the parser, traversal, deletion and CLI *"remain ungated … because they carry no policy"* | plan §3 and placement principle |
| A-9 | `tests/Feature/Audit/AuditCleanupTest.php` exists and passes today | test run |
| A-10 | Those tests create directories with `File::makeDirectory` and no `Election` records; folder names correspond to no election row | source |
| A-11 | 7C's approved acceptance includes *"an unresolvable folder→election mapping ⇒ **not deleted**"* | plan §5 |
| A-12 | 7C's approved test list includes *"**`--days` no longer overrides the invariant**"* | plan §5 |
| A-13 | The plan records 7C as *"the **first externally visible behaviour change** — announcement owner required before release"* | plan §5 |
| A-14 | Every term in 7C's slice definition already appears in the approved plan; the plan records *"Published language — untouched"* | plan §1, §5 |
| A-15 | WP-7B-R1 (opened under R-60) extracts the INTERIM anchor into a replaceable resolver **with behaviour unchanged** | plan status, R-60 |
| A-16 | Identifiers R-61 through R-64 were issued on 2026-08-01 for the governance-model validation disposition | rulings record |
| A-17 | Seven further conclusions exist as drafts, approved in substance and not minted | session record |

## Section B — Architectural Conclusions

*Conclusions only. No governance.*

| # | Conclusion | Rests on |
|---|---|---|
| B-1 | **Slice 7C introduces no new business concepts and no new ubiquitous language** | A-14 |
| B-2 | **Slice 7C introduces no new bounded-context responsibilities** — WP-7 owns the deletion guard and nothing else | A-14, plan §1 |
| B-3 | **Slice 7C completes the bounded context rather than extending it** — all three slices realize one capability specified in full before any began | B-1, B-2 |
| B-4 | **The architectural dependency is the *Evidence Preservation Window Resolution* capability**, realized today by `ResolvesEvidencePreservationWindow` | A-5 |
| B-5 | **That capability is accepted and its query surface is stable** | A-4, A-5 |
| B-6 | **Slice 7C requires no new port** — folder→election resolution is Infrastructure-side by approved allocation, and the realizing service already accepts the Eloquent model | A-6, A-8 |
| B-7 | **Slice 7C consumes exactly one business capability**; everything else it touches pre-exists and carries no policy | A-7, A-8 |
| B-8 | **Slice 7C and WP-7B-R1 are architecturally independent** — disjoint by file, and 7C depends on a query surface that R-1 does not change | A-15, A-5 |
| B-9 | **Architecture does not block authorization of Slice 7C** | B-1 … B-8 |

## Section C — Governance Decisions Required

*Three. For each: the finding, why the ARB must decide, the options, and the effect of each.*

### C-1 · F-7C-1 — the scope of "shall not alter deletion mechanics"

**Finding.** The proposed authorization wording forbids altering *deletion mechanics*. **7C's approved acceptance (A-11) and approved test list (A-12) both require the command to delete different things than it does today.**

**Why the ARB must decide.** **The wording appears in the authorization instrument, which is the ARB's.** Architecture can say what the words would permit or forbid; **it cannot choose the words.**

| Option | Effect |
|---|---|
| **(a) Issue as proposed** | **Forecloses 7C's own approved acceptance criterion and test list. The slice could not satisfy both its authorization and its acceptance** — this is a conflict with an existing ARB approval, not a matter of preference |
| **(b) Constrain *how deletion is performed*** — traversal, filesystem removal, reporting — and state that the guard gates the deletion **decision** | Authorization and the approved acceptance are consistent; the mechanics remain frozen |
| **(c) Some other wording** | ARB's to formulate; **architecture's only requirement is that the wording not contradict A-11 and A-12** |

**Architecture's sole constraint: whatever wording is chosen must leave the approved acceptance criterion achievable.** Within that, the choice is open.

### C-2 · F-7C-2 — release governance

**Finding.** 7C is the first externally visible behaviour change and the plan requires an announcement owner before release (A-13). **The proposed authorization covers implementation and does not address release.**

**Why the ARB must decide.** **Naming a release owner and separating implementation authorization from release authorization are governance acts.** Architecture observes only that **release governance is required and currently unaddressed.**

| Option | Effect |
|---|---|
| **(a) Authorization silent on release** | Implementation proceeds; **the release precondition remains unrecorded at the point where it will be needed** |
| **(b) Authorization states that it covers implementation and acceptance only** | The precondition is visible at authorization; the owner is named later |
| **(c) Authorization names the announcement owner now** | Precondition satisfied at authorization |

**Architecture names no owner and expresses no preference.**

### C-3 · F-7C-3 — whether authorization covers amending existing passing tests

**Finding.** Under the approved acceptance (A-11), the folders created by the existing tests (A-10) become unresolvable and must be retained, while those tests assert deletion (A-9). **7C's RED therefore includes modifying tests that currently pass.**

**Why the ARB must decide.** **Modifying existing green tests changes the recorded evidence of previously accepted behaviour.** Adding failing tests is ordinary RED; **rewriting passing ones is a change to the acceptance record, and whether an authorization silently covers that is a governance question, not an engineering one.**

| Option | Effect |
|---|---|
| **(a) Authorization silent** | Engineering amends the tests under general RED discretion; **the change to accepted behavioural evidence is not visible in the authorization** |
| **(b) Authorization explicitly covers the amendment, limited to what the criterion requires** | The change is visible and bounded at the point of authorization |
| **(c) Authorization requires the amendment as a separately reviewed step** | Highest visibility; adds a review cycle inside the slice |

**Architecture's observation only: the amendment is unavoidable given A-10 and A-11. What is open is whether it is authorized explicitly, implicitly, or separately.**

### C-4 · Identifier collision

**Finding.** **R-61 through R-64 are already issued (A-16).** Seven further conclusions exist as unminted drafts (A-17).

**Why the ARB must decide.** **Identifier allocation is a property of the rulings register, which is the ARB's instrument.**

| Option | Effect |
|---|---|
| **(a) 7C takes the next free identifiers; drafts renumber on issuance** | 7C's numbering does not depend on an unrelated governance batch |
| **(b) Mint the drafts first; 7C takes identifiers after them** | Draft numbering is preserved; 7C's identifiers depend on that batch being issued |
| **(c) Reserve a range for the drafts and allocate 7C outside it** | Both preserved; the register carries reserved-but-unissued identifiers |

> **Architecture requires exactly one thing: no identifier may be reused. Reusing R-61 would place two rulings under one identifier and break the register's integrity.** **Which allocation achieves uniqueness is entirely the ARB's.**

## Section D — Engineering Impact

*What engineering does after governance disposes. No implementation here.*

| # | Action | Depends on |
|---|---|---|
| D-1 | Apply the authorization wording as issued | **C-1** |
| D-2 | Begin RED for the deletion guard: open window ⇒ retained · closed window ⇒ deleted · unmappable folder ⇒ retained · `--days` no longer overrides the invariant | authorization |
| D-3 | Amend `tests/Feature/Audit/AuditCleanupTest.php` so its fixtures match the approved criterion, **limited to what the criterion requires** | **C-3** |
| D-4 | Implement the guard inside `AuditCleanup`, consuming only the Evidence Preservation Window Resolution capability | B-4, B-6, B-7 |
| D-5 | Preserve behavioural boundaries: folder traversal, filesystem removal and reporting unchanged; the guard gates the **decision** | **C-1** |
| D-6 | Introduce no port, no new domain term, no bounded-context change | B-1, B-2, B-6 |
| D-7 | Do not absorb, implement, or anticipate WP-7B-R1 | B-8 |
| D-8 | Run `composer merge-gate` and present acceptance evidence | A-1 |
| D-9 | Observe the release precondition as disposed | **C-2** |

**D-3 is the only action that alters previously accepted evidence, and it is gated on C-3.**

## Section E — Readiness Statement

> ### Is Slice 7C architecturally ready for authorization?
>
> **Yes. Architecture no longer blocks authorization of Slice 7C.**
>
> **Evidence:** the consumed capability is accepted and its query surface stable (A-4, A-5, B-5) · no new port is required, by approved allocation (A-6, A-8, B-6) · exactly one capability is consumed (B-7) · no new business concept, ubiquitous language, or bounded-context responsibility is introduced (A-14, B-1, B-2) · 7C and WP-7B-R1 are independent (A-15, B-8) · the operational baseline is green (A-1, A-2, A-3).
>
> **No architectural blocker exists.** **The three findings in Section C are governance dispositions, not architectural defects** — none of them changes any conclusion in Section B.

---

**Traceability:** `2026-08-01-slice-7c-preauthorization-verification.md` (Rev 2) · `.claude/plans/WP-7-retention-alignment.md` §1 · §3 · §5 · placement principle · **R-59** · **R-60** · **R-61…R-64** · `app/Console/Commands/AuditCleanup.php` · `tests/Feature/Audit/AuditCleanupTest.php` · `app/Contexts/Election/Application/Service/ResolvesEvidencePreservationWindow.php` · `composer merge-gate` (PASS). **No implementation · no tests · no rulings · no identifiers assigned · no findings renamed · no architecture changed. Slice 7C remains unauthorized.**
