# R7 design repair: implementation record (G-LOG-0086)

| | |
|---|---|
| **Kind** | Engineering evidence for a **design-only** repair slice. ⚠ authority: generated. Engineering supplies evidence and never accepts its own work (EP-02) |
| **Authority** | G-LOG-0086: PF-1…PF-8 accepted as design-repair input; ONE bounded design-repair slice; FD-9 not added |
| **Input** | `audit-p3b/20260926_R7-PREFREEZE-DESIGN-REVIEW.md` (NEEDS-PREFREEZE-REPAIR; 8 MATERIAL + 6 non-material + 1 human question) |
| **End state** | **R7 DESIGN-REPAIRED · NOT IMPLEMENTED · NOT ACTIVATED · AWAITING HUMAN DESIGN FREEZE** |
| **Independence limit** | the design, the review and this repair were all authored by the same agent/session. The repair is **not** independently validated; ONE independent R7 audit is required after implementation (HD-9) |

## 1. Scope performed

| Artifact | Change |
|---|---|
| R7 addendum candidate (`prompts/20260926_2400_…-r7-addendum.md`) | rewritten as **candidate v2 (design-repaired)**; v1 is preserved in git (`c453b1b2b`, sha `4f31d393…4c704`) |
| ADR-R7-01 | W1 table, W7a, W8, PF-9/11/12 paragraphs, FD references, traceability |
| R7 plan (`docs/plans/20260926-1548-s-series-revision-7-plan.md`) | one module per bounded context (B0–B9), status, risks |
| Test matrix | T01–T38 kept (tags aligned to contexts); **T39–T76 added** |
| Governance / TODO / session log | G-LOG-0086; TODO state; session log |

**Not performed:**
- R7 runtime code;
- activation;
- manifest regeneration;
- agent dispatch;
- corpus reads;
- binary pre-classification;
- S5;
- H-19 access;
- any audit.

**Not modified:**
- R6 (code, tests, addendum, record, audit);
- F-Series;
- application code;
- the production ledger;
- P3B-STATE;
- the manifest;
- Master Protocol v3.5.

## 2. Findings addressed → design decisions

| PF | Violated invariant (review) | Design decision (addendum) | Where |
|---|---|---|---|
| **PF-1** | an adjacent-pair class ≠ an object-level summary | **DR-01:** summary relations are object-level; membership + inheritance + **one-directional** class constraints (CONTRADICTS ⇒ ∈ `contradicted_by` with target = predecessor; RETRACTS ⇒ ∈ `rejected_by`); **no equality**. **DR-01b:** `later_*` requires ESTABLISHED precedence. The PROJECTION vs INTERPRETATION table is explicit; **EXTENDS has no default placement** (FD-1′b, a human semantic decision) | §4.1, §12 |
| **PF-2** | R1 / A.10: no precedence from array order | **DR-02:** contradiction relation `{source_id, contradicts}` ≠ temporal qualifier; Precedence = ESTABLISHED iff both points are A.10-dated and the dates are strictly ordered, otherwise NOT-ESTABLISHED; never `source_id`, list, array or UNORDERED-BLOCK order. A NOT-ESTABLISHED contradiction may feed D4, never `later_*`, "earlier" or `end(p)`. **DR-02b:** no STEP-NUMBER precedence (rev3 §14.4c: within-series ordering only, excluded from temporal scope) | §4.2 |
| **PF-3** | absence needs a schema | **DR-03:** DISCOVERY (schema-independent; presence only) ≠ VALIDATION (the closed schema S: required, non-null, forbidden/unknown, types, closed enums, cardinality, uniqueness). Invariant: Reach ⊆ dom(Registry) ∧ Valid_S ∧ Unique_K. A missing key is a validation failure (the M1 path) | §3.1 |
| **PF-4** | registry sovereignty | **DR-04:** F1 Type(p, v) = Registry(p, disc_p(v)) with closed enums (an unknown discriminator is FAIL); F2 consumer read sets ⊆ EVIDENTIARY ∪ DERIVED, as a machine-checked table; the prose rule of force is withdrawn | §3.2–3.4 |
| **PF-5** | closed world over agent tool calls | **DR-05:** W8 execution-capability closure: {canonical reader · permitted Write to a run-owned path · handback}; everything else FAILs; the SEAL stop is carried forward from the R6 exposure logic; the orchestrator pre-creates run directories | §5.5 |
| **PF-6** | W1: fail closed | **DR-06:** the completed-dispatch definition and decision table (11 cases); zero-tool, missing-transcript and missing-count are distinct signatures; **no agent resumption in S5** (never "first" or "last" count) | §5.2 |
| **PF-7** | W_unit ⊆ W_final | **DR-07:** W7a: byte-identical inclusion, unchanged unit transcript digests, only later events added, the same decoder/extractor or a joint re-derivation | §5.7 |
| **PF-8** | byte-exact anchoring | **DR-08:** ∃ i : bytes[i : i+\|q\|] = q ∧ [i, i+\|q\|) ⊆ Wr(S), a union merged only across byte-contiguous pages witnessed by the owning run; multiple occurrences allowed (at least one must qualify); only the N-WS normalization, with a deterministic offset map; other normalizations are not evidence; offsets computed by the verifier | §6 |
| PF-9 | coupling to an experimental instrument | **DR-09:** a neutral transcript syntax decoder; no V1.2.4 dependency; one decoder per transcript | §5.6; plan B0 |
| PF-10 | serial coupled to agent-reachable state | **DR-10:** reader-generated `inv` (sha of run‖S‖page‖start-ns‖pid); a convenience join key only | §5.6, §9 |
| PF-11 | preservation ≠ trust | **DR-11:** the archive is untrusted storage verified by the committed digests | §1 |
| PF-12 | truth strata | **DR-12:** the strata table; "EXECUTION TRUTH ≠ SEMANTIC TRUTH" | §1 |
| PF-13 | god-object risk | **DR-13:** bounded contexts Universe, Witness, Evidence, Reconstruction, Statistics; the verifier composes predicates only; an acyclic dependency graph | §0; plan B0–B6 |
| PF-14 | estimator outcome classes | **DR-14:** REJECT (invalid or tampered) vs NOT-ESTIMABLE (valid but undefined); N_h = 0 is an empty stratum, not a trigger; π_h from the realized n_h | §8 |
| PF-15 / FD-9 | minimum quote length | **not adopted** (G-LOG-0086); recorded in §6 and §12 | — |

**One deviation from the human's module list, stated for the freeze:**
- The instruction named `r7_universe`, `r7_witness`, `r7_evidence`, `r7_stats` and `r7_verify`.
- The repaired design adds **`r7_reconstruction`**, because the instruction's own acceptance criterion includes **ReconstructionValid**. Placing S1–S3, the summary relations and precedence in Evidence would mix "what bytes were observed" with "what follows".
- The neutral decoder (B0) is a sixth, S5-agnostic module (PF-9).

## 3. Logical / mathematical properties of the repaired design

- **Precedence is a strict partial order.** Precedence(T, S) is the restriction of the strict total order on calendar days to A.10-dated points. It is therefore irreflexive, asymmetric and transitive. Undated points and same-day pairs are incomparable (NOT-ESTABLISHED). No array order enters the relation.
- **The one-directional constraint is sound under PF-1's counterexample.** CONTRADICTS(p) ⇒ (p, pred(p)) ∈ `contradicted_by` follows from the definition of the adjacent class ("relative to the previous point"). The converse is not required, so S4 (RESTATES S3; contradicting S1) is admissible with its own CONTRADICTION fact (positive control T72).
- **The closed-world invariant is correctly split.** Discovery is monotone in the object (adding content can only add discovered locations). Validation is not (removing a key can create a failure). So they cannot be one operation. The split makes M1 a validation failure (T01, T02, T39).
- **Registry totality.** Every multi-row path is value-discriminated, with mutually exclusive discriminators. The timeline rows partition rev3's 10 change classes, and the whole-file-exempt set equals R6's {FIRST, RESTATES, EXTENDS, NOT-COMPARABLE} (checks R5–R9).
- **Composition.** Batch PASS = U ∧ W ∧ E ∧ C. The verifier contributes no predicate. The context dependency graph is acyclic, and Universe has no dependency (check D1).
- **Statistics.** Unchanged formulas. The domain D is total, and the result is Estimate on D, REJECT on invalid input, and NOT-ESTIMABLE on valid input with an undefined estimand or variance.

## 4. Design and contract static tests (run; no runtime code)

**The checker:** `r7_design_check.py`, in the session scratchpad; sha256 `05ccbd6f…6506c`. It parses the addendum's machine-readable tables (`REGISTRY`, `CONSUMERS`, `TESTS`) and text.

**Result: 32 / 32 checks PASS** (output sha256 `12aae046…0cb2`). The checks:

| Group | Checks |
|---|---|
| Registry | R1 unique (path, discriminator); R2 closed type set; R3 EVIDENTIARY rows name a kind; R4 every QUOTE belongs to an EVIDENTIARY element; R5 multi-row paths are value-discriminated; R6 two-row discriminators are mutually exclusive; R7 the absences discriminator partitions; R8 the timeline rows partition the 10 rev3 classes; R9 the whole-exempt set equals R6's |
| Coverage | C1 all 18 mandated locations typed; C2 source_id required and non-null, stated as validation |
| Consumers | F2 every consumer read set ⊆ EVIDENTIARY ∪ DERIVED |
| Tests | T1 unique, contiguous ids; T2 ≥ 1 negative control per invariant group (23 groups); T3 positive boundary controls for anchoring, precedence, summary and statistics; T4 a global positive control; T5 all 33 human-mandated repair tests mapped |
| Statements | S1 withdrawn statements absent ("otherwise by the object's timeline order", "schema-independent recursive scan", "the READ-LOG line this call appends", the rule-of-force sentence); S2 no V1.2.4 dependency; S3 array order never precedence; S4 the PASS composition and the verifier as composer; S5–S10 truth strata, preservation, no minimum quote length, no resumption, REJECT vs NOT-ESTIMABLE, realized n_h and N_h = 0; S11 DR-01…DR-14; S12 PF-1…PF-14 referenced; S13 FD-9 not adopted |
| Dependencies | D0 dependency lines present; D1 acyclic, Universe independent |

**The checker found one genuine gap on its first run:** the summary-membership rule had a positive control (T72) but no direct negative control. **T75** (summary entry not a timeline point) and **T76** (a `rejected_by` inheritance violation) were added. The rerun gave 32/32.

**Test matrix:**
- 76 cases (T01–T38 original; T39–T71 the 33 human-mandated cases; T72–T76 PF-1 controls).
- Plus the re-audit's MATERIAL reproductions in R7 form.
- **Mapping of the 33 mandated cases:** 1→T39, 2→T40, 3→T41, 4→T42, 5→T43, 6→T44, 7→T45, 8→T46, 9→T47, 10→T48, 11→T49, 12→T50, 13→T51, 14→T52, 15→T53, 16→T54, 17→T55, 18→T56, 19→T57, 20→T58, 21→T59, 22→T60, 23→T61, 24→T62, 25→T63, 26→T64, 27→T65, 28→T66, 29→T67, 30→T68, 31→T69, 32→T70, 33→T71.

## 5. Safety verification

| Check | Result |
|---|---|
| `P3B-STATE.json` | db52ac7a6fc5e653… (unchanged) |
| `_batch_manifest_p3b_r2.jsonl` | 1b383fbc6ff720eb… (unchanged; revision 3) |
| `P3B-HOLDOUT-SEAL.json` | 9b99169d30d57b2e… (unchanged); guard SEALED (HS-3d32dd44d162); grep 58 files, 0 violations |
| ledger fingerprint | 4fde15fc42513725… (unchanged); no READ-LOG change |
| protected paths | `git status` of `scripts/`, `ledger-p3b-r2/`, the R6 addendum and the R6 record: empty |
| repository-wide `git status` before vs after the slice | differs **only** in: `P3B-GOVERNANCE-LOG.md`, the ADR, the R7 addendum and the R7 plan (+ this record, TODO, session log). The pre-existing unrelated modifications (`app/…`, brainstorming files) are identical before and after |
| corpus | not read; no resolver was called; no agent was dispatched |
| Master Protocol / F-Series / application code | not modified |

## 6. Files changed (sha256 after)

| File | sha256 |
|---|---|
| `prompts/20260926_2400_p3b-agent-contract-r7-addendum.md` (v2) | `e0212573989da5b8538f9e917b36fa58de3ab92b8f7fb25bccb878a0f78e68d6` (v1: `4f31d393066c1688386daa7d0f889f0ef2efbf584cfce2e3b9421516c684c704`) |
| `audit-p3b/20260926_ADR-R7-EXECUTION-TRUST-ROOT.md` | `7a457a7da3ef439d6b111941b461e941c45ec3a4a1e2bccc7b82e24f224d38c5` (before: `a1ceed93…5a4f`) |
| `docs/plans/20260926-1548-s-series-revision-7-plan.md` | `eac6cc30823f88f7156fbcdc6704cbf0b4c503e6322038cfab682ad94dc67790` (before: `0bb824fa…adaa`) |
| `P3B-GOVERNANCE-LOG.md` (+ G-LOG-0086) | `a3026e126d4b113810b58ae38472a0f44fedc870798021396e7bef8bdde62799` |
| `audit-p3b/20260926_R7-PREFREEZE-DESIGN-REVIEW.md` | unchanged since the review (committed with this slice as the accepted input) |

## 7. New design state and next human act

**R7 DESIGN-REPAIRED · NOT IMPLEMENTED · NOT ACTIVATED · AWAITING HUMAN DESIGN FREEZE.**

**Freeze decisions** (addendum §12):

| FD | Decision |
|---|---|
| FD-1′a | the one-directional constraints |
| **FD-1′b** | the list assignment; **EXTENDS is open** |
| FD-1′c | the lateness tightening |
| FD-2′ | the contradiction relation + computed precedence; no STEP-NUMBER |
| FD-3′ | the lifecycle equivalence |
| **FD-4′** | the preservation archive; **a location must be named** |
| FD-5′ | the `inv` header |
| FD-6′ | F1 + F2 |
| FD-7′ | the W1 table; background dispatch; no resumption |
| FD-8 | W8 + SEAL; pre-created run directories |

After the freeze: implement B0–B9 (RED → GREEN per context), then engineering verification, then STOP for the human decision on ONE independent R7 audit.
