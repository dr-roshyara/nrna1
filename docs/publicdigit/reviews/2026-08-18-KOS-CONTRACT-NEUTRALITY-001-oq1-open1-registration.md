# Decision Registration — OQ-1 ratified · OPEN-1 recorded

**Registered by:** Governance, on the delivered PO/ARB act · 2026-08-18
*(Signature date left blank in the act; Governance records the delivery date and does not fill it.)*

---

## ✅ OQ-1 — Anonymous unit identity · **RATIFIED: deterministic declaration path**

> **Use a deterministic declaration-path identity for anonymous analysed units within the single-PHP-file analysis scope.**

**Requirements as ratified:** deterministic · unique within the analysis scope · **must not use the runtime label `"(anonymous)"`** · **independent of line/column formatting changes** · **source-order changes may change identity and therefore invalidate affected declared evidence**.

### ⭐ The trade-off was accepted with eyes open, not inherited

The design offered two candidates and stated the choice as *contract-visible, not a coding preference*: **the choice decides which edit invalidates declared evidence** — reformatting (a line/column coordinate) or reordering (a declaration path).

**The act takes the declaration path and accepts its cost explicitly** — *"source-order changes may change identity and therefore invalidate affected declared evidence"* — rather than adopting the recommendation silently. **Governance records the acceptance as part of the ratification**, because a future reader finding renamed identities after a reordering must be able to see that this was decided, not discovered.

### Operational consequence, surfaced

Declared expected evidence for anonymous units is **order-fragile by design**. This gives `AMD3`'s **evidence-versioning** requirement a concrete driver: the design must say how declared evidence is re-issued when a reordering legitimately renames identities — otherwise a benign edit produces an unexplained conformance failure.

⛔ **Ratification does not authorize implementation.**

---

## 📌 OPEN-1 — L3 vocabulary is PHP-derived in its first version · **RECORDED**

> **The current L3 Fact Model vocabulary is the first version of the language-neutral conformance model, but some vocabulary values are currently derived directly from PHP concepts** — `SelfKeyword` · `StaticKeyword` · `ParentKeyword` · `RelativeName` · `Trait`.

**The five dispositions, as recorded:**

1. the current vocabulary **is PHP-derived in its first binding/version** — stated explicitly rather than left implicit;
2. this **does NOT establish PHP taxonomy as permanently normative** for future languages;
3. **future language-binding work must test whether the vocabulary is sufficiently general**;
4. **any required generalization is a future contract/model amendment requiring its own governed decision**;
5. the current PHP implementation **may proceed** once the remaining implementation-design acceptance conditions are satisfied.

**⛔ Architecture is NOT authorized to rename or redesign these values in this phase**, *"because doing so would alter the already-decided contract semantics."*

### Why this record matters more than it looks

It is the **second** honesty record of the same shape, and the two now reinforce each other:

| Claim being bounded | Recorded limit |
|---|---|
| **Scope of proof** | *"Current scope proves that the PHP binding conforms to the language-neutral model"* — it does **not** prove the model is neutral |
| **Scope of vocabulary** *(this act)* | The vocabulary is **language-neutral in intent, PHP-derived in fact** — its generality is **untested**, not established |

Together they prevent the outcome this programme has repeatedly caught elsewhere: **a first implementation quietly becoming the definition of the thing it was supposed to implement.** The same danger the *"PHP is the first language binding, not the definition"* principle names at the architectural level, now named at the vocabulary level.

*Governance notes the tension is real and correctly resolved by deferral rather than by renaming: renaming now would be redesigning a decided contract on speculation about languages nobody has bound yet — and testing generality without a second binding is exactly what cannot be done in current scope.*

⛔ **This decision does not authorize implementation by itself.**

---

## Boundaries

No implementation authorized · no vocabulary renamed · no contract amended · no artifact modified · the remaining implementation-design acceptance conditions still stand.

**Traceability:** the PO/ARB act 2026-08-18 · implementation architecture (identity candidates §112/§120, `OQ-1` §591) · `IMPL-ARCH-AMD1` (binding ≠ definition) · `IMPL-ARCH-AMD2` (scope-of-claim; declared-specification conformance) · `IMPL-ARCH-AMD3` (evidence versioning) · Decisions 13.3/13.5/13.7
