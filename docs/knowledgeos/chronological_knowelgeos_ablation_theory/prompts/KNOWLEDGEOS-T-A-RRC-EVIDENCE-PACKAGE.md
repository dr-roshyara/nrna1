# RRC EVIDENCE PACKAGE for T-A — RRC-T1 · RRC-T2 · RRC-T3 (governance input; no classification, no RAG)

| | |
|---|---|
| **Kind** | governance input. ⚠ authority: generated. ⛔ **Not the Research Release Check; no R1/R2/R3 class and no GREEN/YELLOW/RED assigned; no option selected** |
| **Commission** | human, 2026-09-26: the pasted "Continue from F-LOG-0033 …" prompt, sent as the answer to Claude's question whether to run it, plus *"Continue to work for next step …"* |
| **Basis** | `prompts/KNOWLEDGEOS-T-A-RRC-MEASUREMENTS.md` (F-LOG-0033) |
| **Reads** | ⛔ no corpus content; T-0056's statement text not read. Only git metadata, byte hashes, file sizes and mtimes, registry/state rows, the **structural** fields of theory-object records (id, sources, status, identifiers in `related`), and this lane's own documents |
| **Writes** | this file and the F-lane logs only. Nothing rebuilt, reverted, deleted or cleaned |

---

## RRC-T1 — F2800 manifest divergence

| # | Question | Evidence |
|---|---|---|
| 1 | **What exactly changed?** | Path: `docs/knowledgeos/brainstorming/knowledgeos_theory_research/corpus/knowledge_os_with_lina_puran/20260831-104505_analyse-review-attached-file-senior-mathematician-statistician-ddd-architect-expert.md`. **Untracked** in git, so no committed version exists and the old bytes are unrecoverable from git. **Recorded before:** governance manifest `CORPUS-MANIFEST.jsonl` sha256 `e140e172…d9d2`; **F-lane baseline** `F-MANIFEST.jsonl` row 1291 and `F-SERIES-STATE.jsonl` (IDENTIFIED) give the same sha256 `e140e172…`, **2960 bytes**, file mtime **2026-09-21T14:32:22+02:00**. **Now:** sha256 `a3df1871…`, **3010 bytes** (+50), mtime **2026-09-26 01:12:40 +02:00**. What the 50 bytes are is not determinable without reading content |
| — | adjacent fact (not interpreted) | In the same directory, the **tracked** file `20260920-104505_analyse-review-…md` is **deleted** in the working tree (last commit `91ae27d92`, 2026-09-20). Its HEAD sha256 `53e2ec4c…` equals **neither** F2800 hash. **No content link is established**, and none is inferred from the similar name |
| 2 | **Inside or outside the T-A scope?** | **Outside.** The T-A scope (gate status §3) is M-1 = F0018 (`d61bf5e84`) plus, if OQ-6 = IN, the 7 ES-006 objects. F2800 is not among them. Frozen r3 does not name F2800 |
| 3 | **Does any T-A object depend on it?** | None recorded. F2800 has **0 read receipts**. It appears in no reconstruction record and no theory object. It is referenced only by the manifest, `SELECTION-UNIVERSE.jsonl`, and the F-lane registration files. M-1's pin (`b685f599…`) is a **per-file** hash that still verifies. The M-4 objects are git objects, independent of the working tree |
| 4 | **Can it be excluded without silently changing the experiment?** | **Yes, if the exclusion is recorded.** T-A's inputs are identified by object hashes that F2800 cannot affect. The one shared reference is the **manifest hash `54977c6e…`**, cited as the provenance of M-1's pin. Leaving the manifest **unrebuilt** keeps that reference valid; rebuilding it would move T-A's baseline. Exclusion alters no T-A input, rule or hash |
| 5 | **Draft containment statement** (for L0 to adopt, amend or reject) | *"F2800 diverges from CORPUS-MANIFEST (`e140e172…` → `a3df1871…`, untracked, modified 2026-09-26). It is outside the T-A scope and quarantined from T-A: no T-A reader reads, cites or relies on F2800. The manifest is not rebuilt for T-A; M-1 is verified by its per-file pin. Any work that reads F2800 requires a separate decision."* |

**Separate note (not a T-A matter):** F2800 is also a registered F-Series file, and its F-lane baseline hash no longer matches. That is an F-Series integrity observation, for the F-Series record.

---

## RRC-T2 — T-0056 provenance significance

**Facts (structural fields only):**

| | T-0056 | T-0013 | T-0014 |
|---|---|---|---|
| `historical_sources` | **['F0018']** | ['F0018'] | ['F0018', 'F0016', 'F0017', 'F0014'] |
| status / evidence | RECOVERED · DIRECT_QUOTED · HIGH | — | — |
| formal relation rows (`phase2_extraction/THEORY-OBJECT-RELATIONS.jsonl`, the KOS-G-020 input) | **0** | 1 | 3 |
| informal `related` field | a list naming **T-0013, T-0014** (identifiers only; the type of relation is not formalized) | none | none |

**Its role in H-F2-1** (this lane's documents):
- T-0056 is the recorded source of the **kind vocabulary** (GOV / EVID / WORK / COMP, and PROVENANCE as non-progression).
- It is a cited source for **A1, A2e (with T-0013), A3m, A4 and A5** (attack document §2).
- **Frozen r3 does not name T-0056.** Its kind procedure (§3.0) is self-contained.

**Governance fact:** L0-DEC-20 records batch 3 (**F0014–F0018**) as *"EXECUTED BEFORE GOVERNANCE RELEASE / NOT RETROACTIVELY CERTIFIED"*. T-0013, T-0014 and T-0056 all cite F0018.

**The three questions, kept apart:**

| | Question | Evidence-based answer |
|---|---|---|
| **A** | Evidence required to **execute** T-A | frozen r3 plus the released objects. **T-0056 is not a T-A input**, and r3's typing needs no theory object. The content T-0056 was recovered from is **F0018 itself**, which T-A re-reads completely. T-A therefore tests the source directly, not T-0056's summary of it |
| **B** | Evidence required to establish the **historical provenance of H-F2-1** | H-F2-1 is an **[E] formalization** combining T-0013 × T-0014 × T-0056. That combination is Claude's construction, **not a recorded relation**. The missing relation row means the reconstruction does not independently record *how* T-0056 relates to T-0013 and T-0014; only an untyped `related` list exists. Batch 3's uncertified status also bears on B: T-A's released re-read of F0018 is the first **released** read of H-F2-1's single common source |
| **C** | Evidence required to claim **completeness of the reconstruction** | KOS-G-020 (not activated) fails on 12 objects, including T-0056. This concerns the reconstruction's completeness, **not** T-A's execution |

**Two governance options** (Claude selects neither):

| Option | Statement | Consequence |
|---|---|---|
| **A** | T-0056 is not required as a T-A evidence object. T-A may proceed, with an explicit caveat: *"T-A tests H-F2-1-R against F0018 and ES-006 directly; H-F2-1's construction from T-0013 × T-0014 × T-0056 is an [E] combination whose inter-object relations are not formally recorded (T-0056: 0 relation rows), and whose common source F0018 comes from an uncertified batch (L0-DEC-20)."* | T-A runs. Its outcome is stated as a result about H-F2-1-R **against the released sources**, not as a validation of the reconstruction chain |
| **B** | T-0056 is required for the pre-registered claim. T-A waits until T-0056's relations are formally recorded (a reconstruction task), and, if L0 so decides, until batch 3 / F0018 provenance is certified | T-A is deferred. It needs a reconstruction commission; relations may not be inferred |

**Fact bearing on the choice** (not a selection): frozen r3 §1 defines T-A as a *source-fidelity* test of the axioms against F0018 and ES-006. It makes no claim about the relation graph of T-0056.

---

## RRC-T3 — four untracked governance-lane files

| File (`knowledgeos_theory_chronological_extraction/prompts/`) | Bytes | mtime | sha256 (16) | Referenced by tracked files | In gates.yaml / governance-state.yaml |
|---|---|---|---|---|---|
| `evaluation_researchmethod.md` | 20232 | 2026-09-24 08:15:44 | `b3fe99e3feda9a76` | only `F-BASELINE-GIT-STATUS.txt` and the F-LOG-0033 report | 0 |
| `review_of_phase1.md` | 34775 | 2026-09-24 07:50:58 | `5698f070e560a4b2` | same | 0 |
| `review_of_phase_2.md` | 25601 | 2026-09-24 07:49:09 | `5e7d1974bea92b33` | same | 0 |
| `review_of_v1.2 architecture.md` | 30015 | 2026-09-24 07:42:28 | `71db8d50cd6e0e29` | same | 0 |

**Facts:**
- All four were created **after** RRC-01 (commit 2026-09-23T19:07).
- They were already untracked when the F-Series baseline was taken.
- They are never committed.

**What metadata settles (by construction, no content needed):**

| Question | Answer | Why |
|---|---|---|
| **B:** do they modify the **release criteria**? | **No, not as criteria in force** | release criteria = L0-DEC-27 plus `gates.yaml` / `governance-state.yaml` / `gate-schema.yaml` / `gate-runner.py`. All are byte-identical to RRC-01, and none references these files |
| **C:** do they modify the **reader packet**? | **No** | the packet is built only from frozen r3 and `aggregate.py`, and the builder refuses any input whose hash differs |
| **A:** do they modify the **frozen methodology text**? | **No, not the text in force**: the committed protocols have had no commit since `628d02169` | an untracked file cannot alter committed text |
| **A′ / D:** are they **proposed** methodology changes, or unrelated artifacts? | ⚠ **Not determinable from metadata.** The names (*review of phase 1/2, review of v1.2 architecture, evaluation of research method*) suggest review material, but a name is not evidence of content. **Governance content review required** | this commission forbids silently deciding |

---

## Preserved measurements (F-LOG-0033; unchanged, no RAG)

| Measurement | Value |
|---|---|
| gate-runner self-test | 55/55 |
| pins | 5/5 identical |
| preflight | CLEAR (process conformance only) |
| regressions | 66/66 · admit 12/12 |
| control hashes | gates.yaml `40f1c2d5…` · governance-state.yaml `effdbebc…` · gate-schema.yaml `4ebde2c9…` · gate-runner.py `050dfda4…`, all identical to RRC-01 |
| KOS-G-020 (not activated) | FAIL 46/58, T-0047…T-0058 missing, **including T-0056** |
| admission audit | BINDING_FAILURES_PRESENT; only the nine RC-H-04 IDs F0031–F0038, F0040 |
| manifest | STALE_OR_CORPUS_CHANGED; only F2800 |

---

## Next state (decisions not taken here)

**Governance session:**
1. F2800: classification and containment (the RRC-T1 draft statement).
2. T-0056: significance (Option A or B).
3. The four files: content review, then classification.
4. GREEN / YELLOW / RED.

**L0:**
1. ES-006 scope IN/OUT (OQ-6). The research recommendation is IN; it is a recommendation only.
2. Whether T-0056 needs action.
3. The formal T-A release.

**Then:** SELF + INDEPENDENT readers execute T-A under frozen r3.

*STOP. No corpus content read; no T-0056 statement read; no manifest, r3 or release hash altered; no protocol created.*
