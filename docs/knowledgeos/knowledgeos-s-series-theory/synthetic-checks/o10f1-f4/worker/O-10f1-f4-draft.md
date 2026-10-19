# O-10f1 – O-10f4 (and O-10i1): contestability, constrained faults, output-reading rules, superseded decisions

| Field | Value |
|---|---|
| Author / clock | Worker A, 2026-10-18 05:39–05:55 CEST |
| Inputs read | only `20261017_2052_O-10f-verifiability.md`, `20261017_2052_O-10i-revisable-decisions.md`, `20261017_2016_O-10a-ordering-history-dependent-records.md` |
| Status | **WORKER DRAFT — UNREVIEWED.** Synthetic and analytical only. |
| Protocol | `PROTOCOL.md` v1, sha256 `34e6ce9de03220952a684a15dc73d5910b053bf0cb5b30613038f8b27ade8887`, frozen 05:39:59, before code |
| Code / results | `workerA_checks.py` sha256 `2ffee359a53514b3f8d7b79e6882419226b9a8b29da45f5cf2c469e5c33fa5a0`; `RESULTS.txt`, run 05:40:38. All 7 check groups as pre-declared; no protocol change. |
| Labels | DEFINITION · PROVED THEOREM · ANALYTIC ARGUMENT · EXPLICIT COUNTEREXAMPLE · FINITE COMPUTATIONAL CHECK · EXPLORATORY · STAKEHOLDER-POLICY CHOICE |

The finite checks re-confirm algebra that is proved below. They are guards against slips, **not independent evidence**.

---

## 0. Common framework

**Definition 0.1 (worlds, views, determination).** [DEFINITION]
- Ω is a set of possible worlds. A world fixes everything relevant: true raw inputs, actual outputs, attesters' behaviour, rule versions in force, and so on.
- A **view** is a map V : Ω → R (what an observer sees). A **property** is a map φ : Ω → P.
- V **determines φ on S ⊆ Ω** iff ∀w, w′ ∈ S: V(w) = V(w′) ⇒ φ(w) = φ(w′). Equivalently, (ker V) ∩ (S×S) ⊆ ker φ.

**Definition 0.2 (trust as world restriction).** [DEFINITION]
- A record component a is an **attestation** of a claimed function f_a if, in a world where the attester is honest, a(w) = f_a(w).
- An observer with trust set T considers only Ω_T := {w ∈ Ω : every a ∈ T is truthful in w} ∩ Φ, where Φ ⊆ Ω is the **fault model**: the worlds the observer regards as possible at all.
- Trusting more components gives fewer worlds: T ⊆ T′ ⇒ Ω_{T′} ⊆ Ω_T.

**Assumption OF (output-log fidelity).** The o-log entry for step t equals the output actually issued. Throughout, the o-log is trusted **as a record of what was decided** but not as being correct. This makes explicit what V1/V2 used implicitly. Where it is dropped, that is said.

**Correctness bit.** c_t := [o_t = d_t], where d_t is the correct decision under the stated rule.

**Verifiability (V2's notion, restated).** [DEFINITION] A record view V is **verifiable under T** iff, for every t, V_t determines c_t on Ω_T.

---

## 1. O-10f1 — Contestability

### 1.1 Definitions

**Definition 1.1 (party profile).** [DEFINITION] A party p is a quadruple (T_p, A_p, K_p, Op_p).

| Element | Meaning |
|---|---|
| T_p | the record components p accepts as truthful. It gives p's world set Ω_p := Ω_{T_p}. |
| A_p | the record components p may **see** (access). The record view is V_p^rec(w) = (r_i(w))_{i∈A_p}. |
| K_p | p's **private knowledge** outside the record, e.g. p's own submitted data. It is a map K_p : Ω → R_K. Often empty. |
| Op_p | the verification operations p can perform, as a class of functions on p's view. The information-theoretic case is Op_p = all functions. Restricted classes model computational, legal or practical limits. |

p's full view is V_p = (V_p^rec, K_p).

**Definition 1.2 (epistemic establishment).** [DEFINITION] p can establish φ iff there is a g ∈ Op_p with g(V_p(w)) = φ(w) for all w ∈ Ω_p. With Op_p = all functions, this is "V_p determines φ on Ω_p".

**Definition 1.3 (demonstrative establishment before an adjudicator J).** [DEFINITION]
- p can **demonstrate** φ to J iff there is a submission s, computable from what p can access, such that (s, J's own view) determines φ on Ω_J.
- Here Ω_J is defined by J's trust set, and s may contain only record parts in A_p and items J trusts. p's private knowledge K_p counts only through items J trusts, e.g. originals signed by a third party J trusts.
- **Why both are needed:** epistemic contestability lets p *know* whether to challenge. Demonstrative contestability lets p *win the argument* before J.

**Definition 1.4 (the four contest levels for decision t).** [DEFINITION]

| Level | Property φ | Mathematical status |
|---|---|---|
| (i) Reconstruction | φ_i = (o_t, v^cited_t): what was decided, and under which cited rule version | formal |
| (ii) Correctness | φ_ii = c^cited_t = [o_t = F_{v^cited_t}(x_t)] | formal |
| (iii) Procedural / legal basis | φ_iii = [v^cited_t = App(t, ctx_t)] ∧ [Auth(actor_t, t)] ∧ … | formal **given** App, Auth and the other predicates as published functions. **Whether App or Auth correctly captures the law is outside mathematics.** |
| (iv) Remedy | that a successful challenge yields a superseding decision D(h_u, t) ≠ D(h_t, t) | **outside mathematics.** It depends on an institution with power to supersede, and on law and procedure |

Notes on the predicates in (iii):
- App maps a decision context to the legally applicable rule version.
- Auth(actor, t) holds iff the actor holds valid authority at t.
- "…" stands for further procedural predicates: deadline met, required notice given, and so on.

What mathematics can say about (iv):
- It can give **necessary record conditions**: a correction channel exists in the record (O-10i: corrections are appended superseding entries), and p can demonstrate (ii) or (iii) to the body with power to supersede.
- It cannot give sufficient conditions.

Applicable-rule correctness is c^app_t := [o_t = F_{App(t,ctx)}(x_t)]. Two facts:
- If (ii) and (iii)'s version clause both hold, then c^app_t = c^cited_t.
- c^app_t can also be established **without** (iii), namely when every candidate version yields the same value on x_t. [ANALYTIC ARGUMENT]

**Definition 1.5 (contestability).** [DEFINITION] Record view V is **level-k contestable for p** (epistemic, or demonstrative before J) iff p can establish (respectively demonstrate) φ_k for every decision t concerning p.

**Requirement ordering.** Requirement R₁ is **stronger** than R₂ on a class 𝓡 of records iff R₁ ⇒ R₂ on every record in 𝓡. It is **strictly stronger** if, in addition, some record satisfies R₂ but not R₁.

### 1.2 Results

**Theorem F1-1 (monotonicity).** [PROVED THEOREM]

Let auditor a = (T_a, A_a, ∅, Op_a) and party p = (T_p, A_p, ∅, Op_p). Assume **dominance**:
- T_p ⊆ T_a;
- A_p ⊆ A_a;
- K_p = ∅;
- for every g ∈ Op_p, g∘π ∈ Op_a, where π is the projection from A_a-views to A_p-views. This holds in the information-theoretic case.

Then, for every level k ∈ {i, ii, iii}: if p can establish φ_k, so can a. In particular, level-(ii) contestability for p implies auditor verifiability.

*Proof.*
- Ω_a ⊆ Ω_p, because T_p ⊆ T_a.
- V_p = π∘V_a.
- If g(V_p(w)) = φ(w) on Ω_p, then (g∘π)(V_a(w)) = φ(w) on Ω_a ⊆ Ω_p, and g∘π ∈ Op_a. ∎

**Proposition F1-2 (strictness: distrusted attestation).** [EXPLICIT COUNTEREXAMPLE; FINITE COMPUTATIONAL CHECK K6a]

*Setting:*
- x ∈ {0,1}, F = id, o ∈ {0,1} unconstrained.
- The record holds o and a, an attestation of x by attester Z, e.g. the deciding body's own data office.
- The auditor trusts Z: T_a = {a, F}. The party does not: T_p = {F}. Both have full access, and K_p = ∅.

*Result:*
- The auditor's world set is a = x, so c = [o = a] is determined.
- In the party's world set, the worlds (x=0, a=0, o=0) and (x=1, a=0, o=0) have the same view but different c.
- So the record is auditor-verifiable but **not** level-(ii) contestable by p. Dominance holds, so with F1-1, contestability for p is strictly stronger than auditor verifiability on this class.

**Proposition F1-3 (strictness: redacted field).** [EXPLICIT COUNTEREXAMPLE; K6b]

*Setting:*
- x = (x_p, x_q) ∈ {0,1,2}², where x_q is a third party's data (e.g., a competitor's score). F = [x_p > x_q], and o ∈ {0,1}.
- All fields are attested and trusted by both.
- A_a = all. A_p excludes x_q (privacy redaction).

*Result:*
- The auditor verifies.
- p cannot: (x_p=1, x_q=0, o=1) and (x_p=1, x_q=2, o=1) have the same view and different c.
- So contestability is strict again, here through **access** rather than trust.

*Mitigations:* each restores (ii) only by adding trust.
- An attested derived field [x_p > x_q]: trust moves to the attester, as in V4.
- A zero-knowledge proof: computational assumption; literature, **unverified**.

**Proposition F1-4 (strictness fails without dominance: incomparability).** [EXPLICIT COUNTEREXAMPLE; K6c]

*Setting:*
- The record holds only o, with no evidence. F = id, x ∈ {0,1}.
- The party knows its own x privately: K_p = x.

*Result:*
- p can epistemically establish c. The auditor cannot.
- Together with F1-2 (auditor yes, party no), epistemic party contestability and auditor verifiability are **incomparable** in general.

*Demonstrative version:* if p's private x is in a form J trusts (e.g. an original signed by an issuer J trusts), p can demonstrate c to J although the record alone is not verifiable.

**Revised claim** (replacing V5's "contestability ≥ auditor verifiability when T_p ⊆ T"):
- Party contestability is **at least as strong** a requirement on the record as auditor verifiability **under dominance** (F1-1). It is **strictly** stronger on classes containing F1-2 or F1-3.
- Without dominance (private knowledge, or trust in sources the auditor does not accept), the two are **incomparable** (F1-4).
- V5 stated only T_p ⊆ T. **Access (A_p ⊆ A_a) and the absence of private knowledge are also needed.**

**Proposition F1-5 (levels are logically independent).** [EXPLICIT COUNTEREXAMPLES; analytic]

| Separation | Witness |
|---|---|
| (i) ⇏ (ii) | V1 |
| (ii) ⇏ (iii) | the record holds attested x, o and v^cited, with F_v published, but holds no effective-date registry for versions. Worlds with App(t, ctx) = v^cited and App(t, ctx) ≠ v^cited have the same view, so φ_iii is not determined, while c^cited is |
| (iii) ⇏ (ii) | an effective-date registry and an authority record are present, but there is no evidence about x |

**Hence:** a record designed for auditor verifiability of correctness (V2) need not support procedural contestation. Procedural contestation needs:
- an attested, immutable **version-effective-date** registry;
- an attested **authority** (delegation) record;
- both **as-of-t**; see §4.

### 1.3 Limitations of §1

- Demonstrative contestability depends on J's trust set. That is a **stakeholder or legal** parameter, not derived here.
- Op_p restrictions (e.g. "cannot run the rule engine") were modelled, but no non-trivial instance was analysed.
- Level (iv) is outside mathematics.
- Correctness of App and Auth with respect to the law is outside mathematics.

### 1.4 Architectural implication (ARCHITECTURAL PROPOSAL; not adopted)

- Specify contestability **per party class**, as (T_p, A_p, J). Auditor verifiability does not discharge it.
- Wherever A_p redacts decision-relevant fields, a party-facing attested derived artefact, or a proof, is needed. **Who attests** is a stakeholder decision.
- The record needs version-effective-date and authority records for level (iii).
- **STAKEHOLDER-POLICY CHOICES:**
  - which party classes must be able to contest;
  - which adjudicator J and which trust set J holds;
  - what redaction is lawful.

---

## 2. O-10f2 — Constrained fault models

### 2.1 Single step

**Setting.** [DEFINITION]
- The admissible set (fault model) is W ⊆ X × O, the possible (true input, actual output) pairs. X_W is its x-projection.
- The rule is F : X → O. Evidence is attested, trusted and x-only: e : X → Ev. The auditor's view is (e(x), o), under OF.
- **Ver(e, W)** :⇔ (e(x), o) determines c = [o = F(x)] on W.

**Definition 2.1 (confusable pair).** [DEFINITION] x, x′ ∈ X_W are **W-confusable** iff
- F(x) ≠ F(x′), and
- there is an o ∈ {F(x), F(x′)} with (x, o) ∈ W and (x′, o) ∈ W.

K_W denotes the set of confusable pairs.

**Theorem F2-1 (exact characterization).** [PROVED THEOREM; K1: 69,120 triples, 0 mismatches]

Ver(e, W) ⇔ no W-confusable pair has e(x) = e(x′). Equivalently, ker e ∩ K_W = ∅.

*Proof.* Ver fails iff there are (x, o), (x′, o′) ∈ W with equal view, so e(x) = e(x′) and o = o′, and with [o = F(x)] ≠ [o = F(x′)]. That inequality holds iff o equals exactly one of F(x), F(x′). This forces F(x) ≠ F(x′) and o ∈ {F(x), F(x′)}, i.e. a confusable pair identified by e. Conversely, any such pair, with its witness o, violates Ver. ∎

**Corollary F2-1a (the (⇐) direction is universal).** [PROVED] If e determines F on X_W, then Ver(e, W) holds for **every** fault model W.
- *Reason:* e-identified pairs then have F(x) = F(x′), so they are not confusable.
- K2 confirms this over 13,824 (F, W) pairs, with 0 failures.
- **So only V2's (⇒) direction depends on the fault model.**

**Theorem F2-2 (weakest condition for V2's equivalence).** [PROVED THEOREM; K2: 0 mismatches]

The statement "for all evidence maps e: Ver(e, W) ⇔ e determines F on X_W" holds **iff** every pair x, x′ ∈ X_W with F(x) ≠ F(x′) is W-confusable. Call this condition **(Conf)**.

*Proof.*
- (⇐) Corollary F2-1a gives one direction. For the other: if e does not determine F, it identifies some F-distinct pair. By (Conf) that pair is confusable, so Ver fails (F2-1).
- (⇒) Suppose (Conf) fails for a pair (x, x′). Let e identify **only** x and x′, and be injective elsewhere. Then e does not determine F. The only identified pair is not confusable, so Ver holds (F2-1). The equivalence therefore fails for this e. ∎

*Strength of (Conf):*
- (Conf) is strictly weaker than V2's (a) ∧ (c). A sufficient condition is: every x ∈ X_W may output every value of F(X_W).
- (Conf) needs only that, for each F-distinct pair, **one** of the two inputs can output the **other's** correct value, while the other's correct output is also admissible.
- For a **fixed** e, the exact condition is F2-1 itself: the equivalence holds for that e iff every e-identified F-distinct pair is confusable.

**Proposition F2-3 (the counterexample, made complete).** [EXPLICIT COUNTEREXAMPLE; K3]

*Setting:* W = {(x, F(x)), (x, F(x)+2)}.
- **(a)** X = {0,1} and F = id. Then o ∈ {0,1} means correct and o ∈ {2,3} means faulty. Empty evidence is verifiable but does not determine F. **(⇒) fails; (⇐) holds.**
- **(b)** The reviewer's counterexample silently needs a condition on F's range: **no two values of F(X_W) differ by exactly 2.** If F(x) = 2x, then x=0 (faulty, o=2) and x=1 (correct, o=2) are confusable, and empty evidence is **not** verifiable (K3b).
- **Correction to the O-10f report's wording:** the counterexample holds when the range of F avoids differences of 2. It is not valid for an arbitrary F.

*Why (⇒) fails:* V2's proof of (⇒) picks, for an e-identified F-distinct pair, a common output equal to one of the two correct values. Under a constrained W that output may be inadmissible for the other input. **The output then leaks the correctness bit, so the evidence needs to say less.**

**Remark F2-4 (verifiable ≠ decision-recoverable).** [ANALYTIC ARGUMENT]
- Under constrained W, a verifiable record can leave d undetermined when faulty. In F2-3(a), o = 2 tells the auditor "faulty", but d = 0 is recovered only through the rule o − 2.
- In general, verifiability (knowing c) and recoverability of d (ker ⊆ ker d) are **distinct**: neither implies the other.

### 2.2 Multi-step (outputs from the x-history only)

**Setting.** [DEFINITION]
- Worlds are w = (x̄, ō) ∈ W ⊆ (X×O)^n. The **joint** fault model may couple steps, e.g. "at most k faults" or "persistent faults".
- d_t = F_t(x̄_t). Cumulative attested evidence at t is G_t(x̄_t). The view at t is (G_t, ō_t) under OF.
- **Ver_t** :⇔ the view at t determines c_t on W_t, the length-t prefixes of W. Ver :⇔ Ver_t for all t.

**Definition 2.5.** [DEFINITION] x̄, x̄′ ∈ X_{W,t} are **t-confusable** iff
- d_t(x̄) ≠ d_t(x̄′), and
- there is an o-history ō of length t, admissible with **both** x̄ and x̄′ in W_t, with ō_t ∈ {d_t(x̄), d_t(x̄′)}.

**Theorem F2-6 (multi-step characterization).** [PROVED THEOREM; K4: 20,000 random instances, 0 mismatches]

Ver_t ⇔ G_t identifies no t-confusable pair.

*Proof:* as for F2-1, with x replaced by x̄_t and o by ō_t. Equal views need equal **whole** o-histories, so the witness must be a common o-history. ∎

**Corollary F2-7.** [PROVED]

Let **(S-multi_t)** say that every d_t-distinct pair of X_{W,t} is t-confusable. Under (S-multi_t), Ver_t ⇔ G_t determines d_t (K4: 11,835 S-multi instances, 0 mismatches).

As in F2-2, "for all cumulative views G_t, Ver_t ⇔ G_t determines d_t" holds iff (S-multi_t). The proof is F2-2's, applied to step t in isolation.

*Not established:* the analogous necessity for **all steps jointly**, with a single append-only evidence family. The construction identifying only one pair at step t may break Ver_s for s < t. **OPEN.**

**Proposition F2-8 (the multi-step phenomenon: past outputs leak correctness).** [EXPLICIT COUNTEREXAMPLE; K4 named]

*Setting:*
- n = 2, binary, F_t = x_t.
- The fault model is "**all honest or all inverted**": ō = x̄, or ō = 1 − x̄ componentwise.
- Evidence: e₁ = x₁, and **nothing** at step 2.

*Result:*
- Ver₂ holds, because c₂ = c₁ and c₁ is determined at step 1. Yet G₂ does not determine d₂.
- The step-2 **marginal** fault model is unconstrained: all four (x₂, o₂) pairs are admissible. So **per-step single-step analysis predicts that verification needs x₂, and is wrong.**
- (S-multi₂) fails: (0,0) and (0,1) have no common admissible o-history.

**Consequence:** a multi-step verifiability analysis must use the **joint** fault model. Per-step marginals can over-demand evidence. Per-step reasoning is still **sound** in the (⇐) direction: evidence determining every d_t always suffices.

### 2.3 Limitations and architectural implication

*Limitations:*
- Deterministic records only (V6).
- The joint necessity in multi-step is open.
- Fault models are assumptions. If W is narrower than reality, a record verified under W is **not** verifiable under the true fault model. **Exploiting a constrained W trades evidence for a fault-model assumption.**

*Architectural implication (ARCHITECTURAL PROPOSAL):*
- Size evidence by the (⇐) direction: **evidence that determines the decision**. That is robust to every fault model.
- Any smaller evidence set justified by a constrained fault model (e.g. error-detecting output encodings, all-or-nothing failure) must name that fault model as a **trusted assumption** in the trust set.
- **STAKEHOLDER-POLICY CHOICE:** whether such assumptions are acceptable for critical decisions.

---

## 3. O-10f3 — Rules reading past outputs; evidence including outputs

### 3.1 Model

[DEFINITION]
- d_t = F_t(x̄_t, ō_{<t}): the rule reads earlier **actual** outputs, e.g. "a second refusal triggers a review".
- Fault model W ⊆ (X×O)^n. OF holds. Evidence G_t is attested.
- **Two distinct correctness notions:**

| Notion | Definition | Meaning |
|---|---|---|
| **Local (stepwise) correctness** | c^loc_t := [o_t = F_t(x̄_t, ō_{<t})] | was the rule applied correctly at t, given what actually happened? |
| **Global (counterfactual) correctness** | c^glob_t := [o_t = d\*_t], with d\*_t := F_t(x̄_t, d\*_{<t}) defined recursively | is o_t what a fault-free run would have produced? |

**Which notion governs is a STAKEHOLDER-POLICY CHOICE**, e.g. whether an appeal concerns the step or the whole trajectory.

### 3.2 Results

**Theorem F3-1 (local correctness reduces to F2-6, given OF).** [PROVED THEOREM]

Treat ō_{<t} as part of the "input" seen through the trusted o-log. Ver^loc_t ⇔ the view (G_t, ō_t) identifies no pair of worlds that:
- share ō_t,
- have different F_t(·, ō_{<t}), and
- have ō_t ∈ {F_t(x̄, ō_{<t}), F_t(x̄′, ō_{<t})}.

*Corollary (⇐):* if (G_t, ō_{<t}) determines F_t(x̄_t, ō_{<t}) on W_t, then Ver^loc_t holds.

*Proof:* this is F2-1 applied to the input (x̄_t, ō_{<t}). The ō_{<t} part is visible and trusted under OF, so equal views force it to be equal. ∎

*Without OF:* if the log could misreport past outputs, the rule's own input ō_{<t} is untrusted. Ver^loc then needs ō_{<t} attested separately, or the logged ō_{<t} is merely the system's claim. [ANALYTIC ARGUMENT]

**Proposition F3-2 (local ⇏ global).** [EXPLICIT COUNTEREXAMPLE; K5a]

*Setting:* F₁ = x₁, F₂ = o₁ ("repeat the previous decision"), binary, unconstrained W, no evidence.

*Result:*
- c^loc₂ = [o₂ = o₁] is determined by the o-log alone.
- c^glob₂ = [o₂ = x₁] is not.
- **Global verification at step 2 needs evidence about x₁, although rule F₂ reads no raw input.**

**Proposition F3-3 (global ⇏ local).** [EXPLICIT COUNTEREXAMPLE; K5b]

*Setting:* x₁ = 0 fixed, x₂ = (a, b), F₁ = x₁, F₂ = (a if o₁ = 0, else b). Evidence: e₁ = x₁, e₂ = a.

*Result:*
- d\*₂ = a, so c^glob₂ is determined.
- In worlds with o₁ = 1 (step 1 faulty), F₂ = b is not determined. So c^loc₂ is not verifiable.

**Theorem F3-4 (minimal evidence for global correctness).** [PROVED THEOREM]

With unconstrained o (full product), Ver^glob_t ⇔ G_t determines d\*_t on X_{W,t}.
- *Proof:* d\*_t is a function of x̄_t alone, so this is V2/F2-2 with F := d\*_t.
- *Remark:* d\*_t can depend on **all** earlier raw inputs. Global verifiability therefore generally needs evidence for the whole input history that influences d\*_t, not just for F_t's direct inputs.

**Theorem F3-5 (evidence that may read outputs: a unique minimum).** [PROVED THEOREM; K5c]

Drop assumption (b): attested evidence may depend on (x̄, ō), e.g. an attester who saw the output. Consider any view containing the o-log.
- (i) A view V is verifiable ⇔ V determines (c_t)_t on W (definition).
- (ii) The view (ō, c̄), i.e. the o-log plus an **attested correctness-bit log**, is verifiable, and it is ⊑-coarsest among verifiable views containing ō. It is the **unique** maximum up to recoding.
- (iii) (ō, c̄) ⊑ (ō, d̄), the o-log plus attested decisions (V4). The inequality is **strict** when W contains two worlds with the same faulty output o but different correct decisions d (possible only if the decision range has ≥ 3 values). With binary ranges they are ⊑-equivalent, since d = o when c = 1 and d = 1 − o when c = 0.

*Proof:*
- (ii) Any verifiable V determines ō (it contains it) and c̄, so it factors through (ō, c̄) by Lemma 1 of O-10a. And (ō, c̄) is verifiable trivially.
- (iii) c = [o = d] is a function of (o, d). For strictness, with V = {0,1,2}, F = id and unconstrained W: (x=1, o=0) and (x=2, o=0) both give c = 0 but different d. ∎

**Consequence:**
- Allowing evidence to read outputs **restores a unique minimum**, unlike V3, where raw x-projections have incomparable minima.
- But the minimum is an **attestation of correctness**. It moves all trust to the attester and supports neither recovery of d nor party contestation at level (ii) unless p trusts that attester (F1-2).
- V4's "(o-log, attested d-log) is the unique minimum of that form" is consistent with this: (ō, c̄) is of a different, coarser form.

### 3.3 Limitations and architectural implication

*Limitations:*
- Deterministic rules only.
- The joint fault model was taken as given. How faults propagate through F_t's dependence on ō was not modelled beyond F3-2 and F3-3.
- Multi-step necessity for global correctness under a constrained W: not analysed beyond F2-6, which applies with F := d\*_t.

*Architectural implication (ARCHITECTURAL PROPOSAL):*
- For output-reading rules, the record must state **which correctness notion** is audited.
- Global audit needs evidence for the raw-input history that feeds d\*.
- Local audit needs a trusted o-log, but not that history.
- **STAKEHOLDER-POLICY CHOICE:** local vs global, per decision type.

---

## 4. O-10f4 / O-10i1 — Verifying superseded decisions

### 4.1 Model

[DEFINITION] Bitemporal, extending O-10i.
- **Evidence:** an evidence item is (subject, valid-time t, value, recording time u). E_{u}|t is the evidence about time t **as known at u**, the latest recorded value per item by u. The **historical snapshot** is E_t|t.
- **Rule versions:** v_u(t) is the version that, **as known at u**, governs decisions for time t. The version in force at t, as known at t, is v_t(t).
- **Decisions:** D(h_u, t), as in O-10i.

**Four correction models.** Two independent axes: are later evidence corrections about t applied, and are later rule changes applied to t?

| Model | G_u(t), the value D(h_u, t) should have |
|---|---|
| M00: frozen | F_{v_t(t)}(E_t\|t), for all u |
| M10: evidence-retroactive only | F_{v_t(t)}(E_u\|t) |
| M01: rule-retroactive only | F_{v_u(t)}(E_t\|t) |
| M11: fully retroactive | F_{v_u(t)}(E_u\|t) |

**Choosing the model is a STAKEHOLDER-POLICY CHOICE** (O-10i B4, refined into two axes).

**Three distinct verification questions about decision t, at audit time τ ≥ u.** [DEFINITION]

| Question | Property | Meaning |
|---|---|---|
| **HV**: historical verification | c^hist_t = [D(h_t, t) = F_{v_t(t)}(E_t\|t)] | was the decision correctly made, given what was known and in force then? The **non-hindsight** standard |
| **CV**: current-authoritative verification | c^cur_{u,t} = [D(h_u, t) = G_u(t)] | under the correction model |
| **SJ**: supersession justification | for a correction at u, whether G_u(t) ≠ G_{u−1}(t), and its recorded reason (new evidence or new rule) is true | was this correction legitimate? |

A fourth question, **hindsight correctness** [D(h_t, t) = F_v(E_u|t)], is a different question ("did it turn out right?"). **It must not be confused with HV.**

### 4.2 Results

**Theorem F4-1 (reduction).** [PROVED THEOREM]

Each of HV, CV and SJ is verifiability, in the sense of §2, of a correctness bit whose "input" is a snapshot:

| Question | Snapshot |
|---|---|
| HV | (E_t\|t, v_t(t)) |
| CV | the model's (E\|t, v(t)) at u |
| SJ | both the u − 1 and the u snapshots |

So F2-1 and F2-2 apply verbatim:
- the record at τ suffices **if** its trusted part determines the relevant F_v(snapshot);
- it is **exactly** sufficient iff it separates every confusable pair of snapshots.

*Proof:* substitution. The decision log is trusted under OF, extended to the bitemporal decision log of O-10i B1/B3. ∎

**Proposition F4-2 (current-evidence-only stores cannot verify historical decisions).** [EXPLICIT COUNTEREXAMPLE; K7a]

*Setting:* F = id, and the store keeps only E_now|t, i.e. evidence is overwritten.

*Worlds:*
- w₁: recorded x = 0 at t, decision 0 (correct then), later corrected to x = 1.
- w₂: recorded x = 1 at t, decision 0 (wrong), never corrected.

*Result:*
- Both give the current-only view (x = 1, D(h_t, t) = 0), with c^hist = 1 and 0 respectively.
- With the bitemporal evidence view, HV is determined.

**Corollary F4-3 (sufficiency of a bitemporal evidence log).** [PROVED, from O-10i B3 applied to evidence as a vector-valued "decision"]
- An append-only evidence log with recording times (or B3's difference log) determines E_u|t for every u ≥ t. HV, CV under every model, and SJ are then verifiable, **provided** the evidence is attested and determines F_v (F2-1a).
- **Verifying superseded decisions needs bitemporal evidence, not just bitemporal decisions.** B1/B3 make decisions bitemporal; F4-2 shows that is not enough.

**Proposition F4-4 (rule versions must also be bitemporal).** [EXPLICIT COUNTEREXAMPLE; K7b]

*Setting:* v1: F = id; v2: F = not. A retroactive change at u sets v_u(t) = 2, while v_t(t) = 1. Decision D(h_t, t) = 0, with x = 0.

*Result:*
- A store holding only the current mapping, v_now(t) = 2, cannot distinguish "correct under v1, then retroactively changed" from "decided under v2 and wrong" (the world with v_t(t) = 2).
- Recording **the version cited with each decision entry** (O-10f §4, O-10i §3 item 2) fixes HV, provided three conditions hold:
  - the tag is attested;
  - versions are immutable and published;
  - the version's effective-date registry is itself bitemporal, for level (iii) of §1.

**Proposition F4-5 (non-retroactive models simplify, but do not remove, the requirement).** [ANALYTIC ARGUMENT]
- In M00 and M10, v_u(t) = v_t(t) for all u, so the version mapping need not be bitemporal.
- But a **correction of the system's own error** (it applied the wrong version, or a wrong evidence value) is still a supersession in every model. It is distinct from a rule change, and SJ for it needs the old and new snapshots.
- In M00, evidence corrections do not change G, so E_u|t is irrelevant to CV. HV still needs E_t|t.

**Theorem F4-6 (expired or lawfully deleted evidence).** [PROVED THEOREM for (a)–(c); ANALYTIC ARGUMENT for (d)–(e)]

Deletion of part of the evidence at δ replaces the view V by V′ = π∘V, a coarsening, for all τ ≥ δ.
- **(a) What stays independently verifiable:** exactly the questions whose correctness bit is still determined by V′ on Ω_T, by F2-1 applied to V′. In particular:
  - (i) reconstruction (level (i)), if the decision log and the version tags are retained;
  - (ii) procedural basis (level (iii)), if the version-registry and authority records are retained;
  - (iii) correctness, **iff** the retained evidence still separates all confusable snapshot pairs. One sufficient way: retain one **minimal verifiable evidence set** (V3) and delete the rest.
- **(b) What becomes attestation-only:** if V′ no longer determines c, then:
  - c is verifiable after δ only under an enlarged trust set T ∪ {A_c}, where A_c is an attestation of c (or of d, V4), recorded **before** δ by an attester the auditor trusts. That attestation is the "verified-at-time-u_v by Z" record;
  - otherwise c is unverifiable.
  This is F3-5(ii) applied post-deletion. *Proof:* V′ fails to determine c on Ω_T; adding a truthful attestation of c to the trust set makes Ω_{T∪{A_c}} ⊆ {w : A_c(w) = c(w)}, on which (V′, A_c) determines c. ∎
- **(c) Retroactive re-evaluation after deletion:**
  - Under M01 or M11, a rule change at u > δ requires computing F_{v_u(t)} on deleted evidence. This is **possible only if** the retained data determines F_{v_u(t)} (cf. O-10a Prop. 6). Otherwise the retroactive decision **cannot even be made**, let alone verified.
  - *Proof:* the decision is a function of the retained data iff ker(retained) ⊆ ker F_{v_u(t)}, by O-10a Lemma 1. ∎
  - *Implication:* **retroactivity policies and deletion policies are not independent.** This is a STAKEHOLDER-POLICY CHOICE with a mathematical constraint.
- **(d) Commitments:** retaining a hash commitment H(ε) without ε gives no recomputation ability. If a party later presents a candidate ε′, then H(ε′) = H(ε) shows ε′ = ε, **under a computational binding assumption**. So verifiability becomes conditional on two things:
  - someone retaining ε, e.g. the data subject (stakeholder or legal);
  - a cryptographic assumption.
  Perfectly-hiding commitments carry no information by themselves (V6). Retaining a hash of personal data may itself be restricted; that is a legal question, outside mathematics.
- **(e) Attestation expiry:**
  - Verifying at τ an attestation made at t needs the attester's key and authority status **as of t**. That is again a bitemporal registry, the same structure as F4-4.
  - If the key status history is lost, earlier attestations degrade to **unverifiable claims**.

**What remains when evidence is deleted.** A tabulation, under the assumption that the decision log, the version tags and the registries are retained:

| Question | After deletion of the evidence for t |
|---|---|
| Reconstruction (o, cited version) | verifiable |
| Procedural basis (version applicability, authority) | verifiable if the registries are retained (bitemporally) |
| HV / CV correctness | verifiable iff the retained evidence separates confusable snapshot pairs; otherwise attestation-only (if attested before deletion) or unverifiable |
| SJ for corrections after δ | the same as CV; the old-snapshot half is attestation-only |
| Retroactive re-evaluation after δ | **infeasible** unless the retained data determines the new rule |

### 4.3 Limitations and architectural implication

*Limitations:*
- Deletion is modelled as payload redaction with the entry structure kept (a tombstone). O-10i's append-only log otherwise conflicts with deletion. Whether a tombstone itself is lawful is outside mathematics.
- Concurrent corrections (B5) were not combined with verification.
- Correction horizons (O-10i2) were not analysed.

*Architectural implication (ARCHITECTURAL PROPOSAL):*
- Evidence stores must be **bitemporal**: append-only, with recording times. Version and effective-date registries and attester-status registries must also be bitemporal, at least in rule-retroactive models.
- Every decision entry carries an attested version tag.
- Before scheduled deletion of the evidence for a critical decision, either retain a minimal verifiable evidence set, or record a trusted verification attestation. Otherwise post-deletion verification is impossible, and retroactive re-evaluation becomes infeasible.
- **STAKEHOLDER-POLICY CHOICES:**
  - the correction model (M00 / M10 / M01 / M11) per decision type;
  - retention periods;
  - who may attest verification;
  - whether hash retention is permitted.

---

## 5. Summary of claims

| ID | Claim | Label |
|---|---|---|
| Def 1.1–1.5 | party profile; epistemic and demonstrative establishment; four contest levels | DEFINITION |
| F1-1 | dominance ⇒ party contestability implies auditor verifiability | PROVED THEOREM |
| F1-2, F1-3 | strictness via distrusted attestation, and via a redacted field | EXPLICIT COUNTEREXAMPLE + FINITE CHECK |
| F1-4 | without dominance (private knowledge): incomparable; V5's premise is incomplete | EXPLICIT COUNTEREXAMPLE + FINITE CHECK |
| F1-5 | levels (i)–(iii) are independent; level (iv) is outside mathematics | EXPLICIT COUNTEREXAMPLES (analytic) |
| F2-1 | Ver ⇔ ker e ∩ K_W = ∅ | PROVED THEOREM + FINITE CHECK |
| F2-1a | (⇐) holds for every fault model | PROVED |
| F2-2 | V2's equivalence holds for all e ⇔ (Conf) | PROVED THEOREM + FINITE CHECK |
| F2-3 | {F, F+2} counterexample; needs F's range to avoid differences of 2 (correction to the report's wording) | EXPLICIT COUNTEREXAMPLE + FINITE CHECK |
| F2-6, F2-7 | multi-step characterization; per-step necessity | PROVED THEOREM + FINITE CHECK; joint necessity **OPEN** |
| F2-8 | joint fault model: past outputs leak correctness; per-step marginals over-demand | EXPLICIT COUNTEREXAMPLE + FINITE CHECK |
| F3-1 | local correctness reduces to F2, given OF | PROVED THEOREM |
| F3-2, F3-3 | local ⇏ global, and global ⇏ local | EXPLICIT COUNTEREXAMPLE + FINITE CHECK |
| F3-4 | global ⇔ evidence determines d\* (unconstrained o) | PROVED THEOREM |
| F3-5 | output-reading evidence: (ō, c̄) is the unique coarsest verifiable view; strictly coarser than (ō, d̄) for ranges ≥ 3 | PROVED THEOREM + FINITE CHECK |
| F4-1 | HV / CV / SJ reduce to §2 with snapshots as inputs | PROVED THEOREM |
| F4-2, F4-4 | current-only evidence, or a current-only version mapping, cannot verify historical decisions | EXPLICIT COUNTEREXAMPLE + FINITE CHECK |
| F4-3 | a bitemporal evidence log suffices | PROVED (via B3) |
| F4-5 | non-retroactive models: what simplifies | ANALYTIC ARGUMENT |
| F4-6 (a)–(c) | after deletion: what stays verifiable, what becomes attestation-only, retroactive infeasibility | PROVED THEOREM |
| F4-6 (d)–(e) | commitments; attestation expiry | ANALYTIC ARGUMENT (cryptographic assumptions not proved) |
| Correction models, J's trust set, retention, local vs global | — | STAKEHOLDER-POLICY CHOICE |

**Not established:**
- joint multi-step necessity (F2-7);
- non-trivial Op_p-restricted contestability instances;
- any probabilistic or randomized record;
- combination with concurrent corrections (B5);
- legal adequacy of any notion here.
