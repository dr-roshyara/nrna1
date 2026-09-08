# `P-26` — Provenance-Chain Unbrokenness & `K5` Scope Audit

**2026-09-08 · Lane T.** After [`42` `P-25`](./42-P25-RECORD-ATTRIBUTION-AND-PERSISTENCE-OBLIGATION-AUDIT.md).

> **The single question:** *is provenance-chain unbrokenness a `K5` obligation, or does a chain require
> more than single-link checkability?*
>
> ⛔ **`P-08` baseline · `P-18`–`P-25` unmodified · 3MC read-only · Schema v2 untouched · no Schema v3 ·
> no architecture · no implementation · no canonical theory · no cell added, split, merged, deleted or
> re-scoped without an independent proof · Lane T constructions are not corpus evidence · no silent
> repair.**

# 0. ⭐⭐⭐ The answer, and the corpus states it in a containing key

$$\boxed{\begin{array}{c}\textbf{NO. Chain unbrokenness is NOT a } K5 \textbf{ obligation, and NOT a persistence obligation at all.}\\[6pt] \boxed{\textbf{Its SOLE corpus home is a rule under } \mathbf{EvidenceAdmissibility}\textbf{. It is an ADMISSIBILITY rule.}}\end{array}}$$

---

# 1. The search

| pattern | files |
|---|---|
| ⭐ **`provenance chain`** | **53** |
| `chain of (custody\|provenance\|evidence\|derivation)` | **22** — largely the **isnād** transmission lens |
| `unbroken` | 19 · `chain unbroken` **2** · `chain complete` 2 · `provenance depth` 2 · `broken chain` 1 · `chain integrity` 1 |
| `transitive` | 113 |
| ⭐ `second-order provenance` | **1** · ⭐⭐ **`provenance of provenance` → 0** |
| ⭐⭐ **`admissibility` / `admissible`** | ⭐ **238 / 398 — a heavily attested corpus category, distinct from persistence** |

# 2. ⭐⭐⭐ Witness 1 — the decisive one

`[EMP]` `brainstorming/20260823-104251-constitutional-dsl-intent-vocabulary-and-evidence-weighting-model.md:348-362`:

```yaml
EvidenceAdmissibility:
  rules:
    - name: "evidence_unbroken_provenance"
      description: "Evidence provenance chain must be unbroken"
      check: for each step in evidence.provenance.history:
               step.actor in identity_registry
               AND step.evidence is not null
               AND step.evidence is admissible
```

⭐ **Measured: `evidence_unbroken_provenance` occurs ONCE, in ONE file, under ONE key.**

$$\boxed{\begin{array}{c}\textbf{The containing key is } \mathbf{EvidenceAdmissibility}. \textbf{ The corpus itself classifies unbrokenness as an ADMISSIBILITY rule.}\\ \textbf{⛔ It is not stated as a retention rule, a preservation rule, or a persistence capability anywhere.}\end{array}}$$

## The `check` decomposed — three conditions, and each already has a home

| condition | what it demands | where it lives |
|---|---|---|
| `step.evidence is not null` | ⭐ **every link must be RETAINED** | ⭐ **`K2`** *(current source value)* · **`K3a`** *(prior value under `τ6`)* · **`K4a-i`** *(the retained argument)* |
| `step.evidence is admissible` | ⭐ **RECURSION over steps** | ⭐⭐ **iterated admissibility — not a persistence datum at all** |
| ⭐⭐⭐ `step.actor in identity_registry` | ⭐⭐ **ATTRIBUTION — the producing actor must be a registered identity** | ⭐⭐⭐ **an ADMISSIBILITY condition — §4** |

# 3. Witnesses 2 and 3, and their weaker status

| | witness | class |
|---|---|---|
| **2** | `kernel-ddd-critical-review-god-object-conflations.md` — *"4. **Provenance preservation** (is the provenance chain unbroken?)"* and `\| Provenance preservation \| Provenance \| Provenance chains are unbroken \| Knowledge Context \|` | ⭐ **`[CORROBORATION]`** — a **DDD critical review**, i.e. a review construct, ⛔ and its *Knowledge Context* assignment is **not** imported |
| ⭐ **3** | `20260822-0135-…relationship-invariant-preservation.md:574` — `\| Projection → Source \| Provenance preservation \|` | ⭐⭐ **an INVARIANT OF A RELATIONSHIP**, not a storage obligation — ⭐ a constraint on which **projections** are admissible, and `P-08`'s `π_K` is **lossy**, so this too is admissibility-flavoured |

$$\boxed{\textbf{Three witnesses, three framings — admissibility rule · review construct · relationship invariant. ⛔ NONE frames unbrokenness as a persistence capability.}}$$

# 4. ⭐⭐⭐ An independent corroboration of `P-25`

`P-25` concluded that **record-attribution is not a persistence obligation.** ⭐⭐ Witness 1's first
condition — `step.actor in identity_registry` — **requires attribution**.

$$\boxed{\begin{array}{c}\textbf{And it requires it FOR ADMISSIBILITY, not for PERSISTENCE.}\\[4pt] \boxed{\textbf{⭐ So the rule that looked like a threat to } P\text{-}25 \textbf{ CONFIRMS it: attribution is an ADMISSIBILITY input, exactly where } P\text{-}25 \textbf{ placed it.}}\end{array}}$$

⭐ **Two independent phases converging on the same boundary is the strongest signal in this audit.**

---

# 5. The two failure modes, kept apart

| mode | what fails | who bears it |
|---|---|---|
| ⭐ **MISSING LINK** *(structural)* | a step's evidence is **absent** | ⭐⭐ **link retention — `K2`/`K3a`/`K4a-i`.** ⛔ **NOT `K5`** |
| ⭐ **UNCHECKABLE LINK** *(grounding)* | a step's link is present but its **external ground is gone** | ⭐⭐ **`K5`** |

$$\boxed{\begin{array}{c}\textbf{"Unbroken" and "checkable" are INDEPENDENT: a chain can be structurally complete with an uncheckable}\\ \textbf{link, and every link checkable with one link MISSING.}\\[6pt] \boxed{\textbf{⇒ Chain unbrokenness is a CONJUNCTION of existing obligations, and only HALF of it is } K5\textbf{'s.}}\end{array}}$$

# 6. Does `K5` generalize to depth?

`K5` in `P-19`'s restated form: **the estate must not claim checkable grounding it does not have.**

| | |
|---|---|
| is that stated per **link** or per **claim**? | ⭐⭐ **per CLAIM** |
| ⭐ consequence | ⭐⭐⭐ **if the estate claims a CHAIN is checkable, `K5` binds the CHAIN claim — at whatever depth is claimed** |

$$\boxed{K5 \textbf{ ALREADY generalizes to arbitrary depth, without modification, BECAUSE its restated form is claim-level rather than link-level.}}$$

⭐ **A retrospective vindication of `P-19` §8's restatement**: the link-level phrasing it replaced would
have needed extending here; the claim-level phrasing does not.

# 7. Independence tests

| pair | all 11 cells identical? | verdict |
|---|:--:|---|
| `a→b→c` all links retained **vs** the middle link **missing** | ⭐ 🔴 **NO — `K2`/`K3a` differ** *(the linking source value is absent)* | **condition 3 fails ⇒ covered** |
| middle link present and **checkable** **vs** present but its ground **vanished** | 🔴 **no — `K5` differs by construction** | **covered by `K5`** |
| ⭐ chain **length/depth** differs, all links retained and checkable | ⭐⭐ **depth is DERIVABLE from the retained links** — `P-16` §14: **paths, reachability and transitive closure are derived, never stored** | ⭐ **no new capability** |

$$\boxed{\textbf{No admissible pair isolates a chain property beyond link retention and per-link checkability.}}$$

⭐ **And `P-16`'s result does the work again:** *unbrokenness* is a **predicate over retained edges**, so
it is **computed, not persisted**.

# 8. Is anything about a chain NOT reducible? — one candidate, and it is not unbrokenness

⭐⭐ **A chain can be UNBROKEN and yet CIRCULAR** — `a` grounded in `b`, `b` grounded in `a`. Every link
retained, every link checkable, the recursion never terminating in anything external.

$$\boxed{\begin{array}{c}\textbf{"Unbroken" does NOT entail WELL-FOUNDED. These are different properties.}\\ \textbf{⭐ And witness 1's } \mathit{check} \textbf{ is RECURSIVE } (\textit{step.evidence is admissible})\textbf{, so its termination is a real question.}\end{array}}$$

| is well-foundedness witnessed? | ⭐ **partly — and NOT unwitnessed:** `regress` **131 files** · `acyclic` **95** · `well-founded` **27** · `provenance cycle` **5** · ⚠️ **`circular provenance` 0 · `provenance of provenance` 0** |
|---|---|

⛔ **Not resolved here** — it is a **different question**, and `P-26` was commissioned for unbrokenness.
⭐ **Recorded as `O-P26-1`, and it is the next question.**

# 9. Verdict

| # | question | answer |
|---|---|---|
| **1** | is chain unbrokenness corpus-attested? | ⭐⭐ **YES — `[EMP]`, verbatim: *"Evidence provenance chain must be unbroken"*** |
| **2** | as what? | ⭐⭐⭐ **an ADMISSIBILITY rule — its sole home is the `EvidenceAdmissibility` key** |
| **3** | is it a `K5` obligation? | ⭐⭐ **NO — only its CHECKABILITY half is `K5`'s; its STRUCTURAL half is link retention** |
| **4** | is it a persistence obligation at all? | ⭐⭐⭐ **NO** |
| **5** | does a chain need more than single-link checkability? | ⭐ **YES — it also needs link RETENTION.** ⛔ **But both are existing obligations** |
| **6** | does `K5` cover arbitrary depth? | ⭐⭐ **YES, unmodified — because `P-19` restated it at CLAIM level** |
| **7** | new capability? | ⛔ **NO** |
| **8** | cardinality? | ⭐ **11 — unchanged** |
| **9** | anything withdrawn? | ⛔ **nothing.** ⭐ `P-25` is **independently corroborated** — §4 |
| **10** | ⭐ **the ONE next question** | ⭐⭐⭐ **Is provenance WELL-FOUNDEDNESS — that the recursion terminates in something external, with no cycles — a persistence obligation, an admissibility condition, or neither?** *(Witness 1's `check` is recursive, and `regress` is discussed in 131 files, so this is attested rather than invented.)* |

## ⭐⭐ The distinction this audit names

$$\boxed{\begin{array}{c}\textbf{PERSISTENCE SUPPLIES. ADMISSIBILITY CONSUMES.}\\[4pt] \textbf{Unbrokenness is a PREDICATE the estate EVALUATES over what persistence retained —}\\ \textbf{not a datum persistence must additionally keep.}\end{array}}$$

⭐ **`admissibility`/`admissible` at 238/398 files makes this a corpus-scale category**, and it explains
why three separate provenance questions (`P-25`'s attribution, `P-26`'s unbrokenness, and witness 1's
`actor in identity_registry`) all land **outside** the persistence kernel while being **required** of
the estate.

# 10. Provenance of this artifact
**`[EMP]`** `evidence_unbroken_provenance` under **`EvidenceAdmissibility`**, one file, one occurrence,
with its three-condition `check` · `provenance chain` **53** files · `admissibility`/`admissible`
**238/398** · `Projection → Source \| Provenance preservation` · the DDD review's *"is the provenance
chain unbroken?"* · `regress` **131** · `acyclic` **95** · `well-founded` **27** · `provenance cycle`
**5** · ⭐ `provenance of provenance` **0**.
**`[DERIVED]`** ⭐⭐⭐ **unbrokenness = link retention ∧ per-link checkability, both existing** · ⭐⭐
**`K5` generalizes to depth because it is claim-level** · ⭐ **missing-link and uncheckable-link are
INDEPENDENT failure modes** · **depth is derivable, per `P-16` §14** · ⭐⭐ **unbroken ⇏ well-founded** ·
⭐ **persistence supplies, admissibility consumes**.
**`[CORROBORATION]`** the DDD critical review · the **isnād** transmission lens *(22 files, imported)*.
**`[OPEN]`** ⭐ **`O-P26-1` well-foundedness** · whether the recursion's termination is decidable · the
`[OPEN]`s carried from `P-25` *(`A6` producer-vs-recorder-vs-citer, the three relations, `P2`)* ·
`K4b-ii`'s corroboration arm · register deletion · *"estate"*'s two referents.
⛔ **No Lane T construction used as corpus evidence; the DDD review's *Knowledge Context* assignment was
NOT imported; nothing rewritten.**

---

```
NO — PROVENANCE-CHAIN UNBROKENNESS IS NOT A K5 OBLIGATION, AND NOT A PERSISTENCE OBLIGATION AT ALL.
IT IS AN ADMISSIBILITY RULE. KERNEL REMAINS 11 CELLS. NO NEW CAPABILITY. NOTHING WITHDRAWN.

THE CORPUS CLASSIFIES IT IN THE CONTAINING KEY. The rule exists verbatim — "Evidence provenance chain
must be unbroken" — in exactly ONE file, ONE occurrence, under ONE key: EvidenceAdmissibility. It is
nowhere stated as a retention, preservation or persistence rule.

AND ITS OWN CHECK DECOMPOSES INTO THINGS THAT ALREADY HAVE HOMES:
  step.evidence is not null      -> LINK RETENTION -> K2 / K3a / K4a-i
  step.evidence is admissible    -> RECURSION, an admissibility matter, not a persistence datum
  step.actor in identity_registry -> ATTRIBUTION, required FOR ADMISSIBILITY
The third condition is the significant one: the rule that looked like a threat to P-25 CONFIRMS it.
P-25 concluded attribution is not a persistence obligation; this rule requires attribution for
ADMISSIBILITY — exactly where P-25 placed it. Two independent phases converging on the same boundary.

TWO INDEPENDENT FAILURE MODES, KEPT APART: a chain can be structurally complete with an UNCHECKABLE
link, and every link checkable with one link MISSING. So unbrokenness is a CONJUNCTION — link retention
(K2/K3a/K4a-i) AND per-link checkability (K5) — and only HALF of it is K5's.

K5 ALREADY COVERS ARBITRARY DEPTH, UNMODIFIED, because P-19 restated it at CLAIM level: "the estate must
not claim checkable grounding it does not have" binds whatever depth is claimed. A retrospective
vindication of that restatement — the link-level phrasing it replaced would have needed extending here.

NO ADMISSIBLE PAIR ISOLATES A CHAIN PROPERTY BEYOND THOSE TWO. Depth is DERIVABLE from retained links,
by P-16 §14's result that paths, reachability and transitive closure are computed and never stored.

ONE CANDIDATE IS NOT REDUCIBLE — AND IT IS NOT UNBROKENNESS. A chain can be UNBROKEN yet CIRCULAR: every
link retained and checkable, the recursion never terminating in anything external. UNBROKEN DOES NOT
ENTAIL WELL-FOUNDED, and witness 1's check IS recursive, so termination is a real question. It is
attested rather than invented — regress 131 files, acyclic 95, well-founded 27, provenance cycle 5 —
and it is deliberately NOT resolved here, because P-26 was commissioned for unbrokenness.

THE DISTINCTION THIS AUDIT NAMES: PERSISTENCE SUPPLIES; ADMISSIBILITY CONSUMES. Unbrokenness is a
PREDICATE the estate EVALUATES over what persistence retained, not a datum persistence must keep. With
admissibility/admissible at 238/398 files this is a corpus-scale category, and it explains why three
separate provenance questions — P-25's attribution, P-26's unbrokenness, and this rule's
actor-in-registry condition — all land OUTSIDE the persistence kernel while being REQUIRED of the estate.

NEXT QUESTION (one): IS PROVENANCE WELL-FOUNDEDNESS — termination in something external, with no cycles
  — A PERSISTENCE OBLIGATION, AN ADMISSIBILITY CONDITION, OR NEITHER?

NO ARCHITECTURE — NO SCHEMA v3 — NO IMPLEMENTATION — NO CANONICAL THEORY — 3MC UNTOUCHED — NO CELL
ADDED, SPLIT, MERGED, DELETED OR RE-SCOPED — NO SILENT REPAIR.
```
