# KOS-SESSION-DISCOVERY-001 — Read-Only Reconciliation of the Corrective Verification Handoff

**Type:** Governance reconciliation (Session 2) · **Date:** 2026-08-15 · **Method:** the raw transition log and the qualified mechanism's source — **not** any session's prose
**⛔ READ-ONLY. Session 1 not started · no new assignment · no new HANDOFF · `workflow-state.php` untouched · nothing repaired, qualified, adopted, or closed.**

> ## Result: **the record is CORRECT and the verification lane IS startable.** The premise that the predecessor handoff is missing came from the **resolver's report**, and that report is **wrong**.

---

## 1 · Seq 16, verbatim

```json
{"type":"HANDOFF","from":"S3-impl-discovery-corrective","to":"S1-verify-discovery-corrective",
 "token":"T-DISC-C12-EVIDENCE",
 "tokenRef":"commit 84100bb0 (C-1/C-2 corrective: RED T-14/T-15 -> GREEN 17/170; AST-015 byte-identical; read purity re-proven)",
 "recordedBy":"governance","seq":16}
```

## 2 · Registered S1 identity — `S1-verify-discovery-corrective` (role `verification`, predecessor `S3-impl-discovery-corrective`, state `CREATED`)

## 3 · Predecessor expected by the mechanism

**The START rule does not consult the `predecessor` field at all.** It requires an entry in `handoffsTo` keyed by the starting session's id. *(The registered `predecessor` value nonetheless matches seq 16's `from` exactly — the chain is coherent on both readings.)*

## 4 · The HANDOFF recognition rule (`workflow-state.php`, verbatim behaviour)

Fold: every `HANDOFF` sets `handoffsTo[$t['to']] = true`. START validation: refuses unless `isset($fold['handoffsTo'][$id])` — *"START requires the predecessor's recorded handoff (token attached)"*.

## 5 · Does seq 16 satisfy it? — **YES.** `to` equals the starting session's id exactly; the transition carries both `token` and `tokenRef`; it was accepted by the contract (a refusal appends nothing, and seq 16 exists); the fold's own consequences are visible — S3 became `HANDED_OFF` and ownership was released.

## 6 · The precise mismatch — **not in the record; between the resolver's report and the record**

**Root cause, established from source:** the `fold` **command's output projection emits only** `workItem · workflow · roles · sessions · mutationOwner · workItemState · grants` — **`handoffsTo` is computed but never emitted** (recorded during the OQ as observation **E-2**: *"the fold projection displays less than the validation fold computes"*). The resolver consumes that projection, so **it cannot see whether a handoff exists.** At `session-resolve.php:227-231` it therefore emits a **fixed pair** for every `CREATED` assignment:

```php
case 'CREATED':
    $missingForActivation = [
        'a recorded predecessor HANDOFF carrying its token',
        'a recorded human START act',
    ];
```

**That list is a static template, not a computed fact.** It named the handoff as missing while the handoff existed.

## 7 · Classification — **not A, not B, not D. A reporting defect in the NEWLY IMPLEMENTED capability, rooted in the pre-existing E-2 projection gap.**

The qualified mechanism's *validation* is correct (it recomputes `handoffsTo` from the log); its *output projection* is lossy. The resolver built a factual-sounding claim on data it never had. **Registered as finding `V-3`.**

**Honest qualifier — this may be specification-conforming:** the approved architecture §H itself illustrated `CREATED` with `missingForActivation: ["recorded human START", …]`, i.e. it enumerated prerequisites without stating they must be *verified against this record*. **So V-3 may be a specification gap rather than a contract breach — that judgement is the PO/ARB's, not Governance's.** What is not in doubt: **a capability whose purpose is faithful reporting stated something about the authoritative record that was not true of it.**

## 8 · Can the existing HANDOFF be used without rewriting history? — **YES.** It is valid, recognized, and sufficient. **Nothing requires repair.**

## 9 · Is a new HANDOFF permitted or needed? — **NOT needed, and it should NOT be created.** A second handoff would be redundant and would falsely imply the first was deficient. *(Had one been genuinely needed, only Governance could record it, and only from the current owner.)*

## 10 · Should S1 remain `CREATED`? — **YES.** Its one genuine outstanding prerequisite is the **recorded human START act**. Both gates were never failing: **predecessor continuity is satisfied; human activation is not yet given.**

---

## Consequences for the lifecycle

**The corrective verification is startable on a human start act alone.** Nothing is papered over: the first gate was never actually open-and-unmet — it was reported as unmet by a tool whose view is incomplete.

**V-3 is now a finding about the very capability under qualification**, discovered by using it — the same way V-1 and V-2 were. It is **outside the approved C-1/C-2 corrective scope** and Governance does not expand that scope. **Three dispositions are open to the PO** *(Governance recommends the third)*: **(a)** accept as a documented limitation and adopt as-is; **(b)** fold a further corrective into the current increment — *not recommended: it re-opens an approved boundary*; **(c)** proceed with the C-1/C-2 verification now, and register V-3 for a **separate disposition** at the final qualification, where the honest options are a follow-up increment or an accepted limitation.

**Traceability:** transition log seq 12–16 · `workflow-state.php` fold/START rules and the `fold` command's emit list · `session-resolve.php:227-231` · OQ observation **E-2** · approved architecture §H · C-1/C-2 grant `G-KOS-DISC-C12-IMPL` · corrective evidence `84100bb0`.
