# Planted-gap result: consolidation S0378–S0402

*2026-11-06T13:15:05Z. Scored by the Governance Engineer (session `57ddce41…`, mandate §8.5) after all five reports were saved with sha256 (A–D `821a3bb29`, integration `26a766124`; hashes re-verified). Key `PLANTED-GAP-ANSWER-KEY.md` (sha256 `c1c17fbd…`) and manifest (`f403a567…`) released into this folder.*

**Key custody [V]:** the machine rebooted on 2026-11-06 13:39 and cleared the scratchpad that held the sealed key and manifest. Both were rebuilt from the Governance Engineer's own session record and from the committed copy; **both reproduce the recorded sha256 exactly** (access record entry of 2026-11-03T08:02:06Z). Nobody else held them.

## Score

| Plant | Reviewer | Class | Found blind? | Evidence |
|---|---|---|---|---|
| Q1 §10.2 L115 | A | a ruling's basis misstated (U2 vs the relocation test) | **yes** | A-01, high: "is inverted … kernel placement is the Principal's decision"; integration confirms (A-01 = B out-of-scope 3) |
| Q2 §10.5 L79 | B | stale pointer (§10.3.9 does not exist) | **no** | B's report never names §10.3.9 or L79; its pointer table lists other stale pointers (B-04, B-06, B-09). Integration did not find it |
| Q3 §8.102 L45 | C | scope overstatement ("within D" dropped) | **no** | C: "All three sections hold within their stated scope ('within the estate'/'within D')". Integration did not find it |
| Q4 §8.103 L40 | D | quotation inverted | **yes** | D-01, high: "The source says **not** … Quote check: L40 NOT FOUND"; integration confirms |

**Blind: 2 of 4. Combined (with integration): 2 of 4.**

## What it shows [I]

- **The mechanical routes worked; the judgement routes did not.** Q4 was found by the quotation check (restored to the reviewer note before reviewers started) and Q1 by a self-contradiction inside one sentence. Q2 needed a pointer check (REVIEWER-NOTE item 7) and Q3 a scope reading; both were missed.
- **Stale pointers are now missed 3 times in 4 tests** (S0302 Q4, S0352 Q2, S0402 Q2). The open proposal P-Q4 (a mechanical pointer check) addresses exactly this.
- **The scope overstatement is the Professor's own most frequent defect** (7 times in C94–C105), and the reviewer read the section and affirmed its scope. Review does not reliably catch it either, so the Professor's pre-draft self-check carries more weight than assumed.
- **Window size is not tested by this result.** The plants sit in the consolidation copy, so they measure consolidation reviewers. Q3's section came from a one-source window (C108). The trial figures (misses per window) come from the settlement.
- **Series (blind):** S0100 4/8 · S0150 3/5 · S0200 2/4 · S0252 4/4 · S0302 3/4 · S0352 3/4 · **S0402 2/4**. Four plants per test; one test is a check, not a measure of reliability.

## Stage-2 guard (for the Professor's settlement)

- **Do not apply A-01 or D-01 to the working book.** Their lines are correct there (the plants exist only in the copy). Record them as found plants.
- **Q2 and Q3 need no change** in the working book.
- **Every other finding** (A, B, C, D, integration) concerns the real book and is settled as usual; R3 as usual.
- Integration items that merge A-01 or D-01 with other findings: apply only the non-plant part.

## Cost

Reviewers ran about 09:14–09:39 (2026-11-03), about 25 minutes for four reviewers in parallel and about 11 minutes of integration. Preparation off the critical path (S2): copy built, sealed and checked in about 15 minutes at the gate.
