# Acceptance of the Governance Decision Report — Protocol Constraints, `P-2H`, and Programme Status

**Type:** Governance registration (Session 2) · **Date:** 2026-08-15 · **Basis:** PO/ARB response to the Governance Decision Report on publication, time semantics and remaining boundaries
**⛔ Registration only. No new analysis, no Architecture prompt, no implementation. Governance now WAITS for the `EM-OPEN-023` decision.**

---

## 1 · What was accepted

The PO/ARB **agrees with the report and would not change its fundamental direction**, endorsing specifically: opportunity identity over schedule equality · the published-schedule / actual-event separation · refusal-is-not-termination · the refusal to invent publication semantics · time semantics staying blocked · candidate semantics not over-read. **The single point named as strongest is not a conclusion but a restraint:** *"The strongest result is not a choice of publication semantics. It is the refusal to invent that choice."*

## 2 · Correction accepted — `D-1` rewording

**The PO's phrasing is better than mine and is adopted verbatim in substance:**

> *"A schedule edit before the business moment defined as publication does not create a correction under `EM-VOC-005`, and therefore does not create supersession under that rule."*

**What my wording risked.** I wrote that such an edit *"creates no voting opportunity and produces no supersession"* — which can be read as *pre-publication activity is never recorded*. **That would have quietly pre-empted N-5** (*is preparation a material event?*), a question I had opened one section earlier. **`EM-GOV-005` is not narrowed by `D-1`.** Manifesto updated.

## 3 · `P-2H` — the two-histories separation, elevated

At PO direction, *"opportunity lifecycle ≠ progression-decision history"* is recorded as a **named core principle** (Manifesto §4b) with the PO's worked example preserved.

**Its status is stated exactly and will not drift:** `P-2H` **names an already-adopted reading** — the confirmed reading of `EM-VOC-004` plus `EM-GOV-005` — **elevated for prominence. It is not a new rule and adds no policy.** Naming it is what stops it being rediscovered, and then re-decided, downstream.

**Binding consequence:** a refusal must never be represented as a termination, and **a successful request must never erase the refusals that preceded it.** Across a correction, the successor opportunity's first request is evaluated **anew** (`EM-VOT-005`).

> *"The Chief has the authority to request progression. The Chief does not have the authority to make progression valid."*

## 4 · `F-PROTO-1` — the refusal-recording gap, registered with its provenance intact

**Registered as an ARCHITECTURE OBSERVATION endorsed by the PO — explicitly NOT as a Governance-verified fact.** Governance did not inspect the code and does not claim to have. **Independent verification is Session 1's act, if and when commissioned; until then the finding stands as reported, not as proven.** *(This separation is the same discipline applied to every prior finding in this programme; the PO's endorsement raises its authority, not its evidentiary status.)*

**The observation:** a guard rejects a progression request → exception or metric → **no durable protocol event.** A capped or rotating history is likewise insufficient for an authoritative record. **PO assessment registered:** *"That fails the adopted recording rule."*

**Seven required properties, binding on Architecture** — durable · append-only in meaning · complete for required material events · opportunity-bound · resistant to silent truncation · able to record a refusal **before or independently of** any state-transition success · able to distinguish lifecycle from progression-decision events. **The storage mechanism is expressly NOT prescribed.**

**Why property 6 carries the most weight:** if refusals are only recordable as a by-product of a successful transition, then **the very events the anti-circumvention rules exist to preserve are the ones the system cannot record** — the refused attempts. That inverts `EM-GOV-005`.

## 5 · Programme status, registered as the PO classified it

```
GOVERNANCE      🟢 foundation established
                🔴 open on PO/ARB: EM-OPEN-023 · EM-OPEN-024 (entered-time semantics)
                   · EM-OPEN-025 (nomination completion / pending candidacy)
                🟡 awaiting one-line ratification: the EM-VOC-004 wording correction
                ⛔ implementation NOT authorized
ARCHITECTURE    ⏸️ WAIT — do not finalize the technical model; no new Architecture prompt
                   is to be written before EM-OPEN-023 is ruled
SESSION 3       🛑 STOPPED
SESSION 1       may continue independent verification — ⚠️ and MUST NOT treat Session 3's
                   uncommitted / unauthorized implementation as authority
```

**The PO's sequence, registered:** ① publication ruled → ② entered-time semantics ruled → ③ nomination/candidacy resolved → ④ `EM-VOC-004` wording ratified → ⑤ Architecture finalizes representation and ADRs → ⑥ **only then** an implementation grant to Session 3 → ⑦ Session 1 verifies independently.

**Registered rationale for the ordering:** `EM-OPEN-023` comes first because it determines **when a voting opportunity comes into existence** — and Governance has already established that this question and the publication question are **inseparable**.

## 6 · Governance's own next act

**None until the PO rules `EM-OPEN-023`.** Governance is not writing an Architecture prompt, not producing further analysis, and not narrowing the options in the meantime.

**Preserved principles** *(the PO's five, restated for the record)*: opportunity identity, not schedule equality · published schedule, not actual event time, governs eligibility · refusal is not termination · lifecycle history and decision history remain separate · **open business questions remain open rather than being resolved by code.**

**Traceability.** Governance Decision Report (`94f000c5`) → PO/ARB acceptance (§1) → `D-1` reworded · `P-2H` recorded (Manifesto §4b) · `F-PROTO-1` + seven properties (Manifesto §3) → this registration. Antecedents: `EM-VOC-004` (confirmed reading) · `EM-VOC-005` · `EM-VOT-005` · `EM-GOV-005` · `EM-OPEN-023/024/025`.

**STOP — Governance waits on `EM-OPEN-023`.**
