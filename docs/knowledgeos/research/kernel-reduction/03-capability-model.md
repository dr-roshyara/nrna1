# 03 — Capability Model (Parts II & III)

## Part II — what "minimal" means here

Five distinct notions, **not** assumed equivalent:

| | Notion | Definition | Role in this experiment |
|---|---|---|---|
| **A** | Cardinality minimality | `|C*|` as small as possible | *secondary result only* |
| **B** | Semantic minimality | no member removable without losing an irreducible capability | **primary** |
| **C** | Compositional minimality | every retained member contributes a capability not reproducible by composition of the others | **primary** |
| **D** | Architectural minimality | no separate domain responsibility where one is naturally derivable from another | §13, reported separately |
| **E** | Cognitive universality | required by *every* epistemic system | **out of scope — not tested, not claimed** |

The experiment targets **B + C**. A result that improves A while worsening B/C is reported as a
*worse* result, per the protocol's final principle.

Formally (Part XIX), for `S ⊆ O`:

```
C_required ⊆ Reach(S)                    (adequacy)
∀ o ∈ S :  C_required ⊄ Reach(S \ {o})   (irredundancy)
```

`Reach` is defined constructively in §06 — it is *not* declared to contain whatever the researcher
wishes it to contain.

---

## Part III — the capability space

Capabilities are stated **independently of operator names**. The initial list is the protocol's
C1–C24. Three modifications were made; each is justified below, and none was made to shrink the
operator set.

### The model (25 capabilities)

| ID | Capability | Realized by carrier | Kind |
|---|---|---|---|
| C1 | acquire an observation | `Observation` | artifact |
| C2 | preserve/construct semantic meaning | `SemanticContent` | artifact |
| C3 | construct a representation | `Representation` | artifact |
| C4 | relate representations/claims | `Relation` | artifact |
| C5 | discriminate alternatives | `Discrimination` | artifact |
| C6 | formulate hypotheses | `Hypothesis` | artifact |
| C7 | perform inference | `Claim` (warrant kind DEDUCTIVE) | artifact |
| C8 | detect epistemic insufficiency | `Gap` | artifact |
| C9 | challenge a claim/model | `Defeater` | artifact |
| C10 | validate a claim/model | `Verdict` | artifact |
| C11 | revise epistemic state | atom `state-mutation` | power |
| C12 | determine inquiry adequately resolved | `Determination` | artifact |
| C13 | select a next action / answer | `Decision` | artifact |
| C14 | represent context | `SemanticContent` (context is an *input* of the meaning rule) | invariant |
| C15 | represent temporal conditions | atom `state-mutation` | invariant |
| C16 | represent uncertainty | `Verdict` | invariant |
| C17 | represent assumptions | `Verdict` | invariant |
| C18 | preserve provenance | — | invariant **(non-discriminating, see below)** |
| C19 | preserve alternative hypotheses | `Hypothesis` + `Discrimination` | invariant |
| C20 | support non-identifiability | `Hypothesis` + `Discrimination` (UNDECIDED verdict) | invariant |
| C21 | distinguish observation from interpretation | `Observation` **and** `SemanticContent`, distinct | invariant |
| C22 | semantic equivalence without representation identity | `SemanticContent` + `Relation` | invariant |
| C23 | produce a smallest adequate answer | `Determination` | artifact |
| C24 | support learning from new observations | `Observation` + `state-mutation` | artifact |
| **C25** | **admit an observation as evidence under a policy** | `Evidence` | **ADDED** |

### Modification M-1 — C25 added `[CORPUS]`

**Justification.** The corpus carries `Qualify : Observation × Policy ⇀ Evidence` as an irreducible
gap (`G1`), and the protocol's own constraint 9 separates *information* from *evidence*. The initial
C1–C24 list has a capability for acquiring observations (C1) and for validating claims (C10) but
**none for the transition between them**. Without C25, C10 is unrealizable in any model where a
`Verdict` requires evidence — which is precisely the model the corpus supports.

This modification **enlarges** the required capability set and therefore makes minimization *harder*,
not easier. It cannot be a convenience.

### Modification M-2 — C18 reclassified as NON-DISCRIMINATING `[NEG]`

Provenance is carried structurally by the reach engine: every artifact records its derivation DAG.
Therefore **no operator set can fail C18 in this simulator.** Rather than report a meaningless pass
for all 14 arms, C18 is marked non-discriminating. *This is a limitation of the instrument, not a
finding about the kernel.* See §16.

### Modification M-3 — C20 given an explicit failure mode

"Support non-identifiability" was under-specified: any system trivially "supports" it by saying
nothing. It is operationalized as: the system must be able to hold ≥2 hypotheses **and** return an
`UNDECIDED` discrimination. It can then fail by **overreach** — producing a `Determination` while
unable to represent the alternatives it is determining between. Property P2 (§12) tests exactly this.

### Modifications considered and REJECTED

| Proposal | Why rejected |
|---|---|
| merge C2 and C3 (meaning + representation) | corpus explicitly separates `Observation ≠ Proposition ≠ Knowledge`; merging is tested instead as robustness variant **V1** (§12), not adopted |
| merge C12 and C13 (determine + select) | different carriers, different inputs (`Inquiry` vs `Objective`); tested as **V5** |
| drop C18 | dropped from *scoring*, retained in the model, and its untestability reported |
| merge C19 and C20 | C19 is retention, C20 is refusal-to-collapse; a system can do the first and fail the second |
