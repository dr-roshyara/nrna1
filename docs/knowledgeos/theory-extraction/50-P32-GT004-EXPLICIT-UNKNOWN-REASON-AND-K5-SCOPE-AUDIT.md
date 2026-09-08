# `P-32` — `GT-004`'s Explicit UNKNOWN Reason & `K5` Scope Audit

**2026-09-08 · Lane T.** After [`49` `P-31`](./49-P31-PARTIAL-TRANSITION-DOMAIN-REFUSAL-AND-NON-EVENT-PERSISTENCE-AUDIT.md).

> **Single question:** *is `GT-004`'s "explicit UNKNOWN reason" a `K5` persistence obligation?*
>
> ⛔ **`P-08`, `P-18`–`P-31`, the 11-cell kernel, Schema v2, 3MC, Lane M FROZEN · no Schema v3 · no
> `K12` · no `Unknown` aggregate · no architecture · no implementation · `P-30`/`P-31` verdicts NOT
> reopened · policy, policy-version and provenance-chain questions NOT reopened · well-foundedness /
> terminality / acyclicity DEFERRED.**

# 0. ⭐⭐⭐⭐ The answer is NO, on three independent grounds — and the first one indicts `P-31`'s own closing question

$$\boxed{\begin{array}{ll}\mathbf{1.\ STATUS} & GT\text{-}004 \textbf{ is } \mathbf{ILL\text{-}TYPED\ and\ ABANDONED.} \textbf{ It cannot ground any obligation.}\\[4pt] \mathbf{2.\ ESTATE} & \textbf{Verification is the } \mathbf{Assurance} \textbf{ actor's function — a DIFFERENT actor from KnowledgeOS.}\\[4pt] \mathbf{3.\ SUBJECT} & \textbf{Even if it stood, its reason is } \mathbf{why\ verification\ failed} \textbf{, not } \mathbf{why\ a\ fact\ was\ accepted.}\\ & \textbf{⭐ The corpus DOES require the second — and that one is } \mathbf{K3b}\textbf{, already in the kernel.}\end{array}}$$

⭐⭐⭐ **`P-31` built its closing question on `GT-004` without checking whether `GT-004` has standing.
It does not.** That is a **third methodological failure mode** in this series, distinct from the two
already recorded, and it is mine: *a witness's STATUS must be established before a question is built on
it.*

# 1. ⭐⭐⭐ Status — an earlier lane had already audited `GT-004`, and it fails twice

$$\boxed{\begin{array}{c}\mathbf{[EMP]}\ \textit{STEP-VERIFY-141-158}\text{:192}\\[4pt] \textbf{"} GT\text{-}004\textbf{: } \mathbf{ILL\text{-}TYPED.} \textbf{ 'evidence } \mathbf{or} \textbf{ an explicit UNKNOWN reason' — } \mathit{UNKNOWN\ reason} \textbf{ is } \mathbf{never\ given\ a\ type.}\\ \textbf{§145.21's } \mathit{Reason = source\ unavailable} \textbf{ is } \mathbf{free\ text.} \textbf{ A free-text field satisfies } GT\text{-}004 \textbf{ with the string } \mathtt{"?"}\textbf{.}\\ \mathbf{The\ invariant\ is\ UNFALSIFIABLE\ as\ written.}\textbf{"}\end{array}}$$

⭐⭐ **An obligation satisfiable by `"?"` obliges nothing.** ⛔ **It cannot be promoted to a kernel
persistence requirement.**

⭐⭐⭐ **And the second failure is worse — `GT-004` was ABANDONED:**

> *"**EVOLUTION** RESOLVES 144's specification gap · **then UNRESOLVED-by-abandonment.** Grep evidence:
> *"Golden Trace"* appears 6× in 155, 3× in 156v1, then **0× in 155A, 0× in 156-revision, 0× in 157,
> 0× in 135842, 0× in 140338**. **The 28 GT/GG/GE/GC/GA invariants are never cited again after 151.**
> **VERIFIER OBSERVATION: the band's single most developed formal object is silently dropped six steps
> after being minted, without supersession, refutation, or acknowledgement.**"*

⚠️ **Provenance checked before use:** `verification/spec/STEP-VERIFY-141-158.md` is an **earlier
verification lane's** audit, pre-existing in the corpus, `[EMP]` as to its **grep measurement** and
`[DERIVED]` as to its typing verdict. ⛔ **Not Lane T's, and not this session's.**

⭐ **And the same audit places `GT-004` at the bottom of its own band:** `GT-001`/`GT-002` are *"the
only two invariants in the entire band expressed as a checkable predicate over a named field"*;
`GT-004` is the **single `ILL-TYPED` entry** among twenty-eight.

$$\boxed{GT\text{-}004 \textbf{ is } \mathbf{[REFUTED]\ as\ a\ standing\ requirement} \textbf{ — ill-typed, unfalsifiable, and silently abandoned without supersession.}}$$

# 2. Estate — verification is **Assurance**, not KnowledgeOS

| `145.2` actor | function |
|---|---|
| Human · Agent · **KnowledgeOS** · Governance · Engineering system | — |
| ⭐⭐ **Assurance** | ⭐ ***"Determines whether the observed state satisfies the applicable rules."*** |

⭐⭐⭐ **`Assurance` is listed as a SEPARATE ACTOR from `KnowledgeOS`.** Verification is its function.

| structural check | result |
|---|---|
| `145.6` **Knowledge state** contents | ⭐ **identity · environment · version · repository configuration · infrastructure references · known constraints** — ⛔ **no Verification, no Rule, no Reason** |
| `145.7` on rules | ⭐⭐ ***"It is an addressable ASSURANCE OBJECT."*** |
| *"enters `K`" / "added to `K`" / "part of `K`"* for a verification | ⭐⭐⭐ **ZERO occurrences in the whole 1 636-line specification** |

⭐⭐ **And the company `GT-004` keeps is decisive.** All seven neighbours are governance/authorization
invariants: `Action.contextID ≠ null` · `Action.authorizationID ≠ null` · *"identifies its exact rule
version"* · *"a denied action cannot execute"* · *"an expired authorization cannot execute"* ·
*"recommendation ≠ authorization"* · *"agent memory cannot become authoritative merely through use"*.

$$\boxed{\textbf{⭐ Not one GT invariant concerns epistemic-state persistence. } GT\text{-}004 \textbf{ is a governance/assurance invariant BY NEIGHBOURHOOD as well as by actor.}}$$

⭐ **This is `P-31`'s pattern repeating exactly: the obligation is real in its own estate, and it is
not the epistemic estate's.**

# 3. ⭐⭐⭐ Subject — two different "reasons", and only one is epistemically required

| | reason | what it explains | estate |
|---|---|---|---|
| ⭐ **`ρ-fail`** | `GT-004` / `145.21` — `Reason = source unavailable` | ⭐ **why VERIFICATION could not complete** | ⭐⭐ **Assurance** |
| ⭐⭐⭐ **`ρ-warrant`** | `refinement-of-the-model:401` | ⭐⭐ **why the FACT was accepted** | ⭐ **epistemic** |

$$\boxed{\begin{array}{c}\mathbf{[EMP]}\ \textit{refinement-of-the-model}\text{:401}\\[4pt] \textbf{"So the reason must be preserved } \mathbf{alongside\ the\ fact.} \textbf{ Otherwise } \mathbf{we\ cannot\ understand\ why\ the\ fact\ was\ ever\ accepted.}\textbf{"}\end{array}}$$

⭐⭐⭐ **This is a genuine epistemic reason-preservation obligation — and it is `K3b`'s, not `K5`'s.**
*"Why the fact was ever accepted"* is the **warrant**, which `P-08` §16 already established and `P-30`
re-confirmed as transition-relative.

⚠️ **The two must not be merged.** `ρ-fail` explains an **absent** verification; `ρ-warrant` explains a
**present** acceptance. ⭐ **`P-32`'s question asked about the first and the corpus supplies the
second.**

⭐ **Note the source also reaches toward the kernel** — §8: *"**And this may tell us what the Kernel
actually needs** … `Fact = Proposition + Reason`" —* but *"may tell us"* is hedged, so it is
`[STIPULATED]` as to the kernel claim while `[EMP]` as to the preservation sentence.

# 4. `K5` scope — the prohibition is **not** upgraded

`P-19` restated `K5` as: ***the estate must not claim checkable grounding it does not have.***

| candidate upgrade | verdict |
|---|---|
| ⭐ **positive obligation to RECORD a reason** | ⭐⭐⭐ **`[REFUTED]`** — its only witness is ill-typed and abandoned |
| **obligation to record the VERDICT** *(`UNKNOWN` vs `PASS`/`FAIL`)* | ⚠️ **Assurance-side** — `verdict` is a field of the **Verification record**, and that record is not in `K` |
| ⭐ **the prohibition as stated** | ⭐⭐ **UNCHANGED and sufficient** — declining to claim grounding needs no stored reason |

$$\boxed{K5 \textbf{ remains a } \mathbf{PROHIBITION.} \textbf{ ⛔ It does not become a positive record-the-reason obligation.}}$$

⭐⭐ **The asymmetry that settles it:** *not claiming* is satisfied by **silence**; *recording why* would
require **content**. `GT-004` is the only witness that could have demanded content, and it demands
nothing a `"?"` would not satisfy.

# 5. ⭐⭐ `P-31` §11 — its GT-004 corroboration is WITHDRAWN, its verdict SURVIVES

`P-31` §11 qualified `P-30` §9 on three grounds: **`K5`** *(`P-19`'s restatement)*, **`K3b`** *(`P-08`
§5.3's honest downgrade)*, and ⚠️ **`GT-004`** *("corroborated by")*.

$$\boxed{\begin{array}{c}\textbf{⭐ The } GT\text{-}004 \textbf{ corroboration is } \mathbf{WITHDRAWN} \textbf{ — an ill-typed, abandoned invariant corroborates nothing.}\\ \boxed{\textbf{⭐⭐ } P\text{-}31\ \S11\textbf{'s } \mathbf{[QUALIFIED]} \textbf{ verdict STANDS on its other two grounds, which never depended on } GT\text{-}004.}\end{array}}$$

⭐ **`P-08` §5.3's honest downgrade and `P-19`'s `K5` restatement are Lane T derivations from primary
corpus material; neither is touched by this withdrawal.**

# 6. Falsification — both directions

| direction | result |
|---|---|
| ⭐ **FOR a `K5` record obligation** | `reason retained` **0** · `reason must be recorded` **5 files, all read** — ⭐⭐ **none is about verification failure**: a voting-schedule refinement, a `[EXP]`-tagged *"reason must be separate from value"*, `step-048`'s *"the **revision** reason must be traceable"*, `step_279`'s *"if associativity does not hold, the reason MUST be documented"* *(a mathematical proof obligation)*, and ⭐ **`refinement-of-the-model`'s warrant sentence — which is `K3b`** |
| ⭐⭐ **AGAINST** | ⭐⭐⭐ **`ILL-TYPED` · `unfalsifiable as written` · `UNRESOLVED-by-abandonment` · 0 citations after step 151 · `Assurance` a separate actor · verification absent from `145.6`'s knowledge state · rules are *"assurance objects"*** |
| ⚠️ **the `P-30` vocabulary trap re-checked** | ⭐ **concept searched, not just words**: `insufficient evidence` **119** · `unavailable` **147** · `cannot be checked/verified` **26** · `honest` **206** · `degrade/downgrade` **108** · `checkab*` **57** · `UNKNOWN` **959** · `Unresolved` **802** — ⭐⭐ **read at the concept level, and the epistemic hits land on `Unresolved` as a STATE, never on a retained REASON** |

⭐⭐ **The parallel structure is the sharpest single piece of evidence.** The same input produces two
different records in two estates:

$$\boxed{\begin{array}{ll}\textbf{EPISTEMIC } \textit{(step-008 §16)} & \mathit{Evidence: insufficient} \;\longrightarrow\; \mathbf{Unresolved} \qquad \textbf{⭐ NO reason field}\\ \textbf{ASSURANCE } \textit{(§145.21)} & \mathit{Evidence = insufficient} \;\longrightarrow\; \mathit{Verdict = UNKNOWN},\ \mathbf{Reason = source\ unavailable}\end{array}}$$

⭐⭐⭐ **The epistemic side records a STATE and no reason; the assurance side records a reason. The
corpus draws the line itself.**

⭐ **One `[STIPULATED]` counterweight, recorded not suppressed:** `step_180` — *"`Unknown` itself
deserves a first-class domain representation … **Often yes.** For important **engineering/governance**
questions, an explicit unknown can carry `Question`, `Scope`, …"* ⚠️ **Hedged (*"often"*), and scoped by
its own words to engineering/governance.**

# 7. New-cell test — **NOT TRIGGERED**

| condition | status |
|---|---|
| 1 corpus-grounded requirement | ⭐⭐⭐ 🔴 **FAILS — the requirement is ill-typed and abandoned** |
| 2 epistemic estate | ⭐⭐ 🔴 **FAILS — Assurance** |
| 3 not covered by `K1`–`K11` | 🔴 **FAILS** — the epistemic reason that IS required is **`K3b`** |
| 4 independence pair | ⛔ **not reached** |

$$\boxed{|K| = 11 \textbf{ — unchanged. No cell added, removed, split, merged or re-scoped. } K5\textbf{'s scope UNCHANGED.}}$$

---

# 8. `P-32` VERDICT

$$\boxed{\mathbf{NO.}\ GT\text{-}004\textbf{'s explicit UNKNOWN reason is } \mathbf{NOT} \textbf{ a } K5 \textbf{ obligation — it is not an obligation at all.}}$$

**Three independent grounds, any one sufficient:** ⭐ **`GT-004` is `ILL-TYPED` and `ABANDONED`** —
unfalsifiable, satisfiable by `"?"`, zero citations after step 151, *"silently dropped … without
supersession, refutation, or acknowledgement"* · ⭐ **verification belongs to `Assurance`**, a separate
actor, and its record never enters `145.6`'s knowledge state · ⭐ **its reason explains a FAILED
VERIFICATION**, whereas the reason the corpus does require epistemically explains **why a fact was
accepted** — and that is **`K3b`**.

## Status register
**`[EMP]`** ⭐ `GT-004` *"never given a type"* / *"free text"* / *"unfalsifiable as written"* ·
*"UNRESOLVED-by-abandonment"*, 0 citations after 151 · `Assurance` = *"determines whether the observed
state satisfies the applicable rules"*, a **separate actor** · `145.6`'s knowledge state excludes
verifications · rules are *"addressable **assurance objects**"* · all seven `GT` neighbours are
governance/authorization invariants · ⭐⭐ *"the reason must be preserved **alongside the fact** …
otherwise we cannot understand why the fact was ever accepted"* · `145.20`'s record schema has **no
`Reason` field** while `145.21`'s example does.
**`[DERIVED]`** ⭐ **`K5` stays a prohibition** — *not claiming* is satisfied by silence · the
`ρ-fail` / `ρ-warrant` split · `ρ-warrant` is **`K3b`** · the two-estate parallel structure
*(`Unresolved` without a reason vs `UNKNOWN` with one)*.
**`[CORROBORATION]`** the earlier verification lane's band-wide typing audit *(provenance checked;
`[EMP]` as to grep, `[DERIVED]` as to typing)*.
**`[STIPULATED]`** `step_180`'s *"often yes"* first-class `Unknown`, self-scoped to
engineering/governance · `refinement-of-the-model` §8's `Fact = Proposition + Reason` *("may tell
us")*.
**`[REFUTED]`** ⭐⭐ **`GT-004` as a standing requirement** · `K5` as a positive record-the-reason
obligation · ⭐ **`P-31` §11's `GT-004` corroboration — WITHDRAWN**.
**`[QUALIFIED]`** ⭐ **`P-31`'s closing question** — it presupposed `GT-004` had standing and did not
check.
**`[UNWITNESSED]`** a typed `UnknownReason` · `reason retained` · an epistemic obligation to record why
verification failed.
**`[OPEN]`** ⭐ **the 28 abandoned `GT`/`GG`/`GE`/`GC`/`GA` invariants have no disposition** — dropped
without supersession · `145.20`/`145.21`'s `Reason`-field discrepancy · the corpus's admitted-transition
**or** infrastructure disjunction *(`P-31`)* · `ω`'s ungloss ed partiality *(`P-31`)* · `Accepted ⇒
Warrant` *(`P-30`)* · `Governance`-in-`K` vs `P-28`'s two estates · `W3`'s conditional *(`P-29`)* ·
`Ind_ρ`'s five other slots · `K4b-ii`'s corroboration arm · register deletion · *"estate"*'s two
referents · ⛔ **well-foundedness / terminality / acyclicity — DEFERRED.**

## ⭐ ONE next unresolved question

$$\boxed{\begin{array}{c}\textbf{Is the PROPOSITION / WARRANT split a kernel artifact, or is it corpus-grounded?}\\[4pt] \boxed{\begin{array}{l}\textit{refinement-of-the-model} \textbf{ §8 writes } \mathbf{Fact = Proposition + Reason} \textbf{ and says } \textit{"this may tell us what the Kernel actually needs."}\\ \textbf{⭐ } P\text{-}08 \textbf{ derived } \mathbf{K4a\text{-}i} \textbf{ (prior proposition) and } \mathbf{K3b} \textbf{ (warrant) as } \mathbf{SEPARATE\ cells.}\\ \textbf{⭐⭐ If the corpus holds a fact to be a COMPOSITE of the two, the separation may be Lane T's, not the corpus's —}\\ \textbf{and } P\text{-}30 \textbf{ has already had to move historical acceptance ACROSS that very boundary.}\end{array}}\end{array}}$$

```
NO — GT-004's EXPLICIT UNKNOWN REASON IS NOT A K5 OBLIGATION. IT IS NOT AN OBLIGATION AT ALL.
KERNEL REMAINS 11 CELLS. K5's SCOPE UNCHANGED — IT STAYS A PROHIBITION.

THREE INDEPENDENT GROUNDS, ANY ONE SUFFICIENT.

1. STATUS. An earlier verification lane had ALREADY audited GT-004: "ILL-TYPED. 'evidence or an
   explicit UNKNOWN reason' — UNKNOWN reason is never given a type. §145.21's Reason = source
   unavailable is FREE TEXT. A free-text field satisfies GT-004 with the string "?". The invariant is
   UNFALSIFIABLE AS WRITTEN." And worse — it was ABANDONED: "UNRESOLVED-by-abandonment", 0 citations
   after step 151, "the band's single most developed formal object is silently dropped six steps after
   being minted, without supersession, refutation, or acknowledgement." An obligation satisfiable by
   "?" obliges nothing.

2. ESTATE. Verification is the ASSURANCE actor's function — "determines whether the observed state
   satisfies the applicable rules" — and Assurance is listed as a SEPARATE ACTOR from KnowledgeOS.
   §145.6's knowledge state contains only claims about the subject; no Verification, no Rule, no
   Reason, and ZERO "enters K" language in 1,636 lines. Rules are "addressable ASSURANCE OBJECTS." All
   seven GT neighbours are governance/authorization invariants — not one concerns epistemic
   persistence.

3. SUBJECT. Even had it stood, GT-004's reason explains WHY VERIFICATION FAILED. The reason the corpus
   DOES require epistemically explains WHY A FACT WAS ACCEPTED — "the reason must be preserved
   alongside the fact ... otherwise we cannot understand why the fact was ever accepted." That is the
   WARRANT, and it is K3b, already in the kernel. The two reasons must not be merged: one explains an
   ABSENT verification, the other a PRESENT acceptance.

THE PARALLEL STRUCTURE IS THE SHARPEST EVIDENCE — the same input, two estates, two records:
   EPISTEMIC (step-008 §16):  Evidence insufficient -> Unresolved            NO reason field
   ASSURANCE (§145.21):       Evidence insufficient -> UNKNOWN, Reason = source unavailable
The epistemic side records a STATE and no reason. The corpus draws the line itself.

AND A THIRD METHODOLOGICAL FAILURE MODE, WHICH IS MINE: P-31's closing question was built on GT-004
WITHOUT CHECKING WHETHER GT-004 HAS STANDING. It does not. After P-30's lesson (vocabulary absence
does not prove obligation absence) and P-31's (notation does not establish ontology), the third is: a
witness's STATUS must be established before a question is built on it. P-31 §11's GT-004 corroboration
is WITHDRAWN; its [QUALIFIED] verdict STANDS on its other two grounds, K5 and K3b, neither of which
depended on GT-004.

THE ASYMMETRY THAT SETTLES K5: "not claiming" is satisfied by SILENCE; "recording why" would require
CONTENT. GT-004 was the only witness that could have demanded content, and it demands nothing a "?"
would not satisfy.

ALSO SURFACED, NOT SUPPRESSED: §145.20's verification record schema has NO Reason field while §145.21's
worked example carries one — an unresolved internal discrepancy; and step_180's "often yes, Unknown
deserves a first-class representation" is hedged and self-scoped to engineering/governance questions.

CARRIED OPEN, NEW: all 28 GT/GG/GE/GC/GA invariants were dropped without supersession and have no
disposition.

NEXT QUESTION (one): IS THE PROPOSITION / WARRANT SPLIT A KERNEL ARTIFACT OR CORPUS-GROUNDED?
  refinement-of-the-model §8 writes Fact = Proposition + Reason and says "this may tell us what the
  Kernel actually needs." P-08 derived K4a-i (prior proposition) and K3b (warrant) as SEPARATE cells.
  If the corpus holds a fact to be a COMPOSITE, the separation may be Lane T's rather than the
  corpus's — and P-30 has already had to move historical acceptance across that very boundary.

NO ARCHITECTURE — NO SCHEMA v3 — NO UNKNOWN AGGREGATE — P-30/P-31 NOT REOPENED — 3MC AND LANE M
UNTOUCHED — WELL-FOUNDEDNESS STILL DEFERRED.
```
