# Round 39-D6 — Methodology Decision Records (ADR-M)

**Program:** NRNA DDD Trustworthiness Research Program
**Workstream:** Round 39 (Methodology Stabilization) — **D6** (closes audit finding F-3)
**Status:** 🔒 NORMATIVE — referenced by the Controlled Working Specification (R39-01). The **constitutional history of the methodology.**
**Rule:** Every methodology evolution **SHALL** have an ADR-M. **No silent methodology changes.** Governance discoveries (constitutional/capability) **MUST NOT** appear here. No DDD concepts here.
**Date:** 2026-06-25
**Decision Authority (all ADRs unless noted):** Round 39 Methodology Governance (sponsor + ARB).

> Scope: methodology evolution only. Each ADR carries: ADR-Status · Date · Trigger · Class · Decision Authority · Context · Problem · Alternatives · Evidence · Decision · Normative Rule(s) · Consequences · Affected Artifacts · Dependencies · Review Trigger · Supersedes/Superseded-By · Traceability.

### Two distinct vocabularies (do not conflate)

These are **independent** axes. An ADR can be *Controlled* (the decision is governed and frozen) while the scientific observation it documents is only *Replicated* (not yet a general principle).

- **ADR status** (governance lifecycle of the *decision record*): **Draft · Accepted · Controlled · Superseded · Retired.**
- **Methodology maturity** (scientific standing of the underlying *observation*, recorded in the Evidence field, defined in P2-SYN-01 §6): **Observed · Replicated · Reinforced · Working Principle · General Principle.**
- **Class** (exactly one per ADR): **Methodology Rule · Process Rule · Validation Rule · Evidence Rule · Version-Governance Rule.**

### Documentation status (read first)

- **Retrospectively documented.** These ADRs were authored together on **2026-06-25** (Round 39 D6), reconstructing decisions made earlier from the **actual research artifacts** that produced them. The single date is the *documentation* date; each ADR's **Trigger** (`F-AUTH`, `F-PROC`, `Round 39 D1/D5`) records *when the decision was actually taken*. These are **not** contemporaneous records.
- **No invented history.** Every claim traces to a real artifact (P2-00/02/02H/02I/03/17/18/19/SYN-01, GLOSSARY-01, Round39-01/02, F-AUTH, F-PROC). No observation IDs, simulation events, dates, scripts, or infrastructure are invented; where automation is hypothetical it is labelled *candidate*.
- **Evidence hierarchy (every ADR cites in this order).** (1) Our research evidence (observations / hostile tests / family docs) — highest authority; (2) methodology validation (P2-SYN-01); (3) integrity validator (Round39-02); (4) literature — *supporting context only, never primary justification*; (5) architectural consequence.

---

## ADR-M-001 — Composite is the Evaluation Unit
- **ADR status:** Controlled *(narrowed by ADR-M-009)* · 2026-06-25 · **Trigger:** F-AUTH · **Class:** Methodology Rule
- **Context:** F-AUTH mechanism evaluation. **Problem:** evaluating mechanisms in isolation missed how appointment safeguards combine.
- **Alternatives:** (a) evaluate single mechanisms — *rejected:* missed interaction; (b) rank mechanisms only — *rejected:* no composite quality.
- **Evidence:** P2-02H-02 (F-AUTH); measurable emergence (P2-02I). *Methodology maturity: Replicated → then Narrowed by F-PROC.*
- **Decision:** the **composite** (a selection across dimensions) is the unit of design where the target property is emergent.
- **Normative rule:** where the target property is emergent, evaluation **SHALL** target the composite, not isolated mechanisms.
- **Consequences:** evaluation template targets composites; enables M-03/M-04. **Risk:** over-applying to additive families (realized → ADR-M-009).
- **Affected:** P2-00, P2-02, P2-02I. **Dependencies:** F-THR, F-REV. **Review trigger:** a family where one mechanism dominates (→ M-07).
- **Supersedes/-ed by:** narrowed by ADR-M-009. **Traceability:** obs M-01; preds M-02/03/04; validator 7.

## ADR-M-002 — Orthogonal Dimensions (Class → Group → Mechanism)
- **ADR status:** Controlled · 2026-06-25 · **Trigger:** F-AUTH · **Class:** Methodology Rule
- **Context/Problem:** the original 6 "classes" mixed dimension-types (who/when/quality), causing category errors.
- **Alternatives:** keep flat classes — *rejected:* invalid head-to-head comparisons.
- **Evidence:** F-AUTH category errors; orthogonality tests (P2-02I). *Methodology maturity: Replicated (F-AUTH + F-PROC).*
- **Decision:** mechanisms are **coordinate vectors** across orthogonal dimensions; taxonomy **Class → Group → Mechanism** ("group" not "family").
- **Normative rule:** mechanisms **SHALL** be classified as coordinates across orthogonal dimensions; dimensions are validated **per family**, not assumed universal.
- **Consequences:** composites become coordinate selections. **Risk:** a family needing a new dimension (P-D6).
- **Affected:** P2-00, P2-02I, GLOSSARY-01. **Dependencies:** all families. **Review trigger:** unclassified residue in any family → candidate new dimension.
- **Traceability:** rule R-01 (=M-05); extension point "new dimension"; validator 1.

## ADR-M-003 — Prediction Register
- **ADR status:** Controlled · 2026-06-25 · **Trigger:** post-F-AUTH · **Class:** Validation Rule
- **Context/Problem:** observations were explained after the fact (hindsight risk).
- **Alternatives:** retrospective synthesis only — *rejected:* not falsifiable.
- **Evidence:** P2-SYN-01 (need for prospective prediction); exercised in F-PROC.
- **Decision:** every methodology observation becomes a register entry: Observation · Prediction · Falsifier · Evidence (+ strength/level/independence/confidence).
- **Normative rule:** every methodology observation **SHALL** be recorded as a falsifiable prediction before the family that tests it.
- **Consequences:** methodology becomes predictive; updated per family. **Affected:** P2-18. **Dependencies:** every family. **Review trigger:** any prediction Refuted.
- **Traceability:** all M-/P- predictions; validator 6.

## ADR-M-004 — Prediction Lock Rule (No Retroactive Prediction)
- **ADR status:** Controlled · 2026-06-25 · **Trigger:** replication-rigor · **Class:** Process Rule
- **Context/Problem:** even a register can be edited mid-discovery, re-introducing hindsight bias.
- **Alternatives:** document-but-editable — *rejected:* appearance of goal-moving.
- **Evidence:** P2-19 (reliability threat: editable register reintroduces hindsight bias); verified clean in F-PROC (locked → unlocked, no mid-cycle edit). *Supporting context: aligns with pre-registration practice — not the primary justification.*
- **Decision:** the register is locked before discovery and unlocked only after, with statuses Supported/Narrowed/Refuted/Inconclusive.
- **Normative rule:** the register **SHALL** be locked before discovery; predictions **MUST NOT** be added, removed, reworded, or re-scored during a locked cycle.
- **Consequences:** F-PROC ran locked (verified). **Affected:** P2-18. **Dependencies:** F-THR, F-REV. **Review trigger:** any lock breach (Critical).
- **Traceability:** INV-2; validator 6.

## ADR-M-005 — Search → Candidate → Design Spaces (+ categorized exclusions)
- **ADR status:** Controlled · 2026-06-25 · **Trigger:** F-AUTH exclusions · **Class:** Evidence Rule
- **Context/Problem:** "why wasn't X considered?" had no auditable answer; the two-space model was too coarse.
- **Alternatives:** single design space — *rejected:* lost provenance.
- **Evidence:** F-AUTH exclusions (external-guarantor, etc.). *Methodology maturity: Observed (1 family) — Warning: thin (Round39-02 §5).*
- **Decision:** three spaces (Search/Candidate/Design); excluded mechanisms are retained with a **categorized** reason (Constitutional/Architectural/Functional/Contextual).
- **Normative rule:** every excluded mechanism **SHALL** be retained with a categorized exclusion reason; exclusions **MUST NOT** be silently dropped.
- **Consequences:** audit-proof exclusions. **Affected:** P2-17, GLOSSARY-01. **Dependencies:** all families. **Review trigger:** a recurring exclusion category suggesting a new filter.
- **Traceability:** evidence model; validator 5.

## ADR-M-006 — Transferability Axis (Evidence ≠ Confidence ≠ Transferability)
- **ADR status:** Controlled · 2026-06-25 · **Trigger:** F-AUTH · **Class:** Evidence Rule · *(first evidence-driven amendment)*
- **Context/Problem:** state-bound mechanisms scored high on evidence/confidence but don't transfer to a voluntary association.
- **Alternatives:** two axes only — *rejected:* hid the state-vs-association gap.
- **Evidence:** F-AUTH (supermajority assumes a reachable threshold; external-guarantor). *Methodology maturity: Observed (1 family) — Warning: thin.*
- **Decision:** record **three independent axes** — evidence strength, confidence (architectural fit), transferability (org-type fit).
- **Normative rule:** mechanism evaluation **SHALL** record all three axes independently; high evidence **MUST NOT** be read as high transferability.
- **Consequences:** prevents literature-favoured-but-mis-fit selection. **Affected:** P2-17, P2-02. **Dependencies:** F-THR, F-REV. **Review trigger:** a high-transferability/low-evidence original mechanism outperforming literature.
- **Traceability:** evidence model; validator 5.

## ADR-M-007 — Controlled Working Specification (consolidation + versioning + RFC-2119 + invariants + extension points)
- **ADR status:** Controlled · 2026-06-25 · **Trigger:** Round 39 D1 · **Class:** Version-Governance Rule
- **Context/Problem:** the methodology was scattered across P2-00/17/18/19/SYN-01; risk of drift; "v1.0" over-claimed stability.
- **Alternatives:** (a) call it v1.0 — *rejected:* contradicts L2+ maturity; (b) leave scattered — *rejected:* not executable/governed.
- **Evidence:** maturity model L2+ (P2-19); integrity-audit need (Round39-02).
- **Decision:** publish **Methodology Specification v0.9 → v0.9.1** (Controlled Working Spec): consolidate; RFC-2119 (incl. MUST NOT); invariants INV-1..5; extension points (with frequency).
- **Normative rule:** the Spec **SHALL** change only via an ADR-M + version bump (no silent edits); promotion to **v1.0 only after F-REV**.
- **Consequences:** the methodology is a governed artifact. **Affected:** R39-01, all P2 docs (as sources). **Dependencies:** all future work. **Review trigger:** F-REV completion → v1.0 decision.
- **Traceability:** INV-1..5; extension points; validators 8/10.

## ADR-M-008 — Methodology Integrity Validator (severity + evidence-dependency + boundary contract)
- **ADR status:** Controlled · 2026-06-25 · **Trigger:** Round 39 D5 · **Class:** Validation Rule
- **Context/Problem:** needed to verify internal consistency before F-THR; a "compiler" metaphor was too deterministic for a methodology containing interpretive decisions.
- **Alternatives:** (a) "compiler" with hard accept/reject — *rejected:* mechanism classification is judgment, not syntax; (b) informal review — *rejected:* not reproducible.
- **Evidence:** D5 run (Round39-02): found + fixed F-1/F-2.
- **Decision:** a **Methodology Integrity Validator** — 10 typed validators, each Pass/Warning/Failure with **severity** (Critical/Major/Minor/Informational); an Evidence-Dependency validator; a research-object boundary contract.
- **Normative rule:** integrity validation **SHALL** run before each family; only a **Critical** finding blocks F-THR.
- **Consequences:** integrity checked before each family. **Affected:** Round39-02. **Dependencies:** every family. **Review trigger:** any Critical finding.
- **Traceability:** validators 1–10; INV-4 (boundary).

## ADR-M-009 — Composite-Conditional Refinement (M-07; narrows M-01)
- **ADR status:** Accepted · 2026-06-25 · **Trigger:** F-PROC · **Class:** Methodology Rule
- *(Decision is firm; the underlying observation is still Experimental — see Evidence. ADR status ≠ observation maturity.)*
- **Context/Problem:** F-PROC (process integrity) was **additive**, not an emergent composite — M-01 (composite is *the* unit) was too broad.
- **Alternatives:** (a) force a hidden F-PROC composite — *rejected:* evidence-forcing; (b) refute M-01 entirely — *rejected:* F-AUTH was genuinely emergent.
- **Evidence:**
  - **Families:** F-AUTH (emergent), F-PROC (additive) — P2-03 Outcome B; pre-registered narrowing disposition (P2-SYN-01 §2).
  - **Methodology maturity:** Observed (M-07).
  - **Confidence:** Low (n=2).
  - **Transferability:** Unknown (discrete-vs-continuum undetermined).
- **Decision:** composite evaluation is **conditional on cross-dimensional interaction** (Emergent/Mixed families); **additive** families are evaluated mechanism-by-mechanism. Candidate **typology** (not taxonomy): interaction profiles {Emergent, Additive, Mixed}.
- **Normative rule:** evaluation mode **SHALL** be selected by the family's interaction profile; the composite unit **MUST NOT** be assumed for additive families.
- **Consequences:** more precise than M-01. **Risk:** the typology may be a continuum, not discrete classes. **Affected:** P2-03, P2-18, P2-SYN-01, R39-01 §12. **Dependencies:** F-THR (P-PROFILE). **Review trigger:** an F-THR Mixed profile → model topology as a continuum.
- **Supersedes:** narrows ADR-M-001. **Traceability:** obs M-07; pred P-PROFILE; validator 7.

## ADR-M-010 — Hostile-Replication Validation + Observation Lifecycle + Maturity Model
- **ADR status:** Controlled · 2026-06-25 · **Trigger:** P2-SYN-01 · **Class:** Validation Rule
- **Context/Problem:** risk of the methodology becoming a confirmation exercise; no way to track premature generalization.
- **Alternatives:** confirmation-style validation — *rejected:* unscientific.
- **Evidence:** F-PROC (hostile test **narrowed** M-01 rather than confirming it).
- **Decision:** each new family is a **hostile replication**; the **observation lifecycle** is Observed→Replicated→Reinforced→Working-Principle→General-Principle (General requires *beyond-NRNA* validation); a **maturity model L0–L4** (current **L2+**); a **saturation criterion** (no protocol change across 2 consecutive families; *saturation ≠ truth*).
- **Normative rule:** each new family **SHALL** be run as an attempt to break the provisional observations, not to confirm them.
- **Consequences:** evidence-graded confidence; bounded methodology. **Affected:** P2-SYN-01, P2-19. **Dependencies:** F-THR, F-REV. **Review trigger:** saturation reached, or an L3 promotion proposed.
- **Traceability:** lifecycle; maturity; convergence matrix; validators 2/10.

## ADR-M-011 — Literature Protocol (sketch-before-literature; informs, never dictates)
- **ADR status:** Controlled · 2026-06-25 · **Trigger:** P2-17 · **Class:** Process Rule
- **Context/Problem:** literature could anchor discovery or reopen settled constitutional questions.
- **Alternatives:** literature-first — *rejected:* anchoring + scope creep.
- **Evidence:** F-AUTH convergence (independent sketch, then literature mostly confirmed it — P2-02H-01).
- **Decision:** sketch before literature (R-02); architecture questions literature; categories A(closed)/B/C/D + reading priority.
- **Normative rule:** an independent sketch **SHALL** precede the literature harvest; literature broadens the design space but **MUST NOT** reopen the constitutional architecture.
- **Consequences:** anti-anchoring; bounded reading. **Affected:** P2-17. **Dependencies:** all families. **Review trigger:** a family where literature diverges sharply from the sketch.
- **Traceability:** rule R-02 (=M-06); validator 5.

---

## Methodology change process (BINDING)

The methodology now evolves **only through ADR governance**. **Family documents (F-AUTH, F-PROC, F-THR, F-REV) MUST NOT directly modify the methodology or the Specification.** A family may *discover* an anomaly and *propose* a change; it may not enact one.

```
Family discovers anomaly
        ↓
Family records the anomaly (in its own Pass-2 document — descriptive only)
        ↓
Prediction Register updated (post-unlock; per ADR-M-004)
        ↓
ADR proposal opened  (ADR status: Draft)
        ↓
Methodology Governance review  (Decision Authority: sponsor + ARB)
        ↓
ADR Accepted or Rejected   (rejected alternatives are retained, never deleted)
        ↓
If accepted → Specification version bump (per ADR-M-007)
        ↓
Next family executes under the new, frozen Specification
```

**Separation of concerns:** *research execution* (discoveries by the families) is kept distinct from *methodology governance* (controlled evolution of the process). The frozen Spec a family runs under does not change mid-family; F-THR anomalies become **Draft ADR proposals** to be reviewed *after* F-THR, before F-REV.

---

## Cross-reference matrix

| ADR-M | ADR status | Class | Spec § | Prediction Register | Validator | Affected docs |
|-------|-----------|-------|--------|---------------------|-----------|---------------|
| 001 | Controlled *(narrowed)* | Methodology | §12 | M-01→M-07 | 7 | P2-00/02/02I |
| 002 | Controlled | Methodology | §3,§5 | R-01 | 1 | P2-00/02I, GLOSSARY |
| 003 | Controlled | Validation | §8 | all M-/P- | 6 | P2-18 |
| 004 | Controlled | Process | §8 | (lock) | 6 | P2-18 |
| 005 | Controlled | Evidence | §11 | — | 5 | P2-17, GLOSSARY |
| 006 | Controlled | Evidence | §11 | — | 5 | P2-17, P2-02 |
| 007 | Controlled | Version-Governance | §0,§14,§15,§16 | — | 8,10 | R39-01, all P2 |
| 008 | Controlled | Validation | §13 | — | 1–10 | R39-02 |
| 009 | Accepted *(obs Experimental)* | Methodology | §12 | M-07, P-PROFILE | 7 | P2-03, P2-18, SYN-01 |
| 010 | Controlled | Validation | §4,§7,§10 | (statuses) | 2,10 | SYN-01, P2-19 |
| 011 | Controlled | Process | §6 | — | 5 | P2-17 |

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

✓ Every methodology change has an ADR (001–011) · ✓ no undocumented evolution found · ✓ every ADR cites evidence (research-evidence-first hierarchy) · ✓ every ADR lists rejected alternatives · ✓ every ADR has a review trigger · ✓ explicit consequences · ✓ **Decision ≠ Normative Rule** separated · ✓ **ADR status ≠ methodology maturity** separated · ✓ Decision Authority recorded · ✓ **no governance discoveries inside ADR-M** · ✓ **no DDD concepts** · ✓ **no invented history** · ✓ literature is supporting context only.

---

*Round 39-D6 — Methodology Decision Records (ADR-M) — ISSUED (closes audit finding F-3)*
*11 ADRs; ADR-status / methodology-maturity vocabularies separated; binding change-process (families propose, governance enacts); cross-reference matrix + timeline + triggers + backlog. Methodology fully traceable without chat history. Strategic DDD GATED.*
