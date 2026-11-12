# Election: post districts and regional voting authority (Principal decision brief)

| | |
|---|---|
| **Status** | **Strategic discovery and decision brief.** Decides nothing; records no ruling; creates no backlog item; changes no code or other document. Not independently reviewed (Claude family). |
| **Commission** | "Strategic DDD Discovery: Election Constituencies and Regional Voting Authority", Principal, 2026-11-10. |
| **Not done (by instruction)** | Q-0…Q-3 of addendum 2 are **not** recorded (the note makes recording a separate explicit instruction); the two backlog items are **not** created (separate authorisation). |
| **Placement** | `php scripts/doc-placement.php --scope=product-specific --maturity=research --domain=publicdigit` → `docs/publicdigit` (exit 0) |
| **Git state** | branch `election-flow-updated`, HEAD `754144edb`; no code changed. |

**Labels:** **FACT** (code and schema read 2026-11-10; nothing run) · **RAT** · **WD** · **APP** · **P** · **REC** · **UNKNOWN** · **PRINCIPAL**.

## 1. Executive summary
- Today, which regional posts a person may vote for is decided by one field on their **central account**, `users.region`. The person types it at registration, an operator's import can overwrite it, and every organisation's election reads it.
- That field also decides which regional votes are **valid** at the final cast.
- Nothing records, per election, on what basis a voter received which regional posts.

**Recommended model:**
- **Election** defines, for each regional post, its **post district**: a set of the organisation's **units**.
- At the electorate cut-off, Election asks **Organisation** who belongs where. That query (R-5, `EffectiveMembersAsAt(scope, asOf, τ)`) is already accepted as a working design (D-32).
- Election decides each voter's regional ballot and **keeps that decision in its snapshot**.
- An organisation's **address data never decides it**.
- An organisation that elects regional representatives **has** divisions. They are its units, created by individual acts, by an authorised import, or by a residence rule its constitution adopts (D-79).
- An externally **certified roll** may serve as Election's evidence instead.

## 2. Verified current state
| # | Finding | Evidence | Status |
|---|---|---|---|
| C-1 | `users.region` is **required and self-declared** at registration | `RegisterController.php:36` | FACT |
| C-2 | the operator's `ImportUsers` command writes it from an organisation's file (columns "region", "area", "zone", "district") | `ImportUsers.php:34, :645, :737` | FACT; run by an operator, not by organisations themselves |
| C-3 | **the UI's election-only import does not set it** (`firstOrCreate` sets name, password, `organisation_id`, verified flag); such voters have an empty region and therefore **see no regional posts** (empty = none, "load-bearing") | `VoterImportService.php:268-278`; `BallotAssemblyService.php:58-66` | FACT (code); runtime not checked |
| C-4 | a post's region is a **free-text** `posts.state_name`; no unit or geography reference | schema `posts(…, is_national_wide, state_name, …)` | FACT |
| C-5 | matching is `posts.state_name = trim(users.region)`, string equality | `BallotAssemblyService.php:62-80` | FACT |
| C-6 | read for the ballot page, the preview and the **final-cast validation** (VE-01 authoritative ballot) | `VoteController.php:363-404, :1686-1691`; `AssembledAuthoritativeBallotQuery.php:30`; `BallotPreviewController.php:38` | FACT |
| C-7 | read **live**, separately at display and at cast; a change in between changes the authoritative ballot | as C-6 | FACT (code path) |
| C-8 | demo flows use the same field | `DemoVoteController.php:430-555` | FACT |
| C-9 | **the electorate keeps no regional basis:** `election_memberships` has no district or region field; votes and results (correctly) none | schema | FACT |
| C-10 | no other path decides regional scope; eligibility services do not read `region` | search of `app/` | FACT (search), not exhaustive proof |
| C-11 | `UserController@update` (sets `region`) has no ownership check; its route is numeric-only while ids are UUIDs, so it is probably unreachable | `UserController.php:361-391`; `routes/user/userRoutes.php:64` | FACT (code); reachability UNKNOWN |

**Consequence:** a held election cannot be explained afterwards. Why Ram had the Bayern post on his ballot is nowhere recorded, and a later change of `region` makes the question unanswerable.

## 3. Vocabulary and owners
| Term | Meaning | Owner | Never confused with |
|---|---|---|---|
| **Residence** | where a person lives (an address as supplied) | the organisation's person profile (D-101) | belonging; district |
| **Organisation profile** | the organisation's own record of the person | Organisation (D-101) | identity |
| **Affiliation** | the relationship with the organisation, in a capacity | Organisation (D-101) | belonging |
| **Unit** | a place in the organisation's structure where people **belong** | Organisation | an organ; an official division |
| **Organ** | a body that decides (committee, convention) | Governance | a unit |
| **Unit belonging** | a person's place in a unit, by an act (D-79) | Organisation | residence; membership |
| **Post district** (proposed term) | the set of units whose members may vote for a regional post **in this election** | **Election** | a **public electoral constituency** (an official territorial scheme; Geography's question 2, not represented) |
| **Regional post** | a post whose voters are limited to a post district | Election | — |
| **Electorate** | who may vote in this election, with each voter's regional ballot, fixed at the cut-off | Election (RAT boundary) | a voter list (Organisation, D-109) |
| **Eligibility / entitlement** | whether a person qualifies; the right to cast | Election (RAT) | membership; belonging |
| **Certified roll** | an electorate list certified by an external competent authority for one election | Election's **evidence** | the organisation's voter list |
| **Cut-off and snapshot** | the instant T up to which facts count (D-42); the fixed electorate with its basis | Election | later changes |

## 4. Model for organisations with units
**Proposed contract (P; builds on R-5, D-32 WD):**
1. Election defines each regional post's **post district** as one or more units of that organisation. The unit tree is "in the unit or below", as in R-5.
2. At cut-off T, Election calls `EffectiveMembersAsAt(unit, asOf, τ = T)` for each district (Organisation answers from belonging and the tree as known at τ). It combines the answer with its eligibility basis: Membership C4 in membership mode, or the voter relationship in election-only mode (D-109).
3. **Election decides each voter's regional ballot** (the set of regional posts) and stores it in the snapshot with τ and the version markers.
4. Voting enforces the authoritative ballot; the ballot carries no identity (VO-1, D-44).

| # | Scenario | Who decides / who supplies evidence | Outcome |
|---|---|---|---|
| 1 | belongs to one unit | Election / Organisation (R-5) | that district's posts |
| 2 | belongs to several units (a wing and a district; or two districts) | **Election's rules decide** whether several regional ballots are allowed; Organisation supplies both belongings | the platform has **no default**: if districts overlap and the rules are silent, Election **does not fix** the electorate until the gap is decided (the D-65 pattern). Single-geographic-unit (V-24) is [PK], not a platform rule |
| 3 | changes unit before the cut-off | Organisation (the act) / Election (reads at T) | the new unit |
| 4 | changes unit after the cut-off | — | snapshot unchanged (D-42; §22 `:266`) |
| 5 | unit dissolved or reorganised **before** T | Organisation (successor and fallback rules D-13…D-19; pending restructuring blocks: D-41, D-43) | belonging resolved through successors; the district definition is resolved the same way at T |
| 5b | **after** T | — | snapshot keeps the units as at T |
| 6 | unplaced member | constitutional type (addendum 2 §3): type 1 cannot occur; type 3 "unplaced" | national posts only; **the organisation's rules must say** whether that is acceptable; Election warns before fixing |
| 7 | residence-based qualification | the organisation's **adopted residence rule** (D-79) creates belongings | same model; residence never decides directly (D-80) |
| 8 | a district of several units | Election (the definition) | union of their effective members |
| 9 | the district changes between elections | Election (per-election definition) | each election keeps its own; history intact |

## 5. O-8: organisations without units
| Option | Authority | Where the fact lives | Election consumes | Evidence | Changes | Snapshot | Works without units? | Risk |
|---|---|---|---|---|---|---|---|---|
| **A. Scope attribute on the standing voter relationship** | the organisation's competent body | Organisation (voter relationship) | the scope at T | basis on each change | forward, with basis | stored | yes | a second, parallel notion of "where a person belongs" beside units |
| **B. Certified roll** | an external competent authority | Election (evidence) | the roll as given | the certification | a new roll | stored | yes | only where such an authority exists |
| **C. Per-election district assignment** | the organisation's competent body, per election | Election | its own record | a decision per election | per election | stored | yes | Election records an organisational relationship; repeated per election |
| **D. The divisions are units** | the organisation's competent body | Organisation (units + belonging, by acts or an authorised import as basis) | R-5 | allocation acts | forward (D-79) | R-5 at T | **yes**: the structure is minimal, a root plus its divisions | needs the business acceptance that "a division whose people vote for a post is a unit" |

**Analysis:**
- An organisation that elects **regional** representatives has, by that very fact, divisions of its people.
- The accepted baseline defines a unit as "a place in the organisation's structure where people belong" (baseline §3). So a voter division is a unit.
- **D** therefore applies the general model with no second concept. **A** is D under another name, with a parallel concept.
- **C** places organisational facts in Election.
- **B** is legitimately different: external evidence.

**REC: D for divisions the organisation itself defines; B accepted where an external authority certifies the roll; A and C not adopted.**
- **PRINCIPAL:** accept or reject the claim "a voter division is a unit".
- **FACT relevant to today:** NRNA-style imports with a "region" column (C-2) are, under D, an **authorised import creating belongings**, written to the organisation's own units instead of the central account.

## 6. Temporal and snapshot analysis
| Question | Answer | Source |
|---|---|---|
| when is the district evaluated? | at the electorate cut-off T, with knowledge boundary τ = T | D-42 (WD); §38 `:537` (P) |
| is it fixed then? | yes, stored in the snapshot | §22 `:266` (P) |
| belonging changes after T? | no effect | D-42 |
| unit renamed, merged or dissolved after T? | the snapshot keeps the units as at T | D-13 spirit (history kept) |
| can a later correction change a cast ballot's validity? | **no.** Correcting a held election belongs to Contestation and Adjudication (§22 `:266`; D-67) | P |
| evidence kept | district definitions, τ, version markers, each voter's regional ballot (identified, in Election; never in Voting) | P |
| restructuring pending at T | the electorate is not fixed until settled | D-41, D-43 (WD) |

**Anonymity note (domain):**
- A regional ballot style shows the voter's district.
- Under D-44, credentials must authorise the right ballot style without identity, so **the anonymity set is the district's voters**.
- Very small districts weaken secrecy (with 3 voters, results come close to revealing choices).
- **Whether a minimum district size or result aggregation applies is an Election rule question** (UNKNOWN; not decided here). VO-1 itself is untouched.

## 7. Cross-context consistency
| Context | Role | Kept? |
|---|---|---|
| Identity | none in districts; an account never establishes belonging | yes (D-102, D-110) |
| Organisation | units, belonging, answering R-5 at τ; the person's address only as a profile fact | yes (D-101, D-106) |
| Membership | C4 facts for eligibility in membership mode; never reads belonging | yes (CR-01) |
| Governance | sets the election rules (DD-11), decides restructurings | yes |
| Election | post districts, the electorate, regional ballots, the snapshot | yes (RAT boundary) |
| Voting | enforces the authoritative ballot; no identity | yes (VO-1) |
| Finance | no role in entitlement | yes |

## 8. Options and trade-offs
| Model | Authority correct | Fits ratified records | Several organisations | Without units | Reproducible | Resists unauthorised change | Clarity | Solo cost | Migration |
|---|---|---|---|---|---|---|---|---|---|
| M0 `users.region` (today) | **no** | **no** (D-80, D-101, D-111, Election boundary) | **no** | partly | **no** | **no** | low | none | — |
| **M1 districts = units via R-5** | yes | yes (D-32, D-42) | yes | with D | yes | yes | high | medium | define districts per post; units and belongings from existing data |
| M2 scope on the voter relationship (A) | yes | yes | yes | yes | yes | yes | medium (two concepts) | medium | regions to scopes |
| M3 certified roll (B) | yes | yes | yes | yes | yes | yes | high | low | none |
| M4 per-election assignment (C) | partly | blurs Organisation and Election | yes | yes | yes | yes | low | high | — |

## 9. Recommended strategic model (REC)
**M1 as the general model, with O-8 option D and certified rolls (B) as Election evidence.** `users.region` is not a source of voting scope.

## 10. Principal decisions requested
| # | Proposition | Why it matters | Alternatives → consequence | REC | Acceptance does **not** authorise |
|---|---|---|---|---|---|
| **E-1** | "A central account field never determines a voter's regional ballot; Election does, from the organisation's facts at its cut-off." | today's ballots depend on an unowned, self-declared value | keep M0 → cross-organisation interference and unexplainable elections | accept | any code change |
| **E-2** | "Each regional post has a post district: a set of the organisation's units, defined by Election per election; the voter's regional ballot comes from effective belonging at T (R-5) and is stored in the snapshot." | the general model | M2, M4 | accept | schemas, migration |
| **E-3** | "A division whose people vote for a regional post is a unit of the organisation (O-8, option D); an externally certified roll may replace it as Election's evidence." | organisations without a formal structure | A (parallel concept), C (Election holds organisational facts) | accept | creating units for any real organisation |
| **E-4** | "If posts' districts overlap for a voter and the organisation's rules do not say whether several regional ballots are allowed, Election does not fix the electorate until the gap is decided." | no invented default | a platform default (one or many) | accept | — |
| E-5 | Minimum district size or result aggregation for ballot secrecy | anonymity in small districts | rule / no rule / later | defer to Election's design | — |

**Still pending from before (separate):** "record them" (Q-0…Q-3); the two backlog permissions.

## 11. Tactical consequences (analysis only)
- **Election:**
  - a post district as part of the post's definition per election;
  - the snapshot holding τ, version markers and each voter's regional ballot;
  - the authoritative ballot (VE-01) derived **from the snapshot**, not from a live account field, which also removes the display/cast inconsistency (C-7);
  - the certified-roll path as evidence.
- **Organisation:** R-5 answered from belonging at τ (§38.5, §50); import with a division column as an **allocation basis** (people register, §50).
- **Voting:** credentials per ballot style (D-44); no identity.
- **Import and correction:** corrections after T go to Contestation and Adjudication, not to the snapshot.
- **Migration (later, design):** existing `state_name` values and the `region` data would need mapping to units per organisation. **Data not queried; mismatch counts UNKNOWN.**
- **Established invariants:** E-1…E-4 (if accepted); D-42; VO-1. **Proposed patterns:** snapshot contents; ballot derived from the snapshot. **Open:** E-5; multi-district rules; migration.

## 12. Unknowns and evidence gaps
- NRNA's posts and their `state_name` values; how many voters have an empty or mismatching `region` (data not queried);
- NRNA's constitution (constitutional type; residence rules; districts);
- whether any organisation uses certified rolls;
- whether `UserController@update` is reachable;
- the size distribution of real districts (for E-5).
