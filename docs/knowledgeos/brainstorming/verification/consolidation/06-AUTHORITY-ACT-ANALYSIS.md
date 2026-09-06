---
artifact: 06-AUTHORITY-ACT-ANALYSIS
date: 2026-08-30
verdict: **CASE B — implied but incompletely typed. REFINEMENT, not innovation. This CORRECTS my own prior finding.**
---

# 06 · AuthorityAct Necessity Analysis

## 1. The seven concepts, separated

| Concept | Where it lives | Typed? |
|---|---|---|
| **competence** | `Auth(a,r,c,p)` (230.14) · `Authority=f(Actor,Role,Action,Scope,Policy,Time,Context)` (179) | 🟡 two rival arities, **no body** |
| **authorization** | `Authorize(N,a,Policy)→c` (Q17 §6.2) | 🟡 signature only; codomain wrong (3 of 4 outcomes are not commands) |
| **authority (standing)** | `authorities.yaml` 5 ranks | ✅ but of an **artifact**, not an actor |
| **governance state** | `Γ` | ✅ |
| **human act** | — | 🔴 **untyped** |
| **reference to a human act** | **`humanActRef`** | 🟡 **free-text string** |
| **representation of a human act** | **`Grant`** | ✅ **TYPED — see §2** |

## 2. Case A test — is it present under another name? **PARTLY, and this is the correction**

`CORPUS` — `architecture/…Epistemic-Architecture-Investigation.md:265`:
> **`Grant = recorded reference to human act`**

`IMPLEMENTATION` — measured, the real record:
```
GRANT FIELDS: ['grantId', 'status', 'authority', 'humanActRef', 'registeredBy', 'scope']
  grantId     = G-KOS-INC1          status      = AUTHORIZED
  authority   = PO                  registeredBy= governance
  humanActRef = "platform-implementation-commission §15 (7cbe5984) + D-2 boundary…"
  scope       = "Increment 1 - authoritative workflow state record (boundary §12)"
```

> ### **The `Grant` IS a typed authority object with identity. 132/132 across 22 work items.**
>
> ## ⚠️ CORRECTION TO MY OWN PRIOR PASS
> `canonical-construction/21` stated: *"`INNOVATION` is required in exactly one place: a typed
> `AuthorityAct` (0 corpus occurrences)."* **That was wrong.** I searched for the *string*
> `AuthorityAct` and concluded the *concept* was absent. **The concept exists, is named `Grant`, is
> corpus-defined and is implemented.** The error was lexical, and it is the same class of error that
> made `Missingness` look absent (`04` §1). **Recorded, not quietly dropped.**

## 3. What is actually missing

**Not the authority object — the act it points at.**

```
Grant           ✅ typed, has grantId, 132/132 instances
  └── humanActRef  🔴 a free-text STRING: no schema, no identity, no verification
        └── the human act itself   🔴 no type anywhere
```

**Consequence, on the estate's own record** `IMPLEMENTATION`:
`KOS-AIP-GOV-STATE-DURABILITY-MIGRATION-PLAN-ARCHITECTURE-REVIEW.md:269` —
> *"Two distinct authority acts — different `humanActRef`, different `scope`, both `AUTHORIZED` —
> **share one identifier** … any verification or dedup keyed on `grantId` would treat them as one,
> which is **a silent loss of an authority act**."*

**The failure has already occurred in production.** This is not a hypothetical requirement.

## 4. Verdict: **CASE B — implied but incompletely typed ⇒ REFINEMENT**

**Not Case A** (it is not fully present — the act has no identity).
**Not Case C** (it is not absent — `Grant` is corpus-defined and running).

## 5. What the mandate requires for a Case-C item — supplied anyway, since the decision is real

| Question | Answer |
|---|---|
| **Why necessary** | two distinct acts already collided on one `grantId`; revocation-after-authorization and policy-version drift between authorization and execution are both undetectable |
| **Which invariant requires it** | `Evidence ≠ Authority` (45×) · `Decision ≠ Authorization` (29×) · *"the mechanism records authority; it does not grant authority"* — the last requires the **referent** to be identifiable, or "records" is unverifiable |
| **Which regress it terminates** | none — **the regress is already terminated** by externalisation (`v0.2 R-1`/`I-11` + 132/132). This adds **no** new termination; it makes the existing one **auditable** |
| **Minimum properties** | an identity distinct from `grantId`; and it must remain a **reference**, never a constructor |
| **Alternatives** | **(a)** status quo — free text; **(b)** `actId` + the existing free text retained; **(c)** a fully structured act record; **(d)** make `grantId` globally unique and stop there — *fixes the observed collision only* |
| **What must be decided** | **whether to type the referent, and how far** — `08` §D-2 |

## 6. The boundary, preserved

> **Software records authority; software does not manufacture the authority from which its
> governance derives.**

**Every option above preserves it.** Giving the act an identity is a *recording* refinement — it does
not let the system originate authority. **Option (c) is where the risk sits**: a fully structured act
record starts to look like a constructor, and the corpus's own strongest rule
(*"Assessment → verdict → NEVER grants authority"*) is the constraint that must be re-tested against
whatever is chosen.
