# Crash-Window Semantics — ARB Decision Package (WP-4B)

**Status:** OPEN — awaiting Board ruling. **Batch 7 is frozen.**
**Convened by:** the Board's Batch-6 review ("open a short ARB review on the crash-window semantics; decide which crash model is canonical").
**Supplied by:** engineering. **Contains no recommendation on the canonical model** — evidence, findings, and the questions the answers turn on.
**Subject:** the persistence state that defines *"concluded but unissued"*, and which crash models WP-4B's redrive must recover.

---

## 0. One withdrawal, first

The Batch-6 report concluded **"K2 is defective."** The evidence supported only **"the RED specification and the architectural model diverge."**

The difference is not cosmetic. The first assigns fault and presumes the remedy; the second states what was observed. Worse, the remedy offered alongside it — *rewrite K2's setup to save a concluded state directly* — **is itself a crash-model decision**, taken in the same breath as a claim to be deferring one. It silently selects Model A below.

**Withdrawn.** What survives is §4's finding: K2's setup produces a state the architecture does not admit.

---

## 1. What the seam does today (Batch 6, commit `f2ac054c8`)

```
receiveRulingDecision(…, Jurisdiction)
  ├─ TRANSACTION 1   save(concluded.retainJurisdiction(j))          [ADR-T1: alone]
  └─ TRANSACTION 2   requestIssuanceFor(concluded)
                       ├─ issuance->request(command)                → CoordinatesAdjudication
                       └─ save(markIssuanceRequested(now))

redriveIssuance()
  └─ foreach store->concludedAwaitingIssuance()  →  requestIssuanceFor(process)
        where "awaiting" ≡ status = concluded_ruling_requested AND issuance_requested_at IS NULL
```

**The load-bearing fact for everything below:** `CoordinatesAdjudication::issueDetermination()` **throws** on a duplicate — it does not no-op.

```php
// app/Contexts/Adjudication/Application/Service/CoordinatesAdjudication.php:41
if ($this->determinations->findByChallengeRef($command->challengeRef) !== null) {
    throw DeterminationAlreadyIssued::forChallenge($command->challengeRef);
}
```

`CoordinatorIssuanceRequest::request()` adds nothing and catches nothing, so that exception propagates **out of `requestIssuanceFor()`, out of the `foreach`, and out of `redriveIssuance()`.**

---

## 2. The three crash models, traced against the code

| | Crash | Persisted state | Determination exists? | What happens on redrive **today** |
|---|---|---|---|---|
| **A** | TX1 committed, TX2 never started | concluded · marker `NULL` | ⛔ no | found → requested → issued → marked. **Correct.** |
| **B** | TX2 started, `request()` **succeeded**, marker not written | concluded · marker `NULL` | ✅ **yes** | found → requested → **`DeterminationAlreadyIssued` thrown, unhandled** → marker never written → **process stays in the redrive set permanently, throwing on every pass** |
| **C** | `request()` **failed**, marker not written | concluded · marker `NULL` | ⛔ no | found → requested → issued → marked. **Correct.** |

**A and C are indistinguishable in persistence** — same row, same absence of a determination — and both are handled correctly today. **The models that matter are A/C versus B.**

---

## 3. FINDING — Model B is already ruled by accepted architecture, and is not implemented

This is the finding that changes the shape of the Board's question, and it is not engineering's opinion. EPIC-004K **§12, Failure handling** (accepted):

> **Issuance request meets INV-B1 refusal** (a determination already exists for the challenge): not an error to escalate blindly — **reconcile**: if this process itself concluded-and-requested earlier (redelivery), **ack**; if another writer issued (should be impossible under PM-1 + INV-B1 together), **dead-letter + escalate** — the conflicting-determination translation precedent (`ConflictingDetermination → PermanentFailure`) applies unchanged.

**Model B's handling is therefore not an open architectural question — it is a ruled policy that WP-4B has not implemented.** Today the refusal is neither reconciled, acked, nor dead-lettered: it escapes as an unhandled exception.

**How this was missed, stated plainly:** the batch plan derived the seam from §11 (persistence, conclude-time atomicity) and R-72/R-76, and did not carry §12 into the RED boundary. §12 was cited in `CoordinatorIssuanceRequest`'s traceability line **as justification for the adapter adding nothing** — the sentence about INV-B1 owning uniqueness at the boundary — while the *obligation* §12 places on the requester was not read across. **A citation was treated as coverage.** This is the same failure mode as the ADR-T14 readiness tick: a document referenced for the half that supported the design, with the other half unexamined.

---

## 4. FINDING — K2's setup produces a state the architecture does not admit

K2 and K3 cannot both pass, and the proof does not depend on which model is canonical:

- **K3** — conclude → redrive → assert the spy still holds **exactly 1** request ⟹ the marker **must** be set by the conclude path.
- **K2** — conclude → `$crashed->requests = []` → redrive → assert the new spy holds **1** request ⟹ the marker **must not** be set by the conclude path.

The store state after `concludeFor()` is **identical** in both. K2's only mutation is on the **spy**, a recording double: it erases the *record* of the request, never its *durable effect*. Both managers share one store and the manager holds no per-instance memory.

So K2's setup yields **marked-but-never-requested** — which is **none of A, B or C**. It is not a crash state at all; it is a state the design cannot produce. **K2's stated intent (its docblock names Model A exactly: *"the conclusion transaction committed and the issuance transaction did not"*) is sound. Its mechanism does not reach that intent.**

---

## 5. OBSERVATION — independent of the model choice: one poisoned process starves the batch

`redriveIssuance()` iterates without per-process error isolation. Any process that throws — a Model-B row today, or any future permanent failure — **aborts the entire redrive pass**, so every process ordered after it is never attempted. The ordering is `orderBy('concluded_at')`, so the oldest stuck process blocks all newer ones indefinitely.

**This is a defect under every candidate model**, and it is not addressed by choosing one. Classified: **Engineering** (behavioural, inside the authorized seam), but it changes the seam's behaviour, so it is surfaced rather than repaired unilaterally.

---

## 6. What the marker can mean, given R-76

`issuance_requested_at` records **"a request was made"**, not **"issuance is confirmed"** — and this is forced, not chosen. R-76 fixed WP-4B's scope to **the request path only**, expressly excluding PM-6's issuance-confirmation half. Nothing in this slice learns whether a determination was actually written.

**The consequence the Board should see:** a marker meaning *"requested"* **cannot** distinguish Model A from Model B, because in both the request was attempted and in neither was the marker written. Only the confirmation half — PM-6, §12's reconcile, or a read of the determination — can tell them apart. **The crash window between `request()` and `save(mark)` is therefore not closable by the marker alone, by construction.**

---

## 7. Questions for the Board

| # | Question | Why it must be answered before Batch 7 |
|---|---|---|
| **Q1** | Which crash models must WP-4B's redrive recover — **A/C only**, or **A/B/C**? | Determines whether §12's reconcile is in this slice |
| **Q2** | If B is in scope: is implementing §12's reconcile **inside WP-4B** (widening R-76's request-path-only scope), or **a separate authorized slice**? | R-76 is a Board ruling; only the Board may widen it |
| **Q3** | Confirm the canonical meaning of `issuance_requested_at` as **"a request was made"** (§6 shows R-76 forces it) — or rule that the marker must mean *confirmed*, which relocates it to PM-6 | Fixes what *"unissued"* means in the redrive predicate |
| **Q4** | Should `redriveIssuance()` isolate failures per process (§5)? | A one-process failure currently starves every later process |
| **Q5** | Given Q1–Q3: is **K2 amended to express its stated intent, or confirmed as written**? | The keystone's setup is currently unreachable (§4) |

---

## 8. What engineering is **not** doing

- **Not** choosing a crash model.
- **Not** amending K2 — its setup change would itself select a model (§0).
- **Not** implementing §12's reconcile — that is scope, and scope is R-76's, not engineering's.
- **Not** repairing §5's isolation gap — inside the authorized seam, but a behavioural change surfaced for the same reason.
- **Not** touching production code at all until the ruling. **Batch 6 stands as committed and is frozen.**

No answer to Q1–Q5 invalidates Batch 6's production code as written; Q2 and Q4 would **add** to it, Q5 touches only the test.

---

## 9. Traceability

EPIC-004K §11 (persistence · conclude-time atomicity) · **§12 (failure handling — the finding of §3)** · R-72 (WP-4B authorized) · R-73 (jurisdiction ← the deciding authority) · R-76 (**request path only**) · ADR-T1 (one aggregate per transaction) · ADR-T3 (at-least-once ⇒ idempotent replay) · INV-B1 (one determination per challenge) · AP-1 (fail closed) · AP-2 (one home per parameter) · commit `f2ac054c8` · plan `docs/plans/20260803-1600-wp4b-conclude-to-issue-seam-delivery-plan.md` §4.

**Placement note:** `php scripts/doc-placement.php --scope=product-specific --domain=publicdigit --maturity=research` derives `docs/publicdigit`. Classification was established first — *purpose:* supply the Board a decision package; *type:* decision package; *role:* evidence for an architectural ruling; *steward:* engineering; *lifecycle:* superseded on ruling — and that type already has a home in use at `engineering/verification/commissions/` (precedent: `2026-08-02-issuance-input-ownership-decision-package.md`). Per ES-005.4, the existing home is consumed rather than a second one created. **Flagged for governance:** the script does not currently rule this artifact type, and `--domain=adjudication` is unknown to it (known: `publicdigit`, `knowledgeos`, `pks`).
