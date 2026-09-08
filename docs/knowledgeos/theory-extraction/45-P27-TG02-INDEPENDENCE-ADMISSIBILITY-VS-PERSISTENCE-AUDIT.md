# `P-27` — `TG-02` Independence: Admissibility vs Persistence Audit

**2026-09-08 · Lane T.** After [`44` `P-26.1`](./44-P26.1-PROVENANCE-CHAIN-STRUCTURE-AND-UNBROKENNESS-SEMANTICS-AUDIT.md).

> **Single question:** *is `⇝` / `CommonAncestor` (`TG-02`) an admissibility predicate over information
> already required by `K1`–`K11`, or does preserving it impose an independent persistence obligation?*
>
> ⛔ **`P-08`, `P-18`–`P-26`, `P-26.1`, the 11-cell kernel, Schema v2 and 3MC all FROZEN · no Schema v3
> · no architecture · no implementation · no canonical theory · no cell changed without an independence
> proof · Lane M excluded, counts admissibility-filtered · well-foundedness / terminality / acyclicity
> **DEFERRED, not adjudicated** · no silent repair.**
>
> ⭐ **And the commissioned methodological correction is honoured: *"computed, therefore not a
> persistence obligation"* is NOT used as an argument anywhere. §3 tests it and rejects it as a proof
> form.**

# 1. Corpus witnesses — `[EMP]`, `verification/spec/STEP-VERIFY-001-010.md:12-26`

| field | content |
|---|---|
| **Problem** | *"Can independence of two evidence items be determined from **computable information**?"* |
| **Idea** | *"Split True Independence from **Operationally Established Independence**, relative to `(G, C, ρ)`"* |
| **Definitions** | ⭐ **`Ind_ρ(e_i,e_j,G,C) → {Independent, Dependent, Unknown}`** · metadata tuple `M(e)` (10 slots) · **`I(e_i,e_j) = (i_S,i_A,i_P,i_M,i_T,i_C)`**, each ∈ {Confirmed, Rejected, Unknown} · `𝕀 = {I,D,U}` |
| ⭐ **Formalization** | *"Deterministic rule: **provenance path `e_i ⇝ e_j` ⇒ Dependent. CommonAncestor ⇒ Independent ≠ Confirmed.** DifferentSource ⇏ Independent. Piecewise decision rule (§9), cases evaluated in order."* |
| **Derivation** | `OperationallyIndependent ⇏ StatisticallyIndependent` — **DERIVED**; invariants `Dependent ⇒ ¬Independent`, **`Unknown ⇒ ¬ConfirmedIndependent`** — **DEFINITIONALLY_VALID** |
| ⭐⭐ **Definition verification §6** | *"well-typed ✓ · exists for finite `G` ✓ · **well-defined relative to `ρ` (the "required conditions" are a POLICY ORACLE — deliberate)** · **computable: graph reachability, decidable ✓** · observable inputs"* |
| ⭐⭐⭐ **Result** | *"Independence computable in a qualified sense: **DETERMINISTIC OVER EXPLICIT PROVENANCE**; Unknown elsewhere."* |
| **Statistical verdict** | *"Conditional independence `P(E₁,E₂\|H)=P(E₁\|H)P(E₂\|H)` stated as an **assumption to be established, never auto-applied**. No estimator-without-estimand violation."* |
| ⭐⭐⭐ **DDD** | *"**`IndependenceAssessment` as domain object with Basis — sound VALUE-OBJECT design**"* |
| **Test** | *"Cases A–F — **CONCEPTUAL_CHECK (0 executed)**"* · **Superseded? No supersession act anywhere** |

⭐ **Corroborating attestation:** `policy oracle` **3 files** · `policy-parametric` **2** ·
`Unknown ⇒ ¬ConfirmedIndependent` re-stated in `STEP-TRACE-B4-20260827.md:124` · `CommonAncestor` **5
admissible files**.

# 2. `TG-02` reconstructed

$$\boxed{\begin{array}{l}R \subseteq E \times E \quad \textbf{— the provenance edge, } \mathbf{[EMP]} \textbf{ (}\textit{"explicit provenance"}\textbf{)}\\[3pt] R^{+} \quad \textbf{— reachability } \mathbf{[EMP]}\textbf{, the corpus itself says } \textit{"graph reachability, decidable"}\\[3pt] \mathit{CommonAncestor}(e_i,e_j) \iff \exists x:\ x\,R^{+}e_i \wedge x\,R^{+}e_j \quad \textbf{— requires depth} \ge 2\\[3pt] \boxed{\mathbf{TG\text{-}02} = \mathit{Ind}_\rho(e_i,e_j,G,C) = \mathit{Assessment}\big(R^{+},\ C,\ \boldsymbol{\rho}\big)}\end{array}}$$

⛔ **No structure introduced beyond the witness.** ⭐ **And `ρ` is carried explicitly, because §7 shows it
is load-bearing.**

# 3. ⭐ *"Computed ⇒ not persistent"* — tested and REJECTED as a proof form

$$\boxed{\begin{array}{c}\textbf{A computed quantity CAN impose a persistence obligation, if the information needed to recompute it}\\ \textbf{must be preserved in a way } K1\text{–}K11 \textbf{ does not cover. ⛔ So the inference is INVALID and is not used.}\end{array}}$$

| the three questions, kept apart | answer |
|---|---|
| **`A`** is `TG-02` itself **stored**? | ⭐ **no witness — §11** |
| ⭐⭐ **`B`** is the information needed to **recompute** it already required to persist? | ⭐⭐⭐ **YES — §4, §5** |
| **`C`** does **recomputability itself** create an additional requirement? | ⭐ **no — §7's `F1`/`F2` show absence degrades to `Unknown`, not to failure** |

# 4. ⭐⭐⭐ The primary necessity test — and it SUCCEEDS, in a way that confirms verdict `A`

**Goal:** `S_K(H₁) = S_K(H₂)` with `TG-02(H₁) ≠ TG-02(H₂)`.

| attempt | result |
|---|---|
| same items, same source values | ⭐ 🔴 **fails** — identical `R` ⇒ identical `R⁺` ⇒ identical assessment. `[EMP]`: *"deterministic over explicit provenance"* |
| ⭐ **vary the policy `ρ`** | ⭐⭐⭐ **SUCCEEDS — `S_K` identical, `TG-02` differs** |

$$\boxed{\begin{array}{c}\textbf{The construction SUCCEEDS. But the differing variable is } \boldsymbol{\rho}\textbf{ — a POLICY ORACLE, which is NOT kernel state.}\\[6pt] \boxed{\begin{array}{l}\textbf{⭐ So it does NOT show an information deficit in } K1\text{–}K11.\\ \textbf{It shows } TG\text{-}02 \textbf{ is POLICY-RELATIVE — i.e. an ASSESSMENT. The test's success CONFIRMS verdict } \mathbf{A}.\end{array}}\end{array}}$$

⚠️ **And the corpus flags this deliberately:** *"well-defined **relative to `ρ`** (the "required
conditions" are a policy oracle — **deliberate**)"*. ⭐ **Same shape as `Qualify`, which the corpus marks
`policy-parametric`.**

⭐⭐ **Consequence for persisting the RESULT:** a policy-relative judgement **cannot** be safely
persisted as fact, because the policy may change — so the result **must** be recomputable. ⛔ **That is
an argument against a result-persistence obligation, derived from the witness's own words rather than
from "it is computed".**

# 5. The primitive relation — retained-information audit

| required datum | covered? |
|---|---|
| **evidence item retained** | ⭐ **`K4a-i`** *(retained assertion/evidence)* |
| **source / provenance assertion retained** | ⭐ **`K2`** *(current source value)* — `P-09.2` §4.9: **source is a VALUE ON the item** |
| **directed edge retained** | ⭐ **`K2`** — the value lives **on** the target, so the edge is retained with it |
| ⭐⭐ **direction retained** | ⭐⭐⭐ **`K2`, STRUCTURALLY — direction is INTRINSIC.** A source field belongs to its bearer, so direction cannot be lost while the field is retained |
| **edge identity** | ⚠️ **not required** — `R` is a relation over item identities, and identities are `K1` |
| ⭐ **historical edge changes** | ⭐⭐ **`K3a`** — an edge change is **`τ6` source re-marking**, non-monotone, prior value retained |
| **warrant for the edge** | ⭐ **`K3b`** |
| **endpoint identities** | ⭐⭐ **`K1`** |

$$\boxed{\begin{array}{c}\textbf{Every datum } TG\text{-}02 \textbf{ needs maps to } K1,\ K2,\ K3a,\ K3b \textbf{ or } K4a\text{-i.}\\ \boxed{\textbf{⛔ And } \textit{"the system would need it to compute } TG\text{-}02\textit{"} \textbf{ was NOT used as evidence anywhere — each mapping cites its own prior derivation.}}\end{array}}$$

⭐⭐ **The ancestry-reading dilemma, and why it does not matter:** the rule says `CommonAncestor` without
specifying **current** or **historical** ancestry — `[OPEN]`. ⭐⭐⭐ **But either reading is covered:
current ancestry by `K2`, historical ancestry by `K3a`.** So the unspecified semantics **cannot** create
a deficit.

# 6. Minimal graph pairs

| | pair | does `K1`–`K11` distinguish the states? |
|---|---|---|
| **`G1`** `a→b` | — | ✅ **`K2`** holds `b`'s source |
| **`G2`** `a→b→c` | vs `a→c` | ✅ **`K2`** — different source values on `b`/`c` |
| **`G3`** `a←x→b` *(common ancestor)* | vs no `x` | ✅ **`K2`** — `a` and `b` both cite `x` |
| **`G4`** `a→x→b` vs `a→b` | — | ✅ **`K2`** — `b`'s source differs |
| **`G5`** edge deleted | — | ⭐ **`K3a`** if it was re-marked; ⚠️ **if simply LOST, that is `K2`/`K3a` failing — not a `TG-02` deficit** |
| **`G6`** `a→b` vs `a→c` | — | ✅ **`K2`** |
| ⭐⭐ **`G7`** two provenance nodes **merged** | `R-INV-04` | ⭐⭐⭐ **`K1`.** ⭐ **And this is `W1`'s shape:** `P-19` showed retained CONTENT is identical under identity collapse while DENOTATION differs — so `TG-02` flips **Independent → Dependent** exactly where `K1` fails. ⚠️ **`R-INV-04`'s interpretation is not assumed: the failure is read as identity, because its own words are *"Independent provenance **nodes** merged or overwritten"*** |

⭐⭐ **`G7` is the one case where `TG-02`'s correctness depends on a cell rather than merely consuming
it — and that cell is `K1`, already in the kernel.** ⇒ **a DEPENDENCY, not a deficit.**

# 7. Temporal / transition audit

| transition | what must survive for `TG-02` to remain determinable | cell |
|---|---|---|
| **edge addition** | the new source value | `K2` |
| ⭐ **edge loss** | ⭐⭐ **nothing extra — `Ind_ρ` returns `Unknown`**, and `Unknown ⇒ ¬ConfirmedIndependent` is **definitionally valid** | ⭐ **no cell needed** |
| **edge correction / replacement** | prior value + warrant | ⭐ **`K3a` + `K3b`** *(`τ6`)* |
| **withdrawal · supersession** | the retained assertion + relation | `K4a-i` / `K4a-ii` |
| **node retirement** | retired identity + successor | `K4c-i` / `K4c-ii` |
| **node identity preservation** | identity | ⭐ **`K1`** |

$$\boxed{\begin{array}{c}\textbf{⭐⭐ The graph is NOT temporally immutable — } \tau6 \textbf{ changes edges — and every change maps to an existing cell.}\\ \boxed{\textbf{And edge LOSS degrades the assessment to } \mathbf{Unknown} \textbf{ rather than breaking anything: the SAME epistemic-downgrade pattern } P\text{-}25 \textbf{ found for provenance.}}\end{array}}$$

# 8. Information-theoretic sufficiency

$$\boxed{I_{TG} = \{R,\ \text{identities},\ \text{prior } R,\ \text{warrants}\} \;\subseteq\; I_K \quad\textbf{— with the sole exception of } \boldsymbol{\rho}, \textbf{ which is a POLICY, not state.}}$$

⭐ **Every apparent "missing variable" was tested against §5's question — *is it genuinely absent, or an
instance of an existing cell?*** — and each is an instance. ⭐⭐ **`ρ` is the only genuine outsider, and
it is outside because it is a policy, not because the kernel is deficient.**

# 9. DDD classification

| term | corpus status |
|---|---|
| **Evidence** · **Assertion** | ⭐ **`[EMP]` — entities/records** |
| **provenance link** | ⭐ **`[EMP]` — a RELATION**, realized as a **value on an item** |
| **`⇝`** | ⭐⭐ **`[EMP]` — a DERIVED relation** *(reachability, "decidable")* |
| **`CommonAncestor`** | ⭐ **`[EMP]` — a PREDICATE** |
| **Independent / Dependent / Unknown** | ⭐ **`[EMP]` — ASSESSMENT VALUES** of `𝕀 = {I,D,U}` |
| ⭐⭐⭐ **`IndependenceAssessment`** | ⭐⭐⭐ **`[EMP]` — and the corpus's OWN DDD reading calls it a VALUE OBJECT with a Basis** |
| **Admissibility** · **Weight** | `[EMP]` — `P-26`/`P-26.1` |
| ⛔ `ProvenanceGraph` · `ProvenanceChain` · `IndependenceGraph` · `TG02Entity` | ⭐ **`[REFUTED]` as required domain concepts — none is manufactured** |

$$\boxed{\begin{array}{c}\textbf{The corpus itself types the result as a VALUE OBJECT / ASSESSMENT — not as a persisted entity.}\\ \boxed{\textbf{relation EXISTS in the domain} \;\ne\; \textbf{relation is a PERSISTED object} \;\ne\; \textbf{predicate COMPUTED over persisted information.}}\end{array}}$$

# 10. Statistical / epistemic classification

| candidate | verdict |
|---|---|
| ⭐ **a deterministic logical rule** | ⭐⭐ **`[EMP]` — the witness's own word: *"Deterministic rule"*, and *"decidable"*** |
| an evidential heuristic | 🔴 no |
| ⭐ a **probabilistic inference** | ⭐⭐ **`[REFUTED]` — *"conditional independence stated as an assumption to be established, never auto-applied"*** |
| a weighting rule | 🔴 no — weighting is `P-26.1`'s `history.length` |
| ⭐ **an admissibility condition** | ⭐⭐ **`[DERIVED]` — it feeds `Confirmed`/`Rejected` on `I(e_i,e_j)`** |
| an epistemic qualification | ⭐ **`[EMP]`** — *"computable in a **qualified sense**"* |

⭐⭐ **And *Independent* ≠ *Confirmed* is explicit in the rule itself** — `CommonAncestor ⇒ Independent ≠
Confirmed`, with the invariant `Unknown ⇒ ¬ConfirmedIndependent`. ⇒ **independence is ONE COMPONENT of a
six-slot vector `I(e_i,e_j)`, not the assessment.** ⭐ **The provenance structure is an INPUT to an
evidence assessment, not evidence itself.**

# 11. Failure-mode matrix

| | failure | persistence failure? | which cell | actually admissibility? |
|---|---|---|---|---|
| **`F1`** links retained, result uncheckable | 🔴 **no** | — | ⭐ **yes — degrades to `Unknown`** |
| **`F2`** one required link absent | ⚠️ **yes IF the link was owed** | **`K2`/`K3a`** | ⭐ **otherwise `Unknown`** |
| ⭐ **`F3`** edge **direction** lost | ⭐ 🔴 **cannot occur while the field is retained** — direction is intrinsic | **`K2`** | — |
| ⭐ **`F4`** historical link replacement lost | ⭐⭐ **yes** | ⭐⭐ **`K3a`** *(`τ6` prior value)* | 🔴 |
| ⭐⭐ **`F5`** nodes merged / overwritten | ⭐⭐⭐ **yes — `W1`** | ⭐⭐ **`K1`** *(+`K4c` if a retirement)* | 🔴 |
| **`F6`** relation valid, warrant lost | **yes** | **`K3b`** | 🔴 |
| ⭐⭐⭐ **`F7`** recomputable but a **different result** | ⭐⭐⭐ 🔴 **NOT a persistence failure at all — the POLICY `ρ` changed** | ⛔ **none** | ⭐ **yes — a policy/assessment change** |

⭐ **Seven modes; five map to existing cells, two are assessment degradations, and `F7` is the decisive
one: a changed result with unchanged state is a POLICY event.**

# 12. ⭐⭐ Attack on *"persistence supplies / admissibility consumes"*

**Falsification search** — is preservation of the *independence RESULT* ever required?

| pattern | admissible files |
|---|:--:|
| `retain independence` · `independence must survive` · `independence history` · `independent status must` · `loss of independence` · `preserve CommonAncestor` · `retain TG-02` · `TG-02 result` | ⭐⭐⭐ **0 · 0 · 0 · 0 · 0 · 0 · 0 · 0** |
| ⚠️ `preserve independence` | **2 — and NEITHER is this sense** |

⭐ **The two hits, checked rather than counted:** *"Therefore we must preserve independence"* — about
**state-space decomposition dimensions**; and *"# 10. Preserve independence from the feedback loop"* —
about a **reviewer's methodological independence**. ⭐⭐ **Two different senses of the word, neither a
persistence requirement.**

$$\boxed{\textbf{⭐ } \mathbf{[UNWITNESSED]} \textbf{. The distinction SURVIVES the attack — and "the system uses the result" was NOT accepted as "the result must persist".}}$$

# 13. Minimal new-cell test — **NOT TRIGGERED**
⭐ §4 found no state-level information deficit; §5 mapped every datum; §8 established `I_TG ⊆ I_K`; §12
found the result-persistence requirement `[UNWITNESSED]`. ⇒ ⛔ **no candidate cell is defined, and the
`P-18`–`P-20` irreducibility standard is not invoked.**

---

# 14. Final verdict

$$\boxed{\mathbf{A} \textbf{ — ADMISSIBILITY / ASSESSMENT ONLY. } TG\text{-}02 \textbf{ creates NO independent persistence obligation.}}$$

⭐ **`TG-02` is a deterministic, policy-relative ASSESSMENT computed from `R`, item identities, prior
`R` and warrants — every one of which `K1`, `K2`, `K3a`, `K3b` and `K4a-i` already retain.** ⛔ **Not
`B`** *(no corpus requirement to preserve the result — §12)*; ⛔ **not `C`** *(the only extra-kernel
variable is a POLICY, which is not persisted state)*; ⛔ **not `D`** *(the witness is explicit and
self-certifying: "deterministic over explicit provenance", "graph reachability, decidable",
"IndependenceAssessment … value-object")*.

# 15. Kernel status

$$\boxed{|K| = 11 \textbf{ — unchanged. No cell added, removed, split, merged or re-scoped.}}$$

⭐ **And the closure argument is now stronger, not merely unchanged:** the only genuinely multi-hop
corpus structure found by `P-26.1` has been tested and lands **outside** persistence, while
**depending on `K1`** for its own correctness (§6 `G7`).

## Qualified / open / refuted
**`[QUALIFIED]`** ⭐ **`P-26.1`'s inference *"`P-16` §14 says closure is computed, therefore not a
persistence obligation"*** — the conclusion holds, **but that inference is not a valid proof form** and
is replaced by §3–§8's necessity argument.
**`[REFUTED]`** `TG-02` as a probabilistic inference · `ProvenanceGraph`/`ProvenanceChain`/
`IndependenceGraph`/`TG02Entity` as required domain concepts.
**`[UNWITNESSED]`** persistence of the independence **result**.
**`[OPEN]`** ⭐ whether `CommonAncestor` means **current** or **historical** ancestry *(covered either
way — §5)* · the remaining five slots of `I(e_i,e_j)` · `Cases A–F` are **`CONCEPTUAL_CHECK`, 0
executed** · well-foundedness / terminality / acyclicity **(deferred, untouched)** · `P-26.1`'s and
`P-25`'s carried opens.

# 16. ⭐ ONE next unresolved question

$$\boxed{\begin{array}{c}\textbf{Is the POLICY } \boldsymbol{\rho} \textbf{ itself something persistence must retain?}\\[4pt] \boxed{\begin{array}{l}\textbf{⭐ } \rho \textbf{ is the ONLY variable found outside } K1\text{–}K11 \textbf{ (§8), the corpus marks it a } \textit{"policy oracle — deliberate"}\textbf{,}\\ \textbf{and } \mathit{Qualify} \textbf{ is marked } \textit{policy-parametric} \textbf{ too — so this recurs. And } F7 \textbf{ shows a policy change alters an}\\ \textbf{assessment with the state unchanged, which is exactly the shape that would make a WARRANT unre-evaluable.}\end{array}}\end{array}}$$

---

```
A — ADMISSIBILITY / ASSESSMENT ONLY. TG-02 CREATES NO INDEPENDENT PERSISTENCE OBLIGATION.
KERNEL REMAINS 11 CELLS — UNCHANGED, AND WITH A STRONGER CLOSURE ARGUMENT.

THE WITNESS SELF-CERTIFIES. verification/spec/STEP-VERIFY-001-010.md gives Ind_rho(e_i,e_j,G,C) ->
{Independent, Dependent, Unknown} with the "Deterministic rule: provenance path e_i ⇝ e_j => Dependent.
CommonAncestor => Independent != Confirmed", and its own §6 verification says "computable: GRAPH
REACHABILITY, DECIDABLE" and "well-defined RELATIVE TO rho (the 'required conditions' are a POLICY
ORACLE — deliberate)", with the Result line reading "DETERMINISTIC OVER EXPLICIT PROVENANCE; Unknown
elsewhere." Its own DDD line types the output as "IndependenceAssessment as domain object with Basis —
sound VALUE-OBJECT design."

THE COMMISSIONED CORRECTION IS HONOURED: "computed, therefore not persistent" is REJECTED AS A PROOF
FORM and used nowhere. The three questions are kept apart, and it is question B — is the information
needed to RECOMPUTE it already required to persist? — that decides the kernel issue. It is: every datum
maps to K1 (endpoint identities), K2 (current source value, and DIRECTION INTRINSICALLY, since a source
field belongs to its bearer), K3a (historical edge changes via tau-6), K3b (edge warrant) and K4a-i
(retained evidence). "The system would need it to compute TG-02" was never used as evidence.

THE PRIMARY NECESSITY TEST SUCCEEDS — AND ITS SUCCESS CONFIRMS VERDICT A. S_K(H1) = S_K(H2) with
TG-02(H1) != TG-02(H2) is constructible, but ONLY by varying the policy rho, which is not kernel state.
So it shows no information deficit; it shows TG-02 is POLICY-RELATIVE, i.e. an assessment. And a
policy-relative judgement cannot safely be persisted as fact, because the policy may change — an
argument against result-persistence drawn from the witness's own words rather than from "it is
computed".

THE ANCESTRY AMBIGUITY CANNOT CREATE A DEFICIT: the rule does not say whether CommonAncestor means
CURRENT or HISTORICAL ancestry ([OPEN]) — but current ancestry is K2 and historical ancestry is K3a, so
EITHER READING IS COVERED.

G7 IS THE ONE DEPENDENCY: if two provenance nodes are merged, TG-02 flips Independent -> Dependent —
and P-19 showed that under identity collapse the retained CONTENT is identical while DENOTATION
differs. That is W1's shape, and R-INV-04's own words ("Independent provenance NODES merged or
overwritten") place it at K1. So TG-02's correctness DEPENDS on K1 — a dependency, not a deficit.

FAILURE MODES: seven tested; five map to existing cells (K2/K3a, K3a, K1, K3b), two are assessment
degradations to Unknown — the same epistemic-downgrade pattern P-25 found — and F7 is decisive: a
DIFFERENT RESULT with UNCHANGED STATE is a POLICY event, not a persistence failure.

THE ATTACK ON "PERSISTENCE SUPPLIES / ADMISSIBILITY CONSUMES" FAILS, and the distinction survives.
Eight falsification patterns return ZERO admissible files; the two "preserve independence" hits were
CHECKED rather than counted and are different senses entirely — state-space decomposition dimensions,
and a reviewer's methodological independence from a feedback loop. Persistence of the independence
RESULT is [UNWITNESSED].

QUALIFIED: P-26.1's inference "P-16 §14 says closure is computed, therefore not a persistence
obligation". Its CONCLUSION holds, but that INFERENCE is not a valid proof form; it is replaced here by
the necessity argument of §3–§8.

NEXT QUESTION (one): IS THE POLICY rho ITSELF SOMETHING PERSISTENCE MUST RETAIN? It is the ONLY variable
  found outside K1–K11, the corpus marks it a deliberate "policy oracle", Qualify is marked
  policy-parametric too so the pattern recurs, and F7 shows a policy change alters an assessment with
  the state unchanged — exactly the shape that would leave a WARRANT unre-evaluable.

NO ARCHITECTURE — NO SCHEMA v3 — NO IMPLEMENTATION — NO CANONICAL THEORY — 3MC UNTOUCHED — NO CELL
CHANGED — WELL-FOUNDEDNESS STILL DEFERRED.
```
