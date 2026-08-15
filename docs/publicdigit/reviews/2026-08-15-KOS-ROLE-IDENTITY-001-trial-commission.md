# KOS-ROLE-IDENTITY-001 — PO/ARB decision registered · bounded `ALT-2` trial commissioned

**Date:** 2026-08-15 · **Registered by:** Session 2 (Governance)
**`ALT-2` is NOT adopted as policy.** No provenance infrastructure created · no `AST-015`/`AST-016` reopening · no workflow change · no startup wiring · no authorization enforcement.

```
─────────────────────────────────────────────────────────────
 ILLUSTRATIVE PROVENANCE BLOCK — TRIAL INSTANCE #1
 (illustrative format, NOT an adopted standard — see §4)
 Responsibility : governance
 Operator       : Session 2 (Governance)            [declared]
 Approver       : PO/ARB — decision of 2026-08-15   [declared, this artifact §1]
 Evidential     : DECLARED, not attested (INV-ATTR-2).
                  Evidence only. NOT proof of authority,
                  correctness, independence or approval.
─────────────────────────────────────────────────────────────
```

> **⚠️ `A-5.2` disclosure.** This process produced the `DEP-3` ADP (`a5b571df`) and its Governance review (`4ad09fa1`), and now registers the decision on both. **Status: ASSERTED, not attested.** Independent contribution of *this* act: none claimed — it is registration, not review.

---

## 1 · The decision, registered verbatim

> **"APPROVE.**
>
> *I accept the Governance review and the Architecture investigation as sufficient for the current decision.*
>
> *I do **not** adopt ALT-2 as policy and do **not** authorize implementation of provenance infrastructure.*
>
> *I authorize a **bounded trial of ALT-2** to gather practical evidence before the 30 August 2026 review.*
>
> *The trial must remain non-authoritative: recorded responsibility, operator and approver information is evidence only and must not be treated as proof of authority, correctness or independent judgement.*
>
> *The illustrative provenance format must remain explicitly labelled **illustrative**. The exact fields and any future standard will be decided only after the trial evidence has been reviewed.*
>
> *The 30 August review shall report what the trial demonstrated, including negative or incomplete results, and shall distinguish clearly between: what can be established by 30 August; and what would require a separately authorized future implementation.*
>
> *No reopening of AST-015 or AST-016, no workflow changes, no startup wiring, and no authorization enforcement are approved by this decision."*

**Refinement, registered verbatim:**

> *"The trial is intended to test whether the proposed practice provides useful transparency and responsibility tracking in real work; it is not intended to test or establish independence, authority, correctness, or approval."*

## 2 · The trial question

> ### **"Does recording responsibility in this way give us useful additional transparency in real work, without creating false confidence?"**

**This is the whole question.** The trial does **not** ask whether the process is independent, and **cannot** answer that — `Q4` is not answerable by identity at any strength.

## 3 · Why no grant and no new work item were created

**Governance considered both and issued neither, deliberately.**

- **A grant authorizes an act.** The trial **authorizes nothing new** — it asks that artifacts already being produced carry one extra block. It confers no authority, creates no capability, and permits no act that was not already permitted. **A grant would misrepresent a documentation convention as an authorization.**
- **A new work item would need an assignment and a lane**, but **nobody "performs" this trial as a task.** It is a practice applied *during* other work, by whichever lane happens to be producing an artifact. Registering a session to hold it would invent a fictional actor.

**The trial is therefore registered as a PROTOCOL under the existing work item `KOS-ROLE-IDENTITY-001`** (OPEN · `mutationOwner: NULL` · `S4` COMPLETED). **Governance's evidence-compilation duty already exists** under `A-6.4` and needs no new instrument.

**It is deliberately NOT registered as an amendment to the orchestration rulebook.** `A-4`, `A-5` and `A-6` are **policy**. This is **explicitly not policy** — recording it beside them would grant it policy appearance and contradict the decision. A **pointer** marked *TRIAL · NOT POLICY · expires 2026-08-30* is added to the rulebook header **for discoverability only**.

## 4 · The illustrative format — labelled, as required

```
Responsibility : <architecture | implementation | verification | governance>
Operator       : <who ran this>                    [declared]
Approver       : <whose act authorized it>         [declared, by commit/artifact ref]
Evidential     : DECLARED, not attested (INV-ATTR-2).
                 Evidence only. NOT proof of authority, correctness,
                 independence or approval.
```

> **🔖 ILLUSTRATIVE — NOT AN ADOPTED STANDARD.** Field names, ordering and wording are **provisional**. **The exact fields and any future standard will be decided only after the trial evidence is reviewed.** Deviations during the trial are **data, not violations** — if a lane finds a field useless or a different field necessary, **that is exactly the evidence being sought.**

## 5 · Trial boundary

| | |
|---|---|
| **Period** | 2026-08-15 → **2026-08-30** (ends at the review, per `A-6.8`) |
| **Applies to** | Governance and Architecture artifacts produced in `docs/publicdigit/reviews/` during the period |
| **Applied by** | Whichever lane produces the artifact — no assignment, no grant |
| **Status** | **Non-authoritative. Evidence only.** Never cited as proof of authority, correctness, independence or approval. Never a gate. Never read by any mechanism |
| **Not in scope** | Any code · `AST-015`/`AST-016` · `workflow-state.php` · `session-resolve.php` · hooks · `SESSION_START` · git configuration · authorization enforcement · the runtime record schema · retrofitting past artifacts |

## 6 · Evidence expectations — what the 30 August review will report

**Defined now so the review reports measurements rather than impressions.**

| # | Evidence | How it is obtained |
|---|---|---|
| **E-T1** | **Completion rate** — qualifying artifacts carrying the block ÷ qualifying artifacts produced | count over `docs/publicdigit/reviews/` in the period |
| **E-T2** | **Accuracy** — does the declared operator/approver match the act the artifact actually records? | inspection against the artifact's own traceability |
| **E-T3** | **Operational effort** — observed friction: was it filled thoughtfully, mechanically, or skipped? | recorded observation, including honest self-report |
| **E-T4** | 🔴 **False-confidence incidents** — any instance where the block was read, cited or relied on as attestation rather than declaration | recorded when observed; **this is the risk the trial most needs to detect** |
| **E-T5** | **Deviations** — fields found useless, missing or reworded | recorded as data (§4) |
| **E-T6** | **Q2/Q3 information content** — did operator/approver ever differ from the expected single human? | direct check of `C-1`'s prediction |

> **Negative and incomplete results are first-class outcomes and MUST be reported**, per the decision. **If the block is skipped, filled mechanically, or proves useless, that is a valid and valuable trial result** — arguably more valuable than success, because it would settle `ALT-2` cheaply. **Governance will not present a tidy result it did not observe.**

**`E-T6` carries a stated prediction:** `C-1` argued operator and approver will be near-tautological while one human directs every lane. **If that prediction holds, `ALT-2`'s near-term value is Q1 + habit-building only — as the decision's refinement already anticipates.** Recording the prediction now prevents it from being reinterpreted after the fact.

## 7 · What the 30 August review must separate

Per the decision, in two explicit parts:

**A · Establishable by 2026-08-30:** `E-T1`–`E-T6` · the no-person-axis finding · per-lane git identities do not work in a shared worktree (measured twice) · identity ≠ independent judgement · the `A-5.2` disclosure count.

**B · Requires separately authorized future implementation:** `DEP-3.1` operator field · `DEP-3.2` approver field *(both reopen qualified `AST-015`; both inside the `DEP-1`/`D-2`/`D-6` family `P-6` keeps unbatched)* · `DEP-3.3` signing with enforced key isolation · `DEP-3.4` `AST-016` surfacing (reporting-only per `A-5.5`). **None authorized.**

## 8 · The line this trial must not cross

> **The trial must not quietly become implementation.**

**Nothing is built.** Applying a text block to a document is **not** provenance infrastructure. **If the trial begins to require tooling, a schema, a validator, a hook, or a mechanism change — it has left its boundary and must STOP and return to the PO/ARB.**

**Precedent this guards against:** `A5` was adopted as a deferral before its trigger existed, and took two further rounds to repair. **The same run-ahead-of-evidence error is the one thing this trial is shaped to avoid.**

## 9 · Scope

**Not done:** `ALT-2` **not adopted as policy** · no provenance infrastructure · no code · no `AST-015`/`AST-016`/`workflow-state.php`/`session-resolve.php` change · no hooks · no `SESSION_START` wiring · no git configuration change · no authorization enforcement · no grant issued · no new work item · no assignment created · no past artifact retrofitted.

**Untouched:** `V-3` · `D-2` · `D-6` · `E-1` · `O-CLOSURE-VOCAB` · the bootstrap gap · Election work. `KOS-SESSION-DISCOVERY-001` and `KOS-EXEC-TOPOLOGY-001` remain closed. `OBS-1` recorded, not resolved.

**Work item `KOS-ROLE-IDENTITY-001`: OPEN · `mutationOwner: NULL` · `S4-architecture-role-identity` COMPLETED · trial running to 2026-08-30.**

---

## 10 · Trial-conduct rule (PO/ARB, 2026-08-15) — registered verbatim

> ### *"Do not try to make the trial succeed. Let it succeed, fail, be ignored, be inconvenient, or prove useless. All of those are valid evidence."*

**Binding on everyone applying the block, and on Governance when compiling.** Concretely:

- **No chasing completion.** A missing block is **`E-T1` data**, not a gap to backfill. **Nobody is to be reminded, prompted or corrected into compliance** — a completion rate achieved by nagging measures the nagging, not the practice.
- **No retrospective tidying.** Blocks are not added to artifacts after the fact, and imperfect ones are not improved before the review.
- **Inconvenience is a finding.** If the block is annoying, slow, or awkward to fill honestly, **that is `E-T3`**, and it is the kind of result most likely to be lost if the trial is run to look good.

## 11 · Instance #1 is existence evidence only — and a bias I must declare

**PO/ARB clarification, registered:**

> *"'The trial started in this act — the registration artifact carries instance #1' is fine as evidence of the trial itself, but I would not treat that first example as evidence that ALT-2 is useful. It's merely: **Trial instance #1 exists.** Its usefulness is something E-T1–E-T6 must establish over the period."*

**Accepted without qualification.** Instance #1 establishes **that the practice was applied once**. It establishes **nothing** about accuracy, effort, information content or usefulness. **It contributes to `E-T1`'s denominator and numerator, and to nothing else.**

### 🔴 Declared bias on the evidence — `E-T-BIAS`

**The trial is being applied, and its evidence compiled, by the same process that designed and recommended it.** That process is invested in the trial being *informative*, which is a different bias from wanting it to *succeed* — but it is still a bias, and it acts most strongly on the two measurements most easily inflated:

| Evidence | How the bias would show |
|---|---|
| **`E-T1`** completion rate | An invested actor fills the block **more reliably than a disinterested one would**. A high completion rate may therefore measure *this process's diligence*, not the practice's viability |
| **`E-T3`** operational effort | The same actor is likely to **under-report friction** it has already decided is worthwhile |

> **Consequence for the 30 August review: `E-T1` and `E-T3` must be presented with this bias attached, not as neutral measurements.** A high completion rate produced by one invested actor is **weak evidence** that the practice would survive contact with a disinterested one. **`E-T4` and `E-T6` are less exposed**, because both can be contradicted by observation regardless of who is looking.
>
> **This is recorded now, before the numbers exist**, so it cannot be added or omitted depending on what they turn out to be.

**Nothing is commissioned by this addendum.** No Architecture or Implementation session is engaged for `ALT-2`; the next governance milestone is the **2026-08-30 review**.

---

## Traceability

PO/ARB decision + refinement 2026-08-15 (§1 verbatim) · ADP `a5b571df` · Governance review `4ad09fa1` (`C-1` prediction → `E-T6`; `C-3` illustrative label → §4) · `A-6.8` review date 2026-08-30 · `A-6.4` Governance evidence-compilation duty · `A-5.2` `D-a`–`D-d` (the pattern `ALT-2` extends) · `INV-ATTR-1`/`INV-ATTR-2` · `A-5.5` reporting-only · `P-6` unbatched family · `DEP-3.1`–`3.4` · `V-3` false-confidence precedent · `A5` run-ahead-of-evidence precedent
