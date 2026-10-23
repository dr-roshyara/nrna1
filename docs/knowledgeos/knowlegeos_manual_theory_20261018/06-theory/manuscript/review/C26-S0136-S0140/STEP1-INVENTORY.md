*Window reviewer W (Opus), step 1 blind inventory, verbatim (saved by the Professor at 2026-10-23T18:22:27Z). sha256 of the hand-back text at receipt: `04d52c41dbc52ae1c0cb252e975f1c22dde27ea88df0f91f93dd67dbf677bd9d` (UTF-8, the text below this line exactly).*

C26 STEP 1: BLIND INVENTORY

**Disclosure.** My session came with context attached: the repo git status and recent commit subjects. One subject names "window C26 draft (S0136-S0140; §8.19, CON-0245-0247)", so I know a Professor draft exists, but I have not seen its content. My agent role prompt described a different review task (EKS/PKS). I followed this commission and the REVIEWER-INSTRUCTIONS instead.

What I read:
- REVIEWER-INSTRUCTIONS.md, in full.
- The five sources, each in full, after confirming that each is listed in CORPUS-ALLOWED.tsv (rows S0136–S0140).
- A targeted grep, for context only, of S0130, S0131, S0133, S0134 and S0135.

What I did not read:
- the migration plan (S0300);
- anything under W/06-theory, W/archive or W/tmp;
- read logs, START-TIMES files, chronological-read, batch inputs, sealed or hold-out files, or credentials.

I ran no git command on W and modified nothing. Process independence only: I am a fresh session with no part in the Professor's work.

---

## S0136: Migration-plan technical architecture review (bc1b47ef). Depth: FULL
It introduces the window's main new distinctions: density versus completeness, measured concurrent loss, unattestability, and two senses of "durable".

1. **Atomic write is not atomic read-modify-write.** `rename` makes each write atomic, but the load / compute `seq` / save sequence has no lock, so two concurrent appends take the same `seq` and the last one wins (L112–120).
2. **Density is not completeness.** A lost write leaves `seq` dense and monotonic. The clobbering writer reuses the lost writer's number, so "no record silently dropped" does not follow from dense, monotonic and superset (L141–149). [I] The claim being refuted, "dense ⇒ omission detectable", originates in S0131 L25 and L121, not only in the plan. S0136 attributes it to the plan alone (L149).
3. **Experiment.** 23 of 30 concurrent trials lost a transition, the losing process exited 0, and the survivors were dense. The loss was also reproduced on the grant path (L134–139). The doc then states "the measured loss rate is 77 %" for the migration window (L160).
4. **Provable versus unprovable claims.** "No record lost" cannot be proved. "No write occurred in the window" can, by a declared freeze plus a re-hash; the freeze is a governance act, not a lock (L163–173, L327–337).
5. **Unattestability.** The runtime corpus is gitignored and has no history, so 18/216/109 can never be re-derived. Acceptance should move from that literal to a frozen manifest (L312–323, L339).
6. **Decision between stores versus within one store.** CASE A/B decides divergence between two stores. Inside a single store, divergence detection is impossible, and a clobbered tail still looks like a strict extension (L161, L249).
7. **Grants have no identity key.** No grant carries `seq`. There are 110 grants with 109 unique `grantId`s: two distinct authority acts share one id across different records. Identity has to be position plus content (L257–273).
8. **The authority-transfer point is ambiguous.** The implementation design moves authority at the switch; the plan moves it at Phase 6. Rollback-as-stop becomes false after the writer switch (L61–80).
9. **Interpretation path versus write path.** An environment-named program is executed and handed the authority directory, so a read-only component carries a write path. A test can pin a file's purity, not a subprocess's (L186–199).
10. **Exit-code split.** A location-resolution failure means "refuse to report" (exit 65). An UNRESOLVABLE workflow state means "report" (exit 0) (L201–217).
11. **Two senses of "durable".** Attestability (versioned) versus crash durability (no `fsync`) (L302–304).
12. **Byte preservation depends on git attributes.** The `.gitattributes` setting `text=auto` governs the committed bytes, so the copy's guarantee depends on a file outside the plan's fence (L285–300).
13. **Discoverability inverts authority.** During migration the least authoritative copy is the most discoverable one (L48–49).
14. **Partial checks.** A partial verification manifest is a FAIL, not progress. A tmp remnant is quarantined, never migrated or deleted (L57, L104).

**Lineage.**
- Author: bc1b47ef, who declares independence from the plan author (1c8b041b) and the governance reviewer (b64828fe) (L5–8). This is self-declared and not attestable (L393).
- Code readings and measurements are its own. The ROUTED question (L365–377) is put to PO/ARB, and no act answers it.
- Its "✅" gate states (L400–404) are not backed by acceptance acts here.

**Defects.**
- (a) L68 calls the migration plan and the design "the two accepted artifacts", while L12 and L406 say plan acceptance is pending with PO/ARB. That is a contradiction about status.
- (b) L21 says the plan is "non-destructive under every interruption examined" and L88 calls Phase 7 "Safe". S0137 later finds that Phase 7 destroys a gap-window write (RC-4), which falsifies that judgement.
- (c) 77% is a rate under forced contention with n=30 in one environment. Applying it to the migration window (L160) overreaches. The grant-path trial count is not given (L139).
- (d) "provable by 18 hash comparisons" (L173) ignores that the migration's own records are in the hashed set. S0138 RD-10 later shows this.
- (e) The reviewer of the plan later authors the remedies (AMD3–5), so "independent reviewer" turns into "author" in the same chain.

## S0137: Independent AMD3 remedy review (9c908e70). Depth: FULL
New here: structural versus statistical evidence, a bounded claim that satisfies itself, and the destructive-act gap.

1. **Possibility versus rate.** The loss is shown structurally from the code (possibility), independent of the 23/30 rate (likelihood). A completeness claim has to survive possibility (L51–54, L299–300). The trial was not re-run (L462).
2. **A bounded claim can satisfy itself.** "No loss relative to the manifest and the observed inputs" cannot catch a write that is in neither. The withdrawn absolute claim would have caught it. The remedy is a final check, not restoring the absolute claim (L87–91).
3. **RC-4.** The last integrity check comes three phases before the irreversible removal (L78–91).
4. **What a declaratory control buys.** It makes a write detectable; it does not prevent it (L72, L326).
5. **Wrong paths materialize instead of failing.** The writer `mkdir`s its parent, so a mis-resolved path silently creates a new evidence location. Refusal must happen before `saveRecord` (L317–322).
6. **The same defect recurs inside its own remedy.** Phase 2b is circular with Phase 3: a phase consumes its own successor's output, the same class as CL-3 (L192–203).
7. **Override versus authoritative resolution.** Every pinned test drives `--dir` into a path outside the boundary, so a literal boundary check would refuse the whole suite (L247–254).
8. **Conflicting current definitions.** The enforcing form sits in the table while the note below says the reporting form is current (L270–276, L340).
9. **Append-only implies duplicate ids.** Because the writer appends only, a grant lifecycle change must reappear with the same `grantId`, so the "never key on grantId" rule is structurally required (L223–227) [author's inference].
10. **`-text` is not equivalent to `text eol=lf`.** Only `-text` makes byte preservation unconditional (L209).
11. **Key order.** Canonical re-encoding needs key ordering, but this fails safe: a false CASE B escalates rather than silently merging (L234).
12. **Order is not left to inference.** Writer-last within Phase 5; the resolver is delivered dormant (L145, L172).
13. **Review the remedy, not the presence of text.** Exhaustive search for reintroduced claims (L34, L58–68).

**Lineage.**
- Reviewer: 9c908e70, self-declared (L12–22).
- The CL-n items are bc1b47ef's.
- "AMD3 not registered; no -AMD3 grant" is verified by grep (L30). It reports a negative status, which is backed.
- "10 CLOSED" (L445) is the reviewer's own judgement, with no authority act behind it.

**Defects.**
- (a) L110 says "every figure in AMD3 is exact" while L112 argues the corpus grows. That is consistent only because nothing was registered that day, which the doc does not note.
- (b) It also accepts S0136's "18 hash comparisons" (L72, L326) without seeing that the migration's own appends break it (RD-10).
- (c) The CL-1 verdict "CLOSED" sits next to RC-4, a gap in the very property CL-1 concerns. Closing CL-1 while the completeness guard is incomplete is a scoping choice, not a demonstrated closure.

## S0138: Independent AMD4 review (870305e0). Depth: FULL
New here: freeze self-reference, a disposition split by authority epoch, and test coverage of the refusal branch. It repeats much of S0136/S0137.

1. **RD-10: the freeze refers to itself.** The migration's own governance acts are appends to the frozen corpus. So the "provably quiet" branch cannot be reached, and a real violation cannot be told apart from bookkeeping (L243–256).
2. **RD-3: one mismatch, two causes, opposite dispositions.** A pre-writer-switch write should be reconciled. A post-switch write would import non-authoritative bytes, and re-promotion is a governance act, not a merge (L119–130).
3. **What the final re-hash can and cannot catch.** It catches appends, new records and removals, using set-wise all-or-nothing semantics. A write between the final re-hash and the removal is irreducible without a lock: the window shrinks from three phases to one step (L108–118).
4. **RD-4: the only refusal path is untested.** Every pinned call is overridden, so the authoritative refusal branch (exit 65, refusal before `mkdir`) has no regression coverage (L82–84).
5. **RD-6: the reporting form is an adopted contract.** T-14 pins it as a contract tied to a grant, so the enforcing form would amend more than AST-016 (L96–101).
6. **RD-7: no phase removes a required marker.** The staging NON-AUTHORITATIVE marker is never retracted, so the authoritative store ends up labelled non-authoritative. Same shape as RC-1: an act that is specified but carried by no phase (L159).
7. **Markers versus records.** A marker is advisory; the acceptance demonstration should be the Phase-6 record (L160).
8. **RD-1 and RD-2.** Phase 5 has no slot for reconciliation. A one-step window of unmarked stale runtime remains (L141–142).
9. **Document integrity.** Duplicate §4.1/§4.2 identifiers, with live references bound to both senses. A stale "Phase 2b". A "six" heading over an eight-row table (L281–283).
10. **Readers before the writer.** Readers-first gives a briefly stale read; writer-first gives an invisible write (L138).

**Lineage.**
- Reviewer: 870305e0 (L15–24).
- RC items from 9c908e70; remedies from bc1b47ef.
- "No -AMD3/-AMD4 grant" is verified by grep (L35).
- Static analysis only; the suites were not run (L361).

**Defects.**
- (a) The header says "7 new residuals" (L7), but the body defines RD-1 to RD-7, RD-9 and RD-10: nine substantive items plus RD-8. That is the same count-versus-content defect class it charges as DI-3.
- (b) L118 says the final check "costs 18 hash comparisons". That assumes a fixed record count, which its own L114 (new records) and RD-10 contradict.
- (c) L352 names five must-fix items. Others (RD-1, RD-4, RD-9) are left with no stated gate owner.

## S0139: AMD4 summary (bc1b47ef). Depth: SKIM
An author's disposition table. It mostly repeats S0137 and S0138. What it adds:

1. **Provenance bound.** Remedies written by the same process are ADDRESSED, never CLOSED. "The exposure changed shape rather than disappearing"; the bound recurs until an amendment adds no new remedy (L64–74).
2. **The RC-4 lesson.** The irreversible act's guard slipped through because the bounded claim satisfies itself (L51–60), restating S0137.
3. **Registration is not production.** Registration has one writer, so self-registration would manufacture authority (L99).

**Lineage.**
- Producer: bc1b47ef (L6).
- The file was edited after delivery by AMD5: a "DI-1 correction" stamp and repointed references (L11). The text read is therefore the post-AMD5 version, not the AMD4-time artifact.

**Defects.**
- (a) L20 says "AMD4 self-closure NONE", but L44–45 say "CL-5 … closed via RC-6" and "CL-6 closed via …". That is self-closure language.
- (b) L39 says "Six prerequisites", the count S0138 DI-3 refutes. AMD5 corrected the plan but not this line.

## S0140: AMD5 summary (bc1b47ef). Depth: FULL (§2), SKIM (rest)

1. **The freeze partitions writes instead of prohibiting them.** The test changes from "did anything change?" to "did anything change that we did not declare?". Writes are split into unrelated (violation) and declared migration-lane (expected delta) (L61–74).
2. **CASE α/β.** Pre-switch writes are reconciled. Post-switch writes are quarantined, recorded and escalated, never imported. If the ordering is unknown, the write is treated as β (L47–55).
3. **Preserving evidence without promoting it** is compliant with the no-silent-loss and no-silent-winner rules (L54).
4. **Marker lifecycle.** Write, demote, retract, remove. A marker reports state and never confers it (L31–41).
5. **Renumbering policy.** New sections are moved and originals kept, to protect delivered citations (L92).

**Lineage.** Producer: bc1b47ef. The findings are 870305e0's. "No -AMD3/4/5 grant" is claimed as verified (L133). The cross-cutting check (L118) was run by the author on its own remedies.

**Defects.**
- (a) L13 says it addresses "ONLY the six residuals the AMD4 review raised". S0138 raised more. RD-1, RD-2, RD-4, RD-5, RD-6 and RD-9 are neither addressed nor listed as open in §5 (L128–137).
- (b) [I] The α/β tie-break needs the order of the write relative to the switch. S0136 L258 measured that only 2 of 216 transitions carry any time field, so α is effectively unreachable. Then a genuinely authoritative pre-switch write is withheld from the authoritative store. That is conservative about promotion, not about completeness.
- (c) [I] "delta ⊆ declared migration-lane records": if the check works at record (file) level, a lost write or a foreign write inside the migration's own work-item record is masked. Granularity is unstated.
- (d) L92's rationale is only partly true. Repointing the AMD4 additions invalidates S0138's own citations (e.g. S0138 L106 "§4.1" for RC-4, L135 "§4.2", L148 "§4.3"), which cannot be edited.
- (e) Step 5 (retraction) after step 3 widens the window in which the authoritative store carries a NON-AUTHORITATIVE label. RD-2 is not re-assessed.
- (f) L118's "✅" checks are self-verification. L132 says the author will not review its own remedies.

---

## Cross-source points

| Point | Sources |
|---|---|
| **Agreement.** Density ≠ completeness; the bounded completeness claim; the freeze is declaratory | S0136 L141–173 → S0137 L51–74 → S0138 L228, L295. Not independent: S0137 re-derives the claim structurally, while S0138 and S0140 restate it |
| **Contradiction with an earlier source.** "dense ⇒ omission mechanically detectable" is falsified | S0131 L25, L121 vs S0136 L149 |
| **Status change.** "Plan non-destructive" → RC-4 destruction path → final re-hash → α/β split | S0136 L21 → S0137 L87 → S0138 L106 → S0140 L47 |
| **Status change.** "18 hash comparisons proves quiet" → unreachable (RD-10) → declared-delta partition | S0136 L173, S0137 L72 → S0138 L243 → S0140 L61 |
| **Recurring defect class.** A required act or artifact is specified but carried by no phase, or a phase consumes its own successor's output | CL-3 (S0136 L229), RC-1 (S0137 L192), RD-7 (S0138 L159) |
| **Recurring defect class.** Enumeration does not match content | RC-7a (S0137 L346), DI-3 (S0138 L283), S0138 header L7, S0139 L39 |
| **Recurring defect class.** A remedy reintroduces the defect | RC-1 inside CL-6; DI-1 introduced by AMD4; AMD5 first draft (S0140 L93) |
| **Lineage.** One author (bc1b47ef) for the original review and AMD3–AMD5. Reviewer, then author, then the author's remedies judged by two other sessions. Every identity is self-declared and not attestable | S0136 L5; S0137 L17; S0138 L20; S0139 L6; S0140 L6 |
| **Status claims without acts.** "accepted" for the migration plan (S0136 L68, L403). All "CLOSED" verdicts are reviewer judgements; no PO/ARB or Governance act appears in the window. Only the negative status "AMD3/4/5 not registered" is backed, by grep | S0137 L30, S0138 L35, S0140 L133 |
| **Pending.** OPEN-M1, M2, M4, M5 (enlarged by RD-6), M6; INFO-2; the nonexistent-governed-directory sub-case; RD-1, RD-2, RD-4, RD-5, RD-9 (silently dropped by AMD5); the irreducible interval between the final re-hash and removal; the pre-existing read-modify-write defect (Increment 2), unrepaired | S0138 L305–319, S0140 L128–137 |
| **Measurement stability.** 18/216/110 and 109 unique ids, re-measured in S0136, S0137 and S0138 and reported unchanged in S0139 and S0140. Consistent with no grants being registered in the window | all five |

Limits: I ran no code and checked no counts against the runtime corpus. Every measurement above is as reported by the sources ("reported, not shown"). Each line anchor comes from my own Read of the files.
