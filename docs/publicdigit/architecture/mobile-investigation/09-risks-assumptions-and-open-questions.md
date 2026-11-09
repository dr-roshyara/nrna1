# 09: Risks, assumptions and open questions

> REPORT · generated · 2026-11-09.

## 1. Risk register

| ID | Risk | Likelihood | Impact | Mitigation (RECOMMENDATION) | Owner |
|---|---|---|---|---|---|
| R-1 | Mobile token API built on the unhardened `/api/login` (S-2) | M | H | Phase 0b first; new endpoint reuses the web login policy | Engineering |
| R-2 | Business rules duplicated into API controllers | M | H | use-case parity architecture test; extraction before exposure | Engineering |
| R-3 | Voting pulled into mobile before V-1…V-5 are resolved | L–M | **Critical** (anonymity, legitimacy) | D-3 gate; no voting routes in the mobile API | Principal |
| R-4 | IP protections refuse legitimate mobile voters (CGNAT, network switching), even in a mobile browser today | M | H | measure refusal telemetry per network type (the IP refusal audit exists, guide 04); constitutional decision on mobile evidence | Principal / Election owner |
| R-5 | Service worker caches authenticated or ballot content | L | H | allow-list caching plus a test | Engineering |
| R-6 | Shared-component extraction regresses desktop (4 Vitest specs today) | H | M | frontend CI and characterization tests first | Engineering |
| R-7 | Documented Angular/Ionic direction leads to a second framework later | M | M | D-2 | Principal |
| R-8 | Store rejection (thin wrapper, privacy disclosures for political or membership data) | M | M | bundled app (C2); privacy labels prepared early | Principal |
| R-9 | Installed app versions lag behind API changes | H | M | versioned API; additive changes; `426` version contract | Engineering |
| R-10 | Solo-developer capacity: the store app roughly doubles the release surface | H | M | gate D-1b on evidence; web first | Principal |
| R-11 | Device or push tokens become a linkage path to votes | L | **Critical** | anonymity fitness test covers new tables; push never references votes | Engineering |
| R-12 | Tenant leakage through header-based tenant selection on token requests | M | H | validate the header against token-holder membership; two-TenantId tests | Engineering |

## 2. Assumptions
- A-1 Web store and browser capabilities (iOS PWA push, store guideline 4.2, Capacitor origins) are as known up to mid-2026. Recheck at decision time.
- A-2 CGNAT is common for the electorate's mobile carriers. Not measured.
- A-3 `POST /api/tokens/create` returns an error rather than issuing a token (not executed).
- A-4 No external consumer currently uses `/api/login` or `/api/register` (no evidence either way).
- A-5 Reuse percentages are a fair proxy for effort. Real coupling may be deeper through child components.

## 3. Unknowns needing clarification
- U-1 Deployment target, error monitoring and the production queue driver (not in the repository).
- U-2 Whether 2FA is in use (`TwoFactorChallenge.vue` exists; Fortify features are empty).
- U-3 Whether the business-case Angular/Ionic plan reflects a current intention (dated February 2026, `BUSINESS_CASE.md` header).
- U-4 Whether a virus or malware scan exists for uploads.
- U-5 Membership owner: is `CommitteeId::generate()` on approval without a committee intended (`MembershipApplicationController.php:278-280`)?
- U-6 Clause-level conformance of the Membership ratification, Governance authority ownership and Finance separation. These were taken from the mandate and records, not re-audited here.

## 4. Scope limits of this investigation
- Static reading only: no app run, no device test, no load test.
- Not every page and controller was opened. Representative workflows were inspected, and the rest was measured by scan.
