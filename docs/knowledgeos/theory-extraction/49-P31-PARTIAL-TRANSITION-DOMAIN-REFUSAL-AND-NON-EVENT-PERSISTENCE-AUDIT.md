# `P-31` — Partial Transition Domain, Refusal & Non-Event Persistence Audit

**2026-09-08 · Lane T.** After [`48` `P-30`](./48-P30-ACCEPTANCE-STATE-PREDICATE-AND-TRANSITION-SEMANTICS-AUDIT.md).
Executes the **REVISED** commission *(domain semantics BEFORE persistence)*.

> ⛔ **`P-08`, `P-18`–`P-30`, the 11-cell kernel, Schema v2, 3MC, Lane M FROZEN · no Schema v3 · no
> `K12` · no Refusal/Attempt/Rejection aggregate · no architecture · no implementation · `P-30`'s
> acceptance verdict NOT reopened · policy and policy-version persistence NOT reopened ·
> provenance-chain questions NOT reopened · well-foundedness / terminality / acyclicity DEFERRED ·
> runtime semantics are NEVER inferred from notation.**

**Three commission deviations, declared before execution and unchanged by the findings:** §16 scoped to
**KnowledgeOS** implementation artifacts *(`app/` is the unrelated PublicDigit voting platform;
searching it would manufacture contamination)* · §19's option `A` **reported as two separable
components** *(it turned out to be half-true, and the split mattered)* · §17's falsification run
**before** every stop, never skipped by one.

# 0. ⭐⭐⭐⭐ The corpus answers §1 in its own words — and the answer is **not** notation

$$\boxed{\begin{array}{ll}\mathbf{[EMP]}\ \textit{question-15}\text{:1952} & \textbf{"The arrow } \rightharpoonup \textbf{ is IMPORTANT because } \mathbf{not\ every\ event\ is\ valid\ in\ every\ state.}\textbf{"}\\[4pt] \mathbf{[EMP]}\ \textit{question-15}\text{:2433} & \textbf{"The } \rightharpoonup \textbf{ indicates a partial function: not all events are valid in all states."}\end{array}}$$

⭐⭐ **Deliberate — the corpus says *"is important because"* — and its meaning is `M3` VALIDITY, not
`M4` refusal.** Its own example is **structural**: `AddEvidence(A,E)` is invalid if `A \notin K_t`.

⭐⭐⭐ **And the decisive estate ruling, which settles the persistence question outright:**

$$\boxed{\begin{array}{c}\mathbf{[EMP]}\ \textit{kernel/…god-object-conflations}\text{:1350–1360}\\[4pt] \textbf{"} \mathbf{Non\text{-}admitted\ candidates\ are\ NOT\ KnowledgeOS\ domain\ state.} \textbf{ They are either:}\\ \textbf{• recorded in History as part of an } \mathbf{admitted} \textbf{ transition, or}\\ \textbf{• } \mathbf{retained\ mechanism\text{-}side/infrastructure\text{-}side.}\\ \textbf{There is } \mathbf{no\ hidden\ semantic\ aggregate.} \textbf{ That is a very strong } \mathbf{anti\text{-}"God\ Kernel"\ constraint.}\textbf{"}\end{array}}$$

⭐⭐⭐ **The corpus states the anti-inflation rule the commission was written to enforce, and applies it
to exactly this case.**

---

# 1. Partiality semantics — **`M3`**, with `M1` as its formal expression

| | reading | verdict |
|---|---|---|
| **`M1`** mathematical partiality / domain restriction | ⭐ **the FORM** — `(s,e,p) \notin \mathrm{Dom}(\omega)` |
| ⭐⭐ **`M3`** **inadmissible / not valid in this state** | ⭐⭐⭐ **`[EMP]` — the corpus's OWN gloss, stated twice** |
| **`M2`** impossible | ⭐ **subsumed** — the example `A \notin K_t` is structural impossibility |
| ⭐ **`M4`** attempted + refused | ⭐⭐⭐ **`[UNWITNESSED]` — the corpus NEVER glosses `⇀` as an attempt** |
| **`M5`** failed | **`[UNWITNESSED]`** |
| **`M6`** unspecified / model gap | ⭐ **`[REFUTED]`** — a gap is not *"important because…"* |
| **`M7`** several | partly — `M1`+`M2`+`M3` |
| **`M8`** undetermined | ⭐ **`[REFUTED]` — the corpus determines it** |

$$\boxed{\omega : \Omega_A \times \mathit{Event} \times \mathit{Policy} \rightharpoonup \Omega_A \quad\textbf{means: } \mathbf{this\ event\ is\ NOT\ VALID\ in\ this\ state.} \textbf{ ⛔ It does NOT mean an attempt occurred.}}$$

⭐ **The commission's correction is confirmed by measurement, not merely conceded:** the slide from
`\notin \mathrm{Dom}` to *"attempted and refused"* has **zero** corpus support.

# 2. Comparative arrow audit — partiality is the corpus DEFAULT

| function | arrow | context | explicit semantics | status |
|---|---|---|---|---|
| **`δ`** | ⭐ **`⇀`** | `𝒦 × ℰ ⇀ 𝒦` *(question-15 ×2, step-007 §11, theory v1.2, GoF review)* | ⭐⭐ **YES — *"not all events are valid in all states"*** | **`[EMP]`** |
| **`T`** | ⭐ **`⇀`** | `𝕂 × Op × Policy × Authority ⇀ 𝕂 × Outcome` — labelled **PARTIAL** | ⭐⭐ **YES + an `Outcome` codomain** | **`[DERIVED]`** |
| **`ω`** | **`⇀`** | `Ω_A × Event × Policy ⇀ Ω_A` *(step-008 §27)* | ⚠️ **none locally** | **`[STIPULATED]`** |
| **`EVal`** | **`⇀`** | `K × 𝒫 × Γ ⇀ ⟨S,B,R,C,P,EvalStatus⟩` ×6 files | none | `[STIPULATED]` |
| **`ε`** | **`⇀`** | `𝒮_Σ × 𝒪 ⇀ 𝒮_Σ` *(question-16)* | none | `[STIPULATED]` |
| **`Add`** | **`⇀`** | `𝕂 × X ⇀ 𝕂` — §232.6 *"`Add_x(𝔎)` is a partial operation"* | ⭐ **YES** | **`[EMP]`** |
| **`Post`** | ⭐ **`→` TOTAL** | `𝒦 × ℰ × 𝒦 → {True,False}` | ⭐ predicates are total | **`[EMP]`** |
| `Policy` | **`→`** | `(K,o) → {true,false}` *(step_269 P1)* | model comparison | `[STIPULATED]` |

$$\boxed{\begin{array}{c}\rightharpoonup \textbf{ appears in } \mathbf{45+52} \textbf{ files across SIX distinct transition functions; } \rightarrow \textbf{ is reserved for PREDICATES.}\\ \boxed{\textbf{⭐⭐ "One partial arrow among total ones" is } \mathbf{REFUTED}\textbf{ — partiality is the corpus's DEFAULT for state transitions,}}\\ \textbf{so } \omega\textbf{'s arrow carries } \mathbf{no\ special\ signal\ whatever.}\end{array}}$$

⭐ **Exactly the inference the commission forbade, and it fails on measurement.** ⚠️ **And `ω`'s own
partiality is doubly weak: `Ω_A` is `[STIPULATED]` (*"I recommend"*) and §27 gives no local gloss —
its meaning is borrowed from `δ`.**

# 3. `R1`–`R6` — and the corpus refuses to model rejection as partiality

| | case | corpus status | event occurred? | attempt? | epistemic effect | governance effect | persistence | cell |
|---|---|---|:--:|:--:|---|---|---|---|
| **`R1`** impossible | ⭐ **`[EMP]`** — `A \notin K_t` | ⛔ no | ⛔ no | **none** | none | ⛔ **none** | — |
| **`R2`** inadmissible | ⭐⭐ **`[EMP]`** — *"not valid in this state"* | ⛔ no | ⚠️ unstated | **none** | ⭐ guard | ⛔ **none epistemic** | — |
| **`R3`** attempted + refused | ⭐⭐⭐ **`[UNWITNESSED]` as a `⇀` reading** | ⚠️ | ⚠️ | ⭐⭐ **NOT domain state** | ⭐ enforcement | ⭐ **infrastructure-side** | ⛔ **none** |
| **`R4`** attempted + failed | **`[UNWITNESSED]`** *(`failed transition` 4 files, none normative)* | — | — | none | — | ⛔ none | — |
| ⭐⭐⭐ **`R5`** executed, `Rejected` outcome | ⭐⭐ **`[DERIVED]`** — `Outcome ∈ {ok, rejected(policy), rejected(authority), rejected(structure)}` | ⭐ **YES** | yes | ⭐ **a real transition** | ⭐⭐ **governance-observable** | ⭐ **History append** | ⭐ **`K3a`+`K3b`** |
| **`R6`** unspecified | ⭐ **`[REFUTED]` for `δ`** *(glossed)*; ⚠️ **live for `ω`** *(no gloss)* | — | — | — | — | none | — |

⭐⭐⭐⭐ **The corpus explicitly REFUSES `R3`-as-partiality and converts it to `R5`:**

> *"**`Outcome` is required in the codomain.** A partial function that merely fails cannot distinguish
> *'rejected by policy'* from *'rejected by authority'* — and both must be recorded in History.
> **Returning `𝕂` alone loses the rejection reason.**"*
> `History_{t+1} = History_t ⌢ ⟨o, policy, authority, actor, wallclock, Outcome⟩`

⚠️ **Provenance checked before use:** `verification/TRANSFORMATION-THEORY.md`, header `mandate:
20260830_1852 §11`, `status: FORMALLY DERIVED`, dated 2026-08-30, checked in with the pre-existing
corpus — **an earlier verification lane's derivation, `[DERIVED]`, NOT `[EMP]` primary, and NOT Lane
T's.** ⭐ **Its own row for this property reads *"governance-observable: YES"*.**

# 4–5. The five things, kept apart

$$\boxed{\begin{array}{lll}\mathit{Attempt} & \textbf{⚠️ } \mathbf{[UNWITNESSED]} \textbf{ as an epistemic object} & \textit{attempted} \textbf{ 60 files — all narrative prose}\\ \mathit{Evaluation} & \textbf{⭐ } \mathbf{[EMP]} & \textbf{guard 194 · precondition 252 · } \mathit{Pre}(K_t,e_t)\\ \mathit{Refusal} & \textbf{⭐⭐ } \mathbf{[EMP]} \textbf{, and } \mathbf{NOT\ domain\ state} & \textbf{"non-admitted candidates"}\\ \mathit{Execution} & \mathbf{[EMP]} & \mathit{Post}(K_t,e_t,K_{t+1})\\ \mathit{Outcome} & \textbf{⭐⭐ } \mathbf{[DERIVED]} & \mathit{rejected}(\mathit{policy}\,|\,\mathit{authority}\,|\,\mathit{structure})\end{array}}$$

⭐⭐ **`Refused(a) \nRightarrow RejectedStatus(a)`, and `Rejected(a)` is a SUCCESSFUL transition** —
grounded, not stipulated: `R5` sits in the **codomain** while `R2` sits **outside the domain**. ⭐
**`P-30`'s `NotAccepted \neq Rejected` and `Unresolved \neq Rejected` are the same distinction one level
up, and none of the three collapse.**

# 6. The non-event question — `H0` vs `H1`

⭐ **§6's gate applies: `R3` is NOT corpus-attested as a `⇀` reading**, so the question is answered
structurally rather than by assumption:

$$\boxed{\textbf{"} \mathbf{Admission\ is\ the\ first\ transition\ of\ the\ epistemic\ lifecycle.} \textbf{"} \quad \mathbf{[EMP]} \textbf{ — 4 kernel files}}$$

⭐⭐⭐ **Before admission there is no epistemic entity.** A refused candidate never became one — so
`H0` *(no attempt)* and `H1` *(attempt refused)* are **epistemically indistinguishable, because in
neither does an epistemic subject exist.** ⭐ **Information may exist; an epistemic persistence
obligation does not.** *(That is exactly the `information exists` ≠ `obligation exists` distinction
§6 demanded.)*

# 7. `H3`/`H4` — ⭐⭐ the pair EXISTS, and it does **not** produce a cell

$$\boxed{S_K(H_3) = S_K(H_4) \quad\textbf{— the kernel does NOT distinguish "τ refused, then τ′" from "τ′".}}$$

| §7 question | answer |
|---|---|
| 1 is the refusal corpus-required information? | ⭐⭐ **NO — *"not KnowledgeOS domain state"*** |
| 2 required to reconstruct epistemic history? | ⛔ **NO** — no epistemic subject existed |
| 3 required for governance/audit? | ⭐ **YES, conditionally** — `Outcome` is *governance-observable* |
| 4 already represented by a K-cell? | ⚠️ **only when it rides inside an ADMITTED transition** ⇒ **`K3b`** content |
| 5 merely operational telemetry? | ⭐ **the corpus's own second disjunct: *"retained mechanism-side/infrastructure-side"*** |

⭐⭐⭐ **The subtlety the commission insisted on, in its mirror form.** `S_K(H_3) = S_K(H_4)` means
refusal information is **absent from `S_K`**. That is `[NEW CELL]` **only if** the information is
epistemically required. ⭐⭐ **It is not — so the pair is defused by the ESTATE CLASSIFICATION, not by a
cell that carries it.** ⛔ **I do not claim `K3a` covers it; `K3a` carries nothing here, because no
transition occurred.**

# 8. `K3a` self-pair — **absence of a transition**

| candidate | verdict |
|---|---|
| a transition | ⛔ **no** — `\notin \mathrm{Dom}` means no transition is defined |
| ⭐ **`(prior → prior)` self-pair** | ⭐⭐⭐ **`[UNWITNESSED]` — and NOT assumed.** No corpus witness writes a self-pair for a refusal |
| ⭐ **absence of a transition** | ⭐⭐ **`[EMP]`-supported** — *"admission is the first transition"* + *"not domain state"* |
| a governance record | ⭐ **partly** — as `Outcome`, when the transition IS defined *(`R5`)* |
| unsupported | — |

$$\boxed{\textbf{A refusal produces } \mathbf{NO\ PAIR\ AT\ ALL} \textbf{ — not } (\textit{prior} \to \textit{prior}). \textbf{ ⭐ } K3a \textbf{ has no subject here, and } P\text{-}16\textbf{'s correction is not repeated.}}$$

# 9. `K3b` warrant test — ⛔ no inference drawn

$$\mathit{Refused}(a) \Rightarrow \mathit{Warrant}(a) \quad\textbf{— ⭐ } \mathbf{[UNWITNESSED]}\textbf{, and left so.}$$

| kept apart | where it lives |
|---|---|
| policy reference | ⭐ **governance** *(`P-29` — a **transition** reference)* |
| decision basis | ⚠️ `DecisionBasis` — *"might contain"*, `[STIPULATED]` |
| transition warrant | ⭐ **`K3b`** — **only for transitions that OCCURRED** |
| reason for refusal | ⭐⭐ **`rejected(policy|authority|structure)`** — `[DERIVED]`, **governance-observable** |
| audit record | ⭐ **`History ⌢ ⟨…,Outcome⟩`** — `[DERIVED]` |

⭐ **`P-30` left `Accepted \Rightarrow Warrant` `[OPEN]`; this audit does not borrow that openness to
manufacture a refusal warrant.** ⛔ **Five distinct objects, none collapsed.**

# 10. `K4b-i` / `K4b-ii` attack

| | case | retained information |
|---|---|---|
| **W** | something **stood**, then was withdrawn | ⭐ **`K4b-i`** withdrawn ground + **`K4b-ii`** invalidated-by + **`K3a`** pair — ⭐⭐ **a subject exists, and its standing was removed** |
| **R** | something **never stood** — the transition was refused | ⭐⭐⭐ **NOTHING — there is no subject.** *"Admission is the first transition"* |

$$\boxed{\begin{array}{c}\textbf{The retained state is NOT identical — it is } \mathbf{EMPTY\ IN\ CASE\ R.}\\ \boxed{\textbf{⭐ So refusal is not "already represented" by } K4b\text{-}i \textbf{; it is } \mathbf{OUT\ OF\ SCOPE} \textbf{ for it. ⛔ Different conclusion, and the stronger one.}}\end{array}}$$

⭐ **The exact information difference:** `W` carries a **ground** and a **prior standing**; `R` carries
**no epistemic subject**, so there is nothing for either cell to point at.

# 11. ⭐⭐⭐ `P-30` §9 attacked — **`[QUALIFIED]`**, and it was not forced

> `P-30`: *"None of `K1`–`K11` was derived from a refused transition; every cell assumes a transition
> occurred."*

$$\boxed{\mathbf{[QUALIFIED].}\ \textbf{The } \mathbf{transition} \textbf{ half stands. The } \mathbf{non\text{-}occurrence} \textbf{ half does NOT — two cells already encode a non-occurrence.}}$$

| cell | the non-occurrence it already encodes |
|---|---|
| ⭐⭐ **`K5`** | `P-19`'s restatement — *the estate must not claim checkable grounding it does not have.* ⭐⭐⭐ **Corroborated by `GT-004`: *"Every verification has evidence OR AN EXPLICIT UNKNOWN REASON."*** An explicit UNKNOWN is a **recorded failure to establish** |
| ⭐ **`K3b`** | `P-08` §5.3's **honest downgrade** — the estate records that a warrant could **not** be retained, rather than retaining the object |
| `K4b-i` | a **withdrawn** ground — a standing that **ceased** |

⭐⭐ **So the kernel does already speak about things that did not happen — but always about a subject
that EXISTS and a grounding that FAILED, never about a transition that never occurred.** ⭐ **That is
the precise qualification, and it narrows `P-31`'s question exactly as the commission anticipated.**

# 12. Information decomposition

$$I_R = I_{attempt} \cup I_{decision} \cup I_{reason} \cup I_{policy} \cup I_{history}$$

| component | estate | cell |
|---|---|---|
| `I_attempt` | ⭐⭐ **operational** — `[UNWITNESSED]` epistemically | ⛔ **none** |
| `I_decision` | ⭐ **governance** — guard / `Pre` | ⛔ none |
| `I_reason` | ⭐⭐ **governance** — `rejected(policy|authority|structure)`, *governance-observable* | ⛔ none |
| `I_policy` | ⭐ **governance** — `P-28` item 4, **not reopened** | ⛔ none |
| `I_history` | ⚠️ **governance History**, or **`K3b`** when inside an admitted transition | conditional |

$$\boxed{I_R \not\subseteq I_K \quad\textbf{— and that is CORRECT, because } I_R \textbf{ is not epistemic. ⭐⭐ Non-containment here signals the ESTATE BOUNDARY, not a missing cell.}}$$

# 13. Estate classification

| datum | estate |
|---|---|
| the attempt | ⭐ **operational** |
| the guard evaluation | ⭐ **governance** |
| the refusal decision | ⭐⭐ **governance** — *governance-observable* |
| the rejection reason | ⭐ **governance** |
| **that a candidate was not admitted** | ⭐⭐⭐ **NOT epistemic — *"non-admitted candidates are NOT KnowledgeOS domain state"*** |
| ⭐ the `Outcome` of an **executed** transition *(`R5`)* | ⭐ **governance-observable, recorded in History** |
| `\notin \mathrm{Dom}(\omega)` itself | ⭐⭐ **NO ESTATE — a model boundary** |

⭐ **Not every auditable fact is epistemic** — and the corpus supplies the second home itself:
*"retained mechanism-side/infrastructure-side."*

⚠️ **`ZeroFinding \neq Knowledge` is boxed, but its estate placement is presented as *"Option A"* of a
decision the corpus says it *"has to decide"* — so it is `[OPEN]`, NOT a settled ruling, and is not
leaned on here.**

# 14. Statistical analogy — **analytical only**

Refusal is closest to a **guard failure** and a **selection mechanism**; ⛔ not a censored observation
*(which presupposes a subject)*, ⛔ not a rejected hypothesis *(a completed inference)*.

> *Is "not observed because filtered" informationally different from "not observed because it never
> occurred"?* — **Yes, for a selection model.** ⚠️ **This analogy is analytical and is NOT corpus
> evidence.** ⭐ **The corpus reaches the opposite placement on its own grounds, and the corpus wins.**

# 15. DDD test

| form | status |
|---|---|
| **command outcome** *(`Reject`)* | ⭐⭐ **`[DERIVED]`** — `Outcome` in the codomain |
| **policy evaluation** *(guard)* | ⭐ **`[EMP]`** — `Pre`, guard, precondition |
| **governance record** | ⭐ **`[DERIVED]`** — `History ⌢ ⟨…,Outcome⟩` |
| **operational telemetry** | ⭐ **`[EMP]`** — *"retained mechanism-side/infrastructure-side"* |
| **status value** *(`Rejected`)* | ⭐ **`[EMP]`** — `Ω_A`; distinct from `Refused` |
| **domain event** *(`RefusalRecorded`)* | ⭐⭐⭐ **`[UNWITNESSED]`** |
| **entity / value object / aggregate** | ⭐⭐⭐ **`[UNWITNESSED]` — and the corpus says *"there is no hidden semantic aggregate"*** |

⛔ **No `Refusal` aggregate. The corpus pre-empts it in the same sentence that classifies the estate.**

# 16. Implementation contamination — ⭐ **none**, on a declared scope

Searched **KnowledgeOS** implementation only — `research/knowledgeos-sim/`, `readiness/exec/` and the
DDD verification plans: ⭐ **zero** `reject|refus|denied|attempt` in the research code.
⛔ **`app/` deliberately excluded and reported: it is the PublicDigit voting platform, an unrelated
product whose `Reject`/`Denied` tokens are voting-code semantics.** ⭐ **Searching it would have
manufactured contamination, not detected it.**

# 17. Falsification — run **before** the stop, in both directions

| direction | result |
|---|---|
| ⭐⭐ **FOR an epistemic refusal obligation** | `refusal must be auditable` **0** · `attempt must remain` **0** · `refused transition` **0** · `policy denial` **0** · `refusal retained` **0** — ⭐ **and the one concept-level hit cuts the OTHER way: `GT-005` says only *"A denied action cannot execute"* — PREVENTION, never retention** |
| ⭐⭐ **AGAINST** | ⭐⭐⭐ *"non-admitted candidates are NOT KnowledgeOS domain state"* · *"retained mechanism-side/infrastructure-side"* · *"no hidden semantic aggregate"* · *governance-observable* · `refusal is operational` **0** *(the phrasing is absent; the CONTENT is present)* |
| ⚠️ **the `P-30` trap re-checked** | ⭐ **the concept was searched, not just the vocabulary** — `auditable`, `audit trail` (24), `recorded in History` (5), `rejection reason` (3), `denied action` (2), `guard` (194), `precondition` (252), `Outcome` (605) **all read**. ⭐⭐ **The obligation that exists is `R5`'s, and it is governance-side** |

⭐ **Zero is never positive evidence — each zero above is reported with its pattern against a scope of
1 859 admissible files, and the verdict rests on the POSITIVE witnesses.**

# 18. New-cell test — **NOT TRIGGERED**

| condition | status |
|---|---|
| 1 independently required | ⭐ 🔴 **FAILS** |
| 2 persistence-relevant | ⚠️ **governance-side only** |
| 3 **inside the epistemic boundary** | ⭐⭐⭐ 🔴 **FAILS — *"NOT KnowledgeOS domain state"*** |
| 4 not represented by `K1`–`K11` | ⭐ ✅ **true — and irrelevant, because 3 fails** |
| 5 information-theoretically independent | ⭐⭐ ✅ **the pair EXISTS** — ⭐⭐⭐ **and still no cell, because 3 and 6 fail** |
| 6 corpus-grounded epistemic requirement | ⭐⭐ 🔴 **FAILS — the corpus grounds the OPPOSITE** |

$$\boxed{\begin{array}{c}\textbf{⭐⭐ Conditions 4 AND 5 PASS and the cell is still REFUSED — the estate boundary, not coverage, is what stops it.}\\ \boxed{\textbf{This is the first audit in the series where an independence pair EXISTS and no cell follows.}}\end{array}}$$

---

# 19. `P-31` VERDICT

$$\boxed{\mathbf{B} \textbf{ — refusal is corpus-attested, and creates a GOVERNANCE/OPERATIONAL obligation only.}}$$

⭐⭐ **The two components of option `A`, reported separately as declared — and they came apart:**

| component | verdict |
|---|---|
| *"`⇀` is mathematical/specification partiality only"* | ⭐⭐ **REJECTED — the corpus glosses it TWICE as *validity*, deliberately (*"is important because"*)** |
| *"refusal is not corpus-attested as persistence-bearing"* | ⭐ **HALF-TRUE — it IS attested, and it bears GOVERNANCE persistence** |

⛔ **So `A` would have been the wrong box, and choosing it would have recorded a false finding about
the arrow.** ⛔ **Not `C`** — refusal is not epistemic, and no cell carries it *(`K4b-i` is out of scope,
not covering)* · ⛔ **not `D`** — condition 3 fails · ⛔ **not `E`** — the corpus determines it.

## `R1`–`R6` table · Refusal/non-event distinction · `H3`/`H4` · `K3a` self-pair
**§3 · §4–5 · §7 · §8 above.** In one line each:
$$\boxed{\begin{array}{ll}\textit{no attempt} & \textbf{epistemically indistinguishable from a refusal — no subject exists}\\ \textit{attempt + refusal} & \textbf{⭐ NOT domain state; infrastructure-side or inside an admitted transition}\\ \textit{attempt + failure} & \mathbf{[UNWITNESSED]}\\ \textit{executed + Rejected outcome} & \textbf{⭐⭐ a REAL transition } (R5) \textbf{ — } K3a + K3b\textbf{, governance-observable}\\ \textit{undefined specification} & \textbf{⭐ } \mathbf{REFUTED\ for\ } \delta \textbf{ (glossed); ⚠️ still live for } \omega \textbf{ (no local gloss)}\end{array}}$$
$$S_K(H_3) = S_K(H_4) \qquad \textbf{a refusal produces } \mathbf{no\ pair\ at\ all} \textbf{, ⛔ not } (\textit{prior} \to \textit{prior})$$

## Existing-cell mapping
⛔ **None.** ⭐ **Refusal is not carried by any cell — it is OUTSIDE the epistemic estate.** The only
refusal-adjacent thing a cell carries is **`R5`'s `Outcome`**, and only because that transition
**occurred** — retained as **`K3a`**'s pair and **`K3b`**'s warrant content, exactly as `P-30` fixed.

## `P-30` §9 verdict
$$\boxed{\mathbf{[QUALIFIED]}\ \textbf{— } \mathbf{K5}\ (P\text{-}19\textbf{'s restatement, corroborated by } GT\text{-}004\textbf{'s } \textit{"explicit UNKNOWN reason"}) \textbf{ and } \mathbf{K3b}\ (P\text{-}08\ \S5.3\textbf{'s honest downgrade}) \textbf{ ALREADY encode a non-occurrence.}}$$
⭐ **Precise qualification:** they encode a **failure to establish grounding for an EXISTING subject** —
never **a transition that never occurred.** `P-30`'s sentence is right about transitions and wrong
about non-occurrence in general.

## Kernel status
$$\boxed{|K| = 11 \textbf{ — unchanged. No cell added, removed, split, merged or re-scoped.}}$$

## Status register
**`[EMP]`** ⭐ *"The arrow `⇀` is **important** because not every event is valid in every state"* ·
*"not all events are valid in all states"* · `AddEvidence(A,E)` invalid if `A \notin K_t` · ⭐⭐
*"**non-admitted candidates are NOT KnowledgeOS domain state**"* · *"recorded in History as part of an
admitted transition, **or** retained mechanism-side/infrastructure-side"* · *"there is no hidden
semantic aggregate"* · *"a very strong anti-'God Kernel' constraint"* · ⭐ *"**Admission is the first
transition of the epistemic lifecycle**"* (4 files) · `GT-005` *"A denied action cannot execute"* ·
`GT-004` *"every verification has evidence or an explicit UNKNOWN reason"* · `Add` explicitly partial ·
`Post` is **total** · `⇀` in **45+52** files over six functions.
**`[DERIVED]`** ⭐⭐ *`⇀` carries no special signal for `ω`* · **`I_R \not\subseteq I_K` marks the estate
boundary, not a gap** · a refusal produces **no `K3a` pair** · `K4b-i` is **out of scope**, not covering
· `H0` and `H1` are epistemically indistinguishable.
**`[CORROBORATION]`** `Outcome ∈ {ok, rejected(policy|authority|structure)}` · `History ⌢ ⟨…,Outcome⟩`
· *"governance-observable: YES"* — ⚠️ all **`verification/TRANSFORMATION-THEORY.md`, an earlier lane's
`[DERIVED]` artifact, provenance checked**.
**`[STIPULATED]`** `Ω_A` and its `⇀` *("I recommend"; no local gloss)* · `EVal`'s and `ε`'s arrows ·
`DecisionBasis`.
**`[ARCH]`** none used. ⭐ **KnowledgeOS implementation searched and CLEAN; `app/` excluded by declared
scope.**
**`[QUALIFIED]`** ⭐⭐ **`P-30` §9's non-occurrence claim.**
**`[REFUTED]`** ⭐ *`\notin \mathrm{Dom}(\omega)` implies an attempt occurred* · *`⇀` is a model gap*
(for `δ`) · *"one partial arrow among total ones"* · *rejection is modelled as partiality* · *`K4b-i`
covers refusal*.
**`[UNWITNESSED]`** `M4`/`R3` as a `⇀` reading · `R4` failed transitions · `Refused \Rightarrow Warrant`
· `RefusalRecorded` as a domain event · `Refusal` as entity/value object/aggregate · `(prior → prior)`
self-pairs · `attempt` as an epistemic object.
**`[OPEN]`** ⭐ **the corpus's own disjunction — *History of an admitted transition* **OR**
*infrastructure-side* — is left unresolved by the corpus** · ⭐ **`ω`'s partiality has no local gloss,
so `R6` stays live for `ω` alone** · `ZeroFinding`'s estate *(presented as "Option A" of an undecided
question)* · `Accepted \Rightarrow Warrant` *(`P-30`)* · `Governance`-in-`K` vs `P-28`'s two estates
*(`P-30`, not reopened)* · `W3`'s conditional *(`P-29`)* · `Ind_ρ`'s five other slots · `K4b-ii`'s
corroboration arm · register deletion · *"estate"*'s two referents · ⛔ **well-foundedness /
terminality / acyclicity — DEFERRED, not reopened.**

## ⭐ ONE next unresolved question

$$\boxed{\begin{array}{c}\textbf{Is the "explicit UNKNOWN reason" a } \mathbf{K5} \textbf{ persistence obligation?}\\[4pt] \boxed{\begin{array}{l}GT\text{-}004\textbf{: "Every verification has evidence } \mathbf{OR\ AN\ EXPLICIT\ UNKNOWN\ REASON.}\textbf{"}\\ \textbf{⭐ That is a RECORDED NON-ESTABLISHMENT — the estate storing } \mathbf{why\ it\ could\ not\ establish\ something.}\\ \textbf{⭐⭐ } P\text{-}19 \textbf{ restated } K5 \textbf{ as } \textit{"the estate must not CLAIM grounding it does not have"} \textbf{ — a } \mathbf{prohibition.}\\ GT\text{-}004 \textbf{ looks like a } \mathbf{positive\ obligation\ to\ RECORD\ the\ reason} \textbf{, which is strictly stronger.}\end{array}}\end{array}}$$

```
B — REFUSAL IS CORPUS-ATTESTED AND CREATES A GOVERNANCE/OPERATIONAL OBLIGATION ONLY.
KERNEL REMAINS 11 CELLS. NO K12.

THE COMMISSION'S CORRECTION IS CONFIRMED BY MEASUREMENT, NOT MERELY CONCEDED. The corpus glosses its
own arrow, twice:
  question-15:1952  "The arrow ⇀ is IMPORTANT because not every event is valid in every state."
  question-15:2433  "The ⇀ indicates a partial function: not all events are valid in all states."
That is VALIDITY (M3), not refusal (M4). The slide from (s,e,p) not-in Dom(omega) to "an attempt
occurred and was refused" has ZERO corpus support. And the arrow is DELIBERATE — "is important
because" — so it is also not a notation gap.

BUT OPTION A WOULD STILL HAVE BEEN THE WRONG BOX, which is why the two components were split. The
arrow is NOT mere notation (rejected), while refusal IS attested and DOES bear persistence — just
governance-side. Reporting them jointly would have recorded a false finding about the arrow.

PARTIALITY IS THE CORPUS DEFAULT, WHICH KILLS THE WEAK INFERENCE OUTRIGHT: ⇀ appears in 45+52 files
across SIX transition functions (delta, T, omega, EVal, epsilon, Add) while → is reserved for
PREDICATES (Post is total). "One partial arrow among total ones" is REFUTED. omega's arrow is doubly
weak — Omega_A is [STIPULATED] ("I recommend") and §27 gives no local gloss at all.

THE DECISIVE ESTATE RULING, AND THE CORPUS STATES THE ANTI-INFLATION RULE ITSELF:
  "Non-admitted candidates are NOT KnowledgeOS domain state. They are either: recorded in History as
   part of an ADMITTED transition, or retained mechanism-side/infrastructure-side. There is no hidden
   semantic aggregate. That is a very strong anti-'God Kernel' constraint."
Combined with "Admission is the first transition of the epistemic lifecycle" (4 files): before
admission there is NO EPISTEMIC SUBJECT, so a refused candidate leaves nothing for the estate to
retain. H0 (no attempt) and H1 (attempt refused) are epistemically indistinguishable.

THE CORPUS EXPLICITLY REFUSES TO MODEL REJECTION AS PARTIALITY, converting R3 into R5: "Outcome is
required in the codomain. A partial function that merely fails cannot distinguish 'rejected by policy'
from 'rejected by authority' — and both must be recorded in History." So a rejection is a SUCCESSFUL
transition with a Rejected outcome, carried by K3a/K3b — and its own row reads "governance-observable."
Provenance checked before use: that file is an earlier verification lane's [DERIVED] artifact, not
[EMP] primary and not Lane T's.

THE FIRST AUDIT IN THE SERIES WHERE AN INDEPENDENCE PAIR EXISTS AND NO CELL FOLLOWS. S_K(H3) = S_K(H4):
refusal information is genuinely ABSENT from S_K, so conditions 4 and 5 of the new-cell test PASS. The
cell is still refused, because condition 3 fails — "NOT KnowledgeOS domain state." The ESTATE BOUNDARY
stops it, not coverage. I do NOT claim K3a covers it; K3a has no subject here, and a refusal produces
NO PAIR AT ALL — not (prior -> prior), which stays [UNWITNESSED] and was not assumed.

K4b-i IS OUT OF SCOPE, NOT COVERING — a stronger and different conclusion. Case W (stood, then
withdrawn) retains a ground and a prior standing; Case R (never stood) has no epistemic subject for
either cell to point at.

P-30 §9 IS [QUALIFIED], AND NOT FORCED. K5 (P-19's restatement, corroborated by GT-004's "explicit
UNKNOWN reason") and K3b (P-08 §5.3's honest downgrade) ALREADY encode a non-occurrence. The precise
qualification: they encode a FAILURE TO ESTABLISH GROUNDING FOR AN EXISTING SUBJECT — never a
transition that never occurred. Right about transitions, wrong about non-occurrence in general.

THE FALSIFICATION RAN BEFORE THE STOP, AS DECLARED, AND ITS ONE CONCEPT-LEVEL HIT CUT AGAINST THE
OBLIGATION: GT-005 says only "A denied action cannot execute" — PREVENTION, never retention.

ONE OPEN THING THE CORPUS ITSELF LEAVES OPEN: its disjunction — History of an admitted transition OR
infrastructure-side — is never resolved, and omega's partiality has no local gloss, so R6 stays live
for omega alone.

NEXT QUESTION (one): IS THE "EXPLICIT UNKNOWN REASON" A K5 PERSISTENCE OBLIGATION? GT-004 requires
  every verification to have evidence OR AN EXPLICIT UNKNOWN REASON — a RECORDED NON-ESTABLISHMENT.
  P-19 restated K5 as a PROHIBITION ("must not claim grounding it does not have"); GT-004 looks like a
  positive obligation to RECORD THE REASON, which is strictly stronger.

NO ARCHITECTURE — NO SCHEMA v3 — NO REFUSAL AGGREGATE — NO REFUSAL REGISTER — P-30 NOT REOPENED —
POLICY PERSISTENCE NOT REOPENED — 3MC AND LANE M UNTOUCHED — WELL-FOUNDEDNESS STILL DEFERRED.
```
