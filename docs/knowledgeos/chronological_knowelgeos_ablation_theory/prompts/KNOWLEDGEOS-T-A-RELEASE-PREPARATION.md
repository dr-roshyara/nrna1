# T-A RELEASE PREPARATION (HD-2 / HD-3 / HD-4) — prepared, not executed

| | |
|---|---|
| **Kind** | release preparation. ⚠ authority: generated. **No release is requested; no decision is recorded here** |
| **Governing document** | T-A pre-registration **r3, FROZEN by the human**, 2026-09-26: *"Freeze r3 at be16deb7…7133."* sha256 `be16deb7af88d1c87072566c0e1c1feb43258cf2e84e6d707107bedc6a617133`, commit `cfcee7bb0`. The frozen file is not modified by anything below |
| **Reads** | ⛔ no corpus content. **Git metadata** (commit ids, dates, paths, rename status, blob ids) and **byte hashes** (`git show <obj> \| sha256sum`; the content itself was never displayed) |

---

## 1. Frozen-hash verification

| Check | Result |
|---|---|
| working-tree sha256 of r3 | `be16deb7…7133` ✅ |
| HEAD object sha256 of r3 | `be16deb7…7133` ✅ |
| commit containing r3 | `cfcee7bb0` (2026-09-26T02:52:21+02:00) |
| lane working tree | clean |

---

## 2. Integrity of the T-A instruments

| Artifact | sha256 (working tree = HEAD) |
|---|---|
| `analysis/t_a/aggregate.py` | `13532a5bd4123514ab1cf92d2a057a64c8bab20bdca5b2cf59e43febc2d8c7b9` |
| `analysis/t_a/test_aggregate.py` | `567e965c809aff6d183e76f2c3e56180b31b56ba5ae52637dee7a1e746fb6b45` |
| attack document | `f85162a9219f2f9ae2e7b41edef21259030f02004047f218291cf466bd49b2fe` |
| `model.py` re-run vs `results.json` | byte-identical (`75b58856…`) ✅ |
| `verifier.py` re-run vs `verifier_results.json` | byte-identical (`372cf3e7…`; re-run in this session, file unchanged in git) ✅ |
| synthetic suite | **32/32 OK** ✅. These show the **instrument** behaves as specified. They are **not** evidence for H-F2-1 |

---

## 3. Disclosed process exception (`sed -i`, F-LOG-0030): reconciled; no file change needed

**What happened:** one line, in the docstring of `analysis/t_a/aggregate.py`, commit `cfcee7bb0`.

```diff
-Without --scope it exits 2: the scope is PENDING HD-S and no candidate-level verdict may be computed.
+Without --scope it exits 2 (HD-S fixed the scope as UNIVERSAL; it must still be passed explicitly).
```

**Which rule was broken:** the repository's source-editing rule (`.claude/CLAUDE.md`, "Source Code Editing Policy").
- It prohibits automated replacement on source code, naming `app/`, `resources/`, `routes/`, `tests/` and `database/`.
- It requires **locate → explain → Read-then-Edit → show the diff → verify**.
- The file lies outside the named directories, but it **is** source code, so the rule is treated as applicable.

**Steps missing at the time:** Read-then-Edit (a deliberate edit) and **show the diff**.

**Minimum correction:**
- Redoing the same line with Read+Edit would produce a byte-identical file and no reviewable change, so it would add nothing.
- The substantive purpose of the rule, a reviewable and intentional hunk, is met by recording it here:
  - **located:** docstring, line 9;
  - **root cause:** the text became stale after HD-S;
  - **diff:** shown above; a comment-only hunk; no code path is touched;
  - **verified:** grep of the line; 32/32 tests; the sha256 is unchanged since the commit.
- **No further action.** Recurrence prevention: Edit only, for any change to lane scripts.

---

## 4. HD-2 — M-1 (F0018): prepared release object

| Field | Value |
|---|---|
| canonical id / path | F0018 · `docs/knowledgeos/KnowledgeOS_Engineering_Progression_Model.md` |
| pinned object | commit `d61bf5e84`, blob `71edaebc344acd6f47181b8a97c0cced501b6fe6` |
| pin check | `git show d61bf5e84:<path> \| sha256sum` = `b685f5991338f24df135a50bd7d004999d62401bdac10f9c0ef8241ecbbed7bc` = the `CORPUS-MANIFEST.jsonl` pin ✅ |
| read kind | **re-READ** of an already-read canonical file, under a named obligation (S2 §4.3b) |
| governance route | Research Release Check (L0-DEC-27); an L0 release. L0-DEC-30's scope is spent |
| tests served | F-A2e, F-A3g, F-A4, F-A5e, F-A5g, F-A3m (with M-4), Q-H6 (with M-3) |

**Complete-read procedure (r3 §10 steps 4–7):**
1. Read **by object** (`git show d61bf5e84:<path>`), never from the working tree.
2. Re-check the sha256 against the pin before reading. A mismatch means STOP.
3. Read the whole file (157 lines per the reconstruction record) and log it in the read-integrity ledger.
4. Fill `operation` before `effect`, per passage.
5. Classify from §3.1 only.

---

## 5. HD-3 — M-4 (ES-006): prepared release objects

**Erratum found in preparation (the frozen text is not changed; for HD-3 to settle).**
- Frozen r3 §2.1 says: *"history = 7 commits, `aee484e9c` (2026-07-11) … `668cc7b22`"*.
- The count is right, but the **first commit is wrong**. The history starts at **`d63202b8c`**, where the file was created under an **older path**, `engineering/governance/ES-006-Knowledge.md`. It was renamed at `aee484e9c` with 71% similarity, so the rename also changed content.
- A literal reading of the range would cover **6** commits and omit the creation.
- Cause: `git log --follow --reverse` returned only the rename commit. The count came from `--follow` without `--reverse`, so the range endpoint came from a truncated listing.
- r3's **binding rule** is §2.1: *"for M-4, the **full revision history** is the object of F-A6"*. That rule covers all 7 commits.
- **Proposed reading for HD-3:** release the 7 objects below. The human confirms that the rule prevails over the mistaken endpoint. This is **not** a revision of r3.

| # | Commit | Committer date | Path at that commit | Blob | sha256 |
|---|---|---|---|---|---|
| 1 | `d63202b8c` | 2026-07-11 18:15:43 +02:00 | `engineering/governance/ES-006-Knowledge.md` (**added**) | `2b192109…` | `7205c52d…7530` |
| 2 | `aee484e9c` | 2026-07-11 18:21:28 | `…/ES-006-Engineering-Knowledge-Governance.md` (**renamed, R071**) | `d8422b5a…` | `d23d8b53…af9c` |
| 3 | `da565a213` | 2026-07-11 18:23:50 | same | `23941312…` | `25fcd004…fbb0` |
| 4 | `c71f7d689` | 2026-07-11 18:50:03 | same | `791ad506…` | `facb576d…7bda` |
| 5 | `8d1df4b1d` | 2026-07-11 19:58:22 | same | `b07ec342…` | `170f344d…65b2` |
| 6 | `43682264d` | 2026-07-11 20:06:02 | same | `71e8a451…` | `12287296…6e6c` |
| 7 | `668cc7b22` | 2026-07-26 20:06:50 | same (**snapshot at F0018's date**) | `a68a2eee…` | `349b7d5d…2a0` |

Full values: F-LOG-0031.

**Further facts:**
- No commit touches either path after 2026-08-02.
- The old path does not exist at HEAD.
- The working tree is unmodified.

**Governance route:**
- M-4 lies outside `docs/knowledgeos/`, so it needs the **corpus-scope decision** (S2 OQ-6) **and** a release.
- The revision history is repository metadata **plus** 7 object reads.
- F-A6 reads object 7 as the snapshot, and objects 1–7 as a sequence. Diffs between consecutive objects are part of the evidence.

**Tests served:** F-A6 (primary), F-A0, F-A3m (with M-1).

**Optional M-2 / M-3** (only for Q-GS and Q-D4; otherwise NOT_RUN, per r3 §5.0 rule 0):
- `authorities.yaml` @ `faa8d61e2`, blob `67d9beb4…`, sha256 `720d63f0…`;
- `statuses.yaml` @ `faa8d61e2`, blob `81b9b12c…`, sha256 `4e05ea13…`.

---

## 6. HD-4 — reader independence: prepared classification

| Class | Who qualifies here | Why |
|---|---|---|
| **SELF** | **Claude in this session lineage** | Claude authored H-F2-1-R, the attack, the pre-registration and §3.2's predictions. It has already seen the predictions, so it cannot be blind |
| **SECONDARY_REVIEW** | a fresh subagent commissioned by Claude, of the same model family | no authorship of the candidate, but commissioned, briefed and scoped by the author; the same training lineage. **Not independent**: a different process or context does not make a reviewer independent |
| **INDEPENDENT** | a person, or a different model family, **commissioned by the human, not by Claude** | independence of authorship, commissioning and lineage |

**Requirements for an INDEPENDENT run (prepared):**
1. It receives a **reader packet**: frozen r3 **minus §3.2** (the predictions) and minus the `PREDICTED` table in `aggregate.py`.
   - The packet is derived mechanically from the frozen file, and its own hash is recorded with provenance to `be16deb7…`.
   - r3 §4 requires a blind second reader, and the frozen file itself contains the predictions, so the packet is necessary.
2. It receives the released objects of §4/§5 by hash, and **nothing else**: no attack document, no model results, no Claude reports.
3. It records its evidence independently, in its own `records.jsonl`.
4. Disagreements with any other reader are **recorded, not averaged** (r3 §5).
5. Its identity and independence class are stated in every record.

**Realistic default [E]:** Claude as reader = **SELF**, optionally plus a **SECONDARY_REVIEW** second reader from the packet.
- That supports **fidelity**, per the T-A purpose, but no independence claim.
- An INDEPENDENT reader can be added now or later without changing r3.

---

## 7. Remaining human decisions

1. **HD-2:** release M-1 (object §4) through the Research Release Check.
2. **HD-3:**
   - the corpus-scope decision (S2 OQ-6) for M-4;
   - the release of the 7 objects in §5;
   - **confirm the erratum reading** (the full-history rule prevails over the mistaken endpoint);
   - optionally, M-2 and M-3.
3. **HD-4:** the reader classes: SELF alone / SELF + SECONDARY_REVIEW / plus an INDEPENDENT reader (who commissions them, and when).

## 8. T-A readiness

**Protocol: READY (frozen). Execution: BLOCKED** on HD-2, HD-3 and HD-4.
- No corpus content read.
- No evidence extracted.
- `aggregate.py` not run on real records.
- No ML.
