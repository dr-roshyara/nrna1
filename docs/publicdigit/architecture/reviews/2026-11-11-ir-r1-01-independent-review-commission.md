# IR-R1-01: independent review of two release-1 tactical designs

| | |
|---|---|
| **Commissioning authority** | The Principal (D-94), 2026-11-11 |
| **Status** | **COMMISSION, READY TO HAND OVER** |
| **Reviewer** | **Independent:** not Claude, **not DeepSeek** (it reviewed earlier designs in this chain), **not run inside Claude Code**. A human architect, or another model family (GPT, Gemini, Mistral) in its own interface |
| **Why** | Both designs were written **and self-reviewed by Claude**. Each states that an independent check is needed before the Principal's acceptance |
| **Not in scope** | Redesign; other contexts; implementation. **A design review proves neither implementation correctness nor test execution** |

## 1. Subjects

| # | File | SHA-256 | What to check |
|---|---|---|---|
| **S1** (file 02) | `docs/publicdigit/reviews/2026-11-11-membership-kernel-renew-suspend-restore-design.md` (**revision 3**: §0–§15, **§16 corrections govern**) | `eb13027e57b454dd22ce5fd95d3fb20e59c6e9f614bc19384b14539e98e7d5e0` | Renew, Suspend, Restore in the ratified Membership ledger |
| **S2** (file 03) | `docs/publicdigit/architecture/reviews/2026-11-11-multi-database-boundary-tactical-design.md` (**§13 revision 2 governs** where it differs from §0–§12; closing D-165 / D-166 notes) | `30b4d4e175be6954dff582d21ee22ecde5c8f4842685eb2c23b76c83a6329cff` | central database ↔ organisation databases (D-164): claims, references, recovery, isolation |

## 2. Sources (read them; summaries in the subjects are not evidence)

| # | File | SHA-256 | Use |
|---|---|---|---|
| 04 | decision record `…/2026-11-08-organisation-strategic-discovery-record-structure-mandates-conventions.md` | `df3e4342…f73` | decisions **D-75, D-101, D-104, D-110, D-111, D-113, D-149, D-154, D-160 … D-166** (§11.42, §11.55 …) |
| 05 | ratified Membership architecture `…/2026-10-08-membership-architecture-ratified.md` | `2ada0ca5…5ab` | **RAT:** glossary §1, facts §2, resolution §3, predicates §4, invariants §5 |
| 06 | Membership final architecture `…/2026-10-08-membership-architecture-final.md` | `3d0dc32c…34fe` | formal model §3, operations §7, suspension §9, **requirements table §18 (operation guards)** |
| 07 | CR-01 `…/2026-11-09-membership-change-request-cr-01-…md` | `04c3aeb1…4b6ba` | RAT contract C0–C4 |
| 08 | CR-02 `…/2026-11-10-membership-change-request-cr-02-standing-in-c1-c2.md` | `a3c2326a…b31e8e6` | RAT addition (D-161) |
| 09 | C1/C2 specification `…/2026-11-10-membership-cr01-c1-c2-implementation-specification.md` | `f2ce8789…8c8c` | accepted (D-162): ledger clock |
| 10 | AuthorityCheck review `…/2026-11-11-authoritycheck-organisation-governance-contract-review.md` | `ff2eb08c…28014` | the six ratified evidence fields + basis |
| 11 | Governance thin slice `…/2026-11-10-governance-thin-slice-minimum-package.md` | `c4c35791…bd71` | accepted (D-157) + correction notes 1–3 |
| 12 | I-TDD-01 `…/2026-11-09-i-tdd-01-identity-participant-access-tactical-design.md` | `b3d78277…a6` | Identity `Claim` (P; **predates D-113**) |
| 13 | XD-3 ARB act `…/2026-11-09-identity-xd3-arb-act.md` | `a67aa2ce…e9` | RAT identity rules |
| 14 | integrated coherence review `…/2026-11-11-integrated-baseline-coherence-review-and-completion.md` | `c001cdc8…691` | context (Addendum M) |

*Full hashes in `MANIFEST.sha256`.*

## 3. Questions

### S1: Membership Renew / Suspend / Restore (revision 3)

1. **Conformance:** does every row of the decision table (§5, as corrected in §16) follow from the **ratified** definitions (file 05 §2–§5) and **guards** (file 06 §18)? In particular:
   - late renewal (gap kept, INV-12);
   - back-dated renewal;
   - renewal after End refused;
   - **Suspend and Restore only while in force** (§16 C-2);
   - fixed-length suspension ending by its interval **without a Restore**;
   - annul and correct effects.
2. **Language:** is `IsMember` unchanged? Is "lapsed membership" used consistently (covered, not a member)? Is `InForceUnrestricted` (D-161) only a consumer-derived name, with no new fact or contract field (CR-02 unchanged)?
3. **Terms at admission:** is "∞ = open-ended; absent = no term; release-1 admissions must state a term" consistent with D-163 (no platform default)?
4. **Grouped renewals (§16 C-3):**
   - does "one transaction under the organisation's ledger-clock lock, all or none, fail-closed size bound" actually guarantee all-or-none?
   - does it respect each aggregate's acceptance rules and the ledger-clock ordering (file 09)?
   - **Name any race or failure the argument misses.**
5. **Authority:** do renew / suspend / restore use the ratified six evidence fields + basis correctly (files 10, 11)?

### S2: multi-database boundary (§13 governs)

6. **Decisions:** does §13.0's reading hold?
   - **D-113 decides P-I1 / K4** (no Person at import; the organisation's `PersonRef` is issued at import; the claim is tied to the `PersonRef`);
   - I-TDD-01's "open K4 seam" is superseded.
7. **Claim authority (§13.2):** can Identity accept `IssueClaim` / `RevokeClaim` **only** when legitimately authorised by the organisation, through:
   - the internal port;
   - the reference-belongs-to-organisation check;
   - fresh `Authorised` evidence?

   **Is anything still forgeable or replayable?**
8. **Recovery (§13.3):** for each crossing (reference issue, claim issue with intent-first, redemption with atomic outbox, inbox deduplication, reconciliation):
   - **is every intermediate state harmless and recoverable**?
   - **is "forward recovery, not compensation" accurate**?
   - check especially redemption committed while the organisation database is down, and the lost reply at claim issue.
9. **`ClaimRedeemed` (§13.1):** is the corrected meaning (routing only; no rights) consistent with D-113, D-164 and the accepted access model?
10. **Isolation (§13.5):** are ISO-1 … ISO-9 **sufficient** to guarantee that an account bound to organisations A and B can never reach B's data from A, and that **the binding is never read as authority**? Test them against:
    - concurrent requests;
    - connection reuse in long-running workers;
    - jobs, inbox and relay;
    - public endpoints;
    - privileged operations (**D-166**).

    **Name any missing invariant.**
11. **Availability (§13.6):** is fail-closed for release 1 stated precisely (which operations need the centre and why)? Is the registry's durable status kept apart from health?

### Both

12. **Any contradiction** with a ratified or accepted decision, **any invented business rule**, **any claim stronger than its evidence**.

## 4. Report

Title: **"IR-R1-01 — Independent Review of Release-1 Tactical Designs"**.

1. **Per question:** CONFIRMED · PARTIAL · MISSING · CONTRADICTORY · NOT ASSESSABLE, with **file, section and line** for every confirmation.
2. **Findings** (high, medium, low), each with:
   - evidence;
   - fact or inference;
   - impact;
   - **whether a Principal decision is needed**.
3. **Verdict per subject:**
   - READY FOR PRINCIPAL ACCEPTANCE;
   - READY AFTER CORRECTIONS (list them);
   - NOT READY.
4. **Reviewer identity** (human, or model, version and interface), prior involvement with PublicDigit, integrity method (hashes computed or not).

**Rules:**
- Decide nothing for the Principal.
- Modify nothing.
- Claim no acceptance.
- Mark anything not in the package UNAVAILABLE.
- **Stop after the report.**
