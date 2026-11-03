# Membership BD-REV-ER: may a correction absorb a whole period? Decision record

**Date:** 2026-11-02 · **Recorded by:** Claude (DDD architect), on the PO's instruction
**Status:** **DECIDED by the PO (2026-11-02): option (b), refuse.**

> **Scope annotation (PO, 2026-11-03, with the COR-2 acceptance):**
> - ER is enforced as a **G\* invariant on every decision**, as §3 places it in the enforcement set. That covers a Correct, an End (e.g. an End dated inside its own period's existing trim) and a reversal (e.g. an appeal Annul(Annul) that would leave a live period absorbed).
> - This **explicitly extends** the §2 wording "a correction is refused if …".
> - Evidence: the oracle and golden at L = 5 give 616 `period_absorbed` refusals (Correct 595, End 21).
> - Record: `2026-11-03-pbdigit327-cor2-independent-review-and-acceptance.md` §4.1. The §2 text is kept as written.
**Placement:** `php scripts/doc-placement.php --scope=product-specific --domain=publicdigit` → `docs/publicdigit` (exit 0); `reviews/*-decision-record.md` convention.

## 1. The question (as recorded)

- **Decision register** (`2026-10-08-membership-final-decision-register.md:146`): "may a correction absorb a whole other period (live Admit, empty lifetime)? (a) allow, or (b) refuse until that period's Admit is annulled or voided."
- **Correction contract** §9 (`2026-10-09-membership-correction-slice-semantic-contract-draft.md:206`):
  - evidence: [MODEL] 35 of 14,365 histories;
  - minimal case: `Admit(p0@0), End(p0@1), Admit(p1@1), Correct(Admit p1 → 0)` erases p0 while its Admit and End stay live.

**In business terms:** a correction to one membership period must not silently wipe out another period that is still recorded as valid. To remove that other period, someone must annul or void its admission explicitly, as an act with its own authority and reason.

**Classification:**
- business policy, decided by the PO (register `:155`);
- architecture-neutral (ratified canonical §10: it "leaves the architecture unchanged");
- ratified canonical §6 already names the enforcement: G\* adds the invariant **ER** "if BD-REV-ER = refuse".

## 2. Decision (PO, 2026-11-02)

**PO words:** "BD-REV-ER: refuse, record it and commit".

> "BD-REV-ER = (b) refuse. A correction is refused if, as a result, another period whose Admit is live would have an empty resolved lifetime. Such a period can be removed only by explicitly annulling or voiding its Admit."

**Recorded reason** (contract §9 recommendation, adopted): absorbing a period would be an implicit set-aside, without Annul or Void, without authority evidence and without a reason. In the bounded model space, refusing changed nothing else.

## 3. Consequences (recorded, not authorised)

- **The enforcement set I** (canonical §6) becomes INV-EP ∧ INV-9 ∧ INV-COV ∧ INV-1 ∧ **ER**.
- **COR-2** (Correct + consequences, contract §10 / roadmap W4):
  - includes the ER guard;
  - includes the refusal reason `PeriodAbsorbed`, which the contract lists as [OPEN BD-REV-ER] at `:102`, `:138`.
  - The final name is fixed when COR-2 is specified.
- **Roadmap BR-11** moves from OPEN to DECIDED (annotation R-9). **COR-2 is no longer blocked by BD-REV-ER.** D-COR-3 still blocks it (contract `:221`).

## 4. What this decision does not change or authorise

- No ratified text changes. BD-REV-ER was always an open policy switch (canonical §6, §10).
- **No implementation is authorised.** COR-2 still needs its own story, plan and authorisation, and still waits on D-COR-3. C-1 acceptance is still deferred (PBDIGIT-268).
- CQ-3 (who may Annul, Void, Correct) is untouched.

## 5. Traceability

- Question: decision register `:146`, `:155`; correction contract §9 (`:206`), §10 (`:101-102`, `:138`, `:221`); EP-01 readiness `:529`.
- Ratified: canonical §6 (`:131`, `:135`), §10 (`:175`); act note N-4.
- Roadmap (ADOPTED): BR-11, annotation R-9.
