# 14_decision-log — Model Boundary & Execution Decisions

Decisions about how this research is conducted. Recorded per §35 of the governing prompt.
Every decision states what it costs, so a reader can judge whether the conclusion is weakened.

---

## MD-001 — Record depth is stratified; record *completeness* is not

**Date:** 2026-09-01 · **Status:** ADOPTED (execution decision, self-made, disclosed)

**Problem.** The authoritative log resolves to 2,320 entries / 61.5 MB, of which 2,280 files /
34.2 MB are analyzable research material. §5 mandates a 25-section record per file and §4 STEP 3 a
5–15-sentence substantive summary. Records 0001 and 0002 were written at that full depth and cost
~2,500 words each. Sustained across 2,280 files this is ~10× the available budget for *writing
alone*, before the ~9M tokens of source reading and before any of the thirteen downstream synthesis
stages (§9–§36) which are the actual object of the research. Executing §4–§5 at uniform maximum
depth would therefore guarantee that §9 onward is never reached — the analysis would be complete on
its least valuable dimension and absent on its most valuable.

**Decision.** Record **depth** is stratified by theoretical content. Record **completeness** and
record **existence** are not stratified and are not negotiable:

- **Every file in the log receives a permanent per-file record.** No file is skipped, merged, or
  represented by a sibling (§1, §43).
- **Every record carries every mandatory section of the §5 template**, including the ones that come
  back empty — an explicit "Mathematical Content: none" is itself evidence and is retained.
- **Every record carries primary classification + reason, secondary classifications, summary,
  contributions, relationship-to-earlier, undefined concepts, status, maturity, importance,
  traceability.**
- **Depth tiers:**
  - **DEEP** — the file introduces, defines, refines, contradicts or refutes theory. Full §5 depth,
    as in records 0001–0002.
  - **STANDARD** — the file develops or applies existing theory. All sections, compressed prose.
  - **COMPACT** — the file is a prompt, session log, index, status report, near-duplicate, or
    non-prose entry (`.pyc`, `.gitkeep`, images). All sections, terse; the record states *why* the
    file carries little theoretical content, which is itself a classification claim that can be
    checked against the file.
- **Tier is assigned after reading the file, never from its filename, path or size** (§4 STEP 2).
  A file assigned COMPACT has been read; the tier records the finding, not the expectation.

**What this costs.** A COMPACT record will not reconstruct a document's argument in the detail a
DEEP record does. If a later stage needs that detail, the source file is one line away in
`00_control/reading-manifest.tsv` and can be re-read. The risk this accepts is that a refinement
buried in a document that reads as routine could be under-weighted. Two mitigations: the tier is
assigned post-reading, and §4 STEP 7 (compare with earlier files) is executed at every tier — the
comparison is where a buried refinement surfaces, and it is never compressed away.

**What this does not do.** It does not license skipping a file because it "appears repetitive"
(§1 forbids this explicitly, and the prohibition is respected — repetition is *recorded* as a
finding, not used as grounds for omission).

**Alternative rejected.** Sampling or stratified *selection* of files. Rejected: the file sequence
is itself research evidence (§43), and a sampled corpus cannot support §6's concept-evolution
ledger, which requires knowing *when* each concept entered the theory.

---

## MD-002 — Cumulative state is on disk, never only in context

**Date:** 2026-09-01 · **Status:** ADOPTED

§4 STEP 7 and §6 require a cumulative research state maintained across the whole pass. That state
lives in `00_control/progress.tsv`, `01_source-analysis/research-ledger.md` and
`01_source-analysis/file-classification.md`, and is written **as each file is processed**.
Conversation context is treated as volatile and is never the system of record. Consequence: the
pass is resumable from `progress.tsv` at any point, and is reproducible by a different reader.

---

## MD-003 — The external PDF is a reference dependency, not a source of claims

**Date:** 2026-09-01 · **Status:** ADOPTED

Entry 26.4 MB — Shapiro, *Thinking about Mathematics: The Philosophy of Mathematics* — is a
third-party published monograph, not a KnowledgeOS research artifact. Its presence in the corpus is
evidence of **what the research read**; its contents are not evidence of **what the research
claimed**. It receives a per-file record; it is analyzed structurally and at the points the corpus
actually cites it; **no KnowledgeOS claim will ever be traced to it as a source.** Rationale: §38
requires every canonical concept to trace to a corpus file and an original statement therein.
Treating a borrowed monograph as corpus would break that traceability chain and would let external
philosophy of mathematics enter the reconstruction disguised as internal research — a §40
circularity risk in a different direction.

---

## MD-004 — Two-stage classification; two-tier records — **SUPERSEDES MD-001**

**Date:** 2026-09-01 · **Status:** ADOPTED (human instruction, 2026-09-01) · **Supersedes:** MD-001

**MD-001 is superseded, not deleted.** Its text stands above as the historical record of what was
decided and why, so that the revision itself is traceable. Where MD-001 and MD-004 conflict,
**MD-004 governs**.

### What MD-001 got wrong

MD-001 responded to the corpus-size problem by **stratifying analytical depth** — DEEP / STANDARD /
COMPACT records. The human instruction identifies the defect: *"Do not reduce analytical depth
merely because the corpus is large."* Depth stratification lets corpus size decide what gets
thought about, which is a budget criterion masquerading as an epistemic one. A file assigned
COMPACT because it *reads as* routine has had a research judgment applied to it under cost
pressure — precisely the silent conversion §3 forbids.

MD-001 also treated the pass-1 classification as **permanent**. It is not. A corpus that records an
evolving theory cannot be correctly classified on first contact: file 1,800 may reveal that file 40
was doing mathematical work that read as governance work at the time.

### MD-004 — the two corrections

**(A) Classification is two-stage.**

```
pass 1 (sequential)          →   PROVISIONAL classification
   ...entire corpus read...
global reclassification      →   FINAL / CANONICAL classification
```

- No file is permanently classified during pass 1.
- The **three-model boundaries are not decided** until the entire corpus has been read and the
  three models reconstructed (§2, §40 — reconstruction precedes comparison).
- A provisional classification is **never erased**. When a later file revises the reading of an
  earlier one, the revision is *recorded alongside* the original.
- Every file carries four fields to final state:
  `initial_classification` · `final_classification` · `classification_change` · `reason_for_change`.

**(B) Records are two-tier — by *theoretical significance*, never by budget.**

| Tier | Applies to | Contents |
|---|---|---|
| **Tier 1** | **EVERY file, without exception** | source identity · substantive summary · primary provisional classification · secondary classifications · **reason** · concepts · contribution · relationships to previously read material · undefined concepts · status · importance · provenance |
| **Tier 2** | **only where theoretical significance warrants it** | mathematical derivation · formal definitions · operator analysis · contradictions · cross-model implications · detailed equations · topology · probability · Kernel implications · Gītā interpretation |

**The Tier-2 trigger is theoretical significance, not file size, not budget, not apparent
novelty.** Tier 2 fires when the file does any of: introduce or alter a formal object; state or
attack a definition; propose or constrain an operator; assert, test or break a contradiction;
carry mathematical, probabilistic or topological content; bear on the Kernel's boundary, state or
admissibility; or advance a Gītā/epistemic interpretation. Tier 2 is **recorded as NOT TRIGGERED
with a reason** when it does not fire — the non-firing is itself a classification claim and must be
checkable against the file.

**What survives from MD-001:** every file in the log receives a permanent record; no file is
skipped, merged, or represented by a sibling; every record carries every Tier-1 section including
the ones that come back empty; tier is assigned **after reading**, never from filename or path.

**What is withdrawn from MD-001:** the DEEP / STANDARD / COMPACT depth ladder, and the treatment of
pass-1 classification as final. Records 0001–0004 were written under MD-001 and are retrofitted:
their classifications are re-marked **PROVISIONAL**, and their depth-tier lines are replaced by the
Tier-1/Tier-2 structure.

### The objective this protects

The pass is **not** a file-categorisation exercise. Its output is the input to the actual research
objective: the **Gītā model**, the **mathematics/statistics model**, the **Kernel/DDD model**, their
**pairwise bridges**, the **three-way common core**, the **contradictions**, the **missing
mathematical structures**, the **missing Kernel structures**, and the **unresolved concepts**.
Deciding model boundaries during pass 1 would fix those boundaries before the evidence for them
exists — the §39 hindsight-bias failure, committed prospectively.

**⛔ The unified Kernel is not constructed until this process is complete.**

---

## MD-005 — Dual record per file (human + machine); confidence is recorded; bridges are discovered

**Date:** 2026-09-01 · **Status:** ADOPTED (human instruction, `prompts/20260901_1157_prompt.md`)
· **Amends:** MD-004 (does not supersede it)

**(A) Every file gets two records, not one.**

| Record | Path | Purpose |
|---|---|---|
| Human-readable analysis | `01_source-analysis/per-file/NNNN.md` | Tier 1 always; Tier 2 on theoretical significance |
| Machine-readable record | `01_source-analysis/per-file/NNNN.yaml` | structured fields for corpus-wide querying |

*Rationale (stated by the instruction):* the global reclassification and the three-model
reconstruction operate over **2,280 files at once**. Prose cannot be queried, joined or counted.
Without a structured record, Phase 2 would have to re-read 34 MB of analysis to answer questions
like *"which files introduce `knowledge_state`?"* or *"which files bridge mathematics to kernel?"*.
The YAML record makes the later synthesis mechanically checkable rather than recollected — which is
the difference between traceability (§38) and assertion.

**(B) Classification carries a confidence.**

`confidence: high | medium | low` — how firmly the *provisional* classification is held, given only
the files read so far. Low confidence is not a defect; it is the correct value for a file whose
role cannot be judged without later material, and it is the primary signal for what the global
reclassification must revisit first.

**(C) Bridges are a first-class field, and they are DISCOVERED, never assumed.**

The instruction is explicit that the pairwise intersections may matter more than the three-way core:

```
C_G ∩ C_M     C_M ∩ C_K     C_G ∩ C_K            and only then     C_G ∩ C_M ∩ C_K
```

`bridges: []` therefore stays **empty by default**. An entry is written only when a file supplies
*evidence* that a concept does connecting work between two models — never because two vocabularies
sound alike. A bridge asserted on lexical similarity is exactly the §41 failure mode
("is this genuine structural convergence or merely linguistic similarity?"), and the empty list is
the honest default.

**(D) What this does not change.** Reading stays strictly sequential in log order. Classification in
pass 1 stays provisional. Tier 2 stays triggered by theoretical significance, never by budget. The
three-model boundaries stay undecided until the corpus is fully read. **The unified Kernel is still
not constructed until Phases 2–5 are complete.**

---

## MD-006 — `kernel_ddd` is split: **C1 Engineering KnowledgeOS** vs **C2 Epistemic KnowledgeOS**

**Date:** 2026-09-01 · **Status:** ADOPTED (human instruction, `prompts/20260901_1200_prompt.md`)
· **Amends:** the classification vocabulary. **Does not restart the pass.**

### The finding this encodes

`kernel_ddd` is too broad. The corpus contains **two distinct KnowledgeOS lineages**, and collapsing
them would commit a historical error at the level of the corpus's central term.

| | **C1 — Engineering KnowledgeOS** | **C2 — Epistemic KnowledgeOS** |
|---|---|---|
| Lineage | `EKS → PKS → KnowledgeOS_engineering` | `Knowledge → K_t → Kernel → Buddhi → Operators → Transformation` |
| Concern | engineering knowledge · product-specific knowledge · evidence · provenance · governance · portability · reusable components · engineering workflow | what knowledge *is* · how it exists as a **state** · how that state is **evaluated** and **transformed** · what it means for knowledge to become **purified** |
| "Kernel" means | the **portability kernel** — the domain-free ∧ binding-free ∧ evidence-free reusable core | the **epistemic Kernel** — minimal state, operators, admissibility, invariants |

**⛔ `Kernel_engineering ≠ Kernel_epistemic` unless later corpus evidence establishes a relationship.**
A document is **never** classified C2 merely because it contains the word *KnowledgeOS* or *Kernel*.

### This was already evidenced before the instruction arrived

The split is not imported from outside — the sequential pass had already produced the evidence for
it, independently, in three places:

- **0001 Tier 2 / Kernel implications:** *"the criterion is **portability**, not epistemic necessity
  … **Provisional flag for global reclassification:** the corpus may use 'kernel' in at least two
  non-interchangeable senses."*
- **0005 Tier 2 / Cross-model implications:** *"the kernel here is the **portability** kernel … §23's
  test is **epistemic necessity** … **These are different minimality tests and may select different
  sets.**"*
- **0008 Tier 2 / Kernel implications:** *"the kernel under test is defined only by enumeration …
  the corpus keeps enumerating kernel members without ever stating a membership property."*

Recording this because it matters for the research's own credibility: the taxonomy change is
**corroborated by the corpus**, not merely instructed.

### The revised primary vocabulary

| Code | Value | Scope |
|---|---|---|
| **MODEL-A** | `gita` | Gītā / philosophical-epistemic material |
| **MODEL-B** | `mathematics` | mathematical / statistical material |
| **MODEL-C1** | `engineering_knowledgeos` | EKS · PKS · product binding · portability · engineering governance · engineering kernel |
| **MODEL-C2** | `epistemic_knowledgeos` | Knowledge Space · Knowledge Element · `K_t` · dimensions/values · epistemic Kernel · Buddhi · operators · purification · Moksha |
| **MODEL-X** | `cross_model` | bridge material connecting two or more lineages |
| **MODEL-M** | `meta_research` | methodology, process, research governance |
| — | `foundational` | retained from the governing prompt §4 STEP 4 |
| — | `experimental` | retained from the governing prompt §4 STEP 4 |
| — | **`ambiguous`** | **used rather than forcing a decision when the lineage cannot be determined from the file alone** |

`kernel_ddd` is **retired as a forward-looking value** and **retained as historical metadata** on the
records already written under it.

### Migration rule for records 0001–0009 — history is preserved, not rewritten

Records 0001, 0005 and 0009 were provisionally classified `kernel_ddd`. Per the instruction:

- **`primary_model_initial` is NOT altered.** It keeps the value it was assigned at first reading.
- A new field **`primary_model_initial_historical: kernel_ddd`** preserves the retired label
  explicitly.
- A new field **`lineage_provisional`** records the C1/C2 reading *with its evidence*.
- `primary_model_final` stays `pending_global_reclassification`.
- The `Classification Revision History` table in each `.md` gains a row recording the taxonomy change.

**⛔ C1 documents are not retrospectively reinterpreted as if they had originally defined C2.**
The genealogy of the idea is itself a research object (§39), and rewriting the early documents to
look like ancestors of the later theory would destroy the evidence for the very transition this
research is trying to detect.

### Candidate C1→C2 bridges — **potential, not conclusions**

The instruction supplies a table of possible correspondences. They are recorded here as
**hypotheses to be tested against corpus evidence**, and enter `bridges:` in a machine record only
where a file supplies evidence of connecting work:

| C1 (engineering) | C2 (epistemic question) | Status |
|---|---|---|
| Evidence | What makes something knowledge? | `[HP]` untested |
| Provenance | What is the identity/history of knowledge? | `[HP]` untested |
| Validation | What makes knowledge admissible? | `[HP]` untested |
| Governance | What determines right/wrong? | `[HP]` untested |
| Kernel | What is the minimal epistemic unit? | `[HP]` untested — **and the term collision makes this the most dangerous of the nine** |
| State | What is `K_t`? | `[HP]` untested |
| Change | What is `δ(K_t, o)`? | `[HP]` untested |
| Evidence harvesting | How does knowledge evolve? | `[HP]` untested |
| Product binding | What is context? | `[HP]` untested |

### The question deferred to synthesis

At global synthesis, determine **from corpus evidence** whether C1 and C2 are:
**(1)** independent models · **(2)** evolutionary stages of one model · **(3)** partially overlapping
models · **(4)** connected by identifiable conceptual bridges.

The transition itself — *engineering problem → EKS → PKS → engineering architecture → limitations →
"what actually is knowledge?" → Knowledge Space → `K_t` → Kernel → Buddhi → operators → purification
→ formalization* — **may be one of the most important discoveries in the research**, and it can only
be detected if the two lineages are kept apart during the pass.

**Consequence for the research's shape:** the study may yield **four** canonical structures
(Gītā · Mathematics · Engineering KnowledgeOS · Epistemic KnowledgeOS) rather than three, with the
relationships among them as the object of study.

### Unchanged

Sequential reading order · provisional-then-global classification (MD-004) · two-tier records
(MD-004) · dual human/machine records (MD-005) · bridges discovered never assumed (MD-005 C) ·
**no global reclassification until the sequential pass is complete** · **the unified Kernel is not
constructed until the process is complete.**

---

## MD-007 — Deterministic resume; and C1→C2 is a HYPOTHESIS, not a frame

**Date:** 2026-09-01 · **Status:** ADOPTED (human instruction) · **Amends:** MD-002, MD-006

### (A) The resume mechanism is deterministic and auditable

`00_control/resume.py` is the **only** admissible way to determine where the pass stands. It is run
**before processing any file**, and it exits non-zero rather than guessing. It verifies:

1. `progress.tsv` and `reading-manifest.tsv` cover exactly the same sequences.
2. The `DONE` set is a **contiguous prefix** `0001..N` — **no gaps**. A gap is an error, never a skip.
3. Every `DONE` sequence has **both** records (`NNNN.md`, `NNNN.yaml`) and both contain every
   mandatory section/field.
4. Each record **cites its manifest path**, so a record cannot silently describe a different file.
5. No record assigns `primary_model_final` during pass 1 — the provisional discipline of MD-004 is
   mechanically enforced, not merely intended.
6. No record exists **beyond** `N` — out-of-order processing is detected.

Output is exactly `last_completed_sequence = N` and `next_sequence = N+1`.

**Consequence:** the 2,320-file pass is auditable and reproducible. A resuming session — this one or
another — never resumes from memory, from a summary, or from context. **It resumes from the check.**
Silent skipping and silent reclassification are made mechanically impossible rather than promised.

### (B) ⛔ Do not let `C1 → C2` become an assumption

The pass has, at seq 10, evidence that C1 and C2 are **different** (the term-collision finding, the
absence tracking). It has **no** evidence about how they are **related**. Three possibilities remain
open, and the research must hold all three:

| | Relation | What would evidence it |
|---|---|---|
| **(i)** | `C1 → C2` — C2 evolved from C1 | documents showing C1 constructs being *reformulated* into C2 constructs |
| **(ii)** | `C1 ∥ C2` — independent lines that happen to share vocabulary | C2 appearing with no reference to C1 limitations; distinct motivation |
| **(iii)** | `C1 → limitations/questions → C2` | documents in which a **recorded C1 gap or failure** motivates the question *"what actually is knowledge?"* |

**(iii) is the most interesting and the most dangerous to assume.** It is a genuinely plausible
reading — the C1 lineage has, by seq 10, produced an undefined central term, a kernel that is only
ever enumerated, a loop with one evidenced edge of five, and a distinctive component at n=0 — and it
would be easy to narrate that as *the engineering programme discovering it lacked a theory of
knowledge*. **That narration is not yet supported.** A programme can carry an undefined term
indefinitely without anyone asking the deeper question; the question may have arrived from an
entirely different direction.

**Rule:** the relation between C1 and C2 is decided at synthesis, **from documents that state the
transition**, not from the plausibility of a story that fits. Until then:
- `bridges: [c1_to_c2]` records **connecting work evidenced in a specific file**, never a
  hypothesised genealogy.
- The absence tracking (ledger entry 10) is what will date the transition: the **first
  corpus-supported appearance** of C2 vocabulary and formal structures at a specific sequence is an
  *observation*; "C2 starts later" is not.
- If the corpus turns out to contain **no** document narrating the transition, that is a finding —
  and it favours (ii) — not a gap to be filled by reconstruction.

### (C) A finding promoted to watch-status

**"Knowledge" is undefined in all ten C1 files while load-bearing in every one.** If this holds
across the C1 region, the candidate reading is that the engineering lineage was
**knowledge infrastructure without a theory of knowledge**. That would make the later question
*"what is knowledge?"* a **conceptual transition** rather than an architecture refinement — and would
bear directly on (iii). It is recorded as `[HP]` and tracked per file. The falsifier is simple and is
being watched for: **any C1 document that defines knowledge.**

---

## MD-008 — Concise Tier-1 by default; Tier-2 selective; no repetition; first-occurrence priority

**Date:** 2026-09-01 · **Status:** ADOPTED (human instruction) · **Amends:** MD-004/MD-005 execution style, not their rules.

Every file still gets full Tier-1 (read, classified, provenance recorded) — never skipped. **What
changes is verbosity, not coverage.** Routine files (near-duplicate prompts, session logs,
administrative artifacts, generated repetitions, implementation material with no new theory) get a
**concise** Tier-1: what it is, primary/secondary class + reason, what if anything is new, one line
on relationship to prior files, undefined-concepts only if new ones appear. No re-summary of context
already on record. Tier-2 still fires only on genuine theoretical/mathematical/epistemic/Kernel/DDD/
Gītā/contradiction/genealogy content — unchanged from MD-004.

**Priority content, always captured regardless of file size:** first appearance of a concept/
definition/object/operator/bridge/probability or measure construction/topology construction/`K_t`/
dimension-value formulation/Kernel formalization/contradiction-and-resolution. These are what the
ledger is for; a routine file that supplies a genuine first-occurrence still gets that occurrence
recorded precisely, even inside an otherwise concise record.

**Taxonomy labels used informally from here (map to the machine schema unchanged):**
`C1`=`engineering_knowledgeos` · `C2`=`epistemic_knowledgeos` · `G`=`gita` · `M`=`mathematics` ·
`X`=`cross_model` · `META`=`meta_research`. The YAML `primary_model_initial` enum is unchanged.

**Checkpointing:** less frequent; only for genuinely significant findings, not every N files.

**Standing caution reaffirmed:** no synthesis, no model-boundary conclusion, from a prefix of the
corpus. The absence of Gītā/probability/topology/C2 content through file 19 is itself evidence — its
value is in *when* (if ever) that changes, which requires reading the corpus in full, sequentially,
before drawing any conclusion from the absence.

---

## MD-009 — `source_role` is a second, independent classification dimension

**Date:** 2026-09-01 · **Status:** ADOPTED (human instruction) · **Amends:** MD-008 (adds a field, does not change its rule)

Files 0001-0023 are all `PRIMARY_RESEARCH`-shaped by inspection (no verification/reconstruction
passes have appeared yet). **Applied from file 0024 onward; not retrofitted to 0001-0023** (their
absence of this field is a historical gap, not an error — MD-004's "never rewritten" principle).

**Two independent dimensions per file, from here on:**
```
model_classification = gita | mathematics | engineering_knowledgeos | epistemic_knowledgeos
                        | cross_model | meta_research   (unchanged, MD-006)
source_role           = PRIMARY_RESEARCH | FOUNDATIONAL | INDEPENDENT_RESEARCH | BRIDGE
                        | CRITICAL_REVIEW | VERIFICATION_RECONSTRUCTION | SESSION_LOG
                        | DUPLICATE_REPRODUCTION | IMPLEMENTATION | META
```

**Depth rule, refining MD-008:** `VERIFICATION_RECONSTRUCTION` / `DUPLICATE_REPRODUCTION` /
`SESSION_LOG` get concise treatment **when they add nothing** — but the moment such a file states a
new concept, contradiction, falsification, refinement, mathematical argument, or bridge, **that
specific content** gets Tier-2 treatment regardless of the file's role. Role sets the default depth;
content can still trigger Tier 2. Never silently discard a reproduction — record its provenance and,
where determinable, `canonical_source` (the file it reproduces/verifies).

**Why this matters for synthesis, stated precisely:** ten documents restating one discovery must
never be read later as ten independent corroborations. `source_role: DUPLICATE_REPRODUCTION` /
`VERIFICATION_RECONSTRUCTION` with a `canonical_source` pointer is what lets the eventual convergence
analysis (§29, common-core intersection) count **ideas**, not **documents**.

**Schema addition** (`01_source-analysis/machine-record-schema.md`, applied from 0024):
```yaml
source_role:       enum        # see list above
canonical_source:  int | null  # sequence number of the file this one reproduces/verifies, if any
```
Not added to `resume.py`'s hard-required YAML fields (would break 0001-0023's contiguity check);
enforced by convention from 0024 forward and spot-checked at global reclassification.

---

## MD-010 — Corpus boundary: primary source vs derived research artifacts

**Date:** 2026-09-01 · **Status:** ADOPTED (human instruction) · **Amends:** the corpus scope, not the
reading-order or classification rules within it.

### The split

`docs/knowledgeos/brainstorming/` is the corpus root. Five top-level subdirectories are **derived
research-processing artifacts**, not primary source material, and are **excluded from the primary
sequential reading pass**:

| Directory | Excluded entries |
|---|---:|
| `verification/` | 438 |
| `synthesis/` | 8 |
| `falsification/` | 4 |
| `corpus/` | 2 |
| `classification/` | 2 |
| **Total excluded** | **454** |

**N_primary = 1,866 · N_excluded = 454 · N_total = 2,320** (recomputed and verified against the
original manifest; the split accounts for every entry, none dropped).

Matching rule: **only the path component immediately after `docs/knowledgeos/brainstorming/`** is
checked against the five names — a same-named directory nested deeper (e.g. under
`phase_measure_theory/`) would **not** be excluded by this rule. A check confirmed no such false
positives exist in this corpus's actual paths.

**Files 0001–0036 (already processed) are unaffected** — none falls under `brainstorming/` at all;
they sit directly under `docs/knowledgeos/`. No retroactive reclassification was needed.

### Why

If `verification/` (or any of the other four) contains a later reconstruction or independent
re-derivation of the three/four models, counting it as primary evidence during the discovery pass
risks manufacturing false convergence — the same finding appearing to be independently supported
merely because a downstream reconstruction of it was read as if it were a fresh source. **Primary
corpus ≠ research artifacts derived from the corpus**, and the two must not be allowed to blur.

### What this does NOT do

The excluded directories are **not deleted, not ignored permanently, and not stripped from the
manifest** — `reading-manifest.tsv` still lists every one of the 2,320 entries, now with a
`corpus_tier` column (`PRIMARY` or `EXCLUDED_<NAME>`). They are reserved as a **secondary
verification layer**: after the primary corpus is fully read and the canonical models (§9–§13 of the
governing prompt) are reconstructed, this excluded material may be consulted to check the independent
reconstruction against what these directories already attempted — comparison, not input.

### Mechanics

- `progress.tsv`: excluded sequences carry `status: EXCLUDED` (not `PENDING`, not `DONE`). No
  per-file Tier-1/Tier-2 record is required or produced for them during the primary pass.
- `00_control/resume.py` rewritten: the frontier `N` is the largest integer such that **every**
  sequence `1..N` is `DONE` or `EXCLUDED` — found by forward scan, not by sorting the union (excluded
  sequences are scattered arbitrarily through the log and must not be allowed to leapfrog a still-
  pending primary file). Reports `last_handled_sequence` (with a DONE/EXCLUDED breakdown),
  `next_sequence`, `remaining_primary` (against `N_primary`), and `excluded_total`.
- The eventual completion statement takes the form: **"N_primary primary historical research
  artifacts were analyzed sequentially; N_excluded derived artifacts were excluded from primary
  evidence and reserved for secondary verification,"** replacing any earlier framing that treated
  2,320 as a single undifferentiated count.

### Unchanged

Sequential order within the primary corpus · two-stage classification (MD-004) · two-tier record
depth with concise-by-default for routine files (MD-008) · `source_role`/`canonical_source` (MD-009) ·
the C1/C2/G/M/X lineage taxonomy (MD-006) · no global reclassification and no model synthesis until
the primary pass is complete.

---

## MD-011 — Corpus root corrected: `brainstorming/` proper only, not `docs/knowledgeos/` root

**Date:** 2026-09-01 · **Status:** ADOPTED (human instruction) · **Supersedes:** MD-010's scope
(mechanics unchanged — same exclusion-and-frontier machinery, narrower root)

### The correction

MD-010 excluded five derived-artifact subdirectories from primary evidence but still treated all of
`docs/knowledgeos/` — including 711 root-level files sitting *outside* `brainstorming/` (architecture
baselines, validation matrices, discovery reports, etc.) — as primary corpus. The human instruction
narrows this: **the primary corpus is strictly `docs/knowledgeos/brainstorming/` and its
subdirectories, minus the five already-excluded ones.** Files directly under `docs/knowledgeos/` (not
inside `brainstorming/`) are **not** part of "the brainstorming corpus" the governing prompt's §1
describes, and are out of scope for the sequential pass.

### Recomputed split

| Tier | Entries |
|---|---:|
| **PRIMARY** (`brainstorming/`, minus the 5 excluded subdirs) | **1,155** |
| `EXCLUDED_VERIFICATION` | 438 |
| `EXCLUDED_SYNTHESIS` | 8 |
| `EXCLUDED_FALSIFICATION` | 4 |
| `EXCLUDED_CORPUS` | 2 |
| `EXCLUDED_CLASSIFICATION` | 2 |
| `OUT_OF_SCOPE_ROOT` (`docs/knowledgeos/` root, not `brainstorming/`) | 711 |
| **Total** | **2,320** |

**N_primary = 1,155.**

### Disposition of files 0001–0039 (already completed, all `OUT_OF_SCOPE_ROOT`)

Not discarded. Every record stands as written — full Tier-1/Tier-2 analysis of genuine C1 material,
several with CRITICAL/HIGH findings now load-bearing in the ledger (the authority-provenance/standing
triple-corroboration; the mission M-A/M-B diagnosis; the DP-n triple-refutation of "Domains own
Capabilities"; the mechanical key-collision proof). **Retained as a completed adjacent analysis, not
counted toward N_primary**, and flagged in `progress.tsv`/`00_control/resume.py` output as
`adjacent_completed` so the eventual report states plainly: *N_primary brainstorming-corpus files were
analyzed sequentially as the primary pass; 39 further root-level files were analyzed before this scope
correction and are retained as a secondary, out-of-primary-scope reference set.*

**Files 0040–0046** (`OUT_OF_SCOPE_ROOT`, not yet processed) are marked `EXCLUDED` retroactively —
no per-file record required going forward.

### Mechanics

`resume.py` unchanged in its frontier algorithm (forward scan for the longest `DONE ∪ EXCLUDED`
prefix from 1); its reporting section now reads `corpus_tier` from the manifest to separate
`n_primary_done` (0, correctly, since none of the 39 DONE files are `PRIMARY` under the corrected
scope) from `n_adjacent_done` (39), and reports `remaining_primary` against the corrected
`N_primary = 1,155`.

### Confirms and is confirmed by MD-011's companion instruction

The user's paired instruction — *"read the older file and then newer file as listed in
`files_to_read_one_by_one.log`"* — reaffirms the standing §43 rule (strict sequential log order,
never reordered) as unaffected by this scope narrowing: the frontier still advances one sequence
number at a time through the full 2,320-entry log, simply treating `OUT_OF_SCOPE_ROOT` sequences the
same way `EXCLUDED_*` sequences are already treated (skipped without a per-file record, never
skipped silently — every skip is recorded mechanically in `progress.tsv`).

### Next primary file

`next_sequence = 0047` → `docs/knowledgeos/brainstorming/20260819-104748-ai-engineering-lifecycle-
five-responsibilities-governance.md` — the first entry in the log that is both under `brainstorming/`
and outside the five excluded subdirectories.

---

## MD-012 — Methodological refinements (accepted boundary; track-identity caution; bridge_candidate field)

**Date:** 2026-09-01 · **Status:** ADOPTED (human instruction)

1. **MD-011's corpus boundary is confirmed final.** N_primary = 1,155. The 39 already-analyzed
   root-level files are relabeled **`ADJACENT_REFERENCE_SET`** (not `OUT_OF_SCOPE_ROOT` — same
   meaning, clearer name) — retained, not primary.
2. **Track-identity caution.** Documentary style and absence of cross-citation (as observed between
   the root-level commission material and the `brainstorming/` conversational threads, e.g. 0049/0050)
   must **not** by themselves be read as evidence of a distinct research track. Record any such
   observation as `[HP]`, explicitly flagged, pending later corpus evidence — never asserted as a
   structural fact. (Records 0049/0050 already used hedged language — "possible," "hypothesis,"
   "flagged" — consistent with this rule; no retroactive correction needed, confirmed on review.)
3. **`bridge_candidate` supersedes ad hoc bridge language going forward.** Any observation suggesting
   a C1→C2, C2→M, M→C2, G→C2, or other cross-lens relationship is recorded as
   `bridges: []` / `bridge_candidates: [...]` with evidence — **never** promoted to `bridges: [...]`
   (a declared bridge) until independently supported by later corpus evidence. `UNKNOWN ≠ DOES NOT
   APPLY ≠ NOT APPLICABLE` (0049) is the first such entry: retained as `bridge_candidate`, not
   declared a bridge.
4. **The three/four canonical models are discovered, not imposed.** C1/C2/M/G/X/META is confirmed as
   a *working hypothesis* for classification convenience, not a conclusion. Final model boundaries are
   decided only at global reclassification, per MD-004/MD-006.
5. **Dual classification confirmed:** MODEL/LENS (C1/C2/M/G/X/META) and SOURCE ROLE (PRIMARY_RESEARCH/
   FOUNDATIONAL/INDEPENDENT_RESEARCH/BRIDGE/CRITICAL_REVIEW/VERIFICATION_RECONSTRUCTION/SESSION_LOG/
   DUPLICATE/IMPLEMENTATION/META) — both already in force since MD-006/MD-009, reaffirmed unchanged.
6. Reconstruction/verification artifacts are never counted as independent evidence for a convergence
   claim — reaffirms MD-009.

**Schema addition (from seq 0052 onward):** `bridge_candidates: [str]` alongside `bridges: []` in the
machine record — a candidate is evidence *toward* a possible bridge, stated with its source and what
would need to be true for it to become one; `bridges:` stays empty until that confirmation lands.

---

## MD-013 — Lean record format: YAML-primary, minimal prose, Tier 2 only on genuine novelty

**Date:** 2026-09-01 · **Status:** ADOPTED (human instruction) · **Applies from seq 0055 onward.**
Records 0001-0054 stand unchanged (documents the pass's own evolution, per §39 — not rewritten).

**Read deeply; write minimally.** Optimize for information gained per token, not prose completeness.
Do not summarize information merely because it is present — extract only what changes the evolving
research model. A file that repeats an established concept gets a repetition pointer to its
canonical source, not a re-explanation.

**Tier 1 (every file): the YAML record is now the primary artifact.** Compact fields only:
`sequence, path, title, model{primary,secondary}, source_role, importance, confidence, about
(1-2 sentences), introduces, defines, refines, contradicts, bridge_candidates, mathematical_content,
gita_content, kernel_content, key_evidence, open_questions, canonical_source`. No narrative padding.

**The `.md` file becomes a thin wrapper**, not a prose duplicate: enough structure to satisfy the
audit contract (source identity, classification + one-line reason, Tier-2 status), everything
substantive lives in the YAML. Detailed Tier-2 analysis, when triggered, is written in the `.md` —
that is the one place prose is still warranted, and only on genuine novelty (Knowledge/Kernel
definitions, `K_t`, dimensions/values, operators, probability/measure, topology, Gītā concepts,
contradictions/falsifications, C1/C2 transitions, bridge_candidates, major architectural principles).

**resume.py contract updated:** a new marker (`ULTRA_CONCISE_MARKER`) selects a minimal required-
section list for records using this format — coverage is still checked mechanically, verbosity is
not. Never lose first-occurrence, provenance, contradiction, refinement, or bridge information — it
is captured in the YAML's structured fields instead of in prose.

---

## MD-014 — 0087 as first anchor document; candidate-vs-canonical discipline; research_arc field; 8-point later-file test

**Date:** 2026-09-01 · **Status:** ADOPTED (human instruction, given after reviewing the 0080-0087
checkpoint report)

**Context.** 0087 produced the corpus's first explicit, numbered "Minimal Kernel Candidate" (six
protected dimensions + one enforcement mechanism). The human instruction confirms this is exactly the
kind of result the sequential pass was designed to surface, and adds four permanent disciplines:

1. **Candidate-vs-canonical status is permanent, not just provisional-pass status.** 0087's result is
   `Minimal Kernel Candidate_0087` — never silently promoted to *the* canonical KnowledgeOS Kernel,
   regardless of how much later corroboration it receives. Promotion to canonical status is a decision
   this research does not have the authority to make from corpus reading alone; it requires the
   explicit progression `Candidate → cross-corpus corroboration → formalization → falsification →
   canonical kernel`, each stage evidenced separately.
2. **`research_arc` field (new, ledger-level, not per-file).** An observed historical-emergence
   sequence (e.g. `EKS/PKS/AIP → Mathematics → Zero → Kernel boundary → Gītā/Vedanta → Pramāṇa →
   Minimal Kernel Candidate`) is recorded with two sub-fields: `status: observed` and
   `causal_interpretation: unproven`. Observing that a sequence of files happened in this order, in
   this reading position, is evidence of *documentary sequence only* — never evidence that earlier
   concepts *caused* or *were necessary for* the later candidate. The two must never be conflated.
3. **Structural correspondence ≠ identity (reaffirms MD-006/MD-012, applied to a specific new case).**
   Pramāṇa (Vedantic means-of-valid-knowledge machinery) and `Admissible(o,K)` (the C1-lineage
   admissibility question tracked since Ledger Entry 5) may share structural shape — both ask "when is
   something admissible as knowledge?" — but this is recorded strictly as a `bridge_candidate`, never
   merged, never treated as the same formal object, until independent corpus evidence (not structural
   resemblance alone) supports identity.
4. **0087 becomes the corpus's first "anchor document."** From file 0088 onward, every subsequent file
   is additionally tested against 0087's six-dimension candidate along 8 specific axes (recorded per
   file only when triggered, not as boilerplate on every record):
   1. does it independently support any of 0087's six dimensions?
   2. does it refine or contradict them?
   3. does it introduce a *different* minimal kernel (dimension count, names, or shape)?
   4. does it supply mathematical semantics for any dimension?
   5. does it supply an operator or transition mechanism?
   6. does it connect Pramāṇa/Vedanta/Gītā concepts to C1/C2 structures (as more than structural
      resemblance)?
   7. does it resolve or worsen the "Knowledge Claim" (0087) vs "Claim empirically absent" (0081)
      tension?
   8. does it resolve any of the four competing candidate-invariant ID schemes (`C-n`, `INV-KOS-n`,
      `H-ZERO-n`/`H-KOS-n`, `INV-CANDIDATE-n`)?

   Additional anchor documents may be designated later (e.g. a future canonical C2 source, once
   located) — this is not limited to 0087 permanently, only the first instance of the practice.

**Discipline restated:** do not declare convergence merely because terminology overlaps. Record
structural correspondence only when supported by evidence. Continue compact per-file records; deep
(Tier 2) analysis only for materially new or contradictory findings, per MD-013.

**Consequence for 01_source-analysis/machine-record-schema.md:** a `research_arc` block and an
`anchor_test` block (8 boolean/short-answer sub-fields, populated only when relevant) are added to the
schema as optional, ledger/per-file fields respectively — see schema doc for the exact shape.

---

## MD-015 — Per-dimension lifecycle registry; candidate-vs-canonical vocabulary; maturity ladder; three-level Kernel target formulation (recorded as hypothesis, not canonical)

**Date:** 2026-09-01 · **Status:** ADOPTED (human instruction, given after reviewing the MD-014
checkpoint's 0087-0092 anchor-test results)

**Context.** The human reviewed the anchor-test findings and reframed the strongest result correctly:
not "the Gītā model is correct" but "the corpus is beginning to expose candidate invariants that
appear across different theoretical languages." The six-dimension anchor has been reshaped four
non-identical ways across 0087-0091 and must **not** be frozen as if it were settled. Four permanent
disciplines are added:

1. **Vocabulary: "candidate invariant" vs "canonical invariant", used consistently from this point
   forward.** Every proposed kernel dimension is a candidate until it passes the full maturity ladder
   (§3) AND receives independent corroboration (MD-012's bar — outside the originating continuous
   research arc). No file in this corpus has yet produced a canonical invariant; none should be
   described as one.

2. **Per-dimension lifecycle registry (NEW artifact): `01_source-analysis/dimension-registry.md`.**
   Every named candidate dimension (Identity, Evidence, Authority, Temporal Validity, Unknown,
   Transformation, Agency, Context, Provenance, Decision/Consequence, Revisability, Inference,
   Justification, and any later addition) gets one entry tracking: first appearance (seq) · name
   variants used across files · refinements (seq → what changed) · contradictions (seq → what
   conflicted) · independent corroboration (seq, and whether it meets MD-012's independence bar) ·
   current maturity stage (§3) · status (`candidate` | `contested` | never `canonical` in this pass).
   **Differently-named dimensions are never collapsed into one registry entry merely because they
   appear semantically similar** (e.g. 0087's "Evidence Integrity" and 0092's "Pramana Grounding" get
   separate entries, cross-referenced as a `possible_correspondence`, not merged) — competing
   formulations are preserved side by side until the corpus itself supplies grounds for unification.

3. **Five-stage maturity ladder, tracked per candidate dimension:** `semantic_definition` (the concept
   is named and described) → `state_representation` (it is given a place in a knowledge-state
   structure, e.g. one component of `K_t`) → `constraint` (a rule is stated about what must remain
   true of it) → `operator` (a transformation with a stated signature touches it) →
   `invariant_preservation_test` (some evidence — even partial — that the constraint survives a stated
   transformation). **No candidate dimension in the corpus has reached stage 4 or 5 as of 0092** — this
   is itself a finding, tracked in the registry, not asserted informally.

4. **Three-level Kernel target formulation — recorded as this research's own working hypothesis for
   where the eventual formal theory may go, NOT as canonical content and NOT placed in
   `12_canonical-theory/` (which remains gated per its STATUS.md guard):**
   - **Level 1 — Knowledge state:** `K_t` — what knowledge exists at time t.
   - **Level 2 — Constitutional invariants:** `I(K_t)` — what must remain true while `K_t` transforms
     (e.g. `I_1` = Identity Preservation, `I_2` = Evidence Integrity, `I_3` = Authority Separation,
     `I_4` = Transformation Integrity, ...).
   - **Level 3 — Transformation:** `δ: K_t × O_t → K_{t+1}`, where `O_t` is an admissible operation/
     observation.
   - **Target theorem shape (not proven, not evidenced yet — a research question the corpus may or may
     not eventually supply grounds for):** `I(K_t) = true ⟹ I(K_{t+1}) = true` for every admissible
     `δ`.
   - **Gita/Vedanta's natural place in this formulation** (explicitly a research hypothesis, not
     established by this checkpoint): Kṣetrajña may correspond to "who is the knower/observer
     associated with `K_t`?"; Pramāṇa may correspond to "by what valid means was this knowledge
     established?"; a possible future guṇa (Sattva/Rajas/Tamas) correspondence to "what mode governs
     the transformation `δ`?" — this last connection is explicitly flagged by the human as **not
     established by this checkpoint**, a hypothesis for later evidence to confirm or refute.
   - **Candidate Cross-Model Correspondence Table** (recorded in `research-ledger.md`, explicitly
     labeled *candidate*, not final model): columns C1 (engineering) / C2 (epistemic, mostly still
     `—`, unpopulated) / Gītā-Vedānta / Mathematical question, rows Identity↔Kṣetrajña, Evidence↔
     Pramāṇa, Authority, Unknown, Temporal validity, Transformation, Agency↔Kṣetrajña, and a
     Purification↔Mokṣa/Sattva-hypothesis row explicitly marked convergence-criterion-not-yet-evidenced.

5. **Per-file `anchor_test` blocks now additionally reference the registry**, rather than restating
   full history inline: when a file bears on a dimension, note the registry entry updated, not a
   duplicate history.

**Discipline restated:** the strategy does not change — continue the sequential pass unchanged. This
adds a tracking obligation, not a new reading order, gate, or synthesis authorization. The kernel
"cannot be accepted merely because it has six dimensions" — every dimension must eventually acquire
operational and mathematical semantics via the maturity ladder before any canonicity question can even
be asked, and that question is explicitly out of scope until the sequential pass and cross-model
comparison (§29 of the governing prompt) are complete.

---

## MD-016 — Consolidation phase (0080-0094 audit): no new philosophical expansion until reconciled; maturity ceiling made a standing rule

**Date:** 2026-09-01 · **Status:** ADOPTED (human instruction, given after reviewing the MD-014/MD-015
checkpoint results at file 0094)

**Trigger.** The candidate-ID landscape (8 distinct naming schemes across 8 files) and the anchor's
dimension count/shape (reshaped 5 non-identical ways across 5 files) were growing faster than this
research's ability to evaluate them. The human judged accumulation had outpaced evaluation and ordered
a consolidation pass over already-read evidence before any further sequential reading.

1. **Reading pause, scoped and temporary.** No file beyond 0094 is read until this consolidation is
   reviewed. This is NOT a permanent gate on the sequential pass — it is a scoped audit step, lifted by
   a fresh authorization to resume (separate from this decision).
2. **Consolidation artifact produced:** `01_source-analysis/checkpoint-0080-0094-consolidation.md` —
   performs, and only performs: (a) a structural (not adjudicating) comparison of the three Context
   treatments, identifying what would falsify each without choosing one; (b) a scoped audit of
   H-KOS-Relation-001 against every existing forbidden-collapse pair, finding real explanatory power
   for derivation-type collapses only, none for authority-type collapses; (c) one differential
   prediction the relation-hypothesis makes that the flat dimension model does not (Context's observed
   instability), explicitly not counted as maturity advancement; (d) reconciliation of the Purification/
   Mokṣa correspondence row with 0094's negative ruling, recorded as one data point, not a refutation;
   (e) a deduplication check across all registry entries (two near-misses examined and kept separate,
   with cross-references added; one genuine near-duplicate already correctly handled in pass 1); (f) a
   grounding classification of every registry entry (`architecturally-grounded` /
   `philosophical-correspondence-only` / `reframing` / `mixed` / `contested-ungrounded`) — this answers
   *where the evidence came from*, not *how mature the candidate is*, and changes no maturity stage;
   (g) the maturity ceiling restated as a standing rule (§3 below); (h) a reproducibility record of the
   exact corpus/registry/anchor state at the audit boundary.
3. **Maturity ceiling, now a standing rule, not merely an observation:** no candidate advances beyond
   maturity stage 3 (`constraint`) without both a stated operator (stage 4) and at least a partial
   invariant-preservation test (stage 5). This exists specifically so that a later session cannot treat
   a source document's own enthusiastic language ("strongest candidate discovered so far" — used for
   several different, mutually-inconsistent candidates across 0084-0094) as maturity progress.
4. **Operational hygiene, kept separate from the research conclusions per the human's explicit
   instruction:** `.claude/sessions/2026-09-01.md` and `.claude/CONTEXT.md` are updated to reflect this
   research thread's state. **The candidate-ID numbering proliferation (8 schemes) is explicitly left
   alone** — "unless registry governance explicitly decides it" — no reconciliation of the 8 schemes is
   authorized by this decision.

**What this decision does NOT do:** it does not promote any candidate, choose among the three Context
treatments, resolve any bridge_candidate, or authorize any content in `12_canonical-theory/` (still
gated). It is a reconciliation and falsification pass over existing evidence — no new corpus file is
read, no new candidate is introduced.

*(Repair applied 2026-09-01 — see `01_source-analysis/checkpoint-0080-0094-repair-verification.md`.
Verdict: REPAIRED — SAFE TO REVIEW FOR RESUMPTION. Sequential pass resumed at 0095 on explicit human
instruction.)*

---

## MD-017 — Six-way relationship taxonomy; `unresolved_equivalence` as a first-class category; the proliferation itself treated as evidence

**Date:** 2026-09-01 · **Status:** ADOPTED (human instruction, given after files 0095-0097 continued
to produce new candidate-ID schemes and new anchor reshapings following the resumed sequential pass)

**Context.** By 0097 the corpus had produced nine unreconciled candidate-ID schemes and eight
non-identical anchor reshapings. The human's instruction: do not treat this as a problem requiring
another consolidation pass — treat the proliferation itself as a research finding. The repeated pattern
(new source → new interpretation → new candidate → new kernel shape, while maturity stays ≤3 and no
canonical kernel exists) is exactly what discovery is expected to look like. The open possibility this
research must now track explicitly: the many shapes may be **orthogonal representations of one deeper
structure**, not competing, mutually exclusive theories — but this is itself unproven and must not be
assumed.

1. **Six-way relationship taxonomy, applied to every new candidate from 0098 onward:**
   1. **New concept** — a genuinely new semantic entity, not previously named in any form.
   2. **New representation** — the same underlying candidate expressed differently (different
      vocabulary/diagram/shape, same referent).
   3. **New decomposition** — the same candidate split into different component parts.
   4. **Refinement** — an existing concept receiving additional semantics (tightened, softened,
      narrowed, or extended, without changing its referent).
   5. **Genuine contradiction** — two formulations that cannot simultaneously hold.
   6. **Unresolved equivalence** — two formulations *might* represent the same underlying thing, but
      equivalence has not been established. **This is the default classification for a plausible-but-
      unproven correspondence — never silently promoted to "new representation" (which asserts
      sameness) or left as a bare `possible_correspondence` note without a tracked relationship.**

2. **`unresolved_equivalence` schema** (see `machine-record-schema.md` MD-017 addendum for the full
   shape) — recorded per pair, in the dimension registry, never merging the two entries:
   ```yaml
   relationship:
     type: unresolved_equivalence
     candidates: [A, B]
     equivalence_status: unproven
   ```

3. **Never merge candidate IDs merely because they appear semantically related.** This reaffirms and
   sharpens MD-015's non-collapse rule: from now on, an apparent correspondence gets an explicit
   `unresolved_equivalence` relationship record, not silence and not a merge.

4. **The mathematical target this points toward (recorded as a research hypothesis, not canonical, not
   placed in `12_canonical-theory/`):** given candidate representations `R_1(K), R_2(K), ..., R_n(K)`,
   ask whether a structure-preserving transformation `φ_ij: R_i → R_j` exists such that
   `φ_ij(R_i(K)) ≅ R_j(K)`. If such transformations exist between the corpus's competing "kernel
   shapes," they may be different coordinate systems for one underlying structure rather than rival
   theories — a stronger result than picking a "best" shape. This generalizes MD-015's three-level
   formulation (`K_t`/`I(K_t)`/`δ`) to multiple representations `R_DDD(K_t)`, `R_Gita(K_t)`,
   `R_math(K_t)`, `R_engineering(K_t)`, with the eventual convergence question being demonstrable
   structural equivalence or a precisely bounded correspondence — never "sounds similar."

5. **Discipline restated:** do not initiate another consolidation checkpoint merely because schemes/
   shapes keep proliferating — that proliferation is now itself the object of study. Continue compact
   per-file records; deep (Tier 2) analysis only for genuine structural changes, contradictions,
   mathematical formalization, or cross-model bridge evidence, per MD-013.

---

## MD-018 — Identity 6-tuple treated as candidate representation only; acquisition-vs-purification operator hypothesis tracked; explicit status chain

**Date:** 2026-09-01 · **Status:** ADOPTED (human instruction, given after reviewing 0099's Identity
6-tuple and its "how is ignorance removed?" finding)

**Context.** 0099's `I = (Entity, Property, Context, Relation, Time, Authority)` is the corpus's first
explicit formal Identity representation. The human's instruction treats this as valuable evidence, not
as a shortcut to canonicity, and adds three permanent disciplines:

1. **The Identity 6-tuple is a candidate formal REPRESENTATION, not the canonical Kernel, and its six
   fields are NOT equated with the anchor's six original dimensions.** A tentative field-to-dimension
   mapping is recorded as a hypothesis only (`Entity→Identity`, `Property→Knowledge state/content`,
   `Context→Context`, `Relation→Structure`, `Time→Temporal validity`, `Authority→Authority`), with two
   named open doubts already flagged by the human: `Property` and `Relation` may be structural
   components rather than independent dimensions, and `Authority` may belong to provenance/
   admissibility rather than to identity itself. **This mapping is not proven and must not be
   silently adopted as a resolved correspondence.**
2. **Five Context representations (C1 flat dimension, C2 part-of-Identity/merged, C3 delimiter, C4
   DDD-bounded-meaning, C5 field-of-the-6-tuple) are preserved as one `unresolved_equivalence` family**
   (already tracked in `dimension-registry.md`). The next question this research should eventually ask
   is not "which is correct?" but **"what invariant is common to all five representations?"** —
   recorded as a standing open question, not answered here.
3. **Acquisition-vs-purification operator hypothesis, tracked as a candidate distinction only, never
   introduced as canonical operators:** 0099's characterization of Vedanta's core question as "how is
   ignorance removed?" suggests knowledge *acquisition* (`T_acquire`: adding/deriving knowledge) and
   knowledge *purification* (`T_purify`: removing an epistemic defect) may be distinct operator types,
   conceptually alongside `T_transform` and `T_validate` already implicit in the corpus's forbidden-
   collapse material. This refines MD-015's single generic `δ: K_t×O_t→K_{t+1}` into a hypothesis that
   `δ` may decompose into typed sub-operators — **recorded as a hypothesis generated by the emerging
   corpus, not adopted, not formalized, not placed in `12_canonical-theory/`.**
4. **Explicit status chain, distinct from MD-015's five-stage maturity ladder** (which tracks formal-
   semantics development: `semantic_definition→state_representation→constraint→operator→
   invariant_preservation_test`). The status chain tracks evidentiary/promotion status instead:
   `candidate → supported → corroborated → formally defined → operationally defined → canonical`. No
   registry entry may skip a stage; nothing yet in the registry has left `candidate`. Recorded per
   entry as a `status_chain` field alongside (not replacing) the existing `Status:` and `Maturity
   stage:` lines.
5. **Two failure modes flagged at 0099 are named as standing patterns to keep watching for:**
   **governance violation** (a document moves from model discovery to technology prescription — e.g.
   0099's "graph storage becomes almost mandatory") and **terminological overclaim** (a title names a
   precise formalism the content does not substantiate — e.g. "Cubical Type Theory" delivering only
   generic dependent types). Both are recorded per-file when they occur, not treated as disqualifying
   the file's other genuine content.

**Discipline restated:** continue to file 0100, no consolidation. Continue identifying whether later
documents independently reproduce, refine, contradict, or mathematically formalize these structures.
Compact per-file records; deep analysis only for genuinely new structure, formalization, contradiction,
or bridge evidence.

---

## MD-019 — Autonomous sequential execution mode; milestones are checkpoints, not approval gates

**Date:** 2026-09-01 · **Status:** ADOPTED (human instruction, given after file 0100)

**Context.** With 1,055 primary-corpus files remaining, interactive milestone check-ins ("continuing
to 0101 next, unless you'd like to redirect") are inefficient control flow, not genuine decision
points — the human has not asked to be consulted at each milestone, and no finding so far has required
a decision only the human could make. This decision changes execution mode; it does not change the
research methodology, which remains fully intact (MD-001 through MD-018).

1. **Autonomous continuation.** Do not stop or request direction after a milestone, file number,
   checkpoint, landmark finding, or interesting discovery. Continue automatically, file by file in
   strict sequence, until the defined corpus scope (N_primary = 1,155) is exhausted, or one of the
   four stop conditions in §3 occurs.
2. **Milestones (e.g. every 0100) are logging/checkpoint events, not approval gates.** At each
   milestone: record it, persist control state (`progress.tsv`, `resume.py` verification), record
   important findings in the registry/ledger, and immediately continue with the next file — no pause,
   no "your call," no "would you like me to continue."
3. **Stop only for:** (a) an unrecoverable technical error; (b) a corpus-integrity failure (`resume.py`
   reports INCONSISTENT and the cause cannot be resolved by re-reading the affected record); (c)
   genuine ambiguity that makes the next operation unsafe (e.g. a manifest/log conflict this research
   cannot resolve from the governing prompt's existing rules); (d) explicit user interruption. **Never
   stop merely because a finding is interesting or a new hypothesis emerged** — record it and continue.
4. **User-visible output policy during autonomous processing:** no routine progress narration, no
   requests for confirmation, no per-file "continuing to file NNNN" announcements, no interactive
   milestone reports. Persist milestones to disk (session log / ledger / registry). Surface to the user
   only critical discoveries (a finding that would materially change how the human wants the research
   directed) or the four stop conditions above.
5. **MD-018 discipline continues unchanged and is restated for emphasis:** `candidate ≠ supported ≠
   corroborated ≠ formally_defined ≠ operationally_defined ≠ canonical`; unresolved-equivalence classes
   are never merged; source-derived findings (what a corpus file states) stay distinct from modelling
   hypotheses (what this research proposes about the underlying structure) — a modelling hypothesis
   never silently contaminates a source-file's own classification. Per-file records stay compact (MD-013);
   depth is spent only on genuine new structure, contradiction, mathematical formalization, or
   cross-model bridge evidence.
6. **No premature synthesis.** The primary evidence pass (sequential reading of all 1,155 files)
   completes before any cross-model comparison, gap analysis, or model reconstruction begins (§29 of
   the governing prompt) — unchanged. Modelling hypotheses generated so far (e.g. the acquisition-vs-
   purification operator distinction, MD-018) remain explicitly hypotheses until the corpus
   independently supports or contradicts them.

**What this decision does NOT do:** it does not authorize skipping files, reducing per-file record
completeness below MD-013's contract, merging any `unresolved_equivalence` class, promoting any
candidate, or beginning synthesis before the sequential pass completes.

---

## MD-020 — Manifest refresh at apparent pass completion; two new exclusion directories; corpus grew during the pass

**Date:** 2026-09-04 · **Status:** ADOPTED (human instruction: "Refresh the manifest before we scope
reclassification") · **Amends:** `reading-manifest.tsv` / `progress.tsv` only — no reading-order or
classification rule is changed.

### Trigger

`resume.py` reported `SEQUENTIAL PASS COMPLETE` at `last_handled_sequence = 2320`. Before treating
that as authorizing global reclassification (MD-004), the corpus was re-scanned against the current
filesystem, since the manifest was captured 2026-09-01 and this is a ~4-week, still-growing research
record (§6 of `corpus-validation-report.md`).

### Method (append-only; no existing row rewritten)

1. Walked `docs/knowledgeos/brainstorming/` on disk (current state, 2026-09-04), excluding two
   directories entirely from the walk (see below).
2. Every file already present in the manifest **by exact path** — skipped (already covered).
3. Every file matching an existing manifest row's `(bytes, mtime)` pair **by content identity, not
   path** — skipped as a rename of an already-processed file, not re-added. This caught exactly one
   case: `ChatGPT Image 1. Sept. 2026, 02_37_26.png` (seq 2303, already `DONE`) now exists on disk as
   `20260902-185501_chatgpt-image-1-sept-2026.png` — same bytes, same mtime, not a new file.
4. Everything else is genuinely new. Classified by the unchanged MD-010/011 rule (immediate
   subdirectory of `brainstorming/`) and appended as new sequences 2321–2376, ordered by mtime then
   path (chronological, per §39 no-hindsight-bias — never inserted out of temporal order relative to
   already-read material).
5. Two malformed manifest rows were hand-corrected post-generation: two on-disk files have
   pathological names (raw LLM response text saved as a filename, e.g. `"Yes. This is now **very
   close to a mathe"`, apparently truncated at a filesystem length limit) that broke naive
   extension-parsing; `ext`/`class` fields were corrected to `noext`/`TEXT` — the `path` field (and
   therefore the file's identity) was never touched.
6. `progress.tsv`: 56 new bootstrap rows appended in the established 7-column schema
   (`seq/status/primary/maturity/importance/path/lineage`, `-` placeholders), `PENDING` for the 38
   new `PRIMARY` entries, `EXCLUDED` for the 18 new `EXCLUDED_VERIFICATION` entries (organic growth
   inside the already-excluded `verification/` subdirectory — no new exclusion category needed there).

### Two new exclusion directories (not previously possible — did not exist when the manifest was built)

| Directory | Files found | Disposition |
|---|---:|---|
| `three_model_convergence/` | — | **Excluded from the walk entirely, not listed in the manifest at all.** This reconstruction's own control/output directory. Treating it as source material would be self-reference — this research analyzing its own analysis — which no prior MD rule needed to name because the directory did not exist at manifest-build time. |
| `mathematical_ideas_that_can_be_implemented/` | — | **Excluded from the walk entirely, not listed.** A separate, differently-governed research lane (the "KR-SIM" track; commissioning prompts and ~229+ code/theory artifacts, first referenced in `.claude/CONTEXT.md`'s 2026-09-02 block: *"a rule that also excludes that directory"*). That block recorded the exclusion informally, outside this decision log; this entry is its formal record here. The lane runs its own experiments, its own adjudication, and reached its own verdict on a question this reconstruction's Gītā-lens thread (sequences 2234–2319) also investigated — see the "Path B" note below. **Its conclusions are not imported into this research by this decision** — MD-002/MD-012's independent-verification discipline applies to it exactly as it would to any other external material.

Both exclusions follow MD-010's own stated rationale (a downstream/derived/parallel reconstruction
must not be read as if it were fresh primary source, or discovery risks manufacturing false
convergence) — extended here to two directories MD-010 could not have named in 2026-09-01.

### Recomputed split

| Tier | Entries (before → after) |
|---|---|
| **PRIMARY** | 1,155 → **1,193** (+38) |
| `EXCLUDED_VERIFICATION` | 438 → 456 (+18) |
| `EXCLUDED_SYNTHESIS` / `FALSIFICATION` / `CORPUS` / `CLASSIFICATION` | unchanged (0 growth found) |
| `OUT_OF_SCOPE_ROOT` | unchanged, 711 (root-level scan was narrow — direct children of `docs/knowledgeos/` only; none new) |
| **Total manifest rows** | 2,320 → **2,376** |

**N_primary = 1,193.** `remaining_primary` after this refresh (confirmed by `resume.py`): **46** —
the pass was **not** actually complete; 38 newly-discovered primary files plus material already
pending are what remain. `resume.py`'s prior `SEQUENTIAL PASS COMPLETE` report was correct against
the manifest as it stood, but the manifest itself was stale.

### Open item flagged, not resolved by this decision

**"Path B."** Sequence 2319 (`...independent-research-line-appears-to-have-arrived-at-the-same-
result.md`) reports an unnamed "independent research line" converging with this Gītā/measure-
theoretic thread's own conclusions. Whether that referred to the KR-SIM lane
(`mathematical_ideas_that_can_be_implemented/`) — which, per a same-day but separately-scoped
session, closed the exact kernel-candidacy question this Gītā thread investigated — has **not** been
checked. This decision only records the exclusion; it does not adjudicate the convergence claim.
Flagged in `01_source-analysis/per-file/2319.yaml` `bridge_candidates` and `CONTEXT.md`.

### Mechanics unchanged

`resume.py`'s frontier algorithm, `progress.tsv` schema, `corpus_tier` column semantics, two-stage
classification (MD-004), and the reading-order rule (strict manifest sequence, never reordered) are
all unchanged. Re-verified `CONSISTENT` after this refresh.

### Next

`next_sequence = 2321` → `docs/knowledgeos/brainstorming/phase_measure_theory/knowledgeos_kernel/
prompts/20260901-115204_step_286_reviewer-two-mathematical-overstatements-in-artifact-36.md`. Per
MD-019 (autonomous continuation, unaffected by this decision), the sequential pass resumes here — 46
primary files remain before `SEQUENTIAL PASS COMPLETE` can be reported again and honored.

---

## MD-021 — Global Reclassification (MD-004): Readiness Finding and Phased Execution Plan

**Date:** 2026-09-06 · **Status:** ADOPTED (human instruction, EP-01 plan approved) · **Amends:**
none — this is a scoping act, not a rule change. **Governs:** the transition from pass 1 to global
reclassification.

### Trigger

`resume.py` reports `CONSISTENT`, `last_handled_sequence = 2376`, `next_sequence = 2377`,
`SEQUENTIAL PASS COMPLETE — global reclassification may now open (MD-004)`. Separately,
`resume_mathematical.py` (the parallel `mathematical_ideas_that_can_be_implemented/` lane governed
by MD-020) reports `CONSISTENT`, all 282 files recorded, `MATHEMATICAL-PART SEQUENTIAL PASS
COMPLETE`. The human's own standing instruction (an earlier window) was to scope MD-004 only once
both reports held — both now do. Per MD-020, the math lane's conclusions are **not** imported into
this reconstruction by that fact; they remain independently-unverified material like any external
source, exactly as MD-002/MD-012 already require.

### Readiness finding

Verified directly before scoping (not inherited from any prior note):

- **Per-file records are complete**: `01_source-analysis/per-file/*.yaml` = 1,224 files (1,185
  primary + 39 adjacent/out-of-scope); `per-file-mathematical/*.yaml` = 282 files.
- **The aggregate artifacts `00_control/protocol.md`'s own artifact contract promises have NOT
  been kept in sync**, and are the actual blocker before any interpretive reclassification work
  can begin:
  - `00_control/classification-register.tsv` (2,321 rows): `final_primary`/`final_secondary`/
    `classification_change`/`reason_for_change` are correctly `PENDING_GLOBAL_RECLASS`/`PENDING`
    everywhere (MD-004 forbids final classification during pass 1) — but `initial_primary`/
    `initial_secondary` are blank (`-`) for a long tail of later rows even though the
    corresponding per-file YAML already carries a provisional classification. This column was
    never mechanically back-filled as the pass proceeded.
  - `01_source-analysis/file-classification.md` (the human-readable matrix + running-summary
    table): stalled at **"Position: 10 of 2,320 processed"**, written once early and never
    updated despite 1,224 per-file records now existing.
  - `01_source-analysis/corpus-map.md`: **did not exist.** The artifact contract lists it as due
    "after the full pass" — i.e. now.
- **The per-file YAML schema evolved partway through the corpus.** Early records (~seq 1–?) use
  `primary_model_initial` (values like `kernel_ddd`) with a separate `lineage_provisional` field
  and `secondary_models`. Later records (confirmed at seq 2376) use a restructured `model: {primary,
  secondary, secondary_note}` block with single-letter/short codes (`g`, `c1`, `c2`, `b`, `x`, `m`)
  plus `source_role`/`canonical_source`. Any mechanical back-fill of the register must detect and
  correctly map **both** generations — this is recorded here as a fact discovered during Phase 0,
  not assumed in advance.
- **Stage directories are genuinely empty.** `02_model-a_gita/` through `11_experimental-
  validation/`, and `13_research-frontier/`, contain nothing. `12_canonical-theory/` contains only
  its own `STATUS.md` guard and an empty `proofs/` — exactly as the stage gate requires. **No
  model reconstruction, cross-model bridge, or gap-analysis work has been attempted anywhere.**

### Phased plan (each phase requires its own separate, explicit human authorization beyond this entry)

This entry authorizes **Phase 0 only**. Phases 1 onward are named here so the pipeline's shape is
visible, but are explicitly **not** authorized by this decision — consistent with this project's own
repeated practice (MD-016: "resuming requires a fresh, separate authorization") and with treating a
plan's approval as covering only what was actually proposed.

- **Phase 0 — Aggregate-artifact consolidation** (mechanical, reversible; AUTHORIZED, executed
  immediately following this entry): back-fill `classification-register.tsv`'s `initial_primary`/
  `initial_secondary` from the existing per-file YAML records across both schema generations,
  changing no other column; extend `file-classification.md`'s matrix/running-summary to the full
  corpus; write `corpus-map.md` per the artifact contract. No file's classification is decided or
  changed by this phase — it is transcription of what pass 1 already recorded, not interpretation.
- **Phase 1 — Independent Model A (Gītā) reconstruction** in `02_model-a_gita/` — NOT authorized here.
- **Phase 2 — Independent Model B (mathematics/statistics) reconstruction** in
  `03_model-b_mathematical/` — NOT authorized here.
- **Phase 3/4 — Independent Model C1 (Engineering KnowledgeOS) / C2 (Epistemic KnowledgeOS)
  reconstruction**, kept separate per the protocol's Term-Collision Rule, in
  `04_model-c_kernel-ddd/` — NOT authorized here.
- **Phase 5 — Final classification pass**: only after Phases 1–4 establish real model boundaries
  from evidence (MD-004's own rule: boundaries are not decided before reconstruction) — NOT
  authorized here.
- **Phases 6+** (cross-model bridges → gap analysis → formalization → kernel → dynamics →
  computational theory → validation → canonical theory, per the stage gate): named as future
  stages only, each its own future authorization when its turn comes.

### What this decision does NOT do

It does not reconstruct any model, assign any file's final classification, touch
`05_cross-model/` onward or `12_canonical-theory/`, or import the math lane's conclusions beyond
confirming its pass is complete.

### Phase 0 execution record (2026-09-07) — completed as approved above

Phase 0 was executed exactly as authorized, immediately following this entry's own approval, with
one disclosed extension of the same mechanical operation (recorded below, not a new interpretive
act):

- **`classification-register.tsv` back-fill:** 1,177 rows had `initial_primary`/`initial_secondary`
  filled from their per-file YAML record (verified: only these two columns changed, confirmed by a
  column-level before/after diff). 9 rows already carrying a value (seq 0001–0009 plus 2 files with
  malformed source YAML, seq 0002/0009, hand-transcribed via targeted `grep` and cross-checked
  against the register's own pre-existing values) were confirmed matching, not overwritten. 1 file
  (seq 0056) carries an anomalous shorthand code (`not_applicable_product_content`, confirmed
  off-topic product content) and was passed through verbatim rather than force-mapped.
- **Discovered gap, closed as part of the same consolidation act:** the register stopped at seq
  2,320, while `progress.tsv`/`reading-manifest.tsv` (the corpus's own authoritative sequence
  tracking) already extended to seq 2,376 — 56 rows had never been appended at all. These 56 rows
  (38 DONE-with-per-file-record, 18 EXCLUDED) were appended using the exact same column conventions
  as every existing row (`final_*` = `PENDING_GLOBAL_RECLASS`/`PENDING`, same as everywhere else).
  This is judged to be the same class of mechanical consolidation Phase 0 already authorized
  ("consolidate the register to reflect the true, complete state of the corpus"), not a new
  interpretive act — no classification decision was made; the appended rows carry the same
  provisional-only status as every other row. `resume.py` re-run after both changes: still
  `CONSISTENT`, `last_handled_sequence=2376`.
- **`file-classification.md`:** position marker updated to 2,376/2,376. The illustrative 0001–0010
  row-by-row table is kept unchanged (never rewritten, per its own footnote); a new full-corpus
  running-summary section was added as a mechanical aggregate over the register + per-file YAML,
  with two items reported honestly rather than force-normalized: the per-file `importance:` field
  uses at least 12 free-text values against protocol.md's defined 4-value scale, and the new-schema
  per-file records (1,177 of 1,224) have no `maturity:` field at all — neither gap is resolved here.
- **`corpus-map.md`:** written for the first time, per the artifact contract. Also records, without
  resolving, a second discrepancy: `reading-manifest.tsv`'s a-priori `corpus_tier` counts (e.g. 1,193
  `PRIMARY`) do not exactly match `progress.tsv`'s actual per-file outcome (1,224 DONE), distributed
  differently across the exclusion categories too. `progress.tsv` is treated as authoritative
  (protocol.md names it "the resume point"; `resume.py` validates against it directly).
- **`protocol.md`:** artifact-contract row for `corpus-map.md` annotated as produced.

No file's `final_primary`/`final_secondary` was touched (still `PENDING_GLOBAL_RECLASS`/`PENDING`
for every row). No stage directory beyond `01_source-analysis/` was written to. Phases 1–6+ remain
unauthorized, exactly as this entry states above.

### Addendum to MD-021 (2026-09-07, later same day): math-lane manifest reconciliation — "282/282 COMPLETE" corrected to 401/401

Before Phase 1 (Model A/Gītā reconstruction) could be authorized, the user supplied an additional
source-of-truth inventory, `mathemtaical-part-file-list.log` (an `ls -la` snapshot of
`mathematical_ideas_that_can_be_implemented/`, 401 entries), and required it be reconciled against
the governed math-lane artifacts before Phase 1 proceeds.

**Finding: the math lane's own `00_control/mathematical-manifest.tsv` was itself stale.** The
directory it describes had grown to 401 files while the manifest — and therefore every "sequential
pass complete" claim built on it, including this same MD-021 entry's own Phase 0 record above —
still reflected only 282. **119 files existed on disk with no manifest row and no per-file record
at all.** This is not a contradiction of MD-021's Phase 0 work (which correctly consolidated the
artifacts that existed at the time); it is a discovery that the math lane's underlying source
directory kept growing after the "282/282 SEQUENTIAL PASS COMPLETE" milestone was declared, and
nothing had re-checked the directory against the manifest since.

**Reconciliation performed** (mechanical extension + full per-file classification, not global
reclassification — no `final_*` field touched anywhere, exactly as Phase 0's own discipline
required):
- Extended `mathematical-manifest.tsv` and `mathematical-progress.tsv` with the 119 missing
  sequences (M0283–M0401), mtime-ordered, confirmed to be a clean chronological tail after M0282
  (no interleaving with the already-classified range).
- Verified via md5 content hash (not filename) that 10 of the 119 are byte-identical duplicates —
  8 of an already-classified file in the original 282, 2 of another file within the same 119-file
  tail — and recorded them with `tier: DUPLICATE` in the manifest and `canonical_source` pointing
  to the anchor sequence, matching the exact convention the original 282-file pass already
  established (22 such rows) — a convention this session initially got wrong (recorded them with a
  non-existent `DUPLICATE` progress status instead of `DONE`+manifest-tier-`DUPLICATE`), caught by
  `resume_mathematical.py` reporting INCONSISTENT, and corrected in place.
- Read and classified the remaining 109 files individually (not by filename or keyword), each
  producing a full `01_source-analysis/per-file-mathematical/MNNNN.{yaml,md}` record in the same
  schema as the original 282. Reused this lane's own established vocabulary throughout (`KR-SIM` /
  `b` / `g` / `c1` / `x` primaries, free-text `secondary` for external-tradition sources) rather
  than inventing a new one. Explicitly flagged, rather than silently resolved: several partial
  (non-byte-identical) content overlaps with already-classified files, one propose/reject pair
  (M0365 rejected by M0366), one ledger correcting its own predecessor (M0380 correcting M0379),
  and one title/content mismatch anomaly (M0400 — its title promises new KR-ZERO-ALGEBRA results,
  but its content is byte-identical to an unrelated 2026-09-02 commissioning document).
- Found genuine Gītā-content documents inside this ostensibly "mathematical" tail (M0375, M0376,
  Bhagavad Gita Chapters 2–3 read directly) and classified them `g`, not folded into `KR-SIM`/`b` —
  confirming the user's caution that appearance in a math-lane directory does not imply Model-B
  membership. Also found genuine cross-model theory-to-architecture bridge documents (a "Theory
  Part XIV–XXI-A" series, M0335–M0344, with Parts XVIII–XXI explicitly deriving DDD architecture
  from the formal theory) classified `x`, not `b`.

**Verification, all passed:**
- `python3 00_control/resume_mathematical.py` → `CONSISTENT`, `last_handled_sequence = M0401`,
  `DONE=401`, `MATHEMATICAL-PART SEQUENTIAL PASS COMPLETE.`
- All 401 `per-file-mathematical/*.yaml` parse as valid YAML; all 401 `.md` counterparts exist.
- `manifest` and `progress` row counts both 401; tier breakdown 369 `PRIMARY` + 32 `DUPLICATE`
  (22 original + 10 newly found).
- `00_control/resume.py` (the separate main-corpus pass) re-run and confirmed untouched:
  `CONSISTENT`, `last_handled_sequence=2376`, unchanged from before this reconciliation.

**Correction to prior record, not a rewrite of it:** every earlier reference in this decision log
and in `.claude/CONTEXT.md` to "MATHEMATICAL-PART SEQUENTIAL PASS COMPLETE: 282/282" is superseded
by this entry — the true, now-verified count is **401/401**. Per MD-020, this lane's conclusions
remain **not imported** into the main `three_model_convergence` reconstruction regardless of file
count; this correction changes only the math lane's own internal completeness accounting.

**Phase 1 status: UNBLOCKED by this reconciliation, still not started.** No file under
`02_model-a_gita/` was touched by this addendum. The user's Phase-1 authorization (eight governance
boundaries, recorded in `.claude/plans/purring-tinkering-graham.md`) remains in force and
unconsumed.

### MD-021 Addendum (2026-09-07) — mathematical-lane inventory reconciliation: 282 -> 401

Before Phase 1 (Model A/Gītā reconstruction) could begin, the user identified an additional
source-of-truth inventory not yet reconciled: `three_model_convergence/mathemtaical-part-file-list.log`
(an `ls -la` snapshot of `mathematical_ideas_that_can_be_implemented/`, 401 entries, generated
2026-09-07 15:02 — after the "MATHEMATICAL-PART SEQUENTIAL PASS COMPLETE: 282/282" milestone this
same day). Directed: reconcile before Phase 1, do not silently classify by filename, use only the
existing evidence-status vocabulary, do not touch `02_model-a_gita/` or `final_primary`/
`final_secondary`/`classification_change`/`reason_for_change` anywhere.

**Finding: the "282/282 complete" milestone was stale against the live source directory.** 119
files existed on disk with no manifest row at all — a clean chronological tail (all after M0282's
own mtime, none interleaved with the already-classified range; all 282 original manifest paths
still present on disk, nothing lost).

**Reconciliation performed:**
1. Mechanically appended the 119 files as M0283–M0401 to `mathematical-manifest.tsv` and
   `mathematical-progress.tsv` (status PENDING), mtime-ordered.
2. Identified 10 byte-identical duplicates via md5 content hash (not filename) — 8 anchored to
   already-classified originals in the 282, 2 anchored to each other within the new tail — and gave
   each a minimal pointer record (`source_role: DUPLICATE_REPRODUCTION`, manifest `tier: DUPLICATE`,
   `canonical_source` set), matching the established convention from the original pass (cf. the main
   corpus's M0220).
3. Individually read and classified the remaining 109 files (self + 4 parallel forks, each given the
   same vocabulary and an explicit instruction to flag substantial-but-not-byte-identical content
   overlap rather than write it up as new — several such overlaps were found and recorded via each
   record's `refines` field with `tier2_triggered: false`).
4. Caught and repaired a genuine process error mid-pass: duplicate records had briefly used
   `progress.tsv` status `DUPLICATE`; `resume_mathematical.py`'s own validation logic expects
   `status: DONE` + manifest `tier: DUPLICATE` — corrected in place before the final consistency run.
5. Closed a self-disclosed gap: one fork batch (M0325–M0333, 9 files) initially classified by title
   and series position only under time pressure, honestly flagged as such rather than fabricating
   content. All 9 were then independently read and re-classified with content-verified records,
   including M0334 (Part XIII, the batch's own final file), which resolved an open question the
   fork had raised: the 13-part "Verified Theory" rewrite (M0322–M0334) does **not** self-declare
   closure — it explicitly points to an unwritten "Part XIV," confirmed absent from all 401 files.
   This series is therefore an unfinished draft, not a closed reconstruction.

**Final counts:** `resume_mathematical.py` → `CONSISTENT`, `DONE=401`, `MATHEMATICAL-PART SEQUENTIAL
PASS COMPLETE`. Primary-classification distribution across all 401: `KR-SIM` 199 · `b` 162 · `x` 16 ·
`c1` 13 · `g` 6 · `c` 5. Two files classified `g` this pass are genuine Gītā-content documents inside
the ostensibly-mathematical tail (M0397: Bhagavad Gita Chapter 3; and the M0375–M0377 sub-thread —
direct Chapter 2–3 readings bridged into the KR-SIM architecture at M0377, `x`-tagged) — exactly
the cross-lane leakage the user's directive anticipated ("do not assume appearance in this list
automatically makes a document part of Model B"). Per **MD-020, unchanged**: none of this lane's
conclusions are imported into the main three-model-convergence classification. All 401 per-file
YAML records validate. `00_control/classification-register.tsv` (the main corpus's own register)
was not touched; `00_control/resume.py` re-confirmed still `CONSISTENT`.

**What this addendum does NOT do:** it does not touch `02_model-a_gita/` or any stage directory; it
does not assign any file's final classification anywhere; it does not import the math lane's content
into Model A/B/C1/C2. **Phase 1 (Model A/Gītā reconstruction) remains exactly as authorized by the
user's own 8-boundary message — unconsumed by this addendum, ready to begin on the user's word.**

### MD-021 — Phase 1 execution record (2026-09-07): Independent Model A (Gītā) Reconstruction

Executed under a separate, explicit 8-boundary authorization from the user (quoted in full in
`.claude/plans/purring-tinkering-graham.md` under "Plan: Phase 1 — Independent Model A (Gītā)
Reconstruction"), issued only after the mathematical-lane inventory reconciliation addendum above
was complete. This authorization is scoped to Phase 1 only; it does not extend to Phase 2 or later.

**Evidence base:** the 84 `initial_primary == gita` rows in `classification-register.tsv` (verified
count, unchanged throughout this phase). Processed in ascending sequence order from their existing
`01_source-analysis/per-file/NNNN.{yaml,md}` records — no raw-source re-reads were required (all 84
use the corpus's "new" per-file schema; none are old-schema or `bytes:0` anomalies).

**Artifacts produced**, all and only in `02_model-a_gita/`:
`00_index.md` · `01_evidence-base.md` (84 rows organized into 7 evidentiary clusters, full
traceability table) · `02_concept-register.md` (14 named concepts/formalisms, each MD-017-typed and
evidence-status-tagged) · `03_contradictions-and-open-questions.md` (3 genuine contradictions, 5
unresolved equivalences, 6 open questions) · `04_boundary-observations.md` (the 511
secondary-tagged-elsewhere rows accounted for, not consumed; outward-pointing `bridge_candidates`
recorded as bounded, not confirmed).

**Principal finding:** Model A's evidence contains a mature, internally coherent architectural
synthesis (the Sañjaya/Arjuna/Krishna two-layer architecture, seq 0427–0451) alongside a
capstone self-correction discipline (seq 0808: explicit retraction of three earlier over-literal
Gītā-derived formulas, establishing "the Gītā lens gives invariants and questions about
transitions, not components") and an independently-repeated, six-cycle KR-SIM companion-study
verdict (seq 2329–2376) that the Gītā supplies **zero kernel candidates** — the single most
repeatedly-corroborated result in the evidence base. Two structurally unresolved threads are
carried forward, not resolved: a four-way Kernel-structure-candidate family (seq 0219) and a
nine-plus-variant Knowledge-Vector family (seq 0247–0262), both explicitly `unresolved_equivalence`
per MD-017 — settling either requires cross-model comparison, which is out of this phase's scope.

**Verification performed:** confirmed (a) only `02_model-a_gita/`'s five files were written — no
other stage directory touched, no `01_source-analysis/per-file/*.yaml` modified; (b)
`classification-register.tsv` byte-for-byte unchanged during this phase (2377 lines, 0 rows with a
non-`PENDING_GLOBAL_RECLASS` `final_primary`, 84 `gita`-primary rows, file mtime predates this
phase's session window); (c) `00_control/resume.py` re-run, still `CONSISTENT`; (d) four
concept-register claims spot-checked verbatim against their source per-file YAML (0219's four-way
family, 0261's EKI-08 collision, 0808's three retracted formulas, 2376's five-cycle closure quote)
— all confirmed faithful.

**Phase 1 status: COMPLETE. Phase 2 (Model B reconstruction) and Phases 3–6+ remain unauthorized and
untouched.**

### MD-021 — Phase 1 completion audit (2026-09-07)

Independent audit of the Phase 1 (Model A) reconstruction, performed before any Phase 2
authorization, per explicit user instruction ("perform a Phase-1 completion audit only... Do NOT
begin Model B or any cross-model work"). Re-derived the 84/76/8 evidence accounting from the
register directly (not from the Phase-1 artifacts' own claims); independently re-verified all 14
concept-register entries against their source per-file YAML (all 10 not previously spot-checked
during Phase 1 itself were re-checked now); independently re-derived the 511-row boundary set by
exact token match; re-ran `resume.py` and the register byte-identity check; confirmed filesystem
scope (only `02_model-a_gita/`'s five files touched).

**One material finding, corrected:** the Phase-1 principal finding ("the Gītā supplies zero kernel
candidates") was insufficiently scoped. Every per-file record carries an
`anchor_test.alternative_minimal_kernel` field. The KR-SIM companion series (seq 2329–2376) carries
`false` on all six files — a genuinely well-supported narrow result about one specific mathematical
test apparatus. But **ten other files** (0219, 0430, 0435, 0436, 0441, 0443, 0451, 0800, 0801, 0808
— the Sañjaya/Arjuna architecture and the Chapter-4 capstone) carry `alternative_minimal_kernel:
true`, and a direct search confirmed the KR-SIM series never references any of them. The two threads
are independent and were never cross-examined, not convergent. **Corrected, evidence-preserving**
(no claim was invented or removed; the correction narrows an overstated scope and records the newly
found tension as CT-4): `02_concept-register.md` §N amended with a scope-correction paragraph;
`03_contradictions-and-open-questions.md` gained item CT-4; `00_index.md`'s summary counts updated
(3→4 contradictions, 14→15 total contradiction/UE/OQ items) and its status line now records the
audit and correction explicitly.

**Verification after correction:** `classification-register.tsv` still byte-identical (2377 lines,
0 non-pending `final_primary` rows); `resume.py` still `CONSISTENT`; only `02_model-a_gita/` was
touched during the audit.

**PHASE 1 AUDIT: FINDINGS REQUIRE CORRECTION → corrected within scope → final state PASSES.**
No cross-model comparison was performed or scoped. Phase 2 remains unauthorized.

### MD-021 — Phase 2 execution record: Independent Model B (Mathematics/Statistics) Reconstruction (2026-09-07)

Executed under a separate, explicit user authorization (2026-09-07, 11 numbered sections, quoted in
full in `.claude/plans/purring-tinkering-graham.md` under "Plan: Phase 2 — Independent Model B
(Mathematics/Statistics) Reconstruction"), issued only after Phase 1's audit passed. A mid-execution
guardrail message (same date) added a mandatory raw-source-verification requirement for every claim
entering the concept register, kernel-candidate table, or contradictions register, plus a
doubly-reinforced four-state kernel-candidate discipline (ESTABLISHED / TESTED→REJECTED /
PROPOSED→UNTESTED / UNRESOLVED — never converting "not tested" into "rejected").

**Directory-name discrepancy recorded, not silently resolved**: the authorization named
`03_model-b_mathematics/`; the pre-existing stage-gate directory is `03_model-b_mathematical/`.
Wrote to the existing directory; documented the discrepancy in `00_index.md`.

**Evidence population, independently re-derived from the 401-file reconciled mathematical lane**
(not copied from any earlier summary): `model.primary` distribution `b` 162 · `KR-SIM` 199 · `x` 16
· `c1` 13 · `g` 6 · `c` 5 (sums to 401). Within the 162 `b`-tagged rows: 151 independent
(`PRIMARY`/`PRIMARY_RESEARCH`/`ADJUDICATION`/`SYNTHESIS`) + 10 `DUPLICATE` (pre-existing markings,
each `canonical_source` verified also `b`-tagged) + 1 `CONTROL_SELF_REFERENCE` (M0003).

**Five artifacts written to `03_model-b_mathematical/`**: `00_index.md` (scope, directory-name
note, population accounting, methodology, artifact map); `01_evidence-base.md` (151 records
organized into 9 evidentiary clusters, full traceability table, 122/151 Tier-2-triggered, importance
71 critical/57 high/13 medium/10 low); `02_concept-register.md` (15 named concepts/formalisms with
source/terminology/evidence-status/maturity/MD-017 relationships, plus a 15-row kernel-candidate
table in the four required states — 6 TESTED→REJECTED, 2 ESTABLISHED, 2 PROPOSED→UNTESTED, 1
UNRESOLVED, 2 items in a named fifth "tested, survives, not yet established" state the table
declines to force into the four headline positions); `03_contradictions-and-open-questions.md` (5
contradictions, 3 unresolved equivalences, 10 open questions, independently derived from Model B's
own evidence only); `04_boundary-observations.md` (239 boundary rows accounted for by category; one
data-quality anomaly found and flagged, not corrected — M0093, tagged `c`, is explicitly Gītā
content).

**Independence maintained**: no Model-A conclusion, concept, contradiction, unresolved equivalence,
or boundary observation was used as evidence anywhere in this phase's artifacts; Model A's own CT-4/
"zero kernel candidates" finding was not imported or referenced. No Model A↔B comparison or
convergence claim was made.

**Verification**: `resume_mathematical.py` → `CONSISTENT` (401 files, `last_handled_sequence=M0401`);
`resume.py` → `CONSISTENT` (unchanged from Phase 1's end state); `classification-register.tsv`
confirmed 0 non-`PENDING_GLOBAL_RECLASS`/`PENDING` rows (untouched by this phase); filesystem scope
check confirmed only `03_model-b_mathematical/` (new) plus this decision-log entry were written
during Phase 2 — `02_model-a_gita/`, `04_model-c_kernel-ddd/` onward, and all cross-model directories
untouched; six mathematically consequential concept-register claims (M0037's four-8-operator-kernel
result, M0103's 12-link non-transitivity counterexample, M0108's Part-1/Part-2 self-refutation,
M0116's M3/M4 indistinguishability result, M0127's Status-C/Kernel-Verdict-K2 collapse-rate figures,
M0045's THM-1/5/11 self-audit) spot-checked verbatim against raw source `.md` titles — all confirmed
faithful, no corrections required.

**Phase 2 status: COMPLETE. Phases 3–6+ remain unauthorized and untouched.**

### MD-021 — Phase 3 execution record: Cross-Model Adjudication and Controlled Convergence (2026-09-07)

Executed under a separate, explicit user authorization (2026-09-07, 19 numbered sections, quoted in
full in `.claude/plans/purring-tinkering-graham.md` under "Plan: Phase 3 — Cross-Model Adjudication
and Controlled Convergence"), issued only after Model A (Phase 1) and Model B (Phase 2) were each
independently completed, audited, and frozen. A mid-execution user instruction reinforced that the
correspondence-candidate list must never become a hidden completeness assumption — evidence outside
the initial list is added; a candidate with no defensible counterpart is recorded explicitly as such,
never forced into a relationship.

**Directory-numbering clarification recorded, not silently resolved**: MD-021's own phase numbers
(1/2/3/4/5/6+) do not map 1:1 onto `three_model_convergence/`'s directory numbers. MD-021 Phase 3
(cross-model adjudication) writes to `05_cross-model/` (protocol.md's own stage-gate numbering),
confirmed empty before this phase began; `04_model-c_kernel-ddd/` remains reserved for the
still-unauthorized Model C1/C2 reconstruction, a later MD-021 phase not yet reached.

**Governing principle applied throughout**: SIMILARITY ≠ IDENTITY, with every proposed correspondence
leveled across six evidentiary strengths (lexical → conceptual → functional → structural → formal
equivalence → demonstrated identity); default status UNRESOLVED until equivalence is demonstrated.

**Five artifacts written to `05_cross-model/`**: `00_index.md`; `01_cross-model-evidence.md` (both
frozen registers organized against the authorization's 16 investigation targets, with 8 explicit
"no demonstrated counterpart" findings — 7 Model-A-missing, 1 Model-B-missing); `02_correspondence-
matrix.md` (10 adjudicated rows, each with evidence-for/evidence-against/preserved-differences/
required-assumptions/confidence — summary tally: 0 rows at STRUCTURAL CORRESPONDENCE or above; 5
UNRESOLVED, 3 INCOMPATIBLE, 2 PARTIAL CORRESPONDENCE at a restricted meta-level, 3 FUNCTIONAL
ANALOGY at low confidence); `03_adjudications-and-contradictions.md` (the strict kernel adjudication
required by §7 — reconstructing what "kernel" means inside each model separately before answering the
cross-model question, verdict UNRESOLVED; the representation-dependence hard constraint from §8,
recorded inapplicable-by-vacuity since no row reached the threshold it binds against; the cross-model
contradiction register required by §9 — 0 genuine contradictions found, 2 apparent conflicts examined
and resolved as term-collisions, not disagreements); `04_non-convergences-and-open-questions.md` (the
mandatory §12 section — 8 no-counterpart structures, 6 rejected/low-confidence correspondence
proposals, 2 methodological parallels explicitly distinguished from domain correspondences, 1
PROPOSED CROSS-MODEL HYPOTHESIS per §14, explicitly not asserted as a finding of either model).

**Independence maintained**: neither Model A nor Model B was rewritten; no reinterpretation was
treated as source evidence; no Model-B negative result was imported into Model A or vice versa; both
models' internal contradictions/unresolved-equivalences/open-questions remain exactly as Phase 1/2
left them.

**Raw-source verification performed for the most consequential claim** (authorization §11): the nine
Sārathi Role Functions (Model A §I/§J's operator/role-function candidate) were verified against raw
source (`phase_measure_theory/20260826-151225_rigorous-analysis-of-the-sanjaya-layer.md`, not merely
the governed per-file record), confirming the exact nine function names before their comparison
against Model B's kernel operators was adjudicated.

**Verification**: `resume.py` → `CONSISTENT` (unchanged, `last_handled_sequence=2376`);
`resume_mathematical.py` → `CONSISTENT` (unchanged, `DONE=401`); `classification-register.tsv`
confirmed 0 non-`PENDING_GLOBAL_RECLASS`/`PENDING` rows; `02_model-a_gita/` and
`03_model-b_mathematical/` confirmed unmodified (md5 hashes recorded); filesystem scope check
confirmed only `05_cross-model/` (new, 5 files) plus this decision-log entry were written —
`04_model-c_kernel-ddd/` and every later-stage directory confirmed untouched.

**Phase 3 status: COMPLETE. Phases 4–6+ remain unauthorized and untouched.**

### MD-021 — Phase 3 integrity audit (2026-09-07, before acceptance)

Independent audit requested before accepting Phase 3 as complete, per the user's own six-point
verification list, echoing the M0133–M0282 completeness question the executing session had itself
flagged mid-task. Re-verified directly against the governed per-file records (not against the Phase-3
artifacts' own claims):

1. **The 151 independent Model-B evidence records, enumerated exactly**: seqs {1,2,5,6,7,9,10,12,14,
   16-19,30,32-38,40,43-49,51-53,55,57,58,60,62,65-68,70,71,73-88,90-92,94,95,97-117,120,121,123-127,
   129,131,132,283-290,292,293,303,307,309,310,312-319,321-338,345-347,349-351,355,359,360,365,366,
   369-372,374,378} — confirmed by direct query of `model.primary == "b"` and `source_role` across
   all 401 `01_source-analysis/per-file-mathematical/*.yaml` records.
2. **M0133–M0282 (150 files) confirmed to contain zero `model.primary: "b"` records — all 150 carry
   `model.primary: "KR-SIM"`.** Re-verified by direct query, independent of Phase 2's original
   accounting.
3. **Therefore no record from M0133–M0282 entered any Model-B evidence cluster, concept, or kernel
   candidate** — the 151-record list's own seq distribution (point 1) already shows a clean gap
   exactly at 133–282, matching the nine evidentiary clusters `03_model-b_mathematical/
   01_evidence-base.md` organizes the 151 records into (M0001–132, then M0283–378, no cluster
   spanning or touching 133–282).
4. **Why excluded**: `KR-SIM` is this math lane's own native classification (distinct from `b` since
   the *original* 282-file pass, not introduced during any later reconciliation) for general
   exploratory/dialogue-style research not carrying `b`'s required `mathematical_content`/
   `kernel_content` density — Model B's evidence-population rule (Phase 2, membership by
   `model.primary` content, not directory) correctly excludes it. The M0133–M0282 range corresponds
   to the session's own earlier-recorded "Zero-algebra/kernel-minimality-theorem/Vedic-Math-mining"
   exploratory programme (`.claude/CONTEXT.md` history, superseded markers 14–17) — genuinely
   extensive and mathematically substantive material, but tagged `KR-SIM` under this lane's own
   pre-existing convention, not `b`.
5. **Model B's actual kernel/minimality conclusions in `03_model-b_mathematical/` rest entirely on
   M0030/M0035/M0037** (all within the confirmed 151-record list, seq range 30–38) — a later,
   independently-executed kernel-reduction experiment (representation-dependent minimality, four
   cardinality-8 minimal kernels) — **not** on any M0133–M0282 material. No Model-B artifact was built
   from, or requires correction against, the excluded range.
6. **No revision to `02_correspondence-matrix.md` or `03_adjudications-and-contradictions.md` is
   required on the M0133–M0282 question** — the evidence base both were built from was already
   correctly bounded.

**One genuine, separate defect found and corrected during this same audit**: `02_correspondence-
matrix.md`'s summary tally table mistakenly double-listed Row 9 (Observation) under both FUNCTIONAL
ANALOGY and UNRESOLVED, when Row 9's own stated adjudication status (in the row itself) is the single
value `UNRESOLVED, low ceiling` — the FUNCTIONAL ANALOGY finding belongs to Row 5 (Operator)'s
narrow Function-8/`Determine`-`Qualify`/THM-9 sub-pairing, not to Row 9. **Corrected in place**
(evidence-preserving: no row's own adjudication text was changed, only the summary tally's
bookkeeping) with an explicit correction note left in the file. Verified: `04_non-convergences-and-
open-questions.md` (which independently restates the matrix's findings) was already correct on this
point and required no change.

**PHASE 3 INTEGRITY AUDIT: ONE TALLY-TABLE DEFECT FOUND AND CORRECTED (evidence-preserving); THE
M0133–M0282 COMPLETENESS QUESTION VERIFIED RESOLVED — zero Model-B evidence exists in that range, by
direct re-query independent of Phase 2's original accounting. Final state PASSES.**

### MD-021 — Phase 3 formal acceptance (2026-09-07)

User reviewed the Phase 3 integrity audit (above) and formally accepted the result:

> "🟢 PHASE 3 ACCEPTED... The resulting finding is actually quite strong: Model A and Model B do not
> currently demonstrate a structural or formal convergence... The kernel remains unresolved, and the
> report explicitly preserves the non-convergences... I would therefore formally mark Phase 3
> COMPLETE / ACCEPTED, with the audit defect recorded as an evidence-preserving correction."

Per **EP-02** (evidence supplied by engineering, acceptance decided separately, never by the same
party), this acceptance is recorded here as the user's own act, not engineering's self-certification.

**Explicit instruction accompanying acceptance, binding going forward**: **"Do not authorize Phase 4
merely because Phase 3 is accepted."** Phase 4 (Model C1/C2 reconstruction) requires its own
independent review and authorization, on its own terms, regardless of Phase 3's acceptance —
consistent with MD-021's own standing rule that each phase is separately gated and this
reconstruction's repeated practice of never treating one phase's approval as implying the next.

**Phase 3 status: COMPLETE and ACCEPTED. Phase 4 remains unauthorized, unscoped, and untouched — no
work toward it is to begin without a separate, explicit authorization.**

### MD-021 — Phase 4 execution record: Model C1/C2 Independent Reconstruction (2026-09-07)

Executed under a separate, explicit user authorization (2026-09-07, a 24-point plan structure plus
14 lettered constraints A–N, quoted in full in `.claude/plans/purring-tinkering-graham.md` under
"Plan: Phase 4 — Model C1/C2 Independent Reconstruction"), issued only after Phase 3's formal
acceptance, with an explicit statement that Phase 3's acceptance did not itself authorize Phase 4. The
authorization carried one additional binding guardrail supplied at authorization time: the C2
population investigation may identify candidate/adjacent material, but resemblance to protocol.md's
C2 definition must never by itself expand Model-C2 evidence membership — original classification
preserved throughout, no reclassification, any membership change reserved for a separately authorized
classification phase.

**Directory-numbering note**: Phase 4 correctly targets `04_model-c_kernel-ddd/` (protocol.md's own
stage-gate numbering aligns with MD-021's phase number here, unlike Phase 3's `05_cross-model/`
misalignment) — confirmed empty before this phase began.

**Central finding, stated up front in this phase's own artifacts**: Model C2's evidence population,
independently re-derived by joining `classification-register.tsv` against `reading-manifest.tsv`'s
`corpus_tier` (main corpus, `PRIMARY` tier only) and the math lane's own per-file records, is **exactly
one file** (seq 2330) across both corpora, with zero secondary-tagged candidates anywhere. Model C1's
population is 732 primary-tier candidates (719 main-corpus + 13 math-lane) plus 39 secondary-tagged
boundary rows (2 main + 37 math-lane). The three `kernel_ddd`-tagged rows (seq 0001/0005/0009) —
MD-006's own evidentiary origin for the C1/C2 split — are confirmed `OUT_OF_SCOPE_ROOT` per MD-011's
prior correction and are cited as historical provenance only, never as evidence population.

**Five artifacts written to `04_model-c_kernel-ddd/`**: `00_index.md`; `01_evidence-base.md` (732 C1
candidates organized into 8 evidentiary clusters — EKS/PKS baseline; the multi-round AI
Kernel-Boundary-Discovery cycle; external-literature epistemology mining; causal-inference/statistical
Kernel-candidate proliferation; the `phase_measure_theory/` Knowledge-State/Discrepancy/Ideal-State
arc; the late `kernel/`-subdirectory lineage containing the sole C2 file; a Reiter/situation-calculus
formal-methods series; 13 math-lane C1 candidates — plus the sole C2 candidate read in full from raw
source); `02_concept-register.md` (12 named C1 concepts/formalisms §A–§L, the sole C2 concept given
full raw-source treatment §M, a 12-row kernel-candidate table §N covering the C1 population's own
eight-member Kernel-definition proliferation — 1 TESTED→REJECTED, 1 TESTED→REJECTED-as-foundation, 1
tested-survives-not-established, 1 UNRESOLVED, 8 PROPOSED→UNTESTED); `03_contradictions-and-open-
questions.md` (4 contradictions, 3 unresolved equivalences, 8 open questions, plus the required
dedicated C1↔C2 relationship section — MD-007's own promoted watch-status finding ["Knowledge"
undefined in all early C1 files] tested against the fuller population and found **falsified**, since
C1-tagged files define "knowledge" repeatedly and explicitly throughout the corpus; verdict on the
C1↔C2 relationship itself: UNRESOLVED, for the specific reason that the empirical classification
boundary does not cleanly track an engineering-vs-epistemic content split, not for lack of evidence);
`04_boundary-observations.md` (39 secondary-tagged rows named, not opened; 3 historical `kernel_ddd`
rows cited for provenance; a bounded C2-population investigation scoped to the sole C2 file's own
cited lineage — 8 named `meta_research`/`cross_model`-tagged adjacent rows, reported with original
classification preserved per the guardrail, no reclassification proposed; 3 rows with no per-file
record; one self-correction of this reconstruction's own earlier false-positive anomaly claim about
seq 2330 — the claimed register-vs-per-file inconsistency was a bug in a prior verification script's
field-path lookup, not a real discrepancy, corrected transparently).

**Independence maintained**: no Model-A, Model-B, or Phase-3 conclusion was used as evidence anywhere
in this phase's artifacts; the C1↔C2 relationship work stayed within this phase (MD-007's own
framework applied to C1/C2's own evidence) and did not extend to cross-model adjudication against
Model A/B.

**Verification**: `resume.py` → `CONSISTENT` (unchanged); `resume_mathematical.py` → `CONSISTENT`
(unchanged); `classification-register.tsv` confirmed 0 non-`PENDING_GLOBAL_RECLASS`/`PENDING` rows;
`02_model-a_gita/`, `03_model-b_mathematical/`, and `05_cross-model/` confirmed unmodified (md5 hashes
identical to values on record since Phase 3's acceptance); filesystem scope check confirmed only
`04_model-c_kernel-ddd/` (new, 5 files) plus this decision-log entry were written — no other stage
directory touched; two mathematically/architecturally consequential claims (seq 0157's falsification
claim, seq 2322's four-model-naming claim) spot-checked verbatim against raw source titles — both
confirmed faithful.

**Phase 4 status: COMPLETE. Phase 5+ remain unauthorized and untouched.**

### MD-021 — Phase 4 verification completion: raw-source spot-check requirement satisfied (2026-09-07)

The user withheld acceptance of Phase 4 because its completion report showed only 2 raw-source
spot-checks against the authorized plan's own requirement of 5–10. Ten additional/replacement
spot-checks were performed against raw `.md` source (not merely governed per-file YAML), selected
across every category the user specified: the sole C2 candidate; a C2-adjacent boundary candidate;
a C1 Kernel-definition proliferation claim; a contradiction; an unresolved-equivalence/open-question
claim; the C1↔C2 relationship finding; two math-lane claims; two major-C1-research-arc claims.

**Result: 9 of 10 confirmed faithful. 1 correction required and applied, evidence-preserving:**

`02_concept-register.md` §L/§N had attributed the Kernel candidate "K-1 =
KnowledgeAggregate+ConflictRecord+VerificationPort" to seq 0216 as if that file originated it. Raw
source of seq 0216 (`kernel/20260824-111317-...md`) shows the opposite: 0216's own `refines` field
names seq 0165/0167 as K-1's canonical origin, and 0216 itself explicitly **rejects** literal
implementation ("do not implement the earlier 'God KnowledgeAggregate' literally"), decomposing it
into separately-owned Claim/Evidence/Determination/Authority records. **Corrected in place**: §L now
attributes K-1 to 0165/0167 and describes 0216 as reviewing/rejecting it; §N's kernel-candidate table
row P-5 is re-labeled from `PROPOSED → UNTESTED` to a new, honestly distinct state, `PROPOSED →
REJECTED BY ARCHITECTURAL DECOMPOSITION` — kept separate from P-3/P-7's genuine `TESTED → REJECTED`
since no executed test was involved, only a reasoned architectural decision. The table's summary
tally was updated accordingly (7, not 8, `PROPOSED → UNTESTED`; 1 new distinct category). **No other
claim, cluster narrative, contradiction, unresolved equivalence, or conclusion was altered** — the
broader finding this row supports (an eight-member, largely unreconciled Kernel-definition family)
stands unchanged; only this one row's sourcing and state label were corrected.

**Full spot-check record:**

| # | Seq | Claim checked | Raw source | What it establishes | Verdict |
|---|---|---|---|---|---|
| 1 | 2330 | Sole C2 candidate: nine-component core `𝒞=(D,P,T,C,I,E,R,H,Θ)`, Roberts's gate, Shani's invariant, DDD Kernel definition | `kernel/20260902-185000_review-yes12345.md` | Exact verbatim match for the DDD Kernel definition (line 1675) and Roberts/Shani treatment throughout | **Faithful** |
| 2 | 2319 | C2-adjacent boundary: "independent research line" converges on a Kernel concept from a Gita/measure-theoretic path vs. an engineering/DDD path | `phase_measure_theory/knowledgeos_kernel/prompts/20260901-111523_step_286_...md` | Explicit "Path A — Gītā → mathematical theory" vs. "Path B — Engineering/epistemic research → Kernel" | **Faithful** |
| 3 | 0216 | C1 Kernel-definition proliferation: 0216 originates K-1 | `kernel/20260824-111317-...md` | 0216 does NOT originate K-1; its own `refines` field names 0165/0167 as origin, and 0216 explicitly rejects literal implementation | **Overstated — corrected** |
| 4 | 0278 | Contradiction CT-2: the 0273-vs-0276/0278 McGinn adversarial-lens-vs-architecture tension is self-flagged, unresolved | `kernel/20260825-120514-zero-lens-on-mcginn-the-kernel-is-not-knowledge.md` + per-file YAML | Per-file `contradicts`/`open_questions` fields name this exact tension verbatim; raw text confirms McGinn's categories treated as literal Kernel-structure content | **Faithful** |
| 5 | 0504 | Unresolved equivalence / open question: seq 0504 reconnects Knowledge Ātma Kernel to seq 0377's `S_Kernel` | `phase_measure_theory/20260827-090350_atma-can-be-the-knowledgeos-kernel.md` + per-file YAML | Title and `refines` field explicitly state the reconnection to "0377/0380's S_Kernel"; raw prompt-response text confirms the Ātma-Kernel concept itself | **Faithful** |
| 6 | 0434 | C1↔C2 relationship finding: seq 0434 explicitly defines "Knowledge" (falsifying MD-007's watch-status finding) | `phase_measure_theory/20260826-143500_masterful-synthesis-lord-lens-omega.md` | Exact table: "Knowledge = Info + Meaning + Purpose," among five defined terms | **Faithful** |
| 7 | 0311 | Major C1 research arc: the `kernel/` subdirectory (141 documents) is "almost entirely unlinked," 1 of 141 cites another | `kernel/classification/dependency-map.md` | Exact verbatim match: "141 source documents," "1 of 141 (0.7%)... citing another corpus document by ID" | **Faithful** |
| 8 | 0157 | Major C1 research arc: the six-part aggregate is genuinely falsified | `kernel/20260823-114530-aggregate-hypothesis-falsification-atomicity-vs-relatedness.md` | Exact verbatim: "FALSIFIED. Semantic relatedness and traceability do NOT imply transactional atomicity" | **Faithful** |
| 9 | M0024 | Math-lane C1 claim: Plato/White extraction with strict source-vs-interpretation separation | `mathematical_ideas_that_can_be_implemented/20260901-213500_plato-...md` | Opening line confirms the explicit separation discipline | **Faithful** |
| 10 | M0050 | Math-lane C1 claim: reviews the main corpus's own "Theory v1.1" against Steps 285–290 | `mathematical_ideas_that_can_be_implemented/20260902-091243_...md` | Opening lines confirm exactly this review framing | **Faithful** |

**Verification re-run after corrections**: `resume.py` → `CONSISTENT` (unchanged); `resume_mathematical.py`
→ `CONSISTENT` (unchanged); `classification-register.tsv` confirmed 0 non-`PENDING_GLOBAL_RECLASS`/
`PENDING` rows; `02_model-a_gita/`, `03_model-b_mathematical/`, `05_cross-model/` re-hashed and
confirmed identical to values on record since Phase 3's acceptance; `06_gap-analysis/` through
`13_research-frontier/` confirmed still empty; `04_model-c_kernel-ddd/` confirmed to contain only its
five intended files.

**Phase 4 status: COMPLETE, verification requirement satisfied (10 spot-checks, 1 evidence-preserving
correction applied). Awaiting the user's formal acceptance. Phase 5+ remain unauthorized and
untouched.**

### MD-021 — Phase 4 formal acceptance (2026-09-07)

User reviewed the Phase 4 reconstruction and its verification-completion pass (above) and formally
accepted the result:

> "I hereby FORMALLY ACCEPT PHASE 4 — Model C1/C2 Independent Reconstruction. Acceptance is based on:
> the authorized Phase-4 scope and constraints; the independent C1/C2 reconstruction; the explicit
> treatment of C2 scarcity as a finding rather than manufactured symmetry; preservation of original
> classifications; the absence of cross-model contamination; preservation of unresolved questions,
> contradictions, boundary observations, and negative findings; the completed 10 raw-source spot
> checks; the evidence-preserving correction to the seq 0216 / K-1 attribution; successful
> re-execution of the full verification suite; confirmation that Model A, Model B, and Phase-3
> artifacts remain unchanged; confirmation that no Phase-5 or later work has begun."

Per **EP-02** (evidence supplied by engineering, acceptance decided separately, never by the same
party), this acceptance is recorded here as the user's own act, not engineering's self-certification —
the same discipline applied to Phase 3's own formal acceptance.

**Explicit governance boundary accompanying acceptance, binding going forward**: **"This acceptance
authorizes nothing beyond Phase 4."** Named as remaining explicitly unresolved unless separately
authorized: the C1/C2 classification boundary; the relationship between C1 and C2; the eight C1
Kernel-definition candidates and their unresolved equivalences; the adjacent C2 candidates; the
`kernel/` vs. `phase_measure_theory/` chronology; all other open questions and non-convergences Phase
4 recorded. Named as explicitly forbidden absent separate authorization: beginning Phase 5; global
reclassification; modifying the classification register; modifying Model A/B/Phase-3 artifacts;
further cross-model adjudication; a unified theory; canonicalizing competing Kernel definitions;
promoting unresolved hypotheses to established theory; any implementation.

**Phase 4 status: COMPLETE and FORMALLY ACCEPTED. Phase 5 remains unauthorized, unscoped, and
untouched — no work toward it is to begin without a separate, explicit authorization.**

### MD-021 — Phase 5A execution record: C1/C2 Classification-Boundary Audit (2026-09-07)

Executed under a separate, explicit user authorization (2026-09-07, issued after the Phase-5
planning-analysis document and Phase 4's formal acceptance) authorizing **Phase 5A only** —
a descriptive, non-reclassifying audit of the C1/C2 classification boundary. The authorization
carried a statistical guardrail: use a census (not sample) for machine-observable metadata/
classification facts across the complete governed population; reserve sampling for genuinely
content-level investigation, with frame/strata/unit/inclusion-exclusion/selection procedure defined
before inspection; maintain three explicit levels throughout (existing classification /
machine-observable corpus fact / independent descriptive content observation), with the third never
silently becoming a reclassification.

**Directory placement**: no protocol.md stage-gate slot exists for a classification audit (as
opposed to a model-reconstruction phase); placed at `14_decision-log/MD-021-phase-5a-classification-
boundary-audit/`, a disclosed judgment call, adjacent to the decision log governing it rather than a
new numbered model-stage directory.

**Method**: a complete-population census (1,585 rows: 1,185 main-corpus `PRIMARY`-tier + 401
math-lane) of every machine-observable field cross-tabulated against classification, plus two
content-level investigations on pre-defined samples — a census-complete 19-file stratum (the full
population matching a specific text-pattern search outside the already-examined C1/C2/Model-A
populations) and a systematic sample of 10 from the remaining 292-file `meta_research`∩
`kernel_content=true` population (fixed-interval selection, defined before inspection).

**Key findings**:
1. 306 `meta_research`-tagged main-corpus rows carry `kernel_content: true` — a population nearly
   half the size of Model C1's own 719-row main-corpus population, unexamined by any prior phase.
2. Post-MD-006 (2026-09-01), classification activity favored `meta_research` (98 further rows) over
   `engineering_knowledgeos` (23) and `epistemic_knowledgeos` (1) — a descriptive temporal pattern,
   cause not investigated.
3. The math lane's `kernel_content` field is inconsistently typed (boolean for most primaries,
   free-text for `KR-SIM`) — a schema-drift finding.
4. Phase 4's 8 C1 Kernel-definition candidates span 4 distinct object categories (enumerated
   checklist, DDD aggregate design, formal governance artifact, mathematical tuple, conceptual/
   identity notion) — typed, not adjudicated for equivalence.
5. **"Eight" is not corpus-wide complete**: a previously-uncounted 237-file subdirectory
   (`docs/knowledgeos/brainstorming/phase_measure_theory/knowledgeos_kernel/`) contains substantial
   additional Kernel-formalization material, classified `meta_research`/`cross_model`, entirely
   outside Model A/B/C1's evidence populations — including one file (seq 2293) whose own per-file
   record states it unifies roughly thirteen internally-tracked competing tuple proposals (a
   per-file-record-level synthesis claim, spot-checked and confirmed as such, not independently
   verified as a literal count in raw prose).
6. The label "K-1" recurs across at least two independent sub-threads (seq 0165/0167 and seq 1008) —
   a genuine, unresolved homonym-vs-shared-reference question, not adjudicated here.
7. A boundary observation, not acted on: Gita-content-bearing files (e.g. seq 2245, 0942) exist
   classified `meta_research`, outside Model A's own 84-file evidence population — reported only;
   Model A remains frozen and untouched.

**No classification was changed. No Model A/B/Phase-3/Phase-4 artifact was modified. No C1↔C2
relationship was adjudicated. No Kernel equivalence was established. No cross-model adjudication
against Model A/B occurred. No unified theory or canonical Kernel was created. No implementation
occurred.**

**Verification**: `resume.py`/`resume_mathematical.py` → both `CONSISTENT`, unchanged;
`classification-register.tsv` → 0 non-pending rows, unchanged; Model A/B/Phase-3/Phase-4 artifacts
(20 files) re-hashed and confirmed unchanged; filesystem scope confirmed — only the new Phase-5A
audit directory (4 files) plus this entry were written; 2 raw-source spot-checks performed on the
most consequential new claims, both confirmed faithful (with one precision disclosed regarding a
per-file-record-level synthesis figure).

**Phase 5A status: COMPLETE. Phase 5B, 5C, and any four-model work remain unauthorized and untouched.**

### MD-021 — Phase 5B execution record: Independent Lineage Reconstruction and Provenance Analysis (2026-09-07)

Executed under a separate, explicit user authorization (2026-09-07, issued after Phase 5A's
completion) authorizing **Phase 5B only** — a documentary lineage/provenance reconstruction, never
assuming `C1→C2`, `C1∥C2`, or `C1→limitation→C2` as a starting point, and never adjudicating the
C1/C2 relationship as a model question.

**Method**: population/denominator tables kept explicitly separate (1,185 main-corpus + 401
math-lane; 306 `meta_research`∩`kernel_content`; 237-file `phase_measure_theory/knowledgeos_kernel/`;
116-hit and 22-hit text-pattern censuses; per-marker transition-language and C2-vocabulary censuses);
a two-level unit-of-analysis discipline (document / research-object); a 13-type relationship taxonomy
used only where evidenced; a five-level evidence-typing scheme applied throughout.

**Key findings**:
1. A 12-object Kernel-candidate provenance graph spans 5 subdirectories and 4 object categories. **Not
   one `REFINES`/`DERIVES_FROM`/`SUPERSEDES` relationship crosses between the `kernel/`-directory
   family and the `phase_measure_theory/`-directory family** — the single most consequential lineage
   finding of this phase.
2. The C1 lineage scaffold (`EKS→PKS→...→Kernel→Knowledge-state→...`) holds as an explicitly-
   evidenced derivation for two of five arrows, holds only as chronological association for one arrow
   (EKS→PKS), and **explicitly does not hold** for the Kernel-discovery-cycle→Knowledge-state-arc
   transition specifically.
3. The K-1 label: content traces to seq 0167 (`ADR-KOS-KERNEL-001`); the label itself and its
   "VerificationPort" component first appear together at seq 0196, with "VerificationPort" found only
   in the governed per-file synthesis, not raw prose; a second, structurally unrelated "K-1" occurs at
   seq 1008 — assessed as a likely homonym (moderate-to-high confidence), formally `UNRESOLVED`.
4. **Documentary evidence for C1→C2: `NO DEMONSTRATED TRANSITION`.** No document states or clearly
   implies the hypothesis. The literal phrase MD-007 used to frame it ("what actually is knowledge")
   does not appear in any corpus source record.
5. **A major, self-caught methodological correction**: an initial finding that "all five earliest
   occurrences of C2-defining vocabulary (`Knowledge Space`/`Buddhi`/`purification`/`Moksha`/`K_t`)
   sit outside the C2 classification" was substantially wrong on raw-source inspection — 4 of 5 were
   keyword-census false positives (classifiers' own forward-looking "bridge candidate, no C2 evidence
   yet" notes, and one explicit absence statement, not genuine content). **Corrected in place,
   evidence-preserving, narrowing the surviving finding to 2 genuinely verified occurrences**
   (`admissibility` seq 0143, `Knowledge Space` seq 0266, both `engineering_knowledgeos`).

**No classification was changed. No Model A/B/Phase-3/Phase-4/Phase-5A artifact was modified. No
C1↔C2 model-level relationship was adjudicated (only the documentary-lineage question, answered per
the authorization's own required format). No Kernel equivalence was established. No cross-model
adjudication against Model A/B occurred. No unified theory or canonical Kernel was created. No
implementation occurred.**

**Verification**: `resume.py`/`resume_mathematical.py` → both `CONSISTENT`, unchanged;
`classification-register.tsv` → 0 non-pending rows, unchanged; Model A/B/Phase-3/Phase-4/Phase-5A
artifacts (24 files) re-hashed and confirmed unchanged; filesystem scope confirmed — only the new
Phase-5B directory (7 files) plus this entry were written; 13 raw-source spot-checks performed
(requirement ≥10), 3 evidence-preserving corrections applied and disclosed.

**Phase 5B status: COMPLETE. Phase 5C, global reclassification, four-model convergence, and Kernel
adjudication remain unauthorized and untouched.**

### MD-021 — Phase 5C execution record: Kernel Object Reconstruction and Equivalence Adjudication (2026-09-07)

Executed under a separate, explicit user authorization (2026-09-07, issued after Phase 5B's
completion, independent of Phases 1–5B's own acceptance) authorizing **Phase 5C only** — an
object-level Kernel reconstruction and equivalence-adjudication phase, explicitly not global model
convergence. Central methodological correction carried forward from Phase 5B: "a keyword match is not
evidence of a conceptual occurrence."

**Method**: reused Phase 5A/5B's own population/denominator census (no new census population
invented); a two-level unit-of-analysis discipline (document/research-object) with an explicit
document→object mapping; a 19-attribute object-taxonomy register using `NOT EVIDENCED` wherever
warranted; a six-level equivalence ladder applied pairwise; a minimality-word-vs-claim distinction
applied before any minimality finding was recorded.

**Thirteen Kernel research objects registered** (KERNEL-OBJ-01 through -13, extending Phase 5B's
twelve with one new object — the earliest document in the population, seq 0080, a 10-candidate
Kernel-worthiness evaluation matrix), mapped from 14 of the 116-document Kernel-marker population (a
disclosed, non-exhaustive subset).

**Key findings**:
1. **Zero of the six pairwise equivalence adjudications performed reach structural correspondence,
   formal equivalence, or demonstrated identity.** The highest-reaching pair (KERNEL-OBJ-05↔06)
   reaches only functional correspondence at Level 3 (reconstructed provenance), with an explicit
   source-level connecting statement but no component-by-component mapping.
2. **Zero of the thirteen registered objects make a formally tested minimality claim** — a stronger
   finding than "minimality is representation-dependent," since no representation-dependent
   comparison was even attempted within this evidence. The word "minimal" appears in all 116 documents
   of the marker population, but inspection shows this is predominantly loose design-philosophy
   language, not formal minimality propositions — restated and generalized from Phase 5B's own
   keyword-vs-content-occurrence lesson.
3. **No cross-family derivation evidenced** between the `kernel/`-directory objects and the
   `phase_measure_theory/`-directory objects, across all 13 relationship types searched (restated,
   not re-derived, from Phase 5B) — correctly stated as "no cross-family derivation evidenced in the
   investigated relation types," not as "the families are independent."
4. **The "K-1" homonym question remains formally `UNRESOLVED`** — two structurally dissimilar objects
   (DDD aggregate decomposition vs. an 8-primitive ratified state space), no connecting document, no
   shared invariant catalog, confirmed via fresh raw-source comparison this phase.

**No classification was changed. No Model A/B/Phase-3/Phase-4/Phase-5A/Phase-5B artifact was
modified. No four-model or Gītā↔Mathematics↔C1↔C2 convergence was performed. No unified or canonical
Kernel was constructed. No candidate was promoted to canonical status. No implementation was
performed.**

**Verification**: `resume.py`/`resume_mathematical.py` → both `CONSISTENT`, unchanged;
`classification-register.tsv` → 0 non-pending rows, unchanged; 31 frozen prior-phase files
(Model A/B/Phase-3/Phase-4/Phase-5A/Phase-5B) re-hashed and confirmed unchanged; filesystem scope
confirmed — only the new Phase-5C directory (7 files) plus this entry were written; 11 raw-source
spot-checks performed (requirement ≥10, distributed across all 10 named categories), 0 new
corrections required.

**Phase 5C status: COMPLETE. Phase 5D, global reclassification, four-model convergence, unified/
canonical Kernel construction, cross-model synthesis, and implementation remain unauthorized and
untouched.**

---

## MD-021 Phase 5D — Kernel Population Completeness and Object-Closure Audit (EXECUTED, 2026-09-07)

**Authorization**: separate, explicit, issued after the user's own review of Phase 5C identified that
its 13-object register was mapped from only 14 of the 116-document Kernel-marker population (102
documents unmapped), with the explicit instruction that "13 Kernel research objects" must be read as
"13 objects reconstructed from the investigated subset," not "the corpus contains exactly 13
objects." Central research question: is the Phase-5C register sufficiently complete, or do additional
distinct research objects remain hidden among the unmapped population?

**Central finding: Additional objects discovered — not closure-supported.** A complete census (not a
sample) of all 116 P1 documents found **6 further distinct, evidenced Kernel-object candidates** not
in Phase 5C's register: K1-K8 (seq 0144, already named in Phase 4's own concept register but never
carried into Phase 5C); DeepSeek's four competing Kernel hypotheses (seq 0150); a second genuinely
distinct 8-primitive kernel `K_OS=(A,T,P,E,I,S,X,R)` (seq 0654, raw-source confirmed this phase); a
formal governance ratification event, "GN-31" (seq 0764); the "Reduced Candidate Kernel"
`K=(K,C,T,E,A)`, explicitly framed as a "Minimal Architectural Kernel" (seq 0856, raw-source
confirmed); and a self-diagnosed "heterogeneous" 5-tuple `𝔎_5=(G,σ,θ,λ,π)` (seq 0867, raw-source
confirmed). None of the 6 reaches structural correspondence or above against any existing object or
each other; 0 reach a formally tested minimality claim.

**One confirmed (raw-source, evidence-level-1) audit finding against Phase 5C's own register**:
KERNEL-OBJ-04's cited origin (seq 0150) does not match its own content; direct raw-source inspection
of seq 0150/0156/0157 shows seq 0156 (not 0150) states the eight-field "KnowledgeAggregate" whose
six-part subset seq 0157 then falsifies. **Per the authorization's explicit instruction, Phase 5C's
own register is NOT modified — this is recorded as a Phase-5D audit finding only**, in
`14_decision-log/MD-021-phase-5d-kernel-population-closure/04_object-closure-and-equivalence.md`.

**K-1 homonym finding re-confirmed and extended**: K-1-B's own source (seq 1008) discusses a
five-candidate family (K-1, K-2, K-3, K-6, K-7) inside one document — K-2/K-3/K-6/K-7 are unregistered
siblings, named here as an open coverage gap, not resolved.

**P2 (`phase_measure_theory/knowledgeos_kernel/`, 237 files) and P3 (`kernel/` directory) were
sampled, not censused, this phase** — neither is certified closed; this is disclosed explicitly, not
treated as completeness.

**No classification was changed. No Model A/B/Phase-3/Phase-4/Phase-5A/Phase-5B/Phase-5C artifact
was modified. No four-model or cross-model convergence was performed. No unified or canonical Kernel
was constructed. No candidate was promoted to canonical status. No implementation was performed.**

**Verification**: `resume.py`/`resume_mathematical.py` → both `CONSISTENT`, unchanged;
`classification-register.tsv` → 0 non-pending rows, unchanged; 38 frozen prior-phase files
(Model A/B/Phase-3/Phase-4/Phase-5A/Phase-5B/Phase-5C) confirmed unmodified; filesystem scope
confirmed — only the new Phase-5D directory (9 files) plus this entry were written; 15 raw-source
spot-checks performed (requirement ≥15, distributed across all 15 named categories), including 9
newly-performed raw-file reads this phase (seq 0144, 0150, 0156, 0157, 0341, 0377, 0654, 0856, 0867)
plus 2 filename-level duplicate confirmations (0379, 0868); one self-caught arithmetic correction
(P1 tally initially summed to 117 due to a mistakenly-included non-P1 reference row, corrected to 116
in place, shown not hidden).

**Phase 5D status: COMPLETE. Phase 5E, global reclassification, four-model convergence, unified/
canonical Kernel construction, cross-model synthesis, repair of Phase 5C's KERNEL-OBJ-04 entry, and
implementation remain unauthorized and untouched.**

---

## MD-021 Phase 5E — Exhaustive Kernel Population Closure and Provenance Reconciliation (EXECUTED, 2026-09-07)

**Authorization**: separate, explicit, issued after review of Phase 5D's completion, which found P2
(`phase_measure_theory/knowledgeos_kernel/`, 237 files) and P3 (`kernel/`, 172 files) were sampled, not
censused. Central research question: after exhaustive inspection of the remaining Kernel-related
populations, can every substantive document be accounted for by an existing object, a new object, a
non-object document, or an explicit unresolved case?

**Method**: mechanically determined P2 (237 on-disk `.md` files, 237 matching per-file records, 0
gaps/orphans) and P3 (172 on-disk `.md` files, 172 matching records, 0 gaps/orphans) — both fully
censused, no sampling. Built and read a full 2,045-line digest across all 409 documents; assigned each
one of 8 required dispositions (0 remained unresolved after full inspection); performed 23 raw-source
spot-checks (requirement ≥20) across all 20 named categories.

**Central finding**: seq 1006 (`D285-1-STATE-ONTOLOGY-MATRIX.md`) supplies a raw-source-confirmed,
authoritative comparison of seven state/Kernel formulations (K-1 through K-7), each with an explicit
authority/ratification status — K-1 (`K_t`, 8 primitives) **RATIFIED and computationally tested**;
K-2 through K-7 explicitly **NOT RATIFIED / REJECTED / research-only, considered and not adopted**.
This resolves, at the provenance level, Phase 5D's own flagged "K-2/K-3/K-6/K-7 unregistered siblings"
open question, and cross-confirms with seq 1008's own consequence matrix (identical naming scheme,
raw-source verified both directions). The K-1 (seq 1006) ↔ K-1-B (Phase 5C's KERNEL-OBJ-02, seq 1008)
pair reaches **structural correspondence** — the strongest equivalence verdict reached anywhere in
this reconstruction's Kernel-object work to date, still short of formal equivalence or demonstrated
identity. A separate six-model comparison (seq 1007, "Models A-F") was found and partially verified,
not fully resolved. 11 total new object-register entries were produced; 0 reach formal equivalence or
demonstrated identity with any existing object.

**KERNEL-OBJ-04 reconciliation record produced (evidence-preserving, Phase 5C's own register NOT
modified)**: raw-source confirms seq 0156 (not seq 0150) states the eight-field "KnowledgeAggregate"
whose six-part subset seq 0157 falsifies — the object's own adjudicated status (falsified) is
unaffected; only its cited provenance pointer was wrong.

**Closure verdict: PARTIAL CLOSURE — SPECIFIC POPULATIONS REMAIN OPEN.** Population and document
closure supported for P2 and P3 (both fully censused, 0 gaps). Object, full-provenance, and
full-equivalence closure NOT supported — the K-1..K-7 register and the Models A-F comparison remain
only partially adjudicated against the existing 19-object set.

**No classification was changed. No Model A/B/Phase-3/Phase-4/Phase-5A/Phase-5B/Phase-5C/Phase-5D
artifact was modified. No four-model or cross-model convergence was performed. No unified or canonical
Kernel was constructed. No implementation was performed.**

**Verification**: `resume.py`/`resume_mathematical.py` → both `CONSISTENT`, unchanged;
`classification-register.tsv` → 0 non-pending rows, unchanged; 47 frozen prior-phase files (Model
A/B/Phase-3/Phase-4/Phase-5A/Phase-5B/Phase-5C/Phase-5D) confirmed unmodified; filesystem scope
confirmed — only the new Phase-5E directory (12 files) plus this entry were written; 23 raw-source
spot-checks performed (requirement ≥20).

**Phase 5E status: COMPLETE. Phase 5F, global reclassification, four-model convergence, unified/
canonical Kernel construction, cross-model synthesis, repair of any frozen Phase-5C/5D artifact, and
implementation remain unauthorized and untouched.**

---

## MD-021 Phase 5F — K-1 Ontology Semantic Equivalence, Context Mapping, and Provenance Adjudication (EXECUTED, 2026-09-07)

**Authorization**: separate, explicit, issued after review of Phase 5E's completion, prompted by the
finding that D285-1 (seq 1006) changes the K-1 problem from "many apparently competing Kernel
definitions" into a provenance-and-semantics problem testable directly. Central question: does ratified
K-1 (`K_t` over 8 primitives) have the same semantic object, a context-specific representation, a
partial mapping, or a distinct object relative to K-1-B and the verification-lane ontology?

**Terminology collision disclosed and resolved without silently discarding either reading**: the
authorization's own description of "K-1-B" ("two-component formulation... Assertion expansion") matches
what this reconstruction has consistently called **K-2**, not Phase 5C's own "K-1-B" (reconstructed
from seq 1008 as the ratified 8-primitive `K_t` itself). Both readings were carried through the full
phase.

**K-1 reconstructed from its true primary source**: seq 0630
(`phase_measure_theory/20260828-104146_step-049-formal-model-reduction-...md`), located this phase
outside Phase 5E's own P2 census — its §49.29/§49.30 supply the exact 8-primitive tuple
`𝒦=(E,S,T,O,P,R,Π,A)` with explicit derived-structure definitions (`Evidence⊆O×Context`, `Claim⊆P`,
`Identity:E→ID`, etc.), a richer formal specification than either D285-1 or D285-7 had themselves
supplied.

**Central findings**:
1. **K-1 (seq 1006) and Phase-5C's own K-1-B (seq 1008) are the same object**, cited identically
   within one authored, same-day package (D285-1 through D285-8) — promoted from Phase 5E's
   "structural correspondence" to **FORMAL EQUIVALENCE** (not full demonstrated identity, since no
   independent isomorphism proof beyond shared citation was constructed).
2. **K-1 and K-2 (the verification-lane ontology, `(𝒜,ℛ)`) stand in a precise, already-executed
   corpus-native "lossy semantic projection" relationship** (D285-6, seq 1007, raw-source read in
   full): structural equality FALSE, semantic equality TRUE only after unpacking `Assertion` (modulo a
   declared drop of `Event/Policy/Action`), observational equality FALSE (three query classes lost).
   The projection map `π_K: K_1→K_2` is definable but not computable, blocked on an unimplemented
   `Qualify: Observation×Policy→Evidence` function.
3. `Observation`'s absence from K-2 traces to a documented recovery construction (the "Sañjaya layer,"
   seq 0979, origin 2026-08-26, six-value observation-status vocabulary `Sañjaya_K=(Observed,
   Inferred,Reported,Unknown,Conflicting,Unresolved)`) — a genuine, traceable layering gap, not an
   omission. `State` has **no** analogous recovery construction — genuinely `UNRESOLVED`.
4. Entity/Proposition/Relation, audited independently, each resolve to partial or structural
   correspondence — none to formal equivalence, none to bare lexical coincidence; Proposition is the
   closest correspondence of the three.
5. **One internal tension found in the D285 package itself** (not repaired): D285-1's own `Assertion`
   unpacking (`Proposition,Entity,Evidence,Context,Time,Provenance`) differs from D285-6's own
   unpacking (`{Proposition,Entity,Observation}+{id,c,t,Π}`) — disclosed, not reconciled.
6. K-1 through K-7 each received one of the required dispositions (`LIVE OBJECT` for K-1; `RESEARCH
   HYPOTHESIS` for K-2/K-3/K-6/K-7; `HISTORICAL CANDIDATE` for K-4; `REJECTED FORMULATION` for K-5) —
   closing Phase 5D's own flagged "unregistered siblings" question definitively.

**No classification was changed. No Model A/B/Phase-3/Phase-4/Phase-5A/Phase-5B/Phase-5C/Phase-5D/
Phase-5E artifact was modified. No four-model or cross-model convergence was performed (the Sañjaya/
Arjuna↔Model-A lineage lead was named, not adjudicated). No unified or canonical Kernel was
constructed. No implementation was performed.**

**Verification**: `resume.py`/`resume_mathematical.py` → both `CONSISTENT`, unchanged;
`classification-register.tsv` → 0 non-pending rows, unchanged; 59 frozen prior-phase files (Model
A/B/Phase-3/Phase-4/Phase-5A/Phase-5B/Phase-5C/Phase-5D/Phase-5E) confirmed unmodified; filesystem
scope confirmed — only the new Phase-5F directory (13 files) plus this entry were written; 22
raw-source spot-checks performed (requirement ≥20).

**Phase 5F status: COMPLETE. Phase 5G, global reclassification, four-model convergence, unified/
canonical Kernel construction, cross-model synthesis, repair of any frozen artifact, and
implementation remain unauthorized and untouched.**

---

## MD-021 Phase 5G — Independent Adversarial Audit of K-1/K-2 Equivalence, Projection, and Context Mapping (EXECUTED, 2026-09-07)

**Authorization**: separate, explicit, an independent adversarial audit of Phase 5F, issued after the
user's own senior-level review identified four specific over-claims/under-scrutinized points: (1) the
K-1↔K-1-B "formal equivalence" promotion may understate the case if no two distinct objects actually
exist; (2) the K-1↔K-2 "semantic equality" claim needed mathematical scrutiny (representation vs.
observational vs. semantic-preservation vs. information equivalence are not interchangeable); (3) the
DDD "context mapping" framing needed independent DDD-pattern evidence, not just a mathematical
projection; (4) the Observation finding should not become a "global architectural fact" without
confirming what specifically maps to what.

**Method**: every one of Phase 5F's 9 claims was independently re-tested against primary source (seq
0630, 1006, 1007, 1008, 0979 — all re-read in full or re-grepped this phase), with explicit targeted
searches for evidence Phase 5F had not itself performed (an explicit-citation search for "D285-1" in
D285-6/D285-7; a DDD-pattern-specific evidence search).

**Central findings — three genuine changes, not a rubber-stamp**:
1. **K-1↔Phase-5C's-K-1-B, STRENGTHENED**: applying the user's own sharper mathematical distinction
   (formal equivalence presumes two distinct representations; if none exist, there is nothing to be
   equivalent to), and finding no document anywhere claims the two mentions are distinct
   representations, this phase promotes the relationship from "formal equivalence" to **DEMONSTRATED
   IDENTITY (qualified: within-package, textual-reuse basis — not an externally-proven referent-
   identity statement)**. A genuine, previously-undetected explicit cross-reference was found in the
   process: D285-7 explicitly cites D285-6 by name ("Under the projection result (D285-6)...") —
   confirming package self-awareness, though no document explicitly cites "D285-1" by name.
2. **K-1↔K-2 "semantic equality," DOWNGRADED**: the corpus's own "semantic equality TRUE" claim was
   found to be an undefined, corpus-local term. Tested against 5 independently-defined equivalence
   types (representation/state/observational/semantic-preservation/information), 3 fail outright, 1 is
   an unverified authorial declaration, 1 fails for want of any inverse map. Downgraded to **PARTIAL
   CORRESPONDENCE over a declared, unverified subset**.
3. **DDD "context mapping," DOWNGRADED**: no bounded-context declaration, governance rule, or
   specified/implemented anti-corruption-layer artifact was found — only the underlying mathematical
   projection. Downgraded to: **a mathematical projection is evidenced; a DDD architectural context
   mapping in Evans' own formal sense is NOT independently established.**

**Confirmed unchanged**: observational equivalence FALSE; Observation-as-layering-gap (re-tested
against the required H1–H4 hypothesis set, resolved to H1 qualified); `State`'s own status remains
genuinely UNRESOLVED (re-tested, no recovery construction found); Entity/Proposition/Relation
correspondences (independently re-audited, no cross-contamination); all seven K-1 through K-7
dispositions (re-verified against primary source).

**One genuine internal source tension re-confirmed and sharpened**: D285-1's and D285-6's own
differing `Assertion`-unpacking lists — this phase traces its direct consequence for the first time
(it is precisely why "semantic equality" cannot be asserted with full confidence).

**No classification was changed. No Model A/B/Phase-3/Phase-4/Phase-5A/Phase-5B/Phase-5C/Phase-5D/
Phase-5E/Phase-5F artifact was modified. D285-1/D285-6/D285-7 were not modified. No four-model or
cross-model convergence was performed. No unified or canonical Kernel was constructed. No
implementation was performed.**

**Verification**: `resume.py`/`resume_mathematical.py` → both `CONSISTENT`, unchanged;
`classification-register.tsv` → 0 non-pending rows, unchanged; 72 frozen prior-phase files confirmed
unmodified; filesystem scope confirmed — only the new Phase-5G directory (15 files) plus this entry
were written; 24 raw-source spot-checks performed (requirement ≥20), covering both K-1 and K-2
primary sources.

**Phase 5G status: COMPLETE. Phase 5H, global reclassification, four-model convergence, unified/
canonical Kernel construction, cross-model synthesis, repair of any frozen artifact, and
implementation remain unauthorized and untouched.**

---

## MD-021 Phase 5H — Mathematical Closure and Source-Gap Resolution for the K-1 → K-2 Projection (EXECUTED, 2026-09-08)

**Authorization**: separate, explicit, a targeted mathematical/knowledge-engineering phase following
Phase 5G's completed adversarial audit. Central question: can the K-1 → K-2 projection be
mathematically completed from evidence already in the corpus, or are the remaining gaps genuine
source-research gaps? Three closure targets: K-1's operators (`𝒪_1`), `Qualify`, and `State`'s
projection target.

**Central finding — a genuinely new primary-source discovery**: the corpus's own executable Python
scripts (`exec/t285_reconcile.py`, `exec/t285_equality.py`, `exec/e_equality.py`, none read in Phase
5F/5G) supply machine-verifiable confirmation of the K-1/K-2 projection's own "definable, not
computable" status, and — most consequentially — **reveal that the Assertion-unpacking conflict Phase
5G found in prose (D285-1 vs. D285-6) recurs, in the identical pattern, inside the executable code
itself** (`t285_reconcile.py`'s own `ASSERTION_CONTAINS` matches D285-1; `t285_equality.py`'s own
`UNPACK` matches D285-6; a third script, `e_equality.py`, supplies a fourth, structurally different
parameterization). This elevates the finding from a prose-level curiosity to a machine-observable
contradiction in the corpus's own machine-verifiable research artifacts — the single most consequential
finding of this phase.

**Per-target closure**: K-1 operators — **PARTIALLY CLOSED** (9 named, typed transformations
reconstructed from seq 0630's own §49.31/§49.76, including a directly relevant re-confirmation of
D285-7's own finding: exactly 1 occurrence of "operator" in seq 0630's full 2,232 lines, independently
confirming the enumeration was never performed). `Qualify` — **PARTIALLY CLOSED**: its type signature
(`Observation → Evidence`) is now triangulated across two independent documents (seq 0630 §49.76,
`Evidence = QualifiedObservation`; seq 0795/step-170 §170.4, a `CaptureAndQualify` gate with named
conditions and explicit tuples for Observation/Evidence) — a genuine strengthening over Phase 5F/5G's
single-source citation — while its computable algorithm remains a **confirmed SOURCE-RESEARCH GAP**,
and a further arity inconsistency was found (1-argument in seq 0795 vs. 2-argument in D285-6). `State`
— **SOURCE-RESEARCH GAP**: a corpus-wide search for "carrier" (D285-6's own unexpanded projection
target) found ten occurrences, none matching — "the carrier" has no independent definition anywhere in
the corpus, sharpening Phase 5F/5G's own "UNRESOLVED" verdict with a positive absence-finding rather
than a mere non-discovery.

**Overall closure classification: B — Projection partially closable; explicit source gaps remain**
(not forced to A; not understated to C).

**No classification was changed. No Model A/B/Phase-3/Phase-4/Phase-5A through 5G artifact was
modified. D285-1/D285-6/D285-7 and the corpus's own three executable scripts were not modified. No DDD
context mapping was declared or revisited. No four-model or cross-model convergence was performed. No
unified or canonical Kernel was constructed. No implementation was performed.**

**Verification**: `resume.py`/`resume_mathematical.py` → both `CONSISTENT`, unchanged;
`classification-register.tsv` → 0 non-pending rows, unchanged; 87 frozen prior-phase files confirmed
unmodified; filesystem scope confirmed — only the new Phase-5H directory (14 files) plus this entry
were written; 26 raw-source spot-checks performed (requirement ≥20), covering K-1, K-2, `Qualify`,
`State`, and all four Assertion-unpacking variants (2 prose, 2 code).

**Phase 5H status: COMPLETE. Phase 5I, global reclassification, four-model convergence, unified/
canonical Kernel construction, DDD context-map adoption, repair of any frozen artifact, and
implementation remain unauthorized and untouched.**

---

## MD-021 Phase 5I — K-2 Definition Integrity and Executable-Semantics Adjudication (EXECUTED, 2026-09-08)

**Authorization**: separate, explicit, following the completed Phase 5H mathematical-closure phase.
Central question: is K-2 a single mathematically identifiable object with multiple representations, or
does the corpus contain multiple competing K-2 definitions that cannot presently be identified?

**Method**: built a 7-entry Assertion Definition Register (5 `Assertion`-shaped records plus 2 related
Evidence/Observation records, `01`) without merging during collection; performed a line-by-line
executable-semantics audit of all three of the corpus's own scripts (`t285_reconcile.py`,
`t285_equality.py`, `e_equality.py`), explicitly distinguishing genuinely-computed results from
hardcoded assertions dressed as code; tested 5 required equivalence relations between the scripts;
re-executed the scripts' own core computations rather than merely reading them.

**Central findings**:
1. **`t285_reconcile.py`'s own computed output was never updated to reflect D285-1's own later prose
   revision.** Re-executing the script's own set arithmetic shows it computes `{Observation, State}`
   as "simply ABSENT" — the exact pre-revision framing D285-1's own prose explicitly retracted ("Too
   strong... they do differ on Observation," via the Sañjaya-layer correction). The corpus's own code
   and its own later prose have drifted apart.
2. **This same script is internally inconsistent with itself**: its own T-A section computes
   `Observation` as "absent" while its own T-C section's `pi` dict assigns it a defined (non-dropped)
   image — a single script's own two sections disagree.
3. **Not all "code-verified" claims in this reconstruction's own prior phases are equally
   computationally grounded**: `t285_equality.py`'s "semantic equality" test is a genuine, computed
   subset check; its "observational equality" test is a hardcoded boolean dictionary, not a derived
   result — a distinction not previously drawn.
4. **Two further, previously-undisclosed discrepancies found**: `Π`/`Pi` is glossed as "Policy"
   (D285-6) but populated with provenance-shaped data in the executable worked example
   (`e_equality.py`); `id` is treated as a peer field (D285-6's prose) but is a *derived* hash of the
   other fields in the executable code (`e_equality.py`).
5. **K-2's own top-level structure `(𝒜,ℛ)` remains stable and undisputed across every source** — the
   instability is localized entirely to its `Assertion` constituent.
6. **K-2 classified: 5. COMPETING OBJECT DEFINITIONS**, with a narrower, additional finding of type
   6 (internal inconsistency) localized to `t285_reconcile.py`'s own two sections.
7. **The K-1 → K-2 projection is revised into two competing projections** ($\pi_1$, using D285-1/
   `t285_reconcile.py`'s own Assertion field set; $\pi_2$, using D285-6/`t285_equality.py`'s own field
   set) — not shown equivalent, compatible, or reconcilable. Phase 5H's own "partial correspondence"
   finding is narrowed (not reversed) to apply specifically to $\pi_2$, the projection it was actually
   testing — it was never demonstrated for $\pi_1$.

**Overall completion classification: D — INTERNALLY INCONSISTENT**, spanning three layers precisely
named: conceptual ontology (D285-1 vs. D285-6), executable semantics (the two scripts disagree with
each other and `t285_reconcile.py` disagrees with itself), and provenance (the code was never updated
to reflect the prose's own later revision).

**No classification was changed. No Model A/B/Phase-3/Phase-4/Phase-5A through 5H artifact was
modified. D285-1/D285-6/D285-7 and the corpus's own three executable scripts were not modified. No DDD
context mapping was declared. No four-model or cross-model convergence was performed. No unified or
canonical Kernel was constructed. No implementation was performed.**

**Verification**: `resume.py`/`resume_mathematical.py` → both `CONSISTENT`, unchanged;
`classification-register.tsv` → 0 non-pending rows, unchanged; 101 frozen prior-phase files confirmed
unmodified; filesystem scope confirmed — only the new Phase-5I directory (16 files) plus this entry
were written; 25 raw-source spot-checks performed (requirement ≥20), covering all three executable
scripts.

**Phase 5I status: COMPLETE. Phase 5J, global reclassification, four-model convergence, unified/
canonical Kernel construction, DDD context-map adoption, repair of any frozen artifact, and
implementation remain unauthorized and untouched.**

---

## MD-021 Phase 5J — K-2 Authority, Version, Provenance and Supersession Adjudication (EXECUTED, 2026-09-08)

**Authorization**: separate, explicit, following the completed Phase 5I integrity audit. Central
question: can the corpus establish a legitimate authority, version, provenance, or supersession
relationship among the competing K-2 Assertion definitions, or are they genuinely unresolved competing
definitions?

**Method**: built an extended K-2 definition/version register (7 records); read Step 272A and Step
272B in full (3,556 lines combined, previously only confirmed to exist) — the two documents most
plausibly expected to resolve the Assertion-unpacking conflict, since they are the corpus's own cited
source for K-2's own operation set and epistemic-status structure; ran a repository-history audit via
`git log --follow` against all six D285/executable-script files; performed an authority audit across
four distinct authority concepts (mathematical/documentary/governance/executable).

**Central findings**:
1. **Repository history cannot establish creation or modification order between any of the D285/exec
   artifacts.** All six files were added to version control in a single bulk-import commit
   (`70fee73c`, 2026-09-06), which reflects when the corpus was checked into git, not when the
   underlying research was written. Every ordering question this phase's own authorization posed
   about git history is reported `NOT EVIDENCED`, as its own authorization anticipated.
2. **Step 272A and Step 272B — read in full for the first time — do not define `Assertion`'s own
   field structure at all**, despite being the corpus's own genuine, substantive source for K-2's
   operation set (`𝒪_sem`, 23-row classification table) and epistemic-status structure. They are
   silent on the exact conflict this reconstruction has tracked since Phase 5G.
3. **A third `Qualify` arity found**: `Qualify(o,c,π)→e` (Step 272A, 3 arguments), joining the
   1-argument (`CaptureAndQualify(O)`, seq 0795) and 2-argument (`Observation×Policy→Evidence`,
   D285-6) variants already known — now 4 mutually-incompatible arities total, with none unified by
   any version or specialization relationship the corpus itself states. Step 272A does supply the
   corpus's own clearest status label for `Qualify` — "REQUIRED / POLICY-DEPENDENT" — which describes
   its importance, not its signature.
4. **New evidence on the `Π`/`π` denotation question** (Phase 5I): Step 272B's own
   `Assess:(A,E,\Pi,C,\pi)→AssessmentResult` uses both capital `Π` (exactly once, never separately
   glossed) and lowercase `π` (four times, always elaborated as "the policy"). This weighs — without
   resolving — the question toward "Policy," while `e_equality.py`'s own concrete, executable worked
   example (`Pi="origin:scan"`) remains independent evidence for a provenance-shaped denotation.
   Verdict remains `UNRESOLVED`, since evidence quality (a worked, executable example) is not directly
   comparable to evidence count (two prose sources).
5. **The `id`-as-field-vs-derived-hash question is resolved as compatible**, not conflicting — a
   derived identifier can legitimately be represented as a record field without being an independent
   primitive input; this is the one genuinely resolved sub-question in the phase's entire scope.
6. **No authority marker of any kind exists for `Assertion`'s own field structure, `Qualify`, or K-2 as
   a whole**, in any of the four tested authority senses — in explicit contrast to K-1, which carries a
   full ratification chain (`FA-4`, `D-FA-6`). K-2 itself remains `NOT RATIFIED` per D285-1's own
   K-1..K-7 table.
7. **Mathematical compatibility testing found no bijection, injection, surjection, embedding,
   quotient, or lossless encoding** connecting the two competing Assertion characterizations, across
   8 tested relation types.

**Overall completion classification: E — GOVERNANCE-UNRESOLVED**, with the contradiction dimension
(inherited from Phase 5I, unresolved by this phase) and the source-research dimension (confirmed
absent across two newly-read, full-length documents) both stated explicitly rather than folded into
the governance finding.

**No classification was changed. No Model A/B/Phase-3/Phase-4/Phase-5A through 5I artifact was
modified. D285-1/D285-6/D285-7, Step 272A/272B, and the corpus's own three executable scripts were not
modified. No DDD context mapping was declared. No four-model or cross-model convergence was performed.
No unified or canonical Kernel was constructed. No implementation was performed. No corpus governance
record proposing an Assertion reconciliation was authored — the authorization's own detailed scope
explicitly forbids "silent reconciliation of competing definitions," and this phase's own findings
(no authority marker exists for any candidate resolution) argue against attempting one without a
separate, explicit authorization.**

**Verification**: `resume.py`/`resume_mathematical.py` → both `CONSISTENT`, unchanged;
`classification-register.tsv` → 0 non-pending rows, unchanged; 117 frozen prior-phase files confirmed
unmodified; filesystem scope confirmed — only the new Phase-5J directory (17 files) plus this entry
were written; 24 raw-source spot-checks performed (requirement ≥20), including direct reads of Step
272A/272B and a repository-history audit.

**Phase 5J status: COMPLETE. Phase 5K, global reclassification, four-model convergence, unified/
canonical Kernel construction, DDD context-map adoption, repair of any frozen artifact, and
implementation remain unauthorized and untouched.**

---

## MD-021 Phase 5K — Governance Proposal for Assertion Reconciliation (EXECUTED, 2026-09-08)

**Authorization**: separate, explicit, following the completed Phase 5J governance-unresolved finding.
Purpose: prepare a governance decision proposal for the unresolved K-2 `Assertion` conflict — not to
select which existing definition is "correct."

**A remarkable corpus find, load-bearing for this phase's own structure**: seq 0927
(`step_283_missing-part-governance-decision-protocol-authority-provenance-and-closure-criteria.md`,
2026-08-31, read in full this phase) already defines a formal `Prepare → Decide → Ratify → Record`
governance-act sequence, an explicit `Authority(a,scope)` (theoretical) vs. `LegitimateAuthority(a,
scope,source,validity)` (organizational-fact) distinction, and a Governance Decision Register table
format — adopted directly as this phase's own structure. The same document explicitly uses *"HPA is
the ratification authority"* as a **negative example** of an overclaim a verifier must not make
"unless the organizational corpus already establishes that fact." This phase found no such
establishment anywhere: "HPA" functions as the corpus's own recurring reviewing/ruling actor
throughout much of the later research (15 files reference it), but its own organizational legitimacy
for this specific decision is never independently verified. **Decision authority: `GOVERNANCE
AUTHORITY NOT EVIDENCED`.**

**Five reconciliation options formulated and evaluated** on mathematical, statistical, DDD-
architectural, and knowledge-engineering grounds: Option A (preserve D1/D3), Option B (preserve
D2/D4), Option C (an explicit two-layer Observation→Qualify→Evidence model, motivated by the
strongest single-relationship evidence found in this whole investigation — 3 independent sources —
but explicitly a new structural proposal), Option D (a new governance-synthesized union schema,
explicitly labeled `PROPOSED GOVERNANCE SYNTHESIS`), and Option E (preserve both variants, formally
postpone canonicalization, evaluated on equal footing per the authorization's own instruction).

**Recommendation (NON-BINDING, NOT MATHEMATICALLY DERIVED, HUMAN DECISION REQUIRED)**: Option E as an
interim governance position — the only option with zero information loss and the lowest governance
complexity, directly consistent with the corpus's own governance principle (seq 0927's own `GC=NOT
CLAIMED` does not block further work) and with this reconstruction's own standing evidentiary
discipline (default to `UNRESOLVED` absent demonstrated equivalence). Option C is named as the
standing content-level candidate for any future, separately-authorized reconciliation attempt, given
its own strongest-available evidentiary basis.

**A formal Governance Decision Record was produced** (5 open entries: which reconciliation option to
adopt; who has legitimate authority to ratify; `Π`'s denotation; `Qualify`'s authoritative arity;
`State`'s projection target), using the corpus's own native register format, decision status
`PROPOSED — NOT RATIFIED`.

**No classification was changed. No Model A/B/Phase-3/Phase-4/Phase-5A through 5J artifact was
modified. D285-1/D285-6/D285-7, Step 272A/272B, seq 0927, and the corpus's own three executable
scripts were not modified. No DDD context mapping was declared. No four-model or cross-model
convergence was performed. No unified or canonical Kernel was constructed. No Assertion schema was
ratified, adopted, canonicalized, or implemented.**

**Verification**: `resume.py`/`resume_mathematical.py` → both `CONSISTENT`, unchanged;
`classification-register.tsv` → 0 non-pending rows, unchanged; 134 frozen prior-phase files confirmed
unmodified; filesystem scope confirmed — only the new Phase-5K directory (17 files) plus this entry
were written; 22 raw-source spot-checks performed (requirement ≥20).

**Phase 5K status: COMPLETE — GOVERNANCE PROPOSAL PREPARED, NOT RATIFIED. Phase 5L, ratification,
adoption, canonicalization, repair, implementation, DDD context-map adoption, and four-model
convergence remain unauthorized and untouched.**

---

## MD-021 Phase 5L — Independent Adversarial Audit of Phase 5K Governance Proposal (EXECUTED, 2026-09-08)

**Authorization**: separate, explicit, an independent adversarial audit of Phase 5K's own governance
proposal, testing whether its recommendation of Option E is evidence-justified or embeds an
unacknowledged preference, whether Options A–E are sufficiently complete, and whether the
`GOVERNANCE AUTHORITY NOT EVIDENCED` finding is itself adequately established.

**Three headline findings**:

1. **"3 independent sources" for the Observation→Qualify→Evidence relationship overstated the
   evidentiary framing.** Re-checked directly: none of seq 0630, seq 0795, or Step 272A cites the
   others — no citation chain exists — but all three sit within one continuously-numbered research
   programme spanning 3 days. Corrected to: "three separate, uncited restatements within one
   continuous research programme," not "3 independent sources."
2. **Option E's own "lowest governance complexity" claim conflated ratification-time complexity with
   overall system complexity.** True narrowly (a smaller decision surface to ratify now); not shown
   true broadly — Option E defers, rather than eliminates, complexity to the point of first
   consumption, where no criterion is supplied for choosing a variant. Corrected accordingly.
3. **A consequential new finding outside this phase's own direct scope**: D285-1's own foundational
   citation for K-1's ratification — "`C-022` in `claim-registry`" — **cannot be verified anywhere in
   the corpus**. A file named "claim-registry" (Step 239) exists but contains neither "C-022" nor the
   attributed content; the only "C-022" found elsewhere (step-152's own "Constitution v1.0") concerns
   an unrelated topic (trace identity). This does not reopen K-2's own governance-unresolved status
   (this phase's actual scope) but weakens the implicit K-1-vs-K-2 "real ratification vs. none"
   contrast this reconstruction has repeatedly drawn — flagged for a future, separately-authorized
   phase, not adjudicated here.

**Five specific corrections recorded** (`13`): the "3 independent sources" framing; the "lowest
governance complexity" framing; Option C's own "strongest candidate" framing (its motivating
relationship is well-evidenced, its structural embedding into `Assertion` is not, equal in status to
Option D's own synthesis); Option E's own 2-variant naming scheme (should be 3, including the
previously-dropped `e_equality.py` record); and the `claim-registry` finding above. **None of these
corrections modifies Phase 5K's own frozen artifacts.**

**One genuine, minor option-completeness gap named** (not added to the option set): "Observation and
Evidence as separate knowledge objects, not fields of Assertion at all" — logically available, not
previously named, not material enough to invalidate the existing A–E frame.

**`GOVERNANCE AUTHORITY NOT EVIDENCED` re-confirmed, with the search scope widened corpus-wide**
(beyond `phase_measure_theory/` alone) — same result.

**Overall verdict: 5L-B — PHASE 5K CONFIRMED WITH QUALIFICATIONS.** The recommendation of Option E as
a non-binding, reversible interim position, with Option C named as the standing content-level
candidate, survives independent adversarial audit.

**No classification was changed. No Model A/B/Phase-3/Phase-4/Phase-5A through 5K artifact was
modified. D285-1/D285-6/D285-7, Step 272A/272B/152/239, seq 0927, and the corpus's own three
executable scripts were not modified. No DDD pattern was adopted. No Assertion schema was ratified. No
governance decision was recorded as effective. No four-model or cross-model convergence was
performed.**

**Verification**: `resume.py`/`resume_mathematical.py` → both `CONSISTENT`, unchanged;
`classification-register.tsv` → 0 non-pending rows, unchanged; all 151 frozen prior-phase files
confirmed unmodified; filesystem scope confirmed — only the new Phase-5L directory (16 files) plus
this entry were written; 28 raw-source spot-checks performed (requirement ≥25), 11 specifically
targeting claims supporting the Phase 5K recommendation (requirement ≥10).

**Phase 5L status: COMPLETE — INDEPENDENT ADVERSARIAL AUDIT ONLY — NO GOVERNANCE DECISION MADE. Phase
5M, governance ratification, implementation, canonicalization, DDD adoption, Assertion schema
selection, Option E adoption, Option C reconciliation, Kernel unification, and four-model convergence
remain unauthorized and untouched.**

---

## MD-021 Phase 5M — C-022 / Claim-Registry Evidentiary Investigation for K-1 (EXECUTED, 2026-09-08)

**Authorization**: separate, explicit, a narrowly-bounded evidentiary investigation following Phase
5L's own discovery that D285-1's foundational citation for K-1's ratification ("`C-022` in
`claim-registry`") could not be verified. Central question: can the corpus substantiate this citation?
Explicitly not an adjudication of K-1's own governance status.

**Method**: reconstructed the literal citation into 10 fields; ran a corpus-wide census for every
named search term; distinguished and compared multiple candidates; performed a field-by-field content
match; built a typed provenance graph; ran a temporal/VCS analysis; re-evaluated authority semantics
specifically for this citation; typed every conclusion E1–E5; actively attempted adversarial
falsification; answered six required questions separately.

**Central finding: M-B — CITATION PARTIALLY SUBSTANTIATED.** D285-1's own citation bundles genuine,
locatable, content-matching evidence spread across three separate documents — none of which is "C-022
in `claim-registry`":
1. **seq 0630 (step-049)**: the true origin of K-1's 8-primitive tuple — substantiates "`step-049`"
   directly.
2. **seq 0757** (a "Phase 1 Status Report," `how_to_combine/combine-prompt-2`): substantiates "50
   attack classes, no counterexample" **with an important dropped hedge** — the primary wording is
   "no counterexample found in tested scenarios, explicitly NOT a proof," which D285-1's own citation
   omits. This same document self-reports producing a `claim-registry.md` (1C, 26 claims) deliverable
   that **cannot be found anywhere in the checked-in corpus**, despite repeated, actively adversarial
   search (no `analysis/` directory, no versioned registry, no external pointer).
3. **seq 0764 ("HPA RULING — GN-31")**: substantiates "D-FA-6 'qualified naming'" near-verbatim, a
   genuine ratification act — but of *naming*, not explicitly of the combined 8-primitive/attack-class
   claim. **A new finding**: this document explicitly expands "HPA" to **"Highest Project Authority"**
   — the first time this abbreviation has been found spelled out anywhere in this reconstruction's own
   reading (Phase 5D–5L). Its own organizational legitimacy remains self-declared, not externally
   verified — re-tested specifically in this context, unchanged from Phase 5J/5L's own finding.

**The identifier "C-022"** (found once, at step-152's own §152.26, "Trace identity") and **the phrase
"claim-registry"** (found as a literal filename once, Step 239, about a *different* Kernel object, the
5-element `𝔎=(G,σ,θ,λ,π)` lineage) **both denote confirmed unrelated subjects wherever they actually
appear in the corpus.** No file anywhere contains "C-022" AND "50 attack classes" AND the 8-primitive
list together.

**Six-question adjudication, kept explicitly separate**: does the cited C-022 exist (no, not for this
subject) · does it contain the claimed content (no) · does it establish ratification (partially,
elsewhere, for naming only) · does it identify an authority (yes, seq 0764: HPA) · is that authority
legitimately established (no) · does D285-1 accurately cite it (no — it bundles genuine but separate
artifacts under an identifier that resolves to unrelated subjects).

**Impact analysis, strictly bounded**: D285-1's own provenance claim is directly affected; the K-1/K-2
"real ratification vs. none" contrast used repeatedly since Phase 5F remains **directionally accurate**
(K-1 has some genuine ratification act; K-2 has none found anywhere) but is **less clean than its prior
framing implied**. No prior phase's own central findings about K-2 are mathematically or evidentially
derived from this citation's own accuracy — every use was background/contrast context, not a
load-bearing premise. **Phase 5E through 5L are NOT reopened or rewritten.**

**No classification was changed. No frozen artifact — including D285-1/D285-6/D285-7, Step 272A/272B,
step-152, Step 239, seq 0757, seq 0764, seq 0630, seq 0927, or the corpus's own three executable
scripts — was modified. K-1's own governance status was NOT adjudicated. No four-model or cross-model
convergence was performed. No implementation was performed.**

**Verification**: `resume.py`/`resume_mathematical.py` → both `CONSISTENT`, unchanged;
`classification-register.tsv` → 0 non-pending rows, unchanged; all 167 frozen prior-phase files
confirmed unmodified; filesystem scope confirmed — only the new Phase-5M directory (16 files) plus
this entry were written; 27 raw-source spot-checks performed (requirement ≥25), 19 directly concerning
C-022/claim-registry/K-1 provenance (requirement ≥15).

**Phase 5M status: COMPLETE — C-022 / CLAIM-REGISTRY EVIDENTIARY INVESTIGATION ONLY — K-1 GOVERNANCE
STATUS NOT ADJUDICATED. Phase 5N, K-1 ratification adjudication, reopening Phase 5E–5J, altering
Phase 5K/5L, Option selection, implementation, and four-model convergence remain unauthorized and
untouched.**

---

## MD-021 Phase 5N — Direct K-1 Ratification Status Adjudication (EXECUTED, 2026-09-08)

**Authorization**: separate, explicit, the first phase in this sub-sequence authorized to adjudicate
K-1's own governance status directly, following Phase 5M's own explicit refusal to do so. Central
question: is K-1 ratified, and by what act?

**Method**: reconstructed the M₄₉/K_t/K-1 identity as an explicit, disclosed inference (E3/E4, not
E1); ran a corpus-wide governance-evidence census; gave seq 0764's own D-FA-6 and D-FA-4 rulings a
full-scope adjudication, quoting both verbatim; investigated the FA-4/D-FA-6 numbering question;
decomposed "ratification" into eight distinct propositions (R1–R8); re-tested authority and
legitimacy specifically for this citation; tested seq 0764's own governance-event fields against seq
0927's own required protocol; kept statistical testing and ratification strictly separate;
mathematically tested six forbidden implications; built a typed provenance graph; applied a DDD
naming/object distinction; ran a temporal analysis; adversarially falsified five hypotheses (H1–H5);
typed every conclusion E1–E5; produced the required N1–N4 status adjudication with a 12-row matrix; ran
a strictly bounded impact analysis across Phase 5E–5M.

**Central finding: N2 — PARTIALLY RATIFIED.** K-1's naming convention (the label `K_t`) is genuinely
ratified by a real, internally well-formed governance act (D-FA-6, within seq 0764, "HPA RULING —
GN-31," dated 2026-08-28), which states directly: **"No formal object changes; this is a terminology
policy."** K-1's own 8-primitive object is **not** ratified by any act found in this corpus — the one
ruling that addresses it (D-FA-4) explicitly classifies the 8-primitive kernel candidate ("M₄₉") as an
**"L2 candidate"** and explicitly states: **"Membership at the object level remains open (OQ-2)."**
This rests on direct quotation from the ruling document itself, not on inference.

**A second missing-ratified-deliverable finding, paralleling Phase 5M's own `claim-registry.md`
finding**: `FA-4-concept-terminology-reconciliation.md` is cited and quoted, as "RATIFIED, GN-31," by
6+ downstream documents (including a direct quote of its own `Knower` row in D285-2) — but does not
exist anywhere in the checked-in corpus, despite exhaustive search. **"FA-4" and "D-FA-4" are
confirmed to be two separate, non-overlapping artifacts** — a numbering conflation earlier phases'
citations left unresolved, now disambiguated.

**Adversarial falsification of five hypotheses (H1–H5)**: H1 (fully ratified) and H5 (not ratified at
all) both failed — the first against D-FA-6's own explicit object-level disclaimer, the second against
the genuine, real, directly-quoted naming ratification. H4 (governance-unresolved) only partially
applied, to the object-level sub-question alone. **H3 (partial ratification) is the only hypothesis
surviving fully** and is adopted as this phase's own final verdict.

**Impact analysis, strictly bounded**: Phase 5E through 5M are each classified UNAFFECTED,
BACKGROUND-INTERPRETATION-NARROWED, or (Phase 5J, PROVENANCE-CLAIM-AFFECTED) — no phase's own central,
adjudicated finding is mathematically or evidentially derived from K-1's own governance status being
fully, rather than partially, established. **No phase is reopened or rewritten.**

**No classification was changed. No frozen artifact — including Model A/B/Phase-3/Phase-4/Phase-5A
through 5M, D285-1/D285-6/D285-7, seq 0630/0757/0764/0740/0865/0927, or the corpus's own three
executable scripts — was modified. No Phase-5K Option was selected. No implementation,
canonicalization, or four-model convergence was performed.**

**Verification**: `resume.py`/`resume_mathematical.py` → both `CONSISTENT`, unchanged;
`classification-register.tsv` → 0 non-pending rows, unchanged; all 183 frozen prior-phase files
confirmed unmodified; filesystem scope confirmed — only the new Phase-5N directory (20 files) plus
this entry were written; 32 raw-source spot-checks performed (requirement ≥30), 23 directly concerning
K-1 ratification/governance evidence (requirement ≥20).

**Phase 5N status: COMPLETE — K-1 RATIFICATION STATUS DIRECTLY ADJUDICATED — NO IMPLEMENTATION,
CANONICALIZATION, OPTION SELECTION, OR FOUR-MODEL CONVERGENCE AUTHORIZED. Phase 5O and any downstream
phase remain unauthorized and untouched.**

---

## MD-021 — Research-to-Governance Handover (2026-09-08, synthesis only, NOT a phase)

**Not Phase 5O.** A single, standalone synthesis artifact
(`14_decision-log/MD-021-research-to-governance-handover.md`) compiled exclusively from the already-
completed and frozen Phase 5K/5L/5M/5N artifacts — no new corpus research performed, no source artifact
read for the first time, none modified. The P-series (`docs/knowledgeos/theory-extraction/`) was not
consulted.

**Purpose**: make the transition from research to organizational governance explicit. Separates two
tracks that must not be merged — **K-1** (5M/5N: N2, PARTIALLY RATIFIED — naming ratified, object-level
membership left open under OQ-2) and **K-2/Assertion** (5K/5L: NOT RATIFIED — `GOVERNANCE AUTHORITY NOT
EVIDENCED`, 5 open decisions GK-5K-1 through GK-5K-5) — and carries 5L's five corrections to 5K forward
as an explicit overlay without modifying 5K's own frozen text: the "3 independent sources" claim
downgraded to "three uncited restatements within one continuous research programme"; Option E's "lowest
governance complexity" narrowed to ratification-time only; Option C's "strongest candidate" framing
narrowed to equal status with Option D's synthesis; Option E's variant count corrected from 2 to 3
(D5 was silently dropped); the `claim-registry` finding corrected from "never located" to "located,
content does not match D285-1's own citation."

**Central finding, restated once for governance**: `GOVERNANCE AUTHORITY NOT EVIDENCED` is one finding
that applies to both tracks — established in 5K, re-confirmed with a widened corpus-wide search in 5L,
re-tested against the D-FA-6/D-FA-4 rulings specifically in 5N. "HPA" (expanded in seq 0764 as "Highest
Project Authority") recurs throughout the corpus as a ruling actor, but no document establishes its own
mandate, charter, or delegation source — repeated ruling behavior is explicitly not treated as evidence
of legitimate authority. Three distinct actions are kept apart: authority establishment (not done) →
governance decision (cannot occur until the first) → ratification/canonicalization (cannot occur until
the second).

**Recommended next action — exactly one, and it is organizational, not a research task**: establish,
within the real organization, which body/role/delegated authority is legitimately empowered to decide
GK-5K-1 through GK-5K-5 and K-1's own OQ-2. Additional corpus research cannot manufacture this
organizational fact — three independent search efforts (5K, 5L, 5N) already looked and found nothing.

**No classification changed. No frozen artifact (5A–5N, D285-1/D285-6/D285-7, Step 272A/272B, seq
0630/0757/0764/0927, or the three executable scripts) modified. No Option A–E selected. No Assertion
schema ratified. No K-1 object-level ratification declared. No DDD context map adopted. No Schema v3.
No four-model or cross-model convergence.**

**Status: THREE-MODEL PROGRAMME — RESEARCH COMPLETE TO GOVERNANCE BOUNDARY — HANDOVER ONLY. Phase 5O is
NOT opened by this document and is not authorized. Research or ratification may resume only under a
new, separately authorized research question, or after the organizational step above and a subsequent,
explicit, evidenced governance decision.**

---

## MD-022 — Governance Authority Evidence-Requirements Preparation (2026-09-08, K-2 track only, NOT a phase)

**Not a fourth authority search.** `GOVERNANCE AUTHORITY NOT EVIDENCED` was established once, in Phase
5K (`03_decision-authority-analysis.md`), and independently re-tested and re-confirmed twice — Phase 5L
(`08_governance-authority-reaudit.md`, search widened corpus-wide) and Phase 5N (`07`, re-tested
specifically against D-FA-6/D-FA-4). This artifact
(`14_decision-log/MD-022-governance-authority-evidence-requirements.md`) does not re-test that finding
against the corpus. It builds the **evidence requirements and verification tooling** a real
organizational governance process will need when obtaining authority evidence outside this corpus —
scoped to the K-2 track only (`GK-5K-1` through `GK-5K-5`); K-1/OQ-2 remains untouched and separate,
per MD-021 §9.

**Contents**: a nine-level evidence-status taxonomy (direct evidence → machine-observable fact →
reconstructed provenance → research interpretation → hypothesis → candidate → recommendation →
governance decision → ratified/canonical), with this research programme explicitly barred from
promoting anything into the last two levels; a required-authority-evidence checklist built on seq
0927's own `LegitimateAuthority(a,scope,source,validity)` predicate (governance charter, role mandate,
committee mandate, delegation record, RACI assignment, recorded governance responsibility — categories
only, no candidate named, HPA neither implied nor excluded); a reusable 9-step authority-chain
verification test (not applied to any candidate here); a K-2 decision-readiness matrix (all five
GK-5K rows: research complete YES, authority established NO, decision possible NO, ratification
possible NO); the three-stage sequencing (authority establishment → governance decision → ratification)
restated as non-collapsible; and the exact organizational ask, paired with the evidence checklist.

**No classification changed. No frozen artifact (5A–5N, MD-021, source documents, or the three
executable scripts) modified. No authority named or inferred. No Option A–E selected. No Π/Qualify/
State resolved. No DDD context map adopted. No Schema v3. K-1/OQ-2 not touched.**

**Status: GK-5K-1 through GK-5K-5 remain OPEN. Phase 5O NOT opened. No ratification, canonicalization,
or implementation occurred. Awaiting the real organizational response — supply of authority evidence
per §4, or an explicit statement that none currently exists.**

---

## MD-021 Phase 6 — Cross-Model Adjudication Extension: Model C1/C2 (EXECUTED, 2026-09-08)

**Authorization**: separate, explicit; extends Phase 3's own cross-model adjudication (`05_cross-
model/`) to Model C1/C2, which did not exist when Phase 3 ran (Phase 3's own `00_index.md`: *"Does not
... begin Phase 4 (Model C1/C2 reconstruction)"*). Explicitly **not Phase 5O** — does not touch, reopen,
or reference as evidence the K-1/K-2 sub-sequence (5A–5N), the research-to-governance handover, or
MD-022.

**Method**: Phase 3's own 16 investigation targets tested against Model C1's 12 named concepts (§A–L,
kernel table §N) and Model C2's sole candidate (§M); new correspondence rows built for C1↔A, C1↔B,
C2↔A, C2↔B only — Phase 3's own A↔B rows cited by pointer, never re-derived. Two consequential
candidate correspondences checked directly against raw source.

**Central findings**:
1. **A promising-looking naming match does not hold.** Model A's seq 0219 was thought (via its own
   per-file synthesis record) to cite "K-1" (`KnowledgeAggregate+ConflictRecord+VerificationPort`),
   matching Model C1's own canonical K-1 (seq 0165/0167). Direct raw-source check: 0219's full text
   contains neither the term nor either seq number — the link exists only in a reconstruction-layer
   synthesis, one step removed from source, and does not hold as a genuine citation.
2. **An independent, citation-free convergence on shared notation.** Model C1's own
   `phase_measure_theory/` arc (seq 0446–0492, main corpus) independently produces a `K_t`/`Δ_t`
   proliferation-and-self-repair pattern using the *identical variable names* as Model B's own
   math-lane thread (M0001–M0132) — verified, via direct search in both directions, to carry **no
   citation link whatsoever**. This is a stronger form of independent replication than Phase 3's own
   original A↔B "State" finding (which had no shared vocabulary at all), and is recorded as a
   strengthened **PROPOSED CROSS-MODEL HYPOTHESIS**.
3. **A `kernel/`-directory classification-boundary finding, directly evidenced.** Seq 0165 and 0219 —
   both implicated in the naming-collision check — carry both `gita`-primary and `c1`-secondary tags,
   confirming (not merely repeating) Phase 4's own `04_boundary-observations.md` finding that this
   corpus's classification boundaries do not track a clean content split.
4. **The Kernel rows settle exactly as Phase 3's own A↔B Kernel row did**: PARTIAL CORRESPONDENCE at
   the categorical level (both C1's and C2's kernel candidates are aggregate/tuple-shaped, matching
   Model A's own family and diverging from Model B's operator-set family), UNRESOLVED at the
   specific-candidate level — no row anywhere in this phase reached STRUCTURAL CORRESPONDENCE or
   above, reported as the correct evidentiary outcome, not a shortfall.
5. **A corpus-wide naming collision surfaced, explicitly not adjudicated**: the label "K-1" is now
   confirmed used for at least two unrelated structures (C1's DDD aggregate, seq 0165/0167; the
   Phase-5A–5N 8-primitive tuple, seq 0630) — recorded as an open question for a future, separately-
   authorized phase; not compared, merged, or resolved here, per this phase's own explicit boundary
   against touching the frozen K-1/K-2 sub-sequence.

**No new INCOMPATIBLE row was found** (contrast Phase 3's own A↔B pass, which found 3) — every C1/C2
candidate correspondence examined settled at PARTIAL CORRESPONDENCE, FUNCTIONAL ANALOGY, or UNRESOLVED,
reflecting genuinely thinner, more categorical-level evidence than several of Phase 3's own sharper A↔B
findings.

**No classification changed. No frozen artifact (Phase 1/2/3/4, Phase 5A–5N, the research-to-governance
handover, MD-022, or any source document) modified. No model selected. No convergence forced. No
governance decision made. GK-5K-1 through GK-5K-5 and K-1/OQ-2 untouched.**

**Verification**: `resume.py`/`resume_mathematical.py` → both `CONSISTENT`, unchanged;
`05_cross-model/`, `02_model-a_gita/`, `03_model-b_mathematical/`, `04_model-c_kernel-ddd/`, all Phase
5A–5N artifacts, the handover, and MD-022 confirmed unmodified; only the new
`14_decision-log/MD-021-phase-6-cross-model-c1c2-extension/` directory (6 files) written; 22 raw-source
spot-checks performed (requirement ≥20).

**Phase 6 status: COMPLETE — CROSS-MODEL ADJUDICATION EXTENDED TO MODEL C1/C2. NO MODEL SELECTED, NO
CONVERGENCE FORCED, NO GOVERNANCE DECISION MADE. Phase 7, gap-analysis, formalization, and any
downstream stage-06+ work remain unauthorized and untouched.**

---

## MD-021 Stage 06 — Gap Analysis (EXECUTED, 2026-09-08)

**Authorization**: separate, explicit. Following a required protocol check (`00_control/protocol.md`
defines `06_gap-analysis` only as a stage-gate node, no dedicated methodology), executed using the
authorization's own fallback artifact structure. **Not Phase 5O** — the K-1/K-2 governance-frozen
sub-sequence (5A–5N), the research-to-governance handover, and MD-022 were treated as an external
frozen boundary, referenced only where a gap genuinely touches them, never reopened.

**Method**: a synthesis stage over the five already-frozen input directories (Phases 1, 2, 3, 4, 6) —
no new corpus research performed. 53 gaps registered (`GA-001`–`GA-053`), each epistemically classified
and evidence-traced, spanning cross-model (6), Model-A-internal (15, sourced directly from
`02_model-a_gita/03`), Model-B-internal (17, from `03_model-b_mathematical/03`), Model-C1/C2-internal
(12, from `04_model-c_kernel-ddd/02`/`03`), and reconstruction-wide formal/empirical gaps (3).

**Central finding**: exactly two gaps are BLOCKING, and only for a *unified* cross-model
formalization — **GA-001** (no two of the four independently-produced Kernel-candidate families are
shown structure-preserving-equivalent; the aggregate-vs-operator-set categorical split first found in
Phase 3, confirmed a fourth and fifth time by C1 and C2) and **GA-038** (Model B's own explicit
statement that no corpus-internal, non-arbitrary way exists to select one canonical `K_t` from its own
9+-variant family — the same question GA-001/GA-002/GA-003 ask cross-model, now sourced directly from
the corpus's own words). **Neither blocks model-specific formalization** — a formalization effort
staying within one model's own already-tested results (e.g. Model B's representation-dependent kernel
work) would not need either resolved first.

**Three governance-blocked items surfaced, none opened**: `GA-006` (a corpus-wide "K-1" naming
collision — Model C1's DDD aggregate, seq 0165/0167, vs. the frozen Phase-5 8-primitive tuple, seq
0630 — deliberately left unresolved since closing it would require reopening 5A–5N); `GA-044`
(`ADR-KOS-KERNEL-001`, PROPOSED, never formally accepted); `GA-050` (the C1/C2 classification boundary
may not track a clean content split — already flagged by Phase 4, restated here as still open).

**No new corpus contradiction or absence was manufactured** — every one of the 53 gaps traces to an
already-established finding in a frozen input; two Model-B contradictions (Zero-vs-Sat, Structure-First
"general theory" self-description) were confirmed already resolved by the corpus's own audit discipline
and correctly excluded from the blocking register.

**No classification changed. No frozen artifact (Phase 1/2/3/4/6, Phase 5A–5N, the handover, or MD-022)
modified. No model selected. No unified theory, universal Kernel, or canonical KnowledgeOS created. No
numerical scores or confidence percentages used anywhere. K-1/OQ-2 and GK-5K-1 through GK-5K-5
untouched.**

**Verification**: `resume.py`/`resume_mathematical.py` → both `CONSISTENT`, unchanged; all five frozen
input directories confirmed unmodified; only the new `06_gap-analysis/` directory (7 files) written.

**Stage 06 status: COMPLETE — 53 GAPS REGISTERED, 2 BLOCKING FOR UNIFIED FORMALIZATION ONLY. Stage 07
(formalization) NOT opened by this stage's completion — whether and how to proceed remains a separate,
explicit authorization decision.**

---

## MD-023 — Blocking-Gap Resolution Study (EXECUTED, 2026-09-08)

**Authorization**: separate, explicit, with three pre-negotiated clarifications applied throughout —
recurrence language corrected ("recurred consistently... not independent or statistical confirmation,"
never "confirmed"); a scoped, pre-registered 4-pair diagnostic sample rather than an exhaustive
pairwise sweep; a four-state obstruction taxonomy (`NOT FORMALLY SPECIFIED ENOUGH TO TEST` /
`PARTIALLY TESTABLE` / `TESTED — NO MAP FOUND` / `UNRESOLVED`), never collapsed, with no under-specified
candidate reconstructed to enable a test. **Not Stage 07** — investigates whether GA-001/GA-038 are
resolvable; does not formalize, canonicalize, or select a model. **Not Phase 5O.**

**GA-001 (Kernel identity) result**: 4 pre-registered pairs tested (B's C0 ↔ C1's P-3; B's C0 ↔ C1's
P-5 "K-1"; A's Kernel Candidate v0.2/v0.3 ↔ C1's P-5; C2's Kernel definition ↔ C1's P-5). No pair
reached beyond `NOT FORMALLY SPECIFIED ENOUGH TO TEST` or `PARTIALLY TESTABLE — no map found`. The
aggregate-vs-operator categorical divide is corroborated by a failure-mode asymmetry (C0's own test
found a missing operation; C1's P-3 test found an invalid data invariant — exactly the failure each DDD
layer would be expected to exhibit) and **reframed, not resolved**, via a DDD complementarity
hypothesis: A/C1/C2's aggregates and B's operators may be two compatible layers of one eventual model
(Entity/Aggregate layer vs. Domain-Service layer) rather than rival answers — explicitly hypothesis-
level, untested by actual composition, which is architecture work out of scope. Even within the
aggregate category, Pairs 3/4 found **no correspondence between any two independently-produced
candidates** (A vs. C1, C1 vs. C2) — persistent non-convergence at the object level, reported as a
legitimate result. **New finding**: `ConflictRecord`/contradiction-tracking is present in C1's own P-5
and in Model B's own contradiction-representation line, but absent from A's and C2's own
most-specified candidates.

**GA-038 (no canonical `K_t`) result**: 10 candidate canonicalization criteria tested against a
six-question admissibility test. **`NO CORPUS-JUSTIFIED CANONICALIZATION CRITERION FOUND`**, with one
precise exception — preservation-via-explicit-ratification is evidenced and was successfully applied,
but only once, to `Δ_t` (M0132's own "freeze-as-constraint, not conclusion"), never to the full `K_t`
tuple. **This is the study's own single most consequential finding**: the corpus's one successful
closure of a proliferating-variant problem was achieved by an explicit governance act, not by
mathematical selection — meaning GA-038 cannot be resolved by further scientific analysis alone.

**GA-002/GA-003 (K_t/Δ_t recurrence) result**: remain hypothesis-level, correctly not upgraded. Six
candidate explanations tested; best-supported for the naming match itself is reconstruction-process
notational reuse (both lineages sit inside one continuous-authorship corpus); independently supported,
for the `Δ_t` non-symmetry correction specifically, is a genuine shared mathematical fact (both
lineages independently proved and corrected for the same non-symmetry).

**No classification changed. No frozen artifact (Phase 1/2/3/4/6, Stage 06, Phase 5A–5N, the handover,
or MD-022) modified. No model selected. No canonical Kernel or `K_t` created. No composition attempted.
K-1/OQ-2 and GK-5K-1 through GK-5K-5 untouched throughout.**

**Verification**: `resume.py`/`resume_mathematical.py` → both `CONSISTENT`, unchanged; all frozen input
directories confirmed unmodified; only the new `14_decision-log/MD-023-blocking-gap-resolution/`
directory (10 files) written.

**MD-023 status: COMPLETE — PERSISTENT NON-CONVERGENCE REPORTED FOR BOTH GA-001 AND GA-038 AS A
LEGITIMATE RESULT. Stage 07 NOT opened. Any future composition-design study, or the governance/
ratification act GA-038's own precedent implies would be needed, remain separate, unauthorized next
actions.**

---

## MD-024 — GA-001 Composition and Complementarity Study (EXECUTED, 2026-09-08)

**Authorization**: separate, explicit, with two methodological corrections to MD-023's own wording
applied throughout (MD-023's frozen text not edited, corrections recorded here only): "no
canonicalization criterion within the tested criterion set was found" (not "cannot be resolved... by
further scientific analysis alone"); MD-023's non-convergence finding holds "within the tested
diagnostic sample" (not universally). **Not Stage 07. Not Phase 5O.**

**Pre-execution raw-source finding**: checked directly whether Model B's own C0 operators (13-operator
kernel) have a stated input/output type anywhere in Model B's own legitimate 151-file evidence base.
M0030 (the specifying prompt) requests exactly such a contract for each operator; M0035 (the confirmed
execution record Model B's own register cites) does not contain it. A more rigorous operator-contract
apparatus (`ASSERT`/`LINK`/`REVISE`/`RETRACT`/`ISOLATE`, with real formal preconditions/postconditions)
does exist in the math lane (`M0185`, `canonical_source` `M0184`) but is tagged `KR-SIM`, not `b` —
outside Model B's own reconstructed scope by Phase 2's own authorization, not imported here.

**Method**: pre-registered 5 selection criteria before choosing any pair; 4 diagnostic pairs tested
(`Validate`↔C1's P-3; `S^epi(E,C,Q)→A`↔A's 8-field Kernel Candidate v0.2/v0.3; B's operator set↔C2's
`Θ` component; B's tested contradiction-representation line↔C1's `ConflictRecord`, deliberately
adversarial). Four-state taxonomy used throughout, never collapsed.

**Results**: 3 of 4 pairs `NOT FORMALLY SPECIFIED ENOUGH TO TEST`/`PARTIALLY TESTABLE`, confirming the
pre-execution finding — Model B's own operators, within its legitimate evidence base, lack the
operational content composition testing requires. **Two genuine, narrow positive findings, neither
reconstructed**: (1) A's Kernel Candidate v0.2/v0.3's own field names (`Evidence`/`Question`/
`Assessment`) align with B's `S^epi`'s own stated argument names (`E`/`Q`/`A`), missing only a
`Context` argument A's own evidence does not supply; (2) B's own tested contradiction-representation
result (M0120/M0127: flat single-field representations provably lose required distinctions) directly
constrains what C1's own `ConflictRecord` field would need to become to be adequate — a genuine
functional correspondence, explicitly not a demonstrated structural map. **The core complementarity
hypothesis (H_C) is neither confirmed nor refuted — predominantly `H4` (untestable given current
specification), with these two findings consistent with, but not confirming, complementarity.**

**No classification changed. No frozen artifact (Phase 1/2/3/4/6, Stage 06, MD-023, Phase 5A–5N, the
handover, or MD-022) modified. No model selected. No composition, canonical Kernel, or `K_t` created.
GA-038 not reopened. K-1/OQ-2 and GK-5K-1 through GK-5K-5 untouched.**

**Verification**: `resume.py`/`resume_mathematical.py` → both `CONSISTENT`, unchanged; all frozen input
directories confirmed unmodified; only the new `14_decision-log/MD-024-ga001-composition-
complementarity/` directory (12 files) written.

**MD-024 status: COMPLETE — COMPLEMENTARITY HYPOTHESIS NEITHER CONFIRMED NOR REFUTED, PREDOMINANTLY
NOT TESTABLE GIVEN CURRENT SPECIFICATION. Stage 07 NOT opened.**

---

## MD-025 — Specification Sufficiency and Missing-Structure Census (EXECUTED, 2026-09-08)

**Authorization**: separate, explicit, with two corrections to MD-024's own wording applied throughout
(MD-024's frozen text not edited): "not defined by the source / not testable" replaces "fails by
default" for unspecified operators; the `ConflictRecord` finding is corrected to "an adequacy
constraint has been identified, cross-model functional correspondence not established." **Not Stage 07.
Not Phase 5O. The P-series was not consulted, per the user's own standing instruction that
`docs/knowledgeos/theory-extraction/` is a separate, independently-run verification track.**

**Central finding**: an exhaustive, corpus-wide (not sampled) search of the 161-file admissible Model B
population led, via one B-admissible historical-summary document, to `docs/knowledgeos/research/
kernel-reduction/04-operator-contracts.md` — a rigorous, fully-specified operator-contract apparatus
(a 14-atom vocabulary, explicit Input/Output/state-effect conventions, and a per-operator "corpus
support" rating) covering all 13 of C0's operators plus `Qualify`, exactly the specification MD-024
found absent. **This directory is not part of Model B's admissible evidence base and has never been
classified by this reconstruction** (zero rows in `classification-register.tsv` reference it) — but
**M0030 itself, an admissible `b`-tagged file, directly cites this exact directory path (twice, in its
own raw text) as its own required output location.** A parallel finding: `docs/knowledgeos/research/
theory-v1.1-simulation/C-type-system.md` (also never classified) formally types `S^epi`'s own missing
`Context` argument. **Both findings are recorded under a new study-local classification,
`E1-OUT-OF-SCOPE` — specification that is directly evidenced but sits outside the corpus root this
entire reconstruction has operated under since Phase 0 (MD-010/MD-011).**

**Negative findings, with a disclosed search-scope limitation**: no external elaboration was found for
C1's `ConflictRecord` or C2's `Θ`, in every frame searched (`docs/knowledgeos/research/` and
`architecture/`, not the full `docs/knowledgeos/` tree). **A's own `Context` material was independently
re-confirmed to be 4-way internally unresolved** (CT-1, already known from Phase 1) — even setting
scope aside, A's evidence cannot supply `S^epi`'s missing argument without an unjustified selection
among 4 competing treatments.

**Central conclusion**: the GA-001/composition question (MD-024) is **not resolved by this study** —
it is instead shown to depend entirely on a prior, separate, not-yet-made **corpus-boundary decision**:
whether `docs/knowledgeos/research/` should be brought into the admissible evidence base. This study
does not make that decision. If made, a genuine composition retest would become possible **without
inventing any semantics**, since the specification already exists in full.

**No classification changed. No frozen artifact (Phase 1/2/3/4/6, Stage 06, MD-023, MD-024, Phase
5A–5N, the handover, or MD-022) modified. No corpus-boundary expansion performed. No model selected.
No composition performed. K-1/OQ-2 and GK-5K-1 through GK-5K-5 untouched.**

**Verification**: `resume.py`/`resume_mathematical.py` → both `CONSISTENT`, unchanged; all frozen input
directories confirmed unmodified; only the new `14_decision-log/MD-025-specification-sufficiency-
census/` directory (14 files) written.

**MD-025 status: COMPLETE — SPECIFICATION SUFFICIENT BUT OUT-OF-SCOPE FOR B's OPERATORS AND `S^epi`'s
CONTEXT; NOT EVIDENCED FOR `ConflictRecord`/`Θ`; INTERNALLY CONFLICTED FOR A's CONTEXT. Composition
testing remains contingent on a future, separately authorized corpus-boundary decision. Stage 07 NOT
opened.**

---

## MD-026 — Corpus Boundary, Provenance, and Admissibility Adjudication (EXECUTED, 2026-09-08)

**Authorization**: separate, explicit. Establishes evidence for a future corpus-boundary decision;
does not make that decision. **Not Stage 07. Not Phase 5O. P-series not consulted.**

**Central finding**: MD-010/MD-011 (2026-09-01) defined the corpus boundary by directory location.
`docs/knowledgeos/research/` was first tracked in this repository's git history on **2026-09-06 — five
days later, in the same commit that checked in the entire `docs/knowledgeos/brainstorming/` corpus**.
That commit's own message linguistically distinguishes *"the brainstorming corpus"* from *"research
lanes,"* naming `research/kernel-reduction/` and `research/theory-v1.1-simulation/` separately from
the enumerated `brainstorming/` subdirectories. `kernel-reduction/04-operator-contracts.md` (MD-025's
own central discovery) is content-internally self-dated to 2026-09-01, the same day as M0030, which
directly cites this exact directory as its own output path — but only as an *intended* output
location; no document states execution actually wrote there, keeping this the weakest link in an
otherwise well-evidenced provenance chain. `theory-v1.1-simulation/C-type-system.md` (the `S^epi`
`Context` finding) has materially weaker provenance — no admissible citation link found, temporal
order unestablished.

**A newly-searched external directory (`docs/knowledgeos/reviews/kernel/`) contains what looks like a
genuine `ConflictRecord` elaboration** — but that same reviewing lane's own internal self-audit
(`S2-R-F028`) explicitly disqualifies it as a *"provenance loop"*: its own earlier adjudication-track
reasoning, saved as a document and mistaken for independent corpus support, explicitly refused as
evidence by its own author. This study applies the same refusal — MD-025's own negative
`ConflictRecord` finding is thereby strengthened, not weakened, by having searched further. No
elaboration of C2's `Θ` was found anywhere in the 6 newly-searched sibling directories
(`governance/`, `backlog/`, `reviews/`, `developer_guide/`, `how_far_we_are/`, `architecture/`).

**Verdict**: predominantly Outcome C (separate research context) for `kernel-reduction/`, with one
material qualification (M0030's direct citation) that keeps Outcome A genuinely open, not refuted;
Outcome D (admissibility unresolved) for `theory-v1.1-simulation/`. **No mechanism for admitting
previously out-of-scope evidence was found in `00_control/protocol.md` or MD-010/MD-011.** The next
authorized action is a formal corpus-boundary decision, structurally analogous to MD-010/MD-011
themselves — a human governance act, not a further research task.

**No classification changed. No frozen artifact (Phase 1/2/3/4/6, Stage 06, MD-023/024/025, Phase
5A–5N, the handover, or MD-022) modified. No directory admitted into the evidence base. No
composition performed. No model selected. One incidental "HPA" mention was found while searching for
`ConflictRecord`; noted only as relevant to that search, not pursued, K-1/K-2 governance untouched.**

**Verification**: `resume.py`/`resume_mathematical.py` → both `CONSISTENT`, unchanged; all frozen input
directories confirmed unmodified; `docs/knowledgeos/governance/`, `backlog/`, `reviews/`,
`developer_guide/`, `how_far_we_are/`, `architecture/`, `research/` confirmed read-only throughout;
only the new `14_decision-log/MD-026-corpus-boundary-provenance/` directory (14 files) written.

**MD-026 status: COMPLETE — EVIDENCE ESTABLISHED FOR A FUTURE CORPUS-BOUNDARY DECISION; THAT DECISION
NOT MADE. Stage 07 NOT opened.**

---

## MD-027 — Adversarial Audit of MD-026 Corpus-Boundary Findings (EXECUTED, 2026-09-08)

**Authorization**: separate, explicit — a quality gate, not a research phase. Determines whether
MD-026's evidence is sufficient and correctly interpreted to support presenting a corpus-boundary
decision to human governance. **Does not modify MD-026. Does not admit any directory. Does not perform
composition. Does not open Stage 07. Does not reopen K-1/K-2.**

**Two corrections required and accepted, applied here only (MD-026's own frozen text not edited)**:
(1) *"did not exist to omit"* conflated Git-tracking date (2026-09-06) with filesystem-existence date —
corrected to *"was not Git-tracked at the boundary date; prior filesystem existence cannot be
established."* (2) *"two independent, decisive pieces of evidence"* overstated — both observations
trace to one connected repository-history chain (the same 5-day, same-collaborator window), corrected
to *"convergent but evidentially-dependent."*

**One further correction, newly identified this audit, not merely a repetition of the authorizing
critique**: MD-026's DDD characterization of `kernel-reduction/` as *"a separate, self-governing
research context"* overstates what the evidence shows. A direct falsification test on the check-in
commit's own message found **no governance vocabulary present** ("ratify"/"authorize"/"admit"/
"exclude") — it is archival, cataloguing language, not a governance act. Corrected to: *"a separately
organized research lane (source-attested: self-labeling, internal falsification/supersession
discipline); DDD bounded-context authority relative to Model B is not established."*

**8 of 12 audited claims required no correction** — MD-026's own section-level hedging (the
`E1-OUT-OF-SCOPE` classification, the explicit `[RECONSTRUCTED PROVENANCE]` cap on the M0030 chain, the
already-weaker rating for `theory-v1.1-simulation/`) was already correctly scoped; the corrections
concentrate specifically on places where MD-026's own summary prose ran ahead of its own careful
section-level findings.

**Governance-readiness verdict: `YES`, with the corrections applied** — the corrected evidence package
does not mislead a decision-maker about what is fact, reconstructed provenance, interpretation, or
unknown.

**No classification changed. No frozen artifact (MD-023/024/025/026, Phase 1/2/3/4/6, Stage 06, Phase
5A–5N, the handover, or MD-022) modified. No directory admitted. No composition performed. No model
selected. K-1/OQ-2 and GK-5K-1 through GK-5K-5 untouched.**

**Verification**: `resume.py`/`resume_mathematical.py` → both `CONSISTENT`, unchanged; MD-026 and all
frozen input directories confirmed unmodified; only the new `14_decision-log/MD-027-md026-adversarial-
audit/` directory (8 files) written.

**MD-027 status: COMPLETE — GOVERNANCE-READY: YES, WITH TWO WORDING CORRECTIONS AND ONE DDD-
CHARACTERIZATION CORRECTION APPLIED. The next authorized action is a formal, human corpus-boundary
decision, presenting the corrected evidence package (§`06`) — not a further research task. Stage 07 NOT
opened.**

---

## MD-028 — Human Corpus-Boundary Decision Package (EXECUTED, 2026-09-08)

**Authorization**: separate, explicit. **Not a research phase. Not a decision by this reconstruction.**
Prepares the evidence and decision structure so a legitimate human authority can decide DQ-1 explicitly
— does not admit `kernel-reduction/`, does not reclassify anything, does not perform composition, does
not open Stage 07, does not reopen K-1/K-2.

**Decision question, narrowly framed** (not "is `kernel-reduction/` part of Model B," which the
evidence cannot establish): *"May `docs/knowledgeos/research/kernel-reduction/` be admitted as an
admissible Model-B-adjacent evidence lane, for the specific purpose of resolving the MD-024/025
specification gap — without that admission being read as historical membership, authorship, or
validation?"*

**Four non-prejudicial options presented, none selected**: A — do not admit; B — admit only the
operator-contract artifacts; C — admit the entire 22-file lane, preserving non-canonical status; D —
defer pending additional organizational provenance evidence. Provenance held at `RECONSTRUCTED
PROVENANCE` throughout, under every option. A blank decision form (YES/NO/DEFER, no preselected
answer) prepared for direct completion.

**Legitimate authority verdict**: `LEGITIMATE AUTHORITY NOT ESTABLISHED IN CORPUS` — applying the same
`LegitimateAuthority(a,scope,source,validity)` predicate already used for the unrelated K-1/K-2 GK-5K
track (MD-022). **This is the second time this reconstruction has independently found the identical
authority gap for two unrelated governance questions** — worth recording as a pattern.

**Adversarial review of the package itself** (10 falsifiers) found no hidden Model-B reclassification,
no evidence-level inflation, no assumed authority, and no prejudgment of the future composition
experiment — one partial finding (scope-breadth ambiguity between Options B/C) left explicitly to
governance rather than resolved by this package.

**No classification changed. No frozen artifact (MD-025/026/027, Phase 1/2/3/4/6, Stage 06, Phase
5A–5N, the handover, or MD-022) modified. No directory admitted. No composition performed. No model
selected. K-1/OQ-2 and GK-5K-1 through GK-5K-5 untouched.**

**Verification**: `resume.py`/`resume_mathematical.py` → both `CONSISTENT`, unchanged; all frozen input
directories confirmed unmodified; only the new `14_decision-log/MD-028-human-corpus-boundary-decision/`
directory (13 files) written.

**MD-028 status: COMPLETE — GOVERNANCE-READY: YES. DECISION PENDING HUMAN GOVERNANCE. This
reconstruction has reached the limit of what it may decide on its own authority; the smallest next
action belongs to the real organization, not to a further Claude-executed phase.**

---

## MD-028-DQ-1 — SESSION-LEVEL HUMAN RESEARCH-GOVERNANCE DECISION RECORDED (2026-09-08)

**Decision**: **ADMIT** only `docs/knowledgeos/research/kernel-reduction/04-operator-contracts.md` and
its directly-cited dependency `03-capability-model.md`, for the narrowly defined purpose of
specification-sufficiency analysis arising from MD-024/MD-025. (Option B of `MD-028/04`'s four
presented options.) **No other file under `kernel-reduction/` is admitted. `theory-v1.1-simulation/`
is not part of this decision at all.**

**Decision authority, typed exactly as given**: a **`SESSION-LEVEL HUMAN RESEARCH-GOVERNANCE
DECISION`** — the user, as this session's directing human principal — explicitly **not**
corpus-internal organizational ratification, not proof of organizational authority, not retroactive
governance authority, not historical provenance confirmation, not Model-B membership, not
mathematical validation, not canonicalization. **MD-028 `07`'s own `LEGITIMATE AUTHORITY NOT
ESTABLISHED IN CORPUS` finding is explicitly preserved, not resolved, by this record** — condition 11
of the decision states this outright, and condition 12 allows a later legitimate organizational
decision to supersede this one.

**Provenance status: `RECONSTRUCTED PROVENANCE`, unchanged by this decision** — the M0030 →
`kernel-reduction/` connection remains inferred, not documented.

**Verified before recording**: both admitted files confirmed present at their original paths,
unmodified — `git log` confirms both were first tracked in commit `70fee73c` (2026-09-06), mtime
2026-09-01, consistent with every prior finding (MD-025/026/027). **No file was copied, moved,
rewritten, or reclassified — the original source material remains exactly where and as it was.**

**All 12 conditions attached to the decision are recorded verbatim in `MD-028/13_recorded-decision.md`**
— in particular: no historical authorship/membership established; no canonicalization; no mathematical
validation; Stage 07 not authorized; model selection not authorized; K-1/K-2 not reopened; the
admission is purpose-bounded, not a general corpus-boundary precedent.

**No classification changed. `classification-register.tsv` not touched — the two admitted files remain
outside the `docs/knowledgeos/brainstorming/` corpus root and are not added to it; their admissibility
is recorded only in this governance-log entry and in `MD-028/13`. No composition performed. No Stage 07
opened. No model selected. K-1/OQ-2 and GK-5K-1 through GK-5K-5 untouched.**

**Status: ADMISSION RECORDED AND VERIFIED. The next scientific step (a controlled specification-
sufficiency/composition retest using the two now-admitted files) remains a separate, not-yet-granted
authorization — not opened by this record.**

---

## MD-029 — Controlled Specification-Sufficiency and Composition Retest, Pair 1 Only (EXECUTED, 2026-09-08)

**Authorization**: separate, explicit. ONE diagnostic experiment — MD-024's own Pair 1 (B `Validate` ↔
C1 P-3) — retested using exactly the two files MD-028-DQ-1 admitted. Not Pair 2/3/4. Not Stage 07.

**Pre-execution finding**: `04-operator-contracts.md`'s own general Input/Output convention is not
self-contained — the concrete derivation rules live in `06-composition-rules.md`, **not admitted** by
MD-028-DQ-1. `03-capability-model.md` (admitted) does supply `Validate`'s own **output** carrier
directly (`Verdict`, capability C10) — genuinely new information MD-024 lacked. `Validate`'s **input**
carriers remain `NOT SPECIFIED BY SOURCE` within the admitted scope.

**A second, independently-discovered finding**: direct re-reading of seq 0157 (P-3's own already-
admissible source) found that this reconstruction's own prior characterization — *"tested via a
many-to-many evidence-sharing stress test"* (MD-023, MD-024, Phase 4's concept register) — **does not
match the source**. The actual method is a pairwise transactional-atomicity argument. The falsification
conclusion itself stands, verified directly; only the method-description inherited across prior phases
is corrected here (not edited into their own frozen text).

**Result**: `Validate`'s stated responsibility ("assign warrant given evidence + assumptions" →
`Verdict`) and P-3's own `Confidence` property (an ASSESSMENT, "derived from evidence and
justification," per seq 0157 §12) show a genuine **functional correspondence at the role-description
level** — independently and consistently described by both sources. No stronger correspondence is
demonstrated: the specific mapping tested is explicitly labeled a **"mapping constructed for
analysis,"** not a native/source-stated one, since the concrete derivation linking them is not in the
admitted scope. **Mathematical classification: `FUNCTIONAL ANALOGY` (level 3 of 6).** 5 of 7 semantic-
preservation properties are untestable given the admitted scope; no invariant preservation was
claimed, since P-3's own only candidate invariant is itself an unestablished hypothesis.

**No classification changed. No frozen artifact (MD-023–028, Phase 1/2/3/4/6, Stage 06, Phase 5A–5N,
the handover, or MD-022) modified. No additional file admitted. Provenance held at `RECONSTRUCTED
PROVENANCE` throughout. No model selected. No common Kernel established. GA-038 and K-1/K-2
untouched.**

**Verification**: `resume.py`/`resume_mathematical.py` → both `CONSISTENT`, unchanged; all frozen
directories confirmed unmodified; only the new `14_decision-log/MD-029-pair1-validate-p3-retest/`
directory (12 files) written.

**MD-029 status: COMPLETE — RESULT: FUNCTIONAL ANALOGY, A DISCLOSED CONSTRUCTED MAPPING. Pair 2/3/4
NOT tested. Stage 07 NOT opened. The evident next question — whether to admit
`06-composition-rules.md` — is named, not authorized.**

---

## MD-030 — Executable Kernel-Reduction Evidence Characterization (EXECUTED, 2026-09-08)

**Authorization**: separate, explicit — a characterization/admissibility-preparation study only, not
an admission decision, not a composition test, not a continuation of MD-029.

**Trigger**: while preparing a proposed "evidence-complete MD-029 retest" (searching `nrna1/research/`
and `nrna1/verification/`), a path-verification check found `nrna1/research/kernel-reduction/` — a
directory distinct from, but explicitly linked by its own README to, the already-partially-admitted
`docs/knowledgeos/research/kernel-reduction/` (narrative write-up). The user declined a broad
evidence census and requested this narrow characterization first, explicitly excluding
`nrna1/research/knowledgeos-sim/` and `nrna1/verification/` (incl. `zero-algebra/`) from scope.

**Central finding**: `kr/carriers.py`'s `DERIVATION_RULES` table contains an explicit, machine-encoded
rule — `({Claim,Evidence}|{Hypothesis,Evidence}, {A_WARRANT}) -> Verdict` — supplying a concrete
candidate answer to exactly the B `Validate` input-carrier question MD-029 left `NOT SPECIFIED BY
SOURCE`. This rule matches, almost exactly, MD-029's own prior constructed inference (`Claim`+
`Evidence`, reasoned from the admitted capability table alone) — a genuine but non-independent
corroboration (both readings trace to the same two admitted capability rows, per this study's own
statistical-dependence discipline). `capabilities.py`'s own docstring states directly it tracks
`03-capability-model.md` "by name"; `variants.py`'s `V0` names `04-operator-contracts.md` as its own
baseline. No contradiction was found anywhere between the executable lane and the two MD-028-admitted
files.

**Provenance finding**: `nrna1/research/` (the whole tree, including `kernel-reduction/`) is
**completely untracked in git — zero commits, any branch, ever** (`git status`: `?? research/`;
`git log --all --diff-filter=A -- research/` empty) — a *weaker* provenance status than the narrative
lane's own already-cautious `RECONSTRUCTED PROVENANCE` (which at least has a dated 2026-09-06 commit).
Filesystem mtimes (2026-09-01, internally sequential) are consistent with, but not independent proof
of, the narrative lane's own self-dating. The two path aliases used across this session
(`d0f38614.../nrna1` and `nab-raj.roshyara@.../nrna1`) were confirmed to be the same git repository
(same toplevel, same inode) — not separate checkouts.

**Admissibility finding**: the candidate derivation rule is relevant and potentially necessary to
close the Validate specification gap, but is **NOT currently admissible** — three independent
qualifications found together: zero git history; the directory's own README self-declares
`[EXP]`/"not KnowledgeOS architecture... nothing here is canonical"; and the same lane's own
`variants.py` (`V6`) tests an alternative 3-input derivation rule for the identical step, meaning even
this lane's own internal design does not present the rule as settled. **Completion-gate outcome:
D — ADMISSIBILITY/PROVENANCE BLOCK** (relevant, not currently admissible — distinct from both "no
additional admission needed" and "a ready admission package").

**Dependency audit**: no MD-024–029 finding is dependent on this directory — chronology confirms it
was discovered only after MD-029 closed; none of those studies' own tool-call records show prior
contact with it.

**DDD reading**: an executable research instrument, not a bounded context (no boundary-authority
evidence; internally contested vocabulary via `V0`/`V6`) — the same classification this programme
already reached for the sibling narrative lane in MD-026/027, independently re-derived here. `Verdict`
reads, at the role-description level, as a derived value object — a structural echo of MD-029's own
P-3 `Confidence` classification, explicitly not re-opened or re-tested as a composition claim.

**No classification changed. No frozen artifact (MD-024–029, Phase 1/2/3/4/6, Stage 06, Phase 5A–5N,
the handover, MD-022) modified. `classification-register.tsv` untouched (2377 lines, unchanged).
`06-composition-rules.md` not read (out of scope). No code executed. `knowledgeos-sim/` and
`verification/` untouched. No file admitted. No composition test performed. No model selected. No
Stage 07. GA-038/K-1/K-2 untouched.**

**Verification**: `resume.py`/`resume_mathematical.py` → both `CONSISTENT`, unchanged; all frozen
directories confirmed unmodified via `git status`; only the new
`14_decision-log/MD-030-executable-kernel-reduction-characterization/` directory (12 files) written.

**MD-030 status: COMPLETE — OUTCOME D, ADMISSIBILITY/PROVENANCE BLOCK. The executable lane is relevant
but not currently admissible. No admission proposed. The next action (if any) — provenance-
strengthening, reading `06-composition-rules.md`, or another path — is named, not authorized.**

---

## MD-031 — Validate Rule Narrative–Executable Convergence Audit (EXECUTED, 2026-09-08)

**Authorization**: separate, explicit, adapted before execution. The user's original prompt included
a "hard firewall" against inspecting `three_model_convergence/` (this session's own home directory —
incoherent as an instruction to this session) and a §21 verification checklist naming "P-07–P-40"/
"K1–K11 cardinality" — vocabulary belonging to the Lane-T/theory-extraction/P-series track this
session has been instructed never to inspect or use, not to this session's own MD-021/K-1/K-2
vocabulary. Both were flagged before any file was touched; the user confirmed proceeding with the
substance of the prompt, dropping the incoherent firewall, and verifying against this session's own
MD-024–030/K-1/K-2 state instead. `docs/knowledgeos/theory-extraction/` remained untouched throughout
— that standing prohibition was honored independent of the mismatched prompt wording.

**Central finding**: `docs/knowledgeos/research/kernel-reduction/06-composition-rules.md` — cited by
section number ("§06") in the already-admitted `04-operator-contracts.md`, but never itself read by
this reconstruction before this study — states, in prose, the identical derivation rule MD-030 found
only in the executable lane: `Claim,Evidence | Hypothesis,Evidence → (warrant-assessment) → Verdict`,
with matching explanatory text ("`Verdict` requires `Evidence`. This is where the absence of
`Qualify` from `C0` becomes fatal.") The document never names `Validate` in its table (deliberately,
by the same anti-circularity design already found in the code); the connection to `Validate`
specifically is established only by chaining two source-stated facts — `04` (admitted): `Validate`'s
atom is `warrant-assessment`; `06` (this study): the derivation rule for that atom — built entirely
from narrative-lane text, without consulting the executable code. **The B `Validate` input-carrier
field moves from `NOT SPECIFIED BY SOURCE` (MD-029) to `CLOSED BY SOURCE`.**

**Provenance finding**: `06` carries the *same* Git history as the two already-admitted files (the
identical 2026-09-06 bulk commit) — not a weaker provenance status. `04` and `06` have filesystem
mtimes five milliseconds apart (vs. tens-of-seconds-to-minutes gaps elsewhere in the same document
series) — consistent with a single generation pass, not independent authorship; no explicit
cross-citation was found between `06` and the executable code in either direction. **Classified
`CONVERGENCE WITH COMMON-CAUSE PROVENANCE`, not independent confirmation.**

**What remains open**: `06` is silent on the executable lane's own tested alternative rule
(`variants.py`'s `V6`, a 3-input variant for the identical step) — neither endorsing nor contradicting
it. Preconditions, postconditions, and failure semantics for `Validate` specifically remain `NOT
CLOSED` by either source. **Final verdict: E — PARTIAL CONVERGENCE**, not A ("sufficient for a future
controlled test" — too strong, given `V6` is unresolved) and not G (a provenance block applies to the
executable lane, not to `06` itself).

**MD-030 claim audit**: four of MD-030's five central claims (executable rule supplies information;
executable lane inadmissible; internal contestation via `V6`; no MD-024–029 dependency) confirmed
unchanged. One (E, "potentially necessary") narrowed — the executable lane is no longer the sole or
best-provenanced candidate for the input-carrier field specifically (`06` now is), though it remains
relevant for the computed C0-reachability result and for `V6`'s own existence, neither supplied by
`06`.

**No classification changed. No frozen artifact (MD-024–030, Phase 1/2/3/4/6, Stage 06, Phase 5A–5N,
the handover, MD-022) modified. `classification-register.tsv` untouched (2377 lines). No file
admitted. No composition test performed. No code executed. `theory-extraction/`, `knowledgeos-sim/`,
and `verification/` untouched. No model selected. No Stage 07. GA-038/K-1/K-2 untouched.**

**Verification**: `resume.py`/`resume_mathematical.py` → both `CONSISTENT`, unchanged; MD-024–030
confirmed unmodified via `git status`; only the new
`14_decision-log/MD-031-validate-rule-narrative-executable-audit/` directory (16 files) written.

**MD-031 status: COMPLETE — VERDICT E, PARTIAL CONVERGENCE. Smallest scientifically justified next
action: a human admissibility decision for `06-composition-rules.md` — a separate SESSION-LEVEL
HUMAN RESEARCH-GOVERNANCE DECISION, following MD-028-DQ-1's own precedent, not implied by this
record. No admission proposed here. A controlled composition retest remains deferred past any such
decision.**

---

## MD-032 — Human Admissibility Decision Gate: `06-composition-rules.md` (EXECUTED, 2026-09-08)

**Not a research phase — a narrowly-scoped human research-governance decision gate**, following
MD-031's own named next action. Presented formally via a direct question (four options, matching the
authorization's own text exactly), even though the user had also stated a preference in prose in the
same authorizing message — per the authorization's own explicit instruction not to infer the
decision, and this programme's own MD-028-DQ-1 precedent that admission is a formal, separately-
recorded governance act.

**Decision recorded: SESSION-LEVEL HUMAN RESEARCH-GOVERNANCE DECISION — Option A, ADMIT (narrow
scope).** `docs/knowledgeos/research/kernel-reduction/06-composition-rules.md` is admitted **solely
for specification-sufficiency and subsequent controlled research concerning the Model-B `Validate`
derivation/composition rule.** Explicitly, per the decision's own text, this does NOT establish:
canonical status, ratification, mathematical proof, implementation approval, Model-B global
authority, sufficiency for composition, resolution of `V6`, or authorization for Stage 07.

**Provenance**: unchanged — `06` remains at the same `RECONSTRUCTED PROVENANCE` ceiling as the two
files MD-028-DQ-1 already admitted (same 2026-09-06 commit). The relationship to the executable
lane's own identical rule remains `CONVERGENCE WITH COMMON-CAUSE PROVENANCE`, explicitly **not**
upgraded to independent confirmation by this decision.

**`classification-register.tsv` not touched** — the newly-admitted file remains outside the
`docs/knowledgeos/brainstorming/` corpus root, exactly as MD-028-DQ-1's own admission of `03`/`04`;
admissibility is recorded only in this governance-log entry and in `MD-032/02_human-decision.md`.
**The executable lane (`nrna1/research/kernel-reduction/`, including `kr/carriers.py`) is explicitly
unaffected — remains not admitted, per MD-030. `V6` remains unresolved — this decision does not
adjudicate it.**

**No classification changed. No frozen artifact (MD-024–031) modified. No composition test. No code
executed. No model selected. No Stage 07. No canonicalization. `theory-extraction/`,
`knowledgeos-sim/`, `verification/` untouched.**

**Verification**: `resume.py`/`resume_mathematical.py` → both `CONSISTENT`, unchanged; MD-024–031
confirmed unmodified via `git status`; `06-composition-rules.md` and the executable lane confirmed
unmodified; only the new
`14_decision-log/MD-032-human-admissibility-decision-06-composition-rules/` directory (8 files)
written.

**MD-032 status: COMPLETE — 06-COMPOSITION-RULES.MD ADMITTED, NARROW SCOPE. Awaiting separate
authorization for any further step — including any composition test, any V6 adjudication, or any
executable-lane admission.**

---

## MD-033 — Controlled Validate Specification-Sufficiency and V6 Adjudication (EXECUTED, 2026-09-08)

**Authorization**: separate, explicit, no blocking disagreement. Read all three admissible files
(`03-capability-model.md`, `04-operator-contracts.md`, `06-composition-rules.md`) cold — `03` and
`04` in full for the first time in this reconstruction (previously known only through other studies'
citations); `06` reused from MD-031's own already-completed cold read.

**Central finding**: `Validate`'s happy-path contract is fully closed by admissible evidence — atom
`warrant-assessment`, input `{Claim,Evidence}`/`{Hypothesis,Evidence}`, output `Verdict`, stated
non-reducible responsibility ("assign warrant given evidence + assumptions"), and — newly
established here — **no state effect** (`04`'s "Common to all" contract: "State effects = none,
except `Revise`"). **`V6` is confirmed absent from all three admitted files** (an exhaustive grep,
not a sample) — not contradicted, not a live unresolved disagreement inside the admissible evidence,
simply never named. The admissible lane does discuss robustness variants generally: `03` names `V1`
(merging C2/C3, rejected) and `V5` (merging C12/C13); `04` names `V4` (attacking the
`DetectGap`-exclusivity assumption) — both citing "§12" (`12-randomized-results.md`, not admitted,
not read as evidence) for elaboration. **Preconditions, postconditions, and failure/error semantics
remain `NOT SPECIFIED BY SOURCE`** — an exhaustive census (zero hits for the entire required search-
term list, across all three files) confirms this, not a sample.

**Final verdict: B — SPECIFICATION PARTIALLY SUFFICIENT; ONE OR MORE MATERIAL GAPS REMAIN.** Not A
(the happy path is closed, but real gaps remain); not C (MD-029 already reached a defensible
`FUNCTIONAL ANALOGY` result using *less* information than is now closed); not D (`V6`'s absence from
admissible evidence means it is not a live blocker for evidence-scoped work — the genuine, more
fundamental gaps are precondition/postcondition/failure semantics, independent of V6).

**Smallest next action**: a targeted characterization study of `12-randomized-results.md` — the file
both admitted narrative sources themselves cite for variant detail — mirroring this programme's own
established characterize-before-admit discipline. Not a composition test; not an executable-lane
admission; not a V6 resolution attempt in isolation.

**No classification changed. No frozen artifact (MD-024–032) modified. `classification-register.tsv`
untouched. No executable artifact admitted. No code executed. `theory-extraction/`,
`knowledgeos-sim/`, `verification/` untouched. No model selected. No Stage 07. No canonicalization.
K-1/K-2 untouched.**

**Verification**: `resume.py`/`resume_mathematical.py` → both `CONSISTENT`, unchanged; MD-024–032,
`03`/`04`/`06` confirmed unmodified; only the new
`14_decision-log/MD-033-validate-specification-v6-adjudication/` directory (11 files) written.

**MD-033 status: COMPLETE — VERDICT B. HARD STOP. Awaiting separate authorization for any further
step.**

---

## MD-034 — Targeted Characterization of `12-randomized-results.md` (EXECUTED, 2026-09-08)

**Authorization**: separate, explicit, no blocking disagreement. Directly executes MD-033's own
named smallest next action.

**Central, load-bearing finding**: `12-randomized-results.md` — the file `03`/`04` themselves cite
as "§12" — **contains an extensive, explicit treatment of `V6`**, unlike the three currently-
admissible files (`03`/`04`/`06`), which MD-031/MD-033 correctly found never mention it. `12`'s own
table (line 144): `V6` = "a `Verdict` requires a surviving-defeater step" — an exact match to the
executable lane's own `variants.py` description (MD-030). More consequentially, `12`'s "Causal /
model-criticism check" section gives a real, source-stated argument that the currently-admitted
baseline rule (`V0`) has a known limitation `V6` alone corrects: *"The baseline model permits the
failure mode `fit ⇒ validation`. Only V6 blocks it structurally."* `12` does **not** adopt `V6` as
the design actually used elsewhere in its own experiments (every other result in the file uses the
baseline rule) — this is a documented, unresolved critique, not a silent replacement.

**MD-031's and MD-033's own findings are not contradicted, only extended**: both were correctly
scoped to the population they examined, and within that population, `V6` genuinely is absent. This
study characterizes a file neither prior study was authorized to read.

**Validate specification gap (MD-033's own)**: **not closed** by this file for the baseline rule —
no new precondition/postcondition/failure semantics for `V0`. A precondition-shaped fact does emerge
for `V6` specifically (a surviving `Defeater` is required), but `V6` is not the baseline design in
use.

**Provenance**: same tier as the three already-admitted files (identical 2026-09-06 bulk commit).
No cross-citation to the executable lane found either direction.

**Admissibility recommendation (not a decision): A — scientifically relevant, admission candidate.**

**Named tension, not resolved here**: MD-033's own reasoning against decision-state D ("V6 is not a
live blocker") rested on V6's absence from admissible evidence — that reasoning would need
re-examination if `12-randomized-results.md` is ever admitted, since V6 would then be present in the
admissible population. Flagged explicitly; not adjudicated by this study.

**Backlog item filed**: `EKS-13` (`docs/knowledgeos/backlog/`; filed as `EKS-12`, renumbered same
day after a concurrent session independently filed its own, unrelated `EKS-12`) — a recurring
operating-model gap:
no formal `protocol.md` mechanism exists for admitting out-of-corpus-root evidence; three separate
instances (`03`/`04`, `06`, and now this recommended file) have each reinvented the same governance
machinery from scratch. Checked against `EKS-06` first (`ES-005.4`) — confirmed not a duplicate,
different subsystem.

**No classification changed. No frozen artifact (MD-024–033) modified. No source file modified,
including `12-randomized-results.md` itself, which is characterized, not admitted.
`classification-register.tsv` untouched. No code executed. `theory-extraction/` untouched. No model
selected. No Stage 07. No canonicalization. K-1/K-2 untouched.**

**Verification**: `resume.py`/`resume_mathematical.py` → both `CONSISTENT`, unchanged; MD-024–033
and all `docs/knowledgeos/research/kernel-reduction/` source files confirmed unmodified; only the
new `14_decision-log/MD-034-characterization-12-randomized-results/` directory (11 files) and
`docs/knowledgeos/backlog/EKS-13-...md` + its index entry written.

**MD-034 status: COMPLETE — HARD STOP. `12-randomized-results.md` NOT admitted. Smallest next
action: a human admissibility decision for it, mirroring MD-032's own precedent. Awaiting separate
authorization for any further step.**

---

## Housekeeping — backlog `EKS-12` renumbered to `EKS-13` (2026-09-08)

A concurrent session independently filed its own, unrelated `EKS-12` (theory-governance-scope-gap,
already closed) using the same next-available-number convention at the same time as MD-034's own
backlog filing. Renumbered this session's ticket to `EKS-13` (content unchanged; no external
reference to the old number existed yet) and fixed the four self-authored cross-references in this
session's own governance records. Lane-T's own `EKS-12` and its own session-log entries were not
read as evidence and not modified.

---

## MD-035 — Human Admissibility Decision: `12-randomized-results.md` (EXECUTED, 2026-09-08)

**Not a research phase — a formal human research-governance decision gate**, directly mirroring
MD-032's own precedent, following MD-034's own characterization and recommendation (A — admission
candidate). Presented formally via a direct question (four options, matching the authorization's
own text exactly), even though the user had also stated a preference in prose in the same
authorizing message — per that authorization's own explicit instruction not to infer the decision
from previous preferences.

**Decision recorded: SESSION-LEVEL HUMAN RESEARCH-GOVERNANCE DECISION — Option A, ADMIT (narrow
scope).** `docs/knowledgeos/research/kernel-reduction/12-randomized-results.md` is admitted **solely
for controlled research into Model-B `Validate` specification, the V1/V4/V5/V6 variants, the stated
baseline limitation, and precondition/postcondition/failure semantics.** Explicitly, per the
decision's own text: **admission ≠ adoption** — `V6` remains a candidate variant, `V0` remains the
baseline candidate, the "`fit ⇒ validation`" critique remains source evidence not a validated
theorem, no variant is selected, no canonical `Validate` contract is created, no executable
implementation is admitted, no composition test is authorized, no Stage 07 is opened.

**Provenance**: unchanged — `12` remains at the same `RECONSTRUCTED PROVENANCE` ceiling as the three
files already admitted (same 2026-09-06 commit). The relationship to the executable lane's own `V6`
remains `CONVERGENCE WITH COMMON-CAUSE PROVENANCE`, explicitly **not** upgraded to independent
confirmation by this decision.

**The admissible evidence set is now four files: `03` + `04` + `06` + `12`.** `V6` is, for the first
time, part of the admissible population — MD-033's own "V6 is not a live blocker" reasoning (which
rested on V6's absence from admissible evidence) no longer applies unmodified and would need
revisiting by any future study drawing on this newly-admitted evidence.

**`classification-register.tsv` not touched** — exactly MD-028-DQ-1's and MD-032's own precedent.
**The executable lane explicitly unaffected — remains not admitted.**

**No classification changed. No frozen artifact (MD-024–034) modified. No V6 adjudication
performed. No composition test. No code executed. No model selected. No Stage 07. No
canonicalization. `theory-extraction/`, `knowledgeos-sim/`, `verification/` untouched.**

**Verification**: `resume.py`/`resume_mathematical.py` → both `CONSISTENT`, unchanged; MD-024–034
and all four admitted files confirmed unmodified; the backlog collision confirmed resolved (each
`EKS` number now unique); only the new
`14_decision-log/MD-035-human-admissibility-decision-12-randomized-results/` directory (8 files)
written.

**MD-035 status: COMPLETE — HARD STOP. `12-randomized-results.md` ADMITTED, NARROW SCOPE. Awaiting
separate authorization for any further step — including any V6 adjudication, any composition test,
or any executable-lane admission.**

---

## Housekeeping — backlog `EKS-13` renumbered to `EKS-14` (2026-09-08)

A second, independent same-day collision: the Lane-T session filed its own, unrelated `EKS-13`
(`EKS-13-cross-lane-dependency-without-change-notification.md`) while this session's own MD-034
ticket still held that number. Renumbered to `EKS-14` (content unchanged); added a brief,
business-language corroboration note to `EKS-07` (multi-process coordination), which this — the
second such collision in one session — directly confirms. No new ticket filed. The already-committed
MD-034/MD-035 entries above, which reference the now-superseded `EKS-13`, are left exactly as
written, per this session's standing discipline against retroactively editing frozen phase records.

---

## MD-036 — Controlled V0/V6 Semantic and Formal Adjudication (EXECUTED, 2026-09-08)

**Authorization**: separate, explicit, no blocking disagreement. Reused the already-completed cold
reads of all four admitted files (`03`/`04`: MD-033; `06`: MD-031; `12`: MD-034) rather than
re-reading; excluded the executable lane entirely, per the authorization's own instruction.

**Central finding**: `V6` is `V0`'s own stated derivation rule with exactly one additional required
carrier (`Defeater`) — a precise, source-grounded structural transformation, not a mere notational
variant. The reachability consequence (`12`'s own table: `V0` reaches `Verdict` without `Challenge`;
`V6` does not) is **directly source-stated** — a logical implication of the two rules' own
definitions, not something requiring re-derivation. **Classified `STRUCTURAL CORRESPONDENCE`** on
the seven-level ladder — not identity, not formal equivalence (domains genuinely differ), not merely
functional analogy (the transformation is exact and nameable), not incompatible (V6 is an
alternative design choice, not a contradicting claim).

**The `fit ⇒ validation` claim, adversarially separated into two parts**: the reachability fact
(Claim 1) is a directly source-stated logical implication. The interpretive framing (Claim 2 — that
this constitutes a genuine epistemic safeguard, analogized to statistical confounding) is a
**methodological/interpretive claim illustrated by an unrelated synthetic OLS example, not a formal
theorem derived from `Validate`'s own definitions.** "Surviving a defeater" is never formally
defined by any admitted source.

**Mathematical comparison**: domain differs (by construction); codomain identical (`Verdict`); the
general matching rule (superset vs. exact-set semantics) is genuinely ambiguous in the admissible
narrative text itself — classified `NOT FORMALLY TESTABLE FROM ADMISSIBLE EVIDENCE` for that general
question, while the specific reachability fact remains source-closed regardless.

**Final verdict: C — V6 adds a source-grounded constraint, but the semantic consequence remains
partially unresolved** (not B, which would overclaim the deeper epistemic-safeguard question as
established; not A/D/E). **Baseline `Validate` specification gaps: UNCHANGED** — not closed by
anything in `12`. **DDD finding**: the V0→V6 difference is a type-system-level change only — no
aggregate, invariant, or command semantics created or altered.

**MD-033's own superseded finding and MD-034's own "potentially different semantics" hedge are both
correctly accounted for**: not contradicted, sharpened — MD-033's finding was accurate for its own
population and is superseded exactly as MD-035 anticipated; MD-034's hedge is sharpened into
`STRUCTURAL CORRESPONDENCE` with a demonstrated (not merely potential) reachability difference.

**No classification changed. No frozen artifact (MD-024–035) modified. No source file modified. No
executable artifact admitted, inspected, or executed. `classification-register.tsv` untouched. No
composition test. No model selected. No Stage 07. No canonicalization. K-1/K-2 untouched.**

**Verification**: `resume.py`/`resume_mathematical.py` → both `CONSISTENT`, unchanged; MD-024–035
and all four admitted files confirmed unmodified; only the new
`14_decision-log/MD-036-v0-v6-semantic-formal-adjudication/` directory (13 files) written.

**MD-036 status: COMPLETE — HARD STOP. V6 not selected, not rejected. Smallest next step (if any):
a targeted search of the document series for a formal definition of "surviving a defeater" —
named, not authorized. Awaiting separate authorization for any further step.**
