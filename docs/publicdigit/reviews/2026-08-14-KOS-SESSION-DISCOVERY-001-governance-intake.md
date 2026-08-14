# KOS-SESSION-DISCOVERY-001 — Governance Intake / Triage

**Type:** Governance intake (Session 2) · **Date:** 2026-08-14 · **Commissioning act:** the PO-delivered intake commission (PA-drafted, delivered "hello session 2" 2026-08-14 — the established commissioning pattern), registered by this artifact; **A-3 note:** the commission authorizes intake + conditional registration; it performs no START and creates no implementation authority.
**⛔ Nothing implemented · `workflow-state.php` untouched · no hooks/auto-activation · no Session 5 · Session 4 NOT started · KOS-OQ-001/Increment 1 not reopened.**

---

## A · Legitimate new governed work item? — YES

1. **Recorded demand:** the Session-Assignment-Discovery capability was proposed during the OQ and explicitly deferred as "a future platform capability, not during OQ." The OQ is closed (terminal G-1, `f16ca763`) — the bar is lifted.
2. **Evidence base is concrete, not speculative:** the closed OQ's own findings define the capability's constraints — E-11/E-14 (sessions already perform discovery *manually* and correctly refuse on machine truth), O-1 (ACTIVE ⇒ mutation owner — "active but read-only" inexpressible), O-2 (exact-string authorization matching), E-15 (no work-item CLOSE vocabulary), plus the operational cost of four manually separated terminals.
3. **Consistency with the qualification ruling:** mechanism *evolution* requires "separate future analysis and authorization" — **this intake is that separate analysis path**, correctly begun as a NEW work item rather than an extension of the closed OQ.

## B · Governance boundary

1. **Read-side only**, over the authoritative workflow record. The write-side G-3 invariant is untouched: `HANDOFF + human START → ACTIVE`.
2. **The first-class invariant (from the accepted rule discussion + Session 4's formulation, adopted into this boundary):** *discovery ≠ activation ≠ authorization ≠ START ≠ ownership.* **No discovery operation may perform a workflow transition, create a grant, create ownership, or infer authority from prose.**
3. **Safety semantics binding on any design:** `ACTIVE → may operate · CREATED / HANDED_OFF / CANCELLED / STOPPED / COMPLETED → STOP`. Terminal/process/TTY identity never creates or strengthens authority (an execution host, never an authority source).
4. **Evidence-informed constraints:** under O-2, authorization resolution must surface the grant's verbatim scope rather than paraphrase-match; under E-15 and the grant-lifecycle gap, discovery must never *interpret* incomplete vocabulary as authority — UNKNOWN is a first-class answer.
5. **The four canonical roles stand (A-1/D-3). No Session 5.**
6. **Stage deliverable:** an architecture design boundary, PROPOSED, for human approval — not code.

## C · Architecture genuinely required? — YES, demonstrated (not assumed)

| Test | Finding |
|---|---|
| Existing coverage? | **NONE.** `workflow-state.php identity` requires the caller to already know `<workItem>` and `--session` — it cannot answer "which work items are open / which assignment is mine." `inject-context.sh` (CMP-002's session-start surface) contains no workflow-assignment concept — verified by inspection |
| New architectural component? | **YES** — a read-side discovery capability that does not exist; its **placement is a genuine ownership question** (extend CMP-002 session-continuity? CMP-004 workflow-engine? an asset under which?) |
| Unresolved design semantics with safety consequences? | **YES** — the edge cases below are decisions, not details |
| Contrast with the KOS-OQ-001 triage | there, an existing pattern covered the mechanics and Architecture was honestly cancelled; **here no pattern exists** — the asymmetry is the proof this is not manufactured |

## D · The architectural question handed to Session 4 *(design, not implementation)*

> **Design the read-side Session-Assignment-Discovery capability over the existing workflow record, as a PROPOSED boundary for human approval, without modifying `workflow-state.php`:** component placement and ownership (CMP-002 vs CMP-004 vs other, by evidence) · resolution semantics for every edge case — **multiple work items in one worktree · zero matching assignments · multiple matching assignments · stale assignments · CANCELLED/COMPLETED assignments · absent or corrupt records** (each with STOP-safe semantics) · authorization/scope surfacing under exact-string matching (O-2) · behavior when grant lifecycle/closure vocabulary is incomplete (E-15) · how the read-side G-3 formulation is enforced structurally (no transition, no authority, no ownership — under O-1's constraint that the mechanism itself cannot express "active but read-only"). **Where a design choice would require mechanism change, record it as a dependency requiring separate authorization — do not design the mechanism change.**

## E · Human decision/approval points

① **S4 START act** (G-3 — the handoff below is only half) · ② **approval of S4's design boundary** · ③ **the implementation authorization** — which must state its own freeze/evolution reading if the design touches the mechanism (the qualification ruling's evolution clause) · ④ S3 START · ⑤ verification START · ⑥ qualification/closure per the lifecycle.

## F · Next role — ARCHITECTURE (Session 4)

Registered accordingly: **work item `KOS-SESSION-DISCOVERY-001` created via the mechanism** (four canonical roles) · **S4 architecture assignment REGISTERED** · **architecture-scoped grant registered at assignment time — applying the E-14 process lesson** (role authorization pre-established at registration, so the verification-gate gap does not recur) · **HANDOFF recorded (token = this intake)**. **START not performed — it is the PO's act.**

## G · Explicitly outside scope

Implementation of discovery · any `workflow-state.php` change · hooks, automatic session activation, automatic role switching · Session 5 or any new role · Increment-2 enforcement · remediation of O-1/O-2/L-1/E-15 (separate future items — discovery must *work within* them, not fix them) · the role-records implementation (separately ruled, unauthorized) · Election work · reopening KOS-OQ-001 or Increment 1.

**Traceability:** the intake commission (this artifact §header) · Session 4's read-side-G-3 formulation and edge-case list (its own stopped response, PA-endorsed) · OQ evidence O-1/O-2/E-9/E-10/E-11/E-14/E-15 · qualification ruling (evolution clause) · `workflow-state.php:22,354` (identity's precondition) · `inject-context.sh` inspection · A-1/D-3 (canonical roles) · A-3 · G-3 · E-14 lesson.

---

## H · S4 START ACT — performative, REGISTERED VERBATIM (2026-08-14)

> **"register the session 4 start act"** — *PO, 2026-08-14, issued directly in the Governance session's record.*

**A-3 verification:** terse but unambiguous, imperative, in the PO's own words, in this stream's conversation record — **a performed human act, durably preserved here** (unlike the preceding message, which only *asserted* an act performed elsewhere and was correctly held). Registered as the human START act for `S4-architecture-discovery`; G-3 conjunction complete (intake handoff seq 2 + this act).

**Scope carried (unchanged):** grant `G-KOS-DISC-ARCH` — architecture design only, per intake §D/§G. **Provenance treatment accompanying this start (per the commissioning context and Session 4's own adopted stance):** `cee1ee6b` is **prior/untrusted architectural input**, not governed S4 output — the now-ACTIVE assignment reviews/revalidates it rather than inheriting it. *(The Option-② disposition ruling as a separate signed text was never received in this stream; this start under registration-first sequencing is consistent with and operationally equivalent to it — recorded as the operative reading, not as a manufactured ruling.)*

**Formal confirmation received same day (registered verbatim; NO second START performed — the seq-3 transition stands):**

> *"hereby authorize and start the S4-architecture-discovery session for KOS-SESSION-DISCOVERY-001, limited to the registered architecture-design scope."* — PO, 2026-08-14.

Same act, full form: it confirms the seq-3 START and pins the scope limit in the act's own words ("limited to the registered architecture-design scope" = `G-KOS-DISC-ARCH`, design-only). Both texts — the terse imperative and the formal confirmation — are now the act's complete durable record.

### H.1 · RATIFICATION OF THE seq-3 START (signed performative PO/ARB ruling, 2026-08-14 — registered verbatim)

> *"I ratify the seq-3 START of S4-architecture-discovery on the basis of my formal act of 2026-08-14 ('hereby authorize and start…', intake §H, `2b125a7a`); the provenance defect stands on record; no history is rewritten."*

**Registration effects, exactly the ruling's terms:** the seq-3 START transition is **RATIFIED** — its acknowledged human authority is the formal act durably recorded in §H (*"hereby authorize and start the S4-architecture-discovery session … limited to the registered architecture-design scope"*), not the terse registrar-instruction its `humanAct` field cites. **The provenance defect (A-3 audit classification B: instruction-conflated-with-act, Governance's own error) STANDS on record as evidence** — for the audit trail, and as motivation evidence for the discovery capability's provenance-visibility requirement. **No history is rewritten:** the seq-3 record, the §H registration, and the audit all remain verbatim; this ratification annotates, it does not replace. **Consequence: `S4-architecture-discovery` is legitimately ACTIVE** under ratified authority, within the design-only scope of `G-KOS-DISC-ARCH`, with `cee1ee6b` as prior/untrusted input to review and revalidate.
