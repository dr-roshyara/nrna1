# Phase-2 Software Architecture — Adjudication Pass

| | |
|---|---|
| **Adjudicates** | `STEP2-PHASE2-SOFTWARE-ARCHITECTURE-REVIEW.md` + `PHASE2-SOFTWARE-ARCHITECTURE-DECISION-LOG.md` |
| **Purpose** | ⛔ Prevent **replacing one premature ontology with another premature software ontology** |
| **Discriminating question** | *Could a different architecture satisfy all currently known evidence and protocol invariants?* **If YES → not evidence-forced.** |
| **Date** | 2026-09-22 |
| ⛔ **Not done** | No code · Phase 2 not executed · **protocol §§3A–3B NOT modified** |
| **Result** | **3 EVIDENCE-FORCED · 7 METHODOLOGICALLY-FORCED · 8 REVERSIBLE · 2 PREFERENCE · 3 PREMATURE · 2 OPEN** |

---

## 1. Executive verdict

> ## ⚠️ **The review over-claimed. Applying it unadjudicated would have installed a second premature ontology.**

**Only 3 of 25 proposals are genuinely evidence-forced.** My decision log claimed 13 "EVIDENCE-SUPPORTED" — but that label meant *consistent with evidence*, which is not the same as *forced by it*. Under the correct test, most collapse to **reversible** or **premature**.

**Three self-corrections of substance:**

| | |
|---|---|
| ⛔ **`TheoryThread` as projection — REFUTED by data** | Edges connect **files**; thread members are **objects**; **zero overlap**. There is no object-graph to project from. And 7 of 11 thread fields — including `identity_basis`, the judgement that kept `TH-0003` `UNCERTAIN` — are not derivable from edges. **I proposed this for elegance.** |
| ⛔ **`Assertion` — boundaries cannot be stated** | It does not separate cleanly from `Relationship`, `Derivation` or `Finding`. As specified it *is* the universal-container failure it was meant to avoid. **Downgraded to OPEN**, with a composition-based alternative to test |
| ⛔ **"34 % coverage" — not a measurement** | Unit of analysis was **JSONL rows**, which are heterogeneous, and the mapping was mine. It is an **adequacy diagnostic for one candidate model**, not a metric of architectural quality |

**What survives is the load-bearing core:** the domain correction, the dependency rule, n-ary derivations, the absence distinction, and the non-determination invariant — the last two in **corrected** form.

---

## 2. Adjudication table

| # | Proposal | Review said | ⭐ Adjudicated | Could another architecture satisfy the evidence? |
|---|---|---|---|---|
| **01** | Domain = Theory Reconstruction & Construction | EVIDENCE-SUPPORTED | ⭐ **B · METHODOLOGICALLY-FORCED** | **No.** Under the alternative, theory revision = domain rewrite, which violates the replacement requirement |
| **02a** | *Domain depends on no infrastructure* | — | ⭐ **B · METHODOLOGICALLY-FORCED** | No — it is the hexagonal law itself |
| **02b** | The specific port taxonomy / diagram | EVIDENCE-SUPPORTED | **C · REVERSIBLE** | **Yes.** Many decompositions satisfy the law |
| **03a** | Reject `type = UNKNOWN` | EVIDENCE-SUPPORTED | ⭐ **A · EVIDENCE-FORCED** | **No.** `F0020` gives three non-simple classifications and **splits** one; `F0024` changes several |
| **03b** | Replace with `ClassificationAssertion` | EVIDENCE-SUPPORTED | **C · REVERSIBLE** | **Yes** — a classification value-collection, or classification-as-relationship, both work |
| **04a** | `Maturity` must not be writable | EVIDENCE-SUPPORTED | ⭐ **B · METHODOLOGICALLY-FORCED** | No — `Q22` is unenforceable against a setter |
| **04b** | `Maturity` = computed value object | EVIDENCE-SUPPORTED | **C · REVERSIBLE** | **Yes** — read model, policy result, or materialized projection |
| **05a** | Distinguish not-searched / searched-and-absent | EVIDENCE-SUPPORTED | ⭐ **A · EVIDENCE-FORCED** | **No.** Step-1 §37 check 13 requires `ABSENT_BY_CONTENT` to be *earned* |
| **05b** | A dedicated `SearchRecord` entity | EVIDENCE-SUPPORTED | **C · REVERSIBLE** | ⭐ **Yes** — `Event(kind=SEARCH)` reuses an existing primitive and may suffice |
| **06** | `TheoryThread` → projection | EVIDENCE-SUPPORTED | ⛔ **E · PREMATURE** | ⛔ **REFUTED — see §9.2.** Keep as a provisional research **entity** |
| **07** | `Provenance` → value object | METH-DERIVATION | **C · REVERSIBLE** | **Yes.** Provenance is a **DAG**, not a chain; its exact form is open. The *invariant* is forced |
| **08** | Split `VerificationRun` / `Finding` | METH-DERIVATION | **C · REVERSIBLE** | **Yes** — a `Finding` carrying run metadata also works |
| **09** | `Assertion` primitive | METH-DERIVATION | ⛔ **F · OPEN** | ⛔ Boundaries unstatable — §9.3 |
| **10** | `Derivation` is n-ary | EVIDENCE-SUPPORTED | ⭐ **A · EVIDENCE-FORCED** | ⭐ **No.** *"two of four grounds falsified"* (`T-0016`/`F0024`/`DI-0009`) is unrepresentable with binary edges |
| **11** | `Competition` entity | EVIDENCE-SUPPORTED | ⛔ **E · PREMATURE** | ⛔ **Yes** — §9.4. The corpus never calls these competitions; **I did** |
| **12** | Seven-class comparison ladder | METH-DERIVATION | **B · METHODOLOGICALLY-FORCED** | No — identity is human-judgement, so demanding a match makes correct expert behaviour a defect |
| **13a** | P3A / LLM ≠ truth | EVIDENCE-SUPPORTED | ⭐ **A → B** | Forced by protocol §1 and by P3A being wrong **7 of 25** |
| **13b** | Type-level `Proposal` vs `Assertion` | EVIDENCE-SUPPORTED | **C · REVERSIBLE** | **Yes** — tagging at ingest also works |
| **14** | Invariants as executable tests | METH-DERIVATION | **B · METHODOLOGICALLY-FORCED** | No — §3A.2's whole thesis |
| **15** | Seven-condition freeze | METH-DERIVATION | **B · METHODOLOGICALLY-FORCED** | No — but status vocabulary must widen (§20) |
| **16** | Iterative 2A↔2B loop | METH-DERIVATION | **B · METHODOLOGICALLY-FORCED** | No — a strict sequence contradicts "programming during Phase 2" |
| **17** | JSONL for the pilot | EVIDENCE-SUPPORTED | ⭐ **C · PILOT DECISION** | **Yes.** Reframed — §19 |
| **18** | Packages by bounded context | DESIGN-PROPOSAL | **D · PREFERENCE** | Yes |
| **19** | Human-judgement classification | METH-DERIVATION | **B · METHODOLOGICALLY-FORCED** | No |
| **20** | Ports without technology | METH-DERIVATION | **B · METHODOLOGICALLY-FORCED** | No |
| **21** | `Gap`/`Obligation` unification | RECOMMENDED | **F · OPEN** | Yes |
| **22** | `Evidence` as value object | RECOMMENDED | **C · REVERSIBLE** | Yes |
| **23–25** | Package names · Architecture context · graph DB | DEFERRED | **D / E / D** | Unchanged |
| **+26** | ⭐ Four bounded contexts | (implicit) | **C · REVERSIBLE** | ⭐ **Yes** — §11 |
| **+27** | ⭐ `TheoryObject` = Aggregate Root | (implicit) | ⛔ **E · PREMATURE** | ⛔ **Yes** — §10 |

**Totals:** 3 A · 7 B · 8 C · 2 D · 3 E · 2 F.

---

## 3–8. By class

**3 · EVIDENCE-FORCED (A)** — `03a` reject `type=UNKNOWN` · `05a` the absence distinction · `10` n-ary derivation. *These three are non-negotiable and each cites a specific corpus fact.*

**7 · METHODOLOGICALLY-FORCED (B)** — `01` domain correction · `02a` dependency rule · `04a` maturity not writable · `12` comparison ladder · `13a` external ≠ truth · `14` executable invariants · `15/16/19/20` process rules.

**8 · REVERSIBLE (C)** — `02b` `03b` `04b` `05b` `07` `08` `13b` `17` `22` `+26`. **Implement, but mark revisable; none may be cited later as settled.**

**2 · PREFERENCE (D)** — `18` package layout · `23` names.

**3 · PREMATURE (E)** — `06` thread-as-projection · `11` `Competition` · `+27` aggregate root. **Do not implement.**

**2 · OPEN (F)** — `09` `Assertion` · `21` `Gap`/`Obligation`.

---

## 9. Corrected theory-neutral domain model

### 9.1 The domain correction — verified independently ✅

**Test A · invariant ownership.** Each candidate invariant, asked *"is this a rule about the research process, or a claim about KnowledgeOS?"*

| Invariant | Owner |
|---|---|
| provenance completeness | research process |
| Step-1 immutability | research process |
| L5 unreachable | research **governance** |
| competing formulations coexist | research process |
| failed tests persist | research process |

⭐ **Five of five are process invariants. Zero are theory invariants** — the theory's own invariants are exactly what is unknown (`S-1`'s `bar` undefined; `HA-0007` unresolved).

**Test B · theory change.** If KnowledgeOS theory changes substantially, must the Phase-2 domain model change? **No** — a theory change is new assertions and a `TheoryEvolution` record. **Therefore the domain is not the theory.**

> ### ✅ **CONFIRMED. `B · METHODOLOGICALLY-FORCED`. This is the architectural foundation.**
>
> ⭐ Classified **B, not A**: the corpus does not dictate software domains. The protocol's own adopted invariants do. *That distinction is the point of this pass.*

### 9.2 ⛔ `TheoryThread` — my projection proposal is REFUTED

**Executed against the real registries:**

| Test | Result |
|---|---|
| Thread fields derivable from edges | **4 of 11** |
| ⛔ Not derivable | `primary_theory_object` · `status` · `identity_basis` · `unresolved_predecessor_candidates` · `historically_complete` + reason · `notes` |
| ⛔⛔ Thread members appearing as edge endpoints | **0 of 12** |

> ### ⛔ **Edges connect FILES (`F####`). Thread members are OBJECTS (`T####`). There is no object-to-object graph to project from.**

And `identity_basis` is a **recorded human judgement** — the very content that kept `TH-0003` at `UNCERTAIN` where a similarity function would have merged it. A projection would delete it.

> **Verdict: `TheoryThread` stays a provisional research ENTITY.** ⛔ Do not force projection for architectural elegance. *(My error; the adjudication's caution was warranted.)*

### 9.3 ⛔ `Assertion` — boundaries cannot be stated, so it does not freeze

| Boundary | Statable? |
|---|---|
| vs `TheoryObject` | ✅ **clean** — an object is a *subject*; an assertion is *about* subjects |
| vs `Evidence` | ✅ **clean** — evidence is a locator+quotation with no truth claim; assertions *use* it as basis |
| vs `Relationship` | ⛔ **not clean** — "A relates to B" is asserted by someone, on evidence, revisably |
| vs `Derivation` | ⛔ **not clean** — would become `Assertion(kind=DERIVATION)` with a bespoke payload |
| vs `Finding` | ⛔ **not clean** — a finding is an assertion with a lens and a mode |

**Three of six boundaries fail.** Freezing `Assertion` would make payload structure vary per `kind` — ⛔ **the universal-container failure it was introduced to avoid.**

> ### ⭐ **Better candidate, to be tested in the pilot: composition, not containment.**
>
> Keep `Relationship`, `Derivation`, `Finding`, `Classification` as **distinct types**, and give each a shared trait:
>
> ```
> Asserted:  asserted_by · basis(Evidence[]) · epistemic_level
>            provenance · superseded_by
> ```
>
> This yields uniform openness and provenance **without** dissolving semantics. **Classified `F · OPEN`** — the pilot decides.

### 9.4 ⛔ `Competition` — over-unification

The three Step-1 "contradictions" have **three different shapes**: `C-0007` is 7 assertions vs 1 refutation with **no discriminator**; `C-0009` is a status disagreement **resolved by falsification**; `C-0008` is a *"performative tension"*.

⛔ **The corpus never calls any of them a competition. I did.** Forcing one entity over three shapes encodes a workflow preference as an ontology.

> **Minimum that survives:** `Contradiction` as a relation, **optionally** carrying a discriminator and an outcome class. Whether a set of contradictions constitutes a "competition" is a **research interpretation**, recorded as such. `E · PREMATURE`.

### 9.5 Structural identity vs classification — a distinction the review missed

⭐ **Not every classification is an epistemic claim.** Three kinds must be separated:

| Kind | Example | Nature |
|---|---|---|
| **Structural identity** | *this record is a `Source`* | ⛔ **intrinsic, not contestable** — what the thing *is* in the system |
| **Interpretive classification** | *`T-0016` is a `Proposition`* | contestable, evidence-backed, revisable |
| **Contested classification** | *`D-5` is a knowledge kind, **not** a bounded context* | two or more interpretive claims |

`type = UNKNOWN` conflated all three. **But so would making everything a `ClassificationAssertion`.** Structural identity needs no assertion machinery; only interpretive classification does.

### 9.6 Corrected primitive set

| Primitive | Role | Status |
|---|---|---|
| `Source` · `Event` | entity | forced (Event ≠ Source: 8 of 25 files carry >1) |
| `Evidence` | value object | reversible |
| `TheoryObject` | **entity** — ⛔ **not** aggregate root (§10) | — |
| `Relationship` | entity | reversible |
| `Derivation` | entity, ⭐ **n-ary** | ⭐ **forced** |
| `TheoryThread` | ⭐ **entity** (provisional) | ⭐ **corrected** |
| `Contradiction` | relation + optional discriminator/outcome | corrected |
| `Gap` / `Obligation` | entity | open |
| `Finding` | record | reversible |
| `TheoryEvolution` | append-only event | forced |
| absence-of-search | ⭐ **distinction forced**; form open (`SearchRecord` **or** `Event(kind=SEARCH)`) | corrected |
| `Provenance` | invariant + structure (**DAG**) | reversible |
| `Maturity` | **never writable**; form open | corrected |
| `Assertion` | ⛔ **OPEN** — test composition instead | corrected |

---

## 10. ⛔ `TheoryObject` is not (yet) an aggregate root

**The test:** *what invariant can only be enforced by aggregate status?*

Candidates examined: identity (insufficient — every entity has one) · controlled mutation (assertions about an object are made by *others*, so the object does not own them) · ownership of children (classifications are **not** owned — they are third-party claims) · transactional boundary (**none identified** — nothing in 25 files requires two changes to commit atomically).

> ⛔ **No invariant requires it. `TheoryObject` is an ENTITY; the aggregate boundary is UNRESOLVED.** Declaring one now would fix a consistency boundary before knowing what must be consistent. `E · PREMATURE`.

---

## 11. Bounded contexts — reversible, not frozen

| Context | Independently reasonable? | Owns a model? | What breaks if merged? |
|---|---|---|---|
| **Historical Evidence** | ✅ | ✅ | ⭐ **immutability** — the strongest boundary |
| **Theory Construction** | ✅ | ✅ | L2–L4 confinement |
| **Verification & Testing** | ✅ | ✅ | ⭐ **demote-only** asymmetry |
| **Research Agenda** | ⚠️ partly | ⚠️ thin | little at 27 rows |

⭐ **Only two boundaries are demonstrated by differing invariants** (immutable vs mutable; demote-only vs construct). **Research Agenda is thin.**

> **Verdict `C · REVERSIBLE`.** Model all four, ⛔ **freeze none.** Premature separation costs more than it saves at this size. *Provenance* remains cross-cutting; *Architecture Alignment* remains rejected (its directory is empty because no Reference Architecture exists).

---

## 12. ⭐ Domain model vs record schema — the distinction the review skipped

The review moved from *conceptual primitive* straight to *entity / value object / aggregate*. **These are six separate questions**, and collapsing them is how a schema becomes an ontology:

| Layer | Question | Example — `TheoryObject` |
|---|---|---|
| **Conceptual research object** | what does the research mean by it? | a thing the corpus theorizes about |
| **Domain role** | entity / value / service / policy? | **entity** (§10) |
| **Identity** | what makes two the same? | ⚠️ **unresolved** — §19A's six criteria are *human judgement* |
| **Lifecycle** | how does it change? | by third-party assertion, never self-mutation |
| **Persistence representation** | how is it stored? | one JSONL row *(pilot)* |
| **Projection / read model** | how is it queried? | thread views, distance dimensions |

> ⛔ **A change at the persistence layer must never be read as a change to the conceptual object.** This must be stated in the protocol; it is the mechanism that keeps the schema from becoming the theory.

---

## 13. Architectural invariants — corrected

| # | Invariant | ⭐ Change from the review |
|---|---|---|
| **I-1** | Step-1 artifacts immutable | — |
| **I-2** | Step-2 is an overlay | — |
| **I-3** | Every derived item has a reconstructable provenance chain | — |
| **I-4** | ⭐ **The Phase-2 bounded context has no operation producing L5** | ⛔ **Corrected.** The review said *no L5 in the type system* — that would block Phase 3/4 canonicalization. **Constrain the context, not the global system** |
| **I-5a** | ⭐ **No ordering may silently carry epistemic standing** | ⛔ **Corrected.** The review banned all ranking APIs. Analytical ordering and research-priority ordering are **legitimate and needed** |
| **I-5b** | ⭐ **Canonical selection requires an explicit `AuthorityAct`** | narrowed to *canonical selection* only |
| **I-6** | Competing formulations coexist | — |
| **I-7** | ⭐ Absence of classification representable **as absence** | — |
| **I-8** | Event ≠ Source | — |
| **I-9** | Relationships first-class where required | — |
| **I-10** | Provenance survives transformation | — |
| **I-11** | Failed tests cannot disappear | — |
| **I-12** | Theory evolution append-only | — |
| **I-13** | External comparison ≠ domain truth | — |
| **I-14** | ⭐ **No machine-generated *inference* acquires epistemic authority by virtue of being machine-produced** | ⛔ **Corrected** — see below |
| **I-15** | Maturity never directly writable | form left open |
| **I-16** | `ABSENT_BY_CONTENT` requires a recorded search | form left open |

### ⭐ I-14 corrected — six provenance classes, not two

The review's *"software proposes; only humans assert"* is **too restrictive**: *"this file contains definition X"* is a deterministic extraction needing no human act.

```
SOURCE_ASSERTION        the file itself says it            — deterministic
DETERMINISTIC_DERIVATION reproducible transformation       — no judgement
MACHINE_PROPOSAL        LLM/ML inference                   ⛔ no authority
HUMAN_ASSERTION         a person asserts it
VALIDATED_FINDING       survived an executed test
GOVERNANCE_ACT          ⛔ not available to Phase 2
```

**Enforcement:** `asserted_by` is **mandatory and non-defaultable**; `MACHINE_PROPOSAL` cannot reach L3+ without a `HUMAN_ASSERTION` or `VALIDATED_FINDING` referencing it.

---

## 14–19. The tests

### 14 · `REPRESENTATIONAL_ADEQUACY_TEST` — reframed

⛔ **"34 % coverage" is withdrawn as a metric.** On re-examination: the unit was **JSONL rows**, which are heterogeneous (one `SOURCE-LOCAL-IDENTIFIERS` row covers an entire identifier series; one `THEORY-OBJECTS` row covers one object); several "missing primitives" collapsed into one; and **the mapping was mine** — a richer use of existing primitives could have represented some of the 127.

> ⭐ **Correct reading: an ADEQUACY DIAGNOSTIC for one candidate model, which found two real missing primitives.** It is not a measure of architectural quality and must never be quoted as one.

⛔ **"≥10 statements" is also withdrawn** as arbitrary. Replaced by **required case coverage**: boundary · contradiction · revision · missingness · multi-premise derivation · competing interpretation · emergent concept · abandoned formulation. **Coverage of case types, not a count.**

### 15 · `THEORY_NON_DETERMINATION_TEST` — corrected

Four orderings, only one of which requires authority:

| Ordering | Permitted? |
|---|---|
| **Analytical** (*similarity 0.82 vs 0.76*) | ✅ yes — a measurement |
| **Research-priority** (agenda ranking) | ✅ yes — needed by §13 |
| **Epistemic ranking** (*A is better supported*) | ⚠️ only with stated semantics, never as standing |
| ⛔ **Canonical selection** | ⛔ **`AuthorityAct` required** |

**Test:** construct rivals where all nine heuristics favour A; assert all nine are **reported**, that no output labels A as *standing higher*, that neither status changes, and that promotion fails without an `AuthorityAct`.

### 16 · ⭐ `EMERGENT_DISCOVERY_TEST` *(new — as important as non-determination)*

Inject a discovery unanticipated by Step 1, Step 2, the schema, the taxonomy or the architecture model — a new relationship type, a new object kind, a new structural distinction, a taxonomy/corpus contradiction.

> **Pass:** representable **without modifying the software core.** **Fail:** the architecture is still too closed.

⭐ **Use a real case:** `T-0023` (the occurrence counter as an ordering instrument) was assembled from five files, **none of which states the counter orders anything**, and it was not anticipated by any protocol section.

### 17 · ⭐ `THEORY_REPLACEMENT_TEST` *(new)*

Theory A is represented; later evidence shows A is wrong; B is introduced. **Must hold:** A is not deleted · A is not mutated into B · historical provenance is unrewritten · Step 1 unchanged · existing derivations uncorrupted. **Must remain recoverable:** *A existed · A had evidence · A was challenged and rejected · B emerged later.*

> ⭐ **The software equivalent of no-silent-repair.** `IFR-0012` is the native precedent — `F0020` stamped a falsification verdict and left the falsified body unchanged.

### 18 · ⭐ `THEORY_SPLIT_TEST` *(new)*

`A → {B, C}` with A's historical identity preserved and the split provenance recorded. ⛔ A is not overwritten. **Live case: `T-0015`, whose premise and conclusion were refuted separately (`DI-0008`).**

### 19 · ⭐ `THEORY_MERGE_TEST` *(new)*

A and B later recognized as one family: **both historical objects preserved**, the later relationship recorded. ⛔ **IDs are never merged.** **Live case: `TH-0003`, held `UNCERTAIN` precisely to avoid a premature merge.**

---

## 20. Revised freeze criteria — four statuses, not two

⛔ **Pass/fail forces false certainty.** Vocabulary: `PASS` · `PASS-WITH-OPEN-QUESTIONS` · `FAIL` · `INCONCLUSIVE` *(the test could not decide — recorded, never silently a pass)*.

| # | Condition | Expected realistic status |
|---|---|---|
| 1 | Model adequacy (case-type coverage) | PASS-WITH-OPEN-QUESTIONS |
| 2 | Provenance preservation | PASS |
| 3 | Deterministic reproducibility | PASS |
| 4 | Theory non-determination | PASS |
| 5 | Human-judgement boundary | PASS-WITH-OPEN-QUESTIONS |
| 6 | Adversarial test | PASS-WITH-OPEN-QUESTIONS |
| 7 | Second-run stability | PASS |
| **8** | ⭐ **Emergent discovery** | — |
| **9** | ⭐ **Replacement / split / merge** | — |

**Freeze requires:** no `FAIL`, no `INCONCLUSIVE` on 2·3·4·8·9, and every open question recorded. ⛔ **No numeric score.**

---

## 21. Exact protocol edits now authorized

**11 edits** — `A` and `B` class only, plus the corrections this pass produced. Full text in `PHASE2-ARCHITECTURE-CHANGESET.md`.

`E-01` domain correction (§3B.1) · `E-02` dependency rule replaces the diagram-as-law (§3B.1) · `E-03` remove `type=UNKNOWN`, add the three classification kinds (§3B.2) · `E-04` maturity not writable, form open (§3B.3) · `E-05` absence distinction, form open (§3B.3) · `E-06` n-ary derivation (§3B.3) · `E-07` `TheoryThread` stays an entity — **withdraws the projection claim** (§3B.3) · `E-08` corrected `I-4`/`I-5a`/`I-14` · `E-09` domain-vs-schema distinction (§3B, new) · `E-10` four new tests (§3B, new) · `E-11` reframe the 34 % result as a diagnostic (§3B.3).

## 22. ⛔ Edits that must NOT yet be made

| ⛔ Not now | Why |
|---|---|
| `Assertion` as a primitive | 3 of 6 boundaries unstatable — test composition first |
| `Competition` entity | over-unifies three different shapes |
| `TheoryObject` as aggregate root | no invariant requires it |
| Freezing four bounded contexts | only two demonstrated by differing invariants |
| `SearchRecord` as a named entity | `Event(kind=SEARCH)` may suffice |
| `ClassificationAssertion` as *the* mechanism | rejection of `type` is forced; the replacement is not |
| Package names / layout | unchanged |
| Any persistence commitment beyond the pilot | JSONL is a **pilot decision** |

---

*Traceability: adjudication pass, 2026-09-22 · 27 proposals re-classified under the discriminating test *"could a different architecture satisfy the evidence?"* · 3 EVIDENCE-FORCED · 7 METHODOLOGICALLY-FORCED · 8 REVERSIBLE · 2 PREFERENCE · 3 PREMATURE · 2 OPEN · three review claims self-corrected (thread projection **refuted by executed test**, `Assertion` boundaries unstatable, 34 % withdrawn as a metric) · four new tests added · ⛔ **no code · Phase 2 not executed · protocol §§3A–3B NOT modified** · 11 edits authorized, awaiting explicit approval.*
