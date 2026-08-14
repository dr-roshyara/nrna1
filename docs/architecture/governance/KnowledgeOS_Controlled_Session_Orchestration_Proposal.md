# KnowledgeOS Controlled Session Orchestration — Governance Proposal

**Status: ✅ ACCEPTED WITH AMENDMENTS G-1–G-4 (PO/ARB, 2026-08-14) — GOVERNANCE RULE ONLY. This acceptance authorizes the RULE; it does NOT authorize implementation of any orchestration mechanism. A separate bounded Platform Implementation commission is required before implementation begins.**
**Amendment A-1 (PO/ARB ruling D-1–D-5, 2026-08-14): ROLE-BOUND EXECUTION — see the amendment section at the end of this document. Per D-1 this is an amendment to KOS-AI-ORCH-001; no KOS-AI-ORCH-002 exists.**
**Acceptance provisos:** placement accepted **provisionally pending the ADR:OQ-2 ruling** (G-5) · the **freeze-exception interpretation must be explicitly recorded as part of the implementation-authority decision** (G-6 — the reading itself is not yet stated and is NOT invented here). Acceptance record: ARB review document, §Acceptance. Amendments G-1–G-4 are applied inline below, each marked.
*(Original status, superseded 2026-08-14: 🟡 PROPOSED — awaiting Human/ARB review.)*
**Date:** 2026-08-14 · **Author:** Session 2 (governance stream), commissioned by the PO · **Type:** platform governance principle (execution policy), proposal
**Placement note (recorded, not rationalised):** `php scripts/doc-placement.php --scope=cross-product --maturity=research` returns **PENDING — unruled (ADR:OQ-2)**. Location `docs/architecture/governance/` was **directed by the commissioning authority**; the PENDING derivation is recorded per the placement rule and per the precedent set by the Platform Architecture Baseline's own placement note. `engineering/` was NOT used: it is under structural freeze (R-37/R-38) and this commission does not invoke the freeze exception.

---

## 1 · Problem

For one governed work item (the Election-Only 65/69 track), three-to-five AI engineering sessions (Governance, Implementation, Verification, plus Architecture and PO-driven sessions) operated as **independent Claude Code sessions in separate terminals sharing one repository checkout: one working tree, one Git index, one branch, one set of shared state files** (`.claude/CONTEXT.md`, `.claude/sessions/YYYY-MM-DD.md`, shared governance documents). Nothing in the platform coordinated their mutations. Boundaries were enforced by per-session discipline and human vigilance — which held, but only through repeated near-misses documented below.

**Desired capability:** for a governed work item, KnowledgeOS creates and coordinates its sessions as **one controlled workflow** — `Work Item → Governance → Implementation → Verification → Completion/Evidence` — rather than as unrelated processes that happen to share a filesystem.

## 2 · Observed failure modes *(all from this repository, 2026-08-13/14 — each with its evidence)*

| # | Failure mode | Evidence |
|---|---|---|
| F1 | **History rewrite across streams:** `git commit --amend` in one session rewrote another session's interleaved commit; recovered only because nothing was pushed (`git reset --mixed`, byte-identical verification) | session log 2026-08-13; the adopted stopgap rule *"never `--amend`/`rebase`/`reset --hard` while a second stream is active"* |
| F2 | **Foreign staged files in a shared index:** another session's staged renames sat in the index during an unrelated commission; a plain `git commit` would have swept them into the wrong commit (avoided via pathspec-only commits; the same mechanism later made a pathspec commit *fail* on untracked files — the workaround itself has failure modes) | 2026-08-13 board commission; `1d1c1a3a` |
| F3 | **Shared append-only files written concurrently:** two sessions appending to the same session log required hunk-selective staging (`git apply --cached` on a filtered patch) to avoid attributing one stream's work to another | session log, "commit hygiene" entries; `7f24b185` |
| F4 | **Contradictory concurrent commits to one governance document:** the PO's grant-acceptance commit (`f6bb5504`) and Session 2's "awaiting signature" commit (`104f729a`) landed minutes apart on the same file, recording opposite authorization states; reconciliation required its own commit (`09b0b500`). **Near-miss:** had Session 2 used a full-file write instead of targeted edits, the PO's performative acceptance record would have been silently destroyed | `f6bb5504` · `104f729a` · `09b0b500` |
| F5 | **Governance-state divergence:** for a window, the repository simultaneously asserted "grant granted" and "grant unsigned" — two sessions each correct at their own read time; authority state had no single coordination point | same commits |
| F6 | **`.git/index.lock` collisions** between concurrently committing sessions | PO-reported operational experience (this commission §0) |
| F7 | **Stale cross-stream directives:** an instruction ("commission the audit") arrived after another stream had already executed it; obeying it would have created a duplicate verification stream | Session 2 log 2026-08-14, "staleness caught" |
| F8 | **Ambiguous working-tree ownership:** another actor's uncommitted production edit (`ElectionUser.php`) and an untracked test of initially unknown provenance (`ElectionOnlyEntitlementPinTest.php`) sat in the shared tree across several commissions before ownership was established | session logs 2026-08-13; readiness gate §1 |
| F9 | **Authority ambiguity as a standing hazard:** with independent sessions, nothing structural prevents two sessions from *believing* they hold implementation authority; only the per-session register discipline prevented it | the entire grant-tracking burden of Sessions 2's registers |

**What held the line was convention, not structure** — and every convention above was invented *during* the incident it mitigates.

## 3 · Existing platform capabilities *(inspected, not redesigned)*

Source: `engineering/developer_guide/AI_Engineering_Platform_Architecture_Baseline_Current_State.md` (§ component matrix), `.claude/` governance, the live registry.

| Component | State | Relevance here |
|---|---|---|
| `composition_root` (CMP-001) | adopted — wires contexts via `settings.json` | the natural *declaration point* if session roles ever become configuration |
| `session_manager` (CMP-002) | adopted — **Session Continuity** (3 scripts: start-of-session context injection, logs) | continuity of ONE session; **no concept of multiple coordinated sessions** |
| `workflow_engine` (CMP-004) | adopted **in minimal form: tripwires + plan/progress convention** — *no orchestrator, no state machine, no execution aggregate in code* | the conceptual owner of a workflow contract, currently paper |
| `verification_engine` (CMP-005) | under-construction (guard surface only) | evidence handling for the Verification role |
| `knowledge_manager` (CMP-003) / `review_engine` (CMP-006) | deferred — not constructed | — |
| `platform_registry` (CMP-008) | active, self-registering | the natural *registration point* for an active-session record |
| `.claude` governance | session logs (append-only, shared) · CONTEXT (shared mutable) · plans · MEMORY · EP-01/02/03 · ES-001…006 · R-34 (engineering never accepts its own work) | the state files at the centre of F3–F5 |
| Concurrency / worktree protections | **NONE FOUND** — no lock, lease, ownership record, or active-session registry exists | the gap this proposal addresses |
| **Standing doctrine** | **"Governance precedes automation" — by ruling (R-37 burden of proof), software mechanisms may exist only after operational evidence shows the governance model insufficient** | **§2 above IS that operational evidence for exactly one area: concurrent mutation of a shared execution context.** This proposal therefore leads with the rule and classifies mechanisms as candidates (§10) |

**UNKNOWN (marked, not invented):** whether any part of KnowledgeOS outside this repository implements session coordination; whether the registry schema can carry session records without change; hook-execution ordering guarantees under concurrent sessions.

## 4 · Terminology

- **Logical session** — a *role* in the workflow: Governance · Implementation · Verification (others per workflow, e.g. Architecture). A role, not a process.
- **Execution context** — the mutable state a session can change: repository worktree · Git index · branch · shared state files (`.claude/CONTEXT.md`, session logs) · plan · the work item's governance documents. **The unit of ownership in this proposal.**
- **Terminal** — a human UI/process arrangement. **Not an architectural concept.** (§7)

## 5 · Proposed governance invariant *(the core of this proposal)*

> **INV-ORCH-1 — For every governed work item, KnowledgeOS MUST create and manage its AI sessions as members of one workflow execution context with explicit ownership. Sessions belonging to the same governed work item MUST NOT independently mutate the same execution context outside the workflow's coordination protocol.**

Three corollaries:

- **C-1 · Ownership is explicit, never inferred:** at any moment, the execution context has at most one owning session for mutation; ownership changes only by handoff (§9), never by a session noticing the context is idle.
- **C-2 · Identity travels with the session:** every session carries `{workflow-id · work-item-id · role · predecessor · state · authorization status · execution context}`. (F8/F9 are identity failures.)
- **C-3 · Coordination is part of the workflow, not of the humans:** the current model — humans routing messages between terminals and each session defensively re-verifying the world — is the documented failure surface, not the design.

## 6 · Session lifecycle *(proposed; states are PROPOSED vocabulary — the platform has no state machine today, so nothing is "supported by existing code" and the minimal set is chosen over the commission's full candidate list)*

```
                 Work item registered (governed)
                            │
              KnowledgeOS creates the session SET
                            │
        ┌────────── CREATED (all roles, inert) ──────────┐
        ▼                                                 
   Governance: ACTIVE ── decision/grant ──▶ HANDED_OFF
                                                │
   Implementation: CREATED ──(grant token)──▶ ACTIVE ── evidence ──▶ HANDED_OFF
                                                │
   Verification: CREATED ──(evidence token)─▶ ACTIVE ── verdict ──▶ COMPLETED
                                                │
                              any role: STOPPED / FAILED / CANCELLED
```

- **Who creates:** KnowledgeOS creates the session *set* when a work item enters governed execution; the Human/PO starts each session (starting is an authorization-adjacent act and stays human). **[G-3, accepted 2026-08-14]: a session start requires BOTH the predecessor's recorded handoff AND the human start act — neither alone unblocks a role.**
- **[G-1, accepted 2026-08-14]: Completion is recorded by the Governance role (or the Human) — Verification REPORTS, it does not close.** The lifecycle's terminal transition is a governance act, so "verification success → business acceptance" can never occur by omission.
- **Sequential by default.** A later role starts only on the previous role's recorded handoff. Overlap is permitted **only** as read-only observation (§8) or in an isolated context (§12).
- **Not all roles always exist:** a docs-only work item may need Governance+Verification only; the workflow declares its role set at creation. (Evidence: this programme ran 4–5 roles, not 3.)
- **STOPPED is sticky (§11):** a stopped session does not imply permission for any other session to continue; continuation is an explicit workflow transition recorded by Governance or the Human.
- Candidate states not adopted here (`READY`, `WAITING`, `VERIFIED` as distinct from `COMPLETED`): deferred — no evidence yet requires them; ES-001.1 parsimony.

## 7 · "Same terminal" — analyzed, and corrected

The originating human requirement was *"create sessions … and instruct to run in same terminal."* Analysis against §2:

| Claim | Verdict |
|---|---|
| Same terminal is the architectural requirement | **NO.** Every §2 failure is a *shared-mutable-context* failure. A single terminal serializes mutation as a side effect of serializing attention — it treats the symptom |
| Same worktree is the real concern | **YES** — shared worktree + shared index + shared state files without ownership is the mechanism of F1–F6, F8 |
| An execution lock would suffice | for repository integrity, largely yes (F1/F2/F4/F6); **not** for F5/F7/F9, which need session identity + a coordination point, not just a mutex |
| Separate terminals with isolated worktrees could safely work | **YES, for isolated work items** (§12). For the *same* work item, uncoordinated isolation reintroduces F5 (divergent governance state) at merge time — so same-item roles coordinate by default; isolated same-item contexts exist only as §12's explicitly-coordinated exceptional case *(consistency wording updated with Clarification A, 2026-08-14)* |
| KnowledgeOS should abstract the terminal entirely | **YES — recommended.** The rule speaks of execution contexts and ownership; terminals are one human arrangement among several |

**Therefore: the human UI arrangement ("one terminal") is one possible *realization* of INV-ORCH-1 — acceptable as an interim practice, wrong as the encoded rule.**

## 8 · Concurrency rule *(commission §6 — the three options evaluated)*

| Option | Evaluation |
|---|---|
| **A — one session mutates at a time, globally** | prevents F1–F6; **over-broad** — needlessly prohibits parallel work on isolated work items (§12); A is the degenerate case of C with a single context |
| **B — concurrency only via isolated worktrees/branches** | right for **different work items**; wrong as the same-item rule — same-item roles share governance state whose *divergence* was itself a failure (F5); isolation defers the conflict to an unmanaged merge |
| **C — reads concurrent; mutation requires exclusive ownership of the execution context** | matches every observed failure; permits the useful concurrency we actually practised (watchers reading while another stream worked); smallest rule that protects integrity |

> **RECOMMENDED (smallest sufficient): Option C per execution context, with Option B as the isolation rule BETWEEN work items.**
> *Within a work item:* any session may read; **exactly one session at a time holds mutation ownership** of the shared execution context, acquired and released only through handoff (§9).
> *Between work items:* concurrency is permitted iff execution contexts are isolated (separate worktree/branch and separate state-file namespaces).
> The hunk-splitting workaround for shared append-only files (F3) must not become the permanent design. **[G-4, accepted 2026-08-14: the earlier sentence "session logs get per-stream sections or per-stream files" is reclassified as a CANDIDATE MECHANISM (see §10/§16) — it is design direction, not part of this rule.]**

## 9 · Handoff protocol *(codifying what this programme actually proved workable — reconciled with EP-01/02, R-34, ES-004.3; not a parallel system)*

| Handoff | Must exist before control passes *(each item evidenced by this programme's working practice)* |
|---|---|
| **Governance → Implementation** | accepted decision(s) · **explicit bounded grant** (performative, recorded verbatim) · scope + exclusions · invariant/acceptance criteria (tests cite the rule IDs) · **boundary-presentation gate where granted** *(the 65/69 grant's six-question reconciliation, PO-reviewed, RED only after)* |
| **Implementation → Verification** | implementation commit(s) · test results · changed-file manifest · baseline comparison (frozen `SD-1`) · known limitations · the standing **"Session 3 does not self-certify"** clause (R-34) |
| **Verification → Completion** | independent verification result · evidence package · final disposition · unresolved/out-of-grant observations **explicitly labelled "detected ≠ authorized"** *(the `start()` observation is the worked example)* |
| **Any → STOPPED** | the stop reason recorded; the register updated; **no successor inherits authority from the stop** |

Handoffs are **workflow state transitions carrying a token (the grant / the evidence package), not chat messages** — F7 (stale directives) is what message-passing between uncoordinated terminals produces.

## 10 · Shared repository protection — controls classified *(commission §10; none implemented)*

| Control | Classification |
|---|---|
| Exclusive mutation ownership per execution context (the rule itself) | **REQUIRED GOVERNANCE RULE** (INV-ORCH-1 / §8-C) |
| Active-session registry (who exists, role, state, context) | **REQUIRED GOVERNANCE RULE** as a *record*; its realization (registry entry vs file) is mechanism. Natural home: `platform_registry` (CMP-008, active) |
| Handoff token (grant / evidence package as transition artifact) | **REQUIRED GOVERNANCE RULE** — already de-facto practice (§9) |
| **Authoritative authorization-state object** — authorization as queryable workflow state (`{status · authority · scope · boundary · issued}`), so a role asks the workflow *"am I authorized?"* instead of interpreting another role's documents | **REQUIRED GOVERNANCE RULE** as a record — the direct cure for F5/F9. *(This programme's near-miss is the proof: for a window the repository carried both "GRANTED" and "unsigned" in prose, and only manual reconciliation resolved it. A session must never implement because it* saw *the word GRANTED in a file.)* Realization (registry entry vs YAML vs other) is mechanism, not decided here |
| Staged-file ownership check before commit (`diff --cached --name-only` review) | **possible mechanism** — today a convention born of F2; candidate for a PreToolUse tripwire *(consistent with the platform's existing tripwire style)* |
| Dirty-tree / foreign-modification detection at session start | **possible mechanism** (session_manager already injects start-of-session context; a natural extension point — NOT designed here) |
| Worktree lease / mutation lock (`.git`-adjacent lockfile or registry lease) | **possible mechanism** for INV-ORCH-1; smallest realization candidate |
| Branch-per-role, commit-ownership metadata (trailers) | **optional hardening** — commit trailers already distinguish streams informally |
| Stop-the-world transition between roles | **unnecessary as a rule** — sequential-by-default (§6) already provides it; keeping it as a named rule would duplicate §6 |
| Removing `.git/index.lock` automatically | **unnecessary and forbidden** — waiting is the rule (existing practice) |

### 10a · Session registry ≠ authority registry *(Clarification B, 2026-08-14 — the two required records above are DIFFERENT concepts and must never merge)*

> **Session existence, session state, and mutation ownership are NOT equivalent to implementation authorization.**

| **Session Registry** answers | **Authority State** answers |
|---|---|
| Who exists? | What has actually been authorized? |
| What role? | By whom? |
| What workflow? | For what scope? |
| What execution context? | Under which decision? |
| What state? | Within which boundary? |
| Who currently owns mutation? | Is the authorization currently active? |

```
ACTIVE session        ≠  AUTHORIZED session
MUTATION ownership    ≠  IMPLEMENTATION authority
SESSION existence     ≠  IMPLEMENTATION permission
```

**[G-2, accepted 2026-08-14]: Only the Governance role writes the Authority State, and only to register a recorded Human/PO/ARB act.** The workflow engine may coordinate both records; **it must not manufacture authority merely because a session is active.** A session holding mutation ownership of the execution context still implements nothing without an active, in-scope authorization in the Authority State — and an authorization existing grants nothing to a session that does not hold ownership. The two records answer different questions by design, and their separation is the structural form of the standing chain: **Human/PO/ARB → governance decision → explicit bounded authorization → implementation → independent verification.** The workflow engine automates the choreography; it does not become the source of business or architecture authority (§15).

## 11 · Recovery and interruption

Survivable events and the governed behaviour: terminal closed / session interrupted / usage limit — the session state remains whatever the register last recorded; **re-entry resumes the same logical session** (session_manager's continuity injection already supports this for one session). Failed implementation or verification → `FAILED`, register updated, **return to Governance** — never a silent retry by another session. Accidental human-started session → it has no workflow identity (C-2) and therefore **no mutation ownership**; it may read and must report. Unrelated repository modifications / existing Git lock → record, wait, escalate — never absorb, never remove (F2/F6 practice). Another workflow active → §12.

> **The load-bearing sentence (commission §11, adopted verbatim in substance): a stopped session must not automatically imply permission for another session to continue. Continuation requires an explicit workflow state transition.** *(This programme's precedent: Session 3's stop register; the trigger rule; watch-only postures — all explicit transitions.)*

## 12 · Multi-workflow model *(Clarification A applied 2026-08-14 — the earlier "DISALLOWED by default" wording for same-item isolated contexts is refined, not reversed)*

> **A governed work item has ONE authoritative workflow state. Its sessions normally operate within one coordinated execution context. Isolated execution contexts for the same work item are permitted only when KnowledgeOS explicitly coordinates them and preserves one authoritative workflow state and explicit ownership/handoff semantics.**

```
same work item
    │
    ├── shared/controlled execution context
    │       → NORMAL CASE (§8-C: reads free, one mutator, token handoffs)
    │
    └── isolated execution contexts
            → EXCEPTIONAL CASE — permitted ONLY when:
                 · KnowledgeOS explicitly coordinates them
                 · ONE authoritative workflow state is preserved
                 · ownership is explicit per context
                 · re-entry to the shared state is an explicit handoff
                 · NO independent governance decisions occur inside an isolated context

different items + isolated contexts        → concurrent freely (Option B isolation)
different items + same execution context   → serialized (the context is the scarce resource, not the terminal)
```

**What the exceptional case exists for** (future capabilities, none designed here): isolated analysis workers · sandboxed implementation · CI workers · remote execution · parallel read-only research · controlled specialist agents. **What it never permits:** uncontrolled parallel implementation, or a second source of governance state — the invariant that F5 (divergent authorization states) taught remains: *divergence of the authoritative workflow state is the failure; isolation of computation is not.* **No implementation mechanism for coordinated isolation is invented here.**

This preserves parallel engineering across work items while closing the observed same-item hazards.

## 13 · Canonical governance artifact *(commission §13)*

**Recommendation: ONE canonical artifact — a platform governance principle ("Controlled Session Orchestration", this proposal's §5–§12 as its content) owned where platform execution policy already lives, with the workflow contract as a *derived* artifact of `workflow_engine` (CMP-004) if and when the principle is accepted.**

**Candidate rule identity and text (PA-drafted, 2026-08-14 — PROPOSED wording for the ARB to accept/modify, recorded verbatim in substance):**

> **KOS-AI-ORCH-001 — Controlled Session Orchestration.** *AI engineering work requiring multiple specialized roles SHALL be orchestrated by KnowledgeOS as one controlled workflow. KnowledgeOS SHALL create and coordinate the required role sessions — at minimum Governance, Implementation, and Verification where the workflow requires them. These sessions SHALL NOT be independently initiated as unrelated Claude sessions against the same working tree. The workflow SHALL execute under a single controlled execution context, with explicit role boundaries, shared workflow state, ordered gates, and authoritative handoffs. Governance authorizes. Implementation executes. Verification independently verifies. No role may infer authority from another role's files, commits, tests, or observations. Concurrent independent sessions SHALL NOT modify the same working tree unless KnowledgeOS explicitly provisions and coordinates that concurrency.*

*(The rule text and this proposal's §5–§12 say the same thing at two granularities — rule and specification — not duplication; on acceptance the rule is canonical and §5–§12 are its elaboration.)* Not an ADR per rule (the principle is policy, not a single decision), though its *acceptance* should be recorded as a decision. Not a KnowledgeOS methodology rule (the methodology freeze stands; this is execution policy, and §2 supplies the freeze-exception evidence if the ARB reads it as methodology — **that reading is the ARB's call, flagged, not assumed**). No second home; `.claude/` carries at most a pointer if accepted (ES-005.4, never a copy).

## 14 · Recommended architecture *(summary of §5–§12)*

KnowledgeOS treats a governed work item's sessions as **one workflow**: created as a set with identities (C-2) · sequential-by-default with token-carrying handoffs (§6, §9) · exclusive mutation ownership per execution context, reads free (§8-C) · **authorization as queryable workflow state, never document interpretation** (§10) · isolation only between work items (§12) · sticky stops with explicit continuation (§11) · the workflow engine **coordinates and never decides** (§15's authority model). Terminals become irrelevant to the rule.

Two build directions, recorded (not decided): **(1)** the capability extends the existing `workflow_engine` (CMP-004) and `session_manager` (CMP-002) components — **no new independent subsystem** (ES-005.4/ES-001.1; the components' own capability assignments already name workflow guidance and session continuity). **(2)** *"Same terminal / one shared controlled context"* is acceptable as the **initial implementation policy** — an interim realization of INV-ORCH-1 while no mechanism exists — but it is policy, never the invariant (§7).

## 15 · Authority model *(what coordination may never become)*

The workflow engine coordinates execution; **it is not a source of business or architecture authority.** Never inferred: `evidence → authorization` · `implementation session → architecture decision` · `verification success → business acceptance` · `session existence → implementation permission`. The standing chain is preserved exactly: **Human/PO/ARB → governance decision → bounded authorization → implementation → independent verification** — the chain this programme executed by hand and that this proposal automates the *choreography* of, never the *decisions*.

## 16 · What is explicitly NOT decided here

The implementation mechanism (lock vs lease vs registry) · any `workflow_engine`/`session_manager` change · the terminal/process model · state-file restructuring (per-stream session logs) · registry schema · whether AMB-style session records enter `platform_registry` · the ARB's freeze-exception reading (§13) · anything about the Election work items.

## 17 · Open questions

1. Does the ARB read this as execution policy (no freeze issue) or methodology evolution (freeze exception required — §2 is the evidence)?
2. Where do per-stream session logs live if F3's workaround is retired?
3. Is the Human "start" act (§6) itself a registry-recorded transition?
4. Cross-repository work items (platform + product): one execution context or two coordinated ones?
5. Placement: this document sits in a PO-directed location over a PENDING derivation — the placement rule for cross-product research (ADR:OQ-2) needs its own ruling.

## 18 · Acceptance criteria for a future implementation commission *(not created here)*

A conforming implementation must demonstrate: (1) two sessions of one work item cannot both hold mutation ownership (F1/F2/F4/F6 impossible by construction); (2) session identity is inspectable (F8/F9); (3) handoffs are recorded transitions carrying their token (F7 eliminated for in-workflow directives); (4) a stopped session's context cannot be mutated without an explicit transition (§11); (5) isolated work items still run concurrently (§12); (6) zero change to the authority chain (§15) — demonstrated by replaying this programme's 65/69 track under the new model with identical decision points.

## Traceability *(commission §14.18)*

The Election-Only 65/69 track as the worked evidence base: `f6bb5504`/`104f729a`/`09b0b500` (F4/F5) · `7f24b185` (F3 convention) · `1d1c1a3a` (F2) · session logs 2026-08-13/14 (F1, F7, F8) · Platform Architecture Baseline (component states; "governance precedes automation"; R-37/R-38) · `developer_guide/ai_platform/01_registry_first_workflow.md` · EP-01/EP-02/EP-03 · R-34 · ES-001.1 · ES-004.3 · ES-005.4 · ADR:OQ-2 (placement PENDING).

---

**Status: ✅ ACCEPTED WITH AMENDMENTS G-1–G-4 (PO/ARB, 2026-08-14) — RULE ONLY · No implementation authorized · No platform code changed · No `workflow_engine`/`session_manager` changes · No `.claude` changes · No Election changes.**
**Next: a separate bounded Platform Implementation commission (humanly issued — not created by this acceptance) → its own verification → operational qualification. Until then, sessions remain governed manually under the existing conventions, with "same terminal" as the interim operational convention (accepted principle 5).**

---

# Amendment A-1 — Role-Bound Execution (PO/ARB ruling D-1–D-5, 2026-08-14; registered by Governance)

**Provenance:** Session 4's role-bound-execution architecture addendum (`2026-08-14-KOS-AI-ORCH-001-role-bound-execution-architecture-addendum.md`, `641d4112`), reviewed and ruled by the PO/ARB. **Per D-1 this content amends KOS-AI-ORCH-001 — deliberately NOT a new rule (no ORCH-002): the addendum is ~95% already covered by this rule; the genuinely new content is below.** Registered by Session 2 (Governance) under the accepted G-2 discipline: registering a recorded PO/ARB act.

## A-1.1 · Canonical role model (D-3)

The platform has **four canonical roles**: **ARCHITECTURE · GOVERNANCE · IMPLEMENTATION · VERIFICATION**. The role set remains **workflow-declared**: a workflow MAY declare additional, explicitly named workflow-specific roles, which follow the same governance discipline. **The platform is NOT a closed four-role enum** — the canonical four are the default vocabulary, not a ceiling. *(Consistent with §6's "not all roles always exist"; no conflict found on registration.)*

## A-1.2 · R8 — role immutability per assignment (D-2)

> **R8: Role is immutable for the lifetime of a SessionAssignment. A role change is not mutation of the existing assignment. A legitimate role change creates a NEW assignment with predecessor linkage, using the existing HANDOFF → START machinery.**

Consequences: a `sessionRegistry.sessions[]` entry is a **SessionAssignment**; its `role` field never changes; no transition mutates role (one added fold rule); an informal "now I'll also verify" has **no record path** — role immutability is structural, like single-ownership. **R8 joins R1–R7 as a contract of the approved Increment-1 boundary** (D-2: boundary approved WITH R8). Provenance note: the refinement originated with the human reviewer during Session 4's architecture work and was adopted by explicit ruling.

## A-1.3 · Startup convention (D-4 — IN EFFECT IMMEDIATELY as an operating convention; NOT runtime enforcement)

Before beginning work, every session answers **eight questions**: **1** work item · **2** role · **3** workflow state · **4** mutation owner · **5** authorization · **6** boundary · **7** predecessor/handoff · **8** prohibitions.

> **If any required answer is missing or contradictory: STOP · remain read-only · report and escalate to Governance · never infer authority.**

This is the articulated form of the consultation duty this rule already carries. As protocol text it is convention, effective now; **as enforced gating (blocking hooks) it is Increment 2 — NOT authorized.**

## A-1.4 · Sequential reassignment (D-5)

Sequential role reassignment within the same process is **allowed** — each reassignment being a NEW SessionAssignment per R8. **R-34 remains binding: a process that implemented a work item may NOT independently verify that same implementation.** Permitted sequences include architecture→implementation and architecture→verification (if it did not implement); **implementation→verification of the same work is prohibited.** **Process/terminal identity must not become the authority mechanism** — terminal/process agnosticism (accepted principle 5) is unchanged.

## A-1.5 · What this amendment does NOT change

The invariant INV-ORCH-1 · the authority model and chain · one-mutation-owner · Session Registry ≠ Authority State (G-2 writer rule) · sticky STOPPED · G-1/G-3 · the Increment-1/Increment-2 separation — **enforcement of any kind, including role gating, remains Increment 2: NOT AUTHORIZED.** No `workflow_engine`/`session_manager`/`platform_registry`/`.claude` mechanism is created by this amendment.
