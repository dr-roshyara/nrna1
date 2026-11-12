# Final challenge of the cross-context decision dossier: corrections and decision preparation

| | |
|---|---|
| **Status** | **Correction and decision-preparation report.** Records no ruling; changes no decision record, baseline, code or backlog. Written by Claude, **not independent** (it reviews Claude's own dossier; the Principal DDD Architect's challenges are the adversarial input). |
| **Reviews** | `./2026-11-10-cross-context-strategic-baseline-closure-decision-dossier.md` (the dossier). **Where this report differs, this report supersedes the dossier's wording**; the dossier itself is not edited. |
| **Commission** | "Final Strategic Dossier Challenge and Principal Decision Preparation", Principal, 2026-11-10. |
| **Sources re-read** | the dossier; CR-01 (C0–C4); ratified Membership architecture §8 (`2026-10-08-membership-architecture-ratified.md:197-201`); people-register design rev. 3 (§ lifecycle, `:116`, `:179`); IR-OPR-01 F-4, F-7, F-8; context map (Finance rows `:83-85`, `:111`); decision record D-41…D-48, D-65, D-71, D-73, D-75, D-77, D-79, D-97, D-101, D-103, D-104, D-107, D-109, D-111, D-113, D-114, D-118…D-122 |
| **Placement** | `php scripts/doc-placement.php --scope=product-specific --maturity=research --domain=publicdigit` → `docs/publicdigit` (exit 0) |

**Labels:** **FACT** · **RAT** · **WD** · **APP** · **P** · **REC** · **UNKNOWN** · **PRINCIPAL**.

## 0. Disagreements with the note (stated first)
1. **"CR-01, particularly C0–C5":** CR-01 has **C0–C4 only** (FACT, CR-01 header, "C0–C4 appended … by addition only"). There is no C5.
2. **P2:** the challenge to my reasoning is right, but **the recommendation survives for a different reason** (§3).
   - The organisation's "Member" capacity reflects Membership and cannot be ended by Organisation (D-103).
   - Under P2(ii) it is therefore an active relationship that blocks archiving.
   - Archiving never touches Membership or the reference in any case.
3. **P4 (my own error):** the dossier's residual risk ("two **unbound** records for one human") is wrong.
   - An unbound record can't be used to vote: voting requires a signed-in Person.
   - A second claim by the same Person is refused (D-113).
   - **The real residual is two Persons for one human** (duplicate Accounts with different login addresses, possible until Identity merges; I-13 correction in the P2–P7 brief §0).
4. **G-1:** the note presents arrangement A ("arrears change Membership status") as needing nothing new. It does: Membership's own arrears act needs Finance evidence, and **the FeePaid direction between Finance and Membership is unregistered** (context map; act N-5 is "a reading, not a decision"). A depends on that ARB act; B needs a new contract.

Otherwise I agree with all five challenges.

## 1. E-3: electoral division assignment
- **Existing proposal:** the division attaches to the organisation's register entry (its `PersonRef`) and covers both organisation modes. **But the dossier's ownership table names the contract "as voter relationship at τ".** That is an internal contradiction (FACT, dossier §1 table).
- **Challenge, answer by answer:**
  1. **Independent of Membership and the voter relationship?** Yes. A membership-mode organisation has no voter relationship (addendum 2 §2.1), so a voter-relationship contract would leave it unable to supply divisions. **The table row is wrong.**
  2. **Correct contract (P):** Organisation → Election, **"division assignments of scheme S, effective at asOf, as known at τ"**. It parallels R-5 and is a **contract addition**, not a C4 change.
  3. **A scheme without everyone assigned?** Yes. A member abroad, or a person not yet assigned. Election shows the committee the number of unassigned people before fixing.
  4. **No assignment** means no regional race from that scheme, **unless the rules name a fallback**. A fallback is itself an assignment, made by the organisation on an adopted rule as basis. **Election never invents one.**
  5. **One per scheme per date: a universal invariant? No.** The resemblance to D-118 is not a justification. The real argument is definitional:
     - a scheme exists to divide voters into **alternative** constituencies of the same organ;
     - two assignments within one scheme are exactly the E-4′ overlap (double representation in one organ);
     - so the choice is only **where** the conflict is caught.
  6. **History and corrections:** effective period plus τ.
     - A correction recorded before T counts from its effective date.
     - A correction recorded after T does not change the snapshot (D-42); the remedy is Contestation (D-71).
- **Business consequence:** without a fix, membership organisations couldn't use divisions at all.
- **Recommended wording (E-3′, revised):**
  > "An organisation may define electoral division schemes and assign the people it records to divisions, by individual act, authorised import or adopted residence rule (never by an address alone). Assignments have effective periods and are recorded with their basis. They grant no organisational belonging, rights, forum, audience or authority. Election reads them as at its cut-off, as known then. A person without an assignment has no regional race from that scheme unless the rules provide a fallback assignment."
- **PRINCIPAL:**
  - (a) E-3′ in principle;
  - (b) **multiple assignments within one scheme**: either **refused at assignment unless the rules allow it** (REC: the conflict is caught early, when the organisation can fix it), or **allowed, with E-4′ holding the affected races at election time**.
- **Impact:** adds a domain concept and **adds a contract** (Organisation → Election).

## 2. P4: duplicate records after an Identity merge
- **Existing proposal:** the pair is one voter; their right is held if the two records give different races.
- **Facts:**
  - D-113: a reference binds to a Person only by valid claim, and at most one of an organisation's references per Person.
  - An Identity merge of two Persons is the only path to two bound records of one organisation for one human (IR-OPR-01 F-7).
  - Identity owns sameness (D-75, D-111). The organisation owns its records (D-101).
- **Identity basis for one person, one vote:** **the canonical Person, not the Account.**
  - Accounts may be duplicated until Identity merges them, and an Account is only the sign-in means for a Person (XD-3).
  - **This requires one rule that the baseline doesn't yet state (R-ID, P):** "an election right is issued only to a Person whose binding to the electorate entry's reference exists at issuance".
  - Whether rights may ever go to unclaimed references (codes by post, for example) is **UNKNOWN**. If allowed, the residual risk below widens.

| Case | Outcome (REC) | Authority |
|---|---|---|
| both records give the same races | **one right**, both entries recorded as one case | Election (dedupe); Organisation (later record decision) |
| different races | **that person's right is held** until the organisation decides which record governs; release any time before close; unresolved at close means no vote, recorded | Organisation decides records; Election holds |
| one record archived | archived entries hold no active relationship (P2), so not in the electorate: no duplicate | — |
| one record's voter relationship pending | pending is not a voter (P6): no duplicate | — |
| merge disputed | Identity's current answer at τ stands; a dispute is Identity's; if Identity reverses after T, the remedy is Contestation | Identity |
| unresolved at cut-off | snapshot fixed **with the case open**; no election block | Election |
| one human, two Persons, not yet merged | undetectable; **residual risk**; Identity's merge process is the only control | Identity |

- **Partial issue (common races now, the rest later)** is narrower, but it conflicts with the single sealed submission per voter (§22, P). REC: hold the whole right of that one person. Partial issue remains a tactical option.
- **Evidence kept:** both entries; the merge notice reference; the case status at T; the hold and its release or expiry.
- **Published with the result:** the **number of held rights**, in the spirit of D-47's published replacement count.
- **Whole election ever held?** **No case requires it.** A held right is at most one missing vote, made visible. A held right never invents or silently discards a vote: it is either released or published as held.
- **PRINCIPAL:**
  - (a) the case table;
  - (b) rule R-ID;
  - (c) the organisation's authority to merge its own records under its rules (F-8).
- **Impact:** **adds an invariant** (R-ID; a known duplicate pair is one voter). No ownership change.

## 3. P2: archiving and active Membership
- **Facts:**
  - Membership refers to people by the organisation's `personRef`; one Membership per (Person, Organisation) (ratified §8 with D-77).
  - The `PersonRef` is Identity's (D-111).
  - The "Member" capacity in Organisation reflects Membership (D-103).
  - Workspace access is separate (D-104).
  - **People-register design rev. 3 still says archiving "ends their active affiliations and revokes access"** (`:179`). That is the cascade P2(ii) rejects; correcting it is a revision-4 item.
- **Five acts, kept apart:**

| Act | Owner | Does archiving do it? |
|---|---|---|
| archive the register entry | Organisation | — |
| end an affiliation | Organisation, with basis | no |
| withdraw workspace access | Organisation (D-104) | no |
| end Membership | **Membership only** | **never** |
| retire the `PersonRef` | Identity | **never** |

- **Challenge answered:**
  - archiving **does not** invalidate Membership, and the reference **does** stay available to Membership technically;
  - so "Organisation cannot end Membership" was not a sufficient reason.
- **The business question is instead what an archived member would mean.** The member keeps all rights (voting via C4, convocations), while the organisation has filed away its record of them, including the contact details it needs for formal notices (D-101 profile).
- **Options:**
  - **M1:** refuse while any reflected or own relationship is active. The "Member" capacity counts, because it reflects active Membership (D-103) and Organisation can't end it.
  - **M2:** allow. "Archived" then means "hidden from working lists" while membership continues. The word loses its meaning, and formal notices may fail.
- **REC: M1.**
  - "Archived" consistently means "no current relationship"; for a member, it follows Membership's own end.
  - **Archiving never changes Membership or the reference.**
  - The register entry stays available to Membership and Election historically.
  - Unarchiving is forward-only.
- **PRINCIPAL:** M1 or M2.
- **Impact:** Organisation invariant only; no contract change.

## 4. G-1: fees and voting rights
- **Facts:**
  - C4 predicates are `IsMember`, `Suspended`, `CurrentType` and tenure, at (asOf, τ) (ratified §8; CR-01 C4). No payment predicate.
  - Fees reference periods; settlement is Finance's (RAT outline).
  - FeePaid direction unregistered (N-5).
- **Arrangements:**
  - **A. arrears change membership status.**
    - Membership decides, by its own act under the rules, on Finance evidence; Election uses C4 unchanged.
    - **Needs the FeePaid direction registered** (a separate ARB act on Finance and Membership).
  - **B. arrears restrict voting while membership stays.**
    - Election needs a Finance fact. **Contract (P):** Finance → Election, **"settlement status of fee period P for the organisation's reference R, as known at τ"**, answering settled, unsettled, disputed or indeterminate.
    - **Finance states facts; Election applies the rule.**
    - Membership is not involved at query time. The period comes from the rule, keyed as fees already reference periods.
- **Timing (B):**
  - "paid by date X" is the rule's asOf;
  - a payment recorded after T doesn't count;
  - reversals, refunds or corrections after T don't change the snapshot (Contestation);
  - **disputed or indeterminate at T:** the rule's treatment applies; **if the rule is silent, that person's right is held** (as in P4) and the committee decides under the rules before close. **Neither "paid" nor "unpaid" is assumed.**
- **Optional:** yes. Invoked only where the organisation's rules contain an arrears-voting rule.
- **Consolidation without NRNA's rule:** yes. The generic baseline expresses both arrangements; NRNA's choice is configuration (UNKNOWN).
- **Recommended wording:**
  > "PublicDigit supports both arrangements, as the written rules determine. A: Membership changes status by its own act, and Election uses C4. B: Election obtains a Finance settlement fact for the rule's fee period, as known at its cut-off, and applies the rule. No payment predicate is added to C4. Where the fact is disputed and the rules are silent, the affected right is held, never assumed."
- **PRINCIPAL:** support both (REC), or declare B out of scope.
- **Impact:** **contract addition** (Finance → Election, B). A depends on a **separate ARB act** (the FeePaid direction).

## 5. P7: legacy e-mail linking
- **Facts:**
  - D-107: registration creates only an Account.
  - D-113: binding only by valid claim; "possession of a `PersonRef` proves nothing".
  - The legacy import attaches existing accounts to organisations by e-mail and creates affiliations silently (PBDIGIT-341, FACT).
- **Assessment:** already prohibited. Silent linking violates D-107 (an Account creating an affiliation) and D-113 (binding without a claim). **No new architecture ruling is needed.**
- **What remains:**
  - **(a) confirmation** that D-107 and D-113 apply to every current and future flow, legacy included;
  - **(b) a release condition:** the people register's first capability goes live only after the violation is contained.
  - (b) is operational, **not domain modelling**. Authorising the containment work is a further, separate act.
- **Impact:** none on the model; operational condition only.

## 6. Cross-check of the other decisions (material findings only)
| Decision | Finding |
|---|---|
| E-1, E-2 | consistent. E-2's "one decision everywhere" now includes rule R-ID at issuance (§2) |
| E-4′ | consistent with D-118 and D-122; it now covers within-scheme multiple divisions if §1(b) chooses "allowed" |
| E-5′ | consistent (D-38, D-45, D-48, D-71); no change |
| E-6′ | consistent (R-6a, P); no change |
| P3 | consistent; "one active standing voter relationship per register entry" stands |
| P5 | consistent; the division is **not** on the voter relationship (now also in the E-3′ wording) |
| P6 | consistent. A sixth state, **"bound by claim"**, matters for R-ID (§2): a confirmed voter whose reference is unclaimed cannot receive a right if R-ID is adopted |

No other material contradiction found.

## 7. Verdict
- **Established boundaries:** Identity, Organisation, Membership, Governance, Election, Voting and Finance as in the dossier §1. **The dossier's table row for divisions is corrected per §1.**
- **Already decided by existing records (confirm, don't rule):** P7(a) (D-107, D-113); the CR-01 time rules; D-41…D-48.
- **Organisation-specific and may stay open:** NRNA's arrears arrangement, districts and division schemes, candidacy timing, secrecy threshold.
- **Contract gaps for the generic platform:**
  - Organisation → Election division assignments (E-3′);
  - Finance → Election settlement fact (G-1 B);
  - the unregistered FeePaid direction (needed for G-1 A; a separate ARB act);
  - T-8, voter relationships at τ (P, already proposed).
- **Strategic discovery:** complete. These are bounded decisions, not discovery.

**Verdict: READY WITH EXPLICIT OPEN ORGANISATION-SPECIFIC QUESTIONS, once the decisions below are ruled.** Not before: two contract additions and one invariant (R-ID) await you.

## 8. Revised decision list (in order)
| # | Decision | REC | Kind |
|---|---|---|---|
| 1 | **P7(a):** confirm D-107 and D-113 apply to legacy flows | confirm | confirmation |
| 2 | **P7(b):** release condition (containment before the register's first capability) | yes | operational |
| 3 | **E-1:** no central account field decides regional voting | accept | boundary |
| 4 | **E-2:** one fixed electorate decision used everywhere | accept | invariant |
| 5 | **R-ID:** rights only to a Person bound to the entry's reference at issuance | accept | invariant (new) |
| 6 | **E-3′(a):** electoral divisions as an Organisation concept with the §1 wording and contract | accept in principle | concept + contract |
| 7 | **E-3′(b):** several divisions in one scheme: refused unless the rules allow / allowed, with E-4′ | refused unless allowed | rule |
| 8 | **E-4′:** genuine overlaps hold only the affected races | accept | rule |
| 9 | **E-5′:** per-layer honest secrecy; no operational override of written rules | accept | invariant |
| 10 | **E-6′:** candidacy by the organisation's rules; changes go to the committee | accept | rule |
| 11 | **P2:** M1 (archive only with no current relationship; never touches Membership or the reference) / M2 | M1 | Organisation invariant |
| 12 | **P3:** Worker and Guest may overlap; one standing voter relationship | accept | rule |
| 13 | **P4:** the case table; organisations merge their own records under their rules | accept | invariant |
| 14 | **P5:** standing voter relationship, separate from Membership and entitlement | accept | boundary |
| 15 | **P6:** R-b; pending never eligible; occurrence-only row key | accept | rule |
| 16 | **G-1:** support A and B per the written rules; B via a Finance → Election fact | support both | contract |
| 17 | **(separate ARB act, can follow)** FeePaid direction between Finance and Membership | — | needed only for G-1 A |

## 9. Mobile and installable app (PWA)
- **R-ID shapes the membership app.** On a phone, the first step is claiming one's record (D-113), because no claim means no right. The claim flow (I-TDD-02) is therefore the gate for both stage 2 (installable membership area) and phone voting.
- **What the installable membership area may show, per organisation and isolated (D-111):**
  - membership status (C4);
  - unit and electoral division, with "as of" dates;
  - after the cut-off, entitled races, or "held" with the reason (P4, G-1);
  - under arrangement B, the member's own fee-period status.
  - Showing the member their own data is permitted; **no other member's data is cached.**
- **Never offline or cached:** ballots, credentials, voting pages, "has voted" status (§22 Level A).
- **Honesty on the device (E-5′):** the app states what is guaranteed (stored-data unlinkability) and what isn't (observation of the screen).
- **Release order for phones:**
  1. P7(b) containment;
  2. claim capability;
  3. installable membership area;
  4. phone voting after E-1/E-2 and R-ID are implemented.
