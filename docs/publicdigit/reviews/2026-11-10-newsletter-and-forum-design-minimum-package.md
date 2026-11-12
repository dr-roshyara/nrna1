# Newsletter (Communication) and Membership Forum (Deliberation): design for the minimum package — PROPOSED

| | |
|---|---|
| **Status** | **ACCEPTED 2026-11-10 (D-158; act `docs/publicdigit/reviews/2026-11-10-minimum-package-tactical-designs-acceptance-act.md`).** No implementation authorised. Written by Claude, not independently reviewed |
| **Scope** | D-146 (minimum package), D-147 (units, placement by act; no area rules, no Reference Geography) |
| **Consumes** | Strategic decisions D-50 … D-64; model document §23, §24 (as corrected by §31), §27, §35, §36; Governance thin slice (`2026-11-10-governance-thin-slice-minimum-package.md`, D-148 … D-150); D-101, D-103, D-104, D-111, D-130, D-143; CR-01 (C1 … C3) |

**Labels:** [AD] decided · [BF] fact · [AC] conclusion · [TD] tactical design · [WD] working decision · [P] proposal · [OQ] Principal question · [LQ] legal question · [TU] technical uncertainty.

## 0. Findings first

1. **[BF] The current newsletter code authorises by organisation role.**
   - `OrganisationNewsletterController` calls `authorizeAdmin` / `authorizeNewsletterAccess` on `UserOrganisationRole` = admin or owner (lines 35, 51–56, 176, 201).
   - **This contradicts D-58** (nobody sends until a committee designates an editor) **and PO-ORG-MEM-01 A** (admin rights are not authority).
   - It is evidence of today's behaviour only; the new design replaces the check with Governance's `AuthorityCheck`.
   - It also serves **election officers** (lines 85–87): those are Election's communications, **outside the minimum package**.
2. **[BF] Membership standing does not reach Organisation.**
   - The Membership → Organisation contract carries **coverage** only (C1, C2).
   - **Suspension (`Suspended`) is in C3, whose ratified consumer is Governance.**
   - Deliberation is **not** a ratified Membership partner (§36 conflict 2).
   - **Any rule that depends on suspension** (e.g. "suspended members cannot post") **needs a Membership change request** under the ratified act. Decided for release 1 by D-153.
3. **[AD, applied] Contact details** are the organisation's person profile (D-101; §31 corrects §24). **The account's login e-mail is a credential, never a newsletter address.**

## 1. Business summary

- **Newsletter:**
  - a committee decides to publish a series (e.g. "Members' Bulletin", national, or "Province Y News") and designates its editor at a sitting;
  - the editor writes issues and sends them to the members of that scope;
  - PublicDigit keeps proof of who sent what, to whom, on what authority, and whether delivery was attempted or accepted;
  - **it never claims a member read it.**
- **Forum:**
  - every unit has a forum;
  - **members read and write in their own unit's forum and those above it**, automatically, from where they belong, never from a hand-kept list;
  - a forum is **read-only until a committee designates a moderator** (D-150);
  - moderators hide or restore posts and link duplicates, never silently delete;
  - every moderation act can be challenged.

## 2. Newsletter (Communication context)

### 2.1 Ubiquitous language

| Term | Meaning | Not |
|---|---|---|
| **Series** | A newsletter publication decided by a committee, for a scope (a unit and those below) | A mailing list someone keeps |
| **Issue** | One edition of a series; content fixed once sent | An official notice or convocation (separate capability, §24) |
| **Editor** | The holder of the series' editor office (Governance, thin slice) | An admin; a role in Communication |
| **Audience snapshot** | The recipients resolved at send time, each with the basis of inclusion | A stored list maintained by hand |
| **Delivery record** | Per recipient: attempted, accepted by the mail system, bounced | Proof of receipt or reading |

### 2.2 Aggregates [TD]

| Aggregate | Responsibility | Invariants |
|---|---|---|
| **Series** | Created from a Governance decision (reference); scope `unitRef`; audience rule (members of scope; other capacities only if the decision says so, D-57); state active / ended | **Exists only by a recorded committee decision** (D-58); **ends when its scope unit ends** (follows D-145 for the editor office) or by decision |
| **Issue** | Draft → sent (or withdrawn); content, subject, purpose declaration | **Sent only if, at send time, `AuthorityCheck(send-newsletter(series))` = Authorised** (asserted or worked out); the answer is stored as the issue's basis; **content immutable after sending**; official purpose declared by the editor (D-59), challengeable |
| **Dispatch** (per issue) | Audience snapshot and delivery records | One snapshot per issue; each inclusion has a basis; delivery records append-only |
| **Preference** (per person, per organisation) | Opt-outs per series (**D-151**); re-subscribe allowed; editors see counts only | Never affects official notices |

### 2.3 Sending, step by step

1. Editor requests send of issue I.
2. Communication asks Governance: `AuthorityCheck(editor, send-newsletter(series), scope, now)`. **Not Authorised or Unknown → refused, reported.**
3. Communication asks Organisation: **members belonging to the scope unit or below, as at now** (Organisation's effective belonging, which includes Membership coverage, CR-01 C2). Other capacities only if the series decision includes them.
4. For each, the **contact endpoint from the organisation's person profile** (D-101). **No endpoint → excluded, reason recorded.**
5. Apply opt-outs (D-151).
6. Store the snapshot with the basis of each inclusion; dispatch; record delivery outcomes.

**Failure:**
- if Organisation or Governance cannot answer completely for that time, **the send is refused**, never sent to a partial audience (D-73);
- retries are idempotent per (issue, recipient).

### 2.4 Rules applied

| Rule | Design |
|---|---|
| D-57 members now, outside subscribers later | Audience = members (+ capacities named by the decision); **no public sign-up in release 1** |
| D-58, D-60 editor designated by a committee at a sitting | Governance thin slice: functional office, AuthorityCheck |
| D-59 official purpose, no candidacy advertising | Editor's declaration on each issue; challengeable; **no automatic content policing** |
| D-61 a candidate may be editor | No restriction |
| §24 no "has voted" audiences | Audiences come only from belonging and capacities |

## 3. Membership Forum (Deliberation context)

### 3.1 Ubiquitous language

| Term | Meaning | Not |
|---|---|---|
| **Unit forum** | The forum of one organisational unit | A group someone joins |
| **Committee forum** | A confidential forum of a committee; participants are its office holders | **Optional in release 1** [P] |
| **Post / thread** | A contribution; the forum's rules in force at posting apply to it permanently (DL) | — |
| **Idea and support** | An idea members can support once each, withdrawable (DL-1, DL-10) | A vote; a ranking that decides anything (DL-2) |
| **Moderator** | Holder of the forum's moderator office (Governance, thin slice) | An admin; someone with many points (DL-3) |
| **Moderation act** | Hide, restore, lock, link duplicates; each with a reason | Deletion of history |

### 3.2 Who reads and who writes [TD]

- **Read:** **a unit's forum is for the members belonging to that unit or any unit below it** (**D-152**): ward forum → that ward's members; district forum → all members within the district; top unit's forum → all members. From the member's side: their own unit's forum and every forum above it (D-56 default). **The organisation's rules may widen this per forum**; widening applies only to later posts (DL).
- **Write:** in the same forums, **when the forum is open** (has a moderator, D-150), by **members with membership coverage** (**D-153**); pending applicants, workers and guests read only.
- **Derived, never stored as a list:**
  - every read and write is checked against Organisation's belonging at the time;
  - **a member who moves unit loses access to forums no longer above them, and gains the new ones**;
  - their earlier posts stay where they were.
- **Pseudonyms:** per forum by the rules; default real names (D-55), taken from the organisation's person profile.
- **Supports:** counted only by default; names visible to no role (D-52, DL).

### 3.3 Aggregates [TD]

| Aggregate | Responsibility | Invariants |
|---|---|---|
| **Forum** | One per active unit (created when Organisation reports the unit); settings from the rules (D-52, D-55, D-56); state **read-only / open / archived** | **Open only while a moderator tenure covers it** (D-150); **archived (read-only, kept) when its unit ends**; settings changes apply to later posts only |
| **Thread** (posts inside) | Posts, edits as new versions, moderation states | Author had write right at posting; post carries the rules version in force; **history never deleted** |
| **Idea** (supports inside) | Supports, withdrawals; duplicate links | One support per entitled person (DL-1); no automatic merge (DL-3) |
| **ModerationAct** | One act by a moderator, with reason and basis | **`AuthorityCheck(moderate(forum))` = Authorised at the act's time**, stored as basis; **reviewable per D-53** (default the tribunal; manual until Adjudication is in scope); a pending challenge changes nothing (D-71) |

### 3.4 Forum and unit lifecycle

| Unit event (Organisation) | Forum |
|---|---|
| Unit established | Forum created, **read-only** until a moderator is designated |
| Moderator designated (Governance) | Forum **open** |
| Moderator's term ends, none other covering | Back to **read-only** |
| Unit re-parented (Organisation structure change) | Readers change from the effective date (derived) |
| Unit ends | Forum **archived**: read-only, history kept; moderator office ends (D-145) |

## 4. Context relationships (release 1)

| Fact | Owner | Consumer | Never | If unavailable |
|---|---|---|---|---|
| Editor, moderator tenures; authority answer | Governance | Communication, Deliberation | Communication or Deliberation deciding who is editor or moderator | Refuse the act |
| Members of a scope; a person's belonging and ancestors | Organisation | Communication, Deliberation | **Deliberation or Communication reading Membership directly** (§36) | Refuse (send) or deny (read / write) |
| Contact endpoints | Organisation (person profile, D-101) | Communication | Account login e-mail used as contact | Recipient excluded with reason |
| Membership coverage | Membership → Organisation (C1, C2) | (via Organisation) | Suspension used without a contract (finding 2) | — |
| Workspace access | Organisation (D-104) → Authorization | — | **Admin role ≠ editor or moderator** | — |
| Forum notifications | Deliberation → Communication | Members who opted in | — | — |

**Geography:** none. Forum scopes and newsletter audiences are units; **a map change never changes who reads or receives** (D-106 §6).

## 5. Scenarios

| # | Situation | Outcome |
|---|---|---|
| 1 | Admin with no editor term tries to send | Refused (D-58); reported |
| 2 | Editor sends "Members' Bulletin" to national scope | Snapshot of all effective members with a profile e-mail; others excluded with reason |
| 3 | Organisation's belonging service down at send time | Send refused; retry later; never partial |
| 4 | Editor's term ended yesterday | Refused at send, whatever the UI shows |
| 5 | Issue sent; editor wants to fix a typo | Not editable; a correction issue may follow |
| 6 | New district created | District forum exists, read-only; members of the district read it; posting after a moderator is designated |
| 7 | Member moved from district D1 to D2 | From the effective date reads and writes D2's forum (and above), no longer D1's; old posts stay in D1 |
| 8 | Moderator hides a post | Hidden with reason; author notified; challengeable; restorable; nothing deleted |
| 9 | Member supports an idea twice | Second refused; withdrawal allowed |
| 10 | District dissolved | Forum archived read-only; series scoped to it end |
| 11 | Ward re-parented on the official map | No effect |
| 12 | Suspended member tries to post | **Allowed in release 1** (suspension not supplied; D-153); a later Membership change request can restrict it |
| 13 | Member unsubscribes from a series | Excluded from that series from then on; still receives formal notices (**D-151**) |

## 6. Principal questions (asked one at a time)

- ~~OQ-N1~~ **Decided: D-151** — opt-out per series; formal notices unaffected; editors see counts only. *(Original question:* may members opt out of a newsletter series? *(Formal notices stay non-optional either way.)* [LQ: German/EU e-mail and data-protection law may require an opt-out for newsletters; to be confirmed by counsel.])
- ~~OQ-F1~~ **Decided: D-153** — members with coverage write; pending applicants, workers and guests read only; suspension not applied until a Membership change request. *(Original question:* when the rules are silent, who may write in a forum? Members only, or also pending applicants, suspended members, workers and guests?)

## 7. Coherence assessment

- **No duplicate ownership.** Authority is Governance's, audiences and belonging Organisation's, contacts Organisation's, sending Communication's, posts and moderation Deliberation's.
- **New cross-context reads (all [P]):**
  - Organisation **`MembersOfScopeAsAt(unit, τ)`** and **`BelongingWithAncestorsAsAt(person, τ)`**, refinements of R-5 (D-32);
  - Governance **`AuthorityCheck`** (thin slice).
- **[TU]:**
  - the mail provider's delivery evidence;
  - idempotent dispatch;
  - access checks per request without a stored participant list (caching must never outlive a belonging change).
- **Before implementation:**
  - acceptance of this design, the Governance thin slice and Organisation §49–§50;
  - [LQ] counsel on newsletter consent.

**Amendment note (2026-11-10, D-161 / CR-02, a Principal decision after acceptance under D-158):**
- **Newsletter audience:** members of the scope **in good standing** (in force and not suspended) as at the send's τ. Lapsed and suspended members are excluded, with the reason recorded.
- **Forum write:** members **in good standing** only.
- **Forum read:** lapsed and suspended members **can still read** the forums they belong to (D-152).
- Standing reaches Communication and Deliberation **through Organisation** (CR-02 §3), never from Membership directly.
- **Scenario 12 now reads:** a suspended member tries to post → refused (D-161).
- **Finding 2 of §0** (suspension unavailable) is resolved by CR-02.

**Conformance note (2026-11-11, baseline acceptance pass P-1, P-2; implements D-167 and D-168, no new rule):**
- **`ModerationAct` / challenges:** a challenge is reviewed by the body the organisation's rules or a designation name; **until then it is recorded and waits** (D-167 replaces D-53's tribunal default).
- **Posting and newsletter audience (D-168):**
  - Forum and Newsletter read **`ParticipationRuleAsAt`** from Governance at the act's τ;
  - they apply the organisation's provision if recorded, **else the platform default D-161** (in force and not suspended);
  - **each access decision and audience snapshot records `ruleSource`** (organisation provision ref, or "platform default (D-161)") (D-167 label).
- **Display defaults** (D-52 counted supports, D-55 real names) and **office counts** (D-171) carry the same label where applied.
