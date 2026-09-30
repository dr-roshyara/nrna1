# R7 FR-1 repair (Option A: bounded input manifest I(run)): record

| | |
|---|---|
| **Kind** | Minimal §26 design repair. ⚠ authority: generated. **DESIGN-REPAIR-ONLY · NOT FROZEN · NOT IMPLEMENTED · NOT ACTIVATED** |
| **Authority** | G-LOG-0087: FR-1 closed by **Option A** (Option B, inline delivery, rejected) |
| **Input** | `audit-p3b/20260926_R7-FREEZE-READINESS-RECORD.md` (DESIGN FREEZE BLOCKED by FR-1) |
| **FR-1 status** | **CLOSED in the design.** No NEW-DESIGN-BLOCKER was found |
| **Independence limit** | the design, the checks and this repair are by the same agent; the independent R7 audit (HD-9) remains required |

## 1. Exact invariant added

> **AllowedRead(r, p) ⇔ p ∈ I(r)**, where I(r) = I_static(r) ∪ I_persisted(r).
>
> - **I_static(r)** is derived by Universe before dispatch, **frozen at dispatch**, and **hash-bound**.
> - **I_persisted(r)** is a class rule frozen at dispatch, with membership witnessed.
>
> ∀ witnessed read-input events (r, p):
> - p ∈ I(r);
> - HashObserved(p) = HashFrozen(I(r), p);
> - the harness-displayed content equals the frozen bytes over the displayed range.
>
> Otherwise `R7-W` (a hash mismatch against the frozen manifest is `R7-U`).
>
> **Input reads are never evidence:** Wr(S) is built only from corpus-reader `read` events.

## 2. Exact W8 change (§5.5)

| Before (v2.1) | After (v2.2) |
|---|---|
| 3 capabilities: canonical reader · Write to a run-owned path · handback. "Read, Grep, Glob, …" were violations | **4 disjoint capabilities:** (1) canonical reader, a *corpus observation* · **(2) Read of p ∈ I(run)**, an *input read, never evidence* · (3) Write to a run-owned path · (4) handback. **Read of any p ∉ I(run)** is a violation; Grep, Glob, Edit etc. remain violations; the SEAL stop covers a Read of a hold-out or corpus path even if wrongly listed |

- The enforcement point is the witness (authoritative, post hoc). A harness permission rule or PreToolUse hook restricting Read to I(run) is **optional operational defence in depth**, never a substitute.
- **Unrestricted Read is not reopened:** an agent reads declared inputs but cannot discover its own authority to read.

## 3. Exact I(run) schema (§5.8)

**`RunInputManifest`:** `{run, role, entries[], persisted_outputs: ALLOWED | NONE, manifest_sha256}`.
- Each entry: `{path (canonical, repository-relative), owner_run, category, sha256, declared_role}`.
- `manifest_sha256` = the sha256 of the canonical JSON without that field.
- It is carried in the dispatch witness record as `input_manifest_sha256`.

**Closed categories:**

| Category | Runs |
|---|---|
| `CONTRACT` (frozen rev3) | all |
| `ADDENDUM` (frozen R7) | all |
| `PLAN` (frozen R7 plan) | all |
| `SLICE` (the run's own label) | all |
| `UNIT-RECORDS` (every unit record file of the same label) | that label's synthesis run only |
| `DECLARED-SYNTHESIS-INPUT` | synthesis only; empty unless the frozen contract lists one |

**Persisted outputs:** the only runtime-membership class. A file is a member iff it is announced by the harness (`<persisted-output>`) in a tool_result of the **same agent's own** transcript, earlier than the Read, and `persisted_outputs = ALLOWED`. Its sha256 is recorded at the freeze. Agents cannot write there (W8).

**Hard prohibitions → `R7-U` (preparation failure; reported, never silently removed):**
- a corpus path;
- another label's slice or records;
- another run's private input;
- hold-out or H-19;
- F-Series;
- application code;
- an arbitrary repository file;
- an out-of-category path.

**Witness grammar:**
- a new `read-input` event (agent_id, run, path, manifest_entry, sha256_frozen, sha256_observed, displayed_range, content_match, t_call, t_result), **distinct from `read`**;
- `dispatch` gains `input_manifest_sha256`.

**DDD:**
- I(run) is owned by **Universe** (listed in §0).
- Witness records what was read. Evidence never decides permission, and input reads never become evidence.
- Universe hashes the input files itself at dispatch. For synthesis, dispatch is after the unit freeze. The equality with the hashes printed at unit validation is a Witness check (§7), so **no Universe → Witness dependency arises** and the context graph stays acyclic.

## 4. Two points made explicit, not treated as blockers

1. **Persisted outputs cannot be path-frozen at dispatch**, because the harness creates them at run time. So the **rule** is frozen at dispatch and **membership** is harness-witnessed. The requirement "frozen at dispatch, hash-bound" holds for I_static. For I_persisted it holds as "rule frozen at dispatch, content hash-bound at the freeze". This is stated in DR-17 for the human freeze.
2. **"At read: verify against the frozen manifest"** is realized by the witness, because the harness Read tool cannot be interposed on by the design. This is consistent with Model D, where every execution fact is witness-established. Pre-emptive hooks are optional.

Neither requires unrestricted Read, corpus access, or a change to reconstruction or statistical semantics, EXTENDS, the archive policy or the Master Protocol, so no stop condition applies.

## 5. Tests added: T84–T97 (the total is now 97; T01–T83 unchanged)

| Mandated test | Id |
|---|---|
| 1 allowed input read | **T84** (BATCH-PASS) |
| 2 undeclared input read | **T85** |
| 3 wrong-run input read | **T86** |
| 4 wrong-label input read | **T87** |
| 5 hold-out read | **T88** (+ SEAL) |
| 6 hash mismatch | **T89** (`R7-U`) |
| 7 persisted permitted | **T91** (BATCH-PASS) |
| 8 persisted undeclared | **T92** |
| 9 DECOMPOSED synthesis positive path | **T93** (BATCH-PASS) |
| 10 W8 output schema | **T94** (static) |
| 11 no regression | T01–T83 byte-identical to v2.1 (check FR1-p) |

**Additional tests:**
- T90: displayed content ≠ frozen bytes;
- T95: a prohibited path in a prepared I(run), which gives `R7-U` and is reported;
- T96: a quote only in an input file, not on a witnessed reader page, which gives `R7-E` (input ≠ evidence);
- T97: a mis-declared I(L02S) omitting U01's records.

**The prompt's cases:** A → T97, B → T87, C → T88, D → T89, E → T85.

## 6. Static design checks

`r7_fr1_check.py` (scratchpad; sha256 `de4810df…60d2`) is a superset of the readiness checker, run with the v2.1 copy as the regression baseline.

**Result: 73/73 PASS** (output `48710d87…caca`). The 16 new FR-1 checks:
- the four disjoint capabilities;
- AllowedRead ⇔ p ∈ I(r); unrestricted Read not reopened;
- the schema fields; the closed category enum; the prohibitions → `R7-U`, reported;
- the hash binding; `read-input` distinct from `read`, with `input_manifest_sha256` at dispatch;
- input reads never evidence;
- Universe ownership;
- persisted-output rule and membership;
- the witness as the enforcement point;
- DR-17 and FD-8′ recorded;
- the mandated-test and A–E mappings;
- W8 positive controls;
- **no regression of T01–T83**.

**Preserved** (all earlier checks still pass): DR-15, DR-16, PF-1…PF-8, the strong-Kleene verdicts, the statistics, the reconstruction semantics, the EXTENDS proposal, the archive decision (open) and the ML boundary.

## 7. Safety

| Check | Result |
|---|---|
| corpus reads / resolver calls / agent dispatch / S5 / binary pre-classification | none |
| H-19 | SEALED (HS-3d32dd44d162); guard 58 files, 0 violations |
| ledger fingerprint `4fde15fc42513725…` · P3B-STATE `db52ac7a…` · manifest `1b383fbc…` (revision 3) | unchanged |
| R6, F-Series, application code, Master Protocol | untouched by this slice |
| repository changes by this slice | the R7 addendum, `P3B-GOVERNANCE-LOG.md` (G-LOG-0087), this record, the S-Series TODO |

**Concurrent activity (not ours):**
- F-Series commits `41318da72`…`dfe3b3410` landed during this slice.
- A concurrent session appended F-lane lines to the shared `.claude/sessions/2026-09-26.md`. My S-Series section was appended, but **the session log is excluded from this commit**, so as not to commit another lane's content.

## 8. Files and hashes

| File | sha256 |
|---|---|
| `prompts/20260926_2400_p3b-agent-contract-r7-addendum.md` (v2.2) | `4850dc76822dffc2be7029ff9c3fd169bdd9d9641e0906f21ece9a2e277cefa5` (v2.1: `f7c6c137…ad6a`) |
| `P3B-GOVERNANCE-LOG.md` (+ G-LOG-0087) | `9006db1dc394c5bbbc7e5de34851f7e0c205ece5deefd992beaaad98b9531240` |
| `audit-p3b/20260926_R7-FR1-REPAIR-RECORD.md` | this file |

## 9. State and next boundary

**R7 v2.2: FR-1 CLOSED (Option A) · DESIGN-REPAIR-ONLY · NOT FROZEN · NOT IMPLEMENTED · NOT ACTIVATED.**

**Next: the HUMAN DESIGN FREEZE.** The remaining open decisions:
- **FD-1′b:** the placement of EXTENDS;
- **FD-4′:** the transcript-archive location.

All other FDs (including FD-8′ = Option A) are ready. After the freeze comes R7 implementation B0–B9 → engineering verification → STOP → the human decision on ONE independent R7 audit.
