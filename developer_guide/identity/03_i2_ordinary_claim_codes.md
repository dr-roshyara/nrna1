# 03. I-2: ordinary claim codes

**Story:** `PBDIGIT-349` step I-2 · **Design:** I-TDD-02 revision 12 (D-200), §5, §6.1–§6.3, §10.1, §12 C-I2, §15 · **Decisions:** D-196 (A1: one act kind, `manage-claims`), D-197 (no automatic lock), D-199 (14 days by default, at most 30).

## Purpose (business)
An officer whom the organisation's rules empower can give a recorded person a **claim code**, an invitation to link their PublicDigit login to the organisation's record of them. The officer can also **replace** an active code (withdraw one invitation and give another, as one act) or **revoke** it.

**A claim code grants nothing:** no membership, office, vote or access. Redeeming it (I-3) creates only an identity binding.

## What works now and what doesn't
| | Status |
|---|---|
| Issue, replace, revoke, with every accepted rule (below) | **implemented and proven in tests**, with a test stand-in for the officer's own R (R-1) |
| **Production** | **every claim operation is refused** (`IdentityUnavailable`). R-1 doesn't exist before I-4 (`NoActingRefYet`), Governance has no `manage-claims` producer yet, and no organisation people register calls Identity. **Fail closed by construction** |
| Redeeming a code, binding | **I-3** (not implemented). The `identity_bindings` table exists already so that issuance can check "R is unbound" under the lock; only I-3 will write it |
| Bootstrap claims | **I-6** (not implemented; B-T1 / B-T2 open) |

## Model
| Element | Kind | File (`app/Contexts/Identity/…`) |
|---|---|---|
| `ClaimCode` | value object: 20 Crockford-base32 characters, shown once as `XXXXX-XXXXX-XXXXX-XXXXX`; only `hash()` (SHA-256) is stored (INV-ID-7) | `Domain/ClaimCode.php` |
| `ClaimWindow` | value object, D-199: rules → days; silent → 14, `platform-default`; > 30 → 30, `capped` | `Domain/ClaimWindow.php` |
| `AuthorityEvidence` | Governance's complete answer **by value** (Identity's own consumer-owned shape) | `Application/AuthorityEvidence.php` |
| `AuthorityEvidenceCheck` | the §10.1 correspondence checks. **Never competence** | `Application/AuthorityEvidenceCheck.php` |
| `IssueClaim`, `ReplaceClaim`, `RevokeClaim` and their commands | use cases (C-I2) | `Application/*Claim*.php` |
| `ClaimOutcome`, `ClaimRefusal` | the result; refusal reasons, all with **one neutral public message** | `Application/` |
| Ports | `ClaimStore`, `ClaimCodes`, `RequestTime`, **`SessionActorRefs` (the R-1 seam)**, `IssuedReferences::find` (added) | `Application/Ports/` |
| Adapters | `PostgresClaims`, `RandomClaimCodes`, `SystemRequestTime`, **`NoActingRefYet`** (production R-1 = refuse) | `Infrastructure/` |

## The rules and where they're enforced
| Rule | Where |
|---|---|
| **The officer is the session itself:** the answer's `actor` must equal the R that Identity resolves for the signed-in session (R-1) | `AuthorityEvidenceCheck` (EvidenceActor) |
| **The answer corresponds:** same organisation, act `manage-claims`, subject (issue: entry, R, issuance id; replace: claim id, new issuance id; revoke: claim id), `authorised` (never `undetermined`), `evaluatedFor` = the act's date, `checkedAt` within this request | `AuthorityEvidenceCheck` |
| **Single use** of each answer: its request id consumed in the claim transaction; a replay is refused | `PostgresClaims` + PK of `identity_consumed_authority_requests` |
| **No answer content centrally,** only the request id and provenance (PI-21) | schema |
| **One active claim per (O, R);** an expired claim doesn't block (PI-20) | under the `ClaimSlot` lock |
| **R must be O's and unbound** (INV-ID-6) | `IssuedReferences::find`; `identity_bindings` checked under the lock |
| **Idempotent retry:** the same issuance id → `already-issued`, no code (PI-12, PI-35) | `UNIQUE (organisation_id, issuance_id)`; checked after the lock |
| **Replace and revoke only an active claim;** an expired one → refused, then issue instead (PI-27) | `PostgresClaims::isActive` |
| **Expiry uses one database-clock sample after all locks** (`clock_timestamp()`, never `now()` or the transaction start: PI-23, PI-24) | `PostgresClaims::tCheck` |
| **Lock order:** `ClaimSlot` (O, R) → claim row | `lockSlot`, `lockClaim` |
| **Another organisation's claim** → unknown (ISO-1) | `lockClaim` filters on O |
| **Content-free audit:** issued, replaced, revoked, refused + reason; never a code | `identity_claim_audit` |

## Database (central migration `2026_11_15_620000_create_identity_claims_and_bindings.php`)
| Table | Contents |
|---|---|
| `identity_claim_slots` | one row per (O, R), no data; the serialisation point |
| `identity_claims` | the code hash, issuedAt / expiresAt, the window (days, source, capped), the state, the issuance id (unique per O), the authority request id, `replaces_claim_id`, `ended_at` |
| `identity_consumed_authority_requests` | the request id (PK = single use), O, act kind, claim id, time |
| `identity_bindings` | (O, R) → Person, unique (O, Person); **immutable** (trigger); written only by I-3 |
| `identity_claim_audit` | event, O, claim id, reason, time; no content |

## Testing
```bash
vendor/bin/phpunit tests/Unit/Contexts/Identity
MEMBERSHIP_PG_IMAGE=docker.io/library/postgres:15@sha256:7da5d534f1ffcdb2f69efa6e466a7a29ff89eefd611c7b50c4d69aa77fd4a822 \
  bash scripts/testing/membership-pg.sh --testsuite IdentityPostgres
```

- **Unit tests** (`ClaimRulesTest`): the window, the code, and every correspondence refusal.
- **PostgreSQL tests** (`OrdinaryClaimsTest`, 17): every rule above, including **real races in a forked process** (PI-18 on a first and on a later issuance, PI-35, PI-23 across expiry) and production fail-closed.

**Mutants (8):**
- M1 (transaction-start time), M3 (actor check removed), M5 (expired still blocks), M6 (no cap), M7 (idempotency before the lock) and M8 (replace doesn't revoke) were all killed.
- **M2 (slot not locked) first survived the PI-18 test.** On a *first* issuance, PostgreSQL's unique-key wait on inserting the slot already serialises the two transactions. A test of a *later* issuance (existing slot) was added and kills it.

Evidence: `docs/publicdigit/identity/evidence/2026-11-15-pbdigit349-i2/`.

## Administrator notes
- **One new central migration**, under the release gate: **test databases only** until the Principal approves the rollout.
- No configuration or secrets.

## Pitfalls
- **Never deliver a code through the login address** (INV-ID-3). The organisation delivers it through its own channel.
- **Never build `AuthorityEvidence` from client input.** It comes from Governance, server-side, in-process.
- **In I-4, replace only the `SessionActorRefs` binding.** Don't answer it from e-mail or the legacy user-organisation tables (D-160).

## Traceability
PBDIGIT-349 I-2 · I-TDD-02 §5, §6, §10.1, §12 C-I2, §14, §15 · PI-7, 12, 17a, 18, 20, 21, 23, 24, 27, 32, 35 · D-196, D-197, D-199, D-200.
