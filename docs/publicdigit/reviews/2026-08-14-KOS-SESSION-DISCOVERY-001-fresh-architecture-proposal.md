# KOS-SESSION-DISCOVERY-001 — Session Assignment Discovery
# Fresh Architecture Proposal (Governed)

**Session 4 — `S4-architecture-discovery` · ACTIVE (seq-3 START, PO-ratified `4603b93d`; fresh-proposal constraint `c1215669`) · mutationOwner = S4 · grant `G-KOS-DISC-ARCH` (architecture-design only) · 2026-08-14**

> ## PROPOSED — NOT HUMAN APPROVED
> Fresh design, produced entirely under the ACTIVE assignment. **Provenance of inputs:** `cee1ee6b` = prior/untrusted input (pre-activation); `22354c24` = governed but review-form, superseded by the registered fresh-proposal constraint. Both are treated below as *evidence consulted*, never as conclusions inherited. Where this proposal converges with them, the convergence was re-derived. No code, no `workflow-state.php` change, no hooks, no Session 5. Next gate: PO/ARB architecture-boundary approval.

---

## A · Problem statement

An execution host (a terminal/worktree running an AI session) currently has **no governed way to answer**: *"Which logical session assignment, for which work item, in what state, may I operate as?"* The mechanism answers `identity` only when the caller **already supplies** the work-item id and session id (`workflow-state.php:17-30`) — it answers *"what am I, given who I am"*, never *"who exists to be."* In practice, identity is assigned by **prompt prose** — and that method has now failed in four recorded ways: a false claim of a registered act (the premature-test docblock), work performed under a live-but-unregistered act (the OBS-0/seq-3 provenance defect, PO-ratified as a preserved defect `4603b93d`), a true report of an act that never reached the registrar, and legitimate read-only work invisible to the record (O-4: S1-verify stayed `CREATED` through an entire verification).

## B · Evidence and architectural drivers

| Driver | Evidence |
|---|---|
| identity failures are the platform's founding failure class | F8/F9 (accepted rule §2) |
| manual discovery works but is ad hoc and repeatedly needed | E-11/E-14 (OQ); every session's hand-run startup check |
| no roster capability exists | intake §C ("cannot answer which work items are open / which assignment is mine"), re-verified against `workflow-state.php` usage |
| authorization answers are weaker than they look | O-2/E-13: `authorized(scope)` = exact-string grant existence only |
| ACTIVE conflates operability with mutation ownership | O-1; O-4's read-only participation is inexpressible |
| grant→session linkage does not exist as data | L-2: `identity.authorizationLinkage` returns the work item's last AUTHORIZED grant, whatever it is (`workflow-state.php:354-374`) — live-reproduced by this session's own `identity` call |
| staleness is uncomputable | L-1: transitions carry `seq`, no timestamps |
| grant lifecycle vocabulary exists but is unexercised | E-15: closed work items still show `AUTHORIZED` grants |
| provenance of human acts is channel-bound | the seq-3 incident + the registrar-delivery rule: a report of an act ≠ the act |

## C · Ubiquitous language

**ExecutionHost** — where resolution runs; never what it proves. **WorkItemRecord** — one authoritative record per governed work item. **SessionAssignment** — the governed binding {session id · role · work item}; role immutable (R8). **AssignmentCandidate** — an assignment matching the caller's filters. **Resolution** — the query act; deliberately *not* "detection": a host never detects who it is, it **resolves which assignment the record permits it to operate as**. **ResolutionReport** — the single output artifact. **Verdict** — `RESOLVED · UNASSIGNED · AMBIGUOUS · UNRESOLVABLE`. **Operability** — a report field true iff folded state == `ACTIVE`; says nothing about authorization. **AuthorizationFacts** — the six separately-surfaced facts of the actor conjunction (§I).

## D · Responsibility / boundary

> **Session Assignment Discovery is a pure query capability: it enumerates WorkItemRecords, classifies SessionAssignments through the mechanism's own fold, and reports facts and verdicts. It changes nothing, owns nothing, activates nothing, authorizes nothing, and repairs nothing.**

The bounded responsibility in one asymmetry: **discovery narrows uncertainty about what the record says; it never narrows the gates the record imposes.**

## E · Context relationship

`workflow_engine` (CMP-004) owns record semantics — discovery is its **second read-side capability**, sibling to the existing query commands. `session_manager` (CMP-002) hosts the runtime namespace and is otherwise uninvolved (its scripts contain no assignment concept — intake §C; wiring into SESSION_START remains the standing deferral). `platform_registry` (CMP-008) registers the eventual asset, registry-first. No new component, no new bounded context.

## F · Proposed capability

A **read-only resolver**: given the runtime record directory and optional narrowing filters (`--work-item`, `--role`, `--session`), it enumerates `*.json` records, obtains each record's state **exclusively through `workflow-state.php fold`/`identity`**, applies the resolution model (§G), and emits one ResolutionReport (JSON + human rendering) with an unconditional caveat line: *"Resolution is not activation. This report creates no authority, no ownership, no state change. G-3 gates are untouched."*

**Filters narrow; they never assert.** A `--session` filter is a question ("what does the record say about this id?"), not a claim strengthened by asking.

## G · Resolution model

```
ENUMERATE  record files (names only — no content guessing)
VALIDATE   parse → schema → fold VIA THE MECHANISM (never re-implemented)
FILTER     apply caller's narrowing
CLASSIFY   every surviving assignment = AssignmentCandidate with folded state
VERDICT    exactly 1 candidate            → RESOLVED (operability per §H)
           0 candidates                   → UNASSIGNED
           ≥2 candidates                  → AMBIGUOUS — all listed, NONE chosen
           no readable record             → UNRESOLVABLE
           invalid record beside valid    → verdict from valid ones + the invalid
                                            fact ALWAYS carried in `reasons`
REPORT     emit; zero writes; zero transitions
```

**The consumer-never-twin rule (structural):** the resolver never re-implements folding — a second fold could diverge from the first, recreating the two-truth failure (F5) at the derivation level. The mechanism remains the sole interpreter of its record.

## H · State / decision matrix

| Folded state of the single candidate | Verdict | operable | Report obligations |
|---|---|---|---|
| ACTIVE | RESOLVED | **true** | mutationOwner surfaced; AuthorizationFacts surfaced (§I) — *operable still ≠ authorized* |
| CREATED | RESOLVED | false | `missingForActivation: ["recorded human START", …]` — **"recorded" is load-bearing**: a live-but-unregistered act still counts as missing (registrar-delivery rule) |
| HANDED_OFF | RESOLVED | false | "work passed to successor" — successor named from the chain |
| STOPPED | RESOLVED | false | sticky; only a recorded CONTINUATION exits — the resolver states this, cannot do it |
| COMPLETED / CANCELLED / FAILED | RESOLVED | false | terminal; a new need = a NEW assignment (R8) |
| — (no candidate) | UNASSIGNED | — | **absence is not permission**: "no assignment exists — request Governance registration" |
| — (record absent) | UNRESOLVABLE | — | STOP-safe: read-only, escalate; "no record = ungoverned = free" is forbidden reasoning |
| — (record corrupt) | UNRESOLVABLE | — | never repair, never hand-derive; escalate to Governance |
| stale information | *(not a state)* | — | staleness is **uncomputable** (L-1, no timestamps); the resolver reports chain position verbatim and renders **no recency judgment**; reports are ephemeral — **re-run before acting**, never cache |

**Default posture, stated once:** *when the authoritative state is not safe to operate, the answer is a STOP-shaped verdict or `operable: false` — never inferred authority, never best-effort identity, never silent selection among candidates.* AMBIGUOUS and UNASSIGNED are **successful resolutions**, not failures to suppress.

## I · Authorization relationship (O-2 analyzed; question 6 decided)

**What `authorized(scope)` proves today:** that *some* grant with status `AUTHORIZED` and a byte-identical scope string exists on the work item. **What it does not prove:** the full actor conjunction —

```
correct session ∧ correct role ∧ ACTIVE ∧ mutation owner ∧ grant-holder ∧ act-covered-by-scope
```

Facts 5 and 6 are **not evaluable from the record**: grants carry no session/role linkage (L-2 is the symptom), and scope coverage exists only as string equality.

**Decision — the commission's A/B/C, partitioned rather than chosen singly:**
- **(A) Surfaced:** the report lists the six facts **separately and verbatim**, with 5 and 6 rendered as `UNKNOWN — not evaluable from the record`. UNKNOWN is a first-class answer.
- **(B) Dependency:** grant↔session/role linkage *as data* = **D-2**; both a discovery dependency and the cure for defect-candidate L-2.
- **(C) Separate future capability:** *evaluating* the conjunction — an authorization-evaluation capability with its own work item, per the PO's standing constraint ("do not assume Discovery should implement Authorization").

**Hard consequence:** the resolver **never emits `authorized: yes`**. It emits facts and UNKNOWNs; the session's startup check and the human judge. *Reporting an authorization fact is not implementing authorization* — that sentence is the boundary.

## J · Activation relationship

Resolution is **never a path to ACTIVE**. G-3 stands untouched: recorded HANDOFF ∧ recorded human START → ACTIVE, registered by Governance from an act **received in its own stream**. The resolver's entire contribution to activation is negative space: it makes *missing* activation facts visible (`missingForActivation`), so a session cannot claim ignorance — the seq-3 incident rendered as one report line instead of a governance episode.

## K · Ownership relationship

`mutationOwner` is surfaced verbatim; the resolver never assigns, transfers, or infers it. Note the current coupling honestly: O-1 makes ACTIVE imply ownership, so today `operable: true` co-occurs with ownership — the report still carries the two as **separate fields**, so that if D-6 ever decouples them, the contract is already correct.

**O-1 / read-only participation (question 7) decided:** **(B) dependency + (C) separate mechanism evolution — not (A).** The resolver can only surface what the record can express; O-4 shows the record cannot express authorized read-only participation. Therefore: the report carries an informational field naming the gap (`readOnlyParticipation: NOT EXPRESSIBLE by the current record — governed by convention (O-1/O-4)`), and the vocabulary cure is **dependency D-6**, a separately governed mechanism evolution. Inventing a pseudo-state inside the resolver would make it a second truth-source — the exact disqualifying move.

## L · Provenance / staleness handling (question 8)

From E-11, E-14, O-4, the seq-3 incident, and stale cross-session reads, one invariant family:

**INV-DISC-P (attestation boundary):** *the resolver attests only what the record contains.* It can attest that a **registration** of a human act exists (`humanActRef`, verbatim); it can never attest that an act **occurred**. It takes no prose as input — not CONTEXT.md, not session logs, not prompts, not its own prior reports — and its report **outranks any prose claim of identity or authorization**: a session whose commission contradicts its ResolutionReport stops and escalates (the OQ's proven refusal pattern, made systematic).

Staleness: with no timestamps (L-1) the resolver renders chain position only; recency judgment would be invention. Reports are **ephemeral query output** — never persisted as state, re-run before acting — so a stale *report* cannot become a stale *truth*.

## M · DDD placement (question 9)

| Concern | Placement |
|---|---|
| aggregate | **none new** — the Work Item/Workflow aggregate already exists; discovery is a **read-model/query service over it**, outside any consistency boundary. Inventing a "Discovery aggregate" would be ceremony (Methodological Fitness Rule): it holds no invariant of its own |
| domain | the resolution semantics (verdict rules, state matrix, the six-fact partition) — owned by CMP-004 as contract |
| application | the query orchestration: enumerate → mechanism calls → classify → report |
| infrastructure | filesystem enumeration; process invocation of `workflow-state.php`; JSON emission |
| port | *AssignmentResolution* (query port: filters in, ResolutionReport out) |
| adapters | inbound: CLI (the only one in this increment — invoked manually as startup-check step 0 candidate); outbound: the mechanism's existing CLI as the record port |
| composition root | **no wiring in this increment** — manual invocation only; SESSION_START injection remains the standing deferral (D-5) |

## N · Interfaces / dependencies

**Input:** record directory (default `.claude/runtime/workflow/`) · optional `--work-item` / `--role` / `--session` filters. **Explicit non-inputs:** TTY, PID, hostname, shell, env, process ancestry, any prose. **Output:** ResolutionReport — roster of work items found · all candidates with {assignment, role, folded state, predecessor, executionContext, mutationOwner} · grants verbatim {id, status, scope, humanActRef} with the E-15 lifecycle caveat · verdict · operability · missingForActivation · AuthorizationFacts with UNKNOWNs · readOnlyParticipation note · reasons (every rule that fired, including invalid-record facts) · the unconditional caveat line. Exit-code convention mirrors the mechanism (0 report / 64 usage / 65 refused).

**Question 10 answered: `workflow-state.php` does NOT change.** `fold` + `identity` suffice for the minimal capability. The attractive alternative — a native `resolve` command — **would** change the qualified mechanism and is therefore a **separately governed evolution boundary**, recorded as **D-4**, not designed here.

**Dependency register (separate authorizations, none designed):** **D-1** transition timestamps (staleness) · **D-2** grant↔session/role linkage data (also cures L-2) · **D-3** grant lifecycle exercise/closure (E-15) · **D-4** native `resolve` command · **D-5** SESSION_START wiring · **D-6** read-only-participation vocabulary (O-1/O-4).

## O · Non-goals

Automatic activation · automatic role switching · terminal/process identity as authority · implementation of any kind in this assignment · hooks · locks · leases · Increment-2 enforcement · remediation of O-1/O-2/L-1/L-2/E-15 (surfaced, never fixed) · Election work · role-record journal implementation (separately ruled, unauthorized) · Session 5 or any new role · authorization evaluation (§I-C) · caching or persistence of reports · cross-worktree federation.

## P · Risks

| # | Risk | Containment |
|---|---|---|
| P-1 | **authority creep** — the report drifts from "facts for your startup check" to "the thing that authorizes you" | never emits `authorized: yes`; operability ≠ authorization as separate fields; unconditional caveat; no ambient/hook delivery this increment |
| P-2 | second-fold divergence | consumer-never-twin, structurally (no fold logic in the resolver) |
| P-3 | ambiguity papered over "to be helpful" | AMBIGUOUS is a success verdict; silent selection = contract violation, pinned by test |
| P-4 | stale report treated as truth | ephemeral-report rule; re-run before acting |
| P-5 | resolver failure read as permission | UNRESOLVABLE ⇒ STOP; absence is never authority |
| P-6 | L-2 confusion imported | the `authorizationLinkage` inference is **not reproduced**; grants listed verbatim |
| P-7 | corrupt-record silent skip → false UNASSIGNED | invalid-record facts always in `reasons` |

## Q · Open architectural questions (PO/ARB)

| # | Question |
|---|---|
| Q-A | Approve this boundary as S3's implementation scope? |
| Q-B | Shape: separate read-only script (**recommended** — qualified mechanism untouched) vs native `resolve` command (= D-4, own authorization)? |
| Q-C | On implementation: ResolutionReport as **step 0 of the D-4 startup convention** (registered convention amendment) or optional tooling until operational evidence? |
| Q-D | L-2 disposition — live-reproduced defect-candidate: accept as known limitation or open its own work item? |
| Q-E | Dependency priority: D-1/D-3/D-6 before or after the resolver? (It works without them; it is more honest with them.) |

## R · Smallest implementation boundary (post-approval, S3)

One new read-only resolver script — CMP-004's second implementation asset, sibling of (never a patch to) `workflow-state.php`, structurally write-free (no code path reaching `append`/`grant`/`init`/`saveRecord`) · hermetic contract tests (§S) · **one** registry-first asset entry in `.claude/platform/registry.yaml` · session-log/CONTEXT bookkeeping · **nothing else**.

## S · Verification implications

Contract tests, RED-first, hermetic (synthetic records in temp dirs — never `.claude/runtime/`), no Laravel coupling: one test per §H row · multi-work-item AMBIGUOUS roster · invalid-beside-valid records · verbatim-scope passthrough including a paraphrasable-looking scope (O-2) · six-fact surfacing with UNKNOWNs (§I) · `readOnlyParticipation` presence (§K) · caveat-line unconditionality · **read-purity: the record directory is byte-identical before/after every invocation — the suite's strongest single assertion** · exit-code contract. Independent verification (S1) then attempts falsification: can any input make the resolver select among ambiguity, emit authorization, or write?

---

**Traceability:** ACTIVE state verified via the mechanism this session (`identity`/`fold`) · seq-3 START + PO ratification (`4603b93d`) · fresh-proposal constraint (`c1215669`) · intake §A–§G + grant `G-KOS-DISC-ARCH` (`15f4f484`) · prior inputs consulted-not-inherited: `cee1ee6b` (untrusted), `22354c24` (governed review-form, superseded) · `workflow-state.php:17-30, 354-391` · O-1 · O-2/E-13 · O-4 · E-11 · E-14 · E-15 · L-1 · L-2 (live reproduction: this session's `identity` output) · F5/F8/F9 · G-3 · R6 · R8 · R-34 · A-3 + registrar-delivery rule · Option-② disposition · ES-001.1 · ES-005.4 · DDD Methodological Fitness Rule.

---

> # PROPOSED — NOT HUMAN APPROVED
