# PKS Phase II.B — Architecture Fidelity Verification (AFV-1)

| | |
|---|---|
| **Kind** | **Fidelity verification of the Model → Architecture transformation** — the one link in the chain that had no dedicated independent verification. Verifies that AD-1 is a faithful architectural *derivation* of the governed Strategic Model. **Derives no architecture, changes none, introduces no strategic knowledge.** |
| **Authority** | Generated — never authoritative without human review. **Findings are recorded; nothing is corrected, decided, or promoted here.** |
| **Status** | **🏁 PROMOTED (Authority act, 2026-07-30) — the authoritative assurance record of the Model → Architecture transformation.** **AFV-1 is expressly NOT constraint-defining** (ADR §3.1.1): it documents the transformation and binds nothing. |
| **Findings — DISCHARGED (as of 2026-07-30)** | **All five findings are closed. AFV-F1/F2/F3/F5 were applied as one bounded amendment to AD-1 (change items C-02, C-03, C-04, C-05); AFV-F4 was DISPOSED ACCEPT at Authority level (C-01, using M6 §9's impact statement); the recommended bounded re-read was institutionalized as CCP-1's C-18 and recorded PASS 9/9; and AD-1 was PROMOTED on 2026-07-30.** *The verdict below is retained exactly as issued, per forward-only supersession and per the governed rule that a record is amended for currency, never rewritten for outcome.* |
| **Status at issuance (retained as history — TRUE WHEN WRITTEN, superseded 2026-07-30)** | **EXECUTED. Verdict: VERIFIED WITH FINDINGS — 5 findings (1 Governance · 3 Semantic · 1 Editorial). Four require action before promotion, and one of those requires an Authority disposition rather than an editorial fix. STOP.** No AD-1 modification, no redesign, no C4 update, no promotion, no governance rule created, no discovery reopened. |
| **Commission** | Architecture Fidelity Verification Commission AFV-1 (PA, 2026-07-28), issued to close the assurance asymmetry at the highest-interpretation transformation. |
| **Inputs (only these, per commission)** | Strategic Model **M0–M8** · **DAR-1** · **RET-1** · **AD-1**. |
| **Deliberately NOT used** | **C4-1 · C4-2 · KBI-1** — downstream products. The architecture is verified against its **source**, never against its own consequences. *(The commission's framing is cited as the reason this review exists; no downstream artifact was used as verification evidence.)* |
| **Placement** | `docs/implementation/`, beside AD-1. |
| **Disposition History** | 2026-07-28: executed. · 2026-07-28: **conformed to the refined commission** (received mid-execution), which relocates the object of verification: **the target is the *transformation*, not the destination artifact** — *is AD-1 a **lawful transformation** of the Strategic Model?* rather than *is AD-1 correct?* Added: the correctness-versus-fidelity discipline (below) · **§10A Transformation Integrity as the central objective** · the **Transformation** finding class, under which **AFV-F1 is reclassified from Semantic to Transformation** · the four required elements per finding (evidence · rationale · impact · disposition) · a tightening of §13's methodology item per the refined STOP ("record findings only; do not propose methodology evolution"). **No finding was added, removed, or weakened; section numbers are preserved so cross-references remain valid.** |

**Governing principles applied:** *architecture refines strategy · architecture does not reinterpret strategy · architecture does not strengthen strategy · architecture does not resolve strategic uncertainty · **architecture is subordinate: its authority derives entirely from the Strategic Model**.*

**THE RUNNING DISCIPLINE (refined commission — maintained continuously below).** Two questions must not be conflated:

- **Architecture correctness** — *is AD-1 internally consistent?* **Out of Scope for this commission.**
- **Architecture fidelity** — *does AD-1 faithfully preserve the Strategic Model?* **The only question asked here.**

**Derived — why the distinction bites rather than being pedantic:** AD-1 is **internally consistent at every one of the four points where this review finds a fidelity defect.** A correctness audit would have passed all four. The defects are visible only when each architectural statement is read *against its source* rather than against its neighbours — which is exactly why a transformation, not an artifact, is the right object of verification.

**Section mapping to the refined structure** (numbers preserved to keep cross-references valid): Semantic Fidelity → distributed through §§5–8 with the strengthening cases consolidated at §10A.2 · Boundary → §5 · Responsibility → §6 · Dependency → §7 · Principle → §8 · Exception → §9 · **Negative-Space → §9's final four rows** · Epistemic → §10 · **Transformation Integrity → §10A (central)** · Findings → §11 · Assurance → §12 · Promotion → §13.

---

| **Disposition History** | **2026-07-30: PROMOTED by Authority act** — the authoritative assurance record of the Model → Architecture transformation (record: `PKS_Phase_II_Execution_Record.md`). · **2026-07-30: AFV-R1/AFV-R2/AFV-R3 disposed ACCEPT and applied** (Knowledge Contract Review, transformation-fidelity emphasis). **AFV-R1** — Status and §13 **date-marked with a discharge note, NOT rewritten**: *a review record is amended for currency, never rewritten for outcome.* **AFV-R2** — a **search-scope disclosure** added at §12 for the verification's corpus-wide absence claims; **no finding weakened.** **AFV-R3** — three editorial rankings neutralized; §10A.2's *"shared signature"* and §13 item 6's traceability/fidelity contrast **deliberately retained as substantive characterizations, not rankings.** **No finding, verdict, mapping, or assurance property was altered.** |

---

## 1. Executive Summary

**Verdict: the transformation is LAWFUL IN STRUCTURE and DEFECTIVE IN FOUR STEPS.** AD-1 **refines rather than redesigns** — the mapping from disposed strategic elements to architectural elements is **bijective**, with nothing added, dropped, split, or merged (§10A.1). Its mode is overwhelmingly derivational. **But at four points an interpretation is presented in derivational form**, strengthening or presupposing what the strategic model deliberately left open — and **in three of those cases the model's own characterization tables explicitly flagged the alternative reading that AD-1 chose without noting it.**

| # | Finding | Class | Materiality |
|---|---|---|---|
| **AFV-F1** | AD-1's constraint *"AC-1 cannot issue on its own initiative"* converts a **role separation** (author vs. gate-or-review) into a **context-boundary claim** (inside vs. outside the assessment boundary). **M6 §3.1 explicitly flags this alternative reading for both L1-4 and L1-13** — *"could be read as a role separation … rather than a knowledge-kind separation"*, *"could be an actor boundary … rather than a context boundary."* The model does not locate the issuing act architecturally | **Transformation** | **High** — load-bearing: it produced an architectural constraint, an entry on SB-1's inbound surface, and a second clause of AP-1 |
| **AFV-F2** | **DR-1 extends** *"nothing may cite a view as authority"* to *"…as authority **or as evidence**."* The evidence clause is defensible (a projection with no independent semantic identity contributes no independent evidence) but **is not derived as written** | **Semantic** | Low-Moderate |
| **AFV-F3** | **AP-3** elevates forward-only revision to a **binding architectural principle**, although **M6 §3.1 records its status as undetermined** — *"could be a constitutional property **or an unexamined habit**"* | **Semantic** | Moderate |
| **AFV-F4** | **AR-1 is placed wholly inside the PKS architecture boundary, unqualified.** That presupposes **OQ-PKS-7's** one-corpus reading; M6 §9 records that under the three-corpora reading **part of that content is outside PKS.** AD-1 lists six open questions and OQ-PKS-7 is not among them | **Governance** | **High** — an ARB-owned open question is answered by architectural placement |
| **AFV-F5** | The region label *"Normative region"* drops "Governance" from the governed concept name **Normative Governance** | **Editorial** | Low |

**Derived — the pattern, and it is the most useful thing this verification produced:** three of the five findings are instances of one failure mode. **The strategic model's Phase-B½ characterization tables recorded, for each item, "what it does NOT say" and "plausible alternative interpretations" — and AD-1 derived from the (a) columns without consulting the (b) and (c) columns.** Every over-strengthening found here was pre-flagged by the model itself, one work package earlier, in a column built precisely to prevent this.

**Derived — why no earlier gate caught this:** AD-1's own quality gates checked *traceability* (does every element trace to a concept and artifact?) and every element does. They did not check *whether the traced statement supports the strength of the claim derived from it.* **Traceability and fidelity are different properties**, and only fidelity verification distinguishes them.

**What is faithful, stated positively:** all five architectural elements originate in governed dispositions · both undefined regions are correctly withheld from encapsulation · seven of ten principles are verbatim-rule consequences · six of eight dependency rules are clean · every one of AD-1's six open questions genuinely routes strategic uncertainty outward · negative-space fidelity is intact on all four checks · no Observed statement exists · no orphan architecture.

---

## 2. Commission

**Authorized:** verify boundary · responsibility · dependency · principle · exception · negative-space · epistemic · and traceability fidelity of AD-1 against the strategic model; classify findings; state an assurance conclusion; recommend on promotion.

**Not authorized, and not done:** no AD-1 modification · no architecture derived or redesigned · no strategic knowledge introduced · no C4 update · no promotion · **no governance rule created** · no Strategic Discovery reopened · no correction applied.

## 3. Scope

**Verified:** AD-1 in full — its ten principles (AP-1..AP-10), five architectural elements (AC-1, AC-2, XD-1, AR-1, AR-2), responsibility allocations, three logical boundaries, eight dependency rules (DR-1..DR-8), five integrations, communication model, traceability matrix, and six open questions — each against M0–M8 and DAR-1, with RET-1 consulted for what the program itself recorded about its own failure modes.

**Excluded by commission:** C4-1, C4-2, KBI-1. **Derived:** the exclusion is methodologically correct — verifying an artifact against its own downstream products would confirm internal consistency while leaving source fidelity untested, which is precisely the gap this commission exists to close.

## 4. Verification Method

**Falsification-first, and specifically instrumented for one question that traceability checking does not ask:** for each architectural statement, locate its cited source, then ask **does the source support a claim of this strength** — not merely *does a source exist*.

**The method choice this verification turns on:** every derivation was tested against the strategic model's **Phase-B½ characterization tables** (M6 §3.1–§3.4), which record per evidence item **(a)** what it objectively says, **(b)** what it does *not* say, and **(c)** plausible alternative interpretations. **Three findings emerged from the (b) and (c) columns alone.** A traceability audit reads (a); a fidelity audit must read all three.

---

## 5. Boundary Fidelity

| Boundary | Source | Verdict |
|---|---|---|
| PKS architecture boundary | CBC-4's disposition as *"adjacent, outside the PKS boundary"* presupposes a PKS boundary | ✅ Originates in the model |
| AC-1 / SB-1 | CBC-1, accepted bounded context with membership and purpose | ✅ |
| AC-2 / SB-2 | CBC-2, accepted bounded context | ✅ |
| XD-1 / IB-1 | CBC-4, accepted adjacent | ✅ |
| AR-1 as an undefined region (not a component) | CBC-3 disposed a **candidate seam**; MCR-2 makes it a formal state distinct from a bounded context | ✅ **Correctly withheld from encapsulation** — the single most important boundary restraint in AD-1, and it holds |
| AR-2 as an undefined region | The expressed-knowledge core, deliberately unpartitioned; T-16 live | ✅ Correctly withheld |
| **AR-1's placement inside the PKS boundary** | — | ⚠️ **AFV-F4** — see §5.1 |

**No architectural boundary exceeds strategic evidence in its *existence*.** One exceeds it in its *placement*.

### 5.1 AFV-F4 (Governance) — the placement of AR-1

**What AD-1 does:** renders AR-1 wholly inside the PKS architecture boundary, with no qualification.

**What the model records (M6 §9, OQ-PKS-7's impact statement, verbatim in substance):** the demoted seam contains Rules that are **platform-hosted but product-binding**; *"If one corpus: `binds` is an intra-domain relationship and the Norm Custody reading gains a home. If three: `binds` is an inter-domain contract with the Engineering Governance domain, and **part of the demoted seam's content is outside PKS**."* OQ-PKS-7 **bit at M6, was surfaced, and was not resolved** — it remains ARB-owned, and Consolidation §3 carries it forward explicitly.

**Why this is a Governance finding rather than a Semantic one:** placing AR-1 entirely inside the boundary is architecturally equivalent to adopting the one-corpus reading. The program's constitutional constraint is that **no open question may be resolved implicitly**, and AD-1's own §3 lists *"resolving Surfacing Register items"* as out of scope — yet OQ-PKS-7 appears nowhere in AD-1's inputs, principles, or six open questions.

**Recommended remedy (not a decision):** qualify AR-1's placement with the contingency the model already states — the region is inside the boundary *under the one-corpus reading, which OQ-PKS-7 leaves open*, and part of its content may fall outside. **This requires no new knowledge**: M6 §9's impact statement supplies the exact wording. Because it touches an ARB-owned question, the qualification should be **dispositioned by the Authority**, not applied as an editorial fix.

---

## 6. Responsibility Fidelity

| AC-1 responsibility | Source | Verdict |
|---|---|---|
| Record evidence entries carrying an epistemic class | CBC-1 UL: *Observation* | ✅ |
| Record evidenced defects, run-scoped by design | CBC-1 UL: *Finding*; M4 mode 2 | ✅ |
| Evaluate recorded evidence against inbound criteria | CBC-1 purpose | ✅ |
| Produce a measured magnitude as a derived Observation, never a Verdict | F-BCP-2 (M3 amendment) | ✅ **Exactly faithful** — preserves the distinction the amendment exists to make |
| Hold the categorical judgment vocabulary; emit judgments in it | CBC-1 UL: *Verdict* + token set | ✅ |
| Derive conformance (adherence) and completeness (sufficiency) as siblings over the recorded substrate | M3 Recommendation B; M2 §5 | ✅ |
| **Constraint: cannot issue on its own initiative** | — | ⚠️ **AFV-F1** — §6.1 |
| Constraint: cannot author its criteria | CBC-1 UL: *"a Rule or Invariant, used here, owned elsewhere"* | ✅ Verbatim |
| Constraint: needs no identity mechanism for derived assessments | M3 Step 6; M4 Part 4 | ✅ |
| Absent capability recorded as specified-not-realized | M3/M5; T-17; OQ-PKS-3 | ✅ **Faithful, and correctly refuses to resolve the ownership vacuum** |
| Three contested concepts allocated nowhere | M6 §8 (contested, unassigned) | ✅ **Correctly declines to allocate** |

| AC-2 responsibility | Source | Verdict |
|---|---|---|
| Render governed knowledge into consumable forms | CBC-2 purpose | ✅ |
| Regenerate from sources; never hold a disagreeing edit | *"recomputed from sources, never hand-edited to disagree"* | ✅ Verbatim |
| Carry no independent semantic identity | L4-8 | ✅ Verbatim rule, not inference |
| Hold no authority | *"nothing may cite a view as authority"*; *"authority: derived"* | ✅ |
| Inherited identity liability recorded, not repaired | M4 §1.3, Amendment 3 | ✅ **Correctly carried rather than fixed** |

**No responsibility is invented.** One constraint over-reaches.

### 6.1 AFV-F1 (Semantic) — "cannot issue on its own initiative"

**What AD-1 does:** derives from *"issued by a gate or review, never by the author"* (M0 G-15) the constraint that **the issuance trigger originates outside SB-1, in AR-1**, and states it as the first of AC-1's three constraints and as the second clause of AP-1.

**What the source says:** the rule separates the **issuer** (a gate or review) from the **author**. Author and gate-or-review are **roles**, not contexts. **The model nowhere places gates or reviews on either side of a context boundary.** CBC-1's own purpose is *"…and issue judgments that feed authority without being authority"* — so the strategic model attributes issuing **to CBC-1**, while the seam holds authorization *acts* (Charter grant · Ruling · Candidate). A review that produces findings and verdicts is, on the model's own membership evidence, assessment-side.

**What makes this a fidelity failure rather than a debatable reading:** the model **pre-flagged exactly this move**. M6 §3.1's (c) column for **L1-4** (*"Recording and deciding are separate acts with separate owners"*) reads: *"Could be read as a **role separation** (human/AI) rather than a knowledge-kind separation."* And for **L1-13** (the AI/human delegation boundary): *"Could be an **actor boundary** (team topology) rather than a context boundary."* AD-1 chose the context-boundary reading of a role-separation rule **without recording that the model marks the alternative as live.**

**A second, independent inconsistency inside AD-1:** AP-1's own first clause holds that *knowledge feeds authority and is never authority* — under which **issuing a Verdict is not an authority act** (a Verdict is knowledge). If issuing is not an authority act, AP-1's first clause does not prohibit AC-1 from issuing, and the second clause's external-trigger requirement does not follow from it.

**Recommended remedy:** restate the constraint as *"the issuing act's placement relative to SB-1 is not determined by the strategic model (M6 §3.1, L1-4/L1-13 alternatives live); the model requires only that the issuer is not the author"* — and record the placement as an open question. **Materially, this weakens one constraint and one inbound-surface entry; it invalidates no boundary and no other principle.**

---

## 7. Dependency Fidelity

| Rule | Verdict |
|---|---|
| **DR-1** nothing may depend on AC-2 | ⚠️ **AFV-F2** on the *evidence* clause; the *authority* clause is verbatim-derived ✅ |
| **DR-2** no substituting a projection for its source | ✅ *"the artifact wins and the diagram is corrected"* |
| **DR-3** AC-1 may depend only on AR-1, AR-2, and the issuance trigger | ⚠️ Inherits AFV-F1 for the third item only; the first two are clean |
| **DR-4** criteria dependencies read-only | ✅ *"used here, owned elsewhere"* |
| **DR-5** no PKS element may depend on XD-1 | ✅ M6 §6.4's asymmetry evidence; DAR-1's VF-3 retained the dependency and its direction |
| **DR-6** dependencies on regions are contracts, not component dependencies | ✅ Follows from MCR-2 and the unpartitioned-region status |
| **DR-7** no cycles | ✅ Consistent with M7 §8 as disposed |
| **DR-8** only the closed verdict vocabulary crosses SB-1 outbound | ✅ R-2's Published Language, validated at DAR-1 |
| **No runtime assumptions introduced** | ✅ **Verified clean.** No dependency rule names timing, coupling, transport, or delivery; AD-1 §9.2 records the absence of such evidence rather than filling it — **the strongest single fidelity result in this verification** |

### 7.1 AFV-F2 (Semantic, minor)

**What AD-1 does:** DR-1 forbids consuming a projection *"as authority **or as evidence**."*

**What the source supports:** the governed rule covers **authority** only. **No governed rule forbids treating a projection as evidence.**

**The available derivation AD-1 did not make:** L4-8 holds that derived artifacts carry **no independent semantic identity**; a thing with no independent identity contributes no independent evidence, so citing a projection as evidence is at best a redundant citation of its source. **That derivation is sound — but it is absent from AD-1, which simply asserts the extension.**

**Recommended remedy:** either add the L4-8 derivation, or narrow DR-1 to authority and record the evidence question as open. Editorial in effect, semantic in class.

---

## 8. Principle Fidelity

| # | Verdict |
|---|---|
| **AP-1** | ⚠️ First clause ✅ verbatim (P-8); second clause is **AFV-F1** |
| **AP-2** | ✅ Four verbatim rules; the strongest-derived principle in AD-1 |
| **AP-3** | ⚠️ **AFV-F3** — §8.1 |
| **AP-4** | ✅ And **correctly qualified**: AD-1 §11 records the source as a *"preferred explanatory model, Medium-High,"* so the principle carries its epistemic status. **This is the model for how AP-3 should have been written** |
| **AP-5** | ✅ *"cite durable ids, not dated filenames"* is stated as a rule in the corpus |
| **AP-6** | ✅ M3 Step 6; M4 Part 4 |
| **AP-7** | ✅ Verbatim UL |
| **AP-8** | ✅ R-2 as validated and disposed |
| **AP-9** | ✅ R-3's constraint exactly as DAR-1 disposed it (VF-2) — **faithful to a disposition, including its correction** |
| **AP-10** | ✅ CBC-4's disposition; the translation obligation left unassigned, matching U-2 |

**Seven of ten are verbatim-rule consequences. None is an architectural preference in origin.** Two carry over-strengthening (AP-1's second clause, AP-3).

### 8.1 AFV-F3 (Semantic) — AP-3's status

**What AD-1 does:** states *"Revision is forward-only"* as a binding architectural principle: *"no in-place revocation exists anywhere in the architecture."*

**What the model records:** L1-7 observes that revocation is forward-only across the corpus — and M6 §3.1's (c) column for L1-7 reads: *"Could be a **constitutional property or an unexamined habit**."* The model **explicitly leaves the status undetermined.**

**Why it matters:** an architectural principle prohibits future construction. Deriving a prohibition from a pattern the model says may be habit **converts an unexamined regularity into a binding rule** — the strengthening this commission's DDD principles forbid.

**Recommended remedy:** qualify AP-3 exactly as AP-4 is qualified — retain the principle, record that its source status is undetermined (constitutional vs. habitual, M6 §3.1 L1-7), and note that a determination would come from OQ-class resolution rather than from architecture. **AD-1 already demonstrates the right pattern in AP-4**, so the remedy is internal consistency rather than new work.

---

## 9. Exception Fidelity

| Check | Result |
|---|---|
| Does AD-1 leave strategic uncertainty open? | ✅ **Six open questions, five routed to Authority, none to literature.** Each names its missing evidence and (after amendment) why repository evidence is insufficient |
| Is any Surfacing Register item resolved implicitly? | ⚠️ **One — OQ-PKS-7, via AR-1's placement (AFV-F4).** OQ-PKS-2, -3, -4, -9, -11, -1 are all verified untouched; -3 is explicitly carried inside Q-5 |
| Is contested membership left contested? | ✅ Allocated nowhere, deliberately |
| Is the absent capability left absent? | ✅ Specified, not realized; ownership vacuum preserved |
| Is the candidate seam left a candidate? | ✅ No promotion; MCR-3's route named as the only legitimate one |
| Are the preserved partitions left preserved? | ✅ Not promoted; not decomposed |

**Derived:** AD-1's exception discipline is strong where it is *conscious* — the six recorded questions are well-formed and correctly routed. The one failure is where uncertainty was resolved **without being noticed**, which is the harder class: an undisclosed presupposition leaves no trace in an open-questions list.

## 10. Epistemic Fidelity

| Check | Result |
|---|---|
| Derived has not become Observed | ✅ AD-1 declares no Observed statements and none appears |
| Recommendation has not become Architecture | ✅ AD-1's recommendations (realization of the absent capability; the Q-1 instrument choice) are classed and routed, not built into any element |
| Assembly has not become Discovery | ✅ Assembly-class statements integrate; none asserts a new domain fact |
| Hypothesis has not become established | ⚠️ **Partially — AFV-F3.** M4's identity model is correctly carried as a preferred explanatory model (AP-4), but L1-7's undetermined status is not carried into AP-3 |
| Are epistemic classes used consistently | ✅ Every statement carries exactly one of Derived / Assembly / Recommendation / Out of Scope |

**Derived — the distinction this review turns on:** AD-1's epistemic labeling is **accurate about the type of each statement** (all correctly "Derived") and **silent about the strength of the underlying evidence.** A statement can be genuinely Derived and still over-strengthen its premise. **Class labels track the reasoning mode; they do not track how much weight the premise can bear.** That gap is where AFV-F1, F2, and F3 all live.

## 10A. Transformation Integrity — the central objective

*Is AD-1 **a refinement, not a redesign** · **a derivation, not an interpretation** · **a transformation, not an extension**? Each question is tested separately, because the transformation can pass one and fail another — and it does.*

### 10A.1 Refinement, not redesign — **PASSES**

**Test applied (a cardinality test on the transformation, which no artifact-level review performs):** if architecture refines strategy, the mapping from disposed strategic elements to architectural elements must be **total and injective** — nothing added, nothing dropped, nothing split, nothing merged.

| Disposed strategic element | Architectural element | Mapping |
|---|---|---|
| CBC-1 Knowledge Assessment (accepted BC) | AC-1 | 1:1 |
| CBC-2 Knowledge Projection (accepted BC) | AC-2 | 1:1 |
| CBC-4 Work Management (accepted adjacent) | XD-1 | 1:1 |
| CBC-3 Normative Governance (candidate seam) | AR-1 (undefined region) | 1:1 |
| The unpartitioned expressed-knowledge core | AR-2 (undefined region) | 1:1 |
| Risk · Question · Exception record (contested, unassigned) | *deliberately unallocated* | preserved as unassigned |
| The preserved finer partition (Norm Custody ∥ Authorization Acts) | *not promoted, not decomposed* | preserved |

**Result: the mapping is bijective over the disposed set, with the three contested concepts and the preserved partition carried in exactly the state the model left them.** No architectural element exists without a strategic counterpart; **no strategic element is dropped, and none is split or merged.** **Derived: on its structural dimension, AD-1 is a refinement and not a redesign — this is the strongest fidelity result in the verification, and it is what makes the four remaining findings repairable without redesign.**

### 10A.2 Derivation, not interpretation — **MIXED: derivational in structure, interpretive at four points**

**Derived:** the transformation's *mode* is overwhelmingly derivational — seven of ten principles are verbatim-rule consequences, six of eight dependency rules are clean, and every responsibility traces to a UL statement or a governed decision. **At four points, however, an interpretation is presented in derivational form**, and this is where all four substantive findings sit:

| Point | The interpretive step taken | Consolidated at |
|---|---|---|
| AC-1's issuance constraint | A **role** separation (author vs. gate-or-review) read as a **context-boundary** claim | §6.1 — **AFV-F1** |
| DR-1's scope | *"as authority"* widened to *"as authority **or as evidence**"* | §7.1 — AFV-F2 |
| AP-3's status | A regularity the model marks *possibly habitual* stated as a **binding principle** | §8.1 — AFV-F3 |
| AR-1's placement | An **unresolved** open question answered by where the region was drawn | §5.1 — AFV-F4 |

**Derived — the shared signature, and the reason a fidelity audit finds these while a traceability audit cannot:** in all four cases **a real source exists and is correctly cited.** What fails is the *step from source to claim*. Traceability asks *is there a source?* — yes, four times. Fidelity asks *does the source bear this weight?* — no, four times.

### 10A.3 Transformation, not extension — **ONE BREACH, one borderline**

**Test:** an extension adds knowledge; a transformation only restates governed knowledge in a new form. Architectural *structure* (AP-, DR-, SB- identifiers) is new form, not new knowledge, and is the transformation's legitimate product. The question is whether any statement adds knowledge **about the domain**.

- **AFV-F4 — a breach.** Placing AR-1 wholly inside the PKS boundary supplies an answer to OQ-PKS-7, which the model records as open and ARB-owned. **That is domain knowledge the transformation created rather than carried.**
- **AFV-F1 — borderline, and recorded as such.** Locating the issuing act outside the assessment boundary is a claim about how the domain is organized that the model does not make. It reads as extension in effect, though its intent was clearly derivational.
- **AFV-F2, AFV-F3 — not extensions.** Both *strengthen* governed statements rather than adding new ones.

**Derived:** the transformation extended knowledge **once clearly and once arguably**, both times without disclosure — and disclosure is the operative failure. **AD-1 records six open questions with exemplary discipline; the defect is not that it hides uncertainty it noticed, but that these two presuppositions were never noticed at all.** An undisclosed presupposition leaves no trace in an open-questions list, which is why only a source-comparison could surface them.

### 10A.4 Transformation Integrity verdict

**The transformation is LAWFUL IN STRUCTURE and DEFECTIVE IN FOUR STEPS.** It refines rather than redesigns (bijective mapping), derives rather than interprets in the overwhelming majority of its content, and extends knowledge in one confirmed and one arguable instance. **All four defects are repairable by qualification using wording the strategic model already supplies; none requires redesign, and none disturbs the bijection.**

---

## 11. Findings

*Each finding carries the four required elements: **evidence · rationale · impact · recommended disposition.** Classes are those of the refined commission (Semantic · Transformation · Traceability · Coverage · Editorial · Governance).*

### AFV-F1 — **Transformation** *(reclassified from Semantic under the refined class list)*

- **Evidence:** AD-1 §7 constraint 1 and AP-1 clause 2 derive from *"issued by a gate or review, never by the author"* (M0 G-15) the claim that the issuance trigger originates **outside SB-1, in AR-1**.
- **Rationale:** the source separates **roles** (issuer vs. author); it does not locate the issuing act relative to any context boundary. **M6 §3.1's (c) column flags precisely this alternative for L1-4** (*"could be read as a role separation … rather than a knowledge-kind separation"*) **and L1-13** (*"could be an actor boundary … rather than a context boundary"*). CBC-1's own purpose attributes issuing to CBC-1, and under AP-1's first clause a Verdict is knowledge — so issuing is not an authority act and AP-1 does not prohibit AC-1 from issuing.
- **Impact:** one architectural constraint, one entry on SB-1's inbound surface, and AP-1's second clause. **Classified Transformation** because the defect is in the *step* from source to claim — an interpretation carried in derivational form (§10A.2) — and it reads as extension in effect (§10A.3). No boundary and no other principle is invalidated.
- **Recommended disposition:** restate as *"the issuing act's placement relative to SB-1 is not determined by the Strategic Model (M6 §3.1, L1-4/L1-13 alternatives live); the model requires only that the issuer is not the author"*, and record the placement as an open question. Bounded editorial, plus one added open question.

### AFV-F2 — **Semantic**

- **Evidence:** DR-1 forbids consuming a projection *"as authority **or as evidence**."* The governed rule is *"nothing may cite a view as authority."*
- **Rationale:** no governed rule addresses evidence-consumption. A sound derivation is available and unused: L4-8 holds that derived artifacts carry no independent semantic identity, so a projection contributes no independent evidence.
- **Impact:** widens one dependency rule beyond its cited source. Low-to-moderate; the extension is defensible, merely undemonstrated.
- **Recommended disposition:** add the L4-8 derivation, or narrow DR-1 to authority and record the evidence question as open. Bounded editorial.

### AFV-F3 — **Semantic**

- **Evidence:** AP-3 states *"no in-place revocation exists anywhere in the architecture"* as a binding principle, from L1-7's corpus-wide observation.
- **Rationale:** **M6 §3.1's (c) column for L1-7 records the status as undetermined** — *"could be a constitutional property **or an unexamined habit**."* An architectural principle prohibits future construction; deriving a prohibition from a possibly-habitual regularity strengthens the source.
- **Impact:** one of ten principles carries more force than its premise supports. Moderate. **AD-1 already demonstrates the correct pattern in AP-4**, which records its source as a *preferred explanatory model*.
- **Recommended disposition:** qualify AP-3 exactly as AP-4 is qualified — retain the principle, record the undetermined status, note that determination is an OQ-class matter and not architecture's. Bounded editorial; internal consistency rather than new work.

### AFV-F4 — **Governance**

- **Evidence:** AR-1 is placed wholly inside the PKS architecture boundary with no qualification. **OQ-PKS-7 bit at M6, was surfaced, and remains ARB-owned**; M6 §9 records that under the three-corpora reading *"part of the demoted seam's content is outside PKS."* OQ-PKS-7 appears nowhere in AD-1's inputs, principles, or six open questions.
- **Rationale:** the placement is architecturally equivalent to adopting the one-corpus reading. The program's constitutional constraint forbids resolving an open question implicitly, and AD-1's own §3 lists resolving Surfacing Register items as out of scope.
- **Impact:** **High** — an ARB-owned open question is answered by architectural placement, and the answer propagates to everything that consumes the boundary. **The confirmed extension of §10A.3.**
- **Recommended disposition:** qualify AR-1's placement with the contingency M6 §9 already states. **The wording requires no new knowledge; adopting it is nonetheless a governance act, so it belongs to the Authority — not to an editorial pass.**

### AFV-F5 — **Editorial**

- **Evidence:** AD-1 labels the region *"Normative region"*; the governed concept is **CBC-3 Normative Governance**.
- **Rationale:** terminology is frozen; dropping "Governance" from a normative-governance seam slightly shifts what the region is about (norms versus the governance of norms). AD-1 §11 traces AR-1 to CBC-3 correctly, so provenance is intact.
- **Impact:** low; no derivation depends on the label.
- **Recommended disposition:** use the governed name, or mark it explicitly as an architectural label for the CBC-3 seam. Bounded editorial.

### Summary by class

| Class | Count | Findings |
|---|---|---|
| **Transformation** | 1 | AFV-F1 |
| **Semantic** | 2 | AFV-F2 · AFV-F3 |
| **Governance** | 1 | AFV-F4 |
| **Editorial** | 1 | AFV-F5 |
| **Traceability** | 0 | — |
| **Coverage** | 0 | — |

**No Coverage finding:** every architectural obligation the model handed forward is addressed — including the DoD relationship the Authority Disposition explicitly required M7-onward work to model.

**Zero orphan architecture:** every element resolves through AD-1 §11 to a concept, an artifact, and a rationale.

## 12. Assurance Statement

**The Model → Architecture transformation is now independently verified. It is substantially faithful, with four substantive defects, all repairable without redesign, and one requiring Authority disposition rather than editorial correction.**

| Property | Assurance after AFV-1 |
|---|---|
| Every architectural element traceable | ✅ Demonstrated (§§5–8, §11) |
| Every principle derivable | ✅ Ten of ten derivable in origin; **two require qualification** (AP-1 clause 2, AP-3) |
| Strategic uncertainty preserved | ⚠️ **Preserved in six recorded cases; breached in one undisclosed case (AFV-F4)** |
| No architecture exceeds strategic evidence | ⚠️ **Four instances of over-strengthening or presupposition found** |
| The transformation independently verified | ✅ **This is the assurance gap closed** |

**Derived — the honest characterization of what closing the gap revealed:** the transformation's *structure* was sound and its *strength calibration* was not. Every finding is a claim pitched slightly higher than its premise supports, and **three of four were pre-flagged by the strategic model's own characterization columns** — which means the defects were detectable from the inputs alone, one work package earlier, and were missed because traceability checking reads only what a source *says*.

**Assurance ceiling, unchanged:** this verification is same-lineage (T-2). It attests fidelity, not correctness, and every "verified" above is same-lineage-verified.

**Search-scope disclosure for the negative claims in this verification** *(added 2026-07-30 per AFV-R2)*: several findings rest on **corpus-wide absence claims** — *"the model **nowhere** places gates or reviews on either side of a context boundary"* (§6.1) · *"**no governed rule** addresses evidence-consumption"* (§7.1). **The basis is stated so their strength can be judged: absence was established (a) against the **(b) columns** of M6 §3.1–§3.4, which record per evidence item what it does *not* say, and (b) by direct reading of the declared source set — M0–M8, DAR-1 and RET-1. It was NOT established by exhaustive search of the wider repository, and C4-1, C4-2 and KBI-1 were deliberately excluded from this verification's scope.** *Recorded because a negative claim cannot be confirmed by exhibiting an instance, so its strength rests entirely on the sufficiency of the search — and this verification's own method demands that a claim's strength be matched to its support.*

## 13. Promotion Recommendation

> **⚠️ SUPERSEDED FOR CURRENCY — 2026-07-30. Every recommendation below was acted on, and its premise no longer holds: item 1 states that AD-1 is not promotion-ready; AD-1 was PROMOTED on 2026-07-30 after all four findings were remedied and re-verified (Package D exit check 17/17, §8 fully satisfied).** **Item 1's clause *"this supersedes any earlier readiness classification of AD-1"* is itself now superseded.** Items 2, 3 and 4 are discharged: the editorial corrections were applied **in one amendment as recommended**; AFV-F4 was **disposed at Authority level**; and the recommended bounded re-read was **institutionalized as C-18**. **The text is retained verbatim because it was correct when issued — *date-marked, not rewritten* (per AFV-R1).**

1. **AD-1 is not promotion-ready.** Its classification must be **Governance Review Required**, not editorial-only, because **AFV-F4 touches an ARB-owned open question** and its remedy is a disposition rather than a fix. **This supersedes any earlier readiness classification of AD-1 that assumed editorial-only correction.**
2. **Apply AFV-F1, F2, F3, F5 as bounded editorial corrections**, each using wording the strategic model already supplies. **Recommendation:** apply them together with the previously identified corrections in one act, so AD-1 is amended once rather than four times.
3. **Dispose AFV-F4 at Authority level** — the recommended qualification requires no new knowledge (M6 §9's impact statement supplies the wording), but adopting it is a governance act because it concerns an unresolved Surfacing Register item.
4. **Re-verification recommendation:** after the corrections, **AFV-1's §§6.1, 7.1, 8.1, and 5.1 should be re-read against the amended text** — a bounded confirmation, not a re-run. *(Recommendation, not a commission.)*
5. **Carried unchanged:** AD-1's six open questions · U-1..U-4 · the deferred MCR-5 instrument · all pending candidate registers. **This verification admitted, resolved, and promoted nothing.**
6. **One observation, recorded as a finding-adjacent note and explicitly NOT a methodology proposal** (the refined commission's STOP forbids proposing methodology evolution; this commission records findings only): **traceability audits and fidelity audits detect different defect classes, and a traceability pass can be complete while a fidelity pass fails.** AD-1's own quality gates verified traceability and passed; four fidelity defects survived them, and all four were detectable from the strategic model's (b)/(c) characterization columns, which a traceability audit has no reason to open. **Recorded as an observation about this verification only. It is not routed for admission, not proposed for adoption, and no register entry is requested** — whether it has methodology significance is for a body with that authority to decide, unprompted by this record.

---

*Traceability: executes the Architecture Fidelity Verification Commission AFV-1 (PA, 2026-07-28) to close the assurance asymmetry at the Model → Architecture link · verifies `PKS_Phase_IIB_Architecture_Definition.md` against M0–M8, DAR-1, and RET-1 only — **C4-1, C4-2, and KBI-1 deliberately excluded as downstream** · eight fidelity objectives verified (boundary · responsibility · dependency · principle · exception · negative-space · epistemic · traceability) · findings AFV-F1/F2/F3 (Semantic) · AFV-F4 (**Governance**) · AFV-F5 (Editorial); zero Coverage findings; zero orphan architecture · method turned on the strategic model's Phase-B½ (b)/(c) characterization columns, which yielded three of five findings · AD-1 reclassified **Governance Review Required** for promotion · one methodology observation recorded and routed, not admitted · no AD-1 modification, no redesign, no C4 update, no promotion, no governance rule created, no discovery reopened · same-lineage act, T-2 declared. **STOP.***

> **Architecture Fidelity Verification complete. Verdict: VERIFIED WITH FINDINGS — the Model → Architecture transformation is substantially faithful, with one Governance finding (AR-1's placement presupposes an unresolved open question), three Semantic findings (over-strengthened claims, three of them pre-flagged by the strategic model's own characterization columns), and one Editorial finding. AD-1 requires Authority disposition, not editorial correction alone, before promotion. The assurance gap at the highest-interpretation transformation is now closed.**
