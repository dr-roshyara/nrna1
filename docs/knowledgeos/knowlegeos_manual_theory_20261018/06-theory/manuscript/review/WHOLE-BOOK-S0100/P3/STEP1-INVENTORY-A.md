*Whole-book review S0100, part P3, reviewer A (opus). Step 1, blind inventory, saved by the Professor from the hand-back, unedited, 2026-10-19T19:55:12Z.*

# P3 (Ch. 4 and Ch. 5): step 1 blind inventory, written before opening the book

I wrote this without opening `R/BOOK-UNDER-REVIEW/`. From the brief I read only the table "Your part's allowed references" (BRIEF.md L37–77). I found the table with a heading-only grep first (see the file list). Statements marked **[interp]** are my reading; everything else is in the source at the line given.

---

## S2377: "KnowledgeOS Theory v1.0, Formal Theory of Epistemic Gaps" (read in full, L1–2762)

**What kind of document it is.** A chat-assistant answer kept in `brainstorming/`. It opens "Yes. I have first analysed the attached derivation" (L1) and speaks as a "Senior-statistician" (L2654). It keeps citing an "attached derivation" (L9, L27, L37, L45, L65, L536, L738, L832, L952, L1655, L2752). That derivation is not in this file. So every "the derivation established X" is **indirect**: the source asserts it, and from this file alone it cannot be checked. It has one commit, 6f38df520, dated 2026-09-06.

**Main claims and concepts**
- **The gap is not a difference.** The gap should not be defined as I_t − K_t, because K and I are "heterogeneous semantic objects and may not live in a vector space" (L5). The fundamental gap is "a set of unsatisfied epistemic requirements". Numbers come later, only when weights, probabilities and similar structure are supplied (L7). The I−K worked example is at L113–169: "Numerical difference ≠ Epistemic Gap" (L161), valid only once each coordinate's meaning and scale are fixed (L169).
- **Three objects.** K_t is the current state, I_t the required state, and Δ_t = Gap(K_t, I_t; EC_t), where EC_t is the epistemic contract (L71–109).
- **Requirements.** A requirement is r = (id, type, scope, content, standard, priority, validity) (L219). A requirement "is not necessarily a factual proposition" (L209).
- **The ideal state.** "I_t = R_t semantically" (L243), with I_t = I(Q, C, S, EC) (L253). The ideal state is "knowledge sufficient for the declared purpose", not all knowledge (L267–278).
- **Satisfaction is yes/no.** Sat(K_t, r) ∈ {0,1} (L297).
- **DEF-G1.** Δ_t = {r ∈ R_t : Sat = 0} (L323–336). The claim is that this needs no subtraction, geometry, probability, vectors or finite dimension (L340–346).
- **DEF-G2 / THM-G1 (Zero).** Zero holds when Δ_t = ∅, which holds when K_t ⊨ EC_t (L356–441).
- **Gap types.** The text announces "seven primary gap classes" (L461) but then lists ten (G1–G10, L465–832): coverage, value, uncertainty, warrant, contradiction, model, observability, staleness, identity, representation. They are assembled as a "typed semantic gap vector, not a numerical vector" (L836–862).
- **Distinctions.** Each is stated as a slogan:
  - unknown value ≠ unknown dimension (L532)
  - having an answer ≠ justified knowledge (L612)
  - do not silently pick one side of a conflict (L660)
  - unobservable ≠ False (L724–735)
  - superseded ≠ False (L774)
  - identity ≠ state (L806)
  - K ≠ Δ ≠ I (L908–952)
  - fewer facts does not mean worse knowledge (L1027–1069)
- **Order and improvement.** K_{t+1} ⪰ K_t when Δ_{t+1} ⊆ Δ_t "subject to preservation of previously valid requirements" (L969–986). THM-G2 says Δ shrinking means improvement (L992–1022), called "finally … a mathematically defensible notion".
- **Direction.** Δ = (Δ⁺, Δ⁻), missing vs. violating (L1101–1126). Gap = Deficit ∪ Conflict ∪ Insufficiency (L1166–1177).
- **Numbers come after the set.** The derived measures are:
  - count and weighted count (L1206–1227)
  - coverage (L1236–1263)
  - uncertainty max(0, τ−p) (L1287)
  - evidence (L1327)
  - time (L1355)
  - model (L1373)
  - observability (L1400)
  - a master functional G = Σ w_r d_r (L1433–1438)
  
  THM-G3 says G = 0 exactly when Δ = ∅, given w > 0 and d = 0 exactly when the requirement is satisfied (L1465–1555). The hierarchy is semantic → typed → numerical, "Not the reverse" (L1596–1605).
- **Probability.** Probability feeds one gap component; it does not define knowledge (L1611–1655).
- **Algebra.** Gap algebra (R, Sat, Type, ⊕, ⪯) (L1665). Composition is set union with "identity-based normalization" (L1704–1716).
- **Contract invariance.** Gaps can only be compared under compatible contracts. THM-G4 requires an explicit mapping M between requirement sets, against "moving the goalposts" (L1754–1831).
- **The ideal state has provenance:** Prov(I_t) = (Knower, Goal, Inquiry, Context, Contract, Time, Authority) (L1842–1866). The gap is relative to purpose (L1870–1938).
- **Lord and Sārathi.** Lord(Δ) → Proposal: "Gap → Proposal, not Lord → Gap" (L2008–2044). Sārathi(Δ, Proposal, Authority) → Decision (L2048–2088).
- **THM-G5 (control).** A transition with Δ_{t+1} ⊄ Δ_t "cannot be called a pure epistemic improvement" (L2131–2165).
- **§54.** The gap can grow while knowledge improves ("Unknown → Known Gap") (L2169–2222). §55–56 therefore add two measures, adequacy and gap observability, and a progress vector (L2226–2279).
- **THM-G6.** Every unsatisfied requirement belongs to at least one class. The classes may overlap: "we should not falsely claim … mutually exclusive" (L2287–2325).
- **Lattice.** P(R) ordered by ⊆ is a lattice with Zero as its bottom element (L2329–2437). The source calls this "an important breakthrough" (L2441).
- **What it would freeze.** A "v1.0 candidate theorem set" (L2543–2629, L2754).
- **What it leaves open.** Its own list of 10 items (L2633–2650), among them the type of K_t, the meaning of Sat, the atomic unit, whether the classes are complete, and whether a canonical scalar exists. The next problem it names is "How should Sat(K_t, r) be defined for each class?" (L2758).

**Problems inside the source** (each is checkable against the lines given)
1. **Seven vs. ten classes.** It says seven (L461) and lists ten (L465–854).
2. **Sat cannot express "cannot be evaluated".** Sat is yes/no (L297), yet G7 is "cannot currently be evaluated" and "must not automatically become False" (L706–735). With yes/no Sat, an unevaluable requirement is forced to Sat = 0. That puts "not shown" and "cannot be checked" in the same bucket. The typing (L455) is the only escape, and Sat itself does not carry it.
3. **§54 against THM-G4/THM-G5.** In §54, Δ_t = ∅ "because the requirement was not represented" (L2181). Then a newly visible requirement enters. That is a change in R, which is a contract change, and THM-G4 says it is not comparable without a mapping (L1808–1829). THM-G5 says it is not an improvement (L2151–2157). Yet §54 calls it "epistemic progress" (L2222). **[interp]** The source does not reconcile these. Either R is fixed by the contract and §54 is impossible as described, or R grows and the ⊆ order does not apply.
4. **Adequacy is both yes/no and graded.** §55 compares adequacy as a graded quantity (A_{t+1} < A_t, L2247). The frozen definition makes it yes/no (L2577–2580).
5. **The improvement order ignores type changes.** It is defined on bare ⊆ (L1726–1729, L2343–2347) and ignores types. A requirement that moves from "uncertainty" to "contradiction", or from 0.65 to 0.94 below a 0.95 threshold, leaves Δ unchanged. **[interp]** So ⪰ is coarse and cannot register partial progress, even though §32–36 define graded deficits.
6. **"Preservation of previously valid requirements" (L982) is never defined.**
7. **The headline theorems hold by definition.** THM-G1 (L393–441) and THM-G3 (L1465–1553) follow directly from the definitions. The lattice result (L2329–2437) is the standard power-set Boolean lattice. "Breakthrough" (L2441) and "profound" (L2208) overstate this. **[interp]** These are correct but not new. Their value is organising, not proving.
8. **The master sum brings back the aggregation it rejected.** G = Σ w_r d_r (L1436) adds deficits from different classes on separate scales. That is the heterogeneous aggregation the source rejected at L5–9. Its only protection is "explicitly chosen loss/weighting" (L1591). It assumes the sum is finite or converges (unstated; L346 explicitly allows infinite dimension).
9. **Sign error.** age_r = t − now (L1346) has the wrong sign; it should be now − t.
10. **Lord and Sārathi are imported, not defined here** (L2010, L2050). "Much stronger formal foundation" (L2088) rests on the arrows being drawn, not derived.
11. **No governance gap class.** None of the ten classes covers a missing approval or authority, although Authority appears in Prov(I_t) (L1849) and in Sārathi (L2057). Compare S0896 below.

**What a rigorous governance theory should take, reject, or leave open**
- **Take:**
  - the gap as a set of unmet requirements relative to a declared contract, with numbers as derived views only (L1596–1605, L2002)
  - comparison only under compatible contracts, plus a goalpost guard (L1779–1831)
  - the ideal state carries provenance, including Authority (L1842–1866)
  - classes may overlap (L2317–2325)
  - probability as one component, not as knowledge (L1611–1655)
  - unobservable ≠ false, and superseded ≠ false
- **Reject or repair:**
  - yes/no Sat, if unevaluability is meant to be kept (S0896 already rejects yes/no Sat)
  - "seven" vs. ten
  - the §54 / THM-G5 tension
  - the claims that THM-G1, G3 and the lattice are substantive results
  - unconstrained cross-class sums
- **Keep open:** the source's own L2639–2648 list. Add: how R_t is produced (contract derivation), and a governance or authority class.

---

## S0044: "Recommendation Lifecycle Discovery" (read in full, L1–81)

**What kind of document it is.** A living discovery instrument for an EP-02 commission, dated 2026-08-04. Its declared evidence base is "ONE engine run · 10 recommendations issued · ZERO developer decisions · ZERO lifecycle transitions" (L8).

**Main claims and concepts**
- **Run 1 observations** (L14–20): 10 recommendations issued; deduplication held on one re-run; 4 rules stayed silent where they had no grounds ("honest silence"); 2 known false-positive candidates from R5. Every other lifecycle behaviour was NOT OBSERVED (L20).
- **Vocabulary graded by evidence** (L26–31). Three grades:
  - **OBSERVED:** recommendation, issued, open, rule, subject, evidence refs
  - **observed as vocabulary, unexercised as behaviour:** decision (ACCEPTED / IGNORED / DEFERRED), rationale, reason code
  - **HYPOTHESIZED, "not introduced":** viewed, satisfied, expired, superseded, reopened, merged
  
  "intervention" is EMERGING. Rule: terms enter "only when engineering performs them" (L30).
- **Discovery log** (L35–44). Each entry carries frequency, counterexamples, confidence and promotion.
  - **RD-1:** an issued recommendation is immutable and responses are separate records. Confidence HIGH "for the implementation; domain meaning unproven" (L37).
  - **RD-2:** identity = (rule, subject), MEDIUM, re-issuance after the subject changes untested (L38).
  - **RD-3:** honest silence (L39).
  - **RD-4:** a three-layer identity (Definition / Instance / Revision) is HYPOTHESIZED, "think, never implement, until a real question requires it" (L40).
  - **RD-5:** age can be computed from existing timestamps (L41).
  - **RD-6:** an actor provenance gap. The `actor` field stamps the git user, but the AI decided at the user's direction, so who-executed must be told apart from who-authorized. n = 1 (L42).
  - **RD-7:** the first anomaly. A recommendation can be decided twice, and the dashboard count is skewed. Exactly-once vs. last-wins "is a domain decision", so: "do not patch the guard before the semantics are decided" (L43).
- **Run 2** (L46–56):
  - the first decision was observed
  - time-to-decision was 6.5 h, computed from existing fields (L52)
  - the learning event is 3 of 5 complete (no commit, no outcome) (L54)
  - the DDD classification is unchanged; "one decision cannot reclassify" (L55)
  - RD-7 hints that Decision may carry its own invariants (L56)
- **DDD verdict: "NONE YET — insufficient evidence"** (L60–64). The shape "resembles an event-with-a-process-around-it more than an aggregate", but it cannot ground a classification.
- **Impact:** "INSUFFICIENT EVIDENCE". "10 issued is an activity count and counts for nothing" (L70).
- **Protocol** (L75): rerun at the first decision, then every ~10 decisions or on any anomaly. No ML, ranking or confidence scoring unless deterministic v1 "demonstrably fails".

**What it leaves open:** the meaning of re-decision (exactly-once vs. last-wins), revision identity, the actor/authority model, the DDD classification, and impact.

**Problems.** L22 says "Anomalies: none yet", while RD-7 (L43) records the first anomaly. That is an append-order artefact, but L22 is now stale. RD-1's HIGH confidence is "by construction" (L37), so it describes the implementation, not an empirical domain finding.

**What a theory should take:**
- vocabulary admitted only on exercised behaviour (graded)
- counts of activity separated from counts of effect (L70)
- refusal to classify on n = 1
- executor ≠ authoriser in decision provenance (RD-6), which applies directly to AI-mediated governance
- anomalies kept unpatched until the semantics are decided (RD-7)
- a learning event defined by its full chain: recommendation → decision → rationale → commit → outcome (L54)

**Keep open:** whether Recommendation or Decision is a domain concept at all. **[interp]** RD-6 and RD-7 are each n = 1. They are good evidence that the questions exist, not evidence of any particular answer.

---

## S0061: Registration, Phase A v1.1 current-state Architecture Baseline ACCEPTED (2026-08-17) (read in full, L1–57)

**Main claims and concepts**
- **The human act, verbatim:** two messages registered once as one act (L5–12). The acceptance sentence names the baseline "the authoritative reconstructed current-state architecture baseline" (L12).
- **Preconditions were checked before recording** (L14–25): V#1 `ad914714`, V#2 `749e14df`, V#3 `ef52a9e4` (whose evidence was separately accepted), a closed correction chain, the snapshot preserved, no target-architecture leakage, and no implementation implied. The accepted content is pinned to blob `40026b12` (L27).
- **Scope, positive:** a "foundation for Stage 2" (L31).
- **Scope, negative:** it authorises "Stage 2 analysis only". It does not approve bounded contexts, aggregates, implementation architecture, technology choices or migration decisions (L35–43).
- **V-E, accepted knowingly** (L45–47). The machine-state figures are "declarations of a moment that left no trace": the records are gitignored with no history and "can never be retroactively audited".
- **Consequence:** Stage 2 becomes eligible, "may be STARTed according to its own governance gate"; "Nothing is started by this registration" (L49–51).
- **Housekeeping:** the status banner is annotated ACCEPTED; the content is untouched (L53–55).

**Distinctions:** acceptance ≠ start; eligibility ≠ authorisation; current-state reconstruction ≠ target architecture; the registrar (Governance) ≠ the decider (PO/ARB); a status annotation ≠ a content change.

**What it leaves open:** the "faithful reconstruction" rests on V#1–V#3, which this file only cites. The facts it accepts include ones that can never be re-audited (V-E). **[interp]** This is a clean example of authority accepting evidence that cannot be verified, with the weakness on record. It shows that "authoritative" here means *conferred by the act*, not *established by verification*. A theory has to keep those two senses of "authoritative" apart.

**What a theory should take:** an acceptance act modelled as (verbatim act, checked preconditions, pinned content hash, positive scope, negative scope, known limits, consequence that is not a start). It also shows that a single acceptance can carry an enumerated denial list.

**Reject as general:** nothing. It is one registration (n = 1) of one kind of act; how far it generalises stays open.

---

## S0027: "Operational Evidence Register" (read in full, L1–202)

**Main claims and concepts**
- **Register rules** (L5–7): append-only; behavioural observations with provenance, "never conclusions"; the register is "Generated", and the Decision Authority (DA) accepts or rejects, never the register.
- **Entry rule:** each entry records what was observed, which claim it bears on, first-hand provenance, and what it does NOT show (L7).
- **OE-KOS-1, WP-4B planning** (L11–44). Four constraint-obeying behaviours, among them refused WP-4C/4D plans (L24). Implementation and runtime are ❌ (L27–28). It bears on R-6 "guides product engineering": "evidenced, not measured", with "no unguided baseline" (L34). It is NOT the back-edge (L36). MO-1 is recorded but not promoted under ES-006.1 (L40).
- **OE-KOS-2, RED-ratchet slice:** one invariant (K2), a constraint the tests cannot detect, cost recorded before being paid, and an honest RED stop (L55). Commit `51fdbfb8b` (L58). R-6 n = 2 (L61). "Implementation Finding" at n = 1 (L64).
- **OE-KOS-3:** the developer-guide obligation was skipped and the Stop hook was blind to `scripts/**`. Found "by USER AUDIT, not by the platform" (L74). The fix was verified twice. The source calls this the back-edge "formally traversed for the first time — at the INSTRUMENTATION level", formal n = 1 (L80). DG-1's first instance is a failure-mode instance (L80). Lesson: preventive nets fail at scope boundaries (L80, L83).
- **OE-KOS-4, WP-4C-2:** discovery refused to invent a business decision ("a missing business decision, not a missing if-statement"); no RED plan was written (L93). R-6 n = 3 (L99).
- **REV 2** (L109–140):
  - a fixed five-section template: Observation / Provenance / Claims affected (the level-mixing guard) / Interpretation / Promotion, always "left to the DA" (L113–121)
  - two streams: A, evidence, written per event; B, learning candidates, generated periodically, reviewed by the DA, never written directly. B is homed in the existing EPC / Pattern Evidence Register (L127–132)
  - "no governance rule extracted before multiple OE entries exist" (L136)
  - "entries are never created to fill the register" (L140)
- **REV 3** (L144–167): MO-1 is held only as staging and migrates (L148). A recurrence pointer table, "no thresholds are policy today" (L152–158). "The strongest statement never exceeds the evidence" already exists in `.claude/CLAUDE.md` (L162).
- **Annotations** (L171–201):
  - the R-6 scope was checked as activity-level, so n = 3 stands; "Had R-6 been implementation-scoped, the count would have been trimmed to n=1" (L175)
  - MO-2 ownership-before-state, n = 1 (L183)
  - an explanatory-precision heuristic moves 1 → 2 (L193)
  - candidate-volume watch at ~10–15, not policy; count 4 (L199–201)

**Problems.**
- The register claims entries are "never edited" (L5), yet it carries REV 2 and REV 3 rule blocks and annotations. These are appended, not edits, and OE-KOS-1 is said to stand untouched (L123). That holds only if the rule sections count as appendices, not edits.
- R-6's n = 3 counts behaviours where the guidance was obeyed, with no unguided baseline (L34). **[interp]** This is confirming-instance counting. It shows the edge is *consistent with* working, not that guidance *caused* the behaviour.
- The external "ARB-persona review" verdicts (L17, L134, L166) are AI-persona outputs recorded as review. They are not human authority, as L134 itself says ("enacted by nobody here").
- OE-KOS-3's back-edge depends on a user audit. The platform did not detect its own hole.

**What a theory should take:**
- evidence ≠ interpretation ≠ promotion, fixed by template
- the level-mixing guard (what an entry does NOT bear on)
- two streams, with patterns extracted by a separate act
- no promotion from n = 1
- scope checks before counting (L175)
- preventive nets fail at scope boundaries, with the external audit as the detector

**Keep open:** whether any of the n-counts measure effect; how stream B is actually triggered.

---

## Fifth source, my choice: S0896, "Step 25D — Formal Zero Algebra" (`docs/knowledgeos/brainstorming/phase_measure_theory/20260827-183142_step-025d-formal-zero-algebra.md`, 1662 lines)

**Depth:** a skim. I read the headings in full, plus L1–60, 138–290, 335–400, 486–560, 598–680, 981–1060, 1165–1240, 1281–1320 and 1439–1560, and grepped for anchors.

**Why this one:** it is the closest earlier formal treatment of the same object as S2377 (requirement-relative gap / "Zero"), dated 5 days earlier (commit f24edf316, 2026-08-28). It is the natural test of whether S2377's choices are stable across the corpus. It is in the allowed table (BRIEF L74) and on CORPUS-ALLOWED (row S0896, "match").

**Main claims, and where it differs from S2377**
- **Sat is not yes/no.** It defines nine statuses: Satisfied, PartiallySatisfied, Unknown, Insufficient, Conflicted, Stale, Invalid, Prohibited, NotApplicable (L160–172). The argument "Why Boolean logic fails" ends "Sat ≠ Boolean" (L179–206). A separate **Missing** status covers "an expected governed artifact/action", such as an absent approval (L247–287). **This contradicts S2377 L297, where Sat ∈ {0,1}.**
- **"Zero" means a different thing.** Here Zero *is* the structured discrepancy: Zero = R_G \ Satisfied, but "only a shorthand"; the real object is {Requirement, Status, Evidence, Reason, Dependencies} (L335–362). In S2377, Zero is the *condition* Δ = ∅ (L356–362, L2395). **[interp]** This is a real clash in terminology across sources. A theory must choose one meaning and record the other as superseded usage.
- **Zero has a governance dimension.** Zero = (Z_world, Z_knowledge, Z_governance, Z_decision) (L598–632). S2377 has no governance class.
- **Not a scalar, not a metric.** "Why not calculate one Zero number?" (L634–669). It is not a metric, because comparison is directional; it is "a directed epistemic discrepancy operator" (L1165–1218, L1211). This is internally strained: it uses Distance(K, K*) at L528/L537 and EpistemicDistance at L1155 before rejecting the metric framing.
- **Zero is an orchestrator, not an inference engine** (L486–518).
- **Reproducibility / replay** (L981–1019).
- **"Zero should not invent gaps".** It compares only against declared or authorised-derived requirements, "not against arbitrary AI expectations" (L1021–1050). **[interp]** This bears directly on S2377 §54: a newly "visible" requirement must enter through authorised derivation, which is a contract change.
- **Zero ≠ Lord:** detection vs. resolution planning (L1281–1316). This matches S2377 L2036–2043.
- **"Falsification tests" 1–9 all "PASS"** (L1373–1435), Test 9 "PASS conceptually". **[interp]** These are thought experiments with no execution. They are not falsification in an empirical sense. The verdict "25D — PASS" (L1536) is the author marking its own work.
- **Open:** ContractDerivation, i.e. where requirements come from: constitution, policy, ADR, law, human instruction (L1439–1478). RequirementGraph, i.e. dependencies between requirements, to avoid counting one chain as three gaps (L1482–1526). **[interp]** S2377 does not resolve either, although it presents itself as the more rigorous successor.

**What a theory should take:** a multi-valued status (or typed failure) on Sat; "Missing" as a governance status; no gap invention without authorised derivation; contract derivation and requirement dependencies kept as open problems.

**Reject:** the conceptual "PASS" verdicts used as evidence.

---

## Cross-source observations [interp]

1. **Formal vs. empirical evidence.** S2377 and S0896 are AI-generated formal brainstorming with no empirical base. S0044, S0027 and S0061 are operational governance records, mostly at n = 1–3, which say so themselves. Any chapter that lets the formal theory borrow authority from the operational records, or the reverse, needs checking.
2. **Executor vs. authoriser.** S0044 RD-6 (executor ≠ authoriser), S0061 (registrar ≠ decider; acceptance ≠ start) and S0027 (register ≠ DA) all separate the recorder or executor from the authority. S2377 encodes Authority only in Prov(I_t) and Sārathi, not in its gap classes.
3. **Shared method rule.** "Do not promote, classify or patch from one occurrence" recurs in S0044 (L43, L55, L64) and S0027 (L40, L136, L189). It is a stated rule in both, not something either one shows to be correct.
4. **What is settled vs. not.** The two formal sources disagree on whether Sat is yes/no and on what "Zero" names. A theory cannot cite both as settled.

---

## Files opened (all reads)
1. `docs/knowledgeos/knowlegeos_manual_theory_20261018/06-theory/manuscript/review/WHOLE-BOOK-S0100/P3/BRIEF.md`. First a heading-only grep (`grep -n "^#"`), which showed all heading lines, including section names after the table. Then a Read of L37–78 only (the allowed-references table).
2. `docs/knowledgeos/brainstorming/mathematical_ideas_that_can_be_implemented/20260902-082333_knowledgeos-theory-v1-0-formal-theory-of-epistemic-gaps.md` (S2377), L1–2762, full.
3. `docs/knowledgeos/KnowledgeOS_Recommendation_Lifecycle_Discovery.md` (S0044), full.
4. `docs/knowledgeos/reviews/2026-08-17-KOS-ARCH-BASELINE-001-phase-a-acceptance-registration.md` (S0061), full.
5. `docs/knowledgeos/KnowledgeOS_Operational_Evidence_Register.md` (S0027), full.
6. `docs/knowledgeos/brainstorming/phase_measure_theory/20260827-183142_step-025d-formal-zero-algebra.md` (S0896), skim (ranges listed above, plus `wc -l` and heading grep).
7. `docs/knowledgeos/knowledgeos-s-series-theory/corpus-access/CORPUS-ALLOWED.tsv`, only through grep for the IDs S2377, S0044, S0061, S0027, S0026, S0029 and S0896, to check that the paths are allowed.

## Searches and commands run
- `grep -n "^#" .../P3/BRIEF.md`
- `wc -l` on the four step-1 sources
- `grep -n -E "S2377|S0044|S0061|S0027|S0026|S0029" CORPUS-ALLOWED.tsv`
- `grep -n "S0896" CORPUS-ALLOWED.tsv`; `wc -l` and `grep -n "^#"` on S0896
- `sed -n` ranges on S0896 (listed above)
- `grep -n -E "Sat\\\\neq Boolean|PartiallySatisfied|R_G$|setminus|Distance\(|directed epistemic discrepancy|Gap.Detection|ContractDerivation|RequirementGraph|PASS|Prohibited"` on S0896
- `git log --format='%h %ad %s' --date=short -- <file>` for each of the five sources (read-only)

I did not open `R/BOOK-UNDER-REVIEW/`, any other file under `R/`, any working chapter, map, register or outline file, `BASELINE-REVISIONS.md`, any OLD file, any manuscript git history, `L/00-access/`, `L/03-methodology/`, `L/archive/`, `.env*`, `_batch_input*`, or any sealed or hold-out file. I reached no forbidden file. I wrote, moved or changed nothing.
