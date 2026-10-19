# Batch 3 — five files after the audit: review and decision

| Field | Value |
|---|---|
| Date | 2026-10-18 |
| Access state | **No corpus file was needed or touched.** All five sources are authorized background files (`docs/knowledge_tranfer/`). Corpus access status was not re-checked, because no corpus action was taken |
| Files (all read in full) | ① target architecture v3.1 `…0841` (655 l.) · ② architecture conformance `…0905` (293 l.) · ③ aggregate boundaries `…0922` (240 l.) · ④ invariant–aggregate matrix `…1023` (393 l.) · ⑤ invariant allocation `…1029` (266 l.) |
| Prior exposure | ③–⑤ were summarised earlier in background note KT-8; ① was partly used before compaction. This batch is the first full M-04 pass on all five |
| Independent checks | `.claude/scripts/session-resolve.php` (L27–29, L225) · `scripts/observations/recommendation-rules.yaml` (keys) · V3 for the principle-numbering collision (grep) · git object model (hashes) · `.gitignore:33` (from §1.13) |
| Register | `06-theory/IDEA-REGISTER.tsv`: **102 rows** (B3-F1 34 · F2 16 · F3 19 · F4 18 · F5 15), 173 in total |

## 1. Classification of the 102 ideas

| Class | Count | Comment |
|---|---|---|
| repeated | 32 | the five files restate each other often ("sixth independent statement") |
| update or refinement | 26 | |
| new evidence or example | 22 | |
| new idea | 12 | listed in §3 |
| contradiction or revision | 6 | two of them against **my own** text |
| open | 4 | |

## 2. Where the ideas went

| Place | What was written |
|---|---|
| Ch. 0 §0.2 | fifth framing (method); names are tiers, a terminology freeze would be a level error |
| Ch. 0 §0.7 | revision: context assembly must filter by may-read before relevance |
| Ch. 1 §1.14 (new) | STATE ≠ AUTHORITY; ARCH-INV-1; five roles; P-8; three layers; conceptual target as an authority order; **§1.14.5 enforcement presupposes ratification** |
| Ch. 1 §1.15 (new) | Claim granularity vs type; 17 concepts mapped to natures; provenance attributes |
| Ch. 1 §1.16 (new) | atomic change and the broken boundary for grants; **two-time authority**; results relative to inputs; projection staleness; the missing act record; pinning; global invariants; acceptance requires an attack |
| Ch. 1 §1.17 (new) | authority and information must co-occur; not found ≠ unauthorized; inputs passed, not fetched; **read access** (Hyp) |
| Ch. 2 | §2.1 gap verified in YAML, convergence caveat · §2.2 candidate contract tested · §2.4 third and fourth vocabularies, report as projection · §2.7 caveat for stochastic analysers · §2.8.8 E6 widened, **E8 withheld**, no-silent-promotion, NoConflictDetected · §2.9 hardness |
| Glossary | 12 entries added, 2 corrected |

## 3. What the theory gained

1. **Enforcement is bounded by standing and strength, not only by realization** (§1.14.5): a gate may block only on a rule that is currently authoritative and mandatory. Authority gates fail closed on a *missing* permitting act; conformance gates may block only on a *present* obliging act. These are one axiom read in two directions.
2. **Authority has two times** (§1.14.1 corrected, §1.16.2): the grant is judged at the time of the act; the claim's validity, revocation and supersession at the time of use.
3. **References drift silently** unless pinned, watched or frozen (§1.16.6). The open "seventh atomicity value" is better read as a missing dimension, *referent time* (Hyp).
4. **Global invariants** such as "one authoritative home" can be prevented only through a single write path; otherwise they can only be detected (§1.16.7). This explains where duplicates arise.
5. **Reproducibility is a precondition of verification**; an analysis that fetches its own inputs hides its evaluation scope, so "nothing found" cannot be told apart from "not looked" (§1.16.3, §1.17.4).
6. **A legitimate decision needs both authority and information** (§1.17.2); the two failure modes are two invariants the book already held.
7. **The theory had no read side** (§1.17.5). Refined hypothesis: read is a grant capability; the new dimension is the confidentiality class of a claim; a new kind of nothing, *withheld* (E8).
8. **The conceptual target is the authority axiom drawn as a graph** (§1.14.4): standing enters at the top; projection is a sink; an upward edge is a test failure.

## 4. Look-back findings

**Omissions found by Lookback A (completeness) and fixed:**
- in ①, nine sections had no register row: naming as tiers, context map, C4 views, OQ register, observed rule-engine gap, conceptual target, closing maxims. All were added (B3-F1-25…34) and five were integrated.

**My own errors found by Lookback B (correctness) and fixed:**

| # | Error | Kind |
|---|---|---|
| 1 | §1.14.1 formula judged the grant at the time of use; refuted by the CONSUMED grant in my own data | **logic** |
| 2 | §1.17.5 called may-read a new dimension; counterexample: read is a grant capability | **logic** (overclaim) |
| 3 | §1.14.5 bound omitted binding strength | logic (incomplete) |
| 4 | §2.8.8 table split by a paragraph; E7 missing from the licensing sentence | coherence (pre-existing) |
| 5 | V3 cited in two chapters but missing from both source lists | reference |
| 6 | three line anchors off by 3–6 lines | reference |
| 7 | "every term is estimable" from evidence streams holding one outcome record | overclaim |
| 8 | "Popperian reading used since Chapter 0"; model-integrity test presented as a standard | overclaim |
| 9 | "three hours later", "an hour later" (actual: about 2.5 h; 6 min) | fact |
| 10 | one bare use of "lifecycle" with a new meaning | glossary discipline |

**Audit lesson applied.** Counterexample attempts were made on four new formulas: the enforcement bound, current authority, may-read and "no conflict detected". Two changed as a result (#1, #2) and one was extended (#3). The defect class the audit named, my own formalizations stated too strongly, is still the main one: 2 of 10 errors are logic, 3 are overclaims.

## 5. Source claims challenged

- "Two (four, six) independent derivations": the derivations are same-day, same-author-family, often from the same material. **Repetition, not replication** (Ch. 2 §2.1).
- CF's code-level gates are "blocked on whether the PKS is software": the blocker was dissolved by V3 REV 1 later that day. The gates now await the decided-capability inventory instead (§1.14.5).
- CF's P-8, P-9 and OQ-11 were **reused for different content** by V3 REV 1. The book cites them by content only.
- The candidate rule contract stores an owner as "authority" and "approved" as state, both refuted by Chapter 1 (§2.2).
- "NoConflictDetected" mixes E1 with E3/E6; IA itself splits it six minutes later.
- "Identical inputs yield an identical result" is false for an AI candidate stage (§2.7).

## 6. Cross-chapter consistency (against the glossary)

- All § references resolve, except historical mentions of the renumbered §2.8.5 (change logs only).
- Every superseded statement found by search (the old two-time formula in §1.14.5 and the glossary) was updated.
- Terms added to the glossary: representation (form) · canonical/derived carrier · presentation/projection · current authority · level error · authority gate · conformance gate · pinned reference · referent time · global invariant · may-read · withheld (E8). Corrected entries: enforcement consequence, current authority.
- **Known residue:** register rows from pilots 1–2 cite sources by short name (GA, TR, RM …), not by path, so a path-based count shows 0 rows for them. This is a format inconsistency, not a loss. It should be normalised once, not per batch.

## 7. Weaknesses that remain

- All five sources are AI-generated proposals inside the Track-B lane. ARB rulings recorded in ① are rulings **inside a proposal**; their original records were not located.
- Three of the five files were known to me through KT-8 before this pass, so my reading was not blind.
- The two-time correction and the may-read refinement are my deductions. They need a reviewer's attack before they are relied on.
- **Corpus evidence is still absent.** Every open hypothesis (referent time, confidentiality class, E8, missing nature for Question/Risk) waits for it.

## 8. Decision

**CONTINUE**, with the corrections in §4 already made (no outstanding CORRECT item).

Reasons:
- the method found real errors, two of them in my own logic, and the look-backs caught them while the files were fresh;
- no idea was left without a disposition;
- no governance boundary was approached.

**PAUSE is not needed**, but two things should be put before the Principal:
- the §1.14.1 correction changes a statement the audit already reviewed; a fresh reviewer should check the corrected form;
- the read-side gap (§1.17.5) concerns this research's own governance as evidence. That is an observation about the process, offered for information, not a proposal to change it.

**Next five files** (chronological, authorized background): `…1034` policy catalogue · `…1040` decision register · `…1044` domain events · `…1057` artifact gap analysis · `…1144` script/capability inventory.

---

## Correction note (2026-10-18, correction round 1; appended, the text above is not rewritten)

1. **§4, "nine sections … five were integrated", is wrong.** Lookback A added **10** rows for 9 uncovered V3 sections (the list above names only 7). Checked against the chapter text: **7 rows integrated**, 1 evidence only (F1-28, since integrated into Ch. 0 §0.9 question 6), 2 repeats with pointers, and §12 already covered. Source: `03-methodology/M-04-efficiency-review-20261018.md` §3.
2. **§8's CONTINUE was premature.** The self-review found one foundational error (Ch. 1 §1.17.2) and one omission of foundational relevance (V3 §10 lifecycle) that this batch's look-backs missed. Both are corrected in round 1 and sent for targeted independent review.
3. **§6, "every superseded statement … was updated", held only for the searches run.** The new coverage check found further propositional passages without a row: 13 new rows (8 for the omissions in the self-review, 5 more found by the coverage check) and 12 extended row locations now cover them (rows B3-F1-35…41, B3-F2-17, B3-F4-19…22, B3-F5-16; non-propositional lines in `06-theory/NONPROP-LINES.tsv`).
