# PKS-ADR-001 — Companion: Review Record

| | |
|---|---|
| **Kind** | **Review record** for PKS-ADR-001 — the artifact's review history, extracted from that ADR's §14 (*ARB Review Status*) and Appendix C (*Terminology Refinement*) on **2026-07-29** per Authority disposition of Knowledge Contract Review finding **KC-8** and recommendation **R-1**. |
| **Authority** | **NONE — this is a historical record.** It documents how the ADR came to say what it says. It is not architecture, not a decision, and not authority. |
| **Why it was extracted** | **KC-8 — knowledge classification: review history was embedded as artifact content.** This program's practice throughout is that **findings apply to the object; the record that raised them stands separately.** Embedded review history also created a **second, competing account of the ADR's status** alongside its metadata block. **The ADR's metadata block is now its only status statement.** |
| **Companion documents** | `PKS_ADR_001_Companion_Illustrative_Realizations.md` (non-normative illustrations) · `PKS_ADR_001_Knowledge_Contract_Review.md` (the current whole-artifact review) |

---

## 1. Review history

| Round | Date | Instrument | Outcome |
|---|---|---|---|
| **1** | 2026-07-29 | ARB review (first pass) | **Approved with minor revisions** — four revisions required and applied: implementation-agnosticism clarified as applying primarily to the *strategic architecture* while governance artifacts are preserved for different reasons · Appendix B's disclaimer strengthened · an **Architectural Limits** section added · the decision statement tied to the governance process (*"until amended through established governance"*) |
| **2** | 2026-07-29 | Section-by-section ARB review, §§1–4 | Sections 1–3 disposed and corrected (see §2); Section 4's findings subsumed into round 3 |
| **3** | 2026-07-29 | **Knowledge Contract Review** under ARB Review Discipline **v2.0** | **REVISE** — 6 Major · 6 Minor · 1 Question · 2 recommendations. All KC findings accepted; Q-KC-1 answered *describe only*; Appendix B extraction sequenced first. Record: `PKS_ADR_001_Knowledge_Contract_Review.md` |

**One finding remains undisposed and is deliberately not closed here: F-3.2**, deferred to the Authority — recorded in the ADR's own §3.1 and Appendix D item 4.

---

## 2. Terminology refinement record — overclaims corrected, retained as an anti-drift reference

**Retained because it has genuine anti-drift utility:** it names the exact overclaims that were removed, so a future revision can recognize them if they reappear. **It is history, not a decision** — which is why it lives here.

| Overclaimed statement (superseded) | Refined statement (in force) |
|---|---|
| *"Not a single artifact needs to be rewritten"* | The strategic architecture remains valid without strategic rediscovery; implementation-specific guidance may be added in the implementation program |
| *"Universal mapping"* | Non-normative illustrative realizations *(now in the illustrations companion)* |
| *"Modules ↔ Agents"* | Modules and agents provide analogous but **not equivalent** separation mechanisms |
| *"Regardless of implementation stack"* | The strategic architecture **as disposed** remains invariant across implementation paradigms; only the realization strategy changes *(KC-13 cascade, 2026-07-30: this column holds statements **in force**, and "certified strategic architecture" carried the defect — certification attaches to the methodology. The left column's superseded text is historical evidence and is untouched)* |
| *"Governance records are implementation-agnostic"* | Governance records document the transformation, not the architecture, and are preserved on that basis — **except the four named in ADR §3.1.1, which do establish enduring constraints** |

**Round-3 addition to this record:** the failure mode observed across the three rounds **migrated** — round 1 found *overclaiming in prose*; round 3 found *content invention in appendices*, where disclaimers create the impression that ordinary rules are suspended. **They are not: a non-normative statement is still a statement.** That observation is the reason the illustrations companion carries a standing constraint of its own.

---

*Traceability: extracted from PKS-ADR-001 §14 and Appendix C on 2026-07-29 under Authority disposition of `PKS_ADR_001_Knowledge_Contract_Review.md` finding KC-8 and recommendation R-1 · historical record; carries no authority · the ADR's metadata block is its sole status statement.*
