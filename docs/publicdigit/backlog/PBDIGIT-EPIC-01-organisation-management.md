# PBDIGIT-EPIC-01 — Organisation Management

**Journey segment:** `Create Organisation → Configure Organisation` (the first thing any customer does)
**Epic status:** `IMPLEMENTED — NOT VERIFIED` · **Gated by** `PBDIGIT-00` (see `README.md`)
**Baseline:** `main` @ `9158ef11` · **Created:** 2026-08-05

---

## PBDIGIT-01 — Create an organisation

| | |
|---|---|
| **Customer goal** | *"I represent an association. I want it registered on the platform so we can govern ourselves here."* |
| **Business steps** | reach the signup/creation form → provide organisation identity (name, slug, contact) → organisation exists with me as owner |
| **Route → code** | `OrganisationController::create():52` (form) → `OrganisationController::store():264` → `Organisation` model → event `app/Events/OrganisationCreated.php`; invitations model `OrganisationInvitation` |
| **Business rules evidenced** | multi-tenancy: every record is scoped by `organisation_id`; **demo mode = `organisation_id NULL`, live mode = `organisation_id X`** (root `CLAUDE.md`); tenant isolation enforced by the `BelongsToTenant` global scope + `TenantContext` middleware, guarded by fitness test `tests/Architecture/TenantIdEnforcementTest.php` |
| **Verification** | create two organisations → confirm neither can see the other's data (the isolation claim is the platform's core promise) → confirm the creator receives owner role |
| **Known findings** | `Evidence not found`: the *self-service* creation route (the controller methods exist; whether a public route reaches them, or whether creation is platform-admin-only, was not traced) |
| **Status** | `IMPLEMENTED — NOT VERIFIED` |

## PBDIGIT-02 — Configure the organisation

| | |
|---|---|
| **Customer goal** | *"I want to set up how my organisation works — membership rules, language, our geography."* |
| **Business steps** | open settings → choose membership mode → set language → define the geographic hierarchy the organisation uses |
| **Route → code** | `routes/organisations.php:97` settings page · `:98` `PATCH /settings/membership-mode` · `:99` `PATCH /settings/language` → `OrganisationSettingsController`; geography `:102` `/geography/cascader-config` → `OrganisationGeographyController`; membership types `:111-113` → `Membership/MembershipTypeController` |
| **Business rules evidenced** | membership **mode** is a first-class organisation setting; language is per-organisation (Nepali/English localisation exists — `docs/TRANSLATION_ARCHITECTURE.md`, `NEPALI_*` guides); geography is hierarchical and cascader-driven (`Contexts/Geography`) |
| **Verification** | switch membership mode → confirm the member application flow changes accordingly; switch language → confirm UI + emails follow; configure a 2-level geography → confirm it appears in member and post assignment |
| **Known findings** | `Evidence not found`: whether an organisation can complete configuration **without** platform assistance (self-service completeness); no constitutional model governs organisation configuration (it is settings-driven, unlike the election lifecycle) |
| **Status** | `IMPLEMENTED — NOT VERIFIED` |

## PBDIGIT-03 — Organisation dashboard

| | |
|---|---|
| **Customer goal** | *"I want one screen that tells me the state of my organisation."* |
| **Business steps** | log in → see members, committees, elections, and what needs my attention |
| **Route → code** | `app/Http/Controllers/Organisation*` + `Organisations/` controller directories; `Membership/MembershipDashboardController`; `app/Services/Dashboard/UserStateBuilder.php`; `Membership/OrganisationParticipantController` · `OrganisationNewsletterController`; participants export `routes/organisations.php:182`, members export `:186` |
| **Verification** | as owner, confirm the dashboard shows: member count · pending approvals · committees · active elections; confirm figures match the database |
| **Known findings** | root `CLAUDE.md` records **Admin Dashboard 40 %** and **Mobile responsiveness 50 %** as in-progress — the only two capability areas the project itself calls incomplete. **This story is therefore the most likely to be genuinely partial.** `Evidence not found`: which dashboard is canonical (multiple dashboard controllers exist) |
| **Status** | `PARTIAL` — dashboard controllers evidenced; completeness self-reported as 40 % |

---

## Epic summary

| ID | Story | Status | Risk |
|---|---|---|---|
| `PBDIGIT-01` | Create an organisation | `IMPLEMENTED — NOT VERIFIED` | medium — tenant isolation is the platform's core promise; verify it explicitly |
| `PBDIGIT-02` | Configure the organisation | `IMPLEMENTED — NOT VERIFIED` | low |
| `PBDIGIT-03` | Organisation dashboard | `PARTIAL` | medium — self-reported 40 % |

**Nothing is authorized by this epic.** Each story becomes its own commission when selected.
