# Registration — Independent Verification #3 of ADR-AIP-04 Correction #2

**Registered by:** Governance, on the delivered PO/ARB `REGISTER` act · 2026-08-19
**Work item:** `KOS-AIP04-DISCOVERY-001` · **grant:** `G-KOS-AIP04-VERIFY3` (AUTHORIZED) · **assignment:** `S1-verification-aip04-correction2` (verification), `REGISTER` seq 13 · `HANDOFF` seq 14
**⛔ NOT STARTED.** State: `CREATED`. **Governance performed no verification and decided nothing.**

---

## 1 · Preconditions — checked against the record, one by one

| | Precondition | Result |
|---|---|---|
| **1** | Correction #2 assignment exists | ✅ `S4b-architecture-aip04-correction2`, seq 10–12 |
| **2** | Correction #2 is delivered | ✅ **in the artifact** — commit `3cbb915c`, the proposal's own closing line *"CORRECTION #2 DELIVERED · STOPPING."* ⚠️ **but not in the record**: the lane was still `ACTIVE` and had never handed off (see §2) |
| **3** | Correction #2 has a governed producing process | ✅ `claude-code-session:5e1dd9ee`, declared at seq 10 **and** disclosed in the proposal — the disclosure obligation Amendment 1 failed (`W-4`). Self-declared, **not attestable** |
| **4** | Verification #2 exists and is delivered | ✅ `acdc613f`, lane `HANDED_OFF`, verdict **RETURNED**, findings `W-1`…`W-6` |
| **5** | No Verification #3 assignment already exists | ✅ none — the four prior lanes are discovery, Verification #1, Verification #2, Correction #2 |
| **6** | No conflicting Verification #3 grant | ✅ none — the five prior grants are DISCOVERY, VERIFY, VERIFY-AMD1, VERIFY2, CORRECTION2 |
| **7** | Candidate verifier independent of the seven named roles | ⚠️ **CANNOT BE ESTABLISHED BY GOVERNANCE.** No candidate is named, and independence is **DECLARED, NOT ATTESTABLE** (`INV-ATTR-2`/`G-2`). **One of the seven exclusions has no identifier to exclude — see §4.** The bar is registered as a precondition **on the assignment**; it is not certified here |

**No duplicate was created** — precondition 5 and 6 were checked before writing anything.

## 2 · The delivery/record gap, recorded rather than repaired

Correction #2 finished its work and said so in the artifact, but **its lane never handed off** — it still held mutation ownership when this act arrived. **Governance did not back-date a delivery.** The handoff at seq 14 is performed **now**, by the current mutation owner, and its token says exactly that: *"the Correction #2 lane, whose commissioned deliverable is complete in the artifact although its lane never handed off."*

*This is the same shape as `W-4`'s original finding one lane later: the work is real; the record lags it. It is disclosed, not smoothed.*

## 3 · The independence bar, as registered

**Barred, from the estate's own self-declarations:**

| Process | Why barred |
|---|---|
| `claude-code-session:5e1dd9ee` | ADR-AIP-04 **discovery** + **Amendment 1** + **Correction #2** |
| `claude-code-session:1c8b041b` | ADR-AIP-04 **Verification #1** **and** the **Track-1 implementation**, the accepted implementation architecture, the reconciliation, the fit assessment, and the V-3 determination — **barred on two independent grounds** |
| `claude-code-session:2da45a86` | ADR-AIP-04 **Verification #2** |

## 4 · ⚠️ The exclusion that cannot be named — the material limitation of this registration

**One of the seven excluded roles has no process identifier anywhere in the estate.** The Track-1 independent verification report discloses its overlap **in role terms** — *"this process held the Governance capacity on this work item earlier today: it registered this very verification grant and assignment"* — and **declares no session identifier for itself.** Every other producer in this chain declares one; that report does not.

**Consequence, registered into the assignment:** the bar **cannot be enforced by process hash alone**. The candidate must self-check by **artifact authorship**: it must state whether it authored `docs/publicdigit/reviews/2026-08-18-KOS-CONTRACT-NEUTRALITY-001-track1-independent-verification.md` (`50d55d26`). **If it cannot answer, `SB-1` is not cleared** — and per the act, *"do not infer clearance."*

**Why this matters more than a bookkeeping nit:** `SB-1` is the one finding two verification passes have now declined to clear, both for the same reason — both verifiers held Track-1 authorship. This assignment exists largely to give `SB-1` its first genuine clearance opportunity. **A bar that cannot be checked cannot clear it.**

## 5 · What the verification must produce

**Six findings** `W-1`…`W-6` re-derived independently · the `SB-1` clearance attempt · the **self-corroboration check** (*"agreement with an unread source is not independent evidence when authorship/exposure cannot be attested"*) · the **DDD capability test** (*"do not use a role title as evidence that a capability exists"*) · **fifteen verdicts A–O**, then **READY FOR PO/ARB DECISION** or **RETURNED FOR MATERIAL CORRECTION**.

**The corrected thesis under test is the narrow one:** not *"the six roles do not exist"* but *"the six-role model is not currently established as governed architecture."* ⛔ **Non-existence may not be inferred from absence in `registry.yaml`, one capability map, one directory, or one search result.**

## 6 · Boundaries registered

⛔ decide `OQ-A`…`OQ-I` · decide ownership · create contexts, roles or agents · modify Track 1 · modify BC-7 · modify ADR-AIP-04 · supply replacement architecture · accept the discovery · close the work item · self-close · self-accept · modify source artifacts.
**A finding may state insufficiency only.**

## 7 · Not done

No verification performed · no verification started · ADR-AIP-04 not decided · the correction not modified · BC-7 untouched · Track 1 untouched · no lane closed · `SB-1` still **CONFLICTED**, carried forward untouched by the handoff.

**Next actor: Human PO/ARB → `START S1-verification-aip04-correction2`.**

**Traceability:** the PO/ARB `REGISTER` act 2026-08-19 · `G-KOS-AIP04-VERIFY3` · seq 13–14 · Correction #2 `3cbb915c` (assignment seq 10–12; grant `G-KOS-AIP04-CORRECTION2`) · Verification #2 `acdc613f` (`W-1`…`W-6`, RETURNED, §1 recusal) · Verification #1 · Amendment 1 `8008ee8a` · discovery `51910203` · Track-1 verification `50d55d26` (the undeclared producer) · `INV-ATTR-1` · `INV-ATTR-2` · `G-2` · `G-3` · `R-34`/`P-2` · `R8`

---

# ⬛ AMENDMENT A1 — `W-1` / verdict `G` re-scoped after the six-role adoption · 2026-08-19

**Registered by:** Governance, on the delivered PO/ARB `RECORD` act · recorded as **`G-KOS-AIP04-VERIFY3-AMD2`**.
*(A prior amendment, `-AMD1`, recorded the adoption decision as a decided input. Grants are append-only: the grant, AMD1 and AMD2 are read together.)*

## A1.1 · What changed, and what did not

> **The former thesis — *"the six-role model is not currently established as governed architecture"* — is SUPERSEDED by the adoption decision.**

**Superseded, not disproven.** The claim was true of the estate when Verification #2 examined it; a later decision changed the world, not the finding.

> ## ⛔ **DO NOT DELETE OR REWRITE THE HISTORICAL VERIFICATION RESULT.**

**Verification #2's findings, and every prior verification result, stand exactly as written.** Supersession governs **what is to be verified next**; it does not edit what was found before. *Recorded emphatically because the estate saw a governed record replaced rather than amended earlier today — the distinction between superseding a question and overwriting an answer is the whole of it.*

## A1.2 · `W-1` / verdict `G` — the re-scoped charge

> **"Verify the evidence quality, provenance, completeness, and historical classification surrounding the adopted six-role model. Do not re-open whether PO/ARB should adopt the six-role model."**

| ✅ The verifier may still verify | ⛔ The verifier must not |
|---|---|
| the six-class evidence distinction | re-decide adoption of the six-role model |
| completeness of the estate survey | reject the PO/ARB decision |
| the 925-line brainstorming document | create a different role model |
| whether that document carries **any independent governed authority** | |
| historical timing / provenance | |
| consistency between the adopted model and surviving artifacts | |

*The sharpest surviving question is the fourth: **whether the untracked document carries independent governed authority.** Adoption of the model did not adopt it, so its evidential standing is exactly as contestable as it was — and it is the document the self-corroboration check turns on.*

## A1.3 · Unchanged

The 925-line document **remains unadopted and remains under `OQ-I`** unless a separate governance act changes its disposition · AMD1's **non-equivalences remain binding** (role ≠ bounded context / capability / agent / platform service / organizational position; the adopted model is not proof a capability exists) · `W-2`, `W-3`, `W-4`, `W-5`, `W-6`, `SB-1` and its strengthened independence bar, the self-corroboration check, the DDD test, and verdicts **A–F** and **H–O** are untouched · `OQ-A`…`OQ-I` remain open.

## A1.4 · Not done

⛔ **The verification is NOT started by this act** — `S1-verification-aip04-correction2` remains `CREATED`. No historical result altered · ADR-AIP-04 not decided · no lane closed.

**Next actor: Human PO/ARB → `START S1-verification-aip04-correction2`.**
