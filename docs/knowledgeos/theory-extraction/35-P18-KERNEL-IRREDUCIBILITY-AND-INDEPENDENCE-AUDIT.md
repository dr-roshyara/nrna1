# `P-18` — Kernel Irreducibility & Independence Audit

**2026-09-08 · Lane T.** After [`34` `P-17`](./34-P17-TWO-AXIS-STRUCTURAL-CLAIM-AUDIT.md).

> **Null hypothesis:** the 11 cells are independently necessary. **The objective is to FALSIFY the
> 11-cell lower bound.**
>
> ⛔ **`P-13`–`P-17`, `Q1`, `W6`, `Q2`, `R0` not reopened · no new axis · no replacement taxonomy ·
> Schema v2 unmodified, v3 not begun · no architecture, event sourcing, temporal/graph storage,
> repository, aggregate or model registry · 3MC / Lane M untouched · DDD, mathematics, statistics and
> implementation are never ontology · no cell changed without an admissible counterexample.**
>
> ⭐ **`P-17`'s result is preserved and NOT rescued:** `SUBJECT × ROLE` is **classificatory only**.

# 1. "Irreducible", defined

$$\boxed{K_i \textbf{ has an INDEPENDENCE WITNESS iff } \exists\, H^1_i \neq H^2_i \textbf{ with } K_i(H^1) \neq K_i(H^2) \textbf{, EVERY } K_j\ (j \neq i) \textbf{ identical, and the distinction a required obligation.}}$$

⛔ **Absence of a witness does NOT make a cell redundant** — it makes it `[OPEN]`.

# 2. Three results, kept apart
**`[DERIVED-INDEPENDENT]`** a minimal witness isolates the cell · **`[DERIVED-REDUNDANT]`** a formal
reduction shows every obligation is already represented · **`[OPEN]`** neither.
⛔ **`B` and `C` are not collapsed. *"Not independently witnessed"* ≠ *"redundant."***

# 3. ⭐ Logical separation enforced first

$$\boxed{\neg(\textbf{SUBJECT} \times \textbf{ROLE generation}) \not\Rightarrow \textbf{irreducibility} \qquad \neg\textbf{ThirdAxis} \not\Rightarrow \textbf{irreducibility}}$$

⭐⭐ **`P-17` refuted one candidate EXPLANATION of the kernel. `P-16` closed one candidate DIMENSION.
Neither bears on whether the eleven obligations are independent** — that is tested below from scratch,
and neither implication is used anywhere in this artifact.

---

# 4. The eleven set-wise witnesses

⛔ **Each pair is constructed. Conceptual examples alone are not used.**

## Present-state family

### `K1` — identity preservation
| | `H1` | `H2` |
|---|---|---|
| two live candidates | ⭐ **distinct identities** | ⭐ **collapsed into one** — `W1` |
| all 10 other cells | ⭐⭐ **identical in CONTENT** — the same retention records exist as records | **identical** |
| ⭐ what differs | ⭐⭐⭐ **their DENOTATION** — the records now pick out ambiguously |
| required? | ✅ **yes** — two candidates become indistinguishable |

⭐⭐ **The subtlety that makes the test valid:** `P-09.3` §13 showed every retention record *presupposes*
identity, which appears to make the pair unconstructible. **It does not** — the records' **content** is
identical across `H1` and `H2`; only what they **denote** differs. ⇒ **`[DERIVED-INDEPENDENT]`.**

### `K2` — current-state preservation
⭐ **Reuses `P-11` §4.2's countermodel:** a candidate advancing `candidate → supported → corroborated`
produces **no `K3` record** under `R0`, so `H1`(corroborated) and `H2`(supported) are identical in all
ten others while the estate must distinguish them.
⚠️ ⭐⭐ **`[DERIVED-INDEPENDENT]`, CONDITIONAL ON `R0`.** Under `R1` a universal `K3` restores the trace
and `{K3a,K3b} ⊨ K2` — `P-11` §6. **The only cell whose independence is stipulation-dependent.**

### `K5` — warrant checkability
| | `H1` | `H2` |
|---|---|---|
| `Σ` `D-05` cited to `code@kosmodel.py:Sigma` | ⭐ repository **present** | ⭐ repository **gone** |
| all 10 other cells | **identical** — same citation string, same everything | **identical** |
| required? | ✅ `P-13`'s proposition **`A` `[DERIVED]`** — a claim's ground must remain inspectable |

⇒ **`[DERIVED-INDEPENDENT]`**, and `F-4` is the live corpus case.

## Retention family — the `-i` cells

### `K3a` — prior-value retention
`H1`: a re-disposition recording the **prior association**; `H2`: the same re-disposition **without** it.
⭐ **`K3b` is identical in both** — a warrant such as *"the containment argument"* does **not name the
prior value**, so warrant identity is achievable. Required by **`P-08` §17's insufficiency proof**.
⇒ **`[DERIVED-INDEPENDENT]`.**

### `K3b` — warrant retention
⭐⭐ **Two independent witnesses, and the second isolates better:**
1. `resolved` recorded **with** vs **without** a warrant — `P-08` §19's repaired witness.
2. ⭐⭐⭐ **`P-17` §5's establishment pair** — a `0→1` transition where **`K3a` is EMPTY IN BOTH ARMS**,
   so the isolation is exact. `[EMP]` arms: **`C-022` establishes with a warrant, `G-67` with none.**
⇒ **`[DERIVED-INDEPENDENT]`.**

### `K4a-i` — prior proposition retention
`H1`: the superseded assertion's **text** retained + the relation; `H2`: the relation retained
*("something was superseded")* with the **text deleted**. ⭐ Required by **`P-08` §5.2** — a superseded
assertion is evidence about the **process's reliability**, which needs its **content**.
⇒ **`[DERIVED-INDEPENDENT]`.**

### `K4b-i` — withdrawn ground retention
`H1`: the withdrawn warrant retained; `H2`: only the invalidation retained. ⭐ Required — without the old
ground's content the estate cannot tell **which kind** of failure occurred (`P-09.2` §4).
⇒ **`[DERIVED-INDEPENDENT]`**, with the `Closure(𝒦₉)` case as its live instance.

### `K4c-i` — retired-identity retention
`H1`: the retired identity retained + `retired-into`; `H2`: only *"something merged into `C`"*.
⭐ Required by **`W1`** — citations to the predecessor dangle.
⚠️ **`[DERIVED-INDEPENDENT]`, conditional on `Q1a` `[DER-S:Q1a]`**, and ⭐ **the pair is CONSTRUCTIBLE,
not corpus-attested** — `P-16` §19 found retirement **permitted but unwitnessed**.

## ⭐⭐ The `-ii` cells — `P-16`'s reconstruction challenge applied

⭐ `P-16` established that **paths, reachability and transitive closure are DERIVED, never stored.** So
each relation cell must survive the question: *can it be reconstructed from its endpoints?*

### `K4a-ii` — supersession relation
| attempt | result |
|---|---|
| **two** retained assertions, one current | ⭐⭐ **RECONSTRUCTIBLE** — `K2` names the current one, so the other was superseded by it |
| ⭐⭐⭐ **three** retained assertions `p₁,p₂,p₃`, with `p₃` current | ⭐⭐⭐ **NOT reconstructible** — `p₁ ⊐ p₂ ⊐ p₃` *(a chain)* and `p₁ ⊐ p₃`, `p₂ ⊐ p₃` *(two independent corrections)* have the **same texts, the same current state and the same warrant content** |

⭐ **The two-assertion case nearly reduced this cell** — and the three-assertion case is the minimal
counterexample that saves it. ⇒ **`[DERIVED-INDEPENDENT]`**, and ⚠️ **the reduction succeeds for `n=2`,
which is worth recording as a genuine near-miss.**

### `K4b-ii` — invalidation relation
Two grounds `g₁`, `g₂` for the same claim, **both retained**. ⭐⭐ Is `g₂` a **replacement** of `g₁`
*(invalidation)* or a **second corroborating** ground *(both valid)*?
⭐⭐⭐ **Not reconstructible — and this is exactly the `Closure(𝒦₉)` distinction**: there the warrant was
**replaced** by a strictly stronger computation (14 → 66 worlds), whereas a corroborating ground would
leave both standing. ⇒ **`[DERIVED-INDEPENDENT]`.**

### `K4c-ii` — `retired-into` relation
Two retired identities `A`, `B`, one live `C`. ⭐⭐ Did `A,B → C` *(a merge)*, or were `A` and `B` each
retired with **no successor** (`1→0` twice) while `C` was **independently established** (`0→1`)?
⭐⭐⭐ **Not reconstructible from identities alone** — it is the *retired-vs-never-existed* shape one
level up. ⇒ **`[DERIVED-INDEPENDENT]`**, ⚠️ **conditional on `Q1a`, constructible not attested.**

---

# 5. Reduction attempts

| candidate | proposed basis | counterexample | result |
|---|---|---|---|
| `K3a → K3b` | a warrant implies its prior value | ⭐ *"the containment argument"* names **no** prior value | 🔴 **refuted** |
| `K3b → K3a` | a prior value implies its justification | ⭐ `resolved` recorded with **no** warrant (`P-08` §19) | 🔴 **refuted** |
| `K4a-i → K4a-ii` | the relation implies the text | ⭐ *"something was superseded"* with the text deleted | 🔴 **refuted** |
| `K4a-ii → K4a-i` | ⭐⭐ **retained texts + `K2` reconstruct the relation** | ⭐⭐⭐ **three assertions, two topologies** — §4 | ⚠️ **refuted, but SUCCEEDS for `n=2`** |
| `K4b-i → K4b-ii` | the invalidation implies the ground | which **kind** of failure is unrecoverable | 🔴 **refuted** |
| `K4b-ii → K4b-i` | retained grounds reconstruct invalidation | ⭐ **replacement vs corroboration** | 🔴 **refuted** |
| `K4c-i → K4c-ii` | the relation implies the predecessor | *"something merged into `C`"* — `W1` survives | 🔴 **refuted** |
| `K4c-ii → K4c-i` | retained identities reconstruct the relation | ⭐ **merge vs two retirements + one establishment** | 🔴 **refuted** |
| `K1 → others` | identity from records | ⭐⭐ **records are INDEXED BY identity — replay presupposes it** (`P-09.3` §13) | 🔴 **refuted** |
| ⭐ `K2 → others` | current state by replay | ⭐⭐⭐ **succeeds ONLY under `R1`** — monotone steps leave no record under `R0` | ⚠️ **refuted UNDER `R0`; would succeed under `R1`** |
| `K5 → others` | internal retention implies external checkability | ⭐ **no internal record entails an external repository's existence** | 🔴 **refuted** |

## ⭐⭐ Three reductions attempted beyond the listed set

| candidate | argument | result |
|---|---|---|
| ⭐⭐ **`K3a → K4a-i`** | *if the estate ASSERTED each field value, prior values would live in retained assertions* | ⭐⭐⭐ 🔴 **refuted by `P-08` §8: `UPDATED` items (candidate, definition, association) are UPDATED, never SUPERSEDED — so their prior states are NOT assertions.** A genuinely different reduction from the listed ones |
| ⭐ **`K5 → K4b-i`** | *if every external ground were retained as an extract, checkability would follow* | 🔴 **refuted** — `K4b-i` covers **withdrawn** grounds; `K5` covers the **current** one |
| ⭐⭐ **`K1 → K4c-i`** | *K1 is just `K4c-i` applied to non-retired things* | ⭐ 🔴 **refuted** — `K4c-i` is **retention of a PAST identity**; `K1` is **present continuity**. `P-09.1` §5: `K1` preserves while `K4c` **retires**. Complementary, not derivable |

⛔ **In no case was co-occurrence mistaken for entailment.**

---

# 6. Present-state vs retention boundary — is the boundary itself forced?

| history | constructible? |
|---|:--:|
| current-state preservation **fails**, retention **survives** | ✅ **yes** — `K2`'s witness with all retention intact |
| retention **fails**, current-state **survives** | ✅ **yes** — every `-i` witness above |
| **warrant checkability fails while warrant CONTENT remains** | ⭐⭐ ✅ **yes — `F-4`**: the citation text is retained; the repository is unreachable |
| **warrant content fails while checkability remains** | ⭐ ✅ **yes** — a live source with the estate's own record of it deleted |

$$\boxed{\textbf{All four vary independently } \Rightarrow \textbf{the present-state / retention boundary HAS an independence witness. It is not merely descriptive.}}$$

⭐⭐ **Note what this does NOT rescue:** the boundary being **forced** says nothing about `SUBJECT ×
ROLE` being **generative** — `P-17` stands. ⛔ **A forced partition is not a generative basis.**

# 7. `K3` vs `K4` — a real distinction?

| | history | required loss |
|---|---|---|
| **`H1`** | prior **value** lost, relation survives | *from what did it change?* — `P-08` §17 |
| **`H2`** | relation lost, prior **value** survives | *what superseded what?* — §4's three-assertion case |
| **`H3`** | warrant survives, prior value lost | *the change is justified but unlocatable* |
| **`H4`** | prior value survives, warrant lost | *adjudicated vs never in doubt* — `P-08` §19 |

$$\boxed{\begin{array}{c}\textbf{Four genuinely distinct required losses } \Rightarrow K3 \textbf{ and } K4 \textbf{ are NOT two descriptions of one obligation.}\\ \textbf{⭐ And the reason is structural: } K3 \textbf{ concerns a FIELD on a surviving thing; } K4 \textbf{ concerns a WHOLE PRIOR OBJECT.}\end{array}}$$

# 8–9. The three `K4` families, tested without their names

⛔ **`assertion`, `ground`, `reference` are not allowed to establish independence, and `P-17`'s finding
that *ground* is not a peer coordinate must not contaminate the test.** The histories alone:

| pair | required loss that separates them |
|---|---|
| `K4a-i` / `K4b-i` | ⭐⭐ **`Π2`** — identical claims **and** identical current warrants, differing only in the **withdrawn** ground. No assertion-level datum separates them |
| `K4a-i` / `K4c-i` | ⭐ **readability vs resolvability** — a retained text is **read**; a retired designator must **resolve** |
| `K4b-i` / `K4c-i` | ⭐ a withdrawn ground is **inspected**; a retired identity is **resolved through** |

⭐⭐ **`P-17` showed *ground* is a proposition at a higher LEVEL. That weakens the SUBJECT AXIS and
leaves the OBLIGATIONS untouched** — a level distinction is still a distinction, and `Π2` is a history,
not a label. ⇒ **all three families keep independent witnesses.**

# 10. Mathematical test — information dependence

Define `D_i(H1,H2) = 1` iff cell `i` distinguishes the histories.

| relation | verdict |
|---|---|
| **logical implication** `D_i ⇒ D_j` | ⭐ **found in ONE place only:** `D_{K4a\text{-}ii} \Leftarrow D_{K4a\text{-}i} \wedge D_{K2}` **for `n=2` assertions** — and it **fails at `n=3`** |
| **functional dependence** `D_i = g(\text{others})` | ⭐⭐ **found in ONE place only: `D_{K2} = g(D_{K3a},D_{K3b})` UNDER `R1`.** ⛔ **Not under `R0`** |
| **correlation / co-occurrence** | ⭐ frequent — `K4c` co-occurs with `K4a` (`P-09.2` §9), `K4c` with `K3b` (`INTAKE-002`) | ⛔ **irrelevant to redundancy** |
| **reconstructibility** | ⭐ paths, reachability, transitive closure — `P-16` | ⛔ **irrelevant: reconstructible ≠ derivable-as-obligation** |

$$\boxed{\begin{array}{c}\textbf{Exactly TWO dependence findings, both BOUNDED:}\\ \boxed{K4a\text{-ii is derivable at } n{=}2 \textbf{ and not at } n{=}3 \quad\cdot\quad K2 \textbf{ is derivable under } R1 \textbf{ and not under } R0}\end{array}}$$

⭐ ⛔ **Only the first two relations bear on redundancy, and neither yields an unconditional reduction.**

# 11. Statistical sanity check
⚠️ **No sampling model exists; ⛔ no significance is claimed. Model-complexity analogy only.**

| question | answer |
|---|---|
| are the 11 cells merely labels? | ⭐ 🔴 **no** — each has an independence witness that is a **history**, not a name |
| any duplicates under a different representation? | 🔴 **no** — §5's eleven refutations |
| does each add information **conditional on the others**? | ⭐ ✅ **yes** — that is precisely the set-wise test |
| are the witnesses independently informative? | ⭐ ✅ **yes** — eleven distinct required losses |
| ⭐ **is any proposed reduction merely notational compression?** | ⭐⭐ **the `n=2` supersession reduction IS** — it compresses a case, not an obligation |

$$\boxed{\textbf{compression of representation} \neq \textbf{reduction of obligations. ⛔ A compact formula is not evidence of redundancy.}}$$

⭐⭐ **Contrast with `P-17`:** there, descriptive fit with **zero** predictions and **no** identifiability
signalled overfitting **of the explanation**. Here each cell is identified by **its own witness** — the
*kernel* is identifiable even though its *explanation* is not.

# 12. DDD cross-check
⚠️ **Corroboration only.** Different domain obligations ✅ *(retain a value · justify a change · retain a
claim · keep a ground inspectable · keep a retired reference resolvable)*; two obligations **can** live
on one object ✅ *(a definition bears both `K3a` and `K4a-i`)* — ⭐ **which corroborates that the cells
are not individuated by their object**, exactly `P-17`'s finding. ⛔ **No aggregate, entity, repository
or event stream imported.**

---

# 13. Independence matrix

| cell | minimal witness | other 10 identical? | required loss | independent? | redundant? | status |
|---|---|:--:|---|:--:|:--:|---|
| **`K1`** | two live candidates collapse — `W1` | ✅ **in content; denotation differs** | two candidates indistinguishable | ✅ | 🔴 | **`[DERIVED-INDEPENDENT]`** |
| **`K2`** | `candidate→supported→corroborated`, no `K3` record | ✅ | current status unrecoverable | ⚠️ ✅ | ⚠️ **under `R1`** | ⭐ **`[DERIVED-INDEPENDENT]`, conditional on `R0`** |
| **`K5`** | citation retained, repository gone | ✅ | ground uninspectable | ✅ | 🔴 | **`[DERIVED-INDEPENDENT]`** · `F-4` live |
| **`K3a`** | re-disposition without the prior association | ✅ | *from what did it change?* | ✅ | 🔴 | **`[DERIVED-INDEPENDENT]`** |
| **`K3b`** | ⭐ **establishment with vs without a warrant, `K3a` empty in both** | ✅ **exactly** | adjudicated vs never in doubt | ✅ | 🔴 | **`[DERIVED-INDEPENDENT]`** |
| **`K4a-i`** | relation kept, text deleted | ✅ | process-reliability evidence | ✅ | 🔴 | **`[DERIVED-INDEPENDENT]`** |
| **`K4a-ii`** | ⭐ **three assertions, two topologies** | ✅ | which superseded which | ✅ | ⚠️ **at `n=2` only** | ⭐ **`[DERIVED-INDEPENDENT]`** — near-miss recorded |
| **`K4b-i`** | invalidation kept, ground deleted | ✅ | which **kind** of failure | ✅ | 🔴 | **`[DERIVED-INDEPENDENT]`** |
| **`K4b-ii`** | ⭐ **replacement vs corroboration** | ✅ | was the old ground invalidated | ✅ | 🔴 | **`[DERIVED-INDEPENDENT]`** |
| **`K4c-i`** | *"something merged into `C`"* | ✅ | dangling citations — `W1` | ⚠️ ✅ | 🔴 | ⭐ **`[DERIVED-INDEPENDENT]`, conditional on `Q1a`; CONSTRUCTIBLE not attested** |
| **`K4c-ii`** | ⭐ **merge vs 2 retirements + 1 establishment** | ✅ | retired-into vs never-existed | ⚠️ ✅ | 🔴 | ⭐ **`[DERIVED-INDEPENDENT]`, conditional on `Q1a`; constructible** |

⛔ **No cell marked `[EMP]`** — every witness is a **constructed history**, even where its arms are
corpus-attested. ⭐ **Corpus-attested arms:** `C-022`/`G-67` *(`K3b`)* · `F-4` *(`K5`)* ·
`Closure(𝒦₉)` *(`K4b-i`/`K4b-ii`)*.

# 14. Verdict

$$\boxed{\textbf{A — 11-CELL IRREDUCIBILITY ESTABLISHED, boundary-relative.}}$$

**Every one of the eleven cells has a set-wise independence witness, and all eleven listed reductions
plus three additional ones are refuted.**

⚠️ **Three caveats, documented rather than hidden:**
1. ⭐⭐ **`K2`'s independence is STIPULATION-DEPENDENT** — it holds under `R0` and **lapses under `R1`**,
   where `{K3a,K3b} ⊨ K2`. **The single most fragile cell.**
2. ⭐ **`K4c-i`/`K4c-ii`'s witnesses are CONSTRUCTIBLE, not corpus-attested** — retirement is permitted
   `[DER-S:Q1a]` but **unwitnessed**, and merge is **scheduled** but unwitnessed.
3. ⭐⭐ **`K4a-ii` was nearly reduced** — at `n=2` assertions it **is** derivable from `K4a-i` + `K2`;
   only the `n=3` topology case saves it.

⛔ **Verdict `A` does NOT mean no future generative structure exists** — `O-P17-1` stands.

# 15. Kernel consequence

$$\boxed{\textbf{KERNEL REMAINS 11 CELLS. ⛔ No change is justified — the audit FAILED to falsify the lower bound.}}$$

⭐ **And the failure is informative:** eleven independent attempts at reduction, three of them not on
the commissioned list, and the two dependence findings that surfaced are both **bounded** (`n=2`, `R1`)
rather than general.

---

# 16. Required final statements

**1 · What `P-17` established** — `[DERIVED]`: `SUBJECT × ROLE` is **classificatory only**, covering
**8 of 11** cells, with **zero predictive instances**, a **heterogeneous** link column, and **no
identifiability**. ⭐ **Role overload resolved affirmatively** (three role values, witnessed by
establishment warrants); **subject stratification retrospective** (*ground* is a level, not a
coordinate).

**2 · Cells with genuine independence witnesses** — ⭐ **ALL ELEVEN.** `K1` *(denotation vs content)* ·
`K2` *(conditional on `R0`)* · `K5` *(`F-4`)* · `K3a` · `K3b` *(the exactly-isolating establishment
pair)* · `K4a-i` · `K4a-ii` *(three-assertion topology)* · `K4b-i` · `K4b-ii` *(replacement vs
corroboration)* · `K4c-i`, `K4c-ii` *(conditional on `Q1a`, constructible)*.

**3 · Reducible cells** — ⛔ **NONE unconditionally.** ⭐ **Two bounded dependences:** `K4a-ii` is
derivable **at `n=2` only**; `K2` is derivable **under `R1` only**. ⚠️ **Neither is a reduction under
the current boundary.**

**4 · Irreducible, partial, or underdetermined** — ⭐⭐ **IRREDUCIBLE, boundary-relative** — verdict `A`,
with the three caveats of §14. ⛔ **Not asserted as universal.**

**5 · Kernel change justified?** — ⛔ **NO.** **11 cells, unchanged.**

**6 · What remains open** — ⭐ **`O-P17-1`** *(is there any generative structure, or is the kernel an
irreducible list?)* — ⭐⭐ **and `P-18` now answers half of it: the kernel IS an irreducible list under
this boundary, which makes "irreducible list" a LIVE and legitimate answer rather than a fallback** ·
**`B4` closure** · **kernel identity** · **equivalence (`Q7`)** · **aggregate existence** ·
**`W6`'s mechanism `[ARCH]` and its live empirical failure** · **`O-INT001-1`'s governance rule** ·
**`O-INT002-1`** · **provenance chains** · **alternate histories** · **`Q3`/`Q8`** · **`Q4`** ·
**`M6`'s occurrence** · `F-4`/`F-5` unrepaired · ⭐ **whether `K4b-ii`'s relation and defect aspects
split** *(`P-17`'s labelling finding; no witness, not split)*.

**7 · Ready to move to the next epistemic layer?** — ⭐⭐⭐ **The KERNEL DERIVATION is closed; ARCHITECTURE
is not open.** Those are different statements and both are true:

| | |
|---|---|
| ⭐⭐ **kernel derivation** | ⭐ **CLOSED under the current boundary.** ⭐⭐⭐ **Auditing every remaining `[OPEN]` item: NONE can change the kernel** — the third axis is closed, `K2`-A closed, `W6` = `C3`, `Q2` closed, role overload is a **labelling** defect, `O-INT002-1` is class `C1` (scope), `O-P17-1` is an **explanation** question, and `P-12` classed kernel identity and equivalence as class **`B`** (interpretation and content, adding no capability) |
| 🔴 **architecture** | ⛔ **STILL GATED** — Gate `C` *(transition closure)*, Gate `D` *(`W6`'s mechanism and live failure)* and Gate `E` *(2 of 8 admitted)* are **not kernel questions**, and they remain blocking |

$$\boxed{\begin{array}{c}\textbf{The kernel is not the blocker any more. The blockers are a MECHANISM and a set of CROSS-LANE ADMISSIONS.}\\ \boxed{\textbf{⛔ And a stable-looking kernel is not a licence to begin architecture — the gates decide that, not the kernel.}}\end{array}}$$

---

```
A — 11-CELL IRREDUCIBILITY ESTABLISHED, BOUNDARY-RELATIVE. THE AUDIT FAILED TO FALSIFY THE LOWER BOUND.
KERNEL REMAINS 11 CELLS.

CENTRAL QUESTION ANSWERED: eleven independent obligations, not a failure to find a better compression —
because each cell is isolated by a HISTORY, not by a name, and every reduction attempt was refuted.

ELEVEN SET-WISE WITNESSES, each with the other ten identical:
  K1     two live candidates collapse (W1). The subtlety that makes the test valid: P-09.3 §13 showed
         every retention record PRESUPPOSES identity, which appears to make the pair unconstructible —
         it does not, because the records' CONTENT is identical and only their DENOTATION differs.
  K2     candidate->supported->corroborated leaves NO K3 record under R0.  [conditional on R0]
  K5     the citation is retained and the repository is gone (F-4 is the live case).
  K3a    a re-disposition without the prior association; K3b identical because "the containment
         argument" names no prior value.
  K3b    an ESTABLISHMENT with vs without a warrant — K3a is EMPTY IN BOTH ARMS, so the isolation is
         exact. Corpus-attested arms: C-022 with a warrant, G-67 with none.
  K4a-i  the relation kept and the text deleted (P-08 §5.2: process-reliability evidence needs content).
  K4a-ii THREE retained assertions with one current: p1>p2>p3 versus p1>p3 and p2>p3 have the same
         texts, current state and warrant content. NEAR-MISS RECORDED: at n=2 the cell IS derivable
         from K4a-i + K2, and only the n=3 topology saves it.
  K4b-i  the invalidation kept and the ground deleted — which KIND of failure is unrecoverable.
  K4b-ii two retained grounds: REPLACEMENT versus CORROBORATION — exactly the Closure(K_9) distinction,
         where the warrant was replaced by a stronger computation (14 -> 66 worlds).
  K4c-i  "something merged into C" — dangling citations, W1 survives.   [conditional on Q1a]
  K4c-ii merge versus two retirements plus one independent establishment.  [conditional on Q1a]

FOURTEEN REDUCTIONS REFUTED — the eleven commissioned plus THREE more:
  K3a -> K4a-i  REFUTED by P-08 §8: UPDATED items (candidate, definition, association) are UPDATED,
                never SUPERSEDED, so their prior states are NOT assertions.
  K5  -> K4b-i  REFUTED: K4b-i covers WITHDRAWN grounds, K5 the CURRENT one.
  K1  -> K4c-i  REFUTED: K4c-i retains a PAST identity, K1 is PRESENT continuity — P-09.1 §5, K1
                preserves while K4c retires. Complementary, not derivable.
  Co-occurrence was never mistaken for entailment (K4c with K4a, K4c with K3b are both co-occurrences).

EXACTLY TWO DEPENDENCE FINDINGS, BOTH BOUNDED: K4a-ii derivable at n=2 and not at n=3; K2 derivable
  under R1 and not under R0. Reconstructibility (paths, reachability, transitive closure — P-16) is
  IRRELEVANT to redundancy, and compression of representation is not reduction of obligations.

THREE CAVEATS DOCUMENTED RATHER THAN HIDDEN: K2's independence is STIPULATION-DEPENDENT and lapses
  under R1 — the most fragile cell; K4c-i/K4c-ii's witnesses are CONSTRUCTIBLE, not corpus-attested,
  since retirement is permitted but unwitnessed and merge is scheduled but unwitnessed; K4a-ii was
  nearly reduced. NO cell is marked [EMP] — every witness is a constructed history, even where its
  arms are corpus-attested.

LOGICAL SEPARATION ENFORCED: not-(SUBJECT x ROLE generation) does NOT imply irreducibility, and
  not-ThirdAxis does NOT imply irreducibility. P-17 refuted one EXPLANATION, P-16 one DIMENSION;
  neither implication is used here. The present-state/retention boundary DOES have an independence
  witness (all four directions vary), and that still does not rescue generativity — a forced partition
  is not a generative basis.

KERNEL CHANGE: NONE JUSTIFIED. 11 cells.

READINESS — two different statements, both true: the KERNEL DERIVATION IS CLOSED under the current
  boundary, because auditing every remaining OPEN item shows NONE can change the kernel (third axis
  closed, K2-A closed, W6 = C3, Q2 closed, role overload a LABELLING defect, O-INT002-1 class C1,
  O-P17-1 an EXPLANATION question, and P-12 classed kernel identity and equivalence as class B).
  ARCHITECTURE IS STILL GATED: Gates C, D and E remain blocking and are NOT kernel questions. The
  blockers are now a MECHANISM and a set of CROSS-LANE ADMISSIONS — not the kernel. A stable-looking
  kernel is not a licence to begin architecture.
  P-18 also answers HALF of O-P17-1: the kernel IS an irreducible list under this boundary, which makes
  "irreducible list" a LIVE and legitimate answer rather than a fallback.

NO ARCHITECTURE SELECTED — SCHEMA v3 NOT BEGUN — 3MC UNTOUCHED.
```
