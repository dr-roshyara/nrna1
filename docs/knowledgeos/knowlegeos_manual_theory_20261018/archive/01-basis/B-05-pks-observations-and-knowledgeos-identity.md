# B-05 — `docs/pks/`: PKS, the observation practice, and the identity of KnowledgeOS

| Field | Value |
|---|---|
| Read (full) | all 17 Markdown files in `docs/pks/` (README + 16 documents, 2026-08-01 … 2026-08-13); `prompts/20260904_2111 ` is an empty file (0 bytes) and is not counted |
| Also consulted (partial, **not extracted**) | `docs/adr/ADR_20260801_1740_ Documentation Roots and Artifact Placement.md` l.8, 24, 42, 54, 152–160, 199, 203: read only to locate the recorded identity question OQ-5 |
| Standing | `docs/pks/` is **not** in the KnowledgeOS corpus registry. It is first-hand **PublicDigit-programme** evidence, partly *about* KnowledgeOS |
| Labels | [SRC] stated · [I] interpretation · [?] open |

## 1. Three different things share this folder

| Thing | What the sources say [SRC] | Anchor |
|---|---|---|
| **PKS: Product Knowledge System** | a **product domain** (peer of `publicdigit` and `knowledgeos` in the placement registry). *"PKS is not software: it is knowledge specifications (YAML + Markdown)."* It contains bounded contexts CBC-1 **Knowledge Assessment** and CBC-2 **Knowledge Projection**; CBC-3 **Normative Governance** is a candidate seam; an **expressed-knowledge core** (Decision · Term · Model element · Contract) is unpartitioned; CBC-4 Work Management is outside | README; architecture-validation report §1.1–1.2 |
| **Engineering-process observations** | since 2026-08-01 the folder is used *de facto* as an **incubation home** for engineering observations and "KnowledgeOS candidates", each placed under a recorded, time-bounded exception (`PENDING`, ADR:OQ-2) | distillation candidate header and C-1; four further candidates |
| **CAP-001 Identifier Integrity** | the first *implemented* knowledge capability (`scripts/lib/EngineeringKnowledge/`). *"The architecture has been verified. Its VALUE has not."* Run zero times at a real minting | CAP-001 stabilization §7.3; operational assessment §3 |

## 2. The identity of KnowledgeOS: the record holds several meanings and one open question

| # | Meaning [SRC] | Date | Source |
|---|---|---|---|
| **KOS-M1** | **KnowledgeOS = the engineering platform (reusable methodology)**; `docs/knowledgeos/` holds ES standards and methodology | 2026-08-01 | ADR_20260801_1740 l.8, l.24, l.54 |
| **KOS-M2** | **KnowledgeOS = a prospective product** *"if their gates open"*; the platform is a **Supporting Subdomain** | 2026-07-27 | DA clarification, cited in ADR_1740 OQ-5; EKS-RM §3, §8 (B-03 K-1…K-6) |
| **KOS-M3** | **KnowledgeOS = where proven, cross-product engineering knowledge graduates.** *"KnowledgeOS never invents engineering practices. It records engineering practices that have already demonstrated value."* · `PublicDigit → Experience → Distillation → KnowledgeOS` · *"KnowledgeOS is where ideas graduate, not where they are born"* | 2026-08-05 | distillation candidate (PREPARED, NOT ADOPTED) |
| **OQ-5** | **"Is KnowledgeOS the same thing as the Engineering Platform (`engineering/`)?"** The ADR's context equates them; canon (the DA clarification) separates them. **Raised 2026-08-01; not resolved.** ENG-009 is blocked on it; the distillation candidate's layer model *"must not be adopted ahead of OQ-5"* | 2026-08-01 | ADR_1740 l.160, l.199, l.203; distillation C-1 |

**[I] Consequence.** The confusion reported in the previous step is **not a reading gap. It is an open governance question in the record itself (OQ-5).** The basic idea can therefore be stated faithfully only with OQ-5 kept open. A fourth candidate meaning:

- **KOS-M4 [?]:** the *theory* developed in the corpus (kernel K, Zero/Gap, invariants, the epistemic pipeline, the Gita lens), known so far only second-hand.

It is unknown which of M1–M3 it serves.

## 3. Knowledge principles stated in PKS architecture

These are cited from AD-1/M6 through the validation report; AD-1 itself has not been read here.

| # | Principle [SRC] | Anchor |
|---|---|---|
| P-1 | **AP-1:** *"knowledge feeds authority; it never holds authority."* A **verdict is an OUTPUT, never a decision** | validation report §1.3 |
| P-2 | **AP-4:** identity is **per-kind and register-scoped**; collisions are prevented by **register discipline, not by a central authority**; no global identifier scheme | §1.3, §1.5 |
| P-3 | **AP-7 / DR-4:** criteria are **used here, owned elsewhere**, and read-only | §1.3, §1.5 |
| P-4 | **AP-8 / DR-8:** a **closed verdict vocabulary**: PASS · PASS AFTER CORRECTION · WARN · FAIL · INCONCLUSIVE · EMERGENT · CERTIFIED. Nothing richer crosses the boundary | §1.5 |
| P-5 | **DR-1 / DR-2:** a **projection is never authority or evidence**; *"source governs; the projection is corrected"*; the direction toward projection must be structurally irreversible (AP-9) | §1.5 |
| P-6 | *"A capability gap is not a boundary"*; *"the elegant-partition error"*: never define a component over an undecided region | §1.2, §1.4 |
| P-7 | **AD-1 §13.8:** *"The architecture cannot be more certain than the model it derives from"* (Medium-High, one corpus) | §1.5 #12 |
| P-8 | **OQ-PKS-7** (open): do the three roots form **one corpus or three**? | §1.1 |

## 4. A knowledge-domain model found in real work (identifier minting discovery, FROZEN)

| # | Finding [SRC] | Anchor |
|---|---|---|
| I-1 | **Domain invariant:** *"An identifier denotes exactly one subject."* This is distinct from "one register row per identifier", which holds and is insufficient (R-68: one row, two denotations) | Part III §4 |
| I-2 | **Commands vs projections:** commands change state (bind · reserve · annotate); projections expose it (register row · citation). *"A command can occur with no projection; a projection can be edited without a command"* | Part III §1 |
| I-3 | **Immutability attaches to the denotation, not the row:** annotation is allowed, re-denotation never | Part III §3 D |
| I-4 | **Two unordered entry paths** for existence: registration and citation (R-70 exists only by citation) | Part II §8.2 |
| I-5 | **The boundary is a lower bound:** *"at least the register and the corpus"*; the set is not closed | Part III §4 |
| I-6 | **Abstraction ladder, discovered top-down:** domain invariant → engineering acts → capabilities → implementations → repository artifacts | closing section |
| I-7 | **Several lifecycles, not one:** the R / PMR / ADR / ES series differ in *when an identifier is minted relative to the decision* | Part I §1 |

## 5. The observation and promotion practice (how knowledge is meant to grow)

- **[SRC] The four promotion tests:** domain independence · repository independence · engineering value · evidence from real work. *Passing all four yields a candidate, not a standard* (distillation candidate).
- **[SRC] Three kinds of knowledge at commission closure:**
  - product knowledge: never promoted;
  - engineering knowledge: a KnowledgeOS candidate, only if repository-independent;
  - local practice: not promoted.
- **[SRC] The ladder:** observation → repeated observation → practice → candidate standard → Decision Authority → a clause of an **existing** standard (never a new one). *"Most commissions must end 'nothing general learned' — that is the healthy outcome."* *"One corpus is one observation."*
- **[SRC] Candidates filed under this practice, all n = 1 and none adopted:**
  - distillation principle;
  - automation for discovery, intent for change;
  - consistency boundary before transaction boundary;
  - discovery does not design;
  - legacy consumer migration;
  - evidence hierarchy for architectural authority (*"directory structure is evidence of organization, not of authority"*);
  - plus observations on classification gaps, documentation debt, qualification timing, three-phase discovery and tenant-qualified cache keys.

## 6. Effect on the basis

- **[I] H-B1 is now partly direct evidence for one meaning.** Under KOS-M3, KnowledgeOS is *by definition* distilled from engineering experience: H-B1 holds **for KOS-M3 by its own stated principle**. That is a candidate's text (n = 1, not adopted), not an established fact.
- **[I] Whether the corpus theory (KOS-M4) is the same "KnowledgeOS" as M1, M2 or M3 is the main open question** for the reconstruction. Its answer must come from the corpus, and from the Authority's answer to OQ-5.
- **[I] PKS's principles (P-1…P-5) and the identifier invariant (I-1…I-3) are the most precise statements so far of a knowledge-governance theory in this repository.** They closely resemble the reported corpus constructs: AX-1 non-collapse, *Claim ≠ Truth*, provenance, FalseZero, *"knowledge feeds authority, never holds it"*. Their relationship to the corpus theory is [?].
