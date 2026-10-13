# 08: Fixed lifetime and kept history (PBDIGIT-274 S5)

**Story:** `PBDIGIT-274` S5 · **Code:**
- `app/Http/Controllers/ElectionController.php` (`storeElection`)
- `app/Services/VoterSlugService.php`
- `app/Http/Controllers/CodeController.php`
- `app/Console/Commands/CleanupExpiredVoterSlugs.php`

**Tests:**
- `tests/Isolated/Pbdigit274/Database/VoterSlugFixedLifetimeTest.php`
- `tests/Unit/Services/Voting/VoterSlugServiceFixedLifetimeTest.php` (database-free)

## Purpose
A real-election link has a **fixed** lifetime from its creation, `min(expires_at, created_at + T)` (guide 02). It is never extended, and once replaced it is kept as history. S5 removed the last code that broke either rule.

| Before | After |
|---|---|
| `POST /election/select` (`election.store`) used `VoterSlugService::getOrCreateSlug` for real elections; its cleanup **hard-deleted** the voter's expired links | real elections go through `VoterSlugIssuer::fresh()` (guide 06); demo is unchanged |
| `getOrCreateSlug` served real elections | it **refuses** them before any query: `LogicException('Real-election voter links come from VoterSlugIssuer, not VoterSlugService::getOrCreateSlug().')` |
| `extendSlugExpiry` added a sliding +30 minutes | a deprecated no-op returning `false` |
| `CodeController` extended and reactivated the slug when a code was regenerated | no slug write |
| `voting:clean-expired-slugs` hard-deleted every soft-deleted real slug | real slugs are **never** deleted; the command only reports how many are kept |

## How to use it
- **Need a link for a real election?** Use the issuer (guide 07). `VoterSlugService` is demo-only for issuance (decision D1).
- **Need "more time" for a voter?** There is no extension. The voter starts again, and the issuer replaces an expired link with a new one.
- **Cleaning up:** demo slugs are still cleaned. Real slugs are history, governed by a future retention policy (O-12, backlog D2).

## Testing
```bash
. ./.pbdigit274.env && vendor/bin/phpunit -c phpunit.pbdigit274.xml tests/Isolated/Pbdigit274/Database/VoterSlugFixedLifetimeTest.php
vendor/bin/phpunit --no-configuration --bootstrap vendor/autoload.php tests/Unit/Services/Voting/VoterSlugServiceFixedLifetimeTest.php
```
- **`election.store` tests** need `withSession(['current_organisation_id' => …])`. Without it the election is loaded through the tenant scope and the request answers 404, a misleading RED (recorded in the S5 evidence).
- **The code-regeneration test** must reach the extension block: `has_code1_sent = false` with `sent_at` 5 minutes ago. Otherwise it passes without testing anything.

## Pitfalls
- **Any new "touch the link" code is a regression.** A link's `expires_at` / `created_at` are set once, by the issuer.
- **`getOrCreateActiveSlug`** returns an existing slug unchanged. Its only route (`vote.direct`) is unreachable anyway (guide 07).
- **The default-suite test** `tests/Unit/Models/VoterSlugExpirationTest.php` was moved to the issuer and now requires history to be **kept**. It needs `nrna_test` and **has not been run**.

**Traceability:** `PBDIGIT-274` S5: code `e2bd4cc8a`; tests `462b98f77`, `a6bc9418b`; evidence `82acc6ca7`.
