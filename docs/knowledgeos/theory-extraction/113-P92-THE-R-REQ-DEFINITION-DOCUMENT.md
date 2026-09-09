# `P-92` — The `ℛ_req` Definition Document, Read Completely

**2026-09-09 · Lane T.** After [`112` `P-91`](./112-P91-R-REQ-HISTORICAL-RECONSTRUCTION.md).
**Commissioned:** *"`ℛ_req` is defined in documents stamped with 20260902."*

⛔ **Nothing defined, ratified or chosen · no 3MC · `|K| = 11 · [REC] · UNFROZEN`.**

# 0. ⭐ The pointer was right, and it found a file I had not read

⭐⭐ **`20260902-182016_required-distinction.md` — 985 lines, the largest of the family, named for the
object — I had only grepped it.** ⛔ **`P-87`, `P-88` and `P-91` were all written without it.**

⚠️ **It is THREE documents concatenated:** the specification (1–265) · **`ABK-1`** (266–552) ·
**`ABK-1/Contr Bridge`** (553–985). ⭐ **Lines 1–265 are the definition. Read completely.**

# 1. ⭐⭐⭐ `-RATIFIED` is in the DOCUMENT ID, not the filename

> **Document ID:** `SPEC-RREQ-2026-V1-`**`RATIFIED`**
> **Status:** ⭐⭐ **`[PROPOSED RATIFICATION]`**
> **Authority:** HPA Supervisory / KnowledgeOS Kernel Group

⭐ **`document6` reported this as a filename effect. It is sharper than that: the discrepancy is inside
the document's own identifier**, which is what downstream artifacts cite. ⛔ **A citation of
`SPEC-RREQ-2026-V1-RATIFIED` carries the word into every reference.**

# 2. ⭐⭐⭐⭐⭐ The minimality clause **is** `R₀`'s deletion test — and this corrects `P-91`

§1.1's Structural Completeness Theorem, **clause 2**:

> *"`d_i` is kept **if and only if there exists a test vector `TV_i` where collapsing `d_i` alters task
> output `τ(TV_i)`**."*

$$\boxed{\begin{array}{c}R_0 \ (273.9,\ 08\text{-}30):\quad \exists o \in \mathcal O_{core}: o(K,x) \neq o(K^{-X},x)\\[4pt] R_6 \ (182016,\ 09\text{-}02):\quad \exists TV_i:\ \tau(TV_i) \textbf{ alters when } d_i \textbf{ is collapsed}\end{array}}$$

⭐⭐⭐ **The same test, with `𝒪_core` replaced by `𝒯`.** ⚠️⚠️ **`P-91` §8 said the criterion *"survived
from `R₀` to `R₃`"* and proposed as its next action *"test whether the original criterion still decides
membership."*** ⛔ **Too weak: the criterion survived all the way into the ratification edition, and is
stated there AS the membership rule.**

⭐⭐ **The corrected gap is narrower and sharper:** **the rule exists; the test vectors do not.** ⛔ **No
`TV_i` is exhibited for any `D-n` anywhere in the document.** ⭐ **Phase 2 of its own governance workflow
— *"Formal Verification & Test Vectors"* — is marked `[COMPLETED]`.**

# 3. ⭐⭐⭐⭐ The count discrepancy — **fully resolved, and it is a demotion**

| source | P1 | P2 | total |
|---|---:|---:|---:|
| ⭐ **`182016` §2** *(the spec)* | **6** | ⭐ **6** — incl. **`D-5.1` Analytic vs Synthetic** | ⭐ **12** |
| ⭐ **`182013`** *(the assessment)* | **6** rows *(header says **7**)* | **5** — ⭐⭐ **`D-5.1` DEFERRED to P3** | **11 rows**, *summary says **12*** |
| ⚠️ **`182016` §3** *(tractability matrix)* | — | — | ⭐⭐ **8 only** — ⛔ omits `D-2.2`, `D-4.1`, `D-4.2`, `D-5.1` |

$$\boxed{\begin{array}{c}\textbf{⭐⭐⭐ The assessment } \mathbf{DEMOTED\ }D\text{-}5.1\mathbf{\ from\ P2\ to\ P3\ and\ did\ not\ update\ its\ counts.}\\ \textbf{Header } \mathbf{7} \textbf{ is wrong; summary } \mathbf{12} \textbf{ is the SPEC's count carried over; } \mathbf{11} \textbf{ is its actual list.}\end{array}}$$

⭐ **And the spec's §6 names `Analytic vs Synthetic` explicitly among the *"missing semantic distinctions"* it ADDED** — ⛔ **so the demotion reverses a change the same package advertises as an achievement.**
⚠️ ⭐⭐ **A fourth count, `8`, comes from the tractability matrix.** ⇒ **`12 · 11 · 8 · (7) · (12)` — five numbers for one inventory.** ⛔ **`P-87` §4 and `P-91` §3 are now both explained.**

# 4. ⭐⭐⭐⭐⭐ THE DECISIVE FINDING — the acceptance semantics **exist**, for the wrong argument

§4.3, *"Integration with Evaluation Engine"*:

$$\boxed{\mathrm{Loss}_{\mathcal R_{req}}(\pi) = \sum_{d_i \in \mathcal R_{req}} w_i \cdot \mathbb I\big(\mathrm{Collapse}(d_i,\pi)\big), \qquad w_{P1} = 1.0,\ w_{P2} = 0.5}$$

> *"An adequate kernel projection requires `Loss = 0` for **all P1 distinctions**."*

⭐⭐⭐ **That is a complete, quantitative acceptance condition — with weights, an indicator function and
a threshold.** ⛔ **And its argument is `π`, a PROJECTION.**

$$\boxed{\begin{array}{c}\mathcal R_{req} \textbf{'s acceptance semantics are defined for } \mathbf{REPRESENTATIONS}\textbf{, never for } \mathbf{PROPOSITIONS}.\\[4pt] \textbf{⭐⭐⭐ And } Sat(K_t, r) \textbf{ asks about an } \mathbf{epistemic\ state}\textbf{. } \mathbf{Different\ argument,\ different\ question.}\end{array}}$$

⇒ ⭐⭐ **This is the precise, corpus-grounded reason the Requirement–Evaluation boundary is missing.** It
is **not** that the corpus never defined acceptance for `ℛ_req` — ⭐ **it did, in §4.3.** ⛔ **It defined
it for the object `ℛ_req` was actually about: whether an ENCODING loses a distinction.**

⭐ **`P-91` §5 said the boundary is *"absent by construction"*. Correct — and §4.3 shows why: the
construction has an acceptance rule, and it is about `ℰ`/`π`, exactly as `R₀`'s deletion test was.**

---

# 4A. ⭐⭐⭐⭐⭐ THE FAMILY SWEEP — and a same-batch REJECTION nobody cites

⭐ **39 files stamped `20260902` mention `ℛ_req`; `md5sum` gives 5 duplicate pairs ⇒ 34 distinct.**
⭐⭐ **The most definitional unread one was `182008_review-read-attached-document-in-full.md`
(1 864 lines, 17 sections) — and it is a CRITICAL REVIEW of the `ℛ_req` document.**

## 4A.1 ⭐⭐⭐ Its verdict, against `182013`'s

| same 27-file batch | verdict |
|---|---|
| ⭐ **`182008`** *(five files earlier)* | ⭐⭐⭐ ***"Research analysis / candidate specification — NOT YET RATIFIABLE."*** ⛔ *"I do **not** recommend following that recommendation directly."* |
| ⭐ **`182013`** | ⭐⭐ ***"VERDICT: READY FOR RATIFICATION"*** — every component ✅ **COMPLETE** |

$$\boxed{\textbf{⭐⭐⭐ Two documents, one batch, } \mathbf{opposite\ dispositions\ of\ the\ same\ object\ —\ and\ neither\ cites\ the\ other.}}$$

⚠️ **`P-87` recorded the package as `[ADVISORY]`, *"READY FOR"* ratification.** ⭐⭐ **That is now
qualified: a same-batch review says it is not ratifiable at all**, on grounds the assessment marks
`COMPLETE`:

| `182013` says | `182008` §§14–16 say |
|---|---|
| completeness ✅ **COMPLETE** | ⭐⭐ ***"'Completeness of `ℛ_req`' is itself a DANGEROUS concept"*** |
| minimality proven in the theorem | ⭐⭐ ***"the 'minimality' definition is not yet mathematically sufficient"*** |
| inventory ✅ **COMPLETE** | ⭐⭐⭐ ***"the 47 distinctions should NOT be frozen as universal"*** |
| `Zero` integration ✅ **COMPLETE** | ⭐ ***"'Zero requires all Tier 1 distinctions' should NOT be adopted"*** |
| — | ⛔ ***"the proposed `δ` requirement is therefore wrong"*** · ⛔ *"I disagree with 'Tier 1 must always be preserved'"* |

## 4A.2 ⭐⭐⭐⭐⭐ And it gives the strongest statement of the object in the corpus

$$\boxed{\mathcal R_{req} = \textbf{the distinctions that must remain available } \mathbf{for\ the\ questions\ KnowledgeOS\ is\ required\ to\ answer}}$$

⭐⭐ **with the unjustified jump named explicitly:**
$$\mathcal R_{req} \rightarrow \textbf{all KR distinctions} \rightarrow \textbf{kernel requirements} \qquad \textbf{⛔ *"that chain is NOT established."*}$$

## 4A.3 ⭐⭐⭐⭐ `RQ-1` restores the relativization the spec dropped

$$\boxed{\mathcal R_{req}(Q,\Gamma) \quad \textbf{— *"the distinctions required to answer a declared question/task under context } \Gamma\textbf{"* — } \mathbf{PROMOTE\ TO\ THEORY\ CANDIDATE}}$$

⭐⭐⭐ **This is `R₀`'s deletion test with its parameter restored, one layer up:**

| | relative to |
|---|---|
| `R₀` (08-30) | ⭐ **`𝒪_core`** — an operation set |
| ⛔ `R₅` (the spec) | **nothing — an absolute inventory** |
| ⭐⭐ **`RQ-1`** (`182008`) | ⭐ **`(Q, Γ)`** — a question and a context |

⇒ ⭐⭐ **The review's diagnosis is `P-91`'s, reached independently and three months earlier in
programme-time: the object lost its parameter when it became a list, and `RQ-1` puts one back.**
⚠️ ⛔ **A different parameter, though — `(Q,Γ)`, not `𝒪_core`. Recorded, not reconciled.**

## 4A.4 The count, once more

⭐ **`182008` reports the source document as *"8 categories, **47 distinctions**, 3 tiers, 5 verification
criteria."*** ⇒ **the series is `47 → 12 → 11 → 8`.**
$$\boxed{\textbf{⭐⭐ Four inventories, one name, and the largest is } \mathbf{6\times} \textbf{ the smallest.}}$$
⭐ **`182008`'s own remedy is the honest one:** ⛔ not *"comprehensive foundation"* but ⭐ ***"47
**currently identified candidate** distinctions within the present research scope"*** — **`KnownSet(ℛ)`
without claiming `ℛ = 𝒟`.**

## 4A.5 ⚠️ Read-status, stated plainly

⭐ **Read completely:** `181000` · `181001`(≡`181003`) · `182001` · `182013` · `182016` §§1–265 ·
`182008` §§1, 16, 17 + its full section map.
⛔ **NOT read completely:** the remaining **28** of 34 — including `182011`, `182017` (`SPEC-DET`,
1 380 ln), `182003`, `182006`, `182007`, `182019`, and the `1800xx` review block. ⭐ **Their definitional
signal is lower, but that is a ranking, not a reading.**

# 5. Two further readings the family does not reconcile

## 5.1 ⚠️ "Compositionality" names two different things
| | |
|---|---|
| ⭐ `182016` §1.1 clause 3 | *"`ℛ_req` is **closed under Boolean operations on equivalence relations**: `∼_{dᵢ∧dⱼ} = ∼_{dᵢ} ∩ ∼_{dⱼ}`"* — ⭐ **a closure property of the SET** |
| ⭐ `181001` §3.1 condition 3 | *"**composition of representations** must preserve `d`"* — ⭐ **a property of `π₁ ∘ π₂`** |

⛔ **Not the same claim.** ⚠️ **And the *"congruence conjunct absent"* finding is about the second.**
⭐ **So the spec supplies set-closure and the definition demands composition-preservation; only the
first is present.**

## 5.2 ⭐ `Zero`, and `Contr`, are conditions on `D-1.1`'s values
$$\mathbf 0 \iff \forall P: \big(\mathrm{Status}(P) \notin \{\textit{Contradictory}, \textit{Underdetermined}\} \wedge \mathrm{Currency}(P) = \textit{Current}\big)$$
⭐⭐ **Both integrations quantify over the VALUES of two named distinctions** — ⛔ **neither introduces a
general requirement-satisfaction relation.** ⭐ **Consistent with §4: the document never needs one.**

# 6. Status

**`[EMP]`** ⭐ `182016` is three documents concatenated; the definition is 1–265 · **Document ID carries
`-RATIFIED`, Status is `[PROPOSED RATIFICATION]`** · ⭐⭐ **the minimality clause is `R₀`'s deletion test
verbatim** · **12 / 11 / 8 / 7 / 12 — five counts** · **`D-5.1` demoted P2→P3 between spec and
assessment** · ⭐⭐ **`Loss_{ℛ_req}(π)` with `w_{P1}=1.0, w_{P2}=0.5` and a zero threshold** · Boolean
closure vs composition-preservation · Phase 2 *"Test Vectors"* marked `[COMPLETED]`.
**`[DERIVED]`** ⭐⭐⭐ **`ℛ_req`'s acceptance semantics are defined for PROJECTIONS, not propositions —
which is why `Sat`'s bridge is missing** · ⭐⭐ **the membership rule exists and its test vectors do
not** · **the 11/12 discrepancy is an unrecorded demotion.**
**Corrections to `P-91`** ⭐⭐ the criterion survived **into `R₆`**, not only to `R₃`; ⭐ its proposed
action **H** is narrowed — ⛔ **not "does the criterion decide membership", but "exhibit the `TV_i`".**
⛔ **`P-91` not rewritten.**
**Governance:** `|K| = 11 · [REC] · UNFROZEN`. ⛔ **No version ratified — unchanged.**

# 7. Backlog

⛔ **No new item.** ⭐ Every finding refines items filed today: the `-RATIFIED` identifier and the
unmarked demotion belong to **`EKS-31`**'s provenance/countability cause; the projection-vs-proposition
mismatch is **`EKS-39`**'s type-drift cause, now with its **exact mechanism**. ⭐⭐ **Nineteenth record
in twenty-eight with no new number.**

### 8. The one next action, corrected

$$\boxed{\begin{array}{c}\textbf{⭐⭐ Exhibit the test vectors. For each } D\text{-}n\textbf{, the spec's own rule requires a } TV_i\\ \textbf{whose task output changes when } d_i \textbf{ is collapsed — and } \mathbf{none\ is\ given}\textbf{, while Phase 2}\\ \textbf{*"Formal Verification \& Test Vectors"* is marked } \mathbf{[COMPLETED]}.\end{array}}$$

⭐ **Smaller than `P-91`'s version and answerable from the corpus:** the rule is written, the inventory
is written, and the question is only whether the witnesses exist. ⚠️ **And it is prior to ratification,
because the spec makes `TV_i` the membership criterion** — ⛔ **so an unwitnessed `D-n` is, by the
document's own standard, not established as a member.**

```
THE R_req DEFINITION DOCUMENT, READ COMPLETELY.

The pointer was right and it found a file I had not read: 20260902-182016_required-distinction.md, 985
lines, the largest of the family and named for the object — I had only grepped it, and P-87, P-88 and
P-91 were all written without it. It is THREE documents concatenated; lines 1-265 are the definition.

"-RATIFIED" IS IN THE DOCUMENT ID, NOT THE FILENAME: Document ID SPEC-RREQ-2026-V1-RATIFIED, Status
[PROPOSED RATIFICATION]. Sharper than reported, because the identifier is what downstream artifacts
cite.

THE MINIMALITY CLAUSE IS R_0'S DELETION TEST, PRESERVED VERBATIM: "d_i is kept if and only if there
exists a test vector TV_i where collapsing d_i alters task output tau(TV_i)" — the same test as
273.9's "exists o in O_core with o(K,x) != o(K^-X,x)", with O_core replaced by T. That CORRECTS P-91,
which said the criterion survived only to R_3 and proposed testing whether it still decides membership.
It survived into the ratification edition and is stated there AS the membership rule. The corrected gap
is narrower: THE RULE EXISTS; THE TEST VECTORS DO NOT — no TV_i is exhibited for any D-n, while Phase 2
"Formal Verification & Test Vectors" is marked [COMPLETED].

THE COUNT DISCREPANCY IS FULLY RESOLVED AND IT IS A DEMOTION: the spec's §2 has 6 + 6 = 12 with D-5.1
(Analytic vs Synthetic) in P2; the assessment has 6 rows + 5 with D-5.1 DEFERRED TO P3, while its header
says 7 and its summary says 12. So the assessment demoted D-5.1 and did not update its counts — and the
spec's §6 names Analytic vs Synthetic among the "missing semantic distinctions" it ADDED, so the
demotion reverses a change the same package advertises as an achievement. A fourth count, 8, comes from
the tractability matrix, which omits four distinctions. Five numbers for one inventory; P-87 §4 and
P-91 §3 are both now explained.

THE DECISIVE FINDING — THE ACCEPTANCE SEMANTICS EXIST, FOR THE WRONG ARGUMENT. §4.3 defines
Loss(pi) = sum of w_i times an indicator that projection pi collapses d_i, with w_P1 = 1.0 and
w_P2 = 0.5, and requires Loss = 0 for all P1 distinctions. That is a complete quantitative acceptance
condition with weights and a threshold — and its argument is PI, A PROJECTION. R_req's acceptance
semantics are defined for REPRESENTATIONS, never for PROPOSITIONS, while Sat(K_t,r) asks about an
epistemic state. Different argument, different question. So the Requirement-Evaluation boundary is
missing not because the corpus never defined acceptance for R_req — it did — but because it defined it
for what R_req was actually about: whether an ENCODING loses a distinction. P-91's "absent by
construction" was right, and §4.3 shows why.

Two further unreconciled readings: "compositionality" names closure of the SET under Boolean operations
in the spec, and preservation under COMPOSITION OF REPRESENTATIONS in the definition — not the same
claim, and the "congruence conjunct absent" finding is about the second. And Zero and Contr are stated
as conditions on the VALUES of two named distinctions, never as a general satisfaction relation.

THE FAMILY SWEEP — AND A SAME-BATCH REJECTION NOBODY CITES. 39 files stamped 20260902 mention R_req; 5
duplicate pairs give 34 distinct. The most definitional unread one, 182008 (1,864 lines, 17 sections),
is a CRITICAL REVIEW of the R_req document, and its verdict is "Research analysis / candidate
specification — NOT YET RATIFIABLE", with "I do not recommend following that recommendation directly".
182013, five files later in the same 27-file batch, says "READY FOR RATIFICATION" with every component
COMPLETE. Two documents, one batch, opposite dispositions of the same object, neither citing the other —
and the review rejects precisely what the assessment marks complete: "'Completeness of R_req' is itself
a DANGEROUS concept", "the 'minimality' definition is not yet mathematically sufficient", "the 47
distinctions should NOT be frozen as universal", "'Zero requires all Tier 1 distinctions' should NOT be
adopted", "the proposed delta requirement is therefore wrong".

It also gives the strongest statement of the object in the corpus — R_req is "the distinctions that must
remain available FOR THE QUESTIONS KnowledgeOS IS REQUIRED TO ANSWER" — and names the unjustified jump:
R_req -> all KR distinctions -> kernel requirements, "that chain is NOT established".

And RQ-1 restores the parameter the spec dropped: R_req(Q, Gamma), "the distinctions required to answer
a declared question/task under context Gamma", PROMOTE TO THEORY CANDIDATE. That is R_0's deletion test
with its parameter back one layer up — R_0 was relative to O_core, the spec was relative to nothing, and
RQ-1 is relative to (Q, Gamma). The review's diagnosis is P-91's, reached independently; the parameter
is a different one, and that is recorded, not reconciled.

The count series is now 47 -> 12 -> 11 -> 8: four inventories under one name, the largest six times the
smallest. The review's own remedy is the honest one — not "comprehensive foundation" but "47 CURRENTLY
IDENTIFIED CANDIDATE distinctions within the present research scope".

READ STATUS, PLAINLY: read completely — 181000, 181001 (= 181003), 182001, 182013, 182016 §§1-265, and
182008 §§1, 16, 17 with its full section map. NOT read completely — the remaining 28 of 34, including
182011, 182017 (SPEC-DET, 1,380 lines), 182003, 182006, 182007, 182019 and the 1800xx review block.
Their definitional signal is lower, but that is a ranking, not a reading.

Backlog: no new item — the findings refine EKS-31 (provenance and countability) and EKS-39 (type drift,
now with its exact mechanism). Nineteenth record in twenty-eight with no new number.

|K| = 11, [REC], UNFROZEN. No version of R_req ratified. No 3MC inspection.

THE ONE NEXT ACTION, CORRECTED: exhibit the test vectors. For each D-n the spec's own rule requires a
  TV_i whose task output changes when d_i is collapsed, and none is given while Phase 2 is marked
  COMPLETED. Smaller than P-91's version and answerable from the corpus, and prior to ratification —
  because by the document's own standard an unwitnessed D-n is not established as a member.
```
