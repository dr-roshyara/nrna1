# Review of the blind-slice design (independent subagent, read-only, blind to every result; Claude family)

**Verdict: PASS_WITH_LIMITATIONS.**

## Findings
1. **Well-definedness: OK.**
   - The P0.1 restatements carry over; no definition hard-codes V or P.
   - (ii)-S's σ_v ≡ r.
   - (iii)-L's last clause gains new branches: record the reading chosen.
   - (ii)-S: say "either witness missing".
   - The §2 sentence is inaccurate: the restatements also refer to σ.
   - The output types and the order of Cap/Ref lists are unpinned, and `r` the key collides with r the value.
2. **Blindness:** no bundle text gives away a U_P2 value. But:
   - counts and least counterexamples where holds is true follow from EXPOSED verdicts;
   - several sizes are identities or duplicates: |R_B^{R-i′}| = n; |H-D&R_B| = |H-D| under R-c, R-f, R-i, R-i′; the (i)-S scope = |R_B|; R_B is shared across 7 readings and H-D across 9.

   The licence must count distinct quantities.
3. **Calibration:** sound, but the V1b → slice field and encoding mapping must be pre-fixed. The U (i)-S and (ii)-S scope sizes and counts are not calibrated.
4. **Feasibility:** about 3 × 10⁶ histories. Minutes to tens of minutes in CPython; a sandbox may time out. A fallback is needed.
5. **Other:** a freeze/void rule is needed; commit the tar hash before the Claude run; record that the worker may load `~/.claude/CLAUDE.md`.

## Reconciliation (main analyst)
Every required and recommended change was applied **before** the Claude run:
- SLICE-SPEC: §2 wording; "either witness missing"; record (iii)-L readings in NOTES.
- TASK: value types; Cap and Ref as sets; `r` = recording time.
- PREREG-SLICE: BLIND restricted to nonzero-count forms (Claude-side holds = false) and de-duplicated; calibration mapping fixed; freeze, worker-context and sandbox-fallback rules (§5).

The bundle was then frozen: tar `fd83979b…`, file hashes in `BUNDLE-FILES.sha256`.
