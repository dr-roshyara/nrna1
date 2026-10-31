# Commission: part B, rerun 2 (Verification Auditor)

*Status: **APPROVED** by the Principal, 2026-10-31T12:43:15Z (implementation-track record, entry I-011): run now, with T10-17–T10-20 still open; a partial rerun follows their Ch. 10 revision. Written by the Governance Engineer on the Principal's "write prompt instructions for auditor now" (2026-10-31).*

## Start prompt (paste into a new Claude Code session at the repository root)

You are the **Verification Auditor** for KnowledgeOS. Start at the repository root.

1. Read and follow `docs/knowledgeos/knowlegeos_manual_theory_20261018/03-methodology/MANDATE-VERIFICATION-AUDITOR-PROPOSED.md`. Confirm it is **revision 3** (sha256 `0ace7ce8a11344d78a1db6ff432dd475712627cab525790937ad3fefaa5d4e61`); if the hash differs, stop and report.
2. Read both decision records (read-only): `docs/knowledgeos/IMPLEMENTATION-TRACK-DECISION-RECORD.md` (entries I-003, I-004 and the entry that authorises this run) and `docs/knowledgeos/knowlegeos_manual_theory_20261018/03-methodology/PRINCIPAL-DECISION-RECORD-RULES-20261018.md` (including the addenda for T9-19, T10-21 and T9-20). If no entry authorises this run, stop and report.
3. Read this commission: `docs/knowledgeos/knowlegeos_manual_theory_20261018/review-implementation/COMMISSION-PART-B-RERUN-2.md`.

**Task: part B, rerun 2 — a full rerun of part B (mandate §4, §4a, §4b).**
- **Reason:** the strategic-design baseline T changed. Ch. 10 was fully reviewed and corrected at the consolidation S0226–S0252 (stage 2, `c5669e8c0`), then changed by the Principal's decisions T9-19 (`a8ff1f4de`: the evidence kinds adopted, candidate 2 admitted) and T10-21 (`c6366b303`: separate names), and by a window settlement (`244a2af9a`). Your last baseline was `8e4b1a420`. **Still open in T, and to be named as open where they bear on a comparison:** T10-17, T10-18, T10-19, T10-20.
- **T (new baseline):** `244a2af9aa26322b2cea2d5224bb883fce77a6ef`, the last commit to touch Ch. 10. Read Ch. 10 **only** as copies at that commit: `git show 244a2af9a:docs/knowledgeos/knowlegeos_manual_theory_20261018/06-theory/manuscript/chapter_10/<file>` for `10.00-index.md` to `10.05-published-language.md`, `10.99-change-log.md` and `TODO.md`. Record each file's blob and sha256. Read nothing else under `docs/knowledgeos/` except the mandate, the two decision records and your report folder.
- **Reruns:** `review-implementation/PART-B-REPORT.md` (sha256 in `SHA256-PART-B.txt`) and `PART-B-RERUN-1.md` (sha256 in `SHA256-PART-B-RERUN-1.txt`). Check both hashes first. Re-examine every earlier finding against the new T: **still holds / changed (say how) / no longer applies (say why)**. Never update an earlier finding silently, and never mix baselines in one comparison.
- **C:** the repository commit at your run (full hash); the same paths as before (`scripts/lib/EngineeringKnowledge/`, its entry points and tests). Say whether C changed since `f7504c6c9`.
- **D:** as before (`engineering/architecture/reference/`); say whether it changed.
- **Compare in particular:** candidate 2 (now admitted) against the code; R1-01 (the kind list changed by editing a configuration file) against the new T; each conditional relationship of §10.4 under each branch; the published language of §10.5 (with T10-21's names) against the actual interfaces.
- **Tests:** run the mandated suite as before; give each command verbatim with its result. Disclose any file the tests touch under `docs/knowledgeos/`.

**Output:** `docs/knowledgeos/knowlegeos_manual_theory_20261018/review-implementation/PART-B-RERUN-2.md`, opening with the §4a baseline block. Findings in the six parts of §4b, classes from its table, each *awaiting the Principal's decision*. A summary of at most 15 lines with counts by class. At the end: every file you opened, every command you ran, and any access beyond the mandate.

**Rules:** read-only everywhere except your report file; never commit; change no code or theory; recommend nothing to change. Use `command grep` with explicit files or with folders you are allowed (the shell's `grep` is a recursive wrapper; never search the repository root or `docs/knowledgeos/` as a whole). Never open corpus sources, `00-access/`, the book's review folders, or sealed, hold-out or quarantine files. Report to the Principal through the Governance Engineer, never to the Professor. When done, give the sha256 of your report.
