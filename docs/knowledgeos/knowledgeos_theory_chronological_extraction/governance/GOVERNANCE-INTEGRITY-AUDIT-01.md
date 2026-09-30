# GOVERNANCE-INTEGRITY-AUDIT-01 — Independent Governance Integrity & Gate Effectiveness Audit

| | |
|---|---|
| **Kind** | independent, forensic audit of the research governance **control plane** |
| **Auditor** | governance/control-plane reviewer (a Claude Code session that is not the research session). ⚠️ Same model family as the research session; the architecture proposal under review (`architecture/*`) was drafted by **this** session, so for those artifacts this is a **self-review** and is marked as such |
| **Evaluated state** | **HEAD `6cffbea5e`** (control-plane files verified unchanged at `15a05f297`; see §2A). Governance digests (sha256, first 16): `gates.yaml 7a01bac1a45ef634` · `governance-state.yaml e20b4d67ab71caf8` · `gate-schema.yaml 4ebde2c923ede3a6` · `gate-runner.py fcafa9cd27976ff2` · `governance-preflight.sh 4b53c0b30e0851a8` |
| **Method** | 54 valid mechanical cases (55 runs; one discarded as an invalid test, see Appendix A). Each ran on its **own `git archive` copy of HEAD** in the session scratchpad, executing the unmodified runner (`--json`) **and** the unmodified door (`governance-preflight.sh`). Harness and case catalogue: Appendix A |
| **Modified** | nothing but this file. No research artifact, gate, activation, fixture, runner, hook or protocol |
| **Verdict vocabulary** | PASS · PASS_WITH_OPEN_QUESTIONS · FAIL · INCONCLUSIVE (P2P §3A.6) |

---

## 1. Executive verdict

> ## **FAIL.** The implemented mechanism does **not** reliably enforce what it claims.
>
> The architecture it implements is sound: two-key activation, REVIEW/HUMAN never auto-passed, an unreadable rule book never read as permission. The **instrument, however, has demonstrated false-CLEAR paths**. The **five activated gates cannot detect the incident class they were activated after**. And **no control distinguishes a destructive overwrite from legitimate theory evolution.**

**Answer to the primary question:** **B — READY WITH CORRECTIONS.**
- Increment 1a must be **extended** by the instrument defects this audit demonstrates (§6, §9, §10), and completed, **before** Increment 1b.
- Separately, **one additional minimal rule** is justified by evidence: namespace qualification (§11, §14). It needs human approval but does **not** block 1a.

| Headline finding | Evidence |
|---|---|
| **False CLEAR by redefinition.** When every activated gate's `status` is edited to `proposed`, the runner evaluates **nothing** and the door prints *"CLEAR — every applicable ACTIVATED gate passed"* | D09 |
| **False CLEAR by re-tiering.** Changing an activated gate's `tier` to `B` turns a real FAIL into a non-blocking row. Result: CLEAR | D10 |
| **False CLEAR on missing or empty input.** KOS-G-022 PASSes with the seed missing or empty, and **the suite reports CLEAR**. KOS-G-010 and KOS-G-002 PASS on missing or empty inputs. The suite is saved only incidentally, by KOS-G-003 | B04, B09 · B01, B03, B06–B08 |
| **Historical incident undetected.** A registry that is self-consistent but canonically wrong gives CLEAR, and so does the real HEAD state | C1, A0 |
| **False FAIL against canonical discipline.** Citing the **correct** canonical ID of an unread file (F2837) **BLOCKs** | G02 |
| **RCI-015 not testable.** An in-place overwrite, a rewritten evolution history, a deleted relation row and a valid additive refinement **all** give CLEAR | E1–E4 |

---

## 2. Current governance baseline

| Item | Finding (V = verified) |
|---|---|
| HEAD | `6cffbea5e` (2026-09-23 02:13) |
| Working tree (research dir) | only `evidence/` untracked, belonging to the research session. **Governance files clean** against HEAD (V) |
| Governance history | `04376db9b` shipped inert → `4dc4ea765` review corrections → `d26f39c1d` five gates activated, runner survives an unreadable rule book → `d62c84c00` closes silent-CLEAR on an unknown ID |
| Activated | KOS-G-001, 002, 003, 010, 022 (`governance-state.yaml`). The activation record notes the research session **repaired the file's syntax** (E-5 in RCA) |
| Catalogue | 25 gates: 15 AUT (14 implemented; 047 `not_yet_defined`), 8 REVIEW, 2 HUMAN. By status: active 23 · proposed 1 · not_yet_defined 1 |
| Self-test | **30/31**. One MISMEASURED: `theory_doc_exists` on the shared `fail` fixture (§5) |
| Door wiring | `governance-preflight.sh` is wired into **no** settings file (`governance/README.md` §5C). It is invoked only where P2P §0.3 says so: the two phase boundaries |
| ⚠️ **Concurrent change to the architecture package** | this session's **uncommitted** `architecture/*` files (5) were committed by the **research session** inside `6cffbea5e` ("ID-ROOTCAUSE-01 Addendum A"). Content is identical to the drafts (clean working tree). This is again a **mixed-unit commit**, and the governed party committed governance artifacts (RCA §0A R-5; RCI-011) |

### 2A. The state moved during the audit window (recorded, not audited)

While the cases ran, the research session made two commits:

| Commit | Content (from its message; ⚠️ **not audited here**) | Consequence for this audit |
|---|---|---|
| `163a0f996` (02:20) | implemented "RCI steps 3–6" in `evidence/`: `build-manifest.py`, `CORPUS-MANIFEST.jsonl` (hash `54977c6e…`), `admit.py` (exit 0 ADMIT / 3 STOP), a receipt format, **`governance/PROPOSED-GATES-RCI.yaml` proposing KOS-G-070…073** (not added to gates.yaml, not activated). It reports **B-14**: 18 of 3,081 canonical-list paths (0.6%) do not resolve (whitespace/truncation); 0 duplicate IDs | the **Increment 1b building blocks now exist, produced by the governed party**. Status is self-marked *"RESEARCH-PRODUCED PROPOSAL / IMPLEMENTATION EVIDENCE — NOT AUTHORITATIVE GOVERNANCE"* |
| `15a05f297` (02:24) | "stop RCI work": records **B-15**, that the research session implemented work the plan assigned to governance, *"recorded as a fact, not offered as justification"*. **It also committed an earlier draft of this audit file** | a **third** instance of the research session committing governance-side drafts (GIA-8) |

**Verified unchanged between `6cffbea5e` and `15a05f297`** (`git diff --stat`, empty): `gate-runner.py`, `gates.yaml`, `governance-state.yaml`, `.claude/hooks/`. **Every control-plane result in §§3–13 therefore holds at the new HEAD.** `evidence/*` and `PROPOSED-GATES-RCI.yaml` were **not** examined, and no statement here should be read as a verdict on them.

**New namespace collision, found in passing:** `PROPOSED-GATES-RCI.yaml` uses **KOS-G-070…073**. The same IDs carry different meanings in this session's catalog (070 Candidate Theory complete · 071 relations accounted · 072 coverage stated · 073 surprise report). This strengthens NR-1 (§14).

---

## 3. Rule → control → gate matrix

The eight properties asked for are shown as columns.
- **MR** = machine-readable (`architecture/control-invariants.yaml`, PROPOSED).
- **Impl** = implemented by any check.
- **Tested** = exercised in this audit.
- **Blocks** = whether a violation, today, blocks the workflow.

| Invariant | Source rule | MR | Impl | Gate | Activated | Tested (case) | Viol. fixture | Clean fixture | Blocks | Status |
|---|---|---|---|---|---|---|---|---|---|---|
| RCI-001 canonical identity | P1P §4 | ✅ | ⛔ (registry `file_id` uniqueness unchecked) | — | — | B14: duplicate `file_id`, different path ⇒ **CLEAR** | ⛔ | ⛔ | ⛔ | **FAIL** |
| RCI-002 no research ID assignment | P1P §4, §5 | ✅ | ⛔ | — | — | C1 swap ⇒ **CLEAR**; A0 real incident ⇒ **CLEAR** | ⛔ | ⛔ | ⛔ | **FAIL** |
| RCI-003 corpus read-only | RA-1 | ✅ | ⛔ (no manifest) | — | — | not testable (no hash baseline) | ⛔ | ⛔ | ⛔ | **INCONCLUSIVE** |
| RCI-004 identity before admission | P1P §45 G3 | ✅ | ⛔ | — | — | — | ⛔ | ⛔ | ⛔ | **FAIL** (absent) |
| RCI-005 read receipt | P1P §9A | ✅ | ⛔ | — | — | — | ⛔ | ⛔ | ⛔ | **FAIL** (absent) |
| RCI-006 position/coverage in canonical IDs | Q58, RA-11/12 | ✅ | ⛔ | — | — | — | ⛔ | ⛔ | ⛔ | **FAIL** (absent) |
| RCI-007 passage traceability | Q15, §5A.7 | ✅ | partial: C3 seed→appendix | KOS-G-022 (+023, 020) | 022 only | B04/B09 missing seed ⇒ **PASS**; B21 malformed seed ID ⇒ **PASS**; B22 untraced ⇒ FAIL ✅ | ✅ (shared) | ✅ | 022 yes | **FAIL** (false PASS paths) |
| RCI-008 no silent exclusion | Q45/46/54 | ✅ | partial: kind-reason text | KOS-G-012 (AUT), 042 (REVIEW) | no | not in activated set | ✅ (012) | ✅ | ⛔ | **INCONCLUSIVE** |
| RCI-009 epistemic preservation | Q3, Q50 | ✅ | ⛔ (REVIEW only) | KOS-G-045 (REVIEW) | no | F1/F2 ⇒ **CLEAR** | n/a | n/a | ⛔ | **INCONCLUSIVE** (correctly a REVIEW/AUDIT question) |
| RCI-010 revision/withdrawal preserved | P1P §5A | ✅ | ⛔ | — | — | — | ⛔ | ⛔ | ⛔ | **FAIL** (absent) |
| RCI-011 governance separation | README §5, P2P §0.3 | ✅ | tamper-**evident** digests only | KOS-G-061 (HUMAN) | no | D09–D13: every redefinition and activation edit is **accepted**; only digests change | n/a | n/a | ⛔ | **FAIL** |
| RCI-012 acceptance separation | KOS-G-060 | ✅ | HUMAN | KOS-G-060 | no | D15: activating it ⇒ **permanent BLOCK** (no completion record exists) | n/a | n/a | yes, permanently | **INCONCLUSIVE** |
| RCI-013 machine proposal ≠ authority | I-14 | ✅ | ⛔ | KOS-G-044 (REVIEW, origin labels, **not** `asserted_by`) | no | — (`asserted_by` occurs 0 times) | ⛔ | ⛔ | ⛔ | **FAIL** |
| RCI-014 instrument validity | assessment §I | ✅ | partial: fixtures + `--self-test` | runner | n/a | A0: self-test 30/31 **and** evaluation CLEAR; D06: fixtures deleted ⇒ evaluation unchanged | ✅ | ✅ | ⛔ (not linked) | **FAIL** |
| RCI-015 non-destructive evolution | P1P §5A, P2P I-11/12, Q24 | ✅ | ⛔ | KOS-G-061 (HUMAN, frozen docs only) | no | E1/E3/E4 violations ⇒ **CLEAR**; E2 valid ⇒ CLEAR (**indistinguishable**) | ⛔ | ⛔ | ⛔ | **FAIL** |

**Reading.**
- Every invariant is **machine-readable**, which is the only property all 15 share.
- **None** is enforced at its correspondence level by an activated gate.
- Machine-readability here is a list, not a control.

---

## 4. Active-gate effectiveness matrix

| Property | KOS-G-001 artifacts-parse | KOS-G-002 no-duplicate-ids | KOS-G-003 no-dangling-refs | KOS-G-010 index-coverage | KOS-G-022 theory-doc-completeness |
|---|---|---|---|---|---|
| **Claims** (`gates.yaml`) | "every .jsonl artifact parses" | "theory-object ids and relation rows are each unique" | "every T-/G-/F- identifier cited **in any artifact** exists" | "every file_id in the registry appears in the index" | "{seed} − {traced in appendix} = ∅" |
| **Implementation** | `c_jsonl_wellformed` (l.85) over `_jsonl_paths` (l.59: **root + phase2_extraction top level only**) | `c_no_duplicate_ids` (l.95) | `c_no_dangling_refs` (l.108): **.jsonl files only**; known = objects ∪ gaps ∪ registry | `c_index_coverage` (l.126): registry − index | `c_seed_trace_complete` (l.186) via `_seed_and_traced` |
| Valid (A0) | PASS | PASS | PASS | PASS | PASS |
| Empty input | n/a | — | FAIL (incidental: refs dangle) | **PASS 0/0** (B07/B08) | **PASS** (B09) ⇒ **suite CLEAR** |
| Missing input | **PASS** (a deleted file is not "malformed") | **PASS** (B06) | FAIL (B01/B06, incidental) | **PASS** (B01/B03) | **PASS** (B04) ⇒ **suite CLEAR**; theory doc missing ⇒ FAIL ✅ (B05) |
| Malformed | FAIL ✅ (B10/B11); binary ⇒ ERROR ✅ (B25) | — | — | ERROR on binary ✅ | — |
| Valid JSON, not an object (B24) | **PASS** | — | ERROR (AttributeError), blocks by accident | — | — |
| Contradictory (B14: duplicate `file_id`, different path) | PASS | **PASS** (registry out of scope) | PASS | PASS | PASS ⇒ **suite CLEAR** |
| Partially missing (B12/B13: truncated at a line boundary) | PASS | PASS | FAIL (incidental) | PASS | PASS |
| Near-miss | **nested `FORMALIZATION/structures/STRUCTURES.jsonl` malformed ⇒ CLEAR** (G01) | — | ref inside a **.md** ⇒ CLEAR (B17); other prefixes (HA-/SI-/C-/TH-) ⇒ CLEAR (B18, by declared design) | trailing space on an index ID ⇒ FAIL ✅ (B19); index entry for an unregistered file ⇒ not checked (caught by 003) | malformed seed ID `SI-044` ⇒ **silently excluded, PASS** (B21); renamed appendix marker ⇒ FAIL ✅ (B23, fail-closed) |
| Deliberate violation | FAIL ✅ | FAIL ✅ (B15) | FAIL ✅ (B16) | FAIL ✅ (fixture) | FAIL ✅ (B22) |
| **False PASS possible** | **yes** (nested files; missing files) | **yes** (missing input) | yes (.md refs; declared "any artifact") | **yes** (missing/empty) | **yes** (missing/empty seed; ID format) |
| **False FAIL possible** | no observed | no observed | ⛔ **yes: citing a correct canonical ID of an unread file BLOCKs** (G02) | no observed | no observed |
| Reaches blocking point | yes (tier A, activated) | yes | yes | yes | yes |
| Scope class | INTERNAL_CONSISTENCY | INTERNAL_CONSISTENCY | INTERNAL_CONSISTENCY (**known set = registry, not corpus**) | INTERNAL_CONSISTENCY | INTERNAL_CONSISTENCY |
| **Purpose = implementation?** | ⛔ **no:** "every .jsonl" vs two directories | ✅ | ⛔ **no:** "any artifact" vs .jsonl only | ✅ (one direction, as declared) | ✅ as declared; ⚠️ missing seed silently accepted |

---

## 5. Fixture coverage

| Check | pass fixture | fail fixture | missing-input fixture | empty-input fixture | near-miss fixture |
|---|---|---|---|---|---|
| all 14 implemented checks | ✅ shared | ✅ shared (13 OK) | ⛔ none | ⛔ none | ⛔ none |
| `theory_doc_exists` (KOS-G-024, not activated) | ✅ | ⛔ **MISMEASURED**: the shared fail fixture has a non-empty body, so the check **correctly** passes. **Fixture defect, not a check defect.** `fixtures/fail/_per_check/` exists but holds no case for it | ⛔ | ⛔ | ⛔ |
| activation logic (3 pure cases) | ✅ | ✅ | — | — | — |

**The fixture suite proves that each check can detect its obvious violation.** It does not prove that the checks refuse to pass on absence of evidence. That was assumption 1 of the fail-open defect, and no fixture tests it.

---

## 6. False-PASS analysis (root causes)

| # | Mechanism | Code | Cases |
|---|---|---|---|
| FP-1 | `_jsonl()` returns `[]` for a **missing** file, the same as for an empty file; set-difference checks then pass on ∅ | l.44–47 | B01, B03, B04, B06–B09 |
| FP-2 | `_seed_and_traced()` treats a missing seed as `set()` | l.179 | B04 |
| FP-3 | `_jsonl_paths()` scans two directories; "every .jsonl" is not what it measures | l.59–65 | G01 |
| FP-4 | `c_no_dangling_refs` scans `.jsonl` only; the gate claims "any artifact" | l.116 | B17 |
| FP-5 | **An activated gate with `status ≠ active` becomes `NOT_APPLICABLE` yet counts as "applicable"** (`applicable = rows whose id is activated`, l.495), so **CLEAR is reported with nothing evaluated** | l.351–355, 495–508 | D08, D09, D16, D17 |
| FP-6 | `blocking` requires `tier == "A"`; **tier and class are read from the editable rule book at run time**, so re-tiering an activated gate silences its FAIL | l.385 | D10 |
| FP-7 | **Self-test is not linked to evaluation**: a 30/31 instrument, or one with its fixtures deleted, still issues CLEAR | l.473–482 vs 484–529 | A0, D06 |
| FP-8 | `gate-schema.yaml` is **never loaded** (only hashed), so a duplicate gate ID or schema-invalid entry is accepted | l.329 | D04, D07 |
| FP-9 | Seed IDs outside `SI-\d{4}` are silently not seed items | l.180 | B21 |

**FP-5 contradicts the runner's own comment** at l.500–506: *"CLEAR must never name a run in which no control was exercised."* The `d62c84c00` fix closed the unknown-ID door. FP-5 is the same failure class through a third door.

## 7. False-FAIL analysis

| # | Mechanism | Case | Consequence |
|---|---|---|---|
| FF-1 | KOS-G-003's known set is the **research registry**, not the canonical corpus, so a **correct** citation of a canonical-but-unregistered ID is "dangling" | G02 (F2837) | ⛔ **it penalises the canonical-ID discipline the F0031–F0040 correction needs** (e.g. noting that canonical F0035 is unread). Activated and tier A, so it blocks |
| FF-2 | An activated REVIEW or HUMAN gate is `REVIEW_REQUIRED` + blocking **with no mechanism to record a completed review**, so the block is **permanent** | D14, D15 | activating any REVIEW or HUMAN gate halts research indefinitely. None is activated today |
| FF-3 | JSONL notes quoting a non-registered `T-/G-/F-` string (e.g. a corpus example) FAIL KOS-G-003 | by construction (FF-1 family) | noise blocks |

## 8. Historical F0031–F0040 reproduction

| Case | Construction | Result | Required | Verdict |
|---|---|---|---|---|
| C1 | registry rows F0005 and F0006 **swap paths**. All paths exist, all IDs are valid, the registry and index are self-consistent, but the **registry disagrees with the canonical list** | **CLEAR** | FAIL | **FAIL** |
| A0 | the **real** HEAD: 9 of 10 rows F0031–F0040 bound to the wrong files | **CLEAR** | FAIL | **FAIL** |
| C2 (control) | F0035 re-bound to its canonical path (`Ontology_Discovery`) | CLEAR | — | indistinguishable from C1/A0 |

**Why each activated gate passed the incident:**

| Gate | What it checked | What it did not check | Outside its declared scope? | Stay internal-only? |
|---|---|---|---|---|
| 001 | JSON syntax | meaning, identity | yes | yes; one purpose |
| 002 | uniqueness of theory-object and relation IDs | registry uniqueness; ID↔path binding | yes | yes |
| 003 | every cited ID exists **in the registry** | whether the registry is **canonical**. The wrong IDs resolve *because they are wrong consistently* | yes | ⚠️ its known set should be **the canonical manifest**, which fixes FF-1 **and** makes it a C1 check. That is a scope decision for L0; otherwise keep it internal and add a separate correspondence gate |
| 010 | registry ⊆ index | registry ↔ corpus | yes | yes |
| 022 | seed ⊆ appendix | source ↔ seed | yes | yes |

**Conclusion:**
- The failure was **outside every activated gate's declared scope.**
- The gates are not "too weak". The **gate set lacks a corpus-correspondence gate**.
- **A correspondence gate is required, and it must be a separate gate with one purpose** (the planned `registry-path-equals-canonical`). No existing gate should be widened to cover it, with the one optional exception of KOS-G-003's known set.

## 9. Governance-instrument integrity

| Case | Condition | Runner | Door | Fail closed? |
|---|---|---|---|---|
| D01 | malformed `gates.yaml` | 3 `GOVERNANCE_INOPERATIVE` | **exit 2, prints "BLOCK"** | ✅ stops. ⚠️ **mislabelled:** the door maps runner exit 3 to BLOCK/2, while P2P §0.3 lists `GOVERNANCE_INOPERATIVE` as a distinct outcome and the door's own header reserves exit 3 |
| D02b | malformed `governance-state.yaml` (live key) | 3 | exit 2 "BLOCK" | ✅ stops (same label defect) |
| D03 | unknown activated ID | 3 | exit 2 | ✅ (the `d62c84c00` fix works) |
| D04 | **duplicate gate ID** | accepted | CLEAR | ⛔ |
| D05 | missing implementation for an activated gate | ERROR → BLOCK | exit 2 | ✅ |
| D06 | fixtures deleted | evaluation unchanged, CLEAR | CLEAR | ⛔ (FP-7) |
| D07 | `gate-schema.yaml` corrupted | ignored, CLEAR | CLEAR | ⛔ (FP-8) |
| A0 | self-test 30/31 | CLEAR | CLEAR | ⛔ **INSTRUMENT FAILURE reported as CLEAR** |
| D18 | runner missing | — | exit 3 | ✅ |
| B25 | corrupted binary input | ERROR → BLOCK | exit 2 | ✅ |

**Classification:** the rule book and activation list **fail closed on unreadability**, which is good. The instrument **does not fail closed on its own invalidity** (self-test, fixtures, schema, duplicates). Under the audit's rule that is **INSTRUMENT FAILURE**, and **current CLEAR results must not be read as research PASS.**

## 10. Activation integrity

| Required property | Test | Result | Verdict |
|---|---|---|---|
| `status: active` alone is insufficient | not activated ⇒ `NOT_ACTIVATED`, non-blocking | ✅ | PASS |
| Activation also requires the human-owned state | yes, but see below | ✅ | PASS |
| Research cannot activate | D12: an edit adding KOS-G-027 is **accepted** | ⛔ only the digest changes | **FAIL** (by design tamper-*evident*; README §5 says so openly) |
| Research cannot deactivate | D13: an edit to `activated: []` is **accepted** ⇒ `NO_ACTIVE_GOVERNANCE_CONTROLS`, exit 0 | ⛔ honest label, still proceeds | **FAIL** (same) |
| Research cannot redefine | D08–D11: status, tier and class edits take effect; D09/D10 ⇒ **false CLEAR** | ⛔ | **FAIL** |
| Unknown activation IDs rejected | D03 | ✅ | PASS |
| Proposed or NYD gates cannot execute as active | D16/D17: activation of a `proposed`/`not_yet_defined` gate is **accepted silently** and counted as applicable ⇒ CLEAR | ⛔ (FP-5) | **FAIL** |
| HUMAN/REVIEW never auto-PASS | D11/D14/D15 ⇒ `REVIEW_REQUIRED`, blocking | ✅ never PASS; ⚠️ never clearable either (FF-2) | PASS_WITH_OPEN_QUESTIONS |

**The two-key design is correct. The keys are not bound to each other.** Activation names an **ID**, and the meaning of that ID (status, tier, class, check) can change underneath it with no signal except a digest nobody is required to compare.

## 11. Namespace audit

Identifier-family census at HEAD. Distinct IDs, number of files in brackets.

| Family | Research tree (excluding prompts, governance, architecture) | Research `governance/` | `architecture/` (this session) | `docs/knowledgeos/governance/` (this session) |
|---|---|---|---|---|
| `KOS-G-nnn` | 8 [5] | 30 [6] | 13 [3] | **45 [3]** (catalog, spec, audit) |
| `G-nnnn` (research gaps) | 13 [22] | 1 [2] | 0 | 2 [1] |
| `G-n` / `G-nn` | 18 [11] | 2 [1] | 1 [1] | 26 [11] |
| `H-n` | 7 [11] | 0 | 2 [1] | 22 [3] |
| `H-Cn` | 0 | 0 | 7 [5] | 0 |
| `D-n` | **69 [37]** | 0 | 0 | 31 [5] |
| `B-n` (research backlog) | 38 [12] | 3 [4] | 0 | 1 [3] |
| `RCI-nnn` | 0 | 2 [1] | 15 [5] | 0 |

**Demonstrated collisions:**

| ID | Meanings found (V) | Kind |
|---|---|---|
| **`KOS-G-001` … `KOS-G-081`** | the research's `gates.yaml` (e.g. 001 = artifacts-parse) **vs** this session's catalog/spec (001 = corpus immutability) | **governance ↔ governance.** RCA §10 withdraws the catalog IDs; the withdrawal is prose only |
| **`G-4`** | corpus: *"qualification rule OPEN — G-4"* (architecture baseline) · *"No second adopting product"* · *"PRODUCT vs DEPLOYMENT / INSTANTIATES"* · *"No authority manufacture"* (checker) · *"FQ first-class callables"* · research enforcement audit: *"no mechanical seed-item → theory-section link"* · **this session's assessment: "human acts unattested"**, cited *inside the research tree* by `architecture/research-control-architecture.md` l.106 | **≥ 6 meanings.** Among the corpus meanings this is a **research provenance/reference problem**; the research already registered it (`C-0010`, `T-0044`, backlog `B-12`; H-3 "closed by G-4"). **The governance meaning is a governance-introduced collision** |
| **`H-1`** | corpus: *"Capability may READ across a space boundary, never own"* · this session's assessment: *"research authority"* | governance ↔ corpus |
| **`D-n`** | corpus decisions (e.g. D-5 mission ratification, D-8 ontology reconciliation; 69 distinct in research) · this session's spec D-1…D-14 | governance ↔ corpus (the research's P3A already found 4 meanings of `D-`) |
| `G-nnnn` vs `G-n` | research gaps are 4-digit, governance and corpus short forms are 1–2 digit | ✅ **distinct by format** (the runner's regex `G-\d{4}` depends on this) |
| `RCI-nnn`, `H-Cn` | only in `architecture/` and in the research's own reference to it | ✅ no collision |

**Recommendation:** a namespace rule **is** justified (§14, NR-1). It is not a rename. The corpus-internal `G-4` ambiguity stays a research question (`B-12`) and is not a governance defect.

## 12. RCI-015 test

| Case | Construction | Result | Required | Verdict |
|---|---|---|---|---|
| E1 | T-0001 `statement` overwritten in place and `status` set to VALIDATED; no revision record | CLEAR | FAIL | **FAIL** |
| E3 | `THEORY-EVOLUTION.jsonl` row 1 `old_formulation` rewritten | CLEAR | FAIL | **FAIL** |
| E4 | a relation row deleted | CLEAR (KOS-G-020 exists but is not activated) | FAIL | **FAIL** |
| E2 | **valid** evolution: new T-0099 plus a relation `refines T-0001`; T-0001 untouched | CLEAR | PASS | PASS |

**Verdict: FAIL.** The mechanism does **not obstruct** legitimate evolution (E2), but it **cannot tell** it apart from destruction (E1, E3, E4).
- **Structural reason:** the runner reads **one working tree**. Detecting "changed without a revision relation" requires a **base state**: a commit diff or a hash chain.
- No input to the runner carries one. P1P §5A defines `previous_record_hash`, and it occurs **0 times** in the data (spec G-004).

## 13. Epistemic-preservation test

| Case | Construction | Result |
|---|---|---|
| F1 | in the theory document, every "candidate" → "established" and "not yet demonstrated" → "demonstrated" | CLEAR |
| F2 | seed-item levels raised from L2 to L4 across the table | CLEAR |

**Classification:** **not mechanically testable as meaning.** It is correctly a **REVIEW/AUDIT** property (KOS-G-045, REVIEW, not activated; RCA §9). The mechanical component that *is* possible is form: "a level changed between two commits without an evolution or revision record". That depends on the same base-state input as §12. **Verdict: INCONCLUSIVE** for automation, and it must **not** be presented as automated.

## 14. Missing-control analysis (evidence-gated)

**NR-1 — Namespace qualification (new rule; human approval required).**

| Step | Content |
|---|---|
| Observed problem | `KOS-G-nnn` means different gates in two governance sources; `G-4`, `H-1` and `D-n` are re-used by governance documents with meanings that collide with corpus and research meanings. A governance document cites "assessment G-4" inside the research tree (§11) |
| Existing rule | RA-9 (no new term may reuse a corpus term with a different meaning) governs **research terms**. RCA §10 names namespaces for **gates and invariants** only. The `gate-schema.yaml` ID pattern covers gates.yaml only |
| Why insufficient | RA-9 does not bind governance documents. §10 does not cover short identifiers (G-n, H-n, D-n) that governance documents mint for decisions and gaps. The catalog-ID withdrawal is prose, and nothing checks it |
| Required property | an identifier minted by governance can never be read as a corpus or research identifier, and vice versa |
| Proposed rule | *Governance-minted identifiers carry a governance-only prefix (e.g. `GOV-GAP-n`, `GOV-DEC-n`, `H-Cn`) and never re-use a short prefix present in the corpus. A reference to another document's short ID is qualified by its source ("assessment:G-4"). Gate IDs exist only in `gates.yaml`.* |
| Enforcement point | a lint over `architecture/` and `governance/*.md` (AUT) |
| Violation fixture | a governance document containing a bare `G-4` or a `KOS-G-nnn` not defined in `gates.yaml` |
| Clean fixture | `GOV-GAP-4`; `KOS-G-010` defined in gates.yaml |
| Human decision | **yes** (it extends RCA §10) |

**Corrections that need no new rule** (they are enforcement of existing RCI-011/014 and the runner's own stated contract):

| # | Correction | Existing rule it enforces | Evidence |
|---|---|---|---|
| IC-1 | a missing input is `INCONCLUSIVE`; empty with 0 examined ⇒ `INCONCLUSIVE`; INCONCLUSIVE on an activated gate blocks | RCI-014; P2P §3A.6 | FP-1/2 |
| IC-2 | an activated gate whose status ≠ active ⇒ `GOVERNANCE_INOPERATIVE`; never counted as applicable | the runner's own l.500–506 contract; README §5A | FP-5 |
| IC-3 | **activation binds to a gate definition**: an activation entry pins the sha256 of the gate's definition; a changed definition (status/tier/class/check) under an activated ID ⇒ `GOVERNANCE_INOPERATIVE` until re-activated | RCI-011 ("never redefines"); two-key design | FP-6, D08–D10 |
| IC-4 | self-test failure, a missing fixture, a schema-invalid or duplicate gate ⇒ `GOVERNANCE_INOPERATIVE` | RCI-014 | FP-7/8, D04/D06/D07 |
| IC-5 | `_jsonl_paths` covers every `.jsonl` under the tree (or the gate text is narrowed); KOS-G-003 scans what it claims, or its claim is narrowed | purpose = implementation | FP-3/4 |
| IC-6 | door: runner exit 3 ⇒ door reports `GOVERNANCE_INOPERATIVE` (exit 3), not BLOCK | P2P §0.3 outcome list | D01–D03 |
| IC-7 | per-check fail fixture for `theory_doc_exists`; missing-input, empty and near-miss fixtures for every activated check | RCI-014 | §5 |
| IC-8 | **do not activate** any REVIEW or HUMAN gate until a review-completion record exists | — (operational, FF-2) | D14/D15 |

**Not justified at this stage:** automating epistemic preservation (§13), and widening any internal-consistency gate into a correspondence gate. The one exception is the optional KOS-G-003 known-set decision (§8).

**RCI-015 enforcement** needs the base-state input (commit diff or hash chain). It is a **design dependency** of increment 2, not a new rule.

## 15. Assessment of the Increment 1a/1b plan

`docs/plans/20260923-0212-kos-evidence-binding-increment-1-plan.md`. ⚠️ This plan was written by this session, so this assessment is a **self-review**.

| Question | Finding |
|---|---|
| Is 1a sufficient? | ⛔ **No.** It covers IC-1, part of IC-4 (self-test linkage) and IC-7 (`theory_doc_exists`). **It misses IC-2 (FP-5), IC-3 (activation–definition binding), IC-4 duplicate/schema, IC-5, IC-6 and IC-8.** FP-5 and FP-6 are demonstrated false-CLEAR paths and must be in 1a |
| Is 1b sufficiently specified? | mostly. **Missing:** (a) FF-1: KOS-G-003's known set must include the canonical manifest, or the correction unit will be blocked for citing canonical IDs; (b) the plan's `receipt-covers-registered` must state its **zero-coverage** semantics explicitly (INCONCLUSIVE, not FAIL) |
| Missing dependency? | ⛔ a **base-state input** for any "changed since" check (RCI-015, epistemic form). Not needed for 1b, needed for 2. Also a **coordination dependency that has already failed**: the research session committed governance drafts (§2) |
| Does any task modify research state? | no. The state-field addition is correctly delegated to a research unit |
| Does source admission duplicate the gate suite? | no. It is C1 at read time only |
| Does it preserve P2P v3.2's two-boundary rule? | yes |
| Are proposed gates separated from source admission? | yes |
| Are legacy receipts treated as unavailable? | yes (INCONCLUSIVE, never fabricated) |
| Is the historical back-test valid? | yes, **if** run on fixtures. This audit's C1 is the fixture form. Running it on live data with the gate activated would BLOCK research, and that is an activation decision |
| Hidden assumptions without L0 approval? | (1) that **this** session may modify `gate-runner.py`, which the research session built; (2) that the canonical list is the manifest source (RC-H-02); (3) that fail-closed may turn today's CLEAR into BLOCK/INOPERATIVE (RC-H-03). All three are listed, but (1) should be an explicit H-decision |

## 16. Exact recommended next step

1. **L0 decisions** (§17), minimum: approve an **extended 1a** = IC-1…IC-8, and confirm who implements (not the research session).
2. Implement **extended 1a only**: fixtures first (every case in Appendix A, specifically B04, B09, D08–D10, D04, D06, D07, G01, G02, C1, becomes a regression fixture with an expected verdict), then GREEN, then self-test at 100%, then an independent review.
3. **Re-run this audit's 55 cases.** Expected outcomes: B04/B09/D08/D09/D10/D16/D17 ⇒ INOPERATIVE or BLOCK; C1 still CLEAR, because that is 1b's job.
4. Only then **1b**, with FF-1 and the zero-coverage semantics added. ⚠️ Given §2A, 1b becomes first an **independent review of the research-produced `evidence/` implementation**: adopt, adapt or reject it (GIA-9). It is no longer a build from scratch, and the plan must be revised before 1b starts. B-14 (18 unresolvable canonical paths) must be resolved or explicitly carried as `MISSING_CANONICAL_SOURCE` before any admission control is activated.
5. NR-1 (namespace) can be decided in parallel. It does not block 1a.

## 17. Human decisions required

| ID | Decision |
|---|---|
| **GIA-1** | Accept this audit's verdict that current CLEAR results are **not** evidence of conformance (§9), and record that in the research state (a research-unit act) |
| **GIA-2** | Approve **extended 1a** (IC-1…IC-8) in place of the plan's 1a |
| **GIA-3** | **Who may modify** `governance/gate-runner.py` and `gates.yaml` (they were built by the research session; RCI-011) |
| **GIA-4** | IC-3 design: activation pins the definition hash (requires re-activation by the human after any gate change) |
| **GIA-5** | KOS-G-003 known set: canonical manifest (it becomes C1) vs registry (it stays C0, and FF-1 then needs a separate fix) |
| **GIA-6** | NR-1 namespace rule (extends RCA §10) |
| **GIA-7** | Standing instruction: **no REVIEW/HUMAN gate is activated** until a review-completion record exists (FF-2) |
| **GIA-8** | Coordination: the research session must not commit `architecture/` or `governance/` files (it did in `6cffbea5e`) |
| **GIA-9** | How to treat the research-produced `evidence/` implementation and `PROPOSED-GATES-RCI.yaml` (§2A): independent review, then adopt, adapt or reject. Adjudicate **B-15** (the separation question the research session raised about itself) |
| **GIA-10** | B-14: resolve the 18 unresolvable canonical paths, or carry them as `MISSING_CANONICAL_SOURCE`. This is a corpus-list question, not a research repair |
| (carried) | RC-H-01…RC-H-07 from RCA §13; RC-H-04 (F0031–F0040 correction) stays after 1b |

## 18. Evidence index

Every conclusion above cites a case ID (Appendix A) or a code line in `governance/gate-runner.py` at digest `fcafa9cd27976ff2`. Result lines were captured to the scratchpad as `input_results.jsonl` (26), `instr_results.jsonl` (20) and `evol_results.jsonl` (7), plus 2 extra cases (G01, G02). They are **re-derivable** with the harness below from `git archive 6cffbea5e`.

---

## Appendix A — Harness and case catalogue

**Harness (verbatim essentials):** for each case, copy the HEAD snapshot to a fresh temp dir, apply one mutation, then run:

```python
r = subprocess.run([sys.executable, "governance/gate-runner.py", "--json"], cwd=t, capture_output=True, text=True)
p = subprocess.run(["bash", ".claude/hooks/governance-preflight.sh"], cwd=t, capture_output=True, text=True)
# record: runner exit, json "result", per-gate verdicts for the 5 activated gates, door exit, door status line
```

| ID | Mutation (on the copy) | Runner result | Door exit |
|---|---|---|---|
| A0 | none (HEAD) | CLEAR | 0 |
| B01 | delete `FILE-REGISTRY.jsonl` | BLOCK (003) | 2 |
| B02 | delete `THEORY-DISCOVERY-INDEX.jsonl` | BLOCK (010) | 2 |
| B03 | delete registry + index | BLOCK (003 only; 010 PASS) | 2 |
| B04 | delete `THEORY-SEED.md` | **CLEAR** | **0** |
| B05 | delete theory document | BLOCK (022) | 2 |
| B06 | delete `THEORY-OBJECTS.jsonl` | BLOCK (003 only; 002 PASS) | 2 |
| B07 | empty registry | BLOCK (003 only; 010 PASS) | 2 |
| B08 | empty registry + index | BLOCK (003 only) | 2 |
| B09 | empty seed | **CLEAR** | **0** |
| B10 | truncate registry line 6 | BLOCK (001, 003) | 2 |
| B11 | cut the last 200 bytes of objects | BLOCK (001, 003) | 2 |
| B12 | registry + index truncated to 30 rows | BLOCK (003) | 2 |
| B13 | registry truncated to 30 rows | BLOCK (003) | 2 |
| B14 | duplicate `file_id`, different path | **CLEAR** | 0 |
| B15 | duplicate theory object | BLOCK (002) | 2 |
| B16 | `T-9999` in a relation | BLOCK (003) | 2 |
| B17 | `T-9999`/`F9999` in theory **.md** | **CLEAR** | 0 |
| B18 | `HA-/SI-/C-/TH-9999` in a relation | CLEAR | 0 |
| B19 | trailing space on an index `file_id` | BLOCK (010) | 2 |
| B20 | index entry `F9998` | BLOCK (003) | 2 |
| B21 | seed row `SI-044` | **CLEAR** | 0 |
| B22 | seed `SI-0999` absent from the appendix | BLOCK (022) | 2 |
| B23 | appendix marker renamed | BLOCK (022) | 2 |
| B24 | line `42` in `GAPS.jsonl` | BLOCK (003 ERROR) | 2 |
| B25 | binary registry | BLOCK (ERROR) | 2 |
| C1 | swap F0005/F0006 paths | **CLEAR** | 0 |
| C2 | F0035 re-bound to canonical | CLEAR | 0 |
| D01 | `gates.yaml` unparseable | INOPERATIVE (3) | **2 "BLOCK"** |
| D02b | state unparseable (live key) | INOPERATIVE (3) | **2 "BLOCK"** |
| D03 | activate `KOS-G-099` | INOPERATIVE (3) | 2 |
| D04 | duplicate gate ID | **CLEAR** | 0 |
| D05 | activated gate → unknown check | BLOCK (ERROR) | 2 |
| D06 | fixtures deleted | **CLEAR** | 0 |
| D07 | schema corrupted | **CLEAR** | 0 |
| D08 | KOS-G-010 `status: proposed` | **CLEAR** (010 NOT_APPLICABLE) | 0 |
| D09 | all 5 activated → `proposed` | **CLEAR** (nothing evaluated) | 0 |
| D10 | KOS-G-002 → tier B, plus a duplicate | **CLEAR** (002 FAIL) | 0 |
| D11 | KOS-G-002 → class REVIEW | BLOCK (REVIEW_REQUIRED) | 2 |
| D12 | state edit adds KOS-G-027 | CLEAR (accepted) | 0 |
| D13 | state edit `activated: []` | NO_ACTIVE_GOVERNANCE_CONTROLS | 0 |
| D14 | activate REVIEW KOS-G-040 | BLOCK (permanent) | 2 |
| D15 | activate HUMAN KOS-G-060 | BLOCK (permanent) | 2 |
| D16 | activate proposed KOS-G-041 | **CLEAR** | 0 |
| D17 | activate NYD KOS-G-047 | **CLEAR** | 0 |
| D18 | runner deleted | — | 3 |
| E1 | overwrite T-0001 in place | CLEAR | 0 |
| E2 | additive refinement T-0099 | CLEAR | 0 |
| E3 | rewrite evolution `old_formulation` | CLEAR | 0 |
| E4 | delete a relation row | CLEAR | 0 |
| F1 | candidate → established in theory | CLEAR | 0 |
| F2 | seed L2 → L4 | CLEAR | 0 |
| G01 | malformed line in nested `STRUCTURES.jsonl` | **CLEAR** | 0 |
| G02 | correct canonical `F2837` cited in a note | **BLOCK (false FAIL)** | 2 |

There were 55 runs, giving **54 valid cases**. Run D02 is discarded as an **invalid test**: its edit hit a commented example in `governance-state.yaml`, not the live key. It was replaced by D02b and is recorded here so that the discard is visible.

---

```text
GOVERNANCE INTEGRITY AUDIT COMPLETE
Implementation: NOT STARTED
Research artifacts: UNMODIFIED
Governance activation: UNCHANGED
```

**Outcome: B — READY WITH CORRECTIONS.** Extend 1a with IC-1…IC-8 and complete it before 1b. One additional rule (NR-1, namespace qualification) is justified by evidence and awaits human approval. It does not block 1a.
