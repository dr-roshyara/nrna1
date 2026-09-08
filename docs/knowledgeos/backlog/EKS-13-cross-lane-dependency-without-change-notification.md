# EKS-13 — A conclusion in one research lane depends on another lane's document, and nothing tells us when that document changes

**Status:** **BACKLOG · OPERATIONAL EXPOSURE** — registered from `P-47` (`docs/knowledgeos/theory-extraction/65-P47-…`). ⛔ **Not commissioned; activation requires a Human/PO-ARB authorization act.**
**Class:** cross-lane dependency / change-notification gap — KnowledgeOS research operations.
**Registered by:** Lane T (theory extraction), 2026-09-08.

> ### ⛔ **This item records a problem and a candidate requirement. It commissions nothing and proposes no mechanism.**

---

## 1 · The problem, in plain terms

Two research lanes work on KnowledgeOS in parallel. **They read the same source material, but each owns different documents, and neither is notified when the other changes something.**

In September 2026 one lane (Lane T) reached a conclusion about the persistence theory that **depends on a specific sentence in a document owned by the other lane** (`MD-018`, owned by the Three-Model Convergence lane). The dependency is real and load-bearing:

* Lane T asked whether a particular capability — *"remember the current status of a thing"* — is genuinely necessary, or whether it could be reconstructed from the record of past changes.
* The answer turned out to hinge on **whether a status can ever move backwards**. If statuses only ever move forward, the current status already tells you the whole history, and the capability is necessary in a specific way. If statuses *can* move backwards, the capability becomes redundant and the theory would need **one fewer** capability.
* The other lane's document says statuses **only move forward**. On that basis, Lane T concluded the capability is necessary.

$$\boxed{\textbf{So a result in Lane T is only as stable as a sentence in a document Lane T does not own, cannot edit, and is not told about.}}$$

## 2 · Why this is a business problem, not a research question

⭐ **Nothing is wrong with the current conclusion.** The dependency was declared openly when it was created — the intake record that admitted the other lane's ruling wrote its own warning at the time:

> *"if Lane M revises `MD-018`'s text, **no mechanism propagates that to Lane T**, and this ruling would require re-confirmation."*

⭐⭐ **The problem is that the warning is the only safeguard.** It sits inside a document that a future reader may never open. If the other lane edits that one sentence — for a perfectly good reason of their own — then:

| what happens | who notices |
|---|---|
| the other lane's document changes | ⭐ that lane |
| Lane T's conclusion silently becomes wrong | ⛔ **nobody** |
| the persistence theory should shrink by one capability | ⛔ **nobody** |
| downstream work built on the theory inherits the error | ⛔ **nobody, until much later** |

⭐⭐⭐ **The exposure is not a disagreement between the lanes. It is that a correct decision in one lane can silently invalidate a correct conclusion in the other, with no signal in between.**

## 3 · Why the usual safeguards do not cover it

* **The two lanes are deliberately independent.** That independence is a *feature* — it is what makes a later comparison of their results meaningful. ⛔ **Any remedy must not create a back-channel between them.**
* **Formal cross-lane intake records exist** and were used correctly here. ⭐ But an intake record captures a fact **at a moment in time**; it does not watch that fact afterwards.
* **Ordinary review does not reach it.** A reviewer of Lane T's work sees a properly cited dependency. A reviewer of the other lane's work sees an ordinary edit to their own document. ⭐⭐ **Neither reviewer is positioned to see the break.**

## 4 · Candidate requirement — ⛔ hypothesis only

> **Candidate:** *"Where a conclusion in one research lane depends on a specific claim owned by another lane, the dependency SHALL be recorded in a place that is checked when the owning document changes — or the dependent conclusion SHALL be re-confirmed on a stated schedule."*

⚠️ **Not adopted, not designed, and deliberately vague about mechanism.** ⛔ **No notification system, registry, or automation is proposed here** — choosing a remedy is a separate act, and `P-44`/`P-45` established that this lane has no authority to design one.

## 5 · Scope of the exposure today

⭐ **One known instance**, and it is documented: the persistence theory's treatment of *current-state retention*, which depends on the forward-only status rule.
⚠️ **The instance count is a floor, not a total** — no survey of cross-lane dependencies has been performed, and this item does not perform one.

## 6 · What this item explicitly does NOT claim

⛔ That the other lane has done anything wrong. ⛔ That the current conclusion is unsound — **it is sound today**. ⛔ That the lanes should be merged or coordinated — ⭐ **their independence is deliberate and valuable**. ⛔ That a technical notification mechanism is the right answer; a re-confirmation habit might be.

## 7 · Traceability

`P-47` `docs/knowledgeos/theory-extraction/65-P47-PERSISTENCE-KERNEL-MINIMALITY-AND-IRREDUCIBILITY-PROOF-AUDIT.md` §5
Origin of the dependency: `25-INTAKE-001-MD018-MONOTONICITY-RULING-AND-BRANCH-R0-RESOLUTION.md` — the `X3` revocation condition.
Earlier corroborating incident: `20-CROSS-LANE-CONCURRENCY-RECONCILIATION.md` (live concurrent writes detected between the lanes, 2026-09-07).
Adjacent: `EKS-07` (multi-process coordination) — ⚠️ **related but distinct**: `EKS-07` concerns *concurrent writes to shared state*; this item concerns *a stale dependency on another owner's stable document*. Kept separate per `ES-005.4`.
