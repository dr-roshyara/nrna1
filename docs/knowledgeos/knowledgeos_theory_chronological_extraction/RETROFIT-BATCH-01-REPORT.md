# Retrofit Batch 1 — `F0002`–`F0006` · **RESULT, and a STOP**

| | |
|---|---|
| **Authority** | ⭐ L0 standing authorization 2026-09-23 — *"the 31 admissible NON_CONFORMANT files … batches of five, in canonical order, starting with F0002–F0006"* |
| **Unit** | `U-0007-RETROFIT-B1` · receipts issued for all five |
| **Status** | ⭐ **five files retrofitted** · ⛔ **BATCH STOPPED at the machine-check step** |
| ⛔ **Batch 2 NOT started** | per the authorization: *"If a batch encounters a governance ambiguity or restriction, **stop that batch and surface the exact issue** rather than assuming authorization"* |

---

## 1 · The work

| File | lines | §9A | `dossier_status` | gate 9 satisfied by |
|---|---|---|---|---|
| `F0002` Architecture Validation Matrix | 68 | **12/12** | ⭐ COMPLETE | ⭐ **`T-0047`** graded source provenance |
| `F0003` Architecture Synthesis Matrix | 124 | **12/12** | ⭐ COMPLETE | ⭐ **`T-0048`** the definitional guard |
| `F0004` Architecture Health Dashboard | 58 | **12/12** | ⭐ COMPLETE | ⭐ verification — `T-0032`/`T-0035` already carry it |
| `F0005` Architecture Fitness Assessment | 210 | **12/12** | ⭐ COMPLETE | ⭐ **`T-0049`** three kinds of gap |
| `F0006` ARB Decision Docket | 198 | **12/12** | ⭐ COMPLETE | ⭐ **`T-0050`** define the metric before running the check |

**Written:** 5 File Reconstruction Records · 5 gate sheets · 5 pipelines (30 steps each) · 5 §6 dossiers · **4 new Theory Objects** · 6 registries appended · ledger reconciled.
⛔ **0 existing Theory Registry rows modified.** ⛔ **0 held files touched.** ⛔ **Candidate Theory v0.9 unchanged.**

---

## 2 · ⛔⛔ THE STOP — the machine checks

> ### ⛔ **Hand-written 12/12 is NOT a gate pass, and the machine surface says so.**

| Check | Exit | Result |
|---|---|---|
| `build-manifest.py --verify` | **0** | ✅ **`STATUS: CURRENT`** |
| `admit.py --audit` | ⛔ **1** | ⛔ **CRASHED — no binding verdict produced** |
| `gate-runner.py` | ⛔ **3** | ⛔ **`GOVERNANCE_INOPERATIVE`** |

### ⛔ 2a · `GOVERNANCE_INOPERATIVE` — ⭐ intended, and the human's to clear

All five activated gates report *"unpinned activation (**L0-DEC-16**); print pins with `--pin-lines`"*.

**`L0-DEC-16` (GIA-4):** *"an activation pins the exact gate-definition hash. Any change to a pinned gate's definition makes governance `GOVERNANCE_INOPERATIVE` until the human re-activates."*
And explicitly: ⭐ ***"pins are written by the human in `governance-state.yaml`, because activation is a human act. The runner will print the pin lines for the human to paste; governance does not write activation… That is intended."***

> ⛔ **The runner's own words: *"This is NOT a gate verdict, NOT a pass, and grants no permission to proceed."*** ⭐ **So this batch has no governance verdict, and I have not manufactured one.**

⛔ **Not mine to clear.** Not governance's either — `L0-DEC-16` assigns it to the human. **Run `python3 governance/gate-runner.py --pin-lines` and paste the output into `governance-state.yaml`.**

### ⛔ 2b · The binding audit CRASHED — ⭐ and the defect is MINE

```
KeyError: 'sha256_at_read'   →  evidence/READ-RECEIPTS.jsonl row 8
```

⭐ **Row 8 is the VOID marker for the F0047 receipt** — the one voided earlier when a receipt had been issued at admission for a file that was never read. Its keys are `status · why · correction_of · rule_applied · instrument_defect_noted`, ⛔ **not receipt keys**.

| | |
|---|---|
| ⭐ **The real defect** | ⛔ **a void marker was written into the receipt stream, and the instrument was never taught that the stream can contain non-receipts.** The void was honest; the shape was not declared |
| **Consequence** | ⛔ **the audit has never completed since that row was written.** Every *"binding audit"* result after it is absent, not clean |
| ⚠️ **Why I did not patch it** | `admit.py` is research-produced and **`GIA-9` has it under governance review as an unreviewed admission path**. ⭐ Silently repairing an instrument that governance is reviewing is the boundary I have already crossed three times |
| **Recommendation** | either a void marker moves to its own file, or `admit.py` learns to skip rows without `sha256_at_read`. ⛔ **One line either way — but it is an instrument change, so I am surfacing it, not making it** |

⚠️ **The one prior signal — *"1 receipt bound to a DIFFERENT manifest hash"* — printed before the crash and was not investigated, because the run aborted.** ⛔ Re-checked by hand afterwards: **no receipt carries a differing hash.** The line appears to be the crash's neighbour, not a finding; ⭐ **recorded as unexplained rather than dismissed.**

---

## 3 · What the batch found

### ⭐⭐ `F0003`'s REJECTED register was never extracted — and it cost three corrections

`F0003` is **canonical position 3**. Its `R-7`, `R-8`, `R-9` rows already rejected, in 2026-08-03:

| | Rejected claim | Later re-derived as |
|---|---|---|
| `R-7` | *"Two missions / mission layer vacant"* | the v0.8 headline built from a withdrawn banner |
| `R-8` | *"0 of 11 responsibilities enforcing"* | the enforcement over-claim |
| `R-9` | *"0 traversals"* **as a generalization** — n≈3 (`R-36 · R-41 · R-63`) | **`C-0007`** |

> ⭐ ***A file whose value is a register of NEGATIVES is the easiest kind to skim past.*** **`C-0021` · `RO-0033`.**

### ⭐ Two of my own claims corrected by canonical discovery

| I had written | Canonical discovery showed |
|---|---|
| *"F0004's R-6 count disagrees with F0032"* | ⭐ **`T-0035` already says n=3 with the same three OE records. `F0004` AGREES with the registry; `F0032` is the outlier** — `C-0020`, ⛔ **REFERRED**, not resolved |
| *"F0005 makes T-0042's claim source-supported"* | ⭐ **`T-0042` already records it correctly** from `F0038`/`F0040`. `F0005` adds a **third supporting file**, not a new finding — `RO-0035` |

⭐ **Four objects were created; at least four more were NOT, because they already existed.** *(`T-0032` back-edge · `T-0035` R-6 counts · `T-0039` two-missions withdrawal · `T-0042` enforcement.)* **`ES-005.4` held.**

### ⭐ The unstated-negative-scope pattern is NOT uniform

`RO-0019/0020/0023/0030` recorded four files whose negative searches state no scope. ⭐ **`F0002` states its scope** — *"the specific 'two products rule' phrasing was NOT found in the fetched sources"*, against *"3 web clusters read this session"*. ⛔ **The weakness varies by commission; it is not a corpus-wide method defect.**

### ⭐ `T-0026`'s qualification gains a second file — but not independence

`F0003` `K-4`: *"10 invariants survived cross-product projection; **I-4 corrected to a lifecycle gap**"*.
**RA-15, honestly:** SOURCE-SUPPORTED ✅ · RECONSTRUCTION-VALID ✅ · THEORY-CONSISTENT ✅ · ⛔ **INDEPENDENTLY CORROBORATED — NO** *(`K-4` cites `F0027` among its three sources; same programme, next day)*. **`C-0019`.**

**New obligations:** `RO-0032` … `RO-0037`. **New contradictions:** `C-0019` · `C-0020` · `C-0021`.

---

## 4 · Ledger

```
COMPLETE              7   (F0001 F0010 + F0002 F0003 F0004 F0005 F0006)
RETROFITTED_S9_S9A    3   (F0026 F0027 F0032 — gate 9 held on §5A m1/m2)
DOSSIER_ONLY          1
NON_CONFORMANT       36   (26 admissible remaining + 10 RC-H-04 held)
```

⭐ **Admissible retrofit backlog: 31 → 26.**

---

## 5 · ⛔ Next action — and why it is NOT batch 2

> ### ⛔ **Batch 2 (`F0007`, `F0009`, `F0011`, `F0012`, `F0013`) is NOT started.**

**Two blockers, neither mine:**

| # | Blocker | Owner | Clears when |
|---|---|---|---|
| **1** | ⛔ `GOVERNANCE_INOPERATIVE` — unpinned activations | ⭐ **the human** (`L0-DEC-16`) | `--pin-lines` output pasted into `governance-state.yaml` |
| **2** | ⛔ the binding audit cannot complete | **governance** (`GIA-9` owns `admit.py`) | void markers separated, or the tool skips non-receipt rows |

⚠️ **Batch 1's artifacts stand as evidence.** ⛔ **They are not certified** — no machine gate verdict exists for them, and `R-34` bars this session from accepting its own work.

⭐ **Still open from before, unchanged:** `m1`/`m2` (§5A) · `GI-1` (v1.2 approval) · `GI-2` · `GVR-F0032` `U-4` · `RC-H-04`/`C-5` · `B-12`.

---

*Batch 1 · 5 files · 5 receipts · 12/12 hand-evaluated gates each · ⭐ 4 new Theory Objects · ⛔ 0 existing rows modified · ⛔ 2 machine checks failing · ⛔ batch 2 NOT started · ⛔ no gate pass claimed.*
