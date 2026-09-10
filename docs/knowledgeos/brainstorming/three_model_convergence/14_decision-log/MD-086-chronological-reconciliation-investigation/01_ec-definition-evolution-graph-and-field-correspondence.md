# MD-086 §01 — `EC` Definition Evolution Graph and Field-by-Field Correspondence

Ten formulations, numbered `EC₀`–`EC₉` per MD-081 §03's own ledger, reused as the base population.
Every pair below tested against the mission's own six-part method: structural (type-preserving
mapping?) · semantic (same responsibility per field?) · dependency (same consumed objects?) · context
(same bounded context?) · historical (explicit predecessor/successor statement?) · mathematical
(equivalence demonstrable without an invented transformation?).

## Pairs with strong, already-source-stated relationships (reaffirmed, not re-derived)

| Pair | Structural | Semantic | Dependency | Context | Historical | Mathematical | Verdict |
|---|---|---|---|---|---|---|---|
| `EC₆`(Part I)↔`EC₇`(Part II) | identical fields | identical | identical | same document, same rewrite | **explicit** — Part I promises, Part II delivers "the exact structure" | trivial (literal restatement) | **`SAME OBJECT`** |
| `EC₀`(`step-023`)↔`EC₈`(`DEFINITION-VERIFICATION-REGISTER.md`) | identical | identical | identical | verification lane quoting the math lane | **explicit** — direct verbatim quotation | trivial | **`SAME OBJECT`** |
| `EC₀`↔`EC₁`–`EC₄` (`step-025` series) | 7→2→4→9→8 fields, non-monotonic | plausibly consistent (contract-for-purpose) | not independently checked this phase | same directory/programme (`phase_measure_theory/step-025`) | **explicit** — the verification lane's own prior "EC SIGNATURE DRIFT" audit treats these as one evolving object | not demonstrable without accepting the drift as unexplained | **`SAME OBJECT, REFINED (undisclosed drift)`** — reused from the verification lane's own governed finding (MD-078/081), not re-derived |

## The new finding: `EC₀`/`step-023` (birth) ↔ `EC₆`/`EC₇` (T21)

| `EC₀` field | `EC₆`/`EC₇` field | Name correspondence |
|---|---|---|
| `Purpose` | *(none)* | no match |
| `Requirements` | `Req` | **close match** |
| `EvidenceRules` | `ER` (`EvidenceRequirements`) | **close match** |
| `UncertaintyLimits` | *(none)* | no match |
| `ConflictRules` | *(none — plausibly absorbed into the generic `Rules`)* | no clean match |
| `TemporalRules` | `TR` (`TemporalRequirements`) | **close match** |
| `AuthorityRules` | `AR` (`AuthorityRequirements`) | **close match** |
| *(none)* | `Scope` | new in `EC₆`/`EC₇` |
| *(none)* | `Rules` | new, generic — plausibly a merge of `EvidenceRules`+`ConflictRules`+`UncertaintyLimits`, **never stated** |

**Structural**: four of seven `EC₀` fields have near-identical named counterparts in `EC₆`/`EC₇` — a
materially stronger correspondence than this reconstruction's earlier "seven competing, never
reconciled" framing gave credit for (MD-078/081). **Semantic**: each matched pair plausibly performs
the same job (requirements, evidence rules, temporal rules, authority rules). **Dependency**: both
feed a `Req(EC)`-shaped function. **Context**: same overall "epistemic contract governs requirement
satisfaction" bounded context. **Historical**: **no explicit citation exists either direction** —
`step-023` is never cited by name anywhere in the T21 rewrite (already independently confirmed, MD-069's
own citation check), and T21 never claims descent from it. **Mathematical**: no stated mapping exists
for `Purpose`, `UncertaintyLimits`, `ConflictRules` (dropped or merged, never stated which), or for
`Scope`/`Rules` (added, never stated from where).

**Verdict: `RELATED OBJECT` — a materially stronger structural correspondence than any other cross-
lineage `EC` pair found in this investigation, but not `SAME OBJECT` or `SAME OBJECT, REFINED`,
because the historical and mathematical tests both fail (no citation, no stated field mapping for the
non-matching fields).** This is recorded as the single most consequential *new* finding of this phase —
not proof of identity, but the strongest evidence yet that T21's own `EC` was not invented from
nothing, and most plausibly descends from (or independently reconstructs with unusual fidelity) the
theory's own founding `EC` concept, four of its seven fields carried through nearly unchanged in name.

## Other pairs, tested and found unresolved (reaffirming, not overturning, prior findings)

| Pair | Verdict | Basis |
|---|---|---|
| `EC₅`(`T5` `[00-47]`, 4-field)↔`EC₆`/`EC₇` | `UNRESOLVED` | no field-name correspondence at all (`S_t,G_t,Q_t,C_t` vs. `Req,Rules,Scope,ER,TR,AR`); no citation; MD-067's own independence check already confirmed no direct citation from the math lane's own birth to `phase_measure_theory/` |
| `EC₀`↔`EC₅` | `RELATED OBJECT, RECONSTRUCTED` (unchanged) | same general concept, `UNWITNESSED` documentarily — MD-078 `TP-1` |
| `EC₉`(`kos/inquiry.py`)↔`EC₅` | `RELATED OBJECT, RECONSTRUCTED` (unchanged) | shares `DEF-20/21/22` citation IDs — MD-078/080 |
| `EC₉`↔`EC₆`/`EC₇` | `UNRESOLVED`, new observation | `EC₉.requirements`≈`EC₆.Req` (plausible); `EC₉.standard` is thematically adjacent to `EC₆.Rules`'s own unfilled slot — **this is a responsibility-level observation, not an object-identity claim**; `EC₉`'s own `standard` field is a *different* dataclass (`EpistemicStandard`) with its own unrelated fields, so no structural correspondence is established, only a suggestive naming echo |

## Definition Evolution Graph, final form

```
EC₀ (step-023, 7-field, BIRTH)
 ├──[SAME OBJECT]──> EC₈ (DEFINITION-VERIFICATION-REGISTER, verbatim quote)
 ├──[SAME OBJECT, REFINED, undisclosed drift]──> EC₁→EC₂→EC₃→EC₄ (step-025 series, 2→4→9→8 fields)
 ├──[RELATED OBJECT, RECONSTRUCTED, UNWITNESSED]──> EC₅ (T5, 4-field)
 │                                                    │
 │                                                    └──[RELATED OBJECT, RECONSTRUCTED]──> EC₉ (kos/inquiry.py, 4-field)
 └──[RELATED OBJECT, strongest field-correspondence found, still UNWITNESSED historically]──> EC₆ (T21 Part I, 6-field, promise)
                                                                                                 │
                                                                                                 └──[SAME OBJECT, explicit internal refinement]──> EC₇ (T21 Part II, 6-field, delivered)
```

**No single canonical `EC` is established, and none should be inferred from this graph.** What *is*
established: two genuine `SAME OBJECT` pairs (`EC₆`/`EC₇`; `EC₀`/`EC₈`), one governed-source-attested
`SAME OBJECT, REFINED` lineage (the `step-025` series), and — new this phase — a materially stronger,
though still unwitnessed, structural correspondence between the theory's own birth `EC` and its final
T21 formulation, than this reconstruction had previously credited.
