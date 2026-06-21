# Round 37-05 — ADR-5: Challenge Architecture

**Program:** NRNA DDD Trustworthiness Research Program
**Round:** 37 — ADR Authoring
**Document:** ADR-5 of 7
**Status:** APPROVED — Required Revisions Applied (2026-06-16)
**Governing Question:** What is the constitutional challenge model — who may initiate, who receives, who adjudicates, what evidence is admissible, what remedies exist, and can adjudication itself be challenged?

**Predecessors:**
- ADR-1 — Authority Vocabulary and Authority Source Model — APPROVED
- ADR-2 — Independence Form per D43 Function — APPROVED
  Key inheritance: GOV-AUTH challengeability carried to ADR-5; all six authority functions have L-3 requirement
- ADR-3 — Evidence and Verifier Architecture — APPROVED
  Key inheritance: CT-1 conditional; VO-3/EC-01 interaction; AC-09/AC-10
- ADR-4 — Audit Scope Authority Structure — SUBMITTED FOR ARB REVIEW
  Key inheritance: AuditScopeAuthority L-3 requirement; AuditExecutionAuthority L-3 requirement; OQ-37-04-02 (Tier 1/Tier 2 adjudication question)

**Scope:** Conceptual architecture only. No challenge workflow implementation. No API design. No technical process specification. Challenge architecture = the constitutional authority model governing who has standing to initiate, receive, and adjudicate challenges, what evidence counts, and what remedies are available — not the technical mechanisms by which these are executed.

---

## Part A — Problem Statement

### A.1 The Six Governing Questions

ADR-5 must answer six constitutional questions about challenges:

1. **Initiation:** Who has constitutional standing to initiate a challenge?
2. **Reception:** Who receives a challenge (and must be independent of the challenged authority)?
3. **Adjudication:** Who adjudicates a challenge, and what is their constitutional authority?
4. **Recursion:** Can adjudication decisions themselves be challenged, and if so, where does the recursion terminate?
5. **Evidence:** What evidence is constitutionally admissible in a challenge proceeding?
6. **Remedy:** What remedies are constitutionally available, and what conditions must be met before each remedy may be granted?

These questions apply to all six authority aggregates:
- EnrollmentAuthority
- CriteriaAuthority
- AuditScopeAuthority (new — ADR-4)
- AuditExecutionAuthority
- GovernanceAuthority
- CertificationAuthority

### A.2 Challenge Authority Model vs. Challenge Workflow

ADR-5 must distinguish between:
- **Challenge Authority Model** (THIS ADR): who has constitutional standing and authority at each stage; how authority boundaries are drawn; what constitutional constraints apply
- **Challenge Workflow** (Round 38+): the operational process by which challenges are initiated, received, routed, and resolved; technical mechanisms; notification processes; timelines

Conflating these risks designing a technically coherent workflow that lacks constitutional authority grounding — a behavioral integrity failure of the type CF-05-08 identified as a program-level risk. Challenge workflows derive from challenge authority models, not the reverse.

### A.3 Binding Inputs

From 36E-01 constraints:

| ID | Constraint | ADR-5 Relevance |
|---|---|---|
| AC-02 | No authority function may be architecturally self-verifying | The challenged authority may not adjudicate its own challenge |
| AC-09 | Challenge reception must be structurally independent of the challenged authority | Reception architecture constraint — direct |
| AC-10 | Challenge process must be structurally independent | Not merely procedurally independent; structural independence required |
| AC-11 | Authority lifecycle must be explicit | L-4 revocation pathway connects to challenge remedy architecture |
| AC-12 | Revocation authority must come from outside | Remedy "Invalidate" must come from outside the challenged authority |
| AC-13 | Succession must be explicitly defined | If a challenged authority is suspended, succession for that period must exist |

From ADR-2 (carried):
- GOV-AUTH challengeability: can GovernanceAuthorizationCommittee decisions be challenged? Carried from ADR-2 Revision 2 — ADR-5 resolves
- All five ADR-2 authority aggregates have L-3 requirements that ADR-5 must design

From ADR-3 (carried):
- CT-1 conditional: active if Authenticity Stratum implementation uses cryptographic commitment — challenge architecture must address voter-level challenges without breaking the authenticity model
- VO-3/EC-01 interaction: VO-3 (ReceiptHash) as the candidate mechanism for vote-level challenges; EC-01 tension (challenge addressability vs. receipt-freeness)

From ADR-4 (carried):
- AuditScopeAuthority L-3: scope specifications must be challengeable through constitutional process
- AuditExecutionAuthority L-3: audit findings must be challengeable
- OQ-37-04-02: who determines whether a Tier 2 item exceeds delegated Tier 1 authority? — L-3 challenge mechanism or architectural design question? ADR-5 answers this

### A.4 The Adjudication Recursion Risk

The highest-risk question in ADR-5 is Question 4: can adjudication decisions themselves be challenged, and if so, who adjudicates the challenge to the adjudicator? This is a constitutional recursion risk analogous to ET-03 in ADR-4 and TC3-NCQ-04 ("Who governs the governors?") from 36C-04.

If the answer is "yes, adjudication decisions can be challenged, and another adjudicator handles that," then the recursion continues infinitely unless a constitutional terminal point is established. ADR-5 must establish that terminal point.

---

## Part B — Constitutional Evidence Inherited

### B.1 AC-09 and AC-10: The Independence Constraint

**AC-09:** Challenge reception must be structurally independent of the challenged authority.

This means: the body or mechanism that receives a challenge may not be the challenged authority itself, nor may it be under the control of the challenged authority. A committee governed by the same authority it is receiving challenges for does not satisfy AC-09.

**AC-10:** The challenge process must be structurally independent.

This extends AC-09 beyond reception to the entire process. The process design must not create structural dependencies that the challenged authority can exploit to delay, modify, or suppress the challenge.

**Implication:** ADR-5 cannot design a challenge process where any stage — initiation, reception, adjudication, or remedy execution — is structurally controlled by the challenged authority.

### B.2 EC-01 — Challenge Addressability vs. Receipt-Freeness

From 36E-02: EC-01 is an unresolved constitutional tension between:
- **Challenge Addressability:** voters and participants must be able to effectively challenge how their participation was processed
- **Receipt-Freeness:** voters must not be able to prove how they voted to a third party (coercion risk)

If a voter can challenge their vote's processing by presenting a receipt showing how they voted, that receipt becomes a coercion instrument.

ADR-5 must resolve whether EC-01 represents:
- A **Type D tension** (structural tension requiring architectural resolution), or
- A **Type E conflict** (incompatibility requiring one principle to yield to the other)

This determination drives the vote-level challenge model.

### B.3 GOV-AUTH Challengeability (from ADR-2)

ADR-2 Revision 2 carried: "GovernanceAuthorizationCommittee independent challengeability" to ADR-5. The specific question is whether GovernanceAuthority's authorization decisions can be challenged, and if so, whether the challenge adjudicator is truly independent of GovernanceAuthority.

The constitutional risk: if GovernanceAuthority can suppress or influence challenges to its own authorization decisions, it achieves functional self-verification (AC-02 violation). If an election system's governance authorization is unchallengeable, the governance manipulation threats (TF-36C-04-07: perfect evidence + manipulated governance rules = constitutional fraud) are structurally unaddressable.

**Finding:** GOV-AUTH challengeability is constitutionally required. ADR-5 must design a challenge pathway for GovernanceAuthority decisions that is independent of GovernanceAuthority. This closes the ADR-2 open item.

### B.4 Six-Function Challenge Surface (from ADR-2 + ADR-4)

All six authority aggregates carry L-3 requirements:

| Authority | L-3 Requirement Source | Constitutional Reason |
|---|---|---|
| EnrollmentAuthority | ADR-2 Option B (Committee) | Enrollment decisions affect voter rights; must be challengeable |
| CriteriaAuthority | ADR-2 Option B (Committee) | SELF-REF L-4 broken by external challenge; L-3 = external challenge path |
| AuditScopeAuthority | ADR-4 E.1 / ET-03 resolution | Scope specifications affect Gap A-3 closure; must be challengeable |
| AuditExecutionAuthority | ADR-2 Option D + ADR-4 E.2 | Audit findings determine election compliance; must be challengeable |
| GovernanceAuthority | ADR-2 Revision 2 (carried) | Authorization decisions must be independently challengeable (GOV-AUTH) |
| CertificationAuthority | ADR-2 Option C (External) | AC-27: external challenge reception required; terminal risk |

### B.5 CT-1 Conditional (from ADR-3)

CT-1 is active if the Authenticity Stratum implementation uses cryptographic commitment (e.g., cryptographic hash commitments at ballot cast time). Under CT-1, vote-level challenge mechanisms may involve revealing a commitment's pre-image — which could constitute a receipt showing ballot content.

ADR-5 must design the challenge architecture so that:
- If CT-1 is activated, the vote-level challenge mechanism is constitutionally structured not to create coercible receipts
- The CT-1 assessment is marked as conditional pending Round 38+ cryptographic selection

### B.6 OQ-37-04-02 Application (from ADR-4)

ADR-4 raised OQ-37-04-02: who determines whether a Tier 2 evidence scope specification exceeds delegated Tier 1 authority? ADR-5 answers: this is an L-3 challenge question. If AuditScopeAuthority produces a Tier 2 specification that a constitutionally standing party believes exceeds or fails to satisfy the Tier 1 constitutional mandate, a constitutional challenge is the mechanism of resolution. The adjudicating body determines whether Tier 2 falls within Tier 1 bounds. This closes OQ-37-04-02.

---

## Part C — Challenge Initiation: Constitutional Standing

### C.1 Options

**Option A — Universal Standing:** Any party may initiate any challenge. No restriction on who may challenge any authority decision.

**Option B — Function-Specific Standing Lists:** Each authority function maintains an enumerated list of parties with standing to challenge its decisions. Different lists for different functions.

**Option C — Constitutional Standing Classes:** A small number of constitutional standing classes, each with defined relationship to the challenged decision, applied across all functions with function-specific mapping.

### C.2 Option Analysis

**Option A (Universal):** Constitutionally over-inclusive. Any party initiating any challenge regardless of relationship to the decision creates challenge processes with no constitutional standing requirement — the challenge process becomes a mechanism for interference, not for constitutional protection. Not recommended.

**Option B (Function-Specific Lists):** Creates six separate standing regimes. Requires maintaining and updating six lists. Risk: list exclusions can suppress legitimate challenges; list inclusions can enable interference. The constitutional requirement is a principled basis for standing, not an administrative list. Not recommended as primary model.

**Option C (Constitutional Standing Classes):** Three constitutional standing classes, each grounded in a different constitutional relationship to the challenged decision:

| Class | Definition | Constitutional Basis |
|---|---|---|
| **Class S-1: Directly Affected Party** | A party whose constitutional rights or interests are directly affected by the challenged decision | L-3 requires challenge addressability; only directly affected parties can invoke L-3 for their own rights |
| **Class S-2: Constitutional Observer** | A party with constitutionally granted oversight authority over the challenged function | AC-10: structural independence requires externally standing monitors, not just affected parties |
| **Class S-3: Authority Peer** | An authority aggregate whose own function depends on the constitutionality of the challenged decision | AC-02: if authority X cannot challenge authority Y when Y's decision affects X's function, X may be forced to build on an unconstitutional foundation |

### C.3 Selection — Option C (Constitutional Standing Classes)

**Selected:** Option C — Three Constitutional Standing Classes (S-1, S-2, S-3).

**Constitutional basis:**
1. Principled — each class has a constitutional reason for standing, not an administrative list
2. Generalizable — applies across all six authority functions without per-function enumeration
3. Addresses GOV-AUTH gap: members governed by GovernanceAuthority decisions have S-1 standing; CertificationAuthority has S-3 standing when a governance decision affects certifiability
4. Addresses AuditScopeAuthority gap: AuditExecutionAuthority has S-3 standing to challenge scope specifications that make completeness assessment structurally impossible; CertificationAuthority has S-3 standing for the same reason

**Per-function standing application** (see H.x for full profiles):
- S-1 maps to: the member/voter/candidate directly subject to the decision
- S-2 maps to: constitutionally designated election observers; audit observers; oversight bodies

> **OBS-ADR5-03: Standing classes must derive from constitutional authority, not from challenged-authority discretion.**
>
> Who grants S-2 (Constitutional Observer) status? Who grants S-3 (Authority Peer) recognition? If GovernanceAuthority designates observers and those observers later bring challenges against GovernanceAuthority, S-2 standing is captured by the challenged authority. Constitutional standing must derive from ElectionConstitution's designation or from an authority constitutionally independent of the challenged function — not from the challenged authority's own discretionary observer designation. ADR-7 must design the standing-grant mechanism to satisfy this constraint. This is particularly important for real election systems where observer status is routinely contested.
- S-3 maps to: authority aggregates with constitutional function dependencies

---

## Part D — Challenge Reception: Independence Architecture

### D.1 AC-09 Reception Independence

AC-09 requires: "Challenge reception must be structurally independent of the challenged authority."

"Structurally independent" means: the reception mechanism cannot be within the challenged authority's operational domain, cannot be suspended by the challenged authority, and cannot be modified by the challenged authority.

### D.2 Options

**Option A — Unified Challenge Reception Point:** One constitutionally designated reception point receives all challenges to all authority aggregates. A single body that is independent of all six authority aggregates simultaneously.

**Option B — Function-Specific Reception:** Each authority aggregate has its own designated external reception point. Six reception points, each independent of its corresponding authority.

**Option C — Adjudicator-Integrated Reception:** Challenge reception and adjudication are handled by the same constitutional body — a single body receives and adjudicates all challenges.

### D.3 Option Analysis

**Option A (Unified):** Strong independence guarantee — one body that is constitutionally mandated to be independent of all six authority aggregates. Prevents forum shopping (a challenger cannot choose which reception point based on anticipated sympathy). Single point of failure risk: if the unified reception point fails, all challenge pathways fail. However, AC-13 (succession) mitigates this.

**Option B (Function-Specific):** Six independent reception points. Reduces single-point failure risk. Increases governance complexity — six bodies must each maintain independence from their respective authority. Risk of inconsistent reception standards across functions. More difficult to govern constitutionally.

**Option C (Adjudicator-Integrated):** Simplifies structure by merging reception and adjudication into one body. However, this may reduce the separation between receiving a challenge (administrative gate — is this challenge constitutionally initiated?) and adjudicating a challenge (constitutional determination — is the challenged decision valid?). These are conceptually distinct functions. Merging them does not create a constitutional violation, but the distinction may be architecturally important.

**Selection basis:** Option A (Unified) + recognition that reception and adjudication are constitutionally distinct functions that may be served by the same body (Option C) or different sub-functions of one body. ADR-5 selects the constitutional requirement (unified, independent reception) and leaves the organizational implementation to Round 38+.

### D.4 Selection — Unified Independent Reception

**Selected:** One constitutionally designated challenge reception function, structurally independent of all six authority aggregates.

**Constitutional basis:**
1. AC-09 requires reception independence from the challenged authority — this applies to all six; unified reception achieves this structurally
2. Prevents forum shopping — there is only one reception point, not six
3. Consistency — one reception standard for challenge validity across all functions

**Naming discipline:** The reception function is a constitutional requirement; its organizational form is Round 38+. "ChallengeReceptionFunction" names the constitutional requirement, not an implementation choice.

> **OBS-ADR5-01: ChallengeReceptionFunction remains a CANDIDATE authority.**
>
> ADR-5 establishes only the constitutional requirement for independent reception. Whether reception is realized as a separate authority aggregate, a sub-function of the adjudication body, or a constitutional capability without its own L-1/L-5 profile is not yet determined. ADR-5 does not select the authority form — it establishes the constitutional requirement. Authority form determination: ADR-6 or ADR-7.

---

## Part E — Challenge Adjudication: The Recursion Question

### E.1 The Adjudication Authority Question

The adjudication authority question is the deepest constitutional question in ADR-5. It connects to TC3-NCQ-04 ("Who governs the governors?"), ET-03 (D43 recursion pattern), and AC-02 (no self-verification).

For challenge adjudication to be constitutionally sound, the adjudicator must:
1. Be independent of the challenged authority (AC-09/AC-10)
2. Have a grounded legitimacy chain (L-1/L-5) — otherwise it triggers a new D43 gap
3. Have its own challengeability resolved without infinite recursion (Question 4)

### E.2 Options

**Option A — GovernanceAuthority Adjudicates:** GovernanceAuthority (Governance Authorization Committee) adjudicates all challenges.

**Option B — New ConstitutionalChallengeAuthority:** A new constitutionally designated authority aggregate adjudicates challenges; independent of all six authority aggregates.

**Option C — External Constitutional Body:** A constitutionally designated external organization (outside the election system entirely, analogous to CertificationAuthority's Option C in ADR-2) adjudicates challenges.

**Option D — Constitutional Process Review:** No designated adjudicator body within the constitutional election architecture; challenges invoke ElectionConstitution's external constitutional review process directly.

### E.3 Option Analysis

**Option A (GovernanceAuthority):** GovernanceAuthority cannot adjudicate challenges to GovernanceAuthority's own decisions — this is a direct AC-02 self-verification violation. Even if GovernanceAuthority could adjudicate other functions' challenges, the self-adjudication problem for GovernanceAuthority challenges eliminates Option A as the complete answer. Partial use (adjudicating other functions) creates an asymmetric model requiring additional design for GovernanceAuthority challenges. Not recommended.

**Option B (New ConstitutionalChallengeAuthority):** A new constitutionally designated aggregate. Requires D43 justification per Rule 3. See E.4 for D43 analysis. If justified, this provides the clearest constitutional structure: a single authority aggregate with mandate, legitimacy, and challenge pathway defined. Key question: does this new aggregate trigger ET-03-like recursion (who challenges the adjudicator)? See E.5.

**Option C (External Constitutional Body):** Modeled on CertificationAuthority's independence form (ADR-2 Option C — External Organization). A body external to the election system adjudicates challenges. Highest independence guarantee — cannot be influenced by any internal authority aggregate. The external body has its own legitimacy (not derived from ElectionConstitution for its existence, but designated by ElectionConstitution for this role). Recursion terminates: the external body's decisions are subject to its own external governance, not to another challenge within the election system.

**Option D (Constitutional Process Review Only):** No internal adjudicator. All challenges invoke ElectionConstitution's constitutional review process directly. This is constitutionally clean but operationally heavy — every challenge requires constitutional amendment-level process. Viable only for the most fundamental constitutional challenges; not practical as the primary architecture for routine authority function challenges.

### E.4 D43 Justification for New Authority Body (Options B and C)

Per Rule 3 (Authority Inflation Prohibited), introducing a new authority body requires demonstrating:

**For adjudication authority (ChallengeAdjudicationBody):**

| D43 Criterion | Analysis |
|---|---|
| Unique mandate | Challenge adjudication across all six authority functions. No existing authority has this mandate — GovernanceAuthority is disqualified by AC-02 self-adjudication; CertificationAuthority would be adjudicating challenges to itself; all others have function conflicts |
| Unique legitimacy chain | L-1 = ElectionConstitution designating an adjudication body; L-2 = constitutionally designated external body (under Option C) or constitutionally designated committee (under Option B); unique — not shared with any authority being adjudicated |
| Unique revocation chain | Revocation of adjudication authority through constitutional process; the election system cannot revoke the adjudicator (if it could, any authority could suppress challenges by revoking adjudication) |
| Unique challenge chain | See E.5 — recursion terminates at constitutional review |

**Verdict:** D43 justification is satisfied. A ChallengeAdjudicationBody does not violate Rule 3 because it cannot reuse any existing authority structure without introducing constitutional self-reference violations. The unique mandate, unique legitimacy chain, unique revocation, and unique challenge pathway are all demonstrable. The new authority is constitutionally necessary, not architecturally convenient.

### E.5 Adjudicator Challengeability — Recursion Termination

**Question 4:** Can adjudication decisions themselves be challenged, and where does the recursion terminate?

**Constitutional analysis:**

If ChallengeAdjudicationBody decisions can be challenged by another internal body, that body requires its own L-1/L-5, creating another D43-like question. The recursion cannot terminate internally without creating either:
- A body that is unchallengeable within the system (constitutional vulnerability — AC-02 applied to adjudication), or
- An infinite sequence of challenge bodies

**Resolution — Terminal Authority Principle:**

The recursion terminates at ElectionConstitution's constitutional review process. This is constitutionally grounded because:
1. ElectionConstitution is the L-1 source for all authority aggregates in the election system — it is the constitutional foundation, not just another authority
2. Constitutional review of adjudication = review of whether the ChallengeAdjudicationBody acted within its ElectionConstitution-designated mandate
3. This is the same constitutional review mechanism used for L-4 revocation (ADR-2) — not a new mechanism, an existing one
4. No new authority body is created — the terminal point is the constitutional instrument itself

**Within-cycle adjudication finality:**

Within a single election cycle, ChallengeAdjudicationBody decisions are constitutionally final. The election cycle's constitutional integrity depends on the adjudication decisions being stable during the cycle — post-cycle constitutional review is available, but within the cycle, adjudication is final.

This is the constitutional design equivalent of a "final appeal" structure. Constitutional review is available but not within the cycle's operational timeline.

**Adjudicator challengeability summary:**
- Yes, adjudicator decisions can be challenged — through ElectionConstitution's constitutional review process
- Within election cycle: adjudication decisions are final (operational stability requirement)
- After election cycle: subject to constitutional review (ElectionConstitution amendment/oversight)
- Recursion terminus: ElectionConstitution constitutional review — the same terminal point used for all L-4 revocations in the system

### E.6 Selection — Constitutional Independence (Form Deferred)

**Selected:** Constitutional independence of the adjudication function. ADR-5 establishes that challenge adjudication requires a constitutionally independent body ("ChallengeAdjudicationBody" names the constitutional requirement, not a selected implementation). The D43 justification (E.4) and the Terminal Authority Principle (E.5) are established.

**What ADR-5 selects:** Independence — the adjudication function must be constitutionally independent of all six authority aggregates it adjudicates. AC-09/AC-10 are structurally satisfied by this independence requirement.

**What ADR-5 does NOT yet select:** Externality — whether this independence requires an external organization (Option C) or can be satisfied by a constitutionally designated internal committee (Option B) is not yet determined. ADR-2 established that CERTIFICATION requires the strongest independence form (external organization). ADR-5 has not yet demonstrated that CHALLENGE ADJUDICATION requires the same externality. Option B and Option C remain constitutionally viable.

> **Realization discipline (ChallengeAdjudicationBody):**
>
> ChallengeAdjudicationBody is a constitutional REQUIREMENT, not a selected architecture. The constitutional requirement is: an independently constituted body with L-1/L-5 grounded in ElectionConstitution adjudicates challenges across all six authority functions. Whether that body is:
> - Option B: Constitutionally designated committee (independent, but internal to the election governance structure)
> - Option C: External constitutional organization (analogous to CertificationAuthority's independence form)
>
> ...is deferred to ADR-6/ADR-7, when the independence-vs-externality threshold for challenge adjudication can be evaluated against the full constitutional architecture.

**Constitutional basis (what is confirmed):**
1. D43 justification satisfied (E.4): unique mandate, unique legitimacy, unique revocation, unique challenge pathway — justified whether Option B or Option C is eventually selected
2. Recursion terminates (E.5): constitutional review as terminal point; within-cycle finality — applies regardless of Option B/C selection
3. AC-02 satisfied: no self-adjudication regardless of form
4. GOV-AUTH challengeability established: GovernanceAuthority decisions can be challenged; the adjudication body is constitutionally independent; GOV-AUTH closure confirmed

**Legitimacy profile (Option B/C neutral):**

| Dimension | Source |
|---|---|
| L-1 | ElectionConstitution — explicit designation of challenge adjudication function |
| L-2 | Constitutionally designated body; organizational form (internal committee or external organization) = ADR-6/ADR-7 |
| L-3 | Constitutional review — decisions subject to ElectionConstitution's review process |
| L-4 | Constitutional process only — the election system cannot revoke the adjudication function |
| L-5 | Constitutional succession provision |

**OBS-ADR3-01 compliance:** ChallengeAdjudicationBody is an independently governed function. Its legitimacy chain (L-1/L-5 above) satisfies OBS-ADR3-01 — existence of independence ≠ constitutional legitimacy; the designated legitimacy chain establishes it. This applies regardless of whether Option B or Option C is eventually selected.

---

## Part F — Evidence Admissibility Model

### F.1 Constitutional Evidence Categories

Four categories of constitutionally admissible evidence in challenge proceedings:

**Category E-1: Constitutional Text Evidence**
Evidence from ElectionConstitution itself — what the constitution mandates, prohibits, or authorizes. The primary evidence category for all challenges — all challenge decisions must be anchored in constitutional text. Includes Tier 1 evidence categories (ADR-4: from ElectionConstitution's constitutional mandate for completeness).

**Category E-2: Process Record Evidence**
Evidence from the Completeness Stratum (AuditScopeAuthority's Tier 2 specification — what was supposed to happen) and the Presence Stratum (Audit Context records — what actually happened). Establishes whether the challenged decision conforms to the constitutional process.

**Category E-3: Authenticity-Verified Evidence**
Evidence verified through the Authenticity Stratum (AC-31 independent reference standard). Records confirmed as unmodified. This category establishes that the evidence itself has not been manipulated — connecting the ADR-3 three-stratum model to the challenge process.

**Category E-4: Procedural Record Evidence**
Documentation of the challenged decision's own process — what inputs were considered, what procedure was followed, whether the authority's own constitutional procedure was respected. Distinct from E-2 (which concerns election process records) — E-4 concerns the authority decision process itself.

### F.2 Excluded Evidence

The following are constitutionally excluded:

- **Self-serving assertions without supporting records:** An authority aggregate's assertion that it acted correctly, without supporting E-1/E-2/E-3/E-4 records, is insufficient
- **Unverifiable third-party claims:** Claims from parties without constitutional standing (Class S-1/S-2/S-3) are not admitted; claims from standing parties without record support are insufficient
- **Vote-level private information (ballot content):** In vote-level challenges, ballot content is excluded unless submitted through a constitutionally structured mechanism that preserves receipt-freeness (see Part I)
- **Post-hoc rationalization:** Reasons offered for a decision after the challenge was initiated that were not present at the time of the decision — challenge proceeds on the record that existed when the decision was made

### F.3 Admissibility and ADR3-INV-01

Evidence admissibility must not create stratum collapse. Specifically:
- Completeness challenges (challenging AuditScopeAuthority scope) must use E-1/E-4 evidence; not evidence from the Presence Stratum (what is present) — that would allow Presence to define Completeness (ADR3-INV-01 violation)
- Authenticity challenges must use E-3 (authenticity-verified) evidence; not E-2 (presence) alone — presence of a record does not prove authenticity (ADR3-INV-01)

---

## Part G — Remedy Architecture

### G.1 Constitutional Remedy Taxonomy

Eight constitutional remedies, ordered from lowest to highest constitutional severity:

| Remedy | Constitutional Meaning | Conditions |
|---|---|---|
| **R-1: Dismiss** | Challenge found without constitutional merit; challenged decision stands | Challenge is constitutionally initiated but fails on the merits |
| **R-2: Require Explanation** | ChallengeAdjudicationBody requires the challenged authority to document its constitutional basis; decision temporarily stands | Procedural irregularity without demonstrated substantive harm |
| **R-3: Correct** | The challenged authority corrects its decision within its constitutional mandate | The decision is constitutionally infirm but correctable within the authority's existing power |
| **R-4: Override** | ChallengeAdjudicationBody issues a determination that supersedes the challenged decision | The challenged decision cannot be self-corrected; the override is within ChallengeAdjudicationBody's constitutional mandate |
| **R-5: Suspend** | The challenged authority's function is suspended pending constitutional correction | The challenged authority has lost constitutional standing to act; suspension preserves the status quo |
| **R-6: Re-audit** | AuditExecutionAuthority performs a new audit under constitutional direction | The audit finding is constitutionally infirm; re-audit is the appropriate corrective |
| **R-7: Recertify** | CertificationAuthority performs new certification after a substantive correction | A prior certification was issued on constitutionally invalid grounds (incorrect audit finding, invalid scope, etc.) |
| **R-8: Rerun** | The entire election must be conducted again from a constitutional starting point | The constitutional integrity of the election as a whole is irreparably compromised; most severe remedy; highest constitutional bar |

### G.2 Remedy Standing Conditions

Not all standing classes may request all remedies. Remedy requests are standing-bounded:

| Remedy | S-1 Standing | S-2 Standing | S-3 Standing |
|---|---|---|---|
| R-1 (Dismiss) | N/A — this is granted to the challenged authority | N/A | N/A |
| R-2 (Require Explanation) | May request | May request | May request |
| R-3 (Correct) | May request | May request | May request |
| R-4 (Override) | May request | May request | May request |
| R-5 (Suspend) | May request | May request | May request |
| R-6 (Re-audit) | Indirectly affected only | May request | S-3 with direct function dependency |
| R-7 (Recertify) | May request if directly affected by certification outcome | May request | May request |
| R-8 (Rerun) | May request; highest constitutional bar | May request | Must demonstrate systemic impact |

### G.3 Remedy Limits — Constitutional Bounds

**ChallengeAdjudicationBody cannot:**
- Grant a remedy that gives itself authority beyond its constitutional adjudication mandate
- Issue remedies that function as constitutional amendments (e.g., permanently expanding an authority aggregate's mandate)
- Grant R-8 (Rerun) without satisfying ADR5-INV-01 (see below)

> **ADR5-INV-01: R-8 (Rerun) is constitutionally terminal.**
>
> R-8 may only be granted when constitutional integrity cannot be restored through any lower remedy (R-1 through R-7). Before granting R-8, ChallengeAdjudicationBody must affirmatively determine that no combination of R-3, R-4, R-5, R-6, and R-7 could restore constitutional integrity to the affected election. R-8 is not a remedy of convenience, severity, or symbolic weight — it is the remedy of last resort. This invariant is binding on all future architecture that implements the remedy model.

**Remedy-remedy dependencies:**
- R-7 (Recertify) requires prior R-3 or R-4 or R-6 to have been ordered and executed; recertification without correction recertifies the same infirmity
- R-6 (Re-audit) under a corrected scope requires R-3/R-4 on AuditScopeAuthority first; re-auditing against an uncorrected scope produces another constitutionally infirm result
- R-8 (Rerun) should follow an assessment of whether lower remedies would be constitutional equivalents; the constitutional bar for R-8 is proportional to the disruption it causes

---

## Part H — Per-Function Challenge Profiles

### H.1 EnrollmentAuthority

| Aspect | Profile |
|---|---|
| **Who may initiate (standing)** | S-1: Member denied enrollment, member challenged for de-enrollment; S-2: Constitutional election observer; S-3: CriteriaAuthority (if enrollment decision contradicts constitutional criteria) |
| **Who receives** | ChallengeReceptionFunction (unified, external) |
| **Who adjudicates** | ChallengeAdjudicationBody |
| **Evidence categories** | E-1 (constitutional enrollment criteria), E-4 (enrollment process documentation), E-2 (election records showing enrollment status) |
| **Remedy range** | R-1 through R-4 (Dismiss / Require Explanation / Correct / Override); R-5 (Suspend) in cases of systematic enrollment fraud |
| **Specific consideration** | Enrollment challenges are time-sensitive — if challenged enrollment decisions are not resolved before voting begins, the election's voter pool is constitutionally uncertain; ChallengeAdjudicationBody must prioritize enrollment challenges |

### H.2 CriteriaAuthority

| Aspect | Profile |
|---|---|
| **Who may initiate (standing)** | S-1: Candidate affected by criteria interpretation; voter affected by eligibility criteria; S-2: Constitutional election observer; S-3: EnrollmentAuthority (if criteria interpretation makes enrollment impossible), CertificationAuthority (if criteria make certification assessments impossible) |
| **Who receives** | ChallengeReceptionFunction (unified, external) |
| **Who adjudicates** | ChallengeAdjudicationBody |
| **Evidence categories** | E-1 (constitutional eligibility criteria), E-4 (criteria decision process), E-2 (election records showing criteria application) |
| **Remedy range** | R-1 through R-4; R-5 (Suspend criteria authority) in cases of systematic criteria manipulation |
| **Specific consideration** | CriteriaAuthority SELF-REF L-4 (ADR-2) — criteria revision was identified as a self-reference risk; ChallengeAdjudicationBody must be alert to challenges that are in substance requests for criteria revision masquerading as challenges to criteria interpretation |

### H.3 AuditScopeAuthority

| Aspect | Profile |
|---|---|
| **Who may initiate (standing)** | S-1: Any party whose election interests are affected by the scope specification; S-2: Constitutional election auditor, election observer; S-3: AuditExecutionAuthority (if scope is constitutionally impossible to execute), CertificationAuthority (if scope makes certification assessment impossible) |
| **Who receives** | ChallengeReceptionFunction (unified, external) |
| **Who adjudicates** | ChallengeAdjudicationBody |
| **Evidence categories** | E-1 (Tier 1 constitutional evidence categories from ElectionConstitution; the constitutional mandate that Tier 2 must satisfy), E-4 (AuditScopeAuthority's specification process documentation) |
| **Admissibility constraint** | E-2 (Presence Stratum — what audit records exist) is NOT admissible to challenge whether the Tier 2 scope is correct — that would constitute Completeness-by-Presence (ADR3-INV-01); what is present does not determine what must be present |
| **Remedy range** | R-1 through R-4; R-5 (Suspend scope until corrected); NO R-6/R-7/R-8 directly against scope authority |
| **OQ-37-04-02 closure** | Challenges to whether Tier 2 exceeds Tier 1 are precisely this challenge type — the ChallengeAdjudicationBody determines constitutional compliance using E-1 and E-4 evidence. OQ-37-04-02 is RESOLVED: this is an L-3 challenge mechanism, not an architectural design question |

### H.4 AuditExecutionAuthority

| Aspect | Profile |
|---|---|
| **Who may initiate (standing)** | S-1: Any party directly affected by audit finding (candidates, election authority); S-2: Constitutional election auditor, observer; S-3: CertificationAuthority (if audit findings are constitutionally insufficient to support certification) |
| **Who receives** | ChallengeReceptionFunction (unified, external) |
| **Who adjudicates** | ChallengeAdjudicationBody |
| **Evidence categories** | E-1 (constitutional audit requirements), E-2 (Presence Stratum — what audit records were assessed), E-3 (Authenticity Stratum — whether the records assessed were authentic), E-4 (audit execution process) |
| **Admissibility constraint** | E-3 (authenticity verification) must be independently established — AuditExecutionAuthority's assertion that records are authentic is not sufficient (ADR3-INV-01: Presence ≠ Authenticity); the Authenticity Stratum's independent reference standard (AC-31) provides E-3 |
| **Remedy range** | R-1 through R-4, R-6 (Re-audit), R-7 (Recertify after re-audit) |
| **CT-1 conditional** | If Authenticity Stratum implementation uses cryptographic commitment (CT-1 active), challenges to AuditExecutionAuthority's authenticity findings must be resolved using the same commitment mechanism — not by revealing ballot content |

### H.5 GovernanceAuthority

| Aspect | Profile |
|---|---|
| **Who may initiate (standing)** | S-1: Members governed by the challenged authorization decision; S-2: Constitutional observer; S-3: Any authority aggregate whose function is constrained by a governance authorization decision (e.g., AuditScopeAuthority challenging a governance decision that constrains scope specification authority) |
| **Who receives** | ChallengeReceptionFunction (unified, external) |
| **Who adjudicates** | ChallengeAdjudicationBody — the external body is the ONLY constitutionally sound adjudicator; GovernanceAuthority may not adjudicate its own challenges (AC-02) |
| **Evidence categories** | E-1 (constitutional governance authority scope), E-4 (governance authorization process), E-2 (election records showing governance decisions' effects) |
| **Remedy range** | R-1 through R-5; R-5 (Suspend governance authorization) is the most constitutionally sensitive — suspension of governance authorization could functionally halt the election; ChallengeAdjudicationBody must balance constitutional protection with operational continuity |
| **GOV-AUTH closure** | GovernanceAuthorizationCommittee decisions ARE challengeable; ChallengeAdjudicationBody IS independent of GovernanceAuthority. ADR-2 Revision 2 open item CLOSED. |
| **AC-25 interaction** | ADR-2 established AC-25 distinguishability — governance authorization ≠ execution. A challenge to a governance authorization decision does not automatically challenge the execution of that decision; these are separate potential challenges |

### H.6 CertificationAuthority

| Aspect | Profile |
|---|---|
| **Who may initiate (standing)** | S-1: Any stakeholder with constitutional interest in the election's certified outcome (candidates, election authority, major constituent bodies); S-2: Constitutional observer; S-3: AuditExecutionAuthority or AuditScopeAuthority if certification was issued without valid audit foundation |
| **Who receives** | ChallengeReceptionFunction (unified, external); NOTE: ADR-2 Option C gave CertificationAuthority AC-27 external challenge reception — this must be integrated with the unified reception model |
| **Who adjudicates** | ChallengeAdjudicationBody |
| **Evidence categories** | E-1 (constitutional certification requirements), E-2 (audit records), E-3 (authenticity-verified records), E-4 (certification process documentation), plus AuditScopeAuthority's Tier 2 specification (from ADR-4: CertificationAuthority must independently verify scope) |
| **AC-27 integration** | AC-27 (CertificationAuthority must have external challenge reception) is satisfied by the unified ChallengeReceptionFunction — the external reception point handles CertificationAuthority challenges as it handles all others |
| **Remedy range** | R-1 through R-7; R-7 (Recertify) is the primary remedy; R-8 (Rerun) available for systemic constitutional failure with highest constitutional bar |

---

## Part I — EC-01 Resolution

### I.1 EC-01 Tension Classification

EC-01 (from 36E-02): challenge addressability vs. receipt-freeness.

**Type D or Type E determination:**

This ADR determines EC-01 is a **Type D tension** (structural tension requiring architectural resolution), not a Type E conflict (incompatibility requiring one principle to yield). The reason: the two principles apply to *different levels* of challenge:
- Receipt-freeness is a property of the **vote-level challenge** (challenging how an individual vote was processed)
- Challenge addressability applies to **authority-level challenges** (challenging authority aggregate decisions) without reference to individual vote content

The tension is not a conflict between principles; it is a scope distinction that was not made explicit in 36E-02.

### I.2 Two-Tier Challenge Model (EC-01 Resolution)

**Tier 1 — Authority-Level Challenges:**

Challenges to decisions made by the six authority aggregates. These challenges:
- Concern process, scope, authority, and constitutional compliance
- Do not require knowledge of individual ballot content
- EC-01 receipt-freeness concern does NOT apply
- Full evidence admissibility model (E-1 through E-4) applies
- All six authority aggregate profiles in Part H are Tier 1 challenges

**Tier 2 — Vote-Level Challenges:**

Challenges by a voter regarding how their individual vote was processed. These challenges:
- Concern whether a specific vote was correctly recorded, included in the tally, and processed according to constitutional criteria
- May require the voter to establish something about their own ballot without revealing its content to others (receipt-freeness concern)
- EC-01 IS active for Tier 2 challenges
- The constitutional mechanism: VO-3 (ReceiptHash — from ADR-3) is the CANDIDATE mechanism enabling custody-independent proof without revealing ballot content
- However: VO-3's constitutional status is Round 38+ design question (ADR-3 determination); VO-3 satisfies custody independence but origin independence is unresolved

**EC-01 resolution:** Tier 2 vote-level challenge architecture is constitutionally required but constitutionally deferred to Round 38+. The constitutional requirement is: a mechanism must exist for individual voters to challenge their ballot's processing without creating a coercible ballot receipt. The VO-3 candidate is the current best constitutional approach. Full Tier 2 architecture depends on Round 38+ VO-3 determination.

### I.3 CT-1 Integration with EC-01

If CT-1 is activated (Authenticity Stratum uses cryptographic commitment):
- Tier 2 vote-level challenge mechanism interacts with the cryptographic commitment structure
- Challenge mechanism must not require revealing the commitment pre-image (which would be a ballot receipt)
- CT-1 + EC-01 Tier 2 interaction = CONDITIONAL dependency; assessed in Round 38+ when cryptographic selection is made

**EC-01 status: RESOLVED for Tier 1 (authority challenges). Constitutionally established requirement for Tier 2 (vote-level). Full Tier 2 architecture = Round 38+.**

> **OBS-ADR5-04: Round 38+ shall distinguish authority-level, vote-level, and tally-level challenges as three constitutionally distinct challenge categories.**
>
> ADR-5 establishes the Tier 1 / Tier 2 distinction (authority challenges / vote-level challenges). A third category not yet in scope is tally-level challenges: an observer challenging the aggregation, tabulation, mixing, or decryption process — without referencing a specific ballot. A tally challenge: (a) does not require EC-01 receipt-freeness handling (no individual ballot is referenced), (b) is not necessarily an authority challenge (it may concern the mathematical or cryptographic tally process rather than an authority decision), and (c) becomes important when the program reaches Aggregation, BulletinBoard, ProofEngine, and Decryption contexts (from the Papers→DDD Reference Guide). This observation is binding on Round 38+ tally architecture: tally challenge infrastructure must be distinguished from vote-level and authority-level challenge infrastructure.

---

## Part J — Cross-Concern Analysis

### J.1 ADR3-INV-01 Compliance

| Invariant | Challenge Architecture Assessment |
|---|---|
| Completeness ← Presence | AuditScopeAuthority challenges use E-1 (constitutional text) + E-4 (process documentation), NOT E-2 (Presence Stratum). What is present cannot define what must be present in a challenge proceeding. Compliant. |
| Presence ← Authenticity | AuditExecutionAuthority challenges require E-3 (authenticity-verified evidence) as a separate category from E-2 (presence records). The presence of audit records in the Presence Stratum does not substitute for authenticity verification. Compliant. |
| Authenticity ← Completeness | Evidence scope specification (E-1/E-4 for AuditScopeAuthority challenges) does not certify records as authentic. Compliant. |

**ADR3-INV-01: SATISFIED in challenge architecture by design.**

### J.2 Rule 3 Compliance (Authority Inflation)

ADR-5 introduces one new authority body: ChallengeAdjudicationBody. Rule 3 requires D43-style justification.

From E.4: D43 justification is satisfied. The ChallengeAdjudicationBody has:
- Unique mandate: challenge adjudication; no existing authority can fulfill this without AC-02 violation
- Unique legitimacy chain: ElectionConstitution designation of external body
- Unique revocation chain: constitutional process only
- Unique challenge chain: ElectionConstitution constitutional review (terminal; no new internal body)

Rule 3 is satisfied. The new authority is constitutionally necessary, not architecturally convenient.

**Total authority aggregate count: Seven** (six from ADR-4 + ChallengeAdjudicationBody = seven). OBS-36D-02-1 governs: seven authority aggregates do not require seven bounded contexts.

### J.3 OQ-37-04-01 Impact

OQ-37-04-01 (from ADR-4): does AuditScopeAuthority's Tier 2 publication require its own evidence integrity? This question now has a partial answer from ADR-5:

If AuditScopeAuthority's Tier 2 specification is challenged, the challenger must have access to evidence that the specification was published as stated (E-4 category). The existence of a challenge pathway for Tier 2 specifications implies that a publication record is constitutionally necessary — otherwise the challenge proceeding has no reliable basis for what specification was actually in effect. This does not fully answer OQ-37-04-01 (which asks about the full three-stratum model for scope publication) but it establishes the minimum: Tier 2 publication must be evidentially recoverable for challenge purposes. ADR-6 should address whether full three-stratum treatment is warranted.

### J.4 OQ-37-04-03 Impact (L-2 Designation Specificity)

OQ-37-04-03 (from ADR-4): how specifically must ElectionConstitution name the AuditScopeAuthority L-2 body?

The challenge architecture adds context: if AuditScopeAuthority's L-2 body (scope authority body) and the ChallengeAdjudicationBody are named by ElectionConstitution, then ElectionConstitution must name at minimum:
- The scope authority body (L-2 for AuditScopeAuthority)
- The challenge adjudication body (L-2 for ChallengeAdjudicationBody)
- Their distinctness (AC-09 requires they be different bodies)

ADR-7 must assess whether ElectionConstitution can constitutionally carry all of these designations without becoming a governance-by-enumeration document that loses constitutional character.

### J.5 ElectionConstitution Concentration Update

ADR-4 raised the count to six authority aggregates with ElectionConstitution as shared L-1. ADR-5 adds ChallengeAdjudicationBody — seven aggregates.

Additionally, ChallengeAdjudicationBody's L-1 may be ElectionConstitution (internal designation) OR the body's own constitutional instrument (external organization). Under Option C, ChallengeAdjudicationBody's L-1 may be partially external — reducing ElectionConstitution concentration for this aggregate.

> **OBS-ADR5-02: ElectionConstitution now serves as L-1 source, revocation terminus, AND challenge terminus simultaneously.**
>
> Prior to ADR-5, ElectionConstitution served as the shared L-1 source for all authority aggregates (ADR-1 through ADR-4). ADR-5 adds a second role: ElectionConstitution's constitutional review process is the terminus for challenge adjudication recursion (Terminal Authority Principle, E.5). If adjudication decisions can be challenged, and that challenge terminates at ElectionConstitution's review process, then ElectionConstitution simultaneously holds: (1) the authority grant role (L-1 for all aggregates), (2) the revocation terminus role (L-4 for all aggregates, per ADR-2), and (3) the challenge terminus role (terminal point for challenge adjudication recursion). This is a new constitutional concentration form — not authority aggregates concentrating at one point, but constitutional functions concentrating at one constitutional instrument. ADR-7 must evaluate whether this triple-role concentration remains constitutionally acceptable or whether it represents a new single point of constitutional failure.

ADR-7 must now update total constitutional collapse analysis for seven (or 6+1 external) authority aggregates and for the triple-role ElectionConstitution concentration.

---

## Part K — Comparative Option Matrices

### K.1 Challenge Initiation Options

| Option | Constitutional Grounding | Precision | Flexibility | Selected |
|---|---|---|---|---|
| A (Universal) | Over-inclusive — no standing requirement | Low | Highest | REJECTED |
| B (Function-Specific Lists) | Adequate but administratively driven | High per-list | Low (rigid lists) | Not selected |
| C (Constitutional Standing Classes) | Principled — grounded in constitutional relationship to the decision | Medium (applicable with mapping) | High (applies across functions) | **SELECTED** |

### K.2 Challenge Reception Options

| Option | AC-09 Compliance | Consistency | Failure Risk | Selected |
|---|---|---|---|---|
| A (Unified) | Structural — independent of all six | High (one standard) | Higher (mitigated by AC-13) | **SELECTED** |
| B (Function-Specific) | Structural per function | Lower (six standards) | Lower | Not selected |
| C (Adjudicator-Integrated) | Conditional on adjudicator independence | Merged model | Depends on design | Partially incorporated |

### K.3 Challenge Adjudication Options

| Option | AC-02 GOV-AUTH | AC-09/AC-10 | D43 Justified | Recursion Terminal | Selected |
|---|---|---|---|---|---|
| A (GovernanceAuthority) | VIOLATED (self-adjudication) | Partial | N/A | N/A | REJECTED |
| B (Internal Independent Body) | Satisfied | Structural | Yes | Yes (constitutional review) | VIABLE — form decision deferred |
| C (External Body) | Satisfied | Strongest | Yes | Yes (external governance + constitutional review) | VIABLE — form decision deferred |
| D (Constitutional Process Only) | Satisfied | Satisfied | N/A | Yes | Not selected (operationally impractical) |

**ADR-5 selects independence (A rejected; B and C remain viable). Externality (C vs B) determination deferred to ADR-6/ADR-7.**

### K.4 EC-01 Resolution Options

| Approach | Receipt-freeness | Challenge Addressability | Constitutional Grounding | Selected |
|---|---|---|---|---|
| Type E — one principle yields | Preserved at cost of vote-level challenge | Sacrificed | Incomplete | REJECTED |
| Two-tier model — scope distinction | Preserved for vote-level challenges | Satisfied for authority challenges | Strong | **SELECTED** |
| Universal receipt-preserving challenge | Difficult — all challenges need anonymous receipt mechanism | High complexity | Unresolved (requires full CT-1 + VO-3 design) | Not selected (Round 38+) |

---

## Section — ARB Decision Block

**[APPROVED — Required Revisions Applied (2026-06-16)]**

### Decisions Made in This ADR

1. **Challenge Initiation → Constitutional Standing Classes (Option C):** Three classes — S-1 (Directly Affected Party), S-2 (Constitutional Observer), S-3 (Authority Peer). Applies across all six authority aggregates with function-specific mapping. No universal standing; no rigid administrative lists. OBS-ADR5-03: standing class grants must derive from constitutional authority, not challenged-authority discretion.

2. **Challenge Reception → Unified Independent Reception Function:** One constitutionally designated reception function, structurally independent of all six authority aggregates. Prevents forum shopping. AC-09 structurally satisfied. OBS-ADR5-01: ChallengeReceptionFunction is a CANDIDATE authority — whether it is a separate aggregate, sub-function of ChallengeAdjudicationBody, or constitutional capability is deferred to ADR-6/ADR-7.

3. **Challenge Adjudication → Constitutional Independence SELECTED; Externality Form DEFERRED:** ChallengeAdjudicationBody names the constitutional requirement for an independently constituted adjudication body. D43 justification satisfied (unique mandate, legitimacy, revocation, challenge pathway). Option B (independent internal body) and Option C (external organization) both remain constitutionally viable. Whether externality is constitutionally required (Option C) or independence suffices (Option B) is determined by ADR-6/ADR-7. Provisional aggregate count: seven.

4. **Adjudicator Challengeability — Terminal Authority Principle:** ChallengeAdjudicationBody decisions are final within an election cycle. After the cycle, decisions are subject to ElectionConstitution's constitutional review process. Recursion terminates at ElectionConstitution. No infinite regress.

5. **GOV-AUTH Challengeability — CLOSED:** GovernanceAuthorizationCommittee decisions are challengeable through the unified challenge architecture. ChallengeAdjudicationBody is constitutionally independent of GovernanceAuthority. ADR-2 Revision 2 open item closed.

6. **Evidence Admissibility → Four-Category Model:** E-1 (constitutional text), E-2 (process records — Presence Stratum), E-3 (authenticity-verified — Authenticity Stratum), E-4 (procedural records — authority decision process). ADR3-INV-01 enforced in admissibility design.

7. **Remedy Taxonomy → Eight-Tier with ADR5-INV-01:** R-1 (Dismiss) through R-8 (Rerun). ADR5-INV-01: R-8 may only be granted when constitutional integrity cannot be restored through any lower remedy; R-8 is constitutionally terminal.

8. **EC-01 → Type D Tension, Two-Tier Resolution:** Authority-level challenges (Tier 1): receipt-freeness not applicable; full evidence model. Vote-level challenges (Tier 2): EC-01 active; VO-3 candidate mechanism; full Tier 2 architecture = Round 38+. OBS-ADR5-04: tally-level challenges are a constitutionally distinct third category — deferred to Round 38+.

9. **OQ-37-04-02 — CLOSED:** Tier 1/Tier 2 boundary adjudication is a Tier 1 challenge to AuditScopeAuthority. ChallengeAdjudicationBody determines using E-1 + E-4 evidence.

10. **OBS-ADR3-01 for ChallengeAdjudicationBody:** Its legitimacy chain (L-1/L-5) satisfies OBS-ADR3-01 — existence of independence ≠ constitutional legitimacy; the ElectionConstitution-grounded chain establishes it. Applies whether Option B or Option C is eventually selected.

11. **OBS-ADR5-02 — ElectionConstitution Triple-Role Concentration:** ElectionConstitution now serves as L-1 source (ADR-1 through ADR-5), revocation terminus (ADR-2), and challenge terminus (ADR-5 Terminal Authority Principle). This is a new concentration form. ADR-7 must evaluate whether triple-role concentration remains constitutionally acceptable.

12. **ADR5-INV-01 — R-8 Terminal Invariant:** Binding on all future architecture.

13. **OBS-ADR5-01/03/04 — Candidate status and inheritance obligations** as documented throughout.

### Open Questions Carried to ARB

**OQ-37-05-01: Does ChallengeReceptionFunction require its own L-1/L-5 profile?**

ADR-5 names a "ChallengeReceptionFunction" as the unified reception point. If this function is a constitutional authority role (not merely an operational process), it requires L-1/L-5. Could ChallengeReceptionFunction be a function of ChallengeAdjudicationBody (merged reception + adjudication) rather than a separate authority? Or does the distinction between receiving a challenge (administrative constitutionality gate) and adjudicating a challenge (constitutional determination) require two separate authorities?

**OQ-37-05-02: Remedy R-5 (Suspend) and succession — what governs the suspended period?**

R-5 suspends a challenged authority's function. During suspension, the authority aggregate cannot perform its constitutional function. What governs in the interim? Does AC-13 (succession) apply — triggering a constitutional successor? Or is suspension a temporary null state where the function is simply unavailable? This is operationally significant for EnrollmentAuthority and CriteriaAuthority (which are time-critical in an election timeline).

**OQ-37-05-03: Vote-level challenge standing (Tier 2) — does a voter retain S-1 standing for their own ballot's processing?**

Tier 2 challenge architecture is deferred to Round 38+. However, the standing model for Tier 2 must be constitutionally established: a voter challenges how their ballot was processed = S-1 standing. But does S-1 standing require the voter to identify their ballot (which could break receipt-freeness) or can S-1 standing be asserted without ballot identification? This intersects VO-3's constitutional status and is a Round 38+ open question — but should be flagged now.

### Decisions Deferred to Subsequent ADRs

| Decision | Deferred to |
|---|---|
| ChallengeReceptionFunction L-1/L-5 (OQ-37-05-01) | ADR-7 (governance state) or resolved by merger with ChallengeAdjudicationBody in Round 38+ |
| R-5 Suspension governance and succession (OQ-37-05-02) | ADR-7 |
| Tier 2 vote-level challenge full architecture | Round 38+ (pending VO-3 constitutional determination) |
| CT-1 + EC-01 Tier 2 interaction | Round 38+ |
| ChallengeAdjudicationBody organizational form | Round 38+ |
| ChallengeReceptionFunction operational design | Round 38+ |
| ElectionConstitution concentration update (7 aggregates) | ADR-7 |
| OQ-37-05-03 (voter S-1 standing for Tier 2) | Round 38+ |
| OQ-37-04-01 (Tier 2 scope publication evidence integrity) | ADR-6 |
| CertificationAuthority challenge handling post-R-7 | ADR-6 |

### Authorization Requested

**ADR-6: Certification Architecture**

ADR-6 must answer: What exactly is CertificationAuthority certifying? (Process compliance, evidence completeness, evidence authenticity, constitutional compliance, election validity — these are NOT the same thing and must be kept constitutionally distinct.)

ADR-5 adds to ADR-6's inheritance:
- CertificationAuthority challenge profile (H.6 above)
- R-7 (Recertify) remedy conditions — what must be corrected before recertification is constitutionally valid
- OQ-37-04-01 partial answer — Tier 2 publication evidence integrity question flagged for ADR-6
- CertificationAuthority's S-3 standing to challenge AuditScopeAuthority and AuditExecutionAuthority

ADR-6 is authorized pending ADR-3 (APPROVED), ADR-4 (submitted), and ADR-5 (this document).

---

*Round 37-05 — ADR-5: Challenge Architecture — SUBMITTED FOR ARB REVIEW*
*Research Program: NRNA DDD Trustworthiness*
*Document: Round37-05_ADR-5_Challenge_Architecture.md*
*Predecessors: ADR-1, ADR-2, ADR-3 — APPROVED; ADR-4 — SUBMITTED*
*Successors pending ARB: ADR-6*
