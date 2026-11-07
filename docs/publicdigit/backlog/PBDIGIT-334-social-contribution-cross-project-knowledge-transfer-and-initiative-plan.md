# PBDIGIT-334: Social Contribution, cross-project knowledge transfer and initiative plan (Phase 0)

**Type:** Discovery and planning · **Created:** 2026-11-07 · **Raised by:** the Principal (instruction "Social Contribution: Cross-Project Knowledge Transfer and Initiative Planning", 2026-11-07). Recorded by Claude, Session A.

| | |
|---|---|
| **Status** | **`DONE`: milestone approved by the Principal, 2026-11-07** (plan corrected by C-1 to C-13; commits `0fa62cd8f`, `edefe49e2` confirmed by the Principal). **Phase 1, strategic DDD design and implementation not authorised.** *(Was: `PROPOSED` · Phase 0 authorised.)* |
| **Target project [D]** | `nrna1` (PublicDigit), the only target. `public-digit` and `election` are reference sources only. |
| **Workstream / Epic** | none assigned |

## 1. Goal

Produce an evidence-based initiative story and development plan for Social Contribution in nrna1:
- verify what the two old projects contain;
- classify each idea or pattern as REUSE, ADAPT, REWRITE, REJECT, INVESTIGATE or ALREADY EXISTS;
- assess the current nrna1 architecture;
- recommend a phased sequence.

## 2. Deliverable

`docs/publicdigit/architecture/reviews/2026-11-07-social-contribution-initiative-story-and-development-plan.md`

## 3. Constraints

- No code, schema or migration changes.
- No copying of old code.
- No new bounded contexts.
- No change to ADRs, PBDIGIT-330, ODA-01, the Membership architecture or the Contribution Points module.
- No implementation tickets.
- No commit.

## 4. Done when

The Principal approves the milestone "Social Contribution Initiative Story and Development Plan — Principal Approved". Strategic DDD design (plan Phase 2) starts only after that approval.

## 5. Source documents (public-digit)

15 source documents at `public-digit@a766ef5f2` are registered in the plan §5.5. Each entry gives its content, line ranges, maturity, transfer class, and the nrna1 phase and artefact type that will consume it.

Transfer method: **Reference → Extract → Classify → Evaluate → Rewrite in nrna1 at the authorised phase.**
- No code or schema copying.
- No bulk document copy.
- public-digit documents are not authoritative for nrna1.
- No frozen snapshot without Principal approval.

Every transferred idea carries `Source: public-digit@a766ef5f2:<path>#L<a>-<b> · class <X>`. *(C-12, 2026-11-07)*

## Traceability

Principal Decisions A and B (2026-11-07) · Principal direction "nrna1 is the target" (2026-11-07) · `PBDIGIT-331` · `PBDIGIT-332` · `PBDIGIT-333` · cross-project discovery 2026-11-07 · Phase 2 preparation package 2026-11-07.
