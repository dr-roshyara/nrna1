# `EM-GOV-006` — Adoption Registered

**Type:** Governance registration (Session 2) · **Date:** 2026-08-15 · **Basis:** PO/ARB formal adoption
**⛔ One adoption only. `EM-OPEN-026`(a) is NOT decided. `EM-OPEN-031` is NOT decided. `EM-OPEN-023` is untouched. Architecture ⏸️ and Session 3 🛑 unchanged.**

---

## 1 · The act, registered verbatim

> **"I formally adopt EM-GOV-006."**
>
> **"A post-schedule decision period exists only where an Election Rule deliberately establishes it. No technical grace period, scheduler behaviour, timeout, retry interval, or implementation convenience constitutes such a period."**
>
> **"This does not decide whether the period is A, B, or C. It simply establishes that Architecture cannot invent one."**

**A-3 check:** performative (*"I formally adopt"*) · performed and durably recorded · scope-exact, with the scope limit supplied by the PO rather than inferred. ✅ **Registered.**

**The PO's wording is the operative text and is broader than Governance's proposal** — it adds *scheduler behaviour*, *timeout*, *retry interval* and, most importantly, **implementation convenience**, which is a category rather than a mechanism and therefore closes the enumeration's escape hatch. Governance's original list is retained as **illustrative, explicitly non-exhaustive, and not the rule.**

## 2 · What changed, and what deliberately did not

| | Before | After |
|---|---|---|
| `EM-GOV-006` | PROPOSED (§4c) | **ADOPTED (§3, governance rules)** |
| PO invariant 8 | ⚠️ listed as established, **was not** | ✅ **established — by an act** |
| A / B / C | open | **open, unchanged** |
| `EM-OPEN-031` | open | **open, unchanged** |
| `EM-OPEN-023` | with the PO | **with the PO, untouched** |

**The flag is preserved as record, not deleted now that it is satisfied.** *"A statement that something is already established does not establish it"* — the adoption came from an act, and the record shows that it did. **Erasing the flag because it was resolved would destroy the evidence that the discipline worked.**

## 3 · What the adoption now makes true

**Rule-backed, no longer merely derived: no governed post-schedule decision period exists in this system today**, because none has been established by an Election Rule. **A later adoption of A, B or C does not retrospectively authorize any behaviour that predates it.**

⚠️ **Governance has inspected no code and asserts nothing about what the system currently does.** Whether any grace behaviour exists — and would now be unadopted — is a **verification** question for Session 1, not a governance claim. *(Same discipline as `F-PROTO-1`.)*

**The rule's real force, stated plainly:** it does not settle what a post-schedule decision period *means*; it settles **who may bring one into existence.** Only an Election Rule may. Not a scheduler, not a timeout, not a convenience.

## 4 · The PO's three-part model — recorded, deliberately NOT adopted

The PO distinguishes **(A) time event** — the scheduled boundary has been reached · **(B) evaluation** — the system evaluates whether progression is currently permitted · **(C) authority decision** — the Chief explicitly requests progression; with progression requiring the temporal condition **and** the authorization **and** eligibility **and** the mandatory Election Rules together.

**Registered as a PO-stated conceptual model. NOT adopted.** It is consistent with everything already adopted — and Governance agrees it is clearer than treating progression as a single boolean.

> ⚠️ **But it must not be adopted as a rule right now, and the reason is precise: the model treats *evaluation* as a distinct thing that happens — which is exactly what `EM-OPEN-031` asks.** Adopting it would resolve `EM-OPEN-031` by the back door, and the PO expressly kept that question open. **The worked example accompanying it illustrates one possible answer; it is not the answer.**

**Governance records the model's own contribution to `EM-OPEN-031` in the PO's terms:** *the clock reaching 10:00* and *the system performing an evaluation at 10:00* are different, and only the second is an act. **That sharpens the question; it does not close it.**

## 5 · Status and the agreed ordering

```
ADOPTED TODAY      EM-GOV-006 — only an Election Rule may create a post-schedule
                   decision period
STILL WITH PO      EM-OPEN-023  what is a published schedule?          ← FIRST
                   EM-OPEN-026(a)  A / B / C                            ← second
                   EM-OPEN-031  evaluate-and-record at the boundary?    ← third
                   EM-VOC-004 wording ratification (one line, pending)
ORDERING           published schedule → when it creates a voting opportunity →
                   what happens at its scheduled boundary → can a post-schedule
                   decision period exist?   (PO's ordering, registered)
ARCHITECTURE       ⏸️ STOPPED    SESSION 3  🛑 STOPPED    IMPLEMENTATION  NOT AUTHORIZED
```

**Governance's next act: none until `EM-OPEN-023` is taken up.** When it is, Governance will bring the question in business language **without narrowing the options** — the restraint the PO named as the strongest result of the earlier report still applies.

**Traceability.** Clarification registration (`c732aee5`) → PO/ARB adoption (§1) → `EM-GOV-006` moved to §3 with the PO's verbatim text · §4c row struck · invariant 8 marked established-by-act with its flag preserved · `EM-OPEN-031` back-door guard recorded → this registration.
