<!-- Recorder note (Claude, 2026-11-11):
- What follows: GPT-6's IR-P-02-R7 formal report, as pasted by the Principal; extracted verbatim from the session transcript; body unaltered.
- GPT-6 stated its own saved file has SHA-256 d64b6bb945df05a2dd5ef21502dc8a0e99b02ce1c9448a4309741392b68a9eb2. That file was not received; this pasted text is a different byte sequence.
- GPT-6's chat message is appended for provenance. -->

# IR-P-02-R7 --- Final Bounded Review: Platform Revision 11

  ----------------------------------- --------------------------------------------------------------------
  **Reviewer**                        GPT-6 in ChatGPT --- Principal DDD Architect review

  **Commission**                      IR-P-02-R7, Principal's governing commission §§4--8

  **Subject**                         `2026-11-11-platform-tenancy-tactical-design.md`, revision 11

  **Subject SHA-256**                 `e3772d2e8e266d909a1328cb2205b1d9271e47c0890caf5be8984d5724d4e005`

  **Proof register**                  `2026-11-11-platform-proof-obligation-register.md`

  **Register SHA-256**                `a3fe00f28f8fe70c388891cbf36042f428bc69a70d1efdf9d24f3cc8e129fb18`

  **Review status**                   Final bounded review; no implementation or acceptance authorised by
                                      this report
  ----------------------------------- --------------------------------------------------------------------

## 1. Verdict

**READY FOR PRINCIPAL CONSIDERATION**

No material design defect remains within the commissioned scope.
Revision 11 resolves the five R6 findings at the specification level and
incorporates D-192 consistently. The outstanding matters are explicit
proof obligations, Governance and Identity dependencies, and the
Principal's reserved acceptance decision.

This verdict does **not** mean that Platform is accepted, that any proof
has passed, or that implementation is authorised.

## 2. Source integrity and verification

I recalculated the SHA-256 fingerprints from the files inside the
uploaded ZIP.

-   All **14 of 14** entries in `MANIFEST.sha256` match their listed
    fingerprints.
-   All **16 of 16** entries in the outer `SHA256SUMS` match.
-   The revision 11 subject independently hashes to
    `e3772d2e8e266d909a1328cb2205b1d9271e47c0890caf5be8984d5724d4e005`.
-   The proof register independently hashes to
    `a3fe00f28f8fe70c388891cbf36042f428bc69a70d1efdf9d24f3cc8e129fb18`.
-   The supplied revision 10 snapshot and R6 report are present and
    match the manifest.

The package is internally consistent. The review uses revision 11 as the
subject and the supplied decision record as the authority for D-164
through D-192. The author's change map and self-assessment were treated
as navigation, not evidence.

No source file was modified. No code, test, database, or infrastructure
was executed. This review makes no claim of implementation proof.

## 3. Finding-by-finding assessment

### F-01 --- Governance AuthorityCheck actor and capacity

**Classification: \[E\] Resolved at design level; \[C\] X-1 remains an
external acceptance dependency; \[C\] X-3 remains separate.**

R11-1 corrects the actor/capacity mapping:

-   `designate-platform-contacts`: the deciding organ is the actor; its
    sitting is the capacity. The designated `PersonRef` is the subject
    of the designation, never the actor.
-   `give-case-grant`: the actor is the actual maker of the
    grant---either the giving `PersonRef` or the deciding organ.
    Capacity is the relevant tenure or sitting, matching the act.

This matches the two evidence shapes described in the AuthorityCheck
contract review: committee decisions are pushed with their decision;
office-holder acts are checked at the act. The six-field evidence shape
plus basis is reused; the Platform does not introduce a second authority
schema or decide competence itself.

The correction also properly withdraws the earlier overstatement that
each Resolution part already records immutable `ruleRef` and
`policyVersion`. D-190 does not settle that persistence rule. R11-1
correctly leaves it with Governance under X-3 and makes the relevant
predicate fail closed until it can be evaluated.

**Remaining condition:** Governance must formally accept the R11-1
addition note under X-1a. Its accepted contract must preserve the
actor/capacity mapping above within the existing AuthorityCheck evidence
shape. This is an external acceptance dependency, not a remaining
Platform design defect.

### F-04 --- Deadline and failover

**Classification: \[E\] Resolved at design level; \[B\] implementation
proof remains.**

R11-2 supplies the missing conservative failover formula:

`remaining = (D − t_now) − (monotonic_at_dispatch − m_before)`

The monotonic reading precedes the central-clock request. Consequently,
request latency, response latency, scheduling delay and time up to
dispatch are deducted. The initial admission path uses the equivalent
bracket with `m0` and `t_admit`.

Dispatch is refused when the central time cannot be obtained or the
conservative remaining duration is non-positive. This closes the R6
defect: a restarted gateway cannot reset the deadline by using an
unadjusted `D − t_now`.

E-4 and F-2 appropriately retain the real execution test, including
injected delays and the effective-end-at-or-before-D assertion. The
formula is a design correction; actual enforcement remains \[B\].

### F-05 --- Backend termination and D-192

**Classification: \[E\] Resolved at design level; \[B\] backend-specific
proof remains.**

R11-3 applies D-192 as a last resort, not as normal operation:

1.  Cancel the tagged session at the deadline.
2.  Allow the explicit 10-second confirmation window on the gateway
    monotonic clock.
3.  If not confirmed, attempt termination and containment, including
    revoking the grant role's login/connect capability, terminating its
    other sessions and closing the gateway network path. A further
    confirmation window is specified.
4.  If termination remains unconfirmed, force-restart the database
    server hosting the Organisation database.
5.  If restart cannot be confirmed, make that Organisation database
    unroutable until an operator confirms server state.

The design correctly distinguishes containment from termination.
`contained` means no new admission; it is not evidence that running work
stopped. Unconfirmed operations remain `termination-unconfirmed`, with
"access may have occurred". A confirmed stop does not erase the fact
that access may have occurred before the deadline.

The incident consequences are also represented: the affected
Organisation is informed, and every Organisation sharing the server
receives an incident report when the forced restart is invoked.

The specified restart confirmation uses server start-time evidence after
the restart order together with absence of the tagged session. Whether
the selected database product exposes authoritative session state and
reliable, causally relevant restart evidence must be demonstrated
against the actual backend. That is \[B\], not a paper design defect.
F-3 and F-6 cover the restart, containment, unroutable fallback,
shared-server notifications and confirmation-window behaviour.

D-192 is not reopened or changed. The accepted hosting layout remains
unchanged.

### F-06 --- D-188 incident report

**Classification: \[E\] Resolved at design level; \[B\] report
completeness and delivery proof remains.**

R11-4 adds the missing per-operation fields:

-   `grantId`;
-   `failedDesignationRef`, including the Resolution, part, designated
    `PersonRef` and failed validation outcome;
-   `suspensionRef`, identifying the grant suspension event;
-   `outcome`, distinct from lifecycle state.

The existing R10-6 lifecycle state, access-completion status, access
uncertainty, termination status and audit references remain. The report
therefore connects the failed designation, suspended grant and each
affected operation without conflating the operation's outcome with its
lifecycle.

The `registered` case is explicitly defined: `admittedAt` and deadline
are null, outcome is `never-executed`, and "access may have occurred" is
false.

E-6 and F-4 appropriately test the fields, enumeration/count,
interrupted reconciliation, higher-version re-emission and D-179's
content-free security notice. These remain \[B\].

### F-07 --- Admission versus abandonment

**Classification: \[E\] Resolved at design level; \[B\] concurrency and
recovery proof remains.**

R11-5 defines a coherent Organisation-side arbitration mechanism without
assuming a cross-database transaction:

-   Admission and abandonment compete for the same unique `operation_id`
    in the Organisation admission table.
-   Admission inserts the admitted row; reconciliation, only after the
    two-minute window, inserts an abandoned tombstone.
-   The first transaction to commit wins; the other loses on the unique
    key.
-   The central registry adopts the Organisation-side result rather than
    independently deciding the outcome.

The window is defined from `registeredAt` using the central clock. The
gateway refuses to start an admission attempt after the window, checking
with the R11-2 bracket. A late admission commit before the tombstone is
explicitly accepted; one after the tombstone is refused. This is
coherent because the Organisation-side unique identity arbitrates both
results and the central state mirrors the result.

The design does not claim that a unique key alone proves lifecycle
correctness. E-2 and F-5 retain the necessary transaction-order,
late-commit, tombstone, retry and central-adoption tests. F-6 covers the
two-minute boundary and clock semantics. These are \[B\].

## 4. D-192 and strategic conformance: D-164 through D-192

**Classification: \[E\] No material non-conformance found within the
commissioned scope.**

Revision 11 remains consistent with the accepted strategic and tactical
boundaries:

-   **D-164 / D-173:** one central Platform database and a separate
    database per Organisation; Platform owns the registry, locator,
    status, service-operation grant record and pending case-grant
    metadata. Organisation business records remain Organisation-owned.
-   **D-166 / D-170:** technical operations require the joining
    service-operation grant and do not authorise content reading.
    Content access remains case-specific. The outage route holds only
    permitted pending-grant metadata centrally, and access is refused
    without a grant.
-   **D-167 / D-181:** Platform does not infer authority. Rules-silent
    or undetermined outcomes do not create a competent actor.
-   **D-174 through D-188:** the contact, emergency-grant, recovery,
    withdrawal, approval, suspension and handover rules remain
    Organisation/Governance-owned. The Platform implements the accepted
    mechanics and fails closed where authority cannot be established.
-   **D-175 / D-185:** counterparty verification precedes technical
    provisioning; `pending` precedes `provisioning`.
-   **D-179 / D-180:** security notices remain a narrow exception, and
    direct-route contact restrictions are preserved.
-   **D-182 / D-183 / D-186:** two eligible, Organisation-recorded
    contacts must both be current at the second approval. The design
    does not promote an unconfirmed direct-route contact into an
    approver.
-   **D-187 / D-188:** protected work pauses during recovery handover; a
    grant approved under a designation that validates as `NotAuthorised`
    or `Undetermined` is suspended and reported.
-   **D-189:** one protected operation is bounded by the earlier of
    grant expiry and the applicable maximum duration; no result is
    delivered after the deadline.
-   **D-190:** the two AuthorityCheck act kinds are consumed by
    Platform; Platform does not create authority.
-   **D-191:** central contact metadata is limited to the permitted
    references, digests, sender, validation outcome and evidence
    reference. Decision texts, minutes and rules are not centralised.
-   **D-192:** forced database-server restart is the last resort when
    cancellation and termination fail; shared-server consequences and
    incident reporting are explicit.

D-165 is superseded as specified by D-166. No responsibility is
transferred from Identity, Governance, Membership, Finance or an
Organisation into Platform. The multi-database boundary accepted under
D-178 is respected.

The initial discovery questions Q-P1 and Q-P2 are answered by the later
accepted decisions D-174 and D-175 and their subsequent refinements.
They do not create new Principal decisions in this review.

## 5. External dependencies

### X-1 --- Governance AuthorityCheck addition

**Classification: \[C\] External dependency.**

D-190 authorises the two act kinds. The R11-1 draft note is not yet
formally accepted in Governance. X-1a remains a condition to be
completed with Platform acceptance. The note must preserve the R11-1
actor/capacity mapping and reuse the existing AuthorityCheck contract.

X-1b does not settle per-Resolution-part `ruleRef` / `policyVersion`
persistence. R11-1 correctly assigns that question to X-3.

### X-2 --- Identity PersonRef-to-Account resolution

**Classification: \[C\] External dependency.**

Platform must consume an accepted Identity operation to resolve an
Organisation `PersonRef` to its bound Account. It must not implement a
parallel identity lookup, match by email, or infer an Account.

The supplied I-TDD-01 is proposed; I-TDD-02 is only a scope document and
has not been written. X-2 therefore remains open. The design correctly
fails closed until the accepted Identity operation exists.

### X-3 --- Governance DecisionLog and evidence persistence

**Classification: \[C\] External dependency.**

GD-11/GD-12 remain proposed, and Governance has not yet established the
accepted contract for per-Resolution-part evidence persistence. D-190
does not decide that persistence question.

Platform correctly withdraws the earlier assertion and does not invent a
Governance rule. The affected AuthorityCheck predicate remains
unevaluable and promotion / grant checks fail closed until Governance
resolves the contract.

## 6. Proof register verification

**Classification: \[B\] Proof obligations; none executed.**

The register is stamped for the exact revision 11 subject fingerprint. I
checked the complete register:

  Section                            Obligations Status
  -------------------------------- ------------- ----------------------
  A --- Concurrency matrix                    14 All NOT EXECUTED
  B --- Other design obligations              10 All NOT EXECUTED
  C --- Revision 8 additions                   9 All NOT EXECUTED
  D --- Revision 9 additions                   6 All NOT EXECUTED
  E --- Revision 10 additions                  8 All NOT EXECUTED
  F --- Revision 11 additions                  6 All NOT EXECUTED
  **Total**                               **53** **All NOT EXECUTED**

The obligation identifiers are unique; no duplicate IDs were found. No
status was changed. The dependency blocks are consistent with the
design: E-7 is blocked on X-2; E-8 is blocked on X-1 and X-3; F-1 is
blocked on X-1.

The revision 11 additions cover the changed behaviour: actor/capacity
mapping, conservative failover, backend termination and restart, report
fields, admission/abandonment arbitration, and both timing windows. They
appropriately refine E-2, E-4, E-5, E-6 and E-8 without removing prior
obligations.

**No additional proof case is required by this bounded review.** The
existing F-1 ... F-6 cases, together with the carried-forward register,
cover the new revision 11 behaviour. Actual execution against the target
database/backend remains mandatory before release. No proof is claimed
complete.

## 7. Remaining matters by classification

-   **\[A\] Design defects:** None found within the commissioned scope.
-   **\[B\] Proof obligations:** All 53 register entries remain NOT
    EXECUTED. The R11-specific cases are F-1 ... F-6.
-   **\[C\] External dependencies:** X-1 Governance acceptance; X-2
    Identity resolution operation; X-3 Governance DecisionLog and
    per-part persistence contract.
-   **\[D\] Principal decision:** The Principal retains the decision
    whether to accept Platform, with explicit conditions. This report
    does not make that decision or authorise implementation.
-   **\[E\] Resolved:** F-01, F-04, F-05, F-06, F-07 at design level;
    D-192 implementation in the specification; and conformance with
    D-164 through D-192 within this review's scope.

## 8. Final recommendation and stop

**READY FOR PRINCIPAL CONSIDERATION.**

The Principal may now consider accepting Platform revision 11 subject to
explicit conditions:

1.  X-1a: formally accept the Governance AuthorityCheck addition note
    with the R11-1 actor/capacity mapping.
2.  X-2: accept and provide the Identity-owned PersonRef-to-Account
    resolution operation; no email matching or Platform-side identity
    inference.
3.  X-3: accept the Governance DecisionLog and per-part
    evidence-persistence contract.
4.  Preserve all 53 proof obligations as NOT EXECUTED until the
    corresponding implementation evidence exists. Execute the applicable
    proofs against the target database/backend before release.

No revision 12 is requested. No accepted decision is reopened. No
implementation is authorised by this report.

**Mandatory stopping rule satisfied. This review stops here.**

------------------------------------------------------------------------

**Reviewer identity:** GPT-6 in ChatGPT, acting as Principal DDD
Architect reviewer. Prior involvement in IR-P-02 and R6 is disclosed;
this is a bounded re-review, not a blind review.

**Review date:** 2026-11-11

---

## Appendix (provenance): GPT-6's chat message, as pasted

I will conduct this as the final bounded independent review IR-P-02-R7, acting as Principal DDD Architect.

The review will follow the Principal's commission, not the author's summary or suggested conclusion. In particular:

- Revision 11 must be assessed as proposed, not accepted.
- The accepted decisions D-164 through D-192 are authoritative within the supplied decision record.
- A property requiring execution against real code, infrastructure or a database is a proof obligation, not automatically a design defect.
- I will not expand the review into unrelated strategic or tactical design.
- I will apply the mandatory stopping rule. I will not manufacture additional findings simply to extend the review.

Initial integrity verification: all 14 files listed in `MANIFEST.sha256` have matching SHA-256 fingerprints. The revision 11 subject independently hashes to:

`e3772d2e8e266d909a1328cb2205b1d9271e47c0890caf5be8984d5724d4e005`

The proof register independently hashes to:

`a3fe00f28f8fe70c388891cbf36042f428bc69a70d1efdf9d24f3cc8e129fb18`

The supplied subject and register therefore match the commissioned artifacts.

## Independent review result

READY FOR PRINCIPAL CONSIDERATION

No material design defect remains within the commissioned scope.

The five R6 findings are resolved at the specification level. D-192 is correctly incorporated as the last-resort termination mechanism. The remaining matters are:

1. Governance acceptance of the AuthorityCheck addition (X-1).
2. Identity's accepted PersonRef-to-Account resolution operation (X-2).
3. Governance's DecisionLog and per-Resolution-part evidence-persistence contract (X-3).
4. The 53 implementation proof obligations, none of which has been executed.

These are not reasons to invent another Platform design revision.

### Finding-by-finding verdict

| Finding                      | Independent assessment                                                                          | Classification |
| ---------------------------- | ----------------------------------------------------------------------------------------------- | -------------- |
| F-01 Actor / capacity        | Corrected. Correct distinction between deciding organ, sitting, grant-giving person and tenure. | [E]            |
| F-04 Failover deadline       | Corrected. Full monotonic elapsed time is deducted through dispatch.                            | [E]            |
| F-05 Termination             | Corrected at design level. D-192 restart, containment and fail-closed fallback specified.       | [E]            |
| F-06 Incident reporting      | Corrected. Grant, designation, suspension and outcome references are explicit.                  | [E]            |
| F-07 Admission / abandonment | Corrected. Organisation-side unique-key arbitration establishes one authoritative result.       | [E]            |

The corresponding execution evidence remains [B], not [A].

## Review report saved

IR-P-02-R7 independent review

Markdown · 17.4 KB

Created

Download the independent review report

Report SHA-256: `d64b6bb945df05a2dd5ef21502dc8a0e99b02ce1c9448a4309741392b68a9eb2`

The report is saved under the requested relative directory in this session's workspace. It has not been committed to your local repository.

### My recommendation as Principal DDD Architect

You can now bring revision 11 to your Principal acceptance decision.

Do not commission revision 12 merely to investigate the remaining proof obligations. Keep them as implementation gates.

The architectural review stops here.
