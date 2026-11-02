T9-02 STEP 1 ANSWER A

*Reviewer A (kos-lane-reviewer, Opus). Blind step: I opened none of the Professor's T9-02 work (§9.20, the index, the change log, TODO, BOOK-OUTLINE, the rest of the review folder). My independence is independence of process, not of person. Labels: Src/Ded/Int as in the book; **[I]** marks my own judgments. W = `docs/knowledgeos/knowlegeos_manual_theory_20261018/`; all line numbers below refer to files under W/06-theory/manuscript/.*

## 1. Current position (Step 1)

| Item | Where the book states it |
|---|---|
| T9-02 is "undefined". The test used a three-part toy state, and the kernel's real attributes are not fixed (F13). The row asks for "one table of attributes, value sets and kinds" | brief L30 |
| The kernel is the tuple (𝒮, M, I, A) (Ext). The type C6 is (𝒮, X, M, G, I_S, I_T, I_R, Req). "Its content stays open: 𝒮, the acts, and Req" | §9.1 L7, L92–100, L122 |
| The toy state is (claims, acts, standing), with 64 states. F13 is "open; the toy state replaces it" | §9.1 L44, L133 |
| Only positive content: "Let 𝒮 contain the record ρ"; ρ is empty at s₀. Open: "the content of 𝒮 beyond 'contains the record'" | §9.11 L73, L76, L129; laws 1 and 7 at L33 and L40 |
| The state space is not a capability; it is the type's 𝒮 | §9.10 L201 |
| Existence and minimality hold only for a fixed finite pool over a finite 𝒮 (T1, T2, T3, T7, T9, T10). "The pool needs 𝒮" | §9.19 L159, L166, L231, L238–274, L361 |
| Q4 no longer waits on F13 (U2); the F13 part of G7 stays open | §9.19 L114, L396; §9.18 L82 |
| Decisions that constrain this turn: U1 (b), U3 (b) granularity unfixed, U5 (a) EF, U6 (a) per kind with D clauses to follow | §9.19 L394–398 |
| Decisions that constrain this turn (cont.): C1 = constitute; D allows citations of earlier positions only; DB-1; kinds evid, ewdr and erst adopted (T9-19) with D_S and D_W | §9.13 L54–56, L70, L81; §9.12 L64; §9.18 L107 |
| F13: two attribute systems (Ch. 1, Ch. 3) never mapped onto each other; evidence status has 3 values against 4; each attribute needs a bearer (F37) | THEORY-MAP L134; §3.1 L91 (DB-5: the bearer stays a fork) |
| The candidate primitives are a status table, not a set of members | §9.19 L185–227 |

## 2. Derivation of the state-space requirements (Steps 2–3)

**Candidates.**
- **S-pt:** a one-point state.
- **S-tab:** an item → attribute-vector table holding current values (the toy's form, and the form the T9-02 row's deliverable presupposes).
- **S-btab:** S-tab made bitemporal, with values at every cut.
- **S-rec:** ρ, a finite sequence of typed entries carrying the fields that some reference definition, D clause or guard reads, with s₀ = ⟨⟩.
- **S-rec+:** S-rec plus a stored cache of derived values.
- **S-Ρ:** a record set together with carriers.

**Trivial cases (Ded).**
- **Empty Req:** every 𝒮 works, S-pt included, and ∅ is the kernel (§9.1 L31). So a state space is minimal only **relative to Req**, as the kernel is relative to (A, Req) in T1.
- **S-pt under Req:** an observer is constant, but CE* gives T on some records of D and F on others (§9.10 L174–175). O1 needs the accepted act to be "in the record" afterwards (§9.10 L110), which a single fixed state cannot do. So S-pt fails as soon as Q1 or O1 is in Req.

**Separating counterexamples.** I checked them in a toy model ([I]; 2-valued, no annulments or ties, not CE*; `t902-a-statespace.py`, 517 histories of length ≤ 4).

| # | Pair | Result |
|---|---|---|
| X1 | h1 = ⟨root r, r adopts c⟩ and h2 = ⟨root r, grant g r→b, b adopts c⟩. Q1(c) = T in both, so S-tab gives them one state. Append "r ends g": h1 stays T, h2 becomes F (Θ_f's P: an ending of a cited grant after the adoption ends its authority, §9.13 L87–88) | Even **current** Q1 does not factor through S-tab. The table is not a congruence under O1, which is S1609's "transformation-compatible" condition [S1609 L595, via §9.8 L71] |
| X2 | Histories with equal Q1 at every cut but different entries | S-btab fails Q2, whose reference is "the record itself, cut at s" (§9.10 L45, L50). It also fails Q1's output "with the acts the answer rests on" (L34) |
| X3 | S-rec+ with the cache equal to CE*(ρ) on S_I | The valid states are in bijection with S-rec, so the cache draws no distinction. A cache that can disagree is the drift §3.2 L41 forbids, so it is not admitted under CON-0162 (ii) |
| X4 | S-Ρ with application records counted | A kernel with no mechanisms meets Req_O (§9.19 L157). The question is decided by the boundary B, not by Req |

Class counts in the toy: S-tab gives 2 classes, S-btab 15, Q1 and Q2 at all cuts 172, ρ 517. These are form only, and a finite model proves nothing general.

**Ded, from X1 and X2.** Under Req's core, with C1 = constitute and Q2's reference as written, 𝒮 must determine ρ up to the fields that nothing reads. The coarsest adequate 𝒮 is the right congruence (the Nerode quotient) of histories under the observers, invariants and guards. *Ext, prior work:* minimal realisation and Myhill–Nerode. No EXT entry exists; one is needed if this is used.

**Distinctions, against the commission's five questions.**

| Distinction | 1 Requirement | 2 What rests on it | 3 Without it | 4 Simpler form? | 5 Placement |
|---|---|---|---|---|---|
| the record ρ (entries, not values) | Q2; Q1 at cuts; Q1's acts | R1, H1′, law 9b, Lemma D | X1, X2 | no (X1, X2) | Kernel (Ded) |
| order: sequence, not set | D's "earlier **position**" (L54–56, decided); Q2 "in record order"; law 10a | D, cuts, replay | CE* values survive (positions unread, L26); D and Q2 fail | a citation DAG with D_acyclic (rival, L70) | Kernel, **by decision**, not by CE* |
| entry kinds with D's required fields | CE*'s clauses (inputs table L100–110); D_W | T1–T10 settings, G1 | CE* undefined | drop unread fields: recording time, reason (ι), delegation parent, I14, revocation scope (required by D, unused, L94) | read fields: Kernel. Unread: not shown necessary; reason depends on whether law 5 (I-2) is a kernel law |
| history variable H | law 9b | C6 r5, R1 | — | derived from ρ under P-loss (§9.11 L77) | not a component |
| carrier and designation | K2-d | §9.17 R1 | — | designation entries are in ρ; the carrier is outside B (§9.19 L155) | carrier outside 𝒮 |
| covers relation, identity link (K3) | CE* inputs | G1, Q3 | Q3 undefined | a type parameter (like Θ_f) **or** state set by some input | **Undecided.** No O-item accepts covers, yet K3 calls it "accepted as input" (§9.15 L76) [I] |
| θ(u) | Q1's input u | H3a, F31 | — | fixed to Θ_f (I7 "not read from the record") | a parameter now; entries if θ varies |
| held acts (§5.6) | law 18 → (c) reading (i), decided (§9.11 L96) | that the held act is applied in the step that creates its object | law 18 (i) cannot be expressed | an entry kind "held"? (§9.10 L229) | **Undecided**: no kind in D |
| clock | — | — | — | none: no input-free step was found (§9.11 L98) | not needed |
| kinds outside D (registration, link, retirement, establishment, declassification) | O1 per kind (U6) | §9.10.7 mappings | O1/D gap | — | Undecided (T9-21) |

## 3. Outcome (Step 4): **Partially resolved**

**Established (Ded, under C1 = constitute, B with one designated record and K1, Q2's reference as written):**
1. 𝒮 is a set of records over a typed entry alphabet, with S_I = D_S ∩ D_W and s₀ = ⟨⟩.
2. No item-attribute table, current or bitemporal, can be 𝒮.
3. Item-level values (current authority, grade, lineage) are observer outputs, not components.
4. The minimal 𝒮 is relative to Req.

**Not established:**
- the entry alphabet for the kinds outside D;
- covers and θ (state or parameter);
- held acts;
- sequence against DAG beyond D's decision;
- S-Ρ under another boundary;
- fields read only by future reference definitions (Q6, Q7).

**[I] Fixing 𝒮 is necessary, not sufficient, for fixing the pool.** G and batching stay unfixed (U3). §9.19 L34 ("fixing it needs the state space") should not be read as "the state space fixes it".

## 4. F13's attributes, classified

Key: **F** = an entry field read by a reference definition, D clause or guard (kernel state). **Dv** = a derived observer value (not stored). **X** = outside 𝒮. **U** = undecided.

| Attribute | Class | Deciding lines |
|---|---|---|
| identity I-0 (§1.10) | entry identity: F (content-derived); item identifier k: F as the object field; how it is assigned: U | §1.10 L90; §9.10 L33, L93; §9.19 L189 (T9-18); registration not in D (§9.13 L52) |
| provenance (§1.3) | act provenance: F (actor, recorder, authentication status, I2, I15). Claim provenance: U (registration outside D); F13a open | §9.13 L50–51; THEORY-MAP L136 |
| evidential grade (§1.3) / evidence status (§3.1) | Dv: g1 over evid/ewdr/erst at a cut (U2). Those kinds' fields are F. The 3-against-4 mismatch dissolves **for 𝒮** [I] but stays a vocabulary-mapping question (G7) | §9.19 L396; §9.18 L82, L111–112 |
| standing (§1.3) / governance status (§3.1) | standing: Dv (CE*, "never reads a stored status"). Lifecycle part: U (retirement and supersession outside D). F6 is not decided here | §9.12 L64; §9.13 L52; §3.1 L52–58 |
| visibility (§1.7) | U: Q6 is a candidate, OQ-25 is open, declassification is not in D; countermodel for the core | §9.10 L94; §9.19 L201, L221 |
| owner (§1.11) | §1.11's maintainer: returned by Q2, read by no clause → not shown necessary (U/application). **Homonym:** the grant's owner (an application, F22) is F | §9.10 L99; §9.13 L29, L39; §1.11 L33 |
| nature (§3.1) | U: I1's "kind" is listed as read, but I could not find a Θ_f clause that depends on it; F7 is open | §9.12 L22 (I1); §9.13 L104 |
| representation | X: a carrier property, and the carrier is outside B | §3.1 L77; §9.19 L155 |
| authority/canon scope | the scope field on acts and grants: F. Canon scope of an item: U (covers undefined; no bearer under (B)) | §9.13 L98; §9.12 L95 |
| operational state | X/U: CON-0006 is invalid and splits three ways; execution is outside the kernel | §3.1 L40–50; §9.19 L122 |

**[I] Framing finding (high).** In the book the kernel's attributes sit on **entries (acts)**; item attributes are derived. So F13's requested "table of attributes" for knowledge items has the wrong type. The deliverable would be:
- a table of entry kinds and their fields;
- a mapping from each item attribute to its derivation.

F37 then moves from "the bearer of a stored value" to "the index type of an observer". This is not decided here.

**[I] Finding (normal).** §9.8 L69 says the record's necessity "rests on CON-0134", since "a bitemporal store of computed standings would answer Hist". That holds for Q1's *values* only. Q1's stated output (with acts) and Q2 force the record without CON-0134 (X2).

## 5. Disproof (what would refute this answer)

- A core reference definition that reads a stored item attribute not derivable from ρ. Examples: C1 = represent; standing conferred by practice (§9.12's rival).
- Dropping Q2, Q1's acts and Q1 at cuts, with no future ending or annulment kinds. S-tab could then be a congruence.
- A required component that cannot be an entry: a timer, a held-act store, or covers that changes outside the kernel's steps.
- Two records that differ only in a field I call F and agree on every observer, D clause, guard and mark. That field would then not be necessary.

## 6. Questions for the Principal, and separate work

- **Covers and the identity link:** a type parameter, or state with an input item? This is needed to close 𝒮.
- **Whether T9-02's deliverable is retyped** to entry kinds and derivations: a definitional choice, not closed by me.
- **Separate work:**
  - D clauses for the five kinds (T9-21), then §5.6's held-act kind;
  - a congruence check of S-rec under the real CE* (`ce_model`), which I did not run;
  - T9-03 is not touched.

## Limits

- Read whole: §9.1, §9.10, §9.11, §9.19, §3.1, §3.2, §1.3, §1.7, §1.10, §1.11, RESEARCH-PURPOSE.
- Read in part: §9.12 L1–80; §9.13 L1–140; §9.17 L1–80; §9.8 L66–75; §9.16 L370–395.
- Seen only through searches: §9.15, §9.18, §9.8, THEORY-MAP.
- Not opened: §9.2–§9.7, §9.9, §9.14 (only searched).
- No corpus source opened. No tmp/tools tool run. The toy is my own, and nothing was compared against CE*.

---
**Files opened:**
- brief `06-theory/manuscript/review/T9-02-TURN/BRIEF-STEP1.md`
- `03-methodology/REVIEWER-INSTRUCTIONS.md`
- chapter_09: 09.01, 09.08 (L66–75), 09.10, 09.11, 09.12 (L1–80), 09.13 (L1–140), 09.16 (L370–395), 09.17 (L1–80), 09.19
- chapter_01: 01.03, 01.07, 01.10, 01.11
- chapter_03: 03.01, 03.02
- `06-theory/RESEARCH-PURPOSE.md`; `06-theory/THEORY-MAP.md` (L125–160 via sed; the output overflowed to a harness file, `/home/nab-raj.roshyara@dg-nexolution.de/.claude/projects/-home-d0f38614-c3a6-41d3-9952-7f59ad699b2d-roshyara-personal-nrna1/16a0ac97-2df1-4d21-8fca-c43f5de5b1e2/tool-results/bjbbft1ff.txt`, which I did not open)
- `06-theory/CONCEPT-REGISTER.tsv` (grep only)

**Files copied:** none.

**Scratch files written:**
- `/tmp/claude-1891886374/-home-d0f38614-c3a6-41d3-9952-7f59ad699b2d-roshyara-personal-nrna1/16a0ac97-2df1-4d21-8fca-c43f5de5b1e2/scratchpad/rev-t902-a/t902-a-statespace.py`
- `/tmp/claude-1891886374/-home-d0f38614-c3a6-41d3-9952-7f59ad699b2d-roshyara-personal-nrna1/16a0ac97-2df1-4d21-8fca-c43f5de5b1e2/scratchpad/rev-t902-a/t902-a-witness2.py` (unused stub)

**Commands and searches:**
1. `ls` of chapter_09/, chapter_01/, chapter_03/; `wc -l` of the allowed sections.
2. grep "𝒮|F13|T9-02|state space|toy state|three-part" over the allowed ch. 9 sections (09.01–09.19), the four ch. 1 sections and 03.01.
3. `sed -n 1,80p` of 09.17; grep "D_W|D_S|g1|evidence entry|fields|kinds" in 09.18; `cat -n` of 01.03, 01.07, 01.10.
4. `wc` and grep "F13|T9-02|state space|F37|F6" in THEORY-MAP; `sed` of THEORY-MAP L125–160 and RESEARCH-PURPOSE L1–140.
5. `sed -n 1,80p` of 09.12; grep for the G/K rows in 09.15 and for "R7" in 09.16; `sed` of 09.16 L370–395.
6. grep of 09.08 for H1′/H2/H3/Lemma D; `wc`/`sed` of 03.02; grep of the register for CON-0183/0188/0184/0185/0162/0261/0189.
7. `sed` of 09.08 L66–75; grep for "L11" in 09.15; greps for "never reads a stored status" (09.12), "stored as an independent value" (03.02) and "covers" (09.15).
8. `python3 -I` on t902-a-statespace.py, and a second `python3 -I` run (stdin) searching for a witness with both histories T.

No git commands.
