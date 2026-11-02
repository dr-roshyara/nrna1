# 10: Membership in business terms: what we are building, what exists, what comes next

**Read this first** if you are new to Membership. Guides 01–09 explain individual implementation steps. This guide explains the business system they belong to.

**Status of this guide:** documentation of the state on 2026-11-02 (HEAD `620e90c3a`). It decides nothing. Every statement carries one of these labels:

| Label | Meaning |
|---|---|
| **RATIFIED** | formally established architecture (2026-10-08) |
| **DECIDED** | a recorded PO decision |
| **IMPLEMENTED** | the code exists (file cited) |
| **TESTED / ACCEPTED** | tests ran (the record is cited) / the PO accepted the increment |
| **PARTIALLY IMPLEMENTED** | some, but not all, of the capability exists |
| **OPEN** | not yet decided |
| **PROPOSED** | suggested, not approved |
| **INFERRED** | concluded, not directly verified |

**Sources:**
- ratified architecture: `docs/publicdigit/reviews/2026-10-08-membership-architecture-ratified.md` and the act `…-ratification-act.md`;
- PO decisions D-1…D-5: `docs/publicdigit/reviews/2026-10-09-membership-slice1b-acceptance-review-and-slice2-decision-closure.md:290-296`;
- the adopted delivery roadmap (W0–W9): `docs/publicdigit/membership/architecture/brainstorming/cross_domain_brainstorming/20261016_2219_membership_architecture_verification_and_completion_plan.md`;
- the status report: `docs/publicdigit/reviews/2026-11-02-membership-ddd-status.md`;
- the foundation audit: `docs/publicdigit/reviews/2026-11-02-foundation-boundary-and-ubiquitous-language-audit.md`.

---

## A. What PublicDigit is building, and why the concepts must stay apart

PublicDigit lets organisations (associations, parties, clubs) manage their people and run elections. For every person it must answer several **different** questions:

| Question | Who answers it |
|---|---|
| Who is this human? | **Identity** (the Person) |
| How do they sign in? | **Identity** (the Account) |
| Which organisations may they work in, and in which roles (owner, admin, staff, guest)? | **Organisation** (Affiliation) |
| Are they a member of this organisation, since when, of which type, and is that membership in force? | **Membership** |
| Have the dues been paid and settled? | **Finance** |
| Who may decide, and which committee holds which office? | **Governance** |
| May they vote in *this* election, *now*? | **Election** |

**What goes wrong when these are confused:**

| Confusion | Consequence |
|---|---|
| Registering on the platform treated as joining an organisation | everyone who signs up becomes a "member" |
| A paid membership treated as a vote | Election rules (deadlines, admission requirements) are bypassed |
| Membership history overwritten instead of recorded | a dispute about who was a member on polling day cannot be settled |

The rule behind every boundary below is **no context decides for another**.

## B. The business model we have established

| Rule in business language | Status | Source |
|---|---|---|
| **Identity owns the person.** Membership refers to a person only through a reference issued by Identity, never through a login account. | RATIFIED | ratified §1, §8 |
| **Membership is the lasting relationship** between one person and one organisation. There is exactly one per person and organisation. | RATIFIED | ratified §1, INV-M |
| **A membership consists of periods.** A period is one episode, from admission to ending. Someone who leaves and returns gets a new period. (Whether re-entry is the *same* membership is BD-1, recommended "yes", OPEN.) | RATIFIED (periods); OPEN (BD-1) | ratified §1, §11 |
| **Admission opens a period.** Every admission needs exactly one recognised basis: the person's declaration (an approved application), a statutory rule, or attested history (migration only). | RATIFIED / DECIDED (D-1) | ratified §1; D-1 |
| **No "pending member".** An application stays outside Membership; when it is approved, an admission is recorded that refers to the application. | DECIDED (D-2) | D-2 |
| **Ending is a closed list of acts:** resignation, exclusion, death (plus `legacy` for migrated history). Non-payment is a *reason* for exclusion, not an ending by itself. | DECIDED (D-3) | D-3 |
| **Lapsed is not ended.** If a membership term runs out without renewal, the person is *lapsed*: still in the relationship, but not in force. They can renew, and the gap stays visible. | RATIFIED | ratified §4, INV-3, INV-12 |
| **Suspended is still a member**, with a restriction. | RATIFIED (predicate); meaning in statutes OPEN (BD-SUSP) | ratified §4 |
| **History is recorded, never overwritten.** Every change is a new fact with three dates: when it takes effect, when it was decided, when it was recorded. The current state is *calculated* from the facts. | RATIFIED | ratified §2, §3; INV-6/14 |
| **Mistakes are corrected openly.** *Annul* sets a valid decision aside (e.g. a won appeal). *Void* says a record never matched reality. *Correct* fixes a wrongly recorded value. Nothing is deleted. | RATIFIED | ratified §2, §9 |
| **Backdating is allowed** within the rules; future-dated admissions and endings are refused. | DECIDED (D-5) | D-5(b) |
| **Affiliation is not Membership.** Being an owner, admin, staff, guest or the "User" of the platform's default organisation does not make anyone a member. | RATIFIED (EM-VOC-002); DECIDED (BR-2, BR-3) | ratified §1; business rules 2026-11-01 |
| **Membership never grants a vote.** Election decides eligibility, fresh at each attempt, from its own rules; it may *read* membership facts. | RATIFIED (INV-11); DECIDED (ADR_20261004_1549) | ratified §4, §12 |
| **Finance owns payments.** Membership *derives* "good standing" (dues compliance) from Finance's payment facts, and tells Finance when a period was cut back or restored (`PeriodTrimmed`, `TrimRevived`). | RATIFIED | ratified §4, §8 |
| **Governance owns authority and committees.** Membership stores *evidence* of who decided and on what authority; it does not decide who has authority. Committee participation is not part of Membership. | RATIFIED | act §10, §11 |
| **No shared persistence** between contexts. | RATIFIED | act §11; verdict `:46` |

These rules are binding. Missing implementation work does **not** reopen them.

## C. What we have developed so far

Two Membership implementations exist side by side:
- **The new Membership kernel** (`app/Contexts/Membership/Domain/Organisational/`, plus `Application/Organisational/` and `Infrastructure/Organisational/`). It implements the ratified model.
- **The legacy implementation** (`app/Models/Member.php`, `MembershipType`, `membership_fees`, `membership_applications`, and the guides under `developer_guide/membership_contexts/`). **It still runs production.**

**The new kernel is not yet connected to the application:** no container binding, no caller, no route or screen (IMPLEMENTED: absent; `2026-11-02-membership-ddd-status.md` §5).

| Capability | What it lets the business do | Status | Evidence |
|---|---|---|---|
| Admit a person (open a period) | record a membership start with its basis and authority evidence | **IMPLEMENTED; ACCEPTED** (Slice 0) | `Member::decideAdmit`; guide 01; PO acceptance 2026-11-02 |
| End a period | record resignation, exclusion, death | **IMPLEMENTED in the kernel; still refused for every actor in the code.** GOV-END was decided on 2026-11-02 (B1, interim): owner/admin may record resignation and death with evidence; exclusion stays refused until Governance's competence check exists. The code change belongs to Slice 2b | `Member::decideEnd`; `InterimRoleAuthority.php:18-19` |
| Calculate the current state at any date, and "what we knew on date X" | answer "was this person a member on 3 May?" reproducibly | **IMPLEMENTED; ACCEPTED** (Slice 0) | `Resolution.php`; `Member::knownAt` |
| Protect the core rules (no overlapping periods; a period is one unbroken episode; corrections never silently remove membership) | the history can never become self-contradictory | **IMPLEMENTED; ACCEPTED** (Slice 0) | `InvariantSet.php`; `RefusalReason` cases |
| Safe concurrent edits | two administrators acting at once cannot break the rules | **IMPLEMENTED; ACCEPTED** (Slice 1a) | guide 02 |
| Store memberships in PostgreSQL | keep the history durably | **IMPLEMENTED; only conditionally accepted**; never run on an isolated database | guide 03; ARB-MEM-02; ARB-SEC-01 |
| Admit/End command rules (basis, dating, ending kinds) | enforce D-1, D-3, D-5 at the entry point | **IMPLEMENTED; in review, not accepted** | guide 08; `PBDIGIT-271` |
| Annul and Void (kernel only) | set aside or void a recorded fact | **PARTIALLY IMPLEMENTED:** decided in the kernel, **not stored**. Kernel **ACCEPTED with explicit residual risks** (C-1 G3, PO, 2026-11-02) | guides 04, 05, 09 (V-2 fix `115e031de`); `PBDIGIT-268` |
| Guard the Membership–Election boundary | stop *new* code from making Membership decide votes, or Election reach into Membership's tables | **IMPLEMENTED; ACCEPTED** (S1b); existing violations are baselined, not removed | `developer_guide/membership_election_boundary/02_s1b_hardened_boundary_guards.md`; `tests/Architecture/MembershipElectionBoundaryTest.php` |

**Test evidence, as recorded:**
- 188 database-free unit tests and 51 architecture tests passed on 2026-11-02 (`2026-11-02-membership-ddd-status.md` §3).
- That run used the shared working tree. **The clean-copy re-run that roadmap W0 requires is still outstanding** (roadmap R-6).
- The PostgreSQL feature tests have **not** been run: there is no isolated test database yet.

## D. Current limitations and gaps

| Gap | Kind | Business consequence | Where it is handled |
|---|---|---|---|
| Renew, Suspend/Restore, ChangeType are not built | missing capability | the new kernel cannot yet show "lapsed", "suspended" or a type change | roadmap W7 (Slices 4, 5) |
| Correct is not built; Annul/Void are not stored | missing capability | errors cannot yet be fixed end to end. **No cutover or live write before correction is complete** (DECIDED, ARB-MEM-01) | roadmap W4 (COR-2 → COR-4 → COR-3) |
| Who may end a membership | **decided (GOV-END B1, interim, 2026-11-02)**; exclusion authority still open (DS-4, D-3-L) | ending through the new kernel stays impossible until Slice 2b; exclusion stays impossible until Governance provides the competence check | roadmap W5 (Slice 2b); Governance (DS-4) |
| Membership types, obligations, dues and "good standing" | missing capability. The business rules are **DECIDED** (E0b-S E-1…E-5, 2026-11-02): Membership owns the dues obligation; Finance owns payments; Standing = every obligation due is paid, waived or exempted, within a grace period | the new kernel has no type, obligation or Standing model yet | roadmap W7 (types, ChangeType) and W8 (Finance) |
| Legacy: "expired" mixes lapsed and ended; rows updated in place | confirmed deviation | history and the lapsed/ended distinction are lost in production data | migration W9 (never invent truth, BR-18) |
| Legacy: membership logic answers voting questions (`Member::canVoteInElection`; the `grants_voting_rights` type flag; Election reads Membership tables) | confirmed deviation (baselined V1, V1b, V2, V3′) | membership can silently act as a vote | `PBDIGIT-318` S2–S4 (roadmap W8); BD-GVR open |
| Legacy: Membership marks fees "paid" and tells Finance | confirmed deviation (direction reversed) | payment truth sits in the wrong place | Finance integration (W8); direction to be decided |
| Legacy: members reachable only through a login account | confirmed deviation | people without an account cannot be members in the legacy model | Identity/affiliation questions; migration |
| Committee code inside the Membership folder | confirmed deviation (act note N-6) | Membership appears to own committee seats | DR-07a (Governance ownership), OPEN |
| How the person reference is produced; where affiliation is stored; the Election contract; physical persistence | open integration decisions (K3/K4, BR-9, OQ-ME-4, D-1/Q-new-1) | affect only Membership's edges, **not** its core | foundation audit §6–§7 |
| Production state of the kernel tables (OQ-01) | evidence needed | unknown whether the migration already ran in production | W2 (read-only check, needs authorisation) |

**Still-open Membership business policies:** BD-1, BD-2, BD-SUSP, BD-TERM4, BD-TEN, BD-NU, BD-NORM. *(BD-TYPE and BD-GVR were answered on 2026-11-02 by E0b-S E-3 and E-4: `docs/publicdigit/reviews/2026-11-02-membership-e0b-s-decision-record.md`. BD-REV-ER was decided "refuse" on 2026-11-02: `docs/publicdigit/reviews/2026-11-02-membership-bd-rev-er-decision-record.md`.)*
- Their questions and recommended answers are in `2026-10-08-membership-final-decision-register.md:36-48`.
- **They are not decided by this guide.**
- None of the remaining ones blocks a slice. BD-REV-ER ("refuse") and D-COR-3 ("dates only in the first Correct slice"), which blocked COR-2, were decided on 2026-11-02. COR-2 still needs C-1 accepted and its own authorisation.

## E. What we plan to do next

**The delivery plan is the ADOPTED roadmap W0–W9** (PO, 2026-11-02, with corrections R-1…R-6):

```
W0 evidence and verdicts → W1 decision packages → W2 PostgreSQL foundation → W3 kernel hardening
→ W4 correction (COR-2, COR-4, COR-3) → W5 Slice 2b (wiring, ending authority) → W6 events and queries
→ W7 Renew / ChangeType, Suspend / Restore, numbering, application → Admit, Governance port
→ W8 Election, Finance, Audit integration (incl. PBDIGIT-318) → W9 migration, cutover, UI, retirement
```

The five steps proposed on 2026-11-02 map onto it as follows. **Only the roadmap is adopted. Steps 1 and 2 are PROPOSED additions.**

| Step | Objective | Roadmap stage | Depends on | Done when |
|---|---|---|---|---|
| 1 Document the current state | make the business model and status readable | (documentation) | — | this guide exists and is kept current |
| 2 Membership discovery: types, obligations, dues, Standing (E0b-S) | define what a type obliges, which Finance facts Membership needs, how "good standing" is derived, and how a type classification avoids granting a vote | not a named stage; feeds W7 and W8 | ratified boundary only; no Finance direction needed | a business-rule document with evidence and open questions (BD-TYPE, BD-GVR, DS-1, DS-5). **Done 2026-11-02:** discovery report and decision record E-1…E-5; DS-1 stays with Finance |
| 3 Complete the lifecycle | Renew, Suspend/Restore, ChangeType, Correct, stored Annul/Void | W4, W7 | W0–W2; C-1 accepted (BD-REV-ER and D-COR-3 decided) | every fact kind is decidable, storable and tested; the lapsed/ended distinction is visible |
| 4 Integration boundaries | Identity, affiliation, Election, Finance, Governance | W5, W6, W8 | DS-4 (exclusion authority); OQ-ME-4; fee direction; K3/K4 | published contracts; baselined violations removed |
| 5 Legacy alignment | migrate without inventing truth | W9 | ARB-MEM-01; CUT-1…CUT-10; OQ-01 | parity evidenced; legacy retired |

**What is authorised now:** nothing beyond the recorded authorisations. Each stage still needs its own authorisation.

## F. Progress table

| Business capability | Intended outcome | Status | Evidence | Remaining work | Dependency | Completion criterion |
|---|---|---|---|---|---|---|
| Membership core model | one consistent history per person and organisation | RATIFIED; IMPLEMENTED; ACCEPTED (Slices 0, 1a) | guides 01, 02 | clean-copy evidence (W0) | — | W0 evidence stored |
| Admission | members admitted with a basis and authority evidence | IMPLEMENTED (kernel + 2a rules); 2a not accepted | guide 08 | acceptance; wiring (W5) | Governance port | 2a accepted; reachable from the application |
| Ending | resignation, exclusion, death recorded | PARTIALLY IMPLEMENTED (refused for all actors); rule DECIDED (GOV-END B1) | `InterimRoleAuthority.php`; GOV-END decision record | Slice 2b (port carries End kind and evidence) | W2; DS-4 for exclusion | owner/admin can record resignation and death; exclusion refused with a distinct reason |
| Lapse and renewal | lapsed ≠ ended; renewals keep the gap | RATIFIED; not implemented | ratified §4 | Slice 4 (W7) | — | Renew stored and tested |
| Suspension | restriction without loss of membership | RATIFIED; not implemented | ratified §4 | Slice 5 (W7) | BD-SUSP | Suspend/Restore stored and tested |
| Corrections | errors fixed without deleting history | PARTIALLY IMPLEMENTED (Annul/Void kernel only) | guides 04, 05, 09 | COR-2…COR-4 (C-1 accepted 2026-11-02; COR-2's decisions taken: BD-REV-ER refuse, D-COR-3 dates only) | COR-2 story and authorisation; COR-4 needs W2 | ARB-MEM-01 met |
| Types, obligations, Standing | dues compliance derived from Finance facts; types classify, never grant votes | **DECIDED** (E-1…E-5); not implemented | E0b-S decision record 2026-11-02 | type model, obligations, Standing in the kernel (W7, W8) | DS-1 (Finance); Finance contract (W8) | Standing computed from stored obligations and Finance payment facts, tested |
| Membership–Election boundary | no Membership vote logic; no direct reads | IMPLEMENTED (guards, S1b ACCEPTED); existing violations baselined | election-boundary guide 02 | S2–S4 | OQ-ME-1…4 | baseline counts reach zero |
| Persistence on PostgreSQL | durable, isolated storage | IMPLEMENTED; conditionally accepted only | guide 03 | isolated database; Part 2 | P-DB (W2) | suite green on an isolated database |
| Legacy cutover | one Membership implementation | OPEN | CUT-1…CUT-10 | W9 | ARB-MEM-01, OQ-01 | legacy retired |

## G. Glossary (business meaning first)

The wider vocabulary, including the Observer, Administrator and Role splits, is in the foundation audit §4.

| Term | Business meaning | Owner |
|---|---|---|
| Person | a human being | Identity |
| Account | how a person signs in; not the person | Identity |
| Affiliation | access and roles in an organisation (owner, admin, staff, guest, the platform "User") | Organisation |
| Membership (code: `Member` aggregate) | the lasting relationship between a person and an organisation | Membership |
| Period | one episode of membership, from admission to ending | Membership |
| Admission (Admit) | the act that opens a period, with one basis | Membership |
| Application | a request to join; stays outside Membership until approved (D-2) | Membership process |
| In force / Lapsed | within a paid-up term / still a member but the term has run out | Membership |
| Suspended | still a member, under a restriction | Membership |
| Ended (End) | the period closed by resignation, exclusion or death | Membership |
| Annul / Void / Correct | set a decision aside / declare a record never true / fix a wrong value | Membership |
| Membership type | the class of membership during part of a period | Membership |
| Standing | whether dues obligations are met, derived from Finance's payment facts | Membership (derives) |
| Payment, settlement | money received and settled | Finance |
| Voting entitlement | whether a person may vote in an election now | Election |
| Authority evidence | who decided, in what capacity, under which rule | Governance rule; Membership stores the evidence |

## Traceability

- Ratified: `2026-10-08-membership-architecture-ratified.md` §1–§12; act §10–§14.
- Decided: D-1…D-5 (`2026-10-09-…-slice2-decision-closure.md:290-296`); ARB-MEM-01, ARB-SEC-01, ARB-MEM-02 (`2026-10-09-membership-human-arb-decision-record.md`); Slice 0 and Slice 1a acceptance (PO, 2026-11-02); roadmap adoption (PO, 2026-11-02).
- Status: `2026-11-02-membership-ddd-status.md`.
- Foundation: `2026-11-02-foundation-boundary-and-ubiquitous-language-audit.md`.
- Anonymity: this guide contains no voter-to-vote linkage (ADR-T11).
