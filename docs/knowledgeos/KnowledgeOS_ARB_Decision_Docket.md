# KnowledgeOS — ARB Decision Docket

| | |
|---|---|
| **Kind** | ⭐ **DECISION PREPARATION.** ⛔ ***No rediscovery · no redesign · no restructuring · no ontology expansion · no implementation. Recommendations only — every decision is the named authority's.*** |
| **Status** | ⚠️ **CANDIDATE — the agenda, not the outcome** |
| **Authority** | ⚠️ **Generated — never authoritative without human review.** *Per EEP: the platform does not approve itself* |
| **Role** | Chief Knowledge Governance Officer *(secretary to the ARB)* — 2026-08-03 |
| **Revision** | ⭐ **REV 2, 2026-08-03** — *Package 1 amended per PA review (the metric-definition question); Package 11 added (the Platform Architecture workstream, packaged as a DECISION, not launched)* |
| **Scope** | ⭐ **the ten D-items from Phase B.5, ordered by gating effect.** *Carried OQs (PM-1..5, SC-1..8, LG-5, MQ-7, OQ-K/CV series) are the annex inventory, not packaged here* |

> ### ⭐ **The bottleneck has shifted from finding answers to deciding which validated answers become canonical. This docket is that agenda — ordered so the decision that gates the others comes first.**

---

## 📋 Package 1 — **D-9 · Derive the Platform Cost metric; run the AIP-14 over-evolution check** *(FIRST — it gates the docket itself)*

| | |
|---|---|
| **Decision** | Compute the AIP-14 Platform Cost metric for the recent iterations and rule whether the over-evolution review fires |
| **Why it exists** | AIP-14: *platform-only iterations are exceptional; two consecutive trigger review.* ⚠️ **The tripwire cannot fire if the metric is never computed** |
| **Supporting evidence** | ⭐ the last ~48h produced **20+ platform-only documents and zero PublicDigit feature progress in this track** *(self-declared in four of them)*; the blind review flagged the pressure (risk #8) |
| **Opposing evidence** | the same register shows dense real product delivery (WP-6/7A/7B/7C, WP-3A/4A) in parallel; the platform work was commissioned, not drift |
| **Risk of adopting** | the check may halt platform work mid-queue — *which is what the control is for* |
| **Risk of rejecting** | AIP-14 becomes a dead letter; every later decision inherits an ungoverned exposure |
| **Downstream** | ⭐ **whether Packages 2–10 are worked now at all** |
| **Authority** | **ARB** |
| ⭐ **Recommendation** | **ADOPT — run the check before anything else on this docket** |

### ⛔⛔ Package 1 — AMENDMENT (REV 2): the metric must be DEFINED before it is RUN

> **PA review, accepted:** *"Should AIP-14 measure platform work by document count, by engineering effort, by elapsed time, or by delivered capability? A document count can be misleading."*
>
> ⛔ **And the amendment corrects THIS DOCKET'S OWN DEFECT:** *Package 1's supporting-evidence line — "~20+ platform-only documents in 48h" — **presupposed document count as the metric**, which is exactly the undecided question.* ⭐ **That line is DOWNGRADED from trigger evidence to illustration pending metric definition.**
>
> **D-9 therefore splits:**
>
> | | Decision | Recommendation |
> |---|---|---|
> | ⭐ **D-9a** | **DEFINE the Platform Cost measurement basis** — candidates: document count · engineering effort · elapsed time · **delivered capability** | ⭐ **ADOPT a basis of delivered-capability + effort; ⛔ reject raw document count** *(one strategic decision may produce many small governance documents; a large implementation may produce few)* |
> | **D-9b** | **RUN the over-evolution check** using the defined basis | **ADOPT — immediately after D-9a** |
>
> ⭐ *Package 1 remains FIRST on the docket. What changed is only that the gate must be built before it can be closed.*

## 📋 Package 2 — **D-5 · Ratify the enacted mission** *(one signature; unblocks the frame)*

| | |
|---|---|
| **Decision** | Ratify (or amend and ratify) the mission the machinery already enacts: *"provide a reusable engineering platform that improves the development of software systems through governed engineering knowledge"* |
| **Why it exists** | the mission is **enacted, unratified** — 8 artifacts, none product-specific; the empty *written* slot is what let AIP-14 be misread as the mission |
| **Supporting** | ES-005.3's litmus · ES-006.4's harvest · the pre-positioned Platform ≙ Adoption split · blind convergence (A-1/A-2) |
| **Opposing** | ratifying may be premature while the KnowledgeOS *product* gate is shut — ⚠️ *mitigated: the text is product-agnostic and does not open the charter* |
| **Risk adopt / reject** | low / *the vacancy keeps pulling constraints upward into mission-shaped misreadings* |
| **Downstream** | Phase C frame · MQ-7 *(a falsifier should be attached at ratification)* |
| **Authority** | **sponsor** |
| ⭐ **Recommendation** | **ADOPT** |

## 📋 Package 3 — **D-1 · Dispose E-1 (the EKP consumption model)** *(blocks the knowledge-model section)*

| | |
|---|---|
| **Decision** | Dispose the recorded falsification: ES-006's register — *"consumption model falsified by E-1; disposition PENDING ARB"* |
| **Why it exists** | ⭐ *"the single biggest architectural liability for any KnowledgeOS ambition"* — the executable knowledge platform's consumption model is the falsified part |
| **Supporting** | the E-1 record itself; the 13%-declared-scope finding; ten rediscoveries occurred **despite** the EKP existing |
| **Opposing** | the EKP's metadata model is recorded as *aligned*; its lint/graph machinery works |
| **Risk adopt / reject** | scoping the EKP to metadata+retrieval narrows a working asset / *Phase C would build a knowledge-model section on a falsified consumption story* |
| **Downstream** | Phase C knowledge model · D-8 · SC-6 |
| **Authority** | **ARB** |
| ⭐ **Recommendation** | **ADOPT a scoped disposition** — *acknowledge the falsification, retain the metadata/lint layer, defer any consumption redesign to evidence* |

## 📋 Package 4 — **D-8 · Reconcile the three knowledge ontologies** *(blocks the same section)*

| | |
|---|---|
| **Decision** | Rule the scopes of L-A *(executable, artifacts)* · the Metamodel *(CANDIDATE, gate OQ-ENG-004)* · RQ-002 *(COMPLETE, dimensional)* |
| **Why it exists** | one context, three competing models — both reviews, independently |
| **Supporting** | RQ-002's smoking guns; L-A already practises the orthogonality RQ-002 prescribes *(status ⟂ authority)* |
| **Opposing** | the Metamodel has a pre-declared adoption gate *(OQ-ENG-004)* that has not run |
| **Risk adopt / reject** | choosing a frame before OQ-ENG-004 pre-empts a declared gate / *a fourth ontology will eventually be commissioned by someone who cannot find the three* |
| **Downstream** | Phase C knowledge model · MM-1..MM-4 · the meta-model candidate |
| **Authority** | **ARB** |
| ⭐ **Recommendation** | **ADOPT RQ-002 as the dimensional frame; SCOPE the other two under it** *(L-A = the artifact projection of it; Metamodel proceeds to its own gate)* |

## 📋 Package 5 — **D-3 · Amend the frozen Phase-02 map via AIP-13** *(blocks the context-map section)*

| | |
|---|---|
| **Decision** | Admit or reject the R-63/R-64 work-package/meta-governance boundary as a candidate context; dispose BC-5's dormancy |
| **Why it exists** | the frozen six-context map lacks the operationally hottest boundary; the amendment path exists and is unused *(C-7)* |
| **Supporting** | R-63 declared the scope from operational validation; the blind review found the drift independently |
| **Opposing** | R-64 itself deferred the second context *pending sustained evidence* — the map's caution is deliberate |
| **Risk adopt / reject** | premature context admission / *the canonical map keeps describing a system that no longer exists* |
| **Downstream** | Phase C context map · PD-*/BC-* reconciliation *(R-4)* |
| **Authority** | **ARB** |
| ⭐ **Recommendation** | **ADOPT the amendment procedure; within it, DEFER the new context per R-64's own bar and annotate BC-5 as dormant-observed** |

## 📋 Package 6 — **D-2 · Mint CAP-001's authorization — or rule the register incomplete**

| | |
|---|---|
| **Decision** | Retroactively mint CAP-001's authorization/acceptance (with disclosure), or rule that PA commissions are a second lawful authorization channel |
| **Why it exists** | catalog says *unauthorized*, README says *REALIZED*, register says nothing — *"the register is provably not the complete record of authorizations"* (C-2/D-3 finding) |
| **Supporting** | append-only register supports disclosed retroactive minting *(R-53 precedent for annotation)*; ⭐ *the minting itself must pass PMR-10 — **CAP-001 would check the ruling that authorizes CAP-001*** |
| **Opposing** | retroactive minting could normalize after-the-fact authorization — ⚠️ *mitigated by pairing with the C-2 process fix: commissions that authorize work must mint, forward-looking* |
| **Risk adopt / reject** | low, with disclosure / *the traceability thesis carries a known counterexample forever* |
| **Downstream** | capability-status claims in Phase C · the commissioning practice |
| **Authority** | **DA** |
| ⭐ **Recommendation** | **ADOPT — mint with disclosure + adopt the forward rule** |

## 📋 Package 7 — **D-4 · Ratify (or decline) the ES set, Reference Architecture, Metamodel**

| | |
|---|---|
| **Decision** | Schedule and run ratification for the six PROPOSED standards; the DRAFT kernel per its own lifecycle; the Metamodel via OQ-ENG-004 |
| **Why it exists** | *"the platform governs by a proposed constitution"* — the exact over-claim class it polices elsewhere |
| **Supporting** | operational evidence is unusually strong: used daily; ⭐ **survived a foreign-domain bootstrap with zero translation (EEP) and discriminating force (tactical principles)** |
| **Opposing** | the kernel's lifecycle requires *"one complete engineering cycle"* — arguably satisfied; the ARB must judge |
| **Risk adopt / reject** | freezing text that still moves / *permanent PROPOSED status erodes every downstream claim* |
| **Downstream** | Phase C labels everything; D-5's mission cites the Reference Architecture |
| **Authority** | **ARB** |
| ⭐ **Recommendation** | **ADOPT — begin with ES-005 and the EEP** *(most exercised, most foreign-domain evidence)* |

## 📋 Package 8 — **D-7 · Issue the canonical register list (G-1)**

| | |
|---|---|
| **Decision** | Enumerate the governed register(ns) — the authority act CAP-001's INCONCLUSIVE rate measures the absence of |
| **Supporting** | baseline: every non-R/PMR series returns INCONCLUSIVE by design; the `ADR` series collapse and the split R register are known hazards |
| **Opposing** | none found — cost is one list |
| **Risk adopt / reject** | trivial / *the dominant verdict of the only operating capability remains "no criterion exists"* |
| **Downstream** | CAP-001 usefulness · PMR-10 enforcement · identifier UL |
| **Authority** | **the Authority** *(register discipline, AP-4)* |
| ⭐ **Recommendation** | **ADOPT** |

## 📋 Package 9 — **D-6 · State that role separation is session-simulated**

| | |
|---|---|
| **Decision** | One governance clause: separation of duties is real as process, currently simulated by fresh sessions, with named limits |
| **Why it exists** | C-6 — *"the corpus states everything else this honestly; this it does not state"*; SC-2 succession risk adjacent |
| **Supporting** | blind review's inference from owner fields; the fresh-session instrument is itself doubly validated *(A-12)* — so the clause can cite working evidence |
| **Opposing** | none of substance — ⚠️ *phrasing must not overclaim what a session boundary provides* |
| **Risk adopt / reject** | low / *a credibility gap in a governance product whose pitch is separation of duties* |
| **Authority** | **DA** |
| ⭐ **Recommendation** | **ADOPT** |

## 📋 Package 10 — **D-10 · Admit "model amnesia prevention" as a platform requirement candidate**

| | |
|---|---|
| **Decision** | Admit C-1 (n=11) to the requirement-candidate register; remedy selection stays open *(PG-8's index is the standing candidate)* |
| **Why it exists** | *"discover → model → forget → rediscover"* is the disease the platform exists to cure — and the programme has it, measured |
| **Supporting** | n=11 clears any repetition bar; the indexed/unindexed correlation *(as corrected at n=10)*; ⭐ *the coverage map demonstrated the counter-behaviour working* |
| **Opposing** | E-1's falsification of the EKP consumption model warns against assuming an index alone cures it *(occurrence #10 came from an INDEXED region)* |
| **Risk adopt / reject** | none at candidate level / *the platform's central failure mode stays unnamed in its requirements* |
| **Authority** | **ARB** |
| ⭐ **Recommendation** | **ADOPT as candidate** — ⛔ *no remedy designed here* |

---

## 📋 Package 11 — **Authorize the "KnowledgeOS Platform Architecture" workstream** *(NEW — packaged, deliberately NOT launched)*

| | |
|---|---|
| **Decision** | Open the Phase-II workstream the PA review proposes: platform domains → platform services → responsibilities → interactions → capabilities → execution assets; Core/Supporting/Generic; the `init` → PKS operational flow |
| **Why it exists** | ⭐ **the PA declares Phase I (knowledge engineering) ENDED and the Semantic Architecture "stable enough"** — the missing level is operational architecture, and the three-model split *(Strategic ≠ Semantic ≠ Documentation)* is legitimate and convergent with the L-A/L-B/L-C finding |
| **Supporting evidence** | the reconciled concept ledger; the 12 blind-validated conclusions; the coverage map showing discovery answers without rediscovery |
| ⛔ **Opposing evidence** | **(a)** this workstream IS Phase-C-class work — ⛔ **the B.5 gate stands and its own first door is D-9, which this same review says not to rush** · **(b)** ⭐ **"services" collides with adopted stance N-10 / Reference Architecture §1** — *"an engineering governance architecture — NOT a software system… no orchestrator, workflow engine, state machine… only after operational evidence demonstrates the governance model is insufficient."* *If "service" means a responsibility grouping, the view is lawful; if it means software machinery, the N-10 evidence bar applies — and the only evidence so far is ONE capability's manual-adoption failure (A-11), which authorizes a question, not a service layer* · **(c)** launching another platform workstream while D-9 is unresolved is the exact AIP-14 exposure under examination |
| **Risk of adopting now** | the platform starts producing platform work "until it exists for itself" — ADR-AIP-02's own named failure mode, with the tripwire not yet built (D-9a) |
| **Risk of rejecting** | the operational-architecture level stays unmodeled and Phase C, when opened, inherits the noun-model gap the PA identified |
| **Downstream** | Phase C in full · the capability↔AST link (SC-6) · the init→PKS hypothesis (n=0) |
| **Authority** | **ARB + sponsor** *(it is the Phase-C opening act by another name)* |
| ⭐ **Recommendation** | ⛔ **DEFER until D-9a/D-9b and the three doors resolve — then ADOPT as Phase C's operational-architecture view, with "service" DECLARED as responsibility-grouping unless the N-10 evidence bar is separately cleared** |

## Annex — carried open-question inventory *(not packaged; listed so nothing is silent)*

**PM-1..PM-5** *(authority split · standing-change acts · vocabulary parsimony · "Qualification" · capability-as-composite)* · **SC-1..SC-8** *(semantic conflict ledger — incl. ⭐ SC-6 capability↔AST linkage, new)* · **LG-5 / CV-4** *(SPECIALIZES + the transformational relationship group)* · **MQ-7** *(the mission's falsifier — attach at Package 2)* · **OQ-K1..K6 · OQ-C1..C2 · CV-1..CV-5 · MM-1..MM-4 · IR-2..IR-5** *(several are subsumed by Packages 1–10; the remainder await their own dockets)*.

## ⭐ The docket in one view

| # | Item | Authority | Recommendation | Gates |
|---|---|---|---|---|
| 1 | **D-9a define the metric → D-9b run the check** | ARB | **ADOPT — first** *(basis: delivered-capability + effort; ⛔ not document count)* | ⭐ **everything below** |
| 2 | **D-5 mission ratification** | sponsor | ADOPT | Phase C frame |
| 3 | **D-1 E-1 disposition** | ARB | ADOPT (scoped) | knowledge model |
| 4 | **D-8 ontology reconciliation** | ARB | ADOPT (RQ-002 frame) | knowledge model |
| 5 | **D-3 map amendment** | ARB | ADOPT (procedure; defer the context per R-64) | context map |
| 6 | **D-2 CAP-001 minting** | DA | ADOPT + forward rule | capability claims |
| 7 | **D-4 ratification** | ARB | ADOPT (ES-005 + EEP first) | all labels |
| 8 | **D-7 register list** | Authority | ADOPT | CAP-001 |
| 9 | **D-6 separation clause** | DA | ADOPT | credibility |
| 10 | **D-10 amnesia requirement** | ARB | ADOPT as candidate | platform evolution |

---

*Traceability: ARB Decision Preparation commission 2026-08-03 · ten one-page packages, each with decision / rationale / supporting / opposing / risks both ways / downstream / authority / recommendation · **ordered by gating effect: D-9 first because the tripwire governs whether the rest are worked now** · recommendations are the secretary's; **every decision is the named authority's** · carried OQ inventory annexed so nothing is silent · ⛔ **no rediscovery · no redesign · no restructuring · no ontology expansion · no implementation.***

> **⛔ Submitted to the Decision Authority. Nothing in this docket executes. It is an agenda.**
