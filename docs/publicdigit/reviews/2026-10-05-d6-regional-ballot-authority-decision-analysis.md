# D6 — Regional ballot authority: decision analysis (PBDIGIT-236)

**Date:** 2026-10-05 · **Status:** ✅ **ANALYSIS APPROVED AS DECISION BASIS (principal-architect review, 2026-10-05)** · recommended option **C** · 🟡 **formal PO / ARB decision required.** No implementation, no migration, no seeding, no post lock, no data change.
**Story:** `PBDIGIT-236` · **Consumed by:** VE-01 / `PBDIGIT-237` (through the `AuthoritativeBallotQuery` port)
**Prepared by:** `claude-code-session:9e4925bb-4151-4185-9e9c-5831184e54eb` (self-declared, not attestable). Engineering analyses and recommends; the PO / ARB decides.
**Evidence labels:** [S] source directly inspected · [I] inference.

> **Recommendation ≠ decision.** Everything below labelled *recommended* is engineering / principal-architect advice. Option C is **not decided and not implemented**: `ElectionMembership` has no region today, no lock exists, and the runtime source of the regional ballot is still `users.region` (§2).

---

## 1. The question

> **Which fact decides the regional part of a voter's ballot in a real election, and when is that fact fixed?**

Today the answer is "the voter's current `users.region`, read whenever a ballot is assembled". The question is whether that is the constitutional rule, or whether regional eligibility should be a different, more stable fact.

## 2. Facts (today)

| # | Fact | Source |
|---|---|---|
| F1 | The regional ballot is `posts WHERE election_id = E AND is_national_wide = false AND state_name = trim(users.region)`. An empty region returns no regional posts (documented as load-bearing). | `BallotAssemblyService::buildRegionalPosts()` [S] |
| F2 | It is called in two places: the ballot page (`VoteController::create`, step 3) and the ballot preview (`BallotPreviewController`). The final cast (`store`) never assembles the ballot — that is VE-01. | [S] |
| F3 | `users.region` is free text, nullable, user-level (one value across all elections and organisations). | migrations `000002`, `2026_04_06_200001` [S] |
| F4 | `users.region` is written by registration (required), `ImportUsers` command, `UserController` bulk import, Google / social login (`''`), `OrganisationUserImportService` (`''`). | [S] |
| F5 | The profile update (`UserController::update`) **validates** `region` as required but does **not** persist it (`forceFill` omits it); its route only matches numeric ids (`where('id','[0-9]+')`) while user ids are UUIDs. A voter cannot change their own region today. | [S] |
| F6 | `posts.state_name` is free text typed by election officers (`PostForm.vue`); matching is exact (case-sensitive) after trimming the voter side only. | `PostManagementController`, `PostForm.vue` [S] |
| F7 | Post create / update / delete are guarded by `managePosts` → chief / deputy of the election, **with no lifecycle lock** — a post's region can be edited while voting is active. | `ElectionPolicy::managePosts()`, `PostManagementController` [S] |
| F8 | `ElectionMembership` (the persisted ElectionVoter, unique user × election) has no region; it has a `metadata` JSON column. Voter import (`VoterImportService`) does not read a region. | [S] |
| F9 | The Geography context has a hierarchical `geo_administrative_units` model (`admin_level` 1 province/state … 4 ward, materialized `path`); `users.residence_geo_unit_id` and `members.residence_geo_unit_id` reference it. Ballot assembly does not use it; posts have no geo-unit reference. | migrations, `SessionMemberGeoPathProvider` [S] |
| F10 | Elections have a voter source strategy; `election_only` = imported voter registry. Voter admission (C2) is locked from `ReadyForVoting` (precedent for lifecycle-locked voter facts). | `VoterSourceStrategy`, PBDIGIT-213 C2 [S] |

**[I] Practical mutation risk today:** because F5 makes self-service changes impossible, a voter's region changes mainly through imports and administrative actions — which can run during voting. F6 and F7 add a second, independent instability on the **post** side.

## 3. Strategic DDD

| Question | Answer |
|---|---|
| Which bounded context owns regional ballot eligibility? | **Elections** — *which posts this voter may vote on in this election* is an election-specific eligibility fact. |
| Is another context affected? | **Membership / Geography** may *supply* a residence fact (membership-based elections), but do not own the election's eligibility decision. Identity (`users`) is today's de-facto source, not an owner. |
| Published-Language interaction? | Only for option D (consuming a Geography identity) and, optionally, C when seeded from Membership residence. |
| Does ownership change? | Today the fact lives on `users` (identity); options C / D move the *authoritative* fact into Elections. |
| Context map change? | A / B / C: no. D: yes (Elections ← Geography dependency for ballot assembly). |

**Model integrity check (new dimension or not?):** "region for this election" is **not** a new dimension — it is the existing *region* dimension, currently **overloaded**: one user-level value serves residence, profile display and ballot eligibility for every election. Options C / D separate the overloaded use; B adds a time dimension; A keeps the overload.

## 4. The options

| | A — current `users.region` at use | B — snapshot at ballot issue / code verification | C — election-specific region on the ElectionVoter | D — geo-unit model |
|---|---|---|---|---|
| **Domain meaning** | "where the person says they live, now" | "where the person lived when they entered the voting session" | "the region in which this voter is registered **for this election**" | "the administrative unit of residence; posts bound to units in a hierarchy" |
| **Consistency within one session** | ✗ can change between ballot page and final cast | ✓ fixed for the session | ✓ fixed (if locked) | ✓ fixed (if locked) |
| **Consistency across voters of one election** | ✗ each voter evaluated at a different time | ✗ evaluated at each voter's session start | ✓ one register, one cut-off | ✓ one register, one cut-off |
| **Temporal semantics** | none | per voter, per session; regenerated code / new slug → new snapshot? (to define) | per election; fixed at `ReadyForVoting` (same precedent as admission, F10) | per election if snapshotted; otherwise as A |
| **Election-specific** | ✗ | partially (copied per election session) | ✓ | ✗ unless also snapshotted per election |
| **Voter UX** | ballot may silently change | stable during the session | stable; officers see and correct the region in the voter register before voting | stable; richer hierarchy (e.g. district voter sees province post) |
| **Officer UX / auditability** | invisible, not reviewable per election | invisible until the voter starts | visible and reviewable in the voter register before lock; correctable before `ReadyForVoting` | needs geo-unit maintenance per organisation |
| **Anonymity** | no change | snapshot is voter-side (code / slug); no ballot link — must not be stored next to ballot data | voter-side (membership); no ballot link | voter-side; **finer units ⇒ smaller anonymity sets** (a ward post with few voters makes ballots easier to attribute) |
| **Migration impact** | none | new voter-side field (codes / voter_slugs) | new column on `election_memberships` (+ seeding from `users.region` for existing memberships — a voter-side data step that needs its own approval) | `posts.geo_unit_id`, voter unit source, hierarchy matching, data seeding of geo units |
| **Operational complexity** | none | low–medium (snapshot lifecycle with code regeneration, slug expiry) | low–medium (import / assign must set it; register UI shows it) | high (Geography data quality per organisation, country-specific levels) |
| **Lifecycle fit** | none | none (time-of-session, not election phase) | ✓ matches setup → `ReadyForVoting` lock | possible, larger |
| **Fit with `ElectionMembership`** | unrelated | unrelated | ✓ natural attribute of the ElectionVoter | indirect |
| **Impact on `BallotAssemblyService`** | none | takes the snapshot instead of `users.region` | takes the membership region instead of `users.region` (signature: ElectionVoter, not raw string) | rewritten matching (hierarchical) |
| **Impact on VE-01** | adapter reads `users.region` (current state) | adapter reads the snapshot | adapter reads the membership region | adapter reads unit + hierarchy |
| **Future elections** | inherits every profile / import change | — | each election has its own register; earlier elections stay reproducible | multi-level elections possible |

**Post-side note (all options):** F6 / F7 mean that freezing the *voter* side alone is insufficient — a post's `state_name`, `is_national_wide` and existence can still change during voting. Whichever option is chosen, a **mandatory companion rule** applies: the regional definition of posts is frozen from `ReadyForVoting` (same lifecycle point). It is independent of the option choice and is delivered as a **separate implementation story** (Q5).

## 5. Recommendation

**Recommended: C — election-specific regional ballot eligibility stored on `ElectionMembership` (the ElectionVoter) and frozen at `ReadyForVoting`, with the mandatory post-side companion rule.** Under C, `ElectionMembership` becomes the authoritative election-specific source of regional ballot eligibility. Reasons:

1. **Ownership** (§3): ballot eligibility belongs to Elections; C puts the authoritative fact there and ends the overload of `users.region`.
2. **One register, one cut-off**: every voter of an election is evaluated against the same frozen register — the same model the platform already uses for voter admission (C2 lock at `ReadyForVoting`).
3. **B is subsumed**: a per-election lock gives the session stability of B without per-session snapshot lifecycle (code regeneration, slug expiry) and without inconsistency between voters.
4. **Reviewable before voting**: officers can see and correct each voter's election region in the voter register; today's silent mismatches (case, spelling, empty region from social login → no regional posts) become visible *before* the election opens.
5. **D stays future architecture**: a GeoUnit can later replace the regional representation behind the ballot-authority boundary without changing VE-01's constitutional validation model. The `AuthoritativeBallotQuery` port remains; its domain input / value object may evolve from region to GeoUnit / hierarchy. GeoUnit support is **not** part of D6; choosing D now would be scope expansion.
6. **VE-01 is unaffected**: VE-01 keeps reading today's `users.region` through the port (characterization source) until C is implemented; switching is an adapter change.

**Role of `users.region` under C:** (1) interim source for the current VE-01 characterization; (2) input for future seeding of the election-specific region; (3) historical evidence where no election-specific region exists (past elections, D4). It is **not** the future runtime constitutional authority. **A** is therefore acceptable only as the documented interim source — not as the rule.

### 5a. Principle and invariant (for the decision record)

> **A voter's regional ballot eligibility is an election-owned fact, frozen before voting begins; a user's current residence / profile region is merely an input used to establish that fact, not its runtime authority.**

> **Invariant:** once an election reaches `ReadyForVoting`, the regional eligibility of its voters **and** the regional definition of its posts (`state_name`, `is_national_wide`, existence / deletion state) are immutable for that election.

### 5b. VE-01 is not delayed

```
VE-01 now:      CastContext → AuthoritativeBallotQuery → users.region (interim adapter)        → AuthoritativeBallot → BallotValidator → ValidatedBallot → CAS → Vote
after D6 / C:   CastContext → AuthoritativeBallotQuery → ElectionMembership region (authority) → AuthoritativeBallot → BallotValidator → ValidatedBallot → CAS → Vote
```

The constitutional boundary does not change; only the authority adapter does.

### 5c. Recommended decision (principal-architect review)

| Decision | Recommendation |
|---|---|
| Regional authority | election-specific, on `ElectionMembership` |
| Freeze point | `ReadyForVoting` |
| Current `users.region` | interim source (VE-01 characterization) · input for seeding · historical evidence — not the runtime authority |
| Election region values | controlled election-specific values, not free text |
| Missing region | **explicit election policy; no silent fallback** (Q2) |
| Post region | `state_name`, `is_national_wide`, existence / deletion frozen from `ReadyForVoting` together with voter regional eligibility — mandatory companion rule, separate implementation story |
| GeoUnit | future evolution, not part of D6 implementation |
| Historical elections | continue to use `users.region` evidence where that is all that exists |
| VE-01 | continues independently; does not wait for D6 implementation |

## 6. Decision table Q1–Q6 (formal PO / ARB answers pending)

| # | Question | Engineering recommendation (if C is chosen) | PO / ARB decision |
|---|---|---|---|
| Q1 | How is the election region set? | Seeded from `users.region` when a voter is assigned / imported into the election; chosen from the election's controlled list of regions (Q4); editable by the election's chief / deputy until `ReadyForVoting`; frozen afterwards. | ⏳ pending |
| Q2 | Voter with **no** election region? | **No engineering default — business-policy decision.** A missing value must not silently become an authorization decision: the election's policy states explicitly whether such a voter gets *national posts only* or is *not admitted*. Today's implicit fallback (empty region ⇒ no regional posts) is not adopted. | ⏳ pending |
| Q3 | Correction after `ReadyForVoting`? | Not allowed (same rule as the voter admission policy). Any correction procedure would be a separate decision. | ⏳ pending |
| Q4 | Matching rule | The election region is one of a **controlled, election-specific list** — the `state_name` values of the election's regional posts; no free text on the voter side; exact match. | ⏳ pending |
| Q5 | Post-side companion rule | **Mandatory:** `state_name`, `is_national_wide` and existence / deletion of the election's posts frozen from `ReadyForVoting`. Separate implementation story. | ⏳ pending |
| Q6 | Existing memberships | One-time seeding from `users.region` — a voter-side data step, separately authorised; ballot-side and historical election data untouched. | ⏳ pending |

## 7. What a decision would trigger (not now)

- Update `PBDIGIT-236` with the decision; amend the VE-01 proposal (§3 "current ballot assembly authority" → the decided authority).
- If C: a new story for the schema + register + lock (`election_memberships` column, import / assign, voter-register UI, lifecycle lock), and a story for the post-side lock (Q5). Each under its own RED → GREEN gate.
- ADR: record D6 as a decision (new ADR or amendment of the voting-authorization ADR's ballot-integrity section).
- D4 interpretation: F8b keeps using `users.region` for historical data (C does not exist for past elections).

## 8. Related observations (not part of D6; classified)

| Observation | Class |
|---|---|
| F5 — profile update validates but never saves `region`; its route cannot match UUID ids | Engineering (defect candidate, low; region is safer *because* of it) |
| F7 — posts editable during voting, no lifecycle lock | Tactical architecture — addressed by the mandatory companion rule (Q5) if D6 is decided |
| Exact case-sensitive matching of two free-text fields (F1, F6) | Engineering (resolved by Q4 under C) |

None of these is acted on; each becomes a backlog item only on PO instruction.

## 9. Gate status

| Gate | Status |
|---|---|
| D6 analysis | ✅ approved as decision basis (principal-architect review) |
| Recommended option | **C** |
| Formal PO / ARB decision (option C, principle, invariant, Q1–Q6) | 🟡 required |
| D6 implementation · migration · `ElectionMembership` seeding · post-lock implementation · GeoUnit support | ⛔ not authorised |
| Historical election data | untouched (D4 measures only) |
| VE-01 GREEN | independent, separately gated |
| D4.1 | independent, waiting for environment authorisation |

**Traceability:** PBDIGIT-236 · PBDIGIT-237 · VE-01 design proposal §3, §8 · gate report §15–§18 · `BallotAssemblyService.php:60–80` · `UserController.php:361–446` · `ElectionPolicy.php:46–106` · PBDIGIT-213 C2 (admission lock precedent).
