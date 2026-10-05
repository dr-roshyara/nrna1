# ADR revision 3 (DRAFT) — Voting Authorization Boundary: ballot integrity (VE-01)

**Date:** 2026-10-05 · **Status:** 🟡 **DRAFT — awaiting PO / ARB signature.** The frozen ADR is **not** modified by this draft. On approval, engineering applies §3 to the ADR verbatim, records the decision, and changes nothing else.
**Target:** `docs/publicdigit/adr/ADR_20261005_0950_Voting_Authorization_Boundary.md` (APPROVED, frozen; currently revision 2)
**Story:** `PBDIGIT-237` (VE-01)
**Source of the decisions (consumed, not re-decided):** VE-01 design proposal `docs/publicdigit/reviews/2026-10-05-ve01-ballot-integrity-design-and-adr-amendment-proposal.md` §2–§6 (approved as design direction; amended I1 and I11–I13 **approved in principle** by the PO, 2026-10-05) · gate report `2026-10-05-voting-engine-verification-gate-report.md` §15–§18 · RED contract `tests/Feature/Voting/FinalCastBallotInvariantTest.php` (commit `785b8344d`).
**Prepared by:** `claude-code-session:9e4925bb-4151-4185-9e9c-5831184e54eb` (self-declared, not attestable). Engineering drafts; the PO / ARB decides.

> **This revision authorises no implementation.** VE-01 GREEN remains a separate authorisation (ADR §10: implementation per phase and per production edit group).

---

## 1. Why a revision

The ADR's commit condition (I1) proves *who may cast* and *that participation is consumed once*, but not *what is cast*. The verification gate proved (T1, synthetic data, real request chain) that the final cast commits client-controlled ballot content — cross-election, cross-post, out-of-region, duplicate, over-maximum and malformed selections — which changes the published tally. **VE-01 is a proven ballot-integrity defect.** The approved VE-01 design closes it with a fourth concept, *ballot integrity*, which the ADR must name so that its commit condition is complete.

## 2. Classification of every change

| # | ADR location | Change | Class |
|---|---|---|---|
| R1 | header | Revision 3 note | administrative |
| R2 | §3.0 | fourth concept *ballot integrity*; "beside `forCast()`, not inside" | **DECISION** (approved in principle — proposal §5, §6) |
| R3 | §3.0 flow diagram | ballot validation step before the CAS | clarification (consequence of R2 / I13) |
| R4 | §3.4 | fail-closed rule names the validated ballot | clarification (consequence of amended I1) |
| R5 | §5 I1 | amended commit condition | **DECISION** (approved in principle) |
| R6 | §5 I11 | transport data untrusted | **DECISION** (approved in principle) |
| R7 | §5 I12 | vote persistence accepts only a `ValidatedBallot` | **DECISION** (approved in principle) |
| R8 | §5 I13 | both checks before the CAS, one CastContext | **DECISION** (approved in principle) |
| R9 | §6 | responsibility rows for ballot integrity | clarification (consequence of R2) |
| R10 | §7 | anonymity-boundary row for ballot integrity | **assessment** required by the ADR's own rule ("every mechanism carries an assessment") — needs PO confirmation |
| R11 | §8 | consequence row for VE-01 | clarification |
| R12 | traceability | VE-01 sources | administrative |

**No new architectural decision is introduced.** Two points are made explicit because the ADR's existing rules require it — they are flagged for confirmation at signature (§5): the anonymity assessment (R10) and the demo scope (Q4 rule).

## 3. Exact text to apply on approval

### R1 — header (insert after the Revision 2 note)

> **Revision 3 (2026-10-05, PO / ARB — ballot integrity, VE-01 / PBDIGIT-237):** fourth concept *ballot integrity* beside `forCast()`; I1 amended (the commit condition requires a `ValidatedBallot` and persists exactly its content); new invariants I11 (transport data untrusted), I12 (vote persistence accepts only a `ValidatedBallot`), I13 (authorization and ballot validation both complete before the participation CAS, from one CastContext). Authorization semantics, `CastDecision` and its reason vocabulary, CAS semantics and the anonymity rules are unchanged. No implementation authorised by this revision.

### R2 — §3.0 (heading "Three distinct concepts" → "Four distinct concepts"; add a row and a paragraph)

| Concept | Question | Mechanism | Nature |
|---|---|---|---|
| **Ballot integrity** | *Is the content of this cast act a valid subset of the voter's authoritative ballot?* | pure comparison `SubmittedBallot` × `AuthoritativeBallot` → `ValidatedBallot` \| `BallotRejection` | derived, fresh, at the final cast |

> Ballot integrity is evaluated **beside** `forCast()`, not inside it: `forCast()` answers *may this attempt proceed?*, ballot integrity answers *is the content of the act valid?* `CastDecision`, `CastAttempt` and the reason vocabulary are unchanged. `SubmittedBallot` is the untrusted, normalised proposal from the session / request path; `AuthoritativeBallot` is assembled server-side from the election's ballot authority (today the *current ballot assembly authority*, `BallotAssemblyService`; the constitutional regional authority is decided separately — D6 / PBDIGIT-236); `ValidatedBallot` is a `SubmittedBallot` proven to be a valid subset of it.

### R3 — §3.0 flow diagram (replace the cast line)

```
       ─▶ BEGIN ─▶ forCast (fresh) ─ Allowed? ─▶ ballot validation ─ ValidatedBallot? ─▶ CAS codes.has_voted false→true ─ exactly 1 row? ─▶ INSERT validated anonymous vote ─▶ COMMIT
          (decision)                            (ballot integrity)                     (consumption — outside any decision; 0 rows → reject)
          any denial / rejection / \Throwable ─▶ ROLLBACK (no vote, has_voted stays false)
```
*(The order of `forCast()` and ballot validation shown is the approved implementation direction, not a constitutional rule — see I13.)*

### R4 — §3.4 (replace the first sentence)

> A cast commits **only** on `Allowed` **and** an existing `ValidatedBallot` **and** exactly-one-row compare-and-set **and** successful insert of exactly the validated content. Any other reason, any ballot rejection, any `\Throwable`, any missing input → rollback, no vote, `has_voted` unchanged, lock released, an audited **reason** (not an HTTP 500).

### R5 — §5 I1 (replace)

> **I1** `Allowed` is the only reason that permits entry or a cast attempt; it is never persisted. `CastDecision = Allowed` alone does **not** commit a vote — the commit condition is `Allowed` ∧ a `ValidatedBallot` exists ∧ the CAS changed exactly one row ∧ the insert persists exactly the validated content ∧ the transaction commits (§3.4).

### R6–R8 — §5 (append after I10)

> **I11** Session, request or any other transport data is untrusted input. It may carry ballot state but never establishes ballot validity; validity is established only against the server-side authoritative ballot, at the final cast, whatever the transport.
>
> **I12** Vote persistence (today `save_vote()`) accepts only a `ValidatedBallot`. It never accepts a raw request payload, session ballot, client candidacy ids or client post ids; stored ballot content is derived from validated content only.
>
> **I13** Authorization (`forCast()` → `Allowed`) and ballot validation (→ `ValidatedBallot`) both complete successfully **before** the participation CAS, and both derive from **one CastContext** (Organisation · User · Election · ElectionVoter). Their relative order is not constitutional.

### R9 — §6 (add rows)

| Component | Layer | Responsibility | Owns |
|---|---|---|---|
| `BallotValidator` (`SubmittedBallot`, `AuthoritativeBallot`, `ValidatedBallot`, `BallotRejection`) | Domain (Elections), framework-free | is the submitted content a valid subset of the authoritative ballot? | ballot-validity rules (VE-01 decisions D1–D3, D5, N1–N5) |
| `AuthoritativeBallotQuery` (port) + adapter over the ballot assembly | Application port / infrastructure | provide the authoritative ballot for the CastContext | nothing beyond assembly; no duplicated assembly rules |

### R10 — §7 (add row)

| Mechanism | 1 | 2 | 3 | 4 | 5 | 6 | 7 | Verdict |
|---|---|---|---|---|---|---|---|---|
| Ballot integrity (`BallotValidator` at the final cast) | ✓ no voter field written | ✓ no new timestamp | ✓ **only if** a rejection reason is not logged with a ballot-side identifier (I8) | ✓ | ✓ the validated ballot contains only election-side ids | ✓ reads voter-side context (region, membership) to assemble the ballot; writes nothing voter-derived to the ballot (I10) | ✓ | **passes, with I8** |

*Inherent and unchanged:* regional posts in a ballot reveal the voter's region, as today; the revision adds no new disclosure.

### R11 — §8 (add row)

| Phase | Consequence |
|---|---|
| VE-01 (after Phase 4) | ballot integrity at the final cast per I1, I11–I13; `forCast()`, CAS and demo behaviour unchanged (Q4 rule); separate GREEN authorisation (PBDIGIT-237) |

### R12 — traceability (append)

> · VE-01: PBDIGIT-237, design proposal `2026-10-05-ve01-ballot-integrity-design-and-adr-amendment-proposal.md`, gate report §15–§18, `tests/Feature/Voting/FinalCastBallotInvariantTest.php`, revision-3 decision record.

## 4. Explicitly unchanged

`forEntry()` (not delivered; Phase 5 candidate) · `forCast()` composition and `CastDecision` with its reason vocabulary (E2-D3) · CAS semantics and placement (immediately before vote persistence) · I2–I10, including I6 (cardinality) and I8 (no authorization / security outcome logged with a ballot-side identifier) · Q1–Q7 · Q4 rule (no silent change to demo behaviour) · A5 / A6 (accepted interim risk, Phase 2.0) · the interim anonymity rule · D6 (open; PBDIGIT-236) · D5 and N1–N5 stay in the VE-01 decision record as ballot-validity rules, **not** ADR invariants.

## 5. Points to confirm at signature

| # | Point | Draft position | Why it needs confirmation |
|---|---|---|---|
| S1 | Anonymity assessment R10 | passes, with I8 | a new assessment row (required by the ADR's own rule) |
| S2 | Demo scope | ballot integrity applies to **real** elections; demo behaviour unchanged (existing Q4 rule) | the VE-01 proposal did not state the demo scope explicitly; this follows the Q4 rule, it does not decide Q4 |
| S3 | Heading "Three" → "Four distinct concepts" | apply | wording consequence of R2 |

## 6. Decision (PO / ARB)

| | |
|---|---|
| Decision | ☐ approve as drafted · ☐ approve with changes (listed below) · ☐ reject |
| S1 / S2 / S3 | ☐ / ☐ / ☐ |
| Changes | |
| Decided by / date | |

**On approval (engineering, documentation only):** apply §3 to the ADR verbatim · write the revision-3 decision record under `docs/publicdigit/reviews/` · update the VE-01 proposal status (ADR revision done) and PBDIGIT-237 · no code, no test change. **VE-01 GREEN still needs its own authorisation.**
