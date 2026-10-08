# EP-01: Principal Implementation Design Review and Falsification

**Date:** 2026-10-08 · **Raised by:** `claude-code-session:42e0287e…` (self-declared, not attestable) · **Reviews:** `2026-10-08-membership-ep01-implementation-readiness-and-design.md` (**EP-01**)
**Authority:** the ratified architecture (act `2026-10-08-membership-architecture-ratification-act.md`; **MA**, **FP**, **FDR**, **FIB**, **FV**). The research corpus is evidence, not authority.
**Status:** REVIEW. It creates no code, migration, story, ADR change or business decision. EP-01 is revised **only where evidence requires** (§23, "Revision 1").
**Evidence:**
- read-only repository inspection, including a dedicated tenancy inventory;
- computational checks with the research model (scratchpad `fsm/persist_check*.py`, `freeze.py`, `concurrency.py`). These are evidence, not product code;
- every repository claim carries `path:line`.

---

## 1. Executive verdict

**B: APPROVED WITH CONDITIONS** (recommended classification; the approval act is the PO's / Principal Reviewer's).

- **No ratified decision is contradicted.** There is no architecture conflict.
- **EP-01 needed 14 evidence-based revisions** (§23). Three of them correct EP-01 **factual errors or hazards**:
  1. **EP-01 C-4 / EP-Q1 was wrong.** There are no "two physical `members` tables". The `tenant` connection does not exist (`config/database.php` defines only `pgsql`, `pgsql_audit`, `testing`, …). The `tenant`-connection model is dead code. The decided tenancy rule is one database, scoped by `organisation_id`. EP-Q1 is answered by evidence; it no longer blocks.
  2. **"No dual-write" was unsafe as written.** 23 application files outside the Membership context read `members` directly, and one legacy writer uses a query-builder mass update that bypasses model events (`app/Console/Commands/ExpireMemberships.php:19`). Cutover needs a **single-writer reverse projection** and a **write guard that also catches mass updates**.
  3. **Conflict retry must re-run the guards and the authority check, not only G\*.** Re-checking invariants on the stale candidate would have committed 10,005 / 206,590 simulated conflicts that the correct path refuses:
     - 8,833 targeted a fact that was no longer live;
     - 1,172 corrected a fact that was no longer active.
- **The other revisions** are minimality and classification fixes:
  - consequence storage;
  - duplicated columns;
  - events aligned with RA-12;
  - deferred projections;
  - split slices 0 and 1;
  - ex-nunc removed from the baseline;
  - number immutability made explicit;
  - orphan legacy columns named.

## 2. Ratified baseline

The act (§2–§17) and MA §1–§11. Frozen: ontology, D1, B′ identity, Period semantics, the fact algebra and correction algebra (MA §9, FP §16.2–16.4), the temporal model, BD-I5 / BD-REV / BD-SYNC, the concurrency requirement, and the Election / Finance / Governance boundaries.

## 3. Decision traceability

| Ratified decision | EP-01 realisation (after Revision 1) | Evidence | New semantics? | Classification |
|---|---|---|---|---|
| One Member aggregate per (p,o) | `Member` root; `UNIQUE(organisation_id, person_ref)` | act §2, §10; EP-01 §5, §10 | no | directly required |
| Period = episode; id = Admit | `PeriodId` = Admit `FactId`; periods derived | act §6; MA §1 | no | directly required |
| Fact algebra (9 kinds) | 9 fact types; no tenth | act §3; EP-01 §6 | no (ex-nunc removed, R2) | directly required |
| Correction algebra | guard matrix §6 below | MA §9; FP §16.4 | no | directly required |
| Resolution order | EP-01 §7 pipeline | act §2 | no | directly required |
| Consequences on their cause; CFC+ | stored with the fact as committed decision content (R4) | MA §2–§3 | no | directly required |
| G\* = enforcement | `InvariantPreservingAcceptance`; refusal names the invariant | act §9 | no | directly required |
| effective / decided / recorded / seq | fact columns; DB time never read | act §5 | no | directly required |
| Date granularity of effectiveAt | organisation calendar date (EP-I1) | §11 below | **convention only** | implementation choice, plus **business confirmation** (no intra-day semantics) |
| BD-I5 / BD-REV / BD-SYNC | InvariantSet scope; ConsequenceGenerator; transactional commit | act §14 | no | business (frozen) |
| BD-REV-ER | ER in I if refuse | act silent (note N-4) | — | **unresolved business decision** |
| Concurrency requirement | version + reload + **re-guard + re-authorise + recompute f⁺** (R3) | act §13 | no | directly required |
| Idempotency | `UNIQUE(member, idempotency_key)` | FP §16.9 (KnownAt under retries) | no | implementation choice serving a ratified property |
| Membership number | root attribute, per-organisation uniqueness, **assigned once** (R13) | MA §1; RA-11 | the reassignment rule is **not ratified**, so OPEN | business (BD-2, BD-NU) + OPEN (reassignment) |
| Published boundary RA-12 | events aligned with RA-12 (R6) | FDR RA-12 | `PeriodOpened` / `PeriodEnded` removed | directly required (when consumers exist) |
| No Event Sourcing (RA-13) | fact ledger + pure resolution; §10 | FDR RA-13 | no | directly required |
| Authority = Governance | `AuthorityCheck` port; interim adapter labelled technical-only (R12) | act §11 | no | directly required + interim implementation |
| Election boundary | `MembershipQueryPort` without entitlement or fees | act §12 | no | directly required |
| Finance owns fees | Fee relocation (DS-1); Standing deferred (DS-5) | act §11 | no | downstream decision |
| Tenancy | default connection, `organisation_id` rows, explicit `TenantId` (R1, R14) | `CLAUDE.md:30-33,75-78`; `config/database.php` | no | **infrastructure rule (decided in the governing doc; no ADR)** |
| Projections | deferred until a measured need (R7) | — | no | implementation choice |
| Migration via attested facts | report-first, per-organisation cutover, reverse projection (R10) | MA §9 (decision path) | no | migration choice |

**No mechanism is left unclassified.**

## 4. New semantic decisions detected (and their disposition)

| Item | Finding | Disposition |
|---|---|---|
| **Void(Void)** | **Ratified by reference:** MA §9 "**Void** targets any fact"; FP §16.2 (Void: "Can be Voided: yes"); FP §16.4 row G ("use Void(Void) if the Void was recorded in error"). Verified within bounds (`freeze.py`: Annul / Void of any live fact incl. Void; 0 violations). The act's own text does not name it | **Allowed.** Not new semantics. The PR may confirm it explicitly; it is not a blocker |
| **Ex-nunc annulment as `End(kind = annulled_ex_nunc)`** | Not ratified: the act ratifies ex-tunc Annul only; LQ-2 is open. The mapping would add an EndKind value | **Removed from the implementation baseline (R2).** Recorded as future / OPEN under LQ-2 |
| Correct of an annulled fact = inert | ratified (act §4) | as ratified |
| Membership-number reassignment | no fact in the algebra changes a number | **assign once; reassignment OPEN** (R13); not implemented |
| Interim authority = organisation role | not domain competence | labelled technical-only (R12) |
| effectiveAt = calendar date | a representation convention | implementation choice + business confirmation (§11) |

## 5. Fact algebra review

EP-01 §6 matches FP §16.2 with one exception: the ex-nunc mapping, removed (R2).
- **End only shrinks.** Enlarging an End is Correct(End).
- **Admit never carries consequences.** A backdated Admit into a live lifetime is refused.
- **Only Correct carries consequences.** Hence the **ConsequenceGenerator is needed only from Slice 7** (R8).

## 6. Correction / annulment matrix (complete)

"Live" = not annulled or voided by a live fact. "Active" = live with a live correction chain.

| Operation | Target state | Result | Class |
|---|---|---|---|
| Correct(live value-bearing fact) | active | value replaced; may carry consequences | **allowed** |
| Correct(live value-bearing fact) | annulled (target or chain) | recorded; no effect until revived | **inert** (ratified, act §4) |
| Correct(fact) | voided (target or chain) | — | **refused** (ratified) |
| Correct(Annul) | any | — | **refused** (no value; MA §9) |
| Correct(Void) | any | — | **refused** (no value; MA §9) |
| Correct(Correct) | active | the latest live correction wins; inherits clipped consequences | **allowed** |
| Correct(annulled Correct) | annulled | inert (as above) | **inert** |
| Annul(fact) | live, decision provenance | target dead; its D(c) inert; G\* checks the result | **allowed** |
| Annul(fact) | live, non-decision provenance | — | **refused** (use Void) |
| Annul(fact) | already dead | — | **refused** (target must be live) |
| Annul(Annul) | live | the original revives (appeal); G\* may refuse | **reviving** |
| Annul(Void) | any | — | **refused** (a Void is not a decision; MA §9) |
| Void(fact) | live | target dead | **allowed** |
| Void(Annul) | live | the original revives | **reviving** |
| Void(Void) | live | the voided fact revives | **reviving** (ratified by reference, §4) |
| Correct(fact whose Annul was later annulled) | active again | applies (an earlier inert correction becomes active) | **allowed / reviving** |

**No combination is ambiguous.** Every reviving operation is subject to G\* (FP §16.4; history H).

## 7. Consequence-model review

**Classification:** consequences are **committed decision content**: part of the fact as recorded, created by the enforcement step (g2) at commit, frozen thereafter.
- They are **not** an independent truth: they enter Resolution only through LiveCut, which clips them by the cause's activeness, supersession and BaseClaim.
- They are **not** a cache: they cannot be recomputed later without the risk of silently re-deciding (act §4: "does not silently re-decide").

**Property: Resolution(Facts) remains authoritative.** Resolution is a pure function of the committed fact rows, consequences included. No other stored artefact (projection, legacy column, event) is read to answer a domain question.

**Why stored consequences cannot diverge** (each step PROVEN by construction, given the mechanism):
1. They are computed by g2 on the state at expected version v.
2. The commit succeeds only if the stored version is still v (optimistic lock), so the prefix they were computed on **is** the committed prefix.
3. On conflict they are recomputed, never reused (R3).
4. Rows are immutable.

Therefore `cons(f) = g2_policy(prefix(f))` holds for every committed fact. The **g2 / policy version is stored on the fact** (R4), so a later code change cannot make the stored value "wrong". It only makes it the value decided under that version.

**Machine evidence** (`persist_check.py`, seed 11, 20,000 random histories):

| Check | Count | Mismatches | Status |
|---|---:|---:|---|
| JSON row round trip: Res(rehydrated) = Res(original) | 20,000 histories | **0** | VERIFIED WITHIN BOUNDS |
| stored cons(f) = g2 re-run on the exact committed prefix; re-acceptance by G\* | 114,766 facts | **0 / 0** | VERIFIED WITHIN BOUNDS |

## 8. Aggregate-boundary review

| Item | Classification | Reason |
|---|---|---|
| fact log (facts + consequences + provenance + authority evidence) | **must be inside** | INV-6 / INV-14; the invariants read them; atomic f⁺ |
| version, seq | **must be inside** | concurrency; ordering |
| MembershipPeriod | **derived only** | resolution output; never stored as truth |
| type segments, restriction intervals, term segments | **derived only** (from facts) | — |
| membership number | **may be inside** (root attribute) | per-org uniqueness is set-based, so it is a DB constraint outside the aggregate's invariant set |
| authority evidence | **inside, as an immutable snapshot on the fact** | Governance's current state is never read back |
| PersonRef, OrganisationId, MembershipTypeRef | **external reference** | Identity / Organisation / catalogue |
| Finance references (fee ids on Renew) | **external reference, optional evidence** | no fee state inside |
| Audit information | **must remain outside** (projection of the fact stream) | — |
| Election information | **must remain outside** | — |
| Committee participation | **must remain outside** (Governance) | act §10 |
| `personal_info`, `residence_geo_unit_id`, `newsletter_*` (legacy `members` columns) | **must remain outside**; owner OPEN (R11) | person / affiliation / communication attributes, not membership truth |

**No enlargement found** once R11 makes the legacy-column exclusion explicit.

## 9. Persistence minimality

| Table / column | Class | Decision |
|---|---|---|
| `org_memberships.id, organisation_id, person_ref, version` | authoritative / operational (version) | keep |
| `org_memberships.membership_number` | authoritative | keep; set once (R13) |
| `org_membership_facts.id, membership_id, seq, kind, effective_at, decided_at, recorded_at, provenance` | authoritative | keep |
| `org_membership_facts.target_fact_id` | authoritative | **single reference column** (R5): for period-scoped facts it is the period's Admit id; for reversals, the target fact |
| `org_membership_facts.period_id` | **derived** (duplicate of target or root) | **removed** (R5); an index or projection may add it later |
| `org_membership_facts.organisation_id` | operational (tenant-row isolation, indexing) | keep: the decided isolation mechanism is row scoping |
| `payload` jsonb | authoritative (the fact value) | keep, validated by a per-kind domain schema; typed columns only when a query needs them |
| `consequences` | authoritative (committed f⁺) | **jsonb on the fact row** (R4), not a child table: immutable with the fact, one row per atomic decision; `g2_version` stored |
| `effective_precision` | authoritative metadata (RA-15) | keep (migration needs it) |
| `idempotency_key` | operational | keep |
| KnownAt | — | **no storage**: computed from `recorded_at` / `seq` |
| `*_view` projections | derived | **deferred** (R7): introduced only when a list query is measured to need them (Slice 3 evidence) |
| outbox rows | integration | reuse the Shared outbox |

## 10. Event-Sourcing boundary

**EP-01 does not create Event Sourcing** (RA-13 respected).

- **Immutable domain history:** the fact ledger (facts + committed consequences). It **is** the business record, like a register or ledger, not a technical event stream.
- **Persisted state:** the ledger + `org_memberships` (identity, number, version).
- **Derived:** periods, segments, predicates, projections, integration events.
- **Replayable:** Resolution over the ledger (pure; on every load). KnownAt = Resolution over a prefix.
- **Not replayed at runtime:**
  - command handlers;
  - authority checks;
  - **g2** (consequences are frozen);
  - integration-event publication;
  - there are no `apply()` event handlers mutating state, no event store, no snapshots, no upcasting.
- Integration events are a separate outbox projection, not the ledger.

## 11. Temporal audit

| Time | Representation | Owner | Rationale |
|---|---|---|---|
| effectiveAt | **calendar date in the organisation's legal calendar** (EP-I1) | the deciding authority | statutes and decisions are dated; legacy timestamps normalise deterministically to dates in the organisation's time zone |
| decidedAt | instant (UTC) | authority | appeal deadlines; evidence |
| recordedAt | instant (UTC), assigned at commit, monotone in seq | Membership | KnownAt |
| seq | int per Member | Membership | ties and chains |
| DB transaction time | never read | infrastructure | — |

- **Time zone evidence:** `organisations.timezone` exists (`2026_05_19_211617_add_timezone_to_organisations.php:15`, default `UTC`) but is **not fillable and not read** (`app/Models/Organisation.php:18-40`). Elections have their own `timezone` (`2026_05_19_211659…:15`). **Condition:** the organisation time zone must be made usable before Slice 2 (an implementation task).
- **Query boundary:** `MembershipQueryPort` takes a **date** in the organisation calendar. Converting an Election instant to that date is the **Election adapter's** responsibility (DS-2), which keeps Election's time semantics out of Membership.
- **Interval convention:** `[from, until)` on dates. "Member until 31.12." means `until = 01.01.`. This is a UI / translation rule, not domain.
- **Is date granularity compatible with the business?** No requirement in the corpus needs intra-day membership semantics (partial-day suspension, same-day ordering beyond seq). This is classified **BUSINESS CONFIRMATION** (one question to the PO). Slice 0 is granularity-agnostic (`Interval` over a comparable time point), so it is not blocked.
- **Unknown dates:** no period is created; evidence is kept (BD-NORM).
- **Imprecise dates** (month / year): normalised by a recorded decision; precision stored.
- **Ties:** same effective date → seq.

## 12. Tenancy audit (EP-Q1)

**Evidence** (dedicated inventory; key claims re-checked):

| Question | Answer | Evidence |
|---|---|---|
| decided rule | **one database; every tenant row carries `organisation_id`; no cross-tenant visibility** | `CLAUDE.md:30-33,75-78`; `docs/architecture/Architecture_Handover_Release_2.0.md:11,34` (ADR-T16: the domain is tenant-free; tenancy at the infrastructure boundary). **No dedicated tenancy ADR exists** |
| database per tenant? | **not decided**: brainstorm only | `docs/publicdigit/brainstorming/multi-database-system/README.md:5` ("Not an ADR … Nothing here is authorised") |
| `tenant` connection | **does not exist** | `config/database.php` defines `pgsql` (:70), `pgsql_audit` (:99), `testing` (:140), …; no `tenant` / `landlord` |
| legacy tenant-connection code | **dead, and dangerous if revived** | `Domain/Models/Member.php:63`; Membership routes commented out (`MembershipServiceProvider.php:586`) |
| "Tenant" / "Landlord" migration folders | naming only; all run on the default connection | `AppServiceProvider.php:405,409`; no `Schema::connection` |
| is `organisation_id` sufficient? | yes, as the decided mechanism, **provided every Membership read and write carries an explicit `TenantId`** | `TenantIdEnforcementTest.php:26`; `app/Contracts/TenantContextInterface.php` ("use cases … receive TenantId") |
| tenant context in HTTP | split three ways: static `TenantContext`, session, `X-Tenant-Id` header | `app/Http/Middleware/TenantContext.php:47-85` |
| **hazard 1** | `BelongsToTenant` falls back to slug `'platform'`, which does not exist, so the scope becomes `organisation_id IS NULL` | `app/Traits/BelongsToTenant.php:46-57` |
| **hazard 2** | the `X-Tenant-Id` header is accepted with **no membership check** | `TenantContext.php:50-56` |
| jobs outside HTTP | **no tenant context** (PBDIGIT-211, ARB decision pending). The working pattern is an explicit tenant parameter | `PBDIGIT-211…:10,16-21`; `GovernanceRebuildProjections.php:32-33` |
| migrations | the default migrator; tables carry `organisation_id` | — |

**Disposition (R1, R14):**
- **The new Membership tables live on the default connection**, with `organisation_id NOT NULL` on every row.
- **Membership must not rely on `BelongsToTenant`'s implicit scope:**
  - repositories and query handlers take the shared `TenantId` explicitly;
  - every query filters `organisation_id` explicitly;
  - an architecture test forbids `withoutGlobalScopes()` in the new Membership infrastructure.
- **The domain needs no scheduled jobs**: expiry and lapse are derived.
- **Projection and outbox workers** take an explicit `TenantId`, following the `--tenant` pattern, until PBDIGIT-211 is decided.

**EP-Q1 is closed by evidence.** The Principal Reviewer may want a tenancy ADR, which is governance housekeeping, not a blocker.

**Hazard 2 is a security defect outside this scope.** **I recommend a backlog item** (not created here: the commission forbids stories). Demo mode (`organisation_id = NULL`) is not a Membership requirement; Membership rows require a real organisation.

## 13. Election boundary

| Port query | Owner of the meaning | Note |
|---|---|---|
| `isMember(person, org, date)` | **Membership** (∃ period InForce) | no fees, no type rights |
| `isSuspended(person, org, date)` | **Membership** (restriction) | what suspension *means* for voting is **Election's** |
| `currentType(person, org, date)` | **Membership** (type segment) | whether a type "grants voting rights" is a classification Election reads (BD-GVR); Membership does not evaluate it |
| `membersAt(org, date) → [person, type, suspended]` | **Membership** | replaces EP-01's `membersInForce` (same meaning, one name) |
| fee standing | **Finance** (DS-5) | **not on this port** |
| authority | **Governance** | not on this port |

**Proof that Membership.isMember ≠ Election.canVote** (DERIVED from adopted decisions):
- **canVote ⇏ isMember:** Election-Only (`VoterSourceStrategy::ImportedVoterRegistry`, `app/Domain/Election/Enum/VoterSourceStrategy.php:47`) entitles voters with no Membership at all.
- **isMember ⇏ canVote:** VotingEntitlement = registered ∧ role = voter ∧ status active ∧ org match ∧ ¬deleted (ADR_20261004_1549). A member with no ElectionMembership registration has isMember = true and canVote = false. PBDIGIT-68 D-ENT-1: organisation membership is only a prerequisite for admission.

**Membership computes no VotingEntitlement:**
- the port returns membership predicates only;
- the legacy predicate (status ∧ fees ∧ `grants_voting_rights` ∧ expiry, `EloquentVoterEligibilityQueryService.php:108-121`) is rebuilt **in the Election adapter**, from port + Finance + BD-GVR, not in Membership;
- the legacy `Membership/Domain/Voting/VotingEligibilityPolicy` is deleted after migration (EP-01 §13).

## 14. Finance boundary

- **Dependency direction:**
  - Finance owns Fee / FeePaid / settlement;
  - Membership publishes membership facts (RA-12) that Finance may use for billing;
  - Membership **may later** consume Finance's published standing evidence (DS-5). **Standing is not in the first implementation.**
  - Election takes fee standing from Finance (via its adapter), not from Membership.
- **Avoided anti-pattern:** "Membership → internal Fee aggregate → Finance" is the **legacy** state (`app/Contexts/Membership/Domain/Fee/*`). The target forbids it. The relocation is DS-1 (Slice 11). Until then, the legacy Fee stays where it is, untouched by the new aggregate.
- **Events (R6):** EP-01 proposed seven events. Each is classified below, against RA-12.

| Proposed | Class | Disposition |
|---|---|---|
| `FactAnnulled`, `FactVoided`, `FactCorrected` | integration events (RA-12) | keep; publish when a consumer slice exists |
| `PeriodTrimmed`, `TrimRevived` | integration events (RA-12) | keep; needed by Finance only if DS-1 bills trimmed periods |
| End, Suspend (RA-12) | integration events | keep (namespaced; avoid the legacy committee `MembershipSuspended`, C-1) |
| `PeriodOpened`, `PeriodEnded` | **not in RA-12**; duplicates the Admit / End facts | **removed** |
| fact stream for Audit | integration (FIB §5: "Membership → Audit: the fact stream") | one `MembershipFactRecorded` per committed fact |

## 15. Governance boundary

- **Direction:** Governance (competence) → `AuthorityCheck` port → Membership records the evidence. The repository has Governance authority classes but **no binding** (`AuthorityResolver` etc.), and today's approval uses an unregistered `MembershipPolicy` that checks `user_organisation_roles.role ∈ {owner, admin}` (`MembershipPolicy.php:104-110`).
- **The interim `OrganisationRoleAuthorityCheck` (R12) means exactly:** "the actor held application role X in the organisation at checkedAt". It does **not** mean "the actor was the competent organ under the statutes". The evidence records `capacity = application_role:<role>`, `ruleRef = interim:org-role`, `competenceVerified = false`.
- **Sunset:** it is replaced in Slice 9 (DS-4). Facts recorded under it **keep** their evidence: they are never relabelled as competent decisions. Governance may later attest competence with its own record.

## 16. Migration audit

| Legacy field | Target | Confidence | Precision | Missingness | Reconstructable? | Human decision |
|---|---|---|---|---|---|---|
| `joined_at` (timestamp) | Admit.effectiveAt (attested) | medium | day after time-zone normalisation | nullable, so **no period** when null | partly | BD-NORM for null / imprecise |
| `ended_at`, `end_reason` | End (attested; kind mapped or `legacy_unknown`) | medium | day | reason often free text | partly | the kind mapping table (PO) |
| `membership_expires_at`, `last_renewed_at` | first term segment + Renew (attested) | medium | day | early renewals unrecorded | **no** before the ledger existed | mark unknown; no invented renewals |
| `members.status` | **no fact by itself**: `expired` is derived; `ended` corroborates End; `suspended` → Suspend (attested, date **unknown**) | low | — | no dates for status changes | **no** | a registrar decision per suspended record |
| `membership_number` | root attribute | high | — | null allowed | yes | collisions / regenerations reported (`OrganisationUserImportService.php:351` regenerates) |
| `fees_status` / `fee_state` | **Finance** (DS-1) | low (two disagreeing columns) | — | — | — | Finance reconciliation |
| renewals (`membership_renewals`) | Renew (attested) | medium | day | — | yes, from the ledger's start | — |
| affiliation (`organisation_users`) | **Organisation context**; PersonRef = `user_id` | high | — | `organisation_user_id` nullable | yes | none |
| `personal_info`, `residence_geo_unit_id`, `newsletter_*` | **no Membership target** (R11) | — | — | — | — | owner decision |

**Safety properties of Migration(M) → TargetMembership(M):**

| Property | Mechanism | Test |
|---|---|---|
| no invented facts | every imported fact is `attested`, with the source row id; nulls produce no fact | fixture: null `joined_at` → no period |
| no silent deletion | legacy rows untouched; retired only after cutover | row counts before and after |
| no unintended overlap | import goes through G\*; overlaps are **reported, never trimmed** | overlap fixture → report entry |
| deterministic dry run | a pure function of the legacy snapshot + the normalisation decisions | run twice → byte-identical report |
| explicit unresolved records | a report section per class | — |
| parity evidence | IsMember and type (target) vs the legacy expectation per organisation; the Election adapter vs the legacy SQL | parity test per organisation |
| per-organisation cutover | a flag on the organisation | — |
| rollback | flip the flag back. The legacy rows are current thanks to the reverse projection (below). Facts recorded after cutover stay in the ledger | rollback rehearsal |

**"No dual-write": challenged.**
- **The hazard:**
  - legacy readers of `members` outside the Membership context: **23 application files**, e.g. `NewsletterService.php`, `ParticipantController.php`, `OrganisationController.php`, `User.php`, `ElectionMembership.php`, `VoterEligibilityService.php`, `EloquentVoterEligibilityQueryService.php`;
  - legacy writers include a query-builder mass update that bypasses Eloquent model events (`ExpireMemberships.php:19`).
- **Required (R10):**
  1. **A single-writer reverse projection.** For cut-over organisations, the new ledger is the only writer. A projector maintains the legacy `members` row as a **derived** read model until every reader is ported. This is not dual-write: one authoritative writer, one derived copy.
  2. **A write guard that catches every legacy write path** for cut-over organisations, including mass updates. Use a database-level guard or the removal of the job for those organisations; an Eloquent `saving` hook alone is insufficient.
  3. **A write freeze per organisation during its migration window** (the migration race).

## 17. Concurrency audit

The known lost update is reproduced (FP §16.10; `concurrency.py`): **683 / 23,522** blind pairs violate I.

**Required retry path (R3):**

```text
reload (v+1) → re-run AuthorityCheck → guards → g2 (recompute f⁺) → G* → commit(expectedVersion = v+1)
```

**Never reuse the old consequences.**

**Evidence that re-checking invariants alone is insufficient** (`persist_check2.py`, 206,590 simulated conflicts):

| Outcome when the stale f⁺ is merely re-checked against I | Count |
|---|---:|
| would commit, but the correct path **refuses**: target no longer live (e.g. an Annul of an already-annulled fact) | **8,833** |
| would commit, but the correct path **refuses**: Correct target no longer active | **1,172** |
| would commit with stored consequences ≠ g2(actual prefix) (resolution equal now, divergent later) | **1,344** |
| re-check refuses (invariant) | 7,630 |

| Race | Mechanism | Status |
|---|---|---|
| same aggregate | expected version | VERIFIED WITHIN BOUNDS (model) |
| (p,o) creation race | `UNIQUE(organisation_id, person_ref)`; the loser reloads and re-decides | design; test required |
| correction race / termination race / admission race | expected version + full re-decide | VERIFIED WITHIN BOUNDS (model) |
| migration vs live command | per-organisation write freeze during import; the import uses the same command path | design; test required |

## 18. Slice audit

- **Slice 0 was too large** (R8). Revised as a **minimal executable semantic kernel**:
  - value objects, `Interval`, the Admit / End facts (Admit with optional first term segment);
  - the **full-shape** Resolution pipeline (stages for live / active / value / LiveCut exist, but are trivial without reversal facts);
  - predicates InLifetime, Ended, TermCovered, InForce, IsMember, CurrentPeriod;
  - InvariantSet (INV-1, INV-2, INV-EP, INV-9, INV-COV) + G\*;
  - a seeded generator for Admit / End.
  - **ConsequenceGenerator moves to Slice 7** (only Correct needs it).
  - No Laravel, no repository, no application service.
- **Slice 1 split** (R9):
  - **1a:** the repository port + an in-memory repository + version / seq / idempotency semantics + concurrency contract tests (domain-facing; no DB);
  - **1b:** DB mechanics (tables, constraints, transaction, outbox), now unblocked by R1.
- The other slices are unchanged in order. The committee relocation stays a separate track.

## 19. Requirements traceability

| Requirement | Domain rule | Invariant | Command | Persistence | Test | Acceptance evidence |
|---|---|---|---|---|---|---|
| admission | Admit opens a period | INV-9, INV-EP | AdmitMember | fact row | domain + invariant | refusal on overlap |
| consent / authority | provenance + AuthorityCheck | — | AdmitMember | provenance jsonb | domain | evidence on the fact |
| re-entry | new Period, same Member | INV-M | AdmitMember | UNIQUE(org, person) | history L | same MemberId, new PeriodId |
| renewal / lapse | term segments | INV-3, INV-12 | RenewMembership | fact row | temporal (history M) | gap preserved |
| suspension / restoration | restriction | — | Suspend / Restore | fact rows | domain | IsMember unchanged |
| termination | End (kind) | INV-2 | EndMembership | fact row | invariant | Ended ⇒ ¬InForce |
| correction / annulment / void | §6 matrix | CFC+, INV-COV, counterfactual | Correct / Annul / Void | facts + consequences | property + CX families | 0 violations in the harness |
| history / KnownAt | ledger + recordedAt | INV-6, INV-14 | — | recorded_at, seq | temporal | pre-correction report reproducible |
| number | assigned once, per-org unique | set rule | AdmitMember (or Slice 6) | column + UNIQUE | persistence | duplicate refused |
| type | type segments | INV-11 | ChangeType | fact | domain | no entitlement |
| concurrency | expected version + re-decide | all | all | version | concurrency | lost-update pair |
| tenancy | explicit TenantId | isolation | all | organisation_id | architecture | no cross-tenant read |
| Election integration | query port | INV-11 | — | — | parity | adapter = legacy predicate |
| migration | attested facts | never invent | import | facts | migration | dry-run determinism |

- **Orphan requirements:**
  - **Standing** (no slice until DS-5);
  - **the legacy product functions** that read `members` (newsletter, participants, dashboards) are served by the reverse projection (R10) until ported;
  - **`personal_info` / geo / newsletter columns** have no owner (R11).
- **Orphan mechanisms removed:** `PeriodOpened` / `PeriodEnded`, the `period_id` column, the consequence child table, the projections in Slice 1.

## 20. ML / computational-method boundary

- **Computation used here:** bounded model checks (exhaustive and random) and a lost-update simulation. Every count in this review is a count over **generated histories or repository files**, **not** a population statistic. For example, "23 reader files" and "~60 committee tests" are repository observations, not evidence about business frequency.
- **ML: not needed for Slices 0–12.** **Optional for Slice 13**, as a reviewer aid in dry-run reports:
  - clustering anomalous legacy histories;
  - duplicate-number patterns;
  - inconsistent status / date combinations.
- **ML never decides** Membership truth, admission, authority, legal validity, voting rights, termination or consent. ML output is a **suggestion** in the report; the registrar's recorded decision is the **authoritative** act.

## 21. Proof-status matrix

| Claim | Status | Evidence |
|---|---|---|
| ratified architecture preserved | REPOSITORY EVIDENCE + this review (§3) | no conflict found |
| Resolution correct | PROVEN (non-circularity, termination, determinism: FP §14.4, §16.3) + VERIFIED WITHIN BOUNDS (FP §14–§16) | — |
| consequences consistent with Resolution / persistence | PROVEN (by the optimistic-lock argument, §7) + VERIFIED WITHIN BOUNDS (114,766 facts, 0 mismatches) | `persist_check.py` |
| correction algebra complete | PROVEN (finite case analysis, §6) + VERIFIED WITHIN BOUNDS (`freeze.py`) | — |
| persistence faithful (round trip) | VERIFIED WITHIN BOUNDS (20,000 histories) | model level; a DB-level test is required in Slice 1b |
| concurrency safe | PROVEN (given the full re-decide on conflict) + VERIFIED WITHIN BOUNDS | §17 |
| tenant isolation safe | REPOSITORY EVIDENCE (the decided rule) + IMPLEMENTATION CHOICE (explicit TenantId) | **existing hazards 1 and 2 are outside Membership; hazard 2 → backlog** |
| Election boundary safe | DERIVED (ADR_20261004_1549, PBDIGIT-68) | §13 |
| Finance boundary safe | BUSINESS DECISION pending (DS-1 / DS-5) | §14 |
| Governance boundary safe | IMPLEMENTATION CHOICE (interim) + OPEN (DS-4) | §15 |
| migration safe | IMPLEMENTATION CHOICE (properties §16) + BLOCKED for Slice 13 (BD-NORM, R10 mechanisms, R11 owner) | §16 |
| requirements traceable | REPOSITORY EVIDENCE + §19 | orphans named |

## 22. Minimality audit

| Component | If removed | Verdict |
|---|---|---|
| projections | nothing ratified fails; list performance is unknown | **defer** (R7) |
| events beyond RA-12 | nothing fails | **remove** (R6) |
| consequence child table | nothing fails (jsonb on the fact is enough) | **replace by jsonb** (R4) |
| `period_id` column | nothing fails (derivable) | **remove** (R5) |
| JSON payload / provenance | typed columns for every kind would be heavier; validation keeps the schema | **keep**, schema-validated |
| `effective_precision` | RA-15 fails | keep |
| idempotency key | KnownAt determinism under retries fails | keep |
| version | the concurrency requirement fails | keep |
| reverse projection (migration) | legacy readers break or go stale after cutover | **keep** (temporary) |
| property-testing library | a seeded generator suffices | **optional** (implementation choice) |
| helper services (`ConsequenceGenerator` in Slice 0) | nothing in Slices 0–6 needs it | move to Slice 7 |

## 23. Conditions / blockers and Revision 1 to EP-01

**Revisions applied to EP-01** (evidence-based; marked "Rev 1" in the document):

| # | Revision | Evidence |
|---|---|---|
| R1 | C-4 / EP-Q1 corrected: single database, default connection, `organisation_id` rows; the `tenant` connection is dead code | §12 |
| R2 | ex-nunc mapping removed from the baseline (LQ-2 OPEN) | §4 |
| R3 | conflict retry = reload + re-authorise + guards + g2 + G\*; never reuse | §17 |
| R4 | consequences = jsonb on the fact row, with `g2_version`; no child table | §7, §9 |
| R5 | a single `target_fact_id`; `period_id` removed | §9 |
| R6 | events aligned with RA-12; `PeriodOpened` / `PeriodEnded` removed; a fact stream for Audit | §14 |
| R7 | projections deferred to measured need | §9, §22 |
| R8 | Slice 0 = minimal kernel; ConsequenceGenerator → Slice 7 | §18 |
| R9 | Slice 1 split into 1a (domain-facing) and 1b (DB) | §18 |
| R10 | migration: single-writer reverse projection, a write guard incl. mass updates, a per-org write freeze | §16 |
| R11 | legacy `personal_info` / geo / newsletter columns: outside the aggregate; owner OPEN | §8 |
| R12 | interim authority evidence labelled technical-only; sunset at Slice 9 | §15 |
| R13 | membership number assigned once; reassignment OPEN | §4 |
| R14 | explicit `TenantId` everywhere; no reliance on `BelongsToTenant`; a fitness test against `withoutGlobalScopes()` | §12 |

**Conditions** (for classification B):

| Condition | Applies to |
|---|---|
| PO / Principal Reviewer approval of EP-01 Rev 1; a story ID per slice; the sequencing act (EM-SEQ-001) | all |
| — (no further condition) | **Slice 0, Slice 1a** |
| the organisation time zone made usable (fillable / readable) + PO confirmation that no intra-day semantics are needed | Slice 1b / 2 |
| BD-CONSENT / LQ-1, BD-REC0, BD-3 (or an interim End-kind list accepted by the PO); the interim authority adapter accepted as technical-only | Slice 2 |
| BD-TYPE / BD-SUSP / BD-2 + BD-NU | Slices 4 / 5 / 6 |
| **BD-REV-ER** | Slice 7 |
| DS-4 | Slice 9 |
| DS-2 (Election owner), BD-GVR, DS-5 | Slice 10 |
| DS-1 / DS-5 | Slice 11 |
| BD-NORM, EP-Q3, the R10 mechanisms, the R11 owner decision | Slice 13 |

**Out-of-scope findings (recommend backlog items; not created):**
- the `X-Tenant-Id` header accepted without a membership check (`app/Http/Middleware/TenantContext.php:50-56`);
- the `BelongsToTenant` fallback slug mismatch (`app/Traits/BelongsToTenant.php:50-57`);
- `TenantContextInterface` bound to a class that does not implement it (`AppServiceProvider.php:99`).

## 24. Final approval classification

**B: APPROVED WITH CONDITIONS** (recommended).

- Implementation may begin **only for Slice 0 and Slice 1a** once EP-01 Rev 1 is approved, a story ID exists and the sequencing act is issued.
- Every later slice starts only when its row in §23 is satisfied.
- **No architecture conflict.**

**STOP.**
