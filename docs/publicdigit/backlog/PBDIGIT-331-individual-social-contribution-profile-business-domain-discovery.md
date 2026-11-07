# PBDIGIT-331: individual social contribution history and its presentation (business-domain discovery)

**Type:** Strategic discovery / business-domain discovery · **Created:** 2026-11-07 · **Raised by:** the Principal (Principal DDD Architect review and Phase 2 instruction, 2026-11-07). Recorded by Claude, Session A.

| | |
|---|---|
| **Suggested title** | Discover what an evidence-backed individual social contribution history means, starting from one real case |
| **Status** | `PROPOSED` · discovery preparation authorised (Principal Decision A, 2026-11-07) · **implementation not authorised** |
| **Priority** | to be determined (PO). Competes with the frozen delivery focus WP-7C → WP-8 → EPIC-005 |
| **Target project [D]** | `nrna1` (Principal direction, 2026-11-07); `public-digit` and `election` are reference sources only. Planning: `PBDIGIT-334` |
| **Workstream** | none assigned. **Product-scope fit within nrna1 unresolved** (person-scoped vs organisation-scoped; see §7) |
| **Epic** | none assigned |

## 1. Background

[F-R, reported by the Principal] The Principal met Komal Oli, a Nepali political leader. As reported:
- she has received many appreciation certificates;
- she has made numerous social contributions;
- her social contributions are not systematically measured or consolidated;
- she wants to make them visible to members of her political party and potentially the wider public;
- she wants to understand and demonstrate the extent of her social contribution.

No further statements are attributed to her.

## 2. Problem statement

There is no way to document, substantiate and present an individual's social contributions so that a reader can see:
- what was done;
- what evidence supports it;
- what recognition followed;
- what difference it made.

Doing this without reducing the person to an unexplained number is part of the problem.

## 3. Capability hypothesis [H]

> "Enable an individual to document, substantiate and present their social contributions through an evidence-backed contribution history, including activities, recognition and demonstrable outcomes."

This is a hypothesis until validated with the business domain expert. The initial discovery concerns **one individual**. Generalisation to party members is `PBDIGIT-333`.

A profile is a **candidate solution**, not a validated requirement. The presentation form (profile, portfolio, history, report, dossier or a combination) is to be discovered. *(C-6, 2026-11-07. The filename keeps "profile" so that the ID and links are preserved.)*

## 4. Discovery scope

- The ubiquitous language: activity, contribution claim, verification, recognition, outcome, impact, and the financial, organisational, political and deliberative senses of "contribution".
- The conceptual relationships between them. These are not an aggregate design.
- What measurement means: effort, distinct from outcome and impact.
- Evidence, verification authority and visibility.
- An interview guide for the initial case, and a workshop sequence.

**Working package:** `docs/publicdigit/architecture/reviews/2026-11-07-social-contribution-domain-discovery-phase-2.md`.
**Evidence base:** `docs/publicdigit/architecture/reviews/2026-11-07-social-contribution-cross-project-discovery.md`.
**Source register (public-digit):** `docs/publicdigit/architecture/reviews/2026-11-07-social-contribution-initiative-story-and-development-plan.md` §5.5.

## 5. Constraints

- No universal score, ranking, leaderboard or weighting formula.
- The nrna1 Contribution Points module and `GaneshStandardFormula` are **not** a foundation. Their status is a historical experiment pending PO confirmation (`PBDIGIT-332`).
- Keep this separate from office-holder accountability (`PBDIGIT-330`). Shared concepts may be identified; ownership and purpose are not merged.
- Do not assign ownership from code structure.
- Do not change the ratified Membership architecture, ODA-01, PBDIGIT-330 or Adjudication.
- Personal evidence (certificates, photographs) is not stored in the repository.

## 6. Definition of done for the discovery

1. Interview(s) with the initial case held, and her answers recorded with her consent.
2. Glossary v2 validated, or amended, by the business domain expert.
3. Conceptual relationship model v2, with ambiguities closed or recorded as open questions.
4. A decision package for the PO covering: product-scope fit, the identity anchor (person or member), visibility and consent, verification authority, and the measurement stance, taking into account the generalisation findings of `PBDIGIT-333` (workshop W4b).
5. Only then: strategic design (context ownership, context map) as a separate, authorised step.

## 7. Dependencies and open decisions

- **Product-scope fit [OQ, PO].** The backlog defines the product as an Organisation Governance Platform. An individual, person-scoped and possibly public profile may extend that scope.
- **Consent [OQ].** Consent of the initial case to be the discovery subject and to be named in project documents.
- **`PBDIGIT-330`.** Shares activity and evidence concepts; ARB-8 is open.
- **`PBDIGIT-332`.** The PO decision on the existing module.

## Traceability

Principal Decisions A and B (2026-11-07) · cross-project discovery 2026-11-07 · `PBDIGIT-330` · `PBDIGIT-332` · `PBDIGIT-333`.
