# IBC-1 Implementation Boundary Contract — Independent Architecture Review

| | |
|---|---|
| **Kind** | **Independent Architecture Review** of the Implementation Boundary Contract (IBC-1), reviewed against the governed baseline it claims to transmit. |
| **Authority** | Generated — never authoritative without human review. **Reviews; decides nothing, corrects nothing.** |
| **Disposition History** | 2026-07-28: executed (3 Critical · 11 Major · 5 Minor · 2 Suggestions). · 2026-07-28: **§6 addendum added — self-audit against the Authority's ARB Review Discipline**, issued after this review. **Rule 5 applied retroactively: mapping classes supplied for every finding, and IBC-M11 RECLASSIFIED Major → Recommendation** because the requirement it asserted was *proposed*, not governed. Rules 1/3/7 compliance verified; Rule 4 produced new Recommendation 13; Rule 6 was already satisfied. **Revised count: 3 Critical · 10 Major · 5 Minor · 1 Recommendation · 2 Suggestions. Verdict unchanged.** · 2026-07-28: **§7 addendum — Rules 8–13 applied.** Constitutional category, authority basis, and resolving act supplied for all 21 findings; **headline result: ZERO architecture defects — every defect is one of TRANSMISSION, not of architecture.** IBC-m3 **split** across two categories (Rule 9); IBC-S2 **reclassified to a Question** (Rule 11); four items reclassified as improvement recommendations; IBC-m4's constitutional weight **raised while its severity stayed Minor**, demonstrating the orthogonality directly. **Verdict REVISE unchanged, but its meaning is now precise: revise for transmission accuracy and completeness, not for architecture.** · 2026-07-28: **§8 addendum — Rules 14, 16, 17 applied; Rule 15 assessed and DECLINED** (n=0 against the Authority's own evidential standard; held as PMR-6). **Scoped verdict now explicit: Architecture PASS · Governance REVISE · Methodology OBSERVATION · Documentation REVISE · Implementation OUT OF SCOPE.** Rule 16 yielded a new result: **0 reviewer-knowledge findings (the review is reproducible by anyone with repository access) but only 6 of 21 artifact-local — IBC-1 read in isolation yields under a third of them, which independently corroborates the "categories where the record states content" finding by a different route.** |
| **Status** | **EXECUTED. Verdict: REVISE — 3 Critical · 10 Major · 5 Minor · 1 Recommendation · 2 Suggestions** *(as revised by §6; originally 11 Major)*. IBC-1's structure is sound and three sections are genuinely strong, but it cannot be issued as presented: it declares an **uncertified, uncorrected artifact** as its governing baseline, and it **omits the single most implementation-relevant open question in the entire record.** |
| **Object of review** | IBC-1 Sections 1–13 **as presented inline in the review request.** |
| **Standard of review** | The governed baseline: M6 record set + §14 · M7 (as corrected) · M8 · **AD-1** · C4-1 · DAR-1 · Consolidation · MCA · CDR · Authority Disposition + §7 · AFV-1 · AIA-1 · C4-2 · KBI-1 · ERV-1 · CCP-1 + §11 · the Execution Record · M0's glossary · M2's canon. |
| **Placement** | `docs/implementation/`. |

---

## 1. Executive Summary

**Verdict: REVISE.**

**The contract's *architecture* is right.** The thirteen-section shape is well chosen, and three sections are genuinely strong: **Section 7 (Forbidden Assumptions)** is well-targeted and is the best-designed part of the document; **Section 12 (Inheritance Contract)** correctly withholds authority, promotion, question-resolution, and decision rights, which is precisely what prevents code from becoming an authority mechanism; **Section 9 (Implementation Constraints)** correctly derives IC-5 and IC-6 from DR-1 and DR-5.

**It cannot be issued as presented, for three reasons that compound:**

| # | Critical finding | Why it blocks issuance |
|---|---|---|
| **IBC-C1** | **Section 2 declares AD-1 a "certified baseline" and makes it govern on conflict. AD-1 is not certified — it is currently classified GOVERNANCE REVIEW REQUIRED**, with four AFV findings open (one Governance-class) and six corrections prepared but unapplied pending the AFV-F4 disposition. C4-1 likewise carries four unapplied corrections including a governed-term drift | The contract transmits an artifact the governance record says is not yet fit to transmit |
| **IBC-C2** | **AD-1's Q-1 — that synchronicity, coupling, delivery, and ordering are NOT derivable from the model — appears nowhere in Section 5 or Section 7.** It is the most implementation-relevant open item in the record, because an implementation team must choose a coupling mechanism and will do so on day one | **The contract's stated purpose fails at exactly the point it matters most.** A team compliant with IBC-1 as written would silently convert an undetermined strategic question into a permanent technical decision |
| **IBC-C3** | **Section 2's conflict rule is inverted.** The record holds that until editorial applications are made, **the decision record governs on conflict, not the artifact** (DAR-1 §§5–7; Execution Record §1). *"Where conflicts arise, AD-1 governs"* would make an uncorrected artifact authoritative over the dispositions that correct it | Implementation could lawfully inherit two withdrawn pattern names and one withdrawn label |

**The systemic weakness beneath the Major findings:** **IBC-1 states categories where the record states content.** Section 4 says knowledge concepts are owned by "PKS" where the record assigns twenty-one concepts to five distinct placements plus three contested. Section 5 lists questions without the impact statements that make them actionable. Section 12 says implementation inherits "certified relationships" while no section states what they are. **A contract that names a constraint without stating its content cannot be conformed to** — and Section 11's conformance criteria inherit that unenforceability.

**One structural observation, stated first because it conditions everything else:** IBC-1 **does not exist as a repository artifact** (verified). It has no authority header, no traceability footer, no epistemic classes, and no Disposition History — the provenance apparatus every other artifact in this baseline carries. **No statement in it is currently traceable**, which is the property Section 11 would need in order to be auditable.

---

## 2. Findings

| # | Finding | Section | Class |
|---|---|---|---|
| **IBC-C1** | AD-1 and C4-1 declared "certified baseline" while both carry unapplied corrections; AD-1 is *Governance Review Required* | 2 | **Critical** |
| **IBC-C2** | AD-1's Q-1 (coupling/synchronicity not derivable) absent from Open Questions and Forbidden Assumptions | 5, 7 | **Critical** |
| **IBC-C3** | *"AD-1 governs on conflict"* inverts the record's rule that the decision record governs until applications are made | 2 | **Critical** |
| **IBC-M1** | Certified Baseline omits **DAR-1**, the M6 record set, the Consolidation record, the CDR, and the verification records holding the open findings | 2 | **Major** |
| **IBC-M2** | Certified Baseline omits **M0's glossary and M2's canon** — the UL that Sections 3, 4, and 7 depend on | 2 | **Major** |
| **IBC-M3** | **"Certified" used for boundaries**, conflating it with the methodology's *Provisionally Certified* status; boundaries are **ACCEPTED**. Terminology is frozen | 3 (and throughout) | **Major** |
| **IBC-M4** | **The three contested memberships are never named**, so FA-6 (*shall not resolve contested memberships*) is unconformable | 3, 6, 7 | **Major** |
| **IBC-M5** | **The accepted relationship model (R-1..R-5 as disposed) appears nowhere**, though Section 12 claims implementation inherits "certified relationships" | 3, 12 | **Major** |
| **IBC-M6** | Ownership matrix is **too coarse to enforce its own rule** — "Knowledge Concepts \| PKS" collapses five distinct placements; no row records an **unassigned** owner | 4 | **Major** |
| **IBC-M7** | **"Verdicts \| PKS"** hides two recorded subtleties: the Verdict is an AC-1 member while the *issuing act* sits at the seam (C4-2 §6.1), and **AFV-F1 found the issuing act's placement undetermined** | 4 | **Major** |
| **IBC-M8** | **OQ-PKS-1 is missing** from the Open Questions register (M8 §8 carries OQ-PKS-1/OQ-PKS-9 together) | 5 | **Major** |
| **IBC-M9** | **No implementation impact statement for any open question** — the register carries status only, where M6 §9 and M8 §8 carry impacts | 5 | **Major** |
| **IBC-M10** | **DD-1's and DD-2's reasons are factually wrong** against the record | 6 | **Major** |
| **IBC-M11** | **Five of eight dependency rules absent** (DR-2, DR-3, DR-4, DR-7, DR-8) — including DR-8, the most implementation-actionable rule in AD-1 | 8, 9 | **Major** |
| **IBC-m1** | The recorded **reopening triggers of each accepted boundary** are not among the re-entry triggers | 10 | Minor |
| **IBC-m2** | **No escalation path defined** — triggers say when to stop, not to whom | 10, 13 | Minor |
| **IBC-m3** | **No verification process** for the conformance criteria; and per T-17/OQ-PKS-3 the assessing capability **operates nowhere and is unowned** | 11 | Minor |
| **IBC-m4** | Section 12 omits the **confidence semantics and assurance ceiling** from what implementation inherits | 12 | Minor |
| **IBC-m5** | SH-3's *"unless explicitly authorized"* does not say **by whom**; "architecture re-entry" is named but not defined | 8, 13 | Minor |
| **IBC-S1** | IBC-1 lacks the provenance apparatus (header, traceability, epistemic classes, Disposition History) carried by every other baseline artifact | — | Suggestion |
| **IBC-S2** | The review request asks whether the *"audit material" claim* is correct; **no such claim appears in Section 7** | 7 | Suggestion |

---

## 3. Section-by-Section Review

### Section 1 — Purpose · **Approve**

**Q1 Clear and unambiguous?** ✅ Yes. *"A semantic firewall"* and *"preservation of strategic integrity during implementation"* are exact, and the framing — preventing implementation from *silently converting open strategic questions into permanent technical decisions* — names the actual risk rather than a generic one.

**Q2 Correctly scoped?** ✅ Yes, and it complies with the ARB's binding scope guard: it disclaims technology, repository structure, APIs, database models, deployment, and solutions.

**Q3 Strong enough to prevent scope creep?** ⚠️ Mostly. One gap: the purpose forbids the contract from *defining* implementation, but says nothing about the reverse direction — **it does not state that the contract may not be amended by implementation.** Section 13 partly covers this via "architecture re-entry," but Section 1 is where a reader forms their model of the document. **Suggestion:** add *"this contract is amended only by architecture re-entry, never by implementation practice."*

### Section 2 — Certified Baseline · **REVISE — three Criticals and two Majors**

**Q1 Correct artifacts identified as authoritative?** ❌ **No — this is the review's central failure point.**

**IBC-C1.** AD-1 is presented as a *certified baseline* and as the governing artifact. The record says otherwise: **AFV-1 found four fidelity defects** — one **Governance**-class (AFV-F4: AR-1's placement presupposes an unresolved open question), one **Transformation**-class, two **Semantic** — and **AD-1's promotion classification is *Governance Review Required***, superseding the earlier editorial-only classification. **Six corrections are prepared and unapplied**, pending the AFV-F4 disposition. C4-1 similarly carries **four unapplied corrections**, one of which (VR-2) is a governed-term drift that C4-2 flagged as *must not be skipped before promotion*. **A contract cannot certify what its own governance record has classified as requiring review.**

**IBC-C3.** The rule *"Where conflicts arise, AD-1 governs"* inverts the recorded rule. Per DAR-1 §§5–7 and the Execution Record: **until the authorized editorial applications are made, the decision record governs on conflict.** As written, IBC-1 would make the uncorrected AD-1 authoritative over the AFV-F4 disposition that corrects it — and, by the same logic applied to M7 before Package E, would have transmitted two withdrawn pattern names and one withdrawn label as governing architecture.

**Q2 Is the AD-1 governance rule appropriate?** ⚠️ **The *principle* is right and the *formulation* is wrong.** AD-1 *should* govern over C4-1 — that is C4-1's own DP-7 (*a view is subordinate to what it renders*) and AP-2 (*nothing may cite a view as authority*). What is wrong is making AD-1 govern over the **decisions and dispositions above it.** **Recommended formulation:** *"Dispositions and decision records govern over artifacts until their authorized applications are made. Among artifacts, AD-1 governs over C4-1. The strategic dispositions govern over AD-1."*

**Q3 Missing authoritative sources?** ❌ **Yes — five classes (IBC-M1) plus the vocabulary (IBC-M2):**

| Missing source | Why it is authoritative for implementation |
|---|---|
| **DAR-1** | Withdrew two pattern names and one label. **A team reading M7 or AD-1 without DAR-1 inherits withdrawn patterns** |
| **M6 record set + §14** | Holds the four strategic dispositions themselves — CBC-1/2/4 accepted, CBC-3 a candidate seam — which Sections 3 and 4 presuppose |
| **Consolidation record** | Holds the frozen strategic baseline **and its binding reopening conditions**, which Section 10 exists to enforce |
| **CDR** | Froze SDM/EOP and **declared Process Under Configuration Control** — the reason implementation may not amend governance |
| **AFV-1 · C4-2 · KBI-1 · ERV-1 · AIA-1** | Hold the **open findings** implementation inherits |
| **M0 G-1..G-17 · M2's two-tier canon** | The **Ubiquitous Language.** Sections 3, 4, and 7 use governed terms; without the glossary, "Evidence", "Verdicts", and "Criteria" are undefined in the contract |

**Derived:** as written, **a team could satisfy IBC-1 completely and still violate DAR-1.** That is the precise failure mode a boundary contract exists to prevent.

### Section 3 — Certified Context Boundaries · **REVISE — three Majors**

**Q1 Correctly stated?** ⚠️ **Substantively yes, terminologically no.** The five elements and their statuses match AD-1 §6, and **AR-1 is correctly named "Normative Governance"** — which matches the selected wording of change item C-05, a detail the drafter got right ahead of the correction being applied.

**IBC-M3.** "Certified" is a governed word in this program: **the CDR certified the *methodology*, provisionally.** Boundaries were **ACCEPTED** by Authority Disposition at Medium-High confidence. Using *certified* for boundaries conflates two governed senses under a frozen terminology regime — **the same defect class as VR-2**, which C4-2 classified Moderate for exactly this reason. **Recommended:** *Accepted Context Boundaries*, with confidence grades stated.

**Q2 Undefined regions correctly identified?** ⚠️ Both are present, but **AR-1's placement contingency is absent from Section 3.** FA-3 covers the assumption side well, but a reader consulting Section 3 alone sees an undefined region unproblematically inside PKS — which is exactly what AFV-F4 found to be unqualified in AD-1 itself.

**Q3 External domains correctly scoped?** ✅ XD-1 correct as an external domain, consistent with CBC-4's disposition as adjacent.

**Q4 Any boundary that should be certified but is not?** ✅ **No, and the restraint is correct** — AR-1 and AR-2 must not be elevated. Section 3 gets this right.

**Q5 Any boundary that should remain undefined but is incorrectly stated?** ✅ No.

**IBC-M4 · IBC-M5 — two omissions that break other sections:**
- **The three contested memberships (Risk · Question · Exception record) are never named anywhere in IBC-1.** FA-6 forbids resolving them; **a team cannot comply with a prohibition whose subject is unstated.**
- **The relationship model is absent entirely.** Section 12 claims implementation inherits "certified relationships," and no section states them. Per DAR-1: three named patterns survive (R-2 Customer/Supplier over a narrow Published Language · R-3 Conformist · R-5 Separate Ways), **two relationships are dependencies with no pattern name** (R-1, R-4), and **R-3 carries a unidirectionality constraint.** All five are directly implementation-relevant.

### Section 4 — Knowledge Ownership Matrix · **REVISE — two Majors**

**Q1 Is every strategic concept assigned an owner?** ❌ **No.** The record places **twenty-one M5 concepts** across five distinct locations: 5 in AC-1 · 3 + the views family in AC-2 · 1 in XD-1 · 5 in the AR-1 seam · 4 in AR-2 (Decision · Term · Model element · Contract) · **3 contested and unassigned.** IBC-1 collapses this into *"Knowledge Concepts | PKS"*.

**IBC-M6.** The matrix's own rule is *"no implementation component may assume ownership different from that recorded here"* — **but the matrix does not record the ownership.** A component placing Decision inside AC-1 would violate the record while conforming to the matrix. **The rule is unenforceable against its own table.** Also: **no row records an UNASSIGNED owner**, though DD-3 and FA-5 both presuppose one exists (U-2's translation obligation).

**Q2 Are the assignments correct?** ⚠️ **"Criteria | AR-1" is correct and well done** — it matches AP-7 (*used here, owned elsewhere*) and DR-4 exactly, and it is the single most important ownership fact for implementation. But:

**IBC-M7.** *"Verdicts | PKS"* hides two recorded subtleties. C4-2 §6.1 found the **Verdict is an AC-1 member while the issuing act belongs to a gate or review at the seam** — graded the model's *weakest edge*. And **AFV-F1 found AD-1's claim about the issuing act's placement to be undetermined**, with a new open question (C-08) pending on exactly this. **IBC-1 states as settled ownership what the governance record has just found unsettled.**

**Q3 Ownership conflicts?** ✅ None internal to the matrix. The conflicts are between the matrix and the record.

**Q4 Is the "no hidden coupling" rule enforceable?** ❌ **The rule asked about is not present.** Section 4 states a *no-different-ownership* rule, not a no-hidden-coupling rule. On enforceability of what *is* stated: see IBC-M6.

### Section 5 — Open Questions Register · **REVISE — one Critical, two Majors**

**Q1 Is every open question listed?** ❌ **No — three omissions, one of them Critical.**

**IBC-C2 — the most serious finding in this review.** **AD-1's Q-1 is absent:** the governed model contains **no timing, coupling, delivery, or ordering evidence**, so synchronicity is **not derivable** — AD-1 §9.2 records this explicitly and routes it under its Exception Protocol, noting that the missing input is *domain evidence*, not literature. **Every implementation team must choose a coupling mechanism, and will do so immediately.** Without Q-1 recorded, they will choose believing the architecture determined it. **This is precisely the conversion of an open strategic question into a permanent technical decision that Section 1 defines the contract to prevent.** IBC-1 fails at its stated purpose at the one point where failure is certain rather than possible.

**IBC-M8.** **OQ-PKS-1 is missing.** M8 §8 carries *"OQ-PKS-1 / OQ-PKS-9 — identity scheme; kind→lifecycle-vocabulary mapping"* as reinforced and ARB-owned. IBC-1 lists OQ-9 and drops OQ-1 — and identity scheme is highly implementation-relevant, since AP-4 forbids a global identifier scheme.

Also absent: **AD-1's remaining open questions Q-2..Q-6**, and the **issuance-placement question** that C-08 will add.

**Q2 Is the implementation impact correctly stated for each?** ❌ **No impact statements are present at all.** The table carries number, question, and *"OPEN"*. M6 §9 and M8 §8 carry an impact statement for every question — and **for an implementation-facing contract the impact is the operative content.** *"OQ-PKS-7: OPEN"* tells a developer nothing; *"if the three roots are separate corpora, part of the normative region's content lies outside PKS, so a schema or module that encloses it assumes the one-corpus reading"* tells them exactly what to avoid. **IBC-M9.**

**Q3/Q4 Should be open but are not / resolved but are not?** ✅ Of those listed, all seven are correctly open and none should be resolved. **No question is wrongly marked.**

### Section 6 — Deferred Decisions Register · **REVISE — one Major**

**IBC-M10 — two reasons are factually wrong:**

| Entry | IBC-1's reason | The recorded reason |
|---|---|---|
| **DD-1** CBC-3 promotion | *"Await operational evidence"* | ❌ The recorded triggers are (i) evidence that authorization acts and standing norms share an identity or lifecycle regime, or (ii) a Phase-B-style collection under the finer partition. And the **lift-path is precise: Phase-F completion alone cannot lift it; only reduced single-source dependency or strengthened convergence can.** *"Operational evidence"* is not a recorded condition and points implementation at the wrong signal |
| **DD-2** Partition re-entry | *"Await MCR-3 decision"* | ❌ **MCR-3 was adopted at the CDR.** The route is defined; what is awaited is an **Authority act commissioning a run under it** |
| **DD-3** U-2 translation | *"Await Authority assignment"* | ✅ Correct |
| **DD-4** AFV-F4 placement | *"Await Authority disposition"* | ✅ Correct |

**Q3 Should be deferred but is not?** ⚠️ The **contested memberships' assignment** (IBC-M4) and the **deferred MCR-5 lens-independence instrument** with its recorded trigger. **Q4 Should be decided but is not?** ✅ None — all four are properly the Authority's.

### Section 7 — Forbidden Assumptions · **Approve with one Critical addition**

**This is the strongest section in the document.** FA-1..FA-6 are well-targeted, correctly identify the highest-risk silent conversions, and FA-3 in particular (*shall not assume AR-1 establishes a fully internal boundary interpretation*) shows genuine understanding of AFV-F4 — it forbids the assumption that AD-1 itself makes unqualified.

**Q1/Q3 Missing:** **the FA paired with IBC-C2** — *developers shall not assume the architecture determines communication mechanism (synchronicity, coupling, delivery, ordering)*. Without it, Section 7 leaves open the one conversion that is certain to occur.

**Adequately covered elsewhere, and worth recording so they are not added redundantly:** treating a view as authoritative is covered structurally by IC-5 (no component may depend on AC-2); assuming AR-2's interior is partitioned is covered by IC-7.

**Q4 Is the "audit material" claim correct?** ⚠️ **No such claim appears in Section 7 as presented** (IBC-S2). If intended, it should be added explicitly — and it would be a good addition, since a forbidden-assumption list is only useful if a reviewer can test against it.

### Section 8 — Seam Handling Rules · **Approve with recommendations**

**Q1 Correct?** ✅ SH-1..SH-4 are sound in principle and correctly aimed at the risk that schema coupling ossifies an unresolved seam.

**Q2 Missing (part of IBC-M11):** the two most actionable seam rules in AD-1 are absent — **DR-8** (only the closed Verdict vocabulary crosses the assessment boundary outbound) and the **R-3 unidirectionality constraint** (no return path in the authority/evidence direction). DR-8 in particular is concrete, checkable, and exactly the kind of rule a seam section should carry.

**Q3 Enforceable?** ⚠️ SH-3's *"unless explicitly authorized"* names no authorizing party (IBC-m5). **Q4 Protect future refactoring?** ✅ Yes — SH-2 is well judged: forbidding schema coupling across unresolved seams is the single most effective protection against a deferred decision becoming permanent.

### Section 9 — Implementation Constraints · **Approve with one Major**

**Q1 Correctly derived?** ✅ **IC-5 and IC-6 are exact** — correctly cited to DR-1 and DR-5, and IC-5 captures the unusual and evidence-driven fact that AC-2 is a terminal sink. **IC-2's explicit naming of OQ-PKS-7 is the best-designed constraint in the document.**

**On the scope guard, defended rather than flagged:** IC-1, IC-2, and IC-3 mention repository structures, API contracts, and database schemas. **This does not breach the ARB's prohibition.** Each is phrased as a constraint — *shall not imply*, *shall not encode*, *shall preserve* — and **naming a mechanism in order to forbid a misuse of it is not prescribing the mechanism.** The line is close, and the phrasing stays on the correct side of it.

**Q2 Missing (IBC-M11):** five of eight dependency rules — **DR-2** (no substituting a projection for its source), **DR-3**, **DR-4** (criteria read-only — the enforcement of Section 4's best row), **DR-7** (no cycles), **DR-8**. Also absent: AP-3's forward-only revision. ⚠️ **Note carefully:** AFV-F3 found AP-3's status *undetermined* (constitutional property or unexamined habit), so **if forward-only is added it must carry that qualification** — omission accidentally avoids the over-strengthening, but omission is not correctness.

**Q3/Q4 Enforceable and protective?** ✅ For what is present. IC-7's *undefined regions shall remain undefined* is the correct structural expression of AD-1 §6.2.

### Section 10 — Re-entry Triggers · **Approve with recommendations**

**Q1/Q2** ✅ RT-1..RT-6 are correctly identified. **Missing (IBC-m1):** the **recorded reopening triggers of each accepted boundary** — CBC-1's flip condition (a rule-governed conformance record with durable identity), CBC-2's (one governed derived artifact with durable semantic identity citable as authority), CBC-4's competitor trigger. **These are binding reopening conditions** per Consolidation §2.2 and Authority Disposition §1, and they are exactly the kind of thing implementation might unknowingly build.

**Q3** ✅ The triggers correctly identify when implementation must stop. **Q4 Escalation path clear?** ❌ **No path is stated** (IBC-m2). The baseline has an escalation rule (CCP-1 §9: *if the pre-supplied wording does not fit, stop and escalate — the mismatch is a finding, not an invitation to compose*), and IBC-1 should carry its analogue: **stop, record, escalate to the Authority; do not resolve in place.**

### Section 11 — Conformance Criteria · **REVISE — measurability**

**Q1 Measurable?** ❌ CC-1..CC-6 restate the constraints rather than defining tests. *"No forbidden assumption observed"* specifies no observer, method, or evidence. Contrast with what the baseline can already do: **C4-2 demonstrated a checkable form** — trace every element to a source, verify every omission is justified.

**Q2 Complete?** ⚠️ Incomplete in proportion to the omissions above — criteria cannot cover the relationship model, the contested memberships, or Q-1, none of which the contract states.

**Q3 Is the verification process defined?** ❌ **No.** And this deserves a sharper observation than a Minor: **the record states that whole-system conformance assessment operates nowhere (T-17) and that no role owns it (OQ-PKS-3, unresolved).** **Section 11 therefore assigns conformance criteria to a capability the strategic model says nobody performs — IBC-1 instantiates T-17 in its own enforcement clause.** *(IBC-m3, recorded as Minor because it reflects a genuine and disclosed gap in the domain rather than a drafting error — but it should be stated in the contract rather than left for a reader to discover.)*

### Section 12 — Inheritance Contract · **Approve with one Minor**

**Q1/Q2/Q3** ✅ **Correct, and the "does NOT inherit" list is the strongest single element of IBC-1.** Withholding authority rights, promotion rights, question-resolution authority, seam-promotion authority, and decision-making authority is exactly right.

**Q4 Does it prevent teams from treating code as an authority mechanism?** ✅ **Yes — in principle, and this is the contract's central achievement.** The withheld-rights list is what makes the semantic firewall real rather than aspirational.

**Missing (IBC-m4):** implementation should also inherit **the confidence semantics and the assurance ceiling** — every boundary is graded at most Medium-High on a **single-corpus, single-lineage** basis (MCR-5), and the methodology is only *provisionally* certified. **A team inheriting "certified boundaries" without inheriting their confidence basis will over-trust them** — which is PMR-2's principle (*a composite claim must not inherit its strongest component's confidence*) applied at the handover. Also missing: the **open findings** implementation inherits while they remain unapplied.

### Section 13 — Authority Statement · **Approve with one Minor**

**Q1/Q2** ✅ Correct and well scoped. *"May optimize solutions but may not alter strategic meaning, ownership, boundary placement, or unresolved governance decisions"* is precisely the right division — it grants implementation full freedom in its own domain while withholding exactly four things.

**Q3 Is "architecture re-entry" clear?** ❌ **Named but undefined** (IBC-m5) — no procedure, no addressee, no artifact. **Q4 Strong enough?** ⚠️ Strong on substance; weakened by the undefined re-entry procedure, since a requirement with no procedure is difficult to invoke under pressure.

---

## 4. Recommendations

**Before issuance (resolves the three Criticals):**

1. **Re-title Section 2 and restate the conflict rule.** Either issue IBC-1 *after* the AFV-F4 disposition and the editorial packages, or issue it now with the baseline described accurately: **AD-1 and C4-1 carry unapplied corrections; the decision records govern on conflict; among artifacts AD-1 governs over C4-1; the strategic dispositions govern over AD-1.**
2. **Add Q-1 to Section 5 with its impact statement, and add the paired forbidden assumption to Section 7** — *developers shall not assume the architecture determines communication mechanism.* This is the single highest-value change available to the document.
3. **Extend the Certified Baseline** to include DAR-1, the M6 record set, the Consolidation record, the CDR, the verification records, and the vocabulary (M0 glossary, M2 canon).

**Before issuance (resolves the load-bearing Majors):**

4. **Replace the ownership matrix with the record's actual placements** — twenty-one concepts across five locations, plus an explicit **UNASSIGNED** row for U-2 — and qualify the Verdict row with the issuing-act question AFV-F1 opened.
5. **Add the relationship model** (three surviving patterns, two pattern-free dependencies, R-3's unidirectionality constraint), since Section 12 already claims implementation inherits it.
6. **Name the three contested memberships**, without which FA-6 cannot be conformed to.
7. **Add impact statements to every open question**, carried from M6 §9 / M8 §8.
8. **Correct DD-1's and DD-2's reasons**, and add the missing dependency rules to Sections 8–9 (DR-2, DR-3, DR-4, DR-7, **DR-8 especially**).
9. **Rename "Certified" to "Accepted" for boundaries**, with confidence grades.

**Recommended, resolvable during implementation:**

10. Add the accepted boundaries' recorded reopening triggers to Section 10; define the escalation path; define "architecture re-entry"; name SH-3's authorizing party; add the confidence semantics to Section 12; and **state in Section 11 that the assessing capability is currently unowned (OQ-PKS-3) and unrealized (T-17)** rather than leaving a reader to discover it.

**Suggestions:**

11. Give IBC-1 the provenance apparatus every other baseline artifact carries — authority header, traceability footer, epistemic classes, Disposition History — since Section 11's auditability depends on it.
12. Add the missing Section 1 clause: *this contract is amended only by architecture re-entry, never by implementation practice.*

---

## 5. Verdict

### **REVISE**

**Not *Reject*:** the contract's architecture is sound, its purpose is correctly framed, and Sections 7, 9, 12, and 13 are genuinely well designed. The withheld-rights list in Section 12 is the strongest element and makes the semantic firewall real. **Nothing in IBC-1 requires redesign** — every finding is an accuracy, completeness, or terminology repair against a record that already supplies the correct content.

**Not *Approve with Recommendations*:** three Critical findings prevent issuance. IBC-1 declares an artifact certified that its own governance record classifies as *Governance Review Required*; it inverts the conflict rule in a way that would transmit withdrawn pattern names as governing architecture; and **it omits the one open question that implementation is guaranteed to encounter on its first day.**

**The verdict in one sentence:** **IBC-1 is the right artifact, drafted slightly ahead of the baseline it transmits and slightly above the level of detail that makes a contract conformable** — and both are fixable from material the record already holds.

**Derived — the pattern worth carrying forward:** every Major finding is an instance of the same thing. **IBC-1 states categories where the record states content:** "PKS" for five distinct placements, "OPEN" for seven impact statements, "certified relationships" for a model it never lists. **A boundary contract's protective power lies entirely in its specificity**, because a constraint whose subject is unstated cannot be conformed to and cannot be audited — which is why Section 11's unenforceability is a symptom rather than a separate defect.

---

## 6. Addendum — self-audit against the ARB Review Discipline (added 2026-07-28)

**Standing:** additive. The Authority issued a seven-rule **ARB Review Discipline** after this review was produced. **Applying it retroactively is the honest response, and Rule 5 changes one finding's weight.** §§1–5 stand as issued; this addendum governs on conflict.

### 6.1 Rule 5 — mapping classification, now supplied for every finding

*Rule 5 requires each identifier mapping to be classified **authoritative** (the record states it) · **traceable** (entailed by the artifact's own text or a stated rule) · **inferred** (a reasonable derivation, not stated) · **proposed** (this reviewer's judgment that it belongs).* **This was not supplied in §2, and it should have been — a finding asserted against a *proposed* requirement does not carry the weight of one asserted against an *authoritative* one.**

| # | Mapping class | Basis | Weight after classification |
|---|---|---|---|
| **IBC-C1** | **Authoritative** | AD-1's classification as *Governance Review Required* is recorded; the four AFV findings and six unapplied corrections are recorded | **Critical — unchanged** |
| **IBC-C2** | **Traceable** | Q-1's existence is authoritative (AD-1 §9.2, §12). That IBC-1 must carry it is **entailed by IBC-1's own Section 1**, which defines the contract's purpose as preventing exactly this conversion | **Critical — unchanged.** Entailment from the artifact's own stated purpose is the strongest non-authoritative basis available |
| **IBC-C3** | **Authoritative** | DAR-1 §§5–7 and the Execution Record state that the decision record governs until applications are made | **Critical — unchanged** |
| **IBC-M1** | **Mixed: traceable for DAR-1, inferred for the rest** | DAR-1's omission is *demonstrable* — a team reading M7 or AD-1 without it inherits withdrawn patterns. The other four sources are an inferred requirement | **Major for the DAR-1 limb; the remainder is a Recommendation** |
| **IBC-M2** | **Traceable** | Sections 3, 4, and 7 use governed terms; without the glossary those terms are undefined *in the contract* | Major — unchanged |
| **IBC-M3** | **Authoritative** | "Certified" is the CDR's word for the methodology's status; boundaries were *accepted* by Authority Disposition. Terminology is frozen | Major — unchanged |
| **IBC-M4** | **Traceable** | FA-6 forbids resolving contested memberships; the prohibition is unconformable without the list. Logical entailment from IBC-1's own text | Major — unchanged |
| **IBC-M5** | **Traceable** | §12 claims implementation inherits "certified relationships"; no section states them | Major — unchanged |
| **IBC-M6** | **Traceable** | The matrix's own rule is unenforceable against its own table | Major — unchanged |
| **IBC-M7** | **Authoritative** | C4-2 §6.1 graded the Verdict/issuing-act split the model's weakest edge; AFV-F1 found the placement undetermined | Major — unchanged |
| **IBC-M8** | **Authoritative** | M8 §8 carries OQ-PKS-1 alongside OQ-PKS-9 | Major — unchanged |
| **IBC-M9** | **Traceable** | Entailed by IBC-1's purpose: *"OPEN"* alone cannot prevent a silent conversion | Major — unchanged |
| **IBC-M10** | **Authoritative** | The recorded reopening triggers and lift-path for CBC-3; MCR-3's adoption at the CDR | Major — unchanged |
| **IBC-M11** | ⚠️ **PROPOSED** | **Nothing in the governed record requires IBC-1 to carry all eight dependency rules.** That they belong in a boundary contract is **this reviewer's judgment**, not a governed mandate | ⚠️ **RECLASSIFIED: Major → Recommendation.** See §6.2 |
| **IBC-m1** | **Traceable** | The reopening conditions are binding per Consolidation §2.2 and Disposition §1, and Section 10 exists to catch re-entry | Minor — unchanged |
| **IBC-m2 · m3 · m4 · m5** | **Traceable** (m3 additionally authoritative on T-17/OQ-PKS-3) | Each is entailed by the artifact's own text or by a recorded fact | Minor — unchanged |

**Revised finding count: 3 Critical · 10 Major · 5 Minor · 1 Recommendation (reclassified) · 2 Suggestions.**

### 6.2 IBC-M11 reclassified — Major → Recommendation

**What the review asserted:** *"five of eight dependency rules absent (DR-2, DR-3, DR-4, DR-7, DR-8) — including DR-8, the most implementation-actionable rule in AD-1."*

**What Rule 5 exposes:** the *existence* of the five rules is authoritative, but **the requirement that IBC-1 carry them is proposed, not governed.** No record states which of AD-1's dependency rules a boundary contract must transmit. **Asserting an omission against a requirement I supplied myself inflated the finding.**

**Restated at its correct weight:** **Recommendation** — DR-8 in particular is concrete and checkable and would strengthen Sections 8–9 materially, and DR-4 would give Section 4's best row (*Criteria | AR-1*) its enforcement. **But their absence is not a defect against the record.**

**Derived — and this is why Rule 5 earns its place:** the finding *read* as authoritative because it cited governed identifiers. **Citing a governed identifier does not make the requirement governed.** That is the same distinction ERV-1 drew between *pre-supplied* and *determined* wording, and the same one AFV-1 drew between *traceability* and *fidelity* — a real source, cited correctly, supporting a claim it does not actually bear.

### 6.3 Rules 1, 3, 4, 7 — compliance verified

| Rule | Result |
|---|---|
| **1** — distinguish constraint / property / mechanism | ✅ Applied, including where it cut against an easy finding: §3's Section 9 review **defended** IC-1/IC-2/IC-3 rather than flagging them, on the ground that *naming a mechanism in order to forbid a misuse is not prescribing the mechanism* |
| **3** — do not prescribe mechanisms | ✅ **Verified by re-read: no recommendation names a tool, framework, technology, or realization technique.** No ArchUnit, no ACL assignment, no schema separation. Where AFV-1 discussed *ACL-shaped consumption*, it was as an **Evans relationship pattern** (Strategic DDD) and it was explicitly **not assigned**, because no translation locus exists |
| **4** — conformance requirements, not realization techniques | ⚠️ **One refinement identified.** Recommendation 10 asks Section 11 to state that the assessing capability is unowned and unrealized — correct. But **Section 11's criteria themselves would be stronger in "shall demonstrate" form**: *"the implementation shall demonstrate that no forbidden assumption is embodied"* rather than *"no forbidden assumption observed"*, which names no demonstrator. **Added as Recommendation 13 (§6.4)** |
| **7** — scope check per recommendation | ✅ Re-verified across all twelve. All sit in Strategic DDD or implementation-governance scope; **none prescribes implementation structure.** Recommendations 4, 5, 6, and 7 add *governed content*; 1, 2, 3, 8, 9 correct *accuracy*; 10–12 concern *governance apparatus* |

### 6.4 Recommendation 13 (new, from Rule 4)

**Restate Section 11's conformance criteria in demonstrative form** — *"the implementation shall demonstrate that …"* — so each criterion names an obligation capable of being discharged and evidenced, rather than a state to be observed by an unnamed observer. **This does not specify how to demonstrate**, which would breach Rule 3; it specifies only that demonstration is owed.

### 6.5 Rule 6 — already satisfied before the discipline was issued

Rule 6 (source-of-truth governance) was applied independently at M8 Finding 1: the proposed improvement to *"feed authority"* was **held against the frozen source (H-1)** rather than applied to the assembly artifact, and the general rule is registered as **PMR-5** with three observed instances. **No action required.**

---

## 7. Addendum 2 — Rules 8–13 applied (added 2026-07-28)

**Standing:** additive. The Authority extended the ARB Review Discipline with **Rules 8–13** after §6 was written. Applying them retroactively produces a result that **changes what the REVISE verdict means.**

### 7.1 The headline result — IBC-1 contains ZERO architecture defects

**Rule 9 requires each output to declare exactly one constitutional category. Classifying all 21 findings produces: 0 architecture defects · 12 governance defects · 1 methodology observation · 6 improvement recommendations · 1 question · (1 finding split across two categories).**

**Derived, and this is the addendum's most important statement: nothing in IBC-1 asserts wrong architecture.** Every defect is a failure of **transmission** — the contract misstates the baseline's *status*, omits recorded content, or states as settled what the record left open. **The architecture it carries is sound; the carrying is defective.** Under the old single-severity list this was invisible: "3 Critical, 11 Major" reads as *the contract is substantially wrong*, when the accurate reading is *the contract is substantially incomplete and its status claims are wrong.* **Severity and category are indeed orthogonal, and merging them misdescribed the artifact.**

### 7.2 Full classification (Rules 8, 9, 11, 12)

| # | **Rule 9** category | **Rule 8** authority basis | **Rule 11** resolving act |
|---|---|---|---|
| **IBC-C1** | **Governance defect** | Baseline violation | Editorial correction *(resolves automatically once the disposition is taken and packages applied)* |
| **IBC-C2** | **Governance defect** | Baseline violation | Editorial correction — add Q-1 + the paired FA |
| **IBC-C3** | **Governance defect** | Baseline violation | Editorial correction — restate the conflict rule |
| **IBC-M1** | **Governance defect** (DAR-1 limb) / **improvement recommendation** (remainder) | Baseline violation / reviewer recommendation | Editorial correction |
| **IBC-M2** | Governance defect | Derived architectural constraint | Editorial correction |
| **IBC-M3** | Governance defect | Baseline violation *(terminology freeze is a GO condition)* | Editorial correction |
| **IBC-M4 · M5** | Governance defect | Baseline violation | Editorial correction |
| **IBC-M6** | Governance defect | Derived architectural constraint | Editorial correction |
| **IBC-M7 · M8 · M10** | Governance defect | Baseline violation | Editorial correction |
| **IBC-M9** | Governance defect | Derived architectural constraint | Editorial correction |
| **IBC-M11** | **Improvement recommendation** | **Reviewer recommendation** | No action required *(strengthening only)* |
| **IBC-m1** | Governance defect | Baseline violation *(binding reopening conditions)* | Editorial correction |
| **IBC-m2 · m5** | **Improvement recommendation** | Reviewer recommendation | No action required |
| **IBC-m3** | ⚠️ **SPLITS — see §7.3** | — | — |
| **IBC-m4** | **Governance defect** | **Baseline violation** *(Consolidation §2.2 binds the confidence semantics; §12 claims baseline inheritance)* | Editorial correction |
| **IBC-S1** | Improvement recommendation | Reviewer recommendation | No action required |
| **IBC-S2** | ⚠️ **RECLASSIFIED to a QUESTION — see §7.4** | — | — |

**Rule 12 applied to the thirteen recommendations:** Recs **1, 2, 3** = **mandatory for conformance** (they resolve the Criticals) · Recs **4, 5, 6, 7, 8, 9** = **mandatory for conformance** (they resolve governance defects with baseline basis) · Recs **10, 13** = **strengthening** · Recs **11, 12** = **informative**. **None is a future-methodology candidate** — PMR-5 already carries the only methodology item this review generated.

### 7.3 IBC-m3 splits into two constitutional categories (Rule 9)

The original finding merged two different things under one Minor:

1. *"No verification process is defined for the conformance criteria"* → **Improvement recommendation** · reviewer recommendation · **no action required** (strengthening).
2. *"The record states the assessing capability operates nowhere (T-17) and is unowned (OQ-PKS-3), so IBC-1 instantiates T-17 in its own enforcement clause"* → **Methodology observation** · authority basis: **derived architectural constraint** · resolving act: **none by IBC-1** — it routes to the methodology/Authority track, because **it is not a defect in IBC-1 at all.** IBC-1 cannot fix an unowned domain capability.

**Derived:** merging them made a *routing error* look like a *drafting error*. **The second item is not IBC-1's to resolve**, and under the old classification a revising author would have tried.

### 7.4 IBC-S2 reclassified to a QUESTION (Rule 11)

**Original:** *"the review request asks whether the 'audit material' claim is correct; no such claim appears in Section 7."*

**Rule 11 test — what constitutional act would resolve this?** **Unclear.** Either the request references a claim IBC-1 was expected to contain (→ editorial correction), or the request references a different draft (→ no action), or the claim was intended and dropped (→ editorial correction). **The required act cannot be determined from the artifact.**

**Reclassified: QUESTION, not a finding.** *For the author of IBC-1: was an "audit material" claim intended in Section 7, and if so, what did it assert?*

### 7.5 Rule 13 — self-audit log

| Finding | Original | Revised | Governing rule | Reasoning |
|---|---|---|---|---|
| **IBC-M11** | Major (defect) | **Recommendation** / improvement / reviewer recommendation | **Rule 5** | Cited governed identifiers, but the requirement that a boundary contract carry all eight DRs is *proposed*, not governed |
| **IBC-m3** | one Minor finding | **split** — Improvement recommendation **+** Methodology observation | **Rule 9** | Two constitutional categories were merged under one severity label; the second is not IBC-1's to resolve |
| **IBC-S2** | Suggestion | **Question** | **Rule 11** | The resolving constitutional act cannot be determined from the artifact |
| **IBC-m2 · m5 · S1** | Minor / Suggestion "findings" | **Improvement recommendations** | **Rule 9** | Nothing in the record requires them; they strengthen rather than repair |
| **IBC-m4** | Minor | **Minor governance defect, baseline basis** | **Rules 8 + 9** | Severity unchanged, **constitutional weight raised** — Consolidation §2.2 binds the confidence semantics, and §12 claims baseline inheritance. **Demonstrates the orthogonality directly** |
| **All 21** | severity only | **+ authority basis + category + resolving act** | **Rules 8, 9, 11** | Constitutional semantics made as explicit as technical semantics |

**Revised totals: 3 Critical · 10 Major · 4 Minor · 6 Improvement recommendations · 1 Methodology observation · 1 Question · 2 Suggestions folded into recommendations.** **Verdict REVISE unchanged — but its meaning is now precise: revise for transmission accuracy and completeness, not for architecture.**

### 7.6 Rule 10 — verified

Every wording change this review proposes was checked for level: **Recommendation 9** (*"Certified" → "Accepted"*) touches **IBC-1's own prose**, not inherited wording → **(c), directly rewritable** ✅. **Recommendations 4, 5, 6, 7** add *quoted* baseline content → **(b), propagation from the source**, and each must be quoted verbatim-in-substance rather than paraphrased ✅. **No recommendation rewrites authoritative-source wording** — the one that would have (M8 Finding 1) was held as H-1 before Rule 10 existed ✅.

---

## 8. Addendum 3 — Rules 14, 16, 17 applied (added 2026-07-28)

**Standing:** additive. Rules 14, 16, and 17 were adopted; **Rule 15 was assessed and declined** on the Authority's own evidential standard and is held as PMR-6.

### 8.1 Rule 14 — review scope declared, and the verdict scoped to it

**Scope of this review, declared retroactively:**

| Scope | In/out | **Scoped verdict** |
|---|---|---|
| **Architecture** | In | **PASS** — 0 architecture defects (§7.1) |
| **Governance** | In | **REVISE** — 12 governance defects, 3 of them Critical |
| **Methodology** | In (incidentally) | **OBSERVATION** — 1 (the T-17 instantiation, §7.3) |
| **Documentation** | In | **REVISE** — provenance apparatus absent (S1); status claims wrong |
| **Implementation** | **OUT OF SCOPE** | — no implementation exists to review, and IBC-1 correctly prescribes none |

**Derived — this is what Rule 14 buys:** the unscoped verdict "REVISE" invited the reading *the contract is architecturally wrong*. **Scoped, it reads correctly: architecture PASS, governance REVISE.** A revising author now knows which sections to touch and which to leave alone.

### 8.2 Rule 16 — evidence origin, and it surfaces a real reproducibility fact

| Origin | Count | Findings |
|---|---|---|
| **Artifact-local** *(reproducible from IBC-1 alone)* | **6** | M4 (FA-6 unconformable without the list) · M5 (§12 claims relationships never stated) · M6 (the matrix's rule unenforceable against its own table) · m2 (no escalation path) · m5 (SH-3's authorizer; re-entry undefined) · the S2 Question |
| **Cross-artifact** *(governing artifact identified in each case)* | **15** | C1, C3, M3, M7, M8, M10 (baseline records) · C2 (AD-1 §9.2/§12) · M1 (DAR-1 · M6 · Consolidation · CDR · the verification records) · M2 (M0 · M2) · M9 (M6 §9 · M8 §8) · m1 (Consolidation §2.2 · Disposition §1) · m3 (M3/M5 · T-17 · OQ-PKS-3) · m4 (Consolidation §2.2) · M11 (AD-1 §10A) · S1 (the baseline's artifact conventions) |
| **Reviewer knowledge** *(not reproducible without participation)* | **0** | — |

**Two derived results, one reassuring and one not:**

✅ **Zero findings rest on reviewer knowledge.** Every one cites a governed artifact, so **the review is reproducible by anyone with repository access** — which is the property Rule 16 exists to establish, and it holds.

> **⚠️ POST-REVIEW ANNOTATION (added 2026-07-31 under IBC-REC-F1, ACCEPTED). The origin taxonomy above is NOT altered — it is sound. Only the inference in the sentence immediately above failed.**
>
> **The claim *"the review is reproducible by anyone with repository access"* does NOT hold, and it is falsified by this review's own header: the object of review was *"IBC-1 Sections 1–13 as presented inline in the review request."* IBC-1 has NO repository artifact.**
>
> **The sentence conflates two distinct reproducibility properties:**
>
> | Property | Standing |
> |---|---|
> | **REASONING reproducibility** — *"why did the reviewer reach this conclusion?"* | ✅ **ESTABLISHED, and genuinely so** |
> | **APPLICATION reproducibility** — *"would I reach the same conclusion from the reviewed artifact?"* | ⛔ **NOT established, and not establishable from the repository** |
>
> ***A finding can be fully explicable and wholly uncheckable at the same time. Reasoning reproducibility does not entail application reproducibility.***
>
> *Credit recorded: this section came closer than anything in the corpus — it found that only 6 of 21 findings are artifact-local and drew a real consequence for Section 11. It stopped one step short of asking whether the artifact was available at all.*
>
> **Consequence for future work:** *no remediation or conformance assessment of IBC-1 can be COMPLETED unless there is a governed instance of IBC-1 against which those activities are performed.* **Re-certification (2026-07-31): 19 of these 21 findings retain their governing basis · IBC-C1 RESOLVED · IBC-C3 SUPERSEDED BY EXECUTION · ZERO dissolved.**

⚠️ **But only 6 of 21 findings are artifact-local: IBC-1 read in isolation yields fewer than a third of them.** That is a fact about the *contract*, not about the review. **A boundary contract should ideally be checkable against itself** — its conformance criteria (Section 11) presuppose exactly that — and this one largely is not, **because it states categories where the record states content.** Rule 16 therefore **independently corroborates §1's systemic finding by a different route**: the same defect that makes the contract unconformable also makes it un-self-checkable.

**Consequence for Section 11, recorded:** conformance criteria that can only be evaluated by a reviewer holding fifteen other artifacts are not criteria an implementation team can self-assess. **Resolving the content omissions (Recommendations 4–8) would raise the artifact-local proportion substantially** — which is a benefit of those recommendations not previously stated.

### 8.3 Rule 17 — summary by constitutional category (primary), severity (secondary)

**PRIMARY — by constitutional category:**

| Category | Count |
|---|---|
| **Architecture defects** | **0** |
| **Governance defects** | **12** |
| **Methodology observations** | **1** *(routes out; not IBC-1's to resolve)* |
| **Improvement recommendations** | **6** |
| **Questions** | **1** |

**SECONDARY — by severity, within governance defects only:** 3 Critical · 8 Major · 1 Minor.

**Derived:** presenting category first makes the diagnosis legible in one line — **the architecture is sound and the transmission is defective** — whereas severity-first ("3 Critical, 11 Major") required three addenda to reach the same statement. **§7.1 had already adopted this presentation spontaneously**, which is the evidence on which Rule 17 was adopted.

### 8.4 Rule 15 — assessed and NOT adopted, per the Authority's own standard

The same instruction set that proposed Rule 15 also directed that new dimensions be adopted **only where they repeatedly distinguish classes previously conflated**. **Applying that test to Rule 15 yields n = 0**: across four review cycles and three self-audits, no case exists where a confidence label would have changed a classification, severity, or routing — each apparent case had a structural cause exposed by authority basis (M11), resolving act (S2), category (m3), or external evidence (AFV-F1/F3).

**Held as PMR-6, with the theoretical case recorded fairly, a duplication risk flagged, and a re-assessment trigger named.** *(Rules 14 and 17 were adopted because they add no taxonomy — they report Rule 9's existing dimension. Rule 16 was adopted on one cycle's concrete yield: §8.2 produced a finding about the artifact that no other dimension surfaced. The multi-cycle standard is not yet met for Rule 16 and is recorded as unmet.)*

---

*Traceability: independent Architecture Review of IBC-1 Sections 1–13 as presented in the review request (verified: IBC-1 does not exist as a repository artifact) · reviewed against M6+§14 · M7 · M8 · AD-1 · C4-1 · DAR-1 · Consolidation · MCA · CDR · Authority Disposition+§7 · AFV-1 · AIA-1 · C4-2 · KBI-1 · ERV-1 · CCP-1+§11 · Execution Record · M0 glossary · M2 canon · 21 findings classified (3 Critical · 11 Major · 5 Minor · 2 Suggestions), each with rationale and recommended disposition · every section reviewed against its stated questions · verdict **REVISE** · this review corrects nothing, decides nothing, and issues nothing.*

> **IBC-1 Architecture Review complete. Verdict: REVISE. Three Critical findings block issuance: AD-1 is declared a certified baseline while classified Governance Review Required with six corrections unapplied; the conflict rule is inverted such that an uncorrected artifact would govern over the dispositions correcting it; and AD-1's Q-1 — that communication mechanism is not derivable from the model — is absent from both the open-questions register and the forbidden assumptions, which is the one conversion implementation is certain to make. The contract's architecture is sound and every finding is repairable from material the record already holds.**
