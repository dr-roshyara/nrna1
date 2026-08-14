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
| 2 | **Decision A** — voting-time entitlement ownership (§5, §5a) | architecture | **PO BUSINESS RULE ESTABLISHED (2026-08-13) · ownership SUPPORTED/ASSESSED (Election BC) · formal acceptance OUTSTANDING (AD-2)** |
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

### 5a · Decision A reconciliation (dated addendum, 2026-08-13 — PO business rule received)

**1 · PO business rule (verbatim):** *"Once a person is a voter for an election, that person can vote for that election within the voting period unless it is suspended before the voting starts."* — **PO BUSINESS RULE ESTABLISHED.** Suspension clause read exactly as given: *suspension **before voting starts** prevents voting.* **Not broadened** — what suspension *during* voting means is NOT ruled here and is not invented here.

**2 · Precise business invariant:** an admitted voter of election E belongs to E's voting population and may exercise the vote during E's voting period, subject to E's constitutional/lifecycle rules; **ambient organisation/tenant context must not revoke that entitlement.** Every fact the invariant references — membership-in-E, E's voting period, E's suspension state — is Election-context data; no organisation/tenant term appears in the rule.

**3 · Admission ≠ voting-time entitlement (preserved):** *"May this person become a voter for this election?"* (admission — Contexts/Elections chain, `DB::table()`, measured unaffected by P6) vs *"may this already-admitted voter exercise the vote in THIS election now?"* (**this decision; where 65/69 live**). Not merged with Full Membership, organisation membership, voter-source strategy, or ambient tenant resolution. **No generic Eligibility BC is created.**

**4 · P6 evidence (MEASURED, cited, not re-run):** both defects reproduced on the voting-time predicate; ambient tenant context is the confirmed mechanism; tenant-blind cache replays the wrong denial; correct context + cleared cache restores the correct answer; admission-time unimplicated; HTTP replay not re-run; testing DB, throwaway rows, no changes. **The measured behaviour directly violates the now-established PO rule** — the entitlement's answer today depends on which page the voter visited last. *(Evidence of defect against the rule — still not ownership authority, and no repair location is inferred from `User.php` or `BelongsToTenant`.)*

**5 · Proposed bounded-context owner:** **the Election bounded context** — per the standing Decision A wording (unchanged): *"Voting-time voter eligibility is owned by the Election context; its resolution derives organisational scope from the election itself; ambient organisation context is a forbidden dependency for this resolution."*

**6 · Accepted architecture authority, assessed (accepted-first; PROPOSED artifacts not used as authority):**

| Accepted evidence | Bearing |
|---|---|
| **PO rule above** + Model B / `PBDIGIT-68` (durable election entitlement) + `EM-GOV-001` (*the election governs exercisability*) | the invariant is stated wholly in Election ubiquitous language — SUPPORTS |
| **`ADR_20260807_1500` (ACCEPTED)** — Election lifecycle/state SSOT = the Election engine/façade | the invariant's *"subject to constitutional/lifecycle rules"* clause is already owned by the Election context by accepted decision — SUPPORTS |
| **ADR-001 (Accepted)** — constitutional capability sovereignty (`ElectionConstitution` home) | voting period and suspension are constitutional facts of E — SUPPORTS |
| **`ADR-002-verified-eligible-authorized` (Accepted)** — assigns "Eligible" to an "Eligibility Context" | **read against its own definition**: its "Eligible" = *"meets requirements for a specific process (membership status, fees, timing)"* — an **admission-style evaluation**. Under the PO rule, voting-time entitlement is **not** a fresh requirements evaluation: admission already happened; what remains is honoring a durable election-scoped entitlement subject to E's lifecycle. **Read this way, ADR-002 does not compete** — but that reading is Session 2's assessment, and the "Eligibility Context" phrase deserves explicit disposition in the ruling. *(The named context never materialised beyond a 0-caller policy — measured.)* |
| Dependency direction (measured): `Contexts/Elections → Domain/Election`, one-way; 1 election : 1 organisation at schema level; three production sites derive context from the election | SUPPORTS — the election can determine its own organisational scope |

**7 · Remaining uncertainty, named — certainty not manufactured:** **no accepted ADR names an owner for voting-time entitlement** (`AD-2` never ratified), and ADR-002's "Eligibility Context" phrase, while assessed non-competing, has not been formally disposed. **The missing authority is one explicit ARB/PO acceptance of the Decision A wording, including that disposition.**

**8 · Decision A status:** `PO BUSINESS RULE ESTABLISHED · ownership hypothesis SUPPORTED — assessed as ELECTION BOUNDED-CONTEXT RESPONSIBILITY · formal ARB/PO acceptance of the ownership wording OUTSTANDING (AD-2)`. The business rule and the architecture ownership decision are **not conflated**: the first is decided; the second is assessed and awaits its signature.

**9 · Separation from Decision B (explicit):** Decision A settles *ownership*; **Decision B (instances vs `BelongsToTenant`-family audit) remains OPEN** unless separately ruled — P6 proves two instances, not the blast radius. Nothing here selects a repair scope or location.

**10 · NO implementation grant exists.** This reconciliation authorizes nothing. Sequence: PO business rule ✅ → ownership acceptance (AD-2 signature) ⬜ → Decision B ⬜ → explicit grant ⬜ → Session 3 six-question reconciliation → smallest TDD slice → PO approval of the implementation boundary → implementation. **Session 3 remains STOPPED.**

### 5b · Election-start readiness — a SEPARATE invariant (recorded, not conflated with Decision A)

**PO rule:** *"To start voting, there must be at least 1 approved candidate AND at least 1 eligible/admitted voter."* — **PO BUSINESS RULE ESTABLISHED.** Two categories kept apart: **election readiness** (may THIS election enter Voting Active? — `EM-VOT-002` belongs here) vs **voter entitlement** (may THIS admitted voter vote? — 65/69 belong here). They interact; they are not the same decision.

| Half of the rule | State |
|---|---|
| ≥ 1 approved candidate | `EM-VOT-002` — ADOPTED · IMPLEMENTED · VERIFIED · CLOSED |
| ≥ 1 eligible/admitted voter **at voting start** | **PO rule established · NOT IMPLEMENTED at the voting boundary.** `has_voters` already exists as constitutional vocabulary — but as a `complete_administration` precondition (an earlier gate). An upstream guard ≠ a boundary invariant: the EM-VOT-002 lesson (the computed path bypasses guarded transitions) applies symmetrically. **Recording as a Manifesto rule (stable `EM-*` ID) + Constitution expression + any implementation each await their own steps: PO one-line adoption confirmation → expression-home decision → explicit grant. Nothing authorized here.** |

**`EM-OPEN-021` stays separate and unresolved:** the readiness rule is *evidence relevant to* lifecycle readiness; it does **not** answer the recovery semantics of *nomination-completed + window-open + zero candidates* — and its voter-half raises the analogous zero-voter question, whose semantics are **also not invented here**. No HOLD, cancellation, postponement, fallback, new state, or extension is proposed.

### 5c · FINAL governance reconciliation — Decision A + election-start rule (dated 2026-08-13; normalization, not investigation)

**1 · The invariant, in final DDD form (two concepts, never merged):**

> **Admission (separate concept, not this decision):** *may this person become a voter for election E?* — evaluated by the admission chain; measured unaffected by P6.
>
> **Voting-time entitlement (INV-A, this decision):** *a person admitted as a voter of election E holds a durable, election-scoped entitlement to cast a vote in E while E's voting period is open, subject only to E's constitutional/lifecycle rules. The entitlement's answer is a function of E and the voter's admitted status in E — never of ambient organisation/tenant context at exercise time. Suspension of E before voting starts prevents exercise.* *(Suspension DURING voting: not ruled, not invented.)*

**2 · Ownership conclusion:**

> ## **"Voting-time voter entitlement is owned by the ELECTION bounded context."**

Supported by accepted authority (§5a table: `ADR_20260807_1500` · ADR-001 · Model B/`PBDIGIT-68` · `EM-GOV-001`) and by measured evidence (P6; dependency direction; 1 election : 1 organisation). Not inferred from `User.php`, `BelongsToTenant`, middleware, DI bindings, or folders.

**`ADR-002-verified-eligible-authorized` (Accepted), classified explicitly — not silently reinterpreted: D — REQUIRES FORMAL CLARIFICATION.** Its own text is ambiguous between the two moments (C): "Eligible" is *"meets requirements for a specific process"*, scoped *"different for Voting vs. Candidacy vs. Delegation"* — which contemplates a voting-process eligibility — while its enabling examples (*membership status, fees, timing*) describe an admission-style requirements evaluation. It predates Model B and the PO rule. Under the PO rule, nothing remains to *evaluate* at voting time except E's own lifecycle facts; but that reading is a clarification the ARB must make, not one Session 2 may assume.

**Sufficiency verdict:** the accepted architecture **supports but does not fully establish** the ownership conclusion. **Exactly two sentences require formal ARB/PO acceptance (this is the entire outstanding act):**

> **(i)** *"Voting-time voter entitlement is owned by the Election context; its resolution derives organisational scope from the election itself; ambient organisation context is a forbidden dependency for this resolution."*
> **(ii)** *"ADR-002's 'Eligibility Context' responsibility is clarified to govern admission-time (process-requirements) evaluation; the voting-time entitlement decision of clause (i) is Election-context-owned and is not an ADR-002 'Eligible' evaluation."*

No generic Eligibility bounded context is created under any disposition.

**3 · Election-start rule, voter half — determined, not implemented:**

| Question | Finding |
|---|---|
| Does an adopted Manifesto rule already express it? | **NO** — checked: `EM-VOT-001`/`EM-VOT-002` are candidate-only; no adopted rule requires ≥ 1 admitted voter at voting start |
| Does a stable `EM-*` ID exist? | **NO** — **a new Manifesto rule/adoption is required — exactly that**; the ID is assigned at adoption, **not invented here** |
| Where should the authoritative constitutional expression live? | by accepted pattern (ADR-001 + the recorded `EM-VOT-002` precedent — *"`ElectionConstitution` is the authoritative implementation home for this precondition"*): the `open_voting` preconditions, **with the both-paths lesson applying symmetrically** (the computed path must also be covered) — an identified home, NOT an implementation instruction |
| What evidence exists today? | `has_voters` exists as a `complete_administration` precondition (upstream gate only); **neither voting-boundary path checks voters** — guard preconditions: `voting_window_defined · timezone_set · has_approved_candidates`; engine rule: window + candidates (`ElectionLifecycleEngineImpl:105`) |

**4 · Kept separate:** `EM-OPEN-021` (zero-candidate recovery semantics — undecided; nothing chosen) · the zero-voter analog (distinguished, unanswered) · **Decision B** (OPEN: instances vs `BelongsToTenant`-family audit; P6 proves the defects, not the scope; not chosen, not authorized).

**5 · Clean decision register (three dimensions, never collapsed):**

| Item | Business status | Architecture status | Implementation authorization |
|---|---|---|---|
| `EM-VOT-002` | **decided** | **settled** (Constitution + both paths) | **completed** (grant consumed; verified; closed) |
| **Voting-time entitlement** (Decision A) | **decided** (PO rule, verbatim §5a) | **Election-BC ownership — assessed & supported; formal acceptance of sentences (i)+(ii) above REMAINS OUTSTANDING** | **none** |
| **Election-start, voter half** | **decided** (PO rule §5b) | **expression home IDENTIFIED** (Constitution `open_voting`, both paths); Manifesto adoption + ID pending | **none** |
| `PBDIGIT-65`/`69` | **defect measured** (P6) — violates the decided rule | **repair scope OPEN** (follows A + B) | **NOT AUTHORIZED** |
| **Decision B** | open | open | none |
| `EM-OPEN-021` | **open** — domain decision (evidence complete for deciding) | n/a until decided | none |

**Boundary of this reconciliation:** business rule and DDD ownership clarified — **implementation authorization untouched**: no `app/` or `tests/` edit, no Constitution change, no policy, no cache-key or `BelongsToTenant` change, no `User.php` fix, no repair scope selected, no tickets created, Session 3 not reopened.

### 5d · ACCEPTANCE RECORD (2026-08-13 — two performative PO rulings, verbatim)

> **A —** *"I accept sentences (i) and (ii) as written."*
> **B —** *"Adopted: an election may enter Voting Active only when it has at least one approved candidate and at least one admitted voter."*

**Consequences registered (and nothing more):**

| Act | Effect |
|---|---|
| **A** | **Decision A is CLOSED (`AD-2` resolved):** voting-time voter entitlement is **owned by the Election bounded context**; ambient organisation/tenant context is a **forbidden dependency** for its resolution; ADR-002's "Eligibility Context" is **clarified to admission-time evaluation** — a dated status annotation now sits on `docs/adr/ADR-002-verified-eligible-authorized.md` (decision text untouched). *(Reading clarified by PO 2026-08-14, recorded in the disposition package §2a: forbidden as the SOURCE of scope derivation — the election defines which organisation must match; the active context participates as an explicit correspondence COMPARAND. Matching context permits; non-matching denies; cache never transports cross-context answers.)* |
| **B** | Recorded in the Manifesto as **`EM-VOT-003`** (§4a + traceability), ID assigned by the established process (EM-VOT-002 precedent). Voter half new; **`ElectionConstitution` identified as expression home, both paths**; `EM-OPEN-021` and the zero-voter analog untouched |
| **Neither** | **creates any implementation authorization.** Grants remain NONE; `PBDIGIT-65`/`69` remain NOT AUTHORIZED; Session 3 remains STOPPED |

**Register after A + B:** the §5c three-dimension table rows update to — *Voting-time entitlement:* business **decided** · architecture **ACCEPTED (Election BC, 2026-08-13)** · authorization **none**. *Election-start voter half:* business **decided** · architecture **adopted as `EM-VOT-003`, home identified** · authorization **none**.

**Remaining before any grant:** **Decision B** (65/69 repair scope — next per PO sequencing) · **`EM-OPEN-021`** (separate lifecycle-recovery decision) · then an explicit bounded implementation grant.

### 5e · DECISION B RULING (2026-08-13 — performative PO ruling, verbatim)

> **"Decision B — audit the `BelongsToTenant` family first, then repair."**

**Decision B is DECIDED: Scope 2 — audit-first.** *(Interpretation note, per register-and-report practice: the ruling arrived as the standalone opening line of a PO message whose remainder is Principal-Architect review text; registered as performed on that reading, flagged for correction if misread.)*

**Consequences registered:**

| What is now decided | What is NOT decided or created |
|---|---|
| The 65/69 repair scope is the **`BelongsToTenant` / ambient-context / tenant-blind-cache family**, entered through an **audit deliverable BEFORE any repair** (the §6 Scope-2 prerequisite; Decision A prerequisite already satisfied — closed 2026-08-13) | **The audit is not yet commissioned:** no executor named (Session 1 / other), no deliverable definition, no schedule — that tasking is a separate PO act |
| Repair follows the audit — *"then repair"* records intent that repair work comes after audit results | **No implementation grant exists** — neither for the audit-driven repairs nor for 65/69 directly; Session 3 remains STOPPED |
| Register row: *Decision B — business **decided** (Scope 2) · architecture **audit-first, family-scoped** · authorization **none*** | The audit's findings do not self-authorize fixes: **audit → PO/ARB disposition → explicit bounded grant → Session 3** |

**Sequencing clarification also registered (PO):** *"two decisions stand between here and any grant"* is **governance sequencing, not a universal gate** — a future grant may explicitly authorize a bounded slice wherever the necessary business/architecture authority already exists; for **65/69 specifically**, Decision B (now decided) had to come first because it determines repair scope. `EM-OPEN-021` stays independent and must not block or contaminate the entitlement track.

**Next single act available to the PO:** commission the audit — name the executor, the deliverable (enumerate every `BelongsToTenant` consumer; classify each against the accepted Decision-A rule; measure blast radius; **no fixes**), and the stop condition. Session 2 can draft that audit charter on request; it is not self-commissioned.

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
