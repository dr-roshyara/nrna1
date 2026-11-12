# IR-OTD-01: commission for the independent review of the Organisation tactical design (§49–§51)

| | |
|---|---|
| **Commissioning authority** | The Principal (Product Owner, sole developer, Human ARB Chief, D-94), 2026-11-10, on the Principal DDD Architect's recommendation ("do not accept §49–§51 yet; send the revised design for an independent review first") |
| **Status** | **COMMISSION, READY TO HAND OVER** to a reviewer who is **not** Claude |
| **Why not Claude** | §49–§51 and their self-review (§51.11) were written by a Claude session. **No independent review has taken place** |
| **Precedent** | IR-OPR-01 (`2026-11-10-organisation-people-register-independent-review-commission.md`) |

## 1. Object of the review

`docs/publicdigit/reviews/2026-11-08-strategic-context-map-and-governance-tactical-model.md`, SHA-256 `8b7a6b3ee981699eaf582755a0176b8b138f98516b7f013b1d54ca5a157edab1`:
- **§49** Structure;
- **§50** person belonging, with **§50.15** (D-114 applied);
- **§51** rule-based geographical allocation and unit closure, with **§51.11 … §51.13** (the author's self-review and update).

**§49 … §51 revise earlier sections of the same file** (§9 … §12, §38, §45). **Read those as the base; where they differ, §49 … §51 govern** (each section says so).

## 2. Authority (each decision keeps its own status)

| Status | Decisions | Reviewer may |
|---|---|---|
| **Ratified** | D-93 Reference Geography; D-96 Membership, incl. **CR-01** (C1 `MembershipCoverageResolved`, C2 `CoverageKnownAt`) | Respect; report contradictions |
| **ARB acts** (D-94) | **D-73** (CR-01; one shared τ; complete or refused), D-75 (Identity XD-3), D-77, D-72 | Respect |
| **Accepted baseline** | **D-106** Organisation strategic baseline, rev. 2c (+ note A-1, D-112) | Respect its eight conclusions |
| **Principal rulings** | **D-79, D-80** (placement by acts; an address never places), D-97 … D-101, D-103 … D-105, D-107 … D-110, D-112, D-113, **D-114** (every effective member has a unit; the root by default), **D-116** (an exception wins; placements are floors), **D-117** (rules apply to all qualifying members from their date) | Respect as recorded; report contradictions or unintended consequences |
| **Working direction** | **D-115** (a province committee is the default allocation authority unless the highest committee decides otherwise), D-102, D-111 | May challenge |
| **Working decisions** | D-1 … D-30 (notably D-6, D-13 … D-19, D-22, D-27, D-29, D-30) | Challenge freely |
| **Practical knowledge [PK]** | The V-series answers (decision record §6); e.g. V-5, V-6, V-23, V-24 | Not documentary fact |

**Implement each decision as recorded in the decision record (§11.43 … §11.45 for D-114 … D-117), not as summarised elsewhere.**

## 3. Questions

1. **Exactly one effective belonging:** does every effective member have exactly one effective organisational belonging (in the geographic tree) on every date of Membership coverage (**D-114**)? **Is "awaiting placement" always accompanied by an effective unit**, never a substitute for one?
2. **Precedence:** do these have complete, deterministic precedence in every combination?
   - general allocation (descent, §51.3; most specific area, A-7);
   - ordinary placement (a floor);
   - explicit exception (D-116);
   - a later decision by an equal or higher authority (D-115, D-116);
   - closure and its consequences (C-1 … C-4, R-3).

   **Is there any case where two sources apply and neither clearly wins?**
3. **Overlap:** do nested or overlapping allocations always resolve deterministically, using Geography's containment as known at τ?
4. **Closure and re-admission:** do all closure cases (district, province with children, wing, several levels at once, future-dated allocations) and all re-admission cases (after a real end; after explicit placements or exceptions) preserve the invariant?
5. **Reproducibility:** is every result computed **from facts as known at one shared τ** (D-73, CR-01): Organisation's records, Membership's C2 at τ, Geography at `positionAt(τ)`? **Can any step mix today's facts with yesterday's?**
6. **Geography:** can a Geography change affect belonging **only through an authorised organisational act or rule** (D-80, D-93; §49.6, §51.5)?
7. **Aggregates:** are Tree, OrganisationalUnit, the allocation scheme per scope unit and PersonBelonging, with derived belonging, **sufficient consistency boundaries**? Is any invariant claimed but not enforceable where it is placed?
8. **Ownership:** are Organisation, Governance, Membership, Reference Geography and Election boundaries respected?

## 4. Adversarial scenarios (at least)

- an ordinary placement at Province Y, then a general allocation to District D3;
- an explicit exception to D3, then a later general allocation;
- an exception, then a decision from an equal or higher authority (expressly addressing it, and not);
- an allocation to an area contained within another allocated area;
- closure of a district holding explicit and rule-derived placements;
- closure of a province with several active children;
- re-admission after a Membership end;
- re-admission after an earlier explicit placement or exception;
- a Geography division, and a Geography correction;
- a historical query before and after a decision was recorded;
- a query beyond `closedThrough`.

## 5. Output

Title: **"IR-OTD-01 — Independent Architecture Review: Organisation Tactical Design (§49–§51)"**:
1. The business behaviour in plain language.
2. Independent architectural assessment.
3. Findings by severity (high, medium, low), each with:
   - evidence (section and line);
   - whether it is a fact, an inference or a recommendation;
   - its impact;
   - whether it needs a Principal decision.
4. Exact contradictions, missing rules, unsupported assumptions.
5. Recommended corrections, with proposed wording where useful.
6. **Verdict:** NOT READY · READY AFTER CORRECTIONS · READY FOR PRINCIPAL ACCEPTANCE.
7. Reviewer identity (human, or model and version), prior involvement, how the integrity check was done.

## 6. Rules

- Review and report only; **decide nothing for the Principal.**
- Modify no document.
- No code, schemas or locks; no event sourcing assumed.
- **Mark missing sources UNAVAILABLE**; do not guess their content.
- **Session conclusions (including §51.11) are not independent evidence.**
- Claim no acceptance or ratification. **Stop after the report.**
