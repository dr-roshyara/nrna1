# Strategic-design turn 5 (published language): settlement

*2026-10-29. Reviewers A (Opus) and B (Sonnet), commission `BRIEF.md`. Reports saved verbatim with sha256: `REPORT-A.md` (`20402404…f245f`), `REPORT-B.md` (`e68685f1…1cbf`; full hashes in the SHA256 files). Draft `b76d88abc`. With the Lead DDD Architect's DDD findings, routed by the Principal (decision record addendum 2026-10-29T10:12:39Z, `1701baad5`; reports `review-architecture/SD1–SD4-DDD-REVIEW.md`, `514068d6d`), weighed below as input, not as review verdicts.*

"V (Professor)" marks evidence the Professor re-checked. "R" marks evidence accepted on a reviewer's reading.

## Reviewer findings and dispositions

| Finding(s) | Sev. | Check | Disposition |
|---|---|---|---|
| A-01, B F2 | high | **V (Professor):** §9.10 L53–64; §9.13 L255 | Accepted. Q3 restated as the book's (a, v, x, t, s) about a hypothetical act; found / not found / U; "not found is not unauthorized"; Int, not modelled (PL-8). authorized(x), §9.15 G2, listed (PL-7) |
| A-02, B F3 | high | **V (Professor):** §9.10 L41–50; §9.15 L63 | Accepted. Each question its own answer and promise; G1 scoped to Q1 on D, Θ_f; Q2 returns entries, narrower on D (PL-6–PL-9) |
| B F4 | high | **V (Professor):** §9.13 R2, R2a | Accepted. T, F (refuted **or not recognized**), U and refusal defined (PL-6) |
| A-03 | high | **V (Professor):** §9.13 L166, L338 | Accepted. Reasons for T and F moved to the non-promises |
| A-04, B F14 | high / normal | **V (Professor):** §9.13 L37–48, L62–63 | Accepted. "Only if … and passes every guard of its kind"; the guard list completed; L62 wording |
| A-05, B F5 (a, b), F11 | high | **V (Professor):** §9.13 L46; §9.16 R4c | Accepted. Owner check scoped to single-use grants; single-use defined; cross-reference and in/out mapping fixed (PL-5 moved "in") |
| A-06, B F5 (c), F6 | high | **V (Professor):** §9.15 G4; §9.16 L183, L228; §9.13 L46; §9.17 L166; Ch. 9 TODO T9-11 | Accepted. Second **recorded** use prevented; actual use only under L9; merged second use marked, not corrected; extended cost; who may transfer; store-owner change not modelled (T9-11); T9-07 |
| A-07, B F16 | high / normal | **V (Professor):** §10.3 L29; §9.13 defect 1 | Accepted. Display rule grounded on §10.3.3 C and promise (b); fail-closed use by a consumer allowed while U is shown as U |
| A-08, B F13 | high / normal | **V (Professor):** §9.13 L51, L60; §9.16 L265 | Accepted. Seven marks; scope Q1 and act authorization |
| A-09, B F18 | high / normal | **V (Professor):** §10.3.6 | Accepted. (p2) and "by construction" in PL-1; R6 in PL-12 |
| B F1 | high | **V (Professor):** §9.15 R3, L94–96, L70 | Accepted. Conditions split three ways (values; guards; legitimacy) |
| B F7, A N-05 | high / normal | R (against §10.4.2, which the Professor wrote) | Accepted. §10.5.3 conditions and shared statuses aligned with §10.4 |
| B F8, A N-02 | high / normal | **V (Professor):** §9.13 L59–61; §9.15 K1 | Accepted. Tombstone keeps every required field, drops content; loss detectable also from citations or numbered successors |
| B F9 | high | **V (Professor):** §9.16 R2 L88–98 | Accepted. PL-4, identity and merge |
| B F10, A N-01 | normal | **V (Professor):** §9.16 L79–80; register CON-0143 | Accepted. PL-11: stipulation, one root, "now" limit; values unchanged after a tombstone |
| B F12 | normal | R | Accepted. Guard-refusal reasons not promised |
| B F15, A N-09, N-10 | normal | R | Accepted. Terms qualified (§10.5.0); G-labels qualified; §9.10 L34; u in Q1's inputs |
| B F17, A N-04 | normal | R | Accepted in part. Decided design terms kept with meaning stated (§10.5.0); attestation stated as presence-checked, the Professor's choice for review |
| B F19, A N-06 | normal | **V (Professor):** S0036 L173; S0039 L147; S1040 L104–116, L155–164 | Accepted. Prior-work rows corrected; S0039's test labelled Int |
| B F20 | normal | R | Accepted. Reliance UNKNOWN, not searched |
| B F21 | normal | R | Accepted. K3 tension stated in PL-12 |
| B F22 | normal | R | Accepted. Q5, Q6, §9.15 G5, O3 listed as unplaced / not published |
| B F23, A N-07 | normal | R | Accepted. Stability level S0 kept apart from register status "at most PROVISIONAL"; CON-0194, CON-0264 added |
| A N-03, N-08 | normal | R | Accepted. Fields summarized with a pointer to §9.13.2; non-promises completed |
| A N-11 | normal | — | Accepted. T10-03 stays open; no line rests on outside literature |
| (gap G8, §10.2.3) | — | the Professor, at settlement | Not raised by the reviewers: §10.2.3 assigned G8 (accepting evidence with refusal reasons) to this turn. Stated in §10.5.3 as not statable before gap G1; carried to the G1 turn |

**R1** (`register_home_check.py`): 1 flag, pre-existing (CON-0133). **R2** (`--scope 10.04,10.05`, after settlement): 1 tier A, "appender", judged: defined in §10.5.0 ("Words used here"; the tool counts the lower-case use first); the reviewers' tier-D judgements applied. **Quote check:** 0 in the tool's format; both reviewers checked every corpus anchor by hand. **R3:** no inbound citation of a renumbered item (§10.5.5 kept, T10-15 still points to it).

**Limits recorded by the reviewers:** neither could identify R1 from the files allowed; neither ran `ce_model.py`; one command of A's was refused by the worktree guard and re-run as plain `sed`. *Recorded.*

## The Lead DDD Architect's findings, routed by the Principal (six points each)

*Input, not review verdicts. Points: (1) what the theory requires; (2) what the settled design says; (3) what the Architect thinks is missing; (4) what it rests on: the theory, the corpus, or only a DDD convention; (5) alternatives and costs; (6) whether this turn resolves it. The Architect's wording is a hypothesis. No DDD convention becomes a claim of the book.*

### A1. One published answer carrying both values (SD1-02, SD3-03, SD4-04)

1. **Theory:** promise (b) (§10.1.3): standing "shown with its reasons, together with an honest display of how well-founded it is"; §0.1 (usable = current ∧ well-founded ∧ visible); I-2, I-4. §10.3.3 row C: "show both values, with reasons, unaltered" is **shared by 1 and 2**, with **3 enforcing for another**. The enforcer is defined as "the candidate whose **operation** prevents a violation, by a step or a guard" (§10.3.2).
2. **Settled design:** delivery is a role (§10.3.6, §10.4.0); R3 and R4 are display rules on consumers; §10.5 publishes standing's answer (PL-4) and no answer of W yet (◇ G1).
3. **The Architect:** no accountable party performs the display duty; one answer that carries both values and their reasons would attach "unaltered" to a published answer.
4. **Rests on:** **the theory, for the gap.** By §10.3.2's own definition, no context's operation now prevents a violation of row C, so the core duty has **no enforcer**. That follows from settled text. **The remedy is a DDD convention** (a joint published answer), and it is a hypothesis here.
   - **A limit from the theory:** "unaltered" is a property of what a consumer **shows**, after delivery. No producer's step can prevent a consumer from altering or dropping half of an answer. A joint answer makes **omission at delivery** impossible: one cannot be received without the other. It does not make **alteration at display** preventable. The display half stays, in the book's terms, an **assumption about consumers**, of the same type as the L assumptions (a world fact).
   - **A limit from Ch. 9:** Q1 and Q4 are separate questions with different identities: Q1 is (k, x, t, s, u) and Q4 is (k, s, g) (§9.10). Q4 has no scope x and no valid time t. Under F37 (B), Q4 is per claim, and Q1 is per item. **A joint answer needs a stated join** (same k, same cut s; which x and t for the class), and that join does not exist in the book.
5. **Alternatives:**
   - **(a) A joint answer** (the Architect's). *Cost:* someone owns the join. If S owns it, S reads W, contradicting R5 ("S never reads W"). If W owns it, W reads S. If a third party owns it, that party is a new area. It also needs the join of (4).
   - **(b) Two answers with a published pairing:** each answer names k and the cut s, and the display rule requires both at the same cut. *Cost:* the enforcer stays the consumer; omission becomes detectable, not prevented.
   - **(c) State the gap:** "unaltered" is enforced by no context's operation, and is an assumption on consumers, named like L1–L11. *Cost:* the core display has no guarantee, only a stated assumption. Promise (b) already says "no guarantee" of well-foundedness, but it does not say that for the display.
6. **This turn:**
   - The gap and the limit are stated (§10.5 PL-14, conditional).
   - The joint answer is **conditional on G1** (W has no answer yet) and on the join (G3/F37), so **it cannot be decided now**.
   - Recommended for the G1 turn: (b), with (c) stated now. Whether the core display needs more than an assumption is **a normative question for the Principal after G1**, not now.

### A2. The record and its kind list as an explicit shared element (SD2-02, SD4-01, recommendation 3)

1. **Theory:** K2, one designated record (CON-0261); D's kind list (§9.13.2); T10-10 (1): an area's own state is the kinds whose acceptance rule is its own, so **adding a kind is what can admit a context**; (p2): the assignment of kinds is stipulated, outside D; (p5): a new kind receives one owner.
2. **Settled design:**
   - §10.3.6 says it in substance ("true by construction"; other areas' kinds "granted by standing's schema").
   - The map has **no row** for it.
   - §10.5 PL-1 lists the kinds as "all ten standing's".
3. **The Architect:** one element, the record and its schema, joins two models (standing's semantics and record-keeping). Name it, with its owner and the act that adds a kind.
4. **Rests on:**
   - **The theory**, for the missing act. The book's own history shows how kinds were added: consumption (v8), transfer (v10) and designation (v19) were each admitted to D **by the Principal's decision** (§9.13.2 L34, L39, L48). So in this estate, adding a kind has been an act of the Principal. That is evidence about the act, not yet a rule.
   - **The corpus**, for naming it: S0069 R-2 calls "the record schema" the **published language at the writing seam**.
   - **The book**, for a shared part with no owner: §8.09 C7: "the shared boundary is not owned by either track … a change to it is a third act by a third authority" [S0087 L22], a stated norm, one source.
   - **Only a DDD convention:** the label "shared kernel", and the inference "two change drivers, therefore two models" (SD3-02 cites the litmus "by analogy").
5. **Alternatives:**
   - **(a) Standing owns the record and its kind list** (the status quo). *Cost:* every area's admission is decided by standing's schema. Standing bundles its semantics with record-keeping (laws 9a, 29, 30, the designation rule, the carrier-change gate, G6).
   - **(b) A shared element owned by no context**, changed only by an act of the Principal (or another designated authority), as in §8.09 C7. Adding a kind is that act. *Cost:* a new standing holder of the amending act, outside every context. Standing's admission is no longer "by construction". The record-integrity rules of row A move to the shared element, and §10.3.3 row A is re-cut.
   - **(c) The record as its own context** (row 14's carriers plus the record-integrity rules). Under (B) it might qualify: it owns rules no other area owns. *Cost:* a second admitted context, and a change of ownership and of the context map.
6. **This turn:**
   - **Can name it.** Added as **R0** in the map and **PL-0** in the published language: the one record and its kind list. Every kind lists its owner. A kind is added only by an authorised act, and in this estate that has been the Principal's decision (evidence, not yet a rule).
   - **Cannot settle its owner.** Choosing between (a), (b) and (c) changes ownership and possibly admission. That is a **normative choice for the Principal**, prepared as one question (T10-16).
   - **Recommendation: (b).** It matches how kinds were actually added. It keeps standing's semantics apart from the shared medium. It uses a pattern the book already records (§8.09 C7).

### A3. R18 as an anti-corruption layer owned by the receiving side (SD4-06)

1. **Theory:** §1.5 and S0036 L93: KnowledgeOS owns no product's concepts; §10.1 stipulation 1; rule (B) admits on a context's **own** vocabulary, so product vocabulary leaking into the catalogue or norms would corrupt the admission test. S0162 asks for translation boundaries.
2. **Settled design:** R18's translator is not named; open (T10-14).
3. **The Architect:** an ACL, owned by the receiving side.
4. **Rests on:**
   - **The corpus, in part.** S0036 L169: C-07 → C-19 "vocabulary cannot leak upward", "ACL — the strongest candidate"; S0039 PR-4: "an ACL already exists in function: Capability Mapping", "⛔ Not assigned — one runtime, so it has never been tested against a second".
   - **The book** records that the function has no artifact: "no Capability Mapping artifact exists" [S0034 L28] (§1.5).
   - **"Owned by the receiving side"** is a DDD convention.
5. **Alternatives:**
   - **(a) An ACL owned by the receiving side.** *Cost:* the receiving sides, catalogue and norms, are **not contexts** under T10-10 (1). An ACL owned by a non-context has no owner of record.
   - **(b) Translation as an accepted entry:** product facts enter the record only as entries of kinds whose acceptance rule is the translation. The acceptance rule is the boundary, in domain terms. *Cost:* the kinds needed (registration, link: O1) are **not admitted** to D (§9.13 L52), and their owner would be decided by A2.
   - **(c) Leave it open,** with the corpus evidence recorded.
6. **This turn:**
   - It cannot be resolved: the receiving side is not a context (G10, G11), and the kinds that (b) needs are not admitted (O1).
   - Recorded in §10.4.5 and §10.5 with (a) and (b) and their costs. T10-14 stays open.
   - (b) is noted as the option the book's own mechanism supports.

### B4. Containment of a breach of one area's acceptance rule (SD4-02) — for the G1 turn

1. **Theory:**
   - (d3) holds every kind to its owner's acceptance rule.
   - Membership of D is a property of the whole record, and Q1 refuses outside D (§9.13.2). The book's reason is that refusing is fail-closed and dropping is not, because dropping a failing annulment, revocation or ending turns the acts it targets to T (model check 13).
   - O2: "An evidence entry is not an act in A1's sense: it changes no authority."
2. **Settled design:** R6: a breach of W's rule takes the record out of D, so every S answer is refused.
3. **The Architect:** contain the breach to the area's entries.
4. **Rests on the theory**, and the theory points both ways.
   - **For containment:** the fail-closed argument applies to kinds that **CE* reads**. If Φ reads no evidence kind (O2), then excluding a W entry cannot change any CE* value, and refusing S's answers protects nothing of S's.
   - **Against containment within W:** the same argument holds **inside** W. Dropping a refutation that breaks W's rule could turn a class from refuted to supported, so W's own answers should be refused, not computed on the rest.
5. **Alternatives:**
   - (a) per-record refusal (the status quo; a supporting area's breach disables the core answer);
   - (b) **per-area domains:** D_S over S's kinds, D_W over W's. A breach refuses only that area's answers. *Cost:* D is no longer one test. It needs the invariant "Φ reads no kind of another area" and a model check. K2-r is untouched (one record, cuts named by entries);
   - (c) exclude the failing entry (not fail-closed for W; rejected by the book's own reason).
6. **The G1 turn** examines (b) with a model check. If (b) fails, per-record refusal is recorded as a **stated cost of decision (1)**. The Governance Engineer's qualification is kept: within one kernel such an entry is refused at append, and the case arises on merged records or bypass.

### C. For the Ch. 10 TODO (a revision after the G1 turn, before tactical design; not worked now)

| Finding | (1) theory | (2) settled | (3) missing | (4) rests on | (5) alternatives, cost | (6) |
|---|---|---|---|---|---|---|
| SD3-01 | R-a (the amending act, §6.5.2) is the book's owner reading; R-b (terms) is a procedure | the table uses R-b; agreement with R-a "untested" (§10.3.2) | a test against "who has the information to reject" and R-a | the theory (R-a and the untested agreement); "information to reject" is the rival single-ownership test the book already records (§1.17.2) | test each row under R-a and the rival, record disagreements; cost: a re-run of §10.3.3 | open, T10-17 |
| SD2-01 | (B): a rule "in its own vocabulary" | the grid scores "Language" without per-candidate homonym evidence except "scope" | per-candidate glossary of terms defined and homonyms | the theory (the decision's wording makes the column decisive); the glossary form is a DDD convention | a glossary per candidate; cost: one pass over §10.2's lists | open, T10-18 |
| SD3-04 | the inventory's selection rule (§10.3.0) | 6, 7 and 8 found "not contexts" | the applications' own rules are not inventoried | the theory: a result relative to a list that cannot contain them | qualify the result "relative to the §10.3.0 inventory", or inventory their rules; cost: a new inventory | open, T10-19; the qualification can be stated now |
| SD1-01 | promise (b): an **honest** display of the class | §10.1: "core: showing; supporting: the assessment methods"; G2: the derivation rule undecided | the class of the derivation rule | the theory: the rule is neither showing nor a method, so §10.1 leaves it unclassified | core (with showing) or supporting; cost: core makes G2 a core gap | open, T10-20; the G1 turn will state the rule, and classify it after |

**Applied in the book:** A1 → §10.5 PL-14 (the gap and the limit stated; the joint answer conditional); A2 → §10.5 PL-0 and §10.4 R0 (named; owner open, T10-16); A3 → §10.4.5 and §10.5.5 (options stated; T10-14 open); B4 → PL-12 and the G1 turn; C → Ch. 10 TODO T10-17 to T10-20.

## Levels

Every line of §10.5, and of the §10.4 additions, stays **S0**.

## For the Principal

One question (T10-16; §10.5 PL-0, §10.4 R0): who owns the record and its list of entry kinds, and so decides which act adds a kind (and can admit a context)? Recommended: (b), no context; changed only by an authorised act outside every context.

## Measures

| Measure | Value |
|---|---|
| Reviewer A (Opus), harness | 530.5 s |
| Reviewer B (Sonnet), harness | 528.8 s |
| Review total | 1,059.3 s (17 min 39 s) |
| Turn elapsed | from 10:02:50Z (start of the corpus consults) to this settlement's commit (see the session log) |
| Findings | A: 9 high + 11 normal (0 critical); B: 9 high + 14 normal (0 critical); all accepted (B F17 in part); one item added by the Professor (gap G8) |
| Routed DDD findings | A1–A3, B4 and C (four) weighed in six points; one question (T10-16); four TODO items |
