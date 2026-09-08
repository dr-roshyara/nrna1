# `P-60` — Retention Obligation ↔ Capability Typing / Realization Audit

**2026-09-09 · Lane T.** After [`77` `P-59`](./77-P59-TRACE-WITNESSABILITY-AND-CAPABILITY-ORDERING-AUDIT.md).

# 1. Executive verdict

$$\boxed{\mathbf{C\ —\ TYPE\ MISMATCH\ ESTABLISHED;\ CATEGORICAL\ INCOMPATIBILITY\ NOT\ ESTABLISHED.}}$$

⭐⭐⭐ **Three independent reasons, and the third was not anticipated by the commission:**

| | |
|---|---|
| **①** | ⭐⭐ **The framework has a designated non-capability slot.** `ℐ` *"Invariants to be preserved"* sits in the environment tuple, `I_K` in the kernel tuple, and the core characterization writes transitions **subscripted by it**: $K_t \xrightarrow{E,Q,C,H}_{\mathcal I} K_{t+1}$. ⭐ `Preserve(T,I)` is witnessed |
| **②** | ⭐⭐ **`𝔎_adm` ranges over five-tuples, not capability sets.** ⛔ **`P-59`'s exclusion of R from `K_adm` on type grounds is REFUTED** — R's obstacle is missing components, not a wrong-typed cell |
| **③** | ⭐⭐⭐ **The formalism I typed against is absent from the estate's own indexed synthesis.** `MinKer` · `⪯_cap` · `𝒳→𝒳` · **`capability`** all return **ZERO** across the **16-document** `Theory 00–14` set of 2026-09-04 11:00 |

$$|K| = 11 \textbf{ — unchanged, } \mathbf{[REC]}. \textbf{ ⛔ Nothing invented: no } \mathcal X\textbf{, no capability, no bridge.}$$

# 2. ⚠️ Concessions — three, and one is a measurement failure

**2.1 Conceded.** `P-59` moved from *"`K_i` is not itself a capability"* to *"the eleven cells can never
be a `MinKer` candidate."* ⛔ **That excluded realization (`H2`) and property semantics (`H3`) without
proof.** The commissioner's decomposition is adopted verbatim.

**2.2 ⚠️⭐⭐⭐ A measurement failure inside this audit, reported because it nearly produced the wrong
verdict.** My first `H2`/`H3` probes ran from the wrong working directory, so every relative corpus path
silently failed to resolve. **They returned all zeros.** Corrected with absolute paths:

| probe | false zero | ⭐ **true count** |
|---|---:|---:|
| `invariant` | 0 | ⭐⭐⭐ **917 files** |
| `invariant-preserving` | 0 | **13** |
| `capabilit* (realiz\|enforc\|satisf\|preserv)` | 0 | ⭐ **10** |
| `(realiz\|enforc\|satisf)* … invariant` | 0 | ⭐⭐ **43** |

$$\boxed{\textbf{⛔ } \mathbf{A\ zero\ from\ a\ broken\ search\ is\ indistinguishable\ from\ a\ true\ negative} \textbf{ — and this programme's method rests on negatives. ⇒ } \mathbf{EKS\text{-}21}.}$$

**2.3 ⭐⭐ A self-correction on `P-59`'s component count.** `P-59` credited `δ_K` as **SUPPLIED**.
⛔ **Wrong: without an `𝒳` for `δ` to act on, a transition CLASS is not a transition FUNCTION.** R
supplies **0 of 5 formal components** — ⭐ while holding the estate's **richest empirical transition
evidence**. **The asset is empirical, not formal**, and `P-59` blurred that.

⛔ **No `three_model_convergence` · no `K1`–`K11` change · no `|K|` change · no `MinKer` · no minimality
claim · no R→S refinement · no canonicalization · no architecture/Schema v3/governance change · no
`𝒳` invented · no capability invented to make R fit · `P-57` not reopened.**
⚠️ **Pre-registered `C`.** ⭐ Correct — record 17 of 25.

# 3. ⭐⭐⭐ The discovery that reframes the question

`20260904-110000_theory-00-index-and-reading-order.md` — ⭐⭐ **an INDEX. Document 00 of 15, with a
reading order, per-document subjects, a stated scope bound, and a tag ledger.**

$$\boxed{\textbf{⭐⭐⭐ The estate has an index of its own theory, and } \mathbf{no\ Lane\ T\ audit\ ever\ found\ it.} \textbf{ Eleventh miss, and the most structural.}}$$

⭐ **Its central architecture is not the capability formalism:**

$$D \xrightarrow{\;T\;} R, \qquad Q : D \to Y, \qquad \Pi : R \to O$$

with the load-bearing result $\textbf{Eliminability} \neq \textbf{Preservation} \neq \textbf{Realization}$,
tagged **`[EXP]`, explicitly not `[THM]`**.

⭐⭐ **Its own honest ledger is the most disciplined thing in the estate:** `[EXP]` **129** · `[NEG]`
**118** · `[REC]` 40 · `[OPEN]` 34 · `[DEF]` 24 · `[CONJ]` 13 · `[DEFECT]` 11 · `[THM]` 8 occurrences
but ⭐⭐⭐ ***"ONE distinct theorem — the data-processing inequality, cited, not ours."***

$$\boxed{\textbf{⭐⭐ } \mathbf{118\ refutations\ against\ 129\ empirical\ results,\ and\ exactly\ one\ theorem,\ borrowed.} \textbf{ That is the estate's actual position.}}$$

⭐ And the carrier: `r = (value, source, tag, timestamp)`, `|D| ∈ {4,5,6}` — ⛔ **`[OPEN]`, *"no carrier
is declared."*** ⭐⭐ **Which independently confirms `P-60`'s `𝒳` finding below, from the estate's own voice.**

# 4. ⭐⭐ The `§4` counterexample test — the implication is **INVALID**

$$K_i \notin (\mathcal X \to \mathcal X) \;\Longrightarrow\; K_i \textbf{ cannot participate in the capability formalism}$$

⛔ **Refuted by the formalism's own structure.** `𝔈` carries `ℐ` *"Invariants to be preserved"* as a
component **distinct from capabilities**; the kernel five-tuple carries `I_K` *"its invariant
structure"*; `Lemma 1`'s witness condition is stated in terms of **preserving or violating an invariant
`i ∈ ℐ`**; `⪯_cap` requires simulation *"without violating contract invariants `ℐ`"*; and the core
characterization subscripts the transition arrow with `ℐ`.

$$\boxed{\textbf{⭐⭐⭐ } \mathbf{A\ non\text{-}function\ object\ that\ constrains\ transitions\ has\ a\ DESIGNATED\ HOME\ in\ this\ formalism.} \textbf{ } P\text{-}59\textbf{'s "categorical" reading is TOO STRONG.}}$$

⛔ **And R is not rescued by this.** The corpus **never says** a persistence obligation is an invariant.
⭐ What is established is only that **the shape has a slot** — ⛔ **not that the eleven cells occupy it.**

# 5. `H1` / `H2` / `H3`

| | hypothesis | verdict |
|---|---|---|
| **`H1`** identity — $K_i : \mathcal X \to \mathcal X$ | each cell **is** a capability | ⭐ **`[REFUTED]`** — `P-59`'s finding stands; `Persist(x,τ)` is a predicate over triples |
| **`H2`** realization — $c_i \models K_i$ | a witnessed capability **enforces** a cell | ⚠️ **`[OPEN]`** — ⭐ **10 files carry capability-realization language and 43 relate realization/satisfaction to invariants**, ⛔ **none for R's cells.** Language exists; the instance does not |
| **`H3`** property — $K_i(c,\tau,x,\ldots)$ | a cell is a **predicate over a transition** | ⭐⭐ **`[OPEN]`, and the strongest** — `ℐ`, `I_K`, `→_ℐ` and the witnessed `Preserve(T,I)` give exactly this shape. ⛔ **But `Preserve(T,I)` is used for ACL translation, not for persistence obligations** |

⭐ **Notation discipline honoured:** `c_i ⊨ K_i` and `K_i(c,τ,x)` are **my analytical notation for a
witnessed shape**, ⛔ **never presented as corpus-native.**

# 6. ⭐⭐ The `𝒳` test *(§5)* — three witnessed candidates, none over R

| candidate | source | verdict |
|---|---|---|
| `EpistemicState(knowledge_base, hypotheses, qualifiers)` with a `digest()` | ⭐ the **executable testbed**, `014642` §4 | ⛔ **an illustrative carrier** — `Fact(entity, attribute, value)`; nothing about candidates, definitions, associations, supersession or warrants |
| ⭐⭐⭐ $\Omega_E$ with $\omega=(C,E,Ch,D,B,R,H,\ldots)$, $K_t = P_t(\Omega_E)$ | `035450_knowledge-as-an-epistemic-probability-space` | ⭐⭐ **`H` = epistemic history is a STATE COMPONENT** — the closest witnessed shape to a retention obligation. ⛔ But `K_t` is a **distribution**, the tuple ends in *"…"*, and it is proposal-grade |
| a measurable Knowledge Space $KS=(\mathcal X,\mathcal A)$ with a filtration | 2026-09-02 | ⛔ probability law deferred |
| **Schema v2** — the extraction record structure, *"appended to, never edited"* | Lane T | ⚠️ **a genuine record carrier**, ⛔ **but the corpus never gives it state-space semantics and no transition is defined as a map over it** |

⭐⭐⭐ **Three incompatible `𝒳`s for KnowledgeOS; ⛔ NONE over the research estate's registers.**

$$\boxed{\mathcal X_R = \mathbf{[UNWITNESSED]} \textbf{ — ⭐ and } \mathbf{unwitnessed\ BY\ DESIGN}, \textbf{ not by omission.}}$$

⭐ The charter is explicit: *"select a carrier — **`OQ-1` is out of scope**"*, and **every** Lane T
artifact banner reads *"no carrier."* ⭐⭐ The question is **live, named, and assigned to Governance**,
with a `carrier-options/ (V1)` work item and an `OQ-1` decision brief. ⛔ **This audit does not touch it.**

# 7. `τ` versus `c` *(§6)*

⭐ Tested rather than assumed in either direction. `τ1`…`τ11` are **witnessed transition CLASSES** —
status change, re-disposition, rename, rescan — each with before/after occurrences and an adjudicated
loss severity. ⛔ **The corpus nowhere gives them state-transformer semantics**, and it cannot: a
transformer needs a domain, and `𝒳_R` is unwitnessed.

$$\boxed{\tau \neq c \textbf{ — ⭐ not refuted, } \mathbf{UNDECIDABLE\ until\ }\mathcal X_R\mathbf{\ exists.} \textbf{ ⛔ And } P\text{-}59\textbf{'s "}\delta_K \textbf{ supplied" was wrong.}}$$

# 8. ⭐⭐⭐ Does `MinKer` require kernel members to be capabilities? *(§7)*

| | option | evidence |
|---|---|---|
| A | kernel members = capabilities | ⛔ **no** |
| B | kernel = set of capabilities | ⚠️ **only in the testbed** — `EpistemicKernel.__init__(capabilities)`, which **contradicts the five-tuple** |
| ⭐ **C** | **kernel = a structure whose capabilities realize required properties** | ⭐⭐ **yes, in the 01:43–02:06 formalism** — `𝔎_adm` is *"the space of admissible **Kernel implementations**"* and an implementation is $(X_K,\delta_K,\mathcal C_K,I_K,O_K)$ |
| D | another object | — |
| ⭐⭐⭐ **E** | **the corpus leaves it unspecified** | ⭐⭐⭐ **yes, across programmes** — the 16-document indexed synthesis has **no `MinKer`, no `⪯_cap`, no `capability`, and its own `theory-12-ddd-architecture-of-the-kernel` never states what the kernel *is*** |

$$\boxed{\textbf{⭐⭐ Answer } \mathbf{C\ within\ one\ formalism,\ E\ across\ the\ estate.} \textbf{ ⛔ Either way, } \mathbf{R\ is\ NOT\ excluded\ on\ type\ grounds.}}$$

# 9. Carried forward without weakening *(§8)*

⭐ `⪯_cap` formally stated, **still under-specified** · `≡_sem` ↔ `≡_cap` **unresolved, flagged a central
lemma** · `𝒪` has **competing versions** · `Obs_𝔠` **not enumerated** · `𝔠 = C_KOS` **unresolved** ·
`K_adm` **circular by the corpus's own account** · **`Theorem 1` covers proper subsets only** ·
**`Lemma 1` carries an undischarged universal over composites** · **experimental ≠ mathematical
irreducibility**, and Lane T's cell-independence arguments are the former.

# 10. Statistical discipline *(§9)*

⛔ 11 cells · 5 components · 917/13/10/43 file counts · 0-of-16 hits — **descriptive observations over
one corpus.** ⛔ No confidence level, no replication claim, no independence assumed among counts.
⭐ **Deep-read: 4 new primaries** *(the index, `theory-01`, `theory-12`, the probability-space document)*
**plus 6 carried from `P-59`.** ⚠️ **Coverage, not exhaustiveness — and §2.2 shows why that caveat is not
decoration.**

# 11. ⭐⭐ Attack on `P-59`

| claim | verdict |
|---|---|
| the corpus types a capability as `𝒳 → 𝒳` | ⭐ **`[CONFIRMED]`** — `Definition 3`, verbatim |
| the eleven cells are not themselves capabilities of that type | ⭐ **`[CONFIRMED]`** |
| ⭐⭐⭐ *"categorical type mismatch"* | ⭐⭐⭐ **`[REFUTED]` — TOO STRONG.** §4: the formalism has an invariant slot; §8: `K_adm` ranges over structures; §3: the formalism is absent from the indexed synthesis |
| *"not a `MinKer` candidate"* | ⭐⭐ **`[REFUTED]`** — exclusion was asserted, never proved |
| *"`δ_K` ✅ SUPPLIED"* | ⭐⭐ **`[REFUTED]` by me** — a transition class is not a transition function |
| *"R supplies 1 of 5"* | ⚠️ **`[QUALIFIED]` → 0 of 5 formal**, ⭐ with the richest empirical transition evidence in the estate |
| *"`|K| = 11` permanently a lower bound"* | ⭐⭐⭐ **`[REFUTED]`** — *"permanently"* rested on the exclusion, which is refuted. ⭐ **The correct status is `[OPEN]`** |

# 12. Consequence for R *(required)*

$$\boxed{\mathbf{POTENTIALLY\ REPRESENTABLE,\ AFTER\ TWO\ UNPROVED\ STEPS\ —\ neither\ of\ them\ Lane\ T's\ to\ take.}}$$

⭐ **① a carrier decision** — `𝒳_R`, `OQ-1`, **explicitly out of scope and assigned to Governance**.
⭐ **② a witnessed obligation↔invariant bridge** — `H3`'s slot exists; ⛔ its occupancy is unproved.
⛔ **Not excluded from the capability framework. ⛔ Not representable today. ⭐ Not undecidable either —
the two missing steps are named, and one of them is already an open governance question.**

# 13. Backlog — ⭐⭐ **one, and it is new**

**`EKS-21`** — ⭐⭐⭐ **a search that fails silently returns zero, and a zero is this programme's most
common published finding.** §2.2 is first-hand evidence: four probes returned `0` that should have
returned `917`, `13`, `10`, `43`. ⛔ Distinct from `EKS-16` *(search not run)*, `EKS-17` *(two roots)*,
`EKS-20` *(sources not recorded)*: **this is a negative result with no positive control.**

# 14. Status register
**`[EMP]`** ⭐⭐⭐ the 16-document indexed set and its ledger (129 `[EXP]` · 118 `[NEG]` · **1 distinct
theorem, cited**) · `MinKer`/`⪯_cap`/`𝒳→𝒳`/`capability` = **0** across it · `theory-12` never defines the
kernel · *"no carrier is declared"* · `ℐ`, `I_K`, `→_ℐ`, `Preserve(T,I)` · `𝔎_adm` = admissible **kernel
implementations** · `Ω_E` with `H` a state component · the testbed's `EpistemicState` and its
capability-list kernel · charter *"`OQ-1` out of scope"* · corrected counts 917/13/10/43.
**`[DERIVED]`** ⭐⭐⭐ the exclusion implication is **invalid** · R is **not** excluded on type grounds ·
`𝒳_R` **`[UNWITNESSED]` by design** · `τ ≠ c` **undecidable** until `𝒳_R` exists · R supplies **0 of 5**
formal components.
**`[CORROBORATION]`** ⭐⭐ the indexed set's *"no carrier declared"* ↔ this audit's `𝒳_R` finding ·
⛔ one corpus, no replication.
**`[QUALIFIED]`** ⚠️ `⪯_cap` · `P-18`/`P-47` independence — experimental · `P-59`'s component count.
**`[REFUTED]`** ⭐⭐⭐ `P-59`'s *"categorical mismatch"*, *"not a `MinKer` candidate"*, *"`δ_K` supplied"*,
*"permanently a lower bound"* · `H1`.
**`[OPEN]`** ⭐⭐ `H2` · `H3` · `𝒳_R` / `OQ-1` · `𝒪` · `Obs_𝔠` · `𝔠` · `K_adm` · `≡_cap ⟺ ≡_sem` ·
granularity · all carried opens · ⛔ **well-foundedness / terminality / acyclicity — DEFERRED.**
**Governance:** `|K| = 11` **`[REC]`** — unchanged, unfrozen, ⛔ **and its minimality status is `[OPEN]`,
not foreclosed.**

### 15. ONE NEXT UNRESOLVED QUESTION

$$\boxed{\textbf{Which of the estate's } \mathbf{TWO\ FORMAL\ PROGRAMMES} \textbf{ — the capability/}\mathsf{MinKer}\textbf{ apparatus, or the indexed } D\!\to\!T\!\to\!R \textbf{ theory — is the one the persistence kernel must answer to?}}$$

⭐⭐⭐ **Both were written on 2026-09-04, nine hours apart, by the same programme, and they share almost
no vocabulary.** ⭐ The later one carries an index, a reading order, an adopted status vocabulary and an
honest ledger; ⛔ the earlier one carries the proofs, the testbed and the minimality machinery, **and
appears nowhere in the later one.** ⚠️ **Until that is settled, every statement about the kernel's
minimality is a statement about an unnamed framework** — including six of my own. ⛔ **And the answer
must be found in the record, never chosen for convenience: choosing would be adopting a theory, which is
adjudication, which is not this lane's act.**

```
C — TYPE MISMATCH ESTABLISHED; CATEGORICAL INCOMPATIBILITY NOT ESTABLISHED.

Three independent reasons, the third unanticipated. ONE: the framework has a designated non-capability
slot — I "Invariants to be preserved" in the environment tuple, I_K in the kernel tuple, transitions
subscripted by it, and Preserve(T,I) witnessed. So a non-function object that constrains transitions has
a home, and P-59's "categorical" reading is too strong. TWO: K_adm ranges over five-tuple kernel
IMPLEMENTATIONS, not capability sets, so R's obstacle is missing components, not a wrong-typed cell.
THREE: the formalism I typed against is ABSENT from the estate's own indexed synthesis — MinKer, <=cap,
X->X and even the word "capability" return ZERO across the 16-document Theory 00-14 set.

THE DISCOVERY THAT REFRAMES THE QUESTION: theory-00 is an INDEX — document 00 of 15, with a reading
order, per-document subjects, a stated scope bound and a tag ledger. No Lane T audit ever found it.
Eleventh miss, and the most structural. Its architecture is D -T-> R with Q : D -> Y and Pi : R -> O,
its load-bearing result is Eliminability != Preservation != Realization tagged [EXP] and explicitly not
[THM], and its ledger reads 129 [EXP] against 118 [NEG] with exactly ONE distinct theorem — the
data-processing inequality, cited, not theirs. It also states "no carrier is declared", which
independently confirms this audit's own X_R finding in the estate's voice.

A MEASUREMENT FAILURE INSIDE THIS AUDIT, REPORTED BECAUSE IT NEARLY PRODUCED THE WRONG VERDICT. My first
H2/H3 probes ran from the wrong working directory, so every relative path silently failed. Four probes
returned zero that should have returned 917, 13, 10 and 43. A zero from a broken search is
indistinguishable from a true negative, and this programme's method rests on negatives. EKS-21 filed.

H1 identity REFUTED, P-59's finding stands. H2 realization OPEN — ten files carry capability-realization
language and forty-three relate realization to invariants, but none for R's cells. H3 property OPEN and
strongest — the slot and the predicate shape both exist, but Preserve(T,I) is used for ACL translation,
not persistence.

THE X TEST: three witnessed candidates for KnowledgeOS — the testbed's EpistemicState, Omega_E with
omega = (C,E,Ch,D,B,R,H,...) where H is epistemic history as a STATE COMPONENT, and a measurable
Knowledge Space with a deferred probability law — and NONE over the research estate's registers. Schema
v2 is a genuine record carrier but is never given state-space semantics. X_R is UNWITNESSED, and
unwitnessed BY DESIGN: the charter puts carrier selection out of scope as OQ-1, assigned to Governance
with a live options paper.

Consequently tau != c is UNDECIDABLE until X_R exists, and P-59's "delta_K supplied" was wrong — a
transition class is not a transition function. R supplies 0 of 5 FORMAL components while holding the
estate's richest EMPIRICAL transition evidence. The asset is empirical, not formal.

Does MinKer require kernel members to be capabilities? Answer C within the 01:43-02:06 formalism, E
across the estate — and the document titled "DDD architecture of the kernel" never states what the
kernel is. Either way R is NOT excluded on type grounds.

ATTACK ON P-59: the capability typing CONFIRMED; the cells-are-not-capabilities finding CONFIRMED; but
"categorical type mismatch" REFUTED as too strong, "not a MinKer candidate" REFUTED as asserted rather
than proved, "delta_K supplied" REFUTED by me, "1 of 5" QUALIFIED to 0 of 5 formal, and "|K| = 11
permanently a lower bound" REFUTED — "permanently" rested on the exclusion. The correct status is OPEN.

CONSEQUENCE FOR R: POTENTIALLY REPRESENTABLE, after two unproved steps, neither of them Lane T's to
take — a carrier decision (OQ-1, out of scope, Governance) and a witnessed obligation-to-invariant
bridge. Not excluded. Not representable today. Not undecidable either: both steps are named.

Pre-registered C. Correct — record 17 of 25.

|K| = 11 UNCHANGED, [REC], UNFROZEN, AND ITS MINIMALITY STATUS IS OPEN, NOT FORECLOSED. Nothing
invented: no X, no capability, no bridge.

ONE NEXT UNRESOLVED QUESTION: Which of the estate's TWO FORMAL PROGRAMMES — the capability/MinKer
  apparatus, or the indexed D->T->R theory — is the one the persistence kernel must answer to? Both were
  written on 2026-09-04, nine hours apart, by the same programme, sharing almost no vocabulary. The later
  carries the index, the status vocabulary and the honest ledger; the earlier carries the proofs, the
  testbed and the minimality machinery, and appears nowhere in the later. Until that is settled, every
  statement about the kernel's minimality is a statement about an unnamed framework — including six of
  my own. The answer must be FOUND in the record, never chosen for convenience: choosing would be
  adjudication, which is not this lane's act.

NO three_model_convergence INSPECTION — excluded by path from every command, unread.
```
