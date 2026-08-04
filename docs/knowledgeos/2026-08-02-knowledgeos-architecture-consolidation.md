# KnowledgeOS — Architecture Consolidation

| | |
|---|---|
| **Kind** | **ARCHITECTURE CONSOLIDATION.** ⛔ ***Identifies architecture that already exists. Invents none · improves none · redesigns none · renames none.*** |
| **Authority** | ⚠️ **Generated — never authoritative without human review** |
| **Commission** | Principal Software Architect / Principal Knowledge Engineer, 2026-08-02 — *"determine the migration path"* · 10 required sections |
| **Vision** | ⭐ **ACCEPTED AS DESTINATION, not evaluated.** `KnowledgeOS creates PKS` · PKS is an **instance**, not a product · four strategic products |
| **Placement** | ⭐ **DERIVED** — `--scope=product-specific --maturity=research --domain=knowledgeos` → `docs/knowledgeos` (**exit 0**) |
| **Method** | every row traced to a committed artifact. ⭐ **Speculative relationships are marked `SPECULATIVE` inline** |

> ### ⭐ **Correction carried in from review:** the prior discovery's verdict is restated as
> ### ***"the current strategic model is sufficient FOR THE PRESENT MATURITY LEVEL"*** — not *sufficient*.
> **KnowledgeOS has reached its *currently validated* model, not its mature one.**

---

## 1. Current Architectural Landscape

**Three concerns are already governed — ES-005.1, ADOPTED:**

| Concern | Paths | Meaning |
|---|---|---|
| **Product** | `docs/` · `architecture/` · `app/` · `tests/` | what PublicDigit **is** |
| **Engineering Platform** | `engineering/` | how it is **engineered** |
| **Runtime mount** | `.claude/` | how the current **adapter** executes — *"never 'the architecture'"* |

**Three documentation domains are already registered** (`doc-placement.php --list`): `publicdigit` → `docs/publicdigit` · `knowledgeos` → `docs/knowledgeos` · `pks` → `docs/pks`.

**Measured landscape:**

| Body | Size | Concern today |
|---|---|---|
| `app/` | ⭐ **1,532 code files** (133 under `app/Domain`) | Product — **exists and is substantial** *(EAD-1 §0)* |
| `engineering/verification/` | **106 md** | Platform — the evidence corpus |
| `engineering/` (rest) | **30 md** + 2 root | Platform — governance · architecture · knowledge |
| `docs/architecture/` | **448 md** | Product |
| `docs/implementation/` | **195 md**, of which ⭐ **92 are `PKS_*`** | ⚠️ **mixed — the central finding** |
| `docs/knowledge/` | **132 md** | EKP |
| `docs/pks` · `docs/knowledgeos` · `docs/publicdigit` | 9 · 2 · 12 | the three registered roots |

> ## ⭐⭐ **THE CENTRAL LANDSCAPE FINDING — already recorded, not discovered here**
>
> **EAD-1 §0, claim 2, verbatim:** *"The governance framework — Integrity Model · Review Method · ARB Discipline — contains **ZERO** occurrences of *vote · voting · ballot · election · voter*. **It is domain-free methodology, and it sits in `docs/`, which ES-005.1 classifies as PRODUCT**."*
>
> ### **Domain-free methodology is already sitting in a product-classified path. That misplacement — not any missing design — is what the migration must resolve.**

## 2. Canonical KnowledgeOS Inventory

⭐ **Everything below passes ES-005.3** — *"could a different project adopt this unchanged?"*

| Artifact | Status | Permanent home |
|---|---|---|
| `engineering/governance/ES-001..ES-006` + `STANDARDS_INDEX.md` | **PROPOSED** ×6 | ⭐ **KnowledgeOS** |
| `engineering/governance/Engineering_Execution_Protocol.md` | in force | **KnowledgeOS** |
| `engineering/architecture/reference/Engineering_Platform_Reference_Architecture.md` | **DRAFT** | ⭐⭐ **KnowledgeOS — the kernel** |
| `engineering/architecture/reference/Engineering_Platform_Knowledge_Metamodel.md` | **CANDIDATE**, gate **OQ-ENG-004** | **KnowledgeOS** |
| `engineering/architecture/reference/Engineering_Decision_Model.md` | reference | **KnowledgeOS** |
| `engineering/architecture/adr/ADR-AIP-01` · `ADR-AIP-02` · `ADR-AIP-LOG` | governing | **KnowledgeOS** |
| `engineering/architecture/baseline/Phase-01 … 03A` | ⛔ **SEALED** (genesis record) | **KnowledgeOS** *(historical)* |
| `engineering/architecture/c4/Engineering_Knowledge_System_Reference_Model.md` | descriptive · living | ⭐ **KnowledgeOS — §8 is the strategic model** |
| `engineering/knowledge/methodology/DDD_Tactical_Governance_Principles.md` | **ADOPTED** (ARB 2026-07-26) | **KnowledgeOS** |
| `engineering/knowledge/methodology/Layer_Verification_Rule.md` | Research | ⚠️ **blocked — OQ-K1** |
| `engineering/knowledge/patterns/*` (EPC cards) | pattern cards | **KnowledgeOS** |
| `engineering/verification/` (106) | evidence corpus | ⚠️ **SPLIT — §5 of the prior discovery.** *Protocol reusable; records are the producing track's* |

⭐ **The kernel already declares its own reusability.** Reference Architecture §1, verbatim: *"It governs how engineering work is planned, approved, executed, verified, and evolved, **independent of project, programming language, or execution provider**."* And its governing principle: *"**Governance precedes automation. Automation may implement governance. Automation never defines governance.**"*

**⛔ Also recorded there, and binding on every extraction stage:** *"No orchestrator, workflow engine, state machine, or execution aggregate is part of the current architecture — deliberately, and not forever… may exist only after operational evidence demonstrates the governance model is insufficient"* (**R-37** burden of proof).

## 3. Canonical PKS Inventory

| Body | Count | Nature |
|---|---|---|
| `docs/implementation/PKS_Phase_I*` · `PKS_Phase_II*` | ~60 | ⛔ **behind the Phase II freeze** — governance record |
| `docs/implementation/PKS_Phase_III*` | 6 | active: Capability Catalog · Engineering Knowledge Model · **EAD-1** · Runtime Adapter Record · Operational Validation Charter |
| `PKS_Knowledge_Integrity_Model` · `PKS_Knowledge_Contract_Review_Method` · `PKS_ARB_Review_Discipline` | 3 | ⭐⭐ **DOMAIN-FREE METHODOLOGY** *(EAD-1: zero election terms)* |
| `PKS_Phase_II_Methodology_Baseline_v1_1` · `v1_2` | 2 | the **SDM/EOP** baseline, **reference-defined** |
| `PKS_BRM_1_Decision` · `PKS_SDM_EXT_1_Extraction_Commission` | 2 | ⛔ **the extraction constraint — see below** |
| `docs/pks/*` | 9 | Phase III working record + CAP-001 reports |
| `scripts/lib/EngineeringKnowledge/**` | CAP-001 | ⭐ **already portable — §5** |

> ## ⛔⛔ **BRM-1 GOVERNS EVERY EXTRACTION STAGE — AND IT ALREADY REJECTED EXTRACTION-BY-COPY**
>
> **`SDM-EXT-1` status, verbatim:** *"**RETIRED 2026-07-31 by BRM-1 (RETAIN MODEL B).** Not deferred — its premise was that the baseline should be **materialized**, and that premise is **decided against**… CDR §4.2's authorization is UNCHANGED and now **UNEXERCISED, not revoked**."*
>
> ### **Consolidating the methodology into single SDM-v1/EOP-v1 documents was commissioned, then retired. The baseline stays REFERENCE-DEFINED.**
>
> ⭐ **Therefore: KnowledgeOS may be extracted by REFERENCE, never by COPY** — which is also **ES-005.4** (*"it never duplicates them. One rule → one home"*). **Any stage below that would materialize a copy is already forbidden.**

## 4. PublicDigit-specific Assets

| Asset | Evidence | Permanent home |
|---|---|---|
| `app/` — 1,532 files, `app/Domain/{Election,Finance,Locale,Shared}` | EAD-1 §0 | **PublicDigit** |
| `docs/architecture/` (448) · `docs/election*` · `docs/membership` · `docs/committee` | product design | **PublicDigit** |
| `docs/implementation/EPIC-*` (32) · `PB-*` (5) · `BACKLOG` · `PROGRAM_STATUS` | delivery | **PublicDigit** |
| `docs/knowledge/domains/adjudication/` | domain knowledge | **PublicDigit** *(as PKS content)* |
| `docs/knowledge/schema/governed-registers.yaml` · every `R-nn`/`PMR-nn` row | register content | ⭐ **PublicDigit — correctly already Infrastructure-side in CAP-001** |
| `engineering/verification/reports/*` (106) | ⚠️ observations *about* PublicDigit | **PublicDigit** *(records)* — protocol stays platform-side |

## 5. AI Runtime Architecture

> ## ⭐⭐ **ALREADY FULLY MODELLED — four layers, not two.** `PKS_Phase_III_Governance_Runtime_Adapter_Record.md`

```
GOVERNANCE POLICY  →  CAPABILITY MAPPING  →  RUNTIME ADAPTER  →  CONCRETE CONFIGURATION
 authority ·          deny · request ·        how ONE runtime     .claude/settings.json
 commission ·         approval · allow ·      implements each
 promotion            immutable · governed    capability
 (governance terms)   (TOOL-NEUTRAL terms)    (tool terms)        (a file)
```

| Recorded finding | Verbatim |
|---|---|
| The inversion | *"**`.claude/settings.json` is an ADAPTER. It is not the governance model.**"* |
| Portability | *"another runtime could replace Claude Code and **only the adapter would be rewritten**. The governance would be unchanged. That is the property that makes the model **portable rather than tool-shaped**"* |
| ⭐ Why four layers | *"**Capability Mapping is NOT an adapter**… it exists so that Claude-specific vocabulary — `ask`, `deny`, `permissions` — **cannot LEAK UPWARD** into the governance model"* |

**Two supporting mechanisms already in place:**

| Mechanism | Why it matters to extraction |
|---|---|
| `.claude/platform/registry.yaml` — *"components carry stable ids **CMP-nnn**, assets **AST-nnn**. **Paths change; ids never do.** ADRs and reviews reference ids, not paths"* | ⭐⭐ **references survive relocation** — the single most important extraction-safety property, already built |
| **R-42** — the registry governs *platform* assets only; project scripts *"execute at **no AI runtime moment** and carry **no AIP lineage**"* | the platform/product line is already drawn inside the runtime model |
| Reserved namespace `registry/` → *"provider-independent registry **spec**"*, trigger ⭐ ***"when a second runtime adapter exists"*** | **the runtime-independence trigger is already recorded** |

## 6. Migration Map

⭐ **Every trigger below is quoted from canon. None is invented.**

| # | Artifact | Current home | Future home | Reason | ⭐ Extraction Trigger *(recorded)* |
|---|---|---|---|---|---|
| **M-1** | `ES-001..006` + index | `engineering/governance/` | **KnowledgeOS** ✅ *already correct* | passes ES-005.3 | ⛔ **none needed — no move.** Ratification is separate |
| **M-2** | Reference Architecture *(kernel)* | `engineering/architecture/reference/` | **KnowledgeOS** ✅ *already correct* | declares project/language/provider independence | **DRAFT → ADOPTED** via *"one complete engineering cycle"* + retrospective |
| **M-3** | Knowledge Metamodel | same | **KnowledgeOS** ✅ | candidate distillation | ⭐ **OQ-ENG-004** + DA adoption *(pre-declared)* |
| **M-4** | `DDD_Tactical_Governance_Principles` | `engineering/knowledge/methodology/` | **KnowledgeOS** ✅ | ADOPTED, domain-free | ⛔ **none — already home** |
| **M-5** | `Layer_Verification_Rule` | same | ⚠️ **UNRESOLVED** | Research-stage in a platform path | ⭐ **OQ-K1** — *"does ES-005.3's research clause still hold?"* |
| **M-6** | ⭐⭐ **Integrity Model · Review Method · ARB Discipline** | `docs/implementation/` *(PRODUCT path)* | ⭐ **KnowledgeOS** | ⭐ **zero election terms — domain-free methodology in a product path** *(EAD-1)* | ⛔ **Phase II freeze** + **R-37** *(no reorganizations)* + **OQ-K1** |
| **M-7** | Methodology Baseline v1.1/v1.2 *(SDM/EOP)* | `docs/implementation/` | **KnowledgeOS by REFERENCE** | domain-free | ⛔⛔ **BRM-1 RETAIN MODEL B — materialization DECIDED AGAINST.** *CDR §4.2 unexercised, not revoked* |
| **M-8** | `PKS_Phase_I*` · `PKS_Phase_II*` (~60) | `docs/implementation/` | **stay — historical record** | governance history is append-only | ⛔ **none. Sealed corpus: moves allowed, edits never (R-30)** |
| **M-9** | `PKS_Phase_III*` (6) | `docs/implementation/` | ⚠️ **`docs/pks` candidate** | Phase III is active PKS work | **R-37** structural freeze |
| **M-10** | ⭐ **CAP-001** `Domain/` `Application/` `Shared/` | `scripts/lib/EngineeringKnowledge/` | ⭐⭐ **KnowledgeOS — READY NOW** | ⭐ **verified: zero repository knowledge** | ⭐ **NONE. The portability rule is already satisfied** |
| **M-11** | CAP-001 `Infrastructure/` + `governed-registers.yaml` | same | ⛔ **PublicDigit — permanently** | holds register paths | ⛔ **must never be extracted** |
| **M-12** | `.claude/settings.json` | `.claude/` | ⛔ **stays — it is the adapter** | ES-005.1: *the mount never moves* | ⛔ **none, ever** |
| **M-13** | **Capability Mapping** *(governance↔runtime)* | ⚠️ **inside the Runtime Adapter Record** | **KnowledgeOS** — tool-neutral layer | *"cannot leak upward"* | ⭐ **`registry/` reserved namespace: "when a SECOND RUNTIME ADAPTER exists"** |
| **M-14** | `engineering/verification/` protocol vs records | `engineering/verification/` | ⭐ **SPLIT** — protocol KnowledgeOS · records PublicDigit | producing track owns evidence | reserved `verification/evidence/` → *"when AST-010 produces them"* |
| **M-15** | `capabilities/` — capability model as first-class artifacts | ⛔ **does not exist** | **KnowledgeOS** | already reserved | ⭐ **"retrospective ruling on the capability layer"** |
| **M-16** | Cross-product research | ⚠️ **`PENDING` — no root** | **KnowledgeOS** | resolver returns PENDING | ⭐ **OQ-K2** |

> ### ⭐ **Nine of sixteen rows need NO MOVE — they are already in their permanent home.** **Seven are blocked, and every blocker is a recorded governance gate, not a design gap.**

## 7. Stage-based Evolution Roadmap

⛔ **Architectural evolution only. No implementation. Each stage's entry condition is quoted from canon.**

| Stage | Name | Entry condition *(recorded)* | Content | State |
|---|---|---|---|---|
| **0** | **Current Repository** | — | three concerns · three roots · CAP-001 realized · 1,532 product files | ✅ **HERE** |
| **1** | **Consolidation** | ⛔ **nothing** — it moves no file | ⭐ **Declare** the KnowledgeOS inventory (§2) as reserved namespaces per **ES-005.2**, resolve **OQ-K1/K2**. **No directory created** | ⏳ **available now** |
| **2** | **Extraction** | ⛔ **R-37 lifted** (C3 + PB-004 + retrospective) **AND OQ-K1 answered** | move **M-6**, split **M-14**, instantiate `capabilities/` (**M-15**) — ⛔ **by reference, never copy (BRM-1)** | ⛔ **blocked** |
| **3** | **Reusable KnowledgeOS** | ⭐ **"a SECOND real adopting product"** — the DA's recorded trigger for the **Platform ≙ Adoption split**, *"pre-positioned, not executed"* | the platform becomes independently addressable; **M-13** and the provider-independent `registry/` spec land | ⛔ **blocked — no second product** |
| **4** | **`knowledgeos init`** | ⛔ **Charter gate 4** — *"Architecture: only now"*, and *"anything before gate 4 passes"* is out of scope | bootstrap · templates · generators | ⛔ **blocked — Stage 1 of the charter is unapproved** |
| **5** | **Multiple Products** | ⭐ **market evidence** — charter records *"one retrospective data point, **zero market data points**"* | Hospital PKS · ERP PKS · … | ⛔ **blocked** |

> ## ⭐⭐ **THE ROADMAP'S REAL FINDING**
>
> **Stages 1–5 already have entry conditions written down — by four different authorities, over three weeks, without anyone drawing this table.**
>
> ### ⛔ **Only Stage 1 is open. Stage 2 needs R-37 lifted. Stage 3 needs a second adopting product. Stages 4–5 need the charter approved.** **Nothing about that ordering is proposed here; all of it is quoted.**

## 8. Open Decisions

| # | Decision | Authority | Blocks |
|---|---|---|---|
| **OQ-K1** | Does **ES-005.3's research clause** still hold now that `engineering/` is treated as a product? | ARB | ⭐ **M-5 · M-6 · Stage 2** |
| **OQ-K2** | Where does **cross-product research** live? *(`PENDING`)* | ARB | **M-16 · Stage 1** |
| **OQ-K3** | Has the **KnowledgeOS gate** opened? *(trigger: second adopting product)* | DA | **Stage 3** |
| **OQ-K4** | Is the **Product Discovery Charter** approved; is Stage 1 authorized? | ARB / sponsor | **Stages 4–5** |
| **OQ-K5** | Is this track accruing **AIP-14 over-evolution** exposure? | ARB | — |
| **OQ-K6** | Does **"KnowledgeOS"** remain the working name? *(charter: a **placeholder**)* | ARB | naming throughout |
| **OQ-C1** | ⭐ **Does BRM-1 (RETAIN MODEL B) permit reference-based extraction, or does it bar Stage 2 entirely?** *Its retirement of SDM-EXT-1 rejected **materialization** — reference-based extraction was never put to it* | **ARB** | ⭐ **Stage 2 · M-7** |
| **OQ-C2** | When **R-37** is lifted, does the **Phase II freeze** independently still bar **M-6**? | ARB | **M-6** |

## 9. Evidence Gaps

| # | Gap | Evidence |
|---|---|---|
| **G-C1** | ⛔⛔ **THE EVOLUTION LOOP'S RETURN ARROW HAS NEVER BEEN TRAVERSED.** EAD-1 §0 claim 3, verbatim: *"**Operational Evidence stands at ZERO-INDEPENDENT.** No evidence has flowed from PublicDigit to PKS. **The arrow has never been traversed**"* | EAD-1 |
| **G-C2** | **CAP-001: 0 executions, 0 decisions changed** by the tool | README §9 |
| **G-C3** | **Zero market data points**; one retrospective data point | Charter |
| **G-C4** | **No second adopting product** — the Stage-3 trigger | DA 2026-07-27 |
| **G-C5** | **No second runtime adapter** — the M-13 trigger | reserved namespace |
| **G-C6** | ⚠️ **Six ES standards remain PROPOSED**; the kernel is **DRAFT**; the metamodel is **CANDIDATE** | §2 |
| **G-C7** | ⚠️ **H-CAT-1 refused** the parent capability abstraction: *"not yet evidenced"* | Catalog |

**Required dependency graph — evidenced arrows solid, speculative marked:**

```
KnowledgeOS ──generates──▶ PKS            ⚠️ SPECULATIVE — no PKS has been generated by it
PKS ─────────guides──────▶ PublicDigit     ⚠️ SPECULATIVE — asserted, not measured
PublicDigit ──produces───▶ Evidence        ✅ EVIDENCED — 106 verification reports
Evidence ────improves────▶ KnowledgeOS     ⛔ EMPTY — "ZERO-INDEPENDENT… never traversed" (EAD-1)
```

> ### ⛔ **Three of the loop's four arrows are unevidenced, and the return arrow is empirically empty.** **The loop is the accepted destination; it is not yet an observed cycle.**

## 10. Recommended Next Engineering Slice

> # ⭐ **STAGE 1, AND ONLY ITS UNBLOCKED HALF.**

| | |
|---|---|
| **Slice** | **Declare the KnowledgeOS inventory (§2) as reserved namespaces in `engineering/README.md`** |
| **Why this one** | ⭐ **ES-005.2 supplies the exact mechanism** — *"Reserved namespaces are documented (README table), **never created speculatively**"* — and the file **already has such a table with triggers**. This extends an existing instrument |
| **Why it is unblocked** | ⛔ **moves no file · creates no directory · copies nothing · renames nothing.** Clears **R-37**, **BRM-1**, **ES-005.4** and the Phase II freeze simultaneously |
| **Prerequisite** | ⚠️ **OQ-K1 + OQ-K2 first.** Declaring a namespace for research that ES-005.3 sends project-side would record the contradiction rather than resolve it |
| **Definition of done** | each §2 row appears in the reserved-namespace table with its §6 trigger; **M-16 marked `PENDING`** per the placement ADR |

**And the two slices to run *before* any of it — both already recorded, neither architectural:**

| # | |
|---|---|
| **1** | ⭐ **Put CAP-001 to use during real minting.** **G-C2** is the only gap this track can close by itself, and **AIP-14** does not penalise it |
| **2** | ⚠️ **Answer OQ-K5.** Every stage above is platform work; *two consecutive platform-only iterations trigger an ARB over-evolution review* |

---

## ⭐ Consolidation verdict

**The commission asked for a migration path. The finding is that the migration path already exists, distributed across four authorities, and had never been assembled in one place.**

| | |
|---|---|
| ✅ **Already in their permanent home** | **9 of 16** artifacts, including ⭐ **CAP-001's Domain/Application/Shared — extractable today** |
| ⛔ **Blocked by a recorded gate** | **7 of 16** — R-37 · BRM-1 · Phase II freeze · OQ-K1 |
| ⭐ **Stage entry conditions** | **all five already written down** — by four authorities, over three weeks |
| ⛔ **What is missing** | ⛔ **not architecture.** **A second adopting product · a second runtime adapter · market evidence · one traversal of the return arrow** |

> ### **KnowledgeOS does not need to be designed. It needs four triggers to fire.**
> ⛔ **None of them fires by writing a document — and this document is the last one that should be written before the first of them fires.**

---

*Traceability: PA Architecture Consolidation commission 2026-08-02 · **vision accepted as destination, not evaluated** · 10 required sections delivered · **prior verdict restated as "sufficient for the present maturity level"** · migration map = 16 rows, every Extraction Trigger quoted from canon · dependency graph marks **3 of 4 arrows speculative or empty** · **new findings surfaced: (a) domain-free methodology sits in a product path (EAD-1), (b) BRM-1 already retired extraction-by-materialization, (c) CMP/AST stable ids make references survive relocation, (d) the four-layer runtime model already exists** · ⛔ **no architecture invented · none improved · none redesigned · nothing renamed · no directory created · no file moved · no tactical DDD · no code.***

> **⛔ Submitted to the Decision Authority. Nothing in this document executes.**
