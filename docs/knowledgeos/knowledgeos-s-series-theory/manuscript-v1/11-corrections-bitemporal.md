# Chapter 11. Corrections, supersession and bitemporal history

*Status: INTERNALLY REVIEWED (same model family; refute-first review 2026-10-18 with independent checks; Prop. 11.6, Prop. 11.8 (downgraded to ANA), Prop. 11.10 and DEF 11.6a corrected). Not externally reviewed. Late additions not re-reviewed: Prop. 11.6 (commuting-actions form), DEF 11.6a refinements.*

**Research question (RQ8).** Decisions get corrected: new evidence arrives, input data are fixed, rules change. What must a record keep so that **both** the original decision and the current authoritative one can be recovered and checked? What does it cost to limit how far back corrections may reach?

**Plain meaning.**
- Keep two time axes: **when** a decision is about, and **as of when** it is held.
- The minimal record then extends Ch. 7: log, at each step, the current view of **all** past decisions. Equivalently, log only what **changed**.
- Logging only "current decisions" loses corrected history.
- Checking a historical decision needs the evidence and rule version **as they were then**, so evidence stores must also be two-timeline when historical checks are required.
- Concurrent corrections need either a recorded order, or a merge rule whose result does not depend on order.
- A **correction horizon** (corrections allowed only up to k steps back) keeps each log entry bounded in size. Corrections that arrive later become impossible.
- Whether rule changes re-evaluate the past (**retroactivity**), the horizon k, and the merge rule are **STAKE** decisions.

## 11.1 Model

**DEF 11.1 (bitemporal decisions).** For a history h with prefixes h_u, the **bitemporal decision** D(h_u, t), for t ≤ u, is the decision **about time t, as held at time u**. It yields four objects:
- the **historical decision**: D(h_t, t), what was decided at the time;
- a **correction** at u: D(h_u, t) ≠ D(h_{u−1}, t), for some t < u;
- the **current authoritative decision** for t: D(h_{|h|}, t);
- **supersession**: the corrected value replaces the old one's authority; the old value **remains** in the history.

Evidence, data corrections and rule changes enter as input letters (IC). Like every decision, D is extended by ⊥ off L (DEF 2.3).

**DEF 11.2.** **(P_bi):** the record at u determines D(h_u, t) for every t ≤ u.

## 11.2 The minimal bitemporal record

**Theorem 11.3. THM.**
- *Claim:* h ≡\*_bi h′ ⇔ |h| = |h′| ∧ D(h_u, t) = D(h′_u, t) for all t ≤ u ≤ |h| is the greatest kernel among online records satisfying (P_bi).
- *Realization:* the log whose entry at u is the vector (D(h_u, t))_{t≤u}.

*Proof.* Apply Theorem 7.3 to the vector-valued decision 𝔇_bi(h) := (D(h, t))_{t≤|h|}. Then (P_bi) is exactly (P) for 𝔇_bi. ∎

**CE 11.4 (a current-decisions-only log is not enough).**
- *Log:* the **current-decisions log** is ⟨D(h_u, u)⟩_u.
- *Setting:*
  - times start at 1; L consists of histories whose first input is "0" or "1";
  - inputs "1" and "0" observe a value, and "c" corrects (flips) the first observation;
  - D(h_u, 1) := the corrected first observation;
  - D(h_u, u) := the last observation, or the corrected first one if the last input is "c". This rule is chosen to make the logs collide, which is legitimate for a counterexample.
- *Pair:* "1c" and "10" have equal current-decision logs, (1, 0). But D(h₂, 1) is 0 for "1c" and 1 for "10".

**Proposition 11.5 (difference log). THM.**
- *Definition:* the **difference log** has, as its entry at u, the list of t < u with D(h_u, t) ≠ D(h_{u−1}, t), with their new values, plus D(h_u, u).
- *Claim:* it determines Theorem 11.3's log, and is determined by it.
- *Proof:* by induction on u, log against log.
  - Full → difference: each difference entry needs only two consecutive full entries.
  - Difference → full: each full entry needs the whole preceding prefix.
  - So the correspondence is between the logs, as functions of the whole prefix, not entry by entry. ∎

## 11.3 Concurrent corrections

**Setting (action reading).** Corrections act on the current value: each correction c gives a map M(·, c) : S → S, and a batch is applied in arrival order.

**Proposition 11.6. THM + CE.**
- *Claim:* the result of applying any finite batch from a set C of corrections, from any start value, is independent of arrival order iff the actions **commute pairwise**: M(M(s, c), c′) = M(M(s, c′), c) for all s and all c, c′ ∈ C.
- *Proof:*
  - (⇒) take batches of size 2.
  - (⇐) every reordering of a finite sequence is a composition of adjacent swaps, and pairwise commutation makes each swap harmless. ∎
- *Special case (fold reading):* if M is a binary operation on S and batches are folded as s₀ ⊕ c₁ ⊕ … with corrections drawn from S, then order-independence for batches of length up to 3 holds iff ⊕ is commutative and associative. This was checked over all 19,683 operations on a 3-element set by an internal reviewer.
- *Commutativity alone is not enough (fold reading):* floor-average on {0,1,2,3} is commutative but order-dependent on 30 of the 64 ordered triples.
- *Non-commutative operations can still commute as actions:* M(s, c) = s + 2c is neither commutative nor associative as a binary operation, yet its actions commute.
- *Arrival sets:* even with order independence, D(h_u, t) depends on **which** corrections had arrived by u. So the record must fix the **arrival set** at each u.

## 11.4 Verifying superseded decisions

**DEF 11.6a (version model; also used by Ch. 10, level iii).**
- **Rule versions:** a set of versions 𝒱, with a rule F_v for each v ∈ 𝒱.
- **Decision event:** each decision event carries its time t, its decision **type**, its **actor**, and its **cited version** v_cited.
- **True procedural facts** are **world components** (Ch. 10, DEF 10.1), and may vary between worlds:
  - eff : 𝒱 → sets of times, when each version is **in force**;
  - auth(actor, type, t), whether the actor **holds authority**.
- **Recorded procedural facts** are record components, which may be false in some worlds:
  - a recorded **registry** r_eff;
  - an **authority log** r_auth.

  Trusting them means r_eff = eff and r_auth = auth (DEF 10.2).
- **Bitemporality:** the registry may itself be corrected. If so, eff is bitemporal, like D (OPEN whether required).
- **The two procedural facts** (Ch. 10, level iii):
  - in force: [t ∈ eff(v_cited)];
  - authority: auth(actor, type, t).
- **Substantive correctness** under the cited version: [o = F_{v_cited}(x)].

**DEF 11.7 (correction models).** There are two independent axes:
- do later **evidence** corrections apply to t?
- do later **rule** changes apply to t (retroactivity)?

This gives four models. **Which model governs is STAKE.**

**Three verification questions** (each a Ch. 8 question on a snapshot):
- **(H) historical:** was D(h_t, t) correct under the evidence and rule version in force **at t**?
- **(C) current:** is D(h_u, t) correct under the model's evidence and rule at u?
- **(S) supersession:** was the change from D(h_{u−1}, t) to D(h_u, t) justified by the correction event?

"Hindsight correctness" (was the historical decision right given everything known later?) is a **fourth, distinct** question.

**Proposition 11.8 (reduction schema). ANA.**
- *Instantiation:* each of (H), (C) and (S) becomes a Ch. 8 question once it is instantiated as data (X, O, F, W, E). X is the snapshot's input, F the applicable rule version, and E the **totality of retained evidence**.
- *Criterion:* if the retained evidence is a function of the snapshot's input alone, the question is verifiable iff that evidence separates the snapshot's confusable pairs (Thm 8.6).
- *If the evidence is not such a function:* if retained evidence from other times carries information about the snapshot's input (for example, a logged correction preserving the old value), the criterion must be applied to that totality. Thm 8.6 does not apply directly.
- *Explicit instantiation of (C) and (S):* OPEN.

**CE 11.9 (current-only evidence cannot verify historical decisions).**
- *Setting:* F = id. The store keeps only the **current** x, with no correction log.
- *World A:* x_t = 0, o = 0, later corrected to x_t = 1.
- *World B:* x_t = 1, o = 0, never corrected.
- *Result:* both show the same view (o = 0, current x = 1). But (H) is correct in A and incorrect in B.

**Proposition 11.10. ANA (under conditions; it rests on Prop. 11.8, which is ANA).**
- **A bitemporal evidence log** (evidence as held at each u) suffices for (H), (C) and (S), **provided**:
  - each snapshot's retained evidence separates that snapshot's confusable pairs (e.g. it is determining);
  - the version and registry state are also logged bitemporally;
  - for (S), the correction event itself is retained.
- **Bitemporal decisions alone do not suffice:** they give recoverability, not verification (Prop. 8.10, CE 8.11).

## 11.5 Correction horizons

**DEF 11.11.** For a base process D, define the **truncation** D^k(h_u, t) := D(h_{min(u, t+k)}, t). D **has horizon k** iff D = D^k: decisions are frozen k steps after their time.
- A horizon can be a property of a process, or a policy that replaces D by D^k.
- Both are captured by the same definition, since every horizon-k process equals its own truncation.

**Theorem 11.12. THM.**
- *Claim:* under horizon k, the **window log** ⟨(D(h_u, t))_{max(0, u−k) ≤ t ≤ u}⟩_u has the kernel of Theorem 11.3. So it is the greatest record satisfying (P_bi), unique up to kernel.
- *Proof:* a frozen value D(h_u, t), with u > t + k, equals D(h_{t+k}, t), which appeared in the window at time t + k. ∎

**Proposition 11.13 (entry size; each statement names its encoding). THM.**

*Notation:* b := ⌈log₂ |V|⌉. The bounds hold for u ≥ k; for u < k the window is shorter.

| Encoding | Bits per entry (at most) |
|---|---|
| plain fixed width | (k+1)·b |
| fixed width over the realizable alphabet of m decision values | ⌈(k+1) log₂ m⌉ |
| difference list | ⌈log₂(k+1)⌉ + k(⌈log₂ k⌉ + b) + b (with k(⌈log₂ k⌉ + b) := 0 for k = 0) |

- None depends on u.
- **Checked:** in random horizon-k runs, at most k times changed per entry, all within [u − k, u − 1].
- **The difference-list worst case is never below the plain bound** (e.g. k = 2, b = 1: 7 vs 3 bits). Its advantage is only in typical, sparse cases.
- **A horizon is *a* sufficient way to bound entries, not the only one.** Restricting corrections to a **fixed finite** set of correctable times also bounds per-node cost. Otherwise offsets grow like log u.

**Proposition 11.14. THM.** For a fixed base D, ker(log of D^{k+1}) ⊆ ker(log of D^k): a smaller k gives a coarser-or-equal record.

*Proof.* Each value D^k(h_u, t) = D(h_{min(u, t+k)}, t) equals the value D^{k+1}(h_{u′}, t) at the prefix u′ = min(u, t + k). So the D^{k+1} log determines the D^k log. ∎

**What a horizon costs (consequence of the definition):** exactly the corrections at u > t + k, i.e. late evidence, deep retroactive rule changes and late appeal outcomes.

## 11.6 Limits and open problems

- **OPEN:** recording corrections that arrive after the horizon. This needs a different model, e.g. explicit "late correction" events with special authority.
- **OPEN:** correction horizons combined with deletion (Ch. 12).
- **Assumptions:** finite value sets for Prop. 11.13. Thm 11.12 needs no serialization assumption.

## 11.7 Traceability (ARCH; policies are STAKE)

| Result | Engineering consequence |
|---|---|
| Thm 11.3 / Prop. 11.5 | append-only logs whose entries carry (time concerned, value, supersedes-pointer); "current" is a query, never an overwrite |
| CE 11.4 | regression test: a correction must not lose the original value |
| Prop. 11.6 | declare a total order, or corrections whose actions commute pairwise, plus recorded arrival sets (action reading) |
| Prop. 11.8 / 11.10 | **if** historical verification or retroactive models are required: bitemporal evidence stores, versions and registries are **one sufficient design** (not proved necessary); entries carry rule version and evidence references; correction events are retained |
| Thm 11.12 / Prop. 11.13 | a horizon k bounds storage per entry; choosing k is STAKE |
