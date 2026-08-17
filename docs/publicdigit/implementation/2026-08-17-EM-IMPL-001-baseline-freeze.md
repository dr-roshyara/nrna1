# EM-IMPL-001 Baseline Freeze — cold-start entry point

**Status (PO, 2026-08-17):** Architecture `BASELINED` · Implementation `ACCEPTED` · Verification `INDEPENDENTLY VERIFIED` · Governance `ONE EXPLICIT OPEN QUESTION (EM-OPEN-111)` · Drift risk `CONTROLLED`.
**Purpose: future sessions — human or AI — consume THIS baseline; they do not reconstruct history from chat logs.** Everything below is a pointer; nothing here is a second source of truth.

## Accepted files
- Production: `app/Contexts/Election/Domain/OperatingCore/` — 56 files @ commit `a31f54f1`
- Tests: `tests/Unit/Contexts/Election/OperatingCore/` — 7 files / 42 tests @ commit `4b707798` (last verified GREEN: 42/2420, run independently by Governance)

## Accepted invariants
I-1…I-16, per the approved design (`docs/publicdigit/architecture/2026-08-17-EM-ARCH-001-model-a-operating-core-design.md` §2c) — verified enforced by C-5, with three honest qualifications recorded there (I-5 enforced-by-absence · I-6 vacuous in Model A · I-15 uniqueness pending persistence).

## Governing records (read in this order on cold start)
1. **Developer guide:** `developer_guide/election_operating_core/01_step_domain_core_first_increment.md` (incl. acceptance status + freeze)
2. **Approved design:** `…-EM-ARCH-001-model-a-operating-core-design.md` (⚠️ §2b commentary carries a KNOWN defect — correction owed, own authorization, proven-half scope)
3. **C-5 verification report:** `…-EM-IMPL-001-c5-verification-report.md`
4. **Acceptance registration:** `docs/publicdigit/reviews/2026-08-17-EM-IMPL-001-acceptance-registration.md` — incl. §4, the protective statement, quoted here because it defines this baseline's meaning:
   > *"EM-IMPL-001 acceptance does not establish the meaning of the OPEN ∧ INOPERATIVE region. It establishes only that the current implementation faithfully realizes the currently adopted rules and that the ambiguity is intentionally preserved pending EM-OPEN-111."*
5. **Rule corpus:** `docs/publicdigit/business_rules/ELECTION_MANIFESTO.md` — ADOPTED rows only are rules.

## Unresolved questions
- ~~`EM-OPEN-111`~~ **RESOLVED 2026-08-17 by adopted `EM-GOV-070`/`071` (Reading A; Meaning-1/Meaning-2 precision). The baseline now has NO open governance question of its own.**
- Walls unchanged: `EM-OPEN-049`/`066`/`094` (external authorities) · `053` (unbounded OPEN, accepted) · `077`/`076` (menu).

## Prohibited changes (binding until their gate opens)
- ⛔ **The canonical forbidden shape stands, now BY ADOPTED RULE (`070`/`071`), no longer by freeze:** `if ($committee->unableToFunction()) { $gate->close(); }` — a derived condition may never become an authority decision; gate state and election condition are independent.
- ⛔ Any adapter/binding/operation for `OrganisationalAppointmentAuthority` (D-1).
- ⛔ Any time attachment to an OPEN gate (deadline, timeout, scheduler, expiry hook).
- ⛔ Any stored classification flag; any second terminal-state rendering; any framework import into the domain core.
- ⛔ Reuse of the historical caveats as evidence: the provenance defect and RED-ordering caveat are PERMANENT record (`…-ratification-registration.md`).

## Next allowed entry points (each requires its own authorization)
1. ~~`EM-OPEN-111` adjudication~~ ✅ done (adopted `070`/`071`; freeze lifted, zero code change).
2. **§2b commentary correction** (Architecture; proven-half scope fixed in advance).
3. **`PBDIGIT-69`** hygiene slices (N-3 bounded free-text · N-4 replay parity · N-5 `$active` removal · N-6 negative-path tests).
4. **Increment 2** (application layer / adapters / persistence / protocol-store technology) — new boundary presentation → authorization → grant → START → RED first.
