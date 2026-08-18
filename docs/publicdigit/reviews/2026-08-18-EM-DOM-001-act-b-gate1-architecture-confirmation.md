# `EM-DOM-001` Act B · **GATE 1 — Architecture confirmation** of naming / placement (`G-2a`)

> # ✅ **GATE 1 — CONFIRMED**

**Lane:** independent Architecture lane for Gate 1 · **Date:** 2026-08-18 · **Type:** 🔴 **READ-ONLY architecture ruling.**
**Scope answered:** `G-2a` **only** — is the proposed Domain-owned contract `RecordedOperationalStatus`, located under `app/Contexts/Election/Domain/OperatingCore/Port/`, architecturally acceptable and **boundary-neutral** for Act B?

**Independence, declared:** this lane held **no position anywhere in the `EM-DOM-001` decision chain** before this commission. It did not author D1–D4, the Rule-8 post-decision gate, the dossiers, the decision surface, ADR-1/ADR-2, the Phase-1 or Phase-2A maps, the architecture referral, or the untracked `2026-08-18-EM-DOM-001-architecture-review.md`. It reached the verdict below from the **code and the governing decisions**, not from any summary.

**Attestation, verified at HEAD `c6a2a0a3`:** `git status --porcelain app/ tests/` shows **no change of any kind** except the pre-existing quarantined pin `tests/Feature/Election/ElectionOnlyEntitlementPinTest.php` (untracked, untouched, not evidence). `git diff 1f4b4c5f -- app/Contexts/Election/Domain/OperatingCore` is **empty** — the frozen core is byte-identical. **No contract was created. No `H-1` test was written. `Port/` still holds its seven pre-existing files.**

---

## 1 · The verdict, in the form the PO/ARB drafted — **adopted as this lane's own judgment, with the scope conditions in §5**

> ### Architecture confirmation — Act B naming/placement
>
> Architecture confirms that the proposed Domain-owned contract `RecordedOperationalStatus`, located under `app/Contexts/Election/Domain/OperatingCore/Port/`, is architecturally acceptable and boundary-neutral for Act B.
>
> This confirmation does **not** determine BND-1 or BND-3, does not establish aggregate ownership, and does not approve R-1/R-2/R-3 beyond the bounded Act-B contract.
>
> It does not authorize implementation beyond the existing EM-DOM-001 Act-B authorization.

⚠️ **The wording is adopted; the judgment is this lane's.** The reasoning in §§2–4 was derived independently from the source and would have supported **REJECT** had the tests below failed. Two of them nearly did — see §6.1 and §6.2.

**Deciding `G-2a` did NOT require deciding `BND-3`.** The neutrality test in §4 is satisfied **under all three `BND-3` candidates without selecting among them**, so the escape hatch offered by the commission (*"if you would need to decide `BND-3`, REJECT"*) is not taken.

---

## 2 · `G-2a`'s hazard is real, and it is stronger than a docblock convention — **CONFIRMED on the code**

| Evidence | What it establishes |
|---|---|
| `app/Contexts/Election/Domain/OperatingCore/Repository/ElectionCommitteeRepository.php:11-12` — *"Persistence-independent contract for **AG-1** (**repositories exist for aggregates only — repo Rule 9**)"* | the bar is a **repository rule**, not a local comment style |
| `Repository/AcceptanceGateDecisionRepository.php:12` — *"contract for **AG-2**"* | 2 of 3 |
| `Repository/RecoveryProcessRepository.php:12` — *"contract for **AG-3**"* | 3 of 3 |
| `CLAUDE.md` — **Rule 9: "Repository Pattern for Aggregates Only. Do not create repositories for every table. Only for aggregates."** | the rule exists at repository level, above this work item |
| all three expose `find(...)` **and** `save(...)` | the artifact class carries a **write/consistency** boundary, which Act B is expressly denied (act C) |

⇒ **A fourth contract named `…Repository` or placed in `Repository/` would assert aggregate standing** — the exact selection **D1** withholds (*"does not authorize … selection of the overlay's aggregate or persistence boundary"*) and **D4** forbids (*"must not use D1 as authorization to select the overlay's aggregate boundary or persistence model"*). **`G-2a` is upheld: `Repository/` is barred for Act B.**

⚠️ **This is where I depart from the untracked `2026-08-18-EM-DOM-001-architecture-review.md`, and the departure is material.** That review's §4 states the contract *"would live in `Domain/OperatingCore/Repository/`"* (line 129). **That statement predates `G-2a` and cannot survive it** — and the review's own §4.1 (line 140) supplies the reason, recording `Domain/OperatingCore/Port/*` as *"a **different** artifact class from Repository"*. **I adopt that row and reject that placement sentence.** *(That review is another session's untracked work; it is cited, not committed, not incorporated.)*

---

## 3 · What `Port/` actually denotes in **this** codebase — read from the seven files, not from the word "port"

`Port/` holds **four interfaces and three supporting types** — not "six ports":

| File | Kind | Its own docblock says |
|---|---|---|
| `Port/InstantSource.php:9-16` | interface | *"**Driven port**: supplies RECORDING INSTANTS only"* |
| `Port/ProtocolAppend.php:8-23` | interface | *"**Driven port**: the Election Protocol … the **storage mechanism is deliberately NOT prescribed (G-6)** … Choosing a concrete store is an implementation decision **gated behind its own authorization — no adapter exists in this increment**"* |
| `Port/ServicePolicySnapshot.php:10-18` | interface | *"**Driven port** (ACL boundary — B-5)"* |
| `Port/OrganisationalAppointmentAuthority.php:8-35` | interface | *"**Driven port** … **DECLARED, DELIBERATELY UNIMPLEMENTED** … **NO ADAPTER, DEFAULT, STUB OR FALLBACK** — anywhere, ever"* |
| `Port/HistoryKind.php`, `Port/ProtocolEntry.php`, `Port/RefusalRecord.php` | enum / value objects | the ports' vocabulary, not ports |

⭐ **The architectural reading, derived from those four:** in the OperatingCore, `Port/` means ***a contract the domain declares for something it needs supplied from outside itself, where the supplying mechanism is deliberately unchosen.*** **It says nothing about aggregates. Not one of the four names an aggregate, and none can be read as asserting or denying one.**

### 3.1 ⭐ The precedent that actually carries the placement is `ProtocolAppend`, not `OrganisationalAppointmentAuthority`

The Phase-2A map rests placement on **V-10** (`OrganisationalAppointmentAuthority`) and honestly concedes its limit: it is *"a **driven port to an external actor**"* and *"**not** semantically like the Act-B contract"*. **The commission names that limit too, and it is correct.** **V-10 therefore proves only that an unimplemented, unwired domain interface may live in `Port/` — a weaker claim than the placement needs.**

**`ProtocolAppend` closes that gap, and it is the closer precedent on every axis that matters here:**

| Property Act B needs | `ProtocolAppend` |
|---|---|
| the **subject is the Election's own governed record**, not an external actor | ✅ the Election Protocol — Election's own permanent record (`EM-GOV-005`; `ProtocolAppend.php:8-9`) |
| **mechanism / persistence expressly NOT chosen** | ✅ *"the storage mechanism is deliberately NOT prescribed (G-6)"* (`:12-13`) |
| **no adapter, and none authorized by defining it** | ✅ *"no adapter exists in this increment"* (`:21-22`) |
| **no aggregate claim, and reconstruction left open** | ✅ *"Aggregate state is always reconstructable FROM recorded facts, never richer than them"* (`:19-20`) — the port names what must be obtainable and leaves the fold unspecified |

⇒ **The `Port/` directory demonstrably already holds a contract over the domain's own recorded truth whose persistence model is deliberately undetermined. That is precisely the artifact class Act B requires, and it exists — so nothing new is invented (`ES-005.4`).**

### 3.2 `Port/` is the **only** existing home that fits — a derivation, not a preference

The OperatingCore's directories are `Committee · Condition · Event · Exception · Gate · Policy · Port · Recovery · Repository · Time`. **There is no `Query/`, `Read/`, `Contract/` or `Service/`.** Given that

1. the required artifact **is a domain-declared outward contract** *(not disputed: the untracked review §4.3 rules *"the required artifact is a domain contract, and creating one is a Domain-model act"*, and `D1` says "Domain-owned contract")*, and
2. it **must not be aggregate-specialised** (§2, `G-2a`), and
3. inventing a **new directory / artifact class** would be *"a technical mechanism"* — barred by `D1`'s closing clause and by the map's own STOP-condition 1,

**`Port/` is the residual and unique fit.** ⛔ **The alternative is not "a more neutral location"; it is "Act B stops".** That is the honest framing of the choice, and it is why placement is confirmable rather than merely tolerable.

---

## 4 · Boundary-neutrality test — applied by this lane, independently

### 4.1 The signature distinguishes none of the three `BND-3` candidates

Proposed: **`ofElection(ElectionId): ElectionOperationalStatus`** — one operation.

It declares **no root**, **no identity of its own** (the key is the *election's* `ElectionId`, `app/Contexts/Election/Domain/ElectionId.php`, never an overlay identity), **no `save`**, **no transaction or consistency boundary**, **no invariant enforcement**, **no collection or filter**, and **no `find()`** — so it does not even borrow the repository idiom.

| `BND-3` candidate (D4, all three OPEN) | Can supply this signature? |
|---|---|
| a **distinct aggregate** | ✅ load its root by `ElectionId`, return its state |
| **part of a lifecycle aggregate** not yet in the OperatingCore | ✅ read the overlay off the larger root |
| a **projection over recorded lifecycle facts** | ✅ fold recorded facts into the status |

⇒ **The contract is satisfiable by every candidate and eliminates none. It cannot be read as a selection because it distinguishes nothing.** *(Re-derived here from the signature; the same conclusion as gate `G-2` and map §G, reached separately.)*

### 4.2 The test that decides it: **does the placement CONSTRAIN the later `BND-3` ruling, or is it revisable BY that ruling?**

**Boundary-neutral cannot mean "metaphysically silent" — every artifact sits somewhere.** The operative test is **lock-in**:

| | |
|---|---|
| **Consumers today** | **zero** — `ElectionOperationalStatus` is referenced only by its own file, one domain unit test, and a structural-guard regex *(re-verified: `grep -rn ElectionOperationalStatus app/` returns the declaration only)* |
| **Adapters** | **none** — act C unauthorized |
| **Wiring** | **none** — act D unauthorized |
| **Therefore, if `BND-3` later rules "distinct aggregate"** | the ruling itself introduces the `find`/`save` aggregate contract in `Repository/`; the Act-B port is superseded or becomes its read face — **a rename of one adapterless interface with no callers** |
| **If it rules "part of a lifecycle aggregate" or "projection"** | the port stands unchanged; only its unwritten implementation differs |

⇒ **No `BND-3` answer is foreclosed, and none is made cheaper or dearer by the placement.** *(The untracked review reaches the same structural point from the other end at its §5.4: "No current code prejudices the choice, because nothing consumes it. The window is open now and closes the moment a consumer is written." **That window is exactly what Act B leaves open** — it defines a contract and writes no consumer.)*

### 4.3 The inverse hazard, tested and cleared: does `Port/` assert *"NOT an aggregate"*?

**Rule 9 is a one-way implication — `Repository/` ⇒ aggregate. It does not license the contrapositive that anything outside `Repository/` is thereby declared a non-aggregate.** And the four existing `Port/` docblocks each state **what their subject is** (an external authority, an ACL boundary, the protocol, instants); **not one of them denies aggregate standing to anything.** Combined with §4.2's zero lock-in, **`Port/` placement withholds the boundary question rather than answering it in the negative.** ✅ Cleared — subject to the condition in §5.1, which is what keeps it cleared.

### 4.4 The name `RecordedOperationalStatus`

| Criterion | Assessment |
|---|---|
| carries **no aggregate/persistence vocabulary** | ✅ no `Repository`, `Root`, `Aggregate`, `Store`, `Persistence`, `Projection`, `ReadModel`, `Table`, `Entity` |
| names **the question answered / the fact yielded**, not a storage role | ✅ *"the recorded operational status"*, with `ofElection(...)` — a question, not a mechanism |
| **consumes existing ubiquitous language** rather than inventing vocabulary | ✅ *"recorded"* is already the core's own word: `Time/RecordedInstant`, `Condition/HaltedAtGate.php:11` *"**The recorded fact** that progression is HALTED"*, `Port/ProtocolEntry.php:11` *"**a recorded business fact**"*, `Port/RefusalRecord.php` |
| does *"Recorded"* **select a persistence model**? | ⛔ **No.** It asserts the **recorded-vs-derived** distinction the core already draws — `Condition/GateIntervalState.php:8-10` *"**DERIVED** … **never stored** as authoritative state"* against `HaltedAtGate.php:11` *"the recorded fact"* — and `EM-GOV-059(b)` retention is already frozen into the type: `ElectionOperationalStatus.php:32-36`, *"becoming Inoperative RETAINS the halt"*. **D4 expressly preserves "identity + persistence does not imply aggregate", so asserting recordedness a fortiori selects no boundary.** |
| does *"Recorded"* **eliminate the projection candidate**? | ⛔ **No — and this lane deliberately does not rule on whether the projection candidate survives.** A projection that **folds recorded facts** yields recorded truth reconstructed, not a fresh classification; the word describes the **status returned**, not the supplier. ⚠️ The untracked review's §5.1 *does* eliminate projection on retention grounds. **That elimination is not adopted here and is not needed here** — the name is compatible with the candidate either way, which is the only property Gate 1 requires. |

✅ **The name satisfies all five criteria the map set, and satisfies them on evidence from the frozen core rather than by assertion.**

### 4.5 Nothing structural bars the file

The only structural guards over `Port/` are **name-specific to `OrganisationalAppointmentAuthority`** (`tests/Unit/Contexts/Election/OperatingCore/StructuralGuardsTest.php:39-65`; `tests/Unit/Contexts/Election/OperatingCoreApplication/StructuralApplicationGuardsRedTest.php:107-151`). **There is no allowlist, count assertion or manifest over the directory's contents.** ⇒ **Adding one interface to `Port/` breaks no existing guard**, and it does not touch the `OrganisationalAppointmentAuthority` absence guards in any way.

### 4.6 Layer and repository-rule conformance

`Port/` sits under `Domain/` ⇒ the contract is **Domain-owned**, as `D1` requires and as ADR-1's protected line demands (*"Application detects absence. **Domain contract defines meaning.**"*). An interface over `ElectionId` and `ElectionOperationalStatus` is **pure PHP with zero framework dependency** — conformant with the repository's Domain-layer rule. **Rule 9 is honoured precisely by not using `Repository/`.** ✅

### 4.7 `C-2` — the one point where I separate the architectural half from the authorization half

ADR-1 §2 records **`C-2` "No new port without its own authorization — grant; §3a closed six-port universe"** (`docs/publicdigit/adr/ADR_20260817_2145_Aggregate_Absence_Semantics.md:65`). The map's STOP-condition 1 turns on whether that closes the **collaborator set** or the **`Port/` directory**.

**Architectural finding, from the grant's own enumeration:** the "authorized six-port universe" is enumerated in `docs/publicdigit/architecture/2026-08-17-EM-IMPL-002-q-restore-recovery-origin-provenance-investigation.md:18-24` as `ProtocolAppend`, **AG-3 `RecoveryProcess`**, **AG-2 `AcceptanceGateDecision`**, **AG-1 `ElectionCommittee`** (+ `ServicePolicySnapshot`, `InstantSource`). ⭐ **Three of the six are `Repository/` interfaces, and the `Port/` directory's other members are absent from the list.** ⇒ **The term "port" in that grant cannot denote the contents of `Port/`; it denotes the set of collaborators available to the Application.** ✅ **V-9 is independently confirmed, from the grant text rather than from the map.**

**Authorization half — not mine, and not needed:** whether the PO/ARB's `C-2` reaches *definition without wiring* is a reading of their own act. **It does not need to be reached**, because **`D1` (2026-08-18) is a later PO act that expressly authorizes *"only the creation and definition of that Domain-owned contract within the bounded Domain slice."*** **`C-2` continues to bite where it always bit: at act D**, which would enlarge the collaborator set to seven and **requires its own authorization**. ⛔ **This lane widens nothing and reads nothing into `D1`.**

---

## 5 · Conditions carried by this confirmation

These are conditions **on the same candidate** — they constrain how the naming/placement may be *carried*, which is what `G-2a` is about (the `Repository/` hazard manifests through docblocks asserting `AG-n`). **They add no design element, prescribe no text, and change neither the name nor the location.**

### 5.1 The contract's docblock must assert no boundary — a negative list

⛔ It must **not** name `AG-1`/`AG-2`/`AG-3`, an aggregate, a root, a projection, a read model, an entity, a store, a persistence model, a transaction or consistency boundary, or an identity of the overlay.
⛔ It must **not** state or imply that the overlay **is** an aggregate, nor that it **is not** one.
⛔ It must **not** claim the overlay is owned outside the OperatingCore *(the map's §C places ownership of the combined status **with** the OperatingCore; a "driven port" declares a **supply** relationship, never a transfer of ownership — `ProtocolAppend` is the standing example)*.
⛔ It must **not** assert a lifecycle phase, a phase discriminator, or anything about elections that have not been constituted *(`BND-1`)*.
✅ It **must** record that `BND-1`/`BND-3` remain **OPEN** and that the contract selects neither, and that **no adapter and no caller is authorized** — the discipline `ProtocolAppend.php:21-22` and `OrganisationalAppointmentAuthority.php:17-19` already model.

### 5.2 What this confirmation does **not** cover, stated so it cannot be inherited by adjacency

⛔ **Not confirmed here:** the **totality (non-nullability) of the return type** · the `H-1` RED test's design or its failure-by-absence claim · `H-2`'s status as a regression lock · the `V-1…V-12` findings *(re-verified only where §§2–4 cite them)* · §8's readiness headline · the map's dependency ledger · the `PBDIGIT-72` / `V-8` recommendation.
⚠️ **On totality specifically:** the map declares the pre-establishment case as a **documented precondition** and logs it as `BND-1`-dependent (§F, §I) rather than encoding it. **That is a shape question, not a naming/placement question, and it is outside `G-2a`.** It remains **Gate 2's** business to verify the declaration is carried faithfully, and it remains `BND-1`-dependent. **My silence on it is not approval of it.**

---

## 6 · Observations — non-blocking, and none of them changes the verdict

### 6.1 The `Port/` interfaces are **supplier-shaped**; `RecordedOperationalStatus` is **subject-shaped**

All four existing interfaces name a *capability or actor*: `InstantSource`, `ProtocolAppend`, `ServicePolicySnapshot`, `OrganisationalAppointmentAuthority`. `RecordedOperationalStatus` names the *subject* — and sits one adjective away from the value object it returns (`ElectionOperationalStatus`). **Residual drift risk: a later reader could take the interface for the overlay's own type/abstraction and reason from there into `BND-3`.**
⚖️ **Weighed and not made a condition.** Boundary neutrality does not turn on it; the single retrieval operation and §5.1's negative list contain the drift; and **replacing the candidate name would leave Gate 2 verifying a candidate that no longer exists.** ⛔ **Architecture confirming a narrow point must not redesign it.** *Recorded so the observation is on the record rather than discovered later.*

### 6.2 ⚠️ A label in the estate that a later lane could quote against the word *"Recorded"*

`tests/…/StructuralApplicationGuardsRedTest.php:228` reads *"**W-4 / DD-1: no application class HOLDS a derived classification** — returned, never stored"*, and its regex covers **`ElectionOperationalStatus` and `HaltedAtGate`** alongside `GateIntervalState` and `ClockReading`. **But `HaltedAtGate.php:11` calls itself *"the recorded fact"*, and `GateIntervalState.php:8` is the one that is *"DERIVED"*.** ⇒ **The guard's *label* miscategorises two recorded facts as derived classifications.** The **rule** it enforces is sound (`DD-1`: no second truth held in the Application, whether derived or recorded); only the wording is loose. ⛔ **Not corrected here — this lane is read-only on `tests/`, and it is not this commission's scope.** **Routing it is the PO/ARB's call.** ⚠️ Recorded because a later lane could cite that label as evidence that the estate treats the overlay as *derived*, and thereby argue against the confirmed name on a false premise.

### 6.3 Two inaccuracies in the untracked `2026-08-18-EM-DOM-001-architecture-review.md`

Cited, **not committed** — it belongs to another session.
1. Its §4.1 (line 140) counts *"six ports"* in `Domain/OperatingCore/Port/*`. **The directory holds four interfaces and three value/enum types**, and the "six" of the grant is the collaborator set (§4.7). *(Same correction as V-9, reached here from the grant's enumeration.)*
2. Its §4 (line 129) places the contract in `Repository/`. **Superseded by `G-2a` and by that review's own §4.1 artifact-class row** (§2 above).
**Neither error affects its rulings this lane relies on**, and its §5.1 projection elimination is **not** adopted here (§4.4).

---

## 7 · The five non-determinations — explicit

| # | This confirmation … |
|---|---|
| **1** | **does NOT resolve `BND-1`.** No lifecycle-phase owner is named, no phase concept introduced, no Published-Language relationship created with `App\Domain\Election\*`, no `ElectionLifecycleState` imported, and `AG-2` establishment is not declared a phase marker. **`BND-1` remains OPEN — D3 stands untouched.** |
| **2** | **does NOT resolve `BND-3`.** No boundary is selected; **all three candidates remain open and all three satisfy the confirmed contract** (§4.1). Neither the distinct-aggregate, the lifecycle-aggregate, nor the projection reading is approved or rejected — including the projection elimination argued in another session's untracked review. **`BND-3` remains OPEN — D4 stands untouched.** |
| **3** | **does NOT establish aggregate ownership.** No aggregate is created, named, implied or denied; no root, identity, transaction or consistency boundary is declared; the principle **"identity + persistence does not imply aggregate"** is preserved intact. |
| **4** | **does NOT approve `R-1`/`R-2`/`R-3` beyond the bounded Act-B contract.** `R-1`'s **save** half and its final form remain unauthorized (act C; Appendix Q item 2); `R-2` and `R-3` are untouched; `ElectionRestored`'s `w8` representation remains unselected (**D2 selects none**). |
| **5** | **does NOT authorize implementation beyond the existing `EM-DOM-001` Act-B authorization** (registered `117536f3`). ⛔ No persistence or adapter (act C) · no Application call site or wiring (act D) · no modification of any existing repository interface (act A) · no new identity/persistence model (act E) · no UC-1/UC-2/UC-3 normalization · no `GREEN-5` · **and Appendix Q's eight-item negative list remains fully operative.** |

⛔ **Additionally: this is not an acceptance of the Phase-2A design map.** That document remains **evidence evaluated**, not a design approved; **its §§1–7 acquire no authority from this confirmation**, and only the naming/placement candidate in §F is confirmed.

---

## 8 · State after this gate

```
Gate 1 · Architecture confirmation (G-2a)   ✅ CONFIRMED — this document
Gate 2 · Independent verification            ⬜ STANDS — declined as a waiver by the PO/ARB; not this lane
        │                                       (⛔ eligibility: not this lane either, once it has ruled)
        ▼
both gates ⇒ the ALREADY-REGISTERED Act-B authorization (117536f3) fires — no new PO/ARB act
        ▼
H-1 structural RED (fails by ABSENCE)  →  create RecordedOperationalStatus  →  Act-B GREEN  →  STOP
```

⚠️ **Unchanged by this confirmation, and restated so it is not lost:** **`GREEN-5` stays STOPPED** — gate `G-6`: `D3`'s deferral keeps the ADR-1 §6(c) normalization slice shut, and **completing Act B perfectly leaves it shut.** **Act B yields a contract nothing may implement yet and nothing may call yet.** Its value is that the domain finally *states what must be retrievable*.

⛔ **Nothing is implemented by this document. No code, no test, no ADR, no authorization changed. This lane does not verify its own ruling (`EP-02` / `R-34`) and is not eligible for Gate 2.**

---

**Traceability:** commission `2026-08-18-EM-DOM-001-act-b-gate-commissions.md` §"GATE 1" *(PO-drafted wording adopted as this lane's verdict)* · `D1`–`D4` verbatim + **Appendix Q (live)** + Appendix R *(recommendation only)* — `2026-08-18-EM-DOM-001-decision-recording-surface.md` · post-decision Rule-8 gate **`G-1`…`G-8`, `G-2a` decided here** · Phase-2A map §§C/F/G/I/J *(evidence, not premise; §8 headline not adopted)* · `2026-08-18-EM-DOM-001-architecture-review.md` *(UNTRACKED, another session's — cited, two corrections in §6.3, its `Repository/` placement rejected, its §5.1 not adopted)* · `ADR_20260817_2145` §2 `C-2` + §6 + constraint ⑥ + protected line §"What this ADR protects" · `ADR_20260817_2300` §6(a)/(b)/(e)/(g)/(h) · `2026-08-17-EM-IMPL-002-q-restore…` §1 *(the six-port enumeration)* · Act-B authorization `117536f3` · repo **Rule 9** (`CLAUDE.md`) · `ES-005.4` · `EM-GOV-005`/`059(b)(c)`/`062`/`068` · `DD-1` · P-7 · **code inspected directly:** `Port/{InstantSource,ProtocolAppend,ProtocolEntry,RefusalRecord,ServicePolicySnapshot,OrganisationalAppointmentAuthority,HistoryKind}.php`, `Repository/{ElectionCommittee,AcceptanceGateDecision,RecoveryProcess}Repository.php`, `Condition/{ElectionOperationalStatus,HaltedAtGate,GateIntervalState}.php`, `Policy/ResumptionTarget.php`, `StructuralGuardsTest.php:39-65`, `StructuralApplicationGuardsRedTest.php:107-151,228-240` · baseline `1f4b4c5f` *(byte-identical)* · HEAD `c6a2a0a3`.
