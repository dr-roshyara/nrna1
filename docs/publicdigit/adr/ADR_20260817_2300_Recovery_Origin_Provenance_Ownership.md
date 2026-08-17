# ADR — Recovery Origin Provenance Ownership

**Status: 🟡 PROPOSED — CANDIDATE ONLY. The decision block (§6) is deliberately BLANK and belongs to the Product Owner / ARB.**
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
| **B** | **The recovery-start fact carries provenance** (`RecoveryPeriodStarted { originatingGate, … }`) | ⛔ **DOMAIN** | Also domain-owned; event-oriented. ⚠️ If AG-3 later needs it, it must reconstruct it. |
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
      +---- a recovery period completed
      |
      +---- an unachievable condition was resolved   (no recovery period ever existed)
```

**So even if option A is chosen, `originatingGate` may legitimately be absent** — and that is a **domain lifecycle** question, not a technical one: *is restoration always caused by a halted gate, or can it also mean an unachievable condition resolved without recovery?*

### 5b · A recommendation the PO offered for the eventual ruling *(⚠️ RECOMMENDATION, expressly "not the decision itself — PO/ARB owns that")*

**Owner:** the recovery domain concept · **preferred:** option A · **reason:** the invariant *"recovery originated from a halt"* belongs with `RecoveryProcess` · **authorization:** requires a future domain slice · ⛔ **not allowed:** application reconstruction · protocol lookup · command-supplied provenance.
**Companion rule offered with it:** *"Restoration without `RecoveryProcess`: allowed. A restoration fact does not imply a prior halt."*

## 5b · TWO questions the ADR must not conflate *(added 2026-08-17)*

| Question | Subject |
|---|---|
| *"What caused this **RESTORATION**?"* | the restoration transition |
| *"What caused this **RECOVERY PROCESS**?"* | the recovery period |

**These are related but NOT identical, may have different answers, and may have DIFFERENT OWNERS.** A model that answers only the second leaves `w8` unanswered (§5a: a restoration can exist with no recovery period at all).

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

### 5d.1 · If event sourcing is ever adopted, it must be adopted properly

**"Event sourcing" must not be claimed loosely.** If the system later adopts it for the relevant aggregate, the implementation must provide: **immutable events · aggregate stream identity · versioning · expected-version append · deterministic replay · schema evolution.** ⛔ **Reading provenance out of an append-only log without these is not event sourcing; it is a weak imitation with none of its guarantees** — the failure mode §3 already forbids.

## 6 · Decision

> **⬜ LEFT BLANK — the Product Owner / ARB decides.**
>
> **This is a CAUSAL-ORIGIN decision, not a field-location decision.** Required in the ruling:
>
> **(a)** the **causal-origin MODEL** (§5c: mandatory predecessor · typed independent origins · conditional paths · state-only) ·
> **(b)** **whether every restoration requires provenance** — and if not, how its absence is represented **explicitly** (§5c.1; ⛔ never a silent null) ·
> **(c)** the **allowed origin types** and their mandatory references ·
> **(d)** the **OWNER of each causal invariant** (the §4 options are the candidate homes: A/B domain-owned · C excluded · D proxy) ·
> **(e)** the **authorization path** if a domain change is chosen — a domain slice with its own RED tests and its own authorization; ⛔ **no application workaround** ·
> **(f)** the **UC-3 consequence**: what the committed `FillCommitteeSeatHandler` must do differently, and whether that lands in the normalization slice or a separate one.

### 6a · REQUIRED IN THE RULING — "What this decision does NOT mean" *(pre-drafted at PO direction)*

> **"Provenance ownership is a domain ownership decision, not a data availability decision."**

**Purpose: it forecloses the future argument *"the protocol already has the information, so let's use it."***

## 7 · Traceability

Investigation (evidence base, unchanged) · GREEN-4 verification `ef9e146c` · UC-3 as committed `4651a3e7` · hold registration `bf6f5141` · `ADR_20260817_2145` (the separate absence question) · `EM-GOV-059`(c)/`060`/`061` · P-7 · the `w8` pin.
