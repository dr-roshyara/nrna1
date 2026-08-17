# ADR — What the absence of a required aggregate MEANS at the application boundary

**Status: 🟡 PROPOSED — CANDIDATE ONLY. No option is chosen here. The decision block (§6) is deliberately BLANK and belongs to the Product Owner / ARB.**
**Date:** 2026-08-17 21:45 · **Deciders:** Product Owner / ARB (pending) · **Recorded by:** the `EM-IMPL-002` implementation lane (evidence and options only — `R-34`: engineering supplies evidence and never accepts its own work)
**Origin:** `EM-IMPL-002` GREEN-2/3/4. Three handlers were implemented under a grant that left this question open; they now handle absence **three different ways**, and the Governance verification record rates the result a robustness regression.
**Evidence base:** `docs/publicdigit/implementation/2026-08-17-EM-IMPL-002-green4-verification.md` §3 (the three-way divergence, and the verified fact that **no test covers an absent-aggregate path in any handler**). This ADR asserts nothing that record did not establish.
**Severity (PO):** HIGH — to be closed **before GREEN-5**.

---

## 0 · Placement note (ES-004.2 / ADR_20260801_1740)

Placement was **derived, not chosen**:

```
$ php scripts/doc-placement.php --scope=product-specific --maturity=research --domain=publicdigit
docs/publicdigit                                                        (exit 0)
```

`--domain=election` is **not** a known domain (`publicdigit`, `knowledgeos`, `pks`), so the product domain was used. The file follows the timestamp convention already in force in `docs/publicdigit/adr/` (`ADR_YYYYMMDD_HHMM_Title`); no global ADR sequence exists in this repository, and inventing one would be an unauthorized convention change.

---

## 1 · Context — the concrete question

Every command handler in `App\Contexts\Election\Application\OperatingCore\` begins by loading what the authorized flow requires:

```php
$committee = $this->committees->find($command->electionId);      // ?ElectionCommittee
$decision  = $this->gates->find($electionId, $designation);      // ?AcceptanceGateDecision
```

The repository contracts are **frozen** and return `null` when nothing is stored. **The application must therefore do something when `null` arrives — and "something" is a semantic choice that the adopted rule corpus does not make.**

### 1.1 What the three implemented handlers do today (verified)

| Handler | Absent-aggregate handling | Sites |
|---|---|---|
| UC-1 `ExpressCommitteePositionHandler` | inline `?? throw new InvalidArgumentException(...)` | 2 |
| UC-2 `RecordVacancyEventHandler` | `?? throw $this->unresolvedReference(...)` (named private method returning the same class) | 3 |
| UC-3 `FillCommitteeSeatHandler` | 🔴 **nothing** — `requiredVotes()` / `unableToFunction()` are reached on possibly-`null` values | 0 |

**How the divergence arose, stated fairly.** Registered condition 3 said *"do not propagate UC-1's exception choice as a pattern."* UC-2 read it as *"keep one provisional shape, isolate it, re-raise the question"*; UC-3's mandate said *"STOP and report; do not invent"*, so the lane removed its placeholder — correctly refusing to throw a domain `SeatNotVacant`, which would have asserted a false meaning — and left no handling at all. **Both readings are defensible. The consequence is not: the three handlers now disagree, and one can raise a raw PHP error.**

### 1.2 Why this cannot be settled by engineering preference

Absence is **not one phenomenon**. The PO's own framing names three distinct concepts, and the application must not silently pick one:

1. **an invalid request** — the caller referenced something that never existed;
2. **an integrity failure** — the reference is legitimate but the store lost or never wrote the aggregate;
3. **a legitimate lifecycle state** — the thing is *not yet established*, which is a normal, expected condition of an election that has not reached that point.

These have **different recording obligations** (`EM-GOV-005`: requests, evaluations, refusals **and their reasons** are material events), different visibility (repo Rule 8: Domain/Application exceptions are user-visible; Infrastructure failures are not), and different operational meaning.

---

## 2 · Constraints any option must respect

| # | Constraint | Source |
|---|---|---|
| C-1 | **The domain core is frozen** — no new domain exception, no repository signature change | `EM-IMPL-001` baseline freeze; the `EM-IMPL-002` grant |
| C-2 | **No new port** without its own authorization | grant; §3a closed six-port universe |
| C-3 | **The protocol records reality** — no entry may exist for a phase that never occurred (`EM-GOV-005`, negative half); refusals are recorded as refusals, never as facts (Q-3) | `EM-GOV-005`; F-PROTO-1 property 6 |
| C-4 | **The application decides no election meaning** (G-1/G-5) — so it may not classify an election's *lifecycle* state on its own authority | grant G-1/G-5 |
| C-5 | The provisionally accepted **A-5 taxonomy** already rules that *"references-nothing-in-the-record"* is a **caller error that propagates unrecorded** — but it was written about **`UnknownCommitteeSeat`, a seat inside an existing aggregate**, not about a missing aggregate | RED acceptance record §2 |

**C-5 is the crux: A-5 answers the seat case and is silent on the aggregate case.** Extending A-5 by analogy is precisely the silent choice this ADR exists to prevent.

---

## 3 · Options

### Option A — absence is an INVALID REQUEST (caller error)

The command referenced an election/decision that the record does not contain; the request is malformed in the same sense as `UnknownCommitteeSeat`.

* **Mechanism:** the handler raises a caller-error exception; **nothing is appended** (C-3: no entry about a subject that never existed).
* **Extends:** A-5's existing half, uniformly.
* **Consequences:** simplest; no protocol growth; consistent with the seat case. **But** it makes every absence the *caller's* fault, which is false in case 2 (integrity failure) — a lost aggregate would be reported as a bad request, hiding a store defect.
* **Frozen-domain fit:** ✅ needs no domain change. Requires only a decision about **which** exception type (see §4).

### Option B — absence is an INTEGRITY FAILURE (infrastructure inconsistency)

A command reached the application naming an election whose aggregate should exist; its absence indicates the store, not the caller.

* **Mechanism:** the handler raises an infrastructure-class error (repo Rule 8: `RuntimeException`, **not** user-visible, logged, 500-class); **nothing is appended.**
* **Consequences:** correctly loud about a real defect; protects the record from being blamed on users. **But** it is wrong for a caller that simply invented an id, and it converts an ordinary bad request into an incident. In a voting system, mislabelling a caller error as an integrity failure **degrades the trustworthiness of integrity alarms**.
* **Frozen-domain fit:** ✅ no domain change.

### Option C — absence is a LEGITIMATE LIFECYCLE STATE (not yet established)

An election that has not been constituted *has no Committee* — that is a normal state, not an error, and it is knowable only from the record.

* **Mechanism:** the handler treats absence as a **recordable refusal** — the act was requested, the precondition did not hold, the refusal and its reason are appended (`EM-GOV-005`; F-PROTO-1 property 6) — and the act does not happen.
* **Consequences:** the strongest audit story: *"a fill was requested for an election with no constituted Committee, at this instant, and was refused"* becomes part of the permanent record instead of vanishing into an exception. **But** it risks C-4: deciding that absence *means* "not yet established" is a statement about **election lifecycle state**, which the application does not own — a lost aggregate would be recorded as a legitimate not-yet-established state, i.e. **an integrity failure would be written into the protocol as normal history.** That is the most dangerous failure mode of the three.
* **Frozen-domain fit:** ✅ mechanically (uses the existing `ProtocolEntry::refusal`) — ⚠️ **but it needs a ruling that the application may make this classification at all**, and it enlarges the protocol with entries about elections that may not exist.

### Option D (composite, offered for completeness) — distinguish by what is absent

Absence of the **Committee/decision for a command whose flow presumes establishment** = Option A or B; absence encountered where the record can legitimately be empty = Option C.

* **Consequences:** most precise; **but** it requires the application to tell the three concepts apart, which is exactly the discrimination it lacks the authority and the information to make (C-4). **Recorded because it is the intuitive answer, and because its cost is easy to miss.**

---

## 4 · The subsidiary question the ruling must also settle

Whichever option is chosen, **the shape is still undecided**, and it cannot be resolved by analogy:

| Candidate shape | Note |
|---|---|
| PHP `InvalidArgumentException` (today's provisional choice in UC-1/UC-2) | zero new artifacts; carries no election meaning; indistinguishable from ordinary argument validation |
| `RuntimeException` | Rule 8's infrastructure class — implies Option B |
| A **new application-layer exception** (`UnknownElectionReference` or similar) | clearest naming; ⚠️ **a new artifact in the application namespace — needs authorization, and the grant forbids new abstractions** |
| A **new domain exception** | ⛔ **blocked by C-1** (frozen domain) — would need its own PO-authorized domain slice |

---

## 4a · SCOPE — which references the ruling covers *(added by Governance, 2026-08-17, at the PO's required-decision list; evidence only, no option chosen)*

**The PO's decision package requires the ruling to state its scope across the three repository references. Governance adds the evidence, because the three are NOT alike and the code already proves it:**

| Reference | How absence is treated TODAY | Evidence |
|---|---|---|
| `ElectionCommitteeRepository` | ⚠️ **inconsistent** — UC-1/UC-2 throw; **UC-3 dereferences it unguarded** | the frozen pin `AbsentAggregateReferenceRedTest` |
| `AcceptanceGateDecisionRepository` | ⚠️ **inconsistent** — UC-1 throws; **UC-3's helper returns `null` and the caller dereferences it** | same pin, second violation |
| **`RecoveryProcessRepository`** | ✅ **ALREADY SETTLED, and settled as a NORMAL STATE, not an error** — `if ($restoration === null) { return; // No allowance is running: there is nothing to pause. }` and `if ($halted === null) { return; // nothing to resume }` in UC-3, with the same shape in UC-2 | `FillCommitteeSeatHandler` · `RecordVacancyEventHandler` |

> ### ⚠️ **The consequence for the ruling, stated as evidence rather than as a recommendation:**
> **A single blanket answer would be wrong for at least one of the three.** An absent `RecoveryProcess` genuinely means *"no period is running"* — a **legitimate lifecycle state (Option C)** — and it is already implemented that way, deliberately and correctly, in two handlers. **So the ruling's scope must either exclude that reference or adopt Option D (composite); a uniform Option A or B would require changing already-correct behaviour.**
> **Governance chooses nothing here. It records that the scope question is not decorative: one of the three references already has a settled and defensible answer, and it is not the same answer the other two need.**

---

## 4b · The PO's decision PROCEDURE and entering POSITION (registered 2026-08-17 — ⚠️ **POSITIONS, NOT THE RULING; §6 stays blank**)

> ### **The reframing — a better test than the option list:** the decision is **not** *"what exception do we throw?"* but ***"what invariant does the absence violate, or does it represent?"***

```
Repository returns null
          |
          v
What invariant does this reference represent?
          |
          +----------------------+
          |                      |
          v                      v
Required existence          Optional lifecycle state
          |                      |
          v                      v
Failure semantics           Normal domain state
```

**The PO's entering position, verbatim:** *"Do not choose a universal absence meaning. Adopt governed composite semantics: each aggregate's absence meaning is determined by its domain invariant. RecoveryProcess absence remains a normal lifecycle state; Committee and AcceptanceGateDecision require explicit existence semantics."*

**With the options graded, and the grading's reason:** ⛔ **A rejected** and ⛔ **B rejected** — *each contradicts `RecoveryProcess`, where absence is already a valid state, so a global A or B "would actually damage an existing correct model"* · ⚠️ **C too broad** — *a missing Committee does not necessarily mean "not yet created"; it may mean a corrupted reference* · ✅ **D strongest** — **and expressly: *"composite does NOT mean arbitrary. It must be governed by the aggregate invariant."***

> ## ⚠️ **The consequence for the NORMALIZATION slice, which this position changes materially:**
> **The goal is NOT *"make all handlers handle null the same way"* — that would be wrong.** It is ***"make each handler conform to the governed absence meaning of the referenced aggregate."***
> **Same discipline. Different meanings.** *(Recorded here because a normalization slice written to the wrong goal would encode the very uniformity this ADR exists to prevent.)*

---

## 5 · Consequences of NOT deciding (recorded, since this is the status quo)

* UC-3 keeps a **null-dereference path**; UC-1/UC-2 keep a shape nobody ratified; the divergence grows by ~2 sites per remaining use case (UC-4, UC-5).
* **No test covers any absent-aggregate path**, so none of the three behaviours is protected against silent change.
* Normalization gets more expensive every slice, and the eventual ruling has to migrate more sites.

---

## 6 · Decision

> **⬜ LEFT BLANK — the Product Owner / ARB decides.**
>
> Required in the ruling: **(a)** which of A / B / C / D is the meaning of absence; **(b)** the shape from §4; **(c)** whether normalization of UC-1/UC-2/UC-3 is authorized as one slice; **(d)** whether a RED pin for the chosen behaviour is required before that normalization (the lane recommends yes — it is currently untested in all three handlers).
>
> *The lane will not choose among these options, and has implemented none of them.*

### 6a · REQUIRED IN THE RULING — "What this decision does NOT mean" *(pre-drafted at PO direction, 2026-08-17, to prevent the discussion reopening)*

> **"This ADR does not define a universal exception policy. It defines the domain meaning of absent references. Representation follows after meaning."**

### 6b · Composite done right vs done wrong *(the PO's illustration, registered because the distinction is the whole risk)*

⛔ **BAD composite — inconsistency wearing composite's clothes** *(no invariant explanation, just three different reflexes)*:
`Committee missing → throw RuntimeException` · `Gate missing → return null` · `Recovery missing → ignore`

✅ **GOOD composite — one governing principle, three outcomes**:

| Reference | Invariant | Therefore absence is |
|---|---|---|
| Committee | **required existence** | a **violation** |
| Acceptance decision | **required constitutional decision** | a **violation** |
| Recovery process | **optional running process** | a **normal state** |

**Same governing principle. Different outcomes.** ⚠️ **And the acceptance criteria for the normalization slice follow from the INVARIANT, never from a uniform null policy: each handler's behaviour follows the invariant of the reference it uses.**

---

## 7 · Traceability

`EM-IMPL-002` grant (registered condition 3) · GREEN-2 `8ea13835` · GREEN-3 `cdc45f99` · GREEN-4 `4651a3e7` · GREEN-4 verification record §3/§5.1 · RED acceptance record §2 (A-5, provisionally accepted) · `EM-GOV-005` · repo Rule 8 · `EM-IMPL-001` baseline freeze (C-1).
