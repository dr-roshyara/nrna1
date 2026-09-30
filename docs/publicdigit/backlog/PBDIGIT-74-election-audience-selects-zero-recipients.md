# PBDIGIT-74 — Election notification selects **zero** recipients where eligible recipients exist

**Type:** Defect (feature) + **business-rule contradiction** · **Epic:** `PBDIGIT-EPIC-05` Member Communication
**Created:** 2026-09-30 · **Found by:** verification of `a72528b29` during the blocker-structure research
**Raised by:** `claude-code-session:84c0f6f6-795e-4c89-a382-733f2c7b7caf` — self-declared, not attestable (`INV-ATTR-1/2`, `G-2`)
**Placement derived:** `doc-placement.php --scope=product-specific --domain=publicdigit` → `docs/publicdigit` (exit 0)

| | |
|---|---|
| **Status** | 🔴 **OPEN — DECISION REQUIRED — NOT AUTHORIZED FOR IMPLEMENTATION.** **Investigation complete. Implementation blocked pending PO/ARB determination of communication eligibility and election-tenancy authority.** Final report: `docs/publicdigit/reviews/2026-09-30-PBDIGIT-74-final-research-report.md`. Coupled to **`PBDIGIT-75`** — must not be implemented independently. ⚠️ The original framing (test/fixture mismatch) is **FALSIFIED**: the defect is **production-reachable** (§10.2). No production code, test, fixture or migration changed. |
| **Business problem** | **Election notification selects zero recipients where eligible recipients are expected.** Every election-audience segment — voters, not-yet-voted, committee, observers — resolves to an empty set in test conditions. |

---

## 1 · ⚠️ Corrected premise — this is NOT a regression

The investigating commission stated that `PBDIGIT-61` left the newsletter suites green, so the current failures represent a change from a verified state. **That premise does not hold for this suite.**

| Evidence | |
|---|---|
| `PBDIGIT-61`'s five suites, named at its own line 90 | `NewsletterCreationTest` · `NewsletterDispatchTest` · `NewsletterBatchJobTest` · `NewsletterKillSwitchTest` · `NewsletterUnsubscribeTest` |
| **`NewsletterAudienceSegmentationTest` is NOT among them** | its 21 tests were never in that ticket's scope, and "25 passed / 45 assertions" cannot include them |
| The test file is **unchanged since 2026-04-18** | only two commits ever: `0bad829f3`, `c2f899b1d`, both 2026-04-18 |
| At 2026-08-07 (`PBDIGIT-61` resolution, commit `a74d4596`) the file already held 21 tests | `git show` |

⇒ **These failures are long-standing, not new.** The suite was never verified, and the 2026-09-16 *"notify voters"* feature (`1181d66e2`) shipped on top of it.

⇒ **The 2026-09-30 join change did NOT cause this.** It added two **LEFT** joins; a left join cannot reduce rows. The row-eliminating predicate is a `where`, and it predates the change.

## 2 · Failure taxonomy — 48 tests · 69 assertions · 6 errors · 9 failures

All 15 are in `NewsletterAudienceSegmentationTest`; `ElectionOfficerNewsletterTest` (12) and `NewsletterCreationTest` (6) pass.

| Family | Count | Signature | Related to this ticket? |
|---|---:|---|---|
| **A · zero recipients** | **4** | `Failed asserting that 0 matches expected N` — `election_voters` (0/2) · `election_not_voted` (0/1) · `election_committee` (0/2) · `election_observers` (0/1) | ✅ **this ticket** |
| **B · audit-log NOT NULL** | 5 | `SQLSTATE[23502]: null value in column "actor_user_id"` | ❌ separate — §6 |
| **C · store/route/rate-limit** | 4 | *"Expected validation exception"*, rate-limit assertions | ❌ unrelated, not investigated |
| **D · duplicate-key fixture** | 1 | `SQLSTATE[23505] uq_user_election_active` | ❌ fixture-shaped, unrelated |
| **E · overlapping segments** | 1 | `0 matches expected 1` | ⚠️ likely family A; not separately traced |

## 3 · Root cause — established, with evidence

**Family A's tests call `resolveAudience()` directly** (not `dispatch()`), so no dispatch machinery is involved.

The election-audience queries filter:

```php
->where('users.organisation_id', $organisation->id)     // NewsletterService, all 7 election audiences
```

But the fixtures produce users owned by a **different** organisation:

| Fact | Evidence |
|---|---|
| `setUp()` creates a fresh org: `$this->organisation = Organisation::factory()->create()` | test `setUp` |
| `User::factory()->create()` assigns `organisation_id` = the **"PublicDigit" platform** org (`firstOrCreate` by slug `publicdigit`) | `UserFactory.php:53` |
| The `ElectionMembership` rows *are* correctly scoped to `$this->organisation` | each Family-A test |

⇒ **The participants exist and are correctly enrolled in the election, but their `users.organisation_id` points at the platform organisation, so the query's org predicate excludes every one of them.** Zero rows, deterministically.

## 4 · What the intended recipient population is supposed to mean — and why it is NOT established

**Reading A — the production predicate is over-restrictive.**
- Election participation is established by `ElectionMembership`, which **carries its own `organisation_id`**; the election-membership join therefore already scopes to the organisation. The extra `users.organisation_id` predicate double-scopes.
- Test `test_non_member_election_participant_receives_newsletter_without_member_id` makes the intent explicit: a user who is **not** an organisation member (asserts `member_id` is **null**) but **is** an election observer **must receive**.
- In **Election-Only mode** participants are linked through `organisation_users`, not `users.organisation_id` — so this predicate would exclude every Election-Only voter (cf. `PBDIGIT-68`, `IERVP-2/3`).

**Reading B — the fixtures are wrong.**
- `UserFactory`'s own docblock: *"CRITICAL: organisation_id is REQUIRED … Manually pass `organisation_id => $org->id` to `create()`"* — the tests did not.
- The active plan asserts the current behaviour is correct: the election queries *"still scope by `users.organisation_id` — i.e. exactly 'all voters of election X'"* (`.claude/plans/encapsulated-hopping-hamster.md`).

## 5 · 🛑 STOP — why the fix is not made here

**The two readings imply different recipient populations, and therefore a different business rule about who may be notified of an election.**

| STOP condition | Triggered because |
|---|---|
| **1** — intended recipient population not unambiguously established | Reading A broadens the population; Reading B keeps it narrow. Both are internally coherent. |
| **2** — fixing requires changing a domain rule | Whether non-organisation-members receive election mail is an entitlement question, adjacent to `PBDIGIT-68`'s durable-entitlement ruling and the Full-Membership vs Election-Only distinction. |
| **6** — existing tests contradict documented behaviour | The test name and assertions require non-members to receive; the active plan records the org-scoping as intended and correct. |

⛔ **No production change. No test change.** Per the commission: *"If the contract is wrong, STOP and escalate rather than silently changing it."*

## 6 · Family B (`actor_user_id`) — separate, and **test-only**

`NewsletterService::dispatch()` writes `'actor_user_id' => auth()->id()` (`:444`) into a **NOT NULL** column (`2026_04_06_090744_create_newsletter_audit_logs_table.php:18`). The tests call `dispatch()` without an authenticated user → `null` → constraint violation.

**Production reachability: NO — established, not assumed.** `NewsletterService` appears in **no** file under `app/Console/` or `app/Jobs/`; `dispatch()` is reached only from the HTTP controller, where `auth()->id()` is non-null.

⇒ **Test-harness defect, not a production defect.** It does **not** need its own ticket on correctness grounds; it does keep 5 tests red. Recorded here, **not fixed**, and deliberately **not merged** into the Family-A question.

## 7 · Required human decision

> **Does an election-audience newsletter go to every active election participant (as `ElectionMembership` records them), or only to participants whose `users.organisation_id` is the sending organisation?**

Once answered, the fix is small and one-sided: either relax the predicate (Reading A) or correct the fixtures (Reading B). **Engineering must not choose**, because the answer changes who receives election communications.

## 8 · Non-actions

⛔ No production code changed · no test added, changed or deleted · no fixture changed · no ADR touched · `a72528b29` not amended or rewritten · Family B not fixed · Families C/D/E not investigated · no `PBDIGIT-61` record altered · no UC-4/`GREEN-5`/`GREEN-6`/`GREEN-7`/`BND-1`/`BND-3`/Act C/Act D/`HaltedAtGate`/read-side-absence work touched.

## 9 · Research observation — recorded separately from conclusions

⚠️ **This case is counter-evidence to a corollary of the current E/O/M/A hypothesis**, and is recorded as such rather than fitted to it.

- **Existence** ✅ the recipient data exists (`ElectionMembership` rows are present and correct).
- **Obtainability** ✅ reachable through the intended relationship.
- **Meaning** ❌ **the recipient population's business meaning is not established** — two documented readings disagree.
- **Authorization** ✅ the feature is shipped and authorized.

⇒ The four **dimensions** hold and classify this cleanly as an **M-blocker**. But the research brief's corollary — *"Goal A's blockers are obtainability/meaning/authorization; **Goal B's are existence**"* — is **weakened**: this is a Goal-B blocker that is purely semantic. **The dimensions survive; the Goal-A/Goal-B split does not generalise.** No adoption of the hypothesis is claimed or implied.

**Traceability:** `NewsletterService.php` election-audience queries (`~188-308`), `:444` · `UserFactory.php:53` · `NewsletterAudienceSegmentationTest.php` `setUp`, Family-A tests · `2026_04_06_090744_create_newsletter_audit_logs_table.php:18` · `PBDIGIT-61` (scope at line 90, resolution 2026-08-07, commit `a74d4596`) · `PBDIGIT-68` · `IERVP-2`/`IERVP-3` · `1181d66e2` · `a72528b29` · `.claude/plans/encapsulated-hopping-hamster.md` · research brief `2026-09-30-blocker-structure-research-brief.md`

---

## 10 · SEMANTIC RECONSTRUCTION + FALSIFICATION PASS (2026-09-30, additive `ES-004.3`)

**Method.** My §1–§9 claims were put to a fresh-context adversarial reviewer instructed to refute them. **Every load-bearing result below was then re-verified from source by me before being recorded.** Findings I could not verify are marked as such. ⚠️ The reviewer is technical falsification, **not** an independent governance lane.

### 10.1 · Claims re-verified

| Claim | Result |
|---|---|
| Pre-change join used `members.user_id`, a **non-existent column** | ✅ **PROVED.** `members` has `organisation_user_id`, never `user_id`; `Member::user()` uses `hasOneThrough` precisely because no direct FK exists. **Stronger than claimed: the defect was present at birth** — `0bad829f3` (2026-04-18, the feature commit) already contained `members.user_id` in 10 places. |
| `users.organisation_id` is today's eliminating predicate, unchanged by the recent commit | ✅ **PROVED.** Context line in the diff; present in all 7 election audiences. |
| `users.organisation_id` ≠ `organisation_users` | ✅ **PROVED.** Former: NOT NULL single-valued FK on the identity row. Latter: pivot with `role`, `status`, `joined_at`, soft deletes, `unique(organisation_id,user_id)`. |
| `VoterEligibilityService` uses `organisation_users`, not `users.organisation_id` | ✅ **PROVED.** `:62-71` Election-Only; `users.organisation_id` appears **nowhere** in that file. **Two application services in one codebase establish organisation participation by different relations.** |
| PBDIGIT-68 does not settle communication eligibility | ✅ **CONFIRMED.** Zero matches for communicat/newsletter/notif/recipient/email in that ticket. |
| No authoritative rule anywhere settles it | ✅ **CONFIRMED.** `ADR-MP-Messaging-Platform` — the nearest authority — declares messaging a Generic Technical Subdomain holding *"no business decisions"*, i.e. **the recipient-population question is explicitly not messaging's to answer**, and no ruling assigns it elsewhere. |

### 10.2 · 🔴 My §6 conclusion is REFUTED — Family A **is** production-reachable

§6 established non-reachability for **Family B** only; §1–§5 then described Family A as occurring *"in test conditions"*. **That framing was wrong.** Two importers disagree about `users.organisation_id`, and both key `firstOrCreate` on **email alone**:

| Importer | Sets `users.organisation_id` to |
|---|---|
| `VoterImportService.php:268-277` | **`$organisation->id`** — the tenant |
| `OrganisationUserImportService.php:318-327` | **`$this->platformOrg?->id`** — the platform org (`getPlatformOrganisation()`: `type='platform'` first, else `Organisation::first()`), while writing the pivot row with the real org |

⇒ **Whichever importer sees an email first permanently fixes `users.organisation_id`.** A person onboarded through the organisation-user importer and later enrolled in an election keeps `organisation_id = platform` and is **excluded from every election newsletter in production** — the same mechanism the tests expose. Additionally, `organisation_users` permits N organisations per user while `users.organisation_id` names one, so a genuinely multi-organisation participant is structurally unreachable by at least one organisation's election newsletters.

**This raises PBDIGIT-74 from a fixture/contract mismatch to a production correctness defect.**

### 10.3 · Correction to my own §3 citation

§3 stated `UserFactory` matches the platform org *"`firstOrCreate` by slug `publicdigit`"*, citing `:53`. ❌ **The mechanism is wrong.** The match key is **`['type' => 'platform', 'is_default' => true]`**; `name`/`slug` are creation values only, and the migration already seeds a matching row. **The effect §3 relies on still holds** (factory users are never owned by the test's own organisation), but the stated mechanism and line were inaccurate. Latent hazard the reviewer surfaced: because the key is `type+is_default`, `OrganisationFactory::platform()` also matches, making the factory's org assignment **order-dependent across tests**.

### 10.4 · A second, independent conflation of the same shape — ✅ verified

Member audiences filter **`members.newsletter_unsubscribed_at`** (`:144, :156, :169, :182`); election audiences filter **`users.newsletter_unsubscribed_at`** (`:203, :224, …`). The only writer of an unsubscribe timestamp in the codebase is `NewsletterUnsubscribeController.php:17`, which updates **`members.newsletter_unsubscribed_at`** (`Member.php:33`). **Nothing ever writes `users.newsletter_unsubscribed_at`.**

⇒ **An opt-out recorded through the public unsubscribe link does not suppress election newsletters**, and a non-member election participant has no unsubscribe path at all (the controller resolves a `Member` by token). Same three-relation conflation, with a data-protection edge. **Not fixed here; warrants its own ticket.**

### 10.5 · One reviewer claim REFUTED by my own check

The reviewer reported that `queryOrgParticipantsStaff`/`Guests` omit the `users.organisation_id` predicate, concluding *"it is not a considered rule."* ❌ **Wrong.** All **10** user-scoped queries carry it — the 7 election audiences plus `queryOrgAdmins`, `queryOrgParticipantsStaff`, `queryOrgParticipantsGuests`. Only the 4 member audiences scope via `members.*` instead. **The predicate is applied uniformly**, which is evidence *for* deliberateness, not against it.

### 10.6 · Formal model and the smallest inconsistent set

```
R1 (u,g)   := users.organisation_id = g                  account/tenant ownership of the identity
R1'(u,g)   := active organisation_users row              organisation participation
R1''(u,g)  := members row via organisation_user_id       organisation membership  ← PBDIGIT-68's subject
R2 (u,e)   := active election_memberships row            election participation
R3 (u,e,r) := election_memberships.role = r              election role
E  (e,g)   := elections.organisation_id = g              the election's owning organisation
R4 (u,e,a) := u is eligible for audience a of election e  ← THE UNDEFINED PREDICATE
```

**Implementation encodes:** `R4 ≡ R2 ∧ R3 ∧ active ∧ R1(u,g_sender) ∧ ¬unsub ∧ ¬bounced` — and notably **not** `E(e,g_sender)`.
**Tests encode:** `R4 ≡ R2 ∧ R3 ∧ active` — no `R1` term.
**The domain's own voter path uses `R1'`**; PBDIGIT-68 ruled on `R1''`. **Four distinct relations, four different answers.**

**Smallest inconsistent evidence set — three current in-tree artifacts, no documentation required:**

1. `NewsletterService::queryElectionVoters` requires `R1(u, g_sender)`
2. `test_non_member_election_participant_receives_newsletter_without_member_id` requires `¬R1'' ∧ R2 ⊨ R4`
3. `UserFactory` guarantees `R1(u, platform)` for factory users, and `platform ≠ g_sender`

{1,2,3} is unsatisfiable. ⇒ **The inconsistency is internal to the code+tests, independent of any doc conflict.**

### 10.7 · Security / tenancy — separating demonstrated from hypothetical

| Finding | Status |
|---|---|
| `audience_meta.election_id` is validated only `exists:elections,id`, never scoped to the organisation; `NewsletterService` never checks `E(e,g_sender)` — `Election` is imported and never used | ✅ **verified** |
| ⇒ `users.organisation_id` is currently the **only** barrier to resolving another organisation's election participants | ✅ verified (no policy, no Gate, no `authorize()`, no newsletter policy; `BelongsToTenant` explicitly bypassed by `withoutGlobalScopes()`) — **so naive removal of the predicate opens cross-organisation recipient disclosure.** A **constraint on any fix**, not a live leak today. |
| `authorizeNewsletterAccess` (`:432-452`) never verifies the election belongs to `$org` | ✅ **verified by me** — a **live authorization gap**, independent of the recipient question → **raised as `PBDIGIT-75`** |
| `previewCount` returns `count` + a 5-row `name`/`email` sample on one POST with only `authorizeAdmin` | ⚠️ reviewer-reported, **not re-verified by me**; recorded in `PBDIGIT-75` |
| `BelongsToTenant` platform fallback looks up slug `'platform'` while the seeded org has slug `'publicdigit'`; `exists:elections,id` ignores soft deletes; one test's name does not match what it exercises | ⚠️ reviewer-reported, **not verified**, out of scope — recorded so they are not lost |

### 10.8 · DDD ownership

`NewsletterService` — a Communication concern — currently **derives election entitlement from a persistence column owned by identity/tenancy** (`users.organisation_id`), while the authoritative election-participation relation (`election_memberships`, which carries its **own** `organisation_id` and uses `BelongsToTenant`) is referenced **zero times** in that file. `ADR-MP-01` states messaging *"holds no business decisions"*.

⇒ **Design hypothesis, requiring a ruling rather than a refactor:** the Communication context is making an election-entitlement decision that belongs to whoever owns election participation. **A third option is already expressible with existing columns** — scope on `election_memberships.organisation_id` and add `E(e,g)` — which would close the §10.7 hole *and* satisfy the tests. **Recorded as expressible; not proposed, not chosen.**

### 10.9 · E/O/M/A falsification, and one justified new dimension

**PBDIGIT-74 classification, tested rather than assumed:** Existence ✅ · Obtainability ✅ · **Meaning ❌** · Authorization ✅ (investigation only). The classification survives.

**Falsified corollary — recorded, not repaired:** *"Goal A's blockers are semantic/governance-shaped; Goal B's are existence/correctness-shaped."* **Does not survive.** PBDIGIT-74 is a Goal-B blocker that is purely semantic. **Weakened to: the four dimensions classify blockers; their distribution across goals does not generalise.**

**A fifth dimension meets the §12 minimality criterion — `H`, authority-of-source.** Counterexample pair, both from this ticket:

- `x` = `users.organisation_id` — E ✅ O ✅ **M ✅** (it has a clear meaning: account ownership) A ✅
- `y` = `election_memberships.organisation_id` — E ✅ O ✅ M ✅ A ✅

**Both agree on all four dimensions, yet differ materially in semantic correctness**, because only `y`'s source is authoritative for the question *"does this person participate in this organisation's election?"* **`x` is a category error, not a missing meaning.** ⇒ `H` is justified: `∃x,y: state_EOMA(x)=state_EOMA(y) ∧ classification(x)≠classification(y)`.

**Dimensions tested and NOT justified** — no counterexample found where E/O/M/A/H agree yet classification differs: `K` observability (collapses into `O` on this corpus) · `V` validity/currency (a property of *documents*, e.g. the stale pin docblock, not of a fact's implementability). **Resisting inflation: E/O/M/A + H, five, no more.**

### 10.10 · Statistical discipline

No statistical claim is made. Counts here are **coverage** of an enumerated finite set (11 query methods, 7 audiences, 21 tests, 18 ADRs), not samples from a population. No sampling model exists, so no inference is available — and *"two services disagree"* is a **logical** contradiction, not a frequency result.

### 10.11 · Outcome

> ## **OUTCOME 3 — ARCHITECTURAL MODEL DEFECT.**
>
> The conflict arises because **four relations** (`R1` account ownership · `R1'` organisation participation · `R1''` organisation membership · `R2` election participation) are each treated as *"belongs to this organisation"* by different parts of the same codebase, and **no authoritative source assigns ownership of `R4`** — eligibility to receive an election communication.

**The missing domain distinction:** `R4` has no owner and no definition. ⛔ **Not invented here.**

### 10.12 · The escalated modelling question — domain-level, implementation-independent

> **Which relation establishes that a person is eligible to receive a communication about a specific election — and which relation establishes that the election belongs to the sending organisation?**

The two halves are separable and both are currently unanswered. Consequences of the competing readings are in §4 and §10.7; **no reading is recommended or ranked.**

⛔ **Still OPEN / NOT AUTHORISED FOR IMPLEMENTATION.** No production code, test, fixture or migration changed by this investigation.
