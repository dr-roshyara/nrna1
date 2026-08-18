# Registration — FINAL implementation architecture ACCEPTED

**Registered by:** Governance, on the delivered PO/ARB act · 2026-08-18
**Accepted artifact:** `2026-08-18-KOS-CONTRACT-NEUTRALITY-001-implementation-architecture.md` (`adc5c8e8`, 691 lines)

## 1 · The acceptance

> **The implementation architecture is accepted as the architecture to be implemented** — **D — Stratified Contract / Fact-Model Boundary**, realized as **B3** grammar-exact PHP extraction → **language-neutral L3 Fact Model** → **language-neutral L4 Cohesion Semantics** → **L5 LCOM4** → **C-as-completion** declared conformance model.

**Incorporating the twelve already-decided constraints named in the act:** L3→L5 boundary · one PHP file per analysis · 13.3 qualifier semantics · 13.5 silences · node+edge+metric conformance · declared expected evidence normative · **implementation agreement diagnostic only** · OQ-1 declaration-path identity · PHP-only language scope · OQ-4 deferred · **L3 vocabulary explicitly PHP-derived in its first version** · future generalization requires its own governed decision.

## 2 · Verified before recording

| Check | Result |
|---|---|
| Delivered and committed | ✅ `adc5c8e8` |
| **AMD3 pre-delivery gate** — can L4/L5 be implemented without PHP source text, spelling, AST nodes, tokens or parser objects? | ✅ **PASSES** — all five inputs unreachable; `Provenance` is a separate collection never handed to L4; **§12 layer C is the executable proof — L4 runs against synthetic facts with no PHP present** |
| Marking discipline (AMD3) | ✅ DECIDED / PROPOSED / FUTURE / OPEN all used |
| No decided rule reopened · no future scope made current · no code · no artifact modified · no new assignment or grant | ✅ self-checked and independently consistent with the record (collector/fixture/contract hashes unchanged) |
| Scope-of-claim stated as AMD2 requires | ✅ *"Current scope proves the PHP binding conforms to the language-neutral model. It does NOT prove the model is neutral… no artifact it proposes should be quoted as evidence for it."* |

## 3 · ⚠️ The qualification this acceptance carries — raised by the design itself

> **"The gate passes on its stated terms. AMD1's stronger principle does not — OPEN-1 records why, and it is returned rather than smoothed."**

**The structural separation is achieved** (L4 cannot reach PHP syntax — mechanically demonstrated). **The stronger principle — *"PHP is the first language binding, not the definition of the language-neutral cohesion model"* — is not fully satisfied**, because the L3 vocabulary carries PHP-derived values (`SelfKeyword`, `StaticKeyword`, `ParentKeyword`, `RelativeName`, `Trait`).

**This is accepted knowingly, not overlooked:** the PO/ARB dispositioned exactly this as **OPEN-1** *before* this acceptance — the vocabulary is PHP-derived in its first version, renaming is not authorized this phase, PHP taxonomy is **not** permanently normative, and generalization is a future governed amendment. **The acceptance therefore incorporates a known, recorded, deferred non-conformance to its own principle — stated here so it cannot later be discovered as a surprise.**

*Governance notes the design behaved correctly: it found a contradiction with its own governing principle, reported it, and returned it rather than resolving it silently or weakening the principle to fit.*

## 4 · The scale this acceptance implies for implementation

**Measured in the accepted design:** `Stmt\Class_` finds **2 of 5** declarations (enums, traits, interfaces missed) · `MethodCall` count is **0** for nullsafe · `(anonymous)` **collides** · `Name::toString()` **erases qualifier kind**. ⇒ ***"Of the nine decided rules, the PHP implementation requires change on eight."***

**Decision 1 stands and is load-bearing here:** the existing reference is **not authoritative and is not this design's baseline** — the accepted architecture is built against the declared contract, not against what the collector currently does.

## 5 · What this acceptance does NOT do — the act's own list

⛔ authorize implementation · modify the PHP collector · modify the Python collector · modify `expected.json` · modify fixtures · retire the existing PHP calculator · add Java or another language · perform artifact migration.

**Also not done:** no lane closed · the OQ-3 artifact-update authorization remains standing and unexercised · `OPEN-1` remains open.

## 6 · Next act, as directed

**Commission / authorize implementation of the accepted architecture** — a separate PO/ARB act. Nothing is commissioned by this registration.

**Traceability:** the PO/ARB act 2026-08-18 · design `adc5c8e8` (§1.1 scope of claim · §1.2 the surviving finding · §11.1 collector disposition · §21 gate) · `IMPL-ARCH` + AMD1–AMD4 · Decisions 13.1/13.3/13.5/13.7 · Decision 1 · OQ-1 · OQ-2 · OQ-4 · OPEN-1
