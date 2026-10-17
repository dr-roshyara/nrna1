
Reading the idempotency and retry tests now so the precedence analysis rests on evidence (read-only).

  Ran 6 shell commands

Nearly done: two quick checks (naming conventions and any existing shared persistence abstraction), then I'll write the revised plan.

  Ran 2 shell commands

Option E, revised after ChatGPT's review (plan only)

My earlier plan was wrong on one material point. I agree with the prompt and with all four of ChatGPT's issues. Nothing was edited, run or committed; HEAD is bab1a9fbb and app/ and tests/ are clean.

1. Summary and recommendation

The correction. I had proposed checking dates before the idempotency-key lookup. The approved contract forbids that: "In append, the idempotency-key lookup precedes every other check" (2026-10-09-membership-slice1a-contract.md, §7, F-3 clarification). My order would also have made the repository disagree with the application's own key pre-check.

Corrected placement. The order in append() becomes: key lookup → date-storability check → version compare-and-set → inserts. The check still runs before any database change. On the double, this is cheap; on PostgreSQL, it is unverified until Part 2.

Second finding: this needs a contract amendment. The approved Slice 1b contract says "No new outcome" (line 48) and fixes the append steps (line 132). The Slice 1a contract lists the possible results of append (§3). Option E changes all three, so the contract amendment itself needs your approval.

Recommendation [Recommendation]. Approve Option E with this placement, plus a short amendment to both contracts. It ships as one test-first increment that needs no database.

2. What I checked in the code

┌─────┬──────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────┬────────────────────────────────────────────────────┐
│  #  │                                                                       Fact                                                                       │                       Where                        │
├─────┼──────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────┼────────────────────────────────────────────────────┤
│ V1  │ The application checks the key before authorising, loading or deciding, and checks again after a domain refusal                                  │ DecideAndAppend.php:40-43, 56-59                   │
├─────┼──────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────┼────────────────────────────────────────────────────┤
│ V2  │ PostgreSQL's order inside the transaction: key lookup → version update → (key again → conflict or not found) → inserts, where the date encoding  │ PostgresMemberRepository.php:149-196               │
│     │ happens                                                                                                                                          │                                                    │
├─────┼──────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────┼────────────────────────────────────────────────────┤
│ V3  │ The test double checks "member not found" first, then the key, then the version. This differs from PostgreSQL; it is the known PBDIGIT-264 F-3   │ InMemoryMemberRepository.php:79-91;                │
│     │ parity gap, already approved and still pending                                                                                                   │ PBDIGIT-264…md:18,31                               │
├─────┼──────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────┼────────────────────────────────────────────────────┤
│ V4  │ The fingerprint covers every command field except the key, so the dates are included                                                             │ AdmitMember.php:29-35, EndMembership.php:24-30     │
├─────┼──────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────┼────────────────────────────────────────────────────┤
│ V5  │ Admit and End refuse an infinite effectiveAt in their constructors, so only initialTermUntil can be ∞                                            │ Admit.php:22, End.php:21                           │
├─────┼──────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────┼────────────────────────────────────────────────────┤
│ V6  │ AppendResult::failed() accepts any result except success or already-applied, so a new case needs no change there                                 │ AppendResult.php:25-32                             │
├─────┼──────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────┼────────────────────────────────────────────────────┤
│ V7  │ The application's result mapping is a switch with no default; an unmapped result retries and ends as ConcurrentModification                      │ DecideAndAppend.php:62-75                          │
├─────┼──────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────┼────────────────────────────────────────────────────┤
│ V8  │ Naming convention: a repository result and its application refusal share the same name and value (MemberNotFound, IdempotencyKeyConflict). The   │ the two enums; the Slice 1a/1b contracts           │
│     │ contracts already use the term "valid time"                                                                                                      │                                                    │
├─────┼──────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────┼────────────────────────────────────────────────────┤
│ V9  │ No shared persistence-contract abstraction exists in Organisational/                                                                             │ grep                                               │
├─────┼──────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────┼────────────────────────────────────────────────────┤
│ V10 │ Only Admit and End carry a valid time. PostgreSQL's encoder refuses any other kind of fact                                                       │ EffectiveDatedFact.php;                            │
│     │                                                                                                                                                  │ PostgresMemberRepository.php:244                   │
├─────┼──────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────┼────────────────────────────────────────────────────┤
│ V11 │ PBDIGIT-264 F-1's acceptance wording requires the date to be refused before any write. Part 1 meets that only through rollback                   │ PBDIGIT-264…md:16                                  │
└─────┴──────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────┴────────────────────────────────────────────────────┘

3. When the key is checked, case by case

Each row compares today's behaviour with the behaviour after the revised Option E. "PG" means PostgreSQL; PostgreSQL behaviour is inferred from the code, not run.

┌─────┬───────────────────────────────────┬───────────────────────────────────────────────────────────────┬──────────────────────────────────────────────────────┬───────────────────────────┬──────────────┐
│  #  │               Case                │                             Today                             │                   After revised E                    │         Changes?          │   Evidence   │
├─────┼───────────────────────────────────┼───────────────────────────────────────────────────────────────┼──────────────────────────────────────────────────────┼───────────────────────────┼──────────────┤
│ 1   │ New command, storable date        │ appended                                                      │ appended                                             │ no                        │ V2, contract │
│     │                                   │                                                               │                                                      │                           │  tests       │
├─────┼───────────────────────────────────┼───────────────────────────────────────────────────────────────┼──────────────────────────────────────────────────────┼───────────────────────────┼──────────────┤
│ 2   │ New command, unstorable date      │ double: stored, and the Member becomes unreadable on          │ both return "not storable" before the version        │ yes, intended             │ V2, V7, V11  │
│     │                                   │ PG-equivalent read-back. PG: exception, rollback expected     │ update; the application refuses without retrying     │                           │              │
├─────┼───────────────────────────────────┼───────────────────────────────────────────────────────────────┼──────────────────────────────────────────────────────┼───────────────────────────┼──────────────┤
│ 3   │ Key reused for a different        │ application: key conflict at the pre-check, so append is      │ unchanged on both paths                              │ no (the earlier plan      │ V1, V2, F-3  │
│     │ command, unstorable date          │ never called. Repository: key conflict                        │                                                      │ would have changed it)    │ clause       │
├─────┼───────────────────────────────────┼───────────────────────────────────────────────────────────────┼──────────────────────────────────────────────────────┼───────────────────────────┼──────────────┤
│ 4   │ Retry of an already-applied       │ the original result                                           │ unchanged. A matching fingerprint means matching     │ no                        │ V1, V4       │
│     │ command                           │                                                               │ dates (V4), and those dates were already stored      │                           │              │
├─────┼───────────────────────────────────┼───────────────────────────────────────────────────────────────┼──────────────────────────────────────────────────────┼───────────────────────────┼──────────────┤
│ 5a  │ Stale version, storable date      │ conflict → re-decide (at most 3 attempts)                     │ unchanged                                            │ no                        │ V2, V7       │
├─────┼───────────────────────────────────┼───────────────────────────────────────────────────────────────┼──────────────────────────────────────────────────────┼───────────────────────────┼──────────────┤
│ 5b  │ Stale version, unstorable date    │ PG: conflict, retry, then an exception at encoding. Double:   │ "not storable" at once, no retry. The dates come     │ yes                       │ V2, handlers │
│     │                                   │ conflict, then stored                                         │ from the command, so re-deciding cannot fix them     │                           │              │
├─────┼───────────────────────────────────┼───────────────────────────────────────────────────────────────┼──────────────────────────────────────────────────────┼───────────────────────────┼──────────────┤
│ 6   │ Not authorised, or refused by the │ the application refuses before append                         │ unchanged                                            │ no                        │ V1           │
│     │  domain                           │                                                               │                                                      │                           │              │
├─────┼───────────────────────────────────┼───────────────────────────────────────────────────────────────┼──────────────────────────────────────────────────────┼───────────────────────────┼──────────────┤
│ 7   │ Member missing and unstorable     │ PG: exception during encoding? No: the version update finds   │ PG: "not storable". Double: "member not found" (the  │ divergence                │ V3           │
│     │ date (repository level)           │ no row → "member not found". Double: "member not found"       │ existing F-3 gap)                                    │                           │              │
└─────┴───────────────────────────────────┴───────────────────────────────────────────────────────────────┴──────────────────────────────────────────────────────┴───────────────────────────┴──────────────┘

Recommended order: key → date → version → write.
- The key comes first because the contract says so.
- The date check must come before the version update, because that update is the first change to the database.
- The check reads nothing, so putting it after the key lookup costs nothing.

Ambiguity to flag:
- Case 5b: the caller gets "not storable" where re-deciding might have produced a domain refusal. I consider that acceptable, because "not storable" is a property of the command itself, but ChatGPT should challenge it.
- Case 7: the E tests deliberately do not pin this case. F-3 will reorder the double later; E does not widen the gap.

4. Where the storable range should live

┌─────────────────────────────────────────────────────────────────────────────────┬────────────────────────────────────────────────────────────────────────────────────────────────────────────┬─────────────┐
│                                     Option                                      │                                                 Assessment                                                 │             │
├─────────────────────────────────────────────────────────────────────────────────┼────────────────────────────────────────────────────────────────────────────────────────────────────────────┼─────────────┤
│ (a) A small framework-free class next to the repository port: two constants and │ the port owns what the repository promises to store. Infrastructure may depend on the application layer,   │ Selected    │
│  one pure check over a fact's valid-time values                                 │ and the application on the domain (Slice 1a/1b architecture tests)                                         │             │
├─────────────────────────────────────────────────────────────────────────────────┼────────────────────────────────────────────────────────────────────────────────────────────────────────────┼─────────────┤
│ (b) An existing shared persistence contract                                     │ none exists (V9)                                                                                           │ not         │
│                                                                                 │                                                                                                            │ available   │
├─────────────────────────────────────────────────────────────────────────────────┼────────────────────────────────────────────────────────────────────────────────────────────────────────────┼─────────────┤
│ (c) Infrastructure only (DateOrdinal)                                           │ the double would have to depend on infrastructure, and the application could not see the rule              │ rejected    │
├─────────────────────────────────────────────────────────────────────────────────┼────────────────────────────────────────────────────────────────────────────────────────────────────────────┼─────────────┤
│ (d) The domain (TimePoint or decision guards)                                   │ that is policy option B, or it breaks ∞                                                                    │ out of      │
│                                                                                 │                                                                                                            │ scope       │
└─────────────────────────────────────────────────────────────────────────────────┴────────────────────────────────────────────────────────────────────────────────────────────────────────────┴─────────────┘

Avoiding circular tests. Only the range definition is shared. The tests assert the boundaries as literal numbers (−719162, 2932896, ±1), never through the shared constants.

DateOrdinalTest stays an independent check: it converts literal dates to and from text. If the shared constants were wrong, both suites would fail.

DateOrdinal would use the shared constants and keep throwing as the last line of defence.

Unverified until the tests run: that the architecture tests accept the new class in the ports folder.

5. New result and exhaustive mapping

Proposed names (following V8, for your decision):
- AppendOutcome::ValidTimeNotStorable = 'valid_time_not_storable';
- ApplicationRefusal::ValidTimeNotStorable, with the same value;
- the class Ports\StorableValidTime.

Mapping. Replace the switch with a match that has no default:
- applied or already applied → appended;
- key conflict, member not found, not storable → the application refusal of the same name;
- concurrency conflict → retry.

PHP throws UnhandledMatchError for any unmapped case, so a missing case fails loudly instead of becoming ConcurrentModification. That error is a safety net, not proof of completeness; the completeness proof is test T4 below.

Unexpected infrastructure failures still propagate unchanged.

What the check covers:
- effectiveAt on Admit and End;
- a finite initialTermUntil on Admit.

An infinite or empty initialTermUntil is skipped. If a future fact carries a new date and is not added to this check, DateOrdinal still throws, so the gap would show as an error, never as corrupt data.

6. Test-first sequence

┌─────┬────────────────────────────────────────────────────────────────────────────────────────────┬───────────────────────────────┬──────────────────────────────┬───────────────────────────┬─────────────┐
│  #  │                                            Test                                            │          Requirement          │            Today             │           After           │  Evidence   │
├─────┼────────────────────────────────────────────────────────────────────────────────────────────┼───────────────────────────────┼──────────────────────────────┼───────────────────────────┼─────────────┤
│ T4  │ Application: for every AppendOutcome case, a stub repository returns it. Assert the        │ complete mapping; retry only  │ should pass (pins current    │ passes                    │ output,     │
│     │ expected result, and that only a conflict calls append more than once (up to 3)            │ on conflict                   │ behaviour)                   │                           │ exit code   │
├─────┼────────────────────────────────────────────────────────────────────────────────────────────┼───────────────────────────────┼──────────────────────────────┼───────────────────────────┼─────────────┤
│ T2  │ Contract: 0001-01-01 and 9999-12-31 for effectiveAt, finite initialTermUntil and End are   │ boundaries stay storable      │ should pass (pins current    │ passes                    │ same        │
│     │ stored and read back                                                                       │                               │ behaviour)                   │                           │             │
├─────┼────────────────────────────────────────────────────────────────────────────────────────────┼───────────────────────────────┼──────────────────────────────┼───────────────────────────┼─────────────┤
│     │ Contract: key reused for a different command, with an unstorable decision → key conflict,  │                               │                              │ passes. It would fail     │             │
│ T5  │ version unchanged                                                                          │ key precedes the date check   │ should pass                  │ under my earlier          │ same        │
│     │                                                                                            │                               │                              │ placement                 │             │
├─────┼────────────────────────────────────────────────────────────────────────────────────────────┼───────────────────────────────┼──────────────────────────────┼───────────────────────────┼─────────────┤
│ T6  │ Contract: same key and fingerprint, unstorable decision → original result                  │ key precedes the date check   │ should pass                  │ passes                    │ same        │
├─────┼────────────────────────────────────────────────────────────────────────────────────────────┼───────────────────────────────┼──────────────────────────────┼───────────────────────────┼─────────────┤
│     │ Contract: four cases (−719163 and 2932897 for effectiveAt; 2932897 for initialTermUntil    │ an explicit non-success       │ assertion failure on the     │                           │             │
│ T1  │ and for End) → 'valid_time_not_storable', compared by string. Version, log and key         │ result with no state change   │ double (it returns appended) │ passes                    │ same        │
│     │ unchanged                                                                                  │                               │                              │                           │             │
├─────┼────────────────────────────────────────────────────────────────────────────────────────────┼───────────────────────────────┼──────────────────────────────┼───────────────────────────┼─────────────┤
│ T7  │ Contract: stale version plus unstorable date → not storable                                │ the check runs before the     │ assertion failure (it        │ passes                    │ same        │
│     │                                                                                            │ version update                │ returns a conflict)          │                           │             │
├─────┼────────────────────────────────────────────────────────────────────────────────────────────┼───────────────────────────────┼──────────────────────────────┼───────────────────────────┼─────────────┤
│ T3  │ Handler, using the double: AdmitMember with 2932897 → refusal value                        │ correct classification        │ assertion failure (returns   │ passes                    │ same        │
│     │ 'valid_time_not_storable', append called once, no domain refusal                           │                               │ appended)                    │                           │             │
└─────┴────────────────────────────────────────────────────────────────────────────────────────────┴───────────────────────────────┴──────────────────────────────┴───────────────────────────┴─────────────┘

Order of work:
1. Write T4, T2, T5 and T6 and record them passing.
2. Write T1, T7 and T3 and record them failing.
3. Add the range class, the new result, and the checks in the double, PostgreSQL and DateOrdinal.
4. T1, T2, T5, T6 and T7 pass. T4 now fails for a real reason: the new result is unmapped, so it ends as ConcurrentModification after 3 calls to append. Record that.
5. Add the new refusal and the match; all tests pass.
6. Run the regression suites: DateOrdinalTest, Domain (89 tests, including temporal algebra and ∞), Application (34 plus the new ones, including concurrency and idempotency), Architecture (12).

Nothing will be reported as passing without its output and exit code.

7. Acceptance criteria

- Every recorded failure is a genuine assertion failure, not a crash. Every final exit code is 0, with output, env.txt and SHA-256 sums kept.
- TimePoint, the domain decisions and the domain tests are unchanged.
- The contract amendment and the developer guide are committed together with the code.

8. PostgreSQL boundary

Until Part 2 provides evidence, I cannot claim any of these:
- the adapter returns the new result;
- no version update, fact row or key row is written;
- the key precedes the date check on PostgreSQL;
- the DateOrdinal fallback path rolls back.

Part 2 keeps every existing gate: an isolated database, accepted provisioning evidence, the rule that the test role cannot connect to nrna_test (I-1), and your separate authorisation.

9. Risks, assumptions, non-goals

Risks:
- the architecture tests might reject the new class;
- case 5b (losing the domain refusal);
- the F-3 gap stays open (case 7).

Non-goals:
- options B, C and D;
- D-5(b);
- BD-NORM: 9999-12-31 stays storable and is never reinterpreted as "no end";
- F-2 to F-5;
- PostgreSQL execution.

10. Your decisions (all blank)

┌─────┬────────────────────────────────────────────────────────────────────────────────────────────────────────┬───────────────────────────┬─────────┐
│  #  │                                                Decision                                                │          Options          │ Verdict │
├─────┼────────────────────────────────────────────────────────────────────────────────────────────────────────┼───────────────────────────┼─────────┤
│ 1   │ Option E architecture: placement (key → date → version), new class in the ports folder, match mapping  │ approve / revise / reject │ [ ]     │
├─────┼────────────────────────────────────────────────────────────────────────────────────────────────────────┼───────────────────────────┼─────────┤
│ 2   │ Contract amendment: one new result in Slice 1a §3 and §5; Slice 1b line 48 and the line-132 step order │ approve / reject          │ [ ]     │
├─────┼────────────────────────────────────────────────────────────────────────────────────────────────────────┼───────────────────────────┼─────────┤
│ 3   │ Names: ValidTimeNotStorable, StorableValidTime                                                         │ approve / rename          │ [ ]     │
├─────┼────────────────────────────────────────────────────────────────────────────────────────────────────────┼───────────────────────────┼─────────┤
│ 4   │ Case 5b: "not storable" takes precedence over re-deciding                                              │ accept / revise           │ [ ]     │
├─────┼────────────────────────────────────────────────────────────────────────────────────────────────────────┼───────────────────────────┼─────────┤
│ 5   │ Business date rule (option B, C, D or none), under a new identifier, not D-5(b)                        │ —                         │ [ ]     │
├─────┼────────────────────────────────────────────────────────────────────────────────────────────────────────┼───────────────────────────┼─────────┤
│ 6   │ Authorise this increment (no database)                                                                 │ yes / no                  │ [ ]     │
└─────┴────────────────────────────────────────────────────────────────────────────────────────────────────────┴───────────────────────────┴─────────┘

11. Exact scope

Production:
- Ports/StorableValidTime.php (new);
- Ports/AppendOutcome.php;
- ApplicationRefusal.php;
- DecideAndAppend.php;
- PostgresMemberRepository.php (the check after answerFromKey, before the version update);
- DateOrdinal.php.

Tests:
- tests/Doubles/InMemoryMemberRepository.php;
- MemberRepositoryContract.php (T1, T2, T5, T6, T7);
- MembershipCommandHandlersTest.php (T3);
- a new mapping test (T4).

Docs:
- the amendment lines in the two contracts;
- a developer guide step, plus its 00_index.md entry.

Commits: failing tests → fix → documents and evidence.

12. For ChatGPT's review

1. Does the placement (key → date → version) satisfy the F-3 clause, IDEM-1 to IDEM-3, and "before any write"?
2. Is case 5b acceptable?
3. Is leaving case 7 to F-3 right?
4. Does the ports-folder class fit the repository's architecture?
5. Are the tests non-circular, with literal boundaries and DateOrdinalTest as an independent check?
6. Does T4 prove the mapping is complete, and that only conflicts retry?
7. Are the red results genuine assertion failures?
8. Is the scope of the contract amendment complete?

I've stopped here, waiting for ChatGPT's review, your architecture approval, and explicit authorisation to implement.
Stop says: Project-state sync (CLAUDE.md):
  x CONTEXT.md not updated today - refresh active ticket / next action if they changed
  o no plan file touched today (fine for small fixes; update the active plan for ticket work)
  (non-blocking; modified files: .claude/runtime/2026-10-10-files.log)
#
###
# PublicDigit Membership — Principal Review of the Revised Option E Plan

The latest report is a meaningful improvement. Claude has corrected its earlier recommendation after discovering that the existing Slice 1a contract explicitly requires the idempotency-key lookup to precede every other check. It also recognizes that introducing a new append outcome requires an amendment to the existing Slice 1a and Slice 1b contracts. These are important corrections.

My verdict: approve the technical direction conditionally, but revise the plan before implementation. The main architecture is defensible. Two unresolved behavioural questions and one test-plan defect still need to be addressed.

I am basing this review on the attached report. I have not independently inspected the live repository or run its tests in this turn.

## 1. What I approve in principle

The ordering: idempotency key → date check → version check → writes

This follows the existing contract as described in the report and places the new representability check before the first database mutation. It is preferable to the earlier proposal that checked the date before idempotency.

A persistence contract shared by the adapter and test double

A small, framework-free definition near the repository port is a reasonable choice if the architecture tests confirm the dependency direction. It avoids changing the domain's general temporal abstraction.

An explicit technical outcome

`ValidTimeNotStorable` is a plausible name. The result should be mapped to an application-level refusal, not confused with a domain refusal or a concurrency conflict.

Separate contract amendments

Because the report says the existing contracts prohibit new outcomes and specify append ordering, the change must update those contracts explicitly. Code must not silently override an approved contract.

## 2. Three issues to resolve

### Issue 1 — A stale version and an invalid date

Claude identifies this case:

- A command contains a date outside the representable range.
- The member version is stale.
- The current production path may detect the version conflict first, retry, and only later fail during date conversion.

The revised plan proposes returning `ValidTimeNotStorable` immediately after the idempotency lookup, before the version check.

That is technically coherent, but it changes the outcome from a concurrency conflict to a storage-contract refusal. Claude argues that the date is a property of the command and re-deciding cannot fix it.

My recommendation: accept this behaviour only if the contract explicitly says that representability is a deterministic precondition for appending after idempotency recognition, and that it takes precedence over version comparison. Add a decision-table test proving the intended result. Do not leave this as an undocumented side effect of code ordering.

### Issue 2 — A missing member and an invalid date

The report identifies a known discrepancy: the PostgreSQL adapter and the test double do not currently have identical check ordering for a missing member and an invalid date. This is associated with the already identified PBDIGIT-264 F-3 parity gap.

Claude proposes leaving that gap for the separate F-3 work rather than expanding Option E.

I support keeping increments small, provided the scope is explicit:

- Option E must not claim that the adapters are fully behaviourally equivalent.
- The known missing-member ordering discrepancy must remain recorded as open.
- The acceptance criteria must state exactly which behaviours Option E makes consistent.
- The later F-3 increment must test and resolve the remaining precedence difference.

The shared date-storage contract and the full repository contract are not the same thing. Fixing one does not prove the other is complete.

### Issue 3 — The proposed test-first sequence is internally inconsistent

The report's sequence says that T4, T2, T5, and T6 are written and recorded as passing, then T1, T7, and T3 are written and recorded as failing. After the implementation, it expects T4 to fail because the new outcome is unmapped.

This sequence needs refinement. T4 is a mapping-coverage test, and the implementation of the new outcome can change its result. A test should not be described as a genuine regression or as passing before the corresponding code state has actually been exercised.

Require Claude to specify the exact red-green sequence, with a real command and recorded exit code for each stage. It should distinguish:

1. Baseline tests that demonstrate existing behaviour.
2. New tests that fail because the missing capability is not implemented.
3. Tests that fail because the new outcome has not yet been mapped.
4. The final regression run after the complete implementation.

No test result should be predicted as evidence; only actual execution can establish it.

## 3. Prompt for Claude — final plan revision

The next step should be a narrow revision, not another broad investigation. Send Claude the following instructions.

# PublicDigit Membership — Final Option E Plan Revision and Architecture Gate

## Mission

Revise the current Option E implementation plan in response to the latest independent Principal Review.

Your objective is to produce an implementation-ready design for the persistence representability contract, with explicit requirements, correct idempotency semantics, non-circular tests, and traceability to the approved Membership architecture.

## Roles and authority

**Claude:** investigate, revise the plan, and later implement only the approved scope.

**ChatGPT:** independently challenge the revised plan, its assumptions, test design, failure semantics, and architecture.

**Human PO/ARB:** approve or revise the final architecture, authorize contract amendments, and explicitly authorize implementation.

No implementation is authorized by this request.

## Phase 1 — Plan only

Read the existing contract documents, relevant source code, tests, and Option E report. Verify the current repository state before relying on earlier observations.

Do not edit files, run tests or scripts, access databases, stage, commit, push, or modify project-state files.

## 1. Preserve idempotency semantics

The existing Slice 1a contract reportedly requires the idempotency-key lookup to precede every other check.

Preserve this requirement.

Specify and justify the append ordering:

1. Idempotency-key recognition and payload-conflict handling.
2. Valid-time representability check for a genuinely new command.
3. Version comparison and concurrency handling.
4. State-changing writes.

Verify the exact ordering against the current production adapter, test double, application pre-check, and existing idempotency tests.

Prepare a decision table covering:

- New command with a supported date.
- New command with an unsupported date.
- Reused key with a conflicting payload and unsupported date.
- Retry of an already-applied command.
- Stale version with a supported date.
- Stale version with an unsupported date.
- Missing member with an unsupported date.
- Unauthorized or domain-refused command.

For each case, document existing behaviour, intended behaviour, whether behaviour changes, and the supporting contract.

The intended result for a stale version plus an unsupported date must be explicitly specified. If the design makes the storage-contract refusal take precedence over the concurrency conflict, document this as a deliberate repository-contract rule, not an incidental implementation detail.

Do not silently resolve any ambiguity in the existing idempotency contract.

## 2. Define the exact scope of Option E

Option E should make the date-representability contract explicit and consistent between the PostgreSQL adapter and the in-memory test double.

It must not:

- Add calendar bounds to TimePoint.
- Change domain Membership rules.
- Decide business plausibility or organizational founding-date policy.
- Reinterpret 9999-12-31 as infinity.
- Claim complete adapter parity while PBDIGIT-264 F-3 remains unresolved.

Record the missing-member precedence discrepancy as an explicit dependency or known limitation if it remains outside this increment.

## 3. Revise the repository contract

Verify whether the proposed StorableValidTime class belongs beside the repository port under Application/Organisational/Ports.

Inspect architecture rules and current dependency direction.

Specify:

- The exact supported inclusive range.
- How Admit effectiveAt, finite initialTermUntil, and End effectiveAt are checked.
- How infinity and absent finite end values are handled.
- The new AppendOutcome and ApplicationRefusal values.
- How the PostgreSQL adapter checks after idempotency recognition and before the first state mutation.
- How the test double honours the same contract.
- How DateOrdinal remains a final conversion safeguard.

Use the existing naming conventions. Do not introduce unnecessary abstractions.

## 4. Make result handling exhaustive

Review DecideAndAppend and its existing retry logic.

Specify a complete mapping for every AppendOutcome:

- Successful application and already-applied results.
- Idempotency conflict.
- Missing member.
- Valid time not storable.
- Concurrency conflict.

Only the intended concurrency result may trigger retries.

A future unmapped outcome must never silently become ConcurrentModification.

If PHP match is used without a default, document the fail-fast behaviour and test it. Do not treat an exception on an unknown result as a substitute for testing every currently defined enum case.

## 5. Correct the test-first sequence

Produce a stage-by-stage test plan that distinguishes:

**Stage A — Baseline**

- Identify existing tests and capture their actual baseline results where execution is authorized in the future.
- Do not execute them in this planning phase.

**Stage B — New contract tests**

- Unsupported dates return the intended explicit result.
- Supported boundaries remain storable.
- Repository state is unchanged after rejection.
- Idempotency-key behaviour remains unchanged.
- Stale version plus unsupported date has the specified outcome.
- The test double and PostgreSQL adapter are checked against the intended contract.

**Stage C — Application mapping**

- Every AppendOutcome maps to the expected application result.
- Only concurrency conflicts retry.
- The new result does not become a misleading ConcurrentModification.

**Stage D — Implementation**

- Apply the approved minimal change.
- Run the authorized tests and record actual output and exit codes.

**Stage E — Regression**

- Run the authorized domain, application, temporal-algebra, concurrency, idempotency, and architecture suites.
- Keep PostgreSQL integration behind the separately authorized Part 2 safety procedure.

For every test, specify the requirement, test input, expected result, relevant code path, and required evidence.

Do not state that any test has passed until it has actually run.

## 6. Contract amendments and acceptance gates

Identify every affected section in the Slice 1a and Slice 1b contracts.

The amendments must document:

- The new outcome.
- The exact idempotency/date/version ordering.
- The behaviour for stale versions and unsupported dates.
- The scope and limitations of adapter parity.
- The required tests and acceptance evidence.

Do not edit the documents during this phase. Present proposed amendments for review.

## 7. Required deliverable

Return:

1. Verified source and contract references.
2. The decision table for all precedence cases.
3. The final proposed append ordering and its rationale.
4. The repository contract design.
5. Exhaustive result-mapping requirements.
6. The corrected red-green test sequence.
7. The exact contract sections that need amendment.
8. Acceptance criteria and evidence requirements.
9. Open risks and dependencies, including F-3 and PostgreSQL Part 2.
10. A Human PO/ARB decision sheet with all verdicts blank.
11. A minimal proposed implementation scope.
12. A checklist for ChatGPT's independent review.

Label statements as Observed, Inferred, Recommendation, or Decision Required.

## Stop condition

Stop after returning the revised plan. Do not edit files, execute tests, access databases, or commit.

Wait for ChatGPT's review, any necessary plan revisions, Human PO/ARB architecture approval, and a separate explicit implementation authorization.

## 4. How far are we from the Membership implementation goal?

The project remains in the domain-core and foundational implementation stage, not yet at a complete operational Membership product.

| Area                                   | Current position from the available reports                                      |
| -------------------------------------- | -------------------------------------------------------------------------------- |
| Membership domain model and core rules | Built and tested to a meaningful degree                                          |
| Slice 0 and Slice 1a                   | Implemented; formal acceptance verdicts still need recording                     |
| Slice 1b PostgreSQL adapter            | Implemented, but integration evidence and final disposition remain pending       |
| C-1 Annul/Void                         | Implemented; residual risks and formal gate verdict remain                       |
| Option E                               | Revised plan available; implementation not authorized                            |
| Slices 2–15                            | Mostly remaining, including commands, workflows, integrations, migration, and UI |
| Production readiness                   | Not established                                                                  |

The latest project inventory estimates 4 of 17 planned slices implemented in whole or in part. That is not a reliable percentage of total completion. A meaningful progress measure must account for tested requirements, accepted invariants, working user journeys, integrations, migration readiness, and release gates.

## 5. Remaining todos

### Immediate architecture and governance

- Obtain Claude's final Option E plan revision.
- Resolve the precedence of unsupported dates versus stale versions.
- Explicitly record the known F-3 adapter parity limitation.
- Review the proposed amendments to the Slice 1a and Slice 1b contracts.
- Complete the independent ChatGPT review.
- Obtain Human PO/ARB architecture approval and explicit implementation authorization.
- Record the formal G3, Slice 0, and Slice 1a verdicts when their acceptance evidence is sufficient.

### Technical correctness

- Implement the approved Option E change test-first.
- Prove exhaustive append-outcome mapping and correct retry behaviour.
- Preserve idempotency semantics and ensure rejected operations leave state unchanged.
- Run the authorized regression suites and preserve actual test output and exit codes.
- Complete the isolated PostgreSQL safety and provisioning requirements.
- Verify PostgreSQL behaviour, rollback, and version consistency in Part 2.
- Complete PBDIGIT-264 F-2 through F-5 and reassess Slice 1b.
- Triage wider-suite failures and PBDIGIT-266; decide the disposition of optional PBDIGIT-267/269.

### Domain and application implementation

- Finalize Slice 2 decisions and command requirements.
- Complete Slices 2, 3, and 8 in dependency order.
- Implement correction semantics C-2, C-3, and C-4.
- Complete application queries and operational workflows.
- Complete the necessary integration work across Slices 9–12.
- Preserve the distinction between Membership and voting entitlement.

### Migration and delivery

- Approve the Slice 13 migration contract.
- Investigate legacy open-ended placeholders and ambiguous historical values.
- Produce a dry-run migration report and require human decisions for ambiguous records.
- Protect period-overlap and re-entry invariants in migration tests.
- Implement Slice 14 UI and operational workflows.
- Complete Slice 15, full regression, security review, acceptance evidence, and release readiness.
- Synchronize project-state documents through an authorized step.

### Research and theory

- Maintain traceability from research findings to requirements, approved decisions, invariants, tests, and code.
- Use ML-based clustering or contradiction detection only where it helps identify specific research gaps.
- Validate ML findings against source documents and human-approved decisions.
- Turn unresolved business questions into explicit decision requests instead of allowing assumptions to become implementation rules.

Next action: send Claude the final revision prompt above. Do not authorize implementation yet. Once Claude returns the revised plan, the next review should focus on the idempotency contract, the stale-version case, the explicit F-3 limitation, and the validity of the proposed test sequence.