# KnowledgeOS — Engineering Knowledge Progression Model

| | |
|---|---|
| **Kind** | ⭐ **PURE DISCOVERY.** ⛔ ***No lifecycle extended · no Authority State Machine designed · no YAML modified · no new concept · no new relationship · no ADR · no implementation.*** |
| **Status** | ⚠️ **CANDIDATE — NOT ADOPTED** |
| **Authority** | ⚠️ **Generated — never authoritative without human review** |
| **Commission** | Principal Knowledge Engineer / Strategic DDD Architect / Enterprise Software Architect, 2026-08-02 |
| **The question** | ⛔ *not "should authority become a state machine?"* — ⭐ **"what independent dimensions describe the evolution of engineering knowledge?"** |
| **Frozen throughout** | ontology · governance · lifecycle · both YAML schemas |

> ## ⛔ **TWO CONCESSIONS BEFORE THE DISCOVERY**
>
> | # | | |
> |---|---|---|
> | **1** | ⛔ **LG-1 was solution design, conceded.** | *"Missing transitions → need state machine" is an inference from a missing implementation to a model — **exactly what DDD forbids.** I located an asymmetry and immediately proposed its repair* |
> | ⭐ **2** | ⭐⭐ **The reviewer's "that chain is QUALIFICATION, not authority" — CONFIRMED by the repository.** | *`DERIVED → REVIEWED → ATTESTED → RELEASED` describes a document becoming **more trustworthy** — and the repository already has vocabulary for that: **ES-006.1's `qualification` stage** and **ES-003 Qualification**. ⚠️ It even has a **recorded UL collision on the word** (the Engineering Governance Domain discovery: "Qualification" = verification activity vs promotion-ladder stage, "candidate for a future UL ruling")* |

---

## 1. The progression mechanisms — inventoried as evidence

⛔ **Treated as observations, not assumed to be lifecycles.**

| # | Mechanism | States / values | Home |
|---|---|---|---|
| **P-1** | Document **status** | 8, with `order` + `settled` + roles + guards | `statuses.yaml` · `_meta/lifecycle.md` |
| **P-2** | **Authority** | 5, with `rank` — ⛔ **no order, no roles, no guards** | `authorities.yaml` |
| **P-3** | **ES-006.1 promotion ladder** | research → pilot → **qualification** → standard | ES-006 |
| **P-4** | **Methodology maturity** | Observed → Replicated → Reinforced → Working Principle → General Principle | `Round39-D6` / P2-SYN-01 §6 |
| **P-5** | **ADR-M status** | Draft · Accepted · Controlled · Superseded · Retired | `Round39-D6` |
| **P-6** | **Capability lifecycle** | observed → candidate → designed → realized → evidenced → promoted | Capability Catalog / CAP-001 |
| **P-7** | **EEP** | plan → review → approval → implement → verify → report → decide | `Engineering_Execution_Protocol` |
| **P-8** | **Observation Protocol** | ACTIVE → CONCLUDED *(confirmed \| falsified \| inconclusive)* — ⭐ *"has a clock"* | Project-State-Sync |
| **P-9** | **Identifier lifecycles** ×4 | R · PMR · ADR · ES, each different | the minting-process model |
| **P-10** | **Evidence accumulation** | n=0 → n=1 → n≥2 → *(operationally validated)* | ES-006.1's bar · CAP-001 §9 |

⭐ **And one existing canonical distinction, found before theorizing:** *Reference Model §6 — **"two lifecycles, never conflated (GEP-F1)"**: the GOVERNANCE-ARTIFACT lifecycle (driven by Human Decision Events) vs the GOVERNED-WORK lifecycle (governed by the active artifact).* ⭐ **The repository already refuses to conflate two progression kinds. This discovery extends that refusal, it does not found it.**

## 2. The seven commissioned questions, per mechanism

| Mech. | What changes? | Invariant protected | Owner | Mandatory? | ⭐ Kind | Independent? | A lifecycle? |
|---|---|---|---|---|---|---|---|
| **P-1 status** | the document's **workflow position** | no unreviewed doc becomes baseline | §2 role table | ⛔ optional *(may rest at any state)* | ⭐ **GOVERNANCE-ACT** | ✅ declared ⟂ authority | ✅ **yes — genuinely** |
| **P-2 authority** | ⭐⭐ **TWO different things — see §4** | one authoritative doc per topic | ⛔ **undefined** | — | ⭐⭐ **CONFLATED — see §4** | ✅ declared ⟂ status | ⛔ **half of it CANNOT be** |
| **P-3 ES-006.1** | knowledge's **reusability standing** | ⭐ *never promote from a single occurrence* | **DA** | ⛔ optional *(most knowledge never climbs)* | ⭐ **EVIDENTIAL + governance gate** | ⚠️ consumes P-10 | ⚠️ *a **ladder** — monotonic, no return edges except demotion-by-decision* |
| **P-4 maturity** | **epistemic confidence** in an observation | a claim never outruns its evidence | ⭐ **the evidence itself** — *governance only records it* | ⛔ optional | ⭐⭐ **PURELY EVIDENTIAL** | ✅ ⭐ **declared ⟂ ADR status:** *"An ADR can be Controlled while the observation is only Replicated"* | ⛔ **no — a measurement scale** |
| **P-5 ADR-M** | the **decision record's** governance position | no silent methodology change | sponsor + ARB | optional | ⭐ **GOVERNANCE-ACT** | ✅ declared ⟂ P-4 | ✅ yes |
| **P-6 capability** | ⭐ **crosses kinds** — see §5 | one invariant per capability | DA / platform | optional | ⭐⭐ **COMPOSITE** | ⛔ **no — a trajectory across P-3, P-7, P-10** | ⛔ **a composite, not one lifecycle** |
| **P-7 EEP** | the **work's** stage | no stage skipped; human approval | DA | ⭐ **mandatory once begun** | ⭐ **WORK-EXECUTION** *(temporal)* | ✅ — GEP-F1 separates it from artifact lifecycles | ✅ yes — **of work, not of knowledge** |
| **P-8 observation** | an **experiment's** run state | a protocol concludes; it does not linger | Verification / DA | ⭐ mandatory *(it has a clock)* | ⭐ **WORK-EXECUTION** | ✅ | ✅ — of an activity |
| **P-9 identifiers** | an identifier's **register state** | PMR-10; identifier stability | the Authority | at minting | ⭐ **GOVERNANCE-ACT** | ✅ | ⚠️ four different ones |
| **P-10 evidence n** | **how much evidence exists** | detection ≠ prevention; *"never add these two lines"* | the producing track | ⛔ cannot be decreed | ⭐⭐ **PURELY EVIDENTIAL** — *monotonic counter* | ✅ — feeds P-3/P-4/P-6 | ⛔ **no — accumulation, not states** |

## 3. ⭐⭐ The kinds that emerge — four, plus one non-progression

> ### ⭐ **The mechanisms are NOT ten instances of "lifecycle." They are four different KINDS of change, moved by different causes, at different speeds, under different owners.**

| Kind | Moved by | Reversible? | Owner type | Instances |
|---|---|---|---|---|
| ⭐ **GOVERNANCE-ACT progression** | **a decision by a named role** | ⚠️ only by a further act *(supersession)* | humans in roles | P-1 · P-5 · P-9 · *(standing changes — §4)* |
| ⭐⭐ **EVIDENTIAL progression** | ⭐ **accumulating evidence — nobody can decree it** | ⛔ **only by refutation, never by decision** | *the evidence itself; governance merely recognizes* | P-4 · P-10 |
| ⭐ **WORK-EXECUTION progression** | **time and effort; has a clock** | ⛔ no — work concludes | the executing team | P-7 · P-8 |
| ⭐ **COMPOSITE trajectory** | crossing the above | — | several | P-3 · P-6 |
| ⛔⛔ **NON-PROGRESSION: PROVENANCE** | ⭐⭐ **NOTHING — it is an immutable fact of origin** | ⛔ **meaningless** | — | ⭐ *half of P-2 — §4* |

> ### ⭐⭐ **The distinction that carries everything: a GOVERNANCE-ACT progression can be granted. An EVIDENTIAL progression can only be earned.**
> *No ARB ruling can make an observation `Replicated`. No amount of evidence can make a document `frozen`. **Two different causal engines — which is why the repository twice declared pairs of them orthogonal** (status ⟂ authority; ADR-status ⟂ maturity) **before this commission existed.***

## 4. ⭐⭐⭐ The flagship finding — `authority` is not one dimension

> ## **`authorities.yaml`'s own header asks TWO questions in ONE field:**
> ## *"how much should I trust this* ⭐ ***and*** *where did it come from?"*

**Its five values answer different questions:**

| Value | Answers | Kind | ⭐ Can it change? |
|---|---|---|---|
| **`generated`** | *where did it come from?* — tooling/AI | ⭐⭐ **PROVENANCE** | ⛔⛔ **NEVER** — *origin is an immutable historical fact* |
| **`derived`** | *where did it come from?* — from an authoritative source | ⭐⭐ **PROVENANCE** | ⛔⛔ **NEVER** |
| **`authoritative`** | *how much should I trust it?* — the single source of truth | ⭐ **STANDING** | ✅ **by a GOVERNANCE ACT** |
| **`provisional`** | *how much?* — *"carries no authority yet"* — ⭐ *the word **yet** concedes it changes* | ⭐ **STANDING** | ✅ by an act |
| **`historical`** | *how much?* — was true, not current | ⭐ **STANDING** | ✅ by an act *(supersession)* |

> ### ⭐⭐⭐ **THIS DISSOLVES LG-1 RATHER THAN ANSWERING IT.**
>
> **Why does `authority` have no state machine? Because HALF of it CANNOT have one — provenance never changes — and the other half changes by governance ACTS, which the platform records as act-logs (rulings, dispositions), not as workflows.**
>
> | LG-1 asked | ⭐ The discovery answers |
> |---|---|
> | *"Should authority have a governed state machine?"* | ⛔ **The question is ill-posed.** *Provenance can't. Standing already changes by acts — what it lacks is only the **role table and guards** that status transitions have. **Whether standing-changes deserve those is a governance question about acts, not a modelling question about machines*** |
>
> ⭐ **And the reviewer's "what is authority?" gets an evidence-based answer:**
> ### **Authority = an immutable PROVENANCE fact × a mutable STANDING judgment. Attestation changes standing. Nothing changes provenance.**
>
> ⚠️ *This also explains why the `I-4` confusion was so persistent: `derived` (provenance) was read as if it entailed low standing — but a signed financial statement is `derived` in provenance and `authoritative` in standing, **simultaneously and without contradiction.***

## 5. Independence and interactions

| Interaction | Form | Health |
|---|---|---|
| **status ⟂ authority** | ⭐ **declared** — *"INDEPENDENT of status"* | ⭐ healthy |
| **ADR-status ⟂ maturity** | ⭐ **declared** — *"independent axes"* | ⭐ healthy |
| ⭐ **status ← authority, at one point** | **a GUARD**: `baseline` requires `authority ∈ {authoritative, derived}` | ⭐ **healthy — coupling by guard, not by fusion.** *Dimensions stay separate; a transition in one consults the other* |
| ⭐⭐ **P-3 / P-6 consume P-10** | **promotion = evidential standing crossing a bar + a governance act recognizing it** | ⭐ **this is the platform's central mechanism, stated at last:** ***evidence EARNS; governance GRANTS; promotion requires BOTH*** |
| **P-7 produces P-10** | work produces evidence | healthy |
| ⚠️ **P-1 vs P-5** *(LG-4)* | ⭐ **answered by kind: the SAME KIND (governance-act workflow), applied to different artifact classes** | ⚠️ **parallel instances of one kind — not two dimensions.** *Whether they should share one vocabulary is a parsimony question, not an ontology question* |
| ⚠️ **P-3 vs P-4** *(LG-4)* | ⛔ **DIFFERENT kinds** — P-3 is a composite with a governance gate; P-4 is purely evidential | ⭐ **two axes, correctly separate** |

## 6. ⭐ The success criterion — why multiple progression mechanisms exist

> ### **Because engineering knowledge changes in four causally different ways, and the repository — mostly deliberately — refuses to conflate them:**
>
> | Change | Engine | Speed | Who owns it |
> |---|---|---|---|
> | its **governance position** | acts | per decision | named roles |
> | its **evidential standing** | evidence | per occurrence | *nobody — it is earned* |
> | the **work around it** | time | per session | the executing team |
> | its **origin** | ⛔ **never changes** | — | — |
>
> ⭐⭐ **A single unified lifecycle would force one engine onto all four — requiring either that evidence be grantable by decree, or that decisions wait on evidence that may never come. The repository's two declared orthogonalities and GEP-F1 are three prior refusals of exactly that fusion.**
>
> ### ⛔ **So no mechanism "needs redesign" on this evidence. What P-2 needs is SEPARATION OF ITS TWO QUESTIONS — and whether to do that is the ARB's, not this document's.**

## 7. Open questions

| # | Question | Authority |
|---|---|---|
| ⭐⭐ **PM-1** | **Should `authority`'s two questions — provenance and standing — be separated?** ⛔ *This supersedes LG-1, which is withdrawn as ill-posed* | **ARB + PD-3's owner** |
| ⭐ **PM-2** | **Should standing-changes (attestation, supersession) get the role/guard treatment status-changes have?** *An act-governance question, not a state-machine question* | **ARB** |
| **PM-3** | Are P-1 and P-5 to share one vocabulary? *(same kind, two instances — a parsimony call)* | ARB |
| **PM-4** | The **"Qualification" UL collision** — already recorded once — now carries a third sense candidate *(the standing-assignment chain)*. Rule on the word? | ARB |
| **PM-5** | Is **P-6 capability** to be modelled as a composite trajectory explicitly, so its stages stop being read as one lifecycle? | ARB |
| **LG-5** *(carried)* | admit `SPECIALIZES` — the one genuinely ontological gap | ARB |

---

## ⭐ Closing

| | |
|---|---|
| ⛔ **Conceded** | **LG-1 was solution design** — an inference from missing implementation to model |
| ⭐⭐⭐ **The flagship finding** | **`authority` is two dimensions in one field — immutable PROVENANCE × act-changed STANDING — and its own header says so** *("how much should I trust this **and** where did it come from")* |
| ⭐⭐ **LG-1 dissolved** | *no state machine is missing: provenance cannot have one, standing changes by acts. **The asymmetry I located mechanically is now explained rather than repaired*** |
| ⭐ **The kinds** | **GOVERNANCE-ACT · EVIDENTIAL · WORK-EXECUTION · COMPOSITE — plus PROVENANCE, which does not progress at all** |
| ⭐⭐ **The platform's central mechanism, named** | ***evidence EARNS; governance GRANTS; promotion requires BOTH.** No ruling can make an observation Replicated; no evidence can make a document frozen* |
| ⭐ **The reviewer's insight confirmed** | *the attestation chain is **qualification** — and "Qualification" already carries a recorded UL collision, now with a third candidate sense* |
| ⭐ **Prior art honoured** | *GEP-F1 and two declared orthogonalities are three earlier refusals of lifecycle fusion. **This discovery extends canon; it does not found it*** |

> ### **The repository does not have ten lifecycles. It has four kinds of change and one immutable fact, wearing ten mechanisms.**
> ### ⭐ **And the question that started this — "should authority have a state machine?" — dissolves once you see that half of authority is a fact and facts do not have lifecycles.**

---

*Traceability: Engineering Progression Model commission 2026-08-02 · ⛔ **pure discovery — ontology, governance, lifecycle and both YAML schemas FROZEN; nothing designed** · ⛔ **LG-1 conceded as solution design and WITHDRAWN as ill-posed** · ⭐ **the reviewer's qualification insight CONFIRMED against ES-006.1/ES-003 and the recorded "Qualification" UL collision** · **10 mechanisms answered against the seven commissioned questions** · ⭐⭐⭐ **flagship: `authorities.yaml` conflates PROVENANCE (immutable — `generated`, `derived`) with STANDING (act-changed — `authoritative`, `provisional`, `historical`), per its own two-question header — which explains mechanically why it has no state machine and dissolves LG-1** · ⭐ **four progression kinds + one non-progression; the central mechanism named: evidence EARNS, governance GRANTS, promotion requires BOTH** · **LG-4 answered by kind (P-1/P-5 same kind; P-3/P-4 different kinds)** · **prior art: GEP-F1 + two declared orthogonalities cited as the platform's existing refusals of fusion** · ⛔ **no YAML change · no state machine · no new concept · no ADR.***

> **⛔ Submitted to the Decision Authority. Nothing in this document executes.**
