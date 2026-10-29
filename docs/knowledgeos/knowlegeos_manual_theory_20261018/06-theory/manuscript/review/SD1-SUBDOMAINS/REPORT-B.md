SD1 REVIEW REPORT B

Reviewer B. Object: `06-theory/manuscript/chapter_10/10.01-subdomains.md` and `10.00-index.md`. W = /home/d0f38614-c3a6-41d3-9952-7f59ad699b2d/roshyara/personal/nrna1/docs/knowledgeos/knowlegeos_manual_theory_20261018.

## SUMMARY (10 lines)
1. The draft's closure criterion (every area classified or marked provisional) is only partly met. Core item 1 and most supporting rows should be provisional.
2. Verified facts: the CON-IDs and their labels in the draft match the register. S0035, S0036, S0178, S0365 and S0196 lines match the sources. The R2 candidates (reused, stable, applied) are ordinary words.
3. The test of §10.1.2 is not a partition. By the book's own usable(k,t) (§0.1), a conjunct needs only to be necessary to be core. Well-foundedness and visibility are conjuncts, yet they are classed open and supporting.
4. "No solution not made for KnowledgeOS delivers it" is an empirical claim marked "Ded". No alternative was searched, and the outside literature is unread.
5. Premise 1 adopts the frame in which S0035 L72 says the core would be "engineering governance/knowledge". The draft then calls that inversion "not taken". Items 6 to 8 use kernel or application placement as evidence for a strategic class, which breaks premise 2.
6. Item 2 recommendation: the fork is real as a mission question, but the recommendation contradicts premise 3 (usable includes well-founded). It also rests on a non sequitur (CON-0127 is about independence, not well-foundedness). I would not send it as phrased.
7. Prior work is partly unfair. S0178's core is truncated to "creating, governing". S0175 L93 (core innovation = Method/Binding/Evidence) is omitted. S0196's "Rule" and "Invariant" as core are omitted. The robot quote is cited to S0175 but is S0171 L23.
8. Missing areas: lifecycle and admission, context assembly (§0.7), relationships, history, trust.
9. Placement: a new Ch. 10 is acceptable. Reason 1 is overstated.
10. Process independence: I am an independent reviewer by process (a separate session with the same commission), not a personal one. I did not see Reviewer A's work.

## 1. VERIFIED FACTS (what I checked)

- **Register labels.** All 17 CON-IDs match the register's status:
  - VALIDATED: CON-0063, 0071, 0072, 0077, 0089, 0111, 0127, 0231, 0265.
  - PROVISIONAL: CON-0080, 0125, 0164, 0179, 0191, 0260, 0261, 0264.
- **S0035 lines.** L30, L71, L72, L142 and L200 match the draft.
- **S0036 lines.** L90–92 and L199 match. L208 says "Generic Subdomain — replaceable by design".
- **S0365 L1760** reads "Not proven".
- **S0196 L248 and L270** match.
- **S0187 L399 and S0191 L578** match ("adapter, not kernel").
- **S0151 L415–417** matches.
- **S0612** is a review finding, not a position. It records two documents of one day in opposite positions.
- **Quote check.** `corpus_quote_check.py` returned "0 found, 0 not found". The draft uses "(… in meaning)" and no `"…" [S L]` form, so the tool checked nothing. I checked the quotes by hand (list in §4).
- **R3.** My corrections touch only 10.01. Inbound citations from other book files to §10.1: none (grep of TARGET-TOC and BOOK-OUTLINE for chapter_10 and Part VII found nothing). Only 10.00-index points to it. `inbound_citations.py "§8.25 C1"` lists 10.01 L44 as a citer.
- **R1.** `register_home_check.py` gave no flag on Ch. 10.
- **R2.** `undefined_terms.py --scope 10` gave three in-scope candidates (tier A: 0).
  - "reused" (10.01 L64): ordinary word. Not a term. No action.
  - "stable" (L64, "Stable numbers"): ordinary word, but it sits near "stability levels S0–S3". Normal homonym risk. Say "unchanging numbers".
  - "applied" (L32): ordinary word. No action.

## 2. FINDINGS

| ID | Finding | Sev | Evidence | Correction | R3 | Resolved when |
|---|---|---|---|---|---|---|
| B-01 | The test is not a partition, and the "core" decision does not follow from it. Core = "usable cannot be delivered without it" ∧ "no non-specific solution". But usable = current ∧ well-founded ∧ visible (§0.1). So item 2 (well-founded) and the delivery of visibility (items 5 and 11 rows) are also necessary. Item 1 alone does not "by itself deliver usable", so by the supporting clause it is supporting. | critical (decides a classification) | 10.01 L34, L38–39, L42 against 00.01 predicate | Restate the test as a partition. Say which conjuncts of usable each area delivers. Apply it to well-founded and visible explicitly. | none | Test restated with exclusive classes; items 1, 2, 5 re-derived |
| B-02 | "No solution not made for KnowledgeOS delivers it" is an empirical, enumerative claim labelled "Ded from the definitions". The only instance cited is the estate's own engine (§9.6 D1). No alternative class was looked at (revocation or status protocols, bitemporal stores, policy engines, signed ledgers). The outside literature was not read. | high | 10.01 L38, L30 | Mark the non-substitutability half HYPOTHESIS. Item 1 becomes "core by necessity, substitutability untested", provisional. | none | Alternatives searched with the sources named, or the half kept as hypothesis |
| B-03 | Premise 2 is breached in the evidence cells. Items 6, 7 and 8 use "adapter, not kernel", "capabilities are not kernel operators" (CON-0111) and "methods are applications of the kernel" to fix a strategic class. Item 8 rejects S0036's core using the kernel/application argument, not the usable test. | high | 10.01 L43–45 against L13 (premise 2) | Re-judge items 6 to 8 by the §10.1.2 test only. Keep kernel placement as a separate column labelled "evidence from the boundary test". | none | The evidence cells no longer use layer words as the reason |
| B-04 | The inversion is not engaged. Premise 1 takes KnowledgeOS as the reference product. S0035 L72 says that under that condition the core "would be" "engineering governance/knowledge". The draft calls this "conditional and not taken", yet premise 1 supplies the condition. The one canon statement of KnowledgeOS-as-product core is thereby bypassed. | high | 10.01 L12, L22; S0035 L72, L142 | State explicitly that the draft's result differs from canon's conditional statement. Add a row for it, as with S0036. | none | The divergence is stated with a reason |
| B-05 | Premise 3 is weaker than stated. usable(k,t) is the Professor's own statement ("This statement is mine", 00.01) with "Solution: Hyp". The draft calls it "the book's statement of the problem". Whether the reason KnowledgeOS exists includes well-founded is exactly the open fork, yet usable includes it. §0.1 itself says whether a kernel predicate can compute well-founded is open. So usable partly presupposes the item 2 answer, and the ceilings of items 1 and 2 cannot exceed this Int. The claim "reviewed in the whole-book reviews" I could not verify. | high | 10.01 L14; 00.01 | Label premise 3 Int, author's definition. Make every ceiling at most the level of §0.1. Show where §0.1 was reviewed. | none | Premise 3 labelled and its review shown |
| B-06 | Visibility is mis-placed. Item 1 claims visible(k,t) is answered by CE*. But §0.1 defines visible as "the user can see both values", a property of the viewer. Rendering is in item 5, supporting. Context assembly (§0.7) is missing. | high | 10.01 L38, L42; 00.01 | Re-derive item 1's claim. visible is not delivered by CE* alone. | none | Visible assigned to a stated area |
| B-07 | Item 2 recommendation: (a) it contradicts premise 3 (the draft's own purpose includes well-founded). (b) "the core would depend on the factors of independence" is a non sequitur. CON-0127 concerns independence of a check, not well-foundedness, which is "supported by its evidence to the degree its use needs". (c) The options are not exhaustive. §0.1's visible requires the user to see both values, so "shows" well-foundedness is already in the book. (d) CON-0231 is about adoption, not about what the product promises. | high | 10.01 L39, L58 | See §4 item 5. Send the Principal a question that separates "promise to hold" from "promise to show". | none | Question reworded; the recommendation removed or restated |
| B-08 | A corpus classification the book has not judged. Item 9 cites BC-7 "Supporting", and BC-7 is an as-built module. §9.6 D1 only records that BC-7 is an application instance; it does not judge the class. Item 2 and item 5 use S0196, a baseline of the current architecture. PKS is a different product (PublicDigit knowledge). | high | 10.01 L39, L42, L46; 09.06 L154; S0196 L248 | Mark these as the estate's classifications, unjudged. Do not use them as evidence for a class. | none | Items 5 and 9 re-grounded without them |
| B-09 | Selective use of S0196. The draft cites only "Conformance strategically Core". The same line (L248) says 8 core, including Decision, Rule, Invariant, Ruling, Term, Candidate, Charter grant, Verdict. Item 3 (rules, supporting) is then contrary to the PKS classification and unreported. | high | S0196 L248 against 10.01 L40 | Report it and explain the difference (different reference product). | none | Row added in §10.1.1 |
| B-10 | Rivals are misrepresented. (a) S0178's core is "creating, governing, validating, evolving, and delivering trusted Knowledge Products" (refined: "creation, evolution, and authorized delivery"). The draft says "creating, governing". "Validating" and "delivering" are the well-founded and visible conjuncts of item 2 and B-06. (b) S0175 L93 names "the core innovation (the Method/Binding/Evidence model)". This is omitted. (c) The robot quote "The robot is not the core domain" is S0171 L23 (via §8.25), not S0175. (d) S0036 L177 is titled "Platform Kernel — the Core Domain". S0036 identifies kernel with core, which contradicts premise 2, and this is not mentioned. | high | S0178 L30–50; S0175 L93; S0171 L23; S0036 L177 | Correct each. Add S0175 to the rivals. State the S0036 identification. | none | Rows corrected |
| B-11 | Ceiling rule. Item 1's "lowest S1" ignores B-05. Items 7 and 11 both rest on relayed estate lines with no book concept, yet item 7 gets S1 and item 11 gets S0. "No open critical finding" was not shown for CON-0191, CON-0264 or CON-0080. CON-0191's follow-up lists open items (T9-11). CON-0080 has open must-do items. | high | register follow_up for CON-0080 and CON-0191; IMPLEMENTATION-STRATEGY §3 | Apply one rule. Name the lowest-level source per row. Show the finding check. | none | Ceilings recomputed with the lowest source named |
| B-12 | Missing areas. RESEARCH-PURPOSE L28–37 lists knowledge object and identity, lifecycle, authority, evidence, relationships, policy, history. Missing as areas: lifecycle (supersession and validity period, part of current(k,t), §2.11), admission or adoption (the CON-0085 fork and S0365/S0392), relationships, history, trust (§9.9), and context assembly (§0.7). | high | RESEARCH-PURPOSE; 00.01 | Add them as candidate areas or state why each is excluded. | none | Each candidate classified or excluded with a reason |
| B-13 | Category error in items 6 and 7. An AI agent or robot is a consumer or actor outside KnowledgeOS, not a part of it. The classifiable area is the interface and grants for agents (which the draft places in item 1). "Generic" means a replaceable solution to a need of the product. | normal | 10.01 L44; §8.25 | Rename the area (runtime adapters only) or remove it. | none | Done |
| B-14 | "Core domain act" is a homonym. S0365 L1760 and S0392 give "core domain act" per kernel hypothesis (S0392 L516, L532, L548 and so on: one act per lens), not a strategic class for the product. Using it as a "rival core" confuses two senses. | normal | S0365 L1760; S0392 L516–580 | Qualify "core domain act (a kernel hypothesis)". | none | Qualified |
| B-15 | Outside literature. §10.1.2 calls the test "the book's own form of the Evans/Vernon test" and §10.1.1 gives chapter details of both books, while saying neither was opened. EXTERNAL-SOURCES.md has no Evans or Vernon entry. | normal | 10.01 L30, L34; EXTERNAL-SOURCES.md grep: none | Withdraw the attribution and the chapter details. Keep only the three class names, flagged Ext, unchecked. | none | Lines withdrawn or checked |
| B-16 | Items 4 and 5: "S1" ceiling needs CON-0080 (PROVISIONAL, open must-do items) and CON-0260 (PROVISIONAL, empty history). See B-11. Also an area "gaps, deferral and activation" is a method mechanism, so it overlaps with item 8's reasoning. | normal | register | Recompute. | none | See B-11 |
| B-17 | Item 11 is S0 and classed "supporting". By the closure criterion that is a provisional classification, not a classification. | normal | 10.01 L48 | Mark provisional. | none | Done |
| B-18 | Placement: the draft's reason 1 is overstated (see §5). TARGET-TOC has no Part VII yet; the text says "would gain". | normal | TARGET-TOC; 06.03 (CON-0089 is Ded in Ch. 6) | Reword reason 1. Add Part VII when settled. | none | Done |

## 3. VERDICTS ON THE 11 CLASSIFICATIONS

| # | Draft | Verdict | Reason |
|---|---|---|---|
| 1 | core, S1 | PROVISIONAL core | Necessity (current standing) is derived. Non-substitutability is a hypothesis (B-02). The premise 3 level applies (B-05). Visible is not delivered here (B-06). |
| 2 | provisional core or supporting | Provisional: right | By the test as written it is core by necessity (B-01). The recommended decider is flawed (B-07). |
| 3 | supporting, S1 | PROVISIONAL | S0196 classes Rule as core (B-09). current(k,t) for a rule is its adoption, lifecycle and validity (§2.11). |
| 4 | supporting, S1 | PROVISIONAL | CON-0080 PROVISIONAL (B-11). Overlaps with lifecycle (B-12). |
| 5 | supporting, S1 | PROVISIONAL | Visibility depends on it (B-06). S0196 use (B-08). |
| 6 | generic, S1 | Right, with evidence rewritten | The "adapter, not kernel" evidence is layer evidence (B-03). Generic only if the record's integrity conditions hold at the carrier boundary. |
| 7 | generic, S1 | PROVISIONAL, rename | Category error (B-13). Ceiling inconsistent with item 11 (B-11). |
| 8 | supporting, S1 | PROVISIONAL | Rejected on layer grounds (B-03). Rivals S0036 and S0175 L93. T0-02 open. |
| 9 | supporting, S1 | PROVISIONAL | Rests on an estate classification (B-08). |
| 10 | supporting, S1 | PROVISIONAL | A primitive candidate. The book decides in turn 2. |
| 11 | supporting, S0 | Provisional (not a classification) | B-17. |

**Placement verdict.**
- A new Ch. 10 is acceptable.
- Reason 1 is overstated: Ch. 6 itself contains derived claims (CON-0089 is Ded). Say instead "Ch. 6 is organised by source windows."
- Reason 2 is sound. Ch. 7 is retired, and no renumbering is needed.
- Reason 3 is sound, but a version line only exists once all five turns settle.
- The alternative of a separate design document, outside a theory book, is also sound. The stakes are low.

## 4. THE THREE PREMISES, THE TEST AND PRIOR WORK (answers to the commission's questions 2 to 6)

**Premise 1 (CON-0089).**
- The register basis is "Ded from canon's own conditional inversion", with a single instance. Applying it with KnowledgeOS as the reference product is a legitimate use of the relativity.
- It is incomplete without B-04.

**Premise 2 (core is not kernel).**
- The separation is sound. It follows Evans/Vernon, with the usual caveat that the outside text is unread. It also follows the book's own TT1 correction at 09.99 L47.
- It is not kept (B-03). S0036 L177 is a corpus source that identifies kernel with core, which strengthens the need to keep them apart.

**Premise 3 (usable).**
- It is fair as "the reason for the book's definition of the problem". It is not fair as "the reason KnowledgeOS exists" (B-05).
- Of the three conjuncts, only well-founded is open, and it is the one the draft sets aside.

**Item 2 fork.**
- Real as a mission question; the decision-maker is the Principal.
- Test of the recommendation: option 2 ("only current standing right and visible") contradicts premise 3. It also changes what visible means (B-07 (c)).
- Rival to test: KnowledgeOS promises to hold the evidence record and show the well-foundedness status (promise to show), not to guarantee it.

## 5. WHAT THE EVIDENCE ESTABLISHES AND WHAT REMAINS UNPROVEN

- **Established (verified):** the source quotes and the register labels are accurate. The areas are named with their evidence.
- **Derived (Ded), valid:** current standing is necessary for usable.
- **Unproven:** that current standing is the only core. That non-substitutability holds. That the test separates classes.
- **Out of scope for this turn:** I did not judge turns 2 to 5.

## 6. FILES OPENED AND COMMANDS RUN

**Files opened (all within the brief's list):**
- `06-theory/manuscript/review/SD1-SUBDOMAINS/BRIEF.md`
- `03-methodology/REVIEWER-INSTRUCTIONS.md`
- `03-methodology/MANDATE-STRATEGIC-DESIGN-PROPOSED.md`
- `03-methodology/IMPLEMENTATION-STRATEGY-PROPOSED.md` (§3, lines 49–60, by sed)
- `06-theory/manuscript/chapter_10/10.01-subdomains.md`
- `06-theory/manuscript/chapter_10/10.00-index.md`
- `06-theory/CONCEPT-REGISTER.tsv` (by awk and grep)
- `06-theory/RESEARCH-PURPOSE.md` (lines 26–40, grep)
- `06-theory/EXTERNAL-SOURCES.md` (grep only)
- `06-theory/manuscript/TARGET-TOC.md`, `BOOK-OUTLINE.md`, `CHAPTER-PLAN.md` (grep)
- `06-theory/manuscript/chapter_00/00.01-the-problem.md`
- `chapter_06/06.00-index.md`, `06.02`, `06.03` (parts)
- `chapter_08/08.22`, `08.25`, `08.28`, `08.17` (greps and parts)
- `chapter_09/09.06-boundary-test-authority-recording.md`, `09.99-change-log.md` (L47)
- `chapter_01/01.14-state-is-not-authority-roles.md`, `01.05` (grep)
- `chapter_00`, `chapter_06`, `chapter_09` directory listings
- `02-FILES.jsonl` (read by `corpus_quote_check.py`)
- `docs/knowledgeos/knowledgeos-s-series-theory/corpus-access/CORPUS-ALLOWED.tsv` (all source IDs below were found there: S0035, S0036, S0151, S0171, S0175, S0178, S0187, S0191, S0196, S0365, S0392, S0612)

**Corpus sources opened (all allowed):**
- S0035: `docs/knowledgeos/2026-08-02-knowledgeos-strategic-domain-discovery.md` (L26–30, 55, 71, 72, 142, 200)
- S0036: `docs/knowledgeos/KnowledgeOS_Platform_Capability_Model.md` (L84–93, 177–208)
- S0178: `docs/knowledgeos/architecture/01-KnowledgeOS-Domain-and-Context-Architecture.md` (L1–50)
- S0151: `docs/knowledgeos/brainstorming/20260819-205541-kos-ddd-architecture-v3-duplicate.md` (L415–417)
- S0171: `docs/knowledgeos/brainstorming/_misc/20260819-221159-digitalization-robot-vision.md` (L23)
- S0175: `docs/knowledgeos/architecture/06-KnowledgeOS-Operating-Model-and-Product-Architecture.md` (L86–100 and a grep for "core")
- S0196: `docs/knowledgeos/architecture/20260821-2229-PKS-Current-Architecture-Baseline-Stage-2.md` (L248, 270)
- S0187 L399–433 and S0191 L562–578, both under `docs/knowledgeos/brainstorming/`
- S0365: `.../brainstorming/kernel/20260823-112155-perplexity-kernel-domain-responsibility-brainstorming.md` (L1758–1761)
- S0392: `.../brainstorming/kernel/20260823-225038-knowledgeos-kernel-multi-lens-domain-discovery.md` (grep "core domain", L505–520)
- S0612: `docs/knowledgeos/reviews/kernel/session1/S1-F010-...md` (L1–36)

**Commands and searches run (from W unless stated):**
- `mkdir -p` the scratch directory (…/scratchpad/sd1B); nothing else written.
- `wc -c` on the files read; `ls` of the manuscript and `tmp/tools`.
- `grep -n "§3" … IMPLEMENTATION-STRATEGY-PROPOSED.md`; `sed -n 49,60p` of the same file.
- `python3 tmp/tools/undefined_terms.py --scope 10`, and `--help`.
- awk over CONCEPT-REGISTER.tsv (status check of 17 CON-IDs; follow_up and history of CON-0080, 0125, 0164, 0179, 0191, 0260, 0261, 0264; CON-0089 row).
- `python3 -I tmp/tools/corpus_quote_check.py 06-theory/manuscript/chapter_10/10.01-subdomains.md` (0 quotes in the tool's format).
- `python3 -I tmp/tools/register_home_check.py`.
- `python3 -I tmp/tools/inbound_citations.py "§8.25 C1"`.
- grep and sed over the files listed above, including: `grep -rn "S0175" chapter_0*/*.md`; `grep -rn -i "measurement is not knowledge"`; `grep -n "DB-6|DB-7"`; `grep -n -i "evans|vernon|distillation"` in EXTERNAL-SOURCES.md and chapters 00 to 09; `grep "Chapter 10|chapter_10|10.1|VII"` in TARGET-TOC and BOOK-OUTLINE.
- awk of CORPUS-ALLOWED.tsv for the source IDs above (the check against the allowed list; I read the file only through this awk and a head/wc).
- No file in the repository was edited.
