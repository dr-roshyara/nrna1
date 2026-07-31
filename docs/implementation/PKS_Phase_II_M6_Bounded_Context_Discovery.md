# PKS Phase II — M6: Bounded Context Discovery

| | |
|---|---|
| **Kind** | Strategic Modeling artifact (WP M6) — **evidence-driven discovery of bounded contexts** from the consolidated M0–M5 baseline. Boundaries are *found in evidence*, never drawn by preference. **Strategic DDD only: no tactical DDD, no aggregates/entities/services, no context map, no relationship patterns, no architecture, no implementation.** |
| **Authority** | Generated — never authoritative without human review. Everything below is a **recommendation**; the M6 Critical Review evaluates and the Authority disposes **per candidate** (G-M6-3). |
| **Status** | **EXECUTION COMPLETE (2026-07-28).** ⚠️ *Programme-position clause date-marked per M6R-2 — see §14 Amendment 5(b): the clause below said the Checkpoint Package was **ready** and that the programme should **STOP at G-M6-2**. **True when written; the programme has since passed G-M6-2, the Authority Disposition, Consolidation, MCA, CDR, M7, M8 and per-artifact promotion.** The commission-scope clause that follows it stands unchanged and is properly historical.* **As issued: M6 Checkpoint Package ready (§13). STOP at G-M6-2.** No disposition, no consolidation, no M7, no SDM/EOP extraction performed. |
| **Commission** | The **M6 Execution Commission** (issued PA, 2026-07-28; recorded verbatim-in-substance in the sealed session log `.claude/sessions/2026-07-28.md`, entry ★, and packaged at `PKS_Phase_II_M5_Consolidation_and_M6_Commission_Package.md` §5). Executed under **Authority Disposition: GO** (`PKS_Phase_II_M6_Execution_Readiness_Review.md` §13, five binding conditions). Execution Governance: the M6 Strategic Discovery Execution Charter (`PKS_Phase_II_M6_Fresh_Session_Prompt.md` §3.1) + the discovery charter in `.claude/CONTEXT.md`. |
| **Method** | The approved plan `.claude/plans/shiny-hopping-nest.md` **exactly as written**: Phases A–G · four evidence lenses L1–L4 · Phase B½ characterization · convergence-named candidates (≥3 lenses) · competing partitions · three falsification probes with pre-declared pass/fail conditions · Boundary Stability Test · Boundary Confidence · Emergence Verification · the Q7 sufficiency bar. No lens was added, removed, or reweighted. |
| **Placement** | `docs/implementation/`, beside M0–M5. |
| **Disposition History** | **2026-07-30: M6R-1 and M6R-2 disposed ACCEPT and applied as EVIDENCE-NEUTRAL folds (MCR-4 class 1) following the discovery-integrity review — §14 Amendment 5. M6R-1 completed Amendment 1's correction list (§7's confidence table · §12's self-verification); M6R-2 date-marked the Status field's programme-position clause. No evidence item, probe result, confidence grade or recommendation changed; no reopening condition triggered; §§0–13 remain unedited.** · 2026-07-28: executed in the fresh session required by G-M6-0(e), first act bootstrap verification (§1.1). · 2026-07-28: Critical Review complete (G-M6-2, ACCEPT WITH REFINEMENTS advisory). · 2026-07-28: **Authority Disposition issued (G-M6-3)** — CBC-1/CBC-2 ACCEPTED · CBC-4 ACCEPTED (adjacent) · CBC-3 RETURNED for evidence correction (`PKS_Phase_II_M6_Authority_Disposition.md`). · 2026-07-28: **Checkpoint Amendments 1–4 applied (§14)** by the Bounded Evidence Restatement / Fold Commission — the CBC-3 cohesion probe restated on the declared member set (PASS-marginal, 3-of-7; stability test FAILS item-1 removal; confidence Low-Medium; **remains below the recommendation bar on corrected grounds**); editorial folds F-M6CR-2/3/4 applied. CBC-3 re-disposition pending. · 2026-07-28: **CBC-3 RE-DISPOSED** (`PKS_Phase_II_M6_Authority_Disposition.md` §7) — **candidate seam, accepted as evidence-decided** on the corrected record; §7.5 reopening triggers binding; finer partition preserved-not-promoted (re-entry = B-4's MCA question). **G-M6-3 COMPLETE: CBC-1 ACCEPTED · CBC-2 ACCEPTED · CBC-4 ACCEPTED (adjacent) · CBC-3 CANDIDATE SEAM. Next gate: G-M6-4 Consolidation.** |

**Presentation convention** (M2 §10.4): claims carry their epistemic class inline at first use — **Observed:** · **Measured:** · **Derived:** · **Synthesized:** · **Recommendation:**.

**Register-sense disambiguation** (R-M6-8, applied at collection time, before any evidence was recorded): **register(doc)** = a document kind that carries many concept instances (decision log, rulings register) · **register(ns)** = M4's identity namespace unit · **register(art)** = a named artifact of this program (Concept Register, Surfacing Register). Every use below is qualified.

---

## 0. Executive Summary

**Three boundaries are recommended; one candidate was demoted by its own falsification probe; the domain's paradigmatic core is deliberately left unpartitioned.**

- **CBC-1 Knowledge Assessment** — evidence recorded, judged against criteria, issued by a gate or review and never by the author. 4-lens convergence; survives all three probes; **Boundary Confidence Medium-High**.
- **CBC-2 Knowledge Projection** — renderings of knowledge that carry no independent semantic identity and no authority. 4-lens convergence; survives all three probes with the strongest removal-test evidence in the run; **Medium-High**.
- **CBC-4 Work Management (adjacent domain — a boundary that *excludes*)** — discharges the M5-handed pre-staged boundary question. 4-lens convergence; survives all three probes; **Medium**. **Recommendation: adjacent, outside the PKS boundary** — the decision is the Authority's (G-M6-3).
- **CBC-3 Normative Governance — DEMOTED to candidate seam.** It converges on three lenses and passes removal and linguistic probes, but **fails the cohesion probe**: its members' identity and lifecycle behavior split three ways (modes 1/2/3), with Ruling and Candidate showing higher affinity to CBC-1 than to Rule and Invariant. The failure is recorded, not smoothed; the finer competing partition that would resolve it is preserved with the evidence that would decide it.
- **The expressed-knowledge core (Decision · Term · Model element · Contract · Question) is recorded as an unpartitioned region.** No boundary within it reaches the ≥3-lens bar. Naming one would be preference, not evidence. **Derived:** a boundary is two-sided, so the well-evidenced edges of CBC-1/CBC-2 imply this region exists; nothing in the evidence partitions its interior.

**Two findings about the method itself** (Ledger B, §11.2, routed to the MCA — not acted on): **R-M6-6 is confirmed, not merely mitigated** — the four lenses are four readings of one corpus produced by one lineage, so convergence is partly correlated agreement; this caps every confidence grade at Medium-High. And **the responsibility classes (F6) are not boundary-preserving** while the significance grades (F1) are **boundary-orthogonal** — which answers M5 §6's question left open for M6.

**Both predicted open questions bit, exactly where the plan said they would** (OQ-PKS-2 incumbent; OQ-PKS-7 three roots) — surfaced with impact statements in §9, neither resolved, discovery unblocked.

---

## 1. Phase A — Preparation

### 1.1 Gate verification — G-M6-0 (Entry)

| Criterion | Verification | Result |
|---|---|---|
| M5 disposition confirmed | `PKS_Phase_II_M5_Strategic_Domain_Classification.md` Status = "ACCEPTED WITH REFINEMENTS — the consolidated M5 strategic baseline"; Disposition History records PA confirmation via the Consolidation Commission | ✅ |
| Three folds (F-M5R-1..3) applied and **visible** | M5 artifact §"Checkpoint Amendments", items 1–3, each quoting its finding verbatim, originals unedited | ✅ verified by direct read |
| Plan approved | `.claude/plans/shiny-hopping-nest.md` — "PLAN STATUS: APPROVED as the M6 planning artifact (PA, 2026-07-28)" + the PA-directed merge note | ✅ |
| Execution commission issued | Session log 2026-07-28 entry ★ (verbatim-in-substance) + commission package §5 prerequisites updated to ISSUED | ✅ |
| **Fresh session opened** (M5 §10(c)) | This session contains no prior PKS Phase II execution turns; the 2026-07-28 record is **sealed** and was read, never appended | ✅ |

**Authority side:** Disposition **GO** (gate review §13), five conditions in force. **Governance state verified:** methodology baseline frozen · terminology frozen (condition 2) · constitutional rule active (*no methodology changes without execution evidence*) · governance/authority/review procedures unchanged (condition 3).

**One inconsistency reported, not repaired** (per the STOP-report-don't-repair rule): the repository convention is one session log per calendar day, and today's date (2026-07-28) is the **sealed** log's date. The seal states "no further appends; future refinements = new artifacts." Rather than reconcile by assumption, this execution wrote a **new** session record (`.claude/sessions/2026-07-28-M6-execution.md`) and reports the collision here. Not a blocker; not a methodology change.

### 1.2 Lens loading (four evidence tables, with source citations)

| Lens | Definition (plan Q1) | Sources loaded (plan Q2) |
|---|---|---|
| **L1 Seam** | the authority seam and relationship polarity | item 1 Q3 (relationship tables + derived polarity rule) · Q6 (ownership) · Q7 (authority establishment, contradiction taxonomy, AI/human delegation) · Q0 P-8 · M1 Part C.4 |
| **L2 Language** | UL shifts: where the same term changes meaning, or distinct vocabularies operate | M0 glossary G-1..G-17 + vocabulary risks V-1..V-6 · M1 Part B/D · M2 C-1/C-3/C-4 · batch checkpoint F-BCP-2/F-BCP-4 · item 1 Q5.1 |
| **L3 Responsibility** | F6×F1 classifications as **input-not-verdict** | M5 §4 (21 rows) · §3 (F5 absorbed as dependency structure) · §6 (O-M5-1, O-M5-2) · M5 Checkpoint Amendment 1 (the O-M5-1 grading assumption, carried explicitly per the F-M5R-1 fold) |
| **L4 Identity/namespace** | register(ns) namespaces and identity modes | M4 §1.1 (17 rows) · §1.2 (falsification test) · §1.3 (semantic ≠ representational) · §1.4 (identity under the evolution canon) · Checkpoint Amendments 2–3 |

**"item 1"** = `Strategic_DDD_Discovery_Product_Knowledge_System_Domain.md` (accepted Phase-I evidence discovery). **"item 2"** = the accepted candidate model (hypothesis set; no concept adopted). Also loaded per Q2: the Work-item adjacent-domain recommendation (M5 §4) as the one pre-staged boundary *question*, and `EPIC-002_Bounded_Context_Discovery.md` as the house method template.

### 1.3 Surfacing Register armed (trigger definitions transcribed from plan Q6)

| OQ | Pre-identified trigger (verbatim from plan Q6) | Armed |
|---|---|---|
| **OQ-PKS-2** (incumbent) | "expected to bite when candidate boundaries touch AKB/EKP-designed-system territory — the discovery proceeds on the *practice-based* system (the accepted executive finding) and surfaces the incumbent question rather than deciding it" | ✅ |
| **OQ-PKS-7** (three roots) | "expected to bite when candidates align with `docs/`/`architecture/`/`engineering/` territory — territorial alignment is recorded as observation, placement never decided" | ✅ |

**Honest ordering note (execution deviation, recorded not repaired):** the Phase-A exit criterion requires the triggers to be transcribed "before any evidence is read", but the bootstrap's own verification sequence requires loading the canonical artifacts first. The two obligations collide in a single-executor session. **The intent of the criterion is preserved**: the trigger definitions above are transcribed *verbatim from the approved plan*, so they could not be tailored to what the evidence turned out to show. The collision itself is a methodology observation → Ledger B (§11.2).

**Phase A exit:** G-M6-0 evidenced in this header ✅ · four lens tables loaded with source citations ✅ · OQ-2/OQ-7 triggers transcribed ✅ (with the ordering note above).

---

## 2. Phase B — Evidence Collection (four inventories, collected per lens, independently, before any candidate is named)

**Discipline applied:** each inventory below was derived from its own sources only. Gaps are recorded as gaps. No item was inferred to fill a hole.

### 2.1 L1 — Seam inventory (authority seam + relationship polarity)

| # | Seam evidence | Source (artifact + row) |
|---|---|---|
| L1-1 | **Derived polarity rule** — every *authoritative* relationship is created or changed only at a human decision event; every *informational* relationship may be written by the author. No counterexample found | item 1 Q3.1, closing paragraph |
| L1-2 | "**nothing may cite a view as authority**" | item 1 Q3.1, informational row `describes / documents` (c4 README) |
| L1-3 | Hub self-description: "**authority: derived** — it points at sources, it is not itself the source of truth" | item 1 Q3.1, same row |
| L1-4 | "**documents record governance; decisions create it**"; the record is per-item and explicit, never inferred from a signal | item 1 Q3.1, authoritative row `is-recorded-by` |
| L1-5 | The acceptance transition: "before that acceptance they remain candidates; **the transition happens at acceptance, unmistakably, and nowhere else**" | item 1 Q7.1 |
| L1-6 | Authority is rank-ordered on conflict — three precedence statements + two meta-rules (evidence beats memory; frozen beats descriptive) | item 1 Q7.1 |
| L1-7 | Authority is **revocable only forward** — supersession / versioned re-issue / VOID-with-record; "no in-place revocation exists anywhere" | item 1 Q7.1 |
| L1-8 | `authorizes` — a charter/gate grant "permits the next activity and nothing else" ("authorizes *planning* an architecture, not the architecture itself") | item 1 Q3.1, authoritative row `authorizes` |
| L1-9 | `binds` (platform→project) — adoption without fork; "may be stricter, never looser" | item 1 Q3.1, authoritative row `binds` |
| L1-10 | `drives` — ER-06 binding *ordering* between knowledge kinds: ADR-UL precedes ADR-PL precedes contract version precedes implementation | item 1 Q3.1, authoritative row `drives` |
| L1-11 | `frozen-input-to` — a frozen artifact is the immutable premise of its successor; entry conditions verify the link | item 1 Q3.1, authoritative row |
| L1-12 | **P-8** — knowledge carries evidence *for* governance decisions, "knowledge as input to authority, **never as authority**"; "when memory and evidence disagree, evidence wins" | item 1 Q0, P-8 |
| L1-13 | AI/human delegation boundary: draft/execute/record/recommend vs approve-ratify-freeze-adopt-promote-certify; "**Authors propose; the authority adopts**" (ES-001.2); "gates are DA-owned"; "the assistant prepares, never advocates" | item 1 Q7.3 + closing sentence |
| L1-14 | **Ownership gaps (negative evidence):** "Own after approval — **Evidence not found** as a named assignment"; "Keep consistent across artifacts — **Evidence not found**. No role owns cross-artifact consistency" | item 1 Q6.2, rows 4 and 6 |
| L1-15 | **Frozen carrier vs superseded concept** — the FROZEN Blueprint still encodes ADR-T17 after T23 superseded it; carrier lifecycle and concept lifecycle disagree; detected but deliberately deferred | item 1 Q2.2 row 3 + Q7.2 row 5 |
| L1-16 | **Designed vs practiced systems are disjoint** — where relationships are richest in meaning they exist only as prose; where machine-readable, they are unused (`supersedes` 0, `depends_on` 0, `reviewed_by` 0) | item 1 Q3.2 (the "machine-readable inversion") + §0 findings 1–2 |
| L1-17 | **Three roots, no unified index** — `docs/`, `architecture/`, `engineering/` bindings; "no single index covering all three" | item 1 Q2.2 row 4 |
| L1-18 | Contradiction detection is **entirely human/AI reading**; the corpus nevertheless operates a six-class conflict taxonomy with resolution rules | item 1 Q7.2 |

### 2.2 L2 — UL-shift inventory (where the same term changes meaning, or distinct vocabularies operate)

| # | Language evidence | Source (artifact + row) |
|---|---|---|
| L2-1 | "**register**" carries **three senses**: (a) document kind, (b) M4's namespace unit, (c) an artifact's own name | Batch Checkpoint Report, F-BCP-4 |
| L2-2 | "**Qualification**" carries **two senses**: (a) the verification activity/instrument, (b) a promotion-ladder stage — flagged by the corpus's own UL audit | item 1 Q9 OQ-PKS-11 · M0 G-15 note · M1 Part D S-1 |
| L2-3 | "**Constraint**" is an umbrella word whose referents resolve to Rules, Invariants or ADRs on inspection; no first-class threshold object measured | M2 §3, Steps 2–5 |
| L2-4 | "**Requirement**" — evidence not found as an identified unit; the function is distributed across Invariant + acceptance criterion + conformance test | M2 §1 (C-1) · item 1 §1.2-negative |
| L2-5 | **Rule vs Invariant carry different violation semantics**: violating a Rule is a *governance violation*; violating an Invariant is a *defect* | M2 §3, Step 2 |
| L2-6 | **Decision vs Ruling** — the grain boundary is fuzzy; a Ruling is "a recorded governance act by an authority, smaller-grained than a decision record" | M0 G-4 · M1 Part A row Ruling |
| L2-7 | "**R-nn**" is a cross-kind collision: Risk register(ns) and Ruling register(ns) share the token; each register is internally coherent | M4 §1.2 · M0 G-11 |
| L2-8 | "**OQ-**" is overloaded across documents; `EPIC-003/004` means two things | M4 §1.1 row Question · item 1 Q9 OQ-PKS-1 |
| L2-9 | **Eight declared lifecycle vocabularies + ~60 ad-hoc status tokens**; several vocabularies encode different **axes**, not different states (authority ⊥ status ⊥ maturity ⊥ adoption) | item 1 Q5.1/Q5.2 · M4 §2.2 |
| L2-10 | "**Contract**" is validated only in **event-contract** scope; the generalized sense (APIs, component interfaces) is unevidenced product-side | M1 Part A row Contract (VALIDATED — NARROWED) |
| L2-11 | The conformance result is **two different things by shape**: the measured *degree* is a derived **Observation**; the categorical judgment is a **Verdict** | M3 Checkpoint Amendment F-BCP-2 |
| L2-12 | Decision-language and teaching-language are explicitly separated: "**the ADR records the *decision*; the guide is the developer *how-to***" | item 1 Q0 P-6 |
| L2-13 | "**Policy**" names two things: a normative package of Invariants+Rules+scope (the four constitutional policies) and a *projection pattern* | M2 §3 Step 5 · M5 §4 row Policy (pattern) |
| L2-14 | Candidate label "**Definition**" not adopted; the observed term "**Term**" wins — evidence-first UL in force | M1 Part B row Definition |
| L2-15 | The **epistemic-class vocabulary** (Observed · Measured · Derived · Interpreted · Synthesized) attaches to evidence claims and to nothing else in the corpus | item 1 Method note · M2 §10.4 |
| L2-16 | **Verdict tokens** form their own closed-ish vocabulary: PASS · PASS AFTER CORRECTION · WARN · FAIL · INCONCLUSIVE · EMERGENT · CERTIFIED | item 1 §1.2 K-15 |

### 2.3 L3 — Responsibility-cluster map (F6×F1 as input, never verdict)

| # | Cluster evidence | Source |
|---|---|---|
| L3-1 | **expresses** = Decision (Core) · Term (Core) · Model element (Supporting) | M5 §4 |
| L3-2 | **governs** = Rule (Core) · Invariant (Core) · Contract (Supporting) · Candidate (Core) · Charter grant (Core) | M5 §4 |
| L3-3 | **records** = Ruling (Core) · Finding (Supp) · Question (Supp) · Observation (Supp) · Risk (Generic) · Work item (Generic, adjacent-candidate) · Exception record (Supp) | M5 §4 |
| L3-4 | **derives** = Verdict (Core) · Conformance (Core) · Completeness (Supp) · Guide step (Supp) · ADR pattern (Supp) · Policy pattern (Supp) | M5 §4 |
| L3-5 | **F5 dependency ordering absorbed as structure:** Observation → criterion → Verdict → … | M5 §3, F5 row |
| L3-6 | **O-M5-2** — the *governs* class coincides with concepts whose relationships are **authoritative** under the observed polarity (recorded as OQ-PKS-4 territory, unresolved) | M5 §6 |
| L3-7 | **O-M5-1 + grading assumption (F-M5R-1 fold):** ~47% Core; governance mechanics are identity-constitutive in a domain about governed knowledge — load-bearing specifically for Charter grant and Candidate | M5 §6 · Checkpoint Amendment 1 |
| L3-8 | **Work-item M1 promise discharged:** Generic within the PKS view + **adjacent work-management-domain candidate**, boundary decision routed to M6/ARB | M5 §4 row Work item · M1 Part A row Work item |
| L3-9 | **Conformance = Core while operationally Absent** (classification ≠ operationality; three-orthogonal-tests discipline) | M5 §4 row Conformance · M3 Step 6 |
| L3-10 | **Completeness** = state-assessed derived assessment; its *Domain Coverage* dimension is explicitly **sequenced after M6** | M5 §4 row Completeness · M2 §5 table |
| L3-11 | **Contract** graded Supporting on portability: "generic capability, locally well-governed" | M5 §4 row Contract |
| L3-12 | **Observation** graded Supporting via K6: the *discipline* differentiates, the *concept* is universal | M5 §4 row Observation |

### 2.4 L4 — Identity / namespace map

| # | Identity evidence | Source |
|---|---|---|
| L4-1 | **Mode 1 — durable-global, register(ns)-namespaced, assigned ordinal:** Decision · Rule · Invariant · Work item · Contract (versioned variant) | M4 §1.1 |
| L4-2 | **Mode 2 — scoped-assigned:** Finding (run-scoped by *stated design*, ≥6 F-spaces) · Observation · Risk · Ruling · Verdict (run-scoped, often unnamed) · Question (with drift liability) · Candidate | M4 §1.1 |
| L4-3 | **Mode 3 — intrinsic / name-as-identity:** Term (the name *is* the identity; change is a governed event) · Model element · Exception record (intrinsic-composite, keyed by what it excepts) · Charter grant (carrier variant) | M4 §1.1 |
| L4-4 | **Mode 3\* — Guide step:** filename+ordinal only; representational identity doubling as semantic identity — a **named liability** | M4 §1.1 · §1.3 |
| L4-5 | **The register(ns) is the namespace unit** — for **assigned-ordinal identity (modes 1–2)**, where every observed collision lives; mode-3 kinds cohere through governed naming or carrier constitution instead | M4 §1.2 · Checkpoint Amendment 2 |
| L4-6 | Genuine collisions occur **exactly where register(ns) discipline lapses** — three distinct "ADR-004/005/001" minted outside any governed register(ns) | M4 §1.2 |
| L4-7 | **Semantic ≠ representational identity**; ES-004.2: "cite durable ids, not dated filenames" states the distinction as a rule | M4 §1.3 |
| L4-8 | **Derived artifacts carry NO independent semantic identity** — "regenerated, never hand-edited" | M4 §1.4, `derive/regenerate` row |
| L4-9 | Identity **survives supersession**: predecessor keeps its identity as history; successor mints new | M4 §1.4, `supersede` row |
| L4-10 | Contract identity = **row identity + orthogonal version** (SchemaVersion) | M4 §1.1 row Contract |
| L4-11 | **Carrier-linked identity exists in two forms** — *by design* (Charter grant: the document is constitutive) and *by neglect* (Guide step: the flagged liability) | M4 Checkpoint Amendment 3 |
| L4-12 | Namespace inventory observed: ADR-T· / ADR-MP· / D-· · ES/ER/EP · CI/BI/INV · PB/EPIC/ENG/AD · catalog rows · ≥6 F-spaces · O-n · R-1..7 · R-nn · OQ- · P-n · the UL name-space · model-scoped names · target keys · per-charter carriers · area-scoped filenames | M4 §1.1 · item 1 §1.2 |

**Phase B exit:** four inventories complete ✅ · every item carries source artifact + row ✅ · gaps recorded as gaps (L1-14 negative evidence; L2-4 absence; L3-9 Absent operationality) ✅ · "register" senses disambiguated at collection time ✅ (R-M6-8 mitigation applied in the header).

---

## 3. Phase B½ — Evidence Characterization

*(For every collected item, before any candidate exists: **(a)** what it objectively says · **(b)** what it does *not* say · **(c)** plausible alternative interpretations · **(d)** confidence per the M0 rubric. Items whose (c) alternatives diverge materially are flagged **⚑** for extra scrutiny in Phase D.)*

### 3.1 L1 characterization

| # | (a) Objectively says | (b) Does NOT say | (c) Alternative interpretations | (d) Conf. |
|---|---|---|---|---|
| L1-1 | Two relationship classes behave differently with respect to who may write them | That the polarity is a *rule* — the corpus never states it (OQ-PKS-4) | ⚑ Could be an artefact of what gets written down rather than a domain property; could be a governance rule awaiting statement | Medium-High |
| L1-2/3 | Views and hubs are excluded from being cited as authority | That views are unimportant, or that they may not be *governed* | The exclusion may be a documentation-hygiene convention rather than a domain boundary | High |
| L1-4 | Recording and deciding are separate acts with separate owners | Which artifact class performs which act in every case | Could be read as a role separation (human/AI) rather than a knowledge-kind separation | High |
| L1-5 | There is a single, locatable transition into authoritative standing | Who may perform it in every context, or what happens to dependents | ⚑ Could mark a lifecycle state boundary rather than a context boundary | High |
| L1-6 | Conflicts are resolved by rank, and three independent ladders exist | That the three ladders agree; nothing reconciles them | The plurality may itself be a seam (three governance regions) or mere duplication | Medium-High |
| L1-7 | Revocation is forward-only, universally | Why; nor whether the constraint is deliberate | Could be a constitutional property or an unexamined habit | High |
| L1-8 | Authorization is bounded permission for a *named next activity* | Whether the grant is knowledge or merely process | ⚑ Charter grant could be a work-management object rather than a knowledge object | High |
| L1-9 | Platform norms bind product-side without forking | Whether platform and product are one corpus or two | ⚑ Directly implicates OQ-PKS-7 | High |
| L1-10 | A binding *ordering* exists between knowledge kinds | That the ordering implies co-location or shared ownership | Could indicate a pipeline within one context, or a chain across several | High |
| L1-11 | Frozen artifacts are immutable premises of successors | Whether the premise relation crosses boundaries or stays inside one | Could be an intra-context chain discipline | High |
| L1-12 | Knowledge feeds authority and is never itself authority | Where the feeding stops — no assessor or cadence named | Could be an epistemic principle rather than a structural seam | High |
| L1-13 | A stable, enumerated act-level split between AI and human authority | That the split maps onto knowledge kinds | ⚑ Could be an actor boundary (team topology) rather than a context boundary | High |
| L1-14 | **Two ownership roles are absent** (post-approval custody; cross-artifact consistency) | Whether the absence is deliberate | A vacuum can indicate a missing context, an unstaffed role, or a genuinely unneeded function | High (that the evidence is absent) |
| L1-15 | Carrier lifecycle and concept lifecycle can diverge and stay divergent | That anything reconciles them | Evidence for the concept/projection separation, **or** evidence merely of maintenance debt | High |
| L1-16 | The designed relationship system and the practiced one are disjoint | Which should be treated as the system of record | ⚑ Directly implicates OQ-PKS-2 | High (Measured) |
| L1-17 | The corpus spans three roots with no unified index | Whether that is one domain or several | ⚑ Directly implicates OQ-PKS-7 | High (Measured) |
| L1-18 | Contradiction detection is unautomated, but a resolution taxonomy operates | Who runs detection, or on what cadence (OQ-PKS-3) | An absent capability, or a capability performed implicitly by review | High |

### 3.2 L2 characterization

| # | (a) Objectively says | (b) Does NOT say | (c) Alternative interpretations | (d) Conf. |
|---|---|---|---|---|
| L2-1 | One token carries three recorded senses | That any current statement is ambiguous (F-BCP-4 explicitly says none is) | ⚑ A boundary signal, **or** ordinary polysemy in a maturing UL | Medium-High |
| L2-2 | "Qualification" has two recorded senses, ARB-owned and open | Which sense is canonical | ⚑ Classic Evans boundary signal, **or** a term awaiting a UL ruling | High (that the collision exists) |
| L2-3 | "Constraint" is colloquial umbrella, not a concept | That threshold objects can never exist here | Umbrella usage may hide an unexpressed distinction (M2 recorded the trigger) | Medium-High |
| L2-4 | The Requirement function is distributed, not reified | That distribution is sufficient — rests on absence-of-harm | ⚑ Distributed representation, or an unmeasured gap | Medium-High |
| L2-5 | Two normative kinds differ by violation consequence | That they belong to different contexts | Evidence of an internal distinction, **or** of a seam | High |
| L2-6 | Ruling and Decision differ by grain and authority, fuzzily | Where the grain line falls | Two concepts in one context, or two contexts | Medium |
| L2-7 | A token collides across two kinds' registers(ns) | That meaning shifts — each register(ns) is internally coherent | Identity-discipline defect, not necessarily a language boundary | High |
| L2-8 | Question ids drifted across documents | That the concept is unstable | Drift liability, or evidence Question lacks a home | Medium |
| L2-9 | Eight vocabularies encode different **axes**, not competing states | Which vocabulary governs which kind (OQ-PKS-9) | ⚑ Multiple contexts each with its own lifecycle language, **or** one context with unfinished vocabulary work | Medium-High |
| L2-10 | Contract is evidenced only for event contracts | That non-event contracts don't exist elsewhere | Narrow concept, or narrow observation window | Medium-High |
| L2-11 | Degree and judgment are different output kinds | That both must be produced together | Evidence of two roles inside assessment, or of two contexts | High |
| L2-12 | Decision records and teaching artifacts are explicitly different kinds of writing | That they must live in different contexts | ⚑ Strong projection signal, **or** an audience convention | High |
| L2-13 | "Policy" names both content and serialization | Which is primary | A projection/content pair, or sloppy naming | Medium-High |
| L2-14 | Observed vocabulary beats proposed vocabulary | That "Definition" is wrong | Evidence-first UL discipline in force | High |
| L2-15 | Epistemic labels attach to evidence claims only | That other kinds could not carry them | Marks an evidence-handling vocabulary, or a program-wide writing convention | High |
| L2-16 | Verdict tokens form a distinct closed-ish vocabulary | Whether the set is fixed | Assessment-local language, or generic gate language | High |

### 3.3 L3 characterization

| # | (a) Objectively says | (b) Does NOT say | (c) Alternative interpretations | (d) Conf. |
|---|---|---|---|---|
| L3-1..4 | Every validated item carries exactly one primary semantic responsibility, with competing classifications preserved | That responsibility classes are boundaries — M5 explicitly refuses to pre-decide this (§6) | ⚑ Classes could seed boundaries, cut across them, or be orthogonal to them | Medium-High (framework), per-row as M5 records |
| L3-5 | A dependency ordering runs Observation → criterion → Verdict | That the ordering stays within one grouping | A pipeline inside one context, or a chain across contexts | High |
| L3-6 | *governs* coincides with authoritative polarity | That either causes the other; OQ-PKS-4 owns the question | ⚑ A deep structural link (cross-lens convergence), **or** two descriptions of the same underlying facts — i.e. **correlated, not independent, lenses** | Medium |
| L3-7 | Core-density is high and the grading rests on a named assumption | That the assumption holds outside this corpus | Meta-domain property, or grading generosity | Medium |
| L3-8 | Work item is Generic in the PKS view and recommended as an adjacent-domain candidate | That the boundary is decided — explicitly routed to M6/ARB | Adjacent domain, or PKS-member Supporting (M5 preserved this competitor) | Medium |
| L3-9 | Conformance is strategically Core while operationally Absent | That any mechanism exists | Core-and-absent, or Core-because-narrated (T-14) | Medium |
| L3-10 | Completeness is state-assessed; Domain Coverage waits on M6 | That M6 must supply coverage input | A sequencing fact | Medium |
| L3-11 | Contract is a generic capability, locally well-governed | That it is unimportant | Supporting member, or an interface concern of another domain | Medium-High |
| L3-12 | Observation is universal; the discipline is what differentiates | That the discipline lives in the Observation concept — it lives in Rules | Supports separating evidence-handling *rules* from evidence *records* | High |

### 3.4 L4 characterization

| # | (a) Objectively says | (b) Does NOT say | (c) Alternative interpretations | (d) Conf. |
|---|---|---|---|---|
| L4-1 | Five kinds carry durable, register(ns)-scoped global identity | That they share a register(ns) — they each have their own | Cohesion by identity *mode*, not by namespace | High |
| L4-2 | Seven kinds carry run/register-scoped identity, with run-scoping a **stated design** for Finding | That scoping was chosen jointly for all seven | ⚑ A shared identity regime (boundary signal), or seven independent local choices | Medium-High |
| L4-3 | Four kinds are identified intrinsically (name or target key) | That intrinsic identity implies shared ownership | Cohesion by identity kind, or coincidence | Medium-High |
| L4-4 | One kind has representational-only identity, flagged as a liability | That the liability caused its weak conformance (M4 marks correlation, not cause) | Evidence for the projection family, or a maintenance defect | Medium-High |
| L4-5 | The register(ns) is the namespace unit for modes 1–2 only | That mode-3 kinds are unmanaged — they cohere otherwise | Preferred explanatory model, not definitive ontology (M4's own wording) | Medium-High |
| L4-6 | Collisions track register(ns) discipline lapses precisely | That register(ns) discipline is a boundary | Identity-governance property, possibly cross-cutting | High |
| L4-7 | Semantic and representational identity are distinct, and the corpus states it as a rule | Which artifacts must carry which | Foundational for separating concept from carrier | High |
| L4-8 | Derived artifacts have **no** independent semantic identity | That they are ungoverned | ⚑ The single strongest structural signal for a projection boundary | High |
| L4-9 | Identity survives supersession | Anything about carriers | Lifecycle property | High |
| L4-10 | Contract has row identity plus orthogonal version | That versioning generalizes to other kinds | Contract-local, or a general pattern | Medium-High |
| L4-11 | Carrier-linked identity occurs by design *and* by neglect | Which is more common | Two different phenomena sharing a shape | Medium-High |
| L4-12 | At least sixteen distinct namespaces operate | That they are coordinated — no unified index exists | ⚑ Namespace plurality could mark contexts, or merely local conventions | High (Measured) |

**Phase B½ exit:** no uncharacterized item enters Phase C ✅ · items with materially diverging alternatives flagged **⚑** for Phase D ✅.

---

## 4. Phase C — Candidate Identification (by convergence only)

**Rule applied (plan Q3/Phase C):** a candidate is named only where **independent lenses converge**; ≥3 lenses is the naming threshold; single-lens signals are recorded as **seams-to-watch**, never named as candidates.

### 4.1 Convergence table

| Candidate | L1 (seam) | L2 (language) | L3 (responsibility) | L4 (identity) | Lenses |
|---|---|---|---|---|---|
| **CBC-1 Knowledge Assessment** | L1-12 (knowledge feeds authority, never is it) · L1-4 · L1-18 · Verdict "issued by a gate or review, never by the author" | L2-2 (**Qualification** shifts exactly across this line) · L2-11 · L2-15 · L2-16 | L3-3 (records subset) + L3-4 (Verdict, Conformance, Completeness) + L3-5 ordering + L3-12 | L4-2 (Observation · Finding · Verdict · Risk · Question all mode 2, run/register-scoped) | **4** |
| **CBC-2 Knowledge Projection** | L1-2 · L1-3 · L1-15 | L2-12 · L2-13 · "authority: derived" phrasing | L3-4 (Guide step · ADR pattern · Policy pattern) | L4-8 (**no independent semantic identity**) · L4-4 · L4-11 | **4** |
| **CBC-3 Normative Governance** | L1-1 · L1-5 · L1-6 · L1-7 · L1-8 · L1-13 (the whole authority seam) | L2-5 · L2-3 · L2-13 · "approved" across three axes (L2-9) | L3-2 (*governs*) + L3-6 (polarity coincidence) | **Split**: Rule/Invariant/Contract mode 1; Candidate mode 2; Charter grant mode 3 | **3** (L4 does not support) |
| **CBC-4 Work Management (adjacent)** | L1-13-adjacent: work items carry no authority; the 14-box DoD binds knowledge obligations *to* work items — a relationship across a line | L2-9 (L-5 is a *work-item status* vocabulary; "lifecycle ≠ progress" applies to these only) | L3-8 | L4-1/L4-12 (PB/EPIC/ENG/AD: own namespace family, disjoint from every knowledge register(ns)) | **4** |

### 4.2 Named candidates (hypotheses, every one)

- **CBC-1 — Knowledge Assessment.** Members: Observation · Finding · Verdict · Conformance (derived assessment) · Completeness (derived assessment). Contested membership: **Risk** and **Question** (see seams-to-watch).
- **CBC-2 — Knowledge Projection.** Members: Guide step · ADR (projection pattern) · Policy (projection pattern) · views/C4 · portal indexes/hubs/packages.
- **CBC-3 — Normative Governance.** Members: Rule · Invariant · Charter grant · Candidate · Ruling · (Policy-as-content) · Exception record (contested).
- **CBC-4 — Work Management.** Members: Work item (PB/EPIC/ENG/AD, WBS rows, boards). Named as a candidate **boundary of the domain**, i.e. a recommendation about what is *outside*.

### 4.3 Seams-to-watch (single- or two-lens signals — recorded, not named as candidates)

| Seam | Lens support | Why it is not a candidate |
|---|---|---|
| **Expressed-knowledge core** (Decision · Term · Model element) | L3-1 only; L4 splits it (Decision mode 1 vs Term/Model element mode 3) | One lens. Naming it would be preference. Its *existence as a region* is implied by the complement of well-evidenced edges — recorded in §7.4 |
| **Identity & register(ns) discipline** | L4 strong; L1 partial (L1-14 ownership vacuum); L2 (L2-1 the word itself collides); L3 none | No responsibility class corresponds; the L2 support is a *collision on the candidate's own name* — precedent (the "Qualification" rejection, session log 2026-07-28) counsels against naming a boundary with the corpus's contested word |
| **Consistency / contradiction management** | L1-18 · L1-14 · L1-15 strong; L2 (conflict taxonomy) moderate; L3/L4 none | Operationally **Absent** (no mechanism, no owner, no cadence — OQ-PKS-3); a capability gap is not yet a boundary |
| **Question routing** | L3-3 · L4-2 · L2-8 | Question's lifecycle outliving its carrier is distinctive, but every signal is shared with CBC-1's members; treated as contested membership rather than a boundary |
| **Contract / boundary specification** | L2-10 · L3-11 · L4-10 | Three weak signals all saying the same thing (it is narrow and generic); no seam statement anywhere marks its edge |
| **Exception record** | L3-3 · L4-3 | Contested between governance (an approved deviation is an authority act) and assessment (a deviation record is conformance evidence) |

**Phase C exit:** every candidate names its converging lenses and the exact evidence items ✅ · **zero candidates sourced from a single lens** ✅ · the seams-to-watch list is non-empty ✅.

---

## 5. Phase D — Competing-Boundary Analysis

*(Per candidate, at least one **genuinely argued** competing partition — a strawman competitor is a Phase-D failure. Both are evaluated under Q3: convergence, evidence quality, coverage.)*

### 5.1 CBC-1 Knowledge Assessment — competitor: **split into Evidence Recording | Judgment**

| | CBC-1 as named (one context) | Competitor (finer: recording ∥ judging) |
|---|---|---|
| **Strengths** | The F5 ordering (L3-5) is an *internal* pipeline; identity mode 2 is uniform across both halves (L4-2); the epistemic vocabulary (L2-15) spans both; the authority seam (L1-12) sits at the *outer* edge, not between the halves | F-BCP-2 (L2-11) shows the output genuinely bifurcates: degree = Observation, judgment = Verdict. M3 Step 3.5 classifies the phenomenon as *property at object grain, assessed magnitude at system grain* — two grains |
| **Weaknesses** | Absorbs a real two-grain distinction into one context | No seam statement separates recording from judging; both are performed by the same acts (reviews, gates, sweeps); M3 explicitly composes them (**Observation + criterion → Verdict**) rather than separating them |
| **Evidence coverage** | Covers L1-12, L1-4, L1-18, L2-2/11/15/16, L3-3/4/5/12, L4-2 | Covers L2-11 and M3 Step 3.5 only; leaves L1 and L4 evidence unexplained (both halves would share the same seam and the same identity mode) |

**Outcome:** the finer competitor is **rejected as the preferred reading** — it explains one lens and orphans two. Preserved with its reopening trigger (§7.5).

### 5.2 CBC-2 Knowledge Projection — competitor: **projection is a cross-cutting responsibility, not a context**

| | CBC-2 as named | Competitor (dissolving) |
|---|---|---|
| **Strengths** | L4-8 is categorical: derived artifacts carry **no independent semantic identity** — a shared *structural* property, not a shared topic. L1-2 states an authority exclusion that applies to the whole class. Guide step's mode-3\* liability (L4-4) is exactly what membership predicts | Every context plausibly projects its own knowledge; a "projection context" could be an artefact of grouping by *form* rather than by *meaning* — the classic documentation-shadow error (R-M6-5) |
| **Weaknesses** | Risks grouping by artifact form (the R-M6-5 failure mode) | Cannot explain why the corpus states a *single* authority rule covering all projections regardless of source context ("nothing may cite a view as authority"), nor why derived artifacts share one identity regime across unrelated topics |
| **Evidence coverage** | Covers L1-2/3/15, L2-12/13, L3-4 subset, L4-4/8/11 | Covers the intuition; explains none of the L4 evidence |

**Outcome:** the dissolving competitor is **rejected**, on the ground that a cross-cutting responsibility would not produce a *shared identity regime*. Preserved with its trigger.

### 5.3 CBC-3 Normative Governance — competitor: **split into Norm Custody | Authorization Acts**

| | CBC-3 as named (one context) | Competitor (finer) |
|---|---|---|
| **Strengths** | The authority seam (L1-1/5/6/7/8/13) is one continuous body of evidence; O-M5-2 (L3-6) links *governs* to authoritative polarity | **Norm Custody** (Rule · Invariant · Policy-as-content · Contract) is identity-uniform (mode 1, register(ns)-namespaced), lifecycle-uniform (standing norms, authoritative class, changed by version/re-issue or amend-additively) and violation-semantics-bearing. **Authorization Acts** (Charter grant · Ruling · Candidate promotion) are act-shaped: consumed on use, carrier- or run-identified, terminal on issuance |
| **Weaknesses** | Members' identity and lifecycle behavior split three ways; Ruling and Candidate behave like CBC-1's members (mode 2, recorded acts) more than like Rule and Invariant | Each half converges on fewer and weaker lenses than the whole did; the split was constructed in Phase D, not surfaced in Phase B — promoting it now would be candidate-first reasoning in reverse |
| **Evidence coverage** | Covers all of L1's authority seam, L2-5/3/13, L3-2/6 — but **contradicts L4** | Explains L4 and the cohesion evidence; leaves the authority seam distributed across both halves without saying which half owns it |

**Outcome:** **neither reading is adopted.** The named candidate fails cohesion (§6.3); the finer competitor resolves cohesion but has not been through Phase B collection under its own terms. Both preserved; the discriminating evidence is named in §7.5.

### 5.4 CBC-4 Work Management — competitor: **Work item is a PKS-member Supporting concept** (M5's own preserved alternative)

| | CBC-4 as named (adjacent domain) | Competitor (inside PKS) |
|---|---|---|
| **Strengths** | Disjoint namespace family (L4-12); its own lifecycle vocabulary (L-5) which M4 §2.2 explicitly types as a *work-item status* vocabulary; "lifecycle ≠ progress" is scoped to these objects alone; work items carry no knowledge authority | The "lifecycle ≠ progress" rule is itself PKS knowledge; the 14-box DoD makes five *knowledge* obligations conditions of a work item's completion — a tight coupling; M5 graded the concept Medium and preserved this alternative deliberately |
| **Weaknesses** | The DoD coupling is real and must be explained as a cross-boundary relationship rather than dismissed | Cannot explain the disjoint namespace or the separate lifecycle vocabulary; would place a Generic-graded concept inside a domain whose Core is knowledge governance |
| **Evidence coverage** | Covers L1 (DoD as relationship), L2-9, L3-8, L4-1/12 | Covers the DoD coupling; leaves L4 and L2-9 unexplained |

**Outcome:** the adjacent reading is preferred; the competitor is preserved verbatim as M5 recorded it.

**Phase D exit:** no candidate without a genuinely argued competitor ✅ · per-pair strengths/weaknesses/evidence-coverage recorded ✅.

---

## 6. Phase E — Falsification Log

**Pass/fail conditions, declared before any probe was run** (verbatim from plan Q4):
- **(a) Removal test** — hypothesize the boundary erased and enumerate what breaks, citing artifact + row. **FAILS** the candidate if *no named invariant, vocabulary rule, or authority rule actually breaks*.
- **(b) Linguistic probe** — for each term crossing the line, does its recorded meaning genuinely shift? **FAILS** if *no term genuinely shifts*.
- **(c) Cohesion probe** — do the concepts inside share lifecycle/identity/authority behavior more with each other than with concepts across the line? **FAILS** if *cross-line affinity equals or exceeds in-line affinity for a majority of member concepts*.
- **Failing ≥2 probes falsifies the candidate.**

### 6.1 CBC-1 Knowledge Assessment

| Probe | Evidence | Outcome |
|---|---|---|
| (a) Removal | Erasing the line collapses: "**issued by a gate or review, never by the author**" (item 1 §1.2 K-15 — an authority rule that presupposes assessment is separable from authorship and from deciding) · **P-8** "knowledge as input to authority, never as authority" (item 1 Q0) · ER-02 "findings invalid without evidence refs" · "when memory and evidence disagree, evidence wins" | **PASS** — three named authority/evidence rules break |
| (b) Linguistic | **Qualification** shifts across this exact line: *verification instrument* (inside) vs *promotion-ladder stage* (outside, in the candidate/promotion vocabulary) — OQ-PKS-11, a collision the corpus itself recorded before this discovery. Also **Observation**: raw evidence entry vs the measured *degree* produced by an assessment (F-BCP-2). Weaker: **criterion** — a Rule/Invariant outside, an assessment input inside (role shift, not meaning shift; recorded as weak) | **PASS** — at least one term genuinely shifts, with an independently recorded UL collision as the specimen |
| (c) Cohesion | In-line: Observation · Finding · Verdict · Risk · Question are **all mode 2** (run/register-scoped, assigned) — 5 of 5 named members plus both contested ones share the identity regime; all enter the corpus as *recorded acts*; none is authoritative alone. Conformance and Completeness carry **no identity of their own** and ride Observation/Verdict identity (M3 Step 6; M4 Part 4 PASS) — consistent, not contrary. Cross-line: Ruling and Candidate are also mode 2 — a genuine cross-line affinity, but they are act-records of *authority*, not evidence-against-criteria | **PASS** — in-line affinity exceeds cross-line affinity for a majority of members; the Ruling/Candidate overlap is recorded as the boundary's weakest edge |

**Boundary Stability Test (Q4 d)** — remove one evidence *source* and re-derive: remove **M4** → the candidate survives on L1-12, L2-2, L3-3/4/5 (identity uniformity lost, cohesion argument weakened). Remove **item 1** → survives on M5's F6 classes + M4's mode-2 grouping + F-BCP-2, but the authority-seam quality drops sharply. **Result: survives every single-source removal; named dependency — the *quality* of this boundary depends on item 1 for its authority evidence and on M4 for its cohesion evidence.**

### 6.2 CBC-2 Knowledge Projection

| Probe | Evidence | Outcome |
|---|---|---|
| (a) Removal | Erasing the line collapses: "**nothing may cite a view as authority**" (c4 README) · "**derived artifacts are recomputed from sources, never hand-edited to disagree**" (item 1 Q4 `derive/regenerate`) · "**if a diagram and a frozen artifact ever disagree, the artifact wins and the diagram is corrected**" (item 1 Q7.2 row 1) · the hub rule "authority: derived — it points at sources, it is not itself the source of truth" | **PASS** — the strongest removal evidence in this run: four named rules, each meaningless without the boundary |
| (b) Linguistic | **ADR** names both a Decision and the document projecting it (item 1 §1.2 K-1 vs Q2.1 family A) · **Policy** names both a normative package and a projection pattern (L2-13) · the corpus states the separation as vocabulary law: "the ADR records the *decision*; the guide is the developer *how-to*" (P-6) · **authority** itself shifts sense ("authority: derived") | **PASS** |
| (c) Cohesion | In-line: **no independent semantic identity** (L4-8) — a property shared by every member and by nothing outside; regenerate-don't-edit lifecycle; non-authoritative standing; Guide step's representational-only identity (L4-4/L4-11 *by neglect*) is the predicted degenerate case. Cross-line affinity: low — no member shares identity regime, lifecycle, or authority standing with concepts outside | **PASS** |

**Boundary Stability Test** — remove **M4** → survives on L1-2/3 + L3-4 + P-6. Remove **item 1** → survives on M4 §1.4 `derive` + M5's *derives* class + M4's Guide-step liability. **Result: survives both removals with the boundary intact — the most stable candidate in the run.** Residual: **R-M6-5** (documentation-shadow) bites hardest here; the counter is that membership is defined by *identity and authority behavior*, not by folder or file type — a C4 diagram and a developer guide live in different roots and share membership, while two files in the same folder can fall on opposite sides.

### 6.3 CBC-3 Normative Governance

| Probe | Evidence | Outcome |
|---|---|---|
| (a) Removal | Erasing the line collapses: "**Authors propose; the authority adopts**" (ES-001.2) · "**No PROPOSED ADR may be treated as binding**" · the authorization chain ("authorizes *planning* an architecture, not the architecture itself") · the three precedence ladders | **PASS** |
| (b) Linguistic | "**approved**" shifts across three axes (authority act vs document status vs ticket state — L2-9/item 1 Q5.2) · "**Policy**" (L2-13) · "**Constraint**" as umbrella (L2-3) | **PASS** |
| (c) Cohesion | Member identity regimes: Rule **1**, Invariant **1**, Contract **1**, Candidate **2**, Charter grant **3**, Ruling **2**, Exception record **3**. Lifecycle behavior: Rule/Invariant/Contract are **standing norms** (authoritative class; change by version/re-issue or amend-additively); Charter grant is a **consumed permission**; Ruling is **terminal on issuance**; Candidate is a **probationary status**, not a content kind at all. Cross-line affinity: Ruling and Candidate share mode 2 *and* recorded-act behavior with CBC-1's members; Exception record shares conformance-evidence behavior with CBC-1. **For 4 of 7 members — a majority — cross-line affinity equals or exceeds in-line affinity** | **FAIL** (by the pre-declared condition) |

**Result: 1 of 3 probes failed → the candidate is NOT falsified (≥2 required), but it does not survive all three probes and therefore cannot meet the Q7 bar.** → **DEMOTED to candidate seam** (§7.5), with the gap named and the finer competing partition preserved. Stability test not run (a demoted candidate carries no confidence grade to modulate) — recorded as not-run, not as passed.

### 6.4 CBC-4 Work Management (adjacent)

| Probe | Evidence | Outcome |
|---|---|---|
| (a) Removal | Erasing the PKS ∥ work-management line collapses: "**lifecycle ≠ progress — two different concepts, never conflated**" (Process v1.0), a rule whose scope is *these objects only* (item 1 §1.2 K-13) · the 14-box DoD's five knowledge boxes, which are meaningful only if a work item is a *carrier of obligations toward* knowledge rather than knowledge itself · the derived-percentage rule ("NEVER written by hand") | **PASS** |
| (b) Linguistic | "**Verified**" (ticket state L-5 vs verification verdict K-15) · "**Approved**" (ticket state vs authority act) · "**Progress**" exists only work-side and is definitionally excluded from lifecycle | **PASS** |
| (c) Cohesion | In-line: PB/EPIC/ENG/AD share one namespace family (mode 1), one lifecycle vocabulary (L-5), one carrier class (boards, epic files, WBS rows), and none carries knowledge authority. Cross-line: work items *reference* knowledge obligations but share no identity scheme, no lifecycle vocabulary, and no authority behavior with knowledge kinds | **PASS** |

**Boundary Stability Test** — remove **M5** (source of the adjacency recommendation) → survives on L4-12 + L2-9 + the DoD evidence. Remove **item 1** → survives on M5 §4 + M4 §1.1/§2.2. **Result: survives both; no single-source dependency.**

**Phase E exit:** per-candidate probe table with declared conditions and outcomes ✅ · falsified candidates preserved (none falsified; one demoted, preserved in full) ✅ · stability-test dependencies named ✅.

---

## 7. Phase F — Confidence, Emergence Verification, and the Recommendation Set

### 7.1 Boundary Confidence (composition, not vote counting)

**Model (plan §5):** confidence = lens convergence **×** evidence quality (M0 rubric; M2 §10 epistemic classes — Observed outranks Derived outranks Synthesized) **×** cross-artifact consistency (informed by the Stability Test) **×** falsification survival. *Three weak lenses do not outrank two strong ones.*

| Candidate | Convergence | Evidence quality | Cross-artifact consistency | Falsification | **Boundary Confidence** |
|---|---|---|---|---|---|
| CBC-1 Assessment | 4 lenses; L1/L2/L4 strong, L3 moderate | Mostly **Observed/Measured** (mode-2 classification; the OQ-11 collision; P-8) with one **Derived** load-bearing step (M3's recorded-act reading) | Survives both single-source removals; two named dependencies | 3/3 probes | **Medium-High** |
| CBC-2 Projection | 4 lenses; L1/L4 strong, L2 strong, L3 moderate | **Observed** rules quoted verbatim; L4-8 is a stated corpus rule, not an inference | Survives both removals, boundary intact | 3/3 probes | **Medium-High** |
| CBC-3 Normative Governance | 3 lenses; L4 contradicts | Strong Observed evidence on L1; the cohesion contradiction is also Observed | Not assessed (demoted) | 2/3 — **cohesion FAIL** | **Low-Medium → demoted** |
| CBC-4 Work Management | 4 lenses | Namespace and vocabulary facts **Measured**; the adjacency recommendation is M5-**Derived**; M5 preserved a competing classification | Survives both removals | 3/3 probes | **Medium** |

**Ceiling applied to every grade — R-M6-6 confirmed:** no candidate is graded High. The four lenses are **not independent in the statistical sense**: L1 and L2 both draw primarily on item 1; L3 (M5) and L4 (M4) both descend from M0/M1, which descend from item 1. Convergence here means *four readings of one corpus by one lineage agree* — which is weaker than four independent confirmations. This is recorded as an execution finding (§11.2), not treated as a defect to repair mid-run.

### 7.2 Emergence Verification (per recommendation — why this and not that; what would flip it)

**CBC-1 Knowledge Assessment.**
*Why it emerged:* the corpus separates, in stated rules, the act of producing evidence-based judgment from both authorship and authority ("issued by a gate or review, never by the author"; P-8), gives that family a single identity regime (mode 2), and carries a UL collision (**Qualification**) precisely on the line. *Why the equally plausible alternative did not:* the finer recording ∥ judging split explains F-BCP-2 but orphans the seam and identity evidence, and M3 explicitly composes rather than separates the two. *What would flip it:* a rule-governed conformance/verification **record** with its own durable identity and lifecycle (this is also M3's reversal condition, first limb) would relocate the boundary and could revive the finer split. *Narrative-investment check (T-14/R-M6-7):* this candidate contains **Conformance**, a concept this program coined. The candidate does **not** rest on it — remove Conformance and Completeness entirely and the boundary still stands on Observation · Finding · Verdict alone (all three are Phase-I Observed concepts with pre-program instances). Recorded as the specific counter to the bias channel.

**CBC-2 Knowledge Projection.**
*Why it emerged:* four independent authority/lifecycle rules exist that are literally meaningless unless projections are a distinguishable class, and the class has a structural signature no other family has (no independent semantic identity). *Why the alternative did not:* a cross-cutting responsibility would not produce a shared identity regime, nor a single authority exclusion that spans unrelated topics. *What would flip it:* a governed derived artifact that carries **durable semantic identity of its own** and is citable as authority — one such instance and the class dissolves into the contexts it serves. *Documentation-shadow check (R-M6-5):* membership is decided by identity and authority behavior, not by file type or folder; the recommendation explicitly does **not** align with any root or directory.

**CBC-4 Work Management (adjacent).**
*Why it emerged:* a disjoint namespace family, a lifecycle vocabulary the model itself types as work-item-status rather than knowledge-status, and a rule ("lifecycle ≠ progress") scoped to these objects only. *Why the alternative did not:* the PKS-member reading explains the DoD coupling but leaves the namespace and vocabulary disjunction unexplained — and coupling is a relationship, not membership. *What would flip it:* evidence that work items participate in knowledge authority (e.g. a work item that *is* the record of a governance act rather than a carrier of obligations), or a unified lifecycle vocabulary spanning both.

### 7.3 The Q7 sufficiency bar, applied

| Q7 requirement | CBC-1 | CBC-2 | CBC-4 | CBC-3 |
|---|---|---|---|---|
| ≥3-lens convergence | ✅ 4 | ✅ 4 | ✅ 4 | ✅ 3 |
| Survives all three probes | ✅ | ✅ | ✅ | ❌ cohesion |
| Boundary Confidence ≥ Medium, grading shown | ✅ Medium-High | ✅ Medium-High | ✅ Medium | ❌ Low-Medium |
| Emergence Verification passed | ✅ | ✅ | ✅ | n/a |
| Explicit UL statement | ✅ §7.4 | ✅ §7.4 | ✅ §7.4 | n/a |
| Named evidence per included concept | ✅ §8 | ✅ §8 | ✅ §8 | preserved §7.5 |
| A recorded competing partition | ✅ §5.1 | ✅ §5.2 | ✅ §5.4 | ✅ §5.3 |
| **Outcome** | **RECOMMENDED** | **RECOMMENDED** | **RECOMMENDED** | **DEMOTED — candidate seam** |

### 7.4 Recommended boundaries (hypotheses until the Authority disposes, per candidate, at G-M6-3)

> **CBC-1 — Knowledge Assessment.** *Purpose:* record evidence about the knowledge system and its objects, evaluate it against declared criteria, and issue judgments that feed authority without being authority.
> **Ubiquitous language (this context):** Observation (a recorded evidence entry, carrying an epistemic class) · Finding (an evidenced defect, id-scoped to its run by design) · Verdict (a categorical judgment against criteria — PASS · FAIL · WARN · INCONCLUSIVE · EMERGENT · CERTIFIED — issued by a gate or review, never by the author) · degree (a measured magnitude; a derived Observation, *not* a Verdict — F-BCP-2) · criterion (a Rule or Invariant, *used here*, owned elsewhere) · **"Qualification" here means the verification instrument, never the promotion-ladder stage** (the second sense belongs outside this context; the canonicalization is OQ-PKS-11's, not this artifact's).
> **Members with named evidence:** §8. **Contested membership:** Risk · Question · Exception record — recorded, not assigned. **Confidence:** Medium-High. **Operational note:** whole-system conformance assessment is **Absent** (M3/M5); this boundary describes a capability the domain names and does not yet perform.

> **CBC-2 — Knowledge Projection.** *Purpose:* render governed knowledge into consumable forms — teaching, navigation, serialization, visualization — without acquiring identity or authority of its own.
> **Ubiquitous language:** projection · serialization · view · guide step · index/hub/package · regenerate ("recomputed from sources, never hand-edited to disagree") · **"authority: derived"** · "the artifact wins and the diagram is corrected". **"ADR" in this context names the document; the Decision it projects lives elsewhere.**
> **Members with named evidence:** §8. **Confidence:** Medium-High. **Standing liability inside the boundary:** Guide step's representational-only identity (M4 §1.3, mode 3\*) — a *by-neglect* carrier-linkage, distinct from Charter grant's *by-design* one.

> **CBC-4 — Work Management: recommended ADJACENT, outside the PKS boundary.** *Purpose (in the adjacent domain):* track units of work and their progress. *Relationship to PKS (stated as an observation, not modeled — relationship modeling is M7's):* the 14-box Definition of Done makes five knowledge obligations conditions of a work item's completion; the coupling is a dependency, not membership.
> **Ubiquitous language (theirs, not ours):** work item · ticket · WBS row · board · progress (derived, never hand-written) · Designed → Approved → In Development → Implemented → Verified → Released. **"Approved" and "Verified" here are work states, not authority acts or verdicts.**
> **Confidence:** Medium. **This discharges the M5-handed pre-staged boundary question** — recommendation only; the boundary decision is the Authority's (M5 §4 routed it to "M6/ARB").

### 7.5 Candidate seams (below the bar — preserved with the gap named)

| Seam | What was present | **What was missing** (the gap) | Reopening trigger |
|---|---|---|---|
| **CBC-3 Normative Governance** (demoted) | 3-lens convergence; removal and linguistic probes passed on strong Observed evidence; the entire authority seam | **Cohesion.** Members split across all three identity modes and three lifecycle shapes; 4 of 7 members show cross-line affinity ≥ in-line affinity. L4 actively contradicts the grouping | Either (i) evidence that authorization acts and standing norms share an identity or lifecycle regime, or (ii) a Phase-B-style collection run under the **finer** partition (Norm Custody ∥ Authorization Acts), which is the reading L4 supports. The finer partition is preserved in full at §5.3 and was **not** promoted, because it was constructed in Phase D rather than surfaced in Phase B |
| **Expressed-knowledge core** (Decision · Term · Model element · Contract · Question) | Implied as a *region* by the complement of three well-evidenced edges | Any evidence of an **internal** boundary: one lens only (L3-1), and L4 splits the group | A UL shift, a seam statement, or an identity-regime split *within* the region. **Deliberately left unpartitioned** — R-M6-2 (elegant-partition seduction) is the named risk this restraint answers |
| **Identity & register(ns) discipline** | Strong L4; ownership vacuum (L1-14) | No responsibility class; and the candidate's own name is a three-sense collision (F-BCP-4) — naming a boundary with the corpus's contested word would deepen the drift | A UL ruling on "register", plus a named owner for identity discipline |
| **Consistency / contradiction management** | Strong seam evidence (L1-15/17/18) | **Operationally Absent** — no mechanism, no owner (OQ-PKS-3), no cadence. A capability gap is not a boundary | The first operating contradiction-detection mechanism, or a named owner with a cadence |
| **Contract / boundary specification** | Three consistent weak signals | No seam statement; validated scope is narrow (event contracts only) | A non-event contract instance product-side (M1's recorded extension hypothesis) |

### 7.6 Rejected partitions (preserved with rationale and reopening triggers)

| Rejected partition | Rationale | Reopening trigger |
|---|---|---|
| **Assessment split into Evidence Recording ∥ Judgment** (§5.1) | Explains L2-11 only; orphans the seam (L1-12) and identity (L4-2) evidence; M3 composes rather than separates | An operating mechanism that records conformance observations *without* any assessment act consuming them |
| **Projection dissolved into a cross-cutting responsibility** (§5.2) | Cannot explain the shared identity regime or the single cross-topic authority exclusion | One governed derived artifact with durable semantic identity, citable as authority |
| **Work Management inside PKS as Supporting** (§5.4, M5's preserved alternative) | Explains the DoD coupling but not the disjoint namespace or lifecycle vocabulary; coupling ≠ membership | A work item that *is* a governance-act record; or a unified lifecycle vocabulary spanning knowledge and work objects |
| **Boundaries aligned to responsibility classes (F6)** | Tested and **falsified as a partition**: the *derives* class is cut in two by L1 and L4 (assessment-derivations vs representation-projections), and *records* is cut between assessment records and authority-act records | Evidence that a responsibility class predicts identity or authority behavior — which would make L3 boundary-preserving |
| **Boundaries aligned to significance grades (F1)** | Tested and **falsified**: Core spans all three recommended boundaries (Verdict/Conformance → CBC-1; Rule/Invariant/Ruling/Candidate/Charter grant → the demoted seam; Decision/Term → the unpartitioned core). F1 is boundary-**orthogonal** | None foreseeable; significance grades a concept's differentiating power, not its locality |

**Phase F exit:** every recommendation meets the full Q7 bar ✅ · everything below the bar demoted with the gap named ✅ · no recommendation without its emergence statement ✅.

---

## 8. Evidence Matrix (commission deliverable 3)

*Every included concept, its named evidence, and the lens that carried it.*

| Context | Concept | Named evidence (artifact + row) | Lens | Class |
|---|---|---|---|---|
| CBC-1 | **Observation** | M0 G-10 · M1 Part A row Observation · M4 §1.1 (mode 2, register-scoped) · M5 §4 (records, Supporting, K6) · F-BCP-2 (the degree is a derived Observation) | L2/L3/L4 | Observed |
| CBC-1 | **Finding** | M0 G-5 · item 1 §1.2 K-5 ("≥6 F-spaces, run-scoping is *stated design*") · M4 §1.1 (mode 2) · M5 §4 (records) | L3/L4 | Measured/Observed |
| CBC-1 | **Verdict** | M0 G-15 · item 1 §1.2 K-15 (token set; "issued by a gate or review, never by the author") · M4 §1.1 (mode 2, run-scoped) · M5 §4 (derives, **Core**) | L1/L2/L3/L4 | Observed |
| CBC-1 | **Conformance** (derived assessment) | M3 Step 6 (kind B, over per-object observations) + F-BCP-2 amendment · M5 §4 (derives, Core-while-Absent) · M4 Part 4 (no new identity scheme; rides Observation/Verdict) | L3/L4 | Derived (Medium) |
| CBC-1 | **Completeness** (derived assessment) | M2 §5 (state-assessed, five dimensions with per-dimension standing) · M5 §4 (derives, Supporting) | L3 | Derived |
| CBC-1 | *contested:* Risk · Question · Exception record | M4 §1.1 (all mode 2 / intrinsic-composite) · M5 §4 (records) · L2-8 (OQ- drift) | L3/L4 | recorded, unassigned |
| CBC-2 | **Guide step** | M0 G-14 (6 of 31 areas conform) · M4 §1.1 mode 3\* + §1.3 (representational-only identity) + Amendment 3 (*by neglect*) · M5 §4 (derives, Supporting) · P-6 | L1/L2/L3/L4 | Measured/Observed |
| CBC-2 | **ADR (projection pattern)** | M1 Part B (validated as a projection pattern, not a concept) · M5 §4 (derives) · item 1 Q2.1 family A | L2/L3 | Observed |
| CBC-2 | **Policy (projection pattern)** | M2 §3 Step 5 (four constitutional policies; composition corrected to {Invariants + Rules + scope}) · M5 §4 (derives) | L2/L3 | Observed |
| CBC-2 | **Views / C4 / indexes / hubs / packages** | item 1 Q2.1 family G · Q3.1 (`describes/documents`, "nothing may cite a view as authority"; hub "authority: derived") · M4 §1.4 (`derive/regenerate`: no independent semantic identity) | L1/L4 | Observed |
| CBC-4 | **Work item** | M0 G-13 (boundary-flagged) · M1 Part A row Work item · item 1 §1.2 K-13 (PB/EPIC/ENG/AD; "lifecycle ≠ progress applies to these only") · Q5.1 L-5 · M4 §1.1 (mode 1, own namespace) + §2.2 (L-5 typed as a work-item status vocabulary) · M5 §4 (Generic, adjacent-domain candidate) | L1/L2/L3/L4 | Measured/Observed + M5-Derived |

**Concepts deliberately unassigned** (in the unpartitioned region, §7.5): Decision (M0 G-1 · M5 §4 expresses/Core) · Term (G-7 · expresses/Core) · Model element (G-9 · expresses/Supporting) · Contract (G-8 · governs/Supporting) · Rule · Invariant · Ruling · Candidate · Charter grant (all in the demoted CBC-3 seam). **No concept was placed without evidence, and none was silently dropped: all 21 M5 items are accounted for** — 5 recommended into CBC-1, 4 into CBC-2 (3 concepts + the views family), 1 into CBC-4, 7 in the demoted seam, 4 in the unpartitioned core (Decision, Term, Model element, Contract), and 3 contested (Risk, Question, Exception record) recorded against CBC-1 without assignment.

---

## 9. Surfacing Register (commission deliverable 6) — surfaced with impact statements, **none resolved**

| # | Question | Where it bit | Impact statement | Status |
|---|---|---|---|---|
| **OQ-PKS-2** | Which designed system (AKB, EKP) — if either — is the incumbent to evolve? | **BIT, exactly as predicted.** CBC-2's membership includes EKP portal/hubs/packages and AKB registry artifacts; L1-16 (designed vs practiced disjoint) is load-bearing seam evidence | If EKP is the incumbent, its typed-relationship model becomes a *specification* of CBC-2's interior and the "0 instances" facts become conformance findings inside CBC-1. If the AKB is, a different vocabulary applies. If neither, both families are **inhabitants of CBC-2 with no special standing**, which is how this discovery treated them — per the accepted executive finding, **discovery proceeded on the practice-based system** | **SURFACED to ARB**; discovery unblocked |
| **OQ-PKS-7** | Do the three roots (`docs/`, `architecture/`, `engineering/` bindings) form one corpus or three? | **BIT, exactly as predicted.** The demoted CBC-3 seam contains Rules that are **platform-hosted (`engineering/`) but product-binding** (K-2: ES-00n.m) via `binds` (L1-9); CBC-2 spans `docs/` and `architecture/` | If one corpus: `binds` is an intra-domain relationship and the Norm Custody reading gains a home. If three: `binds` is an inter-domain contract with the Engineering Governance domain, and part of the demoted seam's content is **outside** PKS — which would materially change the finer partition's viability | **SURFACED to ARB**; territorial alignment recorded as observation, **placement not decided** |
| **OQ-PKS-3** | Who owns cross-artifact consistency, and on what cadence is contradiction detection run? | Reinforced: CBC-1 recommends a boundary around an assessment capability **with no named owner** (L1-14) | Choosing CBC-1 makes the ownership vacuum the boundary's most consequential open dependency (M3 already recorded this) | Reinforced; ARB-owned |
| **OQ-PKS-11** | Is "Qualification" one concept or two? | Reinforced and **now load-bearing**: the collision is the primary linguistic-probe specimen for CBC-1 | If the ARB canonicalizes the term to a single sense, CBC-1's linguistic probe loses its strongest specimen (it retains the F-BCP-2 Observation/degree shift) — the boundary would survive at reduced evidence quality | Reinforced; ARB-owned. **Not resolved here** |
| **OQ-PKS-4** | Is the authoritative/informational polarity a rule? | Reinforced: L1-1 underpins the whole L1 lens; O-M5-2 (L3-6) links it to the *governs* class | If polarity is not a rule, L1's evidence weakens across all candidates — and L3-6 becomes evidence of **lens correlation** rather than convergence | Reinforced; ARB-owned |
| **OQ-PKS-1 / OQ-PKS-9** | Identity scheme; kind→lifecycle-vocabulary mapping | Used as L4 and L2 evidence throughout | Both remain open; CBC-1 and CBC-2 depend on M4's *preferred explanatory model* standing, not on a settled ontology | Reinforced; ARB-owned |

**Prediction record:** the plan predicted OQ-2 and OQ-7 would bite at M6 after remaining unbitten through M0–M5 (four-for-four). **Both bit at M6.** Recorded as a methodology datum (§11.2).

---

## 10. Threat Analysis (commission deliverable 7)

### 10.1 Planned M6 risks — status after execution

| # | Risk | Status |
|---|---|---|
| R-M6-1 | Classification-echo (L3 dominance would make M6 restate M5) | **Mitigation held.** Two of three recommendations cut *across* L3 classes; L3 alone named no candidate; the *derives* class was split by other lenses |
| R-M6-2 | Elegant-partition seduction | **Mitigation held, visibly:** the expressed-knowledge core was left unpartitioned rather than completed into a tidy four-context map |
| R-M6-3 | OQ-2/OQ-7 entanglement | **Occurred as predicted; contained.** Both surfaced with impact statements; no discovery step waited on them |
| R-M6-4 | T-2 continuation (same-lineage reviewer) | **Carries.** G-M6-2 is a Critical Review with T-2 declared; external review remains the unclaimed higher tier |
| R-M6-5 | Documentation-shadow (a candidate mirroring repository structure) | **Live, highest at CBC-2.** Countered by defining membership through identity/authority behavior and by the Stability Test; residual recorded |
| R-M6-6 | Correlated-lens risk | **CONFIRMED, not merely mitigated** — see §7.1 and §11.2. All confidence grades capped at Medium-High as a result |
| R-M6-7 | Narrative-investment carry (T-14) | **Addressed per recommendation** via Emergence Verification; CBC-1 shown to stand without the program-coined concept |
| R-M6-8 | Register-sense contamination | **Mitigated as planned** — disambiguation convention declared before collection; used throughout |

### 10.2 New threats

| # | Threat | Mitigation applied | Residual |
|---|---|---|---|
| **T-15** | **Single-executor phase independence** — Phase B collection, Phase C naming, and Phase D competition were performed by one executor in one session; "collection before candidates" cannot be externally verified | Inventories derived per-source with citations, so a reader can re-derive them independently of the candidates | An executor's awareness of later phases cannot be excluded; compounds T-2/T-5 |
| **T-16** | **Complement-boundary asymmetry** — three well-evidenced edges imply a residual region whose interior is unevidenced; a residue can look artificially cohesive merely by being left over | The residue is explicitly recorded as *unpartitioned*, not as a context; §7.5 names what evidence would partition it | If the residue is in fact several contexts, this discovery under-partitions the domain — the honest failure direction, but a failure direction nonetheless |
| **T-17** | **Absent-capability boundaries** — CBC-1 encloses a capability (whole-system conformance assessment) that **operates nowhere** | Operational status stated inside the recommendation; classification ≠ operationality discipline carried from M3/M5 | A boundary around an absent capability is a hypothesis about a future system as much as a description of the present one |

**T-1..T-14 carry unchanged** from M1–M5, with T-1 (single corpus), T-2 (evaluator non-independence) and T-14 (narrative investment) the most load-bearing here.

---

## 11. Recording Ledgers (Execution Charter §8 — two ledgers, never merged)

### 11.1 Ledger A — Domain Discovery

1. Three boundaries recommended (CBC-1, CBC-2, CBC-4); one demoted (CBC-3); the expressed-knowledge core left unpartitioned.
2. **The `derives` responsibility class is not one thing** — assessment-derivations and representation-projections separate cleanly on identity and authority behavior. This was invisible to M5, which had no identity lens in view when classifying.
3. **A structural signature discovered:** membership in CBC-2 is predicted by *absence of independent semantic identity*. The domain distinguishes knowledge from its renderings by identity, not by topic.
4. **The strongest boundary evidence in the corpus is negative-space evidence:** rules that would be meaningless if a boundary did not exist ("nothing may cite a view as authority").
5. **A UL collision recorded before this discovery (OQ-PKS-11) sits exactly on a discovered boundary.** Independent corroboration of a boundary the corpus had already felt without naming.
6. **Two ownership vacuums (L1-14) fall inside or on the edge of CBC-1** — the domain has a boundary around an assessment capability that no role owns.
7. Unresolved domain questions carried forward: the interior of the expressed-knowledge core · whether Ruling/Candidate belong with norms or with assessment records · whether Contract is a PKS concept or an interface concern of an adjacent domain · the home of Question and Exception record.

### 11.2 Ledger B — Methodology Observations *(recorded, **not resolved**; routed to the MCA/CDR — Authority condition 5)*

| # | Observation |
|---|---|
| B-1 | **R-M6-6 is confirmed by execution, not merely anticipated.** The four lenses share a root corpus and a lineage (L1/L2 ← item 1; L3/L4 ← M4/M5 ← M0/M1 ← item 1). "Lens convergence" therefore measures *internal coherence of one lineage's reading*, not independent confirmation. The plan's evidence-quality weighting handled it, but the SDM currently has no instrument for **measuring** lens independence — a candidate gap |
| B-2 | **A Phase-A ordering conflict exists between the bootstrap and the plan.** The plan requires OQ triggers transcribed "before any evidence is read"; the bootstrap requires the canonical artifacts loaded first. In a single-executor session these cannot both hold literally (§1.3) |
| B-3 | **The Q7 bar's "survives all three probes" is stricter than the falsification rule's "≥2 failures falsifies".** A candidate can be *not falsified* yet unable to reach recommendation — CBC-3 landed exactly in that band. This is arguably correct behavior, but the SDM never names the band; the demotion vocabulary ("candidate seam") had to carry it |
| B-4 | **Phase D can generate a partition that Phase C never collected.** The finer Norm-Custody ∥ Authorization reading resolves CBC-3's cohesion failure, but promoting it would bypass Phase B/B½ for its own members. The SDM has no defined route for a Phase-D-born partition to re-enter the pipeline; it was preserved rather than promoted |
| B-5 | **The trigger-prediction instrument worked.** OQ-2/OQ-7 were predicted at M1 to bite at M6, stayed unbitten through four work packages, and bit at M6. Pre-identified surfacing triggers are cheap and appear to discriminate — evidence for the instrument, from one instance |
| B-6 | **Boundary Stability Test operated as a genuine discriminator, not a formality** — it produced *different* results per candidate (CBC-1 survives with a named quality dependency; CBC-2 survives intact; CBC-4 survives cleanly) |
| B-7 | **A records-convention collision** between the per-day session-log rule and the record-closure seal (§1.1). Reported, not repaired |
| B-8 | **The three-orthogonal-tests discipline was load-bearing again** — CBC-1 encloses an Absent capability (T-17). Classification-vs-operationality separation is doing real work across M3, M5, and now M6 |
| B-9 | **M5 §6's open question is answered by execution evidence:** responsibility classes (F6) seed candidates but are **not boundary-preserving**; significance grades (F1) are **boundary-orthogonal**. Recorded as a finding about the framework's use in discovery, not as a change to M5 |

**Nothing in Ledger B was acted upon during execution.** Per the Continuation Commitment: a better methodology idea is evidence, never a reason to stop.

---

## 12. Self-Verification (commission deliverable 8)

- Evidence-driven discovery, **never architectural decomposition** — every candidate traces to inventory items with artifact+row citations ✅
- **No boundary drawn by preference:** one candidate demoted by its own pre-declared probe; one region deliberately left unpartitioned; two framework-aligned partitions explicitly falsified ✅
- Strategic DDD only — **zero tactical DDD** (no aggregate, entity, value object, repository, service, event design, API, schema, or technology appears) ✅
- **No context map, no relationship patterns, no upstream/downstream** — relationship modeling is M7's; the DoD coupling in §7.4 is stated as an observation and explicitly not modeled ✅
- Plan executed exactly as written: four lenses unchanged, no lens added or reweighted; Phases A–F with their exit criteria evidenced in-line ✅
- **Falsification-first:** probes' pass/fail conditions declared before running; one FAIL recorded and honored rather than reasoned away ✅
- Every recommendation carries convergence, evidence quality, stability, probe outcomes, competing partition, UL statement, confidence grading, emergence statement, and a reopening trigger ✅
- Rejected partitions and demoted candidates preserved in full, with rationale and triggers ✅
- Uncertainty preserved, not replaced by invented certainty: **no High confidence grade issued**, with the reason stated (R-M6-6) ✅
- OQ-2/OQ-7 surfaced with impact statements, **not resolved**; four further OQs reinforced, none answered ✅
- No governance decision taken · no authority act performed · no methodology change enacted (nine methodology observations recorded and routed) ✅
- Terminology frozen — no concept renamed; the three-sense "register" was **disambiguated in use**, not canonicalized (Term maintenance remains ER-06's) ✅
- M5-handed pre-staged boundary question **discharged as a recommendation**, with the decision routed to the Authority ✅
- All 21 M5 items accounted for; nothing silently dropped ✅
- **STOP discipline:** no disposition, no consolidation, no M7 input package staging beyond what the plan lists, no SDM/EOP extraction ✅

---

## 13. M6 Checkpoint Package (commission deliverable 9)

**For the M6 Critical Review (G-M6-2 — Critical Review class; T-2 pre-declared and hereby declared: the executor is the method's author lineage, so independence is procedural, not personal).**

| Item | Location |
|---|---|
| 1. Discovery report | this artifact, §§1–7 |
| 2. Candidate register | §4.2 (named candidates) + §4.3 (seams-to-watch) + §7.5 (candidate seams) |
| 3. Evidence matrix | §8 |
| 4. Falsification log | §6 (probes with pre-declared conditions) + §7.6 (rejected partitions) |
| 5. Confidence assessment | §7.1 (Boundary Confidence composition) + §7.2 (Emergence Verification) |
| 6. Open-question register | §9 (Surfacing Register — OQ-2 and OQ-7 bit) |
| 7. Threat updates | §10 (R-M6-1..8 status; T-15/T-16/T-17 new; T-1..T-14 carry) |
| 8. Self-verification | §12 |
| 9. Checkpoint package | this section |

**Recommended review focus (offered as input; the reviewer sets its own agenda):** (i) is CBC-3's cohesion failure correctly *not* rescued by the finer partition? (ii) is CBC-2 a boundary or a documentation shadow (R-M6-5)? (iii) is leaving the expressed-knowledge core unpartitioned honest restraint or under-discovery (T-16)? (iv) does confirming R-M6-6 change how convergence should be weighed?

**Gates: G-M6-2 (Critical Review) → G-M6-3 (per-candidate Authority disposition) → G-M6-4 (completion).** None is performed here.


---

### Amendment 5 — M6R-1 and M6R-2 *(discovery-integrity review, 2026-07-30; both EVIDENCE-NEUTRAL folds, MCR-4 class 1)*

**Standing unchanged: nothing in §§0–13 has been edited.** Both corrections are stated here, in the same *"reads, corrected"* form Amendment 1 used. **Neither changes an evidence item, a probe result, a confidence grade, or a recommendation** — the CBC-3 re-disposition was already taken *on the corrected record*, so these folds propagate a correction the Authority has already disposed. **No reopening condition is triggered and none is invoked.**

#### 5(a) — M6R-1: Amendment 1's correction list completed

**Finding:** Amendment 1 named **§7.5** as the corrected site. **The same superseded claim stands, uncorrected and unnamed, at two further places.** Because §14's standing is *"the original stands as history and the amendment states the correction"*, that standing only functions if the amendment names **every** superseded statement. **Completed here:**

| Site | As printed | **Reads, corrected** |
|---|---|---|
| **§7 — the confidence table's CBC-3 row** | *"2/3 — **cohesion FAIL**"* | **"2/3 — cohesion probe PASS-marginal (Amendment 1(c): 3 of 7, the FAIL condition is NOT met); the demotion holds on the stability-test failure at 1(d), not on the cohesion probe"** |
| **§12 — Self-Verification, falsification-first line** | *"one FAIL recorded and honored rather than reasoned away ✅"* | **"one probe FAIL recorded and honored as printed; on restatement (Amendment 1(c)) the cohesion FAIL condition was NOT met, and the demotion's grounds moved to the stability test (1(d)). The falsification discipline holds in both records: the pre-declared condition was never relaxed to save the candidate ✅"** |

**Recorded because it is the substantive point of the fold: the *discipline* claim in §12 survives the correction, and the *evidence* claim does not.** *The probe's pre-declared condition was never relaxed — what changed is which probe carried the demotion. §12's ✅ is therefore still earned, on restated grounds.*

#### 5(b) — M6R-2: the Status field's programme-position clause

**Finding:** the Status field asserts *"M6 Checkpoint Package **ready** (§13). **STOP at G-M6-2**"* — a claim about the **programme's position**, which has since passed G-M6-2, the Authority Disposition, Consolidation, the MCA, the CDR, M7, M8 and per-artifact promotion.

**Split, because half of the Status must stand:** *"no disposition, no consolidation, no M7, no SDM/EOP extraction performed"* is a **true statement about what the M6 execution commission did**, and is properly historical. **Only the programme-position clause is superseded.** Corrected **in the metadata block** rather than here — metadata has been maintained by every prior amendment, while **§§0–13 have not been edited by any of them.** *Treating the metadata block and the body differently is not an inconsistency: the standing protects the discovery record, not the filing.*

---

*Traceability: executes WP M6 under the issued M6 Execution Commission (session log 2026-07-28 ★; package `PKS_Phase_II_M5_Consolidation_and_M6_Commission_Package.md` §5), authorized by Authority Disposition GO (`PKS_Phase_II_M6_Execution_Readiness_Review.md` §13, conditions 1–5) · method: `.claude/plans/shiny-hopping-nest.md` (APPROVED) Phases A–G, lenses L1–L4, Phase B½, three probes + Stability + Emergence, Q7 bar · Execution Governance: M6 Strategic Discovery Execution Charter (`PKS_Phase_II_M6_Fresh_Session_Prompt.md` §3.1) + the discovery charter (`.claude/CONTEXT.md`) · inputs: item 1 (Q0/Q2/Q3/Q5/Q6/Q7/§1.2) · M0 glossary + rubric · M1 register + Part D · M2 (C-1/C-3/C-4, OQ-5, §10) · M3 (Steps 3.5–8 + F-BCP-2) · M4 (Parts 1–4 + amendments) · M5 as consolidated (§§3–6, §9, amendments) · batch checkpoint F-BCP-4 · precedent `EPIC-002_Bounded_Context_Discovery.md`.*

> **M6 execution is complete. The M6 Checkpoint Package is ready. Awaiting M6 Critical Review and Authority Disposition.**

**STOP.**

---

## 14. Checkpoint Amendments (ordered at G-M6-3; applied by the Bounded Evidence Restatement / Fold Commission, 2026-07-28)

**Standing:** these amendments are **additive, minimal, and traced** (the M5 Checkpoint Amendment precedent). **Nothing above this section has been edited** — where an amendment corrects a printed statement, the original stands as history and the amendment states the correction. Amendment 1 is the **evidence-affecting fold** (F-M6CR-1), executed under the Bounded Evidence Restatement commission — commissioned solely to repair the auditable record for CBC-3, per the Authority Disposition §3 (evidence repair is never consolidation work). Amendments 2–4 are **editorial folds** (no evidence changed).

**What this commission did NOT do (scope boundary):** no CBC-3 re-disposition (that is the Authority's next act) · no re-run of Phases B–D · no promotion of the finer Norm-Custody ∥ Authorization partition (preserved-not-promoted stands; its re-entry path remains B-4's MCA question) · no Phase-F discovery execution for CBC-3 (see Amendment 1(f)) · no OQ resolved · no methodology change (the member-set fixation gap remains the Critical Review's MCA observation).

### Amendment 1 — F-M6CR-1: the CBC-3 cohesion probe, restated on the declared member set

**Finding (Critical Review §11, quoted):** *"CBC-3 cohesion probe: member set probed ≠ member set declared (Contract in / Policy-as-content out; Contract triple-placed across §§4.2/6.3/7.5); '4 of 7 majority' enumerates only 3 members. The pre-declared FAIL condition is not fully evidenced by the printed record."*

#### 1(a) Member-set correction

The declared §4.2 membership governs: **Rule · Invariant · Charter grant · Candidate · Ruling · Policy-as-content · Exception record** (Exception record declared contested). **Contract is removed from the probe** — it was never a declared member; its inclusion in §6.3 was the recording defect. **Policy-as-content is restored** to the probed set.

#### 1(b) Contract's placement, fixed to one bucket

**Contract's single recorded placement is the unpartitioned expressed-knowledge core** (as §7.5 and §8 already record). The marked contested alternate is the recorded open question (Ledger A item 7): *whether Contract is a PKS concept or an interface concern of an adjacent domain* — a question, not a second placement. Contract's appearance in §5.3's Norm Custody half is **hypothesis-internal** (a member of a preserved-not-promoted Phase-D partition, explicitly so labeled) and is not a placement. Its appearance in the §6.3 probe was the error this amendment corrects.

#### 1(c) The restated probe (pre-declared condition unchanged, restated verbatim: FAILS if *cross-line affinity equals or exceeds in-line affinity for a majority of member concepts*)

Affinity is assessed per member on the probe's three declared dimensions — **identity regime · lifecycle shape · authority behavior** — against M4 §1.1 (identity modes independently verified by the Critical Review, V-4) and the lifecycle characterization the review found well-grounded (§7 of the review). The three cross-affinity enumerations the original probe named (Ruling · Candidate · Exception record) are **preserved** — the review verified the underlying data as real; what was unevidenced was the fourth member and the set itself.

| Member | Identity regime | Lifecycle shape | Authority behavior | **Call** |
|---|---|---|---|---|
| **Rule** | Mode 1 (in-line partner: Invariant) | Standing norm (version/re-issue or amend-additively) — in-line | Normative; violation = governance violation — in-line | **IN-LINE** (3/3) |
| **Invariant** | Mode 1 (in-line partner: Rule) | Standing norm — in-line | Normative; violation = *defect* (L2-5) — an in-line-internal distinction from Rule, not a cross-line affinity | **IN-LINE** (3/3) |
| **Charter grant** | Mode 3 *by design* (in-line partner: Exception record, mode 3). Cross-line shape-resemblance to Guide step (mode 3\*) is **discounted by M4 Checkpoint Amendment 3 itself**: carrier-linkage *by design* vs *by neglect* are "two different phenomena sharing a shape" (L4-11) | Consumed permission ("permits the next activity and nothing else") — **unique**; affine with neither side | `authorizes` (L1-8) — the authority seam itself; squarely in-line | **IN-LINE** — *this adjudicates the review's "plausible unnamed fourth": on enumeration, the cross-line case rests on a shape-resemblance the accepted M4 record explicitly distinguishes, while authority behavior binds the member in-line* |
| **Candidate** | Mode 2 — cross (five CBC-1 members share it; in-line only Ruling) | Probationary status pending adoption; enters the corpus as a recorded act (shared with CBC-1's entry mode) — cross-leaning | Object of adoption ("Authors propose; the authority adopts"); an authority-record, not evidence-against-criteria (§6.1's own distinction) — in-line | **CROSS-AFFINE** (identity + entry-behavior; preserved from the original enumeration) |
| **Ruling** | Mode 2 — cross | Terminal on issuance — parallels Verdict (issued, terminal, run-scoped) — cross | Creates governance (an authority act) — in-line | **CROSS-AFFINE** (2 of 3; preserved) |
| **Policy-as-content** | **No M4 §1.1 row — identity mode unevidenced** (recorded as a gap, not assumed; its constituents are mode-1 norms) | Standing norm (the four constitutional policies are binding; change forward-only) — in-line | Normative package {Invariants + Rules + scope} (M2 §3 Step 5) — in-line | **IN-LINE** (on both evidenced dimensions; identity gap recorded) |
| **Exception record** | Mode 3 intrinsic-composite (in-line partner: Charter grant) — in-line | Terminal record of an approved deviation — record-shaped, cross | Conformance-evidence behavior (a recorded deviation is evidence — shared with CBC-1), against the in-line reading that the *approving act* is authority's; the concept is declared contested in §4.2 | **CROSS-AFFINE** (2 of 3; preserved) |

**Tally: cross-line affinity ≥ in-line affinity for 3 of 7 members — Candidate, Ruling, Exception record. 3 of 7 is not a majority. The pre-declared FAIL condition is NOT met.**

**Restated outcome: PASS — marginal, and recorded as such.** The pass does not assert homogeneity: the members genuinely span all three identity modes and three lifecycle shapes (Observed; M4-verified — the heterogeneity the original probe detected is real). It asserts only that, enumerated per member on the declared set, cross-line affinity does not reach the pre-declared majority. The recorded tension with §6.1 (which resolves the same Ruling/Candidate overlap in CBC-1's favor on behavior) stands on both sides as each boundary's weakest edge.

**Correction to §7.5:** the demoted-seam row's "4 of 7 members show cross-line affinity ≥ in-line affinity" reads, corrected: **3 of 7**; the gap statement "Cohesion (probe FAIL)" reads, corrected: **cohesion heterogeneity real but sub-majority; probe PASS on restatement — the gap has moved to confidence (see 1(e))**.

#### 1(d) Boundary Stability Test — now due (OBS-2) and run

The restated 3/3 probe result revives the candidate to stability-test eligibility (Authority Disposition §2.3 condition 2).

- **Remove M4** → the cohesion contradiction and the identity-mode data vanish; the candidate **survives** on L1 (authority seam) + L2 (violation semantics, "approved" three-axes, Policy) + L3 (*governs* class) — 3 lenses.
- **Remove item 1** → **the entire L1 lens vanishes** (every seam item L1-1..L1-18 is item-1-sourced); survivors are L2-5/L2-3 (M2), L2-13 (M2/M5), part of L2-9 (M4 §2.2), and L3-2/L3-6 (M5) — **two lenses. The candidate falls below the ≥3-lens naming bar and does not survive this removal.**

**Result: CBC-3 is the only candidate in the run that fails a single-source removal.** Its authority-seam evidence — the strongest evidence it has — is single-sourced to item 1.

#### 1(e) Confidence, re-derived (composition model of §7.1, unchanged)

Convergence **3 lenses** (the bar minimum; L4 still does not support the grouping) × evidence quality (L1 strong Observed, but single-sourced) × cross-artifact consistency (**fails the item-1 removal** — unique in the run) × falsification (3/3 restated; cohesion **marginal** at 3-of-7 cross-affine). Against the run's own calibration — CBC-4 holds Medium with four lenses, both removals survived, and one Derived load-bearing step — CBC-3 sits below Medium on every differentiating factor. **Boundary Confidence: Low-Medium.**

#### 1(f) The Q7 row, re-derived

| Q7 requirement | CBC-3, restated |
|---|---|
| ≥3-lens convergence | ✅ 3 |
| Survives all three probes | ✅ (restated — 1(c)) |
| Boundary Confidence ≥ Medium, grading shown | ❌ **Low-Medium** (1(e)) |
| Emergence Verification passed | **Not produced** — Phase F never ran for the demoted candidate; producing it now would be discovery execution, outside this commission's evidence-repair scope |
| Explicit UL statement | **Not produced** — same |
| Named evidence per included concept | ✅ §8 / §7.5 |
| A recorded competing partition | ✅ §5.3 |
| **Outcome** | **REMAINS BELOW THE RECOMMENDATION BAR — on corrected grounds.** |

**The demotion's conclusion stands; its grounds have changed and are now auditable.** As printed, CBC-3 was demoted by a probe FAIL whose record did not support itself. As restated, the probe passes marginally and the candidate fails the bar on **confidence** (Low-Medium, driven by the single-source stability failure and the marginal cohesion) plus two never-produced Phase-F requirements. What could change the outcome is now precise: the never-run Phase-F items cannot rescue it alone (the confidence gap is independent of them); only evidence that reduces the item-1 single-source dependency or strengthens convergence — including the §7.5 reopening triggers, which stand unchanged — could lift it to the bar. **CBC-3's seam status is now evidence-decided on an auditable record; its re-disposition is the Authority's next act (G-M6-3, remaining item).**

### Amendment 2 — F-M6CR-2: citation attributions corrected

**Finding (quoted):** *"'issued by a gate or review, never by the author' attributed to item 1 §1.2 K-15; the verbatim sentence lives in M0 G-15 (item 1 K-15 carries the token set)."* — **Corrected:** wherever §6.1(a) and §7.2 cite that sentence, the home is **M0 G-15**; **item 1 §1.2 K-15** is the correct citation for the Verdict **token set** (PASS · PASS AFTER CORRECTION · WARN · FAIL · INCONCLUSIVE · EMERGENT · CERTIFIED). §8's Verdict row, which already cites both, stands as the model form.

### Amendment 3 — F-M6CR-3: the §8 completeness tally, corrected

**Finding (quoted):** *"§8's completeness tally sums to 23 as printed (Exception record double-counted; Policy-as-content is not an M5 row). Independent recount: all 21 M5 rows ARE placed — the substance holds, the arithmetic doesn't."* — **Corrected accounting of the 21 M5 §4 rows:** **5** recommended into CBC-1 (Observation · Finding · Verdict · Conformance · Completeness) + **3** into CBC-2 (Guide step · ADR pattern · Policy pattern) + **1** into CBC-4 (Work item) + **5** in the demoted seam (Rule · Invariant · Charter grant · Candidate · Ruling) + **4** in the unpartitioned core (Decision · Term · Model element · Contract) + **3** contested at CBC-1 (Risk · Question · Exception record — Exception record counted **here only**) = **21**. Two placed items are not M5 rows and are listed separately, not summed: **Policy-as-content** (a §4.2 seam member; an M2-evidenced package, not an M5 classification row) and the **views/C4/indexes family** (CBC-2 members from item 1 Q2.1 family G).

### Amendment 4 — F-M6CR-4: Question's bucket, unified

**Finding (quoted):** *"Question's bucket disagrees between sections (unpartitioned core in §0/§7.5 vs contested-at-CBC-1 in §4.2/§8)."* — **Unified: Question is recorded as contested membership at CBC-1** (the more specific record, consistent with §4.3's Question-routing analysis: "treated as contested membership rather than a boundary"). Where §0 and §7.5 list the expressed-knowledge core, the listing reads, corrected: **Decision · Term · Model element · Contract**. **Presentation only — Question remains unassigned** either way; its home is among the unresolved domain questions of Ledger A item 7.

---

*Amendment traceability: executes the Bounded Evidence Restatement / Fold Commission (PA, 2026-07-28 — ordered by the Authority Disposition `PKS_Phase_II_M6_Authority_Disposition.md` §3, as PA-refined: evidence restatement ≠ consolidation) · applies F-M6CR-1..4 from `PKS_Phase_II_M6_Critical_Review_Report.md` §§11–12 · restated probe grounded in M4 §1.1 (V-4-verified) + M4 Checkpoint Amendment 3 + M2 §3 Step 5 · OBS-2 discharged (stability test run) · no re-disposition, no Phase B–F execution, no OQ resolved, no methodology change. **Next act: CBC-3 re-disposition (G-M6-3, remaining item), then Consolidation (G-M6-4).***
