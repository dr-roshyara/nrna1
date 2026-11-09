# 06: Domain and security assessment

> REPORT · generated · 2026-11-09 · snapshot `15217f4a9`. Labels: FACT / INTERPRETATION / ASSUMPTION / RECOMMENDATION / UNKNOWN. **No domain is redesigned here.** The mobile app is treated as a delivery channel only.

## 1. Governing constraints consulted
- Strategic design status: `docs/publicdigit/architecture/strategic-design-status.md`.
- Context map: `docs/publicdigit/architecture/context-map.md`.
- VO-1 "A Vote Must Not Be Linkable to the Voter Who Cast It" and its FROZEN derivatives: Blueprint CI-5, ADR-T11, Canonical Event Catalog. These are cited through the 2026-11-03 baseline (`../reviews/2026-11-03-…-report.md:117`).
- Voting authorization boundary: `docs/publicdigit/adr/ADR_20261005_0950_Voting_Authorization_Boundary.md`.
- Voter admission and entitlement: `docs/publicdigit/adr/ADR_20261004_1549_Voter_Admission_Policy_And_Voting_Entitlement.md`.
- IP protections: `developer_guide/election/ip_voting/00_index.md`.

The Membership ratification, Governance ownership of membership authority, and the Finance separation are **taken as given from the mandate and those records**. This investigation did not re-verify them clause by clause (scope limit, recorded in `09`).

## 2. Can mobile invoke authoritative capabilities instead of deciding?

| Domain | Authoritative capability today (FACT) | Mobile implication (INTERPRETATION / RECOMMENDATION) |
|---|---|---|
| Membership: registration, status | `SubmitMembershipApplication`, `ApproveMembershipApplication` use cases; `MembershipApplication::isPending()` | Mobile submits through the use case and displays status from a read model. It never infers membership from a cached flag |
| Membership: directory, jurisdiction | `ensure.organisation` middleware; `organisation_id` global scopes | API controllers must apply the same middleware or scopes. **Tests must run with two TenantIds** (Rule 9) |
| Governance: committees, authority | Governance handlers (`RemoveCommitteeMemberHandler`, `CommitteeMemberQueryService`); `CommitteeAssociationLifecyclePolicy` | Mobile shows the outcomes; authority stays in Governance. Admin authority actions stay desktop-only in early phases |
| Elections: visibility, capabilities | Backend capability resolver consumed through `useElectionCapabilities.ts` ("Runtime truth ownership remains in the backend", `:30-40`) | Reuse the ACL. Capabilities come from the API **per request**, with a `generated_at` timestamp, and are never persisted as authority |
| Eligibility, voting authorization | Middleware chain `voter.slug.verify → window → consistency → ensure.election.voter → voter.step.order → vote.eligibility → validate.voting.ip → vote.organisation → throttle:10,1` (`routes/election/electionRoutes.php:514`) | All server-side. A mobile app cannot bypass it. But the chain is bound to the web session and to IP evidence (below) |
| Duplicate-vote prevention | Atomic compare-and-set `UPDATE codes SET has_voted = true WHERE id = ? AND has_voted = false`, refused if the affected rows ≠ 1 (`VoteController.php:1825-1845`), plus a slug lock and an outer transaction (`:1472-1480`) | **Retry-safe at the server**: a retried cast cannot produce a second ballot. The UX gap: a retry after a lost response gets a refusal, not "your vote was recorded". A safe receipt lookup is needed, and it must not create linkage |
| Adjudication | Contestation and Adjudication contexts, event-only collaboration (`deptrac.yaml` header) | Read-only mobile views at most. Evidence access and determinations stay desktop and authority-bound. Forward-only correction is unaffected by a read-only channel |

## 3. Mobile-specific risks to voting (do not start voting on mobile until resolved)

| # | Risk | Evidence | Label |
|---|---|---|---|
| V-1 | **Ballot state lives in the server session.** A token-based app has no session, so the 5-step flow cannot run without a redesign of where interim selections live. The redesign must not add a voter identity to stored ballot data | `VoteController.php:621,870,1655,1762,2919` | FACT → INTERPRETATION |
| V-2 | **Per-IP limits vs carrier networks.** Default per-IP limit 10, constitutional limit 6 per IP (`ip_voting/00_index.md` table). Mobile carriers commonly put many subscribers behind one public IPv4 (CGNAT), so legitimate voters could be refused | as cited | FACT (limits); ASSUMPTION (CGNAT prevalence for the electorate) |
| V-3 | **IP binding vs network switching.** IP binding allows only the captured /24 (IPv4) or /64 (IPv6) (PBDIGIT-315). A phone that moves from Wi-Fi to cellular between code entry and cast is refused | `ip_voting/00_index.md` row "IP binding" | FACT (rule); INTERPRETATION (effect) |
| V-4 | **Device fingerprint input.** `store()` passes `$request->input('device_fingerprint')` into the security assessment (`:1495`). Native apps could supply a stronger or different signal. Its trust weight is a constitutional-policy question, not a client decision | as cited | FACT |
| V-5 | **Known anonymity gaps.** PBDIGIT-284 (OPEN, "Fix before any production election"): `vote_hash` built from code data. G-1: receipt hash `sha256(out_code . user_id . election_id)` logged with the user (`VoteController.php:1950`, re-verified 2026-11-09) | `docs/publicdigit/backlog/PBDIGIT-284-vote-hash-links-vote-to-voter.md` | FACT |

**Mandatory rules for any future mobile voting (RECOMMENDATION, to be ratified under D-3):**
- The app never decides eligibility.
- Cached eligibility or capabilities are display-only and are refetched before the code step and before the cast.
- No offline voting and no queued casts. A cast is a single online request, or it does not happen.
- The client generates no voter identity into any vote payload or event. Push and device tokens are never joined to votes or results.
- Retries rely on the server compare-and-set. The client shows "status unknown, check your receipt", never "submit again".
- The service worker and the app cache **never** store ballot pages or responses.

## 4. Caching, offline, local storage: general rules (RECOMMENDATION)
- Tokens go in Keychain/Keystore (Capacitor secure storage plugin), **never** in `localStorage`. Note: 15 files already use `localStorage` (FACT, `02`). That is acceptable today for UI preferences but must be reviewed before mobile reuse.
- The offline cache is limited to public reference data (geography, locale strings).
- Membership PII is not cached at rest on the device unless encrypted and with a defined expiry.
- Screenshot or backup exclusion for sensitive screens is a later hardening item (OWASP MASVS-STORAGE/PLATFORM).

## 5. Existing security findings (reported separately, NOT modified)

| ID | Finding | Evidence | Severity (judgement) | Already recorded? |
|---|---|---|---|---|
| S-1 | `POST /api/tokens/create` registered with no auth middleware; calls `$request->user()->createToken(...)` | `routes/api.php:32-36` | Medium (likely errors with 500 rather than issuing a token, an ASSUMPTION shared with the 2026-11-03 baseline) | yes, in the 2026-11-03 baseline row `:111`; no backlog item found |
| S-2 | `POST /api/login`: no input validation, no route-level throttle, no `email_verified_at` check, returns the full User model, token without expiry (`config/sanctum.php` `expiration => null`). This bypasses the hardened web login (`LoginController.php:43-73`) | `routes/api.php:38`; `app/Http/Controllers/Auth/AuthController.php:56-75` | **High** (credential stuffing; verification bypass) | not found as a backlog item |
| S-3 | `POST /api/register` returns `'Registration failed: ' . $e->getMessage()` and issues a token before email verification | `AuthController.php:34-49` | Medium | not found |
| S-4 | Debug endpoints `test-auth`, `test-post-debug` (and `test-membership`) are live in API route files. `test-auth` returns `session()->getId()` to JavaScript, which defeats the HttpOnly protection of the session cookie | `routes/api_v1.php:19-45`; `routes/governance-api.php:16-58`; `routes/api/governance.php:23` | Medium | not found |
| S-5 | Session-authenticated `/api/v1/*` is CSRF-exempt. Mitigations in place: `SameSite=lax` cookie (`config/session.php:199`); the custom `X-Organisation-ID` header forces a CORS preflight; CORS allows only localhost origins (`config/cors.php:22-26`). The protection therefore rests on browser behaviour rather than on a token. **This is a configuration fact, not a demonstrated vulnerability.** It needs a dedicated security review | `bootstrap/app.php:19-22,66-68` | Low–Medium (unproven) | not found |
| S-6 | `IdentifyTenantFromHeader` calls `$request->user()->organisations()` before `auth` runs. An unauthenticated request causes a fatal error (500) instead of 401. It also writes the tenant into the session from an API request | `app/Http/Middleware/IdentifyTenantFromHeader.php` | Low | not found |
| V-5 | Anonymity gaps PBDIGIT-284 and G-1 | above | Highest (per PBDIGIT-284) | yes |

**Discipline note.** Under the End-of-Commission rule, discovered work becomes backlog items. This mandate forbids changes beyond the report folder, so **no backlog items were created**. Recommended: PO triage into new `PBDIGIT-nn` items for S-1…S-6 (`10`, decision D-0).

**Passing observation (not a security issue, not verified as a defect):** `MembershipApplicationController::approve` substitutes `CommitteeId::generate()` (a random new id) when no committee is chosen (`:278-280`). Whether that is intended is UNKNOWN. It is listed in `09` for Membership-owner triage.
