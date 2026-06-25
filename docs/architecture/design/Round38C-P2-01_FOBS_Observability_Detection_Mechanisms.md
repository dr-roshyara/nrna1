# Round 38C-P2-01 — F-OBS Observability & Detection: Mechanism Design Space

**Program:** NRNA DDD Trustworthiness Research Program
**Phase:** Pass 2 — Mechanism Discovery (governed by P2-00)
**Family:** F-OBS (Observability & Detection) — first family, highest reuse, lowest controversy
**Status:** MECHANISM DISCOVERY — governance mechanisms only. **No software, no DDD (gated).**
**Date:** 2026-06-25

---

## Scope & approach

F-OBS has **7 capabilities**: GC-S1-03 (Impact Assessment), GC-S1-07 (Erosion Detection), GC-S2-05 (Transparency), GC-S2-06 (Drift Detection), GC-S3-04 (Jurisdiction Observability), GC-S4-07 (Challenge Observability), GC-S5-04 (Finality Observability).

Per P2-00, they **share one observability/detection mechanism design space** — explored **once** here and **mapped per capability** (§7). This is the reuse the family-organized approach was designed to capture.

**Upward trace:** Mechanism → F-OBS capability → F-OBS family → (the property each capability serves) → Constitution.

---

## ⚠ Dominant architectural constraint (F-OBS-specific): ANONYMITY

The platform's core invariant is **vote anonymity** (votes carry no `user_id`; voter↔vote linkage is impossible by design). Therefore **every F-OBS mechanism must observe governance / composition / process / authority events — NEVER vote content or voter identity.** Any mechanism that observes at the vote or voter level **fails architectural fitness outright** (it would breach the constitutional anonymity invariant). This constraint dominates the evaluation below.

---

## Output 1 + 2 — Mechanism design space, classified (Rule 7)

Mechanism **classes** (not "families" — terminology guard):

| Class | Example mechanisms | What it observes |
|-------|--------------------|------------------|
| **Passive** | immutable audit log / append-only record; periodic manual review; public provenance register | governance events, recorded for later inspection |
| **Continuous** | live status monitoring; dashboards/metrics; real-time alerts | current governance state |
| **Distributed** | regional reporting; member/observer reporting; multi-observer corroboration | governance events, from many independent vantage points |
| **Statistical** | composition-drift detection; anomaly/trend detection over cycles | slow change (PAN / PCS / PEE) |

---

## Output 3 — Evaluation matrix (functional + architectural fitness, Rule 8)

| Class | Functional | Constitutional/anonymity | GRP impact | Concentration risk | Notes |
|-------|-----------|--------------------------|-----------|--------------------|-------|
| **Passive** (audit baseline) | ✓ baseline record | ✓ if scoped to governance events | **strengthens** (makes recursion observable; immutable evidence feeds F-REV) | low | cheap, durable; slow alone |
| **Continuous** | ✓ real-time | ✓ (governance state only) | strengthens | **medium** (a single live monitor can become a vantage hub) | higher cost/complexity for a volunteer org |
| **Distributed** | ✓ | ✓ (anonymity-safe; governance events) | strengthens | **low / anti-concentration** | fits diaspora structure; many independent observers |
| **Statistical** | ✓ for *drift* | ✓ (operates on governance composition, not votes) | **strengthens** (detects the slow erosion the program most fears) | low | needs thresholds (open question) |

**Architectural-fitness highlight:** F-OBS sits on the **Observation→Challenge edge** — observability is precisely what makes GRPs *"surrounded and observable, not closed."* So well-scoped F-OBS mechanisms **strengthen** the architecture's central principle. The one way F-OBS can *damage* the architecture is by observing at the vote/voter level (anonymity breach) or by concentrating observation in a single vantage (mini-hub) — both are disqualifiers, not trade-offs.

---

## Output 4 — Recommendation (Rule 9: no single class dominates → layered composite)

**No single mechanism class dominates** — observability is inherently layered. The recommended composite:

- **Passive immutable audit baseline** — adopt for *all* 7 capabilities (durable governance-event record; the evidence substrate F-REV draws on).
- **Distributed reporting** — adopt where multiple vantages add anti-concentration value (transparency, jurisdiction).
- **Statistical drift/anomaly detection** — adopt for the *drift* capabilities (erosion/composition/challenge-suppression), the program's highest-value detection need.
- **Continuous monitoring** — *conditional / optional*; adopt only where real-time status genuinely matters (finality status) and the concentration risk is managed.

This is an honest "no dominant single mechanism; principled layered combination" outcome — not a forced pick.

---

## Output 5 — Rejected alternatives (retained, with reasons — Rule 6)

| Rejected | Reason |
|----------|--------|
| **Vote-level / voter-level monitoring** | Breaches the anonymity invariant — architectural FAIL, not a trade-off. |
| **Single central sole-observer** | Creates an observation hub → concentration; weakens the anti-capture intent; a captured observer blinds everyone. |
| **Manual periodic review as the *sole* mechanism** | Too slow to catch slow drift (PAN/PCS/PEE) — acceptable only as one layer, never alone. |
| **Self-observation (a body is its own sole observer)** | Re-creates a GRP without surrounding it; observation must include vantage points the observed body cannot control. |

---

## Output 6 — Open research questions (NOT resolved)

- **OQ-P2-01-01:** Statistical drift **thresholds** — what change-rate constitutes "drift"? (calibration; carries into Strategic DDD.)
- **OQ-P2-01-02 (cross-family):** *Who acts* on an observability signal? Detection is inert without **F-REV standing** (GC-S4-01/02) — confirms the Observation→Challenge edge; resolve when F-REV mechanisms are explored.
- **OQ-P2-01-03:** Precise boundary of "governance-event observability" vs anonymity — define the exact line a mechanism may not cross. (Critical; likely a Strategic-DDD invariant.)

---

## Output 7 — Per-capability mapping (explore once, instantiate per capability)

| Capability | Passive | Continuous | Distributed | Statistical |
|------------|:--:|:--:|:--:|:--:|
| GC-S1-03 Impact Assessment | ✓ | | ✓ | |
| GC-S1-07 Erosion Detection | ✓ | | | ✓ |
| GC-S2-05 Transparency | ✓ | | ✓ | |
| GC-S2-06 Drift Detection | ✓ | | | ✓ |
| GC-S3-04 Jurisdiction Observability | ✓ | | ✓ | |
| GC-S4-07 Challenge Observability | ✓ | | | ✓ (PCS trend) |
| GC-S5-04 Finality Observability | ✓ | ✓ (status) | | |

All 7 share the **Passive audit baseline**; drift-oriented capabilities add **Statistical**; transparency/jurisdiction add **Distributed**; finality adds **Continuous** status. Reuse confirmed.

---

## Completion check (F-OBS, per P2-00)

- Design space explored + classified ✓
- Comparative evaluation (functional + architectural) ✓
- Recommendation (layered composite) / no-single-dominant stated ✓
- Rationale documented ✓
- Rejected alternatives retained ✓
- Open questions recorded ✓

**F-OBS Pass 2: COMPLETE.**

---

## Deliverable & next

```
F-OBS mechanism design space — explored, classified, evaluated
  Classes:        Passive / Continuous / Distributed / Statistical
  Recommended:    layered composite (Passive baseline + Distributed + Statistical; Continuous conditional)
  Hard constraint: anonymity — observe governance events, NEVER votes/voters
  Architectural:  F-OBS strengthens GRP handling (Observation→Challenge edge)
  Rejected (retained): vote-level monitoring; sole central observer; manual-only; self-observation
  Open Qs:        thresholds; who-acts (F-REV coupling); anonymity boundary
Status: governance mechanisms only. Strategic DDD GATED.
```

**Next:** Pass 2 continues with **F-AUTH** (Authority Composition & Distribution), per the P2-00 family order. F-OBS's OQ-P2-01-02 (who acts on signals) will close when **F-REV** is reached (the hub, last).

---

*Round 38C-P2-01 — F-OBS Observability & Detection Mechanisms — ISSUED*
*Layered composite recommended; anonymity is the dominant architectural constraint; F-OBS strengthens GRP handling*
*Next: F-AUTH. Strategic DDD GATED.*
