# Identity: adversarial review of the decision dossier and the I-TDD-02 scope

| | |
|---|---|
| **Status** | **Review and decision preparation.** Decides nothing; rewrites none of the three reviewed documents; proposes corrections for the Principal to approve. |
| **Commission** | "Adversarial Validation of Identity Decision Dossier and I-TDD-02 Scope", Principal, 2026-11-10. |
| **Reviewed** | `./2026-11-10-identity-strategic-reconciliation-addendum.md` (A) · `./2026-11-10-identity-principal-decision-dossier.md` (B) · `./2026-11-10-identity-i-tdd-02-scope.md` (C). All PROPOSED, uncommitted. |
| **Independence** | The reviewer (Claude) wrote A, B and C. This review tries hard to break them and **does change the main recommendation** (§3). It is still not an independent review. |
| **Placement** | `php scripts/doc-placement.php --scope=product-specific --maturity=research --domain=publicdigit` → `docs/publicdigit` (exit 0) |
| **Git state** | branch `election-flow-updated`, HEAD `754144edb`; no code changed; no tests run. |

**Labels:** **RAT** · **WD** (binding working decision; direct Principal rulings in DR §11) · **P** · **REC** · **FACT** · **UNKNOWN** · **PRINCIPAL**.

## 1. Executive assessment
1. **P-I1 was not ready to decide as presented.** The dossier compared three options and missed a fourth that fits the ratified records better: **(d) the organisation gets a stable `PersonRef` at import, but Identity creates the Person only when a claim is redeemed.** Option (d) gives Membership and Election their stable reference from day one, never creates two Persons for one human across organisations, and needs no merges for people in several organisations. Option (c), the earlier REC, creates a duplicate Person for **every** person in two organisations, so merges become routine. **My recommendation changes from (c) to (d)**, subject to the Principal confirming that (d) is inside the space XD-3 §3 left open (it is K4 option (ii) with Identity issuing the reference instead of an organisation placeholder).
2. **The ownership model is coherent once a `PersonRef` is defined as a reference to a person, scoped to one organisation, and never as the affiliation itself** (§3.1).
3. **E-3 (P-I12 before I-TDD-02) was an error.** I-TDD-02 only needs the existing rule "registration creates no affiliation or membership" (D-100, D-101, **WD**). Remove E-3.
4. **P-I10 is more serious than the dossier said.**
   - Membership-mode import works as an **account-existence check for any e-mail address**.
   - Election-only import **automatically creates the importing organisation's affiliation records for an existing account**, without the person's consent.
   - That organisation's voter list then shows the account's **central name, e-mail and last IP address**.

   A neutral preview message fixes none of this (§5).
5. **The P-I9 wording "unknown counts as not the same" was unsafe** (addendum §5; dossier P-I9). Corrected rule: approve only on "different", refuse on "same", **wait** on "unknown". "Different" must be defined honestly as "not known to be the same", because Identity can never prove two people are different humans (§6).
6. **I-TDD-02 was too broad.** Of 16 gaps, 9 are essential; the rest become one-sentence seams or move to implementation planning. Under option (d), the `anchored` question (P-I7) disappears (§7).

## 2. Evidence and authority
| Source | Status | Used for |
|---|---|---|
| Membership act §11; `…-ratified.md:157` ("Identity: person equivalence; Membership reconciles through explicit facts") | **RAT** | ownership; merges reach Membership only as explicit facts |
| XD-3 act (D-75) §2, §3 | **RAT** | canonical Person; a different `PersonRef` per organisation; only Identity resolves; Person ≠ Account; K4 **left open** (§3) |
| XD-3b §1 (K4 options i–iii; duplicates), §2, §2a | accepted via D-75 | K4 option (ii) lazy is an admissible option on record |
| XD-3a C-1…C-5 (`:44-48`) | accepted via D-75 | C-4: "minimised and undergoes data-protection review" |
| D-100 (`DR:821-825`), D-101 (`DR:827-831`) | **WD** | Organisation owns affiliation and profile; a platform record is not an Organisation |
| D-84, D-88, D-90 | **WD** | stewards are platform-appointed; "a distinct canonical person"; after-the-fact flag |
| XD-PROV | **P** | — |
| Organisation baseline v1.0 | **P** (rev. 1, untracked, another session) | consistent with D-101 (addendum §1); **not treated as accepted** |
| VO-1; D-44, D-45 | **RAT**; **WD** | Voting receives no identity |
| `app/Contexts/Membership/Domain/Organisational/PersonRef.php`; `AdmitMemberHandler.php:37` | **FACT** | `PersonRef` is an opaque, caller-supplied string; nothing issues it yet |
| `app/Services/VoterImportService.php:68-190, :194-235, :237-348` | **FACT** | §5 |
| `app/Http/Controllers/ElectionVoterController.php:71, :392-410` | **FACT** | the voter list shows the account's `name`, `email`, `current_ip` |
| `app/Http/Controllers/Auth/RegisterController.php:41-63` | **FACT** | platform-organisation role at registration |

## 3. P-I1: conceptual analysis

### 3.1 The nine things, in plain language
| # | Thing | What it is | Created and owned by |
|---|---|---|---|
| 1 | The human | Ram, the real person | nobody (not a record) |
| 2 | Identity's **Person** | a bare number meaning "this human, as far as PublicDigit knows"; no name, no address | Identity |
| 3 | The organisation's **person record** | NRNA's own file on Ram: name, contact, address, and his affiliation (Member, Guest, Worker, voter…) | NRNA (Organisation, D-101) |
| 4 | **`PersonRef`** | a meaningless code NRNA writes in its file so that Identity and other NRNA contexts can refer to Ram; another organisation gets a different code for the same Ram | **issued by Identity**, **held by the organisation** |
| 5 | Membership's reference | Membership stores NRNA's `PersonRef` on Ram's membership | Membership uses #4 (RAT) |
| 6 | Election's reference | Election uses NRNA's `PersonRef` for Ram in NRNA's elections | Election uses #4 (P-I8) |
| 7 | **Account** | Ram's sign-in (address and password) | Identity |
| 8 | **Claim** | a one-time code NRNA hands Ram so he can connect his Account to NRNA's `PersonRef` | issued at NRNA's request; recorded and redeemed in Identity |
| 9 | **Sign-in discovery information** | whatever lets PublicDigit show Ram "your organisations" after sign-in | Identity's mechanism (P-I2) |

**Is `PersonRef` a reference or an affiliation?** A reference only. It says "this file entry is a human". It does not say in what capacity, since when, in which unit, or whether he is a member. Those stay in NRNA's file (#3) and in Membership (#5). **Identity issuing #4 does not make Identity the owner of #3.**

**What does "one canonical Person" mean?** One Person per human **after** Identity has resolved who is who, **not** guaranteed uniqueness at import. Uniqueness at import would need matching by e-mail or name, which D-75 forbids. XD-3b §1 already accepts that independent imports can produce separate Persons, to be merged only by an explicit Identity act (C-5).

### 3.2 The four options
| | (a) eager | (b) lazy, organisation placeholder (K4 ii as written) | (c) eager, no discovery until claim (earlier REC) | **(d) reference at import, Person at claim** (new) |
|---|---|---|---|---|
| At import, Identity holds | a Person + NRNA's `PersonRef` (and, under L1, which organisation) | nothing | a Person + NRNA's `PersonRef`, nothing usable for discovery | an **unbound `PersonRef`** for NRNA (or nothing, if references are issued from a secret key) |
| Membership and Election get a stable reference at import | yes | **no**: a placeholder, then **re-keying** of recorded facts | yes | **yes** |
| Same human imported by two organisations | **two Persons** | none until claims | **two Persons** | **no Person yet**; two unbound references, which is correct and private |
| When that human claims both | redeeming the second claim with the same Account hits 0..1 Account per Person → **a merge** | Identity issues references at claim; no duplicate | **a merge**, for every multi-organisation member | the second claim **binds NRNA's or the other organisation's reference to the existing Person**: **no merge** |
| Merges needed for | every multi-organisation member, plus true duplicates | true duplicates only | as (a) | true duplicates only: the same human imported twice by one organisation, or two Accounts for one human |
| What a merge changes elsewhere | cross-organisation merge: nothing outside Identity (each organisation keeps its own reference); same-organisation duplicate: Membership reconciles by explicit facts (RAT, `…-ratified.md:157`) | same | same | same |
| INV-ID-6 (no re-binding) | works (the claim names a Person; is it already bound?) | changes shape | works | works (the claim names a reference; is it already bound? A bound reference can never be re-bound) |
| People who never register | Identity holds a Person record about them without consent | nothing | a Person record without consent | at most a meaningless unbound reference |
| Privacy (C-2, C-4) | weakest (a central entry per unregistered person; with L1 also their organisation) | strongest in Identity, but spreads placeholders | middle | **strong**: a stored link between a reference and a Person exists only for people who redeemed a claim (an index that people consent into) |
| Operational burden (solo developer) | merge tooling early | re-keying in Membership and Election (touches ratified Membership) | merge tooling early | reference lifecycle (issue, retire); merge tooling only later |
| Effect on P-I2 | none | none | needs an organisation-less mapping or L2 | resolution needs a stored link for **claimed** references only; **pure "derive and store nothing" (L2) cannot resolve an unbound reference**, so (d) leans toward a stored minimal mapping for people who claimed. Discovery itself stays open. |
| Effect on P-I7 | needed | n/a | needed | **disappears**: a self-registered Person simply receives the reference |

### 3.3 Costs of (d) that must be stated
- **New concept: the unbound reference.** It needs a lifecycle: issued at import; bound at claim; retired when the organisation deletes its record (links to XD-PROV).
- **It narrows P-I2.** Identity must store the reference-to-Person link for claimed references. Under C-4 that is a minimised, consent-based index; it still needs the data-protection review.
- **Identity cannot answer equivalence for people who never claimed.** No context needs that; an organisation's own duplicates remain its own matter.
- **Consistency with D-75 needs the Principal's confirmation.** XD-3 §2.2 gives each organisation "a different opaque `PersonRef` for the same human". Under (d) that reference exists before Identity has materialised the Person. XD-3 §3 leaves K4 open and XD-3b lists lazy creation as an option, so (d) appears to be inside the open space, but it is an interpretation.
- **Within-organisation duplicates** (NRNA imports Ram twice) are found only when both codes are redeemed by one Account. Until then Election's double-vote check relies on the organisation's own data, as in every option.

### 3.4 Plain explanation for the decision
> When NRNA uploads its member list, every person on it gets NRNA's private code-number at once, so their membership and voting records are solid from day one. PublicDigit does **not** yet create a central record of that human. Only when the person redeems NRNA's invitation code does PublicDigit create (or find, if they already have an account) their central record and connect NRNA's code-number to it. If the same person later redeems a second organisation's code with the same account, that organisation's code-number joins the same record: no duplicate, nothing to merge, and neither organisation learns about the other.

## 4. P-I12: the entry-criterion contradiction
- **Finding:** the dossier says P-I12 blocks only implementation; the scope document's E-3 made it an I-TDD-02 entry criterion. **Contradiction confirmed; E-3 was wrong.**
- **Why I-TDD-02 does not need P-I12:**
  - the rule it needs already exists: "**Account registration creates no Organisation affiliation and no Membership**" (D-100: a platform record is not an Organisation; D-101: affiliation is the organisation's own record; RA: account creation is not admission);
  - the demo and welcome flow (`RegisterController.php:41-63`; pinned by `RegistrationAndLoginTest`, `LoginOrganisationAccessTest`) is legacy behaviour to replace during implementation planning.
- **Proposed correction to C:** delete E-3; add to I-TDD-02's required statements: "registration creates no affiliation or membership"; P-I12 moves to the first slice's EP-01 plan.

## 5. P-I10: voter-import privacy (verified)
| Question | Finding (FACT) |
|---|---|
| Does the preview reveal whether an e-mail has an account? | **Yes**, in both modes. Membership mode: the validation errors are "User '…' does not exist in the platform" versus "'…' is not an active member of this organisation" (`VoterImportService.php:155-190`). Election-only mode: "Existing user" / "Will be created" (`:194-235`). |
| Does the backend link an existing Account across organisations? | **Membership mode: no**; it enrols only accounts that are eligible voters of this organisation (`:180-187`). **Election-only mode: yes.** `User::firstOrCreate` by e-mail, then **this organisation's** `UserOrganisationRole` and `OrganisationUser` are created for the existing account and it is enrolled as a voter (`:262-312`). An invitation goes only to new or unverified accounts (`:314-332`), so a verified person is enrolled **silently**. |
| What does the importing organisation then see? | Its voter list shows the account's **central** `name`, `email` and **`current_ip`** (`ElectionVoterController.php:71`; export `:392-410`). The name may have been entered by the person or by another organisation's earlier import; the IP comes from the person's activity anywhere on PublicDigit. |
| Can it infer another organisation's relationship? | **Indirectly.** It learns that the person uses PublicDigit; it may receive a name typed by another organisation; and a recent `current_ip` reveals activity elsewhere. It cannot list the other organisations. |
| Does changing only the preview text remove the risk? | **No.** The validation messages, the silent cross-organisation linking and the central-profile display remain. |
| Smallest safe interim measure (REC) | (1) **Neutral messages in both modes** ("will be invited"; one generic "cannot be added" in membership mode), so existence is never revealed. (2) **Election-only mode: do not auto-link an existing account** that has no affiliation with this organisation yet. Record the voter as pending and send an invitation that the person must accept, using the existing invitation mechanism, and link only on acceptance. (3) **The voter list shows `current_ip` only for activity in this organisation's elections**, or not at all; this is a separate, related item. |
| What must stay unchanged until the claim slice | Membership-mode enrolment of this organisation's eligible members; invitations for new accounts; existing voter records. |
| Classification | A **privacy defect with a live cross-organisation effect**. It belongs in `PBDIGIT-341` (widen its scope), possibly with a separate item for `current_ip` exposure. Each step needs an EP-01 plan. |

## 6. P-I9: equivalence for Reference Geography
| Question | Answer (P) |
|---|---|
| Who may ask? | Only Reference Geography's stewardship function, about references in **its own namespace** (stewards and matter reviewers are platform-appointed, D-84; D-84a). No organisation is ever a caller. |
| What may be compared? | Two references from the same namespace. Never across namespaces; never a search. |
| "same" | Identity has bound both references to one Person: the same Account, or a completed merge. |
| "different" | Both references are bound to **two Persons that Identity does not know to be the same**, and no merge review involving them is pending. **This is not proof** that they are different humans: one human with two accounts under two addresses cannot be detected without matching, which D-75 forbids. |
| "unknown" | A reference is unbound, or a merge review involving them is pending. |
| Can "unknown" satisfy D-88? | **No.** Fail-safe rule: **approve only on "different"; refuse on "same"; on "unknown" the change waits** (D-84 already says that without a second person "the change waits"). |
| Is "different" enough for D-88? | It is the best ex-ante evidence possible. D-90 is the remedy if a later merge shows sameness. **The Principal should confirm that D-88 means "not known to be the same" and not "proven different"**, since proof is impossible by design. |
| Pending merge review | answers "unknown" for affected references; Geography waits. |
| Audit | every question: caller, purpose (the change's id), both references, answer, time. Every merge notice to a namespace: references affected, merge id. Retention: XD-PROV. |
| Can an organisation discover anything? | No: it is not a caller, and references are namespace-scoped. |
| **Corrections to A §5 and B P-I9** | replace "'unknown' … counts as 'not established as the same'" with the fail-safe rule above. |

## 7. I-TDD-02 scope: G-1 … G-16 classified
| Gap | Class | In I-TDD-02 as | Seam or revisit condition |
|---|---|---|---|
| G-1 transactions | **essential** | resolve | — |
| G-2 concurrent redemption | **essential** | resolve | — |
| G-3 one active claim per (organisation, reference) | **essential** | resolve | — |
| G-11 expiry and issuance structure | **essential** | resolve (organisation-set expiry under a platform maximum; D-1b) | values per organisation |
| G-16 redemption by an already signed-in account | **essential** | resolve; under (d) it binds the reference to the account's Person (P-I7 not needed) | under (a)/(c): revisit with P-I7 |
| G-4 neutral errors | **security** | resolve | — |
| G-5 audit content | **security** | resolve the content | retention: XD-PROV |
| G-7 authority check | **security** | resolve (deny when unverifiable) | which competence: the organisation's rule |
| G-8 unavailability | **security** | resolve (deny; no partial binding) | — |
| G-13 recovery boundary | **security** | one statement: INV-ID-6 plus the "never" list | procedures: P-I3 |
| G-6 ports | **future contract** | `ReferenceIssuer` fully (the slice issues references); `AffiliationDiscovery` as a named seam only | P-I2 |
| G-9 merge vs Account | **future contract** | one rule (0..1 Account per Person after a merge: the holder keeps one, the other is retired by an Identity act) | the merge process |
| G-14 namespaces | **future contract** | one sentence: reference namespaces are not hard-wired to "organisation" | P-I9 |
| G-10 other invariants | **deferred** | state as seams (Election double-vote; equivalence scope) | P-I8, P-I9 |
| G-12 `users` mapping | **deferred** | one principle (each existing user → one Account + one bound Person + references for its current organisations); migration is an ADR | migration ADR |
| G-15 legacy flows | **out of scope** | name which flow the first slice replaces (election-only voter invitation, §5); the rest goes to implementation planning | EP-01 plans |

**Result:** 9 must-resolve items (G-1, -2, -3, -4, -5, -7, -8, -11, -16), 4 one-sentence contracts or principles (G-6, -9, -13, -14), and 3 seams or plans (G-10, -12, -15). That is appropriately scoped for one capability.

## 8. Revised minimum entry criteria for I-TDD-02
| # | Criterion | Verdict |
|---|---|---|
| E-1 | **P-I1 decided** | **necessary**: it fixes what a claim names and how INV-ID-6 reads |
| E-2 | addendum corrections S-5, S-6 accepted | **necessary only under (a)/(c)**; under (d) the Person design changes anyway. Fold it into the P-I1 ruling. |
| E-3 | P-I12 direction | **incorrectly stated; remove** (§4) |
| E-4 | scope accepted (as corrected here) | **necessary** |

**Minimum: E-1 and E-4.**

## 9. Principal decision brief
**Only one decision is needed now: P-I1.** (P-I12, P-I10, P-I11 and the others do not block I-TDD-02.)

| | |
|---|---|
| **Business question** | When NRNA uploads its member list, what does PublicDigit create for people who have never used PublicDigit? |
| **Already decided** | NRNA owns its own files on people and their affiliations (D-101); each organisation gets its own private code-number for a person (D-75); no matching by e-mail or name (D-75); account ≠ membership. |
| **Undecided** | When the central person record comes into existence. |
| **Options** | **(a)** a central record at upload · **(b)** nothing at upload; NRNA keeps a temporary number and records are renumbered at sign-up · **(c)** a central record at upload, hidden from sign-in until the code is redeemed · **(d)** NRNA's private code-number at upload; the central record only when the person redeems a code |
| **Practical consequences** | (a)/(c): everyone in two organisations becomes two central records until a merge. (b): membership and voting records must be renumbered after sign-up. **(d): stable numbers from day one, no renumbering, no duplicates across organisations.** |
| **Privacy and security** | (a): central records about people who never consented. (c): the same, hidden. (d): central records only for people who redeemed a code. All: no organisation sees another; the takeover guard holds. |
| **REC** | **(d)**: it keeps Membership's ratified stable reference, needs no renumbering, avoids routine merges, and holds the least data about people who never signed up. Its costs: a new "unbound reference" concept; it leans P-I2 toward a stored link for people who claimed; and it needs your confirmation that it fits D-75's open K4 space. |
| **Blocks** | I-TDD-02 (yes); the first slice (yes). |
| **Ruling required** | Choose (a), (b), (c) or (d). If (d): also confirm that it is consistent with XD-3 §2.2 (a `PersonRef` may exist before its Person is materialised). |

## 10. Next step (REC)
1. The Principal rules on P-I1.
2. The Principal approves the proposed corrections to A, B and C (listed in §11), which are then applied as append-only correction notes.
3. I-TDD-02 is commissioned with E-1 and E-4 only and the scope of §7.
4. Independent review of I-TDD-02 (outside the Claude family).
5. In parallel, independent of Identity design: an EP-01 plan for the widened P-I10 privacy fix (§5), under `PBDIGIT-341`.

**Mobile:** under (d), a member's whole first contact can happen on a phone: tap NRNA's link, create an account, done. That is the precondition for inviting members to install the PWA.

## 11. Proposed corrections (for approval; not applied)
| Doc | Place | Correction |
|---|---|---|
| B | P-I1 | add option (d); change REC from (c) to (d); add §3.3's costs |
| B | P-I7 | "disappears under (d)" |
| B | P-I9; A §5 last bullet | replace the "unknown" rule with §6's fail-safe rule |
| B | P-I10 | replace "small fix" with §5's assessment (three steps); widen `PBDIGIT-341` |
| C | §2 | delete E-3; E-2 folded into E-1 |
| C | §3 | replace with §7's classification |
| A | §6 | under (d) the Person design changes (S-5/S-6 then apply only to (a)/(c)) |

## 12. Limitations and unresolved questions
- **Not independently reviewed.** Option (d) is new in this review; it needs outside scrutiny before it becomes architecture.
- **No tests run.** The import behaviour is read from code; the "silent enrolment of a verified existing account" conclusion is from `:314-332`, not from a run.
- **Unknown:**
  - whether real NRNA data contains shared family e-mail addresses (it affects how often the election-only cross-linking has already happened);
  - whether `current_ip` is updated by activity in other organisations' pages (likely, since it is per account; not traced to its writer here).
- The Organisation baseline v1.0 is **P** and untracked; it is used only as consistent context.
