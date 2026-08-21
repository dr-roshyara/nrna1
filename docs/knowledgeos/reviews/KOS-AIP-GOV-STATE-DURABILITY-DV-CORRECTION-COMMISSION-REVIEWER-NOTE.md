# `DV-1`…`DV-7` — ⭐ **REVIEWER'S NOTE ON THE REGISTERED CORRECTION COMMISSION**

**Work item / aggregate:** `KOS-AIP-GOV-STATE-DURABILITY-ADR` · workflow `architecture-adr` · track label `KOS-AIP-GOV-STATE-DURABILITY`
**Written by:** the independent AMD6 Architecture reviewer, `claude-code-session:dd639043` · 2026-08-21
**Subject:** the registered commission **`G-KOS-GOV-STATE-DURABILITY-MIGRATION-PLAN-DV-CORRECTION`** *(status `AUTHORIZED` · `registeredBy: governance` · PO/ARB delivered act 2026-08-21)*
**Findings source:** `…-MIGRATION-PLAN-AMD6-ARCHITECTURE-REVIEW.md` — `DV-1`…`DV-7`, as this process stated them

> ## ⛔ **THIS ARTIFACT CARRIES NO AUTHORITY.**
> **It is not a commission · not a grant · not an amendment · not a remedy · not an acceptance · not a decision.** **It proposes no plan text.**
> ⭐ **It is ADDITIVE to the registered commission and supersedes nothing in it** — the same pattern `claude-code-session:ccf6c9c7` used when it recorded defects in the AMD6 commission as drafted (its §18). ⛔ **Where this note and the registered grant differ, THE GRANT GOVERNS and the difference is a matter to raise, not a choice to make.**
> ⛔ **The writing process is the FINDING-OWNER and is BARRED BY THE GRANT ITSELF from authoring this correction** — *"THE AMD6 REVIEWER `claude-code-session:dd639043` MUST NOT AUTHOR THIS CORRECTION."* **It is equally barred from reviewing it.**

## 0 · ⚠️ **This artifact was drafted as a COMMISSION-PREP and was overtaken while being written**

| | |
|---|---|
| **What happened** | this note began as a *commission-prep* — an input for PO/ARB to deliver and Governance to register. ⭐ **Mid-session, at 14:32, Governance registered `…-DV-CORRECTION` on a delivered PO/ARB act.** ⇒ **the prep's purpose was discharged before it was finished** |
| ✅ **The registered scope matches the findings** | independently compared, item by item: `DV-1` classed **SAFETY-CRITICAL / UNSAFE DIRECTION** with Phase 5 barred until repaired and independently reviewed · `DV-2` posed as a required question to Architecture · `DV-3` with **criterion 17 named** · `DV-4` · `DV-5` · `DV-6` **with the correct reason, in the same words the review used — *"LAUNDER THE QUARANTINE"*** · `DV-7`. ⭐ **The naming discipline is observed: *"deliberately NOT named AMD7 … the identifier is left to the registry/commission mechanism."*** ⭐ **And flag `AD` records precisely what was NOT determined: whether `C-10`'s four authoring bars carry to a different subject** |
| ⇒ **what this note is now FOR** | ⭐ **the four technical traps the REGISTERED scope does not carry** — §2's `TRAP` rows, and above all `DV-1`'s **comparison object**. ⛔ **Everything else here is now redundant with the grant and is retained only so the reasoning behind each item remains readable** |
| ⭐ **The most important one, stated once here so it cannot be missed** | the grant requires *"an ALL-OR-NOTHING VERIFICATION OF THE DURABLE COPY after the final copy-side write and BEFORE the writer switch"* — ✅ **correct, and it does not say WHAT THE COPY IS COMPARED AGAINST.** 🔴 **If Architecture specifies *"re-run Phase 4"*, the comparison object is the FROZEN MANIFEST, which predates the reconciled delta and the pre-switch Phase-5 records ⇒ THE CHECK FAILS IN THE NORMAL CASE, and `DV-1`'s repair reproduces `DV-2`'s defect inside itself.** ⇒ **the comparison object must be the CLOSED `SOURCE FINAL-STATE ENUMERATION` — §2.1 `TRAP 1`** |
| ⚠️ **A correction to the review's own live-state figure** | the review records the corpus at **114 grants / 113 unique**. ⭐ **That was exact when measured and is now stale: the corpus stands at 115 / 114, and the 115th grant IS this commission.** ⛔ **The review is not rewritten** — it was consumed by Governance at 583 lines as delivered, and a delivered artifact is not edited to track state that moved after it. ⭐ **The movement is itself `RD-10`'s phenomenon and `C-4`'s premise, observed a second time: the corpus grew by exactly the act that commissioned the repair, while the reviewer was measuring it** |

**Placement derived, not chosen:** `php scripts/doc-placement.php --scope=product-specific --domain=knowledgeos` → `docs/knowledgeos`, **exit 0**.

---

# 1 · State the commission acts on

```
AMD6's seven commissioned residuals        ⭐ CLOSED by independent review (dd639043)
    DI-5/RD-1 · RD-7·b · RD-3·a · RD-3·b · DI-4 · DI-6 · DI-7
C-4 / C-7 Phase-5 evidence placement       🟡 ADDRESSED BUT NOT CLOSED
    → DV-1 (unsafe direction) · DV-2 · DV-3 · DV-4 · DV-5 · DV-6 · DV-7
AMD6 registration (OPEN-M6, four deep)     ⏳ OPEN
Phase 5                                    ⛔ PROHIBITED until DV-1 is repaired
Phase 3                                    ⛔ MUST NOT BEGIN
```

⛔ **The seven closed residuals are NOT reopened by this commission and must not be re-litigated by the amendment.** ⭐ **The correction is scoped to the `C-4`/`C-7` decision's consequences and to nothing else.**

---

# 2 · What the commission must require, finding by finding

**Form used throughout: REQUIRED PROPERTY → WHERE IT BITES → PROPAGATION SURFACE → ⛔ THE TRAP.** ⛔ **No remedy text is supplied; the property is stated and the mechanism is left to Architecture (`C-7`'s rule, unchanged).**

## 2.1 🔴 `DV-1` — the mandatory safety repair · **gates PHASE 5**

| | |
|---|---|
| **Required property** | ⭐ **The durable copy is verified — Phase-4 semantics, ALL-OR-NOTHING — after the slot-`3(iii)` carry-across and BEFORE the writer switch.** A failure is a **STOP BEFORE THE SWITCH** |
| **Why it is mandatory** | the copy is written at slot `1b` and slot `3(iii)`, both **after** Phase 4's all-or-nothing verification, and Phase 7's gate compares **source vs the recorded enumeration** — neither operand is the copy. ⇒ **a silent copy-side failure passes every specified gate and Phase 7 then removes the source.** ⛔ **That defeats `B′`: the surviving authoritative store would be the unverified one** |
| **Where it bites** | §4.3 slot 3's sub-step sequence · §4.4's Phase-7 precondition · §4.7 `P5·3` · §10 criterion **18** *(which asserts the carry-across and names a specification, not a verifying act)* · §10 criterion **2** *(same shape: its named evidence all predates the writes)* |
| ⛔ **TRAP 1 — the comparison object** | ⭐ **The verification must be against the CLOSED `SOURCE FINAL-STATE ENUMERATION`, ⛔ NOT against the frozen manifest.** **The manifest predates the reconciled delta and the pre-switch Phase-5 records ⇒ a manifest-based re-verification FAILS IN THE NORMAL CASE.** ⛔ **Specifying *"re-run Phase 4"* would reproduce `DV-2`'s defect inside `DV-1`'s repair** |
| ⛔ **TRAP 2 — do not add two checks** | ⭐ **ONE verification point suffices.** Slot `3(iii)` brings the copy to the source's **closing state**, which already includes slot `1b`'s reconciled delta ⇒ a single all-or-nothing check after `(iii)` covers **both** writes. ⛔ **A second check at `1b` adds a gate without adding coverage** |
| ⛔ **TRAP 3 — no new act class** | the failure disposition already exists: §4.3's abandonment-cost table makes a stop at slot `3(ii)` *"still BEFORE the authority transfer … but the slot-2 READER switches must be reverted"*. ⭐ **The new check's failure belongs in that same cell.** ⛔ **Do not invent a fourth outcome, a new marker, or a rollback class** |
| ⛔ **TRAP 4 — renumbering** | **adding a sub-step renumbers slot 3.** ⭐ **Every citing site must move in the SAME edit:** §4.7 `P5·3` · §0.6.5's trace rows **4** and **5** · criteria **17** and **18** · §4.4's precondition · §4.3's residual-window table *(the `measure → switch` window's endpoints)*. ⛔ **A criterion citing a sub-step the mandated block does not define is EXACTLY `DI-5`, and `DI-5` was closed one amendment ago** |
| **Consequence if omitted** | ⛔ **Phase 5 remains prohibited.** This is the only residual in the chain whose failure direction is **UNSAFE** |

## 2.2 🔴 `DV-2` — align the Phase-0 declaration with the `C-4` placement model · **gates PHASE 0 · ACCEPTANCE**

| | |
|---|---|
| **Required property** | ⭐ **The Phase-0 expected-delta declaration is required to COVER the pre-switch Phase-5 record classes — slots 1, `1b` and 2 — so that slot `3(ii)`'s three-outcome test does not yield a FREEZE VIOLATION in the normal case** |
| **Why** | `C-4` gave the comparison object a third operand — *"the **DECLARED** pre-switch Phase-5 records"* — and **§4.0, the only place the declaration's contents are specified, was not touched by `8307beca`.** Its list names *"the reconciliation records"* (slot `1b`) and **not** slot 1's re-hash comparison record or slot 2's reader-switch evidence |
| **Where it bites** | **§4.0** *(the specification of the declaration)* · §4.3 slot `3(ii)` · §4.4's precondition · criteria **11 · 12 · 17 · 18** · §4.7 `P5·1b` and `P5·3` · §11.1's Phase-0 row |
| ⭐ **The repair is TWO-SIDED** | **(a) ADD** the slot 1 and slot 2 record classes. **(b) RELABEL or REMOVE** *"the switch-over record"*, which §4.0 still lists among records expected in the **SOURCE** delta — ⛔ **after `C-4` that record is never written into the source, so it can never appear there.** ⭐ **A declared class that cannot occur is inert, but it is a list that no longer matches the design it feeds** |
| ⭐ **Preferred form, already recorded** | **`RD-10·r2`'s CLASS-BASED declaration** — *"records written by this lane for this work item"* — which §4.0's own *"record classes"* wording already permits, and which also disposes of `RD-10·r2`'s exception-path gap |
| ⛔ **TRAP** | ⛔ **The declaration is PO/ARB's act. The amendment must specify what the act MUST CONTAIN and must NOT write the declaration.** ⭐ **§11.1 already raises the stakes on this act (*"AMD6 raises the stakes without changing the act"*); what is missing is the content requirement** |
| **Failure direction** | ✅ **SAFE — a stop before any switch.** ⛔ **But it makes the NORMAL path unsatisfiable, which is the defect class `C-4` exists to remove** |

## 2.3 🔴 `DV-3` — the acceptance object must be demonstrable · **gates ACCEPTANCE**

| | |
|---|---|
| **Required property** | ⭐ **The claim states the domain concept that is TRUE: the switch-over record is the FIRST GOVERNANCE APPEND made into the authoritative store AFTER the authority transfer** |
| **Why the current wording fails** | *"the FIRST record in the authoritative store"* is false under the plan's own vocabulary **under either reading** — by §4 row 2's `*.json` predicate the store already holds the copied corpus, and by §1.1's counting (*"18 records · 216 transitions · 114 grants"*) the switch-over record is a **transition inside an existing record**, following 216 carried-across transitions. ⛔ **Criterion 17 is therefore not demonstrable as written** |
| ⭐ **PROPAGATION SURFACE — FIVE sites, and the fifth is the second artifact** | **§0.6.3**'s `C-4`-property table · **§4.3**'s placement table · **§4 row 9**'s *Produces* cell · **§10 criterion 17** · ⭐ **`…-AMD6-SUMMARY.md` line 123**. ⛔ **`DI-1`'s lesson is exactly this: the second artifact propagated the defect and had to be corrected in the same act** |
| ⭐ **The vocabulary already exists** | §4.3 distinguishes **GOVERNANCE APPENDS** from **MIGRATION MECHANICAL WRITES** and then does not use the distinction in the claim. ⛔ **No new term is needed, and none should be introduced** |
| ⛔ **TRAP** | ⛔ **Do not weaken the discriminator to fix the wording.** ⭐ **"First post-switch governance append" is not a retreat — it is precisely what the content-defined boundary test requires** |

## 2.4 ⚠️ `DV-4` — the `C-4` termination argument's premise · **gates ACCEPTANCE**

**Required property:** ⭐ **the argument CONTAINS the step that makes it a derivation.**
As written the recursion reads as stoppable after one iteration, because a reconciliation is a mechanical write **into the copy** and need not append to the source at all. What makes it non-terminating is a rule stated in a **different row of a different table** — *"a mechanical write … **is evidenced by a governance append that describes it**"*. Pre-switch, that append lands in the source, which re-opens the delta.
⛔ **TRAP:** ⛔ **Do not restate the rule in a second place.** ⭐ **Cite it inside the argument.** ⚠️ **Why it matters beyond tidiness: if a later amendment relaxed the evidencing rule, `C-4`'s justification would silently become invalid while the decision stood — a decision surviving its own reason is the failure mode §0.4.4 exists to prevent.**

## 2.5 ⚠️ `DV-5` — the stale-read bound · **gates ACCEPTANCE**

**Required property:** ⭐ **the bound is attributed to the mechanism that actually provides it, and the window is recorded.**
§4.3 says *"this order creates only a briefly-stale-read window, and **the re-hash has already bounded that**."* 🔴 **False post-`C-4`:** slot 2 switches the readers to the copy while slots 1, `1b` and 2 append **into the source** ⇒ the window is **non-empty by construction** and contains at least slot 2's own record, and **slot 1's re-hash precedes all three.** ✅ **What bounds it is slot `3(i)`–`(iii)` — a mechanism AMD6 supplies.**
⛔ **TRAP:** the window is **absent from §4.3's residual-window table**, which lists `measure→switch`, `3→4`, `3→5` and `final-rehash→removal`. ⭐ **Add it there, with its bound and its scope — bounded, self-referential, and closed at `(iii)`.** ⛔ **Do not reorder readers and writer to remove it; `RC-5`'s ordering is closed and the reverse order creates an invisible-write window.**

## 2.6 ⚠️ `DV-6` — the real reason a MOVE is forbidden · **gates PHASE 7**

**Required property:** ⭐ **the stated reason is the load-bearing one.**
§4.4 currently says a MOVE *"would alter the demoted store's content and so break the recorded `SOURCE FINAL-STATE ENUMERATION`"*. 🔴 **Inverted:** a post-demotion record is **outside** the enumeration by definition, so moving it out makes the source **MATCH** the enumeration ⇒ the final re-hash reports `identical` ⇒ **criterion 12's removal branch opens while a quarantine stands undisposed.** ⭐ **That is `RD-3·a`'s third unsafe reading in a form the section does not name — not removing the store, but removing the one record that makes it fail.**
✅ **The rule is correct and doubly guarded** (`P7·2` forbids the move in terms; criterion 20 · §4 row 11 · `P7·3` refuse removal while any quarantine is undisposed).
⭐ **PROPAGATION:** `…-AMD6-SUMMARY.md` **line 77** carries the same inverted reason.
⛔ **TRAP:** ⛔ **This edit touches §4.4's quarantine-mechanism row, whose other properties are CLOSED (`RD-3·b`/`C-2`/`C-3`/flag `AA`).** ⭐ **Change the reason; disturb no property — store-level, original filename/bytes/hash, by location never by renaming, outside every `*.json` glob, third marker.**

## 2.7 ⚠️ `DV-7` — the four-layer trace must resolve · **gates ACCEPTANCE**

**Required property:** ⭐ **every cell of the trace cites an object its normative section defines.**
§0.6.5 row 6 cites **`P5·ALL`**. §4.7 defines `P5·1 · P5·1b · P5·2 · P5·3 · P5·4 · P5·5 · P7·1 · P7·2 · P7·3` — **`P5·ALL` occurs exactly once in the artifact, in that cell.** The trace closes *"✅ Every row resolves in all four columns"*, which is false for row 6.
✅ **The substance is present** — each of `P5·1`…`P5·5` states its destination — so the repair is **`P5·1`…`P5·5`**, or a genuinely defined operator object.
⭐ **PROPAGATION:** `…-AMD6-SUMMARY.md` **line 198**.
⚠️ **Recorded because the pattern is the point: this is the fifth occurrence in this chain of *an object citing an identifier its normative section does not define*, and it occurred INSIDE the `C-12` self-check delivered to mitigate that exact quality risk.** ⛔ **It is not an argument against the trace; it is `C-12`'s second half proving itself — which is why the trace does not discharge the review gate.**

---

# 3 · Bounds the commission should carry, so the amendment cannot widen

| | Bound |
|---|---|
| ⛔ **no reopening** | `B′` · `R-CONFLICT` *(⭐ **§6 must remain BYTE-IDENTICAL — it is today, and that is a verifiable acceptance condition**)* · `INV-ORDER` · `OPEN-M3` Option A · existing placement governance · Option D · `RC-1`…`RC-11` · **the seven residuals closed by the AMD6 review** |
| ⛔ **no decisions** | `OPEN-M1` · `OPEN-M2` · `OPEN-M4` · `OPEN-M5` *(⚠️ `RD-6` enlarges it)* · `OPEN-M6` · ⭐ **`OPEN-M7` — no disposer, no disposal path, no new owner or role** |
| ⛔ **no reordering** | the sequence stays `0 · 1 · 2 · 2b-pin · 3 · 2c-commit · 4 · 4b · 5 · 6 · 7`. ⭐ **`DV-1` adds a sub-step INSIDE slot 3; it moves no phase and does not move the authority-transfer instant** |
| ⛔ **no execution** | no migration act · no file moved or copied · no `.gitignore` · no `.gitattributes` · no runtime code · no durable target · no quarantine store · no grant registered |
| ⛔ **no self-closure** | ⭐ **the §0.4.1 bound recurs for the FIFTH time: whoever authors these remedies will not review them.** **Every item `ADDRESSED`, ⛔ never `CLOSED`** |
| ⭐ **second artifact** | ⭐ **the AMD6 SUMMARY carries `DV-3`, `DV-6` and `DV-7` and must be corrected in the SAME act, labelled as a correction that changes no AMD6 disposition** — `DI-1`'s propagation lesson |
| ⚠️ **carried, and NOT in scope** | `RD-2` · `RD-4` · `RD-5` · `RD-6` · `RD-9` · `RD-10·r1` · `DI-1·r` — recorded so they are not lost, ⛔ **not commissioned here** |

---

# 4 · Routing inputs — ⛔ **stated as inputs, NOT decided**

## 4.1 Author eligibility

⛔ **PO/ARB's call, not Architecture's — and the registered grant's flag `AD` now records exactly this as undetermined.** The inputs:

| Process | Standing |
|---|---|
| `dd639043` | ⛔ **BARRED BY THE GRANT ITSELF from authoring this correction, and equally barred from reviewing it** — it owns `DV-1`…`DV-7`. ⭐ **This note authors no remedy text, so it hands no new exposure forward** |
| `bc1b47ef` | **authored AMD6 and its predecessors.** ⭐ **`C-12`'s principle — *author ≠ independent reviewer*, not *author ≠ every prior reviewer* — would make it authoring-eligible again, and repeated authoring remains a QUALITY RISK rather than an independence failure.** ⛔ **`C-12` is scoped to AMD6; extending it is a governance act** |
| `ccf6c9c7` · `870305e0` · `9c908e70` · `1c8b041b` | ⭐ **flag `AD`, verbatim in the grant: `C-10`'s bars were scoped to authoring AMD6, THIS COMMISSION IS A DIFFERENT SUBJECT, and Governance *"NEITHER SILENTLY EXTENDS THE OTHER FOUR BARS TO THIS COMMISSION NOR SILENTLY DROPS THEM"*** ⇒ ⛔ **a PO/ARB statement is required for any eligible-author set other than *"bc1b47ef or a fresh lane"* by the `C-12` precedent.** ⛔ **Not this note's to decide** |

## 4.2 The exclusion ledger for the NEXT independent review — **one addition**

```
⛔ NOT the author of the correction amendment      (whoever PO/ARB selects)
⛔ NOT dd639043    ← ⭐ THE ADDITION: author of DV-1…DV-7, barred both ways
⛔ NOT ccf6c9c7 · NOT 870305e0 · NOT 9c908e70 · NOT 1c8b041b
⛔ NOT bc1b47ef    (if it authors the correction)
```

⭐ **"Fresh to this chain" remains the requirement, and the chain is now six processes deep.** ⚠️ **Every identity in it is SELF-DECLARED and NOT ATTESTABLE (`INV-ATTR-2`/`G-2`) — the limitation `B′` exists to remove for the corpus and which no review in this chain can remove for itself.**

## 4.3 Sequence this commission would sit in

```
independent AMD6 review (dd639043)              ✅ delivered
        ↓
⭐ THIS PREP  →  PO/ARB delivered act  →  Governance registers the commission
        ↓                                        (grant: authority · humanActRef · scope · registeredBy=governance)
Architecture authors the correction              ⛔ not dd639043
        ↓
FRESH independent Architecture review            ⛔ not dd639043, not the author
        ↓
Governance bounded review  →  registers AMD3 + AMD4 + AMD5 + AMD6 + the correction   (OPEN-M6)
        ↓
PO/ARB — acceptance · OPEN-M5 · ⭐ OPEN-M7 · the PHASE-0 DECLARATION (⚠️ DV-2) · PHASE-4b (⚠️ RD-4)
        ↓
Migration from PHASE 0, never Phase 3            ⛔ PHASE 5 PROHIBITED until DV-1 lands
```

---

# 5 · ⭐ Delta against the REGISTERED scope — **the only part of this note that adds anything**

⛔ **The registrable scope text this artifact originally carried is WITHDRAWN as redundant: Governance has registered a scope, and that scope governs.** ⭐ **What follows is the difference — stated as observations for Architecture and PO/ARB, ⛔ not as amendments to the grant.**

| | The registered scope says | ⭐ What it does NOT carry |
|---|---|---|
| 🔴 **`DV-1` comparison object** | *"an ALL-OR-NOTHING VERIFICATION OF THE DURABLE COPY after the final copy-side write and BEFORE the writer switch"* | ⛔ **it does not name the comparison object.** ⭐ **It must be the CLOSED `SOURCE FINAL-STATE ENUMERATION`, NOT the frozen manifest** — a manifest-based check **fails in the normal case** and reproduces `DV-2` inside `DV-1`'s repair *(§2.1 `TRAP 1`)* |
| 🔴 **`DV-1` renumbering** | the verification's position in slot 3 | ⛔ **adding a sub-step RENUMBERS slot 3**, and every citing site must move in the SAME edit — §4.7 `P5·3` · §0.6.5 trace rows **4** and **5** · criteria **17** and **18** · §4.4's precondition · §4.3's residual-window endpoints. ⭐ **A criterion citing an undefined sub-step is EXACTLY `DI-5`, closed one amendment ago** *(§2.1 `TRAP 4`)* |
| 🔴 **second artifact** | `DV-3`: *"update EVERY current reference INCLUDING ACCEPTANCE CRITERION 17"* | ⛔ **it does not name the AMD6 SUMMARY, which is a different document and carries three of the defects — `DV-3` line 123 · `DV-6` line 77 · `DV-7` line 198.** ⭐ **`DI-1`'s lesson is precisely that the second artifact is what gets missed** |
| 🔴 **`DV-2` is two-sided** | *"which records from slots 1, 1b and 2 must be declared as expected migration-lane writes?"* | ⛔ **the other half: §4.0 STILL LISTS *"the switch-over record"* among records expected in the SOURCE delta, and `C-4` made that impossible.** ⭐ **Add the missing classes AND relabel the impossible one; `RD-10·r2`'s class-based form disposes of both** |
| ⚠️ **`DV-5` has a second limb** | *"correct the stale-read boundary explanation so it refers to the ACTUAL SLOT-3 MECHANISM"* | ⛔ **the window is ABSENT from §4.3's residual-window table** and should be added there. ⛔ **And it must not be *"fixed"* by reordering readers and writer — `RC-5` is closed and the reverse order creates an invisible-write window** |
| ⚠️ **a verifiable bound** | *"do NOT change R-CONFLICT"* | ⭐ **§6 is BYTE-IDENTICAL today, and that is checkable rather than assertable** — worth keeping as an acceptance condition rather than an instruction |

⭐ **Every item above is a property or a propagation surface. ⛔ None is a mechanism, and none is remedy text.**

# 7 · ⚠️ Three defects in the AUTHORING HANDOFF as drafted — ⛔ **the handoff is an UNREGISTERED draft, and this note corrects the draft, not the registered commission**

> ⭐ **Precedent for reviewing an unregistered draft:** `C-6` records that it *"corrects an unregistered chat draft, not the registered commission"*, and `ccf6c9c7`'s §18 recorded five defects in the AMD6 commission **as drafted**. ⛔ **Same footing here: the registered grant is untouched and governs.**

## 7.1 🔴 **`HO-1` — the `DV-1` sequence in the handoff INVERTS a data dependency**

**The handoff draft states:**

```
final copy-side write
    ↓  🔴
construct/close SOURCE FINAL-STATE ENUMERATION
    ↓
verify durable copy against that comparison object
    ↓
writer / authority switch
```

⛔ **The plan already closes the enumeration BEFORE the final copy-side write, and the write DEPENDS on it** — §4.3 slot 3, verbatim:

```
(i)   MEASURE the source — after slot 2's append has landed
(ii)  VERIFY the measurement against the expected set     ← the enumeration is CLOSED here
(iii) bring the durable copy TO THAT STATE                ← the final copy-side write
(iv)  SWITCH P-1
(v)   write the SWITCH-OVER RECORD, carrying the enumeration
```

⭐ **Step `(iii)` takes the enumeration as its TARGET — *"bring the durable copy to **that state**"*.** ⇒ ⛔ **the copy cannot be brought to a state that has not yet been closed, so the handoff's order cannot be executed as written.**

| Consequence | Severity |
|---|---|
| ⛔ **the dependency is unsatisfiable** — `(iii)` has no target until `(ii)` closes | 🔴 **the sequence is not executable** |
| ⚠️ **a SECOND construction site for the enumeration** | 🔴 ⭐ **exactly the `DI-5` duplication shape: one object, two current definitions of where it is built** |
| ⚠️ **the closing instant moves later** | ⛔ **enlarges the recorded `measure → switch` residual window (§4.3), which AMD6 deliberately bounded and recorded** |
| ⚠️ **it weakens *"FIXED and RECORDED at slot 3"*** | ⛔ **that property is what STRUCTURALLY EXCLUDES `RD-3·a`'s first unsafe reading (the object absorbing a disputed write). Re-closing it later makes exclusion an ordering convention again** |
| 🔴 **it admits a self-satisfying reading** | ⭐ **Stated at exactly its strength: IF *"construct/close"* were read as deriving the object from the state produced by the write it is meant to validate, the check CANNOT FAIL.** ⚠️ **The enumeration describes the SOURCE, so the strict reading survives — but the draft does not say so, and a gate that cannot fail is the shape §4.4 already names: *"the restatement is honest and here it is SELF-SATISFYING"*** |

⇒ ⭐ **`DV-1` requires exactly ONE new sub-step, and no new construction act:**

```
(iii)    bring the durable copy to that state            [unchanged]
(iii-b)  ⭐ NEW — VERIFY the durable copy, all-or-nothing,
            against the ALREADY-CLOSED enumeration from (ii)
         FAIL → ⛔ STOP · record · escalate · NO authority transfer
(iv)     SWITCH P-1                                      [unchanged]
```

⚠️ **And the failure cost must be stated where §4.3 already states it: a stop at `(iii-b)` is still pre-authority-transfer, so nothing irreversible occurs — ⛔ but the slot-2 READER switches must be reverted.** ⭐ **A small *"undo"*, not a pure *"stop"* — the same cell as `3(ii)`, ⛔ not a new outcome class.**

## 7.2 ⚠️ **`HO-2` — *"the closed enumeration TOGETHER WITH the expected-delta model"* implies two comparison objects**

**The handoff requires the comparison to use the closed enumeration *"together with the governed expected-delta model already established by `C-4` / `DV-2`."*** ⛔ **There is only ONE object, and the declaration is an INPUT to it, not a co-comparand:**

```
SOURCE FINAL-STATE ENUMERATION  =  frozen manifest
                                +  the reconciled 1b delta
                                +  the DECLARED pre-switch Phase-5 records   ← the declaration enters HERE
```

⇒ ⭐ **the fix `DV-2` asks for lands in the enumeration's THIRD OPERAND, and nothing needs to be compared twice.** ⚠️ **Left as drafted, an implementer may build a second comparison object — and two objects that must agree is the defect `INV-R2` already refuses: *"keeping two defaults in agreement is the defect, not the fix."***

## 7.3 ⚠️ **`HO-3` — `DV-2`'s second limb is absent from the handoff, as it is from the grant**

**The handoff says the declaration MUST cover slots 1, `1b`, 2** ✅ **and *"do not create another declaration system"*** ✅. ⛔ **It does not carry the other half: §4.0 STILL LISTS *"the switch-over record"* among the records expected in the SOURCE delta, and `C-4` made that impossible** *(the record is written into the authoritative store, never the source)*. ⇒ **the repair ADDS the missing classes and RELABELS the impossible one.** ⭐ **`RD-10·r2`'s class-based form disposes of both at once.**

## 7.4 ⭐ What the handoff gets right, recorded so the note is not read as a rejection

✅ **the `DV-1` REQUIREMENT itself** — *"compare against the CLOSED `SOURCE FINAL-STATE ENUMERATION`"*, and *"Do NOT re-run an earlier Phase-4 comparison whose comparison object predates reconciliation, pre-switch Phase-5 evidence, and the final copy-side carry-across"*. ⭐ **That is the gap in the registered grant, correctly closed.**
✅ **the PHASE-ORDER CONSEQUENCE section** — it names the `DI-5`/`DI-1` failure mode explicitly and requires propagation into the summary. ⭐ **That is `TRAP 4` and the second-artifact control, both carried.**
✅ **`DV-6`'s laundering chain**, `DV-3`'s *"must NOT imply … first physical JSON record"*, `DV-7`'s *"every cited object must resolve"*, the DDD list, and ⛔ **`PROPOSED · ADDRESSED · NOT CLOSED` with no self-closure.**

## 7.5 ⚠️ **A claim about the assurance tooling that should not be carried forward**

**The handoff's closing remark states that the assurance tooling *"is now capable of detecting the exact class represented by `DV-7`."*** ⚠️ **Not as things stand — and the distinction is the reason `EKS-06` exists:**

| | |
|---|---|
| **What `S2` resolves** | `§x.y` → exactly one heading · `step N` → an enumeration defining `N` |
| **What `DV-7` is** | a dangling **`P5·<id>`** operator reference — ⛔ **outside `S2`'s implemented token register** |
| **Evidence** | ⭐ **the Phase-0 back-test recorded `AMD6 → quiet` on `S2`, and `DV-7` was in the artifact at that moment.** The row is **correct AS SCOPED** and stays so; the artifact was **not** free of `S2`'s class |
| ⇒ | ⭐ **the tooling detects the class only IF the register becomes DECLARATION-DRIVEN — which is `EKS-06`'s candidate requirement and is UNDISPOSED.** ⛔ **Believing the check covers `DV-7` today would be reliance on a check that does not fire — the FALSE-ASSURANCE risk the Phase-0 report's `D-4` verbatim statement exists to prevent** |

✅ ⭐ **The handoff's governing principle is nevertheless exactly right and should be preserved verbatim: the tool produces EVIDENCE · Architecture repairs the DESIGN · an independent reviewer JUDGES the repair.** ⛔ **No checker result may stand in for the review gate — the same rule `C-12` applies to the author's own four-layer trace.**

---

# 6 · Explicit non-actions and limitations

⛔ **This artifact did NOT:** register anything · create, modify or interpret any grant · author or propose any plan text · modify the migration plan or the AMD6 summary · name the amendment · decide author eligibility · decide `OPEN-M5`, `OPEN-M6` or `OPEN-M7` · reopen any closed finding · accept anything · execute anything · touch runtime code, `.gitignore` or `.gitattributes`.

⚠️ **Limitations:** the preparing process's identity is **self-declared and not attestable** · `DV-1`…`DV-7` are derived from the artifacts at `8307beca` and the repository, **not from any execution** · **`DV-1` requires a silent copy-side write failure to fire and `DV-2` requires the Phase-0 declaration to be written from §4.0's illustrative list** — both are properties of the specification as written, not observed events · ⭐ **the writing process owns the findings, so this artifact is the weakest link in its own chain: a finding-owner's account of what its findings require. It is offered as an additive note precisely because it cannot be more than that.**

**Traceability:** the independent AMD6 Architecture review by `claude-code-session:dd639043` *(§5.5 `DV-1`, §5.6 `DV-2`, §5.7 `DV-3`, §5.8 `DV-5`, §5.9 `DV-6`, §6.2 `DV-7`, §5.2 `DV-4`, §13 residual table, §14 verdict)* · AMD6 `8307beca` · AMD5 `7d3abc59` · AMD4 `0a2fa71d` · AMD3 `bb1708b7` · the four registered AMD6 commission grants *(structure read directly: `grantId` · `status` · `authority` · `humanActRef` · `scope` · `registeredBy`)* · artifact-class precedent `KOS-AIP-GOV-STATE-DURABILITY-OPEN-M3-DECISION-PREP.md` · **propagation surface measured, not assumed: `…-AMD6-SUMMARY.md` lines 123 (`DV-3`), 77 (`DV-6`), 198 (`DV-7`)** · `ES-005.4` · `INV-ATTR-2`/`G-2` · `G-2`/`R5a` · `R-34`/`P-2`.

**REVIEWER'S NOTE DELIVERED · STOPPING.** ⛔ **THIS NOTE REGISTERS, COMMISSIONS, DECIDES AND ACCEPTS NOTHING.** ⭐ **The commission is registered; the next actor is an ELIGIBLE ARCHITECTURE PROCESS, and it is not this one.** ⛔ **PHASE 5 IS PROHIBITED UNTIL `DV-1` IS REPAIRED. PHASE 3 MUST NOT BEGIN.**
