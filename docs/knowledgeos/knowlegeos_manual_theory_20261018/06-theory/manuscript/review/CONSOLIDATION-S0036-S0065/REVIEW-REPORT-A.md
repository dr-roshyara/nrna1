*Saved verbatim 2026-10-19 from reviewer A's (Opus) step-2 hand-back. UNSETTLED: passed to the Principal for test scoring first. Harness: step 1 474 s; step 2 sent 15:38:34Z, received at this save.*

# REVIEW REPORT — CONSOLIDATION S0036–S0065 — reviewer A

**What I read:**
- SCOPE.md, RESEARCH-PURPOSE.md and BOOK-OUTLINE.md;
- THEORY-MAP.md forks F13a–F22 and the window blocks from C7;
- register rows CON-0099…0134;
- all sections in scope, using the consolidation copies of §6.4, §8.6 and §9.2;
- the section headings of Ch. 0–3 and 6.1–6.3 used for the sequence check.

**Tools run:** `book_search.py` (25 lexical and 8 meaning queries), `kernel_model.py` (all assertions passed; output matches §9.1 C4), and a recomputation of the §8.6 C4 counterexample (each LR 1.5, joint LR 50, distributions sum to 1).

**Independence:** I am a separate process from the Professor and from reviewer B. I did not open STEP1-INVENTORY-B or any other review folder. My step-1 inventory is the comparison base, and it is a purposive set, so I give **no rates**, only counts.

## 1. Assessment
- **Reliable within scope:** most sections, most concepts. Quotes and anchors check out, apart from the points below. Verdicts on concepts are mostly well hedged.
- **Largest risk:** the consolidation copies of §9.2 and §8.6 contain **two inverted source readings** (CF-1, CF-2). The section copies also **omit three load-bearing points** that the frozen chapter files still carry.
- **Architecture:** the derivation runs **case → existing Ch. 1 law**, not primitives → laws → results. "Requirement" and "trust" have no kernel definition.

## 2. Critical findings (stop dependent work)

| # | Section · anchor | What is wrong | Consequence | Correction |
|---|---|---|---|---|
| **CF-1** | §9.2 C2 (consolidation copy) · S0039 L326 | Claims S0039 "holds that a product's reusability can be declared from an inspection of its parts, before it has been used". The source says the opposite: "the reusable product **cannot be declared reusable until it has been used once**". This also contradicts §9.2's own C1 and §9.3 C3 (shown / refuted / not exercised). The old C2 (the litmus ≠ placement) is gone, so BOOK-OUTLINE §3 row "litmus is not placement · §9.2 C2 · ✓ (CON-0105)" points at nothing. | It reverses the corpus's must-do evidence for Ch. 9 (deliverable 5). | Restore the source reading (use only shows reuse) and the litmus ≠ placement claim. Re-link the outline row. |
| **CF-2** | §8.6 C1 (consolidation copy) · S0059 L21 | "S0059, by contrast, records full model separation from the corrector". The source says "MODEL SEPARATION FROM THE CORRECTOR: **NIL**". The section's own lineage line ("repeats S0056's model disclosure") and C5 ("S0059 bounds its missing model separation") contradict it. | It removes the second case for the model factor of CON-0125 and invents a contrast case. | Restore "NIL". The model factor rests on two disclosures from one line. |

## 3. Findings (ranked)

| Sev. | Type | Topic · anchor | What is wrong or missing | Correction |
|---|---|---|---|---|
| high | omission | §8.6 (copy) · S0164 L709, L3125, L3236 | Dropped: "Automation may reduce assurance cost but must never manufacture authority"; "a blocking checker … would require authority". This is my rank-1 item for S0164 and an A1 instance (capability ≠ authority, also EXT-007). | Restore it as C6, one source repeating itself. |
| high | omission | §6.4 C1 (copy) · S0037 L69 | Dropped: PD-2's lifecycle criterion is "FALSIFIED" by co-authorship, so the criteria are not independent and the ≥3 count overstates. This is the only evidence-based objection to the threshold beyond equal weighting. | Restore it. |
| high | omission | **trust** · S0913 L133, L188–198, L1398, L1426–1428 | "Trust ≠ Authority ≠ Expertise; trust never substitutes for authority; Trust(a, d, c, t)". The central question asks "how can it be trusted?" and the book has **no trust concept** (no lexical or meaning hit beyond CON-0018 ✗). S0913 was read for §8.6 only. | New candidate concept. Relate it to CON-0016 and A1. |
| high | omission | conflict over **claims** · S0913 L363–397, L450, L1086–1097, L1148–1161; S0129 L45, L102 (R-CONFLICT) | Missing: conflict as a first-class state with "Unresolved" as a valid terminal state; apparent conflict that dissolves under identity, time and context; a nine-way conflict taxonomy. Ch. 2 §2.3 covers rule conflict only. **Tension not recorded:** §9.4 C3 settles content disputes by one current claim plus supersession, i.e. a winner. The outline itself lists R-CONFLICT as missing. | Add it to Ch. 2/Ch. 4 as a fork: preserve or supersede. |
| high | defect (language) | "requirement" in §5.4 vs §9.1 | §5.4: an epistemic requirement of a contract (Sat, Δ, Zero). §9.1: a required capability = a reachable state. Same word, two objects, never related. The gap operator is the obvious candidate for deciding Req, but no link is stated. | Qualify both uses. State whether Zero(EC) is a member of Req. |
| high | defect (language) | "Zero" · S0913 L1165–1174, L1744–1752 vs S2377 L358–383 | In S0913, Zero = an unresolved conflict / cannot proceed. In S2377, Zero = Δ = ∅ (adequate). §5.4.1's "three meanings" misses this fourth one, which is opposite in sign. | Add Z4 or record it as a homonym. |
| high | omission | conformance scope · S0164 L1276–1285, L1313–1323, L1485, L1510, L1529 | Missing: "a neutrality claim never stated its layer"; conformance defined at the fact model; "two library defaults stood in for a decision" (cf. §2.8.7). This is a general law: a claim must declare the level it holds at. Only L1529 is used. | New section, or a §8.4 claim. |
| high | omission | self-attestation · S0091 L257, L796 | "a process cannot attest its own separation". No meaning hit. It is distinct from §8.6 C3 (the record is one-sided). | Add it to §8.6. |
| normal | defect | CON-0099 meaning vs §9.1 C1 vs RESEARCH-PURPOSE | The register says "A1 inconsistent with ⊥". The book says "under-specified, not inconsistent". The purpose says "every refusal would break it". Three statements, one concept. | Align on "under-specified". |
| normal | defect | CON-0129 register meaning and BOOK-OUTLINE §2 | Both still say renumbering is safe "exactly" when…, which the review restricted to a sufficient condition. | Fix both texts. |
| normal | defect (sequence) | §6.5 C2 · S0067 L59–69 | S0067's I-1…I-10 are used unmarked. They collide with the book's I-1…I-11 (§0.5; §8.5 C8 uses the book's I-7). §6.4 C3 marks S0037's numbering as local; §6.5 does not. | Mark them as document-local (I-n/S0067). |
| normal | defect (sequence) | §8.6, §9.4, §9.5 | Used but never defined in the book: INV-ATTR-1, INV-ATTR-2 (only in Ch. 1 TODO), Class-B and category C4, AST-007, Lord/Sārathi. The "trivial-solution test" lives in the Rulebook only. | Define them or gloss them at first use. |
| normal | defect | §8.6 C1 vs C5 and §8.6.4 | "Seven factors" in C1; "the five factors (C1)" in C5 and the open questions. | Use seven. |
| normal | defect | §4.3.3 vs §4.3 C1 | The open question asks about an "admission test" that C1 now says governs promotion. | Reword. |
| normal | generalisation | §8.2 C2 · S0038 L175 | "Claims of a gap in this corpus are often wrong", from 9 items, one sweep, one author. | Restrict it to the sweep. |
| normal | omission | §6.4 (copy) · S0039 L98 | Dropped: "Naming SP-5 … a non-functioning mechanism a better title" (naming ≠ existence). | Restore it. |
| normal | omission | §8.6 (copy) · S0164 L2007 | Dropped: "transcript authorship search is self-contaminating". | Restore it. |
| normal | omission | S0091 L169, L310–313, L715 | Missing: "role" has three meanings; communication = distribution (mechanism) + composition (stewardship); whether a match with an unread source is convergence or reconstruction is undecidable. That last point bears on the lineage rule. | Ch. 8/9. |
| normal | omission | S0164 L2420 | Missing: four orthogonal evidence dimensions (SUBJECT · EPISTEMIC · PROVENANCE · REPRODUCIBILITY). This bears on the map's open topic "evidence scales not related". | Ch. 1 §1.3. |
| normal | omission | S2834 L355–437 (q = g ∘ T); L864–878; L1995–2033 | Missing: representation adequacy relative to an inquiry; Unknown ≠ Uncertain; "Outcome ≠ Knowledge, Observation ≠ Evidence" (the loop is unproven). | Ch. 3 §3.4; §8.3 C3. |
| normal | omission | S0071 L80, L100–104, L262, L349 | Missing: "an invariant spanning instances proves the candidate is not the root"; the 33/32/16 closure asymmetry; session-name identity scope. | §6.5. |
| normal | omission | S0067 L94; S0113 L18–20; S0058 L18–35 | Missing: "seq, never wall-time authority"; choosing HALT decides the category by consequence; the wrong report cited (an unchecked referent). | §1.16.2; §9.5; §8.5. |
| normal | source defect | S0036 L114 vs S0039 L164, L265 | Same day: CAP-001 "1 execution, 1 decision changed" against "0 executions, 0 decisions changed". | List it; nothing relies on it. |
| normal | source defect | S0038 L102–109 vs L253 | "30 families, one class each", but the class rows sum to 32 (recomputed). | List it. |
| normal | stale | THEORY-MAP §8–§9 | "Last source read: S0035"; "42 of 71 concepts INVALID". Both are stale. | Update. |

## 4. Concept verdicts (detail in Appendix A)

**Second verdict on the 12 VALIDATED:**
- **VALIDATED (12):** 0099, 0102, 0104, 0105, 0107, 0108, 0111, 0120, 0121, 0126, 0129, 0131, each in the scope the register states.
- Text corrections still needed:
  - **0099:** the meaning field.
  - **0102:** drop "an invariant needs an operation", which merges "invariant" with "enforced"; §6.5 C2 itself has invariants held by convention.
  - **0105:** the concept stands; the section around it does not (CF-1).
  - **0129:** "exactly".

**PROVISIONAL (12): all confirmed PROVISIONAL.** CON-0125 is additionally weakened by CF-2.

**INVALID (12): all confirmed.** Each valid core as stated in the book holds. Two cores are **not minimal**:
- 0118's core duplicates CON-0108;
- 0133 duplicates ARCH-INV-1.

## 5. Architecture view

| Question | Answer |
|---|---|
| Primitives → laws → results? | **No.** 23 of the 24 claims I checked present themselves as "an instance of §1.x" (A1, §1.12, §1.14.3, §1.16.x). The in-scope sections add cases to Ch. 1 laws; they derive nothing from primitives. The outline admits the chain is missing. |
| Primitives complete? | **No.** The in-scope sources demand three candidates that are absent: **trust** (central question; S0913); **conflict state over claims** (S0913, R-CONFLICT); **requirement / contract** (§5.4) as a primitive. They also suggest one split: the record of an act vs the act (§6.5 C6/C7; F22). |
| Primitives minimal? | **Undecided.** No independence test has been run on any primitive. "History" may be derivable from an append-only record + I-7 + fold (§6.5 C4, CON-0134). |
| Load-bearing undefined object | **Req (T9-01).** Six claims say "needs a must-do partner" (§5.5 C3, §6.5 C1, §9.4 C7, §9.5 C1, C5, §9.3). Each defers to an object the book has not defined. |
| Isolated parts | §4.2/§4.3 (application process) and §8.6/§8.7 (review method) do not connect to the kernel. §5.4 is not connected to §9.1 (homonym above). |
| Circular parts | §9.2 C1 corroborates §9.1 with a counterexample drawn from the book's own toy model. This is valid as logic but is not corpus evidence. The section says "not counted as independent", which is correct. |

## 6. Kernel · minimal · stable
In short (detail in Appendix B):
- **Kernel candidates:** 0100, 0101, 0102, 0105, 0108, 0109 (core), 0111, 0114 (core), 0117, 0123, 0124 (restricted), 0127, 0131, 0132, 0134.
- **Application:** 0103, 0104, 0107, 0110, 0115, 0116, 0129.
- **Method:** 0106, 0112, 0113, 0119–0122, 0125, 0126, 0128, 0130.
- **Not minimal:** 0118, 0133, 0134 (each duplicates an existing law).

## 7. Spot-checked quotes and anchors
- I checked about 120 anchors against my step-1 reads and fresh `sed`. All matched except CF-1 and CF-2 and **one off-by-one:** the §8.6 quote "[S0056 L25–27]" is at L26–28.
- The §8.6 C4 counterexample and the §8.7 C3 figures (1/(1−m)) recompute correctly.

## 8. Next steps (in dependency order)
1. Repair CF-1 and CF-2 and restore the three dropped high items. *Done when* each matches its source line.
2. Define "requirement" twice, qualified, and state the relation of Zero(EC) to Req. *Done when* §5.4 and §9.1 cite each other.
3. Open T9-01 with the in-scope candidates (distribution §9.5, bootstrap §9.2, record/fold §6.5). *Done when* each has passed the three boundary questions.
4. Add trust and claim-conflict as candidate primitives. *Done when* each has a register row and a fork against §9.4 C3.
5. Fix the register meanings (0099, 0102, 0129), the outline and the map.

## 9. Limits
- I read only the in-scope sections, plus headings and grep results for Ch. 0–3. Ch. 1 laws are taken as stated.
- S0164 was read in parts (see step 1).
- `book_search.py` searches the chapter files, not the consolidation copies. Its hits for removed text (for example "manufacture authority") come from the frozen chapter versions.
- A search miss is not absence: "MISSING" means not found by my lexical and meaning queries and not seen in the sections read.

---

## Appendix A — verdicts CON-0099…0134

| CON | Verdict | Reason (scope) |
|---|---|---|
| 0099 | VALIDATED | Proof re-checked: under the closed reading ∅ is a proper subset of any M and preserves vacuously. The register meaning's "A1 inconsistent with ⊥" contradicts the book's "under-specified": fix it. |
| 0100 | PROVISIONAL | The C3 proof is valid under the closed reading. The toy model (re-run) is form only; R for KnowledgeOS is undefined. |
| 0101 | PROVISIONAL | Fidelity verified against S2377. The THM grades are correct. It omits S2377 L982 ("subject to preservation of previously valid requirements"). |
| 0102 | VALIDATED | As a three-way distinction. Drop "needs an operation" (that is "enforced"). |
| 0103 | PROVISIONAL | One author; criteria overlap. |
| 0104 | VALIDATED | A source restatement, AIP-14 as quoted. |
| 0105 | VALIDATED | A non-implication shown by counterexample. Section defect CF-1. |
| 0106 | INVALID | The kinds overlap; core = standing × recorded supersession, holds. |
| 0107 | VALIDATED | As a description of the S0040 design (no run). |
| 0108 | VALIDATED | The verdict→action map is a function; a mixed preimage forces error. |
| 0109 | INVALID | Core executor ≠ authorizer (n = 1) holds. |
| 0110 | INVALID | "Only" is defeated by S0041 L23; the core is dated, scoped to Run 1. |
| 0111 | VALIDATED | A conceptual non-identity; the S2834 example is asserted. |
| 0112 | INVALID | The regrouping holds. |
| 0113 | INVALID | The core (promotion needs performance) holds. |
| 0114 | INVALID | The core holds. |
| 0115 | PROVISIONAL | No owner; one line. |
| 0116 | INVALID | A declared constraint of one engine. |
| 0117 | PROVISIONAL | n = 2, one day; degenerate without Req. |
| 0118 | INVALID | The restricted core holds but duplicates CON-0108. |
| 0119 | INVALID | Two axes hold. |
| 0120 | VALIDATED | Scope: absence claims from searches. |
| 0121 | VALIDATED | The practice of one line; a possibility claim. |
| 0122 | INVALID | Purposive sample, 13 rows. |
| 0123 | PROVISIONAL | One line; F19 premise. |
| 0124 | INVALID | Restricted form PROVISIONAL (two cases, one estate). |
| 0125 | PROVISIONAL | Orthogonality not shown; CF-2 removes the second model case. |
| 0126 | VALIDATED | Deterministic, common-mode model. |
| 0127 | PROVISIONAL | Needs R ⊆ F; false recorded factors exist. |
| 0128 | PROVISIONAL | Counterexample recomputed (LR 1.5, 1.5 → joint 50). |
| 0129 | VALIDATED | As a sufficient condition; fix "exactly" in the register and the outline. |
| 0130 | PROVISIONAL | GW correct; i.i.d. premise unmet; no m estimable. |
| 0131 | VALIDATED | The proof is sound with both premises; one probe. |
| 0132 | PROVISIONAL | "Reason lives in" is not operational. |
| 0133 | INVALID | Duplicates ARCH-INV-1; F22 open. |
| 0134 | PROVISIONAL | Needs a fixed fold; not new (§1.14.1). |

## Appendix B — kernel · minimal · stable

| CON | K/A/M | Minimal | Stable |
|---|---|---|---|
| 0099, 0100 | kernel type | ? | Y |
| 0101 | K (gap) | N (classes overlap) | Y |
| 0102, 0108, 0131 | K law | Y | Y |
| 0105, 0111 | K type | Y | Y |
| 0109 core, 0114 core | K | Y | Y |
| 0117, 0123, 0124r, 0127, 0132 | K candidate | ? | Y |
| 0118, 0133, 0134 | K | **N** (duplicates) | Y |
| 0103, 0104, 0107, 0110, 0115, 0116, 0129 | A | — | N (tool- or ADR-bound) |
| 0106, 0112, 0113, 0119–0122, 0125, 0126, 0128, 0130 | M | — | Y (method) |

## Appendix C — items from my inventory judged COVERED or JUSTIFIABLY EXCLUDED

**COVERED:**
- S0040 stages and projections;
- S0044 RD-1, RD-6, RD-7;
- S0045 preventive, n = 2, no baseline;
- S0041 instrumentation n = 1;
- S0043 ladder;
- S0046 two axes;
- S0047/S0048 distribution;
- S0049 F-1, F-3, F-4, F-5;
- S0050/S0052/S0053 acts;
- S0054/S0055 method, V-E, calibration;
- S0056–S0061 correction chain and acceptance scope;
- S0062 tests;
- S0067 adjudicated vs folded, boundary sentence;
- S0071 fold, T-2;
- S0091 absence rule, invariant asymmetry;
- S0113 detectability;
- S0129 recorder ≠ decider and accidental reconstruction (§1.13.2);
- S2377 gap theory;
- S2834 Determine ≠ True.

**JUSTIFIABLY EXCLUDED (application-level):** S0049 F-2 event bookkeeping; S0164 assurance classes A–E and batching; the S0164 durability-migration specifics.
