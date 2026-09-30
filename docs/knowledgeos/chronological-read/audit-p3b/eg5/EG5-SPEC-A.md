# EG-5 formal specification (Subagent A): the batch assembly `<B>-R7` and the EMPTY-label object

| | |
|---|---|
| Kind | Engineering specification DRAFT. authority: generated. No code, contract, plan, frame or sample is changed. |
| Finding closed (if approved) | EG-5 (`audit-p3b/20260929_S5-CANARY-PRECHECK.json`, findings[0]) |
| New finding raised | **EG-5b**: record identity (`run_id`, `rs_id`, `gap_id`) vs the R7 run grammar (§6.3). It blocks every batch with two or more register-bearing labels, not only EMPTY. |
| Method | Read-only code and contract reading. One run of the existing unit test T38 (it passes). One synthetic mutation of `tests/r7_full_fixture` (§6.3). No production, ledger or corpus file was opened. |

**Citation key.** A = `prompts/20260926_2400_p3b-agent-contract-r7-addendum.md` · R3 = `prompts/20260925_1204_p3b-agent-contract-r2.md` (rev3) · RB = `audit-p3b/20260928_S5-EXECUTION-PACKAGE-DRAFT.md` · V = `scripts/p3b_s5_verify.py` · V7 = `scripts/p3b_s5_r7_verify.py` · UN = `scripts/p3b_s5_r7_universe.py` · WI = `scripts/p3b_s5_r7_witness.py` · RC = `scripts/p3b_s5_r7_reconstruction.py` · R6 = `scripts/p3b_s5_r6.py` (imported by `p3b_s5_r6_verify.py`) · R5 = `scripts/p3b_s5_r5.py` · OR = `scripts/p3b_s5_r7_orchestrate.py` · FX = `scripts/tests/r7_full_fixture.py` · TV = `scripts/tests/test_p3b_s5_verify.py`. **[F]** = fact from code or contract · **[D]** = design proposal · **[H]** = human decision or open question.

---

## 1. What EMPTY means (Q1)

**[F] Derivation.**
- `required(L) = stage2_files(L) ∪ rows(L)`, and `stage2_files` counts as ∅ for a hub label (R6:74-78).
- `dispatch_path(n_files, …) = "EMPTY"` iff `n_files = 0` (R5:56-59).
- `plan_derive` gives an EMPTY label `files = []`, `units = []` and **`runs = {}`** (R6:86-103): an EMPTY label has **no planned run**.
- `plan_derive_v7` inherits this (UN:440-449). `run_owner` therefore has no owner for it (R6:124-126), no I(run) exists, the SLICE-VIEW check skips it (V7:112) and the witness skips it (`if not runs: continue`, WI:405).

**[F] Meaning (R5:333-335, "Item 16 (R17)").** EMPTY is "a label with an empty required set". It resolves NOT-EVIDENCED-IN-CAPTURE and is "distinct from not-read and from not-found". R6:313-314 adds "EMPTY is a classification, not an exemption".

**What EMPTY does NOT mean:**
- **Not "not read".** Not-read applies to a required file, which then needs a CONTRACT-DEVIATION escalation (A §6; R3:14-18). EMPTY has no required file to leave unread.
- **Not "not found".** That is the NOT-FOUND-* gap statuses, the strongest of which is NOT-FOUND-AFTER-CENSUS (R3:863-867).
- **Not absence of the concept.** R3:866-867 (v3.5 R17): never "does not exist".
- **Not GENUINELY-UNDEFINED-AFTER-CENSUS**, even when the stage-1 record is NEGATIVE-CENSUS: that value is forbidden for EMPTY (R5:341-343, `WHOLE_ONLY` R5:296).

EMPTY is a statement about the **plan** (no file was assigned), not about the corpus.

## 2. The object schema the verifier enforces (Q4)

**[F]** An EMPTY label must still have exactly one object in the assembly (V7:165-168: "no object in the assembled batch" → R7-R; G-02 in V:463-467). That object passes **every** object gate. The only paths that skip for EMPTY are:
- claims and evidence;
- the reading rule and census;
- S1;
- the S3-LINT file (V7:179-181 `continue` precedes V7:205).

**[F] Required keys** (V:473-486): all 36 keys of the rev3 schema E `object_record` except `checklist_examined` (R3:258-293). There are no extra keys. The shape checks are list, dict, str or bool (V:247-250).

**[F] Gates that bind an EMPTY object, and the only values that satisfy them:**

| Gate (cite) | Requirement |
|---|---|
| G-07 (V:511-513) | `(run_id, batch_id, contract_sha256, input_manifest_sha256) = (<B>-R7, B, entry.contract_sha256, slice.input_manifest_sha256)`, and the key `generation_parameters` is present |
| G-07 model rule (V:514-515, V:111, `p3b_s5_common.py`:42) | `model_id ∈ {"claude-opus-5-5", "claude-opus-5-5[1m]"}` |
| G-11 / G-05 (V:516-520) | `record_status = PROPOSED`, `proposed_by = AI-AGENT`, `evidence_presentation = V1-PLUS-ROWS` |
| HUB / G-05 (V:497-498, 549-562) | `hub` = slice.hub · `tier` = slice.tier · `pair_breakdown` = Counter over slice `p3a_pairs` of `relationship\|basis` · `tier_causing_pair_ids` ⊆ the slice pairs |
| G-09 (V:500-507) | if slice.in_checklist, then `checklist_examined` = 1..23; otherwise absent or empty |
| G-05 statuses (V:541-553) | `type_status` and `mathematical_status` in the enum. **null only with a SCHEMA-LIMITATION escalation + a SCHEMA-LIMITATION register record** (V:534-537). `primary_layer` in the enum; `secondary_roles` in the enum |
| SEMANTIC (V:626-646) | Tier Z → `semantic_status` null, rule ROW-0, note = `TIER_Z_NOTE`. Tier U → the rule and value equal the slice's `semantic_status_mechanical`, which yields only ROW-0/3/4 (`p3b_s5_prepare.py`:331-339). `semantic_evidence` keys = {d4, d5} |
| births (V:584-590, 135-139) | keys = the 5 KINDS; each value matches a birth pattern. NOT-EVIDENCED-IN-CAPTURE is one of them |
| absences (V:754-778) | keys = exactly the slice's DIMENSION search records. Each has keys {resolution, negative_label, population_basis, supplied_by, reason}, with `negative_label` = the record's `negative_label` and `population_basis` = `c.POPULATION_BASIS` |
| HUB absences (V:780-791) | hub label, hit-bearing dimension → `ESCALATED` plus a `LOAD` escalation on `absences.<dim>` carrying the slice `hub_record` |
| escalations (V:524-531) | keys ⊆ {field, reason, detail, hub_record} ⊇ {field, reason, detail}; reason in the enum |
| G-04 dispositions (V:751-753) | a non-hub label with no own hits expects zero keys, so `stage2_dispositions = []` |
| R7 Σ (UN:220-284), typing (UN:287-301), META rules (UN:304-326) | pass trivially when timeline, summaries, lifecycle and semantic_evidence are empty and `supplied_by` is null (null `supplied_by` under ESCALATED is a META location) |
| EMPTY (R5:333-349; R6:313-321; RC:176-183) | all births NOT-EVIDENCED-IN-CAPTURE · no FOUND or GENUINELY-UNDEFINED-AFTER-CENSUS · an escalation whose `detail` contains `EMPTY-REQUIRED-SET` · no dispositions, timeline points or edges · `U.claims(obj) = ∅` · no later_*/contradicted_by/rejected_by · no superseded sources |
| summary / lifecycle (RC:63-133) | `first_<k>` S-ids = the births' S-ids (both ∅) · "SUPERSEDED" ∈ current_lifecycle ⇔ superseded sources ≠ ∅ |
| S3 (RC:155-173, V7:176) | no S-id range; no pointer or register id in layer A (after normalization); no quarantine hit |
| G-12 / G-01 / AUDIT (V:609-624, 1000-1010) | no register id, pointer, register statement text or F-id; cited S-ids ⊆ slice ∪ reads. **An object with no S-id passes vacuously** |

**[F] Positive control.** FX:123-145 `make_empty` builds exactly this shape and T38 passes (`test_p3b_s5_r7_full.py`:105-109; re-run for this spec: OK). But FX mutates a **historical agent object**. It therefore inherits that object's model_id, generation_parameters, author_role, statuses and tier_causing_pair_ids. **The fixture never models who produces the EMPTY object.**

## 3. Field-by-field construction (Q2, Q3)

[D] `empty_object(batch, label, entry, slice_obj, params)` is a pure function. Every value is exactly one of three kinds:
- (P) a **plan, manifest or slice-derived identifier**;
- (K) a **fixed constant**;
- (M) a **mechanical copy of a slice field**, where the slice is hash-checked through `OR._context` (OR:67-91).

| Field | Kind | Value |
|---|---|---|
| working_label | P | label (manifest entry) |
| batch_id | P | B |
| run_id | P | `f"{B}-R7"` (§6) |
| contract_sha256 | P | `entry.contract_sha256` (V:317, 511) |
| input_manifest_sha256 | P | `slice.input_manifest_sha256`. This is the P3b input-manifest hash, **not** an I(run) `manifest_sha256`; EMPTY has no I(run) |
| hub · tier | M | slice.hub · slice.tier |
| pair_breakdown | M | `dict(Counter(f"{p.relationship}\|{p.basis}" for p in slice.p3a_pairs))` |
| tier_causing_pair_ids | K | `[]` [H-6] |
| semantic_status / _rule / _note | M | Tier Z or mech ROW-0: `null` / `ROW-0` / mech.note (= TIER_Z_NOTE). Tier U ROW-3/4: mech.value / mech.rule / the constant `"slice semantic_status_mechanical (rows 3/4)"`. Anything else → **refuse** |
| semantic_evidence | K | `{"d4": [], "d5": []}` |
| type_status / mathematical_status / primary_layer | K | `UNTYPED` / `UNDECIDABLE-FROM-CORPUS` / `LAYER-UNRESOLVED` [H-3] |
| secondary_roles | K | `[]` |
| births | K | `{k: "NOT-EVIDENCED-IN-CAPTURE" for k in KINDS}` |
| timeline | K | `[]` |
| timeline_summary | K | `first_<k>: null` ×5; later_support, later_refinement, contradicted_by, rejected_by: `[]`; `current_lifecycle: "NOT-EVIDENCED-IN-CAPTURE"` |
| absences | P+K | for each DIMENSION record d: `{resolution: "ESCALATED", negative_label: d.negative_label, population_basis: c.POPULATION_BASIS, supplied_by: null, reason: REASON_K}` |
| escalations | K (+M for hub) | `[{field: "absences", reason: "OTHER", detail: DETAIL_K}]`; for a hub label also one `{field: "absences.<dim>", reason: "LOAD", detail: LOAD_K, hub_record: slice.hub_record}` per hit-bearing dim, sorted by dim (V:659-664 defines hit-bearing) |
| stage2_dispositions · census_reading_disagreements · dependency_edges · superseded_by_sources · hindsight_dependency | K | `[]` |
| status_basis · track_composition | K | `{}` |
| proposed_by · evidence_presentation · record_status | K | `AI-AGENT` · `V1-PLUS-ROWS` · `PROPOSED` (gate-forced; see §5) |
| model_id | K | `"claude-opus-5-5"` (gate-forced; see §5, [H-1]) |
| generation_parameters | K+P | `{"producer": "p3b_s5_r7_orchestrate.assemble", "producer_sha256": <sha of the tool file>, "mode": "DETERMINISTIC-NO-MODEL-CALL", "rule": "EMPTY-REQUIRED-SET (R17)"}` |
| author_role | K | `"ORCHESTRATOR-TOOL"` (unchecked by the verifier) |
| analysis_date | P | the explicit `--date YYYY-MM-DD` argument [H-5] |
| checklist_examined | — | omitted. **slice.in_checklist = true → refuse** [H-4] |

The constants are free of S-ids and pointer words:
- `DETAIL_K = "EMPTY-REQUIRED-SET (R17): the frozen plan derives an empty required set; no file was assigned or read; births NOT-EVIDENCED-IN-CAPTURE, absences ESCALATED"`
- `REASON_K = "EMPTY-REQUIRED-SET (R17): no required file; never FOUND or GENUINELY-UNDEFINED"`
- `LOAD_K = "EMPTY-REQUIRED-SET hub label: stage 2 not performed"`

**Q3: what must stay NOT-EVIDENCED or ESCALATED.**
- Every birth, `current_lifecycle` and every absence.
- There is no quote, no `source_id`, no anchor, no fact and no claim.
- No field names any source. The only non-constant values are identifiers (label, batch, run, hashes), the slice's P3a-derived fields (tier, hub, pair_breakdown, mechanical semantic row) and the stage-1 `negative_label`. Each is a **copy of a frozen upstream artifact**, never a new observation.

## 4. Why the construction cannot manufacture corpus evidence (Q13)

1. **[F]** Evidence in R7 exists only at EVIDENTIARY-typed locations that carry an S-id and resolve to an anchored fact (A §3.0, §3.2, §6).
2. **[F]** The object's discovery set Reach(o) (A §3.1) contains **no S-id string at all**. The only source-key location is `absences.<dim>.supplied_by = null` under `resolution = ESCALATED`, which the registry types **META** ("resolution ∉ {FOUND, GENUINELY-UNDEFINED}", A §3.3 row).
3. **[F]** `U.claims(obj) = ∅` is **checked**: `RC.empty_violations` fails on any claim (RC:176-179). So the verifier itself proves that the object asserts no source-requiring claim.
4. **[F]** The EMPTY path reads no corpus bytes. `need` in V7:79 is built from `slice_required`, which is ∅ for this label, and the tool needs no resolver call for EMPTY.
5. **[D]** The producer reads only the hash-checked plan, the manifest entry and the slice (OR:67-91). An adversarial test with a raising resolver (T-12) proves that no corpus read occurs.

Conclusion: every asserted field is an identifier, a P3a/stage-1 copy, a provenance constant or a NOT-EVIDENCED/ESCALATED marker. The EMPTY object is **evidence-free by construction and by verification**.

## 5. Provenance (Q5)

**[F] Gates** (all are applied to the EMPTY object like any other):
- G-07: `model_id ∈ MODEL_IDS` (V:514-515; V:111) and `"generation_parameters" in o` (V:512, which checks presence only, not content);
- G-05: `proposed_by == "AI-AGENT"` (V:518-520);
- G-11: `record_status == "PROPOSED"` (V:516-517);
- `author_role` and `analysis_date` are unchecked (V:250: no shape is derived for "YYYY-MM-DD").

R3 §18 (R3:1317-1319) defines `model_id` as the "exact model identifier" and `generation_parameters` "as exposed by the runtime". R3 §16 (R3:1276-1277) says AI output enters with `author_role: AI-AGENT`.

**[F] Consequence.** A deterministic, non-model producer **cannot** pass the unchanged verifier with truthful values such as `model_id: "NONE"` or `proposed_by: "ORCHESTRATOR-TOOL"`. The forced values are defensible only in a weak sense: the tool is invoked by the orchestrator session, which runs claude-opus-5-5.

**[D] Without a verifier change:**
- carry the forced `model_id` / `proposed_by`;
- make the truth explicit in the unconstrained fields: `generation_parameters` states producer, tool sha and `DETERMINISTIC-NO-MODEL-CALL`, and `author_role` states `ORCHESTRATOR-TOOL`.

**[H-1]** A truthful exemption would change what the verifier accepts. That needs a **verifier change** (V:514-520, keyed on `plan.path == EMPTY` plus a closed producer value) and **contract text** (R3 §18 / schema E semantics). That is **material** and needs a rebind. Recommended default: the forced values plus the explicit `generation_parameters`, recorded in the governance act as an interpretation of §18 for EMPTY objects.

## 6. Run identity (Q6) and the record-identity defect (EG-5b)

**6.1 [F]**
- A §2.1 (A:115): "`OB####-R7` is the assembly". The namespace admits exactly the planned runs + `<B>-R7` + legacy (UN:475-488).
- EMPTY has no planned run (§1). A label run id for an EMPTY label (`<B>-R7-L##`) would be an unplanned directory, which is R7-U.
- V7:172 `final = None` for EMPTY, so the S3 call is `C.s3_violations(…, final or f"{batch}-R7", …)` (V7:176). The EMPTY object's `run_id` is therefore **`<B>-R7`**, as G-07 also requires (V:331-333, 511).

**6.2 [F]** G-07 compares **every** object's `run_id` to `run = <B>-R7` (V:511), not to the label's final run. It also requires:
- `rs_id = <B>-R7:<B>:<n>` (V:929-930) and `gap_id = <B>-R7:<B>:G<n>` (V:988-989);
- batch-unique ids (V:978-979, 998-999);
- register and gap `run_id = <B>-R7` (V:955-957, 990-992).

**6.3 [F] EG-5b (new finding).**
- R3 (3) (R3:7) tells an agent: "`<run_id>` is the run id you are dispatched with; every path, reader call and record uses it". The dispatched id is `<B>-R7-L##[S]` (OR:109). The rendered prompt does not override this (OR:127-131).
- **Probe** (synthetic mutation of FX): setting one SINGLE object's `run_id` to its dispatched run id turns BATCH-PASS into **BATCH-FAIL `G-07 … provenance ids`**. The positive control passes only because FX:155 rewrites every agent record to `<B>-R7`.
- Independently numbered `rs_id`/`gap_id` from ≥ 2 final runs collide (`…:1` twice) → `G-07 repeated rs_id values`. The FX register records come from a single historical run, so they never collide (23 records over 5 labels).
- **The assembly cannot repair this.** Rewriting ids changes the records that the agent's S3-LINT hash binds (V7:205-207: `records_sha256 = sha(canon([obj]+reg+gap))` of the **assembled** records). It would also make the assembly non-mechanical.
- **[H-2] Resolution options:**
  - (a) An addendum rule for agents: in R7 every record's `run_id` is `<B>-R7`, and `n` is label-scoped, e.g. `n = 1000·L## + k`, which stays digits-only so the verifier is unchanged. This is **material** (it changes agent behaviour) → rebind.
  - (b) A verifier change: accept, per label, the label's final run id with label-scoped uniqueness. This is **material** (it changes what the verifier accepts).

  Either way, the EMPTY object keeps `run_id = <B>-R7`.

## 7. R17 (Q7), S3 (Q8), estimand (Q14)

**Q7 [F].**
- "R17" names two related things. R3:866-867 (v3.5 R17): NOT-FOUND-AFTER-CENSUS is the strongest negative; never "does not exist". The rev-5 act item 16 (R5:25, 333-349) calls the empty-required-set rule R17.
- The EMPTY resolution (NOT-EVIDENCED-IN-CAPTURE / ESCALATED) is **weaker** than NOT-FOUND-AFTER-CENSUS, so it respects the v3.5 bound.
- The rule is carried into R7 as "EMPTY full checks" (A:777) and enforced by RC:176-183. **The R7 contract states the checks, not the producer.**

**Q8 [F/D].**
- The verifier runs S3 on `[obj] + reg + gap` of the EMPTY label (V7:175-176). For EMPTY, reg = gap = ∅ by construction.
- S3 holds for the constants of §3: no S-id, no range, no pointer word ("register", "rs id", …: RC:32-33), no `B:<n>` / `B#<n>`, and no quarantine hit. The label is a frame label, and the quarantine counts hold-out names only (`p3b_s5_common.py`:207-215).
- [D] The tool re-runs `C.s3_violations`, `U.validation/typing/meta_violations` and `C.empty_violations` on each constructed object as a pre-write self-check (refuse on any). It writes **no S3-LINT** for EMPTY: V7 never reads one for EMPTY.

**Q14 [F].**
- The estimands are over labels: frame = plan labels; strata keys include EMPTY (A §8, A:590-591).
- Strata come from `assign_strata`: HUB takes precedence over EMPTY (R6:381-391). The **EMPTY stratum = non-hub EMPTY-path labels**; a hub EMPTY label is in HUB.
- RB §4 (RB:77) lists 7 EMPTY audit units.
- The construction changes no plan, frame, stratum, sample or formula. It only supplies the object the frozen plan already requires but assigns to no run.
- An agent producer would require a planned run, i.e. a plan change, which is excluded. The deterministic producer is therefore the **only** producer compatible with the frozen plan.

**[H-7] To check, since B1 v2 and the Freeze-2 record are outside this spec's read set:**
- For EMPTY labels, θ_D ("independent re-analysis under the same protocol", RB:77) compares two applications of one deterministic rule. θ_D on the EMPTY stratum then measures plan/rule reproducibility, not agent reliability.
- The human should confirm that B1 v2 / Freeze 2 intend exactly this.
- No wording change to the estimands is proposed.

## 8. Witness placement (Q9)

**[F] What the witness checks.**
- It consumes from the orchestrator transcript **only** Agent calls (WI:109-134) and Bash calls whose command matches `^: S5-ORCH (UNITS-VALIDATED|ASSEMBLED|FINAL-VALIDATED) batch=B( label=L)?;` (WI:42, 139-147).
- Each marker becomes an `orchestrator` record in WITNESS.jsonl (WI:235), with `printed_record_hashes` parsed from `^<sha256>  <path>$` output lines (WI:43, 146).
- It checks:
  - both markers exist (WI:439-440);
  - ASSEMBLED.t_result ≤ FINAL-VALIDATED.t_call (WI:441-442);
  - produced(L) ≤ ASSEMBLED.t_call for each read label (WI:436-437).
- Printed hashes are compared with current files **only for UNITS-VALIDATED** (WI:428-433). The last ASSEMBLED marker is used (WI:401).
- **Any other orchestrator tool call yields no witness record.** It survives only as raw bytes in the archived `main.jsonl`, which is digest-frozen by `W.digests` (WI:451-460). A separate `python3 … assemble` call between the markers would be **an unwitnessed write** at the WITNESS.jsonl level.
- The trust table (A:92) trusts the orchestrator's "dispatch acts, freeze commits" and lists objects as untrusted. So the assembled files are **verified, not trusted**, like agent objects.

**[D] Runbook step 6 (replaces RB step 6):**
```
: S5-ORCH ASSEMBLED batch=B; python3 -B <CR>/scripts/p3b_s5_r7_orchestrate.py assemble B --date YYYY-MM-DD
: S5-ORCH FINAL-VALIDATED batch=B; python3 -B <CR>/scripts/p3b_s5_r7_orchestrate.py assemble B --date YYYY-MM-DD --check
```
- The assembly runs **inside** the ASSEMBLED marker call. Its stdout (`<sha256>  <relpath>` for every input and output file) becomes the marker's `printed_record_hashes`, frozen in WITNESS.jsonl.
- `--check` re-derives without writing and prints identical lines, which gives idempotence evidence under FINAL-VALIDATED.
- **No witness code change is needed**: the regex accepts text after `;`.
- [D] `freeze-final` additionally refuses if the last ASSEMBLED record's printed hashes for the three output files ≠ the current files (a tool-side check).
- [H-8] Making this a verifier check (like UNITS-VALIDATED, WI:428-433) would change what the verifier accepts. That is a verifier change: optional, not needed for soundness, human choice.

## 9. Assembly semantics (Q10, Q11, Q12)

**Inputs [D].**
- `OR._context(B)`: the manifest entry, the revision binding, the plan hash = `r7_plan_sha256`, and every slice hash (OR:67-91).
- For each label in **manifest order** (`entry.labels`; L## = 1-based position, A:115, R6:90-91):
  - **SINGLE or DECOMPOSED**: final run F = the unique run with role SINGLE or SYNTHESIS (V7:172). Read `ledger-p3b-r2/F/objects.jsonl`, `register.jsonl` and `p1-gap.jsonl`. No other file is read (no claim-evidence, S3-LINT, records or READ-LOG).
  - **EMPTY**: construct §3. Read no ledger file.

**p1-gap name [F].**
- Agents may write only `p1-gap.jsonl` (A:411; UN:53, 694-697; rendered prompt OR:127-128; FX:221).
- The verifier reads `<B>-R7/p1-gap-capture.jsonl` (V:14, 404, 410; TV:165).
- R3:98 (historical rev3) names `p1-gap-capture.jsonl` in the run's own directory. For R7 this is superseded by A §5.5 (the addendum governs where it speaks; R3 governs the rest).
- [D] The assembly is the **sole** renaming point: it reads `F/p1-gap.jsonl` and writes `<B>-R7/p1-gap-capture.jsonl`. It **ignores** any `p1-gap-capture.jsonl` in a run directory. Such a file would be a W8 violation that the witness already fails (WI:327-328); the tool reports its count.

**Parsing [D].**
- UTF-8. Every non-blank line is one JSON **object**. Parse with an `object_pairs_hook` that rejects duplicate keys (json.loads would silently apply last-wins, against A §3.1 "no last-wins"). Reject NaN and Infinity.
- Blank lines are skipped, as `read_jsonl` does. The canonical re-serialization is value-lossless.

**Refusals (exit 2; nothing written) [D]:**
- (R1) any `_context` refusal;
- (R2) F's `objects.jsonl` absent;
- (R3) `objects.jsonl` does not hold exactly one object, or its `working_label` ≠ the plan owner;
- (R4) an unparsable line, a non-object line or a duplicate key in any input;
- (R5) a directory `<B>-R7-L##*` exists for an EMPTY label;
- (R6) an EMPTY slice is `in_checklist` [H-4], or its mechanical semantic rule ∉ {ROW-0, ROW-3, ROW-4};
- (R7) the self-check of §7 Q8 fails;
- (R8) an existing assembly output ≠ the re-derivation (write-once);
- (R9) `--check` finds a difference.

**Pass-through (the verifier owns these; the assembly never judges or repairs):**
- register or gap content;
- `working_label` of register/gap records;
- rs_id/gap_id form and uniqueness (EG-5b);
- a record count per label.

**Concatenation [D].**
- `objects.jsonl` = one line per manifest label, in manifest order (the EMPTY object at its position).
- `register.jsonl` / `p1-gap-capture.jsonl` = the final runs' records, label blocks in manifest order, **file order preserved within a block**.
- Within-block order is **load-bearing [F]**: V7:160-175 rebuilds `[obj] + reg_by[lab] + gap_by[lab]` in assembly-file order and compares its canonical hash with the agent's S3-LINT (V7:205-207).
- An absent `register.jsonl` / `p1-gap.jsonl` = zero records, reported as `ABSENT <path>` [H-9]. Its last-witnessed-Write equality is enforced separately by W5 (WI:338-341).

**READ-LOG [F/D].**
- The R7 path reads each planned run's own READ-LOG (V7:156-159). An assembly READ-LOG is optional (V:404-411) and unused beyond parse checks.
- The assembly writes **none** (the fixture's empty file, TV:165-167, is incidental).

**Outputs and bytes [D].**
- Always write the three files `ledger-p3b-r2/<B>-R7/{objects,register,p1-gap-capture}.jsonl`.
- Each line = `U.canon(record)` (sorted keys, `(",", ":")`, `ensure_ascii=False`, UTF-8; UN:64-65) + `"\n"`. Zero records → a zero-byte file. No BOM, no trailing blank line.
- The output is a pure function of (plan, entry, slices, final-run files, `--date`, tool sha). There is no clock, environment, locale or dict-order dependence.
- Write atomically: validate everything, then write each file as tmp → rename. Idempotent: byte-equal existing files → no write, same printed lines.

**Stdout [D].**
- `<sha256>  <relpath>` for the plan, each consumed input (sorted by path) and each output.
- Then the summary line `S5-ORCH assemble B: labels=… empty_objects=… objects=… register=… p1_gap=… absent_inputs=… ignored_capture_files=…`.
- Counts only; no content, no S-id.

## 10. Contract change? (Q15)

**[F] What is already implied.**
- The assembly run `<B>-R7` (A:115), the freeze "at assembly" (A:352, 441), the ASSEMBLED stage (A:576), the verifier's reading of `<B>-R7/{objects,register,p1-gap-capture}.jsonl` (V:14, 404) and "EMPTY full checks" (A:777).
- The runbook step exists (RB step 6).
- **Unstated:** who produces the EMPTY object, its constants, the p1-gap rename and the concatenation order.

**[D] Assessment.**
- The EMPTY rule plus the mechanical assembly **changes no agent behaviour**: no agent run exists for EMPTY, and the agents' permitted writes are unchanged. It **changes nothing the verifier accepts**: the object must pass every existing gate.
- It is therefore **non-material**: a specification of an orchestration step the contract already implies.
- It can be recorded as a governed engineering specification: this document, a developer guide and a governance-log act, by the EG-1 precedent (a tool authorised without contract text, RB §5.2).
- That route avoids any addendum edit. **Any** addendum text change alters its sha256, which is bound in production (the manifest `contract.sha256` checked by UN:490-500; the I(run) ADDENDUM entries; precheck `contract.sha256`). It would require a rebind like EG-4: a human gate.

**[H] The material items that would force a rebind or a verifier change:**
- EG-5b (H-2) — needed anyway;
- a truthful provenance exemption (H-1);
- a verifier check of the ASSEMBLED hashes (H-8).

**Recommendation:** if H-2 leads to an addendum v2.8 + rebind anyway, fold a short normative EG-5 paragraph (the producer, the §3 constants by reference and the rename) into the same rebind. Otherwise keep EG-5 at the specification level.

## 11. Tool interface (Q16)

```python
def assemble(batch: str, root: str = CR, analysis_date: str | None = None, check: bool = False,
             resolver_factory=None) -> dict
    # -> {"labels": int, "empty_objects": int, "objects": int, "register": int, "p1_gap": int,
    #     "absent_inputs": int, "ignored_capture_files": int, "printed": [(sha256, relpath), ...]}
    # Refused on R1–R9 (§9); never reads corpus bytes; resolver_factory exists only so tests can prove it is unused.
def empty_object(batch, label, entry, slice_obj, analysis_date, tool_sha256) -> dict   # pure; §3
```

**CLI:** `assemble B --date YYYY-MM-DD [--check] [--root DIR]`. It is added to `main` (OR:300-337) under the same `verify_frozen` / `assert_sealed` guard. The batch id must match `OB\d{4}` and the date must match `\d{4}-\d{2}-\d{2}`.

**Tests, RED first** (new `tests/test_p3b_s5_r7_assemble.py`; synthetic fixtures only; temp dirs):

| # | Test | Expected |
|---|---|---|
| T-01 | FX batch where `assemble` (not `T.write`) produces the assembly from `run_outputs` + plan | the production verifier gives BATCH-PASS (T38 re-expressed through the tool) |
| T-02 | `empty_object` field table (§3) as a golden dict; Tier Z and Tier U ROW-0/3/4 variants | exact equality; passes `C.empty_violations`, Σ/typing/META and S3 |
| T-03 | run `assemble` twice; vary cwd, TZ, locale and PYTHONHASHSEED | byte-identical outputs and stdout |
| T-04 | re-run on identical existing outputs | no write (mtime unchanged), same printed lines |
| T-05 | an existing output altered by one byte | Refused (R8); files untouched |
| T-06 | a final `objects.jsonl` absent · 0 lines · 2 lines · wrong working_label | Refused (R2/R3); nothing written |
| T-07 | an unparsable line · a JSON array line · a duplicate key · NaN | Refused (R4) |
| T-08 | register/p1-gap absent vs empty vs multi-record | zero records / zero / order preserved per block; manifest-order blocks |
| T-09 | a stray `p1-gap-capture.jsonl` in a run directory | ignored, counted; the output comes from `p1-gap.jsonl` only |
| T-10 | S3-LINT binding: a permutation of one label's register lines | assembly preserves the order; a permuted assembly → verifier R7-R S3 fail |
| T-11 | plan or slice hash mismatch | Refused (R1) |
| T-12 | adversarial: the resolver factory raises on any call; the corpus root is absent | `assemble` succeeds (no corpus read) |
| T-13 | adversarial: a planted `<B>-R7-L##` directory for the EMPTY label | Refused (R5) |
| T-14 | adversarial: an agent's objects.jsonl carries an object for the EMPTY label | Refused (R3) |
| T-15 | per-constant negatives through the verifier (a birth → ESTABLISHED; an absence → GENUINELY-UNDEFINED; one timeline point; EMPTY escalation removed; one S-id in the detail) | BATCH-FAIL, R7-R or R7-U as owned |
| T-16 | an `in_checklist` EMPTY slice; mech rule ∉ {ROW-0/3/4} | Refused (R6) |
| T-17 | hub EMPTY slice with hit-bearing dims | LOAD escalations carry `hub_record`; verifier HUB gates pass (only if H-4b is approved; otherwise Refused) |
| T-18 | static: the constants contain no `\bS\d{4}\b`, no POINTER7/POINTER match, no `B:<n>` | pass |
| T-19 | witness integration (synthetic transcript): the ASSEMBLED marker command runs the tool | the orchestrator record's `printed_record_hashes` = the output hashes; `freeze-final` refuses after an assembly file changes |
| T-20 | `--check` | writes nothing (filesystem snapshot equal); exit 2 on any difference |
| T-21 | EG-5b documentation: two SINGLE labels each with register `…:1` | assembly passes them through unchanged; verifier FAIL `G-07 repeated rs_id` (it stays RED until H-2 is decided) |

## 12. Open questions / human decisions

- **H-1** Provenance: keep the gate-forced `model_id = claude-opus-5-5` and `proposed_by = AI-AGENT` with an explicit `generation_parameters` / `author_role` (non-material), or authorise a truthful exemption (verifier + contract, material, rebind).
- **H-2 (EG-5b, blocking for the canary independently of EMPTY)** Record identity: R3 (3) makes agents write their dispatched run id, but G-07 requires `<B>-R7` and batch-unique `rs_id`/`gap_id`. Choose (a) an addendum agent rule or (b) a verifier change. Both are material.
- **H-3** Status constants for EMPTY: `UNTYPED` / `UNDECIDABLE-FROM-CORPUS` / `LAYER-UNRESOLVED`. The null + SCHEMA-LIMITATION route is infeasible: its register record needs a non-empty `historical_anchor` (V:976-977), which requires an S-id.
- **H-4** An `in_checklist` EMPTY label cannot truthfully carry `checklist_examined = 1..23` (V:503-507): refuse and escalate. **H-4b:** support hub EMPTY labels (a mechanical LOAD derivation) or refuse them. A counts-only preflight over the frame should report how many path-EMPTY labels are hub and how many are in_checklist.
- **H-5** `analysis_date`: an explicit argument (proposed), or a fixed frozen date (for example the activation date) for full input-independence.
- **H-6** `tier_causing_pair_ids = []` passes (a subset check, V:561-562). Confirm, or name the slice field it should copy.
- **H-7** θ_D on the EMPTY stratum measures rule/plan reproducibility. Confirm against B1 v2 / Freeze 2 (not read here).
- **H-8** Optional verifier check of the ASSEMBLED printed hashes (material if adopted).
- **H-9** An absent `register.jsonl` / `p1-gap.jsonl` = zero records (proposed), or require the agent to write empty files. The latter is a prompt/contract change.
- **H-10** Correct RB step 6: the assembly does **not** include claim-evidence or S3-LINT. The verifier reads them from the final run directories (V7:193, 205).

**Traceability:** EG-5 (S5-CANARY-PRECHECK) · A §2, §3, §5.5-§5.8, §7, §8, §13 · R3 §C, §E, §11.4, §13.4, §16, §18 · R5 item 16 (R17) · R6 R5-08 · RB §1 step 6 · EG-1 (G-LOG-0104) · EG-4 rebind precedent (G-LOG-0105).
