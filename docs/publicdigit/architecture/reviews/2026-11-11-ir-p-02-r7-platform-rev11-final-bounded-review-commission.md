# IR-P-02-R7: final bounded independent review of the Platform (tenancy) design, revision 11

| | |
|---|---|
| **Commissioning authority** | The Principal (D-94), 2026-11-11. **The Principal's commission "IR-P-02-R7 — Final Bounded Independent Review of Platform Revision 11" (§§4–8) governs and is reproduced verbatim in §4 below.** This file adds the files, fingerprints and a short change map |
| **Reviewer** | **GPT-6 in ChatGPT.** Not Claude, not DeepSeek, not in Claude Code. **Prior involvement:** author of IR-P-02 and IR-P-02-R … R6. Not blind |
| **Subject** | `docs/publicdigit/architecture/reviews/2026-11-11-platform-tenancy-tactical-design.md`, **revision 11** ("Revision 11", R11-1 … R11-7), SHA-256 **`e3772d2e8e266d909a1328cb2205b1d9271e47c0890caf5be8984d5724d4e005`**. Verified before packaging; frozen |
| **Proof register** | `2026-11-11-platform-proof-obligation-register.md`, SHA-256 `a3fe00f28f8fe70c388891cbf36042f428bc69a70d1efdf9d24f3cc8e129fb18`. Stamped for revision 11. **53 obligations (A 14, B 10, C 9, D 6, E 8, F 6), all NOT EXECUTED, no duplicate ids** |
| **Status** | **Proposed, not accepted. No implementation authorised.** The author's statements are not evidence |
| **Nature** | **Final, bounded review with a mandatory stopping rule (§4, Principal's §5).** Not a strategic discovery; not a general review |

## 1. Files (`MANIFEST.sha256` authoritative)

| # | File | Status |
|---|---|---|
| 01 | this commission | — |
| 02 | **subject**, revision 11 | proposed |
| 03 | proof register (A–F) | author's; all NOT EXECUTED |
| 04 | revision 10 snapshot (`30ecb53e…`) | historical (R6 subject); **diff 04 → 02** |
| 05 | IR-P-02-R6 report (findings F-01 … F-09) | historical, verbatim |
| 06 | decision record: **D-164 … D-192**; **D-190 §11.89, D-191 §11.90, D-192 §11.91** | binding |
| 07 | multi-database design | accepted (D-178) |
| 08 | strategic baseline acceptance act | accepted (D-173) |
| 09 | Governance thin slice | accepted (D-157); **does not contain the D-190 note** (X-1 unratified) |
| 10 | AuthorityCheck contract review (actor / capacity mapping, §3, §5) | adopted into file 09 as correction notes |
| 11 | strategic context map and governance tactical model (GD-11, GD-12) | **PROPOSED** (X-3) |
| 12 | XD-3 Identity ARB act | accepted (D-75) |
| 13 | I-TDD-01 Identity design | **PROPOSED** (X-2) |
| 14 | I-TDD-02 scope | **scope only; I-TDD-02 not written** (X-2) |

## 2. Change map R6 → revision 11 (author's navigation aid, not evidence)

| R6 finding | Revision 11 | Decision |
|---|---|---|
| F-01 actor / capacity; per-part persistence overstated | R11-1 (draft-note column; persistence sentence withdrawn to X-3) | D-190 |
| F-04 failover formula | R11-2 | D-189 |
| F-05 termination | R11-3 (10 s window, containment, forced restart, unroutable fallback) | **D-192** |
| F-06 report fields | R11-4 | D-188 |
| F-07 admission vs abandonment | R11-5 (tombstone arbitration on `operation_id`; 2 min window) | — |
| F-08 register | R11-6; register section F; E-8 blocked on X-1 and X-3 | — |
| F-09 external | R11-7 (X-1a / X-1b, X-2, X-3) | — |

## 3. Questions

As in §4 (Principal's commission, governing):
- **A.** F-01, F-04, F-05, F-06, F-07;
- **B.** D-192;
- **C.** X-1, X-2, X-3;
- **D.** conformance with D-164 … D-192 and context ownership;
- §6: the proof register;
- §7: the required output.

**Classify with [A] … [E] only, and apply the stopping rule (§5).**

**Allowed verdicts:**
- **READY FOR PRINCIPAL CONSIDERATION**;
- **NOT READY** (only with a material design defect, precise evidence and the minimum correction).

**Title:** "IR-P-02-R7 — Final Bounded Review: Platform Revision 11".
- Recalculate the manifest and the subject fingerprint first.
- **Rules:**
  - modify nothing;
  - decide nothing for the Principal;
  - reopen nothing;
  - claim no acceptance;
  - **stop after the report.**

## 4. Principal's commission (governing; §§4–8 reproduced verbatim, headings demoted one level)

### 4. Review scope

#### A. Recheck the five findings from IR-P-02-R6

**F-01 — Governance AuthorityCheck**

Verify:

- `designate-platform-contacts` uses the deciding organ as actor and its sitting as capacity.
- The designated PersonRef is the subject, never the actor.
- `give-case-grant` represents the actual grant-giving actor and the appropriate tenure or sitting.
- The existing AuthorityCheck contract is reused rather than creating a second schema.
- Per-Resolution-part `ruleRef` and `policyVersion` persistence remains an X-3 Governance matter.
- D-190 is not overstated.

**F-04 — Deadline and failover**

Verify that all elapsed time from the pre-request monotonic reading through clock response and dispatch is deducted.

No recomputation may allow an operation to continue beyond deadline D.

If the authoritative clock or conservative remaining duration cannot be established, dispatch must be refused.

**F-05 — Backend termination**

Verify that revision 11 applies D-192 correctly:

- Normal cancellation and termination are attempted.
- The confirmation window is explicit.
- The database server is restarted when the operation cannot be stopped.
- Restart confirmation is based on reliable server evidence.
- If restart cannot be confirmed, the affected Organisation database becomes unroutable.
- Shared-server consequences and incident reporting are represented.
- `contained` is never treated as proof of termination.
- Unconfirmed termination remains recorded as access that may have occurred.

Do not claim that the design itself proves the real database behaviour. That is a proof obligation.

**F-06 — D-188 incident report**

Verify that each operation has:

- Operation ID;
- Grant ID;
- Failed designation reference;
- Suspension reference;
- Separate outcome and lifecycle status;
- Access completion and uncertainty;
- Termination confirmation;
- Audit evidence references.

Check the treatment of operations that have been registered but not yet admitted.

**F-07 — Admission and abandonment**

Verify that the Organisation database arbitration is coherent:

- Both admission and abandonment compete for the same unique operation identity.
- The first successful transaction determines the Organisation-side result.
- Central Platform state adopts the authoritative Organisation result.
- A late or abandoned operation cannot be admitted inconsistently.
- The two-minute admission window has clear clock and boundary semantics.

Do not assume that a unique key alone proves all cross-database lifecycle behaviour. Identify the remaining proof obligations.

#### B. Recheck D-192

D-192 is an accepted Principal decision.

Do not ask the Principal to make this decision again.

Verify that revision 11 does not:

- Change the accepted hosting-layout decision;
- Treat server restart as a normal operational mechanism;
- Treat a blocked grant as proof that running work stopped;
- Hide the consequences for other Organisations sharing the server.

#### C. Recheck the external dependencies

**X-1 — Governance addition**

The two act kinds are authorised by D-190, but the addition must be formally accepted in Governance. Verify that the draft does not claim ratification has already happened.

**X-2 — Identity PersonRef-to-Account resolution**

Verify that Platform consumes an accepted Identity operation and does not implement its own identity lookup, use email matching or infer an Account.

**X-3 — Governance DecisionLog**

Verify that unresolved GD-11/GD-12 and per-part evidence-persistence semantics remain owned by Governance.

Do not invent Governance rules in Platform.

#### D. Strategic architecture conformance

Check that revision 11 continues to respect:

- D-164: separate database per Organisation;
- D-166: platform operation never grants content access;
- D-167 and D-181: authority is never inferred;
- D-170: only permitted pending grant metadata is held centrally;
- D-173: accepted strategic baseline;
- D-174 through D-191: accepted contact, grant, recovery and metadata decisions;
- D-192: forced server restart as the last resort.

Confirm that Platform has not absorbed responsibilities belonging to Identity, Governance, Membership, Finance or the Organisation.

### 5. Mandatory stopping rule

This is essential.

Do not continue finding increasingly detailed issues merely because more implementation details could be specified.

Classify every remaining issue as one of:

- **[A] Design defect:** a real contradiction, missing business rule or unsafe behaviour in the specification.
- **[B] Proof obligation [TU]:** a property that can only be demonstrated through implementation or a test against the target database/backend.
- **[C] External dependency [EXT]:** an acceptance or contract owned by another bounded context.
- **[D] Principal decision:** a genuine business, constitutional, governance or product-scope choice.
- **[E] Resolved:** already addressed by an accepted decision or sufficiently specified design.

A test that requires actual code or a real database is not automatically a design defect.

Do not demand that implementation-level behaviour be proved on paper.

Do not introduce additional technical hardening unless it is necessary to enforce an accepted business or security invariant.

Do not reopen an accepted Principal decision.

### 6. Proof register

Verify all 53 obligations.

Preserve every existing obligation and its execution status.

Do not mark any proof as executed unless actual execution evidence exists.

Identify any genuinely missing proof cases arising from revision 11, but do not create new speculative test requirements.

### 7. Required output

Prepare a concise independent-review handover containing:

1. Exact revision 11 and proof-register fingerprints.
2. Verification of the actual source files.
3. A finding-by-finding assessment of F-01, F-04, F-05, F-06 and F-07.
4. Conformance with D-164 through D-192.
5. Status of X-1, X-2 and X-3.
6. Proof-register status.
7. A clear distinction between design defects, proof obligations and external dependencies.
8. One final verdict.

Allowed verdicts:

- **READY FOR PRINCIPAL CONSIDERATION:** no material design defect remains; outstanding matters are explicit external dependencies, proof obligations or Principal decisions.
- **NOT READY:** a material design defect remains, with precise evidence and the minimum correction required.

Do not use "READY" to imply acceptance or implementation authorisation.

### 8. Stop condition

If revision 11 satisfies the stopping rule, recommend that the Principal consider acceptance with explicit conditions for X-1, X-2, X-3 and the applicable implementation proofs.

If a material design defect remains, identify only that defect and the minimum correction required.

Do not start another broad review cycle.

Do not implement Platform.

Do not execute tests or claim proof completion.

Do not modify accepted strategic decisions.

Do not create revision 12 during this commission.

Prepare the exact evidence package and stop for GPT-6's independent review.

The purpose is to complete the architectural baseline responsibly and move the project forward, not to pursue endless review.
