# KOS-SESSION-DISCOVERY-001 — Governance Review of the Implementation Boundary

**Type:** Governance review (Session 2) · **Date:** 2026-08-14 · **Reviewed:** the boundary document in the repository (not its description) against the approved architecture and the binding human decision
**⛔ Review only. No grant issued · no implementation authorized · no finding repaired · architecture not redesigned. FINAL STATE: IMPLEMENTATION NOT AUTHORIZED.**

---

## Findings against the twelve review points

| # | Point | Verdict |
|---|---|---|
| 1 | Scope contains only what the architecture authorized | **VERIFIED** — §1 F-1…F-4 map to architecture §R (script · tests · one registry entry · bookkeeping); §1 closes with "Nothing else" and names what is *not* added |
| 2 | Capability = resolver + tests + registry entry; logs/CONTEXT are bookkeeping only | **VERIFIED** — §5a states it explicitly and offers to strike F-4 entirely without affecting the capability; the continuity problem is expressly left with its own track (no scope creep) |
| 3 | **Authoritative interpretation preserved** | **VERIFIED — and structurally, not by promise.** §3: state is obtained *exclusively* by invoking the qualified `workflow-state.php` (`fold`/`identity`) as a subprocess; **no fold loop, no transition interpretation, no state-derivation branch.** "Divergence is structurally impossible — there is nothing to diverge." **Governance corroborated independently:** the delegated commands are write-free (`loadRecord` reads only; every `saveRecord` call sits behind `init`/`append`/`grant`, never behind `fold`/`identity`). *Observation: subprocess delegation is the stronger of the available shapes — a process boundary prevents the resolver from reaching internal functions, which `require`-style reuse would permit.* |
| 4 | Authorization boundary: reports, never decides | **VERIFIED** — the six facts surfaced separately and verbatim, the two unevaluable ones rendered `UNKNOWN`; no authorization decision anywhere; T-10 pins it; the unconditional caveat line is contract, not commentary |
| 5 | Read-only guarantee structural; T-11 genuinely demonstrates it | **VERIFIED** — §2 "write-freedom: structural, not conventional"; **T-11 asserts the record directory is byte-identical before and after every invocation on all four verdict paths including the corrupt-record path.** That is the correct test for the claim: it would fail on any write, including an incidental one inside the delegated subprocess |
| 6 | Stop-safety, four outcomes only | **VERIFIED** — T-7 (ambiguity: all candidates listed, **none chosen**) · T-8 (absent → UNRESOLVABLE; corrupt → UNRESOLVABLE with the fact in `reasons`) · no fifth verdict, no "best guess" |
| 7 | UNKNOWN rather than guessing | **VERIFIED** — T-10 |
| 8 | O-1/O-4 named as limitation, not invented as state | **VERIFIED** — `readOnlyParticipation: NOT EXPRESSIBLE …` names the gap; the architecture's forbidden move is not made |
| 9 | No accidental inclusions | **VERIFIED** — §6 excludes all twelve named categories plus D-1…D-6, each "unauthorized and undesigned here" |
| 10 | Tests prove the architecture, not merely exercise the code | **VERIFIED with one OBSERVATION** — T-1…T-12 map row-for-row to architecture §H/§I plus purity and exit codes. **Observation: no test pins the binding precedence rule itself.** T-11 proves the resolver does not *write*; nothing proves it does not *compute its own interpretation*. A test could assert the resolver's state values equal the qualified mechanism's `fold` output for the same record, and/or that the resolver cannot resolve when the mechanism is unavailable. **The PO's binding constraint deserves its own test** — recommended as **T-13**, not added by Governance |
| 11 | Anything that is really a new architectural decision | 🔴 **UNRESOLVED QUESTION — one item, surfaced rather than approved (see below)** |
| 12 | Final file set, capability vs bookkeeping distinguished | **VERIFIED** — 3 capability paths (`session-resolve.php` · contract test · one registry entry) + 2 bookkeeping paths, unambiguously separated; the earlier count confusion is resolved and the `--work-item=<id>` "defect" was a false positive on inspection |

## The one UNRESOLVED QUESTION — exit-code semantics diverge from the approved architecture

**Approved architecture (§N, verbatim):** *"Exit-code convention mirrors the mechanism (0 report / 64 usage / 65 refused)"* — i.e. **0 whenever a report was produced**; and **§H, verbatim:** *"AMBIGUOUS and UNASSIGNED are **successful resolutions**, not failures to suppress."*

**Boundary §2 proposes instead:** *"RESOLVED=0; UNASSIGNED/AMBIGUOUS = distinct non-zero; UNRESOLVABLE = STOP-shaped non-zero"* (pinned by T-12).

**Why this is architectural, not detail:** by convention non-zero means failure. Under the boundary's mapping, a caller or wrapper that treats non-zero as failure would **suppress the very answers the architecture calls successful** — and "no assignment exists" or "several candidates exist" are precisely the answers that must reach a human. It changes the capability's contract with its consumers. **Classified UNRESOLVED QUESTION; not repaired, not approved by Governance.** Either resolution is legitimate — but it is the approver's, and the two readings are materially different.

## Recommendation to the approver

**APPROVE with two amendments** (both small, neither redesign): ① settle the exit-code contract — Governance's recommendation is the architecture's own version (**0 whenever a report is produced**, verdict carried in the report, non-zero reserved for usage/refusal), since it preserves "successful resolutions are not failures"; ② add **T-13** pinning the precedence rule (resolver's state values equal the qualified mechanism's output / resolver cannot answer without it). Session 3's three open questions (script name · no developer-guide entry · Q-B already answered) need only confirmation.

**Traceability:** boundary proposal (this repository, reviewed in full) · approved architecture §§H/I/N/R/S (`696a4316`) · architecture approval + binding precedence rule (`85104024`) · stage grant `G-KOS-DISC-IMPL-BOUNDARY` (`97c3ee97`) · independent corroboration of read-path purity (`workflow-state.php` `loadRecord` vs `saveRecord` call sites).
