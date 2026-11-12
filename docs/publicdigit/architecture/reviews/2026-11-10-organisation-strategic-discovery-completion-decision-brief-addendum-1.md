# Addendum 1 to the Organisation decision brief: voter relationships, membership without a unit, capacities

| | |
|---|---|
| **Status** | **Addendum (decision preparation).** Decides nothing; changes no decision record. **Q-0…Q-2 are not recorded as rulings:** the commissioning note marks them "provisionally support … leave the final confirmation to the Principal". Explicit one-line rulings are requested in §6. Not independently reviewed (Claude family). |
| **Base** | `./2026-11-10-organisation-strategic-discovery-completion-decision-brief.md` (the brief). This addendum continues its §4 and §7. |
| **Commission** | "Organisation Strategic Discovery: Voter Relationships, Membership Belonging and Organisational Capacities", Principal, 2026-11-10. |
| **Placement** | `php scripts/doc-placement.php --scope=product-specific --maturity=research --domain=publicdigit` → `docs/publicdigit` (exit 0) |

**Labels:** **FACT** · **RAT** · **WD** · **APP** · **P** · **REC** · **UNKNOWN** · **PRINCIPAL**.

## 1. Q-0: what each decision means, and what withdrawing it would break
| Decision | In business language | Supersedes or qualifies | If withdrawn |
|---|---|---|---|
| D-103 | An organisation shows who is a member; only Membership decides it | D-101's "Member" capacity | two sources of truth for "is a member" |
| D-104 | Who may use an organisation's workspace is the organisation's own decision | — | access implied by login, membership or affiliation (PBDIGIT-341 F-6) |
| D-105 | The baseline cites the ratified Membership contract; rulings stay separate acts | earlier baseline text | the baseline contradicts CR-01 (RAT) |
| D-107 | Registering creates an account and nothing else | the "User affiliation" parts of BR-2, BR-8, BR-12 | a platform record holds "affiliations", contrary to D-100 |
| D-108 | "User" is not a relationship with an organisation | D-101's list | "User" overlaps workspace access |
| D-109 | In election-only organisations the voter list is the organisation's; Election only reads it | — | the voter list's owner is open again |
| D-110 | One registration, one account, many organisations | strengthens BR-4 | per-organisation registrations become possible |
| D-111 | One identity; each organisation's data is its own | — | no stated isolation invariant |

## 2. Q-1: the standing voter relationship (election-only organisations)

### 2.1 Facts that matter
- **FACT:** voters are imported per election today (`…/elections/{election}/voters/import`, writing `election_memberships`).
- **FACT:**
  - the live ballot chooses **regional posts from `users.region`**, a field on the **central account** (`app/Http/Controllers/VoteController.php:363-404`; `app/Services/BallotAssemblyService.php:62` `buildRegionalPosts`; `BallotPreviewController.php:38`);
  - a voter's geographic scope in one organisation is therefore taken from a platform-wide, self-declared, address-like field, the same for every organisation;
  - **this conflicts with D-80** (an address never establishes belonging), **D-101** (profiles are organisation-local) and **D-111** (isolation).
  - It is outside this commission's scope to fix. It should become a backlog item next to `PBDIGIT-336` (eligibility rules), with the Principal's permission.

### 2.2 The models, challenged
| Question | Standing relationship (REC) | Per-election relationship | Comment |
|---|---|---|---|
| who adds, changes, ends it | the organisation's competent authority under its rules (often its election committee or executive; **UNKNOWN** per organisation), recorded with a basis (decision, authorised import) | the same authority, per election | equal |
| evidence | a decision or authorised import (basis), as for any affiliation (people register I-14) | the same, per election | equal |
| added after an election is announced | added to the standing list; **Election's own cut-off for that election** decides whether the person counts | added to that election's list; same cut-off question | equal; the cut-off is **Election's rule** |
| removed after the electorate snapshot | the standing list changes; **the snapshot (Election's fact) does not**; any change to the electorate for that election goes through Election's own correction or objection process (PD-A, WD) | removed from that list; the same protection is needed | equal, if Election snapshots |
| categories (delegate, honorary voter, …) | **categories are attributes of the standing relationship**, as the organisation's rules define them | separate lists per category per election | standing is simpler |
| **geographic scope** | from **unit belonging** (Organisation), never from an address or `users.region` (D-80) | the same | both need belonging, or an organisation-recorded scope if no units exist (**open**, O-8 below) |
| different eligibility rules per election | Election filters the standing list by its rules (`PBDIGIT-336`) | lists assembled by hand for each election | standing avoids duplicate lists |
| historical decisions | Election's snapshot plus its basis are kept forever; the standing list's history is corrected forward | each list is history itself | equal if Election snapshots |
| **counter-case: a certified roll for one election** (an external commission certifies the electorate) | the roll is **evidence for Election's electorate decision**, Election's own fact; it does **not** become the organisation's standing relationship | fits naturally | **the standing model must allow Election to base an electorate on a certified roll** |
| an organisation that holds one election only | the same as per-election | — | no difference |

### 2.3 Invariants (P)
- **V-1:** a voter relationship never creates eligibility or entitlement (D-109; RAT boundary).
- **V-2:** an electorate snapshot, once established by Election, is never changed by later changes to the standing list.
- **V-3:** the geographic scope of a voter comes from unit belonging, or from an explicitly recorded organisational scope, never from an address or a central account field.
- **V-4:** every change records its basis and actor.

**REC:** standing relationship, with V-1…V-4, **and** Election may base an electorate on a certified roll as its own evidence.

**New open point:**
- **O-8:** in election-only organisations **without units**, where does a voter's regional scope come from? REC: a scope attribute recorded by the organisation on the voter relationship, under its rules. **PRINCIPAL when such an organisation appears.**

## 3. Q-2: membership without unit belonging
**Boundary:**
- **Membership** states admission and the membership's time coverage (RAT).
- **Organisation** states belonging (D-106 E-5).
- An **admission that names a unit** reaches Organisation as the basis for a belonging (§50.3 BelongingBasis; the route is §50.9's gap, [TU]).

| Scenario | Under B (rules decide; REC) |
|---|---|
| central or national membership (diaspora body) | complete member, no unit; counted in organisation-wide figures, in no unit list |
| geographic membership required by the rules | member shows **"awaiting unit"** until placed; counted organisation-wide, in no unit list; **membership status itself is unaffected** (Membership never interprets belonging) |
| organisation-wide positions (national committee) | a **position** (Governance), not a unit; no belonging needed |
| awaiting placement | as above; no deadline invented (the rules may set one) |
| unit dissolved or reorganised | belonging follows the successor and fallback rules (D-13…D-19, WD); membership unchanged |
| the rules change to require a unit | **from the change's effective date**, members without a unit show "awaiting unit"; no retroactive invalidity (D-6 default) |

**Effects:**
- **Reporting:** organisation-wide member count = Membership; unit member count = belonging ∩ membership coverage (§50 effective belonging).
- **History:** both kept separately.
- **Governance:** positions are independent of belonging.

**REC: B.** NRNA's rule is **UNKNOWN** until its constitution is on file.

## 4. Q-3: capacities, discovered before choosing

### 4.1 Vocabulary proposal (P)
| Term | Meaning | Owner | Recorded? |
|---|---|---|---|
| **Employment** (contract, pay, hours) | an HR and legal relationship | **outside PublicDigit** (no HR context exists or is proposed) | **not modelled** |
| **Worker** (organisation level) | the organisation's own statement "this person works for us, paid or voluntary", for its register, contacts and access decisions | Organisation (affiliation, D-101) | yes |
| **Unit staff** (unit level) | a placement in a unit **in a capacity the organisation's rules recognise** (for example "district office staff") | Organisation (belonging, §50.4) | only where the rules recognise it |
| **Volunteer** | a Worker without pay, or unit staff, as the rules say; not a separate kind unless the rules make it one | Organisation | as a Worker or unit capacity, not a third fact |
| **Guest** (organisation level) | takes part in the organisation without membership | Organisation (affiliation) | yes |
| **Guest / observer at a unit** | admitted to a unit's activities | Organisation (belonging) | where the rules recognise it |
| **Appointed officer** | holds a position | **Governance** (positions, tenures) | not an affiliation, not a belonging |
| **Workspace access** | may use the digital workspace | Organisation (D-104) | yes, its own grant |

### 4.2 The seven cases
| # | Case | Facts and authority | Level | Independent or derived | Unit reorganised |
|---|---|---|---|---|---|
| 1 | paid employee at the Kathmandu district office | Worker (the organisation's management) + unit staff at Kathmandu (if the rules recognise unit staff) | both | **independent:** one is about the organisation, the other about the place | belonging follows successor rules; Worker unchanged |
| 2 | volunteer for a district committee, no national role | unit staff or a unit volunteer capacity (district authority, per rules); a Worker affiliation **only if the organisation chooses to record one** | unit | independent; no automatic Worker | as 1 |
| 3 | national employee temporarily in a district | Worker (national); **temporary unit staff** with a period | both | independent | the temporary belonging ends or follows successor |
| 4 | guest at a unit meeting | unit guest/observer (unit authority); **no** organisation Guest unless recorded | unit | independent | ends with the event or period |
| 5 | appointed to a national committee, no unit | a **position** (Governance); optionally Worker if the organisation says so | — | independent of both | not affected |
| 6 | works for two units | two unit-staff belongings (where the rules allow; limits per rules, §50.4) + one Worker | unit ×2, organisation ×1 | independent | each follows its successor |
| 7 | unit dissolved, staff remain employed | belongings end or move by successor rules; **Worker continues** | — | **this case proves independence:** a derived Worker would vanish with the unit | — |

### 4.3 Conclusion
- **Independence with distinct names (option A) survives the scenarios.**
- **Case 7 rules out "derived" (B).**
- **Cases 1, 3, 6 rule out "one level only" (C),** because they are different facts.

**The duplication risk is controlled by two rules (P):**
1. **No automatic derivation in either direction.**
2. **Each capacity is defined at exactly one level:** Worker and Guest at organisation level; unit staff and unit guest at unit level, only where the organisation's rules recognise them.

**Employment is not modelled.**

**REC: A with this vocabulary.** **PRINCIPAL:** confirm that PublicDigit does not model employment.

## 5. Cross-context check
| Context | Effect of the recommendations |
|---|---|
| Identity | none; all facts above hang off the organisation's `PersonRef` (P-I1 open only for Person timing) |
| Organisation | people register: Worker, Guest, voter (with categories; scope O-8); belonging: unit staff, unit guest, member-dependent capacities; nothing derived |
| Membership | admission and coverage only; never reads belonging (CR-01) |
| Election | snapshots the standing voter list per election (V-2) or a certified roll; geographic scope from belonging (V-3), **not `users.region`** (FACT conflict, §2.1) |
| Governance | sets rules; holds positions (case 5); competent authority for voter-list changes |
| Finance | unaffected |

## 6. Exact rulings requested (explicit one-liners)
| # | Question | REC |
|---|---|---|
| **Q-0** | "D-103…D-111 confirmed as recorded" (or name any to withdraw) | confirm |
| **Q-1** | Election-only voter relationship: **standing**, with V-1…V-4, and Election may use a certified roll as its own evidence | yes |
| **Q-2** | Membership without a unit: **B**, the organisation's registered rules decide | B |
| **Q-3** | **A**: independent capacities at one level each, the vocabulary of §4.1, **and** PublicDigit does not model employment | A, no employment |
| (permission) | open a backlog item: "regional posts use the central `users.region`" (§2.1), next to `PBDIGIT-336` | yes |

## 7. Tactical implications after the rulings
- **§49 Structure:** continues now (no dependency).
- **§50 Person belonging:** add the Q-2 "awaiting unit" state as an Organisation display state (not a Membership status); adopt the §4.1 unit-level vocabulary; no derivation to affiliations.
- **People register:**
  - voter relationship standing, with categories and (O-8) a scope;
  - Worker and Guest at organisation level only;
  - the three aggregates unchanged.
- **Election (later, its own design):** snapshot per election from the standing list or a certified roll; scope from belonging.
- **P-I1:** unchanged; IR-OPR-01 extended (see its appended note).
