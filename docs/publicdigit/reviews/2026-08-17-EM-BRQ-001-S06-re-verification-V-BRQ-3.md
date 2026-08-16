# `EM-BRQ-001` — S-06 re-verification — lane V-BRQ-3 (blind)

**Date:** 2026-08-17 · **Lane:** V-BRQ-3 (independent, blind) · **Scenario:** S-06 (family F-2, Committee arithmetic N = 3)
**Rule corpus:** `docs/publicdigit/business_rules/ELECTION_MANIFESTO.md`
**Verified sha256:** `b6f232cdf8d594c298effcc33639f20476394817018ae60305114760b38aa4c3` ✅ **MATCH** (verified with `sha256sum` before any other read)

**Standing configuration (from the frozen suite):** one election · acceptance participation model = Election Committee alone (Model A) · threshold rule `TWO_THIRDS_OF_COMMITTEE_VOTES` · Committee constituted with **3 members** at Election Appointment · service-policy periods configured and recorded · schedule published by the Chief.

**Scenario S-06:** *2 members become unavailable before the gate; 1 remains. Derive everything that follows, including which clock (if any) is running at each moment and what event ends the situation.*

**Method note:** only rows marked ADOPTED were treated as rules. PENDING rows (e.g. `EM-GOV-023`, `EM-GOV-037`), PROPOSED frameworks (§4c) and `EM-OPEN` rows were not used as rules; `EM-OPEN` rows are cited only to map an undefined answer to an already-registered question.

---

## 0 · Derivation — the load-bearing fork, resolved by adopted text

The scenario says the members "become unavailable". The adopted corpus makes this word carry a governed distinction:

- **`EM-GOV-064` (ADOPTED):** a Committee seat becomes **VACANT only upon a recorded vacancy event** (resignation with reason · death/established permanent incapacity · loss of eligibility or independence). **Temporary unavailability is NOT a vacancy, triggers no filling, and means no Committee Vote is expressed for the affected decision — denominator and threshold unchanged.**
- **`EM-GOV-065` (ADOPTED):** "unable to function" is **arithmetic: non-vacant seats < required Committee Votes**; **Inoperative begins at the recorded vacancy event causing it** — no declaration, no determiner.

S-06 as stated records **no vacancy event** — nobody resigns, dies, or loses eligibility; they are simply unavailable. On the facts given, the scenario is the **temporary-unavailability case**, and the derivation below follows that primary reading. The vacancy branch is derived separately (§0.3) because "derive everything that follows" requires showing what a conversion would change.

### 0.1 · Arithmetic

- Constituted membership = 3 (`EM-GOV-026`, standing configuration); minimum three members, one Committee Vote each (`EM-GOV-033`).
- `TWO_THIRDS_OF_COMMITTEE_VOTES`, rounded up, on a Committee of at least three (`EM-GOV-036`): 3 → **2 required**. Rounding is upward to the smallest whole number satisfying the threshold (`EM-GOV-038`(a)).
- Denominator = **constituted** membership, never currently occupied or available seats (`EM-GOV-057`). It does not move with availability.
- **Presently castable votes = 1** (the remaining member). 1 < 2: the threshold **cannot presently be satisfied** — but see §0.2 for why that is not a halt.

### 0.2 · The gate's state — OPEN, not halted (primary reading)

`EM-GOV-068` (ADOPTED 2026-08-17) governs the gate interval directly:

- A reached gate is **OPEN** while undecided and the threshold remains **mathematically ACHIEVABLE on recorded facts**; progression is **HALTED** at the gate (within `EM-GOV-052`'s meaning) **only on DECIDED failure or MATHEMATICAL impossibility on recorded facts** (`050`/`051`/`065`); **temporary unavailability does not by itself halt**.
- Achievability on recorded facts uses `EM-GOV-065`'s arithmetic: **non-vacant seats = 3 ≥ 2 required** → achievable. (The instantaneous reading — "only 1 vote can be cast right now, therefore the gate cannot be satisfied, therefore halt" — is expressly excluded by `EM-GOV-068`, which names it "the S-06 divergence".)

Consequences while the two members remain temporarily unavailable:

1. **No halt** (`EM-GOV-068`); `EM-GOV-052` does not fire; the preceding phase remains completed and the next phase may not begin because the gate is undecided, not because it failed.
2. **Not Inoperative** — no vacancy event has been recorded, so "unable to function" cannot begin (`EM-GOV-064`/`065`).
3. **No filling** — a non-vacant seat cannot be filled; `EM-GOV-056` is available only for seats vacant through a recorded vacancy event (`EM-GOV-064`; `EM-GOV-057` annotation as conformed by record repair C: "temporary unavailability is not fillable").
4. **Decidability with one member:** the remaining member alone can neither satisfy the gate (1 accept < 2) nor produce a decided failure (a decided failure requires the threshold to be unreachable on **expressed** positions: objections ≥ N − required + 1 = 2; a single objection still leaves 2 potentially accepting seats on recorded facts). **One member cannot end the gate in either direction.**
5. **Cast-vote semantics:** whatever position the remaining member expresses **stands** notwithstanding later events; a seat expresses at most one position per acceptance decision; a seat that has not expressed may still do so (`EM-GOV-066`).

### 0.2a · Clocks (primary reading) — **NONE runs**

`EM-GOV-062` (ADOPTED): the **halted-election recovery clock** runs only while the election is **operative and halted**; the **Committee-restoration clock** runs only while the election is **Election Inoperative**; each pauses whenever its triggering condition is not active; the two can never run at once.

- Election is **not halted** (§0.2, point 1) → halted-recovery clock **not running**.
- Election is **not Inoperative** (§0.2, point 2) → Committee-restoration clock **not running**.
- No other governed period exists, and no technical grace period, timeout or scheduler behaviour may constitute one (`EM-GOV-006`).

**Therefore no clock runs at any moment of S-06 as stated.** An OPEN gate is **unbounded in time** — stated in `EM-GOV-068`'s own annotation, registered as `EM-OPEN-053`, classified NON-BLOCKING by the PO, deliberately not resolved.

### 0.3 · The vacancy branch (if either unavailability is, or becomes, a recorded vacancy event)

If one or both unavailabilities convert into recorded vacancy events (resignation with reason · death/permanent incapacity · loss of eligibility/independence — `EM-GOV-064`):

- **One vacancy** (1 vacant, 2 non-vacant): non-vacant seats = 2 ≥ 2 required → **not** unable to function; the authorised organisational governance body **shall fill** the vacancy (`EM-GOV-056`); denominator and threshold unchanged (`EM-GOV-057`); the gate remains OPEN on the same arithmetic — but note that in S-06 the second non-vacant member is still temporarily unavailable, so the gate still cannot presently be decided.
- **Two vacancies** (2 vacant, 1 non-vacant): non-vacant seats = 1 < 2 required → the Committee is **unable to function** by arithmetic; **ELECTION INOPERATIVE begins at the second recorded vacancy event** — no declaration (`EM-GOV-065`, `EM-GOV-058`). Then:
  - The **Committee-restoration clock runs** (its condition is active — `EM-GOV-062`); it is an **election-level allowance**, not per-failure (`EM-GOV-061`(b)); its policy version and duration are recorded (`EM-GOV-059`(a)).
  - Any active halted-election recovery period would be **paused** (`EM-GOV-060`) — in S-06 none was running, so there is nothing to pause.
  - **Restoration:** the authorised organisational governance body fills the vacant seats (`EM-GOV-056`); replacements fill existing seats, do not reopen Election Appointment, do not alter decisions already made, and **participate fully in positions the seat has not yet expressed** (`EM-GOV-056`, `EM-GOV-066`). On restoration the election **returns to its prior condition** — here, the still-undecided (OPEN) gate; restoring the Committee restores the ability to decide, never the decision (`EM-GOV-059`(c), `EM-GOV-060`).
  - **Non-restoration:** "cannot restore" is evidenced **solely by restoration-period expiry** (`EM-GOV-065`); expiry of the Committee-restoration period → **the election is cancelled** (`EM-GOV-058`, `EM-GOV-060` last sentence). The Service Provider configures the duration, never the consequence (`EM-GOV-058` safeguard).
  - The **identity** of the filling authority is undefined — external dependency, registered: `EM-OPEN-066` (with `EM-OPEN-101`②/`EM-OPEN-102` recording that this recovery waits on an actor the election cannot compel).

### 0.4 · Timeline ("which clock at each moment")

| Moment | Election condition | Clock running |
|---|---|---|
| Members 2 and 3 become temporarily unavailable (before the gate) | Operative; phase progression unaffected by the unavailability itself; events recorded (`EM-GOV-005`) | **None** |
| Gate is reached | Gate **OPEN** — undecided, achievable on recorded facts (`EM-GOV-068`) | **None** |
| While unavailability persists | Gate OPEN; remaining member may express a position (it stands, `EM-GOV-066`); gate cannot be decided by one member | **None** — unbounded (`EM-OPEN-053`, known, non-blocking) |
| *(Branch)* vacancy events recorded, 1 non-vacant seat remains | **ELECTION INOPERATIVE** from the causing event (`EM-GOV-065`/`058`) | **Committee-restoration clock only** (`EM-GOV-062`) |
| *(Branch)* Committee restored within the period | Returns to prior condition: the unresolved OPEN gate (`EM-GOV-059`(c)/`060`) | **None** (gate OPEN again) |
| *(Branch)* restoration period expires unrestored | **Election cancelled** (`EM-GOV-058`/`060`) | — (terminal) |

### 0.5 · What event ends the situation

The situation (an undecidable OPEN gate) ends **only** by one of:

1. **Return of availability + expression of positions deciding the gate** — at least one unavailable member becomes available again and expresses a position: 2 accepts → threshold satisfied, gate passes (`EM-GOV-036`/`038`); 2 objections → decided failure → progression **HALTED** at the gate condition (`EM-GOV-052`, `EM-GOV-068`) → the halted-election recovery clock starts (`EM-GOV-062`) and `EM-GOV-063`'s expiry consequence attaches. (No rule governs "returning" as an act; unavailability is defined by non-expression — `EM-GOV-064` — so its end is simply the member's capacity to express.)
2. **Conversion into recorded vacancy events** → §0.3: Inoperative → restoration (back to the OPEN gate) or cancellation at restoration-period expiry.
3. **Nothing else.** No time-based event exists in this state; no clock runs; no actor is obliged to act (`EM-OPEN-053`/`EM-OPEN-046`, registered open).

---

## 1 · The thirteen questions

| # | Question | Answer | Citations |
|---|---|---|---|
| 1 | **State** | Election **operative**; preceding phase completed; the acceptance gate, once reached, is **OPEN** (undecided; threshold mathematically achievable on recorded facts: 3 non-vacant seats ≥ 2 required). **Not halted, not Inoperative.** | `EM-GOV-068` · `EM-GOV-064` · `EM-GOV-065` · `EM-GOV-052` (not triggered) |
| 2 | **Condition** | 2 of 3 constituted seats **temporarily unavailable** (no recorded vacancy event); denominator stays 3, required stays 2; presently castable votes = 1, which can neither satisfy (needs 2 accepts) nor decidedly defeat (needs 2 objections) the gate. | `EM-GOV-033` · `EM-GOV-036` · `EM-GOV-038` · `EM-GOV-057` · `EM-GOV-064` |
| 3 | **Responsible actor** | For deciding the gate: the **Committee members** (Model A; one vote each). **No actor bears a duty to end the open-gate wait** — UNRESOLVED — Governance rule missing (registered: `EM-OPEN-053`, with `EM-OPEN-046`; PO-classified non-blocking per `EM-GOV-068` annotation). In the vacancy branch: the authorised organisational governance body fills seats (`EM-GOV-056`); its identity is UNRESOLVED — Governance rule missing (registered: `EM-OPEN-066`). | `EM-GOV-033` · `EM-GOV-056` · `EM-GOV-068` · `EM-OPEN-053` / `-046` / `-066` |
| 4 | **Permitted action** | Remaining member: express acceptance / objection / formal concern (recorded; it stands). Unavailable members: become available and express (a seat expresses at most one position per decision). Any member: resign **with recorded reason** → vacancy event. Chief: none specific — no appointment power over the Committee. **Not permitted:** filling a non-vacant seat · reducing denominator/threshold · reconstituting the Committee · manufacturing an outcome or a halt. | `EM-GOV-066` · `EM-GOV-022` · `EM-GOV-064` · `EM-GOV-028` · `EM-GOV-056` · `EM-GOV-057` · `EM-GOV-005` (negative half) |
| 5 | **Consequence of acting** | 2 accepts → gate satisfied → next phase may begin subject to the full progression conditions. 1 objection alone → recorded, gate stays OPEN (still achievable on recorded facts). 2 objections → decided failure → **HALTED at the gate condition**; halted-recovery clock starts. Resignation(s) with reason → vacancy → filling duty; if non-vacant seats fall below 2 → **Election Inoperative** at the causing event. | `EM-GOV-036`/`038` · `EM-GOV-010` · `EM-GOV-068` · `EM-GOV-052` · `EM-GOV-062`/`063` · `EM-GOV-064`/`065`/`058` |
| 6 | **Consequence of not acting** | The gate remains **OPEN indefinitely**. No clock runs, nothing expires, no automatic outcome arises, and no technical grace/timeout may be invented to end it. The unbounded wait is the adopted state of the rules (visible, deliberate). | `EM-GOV-068` (annotation) · `EM-GOV-062` · `EM-GOV-006` · `EM-OPEN-053` (registered) |
| 7 | **Deadline** | **None.** Halted-recovery clock: not running (election not halted). Committee-restoration clock: not running (not Inoperative). The two clocks are the only governed periods and neither trigger condition is active; they can never run at once. | `EM-GOV-062` · `EM-GOV-068` · `EM-GOV-059`(a) |
| 8 | **Expiry consequence** | **Not applicable in this state** — no period is running. (Derived for the exits: after a decided failure, halted-recovery expiry → terminal election-level state per `EM-GOV-063`; while Inoperative, restoration-period expiry → **election cancelled** per `EM-GOV-058`/`060`.) | `EM-GOV-063` · `EM-GOV-058` · `EM-GOV-060` |
| 9 | **Recoverability** | **Fully recoverable.** Temporary unavailability ends by the members' return (no governed act required; unavailability = non-expression). Vacancy branch: filling by the external authority restores function; replacements participate fully in undecided positions; on restoration the election returns to the unresolved gate. Any cast vote survives all of it. | `EM-GOV-064` · `EM-GOV-056` · `EM-GOV-066` · `EM-GOV-059`(c) · `EM-GOV-060` |
| 10 | **Configuration mutability** | **Immutable.** Denominator = constituted membership (3) and required votes (2) do not change with availability, vacancy, or filling. Acceptance model and threshold rule were selected at Application and recorded; the Committee is constituted once; filling a vacancy is not reconstitution. No rule permits altering any of it mid-election. | `EM-GOV-057` · `EM-GOV-032` · `EM-GOV-017` · `EM-GOV-026` · `EM-GOV-056` |
| 11 | **Authority** | Gate decision: **Committee members alone** (Model A), no individual veto (and at 3/2 none is arithmetically possible). Chief: no appointment authority over Committee or Deputy; cannot decide or bypass the gate. Vacancy filling: organisational governance body (identity external, unresolved — `EM-OPEN-066`). Service Provider: durations only, never consequences. | `EM-GOV-025` · `EM-GOV-022` · `EM-GOV-033` · `EM-GOV-028` · `EM-GOV-056` · `EM-GOV-058` (safeguard) · `EM-GOV-008` |
| 12 | **Record** | Each unavailability event recorded when it occurs; the gate's being reached, every expressed position (including a lone objection that changes nothing), every progression request and refusal with reason — all durable, append-only, never fabricated: **no event may be manufactured** for a next phase never begun, and a temporary unavailability must not be recorded as a vacancy, a halt, or a failure. Vacancy branch adds: vacancy event + reason, Inoperative onset (computed from the recorded event), policy version and duration of the restoration period, filling, resumption or cancellation. | `EM-GOV-005` (incl. negative half) · `P-2H` · `F-PROTO-1` props 1–7 · `EM-GOV-064` · `EM-GOV-065` · `EM-GOV-059`(a) · `EM-GOV-045` |
| 13 | **Next state** | One of: **(a)** gate PASSES (≥2 accepts after return) → next phase may begin subject to `EM-GOV-010`'s full conditions; **(b)** gate FAILS decidedly (≥2 objections) → HALTED at the gate condition → halted-recovery clock → recovery or `EM-GOV-063` terminal state at expiry; **(c)** vacancy events → **ELECTION INOPERATIVE** → restored (back to the OPEN gate) or **cancelled** at restoration-period expiry; **(d)** otherwise the gate remains OPEN, unbounded. | `EM-GOV-010` · `EM-GOV-052` · `EM-GOV-062`/`063` · `EM-GOV-058`/`059`/`060` · `EM-GOV-068` |

---

## 2 · Classification

**🟢 DETERMINATE** — on the facts stated (unavailability with no recorded vacancy event), the adopted corpus yields exactly one answer at every step: gate OPEN · no halt · not Inoperative · no clock running · configuration immutable · ends only by return-and-decision or by conversion to vacancy events. The determinacy is supplied decisively by `EM-GOV-068` (adopted 2026-08-17), which expressly excludes the instantaneous reading of `EM-GOV-052` — the Manifesto itself names that excluded reading "the S-06 divergence".

Two answers inside the table are UNRESOLVED, and **both map to already-registered questions (🔴 KNOWN, no NEW findings):**

- **Q3/Q6/Q7 residue — nobody is obliged to end an OPEN gate and nothing bounds it in time:** `EM-OPEN-053` (with `EM-OPEN-046`); expressly made visible by `EM-GOV-068`'s annotation and PO-classified NON-BLOCKING. The determinate answer under current rules is "no deadline, no obliged actor"; whether there *should* be one is the registered open question.
- **Vacancy-branch actor identity — which organisational body fills Committee seats:** `EM-OPEN-066` (external dependency; also recorded at `EM-OPEN-101`②/`EM-OPEN-102` that Committee recovery waits on an actor the election cannot compel).

**⚫ Contradiction hunt (active, two candidates examined, none live):**

1. **`EM-GOV-057` annotation vs `EM-GOV-068`.** `EM-GOV-057` carries: *"With 3 constituted, 2 unavailable makes the Committee threshold presently unsatisfiable — recoverable through `EM-GOV-056` only where seats are VACANT through a recorded vacancy event (`EM-GOV-064`); temporary unavailability is not fillable."* Read instantaneously, "presently unsatisfiable" could suggest a `EM-GOV-052` halt. `EM-GOV-068` resolves this: HALTED requires decided failure or mathematical impossibility **on recorded facts** (`065` arithmetic: non-vacant seats), and *"temporary unavailability does not by itself halt"*. "Presently unsatisfiable" is a description of the instant, not a halt trigger. **Reconciled by adopted text — not a contradiction.**
2. **`EM-GOV-052` ("cannot be satisfied") vs the OPEN gate.** Same shape; `EM-GOV-068` states in terms that it aligns `052`'s wording with its adopted neighbours and excludes the instantaneous reading. **Not a contradiction in the frozen corpus.**

---

## 3 · File-access attestation (blinding protocol)

Every file access this lane performed, exhaustively:

| Access | Path | Scope |
|---|---|---|
| `sha256sum` (hash only, before all reads) | `docs/publicdigit/business_rules/ELECTION_MANIFESTO.md` | permitted input 1 |
| `grep -n` (section headers/locator only) | `docs/publicdigit/reviews/2026-08-16-EM-BRQ-001-frozen-scenario-suite.md` | permitted input 2 — obtained the standing configuration (line 6), S-06 (line 18), and the thirteen questions (line 47); scenario one-liners of other families appeared as locator output and were not used |
| `Read` (full, lines 1–615) | `docs/publicdigit/business_rules/ELECTION_MANIFESTO.md` | permitted input 1 |
| `Write` (this report) | `docs/publicdigit/reviews/2026-08-17-EM-BRQ-001-S06-re-verification-V-BRQ-3.md` | deliverable |

**Not accessed:** any other file under `docs/publicdigit/reviews/` (including anything named pass/comparison/cluster/resolution/registration), anything under `.claude/`, `docs/plans/`, any git history command, any repo-wide search outside the Manifesto. Nothing was committed.
