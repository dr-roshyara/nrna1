# Documentation Placement & Link Integrity — Workstream Closure Record

**Date:** 2026-08-01 · **Prepared by:** Recording Architect
**Disposition:** the *folder management and broken links* workstream is **CLOSED**.
**Repository Integrity Gate:** ✅ PASSED. **No standard amended · no ruling minted · nothing moved at closure.**

---

## 1. Disposition

| Item | Decision |
|---|---|
| Documentation roots implementation | ✅ **CLOSED** |
| Migration registry implementation | ✅ **CLOSED** |
| Deterministic link repair | ✅ **CLOSED** |
| Documentation placement mechanism | ✅ **OPERATIONALLY VALIDATED** *(first execution — §3)* |
| PKS evidence recording | ✅ **COMPLETE** |
| **ENG-008** externalize the confidence policy | 🔓 **OPEN** — trigger: **a second consumer** |
| **ENG-009** promote the migration registry | 🔒 **BLOCKED** on **OQ-5** |
| **ENG-010** documentation integrity | 🔓 **OPEN** |
| **ENG-011** repository-wide link validation | 🔓 **OPEN** |
| The six ambiguous references | ⏳ **GOVERNANCE / editorial decision** |

> **The four ENG items and the six references are not unfinished work from this workstream. They are new work it deliberately created** — and the difference matters: **a closed workstream with explicit successors is finished; one with hidden TODOs is not.**

## 2. The closure wording — stated precisely

**On link repair, the accurate claim and not the flattering one:**

> **All deterministic link repairs have been completed. Remaining unresolved references have been classified and transferred to backlog or governance.**

**Explicitly NOT claimed: "all broken links were fixed."** **53 references remain broken** — 6 ambiguous, 47 pointing at documents that were never written. **They are classified, counted, attributed and owned; they are not repaired, and the record says so.**

**Measured outcome:** 121 broken references found → **58 repaired on evidence** → **53 remain, none of them repairable without inventing a document or making a human choice** → `knowledge-lint`: **✅ All documents pass (0 errors, 0 warnings)**.

## 3. What was achieved

| Outcome | Evidence |
|---|---|
| Canonical documentation roots exist | `docs/publicdigit/` · `docs/knowledgeos/` · `docs/pks/`, each with a first artifact (ES-005.2 satisfied, not bypassed) |
| Placement is **derived**, not chosen | `docs/knowledge/schema/documentation-placement.yaml` + `scripts/doc-placement.php`; self-test **9/9** |
| Migration history is **data**, not code | `docs/knowledge/schema/repository-migrations.yaml`, with lifecycle metadata; a future migration is a registry entry |
| Link repair is **evidence-based** | four rules, every destination existence-verified; confidence model with auto-apply at ≥99 |
| Repository-wide scan completed | 121 found where the linter reported 9 — **green lint was never repository-wide assurance** |
| Operational evidence recorded | `docs/pks/2026-08-01-documentation-debt-observation.md` |
| Candidates recorded, **not promoted** | two, both held at explicit bars |
| Governance gaps named, **not invented** | the unruled `cross-product + research` cell surfaced three times and was never filled by guesswork |

## 4. The milestone — and its honest boundary

**One full traversal of the feedback loop, end to end, within one day:**

```
Observation  →  Classification  →  Placement  →  Validation  →  Operational Evidence  →  Backlog
```

**Concretely:** a repo-wide scan produced an **observation** (121 broken references) · the findings were **classified** by evidence and confidence · a new artifact's **placement** was derived by the resolver rather than chosen · the result was **validated** by `knowledge-lint` returning to green · the residue was recorded as **operational evidence** in PKS · and the unresolved parts became **explicit backlog items** with triggers.

> ### The boundary, applied with the same discipline as the placement claim
>
> **This is one traversal, not a demonstrated capability** — the platform's own test is *"one script proves possibility; routine use proves capability."*
>
> **And the loop has not yet closed on the model.** It delivered evidence **to** the decision point and stopped there: **no ruling has issued, no standard has been amended, no candidate promoted.** **The loop has shown that evidence reaches authority. It has not yet shown that evidence changes the model** — that step belongs to the ARB and has not happened.
>
> **Recorded as: first operational validation of the KnowledgeOS feedback loop, up to the decision point.**

## 5. What this workstream deliberately did not do

**Named so the closure is not read as broader than it is:** nothing was migrated (Phase 2 remains gated on whether R-37 binds `docs/`) · `Layer_Verification_Rule.md` was not moved · ES-005 was not amended · **R-65…R-71 were not minted** · OQ-5 was not resolved · the stewardship cell was not filled · no candidate was promoted · the Phase 1 classification map was not produced.

## 6. Successor work, with owners

| Item | State | Unblocks on |
|---|---|---|
| **ENG-008** | open | a second consumer of the confidence model |
| **ENG-009** | blocked | **OQ-5** |
| **ENG-010** | open | a disposition: write · remove · accept as tracked debt |
| **ENG-011** | open | — (it is also the prerequisite for ENG-010's third option) |
| 6 ambiguous references | governance | a human choice; carried by ENG-010 |
| Phase 1 classification map | not started | authorization; **not blocked** |
| Stewardship of `cross-product + research` | open | **now supported by recurrence, not a count** |
| OQ-5 · R-37 scope · minting R-65…R-71 · applying the ES-005 amendment · five principles into the ADR | open | ARB acts |

## 7. Final state

```
knowledge-lint    ✅ All documents pass (0 errors, 0 warnings)
doc-placement     All 9 cases pass · registry and roots consistent
link-check        53 broken: 6 ambiguous · 47 missing · 0 deterministic
```

> **The bounded context has fulfilled its purpose: the architectural model is implemented, operational evidence is gathered, unresolved issues are classified rather than ignored, and future work is isolated into explicit items. Closed.**

---

**Traceability:** the roots ADR (APPROVED) · `2026-08-01-documentation-roots-implementation-report.md` · `2026-08-01-placement-refinement-and-link-integrity-report.md` · `2026-08-01-link-evidence-and-ambiguity-reports.md` · `2026-08-01-link-repair-disposition-and-governance-note.md` · `2026-08-01-classification-placement-operational-validation.md` · `2026-08-01-recurring-unruled-classification-finding.md` · `docs/pks/2026-08-01-documentation-debt-observation.md` · `docs/implementation/backlog/BACKLOG.md` ENG-008/009/010/011. **No standard amended · no ruling minted · nothing moved at closure.**
