# Round 40-08 — Governance Evidence Graph

**Program:** NRNA DDD Trustworthiness Research Program
**Type:** Evidence **traceability** for governance knowledge (the bridge: discovery → ontology) · **Under MB-39.1 (frozen)**
**Status:** 🕸️ LIVING GRAPH — answers *"why do we believe any of this?"* for every governance claim. Updated by F-REV.
**Date:** 2026-06-25

> **Why this exists.** The program now has Research Questions, a Semantic Inventory, Decision Records, and family architectures — but no single artifact tracing **constitutional property → family → observation → evidence → contradiction → research question → future validation.** Without that chain, the eventual Governance Ontology would be just another synthesis document. This graph makes every governance belief **auditable to its evidence**, and every open edge **explicit**.
> **Discipline.** Traceability only. No new claims, no definitions, no theory. Confidence uses the methodology's evidence model (evidence · confidence · transferability · population). MB-39.1 frozen · DDD gated.

---

## 1. Node & edge taxonomy

**Node types:** `Constitutional-Property` · `Family` · `Capability` · `Observation` · `Evidence` · `Semantic-Conflict` · `Contradiction` · `Research-Question` · `Future-Validation`.

**Edge types:** `grounds` · `produces` · `supports` · `dependsOn` · `contradicts` · `raises` (a question) · `pendingValidation`.

**Rule:** every `Family`/`Capability`/`Semantic-Conflict` node must trace **upward** to a `Constitutional-Property` and **downward** to either confirming `Evidence` or an open `Research-Question`. A node with neither is an **orphan belief** (flagged).

---

## 2. Justification chains (why we believe each claim)

### Chain J-1 — Independence (the Critical chain)
```
S-3 (appointment insulation)  ──grounds──►  F-AUTH: institutional independence
        │                                      └─Observation: appointment safeguards EMERGENT (composite)
        │                                          └─Evidence: P2-02H-02   [conf Medium · NRNA · 1 family]
        ├──grounds──►  F-THR: operational/functional independence
        │                  └─Evidence: 40-02 domain pass  [conf Medium · NRNA]
        └──grounds──►  F-REV: decisional independence  [PREDICTED — no evidence yet]
                           │
        all three ──raises──► SEMANTIC-CONFLICT SI-01 ("independence" = 3 meanings)
                           └──raises──► RQ-SEM-01 🔴 ──pendingValidation──► F-REV
                                          └─ BLOCKS: Strategic DDD ubiquitous language
```

### Chain J-2 — Legitimacy dependency (the apex chain)
```
Legitimacy (emergent)  ──dependsOn──►  Review (F-REV)  ──dependsOn──►  Evidence
        │                                                                  │
        │                                                          dependsOn▼
        │                                                          Process Integrity (F-PROC)
        │                                                                  │
        │                                                          dependsOn▼
        │                                                          Monitoring (F-THR/oversight)
        └─Observation: legitimacy used as apex everywhere   ──raises──► SI-02 + RQ-SEM-02
           Evidence: synthesis-level only  [conf LOW · undefined]  ──pendingValidation──► post-F-REV synthesis
```
*Reading: our belief in "legitimacy" currently rests on a synthesis-level observation with LOW confidence and an undefined term — the weakest justified node in the architecture. The chain shows exactly why (it bottoms out in an open semantic question, not in evidence).*

### Chain J-3 — Capture resistance & its bound
```
F-THR  ──Observation: family is MIXED  ──Evidence: 40-02 (RQ-1)  [conf Medium · NRNA · 1 family]
   │
   └─Claim: resilience bounded by weakest of {S-3 independence, F-REV capture-resistance}
        ├──dependsOn──► S-3  (Evidence: J-1)
        └──dependsOn──► F-REV capture-resistance  ──raises──► RQ-THR-01 (peer vs cross-cutting?)
                                                  ──pendingValidation──► F-REV
        Recursion: F-THR ↔ F-REV (GRP) ──raises──► RQ-REC-03 ──pendingValidation──► F-REV
```

### Chain J-4 — Founding-stage decay (the slow-failure chain)
```
Founding-stage (no track record)  ──grounds──►  F-4 institutional-decay risk
   └─Observation: dominant residual risk  ──Evidence: program-wide  [conf High · founding-stage orgs]
        └─Contradiction with event-driven detection ──raises──► RQ-FAIL-01 (silent erosion)
             └──pendingValidation──► discovery + modelling
```

### Chain J-5 — Sponsor capture (the coverage chain)
```
Sponsor authority (38C-14C)  ──Observation: unmodeled capture surface  [conf High · NRNA]
   └──raises──► RQ-ACT-01  ──pendingValidation──► family that owns sponsor relations (undiscovered)
```

---

## 3. Traceability matrix (claim → evidence → openness)

| Claim / node | Grounded in | Evidence | Confidence | Conflict/Contradiction | Open RQ | Validated by |
|--------------|-------------|----------|-----------|------------------------|---------|--------------|
| F-AUTH emergent | S-3 | P2-02H-02 | Medium | — | — | replicated (F-PROC) |
| F-PROC additive | S-4 | P2-03 | Medium | — | — | F-PROC |
| F-THR mixed | S-3/S-4/S-5 | 40-02 | Medium | — | RQ-THR-01 | F-REV |
| Independence (3 senses) | S-3 | J-1 | Medium/Low | **SI-01** 🔴 | RQ-SEM-01 | F-REV |
| Legitimacy (apex) | — | synthesis | **Low** | SI-02 | RQ-SEM-02 | post-F-REV |
| Bound {S-3, F-REV} | S-3 | 40-03/04 | Medium | — | RQ-THR-01 | F-REV |
| GRP-THR-REV recursion | Option B | 40-04 D5 | High | — | RQ-REC-03 | F-REV |
| Recursion-as-class | — | review | Low | — | RQ-REC-01/02/03 | F-REV |
| Institutional decay (F-4) | founding-stage | program | High | event-driven blindspot | RQ-FAIL-01 | modelling |
| Sponsor capture surface | sponsor authority | 38C-14C | High | — | RQ-ACT-01 | future family |

**Orphan-belief check:** every row traces up to a constitutional property *or* is explicitly an open RQ. The weakest justified node is **Legitimacy** (no constitutional grounding yet, Low confidence) — correctly the apex *emergent* property awaiting synthesis, not an orphan.

---

## 4. How this bridges discovery → ontology

- **Before F-REV:** the graph shows *what we believe and on what evidence* — and, critically, **which beliefs bottom out in open questions** (Independence, Legitimacy, recursion-as-class). Those are exactly the nodes the ontology must NOT freeze yet.
- **After F-REV:** each `pendingValidation` edge resolves to `supports` / `narrows` / `contradicts`, the semantic conflicts close, and the graph's *settled* sub-region becomes the evidence base for **Governance Ontology v1.0**. The ontology is then built **only from nodes with confirming evidence**, not from synthesis prose.
- **For DDD:** a candidate ubiquitous-language term is admissible only when its node is **evidence-backed, conflict-free, and constitutionally grounded.** Today, by this test, **none qualifies** — SI-01 alone disqualifies "independence," and "legitimacy" lacks grounding. The graph operationalizes the DDD gate.

---

## 5. Updated roadmap

```
40-05 Review → GDR → 40-06 Semantic Inventory → 40-07 Research Question Register
   → 40-08 Governance Evidence Graph (this)        [bridge]
   → F-REV  (resolves pendingValidation edges; tests RQ-EL-01 / RQ-THR-01 / RQ-REC-*)
   → Cross-Family Final Synthesis
   → Governance Ontology v1.0  (built only from evidence-backed graph nodes)
   → Methodology Governance Review  (DA-THR-01..06 → MB-39.2 if warranted)
   → Strategic DDD Readiness
```

---

*Round 40-08 — Governance Evidence Graph — ISSUED (living; traceability bridge).*
*5 justification chains + traceability matrix; every governance belief traced to evidence or an open RQ. Weakest node: Legitimacy (Low, ungrounded — awaiting synthesis). Operationalizes the DDD gate: no term is UL-admissible until evidence-backed, conflict-free, constitutionally grounded. MB-39.1 FROZEN · DDD GATED. Next: F-REV.*
