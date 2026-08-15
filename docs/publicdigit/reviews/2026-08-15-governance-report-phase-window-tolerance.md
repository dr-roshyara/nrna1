# Governance Report — Phase / Window Tolerance Rules

**Type:** Governance Decision Request (Session 2) · **Date:** 2026-08-15 · **Commission:** PO/ARB, *"Phase/Window Tolerance Rules"* — **business rule only**
**⛔ No code, tests, Constitution, lifecycle, aggregates, schemas, events or technical representation were designed or modified. Architecture and Session 3 remain STOPPED. `EM-OPEN-023` is not resolved, implicitly or otherwise.**

**Label discipline used throughout:** **ADOPTED** *(already binding)* · **DERIVED** *(a consequence of an ADOPTED rule — binding, adds no policy)* · **PROPOSED** *(Governance's formulation, NOT binding until the PO adopts)* · **OPEN** *(PO/ARB decision required)* · **OUT OF SCOPE**.

---

## 1 · The business definition of "tolerance period"

**PROPOSED definition** — offered so the rest of the report has one meaning, **not adopted**:

> **A tolerance period is a governed span of time, fixed in advance and attached to a scheduled election phase, during which an authorized request to progress that phase may still be considered. It is a property of the schedule, not a decision, not a permission, and not a progression.**

**Two things the definition deliberately excludes**, both DERIVED from `EM-VOT-004`:

- **A tolerance never progresses anything.** It bounds *when a request may be considered*; it never supplies the request and never supplies the permission.
- **A tolerance never becomes a time.** The published start stays 10:00 when the tolerance is 30 minutes. `10:30` is the edge of a decision period, not a new promise to the electorate. *(This is the PO's §3 principle, and it is DERIVED besides: `EM-GOV-005` forbids silently rewriting a recorded fact, and `EM-VOC-005`/`D-2` make the published schedule the authoritative one.)*

---

## 2 · ⚠️ The first finding: the commission contains **two different meanings of tolerance**

**This must be settled before any other tolerance question can be answered, because the two readings have opposite effects.**

| Reading | Text supporting it | What it does |
|---|---|---|
| **Grant** | §3: *"the tolerance creates an **additional** governed period during which progression may still be considered"* | **Extends** the time available — progression may be requested **later than** it otherwise could |
| **Bound** | §13: *"**Permitted Decision Period**"* · the final principle: *"how long the governed opportunity to request progression **remains available**"* | **Limits** the time available — progression may **no longer** be requested after the tolerance ends |

**Why this is not a quibble.** Take the PO's own example: published voting window **10:00–12:00**, start tolerance **30 minutes**.

- Under **Grant**, the opportunity was already available across the window; the tolerance adds nothing at the start and it is unclear what it grants at all.
- Under **Bound**, the Chief may request progression **only until 10:30** — after which the opportunity is no longer available even though the published window runs to 12:00. **The tolerance has removed 90 minutes of otherwise available decision time.**

**These are opposite rules with the same name.** Governance cannot choose between them: it determines whether tolerance is a relaxation or a restriction, which is the whole business meaning.

> ## ⛔ **STOP — PO/ARB decision required. Registered as `EM-OPEN-026`(a).**

**A prior question the two readings expose, also OPEN:** **what is the decision period when no tolerance is defined?** The whole published window? The published start instant only? **No adopted rule says**, and `EM-VOT-004` speaks only of *when* eligibility begins, never of when it ends. **Tolerance cannot be defined against an undefined baseline.**

---

## 3 · `T-1` Does tolerance exist? · `T-2` Who defines it? · `T-3` When binding? · `T-4` Published or internal?

**`T-1` — OPEN.** Governance does **not** adopt tolerance merely because it was proposed, per the commission's own instruction. **Governance records that the concept is coherent and addresses a real gap** — the absence of any rule about how long an opportunity remains available — **but its existence, and its direction (grant or bound), is the PO's to establish.**

**`T-2` — PROPOSED, with a gap the PO direction does not close.** The direction is *"tolerance should be given for each window in the election application."* **PROPOSED:** tolerance values are supplied **when the election is applied for/scheduled**, per phase. ⚠️ **But the direction names who *supplies* the value and not who *approves* it.** An unbounded, self-set tolerance would let the applicant neutralise the schedule — a 30-day "tolerance" on a two-hour window makes the published window decorative. **`EM-OPEN-026`(c): who approves a tolerance, and is there a governed maximum (absolute, or relative to the window)?**

**`T-3` — ⛔ STOP: dependent on `EM-OPEN-023`.** The question *"at what business moment does the tolerance become part of the official election schedule?"* **cannot be answered before publication has a meaning.** If tolerance is part of the published schedule, it binds when the schedule is published — a moment that is precisely what `EM-OPEN-023` is deciding. **Raised, not invented** (commission §14 dependency clause). Registered as `EM-OPEN-026`(d).

**`T-4` — PROPOSED: tolerance IS part of the published schedule.** *(Reasoning, offered for the PO to accept or reject.)* A tolerance determines whether a phase may still legitimately begin. Under the **Bound** reading it determines when an opportunity **dies**. **A rule that decides whether an election may start, but which the electorate cannot see, is a hidden rule** — and it sits badly beside the anti-hidden-tolerance principle in §11 of this same commission and the no-second-policy-surface constraint from the `EM-OPEN-022` ruling. **Governance nevertheless flags the counter-argument honestly:** publishing an operational allowance may read to voters as *"the election may be late"*, which is a legitimate transparency concern. **Registered as `EM-OPEN-026`(e); the PO decides.**

---

## 4 · `T-5` — What does tolerance permit? **Model B is excluded; not by preference, by adopted rule**

> ### **DERIVED — Model B (automatic progression during tolerance) is EXCLUDED.**
>
> `EM-VOT-004` (ADOPTED): *"No clock-driven lifecycle mechanism may exercise the Chief Election Officer's authority."* An automatic progression during tolerance is a clock-driven mechanism exercising exactly that authority. **Governance searched for a genuine conflict that would justify returning Model B to the PO and found none: tolerance and automation are independent — a tolerance is fully meaningful without any automatic behaviour.**

> ### **PROPOSED — Model A: within the tolerance period, the CHIEF may request progression, and the Election Rules evaluate that request as they always do.**
>
> Tolerance changes **which requests are still considered**. It changes **nothing** about who requests, what is evaluated, or what the answer may be.

**PROPOSED rule text** *(not adopted)* — **`EM-VOT-006`:**

> **A tolerance period never authorizes progression.** It establishes only that a request made within it may still be considered. **Progression requires the authorized request, and the mandatory Election conditions, exactly as it does at the scheduled time.**

**Model C is recorded as not needed:** the commission invited "something else"; Governance identifies no third model that survives `EM-VOT-004`.

---

## 5 · `T-6` start tolerance · `T-7` end tolerance — **and the collision the commission was right to suspect**

**`T-6` — start tolerance: PROPOSED coherent.** A late start delays a promise. The electorate's opportunity is not enlarged; the phase simply begins later than published, within a governed and disclosed limit. **No adopted rule is threatened**, given §4's Model A.

**`T-7` — end tolerance:**

> ## ⛔ **STOP — PO/ARB decision required. `EM-OPEN-027`.**
>
> **An end tolerance and a window extension may be the same act wearing different names.** *"Voting scheduled 10:00–12:00, end tolerance 30 minutes"* means **voting may continue until 12:30** — i.e. **votes may be cast that could not have been cast under the published window.**
>
> **`EM-GOV-004`'s boundary list expressly does NOT adopt *extending a window*.** Adopting an end tolerance would settle, by implication, a boundary the PO deliberately left open. **Governance will not do that**, and flags that any answer to `T-7` must be given knowingly as a partial answer to `EM-GOV-004`.

**Governance's substantive observation, recorded and clearly not a decision:** start and end are **not symmetric for election integrity**. A late *start* changes when an opportunity opens. A late *end* changes **who could vote** — it enlarges the electorate's window after publication, and it does so at the moment the outcome is most predictable. **A rule that treats them alike should be adopted only deliberately.**

---

## 6 · `T-8` — Per-phase tolerance, and a second dependency

**PROPOSED:** if tolerance exists, it is defined **per phase**, not globally. A single value across administration, candidacy, voting and counting would force the most consequential phase to inherit the loosest allowance.

> ### ⛔ **But: tolerance for non-voting phases STOPS on a prior gap. `EM-OPEN-030`.**
>
> **`EM-VOT-004` governs the start of VOTING only** — that scope limit is explicit in its adoption. **For entering nomination, early closure, counting and results publication, the commanded-vs-derived question (`G-PROG-1`) is still open**: it is not established that those phases progress by an authorized *request* at all. **A tolerance is a bound on a decision period; where no decision act is established, there is nothing for a tolerance to bound.**
>
> **Consequence:** tolerance can be ruled for **voting** now. Ruling it for other phases either presupposes `G-PROG-1`, or must be explicitly deferred. **Governance raises this rather than quietly generalising `EM-VOT-004` beyond its adopted scope.**

---

## 7 · `T-9` (§8) — What happens when tolerance expires?

> ## ⛔ **OPEN — PO/ARB decision required. `EM-OPEN-028`.**

**What is already settled and must constrain any answer:**

- **DERIVED:** `EM-VOC-004` already supplies the vocabulary — **expired unused · explicitly cancelled · superseded** are distinct adopted outcomes. **Whatever the PO chooses must be one of these or a newly named outcome; it may not be left unnamed**, because `EM-VOC-004` requires outcomes to stay distinguishable.
- **DERIVED (`EM-GOV-005` + `P-2H`):** the two situations the commission distinguishes — **no progression request occurred** vs **a request occurred and was refused** — **are already required to be separately recorded.** The progression-decision history holds the refusals; the lifecycle holds the ending. **The recording question is therefore ALREADY ANSWERED; only the business outcome is open.**
- **DERIVED:** whichever outcome is chosen, **it must not silently revive**: an expired opportunity is not reusable (`EM-VOC-004`, ADOPTED).

**Governance offers no preference between "expires unused" and "remains valid pending explicit rescheduling"** — they distribute responsibility differently between the Chief and the Rules, which is a governance choice.

---

## 8 · `T-10` (§9) — A refusal during the tolerance period

> ### ✅ **DERIVED — ALREADY ANSWERED by adopted rules. No new decision is required.**
>
> **Yes: the Chief may request progression again within the same opportunity.** At 10:10 the request is refused for want of an approved candidate; the candidates are corrected; at 10:20 **the Chief may request again, and the Election Rules evaluate afresh.**
>
> **Basis:** `P-2H` and the confirmed reading of `EM-VOC-004` — **a refusal is a recorded decision, not a termination.** The opportunity remains valid, and *"temporarily unable to proceed while still valid"* is precisely this situation. `EM-GOV-005` keeps **both** refusals in the protocol; the later success erases neither.

**The one open edge, and it belongs to `EM-OPEN-026`(a):** *until when* the opportunity remains valid — the tolerance edge under the **Bound** reading, or something else under **Grant**. **The right to re-request is settled; its outer limit is not.**

---

## 9 · `T-11` (§10) — Tolerance across a schedule correction

> ### ⛔ **OPEN — `EM-OPEN-029`. And the answer is CONDITIONAL on `EM-OPEN-026`(e), which is why it must not be settled by architecture.**

- **If tolerance is part of the published schedule** *(T-4 PROPOSED)*: then under `EM-VOC-005` the corrected schedule is a **new opportunity with its own schedule**, and **O2's tolerance is simply part of O2's schedule. Inheritance is not a mechanism that exists** — there is nothing to inherit. Clean.
- **If tolerance is an internal parameter**: inheritance becomes a live question — does O2 keep O1's tolerance, receive a new one, or fall to a default? **Every answer needs an explicit rule.**

**DERIVED constraint binding either way:** `EM-VOT-005` — **no authorization, refusal or eligibility decision from O1 carries into O2**, whatever happens to the tolerance value. **A tolerance is a schedule property, not a decision, so `EM-VOT-005` does not by itself settle it** — Governance states this explicitly so the ruling is not over-read in either direction.

**`EM-OPEN-022` is NOT reopened.** This is its interaction with a new concept, registered as required by commission §10.

---

## 10 · §11 — No hidden tolerance

> ### **PROPOSED — `EM-GOV-006`** *(strongly supported by adopted rules, but stated as a proposal because it is a new prohibition):*
>
> **A tolerance exists only where an Election Rule deliberately establishes it. No technical behaviour constitutes a tolerance.** Specifically **not** tolerances: a cron or scheduler grace period · a hard-coded allowance · retry windows · delayed or queued jobs · clock-drift allowance · interface grace on a button · automatic state-transition grace · any implicit *"a few minutes late is fine"* behaviour.

**Why this is nearly derived.** `EM-VOT-004` forbids a clock-driven mechanism exercising the Chief's authority; the `EM-OPEN-022` ruling forbids creating a second policy surface where an implementation decides what "doesn't really count". **An undeclared grace period is both at once.**

**Two consequences Governance states plainly:**
1. **Until a tolerance is adopted, the correct number of tolerances in this system is zero.** Any grace behaviour that exists today is **unadopted**, and adopting a tolerance rule later does not retrospectively authorize it.
2. ⚠️ **Governance has not inspected the code and asserts nothing about what the system currently does.** Whether any such behaviour exists is a **verification** question (Session 1), not a governance claim. *(Same discipline as `F-PROTO-1`.)*

---

## 11 · §12 — Which tolerance-related events are material to the protocol

**DERIVED — already required by `EM-GOV-005`'s own adopted wording** *(each attempt to proceed · each refusal and its reason · each termination, cancellation, expiry or supersession)*:

| Event | Status |
|---|---|
| progression request made | **DERIVED — material** |
| progression permitted | **DERIVED — material** |
| progression refused **+ the reason** | **DERIVED — material** *(and `F-PROTO-1`'s property 6: recordable independently of any transition success)* |
| schedule corrected · opportunity superseded | **DERIVED — material** |
| opportunity expired unused · cancelled | **DERIVED — material** |

**PROPOSED — new, tolerance-specific, not adopted:**

| Event | Status | Governance note |
|---|---|---|
| **tolerance expired** | **PROPOSED — material** | it is the moment an opportunity may cease to be available; under the **Bound** reading it is decisive, and unrecorded it cannot be reconstructed |
| **tolerance period reached** | **PROPOSED — NOT material in itself** | see below |
| **scheduled time reached** | **PROPOSED — NOT material in itself** | see below |

**Reasoning for the two negatives, offered for rejection as much as acceptance.** `EM-GOV-005` speaks of events in an election's **progression** — things that were *done*, by someone, with consequences. **A clock reaching a time is not something anyone did**, and it is fully reconstructable from the published schedule plus the protocol's timestamps. **Recording it would add volume without adding reconstructability.** ⚠️ **Governance flags the counter-argument:** if the schedule may be corrected, then *"which schedule was in force when the time was reached"* matters — but `D-2` already secures that, because each opportunity keeps its own schedule permanently. **The PO may still choose to record them; Governance simply does not find them material.**

⛔ **No database, event class, or storage design is proposed here** — only materiality. The seven `F-PROTO-1` properties continue to bind Architecture.

---

## 12 · Proposed identifiers — and why no new series was created

**Consumed existing series rather than inventing an `EM-TOL-*` family** (ES-005.4 — consume or extend, never create a second):

| Proposed ID | Content | Status |
|---|---|---|
| **`EM-VOC-006`** | the business definition of a tolerance period (§1) | **PROPOSED** |
| **`EM-VOT-006`** | a tolerance never authorizes progression; Model A only (§4) | **PROPOSED** |
| **`EM-GOV-006`** | no hidden tolerance — only a deliberate Election Rule creates one (§10) | **PROPOSED** |

**New OPEN questions registered:** `EM-OPEN-026` *(the tolerance concept — five parts: **(a)** grant or bound, and what the baseline is with no tolerance · **(b)** does tolerance exist at all · **(c)** who approves it and is there a governed maximum · **(d)** when it becomes binding — **depends on `EM-OPEN-023`** · **(e)** published or internal)* · `EM-OPEN-027` *(end tolerance vs window extension — touches `EM-GOV-004`'s unadopted boundary)* · `EM-OPEN-028` *(what happens at tolerance expiry)* · `EM-OPEN-029` *(tolerance across a correction — conditional on 026(e))* · `EM-OPEN-030` *(tolerance for non-voting phases — depends on `G-PROG-1`)*.

---

## 13 · Unresolved, and untouched

**Not resolved by this commission, as instructed:** `EM-OPEN-021` · `EM-OPEN-022` · `EM-OPEN-023` · `EM-OPEN-024` · `EM-OPEN-025` · `G-3`…`G-8` · `EM-GOV-004`'s six boundaries · the `EM-VOC-004` wording ratification.

**Three dependencies were discovered and RAISED, not resolved** — `T-3` → `EM-OPEN-023` · non-voting-phase tolerance → `G-PROG-1` · `T-7` → `EM-GOV-004`'s unadopted *extension* boundary.

**Nothing in this report is adopted. Nothing here authorizes implementation. Architecture and Session 3 remain stopped.**

---

## 14 · Handoff

```
TOLERANCE — GOVERNANCE STATUS

Definition            PROPOSED (EM-VOC-006)
T-1 exists?           OPEN — EM-OPEN-026(b); not adopted merely because proposed
⚠️ FIRST FINDING      OPEN — EM-OPEN-026(a): the commission carries TWO opposite
                      meanings of tolerance (GRANT of extra time vs BOUND on an
                      otherwise-open period). Nothing else can be settled first.
                      Sub-question: what IS the decision period with no tolerance?
T-2 who defines       PROPOSED (per phase, at application) — gap: who APPROVES it,
                      and is there a governed maximum? EM-OPEN-026(c)
T-3 when binding      STOP — depends on EM-OPEN-023 (publication). EM-OPEN-026(d)
T-4 published?        PROPOSED yes (a rule that decides whether an election may start
                      should not be invisible) — counter-argument recorded. 026(e)
T-5 what it permits   DERIVED: Model B EXCLUDED by EM-VOT-004, no genuine conflict
                      found.  PROPOSED: Model A only (EM-VOT-006)
T-6 start tolerance   PROPOSED coherent
T-7 end tolerance     ⛔ STOP — EM-OPEN-027: an end tolerance IS a window extension by
                      another name, and extension is expressly NOT adopted in
                      EM-GOV-004. Start and end are not symmetric for integrity.
T-8 per phase         PROPOSED per-phase; ⛔ non-voting phases STOP — EM-OPEN-030
                      (EM-VOT-004 is voting-scoped; G-PROG-1 still open)
T-9 expiry            OPEN — EM-OPEN-028. Vocabulary and recording already DERIVED;
                      only the business outcome is open
T-10 refusal in
     tolerance        ✅ ALREADY ANSWERED (DERIVED) — the Chief may request again;
                      refusal ≠ termination (P-2H). Outer limit → 026(a)
T-11 correction       OPEN — EM-OPEN-029, CONDITIONAL on 026(e). EM-OPEN-022 not reopened
No hidden tolerance   PROPOSED (EM-GOV-006) — and: until adopted, the correct number of
                      tolerances is ZERO; Governance asserts nothing about current code
Protocol events       DERIVED for request/permitted/refused+reason/corrected/superseded/
                      expired/cancelled.  PROPOSED: "tolerance expired" material;
                      "time reached" NOT material in itself

DECISIONS NEEDED      EM-OPEN-026 (a-e) → then 027, 028, 029, 030
                      026(a) is the gate: grant or bound
ARCHITECTURE          ⏸️ STOPPED — unchanged
SESSION 3             🛑 STOPPED — unchanged
IMPLEMENTATION        NOT AUTHORIZED
```

**Traceability.** Commission (PO/ARB, 2026-08-15) · `EM-VOT-004` · `EM-VOC-004` *(+ confirmed reading)* · `EM-VOT-005` · `EM-GOV-004` · `EM-GOV-005` · `EM-VOC-005` + `D-1`/`D-2`/`D-3` · `P-2H` · `F-PROTO-1` · `G-PROG-1` · `EM-OPEN-023`.

**STOP — end of Governance report. No Architecture work follows.**
