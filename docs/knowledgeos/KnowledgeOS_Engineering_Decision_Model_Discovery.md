# KnowledgeOS — Engineering Decision Model Discovery

| | |
|---|---|
| **Kind** | ⭐ **STRATEGIC DISCOVERY — what decisions does KnowledgeOS exist to help humans make correctly?** ⛔ ***No new concept · no ontology redesign · no implementation · every conclusion a hypothesis until evidenced.*** |
| **Status** | ⚠️ **CANDIDATE — NOT ADOPTED** |
| **Authority** | ⚠️ **Generated — never authoritative without human review** |
| **Commission** | Decision Model Discovery, 2026-08-03 — *"the purpose of a domain model is not classification; it is to enable better domain decisions"* |
| ⭐ **Freeze lawfulness** | Knowledge Architecture stream — this pass CONCLUDES the ontology arc by binding it to decisions; it grows nothing |
| ⭐ **The DDD rule** | **Evans' question governs every row: *what decisions become easier because of this model?*** |

> # ⭐⭐ **CHECK-BEFORE-DISCOVERING — THE DECISION MODEL ALREADY EXISTS, AND IT IS CANON**
>
> ⛔ **`engineering/architecture/reference/Engineering_Decision_Model.md` — DRAFT, ARB-ACCEPTED 2026-07-11, `Owner: Decision Authority`, pointed to by the FROZEN runtime binding in `.claude/CLAUDE.md`.** It already contains:
>
> | Already there | Verbatim |
> |---|---|
> | ⭐ **The commission's own thesis** | *"The Standards say what the rules are; **this model says what decisions an Engineer resolves with them**"* |
> | **A universal pattern** | `Question → Decision → Authority → Procedure → Evidence` |
> | **A catalog of EIGHT named decisions** | `DetermineConcern · DetermineArtifactLifecycle · DetermineReusePotential · DetermineArtifactType · DeterminePlacement · DetermineApplicableStandards · DetermineQualificationMethod · DeterminePromotionPath` |
> | ⭐ **A stopping rule** | *"No new engineering decisions will be added unless operational evidence demonstrates insufficiency… the first question is: **Which existing decision does this extend?**"* |
> | **The authority stance** | *"the AI **evaluates and recommends** — authority remains with governance"* |
>
> ⭐⭐ **And the platform already MEASURES itself in the commission's currency:** CAP-001's Capability Evidence Record counts **`decisions-changed-by-tool: 1`** as its success metric — *decision-centricity is not a proposal; it is the platform's own yardstick.*
>
> **Had this commission "discovered" a decision model, it would have been occurrence #12 of proposing-before-searching. Caught.** ⛔ **Therefore the commission is re-read the only lawful way: RECONCILE the DECLARED catalog against the ENACTED decision record — and bind the ontology to it.** *(The reviewer's engineer-workflow sketch `Problem → Decision → Knowledge → Architecture → Implementation` is also already canon: the standing Development Discipline rule — business need → model → **decision** → tests → code.)*

---

## 1. Q1 — the enacted decision inventory, and its first structural finding

**The repository's decisions live in two records with different natures — and the corpus already has the vocabulary for the split** *(the Progression Model's GOVERNANCE-ACT kind; R-34's separation of evidence from acceptance)*:

| Class | Who resolves | The enacted record | Indexed as a catalog? |
|---|---|---|---|
| ⭐ **ENGINEER decisions** *(resolution: apply a governed procedure)* | the working engineer, human or AI | every deliverable's derived placement *(exit 0)* · harvest answers · lifecycle triage | ✅ **YES — the eight of the Decision Model** |
| ⭐⭐ **GOVERNANCE decisions** *(resolution: an authority act)* | DA · ARB · sponsor | ⭐ **R-1..R-77** *(the Platform Rulings log — 77 enacted rulings)* · DR-1..DR-8 · the ADR series · the **Decision Docket** *(the pending register, D-1..D-9b, 11 packages)* | ⛔ **NO — recorded richly, cataloged nowhere** |

> ### ⭐⭐ **FINDING 1: the platform has a decision CATALOG for engineers and only a decision LOG for governance.**
> *The engineer side says in advance which decisions exist and how each is resolved. The governance side is discoverable only retrospectively, by reading 77 rulings.* ⚠️ **Whether that asymmetry is a defect or the correct shape (authority acts should perhaps never be pre-enumerated) is itself a governance question — recorded as U-DM-1, not resolved here.**

## 2. Q2 + Q3 — explicit vs implicit, tested against the stopping rule

**Discipline:** every recurring decision found in the enacted record is first asked the stopping rule's own question — *"which existing decision does this extend?"* Only what extends **nothing** may count as insufficiency evidence.

| Recurring decision *(observed)* | Extends | Verdict |
|---|---|---|
| promote / defer | `DeterminePromotionPath` + ES-006.1 | ✅ **explicit** |
| platform or product? | `DetermineConcern` · `DeterminePlacement` — ⭐ **the one MECHANIZED decision** *(`doc-placement.php`, exit 2 = unruled → escalate)* | ✅ **explicit** |
| ephemeral or governed? | `DetermineArtifactLifecycle` *(deletion litmus)* | ✅ explicit *(CANDIDATE status, pilot-gated)* |
| harvest or not? | `DetermineReusePotential` *(ES-006.4)* | ✅ explicit |
| what kind of thing is this? | `DetermineArtifactType` — ⭐ *the archetype classification of the Meta-Model pass EXTENDS this decision rather than adding one* | ✅ explicit |
| approve / reject work · accept / reject completion | EP-01 · EP-02 · R-34 | ✅ **explicit — governance class** |
| verdict on evidence | the closed verdict sets *(with the recorded ES-003.1 vs CAP-001 collision — MVK reach #7)* | ✅ explicit, ⚠️ **two colliding vocabularies, unscoped** |
| ⛔ **FREEZE / UNFREEZE** | ⭐⭐ **extends NOTHING found.** Exercised **n≥3** *(methodology freeze 2026-08-01 · architecture freeze 2026-08-03 · corpus freeze Phase A)* — **each time by declaration, under no named authority, procedure, or reversal condition** | ⛔ **IMPLICIT — the strongest insufficiency candidate the stopping rule's own test admits** |
| ⛔ **RETIRE / PRESERVE knowledge** | extends nothing — **PM-6 asked exactly this and stands OPEN**; retirement meanwhile happens informally by supersession | ⛔ **IMPLICIT — already on the research backlog; second insufficiency candidate** |
| ⚠️ commission a CHALLENGE? | the challenge capability exists *(C-12, kernel)*; **the decision WHEN to invoke it is nowhere stated** — blind review, falsification pass, hostile replication were each ad-hoc commissioned | ⚠️ **implicit — capability governed, trigger ungoverned** |
| ⚠️ adopt TERMINOLOGY *(UL change)* | recurring *(INTENDED withdrawn · Boundaries≠Triggers · kinds→archetypes)*; each docketed case-by-case *(MM-1, D-8)* | ⚠️ **implicit — handled, never patterned** |

> ### ⭐ **FINDING 2: exactly TWO recurring decisions extend nothing in the existing set — FREEZE and RETIRE — and both already carry the operational evidence the stopping rule demands (n≥3 and an open PM-6 respectively).** *They are submitted as insufficiency EVIDENCE for the Decision Model's own ladder — ⛔ not as new catalog entries, which only the ARB may mint.*

## 3. Q4 — which ontology elements exist BECAUSE they support a decision

**Evans' test, run over the ontology — each element must name the decision it makes easier:**

| Ontology element | The decision it serves | ⭐ The proof it is load-bearing |
|---|---|---|
| ⭐⭐ **KNOWLEDGE ≠ ARTIFACT (I-1)** | `DetermineArtifactLifecycle` | ⭐⭐ **the deletion litmus — *"can this be deleted without loss of governed knowledge?"* — is UNASKABLE unless knowledge and its file are different things.** *The catalog's own procedure presupposes the ontology's central split* |
| **AUTHORITY-SCOPE** *(dimension)* | `DetermineConcern` / `DeterminePlacement` | the mechanized decision reads exactly this |
| **GOVERNANCE-STATUS** *(dimension)* | `DeterminePromotionPath` | the ladder's rungs ARE its values |
| **NATURE: normative force** *(the demoted "Governance Asset")* | `DetermineApplicableStandards` | *what governs me?* is answered by normativity, not by kind |
| **DP-n anchoring** | the approve-capability-change decision | 1:1 CAP↔DP names whose policy a change touches |
| **CONTAINER boundary** *(candidate)* | *what moves together* — the extraction/adoption decision | ⛔ the MVK FAIL = this decision made without the model |
| **PROJECTION + attestation** *(corrected I-4)* | *trust or rebuild?* | regenerate freely ⇔ non-authoritative until attested |
| **EVIDENCE-STATUS** *(candidate dimension)* | the earns/grants promotion gate | *"evidence EARNS, governance GRANTS"* |
| **the four partial orders** | *in which direction may change propagate* | the thin back-edge (n≈3) is visible only through them |

## 4. Q5 — elements supporting NO observable decision *(the commission's honesty clause)*

| Element | Evidence of decision-support | Disposition |
|---|---|---|
| ⛔ **DOMAIN** | ⛔⭐ **the frozen Phase-02 BC map existed through the entire discovery programme and was NEVER CONSULTED (occurrence #11). No recorded decision changed because something was classified into PD-1..PD-6.** ⚠️ *Honest caveat: domains organized the discovery work itself — a scaffolding use, not a decision use* | ⛔ **flagged: candidate for future SIMPLIFICATION pressure, per the commission's instruction — NOT a removal proposal.** *Converges with the ontology pass: the archetype that owns nothing also decides nothing yet* |
| ⚠️ **PURPOSE** | no decision has been changed by consulting the mission *(enacted, UNRATIFIED — D-5 pending)*; its §5 use in the ontology was a test, not a decision | ⚠️ **suspended judgment — an unratified purpose CANNOT yet support decisions; D-5 decides whether it starts to** |
| **everything else** | maps in §3 | ✅ earns its place |

## 5. The answer to the commission's single question

> ## **"What engineering decisions is KnowledgeOS designed to help humans make correctly?"**
>
> # ⭐ **The eight indexed Engineer decisions — resolved daily through governed procedures — and the Governance decisions it PREPARES BUT NEVER MAKES.**
>
> **The second half is as load-bearing as the first:** the docket pattern *(evidence → options → human ruling)*, R-34 *(engineering never accepts its own work)*, and the Decision Model's own stance *(“the AI evaluates and recommends — authority remains with governance”)* all state the same boundary: ⭐ **for authority decisions, the platform's product is DECISION-READINESS, not decisions.** *77 rulings were made BY humans; the platform's contribution was that each arrived shaped: evidence graded, options separated, unknowns carried.*

## 6. The review's recommendations — routed, not adopted

| Recommendation | Check-before-adopting | Route |
|---|---|---|
| *"every ontology element must answer: which decision does it improve?"* | ⭐ **the genuinely NEW part of the review** — parsimony rules exist (×2) but none names decision-support as an admission criterion. ⭐ *It is also the same move the Decision Model already made for decisions ("ES-001.1 parsimony, applied to the decisions themselves") — now proposed for the ontology* | ⭐ **docket — rider on D-8**, as the ontology's admission criterion |
| *"freeze ontology discovery after the current reconciliation"* | consistent with the standing three-stream freeze; **this document ends the ontology arc** — §3/§4 are the binding the reviewer asked for | recorded; the freeze declaration itself is a governance act *(and — FINDING 2 — FREEZE is an ungoverned decision type)* |
| *"the promotion ladder should govern the ontology itself"* | ES-006.1 exists; applying it to ontology elements is a scope extension | docket — D-8 |
| the four-part admission test | three of four parts exist *(explains-new-evidence · multiple-observations · not-expressible)*; the fourth is the decision-support criterion above | subsumed by the first row |

## 7. Remaining unknowns

| # | Unknown | Waits on |
|---|---|---|
| **U-DM-1** | **Is the catalog/log asymmetry (§1) a defect or the correct shape?** *Should governance decisions ever be pre-enumerated?* | ARB |
| **U-DM-2** | **FREEZE as a decision type** — n≥3 enacted, no authority/procedure/reversal named | the Decision Model's own ladder *(insufficiency evidence submitted)* |
| **U-DM-3** | **RETIRE** — PM-6, already backlogged | research backlog tier ruling |
| **U-DM-4** | ⭐ **The Decision Model's own adoption test is UNEVENLY met**: `DeterminePlacement` is exercised constantly *(mechanized, every deliverable)* and `DetermineReusePotential` at retrospectives — ⚠️ **but no evidence was found that the catalog is consciously consulted as a whole; its DRAFT status requires "one real engineering cycle" of exactly that** | EP-02 of the next real cycle |
| **U-DM-5** | Whether DOMAIN's decision-silence is permanent *(simplify)* or dormant *(a second product would need domain classification to route adoption)* | the second-adopter gate — the same trigger as everything else |

---

## ⭐ Closing

| | |
|---|---|
| ⭐⭐ **The commission's premise, corrected** | the decision model did not need discovering — **it needed FINDING: it is canon, ARB-accepted, and pointed to by the frozen runtime binding.** The lawful work was reconciliation |
| ⭐ **Finding 1** | engineer decisions have a CATALOG; governance decisions have only a LOG *(77 rulings)* — the asymmetry is recorded, not judged |
| ⭐ **Finding 2** | **FREEZE and RETIRE are the only recurring decisions that extend nothing in the catalog — both carry the operational evidence the stopping rule demands** |
| ⭐⭐ **The DDD result** | the ontology's central split (I-1) is the precondition of the catalog's own deletion litmus — **ontology and decision model are already load-bearing for each other**; DOMAIN and PURPOSE are the two elements that currently decide nothing |
| ⛔ **Nothing minted** | no new decision, no new concept — two insufficiency candidates submitted to the model's own ladder; three riders routed to D-8 |

---

*Traceability: Decision Model Discovery commission 2026-08-03 · ⭐⭐ **check-before-discovering: `Engineering_Decision_Model.md` EXISTS as canon (DRAFT, ARB-accepted 2026-07-11, eight decisions, stopping rule, Owner: Decision Authority) — the commission re-read as reconciliation, preventing occurrence #12 of proposing-before-searching** · **enacted record reconciled: R-1..R-77 rulings + DR-1..8 + docket vs the eight-decision catalog** · **Finding 1: catalog-for-engineers vs log-for-governance asymmetry (U-DM-1)** · **Finding 2: FREEZE (n≥3) and RETIRE (PM-6) extend no existing decision — submitted as insufficiency evidence per the stopping rule** · **Q4 answered: nine ontology elements each bound to the decision they serve; I-1 underwrites the deletion litmus** · **Q5 answered: DOMAIN (BC map never consulted — occurrence #11) and PURPOSE (unratified) flagged as the two decision-silent elements** · **review's four recommendations routed to D-8/backlog; none adopted** · ⛔ **no new concept · no redesign · no implementation.***

> **⛔ Submitted to the Decision Authority. Nothing in this document executes. The docket remains the agenda.**
