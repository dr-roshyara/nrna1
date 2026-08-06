# PBDIGIT-43 — `demo_votes.voting_code` is never populated

**Type:** Defect / business rule to confirm · **Epic:** `PBDIGIT-EPIC-05` Voting · **Created:** 2026-08-06
**Found by:** the anonymity check that verified `PBDIGIT-38`'s first successfully saved vote

| | |
|---|---|
| **Status** | **OPEN — not authorised** |
| **Customer impact** | **Unknown, and that is the point.** A voter may be unable to retrieve their own vote later. Not yet traced |

---

## What was observed

The first vote ever persisted in this repository (`PBDIGIT-38` verification) has:

```
demo_votes.voting_code = NULL
demo_codes.voting_code = NULL
demo_codes.code_to_open_voting_form = '8NZ2X9DM'
```

**Product Owner's rule, stated during that verification:**

> **`voting_code` must be built from the `vote_id`, not the `code_id`.**

## Why the rule matters — it is an anonymity rule, not a convenience

**This is the reason the story exists, and it should not be lost in implementation detail:**

* Deriving `voting_code` from the **`vote_id`** keeps the receipt anonymous — `demo_votes` holds no voter linkage, so a code derived from it cannot be reversed to a person.
* Deriving it from the **`code_id`** would create a linkage path: `demo_codes` holds **both** `voting_code` **and** `user_id`, so a shared value would make `vote → code → user` joinable. **That would break ADR-T11.**

> **So the rule is not "use this id rather than that one". It is: the receipt must be derived from something that carries no identity.**

**Current anonymity status: SAFE, by accident.** Because `demo_votes.voting_code` is `NULL`, the join yields **zero** rows and no linkage exists today (verified). **A future implementation that populated it from `code_id` would silently introduce the linkage** — which is exactly why the rule is recorded before the field is filled.

## What is not yet established

* [ ] **Is the field supposed to be populated at all?** `save_vote()` does not set it. Whether that is an omission or a deliberate anonymity measure is **not known**.
* [ ] **Which flow consumes it?** Routes exist that look like consumers — `POST /demo/vote/submit-code` (`demo.vote.submit_code_to_view_vote`) and `GET /demo-vote/show/{vote_id}` (`demo.vote.show`) — plus `demo-vote/verify-show`, which **does** render (`Vote/DemoVote/VerifyVotingCode`, 200) after a successful vote. **Whether that page works when `voting_code` is NULL was not tested.**
* [ ] **Is `receipt_hash` already serving this purpose?** The saved vote has a populated `receipt_hash` **and** a populated `vote_hash`. **Three receipt-shaped fields exist and only two are filled** — so the first question may be which of them is the real receipt, not how to fill the third.

## Acceptance criteria

* [ ] Establish whether `voting_code` has a consumer, or is superseded by `receipt_hash`/`vote_hash`.
* [ ] If it is required: populate it **derived from `vote_id`**, never from `code_id` or any code-table value.
* [ ] 🔒 **A test asserts the join stays empty**: `demo_votes ⋈ demo_codes` on any shared value must return zero rows. **Assert the property, not the derivation** — that is what protects ADR-T11 against a future well-meaning change.
* [ ] The voter-facing retrieval flow is walked end to end, not inferred.

## Explicitly out of scope

**Do not populate the field to make a page work.** If `verify-show` needs a code, establish which field is the canonical receipt first — the product currently has three candidates and no stated answer.

---

**Traceability:** `docs/publicdigit/adr/ADR_20260806_1340_Audit_Event_Transaction_Boundary.md` §8 (the anonymity check that surfaced the null) · `PBDIGIT-38` · Product Owner rule, 2026-08-06 · `app/Http/Controllers/Demo/DemoVoteController.php` `save_vote()` (`:2725`) · routes `demo.vote.submit_code_to_view_vote`, `demo.vote.show`, `slug.demo-vote.verify_to_show` · root `CLAUDE.md` (*"voting_code: hashed audit trail only"*) · ADR-T11
