*Report of reviewer D2 (Sonnet), returned as hand-back text, verbatim (saved by the Professor, 2026-10-20; the reviewer's Write call was refused).*

# Report D2 (Sonnet), turn 7, commission D: §9.16 and the Ext→Src corrections

The Write of `W/06-theory/manuscript/review/TURN7-REASSESS/REPORT-D2.md` was refused ("Subagents should return findings as text"). The full report is below. The file does not exist. Please save it as that file.

Labels: Src (corpus line checked; sha256 matched CORPUS-ALLOWED for all eight sources), Ded, Int, Hyp, Ext. I read no other REPORT-*. Scratch scripts: `/tmp/claude-1891886374/-home-d0f38614-c3a6-41d3-9952-7f59ad699b2d-roshyara-personal-nrna1/16a0ac97-2df1-4d21-8fca-c43f5de5b1e2/scratchpad/t7d2_stress.py` and `t7d2_split.py` (python3 -I, importing `ce_model.py` only). Outside works were NOT re-checked on public pages; EXT-084–086 were judged against the register rows and my memory of the works.

## 1. Verdict table

| Item | Verdict | Basis |
|---|---|---|
| R1 dependency table | mostly right; one row missing (D2-07); R4's "iff" overstated (D2-04) | D2-04, D2-07 |
| R2 (b) order-independence under content ties | **sound**, proof adequate | table 3.1; my run: 1500 histories with act times forced into {1,2}, 8 linear extensions each, 0 answer variants under ties by id |
| R2 (c) the race | **genuine countermodel**; "F in both" not stated conditionally | table 3.2; my run: 17/1500 histories vary under ties by position (a positive control the book's sample lacks); D2-05 |
| R2 (a), (d) closure under citation | **not correct as stated; vacuous for records in D** | D2-01 |
| R2 with tombstones and I15 fields | not covered; (b)'s hypothesis fails | D2-02 |
| R3 | partly sound: law 21 is not a uniqueness; recording needs none; plural bases fine; completeness not shown | D2-03 |
| R4 "plane iff" | asserted, not derived | D2-04 |
| R5 tombstone changes no answer | sound as a consequence of the definition; model check vacuous; merge case missing | table 3.3; D2-02, D2-06 |
| R6 | correctly labelled a recommendation; one reason is a non sequitur | D2-09 |
| R7 | coherent but partly circular; one claim false as worded | D2-08 |
| Ext→Src corrections | mostly fair; overreaches and one omitted contrary passage | D2-09, D2-10, D2-12 |
| EXT-084–086 | within their abstracts as far as I can judge; closest works missing | D2-11 |

## 2. Findings (ID · finding · severity · evidence · correction · resolved when)

**D2-01 · high.**
- **Finding:** R2 (a)/(d) misstate the cut condition. (a) says a union of records each in D "need not be" closed under citation, because an entry of R₁ may cite an entry only R₂ holds. But D (§9.13.2) refuses a dangling citation, and the append guard admits an entry only if its citations are "already present". So a record in D contains everything it cites. Every union of records in D is then closed, and so is every union of per-record prefixes (D is closed under prefixes). The consistent-cut condition of (d) is automatic, not a condition. §9.16.4 ("the premise is checkable: D's append guard refuses at the entry") and (a) cannot both hold. Either applications replicate what they cite (records overlap, closure automatic), or their records are not individually in D (then each application's own Q1 refuses everything). Model check 18's "128 refused cuts" come from the second case.
- **Evidence:** Ded from §9.13.2. My run (`t7d2_split.py`): of 600 histories, both per-application records (with the root) were in D in only 61 splits. In the replicating variant (each record closed under citation, so in D), 3000 cuts across records gave 0 outside D. Check 18's assertion `closed == inD` is true by construction of `in_D`.
- **Correction:** State whether each Rᵢ is in D. If replication: say the cut condition is automatic, and name what remains of the causal premise (acyclicity of the union, which content-derived identity gives). If not: say application-local Q1 is refused and the condition is on merged batches. Re-run check 18 with Rᵢ in D.
- **Resolved when:** (a) and (d) say whether each Rᵢ ∈ D; check 18 uses Rᵢ ∈ D; §9.16.4 agrees with (a).

**D2-02 · high.**
- **Finding:** Content-derived identity, tombstones and the I15 fields are not reconciled with "the union absorbs duplicates" (R2 (b), R3 bullet 1, R5).
  - (i) A tombstone keeps the identity and drops content, so the identity cannot be recomputed from what remains. The union of a tombstoned copy (one application) and a full copy (another) has one identifier and two different entries. D's "unique identifiers" fails, so step 3 of (b) fails. A grow-only union cannot express removal: the next merge undoes the erasure unless a rule says the tombstone wins. Nothing says so.
  - (ii) If recorder and authentication status are in the identity, one act recorded twice is two entries (R3's "a duplicate is the same entry" fails; single use would count it twice). If they are not, one identity carries two field sets and the marks depend on which copy the union keeps. CE* values are unaffected (they read neither field); the marks are.
- **Evidence:** Ded from §9.13.2, §9.6 K2, §9.16 R2 setting. The model has no merge of equal-identity entries with different fields.
- **Correction:** State the identity function (which fields) and the merge rule for equal identity. Add a model case: full copy plus tombstone, merged in both orders.
- **Resolved when:** R2's setting names both; a check shows the same answers and marks for both merge orders.

**D2-03 · high (completeness claim only; the rest is correct).**
- **Finding:** R3 says K4 arises only from laws that bound a count. The need for a shared point is wider than uniqueness, and no completeness argument is given.
  - The book's own race (R2 (c)) is a cross-application ordering problem between a revocation or ending and an act under the revoked authority: not a count bound. Preventing that act (not just detecting it) needs coordination.
  - The standard test is invariant confluence: an invariant needs coordination iff merging two valid states can violate it.
  - Laws not examined: law 18 and law 31 ("applied once", "returned to its author") when several applications hold the held act.
  - Answers to the brief: law 21 is not a uniqueness (it asks for a function, one answer for equal entry sets, which R2 (b) gives). Of §9.11's laws 1–31 only 9b/24 and 26 bound a count, besides the corpus's role exclusivity. "Plural bases are no violation" is right for CE* (model `basis` is a disjunction over cited bases; χ = single basis).
- **Evidence:** Ded from §9.11 law table and the model; Src S0991 L1145 (the bound ∑ Authority_i ≤ 1).
- **Correction:** Replace "only laws that bound a count" by "laws whose invariant merging does not preserve", and run the 31 laws through it, with a verdict each (including law 18/31 and "no act under an ended authority").
- **Resolved when:** R3 has a per-law merge-safety column and no law is unclassified.

**D2-04 · high.**
- **Finding:** R4's "(P) iff" is asserted, not derived.
  - (1) §9.6.6a's own note (line 223): under F26 (a) "positions are a uniqueness across applications, so K4 = yes for positions". Θ_f still ties by position (§9.13.2; model default). So with θ as it stands, F22 is already (P) by positions. R3/R4 say "evaluation needs none" without the proviso that the tie rule is not yet chosen.
  - (2) §9.6.6 defines (P) as the serialization point and the evaluator CE in the kernel; R4 defines it by the point alone.
  - (3) "(C) otherwise" assumes K4-d needs no shared component. The detection aggregate and the merge check must run over every application's records; R4 lets the kernel "offer" the merge check (§9.6 step 4 says whether that is a kernel candidate "is open"). The aggregate is missing from the contract list (D, Θ_f, CE* version, tie rule).
  - (4) "A law is a kernel law" depends on the kernel's content, which is what F22 decides.
- **Evidence:** §9.6.6, §9.6.6a items 1–3, line 223, §9.16 R3–R4, §9.13.2.
- **Correction:** Derive both directions from §9.6.6's definitions, or relabel R4 as a sufficient condition for (P). Add the position branch, the aggregate and the merge check to the contract.
- **Resolved when:** R4 shows (⇒) and (⇐), or is relabelled; the position branch is stated.

**D2-05 · normal.**
- **Finding:** R2 (c) "with ties by identity it is F in both" depends on the identifier names. In the model the ending is `e`, the grant `x`, so `e` < `x`; with `x` < `e` the answer is T in both. It should say "the same in both". Also, R3's K4-d "earliest consumption by key counts" is manipulable: act time is recorder-asserted (law 10b is W; backdating admitted, §9.13.10), so a backdated consumption can displace an earlier legitimate one after the fact.
- **Evidence:** model race (`ids e, x`); §9.13.10; law 10b.
- **Correction:** Reword (c); add the backdating limit to §9.16.4's K4-d limit.
- **Resolved when:** the text says "the same in both"; K4-d's limit names untrusted act times.

**D2-06 · normal.**
- **Finding:** R5's check 17 is vacuous. `tombstone(rec,'g')` pops `content`, but `g` has none, so it only sets `removed`; no required field is dropped. R5's proof is correct given that a tombstone drops only fields Φ does not read, but then the claim is definitional, and F30's partial answer covers only non-required content (not actor, holder, scope, which an erasure law will want). In-place replacement also sits against append-only (law 7, law 10a) unless the tombstone is a new recorded entry; §9.13.2 does not say. Answers to the brief: Φ and the currency rule read `ok` (via D) and every required field, never `content` and never `removed` (only the marks read it). K5's premise carries the load: a signature over the whole entry cannot be checked after the drop.
- **Evidence:** model `tombstone` and check-17 base entries; §9.13.2; §9.11 laws 7, 10a.
- **Correction:** Say R5 is definitional and which fields are dropped; make check 17 drop a real field; say how the tombstone is recorded.
- **Resolved when:** check 17 tombstones an entry that has content; R5 and F30 name the dropped fields.

**D2-07 · normal.**
- **Finding:** R1 lacks a row for H1/H2 (law 10a, positions only grow) and for Q1's cut parameter R≤s under a merge. In a merged record without a serialization point, an existing entry's position changes when another application's entry arrives earlier in some linear extension. CE* does not read positions under a content tie (verified: model `.pos` is read only at lines 92, 99, 116, 118), but H2, law 10a and Q1's prefix cut do. The Lemma D and T1 rows should say the cut is a closed set, not a prefix.
- **Evidence:** `ce_model.py` grep; §9.8 H2; §9.11 law 10a; §9.10.2.
- **Correction:** Add the row, status "holds per record, not for a merge".
- **Resolved when:** R1 has the row and the cut notion is stated.

**D2-08 · normal.**
- **Finding:** R7 is coherent but partly circular, and one claim is false as worded.
  - (1) A world assumption is by definition "never read" and (ii) requires "read", so (ii) excludes world assumptions by the label. What carries the result is the line between evidence about X (read) and X, and no criterion draws it; that line was the question (§9.9.7).
  - (2) "None of them is read" (key binding, timing, completeness) is false for the first two: the evaluator reads the integrity outcome (`ok`, I13; D refuses on it) and the recorded act time (the key). By R7's own occurrence-evidence argument these evidences become primitive candidates.
  - (3) "Read by some reference definition of Req" is undefined for Q4, which has none; a candidate only Q4 would read fails (ii) because the definition does not exist yet.
  - (4) Primitivity then depends on the current CE* and θ.
  - Boundary cases and the rival are fair; "(ii) excludes no candidate" shows (ii) does its work only on the cases it was built for.
- **Evidence:** §9.16 R7; §9.13.2 integrity clause; §9.12 I13; §9.9.7.
- **Correction:** Give a criterion for evidence vs the thing evidenced; correct "none is read"; mark Q4's candidates undecided, not failing.
- **Resolved when:** R7 states the criterion and classifies the three world assumptions with it.

**D2-09 · normal.**
- **Finding:** Corpus lines used beyond what they say.
  - (a) R6 law 22: "Composition without a non-interference premise fails even in the corpus (S1519 L215)" is given as a reason for self-composition. S1519 L215 is about component non-interference in C₁∥C₂ ⊨ S, not information-flow non-interference; it does not bear on the choice.
  - (b) R3/§9.16.7 cite S1519 L234 for "0/11 invariants are definitions", grading these invariants as names. The same line says "the authority-uniqueness cardinality bound (§92.45) ... correctly stated". That is omitted.
  - (c) S0915 L1315 "This should be governed by policy." is called Src for the normative character of K4-p vs K4-d. It is about whether an agent may make a high-impact decision under partition with incomplete knowledge.
  - (d) §9.11.6 Abadi–Lamport row: S0943 L1033's "fairness" is "assuming required resources remain available" (starvation freedom), not a scheduler-fairness assumption.
- **Evidence:** corpus lines read: S1519 L234; S0915 L1296–1316; S0943 L1008–1040.
- **Correction:** Quote S1519 L234 whole; drop or soften (c); relabel (d) "same word, different sense"; drop (a) or give another reason.
- **Resolved when:** each row says what its line says; S1519's "correctly stated" appears.

**D2-10 · normal.**
- **Finding:** A contrary corpus passage for R2 is not mentioned. S0969 L1215–1264 (cited only for the partial order) contains Experiment 15: "Two distributed events have no causal ordering. System invents e₁<e₂. Expected: OrderingUncertain." A tie rule by content invents an order between acts with no causal order; the corpus expects U. This supports the F26 (b) rival that §9.16.5 names. Also S0915 L485–489 ("epistemic aggregation is not always a CRDT") bears on EXT-086 and is not cited.
- **Correction:** Cite both and say what each gives for the tie rule.
- **Resolved when:** R2's Src line includes Experiment 15.

**D2-11 · normal.**
- **Finding:** Missing prior work. R2/R3 (merge-safe invariants need no coordination, uniqueness does, non-monotone derivations need it for a consistent answer at a time) are the subject of invariant confluence and coordination avoidance (Bailis et al., "Coordination avoidance in database systems", PVLDB 2014/15) and the CALM theorem (Hellerstein 2010; Ameloot, Neven and Van den Bussche 2013). EXT-086's register row rightly says it does not give "uniqueness needs coordination"; these works do. (Ext, from memory, not checked on public pages.)
- **Correction:** Add them as EXT rows; use as R3's test (D2-03).
- **Resolved when:** rows exist and R3 cites them.

**D2-12 · normal.**
- **Finding:** Ext→Src, the informal-statement question. "Something bad never happens." beside Alpern and Schneider is fair for the informal distinction, and §9.11.6 says the prefix definitions and the intersection theorem are not in the corpus. The row's first cell ("every property is their intersection") is not Src. The S0916 bitemporal row: the corpus has valid time, transaction time and a third, "Knowledge time" (L85–89), and names the model bitemporal (L164–166); the row says "both axes". The Searle row (S0534 L162, L1229) is hedged correctly (no regulative rules in the corpus, by grep).
- **Correction:** Add the third axis to the S0916 Src sentence.
- **Resolved when:** the row mentions it.

## 3. Proof-step tables (derivations judged adequate)

**3.1 R2 (b): CE* is the same on every linear extension under content ties**

| Step | Claim | Check |
|---|---|---|
| 1 | CE* reads order only in the key and D's citation rule | Ded; `ce_model.py` `.pos` at lines 92, 99, 116, 118 only; Q1's prefix parameter is outside the model (D2-07) |
| 2 | with content ties the key is a function of the entries | Ded; needs identity to be a function of the entry (D2-02) |
| 3 | every linear extension passes D's citation rule | Ded; also needs unique identifiers (D2-02) |
| 4 | Φ, its least fixed point and the WFM are the same | Ded from §9.13 R1 |
| test | 1500 forced-tie histories, 8 extensions each: 0 variants by id | model, form only |

No counterexample found. Two entries with equal act time and equal content-derived identity are the same entry, assuming an injective identity.

**3.2 R2 (c): the race**

| Step | Claim | Check |
|---|---|---|
| 1 | x (A to B under g, t=3) and e (P ends g, t=3) cite neither the other | Ded; both orders in D |
| 2 | position ties: e before x gives c = F; x before e gives T | Ded; model gives T or F |
| 3 | identity ties: the answer is fixed by the identifiers, the same in both orders | Ded; value F only if id(e) < id(x) (D2-05) |

**3.3 R5: a tombstone changes no CE* answer**

| Step | Claim | Check |
|---|---|---|
| 1 | Φ and the currency rule read kind, citations and required fields (and `ok` via D) | Ded; model |
| 2 | a tombstone keeps all of them | by definition (§9.13.2) |
| 3 | so Φ, its fixed point and every answer are unchanged; only marks change | Ded, conditional on K5's integrity premise; single record only (D2-02) |

## 4. Coverage

- **Read:** all of §9.16; §9.13.2–3 and the tombstone bullets; §9.6.6/§9.6.6a and K2/K4 text; §9.11 law table and R3; §9.15 G4/K4 rows; the Ext→Src rows in §9.8.4, §9.10.9, §9.11.6, §9.12.8; `ce_model.py` (Record, in_D, basis, key, tombstone, marks, checks 17–18) and run v7; EXTERNAL-SOURCES rows EXT-084–086.
- **Corpus lines opened (sha256 matched, all eight):** every line cited in §9.16 and the Ext→Src rows (S0943, S0990, S0916, S0534, S0915, S0969, S1519, S0991), with context. All quotes are present as written; the issues are in their use (D2-09, D2-10, D2-12).
- **My runs:** `t7d2_stress.py` (R2 (b) with forced ties: 0/1500 variants by id, 17/1500 by position); `t7d2_split.py` (D2-01).
- **Not examined:** the expressibility claims of R6 for laws 9b and 20a (rest on §9.11 results I did not re-derive); Ch. 0–2 basis files; outside works on public pages; P-perf (§9.16 says not addressed, which is as stated).
- **Facts / deductions / opinions:** line contents, model outputs and run counts are facts; D2-01 to D2-04 and D2-06 to D2-08 are deductions from the quoted texts; D2-11 and the weight given to invariant confluence are my opinion from memory of the literature.

## Summary (at most 10 lines)
1. R2 (b) is sound: 0/1500 forced-tie histories vary under ties by identity. R2 (c) is a genuine countermodel (17/1500 vary under position ties), but "F in both" holds only for the model's identifier names.
2. High: R2 (a)/(d) are vacuous for records in D, because D's dangling-citation refusal makes every union and every cut closed. Check 18's 128 refusals come from records not in D (61/600 splits had both records in D).
3. High: tombstones and the I15 fields break "the union absorbs duplicates" (equal identity, different entries, unique identifiers fail; erasure undone by merge; marks order-dependent).
4. High: R3's completeness and R4's "plane iff" are not derived. The position-tie branch (§9.6.6a) is omitted, (P) is redefined without CE, the detection aggregate is outside the contract, and the revocation-vs-use race is not a count bound (invariant confluence is the right test).
5. Normal: the R5 check is vacuous; R1 lacks the H2/positions row; R7 is partly circular and "none is read" is false (`ok` and act time are read); several Src uses overreach (S1519 L234 and L215, S0915 L1315, S0943 L1033); S0969 Experiment 15 (contrary) is omitted; invariant confluence and CALM are missing.
6. The report file could not be written (Write refused); the full report is in this message.
