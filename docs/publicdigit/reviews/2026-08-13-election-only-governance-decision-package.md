# Election-Only Governance Decision Package — decision-ready, nothing decided

**Type:** Governance decision package (Session 2, gatekeeper) · **Date:** 2026-08-13
**⛔ Nothing implemented · nothing decided on the PO/ARB's behalf · no grant created · EM-VOT-002 not reopened · archaeology not redone · Session 1 not duplicated · board not edited (proposed wording only, §9).**

**Labels used throughout (exactly these six):** `ACCEPTED AUTHORITY` · `MEASURED EVIDENCE` · `OPEN DECISION` · `PROPOSED` · `IMPLEMENTATION AUTHORIZATION` · `OUTSIDE SCOPE`.

**Standing sequence this package preserves everywhere:**

```
BUSINESS / ARCHITECTURE DECISION → bounded implementation scope → explicit implementation grant → Session 3 → TDD implementation
```

---

## 1 · Current state *(verified this session — by status + explicit authority + evidence, never by file location)*

| Check | Result | Label |
|---|---|---|
| Session 4's four artifacts committed | ✅ `5ff413a3` | MEASURED EVIDENCE |
| `ADR_20260813_1722` status | ✅ **"🟡 PROPOSED — … Nothing in this ADR authorizes a migration, a rename, or a code change"** (verbatim header) | PROPOSED |
| PKS candidate status | ✅ **"CANDIDATE — not a standard, not authoritative, not operational"** (verbatim header) | PROPOSED |
| Implementation grant created by Session 4 | ✅ **NONE** — its own `R-34` header and §12 say so; reconciliation class E = empty set | IMPLEMENTATION AUTHORIZATION: none |
| Session 1 P0–P4 evidence present | ✅ independent-verification report §§10–13; "P4 COMPLETE · STOPPING" | MEASURED EVIDENCE |
| Session 3 | ✅ STOPPED (stop register, board §5); latest activity is a state-only commit `4da60983`, no production change | ACCEPTED AUTHORITY (PO direction) |
| `PBDIGIT-65`/`69` | ✅ NOT AUTHORIZED (board row; unchanged) | OPEN DECISION (A, then B, then a grant) |
| `EM-VOT-002` | ✅ IMPLEMENTED + INDEPENDENTLY VERIFIED + CLOSED — **not reopened here** | ACCEPTED AUTHORITY |

## 2 · Proven evidence *(Session 1 P0–P4, cited with Session 1's own strength labels — the strongest statement never exceeds the evidence)*

**ESTABLISHED (measured):** `EM-VOT-002` enforced on both paths with the approval-correct predicate · the anomalous state (`nomination_completed = true`, zero approved candidates, window open) is **production-reachable via `forceCloseNomination()` using only permitted operations** · `nomination_completed` is the **sole discriminator** · lifecycle derivation reaches **no valid state** in that configuration · **HTTP 500 proven on the voter-facing path** (no `catch`, no `render()`, no global mapping) · **`close_voting` disproven as recovery (measured)** · **no nomination lock exists anywhere** · **the obstruction to re-approval is the auto-rejection side effect** (data destruction, not a lock) · the clock resolves the state at `voting_ends_at` (the trap is time-bounded; traced post-window derivation: `Counting`) · 9 of the 19 regression rows are **order-dependent execution artifacts, all passing in isolation**; the 3 snapshot tests **verify** Election-Only snapshot sovereignty (`O-3`).

**TRACED, NOT EXECUTION-MEASURED:** whether `suspend` fails in execution as the trace predicts (state derivation occurs before the command). *Session 1 lists this NOT ESTABLISHED — do not cite it as proven.*

**DELIBERATELY NOT MEASURED:** whether a **new** candidacy/application can be created and approved in the anomalous configuration — **fenced because its expected outcome IS the semantics `EM-OPEN-021` has not decided**. *The new-candidacy path must NOT be treated as an escape route.* **You cannot verify an expected business behaviour before the business has defined it** — a bounded, cheap follow-up once the ruling exists.

**Regression arithmetic (closed):** 19 rows = 7 superseded-rule expectations + 3 blocked on `EM-OPEN-021` + 9 execution artifacts.

### 2a · P6 reconciliation (dated addendum, 2026-08-13 — `fc86049f`) — MEASURED EVIDENCE, not architectural authority

**`PBDIGIT-65` and `PBDIGIT-69` are REPRODUCED at runtime** — no longer suspected defects. One controlled A/B, same valid `ElectionMembership` (`role=voter`, `status=active`) throughout, only tenant context and cache state varied: wrong tenant + cold cache → `isVoterInElection = false` (**65**: the tenant scope hides a valid membership) · correct tenant + warm cache → still `false` (**69**: tenant-unaware cache replays the wrong answer into the correct context) · correct tenant + cleared cache → `true` (proves scope+cache, not the row). **The reproduced predicate is the VOTING-TIME gate** (`isVoterInElection`, called by `EnsureElectionVoter` on the live middleware stack); **admission-time is measured UNAFFECTED** (`DB::table()` queries — no Eloquent global scope). **Ambient tenant context IS the defect mechanism** — confirmed; Session 1 withdrew its own P5 "no ambient contamination" headline (the model-level global scope injects the tenant filter invisibly). Also measured: `ElectionMembership::booted()` cache clearing addresses stale-after-WRITE only — it **cannot** prevent 69's deny-poisoning, which a READ in the wrong context creates.

**Evidence limitations (cited as Session 1 states them):** the HTTP-level replay was **NOT re-run** in P6 (the ticket's own 2026-08-09 A/B proved the HTTP entry; P6 proves the mechanism persists one level below it) · run on the **testing** database with throwaway rows · no production/test/configuration/Constitution/schema change was made · the untracked entitlement-pin tests were not used, run, or modified.

**What P6 does NOT decide:** ownership (Decision A) · repair scope (Decision B) · repair location (the ticket's three candidate locations each remain defensible, **none selected**) · `EM-OPEN-021` (independent, untouched) · Session 4's ADR/PKS (statuses unaffected — P6 is evidence, not validation of a proposed rule). **P6 creates no authorization: grants remain NONE; 65/69 remain NOT AUTHORIZED; Session 3 remains STOPPED.**

## 3 · Open decisions *(each separate; none combined)*

| # | Decision | Kind | Label |
|---|---|---|---|
| 1 | `EM-OPEN-021` — lifecycle meaning of the anomalous configuration (§4) | business/domain | OPEN DECISION — **evidence sufficient to decide** |
| 2 | **Decision A** — voting-time eligibility ownership (§5) | architecture | OPEN DECISION |
| 3 | **Decision B** — 65/69 repair scope (§6) | architecture/governance | OPEN DECISION — sequenced after A |
| 4 | `BR-1.12` — admission state | business | OPEN DECISION |
| 5 | `59` — which clock is constitutional | business | OPEN DECISION *(presented with 67; decided as two)* |
| 6 | `67` — what an entered time means | business | OPEN DECISION |
| 7 | `SD-15` — `has_chief` ratification (evidence favours the deliberate 2026-05-22 change) | ratification | OPEN DECISION |
| 8 | `EM-OPEN-019` — nomination threshold 30 vs 40 (implemented behaviour observably 40) | business | OPEN DECISION |
| 9 | Session 4 ADR/PKS disposition (§7) | governance | OPEN DECISION |
| 10 | Session-1 F6/F1–F6 tasking (§8) | governance sequencing | OPEN DECISION |
| — | `EM-OPEN-013` confirmation · `EM-OPEN-017` · `Q3` | standing, unhurried | OPEN DECISION |

## 4 · EM-OPEN-021 decision package *(the most important business decision — no answer chosen)*

> ## **"What should the Election lifecycle MEAN when nomination has been completed, the voting window opens, but there is no approved candidate?"**

Facts proven: §2. The current `InvalidElectionStateException` is **MEASURED EVIDENCE of technical behaviour — it is not the intended domain semantics and must not become the rule by repetition.**

**Semantic categories — presented only where existing evidence supports the row; *precedent* ≠ *proposal*; NONE recommended:**

| Category | Existing implementation precedent (MEASURED EVIDENCE) | What choosing it would be (PROPOSED business semantics) |
|---|---|---|
| **Remain in / re-derive a pre-voting state** | the state vocabulary already contains pre-voting states (`SetupNomination`, `ReadyForVoting` — existing enum cases; no new state needed) | a rule that zero-approved-candidates keeps the election in a named pre-voting meaning while the window runs |
| **Enter a defined exceptional/blocked state** | `Suspended` exists as a state; Constitution has `suspend`/`resume` actions, with resume documented as *"engine re-derives state from constitutional facts"* — **but suspend's reachability from inside the trap is TRACED-blocked, not execution-measured** | a rule that this configuration is a defined blocked condition with an administrative exit |
| **Permit an operational recovery** | **no nomination lock exists anywhere** (measured); the obstruction to re-approval is the **auto-rejection data destruction**, not a lock; `close_voting` **disproven**; new-candidacy path **deliberately unmeasured** (fenced on this very decision) | a rule that approving/creating a candidacy while the window is open restores derivability — *would require the fenced follow-up verification after the ruling* |
| **Refuse / invalidate the window** | the **command** path already refuses `open_voting` without approved candidates (`EM-VOT-002` — implemented and verified); the **computed** path has no refusal representation — its fall-through throw IS today's behaviour | a rule that a window without an approved candidate is constitutionally invalid, with a defined representation on the computed path |
| **Adopt the current behaviour deliberately** (mapped, non-500 error as the defined meaning) | the behaviour itself exists (HTTP 500 proven — currently unmapped) | an explicit ruling that "no derivable state" is the intended meaning, with the 500 replaced by a defined, graceful representation |

**Constraints on any choice:** do not invent a lifecycle state merely to make tests green · the 3 category-D regression rows become assertable only after this ruling · the sequence after the ruling is **decision → (targeted verification of the chosen semantics, if needed — including the fenced new-candidacy path) → bounded scope → explicit grant → Session 3 TDD.**

## 5 · Decision A package — voting-time eligibility ownership *(OPEN DECISION; ownership NOT declared here)*

> ## **"Which bounded context owns the business decision: may an already-admitted voter cast a vote now?"**

**"Eligibility" is not one domain concept.** *Admission-time eligibility* (who may enter/be assigned — Contexts/Elections chain, live, mode-aware, **not in question**) is distinct from *voting-time eligibility* (may an admitted voter participate **now** — the legacy `app/Models` gate, mode-blind; **this decision's subject**). No generic Eligibility context or `EligibilityService` is proposed or permitted by this package.

| Evidence on record | Label |
|---|---|
| Adopted business language points toward the Election context (`EM-GOV-001` *the election governs exercisability*; `EM-ENT-007`) | ACCEPTED AUTHORITY (the rules) — their *ownership consequence* remains OPEN |
| `MB-5`/`PBDIGIT-49`: multiple implementations, **no named authority** | MEASURED EVIDENCE |
| Runtime placement (`ElectionOnlyPolicy::decideForContext()` behind the DI-bound port) | MEASURED EVIDENCE of **current execution — NOT proof of bounded-context ownership** (Session 4's own C-1; pending F6 falsification) |
| Ambient organisation context forbidden by adopted entitlement rules; the 65/69 defects are **now runtime-REPRODUCED as exactly ambient-context intrusions on the voting-time gate, with admission-time measured unaffected** (P6, §2a); three production sites already derive context from the election; 1 election : 1 organisation at schema level | MEASURED EVIDENCE supporting the **hypothesis** — *stronger after P6, still NOT ownership authority* |
| `AD-2` — the formal deciding item | OPEN DECISION |

**The wording awaiting authority (unchanged from the accepted board; still a HYPOTHESIS until accepted):**

> *"Voting-time voter eligibility is owned by the Election context; its resolution derives organisational scope from the election itself; ambient organisation context is a forbidden dependency for this resolution."* — **adopt / adopt-with-changes / reject.**

**A business concept is not owned by a bounded context because** a class lives there, a policy is instantiated there, a DI binding points there, a folder is named after it, or a legacy implementation is the current runtime path. **Deciding A authorizes no implementation.**

## 6 · Decision B package — 65/69 repair scope *(OPEN DECISION; follows A; neither scope chosen)*

**Why B follows A:** implementation location and ownership must be known before a repair boundary can be legitimate — otherwise the repair is a guess wearing a grant.

**P6's contribution to B (dated 2026-08-13):** the two instances are **proven** (MEASURED EVIDENCE, §2a) — but **P6 does NOT prove how many other `BelongsToTenant` consumers exist**; the pattern's blast radius remains unaudited. The write-path cache clearing in `ElectionMembership::booted()` is measured insufficient against read-created deny-poisoning — evidence that partial fixes at the instance level leave the mechanism intact. **Neither fact chooses a scope.**

| | **Scope 1 — demonstrated instances only** (`65`/`69`; `62` already fixed) | **Scope 2 — pattern as architectural defect** (`BelongsToTenant` / ambient-context / tenant-blind-cache family: audit, then repair all affected consumers) |
|---|---|---|
| Blast radius | bounded, known | unknown by construction — consumers unaudited |
| Risk of this choice | the pattern resurfaces at a fourth site | unaudited row-visibility changes across the product |
| Prerequisite | Decision A | Decision A **+ an audit deliverable before any change** |
| If chosen | grant names the instances; the pattern stays a recorded risk | grant names the audit as its own phase |

**Deciding B authorizes no implementation.** After A **and** B, a separate explicit implementation grant is still required before Session 3 moves.

## 7 · Session 4 ADR/PKS disposition *(choices prepared, none applied — useful evidence does NOT imply the proposed rule must be accepted)*

| Artifact | Choices before the ARB | What each means |
|---|---|---|
| **`ADR_20260813_1722`** (PROPOSED) | **ACCEPT** — the evidence-ranked authority rule + §5 matrix become the recorded answer to "who owns this capability today" (then AMB-4 maintainer is mandatory) · **MODIFY** — e.g. accept the matrix as record, hold the rule · **REJECT** — evidence remains citable as evidence; the rule dies · **DEFER** — status quo; the AMB-1 re-derivation cost continues | G-1…G-3 |
| **PKS candidate** (CANDIDATE, n=1) | **PROMOTE** (would extend an existing standard — engineering recommends `ES-005` — never a new ES; requires `ES-006.1` Human-decides) · **MODIFY** · **KEEP AS RESEARCH/CANDIDATE** · **REJECT** | G-4…G-6 |

**Open issues preserved, not solved, and not solvable by expanding Election-Only implementation scope:** `AMB-1` (Level-1 ADR discoverability — the freeze-exception question G-7 is argued, not established) · `AMB-4` (matrix maintainer — an unmaintained map becomes the next misleading artifact) · `AMB-5`/G-10 (is "one authority per concern" at n=2? argued, not adjudicated) · `AMB-6` (four ADR series/conventions — OUTSIDE SCOPE for Election-Only) · `AMB-7` (`VoterSourceStrategy` Phase-4 owner/trigger).

## 8 · Session-1 / F6 tasking status

```
Session 1 P0–P4:      COMPLETE (PO-bounded residue fully delivered; "P4 COMPLETE · STOPPING")
Session 4 F1–F6:      SEPARATE PROPOSED TASKING — a request in a handoff, with no standing
F6 in particular:     NOT automatically authorized by having been requested.
                      Only PO/ARB decides whether it runs — and F6 (re-derive eligibility
                      runtime authority) OVERLAPS Decision A: if commissioned, sequence it
                      relative to A so it informs the decision rather than competing with it.
No duplicate verification streams are created by this package.
```

## 9 · Proposed board annotations *(PREPARE, DO NOT APPLY — exact wording; no duplicate rows where the board already says it)*

The board already carries: grants NONE · 65/69 NOT AUTHORIZED · EM-VOT-002 closed · Session 3 STOPPED — **no duplicate rows proposed for those.** Three additions:

**A — board §4, evidence table, append one row:**

> | Session 1 P0–P4 complete (post-board): HTTP 500 **proven**; state **production-reachable** via `forceCloseNomination()`; `close_voting` **disproven** as recovery; re-approval obstructed by auto-rejection data destruction; `suspend` traced-blocked (execution unmeasured); new-candidacy exit **fenced — unmeasurable until this decision supplies an expected outcome**; 19-row surface fully decomposed (7 superseded · 3 blocked on this decision · 9 execution artifacts). **Evidence sufficient for the PO/ARB ruling** — *sufficient to make the decision; not a claim that every technical question is exhausted; targeted verification of the chosen semantics may follow the ruling.* | ✅ measured (`eb5d3e40`, `aaa23872`, `30d2c528`) |

**B — board §1, trigger-events block, append:**

> - **`5ff413a3` (Session 4) reconciled** (`2026-08-13-session4-governance-reconciliation.md`): findings classified A–F; class-A rows rest on accepted authority; class E empty — no authorization created; ADR stays PROPOSED, PKS stays CANDIDATE. Disposition set → decision package row 8a. **Session 1 P0–P4 complete; the only open Session-1 tasking question is Session 4's F1–F6 (PO sequences; F6 overlaps Decision A).** Decision-ready package: `2026-08-13-election-only-governance-decision-package.md`.

**C — board §7, insert row 8a:**

> | **8a** | **Session 4 disposition** — ADR accept/modify/reject/defer (G-1–G-3); PKS promote/modify/keep-research/reject (G-4–G-6); AMB-1 freeze exception (G-7); matrix maintainer (AMB-4); n=2 promotion (G-10/AMB-5); F1–F6 tasking sequence | architecture/governance |

## 10 · Explicit authorization state

```
Active implementation grants:   NONE
PBDIGIT-65 / PBDIGIT-69:        NOT AUTHORIZED  (Decision A → Decision B → separate explicit grant)
EM-VOT-002:                     CLOSED — implemented + independently verified; NOT reopened
EM-OPEN-021:                    OPEN DECISION — evidence sufficient to decide; no fallback selected;
                                current exception ≠ adopted semantics
Session 3:                      STOPPED
Session 4 ADR:                  PROPOSED (unchanged)
Session 4 PKS:                  CANDIDATE — NOT PROMOTED, NOT AUTHORITATIVE (unchanged)
Full Membership:                FROZEN / OUTSIDE SCOPE
```

---

## Questions requiring PO/ARB input *(the deliverable, in one list)*

1. **`EM-OPEN-021`** — choose the business meaning (§4). Decision-ready now.
2. **Decision A** — adopt / adopt-with-changes / reject the ownership wording (§5).
3. **Decision B** — Scope 1 or Scope 2, after A (§6).
4. **`BR-1.12`** — admission state.
5. **`59`** and **`67`** — two decisions, presented together.
6. **`SD-15`** — ratify or overturn `has_chief`.
7. **`EM-OPEN-019`** — 30 vs 40.
8. **Session 4 disposition** — ADR and PKS choices (§7), including AMB-1/AMB-4/AMB-5/AMB-7.
9. **F1–F6 tasking** — whether and when Session 1 runs it, sequenced against Decision A (§8).
10. **Board annotations A/B/C** — authorize application (§9).

**Purpose restated:** once these are decided, Session 3 can implement one bounded DDD slice per grant **without architectural guessing**.

**Evidence by pointer:** governance board (accepted, 2026-08-13) · Session 1 independent verification §§10–13 · Session 4 reconciliation (accepted as evidence) · Session 4 handoff / `ADR_20260813_1722` / PKS candidate (statuses unchanged) · readiness gate (PO-stamped, evidence only) · `ADR_20260807_1500` (ACCEPTED) · Manifesto §4a/§9.
