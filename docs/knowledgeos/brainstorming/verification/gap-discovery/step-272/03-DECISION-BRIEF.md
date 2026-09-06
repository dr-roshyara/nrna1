# 03 — Consolidated Decision Brief

**Purpose.** 13 of 22 canonical artifacts are blocked, and `canonical-construction/BLOCKED-MANIFEST`
states the pattern plainly:

> *"Every blocked artifact is blocked by a NORMATIVE choice or an OPEN CORPUS QUESTION — not by
> missing analysis. The analysis is done. What is missing is six decisions."*

This brief consolidates those six, records **this session's independent verification status** on
each, and orders them by what they unblock. **Nothing here is decided.**

---

## The six, ordered by leverage

| # | Decision | Independently verified here? | Unblocks | Cost of deciding |
|---|---|---|---|---|
| **D-0** | Was `𝒪_core` actually decided? | ✅ **YES — now SPLIT** (see `05`) | everything downstream of Step 273 | one sentence |
| **D-5** | Restore `ℛ` to the corpus 8-tuple? | ✅ **YES** — Step 273 uses 3 fields; grep confirms 0 occurrences of the 8-tuple | G-55, provenance, contestability | small |
| **D-2** | Authority: exogenous-untyped or exogenous-**typed**? | ✅ **YES** — 132/132 `humanActRef`, 0 `AuthorityAct` | the one genuine innovation | small, bounded |
| **D-1** | Are `Transform, Merge, Split, Reintroduce` mandatory? | ✅ **YES** — 14 forced / 18 upper bound reproduced | congruence proof, `Minimality(K\|𝒯)` | one choice of 4 |
| **D-3** | How much of the justification does `Determine` retain? | partially — `Determine` forced, absent from registry | canonical Determination | one choice of 3 |
| **D-4** | Is `Σ` stored or derived? | ✅ **YES** — Σ is policy-relative by construction | canonical status | **do not decide yet** |

---

## D-0 — the one that is urgent
> ⚠️ **UPDATED by [`05-ADDENDUM-STEP-272A.md`](./05-ADDENDUM-STEP-272A.md) §4.** Step 272A closes the
> **content** half (a derivation now exists). The **authority** half stands: Step 272A carries
> `Authority: HPA` as a front-matter label with **no ruling in its body**, and governance-notes is
> still GN-73 with **0** entries.

**Question.** Step 273 works from *"the verified semantic operation universe `𝒪_core`"* and cites an
*"attached HPA response."* **Did you make that decision?**

**This session's independent re-verification** (`exec/premise_audit.py`):

```
files containing 'O_core'                                 : 7
   Steps 273, 274, 275, 276 (×3), 277 — all Stratum-1 research narrative
declaring artifact                                        : none
governance-notes.md, highest note                         : GN-73
governance-notes entries for O_core / "operation universe" : 0
files named *HPA* in docs/                                : 2  (a GN-31 ruling; a Gītā response)
   — neither is the cited response
```

**canonical-construction measured 5 occurrences in 1 file. It is now 7 files.** And Step 278's HPA
ruling has since commissioned Steps 279 and 280 on top of it.

> **An unrecorded decision is compounding at roughly one step every ten minutes.**

Against this estate's own governance standard — **132/132 grants carry `humanActRef`, `registeredBy:
governance` on 132/132** — a premise driving six research steps with **zero** authority record is a
**100 %-coverage invariant with a live exception**.

**Options.** (a) you decided → record it as an authority act, D-1 largely closes ·
(b) you did not → Steps 273–278 are building on an unauthorised premise and should be marked
`HYPOTHESIS` · (c) it was a working assumption → say so, and it stays `HYPOTHESIS`.

**RECOMMENDATION** *(recommendation, not fact)*: **answer D-0 before anything else.** It is one
sentence, it is free, and every other decision inherits its status.

---

## D-5 — the cheapest structural repair

**Question.** The corpus defines `r = (E₁, E₂, T, R, Q, E, Σ, τ)` — an **8-tuple over two *or more*
entities**, in four files. The terminal model uses `ℛ ⊆ 𝒜 × 𝒜 × RelationType`.

**Verified here:** Step 273 line 839 uses `R ⊆ A × Type_R × A`. Grep for a restored 8-tuple across
Steps 272–278: **0 occurrences.**

**Consequence, already constructed twice** (`gap-discovery/05` CS-3; canonical-construction D-5):
under the 3-field form, *"A₁ contradicts A₂"* **cannot be evidenced, dated, superseded or contested** —
and `exec/so_exp02`/`so_exp04` showed this is exactly what makes §265.11's boxed merge-provenance
invariant **inexpressible** (G-55).

**RECOMMENDATION**: **restore the 8-tuple.** It is a *promotion* of a corpus construction, not an
innovation; it closes G-55 and G-24; and three independent passes have now derived the same need.

---

## D-2 — the only place innovation is required

**Verified here:** 132 grants across 22 work items, **132/132** carrying `humanActRef`; **0** typed
authority-act objects; 79 `humanAct` entries, **all free-text strings**; `AuthorityAct` — **0**
occurrences in Step 278, which is the step *about* authority.

**RECOMMENDATION**: **(b) exogenous but typed.** Keeps `Kernel enforces, never originates` and the
acyclicity it buys; fixes revocation, version drift and the `grantId` collision the estate has
already suffered. **This is the single genuine innovation in the whole register** — three independent
passes agree.

---

## D-1 — narrowed to four operations

14-element lower bound **forced** by the corpus's own non-collapse laws; upper bound 18. Open only:
are `Transform, Merge, Split, Reintroduce` *mandatory*?

**What it does not block:** `K` — invariant across all **16** subsets.
**What it does block:** the congruence proof and `Minimality(K|𝒯)` — and Step 277 confirms
*"Minimality: OPEN."*

**RECOMMENDATION**: **(c) or (d)** — both keep the 14-element core provable now. And note
independently: **`Qualify`, `Determine` and `Derive` are forced by laws the corpus repeats 20, 19 and
18 times, yet appear in neither §256.2 nor §259.7.** Whichever option is taken, the registry needs
them added.

---

## D-3 and D-4 — lower priority

**D-3** — the law `Determination ≠ Decision` (19×) forces the *distinction*, not the *retention
depth*. **RECOMMENDATION: (b)** determination + reference to the rule instance.

**D-4** — **do not decide.** Step 273 §273.12's Model A/B/C tests are the designated instrument and
are in flight; §273.31 forbids pre-empting them. This is the one decision where the correct act is to
wait.

---

## What is NOT a decision

Recorded so these are not reopened as questions:

| | Settled | By |
|---|---|---|
| `Σ`'s epistemic shape | `Σ = (D, S)`, `Σ ⊊ D × S`; Conflict/Contest/Acceptance/Supersession/Validity separated **out** | Step 275 |
| `K`'s ontology | `K = (D_t, 𝒜, ℛ, Σ_c, E_L)`, `𝒦 = (K, H)`, band-invariant 16/16 | Step 273 + canonical-construction |
| `𝒪` classification | CLOSED | Step 277 |
| Authority ≠ Authorization | boxed | Step 278 |
| Governance is a transformation constraint, not an epistemic state | boxed | Step 278 |

---

## The one thing no decision covers

**K-sufficiency.** Step 277 declares it OPEN and commissioned Step 278 to test it; Step 278 did
Policy–Authority instead and mentions it **zero times** (PA-1). Its defining criterion —
congruence **plus** invariant-expressibility — sits unadopted at **0 occurrences** (PA-2).

**This needs no decision. It needs the commissioned step to actually be performed**, with the
criterion `Sufficient(K, 𝒪, ℐ)` rather than congruence alone.

---

**Next:** `04-ARCHITECT-ASSESSMENT-OF-THE-MANDATE.md`.
