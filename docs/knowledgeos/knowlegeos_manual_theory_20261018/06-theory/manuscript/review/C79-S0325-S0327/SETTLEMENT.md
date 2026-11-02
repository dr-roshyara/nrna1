# Window C79 (S0325–S0327): settlement

*2026-11-02. Section: §8.72 (v2). Review: `REVIEW-REPORT.md` (sha256 in `SHA256-REVIEW.txt`), W-01–W-15. 0 critical, 9 high. Every finding accepted.*

## Theory first

- **§8.71.3's V-number question is decided from allowed text** (C1, W-13): S0325's complete file list and S0324's matching content show correction text in S0324 §5, §9, §11 under the producer's unchanged header and "self-verified" status. The git-history evidence held at C78 stays unused. Whether an edited document must be attributed as edited is normative and the Principal's (W-12).
- **V-1 is a reporting fix** (C2, W-02): G-3's value is the same before and after for every lane. The "=ACTIVE" definition is the producer's (S0324 L87); the fix carried that homonym into the code (W-01). Whether it meets the commission's S0320 L42 depends on a reading the PO/ARB or a verifier must choose; no verification took place (W-03).
- **ACTIVE certifies a past human START only** (C2, W-04): CONTINUATION also enters ACTIVE; on a Governance CONTINUATION a lane is ACTIVE with no human act since it stopped. R3 on §8.71 C5.
- **The end of the author's work has no counterpart in the record** (C4, W-06): the commission's STOP and the store's STOPPED are homonyms; COMPLETED and a HANDOFF are rival transitions; ownership is not in the window. The store still gives `authorized_to_act = true`, confirmed by a second process (W-07).
- **The commission's sequence has no verifier lane** (C5, W-05): the asymmetry between the author's lane and the verifier's is the PO/ARB commission's as registered.

## Dispositions

| W | Sev | Disposition | Where |
|---|---|---|---|
| W-01 | high | accepted: the homonym is the producer's; the fix carried it into the code; the C2 rival answered "agreed" | C2; §8.72.4; plain language 2; index |
| W-02 | high | accepted: no gate value changes; a reporting fix; "safe" vacuous | C2 |
| W-03 | high | accepted: S0320 L42, two readings; L64 noted (checked: S0320 L42, L64) | C2 |
| W-04 | high | accepted: R3 on §8.71 C5 states the weaker premise, the past-START limit and the Governance-CONTINUATION reading as open | C2; §8.71 C5 (R3) |
| W-05 | high | accepted: the missing verifier lane placed in the commission's sequence (checked: S0320 L76, L89); §8.71.3 citation removed | C5; index |
| W-06 | high | accepted: STOP ≠ STOPPED; rival transitions; ownership not in the window; normative point for the Principal or the PO/ARB | C4; title; plain language 4; index |
| W-07 | normal | accepted: S0327 L25, L55 and S0324 L106 added; S0315 L168 dropped | C4 |
| W-08 | normal | accepted: "the only check reported" | C3; title; index |
| W-09 | normal | accepted: S0320 L3, L20, L101 cited; the appointee the process named to append REGISTER | C3 |
| W-10 | normal | accepted: nine conditions; no. 9 dropped by S0325 (checked: S0320 L48–56) | §8.72.3 |
| W-11 | normal | accepted: H1′ at §9.8; §8.50 C3 "its own adoption" | homes; C1 |
| W-12 | normal | accepted: normative, the Principal's; the code point added | C1 |
| W-13 | normal | accepted: content as the evidence; "=ACTIVE" excluded | C1; §8.71 R3 notes |
| W-14 | normal | accepted: independence vs authorization; condition 8's store | C5 |
| W-15 | normal | accepted: read purity by grep; the line slip | §8.72.3 |

## R3

- **§8.71 lineage line and §8.71.3 (V-numbers):** R3 notes added (decided from S0325's text; "=ACTIVE" definitions not shown to be correction text).
- **§8.71 C5 (an act read from a state):** R3 note added (premise false as written; weaker premise; past START only; Governance-CONTINUATION reading open).

## Matters for the Principal (recorded, not decided)

- Whether an edited rule document must be attributed as edited (C1, W-12).
- Which transition should record the end of an author's work, and who owns it (C4, W-06).
- Whether the V-1 fix meets S0320 L42 (C2, W-03); for the PO/ARB or a verifier.
- The Governance-CONTINUATION reading (§8.71 C5 R3, W-04).

## Access notes

W disclosed a first scratch-copy command that included `rm -rf` and was refused by the harness; its scratch copy is a directory (`c79-w-copy`), not a `.txt` file. No git command was run; nothing outside the brief was opened.

## Measures

- 15 findings · 0 critical · 9 high · material omissions 4 (W-03, W-04, W-10, W-14).
