# `EM-DOM-001` Act B — the two remaining gate commissions, **prepared and unsigned**

**Prepared by:** the governance recording session · 2026-08-18 · **Nothing implemented.**

> # 🚀 **`EM-DOM-001` ACT-B — IMPLEMENTATION RELEASE** *(PO/ARB, 2026-08-19 — recorded verbatim)*
>
> **Architecture confirmation is CONFIRMED (`6079fe9f`). Independent Phase-2A verification is VERIFIED (`ca72dec8`). The existing PO/ARB authorization `117536f3` is therefore EXECUTABLE.**
>
> **Implementation scope is strictly limited to Act B:** *create and define the Domain-owned identity/retrieval contract for the election's recorded operational status, using the verified Phase-2A design and Architecture-confirmed naming/placement.*
>
> **The implementation lane shall:** **①** create the RED test for the **absence of the contract**; **②** implement the **minimum** Act-B Domain contract; **③** make the RED test GREEN; **④** independently verify the resulting change; **⑤** **stop immediately after Act-B GREEN.**
>
> **This release does NOT authorize:** Act C persistence or adapters · Act D Application consumption · `BND-1` resolution · `BND-3` resolution · aggregate selection · lifecycle-phase ownership · UC-1/UC-2/UC-3 normalization · `GREEN-5` · final `R-1` representation · **changes to P-7** · **any new repository mechanism** · **any restoration implementation beyond the Act-B contract.**
>
> **Act-B GREEN means ONLY that the Domain-owned identity/retrieval contract exists according to the verified design.** **It does NOT mean** that operational status is persisted or retrievable at runtime, that restoration is fixed, or that `EM-GOV-063` is reachable.
>
> ⛔ **The implementation lane must not infer additional authorization from the existence of the contract.**
>
> **Next actor: a fresh Implementation/Domain lane designated and STARTed by Governance.**

> ### ⚠️ One structural note on step ④, recorded rather than silently reinterpreted
>
> **Steps ①–③ and step ④ cannot be performed by the same lane.** `EP-02`/`R-34`: **engineering supplies evidence and never accepts its own work** — a lane cannot *independently* verify itself, and the word *"independently"* in ④ is the release's own requirement.
>
> ⇒ **The designated implementation lane performs ①–③ and then STOPS** *(which is also ⑤)*. **④ is a SEPARATE designation, made after Act-B GREEN exists**, to a lane with no hand in writing it. ⛔ **No scope is added and none removed by this reading** — the release's five steps are all performed, by two lanes instead of one.

> ## ✅ ACT-B GREEN ACHIEVED — 2026-08-19 · steps ①–③ complete, ⑤ observed
>
> | Step | Commit | Contents |
> |---|---|---|
> | **① RED** | **`e7317077`** | `RecordedOperationalStatusRetrievalRedTest.php` **alone** — 4 failures, all one cause: `interface_exists(...) === false` |
> | **② + ③ GREEN** | **`216552aa`** | `Port/RecordedOperationalStatus.php` **alone** — 63 lines; test then `OK (4 tests, 16 assertions)` |
> | **DoD** | `6c653ace` | `developer_guide/election_operating_core/04_step_…md` + `00_index.md` row |
>
> ```php
> interface RecordedOperationalStatus
> {
>     public function ofElection(ElectionId $electionId): ElectionOperationalStatus;
> }
> ```
>
> **Independently confirmed by Governance before recording** *(structural checks only — this is NOT step ④)*: **RED commit contains no interface · GREEN commit contains no test · ordering strictly RED→GREEN, git-provable** · `git diff 1f4b4c5f HEAD --name-status` over the core shows **exactly one `A` line, no `M`, no `D`** *(`Port/` 7 → 8 files)* · **zero** forbidden-vocabulary hits in the new file · **no adapter, no implementation, no Application wiring** *(act D not performed)* · new test **4 passed / 16 assertions**; full frozen-core suite **46 passed / 2457 assertions**.
>
> **`C-4` applied by the lane:** the RED makes **no domain-invariant claim**, does **not** attempt the non-executable `Then` clause ②, and asserts **nothing behavioural** about halt retention, restoration or `w8` — that behaviour is *already true* on the frozen type, so it would be **`H-2`'s regression lock**, and counting it as the RED **would have falsified the ordering evidence.** **`C-3` applied:** the non-nullable return is asserted as a legitimate type-level encoding.
>
> ⛔ **Act-B GREEN means ONLY that the contract exists.** Status is **not** persisted, **not** retrievable at runtime; restoration is **not** fixed; **`GREEN-5` remains STOPPED**; **`EM-GOV-063` remains unreachable.**
>
> ### 🚀 Step ④ — independent verification DESIGNATED and STARTED, 2026-08-19
>
> A **fresh verifier with no hand in writing the change** was designated, per the release's own word *"independently"* and `EP-02`/`R-34`. **The implementing lane correctly did NOT verify its own work.** Verdict will be recorded verbatim on arrival.
>
> ### Reported by the implementing lane and NOT acted on — all correctly out of scope
>
> | # | Item |
> |---|---|
> | **1** | **15 errors + 1 failure pre-existing in `OperatingCoreApplication/`** *(`QueryServicesRedTest`, `ReportPeriodExpiryHandlerRedTest`, `RecordCommitteeConstitutionHandlerRedTest`, `RefusalTaxonomyRedTest`, `HistoryKindAssignmentRedTest`, `AbsentAggregateReferenceRedTest`)*. **Proved unrelated** — identical counts with the new file present and moved aside. **GREEN-3…7 pending; not this slice.** |
> | **2** | **`F-3` label defect** — `StructuralApplicationGuardsRedTest.php:228` calls these types *"derived classifications"* while their own docblocks call them recorded facts. **Not corrected: existing test file, outside scope; routing is the PO/ARB's.** |
> | **3** | **`H-2` regression lock not written** — the `w8` discrimination pin. **Passes on arrival, explicitly not the RED, not commissioned.** |
> | **4** | **Naming observation stands** — `Recorded…` is otherwise a value-type prefix in this core (`Time/RecordedInstant`), so the port name reads like a value object. **Gate 1 weighed this (§6.1) and declined to change it; the lane did not reopen it.** |
> | **5** | **`V-8`/`PBDIGIT-72`** — two independent reachability causes. **Untouched.** |
> | **6** | ⭐ **`CONTEXT.md` and the session log deliberately left to Governance** — *"other sessions are actively committing … CONTEXT is shared state."* **Correct call: a lane must not write into another lane's records.** **Done by Governance in this commit.** |

> ### 🚀 Designation performed — 2026-08-19
>
> **A fresh Implementation/Domain lane was DESIGNATED and STARTED by Governance**, as the release directs. It inherits none of the recording session's context, was pointed at the verified design and both gate verdicts **as its authority**, and was given the negative list above verbatim.
>
> **It was also given the two Gate-2 corrections that bear directly on its work:** **`C-4`** — `H-1`'s *"Domain invariant"* framing **overclaims**, and its `Then` clause ② (*"no target is produced by any means"*) **is not an executable assertion**, so the RED must assert only what a test can actually check; **`C-3`** — a **non-nullable** return type is a legitimate type-level encoding and **does not breach `D3`**, which settles the totality question Gate 1 expressly left open.

> ## ✅ GOVERNANCE RECORD — `EM-DOM-001` Act-B gate · **READY FOR EXECUTION — NO NEW PO/ARB DECISION REQUIRED**
>
> **Recorded as directed by the PO/ARB, 2026-08-18.**
>
> **Act B is AUTHORIZED but NOT YET EXECUTABLE.** Authorization `117536f3` covers **only** creation and definition of the Domain-owned identity/retrieval contract for the operational overlay. ⛔ **It does not authorize** Act C · Act D · `BND-1` · `BND-3` · `GREEN-5` · UC-1/UC-2/UC-3 normalization · aggregate selection · final `R-1` representation · lifecycle-phase ownership · persistence/adapters · Application changes · **or any other mechanism.**
>
> **Only the two confirmations below remain.** **On `Gate 1 = CONFIRMED` and `Gate 2 = VERIFIED`, authorization `117536f3` becomes executable WITHOUT another PO/ARB decision**, and the lane performs exactly: **`H-1` RED → create `RecordedOperationalStatus` → Act-B GREEN → STOP.**
>
> ### ⛔ Mandatory stop boundary
>
> **Act-B GREEN means ONLY:** *the Domain-owned identity/retrieval contract exists, per the verified Phase-2A design and the confirmed naming/placement.*
>
> **It does NOT mean:** restoration is fixed · operational status is persisted or retrievable at runtime · `EM-GOV-063` is reachable *(`PBDIGIT-72`)* · Act C is complete · Act D is complete · `GREEN-5` is unlocked · `BND-1` is resolved · `BND-3` is resolved. **After Act-B GREEN the lane STOPS and returns the result for the next authorized work item.**
>
> ### Repository boundary until both confirmations land
>
> `Port/` unchanged · `Domain/OperatingCore` byte-identical to baseline `1f4b4c5f` · **no `H-1` RED created** · **no implementation performed** · **no scaffolding prepared in anticipation.**
>
> ### 🚀 Designation performed — 2026-08-18
>
> **Both independent lanes were DESIGNATED and STARTED on PO/ARB instruction** (*"designate the two independent lanes"*). Each is a **fresh session inheriting none of the recording session's context**, so neither holds a prior position in this chain. **Each was pointed at the artifacts in this repository as its authority — not at any summary of them — and each may return the negative verdict** (`REJECTED` / `NOT VERIFIED`).
>
> ⛔ **The recording session performed NEITHER gate**, and by its own eligibility rule could not: it recorded D1–D4, ran the post-decision Rule-8 gate, tightened the design map's headline and authored the critical-path note. **Verdicts will be recorded verbatim when they arrive.**
>
> ⛔ **Per the same instruction, NO further design or governance artifact was created for `EM-DOM-001`** — this record was amended in place.
**Purpose:** hold both commissions ready so that designating them is a one-line act, and neither has to be re-derived.

> ⛔ **Neither text below is a verdict.** ⛔ **This session performs neither gate.** ✅ **The Act-B implementation authorization is already registered** (`117536f3`) and **becomes executable the moment both gates land** — no further PO/ARB act is required after that.

---

## ✅ GATE 1 — **CONFIRMED** (2026-08-18, `6079fe9f`)

**Verdict recorded as returned. Verdict artifact:** `2026-08-18-EM-DOM-001-act-b-gate1-architecture-confirmation.md` *(one file, 222 insertions; `app/` and `tests/` untouched; OperatingCore byte-identical to `1f4b4c5f`)*.

**`G-2a` upheld, and `Repository/` is barred on the code's own words:** all three interfaces there declare *"contract for AG-1/AG-2/AG-3"*, and `ElectionCommitteeRepository.php:11` states outright *"repositories exist for aggregates only — repo Rule 9."* **A fourth would assert exactly the aggregate standing D1 and D4 withhold.**

**The load-bearing precedent is `ProtocolAppend`, NOT `OrganisationalAppointmentAuthority`** — a contract over the Election's **own** recorded record, with *"the storage mechanism deliberately NOT prescribed (G-6)"* and no adapter. *(The commission's caution that V-10's external-actor port is not semantically alike was accepted.)*

⭐ **Neutrality established on LOCK-IN, not on silence:** the signature declares **no root, no identity, no `save`, no transaction, not even `find()`**; **all three `D4` candidates can supply it**; and with **zero consumers** and **no adapter**, **any later `BND-3` ruling remains implementable without rework.** ⇒ **`BND-3` was never needed to answer, so the REJECT hatch did not apply.**

### ⚠️ Three items the Gate-1 lane flagged — routing is the PO/ARB's

| # | Item |
|---|---|
| **F-1** 🔴 | **The Gate-1 lane DEPARTS from the untracked architecture review, which places the contract in `Repository/`** *(that review, line 129: "the contract would live in `Domain/OperatingCore/Repository/`")* **and miscounts `Port/` as six ports.** Gate 1 cites it but **rejects that placement** and does **not** adopt its §5.1 projection elimination. ⚠️ **Two architecture opinions now disagree on placement.** **Gate 1 is the designated, committed, post-Phase-2A one; the other is untracked and predates the proposal.** **Reconciling them is the PO's call.** |
| **F-2** | **`C-2` resolved on its ARCHITECTURAL half only:** the grant's own enumeration includes the three `Repository` interfaces, so *"six-port universe"* means **the Application's collaborator set, not the `Port/` directory** (V-9 independently confirmed). The authorization half is unnecessary because **D1 authorizes definition directly** — ⚠️ **but `C-2` still bites at act D.** |
| **F-3** | **A naming-coherence risk:** `StructuralApplicationGuardsRedTest.php:228` labels `ElectionOperationalStatus`/`HaltedAtGate` *"derived classifications"* — **a loose label a later lane could quote against the word *"Recorded"*** in `RecordedOperationalStatus`. **Flagged, not resolved.** |

**Explicitly excluded from the confirmation:** the return type's **totality** — a shape question, still `BND-1`-dependent, **and Gate 2's business**. **All five non-determinations recorded** *(no `BND-1`, no `BND-3`, no aggregate ownership, no `R-1`/`R-2`/`R-3` approval beyond the bounded contract, no implementation authority beyond `117536f3`)*.

---

## GATE 1 · original commission *(retained for traceability)*

**Scope:** answer **`G-2a` only.** ⛔ Not `BND-3`, not persistence, not wiring, not lifecycle ownership.

**Draft text supplied by the PO/ARB, held here for the Architecture lane to adopt, amend or reject — ⬜ UNSIGNED:**

> ### **Architecture confirmation — Act B naming/placement**
>
> Architecture confirms that the proposed Domain-owned contract `RecordedOperationalStatus`, located under `app/Contexts/Election/Domain/OperatingCore/Port/`, is architecturally acceptable and boundary-neutral for Act B.
>
> This confirmation does **not** determine BND-1 or BND-3, does not establish aggregate ownership, and does not approve R-1/R-2/R-3 beyond the bounded Act-B contract.
>
> It does not authorize implementation beyond the existing EM-DOM-001 Act-B authorization.

⚠️ **Recorded as a DRAFT, deliberately.** **The PO/ARB drafted the wording but assigned the judgment to Architecture** *("the existing Architecture review can be amended with a very narrow statement")*. ⛔ **If this session or any other simply pasted it in as satisfied, `G-2a` would be answered by the party that drafted it rather than by an architecture judgment — which is the exact purpose `G-2a` serves.** **An independent lane must adopt it as its own verdict.**

**⭐ Confirmed procedural point:** ***the existing `2026-08-18-EM-DOM-001-architecture-review.md` must be committed by the session that OWNS it*** — not silently incorporated as another session's untracked work. **That review already establishes the substance** (*"`Domain/OperatingCore/Port/*` … a **different artifact class from Repository**"*); **committing it plus the paragraph above discharges Gate 1.**

---

## ✅ GATE 2 — **VERIFIED** (2026-08-18, `ca72dec8`)

**Verdict recorded as returned.** Artifact: `2026-08-18-EM-DOM-001-act-b-gate2-independent-verification.md` *(tracked, 164 lines, clean at HEAD)*. **Read-only throughout — `app/`/`tests/` untouched, no contract created, no `H-1` written.**

**All six items check out on primary code evidence.** Frozen core byte-identical to `1f4b4c5f` *(same tree hash, 56 files, re-verified after HEAD advanced)* · `V-1…V-12` each trace to `file:line` · **`w8` genuinely representable on the frozen type** (`ElectionOperationalStatus.php:22-24,39-42,49-52` ⇒ `haltedAtGate() === null`, `isHalted() === false`) · **no production producer or retrieval path for `HaltedAtGate`** (`new HaltedAtGate` only at `ConditionSemanticsTest.php:89,110,173`) · **`H-1` is a genuine structural-absence RED**, not behavioural, no design smuggled through it · D1–D4 and `G-2a` complied with (`Port/*` carries **no** AG-n vocabulary; `Repository/*` has three matches).

⭐ **The "all three `BND-3` candidates share `ElectionId`" claim is STRONGER than argued:** the frozen type **carries no second discriminator at all**, unlike AG-2 *(per gate)* and AG-3 *(per kind)*.
⭐ **D2 was the sharpest attack and it clears:** the nullable `?HaltedAtGate` is **pre-existing frozen state with `isHalted()` as its named discriminator** — **no sentinel, and it selects no representation.**

### Four corrections — none falsifying a load-bearing claim

| # | Correction |
|---|---|
| **C-1** | **`V-9`'s *"the three handlers' constructors"* is wrong** — **five handlers plus four `Query/` classes** inject. **The union is still exactly six**, and the grant settles it better than the map does (`EM-ARCH-002…:90-91` defines the six as *"Ports consumed"* and excludes `OrganisationalAppointmentAuthority`). |
| **C-2** | **`V-8`'s attribution is incomplete** — the terminal consequence is unreachable for **TWO** independent reasons: **UC-4 is also unimplemented** (`ReportPeriodExpiryHandler.php:31-33` throws `BadMethodCallException`). ⚠️ **So a halt producer ALONE would not reach it** — recorded against `PBDIGIT-72`. |
| **C-3** | §G's *"the totality question … is declared as a dependency instead of being encoded"* is **imprecise** — a non-nullable return type **IS** a type-level encoding. **What is not encoded is any phase concept or ownership**, so **D3 is not breached.** |
| **C-4** | §`H-1`'s *"Domain invariant"* framing **claims more than the test can carry**, and its `Then` clause ② (*"no target is produced by any means"*) **is not an executable assertion.** |

### 🔴 The blocking item it raised — and its closure

> **Unsubstantiated:** the design map's own authority premise (§0.2) — **the authorization record still read *"⏸️ NOT STARTED — the fresh Domain lane has not been designated."*** *(`…authorization-request.md:6,241`)* **This is the map's own STOP-condition 6 and must be closed by Governance before the `H-1` RED is committed.**

✅ **CLOSED 2026-08-18 by Governance.** The line was **stale on three counts** — a lane was designated and completed Phase 1 and Phase 2A; the analysis-only limitation was **lifted** by `117536f3`; and Act-B implementation is authorized with both gates now returned. **Status corrected in place.** *(A correct finding: the record contradicted the authorization it was supposed to carry.)*

**It also independently corroborated Gate-1 flag `F-1`:** the untracked `…architecture-review.md` proposes the **opposite** placement (`Repository/`, `:129`) and miscounts `Port/` as six ports (`:140`), **so it cannot be cited as confirming `Port/`.** ⇒ **Two independent lanes now reach the same conclusion about that untracked review.**

⚠️ **Reported honestly by the verifier:** a concurrent session's commit `99aeac7c` swept its staged verdict file into that commit before it could commit; `ca72dec8` then carried the final amendment. **Verified here: the file is tracked, complete and clean at HEAD.**

---

## GATE 2 · original commission *(retained for traceability)*

**⭐ The waiver offered by this session is DECLINED by the PO/ARB, on the record:** *"I would **not** waive independent verification merely to save time … an independent verifier costs little and gives you a clean evidence chain."* ⇒ **Gate 2 STANDS. It is no longer an open option and should not be revisited as one.**

**Inspect ONLY:** the Phase-2A design map · the frozen Domain core · the proposed `RecordedOperationalStatus` · the `H-1` definition · naming/placement · compliance with **D1/D2/D3/D4** and **`G-2a`**.

⛔ **Must NOT:** redesign the contract · reopen `BND-1` or `BND-3` · propose an alternative · widen scope.

**Verdict form:** > ## **VERIFIED** / **NOT VERIFIED** — with evidence.

⛔ **Eligibility:** **not this session** and **not the Phase-2A design lane.** `EP-02`/`R-34` — engineering never accepts its own work; **this session holds positions throughout this chain** *(it recorded D1–D4, ran the post-decision gate, tightened the map's headline, and authored the critical-path note)*, and the design lane disclosed a prior position in its own §0.1. **Any session with no position in this chain qualifies.**

---

## What becomes executable when both land

```
Gate 1 ✓  +  Gate 2 ✓
        │
        ▼
the ALREADY-REGISTERED Act-B authorization fires — no new PO/ARB act
        │
        ▼
H-1 structural RED  (fails by ABSENCE of the contract — never a design smuggled through a test)
        │
        ▼
create RecordedOperationalStatus
        │
        ▼
Act-B GREEN
        │
        ▼
STOP  ⛔ not Act C · not Act D · not GREEN-5
```

**Confirmed as NOT required for Act-B GREEN:** `BND-1` · `BND-3` · act C · persistence · adapters · Application changes · UC normalization · `GREEN-5` · aggregate selection · final `R-1` design · lifecycle-phase ownership. **The contract is intentionally boundary-neutral.**

**Traceability:** Act-B authorization record `117536f3` · critical-path note *(`Act-B GREEN ≠ GREEN-5`)* · post-decision Rule-8 gate `G-2`/`G-2a` · Phase-2A map §8 · D1–D4 · `EP-02` · `R-34` · `1f4b4c5f`.
