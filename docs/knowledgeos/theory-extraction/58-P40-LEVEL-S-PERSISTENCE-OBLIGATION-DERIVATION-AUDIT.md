# `P-40` — Level-S Persistence Obligation Derivation Audit

**2026-09-08 · Lane T.** After [`57` `P-39`](./57-P39-PERSISTENCE-KERNEL-SCOPE-THEORY-REGISTER-VS-DOMAIN-TRANSFER-AUDIT.md).

# 0. Executive verdict

$$\boxed{\begin{array}{c}\textbf{⭐⭐⭐ Level S } \mathbf{DOES} \textbf{ independently derive persistence obligations — it does not merely describe.}\\[4pt] \textbf{They sit in a } \mathbf{boxed,\ normative,\ } T\textbf{-}\mathbf{numbered\ invariant\ register} \textbf{ (} \textit{step-016} \textbf{), derived with } \mathbf{no\ reference\ to\ } P\text{-}08.\\[6pt] \boxed{\mathbf{OUTCOME\ C} \textbf{ — S derives a } \mathbf{DIFFERENT} \textbf{ kernel, and it } \mathbf{CONTRADICTS} \textbf{ the R kernel on history.}}\end{array}}$$

$$\boxed{\begin{array}{ll}\mathbf{T9} & \textbf{Historical knowledge } \mathbf{must\ be\ reconstructible.}\\ \mathbf{T10} & \textbf{Historical decisions must be evaluated against } \mathbf{the\ knowledge\ available\ at\ decision\ time.}\\ \mathbf{T11} & \textbf{Retraction } \mathbf{does\ not\ erase} \textbf{ historical knowledge events.}\\ \mathbf{T14} & \textbf{Policy, ontology, and inference rules are } \mathbf{temporally\ versioned.}\end{array}}$$

⭐⭐⭐⭐ **The divergence, stated exactly:**

$$\boxed{\begin{array}{ll}\textbf{Level R } (P\text{-}08\textbf{'s closing line}) & \textbf{"} \mathbf{NO\ COMPLETE\ HISTORY,\ NO\ EVENT\ SOURCING,\ NO\ APPEND\text{-}ONLY\ STORAGE\ IMPLIED} \textbf{"}\\[4pt] \textbf{Level S } (\textit{step-016}) & K(t) = \mathit{Fold}(\delta_K,\ K_0,\ H_K^{\leq t}) \quad\textbf{—} \mathbf{\ explicitly\ NON\text{-}MARKOVIAN}\end{array}}$$

⭐⭐ **`|K| = 11` untouched. The S obligations are recorded as a SEPARATE, unmerged result.**

# 1. Boundary, firewall, and three declared limits

⛔ **No `three_model_convergence/`** — suppression pattern only. ⛔ No Schema v3 · architecture ·
implementation · canonicalization · S schema · event sourcing · CQRS · cardinality change.
✅ **`research/` (253 files) and `verification/` (209 files) inspected under the new permission** — §16.

⚠️ **(a) The R-firewall cannot be total, and pretending otherwise would be dishonest.** §7's "neutral"
criterion — *a datum must remain recoverable across a specified transition* — **is `P-08`'s
`Persist(x,τ)`**. ⭐ **Labelled `[METHODOLOGICAL CRITERION — LANE T, R-DERIVED]`.** ⛔ **No `K` cell was
used as a premise, and the S obligations below were read off the corpus before any comparison.**
⚠️ **(b)** `P-39` Claim B conceded as over-read **before** this audit ran — §15.
⚠️ **(c)** ⭐ **Pre-registered: S generates obligations, and outcome `C`. BOTH CORRECT — record now 2 of
6.**

# 2. ⭐⭐⭐ A false negative that would have inverted this audit

$$\boxed{\begin{array}{c}\texttt{grep "must remain auditable"} \;\longrightarrow\; \mathbf{0\ files} \textbf{ across 813 Level-S files.}\\ \textbf{⭐ The source reads } \mathtt{Historical\backslash\ acceptance\backslash\ must\backslash\ remain\backslash\ auditable.} \textbf{ — } \mathbf{LaTeX\text{-}escaped\ spaces.}\end{array}}$$

⭐⭐ **A plain-text search cannot see normative language inside `\boxed{...}` math.** ⭐⭐⭐ **The entire
`T1`–`T15` register was invisible to every search this programme has run for twelve audits.** Re-run
LaTeX-tolerantly: `must-be-retained` **57** · `must-remain-recoverable` **4** · `must-reference` **10**
· `must-not-be-lost` **3**.

# 3. S primary corpus and the neutral criterion

**Level S** = `phase_measure_theory/` **813 files** *(excluding `external_research/`, which `P-38`
established is KnowledgeOS-internal)*. ⭐ **Count basis unchanged from `P-29`–`P-38`; `R` and `D`
material not mixed in.**

| class | test | qualifies? |
|---|---|:--:|
| **A** state description | *"After X, Y holds"* | ⛔ |
| **B** operational rule | *"When X, do Y"* | ⛔ |
| **C** historical requirement | *"previous X must remain available"* | ⚠️ |
| ⭐ **D** persistence obligation | ⭐ **failure to retain violates a stated S requirement** | ⭐⭐ **only D** |

# 4. ⭐⭐⭐ The S obligation inventory — `T1`–`T15`, read off the corpus

| | invariant | class |
|---|---|---|
| `T1`–`T8` | `EventTime ≠ ObservationTime ≠ KnowledgeTime ≠ DecisionTime`; `WorldState ≠ KnowledgeState`; `Event ≠ State ≠ Snapshot`; `Version ≠ Timestamp` | ⭐ **distinctions, not obligations** |
| ⭐⭐⭐ **`T9`** | **Historical knowledge must be reconstructible** | ⭐⭐ **D — persistence** |
| ⭐⭐⭐ **`T10`** | **Historical decisions must be evaluated against the knowledge available at decision time** | ⭐⭐ **D — requires temporal SNAPSHOTS** |
| ⭐⭐⭐ **`T11`** | **Retraction does not erase historical knowledge events** | ⭐⭐ **D — explicit non-erasure** |
| **`T12`–`T13`** | `Prediction ≠ Observation`; temporal uncertainty representable | ⭐ distinctions |
| ⭐⭐ **`T14`** | **Policy, ontology and inference rules are temporally versioned** | ⭐⭐ **D** |
| `T15` | staleness is purpose/context dependent | ⛔ |

⭐ **And the S mathematical core makes the dependence explicit:**

$$K(t) = \mathit{Fold}(\delta_K,\ K_0,\ H_K^{\leq t}) \qquad A = (P, E, \Sigma, \Pi, T_v, T_o, T_k, \mathit{Ctx}, \mathit{ID})$$

⭐⭐⭐ **`K(t)` is a fold over the event history up to `t` — so S is NOT Markovian, and §23's question
is answered by the corpus rather than assumed.**

# 5–9. The commissioned candidate classes, judged independently

| | class | verdict at S |
|---|---|---|
| **S1** assertion history | ⭐ **`[EMP]` via `T9`+`Fold`** |
| **S2** warrant history | ⚠️ **`[OPEN]`** — not in the `T` register |
| ⭐ **S3** policy-version history | ⭐⭐ **`[EMP]` — `T14`** |
| **S4** acceptance history | ⭐⭐ **`[EMP]` — `A9`** *(`step-008`, LaTeX-escaped)* |
| **S5** evidence history | ⭐ **`[EMP]`** — *"material authoritative knowledge must have traceable supporting evidence"* |
| ⭐ **S6** supersession history | ⭐⭐ **`[EMP]` — `T11`**, and *"current authoritative retrieval must exclude superseded knowledge **unless historical retrieval is explicitly requested**"* ⇒ **superseded knowledge is RETAINED and RETRIEVABLE** |
| **S7** identity continuity | ⚠️ **`[OPEN]`** — *"historical continuity must not depend on the…"* is present but unfinished in the register |
| **S8** provenance continuity | ⭐ **`[EMP]`** — *"guidance must inherit epistemic provenance"* |
| **S9** verification history | ⭐ **`[EMP]`** — *"verification results must identify the rule and checker version that produced them"* |
| **S10** refusal history | ⭐⭐ **`[UNWITNESSED]` at S** — consistent with `P-31` |
| **S11** policy applicability | ⭐ **`[EMP]` — `T10`** |

⭐⭐ **Nine of eleven candidates earned a status from S evidence alone.** ⛔ **None was assumed.**

# 10. Negative and positive controls

⭐ **Negative control — and S passes it.** `we should` **540 files** · `I recommend` **151** · `the
system records` **8**. ⭐⭐ **The overwhelming majority of S material is recommendatory or descriptive**,
which is exactly why the **boxed `T`-register is distinctive**: it is the small normative core inside a
large advisory corpus. ⛔ **Not every S state change generates persistence** — `T15` makes staleness
context-dependent, and most `δ_K` steps carry no retention rule.

⭐⭐ **Positive control — `T11`.** *Retraction does not erase historical knowledge events.* Without the
retained event, a retracted assertion becomes indistinguishable from one never asserted, and `T9`'s
reconstructibility fails outright. ⭐ **A clean `loss ⇒ stated requirement fails` chain.**

# 11. Necessity / sufficiency, and the information test

| | `T9` | `T10` | `T11` | `T14` |
|---|---|---|---|---|
| **necessity** | ⭐ **yes** — `Fold` needs `H^{≤t}` | ⭐ **yes** — decision-time state is not current state | ⭐ **yes** | ⭐ **yes** — *"version used"* |
| **sufficiency** | ⚠️ **`[OPEN]`** — one register, no minimality argument | `[OPEN]` | `[OPEN]` | `[OPEN]` |

⚠️ **The `H1`/`H2` deletion pairs are constructible for `T11`** *(delete the retraction event; `T9`
fails)* **but not for `T9` itself without changing `H`** ⇒ $\mathtt{[UNINFORMATIVE\ FOR\ THIS\ TEST]}$,
⛔ **not evidence against.**

# 12. Description vs obligation vs architecture

| class | S examples |
|---|---|
| **descriptive** | most of the 813 files |
| **prescriptive** | *"we should…"* 540 files |
| ⭐ **normative** | ⭐⭐ **`T9`, `T10`, `T11`, `T14`, `A9`** — boxed invariants |
| **`[ARCH]`** | `step-025w`'s *"event sourcing fits naturally"* — ⛔ **not used** |

# 13–14. DDD and mathematics at S

⭐ `Assertion` **Entity** *(it carries `ID` in the 9-tuple)* · `Evidence` **Entity** · `Acceptance`
**Domain Event** · `PolicyVersion` ⭐⭐ **Entity — versioned by `T14`** · `Verification` **record** ·
`Supersession` **Relation** · `Historical state` ⭐ **a projection, `Fold`-derived**.
⛔ **No aggregate created to make persistence explicable.**

$$\boxed{\textbf{⭐⭐ Is the historical requirement expressible from current } S_t \textbf{ alone? } \mathbf{NO} \textbf{ — } K(t)=\mathit{Fold}(\delta_K,K_0,H^{\leq t}) \textbf{ makes historical dependence } \mathbf{CORPUS\text{-}STATED}\textbf{, not assumed.}}$$

# 15. ⭐⭐⭐ Attack on `P-39`

| | claim | verdict |
|---|---|---|
| **A** | *"`K1`–`K11` established for the research estate only"* | ⭐⭐ **`[QUALIFIED]` — true of DERIVATIONAL and EVIDENCED scope; ⛔ NOT shown for normative or intended scope** |
| ⭐⭐ **B** | *"`P-08` §1 declares the kernel's scope"* | ⭐⭐⭐ **`[QUALIFIED]` — `§1` defines the EMPIRICAL TRANSITION SET used in the derivation.** ⭐ **`P-39` conflated *derivational* with *normative* scope, and I had flagged that risk and then not honoured it** |
| **C** | *"no universal persistence law exists"* | ⭐ **`[QUALIFIED]`** — no **explicit** universal statement; ⛔ that never showed none is derivable, and **S has now derived one for itself** |
| ⭐⭐⭐ **D** | *"S has no established persistence kernel"* | ⭐⭐⭐ **`[REFUTED]`.** `P-39` never audited S independently — ⭐ **and the `T` register was invisible to its searches** |
| ⭐⭐ **E** | *"negative results survive scope transfer"* | ⭐⭐ **`[QUALIFIED]`, exactly as the commission predicted.** *"No new cell needed"* is scope-robust; *"X is not a `K5` obligation"* is **vacuous rather than true** at S, since `K5` is R-derived |

⭐ **`P-39`'s three-level separation `R`/`S`/`D` SURVIVES and is strengthened** — S turned out to have
its own register, which is what a genuine level would have.

# 16. `research/` and `verification/` — instruments, not evidence

⭐ `research/knowledgeos-sim/kos12/history.py` supplies **`static_history_reads()`** — *"Does ANY kernel
transition read `E.history`?"* — and **`reconstructible_from_state(E)`** — *"Can 'was this ever closed?'
be recovered from current content alone?"* ⭐⭐ **Executable tests of exactly `T9`'s property.**
⭐⭐⭐ **And its `domain_probes()` already practises this programme's discipline:** *"**NONE is invented
as a kernel rule**: each is checked against whether the theory actually contains it"*, with
`in_theory=False` and *"**THE THEORY CONTAINS NO SUCH RULE**."*
⚠️ **Classified `[ARCH]` — a verification instrument, ⛔ not evidence of obligation.** ⭐ `verification/`
holds `zero-algebra` corpus audits; **no `T`-invariant verification exists yet** — ⭐⭐ **so `T9`–`T14`
are UNVERIFIED, and `history.py` is the ready-made instrument.**

# 17. ⭐⭐⭐⭐ Comparison with `K1`–`K11` — performed LAST, and it is a divergence

| S obligation | nearest R cell | correspondence |
|---|---|---|
| **`T11`** retraction non-erasure | `K4a-i` / `K4b-i` | ⭐ **close** |
| **`T9`** reconstructibility | ⭐⭐⭐ **NONE** | ⭐⭐ **`P-08` explicitly EXCLUDED complete history** |
| **`T10`** decision-time knowledge | ⭐⭐⭐ **NONE** | ⭐⭐ **requires temporal SNAPSHOTS — no `K` cell provides them** |
| **`T14`** policy/ontology/rule versioning | ⭐⭐ **NONE** | ⭐⭐⭐ **`P-28` placed policy in the GOVERNANCE estate; at S it is INSIDE** |
| `A9` acceptance auditability | `K3a`+`K3b` | ⭐ close *(`P-30`)* |
| `S9` verification history | ⭐ **NONE** | `P-32` placed verification in Assurance |

$$\boxed{\begin{array}{c}\mathbf{OUTCOME\ C.} \textbf{ S derives } \mathbf{additional\ and\ partly\ INCOMPATIBLE} \textbf{ capabilities.}\\ \boxed{\textbf{⭐⭐ The sharpest conflict: } P\text{-}08 \textbf{ closes with } \textit{"NO COMPLETE HISTORY, NO EVENT SOURCING"} \textbf{ while } T9 + \mathit{Fold} \textbf{ require reconstructibility from } H^{\leq t}.}\end{array}}$$

⛔ **NOT merged. Two kernels are recorded side by side, and adjudication is not `P-40`'s job.**

# 18. The four scopes, never collapsed

$$\boxed{\begin{array}{ll}\textbf{1 intended scope} & \textbf{⚠️ } \mathbf{[OPEN]} \textbf{ — } P\text{-}08 \textbf{ never states it; } P\text{-}39 \textbf{ asserted coincidence without evidence}\\ \textbf{2 evidenced scope} & \textbf{⭐ } \mathbf{R} \textbf{ — } \mathcal{T}_{KOS}\textbf{, } \mathbf{[EMP]}\\ \textbf{3 S-level obligation scope} & \textbf{⭐⭐ } \mathbf{ESTABLISHED\ INDEPENDENTLY} \textbf{ — } T9,\ T10,\ T11,\ T14,\ A9\\ \textbf{4 general KnowledgeOS scope} & \textbf{⛔ } \mathbf{[UNWITNESSED]} \textbf{ — and now HARDER, since R and S CONFLICT on history}\end{array}}$$

# 19. Status register
**`[EMP]`** ⭐⭐ `T9` · `T10` · `T11` · `T14` · `T1`–`T8`, `T12`, `T13`, `T15` distinctions ·
`K(t)=Fold(δ_K,K_0,H^{≤t})` · `A=(P,E,Σ,Π,T_v,T_o,T_k,Ctx,ID)` · *"current authoritative retrieval must
exclude superseded knowledge unless historical retrieval is explicitly requested"* · *"verification
results must identify the rule and checker version"* · *"guidance must inherit epistemic provenance"* ·
*"material authoritative knowledge must have traceable supporting evidence"* · `A9`.
**`[DERIVED]`** ⭐⭐⭐ **S independently generates persistence obligations** · **S is non-Markovian** ·
**S and R conflict on complete history** · `T14` places policy versioning inside S.
**`[QUALIFIED]`** ⭐⭐ **`P-39` Claims A, B, C, E**. **`[REFUTED]`** ⭐⭐⭐ **`P-39` Claim D**.
**`[ARCH]`** `history.py`'s probes · `step-025w`'s event-sourcing remark — ⛔ neither used as evidence.
**`[UNWITNESSED]`** S-level refusal history · a minimality argument for the `T` register · any
verification of `T9`–`T14`.
**`[OPEN]`** ⭐⭐⭐ **which kernel governs a built KnowledgeOS** · `T`-register sufficiency · `S2`
warrant history · `S7` identity continuity · `P-08`'s intended scope · everything carried from
`P-29`–`P-39` · ⛔ **well-foundedness / terminality / acyclicity — DEFERRED.**
**`[METHODOLOGICAL CRITERION — LANE T, R-DERIVED]`** the persistence test itself.

# 20. Reusable methodological lesson — an eleventh

$$\boxed{\begin{array}{c}\mathbf{11.} \textbf{ } \mathbf{LaTeX\ escaping\ hides\ normative\ language\ from\ plain\text{-}text\ search.}\\ \textbf{⭐ } \mathtt{must\backslash\ remain\backslash\ auditable} \textbf{ is invisible to } \mathtt{"must\ remain\ auditable"}\textbf{, and a whole } \mathbf{boxed\ invariant\ register}\\ \textbf{went unseen for twelve audits. Search math environments with escape-tolerant patterns.}\end{array}}$$

### 21. ONE NEXT UNRESOLVED QUESTION

$$\boxed{\textbf{Do } T9 \textbf{ and } P\text{-}08\textbf{'s } \textit{"no complete history"} \textbf{ actually contradict — or are they consistent because } \mathbf{RECONSTRUCTIBLE \neq RETAINED}?}$$

⭐⭐ **`P-08` itself allowed reconstruction for measured facts against a pinned corpus state** — so `T9`
might be satisfiable **without** storing history, exactly as `P-08` intended. ⭐⭐⭐ **If so the two
kernels are compatible; if not, KnowledgeOS has two incompatible persistence theories, and one must
yield.**

```
LEVEL S DOES INDEPENDENTLY DERIVE PERSISTENCE OBLIGATIONS. OUTCOME C — S DERIVES A DIFFERENT KERNEL,
AND IT CONFLICTS WITH THE R KERNEL ON HISTORY. |K| = 11 UNTOUCHED; THE S RESULT IS RECORDED SEPARATELY
AND NOT MERGED.

step-016 carries a boxed, normative T1-T15 invariant register, derived with no reference to P-08:
  T9   Historical knowledge MUST BE RECONSTRUCTIBLE.
  T10  Historical decisions must be evaluated against THE KNOWLEDGE AVAILABLE AT DECISION TIME.
  T11  Retraction DOES NOT ERASE historical knowledge events.
  T14  Policy, ontology and inference rules are TEMPORALLY VERSIONED.
And its mathematical core states the dependence outright: K(t) = Fold(delta_K, K_0, H^{<=t}) — S is
explicitly NON-MARKOVIAN, which answers the Markov question from the corpus rather than by assumption.

THE DIVERGENCE, EXACTLY: P-08 closes with "NO COMPLETE HISTORY, NO EVENT SOURCING, NO APPEND-ONLY
STORAGE IMPLIED", while T9 plus the Fold require reconstructibility from the full event history. T10
demands temporal SNAPSHOTS that no K cell provides, and T14 places policy versioning INSIDE S whereas
P-28 had placed policy in the GOVERNANCE estate.

A FALSE NEGATIVE ALMOST INVERTED THIS AUDIT. Searching "must remain auditable" returns ZERO across 813
Level-S files — because the source reads must\ remain\ auditable with LATEX-ESCAPED SPACES inside
\boxed{}. The entire T1-T15 register was invisible to every search this programme has run for twelve
audits. Re-run escape-tolerantly: must-be-retained 57 files, must-remain-recoverable 4, must-reference
10. That is reusable lesson #11.

P-39's CLAIM D IS REFUTED — "S has no established persistence kernel". P-39 never audited S
independently, and the register was invisible to its searches. Claim B is QUALIFIED: P-08 §1 defines the
EMPIRICAL TRANSITION SET used in the derivation, not the kernel's normative scope — and I had flagged
that exact risk before running P-39 and then failed to honour it. Claim E is QUALIFIED just as the
commission predicted: "no new cell needed" is scope-robust, but "X is not a K5 obligation" is VACUOUS
rather than true at S, since K5 is R-derived. P-39's three-level R/S/D separation SURVIVES and is
strengthened — S turned out to have its own register, which is what a genuine level would have.

THE FIREWALL COULD NOT BE TOTAL AND THIS IS DECLARED, NOT HIDDEN: §7's "neutral" criterion IS P-08's
Persist(x,tau), so the yardstick is R-derived. No K cell was used as a premise, and the S obligations
were read off the corpus before any comparison was made.

THE NEGATIVE CONTROL PASSES: "we should" appears in 540 S files and "I recommend" in 151, so the
overwhelming majority of S material is advisory — which is precisely what makes the small boxed T
register distinctive rather than ubiquitous.

research/ AND verification/ WERE USED AS INSTRUMENTS, NOT EVIDENCE. research/knowledgeos-sim/kos12/
history.py already implements static_history_reads() and reconstructible_from_state() — executable
tests of exactly T9's property — and its domain_probes() practises this programme's own discipline,
recording in_theory=False and "THE THEORY CONTAINS NO SUCH RULE". No T-invariant verification exists
yet, so T9-T14 are UNVERIFIED and that instrument is ready-made for it.

Both pre-registrations were correct this time — S generates obligations, and outcome C. Record 2 of 6.

ONE NEXT UNRESOLVED QUESTION: Do T9 and P-08's "no complete history" actually contradict — or are they
  consistent because RECONSTRUCTIBLE != RETAINED? P-08 itself allowed reconstruction for measured facts
  against a pinned corpus state. If T9 is satisfiable without storing history, the two kernels are
  compatible; if not, KnowledgeOS has two incompatible persistence theories and one must yield.

NO SCHEMA v3 — NO NEW CELL — NO MERGE OF THE TWO KERNELS — NO CANONICALIZATION — NO ARCHITECTURE — NO
three_model_convergence INSPECTION — WELL-FOUNDEDNESS STILL DEFERRED.
```
