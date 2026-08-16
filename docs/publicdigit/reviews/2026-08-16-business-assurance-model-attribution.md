# Business Assurance Model for Governed Actions

**Deliverable owed under:** P-1 (*"Governance shall define the business assurance categories and escalation triggers"*)
**Produced by:** Governance · 2026-08-16 · **Revision 3** (rev 2 applied the ARB's seven structural refinements — three dimensions, no false total order; **rev 3 adds the DDD bridge the ARB required: the claim/evidence/assessment domain model, the candidate invariants, and the lifecycle concepts** — so Architecture receives a business/domain model, not only an assurance policy)
**Status:** **APPROVED 2026-08-16 — the business requirements baseline for attribution assurance** *(PO/ARB approval registered in `2026-08-16-assurance-model-rev3-approval-registration.md`; the §7 "would not mean" list applies in full — candidate invariants INV-ATTR-4/5/6 remain unadopted, Architecture uncommissioned, implementation unauthorized).*
**Inputs:** all six approved decisions (registrations quoting the approval acts verbatim: `02f813ae` · `8de09453` · `6ad39fa0` · `fdfd6470` · `defc8a22` · `30976423` — this document creates no approval evidence of its own). **No mechanism is chosen anywhere in this document.** Explicitly kept out, per the ARB: classes, repositories, tables, event schemas, git details, service identities, cryptographic choices, aggregate design, bounded-context declarations.

---

## 1 · The central domain concept — the Attribution Claim

The unit this model governs is not an "assurance level" attached to an action. It is a **claim**:

```
GOVERNED ACTION
      ↓
ATTRIBUTION CLAIM        what is asserted about who acted / who authorized / how independently
      ↓
EVIDENCE SET             what supports the claim
      ↓
ASSURANCE ASSESSMENT     what conclusion the evidence legitimately permits
      ↓
ASSURANCE OUTCOME        the classified result, per dimension
      ↓
DISCLOSURE               the outcome stated where consumers see the record
```

Every question Architecture will eventually ask — what is stored, who owns it, how it changes, what evidence can be added, whether an assessment can be revised without rewriting history — attaches to this chain, not to a level label.

**Three business questions, three claim kinds — never collapsed:**

| Claim kind | Question |
|---|---|
| **Attribution** | who/what performed the action? |
| **Authorization authenticity** | did the claimed authority actually issue the decision? |
| **Independence** | can the claimed independence be established? |

A cryptographic signature can make an *attribution* claim strong while saying nothing about *independence*; an independent human review can establish *independence* without strengthening *attribution*. These are distinct claims about the same action, each with its own evidence and its own outcome.

---

## 2 · Assurance outcomes per dimension

Outcomes within a dimension are ordered weakest→strongest. **There is no ordering across dimensions, and the outcomes do not form a single total ordering** — different outcomes provide different kinds of assurance. Per INV-ATTR-2 the weakest outcome is always the default. Names are business vocabulary, extensible by governance decision.

### 2a · Attribution — *who/what performed the action?*

| Outcome | Business meaning |
|---|---|
| **Declared** | The claim exists only in the actor's own statement. |
| **Recorded** | The claim was registered in the governed record at act time — sequenced, append-only. **Honesty note: this protects against later fabrication of history; it adds no independence — the same actor may produce both the claim and the record.** |
| **Corroborated** | At least one independent evidence source outside the declarer's control is consistent with the claim (e.g. a distinct lane identity, per P-4). Consistency, not proof. **Evidence-source sufficiency is an Architecture determination subject to Governance approval.** |
| **Strongly bound** | Established by evidence the declarer cannot forge independently (cryptographic attestation, trusted signing, or equivalent). **Attests a credential or identity construct, not necessarily a specific natural person.** |

### 2b · Authorization authenticity — *did the claimed authority actually issue the decision?*

| Outcome | Business meaning |
|---|---|
| **Asserted** | An authority act is claimed; nothing corroborates that the authority issued it. |
| **Recorded** | The act was registered in the governed record at the time, in the authority's stated words. |
| **Corroborated** | Independent evidence outside the recorder's control is consistent with the authority having acted. |
| **Independently established** | Established by evidence the recorder cannot forge (e.g. the authority's own signed act). |

*This dimension exists because the accepted root gap is precisely that the platform cannot prove a human authorization genuine — a question attribution alone does not answer.*

### 2c · Independence — *can the claimed independence be established?*

| Outcome | Business meaning |
|---|---|
| **Declared** | Independence is claimed by the performer or recorder. |
| **Corroborated** | Evidence outside the claimant's control is consistent with separation. **Per P-4, this evidences role separation but does not by itself establish independence of persons, systems, or decision authority.** |
| **Independently verified** | An independent party, **meeting the P-2 Class-B independence requirements**, examined the evidence and established the claim. |

---

## 3 · Domain invariants

### Established (adopted by P-3, restated not re-decided)

- **INV-ATTR-1** — Attribution never grants authority. Permission comes only from the human decision and the governed grant.
- **INV-ATTR-2** — Attribution assurance must be explicitly classified; absent independent evidence, it is treated as declared attribution.
- **INV-ATTR-3** — The assurance outcome must remain visible to consumers of the record, in the correct dimension.

### Candidate invariants — **proposed for ARB approval; NOT in force**

- **INV-ATTR-4 (candidate) — Assurance requires a claim.** *An assurance assessment cannot exist without a defined attribution claim to assess.* Prevents a bare `assurance = VERIFIED` with no claim and no evidence behind it.
- **INV-ATTR-5 (candidate) — Assurance cannot exceed its evidence.** *A claim may not be represented with a stronger assurance outcome than the available evidence and assessment actually support.*
- **INV-ATTR-6 (candidate) — Assurance is non-transitive.** *Assurance is scoped to the claim it assesses and must not be automatically transferred to another claim, actor, authority, or conclusion.* "The git identity is Corroborated" does not make the human Corroborated; "the verification process was independent" does not make the verified conclusion correct. **Of particular weight for EKS: knowledge is reused downstream, and this invariant prevents assurance from silently propagating through the knowledge graph.**

**Dimensions never substitute for each other** (standing rule): strong attribution ≠ authorization authenticity ≠ independence.

---

## 4 · Lifecycle concepts — distinct, not designed

Claim, Evidence and Assessment are **distinct domain concepts with their own lifecycles and must not be collapsed into one `assurance` field.** Conceptual shapes only — **no state machine, no aggregate is designed here:**

```
Attribution Claim :   created → recorded → supported → assessed → superseded
Evidence          :   observed → associated → evaluated → retained / superseded
Assessment        :   proposed → assessed → accepted / rejected → superseded
```

One consequence stated now because it is business, not design: **an assessment is revised by superseding it, never by rewriting it** — consistent with the programme's append-only discipline.

---

## 5 · Business use categories and required assurance

Requirements are driven by the P-1 criteria. *"Required"* = what the business needs. ***"Observed Assurance Capability"*** = what the current platform can demonstrably provide (architecture-neutral; renamed from "Today" per ARB). Gaps are managed by §6 — never silently blocking, never hidden.

> **C3 — Human Authority Acts — is the primary assurance-sensitive category**, because authority acts directly establish or change governance authority. **Attribution improvements that do not improve the trustworthiness of authority acts do not fully address the accepted G-2 business risk.** This is Architecture's priority signal.

| Category | Typical acts | Primary dimension | Required | Observed Assurance Capability | Gap handling |
|---|---|---|---|---|---|
| **C1 · Administrative information artifacts** | session logs · portfolio views · generated reports | **boundary question — see note** | Attribution: Declared | met | none |
| **C2 · Governed lifecycle acts** | registering assignments · handoffs · routing · closures | Attribution | **Recorded** | Recorded | met; Corroborated desirable as P-4 lands |
| **C3 · Human authority acts** ⭐ *primary* | authorizations · STARTs · approvals · acceptances | **Authorization authenticity** | **risk-dependent:** ordinary acts → **Corroborated** *(target)* · high-consequence / legally or externally consequential acts → **Independently established** | **Asserted–Recorded** | **known gap** — **until the target is achieved, every authority act MUST explicitly disclose the actual assurance outcome present at the time of the act** (mandatory rule); first escalation candidate |
| **C4 · Independence-bearing claims** | *"this verification was independent"* · Class-B review claims | **Independence** | **Independently verified**, wherever materially represented as an independence claim | **Declared** | **known gap** — P-2 disclosure mandatory at every lower outcome: Declared/Recorded = *"I claim this was independent"* · Corroborated = *"additional evidence supports the claim"* · only Independently verified = *"an independent verifier established the claim"* |
| **C5 · Externally consequential acts** | regulatory, contractual or external-audit exposure | all relevant dimensions | strongest outcomes the obligation demands | not available | **out of current capability** — such an act arising is itself a P-6 trigger |

**C1 boundary note (routed, not decided):** session logs and generated views may be *information artifacts with provenance* rather than *performed governed actions requiring attribution*. Whether C1 belongs here or in a broader **Information Provenance Model** is a domain-boundary question for future EKS modeling — recorded so the boundary is discovered deliberately, not inherited by accident.

**Three consequences, stated plainly:**

1. **No new blocks.** Per P-5, an unmet target never gates automatically — it is high-priority reported evidence for the human's decision.
2. **Today's honesty becomes structural** — C3's mandatory disclosure rule turns the hand-written *"self-declared, not attestable"* practice into a stated requirement with named targets.
3. **This model provides Architecture with the business acceptance criteria for future attribution design.** Architecture determines whether a proposed mechanism satisfies them.

---

## 6 · The escalation scheme — one loop, not two lists

P-1's escalation criteria and P-6's seven reconsideration triggers are **one scheme**. The seven approved triggers, unchanged: attribution dispute · audit failure · independence limitation · governance-control failure · regulatory/contractual requirement · material incident · repeated operational correction.

```
operate under this model
      ↓
candidate trigger arises
      ↓
Governance evaluates and surfaces it — names the category, the required outcome, what fell short
      ↓
Human/PO/ARB decides: raise the requirement · accept the risk · commission stronger mechanism
      ↓
if mechanism work: Architecture designs (P-6 path) · Implementation builds if authorized
      ↓
Independent Verification verifies
```

**The automation boundary, aligned with P-5:** Governance remains responsible for evaluating and surfacing escalation triggers. Automated mechanisms may detect or report candidate triggers, but they may not change requirements, grant authority, or alter workflow disposition without a separate governed decision. **A trigger raises a question, never an outcome or requirement — the model has no self-amending part.**

---

## 7 · Candidate domain areas — hypotheses, not bounded contexts

The modeling surfaces an emerging **Governance Assurance domain** with these concepts: *Attribution Claim · Authorization Authenticity · Independence · Evidence · Assurance Assessment · Assurance Outcome · Assurance Requirement · Escalation Trigger · Disclosure.*

Plausible groupings (e.g. Governance / Attribution / Authorization / Verification) are **candidate domain areas only** — recorded as hypotheses for Architecture to test against the accepted KnowledgeOS current-state baseline. **No bounded context is declared by this document.** Business model first; bounded-context confirmation later.

---

## 8 · What approval of this model would and would not mean

**Would mean:** the claim/evidence/assessment chain (§1), the three-dimension outcome vocabulary (§2), the established invariants (§3), the lifecycle distinctions (§4) and the requirement mapping (§5) become the **business requirements baseline** Architecture designs against; the C3/C4 gaps are formally acknowledged, disclosed and targeted; the escalation loop (§6) becomes the governed path for changing any of it.

**Would not mean:** the candidate invariants INV-ATTR-4/5/6 are **not** adopted by this approval — they require their own ARB decision · no mechanism chosen · no implementation authorized · no gate created · no workflow-engine change · no P-1…P-6 decision altered · no bounded context drawn · Architecture still requires its own commissioning act.

---

**Traceability:** P-1 `02f813ae` · P-2 `8de09453` (→ §2c, C4) · P-3 `6ad39fa0`+addendum (INV-ATTR-1/2/3; requirement/mechanism split) · P-4 `fdfd6470` (→ §2c note) · P-5 `defc8a22` (→ §5 consequence 1, §6 automation wording) · P-6 `30976423` (triggers verbatim) · accepted root gap `487fce74` (→ §2b, C3 primacy) · ARB review of rev 1 (seven structural refinements) · ARB review of rev 2 (DDD bridge: claim chain · candidate invariants 4/5/6 · lifecycles · Observed Assurance Capability rename · C3 primacy · candidate-domain-areas framing · non-transitive assurance)
