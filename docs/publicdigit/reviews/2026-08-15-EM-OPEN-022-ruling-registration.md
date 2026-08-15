# `EM-OPEN-022` — PO/ARB Ruling Registered: READING A

**Type:** Governance registration (Session 2) · **Date:** 2026-08-15 · **Basis:** PO/ARB ruling on the `EM-OPEN-022` decision request
**⛔ Governance only. No implementation. No architecture. No code, test, or lifecycle change. Nothing inferred beyond the ruling's own words.**

---

## 1 · The ruling, registered verbatim

> **EM-OPEN-022 — ACCEPT READING A.**
>
> A correction to a published voting schedule creates a new voting opportunity. The previous opportunity becomes superseded and remains permanently recorded in the election protocol.
>
> The new opportunity has its own schedule and must receive a new progression decision. No authorization, refusal, eligibility assessment, or other progression decision associated with the previous opportunity carries over to the new opportunity.
>
> A schedule correction does not itself start voting, extend an existing authorization, or bypass a mandatory Election Rule.
>
> The Chief Election Officer may exercise schedule-correction authority only within the limits separately established by Governance. The detailed boundaries of that authority remain outside this ruling.
>
> **The purpose of this rule is to prevent schedule manipulation from becoming a means of circumventing an earlier progression refusal.**

**A-3 check:** performative (*"ACCEPT"*, declarative rule text) · already performed and durably recorded · scope-exact (identity of the opportunity; correction authority expressly excluded) · reasoning supplied in full. ✅ **Registrable act. Registered.**

## 2 · What was adopted

| Where | What |
|---|---|
| **`EM-VOC-005`** *(new)* | correction to a published schedule ⇒ **new voting opportunity**; previous **superseded** and permanently recorded; new opportunity needs its own progression decision; correction ≠ start, ≠ extension, ≠ bypass |
| **`EM-VOT-005`** *(scope extended)* | non-carry-over widened from *authorization* to **every progression decision** — authorization, refusal, eligibility assessment |
| **`EM-OPEN-022`** | **CLOSED** — the interim binding constraint is superseded by a ruling at least as strict |
| **`EM-GOV-004`** | **unchanged** — its six boundaries stay open; this ruling settled *identity*, not *authority extent* |
| **`EM-OPEN-023`** *(new)* | does the rule attach only to a **published** schedule? — surfaced, not decided |

## 3 · The reasoning recorded, because it is load-bearing

**The rejected alternative is as important as the adopted rule.** The PO examined the harmless correction (`10:00 → 10:05`) and chose the clean line anyway: *"a published schedule is an immutable historical commitment."* The stated reason is a governance-surface argument, not a convenience one — an exception would force **Architecture** to decide where "minor" ends (`10:00→10:05` · `10:00→11:00` · `20 Aug→21 Aug` · `10:00–12:00→10:00–14:00`), **creating a second policy surface the Chief could exploit.**

⛔ **Therefore, binding:** no significance threshold, tolerance window, grace band, or "no-op correction" shortcut may be introduced by Architecture or Implementation. **The differentiated rule Governance had surfaced (unopened / open / expired windows treated differently) was considered and NOT adopted** — recorded so it is not silently reinvented.

## 4 · The worked example, preserved as the acceptance narrative

Opportunity #1 published for 20.08 10:00–12:00 · progression requested at 10:15 · **refused — mandatory condition not satisfied** · schedule correction authorized · **Opportunity #1 SUPERSEDED, reason recorded.** Opportunity #2 published for 21.08 11:00–13:00 · Chief requests progression at 11:05 · **Election Rules evaluated independently** · voting starts, or is refused again.

**Anyone reviewing the election later can reconstruct exactly what happened, and nothing has been rewritten** — `EM-GOV-005` is what makes this narrative durable rather than momentary.

## 5 · The structural principle this completes

> **The Chief requests progression; the Election Rules decide whether progression is permitted.** *(`EM-VOT-004`)*
> **Authorization is spent on the opportunity it was given for.** *(`EM-VOT-005`)*
> **Changing the schedule creates a new opportunity, so the decision must be made again.** *(`EM-VOC-005`)*

**The Chief has authority, not sovereignty.** The path *refused → change the date → reuse the authorization → voting* is now **closed by business rule**, which is what the PO required — *"not merely by a clever technical check."*

## 6 · Sequencing directive registered (PO, with the ruling)

```
Governance      records the ruling                    ✅ THIS ARTIFACT
Session 4       Architecture MAY now incorporate it into the lifecycle design — on a
                separate commission; not started here
Session 3       REMAINS STOPPED — no implementation authority is created by this ruling
Session 1       OUT OF THE LOOP until an implementation exists to verify
```

**Adoption closes the business question only. Implementation is NOT authorized** — the standing `EM-VOT-002` / `EM-VOT-003` precedent applies unchanged.

## 7 · Open, unchanged by this ruling

`EM-GOV-004`'s six boundaries *(forward · extension · elapsed window · backward · credentials · after votes cast)* · when an opportunity expires · who may cancel one · correction while an opportunity is active · technical representation · **`EM-OPEN-023`** *(published-schedule scope)* · **`EM-OPEN-021`** · the A-2 authorization grant · `EM-VOT-003` implementation.

**Traceability:** decision request `78ce4ce0` (`…-EM-OPEN-022-decision-request.md`) → PO/ARB ruling (§1) → `EM-VOC-005` + `EM-VOT-005` extension + `EM-OPEN-022` closed + `EM-OPEN-023` opened (Manifesto §§4, 4a, 9) → this registration. Antecedents: `EM-VOT-004` · `EM-GOV-004` · `EM-VOC-004` · `EM-VOT-005` · `EM-GOV-005`.
