# Governance Proposal — the business classification of a HALT (`EM-OPEN-042` / `EM-OPEN-102`)

**Type:** Governance formulation (Session 2) · **Date:** 2026-08-16 · **Commission:** *"First ask Governance to formulate the business classification of a halt."*
**⛔ NOTHING ADOPTED.** The PO's message is written as *"I recommend / I would / my recommendation"* — under **A-3** those are **positions, not adoption acts**. **What IS taken as instruction is the WORK: formulate the classification, and consolidate `042` with `102`.** **No consequence, no period, no terminal outcome is proposed here.** `EM-GOV-037` untouched · Architecture ⏸️ · Session 3 🛑.

---

## 0 · Two record checks before building anything

**✅ CONFIRMED — the exclusion I relied on holds.** `SCB-4`'s **lifecycle half is ruled**: the lifecycle may not return to a completed phase, and act v3 records *"`EM-OPEN-042` recovery actions (now with 'restart from an earlier phase' excluded by `SCB-4`)"*.

> ### ⚠️ **0.1 · GOVERNANCE ALREADY REGISTERED THIS EXACT PREREQUISITE — ON 2026-08-15, BEFORE THIS COMMISSION**
> **The `EM-OPEN-042` row in the Manifesto already carries, as distinction ④:** ***"reason-dependent recovery has a hidden prerequisite: it requires failures to be CLASSIFIED, and nothing classifies them today."***
> **The PO has independently arrived at the same prerequisite. This commission is therefore not new policy being invented — it is a pre-registered gap being filled.**

> ### ⚠️ **0.2 · A PREMISE CORRECTION — EXAMPLE B'S CONDITION IS NOT CURRENTLY A RULE**
> **Example B assumes *"an election requires at least one eligible candidate."* **No such condition exists in the Manifesto** — there is no minimum-candidate rule anywhere in the adopted set.
> **This does not weaken the example; it sharpens the model. A halt is always a halt OF A SPECIFIC MANDATORY CONDITION, so the classification is CONDITION-RELATIVE and can only ever be applied to conditions the Election Rules actually impose.** **If the business wants "no candidates" to halt an election, that condition must first be adopted as a rule — otherwise there is nothing to classify.**

---

## 1 · The three-row table conflates TWO INDEPENDENT AXES

**The recommended table lists *temporary/recoverable*, *objectively impossible*, and *actor fails to act* as three situations. The first two describe the CONDITION; the third describes the ACTOR. They are not alternatives — they vary independently.**

| | **Actor is acting** | **Actor is not acting** |
|---|---|---|
| **Condition SATISFIABLE** | **① recovery proceeds** — the normal case | **② bounded wait → escalation** *(`EM-OPEN-102`)* |
| **Condition UNSATISFIABLE** | 🔴 **③ DILIGENT BUT FUTILE** | **④ terminal; inaction is irrelevant** |

> ### 🔴 **CELL ③ IS INVISIBLE IN THE THREE-ROW TABLE, AND IT IS THE ONE THAT COSTS MONEY**
> **A Chief who is diligently rescheduling a condition that can never be satisfied is *acting*, so no bounded-wait escalation ever fires — yet the election is going nowhere.** **Your own words about Example B were *"continuing to wait would be meaningless."*** **Under the three-row table it would not be meaningless; it would look like compliance.**
> **The consequence for the rule: the terminal path must be reachable from the CONDITION alone, never only from the actor's failure.** **Diligence must not extend the clock when the condition is unsatisfiable.**

**And this is precisely why `042` and `102` must be one policy, as instructed: they are two axes of one situation, and each alone leaves a live cell unhandled.**

---

## 2 · The proposed classification — three grades, not two

**A halt is classified against the specific mandatory condition that was not satisfied.**

| Grade | Business meaning | Worked case |
|---|---|---|
| **`C-1` SATISFIABLE** | **A permitted governed act exists that would supply the condition, and an actor is empowered to perform it.** | **Example A** — the document is temporarily unavailable; obtaining it and rescheduling is a permitted act. |
| **`C-2` UNSATISFIABLE IN FACT** | **No act by anyone could supply the condition. The obstacle is the world.** | The external appointing authority has ceased to exist *(`EM-OPEN-066`)* — no permitted act, and no forbidden one either, produces an appointment. |
| **`C-3` UNSATISFIABLE UNDER THE RULES** | **The world could supply it, but NO PERMITTED GOVERNED ACT CAN REACH IT. The obstacle is the Election Rules themselves.** | **Example B** — candidacy has legitimately completed with no eligible candidate. Reopening it would return the lifecycle to a completed phase, which **`SCB-4` forbids**. |

> ### ⛔ **AND ONE THING THAT IS EXPRESSLY NOT A CLASSIFICATION**
> **"Nobody has acted" is NOT impossibility. It is Axis 2.**
> **Permitting inaction to be classified as impossibility would make the terminal consequence reachable by doing nothing — which is exactly what `EM-GOV-012` (*a halt alone never determines the outcome*) and `EM-OPEN-047` (*expiry is an event, never an automatic outcome*) exist to prevent.** **This is the single largest failure mode of a reason-based model, and the test in §3 is built to defeat it.**

### 2.1 · ⚠️ `C-3` is self-inflicted — and that is worth knowing deliberately

**`C-2` impossibility comes from the world. `C-3` impossibility is CREATED BY OUR OWN RULES** — `SCB-4` chose finality of completed phases over recoverability, for good reasons *(it prevents re-running a phase whose outcome someone dislikes)*. **That choice is not reopened here.** **But every `C-3` case is a place where the rules preferred finality to recovery, and if `C-3` proves common in practice, that is evidence for revisiting `SCB-4` later — never a licence to bend it in the moment to rescue an election.** *(This is the same prohibition already in force: a committed model may not be changed to escape a failure.)*

---

## 3 · The test — stated so it can be applied by a person, and so inaction cannot pass it

> ## **Name (a) the permitted governed act that would satisfy the condition, and (b) the actor empowered to perform it.**
>
> * **Both can be named → `C-1` SATISFIABLE.** **Regardless of how unlikely, inconvenient, expensive or slow the act is, and regardless of whether anyone has actually performed it.**
> * **Neither can be named → unsatisfiable.** **Then: is the obstacle the world (`C-2`) or the Election Rules (`C-3`)?**

**Why this test defeats the failure mode in §2: in the inaction case both halves can still be named — the act exists and the empowered actor exists — so the halt classifies as `C-1`, and the matter is correctly routed to bounded waiting rather than to termination.** **"Nobody did it" can never produce "it cannot be done."**

---

## 4 · Classification is a FINDING. It is never itself the consequence.

**Proposed as a structural property of the model, because it is what keeps `EM-GOV-012` and `EM-OPEN-047` intact rather than merely restated:**

> **A classification records what is true about the condition. It creates no outcome. Any terminal consequence is a SEPARATE governed decision taken ON that finding, by an authority competent to take it.**

**This is the same separation already adopted elsewhere in this programme — the Chief prepares, another body decides; evidence, recommendation and authority never merge.**

---

## 5 · The unresolved authority question — raised, not answered

> ### 🔴 **WHO CLASSIFIES? IF IT IS THE CHIEF ALONE, THE MODEL HAS A HOLE.**
> **In Cell ② the Chief is the actor being waited upon. If the Chief may also classify the halt unilaterally, the Chief can convert *their own inaction* into a finding of impossibility and thereby terminate the election.** **This is structurally the same manipulation risk that produced Reading A on `EM-OPEN-022` — an actor reshaping the record to escape a consequence.**
> **So: a classification should not be a unilateral act of the actor whose conduct is in question. But Governance stops there, because naming the alternative decider runs into two things it may not decide alone:**
> * **`EM-OPEN-071` — Committee interpretive authority is OPEN, and the Committee acquires no power by default.**
> * ⚠️ **A halt can occur BEFORE any Committee exists** *(during Election Appointment, ahead of the acceptance gates)* — **so classification authority may have to differ by phase, or the earliest phases have no competent classifier at all.**
>
> **Registered as `EM-OPEN-109`.**

---

## 6 · Three further questions the classification exposes

**(a) Is a classification revisable?** **`C-1 → C-2/C-3` on new evidence is natural.** **The reverse is the hard one: allow it and a terminal outcome can be undone, destroying finality; forbid it and an erroneous finding of impossibility is uncorrectable.** *(Open.)*
**(b) Does a period still make sense once a halt is classified `C-2`/`C-3`?** **A period exists so that something may change; if nothing can change, its only remaining function is as a review window against a wrong finding — which is a different purpose and may deserve a different length.** *(Open.)*
**(c) What is a halt whose condition belongs to an EXTERNAL actor?** **`EM-OPEN-102` already records the constraint: a bounded-wait rule can bind internal actors, but for an external one it can only bound THE ELECTION'S OWN RESPONSE — Election Governance cannot impose duties outside itself.** **So the consolidated policy needs one rule with two actor classes, not two rules.**

---

## 7 · What Governance confirms as already settled, and does not re-open

* **The Chief initiates recovery** *(`EM-GOV-014` Part 1)* · **recovery runs through governed rescheduling and may not bypass a rule or reuse an invalidated decision** *(`EM-GOV-013`, `EM-GOV-008`)*.
* **The Service Provider may set the DURATION of a period, never its CONSEQUENCE** — already in force, and the PO's §6.3 is consistent with it, not an extension of it.
* **The Committee-Inoperative model is NOT generalised.** **Governance records the PO's stated ground — the Committee is a mandatory institutional capability, an ordinary halt need not involve losing one — and notes it matches the distinction Governance offered independently (*capacity to act* vs *satisfaction of a condition*).**
* **`EM-GOV-012` and `EM-OPEN-047` stand: a halt never determines the outcome; expiry is an event, never an automatic outcome.**

**Traceability.** `EM-OPEN-042`④ *(pre-registered 2026-08-15)* · `EM-OPEN-102` · `EM-OPEN-109` *(new)* · `EM-GOV-008`/`011`/`012`/`013`/`014`/`058` · `EM-OPEN-047`/`066`/`071` · `SCB-4` lifecycle half · `EM-OPEN-022` Reading A · A-3.
