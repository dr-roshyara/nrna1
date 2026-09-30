# P3b S5 AGENT CONTRACT — REVISION 7 ADDENDUM (§26 change control; G-LOG-0085, G-LOG-0086)

**Status: R7 v2.8 — ACCEPTED R7 (G-LOG-0092) + v2.5-S statistics (G-LOG-0093; §8, DR-19) + v2.6-DC3 decided binary files (G-LOG-0099; §6.1, DR-20) + v2.7 SLICE-VIEW (G-LOG-0104; §5.9, DR-21) + v2.8 record identity, assembly/EMPTY, attempts, hub view lines (G-LOG-0106; §2.6–§2.8, §5.9a, Annex E, DR-22).** v2.7 sha256 `9523c712efd8c0e67d701b7e0003b85bf3f683e457d2b15aa2d6e1a3b854b367`. v2.6 + AF-1 sha256 `4916932802ca9acf68cee404aea6c0a7a725f2874df275386c64a3f7ac155e6e`. v2.5 sha256 `63fdda65b02a2b9822984edfac2ab9a65136fe2b7889c2ab53611181072ad939`. v2.4 (G-LOG-0089, sha256 `fa837177dc40fe48f15247b2f00e25c456e3dcd93b9ad182fafc5fa34b979674`) was a frozen contract amendment on top of v2.3 (DESIGN FROZEN, G-LOG-0088; sha256 `cdbf53cbe531cc92dc5decc9b29496188b9b6e7ffc0e2ec875bc5d4dc4a6f211`, `05569322d`). v2.4 adds the NDB-1/DC-1 registry rows and INV-LEX (§3.0, §3.3, DR-18); nothing else changes. It includes DR-15/16 and FR-1 closed by Option A (DR-17, G-LOG-0087). v2.4 + F-01 were independently audited (G-LOG-0090/0091) and **ACCEPTED (G-LOG-0092)**; the v2.5-S statistics amendment is engineering-verified only (exhaustive coverage checks). **NOT ACTIVATED.** Any change after this point is a contract change needing a human act.
- PF-1…PF-8 (material) and PF-9…PF-14 (non-material) of `audit-p3b/20260926_R7-PREFREEZE-DESIGN-REVIEW.md` are applied under G-LOG-0086.
- v1 is superseded: sha256 `4f31d393…4c704`, commit `c453b1b2b`.
- **Not frozen, not implemented, not activated.** S5 execution, dispatch and corpus reading are **NOT AUTHORIZED**.
- Every semantic change made by the repair is recorded as a design decision **DR-nn** (§16).
- The repair was designed by the same agent that authored v1 and the pre-freeze review, so **independent validation is still required (HD-9).**

**Supersedes:**
- Revision 6 (`prompts/20260926_2300_p3b-agent-contract-r6-addendum.md`, code `bc6fb01bb`). It was never activated, the re-audit (G-LOG-0084) found it NEEDS-REVISION, and it stays **an immutable audited object** (HD-8).
- Revision 5 is withdrawn.
- Items of revisions 5 and 6 carry over **only where §13 says so**. Revision 3 governs everything the addenda do not change.

**Evidence basis:**
- the re-audit;
- the root-cause analysis (`audit-p3b/20260926_REVISION-6-REAUDIT-ROOT-CAUSE-ARCHITECTURE.md`);
- the Model-D readiness result;
- the pre-freeze review;
- HD-1…HD-9 (G-LOG-0085) and G-LOG-0086.

**Trust-root decision:** ADR-R7-01 (`audit-p3b/20260926_ADR-R7-EXECUTION-TRUST-ROOT.md`).

## 0. Verdicts: a composition of context predicates (PF-13; DR-15)

**Predicates:**

| Letter | Predicate |
|---|---|
| **U** | UniverseValid |
| **W** | WitnessValid |
| **E** | EvidenceValid |
| **R** | ReconstructionValid |
| **S** | StatisticsValid |

**There is no bare "PASS" verdict in R7.** Three verdict layers are kept apart, each with a closed value set:

| Layer | Definition | Values |
|---|---|---|
| **Batch** (per batch; the verifier) | **BATCH-PASS := U ∧ W ∧ E ∧ R** | `BATCH-PASS` · `BATCH-FAIL` · `BATCH-UNDETERMINED` |
| **Statistics** (programme; `estimate_v7`, §8) | **STATISTICS-VALID := S** | `STATISTICS-VALID` · `STATISTICS-NOT-ESTIMABLE` · `STATISTICS-REJECTED` |
| **Programme** (mechanical) | **PROGRAM-ACCEPTED := (∀ b ∈ Batches(frame): BATCH-PASS(b)) ∧ STATISTICS-VALID** | true / false |

**Batch verdict: three-valued conjunction.**
- Each context predicate evaluates to T, F or U. U means the verifier's own required input is unavailable and no violation is shown; for example, an absent witness archive gives `UNVERIFIABLE-WITNESS`.
- The batch verdict is the strong-Kleene conjunction:
  - **BATCH-FAIL** if any predicate = F;
  - otherwise **BATCH-UNDETERMINED** if any = U;
  - otherwise **BATCH-PASS**.
- A failure is never masked by an undetermined predicate. U is never BATCH-PASS.

**Statistics verdict:**
- `STATISTICS-REJECTED` = invalid or tampered input.
- `STATISTICS-NOT-ESTIMABLE` = valid input, but **any** component of the frozen deliverable (τ̂, V̂, the exact upper bound U; v2.5-S) is mathematically undefined. Defined components may be reported as information only. The normal CI is conditional (§8) and never a NOT-ESTIMABLE trigger.
- `STATISTICS-VALID` = the full frozen deliverable is defined.

**Batch validity never implies statistical validity, and statistical validity never implies batch validity.**

**Governance acceptance is not a predicate.**
- PROGRAM-ACCEPTED is the mechanical precondition for, and never the act of, governance acceptance or canonicalization. Those remain human acts recorded in the governance log.
- The layers are distinct: OBSERVATION (Witness) ≠ EVIDENCE (Evidence) ≠ RECONSTRUCTION (Reconstruction) ≠ STATISTICAL VALIDITY (Statistics) ≠ GOVERNANCE ACCEPTANCE (human).

> **The verifier is a composer of predicates, not a source of truth.** It evaluates the context predicates below and composes them by the rules above. It contains **no epistemic rule of its own**. Every rule lives in exactly one bounded context. The verifier's output schema carries only the closed verdict values and the per-predicate failure tags.

| Context | Question | Predicate | Owns |
|---|---|---|---|
| **Universe** | What is allowed? | UniverseValid | the plan (derivation and hash), the run namespace and legacy digest, the revision/contract binding, the closed object schema Σ, the typing registry, the consumer read sets, the agent tool-capability grammar (W8's allowed set), the permitted write paths, **the run input manifests I(run) (`RunInputManifest`)** |
| **Witness** | What happened? | WitnessValid | the neutral transcript syntax decoder (PF-9), the dispatch binding, W1–W5, W7, W7a, W8 (evaluation), the derived stages, `WITNESS.jsonl`, the freezes |
| **Evidence** | What source bytes were observed and cited? | EvidenceValid | coverage from the witness, reading state, byte-exact fact anchoring (§6), claim → fact resolution, and W6 reconciliation of agent artifacts (READ-LOG, records, facts, claim-evidence) against the witness |
| **Reconstruction** | What follows, and is it well-formed? | ReconstructionValid | S1–S3, EMPTY rules, the summary relations (§4.1), contradiction relations and precedence (§4.2), the lifecycle (§4.3), edge classes, S2 |
| **Statistics** | Is the mathematical question defined? | StatisticsValid | `estimate_v7`, the domain D, the frozen record (§8) |

**Dependencies** (read-only, acyclic):
- Witness → Universe;
- Evidence → Witness, Universe;
- Reconstruction → Evidence, Universe;
- Statistics → Universe;
- Verifier → all.

No reverse dependency.

**Failure tags = predicate letters:**
- `R7-U`, `R7-W`, `R7-E`, `R7-R` name the predicate that is F (or U) in a batch verdict.
- `R7-S` names a statistics outcome other than STATISTICS-VALID.
- BATCH-PASS requires no batch tag. `UNVERIFIABLE-WITNESS` makes W = U, hence BATCH-UNDETERMINED.
- *(The closed object schema is written Σ, never S.)*

## 1. Trust boundary, preservation and truth strata (HD-2; PF-11; PF-12)

| Trusted (not verified) | Untrusted (verified) |
|---|---|
| the harness at observation time (transcripts, timestamps, tool-use counts, subagent metadata) · the orchestrator (dispatch acts, freeze commits) · the OS account · the seal-aware resolver's bytes · the frozen 02-FILES · git history after a freeze commit | every agent-authored artifact (READ-LOG as evidence, records, facts, claim-evidence, objects, register and P1-gap records, S3-LINT, RUN-MANIFEST) · every declared time |

**Preservation is not trust (PF-11).**
- The external transcript archive is **untrusted storage**. Its content is accepted only when it matches the digests in a freeze commit.
- Its purpose is reproducibility and witness re-derivation.
- Trust = harness observation + freeze digests/commit.

**Truth strata (PF-12):**

| Stratum | Authority | Model D covers it? |
|---|---|---|
| observation | harness transcript | root |
| execution witness | `WITNESS.jsonl` (derived) | **yes** |
| source bytes | resolver | independent root |
| semantic evidence (does a quote support a claim?) | human review | **no** |
| reconstruction interpretation | protocol + human adjudication | **no** |

**Verifier resolver reads (FREEZE clarification, G-LOG-0088):** verifier resolver reads are integrity-verification operations only; they produce no corpus observation, Evidence, Reconstruction, or Theory object. The verifier reads source bytes through the seal-aware resolver only to recompute page hashes, check byte-offset quote anchoring and check integrity. It obeys the hold-out and seal rules, and it creates or modifies no Evidence, Reconstruction, Theory object or source observation.

> **EXECUTION TRUTH ≠ SEMANTIC TRUTH.** Model D proves execution observability. Joined with the resolver, it proves observation of bytes. It does **not** prove semantic entailment.

## 2. Run universe and revision binding (Universe)

1. **Run grammar:** `OB####-R7-L##` (SINGLE), `OB####-R7-L##U##` (unit), `OB####-R7-L##S` (synthesis); `OB####-R7` is the assembly. `L##` is the label's 1-based manifest position. Byte mode is mandatory.
2. **Plan:** `<slice_root>/<batch>.R7-PLAN.json` must equal `plan_derive_v7` (manifest label order, hash-verified slices, resolver bytes, 02-FILES), with sha256 = the manifest entry's `r7_plan_sha256`. Carried over from R6 L (closed by the re-audit). Runs(P), owner, role and perm come from the plan only.
3. **Namespace totality:** every ledger directory named `<batch>-*` must be a planned run, the assembly, or a member of Legacy(B). Legacy(B)'s digest is frozen at activation as `legacy_ledger_sha256`, and re-frozen only by a governed retirement (§2.8, v2.8). Anything else, and any legacy change, is `R7-U`.
4. **Reader default-deny** (HD-4):
   - For a batch whose manifest entry carries `r7_plan_sha256`, the reader refuses every non-R7 grammar.
   - It reads the plan from the verifier's path and checks its hash against the manifest (closes m8).
   - A rerun is an attempt under §2.8 (v2.8). This replaces the v2.7 sentence "A rerun needs a new governed run, a new plan and a human act"; the default-deny is unchanged.
5. **Revision binding:** `contract.revision` must be the integer 7, and `contract.sha256` must be the sha256 of this addendum as frozen. Anything else is `R7-U`, never an exception (closes TB5 and TB6).

### 2.6 Record identity (v2.8(a), EG-5b, G-LOG-0106; DR-22)

In every object record, research-register record and P1-gap record an agent writes:
- `run_id` is the assembly id `<batch>-R7`;
- `rs_id` is `<batch>-R7:<batch>:<n>` and `gap_id` is `<batch>-R7:<batch>:G<n>`, with **n = 1000·L + k**. L is the label's 1-based manifest position; k (1…999) is the record's position among that label's records of the same kind in the run's file.

This replaces rev3 item (3) **for these three fields only**. The run's ledger directory, the reader's `--run`, the READ-LOG, the `run` of every claim-evidence reference, I(run) and the binding line use the dispatched run id. The dispatch prompt states the concrete values (the RECORD IDS block). An `n` outside the label's range fails the batch: the assembly refuses.

### 2.7 Assembly and the EMPTY object (v2.8(b)(c), EG-5, G-LOG-0106; DR-22)

**Where the assembly runs.** The orchestrator tool writes the assembly `<batch>-R7` **inside the `ASSEMBLED` marker command** (§5.6), and re-derives it without writing inside the `FINAL-VALIDATED` marker command. Both print `sha256  path` for every input and output.

**What it produces.** It is mechanical:
- `objects.jsonl`: one object per manifest label, in manifest order;
- `register.jsonl` and `p1-gap-capture.jsonl`: the final runs' `register.jsonl` and `p1-gap.jsonl` records, in manifest-label blocks, file order kept;
- canonical JSON lines throughout.

It never edits, filters, renumbers or repairs a record.

**Refusals (the batch FAILS):** a missing or multiple object; a record naming another label; an unparsable or duplicate-key line; an `n` outside its range; an existing assembly that differs. claim-evidence and S3-LINT stay in the final runs' directories.

**EMPTY object.** For a label whose plan path is EMPTY (no run), the assembly writes the object of **Annex E** (`EMPTY-OBJECT v1`):
- every birth and `current_lifecycle` is NOT-EVIDENCED-IN-CAPTURE;
- every absence is ESCALATED, with the stage-1 `negative_label`;
- one escalation `OTHER` names EMPTY-REQUIRED-SET, plus a `LOAD` escalation per hit-bearing dimension of a hub label;
- the Annex E status constants;
- no source, quote, claim, timeline point, edge, disposition, register record or P1-gap record.

**Who proposes it.** The orchestrator agent:
- `model_id` is the orchestrator's served model, equal to the batch's agents' model;
- `proposed_by` is AI-AGENT;
- `generation_parameters` names the tool, its sha256, the rule `EMPTY-OBJECT v1` and the mode `DETERMINISTIC-NO-MODEL-CALL`.

Its content is fixed by this rule. The rule is part of the protocol, so an independent same-protocol re-analysis (θ_D) of an EMPTY label applies it. The EMPTY stratum's θ_D is a rule-determinism check, reported separately.

**In-checklist EMPTY labels.** An EMPTY label whose slice has `in_checklist: true` carries `checklist_examined = [1…23]`:
- each §9C question is posed over steps 1–8 as this rule fixes them;
- §9C records only positive findings, and none can exist over an empty required set, so the object records the examination and nothing else (no register or P1-gap record);
- `generation_parameters.checklist` is `VACUOUS-OVER-EMPTY-REQUIRED-SET`.

Any other EMPTY label carries no `checklist_examined`. The in-checklist test is the slice's `in_checklist is true`, cross-checked against the manifest entry's value, never its key membership.

### 2.8 Attempts of a failed batch (v2.8(d), EG-6, G-LOG-0106; DR-22)

**Attempts.** A batch whose current attempt ends FAILED or INCOMPLETE may be run again as **attempt m+1 of the same runs**: same plan, slices, views, run ids, prompts and record identity (§2.6).

**Retirement of attempt m, before attempt m+1:**
- every planned run directory and the assembly move, unaltered, into `ledger-p3b-r2/<batch>-R7.A<m>/`;
- the transcript archive moves to `<archive>/<batch>.A<m>/`;
- the manifest entry's `legacy_dirs` and `legacy_ledger_sha256` are re-frozen (§2.3);
- nothing is deleted.

The retirement is crash-safe:
- an intent record (all sha256s) precedes every move, and every moved byte is verified against it;
- a completion record closes it;
- every rename stays within its own root, and a device mismatch or EXDEV is refused and resumable;
- no attempt starts while a retirement is PENDING or `<archive>/<batch>/` is non-empty.

Each attempt uses a fresh orchestrator session; its transcripts must not reuse a retired digest.

**Policy (pre-registered, fixed before the canary):**
- **Trigger.** An attempt is triggered only by a verifier BATCH-FAIL or an INCOMPLETE dispatch, and only **before** any VERIFIED entry of the batch. A BATCH-PASS batch is never run again.
- **Cause class,** derived mechanically from the verifier's failure strings by the frozen mapping (precedence S > D > H > X > A1 > A2; unrecognized → D; UNVERIFIABLE-WITNESS alone → U):
  - X (instrument, environment, runbook), A1 (no admissible measurement) and A2 (a measurement rejected by a content or consistency gate) may be re-run;
  - D (design), S (seal, tamper, replay) and H (a §21 FAIL or an H-06 rejection) stop for a human act;
  - U means restore the archive and re-verify, with no attempt.
- **Cap.** At most **M = 4** attempts per batch. Two consecutive attempts with an identical failure-string set are class D.
- **Blindness.** The rule never consults the audit sample or any audit result.
- **Estimation object.** The estimation object of a label is its batch's first attempt that reaches BATCH-PASS and is accepted. Failed attempts never enter estimation, are never shown to an auditor or adjudicator, and are reported in E-3.
- **Repairs.** Instrument repairs (runbook, prompt) are admissible only at the canary gate.
- **Named limitation.** A label's difficulty may raise both its failure probability and its discordance, and a retried reading may be more conservative. θ_D and θ_A therefore describe the retry-surviving (accepted) population. A §21 FAIL or an H-06 rejection removes the whole batch with no retry; its labels are NOT-ASSESSABLE and worst-cased (EP-01 §7).
- **Reporting.** The attempt number is recorded per label. The audit report gives:
  - the attempt × audit-class cross-tab (any association test is exploratory, BH family);
  - θ_D and θ_A also for the domain "accepted at attempt 1" (an exploratory domain estimate, reported only beside the primary quantities, never standalone or as a corrected value);
  - E-3 attempts and failures per class.
- **Watchdog W-SYS** (execution-only, no inferential role):
  - n = completed post-canary attempts; f = those that failed with class X, A1 or A2 (RR-7 repeats are class D and not counted);
  - after every K = 20 completed attempts, S5 stops if P(Bin(n, q\*) ≥ f) ≤ α_w, with q\* = 0.12771 and α_w = 0.01 **per look**;
  - on a stop, no new attempt is dispatched; attempts in flight are verified normally; the event is class D, and resumption needs a human act;
  - dispatch order is the frozen `batch_order` (outcome-blind).

## 3. Closed-world typing: discovery + validation + registry (Universe; PF-3, PF-4)

### 3.0 INV-LEX: lexical source-id occurrence ≠ evidentiary support (v2.4, G-LOG-0089; DR-18)

> **An S-id occurring in a value supports nothing unless the value's location is registry-typed EVIDENTIARY (or QUOTE of an EVIDENTIARY element).** A META or NONE occurrence is a *mention*. It creates no Evidence, Claim, Reconstruction input, precedence, lifecycle, statistical membership or canonical identity.
>
> A mention may only:
> - be typed (§3.2);
> - be **constrained** by the whole-record lexical gates (G-01, AUDIT, QUARANTINE, S3) and by the rule of its META row;
> - change byte digests.
>
> These constraints are restrictive only: they add failures, never a claim and never a PASS.

**Test obligation (T98–T104).** For every object O and every META-only mention X at a registered path, the following of O+X equal those of O:
- the claim set;
- the evidence obligations and anchors;
- the reconstruction relations, precedence and lifecycle;
- the statistics frame;
- the canonical claim ids.

### 3.1 Two operations, one invariant (PF-3)

- **A. DISCOVERY** (schema-independent; finds what is **present**): Reach(o) = {(p, v) : v is a string containing `\bS\d{4}\b`} ∪ {(p, v) : last-key(p) ∈ {`source_id`, `sources`, `supplied_by`, `contradicts`}}. Paths are normalized with array indices as `[*]`. Discovery does **not** and **cannot** detect absence.
- **B. VALIDATION** (schema-dependent; decides what is **legal**) applies the closed object schema Σ (rev3 object record + this addendum's amendments):
  - required keys;
  - non-null constraints;
  - forbidden and unknown keys;
  - value types;
  - closed enums (every discriminator);
  - cardinality;
  - the uniqueness keys K.

> **UniverseValid(o) requires: Reach(o) ⊆ dom(Registry) ∧ Valid_Σ(o) ∧ Unique_K(o).**
>
> **A missing key (for example a timeline point without `source_id`, the M1 path) is detected by VALIDATION, not by discovery.**

**Uniqueness keys K:**
- the timeline point (`source_id`);
- (`source_id`, `contradicts`) in `contradicted_by`;
- each summary list entry;
- (pair_id) for pair checks;
- (run, source_id, fact_id) for facts;
- the claim id in claim-evidence;
- `uuid` in transcripts;
- (agentId → transcript file).

A key that occurs twice **fails**, whether the two records agree or conflict. There is no last-wins.

### 3.2 Registry sovereignty (PF-4)

> **(F1) Type(p, v) := Registry(p, disc_p(v))**, where disc_p is a **closed enum** validated by B. The registry is total on dom(Registry) × enum(disc_p). An unknown discriminator value is a validation failure (`R7-U`).
>
> **(F2) Consumer conformance:** every consumer rule C declares its read set R(C) (§3.4). **R(C) ⊆ {p : Registry(p, ·) ∈ {EVIDENTIARY, DERIVED}}.** A consumer cannot upgrade a META path by reading it. A change to Type or to any R(C) is a **contract change**.

**Types:**
- `EVIDENTIARY(kind, whole)`: needs a fact of that kind in a reading run of the label, anchored on witnessed bytes (§6), plus the reading rule.
- `DERIVED(rule)`: must equal the rule's output.
- `META`: no force (a mention, §3.0). Its S-ids are subject only to the row's `kind_or_rule` (for example "⊆ verified cited sources") and to the whole-record lexical gates. A row with rule `—` carries no subset rule. *(v2.4 clarification: this states the per-row reading that the v2.3 implementation already applied to its rule-less META rows.)*
- `QUOTE(of)`: its S-id tokens are not citations; the quote must be anchored (§6).
- `NONE`: no S-id may occur.

The former prose "rule of force" is **withdrawn as an operative rule** (DR-04). F1 + F2 replace it.

### 3.3 Typing registry (machine-checked; one row per (path, discriminator))

<!-- REGISTRY-BEGIN -->
| path | discriminator | type | kind_or_rule | whole |
|---|---|---|---|---|
| timeline[*] | change_vs_previous ∈ {FIRST, RESTATES, EXTENDS, NOT-COMPARABLE} | EVIDENTIARY | TIMELINE | no |
| timeline[*] | change_vs_previous ∈ {NARROWS, CHANGES-DEFINITION, CHANGES-TYPE, CHANGES-TERM, CONTRADICTS, RETRACTS} | EVIDENTIARY | TIMELINE | yes |
| timeline[*].states.quote | — | QUOTE | of timeline[*].source_id | — |
| births.<kind> | value ∉ BIRTH-UNRESOLVED-* | EVIDENTIARY | BIRTH | yes |
| births.<kind> | value ∈ BIRTH-UNRESOLVED-* | EVIDENTIARY | BIRTH | no |
| timeline_summary.first_<kind> | — | DERIVED | = births.<kind> | — |
| timeline_summary.later_support[*] | — | EVIDENTIARY | SUMMARY (inherits the referenced point; §4.1) | inherited |
| timeline_summary.later_refinement[*] | — | EVIDENTIARY | SUMMARY (inherits the referenced point; §4.1) | inherited |
| timeline_summary.rejected_by[*] | — | EVIDENTIARY | SUMMARY (inherits the referenced point; §4.1) | yes |
| timeline_summary.contradicted_by[*] | — | EVIDENTIARY | CONTRADICTION (relation; §4.2) | yes |
| timeline_summary.current_lifecycle | — | DERIVED | §4.3 | — |
| superseded_by_sources[*] | — | EVIDENTIARY | LIFECYCLE | yes |
| superseded_by_sources[*].position | — | DERIVED | A.10 file-level dated position or UNDATED | — |
| absences.<dim>.supplied_by | resolution = FOUND | EVIDENTIARY | ABSENCE | yes |
| absences.<dim>.supplied_by.quote | resolution = FOUND | QUOTE | of absences.<dim>.supplied_by.source_id | — |
| absences.<dim> | resolution = GENUINELY-UNDEFINED | EVIDENTIARY | ABSENCE-CENSUS (negative; complete census, no DEFINES fact) | yes |
| absences.<dim> | resolution ∉ {FOUND, GENUINELY-UNDEFINED} | META | — | — |
| stage2_dispositions[*] | any by_dimension = FOUND | EVIDENTIARY | ABSENCE | yes |
| stage2_dispositions[*] | no by_dimension = FOUND | META | — | — |
| dependency_edges[*] | edge cites a source | EVIDENTIARY | DEPENDENCY | no |
| dependency_edges[*].quote | — | QUOTE | of dependency_edges[*].source_id | — |
| semantic_evidence.d4[*] | — | EVIDENTIARY | CONTRADICTION | yes |
| semantic_evidence.d4[*].quote | — | QUOTE | of semantic_evidence.d4[*].source_id | — |
| semantic_evidence.d5[*] | — | EVIDENTIARY | TWO-CONCEPT | yes |
| semantic_evidence.d5[*].quote | — | QUOTE | of semantic_evidence.d5[*].source_id | — |
| census_reading_disagreements[*] | — | EVIDENTIARY | CENSUS | yes |
| census_reading_disagreements[*].quote | — | QUOTE | of census_reading_disagreements[*].source_id | — |
| status_basis.<field>.sources[*] | — | META | ⊆ verified cited sources | — |
| status_basis.<field>.<text> | — | META | — | — |
| hindsight_dependency[*] | — | META | ⊆ verified cited sources | — |
| escalations[*].detail | — | META | a CONTRACT-DEVIATION escalation must name the unread file | — |
| semantic_status_note | — | META | — | — |
| tier_causing_pair_ids | — | NONE | — | — |
| pair_breakdown | — | NONE | — | — |
| track_composition | — | NONE | — | — |
| absences.<dim>.reason | — | META | — | — |
| stage2_dispositions[*].reason | — | META | — | — |
| timeline[*].historical_position | — | META | — | — |
| escalations[*].field | — | META | own-object location: timeline[<S-id>].<key>, <S-id> ∈ timeline[*].source_id | — |
<!-- REGISTRY-END -->

**Semantics of the v2.4 rows (G-LOG-0089; DR-18):**
- **`absences.<dim>.reason`, `stage2_dispositions[*].reason`.** Agent justification text. They are META whatever the parent's type: the evidentiary content lives in the structural fields (`resolution`, `supplied_by`, `by_dimension`, the source key). A reason creates no claim and needs no anchor.
- **`timeline[*].historical_position`.** The agent's narrative of a point's position. It is **explicitly not a precedence input**: precedence is A.10-only (§4.2, FD-1′c), and an ordering asserted in this text is an unverified statement that no predicate consumes.
- **`escalations[*].field`.** A structural address inside the same object. If it carries an S-id, the value must be exactly `timeline[<S-id>].<order|date_applies_to_file|change_vs_previous>`, with `<S-id>` ∈ this object's `timeline[*].source_id`; otherwise R7-U. It is never a source reference. Its only effect is the historical rule-(b) null exemption (G-LOG-0023).

**Fact kinds** (values of the existing fact-kind dimension, not new dimensions):
- TIMELINE (`change_candidate`)
- BIRTH (`birth_kind`)
- ABSENCE (`dimension`, `finding` ∈ DEFINES/MENTIONS/NONE)
- DEPENDENCY (`target_label`)
- CONTRADICTION (`contradicts_source`)
- TWO-CONCEPT (`concepts`)
- LIFECYCLE (`lifecycle`)
- CENSUS (`dimension`)
- OMQ14 (`type`)

SUMMARY is not a fact kind. A SUMMARY entry is supported by the TIMELINE fact of the timeline point it references (§4.1).

**Claim ids** (the contract defines them; closes m10):
- `timeline:<S>`
- `birth:<kind>:<S>`
- `summary:<field>:<S>`
- `contra:<S>:<T>`
- `lifecycle:<S>`
- `absence:<dim>`
- `stage2:<S>:<hit_kind>:<json(hit_key)>:<term_index>:<dim>`
- `edge:<target>:<S>`
- `d4:<S>`
- `d5:<S>`
- `census:<dim>:<S>`

### 3.4 Consumer read sets (machine-checked; F2)

<!-- CONSUMERS-BEGIN -->
| consumer | read_set |
|---|---|
| D4 (CONTESTED) | semantic_evidence.d4[*]; timeline_summary.contradicted_by[*] |
| D5 (HOMONYM-SPLIT) | semantic_evidence.d5[*] |
| birth determination | births.<kind>; timeline_summary.first_<kind> |
| FOUND / absence resolution | absences.<dim>.supplied_by; absences.<dim>; stage2_dispositions[*] |
| lifecycle and end(p) (A.10) | superseded_by_sources[*]; superseded_by_sources[*].position; timeline[*]; timeline_summary.current_lifecycle |
| edge classes | dependency_edges[*] |
| Track-B rule (§14.5) | timeline_summary.later_support[*]; timeline_summary.later_refinement[*]; timeline_summary.contradicted_by[*] |
| census disagreement | census_reading_disagreements[*] |
<!-- CONSUMERS-END -->

(A consumer's read of a QUOTE is implied by the EVIDENTIARY element that owns it. S1–S3 and EMPTY read whole records for syntax only and derive no epistemic status.)

## 4. HD-1 semantics (Reconstruction; PF-1, PF-2)

### 4.1 Summary relations are object-level, not projections of adjacent classes (PF-1)

**The distinction:**
- `change_vs_previous` is an **adjacent timeline relation**: rev3 §14.3 says it is "what the text does relative to the previous point".
- `later_support`, `later_refinement`, `contradicted_by` and `rejected_by` are **object-level summary relations**. They are not functions of the adjacent classes.

**Rules:**
1. **Membership:** every summary entry identifies a timeline point of the **same object** (its `source_id` is a timeline point's `source_id`).
2. **Inheritance:** the entry inherits that point's evidence (its TIMELINE fact) and its reading rule. `rejected_by` entries require WHOLE-FILE (retraction force). `contradicted_by` entries follow §4.2.
3. **One-directional constraint only** (**not** equality):
   - a point whose class is CONTRADICTS **must** appear in `contradicted_by`, targeting its predecessor, because that is what the adjacent class asserts;
   - a point whose class is RETRACTS **must** appear in `rejected_by`.
   - A CONTRADICTS or RETRACTS point with no predecessor in the timeline list cannot satisfy this constraint and is `R7-R`. "Predecessor" here means only the adjacent point the class refers to. It carries **no** historical precedence (§4.2).
   - No converse: a summary may contain points of other classes (for example S4, which RESTATES S3 while S3 CONTRADICTS S1, may appear in `contradicted_by` with target S1, supported by its own CONTRADICTION fact).
4. **The equality "summary = all points of the matching class" is not required and not enforced.**
5. **Lateness is never inferred from array position.** An entry of `later_support` or `later_refinement` asserts lateness relative to the object's first appearance. It requires ESTABLISHED precedence (§4.2) of the entry's point over the object's earliest dated timeline point. Without it, the entry is invalid in `later_*` (`R7-R`). DR-01b records this as a semantic tightening.
6. **`first_<kind> = births.<kind>`** stays a DERIVED equality (a projection already defined by rev3 §14.3).

**PROJECTION vs INTERPRETATION** (recorded explicitly):

| Relation | Status |
|---|---|
| `first_<kind> = births.<kind>` | **PROJECTION** (rev3 defines it) |
| CONTRADICTS ⇒ ∈ `contradicted_by`; RETRACTS ⇒ ∈ `rejected_by` (one-directional) | **INTERPRETATION of the adjacent class** (the class asserts the relation to the predecessor); HUMAN SEMANTIC DECISION FD-1′a |
| RESTATES as `later_support`; EXTENDS, NARROWS, CHANGES-* as `later_refinement` (which list a later point *may* be listed in; no obligation) | **INTERPRETATION**; HUMAN SEMANTIC DECISION FD-1′b, **FROZEN: EXTENDS → `later_refinement`** (G-LOG-0088) |

### 4.2 Contradiction relation ≠ temporal precedence (PF-2)

**Schema** (amends the rev3 object schema):

```
contradicted_by: [{source_id, contradicts}]
```

`contradicts` = the `source_id` of the timeline point whose statement is contradicted. That is the target Track-A statement, recoverable per HD-1.

**Relation validity** (independent of time):
- `source_id` and `contradicts` are two **distinct** timeline points of the **same object**;
- a CONTRADICTION fact (`contradicts_source` = the target) exists in a reading run of the label, anchored on witnessed bytes (§6);
- the entry's point is WHOLE-FILE;
- if the object is CONTESTED via D4 on it, `semantic_evidence.d4` has an entry for `source_id`.

**Temporal qualifier (computed, never declared):**

> **Precedence(T, S) = ESTABLISHED iff both T and S have A.10 dated positions** (EXPLICIT basis; order evidence other than SOURCE_ID only; not in a BULK block; `date_applies_to_file: CONFIRMED`) **and date(T) < date(S), strictly** (a same day counts as not established). **Otherwise NOT-ESTABLISHED.**

- **Never used as precedence:** `source_id` order, list index, array order, or UNORDERED-BLOCK position.
- **STEP-NUMBER is not used** (DR-02b). Rev3 §14.4c allows a STEP-NUMBER to order sources only within its own series and excludes it from cross-series temporal scope, so R7 does not generalize it into precedence.
- **A NOT-ESTABLISHED contradiction remains a valid contradiction relation.** It may feed D4. It must **not** support `later_*`, "earlier", `end(p)`, or any other temporal claim.
- An agent-declared precedence value that differs from the computed one is `R7-R`.

### 4.3 Lifecycle

- `superseded_by_sources[*] = {source_id, position}` is **EVIDENTIARY(LIFECYCLE), WHOLE-FILE** (HD-1). `position` is DERIVED: the A.10 file-level dated position, or `UNDATED`.
- **FD-3′:** a SUPERSEDED-bearing `current_lifecycle` value (including its `SOURCE-CLAIMED-*` form "until corroborated", rev3 l.1176) holds iff `superseded_by_sources` is non-empty.
- `end(p)` is computed per A.10 from the RETRACTS points with ESTABLISHED dated positions and the derived `position`s. An undated one makes the record UNORDERED.

## 5. Execution witness (Witness; HD-2; W1–W8)

### 5.1 Invariants

| # | Invariant |
|---|---|
| **W1 completeness** | every agent dispatched for the batch has a **completed dispatch** (§5.2). The witness accounts for every tool call of every completed dispatch |
| **W2 identity** | agent → run is derived from `agentId` → `meta.json.toolUseId` → the orchestrator's Agent tool_use → the **binding line** (§5.3) → plan owner. Never from agent-written files |
| **W3 content** | a successful read event requires: the canonical invocation (§5.4); the positional header (first output line); a valid page, byte range and page sha256; the positional trailer (last line) with matching S-id and page; ack = `ACK-` + the last 12 hex of the page sha; and the **page sha equal to the recomputation from resolver bytes**. The ack is a consistency check only, never separate evidence (ACK = f(page_sha)) |
| **W4 failure** | refused, failed and denied calls are explicit events (identified by `is_error` + `Exit code N` + result content, never by a stderr channel). Refused, malformed, denied and non-canonical events are stop conditions |
| **W5 ordering** | every temporal relation uses harness **intervals** [t_tool_use, t_tool_result] and orchestrator event times. Never line order, agent-written or reader-written times |
| **W6 reconciliation** | *(evaluated in Evidence)* witness ↔ READ-LOG (an exact multiset over (run, S, page, page_sha) plus refusals) · witness ↔ records (coverage, reading state) · witness ↔ facts (§6) · witness ↔ dispatch (W2) · witness ↔ declared stages (§7). Any unexplained residue fails |
| **W7 preservation** | the witness and the transcript digests are frozen at unit validation and at assembly (§5.6) |
| **W7a monotonicity** | W_unit ⊆ W_final (§5.7) |
| **W8 execution-capability closure** | ToolCalls(agent) ⊆ AllowedToolCalls(run), and ToolCalls(agent) \ AllowedToolCalls(run) = ∅, with Read admitted only for p ∈ I(run) (§5.5, §5.8) |

### 5.2 W1 decision table: the completed dispatch (PF-6)

> **Completed dispatch** := (1) exactly one completion notification for the task in the orchestrator transcript ∧ (2) exactly one transcript file for the agentId ∧ (3) the harness tool-use count present ∧ (4) the transcript's tool_use count = the harness count ∧ (5) the `uuid`/`parentUuid` chain is valid (every parent seen, no duplicate `uuid`) ∧ (6) zero parse errors.

| Case | Signature | Reason code | Verdict |
|---|---|---|---|
| normal | (1)–(6) hold | — | proceed to W2–W8 |
| zero-tool dispatch | (1)–(6) hold, count = 0 | `W1-ZERO-TOOL` | completed but empty. The run is **FAILED** (every S5 run must read or write) |
| agent declined | completed; only the handback | `W1-DECLINED` | run FAILED |
| harness-denied call | completed; a `denied` event | `W4-DENIED` | stop condition; run FAILED |
| missing transcript | dispatch present, (2): 0 files | `W1-TRANSCRIPT-MISSING` | **FAIL** (`R7-W`) |
| duplicate transcript for one agentId | (2): more than 1 file | `W1-TRANSCRIPT-DUPLICATE` | **FAIL** |
| missing harness count | (3) fails | `W1-HARNESS-COUNT-MISSING` | **FAIL** |
| count mismatch | (4) fails | `W1-COUNT-MISMATCH` | **FAIL** |
| malformed chain | (5) fails (parent unseen or duplicate `uuid`) | `W1-CHAIN` | **FAIL** |
| parse failure | (6) fails | `W1-PARSE` | **FAIL** |
| incomplete / in progress at freeze | (1): 0 notifications | `W1-NOTIFICATION-ABSENT` | **FAIL**; a freeze requires every dispatch completed |
| resumed agent / several notifications for one task | (1): more than 1 notification | `W1-NOTIFICATION-MULTIPLE` | **FAILED / RUN-INVALID.** S5 forbids agent resumption. The first and last counts are **never** chosen |
| corrupted content (valid JSON) | W3 fails | `W3-*` | **FAIL** |

Each reason code is reported separately; several may co-occur, and every one is F.

**"Truncated transcript" is a cause, not an independently observable state:**
- a clean truncation at a line boundary is **observed as** `W1-COUNT-MISMATCH`;
- a mid-line truncation is **observed as** `W1-PARSE` (and usually `W1-CHAIN`).

The reason codes are the machine-testable distinct states. "Zero tool calls" (file present, count 0), "missing transcript" (file absent) and "missing count" (notification absent) are distinct codes, and each fails closed (DR-16).

### 5.3 Dispatch binding line (C5)

The **first line** of every S5 dispatch prompt is exactly:

```
S5-RUN-BINDING run=<run> batch=<batch> label=<label> canary=<hex16>
```

`canary` = the first 16 hex of sha256(activation-commit ‖ run).
- A dispatch without exactly one binding line, or with a run ∉ Runs(P), fails.
- Two dispatches for one run: only one may have tool effects; otherwise the run is FAILED.

### 5.4 Canonical reader invocation (C2)

The Bash command must be **exactly**:

```
python3 -B <READER_ABS> --run <run> --batch <batch> --label <label> --step <1|7|10> --mode bytes --page <k> <S####>
```

It contains literal values only: no variables, pipes, redirections, `;`, `&&`, subshells, `cd`, environment prefixes or second call.

### 5.5 W8: execution-capability closure (PF-5; FD-8′; FR-1 closed by Option A, G-LOG-0087)

**AllowedToolCalls(run)** (owned by Universe):
1. **The canonical reader invocation** (§5.4), with `--run` = the bound run, `--batch`, `--label` = the plan owner, and a source ∈ perm(run). Reading runs only; synthesis runs have none. *This is a corpus observation, i.e. evidence-bearing (§6).*
2. **Read of a path in I(run)**, the run's frozen input manifest (§5.8). This is **AllowedRead(r, p) ⇔ p ∈ I(r)**. *This is an input read, an execution event; never evidence (§6).*
3. **Write** to a **run-owned permitted path**: `ledger-p3b-r2/<run>/{file-reading-records.jsonl}` for units; plus `{objects.jsonl, register.jsonl, p1-gap.jsonl, claim-evidence.json, S3-LINT.json}` for the SINGLE and synthesis runs. Run directories are **pre-created by the orchestrator** before dispatch, so no agent needs `mkdir`.
4. **The harness's final handback mechanism** (its tool name is frozen at activation from the harness version in use).

The four classes are **disjoint**. Every tool call is classified into exactly one class, or is a violation.

**Everything else is a violation (`R7-W`):**
- any other Bash command (`cat`, `grep`, `ls`, `python …`, redirection, copying);
- **Read of any path p ∉ I(run)**, including another run's or another label's files, undeclared repository files, and corpus paths;
- Grep, Glob, Edit, NotebookEdit, web tools, Agent spawning;
- a Write outside the permitted paths.

**Unrestricted Read is not reopened:** an agent may read its declared inputs, but it cannot discover its own authority to read.

**SEAL (carried forward from the R6 exposure logic):** any tool call whose input contains a hold-out identifier, a hold-out path token, or a corpus path outside the reader is a **SEAL stop**. This includes a Read of such a path, whether or not it is (wrongly) listed. The batch stops, a P3B-ESC is raised, and there is no further dispatch. This is not weakened for convenience.

**Enforcement point.** The harness Read tool cannot consult I(run), so W8 is **established by the witness** (authoritative, post hoc), as for every other execution fact in Model D. A harness permission rule or PreToolUse hook restricting Read to I(run) may be added as **operational defence in depth**. It is never a substitute for the witness check.

### 5.6 Witness extractor, `WITNESS.jsonl`, freeze (PF-9; PF-10; C6)

- **Decoder (PF-9):** a **neutral transcript syntax decoder** (new, S5-agnostic). It turns records into blocks, pairs tool_use and tool_result, and extracts `uuid`/`parentUuid`, timestamps, `meta.json` and the orchestrator's queue notifications, resolving persisted outputs. **R7 does not depend on `p3b_v1_2_4_instrument`** (an experimental, pre-registered instrument; unchanged).
- R7 witness semantics sit on top of the decoder. There is **one decoder per transcript and no second parser.**
- **`WITNESS.jsonl`** (per batch, `ledger-p3b-r2/<batch>-R7/`; **no corpus text**; canonical JSON). Its record kinds:
  - `dispatch` (tool_use_id, agent_id, run, batch, label, canary, input_manifest_sha256, t);
  - `agent` (agent_id, run, transcript_sha256, tool_use_count, harness_tool_use_count, notifications, parse_errors, chain_ok, first_t, last_t);
  - `read` (agent_id, run, batch, label, source_id, page, n_pages, byte_start, byte_end, page_sha256, content_sha256, ack, t_call, t_result, uuid_call, uuid_result, invocation_id);
  - `refused` | `malformed` | `denied` | `noncanonical` | `unauthorized` (agent_id, tool, command_sha256, exit, message: refusal text only, t_call, t_result);
  - `read-input` (agent_id, run, path, manifest_entry: category | PERSISTED, sha256_frozen, sha256_observed, displayed_range, content_match, t_call, t_result): an **execution/input event, distinct from `read`** (the corpus reader observation);
  - `write` (agent_id, path, content_sha256, t);
  - `orchestrator` (marker ∈ {UNITS-VALIDATED, SYNTHESIS-DISPATCHED, ASSEMBLED, FINAL-VALIDATED}, label, printed_record_hashes, t_call, t_result).
- `WITNESS-DIGESTS.json` holds decoder_sha256, extractor_sha256, the raw transcript sha256 values (main + each subagent + meta), the archive id and the `WITNESS.jsonl` sha256.
- **Freeze:** at unit validation and at assembly, write both files and **commit**. Archive the raw transcripts **outside the repository** (they contain page text; FD-4′). The verifier re-derives `WITNESS.jsonl` from the archive and requires byte equality with the committed file. A missing archive gives `UNVERIFIABLE-WITNESS` (W = U, hence BATCH-UNDETERMINED; never BATCH-PASS). An archive present but **mismatching** the committed digests is W = F (BATCH-FAIL).
- **`invocation_id` (PF-10):** reader-generated: 16 hex of sha256(run ‖ S ‖ page ‖ reader-start-ns ‖ pid), printed in the R7 header (§9). It is a **convenience join key only**, never used as evidence, completeness, chronology or trust.

### 5.7 W7a: witness monotonicity (PF-7)

> Let W_unit be the witness frozen at unit validation and W_final the witness frozen at assembly. Then:
> - **W_unit ⊆ W_final**, and every r ∈ W_unit appears in W_final **byte-identically**: no deletion, no replacement, no reinterpretation;
> - the transcript digests of every agent in W_unit are identical in the final digests;
> - W_final \ W_unit contains only events of agents dispatched after the unit freeze, and orchestrator events after it;
> - decoder_sha256 and extractor_sha256 are identical at both freezes, **or** both witnesses are re-derived by one extractor from the archive and compared.

### 5.8 Run input manifest I(run) (Universe; FR-1, Option A; DR-17)

**Definition.** I(run) is the **frozen, explicit, hash-bound** set of files that the agent of `run` may Read.
- The Universe context derives it **before dispatch** from the plan and the run's role, never from the agent.
- It is **frozen at dispatch** and carried in the dispatch witness record (`input_manifest_sha256`).

`RunInputManifest` = `{run, role, entries[], persisted_outputs: ALLOWED | NONE, manifest_sha256}`. Each entry is `{path (canonical, repository-relative), owner_run, category, sha256, declared_role}`. `manifest_sha256` = the sha256 of the canonical JSON of the manifest without that field.

**Permitted entry categories** (closed enum; nothing is inferred dynamically):

| category | content | runs |
|---|---|---|
| `CONTRACT` | the frozen rev3 contract | all |
| `ADDENDUM` | the frozen R7 addendum | all |
| `PLAN` | the frozen R7 plan of the batch (`<slice_root>/<batch>.R7-PLAN.json`) | all |
| `SLICE` | the slice of the run's own label | all |
| `SLICE-VIEW` | the lossless multi-line view of that slice (`<slice_root>/<batch>/<label>.view.txt`; v2.7, §5.9) | all |
| `UNIT-RECORDS` | `ledger-p3b-r2/<batch>-R7-L##U##/file-reading-records.jsonl` of **every unit of the same label** | the synthesis run of that label only |
| `DECLARED-SYNTHESIS-INPUT` | any other input the frozen run contract explicitly lists for synthesis | the synthesis run only; empty unless the contract lists one |

**Persisted tool outputs** (a class rule frozen at dispatch; membership witnessed):
- If `persisted_outputs = ALLOWED`, then p ∈ I_persisted(run) iff p is a file in the session's harness tool-results directory **announced by the harness** (a `<persisted-output>` marker) **in a tool_result of the same agent's own transcript**, earlier than the Read.
- Its sha256 is recorded by the witness at the freeze. The agent cannot create or alter such files (W8 forbids Writes there).
- Another agent's persisted output, or any persisted output when `persisted_outputs = NONE`, is ∉ I(run).
- Default: `ALLOWED` for reading runs (their own reader output); `ALLOWED` for synthesis (its own large Read results).
- This is the only runtime-membership class, because persisted paths do not exist at dispatch (DR-17).

**Hard prohibitions (Universe preparation check).** I(run) must not contain:
- a production corpus path;
- another label's slice or records;
- another run's private input;
- a hold-out path or H-19 material;
- F-Series or application code;
- an arbitrary repository file;
- a path outside the categories above.

If one does, the result is **`R7-U` (preparation failure)**. The violating entry is **reported, never silently removed**.

**Hash binding.** HashObserved(p) = HashFrozen(I(r), p). The file's sha256 at the witness freeze (for synthesis: the unit-validation freeze of the unit records) must equal the manifest's sha256. A mismatch is **`R7-U`** (frozen-integrity failure). A changed input never passes silently.

**Verification (Witness):**
- ∀ read-input events (r, p): p ∈ I(r) (static or persisted class);
- the content displayed by the harness Read (the line-numbered view, offset and limit included) equals the frozen file's bytes over the displayed range (`content_match`);
- otherwise `R7-W`.

### 5.9 SLICE-VIEW: lossless multi-line slice rendering (v2.7, EG-2, G-LOG-0104; DR-21)

**Problem (RI-1).** A frozen slice is one canonical JSON line. The harness Read silently drops a line above its token cap, so an agent cannot see a long slice exactly.

**Definition.** `view(s)` is a deterministic function of the slice bytes `s`:
- line 1 is the fixed header `# SLICE-VIEW v1 (R7 v2.7): …`;
- then one line per JSON leaf of `json.loads(s)`, in document order: `<path> TAB <kind> TAB <payload>`;
- `path` = the JSON array of keys and indices (compact JSON);
- kinds: `N` scalar (payload = the compact JSON value); `E` empty container (payload `{}` or `[]`); `S k/n` string chunk k of n (payload = the JSON-encoded chunk of at most 1,000 characters);
- the line separators U+2028, U+2029 and U+0085 are escaped in every payload, so no raw line-separator code point occurs;
- every line is at most 8,000 characters.

**Losslessness.** `parse(view(s))` reconstructs the object, and `canon(parse(view(s))) = s`. A missing, duplicated, reordered or altered line makes `parse` fail or differ.

**Agent rule.** The agent reads its SLICE-VIEW, not the raw SLICE, paging with at most 25 lines per Read. The raw SLICE stays in I(run) as the reference bytes. A view read is an input read, never evidence.

**Verifier check (Universe).** For every label of the batch: the view file exists; its bytes equal `view(slice)`; and `canon(parse(view)) = slice`. Otherwise **`R7-U`**. Hash binding and `content_match` (§5.8) apply to the view like any other I(run) entry.

### 5.9a Required view lines for hub runs (v2.8(f), EG-3, G-LOG-0106; DR-22)

For a run whose slice has `"hub": true` (SINGLE, UNIT or SYNTHESIS), the agent **must display every line of R(run)** and **may** display any other line of its SLICE-VIEW. R(run) is the header line plus every view line whose path p satisfies one of:
- (i) p[0] is neither `search_records` nor `source_meta`;
- (ii) p[0] = `search_records` and p[1] ≥ 1;
- (iii) p[0..1] = [`search_records`, 0] and p[2] is neither `ledger_hits` nor `raw_hits`;
- (iv) p[0] = `source_meta` and p[1] ∈ T(L). T(L) is the set of S-ids occurring in the canonical slice with `search_records`, `stage2_files` and `source_meta` removed.

**Properties:**
- R(run) is a deterministic function of the frozen SLICE-VIEW.
- The dispatch prompt states it as line ranges. The ranges bind nobody, since R(run) is recomputed from the view.
- Lines outside R(run) are discovery metadata. Stage 2 is not performed for a hub, so no output may rest on them.
- A `source_meta` entry outside T(L) is never needed; if one is cited, its provenance rule applies unchanged.

**Coverage** is the union of the displayed ranges of the run's `content_match = true` reads of its SLICE-VIEW. The orchestrator **reports** per run whether R(run) ⊆ coverage (RI-1b), naming each uncovered path.

This section changes no evidence, reading, absence, verdict or statistics rule. For a non-hub run, R(run) is the whole view.

## 6. Reading state and byte-exact fact anchoring (Evidence; HD-5; PF-8)

- **Coverage** of (run, S) = its witnessed read events. **WHOLE-FILE ⇔ pages 1…n all witnessed in the owning run.** The READ-LOG is reconciled (W6), not consulted.
- **Reading rule:** default-deny, through the registry's `whole` flag (§3.3).
- **Anchoring rule.** For every fact quote q of source S, owned by run r, the verifier (**not the agent**; agents never submit offsets) establishes:

> ∃ i : bytes_S[i : i+|q|] = q (exact bytes, UTF-8), **and** [i, i+|q|) ⊆ Wr(S), where Wr(S) = ⋃ { [byte_start_k, byte_end_k) : page k of S witnessed by run r }, **merged only across byte-contiguous pages** (byte_end_k = byte_start_{k+1} in the resolver's paging).

- **Multiple occurrences** are allowed. At least one qualifying occurrence must lie inside Wr(S). "The quote occurs somewhere in the file" is **not** sufficient.
- **Cross-page quotes:** both pages must be witnessed, byte-contiguous in the resolver's paging, and witnessed **by the same owning run**. The interval is computed on resolver bytes, never on transcript text.
- **Normalized matching:** permitted **only** under the normalization **N-WS** (DR-08):
  - it replaces every maximal run of ASCII whitespace (U+0009, U+000A, U+000D, U+0020) by one U+0020 in both q and bytes_S;
  - it keeps a **deterministic offset map** m from each normalized position to its original byte offset;
  - a normalized match at normalized positions [j, j+|N(q)|) maps to the byte interval [m(j), m(j+|N(q)|−1)+1), which must satisfy the containment above.
  - Any other normalization (case folding, Unicode normalization, punctuation) is **not evidence**.
- **Input reads are never evidence (FR-1).** Bytes seen through a `read-input` event (a slice, unit records, the contract, a persisted output) never satisfy the anchoring rule. Wr(S) is built **only** from corpus-reader `read` events. A fact whose quote occurs only in an input file is `R7-E`.
- **Acknowledgement tokens:** consistency check only; not evidence.
- **Carried over:** a non-WHOLE-FILE required file needs a CONTRACT-DEVIATION escalation naming it.
- **No minimum quote length** (FD-9 not adopted). Quote informativeness remains a future research and governance question and is outside the execution trust root.

### 6.1 Decided binary files (v2.6-DC3, G-LOG-0099; DR-20)

**Problem closed (DC-3).** The reader refuses binary content (`BINARY-CONTENT`), and a refused reader call is a W4 stop. The historical G-04 gate, however, requires every stage-2 hit file to be read. Under v2.5, therefore, no label with a required binary file could pass.

**Rules:**
- **The decisions.** The per-file binary decision records, `audit-p3b/20260928_BINARY-DECISIONS.json` (FALSE-HIT or NOT-CONSUMED-ESCALATED; EXTRACT is not executable), are **hash-bound** by the manifest header field `r7_binary_decisions_sha256`. A bound file that is missing, or whose bytes differ, → R7-U. Without the header field there are no decisions.
- **Delivery.** The frozen plan carries, per label, `binary_decisions: {source_id: decision}` for the decided files among its required files. The Universe derives it, and the plan-equality check covers it. The plan is an existing I(run) category (PLAN), so no category is added. **The agent never calls the reader on a decided file; a call remains a W4 stop.**
- **Conformance (R7-E).** For each decided file of the label, all of the following must hold:
  - there is no witnessed read;
  - its file-reading record has `reading_state = NOT-CONSUMED`;
  - it is not a claim source;
  - if the file is a **stage-2 hit file** of the label, at least one disposition exists (a decided file that is only a row source of the label has no hit to dispose: AF-1, G-LOG-0101); every disposition matches the decision:
    - **FALSE-HIT:** method `BINARY-DECIDED`, every dimension `FALSE-HIT`;
    - **NOT-CONSUMED-ESCALATED:** method `NOT-CONSUMED-ESCALATED`, every dimension `ESCALATED`, plus a CONTRACT-DEVIATION escalation naming the file.

  Any mismatch → R7-E FAIL.
- **Coverage.** A **conforming** decided file counts as decided, not missing, in the historical G-04 read requirement. A conforming **FALSE-HIT** file is also exempt from the reading rule's CONTRACT-DEVIATION requirement, from the census completeness rule, and from READ-COVERAGE. A NOT-CONSUMED-ESCALATED file stays incomplete for the census: an ESCALATED hit never supports GENUINELY-UNDEFINED.
- **Undecided files.** An unread required file that has **no** decision still fails G-04 (fail-closed).
- **Artifact integrity (all-or-nothing; any violation → no decision applies, R7-U).** The header also binds `r7_binary_decisions_authority`. The artifact must satisfy all of the following:
  - `artifact = "BINARY-DECISIONS"`;
  - its `authority` equals the bound authority;
  - it is non-empty;
  - each record has a valid S-id, a decision ∈ {FALSE-HIT, NOT-CONSUMED-ESCALATED} (EXTRACT is rejected), a 64-hex `content_sha256`, and `authorized_by` equal to the artifact authority;
  - there are no duplicates.

  Provenance is kept per record.
- **Agreement with the resolver bytes (R7-U).** Checked for the batch's required files:
  - a decided file must be one the reader **refuses** (`r5.is_binary`), so that a decision can never let an agent skip a text file;
  - its bytes must match the decided `content_sha256`;
  - a required file that is binary but **undecided** is a missing decision.
- **The frame is unchanged.** The decisions change only the plan field; paths, files, sizes, units, runs and the statistics frame are identical.

## 7. Temporal order (Witness; W5)

Stages are **derived**:
- units-validated(L) = the UNITS-VALIDATED orchestrator command (its output prints the unit record hashes);
- synthesis-dispatched(L) = the synthesis dispatch time;
- produced(L) = the final run's last permitted object Write.

Required:
- max over unit reads' t_result ≤ units-validated.t_call;
- units-validated.t_result < synthesis-dispatched ≤ produced ≤ ASSEMBLED ≤ FINAL-VALIDATED;
- the printed record hashes equal the current files.

Declared stage values, where present, must equal the derived ones. Otherwise the result is `R7-W`.

## 8. Statistics domain (Statistics; RC-3; HD-6; PF-14). Formulas unchanged

`estimate_v7 : Input → {STATISTICS-REJECTED(reason), STATISTICS-NOT-ESTIMABLE(reason, informational components), STATISTICS-VALID(τ̂, V̂, exact upper bound U (primary), conditional CI, zero bounds)}` (the statistics layer of §0). In the table below, "REJECT" abbreviates STATISTICS-REJECTED and "NOT-ESTIMABLE" abbreviates STATISTICS-NOT-ESTIMABLE.
- **REJECT** = invalid or tampered input.
- **NOT-ESTIMABLE** = valid input, but the requested estimand or variance is mathematically undefined. **Any undefined component of the frozen deliverable (τ̂, V̂, the exact upper bound U) makes the whole result STATISTICS-NOT-ESTIMABLE** (v2.5-S; the normal CI is conditional and informational otherwise); defined components may be reported as information only (DR-15).
- **An input outside the validated domain D never yields a number.**

| Rule | Behaviour |
|---|---|
| frame | the plan labels; sha256 = `frozen_record.frame_sha256`; missing or extra → REJECT |
| strata | keys ⊆ {HUB, EMPTY, MULTIROW, DECOMPOSED, SINGLE}; TIER-X is a reporting domain only (REJECT as a key); a disjoint and exhaustive partition, otherwise REJECT |
| membership | derived from the plan (MULTIROW = DECOMPOSED with row sources over more than one unit); a declared flag that differs → REJECT |
| rates | ∈ [0, 1], else REJECT (no cap). **Realized** n_h = round_half_up(rate · N_h); **π_h = n_h / N_h uses the realized n_h** |
| N_h = 0 | an **empty stratum**: it contributes nothing, and is **not** a NOT-ESTIMABLE trigger |
| n_h = 0 < N_h | τ(U) and p(U) NOT-ESTIMABLE (including when rate · N_h rounds to 0) |
| n_h = 1 < N_h | the stratum point estimate may be reported (information); variance and every combined CI are undefined, so the result is STATISTICS-NOT-ESTIMABLE |
| census | variance contribution 0 exactly |
| frozen record (ST11) | seed, **α (v2.5-S; ∈ (0, 1), else REJECT)**, spec sha, Python version, frame sha, per-stratum N_h / n_h / rate / members / samples. Its sha256 = the value **anchored in the freezing governance act**; otherwise REJECT |
| sample (ST6) | the sha256 of the **actual** sample argument = the frozen sample hash; a label outside the frozen sample, or any overlap → REJECT |
| zero bound | as in R6 per stratum; combined → NOT-ESTIMABLE if any n_h = 0 < N_h |
| **exact upper bound (v2.5-S, PRIMARY)** | per stratum: U_h(α_h) = the largest D with P(X ≤ d_h \| N_h, D, n_h) ≥ α_h (hypergeometric; exact rational arithmetic); census → d_h exactly; α_h = α / H′ over the H′ sampled strata (0 < n_h < N_h). **U = Σ_h U_h, and P(D ≤ U) ≥ 1 − α by the union bound.** Reported as `upper_bound_total` / `upper_bound_share` |
| **normal CI (v2.5-S, conditional)** | the normal 95% CI with FPC is reported as `ci95_share` **only if every sampled stratum has d_h ≥ 5 and n_h − d_h ≥ 5**; otherwise `ci95_share` = null and the interval appears under `ci95_informational` with its reason. Evidence: at SINGLE N = 1,700, n = 100 its exact coverage is 5.9% (D = 1), 45.5% (D = 10), 87.2% (D = 34) (B1 proposal D2) |
| partial-identification bounds | none in R7 (HD-6) |
| verification | exact enumeration of E[τ̂] and E[V̂] on small synthetic populations; **v2.5-S: the bound's definition and its coverage P_D(D ≤ U_h) ≥ 1 − α checked exhaustively on all small (N, n, D), and the Bonferroni combination checked exhaustively on two strata (T105–T109)** |

## 9. Reader changes (R7 byte mode only; historical modes unchanged)

1. The R7 grammar, the default-deny, and the plan path and hash check (§2.4).
2. **Self-describing header (FD-5′, defence in depth; the witness stays authoritative):**
   ```
   === S#### page k/n bytes a-b (content sha256 C; page sha256 P) run R batch B label L inv I ===
   <page>
   === END S#### page k/n ACK-xxxxxxxxxxxx ===
   ```
   `inv` = the reader-generated invocation id (§5.6). Nothing depends on READ-LOG state.

## 10. Verification

`p3b_s5_verify.verify` routes revision 7 to the R7 verifier (the composer, §0).
- The verifier returns **only** a batch verdict ∈ {BATCH-PASS, BATCH-FAIL, BATCH-UNDETERMINED} with per-predicate tags. It never emits a bare "PASS" and never a statistics or programme verdict.
- Revision 6 is rejected as superseded and revision 5 as withdrawn; the historical branch (< 5) keeps its historical output unchanged.

## 11. Acceptance test matrix (fresh synthetic fixtures; negatives through the production verifier; statistics through `estimate_v7`)

<!-- TESTS-BEGIN -->
| id | invariant | case | expected |
|---|---|---|---|
| T01 | U-B required | timeline point `source_id: null` | BATCH-FAIL R7-U |
| T02 | U-B required | timeline point without `source_id` | BATCH-FAIL R7-U |
| T03 | U-A registry | unknown S-id-bearing key (`x_notes: "S…"`) | BATCH-FAIL R7-U |
| T04 | U-B types | non-object timeline element | BATCH-FAIL R7-U |
| T05 | E anchoring | `semantic_evidence.d4` without a CONTRADICTION fact, or with a fabricated quote | BATCH-FAIL R7-E |
| T06 | E whole | `superseded_by_sources` without a LIFECYCLE fact, or non-WHOLE | BATCH-FAIL R7-E |
| T07 | U-K unique | duplicated `contradicted_by` entry | BATCH-FAIL R7-U |
| T08 | U-K unique | contradictory duplicate pair check (YES and NO) | BATCH-FAIL R7-U |
| T09 | U namespace | undeclared `OB####-R7-L03U09` directory | BATCH-FAIL R7-U |
| T10 | U namespace; W2 | legacy `-R2*` read after dispatch | BATCH-FAIL R7-U + R7-W |
| T11 | U namespace | unknown namespace (`-R8-`, `-R6-`) | BATCH-FAIL R7-U |
| T12 | W2 | forged label ownership | BATCH-FAIL R7-W |
| T13 | U binding | revision/contract-hash mismatch; revision `"7"` | BATCH-FAIL R7-U |
| T14 | W6 | fabricated READ-LOG line (correct page hash) | BATCH-FAIL R7-E |
| T15 | W6 | deleted READ-LOG line | BATCH-FAIL R7-E |
| T16 | W5/§7 | declared stage ≠ derived stage | BATCH-FAIL R7-W |
| T17 | E anchoring | claim-evidence repointed to a fact on an unwitnessed page | BATCH-FAIL R7-E |
| T18 | W5 | back-dated agent-written timestamp | BATCH-FAIL R7-W |
| T19 | W8 | synthesis agent reader call | BATCH-FAIL R7-W |
| T20 | W2 | wrong batch | BATCH-FAIL R7-W |
| T21 | W2 | wrong run | BATCH-FAIL R7-W |
| T22 | W2 | wrong label | BATCH-FAIL R7-W |
| T23 | W4/W6 | refusal missing from the transcript (READ-LOG shows one) | BATCH-FAIL R7-W |
| T24 | W1 | transcript truncated (clean / mid-line) | BATCH-FAIL R7-W |
| T25 | W1/W3 | transcript corrupted (invalid JSON; edited page hash) | BATCH-FAIL R7-W |
| T26 | W1/U-K | duplicated transcript event | BATCH-FAIL R7-W |
| T27 | W1 | reordered transcript records | BATCH-FAIL R7-W |
| T28 | W3 | page-hash mutation | BATCH-FAIL R7-W |
| T29 | E anchoring | fact quote on an unwitnessed page | BATCH-FAIL R7-E |
| T30 | S frame | TIER-X as a stratum | STATISTICS-REJECTED |
| T31 | S domain | n_h = 0 < N_h | STATISTICS-NOT-ESTIMABLE |
| T32 | S domain | n_h = 1 < N_h | STATISTICS-NOT-ESTIMABLE (point estimate informational only) |
| T33 | S partition | overlapping strata | STATISTICS-REJECTED |
| T34 | S sample | forged frozen sample (injected label; matching caller hash) | STATISTICS-REJECTED |
| T35 | S rates | invalid rate (1.7; −0.1) | STATISTICS-REJECTED |
| T36 | S frame | incomplete frame | STATISTICS-REJECTED |
| T37 | S membership | declared multirow ≠ derived | STATISTICS-REJECTED |
| T38 | ALL | positive control: valid SINGLE + DECOMPOSED (≥ 2 units) + EMPTY batch with a synthetic witness; a valid frozen sample | BATCH-PASS; STATISTICS-VALID; PROGRAM-ACCEPTED |
| T39 | U-B required | missing required key other than `source_id` (`change_vs_previous`) | BATCH-FAIL R7-U |
| T40 | U-B forbidden | unknown key without an S-id | BATCH-FAIL R7-U |
| T41 | U-F1 discriminator | unknown discriminator (`resolution: "FOUNDX"`) | BATCH-FAIL R7-U |
| T42 | U-B cardinality | two timeline points with one `source_id` | BATCH-FAIL R7-U |
| T43 | U-F2 consumers | static: a consumer read set containing a META path | CONTRACT-FAIL (static) |
| T44 | R precedence | valid contradiction between undated / UNORDERED-BLOCK points (NOT-ESTABLISHED) | BATCH-PASS (boundary control) |
| T45 | R precedence | declared ESTABLISHED precedence between unordered points | BATCH-FAIL R7-R |
| T46 | R relation | contradiction target not in the timeline | BATCH-FAIL R7-R |
| T47 | R relation | contradiction target = the source itself | BATCH-FAIL R7-R |
| T48 | R relation | contradiction target from another object | BATCH-FAIL R7-R |
| T49 | W8 | agent `cat` or Read of a corpus path | BATCH-FAIL R7-W (SEAL stop if hold-out) |
| T50 | W8 | unauthorized non-reader Bash (`ls`) | BATCH-FAIL R7-W |
| T51 | W8 | shell redirection write or read | BATCH-FAIL R7-W |
| T52 | W8 | Write outside the permitted run paths | BATCH-FAIL R7-W |
| T53 | W8 SEAL | tool call bearing a hold-out identifier or path | BATCH-FAIL R7-W + SEAL stop |
| T54 | W1 | missing transcript | BATCH-FAIL R7-W |
| T55 | W1 | missing harness count | BATCH-FAIL R7-W |
| T56 | W1 | zero-tool dispatch for a reading run | BATCH-FAIL R7-W (run FAILED) |
| T57 | W1 | resumed agent (two notifications) | BATCH-FAIL R7-W (RUN-INVALID) |
| T58 | W1/U-K | duplicate transcript for one agentId | BATCH-FAIL R7-W |
| T59 | W1 | incomplete dispatch at freeze | BATCH-FAIL R7-W |
| T60 | W7a | W_unit record removed from W_final | BATCH-FAIL R7-W |
| T61 | W7a | W_unit record modified in W_final | BATCH-FAIL R7-W |
| T62 | W7a | extractor or decoder hash changed between freezes (no joint re-derivation) | BATCH-FAIL R7-W |
| T63 | E anchoring | multiple occurrences: one witnessed → PASS; only an unwitnessed one → FAIL | BATCH-PASS / BATCH-FAIL R7-E |
| T64 | E anchoring | match only under a non-permitted normalization (case fold / NFKC) | BATCH-FAIL R7-E |
| T65 | E anchoring | cross-page quote, both contiguous pages witnessed by the owning run | BATCH-PASS |
| T66 | E anchoring | cross-page quote, one page unwitnessed | BATCH-FAIL R7-E |
| T67 | E anchoring | cross-page quote, pages witnessed by different runs | BATCH-FAIL R7-E |
| T68 | S domain | N_h = 0 stratum present | STATISTICS-VALID (an N_h = 0 stratum is not a trigger) |
| T69 | S domain | rate · N_h rounds to 0 | STATISTICS-NOT-ESTIMABLE |
| T70 | S anchor | frozen record internally consistent, anchor mismatch | STATISTICS-REJECTED |
| T71 | R+E combo | NOT-ESTABLISHED contradiction whose quote is on an unwitnessed page | BATCH-FAIL R7-E (R holds) |
| T72 | R summary | S4 RESTATES S3, S3 CONTRADICTS S1, S4 in `contradicted_by` (target S1) with its CONTRADICTION fact | BATCH-PASS (PF-1 positive control) |
| T73 | R one-direction | CONTRADICTS point missing from `contradicted_by` | BATCH-FAIL R7-R |
| T74 | R lateness | `later_*` entry without ESTABLISHED precedence (array order only) | BATCH-FAIL R7-R |
| T75 | R summary | summary entry (`later_refinement` / `rejected_by`) whose source is not a timeline point of the object | BATCH-FAIL R7-R |
| T76 | R summary; E whole | `rejected_by` entry whose referenced point is not WHOLE-FILE (inheritance) | BATCH-FAIL R7-E |
| T77 | V layers | BATCH-PASS for all batches, statistics NOT-ESTIMABLE (n_h = 1) | PROGRAM-ACCEPTED = false |
| T78 | V undetermined | witness archive absent, no other defect | BATCH-UNDETERMINED |
| T79 | V Kleene | one predicate F and another U | BATCH-FAIL |
| T80 | V output schema | verifier output containing a bare "PASS", or a statistics/programme verdict | CONTRACT-FAIL (static) |
| T81 | V layers | all batches BATCH-PASS, STATISTICS-REJECTED | PROGRAM-ACCEPTED = false |
| T82 | V layers | one batch BATCH-UNDETERMINED, STATISTICS-VALID | PROGRAM-ACCEPTED = false |
| T83 | V archive | archive present, digest mismatch | BATCH-FAIL R7-W |
| T84 | W8 I(run) | unit reads its own label's SLICE and the PLAN (entries in I(run), hashes match) | BATCH-PASS (FR-1 positive control) |
| T85 | W8 I(run) | synthesis reads an undeclared repository file | BATCH-FAIL R7-W |
| T86 | W8 I(run) | wrong-run input read: unit U01 reads U02's record file | BATCH-FAIL R7-W |
| T87 | W8 I(run) | wrong-label input read: synthesis L02S reads label L03's unit records | BATCH-FAIL R7-W |
| T88 | W8 I(run) | Read of a hold-out path | BATCH-FAIL R7-W + SEAL stop |
| T89 | U I(run) | frozen I(run) sha256 ≠ the input file's bytes at the freeze | BATCH-FAIL R7-U |
| T90 | W8 I(run) | the harness-displayed Read content ≠ the frozen file's bytes over the displayed range | BATCH-FAIL R7-W |
| T91 | W8 I(run) | Read of the agent's own persisted output, announced in its own tool_result; `persisted_outputs = ALLOWED` | BATCH-PASS (persisted positive control) |
| T92 | W8 I(run) | Read of another agent's persisted output, or `persisted_outputs = NONE` | BATCH-FAIL R7-W |
| T93 | W8 I(run) | DECOMPOSED L02 (U01, U02) → L02S reads U01 + U02 records, plan, contract, addendum; everything else valid | BATCH-PASS (FR-1 synthesis positive path) |
| T94 | W8 schema | static: the four capability classes are closed and disjoint; every tool call maps to exactly one class or to a violation | CONTRACT-FAIL (static) if violated |
| T95 | U I(run) | prohibited path in a prepared I(run) (hold-out / other label's slice / application code) | BATCH-FAIL R7-U (preparation failure; reported, not removed) |
| T96 | E input≠evidence | fact quote present only in an input file (slice / unit records), not on a witnessed reader page | BATCH-FAIL R7-E |
| T97 | W8 I(run) | I(L02S) mis-declared without U01's records; synthesis reads U01's records | BATCH-FAIL R7-W |
| T98 | U INV-LEX | META-only S-id mentions (known, read sources) in every v2.4 META location; claims, evidence obligations, reconstruction, precedence, lifecycle, frame and claim ids unchanged | BATCH-PASS (INV-LEX positive control) |
| T99 | U INV-LEX | unknown S-id (not in 02-FILES) in a META reason | BATCH-FAIL (G-01 / AUDIT) |
| T100 | U INV-LEX | hold-out S-id in META text | BATCH-FAIL (QUARANTINE; W8 SEAL) |
| T101 | R INV-LEX | S-id range in META text | BATCH-FAIL R7-R (S3) |
| T102 | E INV-LEX | the same S-id moved into an EVIDENTIARY location (`superseded_by_sources`) | BATCH-FAIL R7-E (judged as evidence: a claim is created) |
| T103 | U DC-1 | `escalations[*].field` = `timeline[<own S-id>].order`, SCHEMA-LIMITATION, without a SCHEMA-LIMITATION register record | BATCH-FAIL by G-09 only; U = T (the location is typed and satisfies the rule) |
| T104 | U DC-1 | `escalations[*].field` with a foreign S-id, several S-ids, free text, or a key outside {order, date_applies_to_file, change_vs_previous} | BATCH-FAIL R7-U |
| T105 | S v2.5-S | the exact upper bound equals its definition (largest D with P(X ≤ d) ≥ α) on all small (N, n, d); n = 0 → N | STATISTICS-VALID (bound as defined) |
| T106 | S v2.5-S | coverage: for every true D, P_D(D ≤ U_h(X)) ≥ 1 − α, exhaustive on all small (N, n) | STATISTICS-VALID (coverage ≥ 1 − α) |
| T107 | S v2.5-S | census → d exactly; d = 0 agrees with the R6 zero bound at the proposed sizes | STATISTICS-VALID (consistent) |
| T108 | S v2.5-S | Bonferroni combination of two sampled strata covers D₁ + D₂ with probability ≥ 1 − α, exhaustive | STATISTICS-VALID (coverage ≥ 1 − α) |
| T109 | S v2.5-S | d < 5 in a sampled stratum → `ci95_share` null, interval informational; d ≥ 5 ∧ n − d ≥ 5 → CI reported; α missing or outside (0, 1) | STATISTICS-REJECTED for a bad α; otherwise STATISTICS-VALID as described |
| T110 | E v2.6-DC3 | a file decided FALSE-HIT: not read, NOT-CONSUMED record, dispositions BINARY-DECIDED / all FALSE-HIT, no CONTRACT-DEVIATION | BATCH-PASS |
| T111 | E v2.6-DC3 | a file decided NOT-CONSUMED-ESCALATED: not read, NOT-CONSUMED record, dispositions NOT-CONSUMED-ESCALATED / all ESCALATED, CONTRACT-DEVIATION naming it; affected GENUINELY-UNDEFINED absences ESCALATED | BATCH-PASS |
| T112 | E v2.6-DC3 | a disposition ≠ its decision (either direction) | BATCH-FAIL R7-E |
| T113 | E v2.6-DC3 | NOT-CONSUMED-ESCALATED without a CONTRACT-DEVIATION escalation naming the file | BATCH-FAIL R7-E |
| T114 | E v2.6-DC3 | an unread required file with no decision (no header binding) | BATCH-FAIL (historical G-04) |
| T115 | U v2.6-DC3 | decisions file bytes ≠ `r7_binary_decisions_sha256` | BATCH-FAIL R7-U |
| T116 | U v2.6-DC3 | the frozen plan omits the derived `binary_decisions` | BATCH-FAIL R7-U (plan ≠ derivation) |
| T117 | U v2.6-DC3 | a decision on a file the reader would NOT refuse (text bytes) | BATCH-FAIL R7-U (reader/verifier disagreement) |
| T118 | U v2.6-DC3 | a required binary file without a decision | BATCH-FAIL R7-U (missing decision) |
| T119 | U v2.7 | every label's SLICE-VIEW present, equal to `view(slice)`, bound in I(run) | BATCH-PASS |
| T120 | U v2.7 | a SLICE-VIEW with one altered byte | BATCH-FAIL R7-U (view ≠ view(slice)) |
| T121 | U v2.7 | a label's SLICE-VIEW missing | BATCH-FAIL R7-U (missing view) |
| T122 | U v2.8(a) | two register-bearing labels whose records carry `<B>-R7` with n = 1000·L + k | BATCH-PASS |
| T123 | U v2.8(a) | an object or a register record carries the dispatched run id | BATCH-FAIL (historical G-07 provenance ids) |
| T124 | U v2.8(a) | two final runs each number their records from 1 | BATCH-FAIL (historical G-07 repeated ids) |
| T125 | U v2.8(a) | a record n outside its label's range that collides with nothing | CONTRACT-FAIL (assembly refused, R11; the verifier alone cannot detect it) |
| T126 | R v2.8(b) | the assembly written by `orchestrate assemble` from the final runs' outputs | BATCH-PASS |
| T127 | R v2.8(b) | the EMPTY object equals Annex E (Tier Z; Tier U ROW-0/3/4; hub EMPTY with LOAD escalations) | BATCH-PASS |
| T128 | R v2.8(b) | a register or P1-gap record naming a label other than its run's owner (including an EMPTY label) | CONTRACT-FAIL (assembly refused, R3′) |
| T129 | W v2.8(b) | the assembly bytes ≠ the hashes printed at the ASSEMBLED marker | CONTRACT-FAIL (freeze-final refused) |
| T130 | R v2.8(c) | an in-checklist EMPTY label with `checklist_examined` 1..23 and the VACUOUS disclosure | BATCH-PASS |
| T131 | R v2.8(c) | an in-checklist EMPTY label without the full 1..23 | BATCH-FAIL (historical G-09) |
| T132 | U v2.8(d) | attempt 2 after a governed retirement, Legacy(B) re-frozen | BATCH-PASS |
| T133 | U v2.8(d) | a retired attempt unlisted, not re-frozen, edited or partly deleted | BATCH-FAIL R7-U (namespace) |
| T134 | W v2.8(d) | attempt 2 dispatched in attempt 1's orchestrator session | BATCH-FAIL R7-W (W2) |
| T135 | U v2.8(d) | a new attempt after any VERIFIED entry, beyond M = 4, or for class H, D, S or U | CONTRACT-FAIL (state refused) |
| T136 | U v2.8(f) | a hub run displaying exactly R(run) | BATCH-PASS (coverage reported COVERED) |
| T137 | U v2.8(f) | a hub run displaying no DIMENSION record | BATCH-PASS (coverage reported NOT-COVERED; report-only) |
<!-- TESTS-END -->

The matrix also runs the re-audit's MATERIAL reproductions in R7 form: P10/P10b/P10c, E11a–c, R-*-tsum/-sup, EM13/14, ST3–ST7.

## 12. Design-freeze decisions (human)

| # | Decision | Proposal / status |
|---|---|---|
| **FD-1′a** | the one-directional constraints CONTRADICTS ⇒ ∈ `contradicted_by` (target = predecessor) and RETRACTS ⇒ ∈ `rejected_by` | adopt (§4.1.3) |
| **FD-1′b** | the list assignment for later points: RESTATES → `later_support`; EXTENDS, NARROWS, CHANGES-* → `later_refinement` | **FROZEN (G-LOG-0088): EXTENDS → `later_refinement`.** A relationship classification, not a truth judgment; EXTENDS is never SAME, RESTATES, corroboration or canonical identity |
| **FD-1′c** | the lateness tightening: `later_*` requires ESTABLISHED precedence (§4.1.5) | adopt, or rule otherwise |
| **FD-2′** | contradiction schema `{source_id, contradicts}`; relation ≠ precedence; precedence from A.10 dated positions only; no STEP-NUMBER precedence | adopt (§4.2) |
| **FD-3′** | SUPERSEDED-bearing lifecycle ⇔ a non-empty `superseded_by_sources` | adopt (§4.3) |
| **FD-4′** | a preservation archive outside the repo; read-only; retained until P7 closes; a named location | **FROZEN (G-LOG-0088): `~/knowledgeos-witness-archive/`.** Verified outside git, writable, on persistent storage (btrfs on LUKS, `/home`); a single local disk, so persistent but not backup-redundant. Preservation, not trust; valid only when it matches the freeze digests |
| **FD-5′** | the self-describing R7 header with the reader-generated `inv` | adopt |
| **FD-6′** | F1 registry sovereignty + F2 consumer conformance, replacing the rule of force | adopt (§3.2) |
| **FD-7′** | the W1 decision table; background dispatch; **no agent resumption in S5** | adopt (§5.2) |
| **FD-8** | W8 execution-capability closure, with the SEAL stop; orchestrator-pre-created run directories | adopt (§5.5) |
| FD-9 | minimum quote length | **not adopted** (G-LOG-0086) |
| **FD-10** | the layered verdicts (DR-15): BATCH-PASS / STATISTICS-VALID / PROGRAM-ACCEPTED; no bare PASS | adopt (§0) |
| **FD-8′** | W8 with the fourth capability *Read of I(run)* (the bounded, frozen, hash-bound input manifest; §5.5, §5.8), per the human decision G-LOG-0087 (Option A). This supersedes the FD-8 wording | decided (Option A); freeze pending |

**Freeze status (G-LOG-0088):** every FD above is **FROZEN** as recorded. FD-1′c (`later_*` requires ESTABLISHED precedence) knowingly tightens rev3. FD-9 is not adopted.

## 13. Carried over / changed / dropped

| Item | Status |
|---|---|
| **Carried over from R6** | plan derivation and hash; the reading rule's exempt class set; S1 (exact tokens, quotes in bytes, register + OTHER escalation); S2; S3 over object + register + P1-gap **with the canonical `SID_RANGE` matcher and normalized pointer matching** (replacing `RANGE6`; m5/m6); EMPTY full checks; the frozen statistical specification text; the ML boundary; the exposure/SEAL logic (now enforced through W8) |
| **Changed** | claims (discovery + validation + registry); runs (total namespace + legacy digest); execution facts (witnessed); stages (derived); facts (byte-exact anchoring); statistics (domain-enforced); summary relations (object-level); contradiction (a relation plus computed precedence) |
| **Dropped as authorities** | READ-LOG, R6-PROVENANCE, RUN-MANIFEST times, declared stage times, and the prose "rule of force" |

## 14. ML boundary (unchanged; no ML in R7)

**No ML in:** source observation, witness creation, evidence classification, claim enumeration, provenance, reading state, estimator input, or any verdict (BATCH-*, STATISTICS-*, PROGRAM-ACCEPTED).

**Future interface (post-adjudication only):**

> verified/adjudicated S5 data → candidate retrieval → uncertainty ranking → anomaly detection → review prioritization → human adjudication → labelled data.

**ML optimizes review cost, not truth determination.** It never determines truth, identity, equivalence, evidence, provenance, births, edges, estimator inclusion, any verdict (BATCH-*, STATISTICS-*, PROGRAM-ACCEPTED), canonicalization or theory.

## 15. Activation checklist (human acts; NOT performed)

1. Design freeze (§12).
2. Implementation and engineering verification.
3. ONE narrow independent R7 audit (HD-9).
4. Human acceptance.
5. Binary pre-classification and the 12 per-file decisions.
6. Freeze the sample parameters and seed (anchored).
7. Manifest regeneration: revision 7, the addendum sha, `r7_plan_sha256`, `legacy_ledger_sha256`.
8. The S5 EXECUTION AUTHORIZATION.

## 16. Design-repair decisions (DR; G-LOG-0086)

| DR | Closes | Decision |
|---|---|---|
| DR-01 | PF-1 | summary relations are object-level; membership + inheritance + one-directional class constraints; no equality (§4.1) |
| DR-01b | PF-1 | `later_*` requires ESTABLISHED precedence (a semantic tightening; FD-1′c) |
| DR-02 | PF-2 | contradiction relation ≠ temporal precedence; precedence computed from A.10 dated positions; never array, list or `source_id` order (§4.2) |
| DR-02b | PF-2 | STEP-NUMBER is not used for precedence (rev3 §14.4c limits it to within-series ordering and excludes it from temporal scope) |
| DR-03 | PF-3 | discovery (A) ≠ validation (B); missing keys are validation failures (§3.1) |
| DR-04 | PF-4 | registry sovereignty F1 (value-discriminated, closed enums) + consumer conformance F2; the rule of force is withdrawn (§3.2, §3.4) |
| DR-05 | PF-5 | W8 execution-capability closure; SEAL carried forward; orchestrator-pre-created run directories (§5.5) |
| DR-06 | PF-6 | the W1 completed-dispatch decision table; no agent resumption in S5 (§5.2) |
| DR-07 | PF-7 | W7a witness monotonicity (§5.7) |
| DR-08 | PF-8 | byte-exact anchoring by offset and interval containment; cross-page contiguity and same run; only N-WS normalization, with an offset map (§6) |
| DR-09 | PF-9 | a neutral transcript syntax decoder; no dependency on V1.2.4 (§5.6) |
| DR-10 | PF-10 | reader-generated invocation id; a convenience join key only (§5.6, §9) |
| DR-11 | PF-11 | the archive is preservation, verified by committed digests (§1) |
| DR-12 | PF-12 | truth strata; execution truth ≠ semantic truth (§1) |
| DR-13 | PF-13 | five bounded contexts; the verifier composes predicates only (§0) |
| DR-14 | PF-14 | REJECT vs NOT-ESTIMABLE; N_h = 0 empty stratum; π_h from the realized n_h (§8) |
| DR-15 | freeze-readiness clarification (no new scope) | no bare PASS: BATCH-PASS := U ∧ W ∧ E ∧ R (strong-Kleene three-valued: BATCH-PASS / BATCH-FAIL / BATCH-UNDETERMINED); STATISTICS-VALID := S (VALID / NOT-ESTIMABLE / REJECTED; any undefined component of τ̂, V̂ or the CI ⇒ NOT-ESTIMABLE); PROGRAM-ACCEPTED := ∀b BATCH-PASS(b) ∧ STATISTICS-VALID, a mechanical precondition for, never the act of, governance acceptance; failure tags = predicate letters (R7-U/W/E/R/S); the object schema is written Σ; tests T77–T83 (§0, §8, §10) |
| DR-16 | freeze-readiness clarification (no new scope) | W1 reason codes: distinct machine-testable failure states; truncation is a cause observed as COUNT-MISMATCH (clean) or PARSE (mid-line) (§5.2) |
| DR-17 | FR-1 (Option A, G-LOG-0087) | W8 gains a fourth disjoint capability, *Read of p ∈ I(run)*. `RunInputManifest` (Universe) is frozen at dispatch, hash-bound, with closed categories (CONTRACT, ADDENDUM, PLAN, SLICE, UNIT-RECORDS for the same label's synthesis, DECLARED-SYNTHESIS-INPUT) and hard prohibitions (`R7-U`, reported, never removed). Persisted tool outputs form the only runtime-membership class: the rule is frozen at dispatch and membership is witnessed (announced by the harness in the same agent's own transcript). The `read-input` witness event is distinct from the reader's `read`. Input reads are never evidence. Enforcement is by the witness (hooks are optional defence in depth). Tests T84–T97 (§5.5, §5.6, §5.8, §6) |
| DR-18 | NDB-1, DC-1 (v2.4, G-LOG-0089) | three META rows for the rev3 free-text locations (`absences.<dim>.reason`, `stage2_dispositions[*].reason`, `timeline[*].historical_position`); `escalations[*].field` META with the own-object self-reference rule; INV-LEX (§3.0); the META type line states the per-row rule reading. No EVIDENTIARY, DERIVED or QUOTE row is added; no consumer, predicate or composer rule changes. RI-1 (incomplete input reads under the harness token cap) is DEFERRED, not accepted. Tests T98–T104 (§3.0, §3.3) |
| DR-19 | B1 D2 (v2.5-S, G-LOG-0093) | the primary statistical statement becomes the exact finite-population upper bound (per stratum at α/H′, Bonferroni-combined over the H′ sampled strata); α is part of the frozen record; the normal CI is reported only when every sampled stratum has d ≥ 5 and n − d ≥ 5, otherwise informational and never a NOT-ESTIMABLE trigger. The point estimate, variance, domain D, REJECT and NOT-ESTIMABLE rules are unchanged. Statistics context only. Tests T105–T109 (§0, §8) |
| DR-20 | DC-3 (v2.6-DC3, G-LOG-0099) | decided binary files: the hash-bound decision records reach agents through the frozen plan (`binary_decisions`) and the verifier through the header binding; a conforming decided file is covered (G-04 read requirement; for FALSE-HIT also the reading-rule, census and READ-COVERAGE exemptions); dispositions must equal the decision (B-form: all 12 decisions honoured); the disposition method `BINARY-DECIDED` is added for FALSE-HIT files; undecided unread files and reader calls on decided files still FAIL. Freeze 1 and the Freeze 2 frame are unchanged. Tests T110–T116 (§6.1) |
| DR-21 | EG-2 (v2.7, G-LOG-0104) | RI-1 mitigation: a lossless, deterministic multi-line SLICE-VIEW per label (one line per JSON leaf; string chunks ≤ 1,000 characters; lines ≤ 8,000); the closed I(run) category `SLICE-VIEW` is added; agents page the view (≤ 25 lines per Read); the Universe check requires view bytes = `view(slice)` and a lossless parse, else `R7-U`. Evidence, reading rules, statistics and both freezes are unchanged. Tests T119–T121 (§5.8, §5.9) |
| DR-22 | the v2.8 package (a)–(g), G-LOG-0106 | (a) record identity: record run_id `<B>-R7`, n = 1000·L + k, EG-5b (§2.6) · (b) the assembly by the tool inside the ASSEMBLED marker and the EMPTY object EMPTY-OBJECT v1, EG-5 (§2.7, Annex E) · (c) vacuous examination of in-checklist EMPTY labels (§2.7) · (d) attempts: governed crash-safe retirement, pre-registered retry policy, M = 4, W-SYS, EG-6 (§2.8; §2.3/§2.4 amended) · (f) hub required view lines R(run), coverage report-only, EG-3 (§5.9a). Part (e) (the EP-01 binding) and part (g) (§21/H-06 under R7, EG-10 runbook step) are governance/tool acts outside this text (G-LOG-0106). The verifier and the reader are unchanged; the freezes, frame, sample and estimands are unchanged. Tests T122–T137 |

## Annex E — EMPTY-OBJECT v1 (v2.8(b)(c), G-LOG-0106)

The EMPTY object's field table is fixed by reference to the reviewed and approved working papers. They are hash-bound here:
- `audit-p3b/eg5/EG5-SPEC-A.md` §3 (the field-by-field construction), sha256 `650d83171b3a0e51caf6c960407f670fa65ee729b0774b13aef5c68b64cc18a5`;
- as amended by `audit-p3b/eg5/EG5-SPEC-A-v2.md` §3 (`author_role`, `generation_parameters` including the rule and mode), sha256 `b97960d0479dd05deb372754bf24f096a409cd2147a0b203a64c7f68a125c57a`;
- and by `audit-p3b/eg5/EG5-SPEC-A-v3.md` §3 (the `checklist_examined` and `generation_parameters.checklist` rows), sha256 `ab9c9f5b7bd230540b8164ad9e4fc4980bc166f51f0d4700ba460a96b0632a38`.

The tool constant `EMPTY_OBJECT_V1` in `scripts/p3b_s5_r7_orchestrate.py` implements exactly this table. T127 compares the tool's output with the golden objects derived from it.

