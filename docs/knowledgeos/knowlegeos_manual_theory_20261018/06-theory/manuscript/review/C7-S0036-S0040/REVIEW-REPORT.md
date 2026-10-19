*Saved verbatim 2026-10-19 from the reviewer's step-2 hand-back (group C7; one step-2 message).*

C7 TRANSITION REVIEW: STEPS 2–3 REPORT (S0036–S0040, plus the new theory §9.1, §9.2 and §5.4 C7–C8)

Process independence only: step 1 was blind; I re-read my saved inventory (STEP1-INVENTORY.md, 103 lines) before comparing. I wrote no files. The checks below were run in memory (python3 -I on stdin, importing tmp/tools/kernel_model.py); commands are given under Limits.

## 1. Assessment (3 lines)
- The C7 reading is mostly faithful to the sources. The quotes and anchors I re-checked against S0036–S0040 and S2377 match, and the scope notes are honest.
- Ch. 9 §9.1 is a sound **reason** for required capabilities, but two of its stated **results** do not hold as written. The "not unique" result comes from a duplicate mechanism. "Minimal" depends on which mechanisms are on offer (the pool) and how finely they are cut (granularity).
- Largest risk: deliverable 5 (RESEARCH-PURPOSE item 5) and T9-04 are already built on that non-uniqueness result.

## 2. Findings (ranked)

| # | Sev | Type | Claim / topic | Source line | What is wrong or missing | Consequence | Correction |
|---|---|---|---|---|---|---|---|
| 1 | **critical** | defect | §9.1 C3 "minimal kernel is **not unique**" (also in 09.00, RESEARCH-PURPOSE item 5, T9-04) | kernel_model.py L81: `'grant2': grant` | grant2 is the same function object as grant (`POOL['grant2'] is POOL['grant']` → True). EXT-011 L237–241 defines mechanisms as functions, so M is a set of functions and {record, grant2} = {record, grant}. In the stated model the minimal kernel is **unique**. | The headline result and T9-04 rest on a labelling artifact. Work that depends on it must stop. | Genuine non-uniqueness needs functionally different mechanisms. With an added composite `recgrant` (record + grant in one step), the minimal kernels are {recgrant} and {record, grant} (computed). Restate C3, 09.00, RESEARCH-PURPOSE item 5 and T9-04. |
| 2 | high | omission | §9.1 C2/C4: what "minimal kernel" and "existence" mean | toy model; Ded | (a) P holds per mechanism, so it is closed under union; Req is monotone. Both are verified over all 128 subsets. **Hence:** a kernel exists iff the set of all individually preserving pool mechanisms satisfies Req (here: true). Existence is decided by one reachability check. (b) Mechanism-minimality depends on the pool and its granularity: one composite mechanism gives a size-1 minimal kernel. With an unrestricted pool a single "jump" mechanism always exists. | "Minimal kernel" is not well-posed until the pool, i.e. the interface A, is fixed. This is the real content of T9-03. | Add both as Ded with proof. Make T9-03 depend on fixing the pool or the interface. |
| 3 | high | defect (scope) | §9.1 C1 "as written, the scaffold is degenerate"; existence "trivially yes" | EXT-011 L230 ("operations that **affect or constrain** state transitions"), L253–259 (Constraint type); EXT-010 L87 | C1 is valid only under the closed reading (mechanisms are the only transitions). Under the guard reading, which the source's own definition allows, removing a guard can break the invariants, so A2 is not vacuous. A trivial "refuse-all guard" then satisfies P and A2 instead. "Trivially yes" covers only the A1∧A2 fragment; EXT-010 L220 asks about all its requirements. The ⊥ "inconsistency" disappears under the source's own "partial function" reading. | The need for Req survives both readings. The statement "∅ is the only minimal set" does not. | State both readings and the trivial case for each. Weaken "inconsistent" to "under-specified". Rename "liveness" to reachability (an EF property, not liveness). |
| 4 | high | defect | §6.4 C3: "the repository already violates" V-1 **and V-2** | S0037 L239; S0039 L202; Ch. 8 §8.1 CON-0059 ✓ | The V-2 breach rests on EAD-1's lexical zero-election-terms test. S0039/S0025 then found the trio to be "mostly non-portable case law", and CON-0059 holds that a lexical test cannot establish a semantic property. So V-2 is **not shown to be breached**; it may hold. V-1 stands. | CON-0102's example of a breached invariant is half unsupported. | Mark V-2's breach as unestablished and cite CON-0059. |
| 5 | high | omission | §6.4 C3 takes I-3, "OBSERVED AND MACHINE-ENFORCED", at face value | S0037 L78, L228 vs S0036 L116, L198 | The same day, S0036 lists knowledge-lint (C-15) as advisory: it "runs only when invoked". "Enforced" is a homonym (lint severity vs blocking). By CON-0092, I-3 is **held by a check that runs**, not enforced. | §6.4 defines stated ≠ held ≠ enforced and then misfiles its own example. §6.4 C8 also misses this, which is the closest contradiction. | Re-label I-3. Record the homonym. |
| 6 | high | defect | §6.4 C1: "≥ 3 supplies a sufficiency condition, and so makes verdicts reproducible" | S0037 L96–97 (PD-4 meets the bar and is BLOCKED by H-CAT-1), L103 (PD-5a at the bar, "unexercised") | Even in its source, ≥ 3 is **not treated as sufficient**: a standing refusal overrides it. Reproducibility is not shown; there is one rater. The count also includes "transactional consistency" in the non-DDD sense that §6.2 C2 already rejects (S0037 L53). | C1's first step is distorted. | Restate: "≥ 3 makes the rule determinate given the scores; the source itself overrides it; reproducibility untested." |
| 7 | high | omission | §5.4 C8 item 3, "a larger gap can be progress" | S2377 L1745, L2588 vs L2171–2256; L2181 | S2377 contradicts itself: "GapReduction ⇒ EpistemicImprovement" and the frozen ⪰ order, against §54/§55. The cause is undiagnosed. If the requirement "was not represented" (L2181), the contract changed and THM-G4 makes the states incomparable, so there is no conflict. If Sat is judged against the system's own beliefs, discovering a false "satisfied" enlarges Δ under a fixed contract, and the order calls progress worse. | This is a new fork (F-new-1: does Sat mean "true in the world" or "satisfied by what the system believes"?). It decides whether the gap order can be used at all. | Add the fork. Rewrite item 3 with the two cases. |
| 8 | high | defect | CON-0106, three kinds of duplication; §8.2 C3 "versions are not a breach" | S0038 L156 vs L222; S0037 L220; Ch. 2 §2.10 (rulings have no supersession links) | S0038 classes the two decision templates and the three BC instruments as "historical evolution" (L156), then calls them symptoms of one failure cause (L222); S0037 counts them as symptoms too. A "version" without a recorded supersession is a second authoritative home. | The classes overlap, so the classification fails as stated. | Classify by two variables, authoritative standing × supersession recorded. Only "authoritative, not superseded, ≥ 2" is a breach. |
| 9 | high | defect | §9.2 C1: "The test then failed for **exactly** that reason"; "no element-wise test can decide it" | S0025 / §8.1 lesson 2 (CON-0059) | The bootstrap test also showed a per-element failure: the parts were not portable (73% case law). The Ded is too strong: preservation **is** decided element by element (finding 2). The valid form is a counterexample: {record} and {record, grant} have the same per-element status and differ in sufficiency. | The corpus link to §9.1 is overstated. | Restate as a non-implication with that counterexample. Drop "exactly". |
| 10 | normal | defect | §9.2 C2: the litmus is "the first boundary question, applied to the product" | S0039 L33 vs L212/L234 | Boundary question 1 removes the **capability** and checks what is lost (necessity). The litmus removes the **product** and checks whether the element survives (independence). These are converse directions. It is also not shown that the L33 litmus (applied to products) is ES-005.3 (applied to elements). | The conclusion "product-independent, not kernel" stands; the mapping does not. | Remove the identification with question 1. Mark L33 vs ES-005.3 as undecided. |
| 11 | normal | omission | §5.4 C8: proof status of S2377's theorems | S2377 L393–440, L992–1022, L1796–1831, L1465–1555, L2289–2325 | THM-G1, G2 and G5 only unfold definitions. THM-G4 is a stipulation. THM-G6 is an assumption (completeness is OPEN at L2643). THM-G3 is valid for finite R. S2377's own g_obs = 1 − O_r (L1400) and g_time break THM-G3's premise d_r = 0 ⇔ Sat. | The book lists "theorems" without grading them, against the proof-checking rule. | Add a proof-status table. |
| 12 | normal | omission / lineage | §5.4 C8: S2377 compared only with S0896 | S2377 L532–536 ("Unknown Value ≠ Unknown Dimension… identified in the derivation"), L738 | The "attached derivation" carries S0721's distinctions (E10, the unobserved/unobservable kinds), so shared origin with **S0721** is likely. "Absent in S0896" (model, observability, …) rests on a source that was consulted, not read: a search miss. | Lineage is under-counted, and absence is asserted. | Add S0721 to the lineage check. Mark the S0896 "absent" cells as unverified until S0896 is read. |
| 13 | normal | lineage | §6.4 C8 "Both call the loop a destination"; §8.2's index remedy | S0036 PG-8 L252/L269 = S0038 M-5; "0 traversals" in S0036–S0039, all from EAD-1 | S0036–S0039 are one line: same day, one author/operator (S0039 AR-6), annotating each other (S0038 corrects S0036; S0037 L220 uses S0038's count of 10; S0039 is annotated with S0025). | Agreement among them is not corroboration. | State "one line of support" at C8 and §8.2. |
| 14 | normal | defect | CON-0108, the enforceability condition | S0036 L263; CLAUDE.md: doc-placement exit 2 "unruled → record PENDING and escalate" (repository instructions, seen in my context, not a named source) | S0036's enforceable example, placement, also has a verdict that needs a person. "Decidable" is a homonym: INCONCLUSIVE is a decided output. The real condition is that a verdict value covers both should-pass and should-block cases. | The concept survives once restated; S0036's choice of C-17 is unverified. | Restate (see §3). Check exit 2 when the source is read. |
| 15 | normal | defect | CON-0103, capability identity | S0036 L129 vs L137; L183–186 | The two criteria are not complementary; in practice lifecycle alone decides. S0036 counts its "one capability" as five kernel capabilities (L129) and gives them different kernel statuses, against "single lifecycle". | The criterion is under-specified. | Keep PROVISIONAL. Note the inconsistency in S0036. |
| 16 | normal | defect | §4.2 C1 "each stage can be re-derived from the ones before" | S0040 L22–27, L38 | A Decision is a human act and an Outcome is a measurement; neither can be derived. Only projections can. C5's mapping to CON-0077 overclaims: the watch criteria have no threshold (S0040 L50). C2's Int treats an advisory recommendation as if it were a requirement. | — | Restrict C1 to projections. Weaken C5. Scope C2. |

## 3. Concept verdicts

| CON | Verdict | One-line reason · scope | Kernel/app · minimal · stable |
|---|---|---|---|
| 0099 | **VALIDATED** | Proof checked: P is per-mechanism, so it is downward closed; ∅ preserves vacuously; so only ∅ satisfies A1∧A2. Scope: closed reading, A1∧A2 fragment only (finding 3). | meta (the kernel's type) · n/a · stable |
| 0100 | **PROVISIONAL** | The definition is satisfiable and rules out ∅, refuse and noop. But minimality depends on the pool, and the non-uniqueness evidence is an artifact (findings 1–2). | kernel type · minimality ill-posed until A is fixed · stable as form |
| 0101 | **PROVISIONAL** | Fidelity verified. The S2377 contradiction (finding 7), the proof grades (11) and the lineage (12) are missing. | **application** candidate: Δ is derivable from K, EC and Sat · not a primitive · stable |
| 0102 | **VALIDATED** (as a distinction) | Stated, held and enforced are three logically separate properties. The examples are wrong: V-2's breach is unestablished; I-3 is held, not enforced (findings 4–5). | kernel law (each invariant needs an owner) · minimal · stable |
| 0103 | **PROVISIONAL** | One author, two verdicts; the criteria overlap (finding 15). | application (engineering-kit sense of "kernel", F14) · — · unstable |
| 0104 | **VALIDATED** (restated) | Rules run platform → product. The **criterion** for the platform's change runs product → platform, and its holder is the ARB/DA, not the product. Scope: AIP-14 as quoted in S0039. | application/governance · — · stable |
| 0105 | **VALIDATED** (restated) | A non-implication, shown by counterexample (finding 9). "Exactly that reason" is withdrawn. | kernel meta (requirements on the whole set) · minimal · stable |
| 0106 | **INVALID** as stated | The classes overlap; S0038 classes the same items twice (finding 8). | derivable from standing × supersession · not a primitive · — |
| 0107 | **VALIDATED** (as a source description) | Fidelity checked against S0040 L14–41. It describes a process and reports no run. C1's re-derivation claim is corrected (finding 16). | **application** (S0040 L45) · — · stable |
| 0108 | **VALIDATED** (restated) | A check cannot block automatically without error on a verdict value whose cases include both should-pass and should-block actions. Scope: blocking enforcement. | application (enforcement) · derivable with CON-0092 · stable |

## 4. Coverage

| Inventory point | Mark |
|---|---|
| S0036 discovery stopping rule ("when is discovery done?", L55) and uncertainty carriage (L57) | **MISSING.** The chapter plan names a stopping rule for Ch. 5; this is its first source. |
| S0036 counts its one capability as five (L129 vs L137) | MISSING (finding 15) |
| S0037 PD-3 ownership read from a frontmatter `owner:` field (L77) | **MISSING.** A metadata value is read as semantic ownership. Bears on §1.11. |
| S0037 I-6 void-overriding; I-5 governance precedes automation | COVERED (Ch. 2) |
| S0039 AR-7: without L-6, "earns its abstractions" is unfalsifiable; L-3 has no baseline | COVERED in part (§4.1). AR-7 MISSING. |
| S0039 "reusable ≠ replaceable" | COVERED (§6.4 C6) |
| S2377 §25 hedge dropped at §63; §42 union vs THM-G4; backbone K → EC vs Knower → EC order (L2486 vs L2713); typed-gap carrier P(R × Types) | **MISSING.** The backbone order bears on ContractDerivation. |
| S2377 seven vs ten classes; age sign; ⊥ with two meanings | COVERED |
| Rival causes: authorities (S0037) vs index (S0038) | COVERED (§6.4 C2, §8.2) |

**New forks:**
- F-new-1, the reference point of Sat: true in the world vs satisfied by what the system believes (finding 7).
- F-new-2, mechanism granularity, i.e. the pool fixed by A (finding 2).
- F-new-3, what a mechanism is: a transition vs a guard (finding 3).

**Errors in the book (separate from source defects):**
- §9.1 C3 says "eight mechanisms"; the pool has 7 (128 subsets).
- §6.4 C1 says "S0001 **later** finds … 'insufficient alone'". S0001 comes earlier, and grep finds no such phrase in Ch. 1.
- The source defects in S2377 are already recorded; nothing relies on them.

## 5. Next steps (in dependency order)
1. Fix the toy model: remove grant2 or make it differ; add recgrant. Rerun and restate §9.1 C3, 09.00, RESEARCH-PURPOSE item 5 and T9-04. *Done when* the run lists functionally distinct minimal kernels.
2. Add to §9.1 the union-closure/monotonicity existence result and the dependence on the pool (T9-03 depends on A). *Done when* both are written as Ded with proof.
3. §9.1 C1: give both readings; reachability, not liveness.
4. §6.4: findings 4, 5 and 6, then CON-0102's examples.
5. CON-0106: re-model by standing × supersession; check §8.2 C3.
6. §5.4 C8: add F-new-1, the proof-status table, S0721 lineage, and the S0896 "absent" cells marked unverified.
7. §9.2 C1–C2 (findings 9–10); then the normal findings at the next consolidation.

**For the Principal:** none required.

## 6. Limits
- Not opened: S0896, S0721, S0025, EAD-1, RQ-002, ES-005.3, EXT-012. Anything they are said to contain is reported, not checked.
- The EXT files were read only around their cited ranges: EXT-010 L1–161 and L210–226; EXT-011 L215–285; EXT-008 L115–140; EXT-014 L220–265.
- Dependent sections were read in part: §8.1 and §2.10 in full; §6.2 C1–C2; §1.11 and §5.3 by grep. §3.8, §3.9, §4.1 and §1.8 were checked only through their register rows.
- Computation, in memory, from python3 -I on stdin, importing kernel_model.py:
  - `POOL['grant2'] is POOL['grant']` → True; pool size 7;
  - P per-mechanism and Req monotone, both True over all 128 subsets;
  - kernel(union of preserving mechanisms) = True;
  - with recgrant added, the minimal kernels are [['recgrant'], ['grant', 'record'], ['grant2', 'record']];
  - with R = {}, the only minimal kernel is ∅.
  A passing toy test is not a general proof; the general claims rest on the Ded.
- Time: about 50 minutes (estimate, not measured).

## Appendix (lower priority)
- §8.2 C1 "a sweep of unindexed regions would miss indexed ones": S0038 does not say the sweep was restricted to unindexed regions. "At least two causes" rests on one case attributed by the author; the supported form is that a missing index does not explain every case.
- §5.4 C7's proof that inclusion keeps *proceed* is valid. Its hidden premise: criticality is fixed by the contract.
- C8 table, Prohibited/NotApplicable "absent" in S2377: applicability is handled partly through a requirement's validity field and membership in R_t (L231).
- S2377's Sārathi(Δ, Proposal, Authority) brings authority in at the decision step; link it to Ch. 1 A1.

Running measure for this review: 16 findings; 1 critical; 4 material omissions (findings 2, 5, 7 and 11); 0 defects found later in work that passed an earlier review.
