# 04 — Congruence and Per-Operation Equality Bindings (mandate §§10, 11, 12, 13, 14)

## §10 CONGRUENCE IS A SEPARATE AXIS

$$K_1 \equiv K_2 \ \Rightarrow\ \delta(K_1,o) \equiv \delta(K_2,o)$$

| Question (mandate §10) | Answer | Evidence |
|---|---|---|
| which equality is required? | **`≡_K`** — the semantic/observational one | `258.9` |
| for which operations? | *"every valid transformation `T ∈ 𝒯`"* | `258.9` |
| under what preconditions? | *"all admissible common contexts `c`"* | `258.9` |
| local or global? | **both distinguished** — `259.16` | `259` |
| has it been proven? | 🔴 **NO** — *"global congruence cannot yet be proven because the transformation family is not fully closed"* | `259` |
| merely required by the corpus? | ✅ **yes** — *"the critical mathematical requirement. **If it fails, `≡_K` is too coarse**"* | `258.9` |
| is `δ` defined enough to test it? | 🔴 **NO** — commit case unspecifiable; `Γ` derived, no carrier; executed `K₁ is K₀` | this programme |

**Proposition 258-A** (`258.30`) — the formal criterion:
$$\boxed{F \circ T_H = \bar T \circ F} \qquad \text{every mandatory transformation must FACTOR THROUGH } F$$
with the commuting diagram at `258.23`: `π(T_H(H,c)) = T̄(π(H),c)`.

### Executed: `=` is NOT a congruence
`06 §G` / `exec/OUT-t288_audits.txt`: two states equal on the visible part, differing only in
provenance; `TraceOrigin` distinguishes their images. **`k1 =_visible k2 → True`; `T(k1) = T(k2) →
False`.** ✅ Reproduces `258.10` and `258.15` on a toy model.
$$\boxed{\text{Current structural equality is insufficient evidence of semantic equivalence}} \quad (\texttt{258.10})$$

> **Is equality operation-relative?** **YES** — `261.19`, next section.

## §11 PER-OPERATION EQUALITY BINDINGS

**`261.19`'s corpus matrix (7 operations × 5 relations, every cell `UNRESOLVED`) concludes:**
$$\boxed{\text{No single equality relation is adequate for all KnowledgeOS operations.}}$$

| Operation | Required identity | Required equality | Prov.-sensitive? | Observational? | Congruence? | Status | Evidence |
|---|---|---|---|---|---|---|---|
| **Deduplicate** | possible criterion | **4 rival criteria** | possible | possible | 🔴 | **NORMATIVE / domain-dependent** | `261.19` · `261.17` |
| **Merge** | likely relevant | semantic *"required but insufficient"* | potentially required | possibly | 🔴 | **UNRESOLVED** | `261.19` · `25J.43–47` |
| **Retract** | ⚠️ not in `261.19` | — | — | — | 🔴 | 🔴 **NOT ANALYSED** | — |
| **Supersede** | **required** | structural+semantic *insufficient* | potentially | relevant | 🔴 | **UNRESOLVED** | `261.19` · `258.27` |
| **Validate** | possibly | *insufficient alone* | **potentially required** | secondary | 🔴 | **an ASSESSMENT relation, not an equality** | `261.19` |
| **Replay** | relevant to events | current result only | **required** | **required** (history) | 🔴 | **UNRESOLVED** | `261.19` · `261.14` |
| **Authorize** | ⚠️ not in `261.19` | — | — | — | 🔴 | 🔴 **NOT ANALYSED** | — |
| **Policy evaluation** | ⚠️ not in `261.19` | — | — | — | 🔴 | 🔴 **NOT ANALYSED** | — |
| **Publish** | ⚠️ not in `261.19` | — | — | — | 🔴 | 🔴 **NOT ANALYSED** | — |
| **Apply** | ⚠️ not in `261.19` | — | — | — | 🔴 | 🔴 **NOT ANALYSED** | — |
| **Verify** | ⚠️ not in `261.19` | — | — | — | 🔴 | 🔴 **NOT ANALYSED** | — |
| *(also in `261.19`)* **Remove** | **required for targeting** | potentially | context-dependent | relevant | 🔴 | **UNRESOLVED** | `261.19` |
| *(also)* **Revise** | **required** | insufficient | context-dependent | relevant | 🔴 | **UNRESOLVED** | `261.19` · `258.26` |

> ⚠️ **6 of the mandate's 11 named operations (`Retract`, `Authorize`, `Policy evaluation`, `Publish`,
> `Apply`, `Verify`) have NO corpus equality analysis at all.** The mandate says *"do NOT assume this
> operation set is closed"* — **and it is not: `𝒪` is unratified, so the operation set itself is a
> dependency, exactly as `261.23` condition 2 states.**
>
> **`261.28`:** KnowledgeOS *"requires a **typed family** of equality, identity, equivalence, provenance
> and historical relations."* **The decision is not "pick one" — it is "bind each operation", and that
> cannot start before `𝒪` closes.**

**And `δ` is not the only operator needing equality:** `Merge` too — `25J.45` idempotence is
`Merge(K,K)=K` *"or at least **semantically equivalent** to `K`"*, so **Merge's invariants are
equality-relative**; `25J.46` commutativity *"not yet imposed"*; `25J.47` associativity *"a design
goal, not yet a proven invariant"*; `25J.44` non-destructiveness **is** an invariant.

## §12 DEDUPLICATION — what equality does it actually require?

`261.17` gives **four rival criteria** — structural · semantic · identity · provenance-sensitive — and
`261.19` classifies the choice **NORMATIVE/DOMAIN-DEPENDENT**. The mandate's nine sameness kinds:

| Kind | Available? |
|---|---|
| same reference | ✅ `≅_I` in-context (`I_48`) |
| same entity | 🟡 `EntityID`, per bounded context (`38.6`) |
| same assertion | ✅ `id=H(…)` ⚠️ contradictory |
| same content | ✅ `=_V` (`258.4`) |
| same semantics | 🔴 `≡` — not fully decidable (`012 §35`) |
| same provenance | 🔴 `≅_P` — `λ` unbound |
| same observation | 🔴 `25I.6`: same bytes ≠ same observation |
| same state | ⚠️ `≡_K` vs `≈` conflict (`01 §3.3`) |
| same history | 🔴 `∼_H` — `𝒯` open |

### Required counterexamples (mandate §12)

| Shape | Witness | Source |
|---|---|---|
| structurally different, **semantically equal** | *"Nexus 3.69.0 in production"* vs *"the production Nexus runs 3.69.0"* | `25J.4` |
| semantically equal, **provenance-distinct** | `K_1={p,e_1}`, `K_2={p,e_2}`, `e_1≠e_2`, both support `p` | **`261.8`** |
| observationally equal, **operationally different** | same visible state, `TraceOrigin` differs — **EXECUTED** `06 §G` | `258.15` |
| same output on one test, **not extensionally equal** | `f,g` agree on `0..9`, differ at `10` — **EXECUTED** `06 §I` | this step |
| + same content, **different identity** | `(id=1,A)` vs `(id=2,A)`; `Supersede` needs the distinction | `258.17` |
| + same identity, **different epistemic state** | `(1,Proposed)` vs `(1,Accepted)` | `258.18` |
| + **equal properties, different entities** | two servers both at `Version=3.70`; identical configs | `25S.34`·`25S.35` |
| + **not a contradiction at all** | two IPs on one host — `Functional(HasIPAddress)=False` | `25J.13` |

> **Does the corpus supply enough to choose the production criterion? NO.** Four rival criteria, the
> choice classified NORMATIVE, and three of the nine sameness kinds have no procedure.
> **No criterion chosen here.**

## §13 QUOTIENT / CANONICALIZATION — `q : K → K/≡`

| Property | Status |
|---|---|
| defined? | 🟡 `K = [H]_{∼_H}` (`258.12`), `ℋ/≡_𝒯` (`260`) — **candidates** |
| computable? | 🔴 **`260.11`: *"the quotient is not automatically implementable"*** |
| stable? | 🔴 needs a canonical representative; `38.85` says canonicalization must follow evidence |
| congruent with `δ`? | 🔴 **that IS `258-A`, unproven** |
| provenance-safe? | 🔴 **Decision 3** |
| compatible with governance? | 🔴 no `A`-axis order; `25J.49` `Generation ≠ Validation ≠ Authority` |
| compatible with retraction? | 🔴 retraction re-keys `id` (**EXECUTED** `06 §H`) |
| compatible with history? | ⚠️ `∼_F` (folds) is **insufficient** (`258.11`); `258.14`'s biconditional undemonstrated |

$$\boxed{\text{0 of 8. Mathematical equivalence} \neq \text{implementable canonicalization.}}$$
⚠️ **And an equivalence relation needs transitivity to have a quotient at all** — unestablished for
`≡` (`03 §4b` row 13), resolved only for `≅_I` **within a context** (`I_48`).

## §14 HASH / FOLD / STRUCTURAL IDENTITY AUDIT

| Construction | What it establishes | What it does NOT |
|---|---|---|
| `≡_exact` = *"canonical serialization + hash"* (`25J.2`) | artifact identity **relative to a fixed serialization** | ❌ semantic equality; ❌ observation identity (`25I.6`) |
| `id = H(P,e,c,t,Π)` | a key | ❌ a definition — **inconsistent under mutable `e.state`** (EXECUTED) |
| `Hash(R)` (`25I.5`) | byte identity | ❌ *"cannot simply be `Hash(currentRepresentation)`"* (`25I.29`) |
| `∼_F`: `F(H_1)=F(H_2)` (`25I.31`, `258.11`) | fold equality — **decidable** | ❌ behavioural equivalence — **explicitly insufficient** (`258.11`) |

**EXECUTED (`06 §H`):** the same value under two key orders hashes to `4d7c…` vs `9967…` — **unequal**;
under a canonical order both give `9967…`. And `'03/04/2026'` maps to two different dates under two
locales, hashing to `7741…` vs `de01…` — **an ambiguity no serialization can remove** (`38.53`).

$$\boxed{H(x)=H(y) \text{ requires a specified serialization, canonicalization, collision model and scope. Three of the four are unspecified.}}$$
`38.26`: $\boxed{UniqueIdentifier \text{ is strong EVIDENCE, not metaphysical truth}}$

## STATUS
**ESTABLISHED** congruence is a separate axis; `=` is not a congruence (EXECUTED); no single equality
serves all operations (`261.19`); `Merge` is equality-relative too; hash identity is
canonicalization-relative (EXECUTED); fold equality is insufficient · **BOUNDED** `Deduplicate` has
exactly 4 candidate criteria; 8 counterexample shapes catalogued · **NORMATIVE** every per-operation
binding; `Deduplicate`'s criterion; `Merge` commutativity/associativity · **TECHNICALLY OPEN** global
congruence; the quotient (0 of 8 properties); 6 of 11 operations unanalysed · **BLOCKED** `𝒪`, `𝒯`, `δ`
· **DEFERRED** all bindings until `𝒪` closes
