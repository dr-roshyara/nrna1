# 1w: I-A1 (no execution without full authorization) on WP-4B: git timestamps against the rulings register

| | |
|---|---|
| Status | research record. Not canonical. Authority: none |
| Spec | `IA1-WP4B/SPEC.json`, frozen at `a6533817d` before any timestamp was examined |
| Checker | `ia1_check.py` (`00cb88f6…`) |
| Outputs | `RESULT.json` (`abfbbc8d…`) · locate `LOCATE-R73-R80.json` |
| Exposure | hashes, times, subject prefixes and term presence only |
| Log | F-LOG-0128 |

## Frozen predictions
- **Bounds:**
  - T_R72 = 08-02 15:02: authorized "in principle"; "no RED begins";
  - T_R77 = 08-03 15:28: "WP-4B REMAINS BLOCKED";
  - T_R86 = 08-04 00:29: Batch 7 released.
- **Implementation commits found:** 8. They run from 08-03 16:10 (a "WP-4B RED" commit) to 08-04 20:47.

| Prediction | Result |
|---|---|
| **P-a** no implementation before T_R72 | **SUPPORTED** |
| **P-b** no implementation before T_R77 | **SUPPORTED**: the first RED commit is 42 minutes after R-77 was recorded |
| **P-c** no batch-7 commit before T_R86 | **VIOLATED under the frozen rule** (6a67da5d7, f2ac054c8) |

**P-c: operationalization artefact (post-hoc, disclosed; the frozen result is not overturned).**
- The two commits' own subjects read "WP-4B **RED**" and "WP-4B **batch 6**". The keyword rule matched "redrive"/"keystone" in them.
- No commit subject names batch 7 at all. Batch-7 work after R-86 may exist under subjects that do not match /wp-?4b/ (a recall gap).
- **So P-c is unresolved in substance, not established either way.** A better operationalization is needed: file-level, e.g. the redrive code path between the R-81 freeze and R-86.

## The decisive open question (MODEL-DERIVED from SOURCE facts already read, plus mechanical times)
- **R-79 ("SEQUENCE AFFIRMED", recorded 15:47):** "(1) the two remaining Board acts — PROMOTION (permission) and ALLOCATION (ownership) · (2) WP-4B RED → …"
- **R-86 and R-87 (08-04):** "`ChallengeRaised` PROMOTION and ALLOCATION remain OPEN". R-86 adds that they "bear on the production producer path and §WP-4 closure, not on batch 7's execution".
- **WP-4B RED began 08-03 16:10.**
- Rows first recorded between R-77 and the first RED commit are R-77, R-78, R-79 and R-80. Term presence (locate only):
  - **R-80** (Directive, 15:53, 17 minutes before RED) contains PROMOTION, ALLOCATION and proviso/satisfaction terms;
  - R-78 (15:34) contains ALLOCATION.
- **Two readings:**
  - **H-resequenced:** R-80 (a new act) states that WP-4B RED may begin before, or independently of, the two Board acts. Then **I-A1 holds**, and R-79's order was amended by a later act, which is consistent with the record invariant.
  - **H-deviation:** R-80 does not license it. Then RED began while a gate that the source itself stated was unmet. **I-A1 would then be VIOLATED**, and M3's work sub-model, which *derived* R-79's order from its guards, would be **falsified** for WP-4B.
- This is the sharpest test available: prospective, mechanically timed, and resolvable with one bounded read.

## Next
**L0-REL-32:** a bounded read of R-80 (and R-78), with predictions frozen first.
- **H-resequenced** predicts R-80 states that WP-4B execution does not wait on PROMOTION/ALLOCATION, or that the proviso is satisfied.
- **H-deviation** predicts it does not.
