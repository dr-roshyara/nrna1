# Release message — Critical Attack Pass 01 (L0-DEC-30)

| | |
|---|---|
| **Kind** | ⭐ **the release message handed to the research session**, recorded on L0 instruction (*"commit the release message as a governance record"*). ⛔ It is not the decision. **The decision is L0-DEC-30** in `governance/L0-DECISION-RECORD-01.md`. **Where the two differ, the decision record wins** |
| **Written by** | the governance/control-plane session, 2026-09-23 |
| **Basis** | `audits/2026-09-23-RESEARCH-RELEASE-CHECK-01.md` (YELLOW, `e06870174`) · L0-DEC-27 · L0-DEC-29 · L0-DEC-30 (`487a5c72b`) |
| **Delivery** | ⚠️ **Point the research session at this file** rather than pasting it. Earlier pasted commissions arrived truncated or garbled |

---

## RELEASE — Critical Attack Pass 01 (L0-DEC-30)

**Research is released under 🟡 YELLOW, for a bounded scope.** The authoritative text is in `docs/knowledgeos/knowledgeos_theory_chronological_extraction/governance/L0-DECISION-RECORD-01.md`, section **L0-DEC-30**. Read it from the file. If this message and the file differ, **the file wins**.

Background: `governance/audits/2026-09-23-RESEARCH-RELEASE-CHECK-01.md` (YELLOW, commit `e06870174`).

### 1 · Entry: release revalidation first
Before any research work:
1. Read L0-DEC-27, L0-DEC-29 and L0-DEC-30 in the decision record.
2. Confirm HEAD. The release was recorded at or after `487a5c72b`.
3. Run `.claude/hooks/governance-preflight.sh`. **It must report CLEAR (exit 0).** If it reports anything else (BLOCK, GOVERNANCE_INOPERATIVE, or NO_ACTIVE_GOVERNANCE_CONTROLS), **do not start**. Report the output and stop.
4. Record the result of this revalidation at the head of your output.

### 2 · Authorized scope
- **Critical Attack Pass 01** on exactly the four HYPOTHESIS records **`[E]-01`, `[E]-02`, `[E]-03`, `[E]-04`** in `SENIOR-RESEARCHER-BASELINE-01.md`.
- Attack each one mathematically, statistically, logically, from a DDD standpoint and computationally, and look actively for counterexamples.
- Each one ends as **survives / weakened / reformulated / falsified**. Record that as **new** Phase 2 records carrying an origin label (`[E]` or `[T]`), an epistemic level (§1 ladder), provenance and falsification conditions, as the Step-2 protocol v3.3 requires.
- Then write a **Phase 2 readiness assessment**. It is an assessment only, and it grants no entry into Phase 2.
- **Then STOP** and report to L0.

### 3 · The limitation you must carry
`[E]-02` relies on **T-0056**, one of 12 Theory Objects (T-0047…T-0058) that have **no relation record**. You may use T-0056. Your `[E]-02` attack record **must state this limitation explicitly**.

### 4 · ⛔ Not authorized
- **No corpus reading.** Do not open, admit or take a receipt for any corpus file. Work only from material already recorded.
- **Nothing on F0031–F0040.** Do not read, cite or build on the nine RC-H-04 IDs. If the attack needs them, that work is **RED**: stop and escalate. Do not work around it.
- No Batch 4, no entry into Phase 2, no C-5 / RC-H-04, no 1b.
- **Append-only.** Do not edit existing Theory Object or registry rows, Candidate Theory v0.9, the protocols, or any governance file. That includes gates, activation, the runner and `admit.py`.

### 5 · Exit report
Report to L0:
- the entry revalidation result;
- one verdict per `[E]` item, with its new record IDs;
- the readiness assessment;
- any point where the scope boundary was reached or nearly crossed;
- the door result at the end;
- confirmation that nothing outside §2 was changed.

Commit with explicit paths only, and never `git add -A`.
