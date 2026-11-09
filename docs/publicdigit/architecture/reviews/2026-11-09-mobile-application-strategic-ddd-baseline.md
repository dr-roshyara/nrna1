# PublicDigit mobile channel: strategic baseline

| | |
|---|---|
| **Status** | **PROPOSED baseline** (consolidated 2026-11-09, evening). Ratifies nothing; promotes no working decision; changes no canonical record. Not independently reviewed (Claude family). |
| **Commissions** | MOBILE-STRATEGIC-01 (morning) and "Mobile Channel — Strategic Baseline Reconciliation" (evening), Principal, 2026-11-09. Strategic discovery only; implementation not authorised by these commissions. |
| **Placement** | `php scripts/doc-placement.php --scope=product-specific --maturity=research --domain=publicdigit` → `docs/publicdigit` (exit 0); `architecture/reviews/` |
| **Snapshot** | branch `election-flow-updated`, HEAD `e2051cbbe`. PBDIGIT-338 code is in the working tree, **not committed**. |
| **Revision rule** | This file was never committed; this version **replaces** the morning text and its appended notes. Their decisions survive in §10; nothing deleted from it is a second record. |
| **Supersedes in part** | `./2026-11-09-strategic-ddd-reconciliation-and-participant-channel-mapping.md` §C, §F (correction note appended there). Mobile report `10` points here. |

**Labels:** **RAT** ratified or frozen · **WD** accepted working decision · **FACT** verified in the repository on this date · **P** proposed (analysis) · **REC** recommendation · **UNKNOWN** · **PRINCIPAL** decision required.

**Sources (cited by short name):**
| Short | Record | Maturity |
|---|---|---|
| DEM | `docs/publicdigit/reviews/2026-11-09-business-demand-discovery-record.md` (DD-1…DD-15, R-1…R-16) | DD-13, DD-15 **WD**; other DD and R-n are interview findings (P) |
| REC | `./2026-11-09-strategic-ddd-reconciliation-and-participant-channel-mapping.md` (PD-A…PD-E) | P; PD-E **WD** |
| MAP | `docs/publicdigit/reviews/2026-11-08-strategic-context-map-and-governance-tactical-model.md` (Session B) | D-nn rows **WD**; §33, §38 tactical models P |
| DR | `docs/publicdigit/reviews/2026-11-08-organisation-strategic-discovery-record-structure-mandates-conventions.md` §11 | register of D-1…D-87 |
| RA | ratified Membership act with CR-01 (D-73) | **RAT** |
| XD-3 | `docs/publicdigit/reviews/2026-11-09-identity-xd3-arb-act.md` (D-75) | **RAT** (ARB act) |
| M-TDD-01 | `docs/publicdigit/membership/architecture/2026-11-09-m-tdd-01-membership-participant-operations-tactical-design.md` | P |
| MOB | `docs/publicdigit/architecture/mobile-investigation/00`…`10` | read-only technical assessment; its target architecture is conditional |
| VO-1, BDR | voting anonymity and Round 49 records | **RAT/FROZEN** |

---

## 0. Disagreements with the commission (stated first)

1. **"Check whether the forum has a design" is already answered.** The morning version of this baseline corrected that claim (now in §11). Deliberation's strategic design is **complete as a working design** (MAP §35: D-50…D-56, DL-1…DL-17; "no new strategic round is needed"). What is missing is **tactical** design, which MAP §35 orders after Governance and Membership/Organisation. No strategic discovery is proposed here.
2. **The single next tactical commission should be Identity participant access, not a new Membership tactical design.**
   - Membership's participant tactical design already exists (M-TDD-01, P). What blocks the Membership phone journey is mostly **outside** Membership.
   - The applicant cannot reach the member area without an account-claim mechanism (D-75: organisation-issued claim code, no linking by e-mail). Today approval creates or reuses an account by e-mail (G-2).
   - Several live flows find or reuse accounts by matching e-mail (G-2, `PBDIGIT-341`). The live Google sign-in has its own defects (G-1, `PBDIGIT-340`). An installed PWA would make sign-in more prominent.
   - **PBDIGIT-339** is an implementation defect, not a design question. Its fix is authorised separately (the Principal accepted that recommendation) and does not need a tactical design.
3. **The voting gates are not PWA gates; they apply to the browser today.**
   - `PBDIGIT-284` (OPEN: "fix before any production election") and `PBDIGIT-337` (the current casting links voters to ballots) affect every phone that votes in a browser now.
   - Treating them as conditions for *promoting* an installed app understates them. §6.3 keeps both separately: the browser defects, and the extra PWA conditions.
4. **"Shared responsive components where reuse is justified" (Stage 1) needs a qualifier.** Evidence from PBDIGIT-338 shows that pages own their containers (adding a layout-level container would double the padding on about 100 pages). Shared work belongs in the **header, menu and layout frame**. Page bodies are fixed page by page, as journeys need them. This is not a disagreement with the direction, only with an implied layout-level component programme.

---

## 1. Purpose and scope

- **Purpose:** give applicants, members, voters and candidates their participant operations on a phone, through the existing PublicDigit platform. Then let them install it (PWA), and decide on a store app only on evidence. **(WD:** staged direction, Principal 2026-11-09; DD-15.)
- **In scope:** participant operations (PD-E **WD**: classified by operation, not by person); the PWA product shape; security and privacy constraints for any channel; tactical readiness.
- **Out of scope:**
  - administrative and governance operations, which stay desktop-oriented (DD-13 **WD**);
  - any technology selection (Capacitor, Angular and others; D-2 **WD**: the Angular plan is historical);
  - implementation.
- **Organisation mode (FACT):** membership operations exist only where the organisation uses full membership. In election-only organisations a voter has voter operations only (`app/Domain/Election/Enum/VoterSourceStrategy.php:18-28`; DD-9).

## 2. Product and channel principles

| # | Principle | Label |
|---|---|---|
| CP-1 | **Mobile is an access channel**, not a bounded context, a source of authority or a separate business system. There is no "Mobile User", "Mobile Member" or "Mobile Capacity". | **WD** (MD-1, staged direction) |
| CP-2 | **The channel requirement attaches to the operation.** The same person submits an application on a phone and approves applications at a desk. | **WD** (PD-E) |
| CP-3 | Participant operations **must** work on a phone; desktop is optional for them. | **WD** (DD-15) |
| CP-4 | Administration and governance operations are desktop-oriented. They must not break on small screens, but get no phone design work. | **WD** (DD-13) |
| CP-5 | **One authoritative operation per business act**; every channel calls it. The phone decides nothing. | **WD** (D-7); RA §11 |
| CP-6 | **One PublicDigit application**, accessed three ways: phone browser → installed PWA → (perhaps) store app. All use the same backend and rules. | **WD** (staged direction) + **REC** |
| CP-7 | **Showing a link grants nothing.** Every destination enforces authentication and authorisation on the server. | **REC** (adopted in PBDIGIT-338) |
| CP-8 | **Belonging never comes from an address or a device location.** Forums, eligibility and "your area" come from recorded belonging. | **WD** (D-80, DD-6) |

**Why no Mobile context (P):** every participant operation in §3 already has an owning context. A Mobile context would own no fact, no invariant and no decision. It would only duplicate rules (CP-5) or become a second authority (CP-1).

## 3. Participant capability and operation map

Columns:
- **Phone:** M = must work on a phone (DD-15).
- **Web:** suitable for responsive web now.
- **PWA:** candidate entry point in the installed app.
- **R/W:** read or write.

The owner is the context that owns the operation and records its result. "Decides" names who takes the business decision behind it, usually at a desk.

### 3.1 Membership

| # | Operation (R/W) | Actor → outcome | Owner · facts from · decides · records | Today (FACT) | Phone / Web / PWA | Prerequisites | Open |
|---|---|---|---|---|---|---|---|
| O-1 | submit application (W) | applicant → a declaration is on record | Membership (**RAT**) · Identity `PersonRef` (D-75) · officer admits with authority evidence (RA:72; AuthorityCheckPort) · Membership | two paths with different rules (`PublicMembershipApplicationController.php:28-85`; `MembershipApplicationController.php:56-126`); public form renders at 360 px without overflow (PBDIGIT-338 probe) | M / yes / **yes (pilot)** | one operation for both paths (M-TDD-01; kernel Slice 3–4, PD-M1) | PD-M1, PD-M2 |
| O-1a | receive outcome; get access (R, then W) | applicant → knows the result; can sign in | Membership (outcome) · **Identity** (account claim, D-75) | approval creates or reuses an account **by e-mail** (`MembershipApplicationController.php:220-221`): conflicts with D-75 (G-2) | M / yes / yes | **claim-code mechanism** (XD-3 §2a); Identity commission | — |
| O-2 | view own membership status (R) | member → sees status, term, own applications | Membership (**RAT**) · Finance for dues | `MembershipDashboardController::getMemberData`; **cached per organisation and role, not per user** (`:28-32`) → **PBDIGIT-339** | M / yes / **yes (pilot)** | **PBDIGIT-339 fixed** | — |
| O-3 | renew (W) | member → a new term segment, coverage unchanged (RA, INV-12) | Membership (**RAT**) · rule or decision per organisation | legacy, immediate, in place (`MembershipRenewalController.php:20-99`); kernel has no Renew | M / later / later | kernel Renew slice | **PD-M3** |
| O-4 | view dues and payments (R); pay (W) | member → knows what is owed / pays | Finance (N-5 interpretation) · charges from periods (RA:155) | officer records payments only | M / read yes / read later | FeePaid ownership; online payment deferred (PD-B **WD**) | — |
| O-5 | update own contact details (W) | member → reachable | organisation-local profile; owner pending (INV-AFF-1, proposed Organisation; MAP §31) | legacy user/member fields | M / no / no | **INV-AFF-1** | — |
| O-6 | request listing in another unit after moving (W) | member → a governance body verifies the move | Organisation (**WD** D-3, D-22) · decided by a governance body (DD-7) | none | M / no / no | Phase 3 | — |

### 3.2 Member forum (Deliberation; name provisional) and notices (Communication)

| # | Operation (R/W) | Owner · decides | Today (FACT) | Phone / Web / PWA | Prerequisites |
|---|---|---|---|---|---|
| O-7 | read discussions in own unit and every forum above (R) | Deliberation (**WD** D-50, D-56) · access derived from Organisation belonging, never from lists or addresses (MAP §35; D-80) | **not implemented**; Openions exists, with unescaped output S-7 (`OpenionController.php:94`, `Openions.vue:46`) | M / after build / **yes (2nd)** | Deliberation tactical design (after Governance and Membership/Organisation, MAP §35) |
| O-8 | post a contribution; reply (W) | Deliberation · write within the forum's scope (D-56); posting identity per D-55; moderation from positions or rules (DL-3, D-53) | as O-7 | M / after build / yes | as O-7; S-7 removed or fixed before any posting surface |
| O-9 | support an idea; withdraw support (W) | Deliberation · one support per entitled person, withdrawable (DL-1, DL-10); visibility per D-52; **rank never decides** (DL-2) | none | M / after build / yes | as O-7 |
| O-10 | co-sign and submit a motion (W) | Deliberation until submission; **Governance owns it once submitted** (D-51, DL-5). Support or discussion never confers authority. | none | M / after build / yes | Governance submission pathway (MAP §33, P) |
| O-11 | read notices and official messages (R) | Communication (**WD** D-57…D-64) · audiences resolved by their owners (MAP §24) | e-mail newsletters; audiences query other contexts directly (MAP §26 [F], G-5) | M / partly / yes (in-app notices later) | Communication tactical design |

### 3.3 Election

| # | Operation (R/W) | Owner · facts · decides | Today (FACT) | Phone / Web / PWA | Prerequisites | Open |
|---|---|---|---|---|---|---|
| O-12 | read election information and results (R) | Election (**WD** D-48) | pages exist | M / yes / yes | — | — |
| O-13 | see whether on the confirmed voter list (R) | Election · Membership and Organisation facts at τ (MAP §38.3) · the governance body sets the rule and confirms the list (DD-11) | person-level "approved by", no recorded decision (`VoterlistController.php:40-54`) | M / partly / later | `PBDIGIT-336` | PD-C |
| O-14 | object to exclusion (W) | Election as the process (PD-A **WD**) · competent body per organisation rules | none; Contestation's target set is closed (`TargetType.php:10-18`) | M / no / no | competent authority (Phase 3) | — |
| O-15 | submit, edit, withdraw a candidacy (W) | Election · the election committee accepts (desk) | submit exists (`CandidacyApplicationController::create`); withdrawal by an officer only | M / submit yes / yes | participant withdrawal operation | — |
| O-16 | propose or second a candidate (W) | Election (an act with eligibility consequences, not a forum support) | names typed only | M / no / no | organisation rule | UNKNOWN |

### 3.4 Voting

| # | Operation (R/W) | Owner | Today (FACT) | Phone / Web / PWA | Prerequisites |
|---|---|---|---|---|---|
| O-17 | access the voting journey (R) | Election issues the right (**WD** D-44, D-46) | session-bound browser flow under `v/{vslug}/…`; used on phones (DD-3) | M / yes (existing) / reachable, **not promoted** | §6.3 |
| O-18 | cast a ballot (W) | Voting (**RAT** VO-1; **WD** D-44…D-46) · Voting never sees the person | current casting links voters to ballots (`PBDIGIT-337`); vote hash can link (`PBDIGIT-284`, OPEN) | M / existing, **with defects** / never offline | `PBDIGIT-284`, `PBDIGIT-337`; D-44 design |
| O-19 | receive a confirmation (R) | Voting (**WD** D-45) · it proves acceptance, never content | receipt codes and self-lookup (`VotingReceiptController.php`; `VoteController.php:2272`) | M / existing / reachable | D-45 Level A |
| O-20 | replace a lost voting right (W) | Election (**WD** D-47) · default **no replacement** | UNKNOWN | M / no / no | organisation rule |

**Corrections kept from the morning version:**
- R-8 and R-9 are Deliberation, not "C18 / no design".
- R-14 (proposing a candidate) is an Election act, not a forum support.
- R-16's "confirmation" proves acceptance only (D-45).
- O-20 follows from D-47 and is phone-specific: a lost phone may hold the only credential.

## 4. Context ownership and relationships

```
                         Identity — PersonRef per organisation; account claim by code   [RAT D-75]
                            │ PersonRef only (no e-mail linking, nothing crosses organisations)
   ┌────────────────────────┼───────────────────────────┬──────────────────────────────┐
   ▼                        ▼                           ▼                              ▼
Membership ──coverage──▶ Organisation ──belonging──▶ Deliberation ──motion (D-51)──▶ Governance
 [RAT]                    [WD; D-80: address ≠        [WD D-50..56]                 [WD] authority,
   │ facts at τ           belonging]                     │ signals only                rules, list
   ▼                        ▼                            ▼                           confirmation
 Election (process, eligibility evaluation, rights issuance) ◀───────────────────────────┘
   │ credential, no identity crosses (D-44)
   ▼
 Voting [RAT VO-1; WD D-44..49]      Finance ◀─ fees reference periods      Communication ◀─ audiences resolved by owners [WD]

 ── every arrow is server-side ──   Channel layer (no authority):  phone browser │ installed PWA │ (store app?)
                                     all call the same Laravel operations; none holds business state
```

| Capability | Owns operation and records | Supplies facts | Decides | Consumers | Coupled to HTTP/session today (FACT) | Needs a channel-neutral boundary before a second channel? |
|---|---|---|---|---|---|---|
| Membership application, status | Membership | Identity (`PersonRef`) | officer with authority evidence (Governance competence) | Election, Organisation, Finance | **yes**: rules in controllers; the ratified kernel is **unrouted** | yes, but cutover (Slice 3–4) already requires it; the PWA does **not** |
| Renewal | Membership | Finance (settlement) | organisation rule (PD-M3) | Finance | yes (legacy controller) | as above |
| Forum, supports, motions | Deliberation → Governance on submission | Organisation (belonging) | moderators from positions or rules; Governance after submission | Communication | not built | built channel-neutral from the start (CP-5) |
| Notices | Communication | audience owners | the editor designated at a sitting (D-58, D-60) | — | newsletter controller queries other contexts (G-5) | yes, at its tactical design |
| Voter list, candidacy | Election | Membership, Organisation at τ | Governance (rules, confirmation); election committee | Voting (rights) | yes | at Election's tactical design |
| Ballot | Voting | Election (credential only) | — | results | yes (session-bound, defects) | **D-44 redesign**, not a channel change |

**Preserved without change:**
- Membership's ratified facts and lifecycle (RAT).
- Governance's authority (WD).
- Organisation's structure and belonging (WD; D-80).
- Election's eligibility evaluation (RA:110).
- Voting anonymity (RAT VO-1).
- The separation of Communication and Deliberation (MAP §35).
- Forum participation never acquires governance authority (D-51).

## 5. PWA product architecture

### 5.1 Three ways to reach the same platform

| | Responsive website (Stage 1) | Installed PWA (Stage 2) | Store app (Stage 3, evaluate only) |
|---|---|---|---|
| What the member gets | pages that work at phone width | home-screen icon, full-screen window, possibly notifications | store listing, device features |
| Code | existing Laravel + Vue + Inertia | the **same** site plus a manifest, icons and a service worker | a bundled frontend; MOB `02`: about 17% of code reusable directly |
| Sign-in | existing session cookie | the **same** session cookie, inside the installed window | needs token authentication and an API (MOB `05`: `/api/v1` is session-based, not a mobile API) |
| New business code | none | none | extracted operations for every exposed act (CP-5) |
| Label | **WD** | **WD** direction; design **P** | **WD**: evaluate only on evidence |

**Installation creates no bounded context, changes no ownership or permission, and needs no new API (P).**

### 5.2 PD-PWA-1: start page and scope (recommendation; Principal decides at Stage 2)

**FACT:**
- Participant and admin pages share URL prefixes: `organisations/{organisation}/join` and `…/candidacy/apply` sit beside `…/members` and `…/elections/{election}/voters/manage`.
- Voting is under `v/{vslug}/…`; sign-in is at `/login`; the member hub is at `/my-organisations` (`organisations.index`, authenticated).

| Option | Manifest | Consequence |
|---|---|---|
| **A (REC)** | one PublicDigit app; scope `/`; start page **`/my-organisations`** (unauthenticated users are redirected to sign-in, then return) | Everything the member needs, including sign-in and voting, stays inside the installed window. An officer who installs it also reaches admin pages; permissions are unchanged (CP-7). No route change. |
| B | scope limited to `/organisations/` | **Not workable:** `/login`, `/my-organisations` and voting (`/v/…`) fall outside, so the app jumps to a browser tab at sign-in. |
| C | a new participant URL area (for example `/me/…`) as scope | Needs new routes beside the existing ones (a second route surface per operation). Justified only if evidence shows admin pages must be unreachable from the installed window. |

**Why A is safe (P):**
- Scope decides which pages open **inside the app window**. It does not decide what is **cached**; that belongs to the service worker (§5.3).
- A function can be available in the PWA without anything being stored offline.

### 5.3 Service worker rules for Stage 2 (REC; constraints labelled)

1. **Intercept only static build assets and approved icons and images** (`/build/…`, the icon set). Cache them by version.
2. **Every other request bypasses the worker** (no `respondWith`): pages, Inertia JSON, forms, files and APIs. Authenticated pages, membership status, eligibility, voter lists, ballots and voting responses are therefore **never stored** (K-5, K-10).
3. **Voting paths are excluded from interception entirely**, including the offline fallback. (Labels: **WD** D-44…D-47; **RAT** VO-1.)
4. **Offline page:** a static page shown only when a GET navigation outside voting fails. It says "you are offline" and carries **no** user data.
5. **No offline or queued writes of any kind**, and certainly no queued ballots (K-10, BDR).
6. **Updates:** a new version activates on the next launch. No version pinning that could keep stale election pages.
7. **Sign-out** clears the session as today. The worker's static cache holds nothing personal, so a shared phone keeps no member data.
8. **Push notifications: not in the first PWA.** When added, they need a business purpose (MP-1 reopened) and the Communication rules (K-7: no "has voted" audiences). They must **never** carry ballot-related content or a device token joinable to voting (K-6).

**UNKNOWN (verify on target devices at Stage 2):**
- whether an installed web app on iOS shares the browser's sign-in, or members must sign in again inside the app;
- install prompts and behaviour per browser.

These affect member instructions, not the architecture.

## 6. Treatment of the three participant areas

### 6.1 Membership: first pilot
- **Journey:** submit (O-1) → outcome and access (O-1a) → signed in → own status (O-2) → later renew (O-3).
- **Already done (FACT):**
  - The shared header and menu are responsive, with member links (PBDIGIT-338, uncommitted).
  - The public application form works at 360 px.
- **Must be completed before Membership is a reliable phone journey:**
  1. **PBDIGIT-339** fixed, with a regression test (authorised).
  2. **Account access by claim code** instead of e-mail reuse: Identity participant access (D-75; G-2). Until then, step O-1a is unreliable and conflicts with an accepted decision.
  3. ~~G-1~~ resolved: social sign-in removed (`PBDIGIT-340`, P-I5 decided).
  4. A **signed-in browser check** of the journey with synthetic data (throwaway database).
  5. **One application operation** behind both paths (M-TDD-01; kernel Slice 3–4). This is driven by cutover, not by the phone.
- **Not now:** renewal (PD-M3; kernel Renew slice), payment (Finance), contact changes (INV-AFF-1).
- **No mobile-specific membership logic** (CP-5; cross-project assessment N-1, N-2).

### 6.2 Member forum: second area
- **Strategically settled (WD):**
  - two contexts (Deliberation and Communication);
  - geographic and committee forums;
  - participation derived from belonging;
  - signal versus formal co-signature;
  - motions pass to Governance on submission;
  - moderation from positions or rules;
  - DL-1…DL-17 (MAP §35).
- **Needed before any PWA release:**
  - Deliberation tactical design (forum, contribution, support, motion aggregates; ranking views; duplicate linking; who besides members may write), in the order MAP §35 sets;
  - the Governance submission pathway;
  - Communication tactical design for in-app notices.
- **Security first:** S-7 (Openions unescaped output) is fixed, or Openions is retired, before any phone posting surface is promoted.
- **No new Forum context:** the boundary is Deliberation (name provisional).

### 6.3 Voting: reachable, not promoted
- **Existing (FACT):** members already vote in phone browsers (DD-3). Under option A the installed app opens the same pages.
- **Browser defects that apply now, independent of the PWA:**
  - `PBDIGIT-284` (vote hash; "fix before any production election");
  - `PBDIGIT-337` (casting links voters to ballots);
  - `PBDIGIT-286` (IP limit). Its records disagree: the backlog header says "OPEN, needs a PO decision first", while `.claude/CONTEXT-publicdigit.md` (2026-10-15) records P5/P6 **accepted**, with production release on hold. The latest dated record is the acceptance; the header looks stale (not corrected here).
- **Additional conditions before the PWA is promoted as a voting channel:**
  - the D-44…D-46 design implemented (rights issuance separated from ballot submission; device-side credential; network quota at issuance);
  - D-47 (lost right) set per organisation;
  - service-worker exclusion (§5.3 rule 3) verified;
  - no push linked to voting;
  - ~~G-1~~ resolved (social sign-in removed, `PBDIGIT-340`).
- **No redesign of Voting for the PWA.** The PWA is a window onto the same operation.
- **J-8 consequence (P):** under D-44 the voter's device takes part in the protocol (blind signatures, RFC 9474). Shared and lost devices become voting business rules (O-20), whatever the channel.

## 7. Staged technical evolution

| Stage | Content | Status |
|---|---|---|
| **1 Responsive web** | Existing Laravel 12 + Vue 3 + Inertia, routes and session sign-in. Nine layouts stay distinct. Shared work in the header, menu and layout frame (PBDIGIT-338); page bodies fixed per journey (for example `Welcome.vue` overflow at 360/390 px). | **in progress** (338 uncommitted) |
| **2 PWA** | Manifest, icons, the service worker per §5.3, offline page, install and update behaviour. PD-PWA-1 decided. Push only after a business purpose and Communication rules. | design **P**; not started |
| **3 Store app** | Evaluate only on evidence (MP-1 reopened with a named need). Then reassess frontend reuse (MOB `02`), design a versioned API and token authentication (MOB `05`), and extract operations for both web and API controllers. Reconcile the Angular history (D-2: historical). | **not to be designed now** |

## 8. Security and privacy constraints (all channels)

| # | Constraint | Basis |
|---|---|---|
| K-1 | no channel decides membership, belonging, eligibility, governance outcomes or votes | MD-1; D-7; RA §11 |
| K-2 | one authoritative operation per business act | D-7 |
| K-3 | nothing crosses organisations, including in the app, push payloads, logs and errors | D-75 |
| K-4 | no account linking or takeover by matching e-mail or name | D-75 |
| K-5 | no identity on the cast path; no ballot-related e-mails, queue entries or files; no receipt that unlocks a ballot | D-45 Level A |
| K-6 | no device identifier, push token or analytics joinable to rights issuance, ballots or results | VO-1; D-44 |
| K-7 | no "has voted" audiences; no turnout-chasing notifications | MAP §24 |
| K-8 | the network limit applies at rights issuance inside Election; Voting holds no IP | D-46 |
| K-9 | a lost voting right is replaced only by organisation rule; default none | D-47 |
| K-10 | no offline or queued writes; no cached authenticated, eligibility or ballot data | BDR; MOB `06` |
| K-11 | organisation rules are never platform defaults | D-1b |
| K-12 | channel requirements attach to operations | PD-E |
| K-13 | belonging, forum scope and eligibility never come from an address or a device location | D-80; DD-6 |
| K-14 | one member's personal data is never served to another member, including through caches | PBDIGIT-339 (defect evidence) |

**Live defects relevant to phones (FACT, all OPEN):**
- G-1: Google sign-in (`PBDIGIT-340`). **Corrected 2026-11-09:** the e-mail takeover in `GoogleController.php:44` is **not routed** (commented out since 2022). The live `GoogleLoginController` matches by `google_id`, but stores a plain-text constant password and shows raw database errors. The takeover code remains and would return if re-routed.
- G-2 widened: voter import, membership approval, participant add and `ImportUsers` resolve accounts by matching e-mail (`PBDIGIT-341`); the import preview tells an organisation that an address already has an account.
- G-2: approval reuses accounts by e-mail.
- `PBDIGIT-339`: membership self-view shared through the cache.
- `PBDIGIT-284`, `PBDIGIT-337`: voting anonymity.
- `PBDIGIT-286`: IP limit.
- S-7: Openions output.

## 9. Tactical design readiness

| Area | Classification | Reason |
|---|---|---|
| **Identity participant access** (sign-in, claim code, recovery, organisation selection, shared devices) | **ready for tactical design** | D-75 ratified; blocks O-1a, G-1, G-2 and every installed-app sign-in |
| Membership participant operations | **already designed (P, M-TDD-01)**; renewal **blocked on PD-M3**; own status **blocked by PBDIGIT-339** (fix authorised) | |
| Deliberation (forum) | **blocked by ordering**: after Governance and Membership/Organisation (MAP §35); strategy complete | |
| Communication (notices) | needs limited strategic clarification only for push purpose (MP-1); otherwise ready after audience owners | |
| Election participant operations | **blocked**: rules gap (`PBDIGIT-336`, PD-C), competent authority for objections (Phase 3) | |
| Voting mobile/PWA exposure | **blocked by security remediation** (`PBDIGIT-284`, `PBDIGIT-337`) and D-44 implementation | |

**Sequence (REC):**
1. PBDIGIT-339 fix (authorised; small).
2. **Identity participant access** tactical design.
3. Membership journey verification and kernel cutover (existing roadmap).
4. PWA technical design (PD-PWA-1).
5. Deliberation tactical design in its MAP §35 order.
6. Voting PWA assessment after the voting defects are fixed.

## 10. Principal decision register

**Already decided (not re-asked):**
- Staged direction (responsive → PWA → evaluate a store app).
- MP-1 (a): nothing *requires* an installed app. The PWA is chosen for convenience, not for a missing capability.
- D-2: the Angular plan is historical.
- DD-13, DD-15, PD-E.
- Membership as the pilot.
- PBDIGIT-338 conditionally approved.
- PBDIGIT-339 investigation and correction authorised (accepted recommendation).
- `accent` is the documented brand gold (UI_GUIDELINES 2026-05-29; evidence, not a decision).

| # | Question | Why yours | Options and consequences | REC | When |
|---|---|---|---|---|---|
| ~~P-1~~ | **Decided 2026-11-09:** the next design work is Identity participant access (accepted recommendation). Discovery done: `./2026-11-09-identity-participant-access-strategic-discovery.md` | — | — | — | — |
| ~~P-2~~ | **Decided and done 2026-11-09:** synthetic signed-in journey on a throwaway database passed; "My membership" link added (`PBDIGIT-338`) | — | — | — | — |
| P-3 | NRNA's renewal rule (PD-M3): on request, after payment, or after acceptance? | organisation rule (D-1b) | defines the Renew slice | your knowledge | before Renew |
| P-4 | PD-PWA-1: start page and scope | product shape | A / B / C (§5.2) | **A** | Stage 2 |
| P-5 | Should the header use gold decoratively, given the rule that gold is for governance actions? | visual identity | keep (restored intent) / reduce to neutral borders | non-blocking | any time |
| P-6 | Purpose of notifications, if any | business need (MP-1 reopened) | none / official notices / forum activity | defer | Stage 2 |
| P-7 | PBDIGIT-286 release (P5/P6 accepted 2026-10-15; production release on hold) | release is the PO's | release / hold | carried, not new; correct the stale backlog header | before production elections |
| — | the name of the forum context | non-blocking | Deliberation / other | when convenient | — |

**Not asked (they follow from accepted direction):** service-worker caching rules, mobile authentication, API design, framework.

## 11. Final judgement

1. **Established for responsive web:** the channel principles (CP-1…CP-8), the ownership map (§3–4) and the constraints (§8). The shared header and menu exist (338). Stage 1 work may proceed page by page along the Membership journey.
2. **Before Membership is a reliable phone journey:** PBDIGIT-339; claim-code access instead of e-mail reuse (Identity, D-75); G-1; a signed-in journey check with synthetic data; one application operation behind both paths (cutover).
3. **Before the forum enters the PWA:** Deliberation and Communication tactical design in MAP §35's order; the Governance submission pathway; S-7 removed.
4. **Before voting is promoted in the PWA:** `PBDIGIT-284` and `PBDIGIT-337` fixed (needed for the browser anyway); D-44…D-47 implemented; the service-worker exclusion verified; no push linked to voting; G-1 closed.
5. **Safely deferred:** PD-PWA-1 (Stage 2); push; API, token authentication and frontend packaging (Stage 3 only); the forum context's name.
6. **The single next tactical design commission:** **Identity participant access under D-75** (sign-in, organisation-issued claim code, recovery without e-mail takeover, organisation selection without revealing others, sessions on shared phones).

## Traceability
- **Records read (current working tree):** DEM; REC with its correction note; MAP §21–§38 (§35 Deliberation status; §24 Communication; §22 Election and Voting); DR §11 D-44…D-87 (D-80, D-81 new since the morning); XD-3; RA with CR-01; M-TDD-01; MOB `00`, `02`, `03`, `05`, `06`, `10`; the cross-project assessment; `PBDIGIT-284`, `286`, `336`, `337`, `338`, `339`.
- **Code (FACT):** `routes` (route list 2026-11-09); `MembershipDashboardController.php:20-38, 98-104, 142-186`; `MembershipApplicationController.php:56-126, 220-221`; `PublicMembershipApplicationController.php:28-85`; `MembershipRenewalController.php:20-99`; `GoogleController.php:44`; `VoterlistController.php:40-54`; `TargetType.php:10-18`; `VoterSourceStrategy.php:18-28`; `OpenionController.php:94`; `VotingReceiptController.php`; `VoteController.php:2272`; `resources/js/Components/Jetstream/PublicDigitHeader.vue` (working tree).
- **Earlier corrections preserved:**
  - Reconciliation report: "no design" for R-7…R-9 was wrong (D-50…D-64).
  - M-TDD-01: PD-M4 withdrawn under D-75, and contact details are organisation-local.
  - D-44…D-49 are working decisions, not ratified.
  - "Identity → Membership: verified contact information" is the wrong dependency; Identity supplies only `PersonRef`.
