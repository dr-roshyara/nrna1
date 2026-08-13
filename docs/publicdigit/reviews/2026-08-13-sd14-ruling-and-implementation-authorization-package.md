# `SD-14` ruling — record, and implementation-authorization package (PREPARED, NOT GRANTED)

**Type:** ARB/PO ruling record + implementation authorization · **Date:** 2026-08-13 · **Programme:** IERVP (Session 2)
**Status:** ✅ **§3 AUTHORIZATION GRANTED by the Product Owner, 2026-08-13** — recorded verbatim, with the domain-invariant correction and the design-around-the-invariant constraint. **Session 3 may implement `EM-VOT-002` within the granted scope. Session 2 itself changed no production code, test, schema, migration or Constitution.** Session 1's Master Matrix not read, classified, consumed or modified.

---

> ⚠️ **POLARITY NOTE (added 2026-08-13, governance watch):** the identifier `SD-14` was posed with **opposite polarity** in the two streams — Session 1 posed it as *"MAY voting open with zero candidates?"* (adopted answer: **NO**), while the ruling below answers *"does 'the next phase' include voting?"* (**YES**). **The substance is identical and unambiguous. Never cite the bare polarity — always cite `EM-VOT-002`'s text:** *"an election must have at least one approved candidate before voting may be opened."* *(Session 1 independently recorded the same warning.)*

## 1 · The ruling — recorded verbatim

> **ARB/PO Decision — `SD-14` = YES** *(2026-08-13)*
>
> The phrase **"the next phase"** in the adopted business rule `EM-VOT-001` includes the **voting phase**.
>
> Therefore: **an election must have at least one approved candidate before voting may be opened.**
>
> This closes `SD-14`.
>
> `ElectionConstitution` is the authoritative implementation home for this constitutional workflow precondition.
>
> **Do not implement yet.** Record the decision and prepare the implementation authorization separately for Session 3.
>
> Session 3 must use strict TDD when implementation is authorized.
>
> No other business rules are inferred or changed.

**Vocabulary point, honoured:** the ruling says ***"at least one approved candidate"*** — deliberately matching the Constitution's existing concept `has_approved_candidates`, so business rule and implementation vocabulary stay aligned.

## 2 · What the ruling changed — and did not

| Item | State |
|---|---|
| **`EM-OPEN-020` / `SD-14`** | ✅ **CLOSED** |
| **`EM-VOT-002`** | ✅ **ADOPTED** — *"an election must have at least one approved candidate before voting may be opened"* — recorded in the Manifesto §4a with traceability |
| The failing-test expectation Session 1 found (*no candidates → cannot open voting*) | **now agrees with an adopted business rule** — its disposition is Session 1's, on Session 1's authority |
| **Implementation** | ⛔ **STILL NOT AUTHORISED** — the ruling itself says so |
| Any other business rule | **unchanged — none inferred** |

**The chain, now three-quarters complete:**

```
EM-VOT-001 (adopted 2026-08-08)
      ↓
SD-14 = YES (ruled 2026-08-13)          ✅ done
      ↓
EM-VOT-002 adopted · Constitution = HOME ✅ done
      ↓
IMPLEMENTATION AUTHORIZATION             ⬜ ← §3, awaiting the PO's grant
      ↓
Session 3: strict TDD                    ⬜
      ↓
Session 1: independent verification      ⬜
```

---

## 3 · Implementation-authorization package — **PREPARED FOR THE PRODUCT OWNER'S GRANT**

> ### ☑ **AUTHORIZATION GRANTED — Product Owner, 2026-08-13. Recorded verbatim:**
>
> **Implementation of EM-VOT-002 is authorized for Session 3.**
>
> Scope: **Election-Only mode only.**
>
> Business rule: An election must have at least one approved candidate before voting may be opened.
>
> `ElectionConstitution` remains the constitutional implementation home for the `open_voting` precondition.
>
> Session 3 must use **strict TDD**: establish RED evidence first, then implement the minimum change necessary to make the invariant GREEN.
>
> The implementation must enforce EM-VOT-002 on **every path that can result in `voting_active`**, including the computed lifecycle path identified by `PBDIGIT-64`.
>
> **Do not assume that adding `has_approved_candidates` to `open_voting` is sufficient.**
>
> Do not invent or modify any other business rule. Do not implement Full Membership behaviour. Do not modify ElectionMembership entitlement, credential, suspension, or Organisation Membership rules. Do not create another Constitution or rules registry.
>
> Tests must reference **EM-VOT-002**, not review documents.
>
> After implementation, **Session 1 independently verifies the result. Session 3 does not self-certify** the architectural correctness.

> ### The Product Owner's conceptual correction — binding on the implementation
>
> *"The Constitution is the authoritative implementation home"* is to be read as: **the authoritative architectural home for EXPRESSING the constitutional `open_voting` precondition** — **not** as a claim that the Constitution is the only place the rule is enforced.
>
> **The real requirement is a DOMAIN INVARIANT, not a command authorization:**
>
> > **`EM-VOT-002` must hold at the business boundary where an election becomes `voting_active`, regardless of which technical path produces that state.**
>
> **And the final DDD constraint, verbatim:** *"Do not design around the current test failure. Design around the adopted domain invariant."* The failing test is **evidence** that the invariant is unenforced — **it is not the specification.**
>
> **Implementation flow:** `EM-VOT-002` → RED test → **discover the actual `voting_active` transition paths** → identify the correct domain/application enforcement point → minimal implementation → GREEN → regression → **Session 1 independent verification.**

**What the package contains, so the grant is informed:**

### 3.1 The obvious edit, and why it is insufficient alone

`ElectionConstitution.open_voting.preconditions` currently reads `['voting_window_defined', 'timezone_set']` — adding `has_approved_candidates` is the natural encoding, **and the precondition concept already exists** (used by `complete_nomination`, evaluated as approved-candidacies-exist by the transition guard).

> ⚠️ **But `PBDIGIT-64` established that the guarded transition is BYPASSED on the live path:** lifecycle state is **computed** — `ElectionLifecycleEngineImpl` derives `VotingActive` from the clock — so an election **entered voting candidate-less without `open_voting` ever running.** **Encoding the precondition only on the guarded action would make the rule hold on the path that was never the problem.**
>
> **The authorized slice must therefore cover the computed path too. HOW is Session 3's design question under strict TDD — this package deliberately prescribes no mechanism.**

### 3.2 Boundaries of the slice, if granted

* **In scope:** `EM-VOT-002` enforcement on all paths into `voting_active`; the RED tests that express the business outcome *(an election with zero approved candidates cannot open, and is not computed into, voting)*; GREEN with the smallest change; regression evidence.
* **Out of scope — explicitly:** every other open item (`BR-1.12`, `BR-1.13`, `Q3`, `Q-E1`, `Q-E2`, `BR-1.1`/`1.2`, `BR-1.8`, `EM-OPEN-019`) · Full Membership (`EM-FM-*`, frozen) · any second registry · any entitlement/membership/suspension/credential rule into the Constitution · repair of unrelated `PBDIGIT-64` symptoms beyond this rule.
* **Standing constraints:** strict TDD (RED evidence before implementation) · the mode-boundary hard stop · `ADR-T11` · **tests cite `EM-VOT-002`**, not review documents.
* **Session 1** verifies independently afterward; Session 3 does not self-accept.

### 3.3 Why this package exists as a separate act

The ARB acceptance was explicit: *"accept in principle" settled the HOME; the gate was `SD-14`; **implementation needs its own authorisation even after the gate opens.** The gate has now opened. **This is the authorization act, prepared — the Product Owner signs it or does not.**

---

## 4 · Board after the ruling

| Decision | State |
|---|---|
| ~~`SD-14` / `EM-OPEN-020`~~ | ✅ **CLOSED — YES** |
| **`EM-VOT-002` implementation grant** | ⬜ **§3 — awaiting the Product Owner** |
| `BR-1.12` / `EM-OPEN-001` — admission state | 🟡 open — gates the admission slice |
| `EM-OPEN-019` — threshold 30 vs 40 | 🟡 open |
| `EM-OPEN-017` — artifact status + name | ⏸ deliberately unratified |
| Full Membership | ⛔ deferred |

**Traceability:** ruling verbatim (§1) · `docs/publicdigit/business_rules/ELECTION_MANIFESTO.md` §4a (`EM-VOT-002`), §9 (`EM-OPEN-020` closed), §8 (traceability row) · `app/Domain/Election/Constitution/ElectionConstitution.php:94-100` (`open_voting`), `:76-82` (`complete_nomination`) · `docs/publicdigit/backlog/PBDIGIT-64-election-opens-voting-without-any-candidate.md` (the computed-path bypass) · `docs/publicdigit/reviews/2026-08-12-election-constitution-authority-investigation.md` §5a/§5a.1 (the home-vs-authorization distinction this package honours).
