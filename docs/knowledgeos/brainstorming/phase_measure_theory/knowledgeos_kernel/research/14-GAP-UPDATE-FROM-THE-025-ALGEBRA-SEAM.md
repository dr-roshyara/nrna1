---
artifact: 14 — GAP UPDATE FROM THE `025x` ALGEBRA SEAM (25I · 25J · 25S · 038)
trigger: user directed me to `20260828-093700_step-025i-knowledge-identity-algebra.md`
date: 2026-08-31
verdict: **`REFINED-STEP-288.md` was built on an incomplete evidence base. Six of its claims are corrected here; one is REFUTED.**
---

# 14 — The `025x` algebra seam

## 0. What happened, stated plainly

**Step 288 was written without consulting the `025i–025z` seam.** That seam contains, at
**2026-08-28** — *two days before Step 246 and Step 260* —

| File | Subject |
|---|---|
| `20260828-093700_step-025i` | **Knowledge Identity Algebra** |
| `20260828-093812_step-025j` | **Semantic Equivalence, Refinement, Contradiction and Knowledge Merge** |
| `20260828-093925_step-025k` | Knowledge State Algebra and Closure |
| `20260828-100737_step-025s` | **Identity, Entity Resolution, Same-As, Distinct-From, Knowledge Atma** |
| `20260828-102917_step-038` | Identity, entity resolution, equivalence, reference integrity |

**25J is an earlier draft of the Step 288 programme itself** — equality, refinement, contradiction,
merge, a computability table, and the governance pathway for semantic equality. It was **performed**
(72 seconds after 25I) and verdicted **PASS**.

> ⚠️ **This is not a failure of the corpus. It is a failure of my search.** I searched for
> *behavioural equivalence* and found Step 260; I did not search for *refinement*, *merge*,
> *entity resolution*, or *same-as*, and so missed the seam where the equality algebra was first built.
> **Third instance of my recurring error: searching for a phrase instead of a concept.**

## 1. ⭐ REFUTED — *"the register originates at Step 246"*

I have written **"`CORPUS` — Step 246 … *These are not interchangeable*"** in
`REFINED-STEP-287.md` §2, `04`, `E1-E7`, `exec/e_equality.py`, and by inheritance `288`.

**`25I.11` (2026-08-28) states it first,** over **three** relations:

$$\equiv_{exact} \qquad \equiv_{struct} \qquad \equiv_{sem,C}$$

> *"These are not interchangeable."* — `25I.11`

**Step 246's four state-level relations are a SUCCESSOR to a three-relation register, not the origin.**
And the earlier register carries something 246's does not: **`≡_sem,C` is indexed by context `C`** —
and at `25J.6`, by context **and reference time**:
$$SemanticEquivalent(A_1, A_2, C, t)$$
with `25J.7` observing $\equiv_{sem}^{2025} \neq \equiv_{sem}^{2026}$.

> ### Consequence, and it is structural
> **`≡` is not one relation. It is a `(C,t)`-indexed FAMILY of relations.** Every artifact in this
> programme — 287, 288 included — has treated it as a single relation. **`D288-1` row B is therefore
> under-specified at Level 3 (typed), not merely at Level 4.**

## 2. ⭐ Equality may NOT be transitive — and the corpus says so twice

| Locus | Statement |
|---|---|
| **`25J.27`** | *"We would also like transitivity, but this **must be carefully controlled** because context-dependent semantic mappings can break naive transitivity"* |
| **`25S.6`** | *"evidence for Same-As is **not necessarily transitive**"* — distinguishes `SameAs` from `EvidenceForSameAs` |
| **`25S.29`** | *"If one identity link is wrong, **transitive closure can propagate the error across a huge graph**. Therefore **identity closure must be governed**"* |
| **`25S.30`** | names the failure: **`KnowledgeContamination`** |

> **`≡_sem,C` is not established to be an equivalence relation.** It may fail transitivity, which is
> the axiom that makes quotients exist. **Everything downstream that assumes a quotient — including
> Step 260's `ℋ/≡_𝒯` and my own `≈_X` framing — inherits this as a precondition.**
>
> ⚠️ **My `≈_X` proof is unaffected** (it is a pullback of equality along `π_X`, so transitivity is
> free) — **but that is exactly the point: `≈_X` is transitive because I constructed it that way, and
> the corpus's relation is not known to be.** A property of my construction was being read as a
> property of the corpus's object. **This is the same defect two reviewers caught in `=_semantic`.**

## 3. Identity — a THREE-level hierarchy I never registered (`D288-4` corrected)

`25S.36` / `25S.43`:

$$\boxed{EntityID \ \neq\ KAID\ (\text{KnowledgeMeaningIdentity}) \ \neq\ RecordID}$$

with `KAID ↔ EntityID` *"itself an explicit, versioned semantic relationship"*, and (`25I.19`) a
two-level precursor `KnowledgeMeaningIdentity` / `KnowledgeRecordIdentity`.

**My `D288-4` matrix had five notions on a different axis** (state · operation · authority-act · event
· provenance) and concluded *"`K_t` itself has NO identity rule."*

| | Corrected |
|---|---|
| *"`K_t` has no identity rule"* | ⚠️ **too strong.** `25I.37` gives `KAID = Identity(CanonicalAssertion, Context)`, and `25S.43` refines it. **A candidate rule exists, explicitly marked *provisional*, and was never ratified.** The accurate statement is **"a provisional candidate exists; no ratified rule"** |
| the five notions | **stand** — they are a different axis, and the two axes are **orthogonal**, not rival |

**And two separations my matrix lacked:**
- `25S.34` **`SameState ⇏ SameEntity`** — two servers at `Version=3.70` are not one server
- `25S.35` **`SemanticEquivalent ⇏ SameEntity`** — *"`EquivalentProperties ≠ SameIdentity`"*

## 4. ⭐ Equality decisions are NOT binary (`D288-8` corrected)

`25S.3` — *"Entity resolution should **not** be binary"*:

$$Resolution(r_1,r_2) \in \{\,Same,\ Different,\ PossibleSame,\ Unknown\,\}$$

> **My entire `D288-8` completeness matrix assumes a Boolean decision procedure.** A four-valued
> codomain changes what "Level 5 · Decidable" even *means*: a procedure returning `Unknown` is total
> and decidable **without deciding equality**. **`≡` may be decidable in this four-valued sense while
> remaining undecidable in the Boolean one — and those must never be reported as the same result.**
>
> ✅ **And this is the negative-knowledge discipline again** — `25S.4` *"why Unknown is essential"*
> agrees with Step 224.11's *"absence of evidence should remain Unknown."* **Two independent corpus
> loci, one rule.**

## 5. Level 6 and Level 7 — my "empty columns" need qualifying

I reported **columns 6 (implementable) and 7 (governance-authorized) EMPTY**. That stands as a
statement about *relations taken through them*. It is **too strong** as a statement about the corpus:

| Level | What the corpus already has |
|---|---|
| **6** | `25J.39–40` **blocking/partitioning** — `O(n²)` reduced by indexing on `Subject+Predicate+Context+TimeWindow`. ⚠️ **implementability of the SEARCH, not of the relation** — the distinction matters and I keep it |
| **6** | `25J.41` a 13-operation **Computable? / Deterministic?** table. It rates semantic equivalence **computable but NOT deterministic** — which is *stronger and worse* than my "Level 2, no procedure": **a procedure exists and is non-deterministic** |
| **7** | `25J.36` / `25J.42` **`SemanticCandidate → GovernedValidation → AuthoritativeRelation`** — the governance pathway is **specified** |
| **7** | `25J.49` **`Generation ≠ Validation ≠ Authority`** — *"one of the strongest principles in the entire architecture"* |

> **Corrected wording: the Level-7 PATHWAY is specified; NO relation has been taken through it.**
> **Not the same claim as "column 7 is empty", and the difference is exactly the kind of overstatement
> this programme exists to catch.**

## 6. An ordering candidate DOES exist (`D288-6` corrected)

`D288-6` said of `K`: **"no candidate structure at all."** For `K` that stands. **But an ordering
primitive exists one level down, and it is properly defined:**

`25J.10` — *"`A_1 ⪯ A_2` if every situation satisfying `A_2` also satisfies `A_1`"* — i.e. **entailment**,
with a satisfaction semantics, not a hand-waved "more detailed":
$$A_{specific} \Rightarrow A_{general}$$
and `25J.27` gives **reflexivity and transitivity**. **That is a preorder on assertions, with a
definition and two proven properties.** `25I.21`/`25J.8` call it *refinement* `⪰`.

> **Corrected: `K` has no candidate order; `𝒜` has a DEFINED candidate preorder (entailment-based).**
> Whether it lifts to `K` (Hoare/Egli-Milner style, or not at all) is **an open research question with
> a real starting point** — which is materially different from *"nothing at all."*
>
> ⚠️ **It is computable only relative to an ontology** — `25J.11`: *"computable **if the version
> ontology defines the relationship**."* So it is Level 4, parameterized by `Ω`.

## 7. Two operators absent from Step 288 entirely

### 7a. `Merge` — and its invariants are equality-relative

`25J.43`: $Merge_K(K_1,K_2,\Omega,C,T,M) \rightarrow K_3$

| Property | Corpus status |
|---|---|
| **non-destructive** | `25J.44` **invariant** — *"must not destroy epistemic history"*; `K_1,K_2` remain reconstructable |
| **idempotent** | `25J.45` `Merge(K,K)=K` *"**or at least semantically equivalent to `K`**"* |
| **commutative** | `25J.46` *"we should **not yet impose** universal commutativity"* |
| **associative** | `25J.47` *"**a design goal, not yet a proven invariant**"* |

> ⭐ **Idempotence is stated *modulo* `≡`.** So **`Merge` is a SECOND operator whose invariants depend
> on Decision 3**, alongside `δ`. My `D288-7` named only `δ`. **The equality choice has a wider blast
> radius than Step 288 reported.**

### 7b. The typed relation algebra

`25J.26`: $\mathcal R = \{Equal,\ Equivalent,\ Refines,\ RefinedBy,\ Contradicts,\ EvolvesTo,\ Independent\}$
— *"These relations should not be collapsed into one generic 'related' relation."*

⚠️ **Do not confuse levels.** These are **seven typed edges between assertions**; my five are
**equality relations on states/histories**. **Different carriers, different objects.** `25J.28`'s
framing — *"a graph of typed epistemic relations"*, the edge itself carrying semantics — is the
corpus's own, and my `ℛ ⊆ 𝒜 × Type_R × 𝒜` is its 3-field descendant.

**Also load-bearing:** `25J.16` **`TemporalEvolution ≠ Contradiction`**, and `25J.13–15`
**`Contradiction(A_1,A_2,Ω,C,T)`** requires a domain rule — *"`Functional(HasIPAddress) = False`"*, two
IPs are not a contradiction. **`25J.15`: LLM semantic difference ≠ domain contradiction.**

## 8. The `id` contradiction — now with corpus backing for the repair

My `§4` flagged `id = H(P,e,c,t,Π)` over a **mutable `e.state`** as a live contradiction, and offered
the repair as *"`VERIFIER RECOMMENDS`, not adopted."* **The corpus already ruled on the principle:**

| `25I.29` | *"the identity of the knowledge object **cannot simply be `Hash(currentRepresentation)`**"* |
|---|---|
| `25I.30` | *"We should **not mutate `K_1` into `K_2`**"* → `K_1 \xrightarrow{StateTransition} K_2` |
| `25I.30` | $\boxed{KnowledgeIdentity\ is\ persistent;\ KnowledgeState\ evolves}$ |

> **The repair is no longer only my recommendation — it is the corpus's own stated principle, violated
> by a later construction.** Status improves from `VERIFIER RECOMMENDS` to
> **`CORPUS`-PRINCIPLE vs LATER-CONSTRUCTION CONFLICT** — which is a governance matter, and a stronger
> finding than an engineering suggestion.

## 9. `≈` — a RIVAL parameterization, and mine may be the wrong one

`25I.35`: $K_1 \approx_C K_2$ — *"epistemically equivalent **for context `C`**"*, with
$\boxed{KnowledgeEquivalence\ is\ purpose\text{-}relative}$ and the example: equivalent for
`MigrationPolicy_X`, **not** for `PatchCompliancePolicy_Y`.

| | index | reading |
|---|---|---|
| **my `D288-3`** | `X ⊆ {A,S,R,V,C}` | which **Σ axes** must agree — **32 projections** |
| **corpus `25I.35`** | `C` = a **purpose / policy** | equivalent **for what decision** |

> ⚠️ **These are not the same relation, and the corpus's is indexed by POLICY, not by axis subset.**
> My 32-projection catalogue is a defensible construction; **it is not established to be the corpus's
> `≈`.** `D288-3`'s own caveat said as much — **but it named the wrong reason.** The right reason: a
> rival, corpus-native parameterization exists and I had not read it.
>
> **And `25I.36` gives the purpose:** *"Zero should not ask 'are these identical?' It should ask 'is
> the available knowledge sufficient?'"* → $Sufficient(K,r,C)$, with
> $\boxed{Identity \neq Equivalence \neq Sufficiency}$.

## 10. `K = Fold(history)` — the constructive bridge to Step 260

`25I.31`: $K_t = Fold(Observations_{0..t},\ Rules)$ · `25I.32`:
$Replay(Events_{0..t},\ ModelVersion) \rightarrow K_t$

> ## 🔴 RETRACTED — `258.11` refutes this section as first written
>
> This section originally claimed `Fold` supplies the quotient map Step 260 lacked, via
> `H_1 ≡_𝒯 H_2 ⟺ Fold(H_1) = Fold(H_2)`. **Step 258.11 had already refuted that, one step earlier:**
>
> > *"A **naive** history equivalence is `H_1 ∼_F H_2 ⟺ F(H_1)=F(H_2)`. **But this is insufficient.**
> > The stronger behavioral equivalence is `H_1 ∼_H H_2` iff **all permitted future operation
> > sequences** produce equivalent states"* — `∀σ: F(σ(H_1)) ≡_K F(σ(H_2))`.
>
> And `258.13`/`258.14` state the biconditional `F(H_1)=F(H_2) ⟺ H_1 ∼_H H_2` as *"a major theoretical
> result"* that **"we have not demonstrated yet."**
>
> **I conflated the naive relation with the strong one.** Fold-equality is decidable **and explicitly
> insufficient**; the quotient map remains undelivered, exactly as `260.11` says.
> **`Fold`/`Replay` remain worth recording as a construction — but as `~_F`, a relation the corpus
> names and rejects, not as a closure.** See `REFINED-STEP-288.md` §5.

## 11. Two layers missing from my model (`25I.53`)

$$Observation \rightarrow \textbf{Representation} \rightarrow Evidence \rightarrow Assertion \rightarrow \textbf{Assessment} \rightarrow Knowledge$$

My layer model is `W --Ω--> O` plus the Sañjaya register. **`Representation` (the artifact) and
`Assessment` (support evaluation) are absent from it** — and `25I.5–25I.7` prove they are load-bearing:

- `25I.6` **same representation, different observation** (`R_1=R_2`, `O_1≠O_2` by time) →
  $\boxed{RepresentationIdentity \neq ObservationIdentity}$
- `25I.7` **same observation, different representations** → `R_1≠R_2`, `SourceOf` both `= O_1`
- `25I.8` **same assertion, different evidence** → $\boxed{EvidenceIdentity \neq AssertionIdentity}$

> ✅ **And `25I.3` confirms my `Qualify` gap is old and unmoved:** `E = Transform(O)`,
> $Observation \rightarrow Evidence$ but $Observation \neq Evidence$ — **a named function with no
> body**, exactly the `G1` shape, three days before I named it.

## 12. Evidence discipline on this seam

⚠️ **All of 25I/25J/25S is verdicted `PASS` on ANALYTIC falsification tests** — 25I.44–49 (six),
25S.40 (seven). **None is executed.** `25J.41`'s computability table is a **claim about
computability, not a measurement of it.**

> **Classification: `CORPUS` · self-verdicted PASS · analytic · NOT executed · NOT ratified.**
> Every definition in this seam is marked by its own author **provisional** (`25I.37`, `25J.43`,
> `25S.43`). **It raises no status to `ESTABLISHED`. It does show that several things I reported as
> ABSENT are PRESENT-BUT-UNRATIFIED — a different verdict, and the honest one.**

## 13. Net effect on Step 288

| `288` claim | After this seam |
|---|---|
| register originates at Step 246 | 🔴 **REFUTED** — `25I.11`, three relations, two days earlier |
| `≡` is one relation | 🔴 **CORRECTED** — a `(C,t)`-indexed **family** |
| `≡` transitivity assumed | 🔴 **CORRECTED** — corpus flags it twice as unsafe; quotients at risk |
| `K_t` has **no** identity rule | 🟠 **SOFTENED** — a provisional `KAID` candidate exists, unratified |
| equality decisions Boolean | 🔴 **CORRECTED** — `{Same, Different, PossibleSame, Unknown}` |
| columns 6 & 7 **empty** | 🟠 **QUALIFIED** — the Level-7 **pathway** is specified; nothing taken through it |
| `K`: no candidate structure **at all** | 🟠 **CORRECTED** — `𝒜` has a defined entailment preorder |
| `δ` is the operator needing `≡` | 🟠 **WIDENED** — **`Merge` too**, idempotent only modulo `≡` |
| `≈_X` over Σ-axis subsets | 🟠 **RIVALLED** — corpus `≈_C` is indexed by **policy/purpose** |
| `id ≠ Hash(current)` repair is mine | 🟢 **STRENGTHENED** — it is `25I.29–30`, corpus principle |
| behavioural equivalence needs a quotient map | 🔴 **RETRACTED (§10)** — `Fold` gives `~_F`, which `258.11` names and **rejects as insufficient** |

$$\boxed{\textbf{Step 288's VERDICT stands: the equality problem is mapped, not solved.}}$$
$$\boxed{\textbf{Its EVIDENCE BASE does not: six claims corrected, one refuted.}}$$

**Nothing here closes anything.** The seam is provisional, unexecuted and unratified. **What it
changes is the honesty of the map:** several items I reported as *missing from the theory* are in fact
*present, unratified, and in two cases contradicted by later construction* — and that is a governance
finding, not an engineering one.

## 14. Added to the registers

**Normative:** is `≡_sem` required to be **transitive**? · is the equality codomain **Boolean or
four-valued**? · is `≈` indexed by **Σ-axes or by policy**? · is `Merge` required **commutative /
associative**? · **`EntityID`/`KAID`/`RecordID`** — ratify or reject.
**Technical:** the `(C,t)`-indexed family of `≡` · lifting `⪯` from `𝒜` to `K` · `Fold`'s `Rules` and
`ModelVersion` · governed transitive closure (`25S.29`) · `Ω` for refinement and contradiction.
**Counterexamples:** `25S.34` two servers one version · `25S.35` identical configs · `25J.13` two IPs ·
`25I.6` same bytes different observation · `25J.7` `≡_sem^2025 ≠ ≡_sem^2026`.

## 15. Not read

`025k` (state algebra/closure) · `025l` (distributed merge convergence) · `025m` (retraction/revision)
· `025o` (truth/validity/belief) · `025t` (inference/proof) · `025v` (semantics/ontology/meaning
alignment) · `025w` (bitemporal) · `038` (identity/reference integrity) · the `external_research/`
`20260831` re-issues of 25I/25J. **`025v` and `038` are directly on-topic and unread. Recorded so this
gap is not mistaken for coverage.**
