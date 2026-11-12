# IR-P-02: independent review of the Platform (tenancy) tactical design, revision 4

| | |
|---|---|
| **Commissioning authority** | The Principal (D-94), 2026-11-11 |
| **Reviewer** | **GPT-6 in ChatGPT** (or another reviewer that is not Claude, not DeepSeek, not in Claude Code) |
| **Subject** | `docs/publicdigit/architecture/reviews/2026-11-11-platform-tenancy-tactical-design.md`, SHA-256 `468ebdf48621a0882028ba16365e567298d093c989aeb267549718255cd557fc`. **Revision 4 governs**, then revision 3, revision 2, revision 1, where they differ. **The design was not changed while this package was prepared** |
| **Provenance** | GPT-6's IR-P-01 (revision 2): **extract only** (file 03; full report not saved). **DeepSeek's review of revision 3 (file 04): excluded reviewer, not independent, non-authoritative input.** It was first saved under the IR-P-01 filename and has been renamed; revision 4's text still cites the old filename. **No independent reviewer has examined revisions 3 or 4** |
| **Not in scope** | Implementation; reopening accepted decisions; Membership, Newsletter, Forum, Geography |

## 1. Files

| # | File | Role |
|---|---|---|
| 01 | this commission | |
| 02 | **subject**, revision 4 | |
| 03 | GPT-6 IR-P-01 findings extract (F-01 … F-04) | check whether they are genuinely resolved |
| 04 | deepseek-chat review of revision 3 (**not independent**) | non-authoritative input |
| 05 | decision record (D-113, D-164, D-166, D-167, D-170, D-173 … D-180) | SHA-256 `817c830b…` |
| 06 | multi-database design, accepted (D-178), with conformance notes (ISO-1 … ISO-11) | SHA-256 `430ed58f…` |
| 07 | strategic baseline acceptance act (D-173) | |
| 08 | H-1 analysis (INV-ACCESS-1, D-166 controls) | |
| 09 | I-TDD-01 Identity design (INV-ID-3, INV-ID-6; superseded where D-113 applies) | |
| 10 | XD-3 ARB act (RAT) | |
| 11 | Governance thin slice (accepted, D-157) | |

## 2. Binding (do not reopen)

- **Decisions:** D-113, D-164, D-166, D-167, D-170, D-173 … D-180;
- **invariants:** INV-ACCESS-1; INV-ID-3 as narrowed by D-179; ISO-1 … ISO-11;
- **the Governance authority model.**

**If a contradiction cannot be resolved within them, name it as a Principal question.**

## 3. Questions

**A. Contact-list capture (D-176, D-180): highest priority.** Test exactly:
1. contacts X, Y, Z;
2. X is malicious;
3. X uses the emergency route to remove Y and Z (immediate under D-180);
4. **X, still a contact, immediately gives a grant.**

Does the 24-hour delay on *newly added* contacts prevent this? **Notification ≠ prevention.** Also test the **verified counterparty** making direct changes (D-175 must not become organisational power). **If the decisions do not prevent it, name the exact Principal risk choice.**

**B. Evidence during an outage.**
- What does the platform hold at registration: references, digests, sender?
- What does a digest prove, and what does it not?
- Can a change take effect before its decision is validated?
- What happens if documents are missing, altered or never submitted after recovery?
- **No central decision texts (D-164, D-170).**

**C. Ordering.** Revision 4 orders direct registrations by **arrival**. Can a late-arriving older decision override a newer one? Distinguish:
- decision order;
- receipt time;
- application time.

Is there **one precedence rule** across the two sources (organisation database, direct)? Is it consistent with D-176 and the Governance decision log?

**D. D-179 notices.** Check the lifecycle: requested → recorded → dispatched → delivered / failed → retried / permanently failed. Are the removed contacts notified? **"Reported immediately" ≠ "delivered".** Is the login address used **only** for these notices (never to find, create or bind)?

**E. `PlatformOperator` and ISO-11.**
- operator identity separate;
- **no access without an active grant naming the operator**;
- case, data area and window enforced throughout;
- expiry, withdrawal and use-up end access;
- backup inspection on an isolated, expiring copy;
- no general support route;
- INV-ACCESS-1 intact.

**F. Aggregates and concurrency** (`ServedOrganisation`, `PlatformContacts`, `PendingCaseGrant`). **Name the linearisation point** for:
- contact removal versus grant creation;
- withdrawal versus use;
- duplicates;
- delayed and reordered updates;
- recovery copy.

**A lock or version field is not proof by itself.**

**G. Lifecycle and bootstrap:**
- `pending` versus D-175's "stays `provisioning` until verified" (exact semantics);
- grant before provisioning;
- `migrating` fail-closed;
- **the registrar's first claim as a narrow, one-time exception** (D-113, D-178);
- at least one active contact before `operational`.

## 4. Method and report

**Labels:** [AD] · [BF] · [AC] · [TD] · [TU] · [OQ] · [LQ] · UNKNOWN.

**For each finding give:**
- id;
- severity;
- exact section;
- business consequence;
- DDD consequence;
- whether it contradicts an accepted decision;
- whether it is correctable without the Principal;
- the exact Principal decision, if any.

**The report** ("IR-P-02 — Independent Review: Platform Revision 4") contains:
1. a business summary;
2. the review;
3. findings;
4. Principal decisions;
5. proof obligations;
6. a verdict: **READY FOR PRINCIPAL ACCEPTANCE · READY WITH EXPLICIT PRINCIPAL DECISION · NOT READY**;
7. corrections required.

**Rules:**
- decide nothing for the Principal;
- modify nothing;
- claim no acceptance;
- **stop after the report.**
