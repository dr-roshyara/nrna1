# R-S1 review: S1 (assemble / EMPTY object / RECORD IDS / freeze-final check)

Verdict: IMPL-ACCEPTABLE (no MATERIAL defect; 3 non-blocking hardening items)

Run: `test_p3b_s5_r7_assemble test_p3b_s5_r7_orchestrate test_p3b_s5_r7_full test_p3b_s5_slice_view test_p3b_s5_r7_inv_lex` -> 179 tests OK (364 s). No production file touched by review.

## Faithfulness
- Annex E: every constant (DETAIL/REASON/LOAD/ROW34 note), author_role (v2), generation_parameters {producer, producer_sha256, rule "EMPTY-OBJECT v1", mode} (+checklist, v3), checklist_examined 1..23 iff slice in_checklist is True, hub LOAD escalations sorted by dim with copied hub_record: match the v1 §3 table as amended by v2/v3. Key name `producer_sha256` is the v1 table's key (v2 prose "tool_sha256" is narrative), so deviation (8) is correct.
- Golden tests are written out field by field (test T117), including Tier U ROW-0/3/4, R6; no S-id / pointer / `B:<n>` in constants or the object (T_10); self-check (Sigma/typing/META/empty/S3) wired with correct signatures.
- Manifest-order assembly, file order kept, blocks, canonical lines, write-once/idempotence (inode+mtime), --check writes nothing, byte determinism across cwd/TZ/locale/seed: tested and passing. Refusals R1-R13 each have a test asserting the code prefix, no write (tree hash), no label/S-id in message.

## Deviations judged
1. Fixture prints on-disk hashes: acceptable. It only removes the "no printed hashes" failure for other suites; the dedicated T120 tests use the real tool inside the marker and override the fixture text (user hook runs second). Masking risk is confined to freeze_final tests in other files, which do not assert the ASSEMBLED check.
2. RECORD IDS only for SINGLE/SYNTHESIS: correct (UNIT runs write none); tested.
3. Printed inputs exclude slices: sound; `_context` verifies each slice against the manifest `slice_sha256`, and the manifest's own hash is printed.
4. Extra R1 checks (batch form, addendum bytes on disk, plan labels = manifest labels, plan index = position): acceptable ("R1 context"); the index check enforces the §2.6 L. Tested.
5. R11 on malformed id: needed (otherwise AttributeError). Tested ("G1").
6. R13: per-label, non-bool refused. Absent entry dict tolerated: see H-1.
7. R10 agent objects only: correct; EMPTY object's model_id is the argument by construction.
9. Identical summary for ASSEMBLED/--check: intended and tested.
10. archive_root unused: documented, harmless (spec sketch had resolver_factory; T_06 proves no corpus read).

## Hardening (non-blocking)
- H-1 (orchestrate.py:407-421, 571): an absent manifest `in_checklist` dict is tolerated; prepare.py always writes it. A missing dict would build an in-checklist EMPTY label without `checklist_examined`; the verifier's G-09 (reads the slice) then fails the batch, so not a silent pass, but late. Fix: refuse R13 when dict is None outside test fixtures, or make freeze-final/CI treat `in_checklist_dict_absent=1` as failure; the fixture then needs `entry_extra`.
- H-2 (open question b; orchestrate.py:652-668, 661): freeze_final trusts the LAST ASSEMBLED marker's printed lines. The marker command is orchestrator-controlled text, so an orchestrator session could append a later `: S5-ORCH ASSEMBLED batch=B; echo <sha>  <path>` for a hand-edited assembly and pass; nothing binds the printed lines to a tool run, and FINAL-VALIDATED's `--check` output is never compared. This matches the approved text ("re-checks the hashes on the tool side"), so not a spec defect, but recommend: require the last FINAL-VALIDATED marker's printed hashes for the 3 files = ASSEMBLED = disk (and the `S5-ORCH assemble` summary line present). Add tests: register/p1-gap mismatch, no marker, forged later marker.
- H-3: `producer_sha256` hashes the whole orchestrate.py, so any later edit of that file (e.g. the concurrent S3 hub work) changes EMPTY objects; a pre-edit assembly then fails R8/R9. Freeze the tool file before the canary ASSEMBLED and record its sha in the activation/runbook.

Not covered by tests (minor): freeze_final refusal for register/p1-gap-only mismatch; duplicate n within one label (verifier catches it).

## v2 (delta: H-1, H-2)
Verdict: IMPL-ACCEPTABLE. Rerun test_p3b_s5_r7_assemble, _orchestrate, _full: 158 tests OK.
- H-1 closed (orchestrate.py:407-419): non-dict/null entry dict or missing label or value != slice -> R13; no tolerance.
- H-2 closed (orchestrate.py:652-671): last ASSEMBLED and last FINAL-VALIDATED printed hashes for all 3 files must equal disk and each other; missing marker refused. Fixture prints the same on-disk hashes for both markers (same masking caveat as before; dedicated tests override).
- Ordering: p3b_s5_r7_witness.py _stages (l.400-441) takes the LAST ASSEMBLED and LAST FINAL-VALIDATED (same records freeze_final uses) and fails the batch if assembled.t_result > final_v.t_call; both required. A forged later ASSEMBLED after FINAL-VALIDATED therefore fails W5 in verify. Caveat: freeze_final itself does not refuse on witness failures (returns witness_failures); the order gate is at verify, which is the acceptance gate. No gap.
