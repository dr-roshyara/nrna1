# `H-2` — `w8` regression lock: PO/ARB authorization, recorded verbatim

**Recorded by:** the governance recording session · 2026-08-19 · **Nothing implemented at recording time.**

## The act, verbatim

> **PO/ARB AUTHORIZATION — H-2**
>
> I authorize one bounded slice to create the regression lock for the D2-approved w8 semantics.
>
> The slice shall encode the already-decided meaning that Restoration and Resumption are distinct: the w8 path represents restoration without a prior halt/resumption target, while retaining its known causal origin.
>
> This authorization is limited to the RED test, the minimum implementation/test change required to establish the regression lock, and independent verification of that lock.
>
> This authorization does not authorize: Act C; Act D; BND-1; BND-3; GREEN-5; changes to RecordedOperationalStatus; persistence or adapters; Application-layer changes; HaltedAtGate redesign; ResumptionTarget redesign; a sentinel, UnknownGate, nullable replacement, new enum, new aggregate, or new event; reopening ADR-2; changing the meaning already decided by D2.
>
> The lane must derive the RED test from the existing D2 decision and frozen domain behaviour. It must not invent a new semantic rule.
>
> RED must be committed separately and demonstrated failing before GREEN.
>
> The implementation/test change must then make only that regression lock GREEN.
>
> An independent verifier must verify the resulting lock.
>
> After verification, the lane must stop.
>
> **PO/ARB: AUTHORIZED.**

✅ **`A-3` satisfied:** performative, own voice, unconditioned, scope-exact, explicit negative list.

---

## 🔴 A structural issue in the RED requirement — flagged, not silently resolved

**The `w8` behaviour ALREADY PASSES on the frozen domain.** The Act-B implementing lane reported exactly this: *"`H-2` … passes on arrival, is explicitly not the RED."* Independently confirmed: `ElectionOperationalStatus.php:22-24,39-42,49-52` already yields `haltedAtGate() === null` and `isHalted() === false` for the halt-absent path.

**So a conventional fail-by-absence RED is impossible here**, and the three obvious ways to manufacture one are each **forbidden by this authorization**:

| Route to a failing test | Why it is forbidden |
|---|---|
| Assert something the domain does **not** currently express | ⛔ *"must not invent a new semantic rule"* — this would be a **new requirement**, not a regression lock |
| **Mutate the frozen core** to break the behaviour, commit that | ⛔ Act-B baseline integrity; the core carries exactly one `A` line vs `1f4b4c5f` and no `M` |
| Skip fail-first and commit a passing test | ⛔ *"RED must be committed separately and demonstrated failing before GREEN"* |

> ## ⚠️ **The danger this creates is specific and must be named: a lane under pressure to produce a RED will INVENT a semantic rule to make something fail.** **That is the one outcome this authorization explicitly forbids, and it is the most likely failure mode of this slice.**

### The sanctioned route — mutation-demonstration in a throwaway export

**Precedent, in this repository, from the Act-B step-④ verifier:** it exported the tree at `e7317077` **with that tree's own `vendor/`** and ran the suite inside the export, proving a failure state **without touching the working tree.**

⇒ **`H-2`'s fail-first is demonstrated the same way:** export the current tree to a scratch location · **mutate the copy** so the `w8` discrimination is broken · run the new lock **inside the export** and capture the failure · **discard the export.** **The committed lock then passes on the unmutated tree** — which is what a regression lock is *for*.

**This satisfies the authorization on its own terms:** the RED is **committed separately** *(test alone, before any other change)* and **demonstrated failing** *(against the mutation, with captured output)*, and **no new semantic rule is invented** — the lock asserts only what `D2` already decided and the frozen domain already does.

⚠️ **Recorded as Governance's reading, offered for correction.** **If the PO/ARB intends a different route — including accepting a lock that passes on arrival with no failure demonstration — that is the PO's call and this note should be overruled.**

### What "minimum implementation/test change" is expected to mean here

**Probably test-only.** The behaviour exists; nothing needs implementing. ⛔ **If the lane concludes that production code must change to establish the lock, that is a FINDING to report and stop on — not a licence to change the core**, because it would mean the behaviour was not in fact already correct.

**Traceability:** `D2` *(decision surface, verbatim)* · ADR-2 §6(a)/(b)/(h) · Act-B step-④ verification `67a09e11` *(the export technique)* · Act-B baseline: core = `1f4b4c5f` + one `A` · `EM-GOV-059(b)/(c)` · P-7 · `EP-02`/`R-34`.
