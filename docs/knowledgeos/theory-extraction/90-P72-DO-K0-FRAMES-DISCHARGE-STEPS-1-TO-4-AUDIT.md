# `P-72` — Do `K0`'s `P1`–`P7` Frames Discharge Steps 1–4?

**2026-09-09 · Lane T.** After [`89` `P-71`](./89-P71-FORWARD-READ-OF-THE-S1-THREAD-AND-ITS-DISPOSITION.md).
**Method:** forward-read *(standing since `P-71`)*. ⭐ `verification/spec/` is **not** timestamped, so the
analogue used was **the folder's own `00-INDEX.md` and its last checkpoint report** — and it worked.

# 1. Executive verdict

$$\boxed{\mathbf{1\ DISCHARGED\ \cdot\ 1\ PARTIAL\ \cdot\ 2\ NOT\ —\ and\ }K0\mathbf{\ names\ the\ blocker\ itself.}}$$

| step | `K0` frame | verdict |
|---|---|---|
| **1 · `𝔠`** the contract | `P4` **uses** `EC` in `ev: 𝒦 × R × EC → 𝒮_gap` | ⛔ **NOT DISCHARGED** — `K0` §3 lists ***`η construction (EC = η(G, IdealState))`*** as missing: *"Zero's **input** is unconstructed"* |
| **2 · `ℳ_K`** the model | ⭐⭐ `P4`'s **opaque `𝒦`** + `P6`'s `δ: 𝒦 × ℰ ⇀ 𝒦`, `Pre/Post`, `H`, `Replay` | ⭐⭐ **DISCHARGED** — and **strengthened**: `WAVE3` raised the **extended replay theorem** to theorem level |
| **3 · `Obs_𝔠`** contract-indexed observations | `P1`'s `Ω : W → O` | ⚠️ **PARTIAL — machinery yes, indexing no.** `Ω` is **unindexed**; the `𝔠`-indexing needs step 1 |
| **4 · `⊑_𝔠`** contract-relative refinement | `P2`'s `Identifiable(g,Ω)` | ⛔ **NOT DISCHARGED** — `Identifiable` is a **property predicate**, not a refinement order on models; and it is contract-indexed too |

⭐⭐⭐ **And the structural reason, which is the audit's real result:**

$$\boxed{\begin{array}{c}K0 \textbf{ proves exactly the } \mathbf{contract\text{-}FREE} \textbf{ results — } T\text{-}K1 \textbf{ needs no } \mathfrak C \textbf{ at all.}\\[4pt] \textbf{⛔ Steps } \mathbf{1,\ 3\ and\ 4\ are\ all\ contract\text{-}INDEXED.} \textbf{ That is why its provable core is *"small but real"* and stops where it does.}\end{array}}$$

$$|K| = 11 \textbf{ — unchanged, } \mathbf{[REC]}. \textbf{ ⛔ Nothing adopted; } K0 \textbf{ is reported, not consumed.}$$

# 2. `K0`'s own verdict, verbatim

**§3 gap list** — the first two rows are decisive:
> **`η construction (EC = η(G, IdealState))`** — *"Zero's input is unconstructed; totality is a ruled
> assumption; several requirement origins are **non-computational oracles**"* — refs `D-R05`
> **`Identity calculus (~, state equality, frame equivalence, requirement identity)`** — *"`KA3` is
> **assumed, not supplied**; `I-5`, replay determinism, idempotency all conditional on it"* — refs
> `MV-F-22`, `C-022`

**§4 the defensible chain:**
```
K0 (7 frames + KA1–KA7)
   ⊢ T-K1…T-K10                        [the provable core — small but real]
   ⊬ η, Learn, identity calculus, transition calculus,
     policy uniqueness/genesis, operator selection   [the missing middle]
   ⇒ everything downstream of the missing middle is at most CONJECTURE/DESIGN CHOICE
```

$$\boxed{\textbf{⭐⭐⭐ } \eta \textbf{ constructs } EC\textbf{, } EC \textbf{ is step 1, and } \mathbf{K0 \nvdash \eta.} \textbf{ The answer is in }K0\textbf{'s own gap register.}}$$

⭐ And §4 adds: *"This matches, **independently**, the `GN-46` verdict (**'signatures without
constructions'**) — arrived at here from the raw corpus rather than from the ratified layer."*

# 3. ⭐⭐⭐ A sixth body of work — and it is the most disciplined in the estate

The forward-read analogue *(`spec/00-INDEX.md`)* revealed a **governance-commissioned verification
programme**:

⭐ **`GN-46` (2026-08-28)** — *"a second, **independent** verification track — mathematical · statistical ·
computational · formal-consistency … **READ/VERIFY/FALSIFY — never repair**"*, with a mandated verdict
vocabulary: ***"MATHEMATICALLY SOUND … NOT ESTABLISHED; never 'validated'."***

**Its artifacts, all ✅:** `A1` inventory · `A2` assumptions · `A3`/`A3W`/`A3X` definitions · `A4`
derivations *(`T-K1…10` + **Non-Consequences**)* · `A5` propositions *(`P-01…17` · `R-01…07`)* · `A6`
statistical · `A7` computational · `A8` math↔DDD↔architecture matrix · `A9` counterexamples
*(`CE-01…15` + ⭐⭐ **failed searches**)* · `AC` contradictions *(**61**)* · `AM` measurement ·
`TV-F-001…019` findings across three waves.

⚠️⭐⭐⭐ **And its status: *"session STOPPED pending supervision"* · *"STOP for supervisory review; the
final theory is NOT being written yet."* — dated 2026-08-29, eleven days ago.**

# 4. ⭐⭐ What that programme already settled, that this lane re-derived

| `WAVE3` / `RECONSTRUCTION-COMPLETE` | this lane |
|---|---|
| ⭐⭐ *"**Event sourcing automatically provides epistemic replay**" — **REFUTED** (`TV-F-013`)* | `P-08`'s *"NO EVENT SOURCING"* and Part XIX's *"not a theorem"* — ⭐⭐⭐ **third independent line** |
| ⭐⭐⭐ *"**Replay** — conditional **on adopting the recording rule — governance**"* | ⭐⭐⭐ **`P-67`'s open/closed-world question for `K3`.** The *"recording rule"* **is** the log-completeness obligation `P-67` said `K3` needs — ⭐ **and it is a named, pending governance adoption** |
| *"**retraction as a state** — remains undefined"* | ⭐⭐ `K4c` *retired identity / retired-into*, and `theory-v1.2-simulation`'s `CE-3` *"defines no retirement relation"* — **third witness** |
| *"Five kernel senses on record, **mutually NON-INTERCHANGEABLE**"* | ⭐ confirms `P-68` §7 |
| *"the `K0` rim — **all ten theorems** — nothing in Wave 3 weakened any"* | ⭐ confirms `P-68` |

$$\boxed{\textbf{⭐⭐⭐ } P\text{-}67\textbf{'s "a ruling, not a computation" was right — and the ruling has a } \mathbf{name}\textbf{: } \mathbf{the\ recording\ rule,\ pending\ governance.}}$$

# 5. Why steps 3 and 4 cannot be reached from `K0`

⭐ `P1` supplies an observation channel `Ω : W → O`; `P2` supplies `Identifiable(g,Ω)`. ⛔ **Neither is
indexed by a contract**, and both steps 3 and 4 *are*:

| | needed | `K0` has | gap |
|---|---|---|---|
| **`Obs_𝔠`** | the observations **`𝔠` licenses** | ⭐ **an** observation channel | ⛔ **which channel `𝔠` selects** — needs `𝔠` |
| **`⊑_𝔠`** | a **refinement order on models**, relative to `𝔠` | ⭐ a **property-recoverability predicate** | ⛔ **an order between models**, and its `𝔠`-indexing |

⭐⭐ **`Identifiable` and `⊑_𝔠` are different types**: the first relates a **property** to a **channel**;
the second relates **two models**. ⛔ **Treating them as the same would be the `P-38` error again** — and
they *look* similar because both are written with `Ω`.

# 6. What `K0` *does* buy, stated at full strength

⭐⭐ **Step 2 is genuinely discharged, and it is the first step this series has been able to close.**
`P4` + `P6` give a transition system over an opaque state sort with `Pre/Post`, histories and `Replay` —
⭐⭐⭐ **exactly what `P-60` counted as missing and `P-66` blocked on.** And `WAVE3` **raised replay to
theorem level** *(classes 1–4 evaluators with recorded context ⇒ deterministic replay, bounded by a
single live-oracle counterexample)*.

⚠️ ⛔ **But it does not dissolve `P-66`'s blocker, and `P-68`'s hope is now `[REFUTED]` in part:** `K0`'s
`𝒦` is opaque **and unindexed**, so `Z_K` still cannot be typed **for the estate's registers** — the
missing thing was never only `δ`; it was `𝒳_R` **and** the contract. ⭐ **`P-68` flagged this as a
candidate route and refused to assert it. Refusing was correct.**

# 7. Statistical and DDD discipline

⛔ 7 frames · 7 assumptions · 10 theorems · 61 contradictions · 15 counterexamples · 19 findings are
**corpus witnesses over one estate** — no independence, no replication, no confidence.
⭐ **DDD:** `K0`'s frames are **mathematical sorts**, and its §1 says so — the Constitutional kernel is
*"not mathematics"*, the capability lists *"neither claim deductive sufficiency"*. ⛔ **No pattern named
that the estate has not named.**
⭐ **Method:** absolute paths; controls applied *(`KR-` identifiers = 355 files)*; deep-read **5**
*(`K0` §§3–4, `spec/00-INDEX`, `CHECKPOINT-RECONSTRUCTION-COMPLETE`, `CHECKPOINT-WAVE3`, `GN-46`)*.
⚠️ **The `A`-registers themselves are unread — coverage, not exhaustiveness.**

# 8. Backlog — ⭐ **`EKS-25`**

⭐⭐⭐ **A governance-commissioned programme stopped by its own protocol pending a supervisory review
that has not happened in eleven days — while two other lanes proceeded past it.** ⛔ Distinct from
`EKS-12` *(no office over theory — here an office **did** commission)*, `EKS-18` *(questions unrouted —
here the work is **complete and waiting**)*, `EKS-22` *(status unrecorded — here the status is **recorded
as blocked**)*.

# 9. Status register
**`[EMP]`** ⭐⭐⭐ `K0` §3's `η`-construction gap and identity-calculus gap · §4's `⊢`/`⊬` chain · the
`GN-46` commission and its verdict vocabulary · `spec/00-INDEX`'s eleven ✅ registers, 61 contradictions,
`CE-01…15`, `TV-F-001…019` · *"session STOPPED pending supervision"* · `WAVE3` §1–§5 · *"event sourcing
automatically provides epistemic replay" refuted* · *"Replay conditional on adopting the recording rule —
governance"* · *"retraction as a state remains undefined"* · five non-interchangeable kernel senses.
**`[DERIVED]`** ⭐⭐⭐ **step 2 discharged · step 3 partial · steps 1 and 4 not** · `K0` proves the
**contract-free** results and steps 1/3/4 are **contract-indexed** · `Identifiable` and `⊑_𝔠` are
**different types**.
**`[CORROBORATION]`** ⭐⭐ event sourcing not a theorem — **third line** · retraction undefined — **third
witness** · ⛔ **one estate, no replication.**
**`[REFUTED]`** ⭐⭐ **`P-68`'s hope that `K0`'s opaque `𝒦` dissolves `P-66`'s blocker** — it supplies `δ`,
⛔ not `𝒳_R` and not `𝔠`.
**`[OPEN]`** ⭐⭐⭐ `η` / `𝔠` · the identity calculus (`KA3`) · the **recording rule** *(governance)* ·
`⊑_𝔠` · the `A`-registers unread · all carried opens · ⛔ **well-foundedness / terminality / acyclicity —
DEFERRED.**
**Governance:** `|K| = 11` **`[REC]`** — unchanged, unfrozen.

### 10. ONE NEXT UNRESOLVED QUESTION

$$\boxed{\textbf{Does } \mathbf{A2\textbf{-assumption-register} + A3\textbf{-definition-register}} \textbf{ already contain a } \mathbf{construction\ for\ }\eta \textbf{ — or does the estate's own } \mathbf{D\text{-}register} \textbf{ record it as one of the ten definition gaps?}}$$

⭐⭐⭐ **This is the narrowest question the series has reached, and it is decidable by reading two files.**
`η` is **the** blocker: it constructs `EC`, `EC` is step 1, and steps 3 and 4 are indexed by it — ⛔ **so
`η` alone gates three of the four.** ⭐ `K0` §3 points at `D-R05`, and `CHECKPOINT-A-L-v2` is described as
carrying *"`D` **10-category definition gaps**"*. ⚠️ **If `η` is listed there as a gap, the answer is
final and the estate has already said so twice. ⛔ If it is not listed, then `K0`'s gap row and the
D-register disagree, and that discrepancy matters more than either.**

```
1 DISCHARGED · 1 PARTIAL · 2 NOT — AND K0 NAMES THE BLOCKER ITSELF.

Step 2, the model, is DISCHARGED: P4's opaque knowledge sort plus P6's partial deterministic delta with
Pre/Post, histories and Replay — exactly what P-60 counted as missing — and Wave 3 raised the extended
replay theorem to theorem level. Step 3, contract-indexed observations, is PARTIAL: P1 supplies an
observation channel but it is unindexed, and the indexing needs step 1. Step 1, the contract, is NOT
discharged, and K0's own gap list says why: "eta construction (EC = eta(G, IdealState))" is missing —
"Zero's input is unconstructed". Step 4 is NOT discharged: P2's Identifiable is a property-recoverability
predicate, not a refinement order on models, and it too is contract-indexed.

THE STRUCTURAL REASON IS THE REAL RESULT: K0 proves exactly the contract-FREE results — T-K1 needs no
contract at all — while steps 1, 3 and 4 are all contract-INDEXED. That is why its provable core is
"small but real" and stops precisely where it does. K0 §4 states the chain itself: it derives T-K1
through T-K10 and does NOT derive eta, Learn, the identity calculus, the transition calculus, policy
uniqueness or operator selection — "the missing middle" — after which "everything downstream is at most
CONJECTURE/DESIGN CHOICE".

Identifiable and the refinement order are DIFFERENT TYPES: the first relates a property to a channel, the
second relates two models. They look similar because both are written with Omega, and treating them as
the same would be the P-38 error again.

THE FORWARD-READ FOUND A SIXTH BODY OF WORK, and it is the most disciplined in the estate: a
governance-commissioned verification programme under GN-46 — "READ/VERIFY/FALSIFY, never repair", with a
mandated verdict vocabulary that forbids the word "validated" — carrying eleven completed registers, 61
typed contradictions, fifteen counterexamples plus recorded FAILED SEARCHES, and nineteen findings across
three waves. Its status: "session STOPPED pending supervision", dated 2026-08-29, eleven days ago.

AND IT ALREADY SETTLED THINGS THIS LANE RE-DERIVED. "Event sourcing automatically provides epistemic
replay" is REFUTED there — a third independent line agreeing with P-08 and Part XIX. "Retraction as a
state remains undefined" — a third witness on K4c's territory. And decisively: "Replay — conditional on
adopting THE RECORDING RULE — governance". THAT IS P-67's OPEN/CLOSED-WORLD QUESTION FOR K3, and the
ruling P-67 said was needed has a name and is pending governance adoption.

P-68's hope is partly REFUTED: K0's opaque sort does NOT dissolve P-66's blocker, because it supplies
delta but neither the estate's carrier nor the contract. P-68 flagged it as a candidate and refused to
assert it; refusing was correct.

Deep-read 5; the A-registers themselves remain unread — coverage, not exhaustiveness. Method note: the
spec folder is not timestamped, so the forward-read analogue used was the folder's own index and its last
checkpoint report, and it worked.

EKS-25 filed: a governance-commissioned programme stopped by its own protocol pending a supervisory
review that has not happened in eleven days, while two other lanes proceeded past it.

|K| = 11 UNCHANGED, [REC], UNFROZEN. Nothing adopted; K0 is reported, not consumed.

ONE NEXT UNRESOLVED QUESTION: Does A2-assumption-register plus A3-definition-register already contain a
  construction for eta — or does the estate's own D-register record it as one of the ten definition gaps?
  This is the narrowest question the series has reached and it is decidable by reading two files. Eta is
  THE blocker: it constructs the contract, the contract is step 1, and steps 3 and 4 are indexed by it, so
  eta alone gates three of the four. K0 §3 points at D-R05 and the A-L checkpoint is described as carrying
  "D 10-category definition gaps". If eta is listed there as a gap, the answer is final and the estate has
  said so twice. If it is not listed, then K0's gap row and the D-register disagree, and that discrepancy
  matters more than either.

NO three_model_convergence INSPECTION — excluded by path from every command, unread.
```
