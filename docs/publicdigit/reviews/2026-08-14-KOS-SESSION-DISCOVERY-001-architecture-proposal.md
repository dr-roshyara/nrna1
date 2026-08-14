# KOS-SESSION-DISCOVERY-001 — Session-Assignment Resolution
# Architecture Proposal (Read-Side Capability)

**Session 4 — ARCHITECTURE (`S4-architecture-discovery`) · 2026-08-14 · grant `G-KOS-DISC-ARCH` (design-only)**

> ## PROPOSED — NOT HUMAN APPROVED
> Design only. No code, no `workflow-state.php` change, no hooks, no automatic activation, no Session 5. Next gate: PO/ARB approval of this boundary → implementation authorization → S3.

---

## 0 · Startup check + one process observation

Verified against `KOS-SESSION-DISCOVERY-001.json`: REGISTER seq 1 ✅ · HANDOFF seq 2 (token = intake §A–§G, `15f4f484`) ✅ · grant `G-KOS-DISC-ARCH` AUTHORIZED, scope = design-only ✅ · prohibitions = intake §G ✅.

**OBS-0 (escalated to Governance):** the PO's S4 START act was performed verbatim in the commissioning delivery ("start now to work s4 start act", 2026-08-14) but is **not yet registered** as a START transition. This session proceeded on the live human act — distinct from the earlier false-claim case, where no act existed and S4 correctly stopped — and requests Session 2 register the START citing that delivery as `humanAct`. The gap itself is evidence for this very work item: *resolution must report "START absent" as a fact, and the human, not the resolver, decides what it means.*

## 1 · Bounded context / ownership location

**Owner: `workflow_engine` (CMP-004).** The capability is a **query over the workflow record's semantics** — assignment, state, grant — which CMP-004 owns (Increment-1 split: CMP-004 semantics · CMP-002 runtime namespace · CMP-008 registration). It is **not** session-continuity: `session_manager` (CMP-002) carries one session's *context* across time; this capability answers *which governed assignment exists at all* — a workflow question. Evidence against CMP-002: `inject-context.sh` contains no workflow-assignment concept (intake §C, verified by inspection); grafting assignment resolution onto it would also entangle the design with the deferred SESSION_START-wiring decision (Increment-1 boundary §6.3), which stays deferred.

**Ubiquitous language (adopting the PO's correction):** the operation is **RESOLUTION, not detection**. A terminal never *detects who it is*; it *resolves which assignment the record permits it to operate as*. Vocabulary: **ExecutionHost** (terminal/worktree — never an authority source) · **AssignmentCandidate** · **ResolutionReport** · verdicts **RESOLVED · UNASSIGNED · AMBIGUOUS · UNRESOLVABLE**. The capability name says the boundary: *Session-Assignment Resolver*.

## 2 · Responsibility

> **Answer, from the authoritative workflow record alone: "Which work items exist in this worktree; which SessionAssignments do they contain; in what state; under what registered authorization — and therefore which single assignment, if any, could this ExecutionHost currently operate as?" — while changing nothing and authorizing nothing.**

Equally its non-responsibility: it never decides whether work *should* proceed. It surfaces facts; the session's startup check (D-4) and the human consume them. **Resolution output is an input to the eight-question check — never a substitute for it.**

## 3 · Input contract

| Input | Meaning | Discipline |
|---|---|---|
| runtime record directory (default `.claude/runtime/workflow/`) | where records live | filesystem enumeration of `*.json` names only — never content-guessing |
| `--work-item=<id>` (optional) | narrow to one work item | a **filter**, never an identity claim |
| `--role=<role>` (optional) | narrow to one role | a **filter**, never an identity claim |
| `--session=<id>` (optional) | ask about one specific assignment | passthrough to the mechanism's `identity` |

**Explicitly NOT inputs:** TTY, PID, hostname, shell, environment variables, process ancestry, terminal title, prose files, CONTEXT.md, session logs, prompts. There is no "who am I" heuristic — only "what does the record contain, optionally filtered by what the caller *claims* to be looking for," with the claim itself never strengthening the answer.

## 4 · Output contract — the ResolutionReport

One machine-readable report (JSON) + human rendering, containing:

```jsonc
{
  "verdict": "RESOLVED | UNASSIGNED | AMBIGUOUS | UNRESOLVABLE",
  "workItems": [                       // always the full roster found, even when filtered
    { "workItem": "…", "workflow": "…", "workItemState": "…verbatim from fold…" }
  ],
  "candidates": [                      // every assignment matching the filters — never silently pruned
    {
      "workItem": "…", "sessionId": "…", "role": "…", "state": "CREATED|ACTIVE|…",
      "predecessor": "…", "executionContext": "…",
      "mutationOwner": true|false,
      "grants": [ { "grantId": "…", "status": "…verbatim…", "scope": "…VERBATIM…", "humanActRef": "…" } ]
    }
  ],
  "resolved": { …exactly one candidate, only when verdict=RESOLVED… },
  "operable": true|false,              // true ONLY when resolved.state == ACTIVE
  "missingForActivation": ["recorded human START", …],   // when CREATED — facts absent, per G-3
  "reasons": ["…every rule that fired…"],
  "caveat": "RESOLUTION IS NOT ACTIVATION. This report creates no authority, no ownership, no state change. G-3 gates are untouched."
}
```

**Contract properties:** the `caveat` line is unconditional and unremovable · grant `scope` is always **verbatim** (O-2: no paraphrase, no semantic matching) · grant `status` is verbatim with **no interpretation of lifecycle completeness** (E-15: an at-rest `AUTHORIZED` may be historically consumed; the report may annotate `"lifecycleVocabularyIncomplete": true` as a standing caveat, never resolve it) · `authorizationLinkage`-style inference is **not** reproduced (see L-2, §9).

## 5 · Resolution algorithm

```
1  ENUMERATE   list <dir>/*.json                          (names only)
2  VALIDATE    each: parse → schema present → fold via the MECHANISM (§9)
               parse/fold failure ⇒ that record is marked INVALID (kept in report)
3  FILTER      apply --work-item / --role / --session narrowing
4  CLASSIFY    every remaining assignment = an AssignmentCandidate with folded state
5  VERDICT
     exactly 1 candidate, state non-terminal        → RESOLVED
         · state == ACTIVE                          → operable: true
         · state == CREATED                         → operable: false, missingForActivation listed
         · state == HANDED_OFF                      → operable: false ("your work is passed on")
     exactly 1 candidate, state terminal
       (COMPLETED / CANCELLED / STOPPED / FAILED)   → RESOLVED, operable: false, STOP semantics
     0 candidates                                   → UNASSIGNED
     ≥2 candidates                                  → AMBIGUOUS (all listed; NONE selected)
     no readable record / all invalid               → UNRESOLVABLE
     any invalid record alongside valid ones        → verdict from valid ones, but
                                                      "reasons" MUST carry the invalid-record fact
6  REPORT      emit ResolutionReport; exit codes mirror the mechanism's convention
               (proposed: 0 = report produced regardless of verdict; 65 = refused; 64 = usage)
```

**Two hard rules inside the algorithm:** the resolver performs **zero writes** (no file creation, no repair of invalid records, no touch of the record dir) and **zero transitions** (it never calls `append`/`grant`/`init` — structurally: the implementation must not even import those code paths, see §18).

## 6 · Ambiguity rules (edge cases C, D, M of the commission)

| Situation | Rule |
|---|---|
| multiple work items, no `--work-item` filter | **never auto-pick.** Verdict AMBIGUOUS with the roster; the caller must name the work item. Rationale: auto-selection by recency/alphabet is F7 (stale directive) re-created on the read side |
| multiple candidates within one work item (e.g. two CREATED roles) | AMBIGUOUS; list all; the human/commission names the role. A `--role` filter given *by the commission text* is legitimate narrowing — the resolver still verifies the filtered result is singular |
| stale assignment (old HANDED_OFF, superseded chains) | **staleness is not computable today** — transitions carry `seq` but no timestamps (L-1, §9). The resolver reports chain position (predecessor links) verbatim and makes NO recency judgment. Adding timestamps = mechanism change = dependency, not design (§16) |
| same role appearing in several work items | not ambiguity *within* the rules — each work item resolves independently (Inv J); ambiguity exists only for "which work item," handled above |

**The prohibition, stated once:** *no best-effort identity, ever.* An AMBIGUOUS verdict is a successful resolution outcome, not a failure to be papered over.

## 7 · STOP-safe rules (edge cases B, H–L, N)

| Condition | Report semantics | Session obligation |
|---|---|---|
| UNASSIGNED (0 candidates) | "no assignment exists" | **STOP.** Absence of assignment is NOT permission to act unmanaged — it means: request Governance registration |
| record ABSENT entirely (K) | UNRESOLVABLE | STOP; read-only; escalate. Never "no record = ungoverned = free" |
| record CORRUPT/invalid (L) | UNRESOLVABLE (or flagged alongside valid records) | STOP for that work item; escalate to Governance; **never repair, never re-derive by hand** |
| resolved but CREATED (E) | operable: false + missingForActivation | wait for recorded START (G-3); may read; may not act |
| HANDED_OFF (G) | operable: false | successor's turn; this assignment writes nothing further |
| STOPPED (H) | operable: false, sticky | only explicit CONTINUATION (recorded, Governance/Human) exits — resolver states this, cannot do it |
| COMPLETED / CANCELLED (I, J) | operable: false, terminal | a new need = a NEW assignment (R8) |
| grant absent for a resolved ACTIVE assignment (N) | operable: true, **authorized: facts show none** | R6 exactly: ACTIVE-but-unauthorized is representable and queryable; the session must not mutate under it |
| grant present, scope ≠ intended work (O, P) | verbatim scope surfaced; **no semantic match attempted** | the session/human judges scope fit; the resolver judging it would be interpretation = manufactured authority |

## 8 · Authorization-surfacing semantics

The report distinguishes, without collapsing (commission requirement): **assignment identity** (sessionId) · **role** · **workflow state** (folded) · **grant existence** (list may be empty) · **grant status** (verbatim; vocabulary incompleteness annotated per E-15) · **authorization scope** (verbatim string; exact-string reality per O-2 stated in the report, never softened by paraphrase).

What the mechanism **cannot answer honestly today** is documented, not patched:
- **"Is this grant still live?"** — GRANT_STATES includes CONSUMED/CLOSED, but practice never transitions grants (E-15); the resolver reports status at face value plus the standing caveat.
- **"Is this grant *for this session*?"** — grants carry no session/role linkage field; see L-2 (§9).
- **"Does scope S cover action A?"** — only exact-string equality exists (`authorized` command, O-2); the resolver surfaces, humans judge.

## 9 · Relationship to `workflow-state.php` — consumer, never a twin

**The single most important structural decision:** the resolver **consumes the mechanism's own read commands** (`fold`, and `identity` where a session id is given) and **never re-implements folding**. A second fold implementation could diverge from the first — a two-truth failure at the *derivation* level, which is F5 reborn inside the read side. The mechanism stays the sole interpreter of its record; the resolver adds only: enumeration (which files exist), candidate classification, verdict rules, and the report envelope.

Consequences: `workflow-state.php` is **not modified** (grant scope; the mechanism is qualified and closed) · the resolver's correctness is bounded by the mechanism's read surface — honest gaps become recorded limitations:

| # | Limitation found by inspection | Disposition |
|---|---|---|
| **L-1** | transitions carry `seq` but **no timestamps** → staleness/recency undeterminable | dependency for a future mechanism increment; NOT designed here |
| **L-2** | `identity`'s `authorizationLinkage` links **the last AUTHORIZED grant of the work item, whatever it is** (`workflow-state.php:360-364`) — not a grant scoped to the queried session/role. In KOS-OQ-001's record, `identity --session=S4-architecture-…` would have linked the *implementation* grant | the resolver does **not** reproduce this field; it lists grants verbatim instead. The linkage semantics deserve their own future review — recorded as a defect-candidate observation, **not repaired** |
| **L-3** | no roster command ("which work items exist") — precisely the intake's gap | this capability IS the answer, via enumeration + per-item `fold` |
| **L-4** | grant lifecycle vocabulary exists but is unexercised (E-15) | surfaced as a report caveat; remediation is its own work item |

## 10 · Relationship to existing `.claude` session infrastructure

**None is modified.** `inject-context.sh`, the daily session log, CONTEXT.md, the reminder hooks — all untouched. The resolver is invoked **manually/on-demand** in this increment (a session runs it as step 0 of its startup check; a human runs it to inspect the roster). Wiring it into SESSION_START injection is the *same* deferred decision it has been since the Increment-1 boundary (§6.3) — attractive, and deliberately not smuggled in here (governance precedes automation; the matrix's zero-new-hooks stance; the wiring would also make the resolver's output feel ambient/authoritative, which §13's authority creep guard argues against doing before operational evidence exists).

## 11 · Relationship to `executionContext`

`executionContext` remains **recorded data about** an assignment (today: the string `shared-worktree`), never an identity key. The resolver never matches "my terminal" against it; it simply reprints it. If a future multi-worktree increment makes executionContext load-bearing (worktree paths, isolation contexts per rule §12), the resolver's contract already carries the field without semantic change — that evolution belongs to that future increment.

## 12 · Component placement rationale

| Aspect | Placement | Why |
|---|---|---|
| semantics + contract | **CMP-004** (`workflow_engine`) | owns record semantics; the resolver is its second read-side asset, sibling to `workflow-state.php` |
| runtime data | `.claude/runtime/workflow/` (existing, unchanged) | CMP-002's namespace, per the Increment-1 split |
| registration | one CMP-008 registry asset entry (registry-first: entry → review → implement → verify) | the established pattern |
| implementation artifact (post-approval) | a **separate read-only script** beside the mechanism (name/language = implementation detail; the platform's existing php style is the natural fit) | separate because the grant excludes modifying `workflow-state.php`; read-only-by-construction is also cleaner to verify (§15, §18) |

*Alternative rejected:* extending `workflow-state.php` with a `resolve` command — architecturally attractive (one binary, zero duplication risk) but it modifies the qualified, closed mechanism, which both the intake boundary and this grant exclude; if the PO prefers that shape, it is a **dependency requiring separate authorization** (§16), not a variation of this design.

## 13 · Security / authority invariants

**INV-DISC-1 (read purity).** The resolver performs no write, no transition, no grant operation, no record repair — structurally (§18), not just by policy.
**INV-DISC-2 (no environment identity).** No input derived from TTY/PID/host/process/terminal; an ExecutionHost is where resolution *runs*, never *what it proves*.
**INV-DISC-3 (resolution ≠ activation ≠ authorization ≠ START ≠ ownership).** The read-side G-3: the report changes no gate; a session is ACTIVE only by recorded HANDOFF + recorded human START, exactly as before. The resolver must never become a second path to ACTIVE.
**INV-DISC-4 (verbatim authority).** Grant scope/status pass through untransformed; no paraphrase, no coverage inference, no lifecycle guessing.
**INV-DISC-5 (ambiguity honesty).** AMBIGUOUS and UNASSIGNED and UNRESOLVABLE are first-class verdicts; silent selection and best-effort identity are contract violations.
**INV-DISC-6 (record over prose, read side).** The report outranks any prose/prompt claim of identity or authorization; a session whose commission contradicts its ResolutionReport stops and escalates — this is the OQ's proven refusal pattern, now systematic.

**Authority-creep guard:** the standing risk is the report drifting from "facts for your startup check" into "the thing that authorizes you." Mitigations: the unconditional caveat line; `operable` deliberately meaning only "state == ACTIVE" (never "authorized"); authorization facts listed separately and verbatim; and no ambient/hook delivery in this increment (§10).

## 14 · Failure modes

| # | Failure | Answer |
|---|---|---|
| FM-1 | resolver treated as authorization oracle | INV-DISC-3/4 + caveat + `operable`≠authorized split |
| FM-2 | auto-picking among candidates "to be helpful" | INV-DISC-5; AMBIGUOUS is a success verdict |
| FM-3 | second fold divergence | §9: consumes the mechanism's `fold`; never re-derives |
| FM-4 | corrupt record silently skipped → false UNASSIGNED | invalid records always surface in `reasons` |
| FM-5 | prose/prompt identity overriding the report | INV-DISC-6 (the OQ evidence E-11/OBS-0 pattern, codified) |
| FM-6 | stale-assignment misjudgment | staleness not computed (L-1); chain reported verbatim |
| FM-7 | report cached/stale itself | reports are ephemeral query output, never persisted as state; re-run before acting |
| FM-8 | resolver failure treated as permission | UNRESOLVABLE ⇒ STOP; absence of an answer is never authority (INV-DISC-5 corollary) |

## 15 · Testability strategy

Contract tests in the R1–R8 style: synthetic records in hermetic temp directories (never `.claude/runtime/`), one test per edge case **A–Q** of the commission, plus: AMBIGUOUS multi-work-item roster (C/D) · invalid-record co-existence (L) · verbatim-scope passthrough incl. a scope string that *looks* paraphrasable (O/P) · R6 surfacing (N) · read-purity (the record directory's bytes are identical before/after every resolver invocation — the strongest single assertion in the suite) · caveat-line presence unconditional. All RED-capable today (the resolver does not exist); no Laravel coupling.

## 16 · Migration / compatibility / dependencies

**Migration: none.** Additive read-side; every existing convention, record, and script unchanged; the D-4 startup check gains a mechanical step 0 but remains the session's duty and remains judgment-bearing (scope fit, prohibitions).

**Dependencies recorded, per the intake's instruction — mechanism changes NOT designed here, each requiring separate authorization:** D-1 transition timestamps (would enable staleness reporting — L-1) · D-2 grant↔session/role linkage semantics (L-2, also a defect-candidate in `identity`) · D-3 grant lifecycle exercise/closure vocabulary (E-15) · D-4 `resolve` as a native mechanism command (§12 alternative) · D-5 SESSION_START wiring (the standing deferral).

## 17 · Explicit non-goals

Automatic session selection or activation · terminal detection/fingerprinting · hooks or SESSION_START wiring · any `workflow-state.php` modification · repair of L-1/L-2/E-15/O-1/O-2 · write operations of any kind · Session 5 or any new role · Increment-2 enforcement · role-journal implementation (separately ruled, unauthorized) · scope-semantics interpretation · cross-worktree federation · Election work.

## 18 · Proposed implementation boundary (for the approval, then S3)

| Artifact | Change |
|---|---|
| one new read-only resolver script (CMP-004's second implementation asset; sibling of, never a patch to, `workflow-state.php`) | **new** — structurally write-free: no code path invoking `append`/`grant`/`init`/`saveRecord`; consumes `fold`/`identity` output |
| contract tests per §15 (hermetic, edge cases A–Q + read-purity) | **new**, RED first |
| `.claude/platform/registry.yaml` | **one** governed asset entry under CMP-004 (registry-first) |
| session log / CONTEXT gate rows | append-only bookkeeping |
| **nothing else** — no existing script, hook, mechanism, record, or Election file | |

## 19 · Questions requiring PO/ARB decision

| # | Question |
|---|---|
| Q-A | Approve this boundary as the S3 implementation scope? (Then: implementation authorization → S3 START → RED → GREEN → S1 → qualification → closure.) |
| Q-B | §12 shape: separate resolver script (recommended, keeps the qualified mechanism untouched) vs native `resolve` command (needs its own mechanism-change authorization — D-4)? |
| Q-C | Should the ResolutionReport become **step 0 of the D-4 startup convention** once implemented (a convention amendment — Governance registration, no mechanism), or remain optional tooling until operational evidence? |
| Q-D | Dispose of **L-2** (`identity`'s authorizationLinkage links any last-AUTHORIZED grant): accept as known limitation, or open a defect-candidate work item? |
| Q-E | Priority of dependencies D-1 (timestamps) and D-3 (grant lifecycle, E-15) — before or after the resolver is built? (The resolver works without them; it is more honest with them.) |
| Q-F | OBS-0: confirm Session 2 registers this session's START retroactively citing your delivery act — and, generally, whether a live-but-unregistered human act permits proceeding (this session's judgment) or requires registration-first (stricter reading). **This is a genuine open rule question the OQ pattern has now hit twice.** |

---

**Traceability:** intake `2026-08-14-KOS-SESSION-DISCOVERY-001-governance-intake.md` §A–§G (`15f4f484`) · grant `G-KOS-DISC-ARCH` · commission delivery + START act (PO, 2026-08-14, verbatim — OBS-0) · `workflow-state.php` (read surface: `fold`/`identity`/`authorized`; `:17-30` usage; `:354-374` identity; `:375-391` authorized exact-string; GRANT_STATES `:37`) · OQ evidence O-1/O-2/E-11/E-14/E-15 + seq-6 CANCEL precedent · Increment-1 boundary §6.3 (wiring deferral) + §7 (G-3) · A-1/A-2/A-3 amendments · R6/R8 · rule §7 (terminal ≠ authority) · ES-001.1 · ES-005.4 · R-34 · the PO's "resolve, don't detect" correction (adopted, §1).

---

> # PROPOSED — NOT HUMAN APPROVED
