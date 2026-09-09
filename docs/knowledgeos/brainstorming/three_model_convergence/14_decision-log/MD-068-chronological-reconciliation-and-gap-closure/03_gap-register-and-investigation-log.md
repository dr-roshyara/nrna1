# MD-068 §03 — Gap Register and Investigation Log

## Gap Register (live)

| Gap ID | Question | Blocking object | Why blocking | Earliest evidence | Status | Next action |
|---|---|---|---|---|---|---|
| GAP-001 | What is `standard` (the acceptance-criterion field of `r`)? | `r` → `Sat` edge | Determines whether `Sat`'s per-requirement computation has a real semantic anchor | `[00-51]` | **CLOSED WITH QUALIFICATION** (investigated below) | none — architectural answer found, computational body remains a disclosed, deliberate open parameter |
| GAP-002 | Which `EC_t` structure survives — the canonical 4-field (`[00-47]`) or Theory-00-21's 6-field (`[05-36]`)? | `EC_t` node itself | Affects whether "the theory" is one lineage or two co-existing branches | `[00-47]` / `[05-36]` | UNRESOLVED — investigated below, not closable from corpus evidence | none further possible without a corpus-native reconciling document, which does not exist |
| GAP-003 | Does `EvalReq` subsume `App`, or was `App` simply dropped? | `Req`→`Eval`/`EvalReq` edge | Determines whether Theory-00-21's `Sat` pipeline preserves the Sep-2 applicability discipline | `[00-55]` / `[05-41]` | **CLOSED WITH QUALIFICATION** (investigated below) | none — functional subsumption found, explicit bridge remains unwitnessed |
| GAP-004 | Is the Theory-00-21 `Sat(K,r,Γ)` definition adversarially valid under this corpus's own standards? | `Sat` node itself | Central to whether the whole reconstruction can treat `Sat` as reliable | `[05-41]` | UNRESOLVED (unrecordable from this corpus as traversed) — see disposition below | the smallest next action for any future phase: an actual independent adversarial review, not further reading |
| GAP-005 | Why do `Δ_t`'s two senses (Sat-gap vs. transition-residue) coexist? | none (does not block the main chain) | Terminology-hygiene only | `[04-27]` | **CLOSED WITH QUALIFICATION** | none — permanent, harmless homonym; recorded in the Theory Object Registry |

## Investigation log

### GAP-001 — `standard`

**Phase A (earliest witness)**: `[00-51]` (2026-09-02), `r=(id,type,scope,content,standard,priority,
validity)`, `standard` glossed "acceptance criterion," body never given.

**Phase B (later occurrences)**: `[05-37]` (Theory-00-21, Part II, 2026-09-06) retypes `r` as an opaque
`r∈Req` with no field list — `standard` is not carried forward by name. `[05-41]` (Part VI) introduces
`Det_r:𝒱×EC→𝕊_sat` (§6.18) and explicitly states "the contracts define different epistemic standards"
(§6.19) — i.e. the *word* "standard" reappears, now attached to `EC`, not to `r`. §6.44 "Thresholds"
(reopened directly, source-verified) states outright: **"the meaning of `S` and `τ` must be defined...
A threshold without semantics is not a mathematical epistemic rule... The exact policy belongs to the
epistemic contract."**

**Phase C (semantic typing)**: this is a **RECLASSIFICATION**, not a refinement or extension — the
acceptance-criterion concept is relocated from being a *field of `r`* (v1) to being *consulted by
`Det_r` from `EC`'s own `Rules` field* (v4/Theory-00-21). The arity of "where does the standard live"
changes from `r→standard` to `(r,EC)→Det_r→status`.

**Phase D (conflict)**: no explicit contradiction — `[05-37]` never states it is dropping or replacing
`[00-51]`'s `standard` field; the non-carry-forward is silent. This is recorded as `UNWITNESSED`
(dependency-graph edge `r-v1.standard ?→ EC-v4.Rules`, not asserted anywhere).

**Phase E (closure test)**: **CLOSED WITH QUALIFICATION.** The *architectural* question ("where does
the acceptance criterion belong") is answered by Theory-00-21's own explicit design discipline: it
belongs to `EC`, consulted via a contract-specific `Det_r`. The *computational* question ("what,
concretely, is that criterion for any given `r`") remains explicitly, self-consciously undefined by
design — this is not an oversight the corpus failed to close; it is a boundary the corpus itself draws
on purpose (§6.44's own reasoning: a threshold's semantics must be supplied per-domain, not
universalized). No further investigation of this specific gap is possible or warranted from corpus
evidence.

**Phase F (registry update)**: `standard` entry in `01_...md` and the `r` rows there stand as written;
this closure is cross-referenced, not a rewrite.

---

### GAP-003 — `App` vs. `EvalReq`

**Phase A (earliest witness)**: `[00-55]` (2026-09-02), `App(K_t,r)`/`App(r,Q_t,C_t,S_t,EC_t)`, an
explicit gate before `Sat` is evaluated, "Applicability ≠ Satisfaction."

**Phase B (later occurrences)**: `App` is never re-cited by name anywhere after `[00-56]`. Theory-00-21
(source-verified directly, both `[05-41]` and `[05-40]`) never uses the string "applicab" as a formal
predicate — only as ordinary prose ("the applicable epistemic contract," "requirements actually
applicable under the current contract and context" — `[05-40]` §5.3, line 138 in source). Critically,
`[05-40]`'s own **Definition 5.1** states: `I_t := Req(EC_t,Γ_t)` **is itself** "the set of requirements
against which the current knowledge state is evaluated" — i.e. `Req(EC_t,Γ_t)`'s own (abstract,
uncomputed) generation function is defined to return only the already-applicable subset of `ℛ`, by
construction, rather than returning all logically-conceivable requirements and filtering them
afterward with a separate gate.

**Phase C (semantic typing)**: `App`'s functional role (a `Req×Context → {applicable, not}` filter) is
**architecturally absorbed** into `Req(EC_t,Γ_t)`'s own signature (`EC_t,Γ_t → 𝒫(ℛ)`) rather than
preserved as a discrete, separately-named step. This is a **GENERALIZATION** in one sense (fewer named
primitives) and a **RECLASSIFICATION** in another (the filtering logic moves from post-`Req`/pre-`Sat`
to inside `Req` itself).

**Phase D (conflict)**: no contradiction; `App` is neither rejected nor retired by name — it is simply
never re-engaged. Recorded as `UNWITNESSED` bridge (`App ?→ Req(EC_t,Γ_t)`-v4, functionally consistent,
never asserted).

**Phase E (closure test)**: **CLOSED WITH QUALIFICATION.** The functional question (does the later
pipeline still filter to applicable requirements before evaluating satisfaction?) is answered YES, by
construction of `Req(EC_t,Γ_t)`'s own definition. The identity question (is this the *same* mechanism
as `[00-55]`'s `App`, deliberately reworked, or an independent re-invention of the same necessity?)
remains genuinely `UNWITNESSED` — no corpus text asserts either reading.

**Phase F**: registry cross-referenced, not rewritten.

---

### GAP-002 — competing `EC_t` structures (4-field vs. 6-field)

**Phase A**: `[00-47]` (canonical, 2026-09-02 00:46), `EC_t=EC(S_t,G_t,Q_t,C_t)`.

**Phase B**: `[05-36]` (Theory-00-21 Part I, 2026-09-06 00:16 — nearly 4 days later), `EC=⟨Req,Rules,
Scope,EvidenceRequirements,TemporalRequirements,AuthorityRequirements⟩`. Both are used consistently and
self-sufficiently within their own respective lineages — `[00-47]`'s own downstream chain (`[00-51]`,
`[00-55]`ff) never needs the 6-field structure; Theory-00-21's own downstream chain (`[05-37]`ff) never
needs the 4-field structure.

**Phase C**: genuinely different types — different field counts, different field names, no declared
type-mapping between them.

**Phase D**: no contradiction is asserted (neither document claims the other is wrong), and no
reconciling document was found anywhere in the 876-file traversal or in the direct source re-check
performed for GAP-001/GAP-003 above.

**Phase E (closure test)**: **UNRESOLVED — genuinely, and this is recorded as the honest disposition,
not a placeholder for more searching.** Per the "never construct a missing bridge silently" rule, no
identity or subsumption relation between `EC_t`-v3 and `EC_t`-v4 is asserted. **This gap does not,
however, block computability of `Sat` within either lineage taken on its own** — each lineage is
internally self-sufficient with its own `EC` — so it is recorded as UNRESOLVED but explicitly NOT
load-bearing for the immediate downstream chain, only for the meta-question of whether "the theory" is
one continuous lineage or two parallel, non-identical reconstructions of the same target chain.

**Phase F**: registry cross-referenced.

---

### GAP-004 — adversarial validity of the Theory-00-21 `Sat` definition

**Disposition, without a new investigation round**: this gap was already the central finding of MD-067
itself (the absence of any adversarial-review event for `[05-41]`'s `Sat` anywhere in the remaining
~114 traversed positions, contrasted against 11 other major claims that were each reviewed within the
same or next session). Re-applying Phase A–D here would not add evidence beyond MD-067's own already-
exhaustive check. **Phase E (closure test): UNRESOLVED — and, per the corpus's own evidentiary
standard demonstrated repeatedly throughout this graph, UNRECORDABLE as anything stronger without new
primary material the current corpus does not contain.** This is the one gap in the register that
genuinely requires new investigative work outside what already-read evidence can supply (an actual
adversarial review would have to be conducted, not found) — named explicitly as the smallest remaining
blocker for the main objective, exactly as MD-067 already concluded.

---

### GAP-005 — `Δ_t` two senses

Already fully characterized in the Definition Registry and Theory Object Registry as a permanent,
harmless `UNRELATED_HOMONYM` (Sense 1 load-bearing for the tracked chain, Sense 2 an independent
"transition-residue" object). No further investigation adds value; **closed with qualification** on
the same basis as `EKS-41`'s own disposition for the `ℛ_req` collision.
