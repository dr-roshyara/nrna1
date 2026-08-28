# `EM-DOM-001` — **Independent Architecture / ARB review**: BND-1 · BND-2 · BND-3

**Date:** 2026-08-18 · **Role:** independent Principal Architect / ARB reviewer — **not** the Domain lane, **not** the Application lane, **not** Governance
**Subject:** the three questions referred by `2026-08-18-EM-DOM-001-architecture-referral.md`
**Type:** 🔴 **READ-ONLY ARCHITECTURE RULING.** No code, test, ADR, Domain artifact or authorization was changed. **Nothing was committed.**

> **Process identity, self-declared and NOT attestable** (`INV-ATTR-2`/`G-2`): `claude-code-session:1c8b041b`.
> **Prior contact with `EM-DOM-001`, disclosed:** this process recorded **PO/ARB Amendment 1** (obligation (9) / `DEP-10`) earlier today — a governance recording act. **It did not author the Phase-1 decision map, proposed no domain model, and had not read Phase 1 before this review.** It is not the Domain lane and not the Application lane.
> ⚠️ **A correction this review owes on its own earlier work is recorded in §6.4 — the Amendment-1 note under-counted the protected `DEP-10` sites.**

---

# Executive Verdict

> # 🔴 **BLOCKED — architectural questions remain unresolved.**
>
> **`BND-1` is not merely unanswered; it is BLOCKED BY A STANDING DECISION, and no evidence in this repository can unblock it.** **`BND-3` is blocked on `BND-1` as the referral predicted — but three of its four candidate boundaries are eliminated here on evidence, leaving a binary rather than an open field.** **`BND-2` is ruled on its architectural half and formally referred on its authorization half, which is not Architecture's to decide.**

**The evidence supports Phase 1's factual base and does not support treating `R-1`/`R-2`/`R-3` as approved design.** Two findings from the source change the picture materially and neither is in the referral:

**① `GateDesignation` carries order and no subject — by decision.** Its docblock: *"these values carry order only, never a subject. **Binding a concrete first gate is a configuration/deployment-side act, separately not authorized.**"* **The `DEP-2` discriminator that `BND-1` asks for is exactly a gate↔lifecycle-phase binding.** ⇒ **Answering `BND-1` inside `EM-DOM-001` would perform, as a side effect, the binding act a standing decision reserves — and `EM-OPEN-055`, the acceptance question that would settle it, is still 🔴 OPEN.**

**② The `w8` gap is wider than reported, and the code says why.** `ElectionRestored.$returnsToGate` is non-nullable — **and in the `w8` path no `HaltedAtGate` is ever recorded at all**, so `P-7` has no input and the field has no legitimate filler. `FillCommitteeSeatHandler` therefore substitutes `$decision->gate()` (`DEP-7`). ⇒ **`DEP-7` is not merely a wrong source for a right answer; in this path there is no right answer, and the non-nullable field is what creates the fabrication pressure.** ⇒ **Making `P-7` reachable (`R-1`) does not discharge `DEP-6` for `w8`, because `P-7` is inapplicable there.**

⛔ **This verdict is not "the Domain report is weak."** Phase 1 is a strong analysis and its central discovery is confirmed by independent inspection. **The block is upstream of it.**

---

# Section 1 — Evidence reviewed

### 1.1 Authoritative architecture / governance evidence

`ADR_20260817_2145_Aggregate_Absence_Semantics.md` §6 (a)–(d) + the nine binding constraints · `ADR_20260817_2300_Recovery_Origin_Provenance_Ownership.md` §6 (a)–(h) · `2026-08-17-EM-ARCH-001-model-a-operating-core-design.md` §5d (the state machine) · `ELECTION_MANIFESTO.md` — `EM-GOV-016`, `059`, `062`, `063`, `064`, `065`, `066`, `068`; `EM-OPEN-054` (resolved in substance), **`EM-OPEN-055` (🔴 OPEN)**, `EM-OPEN-108` (resolved).

### 1.2 Governance / authorization evidence

`2026-08-18-EM-DOM-001-authorization-request.md` — the verbatim PO/ARB act, **PO/ARB Amendment 1**, Annotations A and B · `2026-08-18-EM-DOM-001-domain-lane-briefing.md` · `2026-08-18-EM-DOM-001-architecture-referral.md`.

### 1.3 Domain Phase-1 evidence *(accepted as analysis; its proposals are NOT premises)*

`2026-08-18-EM-DOM-001-phase1-domain-decision-map.md` — §0 central discovery, §1 DEP-1…DEP-6, §2 ownership map, §6 `R-1`/`R-2`/`R-3`, §8 BND-1/2/3.

### 1.4 Implementation evidence — **inspected directly, not taken from any document**

| Artifact | What was checked |
|---|---|
| `Condition/ElectionOperationalStatus.php` | `final readonly`, **private constructor**, static factories, `?HaltedAtGate`, `becameInoperative()`, `restored()` |
| `Condition/HaltedAtGate.php` | the docblock's canonical-home assertion; `GateDesignation` + `RecordedInstant` |
| `Condition/GateIntervalState.php` | *"**DERIVED** on recorded facts, **never stored** as authoritative state"* |
| `Gate/GateDesignation.php` | ⭐ *"order only, never a subject… **separately not authorized**"* |
| `Gate/AcceptanceGateDecision.php` | `intervalState(ElectionCommittee $committee)` — an instance method |
| `Policy/ResumptionTarget.php` (P-7) | `resolve(HaltedAtGate): GateDesignation` |
| `Policy/ExpiryConsequence.php` (P-6) | the producer precedent |
| `Event/ElectionRestored.php` | ⭐ `public GateDesignation $returnsToGate` — **non-nullable** |
| `Repository/*.php` (×3) | domain-owned, persistence-independent **interfaces**; *"no adapter exists in this increment"* |
| `Handler/FillCommitteeSeatHandler.php` | `$conditionWasActive = $committee->unableToFunction(...)`; `recordRestorationConsequences(..., $decision->gate(), ...)`; the `Q-RESTORE` docblock |
| `Handler/RecordVacancyEventHandler.php` | `InoperativeOnset::onsetFor($committee, $required, ...)` — **no halt consulted** |
| `App\Domain\Election\Constitution\ElectionConstitution` | `const RULES` registry; **grep for halt/gate/operative/inoperative ⇒ 0 matches** |
| `App\Domain\Election\Enum\ElectionLifecycleState` | Draft · SubmittedForApproval · Approved · Rejected · SetupAdministration · SetupNomination · ReadyForVoting · VotingActive · Counting · ResultsPublished · Archived · Suspended |
| **cross-context coupling** | ⭐ `grep "App\Domain\Election" app/Contexts/Election` ⇒ **ZERO matches** |

---

# Section 2 — Independence assessment

**Where I agree with the Domain lane, and verified independently rather than accepting:**

✅ **The central discovery is real.** `ElectionOperationalStatus` has **exactly one** reference in `app/` — its own declaration. **Zero consumers.** AG-1/AG-2/AG-3 each have a repository interface; the overlay has none. *(`Observed`.)*
✅ **`ElectionRestored.$returnsToGate` is non-nullable** and the state can represent what the event cannot.
✅ **The `HaltedAtGate` / `ElectionConstitution` discrepancy is real**, and worse than reported — §6.
✅ **`DEP-10` is legitimate class-A**, and the referral's count of **4 sites across 2 files** is correct — §6.4.
✅ **`P-7` is settled**: consume, never duplicate, never loosen to `null`.

**Where I depart from the framing (not from the facts):**

🔴 **`BND-1` is presented as an open ownership question with three readings. It is not open in that sense — it is FORECLOSED.** All three readings require the gate↔phase binding that `GateDesignation`'s own decision withholds and `EM-OPEN-055` leaves undecided. **§3.**
🔴 **The referral describes `DEP-7` as *"UC-3 substituting `$decision->gate()` for P-7's answer."* In the `w8` path P-7 has no answer to substitute for.** The substitution is a symptom of the non-nullable field, not of a missing lookup. **§5.3.**
🟡 **`R-1` is characterised as unblocking `DEP-6`. It unblocks `DEP-6` only for the halted path.** For `w8` it changes nothing. **§9.**

**Where I reach no conclusion, and say so:** the overlay's boundary between *distinct aggregate* and *part of a lifecycle concept the OperatingCore does not yet have* — **§5.4.**

⛔ **`R-1`, `R-2` and `R-3` were treated as evidence of the Domain lane's current thinking and at no point as premises.** Each is ruled in §9, and none is ruled Approved.

---

# Section 3 — `BND-1` ruling · lifecycle ownership

> ## 🔴 **RULING: UNRESOLVED — and FORECLOSED, not merely unanswered. `BND-1` cannot be answered inside `EM-DOM-001` by any actor without performing a reserved act.**

**OWNER:** ⛔ **None. No bounded context currently owns "lifecycle phase" for the OperatingCore, and the absence is DELIBERATE.**
**BOUNDED CONTEXT:** ⛔ **Not assignable on this evidence** — see the three eliminations below.
**INVARIANT:** ADR-1 §6(a) row 3 — *"the election has not yet reached the lifecycle phase in which an acceptance decision exists"* ⇒ absence is a **legitimate lifecycle state**; row 2 — *"the command requires an already-established constitutional decision"* ⇒ absence is a **violation**. **The discriminator must answer: *at this point in the election's life, ought a decision for this gate to exist?***
**EVIDENCE:** below, all `Observed`.
**CONSUMPTION RELATIONSHIP:** ⛔ **None exists.** `grep "App\Domain\Election" app/Contexts/Election` returns **zero matches** — the two contexts have **no** relationship: not shared kernel, not customer/supplier, not conformist, not published language, not ACL. **Reading (b) would CREATE a context map from nothing, not use one.**
**AUTHORIZATION CONSEQUENCE:** **`DEP-2` is outside `EM-DOM-001`'s reach.** Proceeding would breach the standing gate-binding reservation.

### 3.1 The three candidate discriminators, each eliminated on evidence

**Reading (a) — a new OperatingCore phase concept.** ⛔ **This is the reserved act itself.** `GateDesignation`'s docblock is explicit: *"these values carry order only, never a subject… **Binding a concrete first gate is a configuration/deployment-side act, separately not authorized.**"* **To say "the phase for gate N has been reached" is to give gate N a subject and a position in the election's life — precisely the binding withheld.** And `EM-OPEN-055` — *"the ONLY live acceptance question"* — is 🔴 **OPEN**. **Architecture cannot rule what governance has explicitly not yet decided.**

**Reading (b) — Published Language with the legacy context.** ⛔ **Eliminated on two independent grounds.**
&nbsp;&nbsp;**(i) Wrong axis.** `ElectionLifecycleState` enumerates an **administrative/operational workflow** — Draft → SubmittedForApproval → Approved → SetupAdministration → SetupNomination → ReadyForVoting → VotingActive → Counting → ResultsPublished → Archived. **Not one case names an acceptance gate, a halt, or an operational condition.** The OperatingCore's gates are `First`/`Second` **with no subject**; mapping them onto this enum *is* the reserved binding, now performed across a context boundary where it is even less visible.
&nbsp;&nbsp;**(ii) No relationship exists to extend.** Zero references in either direction. **Introducing one is a strategic context-map change** — `AIP-14` territory, requiring Phase-2 Strategic-DDD answers the slice has not produced. ⚠️ **The referral's prohibition on "importing `ElectionLifecycleState` because it exists" is correct, and this is the architectural reason: it is not merely ownership by convenience — it is the wrong axis in a context with no map.**

**Reading (c) — AG-2's establishment is itself the phase marker.** ⛔ **Eliminated as logically circular.** If *"no `AcceptanceGateDecision` record exists"* means *"the phase has not been reached"*, then ADR-1 row 2 (*required-but-absent ⇒ violation*) **can never fire**, because every absence is classified as row 3. **The reading collapses the two meanings ADR-1 §6(a) exists to keep apart**, and ADR-1's constraint ④ forbids exactly this: *"a nullable `find()` result is a technical observation, not a domain classification."*

### 3.2 A fourth candidate, tested and also eliminated

**`GateIntervalState::Open` — *"reached, undecided"* — reads like the phase marker.** ⛔ **It cannot be, for two reasons, both from the source:**
1. **It is computed from an existing decision:** `AcceptanceGateDecision::intervalState(ElectionCommittee $committee)` is an **instance method**. **A classification that requires the aggregate to exist cannot classify that aggregate's absence.** Circular, like reading (c).
2. **It is forbidden to be recorded:** *"**DERIVED** on recorded facts, **never stored** as authoritative state… **storing it would create the determiner the rule excludes**"* (`EM-GOV-068`; `EM-ARCH-001` P-2, DD-1). **A discriminator that may not be recorded cannot be the authoritative supplier of a domain classification.**

### 3.3 What is actually missing, stated so the PO/ARB can act on it

> **The OperatingCore models WHICH gate (`GateDesignation`) and the gate's OWN VERDICT (`GateIntervalState`). It models nothing that says WHEN a gate becomes due.** **`DEP-2`'s discriminator is that third thing, and it is absent by construction rather than by oversight** — the subject-abstraction decision removed it deliberately, and `EM-OPEN-055` is where it would be restored.

**⇒ `BND-1` is not an Architecture question that Architecture is refusing. It is a GOVERNANCE question wearing an architecture costume.** The act that answers it is a ruling on `EM-OPEN-055` / the gate-subject binding — **not a boundary choice.**

---

# Section 4 — `BND-2` ruling · authorization boundary

> ## 🟡 **RULING: ARCHITECTURAL HALF RESOLVED · AUTHORIZATION HALF FORMALLY REFERRED.**
> **Architecture can say what kind of artifact is required. It may not say what the PO/ARB's word "repositories" covers — that is a reading of the act, and the act's author is the only one who may give it.**

**AUTHORIZED SCOPE (architectural finding):** the overlay's missing element is a **domain-owned, persistence-independent contract** — the same artifact class as the three that already exist.
**NOT AUTHORIZED:** any Application-layer change · any protocol read · any infrastructure adapter · any modification of the three existing repository interfaces · and **any resolution of the textual ambiguity by Architecture.**
**WHY:** below.
**DOMAIN/APPLICATION BOUNDARY:** the contract would live in `Domain/OperatingCore/Repository/` and be **consumed** by the Application layer, never defined by it — matching ADR-1 §6(b) and ADR-2 §6(e).
**CONSEQUENCE FOR `EM-DOM-001`:** ⚠️ **if the answer is "out of scope", `DEP-5b` and `DEP-6`(halted path) are not deferred — they are UNACHIEVABLE under the current prohibition set, and that must be an explicit decision rather than a discovered dead end.**

### 4.1 The five terms are not interchangeable — the distinction the act turns on

| Term | What it is **in this codebase**, verified |
|---|---|
| **domain repository** | an `interface` in `Domain/OperatingCore/Repository/`, *"Persistence-independent contract"*, `find()`/`save()` over an aggregate. **A DOMAIN CONTRACT.** |
| **persistence mechanism** | ⛔ **does not exist** — *"No storage technology is chosen (G-6); **no adapter exists in this increment**."* |
| **retrieval contract** | the `find()` half of the interface |
| **Application-facing contract** | the same interface, injected into handlers by constructor |
| **domain-owned port** | `Domain/OperatingCore/Port/*` — six ports, a **different** artifact class from Repository |
| **infrastructure implementation** | `Infrastructure/Repository/` holds exactly one class, `CompositeElectionRepository`, **for the legacy context — none for the OperatingCore** |

> ⭐ **The architectural finding: in the OperatingCore a "repository" is a DOMAIN CONTRACT, not an infrastructure artifact.** All three are interfaces in `Domain/`, and **none has an implementation anywhere.** ⇒ **The word "repositories" in the authorization is doing two jobs at once, and the act does not say which it means.**

### 4.2 The five questions the referral poses, answered where evidence permits

1. **Already representable?** ✅ **Yes.** `ElectionOperationalStatus` exists, is authorized, and holds `?HaltedAtGate` with the `EM-GOV-059(b)` retention semantics. **The concept is not missing.**
2. **Already-authorized contract exists?** ⛔ **No.** Zero repositories, zero ports, zero producers, zero consumers. *(`Observed`.)*
3. **Does the authorization permit introducing the missing Domain contract?** ⚠️ **THIS IS THE REFERRED QUESTION.** The act forbids *"change repositories"*; the artifact required is a **new** domain-owned interface, not a change to an existing one. **"Change" and "create" are different acts, and reading the act's prohibition as covering both — or as covering neither — is a decision about what the PO/ARB meant.** ⛔ **Architecture declines to supply that meaning.** ⚠️ **The competing textual pressure is real and must be put in front of the decider:** ADR-2 §6(e) requires *"confirmation that the resulting contract can be consumed by the Application layer"*, which **presupposes a loadable contract**.
4. **Additional authorization required?** **Yes, unless the PO/ARB rules that (3) is already inside scope.** One sentence settles it either way.
5. **Otherwise impossible?** ✅ **Yes, and this is the load-bearing consequence.** Every alternative supplier of `HaltedAtGate` is already prohibited: Application construction (`DEP-9`) · protocol reconstruction (`DEP-8`) · `$decision->gate()` (`DEP-7`) · copying the halt onto AG-3 (two owners — ADR-2 §6(g)). ⇒ **With no retrieval contract there is no legitimate supplier, `P-7` stays unreachable, and `DEP-5b` cannot be discharged by any means the current prohibitions allow.**

### 4.3 What Architecture rules, plainly

✅ **The required artifact is a domain contract, and creating one is a Domain-model act, not an infrastructure act.** That is an architectural fact and it is ruled.
⛔ **Whether the act's prohibition reaches it is not ruled, and must not be inferred from the above.** **Do not read §4.1 as permission.**

---

# Section 5 — `BND-3` ruling · operational-overlay boundary

> ## 🟡 **RULING: PARTIALLY RESOLVED — three of four candidates ELIMINATED on evidence; the remaining binary is BLOCKED on `BND-1`.**

**BOUNDARY:** ⛔ **not selected** — see §5.4.
**OWNER:** the concept's *meaning* is owned by whichever context owns lifecycle transitions — **which `BND-1` shows is currently nobody.**
**INVARIANT:** `EM-GOV-059(b)` — **becoming Inoperative RETAINS the halt**; `EM-GOV-059(c)` — restoration returns to the prior condition.
**CONSISTENCY BOUNDARY:** ⛔ **not determinable without `BND-1`** — §5.4.
**PERSISTENCE REQUIREMENT:** ✅ **RULED — the retained halt MUST be recorded truth.** §5.1.
**WHY ALTERNATIVES ARE REJECTED:** §5.2.

### 5.1 ✅ Ruled: the overlay is **recorded truth**, not a derived value

**The model states its own principle** (`EM-GOV-068`, `EM-ARCH-001` P-2, DD-1): **derived classifications are never stored; recorded facts are.** `GateIntervalState` is on the derived side. **`HaltedAtGate` is on the recorded side by its own docblock — *"The recorded fact that progression is HALTED"*, carrying a `RecordedInstant`.**

**And retention settles it decisively.** `becameInoperative()` **retains** the halt while the Committee cannot function. If the halt were recomputed per request from committee arithmetic, **it would change during exactly the interval `EM-GOV-059(b)` requires it to be held fixed.** ⇒ **a recomputed value cannot satisfy the retention invariant.** ✅ **This eliminates the projection/read-model candidate on evidence, not on preference.**

### 5.2 ✅ Ruled: three candidates eliminated

| Candidate | Verdict | Ground |
|---|---|---|
| **Projection / read model** | ⛔ **ELIMINATED** | retention cannot be recomputed (§5.1); and the model's own rule puts stored ⇒ not derived |
| **Part of AG-3 `RecoveryProcess`** | ⛔ **ELIMINATED** | AG-3 owns the causal **class** of a period (`PeriodKind`), not the halt; placing the halt there creates **two owners** — ADR-2 §6(g): *"Reference is not ownership."* Also `w8` proves restoration occurs **with no `RecoveryProcess` at all**, so an overlay hosted in AG-3 would be unreachable in exactly the path that needs it |
| **Part of AG-2 `AcceptanceGateDecision`** | ⛔ **ELIMINATED** | AG-2's own interval state is **derived and unstorable**; and the overlay must survive independently of any one gate decision. Hosting it in AG-2 also reinstates `$decision->gate()` as the halt's source — **which is `DEP-7`** |
| **Distinct aggregate** *vs* **part of a lifecycle concept the OperatingCore lacks** | 🔴 **BLOCKED** | §5.4 |

### 5.3 ⭐ A finding the referral does not contain — **the `w8` gap is wider, and the code says why**

**Verified path, from `EM-ARCH-001` §5d and the handlers:**

```
OPEN (reached, undecided, NOT halted — no HaltedAtGate is ever recorded)
   │  vacancy event: non-vacant < required          [EM-GOV-065]
   ▼
INOPERATIVE  (ElectionBecameInoperative — produced from InoperativeOnset::onsetFor(committee, required, vacatedAt);
              no halt is consulted, verified in RecordVacancyEventHandler)
   │  seats filled to sufficiency
   ▼
OPERATIVE, gate OPEN again                          [EM-GOV-059(c)]
   └─▶ ElectionRestored( electionId, GateDesignation $returnsToGate ← NON-NULLABLE, restoredAt )
```

> ### 🔴 **In this path no `HaltedAtGate` exists anywhere. `P-7` has no input. `$returnsToGate` has no legitimate filler.**
> **`GateIntervalState::Unachievable` is *derived, reversible and unstorable*; `HaltedAtGate` is *recorded, retained and instant-stamped*. They are not the same fact, and the vacancy-arithmetic path produces only the former.**
>
> **So `FillCommitteeSeatHandler` passes `$decision->gate()` — and its own docblock already concedes the insufficiency:** *"It names the election's ESTABLISHED acceptance decision as the return target — exact in Model A… and **INSUFFICIENT the day two designations are established at once**."*

**Three consequences, and each changes a stated position:**

1. **ADR-2 §6(h)'s conditional antecedent is SATISFIED**, and this review establishes it on evidence: *"If the existing non-nullable `GateDesignation` requirement is incompatible with the approved causal model…"* — **it is.** ⇒ **A domain-model gap is confirmed, not merely suspected.**
2. **`DEP-7` is mis-framed as a substitution.** There is no P-7 answer to substitute for. **The non-nullable field is the cause, and the handler's reach for the nearest gate is the effect.** ⇒ **Repairing `DEP-7` without ruling `R-3` would only relocate the fabrication.**
3. ⚠️ **`R-1` does not unblock `DEP-6` for `w8`.** Giving the overlay identity and retrieval makes `P-7` reachable **for the halted path only.** In the `w8` path `P-7` is **inapplicable**, not unreachable. ⇒ **`R-1` and `R-3` address different halves of `DEP-6`, and the Phase-1 framing that `R-1` "unblocks DEP-6" is too strong.**

### 5.4 🔴 Why the remaining binary is genuinely blocked

**The two survivors are:** **(A)** a distinct aggregate owning `{ retained halt, operational condition }`, and **(B)** part of a lifecycle concept the OperatingCore does not yet have.

**Choosing requires knowing what else must change atomically with the operational condition — DDD Test 4.** And the only candidate co-invariant is the lifecycle-phase transition, **whose owner `BND-1` shows is unassigned.**

> **If lifecycle phase turns out to be owned by a concept not yet modelled, the overlay is plausibly part of it (B). If phase ownership is ruled to sit outside the OperatingCore entirely, the overlay stands alone (A).** **The same unanswered question decides both.** ⛔ **Selecting now would fix the seam before knowing where the seam is** — the referral's own warning, and it is correct.

**What I can add, so the eventual choice is cheaper:** the type is today a `final readonly` **value object with a private constructor and no identity field**. **Neither (A) nor (B) is foreclosed by that** — the value could become an aggregate's state or an aggregate's own representation. ✅ **No current code prejudices the choice, because nothing consumes it.** *(The window is open now and closes the moment a consumer is written.)*

---

# Section 6 — `HaltedAtGate` / `ElectionConstitution`

> ## **RULING: (A) the documentation is wrong — AND (E) it conceals a genuine context-boundary problem. Architecturally significant; NOT repaired here.**

### 6.1 Verified

`Condition/HaltedAtGate.php` asserts: *"Lifecycle transitions themselves stay canonically homed in `ElectionConstitution`."* **`App\Domain\Election\Constitution\ElectionConstitution` is a `const RULES` registry of administrative workflow transitions** (`submit_for_approval`, `approve`, `reject`, `begin_setup`, …). **`grep -ci "halt|operative|inoperative|gate"` ⇒ 0.** *(`Observed`.)*

### 6.2 Why (A) alone understates it

**The named home is in a different bounded context** — `App\Domain\Election\*` vs `App\Contexts\Election\*` — **and there are ZERO references between them in either direction.** ⇒ **The docblock asserts a canonical home that (i) does not contain the concept, (ii) lives in another context, and (iii) has no relationship of any kind to the asserting context.**

> ### ⭐ **The architectural significance: this sentence is the only thing that made the OperatingCore's missing lifecycle owner look assigned.** Remove it and `BND-1`'s gap is visible on inspection. **It is a comment standing in for a context map** — and it is why `BND-1` was discovered late, during a dependency gate, rather than during modelling.

### 6.3 Recommended correction — **reported, deliberately not applied**

**Do not simply delete the sentence.** Deleting it removes the false claim **and** the evidence that the gap was concealed. **Recommend: the docblock is corrected only as part of the act that rules `BND-1`**, so the record shows the home was named, found absent, and then assigned — **or explicitly left unassigned.** ⛔ **A silent edit would erase the trail.**

### 6.4 ⚠️ A correction this review owes on its own earlier work — **`DEP-10` protects FOUR sites, not two**

**PO/ARB Amendment 1, recorded earlier today by this same process, stated that `DEP-10`'s guard exists at *two* sites. That was incomplete. Verified now:**

| Site | Guard | Period |
|---|---|---|
| `FillCommitteeSeatHandler.php:174` | `if ($restoration === null)` | `PeriodKind::CommitteeRestoration` |
| `FillCommitteeSeatHandler.php:192` | `if ($halted === null)` | `PeriodKind::HaltedElectionRecovery` |
| `RecordVacancyEventHandler.php:161` | `if ($halted === null)` | `PeriodKind::HaltedElectionRecovery` |
| `RecordVacancyEventHandler.php:174` | `if ($restoration === null)` | `PeriodKind::CommitteeRestoration` |

**All four are the same class-A semantic** — *"there is nothing to pause / nothing to resume"*, grounded in `EM-GOV-062`. **The referral's count of "4 protected sites across 2 files" is correct and the Amendment note's "two sites" was not.** ⚠️ **This matters: an under-counted protection is a partial protection, and partial protection of a symmetry-resistant semantic is exactly how "normalize for symmetry" creeps in.** **Recommend the Amendment record be corrected to four; that is a Governance act and is not performed here.**

---

# Section 7 — Cross-ADR consistency

| Against | Consistent? | Note |
|---|---|---|
| **ADR-1 §6(a)** | ✅ | The ruling preserves all four absence meanings and refuses to collapse rows 2 and 3 — §3.1(c) eliminates the only reading that would have |
| **ADR-1 constraint ④** | ✅ | *"a nullable `find()` result is a technical observation"* — the ground on which reading (c) falls |
| **ADR-1 constraint ⑥** | ✅ | *"if the required domain contract does not exist, implementation stops and a separate domain slice is required"* — **§3 finds a case where even the separate slice is insufficient, because the missing thing is a governance ruling, not a contract** |
| **ADR-2 §6(a)** | ✅ | *why restoration is permitted* · *why a RecoveryProcess exists* · *where restoration returns* kept distinct throughout; §5.3 **strengthens** the separation by showing the third is undefined in `w8` |
| **ADR-2 §6(b)** | ✅ | No fabricated halt, no fabricated `RecoveryProcess`, no sentinel, no *"unknown"*. §5.3 identifies the existing fabrication (`$decision->gate()`) rather than adding one |
| **ADR-2 §6(g)** | ✅ | *"Reference is not ownership"* — the ground for eliminating the AG-3 candidate |
| **ADR-2 §6(h)** | ⚠️ **ADVANCED** | Its antecedent is **conditional** and nobody had established it. **§5.3 establishes it.** ⇒ the domain-model gap is now **confirmed** |
| **`w8`** | ✅ | Explicitly accounted for, and shown to be **wider** than the referral states |
| **`P-7`** | ✅ | Not duplicated, not loosened, no alternative resolver proposed. **§5.3 notes it is *inapplicable* — not deficient — in `w8`** |
| **`DEP-10`** | ✅ | Preserved, and the protected set **corrected upward** (§6.4) |
| **Protocol boundary** | ✅ | No protocol read proposed; no reconstruction; no event-sourcing vocabulary used |
| **`EM-GOV-068`** | ⚠️ **CONTRADICTION SURFACED** | `068` says progression is HALTED on *"mathematical impossibility on recorded facts"*, yet the vacancy-arithmetic path records **no `HaltedAtGate`** and returns the gate to OPEN. **Either `068`'s "HALTED" is a derived notion distinct from the recorded `HaltedAtGate` fact, or a halt record is missing.** **Referred, not resolved — it bears directly on `BND-3`'s persistence question** |

---

# Section 8 — Consequences

**Stated as consequences. Nothing here is authorized, proposed as a next action, or begun.**

**Domain consequence.** `DEP-1`, `DEP-3`, `DEP-4` stand as Phase 1 leaves them. **`DEP-2` is out of reach until the gate-subject question is ruled.** `DEP-5b` awaits `BND-2`. **`DEP-6` splits: the halted half awaits `BND-2`; the `w8` half awaits a ruling on `ElectionRestored`'s representation and awaits nothing else** — it is blocked by neither `BND-1` nor `BND-3`. ⇒ **the `w8` representation is the only part of this slice that could proceed on its own, and only if separately authorized.**

**Application consequence.** UC-3 stays frozen (ADR-2 §6(f)). **`DEP-7` must not be repaired before `R-3` is ruled** — §5.3(2): repairing the lookup without fixing the field relocates the fabrication rather than removing it.

**Authorization consequence.** **Two acts are required and neither is Architecture's:** a ruling on the gate↔phase binding (`BND-1`, and it reaches `EM-OPEN-055`), and a reading of *"change repositories"* (`BND-2`). ⚠️ **If `BND-2` is answered "out of scope", `DEP-5b`/`DEP-6`(halted) are unachievable under the current prohibitions — an explicit decision, not a dead end discovered later.**

**Testing consequence.** **No `DEP-2` RED test can be written**, because the invariant has no owner to assert against. A `w8` RED test **is** writable — it asserts that a restoration with no halt is representable without fabrication. ⛔ **`AbsentAggregateReferenceRedTest` stays RED and is not the target of any of this.**

---

# Section 9 — `R-1` / `R-2` / `R-3` status

| Proposal | Status | Evidence |
|---|---|---|
| **`R-1`** — overlay identity + retrieval contract | 🟡 **OPEN — substance confirmed, form and authorization unresolved** | ✅ The **need** is ruled: retention requires recorded truth (§5.1), and every alternative supplier is prohibited (§4.2·5). ⛔ Its **form** is `BND-3`-blocked; its **permissibility** is `BND-2`-referred. ⚠️ **And its stated benefit is overstated: it does not unblock `DEP-6` for `w8`** (§5.3·3) |
| **`R-2`** — domain policy producing `ElectionRestored`, resolving via P-7 | 🟡 **MODIFIED — pattern endorsed, scope narrowed** | ✅ The precedent is real and verified: **P-6 `ExpiryConsequence` already produces lifecycle events from `(halt, RecoveryProcess, condition)`**, and the two events the ADRs concern are exactly the two that bypass it. ⛔ **But P-7 cannot be the target resolver in `w8`, where no halt exists** — so `R-2` as written covers only the halted path. **It must be re-scoped, or made to consume `R-3`'s representation, before it can be approved** |
| **`R-3`** — `ElectionRestored` expresses restoration with no resumption target | 🟢 **GAP CONFIRMED · representation OPEN — the strongest of the three** | ✅ **Independently established here, not inherited:** `$returnsToGate` is non-nullable; the vacancy path records no halt; the handler already fabricates a filler and concedes it in its own docblock. ⇒ **ADR-2 §6(h)'s conditional antecedent is satisfied.** ⛔ The **representation** remains a domain decision bound by ADR-2 §6(b): a **named domain meaning**, never a sentinel, never *"unknown"*, never a fabricated halt |

⛔ **None is Approved.** ✅ **None is Rejected.** **`R-3` is the only one whose blocking dependency is a ruling rather than another unresolved boundary.**

---

# Section 10 — Next actor

> ## 🔵 **PO/ARB — and specifically NOT the Domain lane, which is correct to have stopped.**

**Three acts, in this order. Only the first is on the critical path for `BND-1`/`BND-3`.**

| # | Act | Who | Unblocks |
|---|---|---|---|
| **1** | **Rule the gate↔lifecycle-phase question** — i.e. whether a gate acquires a position in the election's life, and who owns that meaning. **This reaches `EM-OPEN-055` and the `GateDesignation` subject-abstraction reservation.** ⛔ It is a governance ruling; Architecture cannot supply it | **PO/ARB** | `BND-1` ⇒ then `BND-3` ⇒ then `DEP-2` |
| **2** | **Read the authorization**: does *"change repositories"* forbid **creating** a new domain-owned contract? One sentence either way | **PO/ARB** (the act's author) | `BND-2` ⇒ `DEP-5b`, `DEP-6`(halted) |
| **3** | **Rule `ElectionRestored`'s `w8` representation** — ADR-2 §6(h), whose antecedent §5.3 now establishes. **Independent of 1 and 2** | **PO/ARB**, then a separately authorized Domain slice | `DEP-6`(`w8`), and removes the pressure behind `DEP-7` |

**And three items referred, not resolved:** the `EM-GOV-068` halt contradiction (§7) · the `HaltedAtGate` docblock correction, to be made **with** act 1 and not before (§6.3) · the `DEP-10` site-count correction to the Amendment record, **four sites not two** (§6.4) — a Governance act.

⛔ **No implementation work is assigned to this reviewer or to anyone by this document.**

---

# Final stop condition — verified

```
[✓] No code changed              — git status on app/ tests/: clean
[✓] No tests changed
[✓] No ADR changed
[✓] No Domain artifact changed   — Phase-1 map, referral, authorization untouched
[✓] No authorization widened     — BND-2's authorization half explicitly NOT ruled
[✓] BND-1 explicitly ruled       — UNRESOLVED / FORECLOSED, with four candidates eliminated
[✓] BND-2 explicitly ruled       — architectural half resolved, authorization half referred
[✓] BND-3 explicitly ruled       — three candidates eliminated, remaining binary blocked on BND-1
[✓] HaltedAtGate/ElectionConstitution addressed — (A)+(E); correction reported, not applied
[✓] R-1/R-2/R-3 not treated as premises        — each ruled in §9; none Approved
[✓] P-7 authoritative            — not duplicated, not loosened; shown INAPPLICABLE in w8
[✓] w8 explicitly accounted for  — and established as WIDER than referred (§5.3)
[✓] RecoveryProcess absence preserved          — and the protected set corrected UP to four (§6.4)
[✓] Protocol remains evidence, not causal authority
[✓] Next actor explicitly identified           — PO/ARB, three acts, ordered
```

> ### The discipline this review chose over a plausible answer
> **A boundary could have been selected for `BND-3` and a lifecycle owner named for `BND-1`. Both would have been defensible and both would have been wrong in kind** — `BND-1` by performing a reserved binding act as a side effect, `BND-3` by fixing a seam whose location depends on it. **The evidence is sufficient to eliminate candidates and insufficient to select one, and that is the finding.**

**ARCHITECTURE REVIEW COMPLETE · STOPPING.** ⛔ **No code · no tests · no commits · no implementation · no authorization widened · this reviewer does not accept or verify its own ruling.**

**Traceability:** referral `2026-08-18-EM-DOM-001-architecture-referral.md` · Phase-1 map `2026-08-18-EM-DOM-001-phase1-domain-decision-map.md` (evidence, not premise) · authorization record + **Amendment 1** · ADR-1 §6(a)–(d) · ADR-2 §6(a)–(h) · `EM-ARCH-001` §5d · `EM-GOV-016`/`059`/`062`/`065`/`068` · **`EM-OPEN-055` (OPEN)** · `GateDesignation`, `GateIntervalState`, `HaltedAtGate`, `ElectionOperationalStatus`, `ResumptionTarget` (P-7), `ExpiryConsequence` (P-6), `ElectionRestored`, `AcceptanceGateDecision`, the three `Repository` interfaces, `FillCommitteeSeatHandler`, `RecordVacancyEventHandler`, `ElectionConstitution`, `ElectionLifecycleState` — all inspected directly · `AIP-14` · `ES-005.4` · `R-34` · `EP-02`.
