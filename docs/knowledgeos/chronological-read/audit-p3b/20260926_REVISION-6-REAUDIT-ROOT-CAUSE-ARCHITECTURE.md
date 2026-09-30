# Revision-6 re-audit: root-cause architecture analysis (PROPOSAL; no code)

| | |
|---|---|
| **Kind** | Architecture analysis (EP-03 readiness input). ⚠ authority: generated. **It authorizes nothing, repairs nothing and is not committed** |
| **Evidence basis** | the independent re-audit `audit-p3b/20260926_REVISION-6-INDEPENDENT-RE-AUDIT.md` (G-LOG-0084, NEEDS-REVISION) and its scripts in `audit-p3b/r6-audit/`. **The Revision-6 implementation record is not used as evidence** |
| **Governing text** | rev3 contract `prompts/20260925_1204_p3b-agent-contract-r2.md` (§12, §14.2–14.5, A.10, the D4 table, the object schema) · revision-6 addendum · Master Protocol v3.5 (R1, R17, R19) |
| **Code read (read-only)** | `p3b_read_source.py` (run grammars l.112 and l.147; stdout page header and trailer l.210–213) · `p3b_s5_r6.py` (`enumerate_claims`) · `p3b_s5_r6_verify.py` · `p3b_s5_common.py` · the `parse_transcript` instruments (`p3b_v1_2_3_instrument.py` l.641) · `p3b_ob0018_pilot.ledger_fingerprint` |
| **State** | revision 6 **NOT ACTIVATED** · S5 **NOT AUTHORIZED** · H-19 **SEALED** · manifest revision 3 · nothing changed by this analysis |

**The goal is not to make revision 6 pass.** It is to state what evidence must exist so that PASS means: *the batch was faithfully observed, reconstructed from those observations, and verified*.

---

## 0. Consolidation: 32 findings → 3 root-cause classes

The findings are not 19 independent defects. They fall into **three root-cause classes**, plus boundaries that no mechanism can close.

| Class | Name | Structural cause | Findings |
|---|---|---|---|
| **RC-1** | **Universe mismatch** (enumeration by allow-list) | the verifier checks what it *enumerates*, and the enumeration is a hand-written list of known locations. The reachable space (where a citation can sit, where a read can land) is larger than the enumerated space, and the **residue is silently ignored instead of failing** | M1 (source-less timeline point); M2 (legacy `-R2*` runs); M3 (`contradicted_by`, `superseded_by_sources` and the other summary lists); m1 (non-object timeline entry); m2 (object quotes outside the R6 path); m3 and m4 (duplicates, last-wins); c1 (`-R7` directory ignored); TB5 (revision 7 routes to R6) |
| **RC-2** | **Unwitnessed authority** | execution facts (a read happened, in which run, for which label, when; the dispatch order) are taken from artifacts **the audited party can write**: READ-LOG, records, R6-PROVENANCE, RUN-MANIFEST and claim-evidence. The verifier proves **mutual consistency, not faithful execution** | TB1–TB3, P9b; the conditional closure of R5-02, R5-03 and R5-04; m7 (fact quote not bound to a logged page); m8 (reader and verifier use different plan sources) |
| **RC-3** | **Unvalidated estimator domain** | the statistical functions accept inputs outside the design's domain and return an apparently valid number. **The specification text is coherent; the functions do not enforce it** | M4 (ST3–ST7); ST10, ST11, ST12 |
| — | **Epistemic boundaries** (not defects; never machine-closable) | semantic entailment (does the quote *support* the claim?); S1 honesty (F2, a blanket NOT-DETERMINABLE); whether a WHOLE-FILE read was cognition or only delivery; agent identity beyond the harness record | P6a/b, P14, S1d/e/j/k, E15 |

M1, M2 and M3 look unrelated (a JSON null, a run-id grammar, two summary fields), but they share **one defect of form**. In each case the rule is:

> for x in KnownLocations: check(x)

when it must be:

> Reachable ⊆ Typed, and for every x in Reachable: check_{type(x)}(x), where Reachable \ Typed ≠ ∅ ⇒ FAIL.

Repairing them one by one would leave the form intact, and the next unrecognized location would reopen it. The repair is the **closed-world inversion** of each enumeration.

---

## 1. Claim completeness (Q1)

### 1.1 Definition

An **evidentiary claim** is any statement in a layer-A object whose truth depends on what a source says. Operationally, a claim is present wherever the object:
- **names a source** (an S-id token, or a `source_id` / `sources` / `supplied_by` key, **including a null or missing value where the schema requires one**), or
- **asserts a state that the contract says is established by sources**, for example a birth kind, a FOUND, CONTESTED (D4), a SUPERSEDED lifecycle, or a dated end of life.

The requirement follows directly:

> **Claim totality (invariant P\*).** Let Cit(o) be the set of all (JSON path, S-id-or-null) pairs found by a **schema-independent** scan of object o: every S-id token anywhere in any string value, plus every value of a source-bearing key. Let τ be a **total typing function** over the object schema's paths, with values:
> - EVIDENTIARY(kind, whole);
> - DERIVED(rule);
> - META(⊆ cited);
> - NONE (no S-id may appear).
>
> Then PASS requires:
> 1. every (path, s) ∈ Cit(o) has τ(path) defined; **an unknown path fails**;
> 2. an EVIDENTIARY element has a non-null s ∈ Files(label) and a fact of the right kind in a reading run of the label, subject to the reading rule;
> 3. a DERIVED element equals its rule's projection of already-verified claims;
> 4. a META element ⊆ the label's verified cited sources;
> 5. a NONE path contains no S-id.

This makes the enumerator *discover* locations and *type* them. A field can then escape only by being typed wrongly in the registry, which is a reviewable, one-line decision. It can no longer escape by being unrecognized.

### 1.2 Typing of the object's source-bearing locations (from the rev3 object schema)

| Location | Proposed τ | Evidence required | Currently in R6 enumeration? |
|---|---|---|---|
| `timeline[i]`: the point itself | **EVIDENTIARY**(TIMELINE; whole unless the class ∈ {FIRST, RESTATES, EXTENDS, NOT-COMPARABLE}) | fact TIMELINE; **`source_id` required and non-null** (§14.3: "one entry per source that states something") | only if `source_id` is truthy (**M1**) |
| `timeline[i].states.quote` | EVIDENTIARY (a quote) | in bytes **and on a logged page** of the owning run | no (m2 and m7) |
| `births.<kind>` S-ids | EVIDENTIARY(BIRTH) | fact BIRTH | yes |
| `absences.<dim>.supplied_by` (FOUND) | EVIDENTIARY(ABSENCE/DEFINES, whole) | fact plus the quote in bytes | yes (claim); the quote no (m2) |
| `stage2_dispositions[*]` FOUND | EVIDENTIARY(ABSENCE, whole) | fact | yes |
| `dependency_edges[*]` | EVIDENTIARY(DEPENDENCY) for **every** edge class that cites a source; quote in bytes | fact | only R2-EVIDENCED |
| `semantic_evidence.d4[*]`, `.d5[*]` | EVIDENTIARY(CONTRADICTION / TWO-CONCEPT, whole): they establish CONTESTED and HOMONYM-SPLIT | fact plus quote | **no** |
| `census_reading_disagreements[*]` | EVIDENTIARY(ABSENCE-CENSUS) | quote in bytes | **no** |
| `status_basis.<field>.sources` | META (⊆ the verified cited sources of that status) | — | **no** |
| `hindsight_dependency` | META (⊆ cited) | — | **no** |
| `timeline_summary.first_*` | **DERIVED** (= `births.<kind>`, i.e. v3.5's five birth kinds; §14.3) | consistency | **no** |
| `timeline_summary.later_support[]`, `later_refinement[]`, `contradicted_by[]`, `rejected_by[]` | **DERIVED or EVIDENTIARY: a human decision (§4)** | — | **no (M3)** |
| `superseded_by_sources[]` | **EVIDENTIARY(LIFECYCLE): a human decision (§4)** | — | **no (M3)** |
| `escalations[*].detail`, `semantic_status_note`, and other free text | NONE or META by rule (for example `VERDICT-EVIDENCE-CONFLICT: RPnnnn` carries RP ids, not S-ids) | — | — |

**Answer to Q1:**
- **One** enumeration model: the total scan with one typing registry.
- **Several typed evidence classes** inside it: TIMELINE, BIRTH, ABSENCE, DEPENDENCY, CONTRADICTION, TWO-CONCEPT, LIFECYCLE, CENSUS.
- Plus two non-evidentiary types: DERIVED projections and META references.

The types differ in *what counts as support*. The *coverage* obligation is the same for all of them. **Verdicts** (S1 YES/NO) are a separate evidence class attached to pair checks in the file-reading records, not to the object, and they are already typed. **Quotations** are not claims of their own: they are the carrier of a claim's evidence, and each must be bound to observed bytes (§3).

**Duplicates** (m3, m4): the same totality principle, applied to keys. A registry key such as (pair_id), (label, stage) or (run, source_id, fact_id) that occurs twice **fails**; it is never resolved last-wins.

---

## 2. Execution universe (Q2)

### 2.1 Entities

Let:
- B be the batch and M its manifest entry;
- P = Derive(M, Slices, Bytes, FilesMeta) be the derived plan (the frozen plan must equal P, and its hash must equal M's; this is **closed**: R5-05);
- **Runs(P)**, with its maps owner: Runs(P) → Labels, role: Runs(P) → {SINGLE, UNIT, SYNTHESIS} and perm: Runs(P) → 2^Files, be the **only** runs that may exist for B;
- **Agents(B)** be the agents the orchestrator dispatched for B. Each agent a carries exactly one run r(a) ∈ Runs(P) in its dispatch.
- **W** be the witnessed reads: every reader invocation e that appears in the harness transcript of any a ∈ Agents(B), with (run, batch, label, source, page, page_sha256, ack token, harness time, refused?);
- **L** be the logged reads: every reader-log entry in **any** ledger directory whose name begins with B;
- **E** be the file-reading records; **F** their facts; **C** the claims of the final objects.

### 2.2 The invariant

**E\*** (execution closure):

1. ∀a ∈ Agents(B), ∀e ∈ W(a): run(e) = r(a) ∧ batch(e) = B ∧ label(e) = owner(r(a)) ∧ src(e) ∈ perm(r(a)) ∧ ¬refused(e).
2. ∀a with role(r(a)) = SYNTHESIS: W(a) = ∅.
3. {d : d is a ledger directory, prefix(d) = B} ⊆ Runs(P) ∪ {B-R6 (assembly)} ∪ Legacy(B), and digest(Legacy(B)) = digest frozen at activation.
4. L|_{Runs(P)} = W as multisets over (run, src, page, page_sha256).

**The chain to the claims:**
- **R\*:** ∀x ∈ E: coverage(run(x), src(x)) is computed from W, never from a declaration.
- **F\*:** ∀f ∈ F: quote(f) ⊆ bytes(src) **∧** quote(f) lies in a page span ∈ W(run(f), src(f)).
- **P\*** (§1): ∀c ∈ Cit(o): τ-typed and supported via F.

**Verification** is PASS ⇔ P\* ∧ E\* ∧ R\* ∧ F\* ∧ T\* ∧ L\* ∧ S\*. T\* (temporal) is §3's witness-derived order; L\* (plan) and S\* (synthesis) are unchanged.

### 2.3 Where each illegitimate execution fails

| Illegitimate execution | Fails at | Why |
|---|---|---|
| legacy `OB####-R2`, `-R2.1` or `-R2S` read by a B-agent (M2) | E\*-1 (run(e) ≠ r(a)); E\*-3 (a legacy digest mismatch, because the reader appended to a legacy directory); and prevention: the reader refuses legacy grammars for batches with an activated R6 plan | the run is outside Runs(P), and the witness shows the call even if the log is deleted |
| undeclared R6-grammar run (`-L03U09` not planned) | E\*-3 and E\*-1 | not in Runs(P) |
| syntactically valid run with no plan owner, or a forged `--label` | E\*-1 (label(e) ≠ owner(r(a))) | ownership comes from P; the agent–run binding comes from the dispatch |
| synthesis agent reading under a unit run id | E\*-1 (run(e) ≠ r(a)) and E\*-2 | the witness ties the call to the **agent**, not to the id it typed. Revision 6 detects this only by log time (TB2) |
| deleted refused line | E\*-1 (¬refused(e) over W) and E\*-4 (L ≠ W) | the witness keeps what the log lost |
| hand-written or back-dated log entry (TB1, TB2) | E\*-4 | a log entry with no witnessed counterpart |
| a `-R7` or other unknown-grammar directory (c1) | E\*-3 | namespace totality |
| a read of a B file under another batch id (E15) | E\*-1 (batch(e) ≠ B for a B-agent) | the witness is per agent, so the CLI's batch argument no longer defines scope |

**Revision 6's form, for contrast:** "for d in dirs matching `-R5` or `-R6-`: check(d)". That is an enumeration over names chosen by the writer. E\* quantifies over **agents dispatched** (the orchestrator's acts) and **all B-prefixed directories**, and derives reads from an observer that the agent does not control.

---

## 3. Provenance trust boundary (Q3), the central question

### 3.1 What revision 6 proves today

| Chain link | Proven? | By what |
|---|---|---|
| observed bytes ← resolver | **yes** | seal-aware resolver; content hashes |
| bytes → **plan** | **yes** | independent derivation (L8) |
| reader execution → read event | **no** | READ-LOG is a ledger file the agent's environment can write (TB1); the page hash proves only that *someone had the bytes* |
| read event → run, label, time | **no** | CLI arguments and a reader-written `utc`, both editable (TB2, E15) |
| read event → evidence record | **partial** | coverage is computed from the log, which itself is unwitnessed; facts are not bound to logged pages (m7) |
| evidence record → claim | **referential yes, for the enumerated claims** | claim-evidence plus facts; no semantic entailment (a boundary) |
| orchestration order (validation < dispatch < production) | **no** | R6-PROVENANCE and RUN-MANIFEST are declarations (TB3, P9b) |

**Conclusion:** revision 6 verifies **consistency among agent- and orchestrator-written files, anchored at both ends** (bytes and plan). **It does not prove the middle of the chain.** Trust has moved from the batch declaration to the logs; it has not been removed.

### 3.2 Threat model

The binding threat is **not** a sophisticated attacker. It is:
- (i) an LLM agent that shortcuts, fabricates or misreports, which is the documented failure mode (E0: an off-allowlist Bash command; OB0018);
- (ii) orchestration bugs;
- (iii) unnoticed post-hoc edits.

A malicious **orchestrator or OS user** cannot be defended against by any mechanism local to the same account. That residual must be *named*, not claimed away.

### 3.3 Models compared

| Criterion | **A. Trusted reader and orchestrator logs** (status quo) | **B. Append-only, hash-chained logs** | **C. Reader-authenticated evidence** (MAC or signature at observation time) | **D. Independent-witness reconciliation** (harness transcript as the execution witness; logs and provenance derived from it and reconciled) |
|---|---|---|---|---|
| **Integrity vs threat (i)** | none: an agent can write or delete lines | weak: a writer who can edit can recompute the chain; the chain is only as good as its **anchor**, which is itself a writable file | strong **only if** the key is out of the agent's reach. That needs a tool sandbox, and a sandbox that hides the key already stops log edits | **strong:** the harness records every tool call and result of the agent. The agent cannot suppress or edit its own tool calls through its tools |
| **vs threat (ii) and (iii)** | none | detects partial edits and truncation | detects them | detects them. Digests frozen and committed at validation and assembly make later edits evident relative to the commit |
| **Complexity** | — | moderate: reader changes, chain verifier, anchor design | high: key management, sandbox, reader changes; the orchestrator holds the key anyway | **low to moderate:** `parse_transcript` already exists (with truncation detection) and the transcript scan already consumes transcripts. New: a witness extractor, reconciliation, freezing steps |
| **Auditability** | low | medium | medium: opaque MACs need the key to check | **high:** the witness is the literal record of what the agent did, including byte-mode page headers with `page sha256` and the `END … <ack_token>` trailer the reader prints to stdout (verified in `p3b_read_source.py` l.210–213) |
| **Reproducibility** | — | yes | yes, with the key | yes, from the archived transcript. **Caveat:** transcripts contain corpus page text, so they must be archived **outside the repo** (like the T-A bundle), with the hashes committed |
| **Master Protocol v3.5** | compatible | compatible | compatible | compatible: no change to reconstruction semantics, R1 or R17 |
| **R19** (the floor: all 396 batches) | — | + reader overhead | + key operations | + one extraction per agent; no extra corpus reads |
| **Architecture or implementation?** | — | implementation | **architecture** (a new trust root: the key) | **architecture:** it changes the **verification trust root** (from logs to witness) and adds a witness artifact and two runbook freeze steps. The reconstruction architecture is unchanged |

### 3.4 Recommendation: Model D-minimal (with B as an optional hardening)

D-minimal is the smallest mechanism that closes the chain against the real threat:

1. **The witness is authoritative for execution.**
   - For every dispatched agent, extract from its harness transcript the canonical list of reader invocations: run, batch, label, source, page, `page sha256`, ack token, harness timestamp, refused.
   - The agent–run binding comes from the orchestrator's **dispatch record**: the Agent call and its prompt in the orchestrator transcript.
2. **The logs become reconciled artifacts.** L|_{Runs(P)} must equal W (E\*-4), and coverage, reading state and fact–page binding are computed from W.
3. **Orchestration times are derived, not declared.**
   - units-validated is the harness time of the orchestrator's validation command, whose output prints the unit-record hashes.
   - synthesis-dispatched is the harness time of the Agent call.
   - produced is the synthesis agent's last Write.
   - R6-PROVENANCE becomes a derived report that the verifier recomputes.
4. **Freezing.**
   - At unit validation and at assembly, write `WITNESS.jsonl`, which contains no corpus text.
   - Record the sha256 of each raw transcript.
   - Commit both to the repo. Archive the raw transcripts outside the repo.
   - The verifier re-derives `WITNESS.jsonl` from the archive and checks it against the committed hashes.
5. **Named residual boundary.** The harness, the orchestrator and the OS account are trusted. Post-hoc edits are detectable relative to the committed digests, not before them.

**B (hash chain)** adds line-order tamper evidence inside a log, but under D the logs are no longer authoritative, so B is a SHOULD at most. **C** is dominated: the sandbox it needs would already give most of its value, and it adds key management without defending against the orchestrator. External timestamping or signing (anchoring the committed digests outside the account) is **POST-S5**.

**Open verification item (readiness, not assumed):**
- whether the harness transcript of an S5 agent reliably contains the reader's full stdout for byte-mode pages (or the persisted tool-result files that `tool_results_dir` resolves);
- whether stderr (`REFUSED: …`) is captured.

This must be measured on the synthetic fixture **before** D is adopted. If it fails, the fallback is the reader writing the witness line to a location outside the agent's write reach. That fallback is a sandbox question for the human.

---

## 4. The M3 semantic question (Q4)

**What the governing text says** (rev3 contract; quotations abbreviated):

- **§14.3** lists `timeline_summary` under the heading **"Summary fields"**: `first_*` (= v3.5's five birth kinds) · `later_support[]` · `later_refinement[]` · `contradicted_by[]` · `rejected_by[]` · `current_lifecycle`. It calls the timeline classes **descriptive**, and says a change's *meaning* is a layer-B/C research record.
- **§14.5, the Track-B rule:** "contradiction: `contradicted_by[]`, feeding D4 only together with the Track-A statement it contradicts". D4 requires "a CONTRADICTION-type row on the label, or a SOURCE-CLAIMED-CONTRADICTION lineage claim", and its quotes go to `semantic_evidence.d4[{source_id, quote}]`.
- **§14.3, `superseded_by_sources[]`:** "the sources corroborating a SUPERSEDED lifecycle, each with its dated position or UNDATED … For these sources the **file-level test of A.10 governs**, because they are **sources rather than timeline points**."
- **A.10:** `end(p)` = the minimum over dated RETRACTS points **and dated sources in `superseded_by_sources[]`**. **If any is undated, the record is UNORDERED.**

**Analysis:**

| Field | Semantic role | Classification |
|---|---|---|
| `first_*` | restates `births` | **DERIVED** projection. No evidence of its own, but it must equal `births` |
| `later_support[]`, `later_refinement[]` | summaries of later timeline points (§14.5 allows Track-B sources here) | **DERIVED** from timeline classes, **but the class → list mapping is not written anywhere** |
| `contradicted_by[]` | a **binary relation**, contradicts(source x, Track-A statement y, object), with downstream force (D4 → CONTESTED). The flat list of S-ids loses the second argument y | **(b) typed relation**, whose support must be a CONTRADICTS timeline point and/or a `semantic_evidence.d4` entry. It is not a free-standing list |
| `rejected_by[]` | presumably mirrors RETRACTS | DERIVED (the mapping is unwritten) |
| `superseded_by_sources[]` | **evidence for the lifecycle claim** `current_lifecycle = SUPERSEDED`, and an **input to a mechanical temporal computation** (`end(p)`) | **(c) provenance assertion** for a lifecycle claim: an EVIDENTIARY(LIFECYCLE) citation. The contract explicitly says these are sources, not timeline points, so it cannot be derived from the timeline |

**Engineering conclusion:**
- Neither field may remain a free list.
- `contradicted_by` (and its siblings) are *either* projections of verified timeline points *or* typed relations that need their own support.
- `superseded_by_sources` needs its own typed evidence (a new fact-kind **value**, LIFECYCLE, in the existing fact-kind dimension; this is not a new dimension, per the model-integrity check).

**Whether a whole-file read is required for LIFECYCLE** follows from default-deny: it is not in the exempt set. It is still worth ruling on explicitly, because the field has temporal force: an undated source makes the record UNORDERED.

**This is partly a governance and theory question.** The contract never states:
- the mapping from timeline classes to summary lists;
- whether a summary entry must correspond to a timeline point (projection) or may rest on non-timeline evidence (relation).

Deciding this silently in code would turn an implementation choice into protocol semantics. It is **flagged for human decision (HD-1)**, with the recommendation above.

---

## 5. Statistical track (Q5), separate from integrity

**Coherence of the frozen text:**
- Sound: the population, the sampling unit, the estimand D_i, SRSWOR, π_h, HT, the SRSWOR variance, the one-sided bounds and TIER-X as a domain. The auditor confirmed that E[HT] and E[V̂] are correct by exact enumeration (ST9).
- **One gap of completeness (not an inconsistency):** the text says a stratum with n_h ≤ 1 is "unestimable" and an n = 0 stratum is "no information", but it never says **what the combined estimate and CI then are**.
- **One missing binding:** the frame (1,975 labels) is named but not hash-frozen.

| Defect | Invalid input domain | Intended domain | Correct mathematical behaviour | Reject / NOT-ESTIMABLE / bounded | Estimator or validation? |
|---|---|---|---|---|---|
| **ST3** extra stratum name (`TIER-X`), which hides a missing stratum | strata keys ⊄ {HUB, EMPTY, MULTIROW, DECOMPOSED, SINGLE}, or ⋃ strata ≠ U | strata = a partition of the frozen frame U (hash-bound) by the derived precedence rule | an estimate over a proper subset of U is not an estimate of τ(U) | **reject** | validation (frame completeness) |
| **ST4** n_h = 0 with N_h > 0 | some π_h = 0 | π_i > 0 for all i ∈ U (HT's condition for unbiasedness) | τ is **not identified**. Only a domain estimate over U \ U_h (labelled as such) or the partial-identification bounds [τ̂_{−h}, τ̂_{−h} + N_h] are valid; the CI is never (0, 0) | **NOT-ESTIMABLE** for τ(U); a bounded estimate optional (HD-6) | **estimator output**: the combined CI treats an absent stratum as observed zeros |
| **ST5** n_h = 1 < N_h | n_h < 2 in a non-census stratum | n_h ≥ 2, or a census | the HT point estimate stays unbiased (π_h > 0), but s_h² has denominator n_h − 1 = 0, so V̂ is undefined | **NOT-ESTIMABLE** variance, hence no combined CI. (A conservative bound using S_h² ≤ N_h / (4(N_h − 1)) for a binary variable is valid but is a methodology extension: HD-6) | estimator (variance branch) |
| **ST6** vacuous frozen-sample and ML guard | the caller supplies both hashes, or omits them | the sample passed to the estimator is **byte-identical** to the sample frozen before any S5 output, whose hash is anchored in a governance act | compute the sha256 of the canonical serialization of the *actual* sample argument and compare it with the frozen record's hash; the frozen record's own hash must match the anchored value | **reject** | validation (integrity). **This is the only code guard of the ML boundary** |
| **ST7** overlapping strata in `ht_with_variance` | a label in more than one stratum sample, or a sample ⊄ its stratum frame | the sample is a disjoint union, and each s_h ⊆ U_h | with overlap, π_i ≠ π_h and units are double-counted: HT is biased | **reject** | validation (inside the estimator's entry) |
| ST10 declared `multirow` | a declared attribute | derived from the plan (the row sources' unit count) | stratum membership must be a function of the derived plan | reject a mismatch | validation |
| ST11 frozen record incomplete | it lacks strata membership, rates and a frame hash | a record sufficient to recompute every π_h | extend the record: without N_h and n_h frozen, π is not provably known in advance | — | validation / reproducibility (**a prerequisite of ST6**) |
| ST12 rate > 1 silently capped | rate ∉ [0, 1] | [0, 1] | — | reject | validation |

**Only ST4 and ST5 touch estimator outputs.** They are the rules for when an estimate or a CI may be returned at all. **No defect requires changing the estimator's formula**, and the methodology needs no redesign.

---

## 6. Minimal repair boundary (Q6)

Three classes, each with one invariant, one implementation boundary and one set of tests.

| Class | One invariant | One implementation boundary | Tests |
|---|---|---|---|
| **RC-1** universe mismatch | **Reachable ⊆ Typed; residue ⇒ FAIL; duplicates ⇒ FAIL.** For claims, this is P\* with the typing registry τ. For runs, it is E\*-3 (namespace totality) plus the reader's default-deny for batches with an activated plan | (a) one typing registry and one total citation scan in the rules module, which replaces `enumerate_claims`' location list; (b) the reader refuses non-R6 grammars for activated batches and reads the plan from the verifier's path, checking the manifest hash (m8); (c) the verifier covers all B-prefixed directories plus the legacy digest; (d) the revision is bound to the contract hash (TB5) | M1, M3 (all summary lists), m1, m2, m3, m4, M2 (legacy, R7, TB5) |
| **RC-2** unwitnessed authority | **Every execution fact the verifier uses is derived from an independent witness; every agent or orchestrator artifact is reconciled against it** (E\*-1, -2, -4; R\*; F\*; T\* from witness times) | a witness extractor (reusing `parse_transcript`) → `WITNESS.jsonl`; verifier reconciliation; runbook steps 7 and 13 freeze and commit the digests; facts bound to witnessed page spans | TB1–TB3, P9b, E7 (time forged), refused-line deletion, m7 |
| **RC-3** estimator domain | **An estimate is produced only from the frozen frame, a derived partition and a hash-verified frozen sample; otherwise reject or NOT-ESTIMABLE, never a number** | the statistics functions and the frozen-record format only | ST3–ST7, ST10–ST12 |

### Classification

| Tier | Items |
|---|---|
| **MUST-FIX BEFORE S5** | RC-1 in full (M1, M2, M3 *after HD-1*, m1–m4, TB5, c1) · RC-2 D-minimal (the witness for reads, agent–run binding, derived orchestration times, digest freeze), **subject to HD-2 and to the §3.4 readiness measurement** · F\* fact-to-page binding (m7, *subject to HD-5*) · RC-3 ST3–ST7 plus ST11 (the prerequisite of ST6) |
| **SHOULD-FIX** (same slice if cheap) | replace `RANGE6` and the pointer regex with the **canonical** matcher plus Unicode/whitespace normalization (m5, m6; ES-005.4 "never a copy") · ST10, ST12 · TB6 (string revision) · a B-style hash chain in the reader logs · the object-quote check folded into the R6 path, so runbook step 14 no longer depends on a separate checker (in effect already MUST through RC-1 m2) |
| **HUMAN GOVERNANCE QUESTION** | HD-1 … HD-9 below |
| **POST-S5** | external timestamping or signing of the committed digests · ML review queues (design only; outside the evidence path) · a conservative-variance extension, if HD-6 declines it now |

**Scope discipline:**
- This is **one slice with three boundaries**.
- It changes no reconstruction semantics except where HD-1 rules.
- It changes no statistical formula.
- The only architectural change is the verification **trust root** (RC-2), which needs a recorded decision first (Business → DDD → **Architecture decision** → RED → GREEN).

---

## 7. Adversarial test design (Q7)

**Rules:**
- Every attack runs through the **production path** `p3b_s5_verify.verify(batch, root=<tmp>)`, except the RC-3 attacks, which go through the estimate entry point the activation act will call.
- Every attack uses a **fresh synthetic fixture**, a fake resolver and synthetic transcripts.
- Each attack is **one mutation** of the positive control.
- Unless noted, Expected = **FAIL**.

| ID | Class | Attack | Expected | Why it must fail (invariant) |
|---|---|---|---|---|
| **PC-0** | — | valid batch: SINGLE, DECOMPOSED (≥ 2 units) and EMPTY labels; the witness equals the logs; every citation typed and supported; statistics on a complete frame with a frozen sample | **PASS** | the positive control: all invariants satisfied |
| A-01 | RC-1 | timeline point with `source_id: null` (FIRST) | FAIL | P\*: an EVIDENTIARY element needs a non-null source |
| A-02 | RC-1 | timeline point with the `source_id` key absent | FAIL | P\*: schema-required source |
| A-03 | RC-1 | a string (non-object) element in `timeline` | FAIL | P\*: an untyped element |
| A-04 | RC-1 | `timeline_summary.contradicted_by = [s]`, where s is NOT-CONSUMED and has no CONTRADICTS point | FAIL | P\*: DERIVED with no projection source (or a relation with no support) |
| A-05 | RC-1 | `superseded_by_sources = [{s, position}]` with no LIFECYCLE fact | FAIL | P\*: EVIDENTIARY(LIFECYCLE) with no fact |
| A-06 | RC-1 | `semantic_evidence.d4 = [{s, quote}]` with a fabricated quote | FAIL | P\*: a quote not in bytes |
| A-07 | RC-1 | an S-id inside a **new, unknown** key `x_notes: "S1234 shows…"` | FAIL | P\*: an unknown path is untyped, so it fails (**the key test of totality**) |
| A-08 | RC-1 | `timeline_summary.first_formal` ≠ `births.formal` | FAIL | DERIVED consistency |
| A-09 | RC-1 | an EMPTY label carrying `contradicted_by` naming another label's file | FAIL | P\* applies to EMPTY; the source ∉ Files(label) |
| A-10 | RC-1 | duplicate pair check (YES then NO for one pair) | FAIL | uniqueness |
| A-11 | RC-1 | legacy run `B-R2` log with a read by the synthesis agent after dispatch | FAIL | E\*-1 / E\*-3 plus the legacy digest |
| A-12 | RC-1 | legacy `B-R2S` and `B-R2.1` variants | FAIL | same |
| A-13 | RC-1 | undeclared `B-R6-L03U09` (not planned) | FAIL | E\*-3 |
| A-14 | RC-1 | a `B-R7-…` directory with reads | FAIL | E\*-3 (namespace totality) |
| A-15 | RC-1 | manifest header revision 7 with the revision-6 contract hash | FAIL | the revision is bound to the contract hash |
| A-16 | RC-2 | **writable log mutation:** a hand-written READ-LOG entry for a page never requested (the page hash is correct, since anyone with the bytes can compute it) | FAIL | E\*-4: a log entry with no witnessed call |
| A-17 | RC-2 | a refused line deleted from READ-LOG; the witness shows `REFUSED` | FAIL | E\*-1 (refused ∈ W) and E\*-4 |
| A-18 | RC-2 | the synthesis agent reads under a unit run id, and the log `utc` is back-dated before units-validated | FAIL | E\*-1: the witness ties the call to the synthesis agent; T\* uses harness time |
| A-19 | RC-2 | **writable provenance mutation:** R6-PROVENANCE rewritten so that synthesis-dispatched is later than the real Agent call | FAIL | T\*: the stages are derived from the orchestrator transcript, and the declared stages must equal the derived ones |
| A-20 | RC-2 | duplicate `units-validated` stage (the later forged one would win) | FAIL | uniqueness; derived-stage equality |
| A-21 | RC-2 | **writable claim-evidence mutation:** claim-evidence repointed to a fact whose quote is in the bytes but on a page the run never read | FAIL | F\*: the fact's quote lies outside the witnessed spans |
| A-22 | RC-2 | records rewritten after validation, with RUN-MANIFEST hashes recomputed to match | FAIL | the record hashes printed at validation are in the witness (the orchestrator transcript) and differ from the current files |
| A-23 | RC-2 | the witness transcript truncated or a line corrupted | FAIL | `parse_transcript` records parse errors; a non-empty error set ⇒ FAIL (never skipped) |
| A-24 | RC-2 | the raw transcript's sha differs from the committed `WITNESS` digest | FAIL | frozen-digest binding |
| A-25 | RC-2 | a read by a B-agent under batch id B′ (E15) | FAIL | E\*-1: batch(e) ≠ B for a B-agent |
| A-26 | RC-3 | an **invalid statistical stratum**: 6 labels under `TIER-X` | FAIL (reject) | the frame partition check |
| A-27 | RC-3 | **n = 0** in SINGLE (N = 12) | NOT-ESTIMABLE (never CI (0, 0)) | π_h = 0: τ is not identified |
| A-28 | RC-3 | **n = 1** in SINGLE | NOT-ESTIMABLE variance and CI; the point estimate is flagged | n_h − 1 = 0 |
| A-29 | RC-3 | **overlapping strata** passed to the estimator | FAIL (reject) | not a partition; biased HT |
| A-30 | RC-3 | **forged frozen-sample metadata:** the caller passes the matching frozen hash while the sample argument contains an injected label | FAIL (reject) | the guard hashes the actual sample |
| A-31 | RC-3 | frozen record whose own hash ≠ the governance-anchored hash | FAIL | anchor binding |
| A-32 | RC-3 | declared `multirow` on a SINGLE label | FAIL | membership derived from the plan |
| A-33 | combo | A-01 + A-11 + A-19 | FAIL (each detected independently) | no masking between invariants |

**Honest limits of the matrix** (these stay boundaries and must not be tested as if they were closed):
- a correct quote attached to an unrelated claim (entailment);
- a blanket NOT-DETERMINABLE (F2);
- a YES on a colliding substring;
- the orchestrator forging the witness before the digests are committed.

---

## 8. Epistemic boundary (Q8)

The repair keeps SOURCE OBSERVATION → RECONSTRUCTION → THEORY strictly separate:
- **RC-1** types *where* a claim is. It never judges whether the claim is true.
- **RC-2** makes *observation* witnessed.
- **RC-3** only validates domains.

No mechanism above uses ML, similarity, clustering, embedding or semantic inference. Entailment stays a **declared human-review boundary**. The witness extractor is deterministic parsing of harness records. **ML stays outside the evidence and sampling paths.** The only ML-related code guard (ST6) is made real, not extended.

---

## 9. Human decisions required (C)

| ID | Decision | Recommendation |
|---|---|---|
| **HD-1** | M3 semantics: are `later_support`, `later_refinement`, `contradicted_by` and `rejected_by` **derived projections** of timeline classes (which mapping?) or **typed relations** with their own support? Is `superseded_by_sources` LIFECYCLE evidence, and does it require a whole-file read? | projections, with the mapping to be written into the contract; `contradicted_by` must also match a `semantic_evidence.d4` entry when it feeds D4; `superseded_by_sources` = EVIDENTIARY(LIFECYCLE), whole-file (default-deny, temporal force) |
| **HD-2** | adopt **Model D** (the harness transcript as witness) as the authoritative execution record; archive raw transcripts outside the repo; accept the named residual (the harness, the orchestrator and the account are trusted) | adopt, after the §3.4 readiness measurement |
| **HD-3** | classify the trust boundary as MUST-FIX BEFORE S5 | **yes** (see E) |
| **HD-4** | the legacy namespace: the reader refuses `-R2*` for batches with an activated plan; freeze legacy directory digests at activation. Must any legitimate R2 re-run remain possible? | refuse; a re-run needs a new plan and a human act |
| **HD-5** | m7: must every fact quote lie on a page the owning run was witnessed reading? The auditor called this a governance question | **yes:** otherwise "observed" is not established for that fact |
| **HD-6** | outputs for n_h = 0 and n_h = 1: pure NOT-ESTIMABLE (the spec as written), or also a bounded estimate (partial identification or a conservative variance)? | NOT-ESTIMABLE now; bounds as a later, separately reviewed extension |
| **HD-7** | the auditor's M3 severity (MATERIAL) | confirm MATERIAL: D4 and `end(p)` give these fields downstream force |
| **HD-8** | versioning: amend the unactivated revision 6, or issue **revision 7** so that the audited object `bc6fb01bb` stays an immutable reference | revision 7 |
| **HD-9** | after repair: whether a further narrow independent audit is required (never automatic) | yes, narrow, on RC-1, RC-2 and RC-3 only |

---

## 10. Is the trust boundary MUST-FIX BEFORE S5? (E)

**Yes. Recommend MUST-FIX BEFORE S5, in the D-minimal form.**

- **The epistemic goal.** A PASS is meant to certify that the batch was *faithfully observed*. Without a witness, PASS certifies only that the agent's and orchestrator's files agree with each other and with the bytes and plan. R5-02, R5-03 and R5-04 are then closed only on the condition that nobody edited a log, and S5 would carry that condition into 396 batches and 1,975 labels (R19) with no way to discharge it afterwards.
- **The real threat.** The documented failure mode is an LLM agent deviating (E0, OB0018). A harness-recorded witness catches exactly that, and the logs cannot.
- **The cost.** The transcript parser and scan already exist. D-minimal adds extraction, reconciliation and two freeze steps. It does not change the reconstruction architecture, the Master Protocol or R19.
- **The order.** RC-1 and RC-3 can be specified at once. **RC-2 must not be coded before HD-2** and before the §3.4 measurement shows that transcripts carry the reader's page headers and refusals. If that measurement fails, stop and bring back the sandbox alternative for decision; do not improvise.

**Stop.** Nothing was implemented, changed or committed. Control returns to the human.
