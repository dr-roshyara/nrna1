# Rule-8 Authorization / Dependency Gate — ADR-2 (Recovery Origin Provenance Ownership)

**Date:** 2026-08-18 · **Lane:** Session 2 — Governance · **Trigger:** the PO/ARB ADR-2 ruling of 2026-08-18, which states verbatim: *"This ADR signature is not blanket implementation authorization."*
**Domain core state:** `app/Contexts/Election/Domain/OperatingCore` — **byte-identical to the RED baseline `1f4b4c5f`** (`git diff --stat` empty). Frozen, as the ruling requires.

## Verdict

> # 🔴 BLOCKED — a separately authorized DOMAIN slice is required before any application normalization.

The block follows from the ruling's own §(e)/§(h), not from a Governance preference.

## Evidence

| # | Question the ruling forces | Finding in the frozen core |
|---|---|---|
| **Q1** | Does the domain represent **why a `RecoveryProcess` exists** (ruling (a)(i), (c) recovery-origin case)? | 🔴 **No.** `RecoveryProcess` carries `ElectionId`, `PeriodKind`, `PolicyBinding`, intervals, `active` — **no causal origin**. `RecoveryPeriodStarted` carries the same four values — **no origin**. |
| **Q2** | Does any domain type express **why a Restoration is permitted**, independently of recovery (ruling (a)(ii))? | 🔴 **No.** No `RestorationOrigin`, no equivalent concept, no named alternative anywhere in the 56 domain files. |
| **Q3** | Can `ElectionRestored` represent the **`w8` no-`RecoveryProcess` path** (ruling (h))? | 🔴 **No.** `public GateDesignation $returnsToGate` is **non-nullable** and the event carries no origin. **This is exactly the incompatibility (h) names**, and (h) rules it is *"a DOMAIN-MODEL GAP requiring a separately authorized domain slice."* |
| **Q4** | Does the model distinguish the **two causal paths** at all (ruling (a), (c))? | 🔴 **No.** Nothing expresses *unachievable-condition-resolved-without-recovery*. |
| **Q5** | Who constructs the causal claim **today**? | 🔴 **The Application layer, at exactly one site.** `new ElectionRestored(...)` appears **once in the whole repository** — `FillCommitteeSeatHandler.php:156` — fed by `$decision->gate()` from line 140. |

## The finding that matters most

**UC-3 obtains the restoration's causal claim by taking the gate of whichever `AcceptanceGateDecision` its unguarded scan happened to find** (`establishedAcceptanceDecision()`, line 206 → line 106 → line 140 → line 156).

That single lookup is doing **two different jobs at once**:
- it is the **ADR-1 absence question** (is a decision *required but absent*, or *not yet reached*?), and
- it is the **ADR-2 provenance source** (which gate does restoration return to, and why is restoration legitimate?).

⚠️ **Therefore the two ADR dependencies are NOT independent — they converge on the same lines of UC-3.** The registered observation (*"weigh the ADR-1 and ADR-2 domain dependencies together before either slice is authorized"*) is now **substantiated, not merely prudent**: normalizing either one alone would rewrite the same code twice, and the first rewrite would have to invent an answer to the other ADR's question in order to compile.

## Consolidated domain gap register (both ADRs)

| Gap | Source | Where it lands |
|---|---|---|
| G-a | ADR-1 | No contract exposes **Committee required-existence** |
| G-b | ADR-1 | No contract **discriminates the two `AcceptanceDecision` meanings** (not-yet-reached vs required-but-absent) |
| G-c | ADR-1 | **No policy P-1…P-7** accepts or returns an absence concept |
| G-d | ADR-2 | No **recovery-origin** invariant on AG-3 / `RecoveryPeriodStarted` |
| G-e | ADR-2 | No **restoration-permission** invariant; `ElectionRestored.$returnsToGate` non-nullable blocks the `w8` path |

**Five gaps · one frozen aggregate set (AG-1, AG-2, AG-3 + `Event/`) · one convergent call site.**

## What Governance has NOT done

⛔ No domain file touched · no RED written · no port proposed · no `RestorationOrigin` authored · no UC-3 change · the existing RED pin left reporting its two sites · **no domain slice requested on Governance's own authority.**

**Per the ruling: requesting and granting that authorization is the PO/ARB's act, not Governance's.** Governance reports the block and the evidence; it does not clear its own gate.

**Traceability:** ADR-2 §6 (a)–(h) · ADR-1 §6 constraint ⑥ · Rule-8 gate for ADR-1 (`7514f145`) · `1f4b4c5f` baseline freeze · `4651a3e7` UC-3 · A-9 precedent · `EM-GOV-059`(c) · `EM-GOV-062` · W-8.

---

# Gate completion — A/B/C dependency classification (2026-08-18, same day)

**Commissioned by the PO:** *"For every identified dependency, classify it as (A) existing authorized domain contract, (B) missing domain concept requiring a new authorized domain slice, or (C) prohibited application/protocol reconstruction. Do not proceed beyond the gate."*

**Nothing was modified to produce this section.** Domain core, application code, tests, repositories and protocol access are untouched; the core remains byte-identical to `1f4b4c5f`.

## ⚠️ Correction to this record's own Q2

The first pass wrote *"no restoration-permission concept … no equivalent concept"*. **That was too broad and I am narrowing it.** The frozen domain **does** contain an authorized contract for **where** restoration returns:

```php
// P-7, Policy/ResumptionTarget.php — EM-GOV-059(c), 060; EM-ARCH-001 §2e
public static function resolve(HaltedAtGate $halt): GateDesignation
```

**What is missing is not the contract. It is (i) any producer or persistence path for its input `HaltedAtGate`, and (ii) the separate question of WHY restoration is permitted at all.** Conflating those two would have overstated the gap — the PO's warning about the third case cuts both ways, and an inflated gap is also a false gate.

**`HaltedAtGate` has NO producer in `app/`** — it is constructed only in `ConditionSemanticsTest` and the RED guard. **No repository and no port carries one.** **`ResumptionTarget::resolve()` is called nowhere in `app/`** — its only call site in the repository is a domain unit test. **So the chain `halt fact → P-7 → returnsToGate` exists and is authorized, and is unreachable.**

The lane declared this itself at `FillCommitteeSeatHandler.php:77-87` rather than improvising: *"this handler therefore does NOT call P-7 and does NOT construct a `HaltedAtGate` (constructing one would invent the halt fact). It names the election's ESTABLISHED acceptance decision as the return target."* **The substitution is self-reported, not concealed.**

## The classification matrix

| # | Dependency | Source | Class | Evidence |
|---|---|---|---|---|
| **DEP-1** | Meaning of an absent **Committee** (required-existence) | ADR-1 | 🟠 **B** | No contract on AG-1 exposes it; UC-1's inline `InvalidArgumentException` is an **application-side** meaning assignment |
| **DEP-2** | Discriminating the **two `AcceptanceDecision` meanings** (not-yet-reached vs required-but-absent) | ADR-1 §6 row 3–4 | 🟠 **B** | No domain type distinguishes them |
| **DEP-3** | A **policy** that accepts or returns an absence concept | ADR-1 constraint ⑥ | 🟠 **B** | P-1…P-7 surveyed: none does |
| **DEP-4** | **Recovery-origin** — why this `RecoveryProcess` exists | ADR-2 (a)(i), (c) | 🟠 **B** | AG-3 carries `ElectionId`/`PeriodKind`/`PolicyBinding`/intervals/`active`; `RecoveryPeriodStarted` the same four. No origin. |
| **DEP-5a** | **Which gate** restoration returns to — the *contract* | ADR-2 (c) | 🟢 **A** | **P-7 `ResumptionTarget::resolve(HaltedAtGate): GateDesignation` exists and is authorized** |
| **DEP-5b** | A **producer / persistence path for `HaltedAtGate`** so P-7 is reachable | ADR-2 (c), (h) | 🟠 **B** | No producer in `app/`, no repository, no port. **This is the true DEP-5 gap** — narrower than "no concept exists" |
| **DEP-6** | **Why restoration is permitted**, incl. the **`w8` no-`RecoveryProcess`** path | ADR-2 (a)(ii), (b), (h) | 🟠 **B** | P-7 answers *where*, never *why* — and it **requires** a `HaltedAtGate`, so it structurally cannot express a restoration that had no halt-and-recovery history. `ElectionRestored.$returnsToGate` non-nullable. |
| **DEP-7** | UC-3 substituting **`$decision->gate()`** for P-7's answer | committed today | 🔴 **C** | `FillCommitteeSeatHandler.php:140 → :156`. **This is not merely "the app has enough data" — it is the application BYPASSING an existing authorized policy** and answering the policy's question itself. Exact in Model A with one decision; wrong the day two designations are established. |
| **DEP-8** | Reading the **protocol** to recover the halt gate | GREEN-4 candidate | 🔴 **C** | Ruling: *"must not be reconstructed from protocol logs."* Also a new port → separate authorization. **"The protocol already contains the information" is not authorization.** |
| **DEP-9** | Constructing a **`HaltedAtGate` in the Application layer** to feed P-7 | technically available | 🔴 **C** | Would fabricate the halt fact. Ruling (b)/(h) forbid a fabricated `HaltedAtGate` by name. **A reachable constructor is not an ownership grant.** |
| **DEP-10** | **Recovery-absence** = *"nothing to pause"* | UC-3:172 | 🟢 **A** | Grounded in **`EM-GOV-062`** (the clock accrues only while the condition holds): the code **consumes** an adopted governance meaning rather than inventing one. **Must NOT be changed for symmetry with the B items.** |
| **DEP-11** | Inferring provenance from **timestamps or ordering** | ruling (e) | 🔴 **C** | Named prohibited |
| **DEP-12** | Causal meaning from a **command supplied by the appointment body** | ruling (e) | 🔴 **C** | Named prohibited |

**Totals: A = 2 · B = 6 · C = 6.**

## What the matrix means

**No B item can be discharged in the Application layer.** All six are domain concepts or domain-reachability paths.
**Every C item is currently either prohibited-and-absent (DEP-8/9/11/12) or prohibited-and-present-under-quarantine (DEP-7).** Ruling (f) requires DEP-7 to remain exactly as committed until an authorized domain representation exists — so **the gate closes with no repair action**, which is the correct outcome, not an omission.
**The two A items are load-bearing in opposite directions:** DEP-5a proves the domain's *design* was right and only its *reachability* is missing; DEP-10 proves conformant absence handling already exists and must not be "harmonized" away.

## Verdict — unchanged, now precise

> # 🔴 BLOCKED at the domain boundary. Six B-class dependencies. Zero of them are application work.

**Scope of the required slice, as the evidence defines it (Governance describes; it does not authorize):** the halt fact's production and persistence so P-7 becomes reachable (DEP-5b) · the recovery-origin invariant (DEP-4) · the restoration-permission invariant including the `w8` path (DEP-6) · the three ADR-1 absence contracts (DEP-1/2/3). **All five gaps sit in one aggregate set (AG-1, AG-2, AG-3, `Event/`, `Policy/`) and converge on one call site** — `FillCommitteeSeatHandler.php:106/140/156`.

## Next actor

**Not Governance** — the gate is finished; this record is its output.
**Not the implementation lane** — every remaining item is B-class.
**PO/ARB acts next**, to authorize (or withhold) a domain slice. **On the convergence evidence, ONE slice covering DEP-1…DEP-6 rather than two.**
**Then a Domain lane** executes `Domain RED → domain GREEN → domain verification`, after which Application normalization and GREEN-5 may be scheduled.

**Traceability:** ADR-2 §6 (a)–(h) · ADR-1 §6 constraint ⑥ · `7514f145` (ADR-1 gate) · `74fcf5e5` (first pass of this gate) · P-7 `ResumptionTarget` · `HaltedAtGate` · `Q-RESTORE` (registered at RED acceptance) · A-9 · `EM-GOV-059`(c) · `EM-GOV-060` · `EM-GOV-062` · W-8.
