# PBDIGIT-328: Membership developer guide 12 for PBDIGIT-264 Part 2 (never written)

**Type:** Documentation debt · **Epic:** `PBDIGIT-EPIC-02` · **Created:** 2026-11-03 · **Raised by:** the COR-2 acceptance (PO, 2026-11-03: "… 1–3 as recommended"; `docs/publicdigit/reviews/2026-11-03-pbdigit327-cor2-independent-review-and-acceptance.md` §4.1, decision 3). The gap was found during COR-2 step 7 and confirmed by the independent review (§G).

| | |
|---|---|
| **Status** | `OPEN` · **not authorised** |
| **Classification** | VERIFIED: the file does not exist; the promise is in the plan |

## Finding

- The PBDIGIT-264 Part 2 plan (`docs/plans/20261102-1750-membership-pbdigit264-part2-postgres-followups-plan.md:99`) promised "a new guide `12_pbdigit264_part2.md`".
- Part 2 updated guide 03 instead (F-5, N1, the RR SET, F-4b, step 4a `ON CONFLICT` / F-3 parity, E4-L). Guide 12 was never written.
- The developer-guide index (`developer_guide/membership/00_index.md`) goes from 11 to 13.

## Proposed direction (needs authorisation)

Choose one, PO's call:
- **(a)** write guide 12 as a short step guide for Part 2, pointing to guide 03 for the adapter details; or
- **(b)** record in the Part 2 plan and the index that guide 03 carries Part 2, and retire number 12.

Either way, ground every statement in the committed code and evidence (`docs/publicdigit/membership/evidence/2026-11-02-pbdigit264-part2/`).

## Not in scope

Any change to the adapter, schema or tests.
