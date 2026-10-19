# Window C18: step 8, strength scan, book search; §1.14 integration checks

*2026-10-19. Sources: S0097, S0098, S0099, S0100 (in order, full). Four sources: the index shifted by one because S0082 is not on the allowed list, and the run stops at S0100. New: Ch. 8 §8.11 (C1–C8), register CON-0157–0159. Step 2 (integration) of Ch. 1 §1.14 under Rulebook §4, entry 52a.*

## Book search (top hits read)
| Query | Hits read | Relation |
|---|---|---|
| "continuation", "compaction" | §8.6.2 (factors: process lineage, working context); §6.5 C2 | §8.6.2 the home (C1, C2) |
| meaning: a continued process inherits context; same actor for a bar? | CON-0125, CON-0117, CON-0127; §8.6.2; §0.7; §8.8 C4 | §8.6 extends; the rest different |
| "session identifier" | §8.10 C5 | extends (C1) |
| "relay", "absorbed" | §1.11; §0.5 | different |

## Step 8: dependencies of §8.11
| Relies on | Status | Check |
|---|---|---|
| §8.6.2 factors (CON-0125 PROVISIONAL) | **unverified** | C1, C2 extend; nothing changed in §8.6 |
| §8.9 C2 (CON-0151, restated), C10; F32 | PROVISIONAL | C2 adds an option to F32; C7 fits the restated pool |
| §8.10 C5 (CON-0155 restated), C6, C1 | PROVISIONAL | C1, C8 |
| §1.14.3 role laws; §1.14.5 | integrated in this window | C3, C6 |
| §9.8 H4 | PROVISIONAL | C4 |
| §5.7 C1 (CON-0144 restated) | PROVISIONAL | C8 supports |

## Strength scan (§8.11)
Strong words are inside quotes or scoped ("one case", "two instances, one line", "one source"). Quotes 16/16 after adding S0098 line anchors; paraphrases marked "in meaning" checked by hand.

## §1.14 step 2 (integration), formatting checks (`tmp/tools/integration_check.py`, now locating "What does not hold" by title)
- revision tags in the prose: **0**; INVALID markers outside "what does not hold": **0** (four inline concept tags removed; the concepts stay listed in 1.14.7);
- claim numbers kept: **yes** (47);
- length: 12140 → 12553 words (diagnostic: the growth is the further evidence in 1.14.1-7, 1.14.2-2, 1.14.3-2, 1.14.3-3, 1.14.5-1 and the restated heads);
- quotes 14/14.
- Old text: `01.14-OLD-before-integration.md`; old and new heads by claim: `01.14-HEADS-OLD-NEW.md`; change-log row: `chapter_01/01.99-change-log.md`, last row.

**What changed beyond the heads (for the reviewer):**
- 1.14.1-4: the "Corrected 2026-10-18" box is folded into the argument (why the grant is evaluated at the act time); the refuted first formula is in 1.14.7.
- 1.14.1-7: the status box loses "in baseline v1 (2026-10-18)" and the correction-register pointer; a "further evidence (challenging)" paragraph from §9.7 (CE not yet a function; the estate's per-record sequence; F26).
- 1.14.2-2, 1.14.3-2, 1.14.3-3, 1.14.5-1: further evidence, each marked supporting, challenging or related, status unchanged.
- 1.14.5-13: "the readings include (corrected RC-34a …)" → "the readings include at least four".
- Everywhere: revision IDs (RC-…, review C…/F-…) removed; "my" made impersonal; four INVALID concept tags removed from the body.
