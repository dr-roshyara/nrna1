# Targeted re-review of Protocol B r1 (frozen)

A researcher repaired PROTOCOL-B.md / scope_b.sh against defects B1–B5 (blocking) and N1–N6, N9, N12, N14 (REVIEW-r0). CHANGELOG-B-r1.md describes the repair. Use only the files here; read the script line by line.

Check **only**:
1. **B1:** does every history query (W, commit counts, E, N) use the same filtered, peeled, commit-only tip set? Is the result independent of the checked-out branch?
2. **B2:** can a candidate's own paths ever enter its N_c? Trace K-STATUS for a register file such as PROGRAM_STATUS.md: what Q7 does it get, and is that outcome forced by construction?
3. **B3/B4:**
   - Is E root-based, author-only and full-history?
   - Can a channel file's historic or renamed versions put its own writers into E?
   - Are doc/governance roots excluded from E?
4. **B5/N1:** is any Q5 = NO without content?
5. **Reachability:**
   - list every reachable outcome per question;
   - can INDEPENDENT_AT_IDENTITY_COUNT_LEVEL actually be produced (give a concrete configuration)?
   - is the headline computed from the data?
   - are any forced negatives left?
6. **Regression:** re-check read-only safety (every command), determinism, config isolation, self path before `cd`, OUT refusal, no identities (guard fails closed), boundedness, docs/knowledgeos/ exclusion, neutrality.

Verdict: **PASS** · **PASS_WITH_LIMITATIONS** (no blocking issue) · **FAIL**, with every blocking issue. FAIL means the script must not run.

Write **REVIEW.md** and **REVIEW.json** ({verdict, fixed: {B1,B2,B3,B4,B5,N1,N2,N3,N4,N6,N9,N12,N14}, reachability: {...}, regressions: [...], blocking: [...], nonblocking: [...], independence_rule_ok, identity_ok}).
