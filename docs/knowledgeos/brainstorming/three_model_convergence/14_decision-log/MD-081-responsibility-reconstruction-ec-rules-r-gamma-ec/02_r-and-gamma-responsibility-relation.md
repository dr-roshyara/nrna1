# MD-081 §02 — `r` and `Γ`: Object Identity (reused) + Responsibility Relation (new)

Object-identity column reused verbatim from MD-079 §01's own matrices — not re-derived. Responsibility-
relation column is new this phase. Responsibility-relation vocabulary: `SAME RESPONSIBILITY` /
`REFINED RESPONSIBILITY` / `SUBDIVIDED RESPONSIBILITY` / `ABSORBED RESPONSIBILITY` / `REPLACED
RESPONSIBILITY` / `RELATED RESPONSIBILITY` / `NO DEMONSTRATED RELATION`.

## `r` (requirement instance)

The question asked separately from object identity: *does variant X discharge the same job* — naming
and holding the condition a knowledge state must satisfy for one specific requirement — *that the
anchor (`r_B`, T21 Part II Def 2.18) is meant to hold?*

| ID | Object identity (MD-079) | Responsibility relation |
|---|---|---|
| `r_A` (T5 `[00-51]`, 7-tuple) | `RELATED OBJECT` | `SAME RESPONSIBILITY` — holds a requirement's own identity/scope/content/standard/priority/validity; the job is identical even though the object is never source-confirmed identical |
| ⭐ `r_B` (T21 Part II Def 2.18, abstract) | anchor | anchor |
| `r_C` (Part IV, relation type) | `UNRELATED_HOMONYM` | `NO DEMONSTRATED RELATION` — a relation type is not in the business of holding a requirement's own satisfaction condition at all |
| `r_D` (Part IX §9.2, relation instance) | `UNRELATED_HOMONYM` | `NO DEMONSTRATED RELATION` |
| `r_E` (Part IX §9.47, relation instance) | `UNRELATED_HOMONYM` | `NO DEMONSTRATED RELATION` |
| `r_F` (Part X §10.6, inference rule) | `UNRELATED_HOMONYM` | `NO DEMONSTRATED RELATION` — an inference rule governs how conclusions are drawn, not what a requirement demands |
| `r_G` (Part X §10.27, inference rule) | `UNRELATED_HOMONYM` | `NO DEMONSTRATED RELATION` |
| `r_H` (Part 13 §13.58–59, `r_B` refined with `g(r)`) | `SAME CONCEPT, REFINED` | `REFINED RESPONSIBILITY` — the requirement-holding job is unchanged, but its own satisfaction outcome is now reported in a richer 5-value space rather than boolean |
| `r_I` (`kos/inquiry.py`, `Requirement` dataclass) | `RELATED OBJECT` | `RELATED RESPONSIBILITY` — holds a requirement's own identity, target proposition, and a `kind`-classification, and is actually consumed by a working satisfaction check; close enough to count as discharging the same general job, not merely resembling it, though no source states the two are the same object |

**Conclusion for `r`**: separating the two dimensions clarifies what MD-079's own matrix work left
implicit — the confirmed homonyms (`r_C`–`r_G`) are homonyms at *both* levels (neither the same object
nor performing any related responsibility), while the requirement-sense family (`r_A`, `r_B`, `r_H`,
`r_I`) is `RELATED`-to-`SAME` at both levels consistently. There is no case in this family where object
identity and responsibility relation diverge — a useful, disclosed negative finding: the `r` proliferation
is real, but it does not hide any subtler responsibility-only transfer the object-identity matrix would
have missed.

## `Γ` (context)

The question: *does variant X hold the same job* — supplying the interpretive/admissibility parameters
that determine how reasoning about a requirement is scoped — *that the anchor (`Γ_B`, T21 Part II Def
2.15) is meant to hold?*

| ID | Object identity (MD-079) | Responsibility relation |
|---|---|---|
| ⭐ `Γ_B` (Part II Def 2.15, 7-tuple) | anchor | anchor |
| `Γ_C` (Part VIII §8.5, 4-field+ellipsis) | `RELATED OBJECT` | `SUBDIVIDED RESPONSIBILITY` — a strict subset of `Γ_B`'s own fields (Domain, Time, Purpose, Vocabulary), used specifically for *proposition-identity* determination, a narrower job than `Γ_B`'s own general "interpretation and admissibility of reasoning" |
| `Γ_D` (`Γ_I`, Part X §10.31, 6-field) | `RELATED OBJECT` | `SUBDIVIDED RESPONSIBILITY` — scoped specifically to *inference preservation*, adds fields (`Contract`,`Model`,`Rules`) `Γ_B` lacks, in service of a narrower job |
| `Γ_E` (`Γ_R`, Part 21 §21.3, 8-field) | `RELATED OBJECT` | `SUBDIVIDED RESPONSIBILITY` — extends `Γ_D`'s own narrower reasoning-context job with `Authority`/`Resources`, still not the general `Γ_B` job |
| `Γ_F` (`EvalReq`'s own bare usage, Part VI) | `UNRESOLVED` | `NO DEMONSTRATED RELATION` to any of the four structured variants specifically — the usage that matters most for this whole investigation commits to none of them |

**Conclusion for `Γ`**: here, unlike `r`, the object-identity and responsibility-relation dimensions
**do diverge usefully**. At the object level, `Γ_C`/`Γ_D`/`Γ_E` are merely `RELATED OBJECT` (structural
resemblance, no confirmed identity). At the responsibility level, a clearer pattern emerges: `Γ_B`'s own
general-purpose 7-field job appears to have been **`SUBDIVIDED`** across the rewrite into three
narrower, domain-specific contexts (`Γ_C` for identity, `Γ_D`/`Γ_E` for inference), each doing part of
what `Γ_B` alone was meant to do, rather than each being an independent, competing redefinition of the
same general object. This is a genuinely new finding this phase adds: **`Γ`'s apparent four-way
proliferation (MD-078's own `E — object identity unresolved` verdict) is, at the responsibility level,
better described as `SUBDIVIDED RESPONSIBILITY` than as pure, unstructured competition** — the later
Parts are narrowing `Γ`'s own scope for their own local purposes, not simply reinventing it. This does
not change MD-078's own object-identity classification (which stands, unedited, `E`), but it supplies
the missing "why" the object-identity matrix alone could not.

`Γ_F` remains the genuine, unresolved problem: `EvalReq`'s own usage — the one specific usage this
whole investigation concerns — does not commit to the general `Γ_B` job or to any one of the three
narrower subdivisions. This, not the general four-way proliferation, is the actual blocker for
composing `EvalReq(K,r,EC,Γ)`.
