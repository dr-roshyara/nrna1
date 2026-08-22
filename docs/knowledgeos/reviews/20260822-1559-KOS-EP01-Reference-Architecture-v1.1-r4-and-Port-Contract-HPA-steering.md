# KnowledgeOS — SNF research pause · architectural refinement · Expression↔Meaning Port Contract — HPA steering (r4 authority)

> **Source:** Human Principal Architect (HPA), 2026-08-22 — recorded **verbatim-in-substance**. The HPA's message is the authoritative act; this instrument is the chain's record of it.
> **Position:** after the **r3 CONSOLIDATION** of Reference Architecture v1.1 (commit `ac9234cb`, the r3 change set applied). The HPA now steers the **next phase**: stop expanding SNF research, refine the architecture around the semantic boundary, and open the **Expression↔Meaning Port Contract** as the first Logical-Architecture deliverable.
> **One sentence (HPA):** *"We should now refine KnowledgeOS architecture around the semantic boundary, not refine the SNF formula itself."*
> **Status:** ✅ **HPA STEERING RECORDED — r4 change authority granted · Expression↔Meaning Port Contract OPENED · SNF research PAUSED (v0.4 gated behind the boundary).** Register **25+4 unchanged** · Constitution **FROZEN** · research **CLOSED** · P4 gate **unchanged**.

---

## 0 · The steering

> *"I would stop expanding SNF research for now and refine the architecture."*

This is not a change to the architecture's content; it is a **direction for the next work**: the semantic-boundary refinement the SNF measurement work surfaced is to be **carried into the architecture**, and the architecture is to be refined around it — not the SNF formula.

---

## 1 · The evidence boundary reached (HPA, in substance)

```
Research → SNF hypothesis → Mathematical measurement framework → v0.1 → v0.2 → v0.3
        gold-relation simulation → ARCHITECTURAL DECISION
```

> *"The simulations have done their job: they have shown us what the architecture must protect. They have not yet shown us which SNF algorithm is correct. That distinction is exactly the kind of discipline KnowledgeOS itself is supposed to enforce."*

The SNF measurement chain ends in an **architectural decision** — the boundary the architecture must protect — **not** in a validated algorithm.

---

## 2 · Strong evidence — carried into the architecture

| # | Established | Architectural consequence |
|---|---|---|
| 1 | SNF is a **candidate semantic mechanism**, not a kernel primitive | stays at the mechanism/representation altitude (§16); never core |
| 2 | **Canonicalization cannot determine identity by itself** | identity is assigned, never derived (INV-KOS-IDENTITY-001) |
| 3 | **Semantic convergence is useful to measure** | measurement vocabulary exists at the port (candidate-side) |
| 4 | **Non-collapse is a safety property** | invariance without non-collapse is a lossy canonicalization; both directions measured |
| 5 | **Unknown / abstention must be preserved** | mechanism abstention → UNKNOWN, never ABSENT / FALSE (C-5) |
| 6 | **Structural similarity ≠ semantic equivalence** | similarity never becomes identity |
| 7 | **Bayesian probability can express interpretation uncertainty** | candidate-side interpretation metadata, never aggregate Confidence (R-1) |
| 8 | **SNF equality must NOT be promoted to truth or identity equality** | a candidate-for-same-admitted-meaning observation, nothing more (C-1) |
| 9 | **A composite SNF score should not be used yet; collision rate = critical failure metric** | collisions reported as evidence, never as admission |

## 3 · Not established — not carried as evidence

The correct SNF formula · a universally valid semantic distance · URDNA2015 appropriateness · that Pāṇinian/Sanskrit normalization yields semantic invariance · that SNF equality implies semantic equivalence · thresholds · a mathematically justified composite score. **None of these is treated as architecture.**

---

## 4 · The commission — five steps (HPA, in substance)

1. **Refine Reference Architecture v1.1. Do not redesign it.** The Semantic Compiler becomes a **bounded mechanism**. The key boundary: *"The semantic compiler may propose a meaning representation; the KnowledgeOS kernel determines what epistemic state, if any, that representation can participate in."*
2. **Put SNF behind an explicit Semantic Interpretation Port** — pipeline:
   ```
   Expression → Semantic Interpretation Port → Candidate Meaning
     [SNF · interpretation probabilities · uncertainty · provenance · transformation evidence]
     → KnowledgeOS → Constitutional admissibility → Knowledge State
   ```
   *"The port, not SNF, becomes architectural."*
3. **Add the negative direction explicitly** — six prohibitions, as **architectural prohibitions / invariant interpretations**, not new philosophical principles:
   `Representation→Identity · Similarity→Equality · Probability→Truth · Canonicalization→Authority · Low entropy→Certainty · SNF equality→Knowledge identity`.
4. **Then move to Logical Architecture.** The first deliverable should be the **Expression↔Meaning Port Contract**, answering 8 questions (see §7).
5. **Return to SNF research only after the boundary exists** — KOS-SNF-ME **v0.4** (compare SNF-A/B/C, an LLM semantic parser, and a human reference; SNF becomes **replaceable research**).

## 5 · The six negative-direction prohibitions (Step 3)

Each is an **interpretation of an existing invariant**, not a new law — see the r4 annotations in Reference Architecture v1.1 (§15 non-collapse table · §17 rejected set · §19 Non-collapse gate).

| Prohibition | Invariant it interprets |
|---|---|
| Representation → Identity | INV-KOS-IDENTITY-001 (Articles 1.2, 1.3) |
| Similarity → Equality | INV-KOS-IDENTITY-001 (Article 1.3) |
| Probability → Truth | INV-KOS-VERIFICATION-001 · INV-KOS-DIMENSION-001 (Articles 6, 2.3) |
| Canonicalization → Authority | INV-KOS-AUTHORITY-001 (Article 3) |
| Low entropy → Certainty | INV-KOS-DIMENSION-001 · INV-KOS-UNKNOWN-001 (Articles 2, 9) |
| SNF equality → Knowledge identity | INV-KOS-IDENTITY-001 (Articles 1.2, 1.3) |

**Status of each in the consolidated artifact:** Representation→Identity · Similarity→Equality · SNF equality→Knowledge identity are **already present** (⟨C-1⟩, §15, §17, §19). **Probability→Truth · Canonicalization→Authority · Low entropy→Certainty are NEW** — added by this steering act as **r4 annotations** (the three are interpretations of existing invariants; no article, no register row, no new invariant).

## 6 · The Semantic Interpretation Port (Step 2)

The HPA's *"Semantic Interpretation Port"* is the **same boundary** the architecture names the **Expression↔Meaning Port** (v1.1 §10 · §5.2 · DEF-2) — the port described from the mechanism's side. **The port, not SNF, is architectural.** The candidate payload vocabulary (SNF · interpretation probabilities · uncertainty · provenance · transformation evidence) is **port-contract vocabulary** — defined by the contract, never aggregate members (no member is added).

## 7 · The eight Expression↔Meaning Port Contract questions (Step 4)

1. What **enters** the port?
2. What does the semantic mechanism **return**?
3. How is **uncertainty** represented?
4. How is **UNKNOWN** represented?
5. What **evidence** accompanies an interpretation?
6. What may the **kernel trust**?
7. What may the kernel **never infer from SNF**?
8. How are **semantic collisions** reported?

Answered by the deliverable `docs/knowledgeos/architecture/20260822-1559-KOS-Expression-Meaning-Port-Contract.md`.

---

## 8 · Recommended sequence (HPA, in substance)

```
Refine RA v1.1 → Expression↔Meaning Port Contract → Logical Architecture
→ SNF v0.4 research (Bayesian calibration) → real corpus experiment
→ evidence-based decision (promote / modify / reject SNF)
```

Step 1 is **already executed** by the r3 consolidation; this act completes it with the **r4 annotations** (the three missing prohibitions) and produces the **Port Contract** (Step 4). Steps beyond — SNF v0.4, the real corpus experiment, the promote/modify/reject decision — are **not authorized here**; they are gated behind the boundary this contract establishes.

---

## 9 · Chain state at this act

```
Research                                   CLOSED
Constitution v1.0                          FROZEN
Reference Architecture v1.1 r3             CONSOLIDATED (commit ac9234cb)
  ⟶ r4 annotations (this act): the six negative-direction prohibitions
Expression↔Meaning Port Contract           ⟵ OPENED by this act (first Logical-Architecture deliverable)
Logical Architecture                       OPENED for the Port Contract
SNF research                               PAUSED — v0.4 gated behind the boundary
KOS-SCB v0.2 experiment (OQ-4)             NOT AUTHORIZED (unchanged)
KOS-SNF-ME v0.4                            NOT AUTHORIZED (gated)
OQ-1 … OQ-5                                recorded; contract takes a position on OQ-1/2/3 at contract altitude
```

## 10 · What this act does NOT do

It does **not** redesign the Reference Architecture (Step 1 says so) · does **not** promote the Semantic Compiler or SNF · does **not** open implementation (no APIs, classes, schemas, storage — DEF-5) · does **not** decide SNF as the port encoding (DEF-4) · does **not** authorize the v0.2 or v0.4 experiments · does **not** add a context, aggregate, member, event, or invariant · does **not** change the register (**25+4 unchanged**) · does **not** touch the Constitution (**FROZEN**).

---

## Traceability

- **Act:** HPA steering, 2026-08-22 — *"I would stop expanding SNF research for now and refine the architecture"*; the evidence boundary, nine strong-evidence items, seven not-established items, Steps 1–5, the six negative-direction prohibitions, the Semantic Interpretation Port pipeline, the eight Port-Contract questions, and the recommended sequence recorded verbatim-in-substance above.
- **Objects:** Reference Architecture v1.1 **r3 CONSOLIDATED** (commit `ac9234cb` — r3 change set applied) · the SNF measurement chain (SNF hypothesis → measurement framework → v0.1 → v0.2 → v0.3 gold-relation simulation → architectural decision) · the Expression↔Meaning Port Contract (named ⟨A-2⟩ in r3 §10 · DEF-2, now authored under this act).
- **Delivered under this act:** (1) **r4 annotations** to Reference Architecture v1.1 — the six negative-direction prohibitions, three of them added (Probability→Truth · Canonicalization→Authority · Low entropy→Certainty) as invariant interpretations; (2) the **Expression↔Meaning Port Contract** — first Logical-Architecture deliverable, answering the eight questions.
- **Status:** ✅ **HPA STEERING RECORDED.** r4 change authority granted (the six prohibitions) · Expression↔Meaning Port Contract **OPENED** · SNF research **PAUSED** · KOS-SNF-ME v0.4 **gated, not authorized** · register **25+4 unchanged** · Constitution **FROZEN** · research **CLOSED** · P4 gate **unchanged**.
