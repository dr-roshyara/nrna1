# Theory 02 — **The Three-Question Separation**

**Document 02 of 15** · 2026-09-04 · **This is the load-bearing result of the programme.**

---

## 1. The statement

> ### `[EXP]` Eliminability, Preservation and Realization are three distinct questions. None implies another.

$$
\underbrace{\Pi(T(D)) = \Pi(T(E_S(D)))}_{\text{Zero — can I remove it?}}
\;\;\neq\;\;
\underbrace{\hat H(Q \mid T(D)) = 0}_{\text{Adequacy — is the meaning there?}}
\;\;\neq\;\;
\underbrace{O(T(D)) = Q(D)}_{\text{Realization — can the consumer recover it?}}
$$

**Status is `[EXP]`, deliberately, and not `[THM]`.** We have no proof that these must differ
in general. We have demonstration that they *do* differ in every system tested, by independent
mechanisms. Promoting this to a theorem would require proving the separations are necessary —
which nobody has done, and which the epistemic-status rule exists to prevent us from doing by
accident.

---

## 2. Why this matters in business language

Take a customer record:

```
Customer = Müller · Order = €1,000 · Channel = Online · Time = 10:42 · Internal ID = 847291
```

and the business question **"which channel generated the highest revenue?"**

- **Eliminability** asks: *can I drop the timestamp without changing what I observe?*
- **Preservation** asks: *is the channel-revenue answer still determined by what remains?*
- **Realization** asks: *can the downstream report actually compute it from what remains?*

The programme's finding is that **these three come apart**, and that a system which answers
one and assumes the others is wrong in a way that is invisible until it matters.

---

## 3. The three separations, each with its evidence

### 3.1 `[NEG]` Eliminability does **not** predict Preservation

Three experiments, increasing power, same answer.

| experiment | design | result |
|---|---|---|
| **KR-REP-REDUCTION** `H-RR2` | Zero rate at the boundary crossing vs. a non-crossing step | Zero rate `0.0377` at the crossing `R5→R4` vs `0.0426` at the non-crossing `R4→R3` — **not distinguishable**; Zero vanishes entirely at `R3` |
| **KR-BRIDGE-01** | one $\Pi$, one $Q$, 7 transformations, 25 000 cases × 2 splits | marginal `RD = −0.363`, **fully accounted for by redundancy as a common cause** |
| **KR-BRIDGE-02** | 5 $\Pi$ × 5 $Q$ × 7 $T$, gated | **H1 not refuted**; the one substantial candidate was an **artifact of shared reads** |
| **KR-BRIDGE-03** | same, at **13× the informative strata** | **H1 not refuted**; surviving cells 14 → 1, substantive **0** |

Document 07 carries the full programme. The negative is stable under a 13-fold increase in
power, which is the strongest form this evidence can currently take.

### 3.2 `[EXP]` Preservation does **not** give Realization

From KR-REP-REDUCTION §3, and this one is subtle enough that the *design* failed to see it
first:

$$\hat H(Q \mid R_5) = 0 \quad\text{(adequate)} \qquad\text{but}\qquad O(R_5) = Q(D) \ \text{is a separate fact}$$

`[EXP]` Adequacy says *the information is present in the fiber*. Realization says *this
particular decoder gets it out*. A representation can determine $Q$ information-theoretically
while the fixed decoder $O$ fails on it — because $O$ was fixed in advance and is not
permitted to be re-fitted.

> `[DEFECT]` **The KR-REP-REDUCTION design failed to operationalize this separation.** Its
> chain never dropped a record or a source, so the constraint $C$ could never fail, and the
> only adequate level had $F = 1.0000$. **The separation is real; that experiment could not
> exhibit it.** Recorded as a design defect rather than as evidence against the separation.

### 3.3 `[EXP]` Eliminability does **not** give Realization

Follows from 3.1 and 3.2 having independent mechanisms — but is **not** a `[COR]`, because
"not implied via A and not implied via B" is not a derivation. It is separately observed:
`Zero` is defined by $\Pi$, which is read-disjoint from $Q$ **by construction**, so `Zero`
carries no information about $Q$'s recoverability by design. Stated plainly: *we built them
to be independent, and then verified the independence held.*

---

## 4. What the separation buys

`[EXP]` **The distinction is decision-relevant, not taxonomic.**

| A system that conflates | fails by |
|---|---|
| Eliminability with Preservation | deleting fields that were "safe to remove" and losing the answer |
| Preservation with Realization | certifying a representation as adequate that the consumer cannot read |
| Eliminability with Realization | both, silently |

The three predicates need **three separate gates** in any implementation. Document 12 places
those gates in the DDD model.

---

## 5. What is still open here

| Question | Status |
|---|---|
| Must the three differ in general, or only in the tested carriers? | `[OPEN]` — this is the gap between `[EXP]` and `[THM]` |
| Is there an ordering among them (e.g. Realization ⟹ Adequacy)? | `[CONJ]` **Realization ⟹ Adequacy**: if a fixed decoder recovers $Q$ from $R$, then $R$ determines $Q$ on the realized cases. *Refutable by* a case where $O(T(D)) = Q(D)$ holds on every observed case while the fiber is $Q$-heterogeneous — i.e. the decoder is right by luck. **Not tested.** |
| Do the separations survive a non-synthetic carrier? | `[OPEN]` — the central transfer problem |
