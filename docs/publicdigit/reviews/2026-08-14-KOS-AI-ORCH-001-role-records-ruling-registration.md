# KOS-AI-ORCH-001 Role-Scoped Session Records — Governance Registration of the PO/ARB Ruling

**Type:** Governance registration (Session 2) · **Date:** 2026-08-14 · **Subject:** Session 4's architecture proposal `2026-08-14-KOS-AI-ORCH-001-role-scoped-session-records-architecture-proposal.md` @ `7444a52d` (PROPOSED)
**⛔ Registration only. The authority originated with the PO/ARB performative act — this artifact registers it and creates nothing. No implementation authority exists or is created. No journal directory or file is created. Increment 1 is not reopened.**

---

## 1 · The human ruling — REGISTERED VERBATIM (signed performative PO/ARB act, 2026-08-14)

> **"PO/ARB RULING — KOS-AI-ORCH-001 — Role-Scoped Session Records — Date: 2026-08-14**
> **I, PO/ARB Chief, hereby rule as follows:"**
>
> **Q-1:** *Adopt Option C — one role-scoped journal per canonical role.*
>
> **Q-2:** *Adopt one journal per role provisionally. The role owns the journal; individual SessionAssignments are represented as distinct sections. Operational evidence may later require revision.*
>
> **Q-3:** *Adopt the rule that role identity and session identity are distinct. A role journal MUST NOT use a session-level identity as its file identity.*
>
> **Q-4:** *A machine MUST NOT parse role journals to determine workflow state, authorization, ownership, or other machine-truth. The workflow-state record remains the sole machine-truth.*
>
> **Q-5:** *Do NOT create human-decisions.md as a second authority source. Human authority remains in the existing performative/committed artifacts; Governance registers those acts by reference.*
>
> **Q-6:** *Do NOT create KOS-AI-ORCH-002 at this time. Route the approved role-record model as an amendment/evolution of KOS-AI-ORCH-001, subject to Governance's conflict/parsimony check.*
>
> **IMPORTANT: Q-6 does NOT authorize implementation.**
>
> **"This ruling authorizes Governance to REGISTER these decisions. This ruling does NOT authorize implementation. A separate implementation commission and boundary approval are required before any role-journal mechanism is built. The current Increment-1 implementation remains a separate work item and is not reopened by this ruling.**
> **Signed: PO/ARB Chief · Date: 2026-08-14"**

## 2 · Numbering reconciliation *(registered to prevent silent mis-closure — the ruling's numbering ≠ the proposal's §20 numbering)*

| Ruling item | Answers, in the proposal | Proposal §20 item affected |
|---|---|---|
| Q-1 (Option C) + Q-2 (one per role, provisional, assignment sections) | §1/§6 journal model | **proposal Q-1 → CLOSED** (Option C adopted; per-role provisional) |
| Q-3 (role ≠ session identity as file identity) | §6 (the adopted revision) | the §6 identity rule → **RULED** |
| Q-4 (no machine parsing of journals; workflow-state = sole machine-truth) | §12 candidate `INV-ORCH-EVID-1` — **ruled FOR JOURNALS** | **proposal Q-6 (generalization to ALL Markdown/CONTEXT.md) remains OPEN — deliberately not expanded** |
| Q-5 (no `human-decisions.md`; authority by reference) | §7 recommendation → **RULED** | *(the separate §20 Q-2 — a Governance-maintained `decisions.md` reference-INDEX — is NOT explicitly ruled and remains OPEN, per the PA's own defer list)* |
| Q-6 (no ORCH-002; amendment route) | §20 **Q-3 → CLOSED** (amendment, parsimony route) | — |

**Remaining OPEN proposal questions (explicitly preserved, not closed by this registration):** §20 **Q-2** (reference-index file) · §20 **Q-4** (journal lifecycle/archival on G-1 closure; retention) · §20 **Q-5** (daily-log relationship long-term) · §20 **Q-6** (generalization of the no-prose-authority invariant — touches `inject-context.sh`; needs its own analysis).

## 3 · Conflict / parsimony check (commissioned; performed before amending)

**Conflict check — NONE FOUND:** journals are evidence/projections, which sits cleanly under the accepted Session Registry ≠ Authority State separation (§10a) and the G-2 writer rule; ruling Q-4 *strengthens* the accepted "authorization is queryable state, never document interpretation" principle; ruling Q-2's assignment-scoped sections are R8-compatible by construction (sections per SessionAssignment, no role mutation); ruling Q-5 is the same-direction rule as the accepted rejection of authority-laundering surfaces. **Parsimony:** per ruling Q-6, the smallest registration is **Amendment A-2 on KOS-AI-ORCH-001** — performed; **no ORCH-002 exists.**

## 4 · Authority state after registration

```
Role-record architecture:   HUMAN-RULED (Q-1..Q-6 above) / GOVERNANCE-REGISTERED (this artifact + A-2)
Implementation:             NOT AUTHORIZED          Implementation grant:  NONE
Increment 2:                NOT AUTHORIZED          Hooks / locks / leases: NOT AUTHORIZED
.claude restructuring:      NOT AUTHORIZED          Journal directories/files: NOT CREATED
Session 3:                  MUST NOT IMPLEMENT (this track has no grant)
Increment 1 (separate):     implementation COMPLETE (c2f5a831) · independent verification COMPLETE
                            (aac62274) · governance closure per its own process — NOT reopened, NOT mixed
```

**The ruling authorizes registration, not construction.** A future implementation commission → Session 4 boundary → human boundary approval → Session 3 → Session 1 remains the only path to a mechanism.

## 5 · humanActRef

The performative act is the PO/ARB ruling text registered verbatim in §1 of this artifact; **`humanActRef` = this registration's commit (recorded in the session log and CONTEXT at commit time) + this artifact §1.** The registration artifact did not create the authority; it preserves it.

**Traceability:** proposal @ `7444a52d` (incl. §20.1 "preliminary positions, NOT the PO ruling") · Session 2's prior STOP report ("no performative ruling available" — now superseded by §1) · KOS-AI-ORCH-001 + G-1..G-4 + A-1 (`51ba56fd`, `f5981933`) · Amendment A-2 (same commit as this artifact) · Increment 1 (`c2f5a831`, `aac62274`) · R8 · R-34 · ES-001.1 · ES-005.4.
