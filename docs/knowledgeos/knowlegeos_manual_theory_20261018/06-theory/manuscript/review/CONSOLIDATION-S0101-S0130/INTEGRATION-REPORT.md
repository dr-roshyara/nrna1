*Consolidation integration reviewer (Opus), report, returned as hand-back text, verbatim (saved by the Professor at 2026-10-23T17:32:57Z). sha256 of the hand-back text at receipt: `1054fd22567a06e325f87ac551bf4c6f0d94744b28971da9ceac71a200ca6168` (UTF-8, the text below this line exactly).*

CONSOLIDATION S0101-S0130 INTEGRATION REPORT

Integration reviewer (Opus). Object frozen at 3d10a8007. `git diff --stat 3d10a8007` over the manuscript shows only the review files changed. Nothing was edited. Marks: [F] fact checked against a file or a run · [D] deduction · [I] opinion.

## Summary (8 lines)
1. [F] No critical finding. The CE* derivation in §9.13 runs from A1 (§1.6) through §1.13/§1.14.1 and holds. The link is one-way: Ch. 1 never points to §9.13, so §1.14.1 is a second, stale home of the evaluator (I-01).
2. [D] Both reviewers' corrections for §1.14.1 claim more than §9.13 decided. ρ and P are reserved (§9.13.9). ω, χ and σ are only restricted in Θ_f. ι = (b) is fixed by Θ_f, not by a decision (I-01).
3. [F] One rule, two homes, already drifted. §9.8.6 item 7 quotes the adopted R-CONFLICT with "silently". CON-0237, the §8.17 C5 heading and 08.00 drop the word. F24 also cites it in the wrong sense of "conflict" (I-02).
4. [F] B-04 reproduced: S0164 L2041ff is S0103's registration pasted in (96 of 136 non-blank lines; same work item, Correction #3). §8.6 L34's "for another work item" is false (I-06).
5. [D] New, found by neither reviewer: §9.16 R3a's reason for single use ("CE* already gives a second consumption F") is stale since "the work act is the use". Φ reads only endings and consumptions, so a second work-act use on a merged record is T and only marked (I-10).
6. [D] The (C + O) chain uses "application" as an identity-bearing participant (owner field, L10). No Ch. 0–1 primitive or identity rule covers it: I-0 covers claims only (I-08).
7. [F] Every homonym named in the commission is real across chapters, and each carries a law on at least one side (table 2). The concrete breakages are "reference" (CON-0202), "conflict" (F24), "owner" (§8.16 C2) and "C1" ("§9.13 C1" points at no claim).
8. [D] The R3 lists are incomplete for A-06/R1-1, B R1-5 and B-06, and they miss one dependent that needs its own status re-examined: CON-0208 (I-12).

Process independence, not personal independence: same operator, repository and model family as the Professor and reviewer A.

## 1. Cross-chapter defects

| ID | Finding | Sev. | Evidence | Correction | Resolved when |
|---|---|---|---|---|---|
| I-01 | §1.14.1 still presents itself as the evaluator: a total order (t, s), case 12 "record position decides", "not yet repaired", C1 open. §9.13.5 repairs it defect by defect. [F] Ch. 1 contains no "9.13" or "CE*". A's list (C1, ties, υ, ι, ω, σ) and B's (ρ, κ/C1, υ, ω, σ, ι) both call parameters "decided" that §9.13.9 keeps reserved (P, ρ) or that Θ_f only restricts (ω, χ, σ). υ was excluded for monotonicity (defect 1), not by a decision. | high | 01.14 L147–149, L202, L244; 09.13 L74–93, L256–268, L325 | Add a pointer table to §1.14.1 with three columns: decided by the Principal (C1 = constitute; ties unordered; D's citation rule; U for self-cancelling acts; consumption; tombstones) · fixed only within Θ_f (ρ = now, P conjunctive, χ, ω, σ, ι = (b), υ) · open. Mark the formal model as history. | No Ch. 0–2 text presents a decided parameter as open, or a Θ_f restriction as decided |
| I-02 | The adopted R-CONFLICT bans *silently* selecting one side (S0130 L28–29, as quoted in §8.17 C9 and §9.8.6 item 7). CON-0237, the §8.17 C5 heading, the §8.17 title area and 08.00 L31 state it without the qualifier (A-01). The same rule is about merging records (§9.8.6 item 7), yet F24 cites it as the "preserve" side of conflict between claims (A-09). With "silently", it does not oppose §9.4 C3's recorded winner plus supersession at all. | high | 08.17 L64, L97; 09.08 L104; THEORY-MAP F24; BOOK-OUTLINE L17, L20 | Restate CON-0237. Remove R-CONFLICT from F24's claim-level evidence, or qualify it as conflict (record merge). Fix the BOOK-OUTLINE History and Conflict rows. | F24's tension rests on S0913 versus §9.4 C3 only |
| I-03 | "Reference" has three senses. A D citation is guaranteed to exist (dangling citation outside D). A D target or application name asserts nothing (09.13 L62). An estate reference shows only presence, a claim (§1.13.2, §1.16 "presence is not validity"). CON-0202's universal ("asserts that its referent exists") fails inside D itself. A-03 holds; B's "sound" does not. | normal | 09.13 L55, L62; 01.16 L165 | Restate as A-03 proposes, with the three senses qualified | Register row and §8.13 C1 restated |
| I-04 | §1.10 says "identity requires a governed namespace … an owner". §9.16 R2's content-derived identity and CON-0219 have no owner of a namespace, so §1.10 fails as a universal. Content identity has its own limits, and §1.10's own argument predicts them: identical entries collapse (09.13 L38), and the same proposition text means different things in different contexts (§1.10 Int). | normal | 01.10 L37; 09.16 L93; CON-0219 | Scope I-0 to assigned identifiers. State content identity as the rival for immutable entries. CON-0219's "proposition text" holds only when qualified by its slot or act. | §1.10 and CON-0219 carry the scopes |
| I-05 | §1.16.7, "a global invariant can be HARD only where all writes pass one serialization point", is too general. Citation acyclicity is global and safe under merge (§9.16 R2 (a), under its premise). Ownership (R3 K4-p) is a serialization point per key, so it fits. The law holds for count bounds. B R1-5 holds in part. | normal | 01.16 L143; 09.16 L104, L161–173; THEORY-MAP L40 | Scope §1.16.7 and THEORY-MAP L40 to invariants that merging can violate. The Ch. 9 dependents (§9.4 C2, §9.6 L123, §9.7 L82, §9.11 L61) use the uniqueness case and need no change. | Both sites scoped |
| I-06 | [F] S0164 L2041ff is S0103 pasted in ("Datei … eingefügt", the German for "file … inserted"; header "Governance Registration — Independent Verification of Correction #3", KOS-AIP04-DISCOVERY-001). §8.6 L34's "for another work item" is false. §8.12 L61's "not checked" is now checked. The rule is one statement by one registrar, binding one assignment. | normal | cons_i_copy.py: 96/136; S0164 L2041–2081 | Correct §8.6 C1, §8.6 L34 and §8.12 C1. Any reading of the rule as recurring loses its second instance. | Lineage stated once |
| I-07 | Loss detectability is one law in three homes, and each statement is partly wrong. §8.17 C5 says "not detectable within D", but a cited loss is detected (A-13). CON-0237 says "interior only", but a head deletion is detected too (B-18). CON-0212 has the prefix read. [D] All three reduce to one statement: a loss is detectable from the record iff a surviving entry cites it, or a numbered successor survives. A lost uncited suffix or branch cannot be told apart from a cut. That is also why the partial read and the truncated tail are one hole (B-06). | normal | 08.14 L54; 08.17 L68–73; 09.15 K1 | One statement in §9.15 K1. §8.14 C5, §8.17 C5, CON-0212 and CON-0237 point to it. | One home |
| I-08 | The kernel additions of C19–C24 attach to Ch. 0–1 as follows. Single use → §1.13.2 (CONSUMED). K4-k → §1.16.7. L9 → §1.12. The transfer rule "never the holder" → §1.14.3's role separation (unstated). The owner application and L10 attach to nothing: Ch. 0–1 has no application participant and no identity rule for it. A word search found no definition (a miss within the search, not proof of absence). Visibility (§1.7) has no home in D. | normal | BOOK-OUTLINE §1; 09.15 L154; 01.10 | List "application (as a recorder)" as a candidate primitive or a defined input, with its identity rule (I-0 extended, or L10 named as its only ground) | BOOK-OUTLINE §1 has the row |
| I-09 | [D] Inside §9.13: v9 says D is "checked on the whole record; a cut is evaluated on its entries" (L60). L133 says CE* is "undefined … if R≤s is not in D". §9.13.6 says "a prefix of a record in D is in D", and §9.16 R2 (d) says "every such cut is in D". A cut that holds a tombstone but not its later removal act separates these readings. B-06 and R2 (d) rely on prefix closure. | normal | 09.13 L60, L67, L133, L273; 09.16 L107 | State whether a cut is tested for membership of D, and align L133, §9.13.6 and R2 (d) | One reading |
| I-10 | [D] "The work act is the use" (after C22) together with Φ (endings and consumptions only) means that a second *work-act* use reaching a record by merge is T, with a mark only. R3a's reason ("its standing needs no correction: CE* already gives a second consumption F") then covers consumptions only. §1.14.1-4's protection is vacuous for single-use grants (09.13 L41), and Ch. 1 does not know this. | normal | 09.16 L183; 09.13 L42, L45, L120 | Restate R3a's reason and G4's clause on detection. Decide whether Φ should read a use as it reads a consumption. | R3a and G4 agree with Φ |
| I-11 | [D] CON-0206 ("a later act is not a supersession without the link") is the *contrapositive* of I-7 (supersession ⇒ link, Ch. 0 §0.5), not its converse. It is therefore derivable, not a new law. Its new content is "until disposed, the decided text stands", plus the three kinds of later act. B-18 saw the mislabel but not the derivability. | normal | CON-0206; §0.5 I-7 | Relabel it as a corollary of I-7 and keep only the new content | Register restated |
| I-12 | CON-0208 is INVALID on two grounds. "The act is silent on performance" was withdrawn at C22 (CON-0213's own row). "Not counted as limb ①" is the registrar's reading (A-02/B-01). Both reviewers list 0208 as a dependent; B-08 asks only for the basis text. The status itself needs re-examination on the one ground left (the invariance sketch). | normal | register basis CON-0208, CON-0213 | Re-examine 0208's status after the CON-0213 split | Status rests on a stated, surviving ground |
| I-13 | "§9.13 C1" and "§9.13's C1" (08.14 L54, L58) mean the *parameter* C1, but they read as claim references, and §9.13 has no Cn claims [F]. So A's mechanical "161 §x.y Cn resolve" cannot catch this. | normal | grep: 0 `**Cn` lines in 09.13/15/16 | Write "C1 (§9.13 Θ_f)" | No ambiguous Cn references to Ch. 9 |
| I-14 | "Owner" has at least six senses: §1.10 register owner, §1.11 maintainer, §8.16 lane, R4a application, S0122 field roles, §9.6 K4 mutation owner. §8.16 C3 lists three. The senses cross at §8.16 C2 (A-12): a release recorded by Governance is read as "an authority moving ownership, R4a's own rule", against §1.14.3 (recorder ≠ decider) and §8.16's own L39. | normal | 08.16 L39–40, L54–57 | Qualify each sense. Restate C2 as "a recorder's release, basis not shown". | — |
| I-15 | R4c labels "(C + O) differs … only in two things" as Ded. R4d's own extended cost is a third difference (A-04/B-19). Nothing downstream derives from "only two", so I rate it normal, not high (A rated it high). | normal | 09.16 L227–228, L251–255 | Label "only two" as the Principal's framing. F22 is not reopened. | Label changed |
| I-16 | NOT ATTESTED is mapped to U at level (a) (§8.12 Int) and to "not established" at level (b) (CON-0201). [D] That is consistent only because a T or F at (a) implies "established" at (b). B-10 holds in part: (b) adds "applies, establishable, by whom". | normal | 08.12 L62; CON-0201 | State the dependency between (a) and (b) | — |
| I-17 | §8.15 C4 says reproducibility is "a fourth axis that the book has not used". §1.16 (its title and §1.16.3's f(inputs, analyser)) and §8.6 L88 use it. A-17 holds. This is a universal drawn from a search (pattern 3). | normal | 08.15 L61; 01.16 L1, L63 | Cite §1.16.3 | — |

## 2. Homonyms: real across chapters, and which carry a law

| Term | Senses (chapters) | Law-carrying | Breakage |
|---|---|---|---|
| owner | 6 (Ch. 1, 6, 8, 9) | R4a; §9.16 R3 count bound; §1.10 | §8.16 C2 (I-14) |
| reference | 3 (Ch. 1, 8, 9) | D's citation rule; CON-0202 | CON-0202 (I-03) |
| conflict | record merge (§9.8.6, R-CONFLICT) · claims (F24, §9.4 C3) · rules (Ch. 2 §2.3, §9.14) · copies (R2 merge) | all four | F24 (I-02) |
| NOT ATTESTED | ≥3 (S0103 lineage; S0115 M4, M2) | CON-0221; CON-0201 | I-16 |
| E1–E10 | kinds of nothing (Ch. 2 §2.8.8; §9.5 L51; §9.14 L207) · establishment criteria (S0164 D5, §9.10 L200; S0117/S0127, §8.15, §8.17) | "only E1 licenses no conflict" · CON-0224 | not yet, but Ch. 9 uses both senses |
| L1–L3 / L1–L10 | levels (S0117) · legitimacy assumptions (§9.15) | CON-0224, CON-0232 · §9.15 | both in §8.17 |
| C1 | parameter · section claims · corpus C-1/C-5/C-10 | C1 = constitute grounds the F by absence | I-13 |

## 3. Duplicates (one law, two homes)
§1.14.1 / §9.13 (I-01) · R-CONFLICT in §8.17 C5/C9 and §9.8.6 (I-02, already drifted) · loss detectability in §8.14 C5, §8.17 C5 and §9.15 K1 (I-07) · "only two" in R4c and R4d (I-15) · the single-use guard in layers across §9.13 v9–v14, §9.15 G4/K4 and §9.16 R4a v7 (A-07, B-05: superseded strata not struck).

## 4. Comparison of A and B

| Found by both | A-02 = B-01 · A-04 ≈ B-19 · A-05 = B R1-2 · A-06 = B R1-1 · A-08 = B-16 · A-18 ≈ B-05 · A-13 ≈ B-06/B-18 (partial) · A-11/A-14 ≈ B-11 (partial) · A-07 ≈ B-05 (same root) |
|---|---|

| One only | Holds? |
|---|---|
| A-01 | yes [F] (I-02) |
| A-03 | yes; B's "sound" is wrong (I-03) |
| A-09 | yes (I-02) |
| A-12 | yes (I-14) |
| A-15 | yes: the annulment target is required (09.13 L32) |
| A-16 | yes: S0103 L20 attributes only Option A to the PO/ARB |
| A-17 | yes (I-17) |
| A-20 | yes, and incomplete (I-14) |
| A-10, A-19 | not checked |
| B-02 | yes: BOOK-OUTLINE §3a Ch. 8 ends at §8.11. A supplied marks but did not raise it as a finding. |
| B-04 | yes, reproduced (I-06) |
| B-08 | yes, with more needed (I-12) |
| B-10 | in part (I-16) |
| B-12 | yes (title; §8.17.1 "lost first") |
| B-14 | yes (table 2) |
| B-17 | yes for the §1.14.1 instance; the others not checked |
| B R1-5 | in part (I-05) |
| B R1-6 | plausible: 01.13 L81 "did not [survive]" against "remained outside git". S0128 not checked. |
| B-03, B-07, B-09, B-13 | not checked |

**Verdict disagreements:**
- CON-0202: A's INVALID as stated is supported.
- CON-0237: INVALID as stated, on two defects ("silently"; head deletion).
- CON-0201, CON-0206, CON-0212: A promoted them to VALIDATED. They should stay PROVISIONAL (I-16, I-11, I-07).
- CON-0186: VALIDATED only after B's inconsistent anchors are fixed.

**Rotated-chapter counts (entry 58):** A 18 (Ch. 0: 7, Ch. 1: 11). B 19 (6, 13). Overlap 7 (Ch. 0: 1; Ch. 1: 6). Union 30. [D] A Lincoln–Petersen estimate, 18 × 19 / 7 ≈ 49, suggests about 19 undetected defects. The reviewers are not independent and detectability varies, so this is a rough indication, not a measurement.

## 5. R3 lists
- **A-06/R1-1:** far from complete. [F] Explicit references to §1.14.1 occur on 148 lines across Ch. 0, 1, 2, 5, 6 and 9, the register, the map and the outline (cons_i_inbound.py; the count includes §1.14 itself and §9.12/§9.13). These dependents need their own fix: §1.16.4 L94 ("needs a recorded order"), §1.17 L60 (ι readings (a)/(c) against Θ_f's (b)), and BOOK-OUTLINE L85, L92, L102 (they point to §9.7, not §9.13). Ch. 2 §2.2 L47 (supersession, not in D) is minor. References to STATE ≠ AUTHORITY stand.
- **A-01:** misses 08.00 L31 and the §8.17 heading. The F24 row already has "silently"; it needs A-09, not A-01.
- **A-03:** misses 08.00 L27; otherwise complete.
- **B R1-5:** lists Ch. 9 items that need no change, and misses THEORY-MAP L40, which does.
- **B-06:** add the CON-0212 and CON-0237 meanings, §9.13 "Cuts", §9.13.6 and §9.16 R2 (d) (I-09).
- **A-02/B-01:** add CON-0208's status (I-12) and CON-0215's Class C attribution (B-17).
- **B-04:** 34 lines cite §8.6 C1 or C3. Only the support count of C1's rule changes. Not checked one by one.

## 6. Limits
- Read: both reports; BOOK-OUTLINE; §9.13 in full; §9.16 L86–345; §1.10, §1.14.1 (to L140), §1.16.7, §8.17, §8.14 C5, §8.16 C2–C3; the register rows; the F22 and F24 rows. Ch. 2–7 only by grep.
- Corpus read: S0103 L16–24; S0164 L2036–2083; a whole-file line match of S0103 against S0164 (both on CORPUS-ALLOWED). No basis files.
- No models were run. dependency_graph `--down` gives about 223 lines for every item, so it is uninformative. Inbound references are by regex, explicit references only.
- I-09, I-10 and I-11 are deductions from the text, not model checks.
- Scratch: /tmp/claude-1891886374/-home-d0f38614-c3a6-41d3-9952-7f59ad699b2d-roshyara-personal-nrna1/16a0ac97-2df1-4d21-8fca-c43f5de5b1e2/scratchpad/cons_i_copy.py, /tmp/claude-1891886374/-home-d0f38614-c3a6-41d3-9952-7f59ad699b2d-roshyara-personal-nrna1/16a0ac97-2df1-4d21-8fca-c43f5de5b1e2/scratchpad/cons_i_inbound.py
