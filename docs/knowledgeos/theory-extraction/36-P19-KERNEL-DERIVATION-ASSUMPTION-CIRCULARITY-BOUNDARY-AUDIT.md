# `P-19` — Kernel Derivation: Assumption, Circularity & Boundary Audit

**2026-09-08 · Lane T · audit of the PROOF, not of the cells.** After [`35` `P-18`](./35-P18-KERNEL-IRREDUCIBILITY-AND-INDEPENDENCE-AUDIT.md).

> ⭐ **`P-18` established that the kernel resists reduction. `P-19` must establish that the PROOF of that
> irreducibility is sound.**
>
> ⛔ **`P-13`–`P-18` not reopened · no new kernel invented · no cell deleted · Schema v2 unmodified, v3
> not begun · no architecture · 3MC untouched · DDD, mathematics, statistics and implementation are
> never ontology · no witness silently repaired.**

# 1. Two findings up front

⭐⭐⭐ **Two of `P-18`'s eleven proofs do not survive this audit intact.** Both are recorded, neither is
repaired, and **no cell is deleted**:

| | finding |
|---|---|
| ⭐⭐⭐ **`K1`** | its **set-wise witness is CIRCULAR** and is **withdrawn**. `K1`'s independence survives — but on a **different kind of proof** (§4) |
| ⭐⭐ **`K5`** | its **statement** conflates a persistence obligation with an **environmental availability condition**. The cell survives **qualified** (§8) |

⚠️ ⭐ **Consequence for the canonical form:** `P-18` presented all eleven proofs **uniformly as minimal
pairs**. **Ten are minimal pairs; one is a presupposition argument.** That difference was obscured.

---

# 2. Provenance graph

| cell | witness | source | evidence level | depends on | circular? | boundary-safe? |
|---|---|---|---|---|:--:|:--:|
| **`K1`** | ⭐ *record content identical, denotation differs* | `P-18` §4 | **`[DERIVED]`** construction | ⭐⭐ **identity semantics** | ⭐⭐⭐ **YES — §3** | ⚠️ **witness withdrawn; cell survives via §4** |
| **`K2`** | monotone chain leaves no `K3` record | `P-11` §4.2 · `P-18` §4 | `[DER-S:R0]` | ⭐ **`R0`**, `K3` scope | 🔴 no | ⚠️ **`R0`-conditional** |
| **`K5`** | citation retained, repository gone | `P-18` §4 | `[DERIVED]`, arm `[EMP]` (`F-4`) | ⚠️ **an environmental condition** | 🔴 no | ⚠️ **statement qualified — §8** |
| **`K3a`** | re-disposition without the prior association | `P-18` §4 | `[DERIVED]` | **`P-08` §17** | 🔴 **no — §3** | ✅ |
| **`K3b`** | establishment with vs without a warrant | `P-17` §5 · `P-18` §4 | `[DERIVED]`, arms `[EMP]` (`C-022`/`G-67`) | **`P-08` §19** | 🔴 no | ✅ |
| **`K4a-i`** | relation kept, text deleted | `P-18` §4 | `[DERIVED]` | **`P-08` §5.2** | 🔴 no | ✅ |
| **`K4a-ii`** | three assertions, two topologies | `P-18` §4 | `[DERIVED]` | `K4a-i`, `K2` | 🔴 **no — §3** | ✅ **proof completed in §7** |
| **`K4b-i`** | invalidation kept, ground deleted | `P-18` §4 | `[DERIVED]`, arm `[EMP]` | `P-09.2` §4 | 🔴 no | ✅ |
| **`K4b-ii`** | replacement vs corroboration | `P-18` §4 | `[DERIVED]`, arm `[EMP]` | ⭐⭐ **grounds having NO current-state cell — §9** | 🔴 no | ⚠️ ⭐ **NEW conditionality** |
| **`K4c-i`** | *"something merged into `C`"* | `P-18` §4 | `[DER-S:Q1a]` **constructible** | ⭐ **`Q1a`** + **the `[EMP]` existence of citations** | 🔴 **no — §3** | ⚠️ `Q1a`-conditional |
| **`K4c-ii`** | merge vs 2 retirements + 1 establishment | `P-18` §4 | `[DER-S:Q1a]` **constructible** | **`Q1a`** | 🔴 no | ⚠️ `Q1a`-conditional |

⭐ **Witness evidence and the argument from it are kept apart throughout** — no Lane T derivation is
treated as corpus evidence.

# 3. Circularity audit

| suspected cycle | verdict |
|---|---|
| **`K3a`** — does the history assume prior-value persistence? | 🔴 **no.** The pair differs in **what was recorded**; the *obligation* comes from `P-08` §17's insufficiency proof, established independently |
| ⭐ **`K4c`** — does it presuppose `K4c`? | ⭐⭐ 🔴 **no, and the reason is load-bearing:** the loss in `H2` is **dangling citations**, and ⭐ **the existence of cross-references is `[EMP]` independently** (`P-14`: `Zero.D-03` and `K.D-03` **are** cited across records). ⚠️ **`K4c`'s non-circularity RESTS on that `[EMP]` fact** — recorded as a dependency |
| **relation cells** — is a relation "retained" merely because the capability says so? | 🔴 **no.** The **topology is a fact about what happened**, independent of whether it is recorded; the witness asks whether the record can distinguish two worlds |
| ⭐⭐⭐ **`K1`** — is identity used to prove identity? | ⭐⭐⭐ ✅ **YES — CIRCULAR. §4** |

# 4. ⭐⭐⭐ `K1` — the denotation argument fails, and a different proof takes over

## The circularity, stated exactly

`P-18`'s witness: *record content identical, denotation differs.*

$$\boxed{\begin{array}{c}\textbf{But WHAT FIXES the denotation of the term } x \textbf{ in } \textit{Changed}(x,a,b)?\\ \textbf{In } H_1 \textbf{ it denotes candidate-1; in } H_2 \textbf{ the merged thing. } \boxed{\textbf{That difference IS a difference of IDENTITY.}}\\[4pt] \textbf{So the witness distinguishes the histories BY the very thing } K1 \textbf{ was to establish. } \mathbf{CIRCULAR.}\end{array}}$$

## Alternative witnesses attempted — both fail on condition 2

| attempt | why it fails |
|---|---|
| `H1` two candidates each with one definition · `H2` one candidate with two | ⭐ 🔴 **`K2` DIFFERS** — the candidate population is part of current state |
| the `Q3` shape — `ℐ`/`𝓘` as two candidates vs one | 🔴 **same failure: `K2` differs** |

$$\boxed{\textbf{Every constructible } K1 \textbf{ pair either uses the circular denotation argument or CHANGES } K2\textbf{. No minimal-pair witness exists.}}$$

## ⭐⭐ What `K1`'s independence actually stands on

`[DERIVED]` **`P-09.3` §13's PRESUPPOSITION argument**: every retention record has the form
`Changed(x,a,b)` / `Supersedes(p,q)` / `RetiredInto(x,Y)`, and each requires a term to **denote the same
thing at record-time and at read-time**. **A presupposed premise cannot be a derived conclusion.**

| is the presupposition argument itself circular? | 🔴 **no** — it observes that the records are **unstatable** without denotation, which is a fact about **record FORM**, not about a preservation obligation. It then concludes non-derivability |
|---|---|

$$\boxed{\begin{array}{c}K1 \textbf{ is independent — established by a PRESUPPOSITION argument, NOT by a minimal pair.}\\ \boxed{\textbf{TEN cells rest on minimal pairs; ONE rests on a presupposition. } P\text{-}18 \textbf{ presented them uniformly.}}\end{array}}$$

⛔ **`P-18`'s witness is marked INVALID, not silently repaired. `K1` is NOT deleted.**

# 5. Witness duplication audit

⭐ **Terminology stripped; only the required LOSS compared.**

| pair | stripped statements | verdict |
|---|---|---|
| **`K3a` / `K4a-i`** | *retain the previous content of a thing that **survived*** vs *retain a thing that **did not survive*** | 🔴 **not duplicates** |
| **`K3b` / `K5`** | *keep our justification* vs *keep its external target inspectable* | 🔴 **not duplicates** — and §8 shows `K5` is not even the same **kind** of obligation |
| **`K4a-i` / `K4b-i`** | separated by **`Π2`** — identical claims **and** identical current warrants | 🔴 **not duplicates** |
| ⭐⭐ **`K4b-i` / `K4c-i`** | *keep the thing we stopped relying on* vs *keep the name we stopped using* | ⚠️ ⭐ **THE CLOSEST PAIR.** Separated by the **loss**: losing a withdrawn ground loses *which kind of failure*; losing a retired identity **dangles citations**. **Inspection vs resolution.** 🔴 not duplicates — **but the margin is thin, and that is recorded** |
| ⭐⭐⭐ **`K4a-ii` / `K4b-ii`** | both are *"did `B` replace `A`, or do they coexist?"* — ⚠️ **and coexistence is real: MD-017's `unresolved_equivalence` holds competing claims without supersession** | ⭐⭐⭐ 🔴 **not duplicates, and the reason is sharp: `K2` names the CURRENT ASSERTION but there is NO current-state cell for GROUNDS.** So `K4a-ii` can be partly reconstructed via `K2` *(the `n=2` case)* and `K4b-ii` cannot — ⭐ **which also EXPLAINS the `n=2` near-miss** |
| `K4c-ii` vs the other two `-ii` cells | *did these merge or vanish* — cardinality-bearing | 🔴 not duplicates |

# 6. The obligation universe, made explicit

| assumption | cells depending on it | type | if revoked |
|---|---|---|---|
| ⭐ **`R0` monotonicity** | **`K2`** | ⭐ **`[STIPULATED]`** | ⭐⭐ `K3` becomes universal ⇒ **`{K3a,K3b} ⊨ K2`** ⇒ **kernel 10** |
| ⭐ **`Q1a` retirement permitted** | **`K4c-i`, `K4c-ii`** | ⭐ **`[STIPULATED]`** | their witnesses lose instances ⇒ **both dormant** |
| **deletion-impermissibility for assertions** | **`K4a-i`, `K4a-ii`** | `[EMP]` — `P-08` §5.2 | `K4a-i`'s witness collapses |
| ⭐ **grounds have NO current-state cell** | ⭐⭐ **`K4b-ii`** | ⭐⭐⭐ **`[DERIVED]` — an absence, never explicitly decided** | ⭐ **`K4b-ii` would collapse into `K4a-ii`** — §9 |
| ⭐ **cross-references exist in the estate** | **`K4c-i`** | `[EMP]` — `P-14` | `K4c-i`'s loss becomes unobservable |
| **`UPDATED` ≠ `SUPERSEDED`** | **`K3a`** | ⭐ `[DERIVED]` from `[EMP]` — §10 | `K3a → K4a-i` reopens |
| **reconstructible ≠ obligated** | all `-ii` cells | `[DERIVED]` — `P-16` | the `-ii` cells reopen |
| ⭐ **record form `Changed(x,a,b)`** | ⭐⭐ **`K1`** | `[DERIVED]` | ⭐ **`K1`'s only surviving proof lapses** |

⛔ **A dependency audit, not a proposal to revoke anything.**

# 7. ⭐⭐ `K4a-ii` — the canonical minimal counterexample, completed

**Three assertions `p₁, p₂, p₃`; current `= p₃`; warrant content identical throughout.**

$$T_{\text{chain}}: p_1 \sqsupset p_2,\; p_2 \sqsupset p_3 \qquad\qquad T_{\text{fan}}: p_1 \sqsupset p_3,\; p_2 \sqsupset p_3$$

**Retained state excluding `K4a-ii`:** `K4a-i = {p₁,p₂,p₃}` · `K2 = p₃` · `K3b`/`K4b-ii` content `= W`.

$$\boxed{\begin{array}{c}\textbf{That retained state is the SAME OBJECT } (\{p_1,p_2,p_3\},\, p_3,\, W) \textbf{ in both histories.}\\ \boxed{\textbf{Therefore NO function of the retained state can differ on them. The proof is complete, not merely unwitnessed.}}\end{array}}$$

⭐ **And the distinction is REQUIRED** *(§8's step `C`)*: `T_chain` means **`p₂` was once current and then
corrected**; `T_fan` means **`p₂` was never current**. **`P-08` §5.2's ground — a superseded assertion is
evidence about the process's reliability — makes *"was `p₂` ever relied upon?"* exactly the required
question.**

⚠️ **`n=2` confirmed derivable:** with `{p₁,p₂}` and `K2 = p₂`, the only topology is `p₁ ⊐ p₂`.

---

# 8. ⭐⭐ `K5` — the audit `§11` demanded, and the statement must be qualified

`P-18` states `K5` as *"the current external ground remains checkable."*

$$\boxed{\begin{array}{c}\textbf{But the estate CANNOT MAKE an external repository exist. An obligation it cannot discharge is}\\ \textbf{an ENVIRONMENTAL AVAILABILITY CONDITION, not a persistence obligation.}\end{array}}$$

⭐⭐ **What the estate can discharge — and `P-08` §5.3 already derived both options:** *either persist
enough cited content to keep the claim checkable, **or** mark the definition's grounding as
pointer-only.*

$$\boxed{\begin{array}{c}\boxed{K5\textbf{'s obligation is: THE ESTATE MUST NOT CLAIM CHECKABLE GROUNDING IT DOES NOT HAVE.}}\\ \textbf{That is a persistence obligation about the estate's own record of GROUNDING STATUS.}\end{array}}$$

⚠️ ⭐ **The cell survives; its STATEMENT is qualified.** ⛔ **And this is exactly why an environmental
dependency must not become an architecture requirement.** `F-4` remains the live instance: the citation
is retained, the repository is unreachable, and the estate has **not** downgraded the grounding.

# 9. ⭐⭐⭐ `K4b-ii` — a reduction `P-18` never tested

⭐ **`P-09` §6 established that WARRANTS ARE ASSERTIONS.** So: could `g₁ ⊐ g₂` be handled by **`K4a-ii`
applied to warrants-as-assertions**?

| step | result |
|---|---|
| `K4a-ii`'s machinery needs, for the `n=2` reduction, a **current-state** designation | ⭐ supplied by `K2` **for assertions** |
| is there a **current-state cell for GROUNDS**? | ⭐⭐⭐ 🔴 **NO — the kernel has none** |

$$\boxed{\begin{array}{c}K4b\text{-ii} \textbf{ SURVIVES — but its distinctness is STRATIFICATION-DEPENDENT.}\\ \boxed{\textbf{If grounds ever acquired a current-state cell, } K4b\text{-ii would COLLAPSE into } K4a\text{-ii.}}\end{array}}$$

⭐⭐ **This is `P-17`'s stratification finding biting a specific cell**: `K4a-ii` and `K4b-ii` are **the
same obligation at different strata**, kept apart only by an **absence** in the kernel — and that
absence was never explicitly decided. ⭐ **Recorded as a THIRD conditionality**, alongside `R0` and
`Q1a`.

# 10. `UPDATED` ≠ `SUPERSEDED` — classified

| question | answer |
|---|---|
| corpus fact, stipulated rule, or Lane T design decision? | ⭐⭐ **`[DERIVED]` from `[EMP]` witnesses** — `P-07` §8, on `Zero`-as-a-state going `[RF]` **while `Zero` persists**, and on retained withdrawals. ⛔ **Not stipulated. Not a design choice** |
| is it admissible at the level `P-18` used it? | ⭐ ✅ **yes — level-consistent.** `P-18`'s reduction refutations are themselves `[DERIVED]`, so a `[DERIVED]` premise is legitimate |
| ⚠️ does it qualify as **corpus fact**? | 🔴 **no — it is Lane T derived**, and is not used as one |

⇒ ⭐ **`K3a`'s independence proof stands.** ⛔ **The distinction was not silently assumed.**

# 11. Reconstructibility audit — the seven questions per `-ii` cell

| | `K4a-ii` | `K4b-ii` | `K4c-ii` |
|---|---|---|---|
| **1** reconstructible? | ⚠️ **at `n=2` only** | 🔴 **no** | 🔴 **no** |
| **2** from what? | `K4a-i` + `K2` | — | — |
| **3** lossless? | ✅ at `n=2` | — | — |
| **4** preserves identity? | ✅ | — | — |
| **5** preserves warrant semantics? | ✅ | — | — |
| **6** ⭐ **guaranteed under the boundary?** | ⭐ 🔴 **NO — `n≥3` breaks it** | 🔴 no | 🔴 no |
| **7** ⭐⭐ **is the RELATION required, or only the information it is derivable from?** | ⭐⭐⭐ **the RELATION — §7 proves no function of the retained state suffices** | **the relation** — §9 | **the relation** — merge vs vanish |

$$\boxed{\textbf{⛔ "Not reconstructible in the constructed witness" was NOT taken as "must be persisted" — §7 supplies the impossibility proof.}}$$

# 12. Mathematical consistency audit

| object | used consistently? |
|---|---|
| **identity** | ⚠️ ⭐ **NO — and §4 is the finding.** `P-18`'s `K1` witness used identity **as denotation**; `P-09.3` used it **as `F-CONT` sameness**. ⭐⭐ **The slide is exactly the circularity** |
| **denotation** | ⭐ now confined to the presupposition argument |
| **relation / graph edge** | ✅ consistent — `P-16` §14 |
| **path** | ✅ derived, never stored |
| **transition / successor** | ✅ per-element, `P-15` §6 |
| **equivalence** | ⭐ ⛔ **untouched — `≡_sem` remains `Q7`** |
| **containment** | ✅ `P-06`'s criterion, unused here |
| **cardinality** | ✅ a parameter, `P-09.3` §10 |

⭐⭐ **One accidental change of meaning found, and it is the one that mattered.** ⛔ No mathematical object
became ontology.

# 13. Sensitivity analysis
⛔ **No sampling model; no significance claimed.**

| perturbation | does the obligation distinction survive? |
|---|:--:|
| **relabel all cells** | ✅ — witnesses are histories, not names |
| **alternative witness representations** | ✅ for 10; ⭐ **`K1` FAILED this test — its representation was doing the work** |
| **alternative decomposition order** | ✅ — `P-18` §5's fourteen refutations are order-independent |
| **alternative grouping** *(merge `K3a`/`K4a-i`, or the three `-ii` cells)* | ✅ — §5's stripped comparisons hold |

$$\boxed{\textbf{The result is robust under re-coding for 10 of 11 cells. } K1 \textbf{'s sensitivity failure IS §4's circularity, detected a second way.}}$$

⭐ **Two independent audits converging on the same defect is the strongest signal in this artifact.**

# 14. DDD audit
> *Would the domain obligation remain if all implementation concepts were removed?*

✅ **for ten cells** — each is a statement about what the estate must be able to say.
⚠️ ⭐ **`K5` is the exception**: *"the repository is reachable"* is an **implementation/environment**
dependency. ⭐⭐ **§8's qualified form removes it** — *"do not claim grounding you do not have"* is
implementation-free. ⛔ **No DDD term replaces any cell.**

---

# 15. Final classification

| cell | independence | boundary dependency | evidence level | circularity | duplicate risk | final status |
|---|---|---|---|:--:|---|---|
| **`K1`** | ⭐⭐ **by PRESUPPOSITION, not minimal pair** | record form `Changed(x,a,b)` | `[DERIVED]` | ⭐⭐⭐ **witness INVALID** | low | ⭐ **INDEPENDENT — proof method REPLACED** |
| **`K2`** | ✅ | ⭐ **`R0`** | `[DER-S:R0]` | 🔴 | low | ⭐ **CONDITIONAL ON `R0`** |
| **`K3a`** | ✅ | `UPDATED ≠ SUPERSEDED` `[DERIVED]` | `[DERIVED]` | 🔴 | low | **UNCONDITIONAL** |
| **`K3b`** | ✅ **exactly isolated** | — | `[DERIVED]`, arms `[EMP]` | 🔴 | low | ⭐ **UNCONDITIONAL — the strongest proof in the kernel** |
| **`K4a-i`** | ✅ | deletion-impermissibility `[EMP]` | `[DERIVED]` | 🔴 | ⚠️ vs `K4b-i` | **UNCONDITIONAL** |
| **`K4a-ii`** | ✅ **impossibility proof, §7** | `n≥3` | `[DERIVED]` | 🔴 | ⚠️ vs `K4b-ii` | ⭐ **UNCONDITIONAL for `n≥3`** |
| **`K4b-i`** | ✅ | — | `[DERIVED]`, arm `[EMP]` | 🔴 | ⭐ **closest pair: `K4c-i`** | **UNCONDITIONAL** |
| **`K4b-ii`** | ✅ | ⭐⭐ **grounds have no current-state cell** | `[DERIVED]` | 🔴 | ⚠️ vs `K4a-ii` | ⭐⭐ **DEPENDENT ON A KERNEL ABSENCE** |
| **`K4c-i`** | ✅ | ⭐ **`Q1a`** + citations exist `[EMP]` | `[DER-S:Q1a]`, **`E3`** | 🔴 | low | ⭐ **CONDITIONAL ON `Q1a`** |
| **`K4c-ii`** | ✅ | ⭐ **`Q1a`** | `[DER-S:Q1a]`, **`E3`** | 🔴 | low | ⭐ **CONDITIONAL ON `Q1a`** |
| **`K5`** | ✅ **qualified** | ⚠️ **statement corrected — §8** | `[DERIVED]`, arm `[EMP]` | 🔴 | low | ⭐ **UNCONDITIONAL, RESTATED** |

## Evidence levels, kept apart
**`E1` corpus observation** — `Zero` changed meaning · `Zero` retains two `[RF]` definitions ·
`C-022`/`G-67` · `F-4` · citations exist · corpus `t₃` split.
**`E2` corpus-attested arm in a constructed pair** — `K3b`, `K5`, `K4b-i`, `K4b-ii`.
**`E3` purely constructible** — ⭐ **`K4c-i`, `K4c-ii`** *(retirement permitted-but-unwitnessed; merge
scheduled-but-unwitnessed)*.
⛔ **`E1`, `E2` and `E3` are not merged, and no cell is marked `[EMP]`.**

# 16. Verdict

$$\boxed{\textbf{B — KERNEL VALID BUT QUALIFIED. 11 cells. ⛔ No cell invalidated, no cell deleted, no kernel change.}}$$

⭐ **Why not `A`:** two proofs did not survive intact — `K1`'s witness is **circular** and `K5`'s
**statement** conflated an obligation with an environmental condition. ⭐ **Why not `C`:** `K1`'s cell
survives on an **independent presupposition argument**, and `K5` survives **restated**; no cell lost its
independence. ⭐ **Why not `D`:** the obligation universe is **consistent** — §6 enumerates eight
assumptions, all classified, none invalid.

---

# 17. Required final statements

**1 · Is the 11-cell kernel epistemically validated?** — ⭐ **YES, as a BOUNDARY-RELATIVE, QUALIFIED
derived lower bound.** ⛔ **Not universal, not architectural.** ⚠️ **And its canonical form must name
the boundary: `11 cells UNDER R0 AND Q1a`.**

**2 · Unconditional cells** — ⭐ **five: `K3a` · `K3b` · `K4a-i` · `K4a-ii` (for `n≥3`) · `K4b-i`**, plus
⭐ **`K5` unconditional once RESTATED** (§8) = **six**.

**3 · Conditional cells, and on what** — **`K2` on `R0`** *(revocation ⇒ kernel 10)* · **`K4c-i` and
`K4c-ii` on `Q1a`**, and both **`E3` constructible only** · ⭐⭐ **`K4b-ii` on a KERNEL ABSENCE — grounds
having no current-state cell**, which was never explicitly decided · ⭐ **`K1` on the record form
`Changed(x,a,b)`.**

**4 · Did any witness fail?** — ⭐⭐⭐ **YES. `K1`'s set-wise witness is CIRCULAR** — the denotation
difference *is* an identity difference, so identity proved identity — **and every alternative pair
changes `K2`, violating condition 2.** ⭐ **It is marked invalid, not repaired**, and **`K1` now rests on
a presupposition argument.** ⭐⭐ **`K5`'s witness stands but its STATEMENT was qualified.**

**5 · Did any cell become genuinely reducible?** — ⛔ **NO.** ⚠️ **Two bounded dependences persist**
(`K4a-ii` at `n=2`; `K2` under `R1`) and ⭐ **one new one is found: `K4b-ii` would collapse into `K4a-ii`
if grounds acquired a current-state cell.**

**6 · What remains theoretically open** — ⭐ **whether grounds should have a current-state cell** *(new,
and it would reduce the kernel to 10)* · **`O-P17-1`** *(any generative structure?)* · **`B4`** ·
**kernel identity** · **equivalence `Q7`** · **aggregate existence** · **`W6`'s mechanism `[ARCH]` and
its live failure** · **`O-INT001-1`'s governance rule** · **`O-INT002-1`** · **provenance chains** ·
**alternate histories** · **`Q3`/`Q8`** · **`Q4`** · **`M6`'s occurrence** · **`K4b-ii`'s relation/defect
split** · `F-4`/`F-5` unrepaired.

**7 · Safe to freeze?** — ⭐⭐ **YES, and only in this form:**

$$\boxed{\begin{array}{c}\textbf{11 CELLS, UNDER } R0 \textbf{ AND } Q1a\textbf{, established by TEN MINIMAL PAIRS AND ONE PRESUPPOSITION ARGUMENT,}\\ \textbf{with } K5 \textbf{ RESTATED, } K4b\text{-ii DEPENDENT ON A KERNEL ABSENCE, and } K4c \textbf{ CONSTRUCTIBLE-ONLY } (E3).\end{array}}$$

⛔ **A freeze that omitted any of those five qualifications would make the kernel stronger than its
evidence.**

**8 · Non-kernel gates still blocking architecture** — **Gate `C`** *(transition closure — `M6`
unclassified)* · **Gate `D`** *(`W6`'s mechanism, and its live empirical failure)* · **Gate `E`**
*(cross-lane admissions, **2 of 8**)*. ⭐ **None is a kernel question.**

---

```
B — KERNEL VALID BUT QUALIFIED. 11 CELLS, UNDER R0 AND Q1a. NO CELL INVALIDATED, DELETED OR CHANGED.

TWO PROOFS DID NOT SURVIVE INTACT — recorded, not repaired:

  K1 — ITS SET-WISE WITNESS IS CIRCULAR. P-18 argued "same record content, different denotation". But
  what fixes the denotation of x in Changed(x,a,b)? In H1 it denotes candidate-1, in H2 the merged
  thing — and THAT DIFFERENCE IS A DIFFERENCE OF IDENTITY. Identity proved identity. Every alternative
  pair attempted (two candidates vs one; the Q3 shape) CHANGES K2, violating condition 2, because the
  candidate population is part of current state. So NO MINIMAL-PAIR WITNESS EXISTS for K1.
  K1's independence nevertheless SURVIVES — on P-09.3 §13's PRESUPPOSITION argument: every retention
  record requires a term to denote the same thing at record-time and read-time, so identity is
  PRESUPPOSED by the record FORM and cannot be a derived conclusion. That argument is not itself
  circular, because it concerns record form rather than a preservation obligation.
  CONSEQUENCE FOR THE CANONICAL FORM: P-18 presented all eleven proofs uniformly as minimal pairs.
  TEN ARE MINIMAL PAIRS; ONE IS A PRESUPPOSITION ARGUMENT. And the sensitivity analysis (§13) failed
  for K1 alone — two independent audits converging on the same defect.

  K5 — ITS STATEMENT CONFLATED AN OBLIGATION WITH AN ENVIRONMENTAL CONDITION. "The current external
  ground remains checkable" is something the estate CANNOT DISCHARGE; it cannot make a repository
  exist. What it can discharge is P-08 §5.3's pair: persist enough cited content, OR mark the grounding
  pointer-only. So K5's obligation is restated: THE ESTATE MUST NOT CLAIM CHECKABLE GROUNDING IT DOES
  NOT HAVE. Cell survives; statement qualified. F-4 is the live instance — citation retained, repository
  unreachable, grounding not downgraded.

A REDUCTION P-18 NEVER TESTED, found here: since P-09 §6 established that WARRANTS ARE ASSERTIONS,
  K4b-ii could be handled by K4a-ii applied to warrants-as-assertions — except that K4a-ii's n=2
  reduction needs a CURRENT-STATE designation, and THE KERNEL HAS NO CURRENT-STATE CELL FOR GROUNDS.
  K4b-ii therefore survives, but its distinctness is STRATIFICATION-DEPENDENT: if grounds ever acquired
  a current-state cell, K4b-ii would COLLAPSE INTO K4a-ii. A THIRD conditionality, alongside R0 and
  Q1a, resting on an ABSENCE that was never explicitly decided. This also EXPLAINS the n=2 near-miss:
  K2 names the current ASSERTION and nothing names the current GROUND.

K4a-ii's CANONICAL COUNTEREXAMPLE COMPLETED: three assertions p1,p2,p3 with p3 current and identical
  warrant content; T_chain (p1>p2>p3) versus T_fan (p1>p3, p2>p3). The retained state excluding K4a-ii
  is the SAME OBJECT ({p1,p2,p3}, p3, W) in both, so NO FUNCTION of it can differ — an impossibility
  proof, not an absence of witness. And the distinction is REQUIRED: T_chain means p2 was once relied
  upon, T_fan that it never was, which is exactly P-08 §5.2's process-reliability ground.

CIRCULARITY: one cycle found (K1). K4c is NON-circular only because THE EXISTENCE OF CROSS-REFERENCES
  IS [EMP] INDEPENDENTLY (Zero.D-03 and K.D-03 are cited) — recorded as a dependency. Relation cells are
  non-circular because a TOPOLOGY IS A FACT ABOUT WHAT HAPPENED, independent of being recorded.

DUPLICATION: no duplicates. Closest pair K4b-i / K4c-i, separated by INSPECTION vs RESOLUTION — margin
  thin, recorded. K4a-ii / K4b-ii ask the same question shape ("replace or coexist?" — and coexistence
  is real, MD-017's unresolved_equivalence) yet differ sharply because K2 covers assertions and nothing
  covers grounds.

CONSISTENCY: one accidental change of mathematical meaning found — "identity" used as DENOTATION in
  P-18's K1 witness and as F-CONT SAMENESS in P-09.3. That slide IS the circularity. ≡_sem untouched.

UNCONDITIONAL (6): K3a · K3b (the strongest proof in the kernel — K3a empty in both arms) · K4a-i ·
  K4a-ii (n>=3) · K4b-i · K5 (restated).
CONDITIONAL (5): K2 on R0 (revocation => kernel 10) · K4c-i, K4c-ii on Q1a and E3-constructible only ·
  K4b-ii on a KERNEL ABSENCE · K1 on the record form Changed(x,a,b).
EVIDENCE LEVELS KEPT APART: E1 corpus observation · E2 corpus-attested arm in a constructed pair ·
  E3 purely constructible. No cell is marked [EMP].

SAFE TO FREEZE — AND ONLY IN THIS FORM: 11 cells, UNDER R0 AND Q1a, established by TEN MINIMAL PAIRS
  AND ONE PRESUPPOSITION ARGUMENT, with K5 RESTATED, K4b-ii DEPENDENT ON A KERNEL ABSENCE, and K4c
  CONSTRUCTIBLE-ONLY. A freeze omitting any of those five qualifications would make the kernel stronger
  than its evidence.

NEWLY OPEN: should GROUNDS have a current-state cell? It would reduce the kernel to 10.

GATES STILL BLOCKING ARCHITECTURE, none of them kernel questions: C (M6 unclassified) · D (W6's
  mechanism and its live empirical failure) · E (cross-lane admissions, 2 of 8).

NO ARCHITECTURE SELECTED — SCHEMA v3 NOT BEGUN — 3MC UNTOUCHED.
```
