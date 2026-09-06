---
artifact: INDEPENDENT-CLOSURE-REVERIFICATION
mandate: 20260830 re-verification §2–§5, §13
date: 2026-08-30
status: **THE CLOSURE VERDICT IS NOT SUSTAINED — 0 of 6 gaps VERIFIED as claimed**
method: corpus re-read from primary sources · executed probes · git-date fingerprinting
---

# Independent Re-Verification of the Six Claimed Closures

`THEORY-CLOSURE-AUDIT.md` is treated here as **a claim to be attacked**, not as a record.
Nothing below is accepted because that document says it.

## 0. Result

| Gap | Previous claim | Re-verified verdict |
|---|---|---|
| **G1** authority→gate binding | `CORPUS ESTABLISHES` | **REFUTED** — the corpus supplies a *signature*, never a definition; the binding asked for is not the binding supplied |
| **G2** qualification predicate | `CORPUS ESTABLISHES` | **SOURCE ESTABLISHES (non-incorporated)** — the quotation is real but is DeepSeek research under a standing "NOT INCORPORATED" ruling; the *typed interface* is a verifier construction |
| **G3** Authority overloaded | `CORPUS ESTABLISHES` | **PARTIALLY VERIFIED** — three senses separated, a fourth (EKP artifact trust-grade) left unhandled |
| **G4** Provenance overloaded | `FORMALLY DERIVED` | **VERIFIED** — the only closure that survives intact |
| **G5** `E`/`V` overloaded | `FORMALLY DERIVED` | **REFUTED** — the corpus's own latest reconstruction records them as *live* ambiguities, and a worse overload (`Ω`, ≥4 global senses) was never registered |
| **G6** policy-change authorisation | `CORPUS ESTABLISHES` + `IMPLEMENTATION CONFIRMED` | **CIRCULAR / SUPERSEDED** — the ratified model already closed it by a *different* mechanism (I-11) that the audit never consulted; the implementation evidence measures a string field, not a binding |

**Independence check (§13):** every corpus source cited below is committed to git on or before
**2026-08-28**; this verification programme began **2026-08-29**. **No cited corpus evidence
post-dates a verifier finding.** The feedback-loop risk is real for the *verifier's own* artifacts
and is why they are not used as evidence here.

---

## 1. G1 — authority → gate binding · **REFUTED**

| Field | Finding |
|---|---|
| **Previous claim** | closed by `c_t = Authorize(N_t, a_t, Policy_t)` (Q17) + `Auth(a,r,c,p)` (230.14) + `Authority=f(Actor,Role,Action,Scope,Policy,Time,Context)` (179) |
| **Source** | `…/question-17-…md:1779-1814` · `# Step 230**…:535-559` · `# Step 179 —…:1495` |
| **Is it a definition?** | **NO.** Q17 §6.2 gives `Authorize : (𝒩 × 𝒜 × Policy) → 𝒞` — arity and argument names. `grep` for any definitional form (`:=`, `≜`, `≡`, "is defined as") over the whole corpus returns **zero hits**. The function has **no body anywhere**. |
| **Normative?** | **NO.** The passage introducing it reads *"The **missing function** is…"* — it is a proposed correction inside a non-authoritative raw research file (Stratum 1). |
| **Typed?** | **Incorrectly.** Codomain is declared `𝒞` (commands), but §6.3's outcome enum is `{Authorized, Rejected, Deferred, Modified}`. Three of four outcomes are **not commands**. The function is partial and the partiality is unacknowledged. `Modified` additionally permits the output to contain content **not present in `a_t`**, with no constraint on what may be added. |
| **All symbols defined?** | **NO.** `𝒩` (the Knower) is never defined anywhere — no space, no identity criterion, no equality. |
| **Independent evidence?** | **NO.** The three citations are **three different relations of arity 3, 4 and 7**, never reconciled with each other. Within Q17 itself the passage is a duplicated block (lines 1779-2088 recur at 2385-2694): **repetition inside one file is not corroboration.** |
| **Counterexample** | Below. |
| **Verdict** | **REFUTED** |

### 1.1 The chain the mandate asked to reconstruct

```
human authority act → authorization object → command → execution → event → K-transition
     ⌀ (no object)        ⌀ (no object)      c_t        e_t        e_t        δ(K,e)
```
**Two of the six links have no object in the theory at all.** There is no `AuthorityAct` type and no
`Authorization` type; `Authorize` returns a command directly. Consequently:

- **What is the identity of an authority act?** *Unanswerable* — the act is not an object.
- **What makes an authority act valid?** *Unanswerable* — nothing types validity of an act.
- **What binds the act to the command?** *Nothing.* `c_t` records `by`, not *which act*.
- **Can two commands refer to the same authority act?** *Inexpressible* — acts have no identity to share.
- **Can one command contain multiple authority acts?** *Inexpressible*, same reason.

### 1.2 Three counterexamples (as mandated)

**CE-G1-1 · Revocation after command creation.**
`Authorize(N,a,Policy_v3) → c`. The grant to `N` is then revoked. `Execute(c)` is a total function of
`c` alone. **`c` carries no reference to the authority that produced it**, so execution proceeds and
`δ` accepts the event. *A revoked authority still mutates `K`, and no invariant is violated.*

**CE-G1-2 · Policy change between authorization and execution.**
`c` records `policyVersion` only if an implementer chooses to put it there — the theory's `𝒞` has no
declared fields. `Execute : 𝒞 → Event` does not take `Policy`. **A command authorized under `v3` is
executed under `v4` with no detection point.** The theory's own §13 makes version part of policy
identity, which makes this a silent identity violation.

**CE-G1-3 · Execution with no authority act.**
`δ(K_t, e_t)` requires only `Pre(K_t, e_t)`. Nothing in `δ`'s signature or precondition mentions
authorization. **An event manufactured directly, bypassing `Authorize`, transitions `K` normally.**
The corpus invariant *"Only validated domain events may mutate K"* is stated in prose and has **no
formal counterpart in `δ`**, because `Event` carries no authorization field.

### 1.3 Why the cited evidence cannot close box 6

Box 6 asked: **which competence satisfies which gate.** The gate model is `Gate = ⋀ᵢ gᵢ` where each
`gᵢ` is a predicate over a decision `d`. **No `gᵢ` anywhere is typed as consuming an authority.**
`Auth(a,r,c,p)=True ⇒ legitimate` is a *global guard*, not a *per-gate binding*. The question is
untouched by all three citations.

---

## 2. G2 — qualification / sufficiency · **SOURCE ESTABLISHES (non-incorporated)**

| Field | Finding |
|---|---|
| **Previous claim** | `Sufficiency = constitution-dependent parameter`, therefore "not an undefined symbol"; `CORPUS ESTABLISHES` |
| **Source, located** | `brainstorming/kernel/20260823-114358-deepseek-kernel-twelve-fundamental-questions.md:197-205` — the quotation is **verbatim accurate** |
| **What kind of source?** | A **DeepSeek answer to twelve questions**. No frontmatter, no status, no authority field, Stratum 1 (raw research, declared non-authoritative). |
| **Standing governance ruling** | `analysis/governance-notes.md:624-627` — *"DeepSeek mathematics never becomes architecture; at most, later, 'possible mathematical interpretation.'"* and the standing line **`DeepSeek NOT INCORPORATED`**. |
| **Is the interface in the corpus?** | **NO.** `grep` for `Qualify` corpus-wide returns **one** hit — `CaptureAndQualify(O)` (Step 170), undefined. **`Qualify : Observation × Policy ⇀ Evidence` is a verifier construction**, correctly labelled `VERIFIER RECOMMENDS`, incorrectly labelled `CORPUS ESTABLISHES`. |
| **Verdict** | **SOURCE ESTABLISHES** for the *principle*; **PROPOSED (verifier)** for the *interface*. Not `CORPUS ESTABLISHES`. |

### 2.1 Testing the parametricity claim rigorously (mandate §4)

The claim *"a symbol bound at application time is not an undefined symbol"* is **true in general and
insufficient here**, for a reason the audit did not test. Classify:

| Class | Is `Sufficient` in it? |
|---|---|
| undefined | no — a principle exists |
| **abstract but well-typed** | **NO — this is the failure.** No signature, domain, codomain or value set is declared anywhere in the corpus |
| externally supplied | yes, by the constitution |
| constitutionally defined | yes, in prose |
| computable only after instantiation | would be true *if* the interface existed |

**A parameter without a declared type is not a parameter; it is a hole.** The distinction is
load-bearing: two constitutions can only be *plugged into the same kernel* if the socket is typed.

### 2.2 Two constitutions, executed reasoning

- `C_civil`: `Sufficient(e,ctx) = |{x∈e : polarity=supports, state=active}| ≥ 1`
- `C_criminal`: `Sufficient(e,ctx) = (≥3 independent sources) ∧ (no active contradicting evidence)`

Both are expressible as `Gates` in `Policy = (id,version,Gates,ValidityInterval,ResolutionBehavior)`
**and the kernel's semantics do not change** — `Apply` is unchanged. **So far the claim holds.**

It fails one step later. `C_criminal` needs `independent sources`. Independence is a relation
**between evidence items**; `Evidence` (amended, 9 fields) carries `source` but the theory declares
**no independence relation on `ℰᵥ`**. A gate `g(d)` is a predicate over a *decision*, and there is no
declared accessor from `d` to the evidence set with its cross-item structure. **`C_criminal` is not
expressible in the current `Policy` type.**

**Consequence:** the parametricity defence holds only for constitutions whose sufficiency is a
*per-item fold*. It fails for any constitution requiring *relational* sufficiency (independence,
corroboration, diversity) — which is the normal case in the corpus's own examples ("beyond
reasonable doubt").

> **`SUFFICIENT : Evidence × Context × Constitution → {True, False, Unknown}` is ABSENT from the
> corpus, and the argument that its absence is harmless is FALSIFIED by the corroboration case.**
> Recorded as a remaining specification gap: **TG-02**.

---

## 3. G3 — Authority overloaded · **PARTIALLY VERIFIED**

The audit's three-way split (competence · verdict · standing) is a **real and useful separation**, and
`Authentication ≠ Authorization`, `Identity ≠ Authority` are genuinely corpus-established (230.14).

**But a fourth sense was measured and is unhandled.** `docs/knowledge/schema/authorities.yaml` types
`authority` as a **five-rank trust grade of a document**
(`authoritative · derived · generated · historical · provisional`). That is a property of an
**artifact**, not of an **actor**. The audit mapped it to "Standing", which it defines as an ordinal
attribute of an actor's authority. **Artifact-trust-grade and actor-standing are different subjects
of predication**; collapsing them is the same category error the audit corrected elsewhere.

**Verdict: PARTIALLY VERIFIED.** Three of four senses separated. Recorded as **TG-07**.

---

## 4. G4 — Provenance · **VERIFIED**

The four-object split is sound and each object is independently anchored:

| Object | Independent anchor | Re-checked |
|---|---|---|
| `Π` AssertionOrigin | the `t=0` counterexample (History empty, origin still exists) | **holds** — it is a genuine proof, not an assertion |
| EvidenceProvenance | `230.15` `E_q=(source,observation,context,time,method,provenance)` | **quotation verbatim accurate** |
| `History(T)` | executed `History(K) ≠ K` | **holds** |
| MessageProvenance | `(correlationId, causationId)` | **measured: 18 PHP files** under `app/Contexts/…`, real |

**This is the one closure that survives adversarial re-verification unchanged.**
One refinement: 230.15 indexes evidence **by claim** (`E_q`). The amended 9-field `Evidence` **dropped
the claim index**, and with it the fact that the same observation may be evidence *for* one claim and
irrelevant to another. Recorded as **TG-08** (minor).

---

## 5. G5 — `E`/`V` overloading · **REFUTED**

| Field | Finding |
|---|---|
| **Previous claim** | "local rebindings in isolated branches, not rival global definitions" → **"Every canonical term now has exactly one meaning."** |
| **Contradicted by the corpus itself** | `# step 263.md:9` — *"the symbols `E` and `V` are overloaded elsewhere in the corpus. `E` is used both for Entity and events; `V` for Value and vertices. **The reconstruction explicitly records these as live ambiguities.**"* This is the corpus's **latest** reconstruction, and it records the ambiguities as **open**, not local. |
| **Worse, unregistered** | **`Ω` carries at least four global senses**, measured: ① sample space of `(Ω,ℱ,P)`; ② **Knowledge Space** — `Zero(K_t) = Ω \ Represented(K_t)`; ③ **observation function** `Ω : W → O` (31.18); ④ remaining possibilities in evidence combination. Plus `Ω_D` (reality space), `Ω_E` (epistemic state space), `Ω_A` (a source status tuple in `ladder_dc_reference.py`). |
| **Severity** | `Ω` is the *worst* overload in the corpus because senses ② and ③ are **both foundational and mutually inconsistent in direction**: ② is a superset of knowledge, ③ is a map from world to observation. Any reader combining them silently equates the knowledge space with the observation codomain. |
| **Verdict** | **REFUTED.** The claim "every canonical term now has exactly one meaning" is false by direct measurement. |

---

## 6. G6 — policy-change authorisation · **CIRCULAR / SUPERSEDED**

### 6.1 The implementation evidence, re-measured — and reinterpreted

I re-ran the measurement over the **whole** estate, not the audit's subset:

```
work items: 22    grants: 132    transitions: 269
humanActRef present & non-empty: 132/132   empty: 0   missing: 0
```

**The count is stronger than the audit's 20/20.** What it proves is weaker than claimed.

`humanActRef` is a **free-text string** (measured: 83 prose, 49 containing a document path). It is
**not a typed reference to an authority-act object**; it has no schema, no identity, and no
verification. It establishes a **discipline**, not a **binding**. The estate's own record already
contains the failure mode: `ASD-001` records a hand-composed append, and
`KOS-AIP-GOV-STATE-DURABILITY-MIGRATION-PLAN-ARCHITECTURE-REVIEW.md:269` records **two distinct
authority acts sharing one `grantId`** — i.e. the estate has already lost an authority act to the
absence of act identity.

> **132/132 is evidence that the field is always filled. It is not evidence that authority is bound.**

### 6.2 The closure is circular in its strongest link

The audit's §9/§21 cite the executed witness *"10^6 evidence, no authority act → not committed"* as
proof. I read and executed `analysis/mathematical-tests/ladder_dc_reference.py`:

```python
def commit(self, purpose, authority_act=None, evidence_volume=0):
    if self.status != "Accepted": raise ...
    if authority_act is None: return False
    ...
```

**`evidence_volume` is never read in the body.** `grep` confirms it appears at exactly two lines: the
parameter declaration and the call site. The test passes `10**6` and the function does not look at it.

> **The witness would produce the identical PASS for `evidence_volume = 0`, and would also "pass" if
> the law it tests were false.** It is a null-check on a parameter named `authority_act`, restating
> A6/I-4 in Python. The script's own docstring says *"Testing tool only; nothing here is
> architecture."* **This is `IMPLEMENTATION-ONLY`, and specifically a tautology — not a proof of
> `Σ ⊥ Γ`.** One of the three "independent proofs" of `Σ ⊥ Γ` is withdrawn.

### 6.3 The ratified answer the audit did not consult

`reviews/synthesis/model/canonical-architecture-v0.2.md` — the **AUTHORIZED canonical model**
(GN-19, ruled 2026-08-28, *before* this programme) — already closes the policy-change loop:

- **R-1 stratification:** `Policy-as-content ∈ K_t` (admissible like any claim) is distinguished from
  `Policy-in-force (versioned)` (the governor of admission).
- **I-11 (new invariant):** *"No in-force policy — AcceptancePolicy and constitution included —
  changes without a governed, versioned approval decision."*
- The model states plainly: *"Self-modification of the in-force policy without governed approval is
  excluded by construction."*

**The audit declared this the only genuine loop in the theory and then closed it by externalisation
to a human act — a second, unreconciled resolution of a question the ratified model had already
answered by stratification.** Two mechanisms now stand for one problem (`ES-005.4`: consume or
extend, never a second).

**Verdict: CIRCULAR** on its executed evidence, **SUPERSEDED** on its governance evidence.
The *underlying question* is closer to closed than the audit realised — by someone else's ruling.

---

## 7. Internal inconsistency inside the closing programme

`COMPUTABILITY-MATRIX.md` (19:02) records **14 of 16 computable, 2 blocked**, and names the two:
**`assessment`** (no corpus passage specifies how a policy maps an evidence set to a support level)
and **`status derivation`** (`str` needs an order-preserving rule that does not exist).

`THEORY-CLOSURE-AUDIT.md` (20:10, 68 minutes later) records **"30/30 symbols resolve"**, and the two
it declares resolved are **a different pair** (qualification predicate, authority→gate binding).

> **The matrix's two blocked symbols were never addressed and are still blocked.** The denominators
> (16 vs 30) are never reconciled, and no artifact records the transition. **"30/30" is not
> sustainable on the programme's own record.**

---

## 8. What survived, stated exactly

| Survives | Withdrawn |
|---|---|
| **G4** provenance four-object split — verified, all four anchors independently confirmed | **G1** — signature ≠ definition; three counterexamples stand |
| **G3** the three-way authority separation, as far as it goes | **G2 as `CORPUS ESTABLISHES`** — non-incorporated source; interface absent; corroboration counterexample |
| The `Σ ⊥ Γ` *derivation* and the `authorities.yaml` declaration | **G5** — refuted by the corpus's own step 263; `Ω` unregistered |
| `humanActRef` **discipline**, now measured at 132/132 | **G6 as closed-by-this-audit** — circular witness, superseded by I-11 |
| `Lineage = Π ∘ ℛ_der*`, implemented, 47 tests | **`Σ ⊥ Γ` proof #3** (the executed witness) — tautological |
| | **"30/30 symbols resolve"** — irreconcilable with the same programme's 14/16 |
| | **"Every canonical term now has exactly one meaning"** — false by measurement |

> ## VERDICT: THE CLOSURE IS NOT SUSTAINED.
> **One of six gaps (G4) is VERIFIED. One (G3) is PARTIALLY VERIFIED. Two (G1, G5) are REFUTED.
> One (G2) is downgraded to a non-incorporated source claim. One (G6) is CIRCULAR on its own
> evidence and SUPERSEDED by a ruling made before the audit began.**
>
> The audit's own closing caution was correct and is now discharged with a result:
> **finding a closure and declaring it closed in the same pass produced four unsound closures.**
