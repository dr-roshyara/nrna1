# `AST-019` — **AUTHORIZATION DECISION** · ✅ **AUTHORIZED for future use**

**Asset:** `AST-019` / `ActivateCommissionedFreshSession` · **Work item:** `KOS-OPERATING-MODEL-001-AMENDMENT-001`
**Date:** 2026-08-24 · **Recorded by:** Governance Engineer — `claude-code-session:5928b9f9-b4d5-46e9-8c71-c295dace18f8` *(recording the PO/ARB's act; Governance does not authorize)*
**Placement derived:** `php scripts/doc-placement.php --scope=product-specific --domain=knowledgeos` → `docs/knowledgeos` (exit 0)

> **This is the SECOND of two separate acts.** Adoption is recorded in `2026-08-24-AST-019-ADOPTION-DECISION.md`. `§38` forbids collapsing them; each rests on its own reasoning.

---

## 1 · The decision (verbatim)

> **"1 and 2"** … **"both"** — *"2. Authorize for future use"*.
> — PO/ARB, in-session, 2026-08-24

**Authorization is unconditioned.** Governance offered the option of authorizing *with a recorded condition* (the `AST-016` qualification-with-conditions shape) and the PO/ARB authorized plainly. **No condition is recorded, and none is implied.**

## 2 · What is now permitted

`AST-019` **may be used** going forward: a fresh session may bind its own discovered runtime identity to an **already-commissioned** responsibility via `activate`, and `check` may be used as its read-only preflight.

**Its internal constraints are unchanged by authorization and remain binding:** the role comes from the authoritative commission and never from a prompt (`prompt ≠ commission → MISMATCH → STOP`) · it never self-chooses role, scope, work item, or authority · it writes **only** through `AST-015` · it **never** writes a `CONTINUATION` (`GO-21`) · a `STOPPED` item stays an honest `Inv E` blocker · the human `START` gate (`G-3`) is untouched.

## 3 · What authorization does **not** create

**Authorization is not wiring.** No hook, no `SESSION_START` path, no automatic invocation is created or implied. `runtime_moments` stays `[ON_DEMAND]`. This mirrors the discipline applied to `AST-016`, whose adoption expressly did not wire it into any startup path.

It also does not make `AST-019` the *only* activation route: `AST-018 appoint` remains the canonical **appointment** mechanism, and the `ASD-001` lesson stands — appointment goes through the engine, never hand-composed appends.

## 4 · ⚠️ The risk this authorization knowingly accepts — `RV-F1`

**Stated plainly, because authorization is the act that makes it live.**

Adoption judged the artifact; **authorization permits use** — and `RV-F1` is a **use-time** risk. It cannot fire while the capability sits unauthorized; from today it can.

- **Mechanism.** The three `AST-015` appends have **no transaction boundary**. Ownership is derived during *analysis*; if it moves before the write completes, `REGISTER` lands with a stale `predecessor`, the `HANDOFF` is refused, and an **orphan assignment** remains.
- **Consequence.** Permanent. `ASD-001` established there is **no un-`REGISTER`**: the log is append-only, role is immutable (`R8`), and no rollback edge exists (`R1`). Resolution requires an explicit Governance workaround.
- **Probability.** Low. It needs a genuine concurrent ownership mutation; the verifier had to force it deliberately with `rv-race.php`.
- **Not a regression — a strict improvement.** Pre-repair the identical orphan occurred **deterministically on every live-owner activation**. Post-repair the fail-closed behaviour is **tested rather than asserted**: no false activation, no mis-attributed handoff, and honest reporting (`transitionWritten: true`, `whoMustActNext: governance`).

**`RV-F1` was correctly classified non-blocking and it did not block this decision.** It is recorded here because a future reader must be able to see **what was authorized and with what known exposure** — not to qualify the decision.

**⚠️ It is also UNOWNED.** The PO/ARB adopted and authorized without commissioning follow-up work (option 3 was available and not taken). So `RV-F1` is now **a live, recorded, unassigned risk**, as is `RV-F2` (the committed suite still does not pin *partial write with a live owner*, so that behaviour is unprotected against future regression). Governance recommends — and does not create — an **Architecture** item for `RV-F1` (atomicity / transaction boundary, adjacent to but distinct from `F-5`) and an **Engineering** item for `RV-F2` (extend `GO-27`/`GO-28`/`GO-30` to `armedOwnerFixture()`). **Either requires a human act to exist.**

## 5 · Still open, and untouched by this act

`RV-F1` · `RV-F2` · `F-5` (held for a separate Architecture decision) · `ASD-001` remedy · `O-4` · `O-6` · `Q-1` · `Q-2` · `REVIEW_INDEPENDENCE_POLICY §22`.

## 6 · State after both acts

```
KOS-OPERATING-MODEL-001    L1+L2+L3   IMPLEMENTED · VERIFIED · REVIEWED · ADOPTED · AUTHORIZED
AST-019 / AMENDMENT-001               IMPLEMENTED · VERIFIED · ADOPTED · AUTHORIZED
                                      (verified via REPAIR-001 re-verification, PASS WITH FINDINGS;
                                       RV-F1 / RV-F2 / F-5 carried, open, and uncommissioned)
```

**Every asset on `CMP-004`'s activation path is now adopted.** The four states were kept distinct at every step and were never collapsed.

**Traceability:** PO/ARB act 2026-08-24 (§1) · adoption record `2026-08-24-AST-019-ADOPTION-DECISION.md` · decision preparation `…-AST-019-ADOPTION-DECISION-PREPARATION.md` · re-verification `…-REPAIR-001-INDEPENDENT-RE-VERIFICATION.md` §14 (`RV-F1`/`RV-F2`/`RV-O3`) · `ASD-001` (no un-`REGISTER`) · repair `d8a5ee93` · `AST-016` adoption (wiring discipline) · `§38` · `G-3` · `R1`/`R8` · `R-34`/`EP-02`
