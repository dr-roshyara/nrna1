# KOS-GOV-ATTRIBUTION-001 — PO/ARB decisions `P-1`…`P-6` registered

**Date:** 2026-08-15 · **Registered by:** Session 2 (Governance) · **Acts:** `P-1`–`P-6`, PO/ARB, 2026-08-15
**Registration only.** No implementation grant · no mechanism change · nothing invented beyond the ruling.

> **`A-5.2` disclosure pointer.** This registration concerns evidence this process also reviewed (`184d2745`, `e0a9d31c`), and — per that review's §0 — **whether it also produced the ADP cannot be established from the record.** Prior act: ADP `988c3593`. **Independence status: `asserted`, not `attested`.** *(`A-5.2`'s four-part duty attaches to Governance **review** artifacts; this is a registration, and the pointer is carried for continuity rather than because the duty is triggered.)*

---

## 1 · The decisions, registered verbatim

**`P-1` — Q-1 attribution direction**
> *"A5 — hybrid/defer, but only with a mandatory escalation trigger to be defined and recorded before the deferral becomes operational policy. A5 must explicitly be treated as a deferral, not as a solution to current Governance attribution."*

**`P-2` — Engineering → Governance**
> *"STRENGTHEN A-4.3. Keep permission-with-disclosure; make disclosure structured and mandatory, including identification of the prior Engineering act and the Governance review's independent contribution. Do not introduce a blanket prohibition."*

**`P-3` — Attribution invariants**
> *"APPROVE INV-ATTR-1 and INV-ATTR-2 as governing principles: process identity is evidential only and never an authority input; self-declared identity must never be represented as independently attested."*

**`P-4` — DEP-3**
> *"APPROVE investigation/preparation of per-lane Git identities as an operating improvement, but do not treat Git identity as proof of independent judgment and do not modify AST-015/AST-016 under this decision."*

**`P-5` — Q-3**
> *"REPORTING-ONLY. A machine may report process-distinctness/provenance findings, but it must never claim that process distinction proves independent judgment and must not act as an authorization or enforcement gate."*

**`P-6` — sequencing**
> *"Do not reopen AST-015 yet. First establish the operational value and limits of A5 + strengthened A-4.3 + DEP-3. Keep DEP-1/D-2/D-6 as a separately commissioned mechanism-evolution family. Do not batch them into implementation without a new architecture boundary."*

**Also registered:** *"No implementation grant is implied by this decision. In particular, do not modify AST-015, AST-016, hooks, SESSION_START, or workflow semantics as a consequence of this act alone."*

## 2 · Where each decision was given effect

**Registered as Amendment `A-5`** in `KnowledgeOS_Controlled_Session_Orchestration_Proposal.md` — the canonical home, alongside `A-1`…`A-4` — plus a header pointer. **Extended, not copied** (`ES-005.4`).

| Decision | Section | Effect |
|---|---|---|
| `P-1` | `A-5.1` | `A5` registered **as a deferral**, with `A5`-is-not-a-solution stated explicitly and **`OB-1`** recorded as a blocking precondition |
| `P-2` | `A-5.2` | **Supersedes `A-4.3`'s prose duty.** Permission unchanged; disclosure becomes a **required four-part section** (`D-a`–`D-d`) |
| `P-3` | `A-5.3` | `INV-ATTR-1` + `INV-ATTR-2` adopted as **governing principles**, quoted in full |
| `P-4` | `A-5.4` | Investigation/preparation approved as **operating-setup only**, with the measured limits attached |
| `P-5` | `A-5.5` | **Reporting-only** binding on any future instrument, with the reason recorded so it is not re-litigated |
| `P-6` | `A-5.6` | **`AST-015` not reopened**; `DEP-1`/`D-2`/`D-6` kept as a separately commissioned family; no batching without a new architecture boundary |

### `A-5.2` — the required disclosure, in force now

Every Governance review artifact where the overlap exists must carry **all four**:

| | Required item |
|---|---|
| **`D-a`** | The overlap, stated plainly — which capacities, which work item |
| **`D-b`** | The prior Engineering act, identified by **commit SHA and/or artifact path** — not by description |
| **`D-c`** | Evidential status of the independence claim — **`asserted` or `attested`**; per `INV-ATTR-2` a self-declared identity must **never** be presented as attested |
| **`D-d`** | The review's **independent contribution** — what it checked that the producer could not check itself |

**Missing any of `D-a`–`D-d` is a governance defect.**

## 3 · 🔴 Outstanding obligation — `OB-1`

> **`A5` is NOT operational policy yet.** `P-1` makes the escalation trigger a **precondition**, and it is **not defined**. Required: **threshold · owner · review point.**
>
> **Until `OB-1` is discharged, the current practice continues as unruled practice, not as an adopted position** — and, per `P-6`, **`OB-1` also gates the start of the evaluation period**, since that period is defined as establishing the operational value of `A5` + strengthened `A-4.3` + `DEP-3`.

**`OB-1` is a governance obligation, not implementation.** It needs a PO/ARB act (or a Governance proposal for approval). **Not performed here** — the ruling did not define the trigger, and Governance will not invent one.

## 4 · Follow-up work items and dependencies identified

**As the ruling directed. None commissioned; none authorized.**

| # | Item | Kind | Status |
|---|---|---|---|
| **`OB-1`** | Define + record `A5`'s escalation trigger | **Governance obligation — blocking** | 🔴 **open; gates `A5` and the `P-6` evaluation** |
| **`FU-1`** | `DEP-3` per-lane git identities — investigation/preparation | Operating setup. **No mechanism change** | **approved by `P-4`; no lane registered.** Needs a lane if it is to proceed |
| **`FU-2`** | `P-6` evaluation period — establish operational value **and limits** of `A5` + strengthened `A-4.3` + `DEP-3` | Governance/operational evidence | open; **starts after `OB-1`** |
| **`FU-3`** | Mechanism-evolution family: **`DEP-1`** (actor field) · **`D-2`** (grant↔session linkage) · **`D-6`** (read-only participation) | **All reopen qualified `AST-015`** | **separately commissioned; NOT batched; needs a new architecture boundary** |
| **`FU-4`** | Reporting instrument for provenance (if ever built) | Requires `AST-016` change (`DEP-5`) | **not authorized**; would be **reporting-only** per `P-5` |
| **`DEP-4`** | Machine-readable review→evidence linkage | Convention | ✅ **partially discharged by `A-5.2` `D-b`** — no mechanism change needed |

**Condition attached to any future `DEP-1` authorization** (from `A-5.3`): an `actor`-style field would require a **contract test proving no gate reads it** (`INV-ATTR-1`). Recorded as a condition on a future authorization, **not** as an authorization.

## 5 · What this registration does NOT do

**No implementation grant is implied.** No modification to `AST-015`, `AST-016`, hooks, `SESSION_START`, workflow semantics, git configuration or `executionContext` is authorized by this act alone.

**Unchanged:** `R-34` · `A-1.4`/`D-5` · accepted principle 5 · `INV-ORCH-1` · `R8` · `Inv C`/`R1` · `G-1`/`G-2`/`G-3` · the authority conjunction · `A-4.1`/`A-4.2`/`A-4.4` · the `A-4.3` **permission** (only its duty's form is strengthened) · the prohibition on wiring `AST-016` into `SESSION_START` while `V-3` is unresolved.

**Not resolved, not reopened, not folded in:** `V-3` (`KOS-ACTIVATION-REPORTING-001`, OPEN 0/0) · `D-2` · `D-6` · `E-1` · `O-CLOSURE-VOCAB` · the bootstrap gap · Election work. **`KOS-SESSION-DISCOVERY-001` and `KOS-EXEC-TOPOLOGY-001` remain closed.**

## 6 · Work-item status

**`KOS-GOV-ATTRIBUTION-001`: the commissioned questions are answered and all six decisions are registered. The authorized scope is discharged.**

- Machine record: **`OPEN`** · `mutationOwner: NULL` · `S4-architecture-attribution` **COMPLETED** (seq 4).
- **Governance/documentary closure: NOT declared** — consistent with this programme's practice that closure is an explicit PO/ARB act.

> **⚠️ One reason to consider *not* closing it yet:** **`OB-1` is unresolved and belongs to this work item's own decision.** Closing now would leave a blocking precondition of `P-1` outside any open item. **Governance recommends either discharging `OB-1` first, or explicitly assigning it to a named successor item at closure.** *(Recommendation on lifecycle hygiene; the decision is the PO/ARB's.)*

---

## Traceability

`P-1`…`P-6` (PO/ARB 2026-08-15, §1 verbatim) · Amendment `A-5` + header pointer (`KnowledgeOS_Controlled_Session_Orchestration_Proposal.md`) · ADP `988c3593` · Governance review `184d2745` + §8 addendum `e0a9d31c` · `S4-architecture-attribution` seq 1–4 · `G-KOS-ATTR-ARCH` · `A-4.3` (permission retained) · `A-1.4`/`D-5` · `R-34` · `INV-DISC-2` · accepted principle 5 · `G-2` · `D-2` · `D-6` · `C-3` · `V-3` precedent for `P-5`'s reasoning · `ES-005.4`
