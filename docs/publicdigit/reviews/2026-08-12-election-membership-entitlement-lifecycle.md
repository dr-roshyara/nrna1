# `BR-1` — The ElectionMembership entitlement lifecycle

**Type:** Business-rule investigation (`BR-1`) · **Date:** 2026-08-12 · **Programme:** IERVP (Session 2)
**Report:** **`D-ENT-1` — APPROVED · `BR-1` — INVESTIGATED · `Q3` — UNDECIDED · `ADR-002` — UNCHANGED · CONSTITUTION — UNCHANGED · IMPLEMENTATION — NOT AUTHORISED**
**Mode:** investigation only. **No production code, entity, aggregate, repository, field, status value, event or persistence structure introduced. No test created. `ADR-002` and the Constitution untouched. `Q3` not decided; candidate D not selected; no overlay designed; no storage chosen.**

**Evidence classes used throughout:** **A** approved business rule · **B** existing ADR · **C** constitutional rule · **D** domain invariant · **E** application rule · **F** test expectation · **G** implementation behaviour · **H** business rule not specified · **I** mechanism not established.
**No conclusion is promoted from `G` or `F` to a business rule.**

---

## 1 · Executive answer

> **`BR-1` has no ratified business rule — but it is not a blank slate, and the answer is already tightly bounded by three authoritative constraints.**

1. **An officer-facing lifecycle is already documented** (`docs/election_management/04-voter-list.md`, **class E**): four states, **suspension reversible by re-approval**, **removal "permanent"** at officer level but reversible by an administrator. **This is documented intent, never ratified as a business rule.**
2. 🔴 **The word `revoke` is already taken, and taken with an inverted meaning.** The governing vocabulary defines *Revocation* as *"an officer's decision to withdraw previously attested trust in an **identity**"* and states explicitly that it **does NOT** *"block future voting (eligibility might persist)"* (**B/E**). **Entitlement revocation would exist precisely to block voting.** Reusing the verb would invert an accepted term — a **fourth** vocabulary collision, and the worst of them.
3. 🔴 **`ADR-T11` makes one candidate answer constitutionally impossible.** *"No voter↔vote linkage in any aggregate, event payload, or projection; hashes only"* — **constitutional, build-breaking** (**C**). **Therefore terminating an entitlement after the voter has voted CANNOT reach that ballot: the system cannot identify which vote was theirs.** Post-vote termination can only ever have **election-level governance consequences**, exactly as `ADR-003` prescribes. **This is a hard boundary, not a gap.**

**And the organisation side already supplies a complete template** (**D**): `SUSPENDED` (reversible, guarded) vs `TERMINATED` (**terminal, irreversible**), **mandatory actor**, **mandatory reason** (an empty reason throws), guarded transitions, and domain events.

> **So `BR-1` is less a question of invention than of ratification and naming.** What is genuinely undecided is narrow: **is election-entitlement termination terminal or administratively reversible, who may cause it, and what must it be called given `revoke` is taken.**

---

## 2 · Approved Model B (not reinterpreted)

**A — APPROVED BUSINESS RULE**, Product Owner 2026-08-12:

> **`ElectionMembership` is the election-specific entitlement record. Admission creates an election-specific entitlement. That entitlement remains associated with the election unless a defined election-level revocation/removal rule terminates it. Whether that entitlement is currently *exercisable* is governed by the election's voting rules and voter-level suspension/governance state.**

**Model B is APPROVED. Model B is NOT implemented. Model B is NOT proven by runtime evidence.**

---

## 3 · Entitlement lifecycle — investigated, not assumed

The named concepts were investigated **as business concepts, not as states.** Findings:

| Concept | Is it a distinct business concept? | Basis |
|---|---|---|
| **Admitted** | ✅ yes — but **unnamed as an act**; the record's creation is its only marker | **E/G**; **H** as a governance act |
| **Exercisable** | ✅ yes — approved as distinct from existence | **A** |
| **Suspended** | ✅ yes — documented, tested, implemented, **and reversible** | **E/F/G** |
| **Restored** | ✅ yes — **documented as "re-approval"**, not as "restore" | **E** |
| **Revoked** | ⚠️ **the word exists for a different object** (identity trust) | **B/E** — for entitlement: **H** |
| **Removed** | ✅ yes — documented as **permanent at officer level** | **E** |
| **Terminated** | ❌ **not an election-side concept.** It is the organisation-side terminal state | **D** |
| **Consumed** (voted) | ⚠️ exists as data; **its meaning for the entitlement is unspecified** | **G**; **H** |

**Documented state model** (**E** — officer guide, `04-voter-list.md`):

```
invited   ○         "appears in the table with invited status" (on assignment)
active    ● green   the only state that may submit a ballot
inactive  🟡        "Suspended by an officer"   -> re-approval returns it to active
removed   🔴        "Removed from this election" -> "permanent"; admin-only recovery
```

> **E, quoted:** *"**Only voters with Active status can submit a ballot.** All others are blocked at the voting step."*

**This is deny-by-default, stated as intent** — and it matches the live predicate measured in the G-1 commission. **The live enforcement is therefore documented, not accidental.** *(That is a correction to my earlier characterisation — see §19.)*

---

## 4 · Admission

| Question | Finding | Class |
|---|---|---|
| What creates the entitlement? | Voter import, or assignment by User ID | **G**; documented **E** |
| Is admission a named governance act? | **No.** `admit` appears **0** times in the Constitution; no rule takes a person as its object | **H** |
| What does the documentation require? | *"**Only organisation members can be assigned as voters.**"* — error: *"not a member of this organisation"* | **E** |
| Is that the business `Member`? | **No** — measured: it is the `user_organisation_roles` linkage the FK demands; the `members` aggregate is **empty** | **G** |
| Initial state on assignment | Documentation says **`invited`**; **no code produces `invited`** | ⚠️ **E vs G divergence** |
| Approval step | *"Approving changes a voter's status from `invited` or `inactive` to `active`"* | **E** |

> ⚠️ **`invited` is a documented business state with no implementation.** Officers are told assignment yields `invited` and that they must then approve. **Measured: assignment writes `active` directly**, and `invited` has no producer. **So the documented approval gate does not exist in the code path.** *(This refines, and partly corrects, my earlier "`invited` has no producer" finding — it is not a vestigial enum value, it is an **unimplemented documented state**.)*

---

## 5 · Suspension

| Question | Finding | Class |
|---|---|---|
| Does the entitlement survive suspension? | **Yes** — the record persists; re-approval restores it | **E/G** |
| Effect on voting | *"A suspended voter cannot vote, even if voting is still open"* | **E**; matches measured behaviour **G** |
| Who may suspend? | **Chief and Deputy yes; Commissioner NO** (403) | **F** (tests) · **E** (guide is "Chief & Deputy only") |
| Four-eyes required? | ⚠️ **Both a two-actor flow and a single-actor button exist** — see below | **F/G** |
| May a voter who has voted be suspended? | **Refused** on the two-actor path (*"Cannot suspend a voter who has already voted"*) | **F/G**; as a rule **H** |
| Reason mandatory? | **No** — no reason is captured on either election-side path | **G**; **H** |
| Temporary or indefinite? | **Nothing anywhere** specifies duration or review | **H** |
| Audit? | Two-actor path logs to `voting_security`; **single-actor path logs nothing** | **G** |

### 🔴 The four-eyes control is real, and undocumented

| Path | Actors | Documented in the officer guide? | Audited? |
|---|---|---|---|
| `propose-suspension` → `confirm-suspension` | **2**, proposer ≠ confirmer enforced | ❌ **not mentioned at all** | ✅ |
| `suspend` (button) | **1** | ✅ **this is the documented mechanism** | ❌ |

> **The officer guide documents only the single-actor button. The two-person control exists in code and tests but is invisible to the officers who would use it.** So the "bypass" I reported earlier is more precisely **the documented path**, and the four-eyes flow is the undocumented one. **Which of the two is intended is `H` — and it is a Product Owner question, not an engineering one.**

### 🔴 The suspension→voting effect is untested at voter level

**Measured across the whole test estate:** every *"suspended cannot vote"* test concerns either **the election** being suspended (overlay / Invariant H) or **the organisation member** being suspended. **Not one test asserts that a suspended *voter* cannot vote.** The voter-suspension tests assert workflow outcomes and stored field values only. **F.**

> **Consequence: the test estate would not notice if voter suspension stopped blocking voting.** This confirms the `G-5` load-bearing warning from the test side, independently.

---

## 6 · Restoration

| Question | Finding | Class |
|---|---|---|
| Does restoration exist? | **Yes, as "re-approval"** — *"Suspended voters show an **Approve** button. Simply click it to restore their eligibility."* | **E** |
| Is it named `restore`? | **No.** `restore` appears **0** times in the Constitution; the concept is expressed as *approve* | **C: absent** |
| Who may restore? | Chief/Deputy (voter list is "Chief & Deputy only") | **E** |
| Actor count | **One** | **E/G** |
| Symmetry with suspension | ❌ **Two actors to suspend via the governed path; one to restore** | **G** |
| Does restoration clear the suspension record? | **No** — measured: `approve` sets `status='active'` and leaves `suspension_status='confirmed'` | **G** |
| Is asymmetry intended? | **Unspecified** | **H** |

> **Correction to my earlier framing:** I described `approve` as *"an ungoverned de facto restore"*. **More accurately: it is the DOCUMENTED restore mechanism** (**E**). What remains a genuine finding is the **asymmetry** (2→1) and the **residue** (`suspension_status` left `confirmed`, so a restored voter votes while recorded as suspended).

---

## 7 · Revocation / removal — the core of `BR-1`

### 7.1 `revoke` is already defined, for a different object

**B/E — the governing vocabulary:**

> **Revocation:** *"An officer's decision to withdraw previously attested trust in an **identity**."*
> **Critical: Revocation Does NOT Automatically:** *invalidate past votes · **block future voting (eligibility might persist)** · void election results · trigger audit reopening.*

**So in the accepted vocabulary, revocation deliberately does NOT stop someone voting.** `ADR-001` and `ADR-003` both use it in this sense (**B**).

> 🔴 **Using `revoke` for entitlement termination would invert an accepted, ADR-backed term.** Recommended for the naming decision: **do not reuse `revoke`.** *(A recommendation about naming risk. The naming decision itself is governance — and belongs in the Phase 4 review.)*

### 7.2 `remove` IS documented, and is the closest thing to a specification

**E — officer guide, quoted:**

> *"Removing a voter sets their status to `removed`. **This is permanent** — a removed voter does not show an Approve button."*
> *"**Removing is different from suspending.** Suspended voters can be re-approved. Removed voters **cannot be restored through the voter list UI** — contact your system administrator if a removal was made in error."*

**This answers much of `BR-1` at documentation level:**

| `BR-1` question | Documented answer | Class |
|---|---|---|
| Business meaning | Removal from **this election** | **E** |
| Suspend vs remove | **Explicitly distinguished**: suspension reversible, removal not | **E** |
| Permanent or reversible? | **"Permanent"** at officer level; **administratively reversible** *("if a removal was made in error")* | **E** |
| Who may remove? | Chief/Deputy; a test asserts *committee member can remove voter* | **E/F** |
| Four-eyes? | **No** — single actor | **G**; as a rule **H** |
| Reason mandatory? | **Not required by the UI**; the model accepts an optional reason | **G** |
| Auditable? | `remove()` logs `voting_security` **critical** — but **only if `election->status === 'active'`** | **G** |

> ⚠️ **The permanence claim rests on a UI fact** — *"does not show an Approve button"*. **A franchise-termination rule grounded in the absence of a button is not a business rule.** Measured: `approve` has **no guard against `status='removed'`** — it refuses only when status is already `active`. **So removal's permanence is a UI convention, not an enforced invariant.** **I — MECHANISM NOT ESTABLISHED.**

### 7.3 Post-vote termination — bounded by the Constitution

| Question | Answer | Class |
|---|---|---|
| Can an entitlement be terminated after the voter voted? | The two-actor **suspension** path refuses; **removal has no such guard** | **G** |
| If terminated, what happens to the cast vote? | 🔴 **Nothing can happen to it.** `ADR-T11` forbids any voter↔vote linkage — **the system cannot identify which vote was theirs** | **C** |
| So what recourse exists? | **Election-level governance only** — `ADR-003`: challenge, recount, invalidate, or archive with notation. *"Governance decides impact."* | **B** |
| Does termination end the entitlement or only future exercise? | **Unspecified for the election entitlement** | **H** |

> **This is the most important structural answer in the investigation: post-vote termination is constitutionally incapable of reaching the ballot.** Any business rule that assumes otherwise is unimplementable, not merely unimplemented. **It also explains why the suspension path refuses a voted voter: there is nothing left to withhold and nothing retrievable.**

### 7.4 Is "suspension = exists but not exercisable / revocation = no longer exists" supported?

**Partially, and I will not invent the rest.**

* **Supported by analogy on the organisation side (D):** `SUSPENDED` reversible vs `TERMINATED` *"final, irreversible… no further transitions"*, with mandatory actor and mandatory reason.
* **Supported at documentation level for the election side (E):** suspension reversible, removal permanent.
* **NOT supported as a statement about entitlement *existence*.** No source says a removed entitlement **ceases to exist**; the record persists with `status='removed'`. **H.**

> **So the distinction is real in effect and unstated in principle.** Recorded as **H**, not adopted.

---

## 8 · Consumption

| Question | Finding | Class |
|---|---|---|
| What is written after a vote? | `has_voted=true`, `voted_at`, **and `status='inactive'`** | **G** |
| Does documentation define it? | ❌ **The officer guide says nothing about a voter who has voted** in the voter-list lifecycle | **H** |
| Is consumption A (terminated), B (remains, non-exercisable), or C (other)? | **Unspecified** | **H** |
| What does `ADR-T11` imply? | The vote **cannot be traced back**, so consumption is **irreversible in effect** whatever it is called | **C** |
| Model B's bearing | The approved rule already separates existence from exercisability, so **B is the only reading consistent with it** — consumption removes exercisability, not the entitlement | **A**, applied |

> ⚠️ **The implementation writes consumption into the same field as suspension** (`status='inactive'`). **Under the approved rule these are different facts.** Recorded as a consequence for `Q3`; **no field, value or structure is proposed here.**

---

## 9 · Organisation membership changes

**Model B is APPROVED and NOT reopened**: later loss or suspension of organisation membership has **no automatic effect** on the election entitlement (**A**).

**The only question investigated, per the commission:**

> **May the Election Chief separately revoke/remove the election entitlement *because* organisation membership changed?**

| Finding | Class |
|---|---|
| No source authorises, requires, or forbids it | **H — BUSINESS RULE NOT SPECIFIED** |
| No mechanism notifies the election when organisation membership changes | **I — MECHANISM NOT ESTABLISHED** |
| A dormant query (`scopeEligible()`) would enforce it automatically — **0 callers**, and returns **0/20** because `members` is empty | **G** |
| A test asserts *ineligible if member status is suspended* — at the **assignment** gate | **F** |

> **So the capability is neither granted nor denied, and no signal exists to act on.** **Recorded as an open business rule (`BR-1.9`).**

---

## 10 · Election-Only mode

**One entitlement concept, as approved.** The **source of admission** differs; the **meaning** does not (**A**).

| Finding | Class |
|---|---|
| Admission without any business `Member`; measured 5/5 voters have no `members` row | **G** |
| The documented rule *"only organisation members can be assigned as voters"* refers to the **org-role linkage**, satisfied by import | **E/G** |
| Suspension, re-approval and removal are **mode-independent** — no path branches on `voter_source_strategy` | **G** |
| Lifecycle applies equally | **A**, consistent with **G** |

---

## 11 · Full Membership mode

**MODEL CONSISTENT BUT RUNTIME NOT YET VERIFIED.**

| Finding | Class |
|---|---|
| `members` has **0 rows platform-wide** → no admission and no retention scenario has ever occurred | **G** |
| Declared admission rule (active `Member` + fees paid/exempt) sits in a **stub returning `true`** | **I** |
| Under the approved rule, admission is the **single point** of organisation-membership enforcement — and it is the stub | **A**, applied |
| The lifecycle after admission is **identical** to Election-Only | **A** |

> **`BR-1` is answerable for both modes because the lifecycle is mode-independent. But the admission *prerequisite* half of Model B remains unexercised.**

---

## 12 · Authority / evidence matrix

| Question | A | B | C | D | E | F | G | H | I |
|---|:-:|:-:|:-:|:-:|:-:|:-:|:-:|:-:|:-:|
| Entitlement is election-specific and durable-until-terminated | ✅ | | | | | | | | |
| Existence ≠ exercisability | ✅ | | | | | | | | |
| Only `active` may submit a ballot | | | | | ✅ | | ✅ | | |
| Suspension is reversible | | | | ✅ | ✅ | ✅ | ✅ | | |
| Removal is "permanent" | | | | | ✅ | | | | ✅ |
| Removal permanence is **enforced** | | | | | | | | | ✅ |
| Chief/Deputy may suspend; Commissioner may not | | | | | ✅ | ✅ | ✅ | | |
| Four-eyes required to suspend | | | | | | ⚠️ | ⚠️ | ✅ | |
| Reason mandatory (election side) | | | | ✗D | | | | ✅ | |
| `revoke` means withdrawing identity trust | | ✅ | | | ✅ | | | | |
| Termination cannot reach a cast vote | | ✅ | ✅ | | | | | | |
| Consumption's meaning for the entitlement | | | | | | | ✅ | ✅ | |
| Suspension duration / review | | | | | | | | ✅ | |
| Chief may act on an organisation-membership change | | | | | | | | ✅ | ✅ |
| Voter-level governance has constitutional existence | | | ✗ | | | | | ✅ | |

*(`✗D` = the organisation-side domain **does** require a reason; the election side does not.)*

---

## 13 · Business rules explicitly specified

**Ratified (A):** entitlement is election-specific · created by admission · persists unless terminated · existence ≠ exercisability · organisation change has no automatic effect · one entitlement concept across both modes.

**Constitutional (C):** `ADR-T11` — no voter↔vote linkage, **build-breaking**; therefore no post-hoc reach to a cast ballot. Election-level suspension *"freezes capabilities only… does NOT mutate business facts."*

**ADR-backed (B):** revocation = withdrawal of **identity trust**, and does not itself block voting; consequences of revocation are **governance** decisions.

**Documented application rules (E)** — intent, not ratified: four states · only `active` may vote · suspension reversible by re-approval · removal permanent at officer level, administratively recoverable · voter list is Chief & Deputy only · assignment requires an organisation-role linkage.

**Domain invariants on the organisation side (D)** — a template, not binding on the election: SUSPENDED reversible · TERMINATED terminal and irreversible · **actor and reason mandatory** · guarded transitions · domain events.

## 14 · Business rules NOT specified

| # | Gap |
|---|---|
| **BR-1.1** | Does terminating an entitlement **end its existence**, or only future exercise? |
| **BR-1.2** | Is termination **terminal**, or administratively reversible (documentation implies the latter)? |
| **BR-1.3** | **What is termination called**, given `revoke` is taken and `remove` is UI-grounded? |
| **BR-1.4** | Who may terminate — and **how many actors**? |
| **BR-1.5** | Is a **reason mandatory**? (Organisation side: yes. Election side: no.) |
| **BR-1.6** | Must termination be **audited unconditionally**? (Today only when the election's legacy `status` is `'active'`.) |
| **BR-1.7** | Is suspension **temporary or indefinite**; any review obligation? |
| **BR-1.8** | Must **restoration** require the same authority/actor count as suspension? |
| **BR-1.9** | May the Chief terminate **because** organisation membership changed? |
| **BR-1.10** | May a voter who **has already voted** be suspended or removed, and to what end? |
| **BR-1.11** | What does **consumption** mean for the entitlement — A, B or C? |
| **BR-1.12** | Is **`invited`** a real business state? (Documented; unimplemented.) |
| **BR-1.13** | Is the **two-actor** or the **single-actor** suspension path the intended one? |

## 15 · Decisions required from the Product Owner

**Only those genuinely needed to complete the entitlement lifecycle:**

1. **`BR-1.1` + `BR-1.2` — termination semantics.** *Does termination end the entitlement, and is it reversible?* **Everything else in `BR-1` hangs off this.**
2. **`BR-1.4` + `BR-1.5` + `BR-1.6` — authority, reason, audit for termination.** The organisation side already answers these one way; the election side must choose deliberately, not inherit by accident.
3. **`BR-1.13` — which suspension path is intended.** Two exist; one is documented, the other audited and four-eyed. **Until this is settled, "four-eyes protects the franchise" is not true.**
4. **`BR-1.8` — restoration symmetry.**
5. **`BR-1.11` — consumption's meaning**, given the approved rule makes reading B the consistent one.
6. **`BR-1.7` — suspension duration/review.**
7. **`BR-1.12` — is `invited` real?** *(Cheap, and it decides whether an approval gate should exist at all.)*
8. **`BR-1.9` — Chief's authority on organisation change.** *(Lowest urgency: no signal exists to act on.)*

**Naming (`BR-1.3`) should go to the Phase 4 governance-language review, not be decided in isolation** — `revoke` is taken, and this is the fourth collision.

## 16 · Consequences for `ADR-002`

* **The proposed amendment is unaffected in substance** — nothing found here contradicts adding **Entitled** as a fourth axis.
* **One addition is now warranted:** the amendment should note that **`ADR-002`'s "Eligible" axis and the *Revocation* vocabulary refer to identity trust**, so entitlement termination must not be described as *revocation* without disambiguation.
* **`ADR-002` remains UNCHANGED.** The amendment is still a proposal awaiting authorisation, and **`BR-1` should be decided first**, per the Product Owner's sequencing.

## 17 · Consequences for the Constitution

* **Voter-level governance still has no constitutional existence** — `ElectionMembership` appears **0** times; `admit`/`restore`/`revoke` **0**; `suspend` is election-level only.
* **`ADR-T11` already constrains any future rule**: no termination rule may promise to reach a cast ballot.
* **The capability-freeze principle is available for extension** to the voter level.
* **New this commission:** the documented lifecycle (**E**) is a **candidate for elevation** — the officer guide is closer to a specification than anything in the Constitution. **Elevating documentation to a constitutional rule is a governance act; I propose nothing.**
* **The Constitution remains UNCHANGED.**

## 18 · Consequences for `Q3` (still undecided)

**Recorded as constraints on whoever decides `Q3`. No candidate selected; no overlay designed; no storage chosen.**

1. **Exercisability must distinguish at least four causes:** suspended · consumed · terminated · lifecycle/credential. **Today three collapse into `status='inactive'`.**
2. **Reversibility differs by cause** — suspension reversible, consumption not (`ADR-T11`), termination per `BR-1.2`.
3. **`Q3` cannot be finished before `BR-1.1`/`BR-1.2`** — a model that cannot express termination is incomplete.
4. **The organisation side has already solved the analogous problem** — mandatory actor and reason, guarded transitions, events, terminal state. `Q3` should not do worse.
5. **The test estate cannot currently detect a regression in voter-suspension enforcement** — any `Q3` outcome needs that coverage.

## 19 · Self-audit

**A coverage failure of mine, and it changed conclusions.** I searched `docs/adr/`, `docs/architecture/` and the Constitution across three prior commissions and **never searched `docs/election_management/`** — which contains the officer guide that documents the entitlement lifecycle. I found it only because this commission required searching for authoritative evidence before answering. **Three earlier statements need correcting:**

| Earlier statement | Correction |
|---|---|
| *"`approve` is an ungoverned de facto restore"* | **It is the DOCUMENTED restore mechanism** (*"Suspended voters show an Approve button"*). The real findings are the **asymmetry** (2 actors to suspend, 1 to restore) and the **residue** (`suspension_status` left `confirmed`) |
| *"`invited` has no producer — an unreachable enum value"* | **It is a documented business state with no implementation** — officers are told assignment yields `invited` and requires approval. **The documented approval gate does not exist in code.** A divergence, not a vestige |
| *"the single-actor `suspend` bypasses four-eyes"* | **The single-actor path is the DOCUMENTED one; the four-eyes flow is undocumented** in the officer guide. Which is intended is `BR-1.13`, a Product Owner question |

**Also corrected:** I previously reported *"no authoritative definition of voter removal"*. **Wrong** — removal is defined in the officer guide, including its permanence and its administrative escape hatch.

**Limits of this investigation, stated plainly:**
* **The officer guide's authority is unestablished.** It has no status header, no knowledge card, no steward. I classified it **E** (application rule / documented intent). **Whether it carries any governance weight is a Product Owner call** — if it does, much of `BR-1` is already answered; if it does not, it is only evidence of intent.
* **No runtime experiment was performed.** Removal, restoration and post-vote termination were **read, not executed**. `approve`-on-a-removed-voter is `I` — I inferred the absence of a guard from code, and did **not** test it.
* **I did not read the Round-N design corpus in full** — I grepped it and found only *authority*-level disqualification (certification bodies), not voter-level. **A deeper read could still hold relevant material.**
* **Test evidence is `F` throughout** and was not run. I did not execute the suspension test suite; I read its assertions.
* **`PBDIGIT-69`** is cited nowhere in this investigation.
* **Session 1's artifacts** — `SD-1`, `SD-2`, `SD-4`, Master Matrix, the 1,376-test baseline — were **neither read as authority nor modified.** No handoff artifact was created; **if `D-ENT-1` is to inform `C4`/`C6`, that requires an explicit governance handoff I have not made.**

---

**Traceability:** `docs/election_management/04-voter-list.md:60-140` · `docs/election_management/README.md:1-20` · `docs/architecture/trust-domain/UBIQUITOUS_LANGUAGE.md:50-72,222-270` · `docs/adr/ADR-T-LOG-Tactical-Implementation.md:18` (`ADR-T11`) · `docs/adr/ADR-001-trust-attestation-domain.md:40,73-85` · `docs/adr/ADR-003-governance-driven-revocation.md:62-71` · `docs/adr/ADR-002-verified-eligible-authorized.md:71,78,161` · `app/Domain/Election/Constitution/ElectionConstitution.php:126-146` · `app/Contexts/Membership/Domain/Membership/MembershipLineage.php:297,334-345,364-395` · `app/Models/ElectionMembership.php:164-197,202-224` · `app/Http/Controllers/ElectionVoterController.php:196-216,222-241,290-356` · `tests/Feature/Election/ElectionVoterSuspensionTest.php:43-170` · `tests/Feature/Election/ElectionVoterManagementTest.php:116-205` · `tests/Feature/Election/VoterEligibilityTest.php:221` · measured read-only 2026-08-12: no test asserts a suspended **voter** cannot vote · `members` 0 rows · `scopeEligible()` 0/20 · `invited` no producer.
