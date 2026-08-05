# PKS — ARB Review Discipline (Reviewer Playbook)

| | |
|---|---|
| **Kind** | **Reviewer operating instructions** — reviewer **conduct** only. **Responsibility (singular, per Review Method objective 11): how a reviewer must behave.** It defines nothing about *what to examine and in what order* (→ `PKS_Knowledge_Contract_Review_Method.md`) and nothing about *what architecturally correct knowledge means* (→ `PKS_Knowledge_Integrity_Model.md`). The standing discipline governing every review of a Strategic DDD or governance artifact in this program. Extracted from `.claude/MEMORY.md` on ARB instruction (2026-07-28): *MEMORY holds durable project state; detailed reviewer procedures belong in a dedicated discipline document.* **MEMORY now carries a pointer to this file, not the rule text.** |
| **Authority** | Issued by the Authority as standing instructions on **reviewer conduct**, in force immediately (Rules 1–7: 2026-07-28 · Rules 8–13: same date, second issuance · Rules 14, 16–18: same date, third issuance). **Scope note (binding):** these instruct the *reviewer*. Binding them on future review **commissions** — making them SDM/EOP requirements — is a methodology change and follows the PMR → MCA → CDR path. |
| **Status** | **BASELINE v1.0 — FROZEN (ARB closure ruling, 2026-07-28) · AMENDMENT 1 applied 2026-07-30 · **AMENDMENT 2** (canonical governance-state vocabulary: Axis A control admission ⟂ Axis B artifact lifecycle) · **AMENDMENT 3** (contract admission rule GOVERNED at n=4; **review emphasis PROMOTED TO GOVERNED on cross-context evidence, same date**) — issued by the Authority 2026-07-30** (finding vocabulary relocated to the Method by Authority disposition of FW-1 — see §Amendment 1; the freeze was not breached, since it constrains reviewer accretion, not Authority acts, and the amendment removes content rather than adding it).** Rules 1–14, 17, 18 adopted · **Rule 16 PROVISIONALLY ADOPTED (operational trial)** · **Rule 15 DECLINED** (held as PMR-6 with a re-assessment trigger). **Freeze semantics:** the playbook is used as-is across independent reviews while operational evidence accumulates; **it reopens ONLY on the admission filter's own trigger — a real defect escaping that the current rules cannot explain** — never for speculative Rules 19+. Two changes are pre-authorized without reopening: **Rule 16's promotion or demotion** when its recorded conditions are met, and **PMR-6's re-assessment** if its trigger fires. Both are recorded as dated notes, not redesigns. |
| **Review Commission (first cycle)** | **CLOSED (ARB, 2026-07-28).** Review verdict: *accepted as the governing review with refinements incorporated.* The chain that produced this baseline: initial review → self-audit (Rules 1–7) → constitutional refinement (8–13) → reporting refinement (14, 16, 17) → Rule 15 assessed and declined on evidence → Rule 18 closing the authority chain → extraction to this playbook → meta-review of the review system → **stabilization under the admission filter.** The ARB's stated meaning of closure: **the review instrument itself has been validated.** Remaining constitutional work (AFV-F4 · the M8 finding acceptances · Packages A/B → C → D · promotion) is **project execution, outside the review commission**, and proceeds through the established governance workflow. |
| **The admission filter (ARB, binding on every layer's growth)** | **STRENGTHENED on ARB instruction 2026-07-30 — canonical statement:** ***a quality or rule becomes part of the governed model only after REPEATED operational evidence demonstrates that its absence allowed defects to escape existing controls.*** The screening question is unchanged — *"did a real defect escape because this rule was missing?"* — but **a single instance now admits a candidate, not a rule.** Rationale (the ARB's): the filter should rest on **empirical validation, not one observation.** See §Admission ladder for the graded statuses and for the honest retroactive check. |
| **Three-model separation (2026-07-30)** | **Discipline** (this file — conduct) · **[Method](./PKS_Knowledge_Contract_Review_Method.md)** (workflow: eleven objectives) · **[Integrity Model](./PKS_Knowledge_Integrity_Model.md)** (criteria: qualities A–F). Separated per DDD's one-responsibility-per-model principle, on the warrant that **applying the Method's objective 11 revealed a better decomposition of concerns this document was holding together** — a refactoring from improved understanding, **not** a finding that the framework was invalid (see Part VI, and the acyclic dependency rule below). **Nothing is restated across the three.** |
| **Lifecycle note (corrected 2026-07-30)** | Rules 1–18 are **Authority-issued** (the rule text is the Authority's, across three issuances). **This document's framing, evidence notes, and structure are assistant-authored.** Reviewer-conduct rules are in force for the reviewing assistant on issuance; **binding them on commissions requires adoption** and follows the PMR → MCA → CDR path. *(The earlier framing did not separate issuance from adoption.)* |
| **Four layers, not three** *(ARB 2026-07-30)* | Layer 4 = **Governance Process** — *how reviews become authoritative decisions and changes* — and it is **pre-existing**: SDM v1 · EOP v1 · the CDR · Process Under Configuration Control · CCP-1 §12.6's chain. **A pointer, never a new artifact.** |
| **Framework stabilization (ARB, 2026-07-30)** | **All four layers are now to be STABILIZED and validated across several independent review cycles before any further expansion.** The admission filter governs each layer's growth; **the correct outcome of most future filter tests is "no addition needed."** |
| **Placement** | `docs/implementation/`, beside the PMR register. |

---

## Part I — Scope discipline (Rules 1–4, 7)

**Rule 1.** Distinguish, in every review: **architectural constraint** · **architectural property** · **tactical implementation mechanism**.

**Rule 2.** Recommend architectural constraints freely.

**Rule 3.** Do **not** prescribe implementation mechanisms unless the certified architecture already mandates them.

**Rule 4.** Express implementation expectations as **conformance requirements** (*"shall demonstrate"*), never as **realization techniques** (*"implement using ArchUnit"*, *"use an ACL"*, *"separate schemas"*).

**Rule 7.** Before issuing each recommendation, verify it remains within Strategic DDD and implementation-governance scope, and does not drift into Tactical DDD or implementation architecture unless that transition has been explicitly authorized.

## Part II — Classification discipline (Rules 5, 8, 9, 11, 12)

**Rule 5.** When mapping identifiers (DR-x · IC-x · AP-x · …), explicitly classify the mapping as **authoritative / traceable / inferred / proposed**. *Evidence: caught IBC-M11 — a Major finding asserted against a requirement the reviewer had supplied. Citing a governed identifier does not make the requirement governed.*

**Rule 8** *(amended 2026-07-30 — Amendment 1)*. Every finding shall declare its **authority basis**, selecting **exactly one** permitted value from the **Method's §Finding vocabulary** *(the enumeration was relocated there by Authority disposition of FW-1; it is no longer defined in this document)*. **A finding's constitutional weight derives from this classification, not from its severity label** — this reasoning constraint is reviewer conduct and remains here.

**Rule 9** *(amended 2026-07-30 — Amendment 1)*. Every review output shall declare its **constitutional category**, selecting **exactly one** permitted value from the **Method's §Finding vocabulary** *(enumeration relocated; see Amendment 1)*. **Severity and constitutional category are orthogonal; never merge them into a single severity list** — reviewer conduct, retained here. *Evidence: re-classifying the IBC-1 review under Rule 9 revealed 0 architecture defects where the severity list read as "substantially wrong architecture"; and it split IBC-m3, whose merged form made a routing error look like a drafting error.*

**Rule 11.** Before raising any finding, ask: **"what constitutional act would resolve this?"** — editorial correction · Authority disposition · architecture amendment · methodology amendment · no action required. **If the required act is unclear, classify the observation as a QUESTION, not a finding.** *Evidence: reclassified IBC-S2.*

**Rule 12.** Every recommendation shall state whether it is **mandatory for conformance · strengthening · informative · a future-methodology candidate** — preventing recommendations from acquiring accidental normative force.

## Part III — Source-of-truth discipline (Rules 6, 10)

**Rule 6.** Improvements to inherited normative wording belong in the **authoritative source** and propagate downstream. Never modify an assembly artifact independently. *Evidence: M8 Finding 1 / held item H-1; the underlying rule is PMR-5 (three instances).*

**Rule 10.** Before proposing new wording, locate it: **(a)** authoritative source → requires source amendment · **(b)** assembly artifact → requires propagation from the source · **(c)** explanatory prose → only (c) may be rewritten directly. *(Rule 10 is the reviewer-conduct form of PMR-5's three-level model; PMR-5 itself remains a methodology candidate.)*

## Part IV — Reporting discipline (Rules 13, 14, 17, 18)

**Rule 13.** When a review changes one of its own findings during self-audit, record: **original classification · revised classification · the governing rule responsible · the reasoning.** Review history becomes evidence for methodology refinement, never hidden editorial evolution.

**Rule 14.** Every review shall declare its **review scope** before issuing findings — architecture · governance · methodology · implementation · documentation — and **verdicts apply only within declared scope.** *Evidence: the unscoped IBC-1 "REVISE" invited the reading "architecturally wrong"; scoped, it reads Architecture PASS · Governance REVISE.*

**Rule 17.** Review summaries shall report results **by constitutional category first, severity second.** *Evidence: the category-first presentation was adopted spontaneously (IBC-1 review §7.1) before the rule existed.*

**Rule 18 (ARB, third issuance).** **Every declared review scope shall identify its governing artifacts** — the authoritative artifacts against which that scope is evaluated — **and no finding within that scope shall exceed the authority of those artifacts.** This completes the chain: **scope → authority → evidence → finding → constitutional act.** *Example form:*

> *Architecture: PASS — governing artifacts: AD-1 · M8 · the M6 dispositions.*
> *Governance: REVISE — governing artifacts: DAR-1 · Consolidation · CDR · the verification records.*

*(Derivation note: Rule 18 makes explicit what Rule 5 + Rule 8 already imply per finding, lifted to scope level — its admission evidence is that IBC-1's §8.1 scoped verdicts carried no evidential anchor, leaving the scope clear but its authority implicit.)*

## Part V — Provisional and declined rules

**Rule 16 — PROVISIONALLY ADOPTED (operational trial; ARB refinement 2026-07-28; *amended 2026-07-30 — Amendment 1*).** Every finding shall declare its **evidence origin**, selecting **exactly one** permitted value from the **Method's §Finding vocabulary** *(the three values were relocated there; the rule's **provisional status and its promotion/demotion conditions stay here**, because trial status is growth governance, not output shape)*.
**Trial status, stated precisely:** *usable and encouraged; not yet part of the permanent discipline.* One cycle of concrete yield exists (the IBC-1 review's 6-of-21 artifact-local result, which corroborated the systemic finding by an independent route and exposed that the contract is not self-checkable). **The multi-cycle standard is not met. Promotion condition: Rule 16 produces information no other dimension surfaces in at least one further, independent review cycle. Demotion condition: two further cycles in which its classifications are derivable from Rules 8/9/11 alone.**

**Rule 15 — DECLINED (per-finding confidence).** Assessed against the Authority's own evidential standard: **n = 0** — no case across four review cycles and three self-audits where a confidence label would have changed a classification, severity, or routing. Held as **PMR-6** with the theoretical case recorded, a duplication risk flagged (the program already grades confidence for *domain* claims via M0's rubric and MCR-5), and a re-assessment trigger named: *the first cycle in which a reviewer cannot express a real uncertainty using authority basis, category, resolving act, or evidence origin.*

---

## Part VI — EXTRACTED: the review method and the quality model now live in sibling documents

**Extracted 2026-07-30 under Authority instruction, applying Review Method **objective 11 (Knowledge Cohesion)** to this framework itself.**

**The warrant is self-application, and it is the strongest available:** this document had accumulated **three responsibilities** — reviewer conduct (Parts I–V) · a review method (the former Part VI's ten objectives) · and, had the integrity criteria been added here, evaluation criteria as well.

**The warrant, stated as a refactoring** *(wording refined on ARB instruction 2026-07-30 — the refinement is recorded because the earlier wording claimed more than the evidence supports)*: **applying objective 11 reveals that the framework combined multiple architectural responsibilities. Separating those responsibilities improves cohesion and aligns the framework with its own design principles.**

**What the earlier wording got wrong, recorded because the distinction is architectural and not merely verbal.** It read: *"retaining all three would have meant the review instrument violating the rule it had just adopted."* That **presupposes the framework was already invalid** — and it was not: it functioned, and it produced every finding in this record while holding all three concerns. What actually happened is that **a new objective exposed a better decomposition.**

**The governing principle, adopted from the ARB:** ***DDD prefers discovering a better model over declaring the previous model incorrect.*** The separation is therefore a **refactoring justified by improved understanding**, not a remediation of a defect.

**The consequence, which is not cosmetic — the two acts carry different evidentiary bars, and conflating them would be a category error:**

| Act | Bar it must clear |
|---|---|
| **Admitting a new quality or rule** | **Escaped-defect evidence** — the absence of the control let a real defect through (see the admission filter) |
| **Refactoring for cohesion** | **Demonstrated multiple responsibilities** — no escaped defect is required, and none is claimed here |

**Recorded consequence:** because the separation claims no escaped defect, it is **not** an instance of the admission filter firing, and it must not be cited as one.

| Concern | Canonical home | Question it answers |
|---|---|---|
| **Reviewer conduct** | **This document** (Parts I–V, Rules 1–18) | *How must a reviewer behave?* |
| **Review method** | [`PKS_Knowledge_Contract_Review_Method.md`](./PKS_Knowledge_Contract_Review_Method.md) | *What do I examine, and in what order?* |
| **Integrity criteria** | [`PKS_Knowledge_Integrity_Model.md`](./PKS_Knowledge_Integrity_Model.md) | *What is architecturally correct knowledge?* (qualities A–F) |
| **Governance process** | **Pre-existing** — SDM v1 · EOP v1 · CDR · Configuration Control · CCP-1 §12.6 | *How do reviews become authoritative decisions?* |

**Rules 1–18 remain in force and operate *inside* the method's eleven objectives.** The method references the qualities; neither restates the other. **Expected rates of change differ — qualities are stable, methods and disciplines evolve faster — which is the practical reason for separating them.**

### The dependency rule *(ARB refinement, 2026-07-30 — made explicit so future maintainers inherit it)*

**The intended dependency direction is acyclic, and the direction is the point:**

```
        Knowledge Integrity Model        (depends on NOTHING)
                  ▲
                  │ referenced by
        Knowledge Contract Review Method
                  ▲
                  │ executed under
        ARB Review Discipline
```

| Model | May depend on | Must not depend on |
|---|---|---|
| **Integrity Model** | *nothing* | the Method · this Discipline |
| **Review Method** | the Integrity Model | this Discipline |
| **Review Discipline** | the Method (and, transitively, the Integrity Model) | — |

**Why the property is worth stating rather than assuming: it tells a maintainer what may change without consulting what.** The Integrity Model can be revised without reading either sibling; the Discipline cannot be revised without the Method. **A cycle would mean no layer could be revised independently — which would defeat the entire purpose of the separation** (each layer, one reason to change).

**Dependency ≠ citation.** A **dependency** exists where a document's content **cannot be applied** without the other. A **back-reference** — *"quality A is what objectives 1–2 already examine"* — is **coverage evidence, not a dependency**: quality A is fully defined without it. **Back-references may point in any direction and do not create cycles; only normative dependencies do.** Every upward reference in the sibling documents is marked accordingly.

**Verification was performed on the text rather than assumed, and it found one genuine violation — FW-1: the Method's required-output section took its finding vocabulary from Rules 8/9/16 of this document**, a *normative* dependency and therefore a back-edge which, with `Discipline → Method`, closed a cycle.

**FW-1 is DISPOSED — resolution (a) ACCEPTED (Authority, 2026-07-30): the finding vocabulary is output shape and moves to the Method.** Applied as **Amendment 1** below. **The graph is now acyclic and verified so.**

**Recorded because the sequence is the point, not the outcome:** the reviewer **found** the violation, **recorded** it, and **did not fix it** — because the fix amends this FROZEN baseline, and *a reviewer relocating rules between governance layers on his own reading is exactly what the freeze exists to prevent.* **The Authority disposed; the reviewer then executed.** That is `review finding → Authority disposition → bounded execution` — CCP-1 §12.6's chain, applied to the review framework itself.

*(The former Part VI's content is preserved in full at the method document, including its double warrant: Authority issuance plus the admission filter firing independently on knowledge lifetime and cascade search. Nothing was lost in the extraction; the freeze note is preserved there and below.)*

## Admission ladder *(ARB refinement, 2026-07-30 — derived from this program's existing practice, not invented)*

> **Concern note (2026-07-30).** This section, §Freeze status, §Amendment 1, §Change discipline, and Rule 16's trial conditions are **Framework Growth Governance** — layer 4 of the canonical map, recognized by the ARB as *"a distinct architectural concern: not review behavior, review workflow, or knowledge quality, but governance of the framework itself."* **They are HOSTED here, not owned here** — this document is simply the layer whose growth they currently govern. **No fourth document was created, and creating one on first recognition would violate the very filter stated below** (promotion requires repeated evidence, not insight). Inventory, promotion trigger, and the open question **Q-GG-1** — *is growth governance distinct, or Configuration Control specialized to the framework?* — live in the Integrity Model's canonical map.

The strengthened filter needs graded statuses, because *"repeated evidence"* is a threshold and something must hold a control **before** the threshold is crossed. **The ladder is not a new stage: every rung is already instantiated in this program's record, which is why it is recorded as derived rather than proposed.**

| Rung | Bar | Precedent already in the record |
|---|---|---|
| **Candidate** | No escaped defect yet, or a hypothesis | **PMR-6 declined at n=0** · PMR-1..PMR-5 in the register, none adopted |
| **Provisional** | **One** escaped defect, named | **Rule 16 PROVISIONALLY ADOPTED** · the methodology **PROVISIONALLY CERTIFIED** · **MCR-5's statement adopted, instrument deferred** |
| **Governed** | **Repeated** escaped-defect evidence across independent occasions | Rules 1–14/17/18 · qualities C, D, F |
| **Declined** | Insufficient evidence, with the trigger recorded | **Rule 15 DECLINED at n=1** — *the ARB itself applied this bar before it was written down* |

**The boundary this preserves, stated so the stronger filter cannot be misread as an excuse for inaction:** a **single** defect of constitutional severity — an artifact performing an Authority act, an anonymity breach — enters as **Provisional immediately and operates from that moment.** The repetition requirement governs **promotion to Governed**, never whether a control operates at all. **Waiting for a second constitutional breach in order to justify preventing the first would be an absurd reading, and it is not this one.**

**Retroactive check against the strengthened bar, reported honestly rather than assumed to pass:**

| Item | Instances | Status under the strengthened filter |
|---|---|---|
| Quality **C** (four knowledge acts) | 4 — M8 Assembly · ADR §3.1.2 · AFV-F1 · KC-1/KC-2 | **Governed** ✅ |
| Quality **D** (five temporal classes) | 5 — all classes instantiated | **Governed** ✅ |
| Quality **F** (context integrity) | 4 — KC-9 · U-2 · the guarded homonyms · OQ-PKS-11 | **Governed** ✅ |
| Quality **E** (traceability/supersession) | **2 — KC-4 and §14's undeclared second status account** | **Governed, but the weakest in the model — flagged.** Two instances is *repeated* by the letter of the filter; it is the one quality a third counter-instance could still unsettle |
| Qualities **A, B** | Recorded as **restatements of existing coverage**, not admissions | **Not subject to the filter** — nothing was added |
| **Rule 16** | 1 | **Provisional** — unchanged, correctly |

**Recorded because the strengthened filter would otherwise be applied only forward:** the check above is the filter turned on the model that adopted it. **Quality E is disclosed as the thinnest rung rather than presented as equal to C/D/F** — the alternative would be exactly the confidence inflation R-M6-6 exists to prevent.

**Freeze status: Baseline v1.0 — FROZEN, and the freeze was NOT breached by Amendment 1.** v1.0's freeze constrains **speculative accretion by the reviewer**, not **acts of the Authority**; supersession is forward-only. Amendment 1 is an **Authority disposition executed by the reviewer**, which is the permitted path — and it **removes** content rather than adding any, so the accretion the freeze targets did not occur.

## Amendment 1 — finding vocabulary relocated to the Method *(Authority disposition of FW-1, 2026-07-30)*

**Disposition:** resolution (a) accepted — *"move finding vocabulary to the Method."* **Effect:** the three enumerations below ceased to be defined in this document; Rules 8, 9 and 16 now **reference** the Method's §Finding vocabulary. **Each rule was split at its own seam** — enumerations are output shape and moved; reasoning constraints and trial status are conduct and stayed.

**Superseded text, retained verbatim as history and NOT authoritative** *(the M6 §14 amendment pattern — forward-only, original stands as record, and deliberately not left as a second live copy, which is the KC-4 defect)*:

> **Rule 8.** …*exactly one of: baseline violation · accepted-architecture violation · methodology violation · derived architectural constraint · reviewer recommendation.*
> **Rule 9.** …*exactly one of: architecture defect · governance defect · methodology observation · improvement recommendation.*
> **Rule 16.** …*exactly one of: artifact-local · cross-artifact · reviewer knowledge* (with their parenthetical definitions).

**Rules 5, 11, 12 and 14 also enumerate values and were NOT touched** — the Method's output requirements never cited them, so they create no back-edge, and moving them would be the reviewer-initiated layer redesign this very finding refused to perform. **The disposition's scope was held exactly.**

**Rule 16's evidence-origin dimension remains PROVISIONAL at n=1.** Relocation changed *where the vocabulary is defined*, not *how much evidence supports it*.

---

## Amendment 2 — canonical GOVERNANCE-STATE vocabulary *(Authority instruction, 2026-07-30: standardize the vocabulary rather than add a document)*

**Issued because the states below now recur across Rule 15, Rule 16, the methodology certification, the AFR recognition, and framework evolution — and wording drift across five subjects is how a homonym forms.** Hosted here as **layer-4 Framework Growth Governance**; **no new document was created.**

### The reconciliation that had to happen first

**The Authority's proposed table lists `Proposed · Provisionally Recognized · Governed · Withdrawn · Deferred · Superseded` as one flat set. Adopted verbatim it would collide with vocabulary already in force** — the admission ladder's `Candidate · Provisional · Governed · Declined`, and the framework documents' `Authored · Proposed · Adopted · Binding`. **`Proposed` in particular already means something else, and `Provisional` vs `Provisionally Recognized` would read as two states.**

**Resolved by separating TWO AXES with different subjects, rather than merging them into one list.** *Merging would have produced exactly the guarded-homonym defect quality F names — the same token authoritative in two contexts with different meanings.*

### Axis A — CONTROL ADMISSION *(subject: a rule · quality · objective · method · review contract)*

| State | Meaning | Subject-appropriate verb |
|---|---|---|
| **Candidate** | No qualifying evidence yet, or a hypothesis | *proposed / registered* |
| **Provisional** | **Valid for the case that produced it; evidence insufficient to generalize** | **methodology → *certified* · rule/quality/objective → *adopted* · review contract → *recognized*** |
| **Governed** | Recognized for general application; repeated evidence | as above |
| **Declined** | **Never admitted** — insufficient evidence at the admission test | — |
| **Withdrawn** | **Admitted, then removed by contrary evidence** | — |

**`Declined` and `Withdrawn` are NOT synonyms, and current usage already respects the distinction** *(Rule 15 **declined** at n=1, never admitted · AF-6 **withdrawn** on verification, after being raised)*. **Codified so the distinction survives.**

**The verb varies by subject and that is LAWFUL, not drift — this is the pass's main finding.** *Provisionally **certified*** (the methodology, 35 usages) · *provisionally **adopted*** (Rule 16) · *provisionally **recognized*** (the AFR) **are one state with correct subject–verb agreement.** A methodology is certified, a rule is adopted, a review contract is recognized. **They must NOT be flattened to one verb; they must not be read as three states.**

### Axis B — ARTIFACT LIFECYCLE *(subject: a document)*

| State | Meaning |
|---|---|
| **Authored** | Written; no governance status |
| **Issued** | Its substance issued by an authority |
| **Proposed** | Offered for adoption; **no adoption record exists** |
| **Adopted** | An adoption act is recorded |
| **Binding** | In force on its stated subjects |

**Axis A and Axis B are ORTHOGONAL.** A **Provisional** control may live in a **Proposed** document *(the AFR does exactly this)*. **Reading one axis's state as the other's is the error this amendment prevents.**

### The promotion chain, with owners *(Authority, 2026-07-30 — codifies the sequence the program has enforced throughout; no new mechanism)*

**Review Complete** *(reviewer)* → **Findings Dispositioned** *(Authority)* → **Remediation Verified** *(reviewer / verification commission)* → **Promotion Ready** *(**a state DERIVED BY VERIFICATION, not a decision by anyone**)* → **Authority Promotion Decision** *(the act)* → **Promoted Baseline** *(the governed state)*.

**Each step has a different owner and a different authority; no step may absorb the one after it.** **The fourth is the one most often collapsed: *promotion-ready* is derived, not decided** — hence ***verified-ready ≠ promoted***, the fifth member of the family that already holds *frozen ≠ promoted* · *review outcome ≠ adoption* · *recommendation ≠ issuance ≠ adoption* · *closing a review ≠ adopting the artifact*. **Every member was added because the program conflated it once.**

### Cross-cutting dispositions *(subject: any item awaiting a decision)*

**Deferred** — intentionally unresolved pending further evidence, **with the trigger named** · **Superseded** — replaced by a later governed decision, **forward-only; the original stands as history.**

### Coherence verification performed before issuing this amendment *(not a review — no new defects sought)*

| Question | Result |
|---|---|
| Is *"Provisionally …"* used with one consistent meaning? | ✅ **Yes — one state, three lawful subject-verbs.** No drift found |
| Are recognition / adoption / governance distinguished? | ✅ Yes — and Axis A/B separation now makes it structural rather than incidental |
| Are review contracts tied to the right artifact kinds? | ✅ Yes — knowledge artifact → Knowledge Contract Review; decision record → Authority Fidelity Review, consistently in all three review records |
| Are commission lifecycles described consistently? | ✅ Yes — *review → recognition → disposition → close-out* held in both commissions |
| Are authority boundaries (review · adoption · execution) uniform? | ✅ Yes — every close-out and disposition states them, and none merged |
| **Conceptual conflicts found** | **NONE.** One editorial gap: this document uses `Status: BASELINE v1.0 — FROZEN` where its two siblings carry an explicit `Lifecycle status` field. **Editorial, recorded, not corrected — it is not a conflict** |

**Conclusion of the pass: the concepts introduced across the recent commissions are used consistently. No conceptual conflict exists. Only one editorial inconsistency was found, and it is recorded rather than fixed** — *a stabilization pass that starts editing is no longer a verification.*

## Amendment 3 — the CONTRACT ADMISSION RULE and the emphasis/contract distinction *(Authority instruction, 2026-07-30)*

**Issued to generalize a conclusion the program reached four times. Layer-4 Framework Growth Governance; no new document.** *Recorded honestly: this amendment adds no mechanism — it names a test already applied four times and gives the lightweight alternative a name.*

### The rule *(GOVERNED — n = 4)*

> ***Artifact kinds may define review EMPHASES, but review CONTRACTS are introduced only when operational evidence demonstrates a review capability that existing contracts cannot provide.***

**The test is not *"is this artifact different?"* and not *"was this review useful?"* It is:** ***could an existing contract have FOUND these defects?***

**Evidence — and it is stronger than repetition, per the ARB's characterization: these are FOUR FALSIFICATION ATTEMPTS with BOTH positive and negative outcomes, not four confirmations.** *Four identical confirmations would evidence a habit; four attempts of which three declined and one recognized evidence a **test that can fail** — which is what a governance criterion must be.*

| Occasion | Evidence | Outcome |
|---|---|---|
| *Governance Integrity Review* | n = 0 — no artifact of the kind reviewed | **DECLINED** |
| *Methodology Review* | n = 0 — **and MCA → CDR under Configuration Control already does it** | **DECLINED** (unnecessary, not merely unevidenced) |
| **Authority Fidelity Review** | **One named capability** — line-by-line comparison against the decision register's *offered* and *chosen* options; **AF-3/AF-4/AF-5 reachable by nothing else** | **PROVISIONALLY RECOGNIZED** |
| *Strategic Model Publication Review* | **Eleven real findings, and ZERO requiring a capability the KCR lacks** — every one mapped to objectives 1, 4, 5, 7, 10, 11 | **DECLINED** |

**The fourth row is the rule's sharpest demonstration: a review that produced eleven genuine findings was declined a contract, because usefulness is not the test.**

**The separation, in the ARB's formulation, adopted as the rule's operative statement:**

> ***Usefulness is evidence for an EMPHASIS. Unique capability is evidence for a CONTRACT.***

*This prevents the common governance failure it names: **many review ideas are useful; very few justify becoming governance constructs.*** 

### The two governing aphorisms, adopted as stated

> ***A distinct lens is not a distinct capability.***

**Its DDD basis, adopted from the Authority:** different bounded contexts justify different models because they solve **different problems**; different **artifact kinds** do not automatically justify different **governance mechanisms**. *A different perspective on an artifact is insufficient evidence for a new contract.*

> ***A named emphasis costs nothing; a named contract costs a governance construct.***

**This is governance economy: review contracts are scarce constructs; review emphases are lightweight applications of existing ones.** The distinction is what prevents framework inflation.

**Corollary, recorded because it is the error this rule catches:** ***"no existing contract asks my question" is a WEAKER claim than "no existing contract can find my defects."*** The declined Publication Review contract rested on the first and was mistaken for the second.

### REVIEW EMPHASIS — the lightweight construct *(PROVISIONAL — n = 1)*

**A review emphasis is a named weighting of an existing contract's objectives for a given artifact kind. It creates no contract, no document, and no new objective.**

| Artifact kind | Contract | Emphasis |
|---|---|---|
| Architecture knowledge | **KCR** | full objective set |
| **Publication artifact** | **KCR** | objectives **7** (currency) · **1** (marker discipline) · **11** (publication responsibility) |
| Decision record | **AFR** *(provisionally recognized)* | its eight objectives |
| **Retrospective** | **KCR** | objectives **11** (responsibility) · **1** (epistemic class) · **5** (classification) · **4** (governance boundary) |

**Status: the ADMISSION RULE is GOVERNED at n = 4. The EMPHASIS CONSTRUCT is now GOVERNED — promoted 2026-07-30 by Authority act on cross-context evidence** *(it was PROVISIONAL at n = 1 for one day; the promotion criterion below was met the same day it was written)*.

**Promotion evidence, checked limb by limb against the criterion as tightened:**

| Limb of the criterion | Evidence |
|---|---|
| *"at least one additional emphasis"* | ✅ **Retrospective emphasis** (RET-1 review), added to the publication emphasis (M8 review) |
| *"applied to a **different artifact category or review situation**"* | ✅ **Publication artifact → retrospective.** *Not a second publication emphasis — which is precisely what the tightening was written to exclude* |
| *"**demonstrably improves** review effectiveness"* | ✅ **The retrospective emphasis directed the review to §9 (patterns) and §11 (promotion inputs) — which produced BOTH of that review's Major findings.** An unweighted pass would have reached them eventually; the emphasis is what put them first |
| *"**without requiring a new review capability**"* | ✅ Complete objective mapping, no new objective: responsibility → 11 · observation/interpretation → 1 · traceability → quality E · DDD integrity → 2 · governance boundary → 4 · pattern responsibility → 1 + 5 · narrative → 1 · reader effectiveness → 10 |

**Demotion condition retained and unchanged: an emphasis that changes no reviewer behaviour is not an emphasis.**

**Recorded because the sequence is the point: the analytical conclusion (*the criterion is satisfied*) and the governance act (*therefore promote*) were kept separate — the review recommended, the Authority promoted.** *A review that promoted on its own finding would have committed the RET-2 defect it had just disposed.*

**Governed emphases:**

**Promotion criterion — CROSS-CONTEXT, not merely n = 2** *(tightened on ARB instruction 2026-07-30)*:

> ***Promotion requires at least one additional emphasis, applied to a different artifact category or review situation, that demonstrably improves review effectiveness WITHOUT requiring a new review capability.***

**Why the tightening was necessary, and it is this program's own discipline turned on a governance construct: a bare n = 2 could be satisfied by TWO PUBLICATION emphases** — which would evidence that the idea works *again for publication artifacts*, not that **review emphasis** is a **reusable abstraction**. **That is precisely the R-M6-6 / T-2 problem in miniature: repetition within one lineage is correlated agreement, not independent confirmation.** *The construct must prove generality across contexts before it is generalized.*

**Demotion condition, retained: emphases that turn out to be decoration rather than direction** — a named emphasis that changes no reviewer behaviour is not an emphasis.

### The four separated concerns *(Authority's hierarchy, adopted)*

| Layer | What it determines | Instances |
|---|---|---|
| **Artifact kind** | **HOW we read** | Knowledge artifact · Decision record · Publication artifact |
| **Review emphasis** | **WHAT WE EMPHASIZE** | Knowledge integrity · Authority fidelity · Publication fidelity *(provisional construct)* |
| **Review contract** | **WHAT WE VERIFY** | KCR · AFR |
| **Review capability** | **WHETHER A NEW CONTRACT IS JUSTIFIED** | The admission test above |

**One precision on the ordering, because the same care was owed to the framework's own dependency rule: the sequence above is a READING ORDER, not a dependency chain.** An **emphasis is *of* a contract** — it weights that contract's objectives — so **the dependency runs emphasis → contract**, upward against the reading order. *Listing emphasis above contract shows how a reviewer proceeds; it must not be read as emphasis governing contract.*

***Artifact kinds are not one-to-one with contracts, and that asymmetry is the point*** — it is what lets the framework become more expressive without becoming more complicated.

## Change discipline for this document

Additions require the **strengthened** admission filter satisfied — **repeated** operational evidence that the control's absence let defects escape (§Admission ladder; one instance admits a candidate, or a provisional control where severity is constitutional) — or an Authority issuance recorded verbatim-in-substance. Additions are appends with a dated note; supersession is forward-only. **This document governs reviewer conduct only** — it is not SDM/EOP content, and nothing in it binds a commission.

*Traceability: Rules 1–7, 8–13, 14–18 issued by the Authority (ARB / Chief Architect), 2026-07-28, across three issuances · Rule 16's provisional status and Rule 18 per the ARB Chief's APPROVE WITH REFINEMENTS review · Rule 15 declined per the Authority's own multi-cycle evidence standard, held as PMR-6 · extracted from `.claude/MEMORY.md` per the same review's instruction that MEMORY hold durable state, not procedures · **Amendment 1 (2026-07-30): Rules 8/9/16's enumerations relocated to the Method per the Authority's disposition of FW-1 (resolution a), each rule split at the output-shape/conduct seam; superseded text retained as history; Rules 5/11/12/14 untouched; graph verified acyclic** · admission filter adopted as this document's own growth rule, **strengthened to require repeated operational evidence on ARB instruction 2026-07-30**, with the graded ladder recorded as *derived from existing program practice* (Rule 15 declined at n=1 · Rule 16 provisional at n=1 · PMR-6 declined at n=0 · MCR-5 statement-adopted/instrument-deferred) rather than proposed as a new stage · **the three-way separation's warrant reworded 2026-07-30 from "this document failed objective 11" to "objective 11 revealed a better decomposition"** — a refactoring from improved understanding, on the ARB's principle that *DDD prefers discovering a better model over declaring the previous model incorrect* · **acyclic dependency rule made explicit** (`Integrity ← Method ← Discipline`), with the dependency/back-reference distinction and the one recorded violation **FW-1** (this document's Rules 8/9/16 supplying the Method's finding vocabulary) **disclosed and left to the Authority, since its fix would amend this FROZEN baseline.***

---

## Amendment 4 — **RECOGNITION ≠ AGREEMENT ≠ ADOPTION** *(2026-07-31)*

**The Authority's formulation, adopted as the register's entry condition:**

| Act | Who | Effect |
|---|---|---|
| **RECOGNITION** | A review may recognize a useful idea | **The idea is on the record. Nothing changes** |
| **AGREEMENT** | The Authority may agree it has merit | **Merit is established. Still nothing changes** |
| **ADOPTION** | Only the governing path may adopt it | **Practice changes** |

> ### **If it changes future practice, it is NOT ADOPTED until it traverses the appropriate governance path.**

**Evidence — GOVERNED on repeated, cross-context application:**

| n | Case | The gap it closed |
|---|---|---|
| 1 | **Review recommendations never became architecture** | across all twelve reviews |
| 2 | **RET-1's body stripped of review-derived governance reasoning** | recognition had begun to write itself into an artifact |
| 3 | **FW-1's finding vocabulary relocated, not re-authored** | a repair, not an adoption |
| 4 | **PMR-7 gained no standing from ERV-R1's acceptance** | ***acceptance of the evidence is not acceptance of the lesson*** |
| 5 | **PMR-8 — the Authority AGREED with an assessment-ordering rule and it was routed, not adopted** | ***an endorsement is not an adoption*** |

**The distinction now applies uniformly across architecture · methodology · archival practice · execution governance.**

***Its force is that it binds the Authority too. A framework in which the deciding party can adopt by agreeing has no entry gate — it has a preference.***

### The full admission chain *(Authority, 2026-07-31 — expands the three stages above)*

```text
Recognition
    ↓
Agreement
    ↓
Methodological admission (if applicable)
    ↓
Assessment
    ↓
Authority disposition
    ↓
Adoption
```

**The crucial property, in the Authority's own formulation:**

> ### **AGREEMENT DOES NOT POSSESS LEGISLATIVE FORCE.**

**True regardless of source — a reviewer, an architect, or the Authority. *Otherwise governance would collapse into preference.***

***PMR-8 is the load-bearing evidence precisely because immediate adoption was the easiest available path: the gate is only demonstrable when passing through it costs the party who could have skipped it.***
