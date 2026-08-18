# `EM-DOM-001` — **DECISION RECORDING SURFACE** for the human PO/ARB

**Prepared by:** a governance **recording** session · 2026-08-18 · **Read-only w.r.t. implementation.**
**Status:** ⬜ **ALL FOUR BLOCKS BLANK. NOTHING DECIDED. This surface confers nothing.**

> ⛔ **This session is not the PO/ARB and decides nothing.** It prepared the blocks, the options and the consequences. **Every choice below is the human's.** ⛔ **No option is marked, recommended or pre-selected in any block.**

**Analysis is NOT repeated here.** The evidence and reasoning live in the dossier: `2026-08-18-EM-DOM-001-po-arb-decision-dossier.md` (§§1–10). **This surface is only the place where a decision is written down.**

---

## ⚠️ Two recording-integrity notices, before any signature

### N-1 · The evidence base is **UNTRACKED** — another session's uncommitted work

| Artifact | Git state |
|---|---|
| `2026-08-18-EM-DOM-001-po-arb-decision-dossier.md` | 🔴 **UNTRACKED** |
| `2026-08-18-EM-DOM-001-architecture-investigation-and-decision-dossiers.md` | 🔴 **UNTRACKED** |

**Both belong to a different session and this recording session must not commit another session's files.** ⚠️ **Consequence, stated so it is not discovered later: decisions signed now would rest on evidence that is not in git history and could be lost or altered without trace.** **Recommended sequence — the authoring session commits its two dossiers FIRST, then the blocks below are signed.** *(A recommendation about record hygiene; it does not gate the human's authority to sign now if they choose.)*

### N-2 · Correction to this lane's earlier provenance claim

The `EM-DOM-001` verification record (`7654b9e4`) stated: ***"no investigation artifact exists in the repository."*** **That was wrong.** It checked `docs/publicdigit/architecture/` and `git log`, and **never listed `docs/publicdigit/reviews/`** — where both artifacts sit. **The artifacts exist and are substantial.**

**What survives that error:** the two substantive corrections (the `intervalState()` reasoning; `BND-1 ≠ EM-OPEN-055`) — **and the dossier independently re-derived both** (§1.3, §6.2), so they never depended on that claim. **What does not survive:** any suggestion that the inbound findings had no authored basis. **They did.** ⚠️ **Recorded because a false provenance claim is exactly the kind of premise that must not reach a ruling.**

---

# DECISION 1 — `BND-2`: the authorization boundary

> ### The exact question
> **Does the `EM-DOM-001` authorization permit creation of a NEW Domain-owned identity / retrieval contract for the operational overlay (`ElectionOperationalStatus`) — domain-side only, no infrastructure, no adapter?**

**This is act B of the five distinguishable acts** (dossier §7.2). **Acts A, C, D, E are NOT this question** and remain unauthorized regardless of the answer: **A** modify an existing repository interface · **C** persistence/adapter · **D** Application call sites · **E** a new identity/persistence *model* (that is `BND-3`).

**Why the text supports both readings:** the authorization forbids *"modify repositories"*; ADR-2 §6(e) requires *"confirmation that the resulting contract can be consumed by the Application layer"*, which presupposes a loadable contract.

| Choice | Consequence if chosen |
|---|---|
| **[A] IN SCOPE** | Act **B** only becomes authorized. **One new domain contract; no runtime behaviour** until act C, which is **not** authorized. `BND-3` stays open — **this does not settle the overlay's boundary**, and **`R-1`'s FORM is not approved**. `app/`, `tests/` and the frozen core stay untouched until a domain slice is separately authorized under Rule 8, **with its own RED test**. |
| **[B] OUT OF SCOPE** | ⚠️ **`DEP-5b` and `DEP-6` cannot be discharged at all** — not later, not differently. `P-7` needs a `HaltedAtGate` that is recorded truth, and **no authorized supplier exists** without act B; every alternative is an already-prohibited `DEP-7`/`DEP-8`/`DEP-9` path. **This is therefore a decision to STOP that work**, and a new authorization would be required to resume it. **UC-3 keeps today's `$decision->gate()` proxy** under ADR-2 §6(f). |

**Draft wording for either choice: dossier §9 Draft B (`B-IN` / `B-OUT`).** ⛔ **Neither is marked.**

### ⬜ DECISION 1 — LEFT BLANK

```
Choice ( [A] IN SCOPE  |  [B] OUT OF SCOPE ):  ____________

PO/ARB wording (verbatim, as you write it):




Signed: ____________________   Date: ____________
```

---

# DECISION 2 — `w8`: confirmation of a **sharpening**, not a new ruling

> ### ⚠️ What this block actually asks — and it is NOT what it may appear to ask
> **`ADR_20260817_2300` §6 is already SIGNED and already rules these semantics** (§6(a) do not collapse the two causal questions · §6(b) restoration without a prior `RecoveryProcess` is a legitimate possibility, fabrications forbidden · §6(h) explicit representation required, the non-nullable `GateDesignation` being the domain-model gap).
>
> ⇒ **`Restoration ≠ Resumption` is a SHARPENING of a ruling the PO/ARB has already made — not a new question.** **This block must not be recorded as amending a signed ADR.**

**The one genuinely open point is a VOCABULARY RECONCILIATION.** The signed text is framed on *"without a prior `RecoveryProcess`"*. **The pinned case is narrower:** `FillCommitteeSeatHandlerRedTest`'s `w8` fixture **seeds `RecoveryProcess(PeriodKind::CommitteeRestoration)`** — so **a `RecoveryProcess` EXISTS in `w8`; no `HaltedElectionRecovery` period does.**

> **`w8` = restoration without a prior HALT. It is NOT restoration without a `RecoveryProcess`.** Both name legitimate domain possibilities; **only the halt-absent one is pinned by an accepted RED test.**

**What the human is asked to confirm:** that §6(b)'s *"legitimate domain possibility"* and §6(h)'s explicit-representation obligation **are read as covering the halt-absent path**.

**The clarification, as proposed:** *"Restoration and Resumption are distinct domain concepts. The `w8` case represents restoration without a prior halt. It therefore has a causal origin but no resumption target."* ✅ **This wording matches the pinned evidence.** **Fuller draft: dossier §9 Draft A.**

⛔ **Selects no representation:** no nullability · no sentinel · no `UnknownGate` · no new enum value · no new aggregate · no new event · no replacement representation · no protocol read · no repository change. **Representation follows meaning.** ⛔ **Grants no implementation authority; the ADR-2 Rule-8 gate stands as recorded (BLOCKED).**

### ⬜ DECISION 2 — LEFT BLANK

```
Confirmed as a sharpening of ADR-2 §6 (not an amendment)?  ____________

PO/ARB wording (verbatim, as you write it):




Signed: ____________________   Date: ____________
```

---

# DECISION 3 — `BND-1`: lifecycle-phase ownership *(kept separate from `EM-OPEN-055`)*

> ### The exact question
> **Who owns "lifecycle PHASE" for the OperatingCore — the discriminator separating `ADR-1` §6 row 2 (`AcceptanceDecision` *required-but-absent* = **violation**) from row 3 (the election has not yet reached the phase in which an acceptance decision exists = **legitimate lifecycle state**)?**

**Evidence.** The OperatingCore contains **no phase concept at all**. `GateDesignation` names *which gate*; `GateIntervalState` is *the gate's own verdict*; **neither is a phase.** `ElectionLifecycleState` and `ElectionConstitution` live in `app/Domain/Election/` — **a different bounded context** — and contain **zero** halt/gate/operative/inoperative vocabulary. Separately, `HaltedAtGate`'s docblock asserts lifecycle transitions are *"canonically homed in `ElectionConstitution`"*; **that class does not contain the concept.**

| Candidate interpretation | What it costs |
|---|---|
| **(a)** a **new OperatingCore phase concept** | a **new strategic concept**, not a representation of an already-approved invariant |
| **(b)** a **Published-Language interaction** with the legacy context | a **context-map change**; requires the Phase-2 Strategic-DDD answers (`AIP-14`) |
| **(c)** a ruling that **AG-2's establishment IS the phase marker** | a **governance reading** of ADR-1 rows 2–3 — cheapest, and it must be *ruled*, never assumed |

⛔ **`BND-1` is NOT `EM-OPEN-055`.** `BND-1` = the **discriminator/meaning** ADR-1's absence semantics need. `EM-OPEN-055` = **who may participate** in acceptance decisions and under what rule (twelve parts). **Resolving `EM-OPEN-055` would not resolve `BND-1`.** ⚠️ **If merged, `BND-1` gets deferred *as* `EM-OPEN-055` and is then never answered on its own terms.**

**Consequences.** ⛔ **Off** the restoration critical path *(the required-votes equivalence removed the denominator need — subject to dossier §3's condition: it holds while exactly one numerically-inert `ThresholdRule` exists)*. ✅ **Still live** for **UC-2** and for **ADR-1's `AcceptanceDecision` semantics**. ⚠️ **Gating input to the ONE normalization slice `ADR-1` §6(c) contemplates**, whose criterion is that each handler conform to the governed meaning of **every** absent reference.

**What remains unresolved either way:** the `HaltedAtGate` ↔ `ElectionConstitution` **documentation-vs-architecture discrepancy**, which both `BND-1` and `BND-3` depend on.

### ⬜ DECISION 3 — LEFT BLANK

```
Ruling — or explicit DEFERRAL (deferral must state: open, not closed, not merged into EM-OPEN-055):

Interpretation, if ruled ( (a) | (b) | (c) | other ):  ____________

PO/ARB wording (verbatim, as you write it):




Signed: ____________________   Date: ____________
```

---

# DECISION 4 — `BND-3`: the operational overlay's boundary *(not before `BND-1`)*

> ### The exact question
> **What is the operational overlay's correct boundary and lifecycle ownership?**

**What the evidence establishes:** the overlay must be **retrievable recorded truth** — `EM-GOV-059(b)` *retains* the halt across `becameInoperative()` → `restored()`, and **a value recomputed per request cannot retain anything.**
**What the evidence does NOT establish:** that the correct model is a fourth aggregate. > ## **Identity + persistence does not imply "aggregate."** *(The Domain lane's earlier "fourth aggregate" claim was **withdrawn** for exactly this reason.)*

| Candidate reading | *(none selected; none may be chosen for ease of implementation)* |
|---|---|
| a **distinct aggregate** | |
| **part of a lifecycle aggregate** not yet present in the OperatingCore | |
| a **projection** over recorded lifecycle facts | |

**Dependency on lifecycle ownership.** The overlay's boundary depends on **who owns lifecycle transitions** — precisely what `BND-1` and the `ElectionConstitution` discrepancy leave open. ⚠️ **A boundary chosen first would fix the wrong seam.** ⚠️ **Noted for whoever rules: with `BND-1` now off the restoration path, the STRENGTH of that ordering dependency should be re-examined rather than assumed.**

**Consequences.** Choosing early risks the wrong seam. **Deferring costs nothing immediately** — act **E** is unauthorized under any `BND-2` answer, and **`BND-2 = [A] IN SCOPE` would authorize identity and retrieval WITHOUT settling shape.**

### ⬜ DECISION 4 — LEFT BLANK

```
Ruling — or explicit DEFERRAL pending BND-1:

PO/ARB wording (verbatim, as you write it):




Signed: ____________________   Date: ____________
```

---

# What happens AFTER the human supplies decisions — queued, NOT done

**In this order, by the recording session:**

1. **Record the human's EXACT wording** — verbatim, into this surface and the governing artifact. **No paraphrase, no tidying, no reordering.**
2. **Preserve decision ≠ recommendation.** A recommendation, however favourable or however often repeated, **is not recorded as a ruling**. ⚠️ *Two favourable reviews do not sum to an acceptance.*
3. ⛔ **Do not broaden the authorization.** What is authorized is exactly what the wording says — **never its evident intent, never its natural extension.**
4. **Run the Rule-8 dependency gate** against the new authorization state.
5. **Report exactly what is authorized and what remains prohibited** — including anything the decisions leave impossible.

⛔ **No implementation in that session either.**

# Standing constraints, restated because they survive every answer above

> ## **An ADR signature is not layer-wide implementation authorization.**
> ## **A scope authorization is not solution authorization.**
> ## **A recommendation is not a decision.**

**Remaining unauthorized and untouched:** `R-1`/`R-2`/`R-3` *(proposals, not approved designs)* · any change to `app/`, `tests/` or the frozen Domain core · any repository, port or protocol change · UC-1/UC-2/UC-3 normalization *(ADR-1 §6(c) subject to Rule 8; ADR-2 §6(f) bars UC-3)* · the misleading `RecordVacancyEventHandler:210` message *(observation only)* · any ADR decision-block text · **any code prepared in anticipation of a decision.**

**State at preparation:** HEAD `4796ef1f` · frozen domain core byte-identical to `1f4b4c5f` · `EM-IMPL-002` Rule-8 gate for ADR-2 **🔴 BLOCKED** · GREEN-5 **STOPPED** · `AbsentAggregateReferenceRedTest` **RED**, and not any slice's target.

**Traceability:** `ADR_20260817_2145` §6 · `ADR_20260817_2300` §6(a)–(h) · `EM-DOM-001` authorization + Annotations A/B · Phase-1 map §§2/4/8 *(ACCEPTED as analysis, `e57adac2`)* · verification record *(`7654b9e4`, corrected by N-2)* · PO/ARB decision dossier §§1–10 *(UNTRACKED — N-1)* · `EM-GOV-036`/`057`/`059(b)`/`059(c)`/`062` · `D-6` · `EM-OPEN-055` · `AIP-14` · `1f4b4c5f`.
