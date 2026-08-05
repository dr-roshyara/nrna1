# Slice 7C — Authorization

**Role:** authorization **instrument** — supports Board deliberation. **Expected identifier on issuance, subject to ARB: R-65** (acceptance R-66).
**Status:** ✅ **ISSUED as R-65 (ARB, 2026-08-01).** The ruling is recorded in `engineering/architecture/adr/ADR-AIP-LOG-Platform-Rulings.md`; this instrument is its supporting record.
**Prepared by:** Recording Architect, on ARB guidance.

> **What this document is, and is not.** It is a **preparing instrument for ARB use — not an ARB ruling.** **The ruling exists only when the Decision Authority exercises authority**, and its text is then recorded in the rulings register (`engineering/architecture/adr/ADR-AIP-LOG-Platform-Rulings.md`) as **R-65**.
>
> **Placement verified against precedent, not asserted:** Slice 7B's authorization package and acceptance record sit in `engineering/verification/reports/`, while its ruling **R-59** sits in the register. **The register holds rulings; verification reports hold the instruments that prepare and record them.** This document follows that split.

> **Note on provenance:** no prior single authorization document existed in the repository. This instrument consolidates the governance decisions from `2026-08-01-slice-7c-arb-disposition-package.md` and the ready-to-issue text held in `.claude/plans/WP-7C-engineering-readiness.md`, with the five ARB refinements applied. **Nothing was reopened; content was moved, not re-derived.**

---

## Section 1 — Verified Facts

*Established by verification. No conclusions, no authority.*

| # | Fact |
|---|---|
| 1 | **The consumed business capability is *Evidence Preservation Window Resolution*, currently realized by** `ResolvesEvidencePreservationWindow::isOpenFor(Election, DateTimeImmutable): bool` |
| 2 | Slice 7B is accepted and closed (**R-59**); its output is in the accepted implementation baseline |
| 3 | `app/Console/Commands/AuditCleanup.php` is 60 lines and contains no election concept: it lists directories under `storage/logs/audit`, compares `lastModified` to a `--days` cutoff, and deletes |
| 4 | The approved plan allocates the guard and folder→election resolution to **Infrastructure**, and records that the parser, traversal, deletion and CLI **carry no policy** |
| 5 | `tests/Feature/Audit/AuditCleanupTest.php` passes today; its fixtures create directories with **no `Election` records** |
| 6 | The approved acceptance requires that **an unresolvable folder→election mapping is not deleted**, and the approved test list requires that **`--days` no longer overrides the invariant** |
| 7 | Three of its six methods therefore assert deletion of an unmappable folder and **will fail** under the approved criterion; three are expected to pass |
| 8 | `composer merge-gate` passes — 266 tests · 665 assertions · 0 failures; working tree clean |
| 9 | Every term in 7C's slice definition already appears in the approved plan; **Published Language is untouched** |
| 10 | **WP-7B-R1** (opened under **R-60**) alters anchor resolution internals with behaviour unchanged; it shares no file with 7C |

## Section 2 — Architectural Constraints

*Recorded once, here. Referenced elsewhere; never duplicated.*

| # | Constraint |
|---|---|
| **AC-1** | **How deletion is performed is unchanged** — folder traversal, filesystem removal and reporting. **The guard gates the deletion *decision*.** |
| **AC-2** | **No duration is defined, defaulted or clamped** — fail closed (**AP-1**) |
| **AC-3** | **MAD has exactly one home**; it is not copied into a retention configuration (**AP-2**) |
| **AC-4** | **No context crossing is introduced** — no event added, changed or retired |
| **AC-5** | **Artifact A only** — the Election Event Journal (`logs/audit/`) |
| **AC-6** | The value object takes **business values only** — never a port, config, model or clock; the instant is an argument |
| **AC-7** | The absent-anchor fallback remains in the **application service** |
| **AC-8** | **No new port, aggregate, entity, repository or domain term** |
| **AC-9** | **Slice 7C shall not absorb, implement or anticipate WP-7B-R1** |

## Section 3 — Authorization (adopted as R-65)

*Adopted by the Board as **R-65**. **C-1 resolved: the literal clause (option a). C-3 resolved: explicit. C-4 resolved: R-65.***

> Slice 7C is authorized to implement the decision service that determines whether evidence may be deleted, consuming only the **Evidence Preservation Window Resolution** capability.
>
> Engineering shall implement Slice 7C subject to the **Architectural Constraints** recorded in this instrument.
>
> Slice 7C **shall not alter how deletion is performed** (folder traversal, filesystem removal, reporting). **The guard gates the deletion decision.**
>
> Slice 7C shall not absorb, implement, or anticipate **WP-7B-R1** (independent under R-60).
>
> Authorization covers implementation and acceptance only. **Release requires a named announcement owner** — not on the engineering critical path.
>
> RED includes amending `tests/Feature/Audit/AuditCleanupTest.php` — **limited to what the acceptance criterion requires**.
>
> **Sequence: RED → GREEN → VERIFY → ACCEPT. WP-7 closes on acceptance.**

**Transcribed into the rulings register as R-65 on issuance.**

## Section 4 — Engineering Handover

### Authorized scope

| Action | Detail |
|---|---|
| **4 new tests** | open window ⇒ retained · closed window ⇒ deleted · unmappable folder ⇒ retained · `--days` no longer overrides the invariant |
| **Amend existing test** | `AuditCleanupTest.php` — three fixtures, per **D-2** |
| **Implement the guard** | `app/Console/Commands/AuditCleanup.php` **only** |

### Architectural boundaries

| Architectural Boundary | Steward |
|---|---|
| Deletion decision | **Audit / Retention** |
| EPW Resolution capability | **Election** |
| MAD parameter | **Adjudication** |
| Published Language | **Election** |
| Tactical model | **Election** |

### Stop and refer

**If engineering discovers it needs a new bounded context, aggregate, repository, port, domain term, context crossing, or an architectural policy change — it stops and refers.** **The slice was verified to need none of them.**

## Section 5 — Scope of Authority

> **This authorization exercises Execution Governance only.**
>
> **It does not:**
> - alter architecture;
> - amend strategic decisions;
> - modify bounded-context ownership;
> - redefine capabilities;
> - approve future work packages.
>
> **Those remain subject to their respective governance processes.**

---

**Traceability:** **R-59** (7B accepted) · **R-60** (WP-7B-R1) · **R-34** (issuance) · **AP-1 · AP-2** · **Policy 2** (`EPIC-003 §THE FOUR DECISIONS` №2) · `.claude/plans/WP-7-retention-alignment.md` §1 · §3 · §5 · `.claude/plans/WP-7C-engineering-readiness.md` · `2026-08-01-slice-7c-arb-disposition-package.md` · `2026-08-01-slice-7c-preauthorization-verification.md` Rev 2. **Unsigned · not issued · no architecture reopened · no readiness re-analysed.**
