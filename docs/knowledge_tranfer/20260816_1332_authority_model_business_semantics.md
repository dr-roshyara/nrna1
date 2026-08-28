# Step 4 — The Authority Model

### Business semantics, derived from twenty live grants

| | |
|---|---|
| **Kind** | ⭐ **BUSINESS SEMANTICS.** ⛔ *No classes · no aggregates · no schema · no implementation · no vocabulary adopted* |
| ⭐ **LANE** | ⛔⛔ **TRACK B — EXPLORATORY. OUTSIDE THE GOVERNED ARCHITECTURE LANE.** *Candidate input only* |
| ⭐ **Lane gate** | May become a candidate architectural input only after the current Architecture Baseline is **reconstructed · independently verified · accepted**. Until then it governs nothing |
| **Commission** | ARB, 2026-08-16 — Track B Authority Model exploration, eleven questions |
| **Method** | ⭐ **Answer from the running authority record**, not from design. **20 grants across 9 work items, measured** |
| **Preserved, not revisited** | Decisions **A**, **B** · `RM-1`…`RM-4` |
| **Date** | 2026-08-16 |

> ⛔ **The presence of findings here is not an architecture commission.** Nothing authorises design, schema, implementation, or a change to any running mechanism.

---

# 0 · The evidence base

⭐ **This is the first step in the sequence with a live, populated record to measure rather than infer from.**

| | |
|---|---|
| Work items carrying authority state | **9** |
| ⭐ **Grants recorded** | ⭐ **20** |
| Most recent write | ⭐ **today** — the governance engine is live |
| Fields every grant carries | `grantId` · `humanActRef` · `authority` · `scope` · `status` · `registeredBy` |

⚠️ **Note against the Python analysis:** the *governance* engine is running and was written to today. The *observation/evidence* engine has been idle since 5 August. **Two engines, two very different states.**

---

# 1 · What is authority?

⭐ **The running record answers this structurally, and the answer is unusual — in a good way.**

Authority in this organisation is **not a property of a person or a role**. It is a **recorded relationship between a human act and a bounded permission**:

```
   A human act happens                    ← outside the system
        ↓ referenced by
   A grant is registered                  ← the system's only move
        ↓ permits
   A named activity, within a stated scope
```

> ### ⭐ **Proposed business definition, for ARB consideration:**
> **Authority is a recorded permission for a named activity within a stated scope, whose legitimacy derives entirely from a human act performed outside the system.**

⭐ **What that excludes, deliberately:** authority is not a role, not a job title, not a capability of the software, and not a property anyone holds in general. **It exists only as a grant, only for a scope, and only by reference to an act.**

---

# 2 · Who can hold it?

**Measured across all 20 grants — the recorded holders:**

```
PO
PO/ARB
PO/ARB (via the delivered intake commission)
PO/ARB (delivered commission, registered verbatim)
PO/ARB (delivered instruction, registered verbatim)
PO/ARB (delivered decision, registered verbatim)
PO/ARB (delivered amendment, registered verbatim)
PO/ARB (delivered commission + Option A/C election, registered verbatim)
```

> ### ⭐ **Two actors. Eight spellings.**

⭐ **Business reading:** in practice only one authority holds anything — the human product/architecture authority. **The eight variants are not eight holders; they are one holder plus a description of *how the act arrived*.**

⛔ **Consequence:** *"which grants did this authority issue?"* cannot be answered mechanically, because the holder and the delivery method are recorded in the same free-text field.

---

# 3 · Who can grant it?

**Measured: `registeredBy` has exactly one value across all 20 grants — `governance`.**

> ### ⭐⭐ **The invariant holds in practice, 20 out of 20.** Only the governance role writes authority, and it writes it only by registering a human act.

⭐ **This is worth stating as a business fact, because it is rare:** the organisation has a rule that only one role may record authority, and **the record shows no exceptions.**

---

# 4 · Who can delegate it?

> ## ⛔⛔ **Delegation is not expressible. Zero of 20 grants carry any delegation field.**

⭐ **What the organisation does instead, observed:** it issues a *new grant* with a narrower scope. Sequential grants on one work item narrow progressively — architecture design → boundary presentation → implementation within the amended boundary.

> ### ⭐ **So delegation exists in behaviour as *re-granting with a narrower scope*, and is invisible as a concept.**
>
> ⛔ **What cannot be answered today:** *is this grant derived from that one?* There is no chain. Each grant stands alone, and the narrowing is legible only to a human reading the prose.

---

# 5 · For what scope?

✅ **All 20 grants carry a scope. None is empty.**

⭐ **But scope is recorded as prose**, e.g.:

> *"Implement the Session Assignment Resolver strictly within the AMENDED boundary: 3 capability paths…"*

| | |
|---|---|
| ⭐ **Strength** | scope is **always** recorded — this is a genuine discipline, and it is 20/20 |
| ⛔ **Limit** | prose scope cannot be compared, intersected, or checked. *"Does this act fall inside that grant?"* is a human judgement every time |

> ⭐ **Exactly the same gap the Rule Model found for rules (§4 there): applicability is written, but not in a form anything can reason about.** **Two independent models, one shared missing dimension.**

---

# 6 · For what period?

> ## ⛔⛔ **Zero of 20 grants carry any validity field.**

No start, no end, no expiry, no review date.

⭐ **Business consequence, stated plainly:** **every authority ever granted in this organisation is, on its face, permanent.** A grant issued for one increment of one work item in July is still, by the record, in force today.

> ⛔ **And it makes one question unanswerable that governance depends on:** *"was this authority valid at the time that decision was taken?"*

⭐ **This confirms — now with running data — the same gap two prior analyses reached independently:** the tactical work reached it from exception validity; the lifecycle analysis reached it from the observation that **status has a governed state machine and authority has none.** **Three routes, one gap.**

---

# 7 · Can it be revoked?

> ## ⛔⛔ **No. `status` has exactly ONE value across all 20 grants: `AUTHORIZED`.**

There is no `REVOKED`, no `EXPIRED`, no `SUSPENDED`, no `SUPERSEDED`.

| Question | Answerable today? |
|---|---|
| Is this grant still in force? | ⛔ **it is always in force** |
| Was it withdrawn, or did it lapse? | ⛔ **neither is expressible** |
| Did the authority change its mind? | ⛔ **no record shape exists for that** |

> ### ⭐⭐ **Authority in this organisation can be created and never ended.**
>
> ⛔ **That is not a bug in the mechanism — it is a missing concept.** The record has no vocabulary for the end of a permission, so the question was never asked of it.

⚠️ **`RM-4` already ruled that exceptions need a validity period and must distinguish withdrawn from expired. §6 and §7 show authority itself has neither** — so the exception concept would inherit a gap from the concept it depends on.

---

# 8 · How is it evidenced?

## ⭐⭐ This is the strongest part of the model, and it answers the ARB's hardest question

**Every grant carries `humanActRef` — a pointer to a human act that happened outside the system.** Measured:

| | |
|---|---|
| ⭐ **Grants carrying a reference to a human act** | ⭐⭐ **20 of 20 — no exceptions** |
| Grants whose reference cites an immutable commit | ⚠️ **13 of 20** |
| Grants referencing an artifact **without** an immutable address | ⛔ **7 of 20** |

**What the references actually look like:**

> *"intake commission (governance-intake artifact header + commit `15f4f484`)"*
> *"PO act 'I authorize the implementation boundary presentation stage' (approval-request artifact…)"*
> *"PO/ARB approval with amendments 1 and 2 (implementation-approval-request artifact, DECISION RECORDED + commit…)"*

## 8.1 ⛔ The weakness inside the strength

⭐ **7 of 20 references identify the act by description rather than by immutable address.**

> **A reference that is not immutably addressed can drift.** The artifact it names can be edited after the grant was registered, and nothing would detect it. **The act would then be, in effect, editable after the fact.**

⛔ **This is the one place where the organisation's strongest authority discipline is not fully carried through** — and it is a small, closable gap: the other 13 show the practice already exists.

---

# 9 · How does authority relate to ownership?

⭐ **The ARB raised this distinction; the record settles that it is real and currently absent.**

> ## ⛔ **Zero of 20 grants carry any owner field. The two concepts are not distinguished anywhere in the authority record.**

| | **Ownership** | **Authority** |
|---|---|---|
| Answers | *who maintains this?* | *who legitimately empowered it?* |
| Duration | continuing | ⭐ **per act, per scope** |
| Transfers by | reassignment | ⛔ **not transferable — a new act is required** |
| Recorded where | document headers (`owner:`) | ⭐ the grant record |

⭐ **Observed in the organisation, and the ARB's example is real here:** the knowledge platform is *owned* by a named individual while the authority to approve changes to engineering standards sits with the governance authority. **Two different actors, two different questions, no field connecting them.**

> ### ⭐ **Business consequence:** *"who do I ask?"* and *"who may approve it?"* have different answers, and the system records only the second.

---

# 10 · How does authority relate to Rule validity?

> ## ⛔⛔ **There is no relationship. Not a weak one — none.**

**No grant references a rule. No rule references a grant.** The authority record governs *work items and sessions*; the rule artifacts live entirely apart.

⭐ **Consequence for the Rule Model:** `RM-3` ruled that changing an authoritative Rule requires explicit authorization and a recorded reason, ending in **Authorize**. **The authority machinery that would carry that step exists, works, and has never been pointed at a rule.**

> ### ⭐ **The gap is not that authority is missing. It is that the two models have never been connected** — which is a far smaller problem than building either from nothing.

---

# 11 · ⭐⭐ The crux — how a human act becomes authoritative without the mechanism becoming the authority

**The ARB's question, and the running record answers it concretely:**

```
   THE ACT                    a human writes and commits an artifact
        │                     ⭐ this happens OUTSIDE the system
        │                     ⭐ the system cannot perform it
        ▼
   THE REFERENCE              governance registers a grant that POINTS AT the act
        │                     ⭐ the grant CONTAINS no authority
        │                     ⭐ it contains a pointer and a scope
        ▼
   THE PERMISSION             a named activity becomes permitted, within that scope
```

> ### ⭐⭐ **The mechanism never holds authority because it never contains any. It holds a reference to something it cannot create.**

**Three properties make this work, all observed:**

| # | Property | Evidence |
|---|---|---|
| **1** | ⭐ **The act is external and immutable** — a committed artifact | 13 of 20 cite a commit |
| **2** | ⭐ **The grant is a reference, never a copy** | every grant carries `humanActRef`, 20/20 |
| **3** | ⭐ **Only one role may register, and it may only register** | `registeredBy: governance`, 20/20 |

⭐ **And the same shape appears independently in code:** the verification enum reserves three outcomes to human review acts and *refuses to emit them mechanically*. **Two mechanisms, built for different purposes, both implementing "record, never grant."**

> ### ⭐ **This is the organisation's answer, and it works. It is worth protecting as-is rather than redesigning.**

---

# 12 · The observed minimum semantic gap

| # | Authority must be able to state | Today | Nearest thing |
|---|---|---|---|
| **1** | ⭐ **when it starts and ends** | ⛔ **no** | nothing — 0 of 20 |
| **2** | ⭐ **that it has ended, and how** | ⛔ **no** | ⛔ one status value only |
| **3** | ⭐ **that it derives from another grant** | ⛔ **no** | prose narrowing across sequential grants |
| **4** | **who the holder is, comparably** | ⚠️ **prose** | 8 spellings of 2 actors |
| **5** | **what scope it covers, comparably** | ⚠️ **prose** | always recorded, never machine-readable |
| **6** | **who owns the thing, as distinct from who empowered it** | ⛔ **no** | 0 of 20 |
| **7** | ⭐ **that its evidencing act is immutable** | ⚠️ **65%** | 13 of 20 cite a commit |
| **8** | **which rule it authorises** | ⛔ **no** | no link exists |

⭐ **Three absent entirely. Three present as prose. One at 65%. One is a missing connection between two working models.**

---

# 13 · Two distinctions the ARB asked to carry forward

## 13.1 Authority ≠ Ownership

✅ **Confirmed as real and currently unrecorded** (§9). ⭐ **Recommended as a question for whatever follows, not a change to `RM-1`.**

## 13.2 Authority *of* the Rule ≠ authority *to change* the Rule

⭐ **The record shows these are already different in practice, though neither is linked to a rule:**

| | *Why is this authoritative?* | *Who may approve a change?* |
|---|---|---|
| Answered by | the act that established it | the act that would amend it |
| Recorded as | ⭐ a grant with a scope | ⛔ **nothing — no rule has a change-authority** |

> ⛔ **Today a rule's *authority* is asserted in its prose header, and its *change authority* is whoever can edit the file** — which is exactly the gap `RM-3` ruled against. **The distinction the ARB drew is precisely where the ruling has to attach.**

---

# 14 · ⭐ The rulings — recorded 2026-08-16, **inside Track B**

⛔ **These are business-semantics decisions. They are NOT implementation commitments.**

| # | Ruling | Refinement carried |
|---|---|---|
| ⭐ **AM-1** | **APPROVED** — authority must be able to end | ⭐ **The end is not one event.** `starts · expires · revoked · suspended · superseded` are **different business situations**. ⛔ *Do not assume the answer is a single expiry field* |
| ⭐ **AM-2** | **APPROVED** — distinguish independent from delegated authority | ⭐ Business form: *"when one authority permits another actor to act within a narrower boundary, the organization recognizes this as **delegation** and must distinguish the delegated authority from the original."* ⛔ **But not every narrower grant is a delegation — independent authorization must remain possible** |
| ⭐ **AM-3** | **APPROVED** — every grant backed by an immutable reference to the human act | ⭐ **Two separable things:** the **evidence principle** *(every grant identifies its human source — already 20/20)* and the **evidence-addressing improvement** *(the reference resolves to an exact historical artifact — currently 13/20)*. ⛔ *The 7 weak references are not a reason to redesign the model* |
| ⭐ **AM-4** | **APPROVED** — ownership and authority are separate concepts | *"Who do I contact?"* and *"Who may approve a change?"* are different questions with different answers today |
| ⭐ **AM-5** | **APPROVED — strongly** — authority must be linkable to the Rule it authorises | ⭐ **The connection is approved; ⛔ *what exactly is authorised* remains OPEN** — a particular rule change · a class of rule changes · a bounded activity · a governance action. **Not resolved here** |

## 14.1 ⭐ The semantic model these rulings assemble

```
Authority
├── human source act          ⭐ external, immutable          AM-3
├── holder
├── scope                                                     → Step 5
├── validity                  ⭐ starts / ends                 AM-1
├── status / end reason       ⭐ expired · revoked · suspended · superseded   AM-1
├── derivation / delegation   ⭐ independent OR derived        AM-2
├── immutable evidence        ⭐ resolves to a fixed artifact  AM-3
├── ownership relationship    ⭐ distinct from authority       AM-4
└── authorised object         ⭐ e.g. a Rule                   AM-5
```

⛔ **A semantic model. Not a class design, not a schema, not a commitment to build.**

## 14.2 ⛔ What these rulings do **not** authorise

no class · no aggregate · no schema · no field names · no status vocabulary adopted · no delegation mechanism · no change to any running grant · no new bounded context.

> ⭐ **Recorded and deliberately not acted on:** the observation that Authority behaves as a **cross-cutting capability connecting governed knowledge to human decisions**, rather than an isolated authorization service. **The ARB has explicitly declined to create a bounded context for it** — the Architecture Baseline must be accepted first.

## 14.3 ⭐ Evidence classification, carried forward

> **Governance capability** → actively producing live records *(written to today)*
> **Observation / evidence capability** → evidence stream inactive since 5 August

⛔ **This is an evidence classification, not a target-architecture conclusion.** ⭐ *Subsystems of one platform do not share one operational maturity, and future reviews should stop speaking of "the platform" as though they do.*

---

# 15 · Bottom line

**The organisation has a working authority model, and it is better than expected in one specific respect: it never lets the mechanism hold authority.** Twenty grants, twenty references to human acts, one registering role, no exceptions.

**What it cannot do is let go.**

> ### ⭐ **Authority here can be created, scoped, and evidenced — but not ended, not delegated, not time-bounded, and not connected to the rules it would authorise.**

Three observations for the sequence:

**One.** The hardest question — *how does a human act become authoritative without the mechanism becoming the authority* — **is already answered, in production, twice, by two unrelated mechanisms.** It should be protected, not redesigned.

**Two.** The Rule Model and the Authority Model reached the **same missing dimension independently**: scope is always written and never comparable. That is one gap, not two.

**Three.** `RM-3` needs an authorization step. **The machinery for it exists and works.** It has simply never been pointed at a rule — which makes `AM-5` the cheapest connection available anywhere in this programme.

---

*Step 4 of the ARB sequence, Track B. Derived by measuring the live authority record: 9 work items, 20 grants, all fields counted. Field-level counts are `OBSERVED`; interpretation is `INFERRED`. Decisions A, B and `RM-1`…`RM-4` preserved and not revisited. ⛔ **No design · no schema · no vocabulary adopted · no running mechanism changed · nothing outside `docs/knowledge_tranfer/` touched.***

***PROPOSED — Track B exploratory. Governs nothing.***
