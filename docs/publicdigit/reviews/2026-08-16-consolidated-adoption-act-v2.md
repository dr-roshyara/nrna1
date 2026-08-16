# Consolidated Adoption Act v2 — ready to sign

**Prepared by Governance (Session 2) · 2026-08-16 · Supersedes the 15 August act, which remains unsigned**
**⛔ NOT adopted. This is the text of an act for the PO/ARB to perform.**

**Why v2:** the 15 August act covered five items. **Eight more rules have accumulated since**, and four rules adopted yesterday under delegation now depend on them. **This act clears everything that is ready and states precisely what is not.**

---

## Part 1 — READY TO ADOPT (no blockers)

> **PO/ARB ADOPTION ACT — 2026-08-16**
>
> **I adopt the following as Election business rules:**
>
> **1 · `EM-GOV-012`** — *Progression is halted when a mandatory phase condition prevents legitimate completion. The protocol records the event, the reason and the affected phase. A halted progression does not by itself define the election-level outcome.*
>
> **2 · `EM-GOV-014` Part 1** — *A halted election requires a governed recovery process. The Election Chief is responsible for initiating it. No recovery action may bypass any Election Rule or reuse invalidated decisions.*
>
> **3 · `EM-GOV-014` Part 2 (service policy, not an Election Rule)** — *The recovery period duration is a service-policy parameter owned by the service provider. The applicable policy version and the deadline used are recorded for each halted election.*
>
> **4 · `EM-OPEN-047`** — *Expiry of the recovery period is recorded as an event. Its meaning and consequence are determined by Election Governance rules and must not be silently converted into an automatic election outcome by service configuration.*
>
> **5 · `EM-OPEN-050`(b)** — *The policy version and duration applicable when a recovery period begins remain bound to it. A later service-policy change is not retroactive and may not shorten a deadline already communicated.*
>
> **6 · `EM-GOV-016`** — *Counting and progression are election-wide. Multiple posts are components of the election result, not independent counting or progression units.*
>
> **7 · `EM-GOV-017` (as SPLIT)** — *The acceptance **rules** — required participation model, thresholds, and entitlement to representation — are established in the election application and form part of the election commitment. The **participants** are not: the Election Committee is constituted during Election Appointment, and candidate representatives are selected during Voting Preparation.*
>
> **8 · `EM-GOV-018` (as AMENDED)** — *During Voting Preparation the Election Chief shall conduct the prescribed consultation and prepare the proposed representatives without bias. Entitled stakeholders shall be given the opportunity to confirm or object. The final representative configuration shall be established **before Voting begins** and recorded as part of the election record.*
>
> **9 · `EM-GOV-019`** — *`Voting Preparation` is a lifecycle phase between Candidacy and Voting, in which the voting and acceptance configuration is finalized so that Voting can legitimately begin. It accepts neither the election nor its result.*
>
> **10 · `EM-GOV-020`** — *The Chief's confirmation is an attestation that the required consultation was conducted and that the recorded representatives were established through that process — not a personal approval of them. A candidate's objection must be addressed through the prescribed process, and **the Chief must not treat an unresolved objection as agreement**.*
>
> **11 · `EM-GOV-026`** — *`Election Appointment` is a lifecycle phase in which the persons and bodies authorised to conduct, support and oversee the election are appointed and recorded — Election Chief, Deputy, and the Election Committee where the selected acceptance model requires one. No Administration may begin until it has legitimately completed.*
>
> **12 · `EM-GOV-027`** — *Election Application is an initiating act, not a lifecycle phase. Submission creates the election and locks it against ordinary administration: the applicant, ordinary administrators and other organisation users may not administer it, and **the applicant does not thereby become Election Chief**. The appointed authorities remain bound by the Election Rules.*
>
> **13 · `EM-GOV-010` AMENDMENT** — *the phase chain becomes* **`election appointment → administration → candidacy → voting preparation → voting → counting → result publication`**. *A two-place insertion. The acceptance gates remain **gates**, not phases.*
>
> **— Signed: PO/ARB, 2026-08-16**

## Part 2 — NOT in this act, and why

| Rule | Blocked by |
|---|---|
| **`EM-GOV-013`** *(rescheduling recovery)* | the schedule-correction boundaries `SCB-1`…`SCB-9` — **`SCB-1`'s scope collision with `SCB-9` is still unconfirmed and blocks the whole set** |
| **`EM-GOV-015`** *(voting freeze + exceptional extension)* | **one sentence** — the express carve-out from `EM-VOC-005` (`EM-OPEN-051`). **Draft supplied there; adopting it alongside would unblock this rule immediately** |
| **`EM-GOV-025`** *(three acceptance participation models)* | **`EM-OPEN-066`** — the Election Committee is not defined, so **Models A and C name a body that does not exist. Only Model B is configurable.** *(`EM-GOV-026` gives the Committee an origin but not a definition.)* |

## Part 3 — What this act would fix

**Four rules adopted 2026-08-16 under delegation currently have no adopted context to operate in.** `EM-GOV-021` (representative selection) operates during **Voting Preparation** — item 9. `EM-GOV-024` (representation weight) feeds the **acceptance gates**, which rest on items 6 and 7. **Signing Part 1 removes that inversion entirely.**

**Three protections currently rest on a report rather than a rule** — *a halted progression does not determine the election-level outcome* is `EM-GOV-012`, cited in the recovery model, the acceptance-gate report and the participant report. **Item 1 fixes all three.**

## Part 4 — Still open after this act

`EM-OPEN-051` *(carve-out sentence)* · `EM-OPEN-054`-Q3 *(process vs tabulation acceptance)* · `EM-OPEN-060` residue · `EM-OPEN-061` *(representative eligibility)* · `EM-OPEN-066` *(Committee definition)* · `EM-OPEN-068` *(appointment scheduling)* · `SCB-1`…`SCB-9` · the Model B threshold · the Model C combination rule · `EM-OPEN-021`/`024`/`025`/`027`/`029`/`030`/`031`/`033`…`046` as previously recorded.

**Architecture ⏸️ · Session 3 🛑 · Session 1 must not verify unauthorized implementation.**
