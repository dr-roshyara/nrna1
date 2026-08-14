# KOS-AI-ORCH-001 Increment 1
# Architecture / Implementation Boundary Proposal

**Session 4 (architecture/design stream) · 2026-08-14 · rev 2 — restructured to the commissioned 16-section form; supersedes this session's rev 1 at the same path (uncommitted).**

> ## ~~PROPOSED — NOT HUMAN APPROVED~~
> ## ☑ HUMAN-APPROVED WITH R8 (PO/ARB ruling D-2, 2026-08-14 — status annotation by Governance; content untouched)
> R8 (role immutable per SessionAssignment; role change = new assignment via HANDOFF → START) joins R1–R7 as an approved contract. Ruling record: platform implementation commission §16 · Amendment A-1 on KOS-AI-ORCH-001. Next gate: RED.
> Every mechanism below is a proposal for PO review. This document creates no implementation authority, and its existence does not make the architecture authoritative. Next gate: **PO explicitly approves / amends / rejects → only then Session 3 may create RED tests.**

**⛔ Produced read-only: no production code, no RED tests, no `workflow_engine`/`session_manager`/`platform_registry` change, no `.claude` change, no lock/lease/hook, no Election change.**

---

## 1 · Commission and authorization

| Item | Reference | Status |
|---|---|---|
| Accepted rule | `KOS-AI-ORCH-001` + principles 1–7 + amendments G-1…G-4 — `docs/architecture/governance/KnowledgeOS_Controlled_Session_Orchestration_Proposal.md` | ✅ ACCEPTED (PO/ARB 2026-08-14) — **rule only** |
| G-6 freeze reading | **(ii)+(iii)**; F1–F9 = qualifying operational evidence | ✅ CLOSED (PO, commission §15) |
| Implementation commission | `2026-08-14-KOS-AI-ORCH-001-platform-implementation-commission.md` (§5–§9) | ☑ AUTHORIZED (PO, commit `7cbe5984`) — **gated on THIS boundary and its human approval** |
| G-5 rule placement | provisional (ADR:OQ-2 open) | unchanged — the rule is not moved |
| Increment 2+ (physical enforcement) | — | ⛔ NOT AUTHORIZED — not designed here |
| Election grants | — | NONE; Election untouched |

### 1.1 Gate-violation finding (reported, not adjudicated — R-34)

An untracked test file exists at `tests/Unit/Platform/WorkflowEngine/KosAiOrch001IncrementOneContractTest.php` (15,966 bytes, 2026-08-14 13:28), written **before** this boundary was approved. Its docblock asserts *"Authoritative boundary (human-approved)"* — **false at the time of writing and false now**. No production code, no runtime record, no registry change accompanies it (`app/Platform/` does not exist; the test is RED by absence). Session 4 does not delete, move, or amend another stream's file; §3 evaluates its content strictly as **candidate evidence**, and the gate breach itself is escalated to Governance (Session 2) for disposition. Any statement in that file claiming approval is void.

## 2 · Governing invariants (fixed — none of these is a design choice)

From the accepted rule and the commission's §6 A–J:

| Inv | Statement |
|---|---|
| A | ONE authoritative workflow state per governed work item |
| B | session identity `{workflow · work-item · role · predecessor · state · authorization-linkage · execution context}` |
| C | ONE work item → one governed mutable execution context → **exactly one mutation owner at a time**; reads concurrent; ownership moves only by explicit governed transition (never by noticing idleness — rule C-1) |
| D | handoff = recorded transition carrying its token; no token → no handoff |
| E | STOPPED is sticky; continuation only by explicit recorded transition |
| F | START = predecessor's recorded handoff **AND** explicit human start act (G-3) — conjunction, no inference |
| G | Session Registry ≠ Authority State — two records, never merged, jointly queryable |
| H | Authority-State writes: Governance role only, registering a recorded Human/PO/ARB act (G-2) |
| I | the coordinating mechanism exposes queries and contains **no decision logic**; closure is a governance act (G-1) |
| J | different work items: independent records, zero cross-item coupling |

**"Same terminal" is NOT among these** — the accepted rule's §7 explicitly corrected it to an interim implementation policy (see §11).

## 3 · R1–R7 contract reconciliation

**Provenance first:** R1–R7 are not a Session 3 invention — they are the commission's own §10 (traced to the accepted proposal's §18 acceptance criteria). They are therefore **authorized contract requirements**. What Session 3's premature test file adds *on top of them* is evaluated in the last column.

| Contract | Status | Authority | Architectural implication | Implementation prescription in the premature test? |
|---|---|---|---|---|
| **R1** single mutation owner | REQUIRED · inside Inc-1 · **contract** | Inv C / commission §10 | ownership must be *unrepresentable* as two owners — a single-valued ownership fact per work item | none beyond the contract |
| **R2** complete session identity | REQUIRED · inside Inc-1 · **contract** | Inv B | all §2-B fields answerable from the record alone — no document interpretation | none |
| **R3** ACTIVE requires token + human act (both directions) | REQUIRED · inside Inc-1 · **contract** | Inv D + F (G-3) | START must be a checked conjunction of two *recorded* facts | none |
| **R4** STOPPED sticky | REQUIRED · inside Inc-1 · **contract** | Inv E | no edge out of STOPPED except explicit CONTINUATION | none |
| **R5** authority writes Governance-only + human-act reference | REQUIRED · inside Inc-1 · **contract** | Inv H (G-2) | authority rows must carry a reference to a recorded human act; contract-level rejection (not physical prevention — Inc-2) | none |
| **R6** ACTIVE ≠ AUTHORIZED, queryable | REQUIRED · inside Inc-1 · **contract** | Inv G / rule §10a | the two records must be structurally disjoint so the state is first-class, not an error | none |
| **R7** work-item isolation | REQUIRED · inside Inc-1 · **contract** | Inv J | per-item state with zero shared mutable substrate | **yes** — asserts byte-identity of a sibling `<work-item>.json` file, freezing "one JSON file per item" |

**Where the premature test overreaches into architecture (each an unapproved decision):**

1. **FQCN `App\Platform\WorkflowEngine\WorkflowStateRecord` + `TransitionRejected`** — creates a new `App\Platform\` production namespace. This is not merely premature; it **violates ES-005.1 three-concern separation**: `app/` + `tests/` are the **Product** concern, while this mechanism is **Engineering-Platform/runtime** concern. A platform coordination mechanism inside the product's autoloaded namespace is an architecture decision nobody made, and this proposal **rejects it** (§9, §10).
2. **API shape** (`::initialize($dir, $workItem, …)`) — implementation detail asserted as contract.
3. **Storage prescription** (per-item `.json` file, byte-compare in R7) — a §9 design choice pre-frozen by a test.
4. **The "human-approved" claim** — void (§1.1).

**Consequence:** after approval, Session 3's RED tests must be (re)written against **§15's contract boundary** — the existing file cannot be grandfathered in as-is, because tests are executable architecture and these encode unapproved architecture.

## 4 · Authoritative state model

Two concepts, never collapsed (Inv G):

**A · SESSION / LIFECYCLE STATE (Session Registry)** — answers *who exists, in what role, in what state, who owns mutation*.

| Field | Why the governance rule requires it |
|---|---|
| work-item id | Inv A/J — the record's unit of existence and isolation |
| workflow id + declared role set | rule §6 "the workflow declares its role set at creation" |
| session id | Inv B; F8/F9 are identity failures |
| role | Inv B; role boundaries are the rule's §14 core |
| predecessor | Inv B + F — START checks the predecessor's handoff |
| lifecycle state | Inv B/E — the §5 machine's subject |
| mutation ownership (one value or none) | Inv C — R1's structural form |
| authorization linkage (grant reference, possibly none) | Inv B "authorization" field + R6 — links to, never contains, authority |
| execution context | Inv B; the unit of ownership (rule §4) |
| human-start evidence + handoff-token reference per START | Inv F (G-3) — both facts must be *recorded* to be checkable |
| transition history (who/what/when/token) | Inv D + I — handoffs are recorded transitions; auditability is the point of a record |

**B · AUTHORIZATION / GRANT STATE (Authority State)** — answers *what has been authorized, by whom, for what scope, within which boundary, active or not*.

| Field | Why required |
|---|---|
| grant id · status (`PROPOSED→AUTHORIZED→CONSUMED→CLOSED`, +`REVOKED`) | Inv H/I; G-1 closure-is-governance-act as a transition |
| authority (the human author — never a session) | Inv H (G-2) |
| human-act reference (committed artifact) | Inv H — the record **registers** acts, never creates them |
| scope · boundary · exclusions | rule §10 authorization-state object `{status · authority · scope · boundary · issued}` |
| registeredBy = Governance + timestamp | Inv H writer restriction |

No other fields. Fields appearing only in the premature test (API artifacts) are not adopted.

## 5 · Session lifecycle model

Exactly the accepted §6 vocabulary — `CREATED · ACTIVE · HANDED_OFF · COMPLETED · STOPPED · FAILED · CANCELLED` — no additions (`READY`/`WAITING`/`VERIFIED` stay rejected per the acceptance; ES-001.1).

```
 CREATED ──(F: predecessor handoff-token AND human start act — conjunction)──▶ ACTIVE
 ACTIVE ──(recorded handoff + token)──▶ HANDED_OFF
 ACTIVE ──(closure recorded by Governance/Human — G-1)──▶ COMPLETED
 any ──(recorded stop + reason)──▶ STOPPED   [STICKY: sole exit = explicit CONTINUATION recorded
                                              by Governance/Human → ACTIVE; no other edge EXISTS]
 any ──(failure recorded)──▶ FAILED ──▶ return to Governance; never a silent retry by another session
 any ──(cancellation recorded)──▶ CANCELLED
```

**Who creates:** KnowledgeOS creates the session *set* when a work item enters governed execution; **the Human starts each session** (rule §6). An accidental session without workflow identity has no mutation ownership; it may read and must report (rule §11).

## 6 · Authority model

```
Human / PO / ARB ──(performative act in a committed artifact)──▶ recorded authority
                                                                      │
Governance role ──(registers: grant row + human-act reference)──▶ AUTHORITY STATE   (sole writer — G-2)
                                                                      │
      sessions QUERY: "is grant X active for scope Y?"  — a state read, not an interpretation
                                                                      │
workflow_engine · session_manager · Session 3 · Session 1 ──▶ READ-ONLY consumers; none can manufacture authority
```

**The F5 near-miss, answered structurally.** For a window this repository simultaneously asserted "GRANTED" and "unsigned" in prose (`f6bb5504`/`104f729a`), reconciled only by hand (`09b0b500`). The architecture prevents authorization being *derived* from:

| Never a source of authorization | Because |
|---|---|
| document wording | authorization is a **grant row**, not a word; prose (CONTEXT.md, reviews) remains narrative, never queried as authority |
| session state | records structurally disjoint (Inv G); R6 makes ACTIVE-without-grant a first-class queryable answer |
| implementation intent | a session row cannot write the Authority State (Inv H) |
| a file existing | existence ≠ permission (rule §10a); a grant must carry `status=AUTHORIZED` **and** a human-act reference |
| a test passing | verification REPORTS; closure is a governance act (G-1); the engine contains no decision logic (Inv I) |

No enforcement hook and no automatic authorization exists anywhere in this model — the record makes authorization *queryable*; humans still make it.

## 7 · Handoff model

| Transition | Minimum architectural meaning |
|---|---|
| **REGISTER** | a session enters the Session Registry with full Inv-B identity, state CREATED; creation of the set is KnowledgeOS's act at governed-execution entry |
| **START** | CREATED→ACTIVE; **valid only when the record already contains BOTH** the predecessor's handoff transition (token attached) **and** the human start act (G-3). Neither alone unblocks. B proves nothing by assertion — the record either contains both facts or B cannot be ACTIVE |
| **HANDOFF** | ACTIVE→HANDED_OFF; a recorded transition `{from, to, token-reference}`; **transfers mutation ownership** (held for the successor); the token references the committed grant/evidence artifact — evidence *accompanies* the handoff by reference, never by copy (ES-005.4) |
| **STOP** | any→STOPPED with recorded reason; ownership does not pass; **no successor inherits authority from a stop** (rule §9) |
| **CONTINUATION** | the *only* exit from STOPPED; a new explicit transition recorded by Governance/Human — not a resumption, a decision |

Predecessor identity is a field of the session (Inv B) and is what START's conjunction checks against. **None of these transitions is implemented here; none presumes a persistence mechanism** — they are fold-semantics over *some* authoritative record (§9).

## 8 · Concurrency model

**Architectural invariant (fixed):** within one work item — reads concurrent, **exactly one mutation owner**, ownership moved only by §7 transitions. Between work items — isolation, free concurrency (accepted §8: Option C within, Option B between).

**Why same-item independent mutable contexts are not the default:** same-item roles share governance state whose *divergence was itself the failure* (F5) — isolation doesn't remove the conflict, it defers it to an unmanaged merge. The accepted §12 permits same-item isolation only as an explicitly coordinated exceptional case, **which Increment 1 does not implement**.

**Invariant ≠ synchronization mechanism.** The invariant is "two owners are unrepresentable and any second claim is adjudicable against the record." A *mutex* (lock/lease) is a synchronization mechanism that would make violation physically impossible — **that is Increment 2, not authorized, and deliberately absent**. Increment 1 makes violations *visible and adjudicable*, not impossible (commission §5's own slicing: F1/F2/F4/F6 mitigated by visible ownership, cured only by later enforcement).

## 9 · Storage / state-record design

**Category discipline first:**

| Element | Class |
|---|---|
| exactly one mutation owner · two never-merged records · G-2/G-3/sticky-STOPPED semantics · queryability | **A — GOVERNED OUTCOME** |
| an authoritative per-work-item **record contract**: identity + fields (§4) + transition semantics (§5/§7) + fold-rejection of illegal transitions + disjoint writer rules | **B — ARCHITECTURAL CONTRACT** (this proposal's core) |
| append-only JSON per work item in `.claude/runtime/workflow/` · fold implementation · helper tool | **C — IMPLEMENTATION MECHANISM (recommended, not frozen)** |
| locks · leases · hooks · write prevention · registry enforcement | **D — INCREMENT 2 / NOT AUTHORIZED** |

**Candidates evaluated:**

| Candidate | Evaluation |
|---|---|
| **(1) Append-only transition record + fold** | matches the platform's own append-only doctrine (CAP-02); illegal writes become *rejected transitions* — testable at contract level without any physical enforcement; full audit trail; state = fold ⇒ no state/history divergence. **RECOMMENDED** |
| (2) Current-state record (mutable snapshot) | loses the transition history Inv D/I require ("handoffs are recorded transitions"); an in-place edit model reintroduces last-writer-wins — F4's shape | 
| (3) Rows inside `platform_registry` | **rejected on evidence**: `registry.yaml` is a review-gated static inventory under the BINDING registry-first workflow (R-17/R-21); its header forbids growth; every ownership change would need a human-reviewed registry edit — schema violence. CMP-008 *registers the mechanism* (one governed asset entry), never *carries the state* |
| (4) Tracked (committed) state file | re-enters the F1–F4/F6 git-collision class the record exists to escape; every transition contends for the shared index |
| (5) Extend CONTEXT.md with a machine block | keeps authority inside the 2,586-line contested prose file — the F4/F5 surface itself |

**Recommended mechanism (class C — the approver may amend without disturbing the contract):** one gitignored JSON document per work item at `.claude/runtime/workflow/<work-item-id>.json`, containing the two records, written append-only, state = fold.

| Aspect | Recommendation |
|---|---|
| reason | `.claude/runtime/` **already exists** as the platform's runtime-state namespace (created by CMP-002's `session-changes-logger.sh`, `schema: 1` precedent, gitignored at `.gitignore:25`, shared by all sessions of the one checkout) — extension, not invention; and gitignored-but-shared is immune by construction to F1/F2/F3/F4/F6, which all struck *tracked* shared files |
| authoritative source | the record itself for workflow state; **committed governance artifacts remain the ultimate authority the record registers** (tokens/human acts are references into git history) |
| state ownership | semantics: CMP-004 · namespace: CMP-002 · registration: CMP-008 (§10) |
| write semantics | append transitions / grant rows only; never in-place edits; writer rules per Inv H and §7 |
| read semantics | any session reads; queries answered by folding |
| transition semantics | §5/§7; illegal transition = fold refusal (contract), not permission denial (Inc-2) |
| failure semantics | corrupted/absent record ⇒ no derivable owner ⇒ **stop and escalate to Governance** — never assume ownership from absence (C-1) |
| auditability | full history in the log; every authority row points at a committed artifact |
| migration | none — CONTEXT.md/session logs/MEMORY unchanged; the record adds a queryable layer, retires nothing (conventions retire only by explicit ruling after operational qualification — commission §13) |
| exclusion compatibility | no hook, no lock, no `.claude` *script* change (a new gitignored data directory is not restructuring), no registry schema change |

**Honestly stated:** the evidence *justifies* recommending (1)+this location over the alternatives, but the **contract (§4–§7) stands even if the PO amends the mechanism** — nothing in R1–R7 requires JSON, a filename, or a class name. One-file-vs-two-files and the path name are approver-amendable without re-design.

## 10 · Component boundaries

Verified against the live registry (`.claude/platform/registry.yaml`) and disk — CMP-004's registered "minimal form" is three reminder scripts; CMP-002 is three continuity scripts **already writing runtime state**; CMP-008 is the static inventory; CMP-001 wires; CMP-003/006 deferred; CMP-005 under-construction; CMP-007 adopted-by-reference.

| Component | Responsibility (Inc-1) | Authority responsibility | State responsibility | **Forbidden** |
|---|---|---|---|---|
| `workflow_engine` CMP-004 | **owns the record's semantics**: state machine, transition preconditions (G-3 conjunction, sticky STOPPED, G-2 writer rule), query vocabulary — its first real contract (today it is paper) | none — coordinates, never decides (Inv I) | defines the schema/fold; does not host the file | manufacturing/promoting authority · decision logic · enforcement |
| `session_manager` CMP-002 | **namespace host only** — the record lives in the `.claude/runtime/` namespace CMP-002 established. *Candidate later duty:* SESSION_START surfacing via `inject-context.sh` — **explicitly deferred; not in Increment 1** (keeps ".claude restructuring excluded" trivially true and the regression criterion "zero change to existing script surfaces" trivially checkable) | none | hosts the runtime namespace; its 3 scripts byte-identical | any script change in Inc-1 |
| `platform_registry` CMP-008 | **registers the mechanism**: one governed, human-reviewed asset entry under CMP-004 (registry-first: entry BEFORE implementation) | none | **never carries runtime state** (§9-3) | runtime rows · schema change |
| `composition_root` CMP-001 | nothing in Inc-1 (no new wiring — no hooks) | none | none | hook wiring |
| CMP-003/005/006/007 | untouched | — | — | — |

**Placement consequence (from ES-005.1):** the mechanism is platform/runtime concern, so its implementation artifact belongs with the platform's existing implementations (`.claude/scripts/`-style helper or equivalent **registered as a CMP-004 asset**) — **not** in the product's `app/` namespace. The premature test's `App\Platform\` placement is rejected (§3-1). Exact path/language = implementation detail *inside* this constraint, Session 3's after approval. Contract-test location should follow the same concern separation (position, not prescription: a `tests/` location is acceptable only if the PO accepts Product-concern tests pinning a platform contract; the alternative is a platform-side test home — **flagged for the approver, §14-Q3**).

## 11 · Same-terminal question

**Increment 1 does NOT encode "same terminal."** The accepted rule's §7 analysis is binding here: every F1–F9 failure is a shared-mutable-context failure; a single terminal serializes mutation only as a side effect of serializing attention — symptom, not cause. "Same terminal" remains what the acceptance called it: an **interim operational convention** (accepted principle 5) that only an explicit ruling after operational qualification may retire (commission §13). Encoding it as architecture would convert a convention into an invariant without evidence — exactly the promotion this commission forbids. The record is deliberately terminal-agnostic: identity, ownership, and authorization are properties of *sessions and work items*, not of process arrangements.

## 12 · Smallest implementation boundary (what Session 3 may change, AFTER approval)

| Artifact | Change | Class |
|---|---|---|
| `.claude/runtime/workflow/<work-item-id>.json` | created **at runtime**; gitignored; never committed | C |
| record-contract reference implementation (small helper in the platform's existing shell/php style; path per §10 placement constraint) | **new** — CMP-004's first implementation asset | C |
| contract tests R1–R7 per §15 (location per §10 placement note) | **new**, RED first | B-pinning |
| `.claude/platform/registry.yaml` | **one** governed asset entry under CMP-004 (registry-first, human-reviewed) | registration |
| session log + CONTEXT gate row | append-only bookkeeping (ES-004.3) | records |
| **Nothing else** — no existing script, hook, workflow file, Election file, or `engineering/` file | | |

Removing any element breaks an invariant; adding any begins Increment 2.

## 13 · Explicit exclusions (each shown excluded)

| Not authorized | How this design excludes it |
|---|---|
| locks / leases | no synchronization primitive anywhere; ownership is a recorded fact, violations adjudicable not impossible (§8) |
| hooks / automatic enforcement | no settings.json change, no new runtime_moment, no CMP-001 wiring (§10); consultation duty discharged by sessions reading, not by machinery intercepting |
| `.claude` restructuring | zero script edits; the only `.claude` addition is a gitignored runtime data file in the pre-existing runtime namespace |
| per-stream logging redesign | G-4 candidate untouched; session logs unchanged |
| terminal orchestration implementation | §11 — terminal-agnostic by design |
| registry enforcement | CMP-008 gains one reviewed inventory row; no schema change, no runtime rows (§9-3) |
| Increment 2 | every "reject" is a fold/contract refusal, never a physical prevention; K-3 raciness explicitly deferred as Inc-2's first input |
| Election changes | no Election file in §12; grants register unchanged (NONE) |
| unrelated platform refactoring | CMP-003/005/006/007 and all existing assets untouched |

## 14 · Risks / unresolved questions

| # | Item | Position |
|---|---|---|
| K-1 | gitignored state is unversioned (no git audit of the record itself) | acceptable for Inc-1: committed artifacts remain ultimate authority; append-only limits damage; durability = Inc-2 candidate |
| K-2 | advisory-only — a non-consulting session can still collide | by design (commission §5); the record makes it adjudicable |
| K-3 | the record file itself can race under concurrent appends (miniature F3) | **named, not solved** — the honest first input to Increment 2; solving it now would smuggle in a lock |
| K-4 | schema drift once implemented | `schema: 1` versioning per the existing state-json precedent; R2 pins the §4 fields |
| K-5 | bootstrap circularity (the mechanism's own build precedes it) | first transitions recorded retroactively; the commission's §11 replay criterion (65/69 track) covers exactly this reconstruction |
| K-6 | record drifts into a second CONTEXT.md | schema-only content; no free text beyond reason/scope |
| **Q1** | premature test file disposition (§1.1) | **Governance/PO decision** — Session 4 recommends: not grandfathered; rewritten against §15 after approval |
| **Q2** | one file vs two files per work item | recommended: one (two *records*, single consistency surface); approver-amendable |
| **Q3** | contract-test home vs ES-005.1 concern separation (§10) | flagged for the approver |

## 15 · Proposed RED contract boundary *(what tests MAY establish after approval — no tests are created here)*

After human approval, Session 3's RED tests are permitted to pin **exactly** the following, all failing today by absence of the mechanism, all expressed against the record **contract** (not a class name, not a file format, not an API shape):

- **R1** — given an owner, a second simultaneous ownership claim without an intervening governed transition is refused; the fold still yields exactly one owner.
- **R2** — every §4-A identity field is answerable from the record alone.
- **R3** — (a) a handoff lacking its token-reference is not a transition; (b) START without the recorded human act, or (c) without the predecessor's recorded handoff, cannot yield ACTIVE — the conjunction, all directions.
- **R4** — a STOPPED item accepts no ownership change or (re)activation without an explicit CONTINUATION transition.
- **R5** — an authority write by a non-Governance role, or by Governance without a human-act reference, is refused.
- **R6** — ACTIVE-with-no-covering-grant is representable and queried as NOT AUTHORIZED, without being an error.
- **R7** — a transition on one work item leaves every other work item's record unchanged *(expressed as record-content equality — not byte-equality of a prescribed file)*.

Tests may NOT: prescribe production namespaces or FQCNs beyond the approved placement (§10) · assert storage format beyond the approved mechanism · add states or fields beyond §4/§5 · claim approval status · touch `.claude/runtime/` itself (hermetic temp contexts only).

## 16 · Human approval gate

**Approval means:** §4 state model + §5/§7 transition semantics + §6 authority model + §8 concurrency invariant + §9 recommended mechanism (as amended) + §10 component boundaries + §12 smallest boundary + §15 RED scope become Session 3's implementation boundary — RED first, minimal implementation, GREEN incl. the 65/69 replay criterion, regression (zero change to existing script surfaces), Session-1 verification, operational qualification, Governance closure (G-1).

**Approval does NOT mean:** Increment-2 enforcement · consultation wiring into `inject-context.sh` · retiring any manual convention (incl. same-terminal) · Election work · the premature test file becoming legitimate without rework (Q1).

**Approver-amendable without re-design:** the §9 mechanism details (one-vs-two files, path, format) · token reference format · test home (Q3).

---

**Traceability:** commission `2026-08-14-KOS-AI-ORCH-001-platform-implementation-commission.md` §15 (`7cbe5984`) · accepted rule + G-1…G-4 (`51ba56fd`) · ARB review (`fb9cee29`) · Platform Baseline (component matrix · "governance precedes automation" · R-37/R-38 · zero-new-hooks matrix) · `registry.yaml` (CMP-001…008 · R-17/R-21 registry-first) · `session-changes-logger.sh` + `.gitignore:25` (`.claude/runtime/` precedent) · `inject-context.sh` (consultation candidate, untouched) · F1–F9 (rule §2) · F4/F5 near-miss commits `f6bb5504`/`104f729a`/`09b0b500` · ES-001.1 · ES-004.3 · ES-005.1 (three-concern — §10 placement) · ES-005.4 · R-34 · ADR:OQ-2 (open) · AIP-14 · 65/69 boundary precedent (`2026-08-14-6569-implementation-boundary-proposal.md`) · premature test file `tests/Unit/Platform/WorkflowEngine/KosAiOrch001IncrementOneContractTest.php` (§1.1/Q1, untouched).

---

> # PROPOSED — NOT HUMAN APPROVED
