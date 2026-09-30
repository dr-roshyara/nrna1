# R-S2b review: EG-6 retire block (p3b_s5_r7_orchestrate.py ~L775-1272 + test_p3b_s5_r7_retire.py)
Verdict: IMPL-ACCEPTABLE. No MATERIAL findings. The 129 tests in the four modules pass.

Line numbers are file lines in p3b_s5_r7_orchestrate.py.

## Attacked and holding
- Crash safety. Every step is idempotent and classifies each pair as source-only, target-only, both (class S) or neither (class S). Stale .tmp files are the tool's own scratch.
  - An empty target directory left before the intent write is repaired by the S0 tgt check. Until then the guard is blocked by the planned-directory and namespace checks.
  - A crash between S6 and the state save resumes through the `_retire_refreeze` "new != raw, state == old" path.
- State-save-before-manifest-install window (L~1427-1431, `st.manifest_sha256 == new_sha`).
  - The state is bound to the new manifest but disk holds the old one.
  - `retirable` still holds, because the state is still FAILED.
  - The re-freeze is deterministic from the intent's `reason` and `legacy_before`, so re-running `retire` produces identical bytes and installs them. The rebind record is checked via history keys "from"/"to", which `_append` does write.
  - `may_start_attempt` fails closed in that window, because it reads the old entry and finds a namespace violation. `state new-attempt` therefore refuses. `stm.resume` also refuses on the manifest hash mismatch.
  - Not repairable without the same `--reason` and cause class, but the refusal message says so.
- Preconditions. `retirable` refuses VERIFIED/AUDITED/ACCEPTED, revision != 7 and a non-FAILED/INCOMPLETE state. RETIRE_CLASSES excludes H, D, S and U. The cause class must equal the recorded class.
  - Running retire twice is a no-op with counts returned. After `new-attempt` the state is PREPARED, so `retirable` refuses.
- Nothing is deleted. The only overwrites are own `.tmp` files and `os.replace` of the manifest.
- Tamper detection. `_tree` compares the full {relpath: sha256} dict, so extra or altered files and symlinks give class S. This is checked before S2, after S2, before S4 and after S4, and again before the re-freeze.
- EXDEV and st_dev. `_device_rule` runs before the first rename of S0, S2 and S4. A mid-step EXDEV gives class X, and resume classifies the already-moved pairs.
- Manifest re-freeze.
  - The header is canonical, and the body is `"\n".join(lines[1:])` (trailing newline preserved), which equals the activation's `sha(body_txt)`.
  - Only the batch's legacy fields change. The body output_sha256 is recomputed and `r7_legacy_refreezes` is appended.
  - `R7_ENTRY_FIELDS` (prepare.py:481) contains both legacy fields, so `r7_base_consistency` is unaffected.
  - `prepare --check` compares against `rev3_base`, and that is not touched.
  - `rebind_manifest` compares composition keys only, and the code asserts `st_new["batches"] == st["batches"]`.
  - The verifier and the reader never re-check the R7 header output_sha256. Only the snapshot and report echoes use it, and nothing compares them.
- namespace_violations. After retirement the moved directory is in `legacy_dirs` and the digest is recomputed with `planned` only. Other batches match the regex `OB####-` and are unaffected. `<B>-R7.A1` does not match the EMPTY-label regex `<B>-R7-L##`.
- Quarantine. The intent record (which holds the reason) and the re-frozen manifest text are scanned before any write. The CLI prints counts and hashes only.

## Builder's open items
1. Guard not wired into `prepare`. Not material. `new-attempt` already requires the guard, `prepare` only writes views, manifests and prompts and never touches the ledger or archive, and the tree cannot become live before dispatch.
   - Wiring it in is optional hardening: call `may_start_attempt` from `prepare` only when the state's current attempt is > 1 and its run has no ledger directory yet.
   - Confirmed: it must NOT go into `archive`. Guard clause (ii) is false for every refresh of `<archive>/<B>/` during a live attempt.
   - EG6-SPEC-A-v2 §1 says "before archive B". That sentence should be amended to say the first archive of attempt m+1 needs guard clause (ii) only, not the full guard.
2. T134 anti-replay is MINOR (spec T134, EG6-SPEC-A.md:84,197), not needed for safety in the honest path.
   - After retirement `<archive>/<B>` is gone, so `archive` will accept any session, including a stale attempt-m transcript. Outputs for m+1 come from new agents and the verifier/W2 bind them to the archive.
   - It is a cheap, deterministic control against a lazy or dishonest orchestrator. Do it in `freeze-final`: refuse if any main/subagent digest of the new archive is in `transcript_sha256` of a retired intent. Otherwise record an explicit waiver.
3. Allowlist. No verifier or commit path reads moved nested files through `check_inputs`. `U.legacy_digest` and `_tree` use raw `open`, and `check_retirement` only touches the two records. Admitting only the RETIREMENT records is sufficient.

## MINOR findings (non-blocking)
- L~1245 `retire()` does not check `rr7_reason`, `wsys_stopped` or m+1 <= M before moving evidence. An attempt recorded A1 but RR-7-promoted to D, or the 4th attempt, gets retired and the manifest re-frozen, and `new-attempt` then refuses. This is human-stop territory, so leaving the evidence unmoved would be cleaner. Fix: in S0, after `retirable`, call `stm.rr7_reason(st, batch)` and `stm.wsys_stopped(st)` and refuse if either applies. Also refuse if `m + 1 > stm.M_MAX_ATTEMPTS` (a policy call).
- L~1223 `_staging_dir` is validated even when unused. Re-running after the REFREEZE-STATE crash with the same `--staging` is refused ("must be a new directory"). Use a fresh path, or validate the staging path only when it is used.
- `_tree` ignores empty directories and file modes. This is a bytes-only guarantee. Acceptable.
- `_install` replaces the prior manifest; the old bytes survive only in the staging dir (default mkdtemp under /tmp) or in git. This is the same as the EG-4 precedent. Recommend documenting or defaulting staging under the archive root.
- A stale `<manifest>.tmp` in the repo root after a crash inside `_install` is untracked debris (overwritten next run).
- `stm.load`/`KeyError` inside `_retire_refreeze` is not mapped to Refused, so a corrupt state gives a traceback rather than REFUSED.
