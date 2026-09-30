I performed this as a **completion audit of the historical F4 / Model-B semantic backbone**, not as another general search for semantic primitives. I excluded `three_model_convergence/` and did not use downstream convergence material as evidence.

One important result emerges immediately: the historical corpus is **stronger than “only fragments”**, but it is also internally explicit that several formal cores remain open. In particular, the historical semantic theory gives `K_t∈𝕂`, a representation carrier, `Obs`, `EC`, `Sat`, `Δ_t`, `T`, and `H`; however, it does not close their types and composition into an executable F4/Model-B semantics. 

---

# 1. `K_t` and `𝕂`

## Finding

### `K_t ∈ 𝕂`: **FOUND-UNTYPED**

The historical theory explicitly states:

$$
K_t\in\mathbb K
$$

and defines:

$$
\mathbb K=\text{space of admissible semantic epistemic states}.
$$

It also explicitly distinguishes `K_t` from `W_t` and from a mandatory implementation tuple. 

This is genuine pre-MD-058 evidence: the artifact is dated **2026-09-01**, and the chronology establishes that this semantic-state formulation predates MD-058/059. 

### What is *not* established

`𝕂` is **not mathematically typed beyond its descriptive semantic-space designation**.

The historical document explicitly leaves the mathematical structure of `K_t` open rather than selecting graph, typed relational structure, lattice, category, state space, etc. 

Moreover, the Model-B register still contains its separate 9+ competing tuple formulations. MD-059 found only one of eleven named components concretely typed within one variant. 

### Therefore

```text
K_t ∈ 𝕂                  FOUND
𝕂 as semantic space      FOUND
mathematical type of 𝕂   NOT ESTABLISHED
canonical Model-B K_t    NOT ESTABLISHED
relationship to 9+ B variants  NOT ESTABLISHED
```

So this is **FOUND-UNTYPED**, with a **CONFLICTING/UNRECONCILED variant family** underneath it.

---

# 2. Representation `r(K_t)=x_t`

## `r`: **FOUND-UNTYPED**

The historical theory explicitly separates semantic knowledge space from representation space:

$$
(\mathbb K,\mathrm{Sem})
$$

versus

$$
(\mathcal X_r,\mathscr A_r).
$$

It defines a representation family

$$
R_r:\mathbb K\leftrightarrow\mathcal X_r
$$

and separately writes:

$$
r(K_t)=x_t.
$$



This establishes that `r` is intended as a **representation**, not automatically an observation or semantic state.

But the corpus does **not** provide a clean function signature such as

$$
r:\mathbb K\rightarrow\mathcal X_r.
$$

Instead, the representation family is expressed with a bidirectional relation-like notation `R_r : 𝕂 ↔ X_r`, while `r(K_t)=x_t` is separately used.

Therefore I will **not infer the function type**.

### `x_t`: **FOUND-UNTYPED**

`x_t` occurs as the represented configuration produced from `K_t`, with its representation carrier identified as `(X_r,A_r)`. 

But the corpus does not explicitly close:

$$
x_t\in X_r
$$

as a formal typed judgement accompanying `r(K_t)=x_t`.

### Representation vs semantic equivalence

This distinction **is established**.

The source explicitly separates semantic knowledge space from representation space and defines semantic equivalence of representations separately. 

However, that semantic-equivalence definition itself remains only a historical formulation; it does not yet provide the closed F4 equivalence machinery required later.

---

# 3. `Obs`

## F4-specific `Obs_F4`: **NOT FOUND**

There is a genuine historical observation definition:

$$
O_t^s=Obs(s,W_t,m_t,c_t).
$$

It defines observation as an epistemically accessible result obtained from world state through an observation mechanism. 

But its signature is fundamentally:

```text
participant + world state + method + context
              ↓
          observation
```

It is **not**:

```text
K_t → F4 observation
```

and the historical audit explicitly confirms:

> no demonstrated F4-specific `Obs_F4(K)`.



### Operational status

Not operationally defined for F4.

The current F4 construction can only reach an observation **in form** through satisfaction/requirements, and that itself depends on unresolved `Sat`.

**Verdict: NOT FOUND.**

---

# 4. `EC`

## Historical structure: **FOUND-UNTYPED**

The historical theory explicitly gives:

$$
EC_t=EC(S_t,G_t,Q_t,C_t)
$$

and describes `EC` as the epistemic contract. 

Another historical contract lineage gives a richer structure:

$$
EC=(Purpose,Requirements,EvidenceRules,UncertaintyLimits,
ConflictRules,TemporalRules,AuthorityRules)
$$

and explicitly says that EC is a first-class governance object. 

### But construction is not closed

The historical audit states:

* contract structure: clear;
* contract construction algorithm: not found;
* `EC` must be authored;
* the corpus does not establish by whom/how it is authored.



The later `DeriveContract(G,S)` / η line also does not solve this: the historical record specifically identifies the total-function interpretation as invalid because the available inputs do not determine the contract.

### F4-specific?

**No demonstrated F4-specific construction.**

So:

```text
EC object/structure       FOUND
EC mathematical type      PARTIAL / UNTYPED
EC construction           NOT CLOSED
F4-specific construction NOT FOUND
```

---

# 5. CRITICAL GATE — `Sat(K_t,r)`

This is the decisive blocker.

## Status: **CONFLICTING + FORMALLY INCOMPLETE**

There are three separate layers that must not be conflated.

### 5.1 Type/role is established

The historical requirement formulation gives:

$$
r=(id,type,scope,content,standard,priority,validity)
$$

and:

$$
Sat(K_t,r)\in\{0,1\}.
$$



The frozen F4 definition consequently uses:

$$
\Delta_t=
\{r\in Req(EC_t):\neg Sat(K_t,r)\}.
$$

That formula is frozen and is not being reopened.

### 5.2 But another historical formulation uses three values

A separate 2026-09-02 formalization proposes:

$$
Sat(K_t,r)\in\{\top,\bot,\mathsf U\}
$$

where `U` means that satisfaction cannot currently be determined. It explicitly warns against silently treating unknown as false. 

This is **not a reason to change the frozen MD-059 Boolean definition**.

It is evidence of an unresolved historical semantic issue.

Therefore the corpus contains a genuine **codomain conflict**:

```text
frozen F4:
Sat : ... → {0,1}

historical alternative:
Sat : ... → {⊤,⊥,U}
```

No adjudication should be made here.

### 5.3 More importantly: no computational body

The historical theory itself says that `Sat` remains part of the unresolved formal core. 

MD-059's primary-source check sharpens this:

> computing `Sat(K_t,r)` requires the semantics of the components of `K_t`.

Only one of eleven named components has a concrete typed definition, inside only one of the competing Model-B variants. 

### Ten requested tests

| Test                           | Result                                                                            |
| ------------------------------ | --------------------------------------------------------------------------------- |
| 1. Domain of `K_t`             | **PARTIALLY FOUND** — `K_t∈𝕂`, but `𝕂` not mathematically closed                |
| 2. Type of `r`                 | **PARTIALLY FOUND** — requirement tuple proposed, but not fully closed as F4 type |
| 3. Codomain                    | **CONFLICTING** — frozen `{0,1}` vs historical `{⊤,⊥,U}`                          |
| 4. Total/partial               | **NOT FOUND**                                                                     |
| 5. Evidence required           | **PARTIALLY FOUND**, but no complete evaluation rule                              |
| 6. Meaning `Sat=1`             | **FOUND conceptually** — satisfied requirement                                    |
| 7. Meaning `Sat=0`             | **FOUND conceptually** — unsatisfied requirement                                  |
| 8. Insufficient evidence       | **NOT CLOSED**                                                                    |
| 9. `UNKNOWN` distinct          | **CONFLICTING / OPEN**                                                            |
| 10. deterministic/reproducible | **NOT FOUND**                                                                     |

### Two engineers test

> Can two engineers, given the same `K_t` and `r`, independently compute the same `Sat(K_t,r)`?

**NO — not from the currently corpus-defined rules.**

The exact missing rule is:

> **a complete, decomposition-independent evaluation semantics connecting the admissible `K_t` state semantics to the satisfaction conditions of `r`.**

I am deliberately **not supplying that rule**.

---

# 6. `Δ_t`

## Status: **FOUND as definition; NOT COMPUTABLE**

The formula is genuinely established:

$$
\boxed{
\Delta_t=\{r\in Req(EC_t):\neg Sat(K_t,r)\}
}
$$

and the historical theory calls this the canonical v1.0 definition. 

But computability has two prerequisites.

### `R_t`

**PARTIALLY GROUNDED.**

The historical corpus has a requirement structure and requirement set, but the exact F4 `R_t=Req(EC_t)` construction is not fully operationally specified.

### `Sat`

**NOT EXECUTABLE.**

Therefore:

```text
R_t typed?       PARTIAL
Sat typed?       CONFLICTING / PARTIAL
Sat executable?  NO
Δ_t executable?  NO
```

### `UNKNOWN` issue

This is deliberately **unresolved**, because the corpus contains both:

* frozen Boolean satisfaction for Δ;
* historical three-valued satisfaction with `U`.

We must not silently decide whether an indeterminate requirement belongs in Δ.

So the correct statement is:

> **Δ is formally defined but not computable from the present corpus-defined semantics.**

---

# 7. Transition semantics

## Status: **CONFLICTING**

The historical theory contains an explicit transition formulation:

$$
K_{t+1}=T(K_t,E_{t+1},Q_t,C_t,EC_t,A_t)
$$

and historical material also gives:

$$
H_{t+1}=H_t\cup\{transition_t\}.
$$



There is also a transition universe `τ1…τ10` historically, with later `τ11` appearing as an open/conditional retirement/merge case.

Separately, the C1 lane has:

$$
K_{t+1}=\delta(K_t,e_t).
$$

But that belongs to another lineage and cannot be silently imported into Model B.

The historical audit explicitly says:

> multiple transition formulations exist and the transformer semantics are not closed.



Therefore:

```text
T exists                  FOUND
δ exists somewhere        FOUND — OTHER MODEL/LANE
τ universe exists         FOUND
one unified F4 transition CONFLICTING
transition type           NOT CLOSED
replay semantics          NOT ESTABLISHED
```

And importantly:

> **τ classes must not be equated with `δ` merely because both describe transitions.**

---

# 8. Semantic equivalence

## Status: **FOUND-UNTYPED / INCOMPLETE**

There is a historical explicit definition:

$$
r_1\equiv_{\mathrm{sem}}r_2
$$

iff they induce the same epistemic meaning, with the more general formulation:

$$
r_1\equiv_{\mathrm{sem}}r_2
\iff B_{r_1}=B_{r_2}.
$$



This is a real historical definition, and MD-059 additionally found `DEF-15` in M0043 as a primary dated corpus precedent for the same shape. 

But the following are **not closed**:

* precise operand domain;
* precise codomain;
* what `r` denotes in every occurrence;
* what exactly `B_r` is;
* admissible test domain;
* relation to F4 `K_t`;
* relation to `EC`;
* relation to F4 observations;
* complete equivalence-class construction.

So the important distinction is:

> **The corpus contains a semantic-equivalence formulation, but not yet a closed F4 semantic-equivalence operator.**

### Requested properties

| Property                   | Status          |
| -------------------------- | --------------- |
| operands                   | PARTIAL         |
| domain                     | NOT CLOSED      |
| codomain                   | NOT CLOSED      |
| criterion                  | PARTIALLY FOUND |
| reflexivity                | NOT ESTABLISHED |
| symmetry                   | NOT ESTABLISHED |
| transitivity               | NOT ESTABLISHED |
| equivalence classes        | NOT ESTABLISHED |
| dependence on `K_t`        | NOT CLOSED      |
| dependence on `EC`         | NOT CLOSED      |
| dependence on observations | NOT CLOSED      |

Therefore this is **not yet ready for actual F4 equivalence evaluation**.

---

# 9. F4 specificity matrix

| Object                 | Classification                                                             |
| ---------------------- | -------------------------------------------------------------------------- |
| `K_t∈𝕂`               | **GENERAL / HISTORICAL SEMANTIC THEORY**                                   |
| Model-B tuple variants | **F4-SPECIFIC**                                                            |
| `𝕂`                   | **GENERAL**                                                                |
| `r(K_t)=x_t`           | **GENERAL**                                                                |
| `(X_r,A_r)`            | **GENERAL**                                                                |
| `Obs(s,W,m,c)`         | **GENERAL**                                                                |
| `EC(S,G,Q,C)`          | **GENERAL**                                                                |
| `Sat(K_t,r)`           | **F4-SPECIFIC in current use; historical general formulation also exists** |
| `R_t=Req(EC_t)`        | **F4-SPECIFIC in frozen Δ formulation**                                    |
| `Δ_t`                  | **F4-SPECIFIC / Model-B frozen**                                           |
| `T`                    | **GENERAL / historical theory**                                            |
| `δ`                    | **OTHER MODEL — C1**                                                       |
| `H_t`                  | **GENERAL / persistence/history substrate**                                |
| `≡sem`                 | **GENERAL semantic-equivalence formulation; not yet closed as F4**         |

This is why the historical backbone cannot simply be pasted onto Model B: **same symbol does not establish same bounded-context meaning**.

---

# 10. Composition test

Here is the decisive system-level test.

| Arrow               | Status                                     | Reason                                                                             |
| ------------------- | ------------------------------------------ | ---------------------------------------------------------------------------------- |
| `K_t → r(K_t)=x_t`  | **DIRECT**                                 | Explicitly stated historically.                                                    |
| `r(K_t) → Obs`      | **NO EVIDENCED LINK**                      | Representation and observation are explicitly distinct concepts.                   |
| `K_t → Obs`         | **NO EVIDENCED LINK for F4**               | General `Obs(s,W,m,c)` is not `Obs_F4(K)`.                                         |
| `Obs → EC`          | **NO EVIDENCED LINK**                      | EC is a knower/goal/inquiry/context construct, not shown as an observation output. |
| `EC → R_t`          | **DIRECT / PARTIAL**                       | `Req(EC_t)` is explicit, but complete construction is not operationally closed.    |
| `K_t,r → Sat`       | **DIRECT but incomplete**                  | The relation is explicit; its computational body is not.                           |
| `Sat → Δ_t`         | **DIRECT**                                 | Frozen Δ definition.                                                               |
| `K_t → T → K_{t+1}` | **CONFLICTING / PARTIAL**                  | Historical `T` exists, but competing transition formulations remain.               |
| `T → H_t`           | **DIRECT at historical formulation level** | Historical history-update formulation exists.                                      |
| `Obs/Beh → ≡sem`    | **NO EVIDENCED LINK for complete F4**      | `B_r` and F4 behaviour are not closed.                                             |

### Result

The corpus therefore contains **a chain of related constructs, but not a closed executable semantic composition**.

That is the key finding of this completion audit.

---

# 11. Final missing-parts matrix

| Component | Exists in corpus? | Typed?                     | F4-specific?                           | Operationally defined? | Current blocker                                      |
| --------- | ----------------- | -------------------------- | -------------------------------------- | ---------------------- | ---------------------------------------------------- |
| `K_t`     | **YES**           | **NO / PARTIAL**           | **YES, but 9+ variants**               | **NO**                 | unresolved mathematical type + variant family        |
| `𝕂`      | **YES**           | **NO**                     | **GENERAL**                            | **NO**                 | semantic-space description not closed mathematically |
| `r`       | **YES**           | **NO**                     | **GENERAL**                            | **PARTIAL**            | exact function/relation type not closed              |
| `x_t`     | **YES**           | **NO**                     | **GENERAL**                            | **PARTIAL**            | carrier membership/type not formally closed          |
| `Obs_F4`  | **NO**            | —                          | **F4**                                 | **NO**                 | no F4-specific observation operator                  |
| `EC`      | **YES**           | **PARTIAL**                | **GENERAL**                            | **NO**                 | construction/authority/input semantics incomplete    |
| `Sat`     | **YES**           | **CONFLICTING**            | **F4 in current use**                  | **NO**                 | missing decomposition-independent evaluation body    |
| `R_t`     | **YES**           | **PARTIAL**                | **F4**                                 | **NO**                 | requirement-set construction not fully closed        |
| `Δ_t`     | **YES**           | **YES for frozen formula** | **F4**                                 | **NO**                 | depends on non-computable `Sat`                      |
| `T/δ`     | **YES**           | **CONFLICTING**            | `T`: general; `δ`: other lane          | **NO**                 | competing transition semantics                       |
| `H_t`     | **YES**           | **PARTIAL**                | **GENERAL/persistence**                | **PARTIAL**            | history/state relation not closed as F4 semantics    |
| `≡sem`    | **YES**           | **PARTIAL**                | **GENERAL; F4 application incomplete** | **NO**                 | operand/behaviour/test-domain semantics incomplete   |

---

# 12. Decisive conclusion

## **C — Backbone is only partially composed**

Not A, because `Sat` and semantic equivalence are not executable.

Not merely B, because the principal issue is not one isolated missing type: the **composition between historically grounded constructs is not established as an F4/Model-B semantic system**.

Not D as the primary conclusion, because although there are genuine competing formulations, the deeper finding is that the corpus has not established the relationships necessary to determine which alternatives constitute the same semantic object.

### The three maximum blocking items are:

**1. `K_t / 𝕂` semantic closure**

The corpus establishes `K_t∈𝕂` but does not provide a mathematically closed type for `𝕂` or reconcile the 9+ Model-B `K_t` variants. 

**2. A computable, decomposition-independent `Sat(K_t,r)`**

This is the decisive F4-internal blocker. The relation is present and frozen, but its evaluation depends on unresolved `K_t` component semantics; the corpus does not supply the missing computational rule. 

**3. F4-specific observation/behaviour composition**

There is no demonstrated `Obs_F4`, `Beh_F4`, or `Tr_F4`; consequently the historical `≡sem` formulation cannot yet be instantiated as a complete F4 equivalence test. 

---

## Final gate state

```text
Sat computable?                 NO
Δ computable?                   NO
F4 semantic equivalence?        NO
F3↔F4 well-posed?               NO
GA-001 ready?                   NO
GA-038 ready?                   NO
```

And importantly, **nothing in this audit authorizes a choice among the competing formulations**.

The historical evidence confirms that the semantic backbone predates MD-058, but it does **not** establish that backbone as a closed Model-B/F4 executable semantics. 

`|K| = 11 · [REC] · UNFROZEN · firewall intact`

**NOT READY — REMAINING EVIDENCE/GOVERNANCE GAP**
