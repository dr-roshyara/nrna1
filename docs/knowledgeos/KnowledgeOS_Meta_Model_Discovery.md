# KnowledgeOS — Meta-Model Discovery

| | |
|---|---|
| **Kind** | ⭐ **STRATEGIC DISCOVERY — has a meta-model EMERGED?** ⛔ ***Not invented. No redesign · no folder change · no ADR · no implementation · no new terminology.*** |
| **Status** | ⚠️ **CANDIDATE — NOT ADOPTED** |
| **Authority** | ⚠️ **Generated — never authoritative without human review** |
| **Commission** | Meta-Model Discovery, 2026-08-03 |
| ⭐ **Freeze lawfulness** | *this is Knowledge Architecture stream work — maturing ontology and relationships without new platform abstractions. **No new category is introduced anywhere below*** |
| **Rule** | evidence before architecture · only categories that **repeatedly emerged** are recognized |

> ## ⭐ **CHECK-BEFORE-DISCOVERING — and it reshapes the commission twice**
>
> **1 ·** Of the `1144` review's four "remaining discoveries", ⭐ **THREE ARE ALREADY DONE**: *platform invariants* → the 11-invariant set *(10 surviving cross-product projection)* · *lifecycle owners* → the Engineering Progression Model *(P-1..P-10, each with owner)* · *the Engineering Kernel* → A-5, **tested and falsified for sufficiency at n=1 (MVK)**. ⛔ *Recording this prevents their re-commissioning — the anti-amnesia rule working.* **Only the meta-model question is open. This document answers it.**
>
> **2 ·** ⛔ **Meta-models already exist in the corpus — THREE formal ones plus two working classifications** *(§1)*. **The question is therefore not "is there a meta-model?" but "do the independent passes CONVERGE?"**

---

> ### ⚠️ **REV 2 — review 2026-08-03 (1420), three amendments annotated, none rewritten**
>
> | # | Amendment |
> |---|---|
> | **1** | ⭐ **"nine kinds" are held as CANDIDATE ARCHETYPES, not frozen primitives, until MM-1 resolves whether KIND is itself a dimension** *(U-MM-1 already said this; the terminology now follows it)* |
> | **2** | ⛔ **CONTAINER demoted CONFIRMED → CANDIDATE** — *its distinguishing edge, "generates Projections", is observed in ONE space only (the EKP); nesting unevidenced (H-3). My own n=1 rule decides this* |
> | **3** | **PURPOSE flagged for its own investigation** *(U-MM-4 stands; waits on D-5)* |
>
> **Successor deliverable: `KnowledgeOS_Ontology_Discovery.md` — the relationship pass over these archetypes.**

## 1. Discovery Summary

> # ⭐ **VERDICT: SUPPORTED — a meta-model HAS emerged. ⛔ But not the one hypothesized.**
>
> ### **It is DIMENSIONAL-WITH-ARCHETYPES, not a flat category list — and the A–G candidate list repeats the exact error the corpus's own strongest meta-artifact already refuted.**

**The evidence: FIVE independent classification passes, produced months apart by different exercises, converge:**

| Pass | Produced | When |
|---|---|---|
| **RQ-002 Knowledge Meta-Model** *(COMPLETE)* | `NATURE × REPRESENTATION × GOVERNANCE-STATUS ( × AUTHORITY-SCOPE )`; the seven types survive as **archetypes** *(densely populated cells)*, not primitives | research phase |
| **Engineering Platform Knowledge Metamodel** *(CANDIDATE, gate OQ-ENG-004)* | five knowledge classes — Normative · Descriptive · Evidence · Runtime · Learning | 2026-07-27 |
| **L-A `knowledge-types.yaml`** *(executable)* | 31 **document** types, lint-enforced | operating |
| **Relationship Ontology §2** | kinds assigned to 13 concepts: *purpose · governance · capability · knowledge · representation · container · runtime element · projection* | 2026-08-02 |
| **Progression Model** | four kinds of **change** + one non-progression *(governance-act · evidential · work-execution · composite · provenance)* | 2026-08-02 |

⭐ **Convergence across passes that could not see each other is exactly what "emerged from evidence" means.** ⛔ *And RQ-002's smoking guns bind this commission: "one thing cannot change type by being encoded differently" — a FLAT list of categories conflates dimensions. The A–G hypothesis must be tested against that.*

## 2. Evidence Matrix — the seven candidate categories

| Candidate | Supporting evidence | Conflicting evidence | ⭐ Verdict | Confidence |
|---|---|---|---|---|
| **A · Engineering Domains** | PD-1/2/3 at ≥3-of-9 criteria with observed owners · the frozen BC-1..BC-6 map · the blind review's 8 contexts — **three independent passes found domain-kind things** | none | ⭐ **CONFIRMED as a KIND** | **HIGH** |
| **B · Engineering Processes** | ⭐ **GEP-F1 is CANON**: *"two lifecycles, never conflated — governance-ARTIFACT vs governed-WORK"* · EEP · Observation Protocol *(has a clock)* · the WORK-EXECUTION progression kind · ⭐ **D-4's rejection as a domain IS the meta-statement: processes are not domains** | ⚠️ ownership thin — processes are owned via their governing artifacts | ⭐ **CONFIRMED as a KIND** — *the repository refuses to conflate it with artifacts, in canon* | **HIGH** |
| **C · Engineering Capabilities** | CAP-001 realized · `Platform_Capability_Pattern` FROZEN · the 1:1 CAP↔DP binding · the Catalog's six | H-CAT-1 refused the **parent capability** — ⭐ *but that refuses an abstraction INSTANCE, not the kind* | ⭐ **CONFIRMED as a KIND** | **HIGH** |
| ⛔ **D · Knowledge Assets** | knowledge cards, 31 types, the corpus | ⛔⛔ **I-1 SPLITS IT: Knowledge ≠ Artifact.** *"Knowledge Asset" fuses a NATURE with its REPRESENTATION — RQ-002's first smoking gun exactly* | ⛔ **SPLIT: KNOWLEDGE** *(kind)* **+ ARTIFACT** *(kind)* — *the candidate as named is rejected* | HIGH *(for the split)* |
| ⚠️ **E · Governance Assets** | rulings · standards · principles · DP-n · the Normative class · the GOVERNANCE-ACT kind | ⚠️ **a ruling IS knowledge (a decision) carried by an artifact.** *What distinguishes it is NORMATIVE FORCE — a NATURE value, not a separate kind* | ⚠️ **DEMOTED: a NATURE VALUE (normative), not a top-level kind** — *consistent with the Metamodel's Normative class* | MEDIUM-HIGH |
| **F · Runtime Assets** | ⭐ **already FIRST-CLASS and governed**: `AST-nnn` · `runtime_moment_enum` · five-question lineage · R-42's boundary ruling | none | ⭐ **CONFIRMED as a KIND — the only candidate already executable** | **HIGH** |
| ⛔ **G · Product Assets** | product code, records, PKS instances | ⛔ **"product" is AUTHORITY-SCOPE — a dimension VALUE.** *A product artifact is still an artifact; only its scope differs. Making it a kind would let scope change a thing's type — the smoking-gun error again* | ⛔ **REJECTED as a kind — it is a SCOPE value** | HIGH |

**Kinds the candidates missed but the passes repeatedly found:** ⭐ **PURPOSE** *(mission/vision — Relationship Ontology, twice)* · **CONTAINER** *(Knowledge Space — declared boundary, nesting)* · **PROJECTION** *(derived, operating in production)*.

## 3. The Candidate Meta-Model — only what evidence supports

```
   AN ELEMENT OF KNOWLEDGEOS =

      KIND        ×   the RQ-002 dimensions
      (archetype)     NATURE × REPRESENTATION × GOVERNANCE-STATUS × AUTHORITY-SCOPE
                        (× EVIDENCE-STATUS × OPERATIONAL-STATE — the two candidate additions)

   KINDS (CONFIRMED — each found by ≥2 independent passes):
      DOMAIN · PROCESS · CAPABILITY · KNOWLEDGE · ARTIFACT ·
      RUNTIME ASSET · CONTAINER (Knowledge Space) · PROJECTION · PURPOSE

   NOT KINDS (evidence rejects):
      ⛔ "Governance Asset"  → NATURE value: normative force
      ⛔ "Product Asset"     → SCOPE value: product
      ⛔ "Knowledge Asset"   → a fusion I-1 forbids
```

| Grade | Content |
|---|---|
| ⭐ **Confirmed** | the **nine kinds** above *(⚠️ REV 2: read as **candidate archetypes** pending MM-1; **CONTAINER demoted to Candidate**)* · the dimensional form *(RQ-002)* · ⭐ **kinds are ARCHETYPES, not primitives** |
| ⚠️ **Candidate** | EVIDENCE-STATUS and OPERATIONAL-STATE as dimensions *(MM-2, carried)* · PURPOSE as a kind *(found twice, exercised little)* |
| ⚠️ **Hypothesis** | ⭐ **whether KIND itself is simply a NATURE value under RQ-002, or a separate axis** — *the passes do not settle this; **MM-1 (adopt RQ-002) must land first*** |
| ⛔ **Rejected** | the flat A–G list as a meta-model · Governance-as-kind · Product-as-kind · Knowledge-Asset-as-fusion |

### ⭐ The acceptance demo — the review's own test case

> *"Suppose tomorrow someone says **Capability Mapping** — what is it?"*
>
> **Under the emerged model, decidable in one line:** ⭐ **KNOWLEDGE** *(nature: definition — a tool-neutral vocabulary)*, **scope: platform**, ⛔ **whose ARTIFACT does not exist** *(the falsified W-2)*, runtime-adjacent by SC-6.
>
> *The ambiguous case the review poses is exactly the case the model already handled — the knowledge/artifact split made "described in prose, instantiated nowhere" expressible.*

## 4. Relationship Analysis — the proposed meta-edges, validated

| Proposed | Verdict | Evidence |
|---|---|---|
| ⛔ **"Domains own Capabilities"** | ⛔⛔ **REFUTED** | *no artifact binds any `CAP-n` to a `PD-n`; capabilities are anchored to **`DP-n`*** — ⭐ *a proposed meta-edge the evidence rejects is the strongest sign the validation is real* |
| **"Capabilities use Knowledge"** | ✅ **SUPPORTED** | CAP-001 reads registers · ⭐ *with H-1's refinement: **may read across a space boundary, never own*** |
| **"Processes produce Knowledge/Artifacts"** | ✅ **SUPPORTED** | EEP §8 *(reports mandatory)* · P-7/P-8 *(work produces evidence)* · 106 records |
| **"Governance constrains Processes"** | ✅ **SUPPORTED** | EEP `Owner: Decision Authority` · R-46 · approval gates |
| ⭐ *(emergent, unproposed)* **"Containers generate Projections"** | ✅ **SUPPORTED** | `knowledge-graph.php` · `authority: derived` in production |

## 5. Boundary Analysis

| | |
|---|---|
| ⭐ **The KINDS themselves** | **KnowledgeOS** — *the meta-model is platform knowledge; a hospital adopter inherits the kinds and none of the instances* |
| **Instances** | distribute by **AUTHORITY-SCOPE** — *which is precisely why scope must stay a dimension: the same kind lives on both sides* |
| **Runtime Assets** | the registry's R-42 boundary already rules this — platform assets only, five-question lineage |
| ⛔ **Outside the platform** | external obligations *(the CONSTRAINS gap, CV-3/G-3)* — *the meta-model inherits the known hole; it does not fill it* |

## 6. Remaining Unknowns — ⛔ not resolved by reasoning

| # | Unknown | Waits on |
|---|---|---|
| ⭐⭐ **U-MM-1** | **Is KIND a NATURE value under RQ-002, or a separate axis?** *The passes converge on the kinds but not on their dimensional position* | **MM-1** *(adopt RQ-002)* — ⭐ *the meta-model's shape is decided there, not here* |
| **U-MM-2** | **Who owns the meta-model?** ⛔ *nobody — the same ownership-gap pattern as the platform itself* | SA-1 / the sponsor layer |
| **U-MM-3** | Does **PROCESS** need an owner model of its own, or is owned-via-governing-artifact sufficient? | operational evidence *(Stream 2 of the freeze trio)* |
| **U-MM-4** | **PURPOSE as a kind** — found twice, exercised least | mission ratification *(D-5)* |
| **U-MM-5** | The three formal meta-artifacts *(RQ-002 · the Metamodel · this discovery)* must not become a **fourth unreconciled ontology** | ⛔ **D-8 — this document is INPUT to that reconciliation, never a competitor** |

---

## ⭐ Closing — the success criterion

> **"Has Strategic Discovery revealed not only domains, but the fundamental categories of architectural elements?"**
>
> # ⭐ **YES — nine kinds, converged upon by five independent passes — with one correction to the hypothesis: the meta-model is DIMENSIONAL, and the kinds are its ARCHETYPES.**
>
> | | |
> |---|---|
> | ⭐ **Confirmed kinds** | DOMAIN · PROCESS · CAPABILITY · KNOWLEDGE · ARTIFACT · RUNTIME ASSET · CONTAINER · PROJECTION *(+ PURPOSE, candidate)* |
> | ⛔ **Rejected as kinds** | *Governance* (a nature value) · *Product* (a scope value) · *Knowledge Asset* (a fusion I-1 forbids) |
> | ⛔ **Refuted meta-edge** | *Domains own Capabilities* — **`DP-n` owns them** |
> | ⭐ **The demo** | *"What is Capability Mapping?" — decidable in one line under the model* |
> | ⛔ **The guard** | **this becomes a candidate for D-8's reconciliation — it must not become the fourth competing ontology** |

---

*Traceability: Meta-Model Discovery commission 2026-08-03 · lawful under the freeze (Knowledge Architecture stream; no new terminology — every kind name already existed in a prior pass) · ⭐ **check-before-discovering: three of the review's four "remaining discoveries" were already done (invariants · lifecycle owners · kernel), and three formal meta-artifacts already existed** · **verdict SUPPORTED-with-shape-correction: dimensional-with-archetypes (RQ-002's form), evidenced by FIVE independent converging passes** · **A–G tested: 4 confirmed as kinds (one after splitting), 2 rejected as dimension values, 1 split by I-1** · **"Domains own Capabilities" REFUTED; four other meta-edges validated** · **five unknowns recorded, incl. U-MM-1 (kind vs nature — waits on MM-1) and U-MM-5 (must not become the fourth unreconciled ontology — input to D-8)** · ⛔ **nothing invented · nothing redesigned · no ADR.***

> **⛔ Submitted to the Decision Authority. Nothing in this document executes. It enters D-8's reconciliation as input.**
