# PublicDigit strategic baseline: Principal decision paper

| | |
|---|---|
| **Status** | **Decision paper.** Records no ruling; changes no decision record, baseline, code, backlog or plan. Written by Claude, **not independent**. |
| **Commission** | "Final Strategic Baseline Closure and Principal Decision Preparation", Principal, 2026-11-10. |
| **Supersedes (wording only)** | where this paper differs from `./2026-11-10-cross-context-strategic-baseline-closure-decision-dossier.md` and `./2026-11-10-strategic-dossier-final-challenge-report.md`, this paper governs. Those documents are not edited. |
| **Sources (current working tree)** | the dossier and challenge report; IR-OPR-01; people-register design rev. 3 (latest; rev. 4 not yet written); CR-01 (C0–C4); ratified Membership architecture §8; context map; decision record D-41…D-48, D-65, D-71, D-73, D-75, D-77, D-79, D-97, D-101, D-103, D-104, D-107, D-109, D-111, D-113, D-114, D-118…D-122; Election brief and addenda; code: `routes/election/electionRoutes.php:30-49`, `app/Services/VoterImportService.php:268-278` |
| **Placement** | `php scripts/doc-placement.php --scope=product-specific --maturity=research --domain=publicdigit` → `docs/publicdigit` (exit 0) |

**Labels:** **FACT** · **RAT** · **WD** · **APP** · **P** · **REC** · **UNKNOWN** · **PRINCIPAL**.

## Disagreements and precisions (before the paper)
1. **R-ID should be replaced, not only clarified.** I agree with the note's challenge. The wording I proposed mixes up two questions: *is this person entitled?* (Election, on the organisation's entry) and *through which channel may they receive the right?* (the rules for that election).
   - **FACT:** today's voters already vote without a claim. Voting requires a signed-in, verified account (`electionRoutes.php:30-49`). The election-only import **creates that account itself**, with a random password and e-mail marked verified (`VoterImportService.php:268-278`). So proof of identity today is control of the mailbox.
   - That path violates D-107 and D-113. Adopting strict R-ID would silently **change who can vote in practice**.
   - The replacement is R-ID′ (Decision 1).
2. **The Finance → Membership relationship is not entirely missing.** The ratified outline exists: "fees reference periods; settlement is Finance's" (RAT, context map R-05). What is unregistered is **the direction of the FeePaid fact** (act N-5, "a reading, not a decision"). The decision is narrower than "add a contract".
3. **One limit the note implies, stated plainly:** a human who voted twice through two undetected Persons **cannot be found or corrected afterwards**, because votes carry no identity (VO-1). Detection must happen before issuance, or not at all.

## Part 1. Executive summary
- **Established:**
  - seven bounded contexts with clear ownership;
  - timing rules (effective time and knowledge time, cut-off snapshots);
  - ballot anonymity;
  - the rule that no context changes another's facts.
  - **No new bounded context is justified.**
- **Undecided:** eight Principal decisions (Part 3). The most important is **how people without a PublicDigit account can vote** (Decision 1).
- **Strategic discovery:** complete.
- **Ready for tactical DDD:** **not yet.** The decisions in Part 3 must be ruled and the baseline consolidated first.

## Part 2. Established architecture
| Context | Owns | Status |
|---|---|---|
| Identity | canonical Person, Account (0..1 per Person), login, claims, `PersonRef` issue and binding, merges | RAT XD-3; WD D-75, D-111, D-113 |
| Organisation | its register entries, affiliations (incl. standing voter relationship), units and belonging, workspace access; **electoral divisions (proposed)** | WD D-101, D-104, D-109, D-114…D-122; divisions P |
| Membership | membership facts, coverage, lifecycle | RAT; CR-01 (D-73, D-77) |
| Governance | written rules, organs, positions, decisions, competence | WD D-38, D-65, D-97 |
| Election | electorate, eligibility, entitlement, post districts, candidacy evaluation, right issuance | RAT boundary; WD D-41…D-48 |
| Voting | ballots, consumed credentials, anonymity | RAT VO-1; WD D-45 |
| Finance | payments, settlement | RAT (outline) |

**Concepts kept separate:**
- Membership;
- affiliation;
- unit belonging;
- electoral division;
- workspace access;
- Governance authority;
- election entitlement;
- the channel through which a right is exercised (new, Decision 1).

**Temporal principle (WD/RAT):**
- every cross-context read is "**in force at asOf, as known at τ**" (CR-01 §2; D-73);
- Election reads at cut-off T with τ = T;
- later facts change no fixed snapshot (D-42);
- a pending restructuring blocks (D-41, D-43);
- corrections to held elections go through Contestation (D-71).

**Established invariants:**

| Invariant | Status |
|---|---|
| a vote is not linkable to its voter | RAT VO-1 |
| registration creates only an Account; an Account never creates a record or affiliation | WD D-107 |
| binding only by valid claim; possession of a reference proves nothing; at most one of an organisation's references bound per Person | WD D-113 |
| an address never creates belonging or entitlement | WD D-79, D-80, D-82 |
| organisations' data isolated | WD D-111 |
| Organisation never interprets Membership; Membership never reads belonging | RAT CR-01 |
| every race result published; breakdowns only above the organisation's minimum | WD D-48 |
| Level A never presented as Level B | WD D-45 |

## Part 3. Principal decisions

### Decision 1 (R-ID′). Who can receive a voting right, and through which channel?
- **Question:** must every voter have a registered, claimed PublicDigit identity, or may an organisation authorise another way to receive and use a voting right?
- **Why it matters:**
  - parties have members without smartphones or e-mail (older members, rural branches);
  - excluding them because they lack an account would make **platform registration** a voting condition that no constitution set;
  - but every channel without a claim is weaker against duplicates and impersonation.
- **Facts:**
  - today: voting needs a verified account, and the import creates accounts (see disagreement 1);
  - D-113: a Person exists only after a claim;
  - §22 (P): rights are issued by blind credential, one per person, recorded as "right issued".
- **Six things kept apart:**
  1. entitlement (Election, on the organisation's entry);
  2. canonical Person (Identity);
  3. Account (Identity);
  4. claim (Identity, D-113);
  5. issuing an online right (Election);
  6. using a right through another authorised channel.
- **Options:**
  - **A (strict):** a right is issued only to a Person bound by claim. Voters without accounts are entitled but cannot vote.
  - **B (channels):**
    - entitlement is decided independently of accounts;
    - a right is issued through a **channel the organisation's rules authorise for that election**: by default online through a claimed Person; additionally, where authorised, a one-time credential delivered to a contact the organisation has verified, or in-person issuance by the election committee;
    - **each electorate entry receives at most one right across all channels.**

| | A | B |
|---|---|---|
| no-account members | cannot vote online | can vote if the organisation authorises a channel |
| duplicate protection | one right per Person; known duplicate pairs handled (Decision 2) | per **entry**; a claimed entry and an unclaimed entry of the same human are not linked by Identity, so **the organisation's own duplicate checks are the only control** |
| impersonation | login plus claim | only as strong as the organisation's contact verification |
| honesty | — | PublicDigit states per election which guarantee applies |

- **REC: B.**
  - Default when the rules are silent: **online through a claimed Person only**.
  - Before opening, Election shows the election committee **how many entitled entries have no claimed Person**. The competent body then decides whether to authorise another channel, so **nobody is excluded silently**.
  - The published result includes "entitled, no right issued".
  - **Rejects A** because it makes platform registration a voting condition.
- **Effects:**
  - **snapshot:** it records entitlement per entry, not per Person;
  - **issuance:** it checks "at most one right per entry", plus per Person where claimed;
  - **claims:** organisations should send claim codes early, because claims must be complete before issuance closes;
  - **membership app:** it requires a claim (it is the claimed channel);
  - **identity verification:** the alternative channel relies on the organisation's verified contact, recorded as the basis.
- **Postal or paper voting:** **out of scope (UNKNOWN).** PublicDigit counts no paper ballots, and combining external results is not designed.
- **Impact:** an **invariant** (one right per entry across channels; channel per the rules) and a **contract** (Identity → Election binding, Part 4).

### Decision 2 (P4). Two of an organisation's records turn out to be one human
- **Facts:**
  - only an Identity merge of two Persons creates this (D-113; IR-OPR-01 F-7);
  - Identity owns sameness (D-75);
  - the organisation owns its records (D-101).

| Case | REC outcome | Who acts |
|---|---|---|
| same races | one right; both entries kept as one case | Election |
| different races | **that person's whole right held** until the organisation names the governing record; then released. A single sealed submission per voter (§22, P) rules out issuing part now and part later | organisation decides; Election holds and releases |
| one entry archived | not in the electorate (Decision 6): no duplicate | — |
| one entry pending | not a voter (Decision 8): no duplicate | — |
| merge disputed | Identity's answer at issuance stands; a reversal after the vote goes to Contestation | Identity |
| merge after T, before issuance | Election applies the merge at issuance: one right | Election |
| merge after both rights were used | **cannot be detected in results or corrected** (VO-1); recorded; Contestation | — |
| unresolved at T | snapshot fixed with the case open; no election block | Election |
| one human, two Persons, never merged | undetectable | residual risk |
| (under Decision 1 B) one claimed entry plus one unclaimed entry | undetectable by Identity; organisation's duplicate checks only | residual risk |

- **What PublicDigit guarantees (recommended wording):**
  > "At most one right per electorate entry, and per canonical Person where entries are claimed; duplicate Persons are handled once Identity has merged them. **PublicDigit does not guarantee one right per human while duplicates remain undetected.**"
- **Evidence kept:** both entries, the merge notice, the case state at T, the hold and its release. **Published:** the number of held rights.
- **PRINCIPAL:** accept the table, the guarantee wording, and the organisation's authority to merge its own records under its rules (F-8).
- **Impact:** **invariant**; no ownership change.

### Decision 3 (E-3′). Electoral divisions
- **Question:** may an organisation keep electoral divisions separate from its units?
- **REC wording:**
  > "An organisation may define electoral division schemes and assign the people it records to divisions, by individual act, authorised import or adopted residence rule (never by an address alone). Assignments have effective periods and a recorded basis, and grant no organisational belonging, rights, forum, audience or authority. Election reads them as at its cut-off, as known then. A person without an assignment has no regional race from that scheme unless the rules provide a fallback, which is then an assignment by the organisation."
- **Part (b), several assignments in one scheme:**
  - **REC: refused unless the written rules allow it.** The conflict is caught when the organisation can fix it. Where the rules allow it, Decision 4 governs at election time.
  - **Alternative:** allow it always and rely on Decision 4 holds. This pushes conflicts to election time.
- **Why not "because D-118 says so":** a scheme divides voters among **alternative** constituencies of one organ. Two assignments in one scheme are double representation by definition.
- **Impact:** **new concept** (Organisation); **new contract** (Part 4).

### Decision 4 (E-4′). Overlapping constituencies
- **REC wording:**
  > "A genuine overlap exists only where a voter qualifies for races that fill seats of the same organ through alternative constituencies and the written rules do not say whether that is allowed. Nested districts and races for different organs are not overlaps. The affected races are not opened until the authority the rules name decides (if the rules name none, D-65 applies); other races proceed if the election committee so decides."
- **Why unaffected races may continue:** their electorates are complete and unaffected.
- **Impact:** **rule.**

### Decision 5 (E-5′). Ballot secrecy
| Layer | Nature |
|---|---|
| stored-data unlinkability | **technical guarantee** (VO-1, Level A) |
| secrecy while voting (observation, coercion, especially on phones) | **not guaranteed** remotely; Level B later (D-45) |
| inference from results | **risk control** (organisation minimum; D-48 breakdown rules) |
| PublicDigit's claims | **obligation:** never more than holds |
| constitutional requirement | **the organisation's obligation** under its written rules |

- **REC wording:**
  > "PublicDigit describes each race's secrecy layer by layer, never as more than holds. A race below the organisation's minimum opens only on a recorded decision of the authority the written rules name; no operational decision overrides a written secret-ballot requirement. No platform-wide number applies."
- **Impact:** **invariant.**

### Decision 6 (P2). Archiving
- **Five separate acts:**
  - archive the entry;
  - end an affiliation;
  - withdraw access;
  - end Membership (Membership only);
  - retire a `PersonRef` (Identity only).
- **Archiving never does the last two.**

| | M1: archive only when no current relationship (the reflected "Member" capacity counts, D-103) | M2: archive allowed while membership continues |
|---|---|---|
| meaning of "archived" | "we have no current relationship" | "hidden from working lists" |
| formal notices to members | contact record stays current | may fail: the record is filed away while rights continue |
| history | kept | kept |

- **REC: M1.** Unarchiving is allowed, forward-only.
- **Impact:** Organisation **invariant.**

### Decision 7 (P3). Capacities
- **REC:**
  - Worker and Guest may overlap, each with its own description;
  - **at most one active standing voter relationship per register entry**;
  - unit capacities, offices (Governance) and workspace access (D-104) are separate facts;
  - **no capacity implies another**;
  - **no employment model**;
  - the rules may add limits.
- **Impact:** **rule.**

### Decision 8 (P5, P6). Voter relationship and imports
- **P5:** a standing voter relationship for election-only organisations. It is not Membership, not entitlement, and does not carry the division.
- **P6:** the stages are never merged:
  - entry recorded;
  - reference issued;
  - claimed;
  - voter relationship confirmed;
  - entitled at T;
  - right issued (by channel, Decision 1).
- **Import rules:**
  - pending is never eligible;
  - the row key is the organisation's identifier, otherwise file plus row as an **occurrence only**;
  - re-import applies corrections with the import as basis;
  - **omission never archives**;
  - **an import never creates an Account** (D-107; today's code does, P7).
- **Impact:** **confirms boundaries**; P6 is a rule.

### Decision 9 (G-1). Fees and voting
- **REC: support both arrangements, per the written rules.**
  - **A:** arrears change status by Membership's own act, on Finance evidence; Election uses C4.
  - **B:** membership continues; Election obtains a Finance settlement fact for the rule's fee period, as known at T, and applies the rule.
  - **No payment predicate in C4.**
  - If the fact is disputed and the rules are silent, the affected right is held, never assumed.
- **Separate ARB act (not needed for B):** the FeePaid direction between Finance and Membership, required for A.
- **Impact:** **contract** (Finance → Election); A depends on the ARB act.

### Confirmation only (no new ruling)
**P7(a):** D-107 and D-113 already prohibit silent e-mail linking and account creation by import.
- **FACT:** the legacy import contradicts them (`VoterImportService.php:268-278`; PBDIGIT-341).
- **P7(b):** the people register's first capability goes live only after containment. This is a **release condition**, not a domain concept.

## Part 4. Cross-context contract register
| Contract | Owner → consumer | Fact or query (business terms) | Time | Exists? | Open |
|---|---|---|---|---|---|
| electoral division assignments | Organisation → Election | assignments of scheme S | asOf, τ | **to add** (Decision 3) | — |
| voter relationships | Organisation → Election | standing voter relationships with categories | asOf, τ | **proposed** (T-8, P) | — |
| unit belonging | Organisation → Election | effective members of a unit or below | asOf, τ | WD (R-5, D-32) | — |
| membership predicates | Membership → Election | IsMember, Suspended, CurrentType, tenure | asOf, τ | **RAT** (C4) | conformance of today's code (`PBDIGIT-318`) |
| payment evidence | Finance → Membership | settlement of a fee period | asOf, τ | outline RAT; **direction unregistered** | separate ARB act (Decision 9 A) |
| settlement fact | Finance → Election | settlement status of fee period P for reference R | asOf, τ | **to add** (Decision 9 B) | — |
| claim and binding | Identity → Organisation | reference bound to a Person; merge notices for its own references | event / τ | WD (D-113; C-5) | merge-notice details tactical |
| binding at issuance | Identity → Election | is this signed-in Person bound to this entry's reference? | at issuance | **to add** (Decision 1) | — |
| election rules | Governance → Election | rules in force, voter and candidacy criteria, channels authorised | asOf, τ | WD (R-6a) | "channels" is new content (Decision 1) |

No names above are approved interface names.

## Part 5. Strategic invariants needing approval
| Invariant | Decision |
|---|---|
| at most one right per electorate entry across channels, and per Person where claimed; channels per the rules | 1 |
| PublicDigit does not claim one right per human while duplicates are undetected | 2 |
| a known duplicate pair is one voter; different races hold that right | 2 |
| a division grants no organisational rights; never assigned by address alone | 3 |
| only genuine overlaps hold races, and only the affected ones | 4 |
| secrecy described per layer; no operational override of written rules | 5 |
| "archived" only with no current relationship; never touches Membership or reference | 6 |
| no capacity implies another; one standing voter relationship per entry | 7 |
| pending is never eligible; omission never archives; import never creates an Account | 8 |
| a disputed payment fact is never assumed | 9 |
| one fixed electorate decision used for display, preview, issuance and validation | E-2 (accept) |
| a central account field never decides regional voting | E-1 (accept) |

## Part 6. Organisation-specific and UNKNOWN (not blocking)
- NRNA's constitution: districts, division schemes, candidacy timing (E-6′ default nomination cut-off), arrears arrangement (A/B/none), secret-ballot wording.
- Per organisation: secrecy minimum; authorised voting channels; who certifies rolls.
- Real data: `region`/`state_name` mismatches; district sizes.
- Postal or paper voting; erasure (XD-PROV).

## Part 7. Tactical DDD entry conditions
1. Decisions 1–9 ruled; E-1, E-2 and E-6′ accepted; P7(a) confirmed.
2. The separate ARB act on the FeePaid direction, **or** an explicit note that arrangement A waits for it.
3. A consolidated strategic baseline recording the above, with Part 6 kept as open.
4. Advisable: one independent (non-Claude) review of the consolidated baseline.

## Compact decision register
| Group | Items |
|---|---|
| **Already decided: confirm only** | P7(a) (D-107, D-113); the CR-01 time rules; D-41…D-48; VO-1 |
| **Principal decision required** | 1 R-ID′ · 2 P4 · 3 E-3′ (a, b) · 4 E-4′ · 5 E-5′ · 6 P2 · 7 P3 · 8 P5/P6 · 9 G-1 · plus accept E-1, E-2, E-6′ |
| **Separate ARB act** | FeePaid direction, Finance and Membership |
| **Organisation-specific UNKNOWN** | Part 6 |
| **Operational** | P7(b) containment; C4 conformance (`PBDIGIT-318`); `users.region` replacement (needs permission) |
| **Later tactical detail** | snapshot structure; credential mechanics per channel; held-right release mechanics; division scheme model; number thresholds |

**Recommended order:** 1 → 2 → 3 → 4 → 5 → 6 → 7 → 8 → 9. Decision 1 first, because it shapes issuance, duplicates and the app.

## Mobile and installable app (PWA)
- **Decision 1 decides who the phone app serves.**
  - Under B, the installable membership area is the **claimed channel**. Claiming one's record is the first screen after login.
  - Members without accounts are not forced into the app; their organisation may authorise another channel.
- **Installable membership area (stage 2), per organisation and isolated:**
  - membership status;
  - unit and division with "as of" dates;
  - after T, races or "held" with the reason;
  - under arrangement B, one's own fee-period status.
  - Only one's own data is cached.
- **Never on the device:** ballots, credentials, voting pages, "has voted" status.
- **The app states secrecy per layer:** stored-data unlinkability is guaranteed; privacy from someone watching the screen is not.
- **Release order:**
  1. P7(b) containment;
  2. claim capability (I-TDD-02 on D-113);
  3. installable membership area;
  4. phone voting after E-1, E-2 and Decision 1 are implemented.

## Conclusions
- **Strategic discovery is complete.** We know which contexts exist, what each owns and how they exchange facts. Further discovery would rediscover the same answers.
- **The strategic baseline is not yet ready for consolidation.** Nine bounded decisions are open. The first, how members without accounts can vote, affects who can take part at all.
- **Tactical DDD must wait.** It can begin as soon as these decisions are ruled and the baseline is consolidated. No further discovery round is needed in between.
