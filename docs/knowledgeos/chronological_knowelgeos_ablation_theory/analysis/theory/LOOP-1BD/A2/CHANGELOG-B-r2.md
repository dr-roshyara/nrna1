# CHANGELOG — Protocol B r1 → r2 (targeted repair of REVIEW-r1 FAIL)

Only the defects listed in TASK.md were changed. Nothing was executed.

**Disclosed origin of BLK-1:** the r1 task text ("a candidate that is a norm register → SELF_ATTESTED") was applied at the **channel** level. That was a main-analyst specification defect, not a worker error.

## Blocking

- **BLK-1 — K-STATUS split into two disjoint channels.**
  - **New channel `K-NORMREG`.** It holds the paths that match the register regex (doc-like ext only; this was the r1 `npaths` subset). The classifier emits `K-NORMREG` first and `K-STATUS` only in the `else` branch, so no path can be in both. *(scope_b.sh classifier awk; PROTOCOL-B §1 table)*
  - **K-NORMREG Q7** = `SELF_ATTESTED[norm_register;overlap_exec=…]`. This holds **by declared definition**: a register records its own rulings, which is criterion (c). §3 rule 1 now states it as a definitional outcome, not an observed negative. `overlap_norm` is dropped from this label because N_c's path set is empty for K-NORMREG by construction. *(answers awk; §3 rule 1)*
  - **K-STATUS** now holds only the remaining status, progress, approval and sign-off records. It is evaluated **by data**, like K-DEPLOY: its mechanism is observable, and N_c = K-NORMREG paths − own paths, which is all K-NORMREG paths because the two channels are disjoint. The full ladder (rules 2/4/5/6) applies, including `INDEPENDENT_AT_IDENTITY_COUNT_LEVEL`. Traced against REVIEW-r1: `docs/implementation/PROGRAM_STATUS.md` is now K-STATUS. If a K-NORMREG path exists, `nnp>0` and its Q7 follows from the data. *(answers awk; §3 rules 4–6)*
  - **`npaths`** is now read from the `K-NORMREG` rows of `cand`. The per-channel loop covers `K-CI K-HOOK K-CODEOWNERS K-NORMREG K-STATUS K-DEPLOY`. *(§3 "Norm-register paths", "N_c"; §5 metrics)*
  - **Q2 labels** (follows from the split): `NORM_REGISTER_RECORD` (K-NORMREG) and `STATUS_RECORD` (K-STATUS) replace the shared `STATUS/NORM_RECORD`. *(§2 Q2)*
  - **Headline.** `SELF_ATTESTED` now has its own bucket and never counts towards `LOCAL_NO_INDEPENDENT_CANDIDATE_OBSERVED`. The order is INDEPENDENT → NOT_DETERMINABLE → NOT_INDEPENDENT (observed overlap only) → `LOCAL_ONLY_SELF_ATTESTED_RECORDS` → `LOCAL_NO_CANDIDATE_EVIDENCED`. The new token is defined in §5. *(scope_b.sh overall block; §5)*

## Non-blocking (fixed)

- **NB-1:** `--full-history` was added to the three `git log` calls for E, W (per path) and N_c. *(§3 intro)*
- **NB-2:** `GIT_CONFIG` and `GIT_COMMON_DIR` were added to the `unset` list. `GIT_GRAFT_FILE=/dev/null` is now exported, which neutralizes `.git/info/grafts`. *(§5 Guarantees)*
- **NB-3:** the register regex now has the trailing boundary `([._/-]|$)`. `authoriz` is reachable on its own as `authoriz(ation|ations)?`, because K-NORMREG is classified directly by the register regex and no longer needs another K-STATUS token. The K-STATUS token match already had the same boundary in r1; it is unchanged. To keep E's exclusion a complete glob translation of the classifier, `authoriz authorization authorizations` were added to the doc-ext glob token loop. The N13 clause "token `authoriz` is reachable only with another K-STATUS token" was removed from §6 because it no longer applies.
- **NB-10:** §3 now says which channels can reach a positive Q7: only `K-STATUS` and `K-DEPLOY`, and only when at least one K-NORMREG path exists outside their own paths. **Line count corrected:** r1 `scope_b.sh` had **170** lines, not the "~169" that CHANGELOG-B-r1 stated. r2 has **174** lines.

## Non-blocking (left as disclosed limitations, PROTOCOL-B §6)

- **NB-4, NB-5, NB-6, NB-7, NB-8, NB-9:** each one is listed on one line and not fixed. N7, N8, N10, N11, N13 (with the authoriz clause removed) and N15 are kept.

## Unchanged (by requirement)

- These are unchanged:
  - the tip set;
  - the E roots and exclusions (apart from the NB-3 token addition);
  - the K-CI, K-HOOK, K-CODEOWNERS and K-DEPLOY classification;
  - K-SIG and K-LOCALHOOK;
  - Q1, Q3–Q6 and Q8;
  - the output files and their columns.
- The register tokens in the K-STATUS token list were left as they were. After the split they cannot match a path that is not already K-NORMREG, so they are dead in the K-STATUS branch.
- The script is still read-only and emits counts only. The self path is still resolved and hashed before the one `cd`. OUT refusal, the fail-closed identity guard and neutrality are kept.
