# PKS Governance Architecture v1.0 — Canonical Responsibility Map (pre-M6 baseline)

| | |
|---|---|
| **Kind** | Canonical governance-architecture record: the **Governance Invariant**, the lifecycle responsibility chain, and the architectural properties it guarantees. **Descriptive, not constitutive** — it represents the governance architecture already in force; it creates no stage, no authority, and no procedure. |
| **Status** | **v1.0 — canonical descriptive representation of the current governance baseline.** Frozen with the pre-M6 baseline. *Deliberately **not** worded as "in force": that language is reserved for constitutive governance documents, and this artifact must not become a new source of authority through its wording (PA caution, 2026-07-28). Its authority is entirely derivative — every element traces to an artifact that already holds it.* |
| **Freeze relationship** | Not a governance change under GO condition 3 (which binds governance *during* M6 execution) and not a methodology change under the constitutional evidence-gated-change rule: every element below already exists in the record. What is new is the **explicit statement of the invariant** and a single canonical map replacing per-turn diagrams. |
| **Disposition History** | 2026-07-28: PA delivered the Updated Final Governance Architecture (invariant · lifecycle · responsibility map · architectural properties) following the operational-control / Execution-Governance terminology corrections. Recorded same date; one completeness observation raised (§4). |
| **Placement** | `docs/implementation/`, beside the gate review, the commission-instruction artifact, and the fresh-session prompt. |

---

## 0. Descriptiveness test (PA's test, applied honestly)

> **"If this document were deleted tomorrow, would the governance of the program change?"** — No = descriptive; Yes = it has become normative.

**Result, per element:** responsibility map (§2) — **No**, every row restates an existing artifact's own scope · lifecycle sequence (§3) — **No**, it restates the staged commissions · architectural properties (§5) — **No**, each is a property of the artifacts described · completeness observation (§4) — **No**, it is explicitly an observation routed to the PA, changing nothing.

**One honest wrinkle — the invariant (§1).** Deleting it would remove **no existing rule**: every case it covers was already decided (review ≠ Authority; authorization ≠ commission; classification ≠ disposition; Certified ≠ Configuration Controlled; control transfers, authority does not). But a *generalization* reaches cases its precedents never adjudicated — so as stated, the invariant could decide a **novel** future case that no individual precedent settles. That is normative reach, however slight.

**Recorded, not resolved:** §1 is therefore presented as *the governance invariant of the current baseline* — descriptive of what the record already enforces, and **not** claimed as an independently approved rule. Whether to elevate it to a constitutive rule via an explicit approval act is a **PA decision**, available at any time and not required for M6. Until then, if the invariant and a specific governance artifact ever appear to conflict, **the specific artifact governs and the conflict is reported** — this document never wins an argument against the artifacts it describes.

## 1. The Governance Invariant (of the current baseline — see §0 on its status)

> **Each governance artifact has exactly one primary responsibility. No artifact may redefine, duplicate, or assume the primary responsibility of another artifact. Governance authority, operational mandate, execution procedure, execution governance, execution activity, evaluation, and certification must remain explicitly separated.**

This is the generalization of every boundary the program has enforced in practice — the ARB-review seam (review recommends, Authority decides), the commission seam (authorization ≠ commission), the disposition seam (classification ≠ disposition), the certification seam (Certified ≠ Configuration Controlled), and the handover seam (operational control transfers; **authority never does**). Stated once here, it is checkable against any future artifact.

## 2. Responsibility chain (who owns what — one owner per row)

| Layer | Primary responsibility (sole owner) | Never does |
|---|---|---|
| **Program Authority (PA/DA)** | Governance decisions: GO / Conditional GO / NO GO · authorize execution · authorize certification and adoption | Execute · review its own decisions |
| **Bootstrap Composition Root** | Assemble canonical artifacts · verify preconditions (authority decision · frozen baseline · repository state · terminology freeze · fresh session) · **transfer operational control** | Hold authority · perform discovery |
| **Execution Governance** | Behavioral and constitutional constraints: discovery before design · business before solution · evidence before confidence · falsification before confirmation · UL discipline · boundary discovery · no methodology modification during M6 | Define workflow · define scope |
| **M6 Execution Commission** | The **operational mandate**: scope · mission · deliverables · required checkpoints · required outputs | Become the Authority · define the workflow |
| **Approved Execution Plan** | The **operational procedure**: workflow · sequencing · activities · review points · evidence collection · artifact production | Authorize · mandate |
| **M6 Execution** | Strategic domain **discovery**: findings · evidence · open questions · traceability · discovery artifacts · **method observations (recorded, unresolved)** | Decide · certify · modify the method |
| **Critical Review** | **Execution** evaluation: process adherence · evidence quality · risks · deviations · improvement candidates | Dispose · evaluate the methodology |
| **Authority Disposition** | Disposition of M6 outputs: accept · revise · defer · reject (**per candidate**) | Certify the methodology |
| **MCA** | **Methodology** evaluation: executability · repeatability · instruction quality · governance clarity · evidence-supported improvement candidates | Decide certification |
| **CDR** | Methodology **disposition analysis**: certify · revise · reject portions · maintain unchanged | Take the certification decision |
| **Program Authority (PA/DA)** | **Certification / adoption decision** (see §4 for the full act list) | — |

## 3. Lifecycle sequence

```
Authority Decision (GO)
  → Bootstrap Composition Root ──(operational control)──→
      Execution Governance ──(governs)──→ M6 Execution Commission ──(references)──→ Approved Plan
          → M6 Execution → M6 Checkpoint Package
              → Critical Review → Authority Disposition → M6 Consolidated Baseline
                  → MCA → CDR → Authority certification/adoption decision → Certified SDM/EOP v1 baseline
                      → M7 (by reference) → M8 → Post-M8 Benchmark
                          (Strategic DDD · Architecture Governance · DSR · EBSE · Knowledge Engineering)
```

## 4. Completeness observation (raised, not reconciled by assumption)

The delivered diagram's **Authority certification/adoption** box lists: *freeze SDM/EOP v1 · partially revise · continue evaluation.* The standing record stages **two further acts** at that same decision point: **adopt MCR-1** (the Method Certification Rule candidate) and **declare the Process Under Configuration Control** (the Certified ≠ Configuration-Controlled distinction). Neither appears in the diagram.

**Not treated as a conflict** — the diagram is a lifecycle map, not the decision's act list, and both acts are recorded elsewhere in the program state. **Recorded here so the decision point carries its full scope when reached**, and so no future reader infers from this map that certification is a two-option decision. Disposition of this observation belongs to the PA at the CDR, not to this artifact.

## 4a. Canonical program-state characterization (PA, 2026-07-28 — ARB-record wording)

> *"The program has reached a governance transition rather than a design transition. The v1.0 methodology has completed its engineering phase and is now entering controlled empirical evaluation under an established governance framework. Remaining uncertainties are primarily operational and are expected to be resolved through execution, structured review, certification, and authority decisions rather than additional conceptual design. Future evolution of the methodology should therefore be driven by evidence generated during M6–M8 and evaluated through the existing MCA/CDR governance cycle before incorporation into subsequent baselines."*

Use this wording verbatim wherever the program states where it stands (including the MCA's opening context). *Relocated here from `.claude/CONTEXT.md` per the PA's CONTEXT-hygiene caution (2026-07-28): CONTEXT carries current authoritative state; implementation documents carry rationale and canonical wording.*

## 5. Architectural properties guaranteed

Authority is never transferred ✅ · operational control is handed over by the Bootstrap Composition Root ✅ · Execution Governance is distinct from workflow and procedure ✅ · the Execution Commission is the sole operational mandate ✅ · the Approved Plan is the sole operational procedure ✅ · M6 performs discovery only ✅ · Critical Review evaluates execution ✅ · MCA/CDR evaluate the methodology ✅ · certification and adoption remain Authority acts ✅ · every governance artifact has exactly one primary responsibility ✅.

---

*Traceability: PA Updated Final Governance Architecture delivery, 2026-07-28 (invariant · lifecycle · responsibility map · properties), following the same-date terminology corrections (operational control ≠ authority; Execution Governance ≠ Execution Workflow) · descriptive of the governance architecture already in force · one completeness observation raised at §4 for the PA at the CDR · frozen with the pre-M6 baseline. Companion artifacts: `PKS_Phase_II_M6_Execution_Readiness_Review.md` (§13 Authority Decision) · `PKS_Architecture_Gate_Review_Commission_v1.md` · `PKS_Phase_II_M6_Fresh_Session_Prompt.md` (Parts A/B).*
