# Addendum 1 to the Election post-district brief: membership mode, P2…P7 dependencies, time, readiness

| | |
|---|---|
| **Status** | **Strategic discovery addendum.** Records no ruling; changes no code or other document; creates no backlog item. Not independently reviewed (Claude family). |
| **Base** | `./2026-11-10-election-constituency-and-regional-voting-authority-decision-brief.md` (the brief: vocabulary §3, organisations with units §4, O-8 §5, time §6, options §8, E-1…E-4 §10). **Not repeated here.** |
| **Commission** | "Strategic DDD Discovery: Election Constituency and Regional Voting Authority" (second commission of that name), Principal, 2026-11-10. |
| **P2…P7** | **Pending** (the commission says so unless rulings are supplied explicitly). Dependencies are analysed in §5 without choosing. |
| **Placement** | `php scripts/doc-placement.php --scope=product-specific --maturity=research --domain=publicdigit` → `docs/publicdigit` (exit 0) |

**Labels:** **FACT** · **RAT** · **WD** · **P** · **REC** · **UNKNOWN** · **PRINCIPAL**.

## 1. Executive summary
**Additions to the brief:**
1. In **membership-mode** organisations the electorate is formed from Membership facts. **Today's code reads Membership's tables directly**, not through the ratified contract.
2. **Post regions are free text typed by officers.**
3. **Candidacy for a regional post is not scoped to the district at all** (new question E-6).
4. People-register decisions P4 and P6 affect when an electorate may be fixed.
5. The time rules hold for every scenario tested.

**Readiness:** strategic discovery for Election post districts is **sufficient for baseline consolidation once E-1…E-4 (and E-6) are ruled**. NRNA's constitution remains UNKNOWN; that is evidence the rules depend on, not a gap in the model.

## 2. Business model and vocabulary (complements brief §3)
| Concept | Meaning | Owner |
|---|---|---|
| Organisation's knowledge of a person | its register entry (D-101) | Organisation |
| Formal membership | membership facts and time coverage | Membership (RAT) |
| Unit belonging | a place in a unit, by an act (D-79) | Organisation |
| Standing voter relationship | election-only organisations: on its voters' list (D-109) | Organisation |
| **Membership-based voter qualification** | membership-mode organisations: qualification evaluated from Membership facts **under the rules set by the competent body** (DD-11) | Election evaluates; Membership supplies facts (C4); Governance sets rules |
| Election-specific eligibility | the result of applying the election's rules at T | Election |
| Final electorate | fixed at cut-off T with each voter's regional ballot | Election |
| **Candidate district eligibility** (new) | whether a candidate for a regional post must belong to its district | Election, under the organisation's rules (E-6) |

## 3. Verified implementation behaviour (additions to brief §2)
| # | Finding | Evidence | Status |
|---|---|---|---|
| C-12 | a post's region is **free text typed by an officer**, required only for non-national posts | `app/Http/Controllers/Election/PostManagementController.php:55, :80` | FACT |
| C-13 | results are grouped and labelled by that free text | `ResultController.php:42, :95, :253, :318` | FACT |
| C-14 | **candidacy is not scoped by district:** the old region filter is commented out; candidacy applications never read the candidate's region or belonging | `CandidacyController.php:57` (`// ->where('region', …)`); `CandidacyApplicationController.php:50, :145` (lists posts with `state_name` only) | FACT (code read) |
| C-15 | **membership-mode eligibility reads Membership's tables directly** (`members`, `organisation_users`, `membership_types`), not the ratified C4 contract | `app/Contexts/Elections/Infrastructure/Policies/EloquentVoterEligibilityQueryService.php:27, :108-112`; known as `PBDIGIT-318` V2 | FACT |
| C-16 | eligibility (who votes) and regional scope (which posts) are **separate code paths**: eligibility does not read `region`, scope does not read eligibility | brief C-10; C-15 | FACT |
| C-17 | organisations influence each other's regional scope only through the operator's `ImportUsers` (brief C-2) and the person's own registration; **no organisation-facing screen writes `region`** | brief C-1…C-3, C-11 | FACT (search) |

## 4. Organisation types (complements brief §4–§5)
| Type | How the electorate and regional ballots form | Status |
|---|---|---|
| **A. With units** | brief §4: post district = units; belonging at T via R-5 | P (E-2) |
| **B. Without units (O-8)** | brief §5: divisions are units (D), or a certified roll (B) | P (E-3) |
| **C. Membership-based rules** | **Election** asks Membership's **C4** predicates at `(asOf, τ = T)` for the qualification the rules require (for example "member in good standing for 6 months"), combines them with belonging at T for regional ballots, and fixes the electorate. **No voter list exists in Organisation** (D-109 is for election-only organisations). Today's direct table reads (C-15) are the deviation to close (`PBDIGIT-318`). | RAT (C4, CR-01); P (combination) |
| **D. Residence rules** | residence affects scope **only** through the organisation's adopted residence rule creating belongings (D-79); the officer confirms, nothing is inferred from an address | WD D-79, D-80 |

**NRNA:** which type applies is **UNKNOWN** until its constitution is on file. **[PK]:** posts like "State Representative – Bayern" (project overview) suggest type A or D with German states as districts.

## 5. Dependencies on the pending people-register decisions
| Pending | What changes for Election | Analysed alternatives |
|---|---|---|
| **P4** (duplicates; Identity merge before cut-off) | if the "decide before the next cut-off" duty is adopted, **Election does not fix an electorate while an affected organisation has an undecided merge case** (the D-41 pattern) | if not adopted: the electorate may contain two entries for one human, so a double-vote risk remains until the organisation acts. **Election-only voters with two unbound references cannot be detected at all** (D-113: no Person yet), and that holds under every option. |
| **P5** (standing voter relationship) | election-only electorates are read from the standing relationship at T | per-election lists: Election would read the list made for that election; same cut-off rule |
| **P6** (pending voter relationship) | a row in "voter relationship pending" at T **is not a voter** for that election; Election never assumes it | under all-or-nothing, the case does not arise; under person-only, a lost relationship is invisible to Election |
| **P2, P3, P7** | no direct effect on post districts | — |

**The P6 qualification** (from the Principal's note): file plus row identifies an **import occurrence**, not a person across files. It may be used only to detect a repeated row within one import, never to decide that two rows in different files are one person.

## 6. Time and integrity (complements brief §6)
| Event | Evaluated when | Effect |
|---|---|---|
| belonging changes before T | at T | the new unit counts |
| belonging changes after T | — | snapshot unchanged (D-42) |
| added to or removed from the standing voter list | at T | before T counts; after T no effect on that election |
| **membership changes near T** (admitted, ended, lapsed, corrected) | **C4 at (asOf, τ = T)**: Membership answers "as known at τ"; later corrections do not alter the snapshot | RAT (CR-01; D-73) |
| duplicate record corrected by the organisation | before T: one entry; after T: no effect; any double vote is a matter for Contestation | P4-dependent |
| Identity merges two Persons affecting an organisation | P4: decide before T, or Election waits | P4-dependent |
| district changed after T | no effect on the fixed election | brief §6 |
| voter relationship pending at T | not a voter | P6-dependent |
| different organisations' rules | each election applies its own organisation's rules (BR-13: none across organisations) | — |

**Preserved in Election's own record:** T and τ; the district definitions; the rule version applied; for each voter their regional ballot and its basis (belonging version, C4 answer marker); the certified roll if used. **Voting receives none of this identity** (VO-1).

## 7. Cross-context ownership
No change to brief §7. **One addition:** candidate district eligibility (E-6) is Election's, using Organisation's belonging at the nomination cut-off. Governance sets the rule; Organisation supplies the fact.

## 8. E-1…E-4 (from the brief), with E-5 and new E-6
| # | Proposition (short) | Brief REC | Change after this addendum |
|---|---|---|---|
| E-1 | no central account field decides a regional ballot | accept | strengthened: the free-text post regions (C-12) are part of the same problem |
| E-2 | post district = a set of units; regional ballot from belonging at T, stored in the snapshot | accept | covers type A and D; type C adds C4 (§4) |
| E-3 | a division whose people vote for a post is a unit; a certified roll may replace it | accept | unchanged |
| E-4 | overlapping districts with silent rules: no electorate until decided | accept | unchanged |
| E-5 | minimum district size or result aggregation for secrecy | defer | unchanged |
| **E-6 (new)** | "Whether a candidate for a regional post must belong to its district is the organisation's rule; where it applies, Election checks the candidate's belonging at the nomination cut-off. Without a rule, no district requirement applies." | — | **REC: accept.** Today there is no check at all (C-14); E-6 makes it rule-driven instead of absent |

**Why E-6 is needed:** E-1…E-4 cover who may **vote** for a regional post, not who may **stand** for it. Party statutes commonly require district representatives to belong to the district. Without E-6 that requirement cannot be expressed.

## 9. Contradictions, missing evidence, open questions
- **Contradictions with records (FACT):**
  - `users.region` (brief);
  - free-text districts (C-12);
  - membership-mode eligibility reading Membership tables directly (C-15, against CR-01 C4, RAT).
- **Missing evidence:**
  - NRNA's constitution (type; districts; candidate rules);
  - NRNA's list practice (P5);
  - real post and region data (not queried).
- **Open:** E-5; O-8 real cases; legal (GDPR Art. 9 for electorates of political organisations).

## 10. Consequences for the strategic baseline
Election's strategic part becomes:
- post districts;
- electorate formation by organisation type;
- the snapshot contents;
- candidate district eligibility.

All rest on existing contracts: R-5 (WD D-32), C4 (RAT), D-42 (WD), D-109 (WD), VO-1 (RAT). **No new bounded context; no ownership change.**

## 11. Is discovery complete enough to consolidate?
**Yes, conditionally.** Election post districts can enter baseline consolidation once **E-1…E-4 and E-6** are ruled. People-register P4, P5 and P6 should be ruled first or at the same time, because they set the cut-off behaviour. NRNA-specific evidence can follow without changing the model.

## 12. Principal briefing
- **What we learned:**
  - regional voting today depends on two free-text values: the person's own `region` and the officer's post `state_name`;
  - candidacy for regional posts is unchecked;
  - membership-mode eligibility bypasses Membership's contract.
- **Already settled:**
  - ownership (Election decides; Organisation supplies belonging; Membership supplies facts);
  - time (D-42; C4 "as known at τ");
  - identity (D-113).
- **Uncertain:** NRNA's constitution and list practice; real data.
- **Decisions for you:**
  - **E-1, E-2, E-3, E-4, E-6.** E-1 to E-4 make regional voting depend on the organisation's own structure at a fixed time, instead of free text. E-6 lets party rules about who may stand for a region be enforced.
  - **P4, P5, P6.** They decide when an electorate may be fixed.
- **REC:** accept E-1…E-4 and E-6 as worded; rule on P2…P7 per their brief.
- **Next:** with those rulings, baseline consolidation (Identity, Organisation, Membership, Election, Governance, Voting) can begin.

## Reconciliation note (2026-11-10, after D-114…D-121; append-only)
- **§4 type "without units" for membership-based organisations does not arise:** every effective member is in a unit, at least the root unit (D-114, D-120). O-8 remains **only for election-only organisations**, whose voters are not members.
- **§6 "unplaced"** is replaced by D-121's "awaiting placement", meaning the member sits in their fallback unit and **counts for the fallback unit's districts**.
- **E-2 under D-114:** posts are hierarchical. A post whose district is province Y covers members of Y and of every unit below it (R-5, "the unit or below"). A root-unit post is organisation-wide. A member in district D1 therefore receives the D1, Y and organisation-wide posts. This **nesting is intended, not an overlap**.
- **E-4 narrows under D-118** (one geographic belonging by default). Non-nested overlaps arise only from wing districts, or where an organisation's rules allow several geographic belongings. E-4 stays as worded for those cases.
- **E-6 unchanged.**
