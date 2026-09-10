# MD-087 §02 — `EC` and `r`: Deep Reconciliation Results

## Pair 1 — `EC₀` (`step-023`, 7-field) ↔ T21 `EC` (`EC₆`/`EC₇`, 6-field)

**Sweep**: `(extends|refines|formaliz|special case|instance of|projection of|restriction of|earlier
definition|earlier version) (the )?(epistemic contract|EC)`, across `phase_measure_theory/` and
`mathematical_ideas_that_can_be_implemented/`, the complete 425-file window between the two birth
points. **Result: zero hits.**

**Mathematical reconciliation test**:

| Test | Result |
|---|---|
| Domain/codomain | n/a — both are data containers, not functions |
| Arity/fields | 7 vs. 6, four names closely matching (`Requirements`≈`Req`, `EvidenceRules`≈`ER`, `TemporalRules`≈`TR`, `AuthorityRules`≈`AR`) |
| Structure | partial overlap only — three `EC₀` fields (`Purpose`,`UncertaintyLimits`,`ConflictRules`) have no counterpart; two `EC₆`/`₇` fields (`Scope`,`Rules`) have no counterpart |
| Semantics | plausibly consistent, never source-compared |
| Dependencies | both feed a `Req(EC)`-shaped function — consistent |
| Context | same overall "epistemic contract governs requirement satisfaction" concern |
| History | **no explicit predecessor/successor statement anywhere in the 425-file window** |
| Equivalence | not demonstrable — the unmatched fields have no stated disposition (dropped? merged? renamed?) |

**Verdict: `FIELD ECHO ONLY`, not identity — confirmed, not merely repeated.** This is the exact
distinction the mission's own §3 names precisely: a striking name correspondence exists, but per the
mission's own instruction ("if nowhere [does the corpus establish the mapping]: `FIELD ECHO ONLY`, not
identity"), the correct classification is now stated with that exact vocabulary. Object identity:
**`RELATED OBJECT`**, unchanged from MD-086, now confirmed by a targeted 425-file negative search rather
than by citation-check alone.

## Pair 2 — `r_A` (`T5` `[00-51]`, 7-tuple) ↔ `r_B` (T21 Part II Def 2.18, abstract)

**Sweep**: same bridging pattern with `requirement`, same window (`T5` Sep 1/2 → T21 Sep 6). **Result:
zero hits.**

**Test**: `r_B`'s own Definition 2.18 gives no field list against which `r_A`'s own seven fields
(`id,type,scope,content,standard,priority,validity`) could be checked — the abstraction is total, not
partial. No structural test is even possible without inventing a correspondence. **Verdict:
`RELATED OBJECT, RECONSTRUCTED` — unchanged, now confirmed by direct sweep rather than left as an
unexamined gap.**

## Pair 3 — `r_I` (`kos/inquiry.py`, `kind`-dispatched) ↔ `r_B`, specifically the `causal` component

**Checked directly**: `kos/inquiry.py`'s own source (`research/knowledgeos-sim/kos/inquiry.py`, lines
8, 54–55) defines `kind: str = "determined" | "unique" | "corroborated" | "causal" | "provenanced"`
with **no comment, docstring, or citation connecting `causal` (or any `kind` value) to `r_B`'s own
Definition 2.18 or its eleven-topic list**. A grep for any `theory-part`/`Det_r`/`EvalReq` reference
anywhere in the entire `research/knowledgeos-sim/` tree returns zero hits.

**Answering the mission's own four framing questions directly**:
- *Is `causal` an extension?* No evidence either way — `NOT FOUND`, not `NO` (absence of a citation is
  not proof of independent invention, only proof of no demonstrated connection).
- *Is it a different requirement ontology?* Plausible, given the total absence of any cross-reference,
  but not proven — the code is simply silent about its own relationship to the narrative theory.
- *Is it a contextual specialization?* No evidence.
- *Is there a source-stated transformation?* **No — confirmed by direct search of the only candidate
  source (`kos/inquiry.py` itself).**

**Verdict: `UNRESOLVED / NOT FOUND`** — sharper than MD-081's own "no source states this correspondence"
because this phase specifically searched the *executable* source's own comments for a cross-reference,
not merely the narrative theory's own text, and found none there either.

## Consolidated `EC`/`r` sweep result

Every bridging-language sweep performed for these two families returned zero hits. This is not a
failure of this phase's own method — it is the method's own genuine output, precisely matching the
mission's own closing instruction: report "what the corpus actually establishes and what it does not,"
not what would make the theory look more complete.
