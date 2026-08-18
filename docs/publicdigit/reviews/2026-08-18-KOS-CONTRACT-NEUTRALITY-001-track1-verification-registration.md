# Registration — Independent Verification of Track 1 (PHP Adapter / PHP L3/L4/L5)

**Registered by:** Governance · 2026-08-18 · **Work item:** `KOS-CONTRACT-NEUTRALITY-001`
**⚠ This is a REGISTRATION. No verification was performed, no finding was created, nothing was accepted, and `START` has NOT occurred.**

## 1 · Preconditions — checked against the workflow record, not against prose

| # | Precondition | State | Evidence |
|---|---|---|---|
| **1** | Track-1 implementation assignment is **DELIVERED** | ✅ | Delivery `4c6c1dac`; delivery record `2026-08-18-…-track1-delivery.md` §8 ("STOPPING… next actor: Independent Verification"); governance registration `2abbf79f`. **Note recorded precisely:** the state machine has **no `DELIVERED` state** — `S3-implementation-track1-php-adapter` folds to `ACTIVE` and is still the mutation owner. *Delivery is evidenced by the registered artifact; it is not a lifecycle state.* |
| **2** | Track-1 delivery artifact exists | ✅ | `docs/publicdigit/reviews/2026-08-18-KOS-CONTRACT-NEUTRALITY-001-track1-delivery.md` + `…-track1-delivery-registration.md` |
| **3** | **No** independent-verification assignment already exists for this delivery | ✅ | Fold of 34 transitions: the last `REGISTER` of a `verification`-role session is `S1-verification-stage2-breadth` (seq 17), whose predecessor chain ends at the **Stage-2** delivery. **No session carries predecessor `S3-implementation-track1-php-adapter`.** |
| **4** | **No** conflicting verification grant exists | ✅ | The three verification grants on file — `G-KOS-CONTRACT-STAGE1-VERIFY`, `G-KOS-CONTRACT-STAGE2-VERIFY`, `G-KOS-CONTRACT-STAGE2-BREADTH` — are all scoped to **earlier deliveries** (the PHP reference, the Python Stage-2 evidence, the defect-surface breadth). **None reaches the Track-1 PHP adapter / L3 / L4 / L5 implementation.** |
| **5** | The verifier will be a **separate process** from the implementer | ⚠️ **DECLARED, NOT ATTESTABLE** (`INV-ATTR-2`) | Carried in the assignment `executionContext` as a binding condition + a mandatory `D-a`–`D-d` disclosure. **Governance can register the obligation; it cannot attest the separation.** Per `INV-ATTR-1`, no precondition reads process identity. |
| **6** | `R-34` / `P-2` applies | ✅ | `R-34`: engineering never accepts its own work; **implementation→verification of the same work is prohibited.** `P-2`: the four-part disclosure `D-a`–`D-d` is mandatory in the verification artifact. |

**No precondition failed. No duplicate grant and no duplicate assignment were created.**

## 2 · What was registered

| # | Act | Identifier |
|---|---|---|
| **1** | Independent Verification **grant** | **`G-KOS-CONTRACT-TRACK1-VERIFY`** — status `AUTHORIZED`, authority PO/ARB, registered by Governance |
| **2** | Independent Verification **assignment** (session) | **`S1-verification-track1-php-adapter`** — role `verification` |
| **3** | **Execution context** carrying the separation rule | on the `REGISTER` transition (Inv B) |
| **4** | **Predecessor** | `S3-implementation-track1-php-adapter` |
| **5** | **Lifecycle `HANDOFF`** — implementation → verification | token = the PO/ARB verification commission; `tokenRef` = **this document** |

**Why the `HANDOFF` was required:** the contract makes `START` a **conjunction** (Inv F / G-3) — the predecessor's recorded handoff **and** a recorded human act. Without the handoff a later human `START` would be **refused**. The handoff is therefore a **lifecycle prerequisite recorded by Governance, not verification work**, and it transfers nothing else: `START` remains the human's act and has **not** been performed.

## 3 · Verification scope, as commissioned by the PO/ARB

**Purpose — assurance, not redesign:** independent assurance that the delivered Track 1 realizes the accepted architecture, preserves the **L3 → L4 → L5** boundary, implements the accepted semantic decisions, respects the **PHP-specific L4/L5 bounded exception**, stays inside authorized scope, and **honestly represents its conformance state.**

**The verifier must independently:** (1) **re-derive Gate 3** — L4/L5 must run from L3 facts with **no** PHP source, tokens, AST, parser objects or provenance; (2) re-run the relevant Track-1 suites; (3) **challenge the reported extractor corrections** — heredoc interpolation, nowdoc behaviour, method-body brace handling; (4) verify the accepted semantics **case by case** — enum = unit · trait = unit · anonymous = unit · interface = excluded · nullsafe = edge · the 13.3 qualifier rules · single-file scope · declaration-path anonymous identity; (5) verify the dual-run result — **10/10 existing units equal**, the `trait-user.php` divergence being the **expected `OQ-3` consequence**; (6) verify **no unauthorized artifact** was changed; (7) verify **no expected evidence** was generated or treated as an implementation-derived oracle; (8) verify the delivery claims — **55 tests / 105 assertions**, no relevant regressions, production changes limited to authorized Track-1 scope.

**Separate verdicts are required** for: **A** architecture conformance · **B** L3 semantic fidelity · **C** B3 extraction correctness · **D** L4 correctness · **E** L5 correctness · **F** test-claim correctness · **G** knowledge/conformance integrity · **H** scope discipline. **The categories must not be collapsed.** Each verdict is `PASS` / `PASS WITH NOTES` / `FAIL`.

**MAY:** inspect code, tests, workflow records and delivery artifacts · execute read-only tests · create temporary throwaway probes · independently re-derive claims · produce findings · issue the verdicts.

**MAY NOT:** modify Track 1 · repair defects · modify expected evidence · modify fixtures · change the contract · change Decisions **13.3** or **13.5** · redesign Architecture D · create a replacement implementation · retire the legacy calculator · **accept** the implementation · **close** Track 1.

> **The finding boundary, registered explicitly:** a finding **may** state *"the implementation is insufficient because…"*. It **may not** supply the replacement design. **Verification identifies insufficiency; Architecture supplies architecture.**

**Two containment rules carried from the delivery, so the verifier cannot be pushed into an unauthorized act:**
- ⛔ **`G-KOS-CONTRACT-ARTIFACT-UPDATE` remains UNEXERCISED.** The **absence** of declared expected evidence is **not** a verification failure at this stage — it is a **governed deferral**. The verifier assesses *readiness for* the future conformance layer, and **produces none.**
- ⛔ **The legacy PHP calculator is NOT the specification** (Decision 1). A divergence from it is **not** by itself a defect; the verifier must determine which path diverges **from the accepted contract**.

## 4 · Independence — the condition, and its honest limit

The assignment context states: **Independent Verification · the producer is barred · `R-34`/`P-2` applies.** The verifier **must** disclose prior participation or exposure in the four-part `P-2` form (**`D-a`** the overlap · **`D-b`** the prior engineering act by SHA/path · **`D-c`** `asserted` vs `attested` · **`D-d`** what it checked that the producer could not check itself). **If independence cannot be established: STOP and report the limitation** — do not verify and disclose afterwards.

**Honest limit:** per `INV-ATTR-2` this separation is **self-declared and not attestable**, and per `INV-ATTR-1` no gate in `AST-015` reads process identity. **Governance registers the duty; it does not and cannot enforce it mechanically.**

## 5 · Governance boundary of this act

⛔ **No verification performed · no `START` · no implementation modified · no expected evidence modified · no fixture modified · no finding created · Track 1 not accepted · the work item not closed · the implementation session not `COMPLETE`d** (closure follows verification, per the seq-14/15/16 precedent).

**Next actor: the Human PO/ARB — `START` the independent verification assignment.** Only after `START` may a **fresh** Independent Verification Engineer perform the verification.

**Traceability:** grant `G-KOS-CONTRACT-TRACK1-VERIFY` · assignment `S1-verification-track1-php-adapter` · predecessor `S3-implementation-track1-php-adapter` (seq 32/33/34) · delivery `4c6c1dac` · delivery registration `2abbf79f` · `G-KOS-CONTRACT-IMPL-TRACK1` + `AMD1` · accepted implementation architecture `adc5c8e8` (accepted `c6c4f984`) · Decisions 1 · 13.1 · 13.3 · 13.5 · 13.7 · `OQ-1` · `OQ-2` · `OQ-3` · `OPEN-1` · `O-EKS-1` · `G-KOS-CONTRACT-ARTIFACT-UPDATE` (AUTHORIZED, UNEXERCISED) · `R-34` · `P-2` (`D-a`–`D-d`) · `INV-ATTR-1`/`INV-ATTR-2` · `AST-015` (`.claude/scripts/workflow-state.php`, Inv B/C/D/F, G-2/G-3).
