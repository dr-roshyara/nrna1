# KOS-EXEC-TOPOLOGY-001 — Architecture Decision Proposal

**Date:** 2026-08-15 · **Session 4 (Architecture)** · **Grant:** `G-KOS-TOPO-ARCH` · **Startup check passed** (`identity` → ACTIVE · `authorized` → true · `AST-016` → RESOLVED/operable:true)
**Type:** Architecture Decision **PROPOSAL** — advice, not authorization. **Nothing implemented, no mechanism modified, no PO/ARB decision taken.**

---

## 1 · Executive conclusion

> ### **The two-terminal model is architecturally valid, changes nothing structural, and is already permitted by adopted governance.**
> ### **Recommendation: ADOPT WITH CONDITIONS — as an operating convention and documentation only. It must NOT be encoded as a platform rule or requirement.**

**The decisive finding is that the commission's question is largely already answered — by a rule the PO/ARB adopted on 2026-08-14.**

`A-1.4` (ruling `D-5`) of `KnowledgeOS_Controlled_Session_Orchestration_Proposal.md` — **status ACCEPTED, a governance rule, not a proposal** — states:

> *"Sequential role reassignment within the same process is **allowed** … **R-34 remains binding: a process that implemented a work item may NOT independently verify that same implementation.** Permitted sequences include architecture→implementation and architecture→verification (if it did not implement); **implementation→verification of the same work is prohibited.** **Process/terminal identity must not become the authority mechanism** — terminal/process agnosticism (accepted principle 5) is unchanged."*

Three consequences follow immediately:

1. **Terminal A (Governance → Architecture → Implementation, sequential) is already explicitly permitted.** It needs no decision, no change and no new capability.
2. **The substance of Terminal B is already mandatory** — but as *"the process that implemented may not verify"*, **not** as *"verification lives in a different terminal."* Two terminals are one way to satisfy it, not the requirement.
3. **⚠️ Encoding two-terminal as a *requirement* would contradict adopted governance.** The same document rules that *"the human UI arrangement ('one terminal') is one possible **realization** of INV-ORCH-1 — **acceptable as an interim practice, wrong as the encoded rule**."* A two-terminal *rule* makes terminal identity load-bearing for authority, which `A-1.4` expressly forbids.

**So the model should be adopted as practice and refused as architecture.** Adopting it as practice costs nothing and codifies good habit. Adopting it as a *rule* would import a topology dependency into an authority model that is deliberately topology-free.

**One genuine gap was found** that the adopted rule does not cover, and it needs a PO/ARB decision (§19, `DEC-2`): the rule enumerates `implementation→verification` as prohibited but is **silent on `verification→governance` where that Governance act reviews the very verification the process performed.**

---

## 2 · Problem / architectural question

*Should the two-terminal execution model be adopted, and what, if anything, must change to support it?*

**Terminal A:** Governance · Architecture · Implementation. **Terminal B:** Verification, and potentially Governance when governance work is required.

**Motivation (`E-3`, recorded):** at the corrective increment, implementation (`S3-impl-discovery-corrective`) and verification (`S1-verify-discovery-corrective`) both carried `executionContext: shared-worktree`. Independence there rested on distinct session identities, immutable roles, the recorded handoff, the human START and the verifier's own authorship self-check — **not** on process isolation.

## 3 · Established facts

**FACT 1 — the authority model is topology-free.** Authority derives from `assignment + grant + human START`, recorded in `AST-015`. No transition, precondition or field in the mechanism references a terminal, PID, TTY, host or working directory. *(Source: `workflow-state.php` in full; `AST-016` additionally forbids environment identity as an input — `INV-DISC-2`.)*

**FACT 2 — sequential role reassignment in one process is ADOPTED-PERMITTED** (`A-1.4`/`D-5`), each reassignment being a new `SessionAssignment` per `R8` (role is immutable per assignment).

**FACT 3 — `R-34`'s actual requirement** is *"a process that implemented a work item may not independently verify that same implementation"* — an **implementer/verifier identity** rule on the **same work**. It is **not** a terminal, directory or environment rule. **`implementation→verification` of the same work is prohibited; `architecture→implementation` and `architecture→verification` (if it did not implement) are permitted.**

**FACT 4 — terminal-agnosticism is an accepted principle** (principle 5). *"Same terminal"* is recorded as the **interim operational convention**, and the terminal arrangement is a **realization of `INV-ORCH-1`, explicitly "wrong as the encoded rule."*

**FACT 5 — the real concern is the shared worktree, not the terminal count.** The accepted analysis attributes failure modes F1–F6 and F8 to *shared mutable context* — shared worktree, index and state files without ownership. A single terminal *"serializes mutation as a side effect of serializing attention — it treats the symptom."*

**FACT 6 — concurrent `ACTIVE` is possible; single mutation ownership is the invariant.** Measured: two bootstrap handoffs accepted while no owner existed, then both `START`s accepted → two `ACTIVE` sessions, `mutationOwner` single-valued. `Inv C`/`R1` constrain the **owner** — *"reads concurrent"* is approved.

**FACT 7 — `START` silently transfers mutation ownership.** It carries no precondition on the current owner; the second `START` took ownership while the first session remained `ACTIVE` and would still report itself active.

**FACT 8 — read-only participation is not expressible.** `O-1`/`O-4`; the vocabulary cure is dependency `D-6`. A lane acquires mutation ownership merely by starting.

**FACT 9 — `V-3` is open and separate** (`KOS-ACTIVATION-REPORTING-001`), and `AST-016` must not be wired into `SESSION_START` until it is resolved.

## 4 · Authority model

**ARCHITECTURAL INFERENCE.** Authority is a **four-part conjunction**, and topology appears in none of it:

```
assignment (WHO, immutable role per R8)
  ∧ grant     (WHAT scope, Governance is sole Authority-State writer, G-2)
  ∧ human START (THAT it may begin, G-3: recorded handoff ∧ recorded human act)
  ∧ workflow state (STILL may — ACTIVE, and mutation ownership if mutating)
```

> **A terminal is not a member of this conjunction and must never become one.** Adding topology as a fifth term would create a second, informal authority path — precisely the failure class this programme has been eliminating (`F5`/`F9`: authority read from prose rather than from the record).

**Corollary:** the two-terminal model can only ever be a *heuristic that makes violations less likely*. It can never *confer* or *withhold* authority. Any proposal that relies on it doing so is unsound.

## 5 · Two-terminal operating model — assessment

| Element | Verdict |
|---|---|
| Terminal A hosting Gov → Arch → Impl sequentially | ✅ **Already permitted** (`A-1.4`). No change. |
| Terminal B hosting Verification | ✅ **Permitted, and its substance already required** — but by `R-34`'s identity test, not by terminal separation |
| Terminal B hosting Governance when needed | ✅ Permitted — Governance is an authority function, not a terminal identity. **But see `DEC-2` (§19)** for the one sequence the rule does not cover |
| "The terminal never grants authority" | ✅ **Correct, and already binding** (`A-1.4`, principle 5) |
| Two-terminal as a **platform requirement** | ❌ **Reject** — contradicts principle 5 and `INV-ORCH-1`'s realization/rule distinction |

**OBSERVATION.** The proposal is, in substance, a **re-derivation of the adopted rule**, arrived at independently from lived experience. That is corroboration, and it is worth recording as such. **Its one novel element — making the topology itself normative — is the one element that must not be adopted.**

## 6 · Role / process / terminal independence

**ARCHITECTURAL INFERENCE — four distinct notions, routinely conflated:**

| Notion | Required by adopted governance? |
|---|---|
| **Separate governed assignments** (distinct `SessionAssignment`, immutable role) | ✅ **Always required** (`R8`) |
| **Separate process identity for implementer vs verifier of the same work** | ✅ **Required** (`R-34`, `A-1.4`) |
| **Separate OS terminal** | ❌ **Not required by any adopted rule** |
| **Separate working directory / worktree** | ❌ Not required for the *same* work item — and §12 treats isolated same-item contexts as an *exceptional, explicitly-coordinated* case, because uncoordinated isolation reintroduces `F5` at merge time |

> **The architecture must not impose a stronger requirement than the governing rule establishes.** The evidence does **not** establish that physical terminal separation is necessary. **It establishes that implementer ≠ verifier process identity is necessary — which is a weaker and more precise requirement, and one the two-terminal arrangement happens to satisfy.**

**Correction to `E-3`, offered honestly.** Governance recorded that independence at the corrective increment was *"weaker than the phrase 'independent verification' ordinarily implies."* **Measured against the adopted rule, `R-34` was satisfied** — the verifier confirmed by authorship that another process produced `84100bb0`, which is exactly `R-34`'s test. **`E-3` was not a rule violation.** What `E-3` *does* correctly point at is the **shared-worktree risk class** (`FACT 5`), which is real, is governed by `INV-ORCH-1`, and is **not** cured by counting terminals.

## 7 · Concurrent `ACTIVE` vs mutation ownership

**FACT 6 + FACT 7 + FACT 8 combine into the single most consequential finding for this topology.**

```
ACTIVE            = this assignment may operate          (may be held by several sessions)
mutationOwner     = this assignment may MUTATE           (exactly one, or none — Inv C)
reads             = concurrent, approved
role/assignment   = immutable per R8, orthogonal to both
authorization     = grant + scope, orthogonal to all of the above
```

**ARCHITECTURAL INFERENCE.** A concurrent **assurance** lane is conceptually *read-only*, and *"reads concurrent"* is already approved — so the intent is architecturally sound. **But the record cannot express a read-only participant** (`FACT 8`), so starting the assurance lane makes it the **mutation owner** and, per `FACT 7`, **silently disowns the production lane, which continues to believe it is active.**

> **This is the real obstacle to running two same-work-item lanes concurrently. It is `D-6`, not `Inv C`.** `Inv C` is satisfied throughout — ownership never becomes two-valued. The problem is that ownership *moves without anyone being told*.

**Disposition of `FACT 7` (Q-B), as commissioned — I judge, I do not repair.**

**RECOMMENDATION:** classify unguarded ownership transfer at `START` as an **ACCEPTED CONSEQUENCE of the current design, with a documented operating constraint** — *not* a defect, *and not* silently acceptable.

*Reasoning.* `START` is gated by `G-3` (recorded handoff ∧ recorded human act). Reaching a second `START` therefore requires a **recorded handoff toward the second session** and a **human act**. Both are deliberate, governed, human-authorized events. **Ownership transfer at `START` is arguably the intended meaning of a human starting a session that has been handed to it** — the transfer is not accidental. What is genuinely absent is **notification**: the disowned lane is not told, and `AST-016` cannot tell it either, because ownership loss is invisible in the moment.

**Therefore: not a mechanism defect; a *reporting and convention* gap.** It becomes dangerous only when two same-item lanes run concurrently — which §9 recommends against for exactly this reason. **No mechanism change is recommended.** *(If the PO/ARB later adopts concurrent same-item lanes, this must be revisited and would become a genuine mechanism dependency — recorded as `DEP-2`.)*

## 8 · Architecture-interrupt model

The scenario: Implementation is running; an architectural question arises; Architecture must answer; Implementation resumes.

| Option | Assessment |
|---|---|
| **A · Separate work item** | ✅ **Works today, no mechanism change.** *"Between work items — isolation, free concurrency"* is approved. Cost: the relationship lives in cross-references; the architecture answer is one hop from the implementation record |
| **B · Concurrent lane in the same work item** | ❌ **Does not work safely today.** Starting the architecture lane would take mutation ownership from the implementation lane silently (`FACT 7`), and the record cannot mark the interrupt read-only (`FACT 8`). Would require a `D-6` cure and a change to the **qualified** `AST-015` → **separately authorized mechanism change** |
| **C · Sequential interrupt via handoff *(discovered; not in the commission's list)*** | ✅ **Works today, no mechanism change.** Implementation `HANDOFF` → Architecture `START` → answer → `HANDOFF` back → Implementation resumes under a **new `SessionAssignment`** (`R8`: resuming is a new assignment, not a role change). Uses only existing, proven transitions and keeps a single mutation owner throughout. Cost: heavier ceremony, and each resumption needs a human `START` |
| **D · Not permitted** | ❌ Rejected — architectural questions during implementation are normal and must have a governed path |

**RECOMMENDATION:** **A for substantial architectural questions** (they deserve their own evidence, grant and lifecycle) and **C for short in-flight clarifications**. **Not B**, until `D-6` and `FACT 7` are decided. *(No change required for either A or C.)*

**OBSERVATION worth the PO/ARB's attention:** option **C is what this very work item did** — `KOS-SESSION-DISCOVERY-001` ran architecture → implementation → verification → corrective implementation → corrective verification as a chain of handoffs and new assignments. **The interrupt pattern is already proven in production, not hypothetical.**

## 9 · Options considered

| Option | Architectural benefit | Mechanism impact | Governance complexity | Verification impact | Risk |
|---|---|---|---|---|---|
| **Two-terminal as an operating convention** *(recommended)* | Makes `R-34` violations physically awkward; matches lived practice | **None** | **Low** — documentation | Strengthens habit; changes no requirement | **Low.** Main risk is *drift into being treated as a rule* — hence condition `C-1` |
| **Two-terminal as a platform requirement** | Marginal over the convention | **None technically** — but contradicts principle 5 and `A-1.4` | **High** — introduces a topology term into the authority model | Would imply authority follows topology | 🔴 **High — reject.** Creates a second, informal authority path |
| **A · Separate work item per lane** | Clean isolation; approved concurrency between items | **None** | Medium — cross-references to maintain | Neutral | Low |
| **B · Concurrent assignments within one item** | Genuine parallelism; one record | 🔴 **Reopens QUALIFIED `AST-015`**; needs `D-6` | High | Assurance lane could silently own mutation | 🔴 High |
| **C · Sequential interrupt via handoff** *(discovered)* | Zero new concepts; already proven | **None** | Low–medium (ceremony per hop) | Neutral | Low |

*No numerical scores are offered; the evidence does not support them.*

## 10 · Impact on `AST-015`

**NONE — and none should be sought.** The recommendation requires no change to `workflow-state.php`. `AST-015` is operationally qualified, and `KOS-AI-ORCH-001` reserves mechanism *evolution* to separate authorization.

**Recorded dependencies, not designs:**
- **`DEP-1` (`D-6`, pre-existing):** read-only participation is not expressible. **Blocks option B only.** Not required by the recommendation.
- **`DEP-2` (conditional):** if concurrent same-item lanes are ever adopted, unguarded ownership transfer (`FACT 7`) must be revisited — notification or a precondition would then be needed. **Not required by the recommendation.**

## 11 · Impact on `AST-016`

**NONE.** No redesign, no new field, no startup wiring. **The `SESSION_START` prohibition remains binding while `V-3` is unresolved**, and this proposal does not weaken it.

**OBSERVATION relevant to the topology decision only:** `AST-016` is the instrument by which a process consults the record before acting in each role — the mechanism that makes `FACT 1` operational. **A topology that relies on habit needs that instrument to be trustworthy**, which is an argument for prioritising `KOS-ACTIVATION-REPORTING-001` — **but not an argument for changing anything here.**

## 12 · Impact on Governance

- **Terminal-agnostic**, as already ruled. Governance may act from either terminal; location confers nothing.
- Remains **sole writer of the Authority State** (`G-2`) — a role rule, unaffected by topology.
- **One new duty if the convention is adopted:** record `executionContext` meaningfully enough to evidence `R-34` (see `C-3`). Today both lanes recorded the identical string `shared-worktree`, which **cannot** distinguish implementer from verifier process — the `R-34` check had to fall back on commit authorship.

## 13 · Impact on Implementation

**None.** Implementation continues to derive authority from assignment, grant and START. It may share a terminal with Governance and Architecture. **It may not subsequently verify its own work — already binding, unchanged.**

## 14 · Impact on Verification

**None to the requirement; a strengthening of the practice.** `R-34` already prohibits implementer-verifies-own-work. The two-terminal convention makes accidental violation harder by making the verifier a physically distinct process by default. **It does not raise the standard, and must not be described as doing so.**

## 15 · Impact on PO/ARB

- Continues to hold every `START` act and every acceptance. **Unchanged.**
- **Two decisions are requested** (§19). Neither is technical.
- **A caution:** adopting the model as a *rule* would create an expectation that topology enforces independence, which would weaken the actual control — the recorded assignment and the `R-34` identity test. **The convention should be described as a habit that makes the rule easier to keep, never as the rule itself.**

## 16 · Risks

| Risk | Severity | Mitigation |
|---|---|---|
| **Convention hardens into a perceived rule**, and sessions start inferring authority from which terminal they are in | 🔴 **Highest** | Condition `C-1`: state in the same breath that the terminal confers nothing |
| `executionContext` remains too coarse to evidence `R-34` | 🟠 Medium | Condition `C-3` — documentation only |
| Two same-item lanes started concurrently → silent ownership transfer | 🟠 Medium | Recommend against B; `FACT 7` documented as an operating constraint |
| `verification→governance` self-review sequence | 🟠 Medium | **`DEC-2` — PO/ARB decision required; deliberately not invented here** |
| Shared worktree remains the real exposure regardless of terminal count | 🟡 Low–Medium | Already governed by `INV-ORCH-1`; unchanged by this decision |

## 17 · Dependencies

`DEP-1` `D-6` read-only participation *(blocks option B only)* · `DEP-2` ownership-transfer notification *(conditional on B)* · `V-3` / `KOS-ACTIVATION-REPORTING-001` *(independent; affects trust in the startup instrument, not this decision)* · `E-1`, `O-CLOSURE-VOCAB`, bootstrap gap *(unrelated; not folded in)*.

**None of these blocks the recommendation.**

## 18 · Recommendation

> ## **ADOPT WITH CONDITIONS**
> **Adopt the two-terminal execution model as an OPERATING CONVENTION, recorded as documentation. Do NOT encode it as a platform rule, requirement or capability.**

| # | Condition | Classification |
|---|---|---|
| **C-1** | State explicitly, wherever the convention is recorded, that **the terminal confers no authority** and that authority remains `assignment ∧ grant ∧ human START ∧ workflow state`. The convention is a habit that makes `R-34` easier to keep — **never the control itself** | **Documentation** |
| **C-2** | Record `R-34`'s operative test in the convention **verbatim** — *a process that implemented a work item may not independently verify that same implementation* — so the convention cannot drift into "verification must be in another terminal" | **Documentation** |
| **C-3** | Make `executionContext` distinguish processes well enough to evidence `R-34` (e.g. a per-process identifier rather than the shared literal `shared-worktree`). **A convention for what Governance writes into an existing field — no schema or mechanism change** | **Governance rule (documentation-level)** |
| **C-4** | Architecture interrupts use **option A or C**. **Option B is not adopted** and would require a separately authorized mechanism change | **Governance rule** |

**Classification of the recommendation as a whole: NO PLATFORM CHANGE. Documentation and one governance convention. No new capability. No `AST-015` change. No `AST-016` change.**

## 19 · Decisions requiring PO/ARB

**`DEC-1` — Adopt the two-terminal model as an operating convention with conditions `C-1`–`C-4`?**
*Architecture recommends **yes**. This is a decision about practice, not architecture: the architecture is unchanged either way.*

**`DEC-2` — The uncovered sequence. 🔴 Genuinely open.**
`A-1.4` prohibits `implementation→verification` of the same work and permits `architecture→implementation` and `architecture→verification`. **It is silent on `verification→governance` where the Governance act reviews the very verification that process performed.**

> This is not hypothetical: Governance reviews verification evidence as a matter of course, and the proposed Terminal B hosts **both** verification and Governance. **Under the adopted text this is permitted.** Whether it *should* be is a question about `R-34`'s intent — does *"engineering never accepts its own work"* extend to *"the reviewer of verification evidence should not be the process that produced it"*?
>
> **Architecture deliberately does not answer this.** The commission forbids inventing a stronger requirement than the governing rule establishes, and this would be exactly that. **Recorded as an open question for the PO/ARB**, with the observation that adopting Terminal B as described makes the sequence *more* likely, not less.

## 20 · Explicitly out of scope

Not touched, not decided, not folded in: implementation of anything · `workflow-state.php` · `session-resolve.php` · tests · hooks · `SESSION_START` wiring · locks/leases · concurrent-session semantics · `V-3` / `KOS-ACTIVATION-REPORTING-001` · `E-1` · `O-CLOSURE-VOCAB` · the successor-registration bootstrap gap · Election code · `KOS-SESSION-DISCOVERY-001` (not reopened) · any implementation or verification assignment · any grant · the PO/ARB decision itself.

**Architecture does not self-certify this proposal.** It returns to Governance / PO/ARB for disposition.

---

## Traceability

`A-1.4`/`D-5` and accepted principle 5, `INV-ORCH-1`, §7 realization-vs-rule ruling, §8 concurrency options, §12 isolated-context exception — `docs/architecture/governance/KnowledgeOS_Controlled_Session_Orchestration_Proposal.md` (**status: ACCEPTED WITH AMENDMENTS G-1–G-4, PO/ARB 2026-08-14**) · `R8` role immutability · `Inv C`/`R1` single mutation owner (`2026-08-14-KOS-AI-ORCH-001-implementation-boundary-proposal.md`) · `G-2`/`G-3` · `INV-DISC-2` (no environment identity) · measured concurrency and ownership-transfer probes, and `E-3` (`2026-08-15-execution-topology-two-terminal-governed-workflow-intake.md`, `ef19e21c`) · `O-1`/`O-4`/`D-6` · `V-3` (`KOS-ACTIVATION-REPORTING-001`) · commission `G-KOS-TOPO-ARCH` (`0f203192`) · reconciliation (`1fe05a4e`) · START seq 3 (`12c9c297`) · interrupt-pattern precedent: `KOS-SESSION-DISCOVERY-001` seq 1–18
