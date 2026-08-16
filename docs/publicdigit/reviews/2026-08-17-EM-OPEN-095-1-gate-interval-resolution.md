# `EM-OPEN-095`① Resolution — gate-interval semantics (`EM-GOV-068` PREPARED)

**Type:** Governance resolution (Session 2, per PO shortest-path, 2026-08-17) · **§0 registers the `EM-GOV-067` adoption and the KNOWN classification.**
**⛔ `EM-GOV-068` PREPARED, NOT ADOPTED. `053` deliberately NOT resolved (the PO classified it non-blocking). No new simulation.**

## 0 · Acts and classifications registered

> **"I adopt EM-GOV-067 as prepared."** — PO/ARB, 2026-08-17 · applied; before a Chief exists, recovery initiation is expressly not assignable inside the election. **The boundary registered verbatim: *"No actor is created merely because the rules need an actor."***

**The KNOWN classification registered as the PO set it:** **`095`① 🔴 must-resolve** (exposed by tested S-06) · **`053` 🟡 non-blocking dependency** · **`110` 🟡 implementation dependency** · **`049`/`066` 🟢 external boundaries, no internal defect** · **other KNOWN scenarios 🟡 open-not-newly-discovered, not reopened for cosmetic completeness.**

**And the interim statement registered verbatim:** *"Model A's tested operating core is free of contradictions and NEW gaps, but final qualification remains conditional on resolving the gate-interval semantics exposed by S-06 / EM-OPEN-095①."*

## 1 · What the divergence actually was, and what the corpus already holds

**S-06's two readers split on one word: does `052`'s *"cannot be satisfied"* mean *cannot NOW* or *cannot AT ALL*?** The corpus already leans: **`EM-GOV-050` evaluates *"if the threshold remains mathematically ACHIEVABLE, acceptance proceeds normally; if not, the recovery procedure applies"*, and `EM-GOV-051` halts a gate that *"becomes mathematically IMPOSSIBLE."*** **The enduring reading is the one the adopted neighbours use; only `052`'s wording left the instantaneous reading open.** The resolution is therefore mostly an alignment, and only one element is genuinely new: naming what the gate IS in the meantime.

## 2 · `EM-GOV-068` (PREPARED) — the three gate-interval states

> *"A reached acceptance gate is OPEN while its acceptance decision has not been made and satisfaction of the applicable threshold remains mathematically achievable on the recorded facts. An open gate is in progress: no recovery period runs, and participants' positions may be expressed. Progression is HALTED at the gate condition — within the meaning of `EM-GOV-052` — only when the acceptance decision has been made and the threshold was not achieved, or when satisfaction has become mathematically impossible on the recorded facts (`EM-GOV-050`/`051`, `EM-GOV-065`). A halted gate resumes as provided by the applicable recovery rules (`EM-GOV-051`, `EM-GOV-059`(c)), evaluated under the same acceptance rule. Temporary unavailability of a participant does not by itself halt a gate."*

| Element | Status |
|---|---|
| open vs halted split on decided-failure or mathematical impossibility | **DERIVED** — aligns `052` with adopted `050`/`051`/`065`; the instantaneous reading is excluded |
| "an OPEN gate has no running recovery period" | **DERIVED** — recovery periods attach to halts (`014` Part 2, `062`) |
| naming the OPEN state and its properties | **NEW** — the sentence `095`① said was missing |
| temporary unavailability does not halt | **NEW as text, derived in substance** from `057`'s worked case + `064` |

⚠️ **Stated so silence cannot be misread: an OPEN gate is UNBOUNDED IN TIME. That is `EM-OPEN-053`, which the PO has classified a NON-BLOCKING known dependency — this rule makes the exposure visible in adopted text and deliberately does not resolve it.**

## 3 · Effect on S-06, stated in advance of the re-run (prediction, not evidence)

Under `068`: no-vacancy branch → gate **OPEN** (achievable: seats non-vacant, members may return) → no clock → ends by return, by vacancy events (→ `065` Inoperative), or never (`053`, accepted). Every cell cites text. **Whether two independent readers in fact converge is for the re-run to show — this section is a prediction and carries no evidential weight.**

## 4 · Adoption line and the one remaining step

> *"I adopt EM-GOV-068 as prepared."*

**Then: re-run S-06 ONLY, two-reader protocol, freeze at the adoption commit. Both readers converge → the FINAL MODEL A QUALIFICATION is written — as pre-registered: "qualified for the tested operating core."**

**Traceability.** S-06 divergence adjudication (`2026-08-17-EM-BRQ-001-re-run-comparison.md` §1) · `EM-GOV-050`/`051`/`052`/`057`/`059`(c)/`062`/`064`/`065` · `EM-OPEN-053`/`095` · A-3.
