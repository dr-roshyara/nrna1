# Decision Request — the six pending attribution decisions (P-1 … P-6)

**To:** Human / PO / ARB
**From:** Governance (system-of-record presentation per ES-001.3 — business language first; recommendations, not decisions)
**Source:** `KOS-GOV-ATTRIBUTION-001` Architecture Decision Proposal (2026-08-15), presented on the ARB routing of 2026-08-16.
**Standing:** these six decisions are now also the remediation direction for the **accepted root governance gap** — *the system has no trustworthy identity of the actor performing a governed action* (acceptance registered 2026-08-16). The independent G-2 verification confirmed the proposal's evidence without having read it; the evidence base is stronger than when the proposal was delivered.

**Business context in one sentence:** every claim in our governance records about *who did something* is currently a self-declaration — by the record fields, by the terminal label, and by git, which shows one single identity across all lanes; these six decisions choose how much of that to fix, in what order, and with which safety rules.

---

## P-1 · How should we attribute actions to actors?

**The situation:** four ways forward were analyzed. Making Governance itself a tracked session fails structurally (someone must register the registrar). A small record change could stamp every action with its actor. Signed/per-lane git identities give the only *hard-to-forge* attribution. Doing nothing keeps today's convention, which cannot attribute governance acts at all.

**The consequence of the choice:** this decides whether "who did this?" is ever answerable from our records, and at what cost.

**Options:** staged hybrid (convention now → record change if evidence demands → signatures for high-assurance acts) · record change now · signatures now · convention only · reject all.

**Governance recommendation: the staged hybrid (A5)** — it matches the platform's own doctrine (record before enforcement, upgrade on demonstrated need), and it avoids touching the qualified-and-closed workflow engine until evidence demands it. This is also the proposal's recommendation.

---

## P-2 · May a producer review its own work at the governance level?

**The situation:** today this is permitted with disclosure. The analysis found the two independence cases are not equal: an implementer verifying its own work can *choose what to test* (poorly recoverable — stays prohibited), while a producer reviewing its own scope can be *checked by anyone later* against the recorded grant (well recoverable).

**The consequence:** a blanket prohibition would be unenforceable today (no attribution exists to enforce it with) and could paralyze ordinary governance work; leaving it as-is keeps the duty toothless.

**Options:** leave unchanged · **strengthen the disclosure duty** · restrict the worst sub-case · prohibit outright.

**Governance recommendation: STRENGTHEN** — keep the permission, but make disclosure structured and mandatory-by-shape (name the prior act by commit, and state what the review checked that the producer could not check itself). It attacks the real risk — a review that adds no independent signal — rather than the proxy of who ran it. Also the proposal's recommendation.

---

## P-3 · Adopt the two attribution safety rules as binding rule text?

**The situation:** two invariants were proposed. First: *actor identity may only ever describe who acted — it must never decide who may act.* Second: *every attribution is a self-declaration until a mechanism the declarer cannot forge produces it, and must be labelled as such.*

**The consequence:** without the first, any future attribution field is one careless precondition away from becoming an authority mechanism — which the programme's constitution forbids. Without the second, artifacts will overstate what they know. Today's records already *use* both informally; this makes them binding.

**Governance recommendation: ADOPT both** (INV-ATTR-1, INV-ATTR-2). They are prerequisites for every other option and cost nothing. If adopted, Governance advises hosting them by the established parsimony route rather than minting a new document.

---

## P-4 · Authorize separate git identities per lane?

**The situation:** every commit in the programme currently carries one identical author identity. Separate identities per lane (optionally signed) would give each commit a real, hard-to-forge attribution — an operating-setup change, touching no mechanism, no code.

**The consequence:** this is the single highest-value, lowest-cost attribution gain available today. It would also make rules like "the drafter must not verify" *checkable after the fact* from git history.

**Governance recommendation: AUTHORIZE** — with the note that the identity attested is a machine user, so mapping user→lane honestly requires one identity per lane, and the labelling duty from P-3 still applies.

---

## P-5 · May an independence check ever block work, or only report?

**The situation:** even with perfect attribution, a machine could verify only that two *processes* are distinct — a proxy for independence, not the thing itself (all lanes are the same model and the same human director). A green gate would certify something weaker than the rule intends while looking stronger than honest disclosure.

**The consequence:** deciding this now prevents a future over-trusted automation from quietly replacing human judgment on independence.

**Governance recommendation: REPORTING-ONLY, as a standing constraint** — flags for human attention, never blocking gates, consistent with the platform's zero-new-hooks position and the existing surface-don't-decide precedent.

---

## P-6 · Where does the record-change dependency sit in the queue?

**The situation:** the possible `actor`-field change (from P-1's later stage) would join two already-open mechanism dependencies (grant↔session linkage, and another). The engine is qualified and closed; each change needs its own authorization.

**The consequence:** ordering decides which gap closes first if and when mechanism work is ever authorized.

**Governance recommendation: DEFER the priority call** until P-1's staged path produces evidence that the convention is insufficient — deciding a queue position today for work that may never be needed would be planning ahead of evidence. *(This is Governance's recommendation; the proposal itself leaves P-6 open.)*

---

## Summary for decision

| # | Decision | Recommendation |
|---|---|---|
| P-1 | Attribution direction | **Staged hybrid (A5)** |
| P-2 | Producer self-review at governance level | **STRENGTHEN disclosure** |
| P-3 | Adopt the two safety invariants | **ADOPT both** |
| P-4 | Per-lane git identities | **AUTHORIZE** |
| P-5 | Independence checks: gate or report | **Reporting-only** |
| P-6 | Mechanism-change priority | **DEFER until evidence** |

Each may be decided independently (Approve / Reject / Defer per item, ES-001.2). **Only after these decisions does Architecture enter** — to turn the accepted decisions into the target governance architecture, per the ARB's sequence of 2026-08-16.

**Technical evidence:** ADP `2026-08-15-KOS-GOV-ATTRIBUTION-001-architecture-decision-proposal.md` (E-1…E-7 · §2–§9 · P-1…P-6) · G-2 verification `fe298569` · acceptance registration `487fce74` · reconciliation `2026-08-16-G2-attribution-reconciliation.md`.
