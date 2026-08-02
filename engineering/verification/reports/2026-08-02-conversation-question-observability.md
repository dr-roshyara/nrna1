# Is the conversation question architecturally observable?

**Date:** 2026-08-02 · **Prepared by:** Principal DDD Architect
**Question the ARB set:** does Answer A vs Answer B change the **business model**, or only **provenance plumbing**?
**Status:** verification only. **No ruling · no code · no elevation.**

---

> # VERDICT — **On the five tests, it does not change the business model today.**
>
> **It is an implementation-governance allocation decision now — and it becomes strategically observable the moment WP-8's keystone is written.**
>
> **So the honest answer is not "elevate" or "don't". It is: the Board may defer it, but not past WP-8.**

---

## 1. The five tests

| # | Test | Answer | Evidence |
|---|---|---|---|
| **1** | **Which domain invariant changes under A?** | **None** | No Determination invariant references correlation or conversation — `EPIC-004E` mentions neither |
| **2** | **Which domain invariant changes under B?** | **None** | Same |
| **3** | **Which user-visible business behaviour changes?** | **None found** | `grep -rln "correlation" app/Http app/Contexts/*/API resources/js` → **no matches.** Correlation reaches no user-facing path |
| **4** | **Which published contract changes?** | **None — shape** | `CorrelationId` is **already** in the published envelope (`EventId · EventType · AggregateId · AggregateVersion · OccurredAt · CorrelationId · CausationId · SchemaVersion`). **Only the VALUE differs between A and B, not the contract** |
| **5** | **Which downstream bounded context changes?** | **None** | No consumer reads correlation. Election's and Contestation's handlers do not reference it; the only consumer of the field is the messaging platform itself |

> **On the criteria as set: only provenance plumbing changes. Nothing in the business model does.**

## 2. The qualification the Board should weigh before accepting that verdict

**Neither answer changes an invariant — because both are *readings of one invariant that already exists*:**

> **CONSTITUTIONAL AUDIT INVARIANT (ARB, 2026-07-10)** — *"Exactly **one** producer mints a CorrelationId for each constitutional conversation… no producer may mint a second correlation within a conversation."*
>
> **And its definition of the subject:** *"A **CONSTITUTIONAL CONVERSATION** is the complete causal chain of events originating from **one constitutional decision process**."*

**So the question restates as: *is the correction loop one constitutional decision process, or two?*** **That is a question about the constitutional model, not about plumbing** — even though, today, nothing observable turns on the answer.

> ### ⚠️ Corrected — no present violation is established, and the earlier wording claimed one
>
> **Today's implementation is compatible with one interpretation of the constitutional invariant and potentially inconsistent with another. Resolving which interpretation is normative is deferred until WP-8, when the invariant becomes executable through end-to-end assertions.**
>
> **The evidence supports a CONDITIONAL statement, not a conformance finding.** *If* Answer A is adopted, the current implementation **appears inconsistent** with the invariant; *if* Answer B is adopted, it **appears consistent**. **The modelling decision determines how the existing invariant APPLIES — it does not establish that the invariant is being breached today.**
>
> **Concluding a violation before the definition it depends on has been decided would be circular**: the invariant speaks of *one constitutional conversation*, and what counts as one conversation here **is the open question itself.**

**What the five tests do not capture:** the decision does not *choose* an invariant — **it determines how an existing one applies.** **A question about the application of a constitutional invariant is not obviously an implementation-allocation matter, however invisible its current effects.**

## 3. Why the horizon matters

**Roadmap §WP-8's keystone is *"IT-style full-loop suites… ONE CorrelationId per conversation asserted end-to-end."***

**That test cannot be written without the answer** — it must assert either one id across raise → route → adjudicate → issue → correct → resolve, or one id per conversation with the loop spanning two. **At that point the answer becomes executable, and therefore observable.**

**So the question's classification is time-dependent:**

```
today          →  implementation-governance allocation (nothing observable)
at WP-8        →  strategic: it defines what the end-to-end assertion asserts
```

## 4. What this changes about the modelling commission

**`2026-08-02-determination-conversation-decision.md` asked the Board to decide now. On this evidence that was premature** — the report elevated a question without first showing it was strategic, which is the test the Board applied and the report had not met.

**It is re-marked accordingly: convened, but *not urgent*, and explicitly deferrable.** **What it may not become is forgotten** — WP-8 will force it, and forcing it *during* WP-8 would settle a constitutional reading inside a test-writing task.

## 5. What is not concluded

**Whether the loop is one decision process or two.** **Whether today's two mints are a violation or a design — and note that this cannot be settled BEFORE the definition it depends on is settled.** **Whether the decision belongs to the ARB or to implementation governance** — §2 argues the constitutional framing pulls one way and the five tests pull the other, **and the Board is better placed than this report to weigh them.**

---

**Traceability:** `app/Contexts/Shared/Application/Messaging/EventProvenance.php` (the Constitutional Audit Invariant and the definition of a constitutional conversation) · **ADR-MP-06** (`docs/adr/ADR-MP-Messaging-Platform.md` §MP-06) · `docs/implementation/EPIC-004E_Determination_Invariants.md` (no correlation reference) · `docs/implementation/Canonical_Event_Catalog_v1.0.md` (envelope) · `docs/implementation/EPIC-004_Architecture_to_Implementation_Roadmap.md` §WP-8 keystones · `engineering/verification/commissions/2026-08-02-determination-conversation-decision.md` (**qualified by this report**) · `…/reports/2026-08-02-wp3b-wp4b-boundary-verification.md`. **Verification only — nothing elevated, nothing decided, nothing implemented.**
