# RQ-002 Deliverable 10 — Multi-dimensional Knowledge Meta-Model

**Kind:** synthesis artifact (modeling, not design) — tests the hypothesis that Deliverable 9's seven types are projections of orthogonal dimensions rather than a flat classification.
**Status:** COMPLETE — presented for ARB review. **STOP: no Reference Architecture until the ARB rules.**
**Evidence base (closed):** the RQ-002 chain (charter · raw findings · discovery report · synthesis · taxonomy). No new research; no unsupported concepts.

---

## 0. Verdict first

**The hypothesis is CONFIRMED — in a refined form.** The seven types are not peers on one axis; they decompose into **three genuinely orthogonal dimensions plus one scope dimension**, with two further candidate dimensions (lifecycle regime, validation mechanism) revealed to be **derived functions**, not independent axes. The seven types survive — but as **archetypes** (the densely populated cells of the dimensional space), not as primitives.

```text
Knowledge instance = NATURE × REPRESENTATION × GOVERNANCE-STATUS ( × AUTHORITY-SCOPE )
                     lifecycle regime  = f(Nature)
                     validation method = f(Representation, Nature)
```

## 1. The three smoking guns (evidence that the flat taxonomy conflated dimensions)

1. **The Business-Rules merge (D9 §2).** The same rule landed in *different types depending on its encoding* — Executable when encoded in a test, Definitional/Descriptive when not. One thing cannot change type by changing clothes: **the rule's identity is its Nature; the encoding is a Representation.** The taxonomy's own merge decision was the dimensional split, unrecognized.
2. **The Rationale Gap (B, D — the corpus's central problem).** "Design rationale is the most demanded and least captured knowledge" is precisely the statement that **Decision-nature knowledge is trapped in Tacit representation**. The problem is a *cell* of a matrix (Decision × Tacit), not a type — and the remedy the evidence supports (capture at decision time) is a **representation transition** within an unchanged nature.
3. **The Historical falsification (D9 §2).** D9 already extracted "Historical" as *a status, not a type* — which is exactly what pulling a dimension out of a flat classification looks like. Governance status was the first dimension to escape; this deliverable extracts the rest.

## 2. Decomposition of the seven types

| D9 type | Nature | Representation | Notes |
|---|---|---|---|
| Executable | Constraint / Definition / Behavior-description (varies) | **Executable** | A representation class hosting several natures — first conflation |
| Decision | **Decision** | Recorded (prose record) | The nature in its canonical representation |
| Definitional | **Definition** | Conversational + code identifiers | Nature with a *dual* representation (speech + code) |
| Descriptive | Description-of-state | **Recorded prose** | Nature and representation coincide — why it looked primitive |
| Experiential | **Finding / learning claim** | Recorded | Its "input-only until promoted" property is GOVERNANCE STATUS, not nature |
| Qualification | **Measurement / observation** | Recorded, instrument-produced | Producer constraint (instrument, never author) is a property of the nature |
| Tacit | **ANY nature** | **Tacit / embodied** | The strongest proof: Tacit was never a peer — it is a representation column applicable to every nature (tacit decisions, tacit definitions, tacit skill) |

## 3. The dimensions (derived, with evidence and contradictions)

**Dimension 1 — NATURE** (what the knowledge asserts) · values: **Decision · Definition · Constraint/Rule · Description-of-state · Finding/Experience · Measurement/Observation** (· Skill — tacit-only, see D-R2)
- Evidence: each value has a distinct authority behavior and capture window traced in the corpus (decisions vaporize at decision time — C; definitions live in conversation — B; measurements are instrument-produced — platform practice + C-provenance; findings require claim evaluation — A).
- Contradictory evidence: boundary artifacts (IDDs: decision+description) show instances can *bundle* natures — the model must permit composite artifacts of single-nature claims (consistent with PK-P3 claim granularity).

**Dimension 2 — REPRESENTATION** (how it is expressed) · values: **Executable · Recorded (prose/diagram) · Conversational/Social · Tacit/Embodied**
- Evidence: the trust gradient (r=0.67 → 0.03 — B) is a *representation* gradient: the same architecture content is trusted by how it is expressed, not what it asserts. Agent memory, RAG, and docs findings (D) all vary by representation while holding content constant.
- Contradictory evidence: none against the dimension; against value-completeness — the corpus hints at "structured-data" representations (registries, metadata) between executable and prose; left as a candidate value, not asserted.

**Dimension 3 — GOVERNANCE STATUS** (lifecycle position) · values: **Draft/Candidate · Canonical · Superseded/Historical · Deprecated (with reason) · Archived**
- Evidence: Wikidata ranks (C) ≙ ADR statuses (B) ≙ ROT staged states (C) ≙ McElroy's evaluated/falsified claims (A) — four independent systems, one vocabulary; D9's Historical falsification.
- Contradictory evidence: none; the only dispute (force-resolution vs coexist — C3) is about *transitions*, not about the dimension's existence.

**Dimension 4 — AUTHORITY SCOPE** (where canonicity holds) · values: **Context-local · Project · Platform · Constitutional**
- Evidence: scoped canonicity (C-F3.4: "canonical within a given scope"; global SSOT falsified); definitions are authoritative only inside their bounded context (B); this repository's own EM-001 split of product vs platform ADRs is first-party evidence that decision knowledge carries scope.
- Contradictory evidence: weakest of the four — the value list is thinner in the corpus than the other dimensions; marked **provisional**.

**Rejected as independent dimensions (derived functions):**
- **Lifecycle regime** = f(Nature): decisions → immutable+superseded; descriptions → evolve/regenerate; measurements → immutable records; skill → practice-bound. No evidenced counterexample of a nature freely choosing its regime → recording regime as an axis would denormalize the model. (PK-P5 stands, restated: *the regime is determined by the nature*.)
- **Validation mechanism** = f(Representation, Nature): executable → execution; recorded prose → review-with-code/regeneration; conversational → conversation; tacit → use — with one nature-driven override: Decision-nature requires enforcement-binding regardless of representation (B: the Watson Discovery exception). Derived, not orthogonal.

## 4. Relationships between dimensions

| Pair | Relationship | Evidence |
|---|---|---|
| Nature × Representation | **Orthogonal, unevenly populated** — every nature can in principle take several representations; the empty/painful cells are the findings (Decision×Tacit = the rationale gap; Constraint×Prose = low-authority rules) | §1 smoking guns |
| Nature × Governance status | **Orthogonal** — any nature's instance can be draft/canonical/superseded | four status systems across natures (§3-D3) |
| Representation × Governance status | **Orthogonal with one asymmetry** — tacit knowledge cannot carry explicit status (unmarkable), which is *why* it is ungovernable as artifact (PK-P9 re-derived from the model!) | B, A |
| Nature → Lifecycle regime | **Determined** (function) | §3 rejection |
| (Representation, Nature) → Validation | **Determined** (function) | §3 rejection |
| Representation → default Authority | **Strongly correlated** (executable highest, tacit lowest — PK-P4 restated dimensionally) | B trust gradient |

**A model self-check the ARB asked for implicitly:** PK-P9 (Succession-over-Capture) — previously a standalone principle — now *falls out of the model*: Tacit representation cannot carry Dimension-3 status, therefore tacit instances cannot satisfy the status-at-discovery invariant, therefore they are not governable as artifacts. A principle becoming a theorem is evidence the model is cutting reality correctly.

## 5. Impact on the future architecture (direction only)

- **Governance machinery splits by dimension:** the status vocabulary (D3) is UNIFORM across all knowledge (the constitutional core); validation machinery attaches per REPRESENTATION; capture strategy and lifecycle attach per NATURE; canonicity resolution attaches per SCOPE. Four small mechanisms instead of seven type-sized ones.
- **Storage vs retrieval decouple:** instances are *produced* by nature (a decision is made, a measurement occurs) but *retrieved* by task-driven filters across dimensions ("canonical constraints for this context, executable first") — matching minimal-sufficient-context (PK-P6).
- **The seven archetypes remain the working vocabulary.** The dimensions are the *model*; the archetypes are the *ubiquitous language*. Per the corpus's own minimal-core law (PK-AD4 applied reflexively): do NOT build a five-dimensional bureaucracy — the dimensions govern the machinery; humans and agents keep speaking in archetypes ("an ADR", "a qualification record").
- **Representation transitions become first-class:** the evidence's biggest wins are transitions — rationale: tacit→recorded at decision time; rules: prose→executable; description: prose→regenerated. An architecture aware of the representation axis can name, encourage, and check these transitions; a flat-type architecture cannot express them at all.

## 6. Success criteria check + readiness

1. Hypothesis **confirmed** (refined): 3 orthogonal dimensions + 1 provisional scope dimension + 2 derived functions. ✅
2. Dimensions and values defined, each with supporting AND contradictory evidence. ✅
3. All seven D9 types decomposed (§2); Tacit exposed as a representation column; the taxonomy's own anomalies (Business-Rules merge, Historical falsification) explained as dimensional signatures. ✅
4. Semantic foundation for the Reference Architecture: **the constitutional core (9 invariants) + 10 principles + 4 dimensions + 7 archetypes + 2 derivation functions.** ✅

**The deeper question remains flagged, now sharper:** the Engineering Platform's artifacts occupy the same dimensional space (its qualification records, ADRs, guides decompose identically) — consistent with "one bounded context of a General Knowledge Architecture," still undecided, still architecture-phase.

**STOP — awaiting ARB review before any Reference Architecture is written.**
