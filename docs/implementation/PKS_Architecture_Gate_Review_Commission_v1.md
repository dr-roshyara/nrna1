# Architecture Gate Review — Commission Instructions v1.0 (M6 Authorization Assessment)

| | |
|---|---|
| **Kind** | Governed synthesis artifact: comparative analysis (Strategic-DDD lens) of three supporting-material prompt drafts + the resulting **canonical commission instructions** for an Architecture Gate Review. Produced under the artifact-ingestion rule — the three drafts (program draft · Perplexity · Copilot, relayed by the PA 2026-07-28) are inputs; this artifact is the governed output. |
| **Status** | **v1.0 — canonical commission text.** The M6 *instance* of this review is already executed (`PKS_Phase_II_M6_Execution_Readiness_Review.md`, renamed Architecture Gate Review — M6 Authorization Assessment; recommended disposition GO, pending PA/DA confirmation). This artifact therefore serves as: **(a)** the retro-canonical commission text of record for that instance, and **(b)** a **candidate reusable method asset** staged for the MCA — candidate, not promoted; generalization follows the standing evidence bar (successful reuse), per the plan's Method Applicability pattern. |
| **Disposition History** | 2026-07-28: PA prompt-synthesis review (scores 9.7 / 9.5 / 9.2; rename directive; seven dimensions; three closure questions; independent-review refinement; non-goals elevation) → PA commission: "analyse all prompts and write final prompt instructions with DDD mindset and principal architect [perspective]". Executed same date. |
| **Placement** | `docs/implementation/`, beside the executed gate review. |

---

## 1. Comparative Analysis — the three drafts under a Strategic-DDD lens

The gate review is treated as its **own bounded context**, with its own ubiquitous language (readiness · gate · finding · recommended disposition) and explicit relationships to two neighboring contexts: **Method Engineering** (upstream — its subject matter) and **Authority** (downstream — its customer). Each draft is assessed for how faithfully it models those boundaries.

### 1.1 Per-draft assessment

| Draft | Strength (confirmed) | Boundary defects found |
|---|---|---|
| **Program draft** ("Chief Domain Architect…") | Repository grounding; execution sequencing; governance-state awareness | **D-P1 — role conflation:** stacking Chief Domain Architect + DDD Practitioner + Knowledge Engineer + Governance Steward merges the *design* lineage into the *review* role — the reviewer's UL and the designer's UL belong to different contexts. Corrected: ARB-reviewer role with the T-2 honesty clause (§2, DD-2). |
| **Perplexity** | Best structural apparatus: dimension definitions (D1–D6) · output format · operational rules · post-review transition · MCA routing of deferred items | **D-X1 — UL collision, "Review Authority":** the opening line makes the reviewer *the Authority* — a direct collision with this program's Authority model (classification ≠ disposition; the reviewer recommends, the Authority decides). **D-X2 — pre-filled evidence:** its Phase 1–5 verification tables arrive already stamped ✅ — a commission that *asserts* gate states instead of commissioning their *verification* reproduces exactly the defect this program STOPPED on (the false "this fresh session" assertion). Gate states are questions the reviewer answers against the repository, never inputs the commission supplies. **D-X3 — verdict-as-authorization:** "If the verdict is Go … M6 execution begins" collapses the reviewer's recommendation into the authorization act. |
| **Copilot** | Best review *mindset*: execution bias · burden of proof on blockers ("could this prevent execution?", never "could this be better?") · finding classes · freeze respect | **D-C1 — recommendation abstention:** "Do not recommend an option; present consequences only" under-delivers against this program's decision model, in which the reviewer *does* produce a **recommended** authority disposition (clearly labeled recommendation; the Authority still decides). **D-C2 — no repository grounding:** generic; every verdict-justification rule ("cite a specific frozen artifact") has nothing concrete to cite. |

### 1.2 The context-map reading

All three drafts err on the **same boundary** — the seam between the Review context and the Authority context — in three different directions: the program draft *imports the designer* into the reviewer (upstream leak), Perplexity *promotes the reviewer to Authority* (downstream leak), Copilot *severs the reviewer's output relationship* to the Authority (missing published-language element: the recommendation). The v1.0 synthesis fixes the seam once, explicitly: **the review is a supplier to the Authority; its published language is evidence + findings + a recommended disposition; authorization never crosses back upstream.**

## 2. Design Decisions for v1.0 (each traceable to the analysis or the PA synthesis)

- **DD-1 — Name and placement:** *Architecture Gate Review — \<Milestone\> Authorization Assessment.* The gate sits between Method Engineering and Execution (`Method Engineering → Architecture Gate → Execution → Evidence → Certification`). "Execution Readiness Review" was too operational for a lifecycle that already separates Critical Review · Disposition · MCA · CDR · Authority Decision. *(PA synthesis; pre-M6 considered naming decision — the freeze binds from M6 onward.)*
- **DD-2 — Role with honest independence:** the reviewer acts as an **ARB reviewer conducting the gate review** and **declares T-2 where it applies**: a prompt cannot *create* independence that does not exist. Where the reviewer lineage built the methodology, independence is **procedural** (falsification-first, findings-as-discrimination, burden of proof on blockers) and the artifact says so. Claimed assurance must match available assurance. *(Fixes D-P1 without Perplexity's over-claim.)*
- **DD-3 — Verify, never assume:** all gate/readiness states enter the commission as **questions with named evidence locations**, never as pre-stamped verdicts. *(Fixes D-X2; codifies the false-gate-assertion precedent.)*
- **DD-4 — Recommendation, not decision:** the review ends with a **Recommended Authority Disposition** (GO / CONDITIONAL GO / NO GO) — explicitly a recommendation. The Authority authorizes; the review provides evidence. *(Fixes D-X1, D-X3, D-C1.)*
- **DD-5 — Three closure questions before the disposition:** conceptual closure (architecture question) · empirical maturity (engineering question) · design-vs-execution value (research question) — each answered YES/NO with grounding, *then* the recommendation. Q3 is the program's actual position-finder. *(PA synthesis.)*
- **DD-6 — Seven dimensions, no overlap:** A Architecture Integrity · B Governance Integrity · C Method Integrity · D Operational Readiness · E Evidence Readiness · F Freeze Integrity · G Research Integrity — absorbing Perplexity's D1–D6 content and Copilot's five dimensions without duplication. *(PA synthesis.)*
- **DD-7 — Non-goals as a dedicated section**, with Copilot's burden-of-proof rule as its enforcement principle. *(PA synthesis + Copilot.)*
- **DD-8 — MCA routing:** every deferred item is packaged for direct consumption by the Method Certification Assessment (tagged observation / improvement candidate / open question). *(Perplexity, retained.)*
- **DD-9 — Instance vs. template:** the commission below is the **M6 instance**; reuse for later gates re-instantiates the evidence locations and milestone references — the workflow generalizes, the configuration does not. *(House Method Applicability pattern.)*

---

## 3. The Commission — Architecture Gate Review: M6 Authorization Assessment (v1.0)

> The text below is the canonical, verbatim-usable commission. For M6 it is the text of record; the executed instance conformed to it (verified §4).

### Role

You are acting as an **Architecture Review Board (ARB) reviewer** conducting the final **Architecture Gate Review** before M6 execution.

You are reviewing readiness — not participating in design or execution. Where you (or your lineage) participated in designing the methodology under review, **declare T-2 in the artifact header**: your independence is then *procedural* — falsification-first, findings-as-discrimination, burden of proof on blockers — and the review must say so rather than claim personal independence it does not have.

Your review **provides evidence to the Authority. It does not authorize.** The Authority authorizes.

### Position in the lifecycle

```
Method Engineering → ARCHITECTURE GATE (this review) → Execution → Evidence → Certification (MCA → CDR → Authority Decision)
```

### Non-goals (binding)

This review SHALL NOT: redesign or optimize the methodology · compare alternative methodologies · reopen accepted design decisions · introduce new governance · rename concepts · expand scope · continue literature review — **unless an execution-blocking architectural defect is discovered.** Enforcement principle: only questions of the form *"could this prevent successful execution?"* are in scope; *"could this be better?"* is out of scope and is recorded, if at all, as a deferred MCA item.

### Verification discipline (binding)

No readiness state is assumed. Every gate, freeze, and artifact status below is a **question you answer against the repository**, citing the specific artifact (and row where applicable). A missing or insufficient artifact is itself a finding. If a stated precondition of this commission proves false on verification: **STOP and report** — do not proceed, do not repair silently.

### Assessment — seven architectural dimensions

For each dimension: verify against the named evidence · record findings classified as **Observation / Risk / Dependency / Gap / Execution Blocker** · conclude READY / CONDITIONALLY READY / NOT READY.

| # | Dimension | Core questions | Evidence to verify (M6 instance) |
|---|---|---|---|
| A | **Architecture Integrity** | Internal consistency, completeness, coherence of the frozen conceptual baseline; contradictions between frozen artifacts? | M0–M5 consolidated artifacts; amendment/fold visibility |
| B | **Governance Integrity** | Authority boundaries unambiguous? Checkpoint criteria testable? Disposition process specified? Escalation defined? | Gates G-M6-0..4; commission text; disposition records; refusal precedents |
| C | **Method Integrity** | SDM coherent with per-phase exits? EOP coherent? Lifecycle defined? Decision points unambiguous — could a competent architect execute without methodological invention? | Approved plan (Phases A–G, probes, confidence model); commission package |
| D | **Operational Readiness** | Prerequisites available? Inputs enumerated? Handoffs specified? No unresolved dependency blocks start? | Commission package input list; fresh-session gate; Surfacing Register arming |
| E | **Evidence Readiness** | Will execution produce traceable evidence sufficient for the MCA? Can divergence from intent be detected and recorded? Are MCA/CDR criteria defined *in advance*? | Staged MCA commission (nine criteria, per-asset verdicts); CDR definition; evidence-model rules |
| F | **Freeze Integrity** | Terminology / governance / methodology frozen with bounded scope? Any artifact referencing unfrozen elements (beyond the intentionally open academic positioning)? Which direction does drift risk point? | Freeze declarations; terminology record; refinement-trend evidence |
| G | **Research Integrity** | Working hypothesis explicit? Measurable validation criteria? Can execution strengthen, narrow, or falsify the framing? | Research-framing record; validation criteria; SLR package status |

### Risk assessment

Top execution risks (execution, not design) with: class · level · standing mitigation · whether mitigation requires unfreezing (any "yes" is a finding). A risk that would make execution evidence **uninterpretable or invalid** is a candidate Execution Blocker.

### Decision model — answer in order

**Three architectural closure questions** (each YES/NO, grounded in the dimension findings):

1. **Has the conceptual phase reached architectural closure?** *(architecture question)*
2. **Is the methodology sufficiently mature to enter empirical execution?** *(engineering question)*
3. **Would further conceptual work produce more value than execution?** *(research question — the position-finder)*

**Then, and only then:**

> **Recommended Authority Disposition: GO | CONDITIONAL GO | NO GO**

- **GO** requires: no Execution Blocker · Q1 YES · Q2 YES · Q3 NO.
- **CONDITIONAL GO** lists conditions — each achievable **without unfreezing**, each with owner and completion criterion.
- **NO GO** names the blocking defect with full evidence; its resolution is the first post-freeze change request, escalated through the Authority model.

This is a **recommendation, not a decision.** Do not declare execution begun. Do not authorize.

### Output structure

Executive summary (recommendation first) → per-dimension sections with findings → risk register → closure questions table → **Recommendation Basis** (3–5 evidence-grounded bullets, each citing a section — the recommendation must read as the conclusion of the evidence, never as a standalone verdict) → recommended disposition → **deferred-items package for the MCA** (each tagged: observation / improvement candidate / open question; none blocks execution) → traceability line ending in the STOP.

### STOP condition

The review terminates with the recommended disposition. Final statement: **"STOP — the GO decision is the authority's; on confirmation, the fresh session executes M6."** All further changes are deferred to the MCA/CDR cycle; on authority confirmation of GO, the methodology operates as a working hypothesis under empirical test.

---

## 4. Conformance note — the executed M6 instance against this v1.0 text

The executed review (`PKS_Phase_II_M6_Execution_Readiness_Review.md`, as renamed and amended) conforms: T-2 declared with procedural-independence framing (DD-2) ✅ · states verified against the repository, none pre-assumed (DD-3) ✅ · recommendation-not-decision, STOP at the authority's act (DD-4) ✅ · three closure questions answered YES/YES/NO (DD-5) ✅ · seven dimensions mapped, all READY (DD-6) ✅ · non-goals honored — zero redesign findings; RR-1/RR-2 routed as MCA context, not conditions (DD-7, DD-8) ✅. **No re-execution is required; the standing GO recommendation is the v1.0-conformant output.**

---

*Traceability: PA prompt-synthesis + final-prompt commission 2026-07-28 · inputs: three prompt drafts (supporting material, relayed in-session; program draft · Perplexity · Copilot) · governed per the artifact-ingestion rule · fixes recorded as D-P1, D-X1..X3, D-C1..C2 · design decisions DD-1..DD-9 · the M6 instance stands executed and conformant (§4); recommended disposition GO remains pending PA/DA confirmation. Reuse beyond M6 is a method-asset question for the MCA — candidate, not promoted.*
