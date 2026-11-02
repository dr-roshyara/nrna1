C93 REVIEW REPORT

Window C93, reviewer W (Opus). Transition review, step 2. Object: §8.86 (draft), its row in `08.00-index.md` (L102), and `STEP8-AND-CHECKS.md`. I re-read my saved inventory first; its sha256 a7355ce8…82ae matches `SHA256-STEP1.txt`. Independence here is process independence (I did not write §8.86), not personal independence.

## Summary
1. All quotations match S0361 by script: 36 found. The 3 misses were artefacts of my regex; I checked them by hand and all match. The labels are mostly sound.
2. **C1 misstates a decision of the Principal.** It cites T10-05 for "none admitted". T10-05 is the decision that admitted standing, and later well-foundedness (§10.2 L5).
3. **C5's headline does not hold.** S0361's own row at L347 is labelled "mechanism confidence", so the two halves do not disagree on confidence. They do disagree on evidence admission and on justification sufficiency.
4. **C3 answers an open question.** The book fixes θ to Θ_f in D, admits no θ-setting act, and leaves F31 (which acts set θ, and on whose authority) open. (B)'s questions are F31, not a book result. C3 also stretches A1 from standing to rules.
5. **C4 uses the wrong home.** Its home is the carrier-change gate, an architecture act outside D. The book's in-domain answer is different: it evaluates over recorded acts (C1 = constitute), and where a check precedes the append, check and append are one serialised kernel step (G4, K4-k, K6, §9.13 L48).
6. **C2's mapping is loose.** (A)'s function judges a proposed transition before the act. Its counterpart in the book is the append guard, not CE*.
7. Largest risk: three "agreement" claims (C1, C3, C4) report the book's position as more settled, or as more nearly the same, than the book itself states it.

## Findings

| W-nn | Sev. | Claim | Evidence | Correction | R3 lines |
|---|---|---|---|---|---|
| W-01 | high (defect) | C1: "agreement with §10.2, where eight bounded-context candidates were admitted as none … (T10-05)"; index row: "candidate contexts, none admitted" | §10.2 L5: "Admission decided since (the Principal, T10-05 …): candidate 1 **admitted** …; 2 **admitted since the evidence kinds were adopted**". TODO T10-05: "Resolved: (B) with the K2 condition". THEORY-MAP L432: "Standing admitted". The draft uses the turn-2 paragraph (§10.2 L8; THEORY-MAP L430), which T10-05 superseded. | Restate: "§10.2: eight candidates; since T10-05, (B) with the K2 condition, standing is admitted, and well-foundedness since 2026-10-31; 3, 6, 7 and 8 are not contexts. (B) declines (A)'s seven contexts as not yet shown, which agrees with the book's method: admission needs a sufficiency condition and evidence." Do not cite T10-05 for "none". | §8.86 C1 (L33); 08.00-index L102. Tool: 0 claim citations. Separate book defect: THEORY-MAP L430 and §10.2 L8 read as current when taken alone (W-10). |
| W-02 | high (defect) | C5: "two halves that disagree on confidence"; plain-language item 5; challenge answer | S0361 L347: "Confidence scoring \| Interpretation Context (**mechanism confidence**)". (A) keeps "Mechanism Confidence ≠ Epistemic Confidence" (L50, L317). OQ-5, as §8.85 C1 reports it, defines domain confidence as "not a mechanism confidence score". So (A) and (C) are compatible on confidence, and the rival stated in §8.86.4 is the stronger reading. The disagreements the source does support: (A) puts "Evidence admissibility checking" in the Evidence or Governance Context (L344, L604); (C) lists "Evidence admission" as MUST EXIST (L1164). (A)'s "does not decide … how much it counts" (L349) meets (C)'s "Justification preservation + sufficiency evaluation" (L1165, L1197) more squarely than it meets epistemic-state determination. | Retitle to "two halves that disagree on evidence admission and sufficiency; confidence compatible under (A)'s own distinction". Accept the C5 rival. Move "disagree on confidence" to §8.86.5. | Title L1; L23; L57–61; L78–79; index L102 |
| W-03 | high (defect) | C3: the book's position is that rules are recorded and set by acts; "Under A1-recognized a rule that changes standing must itself be effected by a recorded act"; "Agreement with (B), against (A)" | §9.13 L107: θ is "fixed to Θ_f (not read from the record)". §9.13 L52: "the acts that set θ" are not admitted. §9.13 R4 (L252): "In general θ(u) is set by acts (§9.8 H3)", with the regress F31 "located", not solved. §9.13 L337 keeps "Which acts set θ, and under whose authority (F31)?" open. H3a (§9.8 L39) is a form law that D does not yet use for θ. A1's scope is "the standing of claims and the permissions of actors" (§1.6); rules are not in it. | Restate: "(B)'s questions (L882–886) are the book's open F31. In D the evaluator's rules are fixed by the Principal's decision (Θ_f), not supplied by a caller and not recorded. H3 states the requirement for the general case." Delete the A1 sentence, or label it Hyp, an extension beyond A1's scope. "Agreement" becomes "same open question". | L21; L48; index L102 |
| W-04 | high (omission) | C4: "Agreement with the book … (A)'s split … opens exactly the gap the book's gate excludes by premise" | CON-0265 and §9.17 R4 concern retiring a carrier, "an architecture act; D names carriers but admits no retirement act", and the scope "no write between the check and the act" (L260). The book's in-domain answers to (B)'s gap are missing: (i) under C1 = constitute an act is recorded and its standing is evaluated over the record, so for most kinds there is no prior verdict that can go stale; (ii) where a check precedes the append (a single-use grant, a designation), "authority is evaluated in the same kernel step as the append" (§9.13 L48; G4; K4-k "one step per grant"; K6 "steps are serialised"). CON-0243 and CON-0263 give the general rule: "only serialisation closes it". (A) is also inconsistent with itself: L195–201 has the Kernel "appl[y] the command to the aggregate", against L498. | Restate: the time-of-check/time-of-use form agrees (EXT-097). (B)'s "stronger transactional concept" is K6/K4-k's one step, which the book already has for checks at append. Its general answer is evaluation after recording, not a gate. Name (A)'s inconsistency, L195–201 against L498. Add G4, K4-k, K6, §9.13 L48 and CON-0243 to the home table. | L14; L22; L54 |
| W-05 | normal (defect) | C2: "(A)'s pure function is the book's evaluator" | (A)'s function takes a *proposed* transition and an "Authorized Command" and returns an admissibility verdict (L264–272, L708). CE* answers standing over recorded entries (§9.15 G1). The object that judges a proposed entry is the append guard, which "reads CE*'s value" (§9.13 L37). (A) also contradicts its own purity: it would "Record the adjudication" (L378) and own History (L466). | "(A)'s function corresponds to the append guard, a predicate on the record and the proposed entry that reads CE*. CE* itself is a function of the record and its inputs. (A) contradicts its own purity at L378 and L466." | L20; L41 |
| W-06 | normal (defect) | Lineage: "(B) and (C) are in the voice of the adviser of S0356–S0357 and S0360" | L1084 shows only that (B) is neither DeepSeek nor Claude. §8.84's lineage records that adviser identity "rests on voice alone (Int)". | Add "rests on voice alone". Add that (A)'s targets match S0360's unshown document (GLR L501, self-versioning L477; per §8.85 these are absent from S0358–S0359), so S0360 and (A) review the same unshown document. | L5 |
| W-07 | normal (R2) | "pure" (tier D; L1, L20, L36) | A technical sense with no gloss in the book. | Gloss once: "pure: its answer depends only on its inputs, and it changes nothing". | — |
| W-08 | normal (UL) | "admit / admitted / admissible" | Used in three senses in §8.86: contexts admitted (C1), non-admitted candidates (C5), and an admissibility verdict (C2), beside D's "admitted kinds". | Qualify each use, for example "admitted (context)" and "admissible (S0361's verdict)". | — |
| W-09 | normal (omission) | C5, non-admitted candidates | The quotation stops at L1353. F-4's first option at L1357, "recorded in History as part of an admitted transition", is the part closest to T9-12 (c)'s "accepted and shown, not counted". | Quote L1355–1358. "Not mapped" stays. | L59; L62 |
| W-10 | normal (book defect) | THEORY-MAP L430; §10.2 L8 | Each states "none admitted" without saying it was superseded. Only §10.2 L5 and THEORY-MAP L432 show T10-05. | Annotate both at the next consolidation. | — |

## Verdicts
- **C1:** the three texts, the two providers and one line of support are VALIDATED within the window. The §10.2 clause is INVALID as stated (W-01). The voice identity is PROVISIONAL (W-06).
- **C2:** PROVISIONAL. That a function is not an aggregate holds. The mapping needs W-05.
- **C3:** INVALID as stated. The book does not settle where rules come from; F31 is open (W-03). The source's reading of (B) against (A) is right.
- **C4:** PROVISIONAL. The agreement on form holds. The home is incomplete and "exactly" overstates it (W-04).
- **C5:** INVALID as stated on confidence (W-02). The disagreement holds on evidence admission and sufficiency.

## Coverage against my inventory
- **COVERED:** items 1, 3, 6, 8 (falsification table), 9 (numeric weights), 14–18 and 20 (part), plus F-3 and F-4 (part).
- **MISSING, and a loss of theory:**
  - (a) (A)'s verdict vocabulary (L270–272, L437, L684–686) keeps AMBIGUOUS/CONTRADICTION and "Requires Human Resolution" inside the Kernel. That agrees with G1/G6 (a contradiction gives U) and is a counterpoint to S0360 moving U out (§8.85 C4) within the same line.
  - (b) (C)'s single admission path, with Verification Port and Conformance Gate inside the boundary (L1209–1248), reverses (A)'s "already validated by Governance" (L197). The adviser moves toward the book's evaluation over recorded acts, which bears on §8.85 C2's rival.
  - (c) The trivial case: a Kernel that always returns "Not Admissible" meets every invariant at L586–591. This is §9.19.8 F-min, as §8.85 C4 uses it.
- **MISSING, minor:**
  - "must never acquire authority merely by crossing the boundary" (L616–621): agrees with A1 and §1.4;
  - contradiction SPLIT, detect then escalate (L497): agrees with G6;
  - self-versioning (L475–485): the same tension as §8.85 C5 with K7/H3b;
  - the internal contradictions C2/C3 of my inventory (who evaluates rules, L207 and L603–606 against L423).
- **JUSTIFIABLY EXCLUDED:** (B)'s challenge 4 (open); (C)'s program status and its thirteen outputs (process, reported).
- **New fork:** none beyond F31, which is already open.

## What is presented as decided that is the Principal's
- W-01: T10-05 is cited for its opposite.
- W-03: rule governance (F31) is presented as answered.
- (C)'s "MUST EXIST … existing law" and "research_required: NO" are correctly kept as reports.

## Limits
- I ran R2 with `--no-corpus`, because the tool reads the corpus through the repository path. That is why my run differs from the Professor's tier-A "effected".
- `corpus_quote_check.py` needs `02-FILES.jsonl`, which I may not open, so I used my own script against S0361 only.
- I did not re-verify §8.83 C5, §8.84 C3 or §8.06 C1 in full. I checked F-1…F-5 and OQ-5 only as reports.

## Files opened
- `06-theory/manuscript/review/C93-S0361/BRIEF-W-STEP2.md`
- `06-theory/manuscript/review/C93-S0361/STEP8-AND-CHECKS.md`
- `06-theory/manuscript/review/C93-S0361/SHA256-STEP1.txt`
- `06-theory/manuscript/review/C93-S0361/STEP1-INVENTORY.md` (first 60 lines re-read; hashed whole)
- `03-methodology/REVIEWER-INSTRUCTIONS.md` (step 1)
- S0361 (step 1; quote script at step 2)
- §8.86 and §8.85 in full
- `08.00-index.md` (grep)
- lineage headers of §8.83 and §8.84 (L3–8)
- grep or sed extracts:
  - §9.15 (G1, G4, G6, K1, K4, K6, K7, the S0361 row);
  - §9.13 (L37, L48, L52, L71–73, L81, L107, L252, L337);
  - §9.17 (R4, L223–260, L283);
  - §9.11 row 11a; §9.8 H3; §9.18 T9-12; §9.19 F-min;
  - §10.2 (L1–12, the candidate rows); §10.3 (admission lines); chapter_10 `TODO.md` L9;
  - §1.6 L10–22;
  - THEORY-MAP (L1–12, L205, L260, L430–434);
  - CONCEPT-REGISTER (CON-0243, 0257, 0259, 0263, 0265).

Book files were read from the scratch copy at `/tmp/claude-1891886374/-home-d0f38614-c3a6-41d3-9952-7f59ad699b2d-roshyara-personal-nrna1/16a0ac97-2df1-4d21-8fca-c43f5de5b1e2/scratchpad/rev-c93-wren/root/`, which holds the allowed book files and tools only, with no review folders. W paths are relative to `docs/knowledgeos/knowlegeos_manual_theory_20261018/`.

## Commands and searches
1. `ls` of the C93 folder, `06-theory/manuscript/`, `chapter_08` and `tmp/tools`; `sha256sum` of the inventory.
2. `cat` of the step-8 checks and §8.86; `grep "8\.86\|8\.85"` in the index; `wc -l` on chapters 8 (part), 9 and 10.
3. A first combined copy command was denied by the permission system. I then built the scratch copy with `mkdir` and `cp`, copying only the allowed files.
4. Tools run with `--root` on the scratch copy:
   - `undefined_terms.py --help` (failed: needs the corpus);
   - `inbound_citations.py --help`;
   - `corpus_quote_check.py --help` (failed: needs `02-FILES.jsonl`);
   - `undefined_terms.py --no-corpus --scope 08.86`;
   - `inbound_citations.py` for "§8.86 C1"–"§8.86 C5" and "§10.2".
5. My own quote script against S0361.
6. Greps on the scratch copy: K1/K7/G1/G4/G6/S0361 in §9.15; CON-0265 in the register; the scope phrase in chapter 9; constitute/θ/append in §9.13; "none admitted" in chapter 8, THEORY-MAP and the register; T9-12; T10-05; the candidate rows; K6; "pure"; F-min.

Scratch output files: `/tmp/claude-1891886374/-home-d0f38614-c3a6-41d3-9952-7f59ad699b2d-roshyara-personal-nrna1/16a0ac97-2df1-4d21-8fca-c43f5de5b1e2/scratchpad/rev-c93-wren/c93-w-r2.txt`, `/tmp/claude-1891886374/-home-d0f38614-c3a6-41d3-9952-7f59ad699b2d-roshyara-personal-nrna1/16a0ac97-2df1-4d21-8fca-c43f5de5b1e2/scratchpad/rev-c93-wren/c93-w-r3.txt`, `/tmp/claude-1891886374/-home-d0f38614-c3a6-41d3-9952-7f59ad699b2d-roshyara-personal-nrna1/16a0ac97-2df1-4d21-8fca-c43f5de5b1e2/scratchpad/rev-c93-wren/c93-w-quotes.txt`. The quote script is `/tmp/claude-1891886374/-home-d0f38614-c3a6-41d3-9952-7f59ad699b2d-roshyara-personal-nrna1/16a0ac97-2df1-4d21-8fca-c43f5de5b1e2/scratchpad/rev-c93-wren/c93-w-qcheck.py`, which does not follow the `c93-w-*.txt` naming rule.

No git commands. No edits to the repository. No corpus text sent to any outside service.
