# Consolidated Adoption Act v4 — the Acceptance / Election Committee cluster

**Prepared by Governance (Session 2) · 2026-08-16 · PREPARED, NOT ADOPTED**
**⛔ Consolidation only. No rule was redesigned, no resolved question reopened, no new policy made. No status was changed to ADOPTED. No Architecture or implementation work.**
**⛔ `EM-OPEN-042` is NOT in this act and remains pending. It is opened only after v4 is adopted.**

---

## 1 · The act

> **PO/ARB ADOPTION ACT v4 — Acceptance Governance baseline, 2026-08-16**
> **I adopt the following as Election business rules, together with the two amendments they carry.**

### A · The Election Committee
**`EM-GOV-029`** definition — independent and impartial, appointed by the organisation's governance body, participating in acceptance and providing an independent governance check; members independent and unbiased as to candidates and outcome; **a member may not simultaneously serve as a candidate's representative**.
**`EM-GOV-033`** at least **three** eligible, independent members; each holds exactly one Committee Vote.
**`EM-GOV-056`** an unavailable member creates a **vacancy filled by the authorised organisational governance authority** — filling a vacancy, **not** reconstituting the Committee; it creates no new membership, reopens no Election Appointment, and alters no decision already made.
**`EM-GOV-057`** Committee acceptance is calculated against the **constituted** membership; a vacancy does not reduce the denominator or the threshold.

### B · Acceptance models
**`EM-GOV-025`** *(as amended by `EM-GOV-048`)* — the election application establishes **two separate things**: the **participation model** and the **decision rule**. **Committee votes are not simply added alongside representation votes.**
**`EM-GOV-048`** **Model B (representatives only) is retired** as a standalone acceptance model — on evidence, not on a premise. Representatives provide stakeholder trust and transparency; the Committee provides the institutional fallback and governance authority.
**`EM-GOV-030`** **`Committee Vote ≠ Representation Vote`** — the two are never called by one name.
**`EM-GOV-053`** the two vote types are **never combined numerically**, and the Committee **never casts a Representation Vote**.

### C · Thresholds
**`EM-GOV-031`** *(as amended by `EM-GOV-055`)* — **separate thresholds**, evaluated independently; acceptance requires both, **except** where substitution applies under `EM-GOV-054`.
**`EM-GOV-032`** the model and each threshold are **selected during Election Application from the choices the Election Rules permit** and recorded as part of the governing configuration — never free-form.
**`EM-GOV-034`** **unanimity is not an available threshold**, for either vote type.
**`EM-GOV-035`** the configuration records **the RULE, not a number**, and is evaluated against the actual population at Voting Preparation.
**`EM-GOV-036`** the Committee threshold rule is **`TWO_THIRDS_OF_COMMITTEE_VOTES`, rounded up**, over a Committee of at least three.

### D · Representatives (trustees)
**`EM-GOV-040`** a representative is a **TRUSTEE, not a delegate** — the decision is their own governed assessment, not an instruction or vote cast on behalf of the selecting candidates; weight represents **trust placed**.
**`EM-GOV-039`** a representative carries one vote per candidate represented; **the Chief seeks common representation but does not choose it**; where it cannot be established the Chief records that fact and the Committee decides under its predetermined model.
**`EM-GOV-041`** selection is **revocable during Voting Preparation, binding from its close, never retroactive**.
**`EM-GOV-046`** **one freeze governs both acceptance gates**; no reselection or replacement between them.
**`EM-GOV-043`** the denominator is **fixed by the commitment** and does not change on later acceptance, objection, abstention, resignation or unavailability; **no selection creates no vote**; **five states kept distinct**.
**`EM-GOV-042`** *(as corrected)* — unavailability after the freeze means the votes are **not cast**, do not transfer, and permit no replacement. ⚠️ **Its original cast-vote calculation is NOT adopted; `EM-GOV-043`'s configured denominator governs.**
**`EM-GOV-044`** a representative may **decline before the freeze** (permitting re-selection); **resignation after it permits no replacement**.
**`EM-GOV-045`** a post-freeze resignation **must carry a recorded reason**, which is **recorded but not approved or evaluated**.

### E · Attrition, recovery and substitution
**`EM-GOV-047`** where attrition makes the threshold **mathematically impossible**, the Committee may **require the affected candidate to nominate** an eligible replacement; **the Committee does not choose the trustee**; no candidate-initiated replacement; threshold and denominator unchanged.
**`EM-GOV-049`** **recovery restores exercise, not shape** — the candidates represented, denominator, threshold and rule are unchanged; which trustee exercises each vote may change.
**`EM-GOV-050`** impossibility is evaluated **at the acceptance gate**, not continuously.
**`EM-GOV-051`** the gate **halts** during recovery and resumes under the same rule.
**`EM-GOV-054`** **substitution only for an incapable channel** — never an override of a channel that operated and rejected; the Committee must establish and record impossibility and exhausted recovery first.
**`EM-GOV-055`** the amendment to `EM-GOV-031` carrying that exception.

### F · Gate and progression semantics
**`EM-GOV-052`** **AMENDMENT to adopted `EM-GOV-011`** — *when a mandatory acceptance gate cannot be satisfied, the next phase may not begin; the preceding phase remains completed; progression is halted at the gate condition.*
**`EM-GOV-059`(c)** resumption returns to the **unresolved gate** — restoring an authority restores the **ability to decide**, not the decision.

### G · Committee failure
**`EM-GOV-058`** Committee failure ⇒ **`ELECTION INOPERATIVE`**; the election resumes on restoration; **if restoration does not occur within the applicable service-policy recovery period, the election is cancelled.** *(Not "suspended", not "inactive".)*
**`EM-GOV-059`(a)(b)** **separate service-policy parameters** for halted-phase recovery and Committee restoration, with the configured value and policy version recorded; **Inoperative is dominant but does not erase the halt**.

### H · Recovery clocks
**`EM-GOV-060`** on Inoperative the halted clock **pauses**; the Committee clock runs; the **remaining** portion resumes on restoration; non-retroactive.
**`EM-GOV-061`** ***recovery can pause a clock when the election cannot act, but can never create new time merely because the same failure happens again*** — no minimum on resumption; the restoration allowance is **election-level, not per-failure**.
**`EM-GOV-062`** each clock **runs only while its triggering condition is active** and pauses otherwise; neither is a countdown on the election's lifetime.

> **— Signed: PO/ARB, 2026-08-16**

**Amendments carried:** `EM-GOV-011` *(by `EM-GOV-052`)* · `EM-GOV-022` *(by `EM-GOV-040`: "no person has a veto merely because they personally hold a role; any blocking effect must arise from the aggregate votes entrusted to them under the predetermined threshold")* · `EM-GOV-031` *(by `EM-GOV-055`)*.

---

## 2 · Scope statement

**v4 adopts 33 rules and 3 amendments** — the Committee, the acceptance models, the threshold framework, the trustee model, attrition/recovery/substitution, gate semantics, Committee failure, and the clock model.

### ⛔ Deliberately EXCLUDED

| Item | Reason |
|---|---|
| **`EM-GOV-037`** *(representative threshold rule + applicability floor)* | ⛔ **EXCLUDED — NEW POLICY REQUIRED.** Its floor is not settled: `EM-OPEN-075` was narrowed but held open, `EM-OPEN-076` (which rules the menu may contain) is open, and `EM-OPEN-077`'s three consequences are unresolved. |
| **`EM-OPEN-042`** | ⛔ **Outside v4 by instruction; opened only after adoption.** |
| **`EM-OPEN-066`** | ⛔ **External dependency. The organisational appointing authority is referenced throughout and nowhere invented or named.** |

> ### ⚠️ ONE CONSEQUENCE OF EXCLUDING `EM-GOV-037`, STATED PLAINLY
> **Several included rules reference "the applicable representative threshold" — `EM-GOV-031`, `EM-GOV-047`'s impossibility trigger, `EM-GOV-054`'s substitution condition.** **With `EM-GOV-037` excluded, that threshold is not defined.**
> **So v4 adopts the acceptance FRAMEWORK, and the representative channel is not OPERABLE until `EM-GOV-037` is settled.** **The Committee channel is complete and operable** *(`EM-GOV-036` supplies its rule)*. **This is a real limitation of v4 and is not concealed.**

## 2a · FINAL DEPENDENCY CHECK on `EM-GOV-037` *(requested before signature)*

**Test applied:** does any included rule depend on `EM-GOV-037` in a way that makes its adoption **misleading or internally non-operative** — as distinct from merely **referencing an applicable threshold that will exist later**?

| Class | Rules | Verdict |
|---|---|---|
| **Fully operative now — no threshold needed** | `029` `030` `033` `036` `040` `041` `042` `043` `044` `045` `046` `048` `049` `052` `053` `056` `057` `058` `059` `060` `061` `062` | ✅ **Adoptable.** They define bodies, vocabularies, configuration, freezes, states and clocks — none evaluates a threshold. |
| **Conditional triggers** | `047` *(triggers only where the threshold is already impossible)* · `050` `051` `054` `039` | ✅ **Adoptable.** Each describes what happens **when a condition arises**; the condition cannot arise before a threshold exists, so the rule is dormant rather than wrong. |
| **Generic over whatever rules exist** | `032` `034` `035` | ✅ **Adoptable — and they CONSTRAIN `EM-GOV-037` in advance:** with `034` (no unanimity) and adopted `EM-GOV-038` (round up), **v4 NARROWS what `EM-GOV-037` may later become rather than leaving it open.** |
| **Constitutive reference** | `031` + its amendment `055` | ⚠️ **Adoptable, but inoperative for Model C.** They state that acceptance requires **both** thresholds; with the representative threshold undefined, **Model C cannot be configured.** **Not misleading — the rule is correct and simply cannot be exercised**, exactly as `EM-GOV-025`'s models cannot be configured while the appointing authority is external. |

> ### ✅ **RESULT: no rule requires further exclusion.** **The dependency is confined to Model C's CONFIGURABILITY.**
>
> ### ✅ **AND THE CHECK PRODUCES A POSITIVE FINDING WORTH STATING: v4 MAKES MODEL A (Committee only) COMPLETE AND OPERABLE.**
> **Model A needs `029` `030` `033` `036` `052` `056` `057` `058`–`062` — all included — and none of the representative machinery applies to it.** **Its only outstanding dependency is the EXTERNAL appointing authority (`EM-OPEN-066`), which is not a Governance gap.**
> **So v4 does not merely record a framework: it delivers one working acceptance path and defers the other to `EM-GOV-037`.**

## 3 · Traceability

| Rule | Originating decision / resolved question |
|---|---|
| `029` `033` `056` `057` | `EM-OPEN-066` narrowed · `EM-OPEN-069` (load-bearing half) · `EM-OPEN-100` · `EM-OPEN-101`① |
| `025` `048` | `EM-OPEN-079` *(retired on the three gaps at `EM-OPEN-078`, `EM-GOV-037`, `EM-OPEN-093`①)* |
| `030` `053` | `EM-OPEN-062` · `EM-OPEN-097` |
| `031` `055` | `EM-OPEN-070` · `EM-OPEN-098` |
| `032` `034` `035` `036` | `EM-OPEN-072` · `EM-OPEN-074` *(with adopted `EM-GOV-038`)* |
| `039` `040` `041` `042` `043` `044` `045` `046` | `EM-OPEN-060` · `083` · `084` · `085` · `086` · `087` · `088` · `090` |
| `047` `049` `050` `051` `054` | `EM-OPEN-092` · `093` · `095` · `099` |
| `052` `059`(c) | `EM-OPEN-096` · `EM-OPEN-105` |
| `058` `059`(a)(b) | `EM-OPEN-103` · `EM-OPEN-104` |
| `060` `061` `062` | `EM-OPEN-106`① · `107` · `108` |

## 4 · Consistency check against rules already adopted

✅ **`EM-GOV-005`** — every recording obligation in v4 is satisfied by it and its negative half; **no new recording rule was created.**
✅ **`EM-GOV-006` / `EM-OPEN-047`** — the Service-Provider boundary is preserved: **policy supplies durations; it never defines electoral meaning or consequence.**
✅ **`EM-GOV-009` / `010` / `019` / `026`** — the phase chain is untouched; **acceptance gates remain gates, not phases.**
✅ **`EM-GOV-011`** — amended **explicitly** by `EM-GOV-052`, not silently.
✅ **`EM-GOV-012`** — cancellation under `EM-GOV-058` follows a **structural failure**, not a halt, so the rule that a halt does not determine the election-level outcome is intact.
✅ **`EM-GOV-014` Part 2** — no numeric duration enters the Election Rules; `EM-GOV-059`(a) adds a **second** service-policy parameter and extends the recording obligation to it.
✅ **`EM-GOV-022`** — amended **explicitly** by `EM-GOV-040`.
✅ **`EM-GOV-038`** — v4's threshold rules use its adopted rounding; nothing overrides it.
✅ **`P-2H` / `EM-VOC-004` / `EM-VOC-008`** — the two-histories separation and the five distinguishable outcomes are preserved; **`EM-GOV-043`'s five states are the same discipline applied to representatives.**
✅ **`ADR-T11`** — nothing in v4 requires a voter↔vote linkage; the constraint on the resignation-reason field (`EM-OPEN-091`②) stands and is **not** resolved here.
⚠️ **`EM-GOV-042`** is included **as corrected** — its original cast-denominator calculation is expressly not adopted, so v4 does not silently carry a superseded clause.

## 5 · Unresolved after v4

**`EM-OPEN-042`** general recovery *(next, after adoption)* · **`EM-GOV-037`** + `EM-OPEN-075`/`076`/`077` *(representative threshold, floor, permitted menu)* · **`EM-OPEN-066`** external appointing authority · **`EM-OPEN-071`** Committee interpretive authority · **`EM-OPEN-069`** remaining eligibility combinations · **`EM-OPEN-055`**/`060` residues · **`EM-OPEN-091`**② `ADR-T11` and the reason field · **`EM-OPEN-093`**②③ shared-trustee weight splitting; gate duration · **`EM-OPEN-095`** gate intermediate state; partial recovery · **`EM-OPEN-099`**②③ resignation as a transfer vector; the substitution recording point · **`EM-OPEN-102`** the bounded-wait rule · **`EM-OPEN-106`**② the cascade.

## 6 · Post-v4 next step

> **`EM-OPEN-042` — the general recovery behaviour for an ordinarily halted election — is the next Governance question, and ONLY after v4 has been formally adopted.**
> **It will then be answered against rules in force rather than against proposals.**

**Architecture ⏸️ PAUSED · Session 3 🛑 STOPPED · Session 1 must not verify unauthorized implementation.**
