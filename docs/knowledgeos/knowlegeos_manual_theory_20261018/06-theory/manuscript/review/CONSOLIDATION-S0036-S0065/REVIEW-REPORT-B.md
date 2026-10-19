*Saved verbatim 2026-10-19 from reviewer B's (Sonnet) step-2 hand-back. UNSETTLED: passed to the Principal for test scoring first. Harness: step 1 405 s; step 2 sent 15:37:29Z, received at this save.*

# REVIEW REPORT — CONSOLIDATION S0036–S0065 — reviewer B

Process independence only (no other reviewer's work seen). Read-only. Before writing I re-read my saved Step 1 inventory. Checks I ran:
- 40+ quote anchors against the sources, with the line numbers as given in §6.4, §6.5, §8.2–8.7 and §9.1–9.5. All held except the two planted defects (C-1, C-2) below.
- `kernel_model.py` (reproduces the §9.1 table, all assertions pass).
- The LR counterexample of §8.6 C4 (recomputed: 1.5, 1.5, joint 50) and the Galton–Watson figures of §8.7 C3 (10 and 100).
- `book_search.py` for every "missing" claim.

**Disclosure (method).** I compared the three review-folder section copies (`sections/06.04`, `08.06`, `09.02`) against the chapter copies by `diff`. I found the §9.2 and §8.6 errors by reading and source check, and I confirmed them with the diff. The omissions in 06.04 and 08.06 (C-1 context below, completeness rows 11–12) I found **only** by the diff. The planted-gap count should treat those as non-blind.

## 1. Executive findings
- **Reliability.** The in-scope sections quote accurately and keep their scope. They are mostly reliable within their stated scope.
- **Largest structural risk.** There is no derivation chain from primitives to laws to results. Ch. 9's kernel test is a toy whose required capabilities (Req) are invented. Whole families of laws rest on an assurance model that no section defines.
- **Most serious local defects.** Two source distortions in the frozen section copies:
  - §9.2 C2 inverts S0039 L326.
  - §8.6 C1 misreports S0059 L21.
- **Largest omission.** Nothing in scope covers trust and evidence-source conflict (S0913), although that is the central question ("how can it be trusted?").

## 2. Critical findings (marked separately)
| # | Sev. | Finding | Anchor | Correction |
|---|---|---|---|---|
| C-1 | **critical** (section) | §9.2 C2 says S0039 "holds that reusability can be declared from inspection before use". The source says the opposite: "cannot be declared reusable until it has been used once". This contradicts §9.2 C1 and CON-0105's own evidence. BOOK-OUTLINE §3 cites "§9.2 C2" for "litmus ≠ placement", which the frozen section lacks. | S0039 L326, L324; §9.2 C2 | Restore the chapter's C2 (litmus ≠ placement) and C3 (cannot be declared until used). |
| C-2 | high | §8.6 C1 says S0059 "records full model separation from the corrector [S0059 L21]". S0059 L21 reads "MODEL SEPARATION FROM THE CORRECTOR: NIL". The section contradicts itself: its lineage line and C5 say S0059 repeats the missing separation. The false contrast distorts the CON-0125 evidence on the model factor. | S0059 L21–22, L27–30 | Replace with "records model separation NIL". |
| C-3 | high | The assurance model (Assurance Claim, Assessment, outcome ladder, Governed Act, dimension, C4 Independence claim, INV-ATTR-1/2/3, "Declared") is used in §5.6, §8.6 and §9.4 and defined nowhere. CON-0114, 0115 and 0116 and §9.4 C1–C7 are conditional on it. | `grep`: terms occur only in 05.06, 08.06, 09.04 | Add a defining section (home Ch. 1 or Ch. 6). |
| C-4 | high | §6.5 C2 uses BC-7's I-1, I-3, I-4, I-5, I-7, I-8, I-10 unqualified. The book's I-1..I-12 (Ch. 0 §0.5) differ (I-1 "knowledge never silently disappears", I-7 "supersession is a link", I-8 "no second population"). §6.4 C3 flags only I-7. CON-0132 therefore reads ambiguously. | §6.5 C2; 00.05 L7, L13, L14 | Prefix "BC-7:". |

## 3. (1) Completeness: what is in my inventory and missing or distorted in the book (ranked)
| # | Inventory point | Status | Type |
|---|---|---|---|
| 1 | S0039 L326 | DISTORTED (C-1) | defect |
| 2 | S0913 L40–199, L1381–1432: trust is contextual, Trust ≠ authority, Trust ≠ truth, trust is itself knowledge with provenance, feedback bias | MISSING (S0913 used only for independence and LR) | omission |
| 3 | S0913 L314–433, L525–562, L1081–1220: apparent vs genuine conflict, resolution pipeline, "Unresolved" as a terminal state, resolution record. Ch. 2 covers rule conflict only. | MISSING | omission |
| 4 | S0164 L2233–2300, L2420–2435: establishing a capability (E1–E10, 17 counterexamples, Option A unsatisfiable because E10 needs history, four evidence dimensions). Appears only as one open question in §8.6. Bears on how Req can be shown. | PARTLY | omission |
| 5 | S0164 L709: automation never manufactures authority | MISSING in the frozen §8.6 (indexed chapter copy has it as C6) | omission |
| 6 | S0129 L43–62, L45, L101–103: authority record never runtime's to export, R-CONFLICT, "History ≠ Reconstruction Guess", Execution ≠ Governance-Evidence context | MISSING (outline marks it known) | omission |
| 7 | S0164 L1196–1204, L1467–1485: a neutrality claim over an unstated layer; boundary at the fact model; defaults standing in for decisions. This is a model for the kernel/application boundary claim. | MISSING | omission |
| 8 | S2834 L864–880, L2039–2081: Unknown ≠ Uncertain, E_t / A_t / K_t, factive Knows | MISSING (F13a half) | omission |
| 9 | S0091 L169–172, L113–124, L250–257: "role" has three meanings; capability / stewardship / context; a process cannot attest its own separation | MISSING / partial | omission |
| 10 | S0036 L55–57 stopping rule and uncertainty carriage; S0039 L281 AR-7 | MISSING (map lists) | omission |
| 11 | S0037 L69 (criteria not independent); S0039 L98 (06.04) | MISSING in frozen copy | omission (comparison-found) |
| 12 | S0164 L2007 self-contaminating transcript search | MISSING in frozen copy | omission (comparison-found) |

Justifiably excluded: S0049's 13-event table, the S0164 AMD3–6 migration detail, S0071 event names, the S0062 ADR-C list.

COVERED: S2377 (including S2377's self-tension F15, the "seven classes" slip, the age sign), S0044, S0040, S0045, S0067 and S0071 boundaries, S0052–S0059.

## 4. (2)+(5) Verdicts for CON-0099..0134 · layer (K kernel-candidate / M method / A application) · minimal · stable
Second independent verdict on the 12 VALIDATED: I agree with all 12, with scope notes.

| CON | My verdict | Reason | Layer · min · stable |
|---|---|---|---|
| 0099 | VALIDATED (scoped) | Safety is subset-closed, so ∅ is the unique minimal set. Hidden premise: the initial state is valid. Scope is A1∧A2 only. | K · yes · yes |
| 0100 | PROVISIONAL | Satisfiable and ∅/refuse/noop excluded (rerun). Req is supplied post hoc, so existence is trivially satisfiable for any chosen Req. Minimality depends on the pool. | K · pool-relative · partly |
| 0101 | PROVISIONAL | Fidelity good. THM-G1/G2/G5 restate definitions. Sat is open. F15 unresolved. | K? · no (overlapping G1–G10) · no |
| 0102 | VALIDATED, restricted to stated ≠ enforced | "Held" has two senses (true in the estate vs maintained by discipline: §6.4 C3, §6.5 C2, §5.5 C2). The three-way trichotomy is not shown. | K-law · yes · yes |
| 0103 | PROVISIONAL | The two criteria leave a gap (shared lifecycle with distinct invariants). One author, two verdicts. | A · no · no |
| 0104 | VALIDATED (scope AIP-14 as quoted) | Faithful. The authority holder is the ARB/DA, not "the product". Estate fact, n=1. | A · yes · no |
| 0105 | VALIDATED | A counterexample ({record} vs {record,grant}) is sound. | K · yes · yes |
| 0106 | INVALID | Kinds overlap (S0038 L156 vs L222); the replacement (standing × supersession) is fine. | A |
| 0107 | VALIDATED (source description only) | The faithful reading was checked (S0040 L14–41). No run is shown, and §4.2 C1 "a decision is a human act" meets §4.3 C5b (an AI decided). | A · yes · yes |
| 0108 | VALIDATED (blocking enforcement) | Near-analytic. The S0036 instance is unverified, as the section says. | K-law? · yes · yes |
| 0109 | INVALID | "Decider" is a homonym; the core executor ≠ authorizer is kept. | K (F13a) |
| 0110 | INVALID | "Only/never" defeated by OE-KOS-3 and S0045's fourth mechanism (S0045 L64–67). | A |
| 0111 | VALIDATED (non-identity) | Needs one shown instance. §9.1 C4 supplies it (R2 met by a composite); S2834's is only asserted. | boundary · yes · yes |
| 0112, 0113, 0114, 0116, 0118, 0119, 0122, 0124 | INVALID | Restated valid cores are correctly stated in the book. 0122: 13 rows, purposive sample (verified, S0055 table L61–73). | M/A |
| 0115 | PROVISIONAL | One line. Model undefined (C-3). No owner. | A · – · no |
| 0117 | PROVISIONAL | n=2, one day. Degenerate without Req. | A/K? |
| 0120 | VALIDATED | Procedure. "Exhaustive" = scope, method, completeness stated. | M · yes · yes |
| 0121 | VALIDATED (possibility claim) | One line (S0054→S0055). V-A shows method independence is not sufficient. | M · yes · yes |
| 0123 | PROVISIONAL | One line. Relative to a premise (F19). | K-act |
| 0125 | PROVISIONAL | Seven factors; orthogonality and completeness not shown. Frozen text carries C-2. | M |
| 0126 | VALIDATED (common-mode, deterministic) | Proof correct but analytic. The substance is the premise in 0125. | M · yes · yes |
| 0127 | PROVISIONAL | Needs R ⊆ F; the corpus has false recorded factors. | K-boundary |
| 0128 | PROVISIONAL | Product rule standard; the counterexample "joint 50 from two 1.5" verified. "Between" invalid. Lineage rule is a heuristic. | M |
| 0129 | VALIDATED (sufficient condition) | Premise: inside references are updated. **Register meaning still says "exactly when"** (stale). | A · yes · yes |
| 0130 | PROVISIONAL | The theorem is right (m ≤ 1 non-degenerate; E[N] = 1/(1−m)). The i.i.d. premise is unmet and no m is estimable. | M |
| 0131 | VALIDATED (scope: sole enforcement point, caller supplies scope) | Proof tautological from the definition. The engine instance is one unrerun probe (S0058 L82–88). | K-law · yes · yes |
| 0132 | PROVISIONAL | "Reason lives in" is not operational. One line. | K-boundary? |
| 0133 | INVALID | Duplicate of ARCH-INV-1; F22 stands. | – |
| 0134 | PROVISIONAL | Needs a fixed fold; the interpreter is swappable (S0062 L112). Not new (§1.14.1). | K-law |

## 5. (3) Sequence check
| Concept | Used in | Problem |
|---|---|---|
| Assurance model terms, INV-ATTR-*, Declared | 05.06, 08.05, 08.06, 09.04 | never defined (C-3) |
| "Lord" | 05.04, 05.05 | used without a definition (S2377's role) |
| BC-7 I-1..I-10 | 06.05 | collide with book I-1..I-12 (C-4) |
| "A1", "A2" | 09.01 | two meanings in one section: the EXT preservation axiom vs Ch. 1 A1 (act axiom) |
| EKS-01, AST-007 | 09.05 | source IDs undefined in the book |
| §5.4 / §6.3 / §5.5 | 04.02, 04.03 | forward references only, acceptable |

## 6. (4) Architecture view
- **Primitives.** BOOK-OUTLINE lists seven candidates (identity, lifecycle, authority, evidence, relationships, policy, history). Three have no concept ID or lack a split ("authority" in three senses, identity I-0, policy). **Not complete, and minimality was never tested** (no independence model for any).
- **Derivation.** Laws are proved locally (CON-0099, 0105, 0126, 0129, 0131). No chain links them to the primitives. Ch. 9 uses a toy state (claims/acts/standing, F13 open). This part is isolated from Ch. 1.
- **Circularity and weak support.**
  - Req is the author's own, and 9.5/9.3 add Reqs by classing corpus failures. That is a post-hoc fit, so "existence" cannot fail.
  - Support is mostly one estate-line (the Group C lineage rule), correctly counted as one.
  - F22's premise "plane ⇒ kernel candidate" is unstated; a cross-cutting mechanism can still be delegable.
- **Strong parts.** Ch. 5 §5.4, §9.1, and the proofs of §8.6 C2 and §8.7 C2–C3. They are explicit about premises.

## 7. (6) Quote and generalisation check
Anchors verified as above. No new rate is estimated without a random sample: §8.2 C2, §8.5 C3 and §8.7 C3 state "count only". THEORY-MAP §9 Position and "42 of 71" are stale (register now 134).

## Appendix
- **Source defects (listed separately).** S0055 "11 of 12" (13 rows); S0049 E-R1 count; S2377 "seven" vs ten classes, age sign, ⊥ homonym; S0067 "2 of 5" vs eight transition types.
- **Normal findings.**
  - THEORY-MAP position block is stale.
  - §4.2 C1 conflicts with §4.3 C5b.
  - CON-0129 register text.
  - "Held" homonym (CON-0102).
  - §6.4 C3's "I-3 held, not enforced".
- **New forks/topics.**
  - Evidence-source trust and conflict resolution (S0913).
  - Contract/neutrality scope as the kernel boundary claim (S0164 L1467).
  - Establishing a required capability (S0164 D5).
  - Authority-record durability, R-CONFLICT (S0129).
- **Not read in full.** S0164 L2520–3328 (sampled); S0001 L29 and the S0025 test record (not opened).
- **Questions for the Principal (carried).** Was the "Principal DDD Architect" in S0064 human? F22: plane or context?
- **Planted-gap measure.** Independent finds: C-1, C-2, completeness rows 5, 13 (via the outline). Comparison-found only: rows 11–12, and the 06.04 omissions.
