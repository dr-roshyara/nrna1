# S5 Post-Pilot Architecture Decision Brief (preparation for a human ruling; nothing implemented)

**Scope:** S-Series only. **Production frozen:** OB0004 FAILED; 395 batches PREPARED; no batch authorized; H-19 SEALED; S5c PROHIBITED. No production contract, manifest, reader or state was changed for this brief.

**Sources:**
- `audit-p3b/S5-DECOMPOSITION-PILOT-REPORT.md` and G-LOG-0053;
- the pilot records under `pilot-s5-decomp/` (unit records, synthesized objects, `PX0004-A01/audit-report.json`);
- new read-only measurements made for this brief. Stored records only, plus content sniffing of required files through the seal-aware `discovery_resolver`: a checker read, not an agent read, and counts only.

**The claim used throughout.** Deterministic decomposition solved the demonstrated mechanical capacity problem and passed the frozen pilot criteria. But the pilot identified four cross-unit synthesis losses, and it did not establish epistemic equivalence with single-context analysis across multiple units.

## A. Established facts (from the pilot, not re-run)

| Level | Status |
|---|---|
| **A. Mechanical correctness** (every required page proven read) | **demonstrated**: control 6/6 files, target 39/39 files (1,760,662 bytes, 3 units, 106 pages); 0 missing, 0 duplicate, 0 hash failures; provenance complete; cross-session continuation worked; production verifier PASS on both synthesized objects |
| **B. Synthesis correctness** (unit results combined correctly) | **partly demonstrated**: both objects pass the verifier, and the target audit found 0 protocol violations. But the auditor found four loss mechanisms, three minor miscount or ordering errors, and cross-unit inconsistencies (§C) |
| **C. Epistemic equivalence** (same judgment as a single analyst) | **limited evidence**: one single-unit control (19 exact / 6 coarse / 4 different; 0 BASELINE-UPHELD). No multi-unit control exists |

**New measured facts for this brief (population-wide, required files = stage-2 files plus OMQ-14 sources):**
- **Files:** 2,558 required files in total. **12 are binary or non-text** (2 PNG, 3 ZIP, 7 containing NUL bytes), 1,731,013 bytes. All 12 are required only as **stage-2 hit files**, and 41 labels touch one.
- **Pages:** in every required **text** file, the largest 20,000-character page is **25,041 UTF-8 bytes**. **No text page exceeds 30,000 bytes.**
- **File size:** the largest required **text** file is **241,117 bytes** (p99 91,710). **No required text file exceeds 1 MB.** The "largest required file" of the load brief (S2276, 1.52 MB) is binary.
- **Labels:** measured on text-only required load, **82 labels exceed 1,088,267 bytes** (the completed reading of one context in R2.3). The maximum is 7,099,965 bytes. Label-level decomposition remains the capacity question; single files do not.

## B. Non-established claims

- Epistemic equivalence of synthesis across units (no multi-unit control with a single-analyst baseline).
- Fidelity on the heaviest labels (up to 7.1 MB of text).
- Agreement under an auditor from a different model family (all agents and auditors were claude-opus-5-5).
- Whether the four loss mechanisms ever change a **label-level** resolution. They did not in the pilot, which is one label.
- How binary stage-2 hits should be treated (§E).
- That a paged read was **consumed**, not only delivered. The ledger proves delivery (§F).

## C. The four information-loss findings, verified from stored evidence

| Finding | Evidence (stored records) | Real loss? | Scope | Mitigation candidates | Residual risk |
|---|---|---|---|---|---|
| **1. Cross-unit change classes** | Units assign `change_candidate` only against files in their own unit; S1513 (U03) was NOT-COMPARABLE and the synthesis made it EXTENDS. The synthesis assigned **EXTENDS to 31 of 33 points** from record quotes, without comparing texts across units. The auditor judged cross-boundary classes JUDGMENT-CALL | **Yes.** The unit is the wrong scope for a comparison between successive files. The uniform EXTENDS suggests the default filled the gap | per-file timeline classes; could reach births and `CHANGES-*` timeline points (whole-file claims), and so status | (a) deterministic boundary context: each unit also receives the preceding file of the global order as a **comparison-only** read; (b) a synthesis-time reconciliation pass comparing adjacent points across units from records; (c) a mandatory re-read by the synthesizer of both files at each cross-unit adjacency whose class is not EXTENDS or RESTATES | (a) adds reading load and re-introduces overlap. (b) stays record-limited. (c) is bounded by the number of unit boundaries (units − 1 adjacencies per timeline) |
| **2. Disposition vs absence finding** | Reproduced mechanically: **9 conflicts**, all on `dependencies`: a stage-2 disposition of FOUND while the **same record's** `absence_evidence` says only MENTIONS (S1438 ×2, S1439, S1441 ×2, S1444, S1536 in U04; S1531, S1541 in U02) | **Yes, but correctly placed it is an intra-record inconsistency.** Decomposition's contribution is that the synthesizer cannot adjudicate without reading. A single analyst could hit the same inconsistency but would resolve it in context | per-key dispositions; absence resolution FOUND/GENUINELY-UNDEFINED (dependencies was FOUND regardless, via S1537) | **mechanically detectable invariant:** *for each (file, dimension), a FOUND disposition requires a DEFINES-class finding in the same record, and a DEFINES finding forbids UNSUPPLIED / FALSE-HIT; a violation is not allowed to survive synthesis unresolved (re-read, or disposition ESCALATED)*. Also a cross-unit form: *no final GENUINELY-UNDEFINED may survive if any unit recorded a DEFINES finding for that dimension* | the invariant catches inconsistency, not wrong-but-consistent judgments |
| **3. Cross-unit calibration** | Per-unit FOUND rates for stage-2 dispositions: **dependencies U02 4/4, U03 1/8, U04 11/21; invariants 4/4, 3/8, 14/21**. The files differ, so rates alone are not proof. But the auditor read both S1518 (U03, dependencies UNSUPPLIED) and S1531 (U02, FOUND) and confirmed near-identical front matter judged differently | **Yes: a missing shared calibration mechanism on top of normal model variance.** A single analyst has one (possibly wrong) calibration; k units have k | per-key dispositions; could move GENUINELY-UNDEFINED ↔ FOUND if a dimension's only evidence sits in a disagreeing unit | (a) canonical calibration examples in the unit contract (what counts as DEFINES vs MENTIONS per dimension); (b) shared classification rules; (c) a **post-unit consistency pass** grouping near-identical passages across units, adjudicated by one agent; (d) overlap reads (the same calibration file given to every unit); (e) synthesis-time adjudication | (a)/(b) reduce but do not remove variance. (c) needs a similarity rule (quotes or anchors). Model-family correlation remains |
| **4. Dependency edges** | Units extracted many dependency targets that are **not labels** (U04: mandates, artifacts, marked "label not resolved"); the synthesis dropped them. **Co-label-derived edges** (U03: 1; U02: 6 from S1541 co-labels) were accepted; the auditor notes another unit declined the same kind of co-label evidence and 14 other co-labelled rows got no edge | **Yes: an inconsistent evidence standard across units**, and no endpoint check at unit level | dependency graph (edges); not the label-level resolution | mechanical checks: (i) **endpoint existence** (the target is a discovery label); (ii) **evidence type** (an edge requires a quote stating dependency, not only row co-labels); (iii) **cross-unit consistency** (the same target and evidence type is treated the same way); (iv) a **graph-consistency pass** at synthesis | (ii) needs a rule decision: are co-labels evidence? (Protocol interpretation, human) |

## D. Multi-unit equivalence experiment (proposal; not executed without approval)

**Hypotheses (pre-registrable):**
- **H0.** Decomposition plus synthesis is as faithful as single-context analysis on the fixed fields (architecture A).
- **H1.** A consistency pass is required (architecture B).
- **H2.** Selective whole-file re-reading of cross-unit judgments is required (architecture C).

**Control (multi-unit, with a single-context baseline):** `s1620-removal-test-provenance-base-case-and-user-decisions` (OB0018).
- 34 required files, 771,717 bytes; 8 row sources, 5 OMQ-14 sources; no binary file; tier U; in the checklist.
- **Selection rule:** non-hub, required load 600–900 KB, at least 12 files, at least 4 row sources, at least 1 OMQ-14 source, not OB0004. That gives 16 candidates; the pick was seeded with 20261100.
- It fits one context (below the 1.09 MB completed in R2.3) **and** splits into **3 units at a 300 KB budget**.

**Arms (same label; the units are shared by A/B/C, so only synthesis differs):**
0. **Baseline:** one single-context analyst, production procedure (paged, READ-COVERAGE).
1. **A:** 3 units, then synthesis from records only.
2. **B:** 3 units, a mechanical invariant and consistency pass (the §C-2/-3/-4 checks), then synthesis.
3. **C:** 3 units, then synthesis with mandatory complete re-reads at cross-unit adjacencies and at invariant conflicts. The budget is fixed in advance, e.g. ≤ 300 KB.

**Fixed comparison fields:** the pilot's §5 fields (births, absences, statuses, layer, roles, edges, timeline, escalations, per-key stage-2) **plus** four failure-mode metrics:
- (i) change classes at unit adjacencies;
- (ii) the count of disposition-vs-finding conflicts at the end;
- (iii) calibration agreement on paired passages;
- (iv) edge-set Jaccard and endpoint validity.

**Independent audit:** one auditor, blind to arm labels, dispositions every non-exact field per arm against the baseline (DECOMPOSED-UPHELD / BASELINE-UPHELD / JUDGMENT-CALL / PROTOCOL-VIOLATION) with its own whole-file reads. **An auditor from a different model family, if available, is recommended for independence.**

**Success and failure criteria (per arm, frozen in advance):**
- An arm is **faithful** if it has 0 PROTOCOL-VIOLATION, **0 BASELINE-UPHELD on judgment fields**, and every failure-mode metric at or better than a pre-set bound. For example: 0 unresolved invariant conflicts; edge endpoints 100% valid; adjacency classes BASELINE-UPHELD = 0.
- **Decision mapping:**
  - A faithful → evidence for architecture A;
  - A not faithful but B faithful → B;
  - A and B not faithful but C faithful → C;
  - none faithful → none of the three is shown to preserve equivalence.

**Cost:** about 1 baseline, 3 units, 3 syntheses (B and C with small extra passes) and 1 audit, so 8 contexts. Isolated namespace (`PX0018-…`), no production effect; H-19 and S5c untouched.

**It does not answer:** the heaviest labels, binary treatment, or model independence (unless a different-family auditor is used).

## E. Large-file and binary analysis (S2276)

**What the pilot established:**
- Binary content decoded as text under character paging can exceed the display limit, so delivery and reading diverged.
- S2276 was not consumed whole.

**What this brief adds (measured):**
- This affects **only the 12 binary required files**. **No text page exceeds 30 KB** under current paging.
- **No required text file exceeds 241 KB**, so any unit can read any text file whole.
- The single-file capacity problem **does not exist for text**.

**Not established:**
- that large text files cannot be consumed. They can, and the largest is 241 KB;
- that S2276 is a genuine whole-file research obligation. It is an AI-generated PNG, and its stage-2 "hit" (the term `b'`) is almost certainly a byte coincidence in compressed data. That is a question of policy (§E.1), not of capacity.

### E.1 Binary-file policy options (for the human; none selected)

| Disposition | Evidence strength | Methodological consequence | Completeness effect | Effect on S5 guarantees |
|---|---|---|---|---|
| Whole-file reading as text | nil (decoded binary is not readable text) | impossible under the display limit; meaningless as reading | cannot complete | would force false WHOLE-FILE claims or permanent failure |
| Binary exclusion (drop from required sets) | a mechanical rule (signature, NUL bytes, non-UTF-8) | changes required sets for 41 labels | completeness redefined as "all text required files" | changes the frozen population of required files: a §26 change |
| NOT-CONSUMED-ESCALATED | honest record of non-consumption | the dimension resolves ESCALATED; G-04 still fails the batch (never thinned) | incomplete by definition | preserves honesty; 41 labels could never pass G-04 |
| FALSE-HIT classification | strong if the hit lies in non-text data (verifiable: the offset falls in binary content) | matches F2's spirit ("a hit whose relation to the label is not content") | the file is dispositioned without whole reading | needs a rule extension so that "hit inside binary bytes" becomes a mechanical FALSE-HIT basis: a §26 rule change |
| Binary-specific extraction (e.g. image metadata or OCR) | medium; depends on the extractor | a new evidence type | could "read" images | a new method, outside the frozen methodology |
| Human decision per file | strongest | 12 files, 41 labels: small enough to decide by list | complete once decided | explicit and recorded |

## F. Byte-bounded paging proposal (technical design; production reader not changed)

1. **Page size:** at most **24,000 bytes** of delivered output per page, leaving headroom under the ~30 KB display limit for the header line.
2. **Decoding and boundaries:** pages are cut on **UTF-8 byte boundaries**. A page ends at the largest code-point boundary ≤ 24,000 bytes, so no multibyte sequence is split. Boundaries are a pure function of the content bytes, so they are deterministic.
3. **Binary detection (before paging):** a PNG, ZIP, GIF, JPEG or PDF signature; any NUL byte; or failure of strict UTF-8 decoding. **Binary content is never paged as text.** The reader refuses it with `BINARY-CONTENT` (logged). The file then falls under the chosen binary policy (§E.1).
4. **Page hash:** sha256 of the page's exact UTF-8 bytes. The logged page record carries `source_id, page, n_pages, byte_start, byte_end, page_sha256, content_sha256`. The verifier recomputes the spans and hashes from the content.
5. **Coverage proof:** unchanged in principle. A file is read only if pages 1..N are logged with verified hashes, and the byte spans tile the content exactly (contiguous, no overlap, first at 0, last at the length).
6. **Delivery vs consumption.** The ledger proves delivery only; S2276 page 2 shows the gap. Candidate strengthening measures (proposals, not tested):
   - **(a) Display-safe guarantee.** Byte-bounding plus binary refusal ensures every delivered page fits the display, so a delivered page can be shown whole. This removes the observed failure mode, not deliberate non-reading.
   - **(b) Per-page acknowledgement token.** Each page header carries a short token derived from the page hash, and the agent's record must list the tokens of every page it relies on. The verifier checks them. A preview-only delivery would not show the token if it sits at the page end.
   - **(c)** Audit sampling of quotes near page ends.

   None proves cognition. (b) makes "saw the whole page" checkable at the end of the page.
7. **Compatibility:** existing character-paged logs remain valid for historical runs. The byte mode would be a new, versioned reader mode under a future contract revision.

## G. NOT-CONSUMED-ESCALATED (schema-4 delta)

**Status:** implemented behind a revision-4 gate and used only in the pilot. It is not in the production contract.

**Proposed semantic distinctions** (a single closed set, if adopted):

| State | Meaning | Allowed consequences |
|---|---|---|
| **WHOLE-FILE** | every page 1..N consumed and verified | may support FOUND, GENUINELY-UNDEFINED, births, change points |
| **READ-PARTIAL** | some pages consumed; not all | never whole-file evidence. Quotes from consumed pages allowed only as research-read material. Must be escalated if a whole reading was required |
| **READ-FAILED** | a read was attempted and failed technically (refused, binary refusal, display failure) | never evidence; escalated, with the failure reason |
| **NOT-CONSUMED** | a required file not attempted or abandoned | never evidence |
| **NOT-CONSUMED-ESCALATED** | the disposition form of NOT-CONSUMED, READ-PARTIAL or READ-FAILED for a required file: every dimension ESCALATED, plus a CONTRACT-DEVIATION escalation naming the S-id | cannot be read as FOUND, NOT-FOUND, complete or whole-file evidence; G-04 still applies (never thinned) |

**Can honesty be represented without weakening the standard?** Yes, provided the distinction is **one-directional**: only WHOLE-FILE supports whole-file results, and every other state forces ESCALATED plus a named deviation.

**Adoption requires:**
- a production contract revision (closed values, schema E, the verifier gate);
- a human decision on whether the reading-state record (READ-PARTIAL / READ-FAILED / NOT-CONSUMED) is kept as a separate field or folded into the escalation detail.

## H. Architecture options (not ranked; no selection)

| Option | Capacity | Completeness | Synthesis fidelity | Complexity | Evidence implications | Unresolved risks |
|---|---|---|---|---|---|---|
| **1. Deterministic decomposition only** | solves label capacity (text files ≤ 241 KB always fit a unit) | mechanically provable (pilot) | the four measured losses remain | lowest | per-file whole-file basis kept; cross-file judgments from records | per-key calibration, change classes and edges; multi-unit equivalence unknown |
| **2. Decomposition + consistency pass** (mechanical invariants §C-2/-4, calibration grouping §C-3, graph checks) | same as 1 | same | addresses detectable inconsistencies (conflicts, endpoints, calibration pairs) | medium (checker plus one adjudicating agent) | adds a reconciliation record type | wrong-but-consistent judgments; similarity-rule design; change classes only partly addressed |
| **3. Decomposition + selective whole-file re-read** (adjacencies, conflicts) | adds bounded re-read load per label (≈ boundaries × 2 files) | same, plus re-read coverage | cross-unit judgments made by one reader of both files | medium–high | re-reads are page-proven whole-file evidence | re-read budget on heavy labels; selection rule for what gets re-read |
| **4. Other: hierarchical synthesis** (unit → sub-label synthesis over adjacent units → label) | same as 1 | same | reduces cross-unit distance; unmeasured | high | more record layers | untested; more synthesis steps can compound error |

For every option, the binary-file policy (§E.1), byte-bounded paging (§F) and the schema-4 decision (§G) are **independent prerequisites**.

## I. Minimum human decisions required

1. Whether to run the **multi-unit equivalence experiment** (§D) as specified or amended, including whether to seek a different-model-family auditor.
2. **Binary-file policy** for the 12 binary required files and 41 labels (§E.1).
3. **Byte-bounded paging** plus binary refusal as the next reader revision (§F), and whether to add the per-page acknowledgement token.
4. **NOT-CONSUMED-ESCALATED** and the reading-state distinctions (§G) for a production contract revision.
5. **Rule interpretation for dependency evidence:** whether row co-labels alone can ground an edge (§C-4).
6. Only after 1–5: the architecture choice (§H).

**State:** production unchanged; no batch authorized; H-19 SEALED; S5c PROHIBITED.
