# `EM-BRQ-001` — GOVERNANCE FIRST-PASS REPORT

**Type:** Qualification first pass (Governance lane, grant `G-EM-BRQ-QUAL`) · **Date:** 2026-08-16
**Inputs:** corpus sha256 `5172d3e2…` @ `affddca6` · suite frozen @ `9b9d4d2a` · **adopted rows only; every cell cites a rule or records `UNRESOLVED`.**
**⛔ This report RESOLVES NOTHING. NEW findings are candidates for PO acceptance as new Governance work; the Manifesto is not modified by this pass.**

## 0 · Result

> ## **⚫ CONTRADICTIONS FOUND: 0 · 🔴 NEW GAPS: 7 (N-1…N-7) · 🔴 KNOWN: every other undefined cell mapped to an existing `EM-OPEN` row · 🟢 13 of 20 scenarios fully determinate.**
> **The adopted corpus never fought itself in 20 scenarios. Where it fails, it fails by SILENCE, not by conflict — and `EM-GOV-063` proved to be the piece that makes most failure paths terminate deterministically.**

**Tally:** 🟢 13 · 🟡 0 · 🔴 NEW 6 scenarios (7 findings) · 🔴 KNOWN 1 scenario · ⚫ 0.

Q-key: ①state ②condition ③responsible ④permitted action ⑤if acted ⑥if not acted ⑦deadline ⑧at expiry ⑨recoverable ⑩config change ⑪authority ⑫record ⑬next state.

---

### S-01 · 🔴 NEW (N-1, N-7) — normal lifecycle walk
| # | Answer |
|---|---|
| ① | phases per adopted chain *(act v2 item 13)*: appointment → administration → candidacy → voting-prep → voting → counting → result publication |
| ② | per transition: `EM-GOV-010` ①schedule ②predecessor complete ③rules permit ④authorized request *where required* |
| ③ | voting entry: **Chief requests** (`EM-VOT-004`) · **other transitions: `UNRESOLVED` — no adopted rule names the requesting actor or whether a request is required → N-1** |
| ④ | publish schedule (Chief/Deputy, `EM-GOV-008`) · request progression (voting: `EM-VOT-004`) |
| ⑤ | evaluation → progress on satisfaction (`EM-GOV-010`/`011`) |
| ⑥ | boundary alone never progresses or assigns outcomes (`EM-GOV-011`) |
| ⑦ | published schedule = plan (`EM-GOV-010`) |
| ⑧ | evaluation trigger only (`EM-GOV-011`) |
| ⑨ | n/a (no failure) |
| ⑩ | model+threshold fixed at Application (`EM-GOV-032`) |
| ⑪ | org authority appoints Deputy+Committee (`EM-GOV-028`); Chief appointment source: wall (`EM-OPEN-049`, recorded) |
| ⑫ | protocol per event (`F-PROTO-1`, `EM-GOV-011`) |
| ⑬ | next phase in chain · **gate positions: TWO election-wide gates adopted; Gate 2 = post-counting (`EM-GOV-016`); Gate 1's POSITION in the chain: `UNRESOLVED` → N-7** |

### S-02 · 🟢 — 3/3 accept *(N=3, threshold ⌈2·3/3⌉=2: `EM-GOV-036`/`038`, denominator=constituted 3: `EM-GOV-057`)*
①gate open ②≥2 accepts ③members ④cast Committee Vote (one each, `EM-GOV-033`) ⑤3≥2 → **gate passes**; Model A: Committee threshold is the sole applicable one (`EM-GOV-025`/`032` conditional applicability) ⑥(see S-04/⑥ family) ⑦none adopted (`EM-OPEN-053` KNOWN) ⑧n/a ⑨n/a ⑩frozen (`EM-GOV-032`) ⑪Committee ⑫votes+outcome (`EM-GOV-031` dissent-recording clause) ⑬next phase.

### S-03 · 🟢 — 2 accept / 1 object
As S-02; 2≥2 → **passes**; objection recorded, **no individual veto** (`EM-GOV-031`); objection ≠ unavailability ≠ abstention kept distinct (`EM-GOV-043` discipline, Committee analogue via `031`).

### S-04 · 🔴 NEW (N-2) — 1 accept / 2 object
| # | Answer |
|---|---|
| ① | gate fails: threshold unmet → **next phase may not begin; preceding phase remains completed; halted at the gate condition** (`EM-GOV-052`) |
| ② | acceptance per threshold — failed by OBJECTION, not attrition |
| ③ | Chief must initiate recovery (`EM-GOV-014` P1) |
| ④ | **`UNRESOLVED` → N-2: `EM-GOV-047` inapplicable (attrition-only) · `EM-GOV-054` expressly forbids substitution for actual objection · `EM-GOV-013` addresses schedule defects, and nothing here is a schedule defect · NO adopted rule says whether the gate may be re-decided** |
| ⑤ | — (no permitted act identified) |
| ⑥⑦⑧ | halted clock runs (`EM-GOV-062`) → expiry → **terminal state** (`EM-GOV-063`), cause recorded |
| ⑨ | not by any identified governed act |
| ⑩ | ⛔ threshold may not be changed to escape the failure (committed-decision prohibition) |
| ⑪ | none beyond Chief-initiation |
| ⑫ | objections with reasons (`EM-GOV-031`), halt (`011`/`052`), expiry+failed recovery (`063`) |
| ⑬ | terminal (unnamed — KNOWN `EM-OPEN-110`); gate-interval state KNOWN `EM-OPEN-095` |

### S-05 · 🟢 — 1 temporarily unavailable, 2 accept
2≥2 (denominator stays 3, `EM-GOV-057`) → **passes**. `EM-GOV-056`: unavailability → org **shall** fill the vacancy — determinate. **Observation → N-3:** `056` collapses *temporarily unavailable* into *vacancy to fill* — no temporary/permanent distinction Committee-side (unlike the representative five-state discipline, `EM-GOV-043`), and a returning member vs an installed replacement is unaddressed.

### S-06 · 🔴 NEW (N-4) — 2 unavailable, 1 remains
| # | Answer |
|---|---|
| ① | max possible accepts 1 < 2 → gate cannot be satisfied → halted (`EM-GOV-052`) |
| ② | ≥2 of denominator 3 |
| ③ | org authority **shall** fill both seats (`EM-GOV-056`); Chief initiates recovery (`014` P1) but has no appointment power (`028`) |
| ④ | wait for filling; coordinate (`028`) |
| ⑤ | seats filled → gate decidable → proceed |
| ⑥ | **which regime? halted clock runs while "operative and halted" (`EM-GOV-062`) → `EM-GOV-063` at expiry. `EM-GOV-058` (Inoperative) requires *"unable to function"* + *"cannot restore"* — `UNRESOLVED` → N-4: NO adopted rule defines WHO/WHAT determines "unable to function" or WHEN Inoperative begins, and the boundary between the two clocks turns on that undefined onset** |
| ⑦⑧ | halted period → `063` terminal — **unless** Inoperative began, which pauses it (`060`) — same gap |
| ⑨ | yes, by filling (`056`) |
| ⑩ | no |
| ⑪ | org authority (external; wall `EM-OPEN-066` recorded, not reopened) |
| ⑫ | halt, unavailability, filling or its absence, expiry |
| ⑬ | filled → gate · unfilled → terminal (`063`) or cancelled (`058`) — **selection between them is N-4**; interval state KNOWN `095` |

### S-07 · 🔴 NEW (N-5) — accept cast, member resigns, seat filled mid-gate
Vacancy filled; **"does not alter any decision already made"** (`EM-GOV-056`). **`UNRESOLVED` → N-5: is an individual's cast Committee Vote a "decision already made"?** Reading (a): vote stands, replacement's seat has voted. Reading (b): the vote lapsed with membership; replacement casts anew. **Opposite arithmetic in close gates; neither reading excluded by adopted text.** Denominator unchanged either way (`057`).

### S-08 · 🟢 — Committee unrestored → end of chain
Unable to function + authority cannot restore → **Election Inoperative** (`EM-GOV-058`) → halted clock pauses, restoration clock runs (`060`/`062`, parameter `059`(a)) → period expires unrestored → **"the election shall be cancelled"** (`058`). Deterministic. *(Vocabulary: election-level "cancelled" — KNOWN `EM-OPEN-110`. Onset gap N-4 noted but scenario stipulates the condition obtains.)*

### S-09 · 🟢 — restored in time, earlier halt outstanding
Committee restored → election resumes **at the unresolved gate** (`EM-GOV-059`(c)) → halted clock **resumes with its remainder** (`060`), no fresh time (`061`), each clock only under its condition (`062`).

### S-10 · 🟢 — halt, governed reschedule, document arrives
Halt recorded with reason (`011`) → Chief initiates (`014` P1) → governed reschedule = **new opportunity** (`013`, `EM-VOC-005`); superseded opportunity keeps its published schedule and records (`EM-VOC-004`, `P-2H`, D-2); nothing carries over (Reading A) → conditions met → progression.

### S-11 · 🟢 — Chief does nothing; period expires
Halted clock (`062`) → expiry without recovery → **terminal election-level state; protocol records expiry + failed recovery + state** (`EM-GOV-063`). **For the Chief: NOTHING — expressly no misconduct finding, no sanction, no removal authority (`063` ¶2; `EM-OPEN-049` wall recorded).** **For the Deputy: NOTHING — no transfer (`063` ¶2); `EM-GOV-008` is operational publication authority only.** *(State unnamed — KNOWN `110`.)*

### S-12 · 🟢 — three diligent reschedules, same failure, expiry mid-attempt
Each reschedule = new opportunity (`VOC-005`); **no failure creates fresh time** (`EM-GOV-061`); clock never paused (election stayed operative+halted, `062`) → expiry → `063`. **Diligence changes nothing — by design; matches the diligent-but-futile cell.**

### S-13 · 🟢 — zero approved candidates; progression requested
Floors fail at the Chief's request: **≥1 approved candidate** (`EM-VOT-002`), restated + voter floor (`EM-VOT-003`), evaluated at request (`EM-VOT-004`) → refusal recorded with reason (`P-2H`/`F-PROTO-1`) → halted (`011`). Recovery: producing an approved candidate requires candidacy activity; candidacy **completed**; ⛔ lifecycle may not return to a completed phase (`SCB-4` ruled half) → no governed act supplies the condition → halt persists → clock → **`063` terminal.** **Deterministic end-to-end — the corpus's former C-3 hole is closed by `063`.**

### S-14 · 🟢 — external authority never appoints
No Deputy/Committee (`EM-GOV-028`) → Election Appointment cannot complete → *no appointed Chief and Deputy ⇒ no Administration* (`EM-GOV-026`) → halted (`011`) → Chief may only coordinate (`028`) → clock (`062`) → **`063` terminal.** Walls `EM-OPEN-049`/`066` recorded, not reopened. **The election cannot be constituted by anyone else — and now ends deterministically instead of waiting forever.**

### S-15 · 🟢 — "long inaction ⇒ impossible ⇒ end it now"
**No adopted rule gives the assertion any effect.** No classification mechanism exists in the corpus (`C-1/2/3` remain PROPOSED); no actor holds a declare-impossible power; the only terminal routes are `063` (clock, reason-blind) and `058` (Committee restoration failure). **Inaction cannot be converted into impossibility — vacuously safe today; becomes a live requirement only if a classification rule is ever adopted.**

### S-16 · 🟢 — mid-election window correction
Governed correction = **new opportunity** (`EM-VOC-005`); the superseded opportunity keeps its own published schedule permanently (D-2) and its lifecycle record (`EM-VOC-004`, `P-2H`); decisions do not carry (Reading A, `EM-OPEN-022`); version-binding holds.

### S-17 · 🟢 — halted at Administration; Candidacy window passes
**Candidacy was never reached ⇒ not started, failed, expired, cancelled or superseded** (`EM-GOV-011` negative clause). Its planned window remains a plan that never activated (`EM-GOV-010`).

### S-18 · 🟢 — Committee seat filled during representative freeze
`EM-GOV-046` freezes **the representative configuration** — its text reaches selection/replacement of representatives only. `EM-GOV-056` fills a **Committee seat**. Different subjects; no adopted text collides; consistent with *recovery restores exercise, not shape* (`EM-GOV-049` rule).

### S-19 · 🔴 NEW (N-6) — unfillable vacancy; remaining 2 both accept
Arithmetic: denominator 3 (`057`), threshold 2, accepts 2 → **passes**. **But `EM-GOV-033`: the Committee *"shall consist of at least three eligible, independent members."*** **`UNRESOLVED` → N-6: constitution-time requirement (satisfied — 3 were constituted) or CONTINUOUS operating requirement (violated — only 2 exist)?** The readings yield **opposite outcomes** (gate passes vs Committee may not act). **Near-⚫, and why it is not ⚫:** no adopted rule *forces* the continuous reading, so this is silence about `033`'s temporal scope, not two rules commanding incompatible things.

### S-20 · 🔴 KNOWN (`EM-OPEN-077`) — configuration at Application
Model+threshold selected at Application and recorded as a RULE, not a number (`EM-GOV-032`/`035`; `TWO_THIRDS_OF_COMMITTEE_VOTES` `036`, ⌈⌉ `038`). **But the PERMITTED MENU from which selection happens is itself a governance artifact that no adopted rule yet supplies** — exactly `032`'s own recorded flag → **KNOWN, `EM-OPEN-077`.**

---

## Findings register

| # | Finding | Surfaced by |
|---|---|---|
| **N-1** | No adopted rule names the progression-request actor (or whether a request is required) for any transition except voting entry | S-01 |
| **N-2** | No adopted rule provides ANY recovery or re-decision path for an OBJECTION-based gate failure — the only exit is `063` expiry | S-04 |
| **N-3** | `EM-GOV-056` collapses *temporarily unavailable* into *vacancy to fill*; no Committee-side five-state discipline; returning member vs installed replacement unaddressed | S-05 |
| **N-4** | No rule defines who/what determines *"unable to function"* or the ONSET of Election Inoperative; the boundary between the halted clock and the restoration clock turns on that undefined onset | S-06 |
| **N-5** | Whether a departed member's already-cast Committee Vote survives seat-filling is undefined (two readings of `056`'s "decision already made", opposite arithmetic) | S-07 |
| **N-6** | `EM-GOV-033`'s temporal scope undefined — constitution-time vs continuous; opposite outcomes; **near-⚫** | S-19 |
| **N-7** | Gate 1's POSITION in the lifecycle chain is not fixed by adopted text (two gates adopted; Gate 2 = post-counting, `016`) | S-01 |

**KNOWN mappings used:** `EM-OPEN-053` (no time bound on an undecided gate) · `095` (gate-interval state) · `110` (terminal-state name) · `077` (permitted menu) · walls `049`/`066` (recorded, untouched).

## Completeness note (no silent caps)

**The frozen suite lacks a "gate open, Committee simply never decides" scenario** — the undecided-forever case, distinct from S-04's refusal. It maps to KNOWN `EM-OPEN-053`. **The suite stays frozen; the omission is recorded for the next round rather than repaired mid-pass.**

## What happens next (per the assignment — nothing here is self-executed)

**① Verification's blind pass** over the same frozen inputs (own grant + START). **② Comparison** — divergence is itself a finding. **③ PO disposition** of N-1…N-7 (each a candidate new Governance work item; ⛔ this report resolves none of them). **Model A qualification verdict comes AFTER comparison, not from this pass alone.**
