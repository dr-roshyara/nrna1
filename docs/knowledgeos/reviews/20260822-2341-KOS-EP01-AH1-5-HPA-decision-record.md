# KnowledgeOS — AH-1…AH-5 HPA Decision Record — five proposed rulings, their consequences, acceptance fields ⬜ OPEN (2026-08-22)

> **Role:** the instrument that carries the HPA's **five proposed rulings** on AH-1…AH-5 to their formal acceptance. It presents each proposed ruling — the HPA's own reading of the evidence — its consequence, and what confirming it does and does **not** authorize. It then **STOPS**. The acceptance fields are **⬜ OPEN**; the HPA confirms each. **Claude does not decide.**
> **Source:** Human Principal Architect (HPA), 2026-08-22 — *"prepare an **HPA Decision Record for AH-1…AH-5**, but **do not let Claude make the decisions**. It should present the five proposed rulings and their consequences, then stop for your explicit acceptance."* The proposals below are the HPA's own, recorded **verbatim-in-substance**; nothing here is Claude's recommendation.
> **Position:** `P5 research CLOSED → AH-1…AH-5 decision support DONE → HPA rulings ← WE ARE HERE → authorize specific acts → Port Contract refinement → reassess Reference Architecture`. The HPA: *"We should now make the five architectural rulings."*
> **Status:** 📋 **AH-1…AH-5 HPA DECISION RECORD — DELIVERED · PROPOSED · NON-AUTHORITATIVE.** All five acceptance fields **⬜ OPEN** · **no ruling is final until the HPA confirms it** · **no act is authorized** · register **25+4 unchanged** · Constitution **FROZEN** · research **CLOSED** · OQ-4 **unauthorized**.

---

## 0 · How this record works

1. **It records proposed rulings; it does not make them.** Each section below states the HPA's proposed ruling, the HPA's stated reading, the P5 evidence it rests on, the consequence *if confirmed*, and what confirmation does **not** authorize. The acceptance line is the HPA's to fill.
2. **Acceptance is separate from act-authorization.** Confirming AH-1 / AH-3 names a Port Contract vocabulary refinement; it does not perform it. The refinement is a **separate, explicitly authorized act**, to be commissioned after acceptance (EP-01 plan → approval → implementation).
3. **Claude's role is recording only.** The strongest statement in this record never exceeds the evidence (P5 is toy-world; no natural-language claim; one seed).

**The boundary (HPA-confirmed at P5 closure, restated verbatim-in-substance):**

```text
P5 research                         CLOSED
     │
     ▼
AH-1…AH-5 decision support          DONE
     │
     ▼
HPA rulings                         ← WE ARE HERE (this record)
     │
     ├── reject / defer → no change
     │
     └── accept
           │
           ▼
     authorize specific act         (separate, explicit — not implied by acceptance)
           │
           ▼
     Port Contract refinement
           │
           ▼
     reassess Reference Architecture
           │
           ├── no material architectural impact
           │       → v1.1 remains
           │
           └── material impact
                   → controlled v1.2
```

---

## 1 · The five proposed rulings

### AH-1 — **ACCEPT** (proposed) — UNKNOWN vs NOT_EXPRESSED as separate port declarations

- **HPA reading:** *"Strongest architectural finding. v1.1 already distinguishes them in the domain, but the Port Contract currently has only one generic 'declared insufficiency.'"*
- **Evidence:** P5 Established item 9 — no producer represents the epistemic/semantic absence distinction, **110/110 failures**, all producers · Established item 2 — v0.1 collapsed NOT_EXPRESSED vs UNKNOWN at 0.247 under τ = 0.40, v0.2 separates all four states (0.45–1.00, gate 14/14) · Suggested item 2 — a structural inability to say *"I don't know which filler"* may be the common cause of the `unknown_vs_not_expressed` failure and the high false-acceptance rates.
- **Consequence if confirmed:** a **Port Contract vocabulary refinement** — obligation 3's *declared insufficiency* gains the structure to distinguish *"no filler"* from *"filler unknown"* (two declarations, not one), and **OQ-1** is resolved in that direction. **Not** a v1.1 change · **not** a Constitution change · **not** an aggregate change. The domain-side semantics (v1.1 §9: unknown ≠ absent ≠ false; Article 9) already exist and are unchanged.
- **Not authorized by confirmation:** editing v1.1 · editing the Constitution · changing the aggregate · implementing the distinction in code · running any experiment.
- **Constitutional impact:** none to any article — Article 9 is the source the refinement would render at the boundary; V.3 amendment discipline is not engaged.
- **HPA confirmation: ⬜ OPEN — PENDING the HPA's explicit acceptance.**

### AH-2 — **REJECT / RECORD AS CORROBORATION** (proposed) — agreement ≠ sameness

- **HPA reading:** *"Already fully protected by the architecture. P5 merely provides empirical corroboration."*
- **Evidence:** P5 Established item 7 — 0.587 of agreeing mechanism-pairs agree on a wrong reading; the *"with NP → INSTRUMENT"* rule makes A/B/C wrong together on 90/90 traps.
- **Consequence if confirmed:** **no architecture change.** The finding is **recorded as corroboration** of the existing prohibition — INV-KOS-IDENTITY-001 (identity assigned, never derived; similarity ≠ identity) and the Port Contract's collision rule (**Q8** — collision = evidence, never admission). Optionally, the corroborating number is annotated beside the existing invariant in an evidence/record artifact — an annotation, not a change.
- **Not authorized by confirmation:** any change to v1.1 · Constitution · aggregate · Port Contract · invariants · register.
- **Constitutional impact:** none — Article 1 (Similarity SHALL NOT become identity) already states the rule the number corroborates.
- **HPA confirmation: ⬜ OPEN — PENDING the HPA's explicit acceptance.**

### AH-3 — **ACCEPT** (proposed, composable with AH-1) — parse failure vs reading uncertainty

- **HPA reading:** *"Useful distinction at the mechanism/port boundary, but related to AH-1. It does not require a domain change."*
- **Evidence:** P5 AH-3 row — registry ignorance and semantic underdetermination both scored as "warranted abstention" (SNF-D's definitional artifact) · Threats-to-validity item 6 — *"abstention_warranted is partly definitional for SNF-D (AH-3)."*
- **Consequence if confirmed:** a **Port Contract vocabulary refinement** — the declared-insufficiency declaration gains a reason dimension: *cannot parse the word* (parse-level inability) vs *cannot decide the reading* (reading-level underdetermination). **Composable with AH-1** (reason × what-was-undetermined) but independently confirmable. **Not** a domain change · **not** a v1.1 change. The domain-side rule — declared insufficiency → UNKNOWN whatever its reason (⟨C-5⟩) — is unchanged.
- **Not authorized by confirmation:** editing v1.1 · editing the Constitution · changing the aggregate · making the abstention taxonomy a domain object · implementing in code · running any experiment.
- **Constitutional impact:** none — Article 9 (structured uncertainty SHALL be preserved, never flattened) is the source the richer declaration would render; V.3 not engaged.
- **HPA confirmation: ⬜ OPEN — PENDING the HPA's explicit acceptance.**

### AH-4 — **ACCEPT AS GOVERNANCE** (proposed) — no architecture change

- **HPA reading:** *"Clearly a research-governance requirement, not KnowledgeOS architecture."*
- **Evidence:** P5 Established items 2–4 — a repaired evaluation metric changed measured performance materially (v0.1 credited CORRECT on **45/90** false-consensus traps; v0.2: **0/90**); v0.1's claimed `d(x,x)=0` was false for UNKNOWN-bearing IRs (measured 0.124), fixed in v0.2.
- **Consequence if confirmed:** a **research-governance standing requirement, recorded as governance, not as a v1.1 rule**: any future promotion argument (a mechanism, an SNF encoding, a representation) must **name its metric version, corpus/family scope, and the failure modes that version addresses** — a measured-competence claim carries its scoring instrument as part of the claim. It takes effect when (if ever) a promotion argument is made, conditioning the future experiment (v0.4 / OQ-4 — both gated) and any promotion submission. **No architecture change.**
- **Not authorized by confirmation:** any architecture change · any experiment · any promotion · editing v1.1 / Constitution / aggregate / Port Contract.
- **Constitutional impact:** none — no article engaged; the requirement *protects* Articles 1 and 3 (a metric version cannot smuggle a similarity score or performance result into an identity or authority claim) without changing them.
- **HPA confirmation: ⬜ OPEN — PENDING the HPA's explicit acceptance.**

### AH-5 — **DEFER** (proposed) — asserted vs possible

- **HPA reading:** *"P5 explicitly says the experiment cannot settle this."*
- **Evidence:** P5 Not-established item — modality/tense/quantifier differences declared DISTINCT in gold but the metric treats modality as graded (0.10), so modality-only pairs still register agreement at τ_conv — *"a known and deliberate tension, unresolved"* · OBS-P5-3 — modality graded vs declared DISTINCT, recorded, **not** silently "fixed" (*"changing it would make every hedge a different meaning"*).
- **Consequence if confirmed:** **no change now.** The tension stays recorded (OBS-P5-3). Whether *asserted* vs *possible* is a **category boundary or a degree** remains a **modelling** question — named owner: OQ-3 territory · DEF-4 (the future canonical form must name its modality treatment). Resolved as part of the post-rulings sequence, not by this record.
- **Not authorized by confirmation:** any change · any metric or architecture decision on modality · editing v1.1 / Constitution / aggregate / Port Contract · running any experiment.
- **Constitutional impact:** none — no article engaged; the deferral leaves Article 9 untouched.
- **HPA confirmation: ⬜ OPEN — PENDING the HPA's explicit acceptance.**

---

## 2 · The likely architectural outcome (as the HPA expects)

> *"If we make those rulings, I would expect …"*

```text
AH-1  ACCEPT   →  Port Contract vocabulary refinement
AH-2  REJECT / CORROBORATION  →  no architecture change
AH-3  ACCEPT   →  Port Contract vocabulary refinement
AH-4  ACCEPT   →  research governance rule
AH-5  DEFER    →  no architecture change
```

> *"That means **we probably do NOT need a KnowledgeOS Reference Architecture v1.2**."* The evidence says v1.1 already has the correct domain-side semantics — UNKNOWN, ABSENT and FALSE are distinct (v1.1 §9), and the Constitution already prevents their collapse (Article 9). The likely refinement is therefore **at the Expression↔Meaning Port Contract, not at the Kernel/domain model.** A controlled v1.2 would only follow if the Port Contract refinement reveals a **material** impact on the Reference Architecture — which the current evidence does not indicate.

---

## 3 · The sequence after the rulings

1. **Authorize the specific acts** for the accepted hypotheses — a **Port Contract vocabulary refinement** (AH-1, AH-3; OQ-1) and a **research-governance rule** (AH-4). Each is a separate, explicit act (EP-01 plan → approval → implementation).
2. **Resolve the already-open questions** — **OQ-1** (structure of declared insufficiency) · **OQ-2** (may SNF-equivalence be recorded as an `EvidenceLink`) · **OQ-3** (modality / asserted-vs-possible) · **OQ-5** (does `Confidence` belong in the aggregate) · **F-1…F-5** (Port Contract wording candidates). The decision-support artifact identified these as **already waiting** — not newly invented by P5.
3. **Reassess the Reference Architecture**: no material architectural impact → **v1.1 remains**; material impact → **controlled v1.2**.
4. **Kernel only after the boundary is settled** — the Kernel question comes after this architectural boundary; **P5 closed with no Kernel implementation, and this record does not reopen that.**

---

## 4 · What this record does NOT do

- **Does not make the rulings.** All five acceptance fields are **⬜ OPEN**; none is final until the HPA confirms it.
- **Does not authorize any act.** No Port Contract edit · no v1.1 edit · no v1.2 · no experiment · no Kernel work · no mechanism promotion · no SNF winner.
- **Does not change the register or the Constitution.** Register **25+4 unchanged** · Constitution **FROZEN**.
- **Does not reopen the research phase.** Research **CLOSED** · **OQ-4 stays unauthorized**.
- **Does not let Claude decide.** The proposals are the HPA's own, recorded for its explicit acceptance.

---

## Traceability

- **Commission:** HPA, 2026-08-22 — *prepare an HPA Decision Record for AH-1…AH-5; present the five proposed rulings and their consequences; stop for the HPA's explicit acceptance; do not let Claude make the decisions.* Recorded verbatim-in-substance.
- **Proposals:** the HPA's own 2026-08-22 message — the "Decision to consider" table and the expected-outcome flow (AH-1 ACCEPT · AH-2 REJECT/CORROBORATION · AH-3 ACCEPT · AH-4 ACCEPT AS GOVERNANCE · AH-5 DEFER; probably **no** v1.2; refinement at the Port Contract, not the Kernel; Kernel deferred).
- **Decision-support basis:** `docs/knowledgeos/reviews/20260822-2334-KOS-EP01-AH1-5-decision-support.md` (commit `29e28d05`) — the 9-field analysis this record's consequences build on.
- **Evidence source:** `docs/knowledgeos/brainstorming/KOS-SNF-RESEARCH-P5-COMPETITION-001.md` — Part I (Established items 2–4 · 7 · 9 · Suggested item 2 · Not-established modality tension · AH-3 row · Threats item 6 · OBS-P5-3).
- **Authoritative grounding:** Reference Architecture v1.1 (§9 seven states · §10 ⟨A-2⟩ obligations · §14 ⟨C-5⟩ · §15 ⟨C-1⟩/⟨R-1⟩ · §16 altitudes · §20 OQ-1…OQ-5) · Constitution v1.0 (Articles 1 · 3 · 6 · 9; V.3) · Expression↔Meaning Port Contract (obligations 3–6 · Q3/Q4/Q7/Q8 · §4 · §6) · Post-Research review (`20260822-1658`, NO CHANGE verdict · OBS-PR-1…3).
- **Predecessor HPA acts on this chain:** AH-1…AH-5 decision support (`20260822-2334`, commit `29e28d05`) · P5 HPA acceptance + closure (`20260822-2327`, commit `c9a7650f`) · EP-02 completion review (commit `32554cbd`) · P5 execution (commit `92d7e5eb`) · Post-Research review HPA acceptance (`20260822-1711`, NO CHANGE).
- **Discipline honored:** advisory recording only — Claude proposes nothing, recommends nothing, decides nothing · the strongest statement never exceeds the evidence (P5 is toy-world) · acceptance and act-authorization kept strictly separate · no new law, invariant, aggregate, member, event, or register row · register **25+4 unchanged** · Constitution **FROZEN** · **next step = the HPA's explicit confirmation of the five rulings** (then the authorized Port Contract / governance acts).
- **Status:** 📋 **AH-1…AH-5 HPA DECISION RECORD — DELIVERED · PROPOSED · NON-AUTHORITATIVE.** Acceptance fields **⬜ OPEN** · Reference Architecture v1.1 **unchanged** · Constitution **FROZEN** · register **25+4 unchanged** · research **CLOSED** · OQ-4 **unauthorized**.
