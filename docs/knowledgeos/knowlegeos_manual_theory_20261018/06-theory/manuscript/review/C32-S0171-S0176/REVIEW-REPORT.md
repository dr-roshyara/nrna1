C32 STEP 2-3: REVIEW REPORT

**Process independence:** I reviewed the work's process independently. I am not independent of the person: I am the same model family as earlier reviewers. I re-read my saved step-1 inventory before reading any of the Professor's work.

## Summary
1. **Critical (W-01).** As applied in the text and in model v17, the T9-09 rule lets the record fork. Two designations can both close C1, one to C2 and one to C3. The append guard accepts both, both are in D, and both evaluate to T. A stale "old store" is also accepted, and it leaves the C2 entries closed by no designation. Neither the guard nor D ties "old store" to the carrier in force, so K2's single designated record is not guaranteed.
2. **The Professor's premise** (each entry records the store the kernel appended it to) answers C31 W-01 only for D's check. It holds only if the field is set by the kernel and is trustworthy. The model does not enforce that (W-02). Where the premise is stated is also wrong: it contradicts the opening sentence of R3 (c), §8.19 L236 and, unless scoped, R1.
3. **The append guard** counts any entry labelled with the old store as a maximal entry. A designation must then cite a bypass write and so promotes it (W-03).
4. **Check 29.** I re-ran it and the output is identical to the saved run. It passes, but it does not test W-01 to W-03. Part (6) passes by construction. Parts (6) and (7) test a helper defined inside the check, not the evaluator.
5. **Checks 1–27 are unchanged against v16.** I ran both versions; apart from checks 28 and 29, the outputs are byte-identical.
6. **§8.25 is mostly sound.** C6 is invalid as stated: a translation is not a projection in the book's sense, and the restriction to "lossy" mappings is unnecessary. C2 overstates in two places. The lineage misidentifies S0150.
7. **The C31 settlement rows match the text they claim to change.**

## Findings
| W-nn | Finding | Sev. | Evidence | Correction | Resolved when |
|---|---|---|---|---|---|
| W-01 | **The record can fork.** The guard (`append_guard`) checks only omission and authority. D checks that the closed entries are cited, lie in the old store and form an antichain, and that the designation's store is its own carrier. Nothing requires `old` to be the carrier in force. | **critical** (F1: K2-d/K2-w, CON-0264, R3 (c) rest on it) | Probe on a scratchpad copy of v17: d1 C1→C2 and d2 C1→C3 both return `(True, None)`, `in_D` is True, d1 and d2 are both T, and both have the same closed members. A stale designation (old=C1 after C2 is in force) is also accepted, and C2's maximal entries {d1, y} are never closed. | Guard: `old` must equal the carrier the designation in force names at this step (K6 knows it). D: designations form one citation chain, each later designation reaches the previous one and names its carrier as `old`, and each store is closed at most once. Add a fork case and a stale-old case to check 29. | The model refuses both cases, and R3 (c) and §9.13.2 state the condition. |
| W-02 | **The store premise is neither enforced nor reconciled.** The model gives `store` a default of `'C1'` set by the caller, so a kernel append after d1 lands in C1 (probe P1). That entry was accepted by the kernel, yet it is excluded from the closed members. D also admits an entry in a store no designation names (P4). The text contradicts itself: R3 (c) opens "D does not need a field per entry naming the carrier in force"; §8.19 L236 says "not per entry … last entry"; §8.23 L63 says the store "is not a property of its entries". | high | Probes P1 and P4; `Record` uses `setdefault('store', …)`; R3 (c) L145 against L152. | State the field as a **recorded claim set by the kernel**, equal to the carrier in force (K2-w) and covered by the integrity check, like the authentication status (§9.13.2, K5). Scope R1: the field records the carrier; it does not designate it. Fix the three lines. Name the rival: membership by down-closure from the closed entries, stopping at the previous designation, which needs no field for membership. | Model: the kernel sets `store`, D checks it against the designation chain, and the text is consistent. |
| W-03 | **A bypass write can be promoted.** The guard's "every maximal entry the old store has at that step" ranges over every entry labelled with the old store. A bypass write z labelled C1 forces the designation to cite it, which makes z a member. That contradicts "preserved, not promoted" (CON-0264) and the ruling's limit ("not seen by this check"). | high | Probe P3: `designation d refused: it omits the maximal entries ['z']`. | Take the maximum over the entries **the kernel accepted** into the old store, using its own acceptance state under K6, and say so. | The text and the guard agree on this, and a check with a bypass write before the designation is added. |
| W-04 | **Check 29's scope is overstated.** Part (1) does include a later new-store entry y that does not cite d (38 orders), but the fixture assigns y's store, and the case has one designation. The random part has no new-store entries. Part (4) builds a genuine U, but only from tied act times, while the model has other sources of U. Parts (6) and (7) test `choice_value`, a helper inside the check; "open choice" is not part of Φ. W-03's settlement claim that check 29 (1) "covers merge orders with designations" holds only under these limits. | high | `ce_model.py` lines 1905–2015 of v17. My own check: two successive designations give 3 orders and 1 result, which passes. | Add the fork, stale-old, kernel-set-store and bypass-before-designation cases. Scope R3 (c)'s "all seven pass" to the cases the check builds. | The cases are added and the scope is stated. |
| W-05 | **The ruling is attributed to the wrong words.** The decision record gives the Principal's verbatim as "yes record them". The quoted T9-09 and T8-04 text is the Rule Reviewer's recommendation, which the Principal adopted. The quotations in R3 (c), §9.13.2, §8.22 C7, the TODO rows and the model docstring also differ from the record: "State the limit:" against "Limit stated:"; "the old system's" against "an incumbent system's"; and "the append-order premise is replaced" is left out. | normal | Diff of `7ecf5a024`; the session log line in `9260e6d3a` says the quotes come from the earlier "refined decisions" text. | Quote the record and label the text "recommendation, adopted by the Principal". | The quotes match the record. |
| W-06 | **Stale text remains after the ruling.** The §8.22 C7 boundary still says "the incumbent is in practice the specification for that choice", directly before the decision that says it is not. THEORY-MAP L365 still has "last entry … ce_model v16". The CON-0264 basis cell still cites "v16 check 28". R3 (c) says the closed entries do not cite one another (direct citation); the model checks reachability, which is the right test for maximality. STEP8 records R3 only for §8.25, not for the ruling's changes. | normal | Read directly; `inbound_citations.py` output. | Restate each line, and record R3 for the ruling's changes. | — |
| W-07 | **The `observe` kind.** It is said to be "not an act", yet the evaluator gives it an authorization value: T, or F when the actor has no basis. No value depends on it, and T8-04 needs no new kind. | normal | Probe: `observe` evaluates to T, and to F when the actor holds no grant. | Rival: keep observations outside D as evidence (§8.06 C6), or give the kind no authorization value. State that 29 (6) is trivially true. | The kind is removed or redefined. |
| W-08 | **§8.25 C6 misuses "projection".** In the book, a projection is regenerable from its sources (CON-0260). An interpretive translation is not regenerable. C7, meanwhile, classes a derived summary as a claim, so the two claims classify the same kind of object differently. "A decision cites the governed state" follows from the C23 rule for **any** translation, including a one-to-one one. The challenge record's "for a one-to-one mapping the deduction would not hold" is wrong. Reading "Ready for decision" as covering ADDRESSED and CLOSED is the Professor's interpretation, unlabelled: S0173 defines no mapping. C6 also omits S0173's "Business Meaning Truth" (S0173 L303–307), which is in tension with its own "not an authority layer". | high | S0173 L252–253 and L303–307; CON-0260. | Restate: a translation is a derived claim (a gloss), and a decision cites the governed state (C23 rule). Information loss only adds that the state cannot be recovered. Label the mapping [I]. | Restated. |
| W-09 | **§8.25 C2 overstates in two places.** First, "recording a new knowledge product needs an adoption whose authorization is T": D records F adoptions, and only removals, designations and single-use uses are refused at append. Second, "outside that scope its acts are F": they can also be U, when "covers" is undefined (check 15). The title "acts under grants or not at all" merges capability with authority, which §8.24 C3 separates; plain-language item 1 states it correctly. The attack on S0171's worked example is fair: the mode is not named, the loop includes "Decide", and the checks are binary PASS. | normal | Model `append_guard`; check (15). | Write "effective only if T", "F or U", and fix the title. | — |
| W-10 | **§8.25 lineage misidentifies S0150.** It calls S0150 "a POA-versus-DDD file not yet read". But S0150 was read in C28 (§8.21; index row S0146–S0150), and §8.23 C10 identifies it with S0163's IPO/IP text. S0175's IP-protection file is not accounted for. | normal (source defect in the book) | §8.00 L35; §8.23 L100. | Re-derive S0175's input IDs from the index. | — |
| W-11 | **C7, "one source with its inputs", holds only for summarised content.** A synthesis also adds content of its own. S0176 adds a `.claude/platform/` path that S0173 does not state. It imports "does not replace any authority" from `…142748`, against S0171's self-run governance check (S0171 L403–416). S0175's "here it does not fire" is its own judgement, tagged ESTABLISHED. S0175 L102–104 is ESTABLISHED with **no** source, so it is not "an adviser stated it". C1 rests on S0176's resolution of S0171's identity tension, which is a summariser's interpretation, and C1 omits S0171's third reading, the robot as the execution layer (L552–554). | normal | Inventory S0175 items 8 and 10; S0176 items 8–9. | Add: a synthesis's additions are a separate, unsupported source. | — |
| W-12 | **Three contradictions have no home.** S0171 L129 against S0172 L156 is a homonym: "authority" as access to recorded authority versus as its source. Record it as in §8.24 C4. S0172 L160 against S0175 L46 is not a contradiction: one reports a gap in the estate, the other is a proposal from a research file; this agrees with C5. S0173's three template variants (L43–55, L157–193, L344–351) show that its "fixed template" is not stable. | normal | Inventory. | Record all three in §8.25.3. | — |

## Claim verdicts
| Claim | Verdict | Reason / scope |
|---|---|---|
| §8.25 C1 | VALIDATED | Holds as evidence of S0171's tension; add the third reading (W-11). |
| C2 | PROVISIONAL | Sound reading; wording defects (W-09). |
| C3, C5, C8 | VALIDATED | Holds as reported evidence (n=20 is reported, not shown). |
| C4 | PROVISIONAL | Labelled correctly as a relay. §2.8 is a Ch. 2 file, so I did not check it. |
| C6 | INVALID as stated | W-08. |
| C7 | VALIDATED (the tag is a claim); PROVISIONAL ("one source with its inputs") | W-11. |
| T9-09 as applied (§9.17 R3 (c), §9.13.2, CON-0264) | PROVISIONAL, with critical gap | W-01 to W-03. |
| T8-04 as applied (§8.22 C7 Ded, §8.22.3) | VALIDATED | A normative consequence; W-06's stale sentence must go. |
| `observe` | PROVISIONAL | Not shown to be needed (W-07). |
| Check 29 | VALIDATED within its constructed cases | Reproduced; it does not cover W-01 to W-03. |
| Checks 1–27 unchanged | VALIDATED | Rerun output is identical. |
| C31 settlement rows | VALIDATED | W-02, W-05 and W-08 match the diff; W-03's coverage is overstated (W-04). |

## Coverage against my inventory (MISSING, ranked)
1. S0172 L87: the Rule definition, which already uses authority, scope, period and exception (ordering is presentational only).
2. S0172 L160–164: the other authority gaps: delegation not represented, ownership ≠ authority, immutable provenance.
3. S0171 L104 against L240: KOS as brain or as memory, for T0-03.
4. S0171 L593–636: a learning loop with no retraction path.
5. S0173 L303–307: the three "truths" (W-08).
6. S0172 L406–494: semantics first, then contexts. This bears on the Automation Context.
7. S0172 L38–55: "rule" used for eight different things (a homonym).
8. S0175: the undeclared [GOVERNANCE] tag, and role ≠ capability ≠ context ≠ agent ≠ service.
9. S0173 L77 against L213: different review states for the same work.

## Next steps (dependency order)
1. Fix W-01 in the guard and in D, then add the fork and stale-old checks. Done when both are refused.
2. Fix W-02 and W-03: a kernel-set, integrity-covered store field, maximality over accepted entries only, and the four contradicting lines corrected.
3. Re-run v17 or v18. Checks 1–28 must be unchanged.
4. W-05 and W-06 text corrections, with R3 run for each.
5. §8.25: W-08 to W-12.

## Limits
- I did not open Ch. 0–2 (§1.13, §1.14.x, §1.16.2, §2.2, §2.8), so the homes of C3–C5 and C8 are unchecked.
- I did not open §9.13 or §9.15 in full, only the cells I cite.
- `08.26-…md` exists. I did not open it, but one line of it appeared in `inbound_citations.py` output.
- I saw the decision record and the 2026-10-27 session log only as diffs in git history (`git show 7ecf5a024`, `9260e6d3a`).
- The probes run on a scratchpad copy of the model. They show the model's behaviour, not that of the estate's real record.

**Files opened:**
- `W/06-theory/manuscript/review/C32-S0171-S0176/BRIEF-W-STEP2.md`, `STEP1-INVENTORY.md`, `STEP8-AND-CHECKS.md`
- `W/06-theory/manuscript/chapter_08/08.25-…md`
- `W/06-theory/manuscript/review/C31-S0165-S0169/SETTLEMENT.md`
- `W/tmp/tools/inbound_citations.py` (header only)
- Single lines read with `sed`: THEORY-MAP L365, §8.19 L236, §8.23 L63
- Tool-result file of the `git show` output, under `~/.claude/projects/…/tool-results/bv7wnpuyt.txt`

**Commands run:**
- `git show --stat` on `9260e6d3a`, `a7dc2b83d`, `7ecf5a024`, `019ed5b47`
- `git show 7ecf5a024`; `git show 9260e6d3a -- *.md *.tsv`; `git show 9260e6d3a -- *ce_model.py` saved to the scratchpad
- `git show a7dc2b83d` for §8.24, the register (word-diff) and §9.17
- `git show 9260e6d3a^:` / `9260e6d3a:` for `ce_model.py`, copied to the scratchpad
- `git diff --quiet` comparing HEAD with `9260e6d3a` for the model
- `python3 -I ce_model.py` on the v16 and v17 copies, with diffs against each other and against the saved run
- `grep`/`sed` on the v17 copy
- `python3 -I probe29.py` and `probe_fork.py` (scratchpad)
- `grep` for headings and "projection, never a source" in chapter_08; `grep` for S0150 in §8.00, §8.20–8.24 and the map
- `python3 -I tmp/tools/inbound_citations.py "§9.17 R3" CON-0264 "§8.22 C7" "§9.13.2"` (chapter_00–02 lines filtered out)
- `grep` for "last entry" in chapters 03+ and the map
- `ls chapter_08`; `ls chapter_09 | grep 09.15`; `grep` in §9.15 for the K2 cell

No file was refused, nothing was edited, and nothing was sent to an outside service.
