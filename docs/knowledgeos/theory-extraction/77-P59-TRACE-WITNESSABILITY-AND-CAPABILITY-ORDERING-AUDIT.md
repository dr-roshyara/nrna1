# `P-59` — `Trace(K,h)` Witnessability & Capability-Ordering Audit

**2026-09-09 · Lane T.** After [`76` `P-58`](./76-P58-FA7-CAPABILITY-LEVEL-TRACEABILITY-AUDIT.md).

**Primaries, all `…/mathematical_ideas_that_can_be_implemented/`, all 2026-09-04, read in full or in the
decisive sections:** `014317_kernel-minimality-as-a-relative-conditional-proof-not-universal` (**3 013 ln**) ·
`014642_unified-formal-evaluation-kernel-minimality-semantic-capability-ordering` (**1 190**) ·
`014807_substantial-agreement-with-corrections-minimality-and-ratification` (**711**) ·
`015837_close-to-freeze-quality-four-final-corrections-to-sem-equivalence` (**243**) ·
`020004_remaining-todos-after-v12-freeze` (**642**) ·
`020642_defining-the-remaining-formal-todos-semantic-environment-first` (**3 054**).
⭐⭐ **Six documents, ~8 850 lines, produced in one 90-minute window.**

# 1. Executive verdict

$$\boxed{\mathbf{C\ —\ THE\ TRACE\ AND\ ORDERING\ APPARATUS\ EXISTS\ AND\ IS\ NEARLY\ COMPLETE,\ BUT\ NOT\ FOR\ THE\ R\ PERSISTENCE\ KERNEL.}}$$

⭐⭐⭐ **And the reason is a TYPE MISMATCH, not a gap.** `Definition 3` of `014642`:

$$\textbf{a capability is a mapping } c : \mathcal X \to \mathcal X$$

$$\boxed{\begin{array}{c}\textbf{The eleven cells are } \mathbf{RETENTION\ OBLIGATIONS\ ON\ TRANSITIONS} \textbf{ — } \mathbf{\textit{"x survives }\tau\textit{"}} \textbf{ — } \mathbf{not\ state\ mappings.}\\[4pt] \textbf{⛔ } K_i \textbf{ is not of type } c:\mathcal X \to \mathcal X\textbf{, so the eleven cells are } \mathbf{NOT\ CAPABILITIES\ in\ the\ corpus's\ sense.}\end{array}}$$

⭐⭐ **`P-58`'s framing was wrong twice over.** `Trace` is **not** the bottleneck — it is defined. And the
bottleneck is not a missing definition at all: ⛔ **it is that the R kernel is the wrong kind of object
for the machinery that exists.**

$$|K| = 11 \textbf{ — unchanged, } \mathbf{[REC]}. \textbf{ ⛔ No } Trace \textbf{ manufactured · no } \mathsf{MinKer} \textbf{ · no minimality claim · no retyping of any cell.}$$

# 2. ⚠️ Concessions and disagreements

**2.1 Conceded.** *"If `Trace` is constructible, `⪯_cap` becomes computable"* was too strong — ⭐ and §6
shows it is worse: `⪯_cap` needs a simulation mapping `Φ`, an observable set, a contract and an
admissible class. `Trace` is **one input of four**.

**2.2 ⭐⭐⭐ Disagreement with the commission's premise — `Trace(K,h)` is not the corpus's object.**
`Trace(K,h)` appears at **01:58** as a *demand* — *"the next proof object must define at least
`Trace(K,h)`"* — with **`h` never defined**. ⭐⭐ **Eight minutes later the corpus answers and does not use
`h`**: it writes $\operatorname{Tr}_K(s,n)$, `s` an admissible **scenario**, `n` a finite **horizon**.
⛔ **Auditing `h` would have audited a ghost.**

**2.3 ⭐ §5's menu `A`–`F` was answered from the corpus, not chosen from the menu.**

⛔ **No `three_model_convergence` · no `K1`–`K11` change · no `|K|` change · no `MinKer` · no minimality
claim · no R→S transfer · no canonicalization · no architecture/Schema v3/governance/theory change · no
external simulation theory imported · no transition sequence assumed to be a trace.**
⚠️ **Pre-registered `C`.** ⭐ Correct — record 16 of 24, ⛔ **but three sub-claims of my own first draft
were refuted by the two files the commissioner named mid-audit, and are corrected below in §12.**

# 3. Hit map and coverage

`Trace(` **22 files**, 13 distinct arities, ⛔ none the ordering's · `observable trace` **6**, five of them
this 09-04 cluster · `losslessly simulate|lossless simulation` **9** · `bisimul*` **3 — all
literature-survey, none adopted** · `\btrace\b` **213**, overwhelmingly unrelated senses.

⭐⭐ **Deep-read: 6 primaries.** ⚠️ **Coverage, not exhaustiveness.** ⭐ **Tenth miss — but the first
found *inside* the commissioned search**, ⛔ **and two of the six primaries still had to be handed to me
mid-audit.**

# 4. What the corpus actually has *(§1–§3)*

**⭐⭐⭐ `T2` — formal definitions, several, and a `T3` I wrongly said did not exist.**

| object | definition | status |
|---|---|---|
| semantic environment | $\mathfrak E = (\mathcal X,\mathcal Q,\mathcal C,\mathcal H,\mathcal S,\mathcal I,\mathcal A[,\mathcal T])$ | ⭐ **`T2`** ⚠️ 7-tuple and 8-tuple, 20 min apart |
| kernel implementation | ⭐⭐ $K = (X_K,\ \delta_K,\ \mathcal C_K,\ I_K,\ O_K)$ — *"not merely a set of operators"* | ⭐ **`T2`** |
| ⭐⭐⭐ **capability** | ⭐⭐⭐ $c : \mathcal X \to \mathcal X$ | ⭐ **`T2` — the decisive one** |
| semantic trace | $\operatorname{Tr}_K(s,n) = (o_t;\ i_t;\ \delta_t) \in \mathcal T_{obs}$ | ⭐ **`T2`** |
| observable set | ⚠️⭐⭐ **TWO**: a **6-vector** *(StateTransition · GapDetection · Determination · RevisionOutcome · InvariantStatus · Attribution)* and an **8-tuple** *(+ History, Transition)* | ⚠️ **the 8-tuple is marked *"a candidate list, not yet a theorem"*** |
| observational equivalence | $K_1 \equiv_{sem} K_2 \iff \forall(q,c,e,x):\ \operatorname{Obs}(K_1,\cdot)=\operatorname{Obs}(K_2,\cdot)$ | ⭐ **`T2`** |
| capability ordering | ⭐⭐ repaired: $K_1 \preceq_{cap} K_2 \iff \exists\Phi_{12}\ \forall s:\ \Phi_{12}(\operatorname{Tr}_{K_1}(s)) \equiv_{\mathfrak C} \operatorname{Tr}_{K_2}(s)$, invariants preserved; `Beh`-inclusion demoted to a **consequence** | ⭐ **`T2`** |
| **Lemma 1** universal irreducibility | a witness $(x_w,q_w,i)$ such that **every** composite over $\mathcal C_K\setminus\{c^*\}$ either misses $a_{valid}$ or breaks $i$ | ⭐⭐ **proved, `∎`** |
| **Theorem 1** minimality | no **proper subset** of $\mathcal C_{irr}$ is $\equiv_{sem}$ to $K^*$ | ⭐⭐ **proved, `∎`** |
| ⭐⭐⭐ **executable testbed** | Python: domain carriers · capability interfaces · a **derived-capability example** · **observable evaluator** · kernel container | ⭐⭐ **`T3` SPECIFICATION** |

⭐⭐ **The corpus repaired its own `Beh`-inclusion definition with a counterexample:** $K_A: K_0\to K_1\to
K_2$ vs $K_B: K_0\to K_2$ — *"same final state, differing lifecycle semantics."* ⭐⭐⭐ **That is `P-08`
§17's insufficiency pair in the same shape, three days earlier.** ⛔ **Shared corpus, no known
information flow — suggestive convergence, not replication.**

# 5. ⭐⭐ The proof's own two limits — reported, not softened

**⭐ Theorem 1 quantifies over PROPER SUBSETS of $\mathcal C_{irr}$ only.** ⛔ It does not exclude a
**different decomposition** realizing the same powers. ⭐⭐⭐ **The corpus itself catches this eighty-five
minutes later**: *"a competing Kernel may realize the same semantic capability through a different
decomposition… the theorem must quantify over admissible implementations and realization mappings, not
simply subsets of one named capability list."*

**⭐⭐ Lemma 1's condition 2 quantifies over *every arbitrary composite* over $\mathcal C_K\setminus\{c^*\}$
— an undischarged universal.** ⛔ Not decidable without characterizing the composition space, which is
`K_adm`, which the corpus's own later review calls *"a subtle but important circularity."*

$$\boxed{\textbf{⭐⭐ A valid proof resting on an } \mathbf{undischarged\ universal\ quantifier} \textbf{ — which is exactly what its own authors then say.}}$$

# 6. Where the chain bottoms out *(§10, §11)*

$$\mathsf{MinKer} \leftarrow \preceq_{cap} \leftarrow \{\Phi,\ \operatorname{Tr}_K(s,n),\ \equiv_{\mathfrak C}\} \leftarrow \{\mathcal O,\ Obs_{\mathfrak C}\} \leftarrow \mathfrak C_{KOS},\ \mathfrak K_{adm}$$

| term | status |
|---|---|
| ⭐⭐ **`𝒪`** | ⚠️ **two incompatible versions** (6-vector, 8-tuple), the larger marked *not yet a theorem* |
| ⭐ **`Obs_𝔠`** | ⛔ never enumerated per contract |
| ⭐⭐ **`𝔠 = C_KOS`** | ⛔ **undefined** — `P-53` **`[CONFIRMED]`** |
| ⭐⭐ **`K_adm`** | ⛔ **circular, by the corpus's own account** |

⭐⭐⭐ **`Trace` is not among them.** And the **v1.2 frozen ledger** independently agrees on the
direction of travel: `Semantic Equivalence (≡_sem)` **FORMALIZATION OPEN** · `Capability Ordering
(⪯_cap)` **FORMALIZATION OPEN** · `Minimal Kernel Existence` **NOT YET PROVED** · `Uniqueness` **NOT YET
PROVED** · `Named Operator Set` **NOT YET PROVED** · `Governance Ratification` **OPEN**.

⚠️ **A ledger saying `FORMALIZATION OPEN` at 01:48 and three formalizations at 01:43–02:06 is not a
contradiction — it is a 90-minute working session.** ⛔ **But nothing in the corpus marks which
formulation supersedes which, and that is a fact about the record, not about the mathematics.**

# 7. ⭐⭐⭐ Can the R kernel enter? — **no, and it fails before the trace question**

**The type test, and it is decisive.** A capability is $c:\mathcal X \to \mathcal X$. The kernel's cells
are $\mathbf{Persist}(x,\tau) \iff x \textbf{ survives } \tau$ — ⛔ **a predicate over (item, transition,
lost datum) triples.** ⭐ **An obligation that something is retained is not a mapping from states to
states**, and the corpus never bridges the two.

**The component test, by counting** — the definition needs $(X_K,\delta_K,\mathcal C_K,I_K,O_K)$ plus `s`, `n`:

| | `P-08` / `P-09.3` |
|---|---|
| **`δ_K`** | ⭐⭐ **✅ SUPPLIED** — `τ1`…`τ11`, every one `[EMP]`-witnessed. **The kernel's strongest asset** |
| **`𝒞_K`** | ⛔ **NOT SUPPLIED** — the cells are not `𝒳 → 𝒳` *(above)* |
| **`X_K`** | ⛔ `state space` **0**; no carrier declared |
| **`I_K`** | ⛔ `invariant` ×2, both incidental; no structure |
| **`O_K`** | ⛔ `interface` **0** |
| **`s`, `n`** | ⛔ `scenario` **0** · `horizon` **0** · `trace` **0** |

⚠️⭐⭐ **A near-miss that must not be counted:** `P-08` uses *"observ\*"* **12 times** — ⛔ **every one an
empirical re-measurement of the corpus** (*"a second observation of a changed corpus"*), **not an
observable interface.** ⭐ Same word, different concept; the `P-38` trap, avoided by reading the lines.

$$\boxed{\textbf{⭐⭐⭐ } \mathbf{1\ of\ 5\ components,\ and\ a\ type\ mismatch\ on\ the\ second.} \textbf{ Construction test } \Rightarrow \mathbf{F}.}$$

⭐ `A` *(sequence of witnessed transitions)* is R's only candidate and **fails**: a trace needs `o_t` and
`i_t` beside `δ_t`, and ⛔ **a transition sequence is not a trace** — §4's own warning.

# 8. Losslessness, countermodel, granularity *(§6–§8)*

⭐ *"Lossless"* is defined **relative to `≡_𝔠`** ⇒ ⛔ undefined until `Obs_𝔠` is. ⭐⭐ **Preserving
`K1`–`K11` does not define it**: cells are retention obligations, `≡_𝔠` is observational
indistinguishability, ⛔ **nothing in the corpus connects them.**

⭐⭐ **The countermodel exists and is witnessed** — `P-08` §17's A/B pair: two histories, one retained
state, a required distinction. ⭐ It shows R carries information not recoverable from current state.
⛔ **It does not show `Tr` applies** — `o_t` and `i_t` remain absent.

⭐⭐⭐ **Granularity — `[OPEN]`, and now sharper than "unresolved":** the question is no longer *how big is
a capability* but ⛔ **whether the kernel's units are capabilities at all.** Fifth line to stop here, and
the first to reach a **typing** answer rather than a sizing one.

# 9. Three objects kept apart *(§9)*

$$\textbf{obligation } (K_i) \neq \textbf{capability } (c:\mathcal X\to\mathcal X) \neq \textbf{trace } (\operatorname{Tr}_K(s,n)); \qquad \tau \neq \textbf{trace} \neq \textbf{history}$$

⭐ **No equivalence asserted; the corpus establishes none and this audit invents none.**

# 10. Status of `⪯_cap`, `≡_sem`, `MinKer` *(§10, §11)*

| | status |
|---|---|
| **`⪯_cap`** | ⭐⭐ **formally defined in three places, ⚠️ the ledger calls it FORMALIZATION OPEN, and its observable input has two versions** ⇒ **defined · under-specified · not computable** — ⛔ **and not instantiable for R** |
| **`≡_sem` vs `≡_cap`** | ⭐⭐⭐ **`014642` and the ledger GROUND `≡_sem` in mutual `⪯_cap`; `020642` says `≡_sem ≠ ≡_cap` in general and flags the bridge as a CENTRAL LEMMA to be *"proved or explicitly rejected, not assumed."*** ⚠️ **Both readings live in the corpus** |
| **`MinKer` for R** | ⛔ **not applicable** — `K_adm` membership needs the five-tuple form and `𝒳 → 𝒳` capabilities |

# 11. DDD audit *(§13)*

⭐ `Trace` — a **mathematical construct**. `Capability` — ⭐⭐ **now typed** (`𝒳 → 𝒳`), and the R cells fail
the type. `Transition` — ⭐⭐ **the only one with domain standing in R**: `τ1`…`τ11` are `[EMP]`-witnessed
occurrences with before/after and an adjudicated loss severity. ⛔ **No bounded context, value object or
implementation pattern manufactured.**

# 12. ⭐⭐ Attack on `P-58` — **and on this audit's own first draft**

| claim | verdict |
|---|---|
| 1 · `⪯_cap` is sufficiently defined | ⚠️ **`[QUALIFIED]`** — formally defined, ⛔ **but the v1.2 ledger says `FORMALIZATION OPEN` and the observable set has two versions.** ⭐ **My first draft said `[CONFIRMED] and strengthened` — too strong** |
| 2 · `Trace(K,h)` is the only remaining hole | ⭐⭐⭐ **`[REFUTED]`** — `Trace` is defined; the holes are `𝒪`, `Obs_𝔠`, `𝔠`, `K_adm` |
| 3 · constructing `Trace` suffices for computability | ⭐⭐ **`[REFUTED]`** — the commissioner's correction, evidenced |
| 4 · the eleven cells can be treated as capabilities | ⭐⭐⭐ **`[REFUTED]`, not `[OPEN]`** — `Definition 3` types a capability as `𝒳 → 𝒳`. ⭐ **My first draft said `[OPEN]`; the type answers it** |
| 5 · the five-step programme is executable for R | ⭐⭐⭐ **`[REFUTED]`** — R supplies **1 of 5** components |
| 6 · the persistence kernel is a valid `MinKer` candidate | ⭐⭐⭐ **`[REFUTED]`** |
| ⭐ **7 · (my first draft) *"`T3` — nothing"*** | ⭐⭐⭐ **`[REFUTED]` by the commissioner's own pointer** — `014642` §4 is an **executable verification testbed specification** in Python |

⭐⭐⭐ **And one unprompted, against `P-18`/`P-47`:** the corpus separates **experimental** irreducibility
($\exists s \in S_{tested}$) from **mathematical** ($\forall K' \in \mathfrak K_{adm}$) and states
*"your experiments establish the former **unless the admissible implementation space is formally
characterized**."*

$$\boxed{\textbf{⚠️ Lane T's cell-independence arguments are } \mathbf{EXPERIMENTAL\ irreducibility.} \textbf{ ⛔ Consistent with } \mathbf{[REC]} \textbf{ — the stronger reading was never earned.}}$$

# 13. Backlog — ⛔ **nothing, and the reason matters**

⭐ Three candidates tested, all fail the filing test. ① The competing `≡_sem`/`≡_cap` formulations —
⛔ **the corpus flags the bridge itself as an open lemma; filing would invent a management problem the
estate has already managed.** ② The two observable sets and the ledger/formalization tension — ⛔ **a
90-minute working session is not a defect.** ③ *"Formal proof material filed under `brainstorming/`"* —
⛔ **the same cause as `EKS-16`/`EKS-17`.** ⭐⭐ **Six tickets in six audits would split one cause into six
records, which `ES-005.4` forbids. The pattern is recorded; the marginal ticket is noise.**

# 14. Status register
**`[EMP]`** ⭐⭐⭐ `Definition 3` — a capability is `𝒳 → 𝒳` · the five-tuple kernel implementation ·
`Tr_K(s,n)` · **two** observable sets (6-vector, 8-tuple; the latter *"not yet a theorem"*) · `≡_sem` via
`Obs` · the `Φ`-simulation repair and its `K_A`/`K_B` counterexample · **Lemma 1 and Theorem 1, both
proved `∎`** · the **executable testbed specification** · the v1.2 ledger's six OPEN/NOT-PROVED rows ·
the experimental-vs-mathematical irreducibility split · `K_adm` circularity · `P-08`/`P-09.3`:
`state space`/`interface`/`scenario`/`horizon`/`trace` = **0**, `observ*` ×12 all empirical.
**`[DERIVED]`** ⭐⭐⭐ **the eleven cells are not capabilities in the corpus's sense — type mismatch** ·
`Trace` is not the bottleneck · the chain bottoms out at `𝒪`/`Obs_𝔠`/`𝔠`/`K_adm` · R supplies **1 of 5** ·
construction test ⇒ **`F`** · **Theorem 1 covers proper subsets only** · **Lemma 1 carries an
undischarged universal.**
**`[CORROBORATION]`** ⭐⭐ the `K_A`/`K_B` counterexample ↔ `P-08` §17 · `P-53` on `C_KOS` ·
⛔ **one corpus, no replication.**
**`[QUALIFIED]`** ⚠️ `⪯_cap`'s sufficiency · `P-18`/`P-47` independence — **experimental, not
mathematical.**
**`[REFUTED]`** ⭐⭐⭐ `P-58` claims 2, 3, 4, 5, 6 · **my own first-draft claims that `T3` does not exist
and that cell-as-capability is `[OPEN]`.**
**`[OPEN]`** ⭐⭐ `𝒪` · `Obs_𝔠` · `𝔠 = C_KOS` · `K_adm` · the `≡_cap ⟺ ≡_sem` bridge · the
different-decomposition gap in Theorem 1 · `B2` · `τ4`/`Q3` · all carried opens · ⛔ **well-foundedness /
terminality / acyclicity — DEFERRED.**
**Governance:** `|K| = 11` **`[REC]`** — unchanged, unfrozen, ⛔ **and not a `MinKer` candidate.**

### 15. ONE NEXT UNRESOLVED QUESTION

$$\boxed{\textbf{Is } \mathbf{Persist(x,\tau)} \textbf{ EXPRESSIBLE as a capability } c:\mathcal X\to\mathcal X \textbf{ over a witnessed } \mathcal X \textbf{ — or is a retention obligation a } \mathbf{categorically\ different\ object}?}$$

⭐⭐⭐ **This is the single question the whole minimality track now reduces to, and it is answerable from
the two definitions alone.** ⭐ If a witnessed `𝒳` exists over which retention can be written as a state
mapping — plausibly *"the estate's register state"* — then R may enter `K_adm`, and `Lemma 1`,
`Theorem 1` and the testbed become **available to the eleven cells**, which would be the first genuine
route from this lane's work into the estate's formal programme. ⛔ **If a retention obligation is
categorically not a state mapping, then the eleven cells can never be a `MinKer` candidate, no amount of
work on `𝒪` or `𝔠` changes that, and `|K| = 11` is permanently a lower bound under one construction —
a real result, but a different kind of result than minimality.**
⚠️ **And `𝒳` must be found in the witnessed record, ⛔ never designed** — designing it would manufacture
the membership the question is testing.

```
C — THE TRACE AND ORDERING APPARATUS EXISTS AND IS NEARLY COMPLETE, BUT NOT FOR THE R PERSISTENCE
KERNEL — AND THE REASON IS A TYPE MISMATCH, NOT A GAP.

Definition 3 of the 01:46 document types a capability as a mapping c : X -> X. The eleven cells are
retention obligations on transitions — "x survives tau" — a predicate over (item, transition, lost
datum) triples. An obligation that something is retained is not a mapping from states to states, and
the corpus never bridges the two. THE ELEVEN CELLS ARE NOT CAPABILITIES IN THE CORPUS'S SENSE.

P-58's framing was wrong twice over. Trace is not the bottleneck — it is formally defined as
Tr_K(s,n) = (o_t; i_t; delta_t). And the bottleneck is not a missing definition at all: the R kernel is
the wrong kind of object for machinery that already exists.

WHAT THE CORPUS HAS, across six documents and roughly 8,850 lines produced in one 90-minute window:
the semantic environment, the kernel implementation as a five-tuple, the capability type, the semantic
trace, observational equivalence, capability ordering repaired from Beh-inclusion to Phi-simulation
after the corpus refuted its own earlier version with a counterexample — AND Lemma 1 and Theorem 1,
both proved, AND an executable verification testbed specification in Python. My own first draft said
"T3 — nothing"; that is REFUTED by the commissioner's pointer.

THE PROOF'S TWO OWN LIMITS, reported rather than softened. Theorem 1 quantifies over PROPER SUBSETS of
C_irr only — it does not exclude a different decomposition realizing the same powers, and the corpus
catches this itself eighty-five minutes later. And Lemma 1's condition 2 quantifies over every
arbitrary composite over C_K minus c*, an undischarged universal not decidable without characterizing
K_adm — which the corpus's own later review calls "a subtle but important circularity".

THE CHAIN BOTTOMS OUT BELOW TRACE, at four terms: O (two incompatible versions, a 6-vector and an
8-tuple, the latter marked "a candidate list, not yet a theorem"), Obs_C (never enumerated), C_KOS
(undefined — P-53 confirmed), and K_adm (circular). The v1.2 frozen ledger independently agrees on the
direction: semantic equivalence FORMALIZATION OPEN, capability ordering FORMALIZATION OPEN, existence
and uniqueness and named operator set NOT YET PROVED, governance ratification OPEN. A ledger saying
FORMALIZATION OPEN at 01:48 and three formalizations at 01:43-02:06 is not a contradiction — it is a
working session. But nothing marks which formulation supersedes which, and that is a fact about the
record, not the mathematics.

THE COMPONENT COUNT: R supplies 1 of 5. delta_K is supplied and is the kernel's strongest asset —
tau_1 to tau_11, every one witnessed. C_K fails on type. X_K, I_K, O_K, scenario and horizon are all
absent, at zero occurrences. A near-miss not counted: P-08 uses "observ*" twelve times and every one is
an empirical re-measurement of the corpus, not an observable interface.

Granularity is now sharper than "unresolved": the question is no longer how big a capability is, but
whether the kernel's units are capabilities at all. Fifth line to stop here, and the first to reach a
typing answer rather than a sizing one.

DISAGREEMENT WITH THE COMMISSION'S PREMISE: Trace(K,h) is not the corpus's object. It appears at 01:58
as a demand with h never defined; eight minutes later the corpus answers with scenario s and finite
horizon n. Auditing h would have audited a ghost.

ATTACK: "<=cap sufficiently defined" QUALIFIED (my first draft said CONFIRMED — too strong). "Trace is
the only hole" REFUTED. "Constructing Trace suffices" REFUTED. "The eleven cells are capabilities"
REFUTED, not OPEN — the type answers it. "The five-step programme is executable for R" REFUTED. "The
kernel is a valid MinKer candidate" REFUTED. And unprompted, against P-18/P-47: the corpus separates
experimental from mathematical irreducibility and says experiments establish only the former unless the
admissible space is formally characterized — SO LANE T'S CELL-INDEPENDENCE ARGUMENTS ARE EXPERIMENTAL
IRREDUCIBILITY. Consistent with [REC]; the stronger reading was never earned.

NO BACKLOG ITEM. Three candidates tested and all fail: the competing formulations are already flagged
by the corpus as an open lemma; a 90-minute working session is not a defect; and "formal material filed
under brainstorming" is the same cause as EKS-16 and EKS-17. Six tickets in six audits would split one
cause into six records, which ES-005.4 forbids.

Tenth miss — the first found inside the commissioned search, but two of the six primaries still had to
be handed to me mid-audit. Pre-registered C. Correct — record 16 of 24, with three first-draft
sub-claims corrected.

|K| = 11 UNCHANGED, [REC], UNFROZEN, AND NOT A MinKer CANDIDATE. No Trace manufactured, no MinKer, no
minimality claim, no cell retyped.

ONE NEXT UNRESOLVED QUESTION: Is Persist(x,tau) EXPRESSIBLE as a capability c : X -> X over a witnessed
  X — or is a retention obligation a categorically different object? If a witnessed X exists over which
  retention can be written as a state mapping, R may enter K_adm and Lemma 1, Theorem 1 and the testbed
  become available to the eleven cells — the first genuine route from this lane into the estate's formal
  programme. If not, the eleven cells can never be a MinKer candidate, no work on O or C_KOS changes it,
  and |K| = 11 is permanently a lower bound under one construction: a real result, but a different kind
  of result than minimality. X must be FOUND in the witnessed record, never designed.

NO three_model_convergence INSPECTION — excluded by path from every command, unread.
```
