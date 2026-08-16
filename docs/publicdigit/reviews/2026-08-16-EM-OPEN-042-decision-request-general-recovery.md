# Decision Request — `EM-OPEN-042`: general recovery for an ordinarily halted election

**Type:** Governance decision request (Session 2) · **Date:** 2026-08-16 · **Business language only**
**⛔ Nothing adopted. No recommendation on the substance. `EM-GOV-037` untouched. Architecture ⏸️ · Session 3 🛑.**

---

## 1 · The question, in plain terms

> **When an election is legitimately halted because a required condition cannot currently be satisfied — who is responsible for getting it moving again, what must they do, and what happens if nobody can restore it?**

## 2 · Two of those three are ALREADY ANSWERED by rules in force

| Part | Status |
|---|---|
| **Who is responsible?** | ✅ **ANSWERED — `EM-GOV-014` Part 1:** *a halted election requires a governed recovery process; **the Election Chief is responsible for initiating it**; no recovery action may bypass any Election Rule or reuse invalidated decisions.* |
| **What must they do?** | ✅ **LARGELY ANSWERED — `EM-GOV-013`:** *a halted phase **may** be recovered through governed rescheduling; a reschedule creates new governed schedule conditions and requires protocol recording; dependent phases are reviewed and rescheduled **where their validity depends on the changed predecessor**.* ⚠️ **Note the word *may*: rescheduling is **a** path, not stated to be the only one.** |
| **What if nobody restores it?** | 🔴 **THE ONLY GENUINELY OPEN PART — and it is the whole of `EM-OPEN-042`.** |

## 3 · What the rules in force already EXCLUDE

**The answer must fit inside these; none is open for reconsideration here:**

* **`EM-GOV-012`** — **a halted progression does not by itself define the election-level outcome.** So **cancellation can never follow from the halt alone.**
* **`EM-OPEN-047`** — **expiry of a recovery period is a recorded EVENT; its meaning is determined by Election Governance rules and must never be silently converted into an automatic election outcome by service configuration.** ⚠️ **This is precisely why `EM-OPEN-042` cannot be answered by default: expiry currently means nothing.**
* **`EM-GOV-011` + `052`** — the preceding phase remains completed; **no downstream phase is executed or given an artificial outcome.**
* **`EM-VOC-005` / `EM-GOV-009`** — any schedule change is a **governed correction creating a new opportunity**; nothing carries across.
* **`SCB-4`** — ⛔ **the lifecycle may NOT return to a phase that has legitimately completed**, so *"restart from an earlier phase"* — an option on the original `EM-OPEN-042` list — **is already excluded.**
* **`EM-OPEN-081`(C)** — the acceptance model may not be changed after the fact.
* **`EM-GOV-062`** — the halted clock **runs only while the election is operative and halted.**

## 4 · ⚠️ The model already contains an answer to a structurally identical question

**`EM-GOV-058` (in force) answers the same shape for Committee failure:**

> *Committee unable to function → **Election Inoperative** → restored, or **cancelled** if restoration does not occur within the applicable service-policy period.*

> ### **So the real decision is narrower than it appears: does the ordinary halted case FOLLOW that shape, or DIVERGE from it — and if it diverges, why?**
> **Following it would give: halted → Chief's recovery → cancelled if not recovered within the halted-phase period.**
> **Diverging would require a reason that distinguishes an ordinary halt from a governance-structure failure.** ⚠️ **And there is a candidate reason, offered as analysis rather than as a recommendation: a Committee failure is a defect in the election's CAPACITY TO ACT, whereas an ordinary halt is a failure to SATISFY A CONDITION — and a condition may be unsatisfiable for reasons that no recovery could ever repair** *(a post with no candidates, an electorate that cannot be established)*. **Whether that difference should produce different consequences is the PO's to say.**

## 5 · What is genuinely open

**(a)** **What does expiry of the halted-election recovery period MEAN?** *(cancellation · escalation · the election remains halted indefinitely · a reason-dependent rule · another governed outcome.)*
**(b)** **Is there an ESCALATION before any terminal consequence** — does anyone get a turn after the Chief has not acted? ⚠️ **Note the constraint already in force: the Committee cannot acquire powers by default, and the appointing authority is external.**
**(c)** **Does the answer depend on the REASON for the halt?** *(a mandatory condition unmet · an acceptance refused · attrition · a schedule that elapsed unnoticed.)* **`EM-GOV-011` records the failed condition and its reason, so the record can support a reason-dependent rule if one is wanted.**
**(d)** ⚠️ **`EM-OPEN-042`(a) and `EM-OPEN-102` OVERLAP and should be decided together.** **`102` is the bounded-wait rule; its INTERNAL half asks what happens when the responsible actor does not act — which is exactly this question.** **Answering them separately risks two different rules for one situation.**

## 6 · Governance's position

**None on the substance.** Governance has traced the question against the rules in force, found that **two of its three parts are already answered**, narrowed the third, identified the structurally identical precedent, and named the overlap with `EM-OPEN-102`.

**Traceability.** `EM-GOV-009`/`011`/`012`/`013`/`014`/`052`/`058`/`062` · `EM-VOC-005` · `SCB-4` · `EM-OPEN-047`/`081`(C)/`102`.
