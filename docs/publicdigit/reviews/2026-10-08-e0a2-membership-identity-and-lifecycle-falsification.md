# E0a-2: Membership Identity and Lifecycle Falsification

**Type:** Research report for **Principal Reviewer** review · falsification and formalisation · **decides nothing; implements nothing** · **Date:** 2026-10-08
**Commission:** PO, *"E0a-2 Principal Review Phase: Falsification, Formal Logic and Membership Identity Theory"* (2026-10-08)
**Baseline (not repeated):** `2026-10-08-e0a2-membership-development-report.md` (**DR**), plus R / Q / D (reconciliation, question register, discovery)
**Raised by:** `claude-code-session:42e0287e…` (self-declared, not attestable)

> **Status labels:** FACT · DECIDED · DERIVED (follows formally from facts or definitions) · CANDIDATE · HYPOTHESIS · OPEN · CONTRADICTED.
>
> **Evidence categories:**
> - **DIRECT:** a requirement or decision states it for Organisation Membership;
> - **INDIRECT:** a document, test or design intent about Organisation Membership implies it;
> - **ANALOGICAL:** another lifecycle (committee, election);
> - **IMPLEMENTATION:** what the code does;
> - **INFERRED:** reasoning;
> - **UNKNOWN.**
>
> Nothing here is called "proven" unless it is a formal consequence of stated definitions.

---

## 1. Executive conclusion

1. **The hypothesis "termination is final; rejoin creates a new MembershipId; lapse is resumable" does not survive as one claim.** It splits into three claims of different strength:
   - **(a) "lapse is resumable within the same membership":** **STRONGLY SUPPORTED** (IMPLEMENTATION + INDIRECT).
   - **(b) "termination is final":** **SUPPORTED**, but **only meaningful once identity is fixed**. Under one candidate identity model (B) "final" and "rejoin" contradict each other.
   - **(c) "rejoin creates a new MembershipId":** **INSUFFICIENT EVIDENCE.** There is **no DIRECT evidence**. The only INDIRECT statement (*"To reinstate, a new application is required"*) requires a new **application**, not a new **identity**. The implementation is **self-contradictory**: six re-entry paths behave six different ways (§7).
2. **Identity logically precedes termination and rejoin.** The smallest sufficient decision is therefore **not** EQ-7/EQ-8, as DR §19 claimed. It is **D-ID: the identity anchoring of an Organisation Membership**, with **membership-number semantics** as its business-facing form (§5, §21).
3. **Model B (one enduring identity across rejoin) is falsified as a sole identity**, *provided* the domain must let historical artefacts name the membership they refer to (the exhaustive exploration found **30,015 / 30,015** rejoin histories in which an old reference becomes ambiguous; §11). Repairing B means adding an episode identity, and B becomes C.
4. **Model C reduces to Model A plus an anchor that already exists.** The person-in-organisation continuity C needs is the `(personId, organisationId)` pair, which the platform already has as the **affiliation** (`organisation_users`). C is justified as a *distinct* concept **only** if something must carry identity *across* episodes that the pair cannot. The one plausible candidate is the **membership number** (an organisation-issued, externally visible credential). Whether it persists across rejoin is **UNKNOWN** and is a **business decision** (§5).
5. **Formal results that hold independently of the open decisions (DERIVED):**
   - effectiveness and lifecycle are **orthogonal** (SUSPENDED × lapsed is reachable), so **lapse cannot be a member of the same state enum** without composite states;
   - termination must **close the effective interval**, or "was a member at t" gives wrong answers;
   - the legacy machine reaches **two contradictions** (re-admission violates UNIQUE; `expired` while effective after renewal).
6. **DR's claim "EQ-1 is nearly answered" is withdrawn** (§19).
7. **The number-generation requirement is currently broken.** The test pinning *"approval generates a unique membership number"* **fails** (run 2026-10-08, `--env=testing`: 2 failed).

---

## 2. Evidence reviewed (new in this phase)

| Evidence | Category | Finding |
|---|---|---|
| `MemberApprovalCreatesCorrectMemberTest` (**run**: 2 failed) | IMPLEMENTATION + INDIRECT (a test = an intended requirement) | **intent:** approval generates a membership number starting with `M`, unique per member. **Fact:** the approval path (`ApproveMembershipApplication` → `EloquentMemberRepository::save`) never writes it, so the number is null |
| `developer_guide/membership/membership_management/membership_lifecycle_implementation.md:35-85` | INDIRECT | documents *"Phase 1 — `membership_type_id` & `membership_number` on Approval"* (`'M' . Str::random(8)`), so **the requirement existed and regressed** when approval moved to the DDD use case |
| `members.membership_number` `nullable()->unique()` | IMPLEMENTATION | **globally** unique across **all tenants** |
| `MemberImportController:72` CSV columns incl. `membership_number`; tutorial (en/de/np): *"custom membership ID (auto-generated if empty)"*, *"eigene Mitgliedsnummer"* | INDIRECT | the number is an **organisation-issued, externally carried identifier** (organisations bring their own numbers) |
| `MemberImportService:35-90` (the DDD import actually used) | IMPLEMENTATION | **ignores** the CSV `membership_number`; **skips** existing e-mails (`existsByEmailForTenant`) |
| Import tutorial (en.json:55): *"Already a member … member record is updated (status, fees_status, expires_at, joined_at, membership_number). Nothing is duplicated."* | INDIRECT | the documented intent is **reuse + overwrite**, which contradicts the code (skip) |
| `OrganisationRoleController::addMember` | IMPLEMENTATION | refuses if **any** Member row exists for the affiliation (*"already a formal member"*), including **ended** |
| `OrganisationMemberInvitationController:206-220` | IMPLEMENTATION | `firstOrCreate` on (org, `organisation_user_id`): a returning person **gets the old row back unchanged** (still `ended`/`expired`); the invitation is marked accepted |
| `MembershipApplicationController::store:68-81` | IMPLEMENTATION | blocks only an **active** member, so an ended or expired member **may apply** |
| `ApproveMembershipApplication:27-83` | IMPLEMENTATION | **always** `Member::register` (a new id) and saves it with the existing `organisation_user_id` → **UNIQUE violation** for a returning person (DERIVED from code; not runtime-reproduced) |
| `OrganisationUserImportService:345-353` | IMPLEMENTATION | `updateOrCreate`: **reuse + overwrite** |
| `docs/publicdigit/membership/20260403_0003_membership_management.md:214-225` | INDIRECT | ending: *"This action cannot be undone. To reinstate, a new application is required."* |
| `architecture_legacy/membership/20260405_0918_membership_ablauf.md:975, :1025` | INDIRECT (legacy design) | numbers prefixed by type: `FM-…` (full) / `AM-…` (associate) |
| `CommitteeLevel` (`minMembershipYears`) | IMPLEMENTATION | a consumer needing **membership tenure**: cumulative or continuous is UNKNOWN |
| `tests/Unit/Constitutional/Membership/{Reapply,Terminate,Restore}MembershipTest` | ANALOGICAL (committee) | terminated → reapply = **new** relationship, history preserved |
| SM2 (2026-09-16) | ANALOGICAL (election), DECIDED | REMOVED final |
| `election_memberships` keyed on `user_id` (no membership reference) | FACT | Election never references a membership identity |

---

## 3. Competing identity models

| | **A: membership = admission episode** | **B: membership = enduring relationship** | **C: relationship + episodes** |
|---|---|---|---|
| Identity | new `MembershipId` per admission | one `MembershipId` per (person, org) forever | `RelationshipId` per (person, org) + `EpisodeId` per admission |
| Termination | ends this identity forever | ends the current *state*; the identity continues | ends the episode; the relationship continues |
| Rejoin | new identity | same identity re-activated | new episode under the same relationship |
| "Final" | well defined | **contradicts rejoin** (final yet re-activated) | final for the episode |
| Implementation match | approval path (new id) | invitation, legacy import, tutorial (reuse) | none |
| Reduction | — | **B + episode ids = C** | **C = A + `(personId, organisationId)` anchor**, *unless* the relationship carries its own attributes (§5) |

## 4. Identity / event matrix

| # | Event | A | B | C | Consequences (audit · history · Election · migration) |
|---|---|---|---|---|---|
| 1 | application | no membership | no membership | no membership (relationship? ❓) | an application is its own aggregate (FACT) in all models |
| 2 | approval | **new M** | new M (first) / reuse (re-entry) | new episode (+ relationship on first) | A/C: the audit binds to M/episode. B: the audit must also carry a date to say *which* period |
| 3 | import | new M; or link to a prior register number | reuse if exists | new episode; or continue | **migration:** the prior register's numbers must map somewhere. Under A a number per episode, under C per relationship |
| 4 | invitation | new M (acceptance as admission) | reuse | new episode | today = B-like reuse **without** re-activation (CONTRADICTED by any model) |
| 5 | activation | same M | same | same episode | — |
| 6 | expiry | same M (lapse) | same | same episode | all models agree: **lapse ≠ end of identity** |
| 7 | renewal | same M, interval extended | same | same episode | all agree |
| 8 | suspension | same M | same | same | all agree |
| 9 | restoration | same M | same | same | all agree |
| 10 | termination | M final | M state → ended (resumable?) | episode final | **B cannot say "final" without forbidding rejoin** |
| 11 | rejoin | **M2 ≠ M1** | **M1 again** | **E2 under R1** | Election: old evaluations keep M1 / E1 (A, C). B loses the distinction |
| 12 | type change | same M (attribute) — or new M if the type defines the membership (FM-/AM- prefixes ❓) | same | same, or a new episode ❓ | **opens a 4th question:** does a type change start a new membership? (UNKNOWN) |
| 13 | organisation migration (tenant split/merge) | new M in the new org (O is constant per M) | breaks invariant `organisation(m)` constant | new relationship | all models keep `organisation(m)` constant. A migration = new memberships + a provenance link |
| 14 | correction (wrong data) | same M, a corrected record | same | same | needs the record-vs-valid time distinction only if retroactive (DR §9) |
| 15 | duplicate application | refused (open application guard, FACT) | same | same | no identity effect |

## 5. Membership number analysis

| Hypothesis | For | Against | Category |
|---|---|---|---|
| **A. Persistent identity of the person in the organisation** | organisations import their **own** numbers (tutorial: *"eigene Mitgliedsnummer"*), which in real registers usually persist; `CommitteeLevel.minMembershipYears` wants tenure | no statement on rejoin; global uniqueness would let one tenant's number block another's | INDIRECT |
| **B. Identity of one membership episode** | generated **at approval** (the episode-creating act) by the intended design; the legacy type prefixes (FM-/AM-) tie the number to the *kind* of membership | no statement on rejoin | INDIRECT + legacy |
| **C. Externally visible credential** | carried on CSV, shown to admins, unique | — compatible with A or B | INDIRECT |
| **D. Legacy identifier** | random `M` + 8 / `uniqid` formats; a separate `{SLUG}-{YEAR}-{SEQ}` generator in the unrouted Desktop/Mobile service | it is in the current fillable fields and tests | IMPLEMENTATION |

**Findings:**
- **C holds in every case (FACT-level INDIRECT).** Whether it is A or B is **UNKNOWN**: no source says what happens to the number on rejoin.
- **CONTRADICTED:** global `unique` vs "organisation-issued own numbers". Two tenants that both number from `1001` cannot both import. The domain meaning (per-organisation credential) implies **per-organisation uniqueness**. That is a DERIVED constraint, not an implementation choice.

**The membership number is the business-facing form of D-ID.** If an organisation keeps a returning member's number, then a cross-episode identity exists in the business, and C is justified (or B's identity is the number). If the number is re-issued, then A suffices. **This is a question an association officer can answer, unlike "which DDD identity model".** It is plausibly **class B** (a tenant parameter on a platform model): *"retain number on rejoin: yes/no"*.

## 6. Historical query analysis

The timeline: t1 member · t2 terminated · (t2, t3) not a member · t3 rejoined · t4 now.

| Query | A | B | C | Legacy today |
|---|---|---|---|---|
| member at t1? | ✓ (M1 effective) | ✓ only with a period history | ✓ | ✓ |
| member at t2? | ✓ iff `t2 < effectiveUntil(M1)`; requires **termination closes the interval** (§10) | needs a period history | ✓ (same requirement) | ✗ (`ended_at` exists; the interval is not closed) |
| member in (t2, t3)? | ✓ (no in-force M) | needs gap representation (multiple intervals) | ✓ | **✗: cannot happen** (re-admission impossible or overwrites the row; the gap is lost) |
| member at t3? | ✓ (M2) | ✓ | ✓ | ✗ |
| which membership did an old document mean? | **unambiguous** (M1) | **ambiguous** (M1 denotes both periods) | unambiguous (E1) | n/a |
| audit record → episode? | by `membershipId` | needs id + date | by `episodeId` | partial |

**DERIVED:** B answers historical membership questions only by adding per-period structure (multiple effective intervals + episode references), which is exactly C. **Criterion met by A and C, not by B.**

## 7. Rejoin analysis: falsification of "rejoin = new MembershipId"

**Search result: no DIRECT evidence either way.**

| Path | Behaviour on re-entry | Implied model |
|---|---|---|
| application → approval | new id → **UNIQUE violation** | A (intent), blocked by the B-shaped constraint |
| invitation | old row returned unchanged | B (silent; no reactivation) |
| admin add | refused (*"already a formal member"*) | "once ever" (neither) |
| DDD CSV import | skipped | none |
| legacy participant import | update + overwrite | B |
| documented intent (tutorial) | update + overwrite | B |
| user guide | *"new application required"* | **A or C** (a new application ≠ a new identity) |
| analogies (committee, SM2) | new relationship / final | A or C |

**Verdict on claim (c): INSUFFICIENT EVIDENCE.** The implementation **contradicts itself** (counterexamples to every single model). The analogies are not proof (as the Principal Reviewer instructed).

## 8. Termination analysis

| Claim | Evidence | Strength | Formal consequence | Counterexample tested | Remaining uncertainty | Status |
|---|---|---|---|---|---|---|
| Termination ends the (episode) membership permanently | guide *"cannot be undone"* (INDIRECT); no code transition out of `ended` (IMPLEMENTATION); committee + SM2 (ANALOGICAL) | moderate | `Terminated(m,t1) ⇒ ∀t2>t1: ¬InForce(m,t2)` | a reinstatement-by-reversal path: none found (`endMembership` has no caller; `reactivate()` requires `suspended`) | resignation withdrawn before its effective date? appeal reverses an expulsion? | **SUPPORTED** (episode-final). A domain decision is needed for appeal/withdrawal |
| Termination is reachable today | — | — | — | **CONTRADICTED:** unreachable (DR §3) | — | FACT |
| Termination closes effectiveness | — | — | needed for §6 | see §11 (TERMINATED × effective reachable) | effective date of a resignation | **DERIVED** requirement |

## 9. Multiplicity analysis

| Candidate | Test cases | Evidence | Verdict |
|---|---|---|---|
| **1:** ≤ 1 Membership(p,o,t) in **any** state | terminated M1 + active M2 coexist **as records** at t4 | if "Membership(p,o,t)" = *exists at t* (incl. history), this **forbids rejoin under A/C** | **FALSIFIED** under A/C (it is the legacy UNIQUE, which produces the §7 crash). Holds only under B |
| **2:** ≤ 1 **IN_FORCE** | suspended M1 + in-force M2 at the same t | allows a person to be suspended in one membership and active in another, which **circumvents suspension** | **CONTRADICTED** by the purpose of suspension (INFERRED, strong) |
| **3:** ≤ 1 **non-terminal** (in-force, suspended or lapsed-not-terminated) | lapsed M1 + new admission M2? | renewal resumes a lapse (FACT), so a lapse is not an end; a second admission during a lapse would duplicate | **CANDIDATE: strongest surviving**. Under B it is trivially true |
| **4:** no global invariant; concurrent types | "full + youth"? | PI-1 places youth/women/student **wings** in **committee type**, not membership type; `members.membership_type_id` is single-valued; no source asks for two concurrent types | **no evidence for**; a counterexample was searched for and not found → **HYPOTHESIS rejected for now** |
| historical overlap (backdated M2 overlapping a terminated M1) | — | requires retroactive admission: no evidence | OPEN (only if backdating is allowed) |
| future overlap (M2 admitted effective after M1 ends, while M1 still in force) | "renewal as a new membership" practice | renewal extends the same membership (FACT) | not needed |

**Order respected:** the invariant (Candidate 3) is stated as a business rule first. **No mechanism is proposed** (§23 states only the requirement ordering).

## 10. Formal temporal model

```text
P persons, O organisations, M membership identities, T time (totally ordered), State = {IN_FORCE, SUSPENDED, TERMINATED}

holder        : M → P × O                      -- I1 (identity): constant over m's life     [DERIVED: all models]
effectiveFrom : M → T
effectiveUntil: M → T ∪ {∞}
life          : M × T → State ∪ {⊥}            -- ⊥ before admission
Effective(m,t)  ↔ effectiveFrom(m) ≤ t ∧ (effectiveUntil(m)=∞ ∨ t < effectiveUntil(m))   -- TC-01 [CANDIDATE]
Lapsed(m,t)     ↔ life(m,t) ≠ TERMINATED ∧ t ≥ effectiveUntil(m)                          [DERIVED def.]
InForce(m,t)    ↔ Effective(m,t) ∧ life(m,t) = IN_FORCE  (∧ Recognised(m) — §19)
IsMember(p,o,t) ↔ ∃m: holder(m)=(p,o) ∧ InForce(m,t)       -- "was this person a member at t?"
```

**Invariants tested:**

| # | Statement | Result |
|---|---|---|
| I1 | `holder(m)` constant | **holds in A, B, C.** Tenant migration creates new identities (§4 #13). DERIVED |
| I2 (termination closes the interval) | `life(m,t)=TERMINATED ⇒ effectiveUntil(m) ≤ t_term_effective` | **required.** Without it, `Effective ∧ TERMINATED` (reachable, §11) makes any query that uses Effective alone wrong. DERIVED (as a requirement on the model) |
| I3 (finality) | `Terminated(m,t1) ⇒ ∀t2>t1: ¬InForce(m,t2)` | consistent with A and C. **Inconsistent with B + rejoin.** SUPPORTED |
| I4 (rejoin) | `Terminate(m1,t1) ∧ Rejoin(p,o,t2) ⇒ ∃m2≠m1: holder(m2)=(p,o)` | **true under A and C (by construction), false under B.** Not decidable from evidence (§7). **OPEN = D-ID** |
| I5 (multiplicity) | `∀p,o,t: |{m : holder(m)=(p,o) ∧ life(m,t) ≠ TERMINATED ∧ life(m,t)≠⊥}| ≤ 1` | CANDIDATE (§9) |
| I6 (identity sufficiency) | an artefact referencing `m` at t1 refers to exactly one period of membership | A, C ✓; **B ✗** (§11) |

## 11. State-machine analysis (exhaustive exploration)

**Method:** all event sequences of length ≤ 7 over {ADMIT, EXPIRE, RENEW, SUSPEND, RESTORE, TERMINATE} (≈ 335k sequences) for one (person, org), under:
- (i) the **legacy machine as implemented**;
- (ii) the candidate lifecycle {IN_FORCE, SUSPENDED, TERMINATED} × effective(bool), under models A, B and C.

The script is in Appendix A; it was run 2026-10-08.

| Result | Count | Meaning | Status |
|---|---|---|---|
| Legacy: re-admission violates `UNIQUE(organisation_user_id)` | 106,431 sequences | rejoin is impossible through approval | DERIVED (model of FACT code) |
| Legacy: `status=expired` while the interval is effective again (after renewal) | 30,015 sequences | lapse stored as a state contradicts effectiveness | DERIVED, **CONTRADICTION** |
| Candidate: reachable (life, effective) pairs = **all six**, incl. (SUSPENDED, ¬effective) and (TERMINATED, effective) | — | lifecycle ⟂ effectiveness. **Lapse cannot be a lifecycle value** without composite states (SUSPENDED-and-LAPSED). TERMINATED needs I2 | DERIVED |
| Model B: an old reference is also the identity of a later episode | **30,015 / 30,015** rejoin sequences | B fails I6 | DERIVED |
| Models A and C: ambiguous old references | 0 | — | DERIVED |
| Guards needed in every model: refuse ADMIT while non-terminal; refuse RENEW after TERMINATE | 85,552 / 25,726 | these are I5 and I3 as command guards | DERIVED |

**No unreachable candidate state** was found. No identity collision occurs in A or C. The only collisions are B's reference ambiguity and legacy UNIQUE.

## 12. Suspension vs restriction

**Formal test:** `SUSPENDED(m) ≡ IN_FORCE(m) ∧ ∀r ∈ Rights: Restricted(m,r)`?

The equivalence **fails** iff a membership suspension has a consequence that is **not** the withdrawal of a right.

| Dimension | As a state (SUSPENDED) | As restrictions (IN_FORCE + restrictions) | Evidence | Discriminates? |
|---|---|---|---|---|
| membership benefits / services | withdrawn | withdrawn per right | none | no |
| dues | ❓ continue / pause | continue (still in force) | none | **yes, if dues pause** |
| renewal / term | ❓ the term clock pauses? | runs | none | **yes, if the term pauses** |
| member counts / quorum | ❓ excluded | counted | none | **yes, if excluded** |
| election participation | EM-FM-003 speaks of *removal*; suspension → FM-6 (OPEN) | a restriction on the election right (PI-5-like) | DECIDED only for removal | indirectly |
| committee participation | lineage has its **own** suspension (FACT) | restriction | FACT (separate) | no |
| termination from the suspended state | allowed (lineage, SM2 analogues) | allowed | ANALOGICAL | no |
| restoration | a state transition | lifting restrictions | IMPLEMENTATION (`reactivate()` from `suspended`) | no |
| participation ban for one process (PI-5) | **cannot** be expressed (it is not the whole membership) | expressed | PO input | — (always a restriction) |

**Result:**
- **(i)** A *participation restriction* is needed **regardless** (PI-5; EM-GOV-002 analogue). DERIVED from the PO input.
- **(ii)** Whether *membership suspension* is a **state** depends on one discriminating question: ***"Does suspending a membership pause the term, stop dues, or exclude the person from member counts?"*** Yes to any → a genuine lifecycle state. No to all → it is a **composite restriction** and SUSPENDED can be dropped from the state set (State = {IN_FORCE, TERMINATED} + restrictions).
- **OPEN (D-SUSP).** The implementation's `suspended` status is IMPLEMENTATION evidence only (unreachable).

## 13. Type / term analysis

| Question | Finding | Status |
|---|---|---|
| Which invariant would require *type assignment* and *term* to change atomically? | Only *"the term is derived from the type duration **and** the type can change mid-term"*. **No type-change command exists**, and the guide says *"duration cannot be changed once members have this type"* (`0003:80`) | **no invariant proven.** Placement stays CANDIDATE |
| Is there a real type-related invariant? | **yes, but a different one:** the obligations of an existing membership must not change when the type *definition* changes. That is solved by **snapshotting** (`fee_amount_at_time`, FACT), not by atomic assignment+term | DERIVED (from FACT design) |
| Does a type change start a new membership? | legacy FM-/AM- numbering suggests the number is tied to the type (INDIRECT, legacy). Otherwise no evidence | **OPEN:** part of D-ID (§4 #12) |
| `grants_voting_rights` vs "MembershipType ≠ VotingEntitlement" | **DECIDED:** entitlement is the election's (EM-ENT-001/007). A type *granting* a vote contradicts that. **Not contradicted:** a type acting as a **criterion fact** an election may filter on (Full Membership *"must have active Organisation Membership"*, plus PI-1 criteria) | **decision to prepare (not taken):** (1) retire the flag + tests; (2) **reinterpret** it as a type *classification* ("voting-class type") that Election criteria may reference, renamed so it no longer *grants*; (3) keep it as legacy. **Option 2 alone is consistent with both the DECIDED rules and the tested intent** |

## 14. Authority dependency analysis

| Concept | Definition (candidate) | Evidence |
|---|---|---|
| **Actor** | the person performing an act | FACT (`created_by`, `renewed_by`, `reviewed_by`) |
| **Role** | a platform authorization label in a tenant (owner/admin/commission/…) | FACT (`user_organisation_roles`) |
| **OrganisationAffiliation** | the person's tenant relationship that carries roles | R §7 (CANDIDATE, EM-VOC-002 basis) |
| **Organ** | a body or office of the organisation defined by its statutes (board, membership secretary, assembly) | **no implementation**; EM-FM-005 "Organisation Chief" has no referent (EM-OPEN-014) |
| **Authority** | the competence of an organ to perform an act type, conferred by statutes | none; election analogue: ADR §3 (election-scoped Chief/Deputy) |
| **Decision** | one exercise of authority: act + actor + organ + reason + time | partial (renewal ledger; unused review columns) |
| **Evidence** | the record proving the decision | partial |

**Dependency result (DERIVED):**
- Authority is **orthogonal to identity, multiplicity and the state set**. None of I1–I6 mentions who decides.
- Authority is **required before**: command specifications (who may `Admit` / `Terminate`), MS-3, the decision record schema, and the published "recognised" fact if recognition = an organ's act.
- So authority is **not upstream** of D-ID. It runs **in parallel**, and must close **before the aggregate's commands**. "owner/admin" stays IMPLEMENTATION evidence only.

## 15. Election impact (consumer constraints only; Election not redesigned)

- **FACT:** `ElectionMembership` and `VotingEntitlement` key on **person** (`user_id`), not on a membership identity. Hence "old election → M1, new election → M2" is **already unambiguous on the election side**. Membership identity enters Election only through:
  - (a) **eligibility evaluation at registration** (which membership satisfied the criteria: an EligibilityEvaluation should record the `membershipRef`, which is possible under A and C, ambiguous under B);
  - (b) the **EM-FM-003 trigger**.
- **The exact mapping question (for the PO; not decided here). FM-5 refined:**

> **Q-FM-MAP:** *For EM-FM-003, is "removal of Organisation Membership" evaluated against (i) the **specific membership** that satisfied the election's criteria at registration, or (ii) the **person's membership status** in the organisation (`IsMember(p,o,t)`)? And which Membership-side transitions count as removal: termination (which kinds?), lapse, suspension?*

- Under (i) + A: M1 terminated → suspended; rejoin (M2) **does not** lift it (a new admission is needed under the old election's criteria: W-1/FM-7 answered "no auto-lift").
- Under (ii): rejoin automatically satisfies `IsMember` again, which points to auto-lift.
- **The choice of identity model and Q-FM-MAP interact.** This is why D-ID precedes the election mapping in §20.

## 16. Counterexamples found

| # | Counterexample | Refutes |
|---|---|---|
| CX-1 | the approval path creates a new id for a returning person → UNIQUE violation | "the implementation supports A" (it is blocked by a B-shaped constraint) |
| CX-2 | invitation silently returns an `ended` row | "the implementation supports B with reactivation" |
| CX-3 | admin add refuses ended persons | rejoin being possible at all, by that path |
| CX-4 | old-reference ambiguity in 30,015/30,015 B rejoin histories | **Model B as the sole identity** (given I6) |
| CX-5 | `expired` ∧ effective after renewal (30,015 legacy sequences) | "lapse is a lifecycle state" (as implemented) |
| CX-6 | (TERMINATED, effective) reachable | any definition of membership-at-t that ignores termination (motivates I2) |
| CX-7 | suspended M1 + in-force M2 | multiplicity Candidate 2 |
| CX-8 | global `unique(membership_number)` vs organisation-issued numbers | "the membership number is an organisation credential" *as implemented* |
| CX-9 | the "approval generates a number" test fails | the guide's claim that the number is generated on approval (regressed) |
| CX-10 | "new application required" ≠ "new identity" | DR's reading of the guide as support for a new MembershipId |

## 17. Counterexamples searched for and not found

- No source (requirement, test, guide, ticket) requires **concurrent memberships of two types** (Candidate 4).
- No source requires **future-dated** or **backdated** admissions.
- No **reversal of termination** path (other than a hypothetical appeal).
- No consumer references a **membership id** across contexts (Election keys on person; the committee lineage on MemberId within Membership).
- No source states that the **membership number is re-issued** on rejoin, **nor** that it is kept.
- No evidence that **suspension pauses term or dues**, **nor** that it does not.

## 18. EQ-7 / EQ-8 verdict

| Sub-claim | Verdict |
|---|---|
| Lapse is resumable within the same membership | **A. STRONGLY SUPPORTED** (IMPLEMENTATION: renewal extends the same row; INDIRECT: self-renewal 90 days after expiry; DERIVED: lapse ⟂ lifecycle) |
| Termination is final | **B. SUPPORTED BUT REQUIRES A DOMAIN DECISION:** final **for an episode**; appeal and withdrawn resignation are undecided; meaningless under Model B |
| Rejoin creates a new MembershipId | **C. INSUFFICIENT EVIDENCE.** No DIRECT evidence; the implementation contradicts itself; the analogies are not proof. **Logically subordinate to D-ID** |
| "EQ-7/EQ-8 is the smallest unlocking decision" (DR §19) | **D. FALSIFIED.** Identity precedes it (§20) |

## 19. EQ-1 / EQ-2: residual questions

**Withdrawn:** DR's *"EQ-1 nearly answered by practice"*. Practice shows **where the code creates rows**, not when a membership exists in the domain. The counterexamples:
- invitation and import create rows with **no decision**;
- the business case says *active after payment*;
- `joined_at` is not written on approval, so **effectiveness has no start** in the main path.

| Predicate | Logically distinct? | Does the domain need it? | Residual question |
|---|---|---|---|
| `Exists(m)` | yes | **yes:** identity (I1) | D-ID |
| `Recognised(m)` | yes: differs from Exists exactly for **registry entries / imports / invitations** (no organ decision) | **only if** an imported or invited membership may lack recognition | **EQ-2′:** *"Is a membership entered from a prior register (or by invitation) recognised by that act, or does an organ have to confirm it?"* |
| `Effective(m,t)` | yes (FACT: an interval exists) | **yes** | the start date when not written (approval path); the tenant time zone (G2) |
| `InForce(m,t)` | derived | **yes** (it is what consumers ask) | depends on D-SUSP and EQ-2′ |

## 20. Revised decision dependency graph

```text
                    D-ID  Membership identity anchoring
           (episode identity [A] vs enduring identity [B]; C = A + (person,org) anchor
            unless the relationship carries its own identity — business form:
            "Does a returning member keep the membership number?"  [tenant parameter?])
                                   │
            ┌──────────────────────┼─────────────────────────┐
            ▼                      ▼                         ▼
   Termination finality     Multiplicity (I5)        Type-change ⇒ new membership?
   + appeal / withdrawal    (Candidate 3)            (§4 #12)
            │                      │                         │
            ▼                      │                         │
        Rejoin (I4)  ◄─────────────┘                         │
            │                                                 │
            └──────────────┬──────────────────────────────────┘
                           ▼
       Lifecycle state set ── D-SUSP (suspension: state or restriction?) ── lapse DERIVED (not a state)
                           │                                     ▲
   EQ-2′ (recognition of   │                                     │
   imports/invitations) ───┤                                     │
                           ▼                                     │
            InForce / IsMember definitions                       │
                           │                                     │
                           ▼                                     │
     Election mapping  Q-FM-MAP (membership-level vs person-level; which transitions = removal)  [Election-side, PO, DEFERRED phase]
                           │
                           ▼
              Published facts (minimum set; MembershipFacts v0 withdrawn as premature)
                           │
                           ▼
     Aggregate + commands  ◄────────────  AUTHORITY (organ model; EQ-5/EM-OPEN-014) — parallel track, must close before commands
                           │
                           ▼
                 Concurrency requirement (I5, I3 guards)
                           │
                           ▼
            Persistence mechanism (constraint/index choice) → Migration (UNIQUE, global number uniqueness, cascade)
```

**Corrections relative to the commission's draft graph:**
- **D-ID** is the root, with membership-number semantics as its business proxy.
- **Type-change** is a third child of D-ID.
- **D-SUSP** and **EQ-2′** feed the lifecycle and the InForce definition, independently of rejoin.
- **Authority** is a parallel prerequisite of the aggregate's commands, not of identity.
- **Lapse is not a decision node**: it is DERIVED.

## 21. What can now be decided (smallest logically sufficient set)

Four business decisions, plus one formal adoption:

1. **D-ID:**
   - *"When a former member returns, is that the same membership continuing (and do they keep their membership number), or a new membership (with a new number)?"*
   - plus *"Does changing membership type start a new membership?"*
2. **D-TERM:** *"Is termination final for that membership, and are appeal or a withdrawn resignation reversals (the same membership continues) or new admissions?"*
3. **D-SUSP:** *"Does suspending a membership pause the term, stop dues, or exclude the person from member counts?"*
4. **EQ-2′:** *"Is a membership entered from a prior register, or by invitation, recognised by that act?"*
5. **Formal (for the Principal Reviewer; DERIVED, not a business choice):**
   - I1 (holder constant);
   - I2 (termination closes the interval);
   - lapse is derived, not a state;
   - effectiveness ⟂ lifecycle;
   - per-organisation uniqueness of the membership number.

**From 1–5, the following are derivable:**
- the state set;
- I3 and I4;
- I5;
- `InForce` / `IsMember`;
- the exact form of Q-FM-MAP for the PO;
- the minimum published facts.

**Authority (EQ-5) and Q-FM-MAP stay separate tracks.** Neither changes D-ID.

## 22. What must remain open

- EQ-5 / EM-OPEN-014 (organs, authority);
- Q-FM-MAP and FM-5…10 (election side; Full Membership deferred);
- EQ-10 (type→term coupling: no invariant found);
- the published-fact schema (premature: DR §12 **withdrawn as a design**; its *consumer list* stands);
- tenant time zone (G2);
- backdating / retroactivity (no requirement);
- the disposition of `grants_voting_rights` (options prepared, §13).

## 23. Architecture implications (conditional; no design chosen)

- **If D-ID = episode (A):**
  - `MembershipId` per admission;
  - continuity via the `(person, organisation)` anchor (affiliation);
  - the number per episode **or** per anchor (a tenant parameter);
  - legacy UNIQUE(`organisation_user_id`) **contradicts** the business rule and must give way to I5.
- **If D-ID = enduring with a retained number (C):**
  - a relationship identity (possibly realised **by** the affiliation, if its lifecycle suffices; else a separate MembershipRelationship) + an episode identity;
  - the audit references the episode.
- **B is not recommended** (CX-4) unless the business declares that no artefact needs to name a period of membership. That would contradict EligibilityEvaluation and the audit expectations.
- **In every case:**
  - I2, derived lapse, per-organisation number uniqueness;
  - the number-generation regression (CX-9) and the six inconsistent re-entry paths (§7) are **conformance defects** whose correct fix **depends on D-ID**. They are **not** fixable before it.
- **The order stays:** business rule → formal invariant → aggregate consistency → concurrency → persistence.

## 24. Implementation readiness

**Not ready:** Membership identity, lifecycle, multiplicity, aggregate, published facts, migration. All depend on D-ID / D-TERM / D-SUSP / EQ-2′.

**Independent of the open decisions (authorisable separately, not by this report):**
- the defect stories `PBDIGIT-257`, `-202`, `-209`;
- the observation that `MemberApprovalCreatesCorrectMemberTest` fails. **Classification:** a regression of a documented requirement. The fix (generate a number at approval) is **model-neutral only in part**: *which* number and *whether it persists across rejoin* is D-ID. The number's *existence* is not in dispute.

**No new PBDIGIT ID is raised.** These findings map to EPIC-02 `PBDIGIT-06` (approval) and `-05` (import), and to this discovery.

---

**STOP.** Returned to the Principal Reviewer. Nothing implemented, migrated, changed or committed.

### Appendix A: exploration script (reproducible; research aid only)

`python3 -I explore.py` (session scratchpad, 2026-10-08). Its structure:
- **Legacy machine:** one `members` row per affiliation; approve → active; expire active → expired; renew extends the interval without changing status; suspend/end unreachable.
- **Candidate machine:** lifecycle {IN_FORCE, SUSPENDED, TERMINATED} × effective(bool), with guards (no ADMIT while non-terminal; no RENEW after TERMINATE), under models A (a new id per admission), B (one id, reactivated), C (a relationship id + episode ids).
- **Properties checked per sequence:**
  - UNIQUE violation;
  - expired ∧ effective;
  - reachable (life, effective) pairs;
  - old-reference ambiguity (the first admission's id reused by a later admission).
- **Results:** §11. **Limits:**
  - one (person, organisation);
  - abstract time;
  - length ≤ 7;
  - no recognition dimension;
  - no authority.

The exploration finds **counterexamples and reachability**; it cannot establish business truth.

**Traceability:** DR §§1–19 · R §1 (H-05, H-09), §5 · Q (EQ-1…16) · `MemberApprovalCreatesCorrectMemberTest.php:132-177` (run: 2 failed) · `membership_lifecycle_implementation.md:35-85` · `2026_03_07_145529_create_members_table.php` (`membership_number` unique; `organisation_user_id` unique + cascade) · `MemberImportService.php:35-90` · `ImportTutorial/en.json:45-56` · `OrganisationRoleController.php:74-112` · `OrganisationMemberInvitationController.php:206-220` · `MembershipApplicationController.php:68-81` · `ApproveMembershipApplication.php:27-83` · `OrganisationUserImportService.php:345-353` · `docs/publicdigit/membership/20260403_0003_membership_management.md:214-225, :80` · `architecture_legacy/membership/20260405_0918_membership_ablauf.md:975, :1025` · `CommitteeLevel.php` · `EM-ENT-001/007`, `EM-FM-003…006`, `EM-OPEN-014`, SM2.

**Verification note (same day):** a second script independently counted all rejoin sequences (≥ 2 successful admissions): **30,015**. Model B's ambiguous sequences are **30,015**, i.e. **all** of them (the §11 claim holds). The equal legacy count (30,015, expired ∧ effective) is a **structural coincidence, not a link**: both properties require one specific ordered three-event pattern (ADMIT…TERMINATE…ADMIT vs ADMIT…EXPIRE…RENEW) over the same six-event alphabet, so they count the same sequences up to relabelling.

---

**Correction (2026-10-08, Decision Engineering phase; additive, text above unchanged):**
1. **§1.3 / §11 / CX-4 overstated.** Model B is **not falsified**. The ambiguity property counted *undated* references, but PublicDigit artefacts are dated (`membership_fees.period_label` / `due_date`, renewal dates, election timestamps). With a dated membership history, B identifies the referenced period. B is weaker only for undated references. D-ID is open between A and B.
2. **I2 downgraded.** "Termination closes the interval" is one of two equivalent representations (an implementation choice). The DERIVED rule is **I2′**: not in force from the termination's effective time.
3. **Per-organisation number uniqueness** (§5, §21.5) is a **HYPOTHESIS (strongly supported)**, not DERIVED. Global uniqueness = implementation behaviour.

See `2026-10-08-e0a2-membership-decision-pack.md` §1, §11.
