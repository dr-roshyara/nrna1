# ElectionMembership governance decision package

**Type:** Governance decision package · **Date:** 2026-08-12 · **Programme:** IERVP (Session 2)
**Report:** **`D-ENT-1` — APPROVED · `BR-1` — PARTIALLY RESOLVED · `ADR-002` — UNCHANGED · CONSTITUTION — UNCHANGED · IMPLEMENTATION — NOT AUTHORISED**
**Mode:** governance preparation only. **No entity, aggregate, repository, field, status, event, migration or test created. `ADR-002` and the Constitution untouched. `Q3` not decided.**
**Predecessor:** [`2026-08-12-election-membership-entitlement-lifecycle.md`](2026-08-12-election-membership-entitlement-lifecycle.md)

**Why `BR-1` is *partially* resolved:** the analysis converges on **Option B** with evidence from every layer, and I set that out below as a recommendation. **Adopting it is a business ratification I cannot perform.**

---

## 1 · Approved decisions

**A — APPROVED BUSINESS RULES** (Product Owner, 2026-08-12). Not reopened.

| # | Rule |
|---|---|
| **A-1** | `ElectionMembership` is an **election-specific entitlement**. |
| **A-2** | **Existence is distinct from exercisability.** |
| **A-3** | Organisation membership changes do **not** automatically destroy the entitlement. |
| **A-4** | The election has **its own governance** over whether the entitlement may currently be exercised. |
| **A-5** | Admission creates the entitlement; it **remains associated with the election unless a defined election-level rule terminates it**. |
| **A-6** | **One entitlement concept across both modes**; only the source of admission differs. |

## 2 · Decisions still open

`BR-1.1`/`BR-1.2` (analysed below, **recommendation only**) · `BR-1.3` naming · `BR-1.4` actors · `BR-1.5` reason · `BR-1.6` audit · `BR-1.7` duration/review · `BR-1.8` restoration symmetry · `BR-1.9` Chief acting on organisation change · `BR-1.10` post-vote governance · `BR-1.11` consumption meaning · `BR-1.12` is `invited` real · `BR-1.13` which suspension path is intended · **`Q3`** exercisability model.

---

## 3 · `ElectionMembership` lifecycle semantics

**The seven concerns, kept separate as instructed:**

| Concern | Question | Owning business concept |
|---|---|---|
| **Entitlement** | does this person hold an election-specific right? | **`ElectionMembership`** |
| **Exercisability** | may that right be exercised now? | **Election governance** (composed with lifecycle) |
| **Authorization** | may this actor change the entitlement? | **`ElectionConstitution`** (today: `ElectionPolicy`) |
| **Credential** | has the voter proved possession? | **Voting credential** |
| **Lifecycle** | does the election permit voting now? | **Election** |
| **Vote** | what anonymous ballot was cast? | **Vote** (`ADR-T11`) |
| **Governance** | what happens when integrity is challenged? | **Election governance** |

**Proposed lifecycle semantics** — meanings, deliberately **not** states, fields or values:

```
ADMISSION ─────────────► ENTITLEMENT EXISTS
                              │
                              ├── EXERCISABLE          may act now
                              ├── SUSPENDED            exists; may not act; reversible
                              ├── CONSUMED             exists; ballot cast; not repeatable
                              └── TERMINATED/REMOVED   exists historically; may not act
```

> **Every branch keeps the entitlement in existence.** Under `A-2` the entitlement is not the thing that changes — **its exercisability is.** *(`ARCHITECTURAL CONSEQUENCE` of `A-1`/`A-2`, offered for ratification.)*

---

## 4 · Suspension semantics

**The Product Owner's business intention, translated into DDD terms and offered as a PROPOSED BUSINESS RULE:**

> **An Election officer with governance authority may suspend an `ElectionMembership`. Suspension does not destroy the election entitlement; it renders the entitlement non-exercisable. The entitlement may subsequently be restored according to the authorised restoration rule.**

**The governed object is the `ElectionMembership`** — not the organisation `Member`, not the `User`, not the vote. **That is the substance of the translation.**

> ⚠️ **One word needs a decision before this is ratified.** The Product Owner's draft says *"**temporarily** non-exercisable"*. **"Temporarily" asserts a time bound, and no source specifies one** (`BR-1.7`). I have written *"non-exercisable"* above and flagged it, rather than silently keeping or dropping the word: **if suspension is genuinely temporary, something must define its end; if it is indefinite until reversed, "temporarily" is the wrong word.**

| Question | Finding | Class |
|---|---|---|
| Who may suspend? | `manageVoters` = active `ElectionOfficer`, `role IN ('chief','deputy')`, **election-scoped** | **E**; tests agree (**F**) |
| One actor or two? | ⚠️ **Both paths exist.** Single-actor `suspend` is the **documented** one; propose→confirm (proposer ≠ confirmer) is **undocumented** | **E/F/G**; intent **H** |
| Reason mandatory? | **No** on either election-side path. *(Organisation side: mandatory — empty reason throws)* | **G**; **H** · contrast **D** |
| Temporary or indefinite? | **Nothing specifies duration** | **H** |
| Review required? | **Nothing** | **H** |
| Audited? | Propose/confirm → global `voting_security` log. **Single-actor `suspend` → nothing** | **G** |
| Effect on voting | *"A suspended voter cannot vote, even if voting is still open"* | **E**; measured **G** |

---

## 5 · Restoration semantics

**PROPOSED BUSINESS RULE:** *restoration returns a suspended entitlement to exercisable, by an authorised act.*

| Question | Finding | Class |
|---|---|---|
| Does it exist? | **Yes, documented as re-approval** — *"Suspended voters show an Approve button. Simply click it to restore their eligibility."* | **E** |
| Named `restore`? | **No** — expressed as *approve*. `restore` is absent from the Constitution | **C: absent** |
| Who may restore? | Same authority as suspension: `manageVoters` | **E/G** |
| Actor count | **One** | **E/G** |
| Symmetry | ❌ **Two actors to suspend via the governed path; one to restore** | **G** |
| Residue | `approve` leaves `suspension_status='confirmed'` — restored **and** recorded suspended | **G** |
| Is asymmetry intended? | **Unspecified** | **H** (`BR-1.8`) |

> **The asymmetry is the governance question, not the residue.** If withdrawing the franchise deserves two actors, restoring it plausibly does too — **but the opposite is also defensible** (restoring is the safe direction). **Either is legitimate; neither is stated.**

---

## 6 · Termination / removal semantics — `BR-1.1` / `BR-1.2`

### 6.1 The question

> **When an authorised officer removes/terminates an `ElectionMembership`: (A) does the entitlement cease permanently, or (B) does it remain historically associated with the election but become non-exercisable, with possible administrative restoration?**

### 6.2 Analysis — business meaning, governance, audit, integrity

**Business meaning.** *(A)* treats the entitlement as **erasable**: the person was never, in the end, a participant. *(B)* treats it as a **historical fact with a terminated effect**: the person *was* admitted, and that admission was later withdrawn. **Under `A-1`/`A-5` the entitlement is a record of an act of admission.** An act that happened cannot stop having happened; only its effect can be withdrawn. **INTERPRETATION, and it favours (B).**

**Governance consequences.** *(A)* leaves no trace of who withdrew whose franchise, so a disputed removal cannot be adjudicated. *(B)* preserves the decision trail, which is what `ADR-003` presumes when it says consequences are **governance decisions** made on evidence (**B**).

**Audit requirements.** `remove()` **already captures** `removed_at`, `removed_reason`, `removed_by`, `removed_by_email` in metadata (**G**). ***(A)* would require discarding audit evidence the system already collects.** More strongly: `ParticipationEligibilityEvidence` and its `eligibilityHash` exist to let **replay detect when runtime eligibility diverged from the frozen snapshot** (**G**) — **replay divergence detection requires the entitlement record to persist.** *(A)* would break it.

**Election integrity.** Turnout and participation figures are computed against the roll (`voters_count` is maintained from `role='voter' AND status='active'`, **G**). **Under *(A)*, erasing an entitlement retroactively changes the denominator of a possibly-published result.** Under *(B)* the denominator is reconstructible. **The integrity argument is the strongest of the four.**

### 6.3 What the layers already do — all of them behave as (B)

| Layer | Behaviour | Class |
|---|---|---|
| Implementation | `destroy()` → `remove()` sets `status='removed'` **and writes actor + reason + timestamp**. **No delete** | **G** |
| Schema | `SoftDeletes` **is available and deliberately not used** by removal | **G** |
| Concurrency | `destroy()` takes `lockForUpdate()` in a transaction, commented as guarding a **live vote in flight** — designed so removal does not race a ballot | **G** |
| Documentation | *"permanent — a removed voter does not show an Approve button"* **and** *"contact your system administrator if a removal was made in error"* | **E** |
| Organisation side | `TERMINATED` retains the episode; the lineage is append-only | **D** |

> **So (B) is what every layer already does, and (A) is not implemented anywhere.** That is *evidence of intent*, **not** a business rule — and I am not promoting it to one.

### 6.4 Recommendation

> **RECOMMEND OPTION B**, in this wording, for the Product Owner to ratify or reject:
>
> **"Termination withdraws the exercisability of an `ElectionMembership` permanently by officer authority. The entitlement remains historically associated with the election, together with the actor, reason and time of termination. Reversal is not available to election officers and requires a separately authorised administrative act."**

**Why:** it is the only option consistent with `A-5`, with `ADR-003`'s governance-decides principle, with replay-divergence detection, and with denominator stability — and it matches the documented officer experience (*permanent to me; recoverable by an administrator*).

**Two things this wording deliberately does:** it makes permanence **an authority boundary** (permanent *to the officer*) rather than a metaphysical claim, and it makes **reason and actor mandatory**, which the election side does not currently require (`BR-1.5`).

> ⚠️ **Today "permanent" is not enforced.** `approve` refuses only when status is already `active`, so **nothing prevents it acting on a `removed` row** — permanence rests on a hidden button. **`I` — I read this and did not execute it.** **Under (B) this becomes a defect to fix; under (A) it becomes a contradiction.** Either way it needs the decision first.

---

## 7 · Consumption semantics

| Question | Finding | Class |
|---|---|---|
| What is written today | `has_voted`, `voted_at`, **and `status='inactive'`** | **G** |
| Documented? | **The officer guide says nothing about a voter who has voted** | **H** |
| Meaning under `A-2` | Consumption removes **exercisability**, not the entitlement — the only reading consistent with the approved rule | **A**, applied |
| Repeatable? | **No** — and not merely by policy: `ADR-T11` means a cast ballot cannot be identified, so it cannot be withdrawn and re-cast | **C** |
| Is it a *governance* act? | **No.** It is caused by the voter, not by an officer | **INTERPRETATION** |

> **Consumption is the one non-exercisable cause that no officer authorises.** That alone distinguishes it from suspension and termination, and it is why sharing one representation with them is a modelling problem rather than a cosmetic one.

**⛔ Not fixed here.** The `status='inactive'` collision between consumption and suspension is recorded as a `Q3` consequence and **deliberately left alone**, per the Product Owner's instruction that tactical work must not run ahead of governance.

---

## 8 · Post-vote boundary

**`ADR-T11` — constitutional, build-breaking: no voter↔vote linkage in any aggregate, event payload or projection.**

```
ElectionMembership governance          Election-level governance
  suspend / terminate / restore          challenge · recount · invalidate
  object: one entitlement                object: the election
          │                                        │
          └── CANNOT identify or mutate ───────────┘
              an already-cast anonymous vote
```

| Question | Answer | Class |
|---|---|---|
| What can happen to the `ElectionMembership` after a vote? | Its exercisability is already spent. Suspension of a voted voter is **refused** on the governed path; **removal has no such guard** | **G** |
| What can happen to the **election**? | `ADR-003`: challenge · recount · invalidate · archive with notation — **governance decides impact** | **B** |
| Can voter-level governance reach the ballot? | **No. Constitutionally impossible.** | **C** |
| Should removal also refuse a voted voter? | **Unspecified** — and worth deciding, since the two paths disagree today | **H** (`BR-1.10`) |

**No mechanism that violates `ADR-T11` is proposed, considered, or left implicit anywhere in this package.**

---

## 9 · Actor authority

**One authority check governs all six voter-governance operations:** `manageVoters` → active `ElectionOfficer` with `role IN ('chief','deputy')`, scoped to that election (**E**).

| Operation | Authority | Actors | Consequence |
|---|---|---|---|
| `propose-suspension` | `manageVoters` | 1 | none yet |
| `confirm-suspension` | `manageVoters` + proposer ≠ confirmer | **2** | not exercisable |
| `cancel-proposal` | `manageVoters` + must be proposer | 1 | none |
| `suspend` | `manageVoters` | 1 | not exercisable |
| `approve` (restore) | `manageVoters` | 1 | exercisable again |
| `destroy` (remove) | `manageVoters` | **1** | **"permanent"** |

> 🔴 **The gradient of authority does not follow the gradient of consequence.** The **most** consequential act — permanent removal — requires the **same** authority as suspension and **fewer actors** than the governed suspension path. **Whether that is intended is `BR-1.4`, and it is a governance question, not a defect I may declare.**

**Ownership test:** authorization belongs to **`ElectionConstitution`** as a business concept. **Today it lives in `ElectionPolicy`, an application class, and the Constitution names no voter-level action at all.** *(Assigned by business meaning, not by where the code sits.)*

---

## 10 · Audit requirements

**A structured, per-election audit facility already exists — `ElectionAuditService`, whose default category is literally `'voters'`, writing to a per-election audit folder.**

| Operation | Structured election audit | Global security log |
|---|---|---|
| `propose-suspension` | ❌ | ✅ |
| `confirm-suspension` | ❌ | ✅ |
| `suspend` | ❌ | ❌ |
| `approve` (restore) | ❌ | ❌ |
| `destroy` (remove) | ❌ | ⚠️ **only if** the election's legacy `status === 'active'` |
| `cancel-proposal` | ❌ | ❌ |

> 🔴 **No voter-governance act appears in the election's own audit trail — and the facility for it exists, unused.** Four of the six acts leave no security record at all, and the record for removal is **conditional on a legacy column** that `PBDIGIT-58` is migrating away from.
>
> **`ElectionMembership` also emits no domain events**, so nothing downstream can react to a franchise change.

**PROPOSED (for ratification):** every act that changes exercisability or terminates an entitlement records **actor · timestamp · reason · affected entitlement**, in the election's audit trail, unconditionally. *(`BR-1.6`. The organisation side already requires actor and reason — **D**.)*

---

## 11 · Full Membership vs Election-Only

**The distinction is preserved and unchanged.**

```
FULL MEMBERSHIP      Organisation Member ──► admission prerequisite ──► ElectionMembership
ELECTION-ONLY        imported/assigned person ─────────────────────────► ElectionMembership
```

**After the entitlement exists, its governance lifecycle is election-specific and mode-independent.** Evidence: **no** suspension, restoration or removal path branches on `voter_source_strategy` (**G**). **`ElectionMembership` is not merged with organisation `Member` anywhere in this package.**

| Mode | Status |
|---|---|
| **Election-Only** | lifecycle exercised for admission and suspension; **restoration and termination never exercised** |
| **Full Membership** | **MODEL CONSISTENT BUT RUNTIME NOT YET VERIFIED** — `members` has **0 rows**, so no admission has ever occurred; the declared admission rule is a **stub returning `true`** (**I**) |

> **Under `A-3`/`A-5`, admission is the single point at which organisation membership is enforced — and that point is currently a stub.** `ARCHITECTURAL CONSEQUENCE`.

## 12 · Vocabulary implications

| Term | Status | Recommendation |
|---|---|---|
| **`revoke`** | 🔴 **Taken.** Defined as withdrawing **identity trust**, and explicitly *"does NOT block future voting"* (**B/E**) | **Do not use for `ElectionMembership`.** Using it would invert an ADR-backed term |
| **`suspend`** | 🔴 **Collides** — constitutionally means *hold the whole election* | **Needs a distinguishing name at voter level** |
| **`remove`** | ⚠️ Documented for voters; also the model method name | Usable, but *"permanent"* needs the authority-boundary wording of §6.4 |
| **`terminate`** | Free on the election side; **means terminal/irreversible on the organisation side** (**D**) | Reusing it imports "irreversible", which conflicts with administrative recovery |
| **`approve`** | Currently means **both** first-time approval **and** restoration | Two acts under one verb |
| **`admit`** | **Absent everywhere** | The act `A-5` depends on has no name |
| **`restore`** | Absent | — |
| **`member`** | 🔴 **Three referents**: the `Member` aggregate (0 rows) · `user_organisation_roles.role='member'` · `ElectionMembership` | — |

**Recommendation: all voter-level naming goes to the Phase 4 governance-language review that `VoterSourceStrategy` already designates** (its case names are `@deprecated`, *"transitional bridge vocabulary"*). **One naming decision, not two** (**ES-005.4**). **No name is chosen in this package.**

## 13 · Constitution implications

* **Voter-level governance has no constitutional existence** — `ElectionMembership` **0**; `admit`/`restore`/`revoke` **0**; `suspend` election-level only.
* **`ADR-T11` already bounds any future rule:** none may promise to reach a cast ballot.
* **The capability-freeze principle is available for extension** to the voter level.
* **The documented officer lifecycle is a candidate for elevation** — it is closer to a specification than anything in the Constitution. **Elevating it is a governance act; I propose no text.**
* **Authorization ownership belongs constitutionally** but sits in an application policy (§9).
* **The Constitution remains UNCHANGED.**

## 14 · `ADR-002` implications

* **Unchanged, and the amendment remains a proposal.** Substance unaffected by this package.
* **One addition warranted:** the amendment should note that *Revocation* in the accepted vocabulary means **identity trust withdrawal**, so entitlement termination must not be described as revocation without disambiguation.
* **Sequencing confirmed:** `BR-1.1`/`BR-1.2` should be ratified **before** the amendment is applied, since the amendment's Entitled axis describes what termination acts upon.

## 15 · `Q3` implications

**Constraints on whoever decides `Q3`. No candidate selected; no overlay designed; no storage chosen.**

1. **Exercisability must distinguish four causes with different reversibility and different authors:** suspended (officer, reversible) · consumed (**voter**, irreversible) · terminated (officer, per §6.4) · lifecycle/credential (system). **Today three collapse into `status='inactive'`.**
2. **Consumption has no officer author** — a model that treats all three as officer decisions is wrong (§7).
3. **`Q3` cannot be completed before `BR-1.1`/`BR-1.2`.**
4. **Whatever is chosen must carry actor, reason and time** (§10) and **must not attempt post-vote reach** (§8).
5. **The organisation side already solved the analogous problem** — guarded transitions, mandatory actor and reason, terminal state, domain events. `Q3` should not do worse.

## 16 · Implementation consequences — descriptive only

**Descriptive. No authorisation, no design, no proposal.**

| # | If the recommendations were ratified, these would follow |
|---|---|
| 1 | **`status`'s three meanings would need separating** — but only after `Q3`, and **never before a replacement enforces**: `status='inactive'` is currently the only thing preventing a suspended voter from voting |
| 2 | **Permanence of removal would need enforcing** — `approve` has no guard against `removed` |
| 3 | **A mandatory-reason requirement** would change all election-side governance paths |
| 4 | **Unconditional structured auditing** would route six operations through the existing `ElectionAuditService` |
| 5 | **One suspension path would be retired** once `BR-1.13` is settled |
| 6 | **Restoration would gain a name distinct from `approve`** |
| 7 | **The admission stub would become load-bearing** for Full Membership |
| 8 | **Test coverage for the suspension→voting effect would be required** — no such test exists today |
| 9 | **`invited` would be implemented or removed**, per `BR-1.12` |

## 17 · Product Owner decisions required

| # | Decision | Why it is first / blocking |
|---|---|---|
| **1** | **`BR-1.1`/`BR-1.2` — ratify or reject Option B (§6.4)** | **Everything else depends on it**, including `Q3` and the `ADR-002` amendment |
| **2** | **`BR-1.5` + `BR-1.6` — mandatory reason and unconditional audit** | The facility exists unused; the organisation side already requires both |
| **3** | **`BR-1.4` — actor counts, given authority does not track consequence (§9)** | Removal is the most consequential act with the fewest actors |
| **4** | **`BR-1.13` — which suspension path is intended** | Until settled, *"four-eyes protects the franchise"* is not true |
| **5** | **`BR-1.7` — is suspension temporary or indefinite** | The word *"temporarily"* in the draft rule cannot be ratified without it |
| **6** | **`BR-1.8` — restoration symmetry** | — |
| **7** | **`BR-1.11` — consumption's meaning** | Reading B is the only one consistent with `A-2`; ratification still needed |
| **8** | **`BR-1.10` — may a voted entitlement be terminated, and to what end** | The two paths currently disagree |
| **9** | **`BR-1.12` — is `invited` a real business state** | Cheap; decides whether an approval gate should exist |
| **10** | **`BR-1.3` — naming** → route to the Phase 4 review | `revoke` is taken; do not decide in isolation |
| **11** | **`BR-1.9` — Chief acting on an organisation-membership change** | Lowest urgency: no signal exists to act on |

---

## Boundaries and self-audit

* **Nothing was created or changed:** no entity, aggregate, repository, field, status, event, migration, schema change, test, code change; `ADR-002` and the Constitution untouched; `Q3` undecided.
* **No runtime experiment.** Removal, restoration, and `approve`-on-a-removed-row were **read, not executed** — the last is marked `I`.
* **Every proposed rule was assigned an owning business concept** (§3, §9), by business meaning rather than by which class currently holds the code.
* **`ADR-T11` preserved** — no proposed mechanism identifies or mutates a cast ballot.
* **`PBDIGIT-69`** is cited nowhere in this package.
* **Session 1's artifacts** — `SD-1`, `SD-2`, `SD-4`, Master Matrix, the 1,376-test baseline — **neither read as authority nor modified. No handoff artifact exists, so `D-ENT-1` is NOT imported into `C4`/`C6`.**
* **Known limit:** the officer guide's governance weight is still unestablished (**E**). **If the Product Owner elevates it, several `BR-1.x` items are already answered; if not, they are open.** That single ruling would close more of `BR-1` than any other.

**Traceability:** `app/Http/Controllers/ElectionVoterController.php:171-190` (`destroy` → `remove`, row lock), `:196-216` (`approve`, no `removed` guard), `:222-241`, `:290-356` · `app/Models/ElectionMembership.php:32` (`SoftDeletes`), `:164-171`, `:173-197`, `:202-224` · `app/Policies/ElectionPolicy.php:91-98` (`manageVoters`) · `app/Services/ElectionAuditService.php:28-46` (categories, default `voters`) · `app/Application/Election/Security/TrustPolicyEvaluator.php:74-128` (replay divergence evidence) · `app/Domain/Election/Constitution/ElectionConstitution.php:126-146` · `docs/election_management/04-voter-list.md:60-140` · `docs/architecture/trust-domain/UBIQUITOUS_LANGUAGE.md:222-270` · `docs/adr/ADR-T-LOG-Tactical-Implementation.md:18` (`ADR-T11`) · `docs/adr/ADR-003-governance-driven-revocation.md:62-71` · `app/Contexts/Membership/Domain/Membership/MembershipLineage.php:364-395` · measured read-only 2026-08-12: removal retains the row and writes actor/reason/time · no voter-governance act uses `ElectionAuditService` · `members` 0 rows.
