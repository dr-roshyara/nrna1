# 1bd reconciliation: Protocol B (norm-status channel scoping), sealed

## Design loop (FAIL → targeted repair → re-review)

| Round | Verdict | Blocking | Note |
|---|---|---|---|
| r0 (A, B) | FAIL | 5 (B1–B5) | HEAD-only writers; N ⊆ own paths; historic-path leak into E; E not an executor set; a K-DEPLOY forced negative |
| r1 (A1, B1) | FAIL | 1 (BLK-1) | K-STATUS SELF_ATTESTED by channel id. **Origin: main-analyst task wording** |
| r2 (A2, B2) | FAIL | 1 (BLK-2) | the register regex missed infix names (the rulings register) |
| r3 (A3, B3) | PASS_WITH_LIMITATIONS | 0 | pre-registered stop-on-FAIL not triggered |

## Execution and output review
- **Execution:** verbatim, r3 sha `e9e0975b…`, HEAD `9f1bcab5`. The root and `app/` runs are byte-identical; exit 0/0; no errors.
- **Main-analyst verification after the review:** `sha256sum -c` on all 9 files OK; the script hash matches; `grep @` over the outputs returns nothing (rc 1).
- **Output review: PASS_WITH_LIMITATIONS.** All 13 Q7 values and the headline were recomputed by hand and match. Identity OK.

## Sealed findings (descriptive; scoping only)

**F-B1. No locally checkable channel qualifies as an independent norm-in-force record.**

| Channel(s) | Q7 |
|---|---|
| K-CI, K-HOOK, K-SIG | INDEPENDENCE_NOT_DETERMINABLE |
| K-STATUS, K-DEPLOY | NOT_INDEPENDENT[overlap_norm=3] |
| K-NORMREG | SELF_ATTESTED (definitional) |
| K-CODEOWNERS, K-LOCALHOOK | not evidenced |

Q5 (norm-in-force at t) is NOT_DETERMINABLE_BY_EXISTENCE_CHECK everywhere. Headline: `LOCAL_INDEPENDENCE_NOT_DETERMINABLE`.

**F-B2. The identity-overlap test has almost no power here** (output review, power note). Author-identity sets have size ≤ 3, and aliases and non-human authors are unresolved. Neither the observed overlaps nor a hypothetical zero overlap would discriminate between role arrangements. **F-B1's NOT_INDEPENDENT entries must therefore be read as "no evidence of role separation", not as "evidence of role fusion".**

**F-B3. The only candidates not closed by local scoping are outside the local scope:**
- the 5 host-side channels (PR approvals, branch protection, audit logs, CI run history, membership): NOT_CHECKABLE_LOCALLY;
- the 23 signature headers (K-SIG), whose signer kind is undetermined (a developer key vs a hosting platform's web-flow key vs automation). Verifying them would require keys and/or host data.

Each would be a **new evidence channel → a human decision**.

## Disclosed limitations
- **Vocabulary over-capture:** NB-12/NB-13; 202 K-NORMREG and 153 K-STATUS paths, composition not emitted.
- **Programme-adjacent artefacts:** .claude/ and claude/ are not excluded (N15) → falls under the R-SELF decision, which is human-held.
- **Not emitted into the hashed outputs:** |E|, the identity universe, HEAD and the tip count.
- **Signature count:** merges are included in K-SIG; it is a lower bound (N7/NB-5).
- **Repo-local config:** readable.
- **Scope:** local refs only; completeness vs the host is not shown.

## Interpretation boundary
**Nothing here scores H-D or H-B.** The discrimination-readiness question (does the acquired evidence contain an independent norm-state observable?) is a **separate, reviewed task**.
