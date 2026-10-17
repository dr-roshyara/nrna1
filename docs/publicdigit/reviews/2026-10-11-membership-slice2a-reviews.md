# Membership Slice 2a (PBDIGIT-271): independent review and architect review, reconstructed summaries

> **⚠️ RECONSTRUCTED SUMMARIES, NOT VERBATIM REVIEWS.**
> - **Why this document:** written 2026-10-16 with the Human PO's authorisation, to meet the review-pack condition "save the reviews as documents". The original reports of the two 2026-10-11 reviews were never saved in the repository.
> - **Sources:** only the recorded summaries listed below. Nothing was added, and no finding was re-assessed.
> - **If the original reports turn up,** they take precedence over this document.
> - **Status:** this document records reviews; it is **not an acceptance**. Slice 2a is `IN REVIEW`. The Principal Reviewer's review and the Human PO / ARB decision are still to come.

**Story:** `PBDIGIT-271` · **Plan:** `docs/plans/20261011-0010-membership-slice2-command-rules-plan.md` (rev. 5) · **Commits reviewed afterwards:** `834a4f65c` (code and tests), `14f89d18e` (docs)

## Sources
| # | Source | Location | Tracked in git? |
|---|---|---|---|
| S1 | plan §15, progress entry "Slice 2a hardening (review findings H1, H1-b, H2)" | plan `:380` | yes |
| S2 | plan §15, "Architect review (sub-agent, read-only)" | plan `:391-394` | yes |
| S3 | plan revision note rev. 5 | plan `:14` | yes |
| S4 | session log, section "Membership Slice 2a (PBDIGIT-271): implementation, independent review, hardening" | `.claude/sessions/2026-10-11.md:95-128` | **no**: that section is an uncommitted change of unknown ownership; quoted here without modifying it |

## 1. Independent review (2026-10-11), summary
- **Verdict (S4 `:107`):** "conditionally acceptable".
- **Findings (S4 `:107-111`; S3):**

| ID | Finding |
|---|---|
| H1 | an infinite decision date was accepted at the domain API |
| H1-b | an infinite effective date had become a refusal instead of an exception |
| H2 | the literal scans missed aliases, fully qualified names, whitespace and case |
| D1–D5 | documentation drift |

- **Human PO / ARB decisions (S4 `:117-122`; S3):**
  - H1 = A, with the effective-date exception restored (H1-b);
  - H2 = nikic/php-parser with name resolution;
  - documentation D1–D5 in scope;
  - implementation authorised;
  - `VoterSlugStep.php` handled by a named exclusion plus backlog item `PBDIGIT-272`.
- **Resolution (S1; S4 `:112-115`):**
  - `Member::requireFiniteDates`;
  - parser-based `MembershipSourceScan::creationViolations`;
  - docs D1–D5.
  - Evidence: `docs/publicdigit/membership/evidence/2026-10-11-slice2a-hardening/`, with RED H1 = 5 failures and RED H2 = 22 failures, then GREEN.
- **Later development (not part of the review):**
  - `PBDIGIT-272` was closed as a duplicate of `PBDIGIT-70`.
  - `f311c97c4` (2026-10-16, another session) deleted `VoterSlugStep.php` and removed the named exclusion and its guard test. Re-verified at `9582de324`: `docs/publicdigit/membership/evidence/2026-10-16-a1-evidence-refresh/delta-9582de324/`.

## 2. Architect review (2026-10-11, read-only sub-agent), summary
**Verdict (S2):** "no blocking defect in Slice 2a".

| Follow-up (S2) | Status recorded |
|---|---|
| a CHECK constraint on `end_kind`, and a decode-failure test | assigned to **Slice 2b** (held) |
| R5 moved onto the parser scan and narrowed | **open** (not done in 2a) |
| the finite-date note added to the Slice 0 contract | **done** 2026-10-11 |

## 3. What these summaries do not contain
- the reviewers' full reasoning, the checks they ran, or any finding not carried into the summaries;
- the exact prompts or scope given to the reviewers;
- any assessment of the committed code. Both reviews predate commit `834a4f65c`.

Fresh committed-code evidence: `docs/publicdigit/membership/evidence/2026-10-16-a1-evidence-refresh/`, at `83a059f98` plus the delta at `9582de324`. All runs exit 0, database-free only.

**Traceability:** PBDIGIT-271 · plan rev. 5 §15 · session log 2026-10-11 · A1 evidence 2026-10-16 (`f54037f96`).
