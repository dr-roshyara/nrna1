# §1.14.1 Bounded Repair Proposal (2026-10-18)

*Status: **PROPOSAL for Principal review.** It is not approved, not applied, and not a replacement baseline. The manuscript is unchanged. Everything here lives in the session scratchpad. Authority: the Principal's instructions "Bounded Repair Instructions for §1.14.1" and the follow-up of the same day (steps 1–7).*

**Labels used throughout:**
- **VERIFIED**: checked by me against the baseline text, a source or the code, or computed;
- **REPORTED**: a finding of the targeted review that I have not independently checked;
- **ASSUMPTION**: introduced by me or by the baseline author, with no source;
- **CANDIDATE**: a proposed correction;
- **DECISION**: needs Principal approval.

Status tags for definitions (§3): [SRC] [DER] [ASM] [UNR] [PROP], as defined in `M1-candidate-model.md`.

**The twelve deliverable items.** The instruction's list was cut off after item 2 in the record, and the Principal asked me not to request it again. The twelve items below are **my derivation from sections 1–8 of the instruction** (an ASSUMPTION about its intended content): 1 executive summary · 2 verified baseline and evidence · 3 model reconstruction · 4 headline claim · 5 defect analyses · 6 candidate model · 7 proofs vs tests · 8 test challenge · 9 independent review · 10 Principal decisions · 11 impact on dependent claims · 12 readiness.

---

## 1. Executive summary (business language)

§1.14.1 answers one business question: **does the organisation currently recognise this decision or rule, why, and on whose authority?** The design behind the answer is sound and survives this review:
- judge a decision by the authority **at the time it was made**, not at the time it is used;
- endings count only if made by someone entitled to make them;
- the organisation's policy choices are explicit parameters;
- the answer has three values and a headline with all its reasons.

**The mathematics in the baseline does not fully deliver it.** Five things are wrong:
1. Under one of the organisation's policy options ("an unverifiable cancellation does not count"), the model can **flip back and forth forever** instead of giving an answer. Its guarantee of a unique answer is therefore false for that option.
2. **Who issued a permission is never checked.** An improperly issued or improperly delegated permission works as if it were valid, and a delegated permission is not affected when the delegator's permission ends.
3. **An expiry date does not stop a permission**, because the text counts only endings made by acts.
4. The claim that **every question gets exactly one headline** is false in two cases ("no adoption on record"; "the adoption was annulled").
5. **Several kinds of time are mixed.** As a result, two actions at the same moment can deadlock, and "what did the records show on 5 March?" cannot be computed.

**The candidate repair (M1) fixes each of these with the smallest change I could find:**
- permissions become the product of a checked act;
- expiry enters the window;
- the policy "does not count" is applied **after** the model has settled;
- time is split into world time, record position and recording time, with defined comparisons;
- the headline is computed from the same evidence as the value.

Under M1 I can **prove** that an answer always exists, is unique and receives a headline. Under the "at the time" view I can prove there are **no deadlocks at all**.

**The repair does not decide policy.** Seven questions remain for the Principal (§10): among them whether a delegate loses authority when the delegator does; whether a permission is still valid at the exact instant it expires; and how to treat unrecorded authority. **One new problem was found:** under the "records as they stand now" view, recording an extra adoption can make a currently recognised decision unrecognised (§5.7).

**What this does not change.** No rule in the organisation today cites the grant it was adopted under, and grant records carry no holder, period or issuer link (§2.4). So, today, every claim evaluates to "cannot determine" under the current model **and** under M1. The repair makes the theory correct; it does not make today's records sufficient.

---

## 2. Verified baseline and evidence

### 2.1 Baseline identity (VERIFIED)

| Item | Result |
|---|---|
| Recorded baseline v1 hash | `4d65261d18f6fdb1af007732ca46e443d8c1a057fe1f3e0746a6fc852f391367` (computed at 2026-10-18T17:13:10Z, session record line 16693) |
| Current manuscript file hash | `562230ad6780848c21f92e25c7c88c9287c476461fda3ac625751df08fffc54a`: different, as expected, after 26 logged revisions |
| Reconstruction | 15 commands changed Chapter 1 after v1 (session record lines 16751–17641: scripted replacements, one Edit, two appends). Re-running their scripts against a recorder captured every (old, new) pair, which were then applied **in reverse** to a copy of the current file |
| Reconstructed hash | **`4d65261d…391367`: MATCH** |
| §1.14.1 in v1 vs current | **byte-identical**: 202 lines, sha256 `ce525e50b74582a03ee4be86063cc34513a93d28a85c92b424897baa326b10ae`. No revision since v1 touched §1.14.1 |

**Not touched:** the manuscript, git state and configuration. The scratchpad holds the reconstruction (`v1-recon/`), the reverse script (`v1-recon/reverse.py`) and the extracted commands (`v1-recon/cmds/`).

### 2.2 Material read
- **§1.14.1 (v1)** in full; §1.13 (authority, C1, AM-1…AM-5, delegation) in v1.
- **Targeted review report:** findings F-1…F-19, the case suite, the platform data. It was recovered from the session record.
- **Step-1 record:** `CH-01-step1-correction-and-review.md`.
- **The lead's encodings:** `ch1-model/model2.py`, `suite.py`, `props.py`.
- **Sources:**
  - AMS L38–51, L92–96, L119–123, L200–206, L214–226, L284–288 (via the v1 text, plus a direct check of L121, L135, L284, L298);
  - IM L131 (`validFrom < validUntil, unless open-ended`) and L205–227 (INV-EXC-004, expiry evaluated at the asking instant, `MARK_UNKNOWN`);
  - DE L101–105, L372–380 (causation links, ordering not assumed, version comparison).

### 2.3 Evidence about the expiry boundary (VERIFIED)
No authorized source decides whether a permission is valid **at** its end instant:
- IM L131 requires only `validFrom < validUntil` (a non-empty interval);
- IM L211–219 evaluates expiry "against the asking instant", but does not say whether the end instant is inside;
- AMS L284 lists *expires* as a distinct ending and says nothing about the boundary.

v1's half-open intervals are therefore the **author's convention [ASM]**, not a source rule. This is decision D-4.

### 2.4 Platform data (REPORTED by the targeted review; the grant fields and the resolver statement VERIFIED earlier today)
- 144 grant records with six fields: grantId, humanActRef, authority, status, scope, registeredBy;
- no holder, activity list, period, issuing act or parent link; no global sequence or recording time;
- `session-resolve.php` L268–269: holder "not evaluable from the record".

---

## 3. Reconstruction of the v1 model, with status

| Element | v1 statement (§1.14.1 line) | Status |
|---|---|---|
| act, record of act | acts are events; records are evidence (L83) | [SRC] |
| act time *t*ₓ | "when it was done" (L86) | [SRC] |
| record position *s*ₓ | one global gap-free sequence (L87) | [SRC] in the book; **[ASM]** about the platform, where `seq` is only per file (§2.4) |
| effective time | "may be earlier: void from the start" (L89) | [SRC] |
| total order (*t*, *s*) | used for all comparisons of acts (L89) | [SRC] |
| intervals | "half-open, [from, to)" (L89) | **[ASM]** (§2.3); the **domain** of the bounds is [UNR] (act time or the total order, F-8) |
| activities | adopt, revoke, supersede, end-grant, annul, issue-grant, … (L93) | [SRC]; the list is open ("…") |
| cited | a disjunction of conjunctions; ∅ vs ⊥ (L96, L120–121) | [SRC] |
| grant | holder, activities, scope, start, possibly end, "all as recorded" (L99) | [SRC]: a **primitive record**; issuance is not checked |
| act window | [start, first effective terminal ending) minus suspensions, endings by authorized acts only, own effects ignored (L100) | [SRC]; whether the **period end** counts is [UNR]; read literally, it does not (F-7) |
| root act | authorized by stipulation (L101) | [ASM], marked as a stipulation in the text |
| ρ, κ, χ, ω, σ, π, υ, P, ι | the parameter table (L105–115) | [SRC] as open alternatives |
| covers(B, *x*) | used (L124), **never defined** | [UNR] |
| actor–holder link | not stated | [UNR] |
| authorized(*x*) | L119–125 | [SRC] |
| endings take effect only if authorized | L127 | [SRC]; "authorized" ≠ "authorized and not annulled" ([UNR], F-18) |
| currently-authoritative(*c*, *t*) | L129–133, conditions 1–4 | [SRC]; **no record-state argument and no scope argument** |
| "at-the-time" timing note | L133 | [SRC]; silent for an ending whose act time precedes *x* (§5.6) |
| well-definedness | least fixed point, Knaster–Tarski / Kripke (L135) | [SRC] claim; **[DER] false as stated** (§5.1) |
| headline | L148–155 | [SRC] |
| Properties 1–4 | L157–161 | [SRC] claims; 1 false (§5.4), 2 false under ρ = now (§5.7) |
| case suite | L163–182 | [SRC]; the text says "do not prove the model correct" |
| validity(*c*) = [*t*ₐ, ∞) for claims with no period | L192 | [ASM], marked "convention (Int)" |
| delegation | L176 and §1.13.3(a)–(d) | (a)–(c) are the book's **[DER/ASM]**; (d) is **open**; the **source** says only that delegation and independent authority differ (AMS L285) [SRC] |

---

## 4. The headline claim, made explicit

**v1 Property 1 (L158), written with quantifiers.** For every finite record *K* and every value of (ρ, κ, χ, ω, σ, π, υ, P, ι, C1), for every claim *c* and instant *t*, there is exactly one *h* ∈ {current, cannot determine, not current: *r*} such that H_K(*c*, *t*) = *h*.

**Domain:** finite records whose acts have act times, positions and, for endings, effective times; claims with recorded or conventional validity.

**Verdict: false for some permitted inputs (VERIFIED).** Three witnesses:
- **(i)** under υ = "does not count", mutual annulment has no least fixed point, so there is no value and hence no headline (§5.1);
- **(ii)** "no adoption on record" under C1-b: the formula gives F, the headline rule gives "cannot determine". So H is not a function of the value: two rules give two answers (§5.4);
- **(iii)** an adoption annulled with effect: value F, and no entry of the causal list applies (§5.4).

**Candidate claim (M1-T4, proved in §7).** For every finite record, every parameter value, every *c* and every *t*, H is defined and unique. No domain restriction is needed once (i)–(iii) are repaired. The claim **does not say** that the answer is useful: on today's records it is "cannot determine" everywhere.

---

## 5. The reported defects, investigated separately

Each subsection follows the instruction's format: claim · premises · derivation · conclusion · limitations · counterexample · correction candidate.

### 5.1 Monotonicity of υ (reported F-1, F-2)

- **Claim (v1 L135):** "Strong-Kleene connectives are monotone in that order … So the least fixed point exists and is unique (Knaster–Tarski; Kripke)."
- **Premises:**
  - (P1) values {T, F, U} under the information order ⊑ with U ⊑ T and U ⊑ F (L135) [SRC];
  - (P2) an ending's effect depends on whether its act is authorized "with υ deciding how U counts" (L127) [SRC];
  - (P3) υ ∈ {counts as undetermined, does not count until evidenced} (L113) [SRC].
- **Ordering required:** the pointwise order ⊑ on V₃^D. It is **defined in the text** (L135), so the prerequisite exists.
- **Derivation:**
  1. Let h_υ be the map applied to an ending's authorization. For "counts as undetermined", h = id, which is monotone.
  2. For "does not count", h(T) = T, h(U) = F, h(F) = F.
  3. Then U ⊑ T, but h(U) = F and h(T) = T, and F ⋢ T. So h is **not monotone**.
  4. The window term ¬h(·) inherits this, so the operator Φ is not monotone under step 2's υ.
  5. Without monotonicity, neither Knaster–Tarski nor Kleene guarantees a least fixed point.
  6. Separately, (V₃^D, ⊑) has **no top element**, so it is not a complete lattice and Knaster–Tarski does not apply even when Φ is monotone. The applicable theorem is Kleene's: a monotone map on a pointed directed-complete partial order (here finite) has a least fixed point, reached by iteration from ⊥.
- **Conclusion (VERIFIED):** monotonicity holds for υ = "counts as undetermined" and **fails** for υ = "does not count". The cited theorem is the wrong one in both cases.
- **Counterexample (VERIFIED by computation, `witnesses_v1.py` W1):** two acts, each annulling (backdated) the grant the other relies on, ρ = now.
  - Under "does not count", Kleene iteration runs (U,U) → (T,T) → (F,F) → (T,T) …;
  - the only fixed points are (T,F) and (F,T), which are incomparable, so there is **no least** one;
  - under "counts as undetermined", the iteration stays at (U,U), the least of the three fixed points (U,U), (T,F), (F,T).
- **Limitations:** the failure needs a dependency cycle. Cycles arise only under ρ = now with retroactive or late-recorded effects (§7, M1-T3).
- **Correction candidate:** apply υ **after** the U-valued least fixed point. Stage 1 propagates U; stage 2 suppresses the endings whose stage-1 authorization is U and solves again (M1.3). This keeps υ's meaning ("an ending whose authority cannot be verified has no effect") without making the operator non-monotone. Cite Kleene's theorem.
  - **Cost:** in stage 2, a suppressed ending's own authorization may come out T. It must be reported as "undetermined; effect suppressed", never as "authorized".
  - **Rejected alternative:** restricting υ to acyclic records. It leaves the cyclic cases undefined, which is exactly what totality forbids.

### 5.2 Issuance (reported F-3)

- **Claim (v1 L101, L59):** the regress "stops at the root"; safeguard 2: "a record is not the decision, and that holds for **every** kind of record".
- **Premises:**
  - a grant "has … all as recorded" (L99) [SRC];
  - condition (i) tests only the grant's act window (L123) [SRC];
  - *issue-grant* is an activity (L93) [SRC].
- **Derivation:**
  1. authorized(*x*) through B requires only window membership and coverage (L122–125).
  2. The window is defined from the grant's recorded start and its endings (L100). No term refers to the act that issued the grant.
  3. So a grant whose issuing act is F still yields T for acts under it.
- **Counterexample (VERIFIED against the text, W4):** an issuing act that explicitly cites no grant has authorized = F (L120), yet an act under the issued grant passes (i).
- **Conclusion:** the regress is **not closed**, contrary to L101, and safeguard 2 is not met for grant records.
- **Limitations:** today's records have no issuing-act link (§2.4), so the defect is in the theory, not yet observable in the data.
- **Correction candidate:** a grant is the product of its issuing act. W1 requires the issuing act to be valid at the point in question (M1.3). A grant with no issuing act on record takes κ_g, tied to C1 (D-2).
  - **Source support:** AM-3 ("every grant must reference an immutable human act", AMS L286) [SRC] supports requiring an act. That the act must itself be **authorized** follows from safeguard 2 [DER].

### 5.3 Delegation (reported F-4)

- **Source semantics (VERIFIED):** AMS distinguishes delegated from independent authority, and "not every narrower grant is a delegation" (L285, via §1.13.3 AM-2). **That is all the source rules.** The book adds:
  - (a) scope and period containment;
  - (b) a right to delegate;
  - (c) several parents (a DAG);

  all [DER/ASM]. It also leaves (d), whether ending the parent ends the child, **open**.
- **Claim (v1 L176):** "If the Chair's own grant ends, the Secretary's grant ends with it only if its kind's ending policy says so."
- **Derivation:**
  1. P appears only in condition 4 of currently-authoritative (L133), which concerns **claims**.
  2. The Secretary's later acts are judged by condition (i) on the **child** grant's window (L123).
  3. That window closes only through endings **of the child** (L100).
  4. So no value of P makes the parent's ending reach the child's later acts.
- **Counterexample (VERIFIED against the text, W5):** parent ended by a root act at *t* = 3; child issued at *t* = 1; the child's act at *t* = 5 is authorized under every P. L176 is not derivable.
- **Further gap:** nothing checks that the delegator was entitled to delegate. An unauthorized delegation works (that is the §5.2 defect).
- **Conclusion:** delegation is described in prose and absent from the formula.
- **Correction candidate:**
  - (i) issuing acts are judged like other acts, with the issuing-act conditions in covers (right to delegate; containment). These conditions are labelled **[DER/ASM]**, not source rules;
  - (ii) a cascade term W4, switched by δ (D-3).

  Verified in the M1 encoding: W4 delegation without a right to delegate → F; W5 cascade → T for δ = no, F for δ = yes.
- **Not assumed:** that issuance and delegation are the same operation. A root-issued grant is independent authority with no parents. A delegated grant has parents, and only for those does W4 apply.

### 5.4 Totality of the headline (reported F-5, F-6; v1 Property 1)

See §4 for the claim with quantifiers and the three witnesses.

- **Derivation of (ii):**
  1. "No adoption on record" leaves the disjunction over adoptions empty.
  2. The empty ⋁ is F.
  3. The headline rule (L150) maps this case to "cannot determine" under C1-b.
  4. So two rules assign different values to one input.
- **Derivation of (iii):**
  1. Condition 1 can be the only false conjunct.
  2. The causal list (L152–153) has no entry for "annulled".
  3. So no headline is selected.
- **Correction candidate:**
  - ε inside the formula (H2, the minimal change keeping v1's intended answers);
  - add "annulled (void)" to the causal list, after "not validly made";
  - compute the reason set from the same conjuncts as the value (M1.4).
- **Deeper finding (DER) for decision D-1.** Read literally, C1-b ("records represent authority") makes **no** claim ever F: any F rests partly on the **absence** of a later recorded re-adoption, which under C1-b is "not evidenced", not "did not happen". v1 avoids this only for the zero-adoption case. H2 keeps that asymmetry explicit, which is the smallest change. H1 (every answer is relative to the record, with "no adoption on record" as an F-reason) is the alternative. **Not decided here.**

### 5.5 Expiry (reported F-7)

- **Claim:** case 3 and the business case "expired permission": a decision made after the permission ran out "was never validly made" (L76).
- **Premises:**
  - the window is [start, first effective terminal ending) (L100);
  - "only endings made by **authorized acts** count" (L100);
  - a grant's end of period is recorded data, not an act [SRC, L99].
- **Derivation:**
  1. The end of period is not an act.
  2. So it is not among the endings that close the window.
  3. So the window never closes by expiry.
- **Counterexample (VERIFIED against the text, W3):** end of period 4; an act at 9 lies inside the window as written. Also, π (missing period) appears in no formula.
- **Correction candidate:** the period set by the **issuing act** bounds the window (W2), so expiry is an ending made by an authorized act, the one that set the period. π enters W2. Expiry as a claim ending enters c4 when expiry ∈ P.
- **Boundary cases, both conventions computed (M1 encoding):**

  | Act at | 3 (before) | 4 (= to) | 5 (after) |
  |---|---|---|---|
  | half-open [from, to) | inside | **outside** | outside |
  | closed [from, to] | inside | **inside** | outside |

  The source does not decide which convention applies (§2.3): **D-4**.
- **Interactions:**
  - a child period exceeding the parent's is rejected at issuance under the containment condition (§5.3);
  - with δ = yes, the child also closes at the parent's expiry;
  - a claim made **before** expiry stays current unless expiry ∈ P (case 4 logic).

### 5.6 Time domains (reported F-8, F-9, F-10)

**Domains present in v1 (VERIFIED from the text):**
- act time;
- effective time;
- record position;
- the question instant *t*;
- validity intervals of claims and act windows of grants.

**Missing:**
- the **recording time**, so "what the records showed on 5 March" is not computable (F-10);
- a **record-state argument** of the evaluation, so under ρ = now the answer depends on when it is computed.

**Conflations, each VERIFIED from the text:**
1. **Intervals vs order (F-8).** Windows are half-open **in act time**, while acts are ordered by (*t*, *s*). Two acts at the same act time using one single-use grant each close the window for the other.
   - The witness (W2) stays (U,U) under υ = "counts as undetermined", and oscillates under "does not count", although the record order is known.
   - **Candidate:** intervals in 𝕎 (points (*t*, *s*), time bounds as (*t*, −∞)). The first recorded use wins (M1 encoding: x1 T, x2 F).
2. **ρ partly wired (F-9).** ρ appears in (i) and in the condition-4 timing note, but not in conditions 1 and 3. So a backdated annulment of *x* itself reaches back under both views, contradicting the note.
   - **Candidate:** ρ governs every visibility test, through views.
3. **Nested views (F-9).** v1 does not say whether, when judging *x* under at-the-time, an ending *y* is judged in its **own** view or in *x*'s.
   - **Candidate:** the own view, which matches safeguard 3's "what the records showed at the time" [UNR → PROP]. Under it, M1-T3 proves acyclicity.
4. **The late-recorded early ending (DER, a gap not in the review).** v1's timing note says a not-yet-recorded ending takes effect for *x* "from its own act time, never retroactively".
   - For an ending **dated before** *x* but **recorded after** it (revoked 1 March, recorded 10 March; *x* on 5 March, recorded 6 March), "from its own act time" is before *x*. Condition 4 counts only endings **after** *x*, so the ending never reaches *x*'s product. The claim stays current for ever with no note, even when revocation ∈ P. The reviewer's case 3 points at this.
   - **Candidate:** under at-the-time, a later-recorded ending takes effect for *x*'s products **from its recording time** (λ_e = record-time, which needs τ). M1 encoding: current before 10 March, ended after, when revocation ∈ P.
   - Because this changes what at-the-time means, it belongs to reserved decision 2: **D-6**.

**Permitted comparisons (CANDIDATE):** within each domain; positions to time only through τ. Act time is never compared with position.

### 5.7 Further findings

| Finding | Kind | Result |
|---|---|---|
| **Property 2 fails under ρ = now (NEW; VERIFIED by encoding and by hand).** A late-recorded adoption of the same claim, dated earlier and with a short validity, consumes the single-use grant before the existing adoption: c goes **T → F** | derivation defect; strengthens F-18's "T → U" | v1's proof (T ∨ anything = T) ignores that a new disjunct can change the other disjuncts through consumption. Under ρ = at-the-time the property holds: later entries are invisible to earlier acts. **Candidate:** restate Property 2 as holding under at-the-time, and under ρ = now only for records without consumption by later-recorded acts |
| Case 4's second half is vacuous (DER) | consistency | With single-use semantics, a grant used by *x* cannot be validly used by a later act. "If another act consumed it, the result depends on P" therefore has content only for quota grants (several uses), which v1 does not model. Earlier consumption makes *x* not validly made, with P irrelevant (F-15) |
| F-11 σ = conditional outside the fixed point (REPORTED; VERIFIED in M1) | formal | C(*c*, *t*) keys inside the fixed point; mutual conditional supersession → U |
| F-12 covers / actor–holder link / ω undefined (REPORTED) | formal | covers defined in M1. ω needs a scope argument for C (**not done**; carried as an open item in §12) |
| F-13 revoked annuller (REPORTED; VERIFIED in M1) | policy | ρ = now with υ = undetermined: the revoker is U. With υ = "does not count": the revoked actor's annulment **and** the revoker's revocation are both suppressed, so the revoked actor keeps acting. Under at-the-time: revoker T, revoked actor F. Neither ρ = now variant is attractive: **D-7** (C10) |
| F-14 issuer vs holder (REPORTED; resolver line VERIFIED earlier) | data / text | "the recorded 'PO/ARB' grants are one holder" (L98, L101) should read **issuing authority**. Text fix only |
| F-16 κ vs C1 (REPORTED) | consistency | (DER) κ = constitutive under C1-b breaks the promise "missing evidence is never reported as missing authority" (L69). The admissible pairs are (C1-a, κ any) and (C1-b, κ evidential), a **derived restriction**, not a new choice |
| F-17 unstated assumptions (REPORTED) | — | (a) citation is constitutive (a parameter; not added); (b) C6 adopted silently (the parameter is not yet in M1: an open item); (c)–(g) addressed above or listed in §10 |
| F-18 annulled revocation | formal | valid() = authorized ∧ not annulled (M1). Encoding: annulled revocation → claim current |
| F-19 AMS line offsets; π support | fidelity | "on its face, permanent" is AMS L123, and AMS states it as a defect: L112 should not cite it as support for π = open-ended. Text fix only |

---

## 6. Candidate model M1

The full candidate text is in `M1-candidate-model.md` (separate, so that the reviewer can attack it on its own). The changes against v1, in one line each:
1. domains and points (𝕎), recording time τ, record-state argument;
2. grants as products of issuing acts (W1), the period inside the window (W2);
3. endings and consumption (W3), with consumption as the effect of a valid use (C13 → D-5);
4. delegation cascade W4 under δ (D-3); covers defined, with issuing-act conditions;
5. valid() = authorized ∧ not annulled;
6. claims inside the fixed point (σ);
7. ε puts C1 in the formula (H2);
8. "annulled (void)" in the headline list;
9. υ in two stages;
10. own-view at-the-time with the record-time timing for late entries (D-6).

**Kept unchanged:** act time vs time of use, P, ρ, κ, χ, π, σ, ι, C1, C6 (not yet parameterized), C8, the three values, the reasons, and the business safeguards. **The six safeguards are now all expressible.** Safeguard 6 ("nobody can freeze by an unverifiable entry") holds under υ = "does not count" except in the cyclic case F-13 under ρ = now (D-7).

---

## 7. What is proved, and what is only tested

### Proofs (M1)

**M1-T1 (existence and uniqueness, stage 1).** For a finite record and a fixed question, the key set *D* is finite:
- acts;
- (grant, point, view, judge) with points and judges drawn from acts and views from positions ∪ {now};
- claims at the question instant, plus their successors.

(V₃^D, ⊑) is a finite poset with least element ⊥ (all U). Each coordinate of Φ is a term built from constants (record data and comparisons of data) and other coordinates using Kleene ¬, ∧, ∨ over finite index sets. Each connective is ⊑-monotone:
- ¬U = U ⊑ ¬*a*;
- if *a* ⊑ *a′* and *b* ⊑ *b′*, then *a* ∧ *b* ⊑ *a′* ∧ *b′*, by case analysis on F, T and U;
- dually for ∨.

The non-monotone map h(U) = F is **not** used inside Φ. So Φ is monotone. The chain ⊥ ⊑ Φ(⊥) ⊑ Φ²(⊥) ⊑ … is increasing in a finite poset. Each coordinate changes at most once (from U), so the chain is stationary after at most |*D*| strict steps. Its limit is a fixed point below every fixed point: by induction, Φⁿ(⊥) ⊑ *q* for every fixed point *q*. So it is the **unique least** fixed point (Kleene). ∎

**M1-T2 (stage 2, υ = "does not count").** The suppressed set S is fixed by stage 1 and does not vary during stage 2. Φ_S has the same form as Φ, with fewer terms, so M1-T1 applies. ∎ *Limitation:* stage-2 values of members of S are not reported as their authorization (§5.1).

**M1-T3 (no cycles under at-the-time).** Under ρ = at-the-time with own views, A(*x*) reads only:
- W keys of view *s*ₓ;
- through them, A(*y*) for acts with *s_y* < *s*ₓ: issuing acts, endings and annulments visible in the view, and uses before *p*(*x*).

Induction on *s* therefore gives every A(*x*) a value determined by earlier entries. **A-values never depend cyclically on each other**, so U arises only from genuine unknowns (κ, π, λ, missing holders) or from one recorded inconsistency: **cyclic issuance** (*g* issued under *h* and *h* under *g*). Cyclic issuance makes the W keys of one view depend on each other, and they stay U. Mutual annulment, mutual revocation and the F-13 freeze cannot occur under at-the-time. ∎

**M1-T4 (total headline).** If the value is T or U, the headline is fixed. If the value is F:
- either there is no recorded adoption and C1-a holds, giving the reason "never adopted";
- or every disjunct is F, so each has at least one conjunct equal to F. Every conjunct maps to an entry in the causal list (c1: not validly made or annulled; c2: no longer valid or not yet valid; c3: revoked or superseded; c4: authority ended or suspended).

So R ≠ ∅, and the first entry in causal order is unique. ∎

**Property 2′ (CANDIDATE restatement).** Under ρ = at-the-time, adding an adoption act or a basis for *c* never turns T into F, but may turn F or U into T.
- *Proof sketch:* a later-recorded entry is invisible to earlier acts' views, so existing disjuncts keep their values, and ⋁ with one more term cannot decrease from T.
- Under ρ = now this fails (§5.7 witness).

**Property 4 (views agree without late or backdated entries)** carries over: with no entry recorded after an act it affects, each own view already contains every entry the "now" evaluation uses for that act, so the two views coincide (v1's sketch, unchanged). *Not re-proved for quota consumption.*

### Tests (encoding evidence only)

- `witnesses_v1.py`: six witnesses against the **v1 text**, encoding only quoted rules. W1 and W2 are computed iterations; W3–W6 restate the text literally.
- `cases_m1.py`: 33 checks of M1 against **my own expectations**, 33/33. This shows the encoding behaves as **I** intended. It does **not** validate the model: the expected values were written by the same author.
- **Monotonicity probe:** 0 violations of Φ(V) ⊑ Φ(W) in 4,000 random pairs V ⊑ W over random records. This is **consistent with** M1-T1, which is the proof; the probe only searches for an encoding error.
- `prop2_check.py`: the Property 2 counterexample (T → F under ρ = now; T → T under at-the-time).
- **An instance of the masking lesson, recorded honestly.** My first M1 run gave 12 unexpected U values. Every one was caused by my **test helper's default** (grants without a period, so π = undetermined), not by the model. One unstated default in a test changed a third of the results. That is the same mechanism by which F-5 and F-7 escaped the lead's v1 tests.

---

## 8. Challenge of the lead's v1 tests

Assumptions encoded in `model2.py` / `suite.py` / `props.py` that the **text** does not contain:

| # | Encoded assumption | What it hid |
|---|---|---|
| 1 | `fixpoint()` freezes the first defined value ("only allow U→T/F changes") | **This is not Kleene iteration of the text's operator.** It suppresses the oscillation under υ = "does not count" (F-1) by forcing an arbitrary fixed point |
| 2 | `window()` closes at the grant's recorded `end` | F-7: the text closes windows only by acts |
| 3 | `current()` returns U for "no adoption" under C1-b | F-5: it encodes the **headline**, not the formula (empty ⋁ = F) |
| 4 | `covers()` invents holder ∈ actors and a scope test | F-12: covers is undefined in the text |
| 5 | σ = conditional recursion capped at depth 3 | F-11: mutual conditional supersession is cut off, not resolved |
| 6 | consumption encoded as a **root** end-grant act with `cause` | C13 / F-15: it assumes consumption is an authorized act |
| 7 | at-the-time timing uses max(eff, act time) | the §5.6 late-recorded early-ending gap |
| 8 | no issuing acts at all; grants primitive | F-3, F-4 cannot appear |
| 9 | suite expectations written by the lead from the lead's reading | circular: they confirm the encoding, not the text |
| 10 | `props.py` random worlds: no issuance, no delegation, no same-time uses, no cyclic backdating under υ = "does not count" | the generator could not produce the counterexamples |

**Proposed independent tests**, derived from the safeguards and requirements and not from any encoding. They should be written by someone other than the lead (§9):

| Area | Negative | Boundary | Adversarial |
|---|---|---|---|
| issuance | grant issued by an act citing nothing; issued by an annulled act | issuance at the same point as first use | issuing act recorded after its first use (late ratification, λ) |
| delegation | child scope wider than the parent's; no right to delegate | child period ending exactly at the parent's end | delegation cycle *g* ↔ *h*; parent revoked **and** reinstated |
| expiry | act after `to` | act at `to`, `to` − ε, `to` + ε, under both conventions | expiry and revocation at the same instant; a claim made before expiry with expiry ∈ P |
| time domains | same act time, different positions | backdated eff equal to the act time of another act | late-recorded early ending (§5.6); evaluation at two record states K₁ ⊂ K₂ |
| fixed point | mutual annulment under both υ | three-cycle of annulments | revoked annuller (F-13); cycle through σ = conditional |
| headline | no adoption (C1-a / C1-b); annulled adoption | every reason present at once (causal order) | reasons under stage-2 suppression |

**Testable mechanically:** values and headlines on concrete records; the absence of oscillation; monotonicity probes. **Requires reasoning:** existence, uniqueness, acyclicity and totality **for all** records (proofs, §7). **Requires human interpretation:** whether an answer is the **intended** one (D-1…D-7).

---

## 9. Independent review

**The packet** (`review-packet/PACKET.md`) contains:
- the v1 chapter (reconstructed, hash-verified) and the candidate M1 text;
- the claimed theorems with their proofs, as claims to falsify;
- the lead's encodings, marked suspect;
- the authorized source excerpts.

It does **not** contain my per-defect conclusions, my recommendations, or §10. The reviewer is asked to reconstruct independently and to falsify. **The reviewer's findings are recorded individually in `REVIEW-RECORD.md` when it reports.** Agreement with the lead is not counted as validation.

---

## 10. Decisions requiring Principal approval

| # | Question | Alternatives | Consequences | Evidence | Recommendation (AI) | Approval needed |
|---|---|---|---|---|---|---|
| **D-1** | How does C1-b treat absence (§5.4)? | **H2:** U only when no adoption is recorded at all (v1's intended answers). **H1:** every answer is record-relative; "no adoption on record" is an F-reason, and the headline says "on the record" | H2 is minimal but keeps an asymmetric closure assumption. H1 is uniform, but its "not current" never means more than "not shown" | IA L99–101; AMS L49–51; v1 L69, L150 | **H2 now** (smallest change); revisit with C1 | choice of H1/H2, as part of the C1 decision |
| **D-2** | κ and κ_g under C1 (§5.2, F-16) | tie κ to C1 (derived restriction) / keep independent | independent κ = constitutive under C1-b breaks the stated promise | v1 L69 | **tie them** (the restriction is derived; a choice only if the promise is dropped) | confirm the restriction |
| **D-3** | Delegation (§5.3): (a) containment; (b) right to delegate; (c) does ending the parent end the child (δ)? | δ = yes / no / per kind | yes: a delegate falls with the delegator. No: delegated authority survives. (a) and (b) are book derivations, not source rules | AMS L285; §1.13.3(a)–(d) | (a) and (b) **adopt as assumptions**; δ **yes for delegated grants** (as §1.13.3(d) leans), via P | approve (a), (b) as governance rules; choose δ |
| **D-4** | Expiry boundary (§5.5) | half-open [from, to) / closed [from, to] | half-open: invalid at the end instant; closed: valid at it | IM L131, L211–219; no source decides | **half-open** (a period [from, to) chains without overlap or gap) | choose |
| **D-5** | Consumption (C13; §5.6) | the effect of a **valid** use / an act / a condition | "valid use": an unauthorized actor cannot exhaust a grant (safeguard 6). "Act": consumption needs its own authorization; the source says it is "recorded, not newly authorized" | the CONSUMED record (§1.13.2) | **effect of a valid use** | choose |
| **D-6** | At-the-time timing of later-recorded entries (reserved decision 2) | from **recording time** / from the act time (v1's note) | record-time: a late revocation ends the product once known. Act-time: a revocation dated before *x* never reaches it | v1 L133; §5.6 | **record time**; needs the recording-time axis | part of reserved decision 2 |
| **D-7** | Cycles under ρ = now (C10, F-13) | U (honest; a freeze is possible) / forbid retroactive effect against earlier-recorded acts / use at-the-time where cycles matter | U freezes the revoker; prohibition loses "void from the start" for those acts; at-the-time has no cycles (M1-T3) | F-13; M1-T3 | report cycles as a distinct reason "**cycle**"; recommend at-the-time as the default view | choose |

**Reserved decisions unchanged by this proposal:** 1 (P), 3 (P-E), 4 (ι), 5 (OQ-24), 6 (υ itself; M1 only makes both values well-defined). Also unchanged: C6, C8, C11 (λ), C12.

**Not decided by me:** whether the universal headline claim should be **weakened**. M1 keeps it universal by repairing the inputs, so no weakening is proposed. If the Principal prefers not to adopt M1's changes, the alternative is to restrict the claim to υ = undetermined and C1-a, which is a weaker theory.

---

## 11. Impact on dependent claims (if M1 were adopted later)

- **§1.14.1:**
  - the formal block, the status note, Properties 1–2, cases 3, 4, 11, 13, 15;
  - L98/L101: issuer, not holder (F-14);
  - L112: the AMS L123 citation (F-19).
- **Glossary:** "current authority" (theorem name, record-state argument, "annulled"); "record view ρ" ("when each act was **recorded**"; remove "decides whether annulment is retroactive", F-16); "root act"; "ending policy P" (delegation via δ).
- **§1.16.2:** authorizedρ notation and the ρ sentence (F-16).
- **§1.13.3:** (a)–(b) labelled as adopted assumptions, if D-3 is approved; (d) linked to δ.
- **§1.14.5 (P-E):** no change; it consumes "currently authoritative".
- **Chapter 2 §2.11 ("effective"):** no change.
- **Glossary "verifiable":** no change.
- **Platform requirements, a consequence and not a change:** to evaluate M1 on real records, grants need holder, activities, period, issuing act and parent link; endings need kind, act time, effective time and target; every record needs a global position and a recording time; rules need their cited bases (consistent with the review's §6).

---

## 12. Readiness

| Question | Answer |
|---|---|
| Baseline verified? | **Yes** (§2.1) |
| All six reported defects investigated? | **Yes.** All six are confirmed as defects of the text; one new defect (§5.7) and one new gap (§5.6, item 4) were found |
| Candidate corrections? | **Yes** (M1), each the smallest found. **Still open in M1:** ω's scope argument, the C6 parameter, quota consumption |
| Proofs? | M1-T1 … T4 and Property 2′, with stated limitations; **not yet independently checked** |
| Policy assumptions unresolved? | **Yes: D-1 … D-7.** Under the Principal's instruction "stop and report if a required policy assumption remains unresolved": M1 is **formally defined for every value** of these choices (they are parameters), so the formal repair does not depend on them. **The text of a revised §1.14.1 cannot be finalized until D-3, D-4 and D-6 are chosen**, because the business cases depend on them |
| Ready to become the baseline? | **No.** It needs the independent review (§9), the Principal's decisions, and then a separately authorized implementation |
