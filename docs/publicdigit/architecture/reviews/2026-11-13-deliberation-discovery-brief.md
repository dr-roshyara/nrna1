# Deliberation (Membership Forum): discovery brief (PBDIGIT-347)

**Date:** 2026-11-13 · **Status:** brief for the strategic discovery. **No tactical design and no code.**

**Authority:**
- the accepted forum design for the minimum package (**D-158**: `docs/publicdigit/reviews/2026-11-10-newsletter-and-forum-design-minimum-package.md` §3, §4);
- D-50 to D-56 and D-146 to D-153 (decision record `docs/publicdigit/reviews/2026-11-08-organisation-strategic-discovery-record-structure-mandates-conventions.md`);
- the strategic baseline rev3 (Deliberation row; K-4, K-5, K-7; U-5).

**This brief records the Principal's new product direction** (the "hybrid forum, Option C" proposal, 2026-11-13) and Session A's review of it. It **reconciles both against what is already decided.** It decides nothing new: every point that would change or widen an accepted decision is listed in §5 as a question for the Principal.

## 1. The product direction recorded (Principal, 2026-11-13)

1. **A hybrid forum.** Structured questions with answers ("Stack Overflow style") **and** open community discussion and proposal development. Not only a Q&A system.
2. **A community feed** as the landing page, combining questions, ideas and discussions.
3. **Topics** (subject areas such as Education or Finance) **group discussions;** a topic is not a discussion.
4. **Members can create questions, ideas, proposals and open discussions.**
5. **Support** for ideas and contributions; **no downvote as a ranking mechanism**; reporting inappropriate content.
6. **Sorting** (most supported, newest, most active, unanswered) **without suppressing minority opinions.** Every contribution stays reachable unless moderated under clear rules.
7. **Following** topics and discussions, with notifications.
8. **Popularity is never authority.** An idea's support, ranking or discussion never constitutes an organisational decision. A developed proposal enters **Governance** only by formal submission.
9. **Option C, an organisation forum with configurable visibility:**
   - each organisation owns its forum;
   - **reading** (audience), **participation** (creating, replying, supporting) and **moderation** are three separate policies;
   - the proposed audience levels are public, members only, and a restricted group.

## 2. Already decided: the direction CONFIRMS these (no new decision)

| Point | Accepted rule |
|---|---|
| Deliberation owns drafting, discussion and co-signatures before submission; Governance owns the motion from submission | **D-50, D-51** |
| Support is **not a vote**; a ranking decides nothing | D-158 §3.1 (Idea and support; DL-2) |
| **Support is counted only by default;** whether supporters' names are visible is per forum; **co-signatures are always named** | **D-52** |
| An organ must respond to a highly supported idea only where the rules set a duty; **default: no duty** | **D-54** |
| Real names or pseudonyms per forum; default real names | **D-55** |
| **One forum per organisational unit** (scope); reading: the unit and every unit below it; committee forums confidential to their office holders | **D-56, D-152** (and D-147: release 1 uses units) |
| Writing: members with membership coverage in the forum's scope; applicants, workers and guests read only (unless the rules say otherwise) | **D-153** |
| **No unmoderated posting:** a forum is read-only until a competent committee designates a moderator; the moderator is an office (Governance), not an admin or someone with points | **D-150**; D-158 §3.1 |
| **Every moderation act** (hide, restore, lock, link duplicates) has a reason and an authority basis (`AuthorityCheck(moderate(forum))`); **history is never deleted** | D-158 §3.3 |
| **Review of moderation:** by the organisation's rules; default the independent tribunal (Contestation, Adjudication), with recusal | **D-53** |
| Access is **derived from Organisation's belonging (with Membership's standing) at the time, never stored as a list.** Deliberation never reads Membership directly | D-158 §3.2, §4; baseline K-4, K-7 |
| **Forum notifications go Deliberation → Communication** (to members who opted in) | D-158 §4 |
| Data stays in the organisation's own database | D-101, D-222; baseline |

## 3. NEW in the direction, consistent with the accepted decisions (to design in discovery)

| New element | Fits because | To settle in discovery |
|---|---|---|
| **Topics** (subject classification) | orthogonal to the unit scope (D-152): a discussion belongs to **one scope** (a unit's forum) and carries **topics** | organisation-defined, system-provided, or both? A topic list per forum or per organisation? |
| **Contribution types:** question, idea, proposal, discussion | D-158 already has Thread/Post and Idea | the lifecycle of each; how an idea becomes a proposal and then a draft motion |
| **Q&A with an answer marked helpful** | doesn't touch authority, if worded right (see §4.2) | who may mark; whether marking can be changed |
| **Following and notifications** | D-158 §4 already routes forum notifications through Communication | the opt-in rule; frequency; that a notification never carries content the recipient may not read |
| **A community feed and sorting** | DL-2: ranking decides nothing | **default sort** (see §4.3); the feed only ever shows forums the reader may read |
| **Reporting content** | moderation acts already defined | how a report reaches the moderator; reporter protection |

## 4. Session A's review of the direction: corrections kept, and those withdrawn

### 4.1 Kept: language. Never "vote" in the forum

In PublicDigit, a "vote" belongs to **Election**, with its anonymity guarantees. The proposal's domain list has both "Endorsement" and "Vote" as feedback actions.
- **Use only Support**, as D-158 already does.
- **Co-signature is a separate, formal, named act** (D-52), checked by Governance at submission.

### 4.2 Kept: an answer marked helpful is not an official position

In a party, an "accepted answer" to "what is our position on X?" reads as official. Two separate markers:
- **helpful to the asker** (the asker's choice);
- **official answer** (only an office holder authorised for it, through `AuthorityCheck`, never a forum role).

### 4.3 Kept: a neutral default order

"Most supported" as the **default** lets majorities dominate the page.
- **Default to newest or most recent activity;** "most supported" is a choice.
- **Explain how ranking works,** and use no hidden amplification.

### 4.4 Kept: no forum-owned member lists

"Restricted group or capacity" must come from **Organisation** (units, committees, capacities) and Governance (offices). **Deliberation keeps no group lists of its own** (D-158 §3.2 "derived, never stored as a list").

### 4.5 Withdrawn (already decided; my review had missed D-158)

- unit scope (D-152);
- named versus counted support and named co-signatures (D-52);
- moderation legitimacy and appeal (D-150, D-53, D-158 §3.3);
- pseudonyms (D-55).

**Also withdrawn:** my suggestion that forum notifications should *not* go through Communication. **It contradicted D-158 §4**, which routes them Deliberation → Communication.

## 5. Questions for the Principal: where the direction WIDENS an accepted decision

These are business decisions, to take in the discovery session, one at a time.

| # | Question | Accepted today | Consequence to weigh |
|---|---|---|---|
| **Q-D1** | **Public reading by anyone, without an account** (Option C "public")? | D-56 and D-152 let the rules **widen** reading per forum, but the accepted defaults stop at members; D-153 lets guests read only "where they belong". Anonymous public reading isn't decided | **Data protection: party membership and the political opinions posted are special-category personal data (GDPR Art. 9).** A public post reveals its author's affiliation. This needs a rule: e.g. explicit, revocable consent before a member's name appears publicly; pseudonymous public display (D-55); members-only by default. **[LQ]: counsel to confirm.** Also: a public read path from an organisation's own database (Platform routing, D-222) |
| **Q-D2** | **Restricted groups beyond units and committees?** (Option C "restricted group") | committee forums only (D-56, D-152) | A restricted group must be an Organisation or Governance fact (a committee, office or capacity), never a list kept by the forum. Is anything beyond committee forums wanted? |
| **Q-D3** | **Can non-members post in a public discussion?** | D-153: guests read, don't write (unless the rules say otherwise) | Identity account + a guest capacity (D-101); moderation load; data protection as in Q-D1 |
| **Q-D4** | **Topics:** organisation-defined, system-provided, or both? | not decided | affects cross-forum feeds and following |
| **Q-D5** | **The official-answer marker** (§4.2): wanted, and which offices? | not decided | binds forum content to Governance authority (`AuthorityCheck`) |

## 6. Known gaps carried in (not business questions)

- **U-5 (baseline):** the contract by which Deliberation learns that a moderator tenure covers a forum at a given time is **not named**. The rule (D-150) and its owner (Governance) are accepted. It's for the Forum tactical slice.
- **D-151 / D-158 [LQ]:** counsel's confirmation of the legal basis for newsletters is still required before launch. It applies equally to forum notifications sent through Communication.

## 7. Next steps

1. **The strategic discovery session** (after G-4 acceptance, alongside G-5): Q-D1 to Q-D5, one at a time, in business language.
2. **Then the tactical design:**
   - extend D-158 §3: topics, contribution types, the Q&A flow, following, the feed;
   - name the U-5 contract.
3. **Then strict-TDD implementation slices,** after the Membership routing they depend on (PBDIGIT-345).

**Traceability:** PBDIGIT-347 · D-50 to D-56, D-146 to D-153, D-158 · strategic baseline rev3 (Deliberation; K-4, K-5, K-7; U-5).
