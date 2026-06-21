# Round 38C-03 — ARB Ruling: OQ-38B05-07 ADR ↔ EC Relationship

**Program:** NRNA DDD Trustworthiness Research Program
**Round:** 38C-03 — Formal ARB Ruling
**Status:** SUBMITTED FOR ARB RULING
**Purpose:** Issue the formal ARB ruling for OQ-38B05-07 based on the evaluation and review record
**Date:** 2026-06-18

**Inputs considered:** 38C-02 Evaluation (ACCEPTED), 38C-02 ARB Review (APPROVED), ADR-1 through ADR-7, 38A-01 through 38A-06, 38B-01 through 38B-08, 38C Authorization Decision.

**Governing invariant:** 38C01-INV-01 — all outputs remain hypotheses until ARB acceptance.

**No technical architecture. No bounded contexts. No aggregates. No domain events. No services. No APIs. Constitutional governance ruling only.**

---

## Part A — Inputs Considered

### A.1 — Evaluation Record

| Document | Status |
|----------|--------|
| 38C-02 Evaluation (Round38C-02_OQ-38B05-07_ADR_EC_Relationship_Evaluation.md) | ACCEPTED (Outcome B) |
| 38C-02 ARB Review (Round38C-02-ARB-Review.md) | APPROVED |

### A.2 — Prior Program Record Drawn Upon

**From ADR authoring (Round 37):**
- ADR-2: Independence forms per D43 function — per-function independence selected; forms are architecturally specified but relationship to EC tier provisions was unresolved
- ADR3-INV-01: Evidence strata independence — binding invariant; constitutional grounding in AC-31 minimum (Tier 3)
- ADR5-INV-01: R-8 terminality — binding invariant; grounded in Tier 3 challenge rights
- ADR6-INV-01: CO-5 void rule — binding invariant; grounded in certification mandate
- ADR7-INV-01: Succession pre-designation — binding invariant; grounded in constitutional continuity requirement
- ADR7-INV-02: Anti-capture — binding invariant; grounded in Tier 3 challenge rights + CIC existence

**From 38A threat validation:**
- TM-39 / F-4 (Independence Illusion): FAIL-class at Adversary D; design-level structural FAIL; confirmed as **dominant residual FAIL-class risk** in 38B-07/08
- TM-06 (Concentration chain capture): FAIL; EC + CA full capture = F
- TM-19 (AC-31 capture): C-F → F; AC-31 ratchet more dangerous than EC ratchet (automatic)
- TM-42 (Complete Deadlock): UNCONDITIONAL FAIL; non-adversarial trigger possible
- TM-44 (Temporal Concentration): C-F approaching F
- TM-47 (Certification self-reference): C-F → F conditioned on TM-19

**From 38B governance specification:**
- AA-01: Pre-constitutional assumption; MA holds 15 constitutional functions post-38B; forward-permanent urgency (OBS-38B05-02)
- OBS-38B06-05 (PERMANENT): specification completeness ≠ governance sufficiency
- OBS-38C-01: authorization ≠ confirmation of 38B correctness; 38B specifications are inputs, not proofs
- OBS-38B07-03: F-4/TM-39 cannot be mitigated by constitutional specification
- Family-B (Delegated Constitutional): governing architectural family; internally coherent; F-4 and AA-01 unresolved within it
- OQ-38B05-07 mandate: must resolve before first 38C technical architecture ADR (38B-07, 38B-08)

**From 38C Authorization:**
- ARB-CONSTRAINT-38C-01: no technical architecture without further ARB decision
- ARB-CONSTRAINT-38C-02: discovery scope discipline
- The program is in Strategic DDD Discovery (38C) — technical realization has not yet occurred; ADR model has not yet been validated under realization

### A.3 — Twelve Carry-Forward Observations from ARB Review

All twelve (RV-B-01 through RV-F-02) are addressed in Part E.

---

## Part B — Review of Option A

### B.1 — Constitutional Strengths

**Separation resilience (grounded in TM-06 analysis):** Option A maintains two independent governance systems — EC and ADRs — which constitute two separate attack surfaces. A successful constitutional capture of EC does not automatically compromise architectural governance (ADR layer). This provides resilience against TM-06 (Concentration Chain Capture) that Options B and C do not fully replicate.

**AA-01 stability (grounded in 38B-04 MA function inventory):** Option A does not increase MA's constitutional function inventory beyond the 15 functions established in 38B. Every additional MA function amplifies the pre-constitutional AA-01 assumption (OBS-38B04-04). Option A minimizes this amplification.

**Discovery-phase correction agility (grounded in OBS-38C-01):** OBS-38C-01 states that 38B specifications are inputs to discovery, not proofs of correctness. The current ADR model will encounter corrections during technical realization (38D+). Option A permits ARB-level ADR corrections without EC amendment process. This is constitutionally relevant during a discovery phase when ADR-level errors are expected, not exceptional.

### B.2 — Constitutional Weaknesses

**TM-39 / F-4 (dominant residual FAIL-class risk): UNMITIGATED.** This is the most constitutionally significant weakness of Option A.

38A-03 confirmed TM-39 as a design-level structural FAIL at Adversary D. 38B-06 confirmed it as the dominant residual FAIL-class risk in the program. 38B-07 confirmed it as a program-level risk that constitutional specification alone cannot mitigate.

Under Option A, independence forms (ADR-2 per-function) are architecturally revocable without constitutional visibility. An adversary or a future architectural revision can degrade independence forms without triggering any constitutional challenge procedure. The architecture cannot detect — and the constitution cannot prevent — the nominal independence becoming practical capture. This is the structural definition of TM-39.

The Tier 3 minimum provides the existence of independence as constitutionally protected. Option A does not protect the **form** of independence. Existence without form is insufficient to prevent TM-39.

**Constitutional gap profile:** All ADR invariants (ADR3-INV-01, ADR5-INV-01, ADR6-INV-01, ADR7-INV-01, ADR7-INV-02) are architectural under Option A — revocable without constitutional process, not challengeable through S-1/S-2/S-3 standing. This creates a governance architecture in which the most carefully derived invariants of the entire Round 37 process have no constitutional anchoring.

### B.3 — Ruling Assessment of Option A

The constitutional record does not support Option A as the primary ruling outcome.

The dominant constitutional risk of the program (F-4/TM-39) is structurally unmitigated under Option A. The program has invested seven rounds of constitutional discovery, threat validation, and governance specification to arrive at seven ADRs with binding invariants. Leaving those invariants constitutionally unanchored contradicts the explicit purpose of that work.

Option A is not constitutionally indefensible. It is architecturally agile and AA-01 stable. But its TM-39 exposure is a known FAIL-class risk that this program cannot accept as the final constitutional arrangement for the ADR-EC relationship.

---

## Part C — Review of Option B

### C.1 — Constitutional Strengths

**Strongest TM-39 mitigation.** Under Option B, independence forms (ADR-2) become Tier 2 constitutional. Changing independence forms requires qualified majority + deliberation period. This provides the constitutional visibility that Option A lacks. Nominal independence cannot become practical capture through architectural revision without triggering constitutional amendment process.

**Full ADR invariant protection.** All seven binding invariants (ADR3-INV-01 through 38B05-INV-01) become Tier 2 constitutional. Each is challengeable through S-1/S-2/S-3 standing. CIC and CAB have jurisdiction over ADR-level governance failures.

**Strongest TM-47 and TM-42 mitigation.** ADR6-INV-01 (CO-5 derivation rule) and ADR7-INV-01 (succession pre-designation) gain constitutional protection. Weakening the certification derivation chain or degrading succession mechanisms requires Tier 2 amendment.

### C.2 — Constitutional Weaknesses

**Governance ossification during discovery phase (grounded in OBS-38C-01).** This is Option B's most significant constitutional weakness given the current program state.

OBS-38C-01 states: "The authorization of 38C does not imply that the governance specifications of 38B are correct. It implies only that they are sufficiently mature to become inputs to strategic discovery." This is not an isolated observation — it is a direct acknowledgment that errors in the current ADR model are expected during technical realization.

Under Option B, correcting an error in any existing ADR — including a technical architectural error discovered during 38C or 38D realization — requires Tier 2 EC amendment (qualified majority + deliberation). This directly contradicts the discovery-phase expectation established by OBS-38C-01 and the governance sequence established by ARB-CONSTRAINT-38C-02. The program would be constitutionally locked into potentially incorrect architectural decisions before those decisions have been validated.

This is not a hypothetical risk. The program's own governance documents confirm that 38B specifications are inputs, not proofs. Constitutionalizing all ADR decisions under Option B before they are validated is constitutionally premature.

**Dual-channel MA control (grounded in 38B-04 MA function inventory; RV-B-04).** Under Option B, MA acquires two channels of control over ADR governance:

1. EC Tier 2 amendment process (MA ratification controls which ADR changes are constitutionally approved)
2. CIC interpretation of all ADRs as Tier 2 instruments (MA appoints CIC)

The 38B-04 MA function inventory stands at 15 functions. Option B effectively adds control over all ADR revision processes, plus expands CIC's interpretive scope to encompass all ADRs. The constitutional consequence: an AA-01 failure (MA is not legitimately constituted) simultaneously compromises both the constitutional layer (EC) and the architectural layer (all ADRs as Tier 2). This is the lowest governance survivability of the three options.

**Bootstrapping problem (OQ-38C-05).** Retroactive Tier 2 designation of all seven existing ADRs requires a Tier 2 EC amendment process to enact — before Option B is operative, a Tier 2 process must be completed to make all ADRs Tier 2. Who initiates this process, under what authority, before Option B is constitutionally effective, is unresolved.

### C.3 — Ruling Assessment of Option B

The constitutional record does not support Option B as the primary ruling outcome.

Option B's TM-39 mitigation is genuinely strong. But its governance ossification risk during the current discovery phase is structurally contradicted by OBS-38C-01, and its AA-01 amplification to maximum exposure contradicts the cumulative weight of OBS-38B04-04, OBS-38B05-02, and the forward-permanent urgency of AA-01 resolution.

A ruling that selects Option B would constitutionalize ADR errors that OBS-38C-01 expressly acknowledges are likely to be discovered in 38C. This is constitutionally backwards from the discovery discipline the program has established.

Option B may be appropriate in a future round (38D+) once technical realization has validated the ADR model. It is not appropriate as the ruling outcome for a program in Round 38C-03.

---

## Part D — Review of Option C

### D.1 — Constitutional Strengths

**Partial TM-39 mitigation without governance ossification.** Under Option C, independence principles are constitutional (protecting the existence and nature of independence at the constitutional level) while independence forms remain architectural (permitting form-level corrections without Tier 2 amendment). This provides constitutional visibility for the governance category that TM-39 attacks — independence degradation — while preserving the correction agility that OBS-38C-01 requires.

**Targeted AA-01 amplification.** Option C adds a moderate increase to MA dependency (principle amendments trace to MA; form amendments do not). This is an increase, but it is not the maximum-exposure increase of Option B. The form layer retains ARB governance, partially preserving the two-system resilience of Option A.

**Addresses the F-4/TM-39 at the constitutionally relevant layer.** TM-39 (Independence Illusion) attacks the form of independence — the practical realization of nominal independence. Option C's constitutional protection of independence principles ensures that the constitutional requirement for independence survives even when forms are amended. An adversary can revise an independence form only within the constraints of the constitutionally protected independence principle. This is a structural protection that Option A entirely lacks.

**Preserves governance phase compatibility.** Under Option C, ADR-level form corrections (technical realization errors, implementation refinements) do not require EC amendment. This is compatible with the discovery-phase expectation established by OBS-38C-01 and the governance sequence of ARB-CONSTRAINT-38C-02.

### D.2 — Constitutional Weaknesses

**Three prerequisites before fully operative (RV-B-05).** Option C requires:
1. EC extension to include principle-level provisions (amendment process required)
2. Classification exercise to designate existing ADR elements as principles or forms (separate ARB authorization required)
3. CIC jurisdiction expansion for boundary disputes (38B-01 update)

Until these prerequisites are complete, there is a governance gap: the principle/form distinction is declared but not yet operationalized for existing ADR invariants.

**OQ-38C-04 load-bearing (ADR7-INV-02 ambiguity).** If ADR7-INV-02 (anti-capture invariant) is classified as a form rather than a principle in the classification exercise, TM-39 mitigation under Option C is substantially weakened. ADR7-INV-02 is the direct constitutional defence against self-referential authority expansion — the specific mechanism by which Independence Illusion exploitation operates. If it is a form, it is architecturally revocable without constitutional visibility, and Option C's TM-39 mitigation is structurally undermined.

This risk is manageable: the classification exercise can — and must — address ADR7-INV-02 with explicit attention to its TM-39 implications. But the risk is load-bearing, not incidental.

**Principle/form boundary contestability.** The boundary between principle and form is a potential governance target. A boundary challenge that successfully reclassifies a principle as a form removes constitutional protection without EC amendment. CIC's boundary-determination role must be constitutionally robust against this attack path.

### D.3 — Ruling Assessment of Option C

The constitutional record supports Option C as the ruling outcome, subject to the implementation conditions in Part F.

The program has identified TM-39/F-4 as the dominant residual FAIL-class risk. Option C provides constitutional protection at the level TM-39 attacks (independence principles), without the governance ossification of Option B or the complete TM-39 exposure of Option A.

The three prerequisites are governance work within ARB authority — not obstacles that require external resolution. The ADR7-INV-02 risk is real and must be addressed explicitly in the classification exercise, but it does not constitute a reason to defer the ruling.

The AA-01 amplification under Option C is moderate and bounded. Option C does not create the dual-channel MA control of Option B.

---

## Part E — Assessment of Twelve Carry-Forward Observations

| Observation | Assessment |
|-------------|------------|
| **RV-B-01** — "Gap width" in Option A evaluation is mild steering | Noted. The term "wide" is a structural description used to frame comparison, not a pre-judgment. Ruling does not inherit any weight from that language; analysis is driven by threat and concentration evidence. |
| **RV-B-02** — EC/ADR long-term divergence risk under Option A | Valid concern. Under Option C, principle provisions anchor the constitutional baseline; ADR forms must remain consistent with those principles. CIC adjudicates boundary disputes that include constitutional/architectural divergence. This divergence risk is partially addressed by Option C's principled connection between the two layers — not present under Option A. |
| **RV-B-03** — Option B governance ossification asymmetrically severe in 38C vs. 38D+ | Accepted as governing. This is the primary constitutional argument against Option B at current program phase. Addressed in Part C.2. |
| **RV-B-04** — Dual-channel MA control under Option B | Accepted as governing. Under Option C, MA's control is limited to principle-level EC provisions; form-layer ARB governance is independent. The dual-channel amplification of Option B is avoided. |
| **RV-B-05** — Option C three prerequisites before operative | Accepted as a sequencing constraint. Addressed by 38C03-CON-01 through 38C03-CON-03 (Part F.4). The prerequisites are ARB-authorized governance work, not external dependencies. |
| **RV-B-06** — Boundary ambiguity exploitation: structural vs. implementation-dependent? | Partially structural. The boundary determination role held by CIC creates a contestable point. However, the attack path requires successfully arguing a mis-classification to CIC — a body that is independent (interpretive independence per 38B-01) and whose decisions are themselves challengeable through S-2/S-3 standing. This is a structural friction, not a structural void. The ruling imposes 38C03-INV-02 (boundary determination must address exploitation resistance) to make this explicit. |
| **RV-C-01** — Constitutional divergence risk under Option A (two systems, no mutual enforcement) | Accepted as additional evidence against Option A. Under Option C, the principle/form structure creates explicit mutual grounding: ADR forms must realize EC principles; EC principles must be derivable from the constitutional purpose the ADR serves. This is not fully enforced without the classification exercise, but the conceptual grounding is present in Option C in a way it is not in Option A. |
| **RV-C-02** — Whether Option C's partial TM-39 mitigation is constitutionally sufficient given F-4 severity | The ruling cannot claim sufficiency with confidence — no architecture fully mitigates TM-39 (OBS-38B07-03). The relevant comparison is not "Option C vs. full TM-39 mitigation" but "Option C vs. the alternatives within the program's current constraint set." Against Option A (no mitigation), Option C is constitutionally superior. Against Option B (strong mitigation but governance ossification), Option C is constitutionally appropriate for the current phase. The ruling accepts partial mitigation as the best achievable outcome given program constraints, consistent with OBS-38B07-03. |
| **RV-D-01** — CIC-capture degradation path under Option C | Acknowledged. CIC's boundary-determination role creates a capture risk distinct from MA compromise. The programme must ensure CIC independence under Option C extends to the boundary-determination function specifically. 38C03-CON-03 addresses this by requiring that the 38B-01 CIC update produced for Option C explicitly specify the interpretive independence requirement for boundary determinations. This is an additional obligation, not a disqualifier. |
| **RV-E-01** — Whether CIC boundary-determination role = 16th MA function | Determined: CIC's boundary-determination role under Option C is an **extension of the CIC interpretive function** (which is already MA function #8 in the 38B-04 inventory via CIC appointment). It is not a new independent MA function. The scope of CIC's interpretation expands; the nature of MA's control through CIC appointment does not change. MA function count remains 15 under Option C (plus the EC amendment ratification function #15). |
| **RV-E-02** — Whether Option B ADR amendment control = new MA functions | Determined: Under Option B, MA's control over ADR amendment processes would constitute an extension of function #15 (EC amendment ratification) rather than new numbered functions. However, the scope amplification is materially significant — all seven existing ADRs plus future 38C ADRs become subject to MA Tier 2 ratification. This is a scope amplification of function #15 that exceeds any other 38B expansion. The dual-channel problem (function #15 for amendment + CIC appointment for interpretation) remains and is addressed by Option C's non-selection of Option B. |
| **RV-F-01** — Whether timing asymmetry (38C vs. 38D+) is a factor in option selection | Accepted as governing. Timing is a constitutional factor — the program's current phase (discovery, with ADR model unvalidated) materially changes the constitutional cost of governance ossification. The ruling incorporates a phase-appropriate selection: Option C now, with Option B revisitation authorized if demanded by 38D+ realization evidence. |

---

## Part F — OQ-38B05-07 Ruling

### F.1 — Question

**OQ-38B05-07:** What is the constitutional relationship between ADR architectural decisions and Election Constitution (EC) tier provisions?

### F.2 — Ruling

```
OQ-38B05-07

RULING: OPTION C SELECTED
(Hybrid Principle/Form Split)

Effective: 2026-06-18
Issued by: ARB (Senior DDD Architect + Constitutional Governance
            Architect + Online Voting Security Architect)

Constitutional relationship:

  ElectionConstitution holds constitutional principles
  at assigned tiers (Tier 1, Tier 2, or Tier 3).

  ADRs hold implementation forms at the architectural level.

  Each has distinct standing.

  EC-held principles carry constitutional authority.
  ADR-held forms carry architectural authority only.

Constitutional protection:

  Independence principles: constitutional (EC)
  Independence forms:      architectural (ADR)

  Challenge procedures S-1/S-2/S-3 apply to
  principle violations.

  Form disputes go to architectural governance (ARB).

  CIC adjudicates principle/form boundary disputes
  when contested.

This ruling governs all subsequent 38C documents,
all future technical architecture ADRs, and all
future ADR amendments.

OQ-38B05-07: RESOLVED
```

### F.3 — Constitutional Grounding

This ruling is grounded in:

1. **TM-39/F-4 dominance:** Option C provides constitutional protection at the level TM-39 attacks (independence principles) without the governance ossification of Option B. The dominant residual FAIL-class risk is partially but materially mitigated. Neither of the other options achieves this balance.

2. **OBS-38C-01 compatibility:** Option C preserves architectural correction agility at the form level. 38B specifications may contain errors that 38C discovery will surface. Form-level corrections do not require EC amendment under Option C. The constitutional arrangement is compatible with the discovery-phase governance posture.

3. **AA-01 proportionality:** Option C adds moderate AA-01 amplification (principle amendments trace to MA). This increase is bounded and proportionate. It does not create the maximum-exposure dual-channel control of Option B, nor does it amplify AA-01 beyond what the program has already accepted in 38B-01 through 38B-05.

4. **ADR7-INV-02 constitutional anchoring required:** The classification exercise (38C03-CON-01) must treat ADR7-INV-02 as a load-bearing case for TM-39 mitigation. If ADR7-INV-02 is classified as a form, Option C's TM-39 mitigation is substantially weakened. This is a binding instruction, not a recommendation.

### F.4 — Implementation Conditions (Binding)

The Option C selection is conditional on the following:

**38C03-CON-01 — Classification Exercise Authorization**

A classification exercise is hereby authorized within Round 38C. Its scope: designate each existing ADR element (invariants from ADR-1 through ADR-7, 38B-01 through 38B-05) as principle-level or form-level, subject to the observations in Part G of the 38C-02 Evaluation (appears principle-like / appears form-like / ambiguous). The exercise produces candidate classifications for ARB ruling. The exercise does not itself determine classifications — it prepares them for ARB review.

ADR7-INV-02 (anti-capture invariant) must be designated principle-level in the classification exercise unless the classification team can demonstrate that a constitutionally deeper principle (challenge independence) provides equivalent or stronger protection independently. The default designation is principle-level for TM-39 mitigation reasons.

**38C03-CON-02 — EC Extension Authorization**

EC extension to include principle-level provisions is hereby authorized as a prerequisite work item within 38C. The extension must be enacted via the amendment process appropriate to the tier assigned to each principle in the classification exercise (Tier 2 for governance-level principles; Tier 3 for protected-core-level principles, per 38B-05). The EC extension must be completed before any technical architecture ADR (post-38C) is written for any ADR element designated principle-level.

**38C03-CON-03 — CIC Jurisdiction Update**

38B-01 must be updated to specify CIC's boundary-determination jurisdiction explicitly. The update must:
(a) Define that CIC adjudicates principle/form boundary disputes when contested
(b) Specify that interpretive independence (38B-01 character) extends to the boundary-determination function
(c) Address the boundary-determination capture risk identified in RV-D-01 by requiring that CIC boundary rulings are themselves challengeable through S-2/S-3 standing

**38C03-CON-04 — Interim Governance Rule**

Until 38C03-CON-01 (classification exercise) is complete, no existing ADR invariant is treated as constitutionally unprotected. The classification exercise determines constitutional standing; until it concludes, status quo protections prevail. No architectural revision that would reduce existing ADR invariant force is authorized pending classification.

### F.5 — Re-Defer Option: Not Applicable

Re-deferral of OQ-38B05-07 is not constitutionally available. 38B-07 and 38B-08 ruled OQ-38B05-07 as a mandatory early requirement that must resolve before the first 38C technical architecture ADR. The program is now in 38C-03. A second deferral would contradict the binding mandate of 38B-07/08 without new constitutional evidence. No such evidence has emerged.

---

## Part G — Consequences of Ruling

### G.1 — Consequences for 38C Governance

**Principle/form distinction is now the governing constitutional framework** for the ADR-EC relationship throughout 38C and all subsequent rounds.

Future 38C discovery documents that touch ADR elements must operate within this framework:
- Findings about independence requirements → principle-level (constitutional)
- Findings about independence implementation → form-level (architectural)
- Any new ADR element proposed must carry a principle/form designation before ARB approval

**Classification exercise is now an authorized 38C work item.** It must be completed before the first technical architecture ADR. It is a prerequisite for 38C03-CON-02 (EC extension).

**OQ-38C-03 is partially addressed.** Whether existing ADR invariants require explicit EC designation or are already constitutional by derivation is resolved at the principle level: they require explicit designation (38C03-CON-02). The derivation argument is not sufficient — explicit EC designation is required for constitutional standing under Option C.

**OQ-38C-01 (principle/form classifier authority) is resolved by this ruling.** CIC adjudicates principle/form boundary disputes (38C03-CON-03). CIC's authority for the boundary-determination function is hereby established.

### G.2 — Consequences for Future ADR Handling

**No new technical architecture ADR may be written until:**
1. This ruling is formally accepted (38C-03)
2. The classification exercise (38C03-CON-01) has designated the principle/form status of the relevant governing ADR element
3. The EC extension (38C03-CON-02) has added the relevant principle provision to EC at its assigned tier

This is a binding sequencing constraint derived from the Option C operative conditions. Architectural decisions built on constitutionally undesignated principles are not protected by Option C's constitutional framework.

**Existing ADRs are not retroactively changed by this ruling.** Their architectural content remains. Their constitutional standing is determined by the classification exercise. Until the classification exercise, status quo protections apply (38C03-CON-04).

**ADR7-INV-02 receives conditional principle-level designation.** Per Part F.4 (38C03-CON-01), ADR7-INV-02 is designated principle-level by default unless the classification exercise produces a constitutionally grounded counterargument. This default designation is binding until the classification exercise explicitly rules otherwise.

### G.3 — Consequences for Future Discovery Work

**38C discovery documents must observe the principle/form distinction.** Any discovery finding that touches a principle-level element must acknowledge its constitutional standing. Any discovery finding that proposes a form-level change must acknowledge that the change must remain within the constraints of the governing principle.

**OQ-38C-02, OQ-38C-04, OQ-38C-05 disposition:**
- OQ-38C-02 (EC extension amendment level): resolved by 38C03-CON-02 — the amendment level is determined by the tier assigned in the classification exercise, per 38B-05 tier structure
- OQ-38C-04 (ADR7-INV-02 ambiguity): addressed by the conditional default designation in 38C03-CON-01; classification exercise must adjudicate explicitly
- OQ-38C-05 (Option B bootstrapping): moot — Option B not selected

---

## Part H — Interaction with OQ-38B05-05

**OQ-38B05-05:** Does trust root structural separation require explicit constitutional protection, or is architectural design sufficient?

**Assessment: Partially addressed — mechanism created, question open.**

Option C creates the constitutional mechanism by which OQ-38B05-05 can be resolved. Trust root structural separation could be designated as a principle-level provision in the classification exercise (38C03-CON-01) and added to EC via appropriate tier amendment (38C03-CON-02). If trust root separation is designated a Tier 3 principle, it would receive near-unanimity protection — which is the Option A outcome for OQ-38B05-05 (explicit constitutional protection at the highest tier).

However, Option C's selection does not itself resolve OQ-38B05-05. The classification exercise must explicitly address trust root separation as a candidate principle. Whether it is designated Tier 2 or Tier 3 (or not designated as principle-level at all) is a determination for the classification exercise, subject to ARB approval.

**OQ-38B05-05 status after this ruling: MANDATORY PRIMARY REQUIREMENT — unchanged. Mechanism now exists (via Option C) to address it within 38C. Classification exercise must include trust root structural separation as a mandatory candidate.**

---

## Part I — Program State Update

```
PROGRAM STATE — 2026-06-18

Round 36A–36E  Constitutional Discovery       COMPLETE
Round 37        ADR Authoring                 COMPLETE
Round 38A       Threat Validation             CLOSED
Round 38B       Constitutional Governance     CLOSED WITH DEFERRED QUESTIONS

Round 38C       Strategic DDD Discovery       AUTHORIZED AND IN PROGRESS

  38C-01        Discovery Charter             APPROVED
  38C-02        OQ-38B05-07 Evaluation        ACCEPTED
  38C-02-Review OQ-38B05-07 ARB Review        APPROVED
  38C-03        OQ-38B05-07 ARB Ruling        THIS DOCUMENT

OQ-38B05-07:    RESOLVED — OPTION C SELECTED
                (Hybrid Principle/Form Split)

OQ-38B05-05:    MANDATORY PRIMARY REQUIREMENT — UNCHANGED
                Mechanism now available via Option C.
                Classification exercise must include
                trust root separation as mandatory candidate.

OQ-38A05-02:    PROTECTED — unchanged throughout.

Active obligations from this ruling:
  38C03-CON-01  Classification exercise (authorized, 38C scope)
  38C03-CON-02  EC extension (authorized, prerequisite to tech ADRs)
  38C03-CON-03  CIC jurisdiction update (required, 38B-01 extension)
  38C03-CON-04  Interim governance rule (in force until CON-01 complete)

New invariant:
  38C03-INV-01  No existing ADR invariant loses protection pending
                classification exercise — status quo protections prevail
                until explicit classification ruling

  38C03-INV-02  CIC boundary-determination rulings must be explicit
                on boundary-manipulation/exploitation resistance;
                rulings are themselves challengeable through
                S-2/S-3 standing

Next steps in 38C:
  (1) Classification exercise (38C03-CON-01) — first priority
  (2) OQ-38B05-05 evaluation — mandatory primary requirement
  (3) OBS-38B08-04 evaluation — does technical realization amplify
      or mitigate governance concerns?
  (4) Further 38C discovery documents proceed under Option C framework

Technical Architecture:    NOT YET EVALUATED
DDD Strategic Discovery:   IN PROGRESS (~20%)
Implementation:             0%
```

---

*Round 38C-03 — ARB Ruling: OQ-38B05-07 — SUBMITTED FOR ARB RULING*
*Research Program: NRNA DDD Trustworthiness*
*Date: 2026-06-18*
*Ruling: OQ-38B05-07 RESOLVED — OPTION C (Hybrid Principle/Form Split) SELECTED*
*Binding: 38C03-CON-01 through CON-04; 38C03-INV-01 through INV-02*
*OQ-38A05-02 PROTECTED throughout*
*OQ-38B05-05 MANDATORY PRIMARY REQUIREMENT — mechanism available via Option C*
*38C01-INV-01: This ruling does not constitute technical architecture*
