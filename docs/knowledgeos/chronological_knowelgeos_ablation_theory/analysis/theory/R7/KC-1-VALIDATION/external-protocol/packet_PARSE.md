PROPOSED / NOT RATIFIED; materials only; not executed

<!-- BEGIN PACKET: send to the encoder exactly the text between this line and the END line -->
# Encoding packet H

## 1. Task

You are an encoder. Below is a list of concept items (C1 to C28) and a list of numbered statements ("rows"). For each row, list the concept items the row **reads**, as defined by the convention in section 2, using the symbol glossary in section 3.

- **Encode from the text and the convention only.** Do not search, browse, use tools or read any other file, and do not use outside knowledge of where these texts come from. Do not discuss the task with anyone.
- Work row by row. Apply the convention literally, even where it seems to give an odd result. There is no preferred answer; the study measures how the convention text is read, not whether you agree with it.
- If a row reads no concept item, give an empty list.
- The texts of the definitions, axioms, clauses and rows that a statement or a rule cites (for example "Def 3.1", "A5", "F1", "F2", "R1", "R3", "D-chg", "H-B") are **not supplied** in this packet. Where the convention tells you to read such a text, read only what this packet supplies about it (the statement's own words, the glossary and the concept list), and say so in your reason for that row.

**Required output, in this order:**

1. **A single JSON object** mapping each row id to a list of C-ids sorted by number, or `[]`. Include every row id of section 5 exactly once and nothing else. Use only the forms "C1" to "C28"; do not add question marks, alternatives or comments inside the JSON. Example of the format only (not an answer):

```json
{"Rxx": ["C2", "C10"], "Ryy": []}
```

2. **One reason line for every row**, in the order of section 5: the row id, the rule(s) of section 2 you applied, and why. Mark with "HARD" any row where you found the decision hard.
3. **For each row you treated under rule 6, and only for those:** list the parts you found in its statement, each with the letter (a) to (g) of the rule-6 table you assigned it to, and whether you read it. If a part fits no letter, or more than one, say so.

## 2. Convention

1. **Statement.** What a row reads is taken from its statement (the full text given for the row, including the clauses it names). A row that states a clause or reading of a hypothesis is judged by its clause text only (see rule 3). The listed rows are also subject to rule 6.
2. **Items, at the level named.** Items are counted by the concept table (section 4), at the level at which the statement names them. A named concept counts as its own item. A spelled-out coordinate counts as the item of its concept (symbol glossary, section 3). Ref and con relate records to acts or work events, so a statement reading them also reads the acts or work events it relates them to.
   - **What counts as "reading C1".** A statement reads C1 where its text names an actor: 𝒜, α(a), α(b), α(w) / α_W, k(ρ) ∈ 𝒜, actor identity, a keeper, norm-maker or executor, or a role or office.
   - Per rule 1, the text of a Def 3.1 clause that the statement names counts as read (so a row naming D-chg reads D-chg's "(α(a), iss) ∈ Cap").
   - A derived predicate named as a concept (Adopted, C10) and Cap named alone (C2) count as their own items and are not read through their definitions.
   - Negations ("with no Cap condition", "no Cap or status condition") are not read.
3. **Class rule.** A statement that asserts or uses membership of histories in H-D, H-B or R_B (or their unions or intersections), or separation between them, reads those classes' 𝐑₀ clauses (Def 3.1). These include the record clauses (D-rec, B-recW). The rule does **not** apply to a row that states a clause or reading of a hypothesis: such a row is assessed by its clause text only.
4. **Row references.** A row that refers to other rows (by id or as a set) is assessed by the items its own statement reads, not through those rows. Where a statement names a Def 3.1 clause and also cites a row id, the clause name takes priority: the clause is read through (rule 1), and the row reference is not (rule 4).
5. **Nothing read.** A row reading no item (an absent clause, finite parameters, an item outside the frame) reads nothing: give `[]`.
6. **Listed rows.** The listed rows are R11, R14, R15, R16, R17 and R21. Rule 6 applies to them only; every other row is read by rules 1–5 alone. A listed row reads only the object-level parts of its statement, that is, the parts that themselves assert a formula about histories. A denial, a report or a contrast asserts no such formula. Treat the statement of a listed row part by part, as the table below says. In a part that is read, count items by rule 2 (a clause it names by rule 1, a row id it cites by rule 4). If no part of a listed row is read, give `[]`.

   | part of a listed row's statement | treatment |
   |---|---|
   | (a) the tie or order between items that the row denies | not read (a denial asserts no formula) |
   | (b) a statement of the type, set or attribute of a symbol (for example "x : A → B is total", "x ∈ S", "every x has an attribute y(x) in S") | read |
   | (c) a report that the frame lacks an axiom, a requirement or a constraint, or a remark on how a missing item is to be understood | not read |
   | (d) a contrast that only says what something is not ("…, not Y") | not read |
   | (e) a statement that something may hold in some histories | not read |
   | (f) an exception ("except …", "beyond …") that refers to another axiom or row | not read |
   | (g) a restatement of another axiom or row cited by name or id | not read |

## 3. Symbol glossary

Symbol → concept item. This only translates notation and says nothing about which items any row reads. Every entry is at the level named (rule 2): a derived predicate listed here (Adopted, Chg, conforms / violates) maps to its own item and is not read through its definition.

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
<!-- END PACKET -->
