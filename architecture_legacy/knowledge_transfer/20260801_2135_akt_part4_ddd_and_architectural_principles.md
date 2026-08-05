# Architecture Knowledge Transfer (AKT) — Part 4

**DDD & Architectural Principles**

| | |
|---|---|
| **Artifact** | Architecture Knowledge Transfer (AKT) — **Part 4 of 10 + Appendix** |
| **Part** | **Part 4 — DDD & Architectural Principles** |
| **Baseline** | Architecture Principles (Release 1.0) **IMMUTABLE** · Strategic DDD Constitution SD-1..7 **IMMUTABLE** · PGP-01..05 **FROZEN** (D-13) · DDD Tactical Governance Principles **ADOPTED** (ARB 2026-07-26) · Layer Verification Rule **PROPOSED, NOT ADOPTED** |
| **Version** | 1.0 |
| **Status** | **Living Architecture Reference.** *Lifecycle: **AUTHORED**, not ISSUED* |
| **Audience** | AI Architects · Principal Engineers · Architecture Review Board |
| **Authored** | 2026-08-01 · branch `feature/pb003` · HEAD `622c515d4` |
| **Prerequisite** | **Part 1** (philosophy) · **Part 2** (what is governed) · **Part 3** (the machinery). This Part is the *reasoning* layer between them |

---

## 0. How to use this Part

**Parts 2 and 3 gave structure and machinery. Part 4 gives the reasoning that produced both** — the principles that keep recurring, and which therefore predict what a future decision *should* look like.

> ### **Why this Part is the most portable one: individual decisions expire; principles are how you decide the next thing that nobody has decided yet.**

**The distinction the programme insists on, stated once:**

> **A PRINCIPLE states an enduring architectural rule. A DECISION (ADR) applies a principle to a specific context.** *"An ADR records a decision **in context**; a principle **endures across decisions**."*
>
> ***Principles endure; ADRs are their APPLICATION.*** *(ADR-MP-01..05 are the first application of PGP-01..05 to Messaging; future capabilities apply the same principles through their own ADRs.)*

**⛔ And the structural rule that prevents principle sprawl:**

> ### **ONE CONSTITUTION, MANY CHAPTERS.**
> `PGP-nn` is a **chapter** of the single Architecture Principles constitution — **not a second constitution**, and the local numbering *"does not imply a parallel authority."* **Future concerns (security, testing, operations) become FURTHER CHAPTERS of the SAME constitution — never new constitutions.**

**Each principle below carries its status.** ⚠️ **Cite status with the principle** — the difference between *IMMUTABLE*, *FROZEN*, *ADOPTED* and *PROPOSED* is the difference between authority and a recommendation.

---

## 1. The principle systems — a map, with authority and status

| # | System | Scope | Status | Home |
|---|---|---|---|---|
| **1** | **Architecture Principles (Release 1.0) 1–10** | the software-architecture constitution | **L1 · IMMUTABLE** *(changes only via a new governed release)* | `docs/architecture/design/Architecture_Release_1.0.md` |
| **2** | **Strategic DDD Constitution SD-1..7** | what Strategic DDD may consume, invent and enact | **L1 · IMMUTABLE** | `docs/architecture/design/Round47-00_Strategic_DDD_Constitution.md` |
| **3** | **Platform Governance Principles PGP-01..05** | Platform Capabilities (reusable infrastructure consumed by multiple contexts) | **L2 · FROZEN** (D-13 — change only via ADR + review) | `docs/architecture/principles/Platform_Governance_Principles.md` |
| **4** | **DDD Tactical Governance Principles** (7) | tactical DDD design only | **ADOPTED** — explicit DA ruling 2026-07-26; **early promotion is a recorded exception, R-39** | `engineering/knowledge/methodology/DDD_Tactical_Governance_Principles.md` |
| **5** | **Layer Verification Rule** + the four-level model | *who may change what, at which level* | ⚠️ **PROPOSED, NOT ADOPTED** — non-binding until an explicit ruling (R-34). **Cite as a recommended heuristic, never as authority** | `engineering/knowledge/methodology/Layer_Verification_Rule.md` |
| **6** | **AIP-01..14** platform principles | the Engineering Platform itself | **SEALED corpus** | `engineering/architecture/baseline/` |
| **7** | **Knowledge-integrity qualities A–F** | architectural *correctness* of knowledge artifacts | **FROZEN FOR STABILIZATION** | `PKS_Knowledge_Integrity_Model.md` *(see Part 1 §5.9)* |
| **8** | **The ordering principles** (§2 below) | the sequence of any governed act | distributed — each hosted where its subject lives | see §2 |

**Two things this table tells you that are easy to miss:**

1. **Systems 1–3 are PRODUCT constitution; system 4–6 are PLATFORM methodology.** They are separately owned and separately versioned. *A tactical-DDD principle cannot amend an Architecture Principle, and neither can amend the other's status.*
2. **System 4 binds only work that DECLARES tactical-DDD governance.** *"A methodology module the platform enforces, never platform architecture — the Engineering Platform remains methodology-agnostic."* The recorded analogy is exact: ***"VS Code doesn't know DDD; the DDD extension does."***

---

## 2. The ordering principles — the spine of everything

**These are the principles that generate the rest. Each states that one thing must precede another, and each exists because the reverse order was once attempted.**

```
                    ┌─────────────────────────────────────────────┐
                    │  CLASSIFICATION  precedes  PLACEMENT        │
                    │  EVIDENCE        precedes  AUTHORITY        │
                    │  AUTHORITY       precedes  RECORDING        │
                    │  RECORDING       precedes  EXECUTION        │
                    └─────────────────────────────────────────────┘
```

### 2.1 Classification precedes placement

> **Placement is derived exclusively from artifact classification. Placement shall NEVER be used as evidence of classification.**
>
> **Invalid reasoning it kills:** *"It's already under KnowledgeOS therefore it must be KnowledgeOS."*
>
> **Companion:** **artifact identity is independent of physical location.** *A repository move carries no governance meaning — it neither promotes, demotes, re-owns nor re-authorizes what it moves.*

**The teeth:** a placement argument that cannot be grounded in a classification property is **out of order**. ***"It's useful here", "easier to find", "it already lives there" cease to be reasons.***

**Status: EXECUTABLE.** `php scripts/doc-placement.php` resolves it; **exit code 2 means the classification is real but its placement is UNRULED → record `PENDING` and escalate. NEVER invent a destination.**

### 2.2 Evidence precedes authority

> **A rule becomes GOVERNED only after REPEATED operational evidence that its absence let defects escape.** One instance admits a **CANDIDATE** — or, where severity is constitutional, a **PROVISIONAL** control that operates immediately.

**The one-line test:** ***"Did a real defect escape because this was missing? If not, don't add it."***

**And the reversed burden of proof (R-37, operationalizing R-29):** ***any proposal for architectural expansion must include evidence that the existing architecture was insufficient; improvements without demonstrated insufficiency are REJECTED BY DEFAULT.***

### 2.3 Authority precedes recording

> **Documents record governance; they do not create it (ES-001.2).** Only explicit decisions create governance. **Workflow words are permission to proceed, never Approved/Promoted/Retired/Closed.**
>
> **Corollary (AIP-10 Assertion Integrity):** **no artifact may assert an unoccurred adoption.**

**The artifact-level form:** a **PACKAGE** (before) holds evidence, recommendations, traceability and **blank** templates; a **RECORD** (after) holds rulings, outcomes and state transitions. **Never write *"is accepted"* in a package, and never pre-fill an outcome — not even the recommended one.**

### 2.4 Recording precedes execution

> **`Evidence → Recommendation → Authority Decision → Recorded Outcome → Engineering Handover → RED`**
>
> **Three roles, three acts: the Authority DECIDES · the Recording Architect RECORDS · engineering EXECUTES. Recording is not deciding; deciding is not executing.**

**And the reviewer's own version of it:** *review delivered → Authority disposes → apply → record.* **A reviewer may not apply its own findings before disposition, including its own supplementary findings.**

### 2.5 Architecture does not approve itself

> **Analysis cannot ratify itself.** `analysis → recommended realization → ratification → resolution` — **never** analysis → resolution → ratification.
>
> **Readiness is EVIDENCE; acceptance is AUTHORITY.** *Architecturally authorizing a transition ≠ authorizing RED to start.*
>
> **Completeness is verified; SUFFICIENCY is the authority's judgement.**

**The certification form of the same principle, and it is the sharpest statement of it in the record:**

> ***A certification dimension derives credibility from being OUTSIDE THE UNILATERAL CONTROL of the programme it certifies.*** *This is why Operational Evidence sat at zero-independent and was **unmovable by a governance programme** — only USE can move it.*

**⚠️ Check both directions.** *A recorded self-catch: the same session applied "readiness is evidence, acceptance is authority" to someone else's gate and then failed to apply it to its own recommendation.*

### 2.6 Generalize only after operational evidence

| Bar | Statement |
|---|---|
| **One corpus is one observation** | *A rule generalized from one corpus is **a rule fitted to one corpus**, however many instances that corpus produced.* 47 references and 30 targets are still **one class of failure in one repository** |
| **Same-lineage ≠ independent** | *a new lens over the same corpus is not new evidence* |
| **Abstraction waits for a SECOND CONSUMER** | *one implementation is a hypothesis, not a demonstrated abstraction* |
| **One traversal ≠ a capability** | ***one script proves possibility; routine use proves capability*** |
| **Recurrence, not accumulation** | *you evolve a model when reality **repeatedly exposes the same missing concept from UNRELATED DIRECTIONS** — the observation is the RECURRENCE, not the population* |
| **Cross-context bar for methodology** | promotion normally requires evidence from **more than one bounded context**; R-39 records the one exception **as an exception**, expressly *"this does not weaken the rule"* |

### 2.7 Deterministic work may be automated

> **Governance precedes automation. Automation may IMPLEMENT governance. Automation NEVER DEFINES governance.**
>
> **🔑 Runtime tooling ENFORCES governance; it never DUPLICATES governance.** Hooks **derive** their governing standards at runtime, **quote no rule text and hardcode no standard number** — so renumbering, supersession or rehoming is followed automatically.

**The confidence bar for any deterministic repair:** **only ≥99 may be auto-applied** — 100 (git rename record / documented migration / exact existing target) · 99 (exactly one file carries that basename) · 75 **AMBIGUOUS** · 0 **MISSING**. ***Ambiguous and missing are EVIDENCE, never repairs.***

**And the smallest-set discipline:** the standards consolidation concluded **ZERO new hooks** were justified by evidence. **Machine verification lives in periodic qualification instruments, not resident daemons.**

### 2.8 Semantic ambiguity returns to governance

> **A felt need for a new concept → STOP → governance item** (SD-2).
>
> **If the required constitutional act is unclear, classify the observation as a QUESTION, not a finding** (Rule 11).
>
> **Exit code 2 → record `PENDING` and escalate.** **Never invent a destination, a target, an authority, a severity scale or a rule number.**

**The general form:** **the correct output of an unresolvable classification is a routed question, never a chosen answer.** *A model that **refuses** is as much evidence as one that answers.*

### 2.9 `OBSERVATION` — "constitutional" is the most overloaded word in the repository

**The review framework's quality F exists to catch guarded homonyms — *the same token authoritative in two contexts with different meanings*. By that standard, `constitution/constitutional` is used for at least six distinct things:**

| Usage | Means |
|---|---|
| PublicDigit is a *"constitutional governance platform"* | **the product domain** (committees, authority models) |
| **CI-1..CI-5** *constitutional invariants* | **permanent business invariants** — *weakening one is a "constitutional incident"* |
| **L1 Constitution** (AKB level) | **an artifact class** — Implementation Architecture Constitution · Architecture Principles · Strategic DDD Constitution |
| **ES-001 Engineering Constitution** | **the Engineering Platform's** foundational standard |
| **"constitutional category"** (Rule 9) | *architecture defect · governance defect · methodology observation · improvement recommendation* |
| **"constitutional act"** (Rule 11) | *editorial correction · Authority disposition · architecture amendment · methodology amendment · no action* |

**Consequence:** *"this is a constitutional matter"* is **not a decidable statement** in this repository without saying which sense is meant. **Recorded, routed, not enacted** — renaming any of the six is an authority act, and the ARB owns it. **In the meantime: always qualify the word.**

---

## 3. Architecture Principles (Release 1.0) — the software-architecture constitution

**L1 · IMMUTABLE.** Ten principles, and note that the *ordering* of the list is itself the argument:

| # | Principle |
|---|---|
| **1** | **Knowledge before software** |
| **2** | **Semantic ownership before software ownership** |
| **3** | **Evidence before architecture decisions** |
| **4** | **Strategic discovery before tactical design** |
| **5** | **Boundary decisions are governed** *(BDR; versioned)* |
| **6** | **One authoritative implementation per confirmed bounded context** |
| **7** | **Invariants dominate implementation** — ***decision-first, not entity-first*** |
| **8** | **Architecture fitness tests prevent drift** |
| **9** | **Evidence remains permanent** — *append-only; never overwritten* |
| **10** | **Architecture evolves only through governed releases** *(1.x / 2.0)* |

**Read 1→4 as a single sentence:** *knowledge, then semantics, then evidence, then strategy — and only then design.* **This is the same ordering as the standing development discipline** (`business → DDD → architecture → tests → implementation`), stated constitutionally.

**Principle 7 is the one most often violated in practice.** *"Decision-first, not entity-first"* is the constitutional form of the Aggregate Protection Principle (§6.2): **you do not get an aggregate because you have an entity.**

---

## 4. Strategic DDD Constitution — SD-1..7

**L1 · IMMUTABLE.** These govern **what Strategic DDD may consume, what it may not invent, and what it may only request.**

| ID | Principle |
|---|---|
| **SD-1** | **Consume only certified knowledge.** Strategic DDD consumes **only** the Certified Domain Knowledge Release (Package + Canonical Vocabulary). **No raw research artifact, no uncertified draft, no external source may enter the software model** |
| **SD-2** | **No governance concepts invented in DDD.** **DDD discovers SOFTWARE BOUNDARIES, not constitutional theory.** A felt need for a new concept → **STOP** → governance item |
| **SD-3** | **Ubiquitous Language originates from the Canonical Vocabulary.** Forbidden synonyms (e.g. *CaseDecision* / *Judgment* for **Determination**) **MUST NOT** appear in code. **Overloaded terms (Review · Authority · Independence) MUST be qualified** |
| **SD-4** | **Forbidden Transformations are architectural constraints**, enforced by fitness functions: *Legitimacy is never an Aggregate or persisted truth (one read model only)* · *Trust-Anchor is never an Entity* · *bare "Independence" is never a class* · *anonymity-violating linkage is never stored* · *Blocked concepts never become contexts or aggregates* |
| **SD-5** | **Carried constraints are binding:** (1) federate Independence by its **four facets** · (2) ***Anonymity is SUPREME over all other concerns*** · (3) **exactly one** Legitimacy projection · (4) **software does NOT own enforcement** (an acceptance event, never a compelling aggregate — TA-1) · (5) **design FROM ownership seams, then reconcile with existing code — NO RETROFIT** (TA-2) |
| **SD-6** | **Any governance change returns to Knowledge Release Governance.** **DDD may *request*, never *enact*.** ***Software ↛ Governance*** |
| **SD-7** | **Traceability is mandatory.** Every artifact declares the Package/Vocabulary/Ontology versions it was built against. **Each confirmed BC maps to exactly one module** |

**⭐ SD-3 is the reason §2.9's observation matters:** the constitution **already requires overloaded terms to be qualified**, and it names *Review · Authority · Independence* as examples. **`Constitutional` belongs on that list by the same rule.**

**⭐ SD-5(2) — *anonymity is supreme* — is the tie-breaker for any conflict in PublicDigit.** *When anonymity conflicts with auditability, verifiability, convenience or performance, anonymity wins, and the conflict is a design error to be resolved elsewhere.*

---

## 5. Platform Governance Principles — PGP-01..05

**L2 · FROZEN (D-13).** Scope: **Platform Capabilities** — reusable infrastructure consumed by multiple bounded contexts.

| ID | Principle |
|---|---|
| **PGP-01** | **Platform Capability — role vs classification.** Modelled on **two orthogonal axes, never conflated**: its **architectural role** (*Platform Capability*) and its **DDD classification** (e.g. Generic/Supporting Technical Subdomain). **Not a bounded context, not a core/business subdomain, holds no business decisions.** ***Architecture produces tickets; tickets never accrete into architecture*** — capture the capability as a first-class concept; **do not let tickets silently become it** |
| **PGP-02** | **Single ownership.** Every responsibility has **exactly one owner**, expressed with the disposition vocabulary **Owns / Coordinates / Preserves / Observes / Does-NOT-own.** **Ambiguous or shared ownership is a MODELING DEFECT that BLOCKS PROMOTION** |
| **PGP-03** | **Owner-hosts-the-guard.** The suite hosting an invariant's executable guard is the suite owned by the invariant's **owner**. For invariants a capability merely **preserves**, **the OWNER's suite hosts the guard** — the capability may contribute the scan surface but **must not host a guarantee it does not own.** *A preserver hosting an owner's guard is an ownership mismatch (architecture debt)* |
| **PGP-04** | **Constitutional preservation.** Constitutional invariants (anonymity, tenant isolation) are **owned by the constitution / a business context and PRESERVED, never owned, by a Platform Capability.** A capability **must never let a preserved invariant's evolution depend on infrastructure** |
| **PGP-05** | **Deferred evolution is explicit.** A capability makes **no un-sanctioned guarantees.** Any open question is either a new ADR when needed **or tracked architecture debt — never a hidden feature and never a speculative redesign.** ***Evolution happens under pressure and governance, not aesthetics*** |

**⭐ PGP-02's disposition vocabulary is the most reusable single artifact in this Part.** *Owns / Coordinates / Preserves / Observes / Does-NOT-own* answers a question that otherwise gets hand-waved, and **PGP-03/PGP-04 then make the answer executable.** **Live instance: AD-M1** — the anonymity guard added during C6B **is mis-hosted** (a preserver holding an owner's guarantee) and must be relocated to the constitutional suite.

---

## 6. The seven DDD Tactical Governance Principles

**ADOPTED (explicit DA ruling, 2026-07-26).** **Scope: tactical DDD only** — aggregates, responsibilities, invariants, value objects, events, commands, repositories, domain services. **NOT** Strategic DDD, bounded-context discovery, context mapping, team organization, event storming, or ubiquitous-language discovery.

**Binding rule: projects BIND this module; they do not FORK it.** *PublicDigit binding: `docs/architecture/governance/DDD_PRINCIPLES.md` — hosts the project's demonstrations and provenance. **Rules live once: bindings and runtime reminders point here and never restate.***

### 6.1 The Methodological Fitness Rule

> ### **A criterion that never rejects or modifies a candidate over the lifetime of the methodology is presumed CEREMONIAL until evidence shows otherwise.**
>
> **Every evaluation criterion must demonstrate discriminative power — INCLUDING THE ACCEPTANCE CRITERIA THEMSELVES.**

**The canonical acceptance chain (a closed epistemic loop):**

```
business evidence → obligation → decision → consistency → alternatives eliminated
    → removal test → protected domain truth → responsibility accepted
```

*The protection target is **the positive form of the removal-test sentence**; a Business Truth is **implemented by** Business Invariant(s).*

**⭐ This rule is why the Layer Verification Rule had to justify itself by DISCRIMINATING** — *it accepts G-1 and rejects AP-1/AP-2, therefore it is not ceremonial* — and why its retirement condition is stated as **"if it never escalates in two prospective uses, it is ceremonial and should be RETIRED."** **Apply this rule to any check you propose, including your own.**

### 6.2 Aggregate Protection Principle (APP)

> **An aggregate exists to protect DOMAIN PROPERTIES — finality, uniqueness, attribution, authenticity, integrity — not merely to encapsulate domain objects. Objects are the vehicle; protected properties are the purpose.**

**Kills:** *"we have an entity, therefore we need an aggregate."*

**Companion litmus for invariants:** ***would a domain expert recognize the rule as business, even if the software didn't exist?*** — **implementation constraints are not domain invariants.**

### 6.3 Value Object Derivation Principle (VODP)

> **A Value Object is justified only when it strengthens the expression, validation or protection of one or more ACCEPTED business invariants. Convenience grouping or data packaging alone is insufficient.**

**Derivation direction: `truth → invariant → concepts needed` — never *fields-that-travel-together*.**

**Companion — Domain Event entry condition:** *a Domain Event must trace to accepted invariants **AND** represent a business-significant occurrence.* Litmus: **would a domain expert describe it as something that happened in the business, regardless of implementation?** ⛔ **Never method-executed → event.**

### 6.4 Architectural Silence Principle (ASP)

> ### **The ABSENCE of an architectural element is a DECISION, not a default.**
>
> **Rejected candidates and deliberate non-events shall be recorded together with their rationale and reversal conditions when appropriate.**
>
> ***"Why isn't there an X?" must have an answer ALREADY ON THE RECORD.***

**Live instance: G-2's *"no translator — a DEFENDED ABSENCE"***, because a chain-head consumption has no causal predecessor — **with a reversal condition armed** for the authority-decision and evidence-admission slices. *That is what ASP compliance looks like: not silence, but recorded silence with a trigger.*

### 6.5 Artifact Derivation Principle (ADP)

> **Every tactical artifact derives from the immediately preceding FROZEN artifact. New concepts may not bypass the derivation chain without explicit ARB authorization.**

**The Canonical Tactical Derivation Chain** *(this platform's tactical methodology — **not** a claim about universal DDD process)*:

```
Aggregate → Responsibilities → Protected Domain Truths → Business Invariants
   → Value Objects → Domain Events → Commands → Repositories → Domain Services
```

**Companion — commands:** *business occurrence → business intention → command;* ⛔ **never public-method → command.**

**Companion vocabulary — the Emergent Design Cluster:** **deferrals that CLUSTER are symptoms of ONE missing concept — open them together.** *Distinct centers are cross-referenced, never conflated.*

### 6.6 Dormant Mechanism Trichotomy (DMT)

> **Dormant implementation (no caller, no expressed intention) must be classified THROUGH EVIDENCE, never intuition or default labels ("tech debt", "future work"):**
>
> **(1)** implementation convenience meant to remain internal → **evidence** · **(2)** dead/obsolete capability → **evidence** · **(3)** missing business intention never modeled → **evidence → recorded conclusion (a HYPOTHESIS, preserved as such)**

**Companion insight, and it is a genuinely subtle one:** a missing intention may prove to be a **temporal business policy** (*window-expiry → policy → action*), **in which case the corresponding COMMAND MAY NEVER EXIST.** ***The realization shape is decided when the owning question resolves — not presupposed by the reversal condition.***

### 6.7 Repository Minimal Surface Principle (RMSP)

> **A repository exposes only the operations required to preserve, reconstitute or enforce accepted aggregate invariants. QUERY CONVENIENCE BELONGS TO READ MODELS, not aggregate repositories.**
>
> *A repository exists because protected truths must survive time and process boundaries — **persistence is the mechanism, not the reason**.*

**Companions:** ***reconstitution is not an occurrence*** (loading state is never a business event) · **Domain-Service entry condition:** *a Domain Service exists **only** when a business operation cannot naturally belong to a single Aggregate **while preserving the participating aggregates' truths***.

### 6.8 Application order — when tactical-DDD governance is active

| Before… | Apply |
|---|---|
| creating any tactical artifact | **confirm the derivation chain (ADP)** |
| accepting any candidate | the relevant principle(s) — **criteria that never reject are suspect (Fitness Rule)** |
| **rejecting** any candidate | **record rationale and reversal conditions (ASP)** |
| evaluating existing implementation | **classify dormant mechanisms through evidence (DMT)** |
| designing a repository | **minimal surface only (RMSP)** |

---

## 7. The four-level model and the Layer Verification Rule

⚠️ **STATUS: PROPOSED, NOT ADOPTED.** *Non-binding until an explicit Decision Authority ruling. **Cite as a recommended heuristic, never as authority.*** *(The artifact requesting indexing does not index itself.)*

### 7.1 The four levels

| Level | Role | Authority | Engineering may change? |
|---|---|---|---|
| **Business Policy** | **decides WHAT** | Q-2 / ARB | ❌ never |
| **Architectural Invariant** | **protects WHAT** | ARB | ❌ never |
| **Mechanism** | **decides HOW** | engineering, **within** the invariant | ✅ **the ONLY substitutable level** |
| **Implementation** | **realizes HOW** | engineering | ✅ yes |

> **It turns *"is this substitutable?"* from a JUDGEMENT into a LOOKUP.** *A level-3 collision dissolves without touching the model; **a level-2 collision cannot be engineered around — return to the ARB.***

### 7.2 The rule, the dual, the checklist

> ### **Can this layer change WITHOUT changing the layer above it?**
> **YES** → it belongs at this layer. **NO** → **you are modifying the wrong abstraction.**

**THE DUAL — what a failure MEANS:** *if changing this layer forces a change above it, you have discovered an **ARCHITECTURAL** dependency, not an implementation one.* ⭐ **A failing rule ESCALATES; it does not block.** *The failure names the authority that must be engaged, and says the change is larger than it was presented as.*

**Proposal checklist, applied BEFORE implementation:** **(1)** which layer is intended to change? **(2)** which higher layer would also change? **(3)** if any higher layer changes → **escalate before implementation.**

**⚠️ The practical trap:** **plans routinely record an invariant and its mechanism IN THE SAME SENTENCE**, which is why they get read as one thing. ***Split them before treating either as binding.*** *(Live instance: R-57 had to correct an approved plan whose Objective row carried a mechanism R-44 had superseded.)*

### 7.3 Validated against the two worst defects — both of which passed every gate

| Defect | What it did | Level violation |
|---|---|---|
| **AP-2** | a MAD key placed in a retention config | destroyed *"MAD has exactly one canonical home"* — **an INVARIANT BREACH DRESSED AS A MECHANISM CHOICE** |
| **AP-1** | `max(1, $days)` | overrode *"Q-2 decides durations"* — **an IMPLEMENTATION EDIT REACHING TWO LEVELS UP** |

> **Apply this rule to any change that feels like *"just a technical choice."***

**Recorded limits, not minimised:** one work package · two of three cases retrospective · **level assignment is a judgement** · **unenforceable by tooling.** **Retirement condition: if it never escalates in two prospective uses outside EPIC-004, it is ceremonial and should be RETIRED.**

### 7.4 The companion principle — causality vs authority

> ### **Model causality with DEPENDENCIES. Model authority with STATE TRANSITIONS.**
> **Dependencies explain *why*. States record *what exists now*.**

**What it prevents, concretely:** a dependency graph says *"execution authorization requires plan approval."* **A state machine says which states may legally COEXIST** — making `execution AUTHORIZED ∧ plan AWAITING APPROVAL` **unreachable rather than merely discouraged.** ***The first relies on someone remembering the rule; the second makes the illegal state unrepresentable.***

**Classify on two independent axes — and check the direction of your independence claim:**

| Axis | Values |
|---|---|
| **State category** | Architecture · Planning · Delivery · Execution |
| **Transition type** | Approval *(admits a delivered thing)* · Ratification *(confirms an existing thing's reading)* · Planning *(binds a forward commitment)* · Execution *(unlocks work)* |

**Neither is derivable from the other IN PRINCIPLE** — an Architecture Governance transition can be an **Approval** (a *new* ADR) or a **Ratification** (confirming an *existing* one's reading). ⚠️ **BUT in the WP-6→WP-7 sample, Type WAS derivable FROM Category while Category was NOT derivable from Type. *One-directional non-derivability is NOT independence* — say which direction your evidence supports.**

**The independence test, generalized:** **if column B is derivable from column A, it is not a second dimension. TEST BOTH DIRECTIONS.** ***A model that exempts itself from its own test is ceremonial.***

**Column discipline:** the category table has **exactly two columns** and gains no more without a real need surfaced by operational use. *`Governs` answers **what authority acts here?**; `Produces` answers **what durable outcome remains?*** ⛔ **Resist Consumer / Evidence / Owner columns — adding columns that restate the same question is how a crisp model becomes a form.** *(And note: `Produces` is 1:1 with Category **by construction**, so it is a definitional expansion, not a third dimension.)*

---

## 8. The platform's own principles — AIP (sealed corpus)

**AIP-01..14, sealed in `engineering/architecture/baseline/`.** The four that recur constantly:

| ID | Principle | How it shows up |
|---|---|---|
| **AIP-10** | **Assertion Integrity** | **no artifact may assert an unoccurred adoption.** *The reason a package may never say "is accepted"* |
| **AIP-11** | **Append-Only History** | session logs, rulings registers, evidence — **never overwritten** |
| **AIP-13** | **Implementation-Driven Evolution** | **the amendment path**: the platform changes because implementation demonstrated insufficiency |
| **AIP-14** | **Product Primacy** | ***the product comes first.*** *Cited to DEFER platform work — e.g. Fitness Function implementation is deferred per AIP-14, and R-42 cites it to keep project tooling out of the platform registry* |

**Also on the sealed corpus:** Platform Decisions **PD-01..20** and Fitness Functions **FF-01..17** (*defined; FF implementation deferred per AIP-14*).

**Promoted engineering behaviours (R-36, AST-013) — six one-liners worth internalizing:**

> **ownership-determines-architectural-reuse (never precedent)** · **reuse-before-create** · **deferred ≠ skipped** · **epistemic labels (Observed · Measured · Derived · Interpreted · Recommended) on ALL architectural recommendations** · **stop-at-architectural-uncertainty — surface, classify, request ARB; NEVER silently invent architecture** · **implementation-evidence-outweighs-unverified-theory**

**⭐ Note the ARB's wording refinement at adoption of the last one: *"unverified theory", not "theoretical elegance" — **elegance is not the enemy; untested assumptions are***.**

**And what was expressly NOT promoted, because scope discipline is the point:** *Registration ≠ Delivery* stays **Messaging-scoped** (1 slice) · the Domain-Event ≠ Integration-Event distinction and the Discovery→IDD→RED→GREEN chain became **Category-B follow-ups** · Strangler reconstitution, the PGP-03 hoist and Engineering Standards remain **Candidates**. **Promotion report: 6 promoted · 2 follow-ups · 4 platform-doc · 5 candidates · 4 already-permanent (duplication refused) · 1 removal scheduled · ZERO new documents.**

---

## 9. Cross-cutting epistemic principles — the "strongest claim" family

> ### ***Every governance act should establish only the STRONGEST CLAIM THAT ITS EVIDENCE PRESENTLY SUPPORTS.***
> **A quality criterion, not a methodology step.**

**The distinctions it generated — each pair was once conflated, and every refinement NARROWED a claim without changing an outcome:**

| Pair | The distinction |
|---|---|
| **confirmed vs strengthened** | corroboration is not confirmation |
| **dissolved vs superseded** | a claim that stops applying ≠ a claim replaced |
| **document vs rule vs binding** | three different objects |
| **rationale vs ontology** | why we did it ≠ what exists |
| **readiness vs authorization** | evidence ≠ authority |
| **implementation vs adoption** | shipped ≠ governed |
| **observation vs methodology** | noticed ≠ ruled |
| **disposition vs terminal state** | a one-off disposition is not a new lawful state *(SIA-1, n=1)* |
| **Declined vs Withdrawn** | never admitted ≠ admitted then removed |
| **Recognition vs Agreement vs Adoption** | on the record ≠ has merit ≠ changes practice |
| **verified-ready vs promoted** | derived state ≠ authority act |
| **emphasis vs contract** | usefulness ≠ unique capability |
| **frozen vs immutable** | *frozen ≠ immutable; amendment remains available via Authority + change control* |
| **coverage vs ownership** | *documentation coverage is NEVER architectural ownership* (R-42, binding vocabulary) |

**The honest-failure-direction question** *(GOVERNED, n=5)* — ask of every substantial claim: **which way is this most likely to be wrong?** *For the governance baseline the recorded answer is: **likelier TOO EXPENSIVE FOR ITS VALUE than wrong**.*

**Negative-claim discipline:** state **what was checked, over what scope, with what instrument.** *"No X exists" without a stated search is not a finding.* ⚠️ *Broken a third time while already in force in the document stating it — which is why it is worth reading twice.*

**Terminal states are lawful and finite:** **adopted · resolved · deferred · retired.** *Completion means every **necessary** question reached one of them — **not** that every possible question was answered.*

---

## 10. Principles about principles — growth governance

| Principle | Statement |
|---|---|
| **Rule parsimony (ES-001.1)** | on any recurring problem, **first ask *"does an existing rule already cover this?"*** A new rule requires a genuine "no." ***Corollary: the rulings register must not grow faster than the software*** |
| **The stopping rule** | *the set is complete.* If a new rule appears, ask **"which existing document OWNS this?"** — never *"should we create the next one?"* |
| **Interpret rather than add** | *"where existing rulings already express a constraint, **INTERPRET THEM** rather than add another ruling"* — **even a consolidating one** (R-38's own precedent) |
| **Maximal locality** | a growth rule is **hosted in the document whose growth it governs.** ⛔ **Do not extract a new document on first recognition** — that would violate the repeated-evidence filter growth governance itself holds |
| **Refactoring ≠ invalidation** | ***DDD prefers discovering a better model over declaring the previous model incorrect.*** **Two bars, never conflated:** rule admission needs **escaped-defect evidence**; cohesion refactoring needs only **demonstrated multiple responsibilities** |
| **A model as simple as its problem** | **R-64:** *governance acting upon governance artifacts may warrant a second bounded context — materially larger than adding a category, requiring SUSTAINED demand, not a single validation* |
| **The three stabilizers** | *a new **stage** ⇏ a new emphasis · a new **scope** ⇏ a new responsibility · a new **artifact type** ⇏ a new contract.* **General form: *Variation in X does not imply variation in Y*** |
| **Rates of change differ and are governed independently** | criteria **slowest** · method as experience accumulates · conduct **fastest** |
| **Freeze for stabilization** | *validate across several independent cycles before any further expansion.* ***The correct outcome of most future admission tests is "no addition needed."*** |

---

## 11. External frameworks — openness without framework-first thinking

**`docs/architecture/governance/External_Framework_Evaluation_Rule.md` (ACTIVE).** Purpose: ***prevent framework-first thinking while remaining open to external evidence*** — and ensure external material is evaluated **without derailing ongoing architectural work.**

**Step 1 — classify, and note that decision power is assigned BY CATEGORY:**

| Category | Examples | Decision power |
|---|---|---|
| **Evidence** | research papers, domain analysis | **Informational** |
| **Pattern** | design patterns, anti-patterns | **Advisory** |
| **Reference Architecture** | published architectures, standards | **Comparative** |
| **Implementation Technique** | algorithms, data structures, libraries | **Tactical (if approved)** |
| **Mathematical Framework** | formal models, theorem sets | **Analytical** |

**Step 2 — assess:** *what does it claim to explain? does observed domain behaviour match its model? are there contradictions or gaps?* ⭐ **and the falsifiability question: *what evidence would prove it applicable or INAPPLICABLE?*** **Output: an Applicability Assessment — Yes / Partial / No / Unknown.**

> **Nothing external arrives with authority. It arrives with a CATEGORY, and the category caps what it can do.**

---

## 12. Order of consultation — the practical algorithm

> ⚠️ **DERIVED AID, NOT A GOVERNED ARTIFACT.** *Sections 1–11 quote registers that exist. **This section does not** — it is a reading order **synthesized by this AKT** from the precedence relations those registers state (constitutional invariants dominate · strategic precedes tactical · parsimony precedes creation · escalate rather than build). **No ruling establishes this sequence.** Use it as a checklist; **never cite it as authority**, and if it conflicts with a register, the register wins.*

**When facing a design question, consult in this order. Stop at the first that answers.**

```
1. Is a CONSTITUTIONAL INVARIANT engaged?            → CI-1..5 · SD-5(2) anonymity is supreme
      ↓ no
2. Is this STRATEGIC or TACTICAL?                    → SD-1..7 (strategic) · the seven (tactical)
      ↓
3. Which LEVEL is changing?                          → four-level model; if a higher level
                                                        would change → ESCALATE, do not build
      ↓
4. Is a PLATFORM CAPABILITY involved?                → PGP-01..05 (esp. 02 ownership, 03 guard host)
      ↓
5. Does an ADR already govern this?                  → apply it; do NOT re-decide
      ↓
6. Is a NEW rule/concept genuinely needed?           → ES-001.1 parsimony: which existing home
                                                        owns it? Then the admission filter:
                                                        did a real defect escape without it?
      ↓
7. Still unresolved?                                 → RAISE A QUESTION. Record PENDING. Escalate.
                                                        NEVER invent.
```

**And the standing check on whatever you produce:** **does this criterion ever REJECT anything?** *If not, it is presumed ceremonial (Fitness Rule).*

---

## 13. What is NOT a principle here

| Not a principle | Why |
|---|---|
| **"Clean code" / "SOLID" / general craft maxims** | not in any register; **the registers are specific and evidence-backed.** *Don't cite what isn't hosted* |
| **Preference, elegance, symmetry, consistency-for-its-own-sake** | **PGP-05: *evolution happens under pressure and governance, not aesthetics.*** Reopening a freeze *"for refinement, expression or pattern preference"* is expressly forbidden |
| **A pattern name as justification** | **cite the STRUCTURAL layer (dependency · direction · ownership) freely; cite Evans pattern names ONLY where they survived DAR-1** |
| **An observation, however sound** | **observations are ROUTED, never enacted** |
| **The Layer Verification Rule as authority** | **PROPOSED, not adopted** — a recommended heuristic |
| **Anything in this AKT** | **AUTHORED, not ISSUED.** *This Part is a map of the principle registers; **the registers are the authority*** |

---

## Traceability

**Primary sources (all repository-internal, read at authoring):**

- `docs/architecture/design/Architecture_Release_1.0.md` — **Architecture Principles (Release 1.0) 1–10**
- `docs/architecture/design/Round47-00_Strategic_DDD_Constitution.md` — **SD-1..7 in full**
- `docs/architecture/principles/Platform_Governance_Principles.md` — **PGP-01..05**, one-constitution-many-chapters, principle-vs-decision
- `engineering/knowledge/methodology/DDD_Tactical_Governance_Principles.md` — **the seven principles with all companions**, scope, binding rule, application order, traceability
- `engineering/knowledge/methodology/Layer_Verification_Rule.md` — **the four-level model, the rule, the dual, the proposal checklist, §3 causality-vs-authority + the canonical governance categories, §4 evidence base and its stated limits**
- `docs/architecture/governance/External_Framework_Evaluation_Rule.md` — classification + applicability assessment
- `docs/architecture/Architecture_Knowledge_Base_v1.0.md` — **AKB levels L1–L4, lifecycle classification** (Immutable · Frozen · Living · Generated · Historical), the document registry
- `engineering/governance/ES-001-Engineering-Constitution.md` — **AIP-10/11/13/14**, PD/FF, the governing insight, rule parsimony
- `engineering/architecture/adr/ADR-AIP-LOG-Platform-Rulings.md` — **R-36** (promoted behaviours + what was not promoted), **R-37/R-38** (burden of proof, interpret-don't-add), **R-39** (the promotion exception), **R-42** (coverage ≠ ownership), **R-64**
- `docs/adr/ADR-MP-Messaging-Platform.md` · `docs/adr/ADR-T-LOG-Tactical-Implementation.md` — principles applied as decisions
- `docs/implementation/PushB_Architecture_Blueprint.md` §1 — **CI-1..CI-5** and the constitutional/business separation
- `docs/implementation/PKS_ARB_Review_Discipline.md` · `PKS_Knowledge_Contract_Review_Method.md` — the admission ladder, the stabilizers, honest-failure-direction, negative-claim discipline
- `.claude/MEMORY.md` — the strongest-claim family, the four-level model's adoption record, the independence test

**New observations recorded by this Part (routed, not enacted):** §2.9 — **`constitution` / `constitutional` carries at least six distinct authoritative meanings**, which SD-3 already requires overloaded terms to qualify.

**Supersedes:** nothing. **Superseded by:** nothing. **Depends on:** Parts 1–3.
