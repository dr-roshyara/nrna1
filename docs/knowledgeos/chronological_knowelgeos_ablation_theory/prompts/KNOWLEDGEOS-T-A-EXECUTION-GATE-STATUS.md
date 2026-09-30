# T-A EXECUTION GATE STATUS — HD-2/3/4 recorded as given; corpus reading NOT started

| | |
|---|---|
| **Kind** | execution-gate report. ⚠ authority: generated. **No decision is taken by Claude** |
| **Commission** | human, 2026-09-26: *"Continue to work for next step … follow the prompts."* It adopts a senior assessment and prompt ("HUMAN DECISIONS — RECORD EXACTLY …") |
| **Frozen protocol** | r3 sha256 `be16deb7…7133` (HD-1), re-verified; unchanged |
| **Corpus reads** | ⛔ **none** |

---

## 1. Human decisions, recorded exactly as given (adopted prompt, human's own endorsement: "follow the prompts")

| HD | As given | What it establishes |
|---|---|---|
| HD-1 | *"DONE — r3 frozen by the human."* | already recorded (F-LOG-0031) |
| **HD-2** | *"RELEASE M-1 / F0018 **through the Research Release Check**."* | **Release intent for M-1, conditional on the Research Release Check.** By its own wording, the release passes through the check |
| **HD-3** | *"1. Record the explicit corpus-scope decision under S2 OQ-6. 2. **If** scope is IN, release the complete seven-object history. 3. Confirm explicitly that the binding r3 rule 'full revision history' governs the mistaken first-commit endpoint. … Do NOT release M-2/M-3 unless … required."* | the **erratum confirmation** (item 3) is given: "full revision history" governs, so the history starts at `d63202b8c`. **M-2 and M-3 are not released**, so Q-GS and Q-D4 are NOT_RUN. ⚠ **The scope decision itself is NOT given**: item 2 is conditional ("If scope is IN"). The senior commentary *recommends* IN, but a recommendation is not the L0 act |
| **HD-4** | *"Use: SELF + INDEPENDENT READER. … The independent reader must be commissioned by the human, not by Claude."* | reader design fixed. SELF = Claude. The INDEPENDENT reader is to be **commissioned by the human** (the senior reviewer offered to act) |

---

## 2. Why corpus reading has not started (the binding control plane, verified read-only)

**What L0-DEC-27 requires** (`governance/L0-DECISION-RECORD-01.md`):
- The Research Release Check is *"the single release check"*. It produces GREEN / YELLOW / RED, and *"**L0 decides the release** on that result."*
- The precedent, `audits/2026-09-23-RESEARCH-RELEASE-CHECK-01.md`, was *"run by the governance/control-plane session"*. It is *"a status report, not a release"*, followed by an L0 decision record (L0-DEC-30).

**Current state:**
- The latest L0 decision is **L0-DEC-30** (`487a5c72b`), whose scope (Critical Attack Pass 01) is **spent**.
- **No Research Release Check has been run for T-A**, and no L0 release covers M-1 or M-4.

**Consequence:**
- Reading F0018 or ES-006 now would be a corpus read without the check that HD-2 itself names. That is exactly the case L0-DEC-27 exists to prevent.
- The research session cannot close this gap itself:
  - (i) it would be the session that certifies its own release (evidence ≠ authority);
  - (ii) it may not write in the governance lane (standing lane-only instruction).

This is **not** a redesign and **not** a refusal. It is the next step of the path the human named: *"through the Research Release Check"*.

---

## 3. Input prepared for the Research Release Check (lane-side evidence; the check itself belongs to the governance session)

| # | RRC question | Lane-side evidence for T-A |
|---|---|---|
| 1 | methodology unchanged since approval? | r3 frozen by HD-1 (`be16deb7…7133`, commit `cfcee7bb0`); working tree = HEAD. Since then, only the release-preparation and gate-status reports, the logs, and the reader packet (derived, §4) were committed; **no change to r3 or to the instruments** |
| 2 | corpus and state identifiable? | M-1: `d61bf5e84` / blob `71edaebc…` / sha256 `b685f599…` = the manifest pin. M-4: 7 objects with blob and sha256 (F-LOG-0031). Manifest hash (reconstruction record) `54977c6e…`. The governance session re-verifies with `build-manifest.py --verify` |
| 3 | known defects classified, no open R1? | lane-side: the ES-006 endpoint erratum is confirmed by the human (HD-3.3) and contained, because the rule governs. The `sed -i` docstring hunk is R3 (operational), reconciled (F-LOG-0031). **Relevance to R2 RC-H-04:** T-A reads neither F0031–F0040 nor F0041, so the existing R2 containment is not engaged. The governance session classifies |
| 4 | controls detect what they claim? | lane instruments: `model.py` and `verifier.py` byte-reproducible; `aggregate.py` 32/32 synthetic. **Governance controls** (gate-runner, admit, batteries) are run by the governance session |
| 5 | L0 accepted the current state? | HD-1 given. **Open for L0:** the OQ-6 scope decision for M-4; the release itself |

**Scope to be authorized (as a statement L0 can adopt or change):**

> T-A under frozen r3 (`be16deb7…7133`). Complete reads of M-1 (`d61bf5e84:docs/knowledgeos/KnowledgeOS_Engineering_Progression_Model.md`) and, if scope is IN, the 7 M-4 objects (F-LOG-0031). Readers: Claude (SELF) plus one INDEPENDENT reader commissioned by the human. No other file. No ML. Stop after aggregation.

---

## 4. HD-4: blind reader packet (built; contains no corpus content)

| File | sha256 |
|---|---|
| `analysis/t_a/build_reader_packet.py` (deterministic builder; refuses to run unless the inputs match the frozen hashes) | `bc2fa3bd…911c` |
| `analysis/t_a/reader_packet/PREREGISTRATION-r3-BLIND.md` | `104defaa…9eb0` |
| `analysis/t_a/reader_packet/aggregate_blind.py` | `75a5f0d2…82da` |
| `analysis/t_a/reader_packet/README.md` (neutral reader instructions) | `475c42b8…ee37` |
| `analysis/t_a/reader_packet/PACKET.sha256` | provenance to `be16deb7…` and to `aggregate.py` `13532a5b…` |

**Redactions:**
- §3.2 in full;
- **one prediction leak found outside §3.2**: the §5 PARTIALLY UNTESTED row said *"§3.2 predicts NOT_EVIDENCED for A5g"*;
- the `PREDICTED` table.

**Checks:**
- A leak scan finds only generic rule text.
- The build is byte-reproducible (two runs, identical).
- The blind tool still validates records.

**Packet contents:** no attack document, no model results, no Claude findings. The released objects are added by hash **after** the release.

---

## 5. Exact remaining human acts

1. **Governance session: run the Research Release Check for the §3 scope**, and report GREEN / YELLOW / RED. Claude in this lane may not run it for its own release.
2. **L0: record the OQ-6 corpus-scope decision for M-4**: IN or OUT.
3. **L0: record the release** on the check's result, as a new L0 decision after L0-DEC-30.
4. **Commission the INDEPENDENT reader** and hand them the packet plus the released objects.

After 1–3: Claude executes T-A per r3 §10, then aggregates, then **stops** and reports one outcome: SURVIVES / INCONCLUSIVE / PARTIALLY UNTESTED / COUNTEREXAMPLE.

## 6. T-A readiness

- **Protocol:** frozen.
- **Reader packet:** ready.
- **Release objects:** pinned.
- **Execution: BLOCKED** only by the Research Release Check, the OQ-6 scope decision and the L0 release record. No corpus content read; no ML.
