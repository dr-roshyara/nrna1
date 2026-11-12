# Election model: strategic validation and Principal decision preparation

| | |
|---|---|
| **Status** | **Strategic validation report.** Records no ruling; changes no code, decision record or baseline; creates no backlog item. Written by Claude, **not independent**. |
| **Commission** | "Strategic DDD: Election Model Validation and Principal Decision Preparation", Principal, 2026-11-10 (the Principal DDD Architect's review, accepted by the Principal). |
| **Validates** | `./2026-11-10-election-constituency-and-regional-voting-authority-decision-brief.md` (the brief) and `./2026-11-10-election-constituency-decision-brief-addendum-1.md` (addendum 1), **with their reconciliation note** after D-114…D-121. |
| **Also read** | `./2026-11-10-people-register-p2-p7-decision-brief.md`; `./2026-11-10-ir-opr-01-independent-review.md`; the decision record D-13…D-122; the tactical model §22 (Election and Voting); the Organisation baseline §3; `docs/architecture/discovery/Round29_Invariant_Catalog.md` (VO-1). |
| **Placement** | `php scripts/doc-placement.php --scope=product-specific --maturity=research --domain=publicdigit` → `docs/publicdigit` (exit 0) |

**Labels:** **FACT** · **RAT** · **WD** · **APP** · **P** · **REC** · **UNKNOWN** · **PRINCIPAL**.

## 0. Where this report disagrees with the commission or corrects the earlier briefs
1. **Scope of decisions read.** The commission names D-13 to D-113. **D-114…D-122 are recorded and change the analysis**, so they are included:
   - D-114 and D-120: every member of a membership organisation always has a unit, at least the root.
   - D-118 and D-122: one geographic belonging by default.
2. **E-5 cannot be deferred as a whole (correction of the brief).** D-48 (WD) says that **the official result of every race is always published**; only optional breakdowns have a minimum size. A regional race with a tiny electorate therefore publishes its voters' choices. The protection must come **before** the race exists, which is strategic. The brief's "defer E-5" was wrong (§6).
3. **Citation correction.** The brief said that "under D-44" credentials authorise a ballot style. D-44 only puts secret elections in the baseline. The credential-per-ballot-style rule is in the tactical model §22 (P, Session B).
4. **E-3 as worded in the brief is withdrawn as a recommendation.** The Principal DDD Architect's doubt is justified, and the reason is stronger than stated: **a unit carries consequences outside elections**. These include:
   - forums and participation derived from belonging (§23, D-50…);
   - communication audiences (D-57…);
   - allocation authority (D-115…D-119).

   Calling an electoral grouping a "unit" would give voters organisational rights nobody decided. §4 proposes the replacement.

Otherwise I agree with the review, including its assessment that discovery is enough to prepare decisions but not yet a frozen design.

## 1. Executive summary
- **E-1 and E-2 hold** (REC accept). They follow from the established boundaries. No recorded decision contradicts them. One gap needs closing in wording, not in a new decision: **R-5 answers only for members**. For election-only organisations a second, already-proposed query applies, the voter relationship at τ.
- **E-3 needs a new formulation.** An electoral district is **not** automatically a unit. Where districts coincide with units, units are used. Where they do not, the organisation records an **electoral division** for the person. It is a separate Organisation fact that gives no organisational rights. A certified roll remains Election's evidence.
- **E-4 should block only the affected races**, not the whole election and not single voters.
- **E-5 needs a strategic rule now:** PublicDigit never presents a race as secret when its electorate is too small to keep it secret. The numerical threshold is the organisation's rule and can wait.
- **E-6 is consistent with the existing boundary.** Governance sets candidacy criteria, and Election applies them (tactical model §15–§17, R-6a). E-6 only adds "belonging" as one possible criterion. Its timing needs one rule: check at the nomination cut-off; a later change is reported to the election committee, never applied silently.
- **The Membership contract finding is a conformance defect, not a design question.**
- **Readiness:** Election can enter baseline consolidation once you rule on **E-1, E-2, E-3′, E-4′, E-5′, E-6′ and P4–P6**. NRNA's constitution remains evidence still to collect; it does not change the model.

## 2. Already established (not for decision)
| Fact or rule | Status |
|---|---|
| Election decides electorate, eligibility, entitlement; Voting enforces ballots without identity | RAT boundary; VO-1 (Round 29, closed) |
| Organisation owns units and belonging; an address never creates belonging | WD D-79, D-80, D-101 |
| Organisation-defined areas never by themselves determine belonging or electoral entitlement | WD D-82 |
| Every effective member is in a unit, the root by default; one geographic belonging by default | WD D-114, D-118, D-120, D-122 |
| Snapshot at cut-off; later facts do not change it; pending restructuring blocks | WD D-41…D-43 |
| Every race's official result is published; breakdowns only above the organisation's minimum | WD D-48 |
| Secret-ballot assurance Level A now; never presented as Level B | WD D-45 |
| Membership facts only through CR-01 C4 (predicates at asOf, τ) | RAT |
| Governance sets voter and candidacy criteria; Election applies them (R-6a) | WD D-32; tactical model §15–§17 (P) |
| Voter relationship in election-only organisations is the Organisation's | WD D-109 |
| Identity: reference at import, Person at claim; at most one bound reference per Person per organisation | WD D-113 |

Today's code (FACT, brief §2 and addendum 1 §3):
- `users.region` against free-text `posts.state_name`, read live at display and at cast;
- no basis kept;
- candidacy not district-checked;
- Membership tables read directly.

## 3. E-1 and E-2 validation
| Test | Result |
|---|---|
| Follow from established ownership? | **Yes.** Election's RAT boundary includes "for which races"; belonging is Organisation's (D-79). A central field owned by neither violates D-80, D-101 and D-111. |
| Contradicted by a decision? | **No.** D-114 strengthens E-2 (members always have a unit). D-118 limits overlap. |
| Is R-5 sufficient? | **For membership mode, yes:** `EffectiveMembersAsAt(scope, asOf, τ)` gives "the unit or below", which matches nested posts. **For election-only mode, no:** voters are not members. Their division comes from the voter relationship at τ (addendum 2 §2.2, T-8). **The gap is closed by E-3′ (§4).** It is a wording gap, not a new contract. |
| Both organisation modes covered? | With E-3′, yes. |
| Same decision for display, preview and final cast? | **Required.** All three must read the regional ballot **from the snapshot**. The issued credential is per ballot style (§22), so Voting checks the style it was given, not a live field. That also removes the display/cast inconsistency (C-7). |
| Explainable afterwards? | Yes, if the snapshot keeps: T and τ; district definitions (unit sets or division sets); the rule version; per voter, the races and the basis (the R-5 answer marker, or the voter-relationship version, or the roll entry); and the C4 answer marker in membership mode. |

**REC: accept E-1 and E-2.** The only refinement is E-2's wording: "a set of units **or electoral divisions** (E-3′)".

## 4. E-3: what is a district if it is not a unit?

### 4.1 The test
The baseline defines a unit as "a place in the organisation's structure where people **belong**" (§3). Belonging then has effects: forums, audiences, allocation authority, and D-114's placement rules.

| | A: organisational division | B: electoral district only | C: certified roll |
|---|---|---|---|
| people belong, take part, have duties there | yes | **no** | no |
| meets the unit definition | **yes** | **no**, only its electoral effect | no |
| who records it | Organisation (D-79) | must be decided | an external competent authority |

**Answers to the commission's questions:**
- **Does an electoral division create belonging?** No, not by itself. A party can count votes by district without the district being a branch.
- **Can an organisation have districts that are not units?** Yes. Example ([PK], NRNA-like): members belong to the national council for Germany, while "State Representative – Bayern" is elected by members living in Bayern. There is no Bayern branch.
- **Would forcing districts into units create false facts?** Yes. A Bayern "unit" would give a Bayern forum, a Bayern audience and Bayern allocation authority that the constitution never created.

### 4.2 Options
| Option | Meaning | Consequence |
|---|---|---|
| D (brief) | every district is a unit | false organisational facts (§4.1); **rejected** |
| **E-3′** | districts are **unit sets** where they match units; otherwise **electoral divisions**, a separate Organisation fact | a second concept, but one with a different meaning: it is a grouping for elections only, with no rights, organ or forum |
| C (per-election assignment by Election) | Election decides where each person stands | Election would decide an organisational fact and repeat it each election; **rejected** |
| B: certified roll only | rely on external rolls | works only where an authority exists; not general |

**The model-integrity test** (new dimension only if orthogonal, necessary, sufficient):
- **Orthogonal: yes.** A member can be in unit "Germany" and division "Bayern".
- **Sufficient: yes.**
- **Necessary: probable, not proven.** The evidence is [PK] (the project overview's regional posts) and NRNA-like diaspora practice. **NRNA's constitution would settle it.** If you prefer to wait for that evidence, E-3′ can be ruled "in principle" and activated when the first organisation needs it.

### 4.3 Rules for electoral divisions (P)
- **Owner:** Organisation. It is a standing fact about the person (a member, or a voter relationship under D-109), used across elections.
- **How assigned:** the same discipline as belonging. An individual act, an authorised import as basis, or a residence rule the written rules adopt (D-79). An address or a D-82 area alone never assigns anyone (D-80, D-82).
- **Effects:** **only** as Election's input. No forum, audience, organ or allocation authority attaches to it.
- **Election** defines each post district as a set of units **or** a set of divisions, and reads both at τ.

**Recommended E-3′:**
> "A post district is a set of the organisation's units where its electoral districts coincide with its units. Otherwise it is a set of electoral divisions: an Organisation fact assigned by act, authorised import or adopted residence rule, which grants no organisational belonging or rights. An externally certified roll may serve as Election's evidence instead."

**PRINCIPAL:** E-3′, or wait for NRNA's constitution.

## 5. E-4: what an unresolved overlap blocks
D-118 and D-122 limit the case to wing districts, or to organisations whose rules allow several geographic belongings.

| Scope blocked | Integrity | Operation | Verdict |
|---|---|---|---|
| whole electorate | safe | one silent rule stops a national election | too broad |
| only the affected **voter's** regional ballot | **unsafe**: the race would run with an incomplete electorate, and its result is wrong | — | rejected |
| **only the affected races** (the posts whose districts overlap) | safe: a race opens only with a complete, decided electorate | national and unaffected races proceed | **REC** |

**Recommended E-4′:**
> "If post districts overlap for a voter and the rules do not say whether that voter may vote in several such races, **those races** are not opened until the competent body decides. Other races may proceed if the election committee so decides. Otherwise the whole election waits."

**Not invented:** no "one district per person" platform rule.

**Tactical consequence:** opening a race later means a separate ballot style and credential round (§22). That is design work, not a strategic issue.

**Note:** D-41 (pending restructuring) blocks the whole electorate. E-4′ does not reopen D-41.

## 6. E-5: ballot secrecy in small districts
**Findings:**
- **A ballot style reveals the district:** the credential is issued per style (§22). That is inherent and acceptable; the anonymity set is the voters of that style.
- **A small district does weaken secrecy.** D-48 publishes every race's result, so with 2 voters each knows the other's vote, and with any N a unanimous result reveals everyone.
- **What is already covered:** breakdowns (D-48 minimum; §22 secondary suppression, unlabelled national records from small styles). VO-1 (stored-data unlinkability) is not breached, because the leak comes from the published result, not from stored links. D-45 forbids presenting weaker assurance as stronger.
- **Not covered:** the race result itself for a tiny district.

**Separation:**
- **Strategic invariant (decide now):**
  > "PublicDigit never presents a race as secret when its electorate is below the organisation's minimum secret-race size. Such a race is held only after a recorded decision by the competent body, and voters are told before voting that secrecy is limited."

  This mirrors D-45.
- **Organisation rule (later):** the number. Until the organisation sets one, the election committee is shown each regional race's electorate size before fixing and confirms it. This needs no platform-invented number.
- **Tactical (later):** merging small districts, how voters are informed, how it is displayed.

**Why not a hard platform minimum:** real party bodies do elect in very small districts (a three-person district committee), and their statutes accept it. A hard minimum would stop legitimate elections. Silence would mislead voters.

**PRINCIPAL:** accept the invariant (E-5′) now; the threshold stays organisation policy.

## 7. E-6: regional candidacy
**Boundary check:**
- Governance sets candidacy criteria and Election applies them (R-6a; tactical model §15–§17). E-6 is that rule applied to belonging; no ownership changes.
- **Candidacy criteria are a separate rule set from voter criteria** (both in R-6a). So E-6 and E-2 are separate rules and may differ.

| Question | Political-party practice | REC (P) |
|---|---|---|
| when is it tested? | statutes name the moment: nomination deadline, acceptance or ballot publication | **the criterion's own rule names the moment**; default the **nomination cut-off** |
| belonging changes after nomination | some statutes disqualify, others let the candidate stand and decide only on taking office | **no silent removal.** Election reports the change to the election committee, which decides under the rules. After the ballot is fixed, a change goes to Contestation (D-71: effective until determined). |
| must remain qualified until election day? | varies | only if the rule says so; then the committee decides as above |
| may the organisation differ? | yes | yes (R-6a) |
| stand in several districts? | usually forbidden; sometimes allowed | the organisation's rule decides; **the platform invents no limit** and warns the committee if a person is nominated twice |
| election-only organisations | candidates may not be members | the criterion reads a division or voter relationship (E-3′) instead of belonging |

**Recommended E-6′:**
> "Whether a candidate for a regional post must belong to its district is the organisation's rule. Where it applies, Election checks the candidate's belonging or division at the time the rule names (by default the nomination cut-off). A later change is reported to the election committee and never removes a candidate automatically."

## 8. Certified rolls
| Question | REC (P) |
|---|---|
| who may certify | the authority the organisation's written rules name (for example an independent election commission or an external public authority); **established through Governance** (a rule or a recorded decision), not by the uploader |
| link to this election | the certification states the election and the as-at date; Election accepts it only for that election and treats that date as its cut-off evidence |
| amendments | before the electorate is fixed: a newly certified roll replaces the old one, with both kept. After fixing: Contestation only |
| identity | **a roll entry is not a person in PublicDigit.** Each entry must be matched to the organisation's own register (a `PersonRef`, through import under P6). Only matched entries can receive a voting right. **Certification proves neither an account nor a credential.** Claim and sign-in follow D-113 |
| duplicates | two entries matched to one reference give one right; unclear matches are a P4 case for the organisation |
| anonymity | the roll stays in Election (identified); right issuance is the normal blind-credential path; **Voting never sees the roll** |
| evidence kept | the roll's digest, the certifier and their authority basis, the date, the matching result per entry |

**Ownership:** Election, as evidence. **No new context.** The matching of entries to people is Organisation's (people register).

## 9. P4–P6: when Election may refuse to fix an electorate
| Pending | Refuse to fix? | Can unaffected voters proceed? |
|---|---|---|
| **P4** (open case after an Identity merge touching two of the organisation's records) | yes, **while the case is open** (D-41 pattern) | no: a duplicate voter affects every race they are in, including national races. The organisation must decide before T, and the count is small. **Residual risk:** duplicate *unbound* references can't be detected (D-113) under any option |
| **P5** (standing voter relationship) | no; it is the input | — |
| **P6** (row "person recorded, voter relationship pending" at T) | **no**; pending is **not a voter** | yes. Election shows the committee the number of pending rows before fixing (visible, not blocking) |

File plus row identifies an **import occurrence only**, never one person across files. This is respected.

P2, P3 and P7 have no direct effect on Election, and no new evidence changes that.

## 10. Membership contract C4: conformance
- **What Election may ask:** C4 predicates for a person at `(asOf, τ)`, for example "in force on date X, as known at τ". **What Membership answers:** its own facts and coverage, including corrections it knew by τ (CR-01, D-73).
- **Why direct table reads violate the boundary:**
  - Election would rebuild Membership's rules (what "active" or "lapsed" means, grace periods, corrections);
  - it would read today's tables instead of "as known at τ", so a later correction could silently change an electorate that is already fixed;
  - Membership could not change its storage without breaking Election.
- **Classification:** strategic contract conformance (FACT `EloquentVoterEligibilityQueryService.php:108-112`; known as `PBDIGIT-318` V2). **No decision needed.** Tactically, Election's eligibility reads go through the C4 port, with τ = T.

## 11. Integrated scenarios
| # | Scenario | Owner of the fact | Rule | Time | Election outcome | Open |
|---|---|---|---|---|---|---|
| 1 | units + membership rules | Org (belonging), Membership (C4) | E-2; C4 | T | races from units; eligibility from C4 | — |
| 2 | units + election-only voters | Org (voter relationship; belonging only if voters truly belong) | E-2 / E-3′ | T | races from units or divisions | E-3′ |
| 3 | no units (election-only) | Org (divisions) or roll | E-3′ / §8 | T | races from divisions or the roll | E-3′ |
| 4 | residence-based allocation | Org (adopted rule creates belonging or division) | D-79, D-80, D-82 | T | as 1 or 3 | — |
| 5 | certified roll | Election (evidence); Org (matching) | §8 | roll date ≤ T | matched entries only | — |
| 6 | several units | Org | D-118; wings additional | T | nested posts all; wing districts per rules | E-4′ |
| 7 | overlapping districts, rules silent | Governance (rule gap) | E-4′ | before opening | affected races wait | E-4′ |
| 8 | unit change before T | Org | D-42 | T | new unit counts | — |
| 9 | unit change after T | — | D-42 | — | no effect | — |
| 10 | membership ends or lapses near T | Membership | C4 at τ = T | T | as known at T; later corrections → Contestation | — |
| 11 | Identity merge touching two records | Org decides (P4) | P4 | before T | refuse to fix while open | P4 |
| 12 | voter relationship pending at T | Org | P6 | T | not a voter; count shown | P6 |
| 13 | candidate changes unit after nomination | Org (fact), Election (applies), committee (decides) | E-6′ | per rule | reported; no silent removal | E-6′ |
| 14 | tiny regional district | Election; Governance (minimum) | E-5′; D-48 | before fixing | held only after a recorded decision, with voters told | E-5′ |

## 12. Contradictions and evidence gaps
- **Resolved contradictions:**
  - E-3 versus the unit definition (by E-3′);
  - "defer E-5" versus D-48 (by E-5′).
- **Implementation contradictions (FACT, unchanged):** `users.region`; free-text post regions; live ballot recomputation; no candidacy check; direct Membership reads.
- **Missing evidence:**
  - NRNA's constitution (districts, candidacy rules, whether divisions are needed);
  - NRNA's list practice (P5);
  - real district sizes (for E-5);
  - real `state_name`/`region` data (not queried).
- **Not independent:** this report and both briefs are Claude-written. An independent review before freezing remains advisable.

## 13. Recommended Principal decisions
| # | In business language | REC | Rejects |
|---|---|---|---|
| **E-1** | A central account field never decides which regional posts someone votes for | accept | keeping `users.region` |
| **E-2** | Each regional post has a district; the voter's regional ballot is fixed at the cut-off and kept with the election | accept | live recomputation |
| **E-3′** | Districts use units where they match. Otherwise the organisation records an electoral division, which gives no organisational rights. A certified roll may be used instead | accept (or "in principle, pending NRNA's constitution") | "every district is a unit"; Election assigning people |
| **E-4′** | An unresolved overlap holds only the affected races | accept | blocking everything; blocking single voters |
| **E-5′** | A race too small to be secret is never presented as secret; it runs only after a recorded decision, with voters told. The threshold is the organisation's rule | accept | hard platform minimum; silence |
| **E-6′** | Regional candidacy follows the organisation's rule, checked at the time the rule names (default nomination cut-off); later changes go to the committee, never automatic removal | accept | no check (today); automatic disqualification |
| **P4** | After an Identity merge touching two of its records, the organisation decides before the next cut-off; until then no electorate is fixed | accept | duplicate voters |
| **P5** | Election-only organisations keep a standing voter relationship | accept | per-election lists only |
| **P6** | "Pending" is never a voter; the committee sees the count before fixing | accept | assuming pending rows vote |

**Can wait for tactical design:**
- the numbers in E-5;
- the mechanics of opening a held race later;
- the snapshot structure;
- how the C4 port is wired;
- migration of existing regions.

## 14. Baseline readiness
- **Coherent with PublicDigit:** yes. No new bounded context; no ownership moves. The only new concept is the electoral division (E-3′), owned by Organisation and passing the integrity test except for proven necessity.
- **Ready for consolidation** once the rows of §13 are ruled. P2, P3 and P7 belong to the people-register consolidation, not to Election.

## 15. Mobile and installable app (PWA) consequences
- **Phone ballot correctness depends on E-2.** Once the ballot is read from the snapshot and the credential per style, a phone renders exactly the issued style. Nothing is computed on the device.
- **Secrecy constrains the PWA** (§22 Level A: no ballot-related files or queue entries). The service worker must **never cache** voting pages, ballots, credentials or submissions. Offline voting is excluded. **Only the membership area may work offline.**
- **Membership area on the phone (stage 2):**
  - it shows the member's unit (always present, D-114) and their division (E-3′);
  - from cut-off T onwards it shows the races they are entitled to;
  - before T it shows them only as **provisional**, never as a right.
- **Notifications:** election reminders go to the electorate, never to "who has not voted" (Communication, no "has voted" audiences).
- **Gates for the installable app** are unchanged: the claim capability (I-TDD-02 on D-113) and P7 containment. Phone voting additionally needs E-1/E-2 implemented.

## 16. Final briefing
- **Established:**
  - Election, not an account field, decides regional ballots, fixed at the cut-off and explainable later;
  - Organisation supplies where people belong, Membership supplies membership facts through its contract, Voting never sees identity.
- **Uncertain:**
  - NRNA's constitution (whether electoral divisions are needed; candidacy rules);
  - real district sizes;
  - list practice.
- **Your decisions:** E-1, E-2, E-3′, E-4′, E-5′, E-6′, P4, P5, P6 (§13).
- **Can wait:** secrecy thresholds, race-reopening mechanics, snapshot design, migration, the C4 wiring (a conformance fix).
- **Coherent with the rest of PublicDigit:** yes.
- **Baseline ready for consolidation:** yes, after those rulings. Freezing should follow an independent review.
