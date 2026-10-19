# Phase 2 review report: S0006–S0010 (independent reviewer, 2026-10-18/19)

*Saved verbatim in substance from the reviewer's hand-back (a fresh read-only Claude sub-agent; access per `00-access/ACCESS-RECORD.md`, AR-11). The reviewer's Phase 1 blind extraction is frozen as `PHASE1-BLIND-EXTRACTION.md` (sha256 `957b3812…3e4e`).*

**Verdict:** SOUND WITH MINOR ISSUES.
- 31 finding IDs: 3 foundational, 28 ordinary.
- 33 issues in all (F-04 and F-08 each carry a secondary issue).
- Capture: about 246 of 301 blind items (≈82%) dispositioned by the researcher.
- Line anchors: all book [Src: S00nn Lx] anchors for these five sources resolve. Two record quotes are truncated (F-24).
- The reviewer's Phase 1 file totals were one line high: 123/69/111/146/158 against the correct 122/68/110/145/157.

## Foundational

| ID | Class | Finding |
|---|---|---|
| F-01 | LABEL-ERROR | S0008 L71–76: the source has one "corrected canonical chain" without strand names. The book's "Four strands, not one chain" (RC-14), with strand names under [Src], presents the researcher's interpretation as source content |
| F-02 | OMISSION | S0008 L69, L76 (evidence → platform n≈3 informal, ≈2 formal) contradicts S0010 L31, L121 ("0 traversals", "never executed once"). Unrecorded; RC-19's assignment of S0010's stall to "the evidence → platform back-edge" is an unlabelled interpretation and the same edge S0008 counts n≈3; E-S0008 P11 was routed to RC-8 (generation) |
| F-03 | OVERSTATEMENT | S0010 L87–90: the source's step from "how much should I trust this, and where did it come from?" to "how should it be used" (prescriptive) is unargued, since provenance is descriptive. RC-18 adopts it and extends it ("provenance and standing both fall on the prescriptive side"); the "third distinction" framing is the researcher's, written as the source's |

## Ordinary

| ID | Class | Finding |
|---|---|---|
| F-04 | LABEL-ERROR | S0006 L36, L54 (and the EEP quote in S0010 L110) carry no date. The book's RC-4, RC-7 and RC-19 attach "ARB, 2026-07-11" and "predates the August corpus" to them. Only L21, L22 and L88 are dated 07-11 in S0006 |
| F-05 | QUOTE | the ARB "complementary siblings, not a hierarchy" (S0006 L88) supports non-hierarchy and independence only; "projections" is S0006's gloss (L89) in a PARTIALLY SUPPORTED hypothesis; it is relayed by a generated document |
| F-06 | MISREADING | RC-14 says "three edges … were rejected", but "creates PKS" is a HYPOTHESIS (n = 0), not rejected; "executed through runtime" is IMPRECISE (L67), though counted wrong at L108; the PARTLY / reattributed edges (L68–69) are omitted |
| F-07 | LABEL-ERROR | "I-7 of the Relationship Ontology" (RC-14, RC-15): S0008 does not name I-7's home document |
| F-08 | MISREADING | RC-13: "a DP-n is the rule with meaning that a capability **enforces**", where the source says "executing" (L25) and "every capability is advisory" (L67). Also misquotes S0007 L35 as "unique, needs operational proof" (source: "UNIQUE HYPOTHESIS — needs operational proof") |
| F-09 | OMISSION | the one-owner criterion also fails for the Runtime Adapter ("no declared owner", S0008 L29), not only for KnowledgeOS |
| F-10 | OMISSION | S0008 L16 "14 examined, plus 3" against 18 table rows; E-S0008 says "Seventeen" without comment |
| F-11 | OMISSION | S0007 L11 REV 2 softened PKS ≈ context engineering to "same problem space, extends it, not an identity"; E-S0007 §0 and W-32 restate the unsoftened identity |
| F-12 | OMISSION | S0007's internal inconsistencies (17 against 19 concepts; PKS-as-governed-context triple-supported vs the "governance-grade PKS framing" unique hypothesis; second-product bar triple-supported vs MEDIUM for the exact bar; the blind-review leg not shown) are unrecorded |
| F-13 | MISREADING | S0007 L27 (derived statements become authoritative by audit plus signature) routed in E-S0007 as evidence for "representations never gain authority", the opposite reading |
| F-14 | OMISSION | S0007 matrix rows without disposition: decisions as first-class; knowledge ≠ artifact; runtime adapters; knowledge space; the four progression kinds; mission enacted, unratified (theory-in-use) |
| F-15 | OVERSTATEMENT | RC-8: "a deliberate choice"; the source says only "the precise bar is ours" |
| F-16 | OMISSION | PURPOSE graded Candidate and CONTAINER demoted, yet L71–73 and L135 still list CONTAINER as confirmed; RC-16 lists the nine kinds without grades |
| F-17 | OVERSTATEMENT | RC-16: the governance-asset demotion is MEDIUM-HIGH, not equal to the HIGH rejections; "the category test that produced them" is wrong, because the test rejected candidates and the kinds came from convergence |
| F-18 | LABEL-ERROR | RC-17: "the two added dimensions are evidence-status and operational-state" equates the source's "two candidate additions" with GA's "2 new"; that is an inference |
| F-19 | OMISSION | the S0009 demo "Capability Mapping … whose ARTIFACT does not exist" not routed to the book's E4 (knowledge with no carrier) |
| F-20 | LABEL-ERROR | RC-18 status: the `Revenue` example and the name "Metadata ≠ Knowledge" come from the [2nd-hand] OKF table, not from repository evidence |
| F-21 | MISREADING | E-S0010 §6: `identifier-check.php` is not in S0010 L38 (imported from elsewhere) |
| F-22 | OMISSION | S0010 L48: "no unguided baseline to compare against", an evidential limit, not carried |
| F-23 | OVERSTATEMENT | RC-19: the source's "the ONLY OKF insight whose failure would invalidate the platform's central premise" became "the one finding" |
| F-24 | QUOTE | truncated quotes: E-S0010 Q13 "Knowledge Layer"; E-S0008 Q11 "a set of artifacts with a " |
| F-25 | OMISSION | S0010 internal issues (two "most heavily evidenced" claims; a broken §4 cross-reference; a "concrete, dated" blocker with no date) |
| F-26 | OMISSION | S0010 L129, first half (CAP-001: Domain/Application knowledge-free; Infrastructure reads knowledge), relevant to Ch. 1 §1.1 |
| F-27 | OMISSION | S0006: "ALREADY SOLVES" vs DRAFT with its adoption test "unevenly met"; U-EDV-1…3 not carried |
| F-28 | OMISSION | S0006 L64 ("extends DetermineArtifactType") vs L70 ("neither extends the other") |
| F-29 | BOTH-DEFENSIBLE | the reading of "FREEZE (n≥3, undeclared authority)" |
| F-30 | BOTH-DEFENSIBLE | RC-9's generalization "one home per rule"; keep the "authoritative" qualifier; relayed quotes |
| F-31 | BOTH-DEFENSIBLE | X-3 and X-5 (S0007) excluded but arguably bear on ownership and adoption |
| F-32 | BOTH-DEFENSIBLE | the CONTAINER demotion compressed |
| F-34 | OMISSION | E-S0008 lacks the Engineering Method definition (own constitution MC-01..08, ADR-M, validator, baseline; sponsor + ARB; distinct by scope exclusion, MEDIUM) and the capability invariants fail-closed / prevents-never-repairs |

**Items the researcher had that the reviewer lacked:**
- chronology corrections (S0006's date; S0010 predates S0001–S0009; S0009 written before S0001);
- the invariant-number collisions;
- the L-A dimension-count difference;
- the vendor-blog source of the 86% figure;
- U-MM-1 framed as a well-formedness question;
- the W-29 reasoning check.
