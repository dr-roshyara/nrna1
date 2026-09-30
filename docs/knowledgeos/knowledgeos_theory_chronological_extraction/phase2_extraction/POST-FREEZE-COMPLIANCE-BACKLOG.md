# Post-Freeze Compliance Backlog

| | |
|---|---|
| **Created** | on freezing **Research Architecture v1.0**, 2026-09-22 |
| **Purpose** | the work the frozen rules **create** — ⛔ *not* a reason to delay the freeze |
| **Principle** | ⭐ **Enforcement frozen ≠ compliance achieved.** A rule can be binding while the work it demands is unfinished |
| ⛔ **Not in scope** | new architecture · new gates · new layers. **The architecture's job is finished enough to govern the research** |

---

## 1 · Open compliance items

| # | Item | Gate | State | Work |
|---|---|---|---|---|
| **B-1** | ✅ **Theory Discovery Index** | `P1-Q1` | ⭐ **CLOSED — 30 of 30 indexed** | Retro-indexed F0001–F0025 by content; F0026–F0030 indexed on read. ⚠️ **`C2` is computable but NOT yet discriminating — all 30 read `true`.** A measure that has never returned `false` has not been shown to measure anything |
| **B-2** | ✅ **Relational completeness** | `Q59` | ⭐ **CLOSED and HELD — 30 of 30** | Closed at 23/23; **the 7 new objects carried relations at creation**, so growth did not reopen it |
| **B-3** | ✅ **Gap dispositions** | `Q57` | ⭐ **CLOSED — 13/13**, measured by `KOS-G-027` | ⭐ **Nine `WAITING` carry a VERIFIED path, not a promise** — every named dependency was confirmed present in the corpus before the disposition was written. `G-0010` came back **`PARTIALLY_RESOLVED`: its premise had gone stale** |
| ⭐ **B-8** | ⚠️ **`KOS-G-027`'s implementation is weaker than its rule** | `Q57` | **OPEN — new** | The gate's stated rule requires a disposition **with a named search or act**; the check tests only the **status token**. ⛔ **I did not strengthen it**: tuning the instrument to match work I had just written is the author grading himself. **Governance's call, with `KOS-G-060`** |
| ⛔ **B-9** | ⛔ **I called the Phase-2 protocol FROZEN. It is not.** | — | **OPEN — my error** | Its header reads **`PROPOSAL — not adopted · not frozen`**. What binds it is the **methodology freeze** (`CLAUDE.md`), a different instrument. ⚠️ **`KOS-G-061`'s purpose text repeats my error** — *"the Q-gate registry inside the frozen Phase-2 protocol"*. ⛔ **I did NOT correct it: that is an active gate definition, and Governance owns those.** The stale status column is still real; only the word *frozen* is wrong |
| ⛔ **B-10** | ⚠️ **`KOS-G-003` cannot tell USE from MENTION** | `KOS-G-003` | **OPEN — new** | An artifact that *explains* a bad identifier by naming it re-triggers the gate. ⭐ **Found by being blocked twice in a row.** ⛔ **I did NOT change the gate** — forbidden to the research session, and I am the party who benefits. ⚠️ **A fix is not obviously right:** a log that can never quote a bad id is limited, but a gate that accepts *"this id is fine, I'm only mentioning it"* is defeated by a sentence. **Governance's call** |
| ⛔ **B-12** | ⚠️ **The corpus's `G-4` has THREE meanings — and `H-3` was 'closed by `G-4`'** | `C-0010` · `T-0044` | **OPEN — ASSIGNED TO INDEPENDENT AUDIT; ⛔ NOT started by research** | *No second runtime adapter* · *No second adopting product* · *PRODUCT vs DEPLOYMENT*. ⭐ **If F0014's nesting closure cites the wrong one, the closure is spurious** — which would explain the three-way disagreement. ✅ **Corpus-testable.** ⛔ Hypothesis, not asserted |
| ⛔ **B-13** | ⛔ **ID divergence from `F0031` — canonical manifest never consulted** | `ID-ROOTCAUSE-01` | **OPEN — investigated, NOT repaired** | 9 of 10 ids wrong; 11 artifacts contaminated; canonical `F0035`/`F0040` **never read**. ⛔ **Remedy is the human's call** — relabelling alone is insufficient |
| ⚠️ **B-14** | ⚠️ **Canonical log: 18 paths (0.6 %) are space-truncated** | `evidence/` | **OPEN — recorded, not repaired** | Identity column **sound** (0 duplicate ids); **path** column defective for filenames containing spaces. ⭐ **Top-level region 51/51 clean**, so it does not touch `B-13`. ⛔ **Correcting the canonical log is a human act** |
| ⛔ **B-15** | ⛔ **Governance-separation question — research implemented governance-session work** | `RCI-011?` | **OPEN — recorded, NOT adjudicated** | The research session implemented evidence-binding (steps 3–6) that the governance plan assigns to the **governance session**. ⚠️ It was instructed in-session — ⛔ **recorded as a fact, not offered as justification.** ⭐ **The part that is mine: I did not check the allocation before acting** — the same shape as `F0031`. ⛔ **Classification belongs to governance** |
| ✅ **B-18** | ⭐ **Retroactive governance-interpretation hazard** | `SRE-Q1` | ⭐ **RESOLVED — L0 chose (a)** | A later clarification identified an ambiguity that existed at the time of five safe-window reads. ⭐ **The reads predated `SRE-Q1` and therefore did not violate it.** **Interpretation fixed forward:** *"`F0041+` progression"* means **chronological advancement of the main research sequence**, ⛔ not every out-of-sequence `F0041+` read. ⚠️ **Reading was permitted; ADMISSIBILITY of the findings is a separate, still-open question**
| ⛔ **B-16** | ⛔⛔ **Research commits sweep governance artifacts — RECURRED at n=3** | `COMMIT-BOUNDARY-FINDING-01` | **OPEN — ⛔ ESCALATED** | 8 artifacts, ~1,684 lines, across 3 commits. ⛔ **The fix I announced ("explicit paths") failed: I staged DIRECTORIES, which sweep untracked files exactly as `-A` does.** ⭐ **I inspected the stage and could not have caught it — my check showed paths, not tracked state.** ⛔ **I am not proposing the fix: two self-corrections produced a recurrence** |
| ✅ **B-17** | ⭐ **`H-C4` ambiguity — RESOLVED by rename** | `IDENTIFIER-RENAME-01` | ⭐ **CLOSED 2026-09-23** | L0-directed rename `H-C1…H-C7` → **`RC-H-01…RC-H-07`**. ⭐ **`RC-H-04` verified to have exactly ONE referent.** ⛔ The worktree's unrelated `H-C4` untouched; 15 occurrences preserved in two append-only records that document the collision as it was |
| ✅ **B-11** | ⭐ **Unknown activation id produced a silent `CLEAR`** | `gate-runner` | ⭐ **CLOSED 2026-09-23** | An id in `activated` naming no gate yielded `CLEAR` with `applicable_activated: 0` — ⛔ *"something was activated, but nothing was checked."* **Now `GOVERNANCE_INOPERATIVE`, exit 3.** ⭐ **A second door to the same danger was closed with it:** `CLEAR` now requires ≥1 *applicable* activated gate, so a stage/timing filter that excludes everything can no longer read as a pass. Proven, not asserted — the rule is a pure function with **3 self-test cases**; suite **28 → 31** |
| **B-4** | ✅ **Theory-document completeness** | `Q60` | ⭐ **20/20 — PASSING**, computed | Grew 11 → 20 and stayed closed. The check is a set difference and it ran |
| **B-5** | ⚠️ **Four partial orders — pointer only** | §13A.1 | content not recovered | Follow the 6-step recovery. ⛔ **Do not construct from the phrase** |
| **B-6** | ✅ **CAP-001 discrepancy** | — | ⭐ **CLOSED by F0030** | *"1 execution, 1 decision changed."* ⭐ **It was a time series, not a contradiction** — 0 at F0012, 1 by F0030 |
| ⭐ **B-7** | ⛔ **`H-3` nesting is a THREE-WAY disagreement** | `C-0010` | **OPEN — new** | `T-0022`/`SI-0005` depend on it. ✅ **Discriminator is corpus-findable:** does any artifact show one space **containing** another rather than sitting **beside** it? |

## 2 · Priority — by what each unblocks

> ### ⭐ ~~`B-1` first.~~ **`B-1`, `B-2`, `B-3`, `B-4`, `B-6` CLOSED. `B-7` is first; `B-5` and `B-8` follow.**

### ⭐ What dispositioning `B-3` actually found

⛔ **The ten gaps were not ten unknowns.** Every named dependency — `Round38C-04`, `Round47-OP`, `ES-003.1`, `CAP-001`, `RQ-002` — **exists in the corpus and was located**, including the exact file `Round38C-04_Principle_Form_Classification_Framework.md`.

> ⭐ **Not one gap was a genuine absence. All nine open ones are `WAITING` on reading that can actually be done.** ⚠️ **This is the `ACL-4` lesson a third time:** these were recorded as gaps at **batch scope** and carried for three passes as though they were corpus scope.

**Two findings came out of the exercise that a status stamp would have missed:**

| ⛔ | |
|---|---|
| **`G-0006` carries an identifier collision** | `R-36` names **both** a traversal record and an unrelated platform ruling about a deprecated hook. ⭐ **Same token, different referent** — and a string match would "resolve" the corpus's most contested question, *has the loop ever run*, with the wrong document |
| **`G-0010`'s premise had gone stale** | it claimed **none** of the counter's 11 occurrences sat in-window; F0030 (#6) and F0019 (#9/#10) now do. ⛔ **Nothing re-reads a gap when new evidence arrives, and no gate detects a stale premise** |

**Why `B-7`:** it is the only open item that **invalidates a recovered object** — `T-0022` rests on `H-3` being closed, and **two of three sources say it is not**. ✅ **And it is the only one whose discriminator is corpus-findable**, so reading answers it. *(⚠️ `B-3` is older, but an undispositioned gap misreports status; a contested object misreports **theory**.)*

⛔ **`B-5` is still deliberately not first.** It remains the most intellectually interesting and the easiest place to invent something.

### ⚠️ One closure to read carefully

⛔ **`B-1` closed as an ARTIFACT, not as a CAPABILITY.** The index exists and covers 30 of 30 — but **every entry reads `candidate_theory_bearing: true`**.

> ⭐ **A discriminator that has never discriminated is not yet evidence that detection works.** `C2` is computable; ⛔ it is not yet *validated*. **The first `false` entry — with a stated reason that survives review — is what would validate it.**

## 3 · What is NOT in this backlog

| ⛔ | Why |
|---|---|
| **`C1` corpus coverage (0.8 %)** | ⭐ **research execution progress, not a compliance defect.** No gate improves it. It is closed only by reading |
| **Candidate Theory completeness** | ✅ **not frozen, by design** — it is provisional and evolvable |
| **More architecture** | ⭐ the audit found **three** missing controls; three were added; the re-test passed. **Further layers would be over-engineering** |

## 4 · The distinction to preserve

```
ARCHITECTURAL ENFORCEMENT          CURRENT OBJECT STATE
   P1-Q1 · Q59 · Q60  exists          do the objects comply?
            ↓                                  ↓
          ✅ PASS                      ⛔ NOT YET COMPLETE
```

⛔ **These are different statuses and must never be reported as one.** The first is frozen. The second is this backlog.

---

*Created on the v1.0 freeze · 6 items · `B-4` passing · ⛔ no new architecture proposed.*
