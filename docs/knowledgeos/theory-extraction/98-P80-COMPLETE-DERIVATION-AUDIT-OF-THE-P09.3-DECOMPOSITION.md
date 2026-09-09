# `P-80` — Complete Derivation Audit of the `P-09.3` Decomposition

**2026-09-09 · Lane T.** After [`97` `P-79`](./97-P79-LAYER-PROVENANCE-AUDIT-OF-K1-K11.md).
**Method:** `§2a` **complete reading** — `P-09.3`, all 474 lines, §§1–22. ⭐ **No range sampling.**

# 1. Executive verdict

$$\boxed{\mathbf{[QUALIFIED].}}$$

⭐⭐⭐ **The decomposition is independently derived — far more so than `P-79` implied.** `P-09.3` supplies
its **own criterion**, its **own counterexamples in both directions per split**, a **structural
explanation**, a **stopping rule**, a **pairwise test** and a **set-wise test**. ⛔ **What it does not
supply is semantic minimality, and it says so itself.**

⭐⭐⭐ **And it refutes my `P-79` propagation concern — by a document that predates it by 41 audits:**

$$\boxed{\mathbf{[DOES\ NOT\ PROPAGATE].}\ K3a\textbf{'s unestablished discharge does not reach } K3b.}$$

$$|K| = 11 \cdot \mathbf{[REC]} \cdot \mathbf{UNFROZEN} \textbf{ — unchanged.}$$

# 2. ⚠️ Concession, and one disagreement

**2.1 Conceded.** `P-79`'s *"if the split inherits `K3`'s independence, `K3a`'s NOT ESTABLISHED discharge
propagates to `K3b`"* was **asserted, not established** — ⭐ and §6 below shows it is **false.**
⚠️ **`P-79`'s framing was also too weak:** it called the eight cells *"revision-layer births"* in a way
that implied thinner support. ⛔ **They are derived by a stated criterion; birth layer ≠ weak basis.**

**2.2 ⭐⭐ Disagreement — the relation is not undefined, and it is none of the five the commission
listed.** `P-09.3` §3 defines it operationally:

$$\boxed{\begin{array}{c}K=(\Delta,\mathcal T) \textbf{ DECOMPOSES iff there exist } \mathbf{TWO\ admissible\ history\text{-}pairs}\textbf{ — one varying each part with the other fixed.}\\[4pt] \textbf{⭐ } \mathbf{Admissible} = \textbf{the varying transition lies in the } \mathbf{CURRENT}\ \mathcal T_{KOS}\textbf{, and the requirement is } \mathbf{[EMP]\ or\ [DERIVED]}.\end{array}}$$

⇒ ⭐⭐ **It is *independent variability under a witnessed transition*** — ⛔ **not statistical
independence, not abstract logical non-implication, not irreducibility.** ⭐ **A counterexample-based
criterion**, and `§2`'s `[UNDEFINED]` verdict does **not** apply.

⭐ And §3's **insufficient-as-evidence** list forecloses the cheap answers: *"different objects · different
DDD categories · different implementations · different tables or fields · different names ·* ⭐⭐ *the mere
ability to describe sub-parts."*

⛔ **No `|K|` change · no canonicalization · no minimality/sufficiency resolved · no `𝒳_R`/`𝒳_S` · no
theory constructed · no `three_model_convergence`.**
⚠️ **Pre-registered `[QUALIFIED]`.** ⭐ Correct — record 24 of 35.

# 3. What `P-09.3` defines before deciding anything

**§2 — capability, derived not inherited:**
$$K = (\Delta,\ \mathcal T_K), \qquad \Delta = \textbf{a distinction the estate is REQUIRED to make}, \qquad \mathcal T_K \subseteq \mathcal T_{KOS}$$
⭐⭐ ***"A capability is INDEXED BY A TRANSITION SET. 'X must be preserved' is not a capability until the
transition under which X could be lost is named."*** ⛔ And mechanism · representation · field · entity ·
record · relation · implementation construct are **`[ARCH]`, barred from the kernel ontology.**

**§4 — three verdicts, with the honest caveat stated as a boxed rule:**
$$\boxed{\textbf{⭐⭐⭐ } \mathbf{\textit{"No counterexample found" NEVER means "atomic". It means STABLE UNDER THIS BOUNDARY. And no\text{-}evidence} \neq \mathbf{false.}}}$$

# 4. The required table

| parent | child | exact proposition | why split? | derivation present? | logical indep. | evidential indep. | domain distinction | status |
|---|---|---|---|---|---|---|---|---|
| `K3` | **`K3a`** | prior-value retention | ⭐ *"changed because of the containment argument" — **without saying from what***, under `τ2` | ⭐⭐ **YES** — admissible counterexample, §7 | ⭐ **YES** §12 both ways | ⚠️ **PARTIAL** — requirement from `P-08` §17 | ⭐ **subject/role**, `[DERIVED]` | ⛔ **`NOT ESTABLISHED`** *(discharge, `P-67`)* |
| `K3` | **`K3b`** | warrant retention | ⭐ `resolved` with prior value but **no justification** ⇒ adjudicated ≡ never-in-doubt | ⭐⭐ **YES** §7 | ⭐ **YES** §12 | ⚠️ **PARTIAL** — from `P-08` §19's **repaired** witness | ⭐ `[DERIVED]` | ⭐ **`[DERIVED]` `[QUALIFIED]`** |
| `K4a` | **`K4a-i`** | prior proposition text | ⭐ relation kept, proposition lost ⇒ *"something was superseded"* with no content | ⭐⭐ **YES** §8 | ⭐ **YES** §12 | ⚠️ **PARTIAL** — `P-08` §5.2 | ⭐ `[DERIVED]` | ⭐⭐ **`[EMP]`** |
| `K4a` | **`K4a-ii`** | the *supersedes* relation | ⭐ proposition kept, relation lost | ⭐⭐ **YES** §8 | ⭐ **YES** §12 | ⚠️ PARTIAL | ⭐ `[DERIVED]` | ⚠️ **role-overload `[OPEN]`** |
| `K4b` | **`K4b-i`** | withdrawn ground | ⭐ invalidation kept, withdrawn warrant lost ⇒ *"which kind of ground failure?"* | ⭐⭐ **YES** §9 | ⭐ **YES** §12 | ⚠️ PARTIAL | ⭐ `[DERIVED]` | ⭐ **`[EMP]`**, stratification `[OPEN]` |
| `K4b` | **`K4b-ii`** | *invalidated-by* | ⭐ converse of the above | ⭐⭐ **YES** §9 | ⭐ **YES** §12 | ⚠️ PARTIAL | ⭐ `[DERIVED]` | ⚠️ role-overload `[OPEN]` |
| `K4c` | **`K4c-i`** | retired predecessor identity | ⭐ successor relation kept, predecessor identity lost ⇒ *"something merged into C"*, **`W1` survives** | ⭐⭐ **YES** §10 | ⭐ **YES** §12 | ⚠️ PARTIAL | ⭐ `[DERIVED]` | ⚠️ **conditional on `O-P09-1`** |
| `K4c` | **`K4c-ii`** | *retired-into* | ⭐ converse | ⭐⭐ **YES** §10 | ⭐ **YES** §12 | ⚠️ PARTIAL | ⭐ **splits by ROLE, ⛔ not arity** | ⚠️ conditional |

⭐ **Rejected sub-parts, recorded:** *transition fact* ⛔ *(prior value entails a change occurred)* ·
*successor value* ⛔ *(that is `K2`)* · *transition classification* ⚠️ **`[OPEN]`**.

# 5. ⭐⭐⭐⭐ The structural result — and it is the strongest thing in the lane's own record

Four capabilities tested, **all four split the same way**:

$$K3 = \underbrace{\textit{prior value}}_{\text{antecedent}} + \underbrace{\textit{warrant}}_{\text{link}}, \qquad K4a = \underbrace{\textit{prior text}}_{\text{antecedent}} + \underbrace{\textit{supersedes}}_{\text{link}}, \quad \dots$$

$$\boxed{\begin{array}{c}\textbf{EVERY retention capability } = \textbf{ ANTECEDENT RETENTION } + \textbf{ LINK RETENTION.}\\[6pt] \textbf{⭐⭐⭐ } \mathbf{THE\ KERNEL\ IS\ NOT\ A\ SET\ OF\ CAPABILITIES.\ IT\ IS\ A\ PRODUCT\ OF\ TWO\ INDEPENDENT\ INDIVIDUATION\ AXES.}\end{array}}$$

⭐⭐ **Axis 1, SUBJECT** — *proposition · ground · reference* — forced by `P-09.2`'s `Π2`
*(readability ≠ inspectability ≠ resolvability)*. ⭐⭐ **Axis 2, ROLE** — *antecedent · link* — forced by
**the eight counterexamples of §§7–10.** ⭐ **Orthogonal.**

⭐⭐⭐ **And this is a stopping rule, not a survival report:** ***"Recursion terminates HERE — not for
want of counterexamples"***, and it ***"explains the counterexamples instead of merely surviving
them."*** §15: **every further split would need a THIRD individuation axis, and none is evidenced.**

# 6. ⭐⭐⭐ The propagation test — `[DOES NOT PROPAGATE]`

| | `K3a` | `K3b` |
|---|---|---|
| **witness** | ⭐ `P-08` §17's **insufficiency proof** | ⭐ `P-08` §19's **repaired** witness |
| **`P-67`/`P-69`/`P-70` attacked** | ⭐⭐⭐ **`W2`** — the prior-association value | ⛔ **not attacked** |
| **§12 pairwise** | `K3a ⇏ K3b` | `K3b ⇏ K3a` — **both directions** |
| **§13 set-wise** | — | ⭐⭐ **NOT redundant**: *"a warrant is an **adjudication**, and `P-08` §6's criterion forbids regenerating decisions"* |

$$\boxed{\begin{array}{c}\textbf{⭐⭐⭐ } K3b \textbf{ has } \mathbf{its\ own\ witness\ AND\ its\ own\ set\text{-}wise\ argument.}\\[3pt] \textbf{⇒ } \mathbf{[DOES\ NOT\ PROPAGATE]} \textbf{ — and } P\text{-}79\textbf{'s concern is } \mathbf{[REFUTED]}\textbf{, by }P\text{-}09.3\textbf{ itself.}\end{array}}$$

⭐ **The commissioner's suspicion was right and my sentence was wrong.**

# 7. The three questions, kept apart *(§5 of the commission)*

| | verdict |
|---|---|
| ⭐⭐ **A · identity distinction** — are they different propositions? | ⭐⭐ **ESTABLISHED for all eight** — a counterexample in each direction, under a transition in the **current** universe |
| ⭐⭐ **B · logical independence** — does establishing one fail to establish the other? | ⭐⭐ **ESTABLISHED pairwise (§12, nine counterexamples) and set-wise (§13)** — ⚠️ with **exactly one** conditional redundancy: **`K2` if `K3` becomes universal** |
| ⚠️ **C · evidential independence** — own basis, or the parent's? | ⚠️⭐⭐⭐ **PARTIAL, and this is the audit's honest core:** each cell has **its own counterexample**, ⛔ **but every underlying requirement traces to `P-08` §§5.2/6/17/19** |

⭐⭐⭐ **And `P-09.3` says so about `K3` itself:** *"both requirements were **ALREADY IN `P-08`** — stated
as two clauses of one capability *('prior value + warrant')* **without noticing they are independently
variable.** The `P-09.2` error pattern, repeating one level down."* ⛔ **`P-08` not rewritten.**

# 8. The five required statements

**① What `P-09.3` proves:** ⭐ the **identity distinctions** (A) and **logical independence** (B), by
counterexample under witnessed transitions; the **two-axis product structure**; the **halting** of the
recursion; and **one** conditional set-wise redundancy.
**② What it merely proposes:** ⚠️ the two axes as *the* individuation — ⛔ **a third axis is
unevidenced, not excluded.**
**③ What it inherits:** ⭐⭐ **all eight requirements**, from `P-08` §§5.2, 6, 17, 19.
**④ What remains unsupported:** ⭐⭐⭐ **semantic minimality — `P-09.3` §14 states it: ✅ in Lane T, and
NOT established.** Also `K2`'s decomposability *(conditional on the open part of `𝒯_KOS`)*, role-overload,
stratification, `O-P09-1`, `W6`.
**⑤ Is `P-79`'s propagation concern valid?** ⛔ **NO — `[REFUTED]`** *(§6)*.

# 9. §14's four minimalities — and the one this lane may claim

| term | definition | Lane T |
|---|---|---|
| **semantic minimality** | minimum independently necessary **distinctions** | ✅ in scope — ⛔ **NOT established** |
| ⭐ **capability minimality** | minimum independently necessary **obligations `(Δ,𝒯)`** | ⭐⭐ ✅ **a LOWER BOUND only** |
| **representation minimality** | minimum implementation constructs | ⛔ **`[ARCH]`, outside the lane** |
| ⚠️ **Lane M's minimality** | kernel **objects** | ⛔ **a FOURTH sense, not reconciled** |

⭐⭐⭐ **This separation has existed since audit 23** — and `P-47`, `P-48`, `P-53`, `P-65`, `P-66` argued
about *"minimality"* **without citing it.** ⚠️ **`EKS-24`'s pattern inside this lane, third occurrence,
recorded not filed.**

# 10. DDD test *(§9 of the commission)*

⭐⭐ **The SUBJECT axis is a domain distinction** — `Π2`'s *readability ≠ inspectability ≠
resolvability* over *proposition · ground · reference*, `[DERIVED]` from counterexamples.
⭐ **The ROLE axis is structural** — *antecedent · link* — also `[DERIVED]`, from the eight
counterexamples. ⛔ **Neither is derived from architecture, and §2 bars architecture from the ontology.**
⛔ **Not merely a technical subdivision of one persistence requirement** — §13 shows the parts are not
set-wise recoverable from one another. ⚠️ **But `[UNWITNESSED]` as *distinct domain invariants*:** no
invariant register maps them *(`P-76`: `TV-F-029`'s invariant set has no crosswalk)*.

# 11. Provenance graph *(§7 of the commission)*

```
P-08 §§5.2/6/17/19  --[EXPLICIT]-->  P-09.3 §§7-10 decomposition arguments
P-09.2 Π2/Π3        --[EXPLICIT]-->  P-09.3 §5.4 subject axis
P-09.3 §§7-10       --[DERIVED]-->   eight child propositions
P-09.3 §§12-13      --[DERIVED]-->   pairwise + set-wise independence
eight children      --[EXPLICIT]-->  P-11 §13 closure record
P-11 §13            --[EXPLICIT]-->  P-67/P-69/P-70 rebuttals (K3a / W2 only)
P-09.3 §14          --[UNWITNESSED]-->  P-47/P-48/P-53/P-65/P-66  ⛔ never cited
```
⛔ **No silent edges.** ⚠️ **The last edge is `[UNWITNESSED]` and it is the significant one.**

# 12. Statistical discipline

⛔ Nine pairwise counterexamples, eight splits, four capabilities — **one corpus, one lineage.**
⭐ Classification: **`SAME LINEAGE`** *(`P-08` → `P-09.2` → `P-09.3`)*. ⛔ **No replication claimed, and
repeated appearances of a cell are not observations.**

# 13. Status register
**`[EMP]`** ⭐⭐⭐ §2's `K=(Δ,𝒯_K)` and the `[ARCH]` bar · §3's criterion and its insufficient-evidence
list · §4's *"no counterexample found never means atomic"* · §7's two `τ2` counterexamples and its own
*"already in `P-08`"* admission · §§8–10's six counterexamples · §10's *"splits by role, not arity"* ·
§10's two-axis product and *"recursion terminates HERE"* · §12's nine pairwise counterexamples · §13's
`K1` presupposition failure, `K5` unconditional independence, and the single `K2` conditional redundancy
· §14's four minimalities with **semantic minimality NOT established** · §15's halting argument.
**`[DERIVED]`** ⭐⭐⭐ **A and B established for all eight; C only PARTIAL** · the two-axis structure is
the stopping rule · `[DOES NOT PROPAGATE]`.
**`[QUALIFIED]`** ⭐⭐ the decomposition as a whole — **derived, inheriting its requirements.**
**`[REFUTED]`** ⭐⭐ **`P-79`'s propagation concern** · ⚠️ **`P-79`'s implication that revision-layer birth
means thinner support.**
**`[OPEN]`** ⭐ a third individuation axis *(unevidenced, ⛔ not excluded)* · `K2`'s decomposability ·
role-overload · stratification · `O-P09-1` · `W6` · **semantic minimality** · all carried opens ·
⛔ **well-foundedness / terminality / acyclicity — DEFERRED.**
**Governance:** `|K| = 11` · **`[REC]`** · **UNFROZEN**.

# 14. Backlog — ⛔ **nothing, and the reason is the discipline**

⭐ Two candidates tested. ① **§14's fourth minimality sense, *"not reconciled"*** — ⛔ **already filed by
the concurrent session as its own `EKS-23`** *(two capability vocabularies never reconciled)*.
② **§14 uncited by five later audits** — ⛔ **`EKS-24`'s pattern, third occurrence inside this lane**;
a fourth record of one cause is noise. ⭐⭐ **Twelfth audit in sixteen with no new ticket.**

### 15. ONE NEXT UNRESOLVED QUESTION

$$\boxed{\textbf{Is there a } \mathbf{THIRD\ individuation\ axis} \textbf{ — and would the } \mathbf{[OPEN]\ part\ of\ }\mathcal T_{KOS} \textbf{ (}\tau4\textbf{, model replacement, semantic reinterpretation) supply it?}}$$

⭐⭐⭐ **This is the load-bearing question `P-09.3` itself leaves, and it is now precisely posed.** §15
halts the recursion because *"every further split requires a third individuation axis, and none is
evidenced"* — ⛔ **and §2 explains exactly why none is: a sub-part whose variation no current transition
produces yields no admissible counterexample.** ⭐⭐ **So the halting is a function of `𝒯_KOS`'s
CURRENT membership, and `𝒯_KOS` is explicitly *descriptive, not closed.*** ⚠️ **If `τ4` or semantic
reinterpretation is admitted, `K2` decomposes (§6) and the antecedent cells become splittable on
content-versus-encoding (§15) — so `|K| = 11` is stable exactly as long as the transition universe is.**
⛔ **And the check must test admissibility per `§3`, ⛔ never treat a describable sub-part as a capability
— which §3 names as the single most common false positive.**

```
[QUALIFIED]. THE DECOMPOSITION IS INDEPENDENTLY DERIVED — FAR MORE SO THAN P-79 IMPLIED.

Complete reading of P-09.3, all 474 lines, no range sampling. It supplies its own criterion, its own
counterexamples in both directions per split, a structural explanation, a stopping rule, a pairwise test
and a set-wise test. What it does not supply is semantic minimality, and it says so itself.

THE RELATION IS NOT UNDEFINED, AND IT IS NONE OF THE FIVE THE COMMISSION LISTED. §3 defines it
operationally: a capability decomposes iff there exist TWO admissible history-pairs, one varying each
part with the other fixed, where admissible means the varying transition lies in the CURRENT transition
universe and the requirement is [EMP] or [DERIVED]. That is independent variability under a witnessed
transition — not statistical independence, not abstract logical non-implication, not irreducibility. And
§3 forecloses the cheap answers: different objects, DDD categories, implementations, tables, fields,
names, and "the mere ability to describe sub-parts" are all insufficient as evidence.

THE STRUCTURAL RESULT IS THE STRONGEST THING IN THIS LANE'S RECORD: four capabilities were tested and all
four split the same way, so EVERY RETENTION CAPABILITY = ANTECEDENT RETENTION + LINK RETENTION, and "THE
KERNEL IS NOT A SET OF CAPABILITIES — IT IS A PRODUCT OF TWO INDEPENDENT INDIVIDUATION AXES": subject
(proposition, ground, reference) forced by P-09.2, and role (antecedent, link) forced by the eight
counterexamples. That is a stopping rule rather than a survival report — it "explains the counterexamples
instead of merely surviving them".

THE PROPAGATION TEST: [DOES NOT PROPAGATE]. K3a's witness is P-08 §17's insufficiency proof; K3b's is
§19's REPAIRED witness. P-67, P-69 and P-70 attacked W2 — K3a's witness — and not K3b's. §12 gives both
directions of non-implication, and §13 shows K3b is not set-wise redundant because "a warrant is an
adjudication, and P-08 §6's criterion forbids regenerating decisions". So K3b has its own witness AND its
own set-wise argument, and P-79's concern is REFUTED by a document predating it by 41 audits. The
commissioner's suspicion was right and my sentence was wrong.

THE THREE QUESTIONS KEPT APART: identity distinction ESTABLISHED for all eight; logical independence
ESTABLISHED pairwise and set-wise with exactly one conditional redundancy (K2, if K3 becomes universal);
evidential independence PARTIAL — each cell has its own counterexample, but every underlying requirement
traces to P-08. And P-09.3 says this about itself: both K3 requirements "were ALREADY IN P-08, stated as
two clauses of one capability without noticing they are independently variable."

WHAT REMAINS UNSUPPORTED: semantic minimality, which §14 states explicitly as in scope and NOT
established — alongside capability minimality as a LOWER BOUND ONLY, representation minimality as [ARCH]
and outside the lane, and Lane M's minimality as an unreconciled FOURTH sense. That separation has
existed since audit 23, and P-47, P-48, P-53, P-65 and P-66 argued about "minimality" without citing it.

DDD: the subject axis is a domain distinction, the role axis structural, neither derived from
architecture — but both [UNWITNESSED] as distinct domain invariants, since no invariant register maps
them.

Pre-registered [QUALIFIED]. Correct — record 24 of 35. No new backlog item; twelfth audit in sixteen
without one.

|K| = 11, [REC], UNFROZEN.

ONE NEXT UNRESOLVED QUESTION: Is there a THIRD individuation axis — and would the OPEN part of the
  transition universe (tau4, model replacement, semantic reinterpretation) supply it? §15 halts the
  recursion because "every further split requires a third individuation axis, and none is evidenced", and
  §2 explains why none is: a sub-part whose variation no current transition produces yields no admissible
  counterexample. So the halting is a function of the transition universe's CURRENT membership, and that
  universe is explicitly descriptive and not closed. If tau4 or semantic reinterpretation is admitted, K2
  decomposes and the antecedent cells become splittable on content-versus-encoding — so |K| = 11 is
  stable exactly as long as the transition universe is. And the check must test admissibility per §3,
  never treat a describable sub-part as a capability, which §3 names as the commonest false positive.

NO three_model_convergence INSPECTION — excluded by path from every command, unread.
```
