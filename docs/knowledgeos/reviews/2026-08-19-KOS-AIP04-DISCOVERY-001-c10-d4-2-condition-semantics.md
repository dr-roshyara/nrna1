# C-10 — D4.2 · Condition semantics · **PROPOSED definitions for PO/ARB decision**

**Work item:** KOS-AIP04-DISCOVERY-001 · **Prepared by:** Governance (`b64828fe`) · 2026-08-19
**Standing:** D1 · D2 (`NOT YET ESTABLISHED`) · D3 (Option C / SPLIT) · D4.1 (proportionate enforcement) · D4 (floor = `WARN` + `RECORD`)
⛔ **No condition is mapped to `INFO`/`WARN`/`BLOCK`/`HALT`.** ⛔ No tiers · no category · no ownership · no implementation.

> ## ⚠️ Producer disclosure — read before relying on these definitions
> **These definitions are authored by GOVERNANCE, which governs this work item's record.** Defining condition semantics is **tactical modelling**, and it will feed D4.3's mapping. They are therefore **`PROPOSED` decision-input, not an authoritative semantic model.**
> **If the PO/ARB wants an authoritative model, commission it from Architecture.** **`R-34`/`P-2` binds: I must not later verify or accept these definitions.**

---

## 0 · ⭐ Two structural findings that cut across all five

**(1) A receipt's D1 claim is a time-stamped historical fact, so it never becomes FALSE — only INSUFFICIENT or UNRELIED-UPON.**
D1 fixes the claim as *"package X was delivered to and acknowledged by session S at time T."* Nothing later makes that false. ⇒ **`STALE`, `SUPERSEDED` and `INVALIDATED` do not contradict the receipt; they change what it is sufficient FOR.** Any wording that says a receipt "became invalid/false" is a category error.

**(2) `DISPUTED` is ORTHOGONAL to the lifecycle, not a state in it.**
D3.5's candidate lifecycle is a chain: `ISSUED → VALID → SUPERSEDED → INVALIDATED → EXPIRED / DISPUTED`. But a receipt can be **`SUPERSEDED` *and* `DISPUTED`** simultaneously, or its very existence disputed while it is `MISSING`. ⇒ **`DISPUTED` is a FLAG over the lifecycle state, not a terminal state within it.** ⚠️ **Recorded as a defect in D3.5's candidate chain** — which D3 expressly did not finalize. **Not corrected here; surfaced for decision.**

---

## 1 · `MISSING` — five sub-conditions, and they are NOT equivalent

| | Sub-condition | Triggering fact | Authoritative evidence | Ownership today | OPEN |
|---|---|---|---|---|---|
| **M1** | **never issued** | no receipt exists for (package version, execution) at the acknowledgement point | absence of a receipt record | 🔴 nobody | ⚠️ **absence of a record in an UNREALIZED store is not evidence of absence** — cf. the engine's `C-1` (*absence never implies ownership*) |
| **M2** | **expected but absent** | a requirement named a package **and** no receipt exists | the requirement + the absence | 🔴 nobody — **and "expected" requires an APPLICABILITY determination, which D3.2 places OUTSIDE C-10** | ⛔ **not determinable by C-10 alone** |
| **M3** | **exists, not retrievable** | retrieval failure (store unavailable, corruption) | the retrieval error | infrastructure, not C-10 | ⚠️ **must NEVER be reported as M1** — that laundders an infrastructure failure into a governance fact (D4.1.4 prohibition) |
| **M4** | **exists, cannot be bound to the execution** | binding attempt against a present receipt | ⛔ **none possible** — `INV-ATTR-1` (no gate reads identity); D3.2 (identity binding **outside** C-10) | 🔴 nobody, **and unownable today** | ⭐ **see below** |
| **M5** | **knowledge itself absent** | the referenced package was never published | BC-1 publication record (`CAP-03` **not built**) | **BC-1 — not C-10** | *receipt missing ≠ knowledge missing* |

> ### ⭐ **M4 must be classified `NOT ATTESTED` — never `MISSING`.**
> Calling it `MISSING` asserts *"no receipt"*, which the evidence does not support. Calling it present asserts a **binding the platform cannot make**. **`NOT ATTESTED` is the only honest value** — and per the standing rule it must never be treated as `PASS`. *(This is D4's §2 "identity binding" prerequisite, at condition level.)*

**Net:** of five sub-conditions, **one is a C-10 condition (M1)** · **one is infrastructure (M3)** · **one needs an unowned upstream determination (M2)** · **one is `NOT ATTESTED` by construction (M4)** · **one is BC-1's (M5)**.

## 2 · `STALE`

**PROPOSED definition:** *a receipt is `STALE` when it remains structurally sound for the version it names, but that version is no longer the one a governed act requires at the time of the act.*

| | |
|---|---|
| **Triggering fact** | a governed act requires a version other than the one the receipt names |
| **Authoritative evidence** | the act's version requirement + the receipt's named version |
| **Basis** | ⭐ **version-based is the only basis C-10 can establish** (D3 gives it version/supersession invalidation). **Time-based** needs expiry semantics — **D3.5 left expiry undecided**. **Policy-based** needs an applicability determination — **outside C-10** |
| **Who establishes currentness** | **BC-1** (knowledge governance / publication). ⛔ **Not C-10** |
| **Does a newer version automatically make the old receipt stale?** | ⛔ **NO.** That requires knowing the act *requires* the newer version — an **applicability judgement, outside C-10.** ⚠️ **Automatic staleness would mean every publication stales every receipt globally** |
| **OPEN** | expiry semantics · whether staleness is per-act or global · who asserts an act's version requirement |

**Distinction preserved:** *receipt stale ≠ knowledge superseded* — **staleness is RELATIONAL** (receipt ↔ this act), **supersession is an upstream FACT** (§3).

## 3 · `SUPERSEDED`

**PROPOSED definition:** *the specific knowledge version the receipt names has been replaced by a later version through a governed publication act.*

| | |
|---|---|
| **Authoritative supersession event** | ⭐ **BC-1's governed publication / supersession act** (`CAP-03` — **not built**). The trigger has an owner in principle; the mechanism does not exist |
| **Authoritative evidence** | the BC-1 publication record naming predecessor and successor versions |
| **Ownership** | **declaring** supersession = **BC-1**. **Reacting** to it (marking the receipt `SUPERSEDED`) = **C-10's**, per D3's *version/supersession invalidation* |
| **OPEN** | whether supersession is transitive across chains · retraction-without-replacement |

> ### ⭐ The three-way cut the act asked for
> | | Fact about | Character | Whose act |
> |---|---|---|---|
> | **`SUPERSEDED`** | the **knowledge version** | objective, **act-independent** | **BC-1** |
> | **`STALE`** | the **receipt's sufficiency for one act** | **relational**, needs applicability | 🔴 unowned |
> | **`INVALIDATED`** | the **receipt itself** | an **authority act** | 🔴 **OPEN (D3)** |

## 4 · `INVALIDATED`

**PROPOSED definition:** *an authority has determined that the receipt must no longer be relied upon — independent of the truth of its original claim.*

| | |
|---|---|
| **Who may invalidate** | 🔴 **OPEN. D3 expressly left invalidation authority undecided; §5.1 records owner = nobody.** ⛔ **No owner invented here** |
| **Triggering event** | an invalidation act by that undetermined authority. *Candidate triggers, recorded not decided:* receipt issued in error · the issuing process later found barred or defective · the acknowledged package was mis-assembled |
| **Authoritative evidence** | the invalidation act **plus its authority reference** — mirroring the estate's grant rule that authority is registered **by reference**, never manufactured |
| **Reversible?** | ⚠️ **the question is mis-framed.** Per §0(1) the original claim was never false, so invalidation cannot be "reversed" — the real question is **whether RELIANCE may be restored**, which is a different decision. **OPEN** |
| **Applies to receipt / version / both?** | ⭐ **the RECEIPT only.** Invalidating a *version* is **BC-1's** act (retraction / supersession). Conflating them would give C-10 authority over knowledge content, which D3.6 forbids |

> ### ⭐ A real instance already exists in this work item
> **`ca6039a8` was reclassified as *"HISTORICAL EVIDENCE + NOT INDEPENDENT / PROVENANCE-CONFLICTED"* — not deleted, findings intact, only its ASSURANCE STATUS refused.**
> **That is exactly the shape of receipt invalidation**, and it demonstrates a property worth deciding explicitly: **invalidation is PURPOSE-SCOPED, not global.** The artifact still serves as evidence; it no longer serves as assurance. *(`OBSERVED`, one instance — evidence, not a standard: `ES-006.1`.)*

## 5 · `DISPUTED`

**PROPOSED definition:** *a party asserts that one of the receipt's D1 claims does not hold, and no authority has yet resolved the assertion.*

| | |
|---|---|
| **What proposition may be disputed** | ⭐ **only a D1 claim** — delivery, or acknowledgement by a specified session execution at a specified time. **A dispute about APPLICABILITY is NOT a receipt dispute**: applicability is reference-only and its correctness is expressly not established by the receipt |
| **Who may raise** | 🔴 **OPEN** |
| **Who adjudicates** | ⛔ **OUTSIDE C-10 (D3.2). Not decided here** |
| **Evidence creating the state** | the assertion **plus its basis**. ⚠️ **Asymmetry worth deciding: raising a dispute needs far less evidence than resolving one** ⇒ `DISPUTED` is **cheap to enter, expensive to exit**, so an unqualified right to raise is a denial-of-service surface on a receipt |
| **Resolved when** | an adjudicating authority (**unowned**) rules |
| **Usable for a limited purpose?** | *Candidate, not decided:* a disputed receipt may still evidence **that an acknowledgement was RECORDED** (a fact about the record) while **not** evidencing the disputed claim. ⛔ **And per D4.1, resolution must never convert `NOT ATTESTED` into `PROVEN`/`COMPLIANT`** |

**Distinction preserved:** *receipt invalidated ≠ receipt disputed* — **invalidation is a completed authority act; dispute is an UNRESOLVED assertion.** Opposite epistemic directions.

## 6 · Cross-condition distinctions, as required

```
MISSING ≠ STALE ≠ SUPERSEDED ≠ INVALIDATED ≠ DISPUTED
```

| Pair | Held apart by |
|---|---|
| **receipt missing ≠ knowledge missing** | **M1** (C-10) vs **M5** (BC-1) |
| **receipt stale ≠ knowledge superseded** | **relational sufficiency** vs **upstream objective fact** (§2 / §3) |
| **receipt invalidated ≠ receipt disputed** | **completed authority act** vs **unresolved assertion** (§4 / §5) |

**Identity constraint honoured:** ⛔ **possession of a receipt does NOT prove it belongs to the executing session.** *Recording identity ≠ attesting identity.* The `C-5` limitation remains in force, and it is why **M4 is `NOT ATTESTED`** (§1).

## 7 · Ownership summary — nothing assigned, nothing invented

| Condition | Trigger owner today |
|---|---|
| `MISSING` (M1) | 🔴 nobody |
| `MISSING` (M2) | 🔴 nobody + **applicability outside C-10** |
| `MISSING` (M3) | infrastructure |
| `MISSING` (M4) | 🔴 **unownable today** (`INV-ATTR-1`) |
| `MISSING` (M5) | **BC-1** |
| `STALE` | 🔴 nobody (currentness = **BC-1**) |
| `SUPERSEDED` | **BC-1** declares · C-10 reacts |
| `INVALIDATED` | 🔴 **OPEN by D3** |
| `DISPUTED` | 🔴 raiser OPEN · **adjudicator outside C-10** |

⭐ **Only ONE of the five conditions has an authoritative trigger owner today: `SUPERSEDED` (BC-1) — and its mechanism `CAP-03` is not built.**

## 8 · Not decided here

⛔ `WARN`/`BLOCK`/`HALT` mappings · risk tiers · C-10 category · ownership / stewardship · `OQ-K` enforcement response · implementation · bounded context · invalidation authority · dispute ownership · expiry semantics · D3.5's lifecycle correction.
**D2 = `NOT YET ESTABLISHED` · D3 = Option C / SPLIT · D4.1 = proportionate enforcement · D4 floor = `WARN` + `RECORD`** — all unchanged.

**Traceability:** PO/ARB act 2026-08-19 (D4.2) · **D1 · D2 · D3** (D3.2, D3.5, D3.6, D3.7) · **D4.1** (D4.1.3, D4.1.4) · **D4** (§2 prerequisites, §3, §4, §6) · canonical analysis §5.1, A1.3 (seven states), C3.3 · `INV-ATTR-1`/`INV-ATTR-2` · `CAP-01` / `CAP-03` · BC-1 / BC-6 / BC-7 · `workflow-state.php` `C-1` (*absence never implies ownership*) · `ca6039a8` reclassification (provenance disposition §B) · `ES-006.1` · `R-34`/`P-2`
