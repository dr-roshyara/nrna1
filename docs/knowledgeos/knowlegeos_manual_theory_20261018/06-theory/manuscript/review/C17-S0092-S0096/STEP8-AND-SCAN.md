# Window C17: step 8, strength scan, book search; §1.13 integration checks

*2026-10-19. Sources: S0092, S0093, S0094, S0096 (in order, full); S0095 (topic-full in TT3, checked against §9.8, §1.12, §1.13). New: Ch. 8 §8.10 (C1–C9), register CON-0153–0156. Step 2 (integration) of Ch. 1 §1.13 under Rulebook §4, entry 52a.*

## Book search (top hits read)
| Query | Hits read | Relation |
|---|---|---|
| "existence" | CON-0100 (§9.1 kernel existence); CON-0120; §1.6; §0.1; §1.9 | §9.1 different (existence of a kernel, not of a capability); §8.4 related |
| "category" | §3.3 (kinds, the category test); §0.3; CON-0008; §3.2 | different (knowledge kinds), not capability categories |
| "authorship" | §8.6 C1 (a new identifier is not a factor), §8.6.2; §1.16.8; §5.6 C6; §6.4 C1; §6.5 | §8.6 C1 extends (C5); the rest different |
| "superseded" | §1.14.1; §0.3; §1.2; §5.6 C6; §8.5 C8 | §8.5 C8 related; none states "superseded, not disproven" |
| "adoption" | §1.14.1; §1.14.5; §1.17.2; §9.7.3 | different (adoption of claims); §8.4 C4 is the home for C1 |
| "receipt" | §9.5 C4, C5 | extends (C4) |
| meaning: existence vs category | CON-0117 (§9.5), CON-0133, CON-0124, CON-0008, CON-0034 | none the same; home taken as RESEARCH-PURPOSE's three questions and §9.6–§9.7 |
| meaning: superseded, not disproven | CON-0124, CON-0133, CON-0014, CON-0139; §1.16.6 (pinning) | §1.16.6 extends (C2) |

## Step 8: dependencies of §8.10
| Relies on | Status | Check |
|---|---|---|
| §8.4 C4 (adopting act); A1 | reviewed | C1 extends |
| §9.8 state history (CON-0148 restated, PROVISIONAL) | **unverified** | C2 uses the definition only |
| RESEARCH-PURPOSE three questions; §9.6, §9.7 | Principal's text; PROVISIONAL | C3 reads S0096 as the same separation, Int |
| §9.5 C4; §6.6, §6.7 C6 homonym | PROVISIONAL | C4 extends |
| §8.6 C1 (CON-0125 PROVISIONAL); §8.9 C2, F32 (CON-0151 PROVISIONAL) | **unverified** | C5 is evidence for F32's relational option, one case; F32 stays open |
| §6.7 C5 (CON-0147) | PROVISIONAL | C8 supports |
| §5.7 C1, C2; §8.6 C3; §8.9 C5, C6, C10 | reviewed / PROVISIONAL | C9 instances |

## Strength scan (§8.10)
8 strong words in the book's own text; one restated before review ("only one of the three kinds is the dependency the law is about" → the law must say which kind). Quotes 28/28; the paraphrased quotes marked "in meaning" were checked by hand against their lines.

## §1.13 step 2 (integration), formatting checks (`tmp/tools/integration_check.py`)
- revision tags in the prose: **0**; INVALID markers outside "what does not hold": **0**; claim numbers kept: **yes** (10);
- length: 3184 → 3316 words (diagnostic: the growth is the further evidence in 1.13.1-2 and 1.13.1-3 and the restated heads);
- quotes 6/6. Old text: `01.13-OLD-before-integration.md` in this folder. Change-log row: `chapter_01/01.99-change-log.md`, last row.

**Old → new, by claim (for the reviewer):**
| No. | Old head | New head | What changed |
|---|---|---|---|
| 1.13.1-1 | The idea (Src, AMS L38–51) | Authority, as the system holds it, is a recorded relation to an external human act (Src) | head states the claim |
| 1.13.1-2 | Authority and its record: two valid concepts (C1; the sources point both ways) | Two valid concepts: the record constitutes authority, or represents it (C1; for the Principal) | further evidence S0095 L210, L216 (an act given, transcribed later), read as bearing on C1 without deciding it; "(final review, C1)" removed |
| 1.13.1-3 | Why the mechanism never holds authority (Src, AMS L214–226) | The mechanism never holds authority: it holds a reference (Src; classification Ded) | the unnumbered Classification paragraph folded in as the claim's classification; further evidence S0129 L147, S0090 L30 (supporting, same line) |
| 1.13.2-1 | I recounted the live record today | The recount of the live record (Ind) | first person removed |
| 1.13.2-2 | Limits of the check | The limit of the recount: the earlier state cannot be re-verified (a limit) | marked as a limit |
| 1.13.2-3 | What the recount adds (Ded) | same | "corrected after audit L-18", "My earlier 'rule of three' bound is withdrawn" moved to 1.13.99 / change log; "added after review C8" removed; a cross-reference to §9.8 |
| 1.13.3-1 | Development (Ded) | Delegation, rule authority and maturity (Ded) | "corrected after audit R-11", "extended after audit L-19" removed; point (c)'s sentence order repaired |
| 1.13.3-2 | The register is not the complete record of authorizations (S0014; revision RC-29) | same claim (Src, S0014; with a Ded consequence) | revision tags removed; the C15 anchor note reduced to the Int note; related evidence S0089 L81 |
| 1.13.3-3 | A forward minting rule beside two options for the past (… RC-33a …) | A forward minting rule stands beside two open options for the past (Int, from 1.13.3-4) | the head carried only a withdrawal note; now states the claim; the withdrawal moved to 1.13.99 |
| 1.13.3-4 | What the sources actually say | What the sources say, rule by rule (Src, with Int) | "revision RC-55" removed |
| "Source added" paragraph | — | folded into the sources line | AMS counts and Track B status kept |
