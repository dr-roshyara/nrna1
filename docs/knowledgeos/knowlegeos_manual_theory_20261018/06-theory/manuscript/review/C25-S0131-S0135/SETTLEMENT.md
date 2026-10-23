# Settlement of window C25 (S0131–S0135)

*2026-10-23. By the Professor. One window reviewer W (Opus): a blind inventory first, then steps 2–3.*

## Reports, saved verbatim, with their sha256 at hand-back

| Report | File | sha256 at receipt |
|---|---|---|
| Step 1, blind inventory | `STEP1-INVENTORY.md` | `679d4c6baa7e4e3697228ec566b3b56d06159564e9f76cb33e27744d30ab1b59` (the reviewer re-checked it against its own text) |
| Steps 2–3, review report | `REVIEW-REPORT.md` | `43812221f92e681ad9b77b134917c87ccca9b85b0336ec33d73ceab53d69a2de` |

## 0. Deliverable status

**Met after settlement.**
- **Findings:** W-01 to W-17. One is critical by rule (W-02, R5 F4), five are high (W-01, W-03 to W-06), and the rest are normal. All are applied.
- **Concepts:** CON-0239–0243 are restated or scoped and stay PROVISIONAL. CON-0244 is new, from W-06. CON-0206 is completed (I-11).
- **Model:** unchanged (v15).

## 1. Findings and their disposition

| W | Disposition | Where |
|---|---|---|
| 01 | **Accepted; the Professor's error, introduced in the adversarial pass.** §9.16 R2 takes the set of recorders and the weakest status. The draft sentence is replaced. The point that a migration replaces copies, and does not take their union, is kept | §8.18 C2; CON-0240; step-8 annotation |
| 02 | Accepted: a fixed decoder is a premise, linked to C12, C15 and K7. *Severity:* critical by the rule (R5 F4), which the Professor applies as written. The Professor's note, for review: the premise is that one function is applied to equal inputs, and no dependants exist, so its cost is one stated premise | §8.18 C2; CON-0240 |
| 03 | Accepted: the replica counterexample, "outside D", and "sufficient, not presupposed" for K2. The self-contradicting sentence is replaced | §8.18 C1; CON-0239 |
| 04 | Accepted: scoped to laws a merge cannot violate; the fork is a K2 failure; R2's setting holds only by analogy | §8.18 C3; CON-0241 |
| 05 | Accepted: the "third shape" was a strawman, replaced by the mirror case | §8.18 C4 (c) |
| 06 | Accepted: **C24** (two lifecycles, two locations), CON-0244. THEORY-MAP's "not yet in the book" item closed | §8.18 C24; map |
| 07 | Accepted: the test cannot be met by "preserve both" | §8.18 C4 (a) |
| 08 | Accepted as C4 (d) | §8.18 C4 (d) |
| 09 | Accepted: the list is the commission's; independence of authorship vs of stake | §8.18 C10, C21; CON-0242 |
| 10 | Accepted: registrar text relabelled | §8.18 C17, C19 |
| 11 | Accepted: the speaker noted; the misattribution added to the S0134 lineage | §8.18 C12; S0134 lineage |
| 12 | Accepted as an open, pending item | §8.18 C5; §8.18.3 |
| 13 | Accepted as a note | §8.18 C1 |
| 14 | Accepted: an attack added; `C-2` marked "as reported" | §8.18 C18 |
| 15 | Accepted: "converse" removed | register CON-0206 |
| 16 | Accepted: position S0135 → next S0136; counts 134; register 244 | THEORY-MAP §9 |
| 17 | Accepted as pending S0300 | §8.18.3 |
| CON-0243 (verdicts) | Accepted: marked a corollary of K1; hash clause corrected; I-07 scope | §8.18 C20; CON-0243 |
| C22 inline pending | Accepted | §8.18 C22 |

## 2. Verdicts

| CON | Before | After |
|---|---|---|
| 0239 | PROVISIONAL | PROVISIONAL, restated |
| 0240 | PROVISIONAL | PROVISIONAL, restated (premise added, R2 corrected) |
| 0241 | PROVISIONAL | PROVISIONAL, scoped |
| 0242 | PROVISIONAL | PROVISIONAL, restated |
| 0243 | PROVISIONAL | PROVISIONAL, a corollary of K1 |
| 0244 | — | PROVISIONAL (new, W-06) |

## 3. Depth, timing, practice

- **Depth:** the reviewer and the Professor agree, FULL on all five. The reviewer would have skimmed S0132 L341–706; it judged the Professor's full reading justified, since it produced C9.
- **Timing:** the per-source times (S0131 174 s, S0132 79 s, S0133 71 s, S0134 60 s, S0135 47 s) are from opening to close. The adversarial pass and the settlement came after the closes and are not included in them (the reviewer's observation, recorded).
- **The C25 practice.** The lineage passes held, except the misattribution in S0134 L30 (W-11). Of the source-first attacks:
  - C4 (a), C4 (b), C9 and C17 were judged adequate;
  - C4 (c) was a strawman (W-05);
  - C10 and C20 had not been compared with S0133 L38 and with K1's wording.
- **The Professor's adversarial pass on the book's own claims introduced one error (W-01) and caught one (C17).** For the next window, a pass that reads a definition must quote the rule it then relies on, not only the definition.
- **R4:** no proof-step tables were supplied, so the reviewer's derivation judgements were "not assessed". The reviewer's appendix supplies tables for CON-0240, 0241 and 0243, and their findings are applied above.

## 4. Spot-check of the previous settlement

Ten rows of `CONSOLIDATION-S0101-S0130/SETTLEMENT.md` §2 were checked. Nine held. I-11 had not reached the register (W-15) and is now fixed.

## 5. Checks

- **Quote check:** §8.18 has 0 not found. The whole book has 3, the baseline.
- **R1:** 220 rows with a home, 1 inherited flag.
- **Register:** 244 concepts (40 VALIDATED, 110 PROVISIONAL, 94 INVALID).

## 6. Open items

| Item | Role | Next action |
|---|---|---|
| the record's identity; the anchor's cut; the interpreter recorded; D's integrity over unread fields (§8.18.3) | Professor | for a theory turn, when the Principal schedules one; no kernel change in windows |
| pending S0300 (C4 a, b, d; C14; C22; W-17) | Professor | settle at S0300 |
| the design's acceptance (`ae451db9`); the migration's subject (W-12) | Professor | settle at a later registration |
| reading | Professor | C26 (S0136–S0140) |
