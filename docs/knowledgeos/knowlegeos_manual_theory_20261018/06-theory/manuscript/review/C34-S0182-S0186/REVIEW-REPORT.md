C34 STEP 2-3: REVIEW REPORT

Window reviewer W. Transition review of §8.27 (C1–C7), plus the changed theory in `4e50725e2` (model v19) and `d149ebd07` (§9.17 (c′), T9-10, T9-11 and the map). I re-read my saved inventory first.

## Summary
1. **§8.27 claims: mostly sound as a coverage ledger.** C3, C5 and C7 hold within their scope.
2. **§8.27 claims that need correcting:**
   - C4 maps S0183 onto CON-0259 wrongly. The estate checks the survivor before authority moves, not at the irreversible removal.
   - C1 treats a prompt (a relay) as an act, which misplaces its T8-03 instance.
3. **Changed theory, critical:** the Professor's reading of the Principal's owner rule (§9.17 (c′)) claims a detection fallback that v19 does not provide. A single move of a store by an application that does not own it, attested by that application's own kernel, leaves the record in D (W probe, below). The owner rule is enforced nowhere in the model or in D, and the "Not modelled" note is scoped too narrowly.
4. **The consolidation settlement matches its text in the rows I checked** (16 rows; table below). There are four residues: stale wording in CON-0079, CON-0264, R3 (a) and the §5.7 body.
5. **Model v19:** my re-run of v18 and v19 reproduces both saved runs byte for byte. Lines for checks 1–29 are identical. Three fixtures in checks 22 and 24 were edited (`kfill`), so the *output* is unchanged, not the code. The premise "attestation assumed unforgeable" is stated, but three further limits are not.
6. **Quotation:** the decision is quoted with wording that differs from the decision record (`a51be45b8`), where only "record all four" is verbatim. This is the same failure class as C32 W-05.

**Process independence:** I am a separate session from the Professor's, possibly of the same model family. My independence is of process, not of person.

## Findings

| W | Finding | Sev. | Evidence | Correction | Resolved when |
|---|---|---|---|---|---|
| **W-01** | §9.17 (c′) has two problems. **(1)** The Professor's reading says conflicting moves are "refused at the owner's kernel" and that "an entry not accepted by the owner's kernel carries no valid attestation and takes the record out of D". In v19, an attestation means *some* kernel accepted the entry. It is not bound to the store's owner, and D checks presence only. **(2)** Prevention also rests on an unstated premise: the non-owner's move passes through the owner's kernel, or every kernel can read who owns the store. Nothing records store ownership (T9-11 lists this as open). | **critical** (F3: a kernel/boundary question; T9-10's closure rests on it) | Probe on a scratch copy of v19, run read-only: a designation C1→C5 by app B, `katt='K2'` → `in_D = (True, None)`. A string `katt='forged'` → in D. Two moves of C1 → outside D through the *fork* clause, not the attestation. | **(1)** State the premises: ownership of each store is recorded in the record; every kernel's guard reads it, or moves are routed to the owner. **(2)** Add a D clause or mark, "designation not by the old store's owner", parallel to the grants' owner check (K4-k, v13 mark). **(3)** Restate the fallback: across merged applications only a *fork* is detected; a single foreign move is not. **(4)** Widen "Not modelled" to: owner check, ownership record, and owner-bound attestation. The Principal's clause permits a model version on a critical finding. | a rule plus a check: a foreign single move is refused at append and is outside D, or marked, when merged |
| **W-02** | §8.27 C4 says the S0183 correction "is CON-0259's operand condition". The `3(iii-b)` check compares the survivor at the authority switch. Phase 7's gate still compares the source against the enumeration, with "neither operand … the durable copy" (S0183 L159, L164). CON-0259 and CON-0265 require the survivor at the *irreversible removal*. Loss in the survivor between `3(v)` and Phase 7 stays unguarded (compare §8.19 C11: guard the destructive act). | high | S0183 L140–164, L171–179; CON-0259; CON-0265 | Restate C4 as partial conformance: the survivor is verified at transfer, not at removal. Record that the estate's design diverges from CON-0265. CON-0265's own verdict is unaffected. | C4 restated; divergence noted under CON-0265 |
| **W-03** | §8.27 C1 says "the act named a set, the record held a larger one". But the handed lists were a chat commission or bootstrap (a relay; S0184 L47 "chat-supplied", S0185 L59), and both bars were read from registered grants (acts). That is not F35's distinction between an act-set rule and a record-derived one. What T8-03 actually gains: grant 22's "fresh" requirement delegates to the record (bar derived from participation), while the bars name processes (bars set by an act), so both kinds appear in one gate. Two more points. Relays err in *both* directions: S0183 L294 excludes `870305e0` absolutely, while the grant bars it "only where conflict remains" (S0185 L38). And S0185's prompt warned the list "may be incomplete", so the second occurrence was anticipated: n = 2 occurrences, one line. | high | S0184 L45–47; S0185 L33–43, L59–67; S0183 L294 | Reword the Int as "record over relay" (the C23 rule). Move the act-set vs record-derived instance to grant 22 and S0185 §2. Add the over-broad relay case. Bound as 2 occurrences, 1 line. | C1 restated; T8-03 entry corrected |
| **W-04** | §8.27 C3 omits CON-0127 (§8.06 C3: independence can be refuted on the record, not established). S0185's headline "INDEPENDENCE IS SATISFIED" (L69) rests on zero occurrences (a search miss) and a self-declared identity. The source scopes "fresh" but not "independent". Its control, falsifiability (L23), works only if some channel could expose a false identity, and none is named. | high | S0185 L17–23, L52–56, L69 | Add S0185 as an instance of a source overstating what CON-0127 allows. Home the falsifiability control there, as PROVISIONAL. | C3 extended |
| **W-05** | §8.27 C2 has five problems. **(1)** "Converse" is wrong: the two rules are the two halves of the claim "registered if and only if live". **(2)** §8.27.1 item 2 adopts "worse" in the book's own voice; that comparison is the source's. **(3)** §8.19 C7 is the wrong link: the point "refusal must come before the record is created" is §8.19 C13. **(4)** "D cannot detect" needs splitting. False attribution partly meets the authentication status (signed-as-other mark, v9) where entries are signed; a false premise is K3's "legitimacy matter". **(5)** An earlier home is uncited: §5.7 Int (§6.6 C4), "a COMPLETE on a lane that never ran would be a false record". Note also that the mechanism already refuses START without REGISTER and HANDOFF (S0184 L88), so the risk lies in false REGISTER and HANDOFF appends. | normal | S0184 L88–107; §8.19 C13; §9.15 K3; §5.7 | Relabel; attribute "worse" as Src; relink to C13 and §5.7; split the D claim | C2 revised |
| **W-06** | §8.27 C6 has three problems. **(1)** "Certified" is "provisionally certified" (S0186 L435). S0186's citations are stripped (inventory item 16), so whether it quotes an act cannot be read. **(2)** The PKS "system of record" is not ρ; the R1 link is an analogy. **(3)** For "processes see different states" (L876, reported, not shown), the link to CON-0239 has rivals: separate worktrees (different carriers), uncommitted changes, stale reads. S0186's own tension (L6 "executable outranks prose" vs the prose-based system of record at L433) is also unrecorded. | normal | S0186 L6, L433–435, L876 | Label the R1 link as analogy and the CON-0239 link as Int with rivals; record the L6/L433 tension | C6 revised |
| **W-07** | The (c′) quotation does not match the record. The decision record addendum (`a51be45b8`, 02:25Z) marks only "record all four" as verbatim. Its item 1 reads "not **only** detected"; the book quotes "not **just** detected", with "Decision: no." The assumptions appear in the record's item 2 in the record-keeper's words, not as "the Principal listed them". Two more points. T9-10's completion condition (a model check of an owner refusal) was dropped without saying it was waived. The "no" answers the settlement's polarity ("may two applications…?"), not T9-10's ("should each store belong to one application?"). | normal | `git show a51be45b8`; `d149ebd07`; T9-10 row | Quote the record, mark which part is verbatim, record the waiver, align the question's polarity | (c′) and T9-10 corrected |
| **W-08** | Settlement residues: **(1)** CON-0079's register row and §5.3 still say "not yet reviewed (only a reviewer's verdict makes it VALIDATED)" beside VALIDATED. **(2)** CON-0264's definition keeps "classed foreign (withheld, not promoted)", although the settlement (§1 item 4) withdrew it. **(3)** CON-0264's open-items column still lists T9-10's questions. **(4)** The settlement says "R3 (a)'s scope now names" attestation, but the R3 (a) scope paragraph is unchanged; the sentence is only in R3 (c). **(5)** §5.7 N-1 leaves the body text "never ran" and appends a note that contradicts it. | normal | register, §5.3, §9.17 lines 137 and 164, §5.7 | Restate each in place | rows restated |
| **W-09** | Three v19 limits are not stated. **(1)** D checks that an attestation is present, not that it is valid ("valid" in the text). **(2)** There is one `KERNEL_ID`, so §9.17's "attested by another kernel" is not modelled. **(3)** Check 31's "citing a bypass entry: nothing promoted" is vacuous under fail-closed: any bypass entry fails D. The "no oracle flag" test is syntactic, and fixtures derive the attestation from the `bypass` flag (acknowledged). | normal | v19 `in_D`, `Record()`; probe | Add a limits line to §9.17's model paragraph | stated |

## Settlement rows spot-checked (16; all match the text, apart from W-08)
- **§1 designation block:** attestation and fail-closed; carrier at most once; store required; membership is classification only.
- **A-04:** R4 and the CON-0265 row.
- **A-08, B-04:** K2-d attribution and §9.17 item 3.
- **A-05, B-06; B-09; A-13:** §8.26.
- **A-07:** §8.23.
- **B-08:** §8.24.
- **A-06, B-11, B-12, B-15:** §4.1.
- **B-13:** §4.3 and CON-0113.
- **B-14, B-16:** §4.2.
- **B-17, B-20, N-1:** §5.7.
- **B-18:** §5.00.
- **B-21:** §5.3.
- **B-22:** §5.4.
- **A-11:** the map.

## Claim verdicts

| Claim | Verdict | Scope / reason |
|---|---|---|
| §8.27 C1 | PROVISIONAL | record over relay holds; T8-03 mapping distorted (W-03) |
| C2 | PROVISIONAL | Src rule; link and "worse" to be corrected (W-05) |
| C3 | VALIDATED | "fresh" = no prior participation the record can test; add CON-0127 (W-04) |
| C4 | INVALID as stated | the match to CON-0259 fails at the removal act (W-02) |
| C5 | VALIDATED | Src agreement within lineage; matches §9.17 R1 "first append" |
| C6 | PROVISIONAL | relay; links are analogy or Int (W-06) |
| C7 | VALIDATED | as rule agreement; mtime is weak save evidence after a git checkout |
| v19 completion (CON-0264 clauses) | PROVISIONAL | holds on guarded, single-kernel built records; premises stated (W-09) |
| (c′) Professor's reading | INVALID as stated | fallback detection by attestation (W-01) |
| The Principal's decision | not judged (normative) | quotation fixes only (W-07) |

## Coverage against my inventory

| Item | Status |
|---|---|
| S0183: quarantine invariant with no discharging command (OPEN-M7, no `DISPOSE`) | COVERED in substance by CON-0250 (S0141/S0142, same line). MISSING: the estate's mechanism lacks the release act CON-0250 requires. Add it as evidence. |
| S0183: two write classes (L167); label is observational (L58); trace ≠ soundness (L229); a freeze cannot cover its own authorizing act (L284) | MISSING. Homes: §9.17 item 3, CON-0248, CON-0242, CON-0254 (analogy) |
| S0183: forced placement and termination (L113–132) | COVERED (§8.20, C4 note) |
| S0184: "appointing is neither reviewing nor accepting" (L100) | DISTORTED: the book says "agrees with §1.14.3-2"; the source says the bar does not literally cover it, so the agreement is by extension |
| S0184: the eligible pool is narrowing (L126) | recorded, but not linked to CON-0151 (§8.09 C2) |
| `870305e0`: conditional vs absolute exclusion | MISSING (W-03) |
| S0185: falsifiability; independence from a search miss | MISSING (W-04) |
| S0186: L6 vs L433 tension; stripped citations | MISSING (W-06) |
| S0186: generations | recorded for T0-03 |
| S0186: scripts are a capability inventory, not boundaries; status vocabulary | JUSTIFIABLY EXCLUDED for now (no theory use yet) |
| S0182: two-tag scheme | JUSTIFIABLY EXCLUDED (classification of the record only) |

There is **no new fork**. Possible new topic: ownership of a store as a recorded fact, the store-level analogue of F22 (goes to T9-11 with W-01).

## Next steps (in dependency order)
1. **W-01:** state the ownership premises, add an owner clause or mark, and run a probe check. Done when a foreign single move is refused at append and is outside D, or marked, when merged.
2. **W-07:** correct the quotation and T9-10.
3. **W-02, W-03:** restate C4 and C1.
4. **W-04, W-05, W-06:** the C2, C3 and C6 edits.
5. **W-08, W-09:** residues and the limits line.
6. Coverage links.

## Limits
- I did not read the integration report, reports A and B, or S0141, S0142, S0300 or S0080. I opened no corpus file beyond the five sources.
- I checked R3 targets by reading the cited lines, not with `inbound_citations.py`. The probe shows a counterexample within v19, not a general result.

## Files opened
- Step 1: `03-methodology/REVIEWER-INSTRUCTIONS.md`, `BRIEF-W-STEP1.md`, and the five sources S0182–S0186.
- Step 2:
  - `review/C34-S0182-S0186/BRIEF-W-STEP2.md`, `STEP1-INVENTORY.md`, `STEP8-AND-CHECKS.md`
  - `06-theory/manuscript/chapter_08/08.27-…md`
  - `review/CONSOLIDATION-S0151-S0181/SETTLEMENT.md`
  - my own saved command output (`…/tool-results/bzhfj3beu.txt`)
- Partial reads with grep or sed:
  - `06-theory/CONCEPT-REGISTER.tsv` (rows CON-0239, 0243, 0250, 0259, 0264, 0265)
  - `chapter_09/09.17-…md`, `chapter_09/09.15-…md`
  - `chapter_08/08.19-…md` (C7–C14), `chapter_08/08.06*`, `08.09*`, `08.11*` (headings)
  - `chapter_08/TODO.md` (T8-03)
- Through git: the diffs of `4e50725e2` (map; Ch. 4, 5, 8, 9; register; `ce_model.py`; sessions; CONTEXT) and `d149ebd07`, and the decision-record addendum in `a51be45b8`.

## Commands run
1. `git show --stat 4e50725e2`, `git show --stat d149ebd07`, `wc -l SETTLEMENT.md`
2. `git show 4e50725e2 -- chapter_09/ THEORY-MAP.md`
3. `git show d149ebd07 -- 06-theory/`
4. `git show` of the sessions and CONTEXT diffs for both commits
5. `ls ce_model*` in `tmp/tools`; `git show 4e50725e2 -- ce_model.py`
6. Model re-runs: v18 and v19 extracted with `git show` into the scratchpad; `cmp` against the working copy; `python3 -I` runs of both; `diff` against the saved runs and against each other
7. `git show --word-diff` and plain diffs for Ch. 4, 5 and 8
8. `grep` over §9.17 and the register rows; `grep` and `sed` over §8.19, §8.06, §8.09, §8.11, §9.15 and the Ch. 8 TODO
9. Probe: a scratch copy of v19 with three probe cases, run with `python3 -I`
10. `git log -S` for the decision wording; `git log --oneline -6`; `git show --stat a51be45b8`; `git show a51be45b8 -- 03-methodology/PRINCIPAL-DECISION-RECORD-RULES-20261018.md`

Nothing was edited, and nothing was sent outside. No private person's name is reproduced.
