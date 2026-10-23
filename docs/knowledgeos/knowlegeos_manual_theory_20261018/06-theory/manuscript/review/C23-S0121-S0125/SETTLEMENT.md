# Settlement of window C23 (S0121–S0125)

*2026-10-23. By the Professor. One window reviewer, W (Opus), normal window form. Object: §8.16 (no kernel section changed in this window).*

## Reports, saved verbatim, with their sha256 at hand-back

| Report | File | sha256 at receipt |
|---|---|---|
| W, step 1 | `STEP1-INVENTORY.md` | `05871bcdb7a295b336d5df843f560ec21fe077d78efb8b4de78d59420aacad10` |
| W, steps 2–3 | `REVIEW-REPORT.md` | `4d5ed242550e7f360826e0f975fd9a6e9bf56e5773c207a4d8cc7e4f3cc3ef79` |

## 0. Deliverable status

**Met.**
- S0121–S0125 were read in order: S0121, S0122 and S0124 at depth full; S0123 and S0125 at depth skim.
- §8.16 is settled as v2:
  - CON-0227 is INVALID as stated, restated as CON-0230;
  - CON-0226 is VALIDATED (scoped);
  - CON-0228 and CON-0229 are provisional, qualified;
  - CON-0222's C23 extension is rewritten in the authority's words.
- §8.15 C2's pointer is amended again.

**The review:** 10 findings (5 high, 5 normal), none critical, and 6 coverage gaps. No question for the Principal.

## 1. Measures (per source; time = opening to close after writing; the clock calibrated at C23)

| Source | Lines | Time | Depth (Professor / reviewer) | Professor's findings | Review findings bearing on it |
|---|---|---|---|---|---|
| S0121 | 136 | 59 s | full / full (borderline) | 2 | W-7, W-8, W-10; coverage |
| S0122 | 228 | 40 s | full / full | 4 | W-1, W-2, W-3, W-5, W-6; coverage |
| S0123 | 49 | 14 s | skim / skim | 0 new | — (nothing lost) |
| S0124 | 108 | 23 s | full / full (light) | 2 | W-9 |
| S0125 | 74 | 13 s | **skim / skim, but the skim lost theory** | 0 new (wrong) | W-3, W-4 |

- **The §3 safeguard worked, and was weakened.**
  - It worked: read closely, S0125 holds the authority's wording, which differs from the producer's gloss. The skim had missed it, and it is now in §8.16 C4.
  - It was weakened: a commit subject naming the skimmed sources reached the reviewer before its blind step, so its depth calls on S0123 and S0125 were not blind.
  - *Rule from now on:* no depth choices in commit subjects or other visible text before the blind step is saved.
- **The READ-LOG row for S0125 stays "skim"** (append-only). This settlement and §8.16 record that the depth was misjudged.
- **Reviewer time** (harness): step 1, 126 s; steps 2–3, 358 s.

## 2. Findings and their disposition

| W | Sev. | Decision and correction | Holds? |
|---|---|---|---|
| W-1 | high | Accepted, verified by the Professor on S0122's ⑤ lines: semantic = invariant on every listed field. CON-0227 INVALID as stated; CON-0230: three roles shown distinct, semantic vs invariant undecided; "four answers" listed as a source defect | **yes** |
| W-2 | high | Accepted. The delivery timestamp separates fact owner from record owner; sufficiency fails on the source's own field | **yes** |
| W-3 | high | Accepted. C4 rewritten with the authority's words first (S0125 L21, L22, L24); "world fact" labelled as the producer's and registrar's gloss; CON-0222 rewritten. **The same error type as C22 W-3 recurred: recorded as a pattern of the Professor's, to watch** | **yes** |
| W-4 | high | Accepted. Qualification 1 answered as intent; qualification 2 open | **yes** |
| W-5 | high | Accepted. The recorder field is the opposite strategy to S0122's forbidden actor field; an open tension, not an analogue | **yes** |
| W-6 | normal | Accepted. No referent gap; the open gap is the supersession trigger | **yes** |
| W-7 | normal | Accepted. The release was legitimate and recorded by Governance; the case is not evidence against the rival rule. §8.15 C2 amended again | **yes** |
| W-8 | normal | Accepted. Three senses of "owner" qualified; the "R4a is split ownership" Int withdrawn | **yes** |
| W-9 | normal | Accepted. The source's own breach (S0124 L92 vs L104) recorded; "not a basis" labelled as D's convention | **yes** |
| W-10 | normal | Accepted. The source's premise about finished lanes marked | **yes** |

**Coverage:**
- *Added:* the receipt's speech act, as an open fork.
- *Listed as coverage in §8.16.3, not yet theory:*
  - naming as boundary protection;
  - conjunctive eligibility not monotone under relief;
  - universal absence claims drawn from search;
  - role eligibility.

**Source defects:** S0122 L26 and L142; S0123 L35; S0124 L92 vs L104; S0124 L100 vs S0125 L66.

**C22 rows spot-checked by W:** W-8 and W-14 met.

**THEORY-MAP:** the Position section is updated, with the new open forks.

**Verdicts:**

| Concept | Verdict |
|---|---|
| CON-0226 | VALIDATED (scope: the engine's state semantics) |
| CON-0227 | INVALID as stated |
| CON-0230 | new, PROVISIONAL |
| CON-0228, CON-0229 | PROVISIONAL, qualified |
| CON-0222 | PROVISIONAL; its C23 extension rewritten |

## 3. Checks

- Quote check: §8.16 0 not found. Whole book: 14, the baseline.
- R1: 206 rows with a home, 1 inherited flag.
- No model run needed (no kernel change).

## 4. The Professor's own pattern, recorded

Twice now (C22 W-3, C23 W-3) a producer's or registrar's gloss was read as the authority's decision. **The check from now on:** for every claim about what an authority decided, quote the authority's own words first, and label every gloss with its author.

## 5. Open items

| Item | Role | Next action |
|---|---|---|
| the C22 settlement §4 questions (the work act as the use; R4d on availability) | **Principal** | decide |
| open forks of THEORY-MAP §9 (now with §8.15–§8.16's) | Professor / Principal | as sources bear on them |
| reading | Professor | C24 (S0126–S0130) |
