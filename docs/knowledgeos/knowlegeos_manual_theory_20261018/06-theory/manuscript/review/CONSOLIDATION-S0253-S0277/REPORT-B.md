CONSOLIDATION S0253–S0277: REPORT B

Object: the frozen copy `…/review/CONSOLIDATION-S0253-S0277/BOOK-UNDER-REVIEW/` only. Sections: Ch. 1 `01.14`, `01.15`, `01.16`, `01.17`, `01.99`, `TODO.md` (copy). Line numbers are the copy's.

## Summary (15 lines)
1. All six files read in full. Quote check: 26 quotations found, 0 not found. R1: no flag in my sections.
2. Most cited corpus lines check out (S0011/12/14/15/17/18/19/20/21/24/25/96/97/100/128/129). Two anchors are wrong (B-16).
3. Most of §1.14–§1.17 rests on V3, AB, IM, CF, AMS, RS, IA, PC, DE, SI and GKC. These live in `docs/knowledge_tranfer/*`, which SCOPE §2.4 forbids. So their fidelity is **not checked**. This is the largest limit of this review.
4. Three high findings:
   - B-01: §1.14.1-8 properties 2–3 are called a "Proof", and a countermodel defeats them (consumption by a further adoption).
   - B-02: §1.16.6's "must be one of three" reference forms rests on a hidden premise. It contradicts open fork F18, and §6.6 C1 depends on it.
   - B-03: the Ch. 1 TODO statuses do not reflect the decisions recorded in §9.13 (T1-03, -12, -13, -18, -20, -21).
5. Normal findings:
   - stale or overstated status of the formal model against §9.13.5;
   - three Ded steps with missing premises (1.14.1-6, 1.14.1-7, 1.14.3-2);
   - "partial order" with a 2-cycle (1.14.4-7);
   - "protect = evaluation" against P-E (§1.17.1);
   - a stale "absent" in §1.17.5;
   - homonyms "well-founded" and "valid", and the symbol κ;
   - undefined identifiers L-RT and "brief 4/6";
   - a dangling T1-49;
   - CON-0015's reason mismatched with the register;
   - a defective References table;
   - the answer spec not linked to T10-21.
6. Concepts: CON-0013 and CON-0014 VALIDATED (by resolution mode only). INVALID cores CON-0012, 0023 and 0054 are restated correctly. CON-0015 is restated with a different reason (B-15). No PROVISIONAL concept is homed in my sections.
7. Process independence: I am a fresh AI session with no other reviewer's report. This is process independence only, not independence as people or as model family.

## Findings

| ID | Section | Finding | Sev. | Evidence | Correction | R3 lines (disposition) | Resolved when |
|---|---|---|---|---|---|---|---|
| B-01 | 01.14 L232–235 (1.14.1-8 props 2, 3) | "Proof: T ∨ anything = T" assumes that adding an adoption act changes no other disjunct. **Countermodel:** x adopts c with B={g} and validity [t0,∞); later x′ adopts c citing single-use g, with validity [t2,∞), t2>t, and consumes g before t; consumption ∈ P(kind c). Condition 4 excludes only endings caused by x itself (L198), and case 4 confirms that another act's consumption matters (L242). Result: x fails condition 4, x′ fails condition 2, so current(c,t) goes T→F. Property 3 (narrowing only by revocation) falls the same way. Proof-step judgement: inadequate, premise missing. | high | L198, L242, L234–235 | Add the premise "the added act ends no grant in another adoption's basis (or consumption ∉ P)". Note that CE* puts consumption outside the currency clause (§9.13 L35, v9), so the defect does not carry into CE* (not fully checked). | 1.14.1-8: 0 claim-level lines; 27 section-only lines, none citing the property (unaffected) | head and body restricted, or marked superseded with a pointer to §9.13 |
| B-02 | 01.16 L112–117 (§1.16.6) | "A reference that carries meaning must be one of" pinned / live+detect / live+forbid. The step "changes what c means **without any act on c**" assumes the change must come from an act on c. A change to d is itself an act, and c's adoption may declare live reading. Fork F18 (map L137) keeps "operating rules need resolution at use" open, so the trichotomy is not exhaustive. | high | L112; THEORY-MAP L137 | Scope the trichotomy to references whose meaning is fixed at the act. Name "live by declared intent, resolved at use" as a fourth form, pointing to F18. | §6.6 L43 **affected** ("only with detection or prohibition"); §6.6 L22, L82, BOOK-OUTLINE L171, map L237, §0.7 L20, 05.06 L98, 09.07 L177, 09.08 L151 unaffected | §1.16.6 and §6.6 C1 restated |
| B-03 | TODO.md L26, 35, 36, 40, 42, 43 | Rows still marked Open / Awaiting Human Decision, although the copy records decisions within the kernel:<br>· T1-12: consumption admitted and outside the currency clause (§9.13 L34–35, L42);<br>· T1-21: ties unordered, giving U (§9.13 L265, §9.13.9);<br>· T1-18: C1 decided 2026-10-20;<br>· T1-03: υ fixed within Θ_f (01.14 L206);<br>· T1-13: "every act under a single-use grant is a use" (§9.13 L40–42);<br>· T1-20: closed world within the kernel (§10.5 PL-6). | high | rows cited | Annotate each row: "decided within the kernel (where, date); open outside". | the tool finds no inbound lines for T1-12, -18, -21, -03 | rows annotated |
| B-04 | 01.14 L208, L210, L221, L242, L510 | Contradictory status of the old model:<br>· the L208 box says the defects are "repaired in §9.13.5, defect by defect", but §9.13.5 leaves defect 2 (propagation), defect 3 (reasons for F) and defect 5 (σ) open;<br>· L510 says "tracked, not repaired";<br>· case 4 (L242) and the tie remark of L221 are not marked as overtaken by §9.13 (consumption v9; ties U). | normal | §9.13.5 table | Correct L208 and L510 defect by defect. Mark case 4 and L221 as superseded within the kernel. | 1.14.1-7: 3 lines (01.99 L51, L53, map L258) unaffected | done |
| B-05 | L113 (1.14.1-6) | "Cannot be a general law" rests on §1.13.3(d)'s *lean* that delegated grants end with their parent. §9.13.5 defect 2 leaves propagation open. Inadequate: premise missing. | normal | L113; §9.13.5 row 2 | "Need not be … ; it is a per-kind policy **if** delegated grants end with their parent." | 0 claim-level lines | restated |
| B-06 | L200 (1.14.1-7) | The step "under ρ = at-the-time there are no cycles; one pass" needs each ending's authorization to be judged in its *own* at-the-time view. That is a nested record view, which defect 4 (L214) declares unspecified. Premise missing; case 13 inherits it. | normal | L200, L214, L253 | State the premise. | as B-04 | stated |
| B-07 | L311–316 (1.14.3-2) | The laws are stated for "no role", but the table gives PO/ARB no prohibition (L303), and root acts are authorized by stipulation (L161). So self-acceptance by the root role is not excluded, and "every acceptance … names a different actor" overreaches. | normal | L303, L161, L316 | Scope the laws to roles below the root, or add the premise. | 14 claim-level lines (§8.24 L20–52, §8.25 L29, L98–99, §8.27 L54, map L194, L258): unaffected (non-root roles) | scoped |
| B-08 | L361–365 (1.14.4-7) | "The arrows form a partial order", yet knowledge ⇄ assessment is a two-way edge, which breaks antisymmetry. The claim holds only for a standing-flow subrelation without that edge (compare T1-42). | normal | L361, L364 | Restrict the claim to standing flow, or say "preorder". | 0 claim-level lines | restated |
| B-09 | 01.17 L9–14 | "*Protect* … is evaluation (never standing)". But an aggregate protecting its state *rejects commands* (IA's test, L27), and a rejection is a block, a normative effect under P-E (1.14.5-2/-3). | normal | L10, L27; 01.14 L418–422 | "Protect" = an enforcement point, admissible only under 1.14.5-5 given P-E; "reason" = evaluation. | §1.17.1 inbound (BOOK-OUTLINE L112, §5.1 L25): unaffected | restated |
| B-10 | 01.17 L111 | The table says may-read is "**absent**", which contradicts L103 (§1.7 already has vis(d) ⊆ ∩ vis(sᵢ)). | normal | L103, L111 | "§1.7's visibility bound; a governed read rule absent (OQ-25)". | map L40 unaffected (it says the same) | fixed |
| B-11 | 01.17 L59–63; 01.14 L121 | Homonyms:<br>· "well-founded(ness)": in §1.17.2 a property of an **act** (its reasons cite evidence); in §0.1, §9.18 and §10.2 candidate 2 (admitted at S0, T9-19) a property of a **claim**; also §9.13's "well-founded model".<br>· "valid": in §1.17.2 it means authorized; §9.10 L149 has "valid = current ∧ well-founded"; §9.7 C2 maps S1414's "valid transition needs evidence" onto §1.17.2. | normal | cited lines | Qualify "well-foundedness (of an act)" and state its relation to the claim-level classes; qualify "valid (authorized)". | 09.07 L123 **affected**; 09.10 L67, L149 qualifier; 10.03 L44, L117, 00.06 L14 unaffected | qualified |
| B-12 | 01.16 L49; 01.14 L178, L180 | "Limitation L-RT", "brief 6" and "brief 4" are undefined in the book (L-RT appears only in old review notes). They mean the record view ρ (reserved decision 2), reserved decision 6 and reserved decision 4. | normal | grep of the copy | Replace with those names. | none | replaced |
| B-13 | 01.14 L173; 01.17 L115, L120 | Symbol collision: κ is both the unknown-citation parameter and the governed classification (Ch. 2 §2.8.6). | normal | lines cited | Rename one of the two. | — | renamed |
| B-14 | Register CON-0014 | The register cites "T1-49", but no TODO file in the copy has that row. Checked against the source: U-DM-1 exists (S0017 L107), and §1.17.1 L22 cites it as open. **Content missing, not only a stale pointer.** | normal | grep of the copy; S0017 L107 | Add T1-49 (U-DM-1), or repoint the register. | CON-0014: 6 concept lines unaffected | row exists |
| B-15 | 01.14 L502; TODO L74 | CON-0015's INVALID ground is given as "not verifiable (no governing record)". The register's restated ground is "definition fails: two senses; no governing record only for activity freezes". | normal | register row | Carry the register's reason. | CON-0015: 4 lines (L522, TODO L74, register, BOOK-OUTLINE L113): text update only | aligned |
| B-16 | 01.14 L165, L502 | Wrong anchors:<br>· "S0024 calls genesis architectural (L155)": L155 does not say so; L36 does ("The one ARCHITECTURAL gap").<br>· "frozen … status [S0016 L43]": L43 does not name frozen; L54 does. | normal | source lines | L36; L43 and L54. | — | fixed |
| B-17 | 01.14 L526–553 | References table:<br>· S0100 (cited at L280) missing;<br>· S0129 row says "1.14.2-2", though that evidence moved to 1.14.2-1 (01.99 L53);<br>· IM row says "C12", but only alternatives C1–C8 exist;<br>· S0097 L41's use in 1.14.2-1 not listed. | normal | table | Fix the four rows. | — | fixed |
| B-18 | 01.14 L125–130, L223–230 | The answer spec does not link to T10-21's display rule for F ("not recognized as current authority", §10.5 PL-6). "Your decision needed" has no place among the three headlines. | normal | §10.5 L174 | Add a pointer, and place "your decision needed" as a meta-output. | — | linked |

## Concept verdicts (SCOPE §2.2, homed in my sections)

| ID | Verdict | Reason (scope) |
|---|---|---|
| CON-0013 engineer decision | **VALIDATED** (second, independent) | Defined by resolution mode: "apply a governed procedure" (S0017 L36). There is a governing record, the canon catalogue (S0017 L127), which CON-0012 lacks. "Catalogued in advance" is an observation, not part of the definition. FREEZE (S0017 L55) shows the two modes are not exhaustive, which does not defeat either one. |
| CON-0014 governance decision | **VALIDATED** (second, independent) | Defined by resolution mode: an authority act (S0017 L37), consistent with §1.6. "Recorded only in a log (77)" is a dated observation and should leave the register's meaning field. The T1-49 pointer dangles (B-14). |
| CON-0012 | INVALID core restated correctly | L18, L23 and T1-51 agree with the register. |
| CON-0015 | INVALID: **reason restated differently** | B-15. |
| CON-0023 | INVALID core restated correctly | L402, L522. |
| CON-0054 | INVALID core restated correctly | L163–166 and L519 (n = 1; not a root-act analogue). |

PROVISIONAL: none homed in 01.14–01.17. CON-0033 is homed at 1.14.4-10 and listed at L522, but SCOPE §2.2 omits it; I give no verdict on it.

## Coverage (TODO rows and pointers; details in appendix A)

| Item | Status | Home |
|---|---|---|
| T1-03, -12, -13, -18, -20, -21 | decided within the kernel; rows stale | §9.13, §10.5 (B-03) |
| T1-02, -04, -05, -46 | partly addressed | §10.5 words and T10-15; CE* (§9.13 L122, L129); C1 |
| T1-14, -15, -17, -19, -47, -50, -51 | still open, referenced later | §9.13.5 defect 2 and §8.28; F2; map L226 and §8.51; F32; §2.4; §3.10; §5.1 |
| T1-31, -36, -53; SEQ-0007/8/9/11 | resolved | as recorded |
| T1-49 | **missing row** | B-14 |
| the other rows (T1-01, 06–11, 16, 22–30, 32–35, 37–45, 48, 52, 54) | open; **not individually searched**. A search miss is not absence. | — |
| Pointers from my sections to later chapters | all exist and say what is claimed (§9.7, §9.8 H4, §9.13–§9.16, §8.6.2, §8.9–§8.11, §6.5 C2, F22, F25, F26, §5.1, §3.5, T2-01, T4-02). Stale in status only: §9.13.5 (B-04) and F26 at ties (B-04). | — |

## R2 (`--scope 01.14,01.15,01.16,01.17,01.99`; TODO.md is not scanned by the tool)

| Word | Judgement |
|---|---|
| annulled, annulling | defined (annul, "void from the start", L149, L153); tool miss |
| pinning | defined §1.16.6 L108; first use at L438 is a forward "cf."; acceptable |
| snap | defined 01.16 L65 |
| RK-1, ALLOC-4, DP-2 | glossed or defined at first use |
| undeterminable, worksheet, preventable, mechanize/-ing, fetche (stemming artefact), unpoliced, dialog, unexplainable | ordinary English or quotation; no defect |
| A-03, A-06, I-01, I-05, F-02, F-14 | review-finding provenance tags, undefined; I-01 and I-05 can be misread as invariants I-1 and I-5 (appendix) |
| tier D (about 80 words) | ordinary English, except: suspension (an ending kind; its placement is open, 1.14.1-2 finding 2, known); monotonicity (two senses in one head, L232, qualified "in bases": acceptable); drift (silent drift and naming drift, qualified); admissible (block sense defined at 1.14.5-5; the source sense in genesis); attack and tuple (glossed). No new defect. |
| Manual (tool blind) | L-RT, "brief 4/6" (B-12); κ (B-13) |

## Coverage of sections
01.14 (554 lines), 01.15, 01.16, 01.17, 01.99 and TODO.md: **read in full**.

## Out of scope (one line each)
- 09.18 L3 still says "Nothing here is adopted … proposed kinds", after T9-19 (for reviewer D).
- 10.03 L44 cites "§1.17.2 L22–25" for IA's test; in the copy it is at L27.
- §8.13 L60, L89 and §9.14–§9.16 use "C19, T1-nn" reviewer IDs that collide with Ch. 1 TODO IDs (e.g. T1-16); this bears on T1-35.
- ρ is reused for the recusal relation in §8.09 F35.

## Appendix
**A. Proof-step judgements.**
- 1.14.1-4: adequate as conditional (premise stated, L105).
- 1.14.1-6: inadequate, premise missing (B-05).
- 1.14.1-7: a sketch, declared as such; the at-the-time step lacks its premise (B-06).
- 1.14.1-8: property 1 a sketch (as stated); properties 2–3 refuted (B-01); property 4 adequate.
- 1.14.5-2: adequate given P-E, except a typing premise: "currently authoritative" is defined for claims, and an act authorizing a block is not shown to be one.
- 1.16.6 drift: inadequate (B-02). 1.16.6 retention: adequate, with permanence correctly withheld.
- 1.16.7: adequate as scoped. 1.17.2 and 1.17.3: adequate.
- 1.14.4-7: inadequate (B-08). 1.14.3-2: inadequate, premise missing (B-07).

**B. Lower priority.**
- 01.99 rows L37–41 (dated 10-18) sit after rows dated 10-19.
- 01.14 L3 says the revision history is in 01.99, but under RK-1 later revisions are recorded elsewhere.
- 01.15 L3: "17 validated concepts" is V3's word, not this book's verdict.
- Index row 1.14.1-12 has no label.
- Stray bold markup at L115.
- SEQ: §1.14 uses §1.16.2, §1.16.6, §1.17.2–3 and Ch. 2's E-codes, S-3 and κ ahead of their homes, all with pointers; only κ is unglossed.

**C. Limits.**
- Not checked: V3, AB, IM, CF, AMS, RS, IA, PC, DE, SI, GKC, EP01, RQ-002 (forbidden by SCOPE §2.4); `session-resolve.php`, `design-check.sh`, `.gitignore` (not on the allowed list); the 77-rulings log.
- TODO rows were checked only as listed above.
- No window inventory was opened (none covers Ch. 1).

**D. Process disclosures.**
- My **first** call of `undefined_terms.py` passed `--help` without `--root`. The tool ignored `--help` and ran on the default root, the **working book**. I discarded that output and used none of it. Every later tool call passed `--root <copy>`.
- Search output (grep, and R3's lines) incidentally displayed lines from the copy's `manuscript/BASELINE-REVISIONS.md` and from the copy's `chapter_01/review/` notes (REVIEW-round4-note.md, REVIEW-ch01-findings-note.md, CH-01-final-review.md). I did not open those files and used them as evidence only to confirm that L-RT appears nowhere else.
- I opened no working chapter, map, register, outline or BASELINE file, nothing in `00-access/`, and no git history.

**E. Files opened.**
- Review folder: COMMISSION-B.md, REVIEWER-NOTE.md, SCOPE.md.
- `03-methodology/REVIEWER-INSTRUCTIONS.md`.
- Copy, read in full: chapter_01 01.14, 01.15, 01.16, 01.17, 01.99, TODO.md.
- Copy, extracts by sed: 09.07 L121–126; 09.10 L60–70, L145–152; 09.13 L1–15, L258–343; 09.18 L1–12, L20–30; 10.03 L40–46, L115–118, L310–322; 10.04 L38–41; 10.05 L26–36, L168–176; 00.06 L8–16; 06.05 L54–67; 08.09 L76–79; 06.06 L40–45; THEORY-MAP L137; CONCEPT-REGISTER rows (awk).
- Copy tool: `tmp/tools/book_search.py` L1–30.
- Lane tools (read-only): `undefined_terms.py` L1–80 and grep; grep of `inbound_citations.py`, `register_home_check.py`, `corpus_quote_check.py`.
- `CORPUS-ALLOWED.tsv` (head; awk lookups).
- Corpus, by line only: S0005 L85; S0011 L1–8, L10, L49–62, L113, L133 (and grep); S0012 L16, 22, 67, 76–80, 152; S0014 L53, 57, 72; S0015 L9–10, 66, 72, 77, 104; S0016 L43 (and grep "frozen"); S0017 L5, 19, 22, 36–40, 55, 88–92, 107–110, 127 (and grep); S0018 L17, 27, 77, 83, 94, 96, 104–105, 129; S0019 L102; S0020 L24, 28, 89, 100; S0021 L108; S0024 L36, 155, 159–163; S0025 L114–122, 129, 139, 141, 155, 173; S0096 L31; S0097 L41, 45; S0100 L22; S0128 L48; S0129 L147.
- Scratch files: scratchpad `r2.txt`, `r3b.txt`; tool-results `bby8fopmz.txt`.

**F. Commands run.**
- `ls` of the review folder, the copy and the tools.
- `undefined_terms.py` once without root (see D); then `--root <copy> --scope 01.14,01.15,01.16,01.17,01.99,01.TODO`, twice.
- `register_home_check.py --root <copy>`, twice.
- `corpus_quote_check.py` on the six copy files.
- `inbound_citations.py --root <copy>` on: 1.14.1-6, 1.14.1-7, 1.14.1-8, 1.14.3-2, 1.14.4-7, 1.14.5-14, §1.17.5, §1.17.1, §1.17.2, §1.16.2, §1.16.6, CON-0014, T1-12, T1-21, T1-18, T1-03, 1.14.1, §1.14.6, CON-0013, CON-0012, CON-0015, CON-0023, CON-0054, 1.14.4-3, 1.14.1-2.
- grep searches over the copy for: INV-A3; P-E; reserved decision 1/4/6; may-read; OQ-25; well-found; proposed, not adopted; choice for the Principal; disputed; sealed; accepted by the kernel; evid, ewdr, erst; G1; G7; T1-nn ids (all T1 ids, T1-49, T1-14); L-RT; brief 4/6; C11, C12, C13; S0100; currency clause and consum (in 09.13); headings of 09.13 and 09.16; §8.6.2; C2 in 06.05; C7 in 08.10; C9 in 08.09; L4 in 09.15; 210 in 09.07; F25 and F26 in the map; section-level references §1.17.5, §1.17.1, §1.16.2, §1.16.6, §1.17.2; pointer extraction from my sections to Ch. 2–10.
- A loop checking that every cited T1 id has a row in TODO.md.
- awk lookups on CORPUS-ALLOWED.tsv and on the register.
- sed line prints of the corpus files listed in E.
