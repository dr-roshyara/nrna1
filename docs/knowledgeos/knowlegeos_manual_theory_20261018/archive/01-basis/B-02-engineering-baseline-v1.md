# B-02 — Engineering Platform Baseline v1.0 (Phases 01–03A): extracted basis concepts

| Field | Value |
|---|---|
| Read | `engineering/architecture/baseline/` — 6 files, **full** (see `01-ledger/READ-AND-EXTRACTED.tsv`) |
| Dates | Phase 01, 02, 02.5, 02.6: 2026-07-07 · Phase 02.7, 03A: 2026-07-08 · all FROZEN as Baseline v1.0 by ADR-AIP-01 |
| Standing | engineering governance documents, **not** KnowledgeOS corpus files. Extracted here as candidate precursors of the theory's basis (hypothesis H-B1, `B-01`) |
| Labels | **[SRC]** stated in the source (anchor = file §/line) · **[I]** my interpretation · **[?]** open |

## 1. The originating problem (Phase-01, 2026-07-07)

- **[SRC]** A vendor AI framework was examined and rejected. Its governance was **"theatrical"**: hard-coded ADR scores, fabricated CVE counts, a "truth score" that was never defined but could still trigger automatic rollbacks, and success messages printed for no-ops (Phase-01 §2, §3 C9–C11).
- **[SRC]** The lesson is recorded as **negative knowledge**: *what fabricated governance looks like* (§7 item 8). There is also an AI-specific risk, **capability roleplay**: prompts asserting powers that do not exist produce confident fictional narration (§9).
- **[SRC]** The response is the **honesty invariant**: *"every reported score/verdict derived from an executable check that exists in-repo"* (§10.4).
- **[I]** This is the earliest stated problem in the material read so far. **A system must not let unverified assertions pass as knowledge or authority.**

## 2. Core concepts (as defined in the sources)

| ID | Concept | Definition / rule (source wording condensed) | Anchor |
|---|---|---|---|
| C-01 | **Evidence vs authority** | the platform *"owns the production, preservation, and verification of engineering evidence — never engineering authority"* | Phase-02 §1 |
| C-02 | **Ownership dispositions** | the only legal ownership words: **Owns · Coordinates · Preserves · Observes · Does-NOT-own**; exactly one owner per responsibility | Phase-02 §1; 02.6 §1.6 |
| C-03 | **Separation of duties** | *discover ≠ decide ≠ check ≠ entrench* (Round39-RGA); producer ≠ reviewer | Phase-02 §1, BC-5; AIP-05 |
| C-04 | **Authority** (dimension) | trust level of an artifact: `authoritative · derived · generated · historical · provisional`; **orthogonal to Status**; illegal combinations rejected (e.g. frozen + generated) | 02.6 §1.1; Phase-02 §4 BC-1 |
| C-05 | **Status** (dimension) | lifecycle position: `idea → research → draft → discovery → reviewed → approved → baseline → frozen` (side branch superseded → archived) | 02.6 §1.1 |
| C-06 | **Single authority** | exactly one authoritative item per topic | 02.6 §1.1; FF-2 |
| C-07 | **Human Decision Event** | the only authoritative facts in the domain (`ADRApproved`, `CapabilityCertified`, …); no platform state transition may substitute for one | Phase-02 §2 Ext. B; 02.6 §1.1 |
| C-08 | **Authority Boundary** | no promotion of `generated` toward `authoritative`, and no gate passage, without a referenced Human Decision Event; enforced *"by aggregate construction"* | Phase-02 §6 Tier-1 #1 |
| C-09 | **Promotion chain** | `Generated → ARB Review → Capability Certification → ADR Approval → Authoritative → Frozen`; stages never skipped | 02.6 §1.1 |
| C-10 | **Evidence** | *"the raw output of an executable check, preserved immutably and referenced by everything that claims anything"* | 02.6 §1.2 |
| C-11 | **Verdict** | constructible only with an Evidence reference; immutable; re-runs create new verdicts | 02.6 §1.2; Phase-02 §4 BC-4 |
| C-12 | **Verification ≠ certification** (D-11) | verification = objective, executable, never a judgment; certification = human judgment after verification | 02.6 §1.2 |
| C-13 | **Assertion integrity** (AIP-10) | *"no artifact may assert an event that has not occurred"*; for example, a certification report may only exist after certification has occurred (R-7) | 02.5 header, §3 |
| C-14 | **Append-only history** (AIP-11) | *"Nothing is ever rewritten. Everything is superseded."*; supersession creates a new item and demotes the old one to `historical` | 02.5 §3; R-4 |
| C-15 | **Derived, not asserted, values** | progress is a pure function of the WBS, *"structurally unsettable by hand"*; **lifecycle ⊥ progress** | Phase-02 BC-3, §4; 02.6 §1.5 |
| C-16 | **Evidence beats memory** | every stable fact carries repo provenance; on conflict the repo wins | Phase-02 BC-2; AIP-03 |
| C-17 | **Staleness** | a snapshot older than the state it references; *"surfaced, never silently repaired"* | 02.6 §1.4 |
| C-18 | **Escalation as obligation** | ambiguity, rule conflict or authority uncertainty → escalate; *"an obligation, not an option"* | 02.7 PD-19 |
| C-19 | **Decision traceability** (AIP-12) | ADR → Capability → Rule → Implementation, never Decision → Implementation | 02.5 §3 |
| C-20 | **Falsifiability** | a check counts only after a recorded run in which it detects a synthetic violation (RED) | 02.6 §1.2; Phase-02 §8 |
| C-21 | **Decide / recommend split** | the AI *decides* where the outcome is a derived fact of an executable check; it *recommends* where the outcome is a judgment; humans hold every change of authority, value or entrenchment | Phase-02 §7 |
| C-22 | **Forbidden vocabulary** | asserted *truth / quality / confidence scores*; *"validated"* (blurs verified/certified); learning claims | 02.6 §4 |

## 3. Structures

- **[SRC]** Six bounded contexts plus two external domains: Knowledge Governance · Session Continuity · Implementation Guidance · Verification & Evidence · Adversarial Review Support · Design & Decision Support (the core domain), plus Project Governance (upstream) and Human Authority (Phase-02 §2; 02.5 §1). A seventh context, BC-7, was added on 2026-08-17 (ADR-AIP-03).
- **[SRC]** Principles AIP-01…14; constitutional decisions PD-01…20 (binary; *"no rule without a guard or a named compensating control"*, 02.7 §4); fitness functions FF-1…16 (+FF-17).
- **[SRC]** *"The repository is the platform's persistence"*: no databases, no hidden state (03A §4).

## 4. Interpretation: correspondences with reported KnowledgeOS constructs

These correspondences are **not verified**: the KnowledgeOS side is still second-hand.

| Engineering concept | Candidate KnowledgeOS counterpart (reported) | Shape of the correspondence |
|---|---|---|
| C-01, C-07, C-08, C-21 | AX-1 *Proposal ≠ Decision*, *Evidence ≠ Knowledge*; AX-6 proposal–decision separation | generalization |
| C-04 + C-05 orthogonal dimensions | Conceptual Foundation dimension model (Semantic, Evidence, **Authority**, Temporal, **Lifecycle**) | same idea, extended by more dimensions |
| C-13 assertion integrity, C-11 evidence-bearing verdicts | FalseZero: *"Zero claims are auditable assertions, not status fields"* | specialization |
| C-15 derived values | v1 Ch. 5: gap/Zero is a *derived evaluation*, recomputed rather than stored | same idea |
| C-14 append-only + supersession | AX-4 non-monotonicity; REVISE/RETRACT; bitemporal history | generalization |
| C-16, C-17 | provenance (AX-5); "record outranks prose" | same idea |
| C-22 forbidden scores | S-7: no single scalar defines knowledge | same idea |

**[?] Chronology anomaly.** Corpus file S0011 is dated **2026-06-25** in the registry and matches the pattern `ADR-AIP`. ADR-AIP-01 was created on **2026-07-08**. Either the registry date is wrong, the file was edited later, or the match is unrelated. **To check when S0011 is read.**

## 5. Effect on hypothesis H-B1

- **Strengthened, but not tested.** The engineering baseline (7–8 July 2026) already contains, in governance form:
  - a dimension model (authority × status);
  - an evidence/authority separation;
  - derived values;
  - append-only supersession;
  - assertion integrity.
- **The decisive test is unchanged:** read the earliest KnowledgeOS documents and record where each concept first appears in theory form, and whether it is stated as a generalization of these sources.
