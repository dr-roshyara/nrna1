---
artifact: 15 — GAP UPDATE FROM STEPS 012 · 038 · 195 (the identity-continuity seam)
trigger: user directive — *"read step-038, step-012 and step_195 and update accordingly"*
date: 2026-08-31
verdict: **one open problem in Step 288 is RESOLVED · one standing gap CLOSES · one Level-5 claim is DOWNGRADED · a relation absent from every register is RECOVERED**
---

# 15 — Steps 012 · 038 · 195

These were the three highest-density equality files left unread after Step 288 v2 (ranked **1st**,
**3rd** and **4th** in the corpus sweep). They are the **identity–continuity** seam, and they are
older than everything 288 v2 was built on:

| File | date | rank |
|---|---|---|
| `step-012` identity, entity resolution, semantic equivalence | **08-27 15:34** | 4th (106) |
| `step-038` identity, entity resolution, equivalence, reference integrity | **08-28 10:29** | 3rd (111) |
| `step_195` **the identity–continuity model** | **08-29 10:44** | **1st (124)** |

## 1. ⭐⭐ RESOLVED — the transitivity problem, by context-relativization

**Step 288 v2 §6 recorded transitivity as unestablished, and therefore the existence of every quotient
in the programme as in doubt.** Five loci flag it unsafe:

| `25J.27` | *"context-dependent semantic mappings can break naive transitivity"* |
|---|---|
| `25S.6` | *"evidence for Same-As is not necessarily transitive"* |
| `25S.29` | transitive closure propagates errors → *"identity closure must be governed"* |
| **`38.16`** | *"Similarity does not necessarily have transitivity … cannot automatically be treated as equality"* |
| **`38.47`** | *"**Not every 'same' relation should be transitive**"* — `sameVersionFamily` ≢ `sameExactArtifact` |

**`195.16` supplies the resolution, as a numbered invariant:**

$$\boxed{I_{48}:\ \textit{Identity equivalence must be transitive \textbf{within a defined identity context}.}}$$

And `38.6`/`38.7`/`38.81` supply the reason it must be *scoped* that way:
$$\boxed{RealWorldIdentity \neq DomainObjectIdentity} \qquad \boxed{Identity\ is\ contextual}$$
with `38.7` explicit: *"KnowledgeOS must **not** globally impose `GlobalEntityIdentity` where the
domain has intentionally defined separate identities."*

> ### The answer, stated once
> **`≅_I` IS an equivalence relation within an identity context, and is NOT one across contexts.**
> **Quotients therefore exist per-context and are undefined globally.**
>
> **Status change in Step 288: §6 moves from `TECHNICALLY OPEN — quotients may not exist` to
> `RESOLVED (context-relative) · CORPUS · `I_48``.** ✅ **This is the first genuine closure this
> programme has been able to record on the equality axis.**
>
> ⚠️ **Two provisos, and they are load-bearing.**
> 1. **It resolves `≅_I`, not `≡`.** `25J.27`'s warning was about *semantic* equality; `I_48` is about
>    *identity* equivalence. **Whether `≡` is transitive within a semantic context is still open** —
>    but `I_48` supplies the shape of the answer, and `25I.11`'s `≡_sem,C` is already `C`-indexed,
>    which is the same move.
> 2. **"Identity context" is not defined anywhere.** `I_48` is well-formed and its parameter is
>    unbound — Level 4, exactly like `≅_λ`'s `λ` and `≈`'s `𝒪`. **The problem is relocated into a
>    smaller, better-named box.**
>
> **`195.15` names the violation:** `A≡B ∧ B≡C ∧ A≢C` → an **identity consistency violation** — a
> checkable invariant, and a candidate for the invariants artifact.

## 2. ⭐ RECOVERED — `Continuity`, a relation in no register

**`195.21` / `195.52` — three concepts, three questions, three answers:**

| | Question |
|---|---|
| **Identity** | *"Is it the same entity?"* |
| **Continuity** | *"Does the history of one connect meaningfully to the other?"* |
| **Similarity** | *"How much does it resemble the other?"* |

*"They should not collapse."* And **`Similarity` is `evidence for` Identity or Continuity — never
either of them** (`195.21`, `38.77` $\boxed{Similarity \neq Identity}$).

$$Continuity(x,y) = f(IdentityRelation,\ TransitionLineage,\ DomainRules,\ Evidence) \qquad \text{(\texttt{195.51})}$$

> **`Continuity` appears in NONE of the five registers reconciled in Step 288 §1** — not `246`, not
> `258`, not `261.1`, not `25I`, not `25S`. **It is a sixth carrier: a relation between entities across
> a lineage, distinct from `≅_H` (which relates *histories*) and from `≅_I` (which relates
> *references*).**
>
> **`195.24`, boxed as an important result: `Identity` and `Lineage` are ORTHOGONAL** — same
> identity/different states · different identity/**with** continuity · different identity/no
> continuity. ✅ **Agrees with `261.9`'s *"Identity and Provenance equality are independent
> dimensions"* — two loci, one finding.**
>
> **Consequence: the register is 9 relations, not 8** — and `Continuity` is the one with a *functional*
> definition (`195.51`) rather than a predicate one.

## 3. 🔴 DOWNGRADED — `=` structural is not innocently decidable

**Step 288 has `=` at Level 5 (DECIDABLE) throughout, qualified only by the `id`/mutable-`e.state`
contradiction. Three loci show the decidability itself is conditional:**

| `38.53` | date `03/04/2026` — 3 April or March 4? *"Without locale/context, normalization is ambiguous"* → $\boxed{Normalization\ is\ itself\ an\ epistemic\ operation}$ |
|---|---|
| **`38.85`** | $\boxed{Canonicalization\ must\ follow\ evidence,\ not\ precede\ it}$ |
| `012 §27` | *"**canonicalization is not truth**"* |

> **`=` is decidable *relative to a fixed canonicalization*. The corpus forbids fixing one a priori.**
> Every Level-5 claim for `=` — and for `≡_exact`, which is *"canonical serialization plus hash"*
> (`25J.2`) — therefore rests on a **prior epistemic operation that is itself unspecified and
> assumption-laden.**
>
> **Corrected level: `=` is Level 5 CONDITIONAL ON a canonicalization that is not established, and
> that the corpus says must be evidence-driven.** ⚠️ **This is the same defect as `≈`'s unbound `𝒪` and
> `≅_λ`'s unbound `λ` — I had simply not seen that `=` has one too.**
>
> **So the corrected count is: ZERO state-level relations are unconditionally at Level 5.** Step 288's
> *"one state-level relation reaches Level 5"* was itself too generous.

## 4. ⭐ CLOSED — `G-62 Compare undefined`

This programme has carried **`G-62` `Compare` undefined** as a standing gap. **`012 §46` defines it:**

$$\boxed{Compare:\ \mathcal P \times \mathcal P \times C \times \Omega \rightarrow \mathcal Q}$$
$$\mathcal Q = \{Equivalent,\ Compatible,\ Contradictory,\ Related,\ Independent,\ Unknown\}$$

with two companions, and **both are partial**:
$$Resolve: \mathcal R \times C \rightharpoonup \mathcal X \qquad\qquad Normalize: \mathcal R \times \Omega \rightharpoonup \mathcal P$$

> **`G-62` moves from `OPEN — undefined` to `DEFINED (signature, `CORPUS`) · body still absent`.**
> **A signature is Level 3, not Level 6** — so the gap narrows rather than vanishes, and it must be
> reported as narrowed, not closed-out.
>
> ### ⭐ And this reframes `G1`/`Qualify`
> My programme treats **`Qualify : Observation × Policy ⇀ Evidence`** as *the* single irreducible
> blocker. **`012 §46` shows it is one of a FAMILY of four partial epistemic functions** —
> `Qualify` · `Resolve` · `Normalize` · `Compare` — **all partial (`⇀`), all with signatures, none with
> bodies.**
>
> **`Qualify` is not unique. It is the instance of a pattern.** That is a better-posed problem than a
> lone blocker: **what the kernel is missing is a discipline for partial epistemic functions, not one
> function.** *(Classification: `DERIVED` from `CORPUS` signatures. Not a closure — a reframing.)*

## 5. 🔴 `≡` is UNDECIDABLE in general — not merely unspecified

**`012 §35`:** *"Semantic equivalence is generally **not fully decidable**. For arbitrary natural
language, `P_1 ≡ P_2` is not generally decidable. Therefore we cannot promise: KnowledgeOS will always
determine whether two arbitrary sentences mean the same thing."*

> Step 288's matrix marks `≡` Level 5 🔴 — correct, but for the weak reason (*no procedure specified*).
> **The corpus gives the strong reason: there can be no total Boolean procedure.**
>
> $$\boxed{\equiv \text{ can NEVER reach Level 5 in the Boolean sense. This is a theorem-shaped limit, not a work item.}}$$
>
> **`012 §35` draws the right conclusion:** the system must be able to say *"I don't know whether these
> mean the same thing"* — *"**That is a feature, not a failure.**"* ✅ **Same rule as `224.11`, `25S.4`
> and `38.22`. Four independent loci.**

## 6. FOUR rival codomains — and the corpus supplies the principle that reconciles them

| Locus | Codomain | Size |
|---|---|---|
| `25S.3` | `{Same, Different, PossibleSame, Unknown}` | 4 |
| **`38.10`** | `{Unknown, Candidate, Probable, Confirmed, Rejected, Contextual}` | 6 |
| **`012 §35`** | `{Determined, Supported, Candidate, Unknown}` | 4 |
| **`012 §36/46`** | `{Equivalent, Compatible, Contradictory, Related, Independent, Unknown}` | 6 |

**Step 288 §12 reported two. There are four. But `012 §36` states the principle that sorts them:**

> *"**The relation itself should not be confused with its epistemic certainty.**"*

| Dimension | Codomains that belong to it |
|---|---|
| **Relation** — *what holds* | `012 §36/46` (pure) |
| **Status** — *how well we know it* | `012 §35` (pure) · `38.10` (mostly) |
| ⚠️ **conflated** | **`25S.3`** — `Same`/`Different` are relations, `PossibleSame`/`Unknown` are statuses |

> **`012 §36` and `012 §47` give the product form:** `SC = (P_1,P_2,Relation,Context,Basis,Status)`,
> `ComparisonResult = (Relation, Basis, Confidence, Context, Provenance)`, and
> `ResolutionResult = (CandidateEntity, Status, Evidence, Provenance)`.
> **Equality decisions return epistemic OBJECTS, not verdicts.**
>
> **`DERIVED` reconciliation, offered as mine and not as corpus:**
> $$Decide_\equiv:\ \mathcal K \times \mathcal K \rightarrow (Relation \times Status)$$
> **This is not "four-valued equality." It is a two-dimensional result, and three of the corpus's four
> codomains are flattenings of it** — the same defect shape as `Σ`'s 10-value flattening of five axes
> (`08`). ⚠️ **Second occurrence of that pattern; still not a promoted finding.**

## 7. The decision-theoretic layer — absent from Step 288 entirely

`038` treats equality as a **classification problem with asymmetric loss**, which Step 288 does not
touch at all:

| `38.18` | **false merge** — wrong evidence aggregation, false conflicts, contaminated statistics |
|---|---|
| `38.19` | **false split** — duplicated knowledge, missed contradictions, fragmented provenance |
| **`38.20`** | $Cost(FalseMerge) \neq Cost(FalseSplit)$ → *"identity thresholds should be domain-specific"* |
| `38.21` | precision vs recall; *"a conservative identity policy often prefers high precision"* |
| **`38.22`** | $\boxed{UnknownIdentity\ should\ generally\ remain\ Unknown\ rather\ than\ being\ silently\ merged}$ |
| `38.23`·`38.80` | $\boxed{Probabilistic\ identity\ must\ not\ silently\ become\ confirmed\ identity}$ |

> **As a statistical matter this is the correct framing and it is well posed:** equality resolution is
> a decision under asymmetric loss, so the operating point is a **domain parameter**, not a constant —
> and the corpus's answer is a **high-precision policy with an abstention option**. `38.22` is exactly
> the abstention rule; `38.23` is the rule against thresholding a posterior into an ontology.
>
> ⚠️ **But note what it is NOT:** no loss function is specified, no threshold, no calibration, and
> `38.20` is stated as an inequality with neither side quantified. **`Level 4 · PARAMETERIZED`, with
> every parameter unbound** — and it inherits `G-22`/`MT-1` (no probability space `(Ω,𝓕,P)`), so
> *"`P(Same)=0.98`"* (`38.23`) **has no measure-theoretic referent in this theory.**
>
> **Recorded as a genuine dimension of the equality problem that Step 288 omitted, at Level 4.**

## 8. Identifier collision — corpus-anticipated

My register carries the **two authority acts colliding on one `grantId`** as a counterexample. **`38.26`
anticipated the class:** identifiers fail when *"systems are misconfigured; identifiers are reused;
environments are copied; test data is cloned"* — therefore

$$\boxed{UniqueIdentifier\ \text{should be treated as \textbf{strong evidence, not metaphysical truth}}}$$

reinforced by `195.29` (*don't confuse technical ID with domain identity*), `195.49`
$I_{49}$ (*name/identifier/address reuse does not establish identity continuity*), and `195.53`
$I_{51}$ (*domain identity must be defined by domain semantics, not inferred from technical
representation*).

> **My `grantId` collision is an instance of a corpus-named failure class, not a novel finding.**
> Its status is unchanged — it is still a real defect — **but the credit is the corpus's.**

## 9. Two more operations, and a graph the kernel does not have

**`38.41`–`38.42` — a closure operation absent from my `𝒪` discussion:**
identity revision propagates, so alongside `Closure(Assumption)` there is
$$\boxed{Closure(Identity)} \qquad \text{computing } AffectedKnowledge(x)$$
with `195.53` $I_{50}$: *identity merge/split/reassignment must preserve lineage*, and `195.36`
calling identity merge *"a high-impact transition"*. `38.40`: *"split is epistemically expensive."*

**`38.88` and `195.43` — the kernel is a graph federation, not one graph:**

$$KnowledgeOS = G_K + G_P + \mathbf{G_I} + TemporalState + EpistemicControl \qquad (\texttt{38.88})$$
$$G_I \cdot G_T \cdot G_E \cdot G_C \cdot G_G \qquad \text{— \emph{"a federation of semantic graphs over a common lineage model"}} \quad (\texttt{195.43})$$

*"These graphs must interact but **must not be collapsed** into one undifferentiated graph"* (`38.87`).

> ⚠️ **The canonical kernel `K = (D_t, 𝒜, ℛ, Σ_c, E_L)` has ONE relational structure, `ℛ` — and `ℛ`
> was itself reduced to 3 fields (`G-55`/`D-5`).** `038` and `195` require **`G_I` as a separate
> graph.** **Either `ℛ` is a union of graphs the theory says must not be collapsed, or the kernel is
> missing a component.** *(Recorded as a structural question for the kernel, outside Step 288's scope
> — `𝒪`/`𝒯` gate it like everything else.)*
>
> ⚠️ **And `195.44` cautions in the same breath: *"we must not over-engineer."* The corpus is aware the
> federation could inflate. Recorded so this is not read as a mandate for five graphs.**

## 10. Evidence discipline

All three files are **self-verdicted PASS** on **analytic** falsification experiments — `038` runs
**twelve** (§§38.64–38.75), `012` and `195` fewer. **None is executed.** `195.54` says the architecture
*"survives, but becomes more precise"* — a refinement verdict, not a proof.

> **Classification: `CORPUS` · analytic · unexecuted · unratified.** `I_48`–`I_51` are **numbered
> invariants in a research step, not ratified kernel invariants** — the corpus's own invariant
> numbering runs to `I_51` here with no adoption record. **`I_48` is the strongest thing in this seam
> and it is still a candidate.**

## 11. Net effect on Step 288

| `288 v2` claim | After this seam |
|---|---|
| transitivity unestablished → quotients may not exist | ✅ **RESOLVED for `≅_I`** — `I_48`, transitive **within an identity context**; open for `≡` |
| eight relations in the register | 🟠 **NINE** — **`Continuity`** (`195.21/195.51`) was in no register |
| `=` reaches Level 5 | 🔴 **DOWNGRADED** — conditional on a canonicalization the corpus says must follow evidence (`38.85`, `38.53`) → **zero unconditional Level-5 state relations** |
| `≡` Level 5 🔴 (no procedure) | 🔴 **STRENGTHENED** — `012 §35`: **not fully decidable**; never reachable in the Boolean sense |
| two rival codomains | 🟠 **FOUR** — and `012 §36` supplies the **Relation ⊥ Status** principle that sorts them |
| `Qualify` is *the* irreducible blocker | 🟠 **REFRAMED** — one of **four** partial epistemic functions (`Qualify`·`Resolve`·`Normalize`·`Compare`) |
| `G-62 Compare undefined` | ✅ **NARROWED** — signature given (`012 §46`); body absent |
| identity is a five-notion matrix | 🟠 **plus contextual scoping** (`38.6/38.81`) and **orthogonality to lineage** (`195.24`) |
| `grantId` collision is my finding | 🟢 **corpus-anticipated class** (`38.26`) |
| — | 🆕 **decision-theoretic layer** (asymmetric loss, abstention) — omitted by 288, Level 4, all parameters unbound |
| — | 🆕 **`Closure(Identity)`** · **`G_I` as a separate graph** the kernel lacks |

$$\boxed{\textbf{Step 288's verdict still stands: the equality problem is mapped, not solved.}}$$
$$\boxed{\textbf{But ONE thing closed: } \equiv_I \textbf{ is transitive within a context } (I_{48}).}$$

**And one thing got worse: `=` no longer reaches Level 5 unconditionally, so the completeness matrix
now has *no* unconditional Level-5 state-level relation at all.**

## 12. Added to the registers

**Normative:** define **"identity context"** (`I_48`'s unbound parameter) · ratify `I_48`–`I_51` ·
adopt `Relation ⊥ Status` as the decision codomain · the false-merge/false-split **loss asymmetry** and
operating point · is `Continuity` a kernel relation · is `G_I` a kernel component or part of `ℛ`.
**Technical:** `Compare`/`Resolve`/`Normalize`/`Qualify` **bodies** · an evidence-driven canonicalization
(`38.85`) · `Closure(Identity)` / `AffectedKnowledge(x)` · transitivity of `≡` within a semantic context
· calibration for `P(Same)` — blocked on `G-22` (no `(Ω,𝓕,P)`).
**Counterexamples:** `38.16` similarity chains · `38.47` `sameVersionFamily` ≢ `sameExactArtifact` ·
`195.15` `A≡B ∧ B≡C ∧ A≢C` · `38.53` `03/04/2026` · `38.26` identifier reuse · `195.24` different
identity **with** continuity.

## 13. Still not read

`025k` · `025l` · `025m` · `025o` · `025t` · **`025v`** (semantics · ontology · bounded contexts ·
meaning alignment) · `025w` · `step-017` (ontology, concepts, semantics) · `step-022` (identity,
lineage, provenance) · `step-039` (bounded-context translation — commissioned by `38.89`) ·
`step-073` (identity, trust, cryptographic provenance) · `step_203` (state-space decomposition).
**`025v`, `step-017` and `step-039` are the semantic-translation thread `38.89` opens, and it is
unexamined. Recorded so this is not mistaken for coverage.**
