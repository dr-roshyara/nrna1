# PBDIGIT-346 — Reference Geography standalone (G-0 … G-8), starting with Identity G-0

| | |
|---|---|
| **Status** | `IN PROGRESS` — **G-0 done 2026-11-13** (migration fix `67425de0e`); **G-1 done 2026-11-13** (evidence `docs/publicdigit/reference-geography/evidence/2026-11-13-pbdigit346-g1/`; guide `developer_guide/reference_geography/01_g1_domain_kernel.md`) (TDD; evidence `docs/publicdigit/reference-geography/evidence/2026-11-13-pbdigit346-g0/`; guide `developer_guide/identity/01_g0_…`), **G-2a done 2026-11-13** (Revision aggregate); **G-2b done 2026-11-13** (before-images, dependencies); **G-2c done 2026-11-13** (validator), **with four open questions Q1–Q4** (G-5; G-4/G-6/D-83; D-82; evidence basis); **G-2 at the Principal's review boundary**; review boundary at the end of G-2 |
| **Design (approved sequence §8)** | `docs/publicdigit/architecture/reviews/2026-11-13-reference-geography-standalone-tactical-completion.md` (D-226), consuming v3.11 (D-93) and D-225 |
| **Decisions** | D-93, D-147, D-224, D-225, D-226 |

## Slices

- **G-0 Identity minimal:** Person per legacy `users` row (lazy), `ContextPersonRef`, `ActingContextRef` (IG-1 … IG-3).
- **G-1 … G-7:** Reference Geography kernel, `Revision`, `StewardRoster`, publication ledger, read models, `Candidate` / `Objection`, CLDR.
- **G-8:** initial evidenced data (Q-G-1).

Each slice: RED → GREEN → guide; review at each boundary.

## Out of scope

- Organisation integration (D-147);
- the full Identity migration;
- addresses (D-83);
- the legacy `App\Contexts\Geography`.

## Review notes

**G-0 verification points (2026-11-13):**
- **"Active account" matches the application's real login rules:**
  - `App\Models\User` implements `MustVerifyEmail`;
  - `LoginController` (line 72) and `LoginResponse` (line 102) refuse unverified e-mails;
  - soft-deleted users are excluded by `SoftDeletes`.

  **Closed.**
- **The race test's non-vacuity (no mutation run) stays open** as a known limit.
