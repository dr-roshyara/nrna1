# Theory turn 5 (global consistency; §9.13's changes): settlement

*2026-10-20. By the Professor. The review was split into two commissions, as the Principal allowed, and both are settled here before turn 6.*
- *Commission A (§9.13's changes since `2649b302c`): `REPORT-A1.md` (Opus, 10 findings: 2 high, 8 normal) and `REPORT-A2.md` (Sonnet, 15 findings: 2 high, 10 normal, 3 low).*
- *Commission B (§9.14): `REPORT-B1.md` (Opus, 16 findings: 1 critical, 3 high, 12 normal) and `REPORT-B2.md` (Sonnet, 15 findings: 2 high, 13 normal).*
- *No reviewer saw another's report. The Professor read all four in full before settling.*
- *One new outside work was verified before it entered the book: Dechter and van Beek 1997, EXT-076.*

## 0. Deliverable status

**Met.** The class for which checking pairs suffices is stated precisely:
- **for a language:** for every rule base over an admitted rule language, single rules and pairs, tested point by point, give exactly §2.3 (d)'s set-wise report **iff the language's effect class has Helly number at most 2**, on the outcome space it is evaluated in (§9.14 R1; the converse under realisability);
- **for one base:** iff its largest minimal conflicting set has at most two rules;
- **the book's rule classes** are placed (R3), with h belonging to the union of the forms admitted on an attribute, the full-product premise stated (R2), and each example's representation marked Int.

The set-wise test holds for every class as defined. It reduces to pairs only for the h = 2 languages listed in R5 (d).

**Commission A:** §9.13's changes are settled as version 4.

**Returned to the Principal (two questions):**
1. **D (citations of earlier entries only) or D_acyclic (refuse only citation cycles).** The lemma needs only the latter. It gives the same guarantee, every T and F forced, with a smaller design limit (§9.13.2, R3). This is an informed confirmation; D is unchanged.
2. **Does decision 3 (self-cancelling acts) include the indirect case:** an act under a child grant annulling the parent? It gives U, and the reason is of the same kind either way (§9.13 R2).

## 1. Research questions (practice 1)

- **§9.14:** for which rule languages do pairs find every conflict? The answer is h ≤ 2. Version 1's classification over-reached in three ways: rows combined freely; a coupled outcome space; "iff" claimed for a single base.
- **§9.13:** do the turn-4 settlement and the Principal's decisions hold?
  - The lemma holds, under a weaker hypothesis than stated.
  - Refusal reasons existed only for the direct cases.
  - Lemma D's premise holds across records only if the merge stays in D.

## 2. Decisions, commission A (§9.13 → v4)

| IDs (A1 / A2) | Finding | Severity | Decision and correction | Resolved when | Holds? |
|---|---|---|---|---|---|
| A1-01 | the lemma needs only that positive dependence is acyclic (tight); "earlier entries only" is sufficient, not necessary, and is not the corpus's "acyclic graph" | high | Accepted. R3's lemma is restated for any record without a citation cycle; on D record position is one such order. Check 14: 3,228 tight records, 1,718 outside D, WF = KK on all. **D_acyclic** is named as the rival, with its smaller design limit (§9.13.2). The S1486 row and R3 distinguish "acyclic" from "earlier entries only". **The choice is returned to the Principal**; D is unchanged | R3 states the real hypothesis; D_acyclic named; the Principal confirms D or chooses D_acyclic | **yes**; the choice is with the Principal |
| A1-02 / A2-03 | refusal reasons existed for direct self-cancelling, two-act mutual annulment and covers on cited grants only. Indirect self-cancelling, annulment rings, ending cycles, the queried scope and U revocations got a false "no cycle" placeholder. The decision record's "the other negative cycle" was false | high | Accepted. The reason is now defined generally (§9.13.3): walk the U atoms the answer reads; name every cycle (self-cancelling direct or indirect, mutual annulment, annulment ring, other negative cycle); name every undefined covers pair on a live rule and on the queried scope. R2 derives why a reason always exists on D. Check 15 covers the five cases; check 15b: 165 U answers on 3,000 records, none without a named reason. The false premise is corrected in §9.13 R2. **Whether decision 3 includes the indirect case is returned to the Principal** | §9.13.3 covers every U cause R2 names; no placeholder on a record with a cycle; the premise corrected in the text (the decision record is the Principal's) | **yes** |
| A1-03 / A2-01, A2-02 | proof gaps: step 7 gave one inclusion; Φ = Fitting's operator only model-checked; "KK ≤ WF" without a derivation | normal | Accepted. Step 0 is new: Φ is Fitting's operator by distributivity (§9.13.3). Step 7 now gives both inclusions and T_P(I) = true(I). Step 8 derives KK ≤ WF, because WF is a Fitting fixed point | the step table contains them | **yes** |
| A1-04 | "the same 27" conflated two counts | normal | Accepted. Check 5 now prints the joint count. v4: 331 fixed points, 31 other than KK, 31 not above WF, of which 15 are other than KK. Version 3's error is noted in R2 | R2 matches a printed joint count | **yes** |
| A1-05 / A2-06 | "found nowhere" rested on an unlogged outside search; Fages's provenance was stronger than what was seen | normal | Accepted. The outside search terms and date are recorded in R3. The claim is restated: "probably known; no statement of it was seen". The Fages provenance is aligned with EXT-070/071 | R3 states the search scope; the provenance agrees | **yes** |
| A1-06 / A2-09 | "earlier" is undefined, and act time is not compared (backdating) | normal | Accepted. "Earlier" = record position (the Professor's reading). Backdated grants are admitted and named. The design limit says "recorded", not "issued". Whether to admit backdating is an open question | §9.13.2 defines "earlier" and names the case | **yes** |
| A1-07 / A2-15 | free targets and their consequences unstated; the root-annulment rule missing from Θ_f; mistyped targets; choices attributed to the decision | normal | Accepted. Forward targets and pre-emptive annulment are stated; mutual annulment exists only through them; pre-assigned identifiers may be used for targets. Θ_f's ρ row now says a root cannot be annulled (check 16). Mistyped targets have no effect. The Professor's reading is separated from the decision's words | §9.13.2 and Θ_f say so | **yes** |
| A1-08 | P is conjunctive over the cited grants, while χ is disjunctive | normal | Accepted. Θ_f's P row states it, with the rival; check 16 exhibits it; open question added | the P row and the check | **yes** |
| A1-09 / A2-08, A2-11 (a) | Lemma D's premise was argued for one record only; a cut across records may leave D; D is not closed under appending | high (A2) / normal: settled **high** | Accepted. §9.13.6 and §9.08 Lemma D: the premise is met inside one record in D, and for a cut across records only if the merged cut is in D (a condition on F26 (c)). T1 likewise. The design limit now states non-closure under appending and per-record refusal | §9.13.6 and §9.08 state the scope and the merge condition | **yes** |
| A1-10 / A2-10, A2-13, A2-14 | residues and stale figures: CON-0191 "grounded"; BOOK-OUTLINE "all values forced"; §9.09 wording; §9.13.7; counts (9,000; the chain outside D); §9.10's vacuity row and stale lines; current-authority's monotonicity unstated | normal / low | Accepted. CON-0191 now says "outside D". The outline says "every T and F forced; every U with a refusal reason". §9.09 premise (vi) reads "authorized". §9.13.7 names all reasons. 12,000 records; an in-D chain witness (check 2). §9.10 "on D" and the stale lines are marked answered. R2 states that current-authority is monotone, so every T and F is forced, while U values are not (mutual annulment has total fixed points) | each sentence is changed as stated | **yes** |
| A2-04 | a spurious covers reason for a dead rule | normal | Accepted. Reasons are read off the live rules of the program reading (`dep_graph`) | covers reasons only for live rules | **yes** |
| A2-05 | "not acyclic in Apt and Bezem's sense" had the wrong reason; the corollary was missing | normal | Accepted. The reason is a cycle through a negative edge, including u ← not u. The corollary is stated: on D, U rests on such a cycle or on an undefined covers pair (R2) | R3 and R2 state it | **yes** |
| A2-07 | §9.07 result 2 conflated "refused by decision" with "not a function" | normal | Accepted. §9.07 and §9.13.5 now separate the two | the two places separate them | **yes** |
| A2-11 (b) | dangling citations accepted silently | normal | Accepted. Stated: F through them (the safe direction), tolerated because of recorded loss (H1′); not flagged without a loss entry, which is an open question (§9.13.2, §9.13.7) | §9.13.2 says so | **yes** |
| A2-12 | a revocation's scope is required but never read | normal | Accepted. Θ_f has a row: not read (whole-or-nothing); open question added | Θ_f has the row | **yes** |

**Counts, A:** 25 findings (A1 10, A2 15) → 15 settled rows. Found by both: 7 rows. One reviewer only: 8 (A1 3: A1-01, A1-04, A1-08; A2 5: A2-04, A2-05, A2-07, A2-11 (b), A2-12). No critical finding.

## 3. Decisions, commission B (§9.14 → v2)

| IDs (B1 / B2) | Finding | Severity | Decision and correction | Resolved when | Holds? |
|---|---|---|---|---|---|
| B1-01 / B2-01 | the h = 2 rows are not closed under union on one attribute: thresholds with forbidden values give minimal sets of size m + 1 for every m; R5 (d)'s list was unsound | **critical** (B1) / high (B2): settled **critical** | Accepted. R3 now states that h belongs to the union of the forms admitted on an attribute, with a new row "threshold with forbidden value": n on n values, unbounded on an unbounded domain, with proof; check 6. R5 (d) and the §2.3 (d) pointer list only unions with h = 2 proved: required values with thresholds; required and forbidden values on a Boolean or unbounded attribute | R5 (d) and §2.3 (d) name only proved unions; the new row and its check | **yes** |
| B1-02 / B2-02, B2-15 | R2 and the Boolean and product rows need a full product outcome space; coupling breaks them | high | Accepted. R2 has the premise; the exactly-one counterexample is in R2 (check 6). The Boolean row says "independent Booleans". §9.14.6 adds the world assumption: a coupling is a rule in the checked set, or part of X | R2's premise; §9.14.6; the counterexample shown | **yes** |
| B1-03, B1-08, B1-11 / B2-03 | "iff" for a single base contradicts §9.14.4; 𝓔 was mixed between base and language; R5 (c)'s "one rule raises h" was false | high | Accepted. R1 is stated over an admitted language L, with 𝓔(L). R1 (d): for one base, iff m(ℛ) ≤ 2; h(𝓔(L)) ≤ 2 sufficient. R5 (c): admitting the form raises h, while one rule does not. The §2.3 (d) pointer is the same | both places use "iff" for languages and m(ℛ) for bases | **yes** |
| B1-05 / B2-06 | S0922's cardinality constraints, as counts over members, are linear across attributes, not h = 2 | high (B1) / normal: settled **high** | Accepted. A cardinality row is added to R3: > 2, with exactly-one-owner and three prohibitions minimal with 4 (check 6). h = 2 holds only if the count is the sole attribute. The S0922 row is corrected | R3 and the S0922 row | **yes** |
| B1-04, B1-15 / B2-12 | check 3 compared the report with a filtered copy of itself; capped searches were called exhaustive | normal | Accepted. Check 3 now uses an independent pairs-only analyser, with the same outcomes. Caps are stated as lower bounds, and the theorem gives the upper bound | the run says so | **yes** |
| B1-06 | set notation collapses rules with equal effects | normal | Accepted. Indexed families are used; a minimal family never repeats a member | step 1 uses indexed families | **yes** |
| B1-07 | h undefined over infinite families; the equivalent form garbled | normal | Accepted. The definition is over finite subfamilies (0 if none is empty), and the equivalent form is the standard one | §9.14.2 | **yes** |
| B1-09 | R2's proof skipped the minimality step | normal | Accepted. R2 now has a step table with the minimality step | the table | **yes** |
| B1-10 / B2-05 | the design checks' phase targets are enforcement thresholds, not effects; §2.3 (a)'s linear arithmetic is a fragment, not a book rule | normal | Accepted. Enforcement thresholds are out of scope (§9.14.2). The design checks are placed as Boolean prohibitions with obligation zero (Int). The linear row is marked "admitted by the fragment; no book rule of this form found" | R3 and the scope say so | **yes** |
| B1-12 / B2-07 | R5 (a) counted tests as if each had constant cost | normal | Accepted. R5 (a) gives the number of subset tests Σ_{i ≤ min(h, n)} C(n, i), each an ∃∀ question; CON-0192 the same | R5 (a) and CON-0192 | **yes** |
| B1-13 / B2-11 | the closest rival, relational consistency, was missing; the Helly property; Jeavons's verb | normal | Accepted. Dechter and van Beek 1997 verified and added (EXT-076, §9.14.5). The Helly property is named in §9.14.2. Jeavons et al.: the abstract's own verb, "characterises", attributed to the abstract | §9.14.5 names the rival with a verified citation | **yes** |
| B1-14 / B2-09 | exactness: forbidden values on an unbounded domain ("2" → no empty family, h = 0); allowed sets only for k ≥ 3; clauses of length n | normal | Accepted, all three | the rows say so | **yes** |
| B1-16 / B2-14 | S1539 overread: only A1 is an implication; the triple needs the bridge as background logic (4 members if explicit); Int, no context/outcome split | normal | Accepted. The S1539 row is rewritten | the row says so | **yes** |
| B2-04 | the OAuth2 and TLS placements depend on representation | normal | Accepted. Both readings are given, marked Int | R3 names the representation | **yes** |
| B2-08 | the unknown context has a partial answer | normal | Accepted as a partial answer (Ded sketch) in §9.14.9; the question is kept open | §9.14.9 | **yes** |
| B2-10 | the interval proof needs pᵢⱼ = pⱼᵢ | normal | Accepted | the proof says so | **yes** |
| B2-13 | §9.14.4 was not the strongest counterexample | normal | Accepted. Retitled "the limit of the converse, and the strongest counterexamples", with the union, coupling and cardinality cases | §9.14.4 | **yes** |

**Counts, B:** 31 findings (B1 16, B2 15) → 17 settled rows. Found by both: 10 rows. One reviewer only: 7 (B1 3: B1-06, B1-07, B1-09; B2 4: B2-04, B2-08, B2-10, B2-13). Critical: 1 (B1-01; B2 rated the same finding high).

## 4. Checks run on the corrections

- **Quote check (entry 60):** §9.13 10 found, 0 not found; §9.14 2 found, 0 not found. §9.07, §9.08 and §9.09 are unchanged against the baseline (15, 16, 13 found). §2.3, §2.6 and §9.10 have no checked-form quotes.
- **R1:** H2 0 (the one H4 flag is inherited, CON-0133).
- **R3:** the inbound citations of CON-0191, CON-0192, §9.13 and §9.14, all updated:
  - §9.07 result 2; §9.08 Lemma D; §9.09 T1;
  - §9.10 Q1, Q3, the vacuity table and the deferred lines;
  - THEORY-MAP F4; BOOK-OUTLINE; the Ch. 9 index;
  - the §2.3 (d) and §2.6 pointers.
- **Models:**
  - `ce_model.py` v4: all assertions passed (`ce_model-run-20261020-v4.txt`);
  - `helly_check.py` v2: all assertions passed (`helly_check-run-20261020-v2.txt`).

## 5. Open items

| Item | Role | Reason | Next action |
|---|---|---|---|
| **D or D_acyclic** | **Principal** | the lemma needs only no citation cycle; D's design limit is larger | confirm D or choose D_acyclic |
| **indirect self-cancelling within decision 3** | **Principal** | the decision names "a holder annuls the grant it acts under" | confirm the scope |
| backdating; dangling citations without a loss entry; P conjunctive vs disjunctive; revocation scope | Professor (P is reserved for the Principal) | §9.13.10 | when a turn needs them |
| the live rule stores' admitted effect forms; §2.3 (a)'s language and h; unknown context | Professor | §9.14.9 | turn 7 or later |
| §9.13 v4 and §9.14 v2: corrected, not re-reviewed | reviewers | — | turn 6's reviewers, where used |
| corpus searches for turns 2 and 3 (the Principal's instruction) | Professor | practice 2 | turn 7 |
