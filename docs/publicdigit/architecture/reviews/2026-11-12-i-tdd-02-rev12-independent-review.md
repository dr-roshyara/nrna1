<!-- Recorder note (Claude, 2026-11-12): GPT-6's limited independent review of I-TDD-02 revision 12 (`b6d54798…`), as pasted by the Principal; extracted verbatim from the session transcript; body unaltered. Earlier GPT-6 reviews of revisions 1–11 were pasted in the session but not saved as files; their findings are recorded in each revision's change summary. -->

Independent review — I-TDD-02 Revision 12
PASS — limited review
No further correction required

Reviewer: GPT-6, Principal DDD Architect / Independent Reviewer
Subject: I-TDD-02 Identity Claims and Bindings, revision 12
Review scope: Final bounded review of the revision 11 → revision 12 correction.
1. Verification of revision 12
First, I verified the uploaded revision itself.
SHA-256 verification
MATCH

Expected and calculated fingerprint

b6d54798a3d7b2da09f5da4f872d92fa45e7e051ac507f50968d41cc8dc0fb44

Revision comparison
- Revision 11 compared with revision 12.
- All differences inspected.
- Changes confined to the stated correction, revision metadata, proof references and revision history.
- No unrelated architectural changes detected.

2. Review of the correction
Finding R11-1 resolved
The original inconsistency was real.

Revision 11 promised idempotent success when the same Account presented an already-redeemed code, but allowed the evidence supporting that response to be deleted.

Revision 12 correctly makes the response conditional on evidence availability.

The resulting behaviour is:
Situation    Expected outcome    Review
Same Account, redeemed row exists    Idempotent success    Correct
Different Account, redeemed row exists    Neutral failure    Correct
Same Account, row deleted    Neutral failure    Correct
Different Account, row deleted    Neutral failure    Correct
Account signs in after cleanup    Existing Binding remains valid    Correct
Second bootstrap redemption    Permanently prevented    Correct

The distinction between two different retry mechanisms is particularly important.

Case 2 — RegistrationCompletion
- Registration request committed.
- Response was lost.
- Same registration token is presented again.
- RegistrationCompletion provides the success response for 24 hours.
Cases 3 and 5 — Code re-presentation
- The claim row provides the evidence.
- While it exists, the original Account receives idempotent success.
- After cleanup, the code is unknown and receives neutral failure.
- The existing Binding is unaffected.
This is a clean separation of responsibilities. 
Eingefügter Text(20261112-052419).txt

3. Retention and security assessment
I specifically examined whether the correction weakens the previously established invariants.

It does not.

Three important properties remain intact:
1. INV-CLM-2: The permanent BootstrapSlot marker prevents another bootstrap redemption throughout the organisation's lifetime.
2. INV-CLM-3: A redeemed claim cannot be redeemed again while its record exists. Once deleted, the code is unknown.
3. INV-BND-1: An organisation's PersonRef can be bound to at most one Person.
The permanent bootstrap marker contains only the redemption timestamp and claim identifier. It does not preserve the code or its fingerprint.

Ordinary and bootstrap claims now follow the same retention principle for re-presentation.

This is the correct data-minimisation decision. 
Eingefügter Text(20261112-052419).txt

4. Proof obligations
The revision introduces PI-48 and extends PI-45 appropriately.
Proof    Assessment
PI-45    Correctly extended
PI-48    Correctly formulated
PI-14    Existing registration retry remains separate
PI-17a    Still required
PI-17b    Still required; security assessment, not executable test

One important distinction: a correctly specified test is not evidence that the implementation works.

All proof obligations remain outstanding. I make no claim that any test has passed. 
Eingefügter Text(20261112-052419).txt

5. Final review verdict
ACCEPTED — REVISION 12 CORRECTION
The specific revision 11 finding is resolved.
- No further correction requested.
- No accepted business rule changed.
- No strategic architecture decision changed.
- No new dependency introduced.
- No implementation authorised.
- No ratification implied.
Revision 12 is suitable for the next acceptance stage.

This is a bounded acceptance of the revision 12 correction, not unconditional approval of the complete tactical design for implementation.

6. Principal decision recommendations
I have also reviewed the outstanding decisions as presented in the document.

These are my recommendations for your consideration as Principal and human ARB authority.
Decision    Recommendation    Reason
P-A    A1    Simplest baseline authority model
P-A4    Defer    Avoid automatic denial-of-service through claim locking
P-B    B2    Minimum central personal-data footprint
B-T1 / B-T2    Defer until D-7    Initial-contact notification remains unresolved
P-C    C1    Organisation-defined validity with 14-day default and 30-day maximum
P-C2    BW-1    Reuse P-C's default for bootstrap claims

These recommendations are consistent with the current decision brief. 
Eingefügter Text(20261112-052419).txt

My position on each:
- P-A: I recommend accepting A1. It is the simpler governance baseline.
- P-A4: I recommend explicitly deferring it, rather than introducing an automatic security lock prematurely.
- P-B: I recommend B2. It preserves the separation between Platform and organisation-owned personal information.
- B-T1/B-T2: I recommend making no decision until Platform D-7 is resolved.
- P-C and P-C2: I recommend accepting C1 with BW-1 as one combined policy choice.
These remain your decisions. My recommendations do not ratify them.

7. What happens next?
I recommend the following sequence.
1. Close the revision 12 review
   
   Record R11-1 as resolved and revision 12 as having passed its limited independent review.
2. Principal acceptance decisions
   
   Decide P-A, P-A4, P-B, P-C and P-C2. Keep B-T1/B-T2 open pending D-7.
3. Complete Governance dependencies
   
   Follow the documented Governance extension route for the claim act kinds.
4. Complete technical verification
   
   Execute PI-1 through PI-48, including PI-17a, and carry out PI-17b.
5. Final implementation acceptance
   
   Only after the required decisions, proof obligations and security assessment have been reviewed should implementation be authorised.
One important instruction: Do not start implementation simply because revision 12 has passed this review. The document itself remains PROPOSED.
Final recommendation
I would close the revision 12 review without requesting revision 13.

The correction is proportionate, technically coherent and consistent with the existing Identity architecture.

The next meaningful work is no longer another wording correction. It is Principal decision-making and verification of the proposed design.

The non-blocking CLAUDE.md project-state sync warning should also be addressed in the project workspace, but it does not affect this architectural verdict.
