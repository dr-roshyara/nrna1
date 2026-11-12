# PublicDigit strategic baseline through D-172: Principal acceptance act

| | |
|---|---|
| **Act** | Acceptance of the PublicDigit strategic baseline (bounded contexts, ownership, authority, access, release-1 scope) |
| **Date** | 2026-11-11 |
| **Authority** | The Principal, as Product Owner and Human ARB Chief (D-94) |
| **Decision-record entry** | **D-173** (§11.75) |
| **Provenance** | direct: "Accept (Recommended)" |
| **Review basis** | **One independent review:** IR-R1-02 by GPT-6 (ChatGPT), independent of the package authorship, prior advice disclosed (`docs/publicdigit/architecture/reviews/2026-11-11-ir-r1-02-independent-review.md`, its GPT-6 text as supplied by the Principal). **Plus self-reviews by Claude and checks by deepseek-chat** (not independent). Post-correction review: `docs/publicdigit/architecture/reviews/2026-11-11-post-correction-strategic-baseline-coherence-review.md` |

## 1. What is accepted

**1. The decision record D-1 … D-172**, as recorded (each decision keeps its own status: ratified, ARB act, accepted, ruling or working direction).

**2. The bounded contexts and their ownership** (post-correction review Part 2), with three kinds of ownership kept apart (domain owner, information owner, storage):
- **central:** Identity, Platform (tenancy), Reference Geography;
- **per organisation:** Organisation, Membership, Governance, Deliberation, Communication, (later) Election, Voting, Finance.

**3. Platform (tenancy) context** as the owner of:
- the organisation registry;
- the database locator, platform status and `schemaState`;
- the record of the service-operation grant (D-166);
- D-170 pending case-grant records.

**This closes HD-8's owner part. The grants inside these records remain the organisation's decisions.**

**4. Cross-cutting rules:**
- **authority only from the organisation's rules or designations (D-1a, D-167, D-172)**;
- product defaults are labelled and overridable (D-167, D-168, D-171);
- **INV-ACCESS-1** (D-166, D-170);
- one τ per cross-context operation, complete or refused (D-73).

**5. Release-1 scope:** D-146, D-147, D-160, D-163; demo mode deferred.

## 2. Re-recorded fingerprints (supersede those in D-169)

The texts below include conformance notes implementing D-167 … D-172.

| Accepted design | Decision | File | SHA-256 |
|---|---|---|---|
| Organisation §49–§51 | D-156 | `docs/publicdigit/reviews/2026-11-08-strategic-context-map-and-governance-tactical-model.md` | `1e9eaf31ee575b51f6ad5d4a9f27baa93946a7faed52f41293d3f81f9a7be79c` |
| Governance thin slice | D-157 | `docs/publicdigit/reviews/2026-11-10-governance-thin-slice-minimum-package.md` | `eea71356da5e8b9585f2f84d9acc4c8a5ec8e17424d843b62e1bb0b4b8fca284` |
| Newsletter and Forum | D-158 | `docs/publicdigit/reviews/2026-11-10-newsletter-and-forum-design-minimum-package.md` | `22929c16ee32b44a86d887bffcc5317e550facaef1629b7a723e52a36ee24d6c` |
| C1/C2 specification | D-162 | `docs/publicdigit/reviews/2026-11-10-membership-cr01-c1-c2-implementation-specification.md` | `5ea99458cb4e34a089f435b5487e0e99fdce2f90387c09872f9a26a69c336b2e` |

**Change control from now on:** a later note implementing a new Principal decision is listed in that decision's entry. **Fingerprints are re-recorded only in the next acceptance act**, not after every note.

## 3. What acceptance does not mean

- **Not accepted by this act:** the tactical designs **Membership renew / suspend / restore (revision 6)** and **multi-database boundary (revision 5)**. They need their own acceptance.
- **Pending in its own session:** Identity's claim design (I-TDD-02); P-I2 and C-5.
- **No implementation is authorised.** Every proof obligation is untested.
- **Legal questions remain** ([LQ]): newsletter basis, compelled disclosure, retention, `PersonRef` classification.
- **Deferred:** dissolution (B-5); challenges without a designated reviewer (now waits, D-167); demo organisation.
- **The context map and status record lag this act**; the decision record governs until they are updated.
