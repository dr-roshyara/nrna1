# 02. I-1: organisation person references (`PersonRef`)

**Story:** `PBDIGIT-349` step I-1 · **Design:** I-TDD-02 revision 12 (D-200), §4 IssuedReference, §12 C-I1 · **Principal decision 2026-11-15:** P-I2 = **L1** (a stored table).

## Purpose
When an organisation records a person (a local entry E in its own database), Identity gives that entry **one opaque reference R** (the organisation's `PersonRef`). Every later claim, binding and act in the organisation refers to R.

**R carries no data and grants nothing:** no membership, no access, no proof of identity.

## Where it sits (central database, D-164)
| Layer | File |
|---|---|
| Domain | `app/Contexts/Identity/Domain/OrganisationPersonRef.php` (32 lower-case hex), `LocalEntryRef.php` (the organisation's opaque entry id, 1–128 non-space characters) |
| Application | `Application/IssueReference.php` (use case C-I1); port `Application/Ports/IssuedReferences.php` |
| Infrastructure | `Infrastructure/PostgresIssuedReferences.php`; bound in `Providers/IdentityServiceProvider.php` on the central (default) connection |
| Migration | `database/migrations/2026_11_15_610000_create_identity_issued_references.php` |

## Rules and how they're enforced
| Rule | Enforced by |
|---|---|
| **INV-REF-2:** one R per (O, E); issuing again returns the same R, also under concurrent calls | `UNIQUE (organisation_id, entry_ref)` plus `INSERT … ON CONFLICT DO NOTHING`, then re-reading the committed row |
| **INV-REF-1:** R names exactly one organisation | R is the primary key |
| a value collision never reuses a value | a fresh random value is tried (up to 5 times) |
| a reference is never changed or deleted | trigger `identity_issued_references_immutable` |
| nothing about the person centrally | only (R, O, E, issuedAt). E is the organisation's own id; no name and no contact |
| failure | `IdentityUnavailable`: the organisation's entry waits; no R is invented |

## How to use it
Only the organisation's own application layer calls it, in-process, in its organisation context (ISO-1):

```php
$ref = app(IssueReference::class)->issue($organisationId /* TenantId */, LocalEntryRef::fromString($entryId));
```

**Not wired to a production caller yet.** The Organisation people register (slice S4) will call it when it records a person. Provisioning will call it for the founding registrar (step I-6).

## Testing
```bash
vendor/bin/phpunit tests/Unit/Contexts/Identity/IssueReferenceTest.php
MEMBERSHIP_PG_IMAGE=docker.io/library/postgres:15@sha256:7da5d534f1ffcdb2f69efa6e466a7a29ff89eefd611c7b50c4d69aa77fd4a822 \
  bash scripts/testing/membership-pg.sh --filter PostgresIssuedReferencesTest
```

**What the tests cover:**
- idempotency;
- one organisation per R;
- the same entry id in two organisations gives two references;
- a concurrent issuance that wins is returned (a deterministic seam that inserts the competitor between the check and the insert);
- a collision is retried;
- immutability;
- production wiring.

**Mutants:**
- **K** (ownership ignores the organisation): killed.
- **L** (deletes allowed): killed.
- **J** (skip the lookup before the insert): **equivalent and survives.** The unique constraint plus `ON CONFLICT` already guarantee INV-REF-2; the lookup only saves one insert attempt.
- Evidence: `docs/publicdigit/identity/evidence/2026-11-15-pbdigit349-i1/`.

## Administrator notes
- **One new central migration.** Under the existing release gate it runs **only on test databases** until the Principal approves the rollout (as with G-0): `php artisan migrate` on a test database.
- No configuration or secrets.

## Pitfalls
- Never derive R from an e-mail or a name (INV-ID-3). Never reuse an R for another entry.
- A different R doesn't prove a different human, and the same human may hold references in several organisations. Only Identity resolves them (XD-3 §2.3).

## Traceability
PBDIGIT-349 I-1 · I-TDD-02 §4, §12 C-I1 · D-164, D-200 · P-I2 = L1 (Principal 2026-11-15).
