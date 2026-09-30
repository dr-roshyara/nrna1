# E0 authorization readiness (Step 2) and post-E0 decision tree (Step 3)

**Kind:** read-only readiness audit plus a decision procedure. **Nothing authorized · no agent dispatched · no corpus content read · frozen instrument unmodified.**

**Governing documents:**
- V1.2.4 pre-registration (§1, §17, §18, §22, "Next-step authorization required");
- Master Protocol v3.5 (R0/R2/R19);
- P3b v1.7.

This document decides nothing.

---

## 1. E0 readiness: **PASS**

## 2. Evidence (all verified on 2026-09-26; script: session scratchpad `e0_readiness.py`, read-only)

| # | Check | Result |
|---|---|---|
| 1 | commit `c9cfd8f568a250a06563fe9b8c3b6496b4d3ac18` exists and is an ancestor of HEAD (`3ce32bf0e`) | ✓ |
| 2 | pre-registration sha256 `4d899d867dc762205a7e5eab74ab9d1f534730c772e984416f1010aa33e19650` (commit = HEAD = working tree) | ✓ |
| 3 | tool `scripts/p3b_v1_2_4_instrument.py` sha256 `946cc5f896167bd869e59e683b77fcd80b2dde1cb0991200672ce0563ecfcae1` (commit = HEAD = working tree) | ✓ |
| 4 | tests sha256 `55957864db6a162bc6c40c49b83811f26f803d712c56bd39d5ea53c26e391bc7` (commit = HEAD = working tree) | ✓ |
| 5 | `PROMPT-CHECK.json` sha256 `1f62f6540823a3a2434536ef7d06931e40db38f39c399f5f9dc3e741894f6cc8`; `passed: true`; names the freeze commit; its per-prompt hashes match the files | ✓ |
| 6 | namespace `PX0109` · `pilot-s5-decomp/v1-r3` · `V1.2.4-AUTHORIZATION.json` · 8 runs (U01–U06, A01, A02) | ✓ |
| 7 | 8/8 prompts byte-equal to `generate_prompt(run, c9cfd8f5…)`; a fresh re-check gives 0 violations over 101 commands | ✓ |
| 8 | all 8 expected prompt files present, and no others | ✓ |
| 9 | dependencies (commit = HEAD = working tree): reader `p3b_read_source.py` `bc705bc9…de60e` · common library `p3b_s5_common.py` `8df4bf7c…a7208f` · resolver `p3b_discovery_io.py` `04abd070…f08a0` | ✓ |
| 9a | the six pre-registered sources resolve as **GIT-OBJECT** blobs that are present in the object store, with manifest `content_sha256` equal to the pre-registered values (existence and metadata only; no content read) | ✓ |
| 10 | guard: no authorization file → refuse; short commit → refuse; tool and each dependency bound (X3/N2; real-git probe in the re-audit, `333b9a79e`) | ✓ |
| 11 | historical immutability: since the freeze, nothing under `v1/`, `v1-r1/`, `v1-r2/`, `PX0106-*`, the V1.x tools/pre-registrations, v3.5, P3b, Architecture v1.2 or S5 changed. Changes are only S-lane additions (PX0109 prompts, re-audit, TODO/architecture notes) and F-lane commits by other sessions | ✓ |
| 12 | PX0106 = RUN-INVALID (G-LOG-0071); its artifacts remain in `v1/` and `PX0106-*` | ✓ |
| 13–14 | PX0107 (`v1-r1`) and PX0108 (`v1-r2`) = prompts plus gate result only; no authorization, no run directories | ✓ |
| 15 | A-2 = MATERIAL-UNTESTED / CONSERVATIVE-RUN-INVALID (pre-registration §0.0000) | ✓ |
| 16 | the F-13 four statements are preserved (pre-registration "Next-step authorization required"; §22) | ✓ |
| 17 | no corpus read under PX0109: no `pilot-s5-decomp/PX0109-*` directories or read logs; `v1-r3` contains only `PROMPT-CHECK.json` and `PROMPTS/` | ✓ |
| 18 | no E0 agent dispatched: no `V1-DISPATCH.json`, no authorization file | ✓ |
| 19 | scientific/statistical identity with V1.2.3: 19 constants (files, models, taxonomy, thresholds, templates, schema, tool rules, dependencies) and all run roles identical; 14 statistical and decision functions source-identical (`segment`, `draw_sample`, `_kappa`, `kappas`, `capture`, `m1_completeness`, `capture_gate`, `gated_metrics`, `chapman`, `decide`, `equalize`, `quote_status`, `apply_repair`, `check_inventory`) | ✓ |
| 20 | tests re-run: V1.2.4 110 · V1.2.3 96 · V1.2.2 80 · V1.2.1 67 · V1.2 51 · V1.1 45 · V1 16, all OK | ✓ |
| — | standing states: H-19 seal `SEALED`; S5c PROHIBITED (`P3B-ESCALATIONS.jsonl`, ESC-0001) | ✓ |

**Scope audit: SCOPE-SAFE.**
- **What E0 writes:** only under `docs/knowledgeos/chronological-read/pilot-s5-decomp/v1-r3/` and `pilot-s5-decomp/PX0109-*/` (the reader's pilot-log isolation, `p3b_read_source.py` `PILOT_ROOT`). E0 reads sources only from immutable git objects.
- **Unrelated changes are untouched:** none of the ~240 unrelated working-tree changes (brainstorming, theory-research, F-lane, application, `.codex`, `.claude/settings.local.json`) lie in those paths. The only untracked file in this folder is the protected v1.2 copy (A6).
- **Condition:** every E0 commit stages **named paths only**; never `git add -A` or `.`.

**Non-material observation (not a blocker, not a reason for V1.2.5):** running the **older** suites (V1–V1.2.2) leaks about 29 `/tmp` directories per full pass. This is the known pre-V1.2.3 m-3 issue; the V1.2.4 suite itself is clean. Deletion is left to the human.

## 3. Remaining authorization items (only what the human must state)

1. **"AUTHORIZE V1.2.4 EXECUTION under pre-registration `c9cfd8f568a250a06563fe9b8c3b6496b4d3ac18`"**, with pre-registration sha256 `4d899d86…9650`.
2. The namespace `PX0109` / `pilot-s5-decomp/v1-r3/`.
3. That PX0106 ended RUN-INVALID and PX0107/PX0108 were prepared only; **PX0109 is independent evidence**.
4. **The four F-13 statements:**
   1. instrument-validation result only;
   2. the taxonomy is experimental, not v3.5 B2;
   3. no v3.5 or P3b obligation changes;
   4. adoption into P3b only by a §26 revision.
5. **Acceptance of A-2** (MATERIAL-UNTESTED / CONSERVATIVE-RUN-INVALID) and of the documented conservative false-FAIL exposure.
6. **Acknowledgement of the residual trust assumptions** (pre-registration §21; G-LOG-0067 §6).
7. **Independence:** the authorization neither requires nor implies Decision A.

Execution then follows the 13 frozen steps of the pre-registration. There is **no re-dispatch** (§18, m6).

---

## 4. Post-E0 decision tree (mapped to the frozen §17 outcomes, not a new classification)

**A correction to the proposed tree.** "E0 valid → S5" overstates what E0 can license:
- §22 states that V1.2.4 authorizes **no** OB0018, production or S5 work;
- F-13(3)–(4) state that an E0 result changes no P3b obligation;
- the instruments enter P3b only via a §26 revision.

So **E0 is not a gate on S5, and a valid E0 does not open S5.** E0 feeds exactly one S5 prerequisite: *heavy-label/load handling* (whether these instruments are fit to be proposed as Phase-1 extraction instrumentation). The S5 floor under the existing P3b procedure depends on the human acts listed in §5, **whatever E0 returns**.

```
E0 result (decide, §17, rules applied in order)
│
├─ NEEDS-REVISION / RUN-INVALID ─────────── "Branch B, integrity"
│    integrity breach (§15/§18); NOT evidence about the instrument
│
├─ NOT-USABLE ───────────────────────────── "Branch B, instrument"
│    completed SEG coverage < 0.95 · SEG quote miss > 5% · κ < 0.40
│
├─ INSTRUMENTS-VALID ────────────────────── "Branch A"
│    all hard conditions and gates met
│
├─ NEEDS-REVISION / INSTRUMENT ──────────── "Branch B, instrument (engineering gate)"
│    SEG exhaustion · SEG schema · coverage < 1 · SEG quote misses · κ < 0.60 · capture < 0.80
│
└─ NEEDS-REVISION / INSTRUMENT-UNDETERMINED  "Branch C"
     κ or capture NOT-COMPUTABLE · reference-extractor quality (X1/F1) · M1 incomplete · non-SEG failure
```

### Branch A: INSTRUMENTS-VALID

| What it establishes | What it does not establish |
|---|---|
| on 6 fixed files and 2 labels: segment coverage = 1.0; zero SEG quote misses; κ_target ≥ 0.60 (agreement, **not** correctness); relative capture_lenient ≥ 0.80 against E1/E2 (**not** completeness) | corpus completeness · semantic or match correctness · model independence · generalization · any strategy claim · any S5, P3b or v3.5 change (§22) |

**Next, all by human act:**
1. **Decide whether to propose adoption** of the instruments as Phase-1 extraction instrumentation, via **one P3b §26 revision** that maps the taxonomy to v3.5 B2 or restricts it to non-contribution use (F-13(4)).
2. **Load handling** uses the adopted instrument for heavy labels.
3. **OB0018** decomposition-fidelity track: authorize and execute.
4. **S5 execution authorization**, per §5.

### Branch B: RUN-INVALID, NOT-USABLE or INSTRUMENT

**Procedure (no automatic repair):**
1. **Freeze the evidence:** commit the run record, `V1-RESULT.json` and the stop report. PX0109 becomes immutable, like PX0106.
2. **Classify the failure** (only the frozen `decide` reasons are used):
   - **RUN-INVALID:** a harness or protocol integrity failure (access, model identity, allowlist, canary, transcript or A-1/A-2, ordering, provenance). **Not evidence about the instrument.** It invalidates the entire run (§17 rule 1).
   - **NOT-USABLE / INSTRUMENT:** evidence *about the instrument*. It invalidates only the instrument claim. SEG-only evidence still counts under an M1 violation (§17).
3. **Minimal-repair test.** A repair is proposed **only if all four hold:**
   - (a) the defect is MATERIAL;
   - (b) it is localized to a named mechanism;
   - (c) a repair leaves the scientific and statistical design unchanged;
   - (d) the human still wants the instrument.

   Otherwise record it, and do not pursue it.
4. **For NOT-USABLE and INSTRUMENT: consider not repairing at all.** The instruments were never a prerequisite for S5 under the existing P3b procedure. A negative result is valid knowledge ("these instruments, at these gates, are not fit"), and it closes the E0 question.
5. **Always immutable:** PX0106–PX0109 artifacts, V1–V1.2.4 tools and pre-registrations, G-LOG entries.

### Branch C: INSTRUMENT-UNDETERMINED (never silently re-labelled VALID or INVALID)

| `decide` reason | What is insufficient | What would resolve it | Cost class |
|---|---|---|---|
| κ NOT-COMPUTABLE (missing or off-scale units; p_e = 1) | the second-matcher sample | a larger or complete M2 sample on the same clusters (new pre-registration) | model only |
| capture NOT-COMPUTABLE (agreed < 20) | the agreed-set size | more files, or a denser label (new pre-registration) | model only |
| capture NOT-COMPUTABLE (extractor incomplete, X6) | a reference extractor's output | re-run that extractor under a new namespace | model only |
| E1/E2 quote misses or schema violations (X1/F1) | reference quality | a stronger reference extractor, or a human reference subsample | model + human |
| M1 incomplete or inconsistent (N1) | the matcher's output | a new M1 run (the matching is sealed per run) | model only |
| non-SEG failure / SEG infrastructure failure | a completed run | re-execution in a new namespace | model only |

**Rule:** an UNDETERMINED result may be followed up only if the specific missing measurement matters for a **pending human decision** (the adoption question in Branch A step 1). Otherwise record it, and route S5 as in §5.

---

## 5. Critical research path (minimal)

```
[human] S5 prerequisites
   load handling (E0 informs) → OB0018 → S5 authorization
   → S5 reconstruction floor: 396 batches, 1,975 labels; 66 hubs ESCALATED (LOAD)
   → S5a/S5b → P3b terminal (§23) → 30-RECONCILIATION → P4
   → P5 validation vector → P6 governance vector → P7 "RECONSTRUCTED, PENDING GOVERNANCE REVIEW"
   → mathematical reconstruction → DDD reconstruction → theory recovery
   → pre-registered adversarial theory testing → canonicalization (governance act only)
```

**E0 is not on the S5 critical path.** It is the decisive input to one prerequisite: **load handling** for heavy labels. R19 holds at every arrow: no later stage substitutes for an unfinished earlier one, and no ML or statistical shortcut declares S5 complete.

## 6. Optional augmentation experiments (triggered, never mandatory gates)

**Decision-relevance test (a formalization of the governing principle).** An augmentation experiment Eₖ may be proposed only if **some possible outcome of Eₖ would change a pending decision** (adopt the instrument? route a label? stop a queue?), or would lower the cost of a decision already on the critical path. If every outcome leads to the same action, the experiment carries zero decision value and is not run.

| Experiment | Trigger (empirical, not calendar) | Decision it informs |
|---|---|---|
| E1 reproducibility (r ≥ 3 repeats) | E0 VALID with a gate margin smaller than the plausible run-to-run variance (e.g. κ ∈ [0.60, 0.70]) | adoption (is VALID robust?) |
| E2 dependence-aware capture–recapture | adoption proposed **and** completeness claims are needed for heavy-label routing. **Not before** the observation mechanism is characterized (E0 + E1) | adoption scope |
| E3 semantic-equivalence gold set | E0 VALID and adoption proposed (κ ≠ correctness) | adoption |
| E4 timed adjudication baseline | the S5 human review load becomes the binding cost | review staffing and ordering |
| E5/E6 ML diagnostics + randomized queue | E3/E4 exist, **and** S5 review volume is large | review ordering |
| E7 relation proposals | P3/P4 relation adjudication becomes the binding cost | relation review ordering |
| E8 augmentation-strategy pilot | the human formalizes F-14 (A7) | strategy |

## 7. ML research layer (preparation only; outside E0; nothing implemented)

**Where ML may enter:** only **after** reliable human-adjudicated evidence exists, and only in the diagnostic layer:

```
SOURCE → deterministic reader → model extraction → schema/provenance validation
→ computational diagnostics → ML diagnostics → disagreement/uncertainty queue
→ HUMAN ADJUDICATION → reconstruction → mathematical / DDD structures → theory candidates
→ adversarial testing → canonicalization
```

**Forbidden paths:** `SOURCE → ML → THEORY` · `SOURCE → similarity → CANONICAL` · `ML output → evidence` · `model confidence → truth` · `graph structure → historical fact`.

**The first legitimate ML use is economic, not epistemic.**
- **Question:** "Can review ordering reach the same evidential reliability with fewer human minutes?"
- **Design** (E6):
  - randomized arms, uncertainty-ordered vs random;
  - **plus a Horvitz–Thompson audit** of the *unreviewed* tail, so that false negatives are estimated, not assumed zero.
- **Uncertainty signals** (observable only):
  - extractor disagreement;
  - matcher disagreement;
  - low similarity;
  - type conflict;
  - weak quote alignment;
  - unusual graph position;
  - unusual cluster size.
- **Calibration and training:**
  - scores are calibrated (isotonic) on adjudicated data;
  - candidate sets carry split-conformal coverage;
  - active learning uses adjudicated labels only;
  - the split is always train → frozen test → held-out audit;
  - no training on model labels.
- **Reporting:** separately, never one score:
  - human minutes;
  - errors found;
  - false negatives (HT);
  - inter-adjudicator agreement;
  - precision/recall against a gold set;
  - compute cost;
  - review reduction versus random.

**ML may:** propose duplicates, paraphrases, omissions, relation candidates and anomalies, and prioritize review. **ML may not:** establish identity, equivalence, a theorem, a DDD boundary or a theory, or canonicalize anything.

## 8. Final statement

**READY FOR HUMAN AUTHORIZATION — STEP 2 AND STEP 3 COMPLETE — E0 NOT EXECUTED**
