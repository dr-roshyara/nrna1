CONSOLIDATION S0278–S0302: REPORT A

Reviewer A. Object: Ch. 2 of the frozen copy (`02.00`, `02.00a`, `02.01`–`02.11`, `02.99`, `TODO.md`), each read in full. `<copy>` = `W/06-theory/manuscript/review/CONSOLIDATION-S0278-S0302/BOOK-UNDER-REVIEW/`.

## 0. Summary (15 lines)
1. Ch. 2 is largely sound within its stated scope. The core results hold: set-wise conflict (§2.3 d), partition over-approximation (§2.6) and the containment rule (§2.8.4). I re-derived the first two by hand and re-ran containment on larger finite cases than the book did.
2. Two formal statements in §2.8.4 are wrong as written, and I have countermodels for both. The set-overlap rule gives UNKNOWN for some sets whose overlap is determinately empty (F2). "UNKNOWN, never CONSISTENT" fails for rules with identical effects (F3).
3. §2.3 L68 still calls CON-0192 "provisional". The register, §9.14 and THEORY-MAP L413 record it as VALIDATED at S0201–S0225 (F1). BOOK-OUTLINE L177 carries the same stale word.
4. The strongest normal-severity theory points are these:
   - §2.5's second ground for rejecting priority numbers is circular with §2.8.4a(2), and the criterion it uses is ambiguous (F5).
   - The ground-1 scope leaves out the case where the author is the competent authority (F6).
   - I-4 is cited in a circle between §2.5 and §2.10, and the reading cited is not in I-4's text (F7).
   - The equality test for exceptions tests scope, not effective region (F4).
   - Shrinking an app-profile silently broadens applicability, and §2.8.6 never analyses it (F12).
5. Concept verdicts for the Ch. 2 homes:
   - CON-0056 and CON-0063 VALIDATED, each with a stated scope.
   - CON-0082 and CON-0083 PROVISIONAL.
   - Five INVALID cores (CON-0034, 0040, 0055, 0061, 0062) checked as restated; they hold.
   - Most concepts in SCOPE §2.2 have their home in Ch. 3 or Ch. 1, not Ch. 2. I give no verdict on those.
6. All pointers from Ch. 2 to later chapters resolve. TODO.md has open items in the text that no row tracks, and three rows are stale or inconsistent (F15).
7. Checks I could not make: every `[RM/RS/SC/SD/EV/DE/PC/IM/CF/AB/IA/SI/AMS/GKC/GA …]` line anchor (about 150 citations) lies in `docs/knowledge_tranfer/*` or outside the allowed list. These are not verified.
8. Process independence: this is process independence only. I saw no other reviewer's report and opened nothing outside the commission.

## 1. Findings
Severity is critical, high or normal. No critical finding.

| ID | Section | Finding | Sev | Evidence | Correction | R3 lines (disposition) | Resolved when |
|---|---|---|---|---|---|---|---|
| F1 | 02.03 L68 | Status stale: "provisional, CON-0192". Register row is VALIDATED (CONS-S0201-S0225, within scope). §9.14 R1 carries `[CON-0192 · validated]`. THEORY-MAP L413 lists CON-0192 as validated at S0201–S0225. | high | Register CON-0192 `status`, `reviewed`; 09.14 L47; THEORY-MAP L413. R1 does not catch it because the tag in 02.03 has no `·` form. | Replace "provisional" with "validated at S0201–S0225, within its scope". | 02.03 L68 (itself, correct). BOOK-OUTLINE L177 says "provisional" (same stale word; reviewer C's area, correct together). 02.06 L13 cites §9.14 with no status (leave). | The two lines agree with the register. |
| F2 | 02.08 L85 | "Overlap of a set of rules": the rule is wrong for n ≥ 3. It reads "some side ∅ → false; otherwise unspecified side → unknown". Counterexample on one dimension: X₁={a}, X₂={b}, X₃=⊥. The specified sides already have empty intersection, so under the book's own supervaluation (L77) the overlap is false for every completion. The rule gives unknown. | high | My exhaustive check `cons2-a-overlap.py` (V={a,b}, one dimension): n=2 gives 0 mismatches of 25; n=3 gives 6 mismatches of 125 (e.g. {a},{b},⊥: rule U, supervaluation F). The book's 625-case check at L99 covers pairs only. Direction of error: conservative (false UNKNOWN, no false CONSISTENT). It still loses E1 determinacy (§2.8.8). | "some side ∅" becomes "the intersection of the specified sides is empty → false". Unknown only when that intersection is non-empty and some side is ⊥. Extend the L99 check to n=3. | 02.09 L59 cites §2.8.4 for "unknown overlap is UNKNOWN" (result unaffected). 09.14 L65 and L262 cite §2.8 for UNKNOWN and "still open" (do not use the set rule; unaffected). | The L85 rule matches supervaluation on n=3 brute force. |
| F3 | 02.08 L87 | "A conflict verdict over an unknown overlap is therefore UNKNOWN, never CONSISTENT." A universal with a trivial counterexample: two rules with identical effects (MUST φ and MUST φ) whose scopes are ⊥. The overlap is unknown, but no x-clash is possible, so "no conflict" is true under every completion. This contradicts L77's supervaluation and §2.3 (e)'s liveness demand. | high | Counterexample inside the stated scope. The same over-strength appears in 02.09 L59, "an unknown overlap is UNKNOWN, so it does not open the gate". Under the gate's own ∀∃ form, effects satisfiable under every completion open the gate. Conservative (fails closed), so no safety consequence. The defect is the stated "never". | "UNKNOWN unless the effects are jointly satisfiable at every point of the possible overlap; never CONSISTENT on the ground of disjointness." Same change at 02.09 L59. | 02.08 L87; 02.09 L59; 02.03 L77 (e) (consistent with the correction); 09.10 L87 (consistent). | Both lines carry the qualified form. |
| F4 | 02.08 L29 (with 02.11 L153) | The equality test for exceptions tests the wrong object. The rule: an exception whose scope equals its rule's scope "would suspend the whole rule, a repeal by another name", so it needs the rule's own authority. Counterexample 1: an exception with a strictly smaller scope can still cover the rule's whole effective applicability region. Rule scope = {P, Q}, applicable only in P, exception scope = {P}. Then A′ᵣ is empty and the rule is wholly suspended, with strict containment. Counterexample 2: equal scope with a shorter validity is a time-limited waiver, not a repeal. §2.8.2 sends the equality case to "§2.11", but 02.11 L153 restates the chain without the condition. | normal | §2.3 (b) defines effective applicability A′ᵣ with the exceptions removed. The test should read on A′ᵣ. | State the test on the region: an exception that empties A′ᵣ over the rule's validity needs the rule's authority. Add the condition to 02.11 L153. | 02.08 L29, L189; 02.11 L153. | The test is stated on the region and stated once in §2.11. |
| F5 | 02.05 L10–13 vs 02.08 L116–120 | Ground 2 ("independent obligations are not alternatives", §2.8.4a) is cited by §2.5. §2.8.4a(2) concludes the same thing, citing §2.5. Neither supports the premise independently. The criterion "rules compete for one output" is ambiguous. "Use TLS 1.3" vs "use TLS 1.2 only" (§2.3 b) do compete for one output, the version in use. By the criterion, precedence would be allowed there, which contradicts the rejection. The intended reading is "selector vs jointly required obligation". It is not stated. | normal | Read: 02.05 L10–11; 02.08 L116–120. §2.5 is marked "Ded under premises 1 and 2" (L15). Premise 2 has no support beyond RM L215–219 (Src), which is not checkable. | Restate the criterion as: the rules are obligations to be satisfied jointly (conflict ⇒ no x), versus selectors that choose one result. Mark premise 2 Int/Src, not Ded. | 02.05 L10–15; 02.08 L116–120; 02.09 L82 (principle → policy chain: unrelated, not affected). | The criterion applies to the TLS example without contradiction. |
| F6 | 02.05 L9–17 (title and L17) | The result is stated as "numeric priorities set by authors are rejected". Ground 1 needs author ≠ the authority that would have to rule. When the author is that authority (one act issues R1 with a rank and R2 with a rank), ground 1 does not apply and ground 2 depends on intent. L17 admits "enacted precedence". So the title and the first clause are wider than the grounds. | normal | Within-scope counterexample: one competent authority issues both rules with ranks. | Narrow the title or L17: "ranks set by someone other than the competent authority, or not recorded as a ruling on the conflict". State that author = authority falls under enacted precedence. | 02.05 title, L9–17; 02.08 L116; 06.06 L54 cites §2.5 for the total-order question. | The scope sentence covers the author = authority case. |
| F7 | 02.05 L6–7 and 02.10 L110 | Circular citation. §2.5 reads I-4/PK-P7 "via §2.10 (rulings bullet)" as requiring contradictions to coexist "with explicit, visible status", rank as annotation. §2.10 L110 cites §2.5 for the same reading. The A-21 note says the text of I-4 "is not quoted in this book". Ch. 0 §0.5 L10 does give I-4: "Contradictions stay visible: they coexist at rest and are resolved at use." That wording does not contain "explicit status" or "rank as annotation". Under it, two rulings both in force on their face is coexistence at rest, so not a breach. Only I-7 (supersession link) is breached cleanly. | normal | 00.05 L10; 02.10 L110 (labelled "violations of the foundations, measured"). | Cite Ch. 0 §0.5 I-4 directly. Mark "explicit visible status" and "rank as annotation" as this book's reading (Int). Keep the I-4 breach only if the reading is adopted; otherwise keep I-7 alone. | 02.05 L6–7; 02.10 L110; 00.05 L10. | The reading is stated once with its label and no circle. |
| F8 | 02.10 L107 | "Prediction (Src, measured)". A prediction from PK-P4 is not Src or measured. It is Ded/Hyp. | normal | Label check, step 3. | Relabel "Hyp (derived from PK-P4)". | 02.10 L107 only. | Label fixed. |
| F9 | 02.00a L15 and 02.10 L90 | Homonyms. (a) Thesis of §2.10 reads "rule" in RS's eight-sense use, which §2.0 L15 says is not one concept (via Decision B). The thesis therefore equivocates between "norm", "decision" and "check". The qualification at 02.10 L90 is there but the title and thesis keep the bare "rules". (b) "Decision B" (Ch. 1 §1.9 L46) is ruled for two outcome concepts (verification vs recommendation effectiveness). §2.0 L15 generalises it to "different question → different concept". (c) "binding" has four senses in Ch. 2: binding strength (§2.9), the method/binding split (§2.8.6 L147), the 1:1 capability↔DP-n binding (§2.9 L76), and S0024's BINDING dimension (coupling). | normal | Reading checks; 01.09 L46. | Qualify each: "rule (norm-bearing statement)", "binding strength", "binding (method/binding split)", "binding (capability↔DP-n)". Restate §2.0 L15 as an application of the Ch. 1 §1.9 pattern, not of the ruling. | 02.00a L15; 02.10 title and L90; 02.09 L12–16, L76. | The qualified terms appear at first use. |
| F10 | 02.10 L111 | "Configuration rules are edited in place, so their history is lost" (breaks I-1). Stated without qualification, but the configuration lives under version control. What is missing is approver, reason, validity and scope in the rule's own structure, not history. I cannot check RS L228–233 (outside the allowed list). | normal | Book itself relies on commit history throughout the lane; claim depends on RS only. | "…no approver, reason or validity in the rule's own record; version control keeps the diff, not the authorisation." | 02.10 L111. | Qualified. |
| F11 | 02.11 L153 | "exception validity ⊆ rule validity (a time containment the sources imply but do not state)", under "Integration (Ded)". An exception that outlasts its rule is moot, not incoherent. So this is a stipulated constraint with no derivation and no cite. | normal | Reading. | Mark Int/convention, or state the derivation. | 02.11 L153. | Label fixed. |
| F12 | 02.08.6 L173–185 | Trivial-case gap in the app-profile model. The text analyses adding a dimension to app(k) (positive results degrade to CANNOT DETERMINE). It does not analyse the dual. Removing d from app(k), or an empty app(k), turns CANNOT DETERMINE into APPLIES (an empty conjunction is true). That is silent broadening, which SC-2 forbids, now through app. "Who controls app controls determinacy" (L174) covers authors, not later governance changes. L185 says "both adding and removing are costly" without deriving the second. | normal | Strong Kleene over an empty set of dimensions is true; L173 "applicability is then strong-Kleene over app(k) only". | Add the dual consequence: shrinking app broadens. State that governed changes to app(k) are acts, and that narrowing is the dangerous direction. | 02.08 L173–185; 09.14 L208 cites "adding a value" (unaffected). | The removal case is stated with its consequence. |
| F13 | SEQ: §2.2–§2.7 vs §2.8 | First use before introduction. §2.6 L11 uses "SC-2" and "⊥" with no pointer. SC-2 and ⊥ are introduced at §2.8.3–2.8.4. §2.3 (d) and (e) use E6 (§2.8.8) and §2.9 (gate). §2.5 ground 2 uses §2.8.4a. §2.3 (b) uses "exception" and "authorized" (defined at §2.11). Most carry a forward pointer; §2.6 L11 does not. §2.8.7 uses E1–E7 before the §2.8.8 table, with pointers. | normal | Index order 2.0–2.11 vs first uses. | Add a "SC-2 (§2.8.3)" pointer at §2.6 L11. Or, better, move §2.8.1–§2.8.4a ahead of §2.3 in the index. | 02.06 L11; 02.03 L77; 02.05 L11. | Every first use has a definition or a pointer. |
| F14 | 02.05 L23, L25, L27, L29 | "(S0034, quoting `Round39-MC`)". S0034 L70 (Domain Model, D-2 table) gives the sentence "void to the extent of the conflict" under "Transactional consistency" with no source name. `Round39-MC` is named two rows above as D-2's constitution. The attribution is inferred from table placement, and the book says it as a quotation. | normal | S0034 L65–70 read. | "In S0034's D-2 table, whose constitution is `Round39-MC`." Mark the attribution Int. | 02.05 L23, L25, L27; TODO T2-08; register CON-0040 basis. | Attribution stated as inference. |
| F15 | `TODO.md` | (a) Open items in the text with no row: cross-kind comparison of scopes (02.08 L179); the three "still open" items (L103–106: unknown context values, tree dimension, folding A and T); whether a standing enacted precedence is allowed (02.05 L17); who may authorise exceptions in general (02.08 L29); change authority for advisory heuristics (02.11 L163, RS L278); E8 reduces to E3 or not (02.08 L282); environment hierarchy and EV-1 ruling (02.08 L243). (b) T2-10 is "Resolved" by derivation, while its resolution criterion ("the record and its standing") and next action are unchanged and unmet. (c) T2-07 reads "Open" while its triage tag says "CLOSE → register CON-0034" and the criterion (ARB ruling on RO-1) is unmet. (d) T2-05 and T2-04 omit §3.9 C4 and §3.1 (L40), which already partly reconcile the control kinds; 08.08 L20 says the same subject is "not yet in a section". (e) T2-11: S0043 L44 says "applying the vocabulary honestly needs a THIRD term the source does not supply", so the advisory term comes from S0043, while Boundaries/Triggers come from a source still unseen. | normal | TODO.md read against the text; 03.01 L40, 03.09 L104; S0043 L44. | Add rows for (a). Reconcile (b) and (c). Add pointers in (d). Note (e). | n/a (TODO rows) | Rows reconcile with the text. |
| F16 | pointers | 02.08 L276 "(below)" points to a passage above (L270, §2.8.7). 02.10 L129 places the blocking gate at "§2.8.6"; the gate analysis is §2.8.7 (the §2.8.6 table has one environment row only). | normal | Read. | "(above)"; "§2.8.7". | 02.08 L276; 02.10 L129. | Pointers corrected. |
| F17 | 02.08 L55–56 (CON-0063) | The register meaning, "a rule whose status is deferred leaves its question silent", is wider than the derived claim, "a deferred rule cannot bind at t". The derivation enumerates two senses of "deferred" (not adopted; adopted with effect postponed) by stipulation. | normal | Register `meaning` vs 02.08 L56. | State the claim and its two senses as the book's reading, and align the register wording. | 02.08 L55–56; register CON-0063. | Meaning and claim agree. |

Out of scope, one line each. R1 flags CON-0003 (register INVALID; `03.01` text says "validated"), a Ch. 3 matter for reviewer B. 02.00a L3 uses "the C23 rule" with no gloss in Ch. 2. Audit labels A-19, A-20 and A-21 each recur for different corrections (02.00 L10 vs 02.09 L73; 02.04 L16, 02.09 L82, 02.11 L152) and I cannot resolve them, since the audit files are not in the book.

## 2. Proof checks (Ded in Ch. 2)
| Item | Result |
|---|---|
| §2.3 (d) counterexamples (global minimality; pairwise not enough) | Checked by hand: R1 MUST(p∨q), R2, R3. Adequate. |
| §2.6 over-approximation soundness | Steps: (1) a conflicting set applies at one (c,t). (2) π(c,t) lies in every member's key, since key ⊇ π(Region). (3) so the set lies in one partition. (4) point-by-point testing inside the partition. Premises: π is a function of (c,t); Region is the effective region. Adequate. |
| §2.8.4 containment steps 1–3 | I ran all pairs of specifications for (V={a,b}, k=2) 625, (V={a,b,c}, k=2) 6,561 and (V={a,b}, k=3) 15,625 against supervaluation (`cons2-a-contain.py`): 0 mismatches. A finite check illustrates; it is not a proof. The book's step-3 argument is sound. |
| §2.8.4 set overlap | Refuted for n ≥ 3 (F2). |
| §2.5 priority argument | Inadequate: premise missing or circular (F5, F6). |
| CON-0063 derivation | Adequate for the stated two senses (F17). |

## 3. Concept verdicts
Ch. 2 homes only. The concepts homed in Ch. 3 or Ch. 1 by SCOPE §2.2 (the rest of the VALIDATED, PROVISIONAL and INVALID lists) are not given verdicts here. They belong to reviewer B or to Ch. 1's rotation. CON-0004 (authority scope, homed in 03.01): the Ch. 2 use of "canon scope" vs "grant scope" (§2.8.4a) matches the register name.

| ID | Verdict | Reason |
|---|---|---|
| CON-0056 maturity ≠ enforcement | VALIDATED (second verdict), scope-limited | A non-implication needs one case. S0024 L46–58 shows Verification (#8) with method complete, 106 reports, six working scripts and level L2, and "Enforcing?" ⛔. L72 corrects the claim to "no capability enforces". The sample's top level is L2 of L0–L4 (L3 "Automated", L98). So the claim is established for maturity up to L2, for capabilities, in one repository on one date. It says nothing about L3–L4. The L0–L4 levels remain undefined in the book (SEQ-0002). |
| CON-0063 a deferred rule cannot bind at t | VALIDATED (second verdict), scope-limited | Follows from the necessary condition of §2.11 L161 and the two senses of "deferred" (F17). A book derivation: the source rule's record is not seen. "Cited as authority" is correctly withdrawn. |
| CON-0082 E9 invalid evidence | PROVISIONAL | S0721 L453–480 supports the distinction (evidence ≠ ∅, validity false). The test for membership in a "kind of nothing" is open: E9 has Evidence ≠ ∅, so it is not an empty answer, and its boundary with E6(c) and E2 is unresolved (Ch. 5 §5.4 C4). |
| CON-0083 E10 unknown dimension | PROVISIONAL | S0721 L294–330 supports "d ∉ D, found only by search". Open: which dimension set, and whether it needs the unbounded-space hypothesis (Ch. 5 §5.4 C4). |
| CON-0034 DP-n as capability anchor | INVALID as restated | Restated at 02.09 L76–84 as Src with RO-1 open. S0021 L32, L33, L44, L106 and L166 and S0008 L38 check. The binding does not mean the capability operates (S0020 L100). One residue: L76 says "a DP-n is the rule with meaning", L82 treats it "as an invariant"; see F9. |
| CON-0040 void-overriding | INVALID as restated | 02.05 L19–29 marks "extent = conflict region" as Int, gives the wider reading, and limits the source to one constitution. S0021 L114, S0022 L66 and S0034 L70 check. Attribution wording: F14. |
| CON-0055 control kinds | INVALID as restated | 02.10 L121–131: the counts are by mechanism and the kinds by effect. S0024 L74–78 and S0043 L44–52 check; "one line of support, not three" holds. T2-11 partly answered (F15e). |
| CON-0061 trigger-bound deferral | INVALID as restated | 02.11 L165–168. S0025 L145–147 check (L143 is the E-row, L145–147 the F-row and its sentence). Who decides remains open (T2-09). |
| CON-0062 closed-vocabulary collision | INVALID as restated | 02.04 L48–53. S0025 L72 and L143 check. The restated form (a candidate conflict, limited to the cases that call for INCONCLUSIVE or PASS AFTER CORRECTION) is consistent with §2.3: closed sets restrict, and only a mandatory emission clashes. |

## 4. Coverage
Pointers from Ch. 2 to later chapters:

| Item | Status | Home |
|---|---|---|
| §9.13 (CE*) | resolves | 09.13 L1, L135 |
| §9.14 (pairs suffice, R5 b) | resolves | 09.14 L47; status stale in 02.03 (F1) |
| §3.9 C4 | resolves, agrees | 03.09 L104 (22 `ask` do not block) |
| §3.6 (principle → design policy → capability) | resolves | 03.06 L15, L53 |
| §3.2 (meta-model dimension test) | resolves | 03.02 L11 |
| §3.1 "Enforced has three senses" | resolves | 03.01 L40 |
| Ch. 5 §5.4 C4 (E9, E10) | resolves | 05.04 L81, L190–191 |
| Ch. 1 anchors (§1.14.5, P-E, §1.17.5, A1-recognized, Decision B) | resolve | 01.14 L412, L7; 01.17 L99; 01.06 L17; 01.09 L46 (Decision B generalised: F9) |

TODO rows:

| Row | Status | Where |
|---|---|---|
| T2-01 conflict of decisions | still open | only 01.14 L141 (each judged on its own); no definition |
| T2-02 NOT_APPLICABLE | still open | 02.09 L61–65 |
| T2-03 ruled absence | still open | 02.08 L284 |
| T2-04 / T2-05 | partly resolved elsewhere | 03.01 L40, 03.09 L104; row pointers stale (F15d) |
| T2-06 | resolved (definition) | 02.09 L76 |
| T2-07 | status inconsistent | F15c |
| T2-08 | open (timing, declarer) | 02.05 L29 |
| T2-09 | open | 02.11 L166–168 |
| T2-10 | marked resolved, criterion unmet | F15b |
| T2-11 | partly answered | F15e |
| T2-12 | open | 02.04 L50–53 |
| T2-13 | open | 02.04 L40 |
| SEQ-0001 to 0006 | resolved as stated; SEQ-0002 (L0–L4) still open per its own row | n/a |

Source errors (listed separately): none found that the theory relies on. S0024 L84–88 says "Not one responsibility is enforcing. Every automation is advisory" under a header the author later corrects (L72); the book already handles this (02.10 L117, L129).

## 5. R2 (scope 02, `--scope "02"`): every candidate
Tier A:
- realisability: used at 02.03 L68; defined at 09.14 L43; pointer present. Acceptable.
- voided: past participle, meaning given by the S0034 quotation (02.05 L23).
- subtree: glossed at 02.08 L151; first use L105 precedes the gloss. Minor.
- recasting: ordinary word.
- alternation: explained in place (02.03 L73).
- initio: "ab initio", glossed "from the start".

Tier B:
- C-05, C-06, H-02, H-04, B-20: audit or consolidation labels, defined at 02.00 L20 as pointing to files outside the book. Opaque to a reader. Either add a gloss or drop (normal).
- DP-1: an instance of `DP-n` (02.09 L76), from a quotation. Acceptable.

Tier D (ordinary words or defined in place), one by one:
- Defined in place:
  - expired: 02.11 L154.
  - rank: 02.05 L13.
  - annotation: 02.05 L13.
  - design-policy: 02.09 L76.
  - author-assigned: 02.05 L1, L17.
  - proven and proved: 02.07 L3.
  - safety: 02.03 L77.
  - polynomial-time: cited to the external reference.
  - pairwise: 02.03 L64.
- Needs a gloss: "specificity" (02.05 L3), a criterion of resolution, never defined. Related to "specializes" (02.04 L5) but not the same; tension with the precedence discussion at §2.8.4a.
- Ordinary English, no judgement needed:
  - coherence, compose, thumb, formalization, computable, smallest, important, hypothetical, land, issued, reporting, useful, fifth, methodology, edit, empirical, nobody, necessity, recommend, weaker, universal, intersection, satisfied, axiom, opposite, standard, problem, prove, gaps, machine, route, violation, declare, resolve, apply, fourth, possible, comparison, built, cost, person, govern, beside, contradiction, once, above, agree, because, keep.

## 6. Coverage of sections
Read in full: `02.00`, `02.00a`, `02.01`–`02.11`, `02.99`, `TODO.md`.

Not checked:
- Every line anchor into `docs/knowledge_tranfer/*` or outside the allowed list: RM, RS, SC, SD, EV, DE, PC, IM, CF, AB, IA, SI, AMS, GKC, GA, V3 and the KT-8 note.
- Repository code and configuration cited in §2.1, §2.8.7 and §2.9. The phase-target series, the 31/8/12/3/9 counts and the `design-check.sh` behaviour are "reported, not shown".
- The audit files behind the L-, H-, R-, E-, C-, A- and B- labels.

Allowed-list sources verified: S0003, S0005, S0007, S0008, S0014, S0015, S0020, S0021, S0022, S0024, S0025, S0034, S0043 and S0721. Every line anchor I checked matched. The `corpus_quote_check.py` run on the Ch. 2 section files gave 10 found, 0 not found.

## 7. Files opened and commands run
Not a single file in REVIEWER-NOTE item 4 was opened. I did not use `book_search.py`; the copy's `tmp/tools` holds only it and the synonyms file, and the other tools are from the working lane's `tmp/tools`.

Files opened (all read-only):
- `W/06-theory/manuscript/review/CONSOLIDATION-S0278-S0302/REVIEWER-NOTE.md`, `SCOPE.md`, `COMMISSION-A.md`; `W/03-methodology/REVIEWER-INSTRUCTIONS.md`.
- `<copy>/06-theory/manuscript/chapter_02/`: `02.00-index.md`, `02.00a-…`, `02.01`–`02.11`, `02.99-change-log.md`, `TODO.md`.
- `<copy>/06-theory/CONCEPT-REGISTER.tsv`.
- Targeted lines in `<copy>/06-theory/manuscript/`: `chapter_00/00.05`, `chapter_01/01.06`, `01.09`, `01.14`, `01.17`, `chapter_03/03.01`, `03.02`, `03.06`, `03.09`, `chapter_05/05.04`, `chapter_09/09.13`, `09.14`; `chapter_06/06.06`; `chapter_08/08.08`; `BOOK-OUTLINE.md` L177; `06-theory/THEORY-MAP.md` L413.
- Tool sources: `tmp/tools/undefined_terms.py`, `inbound_citations.py`, `register_home_check.py` (headers).
- Corpus (allowed list): `CORPUS-ALLOWED.tsv`; S0003, S0005, S0007, S0008, S0014, S0015, S0020, S0021, S0022, S0024, S0025, S0034, S0043, S0721 (line ranges cited above).
- My own scratch files: `cons2-a-r2.txt`, `cons2-a-overlap.py`, `cons2-a-contain.py` in the session scratchpad.
- Persisted outputs of my own earlier commands in the session tool-results folder.

Commands:
- `ls -R` of the copy, `wc`.
- `cat -n` of all Ch. 2 files.
- `python3 -I tmp/tools/undefined_terms.py --root <copy>` (twice; second time with `--scope "02"`).
- `python3 -I tmp/tools/register_home_check.py --root <copy>`.
- `python3 -I tmp/tools/inbound_citations.py --root <copy> "2.8"`, `"2.2"`, `"2.3"`, `"2.11"`.
- `python3 -I tmp/tools/corpus_quote_check.py <the twelve Ch. 2 section files of the copy>`.
- Python register queries via heredoc; `grep` and `sed` on the copy and on allowed corpus lines; `awk` on `CORPUS-ALLOWED.tsv`.
- `python3 -I` on my two scratch scripts.
- Searches in the copy: "realisab", "KT-8", "never CONSISTENT", "unknown overlap", "CON-0192", "provisional", "across subject kinds", "enacted precedence", "T2-05", "conflicting decisions", "NOT_APPLICABLE", "meta-model dimension test", "visible status", "Decision B", "A1-recognized".

I made no repository changes.
