# `EM-IMPL-002` — RED amendment APPROVED · GREEN-5 blocked · one status correction

**Type:** Governance registration of the PO's approval · **Date:** 2026-08-17

## 1 · The approval, as given

| Item | Decision |
|---|---|
| `AbsentAggregateReferenceRedTest` · scope-aware rewrite · RED failure quality · no production workaround · ADR separation · `Q-RESTORE` investigation · the boundary rule | ✅ **all APPROVED** |
| **Proceed to GREEN-5 immediately** | ❌ **NOT YET** |

**The registered ground, and it is the reusable standard:** *"The RED test detects absence-handling ambiguity. It does not decide what absence means."* — and on the diagnosis: *"the danger is not `InvalidArgumentException` vs `DomainException`; the danger is Handler A means caller mistake, Handler B means integrity failure, Handler C ignores it. That is semantic drift."*

**Two further gradings worth preserving:** on RED quality — *"a bad RED would fail everywhere; a good RED fails only where the architecture currently violates the rule"* · on provenance — *"the application layer should not become a provenance warehouse"*, and the DDD test to apply is **which bounded context owns the invariant**, never which layer currently needs the data.

## 2 · The RED amendment is FROZEN as required

**`b8836ee8` is the frozen reference commit.** It contains tests only; `app/` shows **zero production change across the entire hold**. The required sequence is registered:

**GREEN-4 → RED amendment accepted ✅ → ADR decision ⏳ → normalization scope defined ⏳ → UC-1/2/3 aligned ⏳ → GREEN-5.**

⛔ **GREEN-5 is BLOCKED until all three pending steps exist.**

## 3 · ⚠️ One correction to the status table

**The table lists `GREEN-3 UC-2 — pending`. It is DONE.** Verified three ways: commit **`cdc45f99`** *("GREEN-3 — UC-2 behaviour, orchestration only")* · `RecordVacancyEventHandler` contains **zero** `BadMethodCallException` (its body is implemented) · `RecordVacancyEventHandlerRedTest` **6 passed**.

**The corrected state:** GREEN-1 ✅ · GREEN-2 (UC-1) ✅ · **GREEN-3 (UC-2) ✅** · GREEN-4 (UC-3) ✅ · RED hardening ✅ · **GREEN-5 (UC-4) blocked · GREEN-6 (UC-5) not started · GREEN-7 (queries) not started.** *Recorded because it changes what remains: three increments are done, not two.*

## 4 · What the two rulings must settle

**ADR §6:** the meaning of absence (A invalid request · B integrity failure · C lifecycle state · D composite) **(a)** · the shape **(b)** · whether normalization of UC-1/2/3 is authorized **as one slice (c)** · confirmation that the RED pin precedes it **(d — delivered, so this is now a confirmation)**.
**Provenance:** ownership — A/B need **domain** authorization · C needs **new-port** authorization · D needs only a ruling but is weakest on sovereignty · E **rejected** (it would leak lifecycle meaning into the caller) — **plus the companion decision on restoration with no prior halt (`w8`).**

**Traceability.** Hold closure `a219d465` · RED amendment `b8836ee8` · ADR + investigation `783c1f7e` · commits `1f4b4c5f`→`d2a0fe7c`→`8ea13835`→`cdc45f99`→`4651a3e7`.
