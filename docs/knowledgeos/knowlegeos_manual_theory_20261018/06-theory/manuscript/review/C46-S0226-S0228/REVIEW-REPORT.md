C46 STEP 2-3: REVIEW REPORT

*Window C46, §8.39 (claims C1–C6), sources S0226–S0228. Transition review, Rulebook v1.4. I re-read my saved inventory first; its sha256 matches SHA256-STEP1.txt (7ec45104…a542e0). My independence is process independence only: I did not write §8.39 and saw it only after step 1 was saved. It is not personal independence.*

## Summary (at most 10 lines)
1. §8.39 reads the sources faithfully. Every line reference I checked falls inside the cited range. My step-1 items 5, 6, 9, 13 and 14 are carried correctly.
2. **C1's core holds.** Under g1, adding support or refutation entries never ends a contest. I confirmed this with a probe on ce_model v24 (P1).
3. **C1's "only an annulment or a counted withdrawal" is too narrow.** Annulling the producer's grant also ends a contest (P2). A grant ending recorded later, at the entry's act time, turns "contested" into U (P4). §10.2 itself lists "an act of standing" as a third route.
4. **C2's "defeat has no home" treats not-yet-built as absent.** Three places in the book bear on defeat:
   - Q4's evidential grade, where strength could enter (reference definition absent);
   - supersession, a required act kind (O1, I-7) that is not admitted to D (σ open);
   - defeat by undercutting, which has a home: the counted withdrawal.
5. **C3 contradicts the book on time.** An entry's identity includes act time, and an adoption's validity (§9.16 R2, §9.13 required fields). C3 also merges entry identity with item identity; whether time is part of item identity is T9-18's neighbour T9-14, still open.
6. **Changed theory (c32ea453e) left stale g0 text in §9.18.** In particular L145–147, "a surely live refutation gives 'refuted' whatever else is U", is now false under g1; check 38 shows U.
7. **C4's conclusion holds, but the must-do it cites is wrong.** §9.10.5 excludes the empty/no-op system by O1–O4, not by Q1. §8.35 C7 L109 makes the same misattribution.
8. Nothing here is critical. §8.39 adds no concept, and no derivation rests on these claims.

## Findings

| W-nn | Finding | Sev. | Evidence | Correction | Resolved when |
|---|---|---|---|---|---|
| W-01 | **C3:** "time is not part of an entry's identity" is contradicted by the book. It also compares S0226's *knowledge* (item) identity with the book's *entry* identity. | high | §9.16 R2 L93 and PL-4: identity = "kind and D's required fields other than the recorder and the status". §9.13 L27–35: every kind requires actor and act time; an adoption requires validity. Q1's k is "a governed identifier" (§9.10 Q1). | Split C3 into two parts. (a) Entry identity contains actor, act time and validity; it does not contain authority (CON-0141, Q1), and T9-18's extension applies to grants. (b) Item identity is a governed identifier; whether a time-indexed version is a different item is open (T9-14, G10). Restate the plain-language item 3. | C3 and item 3 restated; T9-14 is cited |
| W-02 | **C2:** "defeat by stronger evidence: no home" overstates. Defeat by *strength* is absent from g1 only: Q4's grade is required and unbuilt, and G7 links it to g1. Supersession is a required act kind (O1 L105, I-7), not admitted (Θ_f σ, L85, L266: "σ open"). A rival definition, defeasible-logic *undercutting*, has a home: a counted withdrawal by a grant holder (T9-16 (b)), which is S0888's "resolution". | high | §9.10 Q1–Q4, O1; §9.13 L85, L266; §9.18.5 g1 and (iv) | Restate as follows. "Rebutting defeat by strength: no home in g1; Q4 unbuilt. Supersession by an act: required, not yet built (σ). Undercutting: a counted withdrawal, or an act of standing." The same applies to C1's "Nor does the book weigh evidence": scope it to g1. | C1 and C2 restated with these three homes |
| W-03 | **C1:** the "only" list of acts that end a contest is incomplete, and it disagrees with §10.2 ("a correction, a withdrawal, or an act of standing"). Liveness is the evidence entry's own standing value (`wclass`: `prod = a[e['id']]`), so any act of standing on the producer's basis chain changes it. | normal | Probe on v24 (a scratch file, see the commands below). P0 contested. P2: annul the producer's grant gB → "supported", cause "producer not authorised". P4: a grant ending at the entry's act time → U. | "A contest ends only by a recorded act that makes every entry on one side not surely live: an annulment of the entry or of its producer's basis, or a counted withdrawal for the purpose asked (none applies with no purpose named). A standing act can also turn it into U." Align with §10.2. | C1, plain-language item 1 and §10.2 say the same |
| W-04 | **Changed theory, stale g0 text in §9.18 after c32ea453e.** The worst is L145–147: "(iii) … a surely live refutation gives 'refuted' whatever else is U", which contradicts g1 L200 and check 38 ("v23 gave refuted"). Also stale: L77 (G2 row), L82 ("g0's three values plus U"; there are now four classes), L142, L211, L273 ("the class rule g0", not placed), L44, L49, and TODO T9-17's home "§9.18.5 g0". | high | grep output (see commands); the c32ea453e diff touched only L24, L66–70 and L192–211 | Restate (iii) for g1 and update the listed rows. The G7 row now has four values to map against F13. | No g0 statement remains in §9.18 unless it is marked historical |
| W-05 | **C4:** the trivial solution is excluded by the wrong must-do. §9.10.5's F-vac table gives "∅; refuse-everything; no-op" as "—" under Q1 and "fail (nothing accepted)" under O1–O4 only. Q1 excludes constant answerers, not an empty store. §8.35 C7 L109 has the same error. | normal | §9.10 L169–179. R3: §8.35 C7 is cited at §8.39 L20, L71, L83 and §8.40 L86. | Cite O1–O4 (inputs to accept, EF), and Q1 for constant answerers. Correct §8.35 C7 L109. | Both lines corrected; the R3 sites checked |
| W-06 | **§8.39.4 C3's recorded rival is not the strongest.** S0226's own type, "SecurityStatus : (system, audit-context, timestamp, **assessor**) → EpistemicClaim" (L602–606), reads the tuple's "Authority" (L494) as the *assessor*. The book carries the assessor as the evidence entry's actor (producer), a required field of the entry. On that reading S0226 partly agrees with the book. "authority: operations" in bounded_by (L466) is fairly read as a content delimiter. | normal | S0226 L460–470, L486–496, L602–606; §9.18.4 evid fields | Add the assessor rival and its answer. Keep the delimiter reading for L466 only. | Rival added |
| W-07 | **C2, expiry:** the claim does not say that an evidence entry carries no validity period (the evid fields have act time only). A refutation of "v1.0 is secure" therefore never expires; only a withdrawal or an annulment ends it. Also, for authority the book already keeps five kinds of ending apart (§1.13 AM-1; §1.16 L38). | normal | §9.18.4 table; §1.13 L85 | Add both points. KOS-TIME-001 for evidence then has no home (a gap, not a refutation). | Recorded in C2 |

## Claim verdicts

| Claim | Verdict | Reason · scope |
|---|---|---|
| C1 "contested" agrees with the sources | VALIDATED | g1 L196; S0226 L237–247 |
| C1 more evidence never ends a contest | VALIDATED | It needs premises (c1) and (c2) (§9.18.6), which C1 does not cite. Probe P1 is a case, not a proof. |
| C1 only an annulment or a withdrawal ends it | INVALID as stated | P2, P4; §10.2 |
| C1 not weighted | VALIDATED for g1; PROVISIONAL for the book | Q4's grade is unbuilt (W-02) |
| C2 three-way split as a reading of S0226/S0228 | VALIDATED | The sources merge conflict and defeat (L224–241 against L350–373) |
| C2 defeat has no home | INVALID as stated | W-02 |
| C3 authority is not part of identity | VALIDATED | CON-0141; §9.10 Q1 |
| C3 time is not part of identity | INVALID as stated | W-01 |
| C4 no transitions; prohibitions only | VALIDATED, with a caveat | S0226's Evidence-Override-001 is a *permission* ("may"), not a prohibition. The trivial-case citation is wrong (W-05). |
| C5 | PROVISIONAL | "A content category is the application's" is Int, and the three boundary tests are not applied separately (appendix A3) |
| C6 | VALIDATED | The source facts I checked against the sources hold. The relays to S0217–S0225 and "INV-KOS (earlier)" were not checked by me. |

**Proof-step table for C1's Ded (the forward direction):**

| Step | Justification | Defined in the book? |
|---|---|---|
| (1) live = producer authorised ∧ not annulled ∧ not withdrawn for p | §9.18.5 L194 | yes |
| (2) contested ⇔ ref = T ∧ sup = T | L196 | yes |
| (3) a new evid entry is neither an annulment nor an ewdr | §9.18.4 table | yes |
| (4) a new evid entry changes no standing value of the other entries | **premise (c1)/(c2), §9.18.6** ((c2) is proposed; result 1 is empirical on a sample, L227). Not cited in C1. | yes |
| (5) Kleene "or" is monotone, so ref and sup stay T | L195 | yes |

Judgement: forward direction **inadequate: premise missing** at step 4 (the premise is easily added). The converse ("only") is not derived, and probes P2 and P4 show it false.

**Recorded challenges:**
- C1's rival: adequate.
- C2's rival: adequate against "recency", but the undercutting rival was not tested, so the testing of "no home" is inadequate.
- C3's rival: inadequate (W-06).
- C4–C6: no challenges were recorded; W-05 would have been found by testing C4.

## Coverage against my inventory

| Inventory item | State |
|---|---|
| S0226 items 3–6, 8–12 | COVERED |
| S0226 item 7 (avacchedaka) | COVERED through §8.37 C3 |
| S0226 item 2 (Inference-Consistency-001, "preserve validated relation strength") | **MISSING**, normal. It is another strength assumption, bearing on C1 and C2. |
| S0226 items 13–14; S0227 item 9; S0228 item 12 (three incompatible layer orders; Tarka/Matilal order) | **MISSING**, low. Excludable as an architectural preference, but "ontology before reasoning" (S0228 L692) against S0226's stack without ontology should be noted beside C5. |
| S0227 items 1–4, 7, 8, 10, 13 | COVERED |
| S0227 item 6 (ErrorState-001, erroneous cognition kept) | **MISSING**, normal. It agrees with §9.18.4 L120 ("content found wrong is a refuting entry"). |
| S0227 item 11 ("knowledge is a justified relationship between reality, observer, evidence, reasoning, conclusion", against "knowledge object") | **MISSING**, normal. It is a rival definition of the item and bears on C3 (W-01, W-06). |
| S0227 item 5 (non-sequitur to a graph) | MISSING, low (source defect) |
| S0228 items 2–7, 9, 10, 13, 14 | COVERED |
| S0228 item 8 (PostgreSQL 15/14 could be a change over time) | DISTORTED in part: C1 reads it only as two conflicting adoptions. Read as a change over time, it supports C2's split. |
| S0228 item 11 ("debate tradition" against the body's "investigation") | MISSING, low (source defect) |

**New fork for the map:** none. W-02 sharpens G7: g1's four classes, Q4's grade, and where evidence strength could live.

## Next steps (in dependency order)
1. W-04: restate §9.18 (iii) and the stale g0 rows. Completion: no live g0 statement remains.
2. W-03: C1's "only" list, aligned with §10.2. Completion: P2 and P4 are covered by the wording. If wanted, add them as fixtures to check 38.
3. W-01 and W-06: C3 split into entry identity and item identity. Completion: T9-14 cited, and the assessor rival answered.
4. W-02 and W-07: C2 and C1 scoped to g1, with Q4, σ/O1 and withdrawal named. Completion: no "no home" statement for something that is not yet built.
5. W-05: §8.39 C4 and §8.35 C7 L109. Completion: both cite O1–O4.

## Limits
- The relays to S0217–S0225 were not opened, and no other corpus file was opened.
- ce_model was not re-run in full. I ran one scratch probe; probes P0–P4 are cases, not proofs.
- The quote check was not run mechanically (the tool limit noted in STEP8). I checked line references against the source text read at step 1.
- I did not review §8.40; the R3 tool printed two of its lines, which I note above but did not read as part of this review.

## Appendix (lower priority)
- A1: C1 quotes S0228 as "Conflict detected — Resolution pending". The source has two separate lines (L436–438), with no dash.
- A2: R2 in scope (§8.39), all tier D, judged one by one:
  - "reification" and "higher-order" (L85) carry a refutation and need a one-line gloss;
  - "defeat", the claim's headline term, is undefined in the book (it is not on R2's list either), and its defeasible-logic sense (rebut against undercut) matters (W-02);
  - valid-from, valid-until, avacchedaka, tuple, granularity and template are quoted from or glossed in the sources, so acceptable;
  - the rest (balance, solution, rejection, database, drop, dropped, stronger, exclude, nine, agent, example, agree, rival, keep, unstable) are ordinary words with no defect.
- A3: C5's "absence" is a homonym. It means a content category (the source's sense of absence of a thing) and also a marked absence (O3, a record-level absence of an act). The claim should qualify which.
- A4: A malformed evidence entry arriving by a merge takes the record out of D_W, so the class is *refused* rather than contested (§9.18.6 result 5). This is a boundary case for "never".

## Files opened
- W/06-theory/manuscript/review/C46-S0226-S0228/BRIEF-W-STEP2.md
- W/06-theory/manuscript/review/C46-S0226-S0228/STEP1-INVENTORY.md
- W/06-theory/manuscript/review/C46-S0226-S0228/STEP8-AND-CHECKS.md
- W/06-theory/manuscript/review/CONSOLIDATION-S0201-S0225/SETTLEMENT.md
- W/06-theory/manuscript/chapter_08/08.39-contested-is-not-resolved-by-more-evidence-and-conflict-defeat-and-expiry-are-three-things.md
- W/06-theory/manuscript/chapter_09/09.18-evidence-in-the-record.md (whole)
- Read in part through sed or grep:
  - 09.10 (L1–125, L165–182)
  - 09.13 (L24–40, σ lines)
  - 09.16 (L85–100)
  - 10.05 (L86–100)
  - chapter_09/TODO.md (T9-12, T9-17, T9-18 rows)
  - 08.35 (L74–83, L109)
  - 08.37 (L64–74)
  - 08.38 (L63–77, L99–110)
  - 09.06 (K2 lines)
- W/tmp/tools/ce_model.py (L124–138, L1326–1400, L1452–1457, L2519–2537, L2640–2700)
- W/tmp/tools/ce_model-run-20261030-v24.txt (grep only)
- W/tmp/tools/inbound_citations.py and undefined_terms.py (headers)
- The three sources from step 1 were not re-opened; their text was still in my context from step 1.

## Commands run
- `wc -l` and `sha256sum` on STEP1-INVENTORY.md, compared with SHA256-STEP1.txt; `git log --oneline -3`.
- `git show c32ea453e -- 09.18 10.02`.
- `ls` of chapter_09, chapter_10, 06-theory and tmp/tools; `wc -l` on the dependency sections.
- `grep` in 06-theory chapters, the TODO, the map and the register for: σ; "required field"; PL-4; T9-12/T9-17/T9-18; claim headings in §8.33–§8.38; F-vac, trivial and O1/O2 in 09.10; stale g0 wording ("whatever else is U", "refutation-first/first", "g0's three values"); defeat and expiry; absence in §8.39.
- `grep` in ce_model.py and the v24 run file.
- `python3 -I tmp/tools/inbound_citations.py` for "§8.39 C3", "§8.39 C1", "§8.39 C4", "§8.35 C7" and "§9.18.5 (iii)", on the default root.
- `python3 -I tmp/tools/undefined_terms.py --scope 8.39` and `--scope 08.39`, on the default root.
- I wrote and ran a scratch probe, /tmp/claude-1891886374/-home-d0f38614-c3a6-41d3-9952-7f59ad699b2d-roshyara-personal-nrna1/16a0ac97-2df1-4d21-8fca-c43f5de5b1e2/scratchpad/c46/contest_probe.py, which imports ce_model read-only. I edited it once with `sed -i`; that file is in the scratchpad, outside the repository. Its output:
  - P0 contested;
  - P1 three more supports: contested;
  - P2 annul the producer's grant: supported;
  - P3 grant ending after the entry: contested;
  - P4 grant ending at the entry's act time: U.
- Nothing in the repository was edited, created or committed, and nothing was sent to an outside service.
