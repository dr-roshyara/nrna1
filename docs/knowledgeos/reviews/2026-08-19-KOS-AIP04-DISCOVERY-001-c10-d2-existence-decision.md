# Decision Registration — C-10 Knowledge Distribution · **D2 CAPABILITY EXISTENCE**

**Work item:** KOS-AIP04-DISCOVERY-001
**Registered by:** Governance (`b64828fe`), on the delivered PO/ARB act 2026-08-19
**Decision:** one option, as required.

---

## 1 · The decision

> # 🟡 **`NOT YET ESTABLISHED`**

**The PO/ARB's reasoning, verbatim:**

> *"C-10 Knowledge Distribution is not yet established as an existing operational capability. The platform has an evidenced need and a governed receipt semantic, but no distinct C-10-owned applicability, acknowledgement, receipt, or invalidation activity is currently realized. Existing delivery behavior is incidental and belongs to other contexts. This decision does not reject the C-10 capability hypothesis; it records that its operational existence has not yet been demonstrated."*

## 2 · ⛔ What this decision is NOT

> **`NOT YET ESTABLISHED` is not `NO`.** The act's own option text — *"the capability hypothesis is plausible, but the estate does not yet demonstrate enough to establish it"* — and the PO/ARB's reasoning both say so explicitly: **the hypothesis is not rejected.**

⛔ It must never be read, cited or summarised as *"C-10 does not exist."*
⛔ It does **not** invalidate **D1**, which remains decided (§4).
⛔ It does **not** close the C-10 line of work; it fixes its current epistemic status.

## 3 · ⚠️ Correction to my own decision-prep — my recommendation was wrong

**I recommended `YES`. The PO/ARB decided `NOT YET ESTABLISHED`. The decision is right and my recommendation was wrong on the estate's own established test** — and this matters more than the disagreement, so it is recorded rather than left implicit.

**The canonical analysis (A1.1) had already proposed `NOT YET ESTABLISHED` for C-10.** My prep did not treat that as the incumbent position it was. Worse, A1.1 states the test the estate actually applies, in the C-5 row:

> **C-5 = `YES` on *two* grounds: ① an ADOPTED invariant (`INV-ATTR-2`) that presupposes the capability, **and** ② the capability has been PERFORMED.**

**C-10 satisfies neither limb.** My load-bearing argument — *"D1 fixes the claim boundary of a C-10 receipt, therefore the subject exists"* — made two errors:

| My error | The record |
|---|---|
| treated **D1, a decided claim-boundary semantic**, as equivalent to an **adopted invariant about the capability** | the C-10 invariant is **`I-K1`, and it is `PROPOSED`, not adopted** — A1.1: *"no invariant about it is adopted"* |
| **ignored the performance limb entirely** | *"no instance of applicability-determination or receipt has ever been performed"* — and my own §1 table said so: four of five activities never occur |

⭐ **The estate's test is conjunctive, and I applied half of it.** A decided semantic *about* an artifact is not the same as a *performed* capability — which is precisely the distinction A1.1 drew when it downgraded C-10's implicit `YES` in the first place.

## 4 · D1 stands, and its status is stated precisely

**D1 remains DECIDED and is untouched:** a C-10 receipt *"is authoritative only for the claim that a specified context package was delivered to and acknowledged by a specified session execution at a specified time"*; it may carry an applicability reference; it does **not** establish correctness of applicability.

> ⭐ **D1 is now a decided semantic awaiting a subject.** The estate has fixed *what a C-10 receipt would authoritatively claim* **before** establishing that C-10 exists operationally. **That is coherent, not contradictory** — deciding the boundary of a claim in advance is what prevents an unbounded receipt later. **But it must not be re-read as evidence of existence, which is the error §3 records.**

`OQ-J`'s result also stands: a receipt could carry **at most 2 of 5** claims — delivery authority and possession authority — never applicability, execution, or compliance.

## 5 · Consequences

| | |
|---|---|
| **The artifact needs no change** | ⭐ the analysis's executive table and A1.1 **already carry `NOT YET ESTABLISHED`** for C-10. **This decision RATIFIES the Architecture proposal.** The mid-verification artifact is **not edited** — its `PROPOSED` value simply becomes `DECIDED` |
| **Category** | remains **DEFERRED**, and now consistently so: A1.1 — *"a category for a not-yet-established capability would be premature."* The candidate (*cross-layer delivery capability*, smallest boundary **applicability + receipt**) stays a candidate |
| **`I-K1`** | remains **`PROPOSED`**; its three undefined terms (*valid · applicable · required by the act*) remain undefined |
| **Maturity** | **evidenced need, unrealized capability** — `EKS-01` evidences the need; no C-10-owned activity occurs |
| **Excluded evidence** | missing STARTs, off-record execution and the Amendment-1 provenance gap remain **BC-7 lifecycle**, not C-10 |
| **Other contexts** | unchanged — BC-1 owns creation/governance/publication (`CAP-03` not built); BC-6 owns retrieval/assembly/bootstrap (`CAP-01` live); delivery leaks through BC-7's `tokenRef` (`EKS-01`). **Incidental delivery is theirs, not C-10's** |

## 6 · ⭐ Sequencing question the PO/ARB should see before the next decision

The act names the next decision as **C-10 authoritative state / lifecycle**. **That decision is not blocked by this one — it may be the lever that changes it.**

Per §5.3 pt 22 and A1.1's own test: **adopting the receipt as authoritative state would supply limb ①** (an adopted commitment about C-10), and the analysis already calls it *"the decision that changes the category."*

**So the evidence threshold for revisiting D2 is now explicit** *(ES-002 — what evidence would justify change)*:

1. **adoption of `I-K1`, or of the receipt as authoritative state** → satisfies limb ① *(an adopted invariant/state about C-10)*; **or**
2. **one performed instance** of applicability-determination or acknowledgement → satisfies limb ② *(performance)*.

⚠️ **Either limb alone was enough for C-5's `YES` only because C-5 had both.** Whether **one** limb suffices to move C-10 from `NOT YET ESTABLISHED` to `YES` is itself **`OPEN`** — and Governance does not decide it.

## 7 · Explicitly not decided

⛔ bounded context · cross-context capability vs stewardship · **ownership** · **Knowledge Engineer ownership — a forbidden hypothesis** · `OQ-K` · `OQ-B` (who owns applicability) · halt-or-warn · implementation technology · final receipt schema · **authoritative state / lifecycle (the next decision)** · whether one limb of the existence test suffices.

**Traceability:** PO/ARB act 2026-08-19 (D2, with reasoning verbatim) · **D1 as decided** · decision-prep `2026-08-19-…-c10-existence-decision-prep.md` (recommendation `YES` — **not followed; corrected in §3**) · canonical analysis A1.1 (the two-limb test; C-10's proposed `NOT YET ESTABLISHED`), §5.1–5.4, A1.3 (`I-K1`, `OQ-J`, seven states) · `EKS-01` · `CAP-01`/`CAP-03` · BC-1 / BC-6 / BC-7 · `INV-ATTR-2` (adopted, `P-1`–`P-6`)

---
---

# APPENDED 2026-08-19 — **D3 · AUTHORITATIVE STATE AND LIFECYCLE**

> **This file is the single C-10 decision register.** D3 is appended here, not placed in a new document, per the act's *"do not create a second decision surface."* **Nothing above is rewritten.** ⛔ **The canonical C-10 analysis is NOT modified.**

## D3.1 · The decision

> # ✅ **D3 = DECIDED — OPTION C / SPLIT**
> **C-10's candidate boundary is the receipt / acknowledgement lifecycle.**

**The candidate C-10 boundary includes:**

| | |
|---|---|
| **receipt identity** | |
| **receipt lifecycle state** | |
| **version / supersession invalidation** | |
| **completeness of the receipt** | **at the defined delivery / acknowledgement point** |

## D3.2 · Outside the C-10 candidate boundary

applicability determination · delivery transport · session execution · **identity attestation / identity binding** · **dispute adjudication** · execution authority · knowledge application · compliance / verification.

> ### ⭐ **The two non-equivalences that carry this decision**
> **C-10 MAY record references to these external facts. It does NOT thereby attest their correctness.**
>
> | | |
> |---|---|
> | **recording session identity** | **≠ attesting session identity** |
> | **recording an applicability reference** | **≠ attesting correctness of applicability** |

**This is the D3 prep's §2 finding, adopted as decided boundary:** the platform cannot attest *"a specified session execution"* (`INV-ATTR-1`; `INV-ATTR-2`, adopted `P-1`–`P-6`), so identity binding is placed **outside** C-10 — a capability may not own authoritative state it cannot establish. **Dispute adjudication is likewise excluded**, which keeps D3 from prejudging bounded-context status.

## D3.3 · Relation to D1 — unchanged

**D1 stands.** The C-10 receipt is authoritative **only** for the previously decided, claim-scoped delivery / acknowledgement proposition.

> **The applicability reference is a TRACEABILITY REFERENCE ONLY.**

It does **not** establish: applicability · correctness of applicability · comprehension · understanding · application · compliance · verification · **organizational independence**.

*(⭐ The last item ties D1's limits to `C-5`'s standing `ORGANIZATIONAL_INDEPENDENCE = NOT_ESTABLISHED` — a receipt cannot supply what C-5 does not have.)*

## D3.4 · ⛔ Relation to D2 — the CRITICAL NON-CONSTITUTIVE CLAUSE

> **This decision defines the CANDIDATE ARCHITECTURAL BOUNDARY of C-10.**
> **It does NOT establish that C-10 currently exists as a realized capability.**
> **D2 remains: C-10 = `NOT YET ESTABLISHED`. D2 is NOT changed as a consequence of D3.**

**The three-way distinction must remain, and is recorded as binding:**

```
candidate capability boundary  ≠  capability existence  ≠  capability realization
```

⭐ **This closes the back-door risk the D2 registration §6 flagged:** adopting receipt state *constitutively* would have supplied limb ① of the existence test and moved D2 without an existence decision. **The clause makes D3 a specification, not an establishment.** The prep's recommended safeguard (§4.1) is adopted in the act's own words.

## D3.5 · Candidate lifecycle — not finalized

```
ISSUED → VALID → SUPERSEDED → INVALIDATED → EXPIRED / DISPUTED (where later decided)
```

**Exact semantics remain subject to later decisions and Architecture design.** ⛔ **D3 does NOT finalize:** dispute semantics · expiry semantics · **invalidation authority** · implementation state model.

## D3.6 · DDD boundary

**Candidate C-10 responsibility: receipt / acknowledgement state and lifecycle.**

**C-10 does not absorb:** knowledge creation · knowledge publication · delivery transport · applicability authority · session execution · identity attestation · execution permission · application · compliance.

> ### ⭐ **"Dependencies are not ownership."** — recorded as a binding boundary rule of this decision.

## D3.7 · ⚠️ Two observations carried forward to D4 — recorded, not decided

**1 · `INVALIDATED` has no owner and no authority.** The lifecycle includes `SUPERSEDED` and `INVALIDATED`, but D3 explicitly does not decide **invalidation authority** — and §5.1 records **monitoring / invalidation owner = 🔴 nobody**. **D4 (behaviour when knowledge or receipt is stale / superseded / invalidated) therefore depends on a trigger nobody owns.** ⛔ Recorded as a dependency, not converted into ownership.

**2 · "the defined delivery / acknowledgement point" is NOT stated to be START.** The prep proposed completeness *"at START"*; **the decision deliberately says the delivery / acknowledgement point instead.** That decouples completeness from START — whereas **`I-K1`** speaks of *"before a governed act begins."* **`I-K1` remains `PROPOSED`**, and whether the acknowledgement point coincides with START is **`OPEN`** and material to D4.

## D3.8 · Non-decisions

⛔ whether C-10 is a bounded context · whether C-10 is a cross-context capability · ownership / stewardship · **Knowledge Engineer ownership** · `OQ-K` · `OQ-B` · implementation technology · build order · dispute semantics · expiry semantics · invalidation authority · implementation state model · **D4**.
⛔ **No second C-10 architecture model created. The canonical analysis is unmodified.**

## D3.9 · Status

| | |
|---|---|
| **D1** | DECIDED — unchanged |
| **D2** | **`NOT YET ESTABLISHED`** — unchanged by D3 |
| **D3** | ✅ **DECIDED — OPTION C / SPLIT** |
| **Next** | **D4** — behaviour when required knowledge or its receipt is missing · stale · superseded · invalidated · disputed |

**Traceability (D3):** PO/ARB act 2026-08-19 (D3, Option C / SPLIT, with the non-constitutive clause) · **D1** · **D2** and its registration §6 · D3 decision-prep `2026-08-19-…-c10-d3-decision-prep.md` (recommended **C** + the non-constitutive clause — **both adopted**; the identity-binding and dispute-adjudication carve-outs adopted into the boundary) · canonical analysis §5.1, §5.2, §5.3 pt 22, A1.3 (`I-K1`, `OQ-J`, seven states) · `INV-ATTR-1`/`INV-ATTR-2` (adopted `P-1`–`P-6`) · `C-5` `ORGANIZATIONAL_INDEPENDENCE = NOT_ESTABLISHED`

---
---

# APPENDED 2026-08-19 — **D4.1 · ENFORCEMENT PRINCIPLE**

> Appended to the single C-10 decision register, per the act's recording rule. **Nothing above rewritten.** ⛔ **Canonical C-10 analysis NOT modified.**

## D4.1.1 · The decision

> # ✅ **D4.1 = DECIDED — RISK / CONTEXT-PROPORTIONATE ENFORCEMENT**

**C-10 enforcement strength shall be proportionate to:** the governed risk of the knowledge · the applicability context · the authority level required · **the availability AND authority of a valid replacement** · the escalation requirements.

## D4.1.2 · Allowed enforcement effects

| Effect | Meaning |
|---|---|
| **INFO** | record the condition; no enforcement action required |
| **WARN** | notify the relevant actor; execution may continue subject to applicable policy |
| **BLOCK** | prevent the governed action until the condition is resolved **or an explicitly authorized exception applies** |
| **HALT + ESCALATE** | stop execution; escalate to the designated authority for resolution |

> ⛔ **These are EFFECTS, not condition-specific mappings.** No condition is mapped to any effect by this act.

## D4.1.3 · ⭐ Compensating control — an EXCEPTION MECHANISM, not a fifth effect

**Where a compensating control is authorized, it must preserve:** the unresolved condition · the limitation · the authority granting the exception · the evidence supporting the exception · the resulting assurance status.

> ## ⛔ **CRITICAL CONSTRAINT**
> **A compensating control MUST NOT convert `NOT ATTESTED` into `PROVEN` or `COMPLIANT`.**
> **It MUST NOT silently convert an unresolved governance condition into an ordinary successful result.**

**This corrects the D4 prep's framing.** That prep offered *"F as the transition mechanism"* **alongside** the effects; D4.1 rules that a compensating control is **categorically not an effect** — it is an exception carrying its own preservation duties. **The demotion is the substantive content, and it is recorded as binding.**

## D4.1.4 · ⚠️ The same prohibition now stands in three places — recorded as an OBSERVATION

| Where | The rule |
|---|---|
| **independence classification** | *"If lineage cannot be established: `NOT ATTESTED` — **not `PASS`**"* (PO/ARB 2026-08-19) |
| **`C-5` separation dimensions** | `ORGANIZATIONAL_INDEPENDENCE` and **external attestation** = **`NOT_ESTABLISHED`**, explicitly **never `PASS`, never `NOT_APPLICABLE`** (C3.3) |
| **D4.1 compensating controls** | **must not convert `NOT ATTESTED` into `PROVEN` / `COMPLIANT`** |

⭐ **One prohibition, three surfaces: an unresolved status may never be laundered into a resolved one.** ⚠️ **Recorded as an `OBSERVATION` bearing on the canonical §8.3 unifying-invariant candidate — NOT promoted, and NOT conflated with it.** §8.3's candidate is *"defeated by self-assertion by the gated party"*; this is **adjacent but distinct** — it concerns status laundering, not self-assertion. **Three occurrences is evidence, not a standard** (`ES-006.1`).

## D4.1.5 · ⭐ The category-deciding moment is DEFERRED, not avoided

**D4.1 is category-neutral, and precisely because it maps nothing.** Per §5.3 pt 13/23, C-10 becomes control-plane **if it gates START**. With **no condition mapped to `BLOCK` or `HALT`**, C-10 gates nothing and remains advisory ⇒ **category undecided, as the act requires.**

> ⚠️ **But the moment any condition is mapped to `BLOCK` or `HALT + ESCALATE`, pt 13 fires and the category is decided by consequence.**

⇒ **`D4.2` (condition semantics only) is safe.** **The mapping act that follows it is the category-deciding act** — and will need D3's non-constitutive clause explicitly, or it will move C-10's category without a category decision. **Flagged now, so it is not discovered afterwards.**

## D4.1.6 · Two constraints carried forward to the mapping act, untouched by D4.1

**Neither is addressed by D4.1 — correctly, since it maps nothing — and both remain live:**

1. **`Missing` is currently 100 % of cases.** D2 ⇒ zero receipts exist. Any `BLOCK`/`HALT` mapped to `Missing` stops the estate on the day it is enabled.
2. **The gate cannot bind receipt → session.** `INV-ATTR-1` (no gate reads identity) + D3.2 (identity attestation/binding **outside** C-10) ⇒ **even a `Missing` check cannot verify that a presented receipt belongs to the session presenting it.**

## D4.1.7 · Sequencing — the PO/ARB's cut is stronger than the prep's

The D4 prep proposed splitting the five conditions **by detectability**. **D4.1 instead separates *principle* from *mapping*, and makes mapping wait on *semantics*.** ⭐ **That is the better cut:** definitions precede both detectability and enforcement, and *"do not map enforcement effects until the condition semantics have been defined and decided"* prevents a mapping from silently defining the condition it maps. **Recorded as adopted in preference to the prep's proposal.**

## D4.1.8 · Non-decisions

⛔ the meaning of **MISSING · STALE · SUPERSEDED · INVALIDATED · DISPUTED** · condition-specific enforcement mapping · C-10 existence · C-10 category · ownership / stewardship · `OQ-K` in full · implementation technology · build order · lifecycle implementation.
⛔ **No ownership assigned · no bounded context created · no capability realization established.**

## D4.1.9 · Status

| | |
|---|---|
| **D1** | DECIDED — unchanged |
| **D2** | **`NOT YET ESTABLISHED`** — unchanged |
| **D3** | DECIDED — **Option C / SPLIT**, non-constitutive boundary — unchanged |
| **D4.1** | ✅ **DECIDED — RISK / CONTEXT-PROPORTIONATE ENFORCEMENT** |
| **Next** | **D4.2 — condition semantics** (define MISSING · STALE · SUPERSEDED · INVALIDATED · DISPUTED separately; **no mapping**) |

**Traceability (D4.1):** PO/ARB act 2026-08-19 (D4.1, ADOPT) · **D1 · D2 · D3** · D4 governance input `2026-08-19-…-c10-d4-decision-prep.md` (§6 F-as-mechanism — **corrected by D4.1.3**; §7 detectability split — **superseded by D4.1.7**) · canonical analysis §5.3 pt 12/13/23, §8.3, C3.3 · `INV-ATTR-1` · D3.2 · PO/ARB independence act 2026-08-19 (`NOT ATTESTED` ≠ `PASS`) · `ES-006.1`

---
---

# APPENDED 2026-08-19 — **D4 · ENFORCEMENT MODEL**

> Appended to the single C-10 decision register. **Nothing above rewritten.** ⛔ **Canonical C-10 analysis NOT modified.**
>
> ⚠️ **Register ordering note, stated so no later reader is misled:** **`D4.1` was delivered and registered BEFORE this `D4`.** The numbering therefore runs against the clock in this file. **`D4` does not supersede `D4.1`; it scopes it** — see D4.0 below. Neither entry is rewritten.

## D4.0 · How D4 relates to D4.1

| | D4.1 (registered first) | D4 (this act) |
|---|---|---|
| **What it fixed** | the **principle** is ADOPTED; the **permitted effect vocabulary** — INFO · WARN · BLOCK · HALT+ESCALATE; compensating control demoted to an exception mechanism | the principle is **a CANDIDATE / TARGET model**; the **currently ENABLED ceiling**; which conditions are decidable now |
| **Status of the principle** | "DECIDED — risk/context-proportionate enforcement" | **qualified**: *"DECIDED AS A TARGET ENFORCEMENT PRINCIPLE, NOT AS ENABLED ENFORCEMENT"* |

> ⭐ **The distinction that reconciles them: `D4.1` defines the permitted effect VOCABULARY; `D4` defines the currently ENABLED SET.** `BLOCK` and `HALT + ESCALATE` remain **legal effects** in the vocabulary and are **not enabled** by any mapping. **Vocabulary ≠ enabled set.**

## D4's decision content

*(Governance correction, made before commit: this heading was first written as "D4.1(this act)" — a mislabel, since this act is **D4**, not D4.1. Corrected here; no decision content was affected.)*

### 1 · Target principle — **CANDIDATE MODEL**

**RISK / CONTEXT-PROPORTIONATE ENFORCEMENT**, adopted as the intended decision principle only.

⛔ Does **not**: enable enforcement · grant C-10 control-plane authority · decide C-10 category · establish C-10 existence · assign ownership. **D2 remains `NOT YET ESTABLISHED`.**

### 2 · ⭐ Current enforcement floor

> ## **`INFO` / `WARN` + `RECORD` is the maximum generally applicable C-10 response.**
> **No `BLOCK` or `HALT` behaviour is enabled by this decision.**

**Conditional on four prerequisites, named in the act:** the required **state** · **ownership** · **identity binding** · **triggers**.

### 3 · The only currently decidable condition — `MISSING`

**`MISSING` is presently the dominant / effectively universal case**, because D2 ⇒ no C-10 receipt exists.
⛔ **No Tier-1 `HALT` and no global `BLOCK` is authorized.**

### 4 · Conditions remaining OPEN

**`STALE` · `SUPERSEDED` · `INVALIDATED` · `DISPUTED`** — **OPEN pending authoritative state and TRIGGER OWNERSHIP.** ⛔ **Do not assign enforcement responses to these yet.**

### 5 · Compensating control

The named governance exception mechanism; must preserve unresolved condition · limitation · granting authority · supporting evidence · resulting assurance state. ⛔ **MUST NOT convert `NOT ATTESTED` into `PROVEN` / `COMPLIANT`.** *(Consistent with D4.1.3; the constraint is restated, not changed.)*

### 6 · ⛔ Tier definitions — **NOT YET DEFINED, and not to be invented**

> **"Do not invent Tier 1 = Critical, Tier 2 = High, etc. Architecture must define or propose the tier semantics separately."**

⭐ **This explicitly retires the tier matrix from the D4 options analysis.** The four-tier labels presented there (Critical / High / Medium / Low) and its condition×tier mapping are **NOT adopted** and **must not be cited as decided**. **Tier semantics are Architecture's to propose, under separate authorization.**

### 7 · Current state, as recorded

```
TARGET       risk / context-proportionate enforcement
FLOOR        WARN + RECORD
ENFORCEMENT  not enabled
HALT/BLOCK   not authorized by this decision
```

## D4.2 · Two Governance-flagged constraints are ADOPTED as decision content

**Both constraints carried forward at D4.1.6 are taken up by this act** — recorded factually:

| Flagged (D4.1.6) | Adopted as |
|---|---|
| **`Missing` is 100 % of cases** ⇒ any BLOCK/HALT on it stops the estate | **§3** — *"MISSING is presently the dominant / effectively universal case. No Tier-1 HALT or global BLOCK is authorized."* |
| **the gate cannot bind receipt → session** (`INV-ATTR-1` + D3.2) | **§2** — **identity binding** is named as one of the four prerequisites gating any response above the floor |

⭐ **`INVALIDATED`'s ownerless trigger (D3.7 obs. 1) is likewise adopted:** §4 makes **trigger ownership** an explicit precondition for the four open conditions, rather than leaving it implicit.

## D4.3 · ⚠️ The category-deciding flag remains LIVE

**D4.1.5's flag is neither discharged nor weakened by this act — it is confirmed.** With no condition mapped to `BLOCK`/`HALT`, C-10 gates nothing ⇒ **advisory ⇒ category undecided**, exactly as §1/§8 require.

> **The mapping act that eventually assigns `BLOCK` or `HALT + ESCALATE` to any condition is the CATEGORY-DECIDING ACT** (canonical §5.3 pt 13/23: *control-plane if it gates START*). **It will require D3's non-constitutive clause explicitly, or it will move C-10's category without a category decision.**

**`D4.2` (condition semantics and detectability) does not trigger this** — defining a condition is not mapping an effect to it.

## D4.4 · Non-decisions

⛔ C-10 existence · C-10 category · C-10 ownership · **C-10 control-plane status** · detailed `Missing` mapping beyond the current floor · `Stale` / `Superseded` / `Invalidated` / `Disputed` mappings · implementation · **tier semantics**.

## D4.5 · Status

| | |
|---|---|
| **D1** | DECIDED — unchanged |
| **D2** | **`NOT YET ESTABLISHED`** — unchanged |
| **D3** | DECIDED — Option C / SPLIT, non-constitutive — unchanged |
| **D4.1** | DECIDED — enforcement principle + effect vocabulary + compensating-control demotion — **scoped by D4, not superseded** |
| **D4** | ✅ **DECIDED AS A TARGET ENFORCEMENT PRINCIPLE, NOT AS ENABLED ENFORCEMENT** · floor = `WARN` + `RECORD` |
| **Next** | **D4.2 — semantics and DETECTABILITY of the individual conditions** |

**Traceability (D4):** PO/ARB act 2026-08-19 (D4, enforcement model) · **D4.1** and its register entry (D4.1.3, D4.1.5, D4.1.6) · **D1 · D2 · D3** (D3.2, D3.5, D3.7) · D4 governance input `2026-08-19-…-c10-d4-decision-prep.md` §3 (the 100 %-`Missing` arithmetic and the receipt→session binding gap — **both adopted**) and §7 (floor · Missing-only · defer tiers — **adopted**) · canonical analysis §5.1, §5.3 pt 12/13/23 · `INV-ATTR-1` · `CAP-09` (**not** an extension point) · `ES-006.1`

---
---

# APPENDED 2026-08-19 — **D4.3 · CONDITION-SPECIFIC RESPONSE MAPPING** *(PARTIALLY DECIDED)*

> Appended to the single C-10 decision register. **Nothing above rewritten.** ⛔ **Canonical C-10 analysis NOT modified.**
>
> ⚠️ **HEADING DISAMBIGUATION, recorded because it would otherwise mislead:** in the earlier **D4** entry, the headings `D4.0`, `D4.2`, `D4.3`, `D4.4`, `D4.5` are **internal subsection numbers of that entry** — written before decisions named `D4.2` and `D4.3` existed. **They are NOT decision names.** In particular *"D4.3 · The category-deciding flag remains LIVE"* is a subsection of the D4 entry, **not** this decision. **From this entry onward, decision entries prefix their sections `DEC-`.** ⛔ No earlier heading is rewritten.

## DEC-D4.3 §1 · The decision

> # 🟡 **D4.3 = PARTIALLY DECIDED**

| | |
|---|---|
| ✅ **Fully decided** | **M4 = `NOT ATTESTED`, never `MISSING`** |
| 🟡 **All other mappings** | **OPEN**, pending their **authoritative trigger · authority · cross-context response policy** |

## DEC-D4.3 §2 · ⭐ The mandatory rule now has FIVE prerequisites, not four

**A response may be decided only where ALL five are established:**

1. the condition is **semantically defined**;
2. the trigger is **authoritative**;
3. the trigger is **detectable**;
4. the **responding authority** is established;
5. ⭐ **the response is within the AUTHORIZED BOUNDARY of C-10.**

> ⚠️ **Criterion 5 is new and load-bearing.** The D4.3 prep tested only four (semantic · authoritative · evidence source · detectable). **Criterion 5 is what independently excludes `M5` (BC-1's condition) and `M3` (infrastructure)** — the prep reached those exclusions by argument from D3.6; **the decision makes the test carry them formally.**
>
> *(Governance note: the PO/ARB's accompanying summary message lists four prerequisites. **The decision act itself states five.** The five govern; recorded so the shorter list is not later cited as the test.)*

## DEC-D4.3 §3 · Condition results, as decided

| Condition | Status | Recorded reason |
|---|---|---|
| **M1** receipt never issued | **OPEN** | C-10 `NOT YET ESTABLISHED`; **no realized receipt registry exists**. ⛔ *"Do not invent a permanent WARN/INFO mapping for a future mechanism"* |
| **M2** applicability not established | **OPEN / UPSTREAM CONDITION** | applicability is **outside C-10**; no response until applicability **authority and trigger** exist |
| **M3** receipt not retrievable | **OPEN / INFRASTRUCTURE CONDITION** | ⛔ do not classify an infrastructure retrieval failure as a C-10 governance condition **without an explicit cross-context response policy** |
| **M4** present but unbindable | ✅ **DECIDED — `NOT ATTESTED`** | see §4 |
| **M5** knowledge itself absent | **OPEN / UPSTREAM KNOWLEDGE CONDITION** | knowledge existence/publication is **outside C-10** |
| **SUPERSEDED** | **OPEN** | objective knowledge-version event. ⛔ **do not auto-map to `STALE` or to WARN/BLOCK/HALT.** Response depends on authoritative replacement · replacement availability · applicability · authority for the governed act |
| **STALE** | **OPEN** | contextual sufficiency for a **particular** act. ⛔ **do not infer `STALE` merely from publication of a newer version.** Applicability required |
| **INVALIDATED** | **OPEN** | **invalidation authority not established**; no mapping may be assigned |
| **DISPUTED** | **OPEN** | orthogonal to lifecycle; ⛔ **not a terminal state**; **no adjudication authority established** |

## DEC-D4.3 §4 · ⭐ M4 — the one decided mapping, and it bars BOTH directions

> ## **M4 = `NOT ATTESTED`. Never classify M4 as `MISSING`.**
> **"Recording identity ≠ attesting identity."**
> **"A failure to establish binding must remain unresolved rather than being converted into either *'no receipt'* or *'valid receipt'*."**

⭐ **The bidirectional bar is the substantive content.** The prep emphasised the `MISSING` direction; **the decision bars conversion to `valid receipt` equally.** Both directions launder an unresolved status into a resolved one — the prohibition now standing in **four** places *(cf. D4.1.4's three)*.

⚠️ **Note what D4.3's only decided content is: a CLASSIFICATION rule, not an ENFORCEMENT mapping.** It constrains how a condition may be **named**, not what happens when it occurs. **No effect is attached to M4.**

## DEC-D4.3 §5 · ✅ No enforceable mapping exists — and the category flag is still not reached

**No condition is mapped to `BLOCK` or `HALT + ESCALATE`.** ⇒ **C-10 gates nothing ⇒ C-10 remains advisory ⇒ its category remains undecided**, as the act requires.

> **The category-deciding moment (canonical §5.3 pt 13/23) is not reached by D4.3. The flag remains live for whichever future act first maps an enforcing effect.**

## DEC-D4.3 §6 · Governance proposals NOT adopted — recorded plainly

| Prep proposal | Outcome |
|---|---|
| **`INFO` + `RECORD` as the proportionate baseline for M1** | ⛔ **NOT adopted as a mapping.** M1 is `OPEN`, and *"do not invent a permanent WARN/INFO mapping for a future mechanism."* The **D4 floor stands unchanged as the general baseline**; no M1-specific mapping is created |
| **the `DISPUTED` effect** (*suspends use for the disputed claim only*) | ⛔ **NOT adopted.** `DISPUTED` is `OPEN`. **Consistent with the prep's own warning** that the effect must not be adopted while the right to raise is ungoverned |
| the four-prerequisite test | **superseded by the five-prerequisite test** (§2) |

## DEC-D4.3 §7 · Non-decisions

⛔ C-10 category · ownership / stewardship · bounded context · implementation · **risk tiers** · detailed BLOCK/HALT policy · applicability authority · identity attestation · invalidation authority · dispute adjudication ownership · expiry semantics.
**D1 · D2 (`NOT YET ESTABLISHED`) · D3 (Option C / SPLIT) · D4.1 · D4** — all unchanged.

## DEC-D4.3 §8 · Status

| Decision | Status |
|---|---|
| **D1** receipt semantics | ✅ DECIDED |
| **D2** capability existence | 🟡 **`NOT YET ESTABLISHED`** |
| **D3** state / lifecycle | ✅ DECIDED — Option C / SPLIT |
| **D4.1** enforcement principle | ✅ DECIDED — proportionate |
| **D4** enforcement model | ✅ DECIDED as TARGET, not enabled · floor `WARN`+`RECORD` |
| **D4.2** condition semantics | ✅ DECIDED as the semantic basis |
| **D4.3** response mappings | 🟡 **PARTIALLY DECIDED — M4 only; NO ENFORCEABLE MAPPING** |
| **Category** | ⏳ OPEN |
| **Ownership** | ⏳ OPEN |

**Traceability (D4.3):** PO/ARB act 2026-08-19 (D4.3, partially decided) · D4.3 prep `2026-08-19-…-c10-d4-3-response-mapping.md` (§2 table · §3 DISPUTED effect **not adopted** · §4 M1 floor **not adopted** · four-prerequisite test **superseded**) · **D1 · D2 · D3** (D3.2, D3.6) · **D4.1** (D4.1.4, D4.1.5) · **D4** · **D4.2** · canonical §5.3 pt 13/23 · `INV-ATTR-1`/`INV-ATTR-2` · BC-1

---
---

# APPENDED 2026-08-19 — **D5 · ESTABLISHMENT CRITERIA** *(DECIDED)*

> Appended to the single C-10 decision register. **Nothing above rewritten.** ⛔ **Canonical C-10 analysis NOT modified.**

## DEC-D5 §1 · The decision, as delivered

> # ✅ **D5 = DECIDED**
> **Adopt the D5 establishment criteria model for evaluating whether the C-10 receipt capability may be established.**

| | |
|---|---|
| **Scope of the criteria** | **they evaluate realization of the D1 receipt claim ONLY** |
| **They do NOT decide** | bounded context classification · ownership · enforcement authority · implementation technology |
| **Establishment gate** | **C-10 establishment requires a PO/ARB determination that the minimum sufficient evidence combination has been satisfied** |

**Minimum criteria, as named:** `E1` Semantic fidelity · `E2` Receipt completeness · `E3` Claim authority decision · `E4` Write protection · `E5` Lifecycle realization · `E6` Repeatability · `E7` Boundary discrimination · `E8` Provenance reconstruction · `E9` Negative claim discipline.

**`E10` Operational repeatability — a MATURITY criterion**, and therefore **outside the minimum set**. *(This is the one compositional fact the act states explicitly, and it is recorded as such.)*

**D2 is not changed by this act. Category remains OPEN — D6 next.**

## DEC-D5 §2 · ⚠️ Provenance — the commissioned analysis was never delivered

**Verified against the estate before registering:**

| Check | Result |
|---|---|
| `G-KOS-AIP04-C10-D5` | **AUTHORIZED — undelivered** |
| `G-KOS-AIP04-C10-D5-AMD1` | **AUTHORIZED — undelivered** |
| a D5 analysis artifact | 🔴 **none exists** |
| a D5 workflow lane (REGISTER / HANDOFF / START) | 🔴 **none — 27 transitions, last is `START S5`** |
| the nine criterion names, searched across `docs/`, `engineering/`, `.claude/` | 🔴 **eight of nine appear NOWHERE in the estate** *(only "semantic fidelity" occurs, in unrelated contract-neutrality material)* |

> ### ⛔ **The adopted criteria have no artifact in the governed record.**
> They arrive as **names only**, from outside the governed channel, while the Architecture commission that would have produced and justified them **remains open and undischarged.**

**⭐ This is the THIRD occurrence of the same pattern in this estate** — after **A1.0** (*"the source review is not in the estate — recorded, not worked around"*) and the absent-artifact recurrence already recorded as bearing on C-10/Governance-intake. **Recorded as `OBSERVED` evidence under `ES-006.1`; three occurrences are evidence, not a standard, and no methodology is promoted here.**

⛔ **The decision is registered as made.** Governance records provenance; it does not refuse a PO/ARB act for lacking it.

## DEC-D5 §3 · What this register therefore CANNOT state

**Because no artifact defines them, the record holds nine LABELS, not nine criteria.** For each of `E1`–`E10` the estate cannot say:

- **what it requires** — no definition;
- **what evidence satisfies it** — no evidence specification;
- **which evidence class it belongs to** — A realization / B boundary / C excluded *(the class scheme the act itself mandated)*;
- **whether it is `NECESSARY` / `SUFFICIENT` / `SUPPORTING` / `NOT SUFFICIENT`** — the marking the work statement required for every criterion;
- **its counterexamples** — deliverable item (6).

> ⚠️ **`E1`…`E10` must not be cited as a defined criteria model until an artifact defines them.** Citing a label as though it carried a test is the same laundering the register has prohibited four times *(D4.1.4, D4.3 §4)*: **an undefined name is not a satisfied definition.**

## DEC-D5 §4 · Two deliverable items are absent, and both are load-bearing

| Missing | Why it matters |
|---|---|
| **(4) the MINIMUM SUFFICIENT COMBINATION** | The act lists nine under *"Minimum criteria"* but **does not state whether all nine are required.** ⚠️ Reading the list as conjunctive would **invent a nine-part conjunctive test** — precisely what the work statement forbade: *"do not invent a conjunctive test without justification."* **Governance does not resolve the ambiguity in either direction; it records that the combination is unstated.** |
| **(7) the D2 REVISIT TRIGGER** | ⛔ **Absent.** ⇒ **the mechanism to reopen D2 still does not exist.** D5 was commissioned precisely to supply an *observable and auditable* trigger; **without it, satisfaction of E1–E9 has no defined moment at which it is noticed** — and D4.3 established that **monitoring is owned by nobody**, so nothing will surface it. |

## DEC-D5 §5 · Disposition of the open commission — UNRESOLVED

`G-KOS-AIP04-C10-D5` and `-AMD1` **remain AUTHORIZED and undelivered.** The act does not close, revoke or supersede them.

**Two readings, and Governance selects neither:**

1. the commission is **superseded** by this adoption ⇒ it should be explicitly **CLOSED**, or the record carries a permanently open grant;
2. the commission **stands** ⇒ the eventual Architecture analysis must **define `E1`–`E10` and reconcile with them**, supplying §3's definitions and §4's two missing items.

⚠️ **Reading 2 is the only one that repairs §3 and §4.** ⛔ **Requires a PO/ARB act either way.**

## DEC-D5 §6 · Status

| Decision | Status |
|---|---|
| **D1** receipt semantics | ✅ DECIDED |
| **D2** capability existence | 🟡 **`NOT YET ESTABLISHED`** — unchanged |
| **D3** state / lifecycle | ✅ DECIDED — Option C / SPLIT |
| **D4.1 / D4** enforcement | ✅ DECIDED — target principle, not enabled |
| **D4.2** condition semantics | ✅ DECIDED |
| **D4.3** response mappings | 🟡 PARTIALLY DECIDED — M4 only |
| **D5** establishment criteria | ✅ **DECIDED — model adopted; ⚠️ criteria undefined in the estate (§3), combination unstated (§4), revisit trigger absent (§4)** |
| **Category** | ⏳ OPEN — **D6 next** |
| **Ownership** | ⏳ OPEN |

**Traceability (D5):** PO/ARB act 2026-08-19 (D5, DECIDED) · `G-KOS-AIP04-C10-D5` + `-AMD1` (**open, undelivered**) · D5 commission record · **D1 · D2 · D3 · D4.1 · D4 · D4.2 · D4.3** · A1.0 (first absent-artifact occurrence) · D4.3 (monitoring unowned) · `ES-006.1` · estate search 2026-08-19 (eight of nine names absent)

---
---

# APPENDED 2026-08-19 — **D5 · AMENDMENT 1 — controlled correction of the D5 status**

> Appended append-only. **The D5 entry above is NOT rewritten** — its decision text stands as delivered, and this amendment qualifies its STATUS.

## DEC-D5-AMD1 §1 · The corrected status, verbatim as directed

```
D5 STATUS:        DECIDED — framework adopted.
PROVENANCE STATUS: Architecture artifact pending.
```

**The adopted decision establishes:** the **purpose** of D5 · the **required separation from D6/D7** · the **need for** establishment criteria.

**The following remain OPEN until Architecture delivery:** criterion definitions `E1`–`E10` · evidence classification · **minimum sufficient combination** · **revisit trigger**.

> ⛔ **D5 must NOT be read as "criteria defined by Architecture."** The accurate state is: **decision model adopted provisionally, on proposal material, with the provenance gap recorded.**

**The PO/ARB's own formulation, adopted into the record:**

> ### **"A good model is not the same as an authorized architectural deliverable."**

## DEC-D5-AMD1 §2 · ⭐ The minimum sufficient combination is OPEN — Option A must NOT be assumed

**The conjunctive reading `E1 ∧ E2 ∧ … ∧ E9` was PROPOSAL MATERIAL, never an adopted rule.** Three candidate outcomes are named, and **none is presumed**:

| | Candidate |
|---|---|
| **A** | **all required** — full conjunction |
| **B** | **core** `E1 ∧ E2 ∧ E6 ∧ E7 ∧ E8 ∧ E9` **+ context-dependent** `E3`/`E4`/`E5` |
| **C** | **assurance levels** — prototype · operational · authoritative establishment |

> ⛔ **"Do not import a conjunctive test without evidence that the domain requires it."** — the C-5 lesson, now governing D5.

*(This closes the ambiguity the register flagged at DEC-D5 §4 — not by resolving it, but by making the resolution an explicit required decision rather than a silent reading.)*

## DEC-D5-AMD1 §3 · The revisit trigger needs three parts, all required

```
observable trigger  +  evidence source  +  responsible observation path
```

⛔ **"Someone should notice eventually" is expressly insufficient.**

**This answers the D4.3 trigger-ownership problem directly:** monitoring is owned by **nobody**, so a trigger depending on an unowned observer would never fire. **The responsible observation path is the part that makes the trigger real.**

## DEC-D5-AMD1 §4 · ⛔ The D5 commission REMAINS OPEN — recorded as `G-KOS-AIP04-C10-D5-AMD2`

**It is NOT closed, revoked or discharged.**

> **Reason, as given: closing it would record the false state `commission completed` when the producer never delivered.**

**The governed lifecycle, as directed:**

```
D5 Commission → Architecture artifact delivery → PO/ARB review
      → D5 criteria decision → D2 revisit when evidence exists
```

⭐ **This resolves DEC-D5 §5's unresolved disposition, and it selects READING 2** — the commission stands, and the eventual Architecture artifact must define `E1`–`E10` and supply the missing items. **Reading 1 (supersede and close) is rejected on the ground stated above.**

## DEC-D5-AMD1 §5 · The through-line, recorded because it is the register's own argument

| Decision | What it prevented |
|---|---|
| **D2** | premature **existence** claims |
| **D3** | **ownership** overreach |
| **D4** | **enforcement** overreach |
| **D5** | ⭐ **criteria PROVENANCE overreach** |

> **"The correction is not a setback. It is exactly the type of governance boundary C-10 was designed to expose."**

⚠️ **Recorded as the PO/ARB's characterisation and as `OBSERVED` evidence of a consistent pattern — NOT promoted to a methodology** (`ES-006.1`; a pattern across four decisions of one work item is evidence, not a standard).

## DEC-D5-AMD1 §6 · Standing prohibition reaffirmed

⛔ **The current `E1`–`E10` list must NOT be treated as the final criteria definition, and must not be cited as a defined criteria model until the proposal defines it.**

> **A NAME IS NOT A CRITERION** — the same discipline the estate already applies as *declared capability ≠ evidenced capability · named owner ≠ ownership · documented rule ≠ enforced invariant.*

## DEC-D5-AMD1 §7 · Status

| Decision | Status |
|---|---|
| **D1 · D3 · D4.1 · D4 · D4.2** | ✅ DECIDED — unchanged |
| **D2** | 🟡 `NOT YET ESTABLISHED` — unchanged |
| **D4.3** | 🟡 PARTIALLY DECIDED — M4 only |
| **D5** | ✅ **DECIDED — framework adopted** · ⚠️ **PROVENANCE: Architecture artifact PENDING** · **OPEN: definitions · classification · combination · revisit trigger** |
| **D5 commission** | 🟢 **OPEN — `G-KOS-AIP04-C10-D5` + `-AMD1` + `-AMD2`; awaiting Architecture delivery** |
| **Category (D6) · Ownership (D7)** | ⏳ OPEN |

**Traceability (D5-AMD1):** PO/ARB act 2026-08-19 (D5 controlled correction) · `G-KOS-AIP04-C10-D5-AMD2` · DEC-D5 §2/§3/§4/§5 (the provenance finding this act accepts) · **D4.3** (monitoring unowned) · the C-5 two-limb lesson · `ES-006.1` · `R-34`/`P-2`

---
---

# APPENDED 2026-08-19 — **D1 · RECEIPT CLAIM BOUNDARY — FINAL CONSOLIDATION** *(DECIDED)*

> Appended append-only. **Nothing above rewritten.** ⛔ Canonical C-10 analysis NOT modified.
>
> ⚠️ **One ambiguity, disclosed rather than resolved silently:** the act's section header reads *"RECOMMENDED PROPOSITION FOR DECISION"*, yet its body writes **`Select:` followed by full proposition text** — the exact form the label rule mandated — and it is titled **DECISION**, lists **EXPLICIT NON-CLAIMS**, heads a section **"OPEN ITEMS AFTER THIS DECISION"**, and sequences *"After this decision: Architecture updates E2."* **Governance registers it as a SELECTION on those four structural grounds. If it was intended as a recommendation only, say so and this entry will be corrected additively.**

## DEC-D1 §1 · The selected proposition — recorded verbatim, by text and not by label

> ## ✅ **SELECTED:**
> **"A C-10 receipt claims that governed knowledge delivery occurred and acknowledgement was recorded.**
>
> **The receipt may contain external correlation references for traceability.**
>
> **Such references:**
> - **do not establish ownership;**
> - **do not establish applicability;**
> - **do not establish correctness;**
> - **do not establish compliance;**
> - **do not establish execution authority.**
>
> **A receipt records evidence of delivery and acknowledgement, not truth of the referenced knowledge or application."**

**Explicit NON-CLAIMS:** applicability was correct · knowledge was correct · knowledge was understood · knowledge was applied · compliance was achieved · ⭐ **identity binding was established.**

## DEC-D1 §2 · ⭐ Two wording shifts from the offered Proposition 3 — both are substantive improvements

| Offered P3 | Selected | Why it matters |
|---|---|---|
| a receipt **"proves"** | a receipt **"claims"** / **"records evidence of"** | ⭐ a receipt **evidences**; it does not **prove**. This is the estate's *recording ≠ asserting* discipline written into the language itself |
| **"acknowledgement occurred"** | **"acknowledgement was recorded"** | ⭐ **this is the sharper of the two.** It moves the claim from a fact about the world to **a fact about the record** — and the platform **can** hold that. It directly answers the unattestable-subject problem: the estate cannot attest that session `S` acknowledged, but it can record that an acknowledgement was recorded |

**Also added beyond P3:** *do not establish **compliance*** and *do not establish **execution authority*** among the reference limits, and the closing evidence-vs-truth sentence.

⭐ **`identity binding was established` is now an EXPLICIT NON-CLAIM.** This carries `INV-ATTR-1`/`INV-ATTR-2` and **D4.3's `M4 = NOT ATTESTED`** into D1's language, where it binds every future receipt.

## DEC-D1 §3 · Disposition of the three prior D1 records — explicit, nothing silently replaced

| Record | Disposition |
|---|---|
| **Act A** — `a97ff3b2` / text `a0b61305`, `DECIDED "Option C"` | ⭐ **its PROPOSITION is SUPERSEDED** — the selected text carries neither *"containing knowledge versions K"* nor *"under applicability decision A"*. **The record is RETAINED AS HISTORICAL, unaltered.** Its **eight required properties are NOT re-recorded here** — they fall to `E2`, which the act keeps OPEN |
| **Act B** — conflict record `43c7cd33` | **RETAINED as analysis history**, and **CONSOLIDATED** — it was never a decision, and it is the source of the propositions |
| **Stabilization act** | **CONSOLIDATED.** It corrected the label ambiguity and made no selection; ✅ its defect **`S-1` is cured** by the proposition-text rule |

## DEC-D1 §4 · ✅ The referent gap closes at the level of the claim

The conflict record's unresolved defect: `applicability reference` was a **REQUIRED** property while `ContextSelected` 🔴 **never occurs and is owned by nobody** ⇒ a conforming receipt was **not populatable.**

> **The selected text removes the applicability clause from the claim and makes correlation references `may` — optional and non-authoritative.** ⇒ **A receipt is populatable even where `ContextSelected` has not occurred. The gap closes.**

✅ **And it does so without contradicting `D3.2`** — *"C-10 MAY record references to these external facts. It does NOT thereby attest their correctness."* **The selected proposition is that rule stated as ubiquitous language.**

## DEC-D1 §5 · Open after this decision — as the act records

**`E2` required properties · ⭐ knowledge version field `K` · ⭐ session reference attestation status · `D5` final adoption · `D2` revisit.**

⭐ **Both residues Governance flagged in the consolidation prep are carried as OPEN rather than closed by implication** — `K`'s status as a required property, and how `S` is marked. **Neither is resolved by the selection, and the act does not pretend otherwise.**

## DEC-D1 §6 · Provenance note

⚠️ **All three prior D1 records were recorded by `claude-code-session:4858c37c`** — whose Track-2 artifacts are `PROVENANCE-CONFLICTED` / `INDEPENDENTLY UNVERIFIED` (`b853a644`). **This consolidation is the first D1 record not recorded by that process.** ⛔ Recorded as a fact about the record's provenance; **no prior D1 content is impeached by it**, and Act A recorded a PO/ARB decision rather than making one.

## DEC-D1 §7 · Not decided

⛔ C-10 existence · `D5` criteria satisfaction · category · ownership · implementation · `E2`'s property set · `K` · `S`'s attestation status.

## DEC-D1 §8 · Status and sequence

| Decision | Status |
|---|---|
| **D1** receipt claim boundary | ✅ **DECIDED — consolidated; prior propositions superseded, records retained** |
| **D2** | 🟡 `NOT YET ESTABLISHED` |
| **D3 · D4.1 · D4 · D4.2** | ✅ DECIDED |
| **D4.3** | 🟡 PARTIALLY DECIDED — M4 only |
| **D5** | ✅ framework adopted · ⚠️ proposal **delivered** (`10fbfb05`) · **adoption pending** |
| **D6 category · D7 ownership** | ⏳ OPEN |

```
Architecture updates E2 → D5 adoption review → D2 revisit (only on the D5 trigger) → D6 → D7
```

**Traceability (D1 consolidation):** PO/ARB act 2026-08-19 (D1 final consolidation) · consolidation prep `2026-08-19-…-c10-d1-consolidation-prep.md` (**Proposition 3 recommended; selected with two strengthenings**) · **Act A** `a97ff3b2`/`a0b61305` · **Act B** `43c7cd33` · stabilization act (`S-1` cured) · `b853a644` · **D5 proposal `10fbfb05`** · `D3` (D3.2) · `D4.3` (M4) · `OQ-J` · `INV-ATTR-1`/`INV-ATTR-2` · canonical line 551 (`ContextSelected` unowned)
