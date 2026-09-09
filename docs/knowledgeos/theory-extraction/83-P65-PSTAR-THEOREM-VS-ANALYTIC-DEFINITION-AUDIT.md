# `P-65` — `P*`: Theorem, Analytic Definition, or Conditional?

**2026-09-09 · Lane T.** After [`82` `P-64`](./82-P64-BREAKTHROUGH-CLAIM-ARCHAEOLOGY-AND-DERIVATION-IDENTITY-AUDIT.md).

# 1. Executive verdict

$$\boxed{\mathbf{[QUALIFIED]\ CONDITIONAL.}}$$

⭐⭐⭐ **`P*` is provable under exactly one added assumption — *recovery from the projection alone* — and
FALSE without it.** ⭐ Under that assumption its entire content is the **functionality of the recovery
map**: it is the contrapositive of the elementary factorization lemma, $d$ factors through $P$ iff
$\ker P \subseteq \ker d$. ⛔ **Not the programme's theorem, and not new.**

$$\boxed{\begin{array}{c}\textbf{⭐⭐⭐ And the consequence reverses } P\text{-}64\textbf{'s reading: } \mathbf{the\ kernel\ is\ an\ instance\ of\ }P^*\textbf{'s}\ \mathbf{FAILURE,\ not\ of\ }P^*.\\[4pt] \textbf{If unqualified } P^* \textbf{ were true, } \mathbf{retention\ would\ be\ futile\ and\ } K1\text{–}K11 \textbf{ would have no purpose.}\end{array}}$$

$$|K| = 11 \textbf{ — unchanged, } \mathbf{[REC]}. \textbf{ ⛔ Nothing promoted to } \mathbf{[THM]}.$$

# 2. Concession and disagreement

**2.1 ⭐ Conceded, verbatim.** `P-64`'s *"common-cause derivation"* overstated. Replaced by:
> **`[QUALIFIED]` independently reconstructed routes within a shared corpus; causal/information
> independence is `[UNRECORDABLE]`.**

**2.2 ⭐⭐ Disagreement — the commission's five categories are exhaustive but not exclusive, and `P*`
lands in two at once.** Under its qualifier `P*` is **`[THM]`-provable *and*` [ANALYTIC]`** — the proof
is three lines and every line is a definition unpacking. ⛔ **Forcing a single label would hide the
result.** I report **`[QUALIFIED] CONDITIONAL`** as the verdict *(the qualifier is load-bearing)* and
record the analytic character inside it *(§5)*.

⛔ **No `three_model_convergence` · no `K1`–`K11`/`|K|` change · no minimality · no canonicalization · no
programme selection · no `S3` adoption · no R→S transfer · no architecture/schema/governance · ⛔ no
promotion to `[THM]` without a proof.**
⚠️ **Pre-registered `[QUALIFIED] CONDITIONAL`.** ⭐ Correct — record 21 of 30.

# 3. Do the corpus's own definitions support the terms? *(§ formalize)*

⭐ **`EKS-21` discipline:** control `KnowledgeOS` = **1 815** files. Usage is abundant — `distinction`
**1 708** · `collapse` **960** · `projection` **773** · `recover` **358** · `well-defined` **163** ·
`quotient` **115** · `injectiv*` **60** · `factorization through` ⭐ **17 (verified by re-grep after an
extraction artifact reported a false zero — third consecutive audit in which that fired)**.

⛔ **Usage is not definition.** No corpus document defines *collapse* or *recover* for this proposition.
⭐ **So the formalization below is mine, stated so it can be disputed**, and it is the **weakest** one
that makes the sentence say anything.

# 4. ⭐⭐ Formal statement

Let $P : X \to Y$. A **distinction** is a map $d : X \to D$ with $|D| \ge 2$.

$$P \textbf{ COLLAPSES } d \textbf{ at } (x_1,x_2) \;:\iff\; P(x_1) = P(x_2) \;\wedge\; d(x_1) \neq d(x_2)$$

$$d \textbf{ is RECOVERABLE FROM } P \textbf{ ALONE} \;:\iff\; \exists\, r : Y \to D \textbf{ with } r \circ P = d$$

$$\boxed{P^{*}_{\text{alone}}: \quad P \textbf{ collapses } d \textbf{ at some pair} \;\Longrightarrow\; d \textbf{ is not recoverable from } P \textbf{ alone.}}$$

# 5. Proof — and what it actually uses *(§2, §3)*

**Proof.** Suppose $r \circ P = d$. Then
$$d(x_1) \;=\; r(P(x_1)) \;\overset{P(x_1)=P(x_2)}{=}\; r(P(x_2)) \;=\; d(x_2),$$
contradicting $d(x_1) \neq d(x_2)$. $\blacksquare$

⭐⭐⭐ **The only substantive step is the middle equality, and it holds because `r` is a FUNCTION** —
equal inputs give equal outputs. ⛔ **No property of knowledge, representation, persistence or
KnowledgeOS is used anywhere.**

$$\boxed{\textbf{⭐⭐ Standard form: } d \textbf{ factors through } P \iff \ker P \subseteq \ker d. \textbf{ } P^{*}_{\text{alone}} \textbf{ is its contrapositive.}}$$

⭐ **So it is a theorem — of elementary set theory, roughly 150 years old, and belonging to nobody
here.** ⚠️ **It is simultaneously analytic**: every line unpacks a definition. ⛔ **That is not a
criticism — a true, cheap, correctly-scoped lemma is worth more than an impressive unproved one.**

# 6. ⭐⭐⭐ Countermodels *(§4)*

| | model | result |
|---|---|---|
| **A** | deterministic $P$, $P(x_1)=P(x_2)$, $x_1 \neq x_2$ | ⭐ **`P*_alone` HOLDS** — §5 |
| ⭐⭐⭐ **B** | **side information.** Recovery from $(P(x), Z)$. Take $Z = d(x)$ and $r'(y,z) = z$; then $r'(P(x),d(x)) = d(x)$ | ⭐⭐⭐ **`P*` WITHOUT the qualifier is FALSE.** ⛔ **The word *"alone"* is not decoration — it carries the whole proposition** |
| **C** | non-deterministic $P$ (relation- or distribution-valued) | ⚠️ **`P*` does not even TYPE** — *collapse* was defined by equality of images, which a relation does not have. **Needs restating, not testing** |
| **D** | apparently-lossy but identity-preserving encoding | ⛔ **NOT a countermodel** — if the encoding separates $x_1,x_2$ it does not collapse $d$, so the **antecedent fails**. ⭐ Recorded because §4 asks, and because manufacturing it as a refutation would be dishonest |

$$\boxed{\textbf{⭐⭐ } \mathbf{B\ is\ decisive.} \textbf{ The natural-language sentence, as the corpus states it, is } \mathbf{REFUTED.} \textbf{ Only the qualified form survives.}}$$

# 7. ⭐⭐ Route table *(§5 of the commission)*

| route | actual target | derivation? | relationship to `P*` |
|---|---|---|---|
| **`A`** 09-02 restructuring | *"we tried to make a reduced representation answer questions only the richer structure could answer"* — over `Sat`·`Zero`·`Gap`·`Balanced`·`K_t`·argument field | ⭐ **DIAGNOSIS** — six instances, one pattern; ⛔ no proof attempted | ⭐⭐ **an instance-collection of `P*_alone`**, correctly scoped: it says *these* reductions lost *these* answers |
| **`B`** `Part XIX` | $P(K) \neq K$; $Recover(P(K)) \supseteq Dist_{EC}(K)$ | ⛔ **STIPULATION** — no premises, no in-edges *(`P-63`)* | ⭐⭐⭐ **the CONVERSE direction** — an **adequacy requirement**, which *presupposes* required distinctions **are** recoverable ⇒ ⛔ **it depends on unqualified `P*` being FALSE** |
| **`C`** `P-08` §17 | current-state-only is insufficient; two histories, one state, a required distinction | ⭐⭐ **DERIVATION** from a witnessed minimal pair, `[EMP]` | ⭐⭐⭐ **an instance of `P*_alone`** whose **conclusion** — *therefore retain prior value + warrant* — **also requires unqualified `P*` to be false** |

⭐⭐⭐ **Read the last column: two of the three routes only make sense because `P*` fails without its
qualifier.** ⛔ **They are not three derivations of one proposition. `A` instantiates it; `B` and `C`
rely on its negation-without-qualifier.**

$$\boxed{\textbf{⭐⭐ } P\text{-}64\textbf{'s "three routes to } P^*\textbf{" is } \mathbf{[REFUTED]}\textbf{: one route reaches it, two require its unqualified form to fail.}}$$

# 8. Proposition versus application *(§6)*

$$P^{*}_{\text{alone}} \;\nRightarrow\; \textbf{the eleven cells are necessary}$$

⭐ `P*_alone` says a collapsed distinction is unrecoverable **from the projection**. ⛔ **It says nothing
about WHICH distinctions must be preserved** — that is the contract question (`Dist_EC`, `C_KOS`), and
`P-55`/`P-59` established it is **undefined**. ⛔ **No minimality claim is derivable, and none is made.**

⭐⭐⭐ **The genuine consequence, which runs the other way:**

$$\boxed{\begin{array}{c}\textbf{Retention obligations are meaningful } \mathbf{only\ because\ side\ information\ CAN\ restore\ a\ collapsed\ distinction.}\\[3pt] \textbf{⛔ If unqualified } P^* \textbf{ held, } K1\text{–}K11 \textbf{ would be pointless: nothing retained could ever restore anything.}\end{array}}$$

⚠️⭐⭐ **Self-correction against `P-64`, one audit later.** `P-64` called `P-08` §17 *"a witnessed
instance of `P*`"*. ⛔ **Too loose.** It is an instance of `P*_alone`, **and its conclusion depends on
`P*` failing in the unqualified form.**

# 9. DDD classification *(§8)*

⭐ `P*_alone` is a **mathematical property of functions.** ⛔ **Not** a domain invariant · **not** a
modeling principle · **not** an architectural rule · **not** a KnowledgeOS discovery.
⭐⭐ **At most it licenses a methodological heuristic** — *"before relying on a reduced view, ask which
distinctions it collapses"* — ⛔ **and a heuristic is not promoted to an invariant because it is useful.**

# 10. Statistical discipline *(§7)*

⛔ No probability, confidence, replication or causal claim. ⭐ `A` and `C` are **different reconstructed
routes within the same corpus; information-flow independence is not established** — the commissioner's
wording, adopted. ⛔ 17 · 1 708 · 960 · 773 file counts are **descriptive over one corpus.**

# 11. Backlog — ⛔ **nothing, fifth audit running**

⭐ One candidate tested: *"the estate's most-unified diagnosis is elementary set theory."* ⛔ **That is
an instance of `EKS-15`** — out-of-corpus-root evidence *(here, standard mathematics)* entering with no
admission procedure — **not a new problem.** ⭐⭐ `ES-005.4` puts an instance on the existing record.

# 12. Status register
**`[EMP]`** ⭐ corpus usage counts against a control of 1 815 · **no corpus definition** of *collapse* or
*recover* · `factorization through` in **17** files, none stating the lemma.
**`[THM]`** ⭐⭐ **`P*_alone`, proved in §5** — ⛔ **elementary set theory, cited not owned.**
**`[REFUTED]`** ⭐⭐⭐ **`P*` without the *"alone"* qualifier** *(Model B)* · **`P-64`'s "three routes to
`P*`"** · `P-64`'s *"`P-08` §17 is an instance of `P*`"*.
**`[QUALIFIED]`** ⭐⭐ the verdict itself — provable **and** analytic, reported as conditional because the
qualifier is load-bearing · `A ↔ C` independence, `[UNRECORDABLE]`.
**`[UNDETERMINED]`** ⚠️ `P*` under non-deterministic projection — **does not type**, so cannot be tested.
**`[OPEN]`** ⭐ which distinctions must be preserved (`Dist_EC`, `C_KOS`) · the four guarded invariants vs
the eleven cells · `𝒳_R`/`OQ-1` · `H2`/`H3` · granularity · all carried opens · ⛔ **well-foundedness /
terminality / acyclicity — DEFERRED.**
**Governance:** `|K| = 11` **`[REC]`** — unchanged, unfrozen.

### 13. Consequence for the persistence kernel *(§E)*

⭐ **Changes nothing for minimality.** ⭐⭐ **Provides a general methodological principle**, correctly
scoped and cheap. ⛔ **Supplies no theorem the programme can claim.** ⭐⭐⭐ **And creates one new
obligation to investigate:** the kernel's justification currently reads as though loss were the point,
when in fact **recoverability-with-side-information is the point** — the cells exist to *be* that side
information.

### 14. ONE NEXT UNRESOLVED QUESTION

$$\boxed{\textbf{Do the eleven cells constitute } \mathbf{SUFFICIENT\ side\ information\ }Z \textbf{ to restore every distinction the estate's witnessed transitions collapse?}}$$

⭐⭐⭐ **This is the first well-posed *sufficiency* question in the series, and §6's Model `B` is what
makes it askable.** ⭐ The `Z` is named — the eleven cells. The collapsing projections are named — the
transitions `τ1`…`τ11`. The distinctions are named — the loss table's **FATAL** rows. ⛔ **No undefined
contract is required**, because the estate already adjudicated which losses were fatal.
⚠️ **And sufficiency is not minimality:** a positive answer would say the kernel **works**, ⛔ **never
that it is the smallest thing that works** — and `P-08`'s own §18 table has four rows marked
**UNKNOWN/ACCEPTABLE** that must not be quietly promoted to FATAL to make the answer come out yes.

```
[QUALIFIED] CONDITIONAL — P* IS PROVABLE UNDER EXACTLY ONE ADDED ASSUMPTION AND FALSE WITHOUT IT.

Formalized: P : X -> Y, a distinction d : X -> D with |D| >= 2. P COLLAPSES d at (x1,x2) iff P(x1)=P(x2)
and d(x1) != d(x2). d is RECOVERABLE FROM P ALONE iff there is r : Y -> D with r . P = d. Then
P*_alone: if P collapses d at some pair, d is not recoverable from P alone.

PROOF: suppose r . P = d. Then d(x1) = r(P(x1)) = r(P(x2)) = d(x2), contradicting d(x1) != d(x2). The
only substantive step is the middle equality, and it holds because r is a FUNCTION. No property of
knowledge, representation, persistence or KnowledgeOS is used anywhere. Standard form: d factors through
P iff ker P is contained in ker d; P*_alone is its contrapositive. So it is a theorem of elementary set
theory, and it belongs to nobody here. It is simultaneously analytic — every line unpacks a definition —
which is not a criticism: a true, cheap, correctly-scoped lemma is worth more than an impressive unproved
one.

COUNTERMODEL B IS DECISIVE. Recovery from (P(x), Z) with Z = d(x) and r'(y,z) = z recovers trivially, so
P* WITHOUT the qualifier is FALSE. The word "alone" is not decoration; it carries the whole proposition.
Model C does not even type — collapse was defined by equality of images, which a relation does not have.
Model D is NOT a countermodel: an encoding that separates x1 and x2 does not collapse d, so the
antecedent fails; it is recorded because manufacturing it as a refutation would be dishonest.

THE CONSEQUENCE REVERSES P-64's READING. Two of the three routes only make sense because P* fails without
its qualifier: Part XIX's Recover(P(K)) contains Dist_EC(K) is an ADEQUACY requirement that presupposes
required distinctions ARE recoverable, and P-08 §17's conclusion — therefore retain prior value and
warrant — likewise requires recovery to be possible. So P-64's "three routes to P*" is REFUTED: one route
instantiates it, two rely on its unqualified form failing. And P-64's "P-08 §17 is a witnessed instance
of P*" was too loose.

RETENTION OBLIGATIONS ARE MEANINGFUL ONLY BECAUSE SIDE INFORMATION CAN RESTORE A COLLAPSED DISTINCTION.
If unqualified P* held, K1-K11 would be pointless: nothing retained could ever restore anything. The
kernel is an instance of P*'s FAILURE, not of P*.

P*_alone does NOT imply that the eleven cells are necessary. It says nothing about WHICH distinctions
must be preserved — that is the contract question, and P-55 and P-59 established it is undefined. No
minimality claim is derivable and none is made.

DDD: P*_alone is a mathematical property of functions. Not a domain invariant, not an architectural rule,
not a KnowledgeOS discovery. At most it licenses a methodological heuristic, and a heuristic is not
promoted to an invariant because it is useful.

The commission's five categories are exhaustive but not exclusive: under its qualifier P* is provable AND
analytic. [QUALIFIED] CONDITIONAL is reported because the qualifier is load-bearing.

Corpus usage is abundant but no corpus document DEFINES collapse or recover for this proposition, so the
formalization is mine and is stated so it can be disputed. "factorization through" appears in 17 files,
none stating the lemma — verified by re-grep after an extraction artifact reported a false zero, the
third consecutive audit in which that fired.

Conceded verbatim: "common-cause derivation" is replaced by "independently reconstructed routes within a
shared corpus; causal independence is [UNRECORDABLE]".

No backlog item, fifth audit running — the one candidate is an instance of EKS-15.

Pre-registered [QUALIFIED] CONDITIONAL. Correct — record 21 of 30.

|K| = 11 UNCHANGED, [REC], UNFROZEN, AND NOTHING PROMOTED TO [THM].

ONE NEXT UNRESOLVED QUESTION: Do the eleven cells constitute SUFFICIENT side information Z to restore
  every distinction the estate's witnessed transitions collapse? This is the first well-posed SUFFICIENCY
  question in the series, and Model B is what makes it askable: Z is named (the eleven cells), the
  collapsing projections are named (tau_1 to tau_11), and the distinctions are named (the loss table's
  FATAL rows), so no undefined contract is required. And sufficiency is not minimality: a positive answer
  would say the kernel WORKS, never that it is the smallest thing that works — and P-08 §18 has four rows
  marked UNKNOWN or ACCEPTABLE that must not be quietly promoted to FATAL to make the answer come out yes.

NO three_model_convergence INSPECTION — excluded by path from every command, unread.
```
