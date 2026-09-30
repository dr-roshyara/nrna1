# Revision 7: pre-freeze design review

| | |
|---|---|
| **Kind** | ONE bounded pre-freeze design review (EP-03). ⚠ authority: generated. **No code, no repair, no change to any reviewed artifact** |
| **Question** | Is the current R7 design logically and semantically safe to freeze, or are there material design defects to correct before implementation? |
| **Verdict** | **B. NEEDS-PREFREEZE-REPAIR:** 8 MATERIAL design defects (PF-1…PF-8), each with a small correction. FD-1, FD-2, FD-6 and FD-7 change, and FD-8 is added |
| **Independence limit** | **the reviewer is the author of the R7 design** (the same assistant, same session). The review is independent of any R7 *implementation* (none exists), **not of the design's author**. Correlated blind spots are possible; the human should weigh the findings accordingly |

## 1. Scope and authority

- **Authority:** the human instruction "PRE-FREEZE DESIGN REVIEW" (2026-09-26), under G-LOG-0085.
- **Scope:** Reviews 1–16 of that instruction, at contract and design level.
- **Excluded:** corpus reading, implementation, and any change to the addendum, ADR, plan or TODO.

## 2. Reviewed artifacts

| # | Artifact | Used for |
|---|---|---|
| 1 | Master Protocol v3.5 (via rev3's citations: R1, R17, R19) | the chronology principle (R1: ingestion order is not argument order) |
| 2 | rev3 contract `prompts/20260925_1204_p3b-agent-contract-r2.md` | §14.2–14.5, A.10, the object schema (l.255–299), D4 (l.739) |
| 3 | the R6 re-audit (G-LOG-0084) | M1–M4, m1–m10, TB1–TB6 |
| 4 | the root-cause analysis (RC-1/2/3) | P\*, E\*, R\*, F\*, T\* |
| 5 | the Model-D readiness result | W-evidence, harness structure |
| 6 | the R7 addendum candidate `prompts/20260926_2400_…-r7-addendum.md` | the object of review |
| 7 | ADR-R7-01 | the trust root |
| 8 | the plan `docs/plans/20260926-1548-s-series-revision-7-plan.md` | boundaries B1–B8 |
| 9 | the S-Series TODO | the critical path |

## 3. Findings

**MATERIAL** means: if the design were frozen as written, an implementation faithful to it would admit a false PASS or a false FAIL, would violate a governing rule, or would need a contract change after coding.

| ID | Sev | Review | Finding (short) | FD affected |
|---|---|---|---|---|
| **PF-1** | MATERIAL | 4 | the FD-1 "equality projection" confuses an **adjacent-pair** relation (`change_vs_previous`) with **object-level** summary relations, and needs precedence that may not exist | FD-1 changes |
| **PF-2** | MATERIAL | 3 | FD-2 defines "earlier" by timeline array order when the points are undated, which **violates R1 and A.10**. The relation and its temporal qualifier are fused | FD-2 changes |
| **PF-3** | MATERIAL | 1 | "schema-independent scan" is claimed to detect missing required keys. It cannot. Discovery and validation are two distinct operations; the design names only one, and **M1's missing-key path is exactly the uncovered one** | FD-6 wording |
| **PF-4** | MATERIAL | 2 | the "rule of force" is informal and consumer-dependent (a circular risk), and typing actually depends on the **value** (FOUND vs other), which the registry does not formalize | FD-6 changes |
| **PF-5** | MATERIAL | 7 / 13 | the witness universe is **not closed over agent tool calls**. Only commands that *mention* the reader are typed; `cat`/Read of corpus paths, redirections and other tools are unconstrained. That is RC-1 recurring inside RC-2, and the R6 transcript scan (SEAL/exposure) is not carried over | new FD-8 |
| **PF-6** | MATERIAL | 7 | the W1 completeness proof is under-specified for resumed agents (several notifications), missing transcripts, in-progress agents and several transcripts per agent. The implementation could pick "any" count | FD-7 extended |
| **PF-7** | MATERIAL | 8 | **there is no witness-monotonicity invariant** between the two freezes. The final witness could silently replace the unit-freeze witness | new W-invariant (no FD) |
| **PF-8** | MATERIAL | 9 | the fact-anchoring rule is ambiguous for multiple occurrences, whitespace-normalized matching and cross-page quotes. The R6 `_in_text` normalization has no byte-offset mapping, so "lies on a witnessed page" is undefined for normalized matches | none (contract text) |
| PF-9 | pre-implementation (non-material) | 6 | production witness authority would depend on the experimental V1.2.4 instrument, and two decoders would read one transcript | plan B3 |
| PF-10 | non-material | 12 | the serial is tied to READ-LOG length (agent-reachable state) and racy under parallel calls | FD-5 changes |
| PF-11 | non-material | 11 | the archive is described next to the trust boundary; it should be stated as *preservation*, validated by the committed digests | FD-4 wording |
| PF-12 | non-material | 5 | "execution truth ≠ semantic truth" is implicit, not stated | wording |
| PF-13 | non-material | 13 | plan B1, B2 and B6 all land in one rules module (a god-object risk) | plan |
| PF-14 | non-material | 10 | the estimator's outcome classes (REJECT vs NOT-ESTIMABLE), N_h = 0 strata, and π_h from the realized n_h are implicit | wording |
| PF-15 | human question | 9 | trivial quotes (such as "the") satisfy in-bytes and on-witnessed-page checks vacuously | optional FD-9 |

---

## 4. Formal invariant review

### Review 1: closed-world logic (PF-3)

The addendum §3.1 claims one "schema-independent recursive scan" that also finds "a missing required key". That is logically impossible: absence is only defined relative to a schema. The architecture actually contains **two operations**:

- **A. Discovery (schema-independent):** Reach(o) = {(p, v) : v is a string containing `\bS\d{4}\b`} ∪ {(p, v) : last-key(p) ∈ SourceKeys}. It finds *present* locations only.
- **B. Validation (schema-dependent):** a closed object schema S (rev3 object record + the §4 amendments). It fixes required keys, forbidden (unknown) keys, value types, closed enums, cardinalities and the uniqueness keys.

**Corrected invariant:**

> Reach(o) ⊆ dom(Registry) (A-closure) ∧ Valid_S(o) (B: required ∧ ¬forbidden ∧ types ∧ enums ∧ cardinality) ∧ Unique_K(o) (no duplicates, conflicting or not).

M1 (`source_id` missing or null) is caught by **B** (required, non-null), **not** by A. A faithful implementation of the current text could omit B and reopen M1.

**Smallest correction:** rename "schema-independent recursive scan" to "**discovery (schema-independent) + validation (schema-dependent)**", and state B explicitly as part of U, reusing the existing schema gate for rev3 fields.

### Review 2: rule of force (PF-4)

- **Status of "downstream force":** not formally defined. It is a meta-rule that references consumers ("the contract gives it downstream force").
- **The risk:** a new consumer that starts reading a META path would *implicitly* upgrade its epistemic type. That circularity is the defect: Type depends on Consumers, and Consumers are unconstrained.
- **Value dependence:** `absences.<dim>` is EVIDENTIARY only when `resolution = FOUND`. `stage2_dispositions[*]` is EVIDENTIARY only when some dimension is FOUND. The `whole` flag of a birth depends on the `BIRTH-UNRESOLVED-*` prefix. So in fact Type = f(path, discriminator), not f(path).

**Smallest formal invariant:**

> **(F1) Registry sovereignty:** Type(p, v) := Registry(p, disc_p(v)), where disc_p is a closed enum validated by B. It is total on dom(Registry) × enum(disc_p). An unknown discriminator value is a B-failure.
>
> **(F2) Consumer conformance:** every consumer rule C (D4, D5, birth, FOUND, lifecycle / `end(p)`, edges, S1–S3, the estimator) declares its read set R(C). Then R(C) ⊆ {p : Registry(p, ·) ∈ {EVIDENTIARY, DERIVED}}. The declarations sit in the registry file, and a test enforces this.
>
> A change of Type or of R(C) is a contract change.

The prose rule of force then becomes a *design guideline* for populating the registry, not an operative rule. **FD-6 should adopt F1 + F2 instead of the prose rule.**

### Review 5: trust-root separation (PF-12)

| Stratum | Truth kind | Authority | Model D covers it? |
|---|---|---|---|
| 1. observation | what the harness recorded | harness transcript | yes (it is the root) |
| 2. execution witness | which reader calls happened, by whom, when, with what page hash | `WITNESS.jsonl` derived from 1 | **yes** |
| 3. source bytes | what the file contains | the seal-aware resolver | no (independent root) |
| 4. semantic evidence | whether a quote *supports* a claim | human review (a declared boundary) | **no** |
| 5. reconstruction interpretation | what the object means historically | the protocol + human adjudication | **no** |

**Execution truth ≠ semantic truth.** Model D makes execution observable (strata 1–2) and, joined with 3, makes *observation of bytes* verifiable. It does **not** establish entailment (4) or interpretation (5). The ADR's residual list names entailment, but the addendum should say it in one line (non-material).

### Review 7: W1 completeness (PF-6)

The addendum proves completeness from the parse, the chain, `uuid` uniqueness and the harness count. The cases below are what the Model-D evidence supports:

| Case | Observable signature | Required verdict |
|---|---|---|
| normal dispatch | transcript + meta + exactly one completion notification; counts equal | proceed |
| zero-tool dispatch | transcript exists (records present), count 0, notification present | observable: a dispatch with no effects. The unit is FAILED if reads were required |
| agent declined | transcript exists; count 1 (handback only); 0 reads | FAILED unit (witnessed) |
| harness-denied call | a tool_result with `is_error` and the classifier text | a `denied` event, which is a stop condition |
| **missing transcript** (meta or dispatch present, no .jsonl) | dispatch in the orchestrator transcript, no subagent file | **FAIL**. The addendum does not state this explicitly |
| **missing harness count** | transcript present, no `queue-operation` notification | **FAIL** (FD-7) |
| **in-progress / partially written** | no completion notification yet | **FAIL** at freeze. A freeze requires every dispatch to be completed |
| **resumed agent** (several notifications for one task-id, as the harness's own notes say can happen) | several `<tool_uses>` values | **undefined in the addendum**: an implementation could take the first, the last or any. Must **FAIL CLOSED**: S5 forbids resuming a dispatched agent (a resumed run is FAILED) |
| duplicated transcript / several files per agentId | two files, one agentId | **FAIL** (the uniqueness key is agentId → file) |
| truncated / corrupted | a count mismatch, parse error or chain break | FAIL (demonstrated) |

**Zero tool calls vs missing transcript vs missing count:** these are distinguishable (file present with count 0 / file absent / notification absent) **only if the three are tested separately**, which the addendum does not require.

**Smallest correction:** add this decision table to §5.1 W1. "Completed dispatch" := exactly one completion notification ∧ exactly one transcript file ∧ count equality. **Extend FD-7** to forbid agent resumption in S5.

### Review 8: witness monotonicity (PF-7)

The two freezes (unit validation, assembly) are specified, but **no relation between them is**. The required invariant:

> **(W7a) Monotonicity:** for every record r ∈ W_unit: r ∈ W_final, byte-identical (no deletion, no replacement, no reinterpretation).
>
> The transcript digests of every agent frozen at the unit freeze reappear unchanged in the final digests: those agents had completed, so their transcripts must not change.
>
> W_final \ W_unit contains only events of agents dispatched after the unit freeze, plus orchestrator events after it.
>
> The extractor sha is identical at both freezes, or both witnesses are re-derived by the same extractor from the archive and compared.

Without W7a, a unit transcript could be replaced between the freezes and the final witness would be self-consistent. **Smallest correction:** add W7a to §5.1 and §5.6. No FD change.

### Review 9: fact anchoring (PF-8)

The current text requires "(a) the quote occurs in the resolver bytes; (b) it lies inside a witnessed page span; a cross-page quote within two witnessed adjacent pages". It is ambiguous in three ways:

1. **Multiple occurrences.** Substring search finds *some* occurrence; (b) needs *an* occurrence located inside witnessed spans. The rule must quantify over occurrences.
2. **Normalization.** R6's `_in_text` also accepts a whitespace-normalized match, which has no byte offsets. For a normalized match, "lies on a witnessed page" is undefined. An implementation could accept a normalized match anywhere in the file, which is a false-PASS path.
3. **Cross-page.** "Adjacent" must mean byte contiguity in the *resolver's* paging (byte_end(k) = byte_start(k+1)), both pages witnessed **by the same owning run**. The match is computed on resolver bytes, not on concatenated transcript text.

**Smallest correction** (contract text only):

> ∃ offset i such that bytes[i : i+|q|] = q (exact bytes, UTF-8), or, where the contract permits whitespace normalization, a match under a normalization **with an explicit offset map back to bytes**. And [i, i+|q|) ⊆ ⋃{[a_k, b_k) : page k witnessed by the owning run}, where the union is taken only over byte-contiguous witnessed pages.

Offsets need not be *submitted* by agents; the verifier computes them. Substring search alone is **not** sufficient without this occurrence-and-offset rule.

**PF-15 (human question, not material):** a trivial quote satisfies (a) and (b) vacuously. Whether R7 sets a minimum quote specificity (for example ≥ 20 non-whitespace bytes) is a governance choice, since entailment is a declared human boundary. Optional FD-9.

### Review 12: reader serial (PF-10, non-material)

"Serial = the index of the READ-LOG line this call appends" couples the reader's output identity to the length of an **agent-reachable file**. It also races under parallel tool calls: the readiness experiment showed that concurrent calls occur, and two reads could compute the same index.

It is not a trust root in the design (W6 reconciles on (run, S, page, page_sha)), but the wording invites misuse.

**Correction (FD-5):** serial := a reader-generated invocation id independent of any file state, for example 16 hex of sha256(run ‖ S ‖ page ‖ reader-start-ns ‖ pid). It is declared a **convenience join key only**: never evidence, never used for completeness or order.

### Review 11: preservation vs trust (PF-11, non-material)

- The trust root is the harness at observation time plus the freeze commit.
- **The archive is preservation, not trust:** it is untrusted storage whose contents are validated by the committed digests.
- Its purpose is reproducibility and re-derivation, which the addendum's §5.6 implements, but §1 lists nothing about the archive, and FD-4 reads like a trust decision.

The stated fields (location, read-only, retention, digest, archive id) are sufficient for re-derivation. **Correction:** one sentence in §1/§5.6 ("the archive is not trusted; it is verified against committed digests") and FD-4 re-labelled as a preservation decision.

### Review 6: parser coupling (PF-9, pre-implementation)

- `p3b_v1_2_4_instrument.parse_transcript` belongs to a pre-registered, experimental classification instrument. Its outputs are shaped for that instrument: Write content is hashed and dropped, tool_result content is discarded except for persisted-output detection, and the first-prompt text is captured.
- Making R7's authority depend on it inverts the dependency (production → experiment).
- It also yields **two decoders over one transcript**, since R7 needs results that the instrument drops. Two decoders can disagree silently on edge cases (non-list content, attachments), a W1/W3 divergence risk.

**Correction (plan B3, no FD):** one pure **transcript syntax decoder** (records → blocks → tool_use/tool_result pairs, `uuid`/`parentUuid`, timestamps, `meta.json`, queue notifications) in a neutral module. R7 witness semantics sit on top of it. V1.2.4 stays untouched and is *not* a dependency of R7.

---

## 5. Semantic review

### Review 3: timeline and contradiction semantics (PF-2)

**Governing text:**
- chronology uses "dates only, never `source_id` order (v3.5 R1)" (rev3 l.1206);
- "Inside a BULK/mtime block, order is UNORDERED (v3.5 R1)" (l.1157);
- a dated position requires EXPLICIT basis, order evidence other than SOURCE_ID only, no BULK block, and `date_applies_to_file: CONFIRMED` (A.10);
- a STEP-NUMBER orders only within its own series and is not used across series.

**The defect:** addendum §4.2 rule 2 says the target must be "**earlier** … (by dated position where dated; **otherwise by the object's timeline order**, with `order` recorded)". The "otherwise" clause makes **array position a historical precedence**, which R1 forbids. In an UNORDERED-BLOCK, or for undated points, "earlier" is not mechanically meaningful.

**Separation needed:** a contradiction is a relation between two *statements*. It exists whether or not their historical order is known. Only the temporal qualifier needs precedence.

**Smallest correction** (one additional FD-2 rule):

> `contradicted_by[*] = {source_id, contradicts}`. The relation is valid on its evidence alone (CONTRADICTS point + CONTRADICTION fact with target + D4 linkage). Its precedence is **computed, never declared**:
> - `ESTABLISHED` iff both points have dated positions (A.10) and date(target) < date(source), strictly;
> - or, if the contract admits it for precedence, both lie in the same STEP-NUMBER series with target < source;
> - otherwise `NOT-ESTABLISHED`.
>
> Array order never contributes. A NOT-ESTABLISHED contradiction may feed D4, since D4 needs contradiction evidence, not order. It may **not** support any "later/earlier" statement, including `later_*` fields or `end(p)`.

Whether step-number series count for precedence is a contract question. A.10 uses them only within a series; the conservative default is **no**.

### Review 4: FD-1 semantic validation (PF-1)

**Governing definitions:**
- `change_vs_previous` classes are "**descriptive**: they say what the text does **relative to the previous point**" (l.1170). They describe an adjacent-pair relation in the timeline list.
- The summary fields are listed by name only (l.1175). §14.5 groups `later_support[]` and `later_refinement[]` together as "evidence of **later** discovery, extension or refinement" and `contradicted_by[]` as a contradiction "together with the Track-A statement it contradicts".
- **No governing text maps classes to summary fields.**

**Classification of the proposed mapping:** **D, a mixture.**
- `first_<kind> = births.<kind>` is a **pure projection** (A): the contract defines it as "= v3.5's five birth kinds".
- `RESTATES → later_support`, `CONTRADICTS → contradicted_by` and `RETRACTS → rejected_by` are **semantic interpretation** (B): plausible, but nowhere defined.
- `EXTENDS → later_refinement` is **ambiguous**: §14.5 lists "extension" without assigning it, and it could equally be "support".
- The "equality, excluding the first point" semantics is a **schema/semantics change** (C): the contract never says a summary equals any function of the timeline.

**Why equality is unsound, not merely unconfirmed:**
1. **Adjacent vs object-level.** Source S3 contradicts S1's statement; S4 restates S3. S4's class is RESTATES (relative to S3), yet S4 also contradicts S1. Under equality, S4 cannot appear in `contradicted_by`, and S3 must, even if the researcher's object-level judgment differs. The projection conflates "relative to the previous point" with "relative to the object".
2. **Precedence.** `later_*` asserts temporal lateness relative to first appearance. For undated or UNORDERED-BLOCK points, lateness is not established (PF-2). A projection "excluding the first point" uses array order.
3. **Previous point in unordered blocks.** `change_vs_previous` itself is defined against the array predecessor even inside UNORDERED blocks (rev3 F1 keeps the point's order value). That is existing rev3 semantics, out of R7 scope, but R7 must not *amplify* it into precedence claims.

**Answer to the review question:** the mapping **introduces a new interpretation.** It does not merely preserve existing semantics.

**Smallest correction (FD-1 changes):** replace "equality projection" with **typed summary relations**:

> Each summary entry is an object-level relation whose source must be (i) a timeline point of the object, so it inherits that point's evidence and reading rule.
>
> (ii) The only class → field constraint is **one-directional and precedence-gated**: a point of class C whose array predecessor strictly precedes it under A.10 must appear in the field mapped from C. No converse is required.
>
> (iii) Entries asserting lateness (`later_*`) require ESTABLISHED precedence over the first dated point; otherwise the entry is recorded with `precedence: NOT-ESTABLISHED`, and the human decides whether such an entry belongs in `later_*` at all.
>
> (iv) The class → field table itself (and EXTENDS in particular) is a **human semantic decision**, recorded in the contract as interpretation, not as projection.

`first_<kind> = births.<kind>` stays a DERIVED equality.

### M3 carry-through

- `superseded_by_sources` as LIFECYCLE, whole-file, with a derived `position` (HD-1): **sound.** A.10's file-level test is already the governing rule (l.1165–1167).
- FD-3 (`SUPERSEDED` ⇔ a non-empty list): consistent with l.1165–1166 ("the sources corroborating a SUPERSEDED lifecycle"). One caveat: `current_lifecycle` is "still SOURCE-CLAIMED-* until corroborated" (l.1176), so the equivalence should read *SUPERSEDED-bearing lifecycle values*. That is a wording point for FD-3, not material.

---

## 6. Statistical review (Review 10)

Treat `estimate_v7 : Input → {REJECT(reason), NOT-ESTIMABLE(reason), Estimate}`.

The validated domain D is every input satisfying:
- frame = plan labels, with the hash matching the frozen record;
- strata keys ⊆ the 5 names, forming a partition of the frame;
- membership = the derived rule;
- rates ∈ [0, 1];
- the frozen record's hash = the anchored value;
- sha(actual sample) = the frozen sample hash;
- each s_h ⊆ U_h, pairwise disjoint.

| Check | Assessment |
|---|---|
| outside D → REJECT | coherent. **Clarify** two outcome classes: REJECT = the input is invalid or tampered; NOT-ESTIMABLE = the input is valid but the estimand is unidentified (n_h = 0) or the variance is undefined (n_h = 1). Never a number outside D (PF-14) |
| complete frame, disjoint, exhaustive, derived membership | coherent; enforced |
| n_h = 0, N_h > 0 | NOT-ESTIMABLE for τ(U) and p(U) (HD-6): **correct**, since the HT unbiasedness condition π_i > 0 fails. **Clarify** that N_h = 0 strata (for example HUB, if hubs lie outside the 1,975) are empty and **not** a NOT-ESTIMABLE trigger |
| n_h = 1 | point estimate allowed; V̂ and the combined CI NOT-ESTIMABLE: **correct** (s_h² has denominator n_h − 1) |
| census | V_h = 0 exactly: correct |
| π_h | must be computed from the **realized** n_h = round_half_up(rate · N_h), not from the rate. Implicit in the text; state it. If rate · N_h rounds to 0 with rate > 0, the result is n_h = 0, hence NOT-ESTIMABLE. Correct, but the activation act should know it |
| variance / CI | formulas unchanged (the frozen SRSWOR variance, normal CI with the FPC). Not redesigned |
| zero bound | hypergeometric; census 0; n = 0 → 1.0 per stratum. Combined: NOT-ESTIMABLE if any stratum has n = 0. Coherent |
| exact-enumeration verification | appropriate (the re-audit's ST9 method; E[τ̂] = τ and E[V̂] = Var by enumeration on small N) |
| ML exclusion | enforced by hashing the actual sample: coherent |

**Result:** mathematically coherent. **No material statistical defect.** PF-14 is wording.

---

## 7. DDD boundary review (Review 13; PF-13, PF-5)

| Context | Question it answers | Owns | Must not own |
|---|---|---|---|
| **Universe** | What is allowed? | the run namespace, revision and contract binding, the object schema (B), the typing registry (F1), the consumer read sets (F2), **the agent tool-call grammar (PF-5)** | execution facts; statistics |
| **Witness** | What happened? | the syntax decoder (PF-9), dispatch binding, W1–W5, W7/W7a, `WITNESS.jsonl`, freezes | claim semantics; bytes' meaning |
| **Evidence** | What source bytes were observed and cited? | coverage from the witness, reading state, fact anchoring (PF-8), claim resolution, S1–S3 | run legitimacy; estimation |
| **Statistics** | Is the mathematical question defined? | `estimate_v7`, the domain D, the frozen record | anything about batches' validity |
| **Verifier** | Do all invariants hold? | composition of the four, and W6 reconciliation as the **cross-context policy** | any rule logic of its own |

**God-object risk:** plan B1, B2 and B6 all name `p3b_s5_r7.py`, which would hold Universe, part of Evidence and Statistics. **Correction (plan only):** separate modules along the table (for example `r7_universe`, `r7_witness`, `r7_evidence`, `r7_stats`, `r7_verify`), with dependencies only in the direction Verifier → {Universe, Witness, Evidence, Statistics} and Evidence → Witness (read-only).

**PF-5 (MATERIAL), placed here:** the agent tool-call universe belongs to *Universe* but is missing.
- The addendum types only Bash commands that mention the reader.
- An agent could `cat` or Read a corpus path, write through shell redirection, or call other tools, and nothing in R7 would type or reject it. R6 handled exposure through the runbook's transcript scan (`scan_level_v6`: SEAL/H-19 exposure, outside-reader access). R7 does not carry that over.
- Because R7 already parses every transcript, the closed world must extend to tool calls:

> **(W8) Tool closure:** every agent tool call ∈ {canonical reader invocation · Write to a path permitted for the run (its record, object, register, P1-gap, lint and claim-evidence files) · the harness's final handback}. Every other tool call, or any Bash command other than the canonical reader, is a FAIL, and a hold-out-bearing one is a SEAL stop.

**New FD-8** (the agent tool allowlist; it subsumes the R6 transcript scan). Without W8, bytes could be observed outside the witness, and hold-out exposure would go unverified by the PASS path.

---

## 8. ML boundary review (Review 14)

**Confirmed:** no ML enters source observation, witness creation, evidence classification, claim enumeration, provenance, reading state, estimator input or PASS/FAIL, per addendum §14 and ADR-R7-01. The design is deterministic throughout (decoding, registry typing, byte matching, HT).

**Future interface (architecture only):**

```
verified / adjudicated S5 data (PASS batches + human adjudications)
  → candidate retrieval (similar objects, possible duplicates)
  → uncertainty ranking (which labels are most likely discordant)
  → anomaly detection (unusual evidence patterns)
  → review prioritization (ordering of the human queue)
  → human adjudication
  → new labelled data  → (retrain; evaluate by precision/recall, calibration, workload reduction)
```

**ML optimizes review cost, not truth determination.** It never alters the probability sample, inclusion probabilities, evidence, identity, equivalence, births, edges, canonicalization, truth or theory. Its queue is separate from, and never feeds, the estimator.

---

## 9. Test sufficiency (Review 15)

Of the six dimensions (positive, direct negative, combination, boundary, malformed, missing), the 38-case matrix covers direct negatives well. It has **genuine gaps**:

| Area | Missing |
|---|---|
| Discovery vs validation (PF-3) | a missing required key other than `source_id` (for example `change_vs_previous`); an unknown non-S-id key (forbidden key); an unknown enum discriminator (`resolution: "FOUNDX"`); a cardinality violation (two timeline points with one `source_id`) |
| Consumer conformance (PF-4) | a static test: every consumer's read set ⊆ EVIDENTIARY ∪ DERIVED |
| Unordered chronology (PF-2) | **positive boundary control:** a valid contradiction between undated or UNORDERED-BLOCK points with `precedence: NOT-ESTABLISHED` → PASS (guards against false FAIL). Negative: a declared ESTABLISHED precedence between undated points → FAIL; a `later_*` entry relying on array order → FAIL |
| Contradiction target ambiguity | a target not in the timeline; the target = the source itself; a target in another label |
| Tool closure (PF-5) | an agent `cat`/Read of a corpus path; a Bash redirection write; a Write to an unpermitted path; a non-reader Bash command |
| W1 table (PF-6) | a zero-tool dispatch (the unit FAILED, the verdict explicit); a missing transcript; a missing count (distinct from the zero-tool case); a resumed agent (two notifications); two transcripts per agentId; a dispatch without completion at freeze |
| Monotonicity (PF-7) | a unit transcript replaced between freezes; a W_unit record missing from W_final; a changed extractor sha between freezes |
| Quote anchoring (PF-8) | a quote across two contiguous witnessed pages (positive); across two pages where one is unwitnessed; across pages witnessed by different runs; multiple occurrences where only the unwitnessed one matches exactly; a whitespace-normalized match outside witnessed spans |
| Missing input | an absent archive → `UNVERIFIABLE-WITNESS`; an unknown transcript record type |
| Statistics | an N_h = 0 stratum (must *not* trigger NOT-ESTIMABLE); rate · N_h rounding to 0; a tampered frozen record with a recomputed internal hash but a wrong anchor (in addition to #34) |
| Combinations | PF-2 + PF-8 together (a NOT-ESTABLISHED contradiction whose quote is on an unwitnessed page) |

Existing cases #1–#38 remain valid. With the PF corrections, #7 and #8 need their expected tag checked against B (validation) vs A (discovery).

---

## 10. Final verdict

**B. NEEDS-PREFREEZE-REPAIR.**

| ID | Violated invariant | Why it matters | Smallest correction | FD change |
|---|---|---|---|---|
| **PF-1** | FD-1 semantic soundness: an adjacent-pair class ≠ an object-level summary relation; `later_*` needs precedence | equality would force false FAILs (the S4-restates-S3 case) or encode array order as lateness; it is a new interpretation, not a projection | typed summary relations: timeline membership + a one-directional, precedence-gated class constraint; the class → field table is recorded as a human interpretation; `first_* = births` stays DERIVED | **FD-1 replaced** |
| **PF-2** | R1 / A.10: never infer precedence from array order | the "otherwise by timeline order" clause violates R1 | separate the relation from the qualifier; precedence is computed (ESTABLISHED / NOT-ESTABLISHED) from A.10 dated positions only; NOT-ESTABLISHED may feed D4, never `later_*` or `end(p)` | **FD-2 + one rule** |
| **PF-3** | closed-world logic: absence needs a schema | M1's missing-key path is uncovered by "schema-independent" discovery | name discovery (A) and validation (B); state B (required, forbidden, types, enums, cardinality, uniqueness) as part of U | FD-6 wording |
| **PF-4** | registry sovereignty; no consumer-driven typing | a new consumer could silently upgrade META; value-dependent typing is unformalized | F1: Type = Registry(path, disc(value)), with closed enums; F2: consumer read sets ⊆ EVIDENTIARY ∪ DERIVED, tested | **FD-6 replaced by F1 + F2** |
| **PF-5** | closed world over agent tool calls (RC-1 inside RC-2) | reads outside the reader and hold-out exposure escape the PASS path; the R6 transcript scan is not carried over | W8 tool closure: {canonical reader, permitted Write, handback}; everything else FAILs; a hold-out-bearing call is a SEAL stop | **new FD-8** |
| **PF-6** | W1: every ambiguity fails closed | resumed agents or missing transcripts could be counted inconsistently | the W1 decision table; "completed dispatch" defined; no resumption in S5 | **FD-7 extended** |
| **PF-7** | witness monotonicity W_unit ⊆ W_final | a unit transcript could be replaced between the freezes undetected | W7a: byte-identical inclusion, unchanged unit digests, the same extractor | none (new W-invariant) |
| **PF-8** | F\* anchoring well-defined on bytes | multiple occurrences and normalized matches make "on a witnessed page" undefined, which is a false-PASS path | occurrence-and-offset rule on resolver bytes; normalization only with an offset map; cross-page = byte-contiguous pages witnessed by the same run | none (contract text) |

**Non-material, but correct before implementation:**
- PF-9: a single neutral transcript syntax decoder; V1.2.4 is not a dependency (plan B3).
- PF-10: serial = a reader-generated invocation id, convenience only (FD-5).
- PF-11: the archive = preservation verified by digests (FD-4 wording).
- PF-12: state "execution truth ≠ semantic truth".
- PF-13: split the modules along the DDD table (plan).
- PF-14: estimator outcome classes; N_h = 0; realized n_h.

**Human question:** PF-15, a minimum quote specificity (optional FD-9).

**Not changed by this review:** the addendum, the ADR, the plan and the TODO are unmodified; nothing is implemented; no corpus was read; no S5; no activation.

**Next human decision:**
- (i) accept or reject PF-1…PF-8;
- (ii) decide FD-1′, FD-2′, FD-6′, FD-7′ and FD-8 (and optionally FD-9);
- (iii) then either authorize a **revision of the design-freeze candidate** incorporating the accepted corrections, or freeze as is.
