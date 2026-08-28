# Architecture Gap Analysis — `knowledge_tranfer` vs `knowledgeos`

### Concern separation, reconciliation, and a logical architecture

| Governance | |
|---|---|
| **Authority** | Generated (AI-produced). **Never authoritative without human review.** |
| **Status** | **PROPOSED** — analysis and synthesis; nothing here executes |
| **Role** | Principal Architect · Knowledge Engineer · Strategic DDD |
| **Compared** | `docs/knowledge_tranfer/` (24 files, 9,737 lines) ↔ `docs/knowledgeos/` (46 files, 7,325 lines) |
| **Also read** | `scripts/observations/` (28 files) · `scripts/lib/EngineeringKnowledge/` · `developer_guide/knowledgeos/` · `docs/` roots · `architecture_legacy/round7/` (via citation) · **3,551 LOC of running PHP** |
| **Evidence grades** | **OBSERVED** (read from an artifact or executed) · **INFERRED** · **HYPOTHESIZED**. Only OBSERVED is authoritative |
| **Date** | 2026-08-16 |

---

# 0 · The headline

## 0.1 These are not two versions of one architecture. They are two different subjects — and one corpus does not know the other exists.

| | `docs/knowledgeos/` | `docs/knowledge_tranfer/` |
|---|---|---|
| **Subject** | **KnowledgeOS** — the reusable engineering platform (**T1**) | **PKS / EKS** — a governed knowledge system (**T3**, but argued as T1) |
| **Period** | 2026-08-02 → 08-04 | 2026-07-28 → 08-16 |
| **Maturity** | **RUNNING** — hexagonal, TDD, frozen v1.0, 3,551 LOC | **PAPER** — 38 open questions, 0 decisions |
| **Cites the other** | ⛔ never | ⛔ never |

**Neither corpus contains a single reference to the other.** Verified by search across both directories.

## 0.2 The prediction that was fulfilled

`KnowledgeOS_ARB_Decision_Docket.md`, Package 4, written **2026-08-03**, on the risk of *not* reconciling the three existing knowledge ontologies:

> **"a fourth ontology will eventually be commissioned by someone who cannot find the three."**

On **2026-08-16**, eleven documents were produced in `knowledge_tranfer/` deriving a knowledge ontology — Claim · Evidence · Rule · Decision · Exception · Assessment · Verdict, with lifecycles, relationships and aggregates — **citing none of L-A, RQ-002, or the Engineering Platform Knowledge Metamodel.**

**The docket predicted this corpus. `OBSERVED`.**

That is the most important sentence in this report, and everything below is its elaboration.

---

# 1 · Corpus inventory — measured

| Layer | `knowledgeos` | `knowledge_tranfer` |
|---|---|---|
| Strategic model | PD-1..PD-7, 9-criteria bar, evidence-graded | CBC-1/2, AR-1/2, XD-1 (inherited from AD-1) |
| Meta-model | 6 dimensions, 2 new, 1 rejected on test | — |
| Ontology | 13 concepts, 11 invariants, 8 choices | 17 concepts (G-1..G-17) + `G-18 Claim` candidate |
| Capability model | **25 capabilities**, one class each, every row traced | — |
| Lifecycle | **9 lifecycles** catalogued; gap located mechanically | 3-class + per-type chains |
| Tactical DDD | ⛔ deliberately absent (P3: n≥2) | **32 invariants, 9 aggregates, 8 policies, 4 event categories** |
| Running code | ✅ **3,551 LOC**, hexagonal, TDD, frozen | ⛔ none |
| Deferred register | **25 rows**, activation criteria, 6 transitions logged | 38 open questions, 1 answered |
| Ownership | 3 authorities named, `SA-1` open | ⛔ **absent** |
| Evidence grading | OBSERVED / INFERRED / HYPOTHESIZED throughout | ⛔ absent (adopted informally in v3 only) |

**The asymmetry is the finding.** `knowledgeos` is strong exactly where `knowledge_tranfer` is silent (purpose, ownership, capability, evidence discipline, running code) and silent exactly where `knowledge_tranfer` is strong (normative rules, conflict analysis, aggregate invariants, events).

---

# 2 · The subject error

## 2.1 The tier model already exists

`2026-08-02-knowledgeos-architecture-baseline.md` §2, **OBSERVED**:

```
T1  KnowledgeOS            the reusable engineering product
T2  KnowledgeOS Services   governance · capabilities · validation · runtime
                           integration · operational learning · PKS generation
T3  Product PKS            one generated PKS per product
T4  Business Product       PublicDigit · Hospital · ERP …
T5  Running Software       code · deployments · runtime evidence
```

with the generative relation stated:

> **`KnowledgeOS → creates → PKS → guides → Product`.** *"A compiler is not a program; it produces one."*

## 2.2 What `knowledge_tranfer` did with that

It architected **T3** — a product's knowledge space — while **arguing it should be T1**, a platform other organisations adopt.

`20260815_1615_target_architecture.md`: *"Build an EKS that developers, architects, reviewers, and AI assistants use as the trusted engineering knowledge system of an organization."*

`knowledgeos` canon has already ruled on that ambition, twice:

| Ruling | Source | Status |
|---|---|---|
| KnowledgeOS is a **Supporting Subdomain**; the Election System is the Core Domain | AIP-14 / ADR-AIP-02 | **ADOPTED** |
| The multi-organisation platform is the **Vision** — a sponsor-owned hypothesis with a falsification clause and **zero market data points**; charter **gate shut** | Vision/Mission Clarification §1 | **OBSERVED** |

**My own `v3 §1` reached the same conclusion independently** — rejecting the multi-organisation framing on the grounds that the corpus contains one execution lineage. Correct answer, derived without knowing the ruling existed.

## 2.3 The naming error was mine, and it inverted

`v3 §3` recorded *"five names for one system — PKS · KnowledgeOS · EKS · Engineering Knowledge System · Engineering Knowledge Operating Platform"* and proposed a terminology freeze.

**That was wrong, and the error is structural, not cosmetic.** Under `knowledgeos` canon these are **not synonyms**:

| Name | What it actually denotes | Tier |
|---|---|---|
| **KnowledgeOS** | the reusable engineering platform | **T1** |
| **PKS** | one product's knowledge space — *"never reusable — an instance"* | **T3** |
| **EKP** | the Engineering Knowledge Platform — `PD-3`, `owner: nab.raj.sharma`, frozen constitution, 7 schemas, 18 lint rules | a **domain inside T1/T2** |
| **EKS** | ⛔ no referent in `knowledgeos` canon — introduced by `knowledge_tranfer` on 2026-08-15 | — |

> **Collapsing three distinct tiers into "a naming problem" is the same category error `knowledgeos` names four times: treating a *level* distinction as a *label* distinction.** `v3 §3`'s freeze proposal would have frozen the wrong thing — one name for three concepts.

**`OQ-3` is therefore withdrawn as posed.** The question is not *"which name?"* but *"which of the three subjects is being architected?"*

---

# 3 · Concern separation

The two corpora tangle eight concerns. `knowledgeos` separates six of them explicitly; `knowledge_tranfer` separates two.

| # | Concern | `knowledgeos` | `knowledge_tranfer` | Verdict |
|---|---|---|---|---|
| **C1** | **Purpose** — Vision ≠ Mission ≠ Constraint | ⭐ separated on evidence; AIP-14 correctly reclassified from *mission* to *execution control with a metric and a trigger* | ⛔ conflated — v3 §1 argues a vision without distinguishing it from the adopted constraint | **knowledgeos wins** |
| **C2** | **Subject** — Platform ≠ PKS ≠ Product | ⭐ five tiers, generative relation stated | ⛔ collapsed (§2) | **knowledgeos wins** |
| **C3** | **Ownership** — who governs what | ⭐ three authorities named; `SA-1` open and framed | ⛔ **entirely absent** — "human authority" throughout, never *which* authority | **knowledgeos wins decisively** |
| **C4** | **Knowledge ≠ Artifact** | ⭐ the meta-model's load-bearing edge (`I-1`) | ⛔ absent — and its absence produced `OQ-1` (§4) | **knowledgeos wins decisively** |
| **C5** | **Lifecycle ≠ Structure ≠ Governance** | ⭐ gaps classified; 1 of 4 genuinely ontological | ⚠️ partial — 38 OQs unclassified along this axis | **knowledgeos wins** |
| **C6** | **Method ≠ Binding ≠ Evidence** (`P1`) | ⭐ the extraction decomposition, with a 4-tier portability ladder | ⛔ absent — treats the corpus as undifferentiated | **knowledgeos wins** |
| **C7** | **Responsibility ≠ Component** (`P3`) | ⭐ n≥2 + exercised-by-another + repeatable-pattern gate | ⛔ 9 aggregates and 8 policies proposed at n=0 | **knowledgeos wins** |
| **C8** | **Invariant ownership & consistency boundary** | ⛔ deliberately absent (tactical work gated by P3) | ⭐ **32 invariants, 14 fields, atomicity, pinning, cross-aggregate treatment** | ⭐ **knowledge_tranfer wins** |

**Six–one–one.** The one `knowledge_tranfer` wins is real and substantial — and it is precisely the layer `knowledgeos` deliberately declined to enter.

---

# 4 · `OQ-1` dissolves

## 4.1 The question, as asked

`knowledge_tranfer` `OQ-1`: **"Is the PKS software, or is it specifications?"**

- **49 references** across ten documents
- **five documents** carry an explicitly blocked section
- it blocks `OQ-17`, `OQ-19`, the hexagonal stack, code gates, `EvidenceRecord`/`ProjectionBuild` fields, and **all 32 matrix rows' `status`**

## 4.2 Why it is malformed

`KnowledgeOS_Meta_Model.md` §2, **the model's stated purpose**:

> **`ENGINEERING KNOWLEDGE` ≠ `ENGINEERING ARTIFACT`. One is the thing; the other is a carrier of it.**
>
> *"A meta-model earns its place by making a known error class impossible to phrase."*

The error class it makes unstatable is precisely **artifact-absence read as concept-absence** — recorded four times in `knowledgeos` ("there is no strategic-DDD method" · "there is no capability pattern" · "the mission layer is vacant" · "there is no lifecycle"), each time false.

**`OQ-1` is the fifth instance, inverted.** It asks which *representation* the PKS has, and treats the answer as determining what the PKS *is*. Under `I-1` that inference is invalid: representation is `D-II`, an orthogonal dimension. A rule stays a rule in YAML, in PHP, or in a database row.

## 4.3 And it is already answered empirically

**OBSERVED** in this repository:

| Layer | Representation | Evidence |
|---|---|---|
| Knowledge (rules, schemas, statuses) | **YAML** | `docs/knowledge/schema/*.yaml`, 10 files, lint-enforced |
| Knowledge (constitutions, models) | **Markdown** | 46 + 24 governed documents |
| Capabilities acting on knowledge | **PHP** | `scripts/lib/EngineeringKnowledge/`, hexagonal, TDD |
| Evidence streams | **JSONL, append-only** | `engineering/verification/observations/` |
| Runtime adapters | **shell + JS** | `.claude/scripts/`, `vscode-knowledgeos/` |

**The PKS is not "software or specifications." It is knowledge, carried in five representations simultaneously, with a governed capability layer acting on it.** `developer_guide/knowledgeos/01` states the style outright: *"Hexagonal Architecture with DDD governance — one repo, one process; no evidence justifies distributed boundaries."*

> ## **`OQ-1` is withdrawn as ill-posed. The 49 references and five blocked sections release.**

The correct residual questions, both narrower:

| Restated | Decider |
|---|---|
| **`OQ-1a`** — Does the *governed knowledge* layer need a transactional store beyond git, or does the commit remain the atomic unit? | ARB, on evidence of insufficiency |
| **`OQ-1b`** — Do the knowledge-model capabilities join `scripts/lib/EngineeringKnowledge/` (the existing capability home) or stand apart? | ARB — and `ES-005.2` says a directory exists only when its first artifact arrives |

And `..._0922 §3`'s finding survives intact and now has a home: under git, content hashes, snapshot identity and the outbox are **free**. That is an input to `OQ-1a`, not to a dissolved `OQ-1`.

---

# 5 · Reconciling the 38 open questions

| `knowledge_tranfer` OQ | Status against `knowledgeos` canon |
|---|---|
| **OQ-1** *(is it software?)* | ⛔ **DISSOLVED** — malformed under `I-1` (§4) |
| **OQ-2** *(one product or many?)* | ✅ **ANSWERED** — AIP-14 ADOPTED: Supporting Subdomain; multi-product is the **Vision**, gate shut, zero market data |
| **OQ-3** *(the name)* | ⛔ **WITHDRAWN as posed** — three subjects, not three names (§2.3) |
| **OQ-4** *(re-partition contexts)* | ⚠️ **SUPERSEDED** — `knowledgeos` supplies PD-1..PD-7 at a 9-criteria bar with evidence grades. The certified CBC-1/2 model is `AD-1`'s, a **different subject** |
| **OQ-5** *(execution plane a BC?)* | ⚠️ **ANSWERED for the platform** — PD-6 Runtime scored **2 of 9**, below the bar; *"a supporting subsystem with an adapter boundary"* |
| **OQ-6** *(promote `Claim`)* | ⚠️ open — but `P3`'s gate applies: n≥2, exercised by another |
| **OQ-7 / OQ-14** *(fill `AR-1`)* | ⚠️ open — `AFV-F4` unchanged; `SA-3` is the same question at platform level |
| **OQ-8** *(rule model)* | ⭐ **STRENGTHENED — see §6.1.** A **fifth** derivation, and the first `OBSERVED` one |
| **OQ-9, OQ-10** *(verification, certification)* | ⚠️ open — same blocker: *"the one thing this programme cannot self-supply"* |
| **OQ-11** *(mechanize intake?)* | ✅ **ANSWERED** — `PG-1`: 0 of 25 capabilities enforcing; next slice named (**make C-17 enforcing**) |
| **OQ-12, OQ-13** *(normative vocabulary)* | ⚠️ open — ⛔ **but see §7.5: two closed verdict sets already collide.** Adding a third is the known HIGH-severity UL failure |
| **OQ-15** *(temporal validity)* | ⚠️ open — nine lifecycles exist; none carries a validity interval |
| **OQ-16** *(own System/Component?)* | ✅ **ANSWERED** — those are **product-side**; `PD-7 PKS` is *"not a platform domain… it belongs to the product"* |
| **OQ-17** *(commit or transaction)* | → **`OQ-1a`** |
| **OQ-18** *(as-of-time authority)* | ⭐ **CONFIRMED INDEPENDENTLY** — Lifecycle Gap Analysis: *"status has a governed state machine; **authority has none**"*. Same defect, two derivations |
| **OQ-19** *(how many aggregates)* | ✅ **ANSWERED by `P3`** — n≥2 and exercised-by-another. At n=0, none |
| **OQ-20** *(pin / detect / forbid)* | ⚠️ open — genuinely new |
| **OQ-21, OQ-22, OQ-26** *(modelling)* | ⚠️ open — cheap, unaffected |
| **OQ-23** *(which aggregates survive)* | → `P3` |
| **OQ-24** *(hardness)* | ⚠️ open — and `knowledgeos` supplies the default: **"Advisory, never blocking"** is a stated design pattern |
| **OQ-25** *(read access)* | ⭐ **GENUINELY NEW — absent from `knowledgeos` too.** §6.2 |
| **OQ-27** *(policy naming)* | ⚠️ open — and the UL table shows the disease is repository-wide: `Baseline`×5, `Constitution`×5, `Capability`×3, `Platform`×4 |
| **OQ-28** *(`PARTIALLY_SUPPORTED`)* | ✅ **ANSWERED — IT IS RUNNING.** `AssessmentService.php`: `SUPPORTED · PARTIALLY_SUPPORTED · NOT_SUPPORTED · INCONCLUSIVE`, deterministic thresholds |
| **OQ-29, OQ-33, OQ-35** *(override, approval provenance, retention)* | ⚠️ open — genuinely new |
| **OQ-30, OQ-31, OQ-32, OQ-34, OQ-36** *(events, projections)* | ⚠️ open — `knowledgeos` **REJECTED an event bus for now** (EDA verdict 2026-08-04): *"point-to-point JSONL streams"* until multi-producer demand |
| **OQ-37** *(extraction vs normalizer)* | ⚠️ open |
| **OQ-38** *(bootstrap instrument)* | ✅ **ANSWERED** — it exists: `init.php` + `doctor.php`, **ACTIVE since 2026-08-04**, idempotent, TDD-pinned |

**Tally: 7 answered · 2 dissolved or withdrawn · 4 superseded or redirected · 2 strengthened · 23 remain open.**

**Nine of thirty-eight questions were already settled in the same repository.**

---

# 6 · What `knowledge_tranfer` contributes that `knowledgeos` lacks

This is not a one-sided comparison. Three contributions are genuine, and one is now the best-evidenced open item in either corpus.

## 6.1 `OQ-8` — the normative rule model — now has an OBSERVED gap

The running rule engine, `scripts/observations/recommendation-rules.yaml`, **OBSERVED in full**:

```yaml
rules:
  - id: R1
    description: cohesion review candidate on high LCOM4
    source: lcom4
    op: ">"
    threshold: 20
    recommend: "…"
```

Five rules. The complete field set is `id · description · source · op · threshold · field · recommend`.

| `OQ-8` field | Present in the running engine? |
|---|---|
| `subject` / target | ⛔ **no** — `source` names a collector, not a subject |
| `applicability` | ⛔ **no** |
| `scope` | ⛔ **no** |
| `normativeEffect` | ⛔ **no** — every rule is advisory by construction |
| `temporalValidity` | ⛔ **no** |
| `authority` | ⛔ **no** |

> ## **The platform's rule model is a threshold matcher. It cannot express *where a rule applies*, *what it obliges*, *when it binds*, or *who authorised it*.**

`OQ-8` previously had four derivations, all analytical. **It now has a fifth, and the fifth is `OBSERVED` in running code** — which is this repository's strongest evidence class, and exactly what `ES-006.1` requires for promotion: *"everything is promoted because operational evidence demonstrated necessity."*

**`OQ-8` is no longer a modelling preference. It is a measured insufficiency in a shipped artifact.**

## 6.2 `OQ-25` — read-side access control — is absent from **both** corpora

Verified by search across `docs/knowledgeos/`: no `KnowledgeAccessPolicy`, no retrieval-time permission model, no propagation of visibility to derived edges.

`knowledgeos` ships `ContextDelivered` as an audit event and an `AIContextProjection` as a capability — **with no policy governing who may receive what.** The `knowledge_tranfer` finding that *derivation may narrow visibility but must never widen it* has no counterpart anywhere.

**This is the only architectural gap that both corpora share, and neither has seen.**

## 6.3 Tactical DDD — the layer `knowledgeos` deliberately declined

`knowledgeos` gates tactical work behind `P3` (n≥2). `knowledge_tranfer` produced it anyway: 32 invariants with owners, atomicity classes, enforcement layers, failure behaviours, cross-aggregate treatment (pin/detect/forbid), and reconstructability.

**This is premature by `P3` and valuable by content.** The correct disposition is neither adoption nor rejection: it is **staging with an activation criterion**, which is the pattern the Deferred Architecture Register already runs (§9.4).

---

# 7 · Contradictions

| # | `knowledge_tranfer` says | `knowledgeos` says | Resolution |
|---|---|---|---|
| **7.1** | EKS is an organisation-wide platform | AIP-14 **ADOPTED**: Supporting Subdomain; the platform ambition is the **Vision**, gate shut | **knowledgeos binds.** An adopted ADR outranks an unratified proposal |
| **7.2** | PKS is *the system* | PKS is a **context type**, one instance per product, **product-side** | **knowledgeos binds** — and it explains why methodology leaked into product paths (`V-2`) |
| **7.3** | PostgreSQL as system of record *(3 assertions)* | *"one repo, one process; no evidence justifies distributed boundaries"*; JSONL + YAML + git, shipped | **knowledgeos binds** on evidence; the question narrows to `OQ-1a` |
| **7.4** | Event bus / outbox / inbox as V1 infrastructure | **Event bus REJECTED FOR NOW** (EDA verdict 2026-08-04): *"point-to-point JSONL streams"* until multiple producers AND consumers exist | **knowledgeos binds.** The outbox work is `STAGED`, not wrong — it needs a row and a criterion |
| **7.5** | Proposes a 9-value relationship vocabulary **and** an 8-value disposition vocabulary | **Two closed verdict sets already collide** — `Verdict.php` (PASS·FAIL·WARN·INCONCLUSIVE·PASS AFTER CORRECTION·EMERGENT·CERTIFIED, per AP-8) vs `AssessmentService` (SUPPORTED·PARTIALLY_SUPPORTED·NOT_SUPPORTED·INCONCLUSIVE). Recorded as **HIGH severity** | ⛔ **Adding a third and fourth set without reconciling the two is the documented failure.** Reconcile first |
| **7.6** | *"gates follow ratification"* (conformance §0) | `PG-1`: 0 of 25 enforcing; next slice = **make C-17 enforcing** (chosen because it is Tier-1, has `--verify`, and is deterministic) | ⭐ **AGREEMENT, reached independently.** `knowledgeos` additionally names the concrete first gate |

**Five bind against `knowledge_tranfer`. One is agreement.** In every binding case the deciding factor is the same: an adopted ruling or a shipped artifact outranks an unratified proposal.

## 7.7 The invariant that both corpora derived, independently

`Verdict.php`, in the running code, carries this comment:

> *"Only four are EMITTABLE by a mechanical check: PASS AFTER CORRECTION, EMERGENT and CERTIFIED belong to review and certification acts, which a validator does not perform (**AP-1: knowledge feeds authority, it never holds it**)."*

`knowledge_tranfer` v3 §9, derived from a different corpus entirely:

> *"The mechanism records authority. It never grants authority."*

**Same invariant. Two derivations. One already compiled into an enum's `emittable()` method.** This is the strongest single point of convergence between the corpora, and it is the one that should be lifted to a shared, canonical statement.

---

# 8 · The logical architecture

Derived by separating the eight concerns of §3 and placing each corpus's contribution at the layer its evidence supports. **Every layer names its owner, because `SA-1` showed that unowned layers are where the programme's symptoms originate.**

```
╔══════════════════════════════════════════════════════════════════════════════╗
║  L0 · PURPOSE                                            owner: SPONSOR      ║
║                                                                              ║
║   VISION      multi-product platform — HYPOTHESIS, gate shut, n=0 market     ║
║   MISSION     "a reusable engineering platform that improves the development ║
║               of software systems through governed engineering knowledge"    ║
║               — ENACTED in 8 artifacts · UNRATIFIED                          ║
║   CONSTRAINT  AIP-14 Product Primacy — ADOPTED, metric + over-evolution      ║
║               trigger. BINDS EVERY LAYER BELOW.                              ║
╚══════════════════════════════════════════════════════════════════════════════╝
                                      │ authorizes
╔══════════════════════════════════════▼═══════════════════════════════════════╗
║  L1 · GOVERNANCE                              THREE OWNERS — SA-1 OPEN       ║
║                                                                              ║
║   PD-1 Engineering Governance   owner: Decision Authority                    ║
║        ES-001..006 · EEP · rulings register · append-only                    ║
║   PD-2 Engineering Method       owner: sponsor + ARB          ⭐ CORE (cond.) ║
║        MC-01..08 · ADR-M · MB-39.1 · void-overriding                         ║
║   PD-3 Engineering Knowledge    owner: AN INDIVIDUAL          ⚠️ succession   ║
║        Platform (EKP) — frozen constitution · 10 schemas · 18 lint rules     ║
║                                                                              ║
║   ⛔ NO RECONCILING AUTHORITY EXISTS ACROSS THE THREE.                        ║
╚══════════════════════════════════════════════════════════════════════════════╝
                                      │ governs
╔══════════════════════════════════════▼═══════════════════════════════════════╗
║  L2 · PLATFORM CAPABILITY                     owner: platform, via DP-n      ║
║                                                                              ║
║   KERNEL   C-01 Capability Pattern · C-02 Execution · C-03 Verification      ║
║   (12)     C-04 Tactical Governance · C-05 Placement · C-06 Promotion        ║
║            C-07 Runtime Mapping · C-08 BC Discovery ⚠️blocked                 ║
║            C-09 Saturation · C-10 Uncertainty · C-11 Decisions · C-12 Chal.  ║
║   SERVICE  C-13 Identifier · C-14 Reference · C-15 Card · C-16 Graph         ║
║   (5)      C-17 Placement   ⛔ ALL FIVE ADVISORY — PG-1                       ║
║   RUNTIME  C-19 adapters: commit · file-save · Claude Code · VS Code         ║
║                                                                              ║
║   Style: Capability → Port → Adapter · hexagonal · rules-as-data · TDD       ║
║   Status: v1.0 CLOSED for feature-driven modification                       ║
╚══════════════════════════════════════════════════════════════════════════════╝
                                      │ operates on
╔══════════════════════════════════════▼═══════════════════════════════════════╗
║  L3 · GOVERNED KNOWLEDGE MODEL          ⭐ WHERE knowledge_tranfer BELONGS    ║
║                                                    owner: PD-3 + ARB         ║
║   CONCEPTS   Claim(cand.) · Evidence · Rule · Decision · Exception ·         ║
║              Assessment · Verdict · Finding · Risk · Contract                ║
║   RULE       subject · applicability · normativeEffect · scope ·             ║
║   MODEL      temporalValidity · authorityRef · exceptions · evidence         ║
║              ⭐ OQ-8 — OBSERVED gap in the running engine                     ║
║   ANALYSIS   applicability ∩ scope ∩ temporal → satisfiability →             ║
║              relationship classification → GOVERNED DISPOSITION              ║
║   AUTHORITY  grants · delegation · as-of-time validity (OQ-18, confirmed 2×) ║
║   INVARIANTS 32 rows · owner · atomicity · layer · failure · pin             ║
║              ⚠️ STAGED under P3 — activation criterion required               ║
╚══════════════════════════════════════════════════════════════════════════════╝
                                      │ creates (n=0 by mechanism)
╔══════════════════════════════════════▼═══════════════════════════════════════╗
║  L4 · PRODUCT KNOWLEDGE SPACE                    owner: the product team     ║
║   PublicDigit PKS — hand-built · a CONTEXT TYPE, one instance per product    ║
║   contains: concepts · UL · bounded contexts · decisions · BINDINGS          ║
║   ⛔ never contains reusable Method (I-7 — stated and BREACHED)              ║
╚══════════════════════════════════════════════════════════════════════════════╝
                                      │ guides
╔══════════════════════════════════════▼═══════════════════════════════════════╗
║  L5 · PRODUCT — PublicDigit                      owner: the product team     ║
║   1,532 code files · Election is the CORE DOMAIN (AIP-14)                    ║
╚══════════════════════════════════════════════════════════════════════════════╝
                                      │ produces
╔══════════════════════════════════════▼═══════════════════════════════════════╗
║  L6 · EVIDENCE                              owner: the producing track       ║
║   Observation → Recommendation → Decision → Outcome → Assessment             ║
║   append-only JSONL · 106 verification reports · CAP-001 §9                  ║
╚══════════════════════════════════════════════════════════════════════════════╝
                                      │ harvest
                                      ▼
                    ⛔⛔ 0 TRAVERSALS — the loop has never closed
                         back to L1 / L2
```

## 8.1 The three cross-cutting planes

Orthogonal to the layers, and each already partly present:

| Plane | Spans | Rule |
|---|---|---|
| **Representation** | L1–L6 | Markdown · YAML · PHP · JSONL · shell/JS. **Never a NATURE claim** (`I-1`) |
| **Runtime adapter** | L2 ↔ everything | `C-07 Capability Mapping` stops runtime vocabulary leaking upward — **generic, replaceable** |
| **Projection** | L3–L6 | graph · search · portal · dashboards · AI context. **Derived · rebuildable · nothing may depend on them** (`DR-1`, `L4-8`) |

## 8.2 The four load-bearing invariants of this architecture

Each traced, each derived at least twice:

| # | Invariant | Derivations |
|---|---|---|
| **LA-1** | **Knowledge ≠ Artifact.** Absence of a carrier is never absence of the thing | meta-model `I-1`; 5 error instances |
| **LA-2** | **The mechanism records authority; it never grants it** | `AP-1` in `Verdict.php`; v3 §9 — **two corpora, one conclusion** |
| **LA-3** | **Governance precedes automation** | `I-8` / `R-37`; conformance §0 — **two corpora, one conclusion** |
| **LA-4** | **A responsibility becomes a component only at n≥2, exercised by another** | `P3`; and it is what `knowledge_tranfer` violated at n=0 |

---

# 9 · Where the `knowledge_tranfer` work belongs

## 9.1 It is L3 — and L3 was genuinely empty

`knowledgeos` models **L0–L2** (purpose, governance, capability) and **L4–L6** (PKS, product, evidence). **L3 — the governed knowledge model the capabilities operate on — is the one layer it never populated.** Its rule engine reaches L3 with seven fields and no normative semantics.

**So the eleven documents are not redundant. They are misplaced, misnamed, and mis-scoped — and they fill a real hole.**

## 9.2 Disposition, item by item

| `knowledge_tranfer` artifact | Disposition |
|---|---|
| **Rule model** (`OQ-8`, `..._0914 §3`) | ⭐ **PROMOTE** — observed insufficiency in a shipped artifact (§6.1). The strongest candidate in either corpus |
| **Conflict analysis** (satisfiability, intersection, taxonomy) | ⭐ **STAGE** — activation criterion: **a second rule family whose applicability domains can overlap.** Today's five rules cannot conflict; they read different collectors |
| **`ExceptionGrant`** + 7 invariants | ⭐ **STAGE** — criterion: the first recorded deviation from an active rule |
| **Invariant-to-aggregate matrix** (32 rows) | ⚠️ **STAGE under `P3`** — criterion: n≥2 aggregates actually implemented. Retain as the design record |
| **Authority as-of-time** (`OQ-18`) | ⭐ **PROMOTE** — independently confirmed by the Lifecycle Gap Analysis (§5). Two derivations, one mechanical cause |
| **Read-access policy** (`OQ-25`) | ⭐ **RAISE** — a gap in *both* corpora (§6.2). Needs an ARB question, not a design |
| **Event taxonomy + outbox/inbox** | ⚠️ **STAGE** — the register already **REJECTED FOR NOW** an event bus; these need rows with criteria, not adoption |
| **`RELEVANCE ≠ AUTHORITY`** | ⭐ **PROMOTE** — joins `ACTIVE ≠ AUTHORIZED`, `OWNERSHIP ≠ AUTHORITY`, `EXISTENCE ≠ PERMISSION` |
| **Verdict/relationship/disposition vocabularies** | ⛔ **HOLD** — reconcile the two existing colliding sets first (§7.5) |
| **`EKS` as a name** | ⛔ **RETIRE** — no referent; use KnowledgeOS (T1) · PKS (T3) · EKP (PD-3) |
| **v3 §3 naming freeze** | ⛔ **WITHDRAW** — three subjects, not three labels (§2.3) |
| **`OQ-1`** | ⛔ **DISSOLVE** → `OQ-1a` / `OQ-1b` (§4) |

## 9.3 The correct instrument already exists

Every "stage" above is a row in the **Deferred Architecture Register** — *"a deferred concept has an explicit engineering activation criterion"* — which has 25 rows, six logged transitions, and a working staging discipline (*Can we build it? · Should we build it now? · Should it join the platform?*).

**Nothing new needs to be invented to carry this work. It needs rows.**

---

# 10 · The seventh occurrence

`knowledgeos` records its own failure mode and counts it:

> **`PG-8` — six occurrences of *proposing before searching*.** *"An index of what instruments already exist is worth more than any new instrument."*

**The `knowledge_tranfer` corpus of 2026-08-16 is the seventh, and the largest.** Eleven documents, ~4,900 lines, produced in one day, re-deriving in the abstract:

- an ontology that exists three times over (L-A, RQ-002, the Metamodel) — **the docket predicted this exact event**
- an authority invariant already compiled into `Verdict.php::emittable()`
- a verdict vocabulary already running in `AssessmentService`
- a bootstrap instrument already shipped as `init.php` + `doctor.php`
- a "gates follow ratification" ruling already recorded as `PG-1` with a named first gate
- an "advisory, never blocking" position already a stated design pattern

**I wrote those eleven documents.** The failure is not that the analysis was poor — it converged repeatedly and produced three genuine contributions. The failure is that **none of it began with a search**, in a repository that had already diagnosed that exact habit six times and named the cure.

The cheapest correction is the one `knowledgeos` already identified: **an index of existing instruments.** `PG-8` remains open, and it is now measurably the most expensive open item in the repository.

---

# 11 · Recommendations

Ordered by gating effect, in the docket's own style. **Every one is a recommendation to a named authority; none executes.**

| # | Action | Authority | Why first |
|---|---|---|---|
| **R-1** | **Dissolve `OQ-1`; restate as `OQ-1a`/`OQ-1b`** | ARB | Releases 49 references and five blocked sections at zero cost (§4) |
| **R-2** | **Rule the subject: which tier is being architected — T1, T3, or PD-3?** | ARB | Every other `knowledge_tranfer` question inherits this (§2) |
| **R-3** | **Promote `OQ-8`** — the normative rule model, on the OBSERVED insufficiency of `recommendation-rules.yaml` | ARB | Five derivations, one of them observed in shipped code (§6.1) |
| **R-4** | **Reconcile the two colliding verdict vocabularies** before admitting any third | ARB | Known HIGH severity; adding to it is the documented failure (§7.5) |
| **R-5** | **Close `OQ-18`** — authority needs as-of-time validity | ARB + PD-3 owner | Confirmed twice, independently, with a mechanical cause |
| **R-6** | **Raise `OQ-25`** — read-side access control | ARB | The only gap invisible to **both** corpora (§6.2) |
| **R-7** | **Open `PG-8`'s cure: index the existing instruments** | PD-3 owner | Seven occurrences; the failure that keeps producing the others (§10) |
| **R-8** | **Stage the tactical DDD work as register rows** with activation criteria | ARB | Uses the existing instrument; neither adopts nor discards (§9) |
| **R-9** | **Retire `EKS`; withdraw the v3 §3 naming freeze** | `G-7` act | Three subjects, not three names |
| **R-10** | **Run `D-9a`/`D-9b`** — define the Platform Cost basis, then the AIP-14 over-evolution check | ARB | ⚠️ **This corpus is itself evidence for that check.** The docket put it first for this reason |

> **`R-10` deserves a plain statement.** Twenty-four documents in `knowledge_tranfer/`, eleven of them produced today, are platform-only work. AIP-14's tripwire cannot fire while the metric is undefined. **The honest recommendation is to run the check that may halt this very track** — which is what the control exists for.

---

# 12 · Bottom line

**Three sentences.**

**One.** The two corpora are not competing architectures — they are **adjacent layers of one model that has already been drawn**, and `knowledge_tranfer` filled the single layer `knowledgeos` left empty while believing it was designing the whole system.

**Two.** `OQ-1` — the question 49 references waited on — **is malformed**, and the meta-model that dissolves it has been sitting two directories away since 2026-08-02, along with the answer to eight other open questions, the bootstrap instrument, the verdict vocabulary, and the authority invariant compiled into an enum.

**Three.** The genuine contribution survives all of that: **the normative rule model now has an OBSERVED gap in running code**, authority's missing state machine is confirmed by two independent derivations, and read-side access control is a hole in both corpora — and each of those is worth more than the ten documents that were written around them.

> ### ~~The repository does not have an architecture problem. It has a **retrieval** problem~~ — and it diagnosed that itself, six times, before this corpus became the seventh.

### ⛔ AMENDED — ARB review, 2026-08-16. The original sentence was too strong.

**The ARB's correction is accepted in full.** The evidence demonstrates **both**, and the original formulation suppressed half of its own findings — including §6.1 and §6.2, which this document argues are its most valuable results.

> ### **The repository has a retrieval problem that caused unnecessary architectural duplication, and it also contains several genuine architectural gaps that the duplicated work has now exposed.**

| Retrieval failure — `OBSERVED` | Architectural gap — `OBSERVED` |
|---|---|
| ontology re-derived a fourth time | rule model lacks normative semantics (§6.1) |
| authority invariant re-derived | authority has no state machine (`OQ-18`, 2 derivations) |
| verdict vocabulary re-derived | read-side access control absent from **both** corpora (§6.2) |
| bootstrap instrument re-derived | ownership incomplete — `SA-1`, `PD-6` unowned |
| gates-follow-ratification re-derived | governance and knowledge layers unreconciled (`D-8`) |
| advisory/non-blocking re-derived | two closed verdict vocabularies collide (§7.5) |

*Rhetorical economy is not an evidence grade. A single-cause conclusion was reached by discarding observed findings, which is the error class this document spends §4 diagnosing in others.*

---

*Derived from `docs/knowledge_tranfer/` (24 files, 9,737 lines) · `docs/knowledgeos/` (46 files, 7,325 lines) · `scripts/observations/` + `scripts/lib/EngineeringKnowledge/` (3,551 LOC, read) · `developer_guide/knowledgeos/` · `docs/knowledge/schema/` · `engineering/governance/` + `engineering/architecture/` (via prior reading, cited). Every claim graded; only `OBSERVED` treated as authoritative. **No file moved · no folder created · no ADR written · no code changed · nothing outside `docs/knowledge_tranfer/` touched.***

***PROPOSED — not approved, not authoritative. No governance act is recorded by this document's existence.***
