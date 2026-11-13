# PBDIGIT-346 — Reference Geography standalone (G-0 … G-8), starting with Identity G-0

| | |
|---|---|
| **Status** | `IN PROGRESS` — G-0 started 2026-11-13; strict TDD |
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
