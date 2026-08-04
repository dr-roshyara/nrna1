# KnowledgeOS — Ontology Cross-Product Validation

| | |
|---|---|
| **Kind** | ⭐ **FALSIFICATION BY PROJECTION.** ⛔ ***The ontology is HELD FIXED throughout. No concept added, removed or renamed during validation.*** |
| **Status** | ⚠️ **CANDIDATE** — the ontology's own status is recorded as ⭐ **STRATEGIC ONTOLOGY CANDIDATE**, per the review |
| **Authority** | ⚠️ **Generated — never authoritative without human review** |
| **Commission** | Principal Knowledge Engineer / Strategic DDD Architect / Enterprise Software Architect, 2026-08-02 |
| **Method** | project the ontology into **three** product domains and record what breaks |
| ⭐ **Success criterion** | ⛔ **NOT to prove the ontology correct.** *To determine whether it survives projection **unchanged*** |

> ## ⭐ **FOUR PRE-VALIDATION CORRECTIONS ACCEPTED — applied BEFORE the ontology was frozen**
>
> | # | Correction | Position |
> |---|---|---|
> | ⭐⭐ **1** | **`I-11` "Knowledge is never generative" is too strong** | ✅ **ACCEPTED.** ⭐ **Restated: *"Knowledge does not EXECUTE. Capabilities execute. Knowledge constrains and informs generation."*** *Sharper and testable — and it survives §3 where mine would not have* |
> | **2** | **`Engineering Method` may be a missing layer** | ✅ **Recorded as HYPOTHESIS, not added.** ⚠️ *Note: `Method` already exists as a **domain** (PD-2); the proposal is that it also be a **layer in the chain**. Different claims* |
> | **3** | **`Runtime Adapter` belongs outside the core ontology** | ✅ **ACCEPTED — and §2 confirms it empirically: it disappears in all three projections** |
> | ⭐ **4** | **The ontology models containment but not SPECIALIZATION** | ✅ **ACCEPTED — and it is the single most consequential gap the projection confirms** |

---

## 1. The three conceptual PKSs

⛔ **Conceptual only. No implementation, no schema, no code.**

| | **PublicDigit** *(actual)* | **Hospital Information System** *(hypothetical)* | **ERP System** *(hypothetical)* |
|---|---|---|---|
| **Contexts** | Membership · Governance · Adjudication · Contestation · Elections · Geography · Finance · Trust | Patient Record · Scheduling · Clinical Orders · Pharmacy · Consent · Billing | Inventory · Procurement · General Ledger · HR · Manufacturing |
| ⭐ **A core invariant** | ⭐ **no voter↔vote linkage** *(anonymity)* | ⭐ **consent precedes treatment**; a patient record is never deleted | ⭐ **every posting balances**; a closed period is immutable |
| **A design policy** | every identifier is unique in its register(ns) | every clinical order names a prescriber | every journal entry cites a source document |
| **A binding** | the Certified Domain Knowledge Release; `governed-registers.yaml` | the clinical terminology set *(SNOMED/ICD)* | the chart of accounts; the fiscal calendar |
| ⭐ **Authority** | ARB · Decision Authority | ⛔⛔ **clinical governance + EXTERNAL REGULATOR** | ⛔⛔ **finance function + EXTERNAL AUDITOR/STANDARD** |

## 2. Concept-by-concept projection

| Concept | PublicDigit | Hospital | ERP | ⭐ Verdict |
|---|---|---|---|---|
| **MISSION** | ✅ | ✅ | ✅ | ⭐ **INVARIANT** |
| **STRATEGY** | ✅ charters | ✅ programme plans | ✅ rollout strategy | ⭐ **INVARIANT** |
| ⚠️ **PRINCIPLE** | ✅ internally owned | ⛔⛔ **partly EXTERNAL — the regulator owns it** | ⛔⛔ **partly EXTERNAL — the accounting standard owns it** | ⚠️ **SPECIALIZES** |
| ⭐ **DESIGN POLICY** | ✅ DP-1..DP-6 | ✅ *"an order names a prescriber"* | ✅ *"an entry cites a source"* | ⭐⭐ **INVARIANT — the strongest result** |
| **CAPABILITY** | ✅ CAP-001 | ✅ interaction check | ✅ balance check | ⭐ **INVARIANT** |
| **KNOWLEDGE** | ✅ | ✅ | ✅ | ⭐ **INVARIANT** |
| **ARTIFACT** | ✅ | ✅ | ✅ | ⭐ **INVARIANT** |
| **KNOWLEDGE SPACE** | ✅ declared scope | ✅ | ✅ | ⭐ **INVARIANT** |
| ⚠️ **PKS** | ✅ one | ✅ one | ⛔⛔ **one per product, or per DEPLOYMENT?** | ⛔ **FAILS — see §4** |
| **PRODUCT** | ✅ | ✅ | ✅ | ⭐ **INVARIANT** |
| ⛔ **RUNTIME ADAPTER** | ⚠️ platform-side only | ⛔ **absent** | ⛔ **absent** | ⛔ **DISAPPEARS in all three** |
| ⚠️ **PROJECTION** | ✅ graph, indexes | ⛔⛔ **a discharge summary is generated AND legally authoritative** | ⛔⛔ **a financial statement is generated AND filed as authority** | ⛔ **I-4 FAILS — see §4** |

> ### ⭐ **8 invariant · 2 specialize · 1 disappears · 1 relationship fails.**

## 3. Stable invariants — survived all three projections

| # | Invariant | Held because |
|---|---|---|
| ⭐⭐ **I-1** | **Knowledge is REPRESENTED by an Artifact; an Artifact is never the Knowledge** | ⭐ *"consent precedes treatment" is the same rule whether in a policy PDF, a form, or a validation check — in all three domains* |
| **I-2** | **A Capability protects exactly ONE invariant** | held — *a drug-interaction check and an allergy check are two capabilities, not one* |
| **I-3** | **A Knowledge Space has a DECLARED boundary** | held — *the clinical terminology set and the chart of accounts are both declared, bounded, owned* |
| **I-5** | **One authoritative artifact per topic + context** | held — *and it is what a hospital's "single source of truth for the medication list" already means* |
| **I-6** | **The Runtime never owns Knowledge** | held **vacuously** in two — ⚠️ *no runtime concept appears at all* |
| **I-7** | **A PKS never contains reusable Method** | held — *clinical governance method is not hospital-specific knowledge* |
| **I-8** | **Governance precedes automation** | ⭐ **held emphatically** — *no hospital automates a clinical rule before ratifying it* |
| **I-9** | **Governance-status ⟂ Authority** | held — *a draft protocol authored by a consultant; an approved one generated by a committee* |
| **I-10** | **A constitutional principle is void-overriding** | ⭐ **held, and strengthened** — *a regulation voids a conflicting internal policy in both new domains* |
| ⭐⭐ **I-11** *(as corrected)* | **Knowledge does not EXECUTE; Capabilities execute; Knowledge constrains and informs generation** | ⭐⭐ **HELD — and my original wording would have FAILED here.** *A clinical protocol plainly **informs** the generation of an order set; it does not **execute***. ⭐ *The reviewer's reformulation is validated by projection, not by argument* |

> ### ⭐⭐ **10 of 11 invariants survive three domains. ⛔ One fails — I-4.**

## 4. ⛔ Evidence AGAINST the ontology

### ⛔⛔ F-1 · `I-4` — ⚠️ **FALSIFICATION WITHDRAWN 2026-08-02**

> ⛔ **This section's conclusion is CORRECTED.** ⭐ *A discharge summary is **not** authoritative because it was projected — it became authoritative through `DERIVED → REVIEWED → ATTESTED → RELEASED`. **Governance created the authority.**
>
> ⭐⭐ **`I-4` is UNDERSPECIFIED, not false: it holds until attestation. DP-2 CONFLATES DERIVATION WITH ATTESTATION.**
>
> ⛔ **And the cause is mechanical:** *`statuses.yaml` carries `order`/`settled` plus a role table and lint-checkable guards; **`authorities.yaml` carries none of these**. **Authority has no governed state machine, so “attested” cannot be expressed.** ⭐ **This was a LIFECYCLE gap misdiagnosed as an ontology falsification** — record: `KnowledgeOS_Lifecycle_Gap_Analysis.md`

#### The original observation, retained as evidence

**I-4: *a Projection is `derived` and is cited as authority by nothing.***

| Domain | Counterexample |
|---|---|
| **Hospital** | ⛔ **a discharge summary is GENERATED from the record and is LEGALLY AUTHORITATIVE** — cited by the receiving clinician, the insurer and the court |
| **ERP** | ⛔⛔ **a financial statement is GENERATED from the ledger, then SIGNED AND FILED.** *Auditors cite it as authority. It is the archetypal authoritative projection* |
| **PublicDigit** | ✅ holds — *the graph and portal indexes are cited by nothing* |

> ### ⛔⛔ **A projection CAN be authoritative. The invariant is true of the platform's own knowledge projections and FALSE as a general rule.**
>
> ⭐ **Diagnosis:** *I-4 conflates **derivation** with **non-authority**. They are independent: `derived` describes **provenance**; `authoritative` describes **standing**. **A signed derivation is both.***
>
> ⛔ **The ontology is not modified here. Recorded as evidence against, for the ARB.** ⚠️ *This also bears on **CAP-002 Projection Integrity**, whose DP-2 states the falsified form.*

### ⛔⛔ F-2 · "One PKS per product" fails for configurable products

| | |
|---|---|
| **The claim** | *a PKS is one per product* |
| ⛔ **ERP counterexample** | **the same ERP is deployed to fifty customers with different charts of accounts, fiscal calendars, approval hierarchies and legal jurisdictions.** ⭐ *Is that **one** PKS with fifty bindings, or **fifty** PKSs?* |
| ⚠️ **Hospital** | milder but present — *one HIS across several trusts with different consent regimes* |
| ⭐ **What the ontology cannot express** | ⛔ **the difference between a PRODUCT and a DEPLOYMENT of it.** *`PRODUCT` is atomic in the model* |

⭐ **Note:** *PublicDigit masks this — it has multi-tenancy (`organisation_id`) in the **code**, and the model treats it as one product. **The concept is present in the product and absent from the ontology.***

### ⚠️ F-3 · The authority chain has no place for EXTERNAL authority

| | |
|---|---|
| **The chain** | `MISSION → STRATEGY → PRINCIPLE → DP → CAPABILITY`, ⭐ **all owned by sponsor / DA / ARB** |
| ⛔ **Hospital** | **HIPAA · GDPR · clinical safety standards** — *principles the organisation must obey and **does not own*** |
| ⛔ **ERP** | **IFRS/GAAP · statutory audit** — same |
| ⚠️ **PublicDigit** | ⭐ *masked: election law is treated as **product domain knowledge**, not as external authority — so the gap exists here too and is invisible* |

> ### ⛔ **The ontology can express *who decides*. It cannot express *who must be obeyed*.**

## 5. Candidate specializations

| # | Concept | Specialization the projection requires | Evidence |
|---|---|---|---|
| ⭐ **S-1** | **PRINCIPLE** | **INTERNAL** *(owned, amendable)* vs ⭐ **EXTERNAL** *(imposed, non-amendable, compliance-only)* | 2 of 3 domains; F-3 |
| ⭐ **S-2** | **PRODUCT** | **PRODUCT** vs ⭐ **DEPLOYMENT / TENANT** | ERP decisively; PublicDigit's own `organisation_id` |
| ⭐ **S-3** | **PROJECTION** | ⭐ **INERT projection** *(index, graph — authority by nothing)* vs ⭐⭐ **ATTESTED projection** *(signed, filed, authoritative)* | F-1 |
| ⚠️ **S-4** | **KNOWLEDGE** | **METHOD** vs **RULE** vs **BINDING** vs **CASE LAW** | ⚠️ *already implied by P1; not required by the projection* |

⛔ **None is added. All four are recorded for the ARB.**

## 6. ⭐ Missing relationships

| # | Relationship | Why the projection exposes it |
|---|---|---|
| ⭐⭐ **M-1** | ⭐⭐ **SPECIALIZES** | **The reviewer named it; the projection confirms it is unavoidable.** *`Engineering Knowledge` **specializes into** product knowledge in all three domains — "an order names a prescriber" is the hospital specialization of "a decision names its author". **Containment cannot express this***  |
| ⭐ **M-2** | ⭐ **CONSTRAINS** *(external → internal)* | F-3 — *a regulation **constrains** a principle without owning, containing or generating it. **No existing verb fits*** |
| ⭐ **M-3** | ⭐ **INSTANTIATES** *(product → deployment)* | F-2 — *fifty deployments of one product; `contains` is wrong, `generates` is wrong* |
| ⚠️ **M-4** | **ATTESTS** *(agent → projection)* | F-1 — *what converts an inert projection into an authoritative one is an **act of attestation**, which the ontology has no verb for* |

> ### ⭐⭐ **All four missing relationships are the SAME SHAPE: the ontology models what things ARE MADE OF, and not what happens TO them.**
> **`contains · references · generates · depends on` are all structural. ⛔ `specializes · constrains · instantiates · attests` are all *transformational*.**
>
> ⭐ *That is a single coherent gap, not four unrelated ones — which makes it far more likely to be a real omission than an artifact of my three examples.*

## 7. Hypotheses recorded, ⛔ not admitted

| # | Hypothesis | Status |
|---|---|---|
| **H-5** | **`ENGINEERING METHOD` as a layer** between Strategy and Principle | ⚠️ *the projection **neither confirms nor refutes** it — all three domains have methods, but none required a separate layer to express them.* ⛔ **Not admitted** |
| **H-6** | **`Runtime Adapter` sits outside the core ontology** | ⭐ **SUPPORTED — it disappeared in all three projections.** *Still not moved; that is the ARB's act* |
| **H-1** *(carried)* | a Capability may read across a space boundary but never own what it reads | ⭐ **strengthened** — *the ERP balance check reads the chart of accounts it does not own* |

## 8. Verdict

> # ⚠️ **THE ONTOLOGY SURVIVES PROJECTION — BUT NOT UNCHANGED.**

| | |
|---|---|
| ⭐ **Survived intact** | **8 of 12 concepts · 10 of 11 invariants** |
| ⛔ **Falsified** | ⭐ **I-4** — *a projection can be authoritative; derivation and authority are independent* |
| ⛔ **Failed** | **"one PKS per product"** — *the model cannot distinguish a product from a deployment of it* |
| ⚠️ **Requires specialization** | **PRINCIPLE** *(internal/external)* · **PRODUCT** *(product/deployment)* · **PROJECTION** *(inert/attested)* |
| ⭐ **Confirmed by disappearance** | **Runtime Adapter** — absent in both new domains |
| ⭐⭐ **The systemic gap** | ⭐ **all relationships are STRUCTURAL; none is TRANSFORMATIONAL** |
| ⭐ **Validated by projection** | ⭐⭐ **the corrected `I-11`** — *my original wording would have failed; the reviewer's survives* |

> ### ⭐⭐ **The most valuable result is not that the ontology mostly held. It is that PublicDigit MASKED two of the three failures.**
> **Multi-tenancy and external legal authority both exist in PublicDigit and are both invisible in the model.** ⛔ *A single-product validation could never have found them — which is the argument for cross-product projection, now made by evidence rather than by principle.*

## 9. Open questions

| # | Question | Authority |
|---|---|---|
| ⭐⭐ **CV-1** | **Is `I-4` scoped to platform knowledge projections, or is it withdrawn?** ⛔ *DP-2 / CAP-002 states the falsified form* | **ARB** |
| ⭐ **CV-2** | **Does the ontology need `PRODUCT` vs `DEPLOYMENT`?** ⚠️ *PublicDigit already has `organisation_id`* | **ARB** |
| ⭐ **CV-3** | **Where does EXTERNAL authority attach?** *A regulation is obeyed, not owned* | **ARB** |
| ⭐⭐ **CV-4** | **Should transformational relationships be admitted as a group** *(specializes · constrains · instantiates · attests)* **rather than one at a time?** | **ARB** |
| **CV-5** | Adopt the status **"Strategic Ontology Candidate"** for the Relationship Ontology? | ARB |

---

## ⭐ Closing

**The commission asked whether the ontology survives projection unchanged. It does not — and the failures are more useful than the survivals.**

| | |
|---|---|
| ⭐ **What held** | **DESIGN POLICY held in all three** — *the layer discovered last projected best* |
| ⛔ **What broke** | **I-4**, in the two domains where projections carry signatures |
| ⛔ **What the model cannot say** | *"this product has fifty deployments"* · *"this principle is imposed from outside"* · *"this projection has been attested"* |
| ⭐⭐ **The one-sentence diagnosis** | ⭐ **The ontology describes composition. It does not describe transformation.** |
| ⭐⭐ **The methodological result** | **PublicDigit masked two of three failures.** *Cross-product projection is now justified by evidence, not by principle* |

> ### **An ontology validated against one product describes that product. This one was validated against three and broke in two places — which is the first evidence that the remaining ten invariants are about engineering rather than about elections.**

---

*Traceability: cross-product validation commission 2026-08-02 · ⛔ **the ontology was HELD FIXED throughout; nothing added, removed or renamed** · ⭐ **four pre-validation corrections accepted, including the restatement of `I-11` to "Knowledge does not EXECUTE" — which then SURVIVED projection where my original wording would have failed** · projected into **PublicDigit (actual) · Hospital Information System · ERP System** · **8 of 12 concepts and 10 of 11 invariants survived** · ⛔ **`I-4` FALSIFIED (a discharge summary and a financial statement are both generated AND authoritative — derivation and authority are independent), bearing directly on DP-2 / CAP-002** · ⛔ **"one PKS per product" FAILED on configurable products** · ⚠️ **3 specializations and 4 missing relationships recorded, none admitted** · ⭐⭐ **the four missing relationships share one shape: the ontology models STRUCTURE and not TRANSFORMATION** · ⭐ **`Runtime Adapter` confirmed outside the core by disappearing in both new domains** · ⭐⭐ **PublicDigit MASKED two of the three failures — multi-tenancy and external legal authority exist in it and are invisible in the model** · ⛔ **no implementation · no ADR · no ontology modification.***

> **⛔ Submitted to the Decision Authority. Nothing in this document executes.**
