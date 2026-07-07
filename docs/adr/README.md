# Architecture Decision Records — Index & Classification

**Status:** Living index · **2026-07-08** · introduced per ARB recommendation (distinguish business-language evolution from technical decisions as the ADR set grows).

> **Governance note:** ADR classes describe the **nature** of a decision, not its **importance**. A Published-Language ADR is not "lesser" than a Strategic one — they govern different concerns. Do not infer priority from class.

## Classification scheme
| Class | Meaning | Examples |
|-------|---------|----------|
| **ADR-S** | Strategic Architecture (bounded contexts, subdomains, strategic decisions) | domain ADR-001…008 · Round-38/49 strategic rulings |
| **ADR-UL** | **Ubiquitous Language** evolution (a domain concept/term changes) | ADR-UL-01 (ContestedOutcome) |
| **ADR-PL** | **Published Language** evolution (integration/event contracts change to reflect UL) | ADR-PL-01 (DeterminationIssued v2) |
| **ADR-PC** | **Platform Capability** decisions | ADR-MP-01…05 (Messaging Platform) |
| **ADR-T** | Tactical implementation (aggregates, transactions, versioning) | ADR-T-LOG (ADR-T1…T20) |
| **ADR-IM** | Implementation / technical | (as needed) |

**Ordering rule (ER-06):** an **ADR-UL** precedes the **ADR-PL** it drives; the ADR-PL precedes the event/contract version; the contract precedes implementation.

## Register (new + notable)
| ADR | Class | Title | Status |
|-----|-------|-------|--------|
| ADR-UL-01 | UL | `ContestedOutcome` + `ContestedOutcomeRef` (from `TargetRef`) | Accepted 2026-07-08 |
| ADR-PL-01 | PL | `DeterminationIssued v2` carries `ContestedOutcomeRef` | Accepted 2026-07-08 |
| ADR-MP-01…05 | PC | Messaging Platform (definition · ownership · owner-hosts-guard · constitutional preservation · deferred) | Accepted 2026-07-07 |
| ADR-T-LOG | T | Tactical implementation log (ADR-T1…T20) | Living |
| ADR-001…008, ADR-000x | S | Domain/strategic ADRs (pre-Push-B) | Historical/Accepted |

*(Existing files are not renamed — this index maps them to classes. New ADRs use the `ADR-<CLASS>-NN` prefix.)*
