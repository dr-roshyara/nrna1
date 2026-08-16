# `EM-ARCH-001` — Review criteria for the returned deliverable (registered in advance)

**Type:** Governance registration (Session 2) · **Date:** 2026-08-17
**⛔ THESE ARE REVIEW CRITERIA, NOT COMMISSION AMENDMENTS.** The PO expressly declined to add further design instructions — *"let Session 4 work; the commission already contains the right guardrails."* **Session 4 is NOT bound to read this document; Session 2 IS bound to review against it when the deliverable returns.**

## 1 · The expected shape (PO, 2026-08-17) — order matters

**① Reconstruct the qualified business model** (which of `EM-GOV-001`–`068` actually participate in Model A; no redesign) → **② derive the domain model** (concepts · responsibilities · relationships · lifecycle/state behaviour · invariants and policies) → **③ identify boundaries** (acceptance · Committee membership/recovery · gates · the external-authority boundary · terminal-state boundary · timing/recovery boundaries) → **④ derive behavioural flows** (normal acceptance · objection/failure · temporary unavailability · vacancy · Inoperative · restoration · expiry→terminal · OPEN-gate behaviour) → **⑤ only then the target architecture** (components/bounded contexts · layer responsibilities · interfaces · state transitions · persistence responsibilities · events where justified · dependency directions).

## 2 · The critical rule the review applies to EVERY element

> **"Which qualified governance rule requires this architectural element?"**
> *"The architecture would be cleaner if we did X"* — **NOT sufficient.**
> *"`EM-GOV-065` requires this behaviour, therefore…"* — **traceable and legitimate.**
> At `053`, `049`, `066`, `077`/`076` or any deliberately open dependency: **the branch stops and the dependency is recorded. An invented business rule anywhere in the deliverable is a review FAIL for that element.**

## 3 · Watch items (PO-named failure modes)

* 🔴 **OPEN must not become a technical timeout.** The Governance model deliberately allows a gate to remain open indefinitely (`068`; `053` unresolved by classification). **A timer introduced "because timers are technically convenient" is precisely the drift this review exists to catch.**
* 🔴 **`Inoperative ≠ Halted ≠ OPEN ≠ Terminal.`** These four survived adversarial simulation as distinct business conditions; **the derived model must keep them distinct — as explicit concepts or states if the model requires them — and must never collapse two into one implementation state.**

## 4 · What happens on return

Session 2 reviews against §§1–3 + the commission's own text (traceability obligation, no-assumption rule, §4 constraints-as-constraints) → review goes to the PO with findings → **Human/PO/ARB design approval precedes any implementation authorization** (checkpoint, verbatim in the signed act).

**Traceability.** `EM-ARCH-001` signed commission §5 · final qualification report §4 · this thread's PO message (2026-08-17) · A-3.
