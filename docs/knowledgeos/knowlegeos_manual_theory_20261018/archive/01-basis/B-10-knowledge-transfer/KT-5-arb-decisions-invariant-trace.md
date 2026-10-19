# KT-5 — `docs/knowledge_tranfer/` 2026-08-16: ARB decisions A/B, the ARCH-INV-1 trace, evidence package 01, artifact gap analysis

| Field | Value |
|---|---|
| Read (full) | `20260816_1156_arb_decision_record_A_B.md` · `20260816_1150_arch_inv_1_trace.md` · `20260816_1129_arb_evidence_package_01_v3.md` · `20260816_1057_artifact_gap_analysis.md` |
| Source character | AI-generated, governed format. The decision record says: *"Decisions RECORDED (as stated by the ARB) · translation PROPOSED"* and *"a report of an act is not the act."* The others are **PROPOSED, ARB inputs** |
| Labels | [ARB-REC] ARB decision as recorded · [SRC-G] · [Q-KOS] quoted corpus (second-hand) · [I] · [?] |

## 1. ARB decisions recorded on 2026-08-16

- **[ARB-REC] Decision A:** *"Common engineering knowledge and governance capabilities require explicit ownership. Product-specific mechanisms remain product-owned."*
  - Translation [SRC-G]: every mechanism decomposes into **METHOD** (platform-owned) · **BINDING** (product-owned) · **EVIDENCE** (product-owned): *"one decomposition applied three times."*
- **[ARB-REC] Decision B:** *"Verification outcomes and recommendation-effectiveness outcomes are different business concepts and must not be merged into one verdict vocabulary."*
  - Generalized [SRC-G]: *"concepts that answer different questions do not share a vocabulary."* Under this rule, verification outcome · recommendation effectiveness · rule-relationship classification · governance disposition are **four concepts, not collisions**.
  - Specifically ruled out: **merging a machine finding with a human decision.**
- **[ARB-REC] The maturation model (the pattern for future rules):** **business principle → governed definition → domain model (a type, not prose) → mechanical enforcement → independent verification.**

## 2. The one fully realized invariant [SRC-G, OBSERVED in code]

**"Knowledge feeds authority; it never holds it."** It is traced through the full chain:

| Step | Where |
|---|---|
| Semantic owner | AD-1 AP-1 (inferred; read via citation) |
| Contract | AP-8 closed verdict vocabulary |
| Type | `Verdict` enum (7 values; `emittable()` returns 4) |
| Enforcement | `Assessment::of()` **throws** on PASS AFTER CORRECTION / EMERGENT / CERTIFIED |
| Verification | 3 tests |

*"The only invariant in the repository observed to complete the chain."* Twenty contracts were found; nineteen stop at *checked / conventioned / reminded*.

**Findings recorded alongside the trace:**
- `AP-n` is an **ungoverned series with two meanings** (AD-1 principles vs WP-6 findings).
- The canonical invariant **lives under `scripts/`**, inside *the largest ownership gap*.
- `Verdict.php` and `AssessmentService` *"have never met"*: different questions, different authority postures. This is the evidence base for Decision B.

## 3. Evidence package 01: the L3 decomposition and two further results [SRC-G]

- **[Q-KOS] Invariant I-11** (`KnowledgeOS_Relationship_Ontology.md` §4, 2026-08-02; corpus file, second-hand here): **"KNOWLEDGE DOES NOT EXECUTE. Capabilities execute. Knowledge CONSTRAINS AND INFORMS generation."**
- **[SRC-G] Applied as the layer criterion:**

  | Layer | What sits there |
  |---|---|
  | **L3a** knowledge ontology | which concepts exist: the 17 validated concepts (+ the Claim candidate) |
  | **L3b** normative semantics | what a rule *means*: subject, applicability, scope, normative effect, temporal validity, authority reference, exceptions, evidence |
  | **L2** | rule *analysis* (it executes) |
  | below L2 | tactical DDD |

  This mirrors an existing two-file split (`knowledge-types.yaml` vs `knowledge-schema.yaml`).
- **[SRC-G, citing the Lifecycle Gap Analysis] Authority is *two dimensions in one field*:** **immutable PROVENANCE** (`generated`, `derived`) **⟂** **act-changed STANDING** (`authoritative`, `provisional`, `historical`).
  - *"Provenance CANNOT have a state machine; standing changes by governance ACTS."*
  - Required order: split → govern standing → only then consider an aggregate.
- **[SRC-G] v3 assessed as the most conservative document.** Its declined positions matched adopted KnowledgeOS positions it had not read, 5 out of 5. The single contradiction was its naming freeze.
- **[SRC-G] Self-limit:** *"Producer ≠ reviewer is violated — I authored v3 and this examination of it."* The package is **evidence, not acceptance**.

## 4. Artifact gap analysis [SRC-G]

- **Adopted target sentence:** *"a **governed state-and-analysis system**: authoritative knowledge + evidence + applicability + authority + temporal validity + formal analysis + governed resolution + traceable delivery."*
  - The value lies in *"determining whether a rule applies here, who authorised it, what supports it, and whether it contradicts something else"*, not in storing rules.
- **A fourth non-equivalence:** **RELEVANCE ≠ AUTHORITY.** *"A similarity score is only a relevance signal. It is not permission, authority, applicability, or truth."*
- **Visibility under derivation:** *"derivation may narrow visibility but must never widen it."*
- **Eight deferrals adopted:**
  - generalized agent autonomy;
  - broad organizational knowledge management;
  - automatic rule activation from free text;
  - full graph-database commitment;
  - event sourcing;
  - microservices;
  - a generic workflow platform;
  - **universal ontology**.
- **Gates follow ratification:** *"A gate may only enforce an architecture that a recorded human act has decided."*

## 5. Effect on the basis

- **[I] Core theoretical commitments, now evidenced in more than one place:**
  - knowledge ≠ artifact;
  - knowledge does not execute;
  - knowledge feeds authority, never holds it (enforced in code);
  - provenance ⟂ standing;
  - state ≠ authority;
  - relevance ≠ authority;
  - absence/UNKNOWN is not permission;
  - concepts answering different questions do not share a vocabulary.

  These are **stable across RQ-002 (07-11), the KnowledgeOS canon (08-02…04, second-hand) and the knowledge-transfer work (08-16)**.
- **[I] What the system does:** a *governed state-and-analysis system* for engineering knowledge, with human authority as the only source of standing.
- **[?]** Whether any of the 2026-08-16 ARB decisions were later ratified in a register.
