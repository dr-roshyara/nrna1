*Saved verbatim 2026-10-19 from the reviewer's step-2 hand-back. Harness: step 1 90 s; step 2 sent 17:01:28Z, received at this save.*

# REVIEW REPORT — C12

*Transition review of §6.6 (new), CON-0139 and CON-0140, with S0067–S0070 checked against §6.5 and §9.6. Before this step I re-read the saved inventory (STEP1-INVENTORY.md, header plus body; it matches what I handed back). Independence here means process independence (a separate session, blind for step 1), not personal independence.*

## 1. Assessment
- §6.6 quotes S0066 accurately: 18 anchors checked, 0 failed. It reads one uncited chat reply as evidence for kernel/application laws.
- The largest risk is CON-0139. As written, "never copies" is contradicted by the book's own §0.7 (an assembled working context that is later discarded) and §1.14.4 (derived representations). The part that survives is already §1.10 RC-9 plus §1.16.6 plus CON-0117.
- The header's claim that S0067–S0069 yield "no new claim" does not hold (see findings 3–6).
- No critical findings.

## 2. Findings (ranked)

| # | Sev | Type | Claim / topic | Source line | What is wrong or missing | Consequence | Correction |
|---|---|---|---|---|---|---|---|
| 1 | high | defect | CON-0139 "never copies it" | S0066 L5, L142 | Universal quantifier over "copy", with "copy" undefined. The book's own counterexamples: the §0.7 working context, a validated copy assembled and then discarded; §1.14.4 derived representations, rebuildable and with no standing; a pinned quotation (§1.16.6). S0066's stated harm is a *competing source of truth* (L142), not copying as such | The concept as stated is false inside the book | Restate: "no second **authoritative** home. A copy is admissible only if it is derived/non-authoritative, pinned, or discarded." Then test minimality against RC-9 (§1.10) and CON-0117. It is probably a merge, like CON-0133 |
| 2 | high | omission | S0066 sets "authority" inside KnowledgeOS | S0066 L286–287, L420 | The guide's top node is "KnowledgeOS / Governance", and the contract says KnowledgeOS "establish[es] engineering authority". Under A1 and P-8 a store confers nothing; S0067 L13–16 and S0069 L13 put the authority origin *outside the software*. §6.6 C3 cites the hierarchy as an instance of P-8 and misses that its top node contradicts P-8 | A homonym enters the book unmarked: authoritative = canonical home vs authority = standing from an act | Qualify S0066's term as "authority (canonical-home sense)". Record the tension with A1 and add it to the open split of the authority senses (outline, Authority row) |
| 3 | high | omission | RL breach is a practice, not an incident | S0067 L9; S0070 L7; S0071 L11 | S0067 L9 discloses that the producing terminal "registered the seq-3 START" in BASELINE-002. S0070 L7 states for BASELINE-003 that "START NOT performed … the Human START is required", yet S0071 L11 reports a self-written START in that same work item | §9.6.5 RL "by accepted practice" rests on n=1 when the sources give n=2, plus a stated rule broken in the same work item | Add S0067 L9 and S0070 L7 to §9.6.5 and §6.5 C2. Keep it as one line of support |
| 4 | high | omission | A one-way dependency direction as a kernel/application law | S0066 L85–99 ("Never reverse this relationship"); S0069 L52–54, L68 (Observed: no `app/` reference to `.claude/`) | §6.6 has the copy law but no direction law: applications depend on the kernel, never the reverse. S0069 gives a measured platform→product instance | A candidate boundary law for deliverable 3 is missing | Add it as Src plus Obs (S0069), with S0066 as direction only. Test it against §1.14.4's "upward edge" test |
| 5 | high | defect | C2 maps harnesses onto K3 and onto CE | S0066 L49, L265 | (a) Claude and Codex share one repository; per §6.5 C8, all lanes "share one worktree and one git identity". They are not independent applications in K3's sense, and git is their shared serialization point, so the case cannot bear on K4. (b) S0066 says nothing about evaluating standing, so "the evaluator CE … in a practical case" is a later reading confirming theory (it is not evidence) | False corroboration of F22's branch | Downgrade to "an analogy, untested". State that the case is a single serialization point |
| 6 | normal | omission | Capability Identity Invariant as an identity law | S0068 L10–12, L21, L34 | The book uses it only as a notation bias (§9.6.4). Its content is identifier→owner functional, with no reuse after retirement (CAP-04). That temporal uniqueness is absent from I-0 (§1.10) | I-0 lacks its time clause | Add to §1.10 as Src (one line; the CAP-07 pause is n=1) |
| 7 | normal | omission | Declared ≠ realized: the knowledge-governance context has no executable realization | S0068 L20, L26; S0067 L27 | CAP-03 Knowledge curation is "not built". The only executable governance is the record of authority. (Search for "CAP-03" and "not built": no hit; a miss is not absence.) | Bears on deliverable 5: what exists is the record, not curation | One line in §6.5 C6 or §9.1 |
| 8 | normal | defect | S0066 "undated" | §6.6 L4, L11; STEP8 L11 | `git log` first commit: `f24edf316` on 2026-08-28 ("knoweledge os modelling"), 11 days after S0067–S0070 (`f278dc54`, `b8769fa7`, both 2026-08-17). The commit date is an upper bound on when it was written. "Based on the architecture we have established" (L1) is then plausibly downstream of this programme | The lineage verdict that S0066 and S0070 are "not one line" is unexamined. C1's "second instance" (S0070 L35, citing ES-005.4) may share S0066's origin in the canon rule | Record the date as an upper bound. Count C1 as one line until shown otherwise |
| 9 | normal | omission | The basic distinction: agent execution vs engineering knowledge | S0066 L53–83 | Not stated as a definition, only in plain language. S0066 itself blurs it: governance, DDD and process content are "knowledge" (L72–81), yet the authority statement, the 10-step process and the stop conditions are written *into* each harness contract (L281–326) | The application/kernel line inside S0066 is not sharp, and the book does not say so | Define "harness" and "knowledge". Record S0066's internal tension |
| 10 | normal | defect | CON-0140 used as F13 input | S0066 L686–691 | Scope shift: the six questions concern agent *state artifacts* (CONTEXT/MEMORY), not knowledge items. "Safe for several writers" can be derived from the writers plus a serialization point (§1.16.7), so it is not minimal. Missing: identity, version, provenance, lifecycle. "Authoritative or derived" is unlinked to CON-0134 | Overreach | Keep it as a source list. Mark its relation to F13 as an analogy |
| 11 | normal | omission | Stop conditions: the refusal half without a must-do half | S0066 L602–640 | Not in §6.6. An agent that always stops satisfies all nine conditions (the trivial-solution test); only the cite/explain/ask protocol is a must-do | One instance of the §9.1 C2 pattern is missing | One line |
| 12 | normal | omission | "Governance" in two senses | S0067 L115–117 | Project Governance (an external domain, the origin of authority) vs the Governance role (an actor registering acts through BC-7). §1.10's table lists three senses of "governance", but not this split | Homonym | Add it to §1.10's table |

**Source defects (listed separately; none is load-bearing):**
- S0067's invariant table skips I-9 (L59–69).
- S0070 L35 lists BC-7's invariants as "I-1…I-3/I-10", omitting I-4, which S0067 L64 calls "the context's central structural rule".
- S0070 L25 adds "lineage" to the knowledge side of the seam: drift from S0067's "adjudicated identity".

## 3. Concept verdicts

| Concept | Verdict | Reason · scope |
|---|---|---|
| CON-0139 linked, not copied | **INVALID as stated** | "Never copies" is defeated by §0.7 and §1.14.4. The restricted form (no second authoritative home) is PROVISIONAL and probably not minimal over RC-9 + CON-0117 |
| CON-0140 six questions | **PROVISIONAL** | Accurate as a quoted list. Its scope (state artifact vs knowledge item), minimality and completeness are not shown |
| §6.6 C3 (implementation confers no standing) | **VALIDATED**, narrow | A one-step instance of A1/P-8. The linear order stays Hyp, correctly |
| §6.6 C4 (four states) | **PROVISIONAL** | "Closure is an act" is supported by S0067 L93 (COMPLETE as a governance act), which is not cited. The states are unordered: "code exists ≠ authorized" means implementation can precede authorization |
| §6.6 C6, C7 | **VALIDATED** as source reports | Book caveats correct. S0066's rules carry no adopting act (A1: no standing), which the book should say once |

## 4. The Principal's five questions, for §6.6

| Question | Answer |
|---|---|
| Precise definitions | No: "harness", "link" and "copy" are undefined |
| Derivation | C1 is a sketch; it is not a proof (the premise that a copy is used as authoritative is hidden). C3 is a valid single step |
| Explicit assumptions | Missing for C1 (copy = authoritative) and C2 (independence of harnesses) |
| Refutable | Yes; C1 is refuted (finding 1) |
| Decision/authority recorded | The Principal's act is cited correctly for K3. S0066 has no adopting act, and this is not noted |

**Generalisation rule:** C1 and C2 each rest on one line of support (finding 8). "No counts" is correct.

**Homonyms:**
- "authority/authoritative" (finding 2);
- "truth": S0066's single "engineering truth" vs §6.5 C7's two truth disciplines;
- "application": K3 kernel application vs agent adapter;
- "governance" (finding 12).

## 5. Next steps, in dependency order
1. Restate or merge CON-0139 (finding 1). Done when the restricted form survives the §0.7, §1.14.4 and §1.16.6 cases.
2. Add the S0067 L9 and S0070 L7 evidence to §9.6.5 and §6.5 C2 (finding 3). Done when RL rests on n=2 with its lineage stated.
3. Downgrade C2's K3/CE reading (finding 5).
4. Add the direction law (finding 4) and its test against §1.14.4.
5. Record the authority homonym (finding 2) in the Authority row.
6. Make the lineage and date correction (finding 8). Then take findings 6, 7 and 9–12 at the next consolidation.

## 6. Limits
- I did not open THEORY-MAP or F13's text, or §3.8/§3.9 beyond the outline. CON-0092/0093 were taken from the outline.
- Searches were lexical (`book_search.py`); misses are not absence.
- The git date bounds when S0066 was written; it does not fix it.
- I did not verify ES-005.4's wording, since that file is outside the allowed set.

---
## Appendix (lower priority)
- §6.6 C1 says "a link alone is a live reference". A link can be pinned (commit or version). It is S0066's *path* pointer that is live.
- S0066's DDD order (L338–358) and "start with the responsibility, not the class" (L362) are application methodology. Their exclusion from the kernel is justifiable, but it should be marked.
- S0068's realization vocabulary (live / partial / convention / by reference / not built / retired) is an implicit maturity order. It may be relevant to §1.9's realization ladder.
- S0070 L35's claim→evidence→assessment chain "may relate" to Human Act: a possible shared primitive (evidence/assessment) not in the book. It should be an open question, not a claim.
- Evidence for my statements: `git log --format='%h %ad %s' --date=short -- <file>` on S0066, S0067 and S0070. `python3 -I tmp/tools/book_search.py` for "Capability Identity Invariant", "CAP-03", "not built", "Project Governance", "never reverse", "one-way", "registered the seq-3 START", "claim/evidence/assessment" and "agent execution". Quotes were checked by reading S0066 L1–918 and S0070 L1–41 whole.
