# Encoding packet B

## 1. Task

You are an encoder. Below is a list of concept items (C1 to C28) and a list of numbered statements ("rows"). For each row, list the concept items the row **reads**, as defined by the convention in section 2 and the shared encoding rules in section 3.

- **Encode from the text and convention only; do not search or read any other file.** Do not use outside knowledge of where these texts come from.
- Work row by row. Apply the convention literally, even where it seems to give an odd result.
- If a row reads no concept item, give an empty list.

**Required output format:** a single JSON object mapping each row id to a sorted list of C-ids (sorted by number), or `[]`. Include every row id below exactly once. Example of the format only (not an answer):

```json
{"Rxx": ["C2", "C10"], "Ryy": []}
```

Return the JSON object first. You may then add, after it, one short line per row where you found the decision hard (optional).

## 2. Convention (Interpretation B)

A row reads only the concept items in what the row **asserts** about histories (the formula the statement asserts).

A part of the statement that asserts nothing about histories contributes nothing: a report that the frame lacks an axiom, an empty clause, or a contrast that only delimits what the row asserts. Hence a denial, a non-entailment, or a statement that the frame requires nothing ("nothing to check", "asserts nothing") contributes no items.

## 3. Shared encoding rules

1. **Level of naming.** Items are counted at the level at which the statement names them. A named concept counts as its own item. A spelled-out symbol counts as the item of its concept (glossary below).
2. **Definitions are not unfolded.** A derived term named as a concept (for example "Adopted", or a capability named alone) counts as its own item and is not read through its definition. A definition is unfolded only where the statement text itself uses the defined term's components.
3. **References are not followed.** A row that refers to other rows (by id, such as "R3", or as a set) is assessed by the items its own statement reads, not through those rows. The same holds for references to axioms, definitions, lemmas or remarks (such as "A5", "Def 1.1", "Lem 3.6"): only what the statement's own words say is encoded.
4. If a symbol or term has no glossary entry, decide from the concept list alone whether the text names a concept item.

**Symbol glossary** (symbol → concept item; this only translates notation and says nothing about which items any row reads):

| symbol / word in the text | item |
|---|---|
| actor set 𝒜, actor attribution α(a), α(b), α(w) / α_W, actor identity, keeper / norm-maker / executor as persons | C1 |
| Cap (capability) | C2 |
| target τ(a) of an act; target set 𝒯 | C3 |
| value set V; permitting subset P | C4 |
| value v(a) of an issue act | C5 |
| σ (norm state) | C6 |
| Chg (change points) | C7 |
| issue act; E^iss | C8 |
| adoption act; E^adp; obj | C9 |
| prep (prepared flag); Adopted | C10 |
| an act in general; the act set E; a ∈ E | C8 and C9 |
| d (decision time) | C11 |
| f (entry into force) | C12 |
| record; record set R; record identity; the observation O | C13 |
| r (recording time) | C14 |
| c (record text); text set Σ | C15 |
| k (keeper of a record) | C16 |
| Ref (reference) | C17 |
| con (constitution) / mode | C18 |
| work event; work-event set W; τ(w) | C19 |
| t(w) (work start time) | C20 |
| conforms / violates | C21 |
| T (the time set) | no item |

## 4. Concept items

| id | concept item (working label and one-line definition) |
|---|---|
| C1 | Participant (an actor, i.e. an identity that items refer to; what it is *able* to do is C2, not part of this item) |
| C2 | Capability of a participant for an act kind (issue / adopt), constant within a history |
| C3 | Governed target (the subject a norm is about, e.g. a work package) |
| C4 | Deontic value, with a distinguished subset of values that permit work to begin |
| C5 | Norm as a pair (target, value) only |
| C6 | Norm state (value in force per target per time), held as its own primitive, not computed from acts |
| C7 | Change point of the norm state (target, time at which the value differs) |
| C8 | Issue act (participant, norm, prepared flag). Its decision time is in C11 and its entry into force in C12, both unobserved, so neither is part of this item |
| C9 | Adoption act whose object is an issue act |
| C10 | Prepared / adopted status of an issue act (derived: prepared flag plus capable adoption by a time) |
| C11 | Decision time of an act |
| C12 | Entry into force of an issue act (a time, or never) |
| C13 | Record (identity, recording time, text). The keeper is unobserved in general and belongs to C16, not this item |
| C14 | Recording time of a record (as reported; possibly missing) |
| C15 | Record text as text, including self-asserted statements of force or status ("effective immediately", "remains blocked") held as content |
| C16 | Record keeper |
| C17 | Reference from a record to an act or work event ("carries or references") |
| C18 | Report-vs-constitution mode of a record–act pair, held as *unknown-capable* (record reports the act / record is the act / undetermined) |
| C19 | Work event (target, executor). Its start time is unobserved and belongs to C20, not this item |
| C20 | Work start time |
| C21 | Conformance / violation of a work event against the norm state at its start |
| C22 | Three governance times kept distinct (recording, decision, entry into force), with the work start time outside them |
| C23 | Not-evidenced-in-capture (an act or work event no record references; capture incompleteness) |
| C24 | Execution trace (a capture item ordered by recording order only) |
| C25 | Record independence for a target (keeper is neither norm-maker nor executor; record constitutes no act on that target) |
| C26 | Norm-state attestation channel (an external observable reporting the norm state for a work event's target at its start time) |
| C27 | Mode-undetermined flag on a record–act pair (performative ambiguity as a property of what is observable, not of one history) |
| C28 | Two independent limitation kinds on any norm-state claim: structural non-identifiability vs instrument unreliability |

## 5. Rows to encode

The rows are in a fixed order. Encode each one from its text alone.

**R6.** Work-event attribute typing. Every work event has τ(w) ∈ 𝒯, α(w) ∈ 𝒜, t(w) ∈ T

**R16.** No completeness constraint. The frame does not require that every act or work event is referenced by some record; absence is NOT-EVIDENCED-IN-CAPTURE, not non-occurrence

**R80.** No B-side record clause (R-i′): H-B carries no record requirement; "acts exist" is read as a joint statement about both hypotheses

**R21.** Texts are texts. No axiom ties the text c to σ or f (A5): a self-assertion such as "effective immediately" or "remains blocked" is a value of c, not an observation of σ or f. (Σ and V are not declared disjoint by Def 1.1; the point is the absence of a tie, not a type distinction)

**R11.** Norm state is a total primitive, not computed from acts. σ : T → V^𝒯 is total; no frame axiom derives σ from E. σ is constrained only through F1 (A5): F1 does fix σ(f(a))(τ(a)) = v(a) at every finite entry into force (R1), but nothing determines σ elsewhere from acts

**R79.** B-side record clause replaced by "E ≠ ∅" (R-i): H-B requires only that some act exists, not that a record references it

**R15.** No time order among r, d, f beyond F2; t(w) outside the governance times. The frame imposes no order between r, d and f except d = r under constitution (R3), and treats t(w) as a work attribute, not a governance time

**R9.** Reference and constitution typing. Ref ⊆ R × (E ∪ W); con : E ⇀ R is partial (may be undefined everywhere). If (ρ, a) ∈ Ref and con(a) ≠ ρ, then ρ reports a (definitional)

**R17.** No norm→work dependence. The frame does not require that a norm value implies or excludes any act or work event

**R1.** F1 (entry into force). For every issue act a with f(a) = t ∈ T: σ(t)(τ(a)) = v(a). It says nothing about σ at other times, and nothing about acts with f(a) = ∞

**R14.** No equation between f and d or r ("f ≠ r/d" in the sense *no equation*): the frame never equates entry into force with recording or decision time. It equally imposes no inequality: f may coincide with d or r in some histories

