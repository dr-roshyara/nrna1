# I-TDD-02: scope and entry criteria (Identity, second tactical iteration)

| | |
|---|---|
| **Status** | **Scope proposal.** I-TDD-02 itself is **not written** here (commission instruction). Decides nothing. |
| **Commission** | Deliverable C of the "Identity Strategic Reconciliation and Principal Decision Dossier" commission, 2026-11-10. |
| **Inputs** | I-TDD-01 (P, 2026-11-09) as corrected by the addendum (S-5, S-6); the decision dossier; the maturity review §6. |
| **Placement** | `php scripts/doc-placement.php --scope=product-specific --maturity=research --domain=publicdigit` → `docs/publicdigit` (exit 0) |

## 1. The capability I-TDD-02 designs

**Organisation-issued claim connecting an existing organisational person to a PublicDigit Account. Single organisation, phone-first.**

| Step | Business journey | Owner |
|---|---|---|
| 1 | The organisation already has its own record of the person (affiliation and profile, D-101). | Organisation |
| 2 | An officer shows they may issue claims for that organisation (authority evidence). | Governance (competence); Organisation (rule) |
| 3 | The officer issues a single-use claim (desktop, DD-13). | Identity records it |
| 4 | The organisation delivers the code to the person through its own channel. | Organisation |
| 5 | The person, on a phone, registers from the code or redeems it while signed in. | Identity |
| 6 | Identity binds the Account to the Person safely (INV-ID-4, INV-ID-6). | Identity |
| 7 | The issuing organisation learns that its own reference now has access. | Identity → that organisation only |
| 8 | No other organisation learns anything. | C-1…C-4 |

**Outside this capability (must stay possible later; must not be blocked):**
- multi-organisation discovery (P-I2);
- recovery without the login address (P-I3);
- merge administration;
- organisation profile management (Organisation);
- membership admission (Membership; account creation is **not** admission);
- election eligibility;
- Governance decisions;
- Voting;
- PWA implementation;
- Keycloak;
- the D-90 equivalence capability (only its seam).

## 2. Entry criteria (before I-TDD-02 starts)
| # | Criterion | Why |
|---|---|---|
| E-1 | **P-I1 decided** | fixes `ClaimSubject` and INV-ID-6's shape |
| E-2 | Addendum corrections S-5, S-6 accepted (C-4 read as "minimised and reviewed") | I-TDD-01's Person rationale depends on it |
| E-3 | P-I12 direction known: (b) or (c) | the slice touches registration; "registration creates no affiliation" must be stated |
| E-4 | The Principal accepts this scope | ADP: derives from the preceding artifact |

**Not entry criteria (explicitly deferred, with seams):** P-I2, P-I3 (except the boundary in G-13), P-I8, P-I9, XD-PROV, merge policy, legal, P-I11.

## 3. What I-TDD-02 must resolve (the gaps of I-TDD-01)

| # | Gap | Must deliver | Resolved in I-TDD-02 or deferred |
|---|---|---|---|
| G-1 | Transaction boundaries across Person, Account, Claim | which aggregate changes commit together in redemption; what is eventual (the notice to the organisation) | **resolve** |
| G-2 | Concurrent redemption of the same code | exactly one wins; the loser gets the neutral failure | **resolve** |
| G-3 | Concurrent claims for the same Person | the rule "one active, unexpired claim per (organisation, subject)"; second redemption after the first bound → INV-ID-6 | **resolve** |
| G-4 | Neutral error contract | one message for unknown, expired, revoked, used, throttled and "Person already has an Account"; it never reveals whether an Account or Person exists | **resolve** |
| G-5 | Audit and security records | which acts are audited (issue, revoke, redeem, failed attempts), what each record holds (no codes, no cross-organisation data), retention: open (XD-PROV) | **resolve** the content; defer retention |
| G-6 | `ReferenceIssuer` and `AffiliationDiscovery` port contracts | inputs, outputs, failure behaviour, for **both** L1 and L2 | **resolve** the contracts; the mechanism stays P-I2 |
| G-7 | Governance authority check | which question is asked (may this officer issue claims for this organisation?), with what evidence; behaviour when the check is unavailable (deny) | **resolve** |
| G-8 | Cross-context unavailability | organisation context or Governance down at issue or redeem time: deny safely; no partial binding | **resolve** |
| G-9 | Merge implications for Account binding | what happens to two Accounts when their Persons merge (0..1 rule) | **state the rule**; the merge process itself is deferred |
| G-10 | Missing invariants | one active claim per (organisation, subject); merges respect Election's double-vote check; the equivalence answer is namespace-scoped (P-I9 seam) | **resolve** the first; state the others as seams |
| G-11 | Claim expiry and issuance policy | expiry as an **organisation rule** with a platform maximum (D-1b: no organisation rule as a platform default); throttling numbers | **resolve** the structure; the values are the organisation's |
| G-12 | Existing `users` data | mapping: each existing user → one Account + one Person (XD-3b migration note); personal fields on `users` → the organisation-local profile later (D-101); no data change in this design | **state the mapping**; the migration is a separate ADR |
| G-13 | Recovery boundary | INV-ID-6 restated; what organisations may and must never do (dossier P-I3 table) | **resolve** the boundary; procedures deferred |
| G-14 | D-90 implications | the namespace concept, so the claim slice does not foreclose P-I9 (a), e.g. `PersonRef` namespaces are not hard-wired to "organisation" | **seam only** |
| G-15 | Legacy flows the slice replaces | `VoterInvitation`, participant invitation, member invitation (which creates `Member`), approval by e-mail: which the slice replaces, and in which order | **resolve** the replacement order; the code changes come later |
| G-16 | Plain registration and P-I7 | how a claim treats an account whose Person no organisation refers to | **resolve conditionally** on P-I2 |

## 4. Acceptance criteria for I-TDD-02 (the document)
1. Follows the tactical chain (ADP) for Person, Account, Claim, with every new element traced to an invariant (APP, VODP) and every rejection recorded (ASP).
2. G-1…G-16 each answered or explicitly deferred with a seam and a reversal condition.
3. No element contradicts D-75, D-100, D-101, R-XD3c-1…5, VO-1, or the RA boundary (account creation ≠ admission).
4. No cross-organisation information in any event, error, log or port result; a short threat walk-through (TH-1…TH-6 of I-TDD-01) re-run against the final design.
5. Each invariant comes with a sentence of the test that would pin it (RED-ready), without code.
6. Phone journey at 390 px described end to end (register from code; redeem while signed in), including what the person sees on each failure.
7. **Independent review** by a reviewer outside the Claude family before the Principal accepts it.

## 5. After I-TDD-02 (for orientation, not in scope)
EP-01 plan for the first slice (requires P-I11 and P-I12) → RED → GREEN → synthetic browser journey → Principal acceptance. In parallel, independent of all this: the P-I10 interim privacy fix.
