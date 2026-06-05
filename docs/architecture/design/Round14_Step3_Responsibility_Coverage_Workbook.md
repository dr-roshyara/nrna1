# Round 14 Step 3 — Responsibility Coverage Workbook

**Strategic Design: Explanatory Coverage Mapping**

**Date:** 2026-06-05  
**Status:** Exploratory Coverage Analysis  
**Objective:** Map what each candidate concept explains, partially explains, or cannot explain about observed domain behaviors

---

## Mission

Prior steps tested Authority (Step 2) and documented alternative explanations (Step 2A).

This workbook maps explanatory coverage for all candidate concepts:
- Governance
- Authority
- Verification
- Evidence
- Trust
- Consensus
- Decision Lineage (preliminary)

For each concept: what behaviors can it explain, what remains partial, what lies outside its scope?

**Important:** This is coverage mapping, not model selection. We document what each concept explains without declaring any "foundational" or "non-negotiable."

---

## Methodology

For each concept:

1. **List** behaviors it clearly explains
2. **List** behaviors it partially explains
3. **List** behaviors it does not explain
4. **Note** alternative explanations for unexplained behaviors
5. **Assess** confidence in coverage assessment
6. **Preserve** uncertainty

No verdicts about whether concepts "earn their place."

Just map the territory.

---

## Concept 1: Governance

**Behaviors Clearly Explained by Governance:**

- Rule existence (Governance defines what rules exist)
- Rule scope (Governance bounds what authorities may do)
- Legitimacy standards (Governance defines what counts as legitimate)
- Permission basis (Governance grants permissions to roles)
- Context boundaries (Governance separates different decision contexts)
- Delegation permissions (Governance permits/prohibits delegation)

**Behaviors Partially Explained by Governance:**

- Authority scope variation (partially; context-specific rules explain some variation, not all)
- Decision binding (partially; Governance requires decisions to be followed, but doesn't explain why some decisions stick better than others)
- Acceptance patterns (partially; Governance defines legitimacy standards, but actors' actual acceptance varies)

**Behaviors Not Explained by Governance Alone:**

- Authority acceptance mechanisms (what transforms permission into effective power?)
- Recognition patterns (why are some authorities recognized while others with same Governance status are not?)
- Delegation chain semantics (why does delegated authority behave differently from permission transfer?)
- Permission-to-power translation (how does permission become actual exercised power?)

**Alternative Explanations for Unexplained Behaviors:**

- Verification
- Evidence
- Trust
- Authority (as independent concept)

**Confidence in Coverage Assessment:** MEDIUM-HIGH

**Open Questions:**

- Can Governance + context variation explain all scope variation, or is something independent operating?
- Is the permission-to-power gap explained by concepts yet to be discovered, or concepts already identified?

---

## Concept 2: Authority

**Behaviors Clearly Explained by Authority (if independent):**

- Context-specific authority variation (Authority varies by context in ways Governance rules may not)
- Delegation chain semantics (Authority flows through chains differently than permission)
- Authority acceptance asymmetry (some authorities accepted, others not, independent of Governance)

**Behaviors Partially Explained by Authority:**

- Permission-to-power gap (Authority could explain how permission becomes power, or this could be explained by Verification + Evidence + Trust)
- Authority recognition (Authority could explain why some are recognized, or Verification + Evidence could)
- Authority scope (Authority could define what role-holder may do within Governance boundaries)

**Behaviors Not Explained by Authority (if independent):**

- Rule existence (only Governance explains this)
- Legitimacy standards (only Governance explains this)
- Governance scope (Authority operates within scope, not outside)

**Alternative Explanations for Partially Explained Behaviors:**

- Verification
- Evidence
- Trust
- Context-specific Governance rules

**Confidence in Coverage Assessment:** MEDIUM

(High uncertainty about what Authority uniquely explains vs. what is explained by alternatives)

**Open Questions:**

- Can all "Authority behaviors" be explained by Governance + Verification + Evidence + Trust operating together?
- Is Authority explaining something independent, or is it a redescription of those concepts?

---

## Concept 3: Verification

**Behaviors Clearly Explained by Verification:**

- Legitimacy determination (Verification checks whether something meets legitimacy standards)
- Acceptance criteria (Verification can explain why some decisions are accepted—they passed verification)
- Authority claim validation (Verification validates that authority claims meet standards)

**Behaviors Partially Explained by Verification:**

- Recognition patterns (Verification could contribute to recognition, but Trust and Evidence also matter)
- Authority acceptance (Verification could explain some acceptance, but not all)
- Appeal triggers (Verification failure could trigger appeals, but other factors also trigger challenges)

**Behaviors Not Explained by Verification Alone:**

- Rule existence (only Governance explains)
- Verification standards themselves (who determines what counts as verified? This may require Governance)
- What to verify (Verification needs input about what matters to verify)

**Alternative Explanations for Unexplained Behaviors:**

- Governance (defines what standards to verify against)
- Evidence (provides material to verify)

**Confidence in Coverage Assessment:** MEDIUM-HIGH

**Open Questions:**

- Is Verification independent from Governance, or is it just "checking Governance compliance"?
- Can Verification exist without Evidence to verify?

---

## Concept 4: Evidence

**Behaviors Clearly Explained by Evidence:**

- Decision justification (Evidence provides the material to justify decisions)
- Verification basis (Evidence is what Verification checks against)
- Challenge support (Evidence supports or undermines challenges to decisions)

**Behaviors Partially Explained by Evidence:**

- Authority legitimacy (Evidence about authority backgrounds could support legitimacy, but so could Trust)
- Acceptance variation (Evidence quality could explain some acceptance variation)
- Appeal outcomes (Evidence could determine appeal decisions, but Consensus also matters)

**Behaviors Not Explained by Evidence Alone:**

- Rule existence (only Governance explains)
- What counts as evidence (Governance may define acceptable evidence types)
- Evidence evaluation (who decides if evidence is sufficient? This may require Verification or Authority)

**Alternative Explanations for Unexplained Behaviors:**

- Governance (defines what evidence is acceptable)
- Verification (determines whether evidence is sufficient)
- Authority (could decide what evidence matters in their domain)

**Confidence in Coverage Assessment:** MEDIUM

**Open Questions:**

- Is Evidence independent, or is it just "material that supports Governance/Verification"?
- Can Evidence exist without someone to evaluate it?

---

## Concept 5: Trust

**Behaviors Clearly Explained by Trust (if independent):**

- Authority acceptance asymmetry (same authority accepted in one context/by one group, rejected in another, based on trust)
- Consensus formation (actors agree based on mutual trust)
- Delegation confidence (delegates operate as if they hold real authority because original authority trusts them)

**Behaviors Partially Explained by Trust:**

- Recognition patterns (Trust could partially explain recognition)
- Acceptance variation (Trust could explain some variation in acceptance)
- Authority effectiveness (Trust in authority could make decisions stick)

**Behaviors Not Explained by Trust Alone:**

- Rule existence (only Governance explains)
- Legitimacy standards (only Governance explains)
- What to trust about (Trust requires something to be trusted in)
- How initial trust is formed (unclear whether Trust explains this, or Verification + Evidence do)

**Alternative Explanations for Unclear Behaviors:**

- Verification (actors trust those who are verified)
- Evidence (actors trust decisions backed by evidence)
- Authority (actors trust recognized authorities)

**Confidence in Coverage Assessment:** LOW-MEDIUM

(High uncertainty about whether Trust is independent or emergent from Verification + Evidence + Authority)

**Open Questions:**

- Is Trust an independent concept, or does it emerge from Verification + Evidence?
- What creates initial trust in a system?
- Can Trust vary independently of Verification/Evidence, or does it follow from them?

---

## Concept 6: Consensus

**Behaviors Explained by Consensus (if independent):**

- Multi-party decisions (multiple actors agreeing creates decision)
- Collective legitimacy (shared agreement creates legitimacy)
- Authority conflict resolution (consensus among actors resolves which authority prevails)

**Behaviors Partially Explained by Consensus:**

- Acceptance determination (Consensus could determine what's accepted)
- Constitutional changes (Consensus among governance actors could change Governance)
- Appeal outcomes (Consensus among appeal bodies determines results)

**Behaviors Not Explained by Consensus Alone:**

- Rule existence (only Governance explains initial rules)
- Who participates in consensus (Governance may define this)
- Consensus criteria (Governance may define what consensus means—unanimous, majority, supermajority)

**Alternative Explanations for Unexplained Behaviors:**

- Governance (defines consensus rules and participants)
- Authority (authority figures may drive consensus)
- Verification (verification outcomes may determine consensus)

**Confidence in Coverage Assessment:** MEDIUM

**Open Questions:**

- Is Consensus an independent strategic concept, or is it procedural (how Governance rules are enforced with multiple actors)?
- Does Governance fully define Consensus, or does Consensus add independent explanatory value?
- Are consensus patterns explained by Governance + Authority, or is Consensus truly independent?

---

## Concept 7: Decision Lineage

**Behaviors Possibly Explained by Decision Lineage:**

- Decision history traceability (Lineage traces how decisions evolved)
- Stage progression (Lineage could explain 8-stage pattern progression)
- Reversal capability (Lineage enables tracing back to reverse decisions)
- Authority origin tracing (Lineage traces where authority came from)

**Behaviors Partially Explained by Decision Lineage:**

- Authority source validation (Lineage traces sources, but doesn't validate them)
- Delegation chains (Lineage could trace delegation, but doesn't explain delegation semantics)

**Behaviors Not Explained by Decision Lineage Alone:**

- Why stages exist (only Authority/Governance could explain)
- Whether reversal is permitted (only Governance/Authority could explain)
- What makes decisions binding (only Governance/Authority could explain)
- Rule existence (only Governance explains)

**Alternative Explanations:**

- Governance (defines stages, rules for reversibility)
- Authority (may define what can be reversed)
- Verification (may determine when reversal occurs)

**Confidence in Coverage Assessment:** LOW

(Decision Lineage is least explored; much uncertainty remains)

**Open Questions:**

- Is Decision Lineage a strategic layer, or just a description of how Governance + Authority operate through time?
- Does the 8-stage pattern represent independent structure, or emerge from Governance rules + Authority operation?
- Can Decision Lineage exist without Governance defining the stages?

---

## Coverage Summary Table

| Concept | Behaviors Clearly Explained | Partially Explained | Unexplained | Alternative Explanations Needed | Confidence |
|---|---|---|---|---|---|
| Governance | 6 major | 3 major | 4 major (most are permission-to-power style) | Need to explain how permission becomes power | MED-HIGH |
| Authority | 3 major (if independent) | 2 major | Most behaviors explainable by alternatives | Verification, Evidence, Trust, Governance variants | MEDIUM |
| Verification | 3 major | 3 major | 3 major (standards/sources unclear) | Governance, Evidence | MED-HIGH |
| Evidence | 3 major | 3 major | 3 major (evaluation/sufficiency unclear) | Governance, Verification, Authority | MEDIUM |
| Trust | 3 major (if independent) | 3 major | 4 major (trust formation unclear) | Verification, Evidence, Authority | LOW-MEDIUM |
| Consensus | 3 major (if independent) | 3 major | 3 major (role/rules unclear) | Governance, Authority | MEDIUM |
| Decision Lineage | 4 major (if independent) | 2 major | 4 major (pattern sources unclear) | Governance, Authority, Verification | LOW |

---

## Key Observations (Not Verdicts)

### Observation 1

Governance explains behaviors (rule existence, scope, standards) for which no alternative explanation has yet emerged.

This suggests Governance may explain different phenomena than other concepts, not necessarily that Governance is "foundational" (a verdict to be decided later).

### Observation 2

Authority's explanatory scope is contested. Some behaviors it could explain are also explainable by alternatives (Verification + Evidence + Trust). Others remain unclear.

This is precisely where model stress testing should continue—not concluded here.

### Observation 3

Verification, Evidence, Trust, and Consensus have only been explored in this workbook for the first time.

They underwent less scrutiny than Governance (which was analyzed in Rounds 8-11).

Symmetry suggests continuing exploration of these concepts before reaching conclusions about their strategic role.

### Observation 4

Decision Lineage is only now being preliminarily examined.

Insufficient evidence exists yet to assess its explanatory role.

### Observation 5

Some behaviors appear explained by multiple concepts (e.g., "acceptance" explained by Verification, Evidence, and Trust together).

This overlapping explanatory coverage is itself worth exploring—it could mean:
- Concepts are redundant
- Concepts are complementary
- Overlapping coverage indicates insufficient concept precision
- Something else

---

## Coverage Gaps

Behaviors that remain inadequately explained by any current concept:

1. **Permission-to-Power Translation** — How does Governance permission become effective exercised power?

2. **Authority Acceptance Mechanism** — What explains why some authorities are accepted while others with same formal status are not?

3. **Initial Trust Formation** — What creates trust in a system where no actors are yet trusted?

4. **Consensus Determination** — What determines what counts as "sufficient consensus"?

5. **Decision Lineage Origins** — Why does the 8-stage pattern emerge? What explains stage sequencing?

6. **Verification Standards Sourcing** — Who/what determines what counts as legitimate?

These gaps remain open for future investigation. They are not failures of the framework; they are what should be explored next.

---

## What Remains Unknown

- Whether Authority is independent, dependent, or non-strategic
- Whether Trust is independent or emergent from other concepts
- Whether Consensus is strategic or procedural
- Whether Decision Lineage is a pattern or a structural layer
- What resolves the permission-to-power gap
- What explains initial trust formation

---

**STATUS: Step 3 Responsibility Coverage Workbook Complete**

**FINDING: Governance explains distinct phenomena. Other concepts show mixed/overlapping explanatory coverage. Multiple important gaps remain unexplained.**

**IMPLICATION: Model selection cannot proceed yet. Continued exploration required to understand which concepts are independent vs. emergent.**

**NEXT: Steps 4-5 continue deep investigation of specific concept placement (Verification, Appeals/Consensus). Coverage gaps guide investigation priority.**
