# Round 39-D6 — Methodology Decision Records (ADR-M)

**Program:** NRNA DDD Trustworthiness Research Program
**Workstream:** Round 39 (Methodology Stabilization) — **D6** (closes audit finding F-3)
**Status:** 🔒 NORMATIVE — referenced by the Controlled Working Specification (R39-01). The **constitutional history of the methodology.**
**Rule:** Every methodology evolution **SHALL** have an ADR-M. **No silent methodology changes.** Governance discoveries (constitutional/capability) **MUST NOT** appear here. No DDD concepts here.
**Date:** 2026-06-25

> Scope: methodology evolution only. Each ADR carries: Status · Date · Version · Classification · Stability · Context · Problem · Alternatives · Evidence · Decision · Consequences · Affected Artifacts · Dependencies · Review Trigger · Supersedes/Superseded-By · Traceability.
> **Classification ∈** {Methodology Rule · Process Rule · Validation Rule · Evidence Rule · Version-Governance Rule}. **Stability ∈** {Experimental · Working · Controlled · Stable · General}.

### Documentation status (read first)

- **Retrospectively documented.** These ADRs were authored together on **2026-06-25** (Round 39 D6), reconstructing decisions made earlier from the **actual research artifacts** that produced them. The single date is the *documentation* date; each ADR's **trigger event** (`introduced F-AUTH`, `F-PROC`, `Round 39 D1/D5`) records *when the decision was actually taken*. These are **not** contemporaneous records and must not be read as such.
- **No invented history.** Every claim traces to a real artifact (P2-00/02/02H/02I/03/17/18/19/SYN-01, GLOSSARY-01, Round39-01/02, F-AUTH, F-PROC). No observation IDs, simulation events, dates, scripts, or infrastructure are invented; where automation is hypothetical it is labelled *candidate*.
- **Evidence hierarchy (every ADR cites in this order).** (1) Our research evidence (observations / hostile tests / family docs) — highest authority; (2) methodology validation (P2-SYN-01); (3) integrity validator (Round39-02); (4) literature — *supporting context only, never primary justification*; (5) architectural consequence. Literature **informs, never dictates** (see ADR-M-011).

---

## ADR-M-001 — Composite is the Evaluation Unit
- **Status/Date/Version:** Accepted · 2026-06-25 · introduced F-AUTH · **Classification:** Methodology Rule · **Stability:** Working *(narrowed by ADR-M-009)*
- **Context:** F-AUTH mechanism evaluation. **Problem:** evaluating mechanisms in isolation missed how appointment safeguards combine.
- **Alternatives:** (a) evaluate single mechanisms — *rejected:* missed interaction; (b) rank mechanisms only — *rejected:* no composite quality.
- **Evidence:** P2-02H-02 (F-AUTH); measurable emergence (P2-02I).
- **Decision:** the **composite** (a selection across dimensions) is the unit of design where the target property is emergent.
- **Consequences:** evaluation template targets composites; enables M-03/M-04. **Risk:** over-applying to additive families (realized → ADR-M-009).
- **Affected:** P2-00, P2-02, P2-02I. **Dependencies:** F-THR, F-REV. **Review trigger:** a family where one mechanism dominates (→ M-07).
- **Supersedes/-ed by:** narrowed by ADR-M-009. **Traceability:** obs M-01; preds M-02/03/04; validator 7.

## ADR-M-002 — Orthogonal Dimensions (Class → Group → Mechanism)
- Accepted · 2026-06-25 · F-AUTH · **Methodology Rule** · **Controlled**
- **Context/Problem:** the original 6 "classes" mixed dimension-types (who/when/quality), causing category errors.
- **Alternatives:** keep flat classes — *rejected:* invalid head-to-head comparisons.
- **Evidence:** F-AUTH category errors; orthogonality tests (P2-02I).
- **Decision:** mechanisms are **coordinate vectors** across orthogonal dimensions; taxonomy **Class → Group → Mechanism** ("group" not "family").
- **Consequences:** composites become coordinate selections; dimensions validated *per family* (not universal). **Risk:** a family needing a new dimension (P-D6).
- **Affected:** P2-00, P2-02I, GLOSSARY-01. **Dependencies:** all families. **Review trigger:** unclassified residue in any family → candidate new dimension.
- **Traceability:** rule R-01 (=M-05); extension point "new dimension"; validator 1.

## ADR-M-003 — Prediction Register
- Accepted · 2026-06-25 · post-F-AUTH · **Validation Rule** · **Controlled**
- **Context/Problem:** observations were explained after the fact (hindsight risk).
- **Alternatives:** retrospective synthesis only — *rejected:* not falsifiable.
- **Evidence:** P2-SYN-01 (need for prospective prediction).
- **Decision:** every methodology observation is a register entry: Observation · Prediction · Falsifier · Evidence (+ strength/level/independence/confidence).
- **Consequences:** methodology becomes predictive; updated per family. **Affected:** P2-18. **Dependencies:** every family. **Review trigger:** any prediction Refuted.
- **Traceability:** all M-/P- predictions; validator 6.

## ADR-M-004 — Prediction Lock Rule (No Retroactive Prediction)
- Accepted · 2026-06-25 · replication-rigor · **Process Rule** · **Controlled**
- **Context/Problem:** even a register can be edited mid-discovery, re-introducing hindsight bias.
- **Alternatives:** document-but-editable — *rejected:* appearance of goal-moving.
- **Evidence:** P2-19 (reliability threat: editable register reintroduces hindsight bias); verified clean in F-PROC (locked → unlocked, no mid-cycle edit). *Supporting context: aligns with pre-registration practice — not the primary justification.*
- **Decision:** register **SHALL** be locked before discovery, unlocked only after; statuses **Supported/Narrowed/Refuted/Inconclusive**. Predictions **MUST NOT** be edited during a locked cycle.
- **Consequences:** F-PROC ran locked (verified). **Affected:** P2-18. **Dependencies:** F-THR, F-REV. **Review trigger:** any lock breach (Critical).
- **Traceability:** INV-2; validator 6.

## ADR-M-005 — Search → Candidate → Design Spaces (+ categorized exclusions)
- Accepted · 2026-06-25 · F-AUTH exclusions · **Evidence Rule** · **Controlled**
- **Context/Problem:** "why wasn't X considered?" had no auditable answer; two-space model too coarse.
- **Alternatives:** single design space — *rejected:* lost provenance.
- **Evidence:** F-AUTH exclusions (external-guarantor, etc.).
- **Decision:** three spaces (Search/Candidate/Design); excluded mechanisms retained with **categorized** reason (Constitutional/Architectural/Functional/Contextual).
- **Consequences:** audit-proof exclusions. **Affected:** P2-17, GLOSSARY-01. **Dependencies:** all families. **Review trigger:** a recurring exclusion category suggesting a new filter.
- **Traceability:** evidence model; validator 5.

## ADR-M-006 — Transferability Axis (Evidence ≠ Confidence ≠ Transferability)
- Accepted · 2026-06-25 · F-AUTH · **Evidence Rule** · **Controlled** · *(first evidence-driven amendment)*
- **Context/Problem:** state-bound mechanisms scored high on evidence/confidence but don't transfer to a voluntary association.
- **Alternatives:** two axes only — *rejected:* hid the state-vs-association gap.
- **Evidence:** F-AUTH (supermajority assumes reachable threshold; external-guarantor).
- **Decision:** record **three independent axes** — evidence strength, confidence (architectural fit), transferability (org-type fit).
- **Consequences:** prevents literature-favoured-but-mis-fit selection. **Affected:** P2-17, P2-02. **Dependencies:** F-THR, F-REV. **Review trigger:** a high-transferability/low-evidence original mechanism outperforming literature.
- **Traceability:** evidence model; validator 5.

## ADR-M-007 — Controlled Working Specification (consolidation + versioning + RFC-2119 + invariants + extension points)
- Accepted · 2026-06-25 · Round 39 · **Version-Governance Rule** · **Controlled**
- **Context/Problem:** the methodology was scattered across P2-00/17/18/19/SYN-01; risk of drift; "v1.0" over-claimed stability.
- **Alternatives:** (a) call it v1.0 — *rejected:* contradicts L2+ maturity; (b) leave scattered — *rejected:* not executable/governed.
- **Evidence:** maturity model L2+; integrity audit need.
- **Decision:** publish **Methodology Specification v0.9 → v0.9.1** (Controlled Working Spec): consolidate; **RFC-2119 (incl. MUST NOT)**; invariants INV-1..5; extension points (with frequency); change only via ADR-M + version bump; **promote to v1.0 only after F-REV**.
- **Consequences:** methodology is a governed artifact. **Affected:** R39-01, all P2 docs (as sources). **Dependencies:** all future work. **Review trigger:** F-REV completion → v1.0 decision.
- **Traceability:** INV-1..5; extension points; validators 8/10.

## ADR-M-008 — Methodology Integrity Validator (severity + evidence-dependency + boundary contract)
- Accepted · 2026-06-25 · Round 39 D5 · **Validation Rule** · **Controlled**
- **Context/Problem:** needed to verify internal consistency before F-THR; a "compiler" metaphor was too deterministic for a methodology containing interpretive decisions.
- **Alternatives:** (a) "compiler" with hard accept/reject — *rejected:* mechanism classification is judgment, not syntax; (b) informal review — *rejected:* not reproducible.
- **Evidence:** D5 run (Round39-02): found+fixed F-1/F-2.
- **Decision:** a **Methodology Integrity Validator** — 10 typed validators, each Pass/Warning/Failure with **severity** (Critical/Major/Minor/Informational; only Critical blocks); includes an **Evidence-Dependency** validator and a **research-object boundary contract**.
- **Consequences:** integrity checked before each family. **Affected:** Round39-02. **Dependencies:** every family. **Review trigger:** any Critical finding.
- **Traceability:** validators 1–10; INV-4 (boundary).

## ADR-M-009 — Composite-Conditional Refinement (M-07; narrows M-01)
- Accepted · 2026-06-25 · F-PROC · **Methodology Rule** · **Experimental** *(Confidence Low, n=2)*
- **Context/Problem:** F-PROC (process integrity) was **additive**, not an emergent composite — M-01 (composite is *the* unit) was too broad.
- **Alternatives:** (a) force a hidden F-PROC composite — *rejected:* evidence-forcing; (b) refute M-01 entirely — *rejected:* F-AUTH still emergent.
- **Evidence:** F-PROC Outcome B (P2-03); pre-registered narrowing disposition (P2-SYN-01 §2).
- **Decision:** composite evaluation is **conditional on cross-dimensional interaction** (Emergent/Mixed families); **additive** families are evaluated mechanism-by-mechanism. Candidate **typology** (not taxonomy): interaction profiles {Emergent, Additive, Mixed} — discrete-vs-continuum undetermined.
- **Consequences:** more precise than M-01. **Risk:** typology may be a continuum. **Affected:** P2-03, P2-18, P2-SYN-01, R39-01 §12. **Dependencies:** F-THR (P-PROFILE). **Review trigger:** F-THR Mixed profile → continuum model.
- **Supersedes:** narrows ADR-M-001. **Traceability:** obs M-07; pred P-PROFILE; validator 7.

## ADR-M-010 — Hostile-Replication Validation + Observation Lifecycle + Maturity Model
- Accepted · 2026-06-25 · P2-SYN-01 · **Validation Rule** · **Controlled**
- **Context/Problem:** risk of the methodology becoming a confirmation exercise; no way to track premature generalization.
- **Alternatives:** confirmation-style validation — *rejected:* unscientific.
- **Evidence:** F-PROC (hostile test narrowed M-01, did not confirm).
- **Decision:** each new family is a **hostile replication** (attempt to break provisional observations); **observation lifecycle** Observed→Replicated→Reinforced→Working-Method-Principle→General-Principle (General requires *beyond-NRNA* validation); **maturity model L0–L4** (current **L2+**); **saturation criterion** (no protocol change across 2 consecutive families; *saturation ≠ truth*).
- **Consequences:** evidence-graded confidence; bounded methodology. **Affected:** P2-SYN-01, P2-19. **Dependencies:** F-THR, F-REV. **Review trigger:** saturation reached, or L3 promotion.
- **Traceability:** lifecycle; maturity; convergence matrix; validators 2/10.

## ADR-M-011 — Literature Protocol (sketch-before-literature; informs, never dictates)
- Accepted · 2026-06-25 · P2-17 · **Process Rule** · **Controlled**
- **Context/Problem:** literature could anchor discovery or reopen settled constitutional questions.
- **Alternatives:** literature-first — *rejected:* anchoring + scope creep.
- **Evidence:** F-AUTH convergence (sketch then literature mostly confirmed it — P2-02H-01).
- **Decision:** **sketch before literature** (R-02); architecture questions literature; categories A(closed)/B/C/D + reading priority; **literature broadens the design space, never reopens the constitutional architecture**.
- **Consequences:** anti-anchoring; bounded reading. **Affected:** P2-17. **Dependencies:** all families. **Review trigger:** a family where literature diverges sharply from the sketch.
- **Traceability:** rule R-02 (=M-06); validator 5.

---

## Cross-reference matrix

| ADR-M | Spec § | Prediction Register | Validator | Affected docs |
|-------|--------|---------------------|-----------|---------------|
| 001 | §12 | M-01→M-07 | 7 | P2-00/02/02I |
| 002 | §3,§5 | R-01 | 1 | P2-00/02I, GLOSSARY |
| 003 | §8 | all M-/P- | 6 | P2-18 |
| 004 | §8 | (lock) | 6 | P2-18 |
| 005 | §11 | — | 5 | P2-17, GLOSSARY |
| 006 | §11 | — | 5 | P2-17, P2-02 |
| 007 | §0,§14,§15,§16 | — | 8,10 | R39-01, all P2 |
| 008 | §13 | — | 1–10 | R39-02 |
| 009 | §12 | M-07, P-PROFILE | 7 | P2-03, P2-18, SYN-01 |
| 010 | §4,§7,§10 | (statuses) | 2,10 | SYN-01, P2-19 |
| 011 | §6 | — | 5 | P2-17 |

## Methodology change timeline

```
F-AUTH         → ADR-M-001, 002, 005, 006   (composite, dimensions, spaces, transferability)
post-F-AUTH    → ADR-M-003, 010, 011        (register, validation discipline, literature)
replication    → ADR-M-004                  (prediction lock)
Round 39 (D1)  → ADR-M-007                  (controlled working spec v0.9 → v0.9.1)
Round 39 (D5)  → ADR-M-008                  (integrity validator)
F-PROC         → ADR-M-009                  (M-07 composite-conditional narrowing)
```

## Outstanding review triggers (watch-list)

- **F-THR Mixed profile** → revisit ADR-M-009 (typology → continuum).
- **F-REV unclassified residue** → revisit ADR-M-002 (P-D6 Accountability dimension).
- **Any prediction Refuted** → revisit ADR-M-003/009.
- **Any lock breach** → Critical; revisit ADR-M-004.
- **F-REV completion** → ADR-M-007 v1.0 promotion decision.
- **Independent replication contradicts M-07** → revisit ADR-M-009.

## Open ADR backlog

- **ADR-M-012 (pending):** standalone evidence/provenance model ADR *if* §11 provenance grows beyond ADR-M-005/006 (currently folded). Not yet warranted.
- No other undocumented methodology evolution identified.

## Quality gates (D6 completion check)

✓ Every methodology change has an ADR (001–011) · ✓ no undocumented evolution found (backlog notes the one borderline) · ✓ every ADR cites evidence (research-evidence-first hierarchy) · ✓ every ADR lists rejected alternatives · ✓ every ADR has a review trigger · ✓ explicit consequences · ✓ **no governance discoveries inside ADR-M** · ✓ **no DDD concepts** · ✓ **no invented history** (real artifacts only; retrospective status declared) · ✓ **literature is supporting context, never primary justification**.

---

*Round 39-D6 — Methodology Decision Records (ADR-M) — ISSUED (closes audit finding F-3)*
*11 ADRs; cross-reference matrix + timeline + review-triggers + backlog. Methodology is now fully traceable without chat history. Strategic DDD GATED.*
