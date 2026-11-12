# Membership kernel: Renew, Suspend, Restore (tactical design) — PROPOSED, revision 4

| | |
|---|---|
| **Status** | **PROPOSED, revision 4** (corrections in §16 and §17) (revision 1 superseded in place; reasons in §0). Not accepted; no implementation authorised. **Written and self-reviewed by Claude, not independent** |
| **Mandate** | **D-163** (scope); D-161 (rule), D-162 (C1/C2 design), CR-02 (contract) |
| **Commission** | "Membership Renew / Suspend / Restore Tactical Design Validation and Completion", accepted by the Principal |
| **Ratified sources** | `docs/publicdigit/reviews/2026-10-08-membership-architecture-ratified.md` §1 (glossary: `Standing` = dues), §2 (fact table), §3 (resolution incl. `active(f)` at :84, :162), §4 (predicates), §5 (INV-2, INV-3, INV-12, INV-COV, INV-6/14); `…-membership-architecture-final.md` §3 (fold: `termSegs`, `restrictions`; `Effective`, `Lapsed`, `InForce`), §7 (Restore "ends a suspension only"), §9 (suspension effects; renewal while suspended), §358 (renewal precondition "period not ended"), scenarios 6, 7, 8, 22 |
| **Code (evidence of current behaviour only)** | `Admit` (`?TimePoint $initialTermUntil`, "may be ∞ … without it the term is empty and the member is Lapsed (C-S0-2); no default term"); `TimePoint::infinity()`; `Member::decide*`; `Resolution::of`; Governance Part / GroupApplication (model document §20.1, [WD]) |

**Labels:** [RAT] · [AD] · [BF] · [AC] · [TD] · [TU] · [WD] · [OQ].

## 0. Findings of the validation (against revision 1)

| # | Revision 1 said | Finding | Revision 2 |
|---|---|---|---|
| V-1 | "the initial segment if `initialTermUntil` is given" | **Ambiguous**: it did not distinguish **open-ended** from **no term recorded** | **[BF]** the kernel already distinguishes them: `∞` = open-ended; absent = no term (C-S0-2: lapsed). **[TD]** release-1 admission **requires an explicit term** (finite or ∞); "absent" remains only for attested history (imports). §3 |
| V-2 | named D-161's condition **`Active`** | **Clash**: ratified §3 already uses **`active(f)`** for a fact's liveness (:84, :162) | **New name `InForceUnrestricted`.** It is **a derived name used by consumers only**; **CR-02's external fields (`inForce`, `suspended`) are unchanged.** §2 |
| V-3 | "period not ended at `effectiveAt`" | Correct, but **did not show** that a lapsed period passes it | **Lapsed ≠ ended** (INV-3, final §3 `Lapsed = Recognised ∧ ¬Ended ∧ ¬Effective`), so **a late renewal is accepted**; proof in §5 table rows 3–4 |
| V-4 | "batch not atomic across members" | **Asserted, not derived**: it ignored that a resolution **may itself declare its parts indivisible** | **Derived from Governance's model [WD]:** parts are independent **unless the resolution declares an indivisible group**, which is then applied all or none (GroupApplication). **No universal rule invented.** §7 |
| V-5 | authority "via AuthorityCheck" | Incomplete | Full outcome set and shapes in §8 |

## 1. Business explanation

| Concept | Plain meaning |
|---|---|
| **Membership period** | One episode, from admission to a formal **End**. It is never ended by itself (INV-3) |
| **Term** | The time granted within the period: a year, or open-ended. Set at admission **as the organisation's rules say** (D-163) |
| **In force** | Inside the period **and** inside a granted term |
| **Lapsed** | Inside the period, **outside** any term. **The person holds a membership period that has not ended (a *lapsed membership*), but is not a member (`IsMember` false)**; renewable |
| **Suspended** | Rights restricted for a time by decision. **Membership and its term continue** (S2, final §9) |
| **Dues standing** (ratified `Standing`) | Dues compliance from fee facts. **Not used in release 1** (payment outside PublicDigit) |

**For release 1** (D-161), **a person may post in forums and receive newsletters only while `InForceUnrestricted` = in force and not suspended.** Reading their forums needs only belonging (coverage).

## 2. Definitions

| Name | Definition | Source | Owner / where |
|---|---|---|---|
| `lifetime(q)` | as ratified | [RAT] §3 | Membership |
| `termSegs(q)` | union of the live Admit initial segment and live Renew segments (values after Correct) | [RAT] final §3 | Membership |
| `restrictions(q)` | for each live Suspend s: `[s.from, s.until)` (`s.until` = ∞ if none) when s has no live Restore; `[s.from, min(s.until, r.from))` when r is s's live Restore | [RAT] final §3, §7 | Membership |
| `InForce(q,t)` | `t ∈ lifetime(q) ∧ t ∈ termSegs(q)` | [RAT] §4 | Membership |
| `Lapsed(q,t)` | `t ∈ lifetime(q) ∧ t ∉ termSegs(q)` | [RAT] §4 | Membership |
| `Suspended(q,t)` | `t ∈ lifetime(q) ∧ t ∈ restrictions(q)` | [RAT] §4 | Membership |
| `IsMember(t)` | ∃q `InForce(q,t)` (suspension does not change it) | [RAT] §4 | Membership |
| **CR-02 `inForce`** | `⋃_q termSegs(q) ∩ lifetime(q)` | [AD] D-161 | Membership publishes |
| **CR-02 `suspended`** | `⋃_q restrictions(q) ∩ lifetime(q)` | [AD] D-161 | Membership publishes |
| **`InForceUnrestricted(t)`** | `t ∈ inForce ∧ t ∉ suspended` | [TD], D-161's "good standing" | **Derived by the consuming context** (Deliberation, Communication) from Membership's intervals that Organisation forwards unchanged (coherence review §2B); **not a Membership fact and not a contract field** |
| `Standing` (dues) | E0b-S | [RAT] §1 | **unused in release 1** |

## 3. Terms at admission (V-1)

| Value of `initialTermUntil` | Meaning | Release 1 |
|---|---|---|
| a date | finite term `[effectiveAt, date)` | **allowed** |
| **∞** (`TimePoint::infinity()`) | **open-ended term** | **allowed** (D-163) |
| absent | **no term recorded**: the period starts lapsed (C-S0-2) | **refused for release-1 admissions** (the admission must state the term the rules give); **kept only for attested history** (imports), shown as "no term recorded" |

**[TD] Rationale:**
- D-163 forbids a platform default. Refusing "absent" is not a default: **it forces the admitting body to state the term.**
- **No rule is invented.** The kernel keeps "absent", so history is faithful.

## 4. Commands and acceptance

| Command | Target | Payload | Accepted when (as resolved at acceptance) | Refusal outcomes |
|---|---|---|---|---|
| **Renew** | the period containing `effectiveAt` | `effectiveAt`, `until` (finite or ∞) | a period q has `start(q) ≤ effectiveAt` **and is not ended at `effectiveAt`** (`effectiveAt < end(q)`); `effectiveAt < until` | `NoPeriodAt`, `PeriodEnded`, `EmptySegment`, authority (§8) |
| **Suspend** | the period containing `effectiveAt` | `effectiveAt`, optional `until` | period exists and **is in force at `effectiveAt`** (ratified guard, final §18); `effectiveAt < until` if given | `NoPeriodAt`, `PeriodEnded`, **`NotInForce`**, `EmptyRestriction`, authority |
| **Restore** | a Suspend fact s | `effectiveAt` | s live; **the period is in force at `effectiveAt`** (ratified guard); **s not already restored** (no live Restore on s); `s.from < effectiveAt < s.until` (or `s.until` = ∞) | `NotSuspended`, **`NotInForce`**, `AlreadyRestored`, `OutsideRestriction`, authority |
| Annul / Void / Correct | any of the above | as ratified | as ratified (BD-REV etc.) | as ratified |

**Common to all:**
- one Membership aggregate per (person, organisation);
- append under the expected version (concurrency: re-decide);
- idempotency key;
- **the ledger clock** (D-162);
- **no consequences and no trims** (these facts cannot enlarge a lifetime, [RAT] §2).

## 5. Decision table: main and boundary cases

| # | Situation | Result | Why |
|---|---|---|---|
| 1 | Admit 1 Jan, term to 31 Dec; renew on 20 Dec from 1 Jan next year, 1 year | accepted; continuous `inForce` | segment adjoins |
| 2 | Admit open-ended (∞) | in force until ended; no renewal needed | V-1 |
| **3** | **Term ended 31 Dec, not renewed; renewal decided 15 Feb, effective 15 Feb** | **accepted; lapsed 1 Jan – 14 Feb kept** | **period not ended** (lapsed ≠ ended, INV-3); **INV-12** |
| 4 | Same, but the renewal is **expressly back-dated** to 1 Jan | accepted; no gap | scenario 22 (backdated) |
| 5 | Renewal after a formal **End** (effective before the renewal) | **refused `PeriodEnded`**; a **new Admit** is needed | final §358 |
| 6 | Renewal effective before the period's start | refused `NoPeriodAt` | segment outside the lifetime is meaningless |
| 7 | Overlapping renewals | accepted; union | segments are a set |
| 8 | Renewal while suspended | accepted; **the term runs** | final §9 default (statute may differ: [UNKNOWN] per organisation, recorded with the decision) |
| 9 | Suspension, open-ended | `Suspended` from `from`; `IsMember` unchanged | S2 |
| 10 | Fixed-length suspension 1 Mar – 1 Jun | ends 1 Jun without a Restore | `until` |
| 11 | Restore on 1 Apr of (10) | accepted; restriction 1 Mar – 1 Apr | final §7 |
| 12 | Restore on 1 Jun or later of (10) | **refused `OutsideRestriction`** (already ended) | nothing to end |
| 13 | Two overlapping suspensions; restore one | the other still applies | each Restore targets one Suspend |
| 14 | Restore of an already restored suspension | refused `AlreadyRestored`; **a Correct changes its date** | one live Restore per Suspend |
| 15 | Suspension of a **lapsed** period | **refused `NotInForce`** | **ratified guard: Suspend / Restore require *in force*** (final §18, REQ-MEM-SUSPENSION) |
| 16 | Suspend effective after the period's End | refused `PeriodEnded` | — |
| 17 | **Annul a Renew** | its segment disappears from `inForce` from the annulment's record time on | [RAT] liveness |
| 18 | **Annul a Suspend** | restriction disappears; its Restore becomes orphaned (target not live), no effect | [RAT] liveness |
| 19 | **Annul a Restore** | the suspension runs again from its start | [RAT] |
| 20 | Correct a Renew's `until` | the segment changes; history keeps both | [RAT] Correct |
| 21 | End annulled after a renewal was refused (`PeriodEnded`) | the refusal stands; **a new Renew can now be accepted** | refusals are not facts |
| 22 | Any of these, queried at τ before its record time | not visible | `knownAt(τ)` prefix (INV-14) |
| 23 | Coverage, belonging, units | **unchanged in every row** | [RAT] §92 |

## 6. Aggregate and invariants

- **Aggregate:** unchanged, the Membership per (person, organisation) (ratified D1). **Renew, Suspend and Restore act on its periods, so no new aggregate is needed.**
- **Invariants:**
  - INV-EP, INV-9, INV-COV, INV-1: untouched (no BaseClaim change);
  - **INV-2** (`Ended ⇒ ¬InForce`) by intersection with the lifetime;
  - **INV-3** (lapse never ends);
  - **INV-12** (gap kept) by the segment starting at its own `effectiveAt`;
  - **INV-6/14** append-only and stable `KnownAt`.
- **No Finance or payment type in the kernel** (D-163).

## 7. Several renewals from one committee resolution (V-4)

- **What is established [WD]:** a Governance **Resolution** consists of **Parts**. Parts may form an **indivisible group**, applied all or none (model document §20.1; across contexts: **GroupApplication**, Prepared → Completed or Inapplicable / Pre-empted).
- **Hence:**
  - **a resolution that renews many members states, per its own text, whether the renewals form one indivisible group;**
  - **default when the resolution does not group them:** each member's renewal is **its own part**, applied independently, outcome reported per member;
  - **if grouped:** applied **all or none.** Membership applies the group's facts across the members' aggregates in one transaction (version-checked); **any refusal → none applied**, and the group is reported Inapplicable with the reasons.
- **Every Renew carries the resolution and part reference** (`decisionRef`).
- **One AuthorityCheck answer** (push shape: the committee's competence on the decision date) **serves all its parts.**
- **No universal rule is invented: the organisation's own resolution decides.** It is recorded so (sitting minutes as evidence).

## 8. Authority and evidence

| Item | Content |
|---|---|
| Act kinds | `renew-membership`, `suspend-membership`, `restore-membership` (D-163) |
| Shapes | **committee resolution → push** (Governance applies parts to Membership, AuthorityCheck review §5); **office holder's act → pull** at the moment of the act |
| Evidence on each fact | the **six ratified fields** (actor, capacity, ruleRef, policyVersion, result, checkedAt) **+ basis**: `outcome` (Authorised · NotAuthorised · **Undetermined**), **`evaluatedFor` = the decision date (D-12), distinct from `checkedAt` (τ)**, `competenceKind` (workedOut · asserted · contradiction · interimRole), `decisionRef`, `evidence` fingerprints |
| `competenceVerified` (code) | `= competenceKind ∈ {workedOut, asserted, contradiction}` (correction note 2) |
| Structure for scope | the unit's ancestor chain **as at (d, τ)**, supplied by Organisation |
| Rules silent | `Undetermined`: refused; **nobody until a committee designates** (as D-149) |
| Missing answer | refused; **never permission** |
| `InterimRoleAuthority` | **never for a ledger organisation** |

## 9. Temporal behaviour

- **`effectiveAt`** (business), **`decidedAt`** (decision), **`recordedAt`** (knowledge; the ledger clock): ratified final §8.
- Queries "at d, as known at τ" over `Res(F_τ)`.
- **A back-dated renewal changes past truth only from its record time on** (row 4, row 22): what was known before stays explainable.

## 10. Cross-context effects

| Context | Effect |
|---|---|
| Membership → Organisation (C1/C2) | **C1 when `inForce` or `suspended` changes; coverage unchanged** (CR-02) |
| Organisation | **forwards** Membership's `inForce` / `suspended` per person at the operation's τ, unchanged and attributed; **belonging untouched** (consumers derive `InForceUnrestricted`) |
| Communication | **audience = belonging ∧ `InForceUnrestricted`** at the send's τ (D-161) |
| Deliberation | **read = belonging; write = belonging ∧ `InForceUnrestricted`** (D-152, D-161) |
| Governance | **no automatic office consequence** of lapse or suspension (D-24; final §9 "committee participation: Governance") |
| Geography | none |

## 11. Correction, annulment, void

**As ratified, unchanged:**
- Annul and Void remove a target's effect;
- Correct replaces values;
- each is append-only and authorised like the act it touches.

Rows 17–21 above show the effects.

## 12. Proof properties and acceptance tests (RED first)

| Property / test | Covers |
|---|---|
| Renew, Suspend, Restore never change `coverage` | INV-COV, row 23 |
| `inForce = ⋃ termSegs ∩ lifetime`; `IsMember` ≡ `inForce` non-empty at t | §2 |
| Late renewal keeps the gap; back-dated closes it | rows 3, 4; INV-12 |
| Renewal after End refused; accepted again after the End is annulled | rows 5, 21 |
| Open-ended and finite terms; **admission without a term refused** (release 1); attested import without a term allowed and lapsed | §3 |
| All suspension rows 9–16 | §5 |
| Annul / Correct rows 17–20 | §11 |
| `knownAt(τ)` before the record time hides the fact | row 22 |
| Grouped resolution: one refusal → none applied; ungrouped: per-member outcomes | §7 |
| Authority: NotAuthorised, Undetermined, missing answer → refused; evidence stored with `evaluatedFor` ≠ `checkedAt` | §8 |
| C1 emitted with changed `inForce` / `suspended`, unchanged `coverage` | §10 |
| Architecture: kernel pure; no payment types; **consumers (Deliberation, Communication) compute `InForceUnrestricted` from the intervals Organisation forwards; Organisation never computes it** (F-01) | §6, §10 |

## 13. Out of scope

- ChangeType (BD-TYPE);
- fee facts and dues `Standing`;
- automatic renewal (rule provenance on payment);
- expiry reminders;
- automatic office consequences;
- Election's use of `Suspended` (C4, already ratified).

## 14. Open questions

| Class | Item |
|---|---|
| Business ([OQ]) | **None.** D-163 settles scope; the ratified text settles semantics; batch atomicity is set by each resolution (§7) |
| Per organisation (UNKNOWN) | whether its statute changes the ratified default "renewal while suspended: allowed" (recorded with the decision; no platform rule) |
| Tactical / technical ([TU]) | column layout for `until` and the Restore target; the batch size; GroupApplication within one database transaction across many aggregates (lock order to avoid deadlock) |
| Proof | the ledger-clock tests (D-162) also cover these kinds |
| Legal | none new |

## 15. Assessment

~~READY FOR PRINCIPAL TACTICAL ACCEPTANCE.~~ *(Superseded: see §16 and §17.)*
- **In plain terms:** every case a party meets (on-time renewal, late renewal, back-dated renewal, open-ended membership, suspension with or without an end date, early restoration, mistakes corrected) has one defined outcome, taken from the ratified rules or D-163. **No new business rule was needed.**
- **In DDD terms:**
  - the facts stay inside the existing Membership aggregate;
  - the ratified invariants hold by construction;
  - the external contract (CR-02) is unchanged;
  - batch semantics come from Governance's existing resolution model.
- **Not independent:** the author validated their own design. Independent evidence is still needed for:
  - the decision table against the ratified formal proofs (FP);
  - the ledger-clock and group-transaction tests.

## 16. Revision 3 corrections (2026-11-11, after the Principal DDD Architect's review; self-review, not independent)

**C-1. `IsMember` and "lapsed": language only; the ratified predicate is unchanged.**

| Term (ubiquitous language) | Meaning | Formal |
|---|---|---|
| **Membership** (relationship) | the enduring relationship of one person with one organisation | ratified §1, MembershipId |
| **Membership period** | one episode, admission → End | PeriodId; `lifetime(q)` |
| **Open period** | a period not formally ended at t | `t ∈ lifetime(q)` (`InLifetime`) |
| **In force** | open period **and** inside a granted term | `InForce` |
| **Lapsed membership** | open period, **outside** any term | `Lapsed` |
| **Member** (`IsMember`) | **only** while some period is in force | `IsMember` [RAT] |
| **Suspended** | rights restricted; **the Suspend act is possible only while in force (C-2), but the restriction continues if the term later lapses** (ratified `Suspended` has no in-force condition; F-04) | `Suspended` |
| **Covered** (CR-01) | open period, any term state | `coverage` |

**Rule of wording:**
- **never call a lapsed person "a member"**. Say "holds a lapsed membership" (covered, not a member);
- "suspension does not change `IsMember`" stays true (S2).

**C-2. Suspension of a lapsed period: refused [RAT].**
- **Final §18 (REQ-MEM-SUSPENSION) gives Suspend / Restore the guard "in force".**
- Business sense: a lapsed person cannot exercise rights, so there is nothing to restrict. **If the organisation wants a restriction to bite on renewal, it renews first and suspends in the same decision.** One resolution with two parts, grouped if both must stand together (C-3).
- **Restore under the same guard [AC, literal reading]:** a Restore must take effect while the period is in force. If the term lapsed during a suspension, **the organisation renews (possibly back-dated) and then restores**. The restriction otherwise ends at its own `until`.
- **Added refusal:** `NotInForce` for Suspend and Restore.
- **Rows corrected:** 15 (refused). Row 8 (renewal while suspended) is unchanged.

**C-3. Grouped renewals: the mechanism and its proof.**

| Layer | Owner | Guarantee |
|---|---|---|
| Resolution semantics | Governance | the resolution states independent parts or an **indivisible group** [WD §20.1] |
| Group application | Governance (GroupApplication) | Prepared → **Completed** or **Inapplicable** (reasons) |
| Aggregate acceptance | Membership | each member's Renew decided by its own aggregate rules |
| **Persistence guarantee** | Membership adapter | **one database transaction that first takes the organisation's ledger-clock lock** (D-162), **then decides and appends every member's Renew**; any refusal → **rollback, nothing applied** |
| Interruption and recovery | both | a crash rolls the transaction back; **GroupApplication stays Prepared**; retry is idempotent on the group key |

**Why it holds [AC]:**
- **every Membership append of that organisation takes the same clock lock**, so **no other change to those members can interleave** while the group is applied;
- version conflicts inside the group cannot occur;
- one lock order means no deadlock.

**Limit [TU]:**
- the group holds the organisation's membership writes for its duration;
- **above a measured size bound, the group is refused as `Inapplicable(too large)`, never partially applied** (fail-closed). The organisation can split the resolution.
- **Proof tests:** a concurrent single append waits; a mid-group refusal leaves nothing; a crash leaves nothing and the retry applies once.

**C-4. Remaining cases re-checked against the ratified table (final §18):**
- Renew guard "period not ended": rows 3–6 stand;
- correction guard g2/NE and invalidation g2: rows 17–21 stand;
- KnownAt row 22 stands.

**Assessment, revision 3:** **ready for an independent check, then Principal acceptance.**

## 17. Revision 4 corrections (2026-11-11, after IR-R1-01; that review was by deepseek-chat, not independent)

| Finding | Correction |
|---|---|
| **H-1** | §4 Suspend and Restore now require **in force at `effectiveAt`**, with refusal **`NotInForce`** (final §18). **New row 15b:** *Restore requested after the term lapsed during the suspension* → **refused `NotInForce`**; the organisation renews (possibly back-dated) and then restores; otherwise the restriction ends at its own `until` |
| **H-2** | **One lock order for every Membership append, single or grouped: (1) obtain the AuthorityCheck answer (no locks); (2) take the organisation's ledger-clock lock; (3) per membership: version compare-and-set, decide, append, outbox.** This changes the C1/C2 specification's append order (it took the version lock first); correction note there, no change of meaning. **With one order, a group and a single append wait for each other but cannot deadlock.** A deadlock or serialisation error is still **rolled back and retried** (fail closed) |
| **M-1** | `InForceUnrestricted` is **derived by the consuming context**; Organisation forwards intervals (§2, §10 corrected) |
| **M-2** | **Group outcome:** - **group key** = (resolution id, group id); - **in the same Membership transaction**, Membership writes an **applied-group marker** (its own table: key, parts, outcome) **and** an outbox event `RenewalGroupApplied(key, outcome)`; - **a retry first checks the marker**: if present, it answers the recorded outcome **without appending again**; - Governance's GroupApplication moves to Completed / Inapplicable **from the event** (idempotent). **No cross-context table access** |
| **M-3** | **Source quoted:** model document §20.1 (`2026-11-08-strategic-context-map-and-governance-tactical-model.md` line 310): *"**Part**: … dependencies, **indivisible group**, competence evaluation"*; line 311: *"**GroupApplication**: application of an indivisible group across contexts: Prepared → Completed → Visible; or Inapplicable / Pre-empted"*. **Status [WD]** (Governance working model, D-10) |
| L-3 | Too large a group: the organisation must **re-decide** the resolution in smaller groups; it is never split at application |
| L-4 | One AuthorityCheck answer serves the group **only if its scope covers every part's member**; otherwise each part is checked |
| L-5 | `restrictions(q)` no-Restore case stated (§2) |
| L-6 | The basis carries **`structureRef`** (tree version, τ) when the act's scope uses a unit; **for renew / suspend / restore the scope is the organisation or the admitting body's units**, so `structureRef` is included when a unit scope is evaluated |
| L-1, L-2 | Title revision; §15 verdict marked superseded |

**Assessment, revision 4:** ready for an **independent** review (outstanding), then Principal acceptance.

## 18. Revision 5 corrections (2026-11-11, after IR-R1-02; deepseek-chat, not independent)

- **F-01:** §12 now says the consumers compute `InForceUnrestricted`.
- **F-04:** the glossary row now reads: the **act** of suspending is possible only while in force; **the restriction continues after a lapse**.
- **F-15:** §5 gains **row 15b**: *a term lapses during a suspension; Restore requested* → **refused `NotInForce`**; renew (possibly back-dated), then restore; otherwise the restriction ends at its own `until`.
- **F-08:** §7's "one answer serves all parts" holds **only if the answer's scope covers every part's member** (L-4).
- **F-02 and F-03 (D-168, D-167)**, as they apply here: **the posting and newsletter condition is not fixed.**
  - Each consumer reads **the organisation's participation rule as at the act's τ** (from Governance, see S2 §15). That is either the organisation's recorded provision or, if none, **platform default (D-161)**.
  - **The consumer records which applied** on the access decision or audience snapshot.

## 19. Revision 6 corrections (2026-11-11, after IR-R1-02 by GPT-6, independent of the package authorship)

- **M-8 (group idempotency):** **the applied-group marker has a database-enforced uniqueness constraint on the group key** (resolution id, group id). The marker insert happens inside the group transaction, **before any append**. **A concurrent duplicate fails on the constraint and then reads the recorded outcome.** **The same group can never append twice.** New test: two identical group requests at the same moment → one applies, the other returns the first's outcome.
- **M-7 (one τ across contexts):** **the contexts keep separate knowledge boundaries:**
  - Membership: ledger clock and `closedThrough`;
  - Organisation: §49.8 `closedThrough`;
  - Governance: DecisionLog.

  **They are not one clock. A cross-context operation picks one τ, and each source answers at that τ or refuses when τ is beyond its own `closedThrough` (D-73).** Consistency comes from **the same τ being passed to every source**, not from sharing a database. New proof: an operation whose τ is beyond one source's boundary is refused, never answered partially.

**Status (2026-11-11): ACCEPTED (D-177)** at the fingerprint recorded in the decision record. Build gated on its proof tests; no implementation authorised. Later notes follow the D-173 change-control rule.
