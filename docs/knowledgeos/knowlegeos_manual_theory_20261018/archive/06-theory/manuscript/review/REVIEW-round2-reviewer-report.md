# Reviewer's report — round-2 verification (unedited)

*Saved unedited from the read-only reviewer's hand-back of 2026-10-18. Reviewer: a fresh session of the same model family as the author; not external expert validation. Dispositions are in `REVIEW-round2-verification.md`. The author's verification found one anchor slip: "lapsed, was revoked, or was superseded" is V3 L319, not L317.*

---

## Review of the round-2 corrections (Ch. 1 §1.14.1, §1.14.5, §1.17.2 and related passages)

**Recommendation: CORRECT.** Area A is defective in an ordinary, repairable way. Area B has a confirmed internal contradiction. Area C has a minor issue that still needs fixing: an unsettled choice is written as settled. The round-2 corrections are not yet eligible for the next approval step.

### 1. Scope and access actually observed
I read only the files I was allowed to read, and created, changed or deleted nothing.
- **Chapter 1:** §1.6, §1.13–§1.14.5, §1.16–§1.17 and the change log.
- **Chapter 0:** the whole file.
- **Chapter 2:** §2.8.2, §2.9–§2.11, the change log, and a search for the relevant terms.
- **Glossary and the review record:** both in full.
- **`IDEA-REGISTER.tsv`:** only rows starting `B3-`, and only those matching my search.
- **Background sources:** the relevant line ranges of `…0841` (V3 L276–325, L465–471), `…0905` (CF L14–82), `…0922` (AB L85–115, L210–222), `…1029` (IA L42–60) and `…1332` (AMS L84–100, L196–230, L276–292).
- **Not opened:** `…1023` (IM) was in scope but I did not need it. Nothing outside scope was opened.

### 2. Passages examined
**Chapter 1** (`CH-01-knowledge-authority-execution.md`):
- **Area A:** L194–198, L461, L464, L516–538, L763–787.
- **Area B:** L621–646.
- **Area C:** L889–913.
- **Cross-references:** L968, L987–994.

**Other manuscript files:**
- **Chapter 0:** L152, L170–171, L193–195.
- **Chapter 2:** L338, L596, L625, L668, L672–674.
- **Glossary:** L12, L19, L22, L34, L37, L43–44.
- **Review record:** L14–49.

**Register rows:** B3-F2-01 (L107), B3-F3-07 (L129), B3-F5-02 (L161).

### 3. Verdicts

#### A. Authority formula and ending policy E — **DEFECTIVE (ordinary, repairable; the core two-time correction is sound)**

**Claim reviewed** (Ch. 1 L522–526):
- validly-made(c) ⇔ an act *a* adopted *c* at *t*ₐ, and a grant *g* covering *a*'s actor and *c*'s scope was valid at *t*ₐ.
- currently-authoritative(c, t) ⇔ validly-made(c) ∧ t ∈ validity(c) ∧ not revoked or superseded by t ∧ **E**(*g*, t).

**Premises:**
- the authority axiom (act × scope × time, §1.6 item 2);
- the normative premise at L518, labelled Int: products survive the end of their grant;
- AB L100 for exceptions;
- E treated as an open choice.

**What is established:**
- **(i) Times are separated.** The time of the act (*t*ₐ), the grant's act window and the time of use (*t*) are explicit and used consistently in §1.16.2 (L777–785).
- **(ii) Decisions no longer expire with their grant.** Grant expiry and consumption do not end *c* under either policy, so the CONSUMED counterexample is handled.
- **(iii) Revocation is stated as open.** E is declared an open choice (L526, L532; glossary L34). The "office-holder's decisions lapse" failure is gone.
- **(iv) The exception bound is scoped correctly.** The link between act window and binding period is cited as a source fact for exceptions only (L531).

**Defects found:**
1. **Unbound variables.** *g* and *a* are introduced inside validly-made (existential) but are used free in E(*g*, t). Counterexample: actor X holds two covering grants, g1 (project) and g2 (platform), and g1 is revoked. Under *ends-with-grant*:
   - if *g* is read existentially, *c* survives through g2;
   - if *g* is read as "the grant the act cited", *c* ends although X still held authority.

   The formula gives no answer. Joint "PO/ARB" grants (L463) make the same ambiguity structural.
2. **E settles other endings silently.** E is defined only for *revocation* (L526). Under both policies, suspension and supersession of *g* therefore never end *c*. AM-1 says "the end is not one event" [AMS L284], yet these cases are decided without saying so. This is a smaller repeat of defect 1b from the previous review. The name *ends-with-grant* also suggests every kind of ending, not just revocation.
3. **Serious counterexample: void-from-the-start annulment.**
   - *Setup:* grant g (act window Jan–Jun, scope "module M") was registered in error; the human act it points to covered only module N. On Mar 1, X adopts *c*. On Apr 1, governance records "g revoked, issued in error".
   - *What the formula yields:* g "was valid at *t*ₐ" if the record is read as a revocation from Apr 1. Under *survives*, *c* stays authoritative although no authority ever covered it. Under *ends-with-grant*, *c* is treated as authoritative Mar 1–Apr 1.
   - *Why that is not justified:* neither result is "never validly made", which AM-3's evidence principle points toward. The formula has no record-time index. It cannot say whether "valid at *t*ₐ" is judged against the record as it stood at *t*ₐ or as it stands now. §1.13.2 L449 notes that grant records are editable files outside version control.
4. **Static versus dynamic reading of AB L100 is unstated.** "Validity cannot exceed the approver's authority" can be read statically (the book's reading: binding period ⊆ act window) or dynamically (the exception ends when the approver's authority ends, including by revocation). The dynamic reading would settle E as *ends-with-grant* for exceptions. The book does not mention this.
5. **A relevant source sentence is not discussed.** V3 L317 says a rule may stop being currently authoritative "because its authority lapsed, was revoked, or was superseded". If "lapsed" refers to the grant, this conflicts with the L518 premise. It is not discussed.

**Minor issues:**
- L518 "unless an act revokes it" has an ambiguous referent: the work, or the grant?
- A delegated grant is itself the product of a completed act. The grant/claim distinction therefore holds only as a per-kind exception. That is coherent, but it should be said explicitly.
- validity(c) is undefined for decisions; the glossary at L12 defines it for rules, exceptions and grants only.
- "Two periods of a grant" assigns the products' binding period to the grant.
- The OQ-11 bullet (L536) cites I-9 ("quality is evidenced"). That stretches I-9.
- Computing current authority depends on rule-to-grant linkage, which does not exist today (AM-5; AMS L200–206).
- Register row B3-F3-07's verification column still states the withdrawn bound validity(c) ⊆ validity(g).

#### B. Premise P-E and the generalized enforcement conclusion — **DEFECTIVE (ordinary)**

**Claim reviewed:**
- L629, P-E: "No mechanism may produce the *effects* of an act unless a valid act authorizes those effects."
- L633: blocking(g, r) ⇒ a valid act authorizes the blocking — either by adopting *r* as mandatory, or by authorizing the gate's blocking directly.

**What is established:**
- P-E is labelled a premise. Its dependence on a Track-B ruling is stated (L631).
- The conclusion follows from P-E plus the axiom; it is almost a restatement of P-E.
- The text separates a mechanism that blocks from an act that authorizes a block.
- The conclusion does not assume every block comes from adopting the enforced rule.
- It is conditional on P-E. CF L36 states a similar rule, but only as a PROPOSED source.

**Confirmed defects:**
1. **The conditions contradict the generalization.** Condition 3 (L642) requires "*r* is mandatory, or the block is a confirmation request". A candidate rule has no binding strength, so the authorized emergency freeze accepted in round 2 fails condition 3 and is inadmissible. Glossary L22 repeats the same contradiction.
2. **L646 was not updated.** It still says advisory mode is "the correct mode for any rule whose standing is short of adopted". That contradicts L633.
3. **Stale cross-references:**
   - L625 still classifies the idea as "derivable from what the book already holds", but it now needs P-E.
   - Ch. 2 L625 says "blocking also needs current standing".
   - Ch. 1 L968 says the enforcement point is "bounded above by … standing".
   - Register row B3-F2-01 still says "derivable from axiom" and keeps the withdrawn "r effective; min()" form.

**New counterexamples:**
1. **Automatic escalation to blocking.** `design-check.sh` "becomes hard-blocking when violations drop below threshold" (Ch. 2 L608). The switch is set by configuration that "anyone who can edit" may change (Ch. 2 L633). Disjunct (a) treats adopting *r* as mandatory as authorization to block. But Ch. 2 §2.9 says binding strength and enforcement consequence are separate, and that hardness is "set by an act" (L596, L625). IA's OQ-24 says whether a conflict blocks is "a business decision nobody has made". So disjunct (a) quietly answers OQ-24 and merges two properties the book keeps apart. Either a separate act setting hardness is required, or the question must be marked open.
2. **An actor whose authority exists but is unrecorded (E4).** L644 says that for standing-changing acts, failing closed "follows from the axiom itself". But §1.17.3 (L922) allows authority that exists without a record. For such an actor, the axiom shows only that an act without a grant confers no standing. It does not license a mechanism to *prevent* the attempt. Failing closed rests on INV-A3 (Track B), or on an unstated premise that only recorded grants count. It does not rest on the axiom.

**Consequences of the two options:**
- **Adopting P-E:** every gate, freeze, quota or automatic escalation would need a traceable authorizing act. That includes the session resolver itself, which is currently backed only by Track-B INV-A3. The scope of P-E also widens with whatever "effects of an act" is taken to mean, and that term is undefined.
- **Keeping P-E conditional:** CF's rule stays PROPOSED, and §1.14.5's bound remains advice rather than theory.

#### C. Status of I-2's "reasoned" requirement — **SOUND WITH MINOR ISSUES (one consistency correction required)**

**Claim reviewed:** L903–907 says readings (a) and (b) are both coherent, and that the choice is "a reserved choice, not a result". The recommendation for (b) is marked Int.

**What is established:**
- Under the current axioms, an unreasoned act is *not* invalid. The refined axiom (§1.6 L196) and Ch. 0 L193 list only act, scope and time. It does breach the invariant I-2.
- The book never defines what breaching an invariant does to an act. Elsewhere it treats such breaches as defects, not as voiding the act (Ch. 2 L650: in-place edits "break I-1 and I-7"). That supports (b) without settling it.

**Issues:**
1. **The choice is stated as open but written as settled elsewhere.**
   - L895 ("**is valid** however poorly it was informed", labelled Ded) and L911 ("keeps its standing") assume (b).
   - Glossary L43–44 builds (b) into its definitions: valid acts "confer standing"; an ill-founded act "keeps its standing until revised".
2. **A competing reading is missing.** Call it (c): reasons are a validity condition for some kinds of act only. RM-3, a ruled Track-B source, requires "explicit authorization **and a recorded reason**" to change an authoritative rule (Ch. 2 L668).
3. **The argument for (b) is weak.** (b) is preferred because it keeps validity decidable from records. But (a), limited to the *presence* of recorded reasons, is just as decidable from records; compare §1.16.8's distinction between presence and validity. Only the *adequacy* of reasons is not decidable.
4. **Terminology drifts.** Ch. 2 L674 uses "verifiable … with approver, reason"; Chapter 1 and the glossary use "well-founded"; the register and review record use "quality obligation". I-2's "visible" part is assigned to neither validity nor well-foundedness. Ch. 0 L152 and L195 do not point to the reserved choice.

**Evidence needed to choose:** an organisational ruling on whether unreasoned acts are void; the per-kind treatment under RM-3; and the practical effect of reasons recorded after the act.

### 4. Findings
**Confirmed defects:**
- A1–A3 (formula variables, E covering revocation only, no record time).
- B1 (condition 3 contradicts the freeze), B2 (L646), B3 (stale cross-references).
- C1 (glossary and body state the reserved choice as settled).

**Disputed or interpretive points:**
- A4 (static versus dynamic reading of AB L100) and A5 (V3 L317 "lapsed").
- B disjunct (a): does adoption authorize blocking?
- Whether failing closed follows from the axiom.
- C: does the (b) decidability argument hold?

**Unresolved questions:**
- E for each kind of claim and each kind of ending.
- Annulment from the start versus revocation from now on.
- What "effects of an act" means in P-E.
- What happens when an invariant is breached.

### 5. Decisions reserved for the Principal or ARB
1. **Ending policy E**, for each kind of claim *and* each kind of grant ending (revocation, suspension, supersession, annulment). Options:
   - *survives*: products are stable, but authority obtained in error persists;
   - *ends-with-grant*: authority can be traced, but standing becomes fragile;
   - *per kind*: closest to the sources, but adds governance cost.
2. **Premise P-E:** adopt, adopt with "effects" narrowed to normative effects, or keep conditional. The consequences are as in area B.
3. **I-2 "reasoned":** (a) a validity condition, (b) a quality obligation, or (c) a validity condition for some kinds of act only, such as rules under RM-3. Each choice also determines what breaching any invariant means.
4. **Whether adopting a rule as mandatory authorizes blocking**, or whether hardness needs a separate act (OQ-24).

### 6. Recommended corrections (not applied)
**Corrections for actual defects:**
- **A:** bind *g* in the formula (for example, "the grant cited by *a*'s record"); widen E to cover each kind of ending, or state its limited coverage; add a record-time index or name annulment as an open gap; note AB's dynamic reading and V3 L317.
- **B:** reconcile condition 3 with the freeze, for example "or a valid act authorizes the block for that scope". Then update L646, L625 and L644 (failing closed depends on INV-A3 or a recorded-grant premise), Ch. 2 L625, Ch. 1 L968, glossary L22 and L37, and register row B3-F2-01.
- **C:** make L895, L911 and glossary L43–44 conditional on reading (b); add reading (c) under RM-3; add pointers from Ch. 0 L152 and L195; align terminology with Ch. 2 L674.

**Recommended checks** (good practice; not defects): register row B3-F3-07's verification column; the B3-F5-02 wording; new change-log rows in Ch. 1 and Ch. 2; a round-3 entry in the review record; and the dependent §1.16.2 L781.

### 7. Limitations
- **Not an external review.** I am a fresh session of the same model family as the author and the previous reviewer, so this is not external expert validation. Shared blind spots are possible.
- **Partial reading.** I read only parts of the sources and Chapter 2.
- **Unverified premises.** I did not check the original AB, V3 or AMS rulings beyond these files.
- **Limits of counterexamples.** Where my attempt to break a claim failed, that does not prove the claim.

### 8. Recommendation
**CORRECT.** Fix A1–A3, B1–B3 and C1, then run a targeted re-review of the formula, the enforcement conditions and the glossary before the next approval step. Decisions 1–4 stay reserved for the Principal or ARB.
