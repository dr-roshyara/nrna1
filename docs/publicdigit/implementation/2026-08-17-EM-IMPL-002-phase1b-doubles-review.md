# `EM-IMPL-002` Phase 1B — the two remaining Support doubles: Governance review

**Type:** Governance verification (Session 2 — the doubles were written by the implementing lane, so this is verification, not self-review) · **Date:** 2026-08-17 · **Object:** `tests/…/OperatingCoreApplication/Support/` @ commit `1f4b4c5f`

## 1 · `InMemoryProtocolAppend` — 🟢 **APPROVED** (a passive recording boundary)

| PO criterion | Evidence |
|---|---|
| appends facts and refusals **only** | `append()` is **one line**: `$this->entries[] = $entry;` — nothing else |
| ⛔ does not validate | no conditional, no type inspection, no guard anywhere in the method |
| ⛔ does not create facts | it constructs nothing; entries arrive fully formed |
| ⛔ does not change fact meaning | no mutation of `$entry`; `ProtocolEntry` is consumed as given |
| ⛔ does not deduplicate silently | **no `array_unique`, `unset`, `array_splice`, `usort`, `sort`, `remove`, `delete`, `clear` — verified absent by scan** |
| ⛔ applies no business rules | **zero rule vocabulary** (threshold/quorum/required/majority/eligibility/validity/arithmetic) — verified by scan |
| records order | insertion order preserved; the readers (`eventClassSequence`, `eventKindSequence`) **read** it and never impose it |
| **contract fidelity** | ✅ **the port declares exactly ONE method (`append`), and the double implements exactly that**; everything else is a **read-side accessor for assertions** — inspection, not behaviour |

> **Verdict: it is a recording surface plus assertion lenses. Nothing in it could become a second event store, because it has no writer other than `append` and no rule of any kind.**

## 2 · `InMemoryElectionCommitteeRepository` — 🟢 **APPROVED** (storage simulation only)

| PO criterion | Evidence |
|---|---|
| `find()`/`save()` only, plus fixture `seed()` | ✅ **the interface declares exactly `find` + `save`; the double adds only `seed()`** |
| ⛔ calculates no committee validity | absent |
| ⛔ calculates no quorum / thresholds | absent — **zero rule vocabulary by scan**; `unableToFunction` stays on AG-1 where `EM-GOV-065` put it |
| ⛔ decides no eligibility | absent |
| stores aggregate instances only | keyed by `electionId->toString()`; the aggregate is stored **by reference, unmodified** |
| ⚠️ **`saveCount` — inspected, and it is legitimate** | it is an **assertion counter**, not behaviour: it lets a test pin *"no aggregate mutation"* (UC-4) and query purity (UQ-1…4). It changes no outcome and is read only by tests. **This is exactly the "records test information without becoming a fake production service" criterion, satisfied.** |
| ⚠️ **`seed()` vs `save()` — the distinction is load-bearing** | `seed()` deliberately does **not** increment `saveCount`, so fixture setup can never be mistaken for handler-driven persistence. **Without this split, UC-4's no-mutation pin would be untestable.** |

> **Verdict: storage simulation with one assertion counter. It derives no domain meaning.**

## 3 · Phase 1B conclusion

**All six doubles are now reviewed and approved** — four by the PO (AG-2 repo · RecoveryProcess repo · `FixedInstantSource` · `FixedServicePolicySnapshot`), these two by Governance against the PO's stated criteria. **The testing universe in which GREEN behaviour will be judged introduces no authority production does not have, and contains no double of the D-1 port (RED-4 verified).**

> **Phase 1B: CLOSED. The next act is GREEN-2 (`ExpressCommitteePositionHandler`), on the PO's release, with the three binding obligations attached and the PO's UC-1 specifics: no `count()`/`majority()`/threshold comparison · no manual construction of domain facts unless the domain requires it · flow must be handler → request → domain decision → handler appends protocol.**

**Traceability.** PO criteria (this thread, 2026-08-17) · commit `1f4b4c5f` · grant G-1…G-5 · RED-4/W-10 · `EM-GOV-061`(a)/`065` · this review's own scans.
