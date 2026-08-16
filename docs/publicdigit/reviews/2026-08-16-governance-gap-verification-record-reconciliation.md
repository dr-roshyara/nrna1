# Governance Reconciliation — two records for one governance-gap verification

**Task:** read-only reconciliation of `KOS-GOV-GAPS-VERIFY-001` and `KOS-GOV-GAPS-001`.
**Nothing was merged, deleted, rewritten, closed or state-changed.** Both histories stand intact.
**Governance act only.** No Verification, Implementation or Architecture work performed.

---

## 0 · The finding that governs everything else

**One human START act is recorded in both records.** The PO/ARB issued a single act — *"START S1-verification-governance-gaps — 2026-08-16"* — and it now appears as `seq 3` in **two** authoritative records, activating **two** verification lanes.

| | `KOS-GOV-GAPS-VERIFY-001` | `KOS-GOV-GAPS-001` |
|---|---|---|
| `seq 3` `humanAct` | `START S1-verification-governance-gaps — 2026-08-16` | *"please record : START S1-verification-governance-gaps - 2026-08-16"* + name-resolution annotation |
| Lane | `S1-verification-governance-gaps` | `S1-verification-gov-gaps` |
| State | **ACTIVE**, mutation owner | **ACTIVE**, mutation owner |

The duplication is therefore not confined to the commission layer — **it has propagated into the human-act layer**, which is the one layer the whole model treats as authoritative.

---

## 1 · Provenance chain, side by side

| Stage | `KOS-GOV-GAPS-VERIFY-001` | `KOS-GOV-GAPS-001` |
|---|---|---|
| **1 · Human authorization** | *"Authorize independent verification of the six governance-model weaknesses."* Referent located and confirmed separately. | *"Authorize an independent **read-only** verification of the six governance-model weaknesses **G-1 through G-6**."* Quoted as delivered, with the preceding ARB verdict. |
| **2 · Commission** | `…-KOS-GOV-GAPS-VERIFY-001-commission.md`, committed **16:30** (`b52b31e4`) | `…-KOS-GOV-GAPS-001-commission.md`, committed **16:43** (`754a79ab`) |
| **3 · Grant / scope** | `G-KOS-GOVGAPS-VERIFY` — all six gaps; excludes repair, remediation, closure, self-certification | `G-KOS-GOVGAPS-VERIFY` — **identical ID**; all six gaps; read-only stated explicitly |
| **4 · Assignment** | `S1-verification-governance-gaps` (role `verification`) | `S1-verification-gov-gaps` (role `verification`) |
| **5 · Handoff** | bootstrap, tokenRef → its own commission | bootstrap, tokenRef → its own commission |
| **6 · Human START** | **names this lane exactly** | **names the other lane**; recorded here by reinterpretation |
| **7 · Current state** | ACTIVE, owner | ACTIVE, owner |
| Role set | `governance, architecture, implementation, verification` | `governance, verification` (narrowed) |
| Workflow label | `verification` | `governance-verification` |
| Record created | before 16:30 | **16:49** |

---

## 2 · Classification

**A — one duplicated work item.**

Not B: they are not distinct work. Both cite the same source (§C of the topology report), the same six gaps, the same purpose, and the same authorization event. Their grants carry the **same identifier**.

Not C: neither record is invalid in content. Both were created in good faith by a Governance actor; each is internally consistent and each faithfully registers a real human authorization. **The defect is duplication, not invalidity.**

---

## 3 · Which record is authoritative — `KOS-GOV-GAPS-VERIFY-001`

**Decided on provenance, not on merit or convenience.**

1. **The human act identifies it.** The PO/ARB START names `S1-verification-governance-gaps`. That assignment exists **only** in this record. The human then reinforced it — *"Record my already-issued … START for S1-verification-governance-gaps exactly as stated. Do not manufacture or reinterpret the act."* **The human named the lane twice; a record cannot be more directly identified than by the act it exists to carry.**
2. **It is first in time.** Its commission was committed at 16:30; the other record did not exist until 16:49.
3. **The PO/ARB has been operating against it.** The instruction excluding `G-2`/`G-4` — *"the current process previously produced measurements that materially underpin those claims"* — matches the `executionContext` recorded in **this** record, not the other.

**The competing merits of `KOS-GOV-GAPS-001` are acknowledged and explicitly set aside:** its grant quotes the authorization more completely (*read-only*, *G-1 through G-6*), and its narrowed role set makes "no repair, no redesign" mechanically enforced rather than conventional. **These are merits of drafting, not evidence of provenance.** Deciding on them would be deciding by convenience, which this reconciliation was told not to do. **They should be carried forward by an explicit act, not by silent merge — see §5.**

---

## 4 · What `KOS-GOV-GAPS-001` is

**A duplicate/accidental record**, created by a Governance actor that had no knowledge of the earlier one. Its portfolio scan observed 10 records; the 11th had been created by a second concurrent Governance process minutes earlier.

Its `seq 3` START is a **misrecorded human act**: the act was genuine, but was recorded against an assignment the human did not name. The log is append-only, **so it stands and must not be rewritten.**

**It should be retained, not deleted** — it is evidence. It is the clearest production specimen of `G-1` and `G-5` the programme has, and it was produced with nobody attempting to break anything.

---

## 5 · Decisions required from the Human / PO / ARB before any state changes

**None of the following was performed.**

| # | Decision | Note |
|---|---|---|
| **D-1** | How to park `KOS-GOV-GAPS-001`. `STOP` records a reason and is sticky — only an explicit `CONTINUATION` exits it. `CANCEL` marks the session cancelled but leaves the item `OPEN`. **`STOP` is the closer fit for a duplicate**, but it is the PO/ARB's call. | Its lane is currently **ACTIVE and holding ownership** on a record that should not be worked. |
| **D-2** | Whether to carry the *read-only* qualifier and the narrowed role set into the authoritative record. The authoritative grant's exclusion list is substantively read-only, but does not use the phrase; its role set is the full four. **A grant is append-only — this needs a new registered act, not an edit.** | The human's authorization did say *read-only*. |
| **D-3** | Registration of the `G-2`/`G-4` narrowing. The authoritative grant covers **all six** gaps; the PO/ARB narrowed the pass to four and routed `G-2`/`G-4` elsewhere. **That instruction is not yet registered on the authoritative record.** | Governance can register it on instruction. |
| **D-4** | The verification of `G-1`/`G-3`/`G-5`/`G-6` is complete and its lane is still **ACTIVE**. `COMPLETE` is a Governance act. **The verifying lane must not close itself.** | Owed bookkeeping. |

---

## 6 · Two structural observations — recorded, not promoted

**Neither is a finding, and neither is proposed for repair here.**

- **The grant identifier `G-KOS-GOVGAPS-VERIFY` now exists in two records.** Any future lookup by grant ID is ambiguous. Grants are append-only, so the collision cannot be corrected in place.
- **The record has no way to annotate a correction.** The engine's transition types are `REGISTER`, `HANDOFF`, `START`, `CONTINUATION`, `STOP`, `COMPLETE`, `FAIL`, `CANCEL`; any other type is refused. **There is no documentary transition**, so a misrecorded act cannot be marked as such inside the record it sits in — only in prose outside it, as here.

---

## 7 · Summary

```
Authoritative      KOS-GOV-GAPS-VERIFY-001   — identified by the human START act
Duplicate          KOS-GOV-GAPS-001          — retained as evidence, not deleted
Classification     A, one duplicated work item
State changed      none
Human decisions    D-1 … D-4 outstanding
```

**Traceability:** both records (3 transitions, 1 grant each) · `b52b31e4` · `754a79ab` · `4ec22bc4` · commission documents of both work items · PO/ARB START act 2026-08-16 · PO/ARB `G-2`/`G-4` routing instruction · `workflow-state.php` transition types (`:188–273`), grant append (`:314–339`)
