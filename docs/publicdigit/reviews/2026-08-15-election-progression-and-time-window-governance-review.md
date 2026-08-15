# Election Progression & Time-Window Governance Rule Review

**Type:** Governance / domain-rule investigation (Session 2, Governance) · **Date:** 2026-08-15 · **Requested by:** the Chief Election Officer / PO
**⛔ Investigation only. No code, test, Constitution, or Manifesto change · no implementation granted · no ambiguity silently resolved · nothing inferred from what the software currently permits.**

---

# 1 · CURRENT RULE — what is already established

## 1.1 · Who may move an election forward *(established)*

The Constitution defines **fifteen named actions**, each naming who may perform it and what must be true first. On progression specifically:

| Action | Who | Must be true first | Moves the election to |
|---|---|---|---|
| Complete administration | chief · deputy | posts exist · **voters exist** · a chief exists | nomination setup |
| Complete nomination | chief · deputy | **at least one approved candidate** | *(stays in nomination setup)* |
| **Open voting** | **chief only** | **a voting window is defined · timezone set · at least one approved candidate** | voting active |
| Close voting | chief · deputy | — | counting |
| Publish results | chief only | — | results published |
| Suspend / resume | chief · platform admin | — | suspended |

**Established in business terms:** every *named* progression step belongs to a person — and opening voting and publishing results belong to **the chief alone**. The Manifesto's rule-ownership map states the same: *"Only the chief may open voting or publish results."*

## 1.2 · Candidate and voter prohibitions *(partly established — and precisely placed)*

- **`EM-VOT-001` (adopted):** *"Without a candidate, an election must not proceed to the next phase. Voting must not begin unless the election has at least one valid (approved) candidate."*
- **`EM-VOT-002` (adopted, implemented, verified):** an election must have at least one approved candidate before voting may be opened — enforced on **both** paths.
- **`EM-VOT-003` (adopted, NOT implemented):** voting may begin only with **at least one approved candidate and at least one admitted voter**.
- **Voters are required at exactly one place today:** completing administration. **Nowhere at the voting boundary** — neither the chief's action nor the automatic path checks that voters exist.

## 1.3 · What the system actually does with time *(the decisive finding)*

The election's *authoritative state* is not only the result of the chief's actions. It is **derived**, and the derivation reads the clock:

| Derivation rule | Effect | Requires a human act? |
|---|---|---|
| The voting end time has passed (and setup was completed) | election becomes **counting** | **No** |
| The voting window is open now **and** a candidate is approved | election becomes **voting active** | **No** |
| Setup complete and the window has not started | election is **ready for voting** | No |

**So today, in business terms: the clock advances the election.** The chief's "open voting" action exists, but the election reaches *voting active* — and later *counting* — **whether or not the chief ever acts.**

**Corroborating detail:** "complete nomination" does not itself move the election anywhere; it records that nomination is finished. What moves the election into voting is **the arrival of the window**.

---

# 2 · GAP — comparison against the stated business intent

| # | Business requirement | Constitution | Manifesto | Complete? | Gap |
|---|---|---|---|---|---|
| A | Windows are time-based | implied — a window must be *defined* before voting opens | silent | **partial** | "window" is a precondition, never defined as a business concept |
| B | **Time arriving does NOT advance the election** | **silent** | **silent** | ❌ **NO** | **and the running system does the opposite** — §1.3 |
| C | A manual *proceed* is required | states *who may* open voting | "only the chief may open voting" | **partial** | never stated as *the only* way forward |
| D | A manual *stop* controls progression | suspend · close voting exist | silent | **partial** | no "stop the clock" concept; suspension is a different act |
| E | No voters ⇒ cannot proceed where voters are required | at completing administration only | `EM-VOT-003` adopted | **partial** | **not enforced at the voting boundary on either path** |
| F | No approved candidates ⇒ cannot proceed | yes, at opening voting | `EM-VOT-001/002` | ✅ **YES** | — |
| G | All preconditions checked before progression | yes for the chief's actions | — | **partial** | the automatic path checks only window + candidates |
| H | **Chief may correct election timing** | **no such action exists** | **silent** | ❌ **NO** | **not established** |
| I | **Chief may adjust a slot already past** | **no such action exists** | **silent** | ❌ **NO** | **not established** |
| J | Adjusting timing does not itself advance the election | — | — | ❌ | no rule to qualify |
| K | Adjusting does not bypass candidate/voter rules | — | — | ❌ | no rule to qualify |
| L | No skipping a level | the chief's actions are state-constrained | silent | **partial** | the automatic path can jump (e.g. straight to counting) |
| M | Auditability of a timing change | — | — | ❌ | no rule requires it |

## 2.1 · The central conflict — reported, not resolved

Two authorities describe progression differently, and **both are currently in force**:

- **The Constitution** describes progression as **named acts by named people, gated by preconditions**.
- **The accepted architecture decision** (`ADR_20260807_1500`) makes the **derived state the single source of truth** — and that derivation, as shown, **advances on the clock**.

Today they coexist because the Constitution governs *who may act* while the derivation governs *what the state is*. **The business rule you have now stated collides with that arrangement directly**, because it requires that the state must not change without an act. **Which authority prevails is a decision only you/ARB can make. Governance does not resolve it here.**

## 2.2 · Two prerequisite questions already open

`PBDIGIT-59` — **which clock is constitutional** — and `PBDIGIT-67` — **what an entered time means** — are both registered and undecided. **A window cannot be defined in business terms before those two are answered**, because "the window has arrived" has no agreed meaning yet.

---

# 3 · The past-time-slot question, answered plainly

> **The current business rules do not establish this authority.**

There is **no constitutional action** to change, extend, move, reopen, or reschedule an election's timing — not for a future slot, not for the current one, and not for one already past. The Manifesto contains no such rule either. Whatever the software may currently permit through an edit screen is **implementation behaviour, not a business rule**, and this review does not treat it as one.

---

# 4 · CONCLUSION — **C · NOT COMPLETE**

The **core** of your rule — *time makes a window eligible; the Chief Election Officer decides whether to proceed; preconditions decide whether proceeding is permitted* — **is not established in either document, and the running system currently behaves the opposite way.** The **schedule-correction authority** is not established at all. Fragments exist and are sound: the candidate prohibition (complete), the chief's exclusive right to open voting (established), the voter requirement (adopted for the voting boundary, unimplemented; enforced only at an earlier stage).

---

# 5 · RECOMMENDED MINIMUM RULE EXTENSION *(business meaning only — proposed, not adopted)*

Governance proposes the smallest set of statements that would make the intent authoritative. **Each needs your ruling; none is adopted by this document.**

1. **What a window is** — a named period with a scheduled start and end, belonging to one stage of the election.
2. **What its time arriving means** — *the stage becomes eligible for the officer's decision.* **Arrival alone changes nothing about the election.**
3. **Who proceeds** — the Chief Election Officer, by explicit act, for each stage requiring it.
4. **What must hold to proceed** — every requirement of the stage being entered, checked at the moment of the act.
5. **No voters** — where the next stage requires voters, the election does not proceed. The officer is told why.
6. **No approved candidates** — as already adopted; unchanged.
7. **Who may change timing** — the Chief Election Officer.
8. **May a past slot be changed** — *(your decision)* and if yes, under what limits.
9. **Why** — to correct the schedule so a legitimate election can continue, not to alter an outcome.
10. **Does changing timing advance the election** — **no**, never by itself.
11. **May it skip a stage** — **no**; every required stage is still entered in order.
12. **Auditability** — who changed what timing, when, why, and what happened next.

---

# 6 · BUSINESS DECISIONS REQUIRED FROM YOU

1. **Does the arrival of a window advance the election, or merely make it eligible for your decision?** *(This is the pivotal one — it decides §2.1's conflict.)*
2. **May the Chief Election Officer change a time slot that has already passed** — and under what limits (e.g. only while the election has not progressed past that stage; never after voting has been opened; never after results)?
3. **Must a timing change be justified and recorded?**
4. **Do 59 and 67 need answering first** (which clock; what an entered time means), given that a window cannot be defined without them?

---

# 7 · IMPLEMENTATION CONSEQUENCE *(high level only — nothing authorized)*

If the rule is adopted as stated, the software would eventually have to: stop deriving progression from the clock, and instead derive *eligibility* from the clock while requiring a recorded act to progress · check the entering stage's requirements at the moment of the act · gain an explicit, audited timing-correction act · and keep both paths consistent so no route bypasses the act (the same both-paths lesson as `EM-VOT-002`). **The change touches the state-derivation model, so it would need its own architecture step before implementation** — and that is a *later* work item, not part of this review.

---

# 8 · EFFECT ON CURRENT ELECTION-ONLY WORK

| Work item | Relationship | Why |
|---|---|---|
| **A-2** — voting-start entitlement defect | **related but independent** | different question entirely: *may this person vote* vs *may the election proceed*. **No dependency; A-2 is safe to grant and run unchanged.** |
| **`EM-VOT-002`** — candidates before voting | **direct dependency** | the new rule generalises it; must not contradict it. It also proves the pattern: a requirement must hold on *every* route into the stage |
| **`EM-VOT-003`** — voters before voting | **direct dependency** | the new rule and this adopted rule are the same requirement seen twice; implementing one should not be done without the other's meaning settled |
| **`EM-OPEN-021`** — voting opens with no approved candidate | **direct dependency — and the new rule may dissolve the question** | that state exists **because the clock advances the election**. If arrival no longer advances it, an election with no approved candidate simply **does not proceed**, and stays where it is until corrected. **Recommendation: rule the progression question before, or together with, `EM-OPEN-021` — deciding `EM-OPEN-021` first risks naming a state that the new rule would prevent from ever occurring.** |
| **`PBDIGIT-59` / `67`** — clock authority; entered-time meaning | **direct dependency (prerequisite)** | "the window has arrived" is undefined until these are answered |
| **`PBDIGIT-50` / `EM-OPEN-018`** — timezone display | related but independent | presentation, not authority |
| **Voter-source sovereignty (Phase 3)** | **unrelated** | separate architectural track, not touched |

---

# 9 · EVIDENCE LOCATIONS

`app/Domain/Election/Constitution/ElectionConstitution.php` — the fifteen actions, their roles and preconditions (opening voting at the `open_voting` entry; voters at `complete_administration`) · `app/Application/Election/Services/ElectionLifecycleEngineImpl.php` — the derivation rules, in order (counting on end-time; voting-active on window+candidates; ready-for-voting before start) · `docs/publicdigit/business_rules/ELECTION_MANIFESTO.md` — `EM-VOT-001` · `EM-VOT-002` · `EM-VOT-003` and its note · the rule-ownership map ("only the chief may open voting or publish results") · `EM-OPEN-018` · `EM-OPEN-021` · `ADR_20260807_1500` (accepted — derived state as single source of truth) · registered decisions `PBDIGIT-59`, `PBDIGIT-67`.

**Nothing was changed. No implementation is authorized. Where the rules are silent, this review says so rather than filling the silence.**
