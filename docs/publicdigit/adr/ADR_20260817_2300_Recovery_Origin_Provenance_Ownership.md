# ADR — Recovery Origin Provenance Ownership

**Status: ✅ DECIDED — PO/ARB ruling recorded in §6 on 2026-08-18 (a CAUSAL-MODEL ruling; no A/B/C/D option was selected). Implementation authority is NOT granted; the domain core remains frozen and every consequence must pass the Rule-8 gate.**
**Created:** 2026-08-17, at PO direction *(«Create ADR-2 from the investigation. Keep ADR-2 decision blank.»)* · **Work item:** `EM-IMPL-002`, architecture hold before GREEN-5.
**Evidence base — consumed, NOT restated:** `docs/publicdigit/architecture/2026-08-17-EM-IMPL-002-q-restore-recovery-origin-provenance-investigation.md` (left unchanged, per the registered sequence *evidence → investigation → ADR proposal → decision*).
**Placement:** `docs/publicdigit/adr/` — the same class as `ADR_20260817_2145`, so both rulings of this hold live in artifacts of one kind.

> ## **What this ADR protects, in one line:** **Protocol records reality. Domain owns causal meaning.**
> *(PO formulation, 2026-08-17. The failure mode: an event-sourced architecture without event sourcing's guarantees — no versioning, no replay guarantees, no reconstruction rules, no consistency model.)*

## 1 · Why this is an ADR and not an implementation detail

**The question is an architecture OWNERSHIP question — and reframed 2026-08-17 from a storage question to a MODEL question:** ⛔ *not* *"who owns `originatingGate`?"* but ✅ ***"what causal-origin model governs Restoration, and which bounded-context concept owns each causal invariant?"*** *(`originatingGate` is only one possible CONSEQUENCE of that model; naming it first would settle a field location before the model exists.)* It affects the domain model · the recovery lifecycle · event/protocol interpretation · future restoration behaviour. **It is deliberately kept SEPARATE from `ADR_20260817_2145`:** that one asks *what does null MEAN*, this one asks *who owns the causal explanation.* ⛔ **They must not be combined.**

## 2 · Context

`Q-RESTORE`: P-7 (`ResumptionTarget`) requires a `HaltedAtGate`, and **no port in the authorized six-port universe can supply one** — the protocol is append-only with no read side, AG-3 carries no gate, AG-2 records no halt. **The system currently knows *recovery exists*; it does not know *recovery started because gate X halted*. The missing concept is CAUSAL PROVENANCE.**

**Current interim behaviour (UC-3, committed `4651a3e7`):** the handler neither calls P-7 nor constructs a `HaltedAtGate` — constructing one would have invented the halt fact — and `ElectionRestored` instead names the election's *established* acceptance decision. **Exact in Model A today; silently wrong the day two designations are established at once.**

## 3 · Constraints any option must respect

⛔ **The domain core is frozen** (`EM-IMPL-001` baseline; the `EM-IMPL-002` grant) — a domain change needs its own PO authorization. ⛔ **No new port without authorization.** ⛔ **Protocol is evidence, not causal authority** *(candidate standing rule, signalled 2026-08-17: «Protocol records decisions; it does not become the source of causal authority.»)* ⛔ **No application workaround may substitute for missing domain knowledge.**

## 4 · Options

| | Option | Authorization required | Note |
|---|---|---|---|
| **A** | **`RecoveryProcess` owns `originatingGate`** | ⛔ **DOMAIN** | Registered position ranks it strongest: *"the invariant stays together"* — the recovery period and the halt that caused it in one place. ⚠️ **Evidence from the investigation: this retains a fact the F-2 handler ALREADY HOLDS AND DISCARDS**, which is why it reads as the natural home. |
| **B** | **The recovery-start DOMAIN FACT carries provenance** — an immutable domain record (`RecoveryPeriodStarted { originatingGate, … }`) | ⛔ **DOMAIN** | Also domain-owned. ⚠️ If AG-3 later needs it, it must reconstruct it. ⚠️ **Vocabulary care (2026-08-17): read as *domain fact / immutable domain record* FIRST. "Event" is deliberately avoided here — it pulls readers toward event-sourcing assumptions the system has not adopted. If this option is later implemented AS an event, then and only then do §5d.1's guarantees apply.** |
| **C** | **A protocol read port supplies it** | ⛔ **NEW PORT** | ⛔ **Rejected as a direction:** the protocol answers *"what happened?"*, not *"what causal invariant allows this?"*; using it so **turns history into hidden authority — a hidden event-sourced domain without event sourcing's guarantees.** |
| **D** | **A ruled proxy** (today's behaviour, blessed) | ruling only | Weakest on sovereignty; *"could work temporarily, but creates future risk"* — the application becomes a translator of missing domain knowledge, violating **invariant ownership follows the bounded context, not the layer that needs the data.** |
| **E** | **The command carries it** | — | ⛔ **REJECTED by the lane, with reason:** it would let an appointment body decide progression meaning, leaking lifecycle meaning into the caller. |

## 5 · The companion question — required under EVERY option

⚠️ **Restoration happens with no prior halt at all.** The accepted RED pin `w8` restores an election whose gate is merely **Unachievable** (vacancy arithmetic) with **no halted-recovery period in existence** — and `ElectionRestored` requires a non-nullable `GateDesignation`. **No option in §4 answers this.** ⛔ **Without a companion ruling, `Q-RESTORE` reopens at the first election restored while merely unachievable.**

### 5a · The sharpened statement *(PO direction, 2026-08-17 — this is the form the ruling must answer)*

> ## **"The existence of a restoration event does not imply the existence of a recovery period."**

⚠️ **Why the sharper wording matters:** the loose form (*"restoration happens with no prior halt"*) still lets a later reader assume `ElectionRestored` **implies** a `RecoveryProcess` existed — **which is precisely the hidden-invariant problem this ADR exists to prevent.** The relationship is **two distinct causal paths**, not one:

```
Restoration
      |
      +---- a halted recovery period completed          (halt provenance exists)
      |
      +---- an unachievable condition was resolved      (NO prior halt; in w8 a
                                                         CommitteeRestoration period
                                                         exists, no halted one does)
```

> ⚠️ **FACTUAL CORRECTION (2026-08-18, second independent gate) — the STATEMENT above stands; its causal explanation is corrected.** **VERIFIED in `FillCommitteeSeatHandlerRedTest::test_w8_…`: the `w8` fixture seeds `RecoveryProcess(PeriodKind::CommitteeRestoration, 20)`.** ⛔ **So `w8` does NOT evidence *"a restoration with no `RecoveryProcess`"*; it evidences *"a restoration with no prior HALT"* — no `HaltedElectionRecovery` period, hence no halt-derived gate.** *(§2's own phrasing — «no halted-recovery period in existence» — was the accurate one and is restored as this ADR's canonical form.)*
> **What therefore remains genuinely open, and is the question the ruling must answer:** ***does Restoration require a prior halted recovery period / halt provenance at all, or can it legitimately occur without that causal path?*** ⚠️ **And the broader form — a restoration with no `RecoveryProcess` of EITHER kind — is structurally reachable (the UC-3 handler returns early on both `null` branches) but is pinned by NO test: an unpinned path, never an accepted RED case.** **Three concepts, not two: Restoration ≠ `RecoveryProcess` ≠ halt provenance.**

**So even if option A is chosen, `originatingGate` may legitimately be absent** — and that is a **domain lifecycle** question, not a technical one: *is restoration always caused by a halted gate, or can it also mean an unachievable condition resolved without any halt?*

### 5a.1 · A recommendation the PO offered for the eventual ruling *(⚠️ RECOMMENDATION, expressly "not the decision itself — PO/ARB owns that")*

**Owner:** the recovery domain concept · **preferred:** option A · **reason:** the invariant *"recovery originated from a halt"* belongs with `RecoveryProcess` · **authorization:** requires a future domain slice · ⛔ **not allowed:** application reconstruction · protocol lookup · command-supplied provenance.
**Companion rule offered with it:** *"Restoration without `RecoveryProcess`: allowed. A restoration fact does not imply a prior halt."*
⚠️ **Accuracy note (2026-08-18, second independent gate — the PO's wording above is preserved VERBATIM as the recommendation actually offered, never rewritten):** its second clause — *"a restoration fact does not imply a prior halt"* — **is what `w8` actually evidences.** Its first clause — *"restoration without `RecoveryProcess`"* — **is NOT evidenced by `w8`, whose fixture seeds a `CommitteeRestoration` period** (§5a); that broader path is structurally reachable but unpinned. **The ruling may still adopt either clause; it must simply not rest the second on the first.**

## 5b · TWO questions the ADR must not conflate *(added 2026-08-17)*

| Question | Subject |
|---|---|
| *"What caused this **RESTORATION**?"* | the restoration transition |
| *"What caused this **RECOVERY PROCESS**?"* | the recovery period |
| *"What caused this **HALT**, if one occurred at all?"* | the halt — and it is this halt's gate that provenance would identify, if the ruling requires provenance at all |

**These are related but NOT identical, may have different answers, and may have DIFFERENT OWNERS.** A model that answers only the second leaves `w8` unanswered (§5a, as corrected: **a restoration can exist with no prior halt — and in `w8` a `CommitteeRestoration` period exists while no `HaltedElectionRecovery` period does**).

## 5c · Causal-origin model *(the space the ruling chooses from — deliberately NOT limited to the §4 storage options)*

> **The question is not where `originatingGate` is stored. It is: WHAT DOMAIN FACT EXPLAINS WHY RESTORATION WAS VALID?**

**Candidate models, none preferred here:** **① mandatory predecessor** (every restoration has a predecessor fact) · **② typed independent origins** (several legitimate origin kinds, discriminated) · **③ conditional predecessor paths** · **④ state-only restoration** (no provenance concept — admissible only if expressly justified).

**Whichever is selected, the ruling must define:** the **allowed origins** · **ownership** of each · which references are **mandatory** · the **validation point** · the **immutable representation**.

### 5c.1 · `RestorationOrigin` as a TYPED concept — why nullable fields are the wrong shape

⛔ **The shape to avoid:**

```php
final class Restoration {
    ?RecoveryProcessId $process;   //  all three null → the model
    ?GateDesignation   $gate;      //  cannot say WHY, and every
    ?Reason            $reason;    //  reader must guess
}
```

**Three nullable fields admit combinations that mean nothing, and no invariant forbids them.** ✅ **A discriminated `RestorationOrigin` makes illegal states unrepresentable:**

```
RestorationOrigin
  ├── HaltOrigin                    { recoveryProcessId, originatingGate }
  ├── UnachievableResolutionOrigin  { resolutionId, reason }
  └── … further kinds only if the ruling defines them
```

> ### ⚠️ **AND THE ONE THING THIS SECTION DELIBERATELY DOES NOT DECIDE**
> **Whether provenance is ALWAYS required.** The independent review proposed that every restoration *"MUST carry exactly one typed causal origin"* — **that is not adopted here, because it would silently decide the `w8` case** (§5a: *the existence of a restoration event does not imply the existence of a recovery period*). **The ruling must choose between:** **(i)** every restoration has an origin, with an explicit kind for the unachievable-resolution path · **(ii)** some restorations legitimately have **no** causal provenance, represented explicitly (e.g. a `NotApplicable` kind — **never a silent null**) · **(iii)** an unknown-origin state, if the domain wants one. **This is the same philosophy as ADR-1: absence must have a named meaning, not an inferred one.**

## 5d · Provenance CAPTURE constraint *(binding on any option — added 2026-08-17)*

> **Causal provenance MUST be captured at the moment the Restoration transition is ACCEPTED.**
>
> ⛔ **It MUST NOT be reconstructed from:** protocol logs · timestamps · projections · downstream read models · inferred ordering.

**This closes the same loophole `§6c` closes in ADR-1: a technical trace is not a business meaning, and reconstructing causality after the fact makes the reconstruction — not the domain — the author of the meaning.**

### 5d.0 · Vocabulary discipline in this ADR *(added 2026-08-17)*

**Throughout this ADR, read "fact" as *domain fact / immutable domain record*, never as "event" in the event-sourcing sense.** ⚠️ **The word `event` is used only where the protocol or an actual event-sourcing design is meant** — because using it loosely invites a reader to assume stream identity, replay and versioning that this system has not adopted. **If a chosen option is implemented as an event, §5d.1's guarantees become obligations at that moment, not before.**

### 5d.1 · If event sourcing is ever adopted, it must be adopted properly

**"Event sourcing" must not be claimed loosely.** If the system later adopts it for the relevant aggregate, the implementation must provide: **immutable events · aggregate stream identity · versioning · expected-version append · deterministic replay · schema evolution.** ⛔ **Reading provenance out of an append-only log without these is not event sourcing; it is a weak imitation with none of its guarantees** — the failure mode §3 already forbids.

## 5e · CROSS-ADR CONSISTENCY CONDITION *(H-2 reciprocal, added 2026-08-18 — ⛔ the ADRs remain SEPARATE)*

**Reciprocal of `ADR_20260817_2145` §6-cross.** The two decisions are separate ownership decisions whose consequences must remain mutually consistent.

✅ **`RecoveryProcess` absence may legitimately remain a normal lifecycle state — for both period kinds** *(and it is already implemented that way — see the sibling ADR §4a)*. ⛔ **What cannot simultaneously hold is: *"HALT provenance is required for every Restoration"* TOGETHER WITH *"that provenance is owned by the halted recovery period"*** — because **`w8` permits a restoration with no prior halt, hence no `HaltedElectionRecovery` period to own it** *(⚠️ corrected 2026-08-18: `w8` seeds a `CommitteeRestoration` period, so a `RecoveryProcess` DOES exist there — §5a)*. **Consequence, and it is evidence FOR this ADR's own §5b split: if halt provenance is made mandatory, its owner cannot be a halted period on every path.** ⛔ **No option is selected by this condition.**

## 5f · The `w8` REPRESENTATION constraint *(H-3, added 2026-08-18)*

> ### **VERIFIED FACT:** the committed domain fact is `ElectionRestored(ElectionId $electionId, GateDesignation $returnsToGate, RecordedInstant $restoredAt)` — **`$returnsToGate` is NON-NULLABLE.**

**So a semantic ruling that permits legitimate absence of provenance currently has NO REPRESENTATIONAL PATH for the `w8` restoration**: the semantic rule would say *provenance may be absent* while the domain fact requires a `GateDesignation` in every instance.

**Required of the ruling — the semantic and representational consequences must be resolved TOGETHER:** ***"If Restoration legitimately occurs without a prior HALT — hence with no halted gate to return to — what domain representation satisfies the Restoration invariant?"*** *(⚠️ wording corrected 2026-08-18: `w8` is a no-halt case, not a no-`RecoveryProcess` case — §5a. The same question arises a fortiori on the unpinned path where no `RecoveryProcess` exists at all.)*

⛔ **This ADR does NOT: change `ElectionRestored` · propose a replacement type · invent a sentinel value · invent an "unknown" designation · or decide whether the current representation is correct.** **It only prevents a ruling whose meaning has no representation — which would invert this programme's order: MEANING → INVARIANT → REPRESENTATION, never representation-first with meaning invented to fit.**

## 6 · Decision

> ## ✅ **PO/ARB DECISION — 2026-08-18: APPROVED.**
>
> ⚠️ **Recorded as given. This ruling deliberately does NOT select Option A/B/C/D** — the PO stated expressly: *"I would not sign ADR-2 as merely 'Option A — RecoveryProcess owns originatingGate.' That is too narrow. It solves why recovery started, but it does not by itself solve why restoration occurred."* **Governance has therefore recorded a CAUSAL-MODEL ruling and has not translated it into an option.**

### (a) Causal-origin model

**The Domain shall distinguish between (i) why a `RecoveryProcess` exists, and (ii) why a `Restoration` is permitted to occur.** These are related causal questions, **are not assumed to be the same invariant, and must not be collapsed** merely because one restoration path may follow a recovery period.

**A `RecoveryProcess` may own the invariant describing the origin of that recovery process. A `Restoration` must INDEPENDENTLY express the domain condition that makes restoration legitimate.** **The existence of a Restoration does not imply that a `RecoveryProcess` previously existed.**

```
                    Restoration
                         |
              +----------+----------+
              |                     |
      Recovery completed      Unachievable condition
              |                 resolved directly
       RecoveryProcess          no RecoveryProcess
       existed
```

⛔ **The model must not infer one path from the other.**

### (b) Whether provenance is always required

**Provenance is required whenever the approved domain invariant requires causal origin to explain or validate the transition. It is NOT universally mandatory merely because a Restoration occurred.**

**A Restoration without a prior `RecoveryProcess` is a legitimate domain possibility** where an unachievable condition has been resolved without a recovery process having existed. ⛔ **Such a case must not be represented by:** a fabricated `HaltedAtGate` · a fabricated `RecoveryProcess` · a sentinel `GateDesignation` · an *"unknown"* value used merely to satisfy a non-nullable field · inferred provenance · an application-level assumption.

> ## **Legitimate absence of provenance must itself have a named domain meaning.**

**The exact representation is a domain-model decision and requires the authorization in (e).**

### (c) Allowed origin types and mandatory references

**The Domain shall define explicitly which causal origins are valid for each transition. An origin type is valid only where the corresponding bounded context OWNS the invariant that gives that origin its meaning.**

**Recovery-origin case:** a `RecoveryProcess` may carry the causal fact explaining why that recovery process exists, **including its originating gate where the domain invariant requires that information.**
**Restoration case:** the model must distinguish at least the two causally different situations the evidence establishes — restoration **following a `RecoveryProcess`**, and restoration **following resolution of an unachievable condition without one**.

⛔ **No origin type may be introduced merely as a technical wrapper around `null`. No sentinel or catch-all origin may be introduced without an explicit domain meaning.**

### (d) Ownership of each causal invariant

**Invariant ownership follows the bounded context that DEFINES THE MEANING, not the layer or component that needs the data.** Therefore: the context owning the **recovery lifecycle** owns the invariant explaining why a `RecoveryProcess` exists · the context owning the **restoration lifecycle** owns the invariant explaining why Restoration is permitted · another context **may reference** an authorized origin concept **but does not thereby acquire authority over it.**

**Binding:** ***an origin type may be referenced by other bounded contexts, but only its owning bounded context may create, validate, or change its meaning.***
⛔ **No shared "provenance DTO" or common data structure shall be treated as ownership.** **A concept is not owned because it has a class; a concept is owned because a bounded context defines its meaning.**

### (e) Authorization path

⛔ **This decision does NOT authorize a domain-model modification. The current domain core remains FROZEN.**

**If the existing domain model cannot represent the approved causal relationship, a separate authorized domain slice is required before application normalization.** That slice must have: **an explicit domain RED test expressing the missing invariant · explicit PO/ARB authorization · domain implementation · domain verification · confirmation that the resulting contract can be consumed by the Application layer without reconstructing domain meaning.**

⛔ **No application workaround may compensate for a missing domain concept. In particular the Application layer must not:** inspect protocol history to reconstruct provenance · infer provenance from timestamps · infer provenance from ordering · manufacture `HaltedAtGate` · manufacture a `RecoveryProcess` · obtain causal meaning from a command supplied by an appointment body · use repository absence as a substitute for the missing domain invariant.

### (f) UC-3 consequence

⛔ **UC-3 must NOT be normalized until the approved causal model has an authorized domain representation. The committed UC-3 behaviour remains UNCHANGED until the authorization/dependency gate is completed.** Once the contract exists, **UC-3 must CONSUME it rather than reconstructing provenance itself.**

**Acceptance criterion:** ***UC-3 must express the approved Restoration invariant without inventing causal provenance*** — correctly handling **both** restoration following a `RecoveryProcess` **and** restoration without one. ⛔ **The normalization must not merely replace one technical representation with another.**

### (g) Authoritative bounded context per origin type

**Every origin type introduced by the approved domain model must have ONE authoritative bounded context.** Other contexts may consume it through an authorized contract but may not: **create it · reinterpret it · validate its business meaning · extend its semantics · derive a competing meaning.**

**This prevents a typed `RestorationOrigin` from degenerating into a shared data structure with several contexts implicitly owning the same concept.**

> ## **Reference is not ownership. Consumption is not authority.**

### (h) Restoration without `RecoveryProcess` — explicit representation

**The `w8` case is part of the decision, not an implementation afterthought.** If the Domain permits restoration after an unachievable condition is resolved without a prior `RecoveryProcess`, **the Domain must provide an explicit representation for that causal path — decided TOGETHER with this ADR's implementation, not deferred until after the semantics have been adopted.**

⛔ **The current `ElectionRestored` representation must not be silently repurposed to encode this case.** No fabricated `HaltedAtGate` · no fabricated `RecoveryProcess` · no sentinel `GateDesignation` · no *"unknown"* value without explicit domain meaning · no protocol reconstruction.

**If the existing non-nullable `GateDesignation` requirement is incompatible with the approved causal model, that incompatibility is a DOMAIN-MODEL GAP requiring a separately authorized domain slice.**

### Binding provenance rule

> **Provenance is captured at the domain transition that establishes the causal fact. It must not subsequently be reconstructed from protocol logs, timestamps, projections, read models, inferred temporal ordering, or application-side correlation.**

**The protocol may preserve evidence of what happened. It does not become the authority for why the Domain allowed it.** → ***Protocol records reality. Domain owns causal meaning.***

### What this decision does NOT authorize

⛔ **Not blanket implementation authorization.** It does not authorize: changes to the frozen domain core · a new repository port · a protocol read model · an event-sourcing implementation · a provenance DTO · a new `RestorationOrigin` class · changes to `ElectionRestored` · changes to UC-3 · normalization of the existing RED pin. **All are CONSEQUENCES of this decision and must pass the Rule-8 gate.**

**If the approved model requires a new domain concept, the next action is:** `Domain RED → domain authorization → domain implementation → domain verification → Application normalization → GREEN-5`.

### Final architectural principle

> ## **Restoration provenance belongs to the domain causal model, not to the mechanism that happens to have enough data to reconstruct it.**

**Retained:** ***provenance ownership is a domain ownership decision, not a data availability decision.***
>
> *(The eight required decisions (a)–(h) below are all answered by the ruling above; retained for traceability.)*
>
> **This is a CAUSAL-ORIGIN decision, not a field-location decision.** Required in the ruling:
>
> **(a)** the **causal-origin MODEL** (§5c: mandatory predecessor · typed independent origins · conditional paths · state-only) ·
> **(b)** **whether every restoration requires provenance** — and if not, how its absence is represented **explicitly** (§5c.1; ⛔ never a silent null) ·
> **(c)** the **allowed origin types** and their mandatory references ·
> **(d)** the **OWNER of each causal invariant** (the §4 options are the candidate homes: A/B domain-owned · C excluded · D proxy) ·
> **(e)** the **authorization path** if a domain change is chosen — a domain slice with its own RED tests and its own authorization; ⛔ **no application workaround** ·
> **(f)** the **UC-3 consequence**: what the committed `FillCommitteeSeatHandler` must do differently, and whether that lands in the normalization slice or a separate one ·
> ⚠️ **The consumption rule that (d) and (g) rest on (added 2026-08-17):** ***"An origin type may be REFERENCED by other bounded contexts, but only its OWNING bounded context may create, validate, or change its meaning."*** **It forecloses the future half-claim *"we don't own the concept, but we need to validate it"* — consumers may USE meaning; they do not DEFINE it.**
>
> **(g)** ⚠️ **which BOUNDED CONTEXT is authoritative for EACH origin type** — e.g. a halt origin owned by the Recovery concern, an unachievable-resolution origin possibly owned elsewhere. **A typed origin WITHOUT stated ownership can still degenerate into a shared data structure**, and the DDD rule is: ***a concept is not owned because it has a class; it is owned because ONE bounded context defines its meaning.*** ⛔ **Two contexts writing the same origin type is co-ownership, which is no ownership.** ·
> **(h)** ⚠️ **the `w8` REPRESENTATION consequence (§5f): what `ElectionRestored` contains when a restoration legitimately has no prior recovery period, given that `$returnsToGate` is non-nullable today** — to be resolved TOGETHER with (b), never after it.

### 6a · REQUIRED IN THE RULING — "What this decision does NOT mean" *(pre-drafted at PO direction)*

> **"Provenance ownership is a domain ownership decision, not a data availability decision."**

**Purpose: it forecloses the future argument *"the protocol already has the information, so let's use it."***

## 6d · ⚠️ CROSS-ADR CONSISTENCY CONDITION — the reciprocal of `ADR_20260817_2145` §6d *(added 2026-08-18, closing architecture-review finding **H-2**)*

**§1's instruction stands: this ADR and `ADR_20260817_2145` must NOT be combined.** They ask different questions and are owned separately. **What §1 does not say, and what the architecture review found readers were inferring from it, is that the two ANSWERS are independent. They are not.**

> **Any TWO of these hold comfortably; all THREE cannot:**
> **①** `RecoveryProcess` absence is a legitimate normal lifecycle state, **`HaltedElectionRecovery` included** *(`ADR-1` §4a — already implemented)* · **②** every Restoration requires causal provenance *(a permitted answer to §6(b) below)* · **③** that provenance is the originating gate of a **HALT**, owned by the halted recovery period *(option A on the halt path)*.
>
> **`w8` is the counter-example — a real accepted RED pin — for THIS precise form: it restores an election with NO PRIOR HALT.**

> ⚠️ **FACTUAL CORRECTION (2026-08-18, second independent gate).** An earlier form of ①/③ was framed on `RecoveryProcess` as such, and read as *"`w8` restores with no `RecoveryProcess` at all."* **VERIFIED in `FillCommitteeSeatHandlerRedTest::test_w8_…`: the pin seeds `RecoveryProcess(PeriodKind::CommitteeRestoration, 20)` — a `RecoveryProcess` DOES exist in `w8`; the `HaltedElectionRecovery` period does not.** ⚠️ **This matters to the ruling: the loose form told the signer that a recovery-owned provenance is IMPOSSIBLE for `w8`, closing option A prematurely. It is not impossible — the owner exists and simply carries no halt.** ⛔ **The broader path — a restoration with no `RecoveryProcess` of either kind — is structurally reachable (`FillCommitteeSeatHandler` returns early on both `null` branches) but pinned by NO test: unpinned, not an accepted RED case.**

**The precise form matters:** `RecoveryProcess` absence **may remain a normal state under every combination**. The clause that cannot also hold is *"every Restoration requires HALT provenance **owned by the halted recovery period**"*.

**This reinforces §5b rather than undercutting it:** if a Restoration can exist with no prior halt, then *"what caused this Restoration?"*, *"what caused this RecoveryProcess?"* and *"what halt, if any, originated it?"* **cannot share a single owner in general** — which is precisely why §5b insists they are distinct subjects that *"may have DIFFERENT OWNERS."*

**Required of the two rulings:** their answers must be mutually consistent · **`w8` must be resolved explicitly under §6(b)** and visibly compatible with `ADR-1`'s `RecoveryProcess` ruling · ⛔ neither ADR may be read as having pre-answered the other. **No option is selected, preferred or excluded here.**

## 6e · ⚠️ REQUIRED IN THE RULING — the `w8` REPRESENTATION consequence *(added 2026-08-18, closing architecture-review finding **H-3**)*

**§5c.1 correctly refuses to mandate that every restoration carries an origin, and offers (ii) *"some restorations legitimately have no causal provenance, represented explicitly."* That resolves the ORIGIN's representation. It does not reach the artifact that actually blocks `w8`.**

> **VERIFIED, by inspection of the frozen domain 2026-08-18:**
> ```php
> // app/Contexts/Election/Domain/OperatingCore/Event/ElectionRestored.php
> public function __construct(
>     public ElectionId $electionId,
>     public GateDesignation $returnsToGate,   // NON-NULLABLE
>     public RecordedInstant $restoredAt,
> ) {}
> ```
> **`$returnsToGate` is not nullable.** §2 and §5 both name this constraint as *the problem*; **no option in §4 or §5c states how it is cleared.**

**So a `w8` restoration — one with no prior halted gate — must still supply a `GateDesignation`, and the semantic models offered do not say what it is.** A ruling that adopts (ii) would establish a **meaning with no representational path**, which inverts this ADR's own discipline: **meaning → invariant → representation**, never *representation → invented meaning*.

> ### **The ruling must therefore answer, together and not separately:**
> **"If a Restoration legitimately occurs without a prior HALT — hence with no halted gate to return to — what domain representation satisfies the Restoration invariant?"**
>
> *(⚠️ wording corrected 2026-08-18, second independent gate: `w8` seeds a `CommitteeRestoration` period, so it is a **no-halt** case, not a **no-`RecoveryProcess`** case — §5a. The representational problem is unchanged: `$returnsToGate` is non-nullable either way, and the handler today supplies the ESTABLISHED acceptance decision's gate — the §4 option-D proxy, exactly as §2 records.)*

⛔ **This subsection proposes no answer.** It does **not** change `ElectionRestored`, propose a replacement type, introduce a sentinel, invent an *"unknown"* or *"not-applicable"* designation, or judge whether the current representation is correct. **Any change to `ElectionRestored` is a change to the frozen domain core and requires its own authorization (§3).** The only addition here is the requirement that the semantic and representational consequences be resolved in one act rather than one being deferred behind the other.

---

## 7 · Traceability

Investigation (evidence base, unchanged) · GREEN-4 verification `ef9e146c` · UC-3 as committed `4651a3e7` · hold registration `bf6f5141` · `ADR_20260817_2145` (the separate absence question) · `EM-GOV-059`(c)/`060`/`061` · P-7 · the `w8` pin.
