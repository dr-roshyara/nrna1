# ARB decision package — membership hierarchy refinement (`D-ENT-2`)

**Type:** ARB decision package · **Date:** 2026-08-12 · **Programme:** IERVP (Session 2, governance follow-up)
**Trigger:** the Product Owner refined the business workflow and stated that *"the earlier `F1` wording was too weak."*
**⛔ No implementation. No schema, model, service, controller, test or migration change. `ADR-002` remains Accepted and unamended. Officer Guide not promoted. `G-PUB` not investigated. Session 1's Master Matrix not consumed or modified. `D-ENT-1` is not reopened — this records a refinement on top of it.**

---

## 1 · The refined business decision, recorded verbatim

**FULL MEMBERSHIP MODE**
> * Organisation Membership is **superior to** ElectionMembership.
> * An ElectionMember **must have** Organisation Membership.
> * **Removal of Organisation Membership automatically suspends ElectionMembership.**
> * **This automatic suspension is distinct from Election Chief suspension.**
> * The Election Chief may **independently** suspend ElectionMembership for election-specific reasons.
> * **Removing ElectionMembership does not remove Organisation Membership.**

**ELECTION-ONLY MODE**
> * Organisation Membership is **not required**.
> * ElectionMembership is election-specific.
> * The Election Chief governs ElectionMembership suspension/removal.

---

## 2 · 🔴 Reconciliation with `Q-A0` = `F1` — my earlier record was too coarse and is corrected here

**`F1` as I recorded it:** *"organisation membership is an admission prerequisite — **not a continuously evaluated prerequisite** for retaining the entitlement."*
**The refined rule:** *"removal of Organisation Membership **automatically suspends** ElectionMembership."*

**Read flatly these conflict. Read on the axis this programme established, they do not — and the axis is the reconciliation:**

| Axis | Is organisation membership continuously required? | Source |
|---|---|---|
| **Existence** of the entitlement | ❌ **No.** Organisation-membership loss does **not** destroy or terminate the `ElectionMembership` | **`F1` survives · `A-3` survives** |
| **Exercisability** of the entitlement | ✅ **YES, in Full Membership mode.** Loss automatically suspends it | **NEW — `F1` does NOT survive here** |

> ### The correction I owe the record
> **`Q-A0` was recorded as *"not continuously required"*, flat, without splitting the axes.** That reading was **too coarse**, and it propagated into the `ADR-002` amendment as *"not re-derived from organisation membership."* **The Product Owner's judgement that `F1` "was too weak" is consistent with that: it stated the existence half and left the exercisability half unstated.**
>
> **`Q-A0` restated:** **organisation membership is not continuously required for the entitlement to EXIST; it IS continuously required, in Full Membership mode, for the entitlement to be EXERCISABLE.**

**This is exactly why the existence/exercisability separation was worth establishing** — without it, the refinement would have looked like a reversal of `D-ENT-1` instead of a completion of it.

---

## 3 · The three transitions, kept distinct (task 4)

**These must never collapse into a generic "revocation".**

| | **(a) Organisation-driven** | **(b) Election Chief** | **(c) ElectionMembership removal** |
|---|---|---|---|
| **Trigger** | Organisation Membership removed | An election-specific reason | An election-specific decision |
| **Actor** | **Organisation** *(or the system, on the organisation's act)* | Chief / Deputy | Chief / Deputy |
| **Object** | the ElectionMembership's **exercisability** | the ElectionMembership's **exercisability** | the ElectionMembership |
| **Automatic?** | ✅ **Yes — automatic** | ❌ No — a deliberate act | ❌ No |
| **Effect on existence** | **none** — record persists | **none** | record **retained**; exercisability withdrawn *(`BR-1.1` Option B, recommended, unratified)* |
| **Effect on Organisation Membership** | n/a | **none** | 🔑 **none — explicitly stated by the Product Owner** |
| **Mode** | **Full Membership only** | **both modes** | **both modes** |
| **Reversibility** | ⚠️ **UNSPECIFIED — `W-1`** | reversible by restoration | ⚠️ administrative only *(`BR-1.2`, unratified)* |
| **Mechanism today** | 🔴 **NONE — see §4** | exists *(two competing paths, `BR-1.13`)* | exists (`remove()`) |
| **Audit today** | 🔴 n/a — does not occur | 2 of 3 paths log; none reaches the election trail | conditional on a legacy column |

> **(a) is a new kind of transition for this domain: the only one whose actor is NOT an election officer.** That alone distinguishes it, and it is why calling all three "revocation" would destroy information.

## 4 · 🔑 The mechanism question — the trigger exists, the wiring does not

**Measured, and it is the most actionable fact in this package:**

| Fact | Class |
|---|---|
| The organisation side **already emits** `MembershipSuspended`, `MembershipTerminated`, `MembershipRestored` as domain events | **OBSERVED FACT** |
| **Nothing in Elections subscribes to any of them** | **OBSERVED FACT** |
| No mechanism exists by which the Election context learns that organisation membership changed | **MECHANISM NOT ESTABLISHED** |
| `MembershipRestored` exists, so **either answer to `W-1` is supportable** | **OBSERVED FACT** |

> **So the refined rule is expressible as a Published-Language interaction that is already modelled on the publishing side and simply unsubscribed on the consuming side.** **This is an architectural consequence, not an implementation proposal — I have designed no listener, named no class, and propose no wiring.**
>
### ✅ VERIFIED — no organisation→election trigger exists at ANY layer

**The Product Owner said *"there is no trigger so far I know"* and invited me to check. I checked the application layer (no event subscription), then suspected a persistence-layer trigger, then measured the live schema. The measurement settles it — and it withdrew my own intermediate claim.**

**Measured `pg_constraint` — `election_memberships` has exactly TWO foreign keys in the live database:**

```
election_memberships_assigned_by_foreign                  -> users      on_delete = SET NULL
election_memberships_election_id_organisation_id_foreign   -> elections  on_delete = CASCADE
```

> 🔑 **There is NO foreign key to `user_organisation_roles` in the live database.** **So no organisation→election cascade exists, and the Product Owner's statement is confirmed at BOTH the application and the persistence layer: there is no trigger.**

#### ⚠️ Withdrawn: my intermediate claim

**I briefly recorded that an FK cascade "erases the entitlement" when the organisation-role linkage is deleted. That is WRONG for the live system and is withdrawn.** It came from reading the migration and not verifying the schema — **the same error I have repeatedly flagged in others' claims: treating a declaration as a fact.** The migration declares the constraint; **the database does not have it.**

#### 🔴 But the check surfaced two real findings

| # | Finding | Class |
|---|---|---|
| **`FK-1`** | **The declared FK is ABSENT from the live schema.** The migration states it *"Ensures `user_id` + `organisation_id` exists in `user_organisation_roles` (user is actually a member of this organisation)"* — **that guarantee is NOT ENFORCED.** So an `ElectionMembership` can exist with no organisation linkage at all | **MEASURED** |
| **`FK-2`** | **`(election_id, organisation_id) → elections` IS `ON DELETE CASCADE`.** Deleting an election **hard-deletes every membership record**, bypassing `SoftDeletes` and taking the audit metadata (`removed_at`/`removed_reason`/`removed_by`) with it. **Not organisation-driven — a separate matter, recorded not investigated** | **MEASURED** |

> **`FK-1` corrects statements in my own earlier artifacts.** I wrote that *"the FK enforces a role linkage"* and that *"the FK requires an organisation-role row"* — in the two-mode report, the governance-source analysis (`R7` classed **SUPPORTED**), and the Officer Guide assessment. **Those rest on a constraint that does not exist.** The observation that Election-Only voters *do* receive `user_organisation_roles.role='member'` **stands** — it was measured directly — but it is a **behaviour of the import path, not an enforced invariant.** **`R7` should be reclassified from `SUPPORTED` to `UNSUPPORTED`.**

<details>
<summary>Superseded reasoning, retained for the record</summary>

**Re-checking the persistence layer:**

```php
// database/migrations/2026_03_17_213212_create_election_memberships_table.php
$table->foreign(['user_id', 'organisation_id'])
      ->references(['user_id', 'organisation_id'])
      ->on('user_organisation_roles')
      ->onDelete('cascade');          // <-- an automatic mechanism, at the database layer
```

| Aspect | Finding | Class |
|---|---|---|
| Deleting the `user_organisation_roles` row **CASCADE-DELETES** the `election_memberships` row | a real, automatic organisation→election mechanism | **OBSERVED IN MIGRATION** |
| It **hard-deletes** — a database cascade **bypasses Eloquent**, so the model's `SoftDeletes` does **not** apply | the record is **erased** | **INTERPRETATION** from the mechanism |
| It therefore **destroys the entitlement together with its audit metadata** (`removed_at` / `removed_reason` / `removed_by`) | — | **INTERPRETATION** |
| Whether the constraint is live in the database as `ON DELETE CASCADE` | **NOT VERIFIED** — the `pg_constraint` query could not be run this turn (tooling outage). **Migration evidence only** | **MECHANISM NOT ESTABLISHED at runtime** |

> 🔴 **The position is therefore worse than "unenforced". An automatic organisation→election mechanism exists, and it ERASES the entitlement where `D-ENT-2` says it should SUSPEND it.**
>
> **It contradicts `D-ENT-2`** (suspend, not destroy), **`F1`/`A-3`** (existence durable), **and `BR-1.1` Option B** (record retained with actor and reason). **A silent erasure is more dangerous than a missing suspension, because it removes the evidence that anything happened.**

**The distinction that decides how much this matters is itself a business question:**

| *"Removal of Organisation Membership"* could mean | Does the cascade fire? |
|---|---|
| deleting the **`user_organisation_roles` linkage row** | ✅ **YES — the `ElectionMembership` is erased** |
| setting the **`Member` aggregate's status** to terminated/suspended | ❌ **No** — no row is deleted, so nothing propagates |

**Two different acts, and the refined rule does not say which it means. Recorded as `W-7` — and it remains a live question even though the cascade turned out to be absent, because the rule must name the act it governs before anything can implement or test it.**

</details>

### Where this leaves `D-ENT-2`

> **Product Owner, 2026-08-12: *"there is no trigger so far I know."*** — **verified, at both layers.**

**No event subscription · no policy · no audited transition · no domain-event consumer · and no foreign key.** The organisation side **does** emit `MembershipSuspended` / `MembershipTerminated` / `MembershipRestored`, so **the Published Language needed to implement `D-ENT-2` already exists on the publishing side and is simply unsubscribed.**

**Two consequences hold:**

1. **`D-ENT-2` as a governed rule has never operated** — no audited automatic suspension has ever occurred, so **there is no prior behaviour to preserve and no accumulated state to migrate.** `W-1`…`W-7` are forward-looking choices, not reconstructions of past intent.
2. **`members` has 0 rows, so no Full Membership voter could ever have been affected** — **`D-ENT-2` currently has an empty domain of application.** It is a forward-looking rule, and implementing it breaks nothing that exists.

---

## 5 · Which `ADR-002` amendment clauses must change

**Precisely four. `V-2` and `V-3` are unaffected.**

| Clause | Current proposed text | Verdict |
|---|---|---|
| **§5.1** axis 0 `Entitled` | *"**It is NOT re-derived from organisation membership.**"* | 🔴 **MUST CHANGE — scope it to existence.** Correct as stated **for `Entitled`**; but it must say explicitly that this does **not** extend to `Exercisable`, which in Full Membership mode **is** affected by organisation membership |
| **§5.2** amendment of `:161` | *"`eligibility_status` — computed at check time from the entitlement and the election's own rules, **not from organisation membership**"* | 🔴 **MUST CHANGE.** In Full Membership mode organisation-membership loss drives an automatic suspension, which **is** an input to check-time exercisability |
| **§5.3** admission/check-time split | *"organisation membership, fees and membership type apply **here, and only here**"* (admission) | 🔴 **MUST CHANGE.** Organisation membership applies **at admission AND as a suspension trigger thereafter** |
| **§5.4** formula | `Entitled && Exercisable && Verified && Permission` | ⚠️ **Terms stand; `Exercisable`'s DEFINITION changes** — it now composes an organisation-driven automatic suspension in Full Membership mode. **A definitional note, not a new term** |
| **§5.5** revocation terminology (`V-2`) | — | ✅ **Unaffected, and still recommended** |
| **§5.6/§5.7** staleness note, out-of-scope | — | ✅ **Unaffected** |
| **`V-3`** record vs decision | — | ✅ **Unaffected — and now MORE important**, because the reconciliation in §2 *is* a record-versus-decision distinction |

**Net effect:** the amendment's **structure survives**; three clauses that over-generalised *"not re-derived from organisation membership"* from existence to exercisability must be narrowed. **This does not weaken the `Entitled` axis — it sharpens it.**

## 6 · Consequences for the decision register

| Decision | Change |
|---|---|
| **`BR-1.1`/`1.2`** | **Now must cover three transitions, not one** (§3). Option B recommendation stands for **(c)**; **(a)** is newly in scope and its reversibility is unspecified (`W-1`) |
| **`Q3`** | **Blast radius grows.** Exercisability must now compose **four** causes plus an **organisation-driven** one — and the organisation-driven cause has a **different actor class** (not an election officer) |
| **`Q-E2`** | Unchanged, and reinforced: the gate must distinguish **more** causes, not fewer |
| **`Q-D1`** | 🔴 **My recommendation's REASON is withdrawn.** I said "do not activate `scopeEligible()` — it implements the opposite of `F1`". **Under the refined rule its organisation-membership condition is directionally ALIGNED with the intent.** **The recommendation stands — do not activate — but on different grounds:** it would return **0 of 20** rows while `members` is empty, and it enforces by **query-time filtering** rather than by the **suspension** the refined rule specifies. **Two different mechanisms; only one is the adopted rule.** |
| **`Q-A0`** | **Restated per §2** — existence vs exercisability split |
| **NEW `D-ENT-2`** | This package |
| **NEW `W-1`…`W-6`** | Business-workflow questions — §7 |
| **`G-PUB`** | **Untouched and uninvestigated**, held as an independent ARB finding |

---

## 7 · 🟡 Business-workflow questions — **I am stopping and asking, not resolving**

**The Product Owner instructed: if a remaining question is genuinely a business-workflow question, stop and ask rather than resolve it from implementation evidence. These six qualify.**

| # | Question | Why I will not infer it |
|---|---|---|
| **`W-1`** | **If Organisation Membership is RESTORED, does the automatic suspension lift automatically, or does it require an election-domain act?** | `MembershipRestored` exists, so **both answers are equally implementable.** The choice is a governance one: symmetric automation, or human re-admission |
| **`W-2`** | **May the Election Chief restore a voter whose suspension was caused by organisation-membership loss?** — i.e. can an election act override an organisation-derived condition? | Goes to *"Organisation Membership is **superior**"*. If superior, the Chief presumably cannot; but *"superior"* was stated about the **hierarchy**, not about override rights |
| **`W-3`** | **If a voter is under BOTH an automatic suspension and a Chief suspension, does lifting one restore exercisability?** | Requires knowing whether the two are independent conditions (both must clear) or one state |
| **`W-4`** | **Does automatic suspension apply to a voter who has ALREADY VOTED?** | `ADR-T11` bounds it — the cast ballot **cannot** be reached — so the only question is the record's state, which is a business choice |
| **`W-5`** | **Does organisation-membership EXPIRY (as distinct from removal) also trigger automatic suspension?** | The refined rule says *"removal"*. `members.membership_expires_at` exists, and expiry is a different event. **I will not read "removal" as covering expiry.** |
| **`W-6`** | **In Election-Only mode, does losing the organisation-role linkage suspend the voter?** | The rule says Election-Only needs no `Member` — but imported voters **do** receive `user_organisation_roles.role='member'`, which the schema's FK requires. **Whether that linkage carries the same superiority is unstated** |
| **`W-7`** | 🔴 **Which act IS "removal of Organisation Membership"** — deleting the `user_organisation_roles` linkage row, or terminating the `Member` aggregate? | **The two have opposite consequences today**: the first **erases** the `ElectionMembership` via FK cascade; the second propagates nothing. **The rule must name which act it governs before it can be enforced or even tested** |

**`W-7` is now the one I would answer first** — it determines whether the existing cascade is an accidental implementation of the rule (doing the wrong thing) or an unrelated hazard sitting beside it. **`W-2` and `W-6` follow**, because `W-2` decides whether *"superior"* is a hierarchy statement or an authority statement, and `W-6` decides whether the refinement touches one mode or both — noting the **cascade already spans both.**

---

## 8 · Decision summary for ARB

| ID | Decision | Owner | Recommendation |
|---|---|---|---|
| **`D-ENT-2`** | Ratify the refined hierarchy as recorded in §1 | **PO** *(already stated; recorded here for the register)* | — |
| **`Q-A0`-R** | Accept the restatement: existence vs exercisability (§2) | **ARB** | **Accept** — it is the only reading under which `D-ENT-1` and the refinement are both true |
| **`AMD-1`** | Narrow the four `ADR-002` clauses in §5 before applying | **ARB** | **Yes** — apply **only** the corrected text |
| **`BR-1.1`-R** | Extend termination semantics to cover three transitions | **PO** | Option B stands for **(c)**; **(a)** needs `W-1` |
| **`W-1`…`W-7`** | Business-workflow questions | **PO** | **Asked, not answered** |
| **`Q-D1`**-R | Do not activate `scopeEligible()` — **reason restated** | ARB → eng | **Do not activate** *(new grounds)* |
| 🔴 **`FK-1`** | **The declared FK to `user_organisation_roles` is ABSENT from the live schema** — so *"user is actually a member of this organisation"* is **not enforced**, and an `ElectionMembership` can exist with no organisation linkage | **ARB → engineering** | **Classify, do not repair.** Adding the constraint would be a schema change with unknown blast radius, and it interacts with `W-7`. **`R7` in the Officer Guide analysis should be reclassified `SUPPORTED` → `UNSUPPORTED`** |
| 🔴 **`FK-2`** | **Deleting an election `ON DELETE CASCADE` hard-deletes every membership record**, bypassing `SoftDeletes` and its audit metadata | **ARB** | **Recorded, not investigated** — not organisation-driven, outside `D-ENT-2` |

**Dependency order:** **`W-7`** → `W-2` + `W-6` → `D-ENT-2` fully specified → `Q-A0`-R → `AMD-1` → `D-APPLY` → `BR-1.1`-R → `Q3` → implementation. **`FK-1` and `FK-2` are parallel classifications, not blockers.**

> **Why `W-7` is first:** the rule must name the act it governs — deleting the organisation-role linkage, or terminating the `Member` aggregate — before anything can subscribe to it, enforce it, or test it. **That is one business answer, not an investigation.**

## 9 · Boundaries

* **No implementation:** no schema, model, service, controller, test, fixture or migration change. **No listener designed, no class named, no wiring proposed.**
* **`ADR-002` Accepted and unamended.** Constitution untouched. **Officer Guide not promoted, not modified.**
* **`G-PUB` not investigated** — held as an independent ARB finding, per instruction.
* **`D-ENT-1` not reopened** — §2 is a refinement and a correction of **my own** record's coarseness, not a re-litigation of the decision.
* **Session 1's Master Matrix not consumed, read as authority, or modified.** No row count cited.
* **Runtime residue untouched** — IERVP test data · working-organisation restoration · `voter_source_strategy` backfill · another author's `ElectionUser.php` / `PBDIGIT-70`.
* **`ADR-T11` preserved** — nothing proposed identifies or mutates a cast ballot.

**Traceability:** `app/Contexts/Membership/Domain/Membership/Events/{MembershipSuspended,MembershipTerminated,MembershipRestored}.php` · `app/Providers/EventServiceProvider.php` *(no Elections subscription to any of them)* · `app/Contexts/Membership/Domain/Membership/MembershipLineage.php:297,334-345,364-395` · `app/Models/ElectionMembership.php:126-146` *(`scopeEligible`)*, `:173-197`, `:202-224` · `app/Models/User.php:315-328` · `app/Domain/Election/Enum/VoterSourceStrategy.php:10-60` · `docs/adr/ADR-002-verified-eligible-authorized.md:71,78,161` · `docs/adr/ADR-T-LOG-Tactical-Implementation.md:18` · prior records `fc53f78a` · `3530a234` · `0fafdd50`.
