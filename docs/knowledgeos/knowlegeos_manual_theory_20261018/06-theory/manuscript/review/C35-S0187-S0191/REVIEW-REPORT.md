C35 STEP 2-3: REVIEW REPORT

Window reviewer W, transition review. Scope: §8.28 (C1–C8) and the changed theory in `756b31612` (the C34 settlement, model v20, §9.17 (c′), §8.27 restated). I re-read my saved inventory first.

## Summary (10 lines)
1. **§8.28 is sound as a record of what the sources say.** Its interpretations are weaker in four places: C2 (stale since v20), C4 (the population paragraph), C8 (durability is a homonym, not a contradiction) and C1 (an unexamined premise that PKS and EKS are two systems).
2. **v20, verified.** The owner check is enforced at append (`append_guard`) and in D. My re-run gives checks 1–31 byte-identical to v19 and the v20 run identical to the saved run.
3. **v20, per-store ownership is degenerate (W-01).** Under the guard, and in D, every store's owner is `ROOT_OWNER`, so "one owner per store" is modelled as "one owner per record". (c′) half-says this; R3 (c) and check 32's labels do not.
4. **v20, detection rests on a declared application (W-02).** A move attested by a foreign kernel K2 that declares `app='A0'` stays in D. This is the stated premise, and S0190 is the estate's direct instance of that premise failing, yet §8.28 C2 treats the estate's identity gap as bearing on "a different object".
5. **(c′) quotes the decision record correctly.** "T9-10 met by check 32" holds within the one-owner scope.
6. **§8.27 restated:** C1, C3, C4 and C6 match. C2 keeps the withdrawn "converse" sentence (L45 against L47).
7. **Lineage S0187 → S0186: confirmed, and wider than the draft says** (nine parallels; appendix).
8. **No critical finding.**

**Process independence:** I am a separate session from the Professor's, possibly of the same model family. The independence is of process, not of person.

## Findings

| W | Finding | Sev. | Evidence | Correction | Resolved when |
|---|---|---|---|---|---|
| **W-01** | **v20 per-store ownership is degenerate.** In v20, owner(new store) = the mover, and the guard requires mover = owner(old store). So by induction on the chain, every store is owned by `ROOT_OWNER`. No move to a store owned by another application is reachable, under the guard or in D. Check 32 (c) ("the owner of a store it moved the record to moves it again", `== ROOT_OWNER`) is the only reachable case. (c′) notes "every store in a v20 chain has the root owner's application". R3 (c) item 1 ("for any later store the application that moved the record there … two applications cannot both move one store") and the v20 docstring present per-store variety. The consequence is not drawn: there is no cross-application move without owner transfer. Whether that is the Principal's rule is normative. | high | Probe P1: guarded chains with movers in {A0, B} reach only (C1,A0), (C2,A0), (C3,A0). Probe P2: hand-built chains are in D only for movers (A0,A0). Code: `store_owner`, the `in_D` owner loop, `append_guard` L1066–1068 | Add to R3 (c) and T9-11: "in v20 a store's owner is constant along the chain (induction); per-store and per-record ownership coincide until owner transfer is modelled". Relabel check 32 (c). | stated in R3 (c), T9-11 and the check 32 label, or a transfer modelled |
| **W-02** | **§8.28 C2's interpretation of (c′) is stale under v20.** v20's guard and D read a *declared caller application* (`app`, "trusted as declared"). That is exactly what S0190 shows the estate cannot attest ("no gate reads it", L103; also S0186 L175). The distinction between attestation of acceptance and attestation of identity is sound. But v20's claim "detected across merged applications" needs the second kind (owner-bound attestation, T9-11), and the estate has none. | high | Probe P4: a K2-attested designation C1→C5 declaring `app='A0'` gives `in_D = (True, None)`, and a guarded append declaring A0 is accepted | Restate C2's interpretation: S0190 is a direct estate instance of v20's located premise failing. Qualify (c′)'s "D refuses a record holding one" inline with "if the mover declares its own application". Inbound (R3): §8.29 L21, L52. | C2 and (c′) restated |
| **W-03** | **C1's criteria 1–2 rest on an unexamined premise: that EKS and PKS are two independent domains.** S0187's own base, S0186, speaks of "the system historically called PKS / EKS" (L14), with generations G0→G3 (L734–766). S0189 makes PKS a boundary candidate whose boundary is UNKNOWN (L1608, L1983). If they are generations of one system, what they share reflects lineage, not domain-independence, which undermines S0191's empirical test (L465–467). Separately, criteria applied element by element cannot decide a sufficient kernel (CON-0105, §9.2 C2: litmus ≠ placement). | high | S0186 L14, L734–766; S0189 L1602–1612; S0191 L465 | Add both points to C1's interpretation and route the PKS/EKS identity to T0-03. Inbound (R3): §8.30 L19, L48, L53. | C1's interpretation extended; T0-03 entry |
| **W-04** | **The window's kernel candidates are not assessed.** The book records only the method (C1). Unassessed: S0187's YES table (L81–103), GovernedWork (L172–221) and its kernel definition (L685); S0191's Observation protocol / ChangeSet / Rule port (L162–298). The Rule port rests on the "Rule" homonym the draft already records. | high | sources; BOOK-OUTLINE §3 ("Missing: further capabilities tested by all three boundary questions") | Give each candidate a §3a mark or a reasoned exclusion (e.g. ChangeSet: application, observation runtime) | marks or exclusions recorded |
| **W-05** | **C8: the durability claim is a homonym, stated as a contradiction.** S0188 defines "durable" as "rather than … ephemeral conversation state" (L630). S0190 means version-controlled (L176). The contradiction holds only in S0190's sense, and the challenge record tests only the database rival. "One case" also understates the scope: `.gitignore` excludes the whole `.claude/runtime/` directory, where §1.13's recount read all 22 workflow files. | high | S0188 L630, L1014; S0190 L176; §1.13.2-1 | Restate C8: two senses of "durable"; under the version-control sense S0188 fails for every runtime governance record | C8 restated |
| **W-06** | **C4's "another population … frame not defined … not reconciled" is wrong.** S0188's figures are AMS's 2026-08-16 snapshot (§1.13 L54), relayed as "currently" on 08-21 (S0188 L73): a stale relay. S0190 (18 records, 126 grants) and the book's recount (18 work items, 142 grants, validity fields 0/144) are the same record later. So C4's substance holds across the book's whole census, more strongly than "one line". *(This corrects my step-1 cross-source row.)* | normal | §1.13 L3, L54–62; S0190 L194 | Restate the paragraph, citing §1.13.2-1 | restated |
| **W-07** | **C3 accepts "the eligible set keeps narrowing" without test.** S0190's own table lists `a8ce5a39` as "eligible" (L46), and the declarer is eligible too. What grows is the set of processes with a role, not the excluded set. A declaration is neither ground g1 nor g2 (CON-0151). The real liveness hazard is different: a lane bound to an ephemeral identity (Route A "requires that a8ce5a39 can actually be resumed", L153), plus R8 role immutability, costs a new lane for each unstartable binding. And since no gate reads identity, that binding is only evidential. | normal | S0190 L46, L113, L153, L174; CON-0151 | Attribute the narrowing claim to the source; record the lane-binding liveness question under CON-0151/F32 | restated |
| **W-08** | **§8.27 C2 residue:** L45 "This is the converse …" contradicts the W-05 correction at L47 | normal | §8.27 L45, L47 | Delete or restate L45 | done |
| **W-09** | **§8.28 C3's "n = 3" counts one relay twice.** S0190's eight names are S0185's list handed again (S0190 L64), now two names short. That is three occurrences from two relays in one line; §8.27 C1 says "one line" and C3 should too. S0190 L171 (three grant-id transcription errors) is further relay evidence. | normal | S0190 L62–64, L171; §8.27 C1 | Bound as "three occurrences, two relays, one line" | restated |
| **W-10** | **C2 / T9-11 strength.** "No attestation of either kind" is supported for *identity* (the estate reports none: S0190 L25; S0186 L175, an earlier home with shared vocabulary, so one line). For *acceptance* attestation no source read reports an absence; that part is a search miss. | normal | S0190 L25; S0186 L175 | Identity: "the estate reports none". Acceptance: "none reported in the sources read". | restated |

## Claim verdicts

| Claim | Verdict | Scope / reason |
|---|---|---|
| §8.28 C1 | PROVISIONAL | what the source says holds; the interpretation needs W-03 and W-04. Keeping criteria 3–5 apart from the boundary questions is **right** (appendix) |
| C2 | PROVISIONAL | the distinction is valid; the implication is stale under v20 (W-02); strength (W-10) |
| C3 | VALIDATED | record over relay, three occurrences in one line (W-09); the narrowing sub-claim is not validated (W-07) |
| C4 | VALIDATED within scope | no validity fields across the census; the population paragraph is INVALID as stated (W-06) |
| C5 | PROVISIONAL | agreement within the lineage; enforcement of invariant 1 is UNKNOWN by S0189 L1988 |
| C6 | VALIDATED | the rule matches §8.26 C6 |
| C7 | VALIDATED | same text as S0029; one line (R) |
| C8 | PROVISIONAL | the two-baselines part holds; "a statement against the record" is INVALID as stated (W-05) |
| v20 owner check | VALIDATED within scope | enforced at append and in D; the scope is one owner per record (W-01), the declared application (W-02), presence-only attestation |
| (c′) quotation; T9-10 met | VALIDATED | matches `a51be45b8`; the condition is met within the one-owner scope |
| §8.27 C1, C3, C4, C6 restated | match | C2: W-08 |

## Coverage (my inventory; ranked gaps)

| Item | Status |
|---|---|
| Kernel candidates (S0187 table, GovernedWork, L685; S0191 ChangeSet / Rule port) | MISSING (W-04) |
| PKS/EKS: one system or two (S0186 L14) | MISSING (W-03) |
| S0190 ownership mechanics: `mutationOwner` passes only at START; HANDOFF from=null only while there is no owner (L93, L163–165) | MISSING. This is an estate owner-transfer mechanism, so it is evidence for T9-11. Homonym: "owner" (mutation owner of a work item) vs owner application of a store |
| S0188's second mechanism is unnamed; "two independent mechanisms" (L809) | MISSING: independence is asserted (CON-0127). Belongs to C5 |
| S0189: a single demonstrated run (L818–834) | MISSING: an instance for C6 |
| Two senses of "KnowledgeOS" in S0189 (L879 current capability; L1590 target) | MISSING: a homonym for T0-03 (compare F14) |
| S0190's monotone narrowing | DISTORTED (W-07) |
| S0187: Decision is the "core of the kernel" (L291–305) yet kept "above the kernel" (L637) | MISSING (normal) |
| S0191 matrix: Observation ✓ despite "idle since 5 Aug" | MISSING (normal) |
| S0189 Evidence Analytics (reads only) vs Adaptive Recommendation (changes behaviour); events carry references | JUSTIFIABLY EXCLUDED for now: no theory use |
| Evidence order, Rule homonym, delegation status, status tension, S0191 L9 overstatement | COVERED |

**New topic:** the identity of PKS and EKS (to T0-03). **No new fork.**

## Next steps (dependency order)
1. **W-01, W-02:** state the degeneracy and the declared-application premise in R3 (c), (c′), check 32 and T9-11. Done when stated. No new model version is needed unless owner transfer is modelled.
2. **W-03, W-04:** extend C1; add marks or exclusions for the candidates; add the T0-03 entry.
3. **W-05, W-06, W-07, W-09, W-10:** restate C8, C4 and C3, and the strength of C2.
4. **W-08:** the §8.27 L45 residue.
5. Coverage items: C5's independence point, C6's instance, the S0190 ownership mechanics to T9-11.

## Limits
- I read §9.17 only at the (c′) and R3 (c) diff region, and §1.13 only at L1–62.
- I did not read §1.14, §8.26, §5.3, §8.29 or §8.30, apart from the inbound-citation lines.
- I did not open `.gitignore`, so W-05's scope rests on S0190 L176.
- `inbound_citations.py` does not resolve "§9.17 (c′)" or "§9.17 R3" (it returns 0); for those I relied on CON-0264's concept hits.
- The probes are counterexamples or exhaustions over small finite cases, not general proofs. The general case for W-01 is the induction above.

## Appendix (lower priority)
- **C1, criteria vs the boundary questions.** None of the six criteria tests boundary question 1 (necessary) or 3 (delegable). Closer homes:
  - criterion 3 → CON-0132 (owned vs preserved invariant; a candidate for boundary question 2);
  - criteria 1, 2 and 5 → product-independence / domain-free (CON-0075; §9.2 C2);
  - criterion 4 → none;
  - criterion 6 → §6.6 C8.
- **Lineage, S0187 ≈ S0186 (verified):**

  | S0187 | S0186 |
  |---|---|
  | L5 | L923 |
  | L105 | L131 / L686 |
  | L126 | L559 / L595 |
  | L178 | L303–309 |
  | L229–243 | L617–631 |
  | L291–301 | L606–615 |
  | L561–581 | L389–417 |
  | L433 | L455 |
  | L695 | L204 |

  The two files share the 2026-08-21 20:32 stamp.
- **v20, `store_owner` soundness:**
  - an unattested designation takes the record out of D through the attestation clause first (probe P3);
  - two designations naming one carrier are refused by D's no-reuse clause, which precedes the owner loop (code order read).

## Files opened
- **Step 2 reads:**
  - `review/C35-S0187-S0191/BRIEF-W-STEP2.md`, `STEP1-INVENTORY.md`, `STEP8-AND-CHECKS.md`;
  - `chapter_08/08.28-….md`;
  - `review/C34-S0182-S0186/SETTLEMENT.md`, `REVIEW-REPORT.md`;
  - S0186: `docs/knowledgeos/architecture/20260821_2032_EKS Current Architecture Baseline.md`, whole; checked on `CORPUS-ALLOWED.tsv` row 10 first;
  - `manuscript/BOOK-OUTLINE.md` L46–67;
  - `tmp/tools/ce_model.py` L180–204;
  - the saved diff output file.
- **Partial reads by grep, sed or awk:** `ce_model.py`, `inbound_citations.py` header, §8.27 ("converse"), register rows (CON-0265, 0264, 0105, 0132, 0075, 0151, 0127), §1.13 L1–62, §9.17 (c′).
- **Through git:** `756b31612` (stat; diffs of the map, Ch. 5, 8 and 9, the register, `ce_model.py`), `a51be45b8` (stat; the decision-record addendum), `756b31612^:ce_model.py`.

## Commands run
1. `git show --stat 756b31612`; `git log --oneline -6`; `git show 756b31612` for the theory files (the full diff, then a `--word-diff` view); `git show 756b31612 -- ce_model.py`.
2. Extracted v19 and v20 with `git show` into the scratchpad, `cmp` against the working copy, ran both with `python3 -I`, `diff` (checks 1–31; v20 against the saved run).
3. Probes P1–P4 on scratch copies (`probe/ce_model_probe*.py`), run with `python3 -I`.
4. `grep` / `sed` / `awk` on `ce_model.py`, §8.27, the register, §1.13, §9.17, BOOK-OUTLINE, `CORPUS-ALLOWED.tsv` (row for S0186); `wc -l` on S0186; `ls` of `06-theory` and `manuscript`.
5. `git show --stat a51be45b8`; `git show a51be45b8 --` the decision-record file.
6. `inbound_citations.py` for §8.28 C1, C2, C3, C4, C8; §8.27 C2; §9.17 (c′); §9.17 R3; CON-0264.

No file outside the scratchpad was edited. Nothing was sent outside the machine. No private person's name is reproduced. I opened none of S0155, S0156, S0157, S0161, S0170 or S0174, no Ch. 0–2 basis file and no excluded path.
