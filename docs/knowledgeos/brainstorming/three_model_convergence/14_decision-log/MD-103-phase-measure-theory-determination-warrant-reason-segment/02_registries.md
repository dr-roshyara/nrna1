# MD-103 §02 — Duplicate/Provenance Register, Self-Correction Ledger, Negative-History Register,
External-Theory Register, Cross-Lane Transfer Register

## Duplicate/Provenance Register

- **Position 25 (`extraction-retrieves-material-determination-establishes-warrant.md`) is a full
  internal self-duplicate.** Verified directly: the file's entire content (approx. 311 lines) appears
  twice, back-to-back, separated by a bare `#` line. This is byte-for-byte the same text repeated once
  within a single file — a NEW duplication shape, distinct from both previously catalogued patterns:
  (a) MD-101's cross-file exact duplicates (two separate files, identical content, one `md5sum`); (b)
  MD-102's within-file partial-tail duplicate (a large cumulative file's own tail section matches a
  SEPARATE, smaller file's whole content). Here it is one file whose own full content is pasted twice
  into itself — most plausibly an artifact of the conversation-logging/save tool re-writing cumulative
  content and inadvertently duplicating a save event, rather than new authored content. **Not currently
  cross-referenced against MD-101's own 33-group duplicate register** (this file was not among the
  files that register's `md5sum` scan grouped, since the register covers cross-file duplication, not
  within-file self-duplication) — flagged as a gap for any future duplicate-census refresh, not chased
  further this phase, per the standing instruction not to run expensive full pairwise comparisons.
  **Evidentiary consequence**: the content was read and logged once (not double-counted), consistent
  with the "byte-identical counts once" statistical discipline.
- Positions 26-30 verified NOT exact duplicates of any file in MD-101's own 33-group register (direct
  content inspection; none match a previously catalogued group's filename pattern or content).
- **Provenance chain for positions 29/30 resolved directly, not assumed from timestamp order alone**:
  position 30 (`conditional-evidence-problem-research-synthesis.md`, saved 23:57:54) is the actual
  uploaded AI-authored ("Perplexity"-style) synthesis document (confirmed by its own structured
  FACT/LIMITATION-labeled prose, matching MD-102's own already-established "Perplexity" classification
  precedent); position 29 (`correction-to-the-synthesis-conclusion.md`, saved 23:57:38, sixteen
  seconds EARLIER on disk) is a human-authored critique that explicitly opens "I reviewed the synthesis
  you uploaded." The 16-second gap where the critique's own save timestamp precedes the synthesis
  document's own save timestamp is recorded as a save-order-vs-logical-order artifact of the
  conversation-logging tool (the synthesis was plausibly generated/uploaded and read before either file
  was written to disk, with the disk-write order not matching authorship/reading order) — NOT evidence
  of any logical contradiction or of the critique preceding its own subject in real time.

## Self-Correction Ledger

1. **Position 26→27→28 (implicit, narrative self-correction, not a formal retraction)**: the Fact
   formula is revised three times in close succession (`F=D(O,P,M,A,H)` → `Fact(p)⇐Warrant(p|R,C,t)`,
   `F=(p,reason,context,time,provenance)` → `F=(p,v)` → `Fact_t(p|C,R,E)`), each introduced as "a
   better formulation of the position" or "an important correction," per the files' own titles — a
   continuous same-conversation refinement chain, not independent competing claims from separate
   sources. Recorded as REFINEMENT/APPARENT CONTINUATION, per the discipline distinguishing this from
   an explicitly declared identity or supersession.
2. **Position 28's own explicit revision of "ideal Knowledge"**: pos28 directly states "Earlier we
   thought: `K_t^*(p)∈{0,1}`. I now think we should not assume that" — a self-correction against
   MD-102's own closure-operator gloss of `K_t^*`, recorded as a second, competing formulation (see
   `01_objects-and-theorystates.md`), not a silent overwrite.
3. **Position 29's own explicit challenge to "raw evidence"**: pos29 directly critiques the position-30
   synthesis's own claim that "the Kernel preserves raw evidence: observations, assertions,
   measurements," calling the term "dangerous terminology" and proposing the Evidence-as-relation
   reclassification instead — a genuine within-segment critique of an adjacent document, both retained.

## Negative-History Register

- **`Acceptance`**: SEARCHED-NOT-FOUND as a distinct named lexical/formal object across positions
  25-30 (bounded to this segment; `Admission` occupies a structurally similar narrative role instead).
- **F4 formal family** (`Sat`, `Det_r`, `EvalReq`, `EC`, `EC_t`, `Γ`, `Δ_t`, `≡_sem`, `⪯_cap`,
  `MinKer`, `v1.3`): SEARCHED-NOT-FOUND across all six files (verified via direct `grep`, zero hits
  for every tracked token in every file) — extends the continuous negative boundary already
  established across `kernel/` (172 files) and `phase_measure_theory/`'s own prior 24 positions
  (MD-100-102).
- **`Rule`/`Criterion`/`Material` as independently TYPED objects**: NOT-FOUND-IN-INSPECTED-POPULATION
  this segment — each remains component-role-only (see `01_objects-and-theorystates.md`).

## External-Theory Register

- No new independently-citable external source this phase. Position 30's own content restates KST
  (Doignon & Falmagne, already tracked as EXTERNAL SOURCE, NOT ADOPTED since MD-101) plus a standard
  landscape survey (Bayesian probability, Dempster-Shafer theory, fuzzy/many-valued logic,
  non-monotonic logic, argumentation theory [Dung], epistemic logic, temporal logic, measurement
  theory) — all classified EXTERNAL THEORY, RELATED/EXTERNAL — NOT ADOPTED, consistent with the
  standing five-way classification (CORPUS-NATIVE / EXTERNAL SOURCE / RECONSTRUCTION / HYPOTHESIS /
  RECOMMENDATION). Position 30 itself is classified RECONSTRUCTION-ADJACENT COMMENTARY (in-programme
  AI synthesis), not EXTERNAL SOURCE and not CORPUS-NATIVE THEORY.

## Cross-Lane Transfer Register

- No new cross-lane content transfer found this phase (this segment is entirely internal to
  `phase_measure_theory/`'s own continuous conversation).
- One METHODOLOGICAL echo noted, NOT counted as cross-lane content transfer: the "Kernel preserves
  substrate; regimes interpret" framing recurs here (pos26/29) within the SAME author's SAME
  conversation as earlier occurrences in this same lane — this is continuation within one source, not
  independent cross-lane corroboration, and is explicitly distinguished from the genuinely cross-lane
  recurrence of a structurally similar principle already noted in MD-096/098 ("Kernel preserves;
  regimes reason," found independently in `kernel/`).
