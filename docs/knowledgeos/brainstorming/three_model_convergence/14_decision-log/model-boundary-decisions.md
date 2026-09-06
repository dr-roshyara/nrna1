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
