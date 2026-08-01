# Program Board — Three Tracks (ARB-amended, authoritative)

**Date:** 2026-07-11 · **Kind:** program status/priorities — APPROVED WITH AMENDMENTS by the ARB; this document IS the amended version. Supersedes the single-backlog TODO framing of `20260710_1852_remaining_todos.md` for prioritization purposes (that document's detail remains valid; its structure is corrected here).
**The ARB's structural ruling:** the work is **three distinct programs with different objectives — never one backlog.** Mixing them conflates platform evolution with product delivery and research with both.

---

## Track A — Engineering Platform (governance & execution)

*Scope:* EEP · qualification · knowledge harvest/patterns · Reference Architecture · engineering rules.
*State:* implemented and frozen at Baseline v1.0 (R-27/R-37); Reference Architecture DRAFT with the adoption test; EEP Stable.

| Item | Status |
|---|---|
| **C3 — Cold-Boot Qualification** | ⏳ **Priority 1 of the whole program.** Validates repository bootstrap, CONTEXT assembly, provider independence, the execution protocol, and the qualification process. Fresh session only (execution-integrity clause). |
| Engineering Rules | ER-05..08 in force; **ER-09 renamed: the work item is now "Project Knowledge Architecture"** (it outgrew "Project Knowledge Management" — it may eventually contain the PK model, constitution, reference architecture, and protocol) — PAUSED pending Track B evidence. |
| Reference Architecture | DRAFT → ADOPTED only if it accurately described reality through one full cycle. |
| Platform evolution items (frozen inbox) | Nothing implemented now — evidence first (unchanged). |
| Stale-artifact cleanup | Per the retrospective's Stream B (scheduled). |

**Corrected C3 statement (ARB wording, replaces "the only remaining gate before fully operational"):**
> *C3 is the next architectural validation gate. Passing C3 demonstrates that the Engineering Platform can bootstrap independently. Subsequent operational use, qualifications, and retrospectives determine whether the platform matures from DRAFT to ADOPTED and later to STABLE.*

### Track A-Ops — Platform Operations (SPLIT OUT — operational capabilities, not platform architecture)

CI qualification runs · deployment documentation · backup · monitoring · disaster recovery. Sequenced after C3 ("first CI qualification and operational hardening" = priority 4). These never appear on the architecture backlog again.

## Track B — Knowledge Architecture Research (NEW program, evidence-gated)

*Scope:* the RQ-002 theory and its validation. *State:* **Research Phase — theory frozen at falsifiability (research freeze, 2026-07-11).*

| Deliverable | Status |
|---|---|
| General Knowledge Constitution | ✅ (frozen, falsifiable) |
| Project Knowledge Strategic Model | ✅ (frozen) |
| Knowledge Needs theory | ✅ (state machine, first-party-grounded) |
| Context Assembly theory | ✅ (research report — E-1 answered) |
| Pilot instrumentation | ⏳ awaiting ARB authorization |
| First project pilot (one real ticket, U/O counted) | ⏳ |
| **Pilot Qualification Report** *(added at ARB promotion-model review — did the model hold? what emerged? what disappeared?)* | ⏳ |
| **Promotion Recommendation** *(should a Reference Architecture exist at all?)* | ⏳ |
| Project Knowledge Reference Architecture | ⏳ **CONDITIONAL — written only if the Promotion Recommendation says yes. The RA must earn its existence; it is not a default deliverable.** |
| Evidence collection (~20–30 tasks horizon) | ⏳ |
| Research retrospective (promote/reject deferred concepts) | ⏳ |

**Refined promotion ladder (ARB — reuses existing machinery, no new process):** `Research → Pilot → Qualification → Engineering Standard → Stable Engineering Capability`. Qualification sits between research and engineering — exactly what the platform already does. Correction on record: the ladder has ALREADY graduated artifacts (EEP, qualification lifecycle, rulings, Reference Architecture, score-stop, rule parsimony are engineering standards, not research) — the Project Knowledge work simply hasn't entered it yet. Governing principle = the existing burden-of-proof rule applied to promotion: **evidence promotes architecture; architecture does not promote itself** (rule parsimony: no new rule needed).

Pending ARB decisions gating this track: research-report review · **EKP disposition** · pilot authorization.

## Track C — PublicDigit Product (the reason the other two exist — product primacy)

*Scope:* the Election System, DDD, business domains. *State:* EPIC-001 complete and formally closed; boards synced.

| Item | Status |
|---|---|
| EPIC-002 — Evidence (Strategic Discovery) | ⏳ next product work: literature review per the charter + research method |
| EPIC-003..006 (Voting · Appointment · Read Models · legacy migration) | queued per the renumbered roadmap |
| ENG-004 Mutation Ratchet 1 | backlog; earliest after EPIC-002 discovery |
| F-7C-x / legacy debt | EPIC-000 batches per the retrospective schedule |

## The ARB priority order (authoritative)

| Priority | Track | Next action |
|---|---|---|
| 1 | A — Engineering Platform | **C3 Cold-Boot Qualification** (fresh session) |
| 2 | C — PublicDigit | EPIC-002 Evidence discovery (PB-007/EPIC-001 already complete) |
| 3 | B — Knowledge Research | Instrument one real ticket using the Knowledge Architecture (on ARB pilot authorization) |
| 4 | A-Ops | First CI qualification + operational hardening |
| 5 | B — Knowledge Research | Evaluate pilot evidence; conduct the research retrospective |
| 6 | A — Architecture | Promote or reject deferred concepts — on evidence only |

*(Priority 2 amended from the ARB table's "Complete PB-007 / PB-004 execution": both are verified complete and formally closed on the boards — the product track's actual next action is EPIC-002. The correction follows the boards, which follow the evidence.)*

---
*Rule of this board: items move between tracks only by ARB ruling; each track keeps its own backlog; the product track holds primacy in resource conflicts (AIP-14).*
