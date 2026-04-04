  ---
  Membership Management Routes

  Group A — Public (auth + verified only, NO org membership required)

  ┌────────┬────────────────────────────────────────┬──────────────────────────────────────┬──────────────────────────────────────────────────────────────────┐
  │ Method │                  URL                   │              Route Name              │                             Purpose                              │
  ├────────┼────────────────────────────────────────┼──────────────────────────────────────┼──────────────────────────────────────────────────────────────────┤
  │ GET    │ /organisations/{slug}/membership/apply │ organisations.membership.apply       │ Show the application form with available membership types        │
  ├────────┼────────────────────────────────────────┼──────────────────────────────────────┼──────────────────────────────────────────────────────────────────┤
  │ POST   │ /organisations/{slug}/membership/apply │ organisations.membership.apply.store │ Submit the application — guards against duplicate/active members │
  └────────┴────────────────────────────────────────┴──────────────────────────────────────┴──────────────────────────────────────────────────────────────────┘

  These two sit outside ensure.organisation deliberately. A person who has never joined cannot pass that middleware.

  ---
  Group B — Membership Types (auth + verified + org member, owner role only)

  ┌────────┬───────────────────────────────────────────────┬────────────────────────────────────────┬───────────────────────────────────────────────────────┐
  │ Method │                      URL                      │               Route Name               │                        Purpose                        │
  ├────────┼───────────────────────────────────────────────┼────────────────────────────────────────┼───────────────────────────────────────────────────────┤
  │ GET    │ /organisations/{slug}/membership-types        │ organisations.membership-types.index   │ List all types, open create/edit modal                │
  ├────────┼───────────────────────────────────────────────┼────────────────────────────────────────┼───────────────────────────────────────────────────────┤
  │ POST   │ /organisations/{slug}/membership-types        │ organisations.membership-types.store   │ Create a new membership type                          │
  ├────────┼───────────────────────────────────────────────┼────────────────────────────────────────┼───────────────────────────────────────────────────────┤
  │ PUT    │ /organisations/{slug}/membership-types/{type} │ organisations.membership-types.update  │ Update name, fee, duration, active status             │
  ├────────┼───────────────────────────────────────────────┼────────────────────────────────────────┼───────────────────────────────────────────────────────┤
  │ DELETE │ /organisations/{slug}/membership-types/{type} │ organisations.membership-types.destroy │ Soft-delete a type (blocked if has fees/applications) │
  └────────┴───────────────────────────────────────────────┴────────────────────────────────────────┴───────────────────────────────────────────────────────┘

  ---
  Group C — Applications (auth + verified + org member, owner/admin/commission)

  ┌────────┬─────────────────────────────────────────────────────────────┬───────────────────────────────────────────────┬──────────────────────────────────────────────────────────────────────────────────┐
  │ Method │                             URL                             │                  Route Name                   │                                     Purpose                                      │
  ├────────┼─────────────────────────────────────────────────────────────┼───────────────────────────────────────────────┼──────────────────────────────────────────────────────────────────────────────────┤
  │ GET    │ /organisations/{slug}/membership/applications               │ organisations.membership.applications.index   │ Paginated list of all applications with status filter tabs                       │
  ├────────┼─────────────────────────────────────────────────────────────┼───────────────────────────────────────────────┼──────────────────────────────────────────────────────────────────────────────────┤
  │ GET    │ /organisations/{slug}/membership/applications/{app}         │ organisations.membership.applications.show    │ Full application detail with approve/reject panel                                │
  ├────────┼─────────────────────────────────────────────────────────────┼───────────────────────────────────────────────┼──────────────────────────────────────────────────────────────────────────────────┤
  │ PATCH  │ /organisations/{slug}/membership/applications/{app}/approve │ organisations.membership.applications.approve │ Approve — creates OrganisationUser + UserOrganisationRole + Member + pending fee │
  ├────────┼─────────────────────────────────────────────────────────────┼───────────────────────────────────────────────┼──────────────────────────────────────────────────────────────────────────────────┤
  │ PATCH  │ /organisations/{slug}/membership/applications/{app}/reject  │ organisations.membership.applications.reject  │ Reject with reason — notifies applicant                                          │
  └────────┴─────────────────────────────────────────────────────────────┴───────────────────────────────────────────────┴──────────────────────────────────────────────────────────────────────────────────┘

  ---
  Group D — Fees & Renewals (auth + verified + org member, owner/admin)

  ┌────────┬─────────────────────────────────────────────────────────┬──────────────────────────────────┬────────────────────────────────────────────────────────────────────────────────────────┐
  │ Method │                           URL                           │            Route Name            │                                        Purpose                                         │
  ├────────┼─────────────────────────────────────────────────────────┼──────────────────────────────────┼────────────────────────────────────────────────────────────────────────────────────────┤
  │ GET    │ /organisations/{slug}/members/{member}/fees             │ organisations.members.fees.index │ List all fees for a specific member                                                    │
  ├────────┼─────────────────────────────────────────────────────────┼──────────────────────────────────┼────────────────────────────────────────────────────────────────────────────────────────┤
  │ POST   │ /organisations/{slug}/members/{member}/fees/{fee}/pay   │ organisations.members.fees.pay   │ Record a manual payment — idempotency key supported                                    │
  ├────────┼─────────────────────────────────────────────────────────┼──────────────────────────────────┼────────────────────────────────────────────────────────────────────────────────────────┤
  │ POST   │ /organisations/{slug}/members/{member}/fees/{fee}/waive │ organisations.members.fees.waive │ Waive a pending fee                                                                    │
  ├────────┼─────────────────────────────────────────────────────────┼──────────────────────────────────┼────────────────────────────────────────────────────────────────────────────────────────┤
  │ POST   │ /organisations/{slug}/members/{member}/renew            │ organisations.members.renew      │ Renew membership — admin can renew anyone, members can self-renew within 90-day window │
  └────────┴─────────────────────────────────────────────────────────┴──────────────────────────────────┴────────────────────────────────────────────────────────────────────────────────────────┘

  ---
  Related non-membership routes that membership depends on

  ┌─────────────────────────────┬───────────────────────────────────────────────────┐
  │         Route Name          │              Used by membership for               │
  ├─────────────────────────────┼───────────────────────────────────────────────────┤
  │ organisations.show          │ Cancel/back links from Apply and Types pages      │
  ├─────────────────────────────┼───────────────────────────────────────────────────┤
  │ organisations.voter-hub     │ Redirect after successful application submission  │
  ├─────────────────────────────┼───────────────────────────────────────────────────┤
  │ organisations.members.index │ Entry point to reach a member's fee/renewal pages │
  └─────────────────────────────┴───────────────────────────────────────────────────┘

  ---
  Do You Need a Separate Membership Management Platform?

  No — and here is why.

  What you already have

  Every function a standalone membership platform provides is already built inside this system:

  ┌───────────────────────────────────┬───────────────────────────────────────────────────────────┐
  │    Standalone platform feature    │                    Where it lives here                    │
  ├───────────────────────────────────┼───────────────────────────────────────────────────────────┤
  │ Define membership tiers           │ /membership-types — owner CRUD                            │
  ├───────────────────────────────────┼───────────────────────────────────────────────────────────┤
  │ Accept applications               │ /membership/apply — public form                           │
  ├───────────────────────────────────┼───────────────────────────────────────────────────────────┤
  │ Approve / reject with audit trail │ /membership/applications/{app}/approve + reject           │
  ├───────────────────────────────────┼───────────────────────────────────────────────────────────┤
  │ Track fee payments                │ /members/{member}/fees/pay                                │
  ├───────────────────────────────────┼───────────────────────────────────────────────────────────┤
  │ Waive fees                        │ /members/{member}/fees/waive                              │
  ├───────────────────────────────────┼───────────────────────────────────────────────────────────┤
  │ Renew memberships                 │ /members/{member}/renew                                   │
  ├───────────────────────────────────┼───────────────────────────────────────────────────────────┤
  │ Self-renewal portal               │ Same renewal route, policy checks isSelf                  │
  ├───────────────────────────────────┼───────────────────────────────────────────────────────────┤
  │ Member role access control        │ UserOrganisationRole + MembershipPolicy                   │
  ├───────────────────────────────────┼───────────────────────────────────────────────────────────┤
  │ Auto-expire stale applications    │ membership:process-expiry daily command                   │
  ├───────────────────────────────────┼───────────────────────────────────────────────────────────┤
  │ Mark overdue fees                 │ Same command                                              │
  ├───────────────────────────────────┼───────────────────────────────────────────────────────────┤
  │ Election eligibility enforcement  │ ElectionMembership::scopeEligible() checks members.status │
  └───────────────────────────────────┴───────────────────────────────────────────────────────────┘

  The architectural reason separation would actually hurt

  The value of this system is the tight coupling between membership and elections:

  Member approved
    → OrganisationUser created    (org access)
    → UserOrganisationRole created (RBAC)
    → Member record created        (expiry tracked)
    → MembershipFee created        (pending payment)
    → ElectionMembership eligible  (can vote when election opens)

  Member expires
    → scopeEligible() returns false (blocked from voting)
    → endMembership() removes from active elections

  If membership lived in a separate platform, you would need:
  - An API between the two systems for eligibility checks on every vote
  - Synchronisation of UserOrganisationRole across systems
  - Shared session/auth between platforms
  - Double the infrastructure, double the failure points

  That integration cost buys you nothing — membership is the gateway to election participation. They belong together.

  When a separate platform would make sense

  Only if your organisation outgrew multi-tenancy entirely — e.g. you needed a public membership portal at a different domain that serves thousands of organisations each with their own branding and custom
  domain. Even then, the right answer would be a dedicated Vue SPA calling the existing API, not a new backend.

  Verdict: keep membership inside the platform. The current architecture is correct.
