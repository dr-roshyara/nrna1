# Phase 5F — DDD Context Mapping

## Bounded contexts, as evidenced

| Construction | Bounded context | Ubiquitous-language status | Evidence |
|---|---|---|---|
| K-1 (ratified `K_t`) | The **governance/ratification track** — `FA-4`, `D-FA-6`, `claim-registry` C-022 | Authoritative; the 8 primitive names are the *governed* vocabulary | D285-1 §1 |
| K-2 (`(𝒜,ℛ)`, verification lane) | The **verification-lane research track** — Step 272A/272B, `exec/t285_*.py` scripts | Research vocabulary, explicitly **not** ratified (0 occurrences in ratified artifacts, GN-75) | D285-1 §3, D285-6 |
| Sañjaya/Arjuna (`Observation` recovery) | The **Gītā-lens research track** (2026-08-26 origin) | Corpus-formalized, six typed principles, but not itself a governance ratification | seq 0979 |

## Is this a homonym-across-bounded-contexts situation?

**Partially, and precisely characterized rather than merely labeled a homonym.** `Entity`,
`Proposition`, and `Relation` are not simple homonyms (unrelated concepts sharing a word by accident) —
the corpus's own D285-6 demonstrates a genuine, if lossy and non-computable, **projection relationship**
between the two contexts. This is closer to a DDD **"Conformist" or "Anticorruption Layer" pattern
attempt that is incomplete**: the verification lane's `(𝒜,ℛ)` model imports a *subset* of the ratified
context's vocabulary (via the `Assertion`-unpacking projection), declares three primitives explicitly
external (`Event`, `Policy`, `Action`), and has not yet implemented the translation function (`Qualify`)
needed to make the import operationally complete.

## Upstream/downstream relationship

**K-1 (ratified) is upstream; K-2 (verification lane) is downstream**, per the corpus's own evidence:
K-2 is explicitly evaluated *against* K-1's own ratified primitive set (D285-6's entire "Models A-F"
exercise is a test of whether K-2 is derivable from K-1), not the reverse. This matches a DDD
**Customer/Supplier** relationship, with the "customer" (verification lane) currently unable to fully
consume the "supplier" contract (K-1) because the translation function (`Qualify`) is unimplemented —
this is the DDD-pattern name for what D285-6 calls "definable, not computable."

## Anti-corruption layer status

**Attempted but incomplete.** The `π_K` projection (`05`) functions as a partial anti-corruption layer:
it translates most of K-1's vocabulary into K-2's terms, explicitly excludes three primitives as
out-of-scope for K-2's context, and is blocked on one missing translation function. **No full ACL
exists** — this is disclosed as the honest state of the evidence, not repaired or completed by this
phase.

## Shared kernel?

**No.** Nothing in the evidence supports treating `{Entity, Proposition, Relation}` as a DDD "Shared
Kernel" (a deliberately jointly-owned, jointly-evolved subset) — there is no evidence of joint
governance over these three names; K-1 owns them as ratified primitives, and K-2 imports them
one-directionally via a lossy, non-computable projection.

## Published language?

**No evidence of one.** No standalone, versioned "published language" contract document connecting
the two contexts was found; the connection exists only via the D285-6 analysis itself, which is a
*research* artifact, not a governance-ratified interface contract.

## The Sañjaya/Arjuna split as its own bounded-context observation

`Knower ≠ Observer` ("the foundation of the Sañjaya/Arjuna split") is itself a DDD-relevant invariant:
it separates an "Observer" role (Sañjaya) from a "Knower" role (Arjuna), with `Arjuna_K ≠ Sañjaya_K`
explicitly built "on top of, never replacing." This is consistent with a DDD **Entity/Role**
distinction of the same shape Model A's own reconstruction independently found (Krishna≠Sārathi, per
Phase 1's concept register) — **flagged as a candidate cross-model DDD-pattern parallel, not
adjudicated as a cross-model finding**, since that would exceed Phase 5F's own authorized scope.
