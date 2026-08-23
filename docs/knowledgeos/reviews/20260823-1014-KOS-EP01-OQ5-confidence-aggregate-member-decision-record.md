# KnowledgeOS — OQ-5 Decision Record — Should Confidence Remain an Aggregate Member, or Become a Derived Read-Side Attribute? (2026-08-23)

> **Commission:** the **Human Principal Architect (HPA)**, 2026-08-23 — *"Proceed with OQ-5 as the next separately governed act. Prepare the OQ-5 decision package, grounded exclusively in the existing architecture, governance, and evidence. … Do not implement anything. Do not open a research track autonomously. If the OQ-5 decision cannot be resolved from existing evidence and genuinely requires research, identify the precise bounded research requirement and STOP for HPA authorization. Do not proceed to F-1…F-5, AH-5, AH-4, architectural consolidation, or Kernel work. After delivering the OQ-5 decision package, STOP for the HPA ruling."* OQ-5 is one of the remaining **deferred decisions** recorded in v1.1 §20 (the boundary is recorded, the answer is not invented); the Expression↔Meaning Port Contract records a **position at contract altitude** (its §4 OQ-5 note — *"unchanged by this contract; ⟨R-1⟩ keeps it safe"*); **the ruling remains the HPA's**.
> **Position:** `P5 → AH-1…AH-5 → EP-01 → T-2/T-3 (G-1…G-8 GREEN) → EP-02 (✅ ACCEPTED) → T-5 (v1.1 REMAINS · ✅ ACCEPTED) → OQ-2 (✅ RESOLVED · CLOSED) → OQ-3 (✅ RESOLVED · CLOSED) → OQ-5 (✅ RESOLVED · CLOSED) → ← WE ARE HERE (STOP) → F-1…F-5 · AH-5/AH-4 → Kernel decision`.
> **Status:** ✅ **OQ-5 DECISION RECORD — HPA ACCEPTED (2026-08-23) · OQ-5 RESOLVED · CLOSED** — the HPA ruled **ACCEPT** (*Confidence remains an aggregate member; Confidence is not a mechanism confidence score — a domain-owned, structured epistemic attribute assigned inside the KnowledgeOS boundary*); **no architecture change follows** — no v1.1 edit · no contract edit · no v1.2 · no member added or removed · register **25+4 unchanged** · Constitution **FROZEN** · research **CLOSED** · OQ-4 **unauthorized** · Port Contract remains **PROPOSED · NON-AUTHORITATIVE** · **no Kernel implementation is authorized by this act**.

---

## 1 · The question (v1.1 §20 · OQ-5)

> **Should Confidence remain an aggregate member at all, or become a derived read-side attribute? ⟨R-1⟩ makes it safe; it does not make it necessary.**

v1.1 records this question as **deliberately unresolved**, at altitude **Logical Architecture** (v1.1 §20 · OQ-5, line 622). Two review records frame it precisely:
- the **Second Architectural Review** (20260822-1459) — Confidence reviewed as *"Ambiguous — the weakest member"* and ruled **KEEP — with refinement R-1** (line 166): the member survives **only** as structured epistemic metadata assigned inside the boundary;
- the **LA Review-01** (20260822-1611, line 71) — *"Epistemic confidence is a property of the knowledge state, determined by the domain's own evidence and evaluation — not by the mechanism's self-assessment"*; OQ-5 is **orthogonal** to the port contract (line 155) — whichever way it resolves, no mechanism score crosses as Confidence.

The Expression↔Meaning Port Contract records the question at contract altitude (its §4 OQ-5 note, line 192): *"OQ-5 — should Confidence remain an aggregate member? Unchanged by this contract; ⟨R-1⟩ keeps it safe. A later Logical-Architecture decision may revisit it."* **The ruling on that position remains the HPA's** (v1.1 §20 · OQ-5).

The act therefore resolves OQ-5 by (a) stating the recorded boundary exactly, (b) determining whether the architectural answer is decidable from existing evidence (the HPA's escalation rule), (c) verifying the KEEP reading against every guard, and (d) presenting the ruling options to the HPA. **It changes nothing.**

> **Same-named "OQ-5" — excluded.** The identifier "OQ-5" also appears in `2026-08-18-KOS-AIP04-DISCOVERY-001-adr-aip-04-capability-discovery-proposal.md` (§17 — *role-model ownership*: BC-7's lane-role model vs governance-published authority model) — a **different question in a different lineage** (the AIP-04 capability-discovery proposal), not this act's subject. This record concerns **v1.1 §20 · OQ-5 only** (Confidence placement). No conflation is made.

---

## 2 · The recorded boundary (the answer is grounded, never invented)

The architecture already establishes the entire placement envelope the question needs. Every clause below is existing law:

| Surface | The rule (existing) |
|---|---|
| **Confidence as an aggregate member** (v1.1 §6, line 214) | a **structured** epistemic attribute — never a scalar replacing epistemic structure (Article 2.3); carried as **Temporal Epistemic Metadata (UM-46)**, not as a knowledge-quality score — a governed **Value object** among the aggregate's members |
| **⟨R-1⟩ Boundary rule** (v1.1 §6 · §7 · §19) | Confidence is **assigned INSIDE the boundary**; a mechanism-supplied score — parser accuracy, model likelihood, match strength — **must never cross the port and become Confidence**; a mechanism's *"74% confident"* is a statement about the mechanism, not about the knowledge |
| **INV-KOS-DIMENSION-001** (v1.1 §7, line 246) | dimensions evolve independently; no implicit transition; **no scalar surrogate for structure** — ⟨R-1⟩ no mechanism-supplied score becomes Confidence |
| **Second Architectural Review · Confidence finding** (20260822-1459, line 166) | Confidence was the **weakest member** under the false-acceptance evidence — *"a 74.2%-accurate mechanism would naturally hand over '0.74'"* — but the member **survives** as structured epistemic metadata assigned inside the boundary; verdict **KEEP — with refinement R-1** |
| **LA Review-01 · Confidence** (20260822-1611, line 71) | *"Epistemic confidence is a property of the knowledge state, determined by the domain's own evidence and evaluation — not by the mechanism's self-assessment"*; if mechanisms could assign Confidence, it would be a **backdoor to epistemic authority** |
| **Domain-owned column** (Port Contract §5, line 173) | the port separates **mechanism-owned** (expression · candidate · metadata) from **domain-owned** (admitted meaning · identity · epistemic state · **confidence** · history); nothing crosses except through the Verification Port as a candidate with a preserved justification path |
| **⟨r4⟩ Candidate payload vocabulary** (v1.1 §10, line 366) | interpretation probabilities · uncertainty · provenance · transformation evidence = **port-contract vocabulary, never aggregate members** — the *candidate's* confidence-like signals are already kept out of the aggregate by ⟨r4⟩ |
| **EpistemicState** (v1.1 §9, line 212) | the seven states — VALIDATED · QUESTIONABLE · REJECTED · CONFLICTED · UNKNOWN · ABSENT · FALSE — first-class negative and failure states; **no mechanism may ever produce this member**; Confidence is the structured epistemic metadata carried alongside, as Temporal Epistemic Metadata (UM-46) |
| **Projection context** (v1.1 §5.3 · Article 5) | derive **regenerable, non-authoritative views** (derived view · projection · DerivedView); the core (read-only); downstream; **no write-back** — the read-side is **non-authoritative by design** |
| **KnowledgeCore ownership** (v1.1 §5.3) | the core owns **justified epistemic state** — knowledge · identity · admitted meaning · context · evidence link · justification path · agency · **epistemic state · temporal validity** · history · relation · conflict — the aggregate's reason for being |

**What this envelope already decides:** Confidence is the **domain's own authoritative epistemic evaluation** of the knowledge object (LA Review-01: *a property of the knowledge state, determined by the domain's own evidence and evaluation*), **assigned inside the boundary** (⟨R-1⟩ · INV-KOS-DIMENSION-001), carried as structured Temporal Epistemic Metadata — and the Second Review already ruled **KEEP** it as a member, with R-1. The read-side (Projection · DerivedView) is **regenerable and non-authoritative by design** — it cannot carry an authoritative epistemic evaluation. The only open remainder is therefore the placement question the OQ-5 note poses: is the member *necessary*, or could the evaluation live read-side?

---

## 3 · The positions at contract altitude (§4 OQ-5 note · the two reviews)

- **The Port Contract's §4 OQ-5 note (line 192):** *"OQ-5 — should Confidence remain an aggregate member? Unchanged by this contract; ⟨R-1⟩ keeps it safe. A later Logical-Architecture decision may revisit it."* — the contract **does not change** the member; it confirms ⟨R-1⟩ makes it safe and leaves the placement question to Logical Architecture (this act).
- **The Second Architectural Review (line 166):** Confidence — *"Ambiguous — the weakest member"* — the false-acceptance evidence names the hazard (a 74.2%-accurate mechanism hands over "0.74"); **the member survives only as structured epistemic metadata assigned inside the boundary**; verdict **KEEP — with refinement R-1**.
- **The LA Review-01 (line 71 · line 155):** Confidence is *"a property of the knowledge state, determined by the domain's own evidence and evaluation"*; OQ-5 is **orthogonal** to the port contract — *"whichever way it resolves, no mechanism score crosses as Confidence"* — the placement question is **HPA / Logical Architecture**.

---

## 4 · The escalation check — evidence sufficiency (the HPA's research rule applied)

The HPA's standing escalation rule (2026-08-23): *"If the OQ-5 decision cannot be resolved from existing evidence and genuinely requires research, identify the precise bounded research requirement and STOP for HPA authorization"* — and the research posture is **inform, never initiate**. Applying it to OQ-5:

| Sub-question | What it is | Evidence status | Research required? |
|---|---|---|---|
| **The placement question** — *must Confidence remain an aggregate member?* | a **Logical Architecture** question about the ownership of the knowledge object's authoritative epistemic evaluation | **Decidable from existing law** — Confidence is domain-owned (LA Review-01 line 71 · Port Contract domain-owned column line 173) · assigned inside the boundary (⟨R-1⟩ · INV-KOS-DIMENSION-001) · the Second Review already ruled **KEEP-with-R-1** (line 166) · the read-side (Projection · DerivedView) is **non-authoritative by design** with **no write-back** (v1.1 §5.3 · Article 5) · the candidate's confidence-like signals are already excluded from the aggregate by ⟨r4⟩ (line 366) | **No** — the answer is a rendering of existing law; a bounded research requirement would add nothing the architecture does not already decide |
| **The empirical / mechanism question** — *is any real mechanism's score reliable?* | the mechanism-reliability question | **Not OQ-5's to resolve** — ⟨r4⟩ already excludes candidate scores from the aggregate; the mechanism-reliability question is **OQ-4 territory (KOS-SCB v0.2), which stays UNAUTHORIZED** | **No — and not OQ-5's**: OQ-5 is orthogonal to mechanism behavior (LA Review-01 line 155); the empirical question stays in OQ-4, never reopened implicitly |

**Application:** the **architectural decision (the placement ruling) is evidence-sufficient** — **no bounded research requirement is proposed for OQ-5**. The empirical separation remains in **OQ-4 (stays unauthorized)**, named and explicitly not reopened — the agent opens no research track. If the HPA judges the placement question to require a future design decision (e.g., at the Kernel implementation), the ruling should be **DEFER** (§6, option 3) — keeping OQ-5 open to a later authorized act, never an agent-initiated one.

---

## 5 · Gate check — the KEEP reading holds every guard

| Guard | Does resolving OQ-5 as "Confidence remains an aggregate member" violate it? | Verdict |
|---|---|---|
| **⟨R-1⟩ · INV-KOS-DIMENSION-001** (no mechanism-supplied score becomes Confidence; no scalar surrogate for structure) | No — the member remains **structured epistemic metadata assigned inside the boundary**; the read-side derivation is refused, so no mechanism score crosses the port as Confidence (the ⟨R-1⟩ separation the LA Review-01 calls the *"backdoor to epistemic authority"* stays closed) | ✅ **Holds** |
| **⟨r4⟩** (candidate payload vocabulary never aggregate members) | No — Confidence the member is the **domain's own** evaluation, distinct from the candidate's interpretation metadata (⟨r4⟩ keeps *those* out); no member is added | ✅ **Holds** |
| **Second Review · KEEP-with-R-1** | No — the member is kept in exactly the R-1 form (structured, boundary-assigned); the placement ruling is the review's own verdict rendered at the placement altitude | ✅ **Holds** |
| **LA Review-01 · domain-owned** | No — Confidence stays domain-owned, a property of the knowledge state; the read-side projection would *invert* this (non-authoritative by design) | ✅ **Holds** |
| **Port Contract · domain-owned column** | No — Confidence stays in the domain-owned column; the mechanism-owned/domain-owned separation is preserved | ✅ **Holds** |
| **Projection context · Article 5** | No — the read-side remains what it always was: regenerable, non-authoritative, no write-back; the authoritative evaluation stays in the aggregate | ✅ **Holds** |
| **KnowledgeCore ownership** (owns justified epistemic state · temporal validity) | No — the aggregate continues to own its justified epistemic state, including the structured Confidence carried as Temporal Epistemic Metadata (UM-46) | ✅ **Holds** |
| **No new law** (no new invariant · state · member · event · register row) | No new invariant/state/register row; **no member is added or removed** — the 12-member set stays **12** (v1.1 §19: 12 → 12 → 12) | ✅ **Holds** |
| **No mechanism / no Kernel / no SNF promotion** | No mechanism is created; no SNF promotion; no Kernel authorization; the empirical question is not OQ-5's (OQ-4, unauthorized) | ✅ **Holds** |
| **No v1.1 change · no v1.2 · no Constitution change** | The position is recorded at **contract altitude** and confirmed by the Second Review's KEEP verdict; v1.1 §20 keeps the deferral until the HPA rules; the ruling itself changes no architecture | ✅ **Holds** |

**Aggregate result:** the KEEP reading (Confidence remains an aggregate member) **violates nothing** — it is a **rendering of existing law** (⟨R-1⟩ · INV-KOS-DIMENSION-001 · ⟨r4⟩ · Second Review KEEP-with-R-1 · LA Review-01 domain-owned · Port Contract domain-owned column · Projection non-authoritative design · KnowledgeCore ownership), not new law.

---

## 6 · The proposed ruling (RECOMMENDATION — the HPA decides)

The ruling is the HPA's alone (v1.1 §20 · OQ-5: *"the ruling remains the HPA's"*). The options, with consequences:

| Option | Ruling | Consequences |
|---|---|---|
| **ACCEPT** *(recommended)* | **Yes — Confidence remains an aggregate member.** The knowledge object's **authoritative epistemic evaluation** is the domain's own, **assigned inside the boundary** (⟨R-1⟩), carried as structured Temporal Epistemic Metadata (UM-46) — the aggregate owns justified epistemic state (v1.1 §5.3). The **read-side cannot carry it**: Projection · DerivedView is **regenerable and non-authoritative by design** with no write-back (Article 5); a mechanism-derived read-side value is **prohibited** (⟨R-1⟩ · INV-KOS-DIMENSION-001). The read-side is served, where useful, by a **DerivedView projection** of the member (existing capability, orthogonal) — never a replacement. This confirms the Second Review's **KEEP-with-R-1** at the placement altitude | Records **OQ-5 RESOLVED** with Confidence retained as an aggregate member. The note in the OQ-5 row is answered: ⟨R-1⟩ makes the member *safe*; the member is also *necessary* — it is the only law-consistent carrier of the aggregate's own authoritative epistemic evaluation (the read-side is non-authoritative by design). **No architecture change** — no member removal · no new member · no invariant/state/member/event/register row · no v1.1 edit beyond a status annotation · no v1.2 · no mechanism · no Kernel authorization · register **25+4 unchanged** |
| **REJECT** (move to read-side) | **No — Confidence becomes a derived read-side attribute** | **Not a rendering of existing law.** Either reading is incoherent under the recorded boundaries: (a) read-side as a **non-authoritative DerivedView projection** (Projection context: regenerable, no write-back) → the aggregate's authoritative epistemic evaluation would have **no law-consistent carrier** — inverting LA Review-01's *"property of the knowledge state"* and the KnowledgeCore's ownership of justified epistemic state; (b) read-side as **derived from mechanism-supplied scores** → **violates ⟨R-1⟩ · INV-KOS-DIMENSION-001** (the Second Review's "74%-confident" handover, the LA Review-01 "backdoor to epistemic authority"). Making REJECT coherent would require **new law** — a write-back path into the aggregate, or a re-classification of Confidence as non-authoritative — a structural change, not a resolution |
| **DEFER** (to a later Logical Architecture / Kernel act) | **Not decided now** — OQ-5 stays open; the placement is settled at the Kernel-implementation design | The member stays as it is (the status quo is the KEEP state); OQ-5 reopens on a future authorized act; the bounded research requirement is **not** proposed (no evidence gap) — a DEFER is a sequencing choice, not an evidence finding |

**Recommendation rationale (evidence, not preference):** the KEEP reading is the **only** reading consistent with all existing law at once. Confidence is *"a property of the knowledge state, determined by the domain's own evidence and evaluation"* (LA Review-01) — domain-owned (Port Contract column) — *"assigned INSIDE the boundary"* (⟨R-1⟩) — and the Second Review already ruled **KEEP-with-R-1** (line 166). The read-side is *"regenerable, non-authoritative"* with **no write-back** (v1.1 §5.3 · Article 5) — by construction it cannot carry an authoritative epistemic evaluation. REJECT is incoherent without new law; DEFER would defer a decidable question. The OQ-5 note's distinction (*"⟨R-1⟩ makes it safe; it does not make it necessary"*) is answered by the *ownership* argument, which is independent of ⟨R-1⟩: the member is necessary because the aggregate owns justified epistemic state and Confidence is its structured epistemic evaluation. ACCEPT is therefore the minimal, law-consistent ruling.

---

## 7 · The decision (HPA) — ✅ ACCEPTED (2026-08-23) · OQ-5 RESOLVED · CLOSED

```text
OQ-5 — HPA ruling (2026-08-23): ✅ ACCEPTED.

  ✅ ACCEPT (as recommended)  — Confidence REMAINS an aggregate member: the
      domain's own structured epistemic evaluation, assigned inside the
      boundary (⟨R-1⟩), carried as Temporal Epistemic Metadata (UM-46); the
      read-side is non-authoritative by design (Projection · DerivedView) and
      cannot carry it; the member is necessary (ownership), not merely safe
      (⟨R-1⟩) — OQ-5 RESOLVED; Second Review KEEP-with-R-1 confirmed.
      Confidence is NOT a mechanism confidence score — it is a domain-owned,
      structured epistemic attribute assigned inside the KnowledgeOS boundary.

  Note: no architecture change follows from this ruling. No member removal ·
        no new member · no invariant/state/event/register row · no v1.2 · no
        Constitution change · register 25+4 unchanged · OQ-4 stays
        unauthorized · no research track · no Kernel authorization follows.
```

### §7a — HPA acceptance verbatim-in-substance (2026-08-23)

The HPA ruled **ACCEPT** on OQ-5 — in substance:

> **"HPA ACCEPT — Confidence remains an aggregate member. Confidence is not a mechanism confidence score. It is a domain-owned, structured epistemic attribute assigned inside the KnowledgeOS boundary."**

Each clause traces to existing law:

- *"Confidence remains an aggregate member"* — the Second Architectural Review's **KEEP — with refinement R-1** (line 166) · the KnowledgeCore's ownership of **justified epistemic state** (v1.1 §5.3) · the LA Review-01's *"a property of the knowledge state"* (line 71).
- *"Confidence is not a mechanism confidence score"* — **⟨R-1⟩** (a mechanism-supplied score must never cross the port and become Confidence) · **INV-KOS-DIMENSION-001** (no scalar surrogate for structure) · the Second Review's 74.2%-mechanism hazard (line 166 — *"would naturally hand over '0.74'"*) · the LA Review-01's *"backdoor to epistemic authority"* (line 71).
- *"a domain-owned, structured epistemic attribute assigned inside the KnowledgeOS boundary"* — the Port Contract's **domain-owned column** (line 173 — confidence is domain-owned) · v1.1 §6 line 214 (a **structured** epistemic attribute, carried as **Temporal Epistemic Metadata UM-46** — never a scalar knowledge-quality score) · **⟨R-1⟩** (assigned **inside** the boundary).

**The HPA confirmed the analysis identified no research gap** — *"Do not open another research track."* The **SNF research track remains CLOSED** · **OQ-4 stays UNAUTHORIZED** · the research posture (**inform, never initiate**) is unchanged. **The HPA confirmed the sequence after this ruling:** *"the next step is F-1…F-5, not more research"* → AH-5/AH-4 → architectural consolidation → **Kernel decision** — with the practical framing: *"We are not yet at 'start coding the Kernel.' We are at: final architectural decision closure → Kernel boundary definition → Kernel capability mapping → implementation decision."* **F-1…F-5 remains a separate governed act — a new commission; none is issued by this ruling.**

---

## 8 · STOP

OQ-5 **RESOLVED · CLOSED (2026-08-23)** — the HPA ruled **ACCEPT** (*Confidence remains an aggregate member; Confidence is not a mechanism confidence score — a domain-owned, structured epistemic attribute assigned inside the KnowledgeOS boundary*). **Nothing was modified by this act**: the ruling is a decision-record act — no v1.1 edit · no contract edit · no code · no v1.2 · no register · no Constitution · no Kernel · no SNF · no corpus · **no research track opened** · **no member added or removed**. The **research-escalation rule was applied and confirmed by the HPA**: the placement question was evidence-sufficient — **no bounded research requirement was proposed for OQ-5** — and the empirical mechanism-reliability question is **named and explicitly routed to OQ-4 (stays unauthorized)**, never reopened implicitly; **the HPA: "Do not open another research track."** The following acts remain **separate governed acts, each a new commission**: **F-1…F-5 · AH-5 deferral · AH-4** — and after them the architectural-consequence consolidation (what minimum Kernel boundary the evidence actually requires) and the **Kernel decision**. The HPA's practical framing: **"We are not yet at 'start coding the Kernel.' We are at: final architectural decision closure → Kernel boundary definition → Kernel capability mapping → implementation decision."** **The Kernel still waits.**

---

## Traceability

- **Commission:** HPA, 2026-08-23 — *"Proceed with OQ-5 as the next separately governed act. … Prepare the OQ-5 decision package, grounded exclusively in the existing architecture, governance, and evidence … Do not implement anything … Do not open a research track autonomously … STOP for the HPA ruling."* — together with the escalation rule: *"If the OQ-5 decision cannot be resolved from existing evidence and genuinely requires research, identify the precise bounded research requirement and STOP for HPA authorization"* (research posture: **inform, never initiate**, recorded `793e0202`).
- **The question:** v1.1 §20 · OQ-5 — *"Should Confidence remain an aggregate member at all, or become a derived read-side attribute? ⟨R-1⟩ makes it safe; it does not make it necessary"* — **OPEN · deliberately unresolved** · altitude **Logical Architecture** · boundary recorded, answer not invented · *"the ruling remains the HPA's."*
- **The position assessed:** Expression↔Meaning Port Contract §4 OQ-5 note (line 192 — unchanged by this contract; ⟨R-1⟩ keeps it safe) · Second Architectural Review (line 166 — Confidence KEEP — with refinement R-1) · LA Review-01 (line 71 — property of the knowledge state; line 155 — OQ-5 orthogonal to the contract).
- **Grounding (existing law, cited):** v1.1 §6 Confidence member (line 214) · §7 INV-KOS-DIMENSION-001 (line 246) · §9 EpistemicState (line 212) · §10 ⟨r4⟩ (line 366) · §5.3 KnowledgeCore ownership + Projection context (Article 5 — regenerable, non-authoritative, no write-back) · §20 OQ-5 (line 622) · §19 member count 12 → 12 → 12 · Port Contract §5 domain-owned column (line 173) · Second Architectural Review (20260822-1459) line 166 · LA Review-01 (20260822-1611) lines 71 · 155.
- **Same-named identifier excluded:** the "OQ-5" in `2026-08-18-KOS-AIP04-DISCOVERY-001` (§17, role-model ownership) is a **different question in a different lineage** — not this act's subject; no conflation is made (§1 note).
- **Chain position:** OQ-2 resolved (`56e1bd8a`) · OQ-3 resolved (`55c73b1b`) → OQ-5 is the next deferred decision. The chain (`P5 → … → OQ-2 → OQ-3 → OQ-5 → F-1…F-5 → AH-5/AH-4 → Kernel decision`) records each as a **separate HPA ruling**, untouched by the refinement.
- **Discipline honored:** **grounded, not invented** — every clause traces to existing law (§2); **escalation rule applied** (§4 — the placement question is evidence-sufficient; the empirical question is routed to OQ-4, unauthorized; no research track opened); **gate-verified** (§5 — the KEEP reading holds every guard); **recommendation separated from decision** (R-34 — the record delivers evidence and recommendation; the HPA's ruling is a separate human act); **nothing changed** (§8) · register **25+4 unchanged** · Constitution **FROZEN** · research **CLOSED** · OQ-4 **unauthorized** · **the Kernel still waits.**
- **Status:** ✅ **OQ-5 DECISION RECORD — HPA ACCEPTED (2026-08-23) · OQ-5 RESOLVED · CLOSED** — the HPA ruled **ACCEPT**: **Confidence remains an aggregate member** (domain-owned, boundary-assigned, structured — the read-side is non-authoritative by design and cannot carry it; the member is necessary, not merely safe; **Confidence is not a mechanism confidence score — a domain-owned, structured epistemic attribute assigned inside the KnowledgeOS boundary**); **no research track** (the HPA confirmed the analysis identified no research gap). Next (separate governed acts, each a new commission): **F-1…F-5 · AH-5 deferral · AH-4** · consolidate architectural consequences · **Kernel decision**.
