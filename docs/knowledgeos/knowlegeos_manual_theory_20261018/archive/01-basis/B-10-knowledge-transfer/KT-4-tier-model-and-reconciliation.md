# KT-4 — `docs/knowledge_tranfer/` 2026-08-16: the tier model, the ARB reconciliation, the gap analysis

| Field | Value |
|---|---|
| Read (full) | `20260816_0841_target_architecture_v3.md` (*Architecture Reconciliation v3.1*, REV 1 11:36) · `20260816_1108_architecture_gap_analysis_knowledgeos.md` |
| Source character | AI-generated **governed-format** documents (authority: generated; status **PROPOSED**). v3.1 **embeds ARB rulings of 2026-08-16**, marked ⭐ ARB, as quotations. **Where those rulings are recorded authoritatively is not located** (not in R-30…R-100) |
| **Corpus note** | Both documents **quote first-hand from `docs/knowledgeos/` root files dated 2026-08-02…04**, which are corpus-registered (S0001–S0046) and so still gated for me. Everything below about those files is **second-hand via these quotes** |
| Labels | [SRC-G] stated in a generated governed-format document · [Q-ARB] quoted ARB ruling · [Q-KOS] quoted from corpus file (second-hand) · [I] · [?] |

## 1. What KnowledgeOS is (the answer to the identity question)

- **[Q-ARB 2026-08-16]** **"KnowledgeOS is the T1 platform. The PKS is a T3/product-side knowledge space. EKP/PD-3 is a governed knowledge domain inside the platform. The `knowledge_tranfer` work should be investigated as the candidate L3 governed-knowledge model, not as a replacement architecture for KnowledgeOS."**
- **[Q-KOS, `2026-08-02-knowledgeos-architecture-baseline.md` §2]** the **five-tier model:**
  ```
  T1  KnowledgeOS            the reusable engineering product
  T2  KnowledgeOS Services   governance · capabilities · validation · runtime integration · operational learning · PKS generation
  T3  Product PKS            one generated PKS per product
  T4  Business Product       PublicDigit · Hospital · ERP …
  T5  Running Software       code · deployments · runtime evidence
  ```
  **"KnowledgeOS → creates → PKS → guides → Product."** *"A compiler is not a program; it produces one."*
- **[Q-KOS] Mission (enacted in 8 artifacts, unratified):** **"a reusable engineering platform that improves the development of software systems through governed engineering knowledge."**
  - **Vision:** the multi-product platform is a sponsor-owned hypothesis, *gate shut*, *zero market data* (Vision/Mission Clarification §1).
  - **Constraint:** AIP-14, adopted.
- **[SRC-G] Names:**

  | Name | Denotes | Tier |
  |---|---|---|
  | KnowledgeOS | the reusable platform | T1 |
  | PKS | *"one product's knowledge space — never reusable, an instance"* | T3 |
  | EKP | `PD-3`, a governed knowledge domain inside T1/T2 | — |
  | EKS | **retired**, no referent | — |

  *"Collapsing three tiers into a naming problem is a level error."*
- **[Q-KOS] Strategic domains PD-1…PD-7:**
  - PD-1 Engineering Governance;
  - **PD-2 Engineering Method** (*core, conditional*);
  - PD-3 EKP (owner: an individual);
  - PD-6 Runtime (below the bar);
  - PD-7 PKS (*"not a platform domain… it belongs to the product"*).
- **[Q-KOS] A KnowledgeOS Meta-Model** (6 dimensions) with load-bearing invariant **I-1:** **"ENGINEERING KNOWLEDGE ≠ ENGINEERING ARTIFACT. One is the thing; the other is a carrier of it."** Also an ontology of 13 concepts and 11 invariants, a capability model of 25 capabilities, 9 catalogued lifecycles, and **3,551 LOC of running PHP** (`scripts/lib/EngineeringKnowledge/`, `scripts/observations/`; hexagonal, TDD, v1.0 frozen).

**[I] This settles the identity question at the level of the August canon:**
- KnowledgeOS = the T1 reusable engineering platform that **creates per-product knowledge spaces**.
- The product idea (charter, M2) is its **vision**, gate shut.
- OQ-5's "is KnowledgeOS the Engineering Platform (`engineering/`)?" is not explicitly answered here. *"The T1 platform"* and *"reusable engineering platform"* strongly suggest yes, or a superset. **[?]** It still needs an explicit record.

## 2. Principles and invariants added on 2026-08-16

| ID | Statement | Standing |
|---|---|---|
| **P-8** | **"A representation or projection must never acquire semantic authority merely because it is executable, searchable, visualized, indexed, or consumed by AI."** | [Q-ARB] *proposed constitutional* |
| **ARCH-INV-1 / LA-2** | **"The mechanism records authority. It never grants authority."** Derived twice; already compiled into `Verdict.php::emittable()` as AP-1 | [Q-ARB] elevated to a named invariant |
| LA-1 | **Knowledge ≠ Artifact**: *"absence of a carrier is never absence of the thing"* | [SRC-G], from meta-model I-1 |
| LA-3 | Governance precedes automation | [SRC-G] |
| LA-4 | A responsibility becomes a component only at **n ≥ 2, exercised by another** (P3) | [SRC-G] |
| INV-A1…A4 | record before prose · machine state before narrative · **absence is never permission (UNKNOWN ≠ permission)** · one authoritative owner per invariant | [SRC-G] |
| STATE ≠ AUTHORITY | `Active ≠ Authorized · Approved ≠ Currently authoritative · Exists ≠ Permitted` (+ `OWNERSHIP ≠ AUTHORITY`, **`RELEVANCE ≠ AUTHORITY`**) | [Q-ARB] |
| Lifecycle invariant | *"No type's lifecycle may contain a direct edge from creation to an authoritative state"*; authoritative states are entered only by a recorded human act | [SRC-G] |
| Persistence | the repository is the **canonical representation**; DB, graph, search and vector are **rebuildable projections**; *not a binary* | [Q-ARB] |
| Claim | **G-18 Claim, CANDIDATE**: may not anchor a context or aggregate | [Q-ARB] |
| Provenance | Observation carries source, version, location, extraction method, lineage, evaluator, policy context, verification state **+ authored_by** (*"AI-produced content is provenance-marked, always"*) | [Q-ARB] |

## 3. The logical architecture L0–L6 (gap analysis §8) [SRC-G]

- **L0 Purpose:** vision (hypothesis), mission (unratified), constraint (AIP-14).
- **L1 Governance:** PD-1, PD-2, PD-3; *no reconciling authority across the three*.
- **L2 Platform capability:** 25 capabilities; all service capabilities advisory.
- **L3 Governed knowledge model:** the layer the `knowledge_tranfer` work fills. Rule model; authority as-of-time; invariants staged.
- **L4 Product Knowledge Space (PKS).**
- **L5 Product.**
- **L6 Evidence:** Observation → Recommendation → Decision → Outcome → Assessment.

**[SRC-G]** *"0 traversals — the loop has never closed back to L1/L2."*

## 4. Contradictions and findings recorded

- **[SRC-G] Four ontologies, mutually uncited:** L-A, **RQ-002**, the Engineering Platform Knowledge Metamodel, and the knowledge_tranfer ontology. A docket of 2026-08-03 predicted the fourth: *"a fourth ontology will eventually be commissioned by someone who cannot find the three."* The author calls itself the seventh **"proposing before searching"** occurrence (PG-8).
- **[SRC-G] Two closed verdict vocabularies collide in running code** (HIGH): `Verdict.php` (PASS … CERTIFIED) vs `AssessmentService` (SUPPORTED · PARTIALLY_SUPPORTED · NOT_SUPPORTED · INCONCLUSIVE). Further relationship vocabularies (9-, 8- and 6-value sets) remain unreconciled.
- **[SRC-G] Observed gaps:**
  - the running rule engine has no subject, applicability, scope, normative effect, temporal validity or authority (OQ-8);
  - authority has **no state machine** (OQ-18, two derivations);
  - **read-side access control is absent everywhere** (OQ-25).
- **[SRC-G] Self-correction:** *"The repository has a retrieval problem that caused unnecessary architectural duplication, and it also contains several genuine architectural gaps."*

## 5. Effect on the basis

- **[I] Answer to "what is KnowledgeOS?" by 2026-08-16:** a reusable engineering platform (T1) whose services (T2) generate and govern **product knowledge spaces** (T3) that guide products (T4). Its central theoretical commitments:
  - **knowledge ≠ artifact**;
  - **the mechanism records authority, never grants it**;
  - **representation never acquires authority**;
  - **state ≠ authority**;
  - **absence/UNKNOWN is never permission**.
- **[I] The earliest corpus files (S0001–S0046, 2026-08-02…04) are exactly this canon.** Reading them first-hand (when authorized) is therefore the true start of the corpus reconstruction. They come before the brainstorming/theory phase (late August–September).
- **[?] Open:**
  - where the 2026-08-16 ARB rulings are recorded;
  - whether OQ-5 was formally answered;
  - how the later corpus theory (kernel K, Zero/Gap) relates to this T1/T3 model and to RQ-002.
