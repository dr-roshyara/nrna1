# Task (frozen): a targeted repair of Protocol B (r2)

Use ONLY the files here:
- `PROTOCOL-B.md`, `scope_b.sh`, `CHANGELOG-B-r1.md`: the r1 version to repair;
- `REVIEW-r1.md` / `.json`: its review, verdict FAIL with one blocking issue (BLK-1);
- `QUESTIONS.md`, `ENVIRONMENT.md`, `ROOTS.txt`.

No commands, no web.

**Disclosed origin of BLK-1:** the r1 task text itself ("a candidate that is a norm register → SELF_ATTESTED") was applied at the **channel** level. That was a main-analyst specification defect, not a worker error.

Repair **only** the following; change nothing else.

1. **BLK-1 (blocking): split K-STATUS into two disjoint channels.**
   - **K-NORMREG:** the norm-register subset, i.e. paths matching the register regex.
     - Q7 = SELF_ATTESTED[norm_register;overlap_exec=…], **by declared definition**: a register records its own rulings (QUESTIONS.md: a self-attested record is not independent merely for having a timestamp).
     - State this in PROTOCOL-B §3 as a definitional outcome, not an observed negative.
   - **K-STATUS:** the remaining status / progress / approval / sign-off records, disjoint from K-NORMREG.
     - Evaluated **by data**, exactly like K-DEPLOY: mechanism observable; N_c = the K-NORMREG paths minus c's own paths (disjoint by construction, so N_c = all K-NORMREG paths).
     - The full Q7 ladder applies, including INDEPENDENT_AT_IDENTITY_COUNT_LEVEL.
   - **Headline:** SELF_ATTESTED is counted in its **own** bucket, never in LOCAL_NO_INDEPENDENT_CANDIDATE_OBSERVED. Precedence:
     1. INDEPENDENT;
     2. NOT_DETERMINABLE;
     3. NOT_INDEPENDENT (observed overlap);
     4. only SELF_ATTESTED → LOCAL_ONLY_SELF_ATTESTED_RECORDS;
     5. none → LOCAL_NO_CANDIDATE_EVIDENCED.

     Define the new token in §5.
2. **NB-1:** add `--full-history` to the W, E and N_c `git log` calls.
3. **NB-2:** unset GIT_CONFIG and GIT_COMMON_DIR. Set GIT_GRAFT_FILE=/dev/null, which neutralizes .git/info/grafts.
4. **NB-3:** give the register regex a trailing token boundary `([._/-]|$)`, and make "authoriz" reachable as `authoriz(ation|ations)?`. Apply the same boundary to the K-STATUS token match.
5. **NB-10:** state in §3 which channels can reach a positive Q7 (after the split: K-STATUS and K-DEPLOY). Correct the line count.

Leave NB-4..NB-9 as **one-line disclosed limitations** in PROTOCOL-B §6. Keep everything else unchanged.

Write the repaired **PROTOCOL-B.md** and **scope_b.sh** (≤ ~180 lines), plus **CHANGELOG-B-r2.md** listing each change by defect id.
