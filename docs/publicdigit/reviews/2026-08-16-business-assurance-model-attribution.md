# Business Assurance Model for Governed Actions

**Deliverable owed under:** P-1 (*"Governance shall define the business assurance categories and escalation triggers"*)
**Produced by:** Governance · 2026-08-16 · **Revision 2** (rev 1 returned by ARB/Principal-Architect review with seven required refinements — all applied; the review's key finding is honored structurally: *assurance is not one dimension*)
**Status:** **PROPOSED — awaiting Human/PO/ARB approval as the business requirements baseline for attribution assurance.** Nothing in this model is in force until that approval.
**Inputs:** all six approved decisions — P-1 strategy · P-2 review classes · P-3 invariants · P-4 evidence policy · P-5 advisory boundary · P-6 triggers. *"Approved" is backed by the record: each registration quotes the Human/PO/ARB approval act verbatim (`02f813ae` · `8de09453` · `6ad39fa0` · `fdfd6470` · `defc8a22` · `30976423`) — this document creates no approval evidence of its own.* **No mechanism is chosen anywhere in this document.**

---

## 1 · What this model is for

Every governed action must carry explicit attribution and disclose the level of assurance supporting it (P-1). This model supplies the vocabulary and the requirements — *what assurance each kind of business use needs* — and leaves *how any assurance is achieved* entirely to Architecture, subject to Governance approval (P-3 addendum split).

**The structural correction this revision makes:** a governed action raises **three different assurance questions**, and they must not be forced into one ordinal scale:

```
GOVERNED ACTION
   │
   ├── ATTRIBUTION assurance      — who/what performed the action?
   ├── AUTHORIZATION assurance    — did the claimed authority actually issue the decision?
   └── INDEPENDENCE assurance     — can the claimed independence be established?
```

These are related but distinct business concepts. A cryptographic signature can make *attribution* strong while saying nothing about *independence*; an independent human review can establish *independence* without strengthening *attribution*. A single ladder cannot express that, and rev 1's attempt to build one produced exactly the inconsistencies the review found.

---

## 2 · The three assurance dimensions and their states

States within a dimension are ordered weakest→strongest. **There is no ordering across dimensions.** Per INV-ATTR-2, the weakest state is always the default: anything not independently established is treated as declared/asserted. State names are the business vocabulary; they may be extended by governance decision without breaking the model.

### 2a · Attribution assurance — *who/what performed the action?*

| State | Business meaning |
|---|---|
| **Declared** | The claim exists only in the actor's own statement. |
| **Recorded** | The claim was registered in the governed record at act time — sequenced, append-only. **Honesty note: this protects against later fabrication of history; it does not add independence, since the same actor may have produced both the claim and the record.** |
| **Corroborated** | At least one independent evidence source outside the declarer's control is consistent with the claim (e.g. a distinct lane identity in engineering history, per P-4). Consistency, not proof. **The sufficiency of an evidence source is an Architecture determination subject to Governance approval.** |
| **Strongly bound** | Attribution is established using evidence the declarer cannot forge independently (cryptographic attestation, trusted signing, or equivalent). **This attests a credential or identity construct, not necessarily a specific natural person.** |

### 2b · Authorization assurance — *did the claimed authority actually issue the decision?*

| State | Business meaning |
|---|---|
| **Asserted** | An authority act is claimed (a `humanAct` text exists); nothing corroborates that the authority issued it. |
| **Recorded** | The act was registered in the governed record at the time, in the authority's stated words. |
| **Corroborated** | Independent evidence outside the recorder's control is consistent with the authority having acted (e.g. the act arrived through a channel the recorder does not operate). |
| **Independently established** | The authority's act is established by evidence the recorder cannot forge (e.g. the authority's own signed act). |

*This dimension exists because the accepted root gap is precisely that the platform cannot prove a human authorization is genuine — a question attribution alone does not answer.*

### 2c · Independence assurance — *can the claimed independence be established?*

| State | Business meaning |
|---|---|
| **Declared** | Independence is claimed by the performer or recorder ("this verification was performed by a different process"). |
| **Corroborated** | Evidence outside the claimant's control is consistent with separation (e.g. distinct lane identities). **Per P-4, this evidences role separation but does not by itself establish independence of persons, systems, or decision authority.** |
| **Independently verified** | An independent party, **meeting the P-2 Class-B independence requirements**, examined the evidence and established the independence claim. |

**Standing rules over all three dimensions (from the approved decisions, restated not re-decided):**

- **No state in any dimension ever authorizes.** Permission comes only from the human decision and the governed grant (INV-ATTR-1).
- **The states must be stated and must travel.** Every governed action discloses its state per relevant dimension, and every surface presenting the record must show them (INV-ATTR-3). *"Independently verified"* may only be displayed where that state actually holds — and in the correct dimension.
- **Dimensions never substitute for each other.** Strong attribution is not authorization authenticity; corroborated separation is not independence.

---

## 3 · Business use categories and required assurance

Requirements are driven by the P-1 criteria — business risk, governance importance, audit requirements, demonstrated incidents. *"Required"* = what the business needs; *"Today"* = what the platform currently supplies. Gaps are managed by §4 — never silently blocking, never hidden.

| Category | Typical acts | Primary dimension | Required | Today | Gap handling |
|---|---|---|---|---|---|
| **C1 · Administrative information artifacts** | session logs · portfolio views · generated reports | **boundary question — see note** | Attribution: Declared | met | none |
| **C2 · Governed lifecycle acts** | registering assignments · handoffs · routing · closures | Attribution | **Recorded** | Recorded | met; Corroborated desirable as P-4 lands |
| **C3 · Human authority acts** | authorizations · STARTs · approvals · acceptances | **Authorization** | **risk-dependent:** ordinary governance acts → **Corroborated** *(target)* · high-consequence / legally or externally consequential acts → **Independently established** | **Asserted–Recorded** | **known gap** — **until the target is achieved, every authority act MUST explicitly disclose the actual assurance state present at the time of the act** (mandatory rule); first escalation candidate |
| **C4 · Independence-bearing claims** | *"this verification was independent"* · Class-B review claims | **Independence** | **Independently verified**, wherever the claim is materially represented as an independence claim | **Declared** | **known gap** — P-2 disclosure duty is mandatory at every lower state: Declared/Recorded = *"I claim this was independent"* · Corroborated = *"additional evidence supports the claim"* · only Independently verified = *"an independent verifier established the claim"* |
| **C5 · Externally consequential acts** | regulatory, contractual or external-audit exposure | all relevant dimensions | strongest states the obligation demands | not available | **out of current capability** — such an act arising is itself a P-6 trigger (regulatory/contractual) |

**C1 boundary note (routed, not decided):** session logs and generated views may be *information artifacts with provenance* rather than *performed governed actions requiring attribution*. Whether C1 belongs in this model or in a broader **Information Provenance Model** is a domain-boundary question for the future EKS/target-architecture modeling. Recorded here so the boundary is discovered deliberately, not inherited by accident.

**Three consequences, stated plainly:**

1. **No new blocks.** Per P-5, an unmet target never gates automatically — it is high-priority reported evidence for the human's decision.
2. **Today's honesty becomes structural.** The hand-written *"self-declared, not attestable"* practice becomes a stated requirement (C3's mandatory disclosure rule) with named targets.
3. **This model provides Architecture with the business acceptance criteria for future attribution design.** Architecture determines whether a proposed mechanism satisfies these requirements; this document does not pre-judge any mechanism.

---

## 4 · The escalation scheme — one loop, not two lists

P-1's escalation criteria and P-6's seven reconsideration triggers are **one scheme**: the same evidence that says *"assurance is insufficient for this use"* is the evidence that reopens the record-change question. The seven approved triggers, unchanged:

attribution dispute · audit failure · independence limitation · governance-control failure · regulatory/contractual requirement · material incident · repeated operational correction.

```
operate under this model
      ↓
candidate trigger arises
      ↓
Governance evaluates and surfaces it — names the category, the required state, what fell short
      ↓
Human/PO/ARB decides: raise the requirement · accept the risk · commission stronger mechanism
      ↓
if mechanism work: Architecture designs (P-6 path) · Implementation builds if authorized
      ↓
Independent Verification verifies
```

**The automation boundary, aligned with P-5:** **Governance remains responsible for evaluating and surfacing escalation triggers. Automated mechanisms may detect or report candidate triggers, but they may not change requirements, grant authority, or alter workflow disposition without a separate governed decision.**

**And:** a trigger raises a question, never a state or requirement — the model has no self-amending part.

---

## 5 · Captured domain insight (recorded for future modeling; no architecture performed here)

The review's finding is preserved as a discovery: **"attribution" is not one domain concept.** The emerging **Governance Assurance domain** contains distinct concepts — *Attribution · Authorization Authenticity · Independence · Assurance State · Assurance Requirement · Evidence · Escalation Trigger*. This inventory is input to the future bounded-context and aggregate modeling; nothing here draws a boundary or designs an aggregate.

---

## 6 · What approval of this model would and would not mean

**Would mean:** the three-dimension vocabulary (§2) and the requirement mapping (§3) become the **business requirements baseline** Architecture designs against; the C3/C4 gaps are formally acknowledged, disclosed, and targeted; the escalation loop (§4) becomes the governed path for changing any of it.

**Would not mean:** no mechanism chosen · no implementation authorized · no gate created · no change to the workflow engine · no change to any P-1…P-6 decision · no bounded context drawn · Architecture still requires its own commissioning act.

---

**Traceability:** P-1 `02f813ae` · P-2 `8de09453` (Class A/B → §2c, C4) · P-3 `6ad39fa0`+addendum (INV-ATTR-1/2/3; requirement/mechanism split) · P-4 `fdfd6470` (corroboration ≠ independence → §2c note) · P-5 `defc8a22` (advisory boundary → §3 consequence 1, §4 automation wording) · P-6 `30976423` (triggers verbatim → §4) · accepted root gap `487fce74` (→ §2b's existence) · ARB review of rev 1, 2026-08-16 (seven refinements: three dimensions · no false total order · C4→Independently verified · C3 risk-dependent · §4 automation wording · C1 boundary question · acceptance-criteria rewording)
