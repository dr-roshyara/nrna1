# **BRM-1 — Commission: Baseline Representation Model Decision**

| | |
|---|---|
| **Act** | **COMMISSION of an Authority decision** (Authority instruction, 2026-07-31) |
| **Label** | **BRM-1.** *PMR-10 (GOVERNED) collision check performed: `BRM-1` · `BRMD-1` · `REP-MODEL-1` all verified unused* |
| **Origin** | ***SDM-EXT-1 could not proceed. Not for missing paperwork — because it uncovered a genuine ARCHITECTURAL FORK and stopped before deciding it silently through implementation*** |
| **THE QUESTION** *(Authority's, verbatim)* | ### **Should the PKS governance baseline remain reference-defined (Model B), or should it transition to a materialized baseline (Model A)?** |
| **Status** | 🏁 **DECIDED 2026-07-31 — RETAIN MODEL B.** *Record: `PKS_BRM_1_Decision.md`. SDM-EXT-1 RETIRED as a consequence.* |

---

## 1. The two models, as the Authority framed them

| | **Model A — MATERIALIZED** | **Model B — REFERENCE-DEFINED** *(current)* |
|---|---|---|
| **Shape** | canonical rules → **copied into SDM** → maintained together | canonical rule → **referenced everywhere** |
| **Advantages** | easier to read · easier to hand over · fewer hyperlinks | **one authority · no textual drift · simpler governance** |
| **Disadvantages** | **duplicated governance text · potential drift · Assembly Fidelity burden** | more navigation · references instead of complete text |

---

## 2. ⚠️ THE BURDEN IS ON THE CHANGE

> ### **THE BURDEN IS ON THE PROPOSED CHANGE to demonstrate benefits that JUSTIFY INTRODUCING A NEW GOVERNANCE RISK NOT PRESENT IN THE CURRENT ARCHITECTURE.**

*(Authority formulation, 2026-07-31 — extends the reviewer's bare* ~~*"the burden is on the change"*~~*, which stated where the burden sits without stating what it is.)*

**The balancing test made explicit: the question is NOT *"is extraction useful?"* but *"are the benefits sufficient to justify reintroducing duplicated-governance risk?"***

**⚠️ And what this is NOT: a presumption that Model B is SUPERIOR.** ***It is the ordinary architectural principle that the existing architecture does not have to justify its own existence every time a change is proposed. The proposed change must justify itself.***

***The decision is not "can we extract?" but "why should the architecture STOP being reference-defined?" A decision framed the other way would place the burden on the status quo, which no evidence supports.***

---

## 3. Two justifications are UNAVAILABLE, and the decision should not reach for them

| Unavailable | Why |
|---|---|
| **COMPLETENESS** | ⛔ **CDR §4.2 itself records that *"the record is complete and citable as it stands."*** ***If completeness already exists, extraction cannot be justified by it*** |
| **ENABLING VERIFICATION** | ⛔ **The Authority has ruled that consolidation must NOT be undertaken to enable a verification.** *ISV-1's derived-text point is a consequence of extraction, never a reason for it* |

**What remains available:** *usability · maintainability · discoverability · onboarding · tooling* — **and any such justification must OUTWEIGH the reintroduced drift-risk class, not merely exist.**

---

## 4. ES-005.4 — usable as authority in NEITHER direction

**ES-005.4 *"Never a Copy"* is DEFERRED** *(2026-07-31: adoption unavailable pending ARB scope confirmation)*.

| | |
|---|---|
| It does **NOT prohibit** extraction | *a deferred rule does not bind* |
| It does **NOT support** Model B either | ***a deferred rule cannot be cited as authority for the position it would have supported*** |

> ### ***A deferred rule is not a weak rule pointing one way. It is silent. Citing it in either direction would grant governance force to something the Authority expressly declined to make binding.***

---

## 5. Evidence of the programme's accumulated DIRECTION — a fact, not a verdict

**Five governance acts, all of which reduce duplicated text, and all decided independently of this question:**

| Act | Effect on duplication |
|---|---|
| **Verbatim adoption** *(PMR-5, PMR-9, PMR-10 issuances)* | wording carried, never restated |
| **The Assembly Fidelity Rule** — GOVERNED | assembly artifacts may not modify inherited normative wording |
| **PMR-9** — GOVERNED | classification must verify the source, not a copy of it |
| **Per-rule governance** *(PMR-7 split · ES-005 per-rule adoption)* | authority attaches to the rule, not to a document restating it |
| **The eleven-site KC-13 cascade** *(PMR-5's own evidence)* | ***restatement creates one defect site PER SITE*** |

> ### ***Recorded as EVIDENCE OF DIRECTION, expressly NOT as the decision. Five acts pointing one way is a fact about the programme's history; it is not an argument that the direction is correct, and the Authority may decide against it on grounds none of those acts considered.***

---

## 6. What follows from each outcome

| If **Model B** is retained | If **Model A** is adopted |
|---|---|
| ⛔ **SDM-EXT-1 is RETIRED** — *not deferred; its premise fails* | **SDM-EXT-1 becomes executable, and its §1 question must STILL be answered: did CDR §4.2 authorize an ACT TYPE or a NAMED VERSION (v1 vs v1.2)?** |
| The baseline stays defined by reference; navigation cost is accepted knowingly | **The four §4 constraints bind: zero new design content · every statement VERBATIM with verification before the claim · forward-only supersession · and the DRIFT QUESTION answered — which artifact governs on conflict** |
| **ES-005.4's scope confirmation becomes more consequential**, since "Never a Copy" would then align with the retained architecture | ***A second home is created for every governed statement, and the Assembly Fidelity Rule binds the extraction permanently, not once*** |

---

## 7. What is NOT within this commission

**Executing or retiring SDM-EXT-1 · answering the v1-vs-v1.2 question · confirming ES-005.4's scope · adopting STANDARDS_INDEX · classifying repository roots · amending any baseline · any act against a promoted artifact.**

## 8. No recommendation offered

***The Authority has already established that the burden is on the change, that two justifications are unavailable, and that a deferred rule cannot be cited either way. A recommendation would add nothing the framing does not already supply — and would risk supplying it as authority.***

---

*Traceability: **BRM-1 COMMISSIONED** (Authority instruction, 2026-07-31), label collision-checked under PMR-10 · **origin: SDM-EXT-1 could not proceed because it uncovered an ARCHITECTURAL FORK and stopped before deciding it silently through implementation** · **THE QUESTION taken verbatim: should the baseline remain reference-defined (Model B) or transition to materialized (Model A)?** · **§2 THE BURDEN IS ON THE CHANGE — Model B already exists, so the question is not "can we extract?" but "why should the architecture STOP being reference-defined?"** · **§3 TWO JUSTIFICATIONS UNAVAILABLE: COMPLETENESS (CDR §4.2 records the record is already "complete and citable as it stands") and ENABLING VERIFICATION (ruled out by the Authority); what remains — usability, maintainability, discoverability, onboarding, tooling — must OUTWEIGH the reintroduced drift-risk class, not merely exist** · **§4 ES-005.4 is usable as authority in NEITHER direction: *a deferred rule is not a weak rule pointing one way — it is SILENT, and citing it either way would grant governance force to something the Authority expressly declined to make binding*** · **§5 five governance acts all reducing duplicated text recorded as EVIDENCE OF DIRECTION and expressly NOT as the decision — *a fact about the programme's history, not an argument that the direction is correct*** · **§6 consequences of each outcome, including that Model B RETIRES SDM-EXT-1 (premise fails, not deferred) while Model A leaves the v1-vs-v1.2 question still to answer** · no recommendation offered; nothing decided.*
