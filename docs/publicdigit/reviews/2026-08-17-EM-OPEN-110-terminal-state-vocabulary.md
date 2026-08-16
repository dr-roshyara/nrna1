# `EM-OPEN-110` Resolution — the terminal state's vocabulary (`EM-GOV-069` PREPARED)

**Type:** Governance resolution (Session 2, narrow commission per PO, 2026-08-17)
**⛔ Scope held: ONLY the vocabulary. No recovery rule reopened, no architecture touched, `EM-GOV-058`'s adopted text unchanged. `EM-GOV-069` PREPARED, NOT ADOPTED.**

## 1 · What must be named, and the constraints the record imposes

`EM-GOV-063`'s terminal election-level state has no name; the architecture carries it as a deliberately unnameable placeholder (D-2) and implementation is blocked exactly there. The record constrains any candidate:

* **must not deepen the *cancelled* two-level overload** (`EM-VOC-004` opportunity-level vs `EM-GOV-058` election-level — `110`'s own subject);
* **must not collide** with adopted vocabulary or codebase states — the *Inoperative* precedent (where *"Inactive"* was rejected for the `is_active` flag);
* **must not pre-resolve `EM-OPEN-042`③** (*cancel* vs *abandon*) or any open question;
* the retired/reserved words stand: *suspended* (members), *stopped* (retired), *failed* (phases; never-reached ≠ failed), *expired*/*terminated* (opportunity outcomes, `EM-VOC-004`).

## 2 · Candidates tested — the collision check did real work

| Candidate | Verdict |
|---|---|
| ~~`Expired`~~ | ⛔ opportunity outcome (`VOC-004`) **and** the trigger event's own word — would collapse event into state against the `047` separation |
| ~~`Terminated`~~ | ⛔ opportunity-level in adopted text (`VOC-004`: *"terminated or has expired"*) |
| ~~`Lapsed`~~ | ⛔ **DISQUALIFIED BY THE CODEBASE CHECK: `ChallengeState::Lapsed` already exists** (`app/Contexts/Contestation/Domain/Challenge/Challenge.php`) as a Contestation timeout state — an election-level `Lapsed` would recreate the exact two-level mistake under repair |
| ~~`Closed`~~ | ⛔ 24 corpus uses (gates, work items) |
| ~~`Abandoned`~~ | ⛔ would silently pre-resolve `042`③; actor-flavoured for a consequence nobody chose |
| ~~`Void`~~ | ⛔ electoral-law connotation (voided results) — dangerous adjacency |
| **`Discontinued`** | ✅ **corpus 0 · codebase 0 · no open-question adjacency** |

## 3 · The decision, structured as two options *(recommendation given, decision the PO's)*

**Option A — no new word: both election-level ends are `Election Cancelled`, distinguished by recorded cause.** *For:* minimal; `063` already mandates recording the cause. *Against:* two distinct governed failures (Committee-restoration failure vs halted-recovery failure) share one name, so distinguishability retreats from the NAME into a cause field — **against the grain of this programme's nine naming rulings and of `EM-GOV-059`(a)'s own insistence that the two periods stay distinct.**

**Option B (RECOMMENDED) — distinct name: `ELECTION DISCONTINUED`.** Distinct facts keep distinct names, exactly as the two clocks keep distinct parameters; the collision check is clean; *discontinued* reads as an end-by-course-of-events, without importing an actor.

## 4 · `EM-GOV-069` (PREPARED — Option B form)

> *"The terminal election-level state defined by `EM-GOV-063` is named **ELECTION DISCONTINUED**. An election is Discontinued when its halted progression was not successfully recovered within the applicable halted-election recovery period, as recorded under `EM-GOV-063`.*
> *Election-level conditions and end-states are written with the **Election** prefix — Election Inoperative, Election Cancelled (`EM-GOV-058`), Election Discontinued — while opportunity-level outcomes under `EM-VOC-004` remain unprefixed. Election Discontinued and Election Cancelled are distinct end-states with distinct recorded causes; neither is ever merged into or renamed as the other."*

**What the second paragraph does:** it settles `110`'s LEVEL half by convention — the *Election-prefix* rule the corpus already practises (*Election Inoperative*) — so `058`'s adopted text needs no amendment: *"the election shall be cancelled"* reads as Election Cancelled under the convention. **`EM-OPEN-042`③ is narrowed, not resolved:** no *abandoned* state exists in the adopted set; ③ revives only if one is ever proposed.

## 5 · On adoption

**`110` RESOLVED · architecture dependency D-2 DISCHARGED** — the placeholder acquires its rendering (`TerminalStatePlaceholder` → *Election Discontinued*) **by Governance act, exactly as the design demanded** · the last vocabulary block on any future implementation authorization is gone *(the authorization itself remains a separate PO act)*.

> **Adoption line:** *"I adopt EM-GOV-069 as prepared."* *(or: "…in Option A form", which Governance would then prepare instead)*

**Traceability.** `EM-OPEN-110` · `042`③ · `047` · `EM-VOC-004` · `EM-GOV-058`/`059`(a)/`063` · Inoperative naming precedent · collision checks this document · D-2 · A-3.
