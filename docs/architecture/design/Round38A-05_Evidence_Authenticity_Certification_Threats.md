# Round 38A-05 — Evidence, Authenticity, and Certification Threat Validation

**Status:** APPROVED WITH CORRECTIONS APPLIED  
**Predecessors:** 38A-01 APPROVED / 38A-02 APPROVED / 38A-03 APPROVED / 38A-04 APPROVED (with ARB-Review corrections)  
**Authorization:** ARB 2026-06-17 (38A-04-ARB-Review Part I) + ARB Supplemental Guidance (2026-06-17)  
**Authors:** ARB Chair, Senior DDD Architect, Online Voting Security Architect  
**New threats:** TM-45, TM-46, TM-47, TM-48, TM-49  
**Primary Structural Question:** Can a constitutionally final result remain constitutionally final after the authenticity root is later proven false?

---

## Part A — Organizing Frame: Three Trust Roots and CO-5 Convergence

### A.1 Three Trust Roots

Round 38A has converged on three foundational trust roots underlying the electoral architecture. These are not authority aggregates (D43) — they are the constitutional roots against which all authority, authenticity, and temporal validity are ultimately evaluated. They precede the D43 layer and condition it.

| Root | Name | Constitutional Role | Primary Risk |
|---|---|---|---|
| **Legitimacy Root** | ElectionConstitution | Defines the constitutional rules that all authority aggregates must follow; provides L-1 for all 7 D43 functions | EC capture → CO-4 circularity; constitutional self-destruction (AW-03-11) |
| **Authenticity Root** | AC-31 | Defines what constitutes authentic evidence; provides the reference against which CO-3 is evaluated | AC-31 capture → CO-3 invalid → CO-5 void (Gap 5 — no governance, no succession, no challenge) |
| **Temporal Root** | GovernanceState | Defines what constitutional phase has occurred; determines when CO-3 and CO-4 evaluations are constitutionally authorized | GovernanceState corruption → phase invalidity; challenges evaluate against GovernanceState, not the other way |

These are qualitatively distinct root categories:
- The Legitimacy Root defines **what rules apply**
- The Authenticity Root defines **what is true**
- The Temporal Root defines **when things happened**

Each root is a different dimension of constitutional validity. Each has a distinct failure mode. No single protective mechanism covers all three.

> **Architectural Discipline Note — Trust Roots Are Not DDD Artifacts:** "Trust Root" is a constitutional and threat-modeling abstraction. A Trust Root is not a DDD aggregate root, bounded context, or architecture component. ElectionConstitution, AC-31, and GovernanceState are designated here as constitutional abstractions — the foundational dimensions of validity underlying the electoral architecture. Whether any of these map to DDD aggregates, bounded contexts, services, or infrastructure components is a separate architectural determination belonging to Round 38B. AC-31 in particular has not been formally modeled as any DDD artifact in the existing design documents — it is an architectural hypothesis. Treating AC-31 as a formally modeled artifact produces speculative findings (see Part M — AC-31 Modeling Prerequisites).

### A.2 CO-5 as Three-Root Convergence Point

CO-5 (Election Validity) is **the only constitutional act that simultaneously requires all three trust roots to be valid:**

```
Legitimacy Root (EC)
    ↓ provides the constitutional rules
    CO-4: Constitutional Compliance
    (evaluates process against ElectionConstitution)

Authenticity Root (AC-31)
    ↓ provides the reference standard
    CO-3: Evidence Authenticity
    (evaluates evidence against AC-31 reference)

Temporal Root (GovernanceState)
    ↓ determines when evaluations are authorized
    Phase validity: CO-3/CO-4 evaluations occur in constitutionally
    authorized windows recorded by GovernanceState

        ↓ all three + CO-2 (Evidence Completeness)
        CO-5: Election Validity
        (ADR6-INV-01: CO-5 void unless CO-2 + CO-3 + CO-4 all independently satisfied)

            ↓ CO-5 + challenge window closed
            TS-1: Constitutionally Final State
```

**Key property:** TS-1 is the terminal constitutional state. Once achieved, ADR-6 defines it as final. The primary structural question is whether TS-1 survives post-issuance discovery that CO-3's basis (AC-31) was false.

### A.3 Primary Structural Question

> **Can a constitutionally final result (TS-1) remain constitutionally final after the Authenticity Root (AC-31) is later proven to have been false at the time CO-3 was evaluated?**

This question directly contradicts two constitutional principles simultaneously:
- **Finality Principle**: ADR-6 defines TS-1 as constitutionally final (challenge window closed, CO-5 issued)
- **Validity Principle**: ADR6-INV-01 states CO-5 is void unless CO-2 + CO-3 + CO-4 are *independently satisfied* — if CO-3 was not genuinely satisfied (AC-31 was false), CO-5 was issued in violation of ADR6-INV-01

Which principle governs? Gap 4 (no Constitutional Interpretation Authority) means no authoritative resolution exists within the architecture.

### A.4 Inherited Findings Relevant to 38A-05

| Finding | Source | Relevance |
|---|---|---|
| TM-19 (AC-31 capture) | 38A-03 | C-F → F; entry point for the authenticity chain FAIL path |
| Gap 5 (AC-31 governance absent) | 38A-01/38A-02 | AC-31 has no governance, succession, challenge, or detection mechanisms |
| ADR6-INV-01 (CO-5 requirement) | ADR-6 | CO-5 void unless CO-2 + CO-3 + CO-4 independently satisfied |
| ADR6-CONSTRAINT-01 (challenge window floor) | ADR-6 | Only constitutional minimum specification among all root protections |
| AW-04-08 (OA-01 load-bearing) | 38A-04 | OA-01 unresolved; CO-3 challenge path is theoretical until resolved |
| AW-03-11 (constitutional ratchet) | 38A-03 | Each election cycle entrenches captured EC further; same ratchet mechanism applies to AC-31 |
| OA-01 (challenger evidence access) | 38A-01 | Unresolved; required for any CO-3 challenge to be executable, not merely theoretical |
| TS-1 (terminal state) | ADR-6 | CO-5 + challenge window closed = constitutionally final; retroactive review mechanism: absent |
| Named Attestation | ADR-7 | CO-2/CO-3/CO-4 as individually challengeable sections; primary challenge mechanism |

---

## Part B — Root Dependency Analysis

*ARB Supplemental Guidance — 2026-06-17*

For each trust root, evaluate: challengeability, recoverability, succession, detectability, and concentration level. Then evaluate cross-root failure.

### B.1 Legitimacy Root — ElectionConstitution

| Property | Assessment |
|---|---|
| **Challengeability** | MEDIUM — ADR-5 Named Attestation allows challenges to CO-4 (constitutional compliance); EC amendments challengeable as process through ADR-5 standing; however, once ratified, amendment becomes the new EC and the constitutional ratchet (AW-03-11) makes retroactive challenge impossible |
| **Recoverability** | MEDIUM — MA intervention theoretically restores; amendment process allows correction; but Gap 3 (amendment process unspecified) and no constitutional floor (AW-02-11) limit this |
| **Succession** | PRESENT — EC persists across elections; amendment procedure conceptually handles revision; succession form = amendment (Gap 3: procedure unspecified) |
| **Detectability** | LOW — captured EC looks identical to valid EC; constitutional ratchet prevents detection after ratification (AW-03-11); constitutional drift unmeasurable (OQ-38A03-02) |
| **Concentration level** | HIGHEST — all 7 D43 aggregates, AC-31 legitimacy chain (Gap 5), GovernanceState authority all derive from EC; EC collapse = total constitutional failure |

**Note:** EC is the L-1 source for all authority aggregates. It is the origin layer, not an operational concentration point in the same sense as CA or CAB. Its concentration risk is captured in TM-01 (Constitution Capture) and TM-06 (Concentration Chain Capture).

### B.2 Authenticity Root — AC-31

| Property | Assessment |
|---|---|
| **Challengeability** | **NONE** — Gap 5; no challenge mechanism designated in ADR-5 architecture; Named Attestation challenges CO-3 compliance *with* AC-31, not the AC-31 standard itself; challenging AC-31's validity requires a meta-reference that the architecture does not specify |
| **Recoverability** | **NONE** — Gap 5; no succession mechanism; no reconstitution mechanism; no recovery path once compromised or lost |
| **Succession** | **NONE** — Gap 5; no successor designee; no succession trigger; no succession procedure; unlike D43 aggregates (ADR7-INV-01), AC-31 has no succession provision |
| **Detectability** | **NONE** — Gap 5; no governance body to monitor; compromised AC-31 produces the same outputs as valid AC-31 (authenticity cannot self-detect falseness); TM-19 condition: captured AC-31 authenticates itself |
| **Concentration level** | **HIGHEST operational** — all CO-3 across all elections; retroactive + prospective; no recovery; no challenge; no detection |

**Critical asymmetry (AW-05-01):** ADR6-CONSTRAINT-01 specifies a minimum precision requirement for the challenge window (non-zero, finite, constitutionally published, known before election start). No equivalent minimum specification exists for AC-31 (no minimum clarity standard, no minimum governance, no minimum accountability). The challenge window — a subordinate constitutional artifact — has more constitutional protection than the Authenticity Root itself.

### B.3 Temporal Root — GovernanceState

| Property | Assessment |
|---|---|
| **Challengeability** | **LOWEST of all operational elements** — Named Attestation challenges evaluate compliance WITH GovernanceState; they cannot override GovernanceState based on external evidence (self-referential challenge problem — see 38A-04-ARB-Review Part D) |
| **Recoverability** | **NONE within architecture** — AA-07 (sole authoritative record); no external corroboration mechanism; corrupted GovernanceState record cannot be corrected without an external reference the architecture has not specified |
| **Succession** | N/A as record aggregate — GovernanceState is a state machine aggregate (ADR-7), not an authority aggregate; the concept of "succession" does not apply; however, if GovernanceState becomes unavailable (TM-42 GovernanceAuthority deadlock), phase records become inaccessible |
| **Detectability** | **LOW** — corruption mimicking valid transitions is constitutionally indistinguishable from genuine transitions; TM-40 (GovernanceState Corroboration Absence): no external verification possible |
| **Concentration level** | HIGH within election — sole phase record; all phase-dependent constitutional actions depend on it; temporal concentration point (TM-44) |

**Candidate Gap 7 confirmed as candidate:** GovernanceState shares with AC-31 the self-referential property — both are constitutional roots in their domains that cannot be challenged using themselves as reference. Both lack governance specification. Both are concentration points below the root layer with no independent verification mechanism. Pending ARB confirmation in 38A-06 (alongside Gap 6).

### B.4 Cross-Root Failure Analysis

**Q1: Can failure of one root invalidate the others?**

| Failing Root | Effect on Other Roots |
|---|---|
| EC corruption (Legitimacy) | CO-4 evaluates corrupted EC → CO-4 "satisfied" against wrong standard → CO-5 voided by ADR6-INV-01 even if CO-2 and CO-3 are genuine |
| AC-31 corruption (Authenticity) | CO-3 evaluates against corrupted reference → CO-3 invalid → CO-5 voided by ADR6-INV-01 even if CO-4 and CO-2 are genuine |
| GovernanceState corruption (Temporal) | Phase records false → timing of CO-3/CO-4 evaluations constitutionally suspect → CO-5 may have been issued in wrong phase → CO-5 validity depends on GovernanceState's phase record being accurate |

A single root failure propagates to CO-5 through the certification dependency chain. Each root is independently capable of voiding CO-5 through ADR6-INV-01.

**Q2: Can two roots jointly validate a false result?**

YES — and without detection:

*Scenario: EC captured (TM-01/TM-06) + AC-31 captured (TM-19)*
- CO-4 evaluates compliance with captured EC → passes (captured EC is now valid EC)
- CO-3 evaluates evidence against captured AC-31 → passes (captured AC-31 authenticates anything adversary presents)
- CO-2 requires evidence completeness per AuditScopeAuthority → if the captured EC defined the evidence scope (Tier 1), completeness is evaluated against a corrupted standard
- CO-5 is issued: constitutionally valid in every observable respect, constitutionally void in every foundational respect
- Detection path: NONE — both roots appear valid; neither has a governance body or external verifier to flag compromise

**Q3: Can all three roots become mutually reinforcing while wrong?**

YES — this is the worst-case scenario:

*Three-root simultaneous failure:*
- EC corrupted → constitutional rules define evidence requirements and authentication standards in the adversary's favor
- AC-31 corrupted → evidence presented is "authenticated" per the adversary's standard
- GovernanceState corrupted → phases record as having occurred correctly; challenge windows appear open and closed at adversary-controlled times

Result: CO-2, CO-3, CO-4, CO-5 all satisfied. TS-1 achieved. The election is constitutionally valid in every observable dimension while being constitutionally void in every foundational dimension. No detection mechanism exists within the architecture when all three roots are simultaneously compromised.

*Classification (cross-root combination):* C-F → F — adversary action is required to achieve three-root simultaneous compromise; but once achieved, the false state is self-sustaining without further adversary action (parallel to TM-47 ratchet mechanism). This is not a new named threat — it is a synthesis observation (OBS-38A05-04 — see Part K).

### B.5 Membership Assembly as Source-of-Source Candidate

*ARB Supplemental Guidance — observe but do not resolve.*

All three trust roots exhibit potential dependency on Membership Assembly legitimacy:

- **ElectionConstitution (Legitimacy Root):** EC's constitutional authority derives from MA enactment. OBS-ADR7-SS1 names MA as "strongest candidate for source-of-source." If MA legitimacy fails (TM-07, AA-01), EC's L-1 status is undermined.

- **AC-31 (Authenticity Root):** AC-31's legitimacy chain is entirely unspecified (Gap 5). Whatever body would constitute the governance mechanism for AC-31 presumably derives its authority from MA (or from EC, which derives from MA). The absence of a specified chain means this is unverifiable — but the default assumption is MA as the ultimate source.

- **GovernanceState (Temporal Root):** GovernanceState's authority to record constitutional phases derives from EC (which empowers GovernanceAuthority to govern it), which derives from MA.

**Observation:** Membership Assembly may function as the common upstream dependency — the source-of-source — for all three trust roots. If so, AA-01 (MA Legitimacy Foundation — the highest-priority assumption in the register) is not merely a threat against one function. It is the single assumption that, if false, simultaneously de-legitimizes all three roots, all seven D43 authority aggregates, and CO-5 itself.

**No architectural conclusions drawn. No new threats named. No ADRs proposed.** This observation is carried to 38A-06 for pre-constitutional exposure synthesis.

---

## Part C — Authenticity Root Deep Analysis

### C.1 TM-19 — AC-31 Capture (Deepened from 38A-03)

*Classification: C-F → F (maintained from 38A-03 OQ-38A03-01 resolution)*

38A-03 established TM-19 as C-F → F. 38A-05 deepens the mechanism:

**Five attack forms of AC-31 capture:**

| Form | Mechanism | Constitutional Consequence |
|---|---|---|
| **Direct compromise** | Adversary gains control of the entity/infrastructure holding the reference standard | Authenticates false evidence as genuine; CO-3 satisfied trivially |
| **Substitution** | Adversary replaces AC-31 with a different reference standard without detection | As above; challenge: no governance body to detect replacement (Gap 5) |
| **Scope narrowing** | Adversary redefines what AC-31 authenticates — narrows to exclude adversary-produced evidence from authentication requirements | CO-3 "satisfied" but evidence set incomplete; overlaps CO-2 failure path |
| **Standard dilution** | Adversary causes AC-31 to accept lower-quality evidence as "authentic" — degrades the authentication threshold | Evidence that should fail CO-3 now passes; Gap 5 prevents any governance body from enforcing minimum standards |
| **Temporal manipulation** | Adversary targets AC-31 integrity between CO-3 evaluation and TS-1 achievement | CO-3 evaluated against valid AC-31; by time of post-hoc review, AC-31 has changed; no mechanism to verify which version of AC-31 was used |

**AC-31 Authenticity Ratchet (AW-05-07):** Each CO-5 issued against a compromised AC-31 further entrenches the false authenticity baseline. Future elections inherit a reference standard whose integrity is constitutionally unverifiable. Unlike the EC constitutional ratchet (AW-03-11 — requires MA to ratify amendments), the AC-31 ratchet operates automatically through the certification process itself: each CO-5 implicitly validates the AC-31 reference used at the time of CO-3 evaluation. No explicit act is required to deepen the ratchet. This is structurally more dangerous than AW-03-11.

### C.2 TM-45 — Authenticity Root Ambiguity

*New threat — 38A-05*  
*Class: 2 (Structural) — Adversary: None required / C, D*

**Description:** AC-31 is not captured; it is ambiguous. The reference standard is imprecisely specified — it is unclear what constitutes "authentic" evidence in specific cases. Different constitutional actors evaluating CO-3 against an ambiguous AC-31 may reach incompatible CO-3 conclusions for the same evidence set.

**Structural conditions currently met:**
- Gap 5: no governance body to specify or clarify AC-31
- Gap 4: no Constitutional Interpretation Authority to resolve ambiguity authoritative
- AW-05-01: no minimum clarity standard for AC-31 (asymmetry with ADR6-CONSTRAINT-01)

**Analogue:** TM-41 (Phase Boundary Ambiguity) at the authenticity level. However, TM-45 is more structurally dangerous than TM-41 for one reason: TM-41 ambiguity creates discretion over *when* phases occur; TM-45 ambiguity creates discretion over *what is true*. An ambiguous phase boundary can be contested and potentially resolved through challenge (TM-41 = C-F); an ambiguous authenticity standard corrupts the reference against which the challenge is itself evaluated.

**Attack surface:** No adversary action required for structural ambiguity to exist — Gap 5 and the absence of a minimum clarity standard create this condition constitutionally. Adversary action (Adversary C/D) can deliberately exploit the ambiguity by presenting evidence that satisfies some reasonable interpretation of AC-31 but not others.

**Classification: C-F**
- Condition A: AC-31 ambiguity (currently met structurally per Gap 5)
- Condition B: dispute arises over CO-3 evaluation (adversary action or election contest required)
- Consequence: CO-3 cannot be definitively satisfied or refuted; CO-5 eligibility contested; TS-1 achievement conditional on which interpretation governs

### C.3 TM-46 — Authenticity Root Succession Failure

*New threat — 38A-05*  
*Class: 2 (Structural) — Adversary: None required / B, C, D*

**Description:** AC-31 (the entity or mechanism holding the reference standard) becomes constitutionally unavailable — through organizational dissolution, technical failure, natural disaster, or adversarial incapacitation. No succession mechanism exists (Gap 5). Without AC-31, CO-3 cannot be evaluated. Without CO-3, CO-5 cannot be issued (ADR6-INV-01). Without CO-5, the election is constitutionally uncertified. The entire election cycle is constitutionally suspended.

**Structural analogy: TM-42 at the Authenticity Root layer**

| Property | TM-42 (Operational Deadlock) | TM-46 (Authenticity Root Succession Failure) |
|---|---|---|
| Constitutional minimum | No minimum capacity specification | No minimum AC-31 availability specification |
| Reconstitution mechanism | None | None (Gap 5) |
| Succession | No successor designee for D43 authorities | No successor designee for AC-31 (Gap 5) |
| Non-adversarial trigger | YES — natural disaster, organizational failure | YES — organizational dissolution, infrastructure failure |
| Recovery mechanism | None for Complete Deadlock | None (Gap 5) |
| Constitutional floor | None (AW-04-07) | None |

**Critical distinction from TM-42:** TM-42 Complete Deadlock requires multiple D43 authorities to fail simultaneously — the probability is conditional on multiple concurrent events. TM-46 requires only AC-31 to become unavailable — a **single-point failure** that immediately renders CO-3 impossible for all concurrent and future elections.

**Classification: C-F (Availability Catastrophe)**

*ARB Correction applied — 2026-06-17: A missing dependency is not automatically a constitutional FAIL. TM-46 produces an availability failure — election cannot proceed — not a constitutional legitimacy failure, validity failure, or constitutional void. Constitutional actors (GA, CA, CAB, AEA, all D43 functions) remain fully functional; the specific resource required for CO-3 evaluation (AC-31) is unavailable. This distinction matters: TM-42 Complete Deadlock (unconditional F) fails because the actors who would authorize recovery are themselves unavailable — no constitutional actor can act. TM-46 has all constitutional actors available; they simply cannot evaluate CO-3 without the reference standard. The architectural weakness is Gap 5 (no succession mechanism, no emergency provision) — not the threat classification itself.*

- Condition: AC-31 becomes constitutionally unavailable (no adversary action required for some failure modes — organizational dissolution, technical failure, natural disaster)
- Consequence: CO-3 impossible → CO-5 impossible → all elections constitutionally **suspended** (not void, not invalid) until AC-31 restored or successor established
- Recovery: None constitutionally specified (Gap 5) — the absence of a reconstitution or emergency mechanism is the architectural weakness, recorded as an availability catastrophe
- OQ-38A05-01 remains open: Does permanent loss (organizational dissolution) elevate TM-46 toward unconditional F?

**OQ-38A05-01:** Is TM-46 (permanent loss of AC-31 entity) an unconditional F parallel to TM-42 Complete Deadlock? The structural conditions are similar: non-adversarial trigger possible; no recovery mechanism; no constitutional floor. ARB ruling requested.

### C.4 The Authenticator Authentication Problem

The central self-reference question: **Who authenticates AC-31 itself?**

The certification chain: Evidence → CO-3 (authenticated against AC-31) → CO-5 → TS-1.

But: What validates that AC-31 is the correct reference standard? What validates that AC-31 has not been substituted? What validates that AC-31 is authentic?

The architecture's answer: nothing. AC-31 is constitutionally assumed to be valid. No body is designated to validate it. No chain of evidence supports it. No challenge mechanism exists for it. AC-31 is a constitutional assertion, not a verified constitutional artifact.

This creates the deepest authenticity gap: the architecture stakes all evidence authenticity (every CO-3 in every election) on a reference standard whose own authenticity is constitutionally unverifiable. If AC-31's own authenticity could be false, the entire CO-3/CO-5 certification chain may be constitutionally founded on an unverifiable assumption.

**AW-05-06:** The constitutional architecture specifies the existence of an Authenticity Root (AC-31) without specifying what makes AC-31 itself authentic. This is a self-reference gap at the foundation of the certification chain.

---

## Part D — Certification Dependency Chain

### D.1 Chain Structure

The certification dependency chain for CO-5 is not a parallel evaluation of independent components — it is a **sequential dependency structure** where each component's validity is necessary (but not sufficient alone) for CO-5:

```
AC-31 [Authenticity Root]
    ↓ provides reference
CO-3 (Evidence Authenticity)
    ↓ component of
    ↓
CO-2 (Evidence Completeness) ──┐
    [AuditScopeAuthority]       ├── all independently required (ADR6-INV-01)
CO-4 (Constitutional Compliance)│
    [ElectionConstitution]      │
                               ↓
                           CO-5 (Election Validity)
                               ↓
                           TS-1 (Terminal State)
```

**ADR3-INV-01 binding:** CO-2, CO-3, and CO-4 cannot be used to satisfy each other. Each must be independently satisfied. This binding prevents one corrupt component from borrowing validity from others — but it cannot protect against the corruption of the foundation each independently relies on:

- CO-3 relies on AC-31 (Authenticity Root)
- CO-4 relies on ElectionConstitution (Legitimacy Root)
- CO-2 relies on AuditScopeAuthority, which relies on EC Tier 1 content (ultimately: Legitimacy Root)

**Finding:** ADR3-INV-01 enforces independence *between* components but cannot protect against correlated root-level failures. The three components are independent of each other; they are not independent of their respective roots.

### D.2 TM-47 — Certification Chain Self-Reference Exploitation

*New threat — 38A-05*  
*Class: 3 (Evidence Manipulation) — Adversary: C, D (requires TM-19 precondition)*

**Description:** An adversary who has already compromised AC-31 (TM-19) exploits the certification chain's self-referential property to launder that corruption irreversibly through CO-3 → CO-5 → TS-1.

**Mechanism:**

```
STEP 1: TM-19 condition achieved — AC-31 compromised
STEP 2: CO-3 evaluation occurs against compromised AC-31
        False evidence passes CO-3 (trivially — AC-31 authenticates adversary evidence)
STEP 3: CO-4 evaluated independently (ADR3-INV-01)
        CO-4 is genuinely satisfied OR CO-4 also compromised (if EC also captured — two-root failure)
STEP 4: CO-5 issued (CO-2 + CO-3 + CO-4 apparently satisfied)
STEP 5: Challenge window opens and closes → TS-1 achieved
STEP 6: CO-5 is now constitutionally final
        The false authenticity baseline is constitutionally locked
```

**The irreversibility mechanism:** Once TS-1 is achieved, the constitutional architecture provides no mechanism to retroactively review CO-5 based on subsequently discovered AC-31 compromise. Named Attestation challenges evaluate whether CO-3 was satisfied *at the time of evaluation* — they do not evaluate whether AC-31 was valid. The authenticity ratchet (AW-05-07) then entrenches the false baseline for subsequent elections.

**This is the laundering mechanism, not the attack itself.** TM-19 is the attack; TM-47 is the constitutional property that makes TM-19's consequences permanent. TM-47 is why TM-19 is classified C-F → F rather than C-F (recoverable).

**Explicit prohibition (per ARB instruction):** TM-47 is NOT an unconditional FAIL. TM-47 requires the TM-19 precondition (adversary must have compromised AC-31). Without TM-19, the certification chain is not self-referential in a dangerous way — it is simply dependent on AC-31, which is by design.

**Classification: C-F → F (conditioned on TM-19)**

### D.3 Retroactive TS-1 Invalidity — Primary Structural Question

**The question (Part A.3 revisited):** After TS-1 is achieved using a CO-3 that relied on an AC-31 later proven to have been compromised, can TS-1 be constitutionally voided?

**Constitutional arguments for TS-1 survival (finality governs):**
1. ADR-6 explicitly designates TS-1 as constitutionally final
2. Constitutional certainty requires finality — elections must produce settled outcomes
3. Named Attestation challenge window is closed; no mechanism to reopen it
4. At the time of CO-3 evaluation, CO-3 was formally "satisfied" per the then-current AC-31

**Constitutional arguments for TS-1 invalidity (validity governs):**
1. ADR6-INV-01 states CO-5 is void unless CO-2 + CO-3 + CO-4 are *independently satisfied*
2. If AC-31 was compromised, CO-3 was not genuinely satisfied — it was only formally satisfied
3. A CO-5 issued in violation of ADR6-INV-01 may be constitutionally void *ab initio*
4. CO-5 that is void ab initio cannot achieve valid TS-1

**The irresolvable contradiction:**
- If finality governs: a constitutionally voided CO-3 can nonetheless produce a constitutionally valid CO-5 via TS-1
- If validity governs: TS-1 can be retroactively voided, but no constitutional mechanism specifies by whom, through what process, or with what legal effect

**Gap 4 creates the permanence:** Without a Constitutional Interpretation Authority, neither argument can be authoritatively resolved within the constitutional architecture. The question of which principle governs — finality or validity — has no constitutional answer. The architecture leaves elections in a state of permanent constitutional ambiguity after post-TS-1 discovery of AC-31 corruption.

**Classification of the finding:** This is NOT a threat (no adversary action creates the structural contradiction — it is constitutionally pre-existing). It is a **candidate constitutional specification gap** — the absence of a post-finality review mechanism for CO-5 issued against a false CO-3 predicate.

**OQ-38A05-02:** Can CO-5 / TS-1 be retroactively void when CO-3's predicate (AC-31) is later proven false? This is a constitutional interpretation question requiring a constitutional interpretation authority to resolve — which Gap 4 provides no mechanism for. ARB ruling requested. This may be the strongest finding in 38A-05.

**AW-05-04:** The constitutional architecture specifies TS-1 as final and CO-5 as valid only when CO-3 is independently satisfied. If CO-3's predicate was false, these two constitutional specifications are in direct contradiction for that election. No resolution mechanism exists.

---

## Part E — Evidence Forking and Reference Disagreement

### E.1 TM-49 — Evidence Set Incompatibility

*New threat — 38A-05*  
*Class: 3 (Evidence Manipulation) — Adversary: B, C, D*

**Description:** Two or more constitutional actors present mutually incompatible evidence packages for the same election. Each package satisfies CO-3 independently (each is authenticated by AC-31). ADR3-INV-01 prevents using CO-2 to satisfy CO-3, but does not require evidence uniqueness. The architecture has no constitutional mechanism to select between two internally valid but incompatible evidence packages.

**The fundamental incompatibility:** Each evidence package, independently evaluated:
- Is authenticated by AC-31 (CO-3 satisfied for each)
- Has a defined completeness scope per AuditScopeAuthority (CO-2 evaluated independently)
- Is consistent with ElectionConstitution as evaluated in CO-4

Yet the packages are mutually incompatible: they cannot both be a true record of the same election.

**Resolution paths evaluated:**

| Resolution Path | Availability | Limitation |
|---|---|---|
| CAB adjudicates | CAB adjudicates *challenges*, not evidence selection | CAB would evaluate whether CO-3 is satisfied for each — both pass; CAB cannot select between two CO-3-valid packages |
| Named Attestation | Challenges individual CO sections | Cannot invalidate a CO-3-valid package without a meta-reference above AC-31 |
| MA appeal | Terminal authority for challenge appeals | MA is not a technical evidence referee; no evidence evaluation capacity specified |
| Gap 4 resolution | No constitutional interpretation authority | Cannot authoritatively resolve which package is the "true" one |

**Classification: Candidate Threat — Downgraded from C-F**

*ARB Correction applied — 2026-06-17: TM-49 requires formal modeling of evidence identity, evidence uniqueness, evidence provenance, and evidence lineage before it can be accepted as an established constitutional threat. The analysis above argues that two evidence packages can each independently satisfy CO-3 without formally defining what makes evidence packages distinct, how provenance is established, or what "the same evidence" means across package boundaries. These are not incidental details — they are the formal preconditions on which the threat depends. Until evidence identity and uniqueness are constitutionally modeled (a Round 38B task), TM-49 remains a candidate threat identifying a structural vulnerability that would materialize if those conditions can be met and exploited.*

- Candidate condition A: Evidence identity and uniqueness formally defined (prerequisite — not yet established)
- Candidate condition B: Adversary constructs a competing internally valid evidence package under that definition
- Candidate condition C: AC-31 ambiguity (TM-45) increases probability of dual satisfaction
- Consequence (if conditions met): CO-3 simultaneously satisfied for two incompatible packages; CO-5 cannot be unambiguously issued; constitutional deadlock on evidence

**Note:** TM-49 is amplified by TM-45 (AC-31 Ambiguity) — an ambiguous AC-31 increases the probability that adversary-constructed incompatible packages can each claim CO-3 satisfaction. The two threats are synergistic.

### E.2 TM-48 — Independent Reference Disagreement

*New threat — 38A-05 (ARB-named)*  
*Class: 2 (Structural) — Adversary: D, E*

**Description:** Two independent reference standard instances, or two competing reference standards, produce different authenticity conclusions for the same evidence. The architecture specifies AC-31 as the reference standard without specifying that AC-31 must be a constitutional singleton. No constitutional provision prevents multi-instance interpretation, challenger introduction of alternative standards, or reference standard replacement through successor introduction.

**Scenarios producing TM-48:**

1. **AC-31 version divergence:** The entity holding AC-31 updates the standard; a challenger claims the updated version changes CO-3 evaluation; is the pre-update or post-update version authoritative for elections in progress?

2. **Challenger alternative standard:** A challenger presents an alternative reference standard and claims AC-31 is compromised; the alternative produces different CO-3 conclusions; which governs?

3. **Successor reference introduction:** After TM-46 (AC-31 succession failure), an emergency successor reference is introduced; the successor produces different CO-3 conclusions from what AC-31 would have produced; retroactive CO-3 assessments are disputed.

4. **Multi-jurisdiction dispute:** In a constitutional architecture potentially serving multiple organizations (NRNA multi-tenancy context), different tenants' AC-31 interpretations diverge; cross-tenant elections produce incompatible CO-3 conclusions.

**Resolution failure:** CAB cannot adjudicate without a meta-reference — to determine which AC-31 is "correct," CAB would need a reference for the reference standard. No such meta-reference is specified. TM-48 produces a reference-level deadlock that the challenge architecture is constitutionally incapable of resolving.

**Classification: C-F**
- Condition: Architecture permits multi-instance reference standards or alternative challenge reference standards (structural — no singleton enforcement in Gap 5)
- Adversary action required to trigger active disagreement (version manipulation, alternative introduction)
- Consequence: CO-3 produces non-deterministic results; CO-5 cannot be unambiguously issued

**OQ-38A05-03:** Should AC-31's singleton status be constitutionally enforced to prevent TM-48? Note the paradox: singleton enforcement would itself become a governance requirement for AC-31 — which Gap 5 (no governance specified) currently prevents. Enforcing singleton status may require resolving Gap 5. ARB ruling requested.

### E.3 OA-01 Interaction with CO-3 Challenge Path

*Carry-forward from AW-04-08 — deepened for CO-3*

CO-3 (Evidence Authenticity) is the certification object that most directly depends on AC-31. It is also the certification object with the lowest practical challengeability when OA-01 is unresolved.

**The CO-3 challenge dependency chain:**
1. Challenger must access the evidence to challenge CO-3 (requires OA-01 resolution)
2. Challenger must access AC-31 to evaluate what authentic evidence should look like (requires AC-31 governance — Gap 5 partial barrier)
3. Challenger must compare evidence against AC-31 and demonstrate discrepancy (requires both 1 and 2)
4. Challenge is submitted to CAB via Named Attestation

**OA-01 creates a practical floor:** If OA-01 is unresolved, step 1 fails — challengers may know that evidence exists but cannot access it to compare against AC-31. CO-3 Named Attestation is constitutionally available (the mechanism exists in ADR-6) but operationally inaccessible.

**The asymmetry finding:** CO-3 has the most sophisticated constitutional challenge mechanism (Named Attestation, individually challengeable, ADR-6 structured) and the lowest practical challenge success probability (OA-01 blocks evidence access; AC-31 has no governance for challengers to reference; TM-48 reference disagreement may be raised as defense).

**AW-05-02:** OA-01 is load-bearing for CO-3 challengeability. Without OA-01 resolution, CO-3 Named Attestation is a constitutional framework with no operational path to execution.

---

## Part F — Cross-Election Authenticity Failure

### F.1 Retroactive CO-3 Invalidation Across Election Cycles

If AC-31 is compromised and later discovered to have been compromised, every CO-3 issued against the compromised AC-31 is potentially retroactively invalid. This affects:

- All elections that have achieved TS-1 while AC-31 was compromised
- All CO-5 certifications issued during the compromise period
- The constitutional validity of all election results during that period

**The retroactive scope of Gap 5:** Because AC-31 has no governance, no detection mechanism, and no succession mechanism (Gap 5), the period of compromise may be unknown — the architecture has no mechanism to determine when AC-31 was first compromised. If AC-31 was compromised from deployment, all elections conducted under that reference standard are potentially constitutionally suspect.

**Blast radius temporal extension:** TM-19 (AC-31 capture) was classified C-F → F. The temporal extension reveals that C-F → F is not merely a future-elections classification — it is also a retroactive classification. Every election conducted under a compromised AC-31 is retrospectively C-F → F.

This extends the blast radius beyond the cross-election property identified in 38A-03 E.4. The concentration ranking (AC-31 > GovernanceState > CAB > CA) understated AC-31's impact in one dimension: it is not merely retroactive across elections; the extent of its retroactive reach is unknowable without detection infrastructure that does not exist.

### F.2 Certification Survivability After AC-31 Compromise

**Question:** If AC-31 is discovered to be compromised after elections have been conducted, which elections can claim surviving certification?

| Election State | CO-5 Status After AC-31 Compromise Discovery |
|---|---|
| In progress (CO-3 not yet evaluated) | CO-3 evaluation postponed pending AC-31 reconstitution; ADR6-INV-01 prevents CO-5 issuance |
| CO-3 issued, CO-5 not yet issued | CO-3 is retroactively invalid; CO-5 cannot be issued per ADR6-INV-01 |
| CO-5 issued, challenge window open | CO-3 invalidity constitutes a valid challenge basis; Named Attestation CO-3 challenge filed; outcome depends on CAB + OA-01 resolution |
| TS-1 achieved (challenge window closed) | **Primary structural question — Part D.3 and OQ-38A05-02: No constitutional mechanism to address** |

**Finding:** Only elections at TS-1 state are "safe" from the normal CO-3 challenge mechanism. But TS-1 safety depends on the finality principle prevailing over the validity principle — which Gap 4 cannot resolve.

### F.3 Historical Authenticity Disputes

**Scenario:** AC-31 compromise is discovered three election cycles after the compromised standard was introduced.

Constitutional questions arising:
- Which election cycle's results are constitutionally valid?
- Can results from Cycle N (TS-1 achieved) be the basis for constitutional actions in Cycle N+1?
- If MA membership is determined by Cycle N results, and Cycle N results are constitutionally suspect, is Cycle N+1's MA constitutionally legitimate?

**The Legitimacy Root contamination path:** AC-31 compromise → CO-3 invalid → CO-5 retroactively suspect → election results constitutionally uncertain → MA composition uncertain → EC legitimacy uncertain → all authority aggregates' legitimacy uncertain → the Legitimacy Root itself is undermined by Authenticity Root failure.

This is the multi-cycle version of two-root joint validation failure (Part B.4). In this scenario, Authenticity Root failure propagates backward through elections into Legitimacy Root contamination. The effect compounds across election cycles through the membership composition chain.

**AW-05-08:** CO-4 independent evaluation (constitutional compliance) does not protect against Legitimacy Root contamination by Authenticity Root failure. CO-4 can evaluate CO-4 correctly while the elections that determined the EC's authors were built on false CO-3 foundations.

---

## Part G — Certification Concentration Comparison

### G.1 CA vs AC-31 — Blast Radius, Recoverability, Challengeability

| Criterion | CertificationAuthority (CA) | AC-31 (Authenticity Root) |
|---|---|---|
| **Constitutional function** | Produces CO-5 — terminal certification act | Provides reference for CO-3 — foundation for certification |
| **Blast radius** | One election per certification act | All elections (retroactive + prospective) |
| **Recoverability** | Challenge + recertification (with independent CAB + OA-01 resolved) | **NONE** (Gap 5) |
| **Detectability** | Named Attestation provides CO-2/CO-3/CO-4 individually challengeable challenge framework | **NONE** (Gap 5 — no governance body, no monitoring, no external verification) |
| **Challengeability** | HIGH — ADR-6 explicitly designed to challenge CO-5 via Named Attestation | **NONE** — Named Attestation challenges CO-3 compliance with AC-31, not AC-31's own validity |
| **Succession** | ADR7-INV-01 specifies suspension succession | **NONE** (Gap 5) |
| **Governance** | ADR-2: External Organization independence; AC-27 external challenge reception | **NONE** (Gap 5) |
| **Single-point classification** | C-F (with challenge path; recoverable theoretically) | C-F → F (TM-19; once triggered: no recovery) |

### G.2 Key Finding — Qualitative Difference in Risk Type

CA and AC-31 are not just different in scale — they are different in **risk type**:

- **CA's concentration risk** is almost entirely addressable within the constitutional challenge architecture. ADR-6 was specifically designed to make CA's terminal act challengeable. The certification architecture (ADR-6) treats CA as the expected concentration point and builds systematic challenge infrastructure against it.

- **AC-31's concentration risk** is entirely outside the challenge architecture. Gap 5 means AC-31 has no place in the challenge architecture at all. The architecture that protects against CA failure is itself dependent on AC-31 — Named Attestation challenges CO-3 using AC-31 as the reference. A compromised AC-31 undermines the challenge mechanism designed to catch CA errors.

**Finding:** CA and AC-31 are not comparable on a single concentration ranking axis. They represent two different categories of concentration:
- CA: concentrated authority addressable by distributed challenge
- AC-31: concentrated reference unaddressable by any existing mechanism

### G.3 Updated Authenticity Concentration Ranking

Within the authenticity and certification domain:

**AC-31 > CA** — confirming the existing concentration ranking (38A-03 E.4, updated per 38A-04-ARB-Review C-4)

This holds across all three criteria examined:
- AC-31 blast radius exceeds CA by factor of all elections vs one election
- AC-31 recoverability: none; CA recoverability: theoretical
- AC-31 challengeability: none; CA challengeability: structured (ADR-6)

**Note:** The 38A-03/38A-04 concentration ranking (AC-31 > GovernanceState > CAB > CA) is confirmed and consistent with this analysis.

---

## Part H — Required Deliverables

### H.1 Authenticity Trust Chain

```
[Membership Assembly — Source-of-Source Candidate]
    ↓ (AA-01 — assumption, not verified)
[ElectionConstitution — Legitimacy Root]
    ↓ L-1 authority
[7 D43 Authority Aggregates]
    ↓ governs
[AC-31 — Authenticity Root]       [AC-31 legitimacy chain: ABSENT (Gap 5)]
    ↓ reference standard
[CO-3: Evidence Authenticity]      [CO-3 challengeability: theoretical only (OA-01 unresolved)]
    ↓ component (ADR6-INV-01)
[CO-5: Election Validity]
    ↓ terminal act
[TS-1: Constitutionally Final]

⟵ retroactive: AC-31 compromise invalidates all prior CO-3 assessments
                (extent of retroactive reach: UNKNOWABLE — no detection mechanism)

GAPS: Gap 5 (AC-31 governance) / Gap 4 (no post-TS-1 invalidity resolution)
FAIL PATH: TM-19 + TM-47 = C-F → F with ratchet (AW-05-07)
SUCCESSION PATH: TM-46 = C-F (Availability Catastrophe — election suspended, not void; constitutional actors remain functional)
```

### H.2 Certification Dependency Graph

```
ElectionConstitution [Legitimacy Root]
    ├── L-1 for D43 Authority Aggregates
    │       ├── AuditScopeAuthority → CO-2 (Evidence Completeness)
    │       ├── CertificationAuthority → CO-5 (Election Validity — terminal)
    │       └── [all 5 D43 functions]
    └── Constitutional rules evaluated in CO-4 (Constitutional Compliance)

AC-31 [Authenticity Root]                    ← Gap 5 (no governance)
    └── Reference for CO-3 (Evidence Authenticity)

GovernanceState [Temporal Root]              ← Candidate Gap 7 (no governance)
    └── Phase validity → determines when CO-3/CO-4 evaluations are constitutionally authorized

CO-2 (Evidence Completeness)        ← AuditScopeAuthority-dependent
CO-3 (Evidence Authenticity)        ← AC-31-dependent [weakest in chain: OA-01 + Gap 5]
CO-4 (Constitutional Compliance)    ← EC-dependent [CO-4 masks EC corruption]

CO-5 (Election Validity)  ← requires CO-2 + CO-3 + CO-4 (ADR6-INV-01)
    ↓
TS-1 (Terminal State)     ← IRRESOLVABLE if CO-3 predicate was false (OQ-38A05-02)
```

### H.3 Evidence Failure Matrix

| AC-31 State → | Valid | Ambiguous (TM-45) | Compromised (TM-19) | Unavailable (TM-46) |
|---|---|---|---|---|
| **Evidence: Authentic** | CO-3 ✓ | CO-3 contested | CO-3 formally ✓ (false) | CO-3 impossible |
| **Evidence: Forged** | CO-3 ✗ | CO-3 contested | CO-3 formally ✓ (critical failure) | CO-3 impossible |
| **Evidence: Incompatible set (TM-49)** | CO-3 deadlock | CO-3 amplified deadlock | CO-3 formally ✓ for both (critical) | CO-3 impossible |
| **Reference disagreement (TM-48)** | Non-deterministic | Amplified non-determinism | Both references compromised | Meta-reference needed |

**Critical cell:** Evidence Forged + AC-31 Compromised = CO-3 formally satisfied with forged evidence. This is the TM-19 + TM-47 FAIL path. Constitutional architecture cannot distinguish this from genuine CO-3 satisfaction.

### H.4 Authenticity Concentration Ranking

Within the authenticity domain, with comparative notes:

| Rank | Concentration Point | Domain | Blast Radius | Challengeability | Recoverability |
|---|---|---|---|---|---|
| 1 | AC-31 | Authenticity Root | ALL elections retroactive + prospective | NONE | NONE |
| 2 | CertificationAuthority (CA) | Certification output | One election/cycle | HIGH (ADR-6 Named Attestation) | Theoretical (CAB + OA-01) |

**Cross-concentration comparison (authenticity domain only):** AC-31 > CA by all three criteria.

**Note:** This ranking is authenticity-domain specific. The overall operational concentration ranking (AC-31 > GovernanceState > CAB > CA) applies across all domains and remains valid.

### H.5 Certification Failure Catalog

| Threat/Finding | Classification | Path | Primary Weakness |
|---|---|---|---|
| TM-19 (AC-31 Capture) | C-F → F | TM-19 + TM-47 → TS-1 locks | Gap 5 |
| TM-45 (AC-31 Ambiguity) | C-F | Ambiguous AC-31 → contested CO-3 | Gap 5 + Gap 4 |
| TM-46 (AC-31 Succession Failure) | **C-F (Availability Catastrophe)** [ARB Correction] | AC-31 unavailable → CO-3 impossible → elections suspended (not void); actors remain functional | Gap 5 |
| TM-47 (Chain Self-Reference Exploitation) | C-F → F (via TM-19) | TM-19 precondition → CO-3 → CO-5 → TS-1 irreversible | AW-05-07 (ratchet) |
| TM-48 (Reference Disagreement) | C-F | Multi-reference → CO-3 non-deterministic | Gap 5 (no singleton enforcement) |
| TM-49 (Evidence Set Incompatibility) | **Candidate Threat** [ARB Correction — evidence identity/uniqueness not yet formally modeled] | Candidate: two CO-3-valid incompatible packages → CO-5 ambiguous | No evidence uniqueness requirement |
| Retroactive TS-1 Invalidity | Candidate Gap (OQ-38A05-02) | CO-3 false predicate → TS-1 ambiguous validity | AW-05-04 (no post-finality review) |
| Three-root simultaneous failure | C-F → F (observation — no adversary required to sustain once achieved) | EC + AC-31 + GovernanceState all compromised → CO-5 undetectably false | No cross-root verification |

---

## Part I — FAIL Findings Summary

### I.1 New FAIL-Class Findings (38A-05)

**TM-19 + TM-47 (deepened mechanism) = C-F → F (maintained)**
TM-19 FAIL path confirmed with explicit mechanism: TM-47 explains why TM-19 produces F consequences rather than recoverable C-F. The authenticity ratchet (AW-05-07) ensures TS-1 achievement locks false authenticity baseline permanently.

**Note on TM-46 — ARB Correction 2026-06-17:** TM-46 was initially submitted as C-F → F. ARB correction reclassifies it as **C-F (Availability Catastrophe)**: election cannot proceed (availability failure), but no constitutional void, no constitutional invalidity, no capture. Constitutional actors remain functional throughout — the absence of a required resource (AC-31) differs fundamentally from the absence of the recovery actors themselves (TM-42 Complete Deadlock). TM-46 is a significant architectural weakness but does not add a new FAIL-class finding. Removed from the running catalog. OQ-38A05-01 remains open on whether permanent loss approaches unconditional F.

### I.2 Running FAIL-Class Catalog Updated (38A-01 through 38A-05)

| # | Source | Threat / Finding | Classification |
|---|---|---|---|
| 1 | 38A-03 | CA + CAB coalition | **F** |
| 2 | 38A-03 | EC + CA / TM-06 full | **F** |
| 3 | 38A-03 SC1 | GA + ASA + CAB | **F** |
| 4 | 38A-03 SC2 | TM-39 Independence Illusion (Adversary D) | **F** |
| 5 | 38A-03 | TM-19 AC-31 capture | **C-F → F** |
| 6 | 38A-04 ARB-Review | TM-42 Complete Deadlock | **F (unconditional)** |
| 7 | 38A-04 | TM-42 Partial Deadlock | **C-F → F** |
| 8 | 38A-04 | TM-43 + TM-42 Complete (chain) | **F** |
| 9 | 38A-05 | TM-19 + TM-47 (chain mechanism deepened) | **C-F → F** (mechanism clarified) |

**Running FAIL-class count: 7 distinct F or C-F → F findings across 38A-01 through 38A-05.**

*Note: TM-46 reclassified to C-F (Availability Catastrophe) per ARB Correction 2026-06-17 — removed from FAIL-class catalog. TM-49 downgraded to Candidate Threat per ARB Correction 2026-06-17 — never entered FAIL-class catalog.*

---

## Part J — Architectural Weakness Register (38A-05)

| ID | Description | Threat(s) | Severity |
|---|---|---|---|
| AW-05-01 | No constitutional minimum clarity standard for AC-31 (asymmetry: ADR6-CONSTRAINT-01 specifies challenge window floor; no equivalent for Authenticity Root) | TM-45 | Critical |
| AW-05-02 | CO-3 challenge path requires OA-01 resolution; OA-01 currently unresolved; CO-3 Named Attestation is constitutionally available but operationally inaccessible | TM-19, TM-47 | Critical |
| AW-05-03 | AC-31 singleton status not constitutionally enforced; no provision prevents multi-instance interpretation or challenger introduction of alternative reference standards | TM-48 | High |
| AW-05-04 | No post-finality review mechanism for CO-5 issued against a false CO-3 predicate; finality principle (TS-1) and validity principle (ADR6-INV-01) are in direct contradiction when CO-3 predicate is later proven false | TM-19 + TM-47 | Critical |
| AW-05-05 | Three-root simultaneous compromise (EC + AC-31 + GovernanceState) produces constitutionally undetectable false CO-5; no cross-root verification mechanism exists | Three-root analysis | Critical |
| AW-05-06 | Constitutional architecture specifies an Authenticity Root (AC-31) without specifying what makes AC-31 itself authentic; the "authenticator authentication" problem is constitutionally unresolved | TM-19, TM-46, TM-48 | Critical |
| AW-05-07 | AC-31 authenticity ratchet — each CO-5 issued against compromised AC-31 further entrenches false authenticity baseline without requiring additional adversary action; structurally more dangerous than AW-03-11 (EC ratchet) because it operates automatically through the certification process | TM-19, TM-47 | Critical |
| AW-05-08 | CO-4 independent evaluation masks Legitimacy Root contamination by Authenticity Root failure; cross-election chain where false CO-3 → false election results → false MA composition → EC amendments by false MA can produce Legitimacy Root corruption through Authenticity Root failure | Multi-cycle analysis | High |

---

## Part K — Observations and Open Questions

### K.1 Observations

**OBS-38A05-01: CO-5 is the only constitutional act requiring all three trust roots simultaneously**
CO-4 requires the Legitimacy Root (EC); CO-3 requires the Authenticity Root (AC-31); GovernanceState phase validity determines when CO-3/CO-4 evaluations are constitutionally authorized. CO-5 inherits risk from all three roots simultaneously. CO-5 is therefore the highest-risk constitutional artifact for simultaneous root failure.

**OBS-38A05-02: AC-31 has no constitutional equivalents to any D43 authority aggregate protections**
ADR-2 specifies constitutional independence for each D43 function. ADR-5 specifies a challenge mechanism for each. ADR-7 specifies suspension succession for each (ADR7-INV-01). ADR-6 designates what they certify and how. AC-31 has none of these constitutional specifications. The seven D43 aggregates are more constitutionally protected than the Authenticity Root they depend on.

**OBS-38A05-03: Membership Assembly as source-of-source of all three trust roots**
If MA legitimacy fails (AA-01 unresolved), all three roots may be simultaneously de-legitimized. AA-01 is the highest-priority assumption in the register (38A-01 G.1). Its significance extends beyond TM-07 (MA Capture) — it is the single point whose failure could simultaneously de-legitimize all three roots, all seven D43 aggregates, AC-31's governance (whatever body would govern it), and CO-5 itself. Carry to 38A-06 pre-constitutional exposure synthesis.

**OBS-38A05-04: Three-root simultaneous failure produces constitutionally undetectable false CO-5**
When EC, AC-31, and GovernanceState are simultaneously compromised, CO-2, CO-3, and CO-4 each appear independently satisfied. CO-5 is issued. TS-1 is achieved. The election result is constitutionally valid in every observable dimension. No detection mechanism exists within the constitutional architecture for this condition. This is the maximum adversarial scenario — and its undetectability is structural, not merely difficult.

**OBS-38A05-05: AC-31 authenticity ratchet is more automatic than EC constitutional ratchet**
AW-03-11 (EC constitutional ratchet) requires MA to ratify amendments — an act that could theoretically be detected or resisted. AW-05-07 (AC-31 authenticity ratchet) operates automatically through CO-5 issuance — no deliberate act is required. Each certification cycle entrenches the false authenticity baseline without any constitutional actor choosing to do so. This makes AC-31 ratchet structurally more dangerous: it cannot be prevented even by fully honest actors if AC-31 is already compromised.

### K.2 Open Questions

**OQ-38A05-01:** Is TM-46 (permanent loss of AC-31 entity/mechanism) an unconditional F parallel to TM-42 Complete Deadlock? Non-adversarial trigger possible; no recovery mechanism; no constitutional floor; single-point failure producing CO-3 impossibility across all elections. ARB ruling requested.

**OQ-38A05-02:** Can CO-5 / TS-1 be retroactively void when CO-3's predicate (AC-31) is later proven false? The finality principle (ADR-6 TS-1) and validity principle (ADR6-INV-01) are in direct contradiction. Gap 4 prevents authoritative resolution. This may be the most consequential unresolved question in 38A. ARB ruling requested — this may require formal constitutional specification to resolve.

**OQ-38A05-03:** Should AC-31's constitutional singleton status be enforced to prevent TM-48? Enforcement requires governance specifications for AC-31 — which Gap 5 means are absent. Enforcing singleton status may require Gap 5 resolution as a prerequisite. Raises the question: does preventing TM-48 require resolving Gap 5 (AC-31 governance)? ARB ruling requested.

**OQ-38A05-04:** Does Membership Assembly function as source-of-source for all three trust roots (Legitimacy, Authenticity, Temporal)? If so, does AA-01 (MA Legitimacy Foundation — currently rated as the highest-priority assumption) need to be elevated from "highest-priority assumption" to "pre-constitutional root" in the 38A-06 synthesis? Carry to 38A-06.

**OQ-38A05-05:** Should a new candidate gap be formally named — "Post-Finality Constitutional Review Absence" (Gap 8 candidate) — to capture the absence of a mechanism for retroactive review of CO-5 issued against a false CO-3 predicate? Or is this adequately covered by Gap 4 (Constitutional Interpretation Authority)? The distinction: Gap 4 covers general interpretation disputes; this specific gap covers finality doctrine in the presence of subsequently discovered false predicates. ARB ruling requested.

**OQ-38A05-06:** Who certifies the certifiers? The constitutional architecture designates three trust roots as the foundations of constitutional validity, but provides no mechanism by which the trust roots themselves are validated or certified. ElectionConstitution (Legitimacy Root) receives candidate external validation via Membership Assembly (OBS-ADR7-SS1 — MA as source-of-source; evaluated, not established). AC-31 (Authenticity Root) receives no external validation whatsoever — Gap 5 specifies complete absence of governance, accountability, challenge mechanism, or legitimacy chain for AC-31. GovernanceState (Temporal Root) receives no external validation — Candidate Gap 7. The constitutional architecture certifies elections through roots that are themselves uncertified. Whether this constitutes a structural gap requiring resolution or an acceptable constitutional bedrock assumption is a synthesis question for 38A-06.

---

## Part L — ARB Decision Block

**Status:** SUBMITTED FOR ARB REVIEW

**Date:** 2026-06-17

**New Threats Introduced:**
- TM-45: Authenticity Root Ambiguity — C-F
- TM-46: Authenticity Root Succession Failure — **C-F (Availability Catastrophe)** [ARB Correction: reclassified from C-F → F; election suspended, not void; constitutional actors remain functional]
- TM-47: Certification Chain Self-Reference Exploitation — C-F → F (conditioned on TM-19)
- TM-48: Independent Reference Disagreement (ARB-named) — C-F
- TM-49: Evidence Set Incompatibility — **Candidate Threat** [ARB Correction: downgraded from C-F; evidence identity/uniqueness/provenance not yet formally modeled]

**Findings Summary:**
1. TM-46 reclassified as C-F (Availability Catastrophe) per ARB Correction — election suspended (availability failure), not constitutionally void; constitutional actors remain functional; removed from FAIL-class catalog; OQ-38A05-01 remains open
2. TM-49 downgraded to Candidate Threat per ARB Correction — formal modeling of evidence identity, uniqueness, provenance, and lineage required before threat acceptance
3. TM-19 + TM-47 FAIL path mechanism is now explicit: the authenticity ratchet (AW-05-07) makes TM-19 irreversible; this is structurally more dangerous than AW-03-11 (EC ratchet)
3. Retroactive TS-1 invalidity is the most consequential open question in 38A-05: finality principle and validity principle are in direct constitutional contradiction; Gap 4 prevents resolution
4. Three-root simultaneous failure (EC + AC-31 + GovernanceState) produces constitutionally undetectable false CO-5 — the maximum adversarial scenario
5. CO-3 challengeability is theoretically highest (ADR-6 Named Attestation) and practically lowest (OA-01 unresolved; Gap 5 blocks challenger access to AC-31 reference)
6. AC-31 authenticity ratchet (AW-05-07) operates without deliberate constitutional act — more automatic and more dangerous than EC constitutional ratchet (AW-03-11)
7. Membership Assembly as candidate source-of-source of all three trust roots — if AA-01 fails, simultaneous de-legitimization of all three roots is possible

**Authorization Requests (for 38A-05 findings to be formally recorded):**
1. Authorize TM-45 through TM-49 for Master Threat Catalog (38A-01 F.5b update)
2. Rule on OQ-38A05-01: TM-46 — unconditional F or C-F → F?
3. Rule on OQ-38A05-02: Retroactive TS-1 invalidity — which constitutional principle governs (finality vs validity)?
4. Rule on OQ-38A05-03: AC-31 singleton enforcement — does preventing TM-48 require Gap 5 resolution?
5. Rule on OQ-38A05-05: Candidate Gap 8 (Post-Finality Constitutional Review Absence) — or subsumed by Gap 4?
6. Confirm or modify: AC-31 > GovernanceState > CAB > CA concentration ranking (38A-04-ARB-Review C-4 confirmed here)

**Structural Observations for 38A-06:**
- Three trust roots (Legitimacy, Authenticity, Temporal) established; CO-5 as three-root convergence
- AC-31 authenticity ratchet identified as more dangerous than EC constitutional ratchet
- MA as candidate source-of-source requiring 38A-06 pre-constitutional exposure synthesis
- TM-07 and TM-35 carry-forward mandatory (from 38A-02 — equal visibility in 38A-06)

**Round 38A Program Status:**
*38A-01 APPROVED WITH STRATEGIC CORRECTIONS*  
*38A-02 APPROVED WITH TARGETED CORRECTIONS APPLIED*  
*38A-03 APPROVED WITH STRATEGIC CORRECTIONS APPLIED*  
*38A-04 APPROVED WITH CORRECTIONS (ARB-Review issued)*  
*38A-05 APPROVED WITH CORRECTIONS APPLIED ← current*  
*38A-06 — Consolidated Assessment (Authorization Pending)*  
*Next Phase (after 38A-06): Round 38B — Technical Architecture — AUTHORIZATION NOT YET GRANTED*

---

## Part M — Research Mode Discipline (Binding for 38A-06)

*Senior DDD Architect — 2026-06-17. These rules are binding for Round 38A-06 and all subsequent rounds in the 38A series.*

### M.1 Ten Research Mode Rules

**Rule 1 — Distinguish finding types rigorously.**
Every finding must be classified as: Threat (adversary action required), Architectural Weakness (structural), Constitutional Gap (specification absent), Concentration Point (blast radius), or Observation (non-actionable). Mixed categories invalidate the finding's classification.

**Rule 2 — FAIL only with full proof chain.**
A FAIL classification requires: (a) the precondition is constitutionally possible, (b) the trigger is constitutionally achievable, (c) the consequence is constitutionally irreversible, and (d) no recovery mechanism exists within the architecture. Possibility arguments alone do not establish FAIL.

**Rule 3 — No threats for unmodeled components.**
AC-31 has no defined bounded context, aggregate, data composition, events, or invariants in the formal DDD model. Threats that assume AC-31's internal structure, content, or behavior are speculative — mark them as candidates until the formal model exists (Round 38B).

**Rule 4 — Distinguish trust root types.**
Trust Roots (Legitimacy, Authenticity, Temporal) are constitutional/threat-modeling abstractions. They are not DDD aggregate roots, bounded contexts, or architecture components. Never conflate the threat-modeling abstraction with the DDD artifact. Formal mapping is a Round 38B task.

**Rule 5 — Availability catastrophe ≠ constitutional FAIL.**
A failure mode that suspends elections (nothing can proceed) but leaves constitutional actors intact and constitutional validity uncompromised is an availability catastrophe, not a constitutional FAIL. Classification as F or C-F → F requires that either (a) constitutional invalidity is produced, or (b) the actors needed to authorize recovery are themselves unavailable.

**Rule 6 — Prefer discovery over design.**
Round 38A's mandate is to identify threats, gaps, and weaknesses — not to propose resolutions. When a finding appears to imply a mitigation, record the gap and carry the resolution question to 38A-06 or 38B. No in-round design decisions.

**Rule 7 — Candidate threats must be explicitly marked and gated.**
A Candidate Threat is not an accepted threat. It requires stated formal preconditions that must be established before the threat can be accepted. Candidate status means "this could be a threat if X is demonstrated" — not "we believe this is a threat."

**Rule 8 — Implementation risks belong in Round 38B.**
Race conditions, software bugs, version mismatches, operational failures, certificate expiration, and environmental dependencies are implementation risks. They belong in the technical architecture (38B) or implementation threat modeling — not in constitutional threat catalogs.

**Rule 9 — Constitutional questions require OQs, not in-round resolution.**
When constitutional analysis reveals that two constitutional principles conflict, or that a constitutional question has no answer within the current architecture, record this as an Open Question (OQ) and carry it to the appropriate round for ARB ruling. Do not resolve in-round.

**Rule 10 — Ask "Who certifies the certifiers?" at every trust root.**
Before finalizing any trust root analysis, explicitly identify what validates the root itself. If no external validator is specified, this is a structural observation that must appear in the document (as Gap, Weakness, or OQ). Uncertified roots are architectural hypotheses, not proven constitutional artifacts. See OQ-38A05-06.

### M.2 AC-31 Modeling Prerequisites for 38A-06

Before any Round 38A-06 or 38B finding can treat AC-31 as a formally modeled artifact, the following must be established:

1. **Bounded context:** What bounded context owns or is responsible for AC-31?
2. **Aggregate identity:** Is AC-31 an aggregate root? A reference data object? A service interface? A policy?
3. **Data composition:** What data does AC-31 contain? What structure?
4. **Domain events:** What events does AC-31 produce or consume?
5. **Invariants:** What invariants must AC-31 satisfy?
6. **Authority relationship:** Which D43 authority aggregate governs AC-31?
7. **Succession model:** What is the constitutional succession model for AC-31 if formalized?

Until these prerequisites are established, AC-31 remains an architectural hypothesis. Threats dependent on AC-31's internal behavior (not merely its presence/absence as a reference) are candidates, not established constitutional findings.
