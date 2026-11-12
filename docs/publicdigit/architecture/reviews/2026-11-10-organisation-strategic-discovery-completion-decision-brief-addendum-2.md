# Addendum 2 to the Organisation decision brief: refined Q-0…Q-3 and the `users.region` finding

| | |
|---|---|
| **Status** | **Decision preparation and architectural finding.** Records no ruling; amends no baseline; authorises nothing. Not independently reviewed (Claude family). |
| **Base** | the brief and `./2026-11-10-organisation-strategic-discovery-completion-decision-brief-addendum-1.md` |
| **Commission** | "Complete Organisation Strategic Discovery: Voter Relationships, Membership Belonging and Organisational Capacities", Principal, 2026-11-10. The `users.region` **investigation is authorised; no correction is**. |
| **Rulings** | The Principal accepted the commissioning note, whose §7 recommends: Q-0 retain the existing statuses; Q-1 accept; Q-2 B refined; Q-3 A, no HR model. **Per the same note ("do not record new rulings … without my explicit decision"), nothing is written into the decision record.** One instruction ("record them") would do it. |
| **Placement** | `php scripts/doc-placement.php --scope=product-specific --maturity=research --domain=publicdigit` → `docs/publicdigit` (exit 0) |

**Labels:** **FACT** · **RAT** · **WD** · **APP** · **P** · **REC** · **UNKNOWN** · **PRINCIPAL**.

## 1. Q-0: status table (confirmation ≠ ratification)
| Decision | Status in the record | What a confirmation does |
|---|---|---|
| D-93, D-96, D-73, D-75 (and other ARB acts) | **RAT** | nothing; not reopened |
| D-106 | accepted act (eight §13 conclusions) | nothing |
| D-100, D-101 | Principal rulings, **WD** (DR §11 header) | none reopened |
| D-102 | accepted working direction, **WD** | — |
| D-103…D-105, D-107…D-110 | Principal rulings, **WD** | **confirms that none is withdrawn; the status stays WD** |
| D-111 | accepted working architecture direction, **WD** | as above |

**No item becomes ratified through a confirmation.** Ratification would be a separate act, as for D-93 and D-106.

## 2. Q-1: standing voter relationship, refined

### 2.1 Scope correction
- **The standing voter relationship (D-109) is for election-only organisations.**
- **In membership-mode organisations there is no separate organisational voter list.** Election evaluates Membership's facts directly through the ratified query contract (C4: predicates at `asOf` and τ, CR-01), under the eligibility rules set by the organisation's competent body (DD-11; `PBDIGIT-336`).
- A second list there would duplicate membership truth (D-103 spirit).
- So "Membership does not automatically establish voter-list inclusion" holds because, in membership mode, **no voter list is needed**: Election reads Membership facts.

### 2.2 Contract (P)
| | Organisation (election-only) | Election |
|---|---|---|
| owns | the standing voter relationship (who it regards as its voters), with categories and its basis | the electorate per election, its snapshot and basis, eligibility, entitlement, rights issuance |
| authority | the organisation's competent body under its rules (UNKNOWN per organisation) | Election's rules as set by the competent body |
| exchange | answers "voter relationships as known at τ" (T-8) | reads at its cut-off; may instead rely on a **certified roll** from an external competent authority as its own evidence |
| never | decides eligibility | changes the organisation's list |

**Invariants V-1…V-4** (addendum 1) stand. **O-8 stays open** (no units, but regional posts).

## 3. Q-2: membership and unit belonging, refined by constitutional type
**Fixed in every type:**
- **Membership status changes only by Membership's own acts** (Admit, End, …, with authority evidence; RA).
- **Belonging changes only by Organisation's acts** (D-79).
- **No automatic change crosses between them** (CR-01: Organisation never interprets Membership facts; and conversely).
- Where a rule links them, **a decision by the competent authority** makes the link.

| | (1) Unit belonging is a **condition of admission** | (2) Unit belonging is a **continuing condition** | (3) Unit belonging is an **administrative placement** |
|---|---|---|---|
| Membership decides | the admission, **which names the unit** (one act, D-79 "an admission naming the unit") | the admission; later, whether to suspend or end when the condition fails | the admission |
| Organisation decides | the belonging, from the same act | the belonging over time | the placement, when it decides |
| effective member when | the admission is decided (the unit is part of it) | as (1) or (3), per rules | at admission |
| placement pending | **no such state:** without a unit there is no admission yet (an application stays pending) | — | "**unplaced**": an Organisation work state; membership is complete |
| belonging ends (unit dissolved, no successor; removal) | successor and fallback rules (D-13…D-19, WD) apply; the condition was met at admission | "**condition not met**": an Organisation/Governance finding; **membership unchanged until the competent authority decides** (no automatic End) | "unplaced" again |
| rules change | applies to admissions **after** the effective date | from the effective date: existing members not meeting the condition get the finding; no retroactive invalidity (D-6) | — |
| history | admission and belonging histories kept separately; corrections forward | same | same |

**Display states are Organisation's ("unplaced", "condition not met") and never a Membership status.** That prevents "awaiting unit" from contradicting the rules.

**Which type each organisation has comes from its registered rules (RuleCopy).** NRNA: **UNKNOWN**.

**REC: Option B with this three-type refinement.**

## 4. Q-3: capacities, refined
**Vocabulary (P):**
- **Worker:** the organisation's own recorded relationship. **It is not proof of an employment contract**; PublicDigit models no employment, salary or hours.
- **Guest:** organisation level.
- **Unit staff** and **unit guest/observer:** unit level, only where the rules recognise them.
- **Appointed officer:** a Governance position.
- **Workspace access:** D-104.

**Scenarios added to the seven of addendum 1:**
| # | Case | Facts | Note |
|---|---|---|---|
| 8 | a member who also works for the organisation | Membership (member) + Worker | independent; ending work ends neither membership nor belonging |
| 9 | unit staff who is not a member | unit staff belonging (a non-membership-dependent capacity, §50.4) | belonging without membership is valid where the capacity does not depend on Membership |
| 10 | an officer who is also a Worker | a Governance position + Worker | independent; losing the position does not end the Worker relationship |
| 11 | a former Worker | the Worker affiliation ended, with basis | history kept; no effect on other facts |

**Rule (P):**
- **no automatic derivation.**
- Where an organisation's rules require two facts to go together (for example "unit staff must be Workers"), **the competent authority records both, by two explicit acts**; the platform may warn, never create.

**REC: A with this vocabulary; no HR model.**

## 5. Finding: `users.region` decides regional ballots (verified in source)

### 5.1 Where it is written (FACT)
| Writer | Meaning of the value |
|---|---|
| `app/Http/Controllers/Auth/RegisterController.php:36` | **required, self-declared** at registration |
| `app/Console/Commands/ImportUsers.php:34, :645, :737` | **written from one organisation's import file** (columns named "region", "area", "zone" or "district") |
| `app/Http/Controllers/UserController.php:361-391` (`update`) | profile update with `region` required. The route `users/update/{id}` is constrained to numeric ids (`routes/user/userRoutes.php:64`) while accounts use UUIDs (`User` `HasUuids`), so it **probably never matches (UNKNOWN, not run)**. **It has no ownership check** (`User::where('id', $id)->first()`): a latent defect if the constraint changes |
| test and demo commands | — |

### 5.2 Where it is read (FACT)
| Reader | Business decision it drives |
|---|---|
| `VoteController.php:363-404` | which regional posts the voter **sees** on the ballot |
| `BallotPreviewController.php:38` | the ballot preview |
| `VoteController.php:1686-1691` → `AssembledAuthoritativeBallotQuery::ballotFor` (`:30`) → `BallotAssemblyService::buildRegionalPosts` (`:62`) | **final-cast validation**: which regional votes are **valid** (VE-01 authoritative ballot) |
| `BallotAssemblyService.php:62-80` | matching: `posts.state_name = trim(users.region)`, plain text; an empty region gets **no regional posts** ("load-bearing") |
| `Domain/Election/Models/ElectionUser.php:195`; `Candidacy.php:192`; `Post.php:120`; `Openion.php:21` | copies and displays |
| Demo flows (`DemoVoteController.php:430-555`) | the same, in demo |

**Not found:** use in `VoterEligibilityService` (eligibility proper). **The effect is on electoral scope** (which posts a voter may vote for), not on whether they vote at all.

### 5.3 Why one platform-wide value is wrong
- **One account, several organisations (D-110):** Ram belongs to Organisation A's district X and Organisation B's district Y. A single `region` can serve only one of them, and one organisation's import can overwrite the value another relies on.
- **Self-declared and address-like:** an address never establishes belonging (D-80); profile data is organisation-local (D-101); data is isolated (D-111).
- **Integrity:** the authoritative ballot, Election's own integrity check, rests on a value Election does not own, set by the person or by another organisation.
- **Free-text match:** `state_name` and `region` are strings with no shared reference, so spelling differences silently remove regional posts.

### 5.4 Violated boundaries
D-80 (address ≠ belonging), D-101 (profile ownership), D-111 (isolation), the Election boundary (RAT: Election decides who votes for what; it cannot delegate that to an unowned, self-declared field).

### 5.5 Recommended strategic contract (P)
1. **A regional post has a constituency**, defined by Election as one or more **organisational units** of that organisation (not a free-text region).
2. **A voter's constituency** for that election is their **effective unit belonging at Election's τ** (Organisation answers "belongings as known at τ", T-8; §50 effective belonging).
3. **Election decides** which regional posts each voter may vote for, from (1) and (2), and records it with the electorate.
4. **Residence** may matter **only** where the organisation's rules adopt a residence rule; even then it produces a **belonging** through the organisation's act (D-79), never directly.
5. **Election-only organisations without units:** O-8. Either an organisation-recorded scope on the voter relationship, or a certified roll that states constituencies (Election's evidence).

**Is unit belonging sufficient?**
- Yes for organisations with units, including residence-based constitutions via D-79.
- **Not** for unit-less election-only organisations (O-8).

**Consequences:** election ballot assembly, preview and final-cast validation would move from `users.region` to Election's constituency decision. That is **design work for Election**, next to `PBDIGIT-336`. **No correction is authorised.** REC: open a backlog item (requires permission).

## 6. Tactical implications (after the rulings)
- **§50 Person belonging:**
  - the three constitutional types become a rule-driven **link policy** between admission and belonging;
  - Organisation display states "unplaced" and "condition not met";
  - no automatic Membership change.
- **People register:**
  - Worker, Guest at organisation level;
  - the voter relationship standing, election-only, with categories;
  - O-8 scope pending;
  - no derivation.
- **Election (its own design):**
  - constituency per regional post (units);
  - the voter's constituency from belonging at τ;
  - the electorate snapshot;
  - the certified-roll path.
- **Not prescribed:** schemas, classes, event sourcing, locks.

## 7. Still UNKNOWN
- NRNA's constitutional type for unit belonging;
- who in NRNA maintains voter lists;
- whether `UserController@update` is reachable (not run);
- how many existing posts or users have mismatched `state_name`/`region` (data not queried);
- O-8 real cases.

## 8. Principal decisions requested
| # | Ruling | REC |
|---|---|---|
| — | "record them": write Q-0…Q-3 as accepted in the note into the decision record (with Q-1's scope correction, §2.1, and Q-2's three types, §3) | yes |
| permission | backlog item: "regional ballot scope comes from the central `users.region`" (§5), next to `PBDIGIT-336`, investigation only | yes |
| permission | backlog item: `UserController@update` has no ownership check (latent) | yes |

## Reconciliation note (2026-11-10, after D-114…D-121; append-only)
**§3 (Q-2) is superseded by D-114, D-120, D-121.**
- Every effective member belongs to a geographic unit on every date. Where no lower unit applies, that is the **root unit**, which exists while any membership coverage exists (D-120). "Central" admission is admission into the root unit.
- Type 3's "unplaced" state **does not exist**. "Awaiting placement" means only that no rule or act places the member below their fallback unit (D-121).
- Types 1 and 2 survive only as **which act or rule moves a member below the root** (D-79, D-116, D-117), not as membership conditions.

**Unchanged:**
- Membership status changes only by Membership's acts;
- belonging only by Organisation's acts;
- no automatic crossing.

D-118 (one geographic belonging by default) applies to §4's capacities: wings and staff or guest capacities are additional.
