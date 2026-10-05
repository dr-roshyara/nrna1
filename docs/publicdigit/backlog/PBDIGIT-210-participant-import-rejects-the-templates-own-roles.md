# PBDIGIT-210 — Participant import fails on the roles its own template offers

**Type:** Defect (production, data contract) · **Epic:** `PBDIGIT-EPIC-02` Membership Management · **Created:** 2026-10-01
**Found by:** test-estate classification (`docs/publicdigit/reviews/2026-10-01-backlog-completion-review.md` §8), membership area
**Raised by:** `claude-code-session:42e0287e-e0e2-4638-8165-cd5d5862c578` (self-declared, not attestable)
**Placement derived:** `doc-placement.php --scope=product-specific --domain=publicdigit` → `docs/publicdigit` (exit 0)

| | |
|---|---|
| **Status** | 🔴 **OPEN — defect, runtime-confirmed.** A small **PO decision (the role vocabulary) comes before implementation.** Not authorised. |
| **Problem** | The downloadable participant template (`app/Services/ParticipantImportService.php:20-27`) offers the roles **`coordinator`, `observer`, `scrutineer`**. The database allows only **`participant`, `organizer`**: a CHECK constraint added by `database/migrations/2026_04_19_000001_convert_all_enums_to_varchar_for_postgresql.php:88-91`, although the column was created as a free nullable string. The import never validates roles (`:67`, `:117`, `:128` pass them through). So **importing the template's own sample rows fails with a 500**: `organisation_participants_role_check`. |
| **Business consequence** | An organisation cannot import participants (staff, guests, election-committee helpers such as scrutineers) with the roles the product itself proposes. The routed action `POST …/participants/import` (`routes/organisations.php:154` → `ParticipantImportController::import`) aborts, and the user sees a server error rather than a row-level message. |

## Evidence (re-run 2026-10-01, `php artisan test --env=testing`)

`tests/Feature/Membership/ParticipantImportErrorMessagesTest.php::test_success_message_includes_created_and_participant_wording` → `SQLSTATE[23514]: … violates check constraint "organisation_participants_role_check"`.

## Existing-story search

`git grep -i` for `participant import`, `ParticipantImport`, `organisation_participants` over every `PBDIGIT-*.md`, the 76–157 backlog and the 159–209 specs → **no owning story**. (`PBDIGIT-203` is **voter** import integrity, a different import, model and table. `PBDIGIT-167`/`208` own failing *tests*, and their scope raises production defects as their own stories.)

## Decision required first (PO)

Which participant-role vocabulary is canonical: the database's (`participant`, `organizer`) or the template's (`coordinator`, `observer`, `scrutineer`), or a union? Whichever is chosen, either the constraint or the template must change. **The defect exists whatever the outcome.**

## Scope (after the decision)

RED: the existing test above, unchanged. GREEN:
- align the constraint (an **additive** migration) or the template with the decided vocabulary;
- **validate roles in the import**, so an unknown role becomes a row-level error and never a 500.

## Out of scope

Participant types; permissions JSON; voter import (`PBDIGIT-202`/`203`).

## Acceptance criteria

- Importing the downloaded template's sample rows succeeds. **Business state:** the participants exist with their roles.
- A row with an unknown role is reported in the import result, and the other rows are processed.
- `ParticipantImportErrorMessagesTest` and `ParticipantImportTest` pass with their assertions unchanged, except where the PO decision changes a role name.

## Dependencies / required authority

The PO decides on the vocabulary. Then PO authorisation, RED-first.

## Verification method

The test re-run, plus a manual import of the downloaded template in a test organisation.

**Traceability:** `ParticipantImportService.php:20-27, :67, :117, :128` · migration `2026_04_19_000001:88-91` · `create_organisation_participants_table.php:16` · `routes/organisations.php:154`.
