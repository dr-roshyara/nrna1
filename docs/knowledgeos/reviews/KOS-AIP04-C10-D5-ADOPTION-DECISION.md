# `KOS-AIP04-C10-D5` — **Establishment Criteria Adoption Decision**

**Work item:** `KOS-AIP04-DISCOVERY-001` · **Registered by:** Governance (`b64828fe`), on the PO/ARB act 2026-08-19
**Decision object:** `KOS-AIP04-C10-D5-ESTABLISHMENT-CRITERIA-PROPOSAL.md` @ **`10fbfb05`** (300 lines) · lane `S4c`, seq 28–30

---

## 1 · Decision

> # ✅ **ACCEPT WITH RECORDED FOLLOW-UP ITEMS** *(Option B)*
> **The D5 establishment criteria model is adopted as the governing method for determining when the C-10 capability may be considered established.**
>
> ## **`D5 STATUS: ADOPTED`** · ⛔ **`D2` UNCHANGED — `NOT YET ESTABLISHED`**

**Why B and not A:** the model is conceptually strong and nothing in it blocks adoption, but three matters must stay visible — **verification of the first real proof slice · assurance-level interpretation · the operational-maturity distinction.** **Why not C or D:** no defect warrants revision or rejection; returning it would stall the chain on items that are recordable rather than blocking.

## 2 · Adopted criteria

**`E1`…`E10` as defined in §3 of the proposal**, each with definition · purpose · evidence · classification · counterexamples.

**Minimum sufficient combination — `Option C`, assurance levels (§4.2), adopted:**

| Level | Criteria required | Warrants |
|---|---|---|
| **L1 · PROTOTYPE ESTABLISHMENT** | `E1` ∧ `E2` ∧ `E5`(creation + acknowledgement) ∧ `E7` ∧ `E8` ∧ `E9` ∧ `E3`(identified) | the semantic realized **once**, inside a discriminable boundary, reconstructably |
| **L2 · OPERATIONAL ESTABLISHMENT** | L1 ∧ `E6` ∧ `E5`(supersession **or** invalidation) ∧ `E4`(exercised) | the outcome is **owned rather than incidental**, and the lifecycle is real |
| **L3 · AUTHORITATIVE ESTABLISHMENT** | L2 ∧ `E3`(exercised) ∧ `E10` | the capability can **carry authoritative claims** and has a change history |

> ### ⭐ **`D2` flips at `L2`, not `L1`.**
> **One realized instance establishes `L1`, and `L1` is not C-10.** A single instance is still compatible with an **incidental outcome that happens to have the receipt shape**; `E6` (≥2 independent governed acts) is what shows the outcome is *owned* rather than *falling out of* another mechanism — the same test that correctly excluded `CAP-01` bootstrap delivery and BC-7 `tokenRef` leakage.
> ⭐ **`L1` is adopted as a NAMED, RECORDED milestone that does NOT flip `D2`** — which gives the estate something true to record without repeating its recurring error of treating the first artifact as arrival.

**⛔ Option A is rejected — and on evidence, not preference:** the full conjunction includes `E10`, which requires a history that cannot exist at establishment time. **Adopting it would make C-10 permanently unestablishable.**

**Evidence model adopted (`R-1`):** four orthogonal dimensions — SUBJECT · EPISTEMIC · PROVENANCE · REPRODUCIBILITY. `REPRODUCIBLE` added; ⛔ **`INDEPENDENT` NOT added** — it is `C-5`'s subject, and requiring it would make C-10 unestablishable for reasons unrelated to C-10.

## 3 · ⚠️ Assurance-model interpretation — **the one thing this decision does NOT resolve**

**The adoption act's summary restates the three levels differently from the proposal it adopts.** Since the named decision object is the **proposal**, ⭐ **the adopted model is the proposal's §4.2 — the summary is a restatement, not the object.** *(Same resolution shape as Flag O: the decided text governs over a later restatement.)*

**Recorded because the divergence is material at `L3`:**

| | Proposal §4.2 *(adopted)* | The act's summary |
|---|---|---|
| **L1** | PROTOTYPE ESTABLISHMENT | "Demonstrated receipt shape" — compatible |
| **L2** | OPERATIONAL ESTABLISHMENT | "Capability realization"; *"candidate trigger for D2 revisit"* — ✅ **agrees with `R-3`** |
| **L3** | **AUTHORITATIVE ESTABLISHMENT** = L2 ∧ **`E3`(exercised)** ∧ `E10` — *can carry authoritative claims* | 🔴 **"Operational maturity"** — operational repeatability, long-term stability, production maturity |

> ### 🔴 **The divergence: the summary's `L3` drops the CLAIM-AUTHORITY limb.**
> `E3`(**identified**) sits at `L1`; **`E3`(exercised) appears ONLY at `L3`.** If `L3` were read as maturity-only, **`E3`(exercised) would fall out of the model entirely** — and with it the level at which C-10 could carry authoritative claims. **`L3` would then mean *"it kept working"* rather than *"it may be relied upon."***
>
> ⛔ **Governance does not resolve which reading is intended.** **This is the substance of follow-up item 2**, and one line from the PO/ARB settles it.

## 4 · Trigger decision — **✅ ACCEPTED**

**All four mandated fields are present, and the fourth is the one that makes it real:**

| Field | Adopted specification |
|---|---|
| **1 · Observable event** | a receipt artifact exists in the governed record for a real governed act, satisfying `L2`, referable to that act's workflow record. ⛔ **not** *"C-10 is implemented"* — a state of intent, not an event |
| **2 · Evidence source** | ⭐ **the governed record itself** — self-evidencing; no separate monitoring store is required, **and none exists** |
| **3 · Observation path** | piggybacks an **existing mandatory read** rather than a new unowned monitor. ⚠️ **Residual stated honestly by the proposal: observer-*minimised*, not observer-free** — a fully self-firing trigger is not achievable in this estate, and D5 does not pretend otherwise |
| **4 · Authority path** | ⭐ **two-part: (a) Governance MUST register a detection — a duty, not a discretion, so it cannot die silently; (b) only the PO/ARB may decide `D2` is revisited.** ⛔ **Detection is never automatic revision.** |

**Trigger text, adopted verbatim:**

> **"When a receipt satisfying the `L2` criteria exists in the governed record for a real governed act, and its provenance is independently reconstructable from that record, Governance shall register the detection, and the PO/ARB may then reopen `D2`."**

✅ observable · evidence-backed · auditable · **actionable** — actionable precisely because the authority path names who acts.

## 5 · Recommendation dispositions

| | Status |
|---|---|
| **R-1** four-dimension evidence model; add `REPRODUCIBLE`, not `INDEPENDENT` | ✅ **ADOPTED** |
| **R-2** Option C assurance levels; reject Option A as unsatisfiable | ✅ **ADOPTED** |
| **R-3** `D2` flips at `L2`; record `L1` as a non-flipping milestone | ✅ **ADOPTED** |
| **R-4** the §5 trigger incl. the two-part authority path | ✅ **ADOPTED** |
| **R-5** dispose the `D1` two-act conflict before `E2` is finalised | ✅ **DISCHARGED** — `D1` FINAL CONSOLIDATION disposed it; **`E2` is ADOPTED** |
| **R-6** ⑦ *distinct reason to change* is structurally unsatisfiable at establishment and must never appear in any minimum set | ✅ **ADOPTED** |

## 6 · Follow-up items — recorded, **not blocking**

| # | Item | Substance |
|---|---|---|
| **1** | **Verification of the first real proof slice** | when `L1` or `L2` is claimed, **who verifies it?** The proposal binds its own producer out (*"this process must never judge whether these criteria are satisfied"*), and `INFO-2` of the E2 review noted the **same process shaped both sides of the `E2` ↔ `D5` interface.** ⇒ **the first satisfaction judgement needs an eligible, disclosed process** |
| **2** | **Assurance-level interpretation** | §3 above — **`L3` = AUTHORITATIVE (with `E3` exercised) or maturity-only?** Unresolved by this decision |
| **3** | **Operational-maturity distinction** | `E6` *(repetition across acts — demonstrable now)* vs `E10` *(operation across time — only a history shows it)*. The proposal keeps them apart deliberately; **the distinction must not collapse**, or `L2` silently absorbs `L3` |

## 7 · Explicit non-decisions

⛔ **This decision does NOT:** establish `C-10` · change `D2` (**`NOT YET ESTABLISHED`**) · create a bounded context · assign ownership · enable enforcement · define implementation · decide `D6` or `D7` · resolve `L3`'s reading.

⭐ **Adopting the criteria is not satisfying them.** **D5 says what would have to be true; it does not assert that any of it is true yet.**

## 8 · Sequence

```
D1 ✅ → E2 ✅ ADOPTED → D5 ✅ ADOPTED → D2 revisit ONLY on the §5 trigger → D6 category → D7 ownership
```

**Next actor: PO/ARB** — settle follow-up 2 (`L3`'s reading) when convenient; **`D2` revisit only when the trigger evidence exists.**

**Traceability:** PO/ARB D5 adoption act 2026-08-19 (Option B) · proposal `10fbfb05` §2.3/§3/§3.1/§4.1/§4.2/§4.3/§5/§6/§7 · `G-KOS-AIP04-C10-D5` + `AMD1`/`AMD2`/`AMD3` · lane seq 28–30 · **E2 adoption decision** · E2 governance review `INFO-2` · `D1` FINAL CONSOLIDATION (discharging `R-5`) · `D2` register · `D3` · `D4.1`/`D4.2`/`D4.3` · canonical analysis lines 551–552 · `R-34`/`P-2` · `ES-006.1`

---

# APPENDED 2026-08-19 — **Follow-up 2 CLOSED · `L3`'s reading settled**

**Nothing above rewritten.** §3 recorded the divergence and declined to resolve it; the PO/ARB has now resolved it.

## The resolution, as delivered

> **"The adopted model is: `L3` = L2 + `E3`(exercised) + `E10`, *not* merely: operational maturity."**
> *"The simplified version `L3 = it works over time` is insufficient. The actual meaning: the capability has demonstrated **exercised authority conditions** and operational durability."*

✅ **The proposal's §4.2 governs. The act's earlier summary is confirmed as a restatement, not the object** — the same resolution shape as Flag O, and the PO/ARB states the principle it rests on:

> ### ⭐ **"A summary must never silently replace the governing artifact."**

## The three levels, settled

| Level | Meaning |
|---|---|
| **L1** | receipt realization **milestone** — ⛔ **not establishment** |
| **L2** | **capability establishment** — the `D2` boundary |
| **L3** | **AUTHORITATIVE capability establishment** |

**And the distinction it protects:**

```
Capability existence  ≠  Capability authority  ≠  Operational maturity
```

⭐ **`E3`(exercised) is retained inside the model.** Had `L3` been read as maturity-only it would have fallen out entirely, and `L3` would have meant *"it kept working"* rather than *"it may be relied upon."*

## Follow-up status

| # | Item | Status |
|---|---|---|
| **1** | verification of the first real proof slice | ⏳ **OPEN** — the first satisfaction judgement still needs an eligible, disclosed process |
| **2** | assurance-level interpretation | ✅ **CLOSED by this act** |
| **3** | operational-maturity distinction (`E6` vs `E10`) | ⏳ **OPEN, and reaffirmed** — `E6` asks *"does this happen more than once?"* (supports L2); `E10` asks *"does this continue reliably across time?"* (supports L3). ⛔ **If they collapse, L2 absorbs L3 and authoritative establishment becomes meaningless** |

## Next action — operational, not architectural

⛔ **No further architecture artifact is required, and `D6` is not reachable.** `D6` asks *"what kind of thing is C-10?"* while `D2` still asks *"does the thing exist?"* — **existence precedes categorization.**

**The required future event:**

```
governed act → C-10 receipt lifecycle → repeat realization → evidence reconstruction → PO/ARB revisits D2
```

> **Until then: `C-10` is a DESIGNED capability, not an ESTABLISHED one.**

**Traceability:** PO/ARB act 2026-08-19 (L3 resolution) · §3 of this decision (the divergence it closes) · proposal `10fbfb05` §3.1/§4.2/§4.3 · Flag-O resolution *(same shape)* · `D2` register
