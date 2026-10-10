# Membership (organisational) — developer guide index

The ratified Membership architecture is implemented slice by slice under
`app/Contexts/Membership/Domain/Organisational/`. It is separate from the legacy
`Member` / `Membership*` classes (see EP-01 §3.9, collisions C-1 / C-2).

| # | Guide | Slice / story | Status |
|---|---|---|---|
| 01 | [Slice 0: the semantic kernel](01_slice0_semantic_kernel.md) | Slice 0 · `PBDIGIT-258` | implemented |
| 02 | [Slice 1a: repository port, concurrency and idempotency](02_slice1a_repository_and_concurrency.md) | Slice 1a · `PBDIGIT-259` | implemented |
| 03 | [Slice 1b: PostgreSQL adapter](03_slice1b_postgres_adapter.md) | Slice 1b · `PBDIGIT-260` | implemented (migration not yet rolled out) |
| 04 | [Correction C-1: Annul and Void in the kernel](04_correction_c1_annul_void.md) | C-1 · `PBDIGIT-268` | implemented, awaiting PR / PO acceptance (kernel only) |
| 05 | [C-1 acceptance evidence and the sensitivity kit](05_c1_acceptance_evidence_and_sensitivity_kit.md) | C-1 · `PBDIGIT-268` | test-only correction done; formal acceptance deferred (H-6) |

**Governing documents:**
- architecture (ratified): `docs/publicdigit/reviews/2026-10-08-membership-architecture-ratified.md`;
- implementation design: `…-membership-ep01-implementation-readiness-and-design.md` (Rev 1);
- Slice 0 contract: `…-membership-slice0-semantic-design-contract.md`.
