# EG-5 specification v3: DELTA ONLY (in_checklist EMPTY labels; the in_checklist data type)

| | |
|---|---|
| Kind | A delta on `EG5-SPEC-A-v2.md` (v1 and v2 are kept unchanged). authority: generated. No repository file is changed. |
| Trigger | An orchestrator counts-only preflight (not re-read here) found 8 EMPTY-path labels in the frame. **2 have slice `in_checklist = true`**, 1 is a hub label, and the canary's EMPTY label is not in_checklist. v2 §2.7 would leave those 2 batches without an object. That means G-02 + R7-R, BATCH-FAIL forever, and **PROGRAM-ACCEPTED unreachable** (A §0: PROGRAM-ACCEPTED := ∀b BATCH-PASS ∧ STATISTICS-VALID). |
| Evidence | rev3 §9C (R3:499-526), §9.8 step 10 (R3:436-438), §B (R3:91), schema E (R3:292) · the gates, from a grep of every verifier module for `checklist`: **only** V:504-509 · probe `probes/probe_eg5_checklist.py` (the synthetic fixture through the production verifier; it prints no S-id and no label name) |
| Replaces in v2 | §2.7's last sentence, Annex E's `checklist_examined` row, refusal R6 (its in_checklist part), HD-4 |

## 1. What `checklist_examined` asserts, and what the gates require (task 1)

**[F] Contract.**
- §9C (R3:501-504): "The checklist is a **cognitive control mechanism, not a documentation obligation**… **No object is required to yield positive answers, and a 'no' is never recorded. Only positive findings are recorded, as research records or timeline content.**"
- R3:506-508: it is applied "**in full, with a short record of which questions were examined**" to the checklist population, which is a mechanical sampling decision (§9C.1, R3:528-533). §9.8 step 10 (R3:436-438) runs the checklist "**over steps 1–8**".
- R3:91 and schema E (R3:292): "full 23 questions and `checklist_examined` only if `in_checklist: true`"; the field is `[int]`.
- So `checklist_examined` asserts **which questions were posed** over the label's steps 1–8. It asserts **no answer and no finding**. Findings live only in research records and the timeline.

**[F] Gates.**
- The only checklist gate in the whole verifier stack is **G-09 at V:504-509**:
  - `slice.in_checklist` truthy → the sorted set of int entries must be exactly 1..23;
  - otherwise a non-empty `checklist_examined` → `SCHEMA … checklist_examined on a label not in the checklist`.
- `checklist_examined` is excluded from the required keys (V:478).
- No gate requires a register record, a timeline point or any positive finding for a checklist label. This is consistent with R3:502-503.
- The R7 modules (universe, witness, evidence, reconstruction, verify) and r5/r6/common contain no checklist rule (grep).

## 2. Options (task 2)

### (i) Vacuous examination, RECOMMENDED

**Rule.** For an EMPTY label with `slice.in_checklist is True`:
- `checklist_examined = [1, …, 23]`;
- `generation_parameters.checklist = "VACUOUS-OVER-EMPTY-REQUIRED-SET"`;
- no register or P1-gap record (v2 §5, EG-5c).

**Truthfulness under §9C.**
- The field records *that each question was posed*, never an answer (R3:506). §9C runs the questions "over steps 1–8" (R3:436-438). For an EMPTY label, steps 1–8 are fully determined by the EMPTY rule: no source, every birth NOT-EVIDENCED-IN-CAPTURE, every absence ESCALATED.
- Posing each of the 23 questions over that fixed content is a finite, completed act, and its outcome is necessarily "no positive finding". A positive finding must be recorded as a research record or as timeline content (R3:503). A research record needs a non-empty `historical_anchor`, i.e. a source (V:976-977; v1 H-3). None exists, because nothing may be read.
- §9C records **no "no"** (R3:502-503), so the object records nothing beyond the examination list. That is exactly what §9C prescribes for a checklist label with no positive finding.
- The disclosure in `generation_parameters` and in the v2.8 sentence (§3) makes the vacuity explicit. The field therefore says no more than "the questions were applied to an empty evidence base". It is true, and it is weaker than any agent examination.
- **What it does not claim:** a reading, a finding, or a judgment about the corpus.

**Limitation (a fact, not a defect of the rule).** The checklist population is a *sampling* instrument (§9C.1). For these 2 sampled labels the full analysis can yield no research signal. That follows from the empty required set, which the frozen plan fixes, and it would hold for any producer. [D] Report it in the S5 results as "2 checklist labels vacuous (EMPTY)", so the checklist yield is not overstated.

**[T] Unchanged production verifier, synthetic fixture** (the EMPTY label's slice set to `in_checklist = true` in the fixture state before materialization):

| Variant | Verdict | Failures |
|---|---|---|
| baseline | BATCH-PASS | — |
| **(i) in_checklist + `checklist_examined` 1..23** | **BATCH-PASS** | — |
| (i) + the EMPTY label's register/P1-gap records removed (EG-5c realism) | **BATCH-PASS** | — |
| in_checklist, field absent | BATCH-FAIL | `G-09 … not the full 1..23` |
| in_checklist, 1..22 | BATCH-FAIL | `G-09 …` |
| not in_checklist, but 1..23 present | BATCH-FAIL | `SCHEMA … checklist_examined on a label not in the checklist` |
| **(ii)** in_checklist, EMPTY object not assembled | BATCH-FAIL | `G-02 object labels differ from the assignment` + `R7-R … no object in the assembled batch` |

### (ii) Refuse and escalate (v2 behaviour): REJECTED

- The 2 batches lack an object, so they are BATCH-FAIL (the table above) permanently.
- Recovery would need one of three things:
  - a frame, plan or sample change, which is frozen (Freeze 1/2);
  - a verifier change (material; reopens G-LOG-0092);
  - a human "accept with named exceptions" (R3 §16.5 item 4). That act is outside the mechanical PROGRAM-ACCEPTED predicate, so the predicate would stay false.
- **Scientific consequence:** PROGRAM-ACCEPTED becomes unreachable by construction, for a reason unrelated to reading quality.

### (iii) Other options: all REJECTED

| Option | Why rejected |
|---|---|
| (iii-a) Give in_checklist EMPTY labels an agent run | It needs a planned run, so a plan change (hash-bound, frozen; UN:440-456). An agent over ∅ could not do more than (i) anyway |
| (iii-b) Omit the field and add a SCHEMA-LIMITATION escalation | G-09 has no escape clause (V:505-507), so it still FAILS. SCHEMA-LIMITATION also needs a register record, which is infeasible (v1 H-3) |
| (iii-c) Verifier exemption: EMPTY ⇒ G-09 not applicable | Material; reopens the accepted verifier. It also contradicts §9C "applied in full" to the sampled population |
| (iii-d) Remove the 2 labels from the checklist population | It changes a frozen sampling artifact (§9C.1 is "never chosen" by the researcher) |

**Recommendation: (i).** It is truthful under §9C's own semantics (a record of examination, never of answers) and disclosed. It passes the unchanged verifier ([T]), keeps PROGRAM-ACCEPTED reachable, and changes no freeze.

## 3. Replacement text and Annex E (task 3)

**v2.8 §2.7:** replace the sentence "An `in_checklist` EMPTY label is not assembled; the orchestrator escalates it." with:

> An EMPTY label whose slice has `in_checklist: true` carries `checklist_examined = [1…23]`: each §9C question is
> posed over steps 1–8 as this rule fixes them, and since §9C records only positive findings and none can exist
> over an empty required set, the object records the examination and nothing else (no register or P1-gap record);
> `generation_parameters.checklist` is `VACUOUS-OVER-EMPTY-REQUIRED-SET`. Any other EMPTY label carries no
> `checklist_examined`.

**Annex E, changed rows:**

| Field | Kind | Value |
|---|---|---|
| checklist_examined | M | `[1,…,23]` iff `slice.in_checklist is True`; otherwise **the key is absent** |
| generation_parameters | K+P | the v2 object, plus `"checklist": "VACUOUS-OVER-EMPTY-REQUIRED-SET"` iff `slice.in_checklist is True` (the key is absent otherwise, so the non-checklist bytes are unchanged) |

**Other changes:**
- The tool's refusal **R6** loses its in_checklist clause. It becomes: a mechanical semantic rule ∉ {ROW-0, ROW-3, ROW-4}.
- **HD-4 is withdrawn** and replaced by HD-4′ (§5).

**Tests** (added to the v2 §7 list):

| # | Test | Expected |
|---|---|---|
| T123 | in_checklist EMPTY label → `checklist_examined` 1..23, `generation_parameters.checklist` set | BATCH-PASS ([T] green) |
| T124 | in_checklist EMPTY, the field absent or 1..22 | BATCH-FAIL G-09 ([T]) |
| T125 | non-checklist EMPTY with the field | BATCH-FAIL SCHEMA ([T]); the tool never emits it (golden) |
| T126 | the manifest entry's `in_checklist[label]` ≠ the slice's `in_checklist` | assembly REFUSED (R13, §4) |
| T127 | dict-membership trap: an entry `in_checklist` dict containing **every** label with value false | no EMPTY object gets the field |
| T128 | byte determinism of both variants; the non-checklist EMPTY object is byte-identical to v2 | pass |
| T129 | EG-5c: a register or P1-gap record naming an in_checklist EMPTY label in another run's file | REFUSED (R3′) |

## 4. in_checklist as a dict, not a list (task 4)

**[F] Two sources exist:**
- **Slice:** `slice.in_checklist` is a **bool** (prepare context, `p3b_s5_prepare.py`:419; the R3:34 slice field). This is what G-09 reads (V:505).
- **Manifest entry:** `in_checklist` is a **dict label→bool** (`p3b_s5_prepare.py`:473), next to the count `checklist` (`:472`). Both are composition keys (`p3b_s5_state.py`:240).

**Audit of v1 and v2.**
- v1 §2 and §3 and v2 §7 always use `slice.in_checklist` as a bool. No list is assumed anywhere, and no text uses `label in entry.in_checklist`.
- There is one latent hazard in the tool design. A check written `lab in entry["in_checklist"]` is **true for every label**, because the dict keys are all the batch's labels. That would give every EMPTY object `checklist_examined` and fail SCHEMA on the non-checklist ones.

**[D] Normative for the tool.**
- The rule input is `slice["in_checklist"] is True` (the hash-checked slice, the same source as G-09).
- Cross-check: when the entry carries `in_checklist`, it must be a dict and `entry["in_checklist"].get(lab) is slice["in_checklist"]`. Anything else → **R13 REFUSED**. Absence of the dict is tolerated, but with a reported note.

**Fixture gap.** `TV.write` builds entries with the count `checklist` but **no** `in_checklist` dict (TV:152-157). Tests T126/T127 must add it through `entry_extra` / `entry_hook` so that the fixture matches the production shape.

**Other consumers.** The preflight's "EMPTY ∧ in_checklist" count must also key on the slice bool, or on `dict[label] is True`, never on dict membership. No verifier code reads the entry dict (grep).

## 5. Human decisions after this delta

HD-1, HD-2, HD-3 and HD-5 of v2 are unchanged. HD-4 is replaced by:

- **HD-4′** Adopt option (i), vacuous examination for in_checklist EMPTY labels, via the §3 sentence inside the same v2.8 rebind, and report "2 checklist labels vacuous (EMPTY)" with the S5 results. **Recommended: yes.** It keeps PROGRAM-ACCEPTED reachable without any freeze or verifier change.

**Traceability:** v2 §2.7, Annex E, R6, HD-4 → superseded here · R3 §9C, §9.8 step 10, §B, schema E · V:478, 504-509 · `p3b_s5_prepare.py`:419, 472-473 · `p3b_s5_state.py`:240 · probe `probes/probe_eg5_checklist.py`.
