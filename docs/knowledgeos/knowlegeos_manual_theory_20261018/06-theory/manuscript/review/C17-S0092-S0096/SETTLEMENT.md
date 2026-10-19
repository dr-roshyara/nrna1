# Settlement of review C17 (S0092–S0096; the §1.13 integration; the TT3 and C16 settlement changes)

*2026-10-19. One reviewer, one commission and one step-2 message. Harness time: step 1 178 s, steps 2–3 329 s, total 507 s.*

**Re-checked in the sources and git:** S0093 L63, L68, L77–79; S0094 L19, L41, L104; S0095 L13, L71, L81, L83, L99, L110, L120, L149, L179, L193, L235; S0096 L18, L43; S0092 L13–15, L35, L83, L85, L90, L153; S0129 L96, L102; git: S0092 first committed 2026-08-28 (`f24edf316`). All hold. **All 17 findings are accepted.**

## Measures
| Measure | Value |
|---|---|
| New concepts | 4 (CON-0153–0156) |
| Findings | 17: 0 critical · 8 major · 9 minor |
| Findings on changed theory (step 9) | 3 (findings 8, 12, 13): §8.9 C2's pool; S0129's anchor and status in §9.8; inline revision notes in §8.9, §5.7 |
| INVALID share | 1 of 4 concepts INVALID as stated (CON-0155); C3's Int INVALID as stated; two VALIDATED (CON-0153 as a distinction, CON-0156 scoped) |
| Material omissions | 4 (findings 3, 6, 7, 14) |
| Reviewer time | 507 s |
| §1.13 integration | meaning kept for 8 of 10 claims; 1.13.1-2 and 1.13.1-3 made two-sided; book form yes; tags 0, INVALID markers 0; 3184 → 3475 words (diagnostic) |

## Changes
| Finding | Change |
|---|---|
| 1 | §8.10 C3: one direction; an analogy, not the boundary test (existence ≠ necessity; category ≠ kernel placement); open question restated; CON-0153 |
| 2 | §8.10 C5: one provenance route plus a self-declaration; transcripts assumed intact; the trace ended at an identifier already on the bar; S0092's L85/L90 overreach noted; CON-0155 |
| 3, 6, 7, 14 | new §8.10 C10: the START's time; the DDD order changed silently (with §5.7 C4); a reachable stuck state, fork F33; S0095's further points; C-14 and capability identity; three kinds of authority |
| 4, 5 | §1.13.1-2, §1.13.1-3: two-sided evidence (S0095 L149; S0090 L30 as a challenge); Int labels |
| 8 | §8.9 C2: non-increasing over a fixed population; grows on entry (S0092) |
| 9 | §8.10 C9: the source slip ("two absent, one untracked") |
| 10 | §8.10 C2: Hist(c, x, t, s, u); the FALSE / superseded homonym |
| 11 | §1.13.2-3 point 5: Ded restored; §9.8 cited exactly |
| 12 | §9.8 M5 and result 6, §1.13.2-3: S0129 L102, "still RECOMMENDATION, not DECIDED" (L96) |
| 13 | inline revision notes removed from §5.7 (11) and §8.9 (5) |
| 15 | §8.10 lineage: where the record lives |
| 16 | §8.10 C4: "not standing" a Hyp; seven vs nine stages |
| 17 | §8.10 C10: "seven" exclusions as a source slip |

**Settlement self-check:** quote check §8.10 29/0, §1.13 6/0, §9.8 16/0, §8.9 37/0; `integration_check.py` on §1.13: tags 0, INVALID 0, numbers kept. **Known remaining form debt (not fixed here):** older sections written in this run (§6.5–§6.7, §8.6–§8.8, §9.6–§9.8) still carry inline notes such as "restated after review …"; the labels rule applies to them too. Recorded for the whole-book review rather than edited section by section now.
**Dependency graph (§12d):** CON-0155 → §8.10; CON-0153 → §8.10; §8.9 C2 → §8.10 C5 (consistent); nothing else reopened.
**Next review (step 9):** the TT4 brief lists this settlement's changes as changed theory.
