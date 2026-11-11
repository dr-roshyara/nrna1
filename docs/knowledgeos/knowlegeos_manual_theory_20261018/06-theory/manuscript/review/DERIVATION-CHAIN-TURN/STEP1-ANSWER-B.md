DERIVATION STEP 1 ANSWER B

Reviewer B (kos-lane-reviewer, Sonnet). This is a topic review (blind step). Independence is process independence only. Line references are to the files named in the brief, under W. Judgments of mine are marked [I]. I ran no tool and made no copy.

## 0. Answer in 5 lines
1. **Unit [I].** The unit is the **records over D and W (entry kinds and their fields) together with Req's reference definitions** (CE*, Q2's record cut, Q3's construction, g1). Req is not the premise set. Req selects which kinds, fields and references are required (§9.19.5, §9.20.3).
2. **Not chosen:** primitive concepts, which are undefined and not independent; mechanisms, which the laws do not quantify over; Req alone, which is both too weak and partly circular.
3. **One law follows end to end:** law 1 (I1), in its answer form, from CE* and D (§3). The derivation is definitional, in the sense of Th1 (§9.20.4 L81). It does not use the origin rule.
4. **Marking [I].** Of the 31 laws, only law 1 is *forced by Req* as a derived result without the origin rule. Laws 7, 8 and 10a are forced only **with** the origin rule (or its observable twin, P2 below). Seven more are forced only because Req states them. Ten are compatible. Five are independent. Seven split.
5. **Result.** The laws do not derive from any candidate **concept** as the book stands. That is a result, reported as such.

## 1. The unit of derivation

**Criteria (mine, fixed before choosing).**
- **D1** The unit is fixed by decided premises, with no open fork inside it.
- **D2** The laws' quantifiers range over the unit's elements.
- **D3** Non-vacuity: a trivial system (§9.19.6 T7; DC-7) does not satisfy the derivation by accident.
- **D4** Non-circularity: the unit is not defined by the laws it is to yield.
- **D5** Separability: one part can be removed or varied to see which law fails.
- **D6** Neutrality towards T9-03, G and F37.

| Candidate | D1 | D2 | D3 | D4 | D5 | D6 | Verdict |
|---|---|---|---|---|---|---|---|
| Primitive concepts (§9.19.5) | no: ~ status; 2 of 12 marked ✓; identity T9-14, T9-18 open | partly | — | **no**: A1 is both concept and law (§1.6 L13) | no independence model (TARGET-TOC Part II); removal models are definitional (§9.19.5 L203) | yes | rejected |
| Mechanisms | **no**: G fixed only up to regrouping (§9.21.3 (1), L45) | **no**: the laws constrain states, steps, runs and replies, not which mechanism made a step (§9.21 L40) | — | yes | yes | **no**: would prejudge T9-03 and T9-04 | rejected |
| Req alone (Q, O) | yes, except Q3's construction (not modelled) | answers only | **weak**: EF admits the one-witness system (T7); the forge and deleting-record meet Req and break T-laws (§9.1 C6 L90–91, result 1) | **no**: Req's items already contain laws (Q2 L48 "recorded loss"; O1 L109 idempotence; L110 atomic; Q1 L37) | partly | yes | rejected as premise set |
| **Records over D, W (kinds and fields) + Req's references** | mostly (five kinds outside D; Open rows, §9.20.7) | **yes for about 20 laws** (entries, positions, identity, citations, answers) | yes (§9.20.4 Th2, Th4) | partly: D contains some laws by decision (tombstone, citations earlier) | **yes** (M1–M6; removal models) | yes (§9.20.8 L157) | **chosen** [I] |

**Order of the chain [I].** Req selects its references. The references select the kinds and fields they read (§9.20.3 rows 2–3). The laws are proved over those. For the laws that are conditions on the record's evolution (T class), the proofs need one further premise:

- **P2:** the reference record at a cut is a function of the accepted-input history (Th1 step 7, §9.20.4 L78). This is the observable counterpart of the origin rule, §9.1 L96. **Every step that uses P2 is marked ⟨O⟩.** The origin rule is a premise, not decided.

**"Primitive" split into named senses, each with its test.**

| Sense | Object | Test | Home |
|---|---|---|---|
| **P-mech** | a mechanism not derivable as a function from a sequence of other members | no composite; case-split regrouping is not captured | §9.19.4 L172–177; §9.21 L42 |
| **P-conc** | a candidate concept | not derivable from the others (an independence model: a model where it alone fails), plus the three boundary tests | RESEARCH-PURPOSE L28–35, L49–54; BOOK-OUTLINE §1 |
| **P-field** | a field or kind of an entry | R7 (ii): read by a clause of a core reference (definitional necessity); otherwise "required by decision" (D) or "open" | §9.19.5 L203–226; §9.20.3 rows 2–3 |
| **P-base** [I, proposed vocabulary, not a book sense] | a premise: stated, decided, underived | its status label (decided, premise, open) | e.g. C1, D, P2 |

Three senses of "necessary" stay apart (§9.19.5 L223).

## 2. Why one chain, and what it bottoms out in
The chain ends at decided premises: C1 = constitute, D, Q-O (c) (Q2 returns every entry, §9.21.5 L111), Θ_f, and P2 for the T laws. It does not end at primitives. §9.20.8 L156 already notes that I1 holds "by construction" once 𝒮 = R. [I] That is the general shape: the derivable laws are **properties of the reference definitions**. Req adds that every system must agree with them in its outputs.

## 3. One law, end to end: law 1 (I1), as: every T answer to Q1 rests on recorded entries ending at a root act
**Premises**
- Q1 is met: the answer equals CE*(c, x, t; R≤s) on every record in D (§9.10 L39; §9.10.2 L19).
- C1 = constitute, Θ_f (§9.13 L82). A root is T by stipulation.
- D: a cited basis is in the record and at an earlier position (§9.13 L54–57); the record is finite.
- Φ, current-authority and KK as defined (§9.13 L118–123, L129, L132, L135).
- **Origin rule: not used.**

| # | Step | Justification | Objects defined? |
|---|---|---|---|
| 1 | A Req system's Q1 answer T equals CE* = T on the cut | premise 1 | CE* yes |
| 2 | CE* = T implies the record has an adoption y of c with t_y ≤ t and authorized(y) = T. By C1, F if c has no adoption on record | current-authority, L129 | yes |
| 3 | authorized(y) = T in KK implies some cited basis g has basis(g, y) = T, hence a(g) = T. A non-root act citing no basis is F (L119); a cited entry absent from the record is F (L123) | Φ is a fixed point (KK, L132); strong Kleene ∨ (L116) | yes |
| 4 | The same step applies to g if g is a grant. Citations point to earlier positions (D, L54–57) and the record is finite, so the descent stops | induction on record position | yes |
| 5 | The descent stops only at a root entry: a non-root act has a T basis or is F (steps 2–3), and a root is T by stipulation | L118, L82 | yes |
| 6 | The cut R≤s holds the adoption and the whole chain, since each cited entry is earlier. A tombstone keeps citations (L59–60), so the chain survives a removal, with a mark | D; `ce_star_cut` (L135) | yes |
| 7 | So no Req system answers T on a record with no adoption of c, or with no chain to a root. A system that does fails Q1 on D (R5, §9.13 L254, the constant-T evaluator) | 6, R5 | yes |

**Status.**
- This is **definitional** (Th1's kind). The informative step is the reading check of Φ's clauses.
- **Non-vacuity (D3).** With Q1 outside Req the law is vacuous. With Q1 ∈ Req, a system giving T without a chain fails.
- **What it does not give.** "Recorded" does not mean "performed outside". That is law 2, which needs the origin rule ⟨O⟩, and law 3, which is W.

**Falsifier.**
- (a) A record in D and a cut where CE* gives T for c with no adoption of c in R≤s, or with a chain not ending at a root.
- (b) A system meeting Q1 on D that gives T on such a record.
- (c) A clause of Φ that yields T with no cited T basis.

Model checks 4 and 19 (§9.13 L183) are the book's own sample evidence. I did not run them. A sample is not a proof; step 3 is the proof.

## 4. The laws of §9.11 (1–31, with sub-laws)
Marks:
- **F** = forced by Req: every system meeting Req satisfies the law. **F(s)** = forced because Req states it in an item or in that item's observable consequence. This is stipulation, not derivation.
- **C** = compatible: Req does not force it; a Req system violating it exists (or the law is outside D); Req ∧ law is satisfiable; the law is statable in C6, or with F36 (a)'s extensions.
- **I** = independent: Req's vocabulary cannot state it, or Req leaves it as policy. **AR** = application rule. **WA** = world assumption (the L side of §9.15 L43–46). **WA(E)** = environment assumption.
- ⟨O⟩ marks forced only with P2.

| Law (§9.11 row) | Mark | Reason |
|---|---|---|
| 1 I1 | **F** | §3 |
| 2 A1-as-form | C ⟨O⟩ | the forge meets Req and violates it (§9.1 C6 result 1) |
| 3 A1-as-fact | **I-WA** | W |
| 4a never-grant | C ⟨O⟩ | as 2, relative to faithful labels |
| 4b guard half | C | O1 requires a reply with the reason (L110), not that the reason is a recorded condition; guard set is policy (L108, L112) |
| 5 I-2 reason | C for presence; **I-AR** for presentation | the reason is a field D requires, read by no core reference (§9.20.3 L48) |
| 6 I-7 supersession | C ⟨O⟩ | the link kind is outside D (§9.13 L52); no clause yet |
| 7 H1′ | **F ⟨O⟩** | Q2 under (c) returns each entry whole or as its recorded loss (L45, L48) |
| 8 I-1(GKC) | **F ⟨O⟩** | as 7, "silently" read as "without a recorded act" |
| 9a idempotence | F(s) | O1 identity (L109) |
| 9b U, no act consumed twice | C | needs P-loss and one shared record; guard is policy (§9.11 R1; F36) |
| 10a positions grow | **F ⟨O⟩** | Q1 and Q2 at a fixed cut s keep their answers unless a recorded loss intervenes (L37) |
| 10b true act time | I-WA | W |
| 11a θ and version recorded | C | acts that set θ are not admitted to D (§9.13 L52) |
| 11b computed by the recorded version | I-WA | W |
| 12 atomic change | F(s), vacuous in the type | O1 L110; the refinement premise is an implementation assumption (§9.11.5) |
| 13 §1.7 visibility | C | Q6 is a candidate; deny-all meets it. Possible tension with Q2 under (c); I did not check it |
| 14 H5, H4 | I-WA | accepting a marked absence is O3 (F(s)); that absences are marked and entries sound is W |
| 15 Q1–Q5 correct | F(s) | this is Req; Q3's construction is not modelled |
| 16 cut, lineage / replay | F(s) / C | replay needs the pinned θ (not in D) and Lemma D's premises |
| 17 Req's inputs | F(s) | this is Req |
| 18 (i) held act, T reading | C | held acts are not a kind of D (§9.16 L192) |
| 18 (ii) L+E | **I-WA(E)** | an input must arrive |
| 19 STOP / REPORT / ESCALATE | C / F(s) / **I-AR** | REPORT is O1's reply; ESCALATE is delivery (§9.10.3 E) |
| 20a F33 deadlock | **I-AR** | F36 (b) unless F33 is a kernel law (§9.11 L96; not decided) |
| 20b outside act occurs | I-WA(E) | E |
| 21 K5, one answer whoever asks | F(s) | Q1 identity (L36), under equal act sets at the cut |
| 22 non-disclosure (O / HP) | C / C (by self-composition) | needs a read rule (OQ-25 open) |
| 23 Q5 totality | F(s) | Q5 consequences (L87) |
| 24 U across applications | C | needs a shared point (K4); F36 (a) |
| 25 key binding | I-WA | W |
| 26 one established assessment | C | establishment is outside D (§9.19 L59) |
| 27 §9.5 C1 | C (T reading, E1 deferred) / I-AR (delivery) / I-WA (otherwise) | |
| 28 K1 occurrence | I-WA | W |
| 29 content-derived identity | F(s) | O1 L109; the identity function is open (B-07, T9-18) |
| 30 merge by union | C | the merge is not a Req item; holds under unordered ties (§9.16 L101) |
| 31 §5.5 C3 | C (reply) / I-AR (delivery) | open, in part (§9.19.2) |

**Tally of the 31 numbers.** F: 1, 7, 8, 12, 15, 17, 21, 23, 29 (9). C: 2, 4, 5, 6, 13, 22, 24, 26, 30, 31 (10). I: 3, 14, 20, 25, 28 (5). Split: 9, 10, 11, 16, 18, 19, 27 (7). Total 31.

**What the table shows [I].**
- Derived F: only 1, 7, 8, 10a. Three of those four need P2.
- The other F rows are the item itself or its observable consequence. A law "forced by" its own statement carries no information.
- The compatible laws that matter most are 2 and 4a (origin), 9b and 24 (uniqueness), 22 (HP) and 24-type coordination.
- Independent laws sort as WA (3, 10b, 11b, 14, 18 (ii), 20b, 25, 28) or AR (5 presentation, 19 ESC, 20a, 27 delivery, 31 delivery).

## 5. Consequences (stated, not settled)
- **For deliverable 1.**
  - The §9.19.5 candidates are not the unit. They reappear as kinds, fields or observers: authority is the root, grant and adoption kinds plus CE*; history is act time and position; evidence is W's three kinds and g1; trust, conflict, contract and visibility are countermodels.
  - A Part II primitive table would be rows of fields labelled by sense (P-field), not concepts with a bearer.
  - No independence model for any concept follows from this chain.
  - No primitive is fixed.
- **For T9-03.**
  - U* does not fix G (§9.21). The laws derived here are G-invariant (§9.21 L40), so the chain says nothing about a minimal pool.
  - Mechanism minimality is not claimed from this, and primitive minimality is not claimed from mechanism minimality.

## 6. Disproof

| Answer | What would refute it |
|---|---|
| Unit = records + references | A law of §9.11 derivable from a concept or mechanism through no kind or field (then that unit suffices); or two systems with equal records and references that differ on a law I mark F or C (then U* is insufficient: candidates are laws 12, 20a, 24) |
| Concepts rejected | An independence model for a concept (a model where it alone fails while the others hold) |
| Law 1 derivation | §3 falsifiers (a) to (c); or a premise shown to be used that I did not list |
| Marks F | A Req system violating the law (a countermodel) |
| Marks C | A proof that Req forces the law, or excludes it |
| Marks I | A derivation of the law from Req and decided premises |
| "⟨O⟩ marks need P2" | A Req system, without P2, that forges or deletes silently and fails some Req observer |
| "Only law 1 is forced without P2" | The same, for laws 7, 8 or 10a |

## 7. Limits and what I did not do
- I marked laws from §9.11's one-line statements and from the sections I read. I did not open the home sections of most laws (§1.13, §1.14, §1.16, §9.4–§9.9) or §9.17–§9.18, §00.05, ch. 3, ch. 5, THEORY-MAP, CONCEPT-REGISTER.
- §9.13 was read to line 262 of 374 only. §9.15 to line 70. §9.16 lines 84–103. §1.06 to line 40. BOOK-OUTLINE lines 20–59.
- I did not run the lane's tools (no `proof_step_check.py`, no model scripts). Model counts cited are the book's reports.
- I did not use F13, F37, F6, T9-14, T9-18, B-07's two readings, T9-21, Question G, or G7's F13 part as decided. Law 29's identity function stays open (B-07). Question O (c) is used (laws 7, 8, 10a).

## Files opened (all under W unless stated)
- `03-methodology/REVIEWER-INSTRUCTIONS.md`
- `03-methodology/WORKING-INSTRUCTIONS-IN-FORCE.md`
- `06-theory/manuscript/review/DERIVATION-CHAIN-TURN/BRIEF-STEP1.md`
- `06-theory/RESEARCH-PURPOSE.md`
- `06-theory/manuscript/TARGET-TOC.md`
- `06-theory/manuscript/BOOK-OUTLINE.md` (lines 20–59)
- `06-theory/manuscript/chapter_09/09.01-kernel-scaffold.md`
- `06-theory/manuscript/chapter_09/09.10-required-capabilities.md`
- `06-theory/manuscript/chapter_09/09.11-kernel-type-from-req.md`
- `06-theory/manuscript/chapter_09/09.13-the-evaluator.md` (lines 1–262)
- `06-theory/manuscript/chapter_09/09.15-the-trust-boundary.md` (lines 1–70)
- `06-theory/manuscript/chapter_09/09.16-reassessment.md` (lines 84–103)
- `06-theory/manuscript/chapter_09/09.19-req-and-a-when-a-minimal-kernel-is-well-posed.md`
- `06-theory/manuscript/chapter_09/09.20-the-state-space.md`
- `06-theory/manuscript/chapter_09/09.21-the-interface-granularity-batching-and-output-forms.md`
- `06-theory/manuscript/chapter_01/01.06-nothing-but-an-act-confers.md` (lines 1–40)

All were opened with the Read tool, by full path.

**Files copied:** none.

**Commands and searches:** none. No Bash, grep, tool run, corpus source, allowed-list file or git command was used. The only calls were the 16 Read calls above. The two paths the brief lists but I did not read are given in §7.
