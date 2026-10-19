*Reviewer B's (Sonnet) steps 2–3 report, theory turn 3, verbatim (saved by the Professor, 2026-10-19).*

# REVIEW REPORT — TT3 (reviewer B)

*Topic review of §9.8 (theory turn 3), STEP8-AND-SCAN, CON-0148 and CON-0149, plus the C15 settlement (commit 39612b8d4) as changed theory (Rulebook step 9). I re-read my saved step-1 inventory first. Independence is process independence, not personal independence. I read the book sections named in the commission, the C15 REVIEW-REPORT and SETTLEMENT, and the git diff f16e9d602..39612b8d4. From the corpus I opened only S0095, S2782 and S0129 (as in step 1), plus the cited lines S0079 L32 and L55–L74, S0014 L66–L68, S0015 L58, and S0083–S0086 at the cited lines. I did not open any review/*-A.md file. No computation was run. Everything below is reading and argument, and "small-scope" statements are by hand, not by script.*

## 1. Assessment
- **What holds.** The conditional reduction (a history query is the evaluator applied to a cut of the record, if the record is append-only, carries two orders and versions its inputs) is valid. The evidence quotes check (S0095 L38, L162, L218, L221; S0129 L102; S2782 L775, L779, L868, L947, L3604). H2's independence of H1 is the best-supported point, because the seq-16 transcription [S0095 L218–L221] is a real case where recording order and act order differ.
- **What does not hold as written.**
  - "H1–H3 are independent of the six other candidates" (§9.8.8 item 2, the plain-language section) is contradicted by §9.8.6 item 3 and §1.2: H1 fails inside authority as the book defines it.
  - H3 is partly derivable, because the book itself says policy values are recorded acts.
  - M1 keeps supersession links yet loses all history, which is the question the topic asks.
  - D is nearly definitional, and Hist silently fixes the θ-time index that F27 leaves open.
  - The three properties are all must-never constraints. Completeness, truthfulness and attestation (my M4, M6, M7) are absent, and S0095's central lesson (X-2, off-record act) is therefore not modelled.
- **Largest risk.** The headline "history is not a primitive; the record is" may be a relabelling. H1–H3 are three new properties, and "the record" has no minimality test applied to it. The corpus base is two lines of support, both of which assume append-only as a rule and none of which argues derivability.
- **Changed theory (C15 settlement).** The edits answer their findings. New defects are minor, except that THEORY-MAP still carries three withdrawn formulations.

## 2. Findings (ranked)

| # | Sev | Type | Claim | Line | What is wrong or missing | Consequence | Correction |
|---|---|---|---|---|---|---|---|
| T1 | major | defect | "H1, H2, H3 mutually independent **and independent of the six other candidates**"; "Each of the three can fail while all the others hold" | §9.8.1, §9.8.8 item 2, §9.8.5 M1 "check"; vs §9.8.6 item 3, §9.8.7 bullet 2, §1.2 L53 | §1.2 defines authority as **checkable**: "a claim's history is valid iff … every change is labelled by a recorded act". The outline's authority row cites §1.2 and §1.12–1.14. So the antecedent of §9.8.7 bullet 2 is true in the book as written: M1 breaks authority as the outline defines it. §9.8.6 item 3 says as much ("H1 is prior to authority's checkability") while result 2 says the opposite. The inference "authority is not one primitive either" does not follow. The other reading is that H1 is part of authority's definition, so history depends on authority. | The independence of H1 from the six is undecided and depends on which authority is meant (A1 as a fact, or A1 plus checkability). It is stated unconditionally in three places. | State result 2 as conditional on the split of authority into A1-as-fact and verifiable standing (the book's own valid-vs-verifiable, §1.12). Mark H1 independent only of A1-as-fact. Delete "authority is not one primitive either". |
| T2 | major | defect | H3 "not derivable from H1 and H2" (M3) | §9.8.3, §9.8.5 M3; §9.7.4 step 4 ("those values are acts (A1), and their record is the place TT1 found delegable") | If θ (policy values P, ρ, υ…) are recorded acts, then θ(s) = the latest policy acts in R≤s, and H3 follows from H1, H2 and A1. M3 gets H3 to fail only by keeping θ **outside** the record. That is a model where a policy change confers standing without a recorded act, which breaks A1 (and "recorded rule must reach the actor", §9.5), i.e. it fails "other primitives hold". Only the **definition of CE as code or spec** (pinning, §1.16.6) is genuinely outside R. H3 conflates two things. §9.8.9's last open question ("is versioned policy part of policy or of the record?") is this question, left open while the result asserts independence. | H3's independence is PROVISIONAL and probably holds only for the evaluator's definition, not for θ. | Split H3 into H3a (policy values are entries) and H3b (the definition of CE is pinned or recorded). Show M3 for H3b only, or show it keeps A1. |
| T3 | major | defect | M1: "Superseded claims are kept as objects with a link (I-7) … each new entry replaces the previous entry … Hist has no input" | §9.8.5 M1 | The topic question is whether supersession links plus time suffice. M1 keeps the links and, apparently, the times, and then asserts the earlier cut is gone. If superseded claim objects carry recording times, the content history at any s is recoverable from the chain. What is lost is the **act entries** (standing changes), not the claims. M1 does not say that, and it does not say what I-7 retains. As written the model is under-specified, or it refutes itself. | M1 is not a model until "kept" and "replaced" are typed (claims versus acts). The question "can I-7 + time replace H1?" is not answered. Content-history may be derivable from I-7. Standing-history needs the acts. | Give M1 as an explicit small structure: item, claim chain, act entries, times. State which Hist queries survive. Answer I-7 + time explicitly. |
| T4 | major | omission | Faithfulness, truthfulness, attestation, completeness (my step-1 M4, M6, M7) | §9.8.2 (definition of Hist "as the record stood"); §9.8.6 item 4; S0095 L50–L58, L141, L162; S0129 L4, L147–L149; S2782 L875–L891 | Hist is **defined relative to the record**. So H1–H3 can all hold, and D still delivers a wrong or empty answer, if (a) an act is missing (S0095 X-2: START given, never recorded), (b) the entry's author could not author it (S0129: "self-declared, not attestable"; Recording ≠ Asserting), or (c) the record is empty. The trivial case is the empty record: H1–H3 hold, D yields CE(∅). The conjunction H1–H3 is a set of must-never constraints with no must-do (the book's own trivial-solution test, §5.7 C1 and the instructions). §9.8.6 item 4 imports a truth notion ("a silently reconstructed one is not [truthful]") that the definition cannot express. S2782 L875–L891 ("append-only ≠ correct: wrong, duplicate, mis-ordered, invalid events") is not used. Partial cover: item 4 handles the **recorded loss** (reflexive entry) informally, so M4 is half covered. | "History is derived from H1–H3" is true only for history-as-the-record-says. The truthful-history property that S0095 and S0129 are about is a fourth thing. Partly A1 supplies it (an unrecorded act confers nothing), which §9.8 could use but does not. | Add H4 (completeness and attestation: every standing-changing act has an entry by an actor entitled to author it; changes to the record are entries) or state that Hist is record-relative by definition and truthful history is out of scope. Add M4 (silent truncation with reflexive entries), M6 (unattestable author) and M7 (empty record) as models. |
| T5 | major | omission | H2 and the cut "entries recorded at or before time s" | §9.8.2 (Cut), §9.8.3 H2; S2782 L2124–L2156, L2158–L2182 | A cut by recording **time** is a prefix of the sequence only if τ is non-decreasing in position. The book gives no such property. S2782 L2124–L2156 ("timestamp(e1)<timestamp(e2) does not imply e1<e2"; wall-clock ≠ causal order) is cited by me in step 1 and unused. The estate has 16 records, each with its own seq (S0128 L48), so a cut across records by recording time needs comparable clocks. This is my step-1 item 6 and is MISSING. | H1 "R≤s recoverable from R≤s′" and the cut as a prefix are ill-posed across records. M2 then reads "positions but no times" while H1 and H3 in their stated form quantify over time s, so they presuppose H2 (see T9). | Add: τ is monotone with position within a record, and a stated rule for cuts across records (common clock, happened-before, or the F26 option). |
| T6 | major | defect | §9.8.8 item 4: "The estate keeps H1 at the level of git, not of each file" | §9.8.5 M1 estate case, §9.8.8 item 4; S0129 L187 | S0129 L187 records that 28 grants and 33 transitions (the authority record, the same record M2 measures) stayed **outside git** (`.gitignore:25`, `:32`) while the narrative documents were committed. For that record, H1 at git level did **not** hold at the time (2026-08-19). The corpus's strongest dated instance of M1-type loss is unused (§9.8 cites S0129 only at L102). Whether the position changed since is not shown (option B′ was not adopted, S0129 L89). | Result 4 is true for session logs and review documents (S0095 L38) and false or undetermined for the authority record. | Scope result 4 to the named records. Cite S0129 L187 as the M1-type instance, with its date. |
| T7 | major | defect | CON-0149: "the record … missing from the list of candidate primitives; … in place of 'history'" | §9.8.6 item 2, §9.8.7 bullet 1, CON-0149 | The method of §9.8 (a primitive needs an independence model) is not applied to "the record". The record decomposes into entries with identity (I-0), an order (relationships; but §3.8's four orders are authority, production, realization and containment, not temporal), and an invariant (prefix preservation, H1). §9.8.7 bullet 1 then absorbs H1 into the record's definition, so "three independent properties" becomes "one primitive with two axioms (H2, H3)". The three-versus-one count is thus a matter of definition. Also **time/order** is not a candidate primitive in the outline, so H2's independence from time is untested. Tagged Hyp, which is right. But §9.8.1 states "the record itself is not on the list of primitives" as bare fact, and §9.8.8 item 3 says the record is a candidate "missing". | The Hyp is not supported by the section's own method. The net effect may be "history renamed to record", not a reduction. | Apply the test to the record (a model where the record fails while identity, order, authority, … hold). Or keep CON-0149 as a naming proposal and say so. State the primitive count before and after. |
| T8 | major | defect | D: "history needs no stored field and no separate mechanism"; "D shows … the evaluator applied to past cuts" | §9.8.4 "What D shows" | H2 stores τ and t_x, H3 stores versions, H1 is a retention mechanism. The sentence cannot mean "nothing stored". D itself is two definitional steps: Hist is defined as "standing as the record stood at s", the cut R≤s is TT2's definition, and CE on a cut is by construction that value. The content is the necessity direction (what must hold for the cut to exist), which only M1–M3 address. D also restates S2782's Thm 19.3 (L3036–L3054, itself a sketch). | "Derivation" overstates. It is a conditional **reduction** of history to the cut-and-evaluate operation, plus three new properties. | Reword: "no stored result, and no mechanism beyond H1–H3". Label D a conditional lemma, "definitional given the cut". Note its kinship with S2782 L3036 (one line, not confirmation). |
| T9 | major | defect | Hist uses θ(s) | §9.8.2, §9.8.4 claim D, M3 text | The three temporal queries of S2775 (§9.7.2) and §9.7.4 step 4 ("θ at t_x, at t, or now … the contract-time question") leave the θ index open, and F27 asks whether ρ is a policy or a parameter of the query. Hist fixes θ(s), so it settles the contract-time question in part. M3's complaint that an unversioned evaluator answers "what would we decide now about then" omits that this is a legitimate query (CurrentKnowledgeAbout), not an error. | The result "history is Hist = CE(…θ(s)…)" holds for one reading of "history". The other queries are three-index, not two. | Define Hist with an explicit θ-time argument u, with D's case u = s. Link to F27. |
| T10 | major | defect | §9.8.7 bullet 3: "If CE stays undefined, D fails, and history cannot be derived. It would then have to be a primitive of its own, defined by its queries." | §9.8.7 | A non sequitur. Without a defined CE, Hist, which is defined through "standing", is itself undefined. It does not become a primitive. "A primitive defined by its queries" is the evaluator on cuts under another name. D's premise "CE is a total function" also understates the condition: §9.7.7 result 2 lists missing covers and actor–holder link, a recursive σ, and a non-monotone υ with no least fixed point. The premise needed is "CE is **defined** (all §1.14.1 defects repaired) for the chosen θ", not merely total. | The conditionality is stated in direction but not exactly. | Restate the premise as "CE defined and deterministic for the chosen θ on every cut". Delete the primitive-by-queries sentence or justify it. |
| T11 | major | defect (process) | Selection: "the earliest and the latest relevant hit" of 66 | §9.8 header, STEP8-AND-SCAN | Two new sources of 66 hits (about 3 per cent), chosen by time position. Time extremes do not track the property that matters (does the source argue derivability or test sufficiency?). S1609 "state vs history sufficiency test" is the nearest to the independence question (a sufficiency test of state is M1-like) and was a hit but not selected; I have not read it and cannot say what it contains. The chosen sources assume H1 as a rule (S0095, S0129) or are one AI-series line (S2775 and S2782, same date). Result: the corpus supplies instances, none argues independence, and the lineage is two lines. The rule was fixed before reading, but "relevant" is a content judgment made after seeing hits. Also S0078 (08-17) is earlier than S0095 and was "already read", so "earliest" is not the earliest. | Too narrow for a topic with this many hits. It risks reading a gap into the corpus ("a search miss is not absence"). The book makes no corpus-wide claim, which is right, but no statement says the evidence base is two lines. | Read S1609 (and a sample of the other hits not chosen by time position) before finalising. State "two lines of support; instances, not arguments" in §9.8. |
| T12 | minor | omission | Contract-relativity and more time axes | S2782 L837–L849, L703–L742, L795–L821, L1981–L2003 | S2782 makes history required only "if the previous state is required" (contract-relative), lists seven time dimensions (two are not a complete ontology), and distinguishes five senses of immutability. §9.8 has no EC parameter, two orders only, and no immutability senses. | H1's "no entry is removed" is unconditional. S2782 and the retention argument of §1.16 (retention bounded by reconstructability) say conditional. | State H1 relative to a retention contract, or say why it is kernel-level and unconditional. |
| T13 | minor | defect | M2 "H1, H3 hold" while H2 fails | §9.8.3, §9.8.5 M2 | H1 and H3 are stated over "every s", a time. Without τ they must be restated over positions. The independence is between differently typed properties. | A reader can reject M2 on a technicality. | Restate H1 and H3 over positions in M2. |
| T14 | minor | defect | Lineage and strength | §9.8 header; STEP8-AND-SCAN | "Strong words are scoped" is partly true, but "none follows from the others" (plain language) is unconditional and is the T1–T3 claim. Quotes check; the quote count "7/7" is not demonstrated here ("reported, not shown"). | — | Scope the plain-language sentence. |

## 3. Answers to the commission's specific questions

**Is derivation D sound, and is its conditionality stated right?**
- D is valid as a conditional reduction, and nearly definitional (T8).
- Conditionality is partly right. It names CE as the open premise but states it as "total", which understates it (T10).
- It does not name two further premises:
  - that τ makes R≤s a prefix, across records (T5);
  - that θ and the CE definition at s are determined by the cut, or pinned (T2, T9).
- It does not state that Hist is record-relative (T4).

**Do M1–M3 keep all six other candidates while breaking one of H1–H3? Is §9.8.7's §1.2 boundary case handled correctly?**
- Not shown. The six are not formally defined (STEP8 admits "their definitions are not all precise"), so "keeps" is asserted by one sentence per model.
- M1:
  - It breaks authority as §1.2 defines it (T1).
  - It is internally unclear about I-7 (T3).
  - Lifecycle is claimed to hold on the grounds that it is "stated over the current state", which is exactly the loss of the transitions.
- M3 plausibly breaks A1/policy for the θ part of H3 (T2).
- M2 is the best case.
- The §1.2 boundary is stated but mishandled. It is presented as a hypothetical ("if authority is defined to include checkability"), whereas the book defines it so. And the conclusion drawn is not supported (T1).

**My M4, M6 and M7: missing or covered?**
- M4, silent truncation with reflexive acts: **partly covered**. H1 excludes silent loss, and §9.8.6 item 4 handles a recorded loss. But the reflexive requirement (changes to the record are entries) is not a property, and the distinction "append-only form ≠ truthful" (S2782 L875–L891) is not drawn.
- M6, authority attestation of entries: **missing** (T4).
- M7, the must-do and the trivial model: **missing**. The empty record satisfies H1–H3 (T4).

**Is "the record" as a missing primitive (CON-0149) supported, or a Hyp stated as more?**
- It is tagged Hyp in §9.8.6 item 2 and in the register, which is correct. The section's own method is not applied to it, and the plain-language section states it as fact (T7). So: a Hyp, partly stated as more.

**Is the selection rule too narrow, given S1609?**
- Yes (T11). Its relevance to the question cannot be judged without reading it, which I did not.

## 4. Concept verdicts

| Concept | Verdict | Reason · scope |
|---|---|---|
| D (conditional reduction: Hist = CE(c,t;θ(s),R≤s) given H1–H3 and a defined CE) | **VALIDATED** as a conditional lemma, definitional | Steps 1–4 are justified. Scope: record-relative Hist, θ index u = s (T4, T8, T9, T10). |
| CON-0148 as stated ("history is derived; H1–H3 mutually independent and independent of the six") | **PROVISIONAL** | The derivation is conditional (T10). Independence among H1–H3: H1⊥H2 supported; H3 is partly derivable (T2). Independence of the six: undecided for authority (T1) and policy (T2). Not INVALID: the claim "history is not a primitive" is still plausible, and at worst becomes "history is part of authority and policy plus the record". |
| "History is overloaded, not new" (model-integrity check) | **PROVISIONAL** | Plausible. The count of primitives is not shown to go down (T7). |
| H1 | **PROVISIONAL** | Independent of A1-as-fact only. Unconditional wording conflicts with S2782 L837–L849 (T1, T12). |
| H2 | **PROVISIONAL** (strongest of the three) | Supported by S0095 L218–L221. Cut-as-prefix and clocks missing (T5). |
| H3 | **PROVISIONAL** | Split into θ (derivable if recorded) and the CE definition (independent) (T2). |
| CON-0149 (the record) | **PROVISIONAL**, Hyp | No minimality test (T7). |
| §9.8.8 result 4 (the estate keeps H1 at git level) | **INVALID as stated** | Contradicted for the authority record by S0129 L187 (T6). Restated, scoped: PROVISIONAL. |

## 5. Coverage of my step-1 items

| Step-1 item | Status |
|---|---|
| 1. Current vs history, reconstructible, conditional | COVERED, but conditionality (S2782 L837–L849) DISTORTED (T12) |
| 2. Append-only and replay; append-only ≠ correct | COVERED (H1, D); "≠ correct" MISSING (T4) |
| 3. Replay depends on rule and model versions | COVERED (H3); the θ-time index MISSING (T9) |
| 4. Retraction ≠ deletion, soft delete | MISSING (justifiable: not about the primitive; but §1.16 retention is related) |
| 5. Several time axes, VT and TT | COVERED in part (two orders); seven dimensions MISSING (T12) |
| 6. Ordering is not wall-clock | MISSING (T5) |
| 7. Truthful history, record-level; History ≠ Reconstruction Guess | COVERED in §9.8.6 item 4; truth notion not in the definition (T4) |
| 7. de998173, authority record outside git | MISSING, and result 4 contradicts it (T6) |
| 8. Transcription later than the artifact | COVERED (M2) |
| 9. Restoration as an act; successive acts | COVERED in part (item 4); "successive acts, not a blended text" [S0095 L166] MISSING |
| 10. Retained error and erratum | MISSING (instances; four on one work item) |
| 11. History carries authority; position vs decision | MISSING (T4) |
| 12. Event vs transition identity; five immutabilities | MISSING (minor) |
| 13. Corruption and repair are part of history | MISSING (covered by reflexive requirement, T4) |
| 14. Provenance ≠ audit; typed supersession | COVERED in part (I-7 in M1, T3) |
| 15. Re-evaluation: reproducibility + revisability | COVERED (D) |

**New topics or forks**
- Time and order as a candidate primitive (T7).
- Completeness and attestation of entries (T4).
- The θ-time index of Hist, linked to F27 (T9).
- The cut across several records (T5).
- Contract-relative retention of history (T12).

**Source defects.** None found that the theory relies on. S2782's H∪{e} (a set, no order) against Replay(E1…Et) (a sequence) is an inconsistency inside S2782. The book's H1 repairs it by moving to a prefix.

## 6. Changed theory (C15 settlement, step 9): each change against its finding

| C15 finding | Change | Check | New defect |
|---|---|---|---|
| 1 (critical): order a uniqueness only where act times are absent, tied or untrusted; F26(c) | §9.7 step 2, result 3, plain language; §9.6.6a items 2–4; map F26 | Matches §1.14.1 L159–L161 (act time first, position on ties). The derivation is sound. | (i) **THEORY-MAP L212 still reads "recording by set union … holds only under F26(b)"** while §9.6.6a item 3 now says (b) and (c). Minor, contradictory. (ii) F26(c) "present, distinct and trusted" needs one common time scale across records (S2782 L2124); "trusted" hides that. (iii) A deterministic tie rule gives **agreement** (K5), not truth about which of two same-moment acts came first, which §1.14.1 treats as a consumption race. Minor. |
| 2: withdraw "kernel-defined function" | §9.6.6a item 2 struck; CON-0138 meaning | Done as asked. | §9.6.6a Analysis 2 **step 6** (L127) still reads "delegable only as a kernel-defined function … the stronger kernel candidate", and the options table L182 says "kernel-defined contract", both with only an appended caveat. Minor. |
| 3: §1.10 premise or "common, versioned value" | §9.7 step 4, result 4, condition 1, CON-0142 | Done. The restated form is sound. | **THEORY-MAP L213 still reads "one authoritative, versioned home"**. The settlement says it grepped for this phrase, but the grep missed the map. Minor. |
| 4, 12: CON-0144 deliverable ∧ (lifecycle ∨ defect stated); "recognized" | §5.7 C1, §5.7.1, CON-0144 | Matches S0079 L57 and L73. "recognized" fixed. | The restated rule says a lane with no output "may not be closed". S0079 L57 refuses **COMPLETE**, and the same line offers `CANCEL`; S0080 L47 recommends "SUPERSEDED/CANCEL". The rule needs "closed as COMPLETE". Minor. |
| 5, 6: CON-0146 restated (a)–(c); S0084 a specification | §6.7 C7 | The mathematics checks by hand: a singleton fibre is the exact confirm condition. The edgeless graph on a fixed node set is the only graph with as many components as nodes. LCOM4 = 1 gives connected. S0079 L32 confirms "handed off having deliberately implemented nothing". | None. Variant of LCOM4 is Ext, labelled. |
| 7, 11: boundary disagreement; S0084 adapter/core | §6.7 C2 | Matches S0083 L12–L14, S0086 L15, L17 (estate inside, "Agent runtime"), S0085 L19–L20. | None. |
| 8, 13–15, 17: C4, C5, C6, C3, C7 wording | §6.7 | Consistent with the sources at the cited lines. | None found. |
| 9, 10: new C8, C9 | §6.7 | Quotes agree with the reviewer's findings. | None found. |
| 16: REALIZED anchors | §1.13.3-2, header | S0014 L68 and S0015 L58 both contain "REALIZED" (verified). The Int label and header amendment are present. | **THEORY-MAP, the C14 block ("§1.13 rewritten in form … anchor corrected to S0015 L58") is stale.** Minor. |
| 18: scope of "agree only if" | §9.7.1 | Done. | None. |

**Overall.** No critical defect in the changed theory. The settlement's own self-check ("grep for 'kernel-defined', 'authoritative, versioned home', 'uniqueness'") covered the chapters but not THEORY-MAP, which still carries three of the withdrawn formulations.

## 7. Next steps (dependency order)

1. **Fix THEORY-MAP L212, L213 and the C14 REALIZED line; restate CON-0144 scope (COMPLETE); clean §9.6.6a step 6 and L182.** Done when a grep for the three withdrawn phrases returns only "what does not hold" text.
2. **Restate §9.8.8 results 2 and 4 and the plain-language section** (T1, T6): conditional on the split of authority; result 4 scoped, with S0129 L187 cited.
3. **Rebuild M1 and M3** (T2, T3): explicit structures for M1 (claims, acts, links, times) and for H3a and H3b. Done when each model states which of the six primitives it keeps and why, in one line each, and answers "I-7 + time instead of H1?".
4. **Add H4 or an explicit scope statement** (T4), with M4, M6, M7 as models. Done when the empty record and an unattested record are classified.
5. **Fix H1–H3 formulation** (T5, T9, T13): τ monotone, a cross-record cut rule, an explicit θ-time argument u, and positions in M2.
6. **Apply the independence test to the record, and to time/order** (T7). Done when CON-0149 either has a model in which "the record" alone fails or is kept as a naming proposal.
7. **Read S1609** and a sample of the unselected hits (T11). Done when §9.8 states its evidence base as lines of support and lists what the extra reading changed.
8. **Principal's question, only if it blocks.** Is verifiability (valid vs verifiable, §1.12) part of "authority" in the kernel's list, or a separate property? T1 cannot close without it.

## 8. Limits
- Not read: S1609 and the other 63 hits; S2775 and S0078 (used by the Professor, not opened by me); S0128 (the "2 of 210" and "seq per record" figures are taken from §9.7); §1.14.1 beyond L150–L165 and L218; §3.8 beyond the index line and L51; §9.5; §2.11. No script was run.
- Not decided: whether the six other primitives would survive M1–M3 under a formal definition (none exists); whether the authority record is still outside git; what S1609 argues.
- The reading in T3 (that M1's superseded claims keep their recording times) is mine; the text does not say.
- Quote checks: about 12 quotes in §9.8 and 8 in the changed theory spot-checked and matching; the Professor's "7/7" count is not independently reproduced here ("reported, not shown").

## Appendix (lower priority)
- §9.8.4 step 2 uses "ties broken as F26 decides" and then says H2 makes option (c) available. Option (c) also needs act times to be **trusted**, which H2 does not give (the book says so in §9.8.9, third question); say so in D.
- §9.8.6 item 5 puts conflict persistence (F24) outside H1–H3 as "a property of the state space". Plausible, but S2782 L1083–L1123 argues it as a persistence requirement; the claim is Int.
- The §9.8.7 refutation recipe ("a model where H1, H2 or H3 fails and another primitive fails with it") is exactly T1 and T2, so the section hands the reviewer its own counterexample conditions.
- The references table cites S0129 only at L102; add L187 and L89 (T6).
