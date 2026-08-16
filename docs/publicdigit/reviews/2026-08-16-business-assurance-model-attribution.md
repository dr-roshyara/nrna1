# Business Assurance Model for Attribution

**Deliverable owed under:** P-1 (*"Governance shall define the business assurance categories and escalation triggers"*)
**Produced by:** Governance · 2026-08-16
**Status:** **PROPOSED — awaiting Human/PO/ARB approval as the business requirements baseline for attribution assurance.** Nothing in this model is in force until that approval.
**Inputs:** all six approved decisions — P-1 strategy · P-2 review classes · P-3 invariants · P-4 evidence policy · P-5 advisory boundary · P-6 triggers. *"Approved" is backed by the record: each of the six registrations quotes the Human/PO/ARB approval act verbatim (`02f813ae` · `8de09453` · `6ad39fa0` · `fdfd6470` · `defc8a22` · `30976423`) — this document creates no approval evidence of its own.* **No mechanism is chosen anywhere in this document** (that is Architecture's half, per the P-3 addendum split).

---

## 1 · What this model is for

Every governed action must carry explicit attribution and disclose the level of assurance supporting it (P-1). This model supplies the two things that sentence needs to be operable:

1. **The assurance levels** — a common vocabulary for *how strongly we know who acted*.
2. **The requirement mapping** — which level each category of business use requires, and what happens when reality falls short.

It deliberately says nothing about *how* any level is achieved. Governance defines what assurance a use requires; Architecture later determines what mechanism provides it, subject to governance approval.

---

## 2 · The assurance levels

Five levels, ordered by how far the evidence sits outside the declarer's control. Per INV-ATTR-2, **the default is the lowest: anything not independently established is Declared.**

| Level | Name | Business meaning |
|---|---|---|
| **L0** | **Declared** | The claim exists only in the actor's own statement. Nothing corroborates it. |
| **L1** | **Recorded** | The claim was registered in the governed record at the time of the act — sequenced, append-only. This protects against *later fabrication of history*, not against a false declaration at the time. |
| **L2** | **Corroborated** | At least one independent evidence source **outside the declarer's control** is consistent with the claim (e.g. a distinct lane identity in the engineering history, per P-4). Consistency, not proof. **The sufficiency of an evidence source is an Architecture determination subject to Governance approval.** |
| **L3** | **Verified** | An independent party, **meeting the P-2 Class-B independence requirements**, examined the evidence and established the attribution claim. (A Class-B act — it cannot be claimed by a participant in the work.) |
| **L4** | **Independently attested** | Attribution is established using evidence that the declarer cannot forge independently (for example, cryptographic attestation, trusted signing, or equivalent assurance mechanisms). This attests a **credential or identity construct, not necessarily a specific natural person**. |

**Standing rules over the ladder (from the approved decisions, restated not re-decided):**

- **No level ever authorizes.** Assurance describes confidence in *who acted* — permission still comes only from the human decision and the governed grant (INV-ATTR-1).
- **The level must be stated and must travel.** Every governed action discloses its level, and every surface that presents the record must show it (INV-ATTR-3). *"Verified by X"* may only be displayed where L3+ actually holds.
- **Levels may evolve; the ladder's names may be extended.** The model survives new mechanisms because it classifies evidence by *independence from the declarer*, not by technology.
- **Honesty about composition:** distinct lane identities (L2 evidence) do not, by themselves, establish independence of persons, systems, or decision authority (P-4). Climbing the ladder never converts attribution into an independence verdict.

---

## 3 · Business use categories and required assurance

Requirements are driven by the P-1 criteria — business risk, governance importance, audit requirements, demonstrated incidents. **"Required" means: the level the business needs for that use. "Today" states honestly what the current platform can supply.** The gap between them is managed by §4 — it does not silently block work, and it is never hidden.

| Category | Typical acts | Required level | Today | Gap handling |
|---|---|---|---|---|
| **C1 · Administrative record-keeping** | session logs · progress notes · portfolio views | **L0 Declared** | L0–L1 | none — requirement met |
| **C2 · Governed lifecycle acts** | registering assignments · handoffs · routing · closures | **L1 Recorded** | L1 | met; L2 desirable as P-4 lands |
| **C3 · Human authority acts** | authorizations · STARTs · approvals · acceptance decisions | **L2 Corroborated** *(target)* | **L0–L1** | **known gap** — **until the target level is achieved, all authority acts MUST explicitly disclose the actual assurance level present at the time of the act** (a mandatory rule, not an implied one); first candidate for escalation when triggers fire |
| **C4 · Independence-bearing claims** | *"this verification was independent"* · Class-B review claims · self-review disclosures | **L2 Corroborated** *(target)*, with mandatory P-2 disclosure at every level | **L0 (declared)** | **known gap** — the disclosure duty carries the honesty burden until evidence improves |
| **C5 · Externally consequential acts** | anything with regulatory, contractual or audit exposure outside the programme | **L3–L4** as the obligation demands | not available | **out of current capability** — if such an act arises, that is itself a P-6 trigger (regulatory/contractual) |

**Three consequences of this table, stated plainly:**

1. **It creates no new blocks.** Per P-5, an unmet target never gates automatically — the gap is *high-priority reported evidence*, and where independence or authority materially matters, the human decides with the gap visible.
2. **It makes today's honesty structural.** The programme has been writing *"self-declared, not attestable"* into records by hand; C3/C4 turn that practice into a stated requirement with a named target.
3. **It gives P-4's Architecture work its acceptance bar:** lane identities exist to move C3/C4 evidence from L0–L1 to L2. A design that cannot do that misses the business point.

---

## 4 · The escalation scheme — one loop, not two lists

P-1 demands escalation criteria; P-6 defines reconsideration triggers. **They are one scheme:** the same evidence that says *"assurance is insufficient for this use"* is the evidence that reopens the record-change question. The seven approved triggers, unchanged:

attribution dispute · audit failure · independence limitation · governance-control failure · regulatory/contractual requirement · material incident · repeated operational correction.

**The loop:**

```
operate under this model
      ↓
a trigger fires (Governance detects and surfaces it — ES-001.3 duty, no automation)
      ↓
Governance names: which category, which required level, what fell short
      ↓
Human/PO/ARB decides: raise the requirement · accept the risk · commission stronger mechanism
      ↓
if mechanism work: Architecture designs (P-6 path) · Implementation builds if authorized
      ↓
Independent Verification verifies
```

**Two escalation rules:**

- **A trigger raises a question, never a level.** Requirements change only by Human/PO/ARB decision — the model has no self-amending part.
- **Detection is Governance's standing duty**, exercised through the records; per P-5 no automated instrument may act on a trigger, only surface it.

---

## 5 · What approval of this model would and would not mean

**Would mean:** the assurance vocabulary (§2) and the requirement mapping (§3) become the **business requirements baseline** that Architecture designs against; the gaps in C3/C4 are formally acknowledged, disclosed, and targeted; the escalation loop (§4) becomes the governed path for changing any of it.

**Would not mean:** no mechanism chosen · no implementation authorized · no gate created · no change to the workflow engine · no change to any P-1…P-6 decision · Architecture still requires its own commissioning act.

---

**Traceability:** P-1 `02f813ae` (commission + escalation criteria) · P-2 `8de09453` (Class A/B, disclosure shape → C4) · P-3 `6ad39fa0`+addendum (INV-ATTR-1/2/3; requirement/mechanism split) · P-4 `fdfd6470` (L2 evidence class; the loophole sentence → §2 composition rule) · P-5 `defc8a22` (advisory boundary → §3 consequence 1, §4 rules) · P-6 `30976423` (the seven triggers, verbatim → §4) · accepted root gap `487fce74` (why C3/C4 sit at L0–L1 today) · ES-001.3 (detection duty)
