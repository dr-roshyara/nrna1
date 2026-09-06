# 02 — Identity Register (mandate §5 · identity investigated INDEPENDENTLY of equality)

## §5.1 The eleven identity kinds × the mandate's twelve questions

Legend: ✅ established · 🟡 candidate/provisional · 🔴 absent · — n/a

| ID kind | what it identifies | stable? | immutable? | version in id? | scoped? | survives merge? | survives transform? | survives retraction? | semantic or referential? | collision model? | canonicalization? | decision procedure? |
|---|---|---|---|---|---|---|---|---|---|---|---|---|
| **Entity** | a real-world object | 🟡 | 🟡 | 🔴 | ✅ **per bounded context** (`38.6`) | 🟡 `38.37` merge event | ✅ | — | **referential** | ✅ `38.26` | 🔴 | 🟡 4/6-valued |
| **Assertion** | a proposition instance | ✅ `id=H(P,e,c,t,Π)` | 🔴 **`e.state` mutable** | ⚠️ implicitly via `e` | global | 🔴 | 🔴 | 🔴 **re-keys** | **semantic+prov** | 🔴 | ⚠️ unbound | ✅ hash |
| **State (`K_t`)** | a whole knowledge state | 🔴 | 🔴 | 🔴 | 🔴 | 🔴 | 🔴 | 🔴 | 🔴 | 🔴 | 🔴 | 🔴 **no rule** |
| **History** | a transition sequence | 🟡 `F:ℋ→𝒦` | — | ✅ inherently | 🔴 | — | — | — | behavioural | 🔴 | 🔴 | 🔴 |
| **Event** | a domain event | 🔴 | 🔴 | 🔴 | 🔴 | 🔴 | 🔴 | 🔴 | 🔴 | 🔴 | 🔴 | 🔴 **no schema** |
| **Operation** | an element of `𝒪` | 🔴 | 🔴 | 🔴 | 🔴 | — | — | — | 🔴 | 🔴 | 🔴 | 🔴 **`𝒪` unratified** |
| **Authority-act** | a human act | 🔴 | 🔴 | 🔴 | 🔴 | — | — | — | 🔴 | ⚠️ **two acts already collided on one `grantId`** | 🔴 | 🔴 |
| **Policy** | a policy instance | 🔴 | 🔴 | 🔴 | 🔴 | — | — | — | 🔴 | 🔴 | 🔴 | 🔴 |
| **Provenance** | `Π` | ✅ | ✅ | — | intrinsic | ✅ | ✅ | ✅ | **semantic** | 🔴 | — | ✅ `t=0` proof |
| **Record** | a stored artifact | 🟡 `RecordID` (`25S.36`) | 🟡 | ✅ | 🔴 | — | — | — | **referential** | 🔴 | 🔴 | 🟡 |
| **Knowledge-artifact** | `KAID` — a meaning | 🟡 `Identity(CanonicalAssertion, Context)` | 🟡 | 🔴 | ✅ per context | 🟡 | 🟡 | 🔴 | **semantic** | 🔴 | ⚠️ unbound | 🔴 |

**Score: 2 of 11 established (Assertion — with a live contradiction; Provenance). 5 of 11 wholly absent.**

## §5.2 Two orthogonal identity axes — do not conflate them

| Axis | Members | Source |
|---|---|---|
| **by LEVEL** | `EntityID` ≠ `KAID` ≠ `RecordID` | `25S.36`, `25S.43` |
| **by KIND** | the eleven rows above | this programme + corpus |

`25S.43`: `KAID ↔ EntityID` is *"itself an explicit, versioned semantic relationship"* — **not an
equality, a relation requiring its own semantics.**

## §5.3 `id = H(P,e,c,t,Π)` — the mandate's §5 question answered

> **Which is it: an established state identity definition · a candidate construction · a hash-based
> implementation suggestion · or something else?**

$$\boxed{\textbf{A hash-based IMPLEMENTATION SUGGESTION that is internally inconsistent — not a definition.}}$$

**Evidence, in order of strength:**

1. **`EXECUTED`** (`06 §H`, `exec/OUT-t288_audits.txt`): with `e.state` mutable, withdrawal changes the
   id (`5e0a61aeb3ab → 545541441539`), so **every `ℛ`-edge into it dangles** → `StructuralValid`'s own
   no-dangling clause **VIOLATED**.
2. **`EXECUTED`** (`06 §H`): `H(x)=H(y)` is meaningful only relative to a canonicalization — key-order
   alone flips the verdict (`4d7c…` vs `9967…`).
3. **`CORPUS` — and it ruled against this construction BEFORE the construction existed:**
   - `25I.29`: *"the identity of the knowledge object **cannot simply be `Hash(currentRepresentation)`**"*
   - `25I.30`: *"we should **not mutate `K_1` into `K_2`**"* → $\boxed{KnowledgeIdentity\ persistent;\ KnowledgeState\ evolves}$
   - `258.2`: $\boxed{Identity\ persistence \neq value\ equality}$ — *"exactly what Revision requires"*
   - `258.18`: `(id=1,Proposed)` vs `(id=1,Accepted)` → $\boxed{Identity\ alone\ cannot\ define\ Knowledge\text{-}State\ semantics}$
   - `38.26`: $\boxed{UniqueIdentifier\ \text{is strong EVIDENCE, not metaphysical truth}}$
   - `195.53` `I_51`: domain identity from **domain semantics**, not technical representation

> **NOT PROMOTED.** Classification: **`CORPUS PRINCIPLE vs LATER CONSTRUCTION CONFLICT` — a governance
> matter, not an engineering fix.** *(Candidate repair on record — project mutable `state` out of `id`.
> **Not adopted, not recommended as architecture.**)*

## §5.4 Identity is CONTEXT-SCOPED, and that is what makes it work

| `38.6`·`38.79` | $\boxed{RealWorldIdentity \neq DomainObjectIdentity}$ |
|---|---|
| `38.7` | *"KnowledgeOS must **not** globally impose `GlobalEntityIdentity`"* |
| `38.81` | $\boxed{Identity\ is\ contextual}$ |
| **`195.16` `I_48`** | $\boxed{\textit{Identity equivalence must be transitive \textbf{within a defined identity context}}}$ |
| `195.15` | the violation: `A≡B ∧ B≡C ∧ A≢C` — an **identity consistency violation** |

> ✅ **This is the one place the equality/identity problem admits a real narrowing:** `≅_I` **is** an
> equivalence relation within an identity context and **is not** one across contexts. Quotients exist
> per-context.
> ⚠️ **BOUNDED, not established: *"identity context"* is defined nowhere.** `I_48` is well-formed with
> an unbound parameter — the same defect shape as `≅_λ`'s `λ` and `≈`'s `𝒪_K`. **`I_48`–`I_51` are
> numbered invariants in a research step with no adoption record.**

## §5.5 Identity ⊥ other dimensions

- **`195.24`** identity ⊥ lineage — three cases: same id/different states · different id **with**
  continuity · different id, no continuity
- **`261.9`** identity ⊥ provenance equality — *"independent dimensions"*
- **`258.17`/`25S.34`** `SameState ⇏ SameEntity` · **`25S.35`** `SemanticEquivalent ⇏ SameEntity`
- **`38.77`** $\boxed{Similarity \neq Identity}$ · **`38.78`** $\boxed{Reference \neq Entity}$

## §5.6 When does identity MATTER? — the corpus supplies a criterion, not a list

$$OperationalIdentity(x) \iff \exists T \in \mathcal T:\ \text{changing } id(x) \text{ can change } T(K,c) \qquad (\texttt{258.20})$$
generalized (`258.21`) to any property: `Relevant(p)` iff some mandatory `T` distinguishes states
differing only in `p`.

⚠️ **Both quantify over `𝒯`, which is unenumerated. The criterion is correct and inapplicable.**

## §5.7 Identity is itself knowledge — so it needs its own machinery

`38.76`: $\boxed{Identity\ is\ itself\ knowledge}$ · `38.10` `IdentityStatus ∈ {Unknown, Candidate,
Probable, Confirmed, Rejected, Contextual}` · `38.80` $\boxed{Probabilistic\ identity\ must\ not\
silently\ become\ confirmed\ identity}$ · `38.22` $\boxed{UnknownIdentity\ should\ remain\ Unknown}$ ·
`38.42` **`Closure(Identity)`** with `AffectedKnowledge(x)` · `38.88`/`195.43` **`G_I` is a SEPARATE
graph** that *"must not be collapsed"* — yet the canonical kernel has one `ℛ`, reduced to 3 fields.

## STATUS
**ESTABLISHED** identity ≠ equality; identity is contextual; identity ⊥ lineage; `Π` identity;
`Identity is itself knowledge` · **BOUNDED** `≅_I` transitive within a context (`I_48`, parameter
unbound); `IdentityStatus` codomains enumerated · **NORMATIVE** define *identity context*; ratify
`I_48`–`I_51`; ratify or reject `EntityID`/`KAID`/`RecordID`; is `G_I` a kernel component ·
**TECHNICALLY OPEN** 5 of 11 identity kinds absent; `K_t` has no identity rule; `Closure(Identity)` ·
**BLOCKED** `𝒯` (for `258.20`/`258.21`) · **DEFERRED** the `id`/mutable-`state` conflict → governance
