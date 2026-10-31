C67 STEP 1: BLIND INVENTORY

Reviewer W (Opus), window C67, transition review, Rulebook v1.4. Process independence only: I did not write the Professor's work and have not seen it. This is not personal independence.

## S0300: `docs/knowledgeos/architecture/KOS-AIP-GOV-STATE-DURABILITY-MIGRATION-PLAN.md` (1440 lines, read whole in 8 consecutive parts: 1–200, 201–400, 401–600, 601–800, 801–1000, 1001–1200, 1201–1440)

**My depth judgement: full.** This is not a purely operational source. It is a migration plan carrying derivations (a termination argument, a freeze-partition argument, a content-defined discriminator), withdrawn claims and invariants. Much of the text is about process and lineage. The theory-bearing parts are §1.3–§1.5, §3, §4.0, §4.3–§4.5, §6 and §9.

**What the source is.** A plan for moving the authoritative governance-evidence store (JSON workflow records, which are gitignored and so unversioned) into a versioned location. Status: PROPOSED, never executed (l.3). The text is layered: the original plan, then AMD3, AMD4, AMD5, AMD6 (l.12–265) and a "DV correction" (l.269–375). Each layer answers an independent review, and each keeps the wording it supersedes, labelled.

Ranked inventory. "Says" means the source's own claim. [I] marks my judgement.

1. **Core distinction: storage location is not domain ownership** (l.1286–1287; §1.6 l.494; §9 l.1277–1284). The store became authoritative "because the record happened to be written there". The source keeps apart: evidence bytes, evidence meaning, evidence authority, and historical location versus canonical future location. It names four boundaries: execution/runtime (no authority), governance evidence (authoritative), knowledge artifacts (interpretive), and derived state ("never a second authority source", l.500–505). Its quarantine rule restates the same point: "a location does not confer authority" (l.1008).

2. **"Durable" has two senses, separated** (l.1133–1137). Attestability (versioned, so anyone can re-verify it later) is what the migration cures. Crash durability is unchanged: there is no fsync, and rename gives atomic visibility, not durability. [I] One word was carrying two properties: a homonym, repaired in the source itself.

3. **Density is not completeness. This is a withdrawn claim, with a measurement behind it** (l.428–444, 1176–1193). Appends use read-modify-write with no lock: `seq` is computed from `count()`. Reported experiment: "23 / 30 concurrent-append trials silently lost a transition", and every survivor stayed dense and monotonic, because the overwriting writer reuses the lost seq. The absolute "no record silently dropped" is withdrawn. It is replaced by "no record lost RELATIVE TO the frozen manifest and the observed inputs" (l.1190). Consequence (l.1193): a loss inside the reconciliation tail is imported as a legitimate extension. The figures are reported, not shown in this source; they come from an earlier review the source cites.

4. **The freeze is declaratory, not a lock. Writes are partitioned** (§4.0, l.620–686). "No record was lost" is unprovable. "No write occurred during the window" is provable by re-hashing against the manifest (l.632). A literal freeze contradicts itself, because the migration's own governance acts are appends to the frozen corpus (l.636–648). So appends split into prohibited unrelated appends and permitted migration-lane appends declared in advance. Verification then has three outcomes: identical / delta ⊆ declared set / delta ⊄ declared set, the last being a freeze violation (l.679–684). The source says what was lost was "EVIDENTIAL CLARITY, not integrity" (l.686). It cites a live observation: grants went 110→114 because of AMD6's own commission (l.1390). [I] Hash comparison detects a change in end-state bytes only. A write followed by a byte-identical revert during the window is undetectable, so "provably quiet" means quiet at the two measurement instants. The source does not say this.

5. **Authority transfers at the writer switch, not at a later demotion act** (l.709–716, 718–755). Phase 6 only records a transfer that already happened. Within Phase 5 the mandated order is 1 re-hash · 1b reconcile the delta · 2 readers · 3 writer (the transfer instant) · 4 DEMOTED marker · 5 retract NON-AUTHORITATIVE (l.728–737). Rationale (l.741): readers first and writer last gives a "briefly-stale-read window", while the reverse order gives an "invisible-write window". Rollback changes kind at the writer switch: before it, rollback means "stop"; after it, "reconciliation"; after Phase 6, a governance act (l.1263–1269).

6. **Termination argument for where the slot-3 evidence goes** (l.206–214, 801–829). PREMISE 2 (l.806–810): a mechanical write confers no authority and is evidenced by a governance append. If the switch evidence were written before the switch, it would land in the source, reopen the delta and force another reconciliation, which by Premise 2 is again an append into the source: the process would not terminate. Written after the switch, it terminates in one step. The DV correction (DV-4) moved Premise 2 inside the argument; without it, "the recursion appears to stop after one iteration" (l.822–824). [I] This is a sketch, not a proof. It shows that the post-switch placement terminates. It does not show that every pre-switch alternative fails to terminate; for example, a bounded "evidence-of-evidence is batched" rule is not considered. Premise 2 is stated as a rule, not derived.

7. **The source final-state enumeration, and why comparison object and verification are kept distinct** (l.831–889). Slot 3 sub-steps: (i) measure; (ii) verify against manifest + reconciled delta + declared pre-switch records, and CLOSE the enumeration ("immutable"); (iii) mechanical write that brings the copy to that state; (iii-b) final durable-copy verification, all-or-nothing; (iv) switch P-1; (v) write the SWITCH-OVER RECORD carrying the enumeration and the receipt. Claim: closing at (ii), before the last copy write, is what structurally excludes "absorption" of a disputed write (l.884). Also: a comparison object ≠ a verification (l.752, 887).

8. **DV-1 safety gap: neither operand of the Phase-7 gate is the durable copy** (l.864–879, 941–947). Two copy-side writes (at 1b and 3(iii)) occurred after the last all-or-nothing verification, and nothing re-verified the copy before the source was removed. Source: "a specification is not a verifying act" (l.876). The plan re-verified the copy on the exception path but not on the normal path (l.876). Criteria 12 and 18 cover different operands, and "neither implies the other" (l.1310). [I] This is a general result: a gate guards only the objects it takes as operands.

9. **Record existence ≠ authority establishment; physical storage order ≠ domain event order** (DV-3, l.220, 1315). "FIRST record in the authoritative store" is withdrawn as false, because Phase 3 already copied records into that store. It is replaced by "FIRST GOVERNANCE APPEND AFTER THE AUTHORITY TRANSFER". The discriminator is the transfer event at 3(iv), not file position, filename order or lowest seq.

10. **Pre-switch versus post-demotion: a content-defined discriminator, not a time-based one** (l.891–901, 949–968). Only 2 of 216 transitions carry any time field (l.222, 893). Content inside the enumeration is accounted for. Content outside it is classed POST-DEMOTION "by construction". When ordering cannot be established, the tie-break is POST-DEMOTION (preserve, never promote). The two branches get opposite dispositions: PRE-SWITCH reconcile → re-verify; POST-DEMOTION quarantine, retain, escalate, never import (l.953–960). Admitted imprecision: a genuine pre-switch write in the measure(i)→switch(iv) window is misclassified, which is safe but imprecise (l.905). [I] So "by construction" is too strong: by the source's own window table, the classification is conservative, not exact.

11. **Quarantine is a domain state, not a directory condition. The completion block is indefinite** (l.236–237, 1028–1038; criterion 20). While any quarantine is undisposed: Phase 7 is suspended, the store is retained entire, and the target end state is not reached. The disposal path exists nowhere. The mechanism's transition vocabulary has no DISPOSE act (l.233). This is OPEN-M7 (l.1332). [I] By the trivial-case test, the model has "must-never" rules but no "must-do" command to release a quarantine. An aggregate whose completion depends on an undefined act can be blocked forever. The source states this as the safe direction.

12. **Quarantine laundering** (DV-6, l.1010–1026). Moving the conflicting record out of the demoted store makes the source match the enumeration, so the gate passes spuriously and removal opens. The earlier reason ("a move would break the enumeration") was inverted. The source labels this a rationale correction only, with no rule changed.

13. **Single Authority Resolver invariant; two location axes** (§1.5, §3, l.456–580).
    - P-1 and P-2 compute the same default independently. P-4 (an environment variable) selects the interpreter program. The source says P-4 is a write-capable path, because the reading script executes the program it names and hands it the record directory (l.480–488).
    - INV-R1: no silent fallback. It is split into location refusal (exit 65) versus workflow-state UNRESOLVABLE (report, exit 0) so that an existing contract test is not broken (l.541–549).
    - INV-R2: two defaults are replaced by one resolution, not synchronised.
    - INV-R3: governs P-4. Reporting form is current; enforcing form is deferred (OPEN-M5).
    - INV-R4: enforceable for components, advisory for ad-hoc readers (l.453–454, 563).
    - INV-R5: the resolver must exist before it is consumed; it is delivered dormant at 4b and activated only at 5 (l.565–569).
    - Design brief: refusal must come before the writer's `mkdir`, which otherwise silently creates a new evidence location (l.571–580).
    - Overrides: an override may choose where bytes go but never confers authority (§7, l.1227–1239).

14. **Byte preservation and what it forbids; copy versus comparison** (§4.1, §4.6, §6.3).
    - No re-serialisation of the copy. The pin is `-text` only; `text eol=lf` is withdrawn because it could silently rewrite a CR (l.1091–1098, "0 of 18 records contain a CR").
    - Canonical re-encode (recursive key ordering) is allowed for comparison only (l.1165–1170).
    - CASE A (strict extension → append the tail) and CASE B (same seq, different content → preserve both, escalate). Prefix means element-wise over the decoded transitions array.
    - Dense, monotonic seq makes CASE A decidable but does not prove completeness (l.1195).

15. **Grants have no seq, and grantId is not a key** (§6.4, l.1197–1221). 110 grants, 0 carry seq; 109 unique grantIds, because two distinct authority acts share one ID. Reconciliation is by position and content, never by grantId. The source says this collision does not explain the 109/110 delta: "Two independent facts".

16. **Removal ≠ deletion** (§4.2, l.694–707). Removal is legitimate only once a byte-verified, authoritative copy exists. The source makes the precondition part of the definition. Amended l.1043 (RV-2): "byte-verified" holds for the 1b delta and the pre-switch records only because 3(iii-b) verifies them.

17. **Residual windows: named, bounded, recorded, not closed** (l.903–911, 1077–1087). The windows: measure→switch; reader switch→3(iii) (stale read, "self-referential"); slot 3→4 (RD-2); slot 3→5 (authoritative store mislabelled NON-AUTHORITATIVE); final re-hash→removal. Each is "irreducible without atomicity". The source withdraws the claim "at NO point is the authoritative store labelled NON-AUTHORITATIVE" (l.1075). A marker is observational: it neither confers nor removes authority (l.1089).

18. **Changes over time (method).**
    - Each amendment answers an independent review; the author may not close its own remedies ("ADDRESSED, never CLOSED"). The bound "recurs until an amendment produces no new remedy" (l.74).
    - Superseded wording is kept and labelled. Original section numbers are kept and new ones renumbered, so already-delivered citations stay valid (l.127–139, 182–190).
    - A self-check that asserts its own success is "EVIDENCE, never assurance" (l.260): the four-layer trace was false for one row when delivered.
    - Planning counts are historical observations, not acceptance criteria (l.99–101, 381–395): 18/216/109 → 110 → 114 grants; 113 unique.

**Contradictions, slips and tensions inside the source** ([I] unless quoted):
- a. The header status (l.3–6) names AMD3–AMD6 only. The DV correction (§0.7) and a further undeclared layer of "RV-1/RV-2/RV-4/RV-7 repair" edits (l.796, 811, 1043, 1111, 1129) are not in the header or in §0.7.3's DV-1…DV-7 list. There is an unannounced revision layer.
- b. l.1043 says "the check is 18 hash comparisons", a fixed count. This contradicts the live-corpus rule (l.101: no count hard-coded as universal truth).
- c. "Immutable from this instant" (l.846, 1111) versus RV-7 (l.1111): a retry after a 3(iii-b) FAIL re-measures and re-closes a fresh enumeration. Immutability is per run, scoped only by a later patch.
- d. The CL-4 box (l.1259) still says "authority transfers on completion of Phase 5". The next line (l.1261) and §4.3 refine this to the writer switch. The box is labelled, but its sentence reads as current.
- e. The bound "recurs for the fourth time" (l.1402) and "for the fifth time" (l.1397). These are consistent only if read as AMD6 and DV respectively. The footer interleaves the two status blocks.
- f. §0.7.6 says "no criterion added or removed". Criteria 2, 11, 12, 16, 17 and 18 change their content and evidence under DV. Count preserved, content changed.

**Open questions (the source's own):**
- OPEN-M1: whether the location stays gitignored.
- OPEN-M2: evidence before the target exists; stage first or create the target first.
- OPEN-M4: unbounded readers.
- OPEN-M5: enforcing form versus an existing contract.
- OPEN-M6: amendments not registered.
- OPEN-M7: the quarantine disposer and the disposal path.
- OPEN-M3 is closed (Option A).
- The non-existent governed directory case: exit 65 or report? Left to the implementer (l.534).
- RD-2, RD-4, RD-5, RD-6, RD-9 are carried forward (l.180).

## Cross-source table (one source this window; key terms and where each is defined)

| Concept / claim | Defined at | Status in source |
|---|---|---|
| Location ≠ ownership/authority | l.1286, 494, 1008 | rule (core) |
| Attestability vs crash durability | l.1133–1137 | distinction |
| Density ≠ completeness | l.443, 1185–1195 | withdrawn claim + replacement |
| Declaratory freeze, three outcomes | l.620–686 | rule; PO/ARB act outstanding |
| Writer switch = authority instant | l.715, 734 | ruling |
| Premise 2 + termination | l.804–829 | derivation (sketch [I]) |
| Enumeration closed at 3(ii); 3(iii-b) | l.836–862 | rule (DV, PROPOSED) |
| Existence ≠ authority; storage order ≠ event order | l.1315 | distinction (DV-3) |
| Content-defined pre-switch/post-demotion | l.893–901, 966 | rule + admitted imprecision |
| Quarantine = domain state; indefinite block | l.237, 1028–1038 | rule; OPEN-M7 |
| Quarantine laundering | l.1010–1026 | rationale |
| Single resolver, two axes, INV-R1–R5 | l.456–580 | spec; enforcing form deferred |
| Byte-preserving copy vs canonical comparison | l.688–692, 1165–1170 | rule |
| Grants: no seq; grantId collision | l.1197–1221 | measurement + guidance |
| Self-check is evidence, not assurance | l.241, 260, 375 | rule |

## Lineage note

This is a single source, but internally it is a chain. The original plan (process `1c8b041b`) was amended four times (AMD3–AMD6) by one process (`bc1b47ef`), then corrected by a fresh process (`f7e57e4a`, the DV correction). Five reviews drive the chain (`a282d14b`, `9c908e70`, `870305e0`, `ccf6c9c7`, `dd639043`); none was opened. Every statement about what those reviews found, closed or measured (the 23/30 trials, the CL/RC/RD/DI/DV verdicts, test bodies such as T-12/T-13(b)/T-15, code line numbers) is reported, not verified by me. The source does re-measure its own corpus counts (l.1387–1389). The amendments restate earlier amendments, so for theory purposes they are one line of support, not independent confirmation. The independent reviews are a separate line, but only through this source's reports of them. The DDD reading (aggregate refusing an invalid transition, l.237) is the author's interpretation, not evidence.

## Files opened
- `/home/d0f38614-c3a6-41d3-9952-7f59ad699b2d/roshyara/personal/nrna1/docs/knowledgeos/knowlegeos_manual_theory_20261018/06-theory/manuscript/review/C67-S0300/BRIEF-W-STEP1.md`
- `/home/d0f38614-c3a6-41d3-9952-7f59ad699b2d/roshyara/personal/nrna1/docs/knowledgeos/knowlegeos_manual_theory_20261018/03-methodology/REVIEWER-INSTRUCTIONS.md`
- `/home/d0f38614-c3a6-41d3-9952-7f59ad699b2d/roshyara/personal/nrna1/docs/knowledgeos/architecture/KOS-AIP-GOV-STATE-DURABILITY-MIGRATION-PLAN.md` (S0300, read in 8 consecutive parts covering lines 1–1440)

## Commands run
- `grep -n "KOS-AIP-GOV-STATE-DURABILITY-MIGRATION-PLAN.md" docs/knowledgeos/knowledgeos-s-series-theory/corpus-access/CORPUS-ALLOWED.tsv` (a targeted check of the one path; result: row 62, S0300, status "match") and `wc -l` on S0300 (1440 lines). This grep read the allowed-list file without displaying it beyond the matching line.

Nothing else was opened, searched or listed. No scratch files were written and no repository file was changed. No file was refused, and no item needed an "(access)" mark. The S0155/S0156/S0157/S0161/S0170/S0174 files were not opened, and the source does not refer to them by those IDs. Stopping here until the step 2–3 commission arrives.
