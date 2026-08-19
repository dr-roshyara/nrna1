# C-10 — Decision 4 (missing / disputed knowledge behaviour) · **GOVERNANCE INPUT, NOT A DECISION**

**Work item:** KOS-AIP04-DISCOVERY-001 · **Prepared by:** Governance (`b64828fe`) · 2026-08-19
**Requested:** the PO/ARB supplied a six-option analysis recommending **D (Tiered)** and asked Governance's preference.
**Evidence base:** D1 · D2 · D3 and its registration · canonical analysis §5.1, §5.3 pt 12/13/23, A1.3. ⛔ No second C-10 model.

---

## 1 · ⛔ Two of D4's own constraints are crossed by any enforcing option

**D4 states: "Do not decide — Category."** The PO/ARB's own table puts Category *"After D4."*

But the canonical analysis has already fixed what an enforcing answer does:

> **§5.3 pt 13:** *"advisory today. **If it halts at START it becomes control-plane** — which is why it is adjacent to `C-14`."*
> **§5.3 pt 23:** *"control-plane test: 🟡 **conditional** — advisory today, **control-plane if it gates START**."*

⇒ **Selecting HALT or BLOCK for any tier decides the category by consequence.** B, C, and D-with-Tier-1-HALT all convert C-10 into a control-plane capability — the exact back-door structure **D3's non-constitutive clause** was created to prevent.

**Second constraint:** C-14 (policy enforcement) existence is **`CONTESTED`**. Giving C-10 gate authority while the estate's *enforcement* capability is contested resolves an adjacency the record deliberately left open.

## 2 · ⭐ Three of the five conditions have no detectable state and no owner

**D4 assigns behaviour to five conditions. The platform can currently establish only one of them.**

| Condition | Can the platform detect it? | Evidence |
|---|---|---|
| **Missing** | 🟡 **in principle yes** — absence of a receipt | but see §3 |
| **Stale** | 🔴 **no** — requires monitoring supersession against in-flight sessions | §5.1: **monitoring/invalidation owner = nobody** |
| **Superseded** | 🔴 **no** — same trigger | §5.1 |
| **Invalidated** | 🔴 **no** — **invalidation authority explicitly NOT decided by D3**; owner = nobody | D3.5, D3.7 obs. 1 |
| **Disputed** | 🔴 **no** — **dispute adjudication is OUTSIDE the C-10 boundary** (D3.2) | D3.2 |

> ⛔ **A tier table cannot assign a response to a condition whose state nothing owns.** Four of five rows in the recommended matrix specify behaviour for triggers that **do not exist and are not owned** — and for `Disputed`, D3 placed adjudication outside C-10 *deliberately*, to avoid prejudging bounded-context status.

## 3 · ⭐⭐ The decisive practical fact — "Missing" is currently 100 % of cases

**D2: C-10 is `NOT YET ESTABLISHED`. No receipt activity is realized. Therefore ZERO receipts exist.**

> ## **Every session, for every required context version, is in state `Missing`.**

⇒ **Any blocking response to `Missing` halts the entire estate on the day it is enabled.** Tier-1 HALT on `Missing` is not strict — it is **total**. This is not an argument against tiering as a *target*; it is an argument that **D4 cannot be enabled at the moment it is decided.**

**And a fourth problem the gate itself cannot escape:** a receipt check must bind *this* receipt to *this* session. **`INV-ATTR-1`: no gate reads identity.** **D3.2 puts identity attestation/binding OUTSIDE C-10.** ⇒ **even the `Missing` check cannot verify that a presented receipt belongs to the session presenting it.**

## 4 · Assessment of the six options against the record

| | Governance assessment |
|---|---|
| **A — WARN** | ⚠️ weak, **but it is the only option consistent with pt 13 keeping C-10 advisory** and therefore category-neutral |
| **B — BLOCK** | ⛔ decides category (§1) · unimplementable on `Missing` (§3) |
| **C — HALT** | ⛔ same, more so; and it would place C-10 alongside `CAP-09` which is **"deliberately closed — no extension point exists"** |
| **D — Tiered** | ✅ **right target shape**, ⛔ **but as posed it decides category and specifies four ownerless triggers.** Also **the tiers do not exist**: the estate tiers *policies/gates* (`AST-005/006/007/014`, `CAP-09` Tier-1), not *knowledge packages* |
| **E — Session Responsible** | ⛔ **empirically falsified inside this very work item** — see §5 |
| **F — Compensating Control** | ⭐ **underrated: it describes what the estate already does deliberately** — see §6 |

### 4.1 · ⚠️ The `CAP-09` precedent is weaker than the recommendation states

The recommendation cites *"CAP-09 already implements halt, escalate, never retry for Tier 1"* as precedent. **Per the primary sources:** `CAP-09` is **read-only observation of constitutional guards (`CI-1..5`/`Q7`)** (`Phase-02.5-Certification-Plan.md:46`) and is **"deliberately closed — no extension point exists"** (`Phase-03A-Reference-Architecture.md:73`).

⇒ It is precedent that **a** Tier-1 halt pattern exists — **not** that it can carry C-10. And whether `CAP-09`'s pattern is even the same capability as C-14 is **`OPEN`** (A1.4). *(This is also the citation `F-4` had to split across two documents.)*

## 5 · Why E is not merely "too weak" — it is the falsified status quo

**E is the estate's CURRENT behaviour: evidence recorded, nothing enforced.** The record of this single work item is the counter-evidence:

- **`EKS-01`** — *"Recording a rule is not sufficient"*; a lane was **taught the old path by its workflow record's `tokenRef`**;
- **`INV-ATTR-2` exists precisely because the estate cannot enforce it generally** (A1.6);
- **today**: work performed off-record before its lane's START (`F-7`), a **false identity claim** (`ee77c6c2`) and its erratum, and a barred process authoring **both** the findings and their correction.

⇒ **E is not an untested option. It is the option currently in force, and it produced the defects this work item is adjudicating.**

## 6 · ⭐ Why F deserves more weight than "useful as fallback"

**F is the only option that survives §3.** While zero receipts exist, a compensating control lets governed work proceed **with an explicitly recorded exception** — instead of halting everything (B/C/D-Tier1) or proceeding silently (A/E).

**And the estate already implements it:** the **recorded human START act** *is* a compensating control — a human authorization substituting for absent machine enforcement. **seq 25 and seq 27 are literally that.** F names a mechanism already in production rather than inventing one.

## 7 · Governance's preference

> ### **Preferred: `D` as the TARGET model, decided NON-CONSTITUTIVELY, with `F` as the transition mechanism and `A`/record as the floor for ownerless conditions.**

**Concretely, the form that does not cross D4's own constraints:**

1. **Adopt D as the candidate behaviour model** — using D3's proven wording: *"this specifies the candidate behaviour model; it does not enable enforcement, does not decide category, and D2 remains `NOT YET ESTABLISHED`."*
2. **Split the five conditions by detectability.** Decide behaviour **only** for `Missing`. Record `Stale · Superseded · Invalidated · Disputed` as **`OPEN` pending an owner for the invalidation trigger and for dispute state** — D3 left both open by design.
3. **Floor until receipts exist:** `WARN` + record (never BLOCK/HALT) — because `Missing` is 100 % of cases (§3) and the gate cannot bind receipt→session (§3).
4. **`F` as the named exception path** — the recorded human authorization already in use, so an exception is *documented*, not silent.
5. **Defer the tier definitions** — knowledge-package tiering does not exist and is Architecture design work.

⚠️ **What I would not do: enable any enforcing behaviour in the same act that defines it.** That is the one move that both decides the category and, on §3's arithmetic, stops the estate.

## 8 · Not decided here

⛔ C-10 existence (D2) · authoritative state (D3) · **category** · ownership / stewardship · `OQ-K` · `OQ-B` · implementation technology · build order · tier definitions · invalidation authority · dispute semantics · **and the verdict itself — Governance recommends; it does not decide.**

**Traceability:** PO/ARB D4 options analysis 2026-08-19 (recommending D) · **D1 · D2 · D3** and the D3 registration (D3.2, D3.5, D3.7) · canonical analysis §5.1, §5.3 pt 12/13/23, A1.3, A1.4, A1.6 · `INV-ATTR-1`/`INV-ATTR-2` · `EKS-01` · `CAP-09` (`Phase-02.5-Certification-Plan.md:46`, `Phase-03A-Reference-Architecture.md:73`) · `F-7` · `ee77c6c2` / `1f86623e` · seq 25 / seq 27
