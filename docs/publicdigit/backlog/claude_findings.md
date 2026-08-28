
Classification resolved: C — current enforcement still depends on the legacy mechanism.

I took the one step that settled it and traced ElectionVotingController::start() forward. It never persists a vote. It creates or refreshes a VoterSlug and redirects to slug.code.create — handing the voter straight into the VoterSlug + Code flow that VoteController serves.

That falsifies my own hypothesis (1): VoteController isn't an older parallel path, it is the path start() leads to. So the picture is:

start()  → reads election_memberships.has_voted   (entitlement gate)
         → refreshes VoterSlug (can_vote_now=true, +30min)
         → redirect → VoteController → writes has_voted on voter_slugs / codes

election_memberships.has_voted is read and written by nothing. ElectionMembership::markAsVoted() remains dead code by call-graph. The thing that actually records having voted is the mechanism you called legacy.

The detail I'd look at first: the reuse branch re-arms an existing slug — status=active, can_vote_now=true, fresh expiry — before any check of that slug's own voting state. So whatever prevents a second vote is not in start(). It must live inside the Code flow, or nowhere. Given your two-use code design, code exhaustion is now the likely candidate, and that's the remaining untested hypothesis.

I am not claiming double voting is possible. No vote has been cast in this programme. The claim is narrower and fully evidenced: which representation the current path writes — and it isn't election_memberships.

This feeds back into PBDIGIT-66 in a useful way. ElectionMembership is the proven authority for entitlement, but demonstrably not for "has exercised the vote." So the collapse of registration/eligibility/entitlement into one row does not extend to the one-vote invariant — which is real evidence that D-1/D-2 are separate business decisions rather than one, rather than my having to guess.

Committed as 2b1d1862. Nothing changed anywhere; suite not run.

The next narrow step, if you want it: trace the Code flow's own one-vote enforcement and establish whether the invariant holds without election_memberships participating at all.
Stop says: Project-state sync (CLAUDE.md):
  x Session log .claude/sessions/2026-08-09.md missing (5 files modified today)
  x CONTEXT.md not updated today - refresh active ticket / next action if they changed
  o no plan file touched today (fine for small fixes; update the active plan for ticket work)
  (non-blocking; modified files: .claude/runtime/2026-08-09-files.log)

#

Read-only. Starting with the strongest possible evidence — a database-level guarantee — then the controller guards.

  Ran 1 shell command

Decisive structural finding. Let me confirm the guard that reads the code state.

  Ran 3 shell commands

Classification: B — the mechanism enforces the invariant, but legacy state is still consulted.

And the structural finding reframes the whole question. Reading the actual indexes from PostgreSQL:

codes         UNIQUE (election_id, user_id)   ← one credential per voter per election
voter_slugs   UNIQUE (election_id, user_id)
votes         UNIQUE (id), (vote_hash), (receipt_hash)   ← nothing tying a vote to a voter

votes has no uniqueness against a voter, and it cannot — the table has no user_id, by constitutional design. So the one-vote invariant is structurally forced off the ballot and onto the credential. That isn't legacy residue; anonymity makes credential exhaustion the only place it can live.

Your three facts turn out to have three different owners, and the product does not collapse them:

┌────────────────────┬────────────────────────────────────────────────────────────┬──────────────────────────────┐
│                    │                       Representation                       │            Owner             │
├────────────────────┼────────────────────────────────────────────────────────────┼──────────────────────────────┤
│ Entitlement        │ ElectionMembership (role, status)                          │ Application                  │
├────────────────────┼────────────────────────────────────────────────────────────┼──────────────────────────────┤
│ Execution          │ Code.can_vote_now + slug, window, IP                       │ Application                  │
├────────────────────┼────────────────────────────────────────────────────────────┼──────────────────────────────┤
│ One-vote invariant │ the codes row — unique per election+user, exhausted on use │ Infrastructure + Application │
└────────────────────┴────────────────────────────────────────────────────────────┴──────────────────────────────┘

VoteController:172-176 gates the real path on a code with can_vote_now = true for that useAsVoted() clears can_vote_now and the save-vote flag. Your §6 election-scoping check passes— guard, exhaustion write and unique index are all keyed on the election, not user-global.

This revises my own Appendix 3a "C". That answered a narrower question — does the current path consult legacy state? — correctly. Asked about the invariant instead of the field, the answer is B: the invariant
has a real, sufficient mechanism, and election_memberships.has_voted isn't required for it

Your architectural prediction was right: the fault isn't a missing mechanism. ElectionVotihe entitlement model a question that belongs to the exercise-of-vote history — and sincenothing writes that field, that particular guard is permanently false and therefore inert.

What I did not establish, and it matters: I never traced the arming step that sets can_vote_now = true, so this explains how a second vote is prevented, not that a first can complete. Failure modes 1–7 weren't exercised. And the single most important open question — can start() re-arming a VoterSlugUNDETERMINED, because that's the one path that could defeat exhaustion.

For PBDIGIT-66, D-2 now has evidence rather than speculation: registration/eligibility/entariant are already separate, and anonymity forces them apart. A constitution that fusedthem would contradict the anonymity invariant. I've recorded that as evidence for the decision, not as the decision.

Committed as 379b4498. Nothing changed anywhere.
Stop says: Project-state sync (CLAUDE.md):
  x Session log .claude/sessions/2026-08-09.md missing (5 files modified today)
  x CONTEXT.md not updated today - refresh active ticket / next action if they changed
  o no plan file touched today (fine for small fixes; update the active plan for ticket work)
  (non-blocking; modified files: .claude/runtime/2026-08-09-files.log)
  #
#

The crux is which table's can_vote_now the reuse branch actually writes. Checking.

  Ran 1 shell command

start() never touches Code at all. One residual check: does anything else re-arm the code?

  Ran 2 shell commands

A — RE-ARMING IMPOSSIBLE. The evidence is clean and the design is deliberate.

ElectionVotingController::start() never touches Code at all — I grepped its entire body (:98-215) and there are zero references. The reuse branch at :167 re-arms voter_slugs.can_vote_now, and both tables have a column by that name. But the vote guard reads the other one:

Code::withoutGlobalScopes()
    ->where('user_id',…)->where('election_id',…)
    ->where('can_vote_now', true)->exists();     // ← codes, not voter_slugs

So re-arming the slug does not re-arm the credential. That was the whole question, and the two can_vote_now columns are exactly what made it look dangerous.

The one other path that could have defeated it is explicitly guarded. CodeController regenerates expired codes, and its condition opens with:

$codeNeedsReset = !$code->has_voted && ( … );

A voter who has already voted never enters the regeneration branch. And even inside it, the write is 'can_vote_now' => $code->can_vote_now — it preserves the prior value rather than setting true. It cannot promote an exhausted credential.

So the invariant is structurally strong, and for a reason worth stating: codes UNIQUE (election_id, user_id) means there is only ever one credential row per voter per election, so no path can mint a second. Exhaustion is therefore terminal, and it's election-scoped on every key — guard, exhaustion write, and unique index.

One honest boundary: this proves a second vote cannot be submitted. It does not prove a first one can — I still never traced what initially sets codes.can_vote_now = true, and no vote was ever cast. That remains NOT ESTABLISHED.

On your framing, I agree and would adopt your wording. Saying "the voting system depends ooad. The accurate statement is: the entry path still consults a legacy, inertelection_memberships.has_voted, while the one-vote invariant is enforced independently by the election-scoped credential. The three concerns — entitlement, execution permission, exercise history — are genuinely
separate in the implementation, and anonymity is what forces them apart. That separation iserving into the Manifesto, not debt to be consolidated away.

I have not recorded this last trace to disk yet — my context here is essentially spent, soge only. If you want it durable, the natural home is a fifth appendix on the constitutional review, and it's a small write.
Stop says: Project-state sync (CLAUDE.md):
  x Session log .claude/sessions/2026-08-09.md missing (6 files modified today)
  x CONTEXT.md not updated today - refresh active ticket / next action if they changed
  o no plan file touched today (fine for small fixes; update the active plan for ticket work)
  (non-blocking; modified files: .claude/runtime/2026-08-09-files.log)

  #
  This is a **strong and important closure of the one-vote question**.

I would accept the finding, with one wording correction: **“the invariant is structurally strong”** is supported; **“the invariant is fully verified”** is not yet supported because the first-vote path has not been established or exercised.

### What is now established

The architecture separates three different concerns:

1. **Entitlement** — `ElectionMembership`

   * Does this person have the right to vote in this election?

2. **Execution permission** — `Code.can_vote_now`

   * Can this voting credential currently be used?

3. **Exercise history / one-vote invariant** — exhausted election-scoped credential

   * Has the voter's voting right already been exercised?

And importantly:

```text
ElectionMembership.has_voted
        ↓
legacy/inert consultation
        ↓
NOT the authoritative one-vote mechanism
```

while:

```text
codes
  UNIQUE (election_id, user_id)
        ↓
one credential per voter per election
        ↓
can_vote_now = true
        ↓
vote
        ↓
credential exhausted
        ↓
cannot be re-armed
```

The potentially dangerous-looking `voter_slugs.can_vote_now` is **not the credential guard**. `start()` can re-arm the slug, but the actual voting guard checks `codes.can_vote_now`. That is a very good example of why we insisted on tracing the mechanism rather than reasoning from field names.

### The most important architectural conclusion

I would record this as:

> **The current implementation separates voter entitlement, voting execution permission, and exercise-of-vote history. The legacy `election_memberships.has_voted` field remains consulted by the entry path but is not the authoritative mechanism for the one-vote invariant. The authoritative enforcement is the election-scoped voting credential, whose uniqueness and terminal exhaustion prevent a second valid vote.**

And I would explicitly preserve this distinction in the Manifesto discovery.

### But one thing remains open

There are now two logically separate verification questions:

| Question                                                 | Status                                             |
| -------------------------------------------------------- | -------------------------------------------------- |
| Can a voter submit **two** valid votes?                  | **Established: no, by static structural evidence** |
| Can an eligible voter submit their **first** valid vote? | **Not established**                                |
| Is the voting credential initially armed correctly?      | **Not established**                                |
| Does the complete first-vote runtime journey work?       | **Not verified**                                   |

So I would **not let Claude move into repair/refactoring yet**.

The next step should be the complementary investigation:

> **Trace how `codes.can_vote_now` initially becomes `true`, from voter entitlement/import through creation/activation of the credential, and establish whether an eligible Election-Only voter can legitimately reach the first-vote path.**

That connects directly to your original business question: *after importing five voters in Election-Only mode, are they actually granted the right and access to vote?*

And this is where the **Election Manifesto question becomes important**: we now know what the implementation does, but we still need to establish **who owns the business decision that grants voting entitlement and who makes that entitlement available during the voting window**.

I would keep the current conclusion separate from that constitutional decision. No need to invent the Constitution merely to explain existing code.
This is a **strong and important closure of the one-vote question**.

I would accept the finding, with one wording correction: **“the invariant is structurally strong”** is supported; **“the invariant is fully verified”** is not yet supported because the first-vote path has not been established or exercised.

### What is now established

The architecture separates three different concerns:

1. **Entitlement** — `ElectionMembership`

   * Does this person have the right to vote in this election?

2. **Execution permission** — `Code.can_vote_now`

   * Can this voting credential currently be used?

3. **Exercise history / one-vote invariant** — exhausted election-scoped credential

   * Has the voter's voting right already been exercised?

And importantly:

```text
ElectionMembership.has_voted
        ↓
legacy/inert consultation
        ↓
NOT the authoritative one-vote mechanism
```

while:

```text
codes
  UNIQUE (election_id, user_id)
        ↓
one credential per voter per election
        ↓
can_vote_now = true
        ↓
vote
        ↓
credential exhausted
        ↓
cannot be re-armed
```

The potentially dangerous-looking `voter_slugs.can_vote_now` is **not the credential guard**. `start()` can re-arm the slug, but the actual voting guard checks `codes.can_vote_now`. That is a very good example of why we insisted on tracing the mechanism rather than reasoning from field names.

### The most important architectural conclusion

I would record this as:

> **The current implementation separates voter entitlement, voting execution permission, and exercise-of-vote history. The legacy `election_memberships.has_voted` field remains consulted by the entry path but is not the authoritative mechanism for the one-vote invariant. The authoritative enforcement is the election-scoped voting credential, whose uniqueness and terminal exhaustion prevent a second valid vote.**

And I would explicitly preserve this distinction in the Manifesto discovery.

### But one thing remains open

There are now two logically separate verification questions:

| Question                                                 | Status                                             |
| -------------------------------------------------------- | -------------------------------------------------- |
| Can a voter submit **two** valid votes?                  | **Established: no, by static structural evidence** |
| Can an eligible voter submit their **first** valid vote? | **Not established**                                |
| Is the voting credential initially armed correctly?      | **Not established**                                |
| Does the complete first-vote runtime journey work?       | **Not verified**                                   |

So I would **not let Claude move into repair/refactoring yet**.

The next step should be the complementary investigation:

> **Trace how `codes.can_vote_now` initially becomes `true`, from voter entitlement/import through creation/activation of the credential, and establish whether an eligible Election-Only voter can legitimately reach the first-vote path.**

That connects directly to your original business question: *after importing five voters in Election-Only mode, are they actually granted the right and access to vote?*

And this is where the **Election Manifesto question becomes important**: we now know what the implementation does, but we still need to establish **who owns the business decision that grants voting entitlement and who makes that entitlement available during the voting window**.

I would keep the current conclusion separate from that constitutional decision. No need to invent the Constitution merely to explain existing code.
#
This is a **strong and important closure of the one-vote question**.

I would accept the finding, with one wording correction: **“the invariant is structurally strong”** is supported; **“the invariant is fully verified”** is not yet supported because the first-vote path has not been established or exercised.

### What is now established

The architecture separates three different concerns:

1. **Entitlement** — `ElectionMembership`

   * Does this person have the right to vote in this election?

2. **Execution permission** — `Code.can_vote_now`

   * Can this voting credential currently be used?

3. **Exercise history / one-vote invariant** — exhausted election-scoped credential

   * Has the voter's voting right already been exercised?

And importantly:

```text
ElectionMembership.has_voted
        ↓
legacy/inert consultation
        ↓
NOT the authoritative one-vote mechanism
```

while:

```text
codes
  UNIQUE (election_id, user_id)
        ↓
one credential per voter per election
        ↓
can_vote_now = true
        ↓
vote
        ↓
credential exhausted
        ↓
cannot be re-armed
```

The potentially dangerous-looking `voter_slugs.can_vote_now` is **not the credential guard**. `start()` can re-arm the slug, but the actual voting guard checks `codes.can_vote_now`. That is a very good example of why we insisted on tracing the mechanism rather than reasoning from field names.

### The most important architectural conclusion

I would record this as:

> **The current implementation separates voter entitlement, voting execution permission, and exercise-of-vote history. The legacy `election_memberships.has_voted` field remains consulted by the entry path but is not the authoritative mechanism for the one-vote invariant. The authoritative enforcement is the election-scoped voting credential, whose uniqueness and terminal exhaustion prevent a second valid vote.**

And I would explicitly preserve this distinction in the Manifesto discovery.

### But one thing remains open

There are now two logically separate verification questions:

| Question                                                 | Status                                             |
| -------------------------------------------------------- | -------------------------------------------------- |
| Can a voter submit **two** valid votes?                  | **Established: no, by static structural evidence** |
| Can an eligible voter submit their **first** valid vote? | **Not established**                                |
| Is the voting credential initially armed correctly?      | **Not established**                                |
| Does the complete first-vote runtime journey work?       | **Not verified**                                   |

So I would **not let Claude move into repair/refactoring yet**.

The next step should be the complementary investigation:

> **Trace how `codes.can_vote_now` initially becomes `true`, from voter entitlement/import through creation/activation of the credential, and establish whether an eligible Election-Only voter can legitimately reach the first-vote path.**

That connects directly to your original business question: *after importing five voters in Election-Only mode, are they actually granted the right and access to vote?*

And this is where the **Election Manifesto question becomes important**: we now know what the implementation does, but we still need to establish **who owns the business decision that grants voting entitlement and who makes that entitlement available during the voting window**.

I would keep the current conclusion separate from that constitutional decision. No need to invent the Constitution merely to explain existing code.
