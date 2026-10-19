# Settlement of review C18 (S0097–S0100; the §1.14 integration, step 2)

*2026-10-19. One reviewer, one commission and one step-2 message. Harness time: step 1 144 s, steps 2–3 562 s, total 706 s. The last window of the run to S0100.*

**Re-checked in the sources and git:** S0100 L10, L22, L61, L68, L78, L88, L90, L105, L127, L128, L130–135, L140, L142; S0099 L43–47, L76–80, L109; S0097 L21, L27–31, L41, L53; git: Correction #3 committed 2026-08-19 13:25 (`c38396244`). All hold. **All 11 findings and the 8 appendix items are accepted.**

**The critical finding (1)** refutes §8.11 C1's headline ("self-declared identity failed in both directions"). It is settled from the evidence: every self-declaration in the case was true; the source judges the false-bar instance wrong; the second direction presupposes the open question C2. C1 is restated as the misattribution of an act in inherited context. It had spread into §1.14.3-2's further evidence, which is corrected in the same settlement. Nothing else depends on it (dependency graph: §8.11, §1.14).

## Measures
| Measure | Value |
|---|---|
| New concepts | 3 (CON-0157–0159) |
| Findings | 11: 1 critical · 10 major; appendix 8 minor |
| INVALID share | 2 of 3 concepts INVALID as stated (CON-0157, CON-0159); C2's Int INVALID as stated |
| Material omissions | 5 (findings 4, 5, 10, 11; appendix 18–19) |
| Reviewer time | 706 s |
| §1.14 integration | meaning kept for 38 of 47 claims; 4 major and 5 minor repaired; tags 0, INVALID 0, numbers kept; 12140 → 12744 words (diagnostic) |

## Changes
| Finding | Change |
|---|---|
| 1 | §8.11 plain language and C1; CON-0157 INVALID as stated, restated |
| 2 | §1.14.3-2 further evidence restated |
| 3, 11 | §8.11 C2's Int: identifiers do not track working context; F32 option "inherited working context", apart from information lineage; C3 scoped to direct uptake, Hyp |
| 4 | §8.11 C4: an invariant with no enforcer; conduct vs premise (S0100 L88) |
| 5 | §8.11 C5 time-indexed (git `c38396244`); C6's counter-instance (a verifier produced the correction for its own findings) |
| 6 | §8.11 C7: the erratum says "stands" and restores nothing; truth vs standing |
| 7 | §8.11 C6 and CON-0159: implicit adoption, not a decision; §1.14.5-1's related evidence as Int |
| 8 | §1.14.1-7 head and body conditional |
| 9 | §1.14.2-1: two-sided further evidence, moved from 1.14.2-2 |
| 10 | §8.11 C1: identifiers are not episodes (resumed sessions) |
| appendix 12–16 | §1.14 heads keep their conditions; status-box pointer; 1.14.5-14 sentence repaired; "My answer"; lineage Int label |
| appendix 17–19 | §8.11 C1 (transcripts keyed by identifier; timing corroborates); C8 (S0098 L337, its model defects); S0097 L21, L53 |

**Settlement self-check:** quote check §8.11 16/0, §1.14 14/0; `integration_check.py` on §1.14: tags 0, INVALID 0, numbers kept.
**Dependency graph (§12d):** CON-0157 → §8.11, §1.14 (via 1.14.3-2, corrected); CON-0159 → §8.11, §1.14.5-1 (corrected).
