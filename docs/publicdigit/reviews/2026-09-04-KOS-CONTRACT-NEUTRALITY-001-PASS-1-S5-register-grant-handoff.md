> ⛔ **CORRECTION (2026-09-04, appended after this document's initial write):** the `REGISTER`
> proposed in §2.1 below was appended to the record (as `S5-pass1-evidence-reconciliation`,
> seq 43) **before** this session discovered that a peer/Governance process had already
> performed the equivalent act under a different lane name,
> `S5-architecture-pass1-evidence-reconciliation` (seq 41–42, with grant amendment
> `G-KOS-CONTRACT-PASS1-RECONCILE-AMD1` already `AUTHORIZED` — see
> `2026-09-04-...-PASS-1-reattribution-determination.md`). The §2.3 `HANDOFF` planned below
> was **never executed** by this session (it would have been refused — mutation ownership
> had already left `S4`). The duplicate `REGISTER` was disclosed and voided by a `CANCEL`
> transition (seq 44), following this repo's own precedent for the identical failure mode
> (seq 26–28). **The canonical lane is `S5-architecture-pass1-evidence-reconciliation`,
> not the one named below.** This document's plan (§2) is preserved as a record of what was
> attempted and superseded — not rewritten, per `ES-004.3`.

# `KOS-CONTRACT-NEUTRALITY-001` — Pass-1 performer reassignment: REGISTER S5 · GRANT amendment · HANDOFF

**Work item:** `KOS-CONTRACT-NEUTRALITY-001` · **Date:** 2026-09-04
**Recorded by:** Governance — `claude-code-session:e8f324f1-25ea-4281-a95a-483401312e3e`
*(recording the PO/ARB's authorization; Governance does not authorize)*

> ⛔ **Intermediate state only. START has NOT been performed. Pass 1 has NOT begun.**
> This act attributes a new lane and relinquishes S4's mutation ownership. It does not
> activate anyone, does not resolve V-3, and does not change Pass 1's scope or bounds.

---

## 1 · The authorization (PO/ARB decision, quoted)

Selected option: **"New Pass-1-only lane (Recommended)"** — REGISTER a new lane
(`S5-pass1-evidence-reconciliation`) with predecessor `S4-architecture-v3-determination`,
HANDOFF to it from the current mutation owner, then START it naming this session as
performer under `G-KOS-CONTRACT-PASS1-RECONCILE`.

Followed by the PO/ARB's own words, confirming the design and the containment intended
around it (quoted verbatim):

> "Yes — this is the correct intermediate state, and I would proceed to START. The important
> thing is that Claude followed the separation we wanted: 1. REGISTER → S5 exists.
> 2. GRANT amendment → Pass 1 names S5. 3. HANDOFF → S4 relinquishes ownership. 4. No work
> yet → because START has not happened. [...] The START should mean only: Activate S5 for
> the already-authorized Pass-1 grant `G-KOS-CONTRACT-PASS1-RECONCILE`. It should not:
> resolve V-3; accept either V-3 determination; change the Pass-1 scope; create
> implementation authority; authorize contract correction; modify application/runtime code;
> retroactively change S4's history. [...] Proceed with the canonical START transition for
> S5. After START, re-run the authority resolver and stop if `authorized_to_act` is not
> TRUE. If it resolves TRUE, begin Pass 1 using the existing prepared direction without
> changing its scope."

This is the recorded human act. Governance records it; it does not originate it.

## 2 · What this act does

1. **REGISTER** a new session/lane `S5-pass1-evidence-reconciliation`, role `architecture`
   (same role class as its predecessor, for minimal deviation), predecessor
   `S4-architecture-v3-determination`. Execution context discloses the performing process
   self-declared as `claude-code-session:e8f324f1-25ea-4281-a95a-483401312e3e`
   (DECLARED, NOT ATTESTABLE — `INV-ATTR-2`/`G-2`), no prior participation on this work
   item, and states Pass 1's containment rule verbatim.
2. **GRANT amendment** `G-KOS-CONTRACT-PASS1-RECONCILE-AMD1` — narrows nothing about scope
   or bounds; changes only the performer premise: from "performed by the SAME lane:
   `S4-architecture-v3-determination`" to "performed by `S5-pass1-evidence-reconciliation`."
   `G-KOS-CONTRACT-V3-ARCH` and `AMD1` are untouched.
3. **HANDOFF** from `S4-architecture-v3-determination` (current mutation owner) to `S5`,
   carrying this document as its `tokenRef`.

## 3 · What this act deliberately does not do

No START (no `humanAct`, no activation). No V-3 ruling. No change to Pass 1's authorized
scope, containment rule, or "Does not authorize" list (`...-PASS-1-AUTHORIZATION.md` §3).
No modification to `S4`'s prior history — its transitions stand; only its *mutation
ownership* moves to `S5`, which is what a HANDOFF is for. No Pass-1 work product.

## 4 · Verification required immediately after this act

Re-run `php .claude/scripts/session-bootstrap.php --work-item=KOS-CONTRACT-NEUTRALITY-001
--session=S5-pass1-evidence-reconciliation --json` (and the process-label form). Expected:
`verdict: RESOLVED`, `attribution: MATCH`, **`gates.authorized_to_act: false`** — because no
START has occurred. Any other result is a discrepancy to report, not to paper over.

**Traceability:** PO/ARB decision 2026-09-04 (this document, §1) · prior authorization
`2026-08-24-KOS-CONTRACT-NEUTRALITY-001-PASS-1-AUTHORIZATION.md`
(`G-KOS-CONTRACT-PASS1-RECONCILE`) · fresh-performer STOP record
`2026-09-04-KOS-CONTRACT-NEUTRALITY-001-PASS-1-fresh-performer-registration-attempt-STOP.md`
· `EKS-07` · `INV-ATTR-2` · `R8`.
