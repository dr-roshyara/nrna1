# `KOS-CONTRACT-NEUTRALITY-001` — Deliverable E retired: neutrality proceeds under architectural framing

**Work item:** `KOS-CONTRACT-NEUTRALITY-001` · **Date:** 2026-09-04
**Recorded by:** Governance — `claude-code-session:e8f324f1-25ea-4281-a95a-483401312e3e`
*(recording the PO/ARB's decision; Governance does not decide)*

> ✅ **DECIDED.** The conflict the Pass-1 handoff reported (Decision 2) is resolved, not left
> open: current neutrality work proceeds under the **architectural-neutrality framing**
> already established by the 2026-08-18 language-scope decision. **Deliverable E is
> retired/reworded accordingly. Python is not part of the current implementation/conformance
> obligation.**

---

## 1 · The decision (verbatim)

> *"The current neutrality work proceeds under the architectural-neutrality framing.
> Deliverable E is retired/reworded accordingly. Python is not part of the current
> implementation/conformance obligation. Any future empirical Python conformance track
> requires a separately authorized scope decision."*

## 2 · What this resolves

The commission (`2026-08-16-KOS-CONTRACT-NEUTRALITY-001-commission.md`, re-registered/
framing-amended 2026-08-24) listed **Deliverable E — "Python conformance plan (only after
the contract is precise)"** as live. The `2026-08-18-KOS-CONTRACT-NEUTRALITY-001-language-scope-registration.md`
decision — dated *before* the 2026-08-24 re-registration — had already put Python **out of
current implementation scope** and reframed neutrality as an **architectural claim**, not an
empirical cross-language one. Nothing on record reconciled the two until now. **This act is
the reconciliation:** the commission's deliverable list is brought into line with the
already-standing scope decision, rather than the scope decision being reopened.

## 3 · What changes, and what does not

**Changes:** Deliverable E's obligation is retired as originally worded (a Python
conformance plan is no longer a pending deliverable of this commission). The commission's
remaining deliverables (A–D, F) are unaffected in substance; Deliverable F (neutrality
verdict) may now be answered on architectural grounds alone, once Deliverables B/C (V-3) and
D (fixture/evidence sufficiency) are addressed — it is **not** thereby answered by this act.

**Does not change:** the 2026-08-18 language-scope decision itself (this act applies it,
does not amend it) · Stage-2 Python's independently-verified `FAIL` finding (stands as
historical record of that scoped experiment, not retroactively erased) · `G-KOS-CONTRACT-ARTIFACT-UPDATE`
(remains `AUTHORIZED`/unexercised, independent of Python) · any code, fixture, or contract
file (none touched) · lane `S5` (remains `ACTIVE`, not closed by this act).

## 4 · Forward condition, made explicit

**Any future empirical Python conformance track requires a separately authorized scope
decision.** This act does not foreclose Python permanently — it removes it from the
*current* commission's obligations and requires a fresh, explicit authorization (reopening
or superseding the 2026-08-18 language-scope decision) before any Python implementation or
conformance work is commissioned again.

## 5 · Effect on the Pass-1 remaining-gap statement

Of the four gaps `2026-09-04-...-PASS-1-evidence-determination.md` §4 identified, gap 3
("Deliverable E's premise conflicts with the language-scope decision... needs a governance
disposition, not a silent pick") is **closed by this act**. Gaps 1 (V-3), 2 (breadth-review
closure linkage), and 4 (no fixture/evidence covers the 12 breadth-found divergences or
Track-1's `V-1`/`V-2`/`V-3`/`V-6`) remain open; gap 1 has a separate, narrower authorization
(`G-KOS-CONTRACT-V3-TARGETED-CONTINUATION`, this same date) covering only `D-1`/`D-4`/`D-5`.

**Traceability:** Pass-1 handoff `2026-09-04-...-PASS-1-PO-ARB-escalation.md` (Decision 2) ·
Pass-1 evidence determination `2026-09-04-...-PASS-1-evidence-determination.md` ·
language-scope decision `2026-08-18-KOS-CONTRACT-NEUTRALITY-001-language-scope-registration.md`
· commission `2026-08-16-KOS-CONTRACT-NEUTRALITY-001-commission.md` and its 2026-08-24
re-registration/framing-amendment · Stage-2 (`4d4738db` `FAIL`) · `G-KOS-CONTRACT-ARTIFACT-UPDATE`.
