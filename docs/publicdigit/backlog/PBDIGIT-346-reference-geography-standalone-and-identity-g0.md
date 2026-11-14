# PBDIGIT-346 — Reference Geography standalone (G-0 … G-8), starting with Identity G-0

| | |
|---|---|
| **Status** | `IN PROGRESS` — **G-0 done 2026-11-13** (migration fix `67425de0e`); **G-1 done 2026-11-13** (evidence `docs/publicdigit/reference-geography/evidence/2026-11-13-pbdigit346-g1/`; guide `developer_guide/reference_geography/01_g1_domain_kernel.md`) (TDD; evidence `docs/publicdigit/reference-geography/evidence/2026-11-13-pbdigit346-g0/`; guide `developer_guide/identity/01_g0_…`), **G-2a done 2026-11-13** (Revision aggregate); **G-2b done 2026-11-13** (before-images, dependencies); **G-2c done**; **G-2d done 2026-11-13** (G-5, D-82, D-83, G-4, G-6 under D-227/D-228); **G-2 accepted 2026-11-13** (the domain-validation baseline, with F-1, F-2 and D-229; G-4 obligations open); **G-3 accepted 2026-11-13** (governance domain baseline, D-230; Platform Owner authority unresolved); **G-4 accepted 2026-11-13 with conditions** (no production steward appointment or publication until the Platform Owner is established; no production migration until an enforced approval control); **G-5a/b/c implemented 2026-11-13** (published read model, PlaceLookup, publication feed; evidence `…-g5a`, `-g5a-verification`, `-g5b`, `-g5c`); **G-5 ACCEPTED 2026-11-13 with its recorded limitations**; **G-6a candidates done 2026-11-13** (provenance fix `cde4e07f9`); **G-6b objections done 2026-11-13**; **G-6c: design revision 3 ACCEPTED 2026-11-14 (full coverage; re-evaluate confirmers). Implementation: G-6c-1 schema DONE 2026-11-14 (audited against the Principal's integrity pattern, gaps closed); G-6c-2 domain DONE 2026-11-14; G-6c-3 atomic Identity merge/reversal notices DONE 2026-11-14; G-6c-4 confirmation DONE 2026-11-14 (Principal-accepted); G-6c-5 full-coverage correction DONE 2026-11-14; G-6c-6 history, feed fields, notice-write guard and full mutation run DONE 2026-11-14 (**G-6c ACCEPTED WITH CONDITIONS by the Human Arb Chief, 2026-11-14:** C1 guide correction DONE, C2 multi-line guard DONE, **C3 open**: the first feed consumer proves its checkpoint atomicity); **G-7 CLDR display names DONE 2026-11-14** (D-87; region names by D-231 = Option A; guide 32); **G-8 waits for the Principal's decisions** (initial data source and evidence, default-locale starting value, sub-country unit names). Note for the first feed consumer (e.g. Membership): its own tests must prove that the last processed position is stored in the same transaction as the work it caused (G-5c consumer obligation)****; review boundary at the end of G-2 |
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
