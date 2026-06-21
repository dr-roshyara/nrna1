# Round 38B-05 — ARB Review
## Protected Core Catalog and Graduated Threshold Model Evaluation

**Program:** NRNA DDD Trustworthiness Research Program
**Document:** 38B-05-ARB-Review
**Status:** ISSUED (2026-06-18)
**Reviewing Roles:**
- Senior DDD Architect
- Senior Constitutional Governance Architect
- Senior Online Voting Security Architect

**Document Under Review:** `Round38B-05_Constitutional_Amendment_Governance.md`
**Current Status of Reviewed Document:** APPROVED WITH MINOR REVISIONS AND INTEGRATIONS APPLIED (ARB Review 2026-06-18; R1/R2/R3 applied; DeepSeek 38B-05 integration 2026-06-18)

**Scope Restrictions (binding on this review):**
- Do NOT create new governance specifications
- Do NOT redesign 38B-05
- Do NOT begin 38B Synthesis
- Do NOT begin 38C
- Review only

**Required Evaluations:**
1. Protected Core Justification
2. Candidate Protected Provisions
3. Protected Core Completeness
4. AA-01 Consistency
5. Self-Destruction Analysis

**Required Outputs:** OBS-38B05-04, OBS-38B05-05, OBS-38B05-06, OBS-38B05-07

---

## Preliminary Note — OBS Numbering Conflict

The DeepSeek integration pass (2026-06-18) introduced an observation labeled OBS-38B05-04 (Amendment Appeal Circularity) into the 38B-05 document. This ARB review is prescribed to produce OBS-38B05-04 through OBS-38B05-07 as its formal outputs. The numbering collision is real and must be resolved.

**Proposed resolution (Required Revision R1):** Renumber the DeepSeek integration OBS-38B05-04 (Amendment Appeal Circularity) to OBS-38B05-08. The ARB-prescribed designations OBS-38B05-04 through OBS-38B05-07 are then available for the ARB review outputs. The Amendment Appeal Circularity observation retains its full content under the new designation.

Post-revision numbering:
- OBS-38B05-01: Constitutional Self-Destruction cost/friction system (original)
- OBS-38B05-02: Amendment chain as AA-01 dependency vehicle (original)
- OBS-38B05-03: CAB independence principle vs. appointment mechanics (original, SCF-38B04-01)
- OBS-38B05-04: Protected Core Assessment (this ARB review — see Part G)
- OBS-38B05-05: Candidate Protected Provision Assessment (this ARB review — see Part H)
- OBS-38B05-06: AA-01 Consistency Assessment (this ARB review — see Part I)
- OBS-38B05-07: Constitutional Self-Destruction Assessment (this ARB review — see Part J)
- OBS-38B05-08: Amendment Appeal Circularity (renumbered from DeepSeek integration OBS-38B05-04)

---

## Part A — Review Context

The Protected Core Catalog in 38B-05 Part E is the most consequential single constitutional design decision produced by the 38B series. The catalog specifies which constitutional provisions are placed in the highest tier of amendment protection — practically very difficult to remove, requiring near-unanimity, 180-day deliberation, and a 90-day challenge window.

The catalog contains seven provisions. Each provision represents a constitutional judgment: that this protection is so foundational that removing it through normal or even supermajority processes would be constitutionally impermissible. The catalog is not a list of "important" provisions — it is a list of provisions whose removal would transform the architecture into something structurally different from what was constitutionally adopted.

This is the correct framing for the review. The question for each provision is not "is this important?" but "would its removal change what this architecture fundamentally is?"

The review also addresses the inverse: the candidates properly excluded from Tier 3. The Rejected Candidates table (E.3.2) is as important as the catalog itself. If significant constitutional protections were placed in Tier 2 that should be Tier 3, the catalog is incomplete — and the architecture is more vulnerable than it appears.

---

## Part B — Evaluation 1: Protected Core Justification

*For each Tier 3 provision: threats mitigated, architectural dependencies, consequence of removal, why Tier 2 insufficient, why Tier 3 justified.*

---

### B.1 Vote Anonymity (VO-1 / AC-17)

**Threats mitigated:**
- TM-20 (voter coercion) — the primary threat; voter identity linkable to ballot enables post-election punishment for how one voted
- Retroactive voter targeting — post-election identification of voters who voted against incumbents
- Electoral intimidation — forward-looking threat; voters who anticipate potential linkage vote strategically rather than honestly

**Architectural dependency:**
Vote anonymity is not merely a policy preference; it is the constitutional foundation for the Vote aggregate's primary design constraint. The votes table has no user_id column — this is not a database implementation detail, it is a constitutional architectural decision. Removing vote anonymity does not require changing a line of code; it requires constitutionally permitting that the code change. The Vote aggregate boundary constraint (no voter identity in vote domain events) is constitutionally grounded in VO-1. Without VO-1, the constraint has no constitutional basis.

**Consequence of removal:**
Retroactive voter-vote linkage becomes constitutionally permissible. The architecture's entire anonymity-preserving design (votes table structure, Vote aggregate boundary, identity stopping at the command boundary) loses constitutional standing. Past elections — whose votes were recorded under the expectation of anonymity — become retroactively linkable once linkage is constitutionally authorized. Anonymity removal is irreversible with respect to historical votes.

**Why Tier 2 insufficient:**
A supermajority coalition forming to remove vote anonymity is not a paranoid scenario. It is the paradigm threat in authoritarian political contexts: a governing majority that wishes to know how the opposition voted. Two-thirds of MA is achievable in a politically homogeneous or captured assembly. The supermajority threshold does not structurally prevent this; it merely requires coordination.

**Why Tier 3 justified:**
Vote anonymity defines what a free election is. Its removal does not reform the election architecture; it converts it into a surveillance architecture. No graduated loss is possible: vote anonymity either exists or it does not. Tier 3 protection (near-unanimity + extraordinary deliberation) is the minimum structurally honest protection for this provision.

**ARB assessment: JUSTIFIED.**

---

### B.2 Challenge Rights (S-1/S-2/S-3 Standing)

**Threats mitigated:**
- TM-04 (CAB capture via standing manipulation) — eliminating standing classes is structurally equivalent to capturing CAB
- Bootstrapping attack (identified in 38B-05 E.3.1) — this is the most architecturally significant threat: removing challenge rights *first* renders every other Tier 3 provision unenforceable without formally amending them
- Systematic suppression — eliminating S-2 (Constitutional Observer standing) removes external constitutional scrutiny from the architecture entirely

**Architectural dependency:**
Challenge rights are the constitutional enforcement mechanism for every other provision in the Protected Core Catalog. Without the ability to challenge an amendment that removes CIC, vote anonymity, or AC-31, those protections exist only as textual provisions with no procedural path to enforcement. The standing classes are the enforcement infrastructure; the catalog items are what is being enforced. Removal of challenge rights collapses the entire enforcement layer simultaneously.

**Consequence of removal:**
This is the most consequential single removal from the catalog. Removing challenge rights does not merely create one gap in protection — it removes the enforcement mechanism for all six other Tier 3 provisions simultaneously. An amendment that eliminates S-1/S-2/S-3 standing leaves the remaining Tier 3 provisions as nominal protections with no constitutional enforcement path. The architecture then becomes constitutionally self-sealing (Root Cause Cluster B confirmed in 38A-06).

**Why Tier 2 insufficient:**
The bootstrapping sequence is two Tier 2 amendments: (1) remove challenge rights, (2) remove everything else. A coalition that achieves supermajority once for amendment (1) gains unlimited amendment capability for everything that was previously Tier 3, because step (2) would then face no enforcement mechanism. Tier 2 protection for challenge rights is therefore effectively no protection for any other Tier 3 provision.

**Why Tier 3 justified:**
Challenge rights have a structural priority relationship to all other protections: they are the enforcement mechanism whose removal cascades immediately to all protected provisions. They require the maximum available protection precisely because their removal is the enabling step for removing everything else.

**ARB assessment: JUSTIFIED. The bootstrapping attack analysis in E.3.1 is the most important justification in the entire matrix — it correctly identifies that challenge rights are not just one protected provision among seven; they are the meta-protection for all other six.**

---

### B.3 CIC Existence and Interpretive Independence

**Threats mitigated:**
- TM-13 (No interpretation authority) — when constitutional questions arise, each authority aggregate interprets its own mandate; disputes become politically resolved rather than constitutionally adjudicated
- Self-serving constitutional interpretation — without CIC, an authority aggregate facing a constitutional challenge interprets the constitution in ways favorable to itself
- Constitutional ambiguity exploitation — deliberately vague EC provisions become weaponizable without an authoritative interpreter

**Architectural dependency:**
CIC is the interpretive infrastructure for the entire constitutional architecture specified in 38B. Every governance specification (38B-01 through 38B-05) either depends on CIC for implementation or depends on provisions that CIC interprets. Gap 4 was opened specifically because the absence of interpretive authority renders all other constitutional provisions operationally ambiguous under adversarial conditions. CIC is not one authority among seven; it is the authority that interprets all other authorities' constitutional basis.

**Consequence of removal:**
Constitutional governance reverts to the pre-38B-01 state — without interpretive authority. Every constitutional dispute becomes a political contest among authority aggregates with no neutral resolution mechanism. The other six Tier 3 provisions, even if retained in the constitution, become interpretation-dependent in ways that cannot be authoritatively resolved. Gap 4 reopens at the moment it is most needed — when the architecture is under constitutional challenge.

**Why Tier 2 insufficient:**
CIC is the body that would be called upon to opine when any other Tier 3 provision is threatened. A coalition that removes CIC by supermajority and then threatens another Tier 3 provision faces no authoritative constitutional interpretation standing between their supermajority and their target. Tier 2 protection for CIC removes the last line of authoritative constitutional defense at the worst possible moment.

**Why Tier 3 justified:**
CIC exists specifically to interpret constitutional disputes when constitutional actors disagree. Removing CIC is most attractive precisely when constitutional actors are engaged in a disagreement — which is when CIC's protection should be strongest. The near-unanimity threshold ensures that CIC cannot be removed by the same supermajority that might wish to remove other protections through CIC-unguarded amendments.

**ARB assessment: JUSTIFIED.**

**ARB refinement:** The 38B-05 text correctly specifies the CIC recusal mechanism for self-abolition amendments (Part F) and identifies the constitutional review panel as the substitute (OQ-38B05-01). This is architecturally sound. One gap that deserves noting: Part F requires the EC to include constitutional review panel designation provisions, but the EC does not yet exist as a drafted instrument. This is a constitutional drafting prerequisite, not a 38B-05 design failure — but it should be named as such in the Open Questions.

---

### B.4 AC-31 Minimum Requirement

**Threats mitigated:**
- TM-19 (AC-31 capture C-F→F) — AC-31 capture directly enables the full FAIL path for certification authenticity
- TM-47 (certification ratchet) — the most dangerous threat in the 38A catalog for AC-31: once AC-31 is compromised or removed, CO-3 self-certification becomes permanent and automatically triggers; no deliberate adversarial act required after initial removal
- TM-48 (reference disagreement) — AC-31 as singleton enforcement depends on constitutional grounding; removal eliminates the constitutional requirement for singleton and therefore the constitutional resolution mechanism for reference disagreement

**Architectural dependency:**
AC-31 is the constitutional grounding for ADR3-INV-01 (the three evidence strata cannot collapse into each other). ADR3-INV-01 states that Completeness cannot satisfy Presence, Presence cannot satisfy Authenticity. This is architecturally binding because AC-31 provides the independent reference that gives the Authenticity Stratum its independence. Remove AC-31 and CO-3 has no independent reference; CO-3 becomes self-authenticated; ADR3-INV-01 is satisfied on paper but not in substance.

AC-31 is also the Authenticity Root of the Three Trust Root architecture. The three trust roots — Legitimacy (EC), Authenticity (AC-31), Temporal (GovernanceState) — are structurally distinct precisely because AC-31 is constitutionally independent of EC and GovernanceState. Remove the AC-31 requirement and the Authenticity Root has no constitutional grounding.

**Consequence of removal:**
CO-3 becomes self-certifying. TM-47 activates unconditionally — the certification chain becomes circular without any deliberate adversarial act. This is characterized in 38A-05 as more dangerous than the EC constitutional ratchet (AW-03-11) precisely because it operates automatically: a single moment of compromise or deletion triggers a permanent self-referential loop. CO-5 validity — the terminal election validity certification — loses its authenticity grounding. An election can be constitutionally certified as valid while its evidence is entirely self-authenticated.

**Why Tier 2 insufficient:**
38A-03 Concentration Ranking: AC-31 > CAB > CA by constitutional blast radius. AC-31 capture affects all elections across all time, is retroactive, and is constitutionally undetectable within the architecture once TM-47 activates. A supermajority of MA shaped by appointment influence (OBS-38B04-02) toward an AC-31-capture coalition could amend AC-31 away through Tier 2 process. The threat rating for this scenario is C-F→F.

**Why Tier 3 justified:**
AC-31 is the Authenticity Root. Its removal is constitutionally irreversible in practice (TM-47 activates automatically) and affects all past elections (retroactive). Near-unanimity protects against the plausible coalition attack identified in 38A-03.

**ARB assessment: JUSTIFIED. The threat justification in E.3.1 is technically the strongest in the matrix — TM-47 (automatic activation) and the C-F→F rating distinguish AC-31 from provisions that merely reduce protection. AC-31 removal converts the architecture into an actively self-undermining system.**

---

### B.5 The Amendment Process Itself

**Threats mitigated:**
- Bootstrapping attack variant 2 (weaken the mechanism first, then use the weakened mechanism): Different from the challenge rights bootstrapping attack (B.2), this attack weakens the Tier 3 threshold itself through a Tier 1 or Tier 2 amendment, then removes actual Tier 3 provisions through the now-weakened process
- Reclassification attack: Reclassify Tier 3 provisions to Tier 2, then remove them through Tier 2 process; 38B05-INV-01 addresses this but the invariant's own protection must be grounded somewhere
- Meta-protection integrity: The amendment process is the protection mechanism for all other provisions; its corruption makes all other protections fragile simultaneously

**Architectural dependency:**
Every Tier 3 provision depends on the amendment process protection as its enforcement mechanism. The seven Protected Core provisions exist as text; the amendment process requirement is what prevents those provisions from being changed through lower-threshold processes. If the amendment process can be amended through Tier 2 (supermajority + CIC vetting only), then a supermajority can lower the Tier 3 threshold to supermajority — and then every Tier 3 provision is immediately removable by the same supermajority. The amendment process is the meta-protection.

**Consequence of removal:**
The Protected Core Catalog becomes a Tier 2 catalog. Every provision in E.3 becomes removable by supermajority. Constitutional Self-Destruction restores to approximately the same risk level as Option B (Uniform Supermajority) — which was specifically rejected as inadequate in Part D.

**Why Tier 2 insufficient:**
The amendment process cannot be protected by the process it governs. A Tier 2 protection for the amendment process means a Tier 2 amendment can change the Tier 3 threshold. This is the meta-protection problem: the protection mechanism must be protected at the level it protects, or higher. Protecting a Tier 3 mechanism at Tier 2 is a category error.

**Why Tier 3 justified:**
The amendment process is neither more nor less important than any individual Tier 3 provision — it is the necessary structural foundation for all of them simultaneously. Its protection level must equal the protection level it provides. This is a logical requirement, not a policy preference.

**ARB assessment: JUSTIFIED. The E.3.1 justification ("meta-protection") is correct but could be stated more precisely — it is not merely important; it is constitutionally necessary that the protection mechanism be protected at least at the level of protection it provides.**

---

### B.6 MA Ratification Requirement

**Threats mitigated:**
- Amendment by non-sovereign actors — authority aggregates, external bodies, or executive action ratifying constitutional amendments without MA participation
- Sovereign legitimacy loss — all amendments after MA's removal from the chain have contested legitimacy, retroactively calling into question every constitutional act taken under post-removal amendments
- Legitimacy drift — gradual transfer of constitutional ratification authority to bodies without sovereign grounding, making the constitutional amendment process progressively less legitimate

**Architectural dependency:**
MA is the source-of-source (OBS-ADR7-SS1). All constitutional legitimacy traces through MA. The amendment legitimacy chain (Part I) terminates at MA legitimacy. If MA is removed from the ratification chain, the terminal point of the legitimacy chain changes — and the legitimacy of the new terminal point is constitutionally unspecified. OBS-38B05-02 states that the amendment chain is the vehicle for all future constitutional evolution; if MA is removed from that chain, AA-01's pre-constitutional assumption becomes unanchored.

**Consequence of removal:**
Post-removal constitutional amendments have no traceable sovereign legitimacy. Two constitutional regimes coexist: amendments made under MA ratification (legitimate, traceable to AA-01) and amendments made without MA ratification (legitimacy unspecified). The architecture cannot determine which regime applies without a constitutional interpretation from CIC — but if CIC was abolished before MA was removed from the chain, the interpretation is unavailable.

**Why Tier 2 insufficient:**
MA ratification is the legitimacy anchor for the entire amendment process. A supermajority vote to remove MA from the chain is self-defeating: MA ratifies its own removal as the ratifying body. The resulting amendments then have contested legitimacy. Tier 2 protection allows this self-defeating action to occur at supermajority threshold — which is a lower barrier than the harm warrants.

**Why Tier 3 justified:**
MA ratification requirement is the constitutional mechanism by which the constitutional architecture remains connected to its legitimacy source throughout its evolution. Its removal severs the only constitutional legitimacy chain that the architecture has discovered. Near-unanimity protection ensures this severance cannot occur as a motivated-minority political maneuver.

**ARB assessment: JUSTIFIED. One observation: this provision has a self-referential quality — MA ratifies amendments, including the amendment that would remove MA from the ratification chain. This is a constitutional self-amendment scenario analogous to the CIC self-abolition scenario, but without a specified recusal mechanism. The document does not address whether a special procedure applies when MA is asked to ratify an amendment removing MA itself. This is a candidate for an additional Open Question — see Required Revision R2.**

---

### B.7 CAB Independence Principle

**Threats mitigated:**
- TM-04 (CAB capture via structural subordination) — making CAB operationally dependent on an authority aggregate whose decisions CAB adjudicates eliminates effective challenge adjudication
- CA+CAB coalition failure (rated unconditional F in 38A-03) — if MA can amend EC to subordinate CAB to CA, the F-1 failure condition activates constitutionally rather than through capture
- Challenge system nullification — CAB independence is what makes the challenge system more than performative; without it, challenge rights (Tier 3 item 2) exist but no independent body adjudicates them

**Architectural dependency:**
Challenge rights (Tier 3 item 2) and CAB independence (Tier 3 item 7) are structurally interdependent. Challenge rights are the procedural right; CAB independence is what gives the procedure constitutional substance. Removing CAB independence without removing challenge rights creates a constitutional shell: challenges can still be filed, a body called CAB still exists, but CAB's decisions are not independent of the bodies being challenged. This is the structural capture scenario — the architecture works exactly as designed while being constitutionally captured.

**Consequence of removal:**
The CA+CAB coalition FAIL (F) becomes constitutionally enabled rather than requiring adversarial capture. An amendment making CAB operationally subordinate to CA converts what was a threat scenario requiring careful coordination into a standard constitutional arrangement. CO-5 validity — which depends on independent challenge adjudication — is no longer independently verifiable.

**Why Tier 2 insufficient:**
The CA+CAB coalition FAIL rating was unconditional F. A Tier 2 amendment (supermajority + CIC) to make CAB operationally subordinate to CA activates this unconditional failure immediately and constitutionally. The Tier 2 threshold is precisely the threshold at which a coordinated MA faction could achieve this.

**Why Tier 3 justified:**
CAB independence is the operational foundation for the challenge system, which is itself Tier 3. If challenge rights are Tier 3 (item 2), CAB independence must also be Tier 3 — otherwise challenge rights are Tier 3 protected but their operational foundation is Tier 2 amendable, and a coalition could erase the substance of challenge rights while leaving the text intact.

**ARB assessment: JUSTIFIED. The constitutional architecture pairing of items 2 and 7 (challenge rights + CAB independence) is structurally sound. The J.3 resolution of SCF-38B04-01 (independence principle Tier 3 / appointment mechanics Tier 2) is the correct constitutional distinction for a judicial-analogue body.**

---

### Evaluation 1 Summary

All seven Protected Core provisions are individually justified. The justification quality varies:

| Provision | Justification Strength | Key Rationale |
|-----------|----------------------|---------------|
| Vote anonymity | Strong | Irreversibility of linkage; defines free election |
| Challenge rights | Strongest | Bootstrapping attack cascades to all other provisions |
| CIC existence | Strong | Interpretive infrastructure for entire architecture |
| AC-31 minimum | Strongest | TM-47 automatic activation; retroactive; Authenticity Root |
| Amendment process | Strong (logically necessary) | Meta-protection must match protection level |
| MA ratification | Strong | Sovereignty anchor for all future constitutional evolution |
| CAB independence | Strong | Operational foundation for Tier 3 item 2 |

No provision should be removed from the catalog. Challenge rights (item 2) and AC-31 minimum (item 4) have the strongest technical justifications in the matrix.

---

## Part C — Evaluation 2: Candidate Protected Provisions

*Were the following correctly excluded from Tier 3?*

---

### C.1 GovernanceState

**Assigned tier:** Tier 2
**E.3.2 justification:** Record-keeping aggregate; form should evolve; constitutional rights underlying GovernanceState protected through governance structure rather than specific technical form.

**ARB evaluation:**
The form-vs-function distinction is the correct constitutional principle. GovernanceState records phase authority — it does not constitute phase authority. The constitutional FUNCTION (temporal evidence of phase transitions must exist and be corroborated) is what matters. 38B-03 specified the governance model for this function: EC-Anchored Phase Specification + GovernanceAuthority Record + Multi-Party Corroboration + CIC Constitutional Challenge Resolution. This governance model is a Tier 2 specification.

**Risk of Tier 2 assignment:**
A Tier 2 amendment to GovernanceState's corroboration requirements (e.g., reducing multi-party corroboration threshold) could weaken temporal evidence without triggering Tier 3 protection. However, challenge rights (Tier 3 item 2) through S-2 standing protect the right to challenge GovernanceState decisions. If GovernanceState evidence is insufficient, a constitutional challenge is available. The protection is procedural but adequate for a record-keeping aggregate.

**Consequence of exclusion:** GovernanceState's specific form remains Tier 2 amendable. The temporal evidence function is protected through challenge rights rather than Tier 3 provision.

**ARB verdict: Correctly excluded.** Form vs. function distinction is architecturally sound. Challenge rights provide adequate indirect protection for the temporal evidence function.

---

### C.2 GovernanceAuthority

**Assigned tier:** Tier 2
**E.3.2 justification:** Operational governance aggregate; form should evolve; constitutional requirement for phase governance is protected through challenge rights.

**ARB evaluation:**
GovernanceAuthority is the aggregate authorized to govern election phase transitions. Its mandate comes from ElectionConstitution; its decisions are challengeable (challenge rights + CAB independence, both Tier 3). The constitutional FUNCTION (phase transitions must be authorized by a constitutionally mandated body) is protected through the combination of challenge rights and the EC's provision for governance authority. The specific aggregate form (GovernanceAuthorizationCommittee, ADR-2 Option B) is a governance mechanism.

**Risk of Tier 2 assignment:**
A Tier 2 amendment could change GovernanceAuthority's decision criteria, mandate scope, or independence requirements. However, any such change would be subject to CIC constitutional vetting and challengeable through S-1/S-2/S-3 standing.

**Consequence of exclusion:** GovernanceAuthority's form remains Tier 2 amendable. The constitutional function (authorized phase governance) is protected through challenge rights.

**ARB verdict: Correctly excluded.** Adequate indirect protection through challenge rights.

---

### C.3 CertificationAuthority Existence

**Assigned tier:** Tier 2
**E.3.2 justification:** Terminal evaluator but governance mechanism; elections can exist with different certification forms; independence principle protected through Tier 2; form should be able to evolve.

**ARB evaluation:**
This is the most contested exclusion in the catalog (flagged in INT-38B05-01). The ARB assessment requires distinguishing two questions:
1. Is CertificationAuthority EXISTENCE correctly Tier 2?
2. Is CertificationAuthority INDEPENDENCE correctly Tier 2?

**On existence:** The E.3.2 rationale is sound. CertificationAuthority as a specific aggregate form can evolve. International observation, external audit firms, multi-party certification panels — these are all candidate certification forms. Constitutional entrenchment of the specific 38B/ADR-6 model would prevent necessary evolution.

**On independence:** Here the exclusion is more contested. ADR-2 specified Option C (External Organization) as the independence form for CertificationAuthority — the strongest available independence form. If this independence form can be amended to a weaker form (e.g., Committee Independence) through Tier 2 process, CertificationAuthority could move from External Organization to a potentially captured committee while remaining nominally independent.

**Critical gap identified:** The E.3.2 rationale states "the independence principle of CertificationAuthority (Option C External) is protected through Tier 2." But the independence PRINCIPLE and the independence FORM are not the same thing. The independence PRINCIPLE (CA must be constitutionally independent) could remain textually while the independence FORM (External Organization) is downgraded. The architectural relationship between ADR-2's independence form decisions and EC tier provisions is UNSPECIFIED in 38B-05.

ADR-2 decisions are architectural decisions ratified by MA. Are they EC provisions subject to the tier system? Or are they separate architectural governance instruments amendable through a different process? This is not specified. If ADR-2 independence form decisions are not EC provisions, they may be amendable through MA ratification without the tier system's procedural protections at all.

**OBS-ADR6-02** identified CertificationAuthority as a constitutional concentration point whose compromise simultaneously invalidates CO-2, CO-3, CO-4, and CO-5. This characterization applies when CA is fully independent (External Organization). If CA independence is weakened through Tier 2 amendment, OBS-ADR6-02's concentration risk increases while the constitutional classification remains unchanged.

**Consequence of exclusion:**
CertificationAuthority independence form remains Tier 2 amendable. A Tier 2 amendment could weaken CA independence from External Organization to Committee Independence. Combined with the CA+CAB coalition risk (38A-03 F rating), a weakened CA with Tier 2 independence is a more exploitable concentration point.

**ARB verdict: Defensible but not fully resolved.** The exclusion is correct for CertificationAuthority EXISTENCE. The INDEPENDENCE FORM exclusion is more contested — its adequacy depends on the unspecified relationship between ADR-2 decisions and EC tier provisions. This is a design gap requiring explicit resolution. See Required Revision R3.

---

### C.4 AuditScopeAuthority / AuditExecutionAuthority

**Assigned tier:** Tier 2
**E.3.2 justification:** Implement constitutional right to audit (IR-H) but specific form is governance; challenge rights protect audit right through S-2 standing; over-constituting would prevent refinement.

**ARB evaluation:**
The audit scope + execution split (ADR-4) resolved Gap A-3 (completeness gap) through the Two-Tier Audit Model: Tier 1 (constitutional evidence categories in EC) + Tier 2 (election-specific expected evidence from AuditScopeAuthority). The constitutional FUNCTION (independent specification of what constitutes a complete audit) is what matters. The specific aggregates implementing this function should be able to evolve.

**Risk of Tier 2 assignment:**
A Tier 2 amendment eliminating AuditScopeAuthority would reopen Gap A-3 (completeness undefined). Challenge rights (S-2 standing) allow challenges to audit decisions, but they do not guarantee audit capability — only that audit decisions are challengeable. If the audit scope function is abolished, there is nothing to challenge.

**Mitigation:** The Two-Tier Audit Model's Tier 1 (constitutional evidence categories in EC) is the more fundamental protection. EC Tier 1 categories cannot be eliminated through Tier 2 amendment. AuditScopeAuthority's authority to specify Tier 2 expected evidence within EC Tier 1 categories could be eliminated, but the constitutional evidence category floor (Tier 1) would remain. A challenge could then be filed arguing that without AuditScopeAuthority, the completeness function is constitutionally unexecuted.

**Consequence of exclusion:** AuditScopeAuthority and AuditExecutionAuthority form remains Tier 2 amendable. Gap A-3 re-exposure risk is mitigated but not eliminated by EC Tier 1 category floor.

**ARB verdict: Correctly excluded.** Tier 1 EC category floor provides partial mitigation. Challenge rights provide procedural protection. The audit function is protected; the aggregate form correctly remains governable.

---

### C.5 Appointment Governance (38B-04 Model)

**Assigned tier:** Tier 2
**E.3.2 justification:** Specific appointment chains are governance mechanisms; independence principles of appointed authorities are protected (CIC independence item 3, CAB independence item 7); freezing 38B-04 model at Tier 3 would produce constitutional fossilization.

**ARB evaluation:**
The constitutionally load-bearing element of the appointment model is not the specific appointment chains but the independence of the resulting authorities. That independence is protected through:
- CIC independence: Tier 3 item 3
- CAB independence: Tier 3 item 7
- Challenge rights: Tier 3 item 2 (appointment decisions challengeable)

The appointment PROCESS is correctly Tier 2. The constitutional OUTCOME (independent authorities) is Tier 3 protected through the independence provisions.

**Risk of Tier 2 assignment:**
A Tier 2 amendment to appointment mechanics could gradually erode the independence of appointed authorities without formally violating the independence principle provisions. For example, changing term lengths, reappointment eligibility, or confirmation processes could systematically select less-independent candidates while leaving the independence principle intact on paper. This is the gradual erosion path (TM-09 Constitutional Drift applied to appointment mechanics).

**Mitigation:** The Tier 2 threshold (two-thirds supermajority) provides meaningful friction. CIC constitutional vetting would flag amendments that would predictably erode independence outcomes. Challenge rights enable challenges to appointment decisions that violate independence requirements.

**Consequence of exclusion:** Appointment mechanics remain Tier 2 governable. Gradual erosion risk exists but is mitigated by Tier 2 procedural protection and CIC vetting.

**ARB verdict: Correctly excluded.** The independence principle protection (Tier 3 items 3, 7) is the correct constitutional anchor. Appointment mechanics flexibility is constitutionally appropriate — the architecture should be able to improve appointment processes as constitutional understanding matures.

---

### C.6 Authority Independence Requirements (Non-CIC/CAB D43 Authorities)

**Assigned tier:** Not explicitly tiered in 38B-05.
**38B-05 coverage:** E.3.2 does not address the independence requirements for EnrollmentAuthority, CriteriaAuthority, AuditScopeAuthority, AuditExecutionAuthority, and CertificationAuthority as a class distinct from the specific aggregate FORM.

**ARB evaluation:**
This is a genuine gap. ADR-2 specified independence forms for all five D43 functions:
- Enrollment: Option B (Committee Independence — Enrollment Review Committee)
- Criteria: Option B (Committee Independence — Criteria Review Committee)
- Audit: Option D (Hybrid — constitutional scope + independent execution body)
- GovernanceAuth: Option B (Committee Independence)
- Certification: Option C (External Organization — strongest)

These independence forms are ADR-2 architectural decisions. Their relationship to EC tier provisions is unspecified (the same gap identified in C.3 above). If ADR-2 decisions are not EC tier provisions, they may be amendable through MA ratification without tier procedural protections. If they are EC tier provisions, which tier are they?

**The document implicitly assigns non-CIC/non-CAB independence requirements to Tier 2** — they would fall within the "authority aggregate mandates (within existing independence forms)" language of the Tier 2 definition (E.2). But this is implicit, not explicit. The E.3.2 table does not address this class of provisions.

**Consequence of exclusion:**
EnrollmentAuthority, CriteriaAuthority, AuditScopeAuthority, AuditExecutionAuthority, and CertificationAuthority independence requirements are implicitly Tier 2 amendable. A Tier 2 amendment to any of these independence forms would not trigger Tier 3 protection.

**ARB verdict: Implicit exclusion is defensible.** These independence forms are governance mechanisms — the specific committee structure, appointment process, and mandate scope should be able to evolve. However, the implicit assignment requires explicit specification. The EC design must explicitly assign the ADR-2 independence form decisions to a tier. This is a required EC design action, not a 38B-05 revision — but it should be named in the Open Questions.

---

### Evaluation 2 Summary

| Candidate | Verdict | Concern Level |
|-----------|---------|--------------|
| GovernanceState | Correctly excluded | Low |
| GovernanceAuthority | Correctly excluded | Low |
| CertificationAuthority existence | Correctly excluded | Low |
| CertificationAuthority independence FORM | Exclusion defensible; design gap | Medium |
| AuditScopeAuthority / AuditExecutionAuthority | Correctly excluded | Low |
| Appointment Governance (38B-04) | Correctly excluded | Low |
| Non-CIC/CAB independence requirements | Implicit exclusion; requires explicit EC assignment | Medium |

The two Medium-concern items both trace to the same design gap: the relationship between ADR-2 architectural decisions and EC tier provisions is unspecified. This gap should be explicitly resolved rather than left implicit.

---

## Part D — Evaluation 3: Protected Core Completeness

*Are important constitutional protections missing from the Tier 3 catalog?*

This evaluation identifies CANDIDATE omissions only. It does not add provisions to the catalog.

---

### D.1 Candidate A — Three Trust Root Structural Separation

**The gap:** The three trust roots — Legitimacy (ElectionConstitution), Authenticity (AC-31), Temporal (GovernanceState) — are architecturally distinct. The independence of each root from the others is a foundational property of the trustworthiness model. An amendment merging two roots (e.g., authorizing CIC to also serve as AC-31 certifier, combining Legitimacy and Authenticity roots) would change the structural architecture of the trustworthiness model.

**Would this trigger existing Tier 3 protection?**
- CIC independence (item 3) protects CIC from operational direction by authority aggregates. It does not prevent CIC from being granted additional mandates.
- AC-31 minimum (item 4) protects the AC-31 requirement from deletion. It does not protect the INDEPENDENCE of AC-31 from CIC.
- No other Tier 3 provision directly addresses trust root structural separation.

A Tier 2 amendment authorizing CIC to also serve as the AC-31 certifier would not clearly violate any of the seven Tier 3 provisions. It would collapse the Legitimacy and Authenticity roots. The trustworthiness model rests on these roots being structurally distinct — if the same body both interprets the constitution (Legitimacy root) and certifies evidence authenticity (Authenticity root), then a constitutional interpretation about authentication standards is self-validating.

**Significance:** HIGH. The DeepSeek parallel specification identified this as an unamendable provision. The cross-model tension (INT-38B05-01) flags this as the most significant candidate omission.

**Designation:** Evaluation 3, Candidate A — Three Trust Root Structural Separation

---

### D.2 Candidate B — D43 Constitutional Function Coverage

**The gap:** The five D43 functions (Enrollment, Criteria, Audit, GovernanceAuth, Certification) are required constitutional functions — each was identified as a legitimacy gap in Round 36D. The authority aggregates implementing these functions exist because the functions themselves are constitutional requirements.

**Would this trigger existing Tier 3 protection?**
A Tier 2 amendment abolishing EnrollmentAuthority (for example) would not clearly violate any of the seven Tier 3 provisions. Challenge rights allow challenging EnrollmentAuthority decisions — but if the authority is abolished, there are no decisions to challenge. The D43 function (enrollment authorization must exist) is architecturally required, but it is not explicitly Tier 3 protected.

**Significance:** MEDIUM. The constitutional requirement for D43 functions to exist is architecturally foundational but more remote from the immediate trustworthiness model than the trust root separation gap.

**Designation:** Evaluation 3, Candidate B — D43 Constitutional Function Coverage

---

### D.3 Candidate C — Constitutional Quorum for Amendment Action

**The gap:** The Tier 3 amendment threshold is near-unanimity (≥90%). This threshold is meaningful when MA has a functioning full membership. OQ-38B04-04 (MA unavailability when vacancies exist) and TM-42 (Operational Deadlock) both identified MA unavailability as a constitutional vulnerability. If MA membership has been significantly depleted, near-unanimity of a diminished MA may represent a minority of the constitutional sovereign.

**Would this trigger existing Tier 3 protection?**
A constitutionally valid MA session with diminished quorum could meet the 90% threshold with few members, while representing a small fraction of the full membership body. No existing Tier 3 provision specifies a constitutional minimum MA membership for amendment action.

**Significance:** MEDIUM-LOW. This is more an EC design gap (specifying minimum MA membership for constitutional action) than a Protected Core omission. The concern is real but secondary.

**Designation:** Evaluation 3, Candidate C — Constitutional Quorum for Amendment Action (EC design gap)

---

### Evaluation 3 Summary

Three candidate omissions identified, in order of constitutional significance:

| Candidate | Significance | Nature |
|-----------|-------------|--------|
| A — Three Trust Root Structural Separation | HIGH | Genuine Protected Core candidate; most significant cross-model tension |
| B — D43 Constitutional Function Coverage | MEDIUM | Constitutional function requirement not explicitly tiered |
| C — Constitutional Quorum | MEDIUM-LOW | EC design gap; secondary concern |

These are candidate identifications, not catalog additions. None of these candidates are added to the Protected Core Catalog by this review. They are carried to 38B Synthesis (pending authorization) as named design questions.

---

## Part E — Evaluation 4: AA-01 Consistency

*Does the selected model remain consistent with AA-01, OBS-38B04-04, and OBS-38B05-02? Does any Tier 3 provision implicitly assume stronger legitimacy than the architecture has discovered?*

**AA-01 Statement:** MA legitimacy is terminal and pre-constitutional. The constitutional architecture assumes MA legitimacy without being able to constitute it. This assumption is unresolved within the architecture.

**OBS-38B04-04:** Every additional MA constitutional function increases dependence on pre-constitutional AA-01 resolution.

**OBS-38B05-02:** The amendment chain is the vehicle for all future constitutional evolution; all future constitutional development depends on MA ratification, which depends on MA legitimacy, which depends on AA-01.

---

### E.1 Primary Question: Does Any Tier 3 Provision Claim to Be Beyond MA's Reach?

This is the constitutional test for AA-01 consistency. A provision that claims to be literally unamendable — beyond the reach of any MA action — would conflict with AA-01, because AA-01 establishes MA as the terminal constitutional authority. If MA is the terminal authority, nothing can be constitutionally beyond its reach.

**Test for each provision:**

| Provision | Does It Claim to Be Beyond MA's Reach? | AA-01 Consistent? |
|-----------|---------------------------------------|-------------------|
| Vote anonymity (Tier 3) | No — near-unanimous MA can remove via Tier 3 process | YES |
| Challenge rights (Tier 3) | No — near-unanimous MA can remove via Tier 3 process | YES |
| CIC existence (Tier 3) | No — near-unanimous MA can abolish CIC via Tier 3 process | YES |
| AC-31 minimum (Tier 3) | No — near-unanimous MA can delete AC-31 via Tier 3 process | YES |
| Amendment process itself (Tier 3) | No — near-unanimous MA can change the amendment process via Tier 3 | YES |
| MA ratification requirement (Tier 3) | No — near-unanimous MA can remove itself from the chain via Tier 3 | YES |
| CAB independence (Tier 3) | No — near-unanimous MA can remove CAB independence via Tier 3 | YES |

**Finding:** The graduated threshold model does not claim any provision to be beyond MA's reach. All Tier 3 provisions are formally amendable by near-unanimous MA action following Tier 3 process. This is AA-01 consistent.

---

### E.2 Secondary Question: Does Near-Unanimity Implicitly Assume Stronger Legitimacy?

The Tier 3 threshold (≥90% near-unanimity) is practically very difficult to achieve. Does requiring ≥90% MA ratification implicitly assume that a ≥90% MA consensus is constitutionally legitimate — when AA-01 says MA legitimacy itself is unresolved?

**Analysis:**
Near-unanimity does not claim stronger legitimacy than simple majority. Both are expressions of MA sovereign action. If AA-01 uncertainty undermines simple-majority MA legitimacy, it equally undermines near-unanimous MA legitimacy. The graduated threshold model does not compound AA-01 uncertainty — it applies it consistently across all amendment tiers. All tiers depend on MA legitimacy for the same reason; the difference is the threshold, not the legitimacy character.

**Finding:** Near-unanimity does not assume stronger legitimacy. It assumes the same quality of legitimacy as simple majority but requires quantitatively more of it. AA-01 consistent.

---

### E.3 OBS-38B04-04 Check: Does Amendment Chain Add to AA-01 Dependency?

OBS-38B04-04 states that every additional MA function increases dependence on pre-constitutional AA-01 resolution. The amendment ratification function (MA function #15) adds to this dependency, not by a new quality of dependence but by an additional dimension: now all FUTURE constitutional evolution depends on MA legitimacy, not just current governance.

**38B-05 Part I.2 (Terminal Legitimacy Dependency)** explicitly addresses this: "The amendment legitimacy chain terminates at MA legitimacy, which terminates at AA-01 (pre-constitutional assumption)."

**38B-05 Part J.2 (OBS-38B04-04 Forward Dependency)** explicitly states: "The entire future constitutional evolution of this architecture is contingent on a pre-constitutional assumption that is unresolved within the architecture."

**Finding:** AA-01 dependency from the amendment chain is explicitly documented. The document does not understate or ignore the dependency. OBS-38B04-04 consistent.

---

### E.4 OBS-38B05-02 Check: Is the Dependency Characterization Complete?

OBS-38B05-02 states that all future constitutional evolution depends on MA ratification, which depends on AA-01. This is formally stated in the ARB Decision Block.

**Refinement needed:** OBS-38B05-02 characterizes the dependency accurately. However, the document does not explicitly state a related corollary: **if AA-01 is not resolved before the first Tier 3 amendment is attempted, the legitimacy of that amendment — and all subsequent constitutional evolution — depends on an unresolved pre-constitutional assumption at the moment of maximum constitutional change.**

This is not a contradiction; it is a consequence. AA-01 resolution does not become MORE urgent as a result of 38B-05 — it was already urgent. But the amendment chain makes this urgency forward-permanent rather than merely present. The document partially acknowledges this but does not name it as a corollary to OBS-38B05-02.

**Finding:** OBS-38B05-02 is accurate but incomplete. The forward-permanent AA-01 dependency (urgency becomes permanent, not merely current) should be named as a corollary. This is a clarification, not a structural revision — suitable for a minor revision.

---

### Evaluation 4 Summary

The graduated threshold model is AA-01 consistent throughout:
- No Tier 3 provision claims to be beyond MA's reach
- Near-unanimity does not assume stronger legitimacy quality than simple majority
- OBS-38B04-04 dependency is correctly documented
- OBS-38B05-02 is accurate but could state the forward-permanent AA-01 urgency corollary explicitly

One minor clarification needed: the forward-permanent character of AA-01 urgency should be named in OBS-38B05-02 or as a corollary. See Required Revision R4.

---

## Part F — Evaluation 5: Constitutional Self-Destruction Analysis

*Does the Graduated Threshold model actually mitigate OBS-38A06-SD1, merely delay it, or merely document it?*

**OBS-38A06-SD1 Statement:** Constitutional Self-Destruction — MA can legally remove all constitutional protections via valid EC amendment paths if no constitutional floor exists. Gap 3 enables; Gap 4 amplifies. The self-destruction sequence is constitutionally valid without requiring adversarial capture — only constitutional process.

---

### F.1 What Constitutional Self-Destruction Requires Under the Selected Model

Under the Graduated Threshold model with Protected Provisions, a Constitutional Self-Destruction sequence must proceed as follows:

**Step 1 — Remove Challenge Rights (Tier 3 item 2):**
- Requires: ≥90% MA ratification + 180-day deliberation + CIC adverse opinion (guaranteed, since this is Tier 3) + 90-day challenge window
- All constitutional observers (S-2 standing) file challenges during challenge window
- CAB adjudicates
- If MA proceeds despite adverse CIC opinion and upheld challenges: constitutional crisis, not silent destruction

**Step 2 — Remove remaining Tier 3 provisions:**
- Now proceeding without challenge rights
- Still requires: ≥90% MA ratification + 180-day deliberation + CIC adverse opinion (guaranteed for each) + 90-day challenge window (but no standing parties to challenge since S-1/S-2/S-3 were removed in Step 1)
- Constitutional record exists for each removal (CIC documented each)

**Step 3 — Reduce remaining operational provisions:**
- Now through Tier 2 or Tier 1 process (amendment process removed in Step 2 or weakened through Step 1/2 sequence)

---

### F.2 What Does the Model Actually Achieve?

**Does it PREVENT Constitutional Self-Destruction?**
No. A near-unanimous MA with sufficient organizational coherence can execute the above sequence, following all Tier 3 procedural requirements, and constitutionally dismantle the architecture.

**Does it DELAY Constitutional Self-Destruction?**
Yes, materially. The sequence requires:
- Multiple Tier 3 amendment cycles, each requiring 180-day deliberation
- 2-year minimum cooling periods between consecutive Tier 3 amendments
- A complete self-destruction sequence would take a minimum of several years under the procedural requirements

**Does it DOCUMENT Constitutional Self-Destruction?**
Yes, in a constitutionally significant way. Each step in the sequence:
- Generates a CIC constitutional opinion documenting what is being removed and why it is constitutionally significant
- Creates a 90-day challenge window during which all constitutional actors can respond
- Generates a constitutional record in GovernanceState (38B-03)
- Is visible to all S-2 constitutional observers

But documentation alone is insufficient mitigation. Documentation without action is passive, not active.

---

### F.3 The Correct Characterization: Active Constitutional Friction

The graduated threshold model provides **active constitutional friction**, not merely documentation or delay. The distinction matters:

**Passive documentation:** The architecture records what happened. It does not change the probability or difficulty of the action.

**Mere delay:** The architecture adds time. Given sufficient time and organizational coherence, the outcome is unchanged.

**Active constitutional friction:** The architecture creates conditions under which the action becomes constitutionally more expensive, more visible, more contestable, and more likely to trigger organizational and political consequences that interrupt the sequence before completion.

The graduated threshold model provides active constitutional friction because:
1. **180-day deliberation windows** create mandatory periods during which external constitutional pressure can interrupt the sequence. Constitutional Self-Destruction cannot be completed between organizational meetings — it requires sustained commitment across years.
2. **CIC adverse opinions** create authoritative constitutional records that can be used by external actors (international observers, member organization constituents, courts) to contest the legitimacy of the sequence during and after.
3. **Challenge windows** create procedural opportunities for constitutional actors to intervene, even if the challenge mechanism itself is being removed.
4. **2-year cooling periods** mean that a single election cycle of political capture cannot be immediately converted into constitutional capture.

**Comparison with alternatives:**
- Option A (No floor): Constitutional Self-Destruction requires one MA vote and takes one day. Zero friction.
- Option B (Uniform Supermajority): Constitutional Self-Destruction requires supermajority coordination. Low friction.
- Option C (Unamendable clauses): Constitutional Self-Destruction is formally impossible — but as Part D.3 analysis shows, this requires claiming constitutional impossibility under AA-01 uncertainty, which is constitutionally dishonest.
- Option D (Graduated Threshold): Constitutional Self-Destruction is formally possible but practically requires years, extraordinary consensus, sustained organizational commitment, and operates under full constitutional visibility. High friction.

**The correct characterization of OBS-38A06-SD1 mitigation:**

The Graduated Threshold model **genuinely mitigates** OBS-38A06-SD1. The mitigation is not prevention (which would require claiming constitutional impossibilities), not mere documentation (which would provide no structural resistance), and not mere delay (which would only shift timing). It is active constitutional friction: a structural resistance that changes the practical feasibility, visibility, and political economy of Constitutional Self-Destruction without claiming to eliminate it.

For a voting platform used by real organizations for real elections, this is the appropriate level of protection. Constitutional Self-Destruction through the graduated threshold model would require organizational conditions (near-unanimous MA consensus sustained over years) that are effectively incompatible with the normal operating conditions of democratic organizations. These conditions are not impossible — they can be forced by external political capture of the organization — but they cannot occur silently, quickly, or casually.

---

### F.4 OBS-38B05-01 Assessment

OBS-38B05-01 states: "The Constitutional Self-Destruction risk cannot be architecturally eliminated within a sovereign assembly model. It can be made constitutionally expensive, visible, and challengeable."

**ARB assessment:** OBS-38B05-01 is accurate. The additional characterization "maximum constitutionally honest protection" is correct. The evaluation in Part K.5 of 38B-05 is well-reasoned.

**One refinement:** OBS-38B05-01 should explicitly state that the mitigation is ACTIVE friction, not merely documentation or delay. This distinction should be recorded. See Required Revision R5.

---

### Evaluation 5 Summary

| Question | Answer |
|----------|--------|
| Does the model prevent Constitutional Self-Destruction? | No — MA sovereignty is preserved |
| Does the model merely delay Self-Destruction? | Partially — the sequence takes years |
| Does the model merely document Self-Destruction? | Partially — CIC opinions create constitutional records |
| What does the model primarily provide? | Active constitutional friction — genuine mitigation |
| Is this adequate for a voting platform? | Yes — conditions for successful Self-Destruction are incompatible with normal democratic governance |

---

## Part G — OBS-38B05-04: Protected Core Assessment

**OBS-38B05-04 — Protected Core Assessment**

The Protected Core Catalog (seven provisions in E.3) is constitutionally justified across all seven entries. The justifications range from strong to logically necessary:

**Strongest justifications (threat-grounded):**
- AC-31 minimum: TM-47 automatic activation makes removal irreversible; Authenticity Root; retroactive impact; strongest technical justification in the catalog
- Challenge rights: Bootstrapping attack cascades to all other provisions simultaneously; structural priority relationship — this is the enforcement mechanism for all six other Tier 3 provisions

**Strong justifications (architecture-grounded):**
- Vote anonymity: Irreversibility of linkage once constitutionally permitted; defines the boundary between free and surveillance-enabling election architecture
- CIC existence: Interpretive infrastructure without which all other provisions become ambiguous under adversarial interpretation
- CAB independence: Operational foundation for challenge rights; CA+CAB coalition failure (unconditional F) would be constitutionally activated rather than adversarially captured

**Logically necessary (meta-protection):**
- Amendment process: A protection mechanism must be protected at least at the level of what it protects; this is a logical requirement, not a policy choice
- MA ratification: Sovereignty anchor for all future constitutional evolution; the amendment chain terminates at MA legitimacy; removing MA from the chain severs the only traceable legitimacy ground

**Internal consistency finding:**
Items 2 and 7 (challenge rights + CAB independence) are correctly paired as complementary protections. Item 5 (amendment process) is the meta-protection for all other six items. Items 3 and 4 (CIC existence + AC-31 minimum) correspond to the governance specifications of 38B-01 and 38B-02 respectively.

**No provision should be removed from the catalog.** All seven are individually justified and collectively consistent.

**One structural observation:** The catalog implicitly has a hierarchical priority structure that the document does not name:
- Tier 3 items 2 (challenge rights) and 7 (CAB independence) are the enforcement layer for all other provisions
- Tier 3 item 5 (amendment process) is the meta-protection for all other provisions
- Tier 3 items 1, 3, 4 (anonymity, CIC, AC-31) are the substantive constitutional rights
- Tier 3 item 6 (MA ratification) is the sovereignty anchor

This hierarchy is not a flaw — the flat catalog structure is appropriate for a constitutional instrument. But it is an analytical observation that is useful for 38B Synthesis.

---

## Part H — OBS-38B05-05: Candidate Protected Provision Assessment

**OBS-38B05-05 — Candidate Protected Provision Assessment**

The seven candidates evaluated in Evaluation 2 (Part C) are correctly assigned to Tier 2 with two identified design gaps.

**Correctly excluded (no revision needed):**
- GovernanceState: Form-vs-function distinction is architecturally sound
- GovernanceAuthority: Constitutional function (authorized phase governance) adequately protected through challenge rights
- AuditScopeAuthority / AuditExecutionAuthority: Audit function protected; aggregate form correctly governable; EC Tier 1 category floor provides partial backstop
- Appointment Governance (38B-04 model): Independence principles (Tier 3 items 3, 7) correctly protect independence outcome; appointment mechanics flexibility is constitutionally appropriate

**Design gaps requiring attention (not catalog additions — EC design actions):**

**Gap 1 — CertificationAuthority Independence Form:**
CertificationAuthority EXISTENCE is correctly Tier 2. CertificationAuthority independence FORM (ADR-2 Option C External) is implicitly Tier 2 through the Tier 2 definition language ("authority aggregate mandates within existing independence forms"). However, the relationship between ADR-2 architectural decisions and EC tier provisions is UNSPECIFIED. If ADR-2 decisions are not formally EC tier provisions, they may be amendable without tier procedural protection. The EC design must explicitly assign ADR-2 independence form decisions to the tier system. Until this is specified, CertificationAuthority independence FORM protection is architecturally ambiguous.

**Gap 2 — Non-CIC/CAB D43 Authority Independence Requirements:**
The independence requirements for EnrollmentAuthority, CriteriaAuthority, AuditScopeAuthority, AuditExecutionAuthority, and CertificationAuthority are not explicitly tiered in 38B-05. The document implicitly assigns them to Tier 2 through Tier 2 definition language. This implicit assignment requires explicit EC specification. The EC design must determine whether ADR-2 independence form decisions are EC Tier 2 provisions subject to the amendment tier system, or architectural governance instruments amended through a separate process.

**Evaluation 3 Candidate Omissions (most significant):**

The most significant candidate omission from the Protected Core Catalog is **Candidate A — Three Trust Root Structural Separation** (identified in OQ-38B05-05 and INT-38B05-01). The structural independence of the three trust roots (Legitimacy=EC, Authenticity=AC-31, Temporal=GovernanceState) from each other is not explicitly protected. An amendment collapsing two roots would not clearly violate any of the seven current Tier 3 provisions. This is the most architecturally consequential candidate omission.

**Candidate B — D43 Constitutional Function Coverage** is a secondary candidate omission. The D43 functions' existence requirement is architecturally foundational but more remote from the immediate trustworthiness model.

These are candidate identifications. Resolution belongs to 38B Synthesis and EC design — not to 38B-05 revision.

---

## Part I — OBS-38B05-06: AA-01 Consistency Assessment

**OBS-38B05-06 — AA-01 Consistency Assessment**

**Finding: The Graduated Threshold model is fully consistent with AA-01, OBS-38B04-04, and OBS-38B05-02.**

**Primary test (no provision claims to be beyond MA's reach):** PASSED. All seven Tier 3 provisions are formally amendable by near-unanimous MA action following Tier 3 process. The model explicitly preserves MA sovereignty throughout. Part D.3 (Option C — Unamendable Eternity Clauses) was rejected specifically because of the AA-01 legitimacy paradox: provisions that cannot be changed by any constitutional process are provisions whose protection ultimately depends on extra-constitutional legitimacy — the legitimacy of the original adoption decision. The graduated threshold model avoids this paradox.

**Secondary test (near-unanimity does not assume stronger legitimacy quality):** PASSED. Near-unanimity (≥90%) differs from simple majority in quantity, not in constitutional legitimacy character. AA-01 uncertainty applies identically to all MA action regardless of threshold.

**OBS-38B04-04 test:** PASSED. Amendment ratification as MA function #15 is correctly characterized as adding to AA-01 dependency. Part I.2 (Terminal Legitimacy Dependency) and Part J.2 (OBS-38B04-04 Forward Dependency) explicitly acknowledge this.

**OBS-38B05-02 test:** PASSED WITH CLARIFICATION NEEDED. OBS-38B05-02 accurately characterizes the forward-facing AA-01 dependency. The corollary that is not explicitly stated: the forward-permanent character of this dependency (urgency becomes permanent, not merely current) should be named. The dependency existed before 38B-05; the amendment chain specification makes it permanent and architecturally explicit. This is not a new dependency — it is the same dependency now formally and permanently embedded in the constitutional evolution mechanism.

**Specific finding on the MA Ratification Requirement (Tier 3 item 6):**
This provision has a self-referential structure: MA ratifies amendments, including the amendment that removes MA from the ratification chain. This is analogous to the CIC self-abolition scenario (Part F), but 38B-05 specifies a CIC recusal mechanism for self-abolition without specifying a parallel mechanism for MA self-removal. When MA ratifies its own removal from the ratification chain, who constitutes the "MA" that is doing the removing? Is it the same MA that would be absent from future amendment chains? This is a constitutional self-reference that the document acknowledges indirectly (I.2 Terminal Legitimacy Dependency) but does not resolve with a procedural mechanism. This is a candidate for an additional Open Question rather than a required revision — the self-referential structure is acknowledged in the document and its resolution involves constitutional philosophy beyond 38B-05's scope.

---

## Part J — OBS-38B05-07: Constitutional Self-Destruction Assessment

**OBS-38B05-07 — Constitutional Self-Destruction Assessment**

**OBS-38A06-SD1 Mitigation Classification: ACTIVE CONSTITUTIONAL FRICTION**

The Graduated Threshold model neither prevents nor merely documents Constitutional Self-Destruction. It provides active constitutional friction — a structural resistance that changes the practical feasibility, visibility, and political economy of Constitutional Self-Destruction.

**Operational characterization:**

*Formal prevention?* No. A near-unanimous MA with sufficient organizational coherence can legally execute a Constitutional Self-Destruction sequence. MA sovereignty is preserved. The model is constitutionally honest about this.

*Mere documentation?* No. Documentation records events without changing their probability or difficulty. The graduated threshold model changes both. The 180-day deliberation windows, CIC adverse opinions, 90-day challenge windows, and 2-year cooling periods collectively create structural resistance — they alter the feasibility calculus, not just the record.

*Mere delay?* No (or only partially). Delay implies the outcome is unchanged; only the timing shifts. Active constitutional friction changes more than timing: it changes the organizational conditions required for the action. Constitutional Self-Destruction under the graduated threshold model requires sustained near-unanimous organizational commitment across multiple years, during which the sequence is fully visible and contestable. These conditions are categorically different from the pre-38B-05 state (where Constitutional Self-Destruction required one MA vote and one day).

*Active constitutional friction?* Yes. The combination of:
(1) Near-unanimity threshold — extraordinary organizational consensus required
(2) 180-day deliberation — mandatory multi-month constitutional exposure period per Tier 3 amendment
(3) CIC adverse opinions — authoritative constitutional documentation of what is being destroyed
(4) Challenge windows — procedural intervention opportunities during the sequence
(5) 2-year cooling periods — multi-year minimum timeline for complete destruction

…creates active resistance. The political economy of Constitutional Self-Destruction is fundamentally changed: the sequence is costly, slow, and constitutionally audible from beginning to end.

**OBS-38A06-SD1 residual risk characterization (precision improvement on OBS-38B05-01):**

OBS-38B05-01 states: "Constitutional Self-Destruction cannot be architecturally eliminated within a sovereign assembly model." This is correct.

The additional precision: **Constitutional Self-Destruction under the graduated threshold model requires organizational conditions that are structurally incompatible with democratic governance under normal conditions.** Specifically:
- Near-unanimous sustained consensus across years is achievable only through extraordinary external political capture of the organization, not through internal constitutional evolution
- The deliberation and challenge windows create mandatory periods for constitutional response that democratic organizations would use to contest the sequence
- The CIC constitutional opinion paper trail would accompany each step with authoritative constitutional documentation of the destruction

The model does not make Constitutional Self-Destruction impossible. It makes Constitutional Self-Destruction politically expensive, organizationally demanding, and constitutionally visible in ways that democratic organizations typically cannot sustain against their own constitutional structure.

**Comparison to alternative models (supporting the graduated threshold selection):**

| Model | Self-Destruction Barrier |
|-------|-------------------------|
| Option A (No floor) | Zero — one MA vote |
| Option B (Uniform supermajority) | Low — supermajority coordination across one session |
| Option C (Unamendable clauses) | Claims impossible — but legitimacy paradox under AA-01 makes this constitutionally dishonest |
| Option D (Graduated threshold) | Active friction — years, near-unanimity, full constitutional visibility |

Option D is the maximum constitutionally honest protection available in a sovereign assembly model. OBS-38B05-01's characterization is confirmed.

---

## Part K — Required Revisions

**R1 — OBS Numbering Resolution (REQUIRED):**
Renumber the DeepSeek integration OBS-38B05-04 (Amendment Appeal Circularity, added in DeepSeek integration pass) to OBS-38B05-08. The ARB-prescribed designations OBS-38B05-04 through OBS-38B05-07 are reserved for this ARB review's outputs. Update all references to OBS-38B05-04 (Amendment Appeal Circularity) in 38B-05 and the C.5 forward reference to OBS-38B05-08. Update the footer integration notation.

**R2 — MA Self-Removal Procedural Gap (REQUIRED):**
Add OQ-38B05-06: When MA ratifies an amendment removing MA from the amendment ratification chain, what procedural mechanism (if any) applies? The CIC self-abolition scenario has a specified recusal mechanism (Part F); the MA self-removal scenario does not. Is the same procedural reasoning applicable? This is an EC design question analogous to OQ-38B05-01 (CIC recusal panel qualifications) — it cannot be resolved within 38B-05 but must be named.

**R3 — ADR-EC Tier Relationship Specification (REQUIRED):**
Add OQ-38B05-07: What is the relationship between ADR architectural decisions (particularly ADR-2 independence form decisions) and EC tier provisions? ADR-2 decisions (Option B Committee Independence for Enrollment/Criteria/GovernanceAuth, Option D Hybrid for Audit, Option C External for Certification) are architectural decisions ratified by MA. Are they EC Tier 2 provisions subject to the amendment tier system, or separate architectural governance instruments amendable through a different process? Until this is specified, CertificationAuthority independence FORM protection and non-CIC/CAB independence requirements are architecturally ambiguous (OBS-38B05-05, Gaps 1 and 2). This is an EC design prerequisite question.

**R4 — OBS-38B05-02 Forward-Permanent AA-01 Urgency (REQUIRED):**
Add a corollary to OBS-38B05-02: the amendment chain makes AA-01 dependency forward-permanent, not merely current. Before 38B-05, current governance depended on AA-01. After 38B-05, the constitutional vehicle for all FUTURE governance evolution depends on AA-01 permanently. The urgency of AA-01 resolution has shifted from a current-governance concern to a forward-permanent constitutional architecture concern. This corollary should be explicitly named in OBS-38B05-02 or as an adjacent observation.

**R5 — OBS-38B05-01 Active Friction Characterization (REQUIRED):**
Refine OBS-38B05-01 to explicitly state that the mitigation is ACTIVE constitutional friction, not merely documentation or delay. The current characterization ("constitutionally expensive, visible, and challengeable") is accurate. The addition: the combination of factors creates conditions that are structurally incompatible with democratic governance under normal conditions, changing the political economy of Constitutional Self-Destruction, not only its timeline.

---

## Part L — Final Verdict

### Assessment Summary

| Evaluation | Finding |
|------------|---------|
| 1 — Protected Core Justification | All 7 provisions: JUSTIFIED |
| 2 — Candidate Protected Provisions | 5 correctly excluded; 2 design gaps requiring EC specification |
| 3 — Protected Core Completeness | 3 candidate omissions identified (A: trust root separation = HIGH significance) |
| 4 — AA-01 Consistency | CONSISTENT throughout; 1 clarification needed (OBS-38B05-02 forward-permanent corollary) |
| 5 — Self-Destruction Analysis | ACTIVE FRICTION — genuine mitigation confirmed |

### Required Revisions Summary

| Revision | Type | Target |
|----------|------|--------|
| R1 | OBS numbering | Renumber DeepSeek OBS-38B05-04 → OBS-38B05-08 |
| R2 | New OQ | OQ-38B05-06: MA self-removal procedural mechanism |
| R3 | New OQ | OQ-38B05-07: ADR-EC tier relationship specification |
| R4 | OBS refinement | OBS-38B05-02: forward-permanent AA-01 urgency corollary |
| R5 | OBS refinement | OBS-38B05-01: active constitutional friction characterization |

All five revisions are minor. They add precision, document identified gaps, and resolve the OBS numbering conflict. None require redesigning the model, changing the catalog, or revising any architectural decision.

### Verdict

**APPROVED WITH MINOR REVISIONS**

The Protected Core Catalog and Graduated Threshold Model are constitutionally justified and internally consistent. The core architectural decisions — seven-provision Tier 3 catalog, near-unanimity threshold, CIC recusal mechanism, retroactive effect rules, MA concentration documentation at 15 functions — are sound.

The five required revisions are genuinely minor: OBS numbering cleanup, two additional Open Questions (MA self-removal and ADR-EC tier relationship), one OBS precision refinement (OBS-38B05-02 forward-permanent corollary), and one OBS characterization improvement (OBS-38B05-01 active friction).

**Authorization for 38B Synthesis is an ARB decision after required revisions are applied.**

The review does not authorize 38B Synthesis. Authorization is reserved for the ARB upon confirmation that R1 through R5 are applied to 38B-05.

---

## Required Outputs Summary

| Designation | Title | Location in Document |
|------------|-------|---------------------|
| OBS-38B05-04 | Protected Core Assessment | Part G |
| OBS-38B05-05 | Candidate Protected Provision Assessment | Part H |
| OBS-38B05-06 | AA-01 Consistency Assessment | Part I |
| OBS-38B05-07 | Constitutional Self-Destruction Assessment | Part J |

Note: OBS-38B05-08 (Amendment Appeal Circularity) is the renumbered designation for the DeepSeek integration observation previously labeled OBS-38B05-04. This renumbering is Required Revision R1.

---

*Round 38B-05-ARB-Review — ISSUED 2026-06-18*
*Review Scope: Protected Core Catalog and Graduated Threshold Model Evaluation*
*Verdict: APPROVED WITH MINOR REVISIONS (R1–R5)*
*38B Synthesis Authorization: PENDING application of R1–R5*
