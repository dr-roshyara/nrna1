C41 STEP 2-3: REVIEW REPORT

This is window reviewer W's transition review of §8.34 (C1–C6), the step-8 file and the C40 settlement (`3e7e8c598`). Before reviewing I re-read my saved inventory; its sha256 `b5d2af76…42fa2` matches SHA256-STEP1.txt.

## Summary
1. **No critical findings.** R3 finds 0 inbound citations to §8.34 C1–C5, so nothing depends on the section yet.
2. **The C40 settlement is applied faithfully.** W-01 to W-10 each match the diff of §8.33, §8.32 C5, the index and the change log. As W-10's row says, the C39 log row keeps its old wording as a record.
3. **C5 contradicts a settled result of the book (high).** "The kernel never stores answers" rests on an estate line (S0206 L705). The book's own position is the restated CON-0134/CON-0141: "never stored" is INVALID as an absolute, and what holds is that a stored derived value has no standing.
4. **C1's "the contradiction is only on merged or bypass records" is not shown (high).** S0212's "failure" has two readings that C1 leaves out. In one of them the book differs inside a single kernel: an accepted tie moves Q1's answer from T to U.
5. **Two material omissions (high).** First, S0212's carry-forward sentence ("prevents unsupported knowledge from becoming trusted knowledge") conflicts with the decided promise (b). Second, the content of the only new candidate, INV-KOS-007 ("provenance of knowing"), is not examined; only its number is.
6. C2 and C6 stand. C3 and C4 need restating (W-05 to W-07).

Independence here is process independence only: I did not write §8.34 or the sources.

## Findings (ranked)

| W-nn | Finding | Sev. | Evidence | Correction | Resolved when |
|---|---|---|---|---|---|
| W-01 | **C1 reads S0212's "failure" only as a breach of D.** S0212's failure is a failed *synthesis* or *transformation* (L106–132, L441–447), and in S0212's mapping synthesis is Assessment (L151). I see three readings. **(a) A content-level failure:** the kernel has no value for content (§9.10 L35), so there is nothing to preserve or refuse. **(b) An unauthorised act:** PL-2 has no authorisation guard for adoption, revocation or annulment, only for single-use, transfer, removal and designation (L128–136). So the act is *appended* and evaluates F. The record advances and the values stay the same: agreement in the values, not in the record. **(c) A counterexample inside one kernel:** an adoption y is T at cut s1. A revocation of the same item is then appended with the same act time, and no guard refuses ties (PL-2). "Ordered after" is now U (§9.13 L26, L129), so Q1 at s2 is U. S0212 calls exactly this outcome the "wrong model" ("corrupt / rollback / **uncertain** state", L116), while the book keeps the U on purpose (CON-0221; PL-6). So "only on merged or bypass records" is false as a quantifier. (I worked (c) from the text; I did not run it in `ce_model`.) In addition, (ii) quotes the phrase "closed under prefixes" from §9.13 L69, which PL-2 L143 declares superseded by L62. | high | S0212 L116, L151, L441–447; §10.5 PL-2 L123–143; §9.13 L26, L62, L69, L129; PL-6 L175 | "On a breach of D: (i) and (ii) as stated. On an unauthorised act: appended, F, values unchanged (agreement in values). On a tie or cycle: an accepted entry turns T into U inside one kernel, which S0212 L116 rejects. **That difference is by design** (CON-0221)." Quote L62, not L69. Add (c) as the strongest rival in §8.34.4. | C1, plain-language item 1 and the challenge restated |
| W-02 | **C5 (ii), "never stores them", contradicts CON-0134 (INVALID as stated) and its restatement CON-0141 ("a stored derived value has no standing").** "Never stored" as an absolute is false (§9.07 L106; §9.08 L69: "the absolute 'never stored' … is not needed"). The cited support, S0206 L705, is about EKS, not the book's kernel. | high | CONCEPT-REGISTER CON-0134, CON-0141; §9.07 L106, L112; §9.08 L69; S0206 L705 (I checked it) | "The kernel derives answers from the record. A stored answer has no standing (CON-0141; §1.14.1 'derived'). S0213's 'does not store answers' fits in that sense only. S0206 L705 is a parallel within the lineage, not a home." Plain-language item 5 likewise. | C5 and item 5 restated |
| W-03 | **Missing: S0212's central sentence** "KnowledgeOS … prevents unsupported knowledge from becoming trusted knowledge" (L535), together with "confidence matters" (L528). Under the decided promise (b) the book does **not** prevent this. An authorised adoption of an item with no evidence gets T, and its well-foundedness is shown as "not attested" *without a guarantee* (§10.1 L45; T10-01). S0212's sentence is closer to (c). | high | S0212 L524–535; §10.1 L45; §10.5 PL-13 L253 | Add a claim or coverage row: "S0212's carry-forward sentence is promise (c)'s. The book decided (b): recognition does not require support, which is displayed beside it." | row added |
| W-04 | **Missing: the content of INV-KOS-007** (S0214 §12.1; S0213 L401–435). The candidate says to preserve the *means* by which something became knowable (Claim, Evidence, Method, Validator, Authority), and S0213 calls it "almost a missing KnowledgeOS primitive" (L107). C6 handles only its number. The source's own admission also goes unrecorded: EKS evidences the "who" more strongly than the "how" (S0214 L561, my item S0214 4). Book homes to test against: D's actor, bases and recorder (§9.13.2), and evidence entries with their producer (§9.18). D has no "method" field. | high | S0214 L555–561; S0213 L107–136, L401–435 | Add: "The 'who' is D's actor and recorder, assumed (L1). The 'method' has no field in D; its home would be W (gap G1). An extension of §8.33.3's provenance row; not a new concept." | coverage or claim added |
| W-05 | **C3: "S0213 does not say so" is false.** The example C3 quotes gives authority as "Authorized person/system accepts it" (S0213 L385–387; S0214 L409–411). Only the diagram (L490–502) is unqualified. "A person's act" is also narrower than the book: AU acts under grants, and robots included (§8.25). §10.4 R1 does not contain "evidence informs"; "informs" is a relation type at §10.4 L24. | normal | S0213 L383–389, L490–502; §10.4 L24, L54; §8.25 | "The tension is in the diagram only. The source's own example makes the step an accepting act by an authorised actor (person or system), which is the book's R1." Cite L24 for "informs". | C3 and item 3 restated |
| W-06 | **C4: "publication gate = acceptance of an authorised act at append (PL-2)" is wrong.** Acceptance checks membership of D and the per-kind guards; it does not check that an adoption is authorised. The gate in S0212's sense is CE*'s T (PL-6). C4 also keeps only S0212's "not a dimension" ruling. It drops "probably a major kernel concept" (L256) and Publication in the "kernel protects" list (L515), which are my items 8 and 14 and are tensions inside S0212. Finally, "always historical" holds trivially of any answer at a cut. | normal | PL-2 L123–136; PL-6; S0212 L218, L256, L515 | Replace with "the gate is recognition (Q1 = T), not acceptance". Record S0212's internal tension. Mark the agreement as trivial at the level of a cut. | C4 restated |
| W-07 | **C5: "'impossible' fits the guard class only" is too narrow.** There is a third class: impossible **by construction** of Φ. CE* reads only acts and never evidence (§9.18 (c1), a proposed premise). So S0213's forbidden "Document → Authority", "Confidence → Truth" and "Popularity → Validity" (L504–525) cannot occur in standing. No guard prevents them; they simply are not inputs. | normal | S0213 L504–525; §9.18 (c1) (premise status per C40 W-03) | "Prevented by guard, made impossible by Φ's inputs (under (c1)), or detected (R3a)". | C5 restated |
| W-08 | **C2's S0206 facts.** I checked them against S0206 itself (allowed list; sha256 matches): L404 (63/126 prose only; "resolvable for at most half"), L405 (duplicate grant id), L718 (identity uniqueness: recommendations content-derived; grants not unique), L733 and L1150. **All are correct.** But the book does **not** record them at §8.32: it records only L404, and only at §8.33.3. The home table presents them as homes when they are new reads of a source outside this window. On fairness: S0214 §12.1 ties the EKS evidence to H-ZERO-002 and INV-003, not INV-001 (that tie is in §4), and it concedes the "how" is weak (L561). "*The* mechanism" by which rows grow is n = 1. The third relay is a mis-mapping by analogy (Pramā is valid knowledge), not a homonym. | normal | S0206 L397, L404–405, L705, L718, L733, L1150; S0214 L205–209, L561 | Mark the S0206 lines as new Src. Add S0214's concession. Write "a mechanism", and "homonym, then analogy". | C2 restated |
| W-09 | **C6, additions:** (a) S0212 labels candidates "(canonical)" while stating "No promotion" (L13, L425); (b) §14.3 says "(HPA, adopted)" against "nothing adopted" (my item 11); (c) the modal ladder is applied twice more in the same lineage (S0212 L470; S0214 L457), both times after the fact, which confirms §8.33 W-07 ("applied downstream"). It is still one line of support. | normal | S0212 L7, L13, L21, L425, L470, L551; S0214 L457 | Add (a) to (c). Point (c) to §8.33 C6. | C6 updated |
| W-10 | **Lineage citation slip:** "S0214 … (L14, L264, 'message UM-21')". L264 is blank; UM-21 is at L583. | normal | S0214 L263–264, L583 | L583 | fixed |

## Claim verdicts

| Claim | Verdict | Reason (scope) |
|---|---|---|
| C1 | **PROVISIONAL** | (i) and (ii) are right for a breach of D. "Only on merged/bypass" is refuted by the tie case (W-01). |
| C2 | **VALIDATED** in substance | The S0206 facts are verified. Wording and the source's concession need fixing (W-08). |
| C3 | Agreement **VALIDATED**; "S0213 does not say so" **INVALID** | The source's own example (W-05). |
| C4 | **PROVISIONAL** | The PL-2 mapping is wrong; the gate is Q1's T (W-06). |
| C5 | (i) prevent/detect **VALIDATED**; (ii) "never stores" **INVALID** as stated | Contradicts CON-0134 and CON-0141 (W-02). The guard class is not exhaustive (W-07). |
| C6 | **VALIDATED** (Int) | Two series. Additions per W-09. |

**Recorded challenges.** C1: inadequate. Its rival is the earlier cut; the strongest rival is a tie inside one kernel (W-01). C2: adequate. Missing challenges: C3 (the source's own example), C5 (CON-0134).

**R2 (scope 08.34; tier A: 0; tier D judged one by one).** Ordinary words, acceptable: knowing, collection, expression, pipeline, lenses, stronger, declare, together, separation, example, agree, rival, keep. "violation" is used for laws (R3a): acceptable. "merged" means merged applications (§9.15/§9.16): acceptable. **"synthesis":** S0212's term, undefined in the book, and equated with an entry (W-01). **"derive":** must carry CON-0141's sense (W-02). **"person":** too narrow for AU (W-05).

## Coverage (my inventory against §8.34)

| Status | Items |
|---|---|
| COVERED | S0212 2, 6, 7, 12, 13, 16; S0213 4, 5, 6, 11, 14 (recorded), 17 (recorded); S0214 1, 2, 3, 6; the lineage note matches mine |
| DISTORTED | S0212 2 (failure as a D-breach only, W-01); S0212 5/8 (Publication, W-06); S0214 4 (concession dropped, W-08) |
| MISSING | **S0212 15 (W-03, high); S0213 2/7 + S0214 3–4 (INV-KOS-007's content, W-04, high)**; S0212 1 (the chain Reality ≠ Observation ≠ … ≠ Decision; home §8.32 C3); S0212 3 ("independently rediscovers": an independence claim inside one lineage, §8.31 C4); S0212 9 (Unknown → Known Unknown; point to §8.33 C1, the open world); S0212 11 ("adopted", W-09); S0212 14 and S0213 14 (the kernel lists disagree: 7 vs 8 vs 6 items; Meaning, Relationships and Validation method dropped); S0213 9 (three pipeline variants that do not agree; relevant to C3); S0213 12 ("not more dimensions, separation requirements", F13); S0213 15 (identifiers cited second-hand); S0214 8 (ends on Part A's eight-item list) |
| JUSTIFIABLY EXCLUDED | the Pramāṇa terms and Aparā/Parā vidyā as reasons; S0212's placement note |
| Source defects (none relied on) | S0213 L601 "Five lenses" against six plus EKS at L1050–1056; S0212 §14.4 "No INV-005..N" against §11 |

New topics or forks: none. W-03 belongs with T10-01, decided (b). W-04 belongs with gap G1 and T10-18.

## Limits
- I read §9.13 by grep and sed (L26–69, L129–135, L206, L275), §10.5 L120–289, §8.32 whole and §8.34 whole. I checked §9.18, §9.16, §9.07, §9.08 and §10.4 by grep. I did not open §8.25, so its title is the only basis for "robots act under grants" (Int).
- The tie case in W-01 is worked from the text, not run in `ce_model.py`.
- In S0206 (allowed list; sha256 match) I read only L395–408, L700–722, L726–736 and L1148–1152. One line in that range names a private person; I have not reproduced it.
- I did not open the C37 folder, S0208–S0211 or the strategy files. Search misses are not absence.

## Files opened
Under `/home/d0f38614-c3a6-41d3-9952-7f59ad699b2d/roshyara/personal/nrna1/docs/knowledgeos/knowlegeos_manual_theory_20261018/`:
- `06-theory/manuscript/review/C41-S0212-S0214/`: BRIEF-W-STEP2.md, STEP1-INVENTORY.md, SHA256-STEP1.txt (cat), STEP8-AND-CHECKS.md
- `06-theory/manuscript/review/C40-S0208-S0211/`: SETTLEMENT.md, REVIEW-REPORT.md
- `06-theory/manuscript/chapter_08/08.34-failure-does-not-fall-back-and-a-homonym-grows-by-relay.md` and `08.32-what-the-estate-cannot-evaluate-the-book-takes-as-input.md` (both whole)
- `06-theory/manuscript/chapter_10/10.05-published-language.md` (L120–289)
- `06-theory/manuscript/chapter_09/09.13-the-evaluator.md`, `09.18-evidence-in-the-record.md`, `09.16-reassessment.md`, `09.07-boundary-test-the-evaluator.md`, `09.08-is-history-a-primitive.md`, `09.04-invariants-that-cannot-fail.md`; `chapter_10/10.04-context-map.md`, `10.01-subdomains.md`, `chapter_10/TODO.md`; `CONCEPT-REGISTER.tsv` (all by grep or sed)
- The `git show 3e7e8c598 -- 06-theory/manuscript/chapter_08/` output, saved by the harness to `/home/nab-raj.roshyara@dg-nexolution.de/.claude/projects/-home-d0f38614-c3a6-41d3-9952-7f59ad699b2d-roshyara-personal-nrna1/16a0ac97-2df1-4d21-8fca-c43f5de5b1e2/tool-results/bn1a49oqd.txt`
- Corpus: `/home/d0f38614-c3a6-41d3-9952-7f59ad699b2d/roshyara/personal/nrna1/docs/knowledgeos/architecture/20260821-2140-EKS-Current-Architecture-Baseline-Stage-1.md` (S0206; checked against CORPUS-ALLOWED.tsv by grep; line ranges listed above). From step 1: S0212, S0213 and S0214 (not reopened).

## Commands run
- `ls` of the C41 folder, 06-theory, the manuscript, chapters 8–10, the review folder and tmp/tools; `ls` and `wc -l` of the C40 folder
- `cat SHA256-STEP1.txt; sha256sum STEP1-INVENTORY.md`; `wc -l` on the new files and on Ch. 9 and Ch. 10; `git log --oneline -6`
- `git show --stat 3e7e8c598`; `git show 3e7e8c598 -- 06-theory/manuscript/chapter_08/`
- `grep` on 09.13 (availability, v9, fail-closed); 10.05 (PL-2/6/12/14); 10.04 (R1); 10.01 (promise (b)); 09.18 (contain, headings); ch. 9 and 10 (informs, stored/persist/recomputed); the book, register and map for S0206 line citations; `grep -P "\tS0206\t"` on CORPUS-ALLOWED.tsv; CON-0134/0141/0143/0221 in the register; T10-01/T10-18; R3a
- `sed -n 28,33p` on 09.13; `sha256sum` and `sed` on S0206 (L395–408, 700–722, 726–736, 1148–1152)
- `python3 -I tmp/tools/undefined_terms.py --scope 08.34` (run twice and filtered)
- `python3 -I tmp/tools/inbound_citations.py "§8.34 C1" "§8.34 C3" "§8.34 C4" "§8.34 C5" "§8.34 C2"` (0 inbound)

I edited nothing in the repository and sent nothing to an outside service.
