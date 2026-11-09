# 10: Decisions for human review

> REPORT · generated · 2026-11-09. **Nothing here is decided.** Claude recommends; the Principal (PO / ARB) decides.

## 1. Classification of decisions

| Class | Decisions |
|---|---|
| **A. Technical: Claude can recommend; engineering-level** | T-1 one repository, folders not workspaces (`04` §1) · T-2 `shared/` boundary plus lint rule · T-3 Vue Router and Pinia for mobile, no new state library · T-4 Playwright for E2E · T-5 OpenAPI generator choice · T-6 device-test tool (Maestro or Appium) |
| **B. Architectural: needs Principal ratification** | D-4 mobile authentication model · D-6 mobile API surface and versioning (`/api/mobile/v1` vs `/mapi/v1`) · D-7 extract-before-expose rule (no API over controller-embedded logic) |
| **C. Product: needs Principal input** | D-1 channel strategy · D-2 Angular/Ionic plan · D-5 pilot workflow |
| **D. Domain and governance: stays under human authority** | D-3 mobile voting and the IP-evidence policy for mobile networks · D-0 security triage priority (touches voting and anonymity backlog ordering) |

## 2. Blocking decisions (needed before the next step)

### D-0: Triage of security findings S-1…S-6 (blocks Phase 0b)
1. **What:** create backlog items for S-1…S-6 (`06` §5) and set their priority against current work (WP-7C → WP-8 → EPIC-005).
2. **Why:** any mobile API would sit next to unhardened token endpoints. S-2 is exploitable today, with or without mobile.
3. **Alternatives:** (a) triage now as standalone security items; (b) fold them into the Phase 4 API work; (c) defer.
4. **Consequences:** (a) costs 2–7 days now and removes the risk; (b) leaves the exposure until Phase 4; (c) leaves S-2 open indefinitely.
5. **Recommendation:** **(a)**. These are independent of the mobile decision.
6. **If postponed:** credential-stuffing and verification-bypass exposure stays open.

### D-1: Channel strategy (blocks Phase 1 scope)
1. **What (business first):** which PublicDigit business capabilities must members and officers reach on a phone, and what do those capabilities require (store presence, push, camera, biometrics)? The channel follows from that: web and PWA only, or also Android and iOS store apps. This question is an input to the strategic roadmap (`docs/plans/20261108-0303-…-plan.md`), not a parallel track.
2. **Why:** it is the single variable that flips the technology ranking (`03` §4).
3. **Alternatives:** (a) web + PWA only; (b) web + PWA now, store app after a D-1b evidence gate; (c) store app now.
4. **Consequences:** (a) lowest cost, no store presence or native features; (b) value early and the option kept open; (c) about 25–85 days more before web mobile quality improves.
5. **Recommendation:** **(b)**.
6. **If postponed:** Phases 0b–2 can still start, because they are needed in every option. Only Phase 4+ waits.

### D-2: The documented Angular/Ionic plan and `/mapi/v1` (blocks the start of Phase 1)

> **Correction (2026-11-09, after Principal review):** the statement "neither is implemented" holds for **this repository only**. The sibling project `personal/public-digit` contains a binding-style rule set for an Angular mobile app on `/mapi` (`public-digit/CLAUDE.md:545-632`, e.g. "MUST treat Angular Mobile as additive", "Angular Mobile → /mapi/ ONLY") and an Angular + Capacitor app (`apps/mobile`, last commit 2026-01-09; cross-project discovery `../reviews/2026-11-07-social-contribution-cross-project-discovery.md:18,64`). No record found supersedes that direction, and whether nrna1 succeeds public-digit is UNKNOWN. **D-2 must not be decided on this report alone**; the decision history must be reconciled first.
1. **What:** retire or keep the business-case statement "Vue 3 (Desktop), Angular/Ionic (Mobile)" with a separate `/mapi/v1` (`docs/publicdigit/business_case/BUSINESS_CASE.md:221-248`; `FEATURE_MATRIX.md:158-167` marks native apps as "Current"). Neither is implemented in this repository.
2. **Why:** it contradicts the reuse goal and misstates the status ("Current") in a product document.
3. **Alternatives:** (a) supersede it with Vue + Capacitor (this report's direction); (b) keep Angular/Ionic; (c) leave it open.
4. **Consequences:** (a) one framework, with the shared-component reuse in `02`; (b) zero UI reuse and two design systems; (c) future sessions may follow the outdated document.
5. **Recommendation:** **(a)**, and correct the "Current" status markers.
6. **If postponed:** risk R-7.

## 3. Non-blocking decisions (needed later)

| ID | Decision | Needed before | Recommendation |
|---|---|---|---|
| D-3 | Is voting ever in mobile-app scope? If yes, how do the IP limits and IP binding treat carrier networks, and what weight does a native device signal carry (V-2…V-4)? | any voting work on mobile; **also relevant to mobile browsers today** (R-4) | Keep voting web-only (browser/PWA) until PBDIGIT-284 and G-1 are closed and V-1 has an approved design. Commission telemetry on IP refusals per network type |
| D-4 | Mobile authentication: Sanctum personal tokens with expiry and device scope vs OAuth2/PKCE (Passport) · refresh strategy · 2FA on mobile · biometric unlock as a local gate only | Phase 4 | Sanctum tokens with expiry, per-device names and abilities, revoke-on-password-change; biometrics unlock the stored token and never replace server auth |
| D-5 | Pilot workflow | Phase 6 | Membership Registration + Member Profile |
| D-6 | API path and versioning | Phase 4 | `/api/mobile/v1`; additive changes only within a version |
| D-7 | Extract-before-expose as a standing rule | Phase 4 | adopt; enforce by an architecture test |

## 4. What happens next if the Principal agrees
1. Decide D-0, D-1 and D-2.
2. Create backlog items: security S-1…S-6; Phase 1 "frontend foundations"; Phase 2 "responsive member flows".
3. Each item enters EP-01: plan → approval → RED → GREEN.

**This investigation does not transition into implementation without explicit authorisation.**

## Decision note (2026-11-09): D-2 decided
**D-2: the Principal agreed with the recommendation** ("I agree with your recommendation", 2026-11-09): the Angular/Ionic plan and `/mapi` design of `public-digit` are recorded as **historical**, kept as a reference source only, and **not continued** in nrna1. The decision history was reconciled first, as the correction above required: `../reviews/2026-11-09-public-digit-mobile-strategy-cross-project-assessment.md` (A-1…A-6 apply as principles; N-1…N-8 do not apply). The technology for any future app is **not** decided by this note; it waits for MP-1 (`../reviews/2026-11-09-mobile-application-strategic-ddd-baseline.md` §11). Working decision; not a ratification act. Text above unchanged.

## Decision note (2026-11-09): D-1 for now
**D-1 (channel strategy): responsive web only, for now.** From the Principal's answer to MP-1 ("a)": nothing is needed from an installed app that the phone browser cannot do). No PWA and no store app are planned now; D-4, D-5, D-6 are not needed. Reopen when a named business outcome requires an installed app. Basis: `../reviews/2026-11-09-mobile-application-strategic-ddd-baseline.md` (decision section). Working decision; not a ratification act. Text above unchanged.

## Decision note (2026-11-09, later): D-1 staged
The Principal revised D-1: responsive web first, then a PWA (installation at least for participant use such as membership), then evaluate a standalone app. The earlier "responsive web only for now" note is superseded in that respect. PWA scope is open (PD-PWA-1). Detail: `../reviews/2026-11-09-mobile-application-strategic-ddd-baseline.md`, last section.
