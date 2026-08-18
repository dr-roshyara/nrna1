# `EM-DOM-001` — **Phase 2A · Act-B Domain Design / Decision Map**

**Status: 🟡 ANALYSIS + RED PREPARATION ONLY. No production code. No test committed. Nothing implemented.**
**Lane:** fresh Domain lane · **Phase:** 2A · **Date:** 2026-08-18
**Controlling input:** the Phase-2A addendum to `2026-08-18-EM-DOM-001-domain-lane-briefing.md` (controlling where it differs from the Phase-1 briefing)
**Placement derived, not chosen:** `docs/publicdigit` (exit 0); `architecture/` matches the sibling Phase-1 map.

> ## ⚠️ PROVENANCE AND AUTHORITY NOTE — added 2026-08-18 by the governance recording session
>
> **Three layers exist and must NOT be conflated:**
>
> | | Artifact | Author | What it carries |
> |---|---|---|---|
> | **1** | the Phase-2A **addendum** to the lane briefing | governance | **constraints only — NO model, no contract, no `H-1`** |
> | **2** | **this design map** | **the fresh Domain lane** | `ofElection()` · `Port/RecordedOperationalStatus` · `H-1` · `V-1…V-12` |
> | **3** | the correction/registration pass | governance recording | the headline tightening · the `V-11` fix to the briefing · `PBDIGIT-72` for `V-8` |
>
> ⛔ **A LATER SESSION HAVING REVIEWED, QUOTED OR EDITED THIS DOCUMENT CONFERS NO AUTHORITY ON IT.** **In particular the §8 headline was tightened at the PO/ARB's explicit request — that edit is a WORDING change and is NOT an acceptance, an endorsement, or an Architecture confirmation of anything in §§1–7.** **Review is not authority. Editing is not approval.**
>
> **Both gates remain UNSATISFIED:** ⬜ **Architecture confirmation** of `Port/RecordedOperationalStatus` as boundary-neutral under `G-2a`/`C-2` *(without deciding `BND-3`)* · ⬜ **independent verification** of this map against the frozen core and the governing decisions *(`EP-02`/`R-34`; §0.1's disclosure makes it non-optional)*.
>
> **Therefore, as of this note: no contract exists · no `H-1` RED exists · no implementation is authorized · `Port/` still holds its seven pre-existing files · `Domain/OperatingCore` is byte-identical to `1f4b4c5f`.** ⚠️ **And `PBDIGIT-72` must not be read as *"Act B fixes `EM-GOV-063`"*** — the Act-B contract alone does **not** make `ExpiryConsequence` reachable; that also needs a caller and act C, neither authorized.

---

## ⚠️ 0 · Two disclosures before any analysis

### 0.1 Lane independence — a grey zone the PO/ARB must resolve

**Obligation 4 (strengthened) bars *"the sessions that produced the decisions, the ADRs, the architecture reviews and this record"*.**

| Bar | This lane |
|---|---|
| produced D1–D4 | ✅ **No** |
| ran the Rule-8 gate | ✅ **No** |
| prepared the PO/ARB dossier / decision surface | ✅ **No** |
| prepared ADR-1 / ADR-2 | ✅ **No** |
| produced `EM-DOM-001`'s architecture review or referral | ✅ **No** |
| ⚠️ **performed an architecture review OF ADR-1/ADR-2** | 🔴 **YES** — this process performed the independent final gate on both ADRs earlier in its own history |

**Consequence, stated rather than argued away:** this lane holds a formed reading of ADR-1 §6 and ADR-2 §6 — the exact texts Phase 2A must interpret into a model — which is the risk Obligation 4's rationale names (*"the session that made a decision is the worst-placed one to discover what the decision obviously implies"*). ⛔ **The PO/ARB should decide whether this lane stands.** **Mitigation applied throughout:** every material claim below is re-verified against primary code/test evidence and marked `Observed`; where a prior artifact is wrong, §1 says so. **This document must be verified by a process that is neither this lane nor any prior EM-DOM-001 session.**

### 0.2 Authority — G-7 is discharged by a human act, not by this lane

The post-decision gate found **G-7: *"Who may execute Act B? No one today"*** — lane not designated, standing STOP in force. **This lane's authority is the PO/ARB's designation and START delivered 2026-08-18.** ⛔ **This lane does not record its own authorization.** **Governance should record the designation and START**; until it does, the record still reads `NOT STARTED`, and that discrepancy is a records-integrity item, not a licence.

---

## 1 · VERIFIED DOMAIN FACTS — re-derived, not inherited

Every row was checked against the repository. **Nothing here is accepted on a prior report's authority.**

| # | Claim | Verdict | Primary evidence |
|---|---|---|---|
| **V-1** | `ElectionOperationalStatus` is semantically complete and needs no change (gate `G-1`) | ✅ **CONFIRMED** | 7 public members exactly: `operative`, `operativeHalted`, `becameInoperative`, `restored`, `condition`, `isHalted`, `haltedAtGate` |
| **V-2** | It **already represents the `w8` case** | ✅ **CONFIRMED, and this is the pivot** | `operative()` → `becameInoperative()` → `restored()` yields `condition = Operative`, `haltedAtGate = null`, `isHalted() === false`. **A restoration with no prior halt, hence no resumption target, is already representable — and `isHalted()` is the named discriminator, so the nullable field is not a sentinel** |
| **V-3** | It also represents the halt path intact | ✅ **CONFIRMED** | `operativeHalted($h)` → `becameInoperative()` → `restored()` retains `$h` — `EM-GOV-059(b)/(c)` |
| **V-4** | `DEP-5b` is a **producer/reachability** gap, not a concept gap | ✅ **CONFIRMED** | `ElectionOperationalStatus` is referenced **only** by its own file, one domain unit test, and a structural-guard regex. **No production code constructs, stores or retrieves one** |
| **V-5** | Nothing in `app/` produces a `HaltedAtGate` | ✅ **CONFIRMED** | its only production references are *parameter/field types* (`ElectionOperationalStatus`, P-7, `ExpiryConsequence`); `FillCommitteeSeatHandler` names it only in a docblock stating it deliberately does **not** construct one |
| **V-6** | P-7's only call site is a domain unit test | ✅ **CONFIRMED** | `ConditionSemanticsTest.php:175` |
| **V-7** | `G-2a` — a fourth `…Repository` there would assert aggregate standing | ✅ **CONFIRMED, and stronger than stated** | all three files read *"contract for AG-1/AG-2/AG-3"*, and `ElectionCommitteeRepository:11` says outright ***"repositories exist for aggregates only — repo Rule 9."*** **The prohibition is a repo RULE, not merely a docblock convention** |
| **V-8** | ⭐ **The producer gap starves TWO domain policies, not one** | ✅ **NEW — not stated in any prior artifact** | `ExpiryConsequence::onHaltedRecoveryExpiry()` also requires a non-null `HaltedAtGate`. So the unreachable chain also disables **`EM-GOV-063`'s terminal consequence** — *"the one genuinely new recording obligation"*. **An adopted business rule, not just a convenience, is unreachable** |
| **V-9** | ⭐ **The "authorized six-port universe" is the Application's INJECTED COLLABORATOR SET — not the contents of `Port/`** | ✅ **NEW — resolves a live ambiguity** | `Port/` holds **4 interfaces + 3 value/enum types**, not six ports. The union of the three handlers' constructors is **exactly six**: `ElectionCommitteeRepository`, `AcceptanceGateDecisionRepository`, `RecoveryProcessRepository`, `ServicePolicySnapshot`, `ProtocolAppend`, `InstantSource` |
| **V-10** | ⭐ **The frozen code already contains the precedent Act B needs** | ✅ **NEW** | `OrganisationalAppointmentAuthority` is a **domain-owned interface in `Port/`, DECLARED and DELIBERATELY UNIMPLEMENTED**, wired nowhere, **not** among the six, and its unimplemented state is **enforced by two structural tests**. ⚠️ Claimed at exactly one strength: *a domain-owned interface may exist in `Port/`, unimplemented and unwired, without asserting aggregate standing.* It is a **driven port to an external actor** and is **not** semantically like the Act-B contract |
| **V-11** | 🔴 **A Phase-1 briefing statement is FACTUALLY WRONG and is the single most likely thing to be mis-modelled** | 🔴 **CORRECTED** | The Phase-1 §"three facts" item 3 says `w8` restoration follows *"with **no `RecoveryProcess`** ever having existed."* **The fixture contradicts it:** `FillCommitteeSeatHandlerRedTest::test_w8_…` seeds `RecoveryProcess(PeriodKind::CommitteeRestoration, 20)`. **`w8` lacks the HALT, not the `RecoveryProcess`** — which is precisely what **D2** records (*"Restoration without a prior halt … has a known causal origin but has no resumption target"*). ⇒ **The addendum and D2 are right; the Phase-1 sentence is stale and must not be built on.** *(A restoration with no `RecoveryProcess` of either kind is structurally reachable but pinned by no test — unpinned, never an accepted case.)* |
| **V-12** | `W-4` structural guard constrains any future consumption | ✅ **CONFIRMED** | `StructuralApplicationGuardsRedTest:235` forbids **any Application class from holding** `ElectionOperationalStatus` / `HaltedAtGate` / `OperationalCondition` as a property — *"returned, never stored."* **So the contract must be retrieve-and-use, never retrieve-and-hold** |

---

## 2 · ACT-B DOMAIN MEANING

### A · Scope and authorization

| ✅ Act B authorizes | ⛔ Act B does not authorize |
|---|---|
| **creation and definition** of a Domain-owned **identity/retrieval** contract for the operational overlay | modifying an existing repository interface *(act A)* |
| its domain vocabulary, naming and placement | persistence / adapter — the **save** half of `R-1` *(act C)* |
| naming what must be retrievable | Application call sites / consumption *(act D)* |
| a **RED test** for the missing invariant | a new identity/persistence **model** *(act E = `BND-3`)* |
| | choosing **`R-1`'s final form** *(Appendix Q item 2 — explicitly withheld)* |
| | `BND-1` · `BND-3` · `ElectionRestored`'s representation · GREEN-5 · any mechanism beyond the contract |

> ⚠️ **`R-1` as written in the Phase-1 map bundles three things — identity, retrieval, and *"loaded **and saved**"*. Act B reaches the RETRIEVAL contract only. The save half is act C, the standing question is `BND-3`, and `R-1`'s final form is withheld by Appendix Q.** **Act B is therefore strictly narrower than `R-1`.**

### B · The approved semantic meaning the contract must expose

> ## **One question, and nothing else: *"What is this election's RECORDED operational status?"***

**Three things that meaning already includes, because `ElectionOperationalStatus` is frozen and complete (V-1/V-2/V-3):**

1. the **overlay condition** — `Operative` | `Inoperative`, mutually exclusive recorded conditions (`EM-GOV-062`);
2. **whether a halt is recorded, and at which gate** — retained across becoming Inoperative and across restoration (`EM-GOV-059(b)/(c)`);
3. therefore **whether a resumption target exists at all** — `isHalted()`, with `haltedAtGate()` feeding **P-7 unchanged**.

**What the meaning deliberately excludes — the `cause vs return target` boundary (ADR-2 (a), permanent):**

```
        WHY restoration is permitted                WHERE restoration resumes
        ────────────────────────────                ─────────────────────────
        nothing in the model answers this           P-7 answers this, from a
        (DEP-6 · R-2 · not authorized)              recorded HaltedAtGate
                    │                                          │
                    └────────── MUST NOT BE COLLAPSED ─────────┘
```

⛔ **The Act-B contract supplies the *input to the second question only*.** It states no permission verdict, and it must not be read as one. **D2 is what makes this safe: `w8` has a known causal origin and no resumption target — so a missing target is a legitimate answer, not a gap to be filled.**

---

## 3 · OWNERSHIP MAP

### C · Owner of each affected concept, with evidence — owner distinguished from consumer

| Concept | **Owner** | Evidence of ownership *(not "a class exists")* | Consumers |
|---|---|---|---|
| **operational overlay condition** (`Operative`/`Inoperative`) | **OperatingCore** | it **defines the meaning**: `OperationalCondition`'s own docblock states the overlay semantics and derives `EM-GOV-062`'s mutual exclusion | AG-3 clocks (indirectly), `ExpiryConsequence` |
| **the recorded halt fact** (`HaltedAtGate`) | **OperatingCore** | it defines *"progression is HALTED at a gate condition"* and explicitly disclaims re-declaring transitions | P-7, `ExpiryConsequence`, `ElectionOperationalStatus` |
| **the combined operational status** | **OperatingCore** | `ElectionOperationalStatus` defines the `HALTED ∧ INOPERATIVE` representability rule `EM-GOV-059(b)` requires | *(none today — V-4)* |
| **lifecycle transitions / phase** | ⛔ **NOT OperatingCore — and NOT decided** | `HaltedAtGate` defers transitions to `ElectionConstitution`; `ElectionLifecycleState` lives in a **different bounded context** (`app/Domain/Election/Enum/`) | — |
| **resumption target resolution** | **OperatingCore (P-7)** | authorized, frozen, consumed | *(only a unit test — V-6)* |
| **the permission to restore** | 🔴 **UNOWNED** | nothing in the model answers *"why is restoration permitted?"* — `DEP-6`/`R-2`, unauthorized | — |
| **appointment authority** | ⛔ **EXTERNAL to the Election model** | `OrganisationalAppointmentAuthority` — declared, deliberately unimplemented | — |

> ⚠️ **`ElectionLifecycleState` is NOT evidence of ownership and must not be imported as the answer.** D3 forbids it in terms. **A concept is owned because one bounded context defines its meaning — and no OperatingCore artifact defines lifecycle phase.** That absence *is* `BND-1`.

### D · Existing concepts, what they actually mean, and which may be consumed

| Concept | What it actually means | Legitimately consumable by Act B? |
|---|---|---|
| `ElectionOperationalStatus` | the two orthogonal recorded facts, combined | ✅ **Yes — as the return type, UNCHANGED** |
| `ElectionId` | election identity | ✅ **Yes — as the retrieval key** |
| `HaltedAtGate` | the recorded halt fact | ✅ **Yes — reached only *through* the status; never constructed by Act B** |
| `ResumptionTarget` (P-7) | halt → gate designation | ✅ **Consumed, never duplicated, never loosened to accept `null`** |
| `OperationalCondition` | the overlay enum | ✅ Yes, via the status |
| the three `…Repository` interfaces | **aggregate** contracts (`AG-1/2/3`, repo Rule 9) | ⛔ **No** — consuming their *shape* would import aggregate standing (V-7) |
| `ElectionRestored` | the restoration fact, `$returnsToGate` **non-nullable** | ⛔ **No** — its representation is `ADR-2 (h)` / `R-3`, and **D2 selects none** |
| `ElectionLifecycleState` (other context) | legacy lifecycle phase | ⛔ **No** — D3 |

### E · Missing domain capability — and why it is a domain gap, not an Application problem

> ## **What is missing is not a concept. It is the domain's ability to ANSWER A QUESTION IT ALREADY KNOWS HOW TO EXPRESS.**

**The gap: `ElectionOperationalStatus` is complete and unreachable (V-4).** Nothing can obtain one for an election, so recorded operational truth cannot enter any decision.

**Why this is a DOMAIN-model gap, on four independent grounds:**

1. **Two adopted domain policies are starved, not one** (V-8): P-7 (`EM-GOV-059(c)`) **and** `ExpiryConsequence` (`EM-GOV-063`). **An adopted business rule is unreachable** — that cannot be an Application concern.
2. **The Application is structurally forbidden to hold the fact** (V-12, `W-4`). It may not compensate even if it wanted to; a second truth that drifts is exactly what the guard forbids.
3. **The halt must be *recorded truth*, not a per-request value** (`EM-GOV-059(b)`). Only the domain can own recorded truth; reconstructing it in Application is banned by ADR-2 §5d.
4. **The Application's only current substitute is a proxy the record already calls insufficient** — `FillCommitteeSeatHandler` names the *established acceptance decision's* gate, *"exact in Model A today; silently wrong the day two designations are established at once."* **A proxy for missing domain knowledge is the ownership violation, not the fix.**

---

## 4 · MINIMAL CONTRACT DEFINITION

### F · Proposed contract — semantic responsibility, inputs, outputs, invariants

| | |
|---|---|
| **Semantic responsibility** | answer exactly one question: *the recorded operational status of an identified election.* **One operation. No second responsibility.** |
| **Input** | `ElectionId` — the retrieval key **all three `BND-3` candidates share** (gate `G-2`) |
| **Output** | `ElectionOperationalStatus` — **the frozen type, unchanged, TOTAL (non-nullable)** |
| **Invariants the contract must preserve** | ① it is a **READ of recorded truth**, never a computation or classification (`DD-1`, `W-4`) · ② it returns **both orthogonal facts intact** — a halt survives Inoperative and survives restoration (`EM-GOV-059(b)/(c)`) · ③ it **never fabricates a halt**; absence of a halt is a legitimate recorded answer (**D2**) |
| **Causal / provenance guarantees** | it supplies **only** the input to *"where does restoration resume?"* (via `haltedAtGate()` → P-7). ⛔ It carries **no** permission verdict and **no** answer to *"why is restoration permitted?"* — `ADR-2 (a)`'s boundary is preserved **by omission**, deliberately |
| **What it deliberately does NOT expose** | ⛔ no `save`/`persist` *(act C)* · ⛔ no lifecycle phase *(BND-1)* · ⛔ no aggregate root, no identity generation, no invariant enforcement *(BND-3)* · ⛔ no `ElectionRestored` construction *(R-3, unauthorized)* · ⛔ no protocol read · ⛔ no clock or instant · ⛔ no collection query, no "all elections", no filtering |

#### ⭐ Why the return type must be TOTAL — derived, not preferred

**A nullable return would mean *"no operational status is recorded for this election"* — a NEW domain meaning carried by `null`, which the standing prohibitions forbid outright.** Applying **ADR-1 §6b's decided composite rule** (each reference's behaviour follows the **invariant of that reference**):

| Reference | Invariant | Therefore absence is |
|---|---|---|
| Committee | required existence | a violation |
| Acceptance decision | required constitutional decision | a violation |
| `RecoveryProcess` | **optional running process** | a **normal state** *(DEP-10, protected)* |
| **operational overlay** | **`Operative`-not-halted is a POSITIVE recorded state, not an absence** | ⇒ **not a legitimate business state** |

**The overlay is therefore not analogous to `RecoveryProcess`, and totality is the boundary-neutral shape.** ⚠️ **One declared dependency:** whether an election that has not yet been constituted *has* an overlay at all is a **lifecycle-phase** question ⇒ **`BND-1`, deferred.** **The contract must not answer it, so it is stated as a documented precondition and logged in the ledger — not encoded.**

#### Naming and placement — criteria first, then one candidate

**Criteria the name/placement must satisfy:** ① assert **no** aggregate standing · ② assert **no** persistence model · ③ name the **question answered**, not a storage role · ④ avoid the `…Repository` suffix and the `Repository/` directory (**barred by repo Rule 9 + `G-2a`, V-7**) · ⑤ avoid inventing a new artifact class where a frozen precedent exists (`ES-005.4`).

**Candidate, offered as a candidate and requiring architecture confirmation:** a **domain-owned driven port** placed in `Domain/OperatingCore/Port/`, named for the recorded fact it yields — e.g. `RecordedOperationalStatus` with a single operation `ofElection(ElectionId): ElectionOperationalStatus`.

**Why `Port/` and not a new location (V-10):** the frozen code already contains a domain-owned interface there that is **declared, deliberately unimplemented, wired nowhere, and not one of the six** — so the directory demonstrably does **not** confer aggregate standing, and no new artifact class is invented. ⚠️ **Stated at one strength only:** the precedent establishes *that such an interface may live there unimplemented*; it does **not** make the two ports semantically alike.

> ### ⚠️ **`C-2` ("no new port without authorization") is NOT triggered by Act B — and this is a precise, verified point, not a convenience**
> **V-9: the "six-port universe" is the Application's INJECTED COLLABORATOR SET.** Act B **defines** a contract and **wires it nowhere** (act D is out of scope), so it **does not enlarge that set**. ⛔ **Act D will enlarge it to seven and therefore needs its own authorization** — logged in the ledger. **If the PO/ARB reads `C-2` as closing the `Port/` *directory* rather than the collaborator set, Act B stops here** (see §J).

---

## 5 · `BND-1` / `BND-3` NON-DECISION CHECK

### G · Demonstration, not assertion

**It does not decide `BND-3`** — the contract is satisfiable by **all three** candidates, unchanged:

| `BND-3` candidate | Can implement `ofElection(ElectionId): ElectionOperationalStatus`? |
|---|---|
| a **distinct aggregate** | ✅ yes — loads its own root by `ElectionId` |
| **part of a lifecycle aggregate** | ✅ yes — reads the overlay off the larger root |
| a **projection over recorded facts** | ✅ yes — folds recorded facts to the status |

**Because the contract declares no root, no identity generation, no invariant enforcement, no transaction boundary and no `save`, it distinguishes none of them.** *(Gate `G-2`, independently re-derived here.)* **And the name carries no aggregate vocabulary (V-7 avoided).**

**It does not decide `BND-1`** — the contract mentions no phase, no `ElectionLifecycleState`, no establishment marker, and creates no Published-Language relationship with the legacy Election context. **The one place `BND-1` could have leaked is the totality question, and it is declared as a dependency instead of being encoded (§F).**

**It does not decide the other five withheld questions:** no persistence *(C)* · no Application consumption *(D)* · no `ElectionRestored` representation *(D2 selects none)* · no GREEN-5 · no existing-repository change *(A)*.

**Two temptations explicitly refused:** ⛔ **no "fourth aggregate"** was created to solve the naming problem; ⛔ **no projection** was invented to dodge the boundary — the contract is deliberately silent on which it is.

---

## 6 · RED TEST DESIGN

### H · Specification only — nothing written

> ⚠️ **The two halves must not be conflated. The estate's own precedent governs:** `AbsentAggregateReferenceRedTest` is *"a genuine RED: it fails on arrival"*; `ConstitutionalValueConsumptionRedTest` is *"a regression lock over behaviour already true."* **Act B has one of each, and only the first is a RED.**

#### H-1 · The genuine RED — retrieval is absent

| | |
|---|---|
| **Test** | `RecordedOperationalStatusRetrievalRedTest` — `tests/Unit/Contexts/Election/OperatingCore/` *(domain suite, not `OperatingCoreApplication/`)* |
| **Domain invariant** | ***"The domain can answer, for an identified election, what operational status is recorded — and where that status records a halt it yields the resumption target through P-7, while where it records none it yields NO target and fabricates nothing."*** |
| **Given** | a domain-level test double of the contract, standing for a recorded status |
| **When** | the status is retrieved for an `ElectionId` |
| **Then** | ① a halted status yields `isHalted() === true` and `ResumptionTarget::resolve(haltedAtGate())` returns the recorded gate · ② a restored-without-halt status yields `isHalted() === false`, `haltedAtGate() === null`, **and no target is produced by any means** |
| **How it fails by absence** | **the contract does not exist, so the double cannot be declared and the test cannot load.** ⛔ Not an assertion failure — a **structural absence**, which is the strongest RED and is git-provable as a separate commit preceding any GREEN |
| **Why it belongs in the DOMAIN layer** | it names only `ElectionId`, `ElectionOperationalStatus`, `HaltedAtGate`, `ResumptionTarget` — **no Application namespace, no handler, no port from the six** |
| **Why it is independent of the current handler** | it never mentions `FillCommitteeSeatHandler` and asserts nothing about who calls whom. **It would survive UC-3 being rewritten or deleted.** ⛔ It is **not** aimed at `AbsentAggregateReferenceRedTest`, and passing it makes GREEN-5 no closer |

#### H-2 · NOT a RED — a regression lock over behaviour already true

**The `w8` discrimination already holds on the frozen type (V-2/V-3), so a test asserting it would pass on arrival.** It is worth pinning — it locks D2's clarification against future drift — **but it must be labelled a regression lock, never counted as the RED**, or the RED-before-GREEN evidence is falsified.

#### H-3 · What must NOT be tested in this lane

⛔ any Application normalization · ⛔ `ElectionRestored`'s representation *(D2 selects none — a test would encode it)* · ⛔ the permission invariant *(`R-2`, unauthorized)* · ⛔ persistence round-tripping *(act C)* · ⛔ anything requiring a phase discriminator *(`BND-1`)*.

---

## 7 · AUTHORIZATION / DEPENDENCY MATRIX

### I · Dependency ledger

| Item | Classification |
|---|---|
| `ElectionOperationalStatus` completeness | ✅ **resolved** — frozen, needs no change (V-1) |
| The `w8` semantic (`Restoration ≠ Resumption`) | ✅ **resolved** — D2 |
| Defining the retrieval contract | ✅ **authorized** — D1 / Act B |
| The RED test for the missing invariant | ✅ **authorized** — Phase 2A |
| Naming/placement of the contract | ✅ **authorized as analysis** — ⚠️ **candidate requires architecture confirmation** (`G-2a`) |
| `DEP-5b` made *retrievable in practice* | ⛔ **outside Act B** — act C (persistence/adapter) |
| `DEP-1` call sites (UC-1/UC-2 rewiring) | ⛔ **outside Act B** — act D |
| `DEP-2` (`AcceptanceDecision` phase discriminator) | 🔴 **blocked by `BND-1`** — no owner for phase (gate `G-5`) |
| `DEP-3` | ✅ **resolved as a property**, not a concept — discharged by `DEP-1`/`DEP-6`, never a separate absence policy |
| `DEP-4` | ✅ **resolved** — `PeriodKind` + P-6 already suffice; a copy would breach `ADR-2 (g)` |
| `DEP-6` (why restoration is permitted) | 🔴 **blocked** — needs `R-2` **and** `R-3`; **D2 authorizes neither** (gate `G-4`) |
| `DEP-10` (the four guarded `RecoveryProcess` sites) | ✅ **class A, PROTECTED** — Amendment 1; **preserve all four**, `FillCommitteeSeat` 174/192 · `RecordVacancy` 161/174 |
| `R-1`'s **save** half and final form | ⛔ **requires new authorization** — act C + Appendix Q item 2 |
| `ElectionRestored` representation (`ADR-2 (h)` / `R-3`) | ⛔ **requires new authorization** — D2 selects none |
| `BND-3` (overlay boundary) | ⏸️ **deferred** — D4 |
| `BND-1` (lifecycle-phase ownership) | ⏸️ **deferred** — D3 |
| **Totality of the return type for a pre-establishment election** | 🔴 **blocked by `BND-1`** — declared as a precondition, deliberately not encoded |
| **`C-2` / seventh Application collaborator** | ⛔ **requires new authorization at act D** — not triggered by Act B (V-9) |
| ADR-1 §6(c) normalization slice · **GREEN-5** | 🔴 **blocked by `BND-1`** — gate `G-6`. ⚠️ **Completing Act B perfectly leaves GREEN-5 stopped** |
| `ExpiryConsequence` / `EM-GOV-063` reachability | 🔴 **blocked** — same producer gap (V-8); **not in any DEP; recommend a backlog item** |

> ⚠️ **New scope discovered mid-slice → a backlog item and a report, never an extension (Obligation 6).** **One item qualifies: V-8** — `EM-GOV-063`'s terminal consequence is unreachable for the same reason as P-7, and **no DEP covers it.** **Reported here; recommended as a backlog item; NOT extended into this slice.**

---

## 8 · IMPLEMENTATION READINESS

> ## **"Act B design is sufficiently defined to PERMIT IMPLEMENTATION OF THE ACT-B CONTRACT — subject to independent verification and Architecture confirmation of naming/placement."**
>
> ⚠️ **Wording tightened 2026-08-18 at the PO/ARB's explicit request, by the governance recording session. The analysis is untouched — only this headline changed.** **Reason given: the previous phrasing (*"sufficiently defined for implementation"*) could be read by an implementation engineer as *"go implement"*, which the authorization does not permit.** **The two conditions below were already stated immediately underneath; the headline now carries them so it cannot be quoted alone.**

**The design is complete** because the meaning was already frozen (V-1/V-2), the key is agreed (`ElectionId`), the return type is agreed and unchanged, boundary neutrality is demonstrated (§G), and the RED is specifiable and fails by absence (§H-1).

**The two conditions:**

1. **Architecture must confirm the naming/placement candidate** — `G-2a` is a real hazard and placement is analysis, not an afterthought. Specifically: whether `C-2` closes the **collaborator set** (V-9 — then Act B proceeds) or the **`Port/` directory** (then Act B stops, §J).
2. **This document must be independently verified** — `R-34`/`EP-02`, and §0.1's disclosure makes it non-optional. **This lane supplies evidence and does not accept its own work.**

⛔ **And the honest limit, which the design cannot remove:** **Act B yields a contract nothing can implement yet** (act C) **and nothing may call yet** (act D). **Its value is that the domain finally states what must be retrievable — not that anything becomes reachable.** **Plan for a contract, not for a fix.**

---

## 9 · STOP / NEXT ACTOR

### J · STOP conditions — what would force this lane to stop rather than invent a model

1. **If `C-2` is read as closing the `Port/` directory** — the contract would need a location that asserts nothing, none exists, and inventing an artifact class to hold it would be a technical mechanism. ⛔ **STOP; ask.**
2. **If the totality of the return type is contested** — a nullable return requires an absence meaning, which is `BND-1`. ⛔ **STOP; do not encode either answer.**
3. **If anyone asks this lane to make `ElectionRestored` express `w8`** — that is `R-3`, and **D2 selects no representation.** ⛔ **STOP.**
4. **If the contract cannot be defined without naming a root, a transaction boundary or a persistence model** — that is `BND-3`. ⛔ **STOP.**
5. **If GREEN-5 or `AbsentAggregateReferenceRedTest` is offered as the target** — both are consequences, never targets, and `BND-1` blocks the slice regardless. ⛔ **STOP.**
6. **If the designation/START is not recorded by Governance** — the record still reads `NOT STARTED` (§0.2). ⛔ **STOP before any RED or implementation commit.** *(Scope corrected by this lane, 2026-08-18: an earlier form read "before any commit", which would have left this analysis untracked and repeated the work item's own recorded integrity defect `N-1` — "the evidence base is UNTRACKED". Committing the ANALYSIS asserts no authorization and carries this document's own disclosure; committing the RED would.)*

### Next actor — distinguished explicitly

| Actor | What is theirs |
|---|---|
| **PO/ARB** | ① decide whether this lane stands, given §0.1 · ② resolve STOP-condition 1 (`C-2`'s scope) · ③ authorize a **backlog item** for V-8 · ④ nothing here requests `BND-1`/`BND-3` |
| **Architecture / ARB** | confirm or replace the **naming/placement** candidate against `G-2a`; confirm the contract asserts no boundary (§G) |
| **Governance** | **record the lane designation and START** (§0.2) — this lane must not record its own authorization; and `G-1`: this lane does not complete its own assignment |
| **Independent verifier** | verify this document — **must be neither this lane nor any prior `EM-DOM-001` session**; §0.1 makes this mandatory, not customary |
| **Domain lane (this lane)** | **nothing further until the two conditions in §8 are discharged.** On confirmation: commit the H-1 RED, verified failing-by-absence, as its own commit |
| **Application implementation lane** | ⛔ **not the next actor.** Act D is unauthorized, `C-2` would need enlarging, and `W-4` constrains the eventual shape |

---

**PHASE 2A DELIVERED · STOPPING BEFORE PRODUCTION IMPLEMENTATION.**
⛔ **No production code. No test file. No contract created. Domain core untouched. `app/` and `tests/` unmodified. `BND-1` and `BND-3` not resolved. No aggregate, projection, sentinel, `UnknownGate`, enum value, nullability or event introduced. P-7 neither duplicated nor loosened. ADR-1 and ADR-2 neither changed nor reopened.**

**Traceability:** D1–D4 *(verbatim)* · Appendix Q *(live)* · post-decision Rule-8 gate `G-1`…`G-8` · Phase-2A addendum *(controlling)* · Phase-1 map §§0–5/7/8 *(accepted as analysis; §"three facts" item 3 corrected — V-11)* · ADR-1 §6/§6b/§6(c)/constraint ⑥ · ADR-2 §6(a)/(b)/(f)/(g)/(h) · `EM-GOV-059(b)(c)` · `062` · `063` · repo Rule 9 · P-6 · P-7 · `1f4b4c5f` · code verified: `ElectionOperationalStatus`, `HaltedAtGate`, `OperationalCondition`, `ResumptionTarget`, `ExpiryConsequence`, the three `…Repository` interfaces, `Port/*`, the three UC handlers' constructors, `ConditionSemanticsTest`, `StructuralApplicationGuardsRedTest`, `FillCommitteeSeatHandlerRedTest::test_w8_…`.
