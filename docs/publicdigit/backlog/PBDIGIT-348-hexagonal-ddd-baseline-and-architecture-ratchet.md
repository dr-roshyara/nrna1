# PBDIGIT-348: PublicDigit Hexagonal DDD baseline and architecture ratchet

**Status:** **DONE 2026-11-13.** The baseline was accepted by the Principal (Shared is a technical library; MemberId is published language; A-4 allows query ports). Revision 2.1 safeguards and the occurrence ceiling are delivered (`edd666525`, `674168f40`). **Open, outside this story (repository administration, the Principal's action):** push, confirm the `architecture-ratchet.yml` job passes, and mark it a required check on `main`. Not verifiable from the repository · **Context:** cross-cutting (all contexts) · **Kind:** architecture supplement and architecture tests (no production code)

## Why

The Principal DDD review of 2026-11-13 asked for one enforceable architecture across PublicDigit before G-5, Membership routing, and the Forum and Newsletter designs. The problem found isn't missing rules: the rules already exist (CLAUDE.md Rules 1 to 10, strategic baseline rev3). It is inconsistent enforcement, and legacy code that predates those rules.

## Delivered

- `docs/publicdigit/architecture/publicdigit-hexagonal-ddd-baseline.md`, revision 2 (PROPOSED). Revision 1 was committed as `f62d9faa7` under PBDIGIT-346, together with the G-4 ruling.
- `tests/Architecture/ArchitectureRatchetTest.php` with `Support/ArchitectureRatchet.php`, the fixtures `tests/Fixtures/ArchitectureRatchet/`, and the baseline `tests/Architecture/baselines/architecture-ratchet.json` (198 individually listed violations).
- `docs/publicdigit/architecture/publicdigit-architecture-deviation-inventory.md` (generated).
- Evidence: `docs/publicdigit/architecture/evidence/2026-11-13-architecture-ratchet/`.

## Acceptance

- Principal review of revision 2: `Shared` as a technical library; `Member\MemberId` as published language; A-4 allows query ports.
- After acceptance: the published-language allow-list in A-3, and A-6 ("no business concepts in Shared").

## Follow-ups, not part of this item

- R-1 → PBDIGIT-345.
- R-2 `MembershipStanding` contract → Membership lifecycle work.
- R-2 relocation, R-4 → Membership change request.
- R-6 → G-5 and D-147.
