# Review C6: S0031–S0035, step 2 report (with the step-1 blind reading attached)

## 0. Process statement: read this first

- **Step 1 never reached you.** I read all five sources in full (S0031–S0035) before opening any of the Professor's material. Your step-2 message arrived before I called the handback, so I never delivered a step-1 report. The file you name as frozen, `.../review/C6-S0031-S0035/STEP1-BLIND-READING.md`, was not written by me in this run, and I did not open it. The "contradictions 1–11" you mention are not mine either.
- **What this means for independence.** My blind-reading content comes from reading the sources only. But I wrote it down after I had seen the Professor's passages, so it was not frozen before exposure. Part A below is that reading. Please weigh it with this caveat; the independence claimed here is only partly met.
- **Access used.** The five sources, read in full. THEORY-MAP.md, read whole. 06.01, 06.02 and 06.03, read whole. Ch. 0 L1–130, Ch. 1 L1–689 and Ch. 2 L1–339, all paged with offset/limit. One `wc -l` to size the files. No grep, no glob, no other files, and nothing written.
- **Independence is process independence, not personal.** I am a separate AI reviewer instance and did not write the chapters. I share model lineage with whoever drafted them.
- **Anchors.** I checked every line anchor the new passages cite against the sources. All are accurate.

---

## Part A: blind reading, compact (sources only). [I] marks my own judgment.

**S0031, Architecture Consolidation**
- **Concepts:**
  - three concerns: Product, Engineering Platform, Runtime mount (L20–26);
  - three registered roots (L28);
  - the central finding that domain-free methodology sits in a product path (L42–46);
  - BRM-1, under which extraction is "by REFERENCE, never by COPY" (L83–89);
  - a four-layer runtime model: policy → capability mapping → adapter → configuration (L104–112);
  - "Capability Mapping is NOT an adapter … cannot LEAK UPWARD" (L118);
  - stable ids, "Paths change; ids never do" (L124);
  - R-42: the platform/product line is drawn by runtime moment and AIP lineage (L125);
  - a 16-row migration map (L132–149);
  - Stages 0–5, each with an entry condition (L157–164);
  - a four-arrow loop: 1 evidenced, 2 speculative, 1 empty (L199–206);
  - the scoping "sufficient FOR THE PRESENT MATURITY LEVEL" (L13).
- **Internal contradictions [I]:**
  - **The 9/7 count does not match its own table.** "Nine of sixteen need NO MOVE … seven blocked" (L151, L235–236). The table gives 7 no-move rows (M-1, 2, 3, 4, 8, 11, 12), 1 ready move (M-10) and 8 blocked (M-5, 6, 7, 9, 13, 14, 15, 16). M-10 is counted as "already in permanent home" and also called "extractable today". Both cannot hold.
  - **Not every blocker is a governance gate.** L151 says "every blocker is a recorded governance gate". M-13 (a second runtime), M-14 (AST-010 producing evidence) and M-15 (the artifact does not exist) are evidence or existence triggers.
  - **Stage 1 is both open and blocked.** Its entry condition is "nothing" (L160), yet OQ-K2 "Blocks … Stage 1" (L177) and §10 requires "OQ-K1 + OQ-K2 first" (L217).
  - **"Four triggers" leaves out the acts.** The verdict says "it needs four triggers to fire" (L238–240), all evidence events. Stages 2 and 4 also need governance acts (R-37 lifted, charter approved).
  - **The extraction rule assumes an open answer.** "May be extracted by REFERENCE" (L89) is asserted, but OQ-C1 (L182) asks whether BRM-1 permits reference extraction at all. Separately, a "move" (M-6) is neither a copy nor a reference, and is left undefined.
  - **"deny" is classed twice.** It appears as tool-neutral (L108) and as Claude-specific (L118).
  - **The adapter sits in two layers.** "settings.json is an ADAPTER" (L116, also M-12) contradicts the diagram, which places it in the configuration layer.
- **Also [I]:** the vision is "ACCEPTED AS DESTINATION, not evaluated" (L8), and "four strategic products" are never listed.

**S0032, Vision/Mission Clarification**
- **Concepts:**
  - "artifact absence ≠ concept absence" (L15);
  - INTENDED proposed, then withdrawn as an overloaded dimension (L25);
  - intent is derived as f(governance-status, owner) (L27);
  - the dimension test: new dimension, overloaded dimension, or a value (L25);
  - a mission is "ENACTED in machinery" (L29), evidenced by 8 artifacts (L77–86) and stated at L89;
  - constraint vs mission: "a mission does not ship with a metric … a CONTROL does" (L70);
  - vision = "a statement that carries its own falsifier **and awaits market validation**" (L59);
  - PublicDigit's roles: product, beneficiary, binding constraint, first adopter (INFERRED), laboratory (HYPOTHESIZED) (L114–120);
  - "laboratory" vs Product Primacy as opposite directions of service, "true across horizons only if the horizon is stated" (L130–132);
  - five layers, each with an owner (L136–142);
  - conceptual order ≠ chronological order (L167);
  - the recursive property (L204–213);
  - three corrections, including the n=10 refutation and the claim that every check is advisory (L194–198).
- **Contradictions [I]:**
  - **"Vacant" survives REV 2.** REV 2 replaces "vacant" with "implicit but evidenced" (L11), yet "vacant" remains at L167, L190 and L233.
  - **The header is stale.** It still announces "+ INTENDED (new — see below)" (L10), which L25 withdraws.
  - **An inference is graded OBSERVED.** The mission is graded OBSERVED (L139, L157) although it is inferred from artifacts. The document's own rule is "no hypothesis is elevated to fact" (L10).
  - **Ownership is assigned but marked unexercised.** The mission's owner is graded OBSERVED as "the sponsor", while the same row says no owner has ratified it.
- **Weak [I]:**
  - **The 8 artifacts are not independent.** ES-005 appears twice and ES-006 twice. The 4 artifacts cited for the recursive property all come from one Round38/39 programme.
  - **The L79 argument does not hold.** "A platform existing solely for one product would never need this test" is false: a single-product platform still needs a litmus that separates platform concerns from product concerns.
  - **The "no enforcement" claim is too broad.** "No capability or check … enforces — every one is advisory" (L198) is a universal claim given without a census.
  - **The Linux/PostgreSQL analogy (L23) carries no evidential weight.**

**S0033, Strategic Boundary Consolidation**
- **Concepts:**
  - Round47-OP's nine "software-boundary evidence criteria", introduced with "promoted … **only if** supported by one or more" and "the ONLY admissible justifications" (L29);
  - "semantic truth does NOT imply software structure" (L31);
  - SD-1 as coupling to a certified release (L40);
  - MVK: the method validated, the boundary invalidated (L47–54);
  - tested vs untested responsibilities R-A, R-B, R-C (L58–71);
  - six candidates classified (L79–131);
  - product ownership ⟂ architectural responsibility (L96–101);
  - three levels: repository → product → subsystem, and do not promote a subsystem to a root (L113–116);
  - two extraction blockers, case-law dilution and coupling at the boundary, neither detectable by vocabulary (L156–167);
  - a context map with 5 arrows, 1 evidenced (L171–209);
  - one promotion event per candidate (L212–224).
- **Contradictions [I]:**
  - **Governance is both a subdomain and not a context.** It is "ALREADY EXISTS — a Supporting Subdomain" (L82, L127), yet also an "INTERNAL SUBSYSTEM … NOT contexts" (L111, L176).
  - **KnowledgeOS is classed "PRODUCT"** (L107), which S0032 (correction 1) and S0035 say contradicts AIP-14.
  - **It draws a context map** (§5) under the standing STOP that S0035 obeys (S0035 O-13, I-6).
- **Weak [I]:** the case-law figures "~90%" and "~95%" are not measured. "Validated in both directions" rests on n=1 per direction. "Team autonomy" is marked as satisfied without any test.

**S0034, Engineering Knowledge Domain Model**
- **Concepts:**
  - D-1 to D-7 tested against the nine criteria, graded with the R16 Strong/Medium/Weak scale (never defined);
  - D-2 Method as separate from Governance: "two constitutions · two ADR series …" (L75);
  - D-4 Workflow rejected;
  - D-5 a knowledge kind, because H-CAT-1 stands;
  - D-6 split into Protocol and Records, "the boundary runs THROUGH the concept" (L111–121);
  - D-7 a context TYPE (L123–131);
  - the P1 layers method/binding/evidence (§2);
  - the relation graph, with D-1 "authorizes" D-2 HYPOTHESIZED, because sponsor adoption came via ADR-M-012 (L174);
  - collisions across 12 terms, including Baseline ×5 (L199–218);
  - folders are projections, so ownership-first finds D-6b inverted (L222–238);
  - "void to the extent of the conflict" (L70).
- **Later annotations override the body:**
  - PD-3 EKP is "missing" and was a "category error" (L12–18);
  - falsification results: D-2 Medium, D-3 WEAK (no mapping artifact, near-identity map), D-7 WEAK, "restate as an observation of one instance" (L20–33).
- **Contradictions [I]:**
  - **The strongest domain is named three ways.** EKP "6 of 9, tying D-1" (L16), but D-1 has "Five criteria" (L59), and D-2 is "the most strongly evidenced" (L74).
  - **It applies software-boundary criteria to bodies of knowledge and documents.** "Transactional consistency = ES-001..006 change as a set" misuses the DDD term.
  - **It counts artifacts to establish domains.** It does this in a document that rejects classification by artifact kind.

**S0035, Strategic Domain Discovery**
- **Concepts:**
  - Observations O-1 to O-24 vs Interpretations I-1 to I-12 with confidence;
  - the litmus (A) "would this responsibility exist if PublicDigit disappeared", declared ≡ ES-005.3 (B) (I-2);
  - "brainstorming premise contradicts DA ruling" (L24–32);
  - the fifth occurrence of "proposing before searching" (L34–44);
  - PKS is "A domain … containing bounded contexts", citing an AD-1 ruling (L144);
  - Core/Supporting with a conditional inversion (O-3);
  - the context map withheld under the STOP (§7);
  - "MISSING: nothing at the strategic level" (L151).
- **Weak or inconsistent [I]:**
  - "Six … answered; seventh under STOP; eighth …" (L20) does not match a §6 table in which all 8 are SUPPORTED;
  - O-21 says "zero" despite one grep hit;
  - recommendations run 7, 9, 8;
  - "No new context" contradicts S0034, written the same day.

---

## Part B: the eight claims, tested

### 1. Runtime adapter (CON-0084) and "falsified as realized"
- **Fit (Q1):** it is placed correctly in §1.5.
  - **The definition picks one of two corpus senses.** It defines the adapter as layer 3. S0031 L116 and M-12, which the Professor quotes, say the configuration file *is* the adapter, and S0035 O-6 calls `.claude/` the "runtime mount" of the adapter. So the corpus uses "adapter" for layer 3, for layer 4, and for the mount. The definition takes one of these without recording the equivocation. Quoting L116 as "the configuration file is not the governance" hides the fact that L116 also says the file is an adapter.
  - **"Falsified" contradicts the book's own §1.11.** The falsification report (S0011) rated the identity-map finding "untestable at n=1" and recommended **DEFER**, not rejection (Ch. 1 RC-23a).
  - **The map and the brief overstate the source.** Both say "the mapping is an identity"; S0034 L28 says "**nearly** the identity".
- **Logic (Q2):**
  - "No mapping artifact exists" supports "not instantiated as a separate artifact". This agrees with S0031 L146 ("inside the Runtime Adapter Record").
  - A near-identity mapping when n=1 is what you would *expect* of an abstraction drawn from a single tool. It shows the layer is redundant today; it does not refute the layer.
  - S0031's own L108 vs L118 ("deny" listed as both tool-neutral and tool-specific) is stronger, internal evidence for the identity reading. The book does not use it.
- **Statistics (Q3):** n=1 runtime, so nothing can be estimated. **Computation (Q4):** n/a, because there is no artifact to execute.
- **Clarity (Q5):** replace "falsified" with "not instantiated; redundancy at n=1; refutation deferred".
- **Verdict: CON-0084 KEEP, not VALIDATE.** It is usable as a definition once the layer-3 vs layer-4 equivocation is recorded.

### 2. Governed identity must be location-free (no CON id)
- **Partly agree.** The Ded is slightly wrong. A path-encoded id does not "change meaning" when the item moves. It either goes stale, misdescribing the location, or has to be changed, which breaks every reference to it.
- **The real condition is stability under every permitted move**, not location-freedom. Location-bearing ids are fine if the location is immutable or redirected, as with persistent URIs or content addresses. Location-free ids are sufficient, not necessary.
- **The source's claim is unverified.** "ADRs and reviews reference ids, not paths" (L124) is asserted, and all five sources (and the book) cite by path. "The single most important extraction-safety property" is a superlative with no comparison behind it.
- **Q2–Q4:** n/a, as this is a definitional requirement.
- **Verdict: KEEP, reworded as "stable under any permitted relocation".**

### 3. Words as identifiers; collision; qualification as the remedy
- **The Ded overreaches.** "Every row is a breach of the requirement that a governed name identify one thing" conflicts with the book's own §1.9 (L489, following RQ-002): a term's meaning is fixed *inside one context*. In DDD, polysemy across explicitly scoped contexts is legitimate. A collision is unqualified use within one namespace, or use across namespaces with no context marker.
- **The term identifies a meaning only together with its context.** So the claim should read: "a term is an identifier only relative to a context; the collision is the missing qualifier."
- **Two different phenomena are merged:**
  - polysemy of a term, where qualification is the remedy;
  - two closed value sets with overlapping members (the "Verdict" row; Ch. 2 §2.4, S0025), which is a conflict needing a *scoping* rule. S0034 says so itself: "one scoping rule needed".
- **The reason for qualification is governance, not theory.** S0034 R-2 prefers qualification because "renaming is forbidden". The book presents qualification as a theoretical remedy.
- **The table is not a measurement.** "Measured, not asserted" (S0034 L201) is only an enumeration of senses. There is no search method and no frequency count, so "the most severe … in the repository" is unsupported.
- **Q2–Q4:** n/a. **Clarity (Q5):** mark this Int, not Ded.
- **Verdict: KEEP, with the context-relative correction.**

### 4. "Void to the extent of the conflict" (CON-0040)
- **Fit (Q1):** it connects well to §2.3 and to ground 1 of §2.5.
- **The step to the region is Int, not Ded.** "Extent" is mapped onto the book's own region definition, which is an interpretation. "Extent of the conflict" also has a known wider reading: in constitutional law, "to the extent of the inconsistency" covers indirect inconsistency ("covering the field"), where voidness reaches beyond the region of direct clash.
- **Computation (Q4):**
  - **Some lower artifacts have no region.** The list includes "a baseline", which is a frozen artifact set, not a rule. §2.3 defines no region for it.
  - **Principles often lack rule form.** Constitutional principles such as MC-01 to MC-08 usually have no applicability/effect structure, so "computable wherever the region is" will rarely apply.
- **One constitution is generalized to all.** S0034 L206 says "Constitution" carries five or more senses. The quoted rule comes from one of them (Round39-MC, D-2's own), and its scope is the artifacts *under that constitution* ("sits above the Specification, ADRs, Validator, and Baseline"). Validating a general "constitutional override" (I-10, traced to Round39-MC *and* Round47-00) from this one text over-generalizes. It is also second-hand, which the Professor admits.
- **S0034 misuses the clause.** It cites it as evidence of "transactional consistency", which is not what DDD means by that term.
- **Verdict: CON-0040 KEEP, downgraded from VALIDATE.** Its meaning holds for Round39-MC and its own artifact scope. The region reading is Int, and "timing" and "who declares" remain open.

### 5. The enacted mission
- **(a) Abductive, and not separating mission from vision: agree, but incomplete.**
  - A third rival explanation is missing: S0019's own "why vs what". On that reading portability is a *design quality* under Product Primacy ("build portable things — but only when doing so advances this product"), which accounts for all 8 artifacts with no mission of reuse.
  - The 8 artifacts are not independent (see Part A), so the abduction rests on about 5 distinct sources, not 8.
  - S0032 grades the result **OBSERVED**, against its own evidence rule. This is the clearest defect, and the book omits it.
- **(b) "REV 2 not carried through": verified** at L167, L190 and L233. The stale INTENDED header (L10) is a further, unmentioned residue.
- **(c) Direction of service needs a horizon, so F1 is "partly dissolved": refuted for the mission.**
  - The Professor assigns the product-neutral statement to "the enacted mission or the vision (later)".
  - S0032 puts the enacted mission in the **present**: "already behaving according to it" (L29), "eight artifacts already enact" (L228).
  - So the horizon split dissolves vision (later) against constraint (now). It does not dissolve enacted mission (now) against Product Primacy (now).
  - F1 therefore stays open for the mission. Only the vision part dissolves. The map's F1 entry should be corrected.
  - Separately, 06.03 C6 equates "horizon" (time) with "reference product". These are two different indices, and both may be needed.
- **(d) The falsifier test conflicts across S0019 and S0032: INVALID as stated.**
  - S0032 L59 is a *conjunctive sufficient* condition: falsifier **and** "awaits market validation" ⇒ vision. It does not say that carrying a falsifier marks a vision, and it does not say that only visions have falsifiers.
  - The "further feature" the Professor says "neither source states" (a market test) is in the very sentence he quotes.
  - Logic (Q2): from "missions must have falsifiers" and "falsifier ∧ market-awaiting ⇒ vision", no contradiction follows.
  - What remains is a weaker *tension*: L54 does present the falsification clause as the vision's "grammatical form", and gives no form for the mission.
- **Q3:** the "8 independent artifacts" count fails the book's own independence rule (Ch. 4). **Q4:** n/a.
- **Clarity (Q5):** split "abductive" (holds) from "falsifier contradiction" (drop it, or restate it as a tension).

### 6. Admitting a bounded context (CON-0085 INVALID)
- **The core logic is wrong (Q2).** The source reads: a seam is "promoted to a bounded context **only if** supported by one or more of" the criteria, plus "the ONLY admissible justifications" and "No other justification is sufficient".
  - That is A(c) ⇒ (k₁ ∨ … ∨ k₉): the disjunction is a **necessary** condition. "No other justification is sufficient" rules out other reasons. It does not make any one criterion sufficient.
  - The Professor's "A(c) = k₁ ∨ … ∨ k₉: any one criterion suffices" turns a necessary condition into a sufficient one.
  - So rejecting Bootstrap (one criterion, n=1) is **consistent** with the written rule. There is no gap between the rule as written and the rule as applied.
- **The actual finding is different but survives.** The rule gives **no sufficiency condition** (no threshold on the number of criteria or the evidence per criterion). Admission is therefore underdetermined and not reproducible. The consequence stands; the reason is different.
- **One example is misread.** "Only Engineering Governance … is accepted" is wrong: S0033 classes it as "ALREADY EXISTS", not newly admitted by the test.
- **Better evidence for non-reproducibility exists and is missing.** The same nine criteria, applied the same day, give opposite verdicts on the same objects:
  - Runtime: S0033 calls it a subsystem plus ACL, "NOT a domain"; S0034 makes D-3 a bounded context (Generic Subdomain);
  - Operational Learning vs D-6a Evidence Protocol: S0033 says "NOT a new domain … 0 traversals"; S0034 says D-6a is "a bounded context that has never executed";
  - Governance: S0033 calls it an internal subsystem, "NOT contexts" (L111, L176); S0034 makes D-1 a bounded context.
- **C2 (criteria that cannot vary) is sound.** S0033 L82 even marks "team autonomy" as satisfied for Governance. Also missing: the nine criteria are explicitly **software**-boundary criteria (L29), and S0034 applies them to bodies of knowledge and document sets.
- **Q3:** n=1 or n=0 per candidate, so no rates exist. **Q4:** not decidable as a procedure, since no sufficiency rule is given.
- **Verdict: CON-0085 KEEP, reversing INVALID.** It is valid as a set of *admissible justifications*, a necessary condition, and incomplete as a decision rule. Record the missing sufficiency condition as an Open item.

### 7. Context type vs instance (CON-0086); a boundary through a concept (CON-0087)
**CON-0086**
- **The source's own override is ignored.** S0034's later annotation rates D-7 **WEAK**, "inferred from n=1", and requires "D-7 restated as 'an observation of one instance'" (L31–33). 06.03 C3 reports the Method and Runtime downgrades but leaves out the D-7 downgrade, which is the one that bears on CON-0086.
- **The counterexample check is circular.** It refutes a shared-space counterexample by citing the source's own rule ("one per product, never reusable").
- **Nesting complicates the instance key.** S0012's nesting ("a deployment IS a Knowledge Space") means instances may contain instances, so the "instance key" may need to be hierarchical.
- **It may be redundant.** If a PKS = methods × bindings + evidence (Ch. 1 §1.11), the "type" may simply be the platform's method set plus its schema, which makes CON-0086 derivative of CON-0072.
- **The readings of PKS number five, not two.** 06.03 C7 has two. The sources give:
  1. a domain containing contexts (S0035, which claims an **AD-1 ruling**, so it has higher standing than S0034's candidate);
  2. a context type (S0034 body);
  3. a "GENERATED PRODUCT ARTIFACT — an instance" (S0033 L108);
  4. "ONE, authored — not generated" (S0032 L160);
  5. a projection, "FALSIFIED" (S0034 L31).
- **Verdict: CON-0086 KEEP, not VALIDATE.**

**CON-0087**
- **The distinction is sound** and is supported by three sources (S0034 §1; S0035 §5; S0031 M-14). They come from one programme on one day, so they count as one line of evidence.
- **"Canon states it" (L118) overreaches.** O-12 assigns ownership of the *records* only. The protocol-to-platform half is the author's own step.
- **Classing the protocol as a context is disputed.** D-6a as a "bounded context" is contested by S0033 (see 6); that is not part of the distinction itself.
- **The "derived patterns, a third kind" addition is good.**
- **Verdict: CON-0087 VALIDATE** (as a distinction).

### 8. Two reuse tests (CON-0088); core/supporting relative (CON-0089); PKS domain vs type
**CON-0088**
- **Non-equivalence holds, and (B) ⇒ (A) is acceptable** given a mapping from documents to responsibilities.
- **"The gap is exactly the binding" is false.** ES-005.3 sends a document project-side if it "needs project context **or evidence**" (S0035 O-7). Under the book's own lattice (CON-0075), (B) fails if b=0 **or** e=0, so diluted case law also separates the tests. A third separator is the research clause (OQ-K1), which sends research project-side whatever its content. The gap is binding ∪ case law ∪ the research clause.
- **(A) is counterfactual, (B) is operational.** That asymmetry in testability belongs in the claim and is missing.
- **Verdict: CON-0088 KEEP, with a required repair** to how the gap is described.

**CON-0089**
- **Standard DDD:** a subdomain's classification is relative to a business strategy.
- **It rests on a conditional.** O-3's "would be" makes the support a counterfactual.
- **Verdict: CON-0089 VALIDATE.** Drop the identification with the "horizon" (see 5c).

**PKS domain vs type:** Open is correct, but the question has 5 candidate readings, not 2, and they differ in standing (see 7).

### 06.01 C7: the "counter-practice" in S0031
- **Partly refuted.** S0031 scopes its headline only.
- **Its closings are unscoped absolutes:**
  - "KnowledgeOS does not need to be designed";
  - "the last [document] that should be written" (L240–241).
- **The companion documents do the same:**
  - S0033: "The strategic model is stable. Stop consolidating it.";
  - S0035: "MISSING: nothing at the strategic level".
- **The S0031 cited as the example also carries the arithmetic errors listed in Part A.**

---

## Part C: coverage. What the sources contain that is missing from the Professor's work

Ordered by weight. I judge items 1–6 necessary.

1. **The same-day cross-source contradictions on boundary verdicts** (S0033 vs S0034: Runtime, Operational Learning/D-6a, Governance), plus S0033's internal contradiction (Governance as a Supporting Subdomain and also "NOT a context"). This is the direct evidence that admission is not reproducible.
2. **S0035 and S0033 say "no new strategic context"; S0034 finds four platform contexts plus PD-3.** That is a three-document disagreement about the model's central result.
3. **The nine criteria are software-boundary criteria.** S0034 applies them to bodies of knowledge and document sets, including a misuse of "transactional consistency". This is the inverse of S0033's own guard ("semantic truth ≠ software structure").
4. **S0033 draws a context map under the standing STOP** that S0035 obeys (O-13 and I-6).
5. **KnowledgeOS is classified three ways.** S0033 says "PRODUCT"; S0032 (correction 1) and S0035 say Supporting Subdomain / potential product; S0031 says "vision accepted as destination, not evaluated".
6. **The D-7 WEAK annotation, and the five PKS readings with their different standing** (see 7).
7. **S0031's internal arithmetic and logic:** 9/7 vs 7+1+8; M-10's double status; non-gate blockers called gates; "four triggers" leaving out the acts; Stage 1 both open and blocked; "by reference" asserted while OQ-C1 is open; move vs copy vs reference undefined.
8. **Trigger vs gate.** An evidence event and a governance act are distinct entry conditions; this is the book's "two engines" applied to staging. S0031 merges them.
9. **The PKS → Product arrow** is "SPECULATIVE" in S0031 and "PARTIALLY EVIDENCED" in S0033.
10. **S0031 L108 vs L118** ("deny" in both vocabularies) and **L116 vs the diagram** (the adapter's layer).
11. **S0034's own counts:** EKP "6 of 9, tying D-1" vs D-1 "five criteria" vs D-2 "most strongly evidenced"; the undefined R16 Strong/Medium/Weak scale; individual ownership read as a domain signal (against S0001's "ownership insufficient").
12. **S0032's evidence-rule violations:** the mission graded OBSERVED; "eight independent" artifacts that are not independent; the L79 argument; the rival explanation from S0019's "why vs what".
13. **S0032's dimension rule:** INTENDED withdrawn as an overloaded dimension; intent as a derived attribute f(governance-status, owner); the stale L10 header.
14. **S0032 L198 contradicts the book's own census.** Its "no capability or check … enforces" clashes with the enforcement census in Ch. 1 §1.9 (2 blocking, 1 compiled guard).
15. **A collision in the book's own core vocabulary.** "The product BINDS the platform" (Reference Model §8, a constraint relation) vs "binding" in CON-0072 (an input contract). It is absent from the §1.10 collision table.
16. **The occurrence counts of the rediscovery / absence-as-absence pattern do not agree:** S0032 says the third time; S0035 the fifth; S0032 correction 2 says n=10; S0002 says eleven. No class was fixed in advance (CON-0070 applies).
17. **S0031 R-42's platform-membership criterion** (an AI runtime moment plus AIP lineage).
18. **S0033's three-level rule** (repository → product → subsystem), stated beyond the orthogonality the book records; and S0034's ownership-led folder assessment, which is in tension with it.
19. **S0032's five-layer ownership scheme vs S0019's three authority systems.** Two schemes, not reconciled.
20. **Minor:** S0033's unmeasured "~90/95%"; S0035's question-count mismatch, O-21 "zero" with one hit, and recommendation order 7, 9, 8.

---

## Part D: verdicts

| Concept | Professor | Mine | Reason, in short |
|---|---|---|---|
| CON-0040 | ✓ | **KEEP** | the region reading is Int; one constitution of 5+; second-hand; lower artifacts that are not rules |
| CON-0084 | ✓ | **KEEP** | adapter = layer 3 or the configuration file (S0031 equivocates); "falsified" should be "not instantiated / deferred" |
| CON-0085 | ✗ | **KEEP** | "only if" is a necessary condition, so there is no written/applied conflict; the sufficiency condition is missing |
| CON-0086 | ✓ | **KEEP** | the source's own D-7 WEAK override; five competing readings; circular counterexample check |
| CON-0087 | ✓ | **VALIDATE** | sound distinction; "canon states it" overreaches |
| CON-0088 | ✓ | **KEEP** | non-equivalence holds; "gap = exactly the binding" is false (case law and the research clause also separate them) |
| CON-0089 | ✓ | **VALIDATE** | relational reading is standard; drop the equation with "horizon" |
| Item 2 (identity) | — | KEEP | restate as "stable under permitted relocation" |
| Item 3 (words as ids) | — | KEEP | collision is relative to a context; polysemy ≠ closed-set conflict; Int, not Ded |
| Item 5d (falsifier conflict) | Ded | **INVALID as stated** | S0032 L59 is conjunctive and contains the market feature |
| Item 5c (F1 partly dissolved) | Int | **Refuted for the mission** | S0032 puts the enacted mission in the present |

No files were written. The only path that matters here is the frozen file the coordinator named, which I did not write or open: `/home/nab-raj.roshyara@dg-nexolution.de/roshyara/personal/nrna1/docs/knowledgeos/knowlegeos_manual_theory_20261018/06-theory/manuscript/review/C6-S0031-S0035/STEP1-BLIND-READING.md`. Its provenance should be checked before it is treated as my blind reading.
