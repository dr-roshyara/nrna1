# Chapter 7. Records and recoverability

*Status: INTERNALLY REVIEWED (same model family; refute-first review 2026-10-18 found an order-direction error in Thm 7.3, a misplaced limit in Thm 7.6 and unsatisfiable instances in Thm 7.8, all corrected). Not externally reviewed.*

**Research question (RQ5).** What must a system **write down** so that every past decision can later be **recovered**: shown to have been the decision taken at that time? What is the least such record?

**Plain meaning.**
- The smallest log from which every past decision can be read back is **the log of the decision outcomes themselves**. It is unique up to its information content (its kernel).
- This is shown under the assumption that the decisions are fixed in advance and never revised later.
- A fixed per-field recording rule is never better than the outcome log, and **can** be worse.
- "Smallest" means least information, not least storage (§7.6).
- A log minimized for today's decisions may not answer a decision introduced later.
- If a privacy rule of the "coarser is always allowed" kind can be met at all, the outcome log meets it.
- *Important limit:* recovering **what** was decided is **not** checking **whether it was right**. That is Ch. 8.

## 7.1 The recoverability requirement

**ASM A1.** The decision set 𝔇 is:
- (i) fixed in advance;
- (ii) computable online, i.e. 𝔇(h) is known when h has occurred. This is a modelling assumption for implementation; no proof below uses it;
- (iii) never revised for a past h. Ch. 11 relaxes (iii).

**DEF 7.1 (recoverability).** An online record r (DEF 2.11) satisfies **(P)** iff ker r ⊆ ⋂_{d∈𝔇} ker d̂, restricted to L: the record at each time determines every decision value at that time.

*Remark.* Records are append-only (Prop. 2.13), so the record at a later time also determines all earlier decisions.

## 7.2 The minimal recoverable record

**DEF 7.2.** On L, define h ≡\* h′ ⇔ |h| = |h′| and 𝔇(h_t) = 𝔇(h′_t) for every t ≤ |h|. The **outcome log** is r\*(h) := ⟨𝔇(h₀), 𝔇(h₁), …, 𝔇(h)⟩.

**Theorem 7.3 (the outcome log is the greatest recoverable record). THM.**
- ker r\* = ≡\*.
- ≡\* ∈ PC(L) and satisfies (P).
- Every π ∈ PC(L) satisfying (P) has π ⊆ ≡\*.

So, among online records satisfying (P), ker r\* is the **⊆-greatest** kernel. Equivalently (Lemma 2.6), **r\* ⊑ r for every online record r satisfying (P)**: r\* holds the least information. It is unique up to kernel.

*Proof.*
- **r\* is online:** its entry at step ha is ρ(h, a) = 𝔇(ha), with initial entry 𝔇(ε).
- **ker r\* = ≡\*:** equal logs have equal lengths and equal entries. That is exactly the definition of ≡\*.
- **≡\* ∈ PC(L):** it is length-preserving by definition. If h ≡\* h′, every prefix pair agrees on 𝔇 at every shorter prefix, so the prefixes are ≡\*-related.
- **(P):** take t = |h|.
- **Greatest:** let π ∈ PC(L) satisfy (P), and let (h, h′) ∈ π. Then |h| = |h′|, and (h_t, h′_t) ∈ π for every t by prefix-closure. By (P), 𝔇(h_t) = 𝔇(h′_t). So h ≡\* h′. ∎

*Remark.* "Unique up to kernel" means: any two ⊑-least records have the same kernel. Their **entries** need not be functions of each other entry by entry; the correspondence may depend on the past (Prop. 2.13).

**Evidence (FIN; finite computation).**
- Beyond the proof, ≡\*'s greatest-kernel property was checked **exhaustively** by two independently written enumerators and a third method, for Σ = {0,1}, histories of length ≤ 3, and **all 2¹⁵ binary single-decision tables**. Only 2¹⁴ of them are effectively distinct, because the value at ε never affects the relations compared.
- The Ch. 7 reviewer's own enumerator reproduced the property on further random decision sets and random L.
- Records: `../synthetic-checks/o10-independent/`. This pointer was not opened by the Ch. 7 reviewer.

**Corollary 7.4 (resumable records). THM.** Suppose the record must also allow the system to **resume**, i.e. determine every future decision: ker r ⊆ ∼ on L. Then the greatest such online record is the log of (𝔇(h_t), [h_t]_∼) for t ≤ |h|.

*Proof.* Apply Theorem 7.3's argument to the equivalence ⋂ker d̂ ∩ ∼ = ∼ (by Prop. 3.2(c)). The greatest PC relation inside an equivalence E is "same length, and every prefix pair E-related". ∎

*Remark.* Since ∼ ⊆ ⋂ker d̂, the log of [h_t]_∼ alone already suffices; listing 𝔇 is redundant.

## 7.3 Fixed per-input recording (data minimization by field)

**DEF 7.5.**
- Define a ≈ b iff d̂(haw) = d̂(hbw) for all h, w ∈ Σ\* and all d.
- A **fixed letter-wise record** applies one map φ : Σ → Σ′ to every input. As an online record (DEF 2.11) it is r_φ(a₁…a_n) := ⟨∗, φ(a₁), …, φ(a_n)⟩, where ∗ is a fixed initial entry.

**Theorem 7.6. THM.**
- (a) ≈ is an equivalence, and r_{[·]≈} satisfies (P).
- (b) **If L = Σ\***, ≈ is the **greatest** fixed letter-wise abstraction satisfying (P).
- (c) ker r_{[·]≈} ⊆ ≡\*, and the inclusion can be strict.

*Proof.*
- **(a)** (Any L.)
  - *Transitivity:* d̂(haw) = d̂(hbw) = d̂(hcw).
  - *(P):* take two legal histories of equal length that agree classwise at every position. Change one position at a time. Each step uses the definition of ≈ (which quantifies over Σ\* with d̂, ⊥ on illegal histories), with the already-changed prefix as h. So d̂ agrees at the two endpoints. Intermediate histories may be illegal; this does no harm, because ≈ is defined on Σ\*.
- **(b)** (L = Σ\*.) If φ satisfies (P) and φ(a) = φ(b), then r_φ(haw) = r_φ(hbw) for all h, w. Since every such history is legal, (P) gives d̂(haw) = d̂(hbw). Hence a ≈ b.
- **(c)** r_{[·]≈} is an online record satisfying (P), so Theorem 7.3 gives the inclusion.
- *Strictness CE:* d = the first input. Here ≈ is the identity, while r\* records the first input and then a constant. ∎

*Limits.*
- **(b) needs L = Σ\*.** If hbw ∉ L, (P) on L gives no constraint.
  - *CE:* Σ = {0,1}, L = {ε, 0}, 𝔇 = {λ}.
  - A constant φ satisfies (P) on L, but 0 ≉ 1, because λ(0) ≠ λ(1).
- **≈ is not optimal once records may depend on position or history** (the CE in (c)).

## 7.4 Field-subset records

**CE 7.7 (records restricted to raw fields: incomparable minima).**
- *Setting:* single-step histories over letters a = (0,0), b = (0,1), c = (1,2), e = (1,3), f = (2,2), with fields (x, y), and d = [x ≥ 1].
- *Minimal field sets:* both {x} and {y} determine d, and both are minimal; ∅ does not determine d.
- *Incomparable:* ker x = {a,b} | {c,e} | {f}, while ker y = {a} | {b} | {c,f} | {e}.
- *Join not realized:* their join is ker d = {a,b} | {c,e,f}, which no field subset realizes.
- *Contrast:* if a derived field holding d may be stored, its projection is r\*, and the problem disappears.
- *And:* fields that carry equal information (u ≡ v) give equal kernels. They are different **representations**, not different information.

## 7.5 Privacy and future decisions

**Theorem 7.8 (privacy feasibility reduces to the outcome log). THM.**
- *Setting:* let Π be a predicate that depends **only on the kernel**, not on the representation, and is **upward-closed** (π satisfies Π and π ⊆ π′ imply π′ satisfies Π).
- *Claim:* a (P)-record satisfying Π exists iff r\* satisfies Π.

*Proof.*
- **(⇐)** r\* itself is such a record.
- **(⇒)** If a (P)-record with kernel π satisfies Π, then π ⊆ ≡\* by Theorem 7.3, and upward closure gives that ≡\* satisfies Π. ∎

*Instances:*
- **"the record does not determine attribute s"** (some class mixes s-values). If a class mixes s-values, so does every coarser class containing it.
- **k-anonymity on histories of a stated length n:** every class of length-n histories has at least k members.
  - *Caution:* the unrestricted form ("every class has at least k histories") is **never** satisfiable for k ≥ 2. Every PC kernel has {ε} as a singleton class.
  - History-counted k-anonymity is also not person-level anonymity.

*Not an instance:* differential privacy concerns randomized mechanisms and lies outside this deterministic model.

**Proposition 7.9 (no retroactive minimization). THM.** If 𝔇 ⊆ 𝔇′, then ≡\*_{𝔇′} ⊆ ≡\*_𝔇. Moreover, r\*_𝔇 satisfies (P) for 𝔇′ iff ≡\*_𝔇 ⊆ ⋂_{d∈𝔇′} ker d̂.

*Proof.*
- The inclusion holds because, for a fixed L, more decisions mean more equalities required.
- The iff is DEF 7.1 applied to 𝔇′. Equivalently, ≡\*_𝔇 = ≡\*_{𝔇′}. ∎

**CE.** 𝔇 = {λ} with L = Σ\* = {0,1}\*, and 𝔇′ = 𝔇 ∪ {first input}. r\*_𝔇 records only constants, so it cannot recover the first input.

**Plain meaning.** A record minimized for today's decisions **may not** answer a decision added tomorrow. **How much future-proofing to buy is a stakeholder decision (STAKE).**

## 7.6 Limitations

- **A1(iii) (no revision)** is strong. Ch. 11 treats revisions.
- **Recoverability is not verifiability.** r\* restates decisions but does not show they were right. Ch. 8 shows when, and with what evidence, checking is possible.
- **Storage.** "Less information" does not mean "less storage" (Ch. 13).

## 7.7 Traceability (ARCH)

| Result | Engineering consequence |
|---|---|
| Thm 7.3 | the information-minimal (not storage-minimal; §7.6) log for recovery is the decision-outcome log. *Forward-looking ARCH:* add the rule version, which is not yet in the model (Ch. 8) |
| Cor. 7.4 | to resume from the log alone, log the kernel state |
| Thm 7.6 / CE 7.7 | fixed per-field minimization is suboptimal; specify records by **what must be recoverable**, not as field lists |
| Thm 7.8 | a privacy check needs to test only the outcome log, for upward-closed rules |
| Prop. 7.9 | changing 𝔇 needs a migration plan; minimized logs may not support new decisions (STAKE) |
| Regression tests | the CE in Thm 7.6(c); CE 7.7 |
